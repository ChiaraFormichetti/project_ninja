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
        if ($date != $now ) {
            $result["errors"][] = "Errore nell'inserimento automatico della data";
        }
        if(!preg_match('/^[a-z0-9._-]+@[a-z]+\.(com|it)$/', $parameters['email'])){
            $result["errors"][] = "Errore nell'inserimento della mail";
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

}