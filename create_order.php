<?php

// Include the database connection code
include 'db.php';

// Get the data from the request body
$data = json_decode(file_get_contents("php://input"));

// Check if the data is valid
if (isset($data->user_id) && isset($data->address_id) && isset($data->order_date) && isset($data->total_amount) && isset($data->status) && isset($data->delivery_status) && isset($data->orderitems)) {
  // Prepare the SQL statement for inserting into orders table
  // Note: order_id is not included in the values as it is auto-incremented by the database
  $sql = "INSERT INTO orders (user_id, address_id, order_date, total_amount, status, delivery_status) VALUES (:user_id, :address_id, :order_date, :total_amount, :status, :delivery_status)";
  $stmt = $pdo->prepare($sql);
  // Bind the parameters
  $stmt->bindParam(':user_id', $data->user_id);
  $stmt->bindParam(':address_id', $data->address_id);
  $stmt->bindParam(':order_date', $data->order_date);
  $stmt->bindParam(':total_amount', $data->total_amount);
  $stmt->bindParam(':status', $data->status);
  $stmt->bindParam(':delivery_status', $data->delivery_status);
  // Execute the statement
  $stmt->execute();

  // Get the last inserted order_id from the database
  $order_id = $pdo->lastInsertId();

  // Loop through the orderitems array
  foreach ($data->orderitems as $item) {
    // Check if the item has valid data
    if (isset($item->product_id) && isset($item->quantity) && isset($item->price)) {
      // Prepare the SQL statement for inserting into orderitems table
      // Note: order_item_id is not included in the values as it is auto-incremented by the database
      $sql = "INSERT INTO orderitems (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
      $stmt = $pdo->prepare($sql);
      // Bind the parameters
      $stmt->bindParam(':order_id', $order_id); // Use the same order_id as the parent order
      $stmt->bindParam(':product_id', $item->product_id);
      $stmt->bindParam(':quantity', $item->quantity);
      $stmt->bindParam(':price', $item->price);
      // Execute the statement
      $stmt->execute();
    } else {
      // Invalid item data, return an error message
      http_response_code(400); // Bad request
      echo json_encode(array("message" => "Invalid item data"));
      exit();
    }
  }

  // All data inserted successfully, return a success message
  http_response_code(201); // Created
  echo json_encode(array("message" => "Order created successfully"));

} else {
  // Invalid order data, return an error message
  http_response_code(400); // Bad request
  echo json_encode(array("message" => "Invalid order data"));
}




// // Include the database connection code
// include 'db.php';

// // Get the data from the request body
// $data = json_decode(file_get_contents("php://input"));

// // Check if the data is valid
// if (isset($data->order_id) && isset($data->user_id) && isset($data->address_id) && isset($data->order_date) && isset($data->total_amount) && isset($data->status) && isset($data->delivery_status) && isset($data->orderitems)) {
//   // Prepare the SQL statement for inserting into orders table
//   $sql = "INSERT INTO orders (order_id, user_id, address_id, order_date, total_amount, status, delivery_status) VALUES (:order_id, :user_id, :address_id, :order_date, :total_amount, :status, :delivery_status)";
//   $stmt = $pdo->prepare($sql);
//   // Bind the parameters
//   $stmt->bindParam(':order_id', $data->order_id);
//   $stmt->bindParam(':user_id', $data->user_id);
//   $stmt->bindParam(':address_id', $data->address_id);
//   $stmt->bindParam(':order_date', $data->order_date);
//   $stmt->bindParam(':total_amount', $data->total_amount);
//   $stmt->bindParam(':status', $data->status);
//   $stmt->bindParam(':delivery_status', $data->delivery_status);
//   // Execute the statement
//   $stmt->execute();

