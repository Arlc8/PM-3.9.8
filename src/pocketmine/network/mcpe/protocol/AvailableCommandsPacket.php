<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\multiversion\CommandEnums;
use pocketmine\network\mcpe\NetworkSession;

class AvailableCommandsPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::AVAILABLE_COMMANDS_PACKET;

	public const ARG_FLAG_VALID = 0x100000;
	public const ARG_FLAG_ENUM = 0x200000;

	public const ARG_TYPE_INT = "ARG_TYPE_INT";
	public const ARG_TYPE_FLOAT = "ARG_TYPE_FLOAT";
	public const ARG_TYPE_VALUE = "ARG_TYPE_VALUE";
	public const ARG_TYPE_TARGET = "ARG_TYPE_TARGET";
	public const ARG_TYPE_STRING = "ARG_TYPE_STRING";
	public const ARG_TYPE_POSITION = "ARG_TYPE_POSITION";
	public const ARG_TYPE_RAWTEXT = "ARG_TYPE_RAWTEXT";
	public const ARG_TYPE_TEXT = "ARG_TYPE_TEXT";
	public const ARG_TYPE_JSON = "ARG_TYPE_JSON";
	public const ARG_TYPE_COMMAND = "ARG_TYPE_COMMAND";

	static private $commandsBuffer = [];
	static private $commandsBufferDefault = "";

	public $commands;

	protected function decodePayload(){

	}

	protected function encodePayload(){
		foreach (self::$commandsBuffer as $playerProtocol => $data) {
			if ($this->getProtocol() >= $playerProtocol) {
				$this->put($data);
				return;
			}
		}

		$this->putString(self::$commandsBufferDefault);
	}

    /**
     * @param array $commands
     * 
     * @return void
     */
	public static function prepareCommands(array $commands) : void{
		self::$commandsBufferDefault = json_encode($commands);

		$enumValues = [];
		$enumValuesCount = 0;
		$enums = [];
		$enumsCount = 0;
		$commandsStreams = [
			ProtocolInfo::PROTOCOL_360 => new NetworkBinaryStream(),
			ProtocolInfo::PROTOCOL_361 => new NetworkBinaryStream()
		];

		foreach ($commands as $commandName => &$commandData) { // Replace &$commandData with $commandData when alises fix for 1.2 won't be needed anymore
		    
			$commandsStream = new NetworkBinaryStream();

			$commandsStream->putString(strtolower($commandName));
			$commandsStream->putString($commandData['versions'][0]['description']);

		    foreach ($commandsStreams as $protocol => $unused) {
			    /** @IMPORTANT $commandsStream doesn't should use after this line */
				$commandsStreams[$protocol]->put($commandsStream->getBuffer());
				$commandsStreams[$protocol]->putByte(0); // flags

			    $permission = 0;//AdventureSettingsPacket::COMMAND_PERMISSION_LEVEL_ANY;
			    switch ($commandData['versions'][0]['permission']) {
				    case "staff":
					    $permission = 1;//AdventureSettingsPacket::COMMAND_PERMISSION_LEVEL_GAME_MASTERS;
					    default;
			    }
			    $commandsStreams[$protocol]->putByte($permission); // permission level

			    if (isset($commandData['versions'][0]['aliases']) && !empty($commandData['versions'][0]['aliases'])) {
				    foreach ($commandData['versions'][0]['aliases'] as $alias) {
					    $aliasAsCommand = $commandData;
					    $aliasAsCommand['versions'][0]['aliases'] = [];

					    $commands[$alias] = $aliasAsCommand;
				    }

				    $commandData['versions'][0]['aliases'] = [];
			    }

			    $aliasesEnumId = -1; // temp aliases fix for 1.2

			    $commandsStreams[$protocol]->putLInt($aliasesEnumId);
			    $commandsStreams[$protocol]->putUnsignedVarInt(count($commandData['versions'][0]['overloads'])); // overloads
			}

			foreach ($commandData['versions'][0]['overloads'] as $overloadData) {
				$paramNum = count($overloadData['input']['parameters']);
				foreach ($commandsStreams as $protocol => $unused) {
					$commandsStreams[$protocol]->putUnsignedVarInt($paramNum);
				}

				foreach ($overloadData['input']['parameters'] as $paramData) {
					// rawtext type cause problems on some types of clients
					$isParamOneAndOptional = ($paramNum == 1 && isset($paramData['optional']) && $paramData['optional']);
					if ($paramData['type'] == "rawtext" && ($paramNum > 1 || $isParamOneAndOptional)) {
						$paramData['type'] = "string";
					}

					if ($paramData['type'] == "stringenum") {
						 $enums[$enumsCount]['name'] = $paramData['name'];
						 $enums[$enumsCount]['data'] = [];

						 foreach ($paramData['enum_values'] as $enumElem) {
							 $enumValues[$enumValuesCount] = $enumElem;
							 $enums[$enumsCount]['data'][] = $enumValuesCount;

							 $enumValuesCount++;
						 }

						 $enumsCount++;
                    }

					foreach ($commandsStreams as $protocol => $unused) {
						$commandsStreams[$protocol]->putString($paramData['name']);
						if ($paramData['type'] == "stringenum") {
                            $commandsStreams[$protocol]->putLInt(self::ARG_FLAG_VALID | self::ARG_FLAG_ENUM | ($enumsCount - 1));
                        } else {
							$commandsStreams[$protocol]->putLInt(self::ARG_FLAG_VALID | self::getFlag($protocol, $paramData['type']));
                        }

						$commandsStreams[$protocol]->putBool(isset($paramData['optional']) && $paramData['optional']);
						$commandsStreams[$protocol]->putByte(0);
					}
				}
			}
		}

		$additionalDataStream = new NetworkBinaryStream();
		$additionalDataStream->putUnsignedVarInt($enumValuesCount);
		for ($i = 0; $i < $enumValuesCount; $i++) {
			$additionalDataStream->putString($enumValues[$i]);
		}

		$additionalDataStream->putUnsignedVarInt(0);
		$additionalDataStream->putUnsignedVarInt($enumsCount);
		for ($i = 0; $i < $enumsCount; $i++) {
			$additionalDataStream->putString($enums[$i]['name']);
			$dataCount = count($enums[$i]['data']);
			$additionalDataStream->putUnsignedVarInt($dataCount);

			for ($j = 0; $j < $dataCount; $j++) {
				if ($enumValuesCount < 256) {
					$additionalDataStream->putByte($enums[$i]['data'][$j]);
				} else if ($enumValuesCount < 65536) {
					$additionalDataStream->putLShort($enums[$i]['data'][$j]);
				} else {
					$additionalDataStream->putLInt($enums[$i]['data'][$j]);
				}
			}
		}
		$additionalDataStream->putUnsignedVarInt(count($commands));

		foreach ($commandsStreams as $protocol => $commandsStream) {
			$commandsStream->putUnsignedVarInt(0);
			self::$commandsBuffer[$protocol] = $additionalDataStream->getBuffer() . $commandsStream->getBuffer();
		}

		krsort(self::$commandsBuffer);
	}

	/**
	 * @param int $playerProtocol
	 * @param string $paramName
	 * 
	 * @return int
	 */
    private static function getFlag(int $playerProtocol, string $paramName) : int{
		// new in 1.6
		// 05 - operator
	    $typeName = "";
	    switch ($paramName) {
		    case "int":
				$typeName = self::ARG_TYPE_INT;
			    break;
		    case "float":
			    $typeName = self::ARG_TYPE_FLOAT;
			    break;
		    case "mixed":
		    case "value":
			    $typeName = self::ARG_TYPE_VALUE;
			    break;
		    case "target":
			    $typeName = self::ARG_TYPE_TARGET;
			    break;
		    case "string":
			    $typeName = self::ARG_TYPE_STRING;
			    break;
		    case "xyz":
		    case "x y z":
			    $typeName = self::ARG_TYPE_POSITION;
			    break;
		    case "rawtext":
		    case "message":
			    $typeName = self::ARG_TYPE_RAWTEXT;
			    break;
		    case "text":
			    $typeName = self::ARG_TYPE_TEXT;
			    break;
		    case "json":
			    $typeName = self::ARG_TYPE_JSON;
			    break;
		    case "command":
			    $typeName = self::ARG_TYPE_COMMAND;
			    break;
		    default:
			    return 0;
	    }

	    return CommandEnums::getCommandArgType($typeName, $playerProtocol);
    }

	public function handle(NetworkSession $session) : bool{
		return $session->handleAvailableCommands($this);
	}
}
