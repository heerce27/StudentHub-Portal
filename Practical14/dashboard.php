<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) ||
    $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("Access denied. Admin only.");
}

$username = $_SESSION['username'];
$action = "Viewed admin dashboard";

$log = $conn->prepare(
    "INSERT INTO audit_logs (username, action) VALUES (?, ?)"
);
$log->bind_param("ss", $username, $action);
$log->execute();

$students = $conn->query("SELECT COUNT(*) AS total FROM students")
                 ->fetch_assoc()['total'];

$events = $conn->query("SELECT COUNT(*) AS total FROM events")
               ->fetch_assoc()['total'];

$registrations = $conn->query(
    "SELECT COUNT(*) AS total FROM registrations"
)->fetch_assoc()['total'];

$latest = $conn->query(
    "SELECT s.name AS student, e.name AS event_name
     FROM registrations r
     JOIN students s ON r.student_id = s.id
     JOIN events e ON r.event_id = e.id
     ORDER BY r.id DESC LIMIT 5"
);
?>

<h1>Admin Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($username) ?>!</p>

<nav>
    <a href="dashboard.php">Dashboard</a> |
    <a href="student.php">Student Page</a> |
    <a href="logout.php">Logout</a>
</nav>

<h2>Statistics</h2>
<p>Total Students: <?= $students ?></p>
<p>Total Events: <?= $events ?></p>
<p>Total Registrations: <?= $registrations ?></p>

<h2>Latest Registrations</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Student</th>
        <th>Event</th>
    </tr>

    <?php while ($row = $latest->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['student']) ?></td>
        <td><?= htmlspecialchars($row['event_name']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<h2>Recent Audit Logs</h2>

<?php
$logs = $conn->query(
    "SELECT username, action, log_time
     FROM audit_logs ORDER BY id DESC LIMIT 10"
);
?>

<table border="1" cellpadding="8">
    <tr>
        <th>Username</th>
        <th>Action</th>
        <th>Time</th>
    </tr>

    <?php while ($row = $logs->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['action']) ?></td>
        <td><?= htmlspecialchars($row['log_time']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>