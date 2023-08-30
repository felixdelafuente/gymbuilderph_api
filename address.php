<?php
// Include the database connection code
include 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Switch based on the request method
switch ($method) {
  case 'GET':
    // Handle the get request
    // Get the user_id from the query string if any
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

    // Prepare the SQL statement for selecting from the table
    if ($user_id) {
      // Select all addresses that match the user_id
      $sql = "SELECT * FROM addresses WHERE user_id = :user_id";
      $stmt = $pdo->prepare($sql);
      // Bind the parameter
      $stmt->bindParam(':user_id', $user_id);
    } else {
      // Select all addresses
      $sql = "SELECT * FROM addresses";
      $stmt = $pdo->prepare($sql);
    }

    // Execute the statement
    $stmt->execute();

    // Fetch the data as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the data is not empty
    if ($data) {
      // Set the Content-Type header to application/json
      header ('Content-Type: application/json');
      // Return the data as JSON using json_encode()
      echo json_encode($data);
    } else {
      // Return an error message
      http_response_code(404); // Not found
      echo json_encode(array("message" => "No addresses found"));
    }
    break;

  case 'POST':
    // Handle the post request
    // Get the data from the request body
    $data = json_decode(file_get_contents("php://input"));

    // Check if the data is valid
    if (isset($data->user_id) && isset($data->address_line1) && isset($data->city) && isset($data->country) && isset($data->postal_code) && isset($data->phone_number)) {
      // Prepare the SQL statement for inserting into the table
      // Note: address_id and address_line2 are not included in the values as they are optional or auto-incremented by the database
      $sql = "INSERT INTO addresses (user_id, address_line1, address_line2, city, country, postal_code, phone_number) VALUES (:user_id, :address_line1, :address_line2, :city, :country, :postal_code, :phone_number)";
      $stmt = $pdo->prepare($sql);
      // Bind the parameters
      $stmt->bindParam(':user_id', $data->user_id);
      $stmt->bindParam(':address_line1', $data->address_line1);
      $stmt->bindParam(':address_line2', $data->address_line2); // This can be null if not provided
      $stmt->bindParam(':city', $data->city);
      $stmt->bindParam(':country', $data->country);
      $stmt->bindParam(':postal_code', $data->postal_code);
      $stmt->bindParam(':phone_number', $data->phone_number);
      // Execute the statement
      $stmt->execute();

      // Get the last inserted address_id from the database
      $address_id = $pdo->lastInsertId();

      // Return a success message and the address_id as JSON
      http_response_code(201); // Created
      echo json_encode(array("message" => "Address created successfully", "address_id" => $address_id));
      
    } else {
      // Invalid data, return an error message
      http_response_code(400); // Bad request
      echo json_encode(array("message" => "Invalid address data"));
    }
    break;

  case 'PUT':
    // Handle the put request
    // Get the data from the request body
    $data = json_decode(file_get_contents("php://input"));

    // Check if the data is valid and has a user_id to update
    if (isset($data->user_id) && (isset($data->address_line1) || isset($data->address_line2) || isset($data->city) || isset($data->country) || isset($data->postal_code) || isset($data->phone_number))) {
      
      // Prepare the SQL statement for updating the table with dynamic parameters based on the data provided
      
      // Start with the update clause and the table name
      $sql = "UPDATE addresses SET ";

      // Initialize an array to store the parameters and their values
      $params = array();

      // Loop through each property of the data object and append to the SQL statement and the params array
      foreach ($data as $key => $value) {
        // Append the column name and a placeholder to the SQL statement
        $sql .= "$key = :$key, ";
        // Add the parameter and its value to the params array
        $params[":$key"] = $value;
      }

      // Remove the trailing comma and space from the SQL statement
      $sql = rtrim($sql, ", ");

      // Append the where clause and the user_id parameter to the SQL statement and the params array
      $sql .= " WHERE user_id = :user_id";
      $params[':user_id'] = $data->user_id;

      // Prepare the statement with the SQL statement
      $stmt = $pdo->prepare($sql);

      // Execute the statement with the params array
      $stmt->execute($params);

      // Check if any row was affected by the update
      if ($stmt->rowCount() > 0) {
        // Return a success message
        http_response_code(200); // OK
        echo json_encode(array("message" => "Address updated successfully"));
      } else {
        // Return an error message
        http_response_code(404); // Not found
        echo json_encode(array("message" => "No address found or no changes made"));
      }
      
    } else {
      // Invalid data, return an error message
      http_response_code(400); // Bad request
      echo json_encode(array("message" => "Invalid address data or missing user_id"));
    }
    break;

  default:
    // Handle other request methods
    // Return an error message
    http_response_code(405); // Method not allowed
    echo json_encode(array("message" => "Method not supported"));
}
?>
