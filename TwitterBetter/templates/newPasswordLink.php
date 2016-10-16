<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 16.10.16
 * Time: 22:52
 */









?>


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
        <!--Forms-->
        <form method="POST" action="<?php $_SERVER['PHP_SELF'];?>">
        <div><input type="password" name="password_1" placeholder="New password" class="password_field"/></div>
        <div><input type="password" name="password_2" placeholder="Repeat password" class="password_field"/></div>
        <div><input type="submit" value="Save" class="button"/></div>
        <input type="hidden" value="<?php echo $_GET['token'];?>" name="token">
        </form>

        </body>


</html>
