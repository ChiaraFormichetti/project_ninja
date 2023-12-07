<?php

namespace Model\Statement;

class Insert extends CommonStatement
{
    public function getErrors(): array {
        return parent::getErrors();
    }
    public function __toString(): string
    {   //Cercare un errore per errore inserimento dati nel database
        //fare un try catch nello storage
        //scriviamo la nostra query 
        $query =  'INSERT INTO ' . $this->tableName . '(' . implode(',', $this->columns) . ') VALUES ' .
            '(' . implode(',', $this->values) . ')';
        //svuotiamo l'array delle colonne e dei valori
        $this->columns = [];
        $this->values = [];
        //ritorniamo la query
        return $query;
    }
    //assegniamo alle proprietà columns e values dell'oggetto insert i valori dell'array associativo body
    public function insert_into(array $body): Insert
    {
        foreach ($body as $column => $value) {
            //le chiavi dell'array saranno le colonne
            $this->columns[] = $column;
            //i volori prima di assegnarli li parsiamo
            $this->values[] = $this->parseClauseValue($value);
        }
        //ritorniamo l'oggetto
        return $this;
    }
}
