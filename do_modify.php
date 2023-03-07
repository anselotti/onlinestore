<?php
require("lib/db.php");
require("lib/class.base.php");
require("lib/class.customer.php");

$customer = new Customer($_POST['id'], $sql);

// sanitizing all the date what goes to the database and sending variables to the Customer-class
$customer->firstname = $sql->real_escape_string($_POST['firstname']);
$customer->lastname = $sql->real_escape_string($_POST['lastname']);
$customer->address = $sql->real_escape_string($_POST['address']);
$customer->zip = $sql->real_escape_string($_POST['zip']);
$customer->city = $sql->real_escape_string($_POST['city']);
$customer->country = $sql->real_escape_string($_POST['country']);
$customer->phone = $sql->real_escape_string($_POST['phone']);
$customer->email = $sql->real_escape_string($_POST['email']);

if ($customer->modify()) {
    header('Location: checkout.php?error=3');
} else {
    return false;
}


?>