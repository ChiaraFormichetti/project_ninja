<?php

namespace Controller;

use Model\Table\Reservation;

class BaseManager
{
    public function checkColumn($tableName, array $parameters)
    {  //La classe reservation usa i metodi della classe table per convertire le sue proprietà in un array 
        $tableClassName = '\\Model\\Table\\'.ucfirst($tableName);
        if(!class_exists($tableClassName)){
            return "Non esiste la classe inerente alla tabella";
        }
        $tableObject = new $tableClassName();
        $tableColumns = $tableObject->getTableColumns();
        //controlliamo che l'array di sinistra sia interamente contenuto nell'array di destra
        $errorColumns = array_diff(array_keys($parameters), array_keys($tableColumns));
        //se ci sono colonne d'errore ritorniamo l'errore
        if (!empty($errorColumns)) {
            return "Le seguenti colonne non esistono all'interno della tabella prenotazioni";
            //ritorniamo direttamente
        }
        //se non ci sono colonne d'errore ritorniamo un valore null
        return null;
    }


}
