<?php

namespace Api;

require '/var/www/vhosts/chiara-dev/vendor/autoload.php';

//istanziamo il router 
$router = new Router();

$router->routeRequest();



