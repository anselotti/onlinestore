<?php
session_start();
require("lib/db.php");
require('lib/functions.php');
require('lib/class.base.php');
require("lib/class.cart.php");
require("lib/class.customer.php");
require("lib/class.products.php");

$payment = $_POST['payment'];
$shipping = $_POST['shipping'];
$shipping_price = 0;
if($shipping == 'dhl') {
    $shipping_price = 10;
}

if($shipping == 'posti') {
    $shipping_price = 5;
}

$sum = $_POST['sum'] + $shipping_price;
$taxes = $_POST['taxes'];
$notax = $sum - $taxes;
$customerid = $_SESSION['logged_id'];
$sessionid = session_id();

$cart = new Cart($sessionid, $sql, 'cart');
$cart_arr = $cart->getCart();

$products = new Products(0, $sql);
$products_arr = $products->getProducts();


$sql->query("INSERT INTO orders (customer_id, price, price_notax, shipping, payment, status) 
VALUES ('$customerid', '$sum', '$notax', '$shipping', '$payment', 'invoice sent')");

$order_id = $sql->insert_id;

// loop to find given product_id so we can insert right values to the order_items-table
for ($i = 0; $i < count($cart_arr); $i++) {
    $product_id = $cart_arr[$i]['product_id']; // takes the product_id from cart-table
    $product_info = null; // creating variable for the data to insert to the order_items-table

    minusStock($product_id, $cart_arr[$i]['product_size'], $cart_arr[$i]['pcs']);
    
    for ($j = 0; $j < count($products_arr); $j++) {
        if ($products_arr[$j]['id'] === $product_id) { // takes the products data from products-table by id taken from cart-table
            $product_info = $products_arr[$j]; // sets the data to product_info-array
            break;
        }
    }
    
    if ($product_info !== null) { // when the product_info has data it will be inserted to the order_items-table
        $sql->query("INSERT INTO order_items (order_id, product_id, name, price, tax) VALUES ('$order_id', '" . $product_info['id'] . "', '". $product_info['name'] . "', '" . $product_info['price'] . "', '" . $product_info['tax'] . "')");
    }
}

$sql->query("DELETE FROM cart WHERE session_id = '".$sessionid."'");

// !!! TO DO: DECREASE THE STOCK'S VALUE !!!


$sendEmail = "Thank you for the order! This was just a practice project so you will not revieve real invoice and we will not deliever your order.";

if (mail('anssi.kosunen@gmail.com', "Order confirmed", $sendEmail)) {
    header("Location: confirmation.php");
} else {
    echo "Error!";
}




?>