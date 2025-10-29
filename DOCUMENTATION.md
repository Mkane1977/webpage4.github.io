# System Overview & Documentation

## What it does
- Authenticates a user against the `users` table.
- On success: redirects to `admin.php` (protected area).
- On failure: redirects to `error.php`.
- Admin page provides a logout button and **auto-logout after 60 seconds** of inactivity.
- After logging out or timing out, trying to visit `admin.php` redirects back to login.

## Session Timeout: How It Works
- Each valid request to `admin.php` refreshes `$_SESSION['last_activity']` with current `time()`.
- If the difference between `time()` and `last_activity` exceeds **60 seconds**, the session is destroyed and the user is sent to `login.php?msg=timeout`.
- Implemented in `common.php::require_login()`.

## Security Notes
- Uses `password_hash()` and `password_verify()` (bcrypt under the hood).
- Parameterized queries (prepared statements) with MySQLi.
- Session hardening:
  - `session.use_strict_mode=1`
  - `httponly` cookie flag
  - `SameSite=Lax`
  - `secure` cookie flag when HTTPS is detected
  - `session_regenerate_id(true)` on successful login
- Sends no-cache headers on protected pages to prevent cached content from being revealed via back navigation.

## Deployment Process
1. Choose a free PHP/MySQL host (e.g., 000WebHost, InfinityFree).
2. Upload project files.
3. Create a MySQL database via the host’s control panel.
4. Import `schema.sql` in phpMyAdmin.
5. Copy `config.sample.php` → `config.php` and fill DB creds.
6. Visit `/seed_user.php` once to insert the default user.
7. Test flows:
   - Successful login (admin/Admin@123)
   - Failed login (error redirect)
   - Inactivity timeout (wait 60s on admin, then try to navigate or refresh)
   - Logout button
   - Direct access to `admin.php` without login (should redirect to login)

## Known Issues / Assumptions
- This is a minimal educational example; no rate limiting or brute-force protection.
- No email/username enumeration protection on the login form (both failures redirect to same error page).
- If the host lacks HTTPS, session cookies will not be `secure` (still `httponly` + `SameSite=Lax`). Prefer enabling HTTPS.
- Timeouts based strictly on server PHP time; ensure server time is correct.

## SQL Schema
See `schema.sql` in the project root.
