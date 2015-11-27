<?php
namespace TDroidd\HAP;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\scheduler\Task;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\inventory\BaseInventory;
use TDroidd\HAP\Task\PlayerHideTask;
class Main extends PluginBase implements Listener{
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "HideAllPlayers Enabled");
    }

    public function onJoin(PlayerJoinEvent $e){
        $cfg = $this->getConfig();
        $player = $e->getPlayer();
        $player->getInventory()->setItem(25, Item::get($cfg->get("HidePlayers-Item"), 0, 1));
        $player->getInventory()->setItem(26, Item::get($cfg->get("ShowPlayers-Item"), 0, 1));
    }

    public function onRespawn(PlayerRespawnEvent $e){
        $cfg = $this->getConfig();
        $player = $e->getPlayer();
        $player->getInventory()->setItem(25, Item::get($cfg->get("HidePlayers-Item"), 0, 1));
        $player->getInventory()->setItem(26, Item::get($cfg->get("ShowPlayers-Item"), 0, 1));
    }

    public function onHeld(PlayerItemHeldEvent $event){
        $cfg = $this->getConfig();
        $player = $event->getPlayer();
        $hidetask = new PlayerHideTask($this, $player);
        $item = $event->getItem()->getId();
            switch($item){
                case $cfg->get("HidePlayers-Item"):
                    $this->task = $this->getServer()->getScheduler()->scheduleRepeatingTask($hidetask, 20);
                    $player->sendPopup(TextFormat::YELLOW . $cfg->get("HidePlayer-Message"));
                    $event->setCancelled(true);
                    break;
                case $cfg->get("ShowPlayers-Item"):
                    $this->getServer()->getScheduler()->cancelTask($this->task->getTaskId());
                    foreach ($this->getServer()->getOnlinePlayers() as $onl){
                        $player->showPlayer($onl);
                        $player->sendPopup(TextFormat::GREEN . $cfg->get("ShowPlayer-Message"));
                        $event->setCancelled(true);
                        }
                    break;
        }
    }
}
