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
    $target_pos = ['PO-202611-001', 'PO-202610-010'];
    $res_info = [];
    $affected = 0;
    if ($dbConnected) {
        $escaped = array_map(function($p) use ($mysqli) {
            return "'" . $mysqli->real_escape_string($p) . "'";
        }, $target_pos);
        $inClause = implode(',', $escaped);
        $q = $mysqli->query("SELECT id_po, no_po, status, status_proses, gm_status, deleted FROM po_masuk WHERE no_po IN ($inClause)");
        if ($q) {
            while ($r = $q->fetch_assoc()) {
                $res_info[] = $r;
            }
        }
        $mysqli->query("UPDATE po_masuk SET deleted = 1 WHERE no_po IN ($inClause)");
        $affected = $mysqli->affected_rows;
    }
    echo json_encode([
        'status' => 'success',
        'affected_rows' => $affected,
        'found_rows' => $res_info,
        'message' => 'PO PO-202611-001 dan PO-202610-010 berhasil dihapus (deleted=1)'
    ]);
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
