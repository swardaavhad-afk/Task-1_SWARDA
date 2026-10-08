<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/flash.php';
require_once dirname(__DIR__) . '/includes/auth.php';

requireAdmin();
$search = trim((string) ($_GET['search'] ?? ''));
$enquiries = [];
$error = null;

try {
    $pdo = getDatabaseConnection();
    if ($search === '') {
        $statement = $pdo->query('SELECT id, name, email, subject, enquiry_type, status, created_at FROM enquiries ORDER BY created_at DESC');
    } else {
        $statement = $pdo->prepare(
            'SELECT id, name, email, subject, enquiry_type, status, created_at FROM enquiries
             WHERE name LIKE :name_search OR email LIKE :email_search OR subject LIKE :subject_search OR enquiry_type LIKE :type_search OR message LIKE :message_search
             ORDER BY created_at DESC'
        );
        $term = '%' . $search . '%';
        $statement->execute([
            ':name_search' => $term,
            ':email_search' => $term,
            ':subject_search' => $term,
            ':type_search' => $term,
            ':message_search' => $term,
        ]);
    }
    $enquiries = $statement->fetchAll();
} catch (Throwable $exception) {
    logApplicationException($exception);
    $error = 'Enquiries are temporarily unavailable.';
}

$pageTitle = 'Admin dashboard';
require dirname(__DIR__) . '/includes/header.php';
?>
<main class="container admin-content">
    <div class="page-heading"><div><p class="eyebrow">Workspace</p><h1>Enquiries</h1></div><span class="user-chip"><?= e($_SESSION['admin_email'] ?? '') ?></span></div>
    <?php if ($error !== null): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form class="search-bar" method="get" action="<?= e(BASE_URL) ?>/admin/index.php">
        <label for="search">Search enquiries</label>
        <div class="search-controls"><input id="search" name="search" type="search" value="<?= e($search) ?>" placeholder="Name, email, subject or message"><button class="button button-secondary" type="submit">Search</button><?php if ($search !== ''): ?><a class="button button-quiet" href="<?= e(BASE_URL) ?>/admin/index.php">Clear</a><?php endif; ?></div>
    </form>
    <?php if ($enquiries === []): ?>
        <div class="empty-state"><h2><?= $search === '' ? 'No enquiries yet' : 'No matching enquiries' ?></h2><p><?= $search === '' ? 'New submissions will appear here.' : 'Try a different search term.' ?></p></div>
    <?php else: ?>
        <div class="table-wrap"><table><caption class="sr-only">Enquiry list</caption><thead><tr><th>ID</th><th>Contact</th><th>Subject</th><th>Type</th><th>Status</th><th>Created</th><th><span class="sr-only">Actions</span></th></tr></thead><tbody>
        <?php foreach ($enquiries as $enquiry): ?>
            <tr><td data-label="ID">#<?= e((string) $enquiry['id']) ?></td><td data-label="Contact"><strong><?= e($enquiry['name']) ?></strong><span class="subtext"><?= e($enquiry['email']) ?></span></td><td data-label="Subject"><?= e($enquiry['subject']) ?></td><td data-label="Type"><?= e($enquiry['enquiry_type']) ?></td><td data-label="Status"><span class="status status-<?= e(strtolower(str_replace(' ', '-', $enquiry['status']))) ?>"><?= e($enquiry['status']) ?></span></td><td data-label="Created"><?= e((string) $enquiry['created_at']) ?></td><td data-label="Actions"><a class="text-link" href="<?= e(BASE_URL) ?>/admin/view.php?id=<?= e((string) $enquiry['id']) ?>">View</a></td></tr>
        <?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</main>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
