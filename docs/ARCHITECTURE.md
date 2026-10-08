# Architecture

This is a lightweight server-rendered PHP application. Pages render HTML, while action endpoints validate POST requests and perform one focused database operation.

```text
Browser
   |
   v
PHP page or form
   |
   v
CSRF + server validation
   |
   v
Action handler
   |
   v
PDO prepared statement
   |
   v
MySQL
```

`config/` owns application constants and the reusable database connection. `includes/` owns cross-cutting concerns and shared layout. `actions/` contains state-changing endpoints. `admin/` contains authenticated pages. `public/` contains browser assets, and `database/` contains the importable schema.

The browser uses GET for pages, views, and searches. POST is used for submissions, login, logout, status updates, and deletion. Mutating requests redirect back to a GET page and use session flash messages, avoiding duplicate form submissions on refresh.

This structure is intentionally small: it gives each responsibility a clear home without adding a framework or service layer that would increase deployment cost for a XAMPP application.
