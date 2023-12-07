<?php

namespace Model\Statement;

abstract class CommonStatement
{
    protected $tableName;
    protected $columns = [];
    protected $values = [];
    protected $joinConditions = [];
    protected $whereClauses = [];
    protected $bondArray = ['AND', 'OR', 'NOT'];
    protected $errors = [];
    protected $typeArray = ['INNER', 'LEFT', 'RIGHT', 'FULL OUTER'];


    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }
    //funzione astratta poichè viene ereditata e implementata da tutte le classi filie (Select,Delete,Insert,Update)
    abstract function __toString(): string;

    public function getErrors(): array{
        return $this->errors;
    }

    //definiamo l'array di colonne da poter usare nel select in caso di bisogno 
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }
    //definiamo il where 
    public function where(string $column, $operator = '=', $value, $whereBond = null): CommonStatement
    {   //CONTROLLO SE $whereBond è corretto
        //caso iniz; accetto solo null
        if (count($this->whereClauses) == 0) {
            $whereBond = null;
        }
        //controlliamo che il where bond abbia i valori permessi e a quel punto aggiungiamo le clausole
        if ($whereBond === null || in_array($whereBond, $this->bondArray)) {
            $this->whereClauses[] = [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
                'whereBond' => $whereBond
            ];
        } else {
            //operatore logico non valido
            $error = 'Operatore logico del where non valido';
            $this->errors[] = $error;
        }
        //ritorna un oggetto common statement con la proprietà whereclauses assegnata
        return $this;
    }
    //analizza i valori e se questi sono stinghe o numeri li lascia inalterati, se invece sono alti tipi di dato li converte in stringhe
    public function parseClauseValue($value): string
    {
        return is_numeric($value) ? $value : "'" . $value . "'";
    }
    //costruisce una query string per il where
    public function appendWhereClausesToQuery($query): string
    //se esiste almeno una clausola where concateniamo alla query il where
    {
        if (!empty($this->whereClauses)) {
            $query .= 'WHERE ';
            //dopodichè per ogni clausola successiva alla prima concateniamo prima di tutto l'operatore logico
            foreach ($this->whereClauses as $key => $clause) {
                if ($key >= 1) {
                    $query .= ' ' . $clause['whereBond'] . ' ';
                }
                //parsiamo i valori in caso di necessità
                $parsedValue = $this->parseClauseValue($clause['value']);
                //concateniamo alla query il cuore della clausola where con i valori parsati
                $query .= $clause['column'] . ' ' . $clause['operator'] . ' ' . $parsedValue;
            }
            //azzeriamo l'array di clausole where poichè ormai abbiamo tutto nella query
            $this->whereClauses = [];
        } //ritorniamo la query
        return $query;
    }

    //stessa cosa del where, di default imponiamo il type inner
    public function join($table1, $table2, $column1, $column2, $type = 'INNER'): CommonStatement
    //contolliamo che il tipo di join inserito sia valido (se non si inserisce niente, di default, vale inner)
    {
        if (in_array($type, $this->typeArray)) {
            $this->joinConditions[] = [
                'table1' => $table1,
                'table2' => $table2,
                'column1' => $column1,
                'column2' => $column2,
                'type' => $type,
            ];
        } else {
            //se non è valido restituiremo un errore
            $error = 'Tipo del join inserito non valido';
            $this->errors[] = $error;
        };
        //ritorniamo l'oggetto con la proprietà joinConditions desiderata, se caso di errore ritoriamo l'oggetto con la proprietà errore assegnata
        return $this;
    }
    //appendiamo le clausole join alla query
    public function appendJoinToQuery($query): string
    //controlliamo che esistano condizioni di join
    {
        if (!empty($this->joinConditions)) {
            //concateniamo tutte le clausole join che ci servono
            foreach ($this->joinConditions as $condition) {
                $query .= ' ' . $condition['type'] . ' JOIN ' . $condition['table2'] . ' ON ' . $condition['table1'] . '.' . $condition['column1'] . ' = ' .
                    $condition['table2'] . '.' . $condition['column2'] . ' ';
            }
            //ripuliamo l'array delle joinConditions
            $this->joinConditions = [];
            //ritorniamo la query
        }
        return $query;
    }
}
