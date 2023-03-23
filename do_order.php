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
$sum = $_POST['sum'];
$taxes = $_POST['taxes'];
$notax = $sum - $taxes;
$customerid = $_SESSION['logged_id'];
$sessionid = session_id();

$cart = new Cart(0, $sql, 'cart');
$cart->session_id = $sessionid;
$cart->customer_id = $customerid;
$cart_arr = $cart->getCart();

$products = new Products(0, $sql);
$products_arr = $products->getProducts();

if ($payment == 'paypal') {
    $sql->query("INSERT INTO orders (customer_id, price, price_notax, shipping, payment, status) 
VALUES ('$customerid', '$sum', '$notax', '$shipping', '$payment', 'paid')");
}  else {

$sql->query("INSERT INTO orders (customer_id, price, price_notax, shipping, payment, status) 
VALUES ('$customerid', '$sum', '$notax', '$shipping', '$payment', 'invoice sent')");

}

$order_id = $sql->insert_id;

// loop through rows in cart and foreach to find prices of products
for ($i = 0; $i < count($cart_arr); $i++) {

    $product_id = $cart_arr[$i]['product_id'];

    minusStock($cart_arr[$i]['product_id'], $cart_arr[$i]['product_size'], $cart_arr[$i]['pcs']);

    foreach ($products_arr as $product) {
        if ($product['id'] == $product_id) {
            $price = $product['price'];
            $tax = $product['tax'];
            $name = $product['name'];
            break;
        }
    } 

    // settings all the founded data to order_items.
    $sql->query("INSERT INTO order_items (order_id, product_id, name, product_size, pcs, price, tax, createdate) 
    VALUES ('" . $order_id . "', '" . $product_id . "', '" . $name . "', '" . $cart_arr[$i]['product_size'] . "', '" . $cart_arr[$i]['pcs'] . "', '" . $price . "', '" . $tax . "', CURRENT_TIMESTAMP)");

}
// empties cart
$sql->query("DELETE FROM cart WHERE session_id = '".$sessionid."'");

// variables for email to customer 
$email = $_SESSION['customer'];
$subject = "Ramp Riot Order Confirmed";
$dateOfOrder = date('Y-m-d H:i:s');
$headers = "from: rampriot@rampriot.fi";
$sendEmail = "Thank you for the order!\r\n\r\nThis was just a practice project so you will not revieve real invoice and we will not deliever your order. \r\n\r\n" . "Date of order: " . $dateOfOrder;

// mail to customer //
if (mail($email, $subject, $sendEmail, $headers)) {
    // mail to admin //
    $subjectToAdmin = "NEW ORDER";
    $orderInfo = "User " . $email . " made an order\r\n\r\nOrder id: " . $order_id;
    mail("anssi.kosunen@gmail.com", $subjectToAdmin, $orderInfo, "from: order@rampriot.fi");

    header("Location: confirmation.php");
} else {
    echo 'Error sending email';
}







?>