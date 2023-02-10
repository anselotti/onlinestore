<?php 
session_start();
require('lib/db.php');
require('lib/class.cart.php');

$cart = new Cart(0, $sql, session_id(), 0);
$cartproducts = $cart->getCart();
$cart_total = count($cartproducts);

echo $cart_total;

?>    
