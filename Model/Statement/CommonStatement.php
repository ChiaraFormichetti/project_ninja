<?php

namespace Model\Statement;

abstract class CommonStatement
{
    protected $tableName;
    protected $columns = [];
    protected $values = [];
    protected $joinConditions = [];
    protected $whereClauses = [];
    protected $bondArray = ['AND', 'OR', 'NOT',];
    protected $errors = [];


    protected function __construct($tableName)
    {
        $this->tableName = $tableName;
    }
    abstract function __toString(): string;

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    public function where(string $column, $value, $operator = '=', $whereBond = null): CommonStatement
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
            $this->errors[] = $error;
        }
        return $this;
    }

    public function parseClauseValue($value): string
    {
        return is_numeric($value) ? $value : "'" . $value . "'";
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

    //
    public function join($table1, $table2, $column1, $column2, $type = 'INNER'): CommonStatement
    {
        $this->joinConditions[] = [
            'table1' => $table1,
            'table2' => $table2,
            'column1' => $column1,
            'column2' => $column2,
            'type' => $type,
        ];

        //CONTROLLO I TYPE
        return $this;
    }

    public function appendJoinToQuery($query): string
    {
        foreach ($this->joinConditions as $condition) {
            $query .= ' ' . $condition['type'] . 'JOIN' . $condition['table1'] . '.' . $condition['column1'] . ' = ' .
                $condition['table2'] . '.' . $condition['column2'] . ' ';
        }
        $this->joinConditions = [];
        return $query;
    }
}
