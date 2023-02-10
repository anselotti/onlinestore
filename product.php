<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Products';
require('templates/header.php');

// creating variables to use in dynamic pages
$id = $_GET['id'];

$result_p = $sql->query("SELECT * FROM products WHERE id = '$id'");
$row_p = $result_p->fetch_assoc();

$name = $row_p['name'];
$short_description = $row_p['short_description'];
$description = $row_p['description'];
$price = $row_p['price'];
$tax = $row_p['tax'];
$category = $row_p['category'];
$imgurl = $row_p['imgurl'];

?>

<!-- CONTENT STARTS -->
<div class="col-lg-10" id="content">

  <div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      <div class="col-10 col-sm-8 col-lg-6">
        <img src="<?= $imgurl ?>" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
      </div>
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold lh-1 mb-3"><?= $name ?></h1>
        <p><i>Category: <?= $category ?></i></p>
        <p class="lead"><?= $short_description ?></p>
        <h3><?= $price ?> €</h3>
        <p>
          Taxes: <?= $tax ?> €
        </p>

        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
        
      </div>
      <?php 
          // Gives proper size range depends on product category
          if ($category == 'shoes') {?>

          <select id="form-select" class="form-select" aria-label="Default select example" style="width: 90%; margin-bottom: 10px;">
              <option selected>Size</option>
              <option value="39">39</option>
              <option value="40">40</option>
              <option value="41">41</option>
          </select>
          <?php 
          } else if ($category == 'clothing') {
              ?>
              <select id="form-select" class="form-select" aria-label="Default select example" style="width: 90%; margin-bottom: 10px;">
                  <option selected>Size</option>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                  <option value="XL">XL</option>
              </select>
              <?php 
          } else { ?>
              
              <select id="form-select" class="form-select" aria-label="Default select example" style="width: 90%; margin-bottom: 10px;">
                  <option selected>Size</option>
                  <option value="ONESIZE">ONESIZE</option>
              </select>
              <?php
          }

          ?>
          <input id="product_id" value="<?= $id ?>" hidden>
          <input id="session_id" value="<?= $session_id ?>" hidden>
          <button id="add" type="button" class="btn btn-primary btn-lg px-4 me-md-2">Add to basket</button>
          <p id="answer"></p>
        </div>
    </div>
  </div>

  <div id="description">

    <p>
      <?= $description ?>
    </p>

  </div>

  <script>
  var submit = document.getElementById("add");

  submit.onclick = function() {

    var answer = document.getElementById("answer");
    var product_id = document.getElementById("product_id").value;
    var session_id = document.getElementById("session_id").value;
    var product_size = document.getElementById("form-select").value;

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

  $("#add").click(function() {
    $("#cart-total").load("cart_total.php");
  });
  
</script>

</div>
<!-- CONTENT ENDS -->

<?php require('templates/footer.php'); ?>