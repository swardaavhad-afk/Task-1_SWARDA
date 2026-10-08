<?php

declare(strict_types=1);

const APP_NAME = 'Contact Management';
const SESSION_NAME = 'contact_management_session';

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$baseUrl = str_starts_with($requestPath, '/contact-management') ? '/contact-management' : '';
define('BASE_URL', $baseUrl);

const ENQUIRY_TYPES = [
    'General Enquiry',
    'Sales',
    'Support',
    'Partnership',
    'Feedback',
    'Other',
];

const ENQUIRY_STATUSES = [
    'New',
    'In Progress',
    'Resolved',
];

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}
