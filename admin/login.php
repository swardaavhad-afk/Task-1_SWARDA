<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/flash.php';
require_once dirname(__DIR__) . '/includes/auth.php';

if (isAdminAuthenticated()) {
    redirect('admin/index.php');
}

$errors = [];
if (requestMethodIs('POST')) {
    $email = strtolower(getPostString('email'));
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your form session expired. Please try again.';
    } elseif ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false || $password === '') {
        $errors[] = 'Enter your email and password.';
    } else {
        try {
            $statement = getDatabaseConnection()->prepare('SELECT id, email, password_hash FROM admins WHERE email = :email LIMIT 1');
            $statement->execute([':email' => $email]);
            $admin = $statement->fetch();

            if (is_array($admin) && password_verify($password, $admin['password_hash'])) {
                loginAdmin((int) $admin['id'], (string) $admin['email']);
                redirect('admin/index.php');
            }
        } catch (Throwable $exception) {
            logApplicationException($exception);
        }
        $errors[] = 'Invalid login credentials.';
    }
}

$pageTitle = 'Admin sign in';
require dirname(__DIR__) . '/includes/header.php';
?>
<main class="container narrow-content">
    <section class="form-panel auth-panel">
        <p class="eyebrow">Internal access</p>
        <h1>Admin sign in</h1>
        <?php foreach ($errors as $error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endforeach; ?>
        <form method="post" action="<?= e(BASE_URL) ?>/admin/login.php">
            <?= csrfField() ?>
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" autocomplete="username" required></div>
            <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required></div>
            <button class="button button-primary" type="submit">Sign in</button>
        </form>
    </section>
</main>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
