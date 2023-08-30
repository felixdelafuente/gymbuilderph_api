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
  if (isset($data['email']) && isset($data['password'])) {
    // Sanitize the input data
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);

    // Prepare an SQL statement to select a user by email from the users table
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    // Bind the email parameter to the SQL statement parameter
    $stmt->bindParam(':email', $email);
    // Execute the SQL statement
    $stmt->execute();
    // Fetch the user as an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and the password matches
    if ($user && password_verify($password, $user['password'])) {
      // Set the response status code to 200 OK
      http_response_code(200);
      // Set the response content type to JSON
      header('Content-Type: application/json');
      // Encode the user details into a JSON object
      echo json_encode($user);
    } else {
      // Set the response status code to 401 Unauthorized
      http_response_code(401);
      // Set the response content type to plain text
      header('Content-Type: text/plain');
      // Echo an error message
      echo "Invalid email or password";
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