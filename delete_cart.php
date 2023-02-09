<?php
session_start();
require('lib/db.php');
require('lib/class.cart.php');


$cart = new Cart($_POST['id'], $sql, 0);
    
    if ($cart->delete()) {

        header("Location: index.php?deleted=3");

    } else {

        header("Location: index.php?deleted=4");

    }
?>