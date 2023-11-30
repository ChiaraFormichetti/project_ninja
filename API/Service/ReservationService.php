<?php

namespace Api\Service;

use Api\Request\Request;
use Api\Response\Response;
use Controller\ReservationManager;

class ReservationService extends BaseService
{
protected $reservationManager;

    public function __construct(){
        $this->reservationManager = new ReservationManager();
    }
  
    public function get(Request $request): Response
    {

        $response = new Response();
        $action = $request->action;
        $parameters = $request->parameters;
        if ($action) {
            //viene invocato il metodo con nome get<action>
            $methodName = 'get' . ucfirst($action);
            //controllo se il metodo esiste realmente
            //se esiste lo richiamo e gli passo i parametri
            if (method_exists($this, $methodName)) {
                $response = $this->$methodName($parameters);// scriviamo il metodo che ci dà la/e prenotazione/i che vogliamo in base ai parametri che abbiamo
            } else {
                $response->setErrors(['message' => 'Il metodo non esiste']);
                $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            $data = $this->reservationManager->getAllRes();
            if (count($data)) {
                $response->setData($data);             
            } else {
                $response->setErrors(['message' => 'Non ci sono prenotazioni']);

            }
            
        }
        return $response;
    }



    public function delete(Request $request): Response
    {
        $action = $request -> action;
        $parameters = $request -> action;
        $response = new Response();
        if ($action){
            $methodName = 'delete' . ucfirst($action);
            if (method_exists($this, $methodName)){              
                $response = $this-> $methodName($parameters);
            } else {
                $response->setErrors(['message' => 'Il metodo non esiste']);
                $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            //$data = chiamata a res manager
            //$response->setData($data);
        }
        return $response;
    }

    public function post(Request $request): Response
    {
        $action = $request -> action;
        $body = $request -> action;
        $response = new Response ();
        if ($action){
            $methodName = 'post'. ucfirst($action);
            if(method_exists($this, $methodName)){
                $response =$this-> $methodName($body);
                } else {
                $response->setErrors(['message' => 'Il metodo non esiste']);
                $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            //$data = chiamata a res manager
            //$response->setData($data);
        }
        return $response;
    }

}
