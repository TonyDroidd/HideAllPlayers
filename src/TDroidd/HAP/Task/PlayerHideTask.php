<?php
namespace TDroidd\HAP\Task;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\PluginTask;
use TDroidd\HAP\Main;

class PlayerHideTask extends PluginTask
{
    public function __construct(Main $plugin, Player $player)
    {
        parent::__construct($plugin);
        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function onRun($currentTick){
        foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
            $this->player->hidePlayer($online);
        }
    }
}
