<?php
// Include the database connection code
include 'db.php';

// Get the order_id parameter from the request
$order_id = $_GET['order_id'];

// Prepare a SQL statement to delete the data from the orders table by order_id
$sql1 = "DELETE FROM orders WHERE order_id = :order_id";

// Execute the statement with the order_id parameter
$stmt1 = $pdo->prepare($sql1);
$stmt1->execute(['order_id' => $order_id]);

// Prepare a SQL statement to delete the data from the orderitems table by order_id
$sql2 = "DELETE FROM orderitems WHERE order_id = :order_id";

// Execute the statement with the order_id parameter
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute(['order_id' => $order_id]);

// Set the header to JSON content type
header('Content-Type: application/json');

// Encode a success message as a JSON string and send it as a response
echo json_encode(['status' => 'success']);
?>
