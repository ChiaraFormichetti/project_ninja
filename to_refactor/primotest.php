<?php

$host = "192.168.157.185";
$user = "root";
$password = "";
$database = "primo_test";


$mysqli = new mysqli($host, $user,$password,$database);
if ($mysqli === false) {
   die("Errore di connessione: " . $mysqli->connect_error);
  }
  


  

//Creazione tabella utenti
$users = "CREATE TABLE users(
    email VARCHAR(255) NOT NULL PRIMARY KEY ,
    nome VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    data_di_nascita DATE NOT NULL,
    telefono INT NOT NULL,
    indirizzo INT NOT NULL,
    FOREIGN KEY(indirizzo) 
    REFERENCES `address`(id) 
    ON DELETE CASCADE ON UPDATE CASCADE

    
    )";


//Creazione tabella indirizzi
$addres = "CREATE TABLE `address`(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    provincia VARCHAR(255) NOT NULL,
    stato VARCHAR(255) NOT NULL,
    
    

    )";


if($mysqli->query($users === true)){
echo "Tabella utenti creata con successo!";
}
else {
    echo "Errore durante la creazione della tabella utenti!" . $mysqli->error;
}

if($mysqli->query($address === true)){
    echo "Tabella indirizzi creata con successo!";
    }
    else {
        echo "Errore durante la creazione della tabella indirizzi!" . $mysqli->error;
    }

$aggiunta = "INSERT INTO users (nome, surname, data_di_nascita, email, indirizzo VALUES
('Chiara','Formichetti','24/02/1999','chiara.formichetti@outlook.it', 23),
('Giordano','Conti','14/08/1996','giordano.conti@gmail.com',5),
('Francesca','Bruschi','22/12/1970','ltag@libero.it',9),
('Marco','Rossi','13/09/1992','marco.rossi@gmail.com',2))
";
    if($mysqli->query($aggiunta) === true){
        echo "Utenti inseriti con successo";

    }
    else{
        echo "Errore durante inserimento: ". $mysqli->error;
    }


$aggiunta2 = "INSERT INTO address (provincia, stato VALUES
('Terni','Italia'),
('Glasgow','Scozia'),
('Roma','Italia'),
('Berlino','Germania'))
";
    if($mysqli->query($aggiunta) === true){
        echo "Utenti inseriti con successo";

    }
    else{
        echo "Errore durante inserimento: ". $mysqli->error;
    }
    if($mysqli->query($aggiunta2) === true){
        echo "Address inseriti con successo";

    }
    else{
        echo "Errore durante inserimento: ". $mysqli->error;
    }

















$mysqli->close();
?>