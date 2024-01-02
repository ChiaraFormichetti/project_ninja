<?php

namespace Api\Service;

use Api\Request\Request;
use Api\Response\Response;
use Controller\CodesManager;

class CodesService extends BaseService
{
    protected $codesManager;
    protected $response;

    public function __construct() 
    {
        $this->codesManager = new CodesManager();
        $this->response = new Response();
    }

    
    public function get(Request $request): Response 
    {
        $action = $request->action;
        $values = $request-> values; 
        $code = null;
        $result = [
            'data' => [],
            'errors' => [],
        ];

        $methodName = 'get' . ucfirst($action);

        if(count($values) === 1){
            if(preg_match('/\d{3}[A-Z]{2}\d{2}/', $values[0])){
                $code = $values[0];
            }
            $result = call_user_func([$this->codesManager, $methodName], $code);
        }
        if(!$result){ 
            $this->response->setErrors(['message' => 'Il metodo non esiste']);
            $this->response->setSuccess(false);
            $this->response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
        } else {
            //da rivedere 
            //meglio se l'oggetto data che ci ritorna sia un array di dati e errori
            if (!$result['errors']) {
                $this->response->setData($result['data']);
            } else { 
                $this->response->setErrors(['message' => $result['errors']]);
                $this->response->setSuccess(false);
            } 
        }
        return $this->response;
    }
    public function post(Request $request): Response
    {
        return $this->response;
    }
    public function delete(Request $request): Response
    {
        return $this->response;
    }

}