<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/flash.php';

if (!requestMethodIs('POST')) {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed.');
}

$input = [
    'name' => getPostString('name'),
    'email' => getPostString('email'),
    'phone' => getPostString('phone'),
    'subject' => getPostString('subject'),
    'enquiry_type' => getPostString('enquiry_type'),
    'message' => getPostString('message'),
];
$_SESSION['old_input'] = $input;

if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Your form session expired. Please try again.');
    redirect('index.php');
}

$errors = validateEnquiry($input);
if ($errors !== []) {
    $_SESSION['form_errors'] = $errors;
    setFlash('error', 'Please correct the highlighted fields.');
    redirect('index.php');
}

try {
    $pdo = getDatabaseConnection();
    $statement = $pdo->prepare(
        'INSERT INTO enquiries (name, email, phone, subject, enquiry_type, message, status)
         VALUES (:name, :email, :phone, :subject, :enquiry_type, :message, :status)'
    );
    $statement->execute([
        ':name' => $input['name'],
        ':email' => $input['email'],
        ':phone' => $input['phone'],
        ':subject' => $input['subject'],
        ':enquiry_type' => $input['enquiry_type'],
        ':message' => $input['message'],
        ':status' => 'New',
    ]);

    unset($_SESSION['old_input']);
    setFlash('success', 'Enquiry submitted successfully.');
} catch (Throwable $exception) {
    logApplicationException($exception);
    setFlash('error', 'We could not submit your enquiry right now. Please try again later.');
}

redirect('index.php');
