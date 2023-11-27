<?php
namespace Model\Table;

class Reservation extends Table {
    protected $nome = '';
    protected $posti = 0;
    protected $ingresso = '';
    protected $uscita = '';
    protected $cancellazione = false;
    
    public function __construct(){
        parent::__construct();
        $this->mapPropertiesToColumns();
    }
}

