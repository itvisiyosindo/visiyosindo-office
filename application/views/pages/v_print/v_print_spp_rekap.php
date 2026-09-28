<!doctype html>
<html>

<head>
   <base href="<?= base_url() ?>">
   <meta charset="UTF-8">
   <title>Rekap SPP | <?= $this->config->item('apps_name') ?></title>
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
         font-size: 11px;
      }

      .page-container {
         padding: 0;
         margin: 0;
      }

      .header-kop {
         width: 100%;
      }

      .header-kop img {
         width: 100%;
      }

      .content-wrapper {
         padding: 0 20px;
      }

      .title-section {
         text-align: center;
         margin: 15px 0;
      }

      .title-section h3 {
         font-size: 16px;
         font-weight: bold;
         text-decoration: underline;
         margin-bottom: 5px;
      }

      .title-section p {
         font-size: 12px;
         margin: 3px 0;
      }

      .info-table td {
         padding: 2px 5px;
         vertical-align: top;
      }

      .data-table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 15px;
         font-size: 10px;
      }

      .data-table th {
         background-color: #C6DEFF;
         border: 1px solid black;
         padding: 5px 3px;
         text-align: center;
         font-weight: bold;
      }

      .data-table td {
         border: 1px solid black;
         padding: 4px 3px;
      }

      .data-table .text-center {
         text-align: center;
      }

      .data-table .text-right {
         text-align: right;
      }

      .data-table tfoot td {
         font-weight: bold;
         background-color: #f8f9fa;
      }

      .summary-box {
         margin: 10px 0;
         padding: 10px;
         background-color: #e3f2fd;
         border: 2px solid #1976d2;
      }

      .ttd-section {
         margin-top: 25px;
         width: 100%;
         page-break-inside: avoid;
      }

      .ttd-section td {
         text-align: center;
         vertical-align: top;
         padding: 8px 5px;
      }

      .ttd-img {
         height: 60px;
         margin: 8px 0;
      }

      hr.ttd-line {
         display: block;
         margin: 0 auto;
         border: 0;
         border-top: 1px solid black;
         width: 80%;
      }

      .status-badge {
         padding: 2px 6px;
         border-radius: 3px;
         font-size: 9px;
         font-weight: bold;
      }

      .status-pending {
         background: #ffc107;
         color: #000;
      }

      .status-approved {
         background: #28a745;
         color: #fff;
      }

      .status-rejected {
         background: #dc3545;
         color: #fff;
      }

      .status-revisi {
         background: #17a2b8;
         color: #fff;
      }

      @media print {
         body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
         }

         .no-print {
            display: none !important;
         }

         .data-table th {
            background-color: #C6DEFF !important;
         }
      }
   </style>
</head>

