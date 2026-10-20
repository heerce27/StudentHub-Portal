<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'student' &&
    $_SESSION['role'] !== 'admin') {
    exit("Access denied.");
}
?>

<h1>Student Page</h1>
<p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <a href="dashboard.php">Admin Dashboard</a>
<?php else: ?>
    <p>You are logged in as a student.</p>
<?php endif; ?>

<br><br>
<a href="logout.php">Logout</a>