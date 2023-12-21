<?php
namespace Model;

use PDO;

class Connection{



    protected $hostname = "192.168.157.185";
    protected $dbname = "coupon";
    protected $user = "chiara";
    protected $pass = "Chiara-dev1";
    protected $conn;

    public function __construct(){
        $this->conn = new PDO("mysql:host=$this->hostname;dbname=$this->dbname",$this->user,$this->pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    }

    public function getConnection(){
        return $this->conn;
    }



}


