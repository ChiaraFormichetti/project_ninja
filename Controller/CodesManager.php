<?php

namespace Controller;

use Model\Storage\CodesStorage;

class CodesManager extends BaseManager
{
    protected $codesStorage;

    public function __construct()
    {
        $this->codesStorage = new CodesStorage();
    }


    public function getCheck(string $code): array{
        $check = $this->codesStorage->getCheck($code);
        return $check;
    }
}