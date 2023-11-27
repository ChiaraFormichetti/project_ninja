<?php

namespace Model;

class UserStorage{


    protected $connection;

    public function __construct(){
        $this->connection;
        $var = new Connection();
        $this->connection = $var->getConnection();
    }


    public function getUser(){
        $users = [];

        foreach($this->connection->query("SELECT * FROM users") as $row){
         $users[] = $row;
         
            

        }
        return $users;
     }

    public function getUserByName($name){

      $user = $this->connection->query("SELECT * FROM users WHERE nome = '$name'");
      return $user->fetch();
    }

    public function getUserById($id){
        $user = $this->connection->query("SELECT * FROM users WHERE indirizzo = '$id' ");
        return $user->fetch();
    }


     
     

   
}