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

        /* #table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #table tr:hover {
            background-color: #ddd;
        } */

        #table th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            font-size: 11Px;
        }

        #table td {
            font-size: 10px;
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
    <img src="assets/img/kop_baru.jpg" width="100%" height="30%" />
    <div style="text-align:center">
        <h2><?= $title_pdf ?></h2>
        <h3>Periode : <?= $periode ?></h3>
    </div>
    <table id="table" style="width:100%">
        <thead class="text-center">
            <tr>
                <th style="width:5%"> No </th>
                <th style="width:10%"> Nama Karyawan</th>
                <th style="width:5%"> No Pegawai</th>
                <th style="width:7%"> Status Karyawan</th>
                <th style="width:13%"> Masa Kerja</th>
                <th style="width:7%"> Jumlah Hari Kerja</th>
                <th> Tunjangan Jabatan </th>
                <th> Tunjangan Kinerja </th>
                <th> Tunjangan Konsumsi </th>
                <th> Tunjangan Komunikasi </th>
                <th> Tunjangan Transportasi </th>
                <th> Tunjangan BBM </th>
                <th> Tunjangan Lainnya </th>
                <th> Potongan </th>
                <th style="width:12%"> Total Tunjangan </th>
                <th> No Rekening </th>
            </tr>
        </thead>
        <?php if (true) { ?>
            <tbody>
                <?php
                $overrides = isset($overrides) ? $overrides : $this->md_salary_tidak_tetap->getOverridesByMonth($month);
                $x = 1;
                $sumJabatan = 0;
                $sumKinerja = 0;
                $sumKonsumsi = 0;
                $sumKomunikasi = 0;
                $sumTunjangan = 0;
                $sumbbm = 0;
                $sumtransportasi = 0;
                $sumPotongan = 0;
                $sumtunjanganlain = 0;

                $excluded_ids = [29, 768]; // Tambahkan ID karyawan yang ingin dikecualikan di sini (contoh: [29, 30, 31])
                foreach ($dt as $row) {
                    if (in_array($row->pengguna_id, $excluded_ids)) continue;
                    // 1. AMBIL DATA DASAR DARI HELPER
                    $dataSistem      = tunjangan($row->pengguna_id, $month);
                    $absen_approved  = $dataSistem['kantor_approved'] ?? $dataSistem['dinas_approved'];
                    $potongan1       = $dataSistem['total_potongan'];

                    // Variabel Tunjangan Asli
                    $jabatan        = ($row->terima_tunjangan_jabatan == 1) ? tunjanganJabatan($row->pengguna_id) : 0;
                    $kinerja        = ($row->terima_tunjangan_kinerja == 1) ? $dataSistem['total_kinerjadinas'] : 0;
                    $konsumsi       = ($row->terima_tunjangan_konsumsi == 1) ? $dataSistem['total_konsumsidinas'] : 0;
                    $komunikasi     = ($row->terima_tunjangan_komunikasi == 1) ? $dataSistem['total_komunikasi'] : 0;
                    $transportasi   = ($row->terima_tunjangan_transportasi == 1) ? $dataSistem['total_transportasi'] : 0;
                    $bbm            = ($row->terima_tunjangan_bbm == 1) ? $dataSistem['total_bbm'] : 0;
                    $pendapatanlain = ($row->id_pendapatan_lain > 0) ? $dataSistem['total_tunjanganlain'] : 0;

                    // 2. LOGIKA KHUSUS SECURITY (ID 94) - Tetap Dipertahankan
                    if ($row->pengguna_id == 94) {
                        $salaryBoddyB = $this->md_absensi->getTunjanganBoddyBiasa($row->pengguna_id, $month);
                        $salaryBoddyL = $this->md_absensi->getTunjanganBoddyLibur($row->pengguna_id, $month);
                        $absen_approved = count($salaryBoddyB) + (count($salaryBoddyL) * 3);
                        $kinerja_days = count($salaryBoddyL) * 3;
                        if (strpos($month, '2026-07') !== false) {
                            $absen_approved = 53;
                            $kinerja_days = 33;
                        }

                        $salary = $this->md_salary->getById($row->id_latestriwayat_salary);
                        $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                        $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                        $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

                        $kinerja = ($row->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * $kinerja_days) : 0;
                        $konsumsi = ($row->terima_tunjangan_konsumsi == 1) ? ($rate_konsumsi * $absen_approved) : 0;
                        $bbm = ($row->terima_tunjangan_bbm == 1) ? ($rate_bbm * $absen_approved) : 0;
                    }

                    // 2b. LOGIKA KHUSUS RICKY ARINDI (ID 14) - Juli 2026 = 19 Hari
                    if ($row->pengguna_id == 14 && strpos($month, '2026-07') !== false) {
                        $absen_approved = 19;
                        $salary = $this->md_salary->getById($row->id_latestriwayat_salary);
                        $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                        $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                        $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

                        $kinerja = ($row->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * 19) : 0;
                        $konsumsi = ($row->terima_tunjangan_konsumsi == 1) ? ($rate_konsumsi * 19) : 0;
                        $bbm = ($row->terima_tunjangan_bbm == 1) ? ($rate_bbm * 19) : 0;
                    }

                    // 2c. LOGIKA KHUSUS RIZKI SABTU PERDANA (ID 25) - Juli 2026 = 21 Hari
                    if ($row->pengguna_id == 25 && strpos($month, '2026-07') !== false) {
                        $absen_approved = 21;
                        $salary = $this->md_salary->getById($row->id_latestriwayat_salary);
                        $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                        $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                        $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

                        $kinerja = ($row->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * 21) : 0;
                        $konsumsi = ($row->terima_tunjangan_konsumsi == 1) ? ($rate_konsumsi * 21) : 0;
                        $bbm = ($row->terima_tunjangan_bbm == 1) ? ($rate_bbm * 21) : 0;
                    }

                    // 2d. LOGIKA KHUSUS VERA PUSPITA SARI (ID 102) - Juli 2026 = 19 Hari
                    if ($row->pengguna_id == 102 && strpos($month, '2026-07') !== false) {
                        $absen_approved = 19;
                        $salary = $this->md_salary->getById($row->id_latestriwayat_salary);
                        $rate_kinerja = isset($salary[0]->tunjangan_kinerja) ? $salary[0]->tunjangan_kinerja : 0;
                        $rate_konsumsi = isset($salary[0]->tunjangan_konsumsi) ? $salary[0]->tunjangan_konsumsi : 0;
                        $rate_bbm = isset($salary[0]->tunjangan_bbm) ? $salary[0]->tunjangan_bbm : 0;

                        $kinerja = ($row->terima_tunjangan_kinerja == 1) ? ($rate_kinerja * 19) : 0;
                        $konsumsi = ($row->terima_tunjangan_konsumsi == 1) ? ($rate_konsumsi * 19) : 0;
                        $bbm = ($row->terima_tunjangan_bbm == 1) ? ($rate_bbm * 19) : 0;
                    }

                    // 2d. LOGIKA KHUSUS NOVEMBY ARDIANSYAH PUTRA (ID 771) & AFYLMARDOPILA (ID 766) - Juli 2026 = 9 Hari
                    if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($month, '2026-07') !== false) {
                        $absen_approved = 9;
                    }

                    // 3. LOGIKA KHUSUS BOB (ID 54) - Konsumsi tetap 5 juta
                    if ($row->pengguna_id == 54 && $row->terima_tunjangan_konsumsi == 1) {
                        $konsumsi = 5000000;
                    }

                    // 4. LOGIKA TRAINING - Tidak dihitung untuk tunjangan tidak tetap
                    if (strtolower($row->status_karyawan) == 'training') {
                        $kinerja = 0;
                        $konsumsi = 0;
                        $komunikasi = 0;
                        $transportasi = 0;
                        $bbm = 0;
                        $pendapatanlain = 0;

                        if ($row->pengguna_id == 771 && strpos($month, '2026-07') !== false) {
                            $konsumsi = 162000;
                        }
                    }

                    if (($row->pengguna_id == 771 || $row->pengguna_id == 766 || strpos(strtolower($row->nama), 'novemby') !== false || strpos(strtolower($row->nama), 'afylmardopila') !== false) && strpos($month, '2026-07') !== false) {
                        $is_afyl = ($row->pengguna_id == 766 || strpos(strtolower($row->nama), 'afylmardopila') !== false);
                        $days = $is_afyl ? 7 : 9;
                        $konsumsi = $days * 18000;
                        $konsumsi_tampil = $konsumsi;
                        $kinerja = $days * 30000;
                        $komunikasi = 0;
                        $transportasi = 0;
                        $bbm = 0;
                        $pendapatanlain = 0;
                        $absen_approved = $days;
                    } else {
                        $konsumsi_tampil = $konsumsi;
                    }

                    // 3. HITUNG TOTAL AKHIR
                    $tunjangan = $jabatan + $kinerja + $konsumsi + $komunikasi + $transportasi + $bbm + $pendapatanlain - $potongan1;

                    // Manual Override jika ada
                    $ov = isset($overrides[$row->pengguna_id]) ? $overrides[$row->pengguna_id] : null;
                    if ($ov) {
                        if ($ov->hari_kerja !== null && $ov->hari_kerja !== '') {
                            $absen_approved = (int)$ov->hari_kerja;
                            $salary = $this->md_salary->getById($row->id_latestriwayat_salary);
                            $rate_kinerja_val = isset($salary[0]->tunjangan_kinerja) ? (float)$salary[0]->tunjangan_kinerja : 0;
                            $rate_konsumsi_val = isset($salary[0]->tunjangan_konsumsi) ? (float)$salary[0]->tunjangan_konsumsi : 0;
                            $rate_bbm_val = isset($salary[0]->tunjangan_bbm) ? (float)$salary[0]->tunjangan_bbm : 0;

                            if ($row->terima_tunjangan_kinerja == 1 && ($ov->tunjangan_kinerja === null || $ov->tunjangan_kinerja === '')) {
                                $kinerja = $rate_kinerja_val * $absen_approved;
                            }
                            if ($row->terima_tunjangan_konsumsi == 1 && ($ov->tunjangan_konsumsi === null || $ov->tunjangan_konsumsi === '')) {
                                $konsumsi = $rate_konsumsi_val * $absen_approved;
                                $konsumsi_tampil = $konsumsi;
                            }
                            if ($row->terima_tunjangan_bbm == 1 && ($ov->tunjangan_bbm === null || $ov->tunjangan_bbm === '')) {
                                $bbm = $rate_bbm_val * $absen_approved;
                            }
                        }
                        if ($ov->tunjangan_jabatan !== null && $ov->tunjangan_jabatan !== '') $jabatan = (float)$ov->tunjangan_jabatan;
                        if ($ov->tunjangan_kinerja !== null && $ov->tunjangan_kinerja !== '') $kinerja = (float)$ov->tunjangan_kinerja;
                        if ($ov->tunjangan_konsumsi !== null && $ov->tunjangan_konsumsi !== '') { $konsumsi = (float)$ov->tunjangan_konsumsi; $konsumsi_tampil = $konsumsi; }
                        if ($ov->tunjangan_komunikasi !== null && $ov->tunjangan_komunikasi !== '') $komunikasi = (float)$ov->tunjangan_komunikasi;
                        if ($ov->tunjangan_transportasi !== null && $ov->tunjangan_transportasi !== '') $transportasi = (float)$ov->tunjangan_transportasi;
                        if ($ov->tunjangan_bbm !== null && $ov->tunjangan_bbm !== '') $bbm = (float)$ov->tunjangan_bbm;
                        if ($ov->tunjangan_lainnya !== null && $ov->tunjangan_lainnya !== '') $pendapatanlain = (float)$ov->tunjangan_lainnya;
                        if ($ov->potongan !== null && $ov->potongan !== '') $potongan1 = (float)$ov->potongan;

                        $tunjangan = $jabatan + $kinerja + $konsumsi + $komunikasi + $transportasi + $bbm + $pendapatanlain - $potongan1;
                    }

                    // Penentuan Status
                    $status_karyawan = ($row->pengguna_id == 758) ? "Magang" : ucwords(strtolower($row->status_karyawan));
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $x++ ?></td>
                        <td><?= ucwords(strtolower($row->nama)) ?></td>
                        <td style="text-align: center;"><?= $row->no_pegawai ?></td>
                        <td style="text-align: center;"><?= $status_karyawan ?></td>
                        <td><?= masaKerja($row->tgl_masuk) ?></td>
                        <td style="text-align: center;"><?= $absen_approved ?> hari</td>
                        <td><?= $jabatan ? 'Rp. ' . rupiah($jabatan) : 'Rp. -' ?></td>
                        <td><?= $kinerja ? 'Rp. ' . rupiah($kinerja) : 'Rp. -' ?></td>
                        <td><?= $konsumsi_tampil ? 'Rp. ' . rupiah($konsumsi_tampil) : 'Rp. -' ?></td>
                        <td><?= $komunikasi ? 'Rp. ' . rupiah($komunikasi) : 'Rp. -' ?></td>
                        <td><?= $transportasi ? 'Rp. ' . rupiah($transportasi) : 'Rp. -' ?></td>
                        <td><?= $bbm ? 'Rp. ' . rupiah($bbm) : 'Rp. -' ?></td>
                        <td><?= $pendapatanlain ? 'Rp. ' . rupiah($pendapatanlain) : 'Rp. -' ?></td>
                        <td><?= $potongan1 ? 'Rp. ' . rupiah($potongan1) : 'Rp. -' ?></td>
                        <td style="font-weight: bold;"><?= $tunjangan ? 'Rp. ' . rupiah($tunjangan) : 'Rp. -' ?></td>
                        <td><?= $row->no_rek ? $row->no_rek : '-' ?></td>
                    </tr>

                <?php
                    // 4. PENJUMLAHAN FOOTER
                    $sumJabatan += $jabatan;
                    $sumKinerja += $kinerja;
                    $sumKonsumsi += $konsumsi_tampil;
                    $sumKomunikasi += $komunikasi;
                    $sumTunjangan += $tunjangan;
                    $sumbbm += $bbm;
                    $sumtransportasi += $transportasi;
                    $sumPotongan += $potongan1;
                    $sumtunjanganlain += $pendapatanlain;
                } ?>

                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="5" style="text-align:right;">Total Keseluruhan :</td>
                    <td style="text-align: center;">-</td>
                    <td>Rp. <?= rupiah($sumJabatan) ?></td>
                    <td>Rp. <?= rupiah($sumKinerja) ?></td>
                    <td>Rp. <?= rupiah($sumKonsumsi) ?></td>
                    <td>Rp. <?= rupiah($sumKomunikasi) ?></td>
                    <td>Rp. <?= rupiah($sumtransportasi) ?></td>
                    <td>Rp. <?= rupiah($sumbbm) ?></td>
                    <td>Rp. <?= rupiah($sumtunjanganlain) ?></td>
                    <td>Rp. <?= rupiah($sumPotongan) ?></td>
                    <td>Rp. <?= rupiah($sumTunjangan) ?></td>
                    <td></td>
                </tr>
            </tbody>
        <?php } // PENUTUP IF(FALSE) 
        ?>
        <!-- Khusus Bulan november 2025 -->
        <?php if (false) { ?>
            <tbody>
                <?php
                $x = 1;
                // Inisialisasi variabel total
                $sumJabatan = 0;
                $sumKinerja = 0;
                $sumKonsumsi = 0;
                $sumKomunikasi = 0;
                $sumTunjangan = 0;
                $sumbbm = 0;
                $sumtransportasi = 0;
                $sumPotongan = 0;
                $sumtunjanganlain = 0;

                $excluded_ids = [29]; // Tambahkan ID karyawan yang ingin dikecualikan di sini (contoh: [29, 30, 31])
                foreach ($dt as $row) {
                    if (in_array($row->pengguna_id, $excluded_ids)) continue;
                    // 1. AMBIL SEMUA DATA TUNJANGAN SEKALI SAJA
                    $dataTunjangan = tunjangan($row->pengguna_id, $month);

                    // Ambil nilai dasar dari data tersebut
                    $absen_approved = $dataTunjangan['dinas_approved'];
                    $val_kinerja    = $dataTunjangan['total_kinerjadinas'];
                    $val_konsumsi   = $dataTunjangan['total_konsumsidinas'];
                    $val_komunikasi = $dataTunjangan['total_komunikasi'];
                    $val_transport  = $dataTunjangan['total_transportasi'];
                    $val_bbm        = $dataTunjangan['total_bbm'];
                    $val_lain       = $dataTunjangan['total_tunjanganlain'];
                    $val_potongan   = $dataTunjangan['total_potongan'];

                    // Variabel Setting (Terima Tunjangan atau Tidak)
                    $s_jabatan      = $row->terima_tunjangan_jabatan;
                    $s_kinerja      = $row->terima_tunjangan_kinerja;
                    $s_konsumsi     = $row->terima_tunjangan_konsumsi;
                    $s_komunikasi   = $row->terima_tunjangan_komunikasi;
                    $s_transportasi = $row->terima_tunjangan_transportasi;
                    $s_bbm          = $row->terima_tunjangan_bbm;
                    $s_tunjanganlain = $row->id_pendapatan_lain;

                    // Assign Nilai ke Variabel Tampil berdasarkan Setting
                    $jabatan        = ($s_jabatan == 1) ? tunjanganJabatan($row->pengguna_id) : 0;
                    $kinerja        = ($s_kinerja == 1) ? $val_kinerja : 0;
                    $konsumsi       = ($s_konsumsi == 1) ? $val_konsumsi : 0;
                    $komunikasi     = ($s_komunikasi == 1) ? $val_komunikasi : 0;
                    $transportasi   = ($s_transportasi == 1) ? $val_transport : 0;
                    $bbm            = ($s_bbm == 1) ? $val_bbm : 0;
                    $pendapatanlain = ($s_tunjanganlain > 0) ? $val_lain : 0;

                    // Logika Khusus User Lain (Pak Bob)
                    if ($row->pengguna_id == 94) {
                        $konsumsiBiasa = tunjangan($row->pengguna_id, $month)['total_konsumsi_Biasa'];
                        $konsumsiLibur = tunjangan($row->pengguna_id, $month)['total_konsumsi_Libur'];
                        $konsumsi = $konsumsiBiasa + ($konsumsiLibur * 3);

                        $kinerjaBiasa = tunjangan($row->pengguna_id, $month)['total_kinerja_Biasa'];
                        $kinerjaLibur = tunjangan($row->pengguna_id, $month)['total_kinerja_Libur'];
                        $kinerja = $kinerjaLibur * 3;
                    }

                    // Hitung Total Akhir Per Baris
                    $potongan1 = $val_potongan;
                    $tunjangan = $jabatan + $kinerja + $konsumsi + $komunikasi + $transportasi + $bbm + $pendapatanlain - $potongan1;

                    // Formatting Nama Status
                    if ($row->pengguna_id == 758) {
                        $status_karyawan = "Magang";
                    } else {
                        $status_karyawan = ucwords(strtolower($row->status_karyawan));
                    }
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $x++ ?></td>
                        <td><?= ucwords(strtolower($row->nama)) ?></td>
                        <td style="text-align: center;"><?= $row->no_pegawai ?></td>
                        <td style="text-align: center;"><?= $status_karyawan ?></td>
                        <td><?= masaKerja($row->tgl_masuk) ?></td>
                        <td><?= $absen_approved ?> hari</td>
                        <td><?= $jabatan ? 'Rp. ' . rupiah($jabatan) : 'Rp. -' ?></td>
                        <td><?= $kinerja ? 'Rp. ' . rupiah($kinerja) : 'Rp. -' ?></td>
                        <td><?= $konsumsi ? 'Rp. ' . rupiah($konsumsi) : 'Rp. -' ?></td>
                        <td><?= $komunikasi ? 'Rp. ' . rupiah($komunikasi) : 'Rp. -' ?></td>
                        <td><?= $transportasi ? 'Rp. ' . rupiah($transportasi) : 'Rp. -' ?></td>
                        <td><?= $bbm ? 'Rp. ' . rupiah($bbm) : 'Rp. -' ?></td>
                        <td><?= $pendapatanlain ? 'Rp. ' . rupiah($pendapatanlain) : 'Rp. -' ?></td>
                        <td><?= $potongan1 ? 'Rp. ' . rupiah($potongan1) : 'Rp. -' ?></td>
                        <td><?= $tunjangan ? 'Rp. ' . rupiah($tunjangan) : 'Rp. -' ?></td>
                        <td><?= $row->no_rek ? $row->no_rek : '-' ?></td>
                    </tr>

                <?php
                    // Penjumlahan Total Untuk Baris Paling Bawah
                    $sumJabatan += $jabatan;
                    $sumKinerja += $kinerja;
                    $sumKonsumsi += $konsumsi;
                    $sumKomunikasi += $komunikasi;
                    $sumtransportasi += $transportasi;
                    $sumbbm += $bbm;
                    $sumtunjanganlain += $pendapatanlain;
                    $sumPotongan += $potongan1;
                    $sumTunjangan += $tunjangan;
                } // AKHIR DARI FOREACH
                ?>

                <tr style="border-bottom: none;">
                    <td colspan="6" style="text-align:right; padding-right:10px;"><strong>Total Keseluruhan :</strong></td>
                    <td><strong> Rp. <?= rupiah($sumJabatan) ?> </strong></td>
                    <td><strong> Rp. <?= rupiah($sumKinerja) ?></strong></td>
                    <td><strong> Rp. <?= rupiah($sumKonsumsi) ?></strong></td>
                    <td><strong> Rp. <?= rupiah($sumKomunikasi) ?></strong></td>
                    <td><strong>Rp. <?= rupiah($sumtransportasi) ?></strong></td>
                    <td><strong>Rp. <?= rupiah($sumbbm) ?></strong></td>
                    <td><strong>Rp. <?= rupiah($sumtunjanganlain) ?></strong></td>
                    <td><strong> Rp. <?= rupiah($sumPotongan) ?></strong></td>
                    <td><strong> Rp. <?= rupiah($sumTunjangan) ?></strong></td>
                    <td><strong> </strong></td>
                </tr>

            </tbody>
        <?php } // PENUTUP IF(FALSE) 
        ?>
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
                <td style="width: 33.33%; text-align: center;"><u>Novemby Ardiansyah P.L</u></td>
                <td style="width: 33.33%; text-align: center;"><u>Mulia</u></td>
                <td style="width: 33.33%; text-align: center;"><u>Dian Melati Amelia</u></td>
            </tr>

            <tr>
                <td style="width: 33.33%; text-align: center;"><i>General Affair</i></td>
                <td style="width: 33.33%; text-align: center;"><i>Staff Accounting</i></td>
                <td style="width: 33.33%; text-align: center;"><i>HR & Legal</i></td>
            </tr>
        </tbody>
    </table>

</body>

</html>