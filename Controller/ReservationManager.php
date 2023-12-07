<?php

namespace Controller;

use DateTime;
use Model\Storage\ReservationStorage;
use Model\Table\Reservation;

class ReservationManager
{
    protected $reservationStorage;
    protected  $reservations = [];

    public function __construct()
    {
        $this->reservationStorage = new ReservationStorage();
    }
    
    public function checkColumns(array $parameters)
    {  
        $reservationTable = new Reservation();
        $tableColumns = $reservationTable->getTableColumns();
        $existentColumns = array_diff(array_keys($parameters), $tableColumns); //problema!!!!!
        if (empty($existentColumns)) {
            $error = "Le seguenti colonne non esistono all'interno della tabella prenotazioni" ;
            return $error;
        }
        return null;
    }


    //funzione per la gestione degli errori nell'agggiunta di una prenotazione
    public function errorMan($name, $seats, $enter, $exit)
    {
        $res = [
            "success" => false,
            "errors" => []
        ];

        if ((preg_match('#\d{4}\-\d{2}\-\d{2}#', $enter)) && (preg_match('#\d{4}\-\d{2}\-\d{2}#', $exit))) {
            $date = new DateTime("$enter");
            $date1 = new DateTime("$exit");
            $now = new DateTime;

            if ((is_numeric($seats)) && (is_string($name)) && ($date > $now) && ($date1 > $date)) {
                $res["success"] = true;
            } else {
                $res["errors"][] = "Errore nell'inserimento dei dati";
            }
        } else {
            $res["errors"][] = "Errore nell'inserimento della data";
        }
        return $res;
    }

    //funzione per la gestione del tipo di valore da modificare
    public function typeError(array $updateDate)
    {
        $res = [
            "success" => false,
            "errors" => ""
        ];
        $str = ["nome", "posti", "ingresso", "uscita"];
        foreach (array_keys($updateDate) as $column) {
            if (in_array($column, $str)) {
                $res["success"] = true;
            } else {
                $res["errors"] = "Errore nell'inserimento del tipo di valore da modificare";
            }
        }
        return $res;
    }

    //Funzione per stampare tutte le prenotazioni
    public function getReservations()
    {
        $reservations = $this->reservationStorage->getReservation();
        return $reservations;
    }

    //funzione per stampare le cancellazioni cancellate
    public function getTrashReservations()
    {
        $reservations = $this->reservationStorage->getTrashReservation();
        return $reservations;
    }
    //funzione per stampare le cancellazioni nello storico
    public function getHistoricReservations()
    {
        $reservations = $this->reservationStorage->getHistoricReservation();
        return $reservations;
    }

    //Lo useremo per trovvare gli errori nell'update $tableColumns = $this->reservationStorage->translateReservation();

    //funzione per cercare le prenotazioni
    public function searchReservations($name, $enter)
    {
        $reservations = $this->reservationStorage->searchReservation($name, $enter);
        return $reservations;
    }

    //funzione per aggiungere una prenotazione
    public function addReservations(array $body)
    {
        $result = $this->reservationStorage->addReservation($body);
        return $result;
    }

    //funzione per modificare una prenotazione
    public function editReservations(array $updateData, int $id)
    {
        $result = $this->reservationStorage->editReservation($updateData, $id);
        return $result;
    }

    //funzione pe spostare una cancellazione nel cestino
    public function trashReservations(int $id)
    {
        $result = $this->reservationStorage->trashReservation($id);
        return $result;
    }

    //funzione per cancellare una prenotazione
    public function deleteReserations(int $id)
    {
        $result = $this->reservationStorage->deleteReservation($id);
        return $result;
    }
}
