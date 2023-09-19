<?php
// Database parameters
$host = "localhost";
$dbname = "gym_builder";
$username = "adminAndroidAPI";
$password = "gymbuilderAPI123!";

// PDO instance
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  // Set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

// use PDO;

//     class Database{
//         public $connection;
//         public $statement;

//         public function __construct($config, $username = 'root', $password = '')
//         {
//             $dsn = 'mysql:' . http_build_query($config, '', ';');

//             $this->connection = new PDO($dsn, $username, $password, [
//             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
//             ]);
//         }

//         public function query($query, $params = [])
//         {
//             $this->statement = $this->connection->prepare($query);

//             $this->statement->execute($params);

//             return $this;
//         }

//         public function get()
//         {
//             return $this->statement->fetchAll();
//         }

//         public function find()
//         {
//             return $this->statement->fetch();
//         }

//         public function findOrFail()
//         {
//             $result = $this->find();

//             if (! $result) {
//                 abort();
//             }

//             return $result;
//         }
//     }
?>
