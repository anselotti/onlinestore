<!-- FOOTER STARTS -->
<div class="container-fluid" id="footer">
  <div class="row">
    <div class="col-md-4">
    &copy; Anssi Kosunen
    </div>
    <div class="col-md-4" id="footer-center" >
      Ramp Riot Online Store<br>
      Koulutie 1, <br>
      70100 KUOPIO, Finland<br>
      040 123 4567
    </div>
    <div class="col-md-4" id="footer-right">
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

  // this updates cart's number when class "btn" is clicked.
    var btn = document.getElementsByClassName("btn");

    for (var i = 0; i < btn.length; i++) {

        btn[i].addEventListener("click", function() {

            var cartTotal = document.getElementById("cart-total");
            var total = 0;

            fetch('getCartTotal_ajax.php', {
                method: 'POST', // Send as POST
                headers: { // Tells headers to the server
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    total: total
                }) // Sending JSON-data to server
            }).then(function(response) {
                // when then-promise has been succesful parse to json
                return response.json();
            }).then(function(myJson) {
                // when then-promise has been succesful modal opens
                console.log("myJson");
                cartTotal.innerHTML = myJson;
            });

        });

    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>