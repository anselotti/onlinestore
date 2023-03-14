<?php
session_start();

class Order extends Base {

    // creating variables

    protected $customer_id;
    protected $sql;

    public function __construct($customer_id, $sql) {

        // taking variables to use in this object
        $this->customer_id = $customer_id;
        $this->sql = $sql;

    }


    // returns customer's orders
    public function getOrders() : array {

        global $sql;
        
        // Setting all the orders to an array
        $result = $sql->query("SELECT * FROM orders WHERE customer_id ='". $this->customer_id ."'");
        $arr = [];

        // Getting data line by line and pushing it to an array
        while($row = $result->fetch_assoc()) { // fetch_assoc returns the data of the table as long as there is data
			$arr[] = $row; // Takes a row and save it to an arrays cell
		}
        
        return $arr; // returns array
    }

}

?>