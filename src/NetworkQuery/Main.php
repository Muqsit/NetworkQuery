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

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    const CONFIG = [
        "enable" => true,
        "networks" => [
            [
                "spirit.cosmicpe.me",
                19132
            ]
        ]
    ];

    private $network = null;

    public function onEnable()
    {
        if (!is_dir($path = $this->getDataFolder())) {
            mkdir($path);
        }

        if (!is_file($path = $path.'network.yml')) {
            yaml_emit_file($path, self::CONFIG);
        }

        $networkData = yaml_parse_file($path);
        if ((bool) $networkData["enable"]) {
            if (!empty($networkData["networks"])) {
                $this->setNetwork(new Network($networkData["networks"]));
            }
        }

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    /**
    * @return Network|null
    */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
    * @param Network $network
    */
    private function setNetwork(Network $network) : bool
    {
        if ($this->getNetwork() === $network) {
            return false;
        }
        $this->network = $network;
        return true;
    }
}