<?php

$db_host = 'localhost';
$username = 'username';
$password = 'password';
$servername = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ' ';

$email_address='something@something.com';

try {
  $conn = new PDO('mysql:host='.$db_host.';dbname=databaseName', $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo 'Connected successfully';
} catch(PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
}
