# XAMPP Deployment

1. Install XAMPP for Windows.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Copy this project to `C:\xampp\htdocs\contact-management`.
4. Open `http://localhost/phpmyadmin/`.
5. Import `database/schema.sql` using the Import tab. The script creates `contact_management`.
6. Copy `.env.example` to `.env`. Use the local MySQL host, database, user, and password. A default XAMPP install commonly uses `127.0.0.1`, `contact_management`, `root`, and an empty password; do not use that default for an exposed production server.
7. From the project root, run `php tools/create-admin.php` and enter a real local admin email and password of at least 12 characters.
8. Open `http://localhost/contact-management/` and submit a test enquiry.
9. Open `http://localhost/contact-management/admin/login.php`, sign in, search, view, update, and delete the test record.

## Common deployment issues

- Apache will not start: check whether ports 80 or 443 are occupied and change the Apache port or stop the conflicting service.
- MySQL will not start: inspect the XAMPP MySQL log and check for port 3306 conflicts.
- Database connection failure: verify `.env`, that MySQL is running, and that the database exists.
- 404: confirm the folder is exactly under `htdocs\contact-management` and the URL includes `/contact-management/`.
- PHP errors: confirm PHP 8+, PDO MySQL, and inspect the Apache/PHP error log rather than displaying errors to users.
- Permission/configuration issues: ensure Apache can read the project and that `.env` exists at the project root.

For a real server, use HTTPS, a non-root database account, restricted database permissions, backups, and web-server access controls.
