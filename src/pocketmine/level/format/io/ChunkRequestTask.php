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

namespace pocketmine\level\format\io;

use pocketmine\level\format\Chunk;
use pocketmine\level\Level;

use pocketmine\tile\Spawnable;

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;

use pocketmine\scheduler\AsyncTask;

use pocketmine\Server;

use function assert;
use function strlen;

class ChunkRequestTask extends AsyncTask {

	protected $levelId;

	protected $chunk;
	protected $chunkX;
	protected $chunkZ;

	protected $subChunkCount;

    protected $tiles;

	protected $compressionLevel;
	
	protected $protocols = [ProtocolInfo::PROTOCOL_360];

	public function __construct(Level $level, int $chunkX, int $chunkZ, Chunk $chunk) {
		$this->levelId = $level->getId();
		$this->compressionLevel = $level->getServer()->networkCompressionLevel;

		$this->chunkX = $chunkX;
		$this->chunkZ = $chunkZ;
		$this->chunk = $chunk->fastSerialize();
		$this->subChunkCount = $chunk->getSubChunkSendCount();

        $tiles = "";
		foreach ($chunk->getTiles() as $tile) {
			if ($tile instanceof Spawnable) {
				$tiles .= $tile->getSerializedSpawnCompound();
			}
		}

        $this->tiles = $tiles;
	}

	public function onRun() {
	    $chunk = Chunk::fastDeserialize($this->chunk);

	    $buffers = [];
	    foreach ($this->protocols as $protocol) {
	        $chunk = $chunk->networkSerialize($protocol) . $this->tiles;

	    	$pk = LevelChunkPacket::withoutCache($this->chunkX, $this->chunkZ, $this->subChunkCount, $chunk);
	    	$pk->setProtocol($protocol);

	    	$batch = new BatchPacket();
	    	$batch->addPacket($pk);
	    	$batch->setCompressionLevel($this->compressionLevel);
	    	$batch->setProtocol($protocol);
	    	$batch->encode();

	    	$buffers[$protocol] = $batch->buffer;
	    }

        $this->setResult($buffers);
	}

	public function onCompletion(Server $server) {
		$level = $server->getLevel($this->levelId);
		if ($level instanceof Level) {
			if ($this->hasResult()) {
			    $buffers = [];

			    foreach ($this->getResult() as $protocol => $buffer) {
			    	$batch = new BatchPacket($buffer);
			    	assert(strlen($batch->buffer) > 0);
			    	$batch->setProtocol($protocol);
			    	$batch->isEncoded = true;

			    	$buffers[$protocol] = $batch;
			    }

				$level->chunkRequestCallback($this->chunkX, $this->chunkZ, $buffers);
			} else {
				$server->getLogger()->error("Chunk request for world #" . $this->levelId . ", x=" . $this->chunkX . ", z=" . $this->chunkZ . " doesn't have any result data");
			}
		} else {
			$server->getLogger()->debug("Dropped chunk task due to world not loaded");
		}
	}

}
