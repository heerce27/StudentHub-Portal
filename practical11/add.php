<?php
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $course = $_POST['course'];

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a name and valid email.";
    } elseif (!in_array($course, ['BCA', 'BSc IT', 'BTech'])) {
        $error = "Select a valid course.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, course)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $name, $email, $course);

        if ($stmt->execute()) {
            header("Location: index.php?msg=Student+added");
            exit;
        } else {
            $error = "Unable to add student.";
        }
    }
}
?>

<h2>Add Student</h2>

<p><?= htmlspecialchars($error) ?></p>

<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>

    Course:
    <select name="course" required>
        <option value="BCA">BCA</option>
        <option value="BSc IT">BSc IT</option>
        <option value="BTech">BTech</option>
    </select><br><br>

    <button type="submit">Save Student</button>
</form>

<a href="index.php">Back</a>