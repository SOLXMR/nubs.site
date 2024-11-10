<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $name = htmlspecialchars(trim($_POST["name"]));
  $email = htmlspecialchars(trim($_POST["email"]));
  $phone = htmlspecialchars(trim($_POST["phone"]));
  $message = htmlspecialchars(trim($_POST["message"]));

  $to = "your-email@example.com"; // Replace with your email address
  $subject = "New Contact Form Submission";
  $body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
  $headers = "From: $email";

  if(mail($to, $subject, $body, $headers)){
    echo "<script>alert('Message sent successfully!'); window.location.href='contact.html';</script>";
  } else {
    echo "<script>alert('Failed to send message. Please try again.'); window.location.href='contact.html';</script>";
  }
}
?>
