<?php 
session_start();
require('lib/db.php');
// checks if Cart is used before in code.
if(!class_exists("Cart")) {
    require("lib/class.cart.php");
}

$cart = new Cart(0, $sql, session_id(), 0);
$cartproducts = $cart->getCart();
$cart_total = count($cartproducts);

if ($cart_total > 0) {

echo $cart_total;

} else {
    echo '0';
}

?>    
