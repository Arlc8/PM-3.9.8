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

use pocketmine\utils\Binary;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\multiversion\SoundEnums;
use pocketmine\network\mcpe\NetworkSession;

class LevelSoundEventPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::LEVEL_SOUND_EVENT_PACKET;

	public const SOUND_LARGE_BLAST = 'SOUND_LARGE_BLAST'; //for firework
	public const SOUND_TWINKLE = 'SOUND_TWINKLE'; //for firework
	public const SOUND_BLAST = 'SOUND_BLAST'; //for firework
	public const SOUND_LAUNCH = 'SOUND_LAUNCH'; //for firework

    public const SOUND_ENDERCHEST_OPEN = 'SOUND_ENDER_CHEST_OPEN';
    public const SOUND_ENDERCHEST_CLOSED = 'SOUND_ENDER_CHEST_CLOSED';
    public const SOUND_BUCKET_FILL_WATER = 'SOUND_BUCKET_FILL_WATER';
    public const SOUND_BUCKET_EMPTY_WATER = 'SOUND_BUCKET_EMPTY_WATER';
 	public const SOUND_CHEST_OPEN = 'SOUND_CHEST_OPEN';
 	public const SOUND_CHEST_CLOSED = 'SOUND_CHEST_CLOSED';
 	public const SOUND_BUCKET_FILL_LAVA = 'SOUND_BUCKET_FILL_LAVA';
 	public const SOUND_BUCKET_EMPTY_LAVA = 'SOUND_BUCKET_EMPTY_LAVA';
 	public const SOUND_GLASS = 'SOUND_GLASS';
	public const SOUND_IGNITE = 'SOUND_IGNITE';
	public const SOUND_THROW = 'SOUND_THROW';
	public const SOUND_BOW = 'SOUND_BOW';
	public const SOUND_BOW_HIT = 'SOUND_BOW_HIT';
 	public const SOUND_EXPLODE = 'SOUND_EXPLODE';
	public const SOUND_BREAK = 'SOUND_BREAK';
	public const SOUND_LEVELUP = 'SOUND_LEVEL_UP';
	public const SOUND_PLACE = 'SOUND_PLACE';

	public const SOUND_UNDEFINED = 'SOUND_UNDEFINED';

	/** @var int */
	public $sound;
	/** @var Vector3 */
	public $position;
	/** @var int */
	public $extraData = -1;
	/** @var string */
	public $entityType = ":"; //???
	/** @var bool */
	public $isBabyMob = false; //...
	/** @var bool */
	public $disableRelativeVolume = false;

	protected function decodePayload(){
		$this->sound = $this->getUnsignedVarInt();
		$this->sound = SoundEnums::getLevelSoundEventName($this->getProtocol(), $this->sound);
		$this->position = $this->getVector3();
		$this->extraData = $this->getVarInt();
		$this->entityType = $this->getString();
		$this->isBabyMob = (($this->get(1) !== "\x00"));
		$this->disableRelativeVolume = (($this->get(1) !== "\x00"));
	}

	protected function encodePayload(){
	    $sound = SoundEnums::getLevelSoundEventId($this->getProtocol(), $this->sound);
		$this->putUnsignedVarInt($sound);
		$this->putVector3($this->position);
		$this->putVarInt($this->extraData);
		$this->putString($this->entityType);
		($this->buffer .= ($this->isBabyMob ? "\x01" : "\x00"));
		($this->buffer .= ($this->disableRelativeVolume ? "\x01" : "\x00"));
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleLevelSoundEvent($this);
	}
}
