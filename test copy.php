#!/usr/bin/php
<?php

$mydb = new mysqli('127.0.0.1','username','password', 'users');

if ($mydb->errno != 0)
{
	echo "failed to connect to database: ". $mydb->error . PHP_EOL;
	exit(0);
}

echo "YAY IT CONNECTED".PHP_EOL;

$query = "select * from users";

$response = $mydb->query($query);
if ($mydb->errno != 0)
{
	echo "failed to execute query:".PHP_EOL;
	echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
	exit(0);
}


?>