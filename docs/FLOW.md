# Application Flows

## Contact submission

```text
User opens form
  -> JavaScript validation
  -> POST submit-enquiry.php
  -> CSRF check
  -> PHP validation
  -> PDO prepared INSERT
  -> flash message
  -> redirect to index.php
```

## Admin login

```text
User opens login page
  -> submits email, password, and CSRF token
  -> PHP loads the matching admin by email
  -> password_verify()
  -> session ID regeneration
  -> redirect to dashboard
```

Invalid credentials return the same generic error and do not reveal whether an email exists.

## Search

```text
Authenticated admin submits GET search
  -> dashboard builds prepared LIKE parameters
  -> MySQL searches name, email, subject, type, and message
  -> escaped table or no-results state is rendered
```

## Status update

```text
Authenticated admin submits POST from detail page
  -> method, session, CSRF, ID, and allow-listed status checks
  -> prepared UPDATE with CURRENT_TIMESTAMP
  -> flash message
  -> redirect back to detail
```

## Delete

```text
Authenticated admin confirms browser prompt
  -> POST delete request
  -> method, session, CSRF, and positive-ID checks
  -> prepared DELETE
  -> flash message
  -> redirect to dashboard
```
