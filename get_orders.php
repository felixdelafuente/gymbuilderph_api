<?php

// Include the database connection code
include 'db.php';

// Prepare a SQL statement to join the orderitems and orders tables by order_id
$sql = "SELECT orderitems.*, orders.* FROM orderitems INNER JOIN orders ON orderitems.order_id = orders.order_id";

// Execute the statement without any parameters
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all the results as an associative array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Encode the results as a JSON string and send it as a response
header('Content-Type: application/json');
echo json_encode($results);
?>