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
        //caso iniz; accettoo solo null
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

    public function parseClauseValue($value): string
    {
        return is_numeric($value) ? $value : "'" . $value . "'";
    }

    public function select(array $columns): QueryBuilder
    {
        $this->selectColumns = $columns;
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

    public function orderBy(string $column, string $direction = null): QueryBuilder
    {
        $this->orderBy = $column . ' ' . $direction;
        return $this;
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
            $query .= $clause['column'] . '=' . $parsedValue;
        }
        $query = $this->appendWhereClausesToQuery($query);

        return $query;
    }

    public function buildDefaultQuery(): string
    {
        return 'SELECT ' . implode(',', $this->selectColumns) . ' FROM ' . $this->tableName . ' ';
    }

    public function appendOrderByToQuery($query): string
    {
        $query .= ' ORDER BY ' . $this->orderBy;
        return $query;
    }

    public function toSql()
    {
        $query = $this->buildDefaultQuery();

        if (count($this->whereClauses) > 0) {
            $query = $this->appendWhereClausesToQuery($query);
        }
        if (strlen($this->orderBy) > 0) {
            $query = $this->appendOrderByToQuery($query);
        }
        return $query;
    }
}
