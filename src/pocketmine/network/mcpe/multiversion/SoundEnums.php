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

abstract class SoundEnums {

	private const GROUP_1 = 1;
	private const GROUP_2 = 2;
	private const GROUP_3 = 3;

	private static $levelSoundEvent = [
		self::GROUP_3 => [
			0 => 'SOUND_USE_ITEM_ON',
			1 => 'SOUND_HIT',
			2 => 'SOUND_STEP',
			3 => 'SOUND_FLY',
			4 => 'SOUND_JUMP',
			5 => 'SOUND_BREAK',
			6 => 'SOUND_PLACE',
			7 => 'SOUND_HEAVY_STEP',
			8 => 'SOUND_GALLOP',
			9 => 'SOUND_FALL',
			10 => 'SOUND_AMBIENT',
			11 => 'SOUND_AMBIENT_BABY',
			12 => 'SOUND_AMBIENT_IN_WATER',
			13 => 'SOUND_BREATHE',
			14 => 'SOUND_DEATH',
			15 => 'SOUND_DEATH_IN_WATER',
			16 => 'SOUND_DEATH_TO_ZOMBIE',
			17 => 'SOUND_HURT',
			18 => 'SOUND_HURT_IN_WATER',
			19 => 'SOUND_MAD',
			20 => 'SOUND_BOOST',
			21 => 'SOUND_BOW',
			22 => 'SOUND_SQUISH_BIG',
			23 => 'SOUND_SQUISH_SMALL',
			24 => 'SOUND_FALL_BIG',
			25 => 'SOUND_FALL_SMALL',
			26 => 'SOUND_SPLASH',
			27 => 'SOUND_FIZZ',
			28 => 'SOUND_FLAP',
			29 => 'SOUND_SWIM',
			30 => 'SOUND_DRINK',
			31 => 'SOUND_EAT',
			32 => 'SOUND_TAKE_OFF',
			33 => 'SOUND_SHAKE',
			34 => 'SOUND_PLOP',
			35 => 'SOUND_LAND',
			36 => 'SOUND_SADDLE',
			37 => 'SOUND_ARMOR',
			38 => 'SOUND_ARMOR_PLACE',
			39 => 'SOUND_ADD_CHEST',
			40 => 'SOUND_THROW',
			41 => 'SOUND_ATTACK',
			42 => 'SOUND_ATTACK_NO_DAMAGE',
			44 => 'SOUND_ATTACK_STRONG',
			44 => 'SOUND_WARN',
			45 => 'SOUND_SHEAR',
			46 => 'SOUND_MILK',
			47 => 'SOUND_THUNDER',
			48 => 'SOUND_EXPLODE',
			49 => 'SOUND_FIRE',
			50 => 'SOUND_IGNITE',
			51 => 'SOUND_FUSE',
			52 => 'SOUND_STARE',
			53 => 'SOUND_SPAWN',
			54 => 'SOUND_SHOOT',
			55 => 'SOUND_BREAK_BLOCK',
			56 => 'SOUND_LAUNCH',
			57 => 'SOUND_BLAST',
			58 => 'SOUND_LARGE_BLAST',
			59 => 'SOUND_TWINKLE',
			60 => 'SOUND_REMEDY',
			61 => 'SOUND_UNFECT',
			62 => 'SOUND_LEVEL_UP',
			63 => 'SOUND_BOW_HIT',
			64 => 'SOUND_BULLET_HIT',
			65 => 'SOUND_EXTINGUISH_FIRE',
			66 => 'SOUND_ITEM_FIZZ',
			67 => 'SOUND_CHEST_OPEN',
			68 => 'SOUND_CHEST_CLOSED',
			69 => 'SOUND_SHULKER_BOX_OPEN',
			70 => 'SOUND_SHULKERBOXCLOSED',
			71 => 'SOUND_ENDER_CHEST_OPEN',
			72 => 'SOUND_ENDER_CHEST_CLOSED',
			73 => 'SOUND_POWER_ON',
			74 => 'SOUND_POWER_OFF',
			75 => 'SOUND_ATTACH',
			76 => 'SOUND_DETACH',
			77 => 'SOUND_DENY',
			78 => 'SOUND_TRIPOD',
			79 => 'SOUND_POP',
			80 => 'SOUND_DROP_SLOT',
			81 => 'SOUND_NOTE',
			82 => 'SOUND_THORNS',
			83 => 'SOUND_PISTON_IN',
			84 => 'SOUND_PISTON_OUT',
			85 => 'SOUND_PORTAL',
			86 => 'SOUND_WATER',
			87 => 'SOUND_LAVA_POP',
			88 => 'SOUND_LAVA',
			89 => 'SOUND_BURP',
			90 => 'SOUND_BUCKET_FILL_WATER',
			91 => 'SOUND_BUCKET_FILL_LAVA',
			92 => 'SOUND_BUCKET_EMPTY_WATER',
			93 => 'SOUND_BUCKET_EMPTY_LAVA',
			94 => 'SOUND_EQUIP_CHAIN',
			95 => 'SOUND_EQUIP_DIAMOND',
			96 => 'SOUND_EQUIP_GENERIC',
			97 => 'SOUND_EQUIP_GOLD',
			98 => 'SOUND_EQUIP_IRON',
			99 => 'SOUND_EQUIP_LEATHER',
			100 => 'SOUND_EQUIP_ELYTRA',
			101 => 'SOUND_RECORD_13',
			102 => 'SOUND_RECORD_CAT',
			103 => 'SOUND_RECORD_BLOCKS',
			104 => 'SOUND_RECORD_CHIRP',
			105 => 'SOUND_RECORD_FAR',
			106 => 'SOUND_RECORD_MALL',
			107 => 'SOUND_RECORD_MELLOHI',
			108 => 'SOUND_RECORD_STAL',
			109 => 'SOUND_RECORD_STRAD',
			110 => 'SOUND_RECORD_WARD',
			111 => 'SOUND_RECORD_11',
			112 => 'SOUND_RECORD_WAIT',
			113 => 'SOUND_RECORD_NULL',
			114 => 'SOUND_GUARDIAN_FLOP',
			115 => 'SOUND_GUARDIAN_CURSE',
			116 => 'SOUND_MOB_WARNING',
			117 => 'SOUND_MOB_WARNING_BABY',
			118 => 'SOUND_TELEPORT',
			119 => 'SOUND_SHULKER_OPEN',
			120 => 'SOUND_SHULKER_CLOSE',
			121 => 'SOUND_HAGGLE',
			122 => 'SOUND_HAGGLE_YES',
			123 => 'SOUND_HAGGLE_NO',
			124 => 'SOUND_HAGGLE_IDLE',
			125 => 'SOUND_CHORUS_GROW',
			126 => 'SOUND_CHORUS_DEATH',
			127 => 'SOUND_GLASS',
			128 => 'SOUND_POTION_BREWED',
			129 => 'SOUND_CAST_SPELL',
			130 => 'SOUND_PREPARE_ATTACK_SPELL',
			131 => 'SOUND_PREPARE_SUMMON',
			132 => 'SOUND_PREPARE_WOLOLO',
			133 => 'SOUND_FANG',
			134 => 'SOUND_CHARGE',
			135 => 'SOUND_TAKE_PICTURE',
			136 => 'SOUND_PLACELEASHKNOT',
			137 => 'SOUND_BREAK_LEASH_KNOT',
			138 => 'SOUND_AMBIENT_GROWL',
			139 => 'SOUND_AMBIENT_WHINE',
			140 => 'SOUND_AMBIENT_PANT',
			141 => 'SOUND_AMBIENT_PURR',
			142 => 'SOUND_AMBIENT_PURREOW',
			143 => 'SOUND_DEATH_MIN_VOLUME',
			144 => 'SOUND_DEATH_MID_VOLUME',
			145 => 'SOUND_IMITATE_BLAZE',
			146 => 'SOUND_IMITATE_CAVE_SPIDER',
			147 => 'SOUND_IMITATE_CREEPER',
			148 => 'SOUND_IMITATE_ELDER_GUARDIAN',
			149 => 'SOUND_IMITATE_ENDER_DRAGON',
			150 => 'SOUND_IMITATE_ENDERMAN',
			151 => 'SOUND_IMITATE_ENDERMITE',
			152 => 'SOUND_IMITATE_EVOCATIONILLAGER',
			153 => 'SOUND_IMITATE_GHAST',
			154 => 'SOUND_IMITATE_HUSK',
			155 => 'SOUND_IMITATE_ILLUSIONILLAGER',
			156 => 'SOUND_IMITATE_MAGMA_CUBE',
			157 => 'SOUND_IMITATE_POLAR_BEAR',
			158 => 'SOUND_IMITATE_SHULKER',
			159 => 'SOUND_IMITATE_SILVER_FISH',
			160 => 'SOUND_IMITATE_SKELETON',
			161 => 'SOUND_IMITATE_SLIME',
			162 => 'SOUND_IMITATE_SPIDER',
			163 => 'SOUND_IMITATE_STRAY',
			164 => 'SOUND_IMITATE_VEX',
			165 => 'SOUND_IMITATE_VINDICATIONILLAGER',
			166 => 'SOUND_IMITATE_WITCH',
			167 => 'SOUND_IMITATE_WITHER',
			168 => 'SOUND_IMITATE_WITHER_SKELETON',
			169 => 'SOUND_IMITATE_WOLF',
			170 => 'SOUND_IMITATE_ZOMBIE',
			171 => 'SOUND_IMITATE_ZOMBIE_PIGMAN',
			172 => 'SOUND_IMITATE_ZOMBIE_VILLAGER',
			173 => 'SOUND_ENDER_EYE_PLACED',
			174 => 'SOUND_END_PORTAL_CREATED',
			175 => 'SOUND_ANVIL_USE',
			176 => 'SOUND_BOTTLE_DRAGON_BREATH',
			177 => 'SOUND_PORTAL_TRAVEL',
			178 => 'SOUND_TRIDENT_HIT',
			179 => 'SOUND_TRIDENT_RETURN',
			180 => 'SOUND_TRIDENT_RIPTIDE_1',
			181 => 'SOUND_TRIDENT_RIPTIDE_2',
			182 => 'SOUND_TRIDENT_RIPTIDE_3',
			183 => 'SOUND_TRIDENT_THROW',
			184 => 'SOUND_TRIDENT_THUNDER',
			185 => 'SOUND_TRIDENT_HIT_GROUND',
			188 => 'SOUND_ELEM_CONSTRUCT_OPEN',
			189 => 'SOUND_ICE_BOMB_HIT',
			190 => 'SOUND_BALLON_POP',
			191 => 'SOUND_LT_REACTION_ICE_BOMB',
			192 => 'SOUND_LT_REACTION_BLEACH',
			193 => 'SOUND_LT_REACTION_ELEPHANT_TOOTHPASTE',
			194 => 'SOUND_LT_REACTION_ELEPHANT_TOOTHPASTE2',
			195 => 'SOUND_LT_REACTION_GLOW_STICK',
			196 => 'SOUND_LT_REACTION_GLOW_STICK2',
			197 => 'SOUND_LT_REACTION_LUMINOL',
			198 => 'SOUND_LT_REACTION_SALT',
			199 => 'SOUND_LT_REACTION_FERILIZER',
			200 => 'SOUND_LT_REACTION_FIREBALL',
			201 => 'SOUND_LT_REACTION_MAGNESIUM_SALT',
			202 => 'SOUND_LT_REACTION_MISC_FIRE',
			203 => 'SOUND_LT_REACTION_FIRE',
			204 => 'SOUND_LT_REACTION_MISC_EXPLOSION',
			205 => 'SOUND_LT_REACTION_MISC_MYSTICAL',
			206 => 'SOUND_LT_REACTION_MISC_MYSTICAL2',
			207 => 'SOUND_LT_REACTION_PRODUCT',
			208 => 'SOUND_SPARKLER_USE',
			209 => 'SOUND_GLOW_STICK_USE',
			210 => 'SOUND_SPARKLER_ACTIVATE',
			211 => 'SOUND_CONVERT_TO_DROWNED',
			212 => 'SOUND_BUCKET_FILL_FISH',
			213 => 'SOUND_BUCKET_EMPTY_FISH',
			214 => 'SOUND_BUBBLE_COLUMN_UPWARDS',
			215 => 'SOUND_BUBBLE_COLUMN_DOWNWARDS',
			216 => 'SOUND_BUBBLE_POP',
			217 => 'SOUND_BUBBLE_UP_INSIDE',
			218 => 'SOUND_BUBBLE_DOWN_INSIDE',
			219 => 'SOUND_HURT_BABY',
			220 => 'SOUND_DEATH_BABY',
			221 => 'SOUND_STEP_BABY',
			222 => 'SOUND_SPAWN_BABY',
			223 => 'SOUND_BORN',
			224 => 'SOUND_TURTLE_EGG_BREAk',
			225 => 'SOUND_TURTLE_EGG_CRACK',
			226 => 'SOUND_TURTLE_EGG_HATCHED',
			227 => 'SOUND_LAY_EGG',
			228 => 'SOUND_TURTLE_EGG_ATTACKED',
			229 => 'SOUND_BEACON_ACTIVATE',
			230 => 'SOUND_BEACON_AMBIENT',
			231 => 'SOUND_BEACON_DEACTIVATE',
			232 => 'SOUND_BEACON_POWER',
			233 => 'SOUND_COUDUIT_ACTIVATE',
			234 => 'SOUND_COUDUIT_AMBIENT',
			235 => 'SOUND_COUDUIT_ATTACK',
			236 => 'SOUND_COUDUIT_DEACTIVATE',
			237 => 'SOUND_COUDUIT_SHORT',
			238 => 'SOUND_DEFAULT',
			239 => 'SOUND_UNDEFINED'
		]
	];

