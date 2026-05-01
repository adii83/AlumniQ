<?php
require_once __DIR__ . '/db.php';

$sql = "SELECT `_status`, COUNT(*) AS jumlah FROM `alumni` GROUP BY `_status`";
$res = $conn->query($sql);

$counts = [
    'Tervalidasi'      => 0,
    'Perlu Divalidasi' => 0,
    'Data Abu-Abu'     => 0,
];

while ($row = $res->fetch_assoc()) {
    if (array_key_exists($row['_status'], $counts)) {
        $counts[$row['_status']] = (int) $row['jumlah'];
    }
}
$conn->close();

echo json_encode([
    'tervalidasi'     => $counts['Tervalidasi'],
    'perluDivalidasi' => $counts['Perlu Divalidasi'],
    'dataAbuAbu'      => $counts['Data Abu-Abu'],
    'tidakDitemukan'  => 0,
    'total'           => array_sum($counts),
], JSON_UNESCAPED_UNICODE);
