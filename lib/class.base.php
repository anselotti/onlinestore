<?php
session_start();

class Base {

    // creating variables

    protected $id;
    protected $sql;
    protected $tablename;

    public function __construct($id, $sql, $tablename = null) {

        // taking variables to use in this object
        $this->id = $id;
        $this->sql = $sql;
        $this->tablename = $tablename;
        $this->init();

    }


    // deleting data from the given table where id is given id
    public function delete() : bool {

        if($this->sql->query("DELETE FROM ".$this->tablename." WHERE id='".$this->id."'")) {

            return true;

        } else {

            return false;

        }
        
    }


}

?>