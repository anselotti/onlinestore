<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Products';
require('templates/header.php');
require("lib/class.products.php");


// creating $category so later it is possible to list products with given GET-category name
$category = $_GET['category'];

// using getCategory or getProducts object
$products = new Products(0, $sql, $category);


if (!isset($category)) { // if $_GET['category'] has not set, if uses object to get all the products to row

    $row = $products->getProducts();

} else { // if $_GET['category'] is set, else uses object to get all the products in given category

    $row = $products->getCategory();

}

?>

<!-- CONTENT STARTS -->
<div class="col-lg-10" id="content">
    <!-- Category-buttons -->
    <div class="container" style="padding: 20px;">
        <h1 style="color: #2C4A52;">Products</h1>
        <h2 style="padding: 20px;">Select category from below:</h2>
        <a href="products.php" class="btn btn-dark" id="cat-all" name="category" type="button">all</a>
        <?php
            // Brings all the categories found in category-table
            $result_products = $sql->query("SELECT * FROM category");

            while ($row_products = $result_products->fetch_assoc()) {
                echo '<a href="products.php?category=' . $row_products['name'] .'" class="btn btn-dark" id="cat-skate" name="category" type="button">'. $row_products['name'] .'</a> ';
            }

        ?>
    </div>


    <div id="category-content">
        <div class="row" style="padding: 20px;">
            <?php

            for ($i = 0; $i < count($row); $i++) {
          
            ?>
                <!-- PRODUCT CARD -->
                
                <div class="col-sm-auto" style="margin: 10px;">
                    <div class="card card-custom" style="width: 18rem; height: 100%;">
                        <img style="width: 100%; " src="<?= $row[$i]['imgurl']; ?>" class="card-img-top" alt="<?= $row[$i]['short_description'] ?>">
                        <div class="card-body">
                            <a href="product.php?id=<?= $row[$i]['id']; ?>">
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

                                <!-- Checks if the product found in stock using getStock-function -->
                                <p>
                                    <?php

                                    if($stock = getStock($row[$i]['id'])) {


                                        echo $stock;

                                    } 

                                    ?>
                                </p>

                                <!-- Checks what category and then shows the right size-type. -->

                                    <select id="form-select<?= $row[$i]['id'] ?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                        <option selected>Size</option>
                                        <!-- Prints sizes what are in the stock -->
                                        <?php
                                        $result_sizes = $sql->query("SELECT * FROM stock WHERE product_id = '" . $row[$i]['id'] . "'");
                                        while ($row_sizes = $result_sizes->fetch_assoc()) {

                                            echo '<option value="' . $row_sizes['size'] . '">' . $row_sizes['size'] . '</option>';
                                        
                                        } 
                                        ?>
                                        
                                    </select>
                                <button class="btn btn-dark" id="add<?= $row[$i]['id']; ?>" name="add" type="button">Add to cart</button>
                                <p class="answer" id="answer<?= $row[$i]['id']; ?>" style="text-align: center; color: white; background-color: #537072">
                                    <?php

                                    foreach ($cartproducts as $key => $value) {

                                        if ($row[$i]['id'] === $value['product_id']) {

                                            echo '<p>In cart</p>';

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
                        var product_size = document.getElementById("form-select" + <?= $row[$i]['id']?>).value;
                        
                        // returns "Select size" if product_size is not selected
                        if (product_size == "size") {
                            answer.innerHTML = "Select size";
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
                                product_size: product_size
                            }) // Sending JSON-data to server
                        }).then(function(response) {
                            // when then-promise has been succesful parse to json
                            return response.json();
                        }).then(function(myJson) {
                            // when then-promise has been succesful modal opens
                            $("#getCode").html(myJson);
                            jQuery("#addedModal").modal('show');
                        });
                    }

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