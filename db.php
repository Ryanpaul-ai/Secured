<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "secured";
$port = 3308;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>