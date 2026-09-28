<!doctype html>
<html>

<head>
   <base href="<?= base_url() ?>">
   <meta charset="UTF-8">
   <title>Tagihan Ekspedisi | <?= $this->config->item('apps_name') ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- Vendor CSS -->
   <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap/css/bootstrap.css" />
   <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/font-awesome/css/all.min.css" />

   <style>
      html,
      body {
         background: white !important;
         color: #000000;
         font-family: 'Times New Roman', serif;
         font-size: 12px;
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
         margin-bottom: 5px;
      }

      .header-kop img {
         width: 100%;
         max-width: 100%;
         height: auto;
      }

      .header-line {
         border: 0;
         border-top: 2px solid #000;
         margin: 0 0 10px 0;
      }

      .content-wrapper {
         padding: 0 40px;
      }

      .title-section {
         text-align: center;
         margin: 20px 0;
      }

      .title-section h3 {
         font-size: 16px;
         font-weight: bold;
         text-decoration: underline;
         margin-bottom: 5px;
      }

      .title-section p {
         font-size: 14px;
         margin: 5px 0;
      }

      .info-table {
         width: 100%;
         margin-bottom: 20px;
      }

      .info-table td {
         padding: 3px 5px;
         vertical-align: top;
      }

      .info-table .label {
         width: 150px;
         font-weight: normal;
      }

      .info-table .separator {
         width: 10px;
      }

      .data-table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
         font-size: 11px;
      }

      .data-table th {
         background-color: #C6DEFF;
         border: 1px solid black;
         padding: 8px 5px;
         text-align: center;
         font-weight: bold;
      }

      .data-table td {
         border: 1px solid black;
         padding: 6px 5px;
      }

      .data-table .text-center {
         text-align: center;
      }

      .data-table .text-right {
         text-align: right;
      }

      .summary-box {
         margin: 15px 0;
         padding: 10px 15px;
         background-color: #f8f9fa;
         border: 1px solid #ddd;
         border-radius: 5px;
      }

      .ttd-section {
         margin-top: 30px;
         width: 100%;
         page-break-inside: avoid;
      }

      .ttd-section td {
         width: 33%;
         text-align: center;
         vertical-align: top;
         padding: 10px 5px;
      }

      .ttd-img {
         height: 70px;
         margin: 10px 0;
      }

      hr.ttd-line {
         display: block;
         margin: 0 auto;
         border: 0;
         border-top: 1px solid black;
         width: 80%;
      }

      .footer-kop {
         width: 100%;
         position: fixed;
         bottom: 0;
      }

      .footer-kop img {
         width: 100%;
      }

      @media print {
         body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
         }

         .no-print {
            display: none !important;
         }
      }
   </style>
</head>

