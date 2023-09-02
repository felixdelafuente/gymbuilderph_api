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


// // Include the database connection code
// include 'db.php';

// // Get the user_id from the query string if any
// $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// // Check if the user_id is valid
// if ($user_id) {
//   // Prepare the SQL statement for selecting from orderitems and orders tables using a join
//   $sql = "SELECT i.*, o.* FROM orderitems i JOIN orders o ON i.order_id = o.order_id WHERE o.user_id = :user_id";
//   $stmt = $pdo->prepare($sql);
//   // Bind the parameter
//   $stmt->bindParam(':user_id', $user_id);
//   // Execute the statement
//   $stmt->execute();

//   // Fetch all the data as an associative array
//   $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

//   // Check if the data is not empty
//   if ($data) {
//     // Return a success message and the data as JSON
//     http_response_code(200); // OK
//     echo json_encode(array("message" => "Data retrieved successfully", "data" => $data));
//   } else {
//     // Return an error message
//     http_response_code(404); // Not found
//     echo json_encode(array("message" => "No data found for this user"));
//   }
// } else {
//   // Invalid user_id, return an error message
//   http_response_code(400); // Bad request
//   echo json_encode(array("message" => "Invalid user_id"));
// }
?>
