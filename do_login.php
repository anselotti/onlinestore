<?PHP
session_start();
require('lib/db.php');
require("lib/class.base.php");
require("lib/class.customer.php");

$customer = new Customer(0, $sql);
$customer->email = $_POST['email'];
$customer->password = $_POST['password'];

if($customer->login()) {

    header("Location: index.php");

} else {

    header("Location: login.php?error=2");

}



?>