<?php
declare(strict_types=1);

namespace NgLam2911\OreSpawner;

use pocketmine\block\Opaque;
use pocketmine\item\Item;

class OreSpawnerBlock extends Opaque{
	public function onScheduledUpdate() : void{
		$tile = $this->getPosition()->getWorld()->getTile($this->getPosition());
		if ($tile instanceof OreSpawnerTile){
			if ($tile->onUpdate()){
				$this->getPosition()->getWorld()->scheduleDelayedBlockUpdate($this->getPosition(), 20);
			}
		}
	}

	public function getDrops(Item $item) : array{
		$tile = $this->getPosition()->getWorld()->getTile($this->getPosition());
		if ($tile instanceof OreSpawnerTile){
			return [Loader::getOreSpawnerItem($tile->getOreType())];
		}
		return parent::getDrops($item);
	}
}