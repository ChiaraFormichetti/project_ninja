<?php

use Controller\Test\ReservationManager;
use Model\Connection;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

session_start();
if(!isset($_SESSION["prenotazioni"][$_REQUEST["nome"]])){
  $_SESSION["prenotazioni"][$_REQUEST["nome"]] = 0;
}
$_SESSION["prenotazioni"][$_REQUEST["nome"]] += $_REQUEST["num"];

?>

<html>

<head>
  <title>Evento prenotato</title>
</head>

<body>
  <?php
  $res = new ReservationManager();
  $ris = $res->errorMan($_REQUEST["nome"], $_REQUEST["num"], $_REQUEST["ingresso"], $_REQUEST["uscita"]);
  if ($ris["success"]) {
    $res->createReserv($_REQUEST["nome"], $_REQUEST["num"], $_REQUEST["ingresso"], $_REQUEST["uscita"]);
    echo "Inserita prenotazione: " . $_REQUEST["nome"] . " posti letto " . $_REQUEST["num"] . " data ingresso " . $_REQUEST["ingresso"] . " data uscita " . $_REQUEST["uscita"] . "<br>";
    echo " totale = " . $_SESSION["prenotazioni"][$_REQUEST["nome"]] . "<br>";
  } else {
    foreach ($ris["errors"] as $errorMsg) {
      echo $errorMsg . "<br>";
    }
  }
  ?>
  <a href="form1.html"> Nuova prenotazione </a><br>
  <a href="listaprenotazioni.php">Lista prenotazioni </a>
</body>

</html>