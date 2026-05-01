<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$body   = json_decode(file_get_contents('php://input'), true);
$uInput = trim($body['username'] ?? '');
$pInput = trim($body['password'] ?? '');

if ($uInput === '' || $pInput === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Username dan password wajib diisi.']);
    exit;
}

$adminUser = $_ENV['ADMIN_USERNAME'] ?? '';
$adminPass = $_ENV['ADMIN_PASSWORD'] ?? '';

if ($uInput !== $adminUser || $pInput !== $adminPass) {
    http_response_code(401);
    echo json_encode(['error' => 'Username atau password salah.']);
    exit;
}

$token = bin2hex(random_bytes(16));

echo json_encode([
    'success' => true,
    'token'   => $token,
    'message' => 'Login berhasil.',
], JSON_UNESCAPED_UNICODE);
