function initPayPalButton() {

  paypal.Buttons({
    style: {
      shape: 'rect',
      color: 'black',
      layout: 'vertical',
      label: 'paypal',

    },

    createOrder: function (data, actions) {
      return actions.order.create({
        purchase_units: [{ "description": "Ramp Riot online store payment", "amount": { "currency_code": "EUR", "value": 0.01 } }]
      });
    },

    onApprove: function (data, actions) {
      return actions.order.capture().then(function (orderData) {

        // Full available details
        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

        // Show a success message within this page, e.g.
        const element = document.getElementById('paypal-button-container');
        element.innerHTML = '';
        element.innerHTML = '<h3>Thank you for your payment!</h3>';

        document.getElementById("accept").click();

      });
    },

    onError: function (err) {
      console.log(err);
    }
  }).render('#paypal-button-container');
}
initPayPalButton();

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