<?php
namespace Model\Table;

use ReflectionClass;
use ReflectionProperty;

abstract class Table{
    protected $tableColumns = [];

    public function __construct(){
        $this->mapPropertiesToColumns();
    }

    public function getTableColumns(){
        return $this->tableColumns;
    }

    public function mapPropertiesToColumns(){
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

        foreach($properties as $property) {
            $this->tableColumns[$property->getName()] = $this->{$property->getName()};
        }
    }   
}