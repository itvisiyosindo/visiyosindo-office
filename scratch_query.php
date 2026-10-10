<?php
header('Content-Type: text/plain');
$conn = new mysqli('localhost', 'visiyosi_root', 'q%MDa{uYlcyv', 'visiyosi_office');
if ($conn->connect_error) {
    die('Connect Error: ' . $conn->connect_error);
}

echo "=== USER DIMAS CHANDRA WINTA ===\n";
$resUser = $conn->query("SELECT pengguna_id, nama, email, username, jabatan, id_divisi FROM pengguna WHERE nama LIKE '%dimas%' OR username LIKE '%dimas%'");
$dimasId = null;
while ($u = $resUser->fetch_assoc()) {
    print_r($u);
    if (strpos(strtolower($u['nama']), 'dimas chandra') !== false || strpos(strtolower($u['nama']), 'dimas') !== false) {
        $dimasId = $u['pengguna_id'];
    }
}

echo "\nSelected Dimas ID: $dimasId\n";

if ($dimasId) {
    echo "\n=== JOBDESC DETAIL FOR DIMAS ===\n";
    $resJob = $conn->query("SELECT * FROM jobdesc_detail WHERE id_pengguna = $dimasId OR idPengguna = $dimasId ORDER BY id ASC");
    if ($resJob && $resJob->num_rows > 0) {
        while ($j = $resJob->fetch_assoc()) {
            print_r($j);
        }
    } else {
        echo "No jobdesc_detail found. Checking jobdesc main table...\n";
        $resJob2 = $conn->query("SELECT * FROM jobdesc WHERE id_pengguna = $dimasId");
        while ($j2 = $resJob2->fetch_assoc()) {
            print_r($j2);
            $jId = $j2['id'];
            $resDet = $conn->query("SELECT * FROM jobdesc_detail WHERE id_jobdesc = $jId");
            while ($d = $resDet->fetch_assoc()) {
                print_r($d);
            }
        }
    }

    echo "\n=== RECENT LAPORAN FOR DIMAS ===\n";
    $resLap = $conn->query("SELECT * FROM laporan WHERE id_pengaju = $dimasId ORDER BY id DESC LIMIT 5");
    while ($l = $resLap->fetch_assoc()) {
        print_r($l);
    }
}
