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
    $cart = new Cart(0, $sql);
    $cart->customer_id = $_SESSION['logged_id'];
    $cart->session_id = $session_id;

    
    $cart->addCustomerToCart();

    echo json_encode("");


} else {

    echo json_encode('<p style="padding: 10px; border-radius: 10px; color: white; background-color: rgb(122, 47, 47);">Email and password do not match.</p>');

}



?>