<?php

namespace Controller;

use Model\Table\Reservation;

class BaseManager
{
    public function checkColumn($tableName, array $parameters): array
    {  //La classe reservation usa i metodi della classe table per convertire le sue proprietà in un array 
        $result = [
            'success' => true,
            'errors' => [],
        ];
        $tableClassName = '\\Model\\Table\\'.ucfirst($tableName);
        if(!class_exists($tableClassName)){
            $result = [
                'success' => false,
                'errors' => "Non esiste la classe inerente alla tabella",
            ];
        }
        $tableObject = new $tableClassName();
        $tableColumns = $tableObject->getTableColumns();
        //controlliamo che l'array di sinistra sia interamente contenuto nell'array di destra
        $errorColumns = array_diff(array_keys($parameters), array_keys($tableColumns));
        //se ci sono colonne d'errore ritorniamo l'errore
        if (!empty($errorColumns)) {
            $result = [
                'success' => false,
                'errors' => "Le seguenti colonne non esistono all'interno della tabella prenotazioni",
            ];
            //ritorniamo direttamente
        }
        //se non ci sono colonne d'errore ritorniamo un valore null
        return $result;
    }


}
