<?php

// Include the database connection code
include 'db.php';

// Define a secret key for encryption and decryption
$key = 'secret key';

// Get the request method from the server
$method = $_SERVER['REQUEST_METHOD'];

// Get the user_id parameter from the request if any
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Prepare a SQL statement based on the request method
switch ($method) {
  case 'GET':
    // If user_id is given, get the user data by user_id
    if ($user_id) {
      $sql = "SELECT * FROM users WHERE user_id = :user_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['user_id' => $user_id]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      // Decrypt the password using openssl_decrypt()
      $user['password'] = openssl_decrypt($user['password'], 'AES-128-ECB', $key);
    } else {
      // Otherwise, get all users data
      $sql = "SELECT * FROM users";
      $stmt = $pdo->query($sql);
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      // Decrypt the passwords using openssl_decrypt()
      foreach ($users as &$user) {
        $user['password'] = openssl_decrypt($user['password'], 'AES-128-ECB', $key);
      }
    }
    break;
  case 'PUT':
    // Parse the input data from php://input
    parse_str(file_get_contents('php://input'), $input);
    // Encrypt the password using openssl_encrypt()
    $input['password'] = openssl_encrypt($input['password'], 'AES-128-ECB', $key);
    // Update the user data by user_id
    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, password = :password WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($input);
    break;
  case 'DELETE':
    // Delete the user data by user_id
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    break;
}

// Set the header to JSON content type
header('Content-Type: application/json');

// Encode the data as a JSON string and send it as a response
if ($method == 'GET') {
  if ($user_id) {
    echo json_encode($user); // Send the user data for the given user_id
  } else {
    echo json_encode($users); // Send all users data
  }
} else {
  echo json_encode(['status' => 'success']); // Send a success message for other methods
}
?>
