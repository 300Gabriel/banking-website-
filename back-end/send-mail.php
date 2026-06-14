<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../vendor/autoload.php";

function sendMail($to, $name, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;

        $mail->Username = "yourgmail@gmail.com";

        $mail->Password = "qgbz bkjc aeib bixc";

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom(
            "yourgmail@gmail.com",
            "TrustBank"
        );

        $mail->addAddress($to, $name);

        $mail->isHTML(true);

        $mail->Subject = $subject;

        $mail->Body = $message;

        $mail->send();

        return true;
    } catch (Exception $e) {

        return false;
    }
}
function mailer($to, $name, $subject, $message)
{
    if (sendMail($to, $name, $subject, $message)) {
        echo "Email sent successfully";
    } else {
        echo "Failed to send email";
    }
}
function mailer_mail($to, $name, $subject, $message)
{
    if (sendMail($to, $name, $subject, $message)) {
        return true;
    } else {
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>