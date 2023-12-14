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
                //se data è un array e ha almneo un elemento (controllo necessario perchè l'array vuoto è comunque un array mentre
                //se faccio il count a una stringa me la considera come un array con un elemento)               
                if (is_array($data) && count($data)) {
                    $response->setData($data);
                } else {
                    //se data è una stringa => ritorna l'errore
                    if (is_string($data)) {
                        $response->setErrors(['message' => $data]);
                    }
                    //Non abbiamo errori e quindi semplicemente non ci sono prenotazioni
                    $response->setErrors(['message' => 'Non ci sono prenotazioni']);
                }
            } else {
                $response->setErrors(['message' => 'Il metodo non esiste']);
                $response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            //non abbiamo una action ma abbiamo dei parametri => siamo nella search
            if ($parameters != []) {
                if (array_key_exists('id', $parameters) && count($parameters) == 1) {
                    $data = $this->reservationManager->getReservationById($parameters['id']);
                } /*else if (array_key_exists('name', $parameters) || array_key_exists('enter', $parameters)) {                  
                    //se non esiste nessun valore nell'array di parametri associato alla chiave name o enter assegna alle due variabili che passeremo 
                    //allo storage valore null
                    $name = $parameters['name'] ?? null;
                    $enter = $parameters['enter'] ?? null;
                    $data = $this->reservationManager->searchReservations($name, $enter);
                }*/ else {
                    //se non abbiamo parametri e non abbiamo action è il caso generale in cui stampiamo tutte le prenotazion
                    $data = $this->reservationManager->getPage($parameters);
                }
            } else {

                //$data = $this->reservationManager->getReservations()
                //$data = $this->reservationManager->getPage();
            }
            //controlliamo che i dati che ci arrivano di risposta siano un array e che abbiano almeno un elemento
            if (is_array($data) && count($data)) {
                //creiamo la risposta
                $response->setData($data);
            } else {
                //se il dato che ci arriva è una stringa => abbiamo l'errore dallo storage
                if (is_string($data)) {
                    $response->setErrors(['message' => $data]);
                } //se ci arriva un array vuoto allora semplicemente non ci sono prenotazioni
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
            if ($parameters != []) {
                //controlliamo in generale che le chiavi dei parametri corrispondano all'array delle colonne di reservation
                //controlliamo che esista il parametro con chiave id
                if ($parameters['id']) {
                    $id = $parameters['id'];
                    $success = $this->reservationManager->deleteReserations($id);
                } else {
                    $response->setErrors(['message' => 'Non è presente l id della prenotazione da cancellare definitivamente']);
                }
                if ($success) {
                    if (is_string($success)) {
                        $response->setErrors(['message' => $success]);
                    } else {
                        $response->setSuccess(true);
                    }
                } else {
                    $response->setErrors(['message' => 'Non è stato possibile cancellare la prenotazione']);
                }
            }
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
            if (array_key_exists('id', $body)) {
                $id = $body['id'];
                if (count($body) == 1) {
                    $success = $this->reservationManager->trashReservations($id);
                    if (!$success) {
                        $response->setErrors(['message' => 'Non è stato possibile spostare la prenotazione nel cestino']);
                    }
                } else {
                    //togliamo l'id dal body poichè non verrà mai modificato 
                    unset($body['id']);
                    if (array_key_exists('cancellazione', $body)) {
                        $success = $this->reservationManager->editReservations($body, $id);
                        if (!$success) {
                            $response->setErrors(['message' => 'Non è stato possibile ripristinare la prenotazione']);
                        }
                    } else {
                        //prima controlliamo che le colonne corrispondono alla nostra tabella
                        $errorColumns = $this->reservationManager->checkColumns($body);
                        if (!$errorColumns) {
                            //poi controlliamo che i valori siano validi
                            $checkEditErrors = $this->reservationManager->errorMan($body);
                            if ($checkEditErrors['success']) {
                                //se è tutto verificato allora modifichiamo
                                $success = $this->reservationManager->editReservations($body, $id);
                                if (!$success) {
                                    //errore nella modifica
                                    $response->setErrors(['message' => 'Non è stato possibile modificare la prenotazione']);
                                } else {
                                    //errore nell'inserimento da parte dell'utente dei valori
                                    foreach ($checkEditErrors['errors'] as $error) {
                                        $response->setErrors(['message' => $error]);
                                    }
                                }
                            }
                        } else {
                            //errore nell'inserimento delle colonne da modificare
                            $response->setErrors(['message' => $errorColumns]);
                        }
                    }
                }
            } else {
                //prima controlliamo che le colonne inserite siano effettivamente nella tabella
                $errorColumns = $this->reservationManager->checkColumns($body);
                if (!$errorColumns) {
                    //poi controlliamo che i dati inseriti dall'utente siano validi
                    $checkAddErrors = $this->reservationManager->errorMan($body);
                    if ($checkAddErrors['success']) {
                        //se è tutto valido allora aggiungiamo la prenotazione
                        $success = $this->reservationManager->addReservations($body);
                        if (!$success) {
                            //a questo punto controlliamo se l'aggiunta è andata o meno a buon fine
                            $response->setErrors(['message' => 'Non è stato possibile aggiungere la prenotazione']);
                        }
                    } else {
                        //se i valori inseriti non sono validi settiamo l'errore (errore inserimento dati o errore inserimento della data(formato))
                        foreach ($checkAddErrors['errors'] as $error) {
                            $response->setErrors(['message' => $error]);
                        }
                    }
                } else {
                    //settiamo l'errore nel caso in cui le colonne che si vogliono aggiungere non sono nella nostra tabella
                    $response->setErrors(['message' => $errorColumns]);
                }
            } //se success esiste
            if ($success) {
                //ma è una stringa => è un messaggio di errore ( arriva il messaggio di errore specifico)
                if (is_string($success)) {
                    $response->setErrors(['message' => $success]);
                } else {
                    //se non è una stringa allora success is true e l'operazione è andata a buon fine
                    $response->setSuccess(true);
                }
            }
            return $response;
        }
    }
}
