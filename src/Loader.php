<?php
declare(strict_types=1);

namespace NgLam2911\OreSpawner;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\tile\TileFactory;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\ToolTier;
use pocketmine\plugin\PluginBase;
use pocketmine\block\BlockIdentifier as BID;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockBreakInfo as BreakInfo;
use pocketmine\block\BlockToolType as ToolType;

class Loader extends PluginBase{

	public function onLoad() : void{
		$bf = BlockFactory::getInstance();
		$bf->register(new OreSpawnerBlock(new BID(Ids::COAL_BLOCK, 0, null, OreSpawnerTile::class), "Coal Block", new BreakInfo(5.0, ToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 30.0)), true);
		$bf->register(new OreSpawnerBlock(new BID(Ids::IRON_BLOCK, 0, null, OreSpawnerTile::class), "Iron Block", new BreakInfo(5.0, ToolType::PICKAXE, ToolTier::STONE()->getHarvestLevel(), 30.0)), true);
		$bf->register(new OreSpawnerBlock(new BID(Ids::GOLD_BLOCK, 0, null, OreSpawnerTile::class), "Gold Block", new BreakInfo(3.0, ToolType::PICKAXE, ToolTier::IRON()->getHarvestLevel(), 30.0)), true);
		$bf->register(new OreSpawnerBlock(new BID(Ids::DIAMOND_BLOCK, 0, null, OreSpawnerTile::class), "Diamond Block", new BreakInfo(5.0, ToolType::PICKAXE, ToolTier::IRON()->getHarvestLevel(), 30.0)), true);
		$bf->register(new OreSpawnerBlock(new BID(Ids::EMERALD_BLOCK, 0, null, OreSpawnerTile::class), "Emerald Block", new BreakInfo(5.0, ToolType::PICKAXE, ToolTier::IRON()->getHarvestLevel(), 30.0)), true);
		$bf->register(new OreSpawnerBlock(new BID(Ids::LAPIS_BLOCK, 0, null, OreSpawnerTile::class), "Lapis Lazuli Block", new BreakInfo(3.0, ToolType::PICKAXE, ToolTier::STONE()->getHarvestLevel())), true);
		/*$bf->register(new OreSpawnerBlock(new BID(Ids::REDSTONE_BLOCK, 0, null, OreSpawnerTile::class), "Redstone Block", new BreakInfo(5.0, ToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel(), 30.0)), true);*/
		TileFactory::getInstance()->register(OreSpawnerTile::class, ["ore_spawner"]);
	}

	protected function onEnable() : void{
		$this->getServer()->getCommandMap()->register("orespawner", new OreSpawnerCommand());
	}

	public static function toOre(Block $block) : Block{
		return match ($block->getTypeId()) {
			VanillaBlocks::COAL()->getTypeId() => VanillaBlocks::COAL_ORE(),
			VanillaBlocks::IRON()->getTypeId() => VanillaBlocks::IRON_ORE(),
			VanillaBlocks::GOLD()->getTypeId() => VanillaBlocks::GOLD_ORE(),
			VanillaBlocks::DIAMOND()->getTypeId() => VanillaBlocks::DIAMOND_ORE(),
			VanillaBlocks::EMERALD()->getTypeId() => VanillaBlocks::EMERALD_ORE(),
			/*VanillaBlocks::REDSTONE()->getTypeId() => VanillaBlocks::REDSTONE_ORE(),*/
			VanillaBlocks::LAPIS_LAZULI()->getTypeId() => VanillaBlocks::LAPIS_LAZULI_ORE(),
		};
	}

	public static function getOreSpawnerItem(string|Block $type) : ?Item{
		//God forgive me for what i'm about to code:
		if($type instanceof Block){
			$type = $type->getTypeId();
		}
		//This match method got only 10 lines
		return match ($type) {
			"coal", VanillaBlocks::COAL()->getTypeId(), VanillaBlocks::COAL_ORE()->getTypeId()
			=> VanillaBlocks::COAL()->asItem()
				->setNamedTag(VanillaBlocks::COAL()->asItem()->getNamedTag()
					->setInt("spawner", 1))
				->setCustomName("Coal Spawner"),
			"iron", VanillaBlocks::IRON()->getTypeId(), VanillaBlocks::IRON_ORE()->getTypeId()
			=> VanillaBlocks::IRON()->asItem()
				->setNamedTag(VanillaBlocks::IRON()->asItem()->getNamedTag()
					->setInt("spawner", 1))
				->setCustomName("Iron Spawner"),
			"gold", VanillaBlocks::GOLD()->getTypeId(), VanillaBlocks::GOLD_ORE()->getTypeId()
			=> VanillaBlocks::GOLD()->asItem()
				->setNamedTag(VanillaBlocks::GOLD()->asItem()->getNamedTag()
					->setInt("spawner", 1))
				->setCustomName("Gold Spawner"),
			"diamond", VanillaBlocks::DIAMOND()->getTypeId(), VanillaBlocks::DIAMOND_ORE()->getTypeId()
			=> VanillaBlocks::DIAMOND()->asItem()
				->setNamedTag(VanillaBlocks::DIAMOND()->asItem()->getNamedTag()
					->setInt("spawner", 1))
				->setCustomName("Diamond Spawner"),
			"emerald", VanillaBlocks::EMERALD()->getTypeId(), VanillaBlocks::EMERALD_ORE()->getTypeId()
			=> VanillaBlocks::EMERALD()->asItem()
				->setNamedTag(VanillaBlocks::EMERALD()->asItem()->getNamedTag()
					->setInt("spawner", 1))
				->setCustomName("Emerald Spawner"),
			/*"redstone", VanillaBlocks::REDSTONE()->getTypeId(), VanillaBlocks::REDSTONE_ORE()->getTypeId()
			=> VanillaBlocks::REDSTONE()->asItem()
				->setNamedTag(VanillaBlocks::REDSTONE()->asItem()->getNamedTag()
					->setInt("spawner", 1))
				->setCustomName("Redstone Spawner"),*/
			"lapis", VanillaBlocks::LAPIS_LAZULI()->getTypeId(), VanillaBlocks::LAPIS_LAZULI_ORE()->getTypeId()
			=> VanillaBlocks::LAPIS_LAZULI()->asItem()
				->setNamedTag(VanillaBlocks::LAPIS_LAZULI()->asItem()->getNamedTag()
					->setInt("spawner", 1))
				->setCustomName("Lapis Spawner"),
			default => null,
		};
	}
}