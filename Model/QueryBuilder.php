<?php

namespace Model;

use Exception;

class QueryBuilder
{
    public $bondArray = ['AND', 'OR'];
    protected $tableName;
    protected $whereClauses = [];
    protected $selectColumns = ['*'];
    protected $selectValues = [];
    protected $orderBy;
    protected $updateClauses = [];
    protected $errors = [];
    protected $limit=null;


    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }
    /**
     * @param string $column
     * @param mixed $value
     * @param string $operator
     * @param string $whereBond 
     * @return QueryBuilder
     */

    public function where(string $column, $value, $operator = '=', $whereBond = null): QueryBuilder
    {   //CONTROLLO SE $whereBond è corretto
        //caso iniz; accetto solo null
        if (count($this->whereClauses) == 0) {
            $whereBond = null;
        }
        if ($whereBond === null || array_search($whereBond, $this->bondArray)) {
            $this->whereClauses[] = [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
                'whereBond' => $whereBond
            ];
        } else {
            $error = 'Operatore logico del where non valido';
            $this -> errors [] = $error;
        }
        return $this;
    }

    public function appendWhereClausesToQuery($query): string
    {
        $query .= 'WHERE ';

        foreach ($this->whereClauses as $key => $clause) {
            if ($key >= 1) {
                $query .= ' ' . $clause['whereBond'] . ' ';
            }
            $parsedValue = $this->parseClauseValue($clause['value']);
            $query .= $clause['column'] . ' ' . $clause['operator'] . ' ' . $parsedValue;
        }
        $this->whereClauses = [];
        return $query;
    }
    //parsa i valori, se non sono numeri o stringhe numeriche li trasforma in stringhe
    public function parseClauseValue($value): string
    {
        return is_numeric($value) ? $value : "'" . $value . "'";
    }

    public function select(array $columns): QueryBuilder
    {
        $this->selectColumns = $columns;
        return $this;
    }
    // Non mi convince !
    
    public function orderBy(string $column, string $direction = null): QueryBuilder
    {
        $this->orderBy = $column . ' ' . $direction;
        return $this;
    }
    //limit serve nella select per far stampare i prirmi $int record di una tabella
    public function limit (int $limit) : QueryBuilder{
        $this->limit = $limit;
        return $this;
    }
    

    public function insert_into (array $values): string
    {
        return 'INSERT INTO ' . $this->tableName . '(' . implode(',', $this->selectColumns) . ') VALUES ' . '(' . implode(',', $values) . ')';
    }

    public function delete(): string
    {
        $query = 'DELETE FROM ' . $this->tableName;
        $query = $this->appendWhereClausesToQuery($query);
        return $query;
    }
    
    public function update(string $column, $value): QueryBuilder
    {
        $this->updateClauses[] = [
            'column' => $column,
            'value' => $value
        ];
        return $this;
    }


    public function updateQuery()
    {
        $query = 'UPDATE ' . $this->tableName . ' SET ';
        foreach ($this->updateClauses as $key => $clause) {
            if ($key >= 1) {
                $query .= ', ';
            }
            $parsedValue = $this->parseClauseValue($clause['value']);
            $query .= $clause['column'] . ' = ' . $parsedValue;
        }
        $query = $this->appendWhereClausesToQuery($query);
        $this->updateClauses = [];
        return $query;
    }

    //select query di default
    public function buildDefaultQuery(): string
    {
        return 'SELECT ' . implode(',', $this->selectColumns) . ' FROM ' . $this->tableName . ' ';
    }

    public function appendOrderByToQuery($query): string
    {
        $query .= ' ORDER BY ' . $this->orderBy;
        return $query;
    }

    public function appendLimitToQuery($query): string {
        $query .= ' LIMIT ' . $this->limit;
        $this->limit = null;
        return $query;
    }


    //crea la query con select
    public function toSql()
    {   
        $query = $this->buildDefaultQuery();
    
        if (count($this->whereClauses) > 0) {
            $query = $this->appendWhereClausesToQuery($query);
        }
        if (strlen($this->orderBy) > 0) {
            $query = $this->appendOrderByToQuery($query);
        }
        if($this->limit){
            $query = $this->appendLimitToQuery($query);
        }
        return $query;
    }
}