    /**
     * @param int $playerProtocol
     * @param int $eventId
     * 
     * @return string
     */
	public static function getLevelSoundEventName(int $playerProtocol, int $eventId) : string{
		$groupKey = self::getSoundKeyByProtocol($playerProtocol);
		if (!isset(self::$levelSoundEvent[$groupKey][$eventId])) {
			return end(self::$levelSoundEvent);
		}

		return self::$levelSoundEvent[$groupKey][$eventId];
	}

    /**
     * @param int $playerProtocol
     * @param string $eventName
     * 
     * @return int
     */
	public static function getLevelSoundEventId(int $playerProtocol, string $eventName) : int{
		$groupKey = self::getSoundKeyByProtocol($playerProtocol);
		foreach (self::$levelSoundEvent[$groupKey] as $key => $value) {
			if ($value === $eventName) {
				return $key;
			}
		}

		return count(self::$levelSoundEvent[$groupKey]) - 1;
	}

    /**
     * @param int $playerProtocol
     * 
     * @return int
     */
	private static function getSoundKeyByProtocol(int $playerProtocol) : int{
		switch ($playerProtocol) {
			case ProtocolInfo::PROTOCOL_361:
			case ProtocolInfo::PROTOCOL_360:
				return self::GROUP_3;
			default:
				return self::GROUP_3;
		}
	}

}
