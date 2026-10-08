# Validation

## Client-side

`public/js/validation.js` validates the form on blur and submit, shows field-specific messages, marks invalid fields with `aria-invalid`, and focuses the first invalid field. It checks the same practical rules used by PHP: name characters and length, email shape, international-friendly phone characters, subject length, known enquiry types, and message length.

## Server-side

`includes/functions.php` trims values, validates required fields, uses `filter_var()` for email, checks lengths, matches the phone pattern, and compares enquiry types against the allow-list in `config/app.php`. The action never trusts browser-required attributes or hidden values.

Rules are:

- Name: 2 to 100 characters, letters, spaces, apostrophes, periods, or hyphens.
- Email: valid PHP email format, maximum 255 characters.
- Phone: 7 to 30 characters using common international phone punctuation.
- Subject: required, maximum 200 characters.
- Enquiry type: `General Enquiry`, `Sales`, `Support`, `Partnership`, `Feedback`, or `Other`.
- Message: 10 to 5,000 characters.
- Status: `New`, `In Progress`, or `Resolved`.

JavaScript improves feedback and usability, but it can be bypassed or modified. PHP validation is the authoritative boundary before database writes.
