
<?php
require_once 'session.php';

// Clear all session variables.
$_SESSION = [];

// Delete the session cookie.
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $p['path'],
        'domain' => $p['domain'],
        'secure' => $p['secure'],
        'httponly' => $p['httponly'],
        'samesite' => 'Lax'
    ]);
}

// Destroy the server-side session.
session_destroy();

header('Location: ../html/login.html?logout=1');
exit;
?>
