<?php

use Controller\Test\ReservationManager;
use Model\HistoricStorage;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';
session_start();
?>
<html>
    <head>
        <title>Lista storico prenotazioni</title>
    </head>
    <body>
    <h3>Storico prenotazioni</h3>
    <?php
        $r = new ReservationManager();
        /*Metodo per stampare la tabella storico
        $check = $r->createHis();
        if($check){
            echo "La tabella dello storico è stata creata";

        }
        else {
            echo "Non è stato possibile creare la tabella dello storico";
        } */
        $histor = $r->pasthist();
        if($histor==false){
            echo "Non ci sono prenotazioni passate da passare allo storico "."<br>";
        }
        else{
            foreach($histor as $res){
                echo "<pre>".json_encode($res)."</pre>";
                 }
            $historic = $r->addhistory();
            if($historic){
                echo "Riga aggiunta nella tabella dello storico"."<br>";
            }
            else{
                echo "Non è stato possibile aggiungere la riga nella tabella dello storico"."<br>";
            }
            
        }
        //Metodo per cancellare i valori di questa riga dalla tabella `prenotazioni` e aggiungerla alla tabella historic
        //Prenderemo in ingresso l'array $histor
        