//   // Loop through the orderitems array
//   foreach ($data->orderitems as $item) {
//     // Check if the item has valid data
//     if (isset($item->order_item_id) && isset($item->product_id) && isset($item->quantity) && isset($item->price)) {
//       // Prepare the SQL statement for inserting into orderitems table
//       $sql = "INSERT INTO orderitems (order_item_id, order_id, product_id, quantity, price) VALUES (:order_item_id, :order_id, :product_id, :quantity, :price)";
//       $stmt = $pdo->prepare($sql);
//       // Bind the parameters
//       $stmt->bindParam(':order_item_id', $item->order_item_id);
//       $stmt->bindParam(':order_id', $data->order_id); // Use the same order_id as the parent order
//       $stmt->bindParam(':product_id', $item->product_id);
//       $stmt->bindParam(':quantity', $item->quantity);
//       $stmt->bindParam(':price', $item->price);
//       // Execute the statement
//       $stmt->execute();
//     } else {
//       // Invalid item data, return an error message
//       http_response_code(400); // Bad request
//       echo json_encode(array("message" => "Invalid item data"));
//       exit();
//     }
//   }

//   // All data inserted successfully, return a success message
//   http_response_code(201); // Created
//   echo json_encode(array("message" => "Order created successfully"));

// } else {
//   // Invalid order data, return an error message
//   http_response_code(400); // Bad request
//   echo json_encode(array("message" => "Invalid order data"));
// }





// // Include the database connection code
// include 'db.php';

// // Get the data from the request body
// $data = json_decode(file_get_contents("php://input"));

// // Check if the data is valid
// if (isset($data->order_id) && isset($data->user_id) && isset($data->address_id) && isset($data->order_date) && isset($data->total_amount) && isset($data->status) && isset($data->delivery_status) && isset($data->orderitems)) {
//   // Prepare the SQL statement for inserting into orders table
//   $sql = "INSERT INTO orders (order_id, user_id, address_id, order_date, total_amount, status, delivery_status) VALUES (:order_id, :user_id, :address_id, :order_date, :total_amount, :status, :delivery_status)";
//   $stmt = $pdo->prepare($sql);
//   // Bind the parameters
//   $stmt->bindParam(':order_id', $data->order_id);
//   $stmt->bindParam(':user_id', $data->user_id);
//   $stmt->bindParam(':address_id', $data->address_id);
//   $stmt->bindParam(':order_date', $data->order_date);
//   $stmt->bindParam(':total_amount', $data->total_amount);
//   $stmt->bindParam(':status', $data->status);
//   $stmt->bindParam(':delivery_status', $data->delivery_status);
//   // Execute the statement
//   $stmt->execute();

//   // Loop through the orderitems array
//   foreach ($data->orderitems as $item) {
//     // Check if the item has valid data
//     if (isset($item->order_item_id) && isset($item->product_id) && isset($item->quantity) && isset($item->price)) {
//       // Prepare the SQL statement for inserting into orderitems table
//       $sql = "INSERT INTO orderitems (order_item_id, order_id, product_id, quantity, price) VALUES (:order_item_id, :order_id, :product_id, :quantity, :price)";
//       $stmt = $pdo->prepare($sql);
//       // Bind the parameters
//       $stmt->bindParam(':order_item_id', $item->order_item_id);
//       $stmt->bindParam(':order_id', $data->order_id); // Use the same order_id as the parent order
//       $stmt->bindParam(':product_id', $item->product_id);
//       $stmt->bindParam(':quantity', $item->quantity);
//       $stmt->bindParam(':price', $item->price);
//       // Execute the statement
//       $stmt->execute();
//     } else {
//       // Invalid item data, return an error message
//       http_response_code(400); // Bad request
//       echo json_encode(array("message" => "Invalid item data"));
//       exit();
//     }
//   }

//   // All data inserted successfully, return a success message
//   http_response_code(201); // Created
//   echo json_encode(array("message" => "Order created successfully"));

// } else {
//   // Invalid order data, return an error message
//   http_response_code(400); // Bad request
//   echo json_encode(array("message" => "Invalid order data"));
// }



// // Include the database connection
// include 'db.php';

// // Check if the request method is POST
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//   // Get the input data from the request body
//   $input = file_get_contents('php://input');
//   // Decode the JSON data into an associative array
//   $data = json_decode($input, true);

