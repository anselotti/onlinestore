<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Products';
require('templates/header.php');
require("lib/class.products.php");

$products = new Products(0, $sql, 0);
$row = $products->getProducts();


?>

<!-- CONTENT STARTS -->
<div class="col-lg-10" id="content">

    <h2 style="padding: 20px;">Select category from below:</h2>
    <div class="container" style="padding: 20px;">
        <button class="btn btn-dark" id="cat-all" name="category" type="button">All</button>
        <button class="btn btn-dark" id="cat-skate" name="category" type="button">Skateboards</button>
        <button class="btn btn-dark" id="cat-cloth" name="category" type="button">Clothing</button>
        <button class="btn btn-dark" id="cat-shoes" name="category" type="button">Shoes</button>
    </div>

    <div id="category-content">
        <div class="row" style="padding: 20px;">
            <!--  -->

            <?php
            // For-loop to get all products and creating unique id:s
            // to every item. 
            for ($i = 0; $i < count($row); $i++) {
            ?>

                <div class="col-sm-auto" style="margin: 10px;">
                    <div class="card card-custom" style="width: 18rem; height: 100%;">
                        <img style="width: 100%; " src="<?= $row[$i]['imgurl']; ?>" class="card-img-top" alt="<?= $row[$i]['short_description'] ?>">
                        <div class="card-body">
                            <a href="product.php?id=<?= $row[$i]['id'] ?>">
                                <h5 class="card-title"><?= $row[$i]['name']; ?></h5>
                            </a>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item lgc">
                                <h2><?= $row[$i]['price']; ?> €</h2>
                            </li>
                        </ul>
                        <div class="card-body">
                            <form><input id="session_id_<?= $row[$i]['id'] ?>" type="hidden" value="<?= $session_id; ?>">
                                <input id="product_id_<?= $row[$i]['id'] ?>" type="hidden" value="<?= $row[$i]['id']; ?>">

                                <!--  -->
                                <?php

                                if ($row['category'] == 'shoes') { ?>

                                    <select id="form-select<?= $row[$i]['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                        <option selected>Size</option>
                                        <option value="39">39</option>
                                        <option value="40">40</option>
                                        <option value="41">41</option>
                                    </select>
                                <?php
                                } else if ($row[$i]['category'] == 'clothing') {
                                ?>
                                    <select id="form-select<?= $row[$i]['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                        <option selected>Size</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                    </select>
                                <?php
                                } else { ?>

                                    <select id="form-select<?= $row[$i]['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                        <option selected>Size</option>
                                        <option value="ONESIZE">ONESIZE</option>
                                    </select>
                                <?php
                                }

                                ?>
                                <button class="btn btn-dark" id="add<?= $row[$i]['id'] ?>" name="add" type="button" class="card-link">Add to basket</button>
                                <p class="answer" id="answer<?= $row[$i]['id']; ?>">
                                    <?php
                                    foreach ($cartproducts as $key => $value) {

                                        if ($row[$i]['id'] === $value['product_id']) {
                                            echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i> In basket';
                                        }
                                    }

                                    ?>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Json for adding products to the cart -->
                <script>
                    var submit = document.getElementById("add" + <?= $row[$i]['id']; ?>);

                    submit.onclick = function() {

                        var answer = document.getElementById("answer" + <?= $row[$i]['id']; ?>);
                        var product_id = document.getElementById("product_id_" + <?= $row[$i]['id']; ?>).value;
                        var session_id = document.getElementById("session_id_" + <?= $row[$i]['id']; ?>).value;
                        var product_size = document.getElementById("form-select" + <?= $row[$i]['id']; ?>).value;

                        fetch('tocart_ajax.php', {
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
                            answer.innerHTML = myJson;
                        });
                    }

                    // Updates cart-icon's total number when content clicked. !!! TO DO: not good code, but gotta find better way
                    $("#add" + <?php echo $row[$i]['id']; ?>).click(function() {
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
</div>
<!-- CONTENT ENDS -->

<!-- FOOTER INCLUDE -->
<?php require('templates/footer.php'); ?>