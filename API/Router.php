<?php

namespace Api;

use Api\Request\Request;
use Api\Response\Response;

class Router
{

    public function routeRequest()
    {
        //specifica gli origini che hanno accesso alle risorse (* li indica tutti)
        header("Access-Control-Allow-Origin: *");
        //indica i metodi http consentiti quando si accede alla risorsa
        header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE, OPTIONS");
        //lettura metodo
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        //ottenimento dei parameters
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = parse_url($url);

        parse_str($urlParts['query'] ?? '', $parameters);
        //ottenimento del body
        $body = [];
        if ($method === 'post') {
            //$body =json_decode(file_get_contents('php://input'), true);
            $body = $_POST;
        }

        //lettura del servizio
        $action = null;
        $values = [];
        preg_match('/\/api\/(\w+)(\/(\w+))(\/([^?]*))?/', $url, $matches);
        if (isset($matches[1])) {
            $serviceName = $matches[1];
        };
        if (isset($matches[3])) {
            $action = $matches[3];
        }
        if (isset($matches[5])) {
            $values = explode('/', $matches[5]);
        }

        $request = new Request($parameters, $body, $action, $values);

        $service = $this->instantiateService($serviceName, $request);

        
        $result = $service->$method($request);
        //controllare se il metodo esiste prima di richiamarlo e settare gli errori in caso negativo

        $result->toJson();

    }

    private function instantiateService(string $serviceName, Request $request): object
     {
        $serviceClassName = '\\Api\\Service\\' . ucwords($serviceName) . 'Service';

        if (class_exists($serviceClassName)) {
            return new $serviceClassName($request);
        } else {
            // Restituzione di un errore nel caso la classe del servizio non esista
            $result = new Response();
            $result->setSuccess(false);
            $result->setErrors(['message' => 'La classe non esiste']);
            $result->setErrorCode(Response::HTTP_CODE_ERROR_BAD_REQUEST);

            return $result;
        }
    }

}