//   // Validate the input data
//   if (isset($data['user_id']) && isset($data['address_id']) && isset($data['order_date']) && isset($data['total_amount']) && isset($data['status']) && isset($data['delivery_status']) && isset($data['orderitems']) && is_array($data['orderitems'])) {
//     // Sanitize the input data
//     $user_id = filter_var($data['user_id'], FILTER_SANITIZE_NUMBER_INT);
//     $address_id = filter_var($data['address_id'], FILTER_SANITIZE_NUMBER_INT);
//     $order_date = filter_var($data['order_date'], FILTER_SANITIZE_STRING);
//     $total_amount = filter_var($data['total_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
//     $status = filter_var($data['status'], FILTER_SANITIZE_STRING);
//     $delivery_status = filter_var($data['delivery_status'], FILTER_SANITIZE_STRING);
//     $orderitems = array_map(function ($item) {
//       return [
//         'product_id' => filter_var($item['product_id'], FILTER_SANITIZE_NUMBER_INT),
//         'quantity' => filter_var($item['quantity'], FILTER_SANITIZE_NUMBER_INT),
//         'price' => filter_var($item['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
//       ];
//     }, $data['orderitems']);

//     // Begin a transaction
//     $pdo->beginTransaction();

//     try {
//       // Prepare an SQL statement to insert a new order into the orders table
//       $sql = "INSERT INTO orders (user_id, address_id, order_date, total_amount, status, delivery_status) VALUES (:user_id, :address_id, :order_date, :total_amount, :status, :delivery_status)";
//       $stmt = $pdo->prepare($sql);

//       // Bind the input data to the SQL statement parameters
//       $stmt->bindParam(':user_id', $user_id);
//       $stmt->bindParam(':address_id', $address_id);
//       $stmt->bindParam(':order_date', $order_date);
//       $stmt->bindParam(':total_amount', $total_amount);
//       $stmt->bindParam(':status', $status);
//       $stmt->bindParam(':delivery_status', $delivery_status);

//       // Execute the SQL statement
//       if ($stmt->execute()) {
//         // Get the inserted order id
//         $order_id = $pdo->lastInsertId();

//         // Prepare an SQL statement to insert multiple order items into the orderitems table
//         $sql = "INSERT INTO orderitems (order_id, product_id, quantity, price) VALUES ";
//         // Loop through the order items array and append values to the SQL statement
//         foreach ($orderitems as $item) {
//           $sql .= "({$order_id}, {$item['product_id']}, {$item['quantity']}, {$item['price']}),";
//         }
//         // Remove the trailing comma from the SQL statement
//         $sql = rtrim($sql, ',');

//         // Execute the SQL statement
//         if ($pdo->exec($sql)) {
//           // Commit the transaction
//           $pdo->commit();
//           // Set the response status code to 201 Created
//           http_response_code(201);
//           // Set the response content type to JSON
//           header('Content-Type: application/json');
//           // Encode the inserted order id and user id into a JSON object
//           echo json_encode(['order_id' => $order_id, 'user_id' => $user_id]);
//         } else {
//           // Rollback the transaction
//           $pdo->rollBack();
//           // Set the response status code to 500 Internal Server Error
//           http_response_code(500);
//           // Set the response content type to plain text
//           header('Content-Type: text/plain');
//           // Echo an error message
//           echo "Failed to create order items";
//         }
//       } else {
//         // Rollback the transaction
//         $pdo->rollBack();
//         // Set the response status code to 500 Internal Server Error
//         http_response_code(500);
//         // Set the response content type to plain text
//         header('Content-Type: text/plain');
//         // Echo an error message
//         echo "Failed to create order";
//       }
//     } catch (PDOException $e) {
//       // Rollback the transaction
//       $pdo->rollBack();
//       // Set the response status code to 500 Internal Server Error
//       http_response_code(500);
//       // Set the response content type to plain text
//       header('Content-Type: text/plain');
//       // Echo an error message
//       echo "Database error: " . $e->getMessage();
//     }
//   } else {
//     // Set the response status code to 400 Bad Request
//     http_response_code(400);
//     // Set the response content type to plain text
//     header('Content-Type: text/plain');
//     // Echo an error message
//     echo "Invalid input data";
//   }
// } else {
//   // Set the response status code to 405 Method Not Allowed
//   http_response_code(405);
//   // Set the response content type to plain text
//   header('Content-Type: text/plain');
//   // Echo an error message
//   echo "Only POST method is allowed";
// }
?>
