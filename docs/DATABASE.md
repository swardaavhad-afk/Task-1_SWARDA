# Database

Database name: `contact_management`

```text
admins
  id (PK)
  email (unique)
  password_hash
  created_at

 enquiries
  id (PK)
  name
  email
  phone
  subject
  enquiry_type
  message
  status
  created_at
  updated_at
```

The `admins` table is used only for authentication. The `enquiries` table stores public submissions; there is no direct relational link because this application has one global admin role rather than per-user ownership.

`enquiries.id` is an unsigned auto-increment primary key. Contact fields use bounded VARCHAR columns, while `message` uses TEXT. `status` is an ENUM with `New`, `In Progress`, and `Resolved`, defaulting to `New`. Both timestamps are maintained by MySQL.

Indexes support status filtering, newest-first access, email lookup, and enquiry type filtering. The full dashboard search also scans name, subject, type, email, and message with prepared `LIKE` parameters; for this small internal application, that keeps the implementation simple and predictable.

Import `database/schema.sql` directly in phpMyAdmin. It creates the database and both tables but inserts no administrator or known password.
