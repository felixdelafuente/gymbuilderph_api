<?php
// Include the database connection
include 'db.php';

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  // Check if the id parameter is set in the query string
  if (isset($_GET['id'])) {
    // Sanitize the id parameter
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    // Prepare an SQL statement to delete a product by id from the products table
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    // Bind the id parameter to the SQL statement parameter
    $stmt->bindParam(':id', $id);
    // Execute the SQL statement
    if ($stmt->execute()) {
      // Set the response status code to 200 OK
      http_response_code(200);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the deleted product id into a JSON object
      echo json_encode(['id' => $id]);
    } else {
      // Set the response status code to 500 Internal Server Error
      http_response_code(500);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Failed to delete product";
    }
  } else {
    // Set the response status code to 400 Bad Request
    http_response_code(400);
    // Set the response content type to plain text
    header('Content-Type: text/plain');
    // Echo an error message
    echo "Missing id parameter";
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
