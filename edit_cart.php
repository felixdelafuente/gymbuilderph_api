<?php
// Include the database connection
include 'db.php';

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  // Get the input data from the request body
  $input = file_get_contents('php://input');
  // Decode the JSON data into an associative array
  $data = json_decode($input, true);

  // Validate the input data
  if (isset($data['cart_id']) && isset($data['quantity'])) {
    // Sanitize the input data
    $cart_id = filter_var($data['cart_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($data['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare an SQL statement to update a cart item by cart_id in the cart table
    $sql = "UPDATE cart SET quantity = :quantity WHERE cart_id = :cart_id";
    $stmt = $pdo->prepare($sql);

    // Bind the input data to the SQL statement parameters
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->bindParam(':quantity', $quantity);

    // Execute the SQL statement
    if ($stmt->execute()) {
      // Set the response status code to 200 OK
      http_response_code(200);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the updated cart item id and quantity into a JSON object
      echo json_encode(['cart_id' => $cart_id, 'quantity' => $quantity]);
    } else {
      // Set the response status code to 500 Internal Server Error
      http_response_code(500);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Failed to update cart item";
    }
  } else {
    // Set the response status code to 400 Bad Request
    http_response_code(400);
    // Set the response content type to plain text
    header('Content-Type: text/plain');
    // Echo an error message
    echo "Invalid input data";
  }
} else {
  // Set the response status code to 405 Method Not Allowed
  http_response_code(405);
  // Set the response content type to plain text
  header('Content-Type: text/plain');
  // Echo an error message
  echo "Only PUT method is allowed";
}
?>
