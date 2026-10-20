<?php
require 'db.php';

$search = $_GET['search'] ?? '';
$course = $_GET['course'] ?? '';

$term = "%$search%";

$stmt = $conn->prepare(
    "SELECT * FROM students
     WHERE (name LIKE ? OR email LIKE ? OR course LIKE ?)
     AND (? = '' OR course = ?)
     ORDER BY id DESC"
);

$stmt->bind_param(
    "sssss", $term, $term, $term, $course, $course
);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
</head>
<body>
    <h1>Student Management System</h1>

    <p>
        <a href="add.php">+ Add Student</a>
    </p>

    <form method="GET">
        <input type="text" name="search"
               placeholder="Search name, email or course"
               value="<?= htmlspecialchars($search) ?>">

        <select name="course">
            <option value="">All Courses</option>
            <option value="BCA">BCA</option>
            <option value="BSc IT">BSc IT</option>
            <option value="BTech">BTech</option>
        </select>

        <button type="submit">Search / Filter</button>
        <a href="index.php">Reset</a>
    </form>

    <?php if (isset($_GET['msg'])): ?>
        <p><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['course']) ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>

                <form action="delete.php" method="POST"
                      style="display:inline"
                      onsubmit="return confirm('Delete this student?')">
                    <input type="hidden" name="id"
                           value="<?= $row['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>