<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Checkout';
require('templates/header.php');
require("lib/class.products.php");


$products = new Products(0, $sql, 0);
$row = $products->getProducts();

$cart = new Cart($_SESSION['logged_id'], $sql);
$cart->session_id = $session_id;

$cart->addCustomerToCart();





?>

<!-- CONTENT STARTS -->
<div class="col-lg-10" id="content">
    <div class="row" style="padding: 20px;">
        <h1 style="color: #2C4A52;">Payment</h1>
        <div class="progress-stacked" style="background-color: #f4ebcf!important; padding: 0px; margin: auto;">
            <div class="progress" role="progressbar" aria-label="Segment one" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-success" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Your data</div>
            </div>
            <div class="progress" role="progressbar" aria-label="Segment two" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-secondary" style="background-color: #2C4A52!important;">Payment and shipping</div>
            </div>
            <div class="progress" role="progressbar" aria-label="Segment three" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-secondary" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Confirmation</div>
            </div>
        </div>
        <div class="col-sm-12">
            <ul class="cart-list">
            <?php

            $orderedItems = $sql->query("SELECT * FROM cart WHERE session_id = '".$session_id."'");
            
            $sum = 0;
            $taxes = 0;

            while ($rowOrderedItems = $orderedItems->fetch_assoc()) { 
                $orderedProducts = $sql->query("SELECT * FROM products WHERE id='" . $rowOrderedItems['product_id'] . "'");
                $rowOrderedProducts = $orderedProducts->fetch_assoc();

            $sum = ($sum + $rowOrderedProducts['price']) * $rowOrderedItems['pcs'];
            $taxes = ($taxes + $rowOrderedProducts['tax']) * $rowOrderedItems['pcs'];

                ?>
            <li>
                <?= $rowOrderedProducts['name'] ?>, <?= $rowOrderedItems['product_size'] ?>, <?= $rowOrderedProducts['price'] ?> €, <?= $rowOrderedItems['pcs'] ?> pcs 
            </li>
            <?php
            }
            ?>
            <b>Total sum: <?= $sum ?> € </b>(Taxes: <?= $taxes ?> €)
            </ul>

            <form action="do_order.php" method="POST">
                <input type="text" name="sum" value="<?=$sum?>" hidden>
                <input type="text" name="taxes" value="<?=$taxes?>" hidden>
                <h2>Payment method:</h2>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment" id="flexRadioDisabled" value="invoice" checked>
                    <label class="form-check-label" for="flexRadioDisabled">
                        Invoice (to email)
                    </label>
                </div>
                <h2>Shipping method:</h2>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="shipping" id="flexRadioDisabled" value="posti" checked>
                    <label class="form-check-label" for="flexRadioDisabled">
                        Posti 5 € (Only in Finland)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="shipping" id="flexRadioCheckedDisabled" value="dhl">
                    <label class="form-check-label" for="flexRadioCheckedDisabled">
                        DHL 10 €
                    </label>
                </div>
                <button class="btn" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>