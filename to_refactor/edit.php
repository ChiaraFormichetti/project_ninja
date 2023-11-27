<?php
require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

use Controller\Test\ReservationManager;

session_start();

$r = new ReservationManager();
$action = '';

if (array_key_exists('action', $_REQUEST)) {
    $action = $_REQUEST['action'];
}
switch ($action) {
    case 'edit':
        $nome = array_key_exists('nome', $_REQUEST) ? $_REQUEST['nome'] : null;
        $posti = array_key_exists('posti',$_REQUEST) ? $_REQUEST["posti"] : null;
        $ingresso = array_key_exists('ingresso', $_REQUEST) ? $_REQUEST['ingresso'] : null;
        $uscita = array_key_exists('uscita',$_REQUEST) ? $_REQUEST["uscita"] : null;
        $ris = $r->errorMan($_REQUEST["nome"], $_REQUEST["posti"], $_REQUEST["ingresso"], $_REQUEST["uscita"]);
        if ($ris["success"]) {
            $modification = $r->modRes($_REQUEST["nome"],$_REQUEST["posti"],$_REQUEST["ingresso"],$_REQUEST["uscita"], $id);
            if ($modification) {
                header('Location:homepage.php?action=edit');
            } 
            else{
                echo "Non è stato possibile modificare la prenotazione"."<br>";
                $modification=false;
            }

        }
        else {
            echo "Errore nell'inserimento dei dati"."<br>";
        }
        
        break;
    case 'fromhome':
        $id = array_key_exists('Id', $_REQUEST) ? $_REQUEST['Id'] : null;
        if($id!=null){
            $res = $r->printReservById($id);
            if($res["reser"][0]){ 
                $nome=$res["reser"][0]["nome"];
                $posti=$res["reser"][0]["posti"];
                $ingresso=$res["reser"][0]["ingresso"];
                $uscita=$res["reser"][0]["uscita"];
            }
        }
        break;
    default:
        echo "Inserisci il numero dell'Id della prenotazione da cercare"."<br>";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Modifica prenotazione</title>
</head>

<body><?php
    if($action===''){?>
        <form action="edit.php?action=fromhome" method="post">
            Id prenotazione da modificare<input type="number" name="Id">
            <input type="submit" value="Cerca">
        </form><?php
    }
    else { ?>
    <form action="edit.php?action=edit" method="post">
        Nome prenotazione<input type="text" name="nome" value="<?= $nome; ?>"><br>
        Persone<input type="number" name="posti" value="<?= $posti; ?>"><br>
        Data ingresso<input type="date" name="ingresso" value="<?= $ingresso; ?>"><br>
        Data uscita<input type="date" name="uscita" value="<?= $uscita; ?>"><br>
        <input type="submit" value="Modifica">
        
       
    </form>
    <?php
    } 
    if ($modification= false) {
        foreach ($ris["errors"] as $errorMsg) {
            echo "<div>Errore nella modifica della prenotazione</div>";
            echo $errorMsg;
        }
    }



    ?>
</body>

</html>