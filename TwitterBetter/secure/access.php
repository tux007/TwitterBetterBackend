<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 04.10.16
 * Time: 22:34
 */

// Declare class to access this php file
class access {

    // global connection variables
    var $host = null;
    var $user = null;
    var $pass = null;
    var $name = null;
    var $conn = null;
    var $result = null;


    // constructing class
    function __construct($dbhost, $dbuser, $dbpass, $dbname) {

        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->pass = $dbpass;
        $this->name = $dbname;

    }

    // connection function
    public function connect() {

        // establish connection and store it in $conn
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);

        // if error
        if (mysqli_connect_errno()) {

            echo 'Could not connect to database';

        }

        // support all languages
        $this->conn->set_charset("utf8");
    }


}

    // disconnection function
    public function disconnect() {

        if ($this->conn != null ) {

            $this->conn->close();

        }







    }

?>

