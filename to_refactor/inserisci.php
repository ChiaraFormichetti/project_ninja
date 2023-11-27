<?php

use Controller\Test\ReservationManager;
use Model\ReservationStorage;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

session_start();
?>

<html>
<head>
<title>Valore inserito</title>
</head>
<body>
<?php
$r = new ReservationManager();
//serve un metodo da usare per il controllo che il valore inserito sia uno dei nostri valori
$work = $r->typeError($_REQUEST["type"]);
if ($work["success"]) {
    echo "Il valore da modificare è ".$_REQUEST["type"]."<br>";
    //switch per capire che tipo di valore inserire
    switch($_REQUEST["type"]){
        case 'nome':
            ?>
            <form action="modify.php" method="post">
            Inserisci il nuovo nome per la prenotazione<input type="text" name="value"><br>
            <input type="submit" value="Modifica">
            <input type="hidden" name="id" value="<?= $_REQUEST["id"] ?>">
            <input type="hidden" name="type" value="<?= $_REQUEST["type"] ?>">
            </form>
            <?php
            break;
        case 'posti':
            ?>
            <form action="modify.php" method="post">
            Inserisci il nuovo numero di posti per la prenotazione<input type="number" name="value"><br>
            <input type="submit" value="Modifica">
            <input type="hidden" name="id" value="<?= $_REQUEST["id"] ?>">
            <input type="hidden" name="type" value="<?= $_REQUEST["type"] ?>">
            </form>
            <?php
            break;
        case 'ingresso':
            ?>
            <form action="modify.php" method="post">
            Inserisci la nuova data di ingresso<input type="date" name="value"><br>
            <input type="submit" value="Modifica">
            <input type="hidden" name="id" value="<?= $_REQUEST["id"] ?>">
            <input type="hidden" name="type" value="<?= $_REQUEST["type"] ?>">
            </form>
            <?php
            break;
        case 'uscita':
            ?>
            <form action="modify.php" method="post">
            Inserisci la nuova data per l'uscita<input type="date" name="value"><br>
            <input type="submit" value="Modifica">
            <input type="hidden" name="id" value="<?= $_REQUEST["id"] ?>">
            <input type="hidden" name="type" value="<?= $_REQUEST["type"] ?>">
            </form>
            <?php
            break;
        default:
            echo "Nessun valore corrispondente alla tua selezione";
            break;
    }
}
else{
   echo $work["errors"];
}


