<?php
session_start();

class Cart {

    // creating variables

    protected $id;
    protected $sql;
    protected $session_id;
    protected $product_size;
    protected $pcs;

    public function __construct($id, $sql, $session_id, $product_size, $pcs = null) {

        // taking variables to use in this object
        $this->id = $id;
        $this->sql = $sql;     
        $this->session_id = $session_id;
        $this->product_size = $product_size;
        $this->pcs = $pcs;

    }



    // deletes product from cart mysql-table <-- TO DO: code delete to main.class
    public function delete() : bool {

        
        if($this->sql->query("DELETE FROM cart WHERE id='".$this->id."'")) {
            return true;
        } else {
            return false;
        }
        
    }

    // adds product to cart mysql-table. If a product with this size exists in the cart object checks it and adds one to a pcs column.
    public function add() : bool {
        
        // checks how many rows whit given session_id, id and product_size
        $result = $this->sql->query("SELECT * FROM cart WHERE session_id = '$this->session_id' AND product_id = '$this->id' AND product_size = '$this->product_size'");
        
        // if there isn't any
        if($result->num_rows < 1) { 

            // adds one to pcs variable and later inserts into row with all the other variablses.
            $this->pcs++; 
            
            if ($this->sql->query("INSERT INTO cart (product_id, session_id, product_size, pcs) VALUES ('". $this->id ."', '".$this->session_id. "', '".$this->product_size. "', '".$this->pcs. "')")) {
    
                return true;
    
            } else {
    
                return false;
                
            }
    
        } else { // else adds one piece into the existing row
            
            $row = $result->fetch_assoc(); 
            $this->pcs = $row['pcs'];
            $this->pcs++;

            $this->sql->query("UPDATE cart SET pcs = '".$this->pcs."' WHERE session_id = '$this->session_id' AND product_id = '$this->id' AND product_size = '$this->product_size'");
            return true;
            
        }
    
    }

    public function minus() : bool {
        
         // checks how many rows whit given session_id, id and product_size
        $result = $this->sql->query("SELECT * FROM cart WHERE session_id = '$this->session_id' AND product_id = '$this->id' AND product_size = '$this->product_size'");

        if($result->num_rows) { 

            $row = $result->fetch_assoc(); 
            $this->pcs = $row['pcs'];
            $this->pcs--;

            $this->sql->query("UPDATE cart SET pcs = '".$this->pcs."' WHERE session_id = '$this->session_id' AND product_id = '$this->id' AND product_size = '$this->product_size'");
            return true;
    
        } else { // else adds one piece into the existing row
            
            return false;
            
        }
}

    // gets all products from cart-table based on session_id. returns an array.
    public function getCart() : array {

        // Global keyword allows to use variables outside of function or method
        global $sql;
        
        // Setting all the produts to an array
        $result = $sql->query("SELECT * FROM cart WHERE pcs > 0 AND session_id='". $this->session_id ."'");
        $cart_array = [];

        // Getting data line by line and pushing it to an array
        while($row = $result->fetch_assoc()) { // fetch_assoc returns the data of the table as long as there is data
			$cart_array[] = $row; // Takes a row and save it to an arrays cell
		}
        
        return $cart_array;
    }

}

?>