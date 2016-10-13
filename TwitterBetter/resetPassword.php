<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 13.10.16
 * Time: 22:21
 */

// STEP 1. Get information passed to this file
$email = htmlentities($_REQUEST["email"]);

if (empty($email)) {

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


// STEP 3. Check if email is registered in db
$user = $access->selectUserViaEmail($email);

if (empty($user)) {

    $returnArray["message"] = "Email not found";
    echo json_encode($returnArray);
    return;

}


// STEP 4. Emailing
// Include email.php
require ("secure/email.php");

// Store all class in $email var
$email = new email();

// Store generated token in $token var
$token = $email->generateToken(20);

// Store unique token in db
$access->saveToken("passwordTokens", $user["id"], $token);

// Prepare email message
$details = array();
$details["subject"] = "Password reset request for TwitterBetter";
$details["to"] = $user["email"];
$details["fromName"] = "TwitterBetter Administrator";
$details["fromEmail"] = "jepp.bone@gmail.com";

// Load HTML template
$template = $email->resetPasswordTemplate();
$template = str_replace("{token}", $token, $template);
$details["body"] = $template;

// Send email to user
$email->sendEmail($details);


// STEP 5. Return message to mobile app
$returnArray["email"] = $user["email"];
$returnArray["message"] = "We have sent you an email to reset your password";
echo json_encode($returnArray);


// STEP 6. Close connection
$access->disconnect();







?>