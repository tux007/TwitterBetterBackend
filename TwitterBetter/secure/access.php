<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 04.10.16
 * Time: 22:34
 */

// Declare class to access this php file
class access
{

    // global connection variables
    var $host = null;
    var $user = null;
    var $pass = null;
    var $name = null;
    var $conn = null;
    var $result = null;


    // constructing class
    function __construct($dbhost, $dbuser, $dbpass, $dbname)
    {

        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->pass = $dbpass;
        $this->name = $dbname;

    }

    // connection function
    public function connect()
    {

        // establish connection and store it in $conn
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);

        // if error
        if (mysqli_connect_errno()) {
            echo 'Could not connect to database';

        }

        // support all languages
        $this->conn->set_charset("utf8");
    }


    // disconnection function
    public function disconnect() {

        if ($this->conn != null) {
            $this->conn->close();

        }

    }

    // Insert user details
    public function registerUser($username, $password, $salt, $email, $fullname) {

        // SQL command
        $sql = "INSERT INTO users SET username=?, password=?, salt=?, email=?, fullname=?";

        // Store query result in $statement
        $statement = $this->conn->prepare($sql);

        // if error
        if (!$statement) {
            throw new Exception($statement->error);

        }

        // Bind 5 params of type string to be placed in $sql command
        $statement->bind_param("sssss", $username, $password, $salt, $email, $fullname);

        $returnValue= $statement->execute();

        return $returnValue;

    }

    // Select user information
    public function selectUser($username) {

        $returnArray = array();

        //SQL command
        $sql = "SELECT * FROM users WHERE username='".$username."'";

        // Assign result we got from $sql to $result var
        $result = $this->conn->query($sql);

        // if there is at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >=1 )) {

            // Assign results we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            //
            if (!empty($row)) {
                $returnArray = $row;

            }
        }

        return $returnArray;

    }

    // Save email confirmation message token
    public function saveToken($table, $id, $token) {

        // sql statement
        $sql = "INSERT INTO $table SET id=?, token=?";

        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // Error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // Bind params to sql statement
        $statement->bind_param("is", $id, $token);

        // launch and store feedback in $returnValue
        $returnValue = $statement->execute();

        return $returnValue;

    }

    // GET id of user via $emailToken received by email
    function getUserID($table, $token) {

        $returnArray = array();

        // SQL statement
        $sql = "SELECT id FROM $table WHERE token = '".$token."'";

        // Launch SQL statement
        $result = $this->conn->query($sql);

        // If $result is not empty and stores some content
        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }

        }

        return $returnArray;

    }

    // Change status of emailConfirmation column
    function emailConfirmationStatus($status, $id) {

        $sql = "UPDATE users SET emailConfirmed=? WHERE id=?";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $statement->bind_param("ii", $status, $id);

        $returnValue = $statement->execute();

        return $returnValue;

    }

    // Delete token once email is confirmed
    function deleteToken($table, $token) {

        $sql = "DELETE FROM $table WHERE token=?";

        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $statement->bind_param("s", $token);
        $returnValue = $statement->execute();

        return $returnValue;

    }

    // Get full user information
    public function getUser($username) {

        // Declare array to store all information
        $returnArray = array();

        // SQL statement
        $sql = "SELECT * FROM users WHERE username='".$username."'";

        // Execute / query $sql
        $result = $this->conn->query($sql);

        // Check if we get results
        if ($result != null && (mysqli_num_rows($result) >= 1)) {

            // Assign result to $row as assoc array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // If assigned to $row - assign everything to $returnArray
            if (!empty($row)) {
                $returnArray = $row;

            }

        }

        return $returnArray;

    }

    // Select user information with email
    public function selectUserViaEmail($email) {

        $returnArray = array();

        //SQL command
        $sql = "SELECT * FROM users WHERE email='".$email."'";

        // Assign result we got from $sql to $result var
        $result = $this->conn->query($sql);

        // if there is at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >=1 )) {

            // Assign results we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            //
            if (!empty($row)) {
                $returnArray = $row;

            }
        }

        return $returnArray;

    }




}
?>

