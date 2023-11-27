<?php

namespace Api\Service;

use Api\Request\Request;
use Api\Response\Response;

abstract class BaseService {
    protected $request;

    abstract function get(Request $request): Response;
    abstract function delete(Request $request): Response;
    abstract function post(Request $request): Response;
}