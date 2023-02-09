<?php
session_start();
require('lib/db.php');
require('lib/class.cart.php');

$cart = new Cart($_POST['product_id'], $sql, $_POST['session_id'], $_POST['product_size']);


if ($cart->add()) {
    
    header('Location: index.php?cartaccepted=1');

} else {

    header('Location: index.php?cartaccepted=2');

}


?>
