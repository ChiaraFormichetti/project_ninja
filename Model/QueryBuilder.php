<?php

namespace Model;

use Model\Statement\Delete;
use Model\Statement\Insert;
use Model\Statement\Select;
use Model\Statement\Update;

class QueryBuilder
{
    protected $statement;
    protected $tableName;
     
    public function __construct($tableName)
    {
       $this-> tableName = $tableName;
    }

    public function select(){
        $this->statement = new Select($this->tableName);
        return $this;
    }

    public function insert(){
        $this->statement = new Insert($this->tableName);
        return $this;
    }

    public function update(){
        $this->statement = new Update($this->tableName);
        return $this;
    }

    public function delete(){
        $this->statement = new Delete($this->tableName);
        return $this;
    }

    public function getQuery()
    {
        return $this->statement->__toString();
    }

    public function selectColumns(array $columns)
    {
        $this->statement->setColumns($columns);
        return $this;
    }

    public function where(string $column, $operator = '=',$value, $whereBond = null){
        $this->statement->where($column,$operator, $value, $whereBond);
        return $this;
    }

    public function join($table1, $table2, $column1, $column2, $type = 'INNER'){
        $this->statement->join($table1, $table2, $column1, $column2, $type);
        return $this;
    }

    public function orderBy(string $column, string $direction = null)
    {
        $this->statement->orderBy($column, $direction);
        return $this;
    }

    public function groupBy(array $columns)
    {
        $this->statement->groupBy($columns);
        return $this;
    }

    public function having(string $column, $value, $operator = '=', $havingBond = null)
    {
        $this->statement->having($column, $value, $operator, $havingBond);
        return $this;
    }

    public function limit(int $limit){
        $this->statement->limit($limit);
        return $this;
    }
    public function updateFunction (string $column, $value){
        $this->statement->updateFunction($column,$value);
        return $this;
    }
    public function insert_into(array $body){
        $this->statement->insert_into($body);
        return $this;
    }
}
