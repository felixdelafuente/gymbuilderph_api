<?php

// Include the database connection code
include 'db.php';

// Get the user_id parameter from the request
$user_id = $_GET['user_id'];

// Prepare a SQL statement to join the orderitems and orders tables by order_id and filter by user_id
$sql = "SELECT orderitems.*, orders.* FROM orderitems INNER JOIN orders ON orderitems.order_id = orders.order_id WHERE orders.user_id = :user_id";

// Execute the statement with the user_id parameter
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);

// Fetch all the results as an associative array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Encode the results as a JSON string and send it as a response
header('Content-Type: application/json');
echo json_encode($results);
?>
