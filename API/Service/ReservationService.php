<?php

namespace Api\Service;

use Api\Request\Request;
use Api\Response\Response;
use Controller\ReservationManager;

class ReservationService extends BaseService
{
    protected $reservationManager;

    public function __construct()
    {
        $this->reservationManager = new ReservationManager();
    }

    public function get(Request $request): Response
    {

        $response = new Response();
        $action = $request->action;
        $parameters = $request->parameters;
        if ($action) {
            //per vedere lo storico o il trash posso farlo direttamente assegnandogli a loro una action
            //viene invocato il metodo con nome get<action>
            $methodName = 'get' . ucfirst($action);
            //controllo se il metodo esiste realmente
            //se esiste lo richiamo e gli passo i parametri
            if (method_exists($this->reservationManager, $methodName)) {
                $data = $this->reservationManager->$methodName(); // scriviamo il metodo che ci dà la/e prenotazione/i che vogliamo in base ai parametri che abbiamo
                if (count($data)) {
                    $response->setData($data);
                } else {
                    $response->setErrors(['message' => 'Non ci sono prenotazioni']);
                }
            } else {
                $response->setErrors(['message' => 'Il metodo non esiste']);
                $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            if ($parameters != [] ) {
                $name = $parameters['name'] ?? null;
                $enter = $parameters['enter'] ?? null;
                $data = $this->reservationManager->searchReservations($name, $enter);
            } else {
                $data = $this->reservationManager->getReservations();
            }
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
        $action = $request->action;
        $parameters = $request->parameters;
        $response = new Response();
        if ($action) {
            $methodName = 'delete' . ucfirst($action);
            if (method_exists($this, $methodName)) {
                $response = $this->$methodName($parameters);
            } else {
                $response->setErrors(['message' => 'Il metodo non esiste']);
                $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            if($parameters !=[]){
                $id = $parameters['id'];
                $success = $this->reservationManager->deleteReserations($id);
            } else {
                $response ->setErrors(['message' => 'Non è presente l id della prenotazione da cancellare definitivamente']);
            }
            if($success){
                $response->setSuccess(true);
            } else {
                $response->setErrors(['message' => 'Non è stato possibile cancellare la prenotazione']);
            }
            //$data = chiamata a res manager
            //$response->setData($data);
        }
        return $response;
    }

    public function post(Request $request): Response
    {
        $action = $request->action;
        $body = $request->body;
        $response = new Response();
        if ($action) {
            $methodName = 'post' . ucfirst($action);
            if (method_exists($this, $methodName)) {
                $response = $this->$methodName($body);
            } else {
                $response->setErrors(['message' => 'Il metodo non esiste']);
                $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            if(array_key_exists('id',$body)){
                $id = $body['id'];
                if(count($body)==1){
                    $success = $this->reservationManager->trashReservations($id);
                    if(!$success){
                        $response->setErrors(['message' => 'Non è stato possibile spostare la prenotazione nel cestino']);
                    }
                } else {
                    unset($body['id']);
                    $success = $this->reservationManager->editReservations($body,$id);
                    if (!$success){
                        $response->setErrors(['message' => 'Non è stato possibile modificare la prenotazione']);
                    }
                }
            } else {
                $success = $this->reservationManager->addReservations($body);
                if (!$success){
                    $response->setErrors(['message' => 'Non è stato possibile aggiungere la prenotazione']);
                }
            }
            if($success){
                $response->setSuccess(true);
            }

            //$data = chiamata a res manager
            //$response->setData($data);
        }
        return $response;
    }
}
