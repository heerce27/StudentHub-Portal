<?php
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $date = $_POST['event_date'];
    $category = trim($_POST['category']);
    $status = $_POST['status'];

    if ($name === '' || $category === ''
        || !in_array($status, ['Open', 'Closed'])) {
        $message = "Enter valid event details.";
    } elseif (!isset($_FILES['poster'])
        || $_FILES['poster']['error'] !== UPLOAD_ERR_OK) {
        $message = "Please choose a poster.";
    } elseif ($_FILES['poster']['size'] > 2 * 1024 * 1024) {
        $message = "Poster must be 2 MB or smaller.";
    } else {
        $tmp = $_FILES['poster']['tmp_name'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);

        $types = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($types[$mime])) {
            $message = "Only JPG, PNG and WebP are allowed.";
        } else {
            $filename = bin2hex(random_bytes(8))
                      . '.' . $types[$mime];

            $path = __DIR__ . '/uploads/' . $filename;

            if (move_uploaded_file($tmp, $path)) {
                $stmt = $conn->prepare(
                    "INSERT INTO events
                     (name, event_date, category, status, poster)
                     VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "sssss", $name, $date, $category,
                    $status, $filename
                );

                if ($stmt->execute()) {
                    header("Location: index.php?msg=Event+added");
                    exit;
                } else {
                    unlink($path);
                    $message = "Unable to save event.";
                }
            } else {
                $message = "Poster upload failed.";
            }
        }
    }
}
?>

<h2>Add Event</h2>
<p><?= htmlspecialchars($message) ?></p>

<form method="POST" enctype="multipart/form-data">
    Event Name:
    <input type="text" name="name" required><br><br>

    Event Date:
    <input type="date" name="event_date" required><br><br>

    Category:
    <select name="category">
        <option>Technical</option>
        <option>Sports</option>
        <option>Cultural</option>
    </select><br><br>

    Status:
    <select name="status">
        <option>Open</option>
        <option>Closed</option>
    </select><br><br>

    Poster:
    <input type="file" name="poster"
           accept=".jpg,.jpeg,.png,.webp" required><br><br>

    <button type="submit">Add Event</button>
</form>

<a href="index.php">Back</a>