<?php

class Customer extends Base
{

    public $firstname;
    public $lastname;
    public $address;
    public $zip;
    public $city;
    public $country;
    public $phone;
    public $email;
    public $password;

    public function init()
    {

        $result = $this->sql->query("SELECT * FROM customer WHERE id = '" . $this->id . "'");
        $row = $result->fetch_assoc();

        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->address = $row['address'];
        $this->zip = $row['zip'];
        $this->city = $row['city'];
        $this->country = $row['country'];
        $this->email = $row['email'];
        $this->password = $row['password'];
    }

    function login(): bool
    {

        // creating sql-variable.
        global $sql;

        // creating username- and password variables with crypting
        $email = $sql->real_escape_string($this->email);
        $password = $sql->real_escape_string($this->password);

        // sql-query to check if given username and password exists and matches
        $result = $sql->query("SELECT id, admin, email FROM customer WHERE email = '" . $email . "' AND password = '" . $password . "'");

        // if there are any, then _SESSION is true and user is logged.
        if ($result->num_rows) {

            $row = $result->fetch_assoc();
            $_SESSION['logged_id'] = $row['id'];
            $_SESSION['admin'] = $row['admin']; // if admin-panel will be made
            $_SESSION['customer'] = $row['email'];

            return true;
        } else {

            return false;
        }
    }

    function getCustomer(): array
    {


        // Global keyword allows to use variables outside of function or method
        global $sql;

        // Setting all the produts to an array
        $result = $sql->query("SELECT * FROM customer WHERE id='" . $this->id . "'");
        $customer_arr = [];

        // Getting data line by line and pushing it to an array
        while ($row = $result->fetch_assoc()) { // fetch_assoc returns the data of the table as long as there is data
            $customer_arr[] = $row; // Takes a row and save it to an arrays cell
        }

        return $customer_arr;
    }

    // modifies customer data
    public function modify(): bool
    {

        if ($this->sql->query("UPDATE customer SET firstname = '" . $this->firstname . "', lastname = '" . $this->lastname . "', address = '" . $this->address . "', zip = '" . $this->zip . "', city = '" . $this->city . "', country = '" . $this->country . "', phone = '" . $this->phone . "', email = '" . $this->email . "' WHERE id = '" . $this->id . "'")) {

            return true;
        } else {

            return false;
        }
    }
}
