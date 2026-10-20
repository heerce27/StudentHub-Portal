<?php
$conn = new mysqli("localhost", "root", "", "practical14");

if ($conn->connect_error) {
    die("Database connection failed.");
}
?>