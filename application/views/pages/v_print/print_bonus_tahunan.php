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
        <h3>Tahun : <?= $periode ?></h3>
    </div>
    <div>
        <table id="table" style="width:100%">
            <thead class="text-center">
                <tr>
                    <th> No </th>
                    <th> Nama Karyawan</th>
                    <th> NPP</th>
                    <th width="7%"> Jabatan </th>
                    <th> Status</th>
                    <th> Masa Kerja (dari Kontrak)</th>
                    <!--<th> Prorate</th>-->
                    <th> Nilai Evaluasi</th>
                    <th> Presentasi Bonus</th>
                    <th> Total Bonus</th>
                    <th> Nomor Rekening</th>
                </tr>

            </thead>
            <tbody>
                <?php
                $x = 1;
                $sumbayar = 0;

                $tahun_ini = $periode;

                // ambil gapok terbaru tahun ini
                $this->db->select('gapok');
                $this->db->from('bonus_config');
                $this->db->where('YEAR(created_at)', $tahun_ini);
                $this->db->order_by('id', 'DESC');
                $this->db->limit(1);
                $q_gapok = $this->db->get()->row();
                $gapok = ($q_gapok) ? $q_gapok->gapok : 0;


                foreach ($dt as $row) {

                    $nama = $row->nama;
                    $npp = $row->no_pegawai;
                    $jabatan = $row->jabatan;

                    $hari_ini = date('Y-m-d');
                    $tanggal_masuk = new DateTime($row->tgl_kontrak); // Tanggal masuk
                    $tanggal_hari_ini = new DateTime($hari_ini); // Tanggal hari ini
                    $selisih = $tanggal_masuk->diff($tanggal_hari_ini); // Menghitung selisih
                    $tahun = $selisih->y;
                    $bulan = $selisih->m;
                    $hari = $selisih->d;

                    if ($tahun == 0) {
                        // Tetap mencantumkan bulan dan hari jika belum mencapai 1 tahun
                        $lama = $bulan . ' Bulan ' . $hari . ' Hari';
                    } else {
                        // Jika sudah lebih dari 1 tahun
                        $lama = $tahun . ' Tahun ' . $bulan . ' Bulan ' . $hari . ' Hari';
                    }

                    //Status Karyawan
                    if ($row->status_karyawan == 'training') {
                        $status_karyawan = "Training";
                    } elseif ($row->status_karyawan == 'kontrak') {
                        $status_karyawan = "PKWT";
                    } elseif ($row->status_karyawan == 'tetap') {
                        $status_karyawan = "PKWTT";
                    } else {
                        $status_karyawan = "";
                    }


                    // Nilai Evaluasi terbaru tahun ini
                    $this->db->select('nilai');
                    $this->db->from('nilai_evaluasi');
                    $this->db->where('id_pengguna', $row->pengguna_id);
                    $this->db->where('periode', $tahun_ini);
                    $this->db->order_by('created_at', 'DESC');
                    $this->db->limit(1);
                    $q = $this->db->get()->row();
                    $nilai = ($q) ? $q->nilai : 0;

                    // pastikan nilai numerik
                    $nilai = 0;
                    if ($q && is_numeric(trim($q->nilai))) {
                        $nilai = floatval(trim($q->nilai));
                    }

                    // Presentasi berdasarkan nilai
                    if ($nilai >= 90 && $nilai <= 100) {
                        $presentasi = 1.00; // 100%
                    } elseif ($nilai >= 80 && $nilai < 90) {
                        $presentasi = 0.80; // 80%
                    } elseif ($nilai >= 70 && $nilai < 80) {
                        $presentasi = 0.60;
                    } elseif ($nilai >= 60 && $nilai < 70) {
                        $presentasi = 0.40;
                    } elseif ($nilai > 0 && $nilai < 60) {
                        $presentasi = 0.20;
                    } else {
                        $presentasi = 0;
                    }

                    // Prorate

                    $sekarang = new DateTime();
                    if (empty($row->tgl_kontrak) || $row->tgl_kontrak == '0000-00-00') {
                        $prorate = "Belum Kontrak";
                    } else {
                        $tgl_kontrak = new DateTime($row->tgl_kontrak);
                        $diff = $tgl_kontrak->diff($sekarang);
                        $total_bulan = ($diff->y * 12) + $diff->m;

                        if ($diff->y >= 1) {
                            $prorate = "Tidak";
                        } elseif ($total_bulan == 0 && $diff->d > 0) {
                            $prorate = "Belum sampai 1 bulan";
                        } else {
                            $prorate = "Ya (" . $total_bulan . "/12)";
                        }
                    }

                    // Hitung Total Bonus
                    if (empty($row->tgl_kontrak) || $row->tgl_kontrak == '0000-00-00') {
                        $totalBonus = "0";
                    } elseif (in_array($row->pengguna_id, [105, 745])) {
                        $totalBonus = "0";
                        $presentasi = 0; // paksa presentasi 0 agar tampil "-"
                    } elseif ($prorate == "Belum sampai 1 bulan") {
                        $totalBonus = "0";
                    } elseif (strpos($prorate, 'Ya') !== false) {
                        //preg_match('/\((\d+)\/12\)/', $prorate, $match);
                        //$bulan = isset($match[1]) ? $match[1] : 0;
                        //$totalBonus = round(($bulan / 12) * $gapok * $presentasi);
                        $totalBonus = "0";
                    } else {
                        $totalBonus = round($gapok * $presentasi);
                    }

                    // Format presentasi biar tampil dengan persen
                    $presentasi_label = ($presentasi > 0) ? ($presentasi * 100) . '%' : '-';


                    $no_rek   =  $row->no_rek ?  $row->no_rek : '-';





                    //<td>' .$prorate. '</td>


                    /*$td = '
                           <tr>
                                 <td style="text-align: center;">' .$x++. '</td>
                                 <td>' .$nama. '</td>
                                 <td>' .$npp. '</td>
                                 <td>' .$jabatan. '</td>
                                 <td>' .$status_karyawan. '</td>
                                 <td>' .$lama. '</td>
                                 <td>' .$nilai. '</td>
                                 <td>' .$presentasi_label. '</td>
                                 <td style="text-align: right;">' .($totalBonus ? 'Rp. ' . rupiah($totalBonus) : 'Rp. -'). '</td>                               
                                 <td style="text-align: center;">' .$no_rek. '</td>
                            </tr>
                        ';
                        $sumbayar += floor($totalBonus);
                        echo $td;*/
                    if ($totalBonus > 0) {
                        $td = '
                                <tr>
                                    <td style="text-align: center;">' . $x . '</td>
                                    <td>' . $nama . '</td>
                                    <td>' . $npp . '</td>
                                    <td>' . $jabatan . '</td>
                                    <td>' . $status_karyawan . '</td>
                                    <td>' . $lama . '</td>
                                    <td>' . $nilai . '</td>
                                    <td>' . $presentasi_label . '</td>
                                    <td style="text-align: right;">Rp. ' . rupiah($totalBonus) . '</td>
                                    <td style="text-align: center;">' . $no_rek . '</td>
                                </tr>
                            ';
                        echo $td;
                        $sumbayar += floor($totalBonus);
                        $x++; // naikkan nomor hanya untuk baris yang ditampilkan
                    }
                }
                ?>

                <tr style="border-bottom: none;">
                    <td colspan="8"><strong>Total Keseluruhan :</strong></td>
                    <td style="text-align: right;"><strong> Rp. <?= rupiah($sumbayar) ?></strong></td>
                    <td><strong></strong></td>
                </tr>
            </tbody>


        </table>
        <br>
        <table style="border-collapse: collapse; width: 100%; height: 128px;">
            <tbody>
                <tr style="height: 18px;">
                    <td style="width: 25%; height: 18px;">&nbsp;</td>
                    <td style="width: 21.8748%; height: 18px;">&nbsp;</td>
                    <td style="width: 9.87224%; height: 18px;">&nbsp;</td>
                    <td style="width: 13.4233%; height: 18px;">&nbsp;</td>
                    <td style="width: 13.4233%; height: 18px;">&nbsp;</td>
                    <td style="width: 29.8296%; height: 18px; text-align: center;">Pekanbaru,
                        <?= indo_dates(date('Y-m-d')) ?>
                    </td>
                </tr>
                <tr style="height: 18px;">
                    <td style="width: 25%; height: 18px; text-align: center;">Diajukan Oleh</td>
                    <td style="height: 18px; width: 45.1703%; text-align: center;" colspan="4">Diverifikasi Oleh</td>
                    <td style="width: 29.8296%; height: 18px; text-align: center;">Disetujui Oleh</td>
                </tr>
                <tr style="height: 56px;">
                    <td style="width: 25%; height: 56px; text-align: center;">&nbsp;</td>
                    <td style="width: 45.1703%; height: 56px; text-align: center;" colspan="4">&nbsp;</td>
                    <td style="width: 29.8296%; height: 56px; text-align: center;">&nbsp;</td>
                </tr>
                <tr style="height: 18px;">
                    <td style="width: 25%; text-align: center; height: 18px;"><u>Amtisari Destiani Eka Putri</u></td>
                    <td style="width: 21.8748%; height: 18px; text-align: center;"><u>Azhari Pratama</u></td>
                    <td style="width: 25%; text-align: center; height: 18px;"><u>Dirangga Madali</u></td>
                    <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Yolanda Pratiwi</u></td>
                    <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Meilina Safitri</u></td>
                    <td style="width: 29.8296%; text-align: center; height: 18px;"><u>Bob Ariyos</u></td>
                </tr>
                <tr style="height: 18px;">
                    <td style="width: 29.8296%; text-align: center; height: 18px;"><i>General Affair</i></td>
                    <td style="width: 21.8748%; text-align: center; height: 18px;"><i>Senior Tax</i></td>
                    <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Head of Accounting and Tax</i></td>
                    <td style="width: 25%; text-align: center; height: 18px;"><i>General Manager</i></td>
                    <td style="width: 23.2955%; text-align: center; height: 18px;"><i>Director of Corp Planning & Bussinees Management</i></td>
                    <td style="width: 29.8296%; text-align: center; height: 18px;"><i>Direktur</i></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>