<?php

use Controller\Test\Business;
use Model\UserStorage;
use Controller\Test\Test;
use Model\AddressStorage;


require '/var/www/vhosts/chiara-dev/vendor/autoload.php';


$prova = new UserStorage();
$prova->getUser();
$users = $prova->getUser();
foreach ($users as $user) {
    
    echo "<pre>".json_encode($user)."</pre>";
}

$prova2 = new AddressStorage();
$prova2->getAddress(); 
$address = $prova2->getAddress();
foreach ($address as $addres) {
    
    echo "<pre>".json_encode($addres)."</pre>";
}


$num=3;
$prova3 = new Business();
$prova3->call_index($num);



$name='Francesca';

echo "Al nome " . $name . " è associato l'indirizzo: ";

$us = new UserStorage();
$res = $us->getUserByName($name);

$ad = new AddressStorage();
$userAddress = $ad->getAddressById((int) $res['indirizzo']);

echo json_encode($userAddress);


















?>