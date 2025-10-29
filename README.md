# PHP Login System (Starter)

A minimal, secure PHP/MySQL login with session based auth, 60 seconds of inactivity timeout, and clean redirects.

## Features
- PHP sessions with secure cookie flags and strict mode
- Password hashing with `password_hash` / `password_verify`
- Prepared statements (MySQLi) to prevent SQL injection
- `admin.php` protected by session + 60-second inactivity timeout
- `logout.php` destroys the session
- `error.php` shown on failed login
- No-cache headers on protected pages (prevents back-button peek after logout)
- Ready for free hosting (000WebHost, InfinityFree, etc.)

## Quick Start
1. Upload all files to your PHP host.
2. Copy `config.sample.php` to `config.php` and update DB credentials.
3. Create the database and run `schema.sql` (via phpMyAdmin).
4. Visit `/seed_user.php` once to create a default user:
   - **Username:** `admin`
   - **Password:** `Admin@123`
5. Go to `/login.php` and sign in.

### Session Timeout
- Inactivity timeout is **60 seconds**.
- Logic lives in `common.php::require_login()` and uses `$_SESSION['last_activity']`.
- After timeout, the session is destroyed and the user is redirected to `login.php?msg=timeout`.

### Deployment Tips (000WebHost / InfinityFree)
- Use their cPanel / phpMyAdmin to create the database.
- Import `schema.sql`.
- Put `config.php` with your DB creds in the same folder as `db.php`.
- Make sure `display_errors` is OFF in production.
- Prefer HTTPS if available (session cookie auto-sets `secure` when HTTPS is detected).

## Known Assumptions / Notes
- Single table `users` with `username` unique index.
- No registration flow; credentials are seeded with `seed_user.php`.
- Minimal styling; adjust `styles.css` as desired.
- Tested with PHP 8.x; should work on 7.4+.

## File Map
- `index.php` -> redirects to `login.php`
- `login.php` -> form + POST handler for login
- `admin.php` -> protected page (requires login)
- `logout.php` -> destroys session and redirects to login
- `error.php` -> shown on bad credentials
- `db.php` -> DB connector + query helper
- `common.php` -> session bootstrap, auth guard, no-cache headers
- `schema.sql` -> DB table
- `seed_user.php` -> create default admin user
- `styles.css` -> minimal CSS
- `config.sample.php` -> copy to `config.php` and fill creds
