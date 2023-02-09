<?php
session_start();

class Cart {

    // creating variables

    protected $id;
    protected $sql;
    protected $session_id;
    protected $product_size;

    public function __construct($id, $sql, $session_id, $product_size) {

        // taking variables to use in this object
        $this->id = $id;
        $this->sql = $sql;     
        $this->session_id = $session_id;
        $this->product_size = $product_size;

    }



    // deletes product from cart mysql-table <-- TO DO: code delete to main.class
    public function delete() : bool {

        
        if($this->sql->query("DELETE FROM cart WHERE id='".$this->id."'")) {
            return true;
        } else {
            return false;
        }
        
    }

    // adds product to cart mysql-table <-- TO DO: code delete to main.class
    public function add() : bool {

        if ($this->sql->query("INSERT INTO cart (product_id, session_id, product_size) VALUES ('". $this->id ."', '".$this->session_id. "', '".$this->product_size. "')")) {

            return true;

        } else {

            return false;
            
        }
    }

    // gets all products from cart-table based on session_id. returns an array.
    public function getCart() : array {

        // Global keyword allows to use variables outside of function or method
        global $sql;
        
        // Setting all the produts to an array
        $result = $sql->query("SELECT * FROM cart WHERE session_id='". $this->session_id ."'");
        $cart_array = [];

        // Getting data line by line and pushing it to an array
        while($row = $result->fetch_assoc()) { // fetch_assoc returns the data of the table as long as there is data
			$cart_array[] = $row; // Takes a row and save it to an arrays cell
		}
        
        return $cart_array;
    }

}

?>