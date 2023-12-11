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

abstract class ParticleEnums {

	private static $particleIds = [
		ProtocolInfo::PROTOCOL_360 => [
			"TYPE_BUBBLE" => 1,
			"TYPE_CRITICAL" => 3,
			"TYPE_SMOKE" => 5,
			"TYPE_EXPLODE" => 6,
			"TYPE_WHITE_SMOKE" => 7,
			"TYPE_FLAME" => 8,
			"TYPE_LAVA" => 9,
			"TYPE_LARGE_SMOKE" => 10,
			"TYPE_REDSTONE" => 11,
			"TYPE_ITEM_BREAK" => 13,
			"TYPE_SNOWBALL_POOF" => 14,
			"TYPE_LARGE_EXPLODE" => 15,
			"TYPE_HUGE_EXPLODE" => 16,
			"TYPE_MOB_FLAME" => 17,
			"TYPE_HEART" => 18,
			"TYPE_TERRAIN" => 19,
			"TYPE_TOWN_AURA" => 20,
			"TYPE_PORTAL" => 21,
			"TYPE_WATER_SPLASH" => 23,
			"TYPE_WATER_WAKE" => 25,
			"TYPE_DRIP_WATER" => 26,
			"TYPE_DRIP_LAVA" => 27,
			"TYPE_DUST" => 28,
			"TYPE_MOB_SPELL" => 29,
			"TYPE_MOB_SPELL_AMBIENT" => 30,
			"TYPE_MOB_SPELL_INSTANTANEOUS" => 31,
			"TYPE_INK" => 32,
			"TYPE_SLIME" => 33,
			"TYPE_RAIN_SPLASH" => 34,
			"TYPE_VILLAGER_ANGRY" => 35,
			"TYPE_VILLAGER_HAPPY" => 36,
			"TYPE_ENCHANTMENT_TABLE" => 37,
			"TYPE_NOTE" => 39,
			"TYPE_WITCH_MAGIC" => 40,
			"TYPE_ICE_CRYSTAL" => 43
		]
	];

    /**
     * @param int $playerProtocol
     * 
     * @return array
     */
    public static function getParticleArrayByProtocol(int $playerProtocol) : array{
		switch ($playerProtocol) {
			case ProtocolInfo::PROTOCOL_361:
			case ProtocolInfo::PROTOCOL_360:
			    return self::$particleIds[ProtocolInfo::PROTOCOL_360];
			default:
				return self::$particleIds[ProtocolInfo::PROTOCOL_360];
		}
    }

}
