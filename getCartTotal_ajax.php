<?php 
session_start();
require('lib/db.php');
require("lib/class.base.php");
require("lib/class.cart.php");

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$cart = new Cart(session_id(), $sql, 'cart');
$cart->customer_id = $_SESSION['logged_id'];
$cartproducts = $cart->getCart();

$cart_total = 0;

for ($i = 0; $i < count($cartproducts); $i++) {

    $cart_total += $cartproducts[$i]['pcs'];

}

if ($cart_total > 0) {

echo json_encode(' ' . $cart_total);

} else {
    echo json_encode(' 0');
}



?>    