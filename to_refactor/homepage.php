<?php

use Controller\Test\ReservationManager;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';
session_start();
//uno dei modi per capire da che pagina veniamo
//rif=$_SERVER["HTTP_REFERER"];

$action = '';


if (array_key_exists('action', $_REQUEST)) {
    $action = $_REQUEST['action'];
}

$r = new ReservationManager();
$reservations = [];
switch ($action) {
    case 'search':
        $nome = array_key_exists('nome', $_REQUEST) ? $_REQUEST['nome'] : null;
        $ingresso = array_key_exists('ingresso', $_REQUEST) ? $_REQUEST['ingresso'] : null;
        $reservations = $r->searchRes($nome, $ingresso);
        break;
    case 'delete':
        $id = array_key_exists('Id', $_REQUEST) ? $_REQUEST['Id'] : null;
        $delete = $r->delReservation($id);
        if ($delete) {
            echo "Hai cancellato la prenotazione con Id: " . $id . "<br>";
        }
        $reservations = $r->printAllRes();
        break;
    case 'edit':
        echo "La prenotazione è stata modificata"."<br>";
        $reservations = $r->printAllRes();
        break;
    case 'add':
        echo "La prenotazione è stata aggiunta"."<br>";
        $reservations = $r->printAllRes();


    default:
        $reservations = $r->printAllRes();
        break;
}
//se la richiesta non è una ricerca, visualizzo tutte le prenotazioni
?>
<html>

<head>
    <title>Homepage</title>
</head>

<body>
    <h1>Homepage</h1>
<?php
if ($action === 'search') {
    //renderizzo la lista delle prenotazioni che corrispondono ai valori cercati
    ?>
    <h2>Lista prenotazioni</h2>
    <?php
    if (count($reservations)) {
        //renderizzazione della lista delle prenotazioni
        $bookDate = "";
        $bookYear = "";
        foreach ($reservations as $res) {
            $date = substr($res["ingresso"], -5);
            $year = substr($res["ingresso"], 0, 4);
            if ($year != $bookYear) { ?>
                <h3><?= $year ?></h3><?php
            }
            $bookYear = $year;

            if ($date != $bookDate) { ?>
                <div><?= $date ?></div><br> <?php
            }
            $bookDate = $date;
            ?>
            <ul>
            <li>
                <pre><?= $res["nome"] . ", " . $res["posti"] . ", " . $res["uscita"] ?></pre><br>
            </li>
            <li>
                <form action="homepage.php?action=delete" method="post">
                    <input type="submit" value="Elimina">
                    <input type="hidden" name="Id" value="<?= $res["Id"] ?>">
                </form>
            </li>
            <li>
                <form action="edit.php?action=fromhome" method="post">
                    <input type="submit" value="Modifica">
                    <input type="hidden" name="Id" value="<?= $res["Id"] ?>">
                </form>
            </li>
            </ul><?php

        }
    } else {
        //renderizzazione messaggio assenza prenotazioni
        ?><div>Non ci sono prenotazioni!</div><?php
    } ?>
    <a href="homepage.php">Torna alla lista completa</a><?php
}
else {
    ?> <ul>
        <li>
            <form action="homepage.php?action=search" method="post">
            Inserisci il nome della prenotazione da cercare<input type="text" name="nome"><br>
            <input type="submit" value="Cerca dal nome">
            </form>
        </li>
        <li>
            <form action="homepage.php?action=search" method="post">
            Inserisci la data di ingresso della prenotazione da cercare<input type="date" name="ingresso"><br>
            <input type="submit" value="Cerca dalla data">
            </form>
            </li>
            </ul>
        <h2>Lista prenotazioni</h2>
        <?php
        if (count($reservations)) {
            //renderizzazione della lista delle prenotazioni
            $bookDate = "";
            $bookYear = "";
            foreach ($reservations as $res) {
                $date = substr($res["ingresso"], -5);
                $year = substr($res["ingresso"], 0, 4);
                if ($year != $bookYear) { ?>
                    <h3><?= $year ?></h3><?php
                }
                $bookYear = $year;
                if ($date != $bookDate) { ?>
                    <div><?= $date ?></div><br> <?php
                }
                $bookDate = $date;?>
                <ul>
                <li><pre><?= $res["nome"] . ", " . $res["posti"] . ", " . $res["uscita"] ?></pre><br>
                <li><form action="homepage.php?action=delete" method="post">
                <input type="submit" value="Elimina">
                <input type="hidden" name="Id" value="<?= $res["Id"] ?>">
                </form></li>
                <li><form action="edit.php?action=fromhome" method="post">
                <input type="submit" value="Modifica">
                <input type="hidden" name="Id" value="<?= $res["Id"] ?>">
                </form></li></ul><?php
            }
        } 
        else {
            //renderizzazione messaggio assenza prenotazioni
            ?><div>Non ci sono prenotazioni!</div><?php
        }
}
?>
    </div>
    <ul>
        <li>
            <form action="add.php" method="post">
                <input type="submit" value="Aggiungi una nuova prenotazione">
                <input type="hidden" name="home" value="0">
            </form>
        </li>
        <li><a href="trash.php">Mostra il cestino</a><br></li>
        <li><a href="historic.php">Mostra lo storico</a></li>
    </ul>
</body>

</html>