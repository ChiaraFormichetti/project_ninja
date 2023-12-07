<?php
namespace Model\Table;

class Reservation extends Table {
    protected $id = null;
    protected $name = '';
    protected $seats = 0;
    protected $enter = '';
    protected $exit = '';
    protected $cancellazione = false;
    
    public function __construct(){
        parent::__construct();
        $this->tableColumns = $this->getTableColumns();
    }
}

