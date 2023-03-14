<?php
session_start();
require('lib/db.php');
require("lib/class.base.php");
require('lib/class.cart.php');

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$session_id = $json["session_id2"];
$product_id = $json["product_id2"];
$product_size = $json["product_size2"];


$cart = new Cart($product_id, $sql, 'cart');
// Sendig variables to extended object
$cart->session_id = $session_id;
$cart->product_id = $product_id;
$cart->product_size = $product_size;
$cart->customer_id = $_SESSION['logged_id'];

    if ($cart->minus()) {
        // Getting total number in cart of this product and returning it as json.
        $cart_tot = $sql->query("SELECT * FROM cart WHERE session_id = '$session_id' AND product_id = '$product_id' AND product_size = '$product_size'");
        $result = $cart_tot->fetch_assoc();
        
        echo json_encode($result['pcs'] . ' pcs');
    
    } else {
    
        echo json_encode('Problem with adding the product to the cart. Please try again.');
    
    }