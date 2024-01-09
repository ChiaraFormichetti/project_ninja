<?php

namespace Controller;

use Model\Storage\GiftsStorage;

class GiftsManager extends BaseManager
{
    protected $giftsStorage;

    public function __construct()
    {
        $this->giftsStorage = new GiftsStorage();
    }


    public function getType(string $code): array
    {
        $check = $this->giftsStorage->getType($code);
        return $check;
    }
   
}