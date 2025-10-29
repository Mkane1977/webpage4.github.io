<?php
require_once __DIR__ . '/db.php';

/**
 * Start a hardened PHP session with sensible cookie flags.
 */
function start_secure_session() {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    // samesite requires PHP 7.3+; most free hosts support it now
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/**
 * Require a valid login. Also implements 60s inactivity timeout.
 * Refreshes the "last_activity" timestamp on each valid request.
 */
function require_login() {
    start_secure_session();
    // Prevent back button from showing a cached admin page after logout
    nocache_headers();

    if (empty($_SESSION['user_id'])) {
        header('Location: login.php?msg=please_login');
        exit;
    }

    // 60-second inactivity timeout
    $timeoutSeconds = 60;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeoutSeconds)) {
        session_unset();
        session_destroy();
        header('Location: login.php?msg=timeout');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

/** Send no-cache headers for protected pages */
function nocache_headers() {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: 0');
}
