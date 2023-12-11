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

use pocketmine\utils\BinaryStream;

class BlockPallet {

	public static $blockNamesIds = [];

	public static function initAll() {
		$result = [];
		$folderPath = __DIR__ . "/data/";
		$palletFiles = array_diff(scandir($folderPath), ['..', '.']);

		foreach ($palletFiles as $fileName) {
			if (strpos($fileName, "BlockPallet") !== 0) {
				continue;
			}

			$parts = explode(".", $fileName);
			$protocolNumber = (int) substr($parts[0], 11);
			$pallet = new BlockPallet($folderPath . $fileName, $protocolNumber);
			$result[$protocolNumber] = $pallet;
		}
		krsort($result);

		return $result;
	}

	private $pallet = [];
	private $palletReverted = [];
	private $dataForPackets = "";

	public function __construct(string $path, int $protocolNumber) {
		$palletData = json_decode(file_get_contents($path), true);

		$bs = new BinaryStream();
		$bs->putUnsignedVarInt(count($palletData));

		foreach ($palletData as $runtimeID => $blockInfo) {
			if (isset($blockInfo['runtimeID'])) {
				$this->pallet[$blockInfo['id']][$blockInfo['data']] = $blockInfo['runtimeID'];
				$this->palletReverted[$blockInfo['runtimeID']] = [$blockInfo['id'], $blockInfo['data'], $blockInfo['name']];
			} else {
				$this->pallet[$blockInfo['id']][$blockInfo['data']] = $runtimeID;
				$this->palletReverted[$runtimeID] = [$blockInfo['id'], $blockInfo['data'], $blockInfo['name']];
			}

			$bs->putString($blockInfo['name']);
			$bs->putLShort($blockInfo['data']);
			$bs->putLShort($blockInfo['id']);
		}

		$this->dataForPackets = $bs->getBuffer();
	}

	public function getBlockDataByRuntimeID($runtimeID) {
		if (isset($this->palletReverted[$runtimeID])) {
			return $this->palletReverted[$runtimeID];
		}

		return [0, 0, ""];
	}

	public function getBlockRuntimeIDByData($id, $meta) {
		if (isset($this->pallet[$id][$meta])) {
			return $this->pallet[$id][$meta];
		}

		return 0;
	}

	public function getDataForPackets() {
		return $this->dataForPackets;
	}

}
