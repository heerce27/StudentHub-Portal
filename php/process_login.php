
<?php
require_once 'session.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Invalid request method.');
}

$type = $_POST['account_type'] ?? '';
$loginId = trim($_POST['login_id'] ?? '');
$password = $_POST['password'] ?? '';

if (
    !in_array($type, ['student', 'admin'], true) ||
    $loginId === '' ||
    $password === ''
) {
    exit('Please enter valid login details.');
}

if ($type === 'student') {
    $stmt = $pdo->prepare(
        "SELECT student_id, fullname, studentid, email,
                department, year, password
         FROM students
         WHERE studentid = :login_id
         LIMIT 1"
    );
} else {
    $stmt = $pdo->prepare(
        "SELECT admin_id, username, email, password
         FROM admins
         WHERE username = :login_id
         LIMIT 1"
    );
}

$stmt->execute(['login_id' => $loginId]);
$user = $stmt->fetch();

// Use the same error for unknown accounts and wrong passwords.
if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    exit(
        'Invalid username/Student ID or password. ' .
        '<a href="../html/login.html">Try again</a>'
    );
}

// Create a fresh session ID after successful authentication.
session_regenerate_id(true);

$_SESSION['logged_in'] = true;
$_SESSION['last_activity'] = time();

if ($type === 'student') {
    $_SESSION['role'] = 'student';
    $_SESSION['student_id'] = (int) $user['student_id'];
    $_SESSION['studentid'] = $user['studentid'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['department'] = $user['department'];
    $_SESSION['year'] = $user['year'];

    header('Location: ../html/dashboard.php');
    exit;
}

// Admin authentication.
$_SESSION['role'] = 'admin';
$_SESSION['admin_id'] = (int) $user['admin_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['fullname'] = $user['username'];
$_SESSION['email'] = $user['email'];

// Record the last successful admin login.
$update = $pdo->prepare(
    "UPDATE admins
     SET last_login = NOW()
     WHERE admin_id = :id"
);
$update->execute(['id' => $user['admin_id']]);

header('Location: ../html/admin_dashboard.php');
exit;
?>
