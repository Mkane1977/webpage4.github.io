<?php
require_once __DIR__ . '/common.php';
start_secure_session();

// Kill the session
session_unset();
session_destroy();
nocache_headers();
header('Location: login.php?msg=logged_out');
exit;
