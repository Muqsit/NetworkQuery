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

/**
* FakePlayer is a hacky class for QueryRegenerateEvent.
* This class helps in combining playerlist of the
* requested-to-query servers.
*
* Why hack?
* To prevent QueryRegenerateEvent::getLongQuery()
* from throwing getName() errors.
*/

class FakePlayer {

    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function isOnline() : bool
    {
        return false;
    }
}