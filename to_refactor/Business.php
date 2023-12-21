<?php

namespace Controller\Test;

use Model\AddressStorage;
use Model\UserStorage;

class Business{

    protected $users;
    protected $address;



    public function call_index($num){
        
        $this->address;
        $this->users;
        
        $prova2 = new AddressStorage();
        $prova = new UserStorage();
        $this->users = $prova->getUserById($num);
       

       
        $this->address = $prova2->getAddress();

        echo "L'indirizzo: " . ($num) . "  è " . json_encode($this->address[$num-1])."<br>";
        echo "l'utente associato è " .json_encode($this->users)."<br>";

        
    }


    
}
    









   