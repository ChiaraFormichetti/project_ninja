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
        return $this->response;
    }


    public function post(Request $request): Response
    {
        $action = $request->action;
        $body = $request->body;

        $methodName = 'post'.ucfirst($action);

        $checkColumnResult = $this->beneficiariesManager->checkColumn('beneficiaries', $body);
        if(!$checkColumnResult['success']){
            return $this->response->setErrors(['message' => $checkColumnResult['errors']]);
        }
        $checkValueResult = $this->beneficiariesManager->addErrorManager($body);
        if(!$checkValueResult['success']){
           return $this->response->setErrors(['message' => $checkValueResult['errors']]);
        }
        $result = call_user_func([$this->beneficiariesManager, $methodName], $body);
        if(!$result){
            $this->response->setErrors(['message' => 'Il metodo non esiste']);
            $this->response->setErrorCode(Response::HTTP_CODE_ERROR_METHOD_NOT_FOUND);
        } else {
            if (!$result['errors']) {
                $this->response->setSuccess(true);
            } else { 
                $this->response->setErrors(['message' => $result['errors']]);
                $this->response->setSuccess(false);
            } 
        }
        return $this->response;
    }
    public function delete(Request $request): Response
    {
        return $this->response;
    }


}
