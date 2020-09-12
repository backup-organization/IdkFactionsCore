<?php

namespace esh123unicorn\factioncore\command\warp;

use esh123unicorn\factioncore\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\config;
use pocketmine\level\Level;
use pocketmine\level\LevelExpection;
use pocketmine\level\LevelProvider;
use pocketmine\level\ProviderManager;
use pocketmine\level\Position;

//form ui
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class Warp extends PluginCommand{

    private $owner;
    
    private $config;
    private $cords;
    
    public function __construct(string $name, Main $owner)
    {
        parent::__construct($name, $owner);
        $this->owner = $owner;
        $this->setPermission("warp.use");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
            if($sender->hasPermission("warp.use")) {
               $this->openWarpUI($sender);   
            } else {
               $sender->sendMessage("§7(§c!§7) §cYou do not have permission to use this command");
            }
    }
               
                
    public function openWarpUI(CommandSender $sender)
    {
        $this->config = new Config($this->getPlugin()->getDataFolder() . "/warps.yml", Config::YAML);
        $this->cords = new Config($this->getPlugin()->getDataFolder() . "/cords.yml", Config::YAML);
        if(!($sender instanceof Player)){
                return true;
            }
            $form = new SimpleForm(function (Player $sender, $data){
            if ($data === null) {
                return;
            }
            switch ($data) {
            	case 0: 
	        $x = $this->cords->get("warp1x");
	        $y = $this->cords->get("warp1y");
	        $z = $this->cords->get("warp1z");
	        $world = $this->cords->get("warp1level");
	        if($world == null) {
	           $sender->sendMessage("§7(§c!§7) §cSpawn has not been set yet");
	        }else{
     	       $world = $this->getPlugin()->getServer()->getLevelByName($world);
     	       $sender->teleport($world->getSafeSpawn());
               $sender->teleport(new Vector3($x, $y, $z, 0, 0));
               $sender->sendMessage("§7(§a!§7) §aYou are being warped to spawn..."); 
               }
               break;
    }
}
