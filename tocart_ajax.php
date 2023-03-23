<?php
session_start();
require('lib/db.php');
require("lib/class.base.php");
require('lib/class.cart.php');

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$session_id = $json["session_id"];
$product_id = $json["product_id"];
$product_size = $json["product_size"];


$cart = new Cart(0, $sql);
$cart->session_id = $session_id;
$cart->product_id = $product_id;
$cart->product_size = $product_size;

    if ($cart->addCart()) {

        $cartproducts = $cart->getCart();

        $cart_total = 0;

        for ($i = 0; $i < count($cartproducts); $i++) {

            $cart_total += $cartproducts[$i]['pcs'];

        }
    
        echo json_encode($cart_total);
    
    } else {
    
        echo json_encode('Error!');
    
    }
    
?>


