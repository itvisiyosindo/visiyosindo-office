<?php
$conn = new mysqli('localhost', 'root', '', 'visiyosi_office');
if ($conn->connect_error) {
    die('Connect Error: ' . $conn->connect_error);
}

echo "=== LATEST LAPORAN ===\n";
$res = $conn->query("SELECT id, id_pengaju, tanggal, jenis, progress, status_pekerjaan FROM laporan ORDER BY id DESC LIMIT 10");
print_r($res->fetch_all(MYSQLI_ASSOC));

echo "\n=== DATE RANGES IN LAPORAN ===\n";
$res2 = $conn->query("SELECT MIN(tanggal) as min_tgl, MAX(tanggal) as max_tgl, COUNT(*) as total FROM laporan");
print_r($res2->fetch_assoc());