<body>
   <div class="page-container">
      <!-- Header Kop Surat -->
      <div class="header-kop">
         <img src="assets/img/kop_baru.jpg" width="100%" height="15%" />
      </div>

      <!-- Content -->
      <div class="content-wrapper">
         <!-- Title -->
         <div class="title-section">
            <h3>FORM TAGIHAN EKSPEDISI</h3>
            <p><?= $type_label ?? 'Surat Jalan Barang Keluar (SJBK)' ?></p>
            <p>No. SJ: <strong><?= $detail->no_sj ?></strong></p>
         </div>

         <!-- Info Section -->
         <table class="info-table">
            <tr>
               <td class="label">Tanggal Cetak</td>
               <td class="separator">:</td>
               <td><?= date('d F Y') ?></td>
            </tr>
            <tr>
               <td class="label">Jenis Dokumen</td>
               <td class="separator">:</td>
               <td><strong><?= $doc_code ?? 'SJBK' ?></strong></td>
            </tr>
            <tr>
               <td class="label">No. Invoice</td>
               <td class="separator">:</td>
               <td><strong><?= $detail->no_invoice ?? '-' ?></strong></td>
            </tr>
            <tr>
               <td class="label">Tanggal Invoice</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->tanggal_invoice) ? date('d F Y', strtotime($detail->tanggal_invoice)) : '-' ?></td>
            </tr>
            <tr>
               <td class="label">Ekspedisi</td>
               <td class="separator">:</td>
               <td><strong><?= $detail->nama_ekspedisi ?? '-' ?></strong></td>
            </tr>
            <tr>
               <td class="label">No. Resi</td>
               <td class="separator">:</td>
               <td><?= $detail->no_resi ?? '-' ?></td>
            </tr>

            <?php
            $tracking_type = $tracking_type ?? 'pengeluaran_barang';

            if ($tracking_type == 'pengeluaran_barang'):
            ?>
               <!-- Info untuk SJBK (Pengeluaran Barang) -->
               <tr>
                  <td class="label">Customer</td>
                  <td class="separator">:</td>
                  <td><?= $detail->nama_customer ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Gudang Asal</td>
                  <td class="separator">:</td>
                  <td><?= $detail->nama_gudang ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">No. PO</td>
                  <td class="separator">:</td>
                  <td><?= $detail->no_po ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Alamat Tujuan</td>
                  <td class="separator">:</td>
                  <td><?= $detail->alamat_penerima ?? '-' ?></td>
               </tr>

            <?php elseif ($tracking_type == 'pengiriman_stok'): ?>
               <!-- Info untuk TTBK (Pengiriman Stok antar Gudang) -->
               <tr>
                  <td class="label">No. Pemindahan</td>
                  <td class="separator">:</td>
                  <td><?= $detail->no_pemindahan ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Tanggal Pengiriman</td>
                  <td class="separator">:</td>
                  <td><?= !empty($detail->tgl_pengiriman_stok) ? date('d F Y', strtotime($detail->tgl_pengiriman_stok)) : '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Gudang Asal</td>
                  <td class="separator">:</td>
                  <td><?= $detail->nama_gudang_asal ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Gudang Tujuan</td>
                  <td class="separator">:</td>
                  <td><?= $detail->nama_gudang_tujuan ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Keterangan</td>
                  <td class="separator">:</td>
                  <td><?= $detail->ket_pengiriman_stok ?? '-' ?></td>
               </tr>

            <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
               <!-- Info untuk STTB (Serah Terima Barang) -->
               <tr>
                  <td class="label">Kode STTB</td>
                  <td class="separator">:</td>
                  <td><?= $detail->kode_stb ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Tanggal Pengajuan</td>
                  <td class="separator">:</td>
                  <td><?= !empty($detail->tgl_stb) ? date('d F Y', strtotime($detail->tgl_stb)) : '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Kota</td>
                  <td class="separator">:</td>
                  <td><?= $detail->kota_stb ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Pihak 1</td>
                  <td class="separator">:</td>
                  <td><?= $detail->nama_pihak1 ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Pihak 2</td>
                  <td class="separator">:</td>
                  <td><?= $detail->nama_pihak2 ?? '-' ?></td>
               </tr>
               <tr>
                  <td class="label">Alamat Tujuan</td>
                  <td class="separator">:</td>
                  <td><?= $detail->alamat_pihak2 ?? $detail->alamat_penerima ?? '-' ?></td>
               </tr>
            <?php endif; ?>
         </table>

         <!-- Detail Barang -->
         <h5 style="font-weight: bold; margin-bottom: 10px;"><u>Detail Barang</u></h5>
         <?php if (!empty($detail_barang_keluar) && count($detail_barang_keluar) > 0): ?>
            <table class="data-table">
               <thead>
                  <tr>
                     <th style="width: 30px;">No</th>
                     <th>Nama Barang</th>
                     <?php if (($tracking_type ?? '') == 'serah_terima_barang'): ?>
                        <th style="width: 70px;">Merk</th>
                        <th style="width: 70px;">No. Batch</th>
                     <?php endif; ?>
                     <th style="width: 60px;">Qty</th>
                     <th style="width: 60px;">Satuan</th>
                  </tr>
               </thead>
               <tbody>
                  <?php $no = 1;
                  foreach ($detail_barang_keluar as $brg): ?>
                     <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $brg->nama_barang ?? '-' ?></td>
                        <?php if (($tracking_type ?? '') == 'serah_terima_barang'): ?>
                           <td class="text-center"><?= $brg->merk ?? '-' ?></td>
                           <td class="text-center"><?= $brg->no_batch ?? '-' ?></td>
                        <?php endif; ?>
                        <td class="text-center"><?= $brg->jumlah ?? $brg->qty ?? '-' ?></td>
                        <td class="text-center"><?= $brg->satuan ?? '-' ?></td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         <?php else: ?>
            <p><i>- <?= $detail->nama_barang ?? 'Tidak ada detail barang' ?></i></p>
         <?php endif; ?>

         <!-- Summary -->
         <div class="summary-box">
            <table style="width: 100%;">
               <tr>
                  <td style="width: 150px;"><strong>Biaya Pengiriman</strong></td>
                  <td style="width: 10px;">:</td>
                  <td><strong style="font-size: 14px;">Rp <?= number_format($detail->biaya_real ?? 0, 0, ',', '.') ?></strong></td>
               </tr>
               <tr>
                  <td><strong>Nilai Tagihan (Ongkir)</strong></td>
                  <td>:</td>
                  <td><strong style="font-size: 14px;">Rp <?= number_format($detail->nilai_tagihan ?? 0, 0, ',', '.') ?></strong></td>
               </tr>
               <tr>
                  <td><strong>Biaya Asuransi</strong></td>
                  <td>:</td>
                  <td><strong style="font-size: 14px;">Rp <?= number_format($detail->biaya_asuransi ?? 0, 0, ',', '.') ?></strong>
                     <?php if (($detail->biaya_asuransi ?? 0) == 0): ?>
                        <small style="color: #6c757d;"><i>(tidak ada asuransi)</i></small>
                     <?php endif; ?>
                  </td>
               </tr>
               <tr style="background-color: #e3f2fd;">
                  <td><strong>Total Tagihan</strong></td>
                  <td>:</td>
                  <td><strong style="font-size: 16px; color: #007bff;">Rp <?= get_display_total_tagihan($detail) ?></strong></td>
               </tr>
               <tr>
                  <td colspan="3">
                     <hr style="margin: 5px 0;">
                  </td>
               </tr>
               <tr>
                  <td>Payment Term</td>
                  <td>:</td>
                  <td><?= $detail->payment_term ?? '-' ?></td>
               </tr>
               <tr>
                  <td>PPh 23</td>
                  <td>:</td>
                  <td><?= $detail->pph23 ?? '-' ?></td>
               </tr>
            </table>
         </div>

         <!-- Dokumen Pendukung -->
         <h5 style="font-weight: bold; margin: 15px 0 10px;"><u>Dokumen Pendukung</u></h5>
         <table class="info-table">
            <tr>
               <td class="label">Link Invoice</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->link_invoice) ? '<a href="' . $detail->link_invoice . '" target="_blank">' . $detail->link_invoice . '</a>' : '-' ?></td>
            </tr>
            <tr>
               <td class="label">Link Faktur Pajak</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->link_faktur_pajak) ? '<a href="' . $detail->link_faktur_pajak . '" target="_blank">' . $detail->link_faktur_pajak . '</a>' : '-' ?></td>
            </tr>
            <tr>
               <td class="label">Link Bukti Potong</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->link_bukti_potong) ? '<a href="' . $detail->link_bukti_potong . '" target="_blank">' . $detail->link_bukti_potong . '</a>' : '-' ?></td>
            </tr>
         </table>

         <!-- TTD Section (sejajar seperti print rekap) -->
         <?php
         $img_path = "uploads/file_karyawan/ttd/";

         // TTD Pengaju
         $ttd_pengaju = $img_path . "ttd_" . $detail->created_by . ".png";
         if (!file_exists($ttd_pengaju)) {
            $ttd_pengaju = $img_path . "ttd_blank.png";
         }

         // TTD Head of Warehouse (ID: 749 - Sehan Ohisabaref)
         $ttd_warehouse = $img_path . "ttd_notyet2.png";
         if ($detail->status_approval >= 2) {
            $ttd_warehouse = $img_path . "ttd_769.png";
         }

         // TTD Senior Tax (ID: 6 - Azhari Pratama)
         $ttd_tax = $img_path . "ttd_notyet2.png";
         if ($detail->status_approval >= 3) {
            $ttd_tax = $img_path . "ttd_64.png";
         }

         // TTD Head of Finance (ID: 33 - Yolanda Pratiwi)
         $ttd_finance = $img_path . "ttd_notyet2.png";
         if ($detail->status_approval >= 4 || $detail->status_approval == 5) {
            $ttd_finance = $img_path . "ttd_106.png";
         }
         ?>

         <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
            <tbody>
               <tr>
                  <td style="width: 25%;">&nbsp;</td>
                  <td style="width: 21%;">&nbsp;</td>
                  <td style="width: 12%;">&nbsp;</td>
                  <td style="width: 12%;">&nbsp;</td>
                  <td style="width: 30%; text-align: right;">Pekanbaru, <?= date('d F Y') ?></td>
               </tr>
               <tr>
                  <td style="text-align: center;">Dibuat Oleh,</td>
                  <td style="text-align: center;" colspan="2">Diverifikasi Oleh,</td>
                  <td style="text-align: center;">&nbsp;</td>
                  <td style="text-align: center;">Disetujui Oleh,</td>
               </tr>
               <tr>
                  <td style="text-align: center;">&nbsp;</td>
                  <td style="text-align: center;">&nbsp;</td>
                  <td style="text-align: center;" colspan="2">&nbsp;</td>
                  <td style="text-align: center;">&nbsp;</td>
               </tr>
               <tr style="height: 60px;">
                  <td style="text-align: center;">
                     <img src="<?= $ttd_pengaju ?>" height="50" alt="TTD Pengaju" onerror="this.src='<?= $img_path ?>ttd_blank.png'">
                  </td>
                  <td style="text-align: center;">
                     <img src="<?= $ttd_warehouse ?>" height="50" alt="TTD Warehouse">
                  </td>
                  <td style="text-align: center;" colspan="2">
                     <img src="<?= $ttd_tax ?>" height="50" alt="TTD Tax">
                  </td>
                  <td style="text-align: center;">
                     <img src="<?= $ttd_finance ?>" height="50" alt="TTD Finance">
                  </td>
               </tr>
               <tr>
                  <td style="text-align: center;"><u><?= $detail->nama_pengaju ?? '-' ?></u></td>
                  <td style="text-align: center;"><u>Fitri Andriani</u></td>
                  <td style="text-align: center;" colspan="2"><u>Azhari Pratama</u></td>
                  <td style="text-align: center;"><u>Dirangga Madali</u></td>
               </tr>
               <tr>
                  <td style="text-align: center;"><i><?= $detail->jabatan_pengaju ?? '-' ?></i></td>
                  <td style="text-align: center;"><i>PJT & Warehouse</i></td>
                  <td style="text-align: center;" colspan="2"><i>Senior Tax</i></td>
                  <td style="text-align: center;"><i>Head of Accounting and Tax</i></td>
               </tr>
            </tbody>
         </table>
      </div>

      <!-- Footer Kop Surat -->
      <div style="margin-top: 30px;">
         <img src="assets/img/kop_surat_bawah.jpg" width="100%" />
      </div>
   </div>
</body>

</html>