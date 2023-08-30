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
  if (isset($data['first_name']) && isset($data['last_name']) && isset($data['email']) && isset($data['password']) && isset($data['admin']) && isset($data['verified'])) {
    // Sanitize the input data
    $first_name = filter_var($data['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($data['last_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    $admin = filter_var($data['admin'], FILTER_VALIDATE_BOOLEAN);
    $verified = filter_var($data['verified'], FILTER_VALIDATE_BOOLEAN);

    // Hash the password using password_hash function
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement to insert a new user into the users table
    $sql = "INSERT INTO users (first_name, last_name, email, password, admin, verified) VALUES (:first_name, :last_name, :email, :password, :admin, :verified)";
    $stmt = $pdo->prepare($sql);

    // Bind the input data to the SQL statement parameters
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
    $stmt->bindParam(':verified', $verified, PDO::PARAM_INT);

    // Execute the SQL statement
    if ($stmt->execute()) {
      // Set the response status code to 201 Created
      http_response_code(201);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the inserted user id and email into a JSON object
      echo json_encode(['user_id' => $pdo->lastInsertId(), 'email' => $email]);
    } else {
      // Set the response status code to 500 Internal Server Error
      http_response_code(500);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Failed to create user";
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
