<?php
require_once __DIR__ . '/common.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Admin</h1>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
    <p>This page is protected. It will auto-logout after 60 seconds of inactivity.</p>
    <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </form>
  </div>
</body>
</html>
