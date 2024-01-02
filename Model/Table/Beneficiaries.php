<?php
namespace Model\Table;

class Beneficiaries extends Table {
    protected $id;
    protected $code;
    protected $name;
    protected $surname;
    protected $email;
    protected $date;
    
    public function __construct(){
        parent::__construct();
    }
}

