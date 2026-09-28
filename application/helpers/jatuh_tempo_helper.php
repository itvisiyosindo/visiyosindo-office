<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helper untuk Kalkulasi Tanggal Jatuh Tempo Pembayaran Tagihan Ekspedisi
 * 
 * LOGIC PEMBAYARAN:
 * 1. Tanggal Invoice + Payment Term (dari PKS) = Tanggal Kontrak Jatuh Tempo
 * 2. Pembayaran dilakukan pada tanggal 25 setiap bulannya
 * 3. Jika Tanggal Kontrak Jatuh Tempo <= 25, bayar di tanggal 25 bulan yang sama
 * 4. Jika Tanggal Kontrak Jatuh Tempo > 25, bayar di tanggal 25 bulan berikutnya
 * 
 * CONTOH:
 * - Invoice: 09 Des 2025, Payment: 30 hari
 * - Kontrak Jatuh Tempo: 08 Jan 2026
 * - Pembayaran: 25 Jan 2026 (karena 08 <= 25)
 * 
 * - Invoice: 30 Des 2025, Payment: 30 hari
 * - Kontrak Jatuh Tempo: 29 Jan 2026
 * - Pembayaran: 25 Feb 2026 (karena 29 > 25)
 */

/**
 * Hitung Tanggal Jatuh Tempo Pembayaran
 * 
 * @param string $tanggal_invoice Format: Y-m-d
 * @param string $payment_term Format: "30 hari", "14 hari", dll
 * @param string $status_bayar 'sudah_dibayar' atau 'belum_dibayar'
 * @return array [
 *   'tanggal_kontrak_jatuh_tempo' => '2026-01-08',
 *   'tanggal_jatuh_tempo' => '2026-01-25',
 *   'status_jatuh_tempo' => 'Belum Jatuh Tempo',
 *   'hari_tersisa' => 19
 * ]
 */
function hitung_tanggal_jatuh_tempo($tanggal_invoice, $payment_term, $status_bayar = 'belum_dibayar')
{
    // Default return jika data tidak lengkap
    $default = [
        'tanggal_kontrak_jatuh_tempo' => null,
        'tanggal_jatuh_tempo' => null,
        'status_jatuh_tempo' => 'Tidak Ada Info Payment',
        'hari_tersisa' => null
    ];

    // Jika sudah dibayar, langsung return
    if ($status_bayar == 'sudah_dibayar') {
        $default['status_jatuh_tempo'] = 'Sudah Dibayar';
        return $default;
    }

    // Validasi input
    if (empty($tanggal_invoice) || empty($payment_term)) {
        return $default;
    }

    // Extract angka dari payment_term (contoh: "30 hari" -> 30)
    preg_match('/(\d+)/', $payment_term, $matches);
    if (empty($matches[1])) {
        return $default;
    }

    $payment_days = (int)$matches[1];

    try {
        // 1. Hitung Tanggal Kontrak Jatuh Tempo (Invoice + Payment Term)
        $dt_invoice = new DateTime($tanggal_invoice);
        $dt_kontrak_jatuh_tempo = clone $dt_invoice;
        $dt_kontrak_jatuh_tempo->modify("+{$payment_days} days");

        $tanggal_kontrak_jatuh_tempo = $dt_kontrak_jatuh_tempo->format('Y-m-d');
        $day_of_month = (int)$dt_kontrak_jatuh_tempo->format('d');

        // 2. Tentukan Tanggal Pembayaran (tanggal 25)
        $dt_pembayaran = clone $dt_kontrak_jatuh_tempo;

        if ($day_of_month <= 25) {
            // Jika kontrak jatuh tempo tanggal 1-25, bayar di tanggal 25 bulan yang sama
            $dt_pembayaran->setDate(
                (int)$dt_kontrak_jatuh_tempo->format('Y'),
                (int)$dt_kontrak_jatuh_tempo->format('m'),
                25
            );
        } else {
            // Jika kontrak jatuh tempo tanggal 26-31, bayar di tanggal 25 bulan berikutnya
            $dt_pembayaran->modify('first day of next month');
            $dt_pembayaran->setDate(
                (int)$dt_pembayaran->format('Y'),
                (int)$dt_pembayaran->format('m'),
                25
            );
        }

        $tanggal_jatuh_tempo = $dt_pembayaran->format('Y-m-d');

        // 3. Hitung Status Jatuh Tempo
        $today = new DateTime();
        $today->setTime(0, 0, 0); // Reset time untuk perbandingan tanggal saja
        $dt_pembayaran->setTime(0, 0, 0);

        // Hitung selisih hari
        $interval = $today->diff($dt_pembayaran);
        $hari_tersisa = (int)$interval->format('%R%a'); // +/- hari

        // Tentukan status
        $status = '';

        if ($hari_tersisa > 7) {
            // Lebih dari 7 hari lagi
            $status = 'Belum Jatuh Tempo';
        } elseif ($hari_tersisa >= 1 && $hari_tersisa <= 7) {
            // 1-7 hari lagi (warning)
            $status = 'Akan Jatuh Tempo';
        } elseif ($hari_tersisa == 0) {
            // Hari ini
            $status = 'Jatuh Tempo Hari Ini';
        } else {
            // Sudah lewat (negatif)
            $status = 'Sudah Jatuh Tempo';
        }

        return [
            'tanggal_kontrak_jatuh_tempo' => $tanggal_kontrak_jatuh_tempo,
            'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
            'status_jatuh_tempo' => $status,
            'hari_tersisa' => $hari_tersisa
        ];
    } catch (Exception $e) {
        // Jika ada error dalam perhitungan tanggal
        return $default;
    }
}

