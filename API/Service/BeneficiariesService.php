<?php

namespace Api\Service;

use Api\Request\Request;
use Api\Response\Response;
use Controller\BeneficiariesManager;


class BeneficiariesService extends BaseService
{
    protected $beneficiariesManager;
    protected $response;

    public function __construct()
    {
        $this->beneficiariesManager = new BeneficiariesManager();
        $this->response = new Response();
    }


    public function get(Request $request): Response
    {
        $action = $request->action;
        $values = $request->values;
        $param = null;

        $methodName = 'get' . ucfirst($action);

        if (count($values) === 1 && preg_match('/^\d+$/', $values[0])) {
            $param = $values[0];
            if (method_exists($this->beneficiariesManager, $methodName)) {
                $result = call_user_func([$this->beneficiariesManager, $methodName], $param);
                if (empty($result['errors'])) {
                    $this->response->setData($result['data']);
                } else {
                    //via tutti i message
                    $this->response->setErrors(['message' => $result['errors']]);
                    $this->response->setSuccess(false);
                }
            } else {
                $this->response->setErrors(['message' => 'Il metodo non esiste']);
                $this->response->setSuccess(false);
                $this->response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
            }
        } else {
            $this->response->setErrors(['message' => "L'id inserito non e' nel formato richiesto"]);
            $this->response->setSuccess(false);
            $this->response->setErrorCode(400);
        }
        return $this->response;
    }


    public function post(Request $request): Response
    {
        $action = $request->action;
        $body = $request->body;
        $result = [];

        $methodName = 'post' . ucfirst($action);

        $checkColumnResult = $this->beneficiariesManager->checkColumn('beneficiaries', $body);
        if (!$checkColumnResult['success']) {
            $errorColumnMessage = implode(',', $checkColumnResult['errors']);
            return $this->response->setErrors(['message' => $errorColumnMessage]);
        }
        $checkValueResult = $this->beneficiariesManager->addErrorManager($body);
        if (!$checkValueResult['success']) {
            $errorValueMessage = implode(',', $checkValueResult['errors']);
            return $this->response->setErrors(['message' => $errorValueMessage]);
        }
        if (method_exists($this->beneficiariesManager, $methodName)) {
            $result = call_user_func([$this->beneficiariesManager, $methodName], $body);
            if (!$result['errors']) {
                $this->response->setSuccess(true);
            } else {
                $this->response->setErrors(['message' => $result['errors']]);
                $this->response->setSuccess(false);
            }
        } else {
            $this->response->setErrors(['message' => 'Il metodo non esiste']);
            $this->response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
        }
        return $this->response;
    }

    public function delete(Request $request): Response
    {
        return $this->response;
    }
}
