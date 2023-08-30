<?php
// Include the database connection
include 'db.php';

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  // Check if the cart_id parameter is set in the query string
  if (isset($_GET['cart_id'])) {
    // Sanitize the cart_id parameter
    $cart_id = filter_var($_GET['cart_id'], FILTER_SANITIZE_NUMBER_INT);
    // Prepare an SQL statement to delete a cart item by cart_id from the cart table
    $sql = "DELETE FROM cart WHERE cart_id = :cart_id";
    $stmt = $pdo->prepare($sql);
    // Bind the cart_id parameter to the SQL statement parameter
    $stmt->bindParam(':cart_id', $cart_id);
    // Execute the SQL statement
    if ($stmt->execute()) {
      // Set the response status code to 200 OK
      http_response_code(200);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the deleted cart item id into a JSON object
      echo json_encode(['cart_id' => $cart_id]);
    } else {
      // Set the response status code to 500 Internal Server Error
      http_response_code(500);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Failed to delete cart item";
    }
  } else {
    // Set the response status code to 400 Bad Request
    http_response_code(400);
    // Set the response content type to plain text
    header('Content-Type: text/plain');
    // Echo an error message
    echo "Missing cart_id parameter";
  }
} else {
  // Set the response status code to 405 Method Not Allowed
  http_response_code(405);
  // Set the response content type to plain text
  header('Content-Type: text/plain');
  // Echo an error message
  echo "Only DELETE method is allowed";
}
?>
