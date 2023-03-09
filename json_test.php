<button class="btn">Click me</button>
<button class="btn">Click me too</button>
<button class="btn">Me too</button>
<p id="jou"></p>

<script>
    var btn = document.getElementsByClassName("btn");

    for (var i = 0; i < btn.length; i++) {

        btn[i].onclick = function() {

            var jou = document.getElementById("jou");
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
                jou.innerHTML = myJson;
            });

        }

    }
</script>