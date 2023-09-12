<?php
// Include the database connection code
include 'db.php';

// Prepare a SQL statement to get all the data from the orderitems table
$sql = "SELECT * FROM orderitems";

// Execute the statement and fetch the data as an associative array
$stmt = $pdo->query($sql);
$orderitems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the header to JSON content type
header('Content-Type: application/json');

// Encode the data as a JSON string and send it as a response
echo json_encode($orderitems);
?>
