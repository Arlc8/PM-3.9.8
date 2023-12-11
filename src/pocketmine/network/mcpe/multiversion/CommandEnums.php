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

namespace pocketmine\network\mcpe\multiversion;

use pocketmine\network\mcpe\protocol\ProtocolInfo;

abstract class CommandEnums {

	private static $commandArgTypes = [
		ProtocolInfo::PROTOCOL_280 => [
			"ARG_TYPE_INT" => 0x01,
			"ARG_TYPE_FLOAT" => 0x02,
			"ARG_TYPE_VALUE" => 0x03,
			"ARG_TYPE_TARGET" => 0x06,
			"ARG_TYPE_STRING" => 0x18,
			"ARG_TYPE_POSITION" => 0x1a,
			"ARG_TYPE_RAWTEXT" => 0x1d,
			"ARG_TYPE_TEXT" => 0x1f,
			"ARG_TYPE_JSON" => 0x22,
			"ARG_TYPE_COMMAND" => 0x29,
		]
	];

    /**
     * @param string $argTypeName
     * @param int $playerProtocol
     * 
     * @return int
     */
	public static function getCommandArgType(string $argTypeName, int $playerProtocol) : int{
		foreach (self::$commandArgTypes as $protocol => $types) {
			if ($playerProtocol >= $protocol) {
				return isset($types[$argTypeName]) ? $types[$argTypeName] : 0;
			}
		}

		return 0;
	}

}
