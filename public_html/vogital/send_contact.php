<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../config.php'; // Adjust the path if necessary

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $subject = htmlspecialchars(strip_tags(trim($_POST['subject'])));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'mail.privateemail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = EMAIL_USERNAME;
        $mail->Password   = EMAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom(EMAIL_USERNAME, 'Vogital');
        $mail->addAddress(EMAIL_USERNAME); // You can specify a different email if needed
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(false);
        $mail->Subject = "New Contact Form Submission: $subject";
        $mail->Body    = "You have received a new message from $name.\n\n".
                         "Email: $email\n\nMessage:\n$message";

        // Send the email
        $mail->send();

        // Redirect to thank you page
        header('Location: thank_you.html');
        exit();
    } catch (Exception $e) {
        // Optionally log the error or display a generic message
        error_log("Mailer Error: {$mail->ErrorInfo}");
        echo "There was an error sending your message. Please try again later.";
    }
} else {
    // Redirect if accessed directly
    header('Location: contact.html');
    exit();
}
?>
