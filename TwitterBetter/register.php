<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 04.10.16
 * Time: 23:05
 */


// STEP 1. Declare params of user information
// Securing information and securing in variables
$username = htmlentities($_REQUEST["username"]);
$password = htmlentities($_REQUEST["password"]);
$email = htmlentities($_REQUEST["email"]);
$fullname = htmlentities($_REQUEST["fullname"]);


// if GET or POST are empty
if (empty($username) || empty($password) || empty($email) || empty($fullname)) {

    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing required information";
    echo json_encode($returnArray);
    return;
}


// Secure password
$salt = openssl_random_pseudo_bytes(20);
$secured_password = sha1($password . $salt);



?>