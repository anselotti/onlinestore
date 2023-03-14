<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Confirmation';
require('templates/header.php');
require("lib/class.products.php");


$products = new Products(0, $sql, 0);
$row = $products->getProducts();
$customer_id = $_SESSION['logged_id'];

?>

<!-- CONTENT STARTS -->
<div class="col-lg-10" id="content">
    <div class="row" style="padding: 20px;">
        <h1>Confirmation</h1>
        <h2>Confirmation has sent to your email.</h2>
        <p>This was just a practice project and we will not deliever your order.</p>

        <hr>

        <h2>If you still have some money left, check out the latest products...</h2>

        <?php
        // Query gets all products to an array sorted from newest to oldest
        $result = $sql->query("SELECT * FROM products ORDER BY id DESC");

        // For-loop to get three recently added products, making all the ids unique
        // to use them later in the script 
        for ($i = 0; $i < 3; $i++) {
            $row = $result->fetch_assoc(); ?>

            <div class="col-sm-auto" style="margin: 10px;">
                <div class="card card-custom" style="width: 18rem; height: 100%;">
                    <a href="product.php?id=<?= $row['id'] ?>&category=<?=$row['category']?>"><img style="width: 100%; " src="<?= $row['imgurl']; ?>" class="card-img-top" alt="<?= $row['short_description'] ?>"></a>
                    <div class="card-body">
                        <a href="product.php?id=<?= $row['id']?>&category=<?=$row['category']?>">
                            <h5 class="card-title"><?= $row['name']; ?></h5>
                        </a>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item lgc">
                            <h2><?= $row['price']; ?> €</h2>
                        </li>
                    </ul>
                    <div class="card-body">
                        <form><input id="session_id_<?= $row['id'] ?>" type="hidden" value="<?= $session_id; ?>">
                            <input id="product_id_<?= $row['id'] ?>" type="hidden" value="<?= $row['id']; ?>">
                            <p>
                                <?php

                                if ($stock = getStock($row['id'])) {


                                    echo $stock;
                                }

                                ?>
                            </p>
                            <select id="form-select<?= $row['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                <option selected value="size">Size</option>
                                <!-- Prints sizes what are in the stock -->
                                <?php
                                $result_sizes = $sql->query("SELECT * FROM stock WHERE product_id = '" . $row['id'] . "' AND quantity > 0");
                                while ($row_sizes = $result_sizes->fetch_assoc()) {
                                    var_dump($row_sizes['size']);
                                    echo '<option value="' . $row_sizes['size'] . '">' . $row_sizes['size'] . '</option>';
                                }
                                ?>
                            </select>
                            <p class="answer" id="answer<?= $row['id']; ?>">
                                <!-- Javascript returns "select size" if size is not selected -->
                            </p>
                            <button class="btn btn-dark" id="add<?= $row['id'] ?>" name="add" type="button" class="card-link">To cart</button>
                        </form>

                    </div>
                </div>
            </div>
            <!-- Json for adding products to the cart -->
            <script>
                var submit = document.getElementById("add" + <?= $row['id']; ?>);

                submit.onclick = function() {

                    var answer = document.getElementById("answer" + <?= $row['id']; ?>);
                    var product_id = document.getElementById("product_id_" + <?= $row['id']; ?>).value;
                    var session_id = document.getElementById("session_id_" + <?= $row['id']; ?>).value;
                    var product_size = document.getElementById("form-select" + <?= $row['id']; ?>).value;

                    // returns "Select size" if product_size is not selected
                    if (product_size == "size") {
                        answer.innerHTML = "Please, select size";
                    } else {

                        fetch('tocart_ajax.php', {
                            method: 'POST', // Send as POST
                            headers: { // Tells headers to the server
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: product_id,
                                session_id: session_id,
                                product_size: product_size,
                            }) // Sending JSON-data to server
                        }).then(function(response) {
                            // when then-promise has been succesful parse to json
                            return response.json();
                        }).then(function(myJson) {
                            // when then-promise has been succesful modal opens
                            $("#getCode").html(myJson);
                            jQuery("#addedModal").modal('show');
                            answer.innerHTML = "";
                        });

                    }

                }

                // Updates cart-icon's total number when content clicked. !!! TO DO: not good code, but gotta find better way
                $("#add" + <?= $row['id'] ?>).click(function() {
                    $("#cart-total").load("cart_total.php");
                });
            </script>

        <?php
        }
        ?>

    </div>
</div>
</div>
</div>
<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>