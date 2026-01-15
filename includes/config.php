<?php
define('BASE_URL', '/project_db');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>