<?php

namespace Model;

use Model\Statement\Delete;
use Model\Statement\Insert;
use Model\Statement\Select;
use Model\Statement\Update;

class QueryBuilder
{
    protected $statement;
     
    //switch
    //o con __call fai il match
    /*public function __construct($tableName)

    {   $this->tableName = $tableName;
        $this->select = new Select($tableName);
        $this->insert = new Insert($tableName);
        $this->delete = new Delete($tableName);
        $this->update = new Update($tableName);

    }

*/
    public function __construct($tableName, $statement)
    {
        switch($statement){
            case 'select':
                $this->statement = new Select($tableName);
                break;
            case 'insert':
                $this->statement = new Insert($tableName);
                break;
            case 'update':
                $this->statement = new Update($tableName);
                break;
            case 'delete':
                $this->statement = new Delete ($tableName);
                break;
            default :
                //gestione errore
                break;               
        }
    }

    public function getQuery()
    {
        return $this->statement->__toString();
    }

    public function selectColumns(array $columns)
    {
        return $this->statement->setColumns($columns);
    }

    public function where(string $column, $value, $operator = '=', $whereBond = null){
        return $this->statement->where($column, $value, $operator, $whereBond);
    }

    public function join($table1, $table2, $column1, $column2, $type = 'INNER'){
        return $this->statement->join($table1, $table2, $column1, $column2, $type);
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
    public function update (string $column, $value){
        $this->statement->update($column, $value);
        return $this;
    }
    public function insert_into(array $body){
        $this->statement->insert_into($body);
        return $this;
    }
}
