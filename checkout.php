<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Checkout';
require('templates/header.php');
require("lib/class.products.php");


$products = new Products(0, $sql, 0);
$row = $products->getProducts();


?>

<!-- CONTENT STARTS -->
<div class="col-lg-10" id="content">
    <div class="row" style="padding: 20px;">
        <h1>Checkout</h1>
        <div class="progress-stacked" style="background-color: #f4ebcf!important; padding: 0px; margin: auto;">
            <div class="progress" role="progressbar" aria-label="Segment one" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-success" style="background-color: #2C4A52!important;">Your data</div>
            </div>
            <div class="progress" role="progressbar" aria-label="Segment two" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-secondary" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Payment</div>
            </div>
            <div class="progress" role="progressbar" aria-label="Segment three" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-secondary" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Confirmation</div>
            </div>
        </div>
        <div class="col-sm-6">
            <h2>Your products</h2>
            <div id="cart-list">
                <!-- TO DO: MAKE THIS UNIQUE BCS INCLUDING MIXES CODE AND DOES NOT WORK CORRECTY -->
                <?php
                require('cart.php');
                ?>
            </div>
        </div>
        <div class="col-sm-6" style="max-width: 560px">
        <h2>Your data</h2>
            <form class="row g-3">
            <div class="row g-3">
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="firstname" placeholder="Eric" aria-label="Eric">
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="lastname" placeholder="Example" aria-label="Example">
                </div>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="address" placeholder="Address 1 B 2" aria-label="Address 1 B 2">
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="zip" placeholder="00100" aria-label="00100">
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="city" placeholder="Helsinki">
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="country" placeholder="Finland">
                </div>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="country" placeholder="Telephone">
                </div>
                <div class="col-sm-12">
                    <input type="email" class="form-control" id="email" placeholder="Email" aria-label="Email">
                </div>
                <div class="col-sm-12">
                    <input type="password" class="form-control" id="password" placeholder="Password" aria-label="Password">
                </div>
                <div class="col-sm-12">
                    <input type="password2" class="form-control" id="password2" placeholder="Password again" aria-label="Password again">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" id="submitOrder" >Submit order</button>
                </div>
            </form>

            <form class="row g-3">
            <b>If you already have an account please sign in:</b>
            <div class="row g-3">
                <div class="col-sm-6">
                    <input type="email" class="form-control" id="email_login" placeholder="Email" aria-label="Number">
                </div>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="password_login" placeholder="Password" aria-label="Password">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" id="submit_login" >Sign in</button>
                </div>
            </form>

        </div>
    </div>

</div>
</div>
</div>
<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>