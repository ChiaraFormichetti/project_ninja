<?php

namespace Model;
//Fare un vero Storage senza tutte queste cose inutili
class ReservationStorage extends BaseStorage
{
    protected $connection;

    public function __construct()
    {
        parent::__construct();
    }

    //funzione per aggiungere una riga al database
    public function addRow($nome, $num, $ingresso, $uscita)
    {
        $ris = $this->connection->query("INSERT INTO `prenotazioni`(nome,posti,ingresso,uscita)
        VALUES('$nome',$num,'$ingresso','$uscita');");
        return $ris->rowCount();
    }
    //funzione per stampare le prenotazioni raggruppandole per data
    public function printReservationByDate($date){
       $reservation = [];

        foreach ($this->connection->query("SELECT * FROM `prenotazioni` WHERE ingresso='$date' AND cancelazzione=0") as $row) {
            $reservation[] = $row;
        }
        return $reservation;
        
    }
    
    //il group by non funziona
    //funzione per prendere tutte le prenotazioni dal database
    public function getRes()
    {
        $reservation = [];
        $now = date('Y-m-d');
    
        foreach ($this->connection->query("SELECT* FROM `prenotazioni` WHERE cancellazione=0 AND ingresso>='$now' ORDER BY ingresso ") as $row) {
            $reservation[] = $row;
        }
        return $reservation;
    }

    //funzione per trovare una prenotazione nel database dato il suo id
    public function getResById($id)
    {
        $now = date('Y-m-d');
        $res = $this->connection->query("SELECT nome,posti,ingresso,uscita FROM `prenotazioni` WHERE Id = '$id' AND cancellazione=0 AND ingresso>='$now'");
        return $res->fetch();
    }

    /*public function getPosti(){
        $posti = $this->connection->query("SELECT MAX(posti) AS PostoMax FROM `prenotazioni`");
        return $posti->fetch();

    }
    public function getPrenMaxNum($posti){
        $pren = [];
        foreach($this->connection->query("SELECT * FROM `prenotazioni` WHERE posti = '$posti'") as $row){
            $pren[] = $row;
        }
        return $pren;
    }*/

    public function getPrenMaxNum()
    {
        $pren = [];
        foreach ($this->connection->query("SELECT* FROM `prenotazioni` WHERE posti = (SELECT MAX(posti) FROM `prenotazioni`)") as $row) {
            $pren[] = $row;
        }
        return $pren;
    }
    
    //funzione per modificare una prenotazione
    public function modify($nome,$posti,$ingresso,$uscita,$id)
    {
        $mod = $this->connection->query("UPDATE `prenotazioni` SET nome='$nome', posti='$posti', ingresso='$ingresso', uscita='$uscita' WHERE id='$id' ");
        return $mod;
    }


    public function deleteReservation($id){
        $delete = $this->connection->query("UPDATE `prenotazioni` SET cancellazione =1 WHERE id='$id'");
        return $delete;
    }
    public function ripristinaReservation($id){
        $ripristina = $this->connection->query("UPDATE `prenotazioni` SET cancellazione=0 WHERE id='$id'");
        return $ripristina;
    }
    //funzione per creare lo storico delle prenotazioni
    public function createHistoric()
    {
        $create = $this->connection->query("CREATE TABLE `historic` AS SELECT * FROM `prenotazioni` WHERE 1 = 2");
        return $create;
    }

    //funzione che cerca le prenotazioni passate nella tabella prenotazioni
    public function searchPastRes()
    {
        $now = date('Y-m-d');
        $past = [];

        foreach ($this->connection->query("SELECT * FROM `prenotazioni` WHERE cancellazione=0 AND ingresso<'$now' ORDER BY ingresso") as $row) {
            $past[] = $row;
        }
        return $past;
    }

    //funzione che cerca le prenotazioni nel cestino
    public function searchInTrash(){
        $trash = [];
        foreach($this->connection->query("SELECT * FROM `prenotazioni` WHERE cancellazione = 1  ORDER BY ingresso") as $row){
            $trash[] = $row;
        }
        return $trash;
   
   
    }   

    /*funzione per aggiungere l'array all'historic
     public function addHist($histor){
        $hist = foreach($histor as $his){
            $this->connection->query("INSERT INTO `historic`(id,nome,posti,ingresso,uscita) VALUES ($his);");
            }
        return ;}
        /*
        $f = $this->connection->query("INSERT INTO `historic`(id,nome,posti,ingresso,uscita) VALUES($histor["id"],$histor["nome"],$histor["posti"],$histor["ingresso"],$histor["uscita"]);");
        return $f->rowCount();

     }*/

    public function addHist()
    {
        $now = date('Y-m-d');
        $s = $this->connection->query("INSERT INTO `historic`(id,nome,posti,ingresso,uscita) VALUES (SELECT * FROM `prenotazioni` WHERE ingresso<'$now' )");
        return $s->rowCount();
    }
    public function searchReservation($nome=null, $ingresso=null){
        
        $sql = '';
        $search = [];

        //scrittura query in base ai valori di $nome e $ingresso

        if($nome){
            $now = date('Y-m-d');
            $sql = "SELECT * FROM `prenotazioni` WHERE nome = '$nome' AND cancellazione=0 AND ingresso>'$now' ORDER BY ingresso";
         }
         else if ($ingresso){
            
            $now = date('Y-m-d');
            $sql = "SELECT * FROM `prenotazioni` WHERE ingresso = '$ingresso' AND cancellazione=0 AND ingresso>'$now' ORDER BY ingresso";
        }
        else if($nome && $ingresso){
            $now = date('Y-m-d');
            $sql = "SELECT * FROM `prenotazioni` WHERE ingresso = '$ingresso' AND nome='$nome' AND cancellazione=0 AND ingresso>'$now' ORDER BY ingresso";
        }

        if ($sql) {
            foreach($this->connection->query($sql) as $row){
                $search[] = $row;
            }
        }

        return $search;
    }
}
