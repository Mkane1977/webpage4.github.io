<?php
// Run this once (via browser or CLI) after configuring config.php and creating the DB.
// It will insert a default user: username 'admin' and password 'Admin@123'
require_once __DIR__ . '/db.php';

$username = 'admin';
$password = 'Admin@123';

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = db()->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
$stmt->bind_param('ss', $username, $hash);
try {
    $stmt->execute();
    echo "Seed user created. Username: admin, Password: Admin@123";
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        echo "User 'admin' already exists. Nothing to do.";
    } else {
        throw $e;
    }
}
