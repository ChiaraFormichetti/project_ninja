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

    //metodo per controllare che i parametri in ingresso nel service abbiano delle colonne (chiavi dell'array) che coincidono
    //con le colonne della tabella in questione
    //se avessi più tabelle potrei generalizzarlo mettendolo nel base manager, controllando che esista la classe reservation e nel caso
    //in cui esista la istanziamo e usiamo il metodo getTableColumns
    public function checkColumns(array $parameters)
    {  //La classe reservation usa i metodi della classe table per convertire le sue proprietà in un array 
        $reservationTable = new Reservation();
        $tableColumns = $reservationTable->getTableColumns();
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


    //funzione per la gestione degli errori nell'agggiunta di una prenotazione
    public function errorMan(array $parameters)
    {
        $res = [
            "success" => false,
            "errors" => []
        ];
        //controllo degli errori, prima controlla il formato della data (Big-Endian)
        if ((preg_match('#\d{4}\-\d{2}\-\d{2}#', $parameters['ingresso'])) && (preg_match('#\d{4}\-\d{2}\-\d{2}#', $parameters['uscita']))) {
            $date = new DateTime($parameters['ingresso']);
            $date1 = new DateTime($parameters['uscita']);
            $now = new DateTime;
            //controlliamo che tutti gli input siano corretti
            if ((is_numeric($parameters['posti'])) && (is_string($parameters['nome'])) && ($date > $now) && ($date1 > $date)) {
                $res["success"] = true;
            } else {
                //restituiamo l'errore
                $res["errors"][] = "Errore nell'inserimento dei dati";
            }
        } else {
            //se la data non è nel formato voluto restituiamo l'errore
            $res["errors"][] = "Errore nell'inserimento della data";
        }
        return $res;
    }

    //Funzione per stampare tutte le prenotazioni
    public function getReservations($page=null, $reservationForPage = null, $parameters=[])
    {
        $reservations = $this->reservationStorage->getReservation($page, $reservationForPage, $parameters);
        return $reservations;
    }

    public function getReservationById($id){
        $reservation = $this->reservationStorage->getReservationById($id);
        return $reservation;
    }

    //funzione per stampare le cancellazioni cancellate
    public function getTrashReservations($page=null, $reservationForPage=null, $parameters=[])
    {
        $reservations = $this->reservationStorage->getTrashReservation( $page, $reservationForPage, $parameters);
        return $reservations;
    }
    //funzione per stampare le cancellazioni nello storico
    public function getHistoricReservations($page=null, $reservationForPage=null, $parameters=[])
    {
        $reservations = $this->reservationStorage->getHistoricReservation($page, $reservationForPage, $parameters);
        return $reservations;
    }

    //funzione per cercare le prenotazioni
    public function getSearch($name, $enter)
    {
        $reservations = $this->reservationStorage->searchReservation($name, $enter);
        return $reservations;
    }

    //funzione per aggiungere una prenotazione
    public function postAddReservation(array $body)
    {
        $result = $this->reservationStorage->addReservation($body);
        return $result;
    }

    //funzione per modificare una prenotazione
    public function postEditReservation(array $updateData, int $id)
    {
        $result = $this->reservationStorage->editReservation($updateData, $id);
        return $result;
    }

    //funzione pe spostare una cancellazione nel cestino
    public function postTrashReservation(int $id)
    {
        $result = $this->reservationStorage->trashReservation($id);
        return $result;
    }

    public function postRestoreReservation(int $id){
        $result = $this->reservationStorage->restoreReservation($id);
        return $result;
    }

    //funzione per cancellare una prenotazione
    public function deleteReservation(int $id)
    {
        $result = $this->reservationStorage->deleteReservation($id);
        return $result;
    }
}
