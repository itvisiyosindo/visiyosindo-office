<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Konfigurasi Notifikasi Laporan Absensi Harian (Fonnte Terpisah)
|--------------------------------------------------------------------------
| Konfigurasi ini terpisah dari whatsapp_helper.php & API notifikasi
| database yang sudah ada agar tidak mengganggu notifikasi/prompt lain.
|
| Anda dapat mengisi nilai melalui file .env:
|   FONNTE_ABSENSI_TOKEN=...
|   FONNTE_ABSENSI_GROUP_ID=...   (opsional, otomatis dicari dari nama grup)
|   FONNTE_ABSENSI_CRON_KEY=...   (opsional, token pengaman URL cron)
*/

$envToken = getenv('FONNTE_ABSENSI_TOKEN');
if ($envToken === false && isset($_ENV['FONNTE_ABSENSI_TOKEN'])) {
    $envToken = $_ENV['FONNTE_ABSENSI_TOKEN'];
}

$envGroupId = getenv('FONNTE_ABSENSI_GROUP_ID');
if ($envGroupId === false && isset($_ENV['FONNTE_ABSENSI_GROUP_ID'])) {
    $envGroupId = $_ENV['FONNTE_ABSENSI_GROUP_ID'];
}

$envCronKey = getenv('FONNTE_ABSENSI_CRON_KEY');
if ($envCronKey === false && isset($_ENV['FONNTE_ABSENSI_CRON_KEY'])) {
    $envCronKey = $_ENV['FONNTE_ABSENSI_CRON_KEY'];
}

$config['fonnte_absensi_token']           = $envToken ? trim((string)$envToken) : '';
$config['fonnte_absensi_sender']          = '0895410953259';
$config['fonnte_absensi_group_name']      = 'Kepegawaian visi/vym group';
$config['fonnte_absensi_group_id']        = $envGroupId ? trim((string)$envGroupId) : '';
$config['fonnte_absensi_cron_key']        = $envCronKey ? trim((string)$envCronKey) : '';

$config['fonnte_absensi_api_url']         = 'https://api.fonnte.com/send';
$config['fonnte_absensi_groups_url']      = 'https://api.fonnte.com/get-whatsapp-group';
$config['fonnte_absensi_fetch_group_url'] = 'https://api.fonnte.com/fetch-group';

// Karyawan yang dikecualikan dari laporan belum absen pulang (17:15)
$config['fonnte_absensi_excluded_pulang'] = [
    'Afrianto',
    'Boddy Cahayadi',
    'Muammar Alfha Azhari',
    'Sulistyo Harmoko',
];
