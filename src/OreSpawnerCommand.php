<?php
declare(strict_types=1);

namespace NgLam2911\OreSpawner;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;

class OreSpawnerCommand extends Command implements PluginOwned{
	use PluginOwnedTrait;

	public function __construct(Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
		$this->setAliases(["os", "osp"]);
		parent::__construct("orespawner", $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if (!$sender instanceof Player){
			$sender->sendMessage("Please use this command in-game!");
			return;
		}
		if (!isset($args[0])){
			$sender->sendMessage("Usage: /orespawner <type> [amount]");
			return;
		}
		$spawner_type = Loader::getOreSpawnerItem($args[0]);
		if ($spawner_type == null){
			$sender->sendMessage("Invalid type!");
			$sender->sendMessage("Available spawner type: coal, iron, gold, diamond, emerald, lapis");
			return;
		}
		if (isset($args[1])){
			if (!is_numeric($args[1])){
				$sender->sendMessage("Invalid amount!");
				return;
			}
			$amount = (int)$args[1];
			if ($amount < 1){
				$sender->sendMessage("Amount must be greater than 0!");
				return;
			}
		} else {
			$amount = 1;
		}
		$spawner_type->setCount($amount);
		if ($sender->getInventory()->canAddItem($spawner_type)){
			$sender->getInventory()->addItem($spawner_type);
			$sender->sendMessage("You have received $amount ore spawner(s)!");
		} else {
			$sender->sendMessage("Your inventory is full!");
		}
	}
}