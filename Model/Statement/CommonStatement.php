<?php
namespace Model\Statement;

abstract class CommonStatement{
    protected $tableName;

    protected function __construct($tableName)
    {
        $this->tableName = $tableName;
    }
    protected function __toString(): string;
}