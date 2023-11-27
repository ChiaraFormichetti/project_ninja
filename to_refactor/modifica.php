<?php

use Controller\Test\ReservationManager;
use Model\ReservationStorage;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

session_start();
?>

<html>
<head>
<title>Cerca id per modificare la prenotazione</title>
</head>
<body>
<?php
$r = new ReservationManager();

$esegui = $r->idErrorMan($_REQUEST["id"]);
if($esegui==true){ 

echo "Id inserito: " .$_REQUEST["id"]."<br>"."Relativo alla prenotazione: ";


$reserv = $r->printReservById($_REQUEST["id"]);
echo json_encode($reserv)."<br>";
   }

else{
    echo "Errore nell'inserimento dell'id"."<br>";
}
?>
 <form action="inserisci.php" action="modify.php" method="post">
            Inserisci il tipo di valore da modificare(nome,posti,ingresso o uscita)<input type="text" name="type"><br>
            <input type="submit" value="Inserisci">
            <input type="hidden" name="id" value="<?= $_REQUEST["id"] ?>">
        </form>
    </body>
</html>

