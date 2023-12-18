<?php

namespace Api\Service;

use Api\Request\Request;
use Api\Response\Response;
use Controller\ReservationManager;


class ReservationService extends BaseService
{
    protected $reservationManager;
    protected $response;
    public function __construct()
    {
        $this->reservationManager = new ReservationManager();
        $this->response = new Response();
    }
    public function getActionMethod($action, ...$params)
    {

        $methodName = 'get' . ucfirst($action);
        if (method_exists($this->reservationManager, $methodName)) {
            //funzione per passare un numero variebile di argomenti al metodo
            $data = call_user_func_array([$this->reservationManager, $methodName], $params);
        } else {
            $this->response->setErrors(['message' => 'Il metodo non esiste']);
            $this->response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
        }
        if (isset($data)) {
            if (is_array($data) && count($data)) {
                $this->response->setData($data);
            } else if (is_string($data)) {
                $this->response->setErrors(['message' => $data]);
            } else {
                $this->response->setErrors(['message' => 'Non ci sono prenotazioni']);
            }
        }
        return $this->response;
    }



    public function get(Request $request): Response
    {
        $action = $request->action;
        $parameters = $request->parameters;
        $values = $request->values;
        $page = null;
        $reservationForPage = null;
        $root = null;
        switch ($action) {
            case 'historicReservations': {
                    if (count($values) === 1) {
                        $page = $values[0];
                    }
                    if (count($values) === 2) {
                        $reservationForPage = $values[1];
                    }
                    return $this->getActionMethod($action, $page, $reservationForPage, $parameters);
                    break;
                }
            case 'trashReservations': {
                    if (count($values) === 1) {
                        $page = $values[0];
                    }
                    if (count($values) === 2) {
                        $reservationForPage = $values[1];
                    }
                    return $this->getActionMethod($action, $page, $reservationForPage, $parameters);
                    break;
                }
            case 'reservationById': {
                    if (count($values) === 1) {
                        $id = $values[0];
                    }
                    return $this->getActionMethod($action, $id);
                    break;
                }

            case 'search': {
                    if (count($values) === 1) {
                        $root = $values[0];
                    }
                    if (array_key_exists('name', $parameters) || array_key_exists('enter', $parameters)) {
                        $name = $parameters['name'] ?? null;
                        $enter = $parameters['enter'] ?? null;
                        switch ($root) {
                            case 'historic': {
                                    $action .= ucfirst($root);
                                    return $this->getActionMethod($action, $name, $enter);
                                    break;
                                }
                            case 'trash': {
                                    $action .= ucfirst($root);
                                    return $this->getActionMethod($action, $name, $enter);
                                    break;
                                }
                            default: {
                                    return $this->getActionMethod($action, $name, $enter);
                                    break;
                                }
                        }
                    } else {
                        $this->response->setErrors(['message' => 'Parametri di ricerca non validi!']);
                    }
                    break;
                }
            default: {
                    if (is_numeric($action)) {
                        $page = $action;
                        if (count($values) >= 1) {
                            $reservationForPage = $values[0];
                        }
                    }

                    $data = $this->reservationManager->getReservations($page, $reservationForPage, $parameters);
                    if (is_array($data) && count($data)) {
                        $this->response->setData($data);
                    } else {
                        //se data è una stringa => ritorna l'errore
                        if (is_string($data)) {
                            $this->response->setErrors(['message' => $data]);
                        }
                        //Non abbiamo errori e quindi semplicemente non ci sono prenotazioni
                        $this->response->setErrors(['message' => 'Non ci sono prenotazioni']);
                    }
                    return $this->response;
                    break;
                }
        }
    }




