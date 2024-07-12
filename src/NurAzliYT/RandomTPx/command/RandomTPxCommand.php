<?php

namespace NurAzliYT\RandomTPx\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use pocketmine\utils\TextFormat as TF;
use pocketmine\world\Position;
use NurAzliYT\RandomTPx\Main;
use pocketmine\utils\Limits;

class RandomTPCommand extends Command implements PluginOwned {
    use PluginOwnedTrait;

    private $plugin;
    private $cooldowns = [];

    public function __construct(Main $plugin) {
        parent::__construct("randomtp", "Teleport to a random location", "/randomtp", ["rtp"]);
        $this->setPermission("randomtpx.command");
        $this->plugin = $plugin;
        $this->owningPlugin = $plugin; // Required by PluginOwnedTrait
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return false;
        }

        if ($sender instanceof Player) {
            $playerName = $sender->getName();
            $currentTime = time();

            if (isset($this->cooldowns[$playerName]) && ($currentTime - $this->cooldowns[$playerName]) < 300) {
                $remainingTime = 300 - ($currentTime - $this->cooldowns[$playerName]);
                $sender->sendMessage(TF::RED . "You must wait " . gmdate("i:s", $remainingTime) . " before using this command again.");
                return true;
            }

            $level = $sender->getWorld();
            $x = mt_rand(Limits::INT32_MIN, Limits::INT32_MAX) % $level->getWorldWidth();
            $z = mt_rand(Limits::INT32_MIN, Limits::INT32_MAX) % $level->getWorldWidth();
            $y = $level->getHighestBlockAt($x, $z);
            $sender->teleport(new Position($x, $y, $z, $level));
            $sender->sendMessage(TF::GREEN . "You have been teleported to a random location!");

            $this->cooldowns[$playerName] = $currentTime;
        } else {
            $sender->sendMessage(TF::RED . "This command can only be used in-game.");
        }
        return true;
    }
}
