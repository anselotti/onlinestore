<?php
require('db.php');

function getStock($id) : bool {

    global $sql;
    
    // Setting all the produts from stock to an array
    $result = $sql->query("SELECT * FROM stock WHERE product_id = '" . $id . "'");
    $row = $result->num_rows;

    // if there there isn't any, returns true what means there is no staff in stock. 
    // TO DO: Make this to return if there are < 5 or 0 in stock.
    if($row < 1) {

        return true;

    } else {

        return false;

    }
}

?>