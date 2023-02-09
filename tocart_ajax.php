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

if ($product_size == 'Size') {

    echo json_encode('<p style="color: rgb(198, 62, 62);"><i class="fa fa-ban" aria-hidden="true"></i> Please select size.</p>');
} else {

    if ($cart->add()) {
    
        echo json_encode('<i class="fa fa-check-circle-o" aria-hidden="true"></i> In basket.');
    
    } else {
    
        echo json_encode('Problem with adding the product to the cart. Please try again.');
    
    }
    

}

