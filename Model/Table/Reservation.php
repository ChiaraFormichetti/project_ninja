<?php
namespace Model\Table;

class Reservation extends Table {
    protected $id;
    protected $nome;
    protected $posti;
    protected $ingresso;
    protected $uscita;
    protected $cancellazione;
    
    public function __construct(){
        parent::__construct();
    }
}

