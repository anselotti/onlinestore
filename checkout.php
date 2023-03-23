<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Checkout';
require('templates/header.php');
require("lib/class.products.php");


$products = new Products(0, $sql, 0);
$row = $products->getProducts();

if (isset($_SESSION['logged_id'])) {

$customer_id = $_SESSION['logged_id'];

}



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
            <div id="cartcontent">
                <ul class="cart-listB">
                    <?php


                    $sum = 0;
                    $taxes = 0;

                    // takes result to $cart_result. 
                    // the result is based on session_id.

                        $cart_result = $sql->query("
                        SELECT cart.id, cart.product_id, cart.product_size, cart.pcs, products.name, products.price, products.tax
                        FROM cart
                        JOIN products ON cart.product_id = products.id
                        WHERE cart.session_id = '$session_id' AND cart.pcs > 0
                    ");

                    // while loop shows all the added products based on session_id and customer_id.   
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

                            // reload page on click bcs in this moment of order everything has to be 100 % updated 
                            $("#delete-itemB" + <?= $cart_row['id'] ?>).click(function() {
                                location.reload(true);
                            });


                            $("#plusB" + <?= $cart_row['id'] ?>).click(function() {
                                location.reload(true);
                            });

                            $("#minusB" + <?= $cart_row['id'] ?>).click(function() {
                                location.reload(true);
                            });
                        </script>
                    <?php
                    }

                    ?>
                    <b>Total sum: <?= $sum ?> € </b>(Taxes: <?= $taxes ?> €)

                </ul>

            </div>
        </div>

        <?php
        if (isset($_SESSION['logged_id']) == false) { ?>
            <div class="col-sm-6" style="max-width: 560px">
                <h2>Your data</h2>

                <b>If you already have an account please sign in:</b>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <input name="email" type="email" class="form-control" id="emailCheckout" placeholder="Email" aria-label="Number">
                    </div>
                    <div class="col-sm-6">
                        <input name="password" type="password" class="form-control" id="passwordCheckout" placeholder="Password" aria-label="Password">
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-dark" id="loginCheckout">Sign in</button>
                        <p id="loginErrorCheckout"></p>
                    </div>
                    </form>
                    <script>
                        var loginBtn = document.getElementById("loginCheckout");

                        loginBtn.onclick = function() {

                            var loginError = document.getElementById("loginErrorCheckout");
                            var email = document.getElementById("emailCheckout").value;
                            var password = document.getElementById("passwordCheckout").value;

                            if (email == "" || password == "") { // checks empty fields

                                loginError.innerHTML = '<p style="padding: 10px; border-radius: 10px; color: white; background-color: rgb(122, 47, 47);">You cannot leave empty fields.</p>';

                            } else {

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

                        }
                    </script>
                    <form action="do_register.php" method="POST" class="row g-3">
                        <b>If you do not have an account, please fill the form:</b>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="firstname" placeholder="Eric">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="lastname" placeholder="Example">
                            </div>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="address" placeholder="Address 1 B 2">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="zip" placeholder="00100">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="city" placeholder="Helsinki">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="country" placeholder="Finland">
                            </div>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="phone" placeholder="Telephone">
                            </div>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" name="password2" placeholder="Password again">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-dark" name="submit">Register</button>
                            </div>
                    </form>

                    <?php

                    if (isset($_SESSION['errors'])) {

                        if (is_array($_SESSION['errors']) && count($_SESSION['errors']) > 0) { // both is_countable and isset works in here

                            foreach ($_SESSION['errors'] as $e) {
                                echo $e . '<br>';
                            }
    
                            unset($_SESSION['errors']);
                        }

                    }
                    

                    ?>



                </div>
            <?php
        } else {
            $customer = new Customer($_SESSION['logged_id'], $sql);

            $customer_data = $customer->getCustomer();

            ?>

                <div class="col-sm-6" style="max-width: 560px">
                    <form action="do_modify.php" method="POST" class="row g-3">
                        <div class="row g-3">
                            <h2>Check your data</h2>
                            <?php

                            if (isset($_GET['error'])) {

                                if ($_GET['error'] == 3) {
                                    echo '<p style="padding: 20px; border-radius: 10px; color: white; background-color: #537072;">Your data has updated!</p>';
                                }
    
                                if ($_GET['error'] == 5) {
                                    echo '<p style="padding: 20px; border-radius: 10px; color: white; background-color: rgb(122, 47, 47);">Your cart is empty. Please select products to continue payment.</p>';
                                }

                                if ($_GET['error'] == 6) {
                                    echo '<p style="padding: 20px; border-radius: 10px; color: white; background-color: rgb(122, 47, 47);">You have to accept terms and conditions to continue payment.</p>';
                                }


                            }
                            

                            ?>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="id" value="<?= $customer_data[0]['id'] ?>" hidden>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="firstname" value="<?= $customer_data[0]['firstname'] ?>">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="lastname" value="<?= $customer_data[0]['lastname'] ?>">
                            </div>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="address" value="<?= $customer_data[0]['address'] ?>">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="zip" value="<?= $customer_data[0]['zip'] ?>">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="city" value="<?= $customer_data[0]['city'] ?>">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="country" value="<?= $customer_data[0]['country'] ?>">
                            </div>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="phone" value="<?= $customer_data[0]['phone'] ?>">
                            </div>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" name="email" value="<?= $customer_data[0]['email'] ?>">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-dark" name="submit">Update data</button>
                            </div>

                            <h2>Payment method</h2>
                            <input type="radio" class="btn-check" name="payment" id="option1" autocomplete="off" value="invoice" checked>
                            <label class="btn btn-outline-secondary" for="option1">Invoice</label>

                            <input type="radio" class="btn-check" name="payment" id="option2" autocomplete="off" value="paypal">
                            <label class="btn btn-outline-secondary" for="option2">Credit card or PayPal</label>

                            <h2>Shipping method</h2>
                            <input type="radio" class="btn-check" name="shipping" id="option3" autocomplete="off" value="posti" checked>
                            <label class="btn btn-outline-secondary" for="option3">Posti 5 € (only in Finland)</label>

                            <input type="radio" class="btn-check" name="shipping" id="option4" autocomplete="off" value="dhl">
                            <label class="btn btn-outline-secondary" for="option4">DHL 10 €</label>

                            
                            <label class="form-check-label" for="flexCheckDefault">
                            <input class="form-check-input" type="checkbox" name="terms" value="" id="flexCheckDefault">
                                I accept <a type="button" class="modalbtn" data-bs-toggle="modal" data-bs-target="#termsAndConditions">terms and conditions</a>.                                
                            </label>
                            
                            <div class="col-12">
                                <button formaction="payment.php" type="submit" class="btn btn-dark" name="submit">Continue to payment</button>
                            </div>



                        </div>
                    </form>

                    

                    <?php



                    if (is_array(isset($_SESSION['errors'])) && count($_SESSION['errors']) > 0) { // both is_countable and isset works in here

                        foreach ($_SESSION['errors'] as $e) {
                            echo $e . '<br>';
                        }

                        unset($_SESSION['errors']);
                    }

                    ?>



                </div>

            <?php

        }
            ?>
            </div>

    </div>
</div>
</div>
</div>
</div>

<script>
    if($("#checkSurfaceEnvironment-1").prop('checked') == false){
    
}
</script>

<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>