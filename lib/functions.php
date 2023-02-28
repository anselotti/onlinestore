<?php
require('db.php');

// function to get information to show availability of products
function getStock($id) : string {

    global $sql;
    
    // Setting all the produts from stock to an array where is the given product_id
    $result = $sql->query("SELECT * FROM stock WHERE product_id = '" . $id . "'");

    // creating counter
    $qty = 0;

    // While loop counts all the products in stock to the variable $qty
    while ($row = $result->fetch_assoc()) {

        $qty = $qty + $row['quantity'];
    }

    // returns string with information about product's amount in the stock
    if($qty < 1) {

        return '<i class="fa fa-ban" aria-hidden="true" style="color: red;"> Out of stock</i>';

    } else if ($qty < 10) {

        return '<i class="fa fa-exclamation-circle" aria-hidden="true" style="color: orange;"> Only few left in stock</i>';

    } else {

        return '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;"> In stock</i>';
    }

}

// Decreaces stock where product_id, size and quantity matches
function minusStock($product_id, $product_size, $qty) : bool {

    global $sql;

      if($sql->query("UPDATE stock SET quantity = quantity - '$qty' WHERE product_id = '" . $product_id . "' AND size = '" . $product_size . "'")) { 
  
        return true;
  
      } else {
          
        return false;
          
      }

}

// returns all categories in an array
function allCategories() : array {

    global $sql;

    $array = [];

    $result = $sql->query("SELECT * FROM category");

    while ($row = $result->fetch_assoc()) {
        $array[] = $row;
    }

    return $array;
}



?>