<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "office_db_dev");

$dataFile = __DIR__ . '/data/audit_state.json';

if (file_exists($dataFile)) {
    $jsonStr = file_get_contents($dataFile);
    $mysqli->query("CREATE TABLE IF NOT EXISTS audit_state_json (
        id INT AUTO_INCREMENT PRIMARY KEY,
        state_key VARCHAR(50) UNIQUE,
        state_data LONGTEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $stmt = $mysqli->prepare("INSERT INTO audit_state_json (state_key, state_data) VALUES ('main_state', ?) ON DUPLICATE KEY UPDATE state_data = ?");
    if ($stmt) {
        $stmt->bind_param('ss', $jsonStr, $jsonStr);
        $stmt->execute();
        $stmt->close();
        echo "Successfully synced audit_state.json into MySQL!\n";
    }
} else {
    echo "File $dataFile not found.\n";
}
