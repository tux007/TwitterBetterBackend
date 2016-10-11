<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 09.10.16
 * Time: 23:49
 */


// STEP 1. Check required information
$token = htmlentities($_GET["token"]);

if (empty($token)) {
    echo 'Missing required information';
}

// STEP 2. Build connection
// Secure way to build connection

$file = parse_ini_file("../../../../TwitterBetter.ini");

// Store in php variable information from ini variables
$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);

// include access.php to call func from access.php file
require ("../secure/access.php");
$access = new access($host, $user, $pass, $name);
$access->connect();

// STEP 3. Get id of user
// Store the result of function in var $id
$id = $access->getUserID("emailTokens", $token);

if (empty($id["id"])) {
    echo 'User with this token is not found.';
    return;
}


// STEP 4. Change status of email confirmation & delete token
$result = $access->emailConfirmationStatus(1, $id["id"]);

if ($result) {

    // STEP 4.1. Delete token from 'emailTokens'
    $access->deleteToken("emailTokens", $token);
    echo 'Thank You! Your email is now confirmed.';

}

// STEP 5. Close connection
$access->disconnect();


?>