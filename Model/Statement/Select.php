<?php

namespace Model\Statement;

class Select extends CommonStatement
{
    protected $havingClauses = [];
    protected $orderBy = null;
    protected $limit = null;
    protected $groupBy = [];

    public function __toString()
    {   
        if ($this->columns == []) {
            $query = 'SELECT ' . '*' . ' FROM ' . $this->tableName . ' ';
        } else {
            $query = 'SELECT ' . implode(',', $this->columns) . ' FROM ' . $this->tableName . ' ';
        }
        $this->columns = [];
        if ($this->groupBy != []) {
            $query .= 'GROUP BY ' . implode(',', $this->groupBy);
        }

        if ($this->havingClauses != []) {
            $query .= ' HAVING ';

            foreach ($this->havingClauses as $key => $clause) {
                if ($key >= 1) {
                    $query .= ' ' . $clause['havingBond'] . ' ';
                }
                $parsedValue = $this->parseClauseValue($clause['value']);
                $query .= $clause['column'] . ' ' . $clause['operator'] . ' ' . $parsedValue;
            }
            $this->havingClauses = [];
        }
        $query = $this->appendJoinToQuery($query);
        $query = $this->appendWhereClausesToQuery($query);
        //anche per più
        if ($this->orderBy) {
            $query .= ' ORDER BY ' . $this->orderBy;
            $this->orderBy = null;
        }
        if ($this->limit) {
            $query .= ' LIMIT ' . $this->limit;
            $this->limit = null;
        }

        return $query;
    }

    public function orderBy(string $column, string $direction = null): Select
    {
        $this->orderBy = $column . ' ' . $direction;
        return $this;
    }

    public function groupBy(array $columns): Select
    {
        $this->groupBy = $columns;
        return $this;
    }

    public function having(string $column, $value, $operator = '=', $havingBond = null): Select
    {
        if (count($this->havingClauses) == 0) {
            $havingBond = null;
        }
        if ($havingBond === null || array_search($havingBond, $this->bondArray)) {
            $this->havingClauses[] = [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
                'havingBond' => $havingBond
            ];
        } else {
            $error = 'Operatore logico del having non valido';
            $this->errors[] = $error;
        }
        return $this;
    }

    //limit serve nella select per far stampare i prirmi $int record di una tabella
    //limit e offset , o limit $numerooffset,$numerolimit
    //se c'è la virgola dopo liomit viene fatta la paginazione
    public function limit(int $limit): Select
    {
        $this->limit = $limit;
        return $this;
    }
}
