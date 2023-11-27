<?php

$request = $_SERVER['REQUEST_URI'];
switch($request){
    case '/': //questa è la prima base del routing
        require __DIR__ .'/views/home.php'; // qui mettiamo il percorso generico homepagge.html
        break;
    case '':
        require __DIR__ .'/views/home.php';
        break;
    case '/chi-siamo':
        require __DIR__ .'/views/chi-siamo.php';
        break;
    case '/contatti':
        require __DIR__ .'/views/contatti.php';
        break;
    default :
        require __DIR__ .'/views/404-php';
}