<?php 
session_start();

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require('lib/db.php');
require("lib/class.base.php");
require("lib/class.cart.php");

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$cart = new Cart(0, $sql);
$cart->session_id = session_id();

$cartproducts = $cart->getCart();

$cart_total = 0;

for ($i = 0; $i < count($cartproducts); $i++) {

    $cart_total += $cartproducts[$i]['pcs'];

}

if ($cart_total > 0) {

echo json_encode($cart_total);

} else {
    echo json_encode(0);
}



?>    