<?php
session_start(); // Session starts
$session_id = session_id();
require("lib/db.php"); // Connection to database
require("lib/class.cart.php");
$title = 'Ramp Riot Online Store | Category: skateboarding';
$cart = new Cart(0, $sql, $session_id, 0);
$cartproducts = $cart->getCart();
?>

<html>


<head>
</head>

<body>
    <div class="row" style="padding: 20px;">
        <!--  -->
        <?php
        // Query gets all products to an array sorted from newest to oldest
        $result = $sql->query("SELECT * FROM products WHERE category = 'skateboarding'");

        // For-loop to get three recently added products, making all the ids unique
        // to use them later in the script 
        while ($row = $result->fetch_assoc()) { ?>

            <div class="col-sm-auto" style="margin: 10px;">
                <div class="card card-custom" style="width: 18rem; height: 100%;">
                    <img style="width: 100%; " src="<?= $row['imgurl']; ?>" class="card-img-top" alt="<?= $row['short_description'] ?>">
                    <div class="card-body">
                        <a href="product.php?id=<?=$row['id']?>">
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

                            <?php

                            if ($row['category'] == 'shoes') { ?>

                                <select id="form-select<?= $row['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                    <option selected>Size</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                </select>
                            <?php
                            } else if ($row['category'] == 'clothing') {
                            ?>
                                <select id="form-select<?= $row['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                    <option selected>Size</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                            <?php
                            } else { ?>

                                <select id="form-select<?= $row['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                    <option selected>Size</option>
                                    <option value="ONESIZE">ONESIZE</option>
                                </select>
                            <?php
                            }

                            ?>
                            <button class="btn btn-primary" id="add<?= $row['id'] ?>" name="add" type="button" class="card-link">Add to basket</button>
                            <p class="answer" id="answer<?= $row['id']; ?>">
                                <?php
                                foreach ($cartproducts as $key => $value) {

                                    if ($row['id'] === $value['product_id']) {
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
                var submit = document.getElementById("add" + <?= $row['id']; ?>);

                submit.onclick = function() {

                    var answer = document.getElementById("answer" + <?= $row['id']; ?>);
                    var product_id = document.getElementById("product_id_" + <?= $row['id']; ?>).value;
                    var session_id = document.getElementById("session_id_" + <?= $row['id']; ?>).value;
                    var product_size = document.getElementById("form-select" + <?= $row['id']; ?>).value;

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
            </script>

        <?php
        }
        ?>

    </div>

</body>

</html>