<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Checkout';
require('templates/header.php');
require("lib/class.products.php");


$products = new Products(0, $sql, 0);
$row = $products->getProducts();
$customer_id = $_SESSION['logged_id'];

?>
<script src="https://www.paypal.com/sdk/js?client-id=ARpXCFfn-bI4yEf_HRTfd5G6TEtNQqT9pvZVzYV3GQlt2RY3dgHdH16ADjK3td870S1DL65FLbMyHOT8&enable-funding=venmo&currency=EUR" data-sdk-integration-source="button-factory"></script>
<script src="lib/script.js" defer></script>

<!-- CONTENT STARTS -->
<div class="col-lg-10" id="content">
    <div class="row" style="padding: 20px;">
        <h1 style="color: #2C4A52;">Payment</h1>
        <div class="progress-stacked" style="background-color: #f4ebcf!important; padding: 0px; margin: auto;">
            <div class="progress" role="progressbar" aria-label="Segment one" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-success" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Your data</div>
            </div>
            <div class="progress" role="progressbar" aria-label="Segment two" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-secondary" style="background-color: #2C4A52!important;">Payment</div>
            </div>
            <div class="progress" role="progressbar" aria-label="Segment three" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                <div class="progress-bar bg-secondary" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Confirmation</div>
            </div>
        </div>
        <div class="col-sm-12" style="padding: 20px;">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Size</th>
                        <th scope="col">Price</th>
                        <th scope="col">Pcs</th>
                        <th scope="col">Img</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    $orderedItems = $sql->query("SELECT * FROM cart WHERE customer_id = '" . $customer_id . "'");

                    $sum = 0;
                    $taxes = 0;

                    while ($rowOrderedItems = $orderedItems->fetch_assoc()) {
                        $orderedProducts = $sql->query("SELECT * FROM products WHERE id='" . $rowOrderedItems['product_id'] . "'");
                        $rowOrderedProducts = $orderedProducts->fetch_assoc();

                        $sum = ($sum + $rowOrderedProducts['price']) * $rowOrderedItems['pcs'];
                        $taxes = ($taxes + $rowOrderedProducts['tax']) * $rowOrderedItems['pcs'];

                    ?>
                        <tr>
                            <th scope="row"><?= $rowOrderedProducts['name'] ?></th>
                            <th><?= $rowOrderedItems['product_size'] ?></th>
                            <th><?= $rowOrderedProducts['price'] ?> €</th>
                            <th><?= $rowOrderedItems['pcs'] ?> pcs</th>
                            <th><img src="<?= $rowOrderedProducts['imgurl'] ?>" height="100px"></th>
                        </tr>

                    <?php
                    }
                    ?>
                    <?php
                    // setting price to shipping
                    if ($_POST['shipping'] == 'dhl') {
                        $shipping_price = 10;
                        $shipping_taxes = 1.94;
                    }
                    if ($_POST['shipping'] == 'posti') {
                        $shipping_price = 5;
                        $shipping_taxes = 0.97;
                    }

                    $sum = $sum + $shipping_price;
                    $taxes = $taxes + $shipping_taxes;
                    ?>
                </tbody>

            </table>
            Shipping: <?= $_POST['shipping'] ?>, <?= $shipping_price ?> € <br>
            <b>Total sum: <?= $sum ?> € </b>(Taxes: <?= $taxes ?> €)
            <?php
                if($_POST['payment'] == 'paypal') { ?>

            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container" style="padding-top: 5%;"></div>
                        <form action="do_order.php" method="POST">
                            <input name="payment" value="<?= $_POST['payment']?>" hidden>
                            <input name="shipping" value="<?= $_POST['shipping']?>" hidden>
                            <input name="sum" value="<?= $sum?>" hidden>
                            <input name="taxes" value="<?= $taxes?>" hidden>
                            <button class="btn btn-dark" type="submit" id="accept" hidden>Accept order</button>                
                        </form>
                </div>
                </div>           
                <?php } else {
            ?>
            <form action="do_order.php" method="POST">
                <input name="payment" value="<?= $_POST['payment']?>" hidden>
                <input name="shipping" value="<?= $_POST['shipping']?>" hidden>
                <input name="sum" value="<?= $sum?>" hidden>
                <input name="taxes" value="<?= $taxes?>" hidden>
                <button class="btn btn-dark" type="submit">Accept order</button>                
            </form>
            <?php
            }
            ?>

        </div>
    </div>
</div>
</div>
</div>
<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>