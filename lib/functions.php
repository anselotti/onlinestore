<?php
require('db.php');

function getStock($id) : bool {

    global $sql;
    
    // Setting all the produts to an array
    $result = $sql->query("SELECT * FROM stock WHERE product_id = '" . $id . "'");
    $row = $result->num_rows;

    if($row < 1) {

        return true;

    } else {

        return false;

    }
}

?>