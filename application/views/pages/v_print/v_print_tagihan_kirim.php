<!doctype html>
<html>

<head>
   <base href="<?= base_url() ?>">
   <meta charset="UTF-8">
   <title>Tagihan Kirim Dokumen | <?= $this->config->item('apps_name') ?></title>
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

      .signature-section {
         margin-top: 40px;
         page-break-inside: avoid;
      }
   </style>
</head>

<body>
   <div class="page-container">
      <!-- Header Kop Surat -->
      <div class="header-kop">
         <img src="assets/img/kop_baru.jpg" alt="Kop Surat">
      </div>
      <hr class="header-line">

      <div class="content-wrapper">
         <!-- Title Section -->
         <div class="title-section">
            <h3>TAGIHAN EKSPEDISI - KIRIM DOKUMEN</h3>
            <p>Kode: <strong><?= $detail->kode ?? '-' ?></strong></p>
         </div>

         <!-- Info Table -->
         <table class="info-table">
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
               <td class="label">Marketing</td>
               <td class="separator">:</td>
               <td><?= $detail->marketing ?? '-' ?></td>
            </tr>
            <tr>
               <td class="label">Nama Customer</td>
               <td class="separator">:</td>
               <td><strong><?= $detail->nama_customer ?? '-' ?></strong></td>
            </tr>
            <tr>
               <td class="label">PIC Penerima</td>
               <td class="separator">:</td>
               <td><?= $detail->pic ?? '-' ?></td>
            </tr>
            <tr>
               <td class="label">Alamat Penerima</td>
               <td class="separator">:</td>
               <td><?= nl2br(htmlspecialchars($detail->alamat ?? '-')) ?></td>
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
            <tr>
               <td class="label">Link Resi</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->link_resi) ? '<a href="' . $detail->link_resi . '" target="_blank">' . $detail->link_resi . '</a>' : '-' ?></td>
            </tr>
            <tr>
               <td class="label">Tanggal Pengiriman</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->tgl_kirim) ? date('d F Y', strtotime($detail->tgl_kirim)) : '-' ?></td>
            </tr>
            <tr>
               <td class="label">Estimasi Sampai</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->tgl_sampai) ? date('d F Y', strtotime($detail->tgl_sampai)) : '-' ?></td>
            </tr>
            <tr>
               <td class="label">Link Dokumen</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->link_doc) ? '<a href="' . $detail->link_doc . '" target="_blank">' . $detail->link_doc . '</a>' : '-' ?></td>
            </tr>
            <tr>
               <td class="label">Keterangan</td>
               <td class="separator">:</td>
               <td><?= nl2br(htmlspecialchars($detail->keterangan ?? '-')) ?></td>
            </tr>
         </table>

         <!-- Nilai Tagihan -->
         <table style="width: 100%; margin-top: 20px; margin-bottom: 20px; border: 2px solid #000;">
            <tr>
               <td style="padding: 15px; background-color: #f0f0f0;">
                  <strong>Nilai Tagihan:</strong>
               </td>
               <td style="padding: 15px; text-align: right; background-color: #f0f0f0;">
                  <strong style="font-size: 16px;">Rp <?= number_format($detail->total_tagihan ?? 0, 0, ',', '.') ?></strong>
               </td>
            </tr>
            <tr>
               <td style="padding: 10px;">
                  Biaya Asuransi:
               </td>
               <td style="padding: 10px; text-align: right;">
                  Rp <?= number_format($detail->biaya_asuransi ?? 0, 0, ',', '.') ?>
               </td>
            </tr>
         </table>

         <!-- Lampiran -->
         <table class="info-table">
            <tr>
               <td class="label">Link Invoice Ekspedisi</td>
               <td class="separator">:</td>
               <td><?= !empty($detail->link_invoice_ekspedisi) ? '<a href="' . $detail->link_invoice_ekspedisi . '" target="_blank">' . $detail->link_invoice_ekspedisi . '</a>' : '-' ?></td>
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

         <!-- TTD Section -->
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

         // TTD Senior Tax (ID: 64 - Azhari Pratama)
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

         <table style="border-collapse: collapse; width: 100%; margin-top: 20px;" class="signature-section">
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