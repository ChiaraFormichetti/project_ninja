<?php

namespace Model\Statement;

class Update extends CommonStatement{

    protected $updateClauses = [];

    protected function __toString()
    {
        $query = 'UPDATE ' . $this->tableName . ' SET ';
        foreach ($this->updateClauses as $key => $clause) {
            if ($key >= 1) {
                $query .= ', ';
            }
            $parsedValue = $this->parseClauseValue($clause['value']);
            $query .= $clause['column'] . ' = ' . $parsedValue;
        }
        $query = $this->appendJoinToQuery($query);
        $query = $this->appendWhereClausesToQuery($query);
        $this->updateClauses = [];
        return $query;       
    }

    public function update(string $column, $value): Update
    {
        $this->updateClauses[] = [
            'column' => $column,
            'value' => $value
        ];
        return $this;
    }
}
