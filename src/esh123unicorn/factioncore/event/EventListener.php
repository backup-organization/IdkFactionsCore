<?php

namespace esh123unicorn\factioncore\event;

use esh123unicorn\factioncore\Main;

//pocketmine event
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\player\PlayerExperienceChangeEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDataSaveEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerChatEvent;

use twisted\bettervoting\event\PlayerVoteEvent;

//config
use pocketmine\utils\config;

//tasks
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskScheduler;

//blocks and items
use pocketmine\block\BlockFactory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\Armor;
use pocketmine\item\Tool;
use pocketmine\item\ItemFactory;

//effect
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

//pocketmine
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\utils\Utils;

//form ui
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

//level
use pocketmine\level\Level;
use pocketmine\level\LevelExpection;
use pocketmine\level\LevelProvider;
use pocketmine\level\ProviderManager;
use pocketmine\level\Position;
use pocketmine\event\Listener;

//function 
use function time;
use function count;
use function floor;
use function microtime;
use function number_format;
use function round;
use function strtotime; //string to time
use function strtolower; //string lowers all letters
use function strtoupper; //string upper letters all
use function ucfirst; //first letter of string capital

class DataBackUps implements Listener{
  
    private $plugin;
	
    public $config;
	
    public $position = [];

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
	
    public function getPlugin(){
	return $this->plugin;
    }
	
    public function displayFaction(PlayerChatEvent $event) {
	    $player = $event->getPlayer();
            $message = $event->getMessage();
	    $level = $this->plugin->getLevel($player);
	    $config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
	    $event->setMessage($config->get("faction-text-prefix") . $this->plugin->getFaction($player) . $config->get("faction-text-suffix") . " " . $config->get("level-text-prefix") . $level . $config->get("level-text-suffix") . " " . $message);
    }
	
    public function onVote(PlayerVoteEvent $event) {
	    $config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
	    $player = $event->getPlayer();
	    $player->sendMessage($config->get("vote-message"));
	    $this->plugin->getServer()->broadcastMessage("§b" . $player->getName() . $config->get("vote-broadcast"));
    }
	
    public function wallGenerator(BlockPlaceEvent $event) {
	    $player = $event->getPlayer();
	    $block = $event->getBlock();
	    $blockName = $block->getName();
	    $blockId = $block->getId();
	    $config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
	    
	    if($blockName == $config->get("gen-name") and $blockId == $config->get("gen-id")) { 
	       $level = $block->getLevel();
	       $x = $block->getX();
	       $y = $block->getY();
	       $z = $block->getZ();
	       
	       $place = $y->distance($config->get("max-distance"));
	       $pos = [];
	       foreach($place as $valueY) {
		       $pos[] = (int) $valueY;
	       }
	       $this->position[$valueY] = $pos;
	       $position = new Vector3($block->getX(), $this->position[$valueY], $block->getZ());
	       $this->plugin->getServer()->getLevel()->setBlock($position, $config->get("get-id"), true, true);
	    }
    }
}
