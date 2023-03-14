<?php
// used to connect to the database
$host = "localhost";
$db_name = "behwaimeng";
$username = "behwaimeng";
$password = "5201314beh@";

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    echo "";
}

// show error
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
