<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid request.");
}

$id = (int)($_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT poster FROM events WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    exit("Event not found.");
}

$stmt = $conn->prepare("DELETE FROM events WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($event['poster']) {
        $path = __DIR__ . '/uploads/' . basename($event['poster']);
        if (is_file($path)) {
            unlink($path);
        }
    }

    header("Location: index.php?msg=Event+deleted");
    exit;
}

echo "Unable to delete event.";
?>