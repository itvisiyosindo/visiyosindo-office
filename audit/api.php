<?php

/**
 * Multi-User Central Synchronization API for Audit System
 * PT Visi Yosindo Medikal
 * Location: https://office.visiyosindo.id/audit/api.php
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$dataDir = __DIR__ . '/data';
$dataFile = $dataDir . '/audit_state.json';

if (!file_exists($dataDir)) {
    @mkdir($dataDir, 0777, true);
}
@chmod($dataDir, 0777);

// Auto-detect CodeIgniter Database Config
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'visiyosi_office';

$ciDbFile = dirname(__DIR__) . '/application/config/database.php';
if (file_exists($ciDbFile)) {
    if (!defined('BASEPATH')) {
        define('BASEPATH', true);
    }
    @include($ciDbFile);
    if (isset($db) && is_array($db) && isset($db['default']) && is_array($db['default'])) {
        $dbHost = !empty($db['default']['hostname']) ? $db['default']['hostname'] : $dbHost;
        $dbUser = !empty($db['default']['username']) ? $db['default']['username'] : $dbUser;
        $dbPass = isset($db['default']['password']) ? $db['default']['password'] : $dbPass;
        $dbName = !empty($db['default']['database']) ? $db['default']['database'] : $dbName;
    }
}

// MySQL Connection for Cross-Device Sync
$dbConnected = false;
@mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli && !$mysqli->connect_error) {
    $dbConnected = true;
    @$mysqli->set_charset('utf8mb4');
    @$mysqli->query("CREATE TABLE IF NOT EXISTS audit_state_json (
        id INT AUTO_INCREMENT PRIMARY KEY,
        state_key VARCHAR(50) UNIQUE,
        state_data LONGTEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : 'get_state');

if ($action === 'get_categories') {
    $topik_tiket = [];
    $kategori_barang = [];
    if ($dbConnected) {
        $q1 = $mysqli->query("SELECT * FROM topik_tiket ORDER BY id_topik ASC");
        if ($q1) {
            while ($r = $q1->fetch_assoc()) {
                $topik_tiket[] = $r;
            }
        }
        $q2 = $mysqli->query("SELECT * FROM kategori_barang ORDER BY id_kategori ASC");
        if ($q2) {
            while ($r = $q2->fetch_assoc()) {
                $kategori_barang[] = $r;
            }
        }
    }
    echo json_encode([
        'status' => 'success',
        'topik_tiket' => $topik_tiket,
        'kategori_barang' => $kategori_barang
    ]);
    exit;
}

if ($action === 'list_po_masuk') {
    $rows = [];
    if ($dbConnected) {
        $q = $mysqli->query("SELECT id_po, no_po, tanggal_po, status, status_proses, gm_status, deleted FROM po_masuk ORDER BY id_po DESC LIMIT 10");
        if ($q) {
            while ($r = $q->fetch_assoc()) {
                $rows[] = $r;
            }
        }
    }
    echo json_encode(['status' => 'success', 'rows' => $rows]);
    exit;
}

if ($action === 'delete_target_pos') {
    $affected = 0;
    $res_info = [];
    if ($dbConnected) {
        $q = $mysqli->query("SELECT id_po, no_po, kode_masuk, tanggal_po, status, status_proses FROM po_masuk WHERE id_po IN (10, 11) OR kode_masuk IN ('PO-202611-001', 'PO-202610-010')");
        if ($q) {
            while ($r = $q->fetch_assoc()) {
                $res_info[] = $r;
            }
        }
        $mysqli->query("UPDATE po_masuk SET deleted = 1 WHERE id_po IN (10, 11) OR kode_masuk IN ('PO-202611-001', 'PO-202610-010')");
        $affected = $mysqli->affected_rows;
    }
    echo json_encode([
        'status' => 'success',
        'affected_rows' => $affected,
        'found_rows' => $res_info,
        'message' => 'PO ID 10 dan 11 berhasil dihapus (deleted=1)'
    ]);
    exit;
}

if ($action === 'check_bob') {
    $bob_rows = [];
    if ($dbConnected) {
        $q = $mysqli->query("SELECT pengguna_id, nama, jabatan, email, level FROM pengguna WHERE nama LIKE '%Bob%' OR jabatan LIKE '%Direct%' OR pengguna_id IN (54, 69)");
        if ($q) {
            while ($r = $q->fetch_assoc()) {
                $bob_rows[] = $r;
            }
        }
    }
    echo json_encode(['status' => 'success', 'rows' => $bob_rows]);
    exit;
}

if ($action === 'sync_email_kantor') {
    $accounts = [
        ['pattern' => 'Afrianto', 'email' => 'afrianto@visiyosindo.com'],
        ['pattern' => 'Afylmardopila', 'email' => 'afylmardopila@visiyosindo.com'],
        ['pattern' => 'Azhari Pratama', 'email' => 'azharipratama@visiyosindo.com'],
        ['pattern' => 'Budi Pradikno', 'email' => 'budipradikno@visiyosindo.com'],
        ['pattern' => 'Dimas Chandra', 'email' => 'dimaschandrawinata@visiyosindo.com'],
        ['pattern' => 'Dirangga', 'email' => 'diranggamadali@visiyosindo.com'],
        ['pattern' => 'Fitri Andriani', 'email' => 'fitriandriani@visiyosindo.com'],
        ['pattern' => 'Muammar', 'email' => 'muammaralfha@visiyosindo.com'],
        ['pattern' => 'Mulia', 'email' => 'mulia@visiyosindo.com'],
        ['pattern' => 'Novemby', 'email' => 'novembyardiansyah@visiyosindo.com'],
        ['pattern' => 'Sinta Kartika', 'email' => 'sintakartika@visiyosindo.com'],
        ['pattern' => 'Sukma', 'email' => 'sukmasakinah@visiyosindo.com'],
        ['pattern' => 'Sulistyo', 'email' => 'sulistyoharmoko@visiyosindo.com'],
        ['pattern' => 'Syarifah Annisa', 'email' => 'syarifahannisa@visiyosindo.com'],
        ['pattern' => 'Yuliannisa', 'email' => 'yuliannisaekaputri@visiyosindo.com']
    ];
    $password = 'visiyosindo2014';
    $updated = [];
    if ($dbConnected) {
        foreach ($accounts as $item) {
            $safePattern = '%' . $mysqli->real_escape_string($item['pattern']) . '%';
            $safeEmail = $mysqli->real_escape_string($item['email']);
            $safePass = $mysqli->real_escape_string($password);
            $q = $mysqli->query("SELECT pengguna_id, nama FROM pengguna WHERE nama LIKE '$safePattern' ORDER BY is_active DESC LIMIT 1");
            if ($q && $user = $q->fetch_assoc()) {
                $uid = (int)$user['pengguna_id'];
                $mysqli->query("UPDATE pengguna SET email_kantor = '$safeEmail', password_email_kantor = '$safePass' WHERE pengguna_id = $uid");
                $updated[] = ['id' => $uid, 'nama' => $user['nama'], 'email' => $item['email']];
            }
        }
    }
    echo json_encode(['status' => 'success', 'updated_count' => count($updated), 'updated' => $updated]);
    exit;
}

if ($action === 'get_pbok_info') {
    $pboks = [];
    $details = [];
    if ($dbConnected) {
        $q1 = $mysqli->query("SELECT id_pbok, kode_pbok, id_kat_surat, id_pengguna, alamat, kota_aju, bank, rekening, ats_nama, tgl_pengajuan, created_at, pembayar_pajak, trf_pajak, nml_pajak FROM surat_pbok WHERE kode_pbok LIKE '%497%' OR kode_pbok LIKE '%499%' OR id_pbok IN (2498, 2500) ORDER BY id_pbok ASC");
        if ($q1) {
            while ($r = $q1->fetch_assoc()) {
                $pboks[] = $r;
            }
        }
        $q2 = $mysqli->query("SELECT id, id_pbok, kode_detail, keterangan, estimasi, qty, total, catatan, mata_uang FROM surat_pbok_detail WHERE id_pbok IN (SELECT id_pbok FROM surat_pbok WHERE kode_pbok LIKE '%497%' OR kode_pbok LIKE '%499%' OR id_pbok IN (2498, 2500)) ORDER BY id_pbok ASC, id ASC");
        if ($q2) {
            while ($r = $q2->fetch_assoc()) {
                $details[] = $r;
            }
        }
    }
    echo json_encode(['status' => 'success', 'pboks' => $pboks, 'details' => $details]);
    exit;
}

if ($action === 'apply_pbok_changes') {
    $results = [];
    if ($dbConnected) {
        // Clean up accidental item 4199 on PBOK 1317 from test run
        $mysqli->query("DELETE FROM surat_pbok_detail WHERE id = 4199 AND id_pbok = 1317 AND keterangan = 'ongkos kirim'");

        // 1. PBOK 497/PBOK/FAT/VYM/XI/2026 (id_pbok = 2498): Ubah "cleo pure water 220 ml" -> "aquviva 250 ml", estimasi 28.000, total = qty * 28.000
        $q497 = $mysqli->query("SELECT id_pbok, kode_pbok FROM surat_pbok WHERE id_pbok = 2498 OR (kode_pbok LIKE '%497%' AND kode_pbok LIKE '%2026%') LIMIT 1");
        if ($q497 && $row497 = $q497->fetch_assoc()) {
            $id497 = (int)$row497['id_pbok'];
            $qItem = $mysqli->query("SELECT id, keterangan, qty, estimasi, total FROM surat_pbok_detail WHERE id_pbok = $id497 AND (keterangan LIKE '%cleo%' OR keterangan LIKE '%aquviva%') LIMIT 1");
            if ($qItem && $item = $qItem->fetch_assoc()) {
                $itemId = (int)$item['id'];
                $qty = (float)$item['qty'];
                $newEstimasi = 28000;
                $newTotal = $qty * $newEstimasi;
                $newKet = 'aquviva 250 ml';
                $mysqli->query("UPDATE surat_pbok_detail SET keterangan = '$newKet', estimasi = $newEstimasi, total = $newTotal WHERE id = $itemId");
                $results['pbok_497'] = [
                    'status' => 'updated',
                    'id_pbok' => $id497,
                    'kode_pbok' => $row497['kode_pbok'],
                    'item_id' => $itemId,
                    'old_keterangan' => $item['keterangan'],
                    'old_estimasi' => $item['estimasi'],
                    'old_total' => $item['total'],
                    'new_keterangan' => $newKet,
                    'new_estimasi' => $newEstimasi,
                    'qty' => $qty,
                    'new_total' => $newTotal
                ];
            } else {
                $results['pbok_497'] = ['status' => 'item_not_found', 'id_pbok' => $id497];
            }
        } else {
            $results['pbok_497'] = ['status' => 'pbok_not_found'];
        }

        // 2. PBOK 499/PBOK/FAT/VYM/XI/2026 (id_pbok = 2500): Tambahkan "ongkos kirim" harga satuan 5.000, qty 6, total = 30.000
        $q499 = $mysqli->query("SELECT id_pbok, kode_pbok, mata_uang FROM surat_pbok WHERE id_pbok = 2500 OR (kode_pbok LIKE '%499%' AND kode_pbok LIKE '%2026%') LIMIT 1");
        if ($q499 && $row499 = $q499->fetch_assoc()) {
            $id499 = (int)$row499['id_pbok'];
            $mataUang = !empty($row499['mata_uang']) ? $row499['mata_uang'] : '1';
            
            // Periksa apakah ongkos kirim sudah pernah ditambahkan agar tidak duplikat jika dipanggil ulang
            $checkExisting = $mysqli->query("SELECT id, keterangan, qty, estimasi, total FROM surat_pbok_detail WHERE id_pbok = $id499 AND keterangan LIKE '%ongkos kirim%' LIMIT 1");
            if ($checkExisting && $existingItem = $checkExisting->fetch_assoc()) {
                $existingId = (int)$existingItem['id'];
                $newKet = 'ongkos kirim';
                $newEst = 5000;
                $newQty = 6;
                $newTotal = 30000;
                $mysqli->query("UPDATE surat_pbok_detail SET keterangan = '$newKet', estimasi = $newEst, qty = $newQty, total = $newTotal WHERE id = $existingId");
                $results['pbok_499'] = [
                    'status' => 'updated_existing',
                    'id_pbok' => $id499,
                    'kode_pbok' => $row499['kode_pbok'],
                    'item_id' => $existingId,
                    'keterangan' => $newKet,
                    'estimasi' => $newEst,
                    'qty' => $newQty,
                    'total' => $newTotal
                ];
            } else {
                $stmt = $mysqli->prepare("INSERT INTO surat_pbok_detail (id_pbok, kode_detail, keterangan, estimasi, qty, total, catatan, mata_uang) VALUES (?, 1, 'ongkos kirim', 5000, 6, 30000, '', ?)");
                if ($stmt) {
                    $stmt->bind_param('is', $id499, $mataUang);
                    $stmt->execute();
                    $newId = $mysqli->insert_id;
                    $stmt->close();
                    $results['pbok_499'] = [
                        'status' => 'inserted',
                        'id_pbok' => $id499,
                        'kode_pbok' => $row499['kode_pbok'],
                        'item_id' => $newId,
                        'keterangan' => 'ongkos kirim',
                        'estimasi' => 5000,
                        'qty' => 6,
                        'total' => 30000
                    ];
                } else {
                    $results['pbok_499'] = ['status' => 'insert_failed', 'error' => $mysqli->error];
                }
            }
        } else {
            $results['pbok_499'] = ['status' => 'pbok_not_found'];
        }
    }
    echo json_encode(['status' => 'success', 'results' => $results]);
    exit;
}
    }
    echo json_encode(['status' => 'success', 'results' => $results]);
    exit;
}

if ($action === 'update_no_po_001') {
    $res = [];
    if ($dbConnected) {
        $q = $mysqli->query("SELECT * FROM po_masuk WHERE id_po = 16 OR kode_masuk = 'PO-202611-006' OR no_po = '000' LIMIT 1");
        if ($q && $po = $q->fetch_assoc()) {
            $idPo = (int)$po['id_po'];
            $oldNoPo = $po['no_po'];
            $newNoPo = '001';
            $mysqli->query("UPDATE po_masuk SET no_po = '$newNoPo' WHERE id_po = $idPo");
            $affected = $mysqli->affected_rows;

            // Fetch the updated record to confirm
            $qAfter = $mysqli->query("SELECT * FROM po_masuk WHERE id_po = $idPo");
            $poAfter = $qAfter ? $qAfter->fetch_assoc() : [];

            $res = [
                'status' => 'success',
                'id_po' => $idPo,
                'kode_masuk' => $po['kode_masuk'] ?? '',
                'old_no_po' => $oldNoPo,
                'new_no_po' => $newNoPo,
                'affected_rows' => $affected,
                'po_after' => $poAfter,
                'message' => "No. PO berhasil diubah dari $oldNoPo menjadi $newNoPo (kode_masuk {$po['kode_masuk']} tetap utuh)"
            ];
        } else {
            $res = [
                'status' => 'error', 
                'msg' => 'Data PO ID 16 / PO-202611-006 tidak ditemukan',
                'db_error' => $mysqli->error
            ];
        }
    } else {
        $res = ['status' => 'error', 'msg' => 'DB not connected'];
    }
    echo json_encode($res);
    exit;
}

if ($action === 'save_state') {
    $rawInput = file_get_contents('php://input');
    $inputData = json_decode($rawInput, true);

    if (!$inputData && !empty($_POST)) {
        $inputData = $_POST;
    }

    if ($inputData) {
        $existing = [];
        if ($dbConnected) {
            $res = $mysqli->query("SELECT state_data FROM audit_state_json WHERE state_key = 'main_state'");
            if ($res && $row = $res->fetch_assoc()) {
                $existing = json_decode($row['state_data'], true) ?: [];
            }
        }

        if (empty($existing) && file_exists($dataFile)) {
            $content = file_get_contents($dataFile);
            $existing = json_decode($content, true) ?: [];
        }

        $existingEdited = isset($existing['edited_employees']) && is_array($existing['edited_employees']) ? $existing['edited_employees'] : [];
        $inputEdited    = isset($inputData['edited_employees']) && is_array($inputData['edited_employees']) ? $inputData['edited_employees'] : [];
        // Note: Use array_replace or manual key merge to PRESERVE numeric string keys (like '105')
        $mergedEdited   = array_replace($existingEdited, $inputEdited);

        $existingEditedPboks = isset($existing['edited_pboks']) && is_array($existing['edited_pboks']) ? $existing['edited_pboks'] : [];
        $inputEditedPboks    = isset($inputData['edited_pboks']) && is_array($inputData['edited_pboks']) ? $inputData['edited_pboks'] : [];
        $mergedEditedPboks   = array_replace($existingEditedPboks, $inputEditedPboks);

        $existingDelEmp = isset($existing['deleted_emp_ids']) && is_array($existing['deleted_emp_ids']) ? $existing['deleted_emp_ids'] : [];
        $inputDelEmp    = isset($inputData['deleted_emp_ids']) && is_array($inputData['deleted_emp_ids']) ? $inputData['deleted_emp_ids'] : [];
        $mergedDelEmp   = array_values(array_unique(array_merge($existingDelEmp, $inputDelEmp)));

        $existingDelPbok = isset($existing['deleted_pbok_ids']) && is_array($existing['deleted_pbok_ids']) ? $existing['deleted_pbok_ids'] : [];
        $inputDelPbok    = isset($inputData['deleted_pbok_ids']) && is_array($inputData['deleted_pbok_ids']) ? $inputData['deleted_pbok_ids'] : [];
        $mergedDelPbok   = array_values(array_unique(array_merge($existingDelPbok, $inputDelPbok)));

        $mergedState = [
            'timestamp'        => isset($inputData['timestamp']) ? $inputData['timestamp'] : (time() * 1000),
            'edited_employees' => isset($inputData['edited_employees']) ? $inputData['edited_employees'] : $existingEdited,
            'edited_pboks'     => isset($inputData['edited_pboks']) ? $inputData['edited_pboks'] : $existingEditedPboks,
            'deleted_emp_ids'  => isset($inputData['deleted_emp_ids']) ? $inputData['deleted_emp_ids'] : $existingDelEmp,
            'deleted_pbok_ids' => isset($inputData['deleted_pbok_ids']) ? $inputData['deleted_pbok_ids'] : $existingDelPbok,
            'pbokList'         => isset($inputData['pbokList']) ? $inputData['pbokList'] : (isset($existing['pbokList']) ? $existing['pbokList'] : []),
            'employees'        => isset($inputData['employees']) ? $inputData['employees'] : (isset($existing['employees']) ? $existing['employees'] : []),
            'findings'         => isset($inputData['findings']) ? $inputData['findings'] : (isset($existing['findings']) ? $existing['findings'] : []),
            'plans'            => isset($inputData['plans']) ? $inputData['plans'] : (isset($existing['plans']) ? $existing['plans'] : []),
            'cars'             => isset($inputData['cars']) ? $inputData['cars'] : (isset($existing['cars']) ? $existing['cars'] : []),
            'beritaAcara'      => isset($inputData['beritaAcara']) ? $inputData['beritaAcara'] : (isset($existing['beritaAcara']) ? $existing['beritaAcara'] : []),
            'last_updated'     => date('Y-m-d H:i:s'),
            'updated_by'       => isset($inputData['user']) ? $inputData['user'] : 'Office User'
        ];

        $jsonStr = json_encode($mergedState, JSON_PRETTY_PRINT);

        // Save to File
        @file_put_contents($dataFile, $jsonStr, LOCK_EX);

        // Save to MySQL DB
        if ($dbConnected) {
            $mysqli->query("DELETE FROM audit_state_json WHERE state_key = 'main_state'");
            $stmt = $mysqli->prepare("INSERT INTO audit_state_json (state_key, state_data) VALUES ('main_state', ?)");
            if ($stmt) {
                $stmt->bind_param('s', $jsonStr);
                $stmt->execute();
                $stmt->close();
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Data audit tersinkronisasi terpusat', 'db' => $dbConnected, 'data' => $mergedState]);
        exit;
    }
    echo json_encode(['status' => 'error', 'message' => 'Input data kosong']);
    exit;
}

if ($action === 'purge_state') {
    $emptyState = [
        'timestamp'        => time() * 1000,
        'edited_employees' => (object)[],
        'edited_pboks'     => (object)[],
        'deleted_emp_ids'  => [],
        'deleted_pbok_ids' => [],
        'employees'        => [],
        'pbokList'         => [],
        'findings'         => [],
        'plans'            => [],
        'cars'             => [],
        'beritaAcara'      => [],
        'last_updated'     => date('Y-m-d H:i:s'),
        'updated_by'       => 'System Purge'
    ];
    $jsonStr = json_encode($emptyState, JSON_PRETTY_PRINT);
    @file_put_contents($dataFile, $jsonStr, LOCK_EX);

    if ($dbConnected) {
        $mysqli->query("DELETE FROM audit_state_json WHERE state_key = 'main_state'");
        $stmt = $mysqli->prepare("INSERT INTO audit_state_json (state_key, state_data) VALUES ('main_state', ?)");
        if ($stmt) {
            $stmt->bind_param('s', $jsonStr);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo json_encode(['status' => 'success', 'message' => 'Seluruh data audit berhasil dikosongkan total', 'data' => $emptyState]);
    exit;
}

// Default: get_state
$data = null;
if ($dbConnected) {
    $res = $mysqli->query("SELECT state_data FROM audit_state_json WHERE state_key = 'main_state' ORDER BY id DESC LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $data = json_decode($row['state_data'], true);
    }
}

if (!$data && file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $data = json_decode($content, true);
}

if ($data) {
    echo json_encode(['status' => 'success', 'db' => $dbConnected, 'data' => $data]);
} else {
    echo json_encode(['status' => 'empty', 'db' => $dbConnected, 'data' => null]);
}
