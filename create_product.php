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
  if (isset($data['name']) && isset($data['description']) && isset($data['price']) && isset($data['image_link']) && isset($data['item_values'])) {
    // Sanitize the input data
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $image_link = filter_var($data['image_link'], FILTER_SANITIZE_URL);
    $item_values = filter_var($data['item_values'], FILTER_SANITIZE_STRING);

    // Prepare an SQL statement to insert a new product into the products table
    $sql = "INSERT INTO products (name, description, price, image_link, item_values) VALUES (:name, :description, :price, :image_link, :item_values)";
    $stmt = $pdo->prepare($sql);

    // Bind the input data to the SQL statement parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':image_link', $image_link);
    $stmt->bindParam(':item_values', $item_values);

    // Execute the SQL statement
    if ($stmt->execute()) {
      // Set the response status code to 201 Created
      http_response_code(201);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the inserted product id and name into a JSON object
      echo json_encode(['id' => $pdo->lastInsertId(), 'name' => $name]);
    } else {
      // Set the response status code to 500 Internal Server Error
      http_response_code(500);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Failed to create product";
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
