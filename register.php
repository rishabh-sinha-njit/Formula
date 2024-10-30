#!/usr/bin/php
<?php if (isset($_POST['register'])) {

    header('index.html?status=success');
    $mysqli = new mysqli("127.0.0.1", "username", "password", "users");
    if ($mysqli->connect_error)
    {
        die("you did not connect. " . $mysqli->connect_error);
    }
    else
    {
        echo "connected";
    }

    $username= $_POST['username'];
    $password= $_POST['password'];
    $email= $_POST['email'];
    $stmt= $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);
    $password = password_hash($password, PASSWORD_DEFAULT);
    

    if ($stmt->execute())
    {
        echo "here is your new account!";
    }
    else
    {
        echo "oops, there is an error:" . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}

?>

