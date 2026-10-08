<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/flash.php';
require_once dirname(__DIR__) . '/includes/auth.php';

requireAdmin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false || $id === null) {
    setFlash('error', 'Invalid enquiry ID.');
    redirect('admin/index.php');
}

$enquiry = null;
try {
    $statement = getDatabaseConnection()->prepare('SELECT id, name, email, phone, subject, enquiry_type, message, status, created_at, updated_at FROM enquiries WHERE id = :id LIMIT 1');
    $statement->execute([':id' => $id]);
    $enquiry = $statement->fetch();
} catch (Throwable $exception) {
    logApplicationException($exception);
}
if (!is_array($enquiry)) {
    setFlash('error', 'Enquiry not found.');
    redirect('admin/index.php');
}

$pageTitle = 'Enquiry #' . $enquiry['id'];
require dirname(__DIR__) . '/includes/header.php';
?>
<main class="container admin-content">
    <p><a class="text-link" href="<?= e(BASE_URL) ?>/admin/index.php">&larr; Back to enquiries</a></p>
    <div class="page-heading"><div><p class="eyebrow">Enquiry #<?= e((string) $enquiry['id']) ?></p><h1><?= e($enquiry['subject']) ?></h1></div><span class="status status-<?= e(strtolower(str_replace(' ', '-', $enquiry['status']))) ?>"><?= e($enquiry['status']) ?></span></div>
    <section class="detail-layout">
        <div class="detail-panel"><h2>Contact details</h2><dl class="detail-list"><div><dt>Name</dt><dd><?= e($enquiry['name']) ?></dd></div><div><dt>Email</dt><dd><a class="text-link" href="mailto:<?= e($enquiry['email']) ?>"><?= e($enquiry['email']) ?></a></dd></div><div><dt>Phone</dt><dd><?= e($enquiry['phone']) ?></dd></div><div><dt>Enquiry type</dt><dd><?= e($enquiry['enquiry_type']) ?></dd></div><div><dt>Created</dt><dd><?= e($enquiry['created_at']) ?></dd></div><div><dt>Updated</dt><dd><?= e($enquiry['updated_at']) ?></dd></div></dl></div>
        <div class="detail-panel message-panel"><h2>Message</h2><p><?= nl2br(e($enquiry['message'])) ?></p></div>
    </section>
    <section class="action-panel"><h2>Manage enquiry</h2><div class="action-grid"><form method="post" action="<?= e(BASE_URL) ?>/actions/update-status.php"><input type="hidden" name="enquiry_id" value="<?= e((string) $enquiry['id']) ?>"><?= csrfField() ?><label for="status">Status</label><div class="inline-form"><select id="status" name="status"><?php foreach (ENQUIRY_STATUSES as $status): ?><option value="<?= e($status) ?>" <?= $enquiry['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select><button class="button button-primary" type="submit">Update status</button></div></form><form method="post" action="<?= e(BASE_URL) ?>/actions/delete-enquiry.php" data-confirm="Delete this enquiry permanently?"><input type="hidden" name="enquiry_id" value="<?= e((string) $enquiry['id']) ?>"><?= csrfField() ?><button class="button button-danger" type="submit">Delete enquiry</button></form></div></section>
</main>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
