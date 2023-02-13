<?php
session_start(); // Session starts
$session_id = session_id();
require("lib/db.php"); // Connection to database
require("lib/class.cart.php");
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
        $cart_result = $sql->query("SELECT * FROM cart WHERE session_id = '$session_id'");

        while ($cart_row = $cart_result->fetch_assoc()) {


            $products = $sql->query("SELECT * FROM products WHERE id='" . $cart_row['product_id'] . "'");
            $productsrow = $products->fetch_assoc(); ?>
            <li><?= $productsrow['name']; ?>, <?php echo $cart_row['product_size']; ?>, <b><?= $productsrow['price'] ?> €</b>, <?= $cart_row['pcs']; ?> pcs
                <input type="hidden" id="id<?= $cart_row['id'] ?>" value="<?= $cart_row['id'] ?>">
                <button class="fa fa-trash-o fa-trash-custom" type="button" id="delete-item<?= $cart_row['id'] ?>" name="delete-btn"></button>
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
                
                // updates cart using ajax and deleting "answer" in the product-card
                $("#delete-item" + <?= $cart_row['id'] ?>).click(function() {
                    $("#cartcontent").load("cart.php");
                    $("#answer" + <?= $cart_row['product_id']; ?>).empty();
                    // updates cart-button number
                    $("#cart-total").load("cart_total.php");
                });

            </script>
        <?php
        }

        ?>
        <b>Total sum: <?= $sum ?> € </b><i>(Taxes: <?= $taxes ?> €)</i>
    </ul>
</body>

</html>