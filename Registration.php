<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';
require 'path/to/database_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $verificationToken = bin2hex(random_bytes(32)); // A verification token 
    
    // Check whether the username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already exists";
    } else {
        // Insert new user information into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, verified, token) VALUES (?, ?, ?, 0, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $verificationToken);
        $stmt->execute();

        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $conn->insert_id; // Capture the last inserted ID

        // Send email verification
        $mail = new PHPMailer(true);
        try {
            // SMTP server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Set the SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com';
            $mail->Password = 'your-email-password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your-email@example.com', 'Mailer');
            $mail->addAddress($email);

            $verificationLink = "http://yourwebsite.com/verify.php?token=" . $verificationToken;
            $mail->isHTML(true);
            $mail->Subject = 'Account Verification';
            $mail->Body    = 'Click this link to verify your account: <a href="' . $verificationLink . '">Verify Now</a>';
            $mail->send();

            echo 'Registration successful! Please check your email to verify your account.';
            header('Location: recalls5.html'); // Redirect to recalls5.html
            exit;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
$conn->close();
?>
