<?php

namespace Api\Request;

class Request {
    public $parameters;
    public $body;
    public $action;
    public $values;

    function __construct($parameters = [], $body = [], $action = null, $values = []){
        $this->parameters = $parameters;
        $this->body = $body;
        $this->action = $action;
        $this->values = $values;
    }
}