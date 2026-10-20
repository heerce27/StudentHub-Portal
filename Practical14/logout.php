<?php
session_start();
require 'db.php';

$username = $_SESSION['username'] ?? 'Unknown';
$action = "Logged out";

$log = $conn->prepare(
    "INSERT INTO audit_logs (username, action) VALUES (?, ?)"
);
$log->bind_param("ss", $username, $action);
$log->execute();

$_SESSION = [];
session_destroy();

header("Location: login.php");
exit;
?>