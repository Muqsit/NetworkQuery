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

use NetworkQuery\tasks\AsyncQueryTask;
use pocketmine\Server;

class Network {

    const MC_API = 'http://mcapi.ca/query/{DATA}/mcpe';

    private $servers = [];
    private $queryData = [];
    private $fakeplayers = [];

    private $onlineplayercnt = 0;

    public function __construct(array $servers)
    {
        $this->server = Server::getInstance();
        $this->servers = $servers;
        $this->updateQueryData();
    }

    /**
    * Returns the number of online players.
    *
    * @return int
    */
    public function getOnlinePlayersCount() : int
    {
        return $this->onlineplayercnt + count($this->server->getOnlinePlayers());
    }

    /**
    * Returns the number of max players.
    *
    * @return int
    */
    public function getMaxPlayersCount() : int
    {
        return 1;
    }

    /**
    * Returns all player usernames.
    *
    * @return string[]
    */
    public function getPlayers() : array
    {
        return array_merge($this->server->getOnlinePlayers(), $this->fakeplayers);
    }

    public function getServers(bool $serialized = false)
    {
        return $serialized ? serialize($this->servers) : $this->servers;
    }

    public function updateQueryData()
    {
        $this->server->getScheduler()->scheduleAsyncTask(new AsyncQueryTask($this->servers));
        $this->update();
    }

    public function getQueries()
    {
        return $this->queryData;
    }

    public function setQueryData(string $ip, string $port, array $data)
    {
        $this->queryData[$ip.':'.$port] = $data;
    }

    public function update()
    {
        $maxplayers = 0;
        $online = 0;
        $fakeplayers = [];
        foreach ($this->queryData as $data) {
            $online += $data["online"];
            $fakeplayers = array_merge($fakeplayers, $data["players"]);
        }
        $this->onlineplayercnt = $online;
        $this->fakeplayers = $fakeplayers;
    }
}