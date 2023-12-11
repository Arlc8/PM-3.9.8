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

namespace pocketmine\crafting;

use pocketmine\scheduler\AsyncTask;

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;

use pocketmine\inventory\CraftingManager;

use pocketmine\Server;

class CraftingDataBuildTask extends AsyncTask {

    /** @var array */
    protected $shapelessRecipes;
    /** @var array */
    protected $shapedRecipes;
    /** @var array */
    protected $furnaceRecipes;
    /** @var int */
    protected $compressionLevel;
    /** @var array */
    protected $protocols = [ProtocolInfo::PROTOCOL_360, ProtocolInfo::PROTOCOL_361];

	public function __construct(array $shapelessRecipes, array $shapedRecipes, array $furnaceRecipes, int $networkCompressionLevel) {
	    $this->shapelessRecipes = $shapelessRecipes;
	    $this->shapedRecipes = $shapedRecipes;
	    $this->furnaceRecipes = $furnaceRecipes;
		$this->compressionLevel = $networkCompressionLevel;
	}

	public function onRun() {
	    $buffers = [];
	    foreach ($this->protocols as $protocol) {
	    	$pk = new CraftingDataPacket();
	    	$pk->cleanRecipes = true;

	    	foreach ($this->shapelessRecipes as $list) {
		    	foreach ($list as $recipe) {
			    	$pk->addShapelessRecipe($recipe);
		    	}
	    	}

	    	foreach ($this->shapedRecipes as $list) {
		    	foreach ($list as $recipe) {
			    	$pk->addShapedRecipe($recipe);
		    	}
	    	}

	    	foreach ($this->furnaceRecipes as $recipe) {
		    	$pk->addFurnaceRecipe($recipe);
	    	}

            $pk->setProtocol($protocol);
	    	$pk->encode();

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
	    if ($this->hasResult()) {
	        if ($server->getCraftingManager() instanceof CraftingManager) {
			    $buffers = [];

			    foreach ($this->getResult() as $protocol => $buffer) {
			    	$batch = new BatchPacket($buffer);
			    	assert(strlen($batch->buffer) > 0);
			    	$batch->setProtocol($protocol);
			    	$batch->isEncoded = true;

			    	$buffers[$protocol] = $batch;
			    }

	            $server->getCraftingManager()->setCraftingData($buffers);
	            $server->getCraftingManager()->sendCraftingDataToQueue($buffers);
	        }
	    } else {
	        $server->getLogger()->error("CraftingDataCacheBuild request doesn't have any result data");
	    }
	}

}
