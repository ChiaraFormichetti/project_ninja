<?php

use Controller\Test\ReservationManager;


require '/var/www/vhosts/chiara-dev/vendor/autoload.php';
session_start();
//unica pagina giusta, migliorare la parte iniziale però, è comune a tutte le pagine quindi possiamo tranquillamente
//scriverla una volta sola
?>
<html>

<head>
    <title>Lista storico prenotazioni</title>
</head>

<body>
    <h3>Storico prenotazioni</h3>
    <?php
    $r = new ReservationManager();
    $printHistory = $r->pasthist();
    $bookDate = "";
    $bookYear = "";
    if ($printHistory == false) {
        echo "Non ci sono prenotazioni passate nello storico " . "<br>";
    } else {

        foreach ($printHistory as $res) {
            $date = substr($res["ingresso"], -5);
            $year = substr($res["ingresso"], 0, 4);
            if ($year != $bookYear) {
                echo "<b style=\"font-size:40px;\">" . $year . "<br>" . "</b>";
            }
            $bookYear = $year;

            if ($date != $bookDate) {
                echo "<b style=\"font-size:22px;\">" . $date . "</b>";
            }

            $bookDate = $date;?>
            <ul><li><?php
            echo "<pre>" . $res["nome"] . ", " . $res["posti"] . ", " . $res["uscita"] . "</pre>";
            ?></li></ul><?php
        }
    }
    ?>
    <a href="homepage.php">Torna alla homepage</a>
</body>
</html>
