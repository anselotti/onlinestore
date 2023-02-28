<?php
session_start();
require("lib/db.php");
require('lib/functions.php');
require('lib/class.base.php');
require("lib/class.cart.php");
require("lib/class.customer.php");


// importing data from the contact form

// sanitizing information security

$firstname = $sql->real_escape_string($_POST['firstname']);
$lastname = $sql->real_escape_string($_POST['lastname']);
$address = $sql->real_escape_string($_POST['address']);
$zip = $sql->real_escape_string($_POST['zip']);
$city = $sql->real_escape_string($_POST['city']);
$country = $sql->real_escape_string($_POST['country']);
$phone = $sql->real_escape_string($_POST['phone']);
$email = $sql->real_escape_string($_POST['email']);
$password = $sql->real_escape_string($_POST['password']);
$password2 = $sql->real_escape_string($_POST['password2']);
$session_id = session_id();

$errors = [];

// security terms to password
if (strlen($password) < 5) { 
    $errors[] = "Password has include at least five characters.";
} else if (!(preg_match('#[0-9]#', $password))){
        $errors[] = "Password has include numbers.";
} else if (preg_match('/\s/', $password)) {
    $errors[] = "Password cannot include spacebar.";
}

// checking that given emails matches
if (strlen($email) < 6) {
    $errors[] = "Incorrect email.";
} else if (!(preg_match('#[@]#', $email))){
    $errors[] = "email has to include @-sign";
} 



// importing all the rows from the table where are given username or password
$queryCheck = $sql->query("SELECT * FROM customer WHERE email='$email'");

if ($queryCheck < 1) {
    $errors[] = "Customer found with this email";
}

// checking that given passwords matches
if($password !== $password2) {

    $errors[] = "Passwords does not match.";

}

// checking that given passwords matches
if($password !== $password2) {

    $errors[] = "Passwords does not match.";

}


// if not, adds user to the users-table

if (count($errors) === 0) {


    // Adding user to the users-table
    $sql->query("INSERT INTO customer (firstname, lastname, address, zip, city, country, phone, email, password) 
    VALUES ('".$firstname."', '".$lastname."', '".$address."', '".$zip."', '".$city."', '".$country."', '".$phone."', '".$email."', '".$password."')");
    
    $customer = new Customer(0, $sql);

    $customer->email = $email;
    $customer->password = $password;

    if($customer->login()) {

        header("Location: payment.php");

    } else {

        echo "Error connecting to database";
    }
    
    

} else {

    $_SESSION['errors'] = $errors;

    header('Location: checkout.php');

}



?>