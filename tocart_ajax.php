<?php
session_start();
require('lib/db.php');
require('lib/class.cart.php');

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$session_id = $json["session_id"];
$product_id = $json["product_id"];
$product_size = $json["product_size"];


$cart = new Cart($product_id, $sql, $session_id, $product_size);


    if ($cart->add()) {
    
        echo json_encode('');
    
    } else {
    
        echo json_encode('');
    
    }
    


