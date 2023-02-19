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
        <h1 style="color: #2C4A52;">Checkout</h1>
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
            <!-- TO DO: MAKE THIS UNIQUE BCS INCLUDING MIXES CODE AND DOES NOT WORK CORRECTY -->
            <div id="cartcontent">
                <ul class="cart-listB">
                    <?php

                    // while loop shows all the added products based on session_id.   
                    $sum = 0;
                    $taxes = 0;
                    $cart_result = $sql->query("SELECT * FROM cart WHERE session_id = '$session_id' AND pcs > 0");

                    while ($cart_row = $cart_result->fetch_assoc()) {


                        $products = $sql->query("SELECT * FROM products WHERE id='" . $cart_row['product_id'] . "'");
                        $productsrow = $products->fetch_assoc(); ?>

                        <li><input type="hidden" id="session_idB" value="<?= $session_id ?>">
                            <input type="hidden" id="product_idB<?= $cart_row['id'] ?>" value="<?= $cart_row['product_id'] ?>"><a href="product.php?id=<?= $cart_row['product_id'] ?>"><?= $productsrow['name']; ?>,
                                <input type="hidden" id="product_sizeB<?= $cart_row['id'] ?>" value="<?= $cart_row['product_size'] ?>"><?php echo $cart_row['product_size']; ?>,
                                <b><?= $productsrow['price'] ?> €</b>,
                                <input type="hidden" id="pcsB<?= $cart_row['id'] ?>" value="<?= $cart_row['pcs'] ?>">
                                <p id="pcs_answerB<?= $cart_row['id'] ?>"><?= $cart_row['pcs']; ?> pcs</p>
                            </a>
                            <a type="button" class="plus" id="plusB<?= $cart_row['id'] ?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>
                            <a type="button" class="minus" id="minusB<?= $cart_row['id'] ?>"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                            <input type="hidden" id="idB<?= $cart_row['id'] ?>" value="<?= $cart_row['id'] ?>">
                            <a class="delete-item" type="button" id="delete-itemB<?= $cart_row['id'] ?>" name="delete-btn"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </li>
                        <hr>
                        <?php
                        $sum = ($sum + $productsrow['price']) * $cart_row['pcs'];
                        $taxes = ($taxes + $productsrow['tax']) * $cart_row['pcs'];
                        ?>

                        <script>
                            var deleteItemB = document.getElementById("delete-itemB" + <?= $cart_row['id']; ?>);

                            deleteItemB.onclick = function() {

                                var id = document.getElementById("idB" + <?= $cart_row['id']; ?>).value;

                                fetch('delete_cart_ajax.php', {
                                    method: 'POST', // Send as POST
                                    headers: { // Tells headers to the server
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id: id
                                    }) // Sending JSON-data to server
                                }).then(function(response) {
                                    // when then-promise has been succesful parse to json
                                    return response.json();
                                }).then(function(myJson) {
                                    // when then-promise has been succesful prints json
                                    console.log(myJson);
                                });
                            }

                            var plusB = document.getElementById("plusB" + <?= $cart_row['id']; ?>);

                            plusB.onclick = function() {

                                var pcs_answer = document.getElementById("pcs_answerB" + <?= $cart_row['id']; ?>);
                                var pcs = document.getElementById("pcsB" + <?= $cart_row['id']; ?>).value;
                                var product_id = document.getElementById("product_idB" + <?= $cart_row['id']; ?>).value;
                                var session_id = document.getElementById("session_idB").value;
                                var product_size = document.getElementById("product_sizeB" + <?= $cart_row['id']; ?>).value;

                                fetch('plus_ajax.php', {
                                    method: 'POST', // Send as POST
                                    headers: { // Tells headers to the server
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        product_id: product_id,
                                        session_id: session_id,
                                        product_size: product_size
                                    }) // Sending JSON-data to server
                                }).then(function(response) {
                                    // when then-promise has been succesful parse to json
                                    return response.json();
                                }).then(function(myJson) {
                                    // when then-promise has been succesful prints json
                                    pcs_answer.innerHTML = myJson;
                                });

                            }

                            var minusB = document.getElementById("minusB" + <?= $cart_row['id']; ?>);

                            minusB.onclick = function() {

                                var pcs_answer2 = document.getElementById("pcs_answerB" + <?= $cart_row['id']; ?>);
                                var pcs2 = document.getElementById("pcsB" + <?= $cart_row['id']; ?>).value;
                                var product_id2 = document.getElementById("product_idB" + <?= $cart_row['id']; ?>).value;
                                var session_id2 = document.getElementById("session_idB").value;
                                var product_size2 = document.getElementById("product_sizeB" + <?= $cart_row['id']; ?>).value;

                                fetch('minus_ajax.php', {
                                    method: 'POST', // Send as POST
                                    headers: { // Tells headers to the server
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        product_id2: product_id2,
                                        session_id2: session_id2,
                                        product_size2: product_size2
                                    }) // Sending JSON-data to server
                                }).then(function(response) {
                                    // when then-promise has been succesful parse to json
                                    return response.json();
                                }).then(function(myJson) {
                                    // when then-promise has been succesful prints json
                                    pcs_answer2.innerHTML = myJson;
                                });

                            }

                            // updates cart using ajax and deleting "answer" in the product-card
                            $("#delete-itemB" + <?= $cart_row['id'] ?>).click(function() {
                                $("#cartcontent").load("cart.php");
                                $("#answerB" + <?= $cart_row['product_id']; ?>).empty(); // cleans the answer-div
                                // updates cart-button number
                                $("#cart-total").load("cart_total.php");
                            });

                            // updates cart-button number
                            $("#plusB" + <?= $cart_row['id'] ?>).click(function() {
                                $("#cartcontent").load("cart.php");
                                $("#cart-total").load("cart_total.php");
                            });
                            // updates cart-button number
                            $("#minusB" + <?= $cart_row['id'] ?>).click(function() {
                                $("#cartcontent").load("cart.php");
                                $("#cart-total").load("cart_total.php");
                            });
                        </script>
                    <?php
                    }

                    ?>
                    <b>Total sum: <?= $sum ?> € </b><i>(Taxes: <?= $taxes ?> €)</i>
                </ul>
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
                        <input type="text" class="form-control" id="phone" placeholder="Telephone">
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
                        <button type="submit" class="btn btn-primary" id="submitOrder">Submit order</button>
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
                        <button type="submit" class="btn btn-primary" id="submit_login">Sign in</button>
                    </div>
            </form>

        </div>
    </div>
    <!-- HUOM TEE SISÄÄNKIRJAUTUESSA NIIN, ETTÄ CARTIIN PÄIVITTYY CUSTOMER_ID. 
    AINA KUN ON LOGANNUT SISÄÄN, NIIN CART NÄYTTÄÄ TUOTTEET CUSTOMER_ID:N PERUSTEELLA. SESSION DESTROY, CUN TUOTTEET OSTETTU? -->
</div>
</div>
</div>
<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>