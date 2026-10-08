<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? APP_NAME;
$flash = consumeFlash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(BASE_URL) ?>/public/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?= e(BASE_URL) ?>/index.php">Contact Management</a>
        <nav aria-label="Primary navigation">
            <?php if (isAdminAuthenticated()): ?>
                <a href="<?= e(BASE_URL) ?>/admin/index.php">Admin dashboard</a>
                <form class="nav-form" method="post" action="<?= e(BASE_URL) ?>/admin/logout.php">
                    <?= csrfField() ?>
                    <button class="nav-button" type="submit">Sign out</button>
                </form>
            <?php else: ?>
                <a href="<?= e(BASE_URL) ?>/admin/login.php">Admin sign in</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<?php if ($flash !== null): ?>
    <div class="container"><div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div></div>
<?php endif; ?>
