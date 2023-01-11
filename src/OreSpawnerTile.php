<?php
declare(strict_types=1);

namespace NgLam2911\OreSpawner;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\tile\Spawnable;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use RuntimeException;

class OreSpawnerTile extends Spawnable{

	protected ?Block $ore_type = null;

	public function copyDataFromItem(Item $item) : void{
		if ($item->getNamedTag()->getTag("spawner") != null){
			$this->ore_type = $this->getBlock();
		}
	}

	public function getOreType() : Block{
		return $this->ore_type;
	}

	protected function addAdditionalSpawnData(CompoundTag $nbt) : void{
		//NOOP
	}

	public function readSaveData(CompoundTag $nbt) : void{
		if ($nbt->getTag("ore_type") == null){
			throw new RuntimeException("Ore type not found");
		}
		$this->ore_type = BlockFactory::getInstance()->fromFullBlock($nbt->getTag("ore_type")->getValue());
		$this->getBlock()->getPosition()->getWorld()->scheduleDelayedBlockUpdate($this->getBlock()->getPosition(), 20);
	}

	protected function writeSaveData(CompoundTag $nbt) : void{
		$nbt->setInt("ore_type", $this->ore_type->getFullId());
	}

	public function onUpdate(): bool{
		if ($this->ore_type == null){
			$this->close();
			return false;
		}
		if (!$this->getPosition()->getWorld()->getBlock($this->getPosition())->isSameType($this->ore_type)){
			$this->close();
			return false;
		}
		if (!$this->getPosition()->getWorld()->getBlock($this->getPosition()->up())->isSameType(VanillaBlocks::AIR())){
			return true;
		}
		$ore_list = [VanillaBlocks::STONE(), Loader::toOre($this->ore_type), VanillaBlocks::STONE()];
		$ore = $ore_list[mt_rand(0, 2)];
		$pos = $this->getPosition()->up();
		if ($pos->getY() >= World::Y_MAX){
			return true;
		}
		$pos->getWorld()->setBlock($pos, $ore);
		return true;
	}
}