    public function delete(Request $request): Response
    {
        $action = $request->action;
        $values = $request->values;
        $id = null;
        $response = new Response();
        if ($action) {
            if (count($values) === 1 && is_numeric($values[0])) {
                $id = $values[0];
                $methodName = 'delete' . ucfirst($action);
                if (method_exists($this->reservationManager, $methodName)) {
                    $success = call_user_func([$this->reservationManager, $methodName], $id);
                    if ($success) {
                        if (is_string($success)) {
                            $response->setErrors(['message' => $success]);
                        } else {
                            $response->setSuccess(true);
                        }
                    } else {
                        $response->setErrors(['message' => 'Non è stato possibile cancellare la prenotazione']);
                    }
                } else {
                    $response->setErrors(['message' => 'Il metodo non esiste']);
                    $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
                }
            } else {
                $response->setErrors(['message' => "Non è presente l'id da cancellare"]);
            }
        } else {
            $response->setErrors(['Non è presente la rotta']);
        }
        return $response;
    }

    public function postActionMethod($action, ...$params)
    {
        $methodName = 'post' . ucfirst($action) . 'Reservation';
        if (method_exists($this->reservationManager, $methodName)) {
            $success = call_user_func_array([$this->reservationManager, $methodName], $params);
        } else {
            $this->response->setErrors(['message' => 'Il metodo non esiste!']);
            $this->response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
        }
        if ($success) {
            if (is_string($success)) {
                $this->response->setErrors(['message' => $success]);
            } else {
                $this->response->setSuccess(true);
            }
        } else {
            $this->response->setErrors(['message' => 'Non è stato possibile modificare/aggiungere la prenotazione']);
        }
        return $this->response;
    }

    public function post(Request $request): Response
    {
        $action = $request->action;
        $body = $request->body;
        $values = $request->values;
        $id = null;
        switch ($action) {
            case 'add': {
                    if ($body != []) {
                        $errorColumns = $this->reservationManager->checkColumns($body);
                        if (!$errorColumns) {
                            $checkAddErrors = $this->reservationManager->errorMan($body);
                            if ($checkAddErrors['success']) {
                                $this->postActionMethod($action, $body);
                            } else {
                                foreach ($checkAddErrors['errors'] as $error) {
                                    $this->response->setErrors(['message' => $error]);
                                }
                            }
                        } else {
                            $this->response->setErrors(['message' => $errorColumns]);
                        }
                    } else {
                        $this->response->setErrors(['message' => 'Non ci sono valori da inserire']);
                    }
                    return $this->response;
                    break;
                }
            case 'edit': {
                    if (count($values) === 1 && is_numeric($values[0])) {
                        $id = $values[0];
                        if ($body != []) {
                            $errorColumns = $this->reservationManager->checkColumns($body);
                            if (!$errorColumns) {
                                $checkEditErrors = $this->reservationManager->errorMan($body);
                                if ($checkEditErrors['success']) {
                                    $this->postActionMethod($action, $body, $id);
                                } else {
                                    foreach ($checkEditErrors['errors'] as $error) {
                                        $this->response->setErrors(['message' => $error]);
                                    }
                                }
                            } else {
                                $this->response->setErrors(['message' => $errorColumns]);
                            }
                        } else {
                            $this->response->setErrors(['message' => 'Non ci sono valori da modificare']);
                        }
                    } else {
                        $this->response->setErrors(['message' => "Non è presente l'id della prenotazione da modificare"]);
                    }
                    return $this->response;
                    break;
                }
            case 'trash': {
                    if (count($values) === 1 && is_numeric($values[0])) {
                        $id = $values[0];
                        $this->postActionMethod($action, $id);
                    } else {
                        $this->response->setErrors(['message' => "Non è presente l'id della prenotazione da spostar nel cestino"]);
                    }
                    return $this->response;
                    break;
                }
            case 'restore': {
                    if (count($values) === 1 && is_numeric($values[0])) {
                        $id = $values[0];
                        $this->postActionMethod($action, $id);
                    } else {
                        $this->response->setErrors(['message' => "Non è presente l'id della prenotazione da ripristinare"]);
                    }
                    return $this->response;
                    break;
                }
        }
    }
}
