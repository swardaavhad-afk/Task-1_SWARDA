<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("Run this script from the command line.\n");
}

require_once dirname(__DIR__) . '/config/database.php';

$email = trim((string) readline('Admin email: '));
$password = (string) readline('Admin password: ');
if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($password) < 12) {
    exit("Use a valid email and a password of at least 12 characters.\n");
}

try {
    $statement = getDatabaseConnection()->prepare('INSERT INTO admins (email, password_hash) VALUES (:email, :password_hash)');
    $statement->execute([':email' => strtolower($email), ':password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
    echo "Administrator created.\n";
} catch (Throwable $exception) {
    exit("Could not create administrator: " . $exception->getMessage() . "\n");
}
