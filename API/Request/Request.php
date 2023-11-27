<?php

namespace Api\Request;

class Request {
    public $parameters;
    public $body;
    public $action;

    function __construct($parameters = [], $body = [], $action = null){
        $this->parameters = $parameters;
        $this->body = $body;
        $this->action = $action;
    }
}