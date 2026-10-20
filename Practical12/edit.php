<?php
require 'db.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM events WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    exit("Event not found.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $date = $_POST['event_date'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    if ($name === '' ||
        !in_array($category, ['Technical', 'Sports', 'Cultural']) ||
        !in_array($status, ['Open', 'Closed'])) {
        $message = "Enter valid event details.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE events
             SET name=?, event_date=?, category=?, status=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "ssssi", $name, $date, $category, $status, $id
        );

        if ($stmt->execute()) {
            header("Location: index.php?msg=Event+updated");
            exit;
        } else {
            $message = "Unable to update event.";
        }
    }
}
?>

<h2>Edit Event</h2>
<p><?= htmlspecialchars($message) ?></p>

<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">

    Name:
    <input name="name" required
           value="<?= htmlspecialchars($event['name']) ?>"><br><br>

    Date:
    <input type="date" name="event_date" required
           value="<?= htmlspecialchars($event['event_date']) ?>"><br><br>

    Category:
    <select name="category">
        <?php foreach (['Technical', 'Sports', 'Cultural'] as $c): ?>
            <option value="<?= $c ?>"
                <?= $event['category'] === $c ? 'selected' : '' ?>>
                <?= $c ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    Status:
    <select name="status">
        <?php foreach (['Open', 'Closed'] as $s): ?>
            <option value="<?= $s ?>"
                <?= $event['status'] === $s ? 'selected' : '' ?>>
                <?= $s ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Update Event</button>
</form>

<a href="index.php">Back</a>