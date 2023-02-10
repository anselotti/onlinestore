<!-- FOOTER STARTS -->
<div class="container-fluid" id="footer">
  <div class="row">
    <div class="col-4">
      Copyrights: Anssi Kosunen
    </div>
    <div id="footer-center" class="col-4">
      Ramp Riot Online Store<br>
      Koulutie 1, <br>
      70100 KUOPIO, Finland<br>
      040 123 4567
    </div>
    <div class="col-4" id="footer-right">
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="checkout.php">Checkout</a>
    </div>    
  </div>
</div>
<!-- FOOTER ENDS -->

<script>
  // updates cart on click event.
  $("#cartbutton").click(function() {
    $("#cartcontent").load("cart.php");
  });

  // loads proper category php-file to #category-content div on click event
  $("#cat-skate").click(function() {
    $("#category-content").load("category_skateboarding.php");
  });
  $("#cat-all").click(function() {
    $("#category-content").load("category_all.php");
  });
  $("#cat-cloth").click(function() {
    $("#category-content").load("category_clothing.php");
  });
  $("#cat-shoes").click(function() {
    $("#category-content").load("category_shoes.php");
  });
  $("#cat-shoes").click(function() {
    $("#category-content").load("category_shoes.php");
  });


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>