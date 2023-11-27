<?php

use Controller\Test\ReservationManager;
use Model\ReservationStorage;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

session_start();
?>

<html>

<head>
    <title>Id inserito</title>
</head>

<body>
    <?php
    $r = new ReservationManager();

    $esegui = $r->idErrorMan($_REQUEST["id"]);
    if ($esegui) {

        echo "Id inserito: " . $_REQUEST["id"] . "<br>" . "Relativo alla prenotazione: ";


        $reserv = $r->printReservById($_REQUEST["id"]);
        echo json_encode($reserv) . "<br>";
    } else {
        echo "Errore nell'inserimento dell'id" . "<br>";
    }
    ?>
    <a href="form1.html"> Nuova prenotazione </a><br>
    <a href="listaprenotazioni.php">Lista prenotazioni </a><br>
    <a href="form2.html">Cerca un'altra prenotazione tramite id </a>
</body>