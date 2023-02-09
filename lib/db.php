<?php

// creating connection to the database

$sql = new mysqli("localhost", "anssi", "Sf3b44j*", "sakky_anssi");

if($sql->connect_errno) {
    die("Issue with connecting to database.");
}

?>
