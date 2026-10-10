<?php
$conn = new mysqli('localhost', 'visiyosi_root', 'q%MDa{uYlcyv', 'visiyosi_office');
if ($conn->connect_error) {
    die('Connect Error: ' . $conn->connect_error);
}

echo "=== USER DIMAS CHANDRA WINTA ===\n";
$resUser = $conn->query("SELECT pengguna_id, nama, email, username, jabatan, id_divisi, status, is_active FROM pengguna WHERE nama LIKE '%dimas%' OR username LIKE '%dimas%'");
$dimasId = null;
while ($u = $resUser->fetch_assoc()) {
    print_r($u);
    if (strpos(strtolower($u['nama']), 'dimas chandra') !== false || strpos(strtolower($u['nama']), 'dimas') !== false) {
        $dimasId = $u['pengguna_id'];
    }
}

echo "\nSelected Dimas ID: $dimasId\n";

if ($dimasId) {
    echo "\n=== JOBDESC FOR DIMAS ===\n";
    $resJob = $conn->query("SELECT j.id as id_jobdesc, j.id_pengguna, jd.id as id_detail, jd.deskripsi, jd.no_urut 
                            FROM jobdesc j 
                            JOIN jobdesc_detail jd ON j.id = jd.id_jobdesc 
                            WHERE j.id_pengguna = $dimasId ORDER BY jd.id ASC");
    if ($resJob && $resJob->num_rows > 0) {
        while ($j = $resJob->fetch_assoc()) {
            print_r($j);
        }
    } else {
        echo "No jobdesc found in jobdesc_detail for Dimas. Searching all jobdesc:\n";
        $resJob2 = $conn->query("SELECT * FROM jobdesc WHERE id_pengguna = $dimasId");
        while ($j2 = $resJob2->fetch_assoc()) {
            print_r($j2);
        }
    }

    echo "\n=== LATEST 10 LAPORAN FOR DIMAS ===\n";
    $resLap = $conn->query("SELECT * FROM laporan WHERE id_pengaju = $dimasId ORDER BY id DESC LIMIT 10");
    while ($l = $resLap->fetch_assoc()) {
        print_r($l);
    }
}
