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
        <div class="progress-stacked">
  <div class="progress" role="progressbar" aria-label="Segment one" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
    <div class="progress-bar bg-success" style="background-color: #2C4A52!important;">Your data</div>
  </div>
  <div class="progress" role="progressbar" aria-label="Segment two" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
    <div class="progress-bar bg-secondary" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Payment</div>
  </div>
  <div class="progress" role="progressbar" aria-label="Segment three" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 34%">
    <div class="progress-bar bg-secondary" style="background-color: #f4ebcf!important; color: #2C4A52!important;">Confirmation</div>
  </div>
</div>
        <div class="col-6">
            <h2>Your products</h2>
            <div id="cartlist">
            <ul class="cart-list">
            
        <?php

        // while loop shows all the added products based on session_id.   
        $sum = 0;
        $taxes = 0;
        $cart_result = $sql->query("SELECT * FROM cart WHERE session_id = '$session_id'");

        while ($cart_row = $cart_result->fetch_assoc()) {        

            $products = $sql->query("SELECT * FROM products WHERE id='" . $cart_row['product_id'] . "'");
            $productsrow = $products->fetch_assoc(); ?>
            <li><?= $productsrow['name']; ?>, <?php echo $cart_row['product_size']; ?> , <b><?= $productsrow['price'] ?> €</b>
                <input type="hidden" id="id<?= $cart_row['id'] ?>" value="<?= $cart_row['id'] ?>">
                <button class="fa fa-trash-o fa-trash-custom" type="button" id="delete-item<?= $cart_row['id'] ?>" name="delete-btn"></button>
            </li>
            <hr>
            <?php
            $sum = $sum + $productsrow['price'];
            $taxes = $taxes + $productsrow['tax'];
            ?>

            <script>


                var deleteItem = document.getElementById("delete-item" + <?= $cart_row['id']; ?>);

                deleteItem.onclick = function() {

                    var id = document.getElementById("id" + <?= $cart_row['id']; ?>).value;

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
                
                // updates cart using ajax and deleting "answer" in the product-card
                $("#delete-item" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartlist").load("cart.php");
                });

            </script>
        <?php
        }

        ?>
        <b>Total sum: <?= $sum ?> € </b><i>(Taxes: <?= $taxes ?> €)</i>
    </ul>
    </div>            
        </div>
        <div class="col-6">
            <form class="row g-3">
                <h2>Your data</h2>
                <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail4">
                </div>
                <div class="col-md-6">
                    <label for="inputPassword4" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword4">
                </div>
                <div class="col-12">
                    <label for="inputAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                </div>
                <div class="col-12">
                    <label for="inputAddress2" class="form-label">Address 2</label>
                    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                </div>
                <div class="col-md-6">
                    <label for="inputCity" class="form-label">City</label>
                    <input type="text" class="form-control" id="inputCity">
                </div>
                <div class="col-md-4">
                    <label for="inputState" class="form-label">State</label>
                    <select id="inputState" class="form-select">
                        <option selected>Choose...</option>
                        <option>...</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="inputZip" class="form-label">Zip</label>
                    <input type="text" class="form-control" id="inputZip">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck">
                        <label class="form-check-label" for="gridCheck">
                            Check me out
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Sign in</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>