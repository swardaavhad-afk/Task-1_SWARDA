<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    exit;
}

function requestMethodIs(string $method): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === strtoupper($method);
}

function getPostString(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function validateEnquiry(array $input): array
{
    $errors = [];
    $name = trim((string) ($input['name'] ?? ''));
    $email = trim((string) ($input['email'] ?? ''));
    $phone = trim((string) ($input['phone'] ?? ''));
    $subject = trim((string) ($input['subject'] ?? ''));
    $enquiryType = trim((string) ($input['enquiry_type'] ?? ''));
    $message = trim((string) ($input['message'] ?? ''));

    if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 100 || !preg_match("/^[\\p{L}\\p{M} .'-]+$/u", $name)) {
        $errors['name'] = 'Enter a valid name between 2 and 100 characters.';
    }

    if ($email === '' || mb_strlen($email) > 255 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if ($phone === '' || mb_strlen($phone) > 30 || !preg_match('/^[+0-9][0-9(). \-]{6,29}$/', $phone)) {
        $errors['phone'] = 'Enter a valid phone number.';
    }

    if ($subject === '' || mb_strlen($subject) > 200) {
        $errors['subject'] = 'Enter a subject up to 200 characters.';
    }

    if (!in_array($enquiryType, ENQUIRY_TYPES, true)) {
        $errors['enquiry_type'] = 'Select a valid enquiry type.';
    }

    if ($message === '' || mb_strlen($message) < 10 || mb_strlen($message) > 5000) {
        $errors['message'] = 'Enter a message between 10 and 5,000 characters.';
    }

    return $errors;
}

function logApplicationException(Throwable $exception): void
{
    error_log(sprintf('[%s] %s in %s:%d', date('c'), $exception->getMessage(), $exception->getFile(), $exception->getLine()));
}
