<?php
session_start();
require('lib/db.php');
require('lib/class.base.php');
require('lib/class.cart.php');

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$cartid = $json["id"];

$cart = new Cart($cartid, $sql, 'cart');
    
    if ($cart->delete()) {

        echo json_encode('Done!');

    } else {

        echo json_encode('Error!');

    }
?>