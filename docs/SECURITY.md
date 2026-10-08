# Security

## Prepared statements

All user-controlled database values use PDO prepared statements in `actions/submit-enquiry.php`, the admin login, search, detail, update, and delete paths. This prevents SQL injection by keeping data separate from SQL syntax.

## CSRF protection

`includes/csrf.php` creates a cryptographically random session token and validates it with `hash_equals()`. Forms include the token; submission, login, logout, status update, and delete endpoints reject missing or invalid tokens.

## Password hashing

`tools/create-admin.php` stores only `password_hash(..., PASSWORD_DEFAULT)`. Login uses `password_verify()`, so the application never needs a plaintext password.

## Authentication and sessions

Admin pages and actions call `requireAdmin()`. Successful login regenerates the session ID. Cookies are HttpOnly, SameSite Lax, and Secure when HTTPS is active. Logout clears the session.

## Output escaping

The `e()` helper wraps `htmlspecialchars()` with UTF-8 and is used for database values, request values, messages, and attributes. Message content is escaped before `nl2br()`.

## Input and method controls

Server-side validation rejects malformed fields, IDs, enquiry types, and statuses. State-changing actions accept POST only. IDs are validated as positive integers. Delete requires a browser confirmation as well as server-side protections.

## Credential and error protection

`.env` is ignored by Git and `.env.example` contains placeholders only. Database exceptions are logged with `error_log()` but normal users see a generic message rather than SQL, paths, credentials, or stack traces.

This is suitable for a small local/internal XAMPP deployment. A public production deployment would additionally require HTTPS, web-server hardening, backups, monitoring, and operational access controls.
