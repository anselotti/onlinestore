<?php
session_start(); // Session starts
$session_id = session_id();
require("lib/db.php"); // Connection to database

// checks if Cart is used before in code.
if(!class_exists("Cart")) {
    require("lib/class.cart.php");
}

$title = 'cart';

?>

<html>


<head>
</head>

<body>
    <ul class="cart-list">
        <?php

        // while loop shows all the added products based on session_id.   
        $sum = 0;
        $taxes = 0;
        $cart_result = $sql->query("SELECT * FROM cart WHERE session_id = '$session_id' AND pcs > 0");

        while ($cart_row = $cart_result->fetch_assoc()) {


            $products = $sql->query("SELECT * FROM products WHERE id='" . $cart_row['product_id'] . "'");
            $productsrow = $products->fetch_assoc(); ?>

            <li><input type="hidden" id="session_id" value="<?= $session_id ?>">
                <input type="hidden" id="product_id<?= $cart_row['id'] ?>" value="<?= $cart_row['product_id'] ?>"><a href="product.php?id=<?=$cart_row['product_id']?>"><?= $productsrow['name']; ?>, 
                <input type="hidden" id="product_size<?= $cart_row['id'] ?>" value="<?= $cart_row['product_size'] ?>"><?php echo $cart_row['product_size']; ?>, 
                <b><?= $productsrow['price'] ?> €</b>,
                <input type="hidden" id="pcs<?= $cart_row['id'] ?>" value="<?= $cart_row['pcs'] ?>">
                <p id="pcs_answer<?= $cart_row['id'] ?>"><?= $cart_row['pcs']; ?> pcs</p></a>
                <a type="button" class="plus" id="plus<?= $cart_row['id'] ?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a> 
                <a type="button" class="minus" id="minus<?= $cart_row['id'] ?>"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                <input type="hidden" id="id<?= $cart_row['id'] ?>" value="<?= $cart_row['id'] ?>">
                <a class="delete-item" type="button" id="delete-item<?= $cart_row['id'] ?>" name="delete-btn"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </li>
            <hr>
            <?php
            $sum = ($sum + $productsrow['price']) * $cart_row['pcs'];
            $taxes = ($taxes + $productsrow['tax']) * $cart_row['pcs'];
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

                var plus = document.getElementById("plus" + <?= $cart_row['id']; ?>);

                plus.onclick = function() {

                    var pcs_answer = document.getElementById("pcs_answer" + <?= $cart_row['id']; ?>);
                    var pcs = document.getElementById("pcs" + <?= $cart_row['id']; ?>).value;
                    var product_id = document.getElementById("product_id" + <?= $cart_row['id']; ?>).value;
                    var session_id = document.getElementById("session_id").value;
                    var product_size = document.getElementById("product_size" + <?= $cart_row['id']; ?>).value;

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

                var minus = document.getElementById("minus" + <?= $cart_row['id']; ?>);

                minus.onclick = function() {

                    var pcs_answer2 = document.getElementById("pcs_answer" + <?= $cart_row['id']; ?>);
                    var pcs2 = document.getElementById("pcs" + <?= $cart_row['id']; ?>).value;
                    var product_id2 = document.getElementById("product_id" + <?= $cart_row['id']; ?>).value;
                    var session_id2 = document.getElementById("session_id").value;
                    var product_size2 = document.getElementById("product_size" + <?= $cart_row['id']; ?>).value;

                    console.log(pcs2);
                    console.log(product_id2);
                    console.log(session_id2);
                    console.log(product_size2);

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
                        console.log(pcs_answer2)                                                   
                    });

                    }
                
                // updates cart using ajax and deleting "answer" in the product-card
                $("#delete-item" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartcontent").load("cart.php");
                    $("#answer" + <?= $cart_row['product_id']; ?>).empty(); // cleans the answer-div
                    // updates cart-button number
                    $("#cart-total").load("cart_total.php");
                });

                // updates cart-button number
                $("#plus" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartcontent").load("cart.php");
                });
                // updates cart-button number
                $("#minus" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartcontent").load("cart.php");
                });

            </script>
        <?php
        }

        ?>
        <b>Total sum: <?= $sum ?> € </b><i>(Taxes: <?= $taxes ?> €)</i>
    </ul>
</body>

</html>