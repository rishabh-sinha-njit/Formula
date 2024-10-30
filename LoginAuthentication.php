<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_database"); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) { // Use $hashedPassword
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $userId;
            header('Location: chat1.php');
            exit;
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href = 'login1.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with that username. Please try again.'); window.location.href = 'login1.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
