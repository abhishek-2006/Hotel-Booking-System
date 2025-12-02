<?php
session_start();

$PROJECT_ROOT = '/Hotel Management system';

// Destroy all session data
session_unset();
session_destroy();

// Optional: Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect to home page after logout
header("Location: {$PROJECT_ROOT}/index.php");
exit;
?>