# Modules

## Application configuration

**File:** `config/app.php`

**Responsibility:** Defines the application name, base URL, allowed enquiry types and statuses, and starts a hardened session.

**Inputs:** Server session and request environment.

**Output:** Constants and an active PHP session.

**Security:** Uses HttpOnly and SameSite cookie settings and enables Secure cookies when HTTPS is detected.

## Database module

**File:** `config/database.php`

**Responsibility:** Loads `.env` values and creates one reusable PDO connection.

**Inputs:** DB host, name, user, and password from `.env` or XAMPP-safe defaults.

**Output:** PDO configured for exceptions, utf8mb4, associative fetches, and native prepared statements.

**Security:** Credentials are centralized and prepared statements are enabled.

## Shared helpers

**Files:** `includes/functions.php`, `includes/csrf.php`, `includes/flash.php`, `includes/auth.php`

**Responsibility:** Escaping, request validation, redirects, CSRF tokens, one-use flash messages, and admin session checks.

**Inputs:** Request values, session values, and database exceptions.

**Output:** Validated data, safe HTML, redirects, and authentication decisions.

**Security:** Untrusted values are validated and escaped; CSRF tokens use `random_bytes()` and `hash_equals()`.

## Public enquiry workflow

**Files:** `index.php`, `actions/submit-enquiry.php`, `public/js/validation.js`

**Responsibility:** Render and validate the public form, then insert a new enquiry with status `New`.

**Inputs:** Form fields and a CSRF token.

**Output:** Flash message followed by redirect to the form.

**Security:** The action accepts POST only, validates every field server-side, and uses a prepared INSERT.

## Admin pages

**Files:** `admin/login.php`, `admin/index.php`, `admin/view.php`, `admin/logout.php`

**Responsibility:** Authenticate administrators, list/search enquiries, show full details, and provide management controls.

**Inputs:** Login POST data and GET search/ID values.

**Output:** Server-rendered HTML with escaped database values.

**Security:** Protected pages call `requireAdmin()`; login uses `password_verify()` and regenerates the session ID.

## Admin actions

**Files:** `actions/update-status.php`, `actions/delete-enquiry.php`

**Responsibility:** Perform validated status changes and deletion.

**Inputs:** POST IDs, status values, and CSRF tokens.

**Output:** Flash message and redirect.

**Security:** Authentication, POST method checks, CSRF checks, ID validation, allow-listed statuses, and prepared statements.

## Local setup utility

**File:** `tools/create-admin.php`

**Responsibility:** Create the first admin from the command line.

**Inputs:** Email and password entered interactively.

**Output:** One hashed admin row.

**Security:** Never embeds a default password and requires a 12-character minimum password.
