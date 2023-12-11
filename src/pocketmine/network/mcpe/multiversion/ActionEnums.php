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

abstract class ActionEnums {

	private const GROUP_1 = 1;
	private const GROUP_2 = 2;

	private static $playerActionType = [
		self::GROUP_2 => [
			-1 => 'UNKNOWN',
			0 => 'START_DESTROY_BLOCK',
			1 => 'ABORT_DESTROY_BLOCK',
			2 => 'STOP_DESTROY_BLOCK',
			3 => 'GET_UPDATED_BLOCK',
			4 => 'DROP_ITEM',
			5 => 'START_SLEEPING',
			6 => 'STOP_SLEEPING',
			7 => 'RESPAWN',
			8 => 'START_JUMP',
			9 => 'START_SPRINTING',
			10 => 'STOP_SPRINTING',
			11 => 'START_SNEAKING',
			12 => 'STOP_SNEAKING',
			13 => 'CHANGE_DEMENSION',
			14 => 'CHANGE_DEMENSION_ACK',
			15 => 'START_GLIDING',
			16 => 'STOP_GLIDING',
			17 => 'DENY_DESTROY_BLOCK',
			18 => 'CRACK_BLOCK',
			19 => 'CHANGE_SKIN',
			20 => 'UPDATE_ENCHANTING_SEED',
			21 => 'START_SWIMMING',
			22 => 'STOP_SWIMMING',
			23 => 'START_SPIN_ATTACK',
			24 => 'STOP_SPIN_ATTACK',
			25 => 'INTERACT_WITH_BLOCK'
		]
	];

    /**
     * @param int $playerProtocol
     * @param int $actionId
     * 
     * @return string
     */
    public static function getPlayerAction(int $playerProtocol, int $actionId) : string{
		$groupKey = self::getActionKeyByProtocol($playerProtocol);
		if (!isset(self::$playerActionType[$groupKey][$actionId])) {
			return self::$playerActionType[$groupKey][-1];
		}

		return self::$playerActionType[$groupKey][$actionId];
	}

    /**
     * @param int $playerProtocol
     * @param string $actionName
     * 
     * @return int
     */
	public static function getPlayerActionId(int $playerProtocol, string $actionName) : int{
		$groupKey = self::getActionKeyByProtocol($playerProtocol);
		foreach (self::$playerActionType[$groupKey] as $key => $value) {
			if ($value === $actionName) {
				return $key;
			}
		}

		return -1;
	}

    /**
     * @param int $playerProtocol
     * 
     * @return int
     */
	private static function getActionKeyByProtocol(int $playerProtocol) : int{
		switch ($playerProtocol) {
			case ProtocolInfo::PROTOCOL_361:
			case ProtocolInfo::PROTOCOL_360:
				return self::GROUP_2;
			default:
				return self::GROUP_2;
		}
	}

}