<body>
   <div class="page-container">
      <!-- Header Kop Surat -->
      <div class="header-kop">
         <img src="<?= base_url('assets/img/kop_baru.jpg') ?>" alt="Kop Surat" />
         <hr>
         </hr>
         <hr>
         </hr>
      </div>

      <!-- Content -->
      <div class="content-wrapper">
         <!-- Title -->
         <div class="title-section">
            <h3>REKAP SURAT PERMINTAAN PEMBAYARAN (SPP)</h3>
            <?php if (!empty($filter_periode)): ?>
               <p>Periode: <?= $filter_periode ?></p>
            <?php endif; ?>
         </div>

         <!-- Info -->
         <table class="info-table" style="margin-bottom: 15px;">
            <tr>
               <td style="width: 120px;">Tanggal Cetak</td>
               <td style="width: 10px;">:</td>
               <td><?= date('d F Y H:i') ?></td>
            </tr>
            <tr>
               <td>Total Data</td>
               <td>:</td>
               <td><?= count($list_spp) ?> SPP</td>
            </tr>
         </table>

         <!-- Data Table -->
         <table class="data-table">
            <thead>
               <tr>
                  <th style="width: 25px;">No</th>
                  <th style="width: 100px;">No. SPP</th>
                  <th style="width: 80px;">No. Invoice</th>
                  <th style="width: 65px;">Tgl Pengajuan</th>
                  <th>Pengaju</th>
                  <th style="width: 40px;">Jml Tagihan</th>
                  <th style="width: 100px;">Total Nilai</th>
                  <th style="width: 55px;">Status</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $no = 1;
               $total_nilai = 0;
               $total_approved = 0;
               $total_pending = 0;

               foreach ($list_spp as $spp):
                  $total_nilai += $spp->total_nilai ?? 0;

                  // Determine status
                  $status_class = 'status-pending';
                  $status_text = 'Proses';
                  if ($spp->status_approval == 5) {
                     $status_class = 'status-approved';
                     $status_text = 'Approved';
                     $total_approved += $spp->total_nilai ?? 0;
                  } elseif ($spp->status_approval == 99) {
                     $status_class = 'status-rejected';
                     $status_text = 'Ditolak';
                  } elseif ($spp->status_approval == 0) {
                     $status_class = 'status-revisi';
                     $status_text = 'Revisi';
                     $total_pending += $spp->total_nilai ?? 0;
                  } else {
                     $total_pending += $spp->total_nilai ?? 0;
                  }
               ?>
                  <tr>
                     <td class="text-center"><?= $no++ ?></td>
                     <td><?= $spp->no_spp ?? '-' ?></td>
                     <td><?= $spp->no_invoice ?? '-' ?></td>
                     <td class="text-center"><?= date('d/m/Y', strtotime($spp->created_at)) ?></td>
                     <td><?= $spp->nama_pengaju ?? '-' ?></td>
                     <td class="text-center"><?= $spp->jumlah_tagihan ?? 0 ?></td>
                     <td class="text-right">Rp <?= number_format($spp->total_nilai ?? 0, 0, ',', '.') ?></td>
                     <td class="text-center"><span class="status-badge <?= $status_class ?>"><?= $status_text ?></span></td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
            <tfoot>
               <tr>
                  <td colspan="6" class="text-right">TOTAL KESELURUHAN :</td>
                  <td class="text-right">Rp <?= number_format($total_nilai, 0, ',', '.') ?></td>
                  <td></td>
               </tr>
            </tfoot>
         </table>

         <!-- Summary Box -->
         <div class="summary-box">
            <table style="width: 100%; font-size: 11px;">
               <tr>
                  <td style="width: 50%;">
                     <strong>Total SPP Approved:</strong><br>
                     <span style="font-size: 14px; color: #28a745;">Rp <?= number_format($total_approved, 0, ',', '.') ?></span>
                  </td>
                  <td style="width: 50%;">
                     <strong>Total SPP Pending/Proses:</strong><br>
                     <span style="font-size: 14px; color: #1976d2;">Rp <?= number_format($total_pending, 0, ',', '.') ?></span>
                  </td>
               </tr>
            </table>
         </div>

         <!-- TTD Section -->
         <?php
         $img_path = "uploads/file_karyawan/ttd/";
         ?>

         <table class="ttd-section">
            <tr>
               <td colspan="4" style="text-align: right; padding-bottom: 10px;">
                  Pekanbaru, <?= date('d F Y') ?>
               </td>
            </tr>
            <tr>
               <td style="width: 25%;">Dibuat Oleh,</td>
               <td style="width: 25%;">Diverifikasi Oleh,</td>
               <td style="width: 25%;" colspan="2">Disetujui Oleh,</td>
            </tr>
            <tr>
               <td></td>
               <td></td>
               <td style="width: 25%;">DCPBM</td>
               <td style="width: 25%;">Direktur</td>
            </tr>
            <tr>
               <td><img src="<?= $img_path ?>ttd_<?= sessPenggunaId() ?>.png" class="ttd-img" alt="TTD" onerror="this.src='<?= $img_path ?>ttd_blank.png'"></td>
               <td><img src="<?= $img_path ?>ttd_33.png" class="ttd-img" alt="TTD Finance"></td>
               <td><img src="<?= $img_path ?>ttd_23.png" class="ttd-img" alt="TTD DCPBM"></td>
               <td><img src="<?= $img_path ?>ttd_54.png" class="ttd-img" alt="TTD Direktur"></td>
            </tr>
            <tr>
               <td>
                  <strong><?= $this->session->userdata('nama') ?></strong>
                  <hr class="ttd-line">
               </td>
               <td>
                  <strong>Yolanda Pratiwi</strong>
                  <hr class="ttd-line">
               </td>
               <td>
                  <strong>Meilina Safitri</strong>
                  <hr class="ttd-line">
               </td>
               <td>
                  <strong>Bob Ariyos</strong>
                  <hr class="ttd-line">
               </td>
            </tr>
            <tr>
               <td><i><?= $this->session->userdata('jabatan') ?? 'Staff' ?></i></td>
               <td><i>General Manager</i></td>
               <td><i>Director of Corporate Planning & Business Management</i></td>
               <td><i>Director</i></td>
            </tr>
         </table>
      </div>

      <!-- Footer Kop Surat -->
      <div class="footer-kop" style="border-top: 1px solid black;padding-top:5px;">
         <img src="<?= base_url('assets/img/kop_surat_bawah.jpg') ?>" alt="Footer" />
      </div>
   </div>
</body>

</html>