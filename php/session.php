<?php
$isHttps = !empty($_SERVER['HTTPS'])
    && $_SERVER['HTTPS'] !== 'off';

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION['logged_in'])
    && $_SESSION['logged_in'] === true) {

    $timeout = 15 * 60;
    $lastActivity = $_SESSION['last_activity'] ?? 0;

    if (time() - $lastActivity > $timeout) {
        $_SESSION = [];

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

        session_destroy();
        header('Location: ../html/login.html?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}
?>
