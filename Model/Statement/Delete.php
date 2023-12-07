<?php

namespace Model\Statement;

class Delete extends CommonStatement
{
    public function getErrors(): array {
        return parent::getErrors();
    }
    //Come unico metodo implementiamo il to string per ottenere la nostra query string per cancellare record dalla tabella
    public function __toString(): string
    {   //scriviamo l'inizio della query
        $query = 'DELETE FROM ' . $this->tableName . ' ';
        //se ci sono clausole join le concateniamo
        $query = $this->appendJoinToQuery($query);
        //stessa cosa per le where
        $query = $this->appendWhereClausesToQuery($query);
        //ritorniamo la query
        return $query;
    }
}
