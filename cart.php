<?php
session_start(); // Session starts

require("lib/db.php"); // Connection to database
$session_id = session_id();
$customer_id = $_SESSION['logged_id'];

// checks if Cart is used in code before.
if (!class_exists("Base")) {
    require("lib/class.base.php");
}
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

        // takes all the products from cart based on session_id when customer is not logged in
        if ($_SESSION['logged_id'] == false) {

            $cart_products = $sql->query("
            SELECT cart.id, cart.product_id, cart.product_size, cart.pcs, products.name, products.price, products.tax
            FROM cart
            JOIN products ON cart.product_id = products.id
            WHERE cart.session_id = '$session_id' AND cart.pcs > 0
        ");

        } 
        
        // takes all the products from cart based on customer_id when customer is logged in
        if ($_SESSION['logged_id'] == true) {
            $cart_products = $sql->query("
            SELECT cart.id, cart.product_id, cart.product_size, cart.pcs, products.name, products.price, products.tax
            FROM cart
            JOIN products ON cart.product_id = products.id
            WHERE cart.customer_id = '$customer_id' AND cart.pcs > 0
        ");
        }
    
        

        while ($cart_row = $cart_products->fetch_assoc()) {
             ?>

            <li><input type="hidden" id="session_id" value="<?= $session_id ?>">
                <input type="hidden" id="product_id<?= $cart_row['id'] ?>" value="<?= $cart_row['product_id'] ?>"><a href="product.php?id=<?=$cart_row['product_id']?>"><?= $cart_row['name']; ?>, 
                <input type="hidden" id="product_size<?= $cart_row['id'] ?>" value="<?= $cart_row['product_size'] ?>"><?php echo $cart_row['product_size']; ?>, 
                <b><?= $cart_row['price'] ?> €</b>,
                <input type="hidden" id="pcs<?= $cart_row['id'] ?>" value="<?= $cart_row['pcs'] ?>">
                <p id="pcs_answer<?= $cart_row['id'] ?>"><?= $cart_row['pcs']; ?> pcs</p></a>
                <a type="button" class="plus" id="plus<?= $cart_row['id'] ?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a> 
                <a type="button" class="minus" id="minus<?= $cart_row['id'] ?>"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                <input type="hidden" id="id<?= $cart_row['id'] ?>" value="<?= $cart_row['id'] ?>">
                <a class="delete-item" type="button" id="delete-item<?= $cart_row['id'] ?>" name="delete-btn"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </li>
            <hr>
            <?php
            $sum = ($sum + $cart_row['price']) * $cart_row['pcs'];
            $taxes = ($taxes + $cart_row['tax']) * $cart_row['pcs'];
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
                $("#delete-item" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartcontent").load("cart.php");
                    $("#answer" + <?= $cart_row['product_id']; ?>).empty(); // cleans the answer-div
                    // updates cart-button number
                    $("#cart-total").load("cart_total.php");
                });

                // updates cart-button number
                $("#plus" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartcontent").load("cart.php");
                    $("#cart-total").load("cart_total.php");
                });
                // updates cart-button number
                $("#minus" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartcontent").load("cart.php");
                    $("#cart-total").load("cart_total.php");
                });

            </script>
        <?php
        }

        ?>
        <b>Total sum: <?= $sum ?> € </b> (Taxes: <?= $taxes ?> €)
    </ul>
</body>

</html>