<?php

namespace Model\Statement;

class Update extends CommonStatement
{

    protected $updateClauses = [];

    public function getErrors(): array {
        return parent::getErrors();
    }
    //implementiamo la funzione to string del common statement per costruire la query dell'update
    public function __toString(): string
    {
        $query = 'UPDATE ' . $this->tableName . ' SET ';
        //per ogni clausola dell'update , se ne abbiamo già scritta una mettiamo la virgola,
        foreach ($this->updateClauses as $key => $clause) {
            if ($key >= 1) {
                $query .= ', ';
            } //altrimenti parsiamo i valori e concateniamo alla query la/e clausola/e
            $parsedValue = $this->parseClauseValue($clause['value']);
            $query .= $clause['column'] . ' = ' . $parsedValue . ' ';
        }
        //se ci sono clausole join le concateniamo      
        $query = $this->appendJoinToQuery($query);
        //stessa cosa per le where                          
        $query = $this->appendWhereClausesToQuery($query);
        //svuotiamo l'array delle clausole update
        $this->updateClauses = [];
        //ritorniamo la query
        return $query;
    }
    //funzione per assegnare valori alla proprietà updateclauses dell'oggetto update
    public function updateFunction(string $column, $value): Update
    {
        $this->updateClauses[] = [
            'column' => $column,
            'value' => $value
        ];
        return $this;
    }
}
