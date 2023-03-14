<?php
require('lib/db.php');

// Recieves JSON-data from client
$json = file_get_contents('php://input');
// Transfering json-string to PHP-array
$json = json_decode($json, true);

$name = $sql->real_escape_string($json["name"]);
$email = $sql->real_escape_string($json["email"]);
$textarea = $sql->real_escape_string($json["textarea"]);
 

$sendEmail = "Henkilö nimeltä " .$name. ", jonka email on: " . $email ." otti yhteyttä web-sivujen kautta. Hän lähetti viestin: " . $textarea;   

if (mail("anssi.kosunen@gmail.com", "Sait viestin websivuilta ", $sendEmail)) {
    echo json_encode('<p><b>Message sent!</b></p>');
} else {
    echo json_encode('<p>Something went wrong.</p>');
}



?>