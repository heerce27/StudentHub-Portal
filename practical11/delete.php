<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid request.");
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    exit("Invalid student ID.");
}

$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?msg=Student+deleted");
    exit;
}

echo "Unable to delete student.";
?>