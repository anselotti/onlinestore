<?php
session_start();

class Cart extends Base {

    // creating variables
    public $session_id;
    public $product_id;
    public $product_size;
    public $pcs;
    public $customer_id;

    public function init() {

        $result = $this->sql->query("SELECT * FROM cart WHERE id ='".$this->id."'");
        $row = $result->fetch_assoc();

        // taking variables to use in this object
        $this->session_id = $row['session_id'];
        $this->product_id = $row['product_id'];
        $this->product_size = $row['product_size'];
        $this->pcs = $row['pcs'];
        $this->customer_id = $row['customer_id'];

    }


    // adds product to cart mysql-table. If a product with this size exists in the cart object checks it and adds one to a pcs column.
    public function addCart() : bool {
        
        // checks how many rows whit given session_id, id and product_size
        if ($_SESSION['logged_id'] == false) {

            $result = $this->sql->query("SELECT * FROM cart WHERE session_id = '" . $this->session_id . "' AND product_id = '" . $this->product_id . "' AND product_size = '" . $this->product_size . "'");

        } else {

            $result = $this->sql->query("SELECT * FROM cart WHERE customer_id='" . $this->customer_id . "' AND product_id = '" . $this->product_id . "' AND product_size = '" . $this->product_size . "'");

        }
        
        // if there isn't any
        if($result->num_rows < 1) { 

            // adds one to pcs variable and later inserts into row with all the other variablses.
            $this->pcs++; 
            
            if ($this->sql->query("INSERT INTO cart (product_id, customer_id, session_id, product_size, pcs) VALUES ('". $this->product_id ."', '".$this->customer_id."', '".$this->session_id. "', '".$this->product_size. "', '".$this->pcs. "')")) {
    
                return true;
    
            } else {
    
                return false;
                
            }
    
        } else { // else adds one piece into the existing row
            
            $row = $result->fetch_assoc(); 
            $this->pcs = $row['pcs'];
            $this->pcs++;

            $this->sql->query("UPDATE cart SET pcs = '".$this->pcs."' WHERE (session_id = '$this->session_id' OR customer_id ='".$this->customer_id."') AND product_id = '$this->product_id' AND product_size = '$this->product_size'");
            return true;
            
        }
    
    }

    public function minus() : bool {
        
         // checks how many rows whit given session_id, id and product_size
         if ($_SESSION['logged_id'] == false) {

            $result = $this->sql->query("SELECT * FROM cart WHERE session_id = '" . $this->session_id ."' AND product_id = '" .$this->id."' AND product_size = '".$this->product_size."'");

        } else {

            $result = $this->sql->query("SELECT * FROM cart WHERE customer_id='".$this->customer_id."' AND product_id = '" .$this->id."' AND product_size = '".$this->product_size."'");

        }
        

        if($result->num_rows) { 

            $row = $result->fetch_assoc(); 
            $this->pcs = $row['pcs'];
            $this->pcs--;
            
            // Deletes from cart at all if quantity is 0
            if ($this->pcs == 0) {

                $this->delete();
            
            }


            if ($_SESSION['logged_id'] == false) {

                $this->sql->query("UPDATE cart SET pcs = '".$this->pcs."' WHERE session_id = '" . $this->session_id . "' AND product_id = '" . $this->id . "' AND product_size = '" . $this->product_size . "'");
                return true;

            } else {

                $this->sql->query("UPDATE cart SET pcs = '".$this->pcs."' WHERE customer_id = '" . $this->customer_id . "' AND product_id = '" . $this->id . "' AND product_size = '" . $this->product_size . "'");
                return true;

            }
    
        } else { // else adds one piece into the existing row
            
            return false;
            
        }
}

    // gets all products from cart-table based on session_id. returns an array.
    public function getCart() : array {

        // Global keyword allows to use variables outside of function or method
        global $sql;
        
        // Setting all the produts to an array
        if ($_SESSION['logged_id'] == false) {

            $result = $sql->query("SELECT * FROM cart WHERE pcs > 0 AND session_id='". $this->id ."'");
        }

        if($_SESSION['logged_id'] == true) {

            $result = $sql->query("SELECT * FROM cart WHERE pcs > 0 AND customer_id='". $this->customer_id ."'");
            
        }
        
        $cart_array = [];

        // Getting data line by line and pushing it to an array
        while($row = $result->fetch_assoc()) { // fetch_assoc returns the data of the table as long as there is data
			$cart_array[] = $row; // Takes a row and save it to an arrays cell
		}
        
        return $cart_array;
    }

    // adds customer_id to the cart-table
    public function addCustomerToCart() : bool {

        if ($this->sql->query("UPDATE cart SET customer_id = '".$this->id."' WHERE session_id = '". $this->session_id ."'")) {

            return true;

        } else {

            return false;

        }
    }

}
