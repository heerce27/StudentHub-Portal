
<?php
require_once '../php/session.php';

if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] !== true
) {
    header('Location: login.html');
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Access denied. Admin accounts only.');
}

$username = $_SESSION['username'] ?? 'Admin';
$email = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>StudentHub | Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <div class="logo">StudentHub Admin</div>
    <div>
        <a href="../php/logout.php">Logout</a>
    </div>
</header>

<main class="main-content">
    <div class="container">
        <h1>
            Welcome, <?php
            echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            ?>!
        </h1>

        <p>You are logged in as an administrator.</p>

        <p>Account type: Admin</p>

        <p>Email:
            <?php
            echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
            ?>
        </p>

        <div class="grid attendance-grid">
            <div class="card">
                <h2>Manage Students</h2>
                <p>Admin area for future student-management features.</p>
            </div>

            <div class="card">
                <h2>Manage Events</h2>
                <p>Admin area for future event-management features.</p>
            </div>
        </div>
    </div>
</main>

<footer>
    © 2026 StudentHub | Admin Dashboard
</footer>

</body>
</html>
