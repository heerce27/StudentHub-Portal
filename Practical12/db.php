<?php
$conn = new mysqli("localhost", "root", "", "practical12");

if ($conn->connect_error) {
    die("Database connection failed.");
}
?>