# Troubleshooting

## The form shows a session-expired message

**Symptom:** A submission returns to the form with a CSRF error.

**Cause:** The session cookie was lost or the form token is stale.

**Solution:** Ensure cookies are enabled, reopen the form, and submit again. Check that PHP sessions are writable.

**Verify:** The form submits successfully and displays a flash message.

## The dashboard says enquiries are unavailable

**Symptom:** Admin pages render a generic database error.

**Cause:** MySQL is stopped, the schema is missing, or `.env` values are wrong.

**Solution:** Start MySQL, import `database/schema.sql`, and verify `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS`.

**Verify:** phpMyAdmin shows the `admins` and `enquiries` tables, then reload the dashboard.

## Login always fails

**Symptom:** Valid-looking credentials are rejected.

**Cause:** No admin row exists, the email differs, or the password was not created through the setup utility.

**Solution:** Run `php tools/create-admin.php` and use the exact email entered there.

**Verify:** The CLI reports `Administrator created.` and the login redirects to the dashboard.

## Apache shows a 404

**Symptom:** The application URL cannot be found.

**Cause:** The project is outside `htdocs` or the URL path is wrong.

**Solution:** Use `C:\xampp\htdocs\contact-management` and `http://localhost/contact-management/`.

**Verify:** The public form loads and its stylesheet is applied.

## PHP source or warnings appear in the browser

**Symptom:** PHP is downloaded or notices are visible.

**Cause:** Apache is not using the XAMPP PHP module, or display errors are enabled.

**Solution:** Start Apache from XAMPP and inspect the PHP/Apache logs. Keep production display errors disabled.

**Verify:** A normal request renders HTML without paths, queries, or stack traces.

## Status or delete action returns Invalid request

**Symptom:** An admin action is rejected.

**Cause:** The request was sent with GET, the session expired, or the CSRF token is missing.

**Solution:** Return to the detail page, sign in again if needed, and submit the form normally.

**Verify:** The action uses POST and a fresh form token.
