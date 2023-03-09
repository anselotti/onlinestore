<?PHP
session_start();
require('lib/db.php');
require("lib/class.base.php");
require("lib/class.cart.php");
require("lib/class.customer.php");

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$email = $json["email"];
$password = $json["password"];

$session_id = session_id();

$customer = new Customer(0, $sql);
$customer->email = $email;
$customer->password = $password;


if($customer->login()) {

    // setting customer_id to the cart
    $cart = new Cart($_SESSION['logged_id'], $sql);
    $cart->session_id = $session_id;

    
    if ($cart->addCustomerToCart()) {

        echo json_encode("");

    } else {

        echo json_encode("Email and password do not match.");

    }

} else {

    echo json_encode("Error with login. Please try again later.");

}



?>