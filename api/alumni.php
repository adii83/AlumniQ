<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$page   = max(1, intval($_GET['page']   ?? 1));
$limit  = max(1, min(100, intval($_GET['limit'] ?? 30)));
$offset = ($page - 1) * $limit;
$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

$conditions = [];
$params     = [];
$types      = '';

if ($search !== '') {
    $conditions[] = "(`Nama Lulusan` LIKE ? OR `NIM` LIKE ? OR `Fakultas` LIKE ? OR `Program Studi` LIKE ?)";
    $s = "%$search%";
    $params = array_merge($params, [$s, $s, $s, $s]);
    $types .= 'ssss';
}

if ($status !== '') {
    $conditions[] = "`_status` = ?";
    $params[] = $status;
    $types   .= 's';
}

$where = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

$countSQL = "SELECT COUNT(*) AS total FROM `alumni` $where";
$stmt = $conn->prepare($countSQL);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total = (int) $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$emptyCheck = fn(string $col) =>
    "(CASE WHEN `$col` IS NOT NULL
          AND `$col` != ''
          AND `$col` != '-'
          AND LOWER(`$col`) NOT IN ('tidak publik','tidak dicantumkan','belum ada','n/a','null','none')
     THEN 1 ELSE 0 END)";

$fieldCountExpr =
    $emptyCheck('Linkedin') . ' + ' .
    $emptyCheck('Instagram') . ' + ' .
    $emptyCheck('TikTok') . ' + ' .
    $emptyCheck('Facebook') . ' + ' .
    $emptyCheck('Tempat Bekerja (Present)') . ' + ' .
    $emptyCheck('Posisi Jabatan (Present)') . ' + ' .
    $emptyCheck('Status Pekerjaan (Present)') . ' + ' .
    $emptyCheck('Sosmed Kantor (Present)') . ' + ' .
    $emptyCheck('Tempat Bekerja (Terakhir)') . ' + ' .
    $emptyCheck('Posisi Jabatan (Terakhir)') . ' + ' .
    $emptyCheck('Status Pekerjaan (Terakhir)') . ' + ' .
    $emptyCheck('Sosmed Kantor (Terakhir)') . ' + ' .
    $emptyCheck('Alamat Bekerja') . ' + ' .
    $emptyCheck('Email') . ' + ' .
    $emptyCheck('Nomor HP');

$tanggalOrder = "IF(`Tanggal Lulus` IS NULL OR `Tanggal Lulus` = '' OR `Tanggal Lulus` = '-', 1, 0) ASC, `Tanggal Lulus` ASC";

$hasEmail = $emptyCheck('Email');

$orderBy = ($status === 'Tervalidasi')
    ? "CASE WHEN `NIM` = '04620276' THEN 1 ELSE 0 END ASC, $hasEmail DESC, ($fieldCountExpr) DESC"
    : $tanggalOrder;

$dataSQL    = "SELECT * FROM `alumni` $where ORDER BY $orderBy LIMIT ? OFFSET ?";
$pageParams = array_merge($params, [$limit, $offset]);
$pageTypes  = $types . 'ii';

$stmt = $conn->prepare($dataSQL);
$stmt->bind_param($pageTypes, ...$pageParams);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$stmt->close();
$conn->close();

echo json_encode([
    'data'       => $rows,
    'total'      => $total,
    'page'       => $page,
    'limit'      => $limit,
    'totalPages' => (int) ceil($total / $limit),
], JSON_UNESCAPED_UNICODE);
