<?php

use Controller\Test\ReservationManager;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';
session_start();
?>
<html>
    <head>
        <title>Lista prenotazioni</title>
    </head>
    <body>
    <h3>Prenotazioni inserite</h3>
    <?php
        $r = new ReservationManager();
        $print_Res = $r->printAllRes();
        foreach($print_Res as $res){
            echo "<pre>".json_encode($res)."</pre>";
             }
             
        
       /* $posti = $r->postiMax();
        echo "Il numero massimo di posti riservati è: ";
        echo json_encode($posti)."<br>";
        $prenotaz = $r->printMaxNum($posti);
        echo "La prenotazione relativa al massimo numero di posti riservati è: ";
        foreach($prenotaz as $pre){
            echo "<pre>".json_encode($pre)."</pre>";
        }*/
        $prenotaz = $r->printMaxNum();
        echo "La prenotazione relativa al massimo numero di posti riservati è: ";
        foreach($prenotaz as $pre){
            echo "<pre>".json_encode($pre)."</pre>";
        }
        ?>
        <a href="form3.html"> Modifica una prenotazione <a/a><br>
        
        <a href="storico.php">Visualizza lo storico delle prenotazioni </a>
        
    </body>
</html>