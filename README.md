# Contact Enquiry Management System

A small PHP 8+ and MySQL application for collecting public contact enquiries and managing them through an authenticated admin area.

## Features

- Responsive public enquiry form
- Client-side and server-side validation
- PDO prepared statements
- CSRF protection and escaped output
- Session-based admin authentication
- Search, detail view, status updates, and deletion
- Flash messages and POST/Redirect/GET handling
- Zero runtime dependencies beyond PHP, MySQL, and Apache

## Stack

HTML5, CSS3, vanilla JavaScript, PHP 8+, PDO, MySQL, Apache/XAMPP, phpMyAdmin, and Git.

## Quick start

1. Copy the project to `C:\xampp\htdocs\contact-management`.
2. Start Apache and MySQL from XAMPP.
3. Open phpMyAdmin and import `database/schema.sql`.
4. Copy `.env.example` to `.env`. For a default XAMPP install, `DB_USER=root` and an empty `DB_PASS` are common local values.
5. Create an administrator from the project directory: `php tools/create-admin.php`.
6. Open `http://localhost/contact-management/`.
7. Open `http://localhost/contact-management/admin/login.php` to manage submissions.

## Structure

- `config/`: application constants and the shared PDO connection.
- `includes/`: reusable session, authentication, CSRF, flash, escaping, validation, and layout helpers.
- `actions/`: POST-only request handlers that modify data.
- `admin/`: protected dashboard, login, and detail pages.
- `public/`: CSS and vanilla JavaScript assets.
- `database/`: importable MySQL schema.
- `tools/`: local CLI setup utility.
- `docs/`: implementation and deployment documentation.

## Admin account

The schema intentionally creates no administrator with a known password. Run `php tools/create-admin.php` from the project root after configuring `.env`; the password is stored with `password_hash()`.

## Testing

Run PHP syntax checks from the project root:

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

Manually test valid and invalid submissions, direct unauthenticated admin access, login/logout, search, no-result search, detail viewing, status changes, delete confirmation, CSRF rejection, invalid IDs, and GET requests to action endpoints.

## Documentation

- [Architecture](docs/ARCHITECTURE.md)
- [Modules](docs/MODULES.md)
- [Database](docs/DATABASE.md)
- [Validation](docs/VALIDATION.md)
- [Security](docs/SECURITY.md)
- [Flows](docs/FLOW.md)
- [Deployment](docs/DEPLOYMENT.md)
- [Troubleshooting](docs/TROUBLESHOOTING.md)

## Git

`.env`, logs, OS metadata, and local VS Code settings are excluded by `.gitignore`. Review changes with `git diff`, then commit only reviewed source, schema, and documentation.
