<?php

namespace Model\Statement;

class Select extends CommonStatement
{
    protected $havingClauses = [];
    protected $orderBy = null;
    protected $limit = null;
    protected $groupBy = [];
    protected $limitInit = null;
    protected $limitFinal = null;
    protected $countColumn = null;

    //implementazione del metodo astatto della classe commonstatement
    //crea la nostra query string
    public function getErrors(): array {
        return parent::getErrors();
    }
    public function __toString(): string
    {   if ($this->countColumn){
        $query = 'SELECT COUNT('. $this->countColumn.') FROM ' . $this->tableName.' ';
        $this->countColumn = null;       
        } else {
            //se l'array di colonne è vuoto => non abbiamo specificato nessuna colonna, le prendiamo tutte e scriviamo la prima parte della query
            if ($this->columns == []) {
                $query = 'SELECT ' . '*' . ' FROM ' . $this->tableName . ' ';
            } else {
                //altrimenti usiamo quelle che abbiamo specificato
                $query = 'SELECT ' . implode(',', $this->columns) . ' FROM ' . $this->tableName . ' ';
            } //dopo averle usate le azzeriamo (tanto ormai sono nella query)
            $this->columns = [];
            //se ci sono elementi di group by li concateniamo
        }
        if ($this->groupBy != []) {
            $query .= 'GROUP BY ' . implode(',', $this->groupBy);
        }
        //Se ci sono clausole having le concateniamo
        if ($this->havingClauses != []) {
            $query .= ' HAVING ';
            //funziona come il where, quindi se abbiamo più di una clausola prima concateneremo l'operatore logico
            foreach ($this->havingClauses as $key => $clause) {
                if ($key >= 1) {
                    $query .= ' ' . $clause['havingBond'] . ' ';
                } //poi parseremo i valori
                $parsedValue = $this->parseClauseValue($clause['value']);
                //dopodichè concateneremo la condizione
                $query .= $clause['column'] . ' ' . $clause['operator'] . ' ' . $parsedValue;
            } //svuotiamo l'array
            $this->havingClauses = [];
        } //se ci sono clausole join le concateniamo (il controllo viene eseguito nella funzione stessa)
        $query = $this->appendJoinToQuery($query);
        //stessa cosa del join
        $query = $this->appendWhereClausesToQuery($query);
        // se abbiamo un order by lo concateniamo
        if ($this->orderBy) {
            $query .= ' ORDER BY ' . $this->orderBy;
            $this->orderBy = null;
        } //se abbiamo un limit iniziale lo concateniamo
        //se abbiamo un limit finale lo concateniamo
        if ($this->limitFinal) {
            $query .= ' LIMIT ' . $this->limitInit.', ' . $this->limitFinal;
            $this->limitInit = null;
            $this->limitFinal = null;
        }
        //ci ritorna la query completa del select
        return $query;
    }

    public function countElements (string $column):Select 
    {
        $this->countColumn = $column;
        return $this;
    }

    //assegniamo all'oggetto select un valore alla proprietà order by
    public function orderBy(string $column, string $direction = null): Select
    {
        $this->orderBy = $column . ' ' . $direction;
        return $this;
    }
    //assegniamo all'oggetto select un valore alla proprietà group by
    public function groupBy(array $columns): Select
    {
        $this->groupBy = $columns;
        return $this;
    }
    //assegniamo all'ogetto select dei valori alla proprietà having clauses
    public function having(string $column, $value, $operator = '=', $havingBond = null): Select
    {   //se non ci sono clausole nell'array non serve un operatore logico
        if (count($this->havingClauses) == 0) {
            $havingBond = null;
        }
        //se invece l'operatore inserito è valido => assegniamo i valori
        if ($havingBond === null || array_search($havingBond, $this->bondArray)) {
            $this->havingClauses[] = [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
                'havingBond' => $havingBond
            ];
        } else {
            //catturiamo l'errore
            $error = 'Operatore logico del having non valido';
            $this->errors[] = $error;
        }
        //ritorniamo l'oggetto
        return $this;
    }

    //limit serve nella select per far stampare i prirmi $int record di una tabella
    //limit e offset , o limit $numerooffset,$numerolimit
    //se c'è la virgola dopo liomit viene fatta la paginazione  => che dobbiamo fare
    public function limit(int $limitInit, int $limitFinal = null): Select
    {
        $this->limitInit = $limitInit;
        $this->limitFinal = $limitFinal;
        return $this;
    }
}
