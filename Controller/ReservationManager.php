<?php

namespace Controller;

use DateTime;
use Exception;
use Model\Storage\ReservationStorage;

class ReservationManager
{
    protected $rs;
    protected $val;

    public function __construct()
    {
        $this->rs = new ReservationStorage();
    }

    //Creare un nuovo metodo per gestire gli errori per le date usando le regex
    //funzione per la gestione degli errori nell'inserimento dati
    public function errorMan($nome, $posti, $ingresso, $uscita)
    {
        $res = [
            "success" => false,
            "errors" => []
        ];

        if ((preg_match('#\d{4}\-\d{2}\-\d{2}#', $ingresso)) && (preg_match('#\d{4}\-\d{2}\-\d{2}#', $uscita))) {
            $date = new DateTime("$ingresso");
            $date1 = new DateTime("$uscita");
            $now = new DateTime;


            if ((is_numeric($posti)) && (is_string($nome)) && ($date > $now) && ($date1 > $date)) {
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
    public function typeError($type)
    {
        $res = [
            "success" => false,
            "errors" => ""
        ];
        $str = ["nome", "posti", "ingresso", "uscita"];
        if (in_array($type, $str)) {
            $res["success"] = true;
        } else {
            $res["errors"] = "Errore nell'inserimento del tipo di valore da modificare";
        }
        return $res;
    }
    //funzione per la gestione degli errori nell'inserimento dell'id da cercare
    public function idErrorMan($id)
    {
        return is_numeric($id);
    }

    //funzione per aggiungere la nuova prenotazione alla tabella nel database
    public function createReserv($nome, $posti, $ingresso, $uscita)
    {
        $risultato =  $this->rs->addRow($nome, $posti, $ingresso, $uscita);
        return $risultato;
    }
 
    //public function getReservation($parameters=[]){
    //    if(count($parameters)>0){
    //        
    //    }
    //}
    //funzione per stampare tutte le prenotazioni presenti nel database
    public function getAllRes()
    {
        $reservation = $this->rs->getRes();
        return $reservation;
    }

    //funzione per stampare tutte le prenotazioni raggruppate per data
    public function printReservation($date){
        $reservation = $this->rs->printReservationByDate($date);
        return $reservation;
    }
    //funzione per stampare una prenotazione con un certo id   
    public function printReservById($id)
    {
        $res = [
            "reser" => [],
            "errors" => []
        ];

        $reserv = $this->rs->getResById($id);
        if ($reserv == false) {
            $res["errors"][] = "Non esiste nessuna prenotazione con questo Id" . "<br>";
        } else {
            $res["reser"][] = $reserv;
        }
        return $res;
    }/*
    //funzione per cercare il massimo numero di posti prenotati
    public function postiMax(){ 
        $posti = $this->rs->getPosti();
        return $posti["PostoMax"];
    }
    //funzione per stampare le prenotazioni con il massimo numero di posti prenotati
    public function printMaxNum($posti){
        $prenotaz = $this->rs->getPrenMaxNum($posti);
        return $prenotaz;
    }*/
    public function printMaxNum()
    {
        $pren = $this->rs->getPrenMaxNum();
        return $pren;
    }
    public function modRes($nome,$posti,$ingresso,$uscita,$id)
    {
        $rismod = $this->rs->modify($nome,$posti,$ingresso,$uscita,$id);
        return $rismod;
    }
    public function delReservation($id){
        $delete = $this->rs->deleteReservation($id);
        return $delete;
    }

    public function ripReservation($id){
        $ripRes = $this->rs->ripristinaReservation($id);
        return $ripRes;
    }
    public function createHis()
    {
        $historic = $this->rs->createHistoric();
        return $historic;
    }
    public function pasthist()
    {
        $histor = $this->rs->searchPastRes();
        return $histor;
    }

    public function printTrash(){
        $trash = $this->rs->searchInTrash();
        return $trash;
    }
    public function addhistory()
    {
        $historic = $this->rs->addHist();
        return $historic;
    }
    public function searchRes($nome=NULL, $ingresso=NULL){
        $search = $this->rs->searchReservation($nome,$ingresso);
        return $search;
    }
}
