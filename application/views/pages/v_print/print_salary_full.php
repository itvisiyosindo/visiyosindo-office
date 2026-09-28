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
                        $potonganBPJSkes = dasarBPJSKetenagakerjaan($row->pengguna_id) * 2 / 100;
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

                    /* Ambil PPh 21 murni dari inputan / import PPh 21 pada menu Salary (mengikuti filter bulan) */
                    $salary_pph21 = pph21_manual($row->pengguna_id, $month1);

                    $PPH21 = $salary_pph21;
                    $sebelumPajak = $gapok + $pendapatan_lain - $potonganBPJSkes - $potonganBPJStk - $potongan_lain;
                    
                    // Keamanan matematika (Safe math if NULL / 0)
                    $safePPH = (empty($PPH21) || $PPH21 === null) ? 0 : $PPH21;
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
                                 <td style="text-align: right;">' . (!empty($PPH21) ? 'Rp. ' . rupiah($PPH21) : 'Rp. -') . '</td>
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
