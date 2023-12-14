<?php

namespace Model;

use Model\Statement\Delete;
use Model\Statement\Insert;
use Model\Statement\Select;
use Model\Statement\Update;

class QueryBuilder
{
    protected $statement;
    protected $tableName;

    //è una classe di passaggio , un collegamento fra gli statement e lo storage

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function select()
    {
        $this->statement = new Select($this->tableName);
        return $this;
    }

    public function insert()
    {
        $this->statement = new Insert($this->tableName);
        return $this;
    }

    public function update()
    {
        $this->statement = new Update($this->tableName);
        return $this;
    }

    public function delete()
    {
        $this->statement = new Delete($this->tableName);
        return $this;
    }

    public function getQuery()
    { 
        try {
            $query = $this->statement->__toString();
            $errors = $this->statement->getErrors();
            //chiamando il get query controlliamo se l'array di errori è popolato
            if (!empty($errors)) {
                //se è popolato blocchiamo l'esecuzione del blocco try e entriamo nel catch
                // il backslash indica che la classe dell'eccezione si trova nel namespace globale
                throw new \Exception('Errore nella creazione della query: ' . implode(',', $errors));
            }
            //se l'array di errori è vuoto continuiamo il blocco try e restituiamo la query
            return $query;
            //catturiamo l'eccezione di tipo \Exceptions dove $e è l'istanza dell'eccezione catturata
        } catch (\Exception $e) {
            //registriamo il messaggio di errore  e quello dell'eccezione catturata, dove getmessage ci restituisce il testo dell'errore specifico
            error_log('Errore durante la creazione della query: ' . $e->getMessage());
            //restituiamo l'errore per gestirlo anche nel manage
            return $e->getMessage();
            //il finally viene eseguito indipendentemente se stiamo nel try o nel catch
        } 
    }

    public function countElements (string $column)
    {
        $this->statement->countElements($column);
        return $this;
    }

    public function selectColumns(array $columns)
    {
        $this->statement->setColumns($columns);
        return $this;
    }

    public function where(string $column, $operator = '=', $value, $whereBond = 'AND')
    {
        $this->statement->where($column, $operator, $value, $whereBond);
        return $this;
    }

    public function join($table1, $table2, $column1, $column2, $type = 'INNER')
    {
        $this->statement->join($table1, $table2, $column1, $column2, $type);
        return $this;
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

    public function limit(int $initLimit, int $finalLimit)
    {
        $this->statement->limit($initLimit,$finalLimit);
        return $this;
    }
    public function updateFunction(string $column, $value)
    {
        $this->statement->updateFunction($column, $value);
        return $this;
    }
    public function insert_into(array $body)
    {
        $this->statement->insert_into($body);
        return $this;
    }
}
