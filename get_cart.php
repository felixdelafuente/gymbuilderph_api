<?php
// Include the database connection
include 'db.php';

// Add ?user_id=1 and change 1 to the input of the specific user_id

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Check if the user_id parameter is set in the query string
  if (isset($_GET['user_id'])) {
    // Sanitize the user_id parameter
    $user_id = filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);
    // Prepare an SQL statement to select all cart items by user_id from the cart table
    $sql = "SELECT * FROM cart WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    // Bind the user_id parameter to the SQL statement parameter
    $stmt->bindParam(':user_id', $user_id);
    // Execute the SQL statement
    $stmt->execute();
    // Fetch all cart items as an associative array
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Set the response status code to 200 OK
    http_response_code(200);
    // Set the response content type to JSON
    header('Content-Type: application/json');
    // Encode the cart items into a JSON array
    echo json_encode($cart_items);
  } else {
    // Set the response status code to 400 Bad Request
    http_response_code(400);
    // Set the response content type to plain text
    header('Content-Type: text/plain');
    // Echo an error message
    echo "Missing user_id parameter";
  }
} else {
  // Set the response status code to 405 Method Not Allowed
  http_response_code(405);
  // Set the response content type to plain text
  header('Content-Type: text/plain');
  // Echo an error message
  echo "Only GET method is allowed";
}
?>
