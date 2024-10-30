#!/user/bin/php
<?php if (isset($_POST['login'])) {

$mysqli = new mysqli("127.0.0.1", "username", "password", "users");
if ($mysqli->connect_error)
{
	die("you did not connect. " . $mysqli->connect_error);
}

$username= $_POST['username'];
$password= $_POST['password'];

$st= $mysqli->prepare("select id, password from users where username = ?");
$st->bind_param("s", $username);

$st->execute();
$st->store_result();





$st->close();
$mysqli->close();
}




<form action="login.php" method="post">
    <label for="username">username:</label>
    <input id="username" name="username" required="" type=text" />
    <label for="password">password:</label>
    <input id="password" name="password" required="" type="password"/>
    <input name="login" type="submit" value="Login"/>
</form>