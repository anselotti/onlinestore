<?php
require("lib/db.php"); // Connection to database
require("lib/class.cart.php");

$cart = new Cart(0, $sql, $session_id, 0);
$cartproducts = $cart->getCart();
$cart_total = count($cartproducts);



?>

<html>
<!-- HEADER STARTS -->

<head>
    <title><?= $title ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="lib/styles.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<body>
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-dark navbar-custom">
        <div class="container-fluid">
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasResponsive" aria-controls="offcanvasResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="index.php" class="navbar-brand">Ramp Riot Online Store</a>
            <a class="btn" id="cartbutton"><i id="cart-total" class="fa fa-shopping-cart fa-2x" data-bs-toggle="modal" data-bs-target="#cartmodal"></i></a>
        </div>
    </nav>
    <!-- CART-MODAL -->
    <div class="modal fade" id="cartmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cart</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cartcontent">
                        <!-- CART'S CONTENT HERE, USING AJAX -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a type="button" href="checkout.php" class="btn btn-primary">Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- SIDEBAR -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2" id="sidebar">
                <div class="offcanvas-lg offcanvas-start offcanvas-custom" tabindex="-1" id="offcanvasResponsive" aria-labelledby="offcanvasResponsiveLabel" style="padding-top: 20px;">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasResponsiveLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasResponsive" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body offcanvas-custom">
                        <div class="list-group" style="width:100%;">
                            <img src="logo.PNG" style="width: 200px; margin: auto; padding: 20px;">
                            <a href="index.php" class="menu-button" active><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                            <a href="products.php" class="menu-button"><i class="fa fa-th-large" aria-hidden="true"></i> Products</a>
                            <a href="#" class="menu-button"><i class="fa fa-credit-card" aria-hidden="true"></i> Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SIDEBAR ENDS! -->