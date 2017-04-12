<?php

/**
*
*    \  |        |                           |      _ \                            
*     \ |   _ \  __| \ \  \   /  _ \    __|  |  /  |   |  |   |   _ \   __|  |   | 
*   |\  |   __/  |    \ \  \ /  (   |  |       <   |   |  |   |   __/  |     |   | 
*  _| \_| \___| \__|   \_/\_/  \___/  _|    _|\_\ \__\_\ \__,_| \___| _|    \__, | 
*                                                                           ____/  
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Twitter: @muqsitrayyan
* GitHub: https://github.com/Muqsit
*
*/

namespace NetworkQuery;

use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;

class EventListener implements Listener {

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onQuery(QueryRegenerateEvent $event)
    {
        $network = $this->plugin->getNetwork();
        $event->setPlayerCount($network->getOnlinePlayersCount());
        $event->setMaxPlayerCount($network->getMaxPlayersCount());
        //Sorta impossible
        //$event->setPlayerList($network->getPlayers());
        $this->plugin->getNetwork()->updateQueryData();
    }
}