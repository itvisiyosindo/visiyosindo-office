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
            $stmt = $mysqli->prepare("INSERT INTO audit_state_json (state_key, state_data) VALUES ('main_state', ?) ON DUPLICATE KEY UPDATE state_data = ?");
            if ($stmt) {
                $stmt->bind_param('ss', $jsonStr, $jsonStr);
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
        $stmt = $mysqli->prepare("INSERT INTO audit_state_json (state_key, state_data) VALUES ('main_state', ?) ON DUPLICATE KEY UPDATE state_data = ?");
        if ($stmt) {
            $stmt->bind_param('ss', $jsonStr, $jsonStr);
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
    $res = $mysqli->query("SELECT state_data FROM audit_state_json WHERE state_key = 'main_state'");
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
