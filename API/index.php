<?php

use Api\Request\Request;
use Api\Response\Response;
use Api\Service\ReservationService;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

//lettura metodo
$method = strtolower($_SERVER['REQUEST_METHOD']);
//ottenimento dei parameters
$url = $_SERVER['HTTP_REFERER'];
$urlParts = parse_url($url);

parse_str($urlParts['query'] ?? '',$parameters);
//ottenimento del body
$body = [];
if ($method=='post'){
    $body = json_decode(file_get_contents('php://input'), true);
}
//lettura del servizio

preg_match ('/\/api\/(\w+)\/(\w+)/', $url, $matches);
if(isset($matches[1])){
    $serviceName = $matches[1];
};
if(isset($matches[2])){
    $action = $matches[2];
}

$serviceClassName = $serviceName.'Service';   
if (!class_exists($serviceClassName)){
    //restituiscono risposta con errore 
    $result = new Response ();
    $result -> setSuccess(false);
    $result -> setErrors(['message' => 'La classe non esiste']);
    $result -> setCodeErrors(Response::HTTP_CODE_ERROR_BAD_REQUEST);
}

//se $service = 'test' allora la classe da istanziare è testService

//controllo esistenza $serviceClassName

$request = new Request($parameters, $body, $action);

// <domain>/api/reservation/action?<parameters> GET,DELETE gestire la possibile assenza di action
// <domain>/api/reservation/action POST -> lettura body
// <domain>/api/<service>/<action>
$service = new $serviceClassName();

//instanziazione del servizio
//new serviziodelcaso
//chiamata metodo base presente nel servizio astratto

$result = $service->$method($request);
//passondogli un oggetto Request
//new Request();

//restituzione del risultato
//come si setta il codice HTTP della risposta?
echo $result;
