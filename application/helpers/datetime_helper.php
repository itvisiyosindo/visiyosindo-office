<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
function now()
{
	return date("Y-m-d H:i:s");
}

function all_hari($num)
{
	$hari = array('', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
	return $hari[$num];
}

function all_bulan()
{
	return array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
}

function all_bulan_short()
{
	return array('', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Okt', 'Nov', 'Des');
}

function indo_date($date, $day = FALSE)
{
	$all_bulan     = all_bulan();
	$tanggal = date('d', strtotime($date));
	$bulan   = $all_bulan[date('n', strtotime($date))];
	$tahun   = date('Y', strtotime($date));
	if ($day) {
		$hari = all_hari(date('N', strtotime($date)));
		return $hari . ', ' . $tanggal . '-' . $bulan . '-' . $tahun;
	}
	return $tanggal . '-' . $bulan . '-' . $tahun;
}

function date_mont($date, $day = FALSE)
{
	$all_bulan     = all_bulan();
	$tanggal = date('d', strtotime($date));
	$bulan   = $all_bulan[date('n', strtotime($date))];
	$tahun   = date('Y', strtotime($date));
	
	return $bulan . ' ' . $tahun;
}
function indo_dates($date, $day = FALSE)
{
	$all_bulan     = all_bulan();
	$tanggal = date('d', strtotime($date));
	$bulan   = $all_bulan[date('n', strtotime($date))];
	$tahun   = date('Y', strtotime($date));
	if ($day) {
		$hari = all_hari(date('N', strtotime($date)));
		return $hari . ', ' . $tanggal . '-' . $bulan . '-' . $tahun;
	}
	return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

function date_db_format($tgl)
{
	if (empty($tgl)) {
		return null;
	}

	$tgl = trim($tgl);
	$format_list = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'Y/m/d'];

	foreach ($format_list as $format) {
		$date = DateTime::createFromFormat($format, $tgl);
		if ($date instanceof DateTime) {
			$errors = DateTime::getLastErrors();
			if (empty($errors['warning_count']) && empty($errors['error_count'])) {
				return $date->format('Y-m-d');
			}
		}
	}

	$timestamp = strtotime($tgl);
	return $timestamp ? date('Y-m-d', $timestamp) : null;
}

function date_view_format($tgl)
{
	if ($tgl == '0000-00-00') {
		return '00-00-0000';
	}
	return date('d-m-Y', strtotime($tgl));
}

function getRangeWeekMonth($year, $month, $week)
{

	$thisWeek = 1;

	for ($i = 1; $i < $week; $i++) {
		$thisWeek = $thisWeek + 7;
	}

	$currentDay = date('Y-m-d', mktime(0, 0, 0, $month, $thisWeek, $year));

	$sunday = strtotime('sunday this week', strtotime($currentDay));
	$saturday = strtotime('saturday next week', strtotime($currentDay));

	$data = [
		'year' => $year,
		'month' => $month,
		'week' => $week,
		'week_start' => date('Y-m-d', $sunday),
		'week_end' => date('Y-m-d', $saturday)
	];

	return $data;
}

function hariIndo($hariInggris)
{
	switch ($hariInggris) {
		case 'Sunday':
			return 'Minggu';
		case 'Monday':
			return 'Senin';
		case 'Tuesday':
			return 'Selasa';
		case 'Wednesday':
			return 'Rabu';
		case 'Thursday':
			return 'Kamis';
		case 'Friday':
			return 'Jumat';
		case 'Saturday':
			return 'Sabtu';
		default:
			return 'hari tidak valid';
	}
}

function getMonthName($num)
{
	if ($num == 1) {
		$month_name = 'Januari';
	} else if ($num == 2) {
		$month_name = 'Februari';
	} else if ($num == 3) {
		$month_name = 'Maret';
	} else if ($num == 4) {
		$month_name = 'April';
	} else if ($num == 5) {
		$month_name = 'Mei';
	} else if ($num == 6) {
		$month_name = 'Juni';
	} else if ($num == 7) {
		$month_name = 'Juli';
	} else if ($num == 8) {
		$month_name = 'Agustus';
	} else if ($num == 9) {
		$month_name = 'September';
	} else if ($num == 10) {
		$month_name = 'Oktober';
	} else if ($num == 11) {
		$month_name = 'November';
	} else if ($num == 12) {
		$month_name = 'Desember';
	}
	return $month_name;
}

function time_passed($timestamp)
{
	//type cast, current time, difference in timestamps
	$timestamp      = (int) strtotime($timestamp);
	$current_time   = time();
	$diff           = $current_time - $timestamp;

	//intervals in seconds
	$intervals      = array(
		'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60
	);

	//now we just find the difference
	if ($diff == 0) {
		return 'just now';
	}

	if ($diff < 60) {
		return $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
	}

	if ($diff >= 60 && $diff < $intervals['hour']) {
		$diff = floor($diff / $intervals['minute']);
		return $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
	}

	if ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
		$diff = floor($diff / $intervals['hour']);
		return $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
	}

	if ($diff >= $intervals['day'] && $diff < $intervals['week']) {
		$diff = floor($diff / $intervals['day']);
		return $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
	}

	if ($diff >= $intervals['week'] && $diff < $intervals['month']) {
		$diff = floor($diff / $intervals['week']);
		return $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
	}

	if ($diff >= $intervals['month'] && $diff < $intervals['year']) {
		$diff = floor($diff / $intervals['month']);
		return $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
	}

	if ($diff >= $intervals['year']) {
		$diff = floor($diff / $intervals['year']);
		return $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
	}
}

function countDays($start, $end)
{
	$date1 = new DateTime(date('Y-m-d H:i', strtotime($start)));
	$date2 = new DateTime(date('Y-m-d H:i', strtotime($end)));
	$interval = $date1->diff($date2);
	$countText = '';
	if ($interval->y)
		$countText .= $interval->y . ' Thn ';
	if ($interval->m)
		$countText .= $interval->m . ' Bln ';
	if ($interval->d)
		$countText .= $interval->d . ' hari ';
	if ($interval->h)
		$countText .= $interval->h . ' jam ';
	return $countText;
}

function masaKerja($masuk)
{
	$old = new DateTime($masuk);
	$present = new DateTime('now');
	$interval = $present->diff($old);

	echo $interval->format('%Y Tahun %M Bulan %d Hari');
}

function getHari($tanggal){
    $w = date('w', strtotime($tanggal));
    if($w==0){
        $hari='Minggu';    
    }elseif($w==1){
        $hari='Senin';
    }elseif($w==2){
        $hari='Selasa';
    }elseif($w==3){
        $hari='Rabu';
    }elseif($w==4){
        $hari='Kamis';
    }elseif($w==5){
        $hari='Jumat';
    }elseif($w==6){
        $hari='Sabtu';
    }
    return $hari;
}

function masaKerjaBulan($masuk)
{

	$date = date("Y-m-d");
	$timeStart = strtotime($masuk);
	$timeEnd = strtotime("$date");
	// Menambah bulan ini + semua bulan pada tahun sebelumnya
	$numBulan = 1 + (date("Y", $timeEnd) - date("Y", $timeStart)) * 12;
	// menghitung selisih bulan
	$numBulan += date("m", $timeEnd) - date("m", $timeStart);

	return $numBulan;
}

/**
 * Mendapatkan informasi libur (Weekend / Hari Libur Nasional / Cuti Bersama Indonesia)
 * @param string|int $date Tanggal (format Y-m-d atau timestamp)
 * @return array{is_libur: bool, tipe: string, nama: string}
 */
function get_info_hari_libur($date)
{
	if (empty($date)) {
		return ['is_libur' => false, 'tipe' => 'kerja', 'nama' => ''];
	}

	$time = is_numeric($date) ? (int)$date : strtotime($date);
	if (!$time) {
		return ['is_libur' => false, 'tipe' => 'kerja', 'nama' => ''];
	}

	$ymd = date('Y-m-d', $time);
	$w = (int)date('w', $time); // 0 = Minggu, 6 = Sabtu

	// Cek Hari Libur buatan perusahaan di DB jika ada
	if (function_exists('get_instance')) {
		$CI = &get_instance();
		if (isset($CI->db)) {
			$db_libur = $CI->db->select('ket')->get_where('absensi_config_libur', ['tgl' => $ymd])->row();
			if ($db_libur && !empty($db_libur->ket)) {
				return [
					'is_libur' => true,
					'tipe' => 'custom',
					'nama' => 'Libur Perusahaan (' . $db_libur->ket . ')'
				];
			}
		}
	}

	// Daftar Hari Libur Nasional & Cuti Bersama Indonesia (2024 - 2026)
	static $holidays = [
		// 2024
		'2024-01-01' => 'Tahun Baru 2024 Masehi',
		'2024-02-08' => 'Isra Mikraj Nabi Muhammad SAW',
		'2024-02-09' => 'Cuti Bersama Imlek',
		'2024-02-10' => 'Tahun Baru Imlek 2575 Kongzili',
		'2024-03-11' => 'Hari Suci Nyepi 1946',
		'2024-03-12' => 'Cuti Bersama Nyepi',
		'2024-03-29' => 'Wafat Isa Al Masih',
		'2024-03-31' => 'Hari Paskah',
		'2024-04-08' => 'Cuti Bersama Idul Fitri 1445 H',
		'2024-04-09' => 'Cuti Bersama Idul Fitri 1445 H',
		'2024-04-10' => 'Hari Raya Idul Fitri 1445 H',
		'2024-04-11' => 'Hari Raya Idul Fitri 1445 H',
		'2024-04-12' => 'Cuti Bersama Idul Fitri 1445 H',
		'2024-04-15' => 'Cuti Bersama Idul Fitri 1445 H',
		'2024-05-01' => 'Hari Buruh Internasional',
		'2024-05-09' => 'Kenaikan Isa Al Masih',
		'2024-05-10' => 'Cuti Bersama Kenaikan Isa Al Masih',
		'2024-05-23' => 'Hari Raya Waisak 2568 BE',
		'2024-05-24' => 'Cuti Bersama Waisak',
		'2024-06-01' => 'Hari Lahir Pancasila',
		'2024-06-17' => 'Hari Raya Idul Adha 1445 H',
		'2024-06-18' => 'Cuti Bersama Idul Adha 1445 H',
		'2024-07-07' => 'Tahun Baru Islam 1446 H',
		'2024-08-17' => 'Hari Kemerdekaan RI',
		'2024-09-16' => 'Maulid Nabi Muhammad SAW',
		'2024-12-25' => 'Hari Raya Natal',
		'2024-12-26' => 'Cuti Bersama Natal',

		// 2025
		'2025-01-01' => 'Tahun Baru 2025 Masehi',
		'2025-01-27' => 'Isra Mikraj Nabi Muhammad SAW',
		'2025-01-28' => 'Cuti Bersama Imlek',
		'2025-01-29' => 'Tahun Baru Imlek 2576 Kongzili',
		'2025-03-28' => 'Cuti Bersama Nyepi',
		'2025-03-29' => 'Hari Suci Nyepi 1947',
		'2025-03-31' => 'Hari Raya Idul Fitri 1446 H',
		'2025-04-01' => 'Hari Raya Idul Fitri 1446 H',
		'2025-04-02' => 'Cuti Bersama Idul Fitri 1446 H',
		'2025-04-03' => 'Cuti Bersama Idul Fitri 1446 H',
		'2025-04-04' => 'Cuti Bersama Idul Fitri 1446 H',
		'2025-04-07' => 'Cuti Bersama Idul Fitri 1446 H',
		'2025-04-18' => 'Wafat Yesus Kristus',
		'2025-04-20' => 'Hari Paskah',
		'2025-05-01' => 'Hari Buruh Internasional',
		'2025-05-12' => 'Hari Raya Waisak 2569 BE',
		'2025-05-13' => 'Cuti Bersama Waisak',
		'2025-05-29' => 'Kenaikan Yesus Kristus',
		'2025-05-30' => 'Cuti Bersama Kenaikan Yesus Kristus',
		'2025-06-01' => 'Hari Lahir Pancasila',
		'2025-06-06' => 'Hari Raya Idul Adha 1446 H',
		'2025-06-09' => 'Cuti Bersama Idul Adha 1446 H',
		'2025-06-27' => 'Tahun Baru Islam 1447 H',
		'2025-08-17' => 'Hari Kemerdekaan RI',
		'2025-09-05' => 'Maulid Nabi Muhammad SAW',
		'2025-12-25' => 'Hari Raya Natal',
		'2025-12-26' => 'Cuti Bersama Natal',

		// 2026
		'2026-01-01' => 'Tahun Baru 2026 Masehi',
		'2026-01-16' => 'Isra Mikraj Nabi Muhammad SAW',
		'2026-02-16' => 'Cuti Bersama Imlek',
		'2026-02-17' => 'Tahun Baru Imlek 2577 Kongzili',
		'2026-03-18' => 'Cuti Bersama Nyepi',
		'2026-03-19' => 'Hari Suci Nyepi 1948',
		'2026-03-20' => 'Cuti Bersama Idul Fitri 1447 H',
		'2026-03-21' => 'Hari Raya Idul Fitri 1447 H',
		'2026-03-22' => 'Hari Raya Idul Fitri 1447 H',
		'2026-03-23' => 'Cuti Bersama Idul Fitri 1447 H',
		'2026-03-24' => 'Cuti Bersama Idul Fitri 1447 H',
		'2026-04-03' => 'Wafat Yesus Kristus',
		'2026-04-05' => 'Hari Paskah',
		'2026-05-01' => 'Hari Buruh Internasional',
		'2026-05-14' => 'Kenaikan Yesus Kristus',
		'2026-05-15' => 'Cuti Bersama Kenaikan Yesus Kristus',
		'2026-05-27' => 'Hari Raya Idul Adha 1447 H',
		'2026-05-28' => 'Cuti Bersama Idul Adha 1447 H',
		'2026-05-31' => 'Hari Raya Waisak 2570 BE',
		'2026-06-01' => 'Hari Lahir Pancasila',
		'2026-06-16' => 'Tahun Baru Islam 1448 H',
		'2026-08-17' => 'Hari Kemerdekaan RI',
		'2026-08-25' => 'Maulid Nabi Muhammad SAW',
		'2026-12-24' => 'Cuti Bersama Natal',
		'2026-12-25' => 'Hari Raya Natal',
	];

	// Cek apakah tanggal merah / libur nasional
	if (isset($holidays[$ymd])) {
		return [
			'is_libur' => true,
			'tipe' => 'nasional',
			'nama' => 'Libur Nasional (' . $holidays[$ymd] . ')'
		];
	}

	// Cek Weekend (Sabtu / Minggu)
	if ($w == 6) {
		return [
			'is_libur' => true,
			'tipe' => 'weekend',
			'nama' => 'Akhir Pekan (Sabtu)'
		];
	}

	if ($w == 0) {
		return [
			'is_libur' => true,
			'tipe' => 'weekend',
			'nama' => 'Akhir Pekan (Minggu)'
		];
	}

	return [
		'is_libur' => false,
		'tipe' => 'kerja',
		'nama' => ''
	];
}

