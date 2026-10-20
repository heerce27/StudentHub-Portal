<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $action = "Logged in";
        $log = $conn->prepare(
            "INSERT INTO audit_logs (username, action) VALUES (?, ?)"
        );
        $log->bind_param("ss", $username, $action);
        $log->execute();

        if ($user['role'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: student.php");
        }
        exit;
    }

    $message = "Invalid username or password.";
}
?>

<h2>Login</h2>
<p><?= htmlspecialchars($message) ?></p>

<form method="POST">
    Username:
    <input name="username" required><br><br>

    Password:
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>