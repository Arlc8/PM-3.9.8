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

abstract class TextEnums {

	private const GROUP_1 = 1;
	private const GROUP_2 = 2;

	private static $textPacketType = [
		self::GROUP_2 => [
			0 => 'TYPE_RAW',
			1 => 'TYPE_CHAT',
			2 => 'TYPE_TRANSLATION',
			3 => 'TYPE_POPUP',
			4 => 'JUKEBOX_POPUP',
			5 => 'TYPE_TIP',
			6 => 'TYPE_SYSTEM',
			7 => 'TYPE_WHISPER',
			8 => 'TYPE_ANNOUNCEMENT',
		]
	];

    /**
     * @param int $playerProtocol
     * @param int $typeId
     * 
     * @return string
     */
	public static function getMessageType(int $playerProtocol, int $typeId) : string{
		$groupKey = self::getTextKeyByProtocol($playerProtocol);
		if (!isset(self::$textPacketType[$groupKey][$typeId])) {
			return self::$textPacketType[$groupKey][0];
		}

		return self::$textPacketType[$groupKey][$typeId];
	}

    /**
     * @param int $playerProtocol
     * @param string $typeName
     * 
     * @return int
     */
	public static function getMessageTypeId(int $playerProtocol, string $typeName) : int{
		$groupKey = self::getTextKeyByProtocol($playerProtocol);
		foreach (self::$textPacketType[$groupKey] as $key => $value) {
			if ($value === $typeName) {
				return $key;
			}
		}

		return 0;
	}

    /**
     * @param int $playerProtocol
     * 
     * @return int
     */
    private static function getTextKeyByProtocol(int $playerProtocol) : int{
		switch ($playerProtocol) {
			case ProtocolInfo::PROTOCOL_361:
			case ProtocolInfo::PROTOCOL_360:
				return self::GROUP_2;
			default:
				return self::GROUP_2;
		}
	}

}
