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

use pocketmine\network\mcpe\multiversion\ResourceEnums;
use pocketmine\network\mcpe\NetworkSession;

class ResourcePackDataInfoPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_DATA_INFO_PACKET;

	public const TYPE_INVALID = 'TYPE_INVALID';
	public const TYPE_ADDON = 'TYPE_ADDON';
	public const TYPE_CACHED = 'TYPE_CACHED';
	public const TYPE_COPY_PROTECTED = 'TYPE_COPY_PROTECTED';
	public const TYPE_BEHAVIOR = 'TYPE_BEHAVIOR';
	public const TYPE_PERSONA_PIECE = 'TYPE_PERSONA_PIECE';
	public const TYPE_RESOURCE = 'TYPE_RESOURCE';
	public const TYPE_SKINS = 'TYPE_SKINS';
	public const TYPE_WORLD_TEMPLATE = 'TYPE_WORLD_TEMPLATE';
	public const TYPE_COUNT = 'TYPE_COUNT';

	/** @var string */
	public $packId;
	/** @var int */
	public $maxChunkSize;
	/** @var int */
	public $chunkCount;
	/** @var int */
	public $compressedPackSize;
	/** @var string */
	public $sha256;
	/** @var bool */
	public $isPremium = false;
	/** @var int */
	public $packType = self::TYPE_RESOURCE; //TODO: check the values for this

	protected function decodePayload(){
		$this->packId = $this->getString();
		$this->maxChunkSize = ((\unpack("V", $this->get(4))[1] << 32 >> 32));
		$this->chunkCount = ((\unpack("V", $this->get(4))[1] << 32 >> 32));
		$this->compressedPackSize = (Binary::readLLong($this->get(8)));
		$this->sha256 = $this->getString();
		$this->isPremium = (($this->get(1) !== "\x00"));
		$this->packType = (\ord($this->get(1)));
	}

	protected function encodePayload(){
		$this->putString($this->packId);
		($this->buffer .= (\pack("V", $this->maxChunkSize)));
		($this->buffer .= (\pack("V", $this->chunkCount)));
		($this->buffer .= (\pack("VV", $this->compressedPackSize & 0xFFFFFFFF, $this->compressedPackSize >> 32)));
		$this->putString($this->sha256);
		($this->buffer .= ($this->isPremium ? "\x01" : "\x00"));
		($this->buffer .= \chr(ResourceEnums::getPackTypeId($this->getProtocol(), $this->packType)));
	}

	public function handle(NetworkSession $session) : bool{
		return $session->handleResourcePackDataInfo($this);
	}
}
