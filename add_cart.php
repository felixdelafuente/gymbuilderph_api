<?php
// Include the database connection
include 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the input data from the request body
  $input = file_get_contents('php://input'); 
  // Decode the JSON data into an associative array
  $data = json_decode($input, true);

  // Validate the input data
  if (isset($data['user_id']) && isset($data['product_id']) && isset($data['quantity'])) {
    // Sanitize the input data
    $user_id = filter_var($data['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $product_id = filter_var($data['product_id'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($data['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare an SQL statement to insert a new cart item into the cart table
    $sql = "INSERT INTO cart (user_id, product_id, quantity, date_added) VALUES (:user_id, :product_id, :quantity, NOW())";
    $stmt = $pdo->prepare($sql);

    // Bind the input data to the SQL statement parameters
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);

    // Execute the SQL statement
    if ($stmt->execute()) {
      // Set the response status code to 201 Created
      http_response_code(201);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the inserted cart item id and user id into a JSON object
      echo json_encode(['cart_id' => $pdo->lastInsertId(), 'user_id' => $user_id]);
    } else {
      // Set the response status code to 500 Internal Server Error
      http_response_code(500);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Failed to create cart item";
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
  echo "Only POST method is allowed";
}
?>
