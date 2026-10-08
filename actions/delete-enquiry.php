<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/flash.php';
require_once dirname(__DIR__) . '/includes/auth.php';

requireAdmin();
if (!requestMethodIs('POST') || !verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Invalid request.');
    redirect('admin/index.php');
}

$id = filter_var($_POST['enquiry_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    setFlash('error', 'Invalid enquiry ID.');
    redirect('admin/index.php');
}

try {
    $statement = getDatabaseConnection()->prepare('DELETE FROM enquiries WHERE id = :id');
    $statement->execute([':id' => $id]);
    setFlash('success', $statement->rowCount() > 0 ? 'Enquiry deleted successfully.' : 'Enquiry not found.');
} catch (Throwable $exception) {
    logApplicationException($exception);
    setFlash('error', 'Unable to delete the enquiry.');
}

redirect('admin/index.php');
