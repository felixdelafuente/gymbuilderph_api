<?php
// Include the database connection
include 'db.php';
// Check if the form data is submitted
if (isset($_POST['submit'])) {
  // Get the form data
  $id = $_POST['id'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $image_link = $_POST['image_link'];
  $item_values = $_POST['item_values'];
  $operation = $_POST['operation'];

  // Validate the form data
  if ($name && $description && $price && $image_link && $item_values && $operation) {
    // Sanitize the form data
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $image_link = filter_var($image_link, FILTER_SANITIZE_URL);
    $item_values = filter_var($item_values, FILTER_SANITIZE_STRING);
    $operation = filter_var($operation, FILTER_SANITIZE_STRING);

    // Prepare the JSON data to send to the API
    $data = [
      'id' => $id,
      'name' => $name,
      'description' => $description,
      'price' => $price,
      'image_link' => $image_link,
      'item_values' => $item_values
    ];
    $json = json_encode($data);

    // Initialize a curl session
    $curl = curl_init();
    // Set the curl options
    curl_setopt($curl, CURLOPT_URL, "http://localhost/$operation.php"); // Replace with the operation file name
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($operation)); // Set the HTTP method to the operation name
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Set the content type to JSON
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json); // Set the JSON data as the request body

    // Execute the curl request and get the response
    $response = curl_exec($curl);
    // Close the curl session
    curl_close($curl);
  } else {
    // Set an error message if any of the form data is missing or invalid
    $error = "Please fill in all the fields and choose a valid operation";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Products CRUD API Test</title>
</head>
<body>
  <h1>Products CRUD API Test</h1>
  <p>This is a simple HTML form that allows you to test your products CRUD API using PHP.</p>
  <form method="post" action="index.php">
    <label for="id">Product ID:</label>
    <input type="number" id="id" name="id" value="<?php echo isset($id) ? $id : '';?>"><br>
    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : '';?>"><br>
    <label for="description">Product Description:</label>
    <textarea id="description" name="description"><?php echo isset($description) ? $description : '';?></textarea><br>
    <label for="price">Product Price:</label>
    <input type="number" id="price" name="price" step="0.01" value="<?php echo isset($price) ? $price : '';?>"><br>
    <label for="image_link">Product Image Link:</label>
    <input type="url" id="image_link" name="image_link" value="<?php echo isset($image_link) ? $image_link : '';?>"><br>
    <label for="item_values">Product Item Values:</label>
    <input type="text" id="item_values" name="item_values" value="<?php echo isset($item_values) ? $item_values : '';?>"><br>
    <label for="operation">Choose an operation:</label>
    <select id="operation" name="operation">
      <option value="">Select an option</option>
      <option value="create_product"<?php echo isset($operation) && $operation == 'create_product' ? ' selected' : '';?>>Create</option>
      <option value="read_product"<?php echo isset($operation) && $operation == 'read_product' ? ' selected' : '';?>>Read</option>
      <option value="update_product"<?php echo isset($operation) && $operation == 'update_product' ? ' selected' : '';?>>Update</option>
      <option value="delete_product"<?php echo isset($operation) && $operation == 'delete_product' ? ' selected' : '';?>>Delete</option>
    </select><br>
    <input type="submit" name="submit" value="Submit">
  </form>
  <?php
  // Check if there is an error message
  if (isset($error)) {
    // Display the error message in red
    echo "<p style='color:red;'>$error</p>";
  }
  // Check if there is a response from the API
  if (isset($response)) {
    // Display the response in a preformatted text
    echo "<pre>$response</pre>";
  }
  ?>
</body>
</html>
