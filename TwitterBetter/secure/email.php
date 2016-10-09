<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 09.10.16
 * Time: 23:55
 */

class email {

    // Generate unique token for user when he receives confirmation email
    function generateToken($length) {

        $characters = "qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM1234567890";

        $charactersLength = strlen($characters);


        $token = '';

        // Generate random char from $characters
        for ($i = 0; $i < $charactersLength; $i++) {
            $token .= $characters[rand(0, $charactersLength-1)];

        }

        return $token;

    }

    // Open confirmation template
    function confirmationTemplate() {

        $file = fopen("templates/confirmationTemplate.html", "r") or die("Unable to open file");

        // store content of file in $template var
        $template = fread($file, filesize("templates/confirmationTemplate.html"));

        fclose($file);

        return $template;

    }

    // Send email
    function sendEmail($details) {

        // Email information
        $subject = $details["subject"];
        $to = $details["to"];
        $fromName = $details["fromName"];
        $fromEmail = $details["fromEmail"];
        $body = $details["body"];

        // header required by some smtp or mail sites
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;content=UTF-8" . "\r\n";
        $headers .= "From: " . $fromName . " <" . $fromEmail . ">" . "\r\n";

        // php func to send email
        mail($to, $subject, $body, $headers);

    }
}

?>