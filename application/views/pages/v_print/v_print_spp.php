<!doctype html>
<html>

<head>
    <base href="<?= base_url() ?>">
    <meta charset="UTF-8">
    <title>SPP EKSPEDISI <?= $spp->no_spp ?> | <?= $this->config->item('apps_name') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/font-awesome/css/all.min.css" />

    <style>
        html,
        body {
            background: white !important;
            color: #000000;
            font-family: 'Times New Roman', serif;
            font-size: 11px; /* Diperkecil sedikit agar compact */
            margin: 0;
            padding: 0;
        }

        .page-container {
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .header-kop {
            width: 100%;
            margin-bottom: 2px;
        }

        .header-kop img {
            width: 100%;
            max-width: 100%;
            height: auto;
        }

        .header-line {
            border: 0;
            border-top: 2px solid #000;
            margin: 2px 0;
        }

        .content-wrapper {
            padding: 0 30px; /* Padding sisi dikurangi agar muat lebih banyak */
        }

        .title-section {
            text-align: center;
            margin: 10px 0;
        }

        .title-section h3 {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
            margin-top: 0;
        }

        .title-section p {
            font-size: 13px;
            margin: 2px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .info-table td {
            padding: 1px 5px; /* Spasi baris diperkecil */
            vertical-align: top;
        }

        .info-table .label {
            width: 140px;
            font-weight: normal;
        }

        .info-table .separator {
            width: 10px;
        }

        /* Styling Tabel Utama */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .data-table th {
            background-color: #C6DEFF;
            border: 1px solid black;
            padding: 4px 3px;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }

        .data-table td {
            border: 1px solid black;
            padding: 3px 4px; /* Cell padding diperkecil agar compact */
            vertical-align: middle;
        }

        .data-table .text-center { text-align: center; }
        .data-table .text-right { text-align: right; }

        .data-table tfoot td {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
        }

        /* Box Summary Biru */
        .summary-box {
            margin: 10px 0;
            padding: 10px;
            background-color: #e3f2fd;
            border: 1px solid #1976d2;
            border-radius: 4px;
        }

        /* Bagian Tanda Tangan */
        .ttd-section {
            margin-top: 20px;
            width: 100%;
            page-break-inside: avoid;
            border-collapse: collapse;
        }

        .ttd-section td {
            text-align: center;
            vertical-align: bottom;
            padding: 2px;
            font-size: 11px;
        }

        .ttd-img {
            height: 60px; /* Ukuran TTD disesuaikan */
            margin: 5px 0;
            max-width: 100%;
        }

        hr.ttd-line {
            display: block;
            margin: 2px auto 0 auto;
            border: 0;
            border-top: 1px solid black;
            width: 90%;
        }

        .footer-kop {
            width: 100%;
            margin-top: 20px;
        }

        .footer-kop img {
            width: 100%;
            height: auto;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print { display: none !important; }
            .data-table th { background-color: #C6DEFF !important; }
            .summary-box { background-color: #e3f2fd !important; }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="header-kop">
            <img src="<?= base_url('assets/img/kop_baru.jpg') ?>" alt="Kop Surat" />
        </div>
        <hr class="header-line">
        
        <div class="content-wrapper">
            <div class="title-section">
                <h3>SURAT PERMINTAAN PEMBAYARAN EKSPEDISI</h3>
                <p style="font-style: italic; font-weight: 400;">No. SPP: <?= $spp->no_spp ?></p>
            </div>

            <table class="info-table">
                <tr>
                    <td class="label">No. Invoice</td>
                    <td class="separator">:</td>
                    <td><strong><?= $spp->no_invoice ?? '-' ?></strong></td>
                </tr>
                <tr>
                    <td class="label">Tanggal Pengajuan</td>
                    <td class="separator">:</td>
                    <td><?= date('d F Y', strtotime($spp->created_at)) ?></td>
                </tr>
                <tr>
                    <td class="label">Diajukan Oleh</td>
                    <td class="separator">:</td>
                    <td><?= $spp->nama_pengaju ?> (<?= $spp->jabatan_pengaju ?? '-' ?>)</td>
                </tr>
            </table>

           <div class="summary-box">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="width: 55%; vertical-align: top; border: none;">
                            <div style="margin-bottom: 5px;"><strong>Jumlah Tagihan:</strong> <?= $spp->jumlah_tagihan ?> Item</div>
                            <div><strong>Total Nilai Tagihan:</strong></div>
                            <div style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?></div>

                            <div style="border-top: 1px dashed #1976d2; padding-top: 5px; margin-top: 5px;">
                                <strong style="font-size: 11px;">Rekening Pembayaran / Transfer:</strong>
                                <table style="width: 100%; border: none; font-size: 11px; margin-top: 2px;">
                                    <tr>
                                        <td style="width: 70px; padding: 0px; border: none;">Nama Bank</td>
                                        <td style="width: 10px; padding: 0px; border: none;">:</td>
                                        <td style="padding: 0px; border: none;"><strong><?= !empty($spp->nama_bank) ? strtoupper($spp->nama_bank) : '-' ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0px; border: none;">No. Rekening</td>
                                        <td style="padding: 0px; border: none;">:</td>
                                        <td style="padding: 0px; border: none; font-size: 12px;"><strong><?= !empty($spp->no_rekening) ? $spp->no_rekening : '-' ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0px; border: none;">Atas Nama</td>
                                        <td style="padding: 0px; border: none;">:</td>
                                        <td style="padding: 0px; border: none;"><strong><?= !empty($spp->atas_nama) ? strtoupper($spp->atas_nama) : '-' ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            </td>

                        <td style="width: 45%; text-align: right; vertical-align: top; border: none;">
                            
                            <table style="width: 100%; border: none;">
                                <?php if (!empty($spp->ppn) && $spp->ppn > 0): ?>
                                <tr>
                                    <td style="text-align: right; padding: 1px; border: none;">PPN:</td>
                                    <td style="text-align: right; width: 100px; padding: 1px; border: none;">+ Rp <?= number_format($spp->ppn, 0, ',', '.') ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if (!empty($spp->diskon) && $spp->diskon > 0): ?>
                                <tr>
                                    <td style="text-align: right; padding: 1px; border: none;">Diskon:</td>
                                    <td style="text-align: right; padding: 1px; color: #ff8c00; border: none;">- Rp <?= number_format($spp->diskon, 0, ',', '.') ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if ((!empty($spp->ppn) && $spp->ppn > 0) || (!empty($spp->diskon) && $spp->diskon > 0)): ?>
                                <tr>
                                    <td style="text-align: right; padding: 1px; font-weight: bold; border: none;">Grand Total:</td>
                                    <td style="text-align: right; padding: 1px; font-weight: bold; border: none;">Rp <?= number_format($spp->grand_total, 0, ',', '.') ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if (!empty($spp->nilai_bukti_potong) && $spp->nilai_bukti_potong > 0): ?>
                                <tr>
                                    <td style="text-align: right; padding: 1px; border: none;">Bukti Potong:</td>
                                    <td style="text-align: right; padding: 1px; color: #d32f2f; border: none;">- Rp <?= number_format($spp->nilai_bukti_potong, 0, ',', '.') ?></td>
                                </tr>
                                <?php endif; ?>
                                
                                <tr>
                                    <td style="text-align: right; padding-top: 5px; font-weight: bold; font-size: 12px; border: none;">NILAI PEMBAYARAN:</td>
                                    <td style="text-align: right; padding-top: 5px; font-weight: bold; font-size: 16px; color: #1976d2; border: none;">
                                        Rp <?= number_format($spp->nilai_pembayaran, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
            </div>

            <h5 style="font-weight: bold; margin: 10px 0 5px; font-size: 12px;"><u>Daftar Tagihan dalam SPP</u></h5>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 120px;">Nomor Surat</th> <th>Customer</th>
                        <th>Ekspedisi</th>
                        <th style="width: 60px;">No. Resi</th>
                        <th style="width: 65px;">Tgl Invoice</th>
                        <th style="width: 85px;">Nilai Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total = 0;
                    foreach ($tagihan_list as $t):
                        $tagihan_amount = !empty($t->total_tagihan) ? $t->total_tagihan : ($t->nilai_tagihan + ($t->biaya_asuransi ?? 0));
                        $total += $tagihan_amount;

                        // --- LOGIKA PENGGABUNGAN KOLOM (Nomor Surat) ---
                        // Prioritas: 1. No SJ, 2. Kode STB/Kirim
                        $nomor_tampil = '-';
                        
                        // Cek No SJ dulu
                        if (!empty($t->no_sj) && $t->no_sj != '-') {
                            $nomor_tampil = $t->no_sj;
                        } 
                        // Jika SJ kosong, cek STTB/KD
                        else {
                             $sttb_kd = !empty($t->kode_stb) ? $t->kode_stb : (!empty($t->kode_kirim) ? $t->kode_kirim : '-');
                             if($sttb_kd != '-') {
                                 $nomor_tampil = $sttb_kd;
                             }
                        }
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td style="font-weight: 500;"><?= $nomor_tampil ?></td> <td><?= $t->nama_customer ?? '-' ?></td>
                            <td><?= $t->nama_ekspedisi ?? '-' ?></td>
                            <td><?= $t->no_resi ?? '-' ?></td>
                            <td class="text-center"><?= !empty($t->tanggal_invoice) ? date('d/m/y', strtotime($t->tanggal_invoice)) : '-' ?></td>
                            <td class="text-right">Rp <?= number_format($tagihan_amount, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right"><strong>TOTAL :</strong></td>
                        <td class="text-right"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            <?php if (!empty($spp->link_dokumen_spp)): ?>
                <div style="font-size: 10px; margin-top: 5px;">
                    <strong>Link Dokumen:</strong> <a href="<?= $spp->link_dokumen_spp ?>" target="_blank"><?= substr($spp->link_dokumen_spp, 0, 60) ?>...</a>
                </div>
            <?php endif; ?>

            <?php
            $img_path = "uploads/file_karyawan/ttd/";

            // Logika Gambar TTD (Tetap sama seperti source asli)
            $ttd_pengaju = file_exists($img_path . "ttd_" . $spp->created_by . ".png") ? $img_path . "ttd_" . $spp->created_by . ".png" : $img_path . "ttd_blank.png";
            $ttd_notyet  = $img_path . "ttd_notyet2.png";
            
            $ttd_finance  = ($spp->status_approval >= 2) ? $img_path . "ttd_106.png" : $ttd_notyet;
            $ttd_gm       = ($spp->status_approval >= 2) ? $img_path . "ttd_33.png"  : $ttd_notyet;
            $ttd_dcpbm    = ($spp->status_approval >= 3) ? $img_path . "ttd_23.png"  : $ttd_notyet;
            $ttd_direktur = ($spp->status_approval == 5) ? $img_path . "ttd_54.png"  : $ttd_notyet;
            ?>

            <table class="ttd-section">
                <tr>
                    <td colspan="5" style="text-align: right; padding-bottom: 5px;">
                        Pekanbaru, <?= date('d F Y') ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">Dibuat Oleh,</td>
                    <td style="width: 20%;">Diverifikasi Oleh,</td>
                    <td colspan="3" style="width: 60%; border-bottom: 1px solid #eee;">Disetujui Oleh,</td>
                </tr>
                <tr>
                    <td><img src="<?= $ttd_pengaju ?>" class="ttd-img" alt="Pengaju"></td>
                    <td><img src="<?= $ttd_finance ?>" class="ttd-img" alt="Finance"></td>
                    <td><img src="<?= $ttd_gm ?>" class="ttd-img" alt="GM"></td>
                    <td><img src="<?= $ttd_dcpbm ?>" class="ttd-img" alt="DCPBM"></td>
                    <td><img src="<?= $ttd_direktur ?>" class="ttd-img" alt="Dir"></td>
                </tr>
                <tr>
                    <td>
                        <strong><?= $spp->nama_pengaju ?? '-' ?></strong>
                        <hr class="ttd-line">
                        <i><?= $spp->jabatan_pengaju ?? 'Staff' ?></i>
                    </td>
                    <td>
                        <strong>Dirangga Madali</strong>
                        <hr class="ttd-line">
                        <i>Head of Accounting & Tax</i>
                    </td>
                    <td>
                        <strong>Yolanda Pratiwi</strong>
                        <hr class="ttd-line">
                        <i>General Manager</i>
                    </td>
                    <td>
                        <strong>Meilina Safitri</strong>
                        <hr class="ttd-line">
                        <i>Director of Corporate Planning and Business Management</i>
                    </td>
                    <td>
                        <strong>Bob Ariyos</strong>
                        <hr class="ttd-line">
                        <i>Director</i>
                    </td>
                </tr>
            </table>

        </div>

        <div class="footer-kop">
            <img src="<?= base_url('assets/img/kop_surat_bawah.jpg') ?>" alt="Footer" />
        </div>
    </div>
</body>
</html>