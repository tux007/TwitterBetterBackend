<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 16.10.16
 * Time: 22:52
 */

// SECOND LOAD OF PAGE

// STEP 1. Check passed information
if (!empty($_POST["password_1"]) && !empty($_POST["password_2"]) && !empty($_POST["token"])) {

    $password_1 = htmlentities($_POST["password_1"]);
    $password_2 = htmlentities($_POST["password_2"]);
    $token = htmlentities($_POST["token"]);

    // STEP 2. Do passwords match or not
    if ($password_1 == $password_2) {

        // STEP 3. Build connection
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

        // STEP 4. Get user ID via user token
        $user = $access->getUserID("passwordTokens", $token);

        // STEP 5. Update 'passwordToken' table in db
        if (!empty($user)) {

            // 5.1 Generate secured password
            $salt = openssl_random_pseudo_bytes(20);
            $secured_password = sha1($password_1 . $salt);

            // 5.2 Update user password
            $result = $access->updatePassword($user["id"], $secured_password, $salt);

            if ($result) {

                // 5.3 Delete unique token
                $access->deleteToken("passwordTokens", $token);
                $message = "Successfully created your new password!";

                header("Location:didResetPassword.php?message=" . $message);


            } else {

                echo 'User ID is empty';

            }

        }

    } else {

        $message = "Passwords do not match";

    }
}

?>

<!--FIRST LOAD OF PAGE-->

<html>
        <head>
            <!--Title-->
            <title>Create new password</title>

            <!--CSS style-->
            <style>

                .password_field {
                    margin: 10px;
                }

                .button {
                    margin: 10px;
                }


            </style>

        </head>


        <body>
            <h1>Create new password</h1>

            <?php
            if (!empty($message)) {
                echo "<br>" . $message . "<br>";
            }

            ?>


        <!--Forms-->
        <form method="POST" action="<?php $_SERVER['PHP_SELF'];?>">
        <div><input type="password" name="password_1" placeholder="New password" class="password_field"/></div>
        <div><input type="password" name="password_2" placeholder="Repeat password" class="password_field"/></div>
        <div><input type="submit" value="Save" class="button"/></div>
        <input type="hidden" value="<?php echo $_GET['token'];?>" name="token">
        </form>

        </body>


</html>
