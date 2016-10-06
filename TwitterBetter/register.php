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


// STEP 2. Build connection
// Secure way to build connection

$file = parse_ini_file("../../../TwitterBetter.ini");

// Store in php variable information from ini variables
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);

// include access.php to call func from access.php file
require ("secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();


// STEP 3. Insert user information
$result = $access->registerUser($username, $secured_password, $salt, $email, $fullname);

if ($result) {

    $user = $access->selectUser($username);

    $returnArray["status"] = "200";
    $returnArray["message"] = "Successfully registered";
    $returnArray["id"] = $user["id"];
    $returnArray["username"] = $user["username"];
    $returnArray["email"] = $user["email"];
    $returnArray["fullname"] = $user["fullname"];
    $returnArray["ava"] = $user["ava"];


} else {
    $returnArray["status"] = "400";
    $returnArray["message"] = "Could not register with provided information";

}

    // STEP 4. DISCONNECT
    $access->disconnect();

    // STEP 5. Json data
    echo json_encode($returnArray);








?>