/**
 * Format Pesan Alert Jatuh Tempo
 * 
 * @param array|object $jatuh_tempo_data Data dari hitung_tanggal_jatuh_tempo() atau query result object
 * @return string HTML alert message
 */
function get_alert_jatuh_tempo_message($jatuh_tempo_data)
{
    // Support both array and object
    $status = is_array($jatuh_tempo_data)
        ? $jatuh_tempo_data['status_jatuh_tempo']
        : $jatuh_tempo_data->status_jatuh_tempo;

    $tanggal_jatuh_tempo = is_array($jatuh_tempo_data)
        ? $jatuh_tempo_data['tanggal_jatuh_tempo']
        : $jatuh_tempo_data->tanggal_jatuh_tempo;

    $hari_tersisa = is_array($jatuh_tempo_data)
        ? $jatuh_tempo_data['hari_tersisa']
        : $jatuh_tempo_data->hari_tersisa;

    if ($status == 'Sudah Dibayar') {
        return 'Pembayaran telah selesai dilakukan';
    }

    if ($status == 'Tidak Ada Info Payment') {
        return 'Informasi payment term tidak tersedia';
    }

    if (!$tanggal_jatuh_tempo) {
        return 'Tidak dapat menghitung tanggal jatuh tempo';
    }

    $tgl_formatted = date('d M Y', strtotime($tanggal_jatuh_tempo));

    switch ($status) {
        case 'Sudah Jatuh Tempo':
            $days_late = abs($hari_tersisa);
            return "Tagihan sudah jatuh tempo sejak {$days_late} hari yang lalu (tanggal {$tgl_formatted})";

        case 'Jatuh Tempo Hari Ini':
            return "Tagihan jatuh tempo HARI INI (tanggal {$tgl_formatted})";

        case 'Akan Jatuh Tempo':
            return "Tagihan akan jatuh tempo dalam {$hari_tersisa} hari (tanggal {$tgl_formatted}). Mohon diingat dan periksa secara berkala.";

        case 'Belum Jatuh Tempo':
            return "Tagihan belum jatuh tempo. Pembayaran pada tanggal {$tgl_formatted} ({$hari_tersisa} hari lagi)";

        default:
            return "Status: {$status}";
    }
}

/**
 * Get Badge Class untuk Status Jatuh Tempo
 * 
 * @param string $status
 * @return string CSS class
 */
function get_alert_jatuh_tempo_class($status)
{
    switch ($status) {
        case 'Sudah Dibayar':
            return 'alert-success'; // Hijau

        case 'Sudah Jatuh Tempo':
            return 'alert-danger'; // Merah

        case 'Jatuh Tempo Hari Ini':
            return 'alert-danger'; // Merah

        case 'Akan Jatuh Tempo':
            return 'alert-warning'; // Kuning/Orange

        case 'Belum Jatuh Tempo':
            return 'alert-info'; // Biru

        case 'Tidak Ada Info Payment':
        default:
            return 'alert-secondary'; // Abu-abu
    }
}

/**
 * Get Icon untuk Status Jatuh Tempo
 * 
 * @param string $status
 * @return string Font Awesome icon class
 */
function get_alert_jatuh_tempo_icon($status)
{
    switch ($status) {
        case 'Sudah Dibayar':
            return 'fas fa-check-circle';

        case 'Sudah Jatuh Tempo':
            return 'fas fa-exclamation-triangle';

        case 'Jatuh Tempo Hari Ini':
            return 'fas fa-exclamation-circle';

        case 'Akan Jatuh Tempo':
            return 'fas fa-clock';

        case 'Belum Jatuh Tempo':
            return 'fas fa-calendar-check';

        case 'Tidak Ada Info Payment':
        default:
            return 'fas fa-info-circle';
    }
}
