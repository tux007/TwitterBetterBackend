<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 12.10.16
 * Time: 22:24
 */

// STEP 1. Check variables passing to this file via POST
$username = htmlentities($_REQUEST["username"]);
$password = htmlentities($_REQUEST["password"]);

if (empty($username) || empty($password)) {

    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing required information";
    echo json_encode($returnArray);
    return;

}

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

// STEP 3. Get user information
// Assign result of execution of getUser to $user
$user = $access->getUser($username);

// If no user information available
if (empty($user)) {

    $returnArray["status"] = "403";
    $returnArray["message"] = "User not found";
    echo json_encode($returnArray);
    return;

}


// STEP 4. Check entered password
// Get pw and salt from db
$secured_password = $user["password"];
$salt = $user["salt"];

// Do passwords match: from db & entered pw
if ($secured_password = sha1($password . $salt)) {

    $returnArray["status"] = "200";
    $returnArray["message"] = "Logged in successfully";
    $returnArray["id"] = $user["id"];
    $returnArray["username"] = $user["username"];
    $returnArray["email"] = $user["email"];
    $returnArray["fullname"] = $user["fullname"];
    $returnArray["ava"] = $user["ava"];


} else {

    $returnArray["status"] = "403";
    $returnArray["message"] = "Password does not match";

}

// STEP 5. Close connection
$access->disconnect();


// STEP 6. Return all information to user
echo json_encode($returnArray);



?>