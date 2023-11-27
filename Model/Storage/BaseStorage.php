<?php

namespace Model;


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