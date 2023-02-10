<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Index';
require('templates/header.php');


?>

            <!-- CONTENT STARTS -->
            <div class="col-lg-10" id="content">

                
                <div id="carouselExampleCaptions" class="carousel slide">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="uploads/skate1.jpg" class="d-block w-100" alt="...">
                            <div class="carousel-caption" style="text-align: left;">
                                <h1>Welcome</h1>
                                <p>We are passionate about skateboarding and all things related. </p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="uploads/skate2.jpg" class="d-block w-100" alt="...">
                            <div class="carousel-caption" style="text-align: left;">
                                <h1>Skateboards And Clothing</h1>
                                <p>Here, you will find high-quality skateboards and stylish clothing. </p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="uploads/skate3.jpg" class="d-block w-100" alt="...">
                            <div class="carousel-caption" style="text-align: left;">
                                <h1>Stuff For Every Skateboarder</h1>
                                <p>Whether you're just starting out or you're a seasoned pro, we have everything you need.</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    
                </div>           

                <div class="row" style="padding: 20px;">
                    <h2>The latest products in the market!</h2>
                    <!--  -->
                    <?php
                    // Query gets all products to an array sorted from newest to oldest
                    $result = $sql->query("SELECT * FROM products ORDER BY id DESC");

                    // For-loop to get three recently added products, making all the ids unique
                    // to use them later in the script 
                    for ($i = 0; $i < 4; $i++) {
                        $row = $result->fetch_assoc(); ?>

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

                                        if ($row['category'] == 'shoes') {?>

                                        <select id="form-select<?= $row['id']?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                            <option selected>Size</option>
                                            <option value="39">39</option>
                                            <option value="40">40</option>
                                            <option value="41">41</option>
                                        </select>
                                        <?php 
                                        } else if ($row['category'] == 'clothing') {
                                            ?>
                                            <select id="form-select<?= $row['id']?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                                <option selected>Size</option>
                                                <option value="S">S</option>
                                                <option value="M">M</option>
                                                <option value="L">L</option>
                                                <option value="XL">XL</option>
                                            </select>
                                            <?php 
                                        } else { ?>
                                            
                                            <select id="form-select<?= $row['id']?>" class="form-select" aria-label="Default select example" style="width: 90%; margin: 10px;">
                                                <option selected>Size</option>
                                                <option value="ONESIZE">ONESIZE</option>
                                            </select>
                                            <?php
                                        }

                                        ?>
                                        <button class="btn btn-primary" id="add<?= $row['id'] ?>" name="add" type="button" class="card-link">Add to basket</button>
                                        <p class="answer" id="answer<?= $row['id']; ?>">
                                        <!-- Foreach check if this product is already in cart and show that on the card. -->
                                            <?php
                                            foreach ($cartproducts as $key => $value) {

                                                if ($row['id'] === $value['product_id']) {
                                                    echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i> In basket';
                                                    break; // breaks when first match found otherwise it prints as many times as there are matches found 
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
                                        product_size: product_size,
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
                            $("#content").click(function() {
                                $("#cart-total").load("cart_total.php");
                            });



                        </script>

                    <?php
                    }
                    ?>
                        

                        <a href="products.php" style="text-align: center;" class="menu-button"><i class="fa fa-long-arrow-right" aria-hidden="true"></i> Check out our wide demand for skateboarding products.</a>

                </div>

            </div>
        </div>
    </div>
    <!-- CONTENT ENDS -->

    <!-- FOOTER INCLUDE -->
    <?php require('templates/footer.php'); ?>