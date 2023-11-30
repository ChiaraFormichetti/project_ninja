<?php

namespace Api\Response;

class Response {
    
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_ERROR_METHOD_NOT_FOUND = 404;
    const HTTP_CODE_ERROR_BAD_REQUEST = 400;
    
    public $data    = [];
    public $success = true;
    public $errors  = [];
    public $code    = self::HTTP_CODE_OK;


    //metodi per gestione dei dati
    function __construct($data = [], $success = true, $errors = []){
        $this->data    = $data;
        $this->success = $success;
        $this->errors  = $errors;
    }

    public function setData(array $data){
        $this->data = $data;
    }
    public function setSuccess(bool $success){
        $this->success = $success;
    }
    public function setErrors(array $errors){
        $this->errors = $errors;
    }
    public function setErrorCode($code){
        $this->code = $code;
    }
    public function toArray(){
        return [
            'data'    => $this->data,
            'success' => $this->success,
            'errors'  => $this->errors,
        ];
    }
    public function toJson(){
        return json_encode($this->toArray()); 
    }
}