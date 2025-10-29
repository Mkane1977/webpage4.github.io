<?php
require_once __DIR__ . '/common.php';

start_secure_session();

// If form submitted, handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username !== '' && $password !== '') {
        $user = find_user_by_username($username);
        if ($user && password_verify($password, $user['password_hash'])) {
            // Login success
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time();
            header('Location: admin.php');
            exit;
        }
    }
    // Login failed
    header('Location: error.php');
    exit;
}

// If already logged in, go to admin
if (!empty($_SESSION['user_id'])) {
    header('Location: admin.php');
    exit;
?>

<?php } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Login</h1>
    <?php if (isset($_GET['msg'])): ?>
      <div class="notice">
        <?php if ($_GET['msg'] === 'timeout'): ?>
            Your session expired due to 1 minute of inactivity. Please log in again.
        <?php elseif ($_GET['msg'] === 'please_login'): ?>
            Please log in to continue.
        <?php elseif ($_GET['msg'] === 'logged_out'): ?>
            You have been logged out.
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <form method="post" action="login.php" autocomplete="off">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required autofocus>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Sign In</button>
    </form>
  </div>
</body>
</html>
