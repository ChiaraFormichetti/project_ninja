<?php

namespace Controller;

use DateTime;
use Model\Storage\BeneficiariesStorage;

class BeneficiariesManager extends BaseManager
{
    protected $beneficiariesStorage;

    public function __construct()
    {
        $this->beneficiariesStorage = new BeneficiariesStorage();
    }

    public function addErrorManager(array $parameters): array
    {   
        $result = [
            "success" => false,
            "errors" => []
        ];

        $date = new DateTime($parameters['date']);
        $now = new DateTime();
        if(!is_string($parameters['name']) && !is_string($parameters['surname']) )
        { 
            $result["errors"][] = "Errore nell'inserimento del nome/cognome";
        }
        if (!preg_match('/\d{3}[A-Z]{2}\d{2}/', $parameters['code'])){
            $result["errors"][] = "Errore nell'inserimento del codice";
        }        
        if ($date->format('Y-m-d') !== $now->format('Y-m-d')) {
            $result["errors"][] = "Errore nell'inserimento automatico della data";
        }
        if(!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $parameters['email'])){
            $result["errors"][] = "Errore nell'inserimento della mail";
        }
        if(!$parameters['id']){
            $result["errors"][] = "Errore nell'inserimento dell'id";
        }

        if($result["errors"] === []){
            $result["success"] = true;
        }
        return $result; 
    }

    public function postAdd(array $body): array {
        $add = $this->beneficiariesStorage->postAdd($body);
        return $add;
    }

    public function getId(int $id): array{
        $check = $this->beneficiariesStorage->getId($id);
        return $check;
    }

}