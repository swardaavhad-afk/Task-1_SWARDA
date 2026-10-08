<?php

declare(strict_types=1);

function loadEnvironment(string $path): array
{
    if (!is_readable($path)) {
        return [];
    }

    $values = [];
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $values[trim($key)] = trim($value, " \t\n\r\0\x0B\"");
    }

    return $values;
}

function getDatabaseConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $environment = loadEnvironment(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');
    $host = $environment['DB_HOST'] ?? '127.0.0.1';
    $database = $environment['DB_NAME'] ?? 'contact_management';
    $username = $environment['DB_USER'] ?? 'root';
    $password = $environment['DB_PASS'] ?? '';

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $database);
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
