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

namespace NetworkQuery\tasks;

use NetworkQuery\{FakePlayer, Network};
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class AsyncQueryTask extends AsyncTask {

    private $serverdata;

    public function __construct(array $serverdata)
    {
        $this->serverdata = (array) $serverdata;
    }

    public function onRun()
    {
        $opt = [];
        foreach ($this->serverdata as $server) {
            $data = [
                $server[0],
                $server[1]
            ];
            $json = json_decode(file_get_contents(str_replace('{DATA}', implode(':', $server), Network::MC_API)));
            if ($json->status == false) {
                $data[2] = [
                    "online" => 0,
                    "players" => []
                ];
            } else {
                $players = $json->list ?? [];
                if (!empty($players)) {
                    foreach ($players as $k => $v) {
                        $players[$k] = new FakePlayer($v);
                    }
                }
                $data[2] = [
                    "online" => $json->players->online,
                    "players" => $players
                ];
            }
            $opt[] = $data;
        }
        $this->setResult($opt);
    }

    public function onCompletion(Server $server)
    {
        $network = $server->getPluginManager()->getPlugin('NetworkQuery')->getNetwork();
        foreach ($this->getResult() as $data) {
            $network->setQueryData(...$data);
        }
        $network->update();
    }
}