<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$nim  = trim($body['nim'] ?? '');

if ($nim === '') {
    http_response_code(400);
    echo json_encode(['error' => 'NIM wajib diisi.']);
    exit;
}

$ALLOWED_FIELDS = [
    'Linkedin', 'Instagram', 'TikTok', 'Facebook',
    'Tempat Bekerja (Present)',    'Posisi Jabatan (Present)',
    'Status Pekerjaan (Present)',  'Sosmed Kantor (Present)',
    'Tempat Bekerja (Terakhir)',   'Posisi Jabatan (Terakhir)',
    'Status Pekerjaan (Terakhir)', 'Sosmed Kantor (Terakhir)',
    'Alamat Bekerja', 'Email', 'Nomor HP',
];

$setClauses = ["`_status` = 'Tervalidasi'", '`_validated_at` = NOW()'];
$params     = [];
$types      = '';

foreach ($ALLOWED_FIELDS as $field) {
    if (!isset($body[$field])) continue;
    $val = trim($body[$field]);
    if ($val === '' || $val === '-') continue;

    $setClauses[] = "`$field` = ?";
    $params[]     = $val;
    $types       .= 's';
}

$params[] = $nim;
$types   .= 's';

$sql  = 'UPDATE `alumni` SET ' . implode(', ', $setClauses) . ' WHERE `NIM` = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => "NIM '$nim' tidak ditemukan di database."]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();
$conn->close();

echo json_encode([
    'success'      => true,
    'message'      => "Alumni NIM $nim berhasil ditandai sebagai Terverifikasi.",
    'nim'          => $nim,
    'fields_saved' => count($setClauses) - 2,
], JSON_UNESCAPED_UNICODE);
