<?php

use Controller\Test\ReservationManager;


require '/var/www/vhosts/chiara-dev/vendor/autoload.php';
session_start();
?>
<html>

<head>
    <title>cestino</title>
</head>

<body>
    <h3>Lista prenotazioni cancellate</h3>
    <?php
    $action = '';
    if (array_key_exists('action', $_REQUEST)) {
        $action = $_REQUEST['action'];
    }


    $r = new ReservationManager();
   
    switch ($action) {
        case 'rip':
            $ripristina = $r->ripReservation($_REQUEST["ide"]);
            if($ripristina==false){
               echo "Non è stato possibile ripristinare la prenotazione"."<br>";
            }
            break;
        case 'delete'://fare un delete su database
            $delete = $r->delReservation($_REQUEST["Id"]);
            if($delete==false){
            echo "Non è stato possibile cancellare definitivamente la prenotazione"."<br>";
            }
            else {
                echo "Prenotazione cancellata definitivamente"."<br>";
            }
            break;
        default:
            break;
    }
    
    $printTrash = $r->printTrash();
    $bookDate = "";
    $bookYear = "";
    if ($printTrash == false) {
        echo "Non ci sono prenotazioni nel cestino " . "<br>";
    } else {

        foreach ($printTrash as $trash) {
            $date = substr($trash["ingresso"], -5);
            $year = substr($trash["ingresso"], 0, 4);
            if ($year != $bookYear) {
                echo "<b style=\"font-size:40px;\">" . $year . "<br>" . "</b>";
            }
            $bookYear = $year;

            if ($date != $bookDate) {
                echo "<b style=\"font-size:22px;\">" . $date . "</b>";
            }

            $bookDate = $date;

            ?>
            <ul><li><?php
            echo "<pre>" . $trash["nome"] . ", " . $trash["posti"] . ", " . $trash["uscita"] . "</pre>";?></li>
            <li><form action="trash.php?action=rip" method="post">
            <input type="submit" value="Ripristina nella lista delle prenotazioni">
            <input type="hidden" name="ide" value="<?= $trash["Id"] ?>"></li>
            </ul>

           
            
        </form>
        <?php
        }
    } 
    ?>
    <a href="homepage.php">Torna alla homepage</a>
</body>
</html>
