<?php

namespace Api\Response;

class Response {
    
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_ERROR_METHOD_NOT_FOUND = 404;
    const HTTP_CODE_ERROR_BAD_REQUEST = 400;
    
    protected $data    = [];
    protected $success = true;
    protected $errors  = [];
    protected $code    = self::HTTP_CODE_OK;


    //metodi per gestione dei dati
    function __construct(array $data = [],bool $success = true,array $errors = []){
        $this->setData($data);
        $this->setSuccess($success);
        $this->setErrors($errors);
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getData(): array {
        return $this->data;
    }


    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getSuccess(): bool {
        return $this->success;
    }

    public function setErrors(array $errors): void 
    {
        $this->errors = $errors;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function setErrorCode($code): void
    {
        $this->code = $code;
    }

    public function getErrorCode(): int {
        return $this->code;
    }

    public function toArray(): array 
    {
        return [
            'data'    => $this->getData(),
            'success' => $this->getSuccess(),
            'errors'  => $this->getErrors(),
            'code'    => $this->getErrorCode()
        ];
    }

    public function toJson(): void
    {
        echo json_encode($this->toArray()); 
    }
}