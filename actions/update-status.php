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
$status = is_string($_POST['status'] ?? null) ? trim($_POST['status']) : '';
if ($id === false || !in_array($status, ENQUIRY_STATUSES, true)) {
    setFlash('error', 'Invalid status update.');
    redirect('admin/index.php');
}

try {
    $statement = getDatabaseConnection()->prepare('UPDATE enquiries SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $statement->execute([':status' => $status, ':id' => $id]);
    setFlash('success', $statement->rowCount() > 0 ? 'Enquiry status updated.' : 'Enquiry was not found or status was unchanged.');
} catch (Throwable $exception) {
    logApplicationException($exception);
    setFlash('error', 'Unable to update the enquiry.');
}

redirect('admin/view.php?id=' . (int) $id);
