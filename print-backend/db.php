<?php
$host = "localhost";
$user = "printuser";
$pass = "printpass";
$db   = "print_service";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}
