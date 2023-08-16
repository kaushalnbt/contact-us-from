<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$fullName = $_POST['fullName'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

if (empty($fullName) || empty($phoneNumber) || empty($email) || empty($subject) || empty($message)) {
    die("All fields are mandatory");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_form";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ipAddress = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');

$sql = "INSERT INTO contactus_form (full_name, phone_number, email, subject, message, ip_address, submission_time)
        VALUES ('$fullName', '$phoneNumber', '$email', '$subject', '$message', '$ipAddress', '$timestamp')";

if ($conn->query($sql) === TRUE) {
    try {
        $phpmailer = new PHPMailer(true);
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'bf5b66537bc539';
        $phpmailer->Password = '71d89886a2d926';
        // $phpmailer->SMTPDebug = SMTP::DEBUG_SERVER;
        
        $phpmailer->setFrom($email, $fullName);
        $phpmailer->addAddress('test@techsolvitservice.com');
        $phpmailer->Subject = 'New Contact Form Submission';
        $phpmailer->Body = "A new contact form submission has been received:\n\n" .
                            "Full Name: $fullName\n" .
                            "Phone Number: $phoneNumber\n" .
                            "Email: $email\n" .
                            "Subject: $subject\n" .
                            "Message: $message\n" .
                            "IP Address: $ipAddress\n" .
                            "Submission Time: $timestamp\n";
        
        $phpmailer->send();
        echo "Form submitted successfully!";
    } catch (Exception $e) {
        echo "Email sending failed: " . $phpmailer->ErrorInfo;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
