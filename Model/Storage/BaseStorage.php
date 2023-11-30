<?php

namespace Model\Storage;

use Model\Connection;

class BaseStorage
{
    protected $connection;

    public function __construct()
    {
        $this->connection;
        $v = new Connection();
        $this->connection = $v->getConnection();
    }
}