<?php 
session_start();
require('lib/db.php');
// checks if Cart-class is used before in code. Avoiding errors :), not sure if this is good way.
if(!class_exists("Base")) {
    require("lib/class.base.php");
}
if(!class_exists("Cart")) {
    require("lib/class.cart.php");
}

$cart = new Cart(session_id(), $sql, 'cart');
$cart->customer_id = $_SESSION['logged_id'];
$cartproducts = $cart->getCart();

$cart_total = 0;

for ($i = 0; $i < count($cartproducts); $i++) {

    $cart_total += $cartproducts[$i]['pcs'];

}

if ($cart_total > 0) {

echo ' ' . $cart_total;

} else {
    echo ' 0';
}



?>    
