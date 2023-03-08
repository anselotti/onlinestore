<?php
require("lib/db.php"); // Connection to database
require('lib/class.base.php');
require("lib/class.cart.php");
require("lib/class.customer.php");
require("lib/functions.php");

$cart = new Cart(0, $sql);
$cart->session_id = session_id();
$cartproducts = $cart->getCart();

$customer = new Customer($_SESSION['logged_id'], $sql);
$loggedCustomer = $customer->getCustomer();

if (
    $_SESSION['logged_id'] < 1 &&
    !strstr($_SERVER["SCRIPT_FILENAME"], 'products.php') &&
    !strstr($_SERVER["SCRIPT_FILENAME"], 'product.php') &&
    !strstr($_SERVER["SCRIPT_FILENAME"], 'checkout.php') &&
    !strstr($_SERVER["SCRIPT_FILENAME"], 'index.php')
) {
    header('Location: index.php');
}

// getting number of products in cart
$cart = new Cart(session_id(), $sql, 'cart');
$cart->customer_id = $_SESSION['logged_id'];
$how_many_items = $cart->getCart();

$cart_total = 0;

for ($i = 0; $i < count($how_many_items); $i++) {

    $cart_total += $how_many_items[$i]['pcs'];

}

if ($cart_total > 0) {

    $cartNumber = $cart_total;

} else {
    $cartNumber = 0;
}

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
</head>
<body>
    <div id="warning" style="background-color:#f4ebdb; color: black; text-align:center;">This is a practice project, so you can place an order, but you do not need to pay the bill. :)</div>
    
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-dark navbar-custom">
        <div class="container-fluid">
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasResponsive" aria-controls="offcanvasResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="index.php" class="navbar-brand">Ramp Riot</a>
            <a class="btn" id="cartbutton"><i id="cart-total" class="fa fa-shopping-cart" data-bs-toggle="modal" data-bs-target="#cartmodal"><?= ' ' . $cartNumber ?></i></a>
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
                        <!-- CART'S CONTENT IS HERE, USING AJAX -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a type="button" href="checkout.php" class="btn btn-dark">Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- PRODUCT ADDED TO CART- MODAL -->
    <div class="modal fade" id="addedModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4><b>Well done!</b></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>The product added to the cart.</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-continue" data-bs-dismiss="modal">Continue shopping</button>
        <a href="checkout.php" id="btn-modal" class="btn-modal" type="button">Checkout</a>
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
                            <a href="index.php"><img class="center-image" src="uploads/logo.PNG"></a> 
                            <a href="index.php" class="btn btn-dark" style="text-align: left; margin-bottom: 5px;">
                                    <i class="fa fa-home" aria-hidden="true"></i> Home
                            </a>
                            <a class="btn btn-dark dropdown-toggle" style="text-align: left; margin-bottom: 5px;" data-bs-toggle="collapse" href="#products-button" role="button" aria-expanded="true" aria-controls="collapseExample">
                                <i class="fa fa-th-large" aria-hidden="true"></i> Products
                            </a>
                                <!-- Collapse is open when user is on products.php or product.php -->
                                <div class="collapse<?php if (strstr($_SERVER["SCRIPT_FILENAME"], 'products.php') || strstr($_SERVER["SCRIPT_FILENAME"], 'product.php')) {echo '.show';}?>" id="products-button">
                                        <ul class="nav-list">
                                            <?php
                                                // when all categories are selected, nav-link style is "active" 
                                                if (strstr($_SERVER["SCRIPT_FILENAME"], 'products.php') && $_GET['category'] == '') {
                                                    echo '<li><a href="products.php" style="background-color: #f3efe9; color: #2C4A52;">all products</a></li>';
                                                } else {
                                                    echo '<li><a href="products.php">all products</a></li>';
                                                }
                                            ?>
                                            <?php
                                            $menu_categories = allCategories();
                                            // for-loop through the array to print category-buttons
                                            for ($i = 0; $i < count($menu_categories); $i++) {
                                                // when certain category is selected, nav-link style is "active" 
                                                if ($_GET['category'] == $menu_categories[$i]['name']) {
                                                    echo '<li><a href="products.php?category=' . $menu_categories[$i]['name'] . '" style="background-color: #f3efe9; color: #2C4A52;">' . $menu_categories[$i]['name'] . '</a></li>';
                                                } else {
                                                echo '<li><a href="products.php?category=' . $menu_categories[$i]['name'] . '">' . $menu_categories[$i]['name'] . '</a></li>';
                                                }
                                            }

                                            ?>
                                        </ul>
                                </div>
                                <a href="checkout.php" class="btn btn-dark" style="text-align: left; margin-bottom: 5px;">
                                    <i class="fa fa-credit-card" aria-hidden="true"></i> Checkout
                                </a>
                            <hr>
                            <?php 
                            if ($_SESSION["logged_id"] == false) { ?>
                                <p id="loginError"></p>
                                <form method="POST" action="do_login.php">
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control" id="emailHeader" placeholder="email">
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control" id="passwordHeader" placeholder="password">
                                    </div>
                                    <button class="btn btn-dark" id="loginBtnHeader" type="button" class="btn btn btn-sm">Log in</button>
                                </form>
                                <?php 
                            } else {
                                echo 
                                '<div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user" aria-hidden="true"></i> ' . $loggedCustomer[0]['firstname'] . ' ' . $loggedCustomer[0]['lastname'] . '
                                </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="padding: 20px;">
                                        <li><i class="fa fa-info" aria-hidden="true"></i> <a href="customer.php?id='.$loggedCustomer[0]['id'].'">Personal data</a></li>
                                        <li><i class="fa fa-sign-out" aria-hidden="true"></i> <a href="logout.php">Log out</a></li>
                                    </ul>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
   
                            var loginBtn = document.getElementById("loginBtnHeader");

                            loginBtn.onclick = function() {

                                var loginError = document.getElementById("loginError");
                                var email = document.getElementById("emailHeader").value;
                                var password = document.getElementById("passwordHeader").value;
                                             
                                fetch('do_login.php', {
                                    method: 'POST', // Send as POST
                                    headers: { // Tells headers to the server
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        email: email,
                                        password: password
                                    }) // Sending JSON-data to server
                                }).then(function(response) {
                                    // when then-promise has been succesful parse to json
                                    return response.json();
                                }).then(function(myJson) {
                                    // when then-promise has been succesful modal opens 
                                    if (myJson == '') {
                                        location.reload(true);
                                    } else {
                                        loginError.innerHTML = myJson;
                                    }
                                    
                                                                       
                                });

                            }


                        </script>
        

            <!-- SIDEBAR ENDS! -->