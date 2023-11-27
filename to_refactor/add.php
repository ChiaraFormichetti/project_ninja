<?php
require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

use Controller\Test\ReservationManager;

session_start();
$action='';
if (array_key_exists('action', $_REQUEST)) {
    $action = $_REQUEST['action'];
}


$r = new ReservationManager();
if($action == 'add'){ 
$ris = $r->errorMan($_REQUEST['nome'], $_REQUEST['num'], $_REQUEST['ingresso'], $_REQUEST['uscita']);
if ($ris["success"]) {
    $create=$r->createReserv($_REQUEST["nome"], $_REQUEST["num"], $_REQUEST["ingresso"], $_REQUEST["uscita"]);
    //nomi comprensibili alle variabili
    header('Location: http://192.168.157.185/homepage.php?action=add');
}
else{
    echo "Errore nell'inserimento dati: ".$ris["errors"][0];
}
}
$nome = array_key_exists('nome', $_REQUEST) ? $_REQUEST['nome'] : null;
$posti = array_key_exists('num',$_REQUEST) ? $_REQUEST['num'] : null;
$ingresso = array_key_exists('ingresso', $_REQUEST) ? $_REQUEST['ingresso'] : null;
$uscita = array_key_exists('uscita',$_REQUEST) ? $_REQUEST['uscita'] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Aggiungi prenotazione</title>
</head>

<body>

    <form action="add.php?action=add" method="post">
        Nome prenotazione<input type="text" name="nome" value="<?= $nome ?>"><br>
        Persone<input type="number" name="num" value="<?= $posti ?>"><br>
        Data ingresso<input type="date" name="ingresso" value="<?= $ingresso ?>"><br>
        Data uscita<input type="date" name="uscita" value="<?= $uscita ?>"><br>
        <input type="submit" value="Aggiungi">
    </form>
    <?php
?>


</body>

</html>