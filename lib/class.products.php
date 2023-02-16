<?php
session_start();

class Products {

    // creating variables

    protected $id;
    protected $sql;
    protected $category;

    public function __construct($id, $sql, $category = null) {

        // taking variables to use in this object
        $this->id = $id;
        $this->sql = $sql;
        $this->category = $category;

    }


    public function getProducts() : array {

        // Global keyword allows to use variables outside of function or method
        global $sql;
        
        // Setting all the produts to an array
        $result = $sql->query("SELECT * FROM products ORDER BY id DESC");
        $product_array = [];

        // Getting data line by line and pushing it to an array
        while($row = $result->fetch_assoc()) { // fetch_assoc returns the data of the table as long as there is data
			$product_array[] = $row; // Takes a row and save it to an arrays cell
		}
        
        return $product_array;
    }

    // returns products based on given category-name
    public function getCategory() : array {

        global $sql;
        
        // Setting all the produts to an array
        $result = $sql->query("SELECT * FROM products WHERE category ='". $this->category ."'");
        $category_array = [];

        // Getting data line by line and pushing it to an array
        while($row = $result->fetch_assoc()) { // fetch_assoc returns the data of the table as long as there is data
			$category_array[] = $row; // Takes a row and save it to an arrays cell
		}
        
        return $category_array;
    }

}

?>