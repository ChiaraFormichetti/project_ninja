<?php

namespace Model\Statement;

class Insert extends CommonStatement
{
    public function __toString():string
    {   //Cercare un errore per errore inserimento dati nel database
        //fare un try catch nello storage

        $query =  'INSERT INTO ' . $this->tableName . '(' . implode(',', $this->columns) . ') VALUES ' .
            '(' . implode(',', $this->values) . ')';
        $this->columns = [];
        $this->values = [];
        return $query;
    }

    public function insert_into(array $body): Insert
    {
        foreach ($body as $column => $value) {
            $this->columns[] = $column;
            $this->values[] = $this->parseClauseValue($value);
        }
        return $this;
    }
}
