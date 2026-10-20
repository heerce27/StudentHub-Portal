<?php
$conn = new mysqli("localhost", "root", "", "practical11_db");

if ($conn->connect_error) {
    die("Database connection failed.");
}
?>