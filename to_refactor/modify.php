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
//controlliamo che il valore inserito sia corretto

//aggiungiamo il nuovo valore alla riga corrispondente nel database

$modification = $r->modRes($_REQUEST["value"],$_REQUEST["type"],$_REQUEST["id"]);
if($modification){
    echo "La modifica è stata apportata"."<br>";
}
else{
    echo "Non è stato possibile apportare la modifica";
}
?>
<a href="listaprenotazioni.php">Lista prenotazioni </a>
</body>
</html>