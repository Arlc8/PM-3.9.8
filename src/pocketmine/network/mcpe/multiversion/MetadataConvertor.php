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

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class MetadataConvertor {

	private static $initialMetadata = [];

	private static $diffEntityFlags340 = [
		'DATA_FLAG_RESTING_BAT' => 23,
		'DATA_FLAG_ANIMAL_SIT' => 24,
		'DATA_FLAG_ANGRY_WOLF' => 25,
		'DATA_FLAG_INTERESTED' => 26,
		'DATA_FLAG_ANGRY_BLAZE' => 27,
		'DATA_FLAG_TAME_WOLF' => 28,
		'DATA_FLAG_LEASHED' => 30,
		'DATA_FLAG_SHAVED_SHIP' => 31,
		'DATA_FLAG_FALL_FLYING' => 32,
		'DATA_FLAG_ELDER_GUARDIAN' => 33,
		'DATA_FLAG_MOVING' => 34,
		'DATA_FLAG_NOT_IN_WATER' => 35,
		'DATA_FLAG_CHESTED_MOUNT' => 36,
		'DATA_FLAG_STACKABLE' => 37,
		'DATA_FLAG_IS_WASD_CONTROLLED' => 44,
		'DATA_FLAG_CAN_POWER_JUMP' => 45,
		'DATA_FLAG_HAS_COLLISION' => 47,
		'DATA_FLAG_AFFECTED_BY_GRAVITY' => 48,
		'DATA_FLAG_FIRE_IMMUNE' => 49,
        'DATA_FLAG_SWIMMING' => 56,
        'DATA_FLAG_BLOCKING' => 71,
	];
	private static $entityFlags340 = [];

	private static $diffEntityMetadataIds360 = [
		'DATA_PLAYER_FLAGS' => 26,
		'DATA_PLAYER_BED_POSITION' => 28,
		'DATA_LEAD_HOLDER' => 37,
		'DATA_SCALE' => 38,
		'DATA_BUTTON_TEXT' => 99,
		'DATA_MAX_AIR' => 42,
		'DATA_WIDTH' => 53,
		'DATA_HEIGHT' => 54,
		'DATA_EXPLODE_TIMER' => 55,
		'DATA_SEAT_RIDER_OFFSET' => 56,
		'DATA_POSE_INDEX' => 78,
	];
	private static $entityMetadataIds360 = [];

	public static function init() : void{
		$oClass = new \ReflectionClass('pocketmine\entity\Entity');
		self::$initialMetadata = $oClass->getConstants();

		foreach (self::$diffEntityFlags340 as $key => $value) {
			if (isset(self::$initialMetadata[$key])) {
				self::$entityFlags340[self::$initialMetadata[$key]] = $value;
			}
		}

		foreach (self::$diffEntityMetadataIds360 as $key => $value) {
			if (isset(self::$initialMetadata[$key])) {
				self::$entityMetadataIds360[self::$initialMetadata[$key]] = $value;
			}
		}
	}

    /**
     * @param array $metadata
     * @param int $playerProtocol
     * 
     * @return array
     */
	public static function updateMeta(array $metadata, int $playerProtocol) : array{
		$metadata = self::updateEntityFlags($metadata, $playerProtocol);
		$metadata = self::updateMetadataIds($metadata, $playerProtocol);

		return $metadata;
	}

    /**
     * @param array $metadata
     * @param int $playerProtocol
     * 
     * @return array
     */
	private static function updateMetadataIds(array $metadata, int $playerProtocol) : array{
		switch ($playerProtocol) {
			case ProtocolInfo::PROTOCOL_361:
			case ProtocolInfo::PROTOCOL_360:
				$protocolMetadata = self::$entityMetadataIds360;
				break;
			default:
				return $metadata;
		}

		$newMetadata = [];
		foreach ($metadata as $key => $value) {
			if (isset($protocolMetadata[$key])) {
				$newMetadata[$protocolMetadata[$key]] = $value;
			} else {
				$newMetadata[$key] = $value;
			}
		}

		return $newMetadata;
	}

    /**
     * @param array $metadata
     * @param int $playerProtocol
     * 
     * @return array
     */
	private static function updateEntityFlags(array $metadata, int $playerProtocol) : array{
		if (!isset($metadata[Entity::DATA_FLAGS])) {
			return $metadata;
		}

		switch ($playerProtocol) {
			case ProtocolInfo::PROTOCOL_361:
			case ProtocolInfo::PROTOCOL_360:
				$newFlags = 1 << 19; //DATA_FLAG_CAN_CLIMBING
				$protocolFlags = self::$entityFlags340;
				break;
			default:
				return $metadata;
		}

		$flags = strrev(decbin($metadata[Entity::DATA_FLAGS][1]));
		$flagsLength = strlen($flags);
		for ($i = 0; $i < $flagsLength; $i++) {
			if ($flags[$i] === '1') {
				$newFlags |= 1 << (isset($protocolFlags[$i]) ? $protocolFlags[$i] : $i);
			}
		}

		$metadata[Entity::DATA_FLAGS][1] = $newFlags;

		return $metadata;
	}

}
