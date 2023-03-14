<!-- FOOTER STARTS -->

<div class="container-fluid border-top" style="background-color:#537072; color:white;">
  <div class="container">
  <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5">
    <div class="col mb-3">
      <a href="index.php">
        <img src="uploads/logo.png" width="200">
      </a>
      <p>&copy; Anssi Kosunen <script>document.write( new Date().getFullYear() );</script></p>
    </div>

    <div class="col mb-3">
    </div>    

    <div class="col mb-3">
      <h5>Address</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2">Ramp Riot Online Store Ltd</li>
        <li class="nav-item mb-2">Koulutie 1</li>
        <li class="nav-item mb-2">70100 KUOPIO, Finland</li>
        <li class="nav-item mb-2">040 123 4567</li>
      </ul>
    </div>

    <div class="col mb-3">
      <h5>Menu</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="index.php" class="text-white">Home</a></li>
        <li class="nav-item mb-2"><a href="products.php" class="text-white">Products</a></li>
        <li class="nav-item mb-2"><a href="checkout.php" class="text-white">Checkout</a></li>
      </ul>
    </div>

    <div class="col mb-3">
     
    <form>
      <h5>Send feedback</h5>
      <div id="answerContact">
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="nameContact" placeholder="John Doe">
          </div>
        </li>
        <li class="nav-item mb-2">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailContact" placeholder="name@example.com">
          </div>
        </li>
        <li class="nav-item mb-2">
          <div class="mb-3">
              <label for="textarea" class="form-label">Textarea</label>
              <textarea class="form-control" id="textareaContact" rows="3"></textarea>
          </div>
        </li>
        <li class="nav-item mb-2">
          <button type="button" class="btn btn-dark" id="submitContact">Send</button>
        </li>
      </ul>
    </div>
    </form>
    </div>
  </footer>
  </div>
</div>

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

    var print = document.getElementById("answerContact");
    var submitContact = document.getElementById("submitContact");

    submitContact.onclick = function() {

        var name = document.getElementById("nameContact").value;
        var email = document.getElementById("emailContact").value;
        var textarea = document.getElementById("textareaContact").value;

        fetch('contact_ajax.php', {
            method: 'POST', // Send as POST
            headers: { // Tells headers to the server
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({name: name, email: email, textarea: textarea }) // Sending JSON-data to server
        }).then(function(response) {
            // when then-promise has been succesful parse to json
            return response.json();
        }).then(function(myJson) {
            // when then-promise has been succesful prints json
            print.innerHTML = myJson;
        });
    }

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>