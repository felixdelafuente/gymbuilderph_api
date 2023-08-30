<?php
// Include the database connection
include 'db.php';

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Check if the product_id parameter is set in the query string
  if (isset($_GET['product_id'])) {
    // Sanitize the product_id parameter
    $id = filter_var($_GET['product_id'], FILTER_SANITIZE_NUMBER_INT);
    // Prepare an SQL statement to select a product by product_id from the products table
    $sql = "SELECT * FROM products WHERE product_id = :id";
    $stmt = $pdo->prepare($sql);
    // Bind the product_id parameter to the SQL statement parameter
    $stmt->bindParam(':id', $id);
    // Execute the SQL statement
    $stmt->execute();
    // Fetch the product as an associative array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists
    if ($product) {
      // Set the response status code to 200 OK
      http_response_code(200);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the product into a JSON object
      echo json_encode($product);
    } else {
      // Set the response status code to 404 Not Found
      http_response_code(404);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Product not found";
    }
  } else {
    // Prepare an SQL statement to select all products from the products table
    $sql = "SELECT * FROM products";
    $stmt = $pdo->prepare($sql);
    // Execute the SQL statement
    $stmt->execute();
    // Fetch all products as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Set the response status code to 200 OK
    http_response_code(200);
    // Set the response content type to JSON
    header('Content-Type: application/json');
    // Encode the products into a JSON array
    echo json_encode($products);
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
