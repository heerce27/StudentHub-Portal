<?php
require 'db.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    exit("Student not found.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $course = $_POST['course'];

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || !in_array($course, ['BCA', 'BSc IT', 'BTech'])) {
        $message = "Enter valid student details.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE students SET name=?, email=?, course=?
             WHERE id=?"
        );

        $stmt->bind_param("sssi", $name, $email, $course, $id);

        if ($stmt->execute()) {
            header("Location: index.php?msg=Student+updated");
            exit;
        } else {
            $message = "Unable to update student.";
        }
    }
}
?>

<h2>Edit Student</h2>
<p><?= htmlspecialchars($message) ?></p>

<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">

    Name:
    <input name="name" required
           value="<?= htmlspecialchars($student['name']) ?>"><br><br>

    Email:
    <input type="email" name="email" required
           value="<?= htmlspecialchars($student['email']) ?>"><br><br>

    Course:
    <select name="course">
        <?php foreach (['BCA', 'BSc IT', 'BTech'] as $c): ?>
            <option value="<?= $c ?>"
                <?= $student['course'] === $c ? 'selected' : '' ?>>
                <?= $c ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Update Student</button>
</form>

<a href="index.php">Back</a>