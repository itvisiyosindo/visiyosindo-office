<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title_pdf; ?></title>
    <style>
        #table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #table td,
        #table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            font-size: 10Px;
        }

        #table td {
            font-size: 9Px;
        }

        .tandatangan {
            text-align: center;
            margin-left: 600px;
        }

        .tandatangan2 {
            text-align: left;
        }

        .tandatangan3 {
            text-align: left;
            margin-left: 300px;
        }
    </style>
</head>

<body>
    <img src="assets/img/kop_baru.jpg" width="100%" height="15%" />
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
        <h3>Periode : <?= $periode ?></h3>
    </div>
    <div>
        <table id="table" style="width:100%">
            <thead class="text-center">
                <tr>
                    <th> No </th>
                    <th> Nama Karyawan</th>
                    <th> Lama Kerja</th>
                    <th width="7%"> Gaji Pokok </th>
                    <th> Pendapatan Lainnya</th>
                    <th> Potongan BPJS Kesehatan</th>
                    <th> Potongan BPJS TK</th>
                    <th> Potongan Lainnya</th>
                    <th> Penghasilan Sebelum Pajak</th>
                    <th> Pajak PPH21</th>
                    <th> Penghasilan Setelah Pajak</th>
                    <th> Nomor Rekening</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $x = 1;
                $sumgapok = 0;
                $sumKinerja = 0;
                $sumKonsumsi = 0;
                $sumAll = 0;
                $sumPajak = 0;
                $sumbayar = 0;
                $sumKomunikasi = 0;
                $sumKomisi = 0;
                foreach ($dt as $row) {

                    //variabel ceklis tanpa tunjanan
                    $s_karyawan = $row->status_karyawan;
                    $s_jabatan = $row->terima_tunjangan_jabatan;
                    $s_kinerja = $row->terima_tunjangan_kinerja;
                    $s_konsumsi = $row->terima_tunjangan_konsumsi;
                    $s_komunikasi = $row->terima_tunjangan_komunikasi;
                    $s_transportasi = $row->terima_tunjangan_transportasi;
                    $s_bbm = $row->terima_tunjangan_bbm;
                    $s_tunjanganlain = $row->id_pendapatan_lain;
                    $s_thr = $row->terima_tunjangan_raya;
                    $s_bonus = $row->terima_bonus_tahunan;

                    if ($s_jabatan == 1) {
                        $tunjanganJabatan = tunjanganJabatan($row->pengguna_id);
                    } else {
                        $tunjanganJabatan = 0;
                    }
                    if ($s_kinerja == 1) {
                        $tunjanganKinerja = tunjangan($row->pengguna_id, $month1)['total_kinerjadinas'];
                    } else {
                        $tunjanganKinerja = 0;
                    }
                    if ($s_konsumsi == 1) {
                        $tunjanganKonsumsi = tunjangan($row->pengguna_id, $month1)['total_konsumsidinas'];
                    } else {
                        $tunjanganKonsumsi = 0;
                    }
                    if ($s_komunikasi == 1) {
                        $tunjanganKomunikasi = tunjangan($row->pengguna_id, $month1)['total_komunikasi'];
                    } else {
                        $tunjanganKomunikasi = 0;
                    }
                    if ($s_transportasi == 1) {
                        $tunjanganTransportasi = tunjangan($row->pengguna_id, $month1)['total_transportasi'];
                    } else {
                        $tunjanganTransportasi = 0;
                    }
                    if ($s_bbm == 1) {
                        $tunjanganBbm = tunjangan($row->pengguna_id, $month1)['total_bbm'];
                    } else {
                        $tunjanganBbm = 0;
                    }

                    //dari Salary Tidak Tetap
                    if (($s_tunjanganlain) <= 0) {
                        $Pendapatanlain = 0;
                    } else {
                        $Pendapatanlain = tunjangan($row->pengguna_id, $month1)['total_tunjanganlain'];
                    }

                    $Potongan_lain_tunjangan = tunjangan($row->pengguna_id, $month1)['total_potongan'];

                    if ($row->pengguna_id == 94) {
                        $konsumsiBiasa = tunjangan($row->pengguna_id, $month1)['total_konsumsi_Biasa'];
                        $konsumsiLibur = tunjangan($row->pengguna_id, $month1)['total_konsumsi_Libur'];
                        $tunjanganKonsumsi = $konsumsiBiasa + ($konsumsiLibur * 3);

                        $kinerjaBiasa = tunjangan($row->pengguna_id, $month1)['total_kinerja_Biasa'];
                        $kinerjaLibur = tunjangan($row->pengguna_id, $month1)['total_kinerja_Libur'];
                        $tunjanganKinerja = $kinerjaLibur * 3;
                    }

                    $tunjanganraya = 0;
                    $bonusTahunan =  0;

                    $nama = $row->nama;
                    $hari_ini = date('Y-m-d');
                    $tanggal_masuk = new DateTime($row->tgl_masuk); 
                    $tanggal_hari_ini = new DateTime($hari_ini); 
                    $selisih = $tanggal_masuk->diff($tanggal_hari_ini); 
                    $tahun = $selisih->y;
                    $bulan = $selisih->m;
                    $hari = $selisih->d;

                    if ($tahun == 0) {
                        $lama = $bulan . ' Bulan ' . $hari . ' Hari';
                    } else {
                        $lama = $tahun . ' Tahun ' . $bulan . ' Bulan ' . $hari . ' Hari';
                    }

                    $gapok = gajiPokok($row->pengguna_id);
                    $no_rek   =  $row->no_rek ?  $row->no_rek : '-';
                    $idPengguna = $row->pengguna_id;

                    if ($idPengguna == '23' || $idPengguna == '33' || $idPengguna == '25' || $idPengguna == '6' || $idPengguna == '14' || $idPengguna == '15' || $idPengguna == '29' || $idPengguna == '64' || $idPengguna == '7' || $idPengguna == '75' || $idPengguna == '106' || $idPengguna == '99' || $idPengguna == '92') {
                        $potonganBPJStk = dasarBPJSKetenagakerjaan($row->pengguna_id) * 3 / 100;
                    } else {
                        $potonganBPJSexe = dasarBPJSKetenagakerjaan($row->pengguna_id) * 2 / 100;
                        $potonganBPJStk = dasarBPJSKetenagakerjaan($row->pengguna_id) * 2 / 100;
                    }

                    if ($idPengguna == '75') {
                        $potonganBPJSkes = dasarBPJSKesehatan($row->pengguna_id) * 2 / 100;
                    } else {
                        $potonganBPJSkes = dasarBPJSKesehatan($row->pengguna_id) * 1 / 100;
                    }
                    $tunjanganKomisi = komisi($row->pengguna_id, $month1);
                    $id_status_kawin = $row->id_status_perkawinan;

                    $pendapatan_lain = pendapatan_lain($row->pengguna_id);
                    $potongan_lain = potongan_lain($row->pengguna_id);

                    if ($idPengguna == '23') {
                        $BpjsPerusahaan = (($gapok + 4345000) * 0.04) > 480000 ? 480000 : (($gapok + 4345000) * 0.04);
                    } else if ($idPengguna == '64') {
                        $BpjsPerusahaan = (($gapok + 1000000) * 0.04) > 480000 ? 480000 : (($gapok + 1000000) * 0.04);
                    } else if ($idPengguna == '29') {
                        $BpjsPerusahaan = (($gapok + 1000000) * 0.04) > 480000 ? 480000 : (($gapok + 750000) * 0.04);
                    } else if ($idPengguna == '106') {
                        $BpjsPerusahaan = (($gapok + 1000000) * 0.04) > 480000 ? 480000 : (($gapok + 1500000) * 0.04);
                    } else if ($idPengguna == '744') {
                        $BpjsPerusahaan = (($gapok + 1000000) * 0.04) > 480000 ? 480000 : (($gapok + 2450000) * 0.04);
                    } else if ($idPengguna == '751') {
                        $BpjsPerusahaan = 0;
                    } else {
                        $BpjsPerusahaan = ($gapok * 0.04) > 480000 ? 480000 : ($gapok * 0.04);
                    }

                    if ($idPengguna == '745' || $idPengguna == '54' || $idPengguna == '747' || $idPengguna == '751') {
                        $dariPerusahaan = $BpjsPerusahaan;
                    } else if ($idPengguna == '23') {
                        $dariPerusahaan = (($gapok + 4345000) * 0.54 / 100) + $BpjsPerusahaan;
                    } else if ($idPengguna == '64') {
                        $dariPerusahaan = (($gapok + 1000000) * 0.54 / 100) + $BpjsPerusahaan;
                    } else if ($idPengguna == '744') {
                        $dariPerusahaan = (($gapok + 2450000) * 0.54 / 100) + $BpjsPerusahaan;
                    } else if ($idPengguna == '29') {
                        $dariPerusahaan = (($gapok + 750000) * 0.54 / 100) + $BpjsPerusahaan;
                    } else if ($idPengguna == '106') {
                        $dariPerusahaan = (($gapok + 1500000) * 0.54 / 100) + $BpjsPerusahaan;
                    } else if ($idPengguna == '756') {
                        $dariPerusahaan = '0';
                    } else {
                        $dariPerusahaan = ($gapok * 0.54 / 100) + $BpjsPerusahaan;
                    }

                    if ($idPengguna == '755') {
                        $salary_sebulan   =  $gapok;
                    } else {
                        $salary_sebulan   =  ($gapok + $pendapatan_lain + $tunjanganJabatan + $tunjanganKinerja + $tunjanganKonsumsi + $tunjanganKomunikasi + $tunjanganraya + $bonusTahunan + $tunjanganKomisi + $Pendapatanlain + $tunjanganTransportasi + $tunjanganBbm + $dariPerusahaan) - $Potongan_lain_tunjangan;
                    }

                    /* Rumus pajak pph 21 */
                    if ($id_status_kawin == 1 || $id_status_kawin == 2 || $id_status_kawin == 5) {
                        if ($salary_sebulan > 0 && $salary_sebulan <= 5400000) { $salary_pph21 = 0; } 
                        elseif ($salary_sebulan > 5400000 && $salary_sebulan <= 5650000) { $salary_pph21 = $salary_sebulan * (0.25 / 100); } 
                        elseif ($salary_sebulan > 5650000 && $salary_sebulan <= 5950000) { $salary_pph21 = $salary_sebulan * (0.5 / 100); } 
                        elseif ($salary_sebulan > 5950000 && $salary_sebulan <= 6300000) { $salary_pph21 = $salary_sebulan * (0.75 / 100); } 
                        elseif ($salary_sebulan > 6300000 && $salary_sebulan <= 6750000) { $salary_pph21 = $salary_sebulan * (1 / 100); } 
                        elseif ($salary_sebulan > 6750000 && $salary_sebulan <= 7500000) { $salary_pph21 = $salary_sebulan * (1.25 / 100); } 
                        elseif ($salary_sebulan > 7500000 && $salary_sebulan <= 8550000) { $salary_pph21 = $salary_sebulan * (1.5 / 100); } 
                        elseif ($salary_sebulan > 8550000 && $salary_sebulan <= 9650000) { $salary_pph21 = $salary_sebulan * (1.75 / 100); } 
                        elseif ($salary_sebulan > 9650000 && $salary_sebulan <= 10050000) { $salary_pph21 = $salary_sebulan * (2 / 100); } 
                        elseif ($salary_sebulan > 10050000 && $salary_sebulan <= 10350000) { $salary_pph21 = $salary_sebulan * (2.25 / 100); } 
                        elseif ($salary_sebulan > 10350000 && $salary_sebulan <= 10700000) { $salary_pph21 = $salary_sebulan * (2.5 / 100); } 
                        elseif ($salary_sebulan > 10700000 && $salary_sebulan <= 11050000) { $salary_pph21 = $salary_sebulan * (3 / 100); } 
                        elseif ($salary_sebulan > 11050000 && $salary_sebulan <= 11600000) { $salary_pph21 = $salary_sebulan * (4 / 100); } 
                        elseif ($salary_sebulan > 11600000 && $salary_sebulan <= 12500000) { $salary_pph21 = $salary_sebulan * (4 / 100); } 
                        elseif ($salary_sebulan > 12500000 && $salary_sebulan <= 13750000) { $salary_pph21 = $salary_sebulan * (5 / 100); } 
                        elseif ($salary_sebulan > 13750000 && $salary_sebulan <= 15100000) { $salary_pph21 = $salary_sebulan * (6 / 100); } 
                        elseif ($salary_sebulan > 15100000 && $salary_sebulan <= 16950000) { $salary_pph21 = $salary_sebulan * (7 / 100); } 
                        elseif ($salary_sebulan > 16950000 && $salary_sebulan <= 19750000) { $salary_pph21 = $salary_sebulan * (8 / 100); } 
                        elseif ($salary_sebulan > 19750000 && $salary_sebulan <= 24150000) { $salary_pph21 = $salary_sebulan * (9 / 100); } 
                        elseif ($salary_sebulan > 24150000 && $salary_sebulan <= 26450000) { $salary_pph21 = $salary_sebulan * (10 / 100); } 
                        elseif ($salary_sebulan > 26450000 && $salary_sebulan <= 28000000) { $salary_pph21 = $salary_sebulan * (11 / 100); } 
                        elseif ($salary_sebulan > 28000000 && $salary_sebulan <= 30050000) { $salary_pph21 = $salary_sebulan * (12 / 100); }
                    } elseif ($id_status_kawin == 3 || $id_status_kawin == 4 || $id_status_kawin == 6 || $id_status_kawin == 7) {
                        if ($salary_sebulan > 0 && $salary_sebulan <= 6200000) { $salary_pph21 = 0; } 
                        elseif ($salary_sebulan > 6200000 && $salary_sebulan <= 6500000) { $salary_pph21 = $salary_sebulan * (0.25 / 100); } 
                        elseif ($salary_sebulan > 6500000 && $salary_sebulan <= 6850000) { $salary_pph21 = $salary_sebulan * (0.5 / 100); } 
                        elseif ($salary_sebulan > 6850000 && $salary_sebulan <= 7300000) { $salary_pph21 = $salary_sebulan * (0.75 / 100); } 
                        elseif ($salary_sebulan > 7300000 && $salary_sebulan <= 9200000) { $salary_pph21 = $salary_sebulan * (1 / 100); } 
                        elseif ($salary_sebulan > 9200000 && $salary_sebulan <= 10750000) { $salary_pph21 = $salary_sebulan * (1.5 / 100); } 
                        elseif ($salary_sebulan > 10750000 && $salary_sebulan <= 11250000) { $salary_pph21 = $salary_sebulan * (2 / 100); } 
                        elseif ($salary_sebulan > 11250000 && $salary_sebulan <= 11600000) { $salary_pph21 = $salary_sebulan * (2.5 / 100); } 
                        elseif ($salary_sebulan > 11600000 && $salary_sebulan <= 12600000) { $salary_pph21 = $salary_sebulan * (3 / 100); } 
                        elseif ($salary_sebulan > 12600000 && $salary_sebulan <= 13600000) { $salary_pph21 = $salary_sebulan * (4 / 100); } 
                        elseif ($salary_sebulan > 13600000 && $salary_sebulan <= 14950000) { $salary_pph21 = $salary_sebulan * (5 / 100); } 
                        elseif ($salary_sebulan > 14950000 && $salary_sebulan <= 16400000) { $salary_pph21 = $salary_sebulan * (6 / 100); }
                    } elseif ($id_status_kawin == 8 || $id_status_kawin == 12) {
                        if ($salary_sebulan > 0 && $salary_sebulan <= 6600000) { $salary_pph21 = 0; } 
                        elseif ($salary_sebulan > 6600000 && $salary_sebulan <= 6950000) { $salary_pph21 = $salary_sebulan * (0.25 / 100); } 
                        elseif ($salary_sebulan > 6950000 && $salary_sebulan <= 7350000) { $salary_pph21 = $salary_sebulan * (0.5 / 100); } 
                        elseif ($salary_sebulan > 7350000 && $salary_sebulan <= 7800000) { $salary_pph21 = $salary_sebulan * (0.75 / 100); } 
                        elseif ($salary_sebulan > 7800000 && $salary_sebulan <= 8850000) { $salary_pph21 = $salary_sebulan * (1 / 100); } 
                        elseif ($salary_sebulan > 17050000 && $salary_sebulan <= 19500000) { $salary_pph21 = $salary_sebulan * (7 / 100); } 
                        elseif ($salary_sebulan > 19500000 && $salary_sebulan <= 22700000) { $salary_pph21 = $salary_sebulan * (8 / 100); } 
                        elseif ($salary_sebulan > 22700000 && $salary_sebulan <= 26600000) { $salary_pph21 = $salary_sebulan * (9 / 100); } 
                        elseif ($salary_sebulan > 26600000 && $salary_sebulan <= 28100000) { $salary_pph21 = $salary_sebulan * (10 / 100); } 
                        elseif ($salary_sebulan > 28100000 && $salary_sebulan <= 30100000) { $salary_pph21 = $salary_sebulan * (11 / 100); } 
                        elseif ($salary_sebulan > 30100000 && $salary_sebulan <= 32600000) { $salary_pph21 = $salary_sebulan * (12 / 100); } 
                        elseif ($salary_sebulan > 32600000 && $salary_sebulan <= 35400000) { $salary_pph21 = $salary_sebulan * (13 / 100); } 
                        elseif ($salary_sebulan > 35400000 && $salary_sebulan <= 38900000) { $salary_pph21 = $salary_sebulan * (14 / 100); } 
                        elseif ($salary_sebulan > 38900000 && $salary_sebulan <= 43000000) { $salary_pph21 = $salary_sebulan * (15 / 100); }
                    }

                    if ($idPengguna == '758') { $salary_pph21 = '0'; }

                    // Ambil PPh 21 dari inputan / import PPh 21 pada menu Salary (mengikuti filter bulan)
                    $pph21_manual_val = pph21_manual($row->pengguna_id, $month1);
                    if ($pph21_manual_val > 0) {
                        $salary_pph21 = $pph21_manual_val;
                    }

                    // ============================================================
                    // INTERVENSI MANUAL
                    // ============================================================
                    if ($tahun2 == 2026 && $bulan2 == 9) {

                        // 1. AFRIANTO (ID Cek di Database, misal 23)
                        if ($idPengguna == '37') {
                             $salary_pph21 = null;
                        }
                        // 4. NOVEMBY ARDIANSYAH (Cek ID-nya, misal 771)
                        if ($idPengguna == '771') {
                            $salary_pph21 = null;
                        }
                        // 2. ANGGI SAPUTRA (ID Misal 745)
                        else if ($idPengguna == '736') {
                            $salary_pph21 = 13979;
                        }
                        // 3.  athala aqsa (Cek ID-nya, misal 756)
                        else if ($idPengguna == '92') {
                            $salary_pph21 = null;
                        }
                        // 5.  azhari (Cek ID-nya)
                        else if ($idPengguna == '64') { // Ganti XXX dengan ID Athala
                            $salary_pph21 = 28355;
                        }
                        // // 6.  Boddy (Cek ID-nya)
                        else if ($idPengguna == '94') { // Ganti YYY dengan ID Azhari
                             $salary_pph21 = 51523;
                        }
                        // 5.  Mr. Bob Ariyos Director
                        else if ($idPengguna == '54') { // Ganti XXX dengan ID Athala
                            $salary_pph21 = 1638400;
                        }
                        // 6.  Budi (Cek ID-nya)
                        else if ($idPengguna == '73') { // Ganti YYY dengan ID Azhari
                             $salary_pph21 = null;
                        }
                        // 6.  Deni (Cek ID-nya)
                        else if ($idPengguna == '99') { // Ganti YYY dengan ID Azhari
                             $salary_pph21 = null;
                        }
                        // 6.  dirangga (Cek ID-nya)
                        else if ($idPengguna == '106') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 76518;
                        }
                        // 6.  febrimon (Cek ID-nya)
                        else if ($idPengguna == '7') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 51765;
                        }
                        // 6.  mega ratu (Cek ID-nya)
                        else if ($idPengguna == '75') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 90089;
                        }
                        // 6.  meilina (Cek ID-nya)
                        else if ($idPengguna == '23') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 631248;
                        }
                        // 6.  tamrin (Cek ID-nya)
                        else if ($idPengguna == '105') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;
                        }
                        // 6.  ricky (Cek ID-nya)
                        else if ($idPengguna == '14') { // Ganti YYY dengan ID Azhari
                             $salary_pph21 = null;
                        }
                        // 6.  rizky sabtu (Cek ID-nya)
                        else if ($idPengguna == '25') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 88775;
                        }
                        // 6.  Rosmaniar
                        else if ($idPengguna == '55') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;
                        }
                        // 6.  Sulistyo harmoko (Cek ID-nya)
                        else if ($idPengguna == '68') { // Ganti YYY dengan ID Azhari
                             $salary_pph21 = null;
                        }
                        // 6.  vera (Cek ID-nya)
                        else if ($idPengguna == '102') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 45133;
                        }
                        // 6.  yolanda (Cek ID-nya)
                        else if ($idPengguna == '33') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 402080;
                        }
                        // 6.  muammar alfha (Cek ID-nya)
                        else if ($idPengguna == '724') { // Ganti YYY dengan ID Azhari
                             $salary_pph21 = null;
                        }
                        // 6.  syarifah andira (Cek ID-nya)
                        else if ($idPengguna == '725') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 119460;
                        }
                        // 6.  Dian M (Cek ID-nya)
                        else if ($idPengguna == '744') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 152161;
                        }
                        // 6.  agata (Cek ID-nya)
                        else if ($idPengguna == '747') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 91025;
                        }
                        // 6.  Nurdiansyah (Cek ID-nya)
                        else if ($idPengguna == '745') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 195463;
                        }
                        // 6.  Intan Kurnia (Cek ID-nya)
                        else if ($idPengguna == '751') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;
                        }
                        // 6.  Sukma (Cek ID-nya)
                        else if ($idPengguna == '754') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 13784;
                        }
                        // 6.  Arifa (Cek ID-nya)
                        else if ($idPengguna == '755') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 13509;
                        }
                        // Zainul
                        else if ($idPengguna == '759') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;
                        }
                        // Nuh Visi
                        else if ($idPengguna == '758') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;
                        }
                        // Reza
                        else if ($idPengguna == '764') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;
                        }
                        // Afylmardopila
                        else if ($idPengguna == '766') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = 85888;
                        }
                        // Fitri Andriani
                        else if ($idPengguna == '769') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;
                        }
                        // Yuliannisa Eka Putri
                        else if ($idPengguna == '770') { // Ganti YYY dengan ID Azhari
                            $salary_pph21 = null;  
                        }
                        // ... Tambahkan 'else if' lagi untuk karyawan lain jika ada beda ...
                    }

                    $PPH21 = $salary_pph21;
                    $sebelumPajak = $gapok + $pendapatan_lain - $potonganBPJSkes - $potonganBPJStk - $potongan_lain;
                    
                    // Keamanan matematika (Safe math if NULL)
                    $safePPH = ($PPH21 === null) ? 0 : $PPH21;
                    $setelahPajak = $sebelumPajak - $safePPH;
                    $potongan_all = $potonganBPJSkes + $potonganBPJStk + $potongan_lain + $safePPH;

                    // Tambahkan Salary ke tabel history_salary
                    $dataHistory = [
                        'tahun' => $tahun2,
                        'bulan' => $bulan2,
                        'nama' => $nama,
                        'lama' => $lama,
                        'no_rek' => $no_rek,
                        'idpengguna' => $idPengguna,
                        'gajipokok' => $gapok,
                        'tunjangankonsumsi' => $tunjanganKonsumsi,
                        'tunjangankomunikasi' => $tunjanganKomunikasi,
                        'tunjanganjabatan' => $tunjanganJabatan,
                        'tunjangantransport' => $tunjanganBbm,
                        'tunjangankinerja' => $tunjanganKinerja,
                        'tunjanganraya' => $tunjanganraya,
                        'bonus' => $tunjanganKomisi,
                        'dariPerusahaan' => $dariPerusahaan,
                        'pendapatanlain' => $pendapatan_lain,
                        'pendapatanlain_tidaktetap' => $Pendapatanlain,
                        'pph21' => $salary_pph21,
                        'bpjskesehatan' => $potonganBPJSkes,
                        'bpjstk' => $potonganBPJStk,
                        'potonganlainnya' => $potongan_lain,
                        'totalpendapatan' => $salary_sebulan,
                        'totalpotongan' => $potongan_all,
                        'totalterima' => $salary_sebulan - $potongan_all,
                        'sebelum_pajak' => $sebelumPajak,
                        'setelah_pajak' => floor($setelahPajak),
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    // ============================================================
                    // LOGIKA UPSERT (UPDATE IF EXISTS, INSERT IF NOT)
                    // ============================================================
                    $whereHistory = [
                        'tahun' => $tahun2,
                        'bulan' => $bulan2,
                        'idpengguna' => $idPengguna
                    ];

                    $existing = $this->db->get_where('salary_history', $whereHistory)->row();

                    if ($existing) {
                        $this->db->where($whereHistory);
                        $this->db->update('salary_history', $dataHistory);
                    } else {
                        $this->md_salary->addHistorySalary($dataHistory);
                    }
                    // ============================================================

                    $td = '
                            <tr>
                                 <td style="text-align: center;">' . $x++ . '</td>
                                 <td>' . $nama . '</td>
                                 <td>' . $lama . '</td>
                                 <td style="text-align: right;">' . ($gapok ? 'Rp. ' . rupiah($gapok) : 'Rp. -') . '</td>
                                 <td style="text-align: right;">' . ($pendapatan_lain ? 'Rp. ' . rupiah($pendapatan_lain) : 'Rp. -') . '</td>
                                 <td style="text-align: right;">' . ($potonganBPJSkes ? 'Rp. ' . rupiah($potonganBPJSkes) : 'Rp. -') . '</td>
                                 <td style="text-align: right;">' . ($potonganBPJStk ? 'Rp. ' . rupiah($potonganBPJStk) : 'Rp. -') . '</td>
                                 <td style="text-align: right;">' . ($potongan_lain ? 'Rp. ' . rupiah($potongan_lain) : 'Rp. -') . '</td>
                                 <td style="text-align: right;">' . ($sebelumPajak ? 'Rp. ' . rupiah($sebelumPajak) : 'Rp. -') . '</td>
                                 <td style="text-align: right;">' . ($PPH21 !== null ? 'Rp. ' . rupiah($PPH21) : 'Rp. -') . '</td>
                                 <td style="text-align: right;">' . (floor($setelahPajak) ? 'Rp. ' . rupiah(floor($setelahPajak)) : 'Rp. -') . '</td>                                  
                                 <td style="text-align: center;">' . $no_rek . '</td>
                            </tr>
                        ';
                    $sumAll += $sebelumPajak;
                    $sumPajak += $safePPH;
                    $sumbayar += floor($setelahPajak);
                    echo $td;
                }
                ?>

                <tr style="border-bottom: none;">
                    <td colspan="10"><strong>Total Keseluruhan :</strong></td>
                    <td style="text-align: right;"><strong> Rp. <?= rupiah($sumbayar) ?></strong></td>
                    <td><strong></strong></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table border="0" style="border-collapse: collapse; width: 100%;">
    <tbody>
        <tr>
            <td style="width: 33.33%; text-align: center;">&nbsp;</td>
            <td style="width: 33.33%; text-align: center;">&nbsp;</td>
            <td style="width: 33.33%; text-align: center;">Pekanbaru, <?= indo_dates(date('Y-m-d')) ?></td>
        </tr>
        
        <tr>
            <td style="width: 33.33%; text-align: center;">Diajukan Oleh</td>
            <td style="width: 33.33%; text-align: center;">Diverifikasi Oleh</td>
            <td style="width: 33.33%; text-align: center;">Disetujui Oleh</td>
        </tr>
        
        <tr>
            <td style="height: 80px; text-align: center;">&nbsp;</td>
            <td style="height: 80px; text-align: center;">&nbsp;</td>
            <td style="height: 80px; text-align: center;">&nbsp;</td>
        </tr>
        
        <tr>
            <td style="width: 33.33%; text-align: center;"><u>Novemby Ardiansyah Putra</u></td>
            <td style="width: 33.33%; text-align: center;"><u>Azhari Pratama</u></td>
            <td style="width: 33.33%; text-align: center;"><u>Dian Melati Amelia</u></td>
        </tr>
        
        <tr>
            <td style="width: 33.33%; text-align: center;"><i>General Affair</i></td>
            <td style="width: 33.33%; text-align: center;"><i>Staff Tax</i></td>
            <td style="width: 33.33%; text-align: center;"><i>HR & Legal</i></td>
        </tr>
    </tbody>
    </div>
</body>
</html>
