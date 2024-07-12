<?php

namespace NurAzliYT\RandomTPx;

use pocketmine\plugin\PluginBase;
use NurAzliYT\RandomTPx\command\RandomTPxCommand;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getCommandMap()->register("randomtp", new RandomTPCommand($this));
    }
}
