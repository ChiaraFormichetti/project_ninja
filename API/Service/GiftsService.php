<?php

namespace Api\Service;

use Api\Request\Request;
use Api\Response\Response;
use Controller\GiftsManager;

class GiftsService extends BaseService
{
    protected $giftsManager;
    protected $response;

    public function __construct()
    {
        $this->giftsManager = new GiftsManager();
        $this->response = new Response();
    }


    public function get(Request $request): Response
    {
        $action = $request->action;
        $values = $request->values;
        $param = null;

        $methodName = 'get' . ucfirst($action);

        if (count($values) === 1 && preg_match('/^[A-Z]{1,10}$/', $values[0])) {
            $param = $values[0];
            if (method_exists($this->giftsManager, $methodName)) {
                $result = call_user_func([$this->giftsManager, $methodName], $param);
                if (empty($result['errors'])) {
                    $this->response->setData($result['data']);
                } else {
                    $this->response->setErrors(['message' => $result['errors']]);
                    $this->response->setSuccess(false);
                }
            } else {
                $this->response->setErrors(['message' => 'Il metodo non esiste']);
                $this->response->setSuccess(false);
                $this->response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            $this->response->setErrors(['message' => "Il type inserito non e' nel formato richiesto"]);
            $this->response->setSuccess(false);
            $this->response->setErrorCode(400);
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
