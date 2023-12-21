<?php

namespace Model;

class AddressStorage{

    protected $connection;

    public function __construct()
    {
        $this-> connection;
        $var = new Connection();
        $this->connection = $var->getConnection();

        
    }
    public function getAddress(){
        $address = [];
        foreach($this->connection->query("SELECT * FROM indirizzi")as $row){
            $address[] = $row;
            
           
        }
        return $address;


    }

    public function getAddressById($id){
        $address = $this->connection->query("SELECT * FROM indirizzi WHERE id = $id");
        return $address->fetch();


    }

    
      
}
