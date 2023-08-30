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
  if (isset($data['id']) && isset($data['name']) && isset($data['description']) && isset($data['price']) && isset($data['image_link']) && isset($data['item_values'])) {
    // Sanitize the input data
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $image_link = filter_var($data['image_link'], FILTER_SANITIZE_URL);
    $item_values = filter_var($data['item_values'], FILTER_SANITIZE_STRING);

    // Prepare an SQL statement to update a product by id in the products table
    $sql = "UPDATE products SET name = :name, description = :description, price = :price, image_link = :image_link, item_values = :item_values WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind the input data to the SQL statement parameters
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image_link', $image_link);
    $stmt->bindParam(':item_values', $item_values);

    // Execute the SQL statement
    if ($stmt->execute()) {
      // Set the response status code to 200 OK
      http_response_code(200);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the updated product id and name into a JSON object
      echo json_encode(['id' => $id, 'name' => $name]);
    } else {
      // Set the response status code to 500 Internal Server Error
      http_response_code(500);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Failed to update product";
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
