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


// check if username already exists in db
$result = $access->getUser($username);

if ($result) {

    $returnArray["message"] = "Username already exists. Please choose another one!";
    $returnArray["status"] = "400";
    echo json_encode($returnArray);
    return;

}


// check if email address already exists in db
$result = $access->selectUserViaEmail($email);

if ($result) {

    $returnArray["message"] = "Email address already exists. Please choose another one!";
    $returnArray["status"] = "400";
    echo json_encode($returnArray);
    return;

}


// STEP 3. Insert user information
$result = $access->registerUser($username, $secured_password, $salt, $email, $fullname);

if ($result) {

    // got current registered user information
    $user = $access->selectUser($username);

    // Declare information to feedback to user of App as json
    $returnArray["status"] = "200";
    $returnArray["message"] = "Successfully registered";
    $returnArray["id"] = $user["id"];
    $returnArray["username"] = $user["username"];
    $returnArray["email"] = $user["email"];
    $returnArray["fullname"] = $user["fullname"];
    $returnArray["ava"] = $user["ava"];

    // STEP 4. Emailing
    // Include email.php
    require ("secure/email.php");

    // Store all class in $email var
    $email = new email();

    // Store generated token in $token var
    $token = $email->generateToken(20);

    // Safe information in 'emailTokens' table
    $access->saveToken("emailTokens", $user["id"], $token);

    // refer emailing information
    $details = array();
    $details["subject"] = "Email confirmation for TwitterBetter";
    $details["to"] = $user["email"];
    $details["fromName"] = "TwitterBetter Administrator";
    $details["fromEmail"] = "ios2test@gmail.com";

    // Access template file
    $template = $email->confirmationTemplate();
    $template = str_replace("{token}", $token, $template);

    $details["body"] = $template;

    $email->sendEmail($details);




} else {
    $returnArray["status"] = "400";
    $returnArray["message"] = "Could not register with provided information";

}

    // STEP 5. DISCONNECT
    $access->disconnect();

    // STEP 6. Json data
    echo json_encode($returnArray);








?>