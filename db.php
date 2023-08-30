<?php
// Define database parameters
$host = "localhost"; // Replace with your host name
$dbname = "gym_builder"; // Replace with your database name
$username = "root"; // Replace with your user name
$password = ""; // Replace with your password

// Create a PDO instance
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  // Set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
