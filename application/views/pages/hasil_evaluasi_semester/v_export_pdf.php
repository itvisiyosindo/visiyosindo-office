<!doctype html>
<html>

<head>
   <meta charset="UTF-8">
   <title>Hasil Evaluasi Semester | <?= $this->config->item('apps_name') ?></title>
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
         padding: 0 30px;
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
         width: 120px;
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

      .summary-section {
         margin: 20px 0;
         padding: 15px;
         background-color: #f5f5f5;
         border: 1px solid #ddd;
      }

      .summary-section h4 {
         font-size: 14px;
         font-weight: bold;
         margin-bottom: 10px;
      }

      .summary-table {
         width: 100%;
      }

      .summary-table td {
         padding: 5px 10px;
      }

      .grade-a {
         color: #28a745;
         font-weight: bold;
      }

      .grade-b {
         color: #007bff;
         font-weight: bold;
      }

      .grade-c {
         color: #ffc107;
         font-weight: bold;
      }

      .grade-d {
         color: #fd7e14;
         font-weight: bold;
      }

      .grade-e {
         color: #dc3545;
         font-weight: bold;
      }

      .footer-kop {
         width: 100%;
         position: fixed;
         bottom: 0;
      }

      .footer-kop img {
         width: 100%;
      }

      .keterangan-section {
         margin-top: 20px;
         font-size: 11px;
      }

      .keterangan-section table {
         width: 100%;
      }

      .keterangan-section td {
         padding: 2px 5px;
         vertical-align: top;
      }

      .ttd-section {
         margin-top: 40px;
         width: 100%;
      }

      .ttd-section td {
         width: 50%;
         text-align: center;
         vertical-align: top;
         padding: 10px;
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
         <img src="<?= base_url('assets/img/kop_newpanjang.jpg') ?>" alt="Kop Surat" />
      </div>

      <!-- Content -->
      <div class="content-wrapper">
         <!-- Title -->
         <div class="title-section">
            <h3>HASIL EVALUASI SEMESTER</h3>
            <p>Periode: <?= $periode ?></p>
            <p>Semester <?= $semester ?> Tahun <?= $tahun ?></p>
         </div>

         <!-- Info Section -->
         <table class="info-table">
            <tr>
               <td class="label">Tanggal Cetak</td>
               <td class="separator">:</td>
               <td><?= $tanggal_cetak ?></td>
            </tr>
            <tr>
               <td class="label">Total Karyawan</td>
               <td class="separator">:</td>
               <td><?= count($data_pegawai) ?> orang</td>
            </tr>
         </table>

         <!-- Summary Statistics -->
         <?php
         $total_laporan = 0;
         $total_evaluasi = 0;
         $total_hadir_all = 0;
         $total_sp_all = 0;
         $total_pk = 0;
         $count = count($data_pegawai);

         foreach ($data_pegawai as $p) {
            $total_laporan += $p['avg_laporan'];
            $total_evaluasi += $p['avg_evaluasi'];
            $total_hadir_all += $p['total_hadir'];
            $total_sp_all += $p['total_sp'];
            $total_pk += $p['nilai_pk'];
         }

         $avg_laporan_all = $count > 0 ? $total_laporan / $count : 0;
         $avg_evaluasi_all = $count > 0 ? $total_evaluasi / $count : 0;
         $avg_pk_all = $count > 0 ? $total_pk / $count : 0;
         ?>

         <div class="summary-section">
            <h4>RINGKASAN STATISTIK</h4>
            <table class="summary-table">
               <tr>
                  <td>Rata-rata Nilai Laporan</td>
                  <td>: <strong><?= number_format($avg_laporan_all, 2) ?></strong></td>
                  <td>Rata-rata Nilai Evaluasi</td>
                  <td>: <strong><?= number_format($avg_evaluasi_all, 2) ?></strong></td>
               </tr>
               <tr>
                  <td>Rata-rata Kehadiran</td>
                  <td>: <strong><?= number_format($total_hadir_all / max($count, 1), 0) ?> hari</strong></td>
                  <td>Rata-rata Nilai PK</td>
                  <td>: <strong><?= number_format($avg_pk_all, 2) ?></strong></td>
               </tr>
               <tr>
                  <td>Total Surat Peringatan</td>
                  <td>: <strong><?= $total_sp_all ?></strong></td>
                  <td></td>
                  <td></td>
               </tr>
            </table>
         </div>

         <!-- Data Table -->
         <table class="data-table">
            <thead>
               <tr>
                  <th style="width: 25px;">No</th>
                  <th style="width: 130px;">Nama Pegawai</th>
                  <th style="width: 55px;">No Pegawai</th>
                  <th style="width: 100px;">Jabatan</th>
                  <th style="width: 50px;">Rata Laporan</th>
                  <th style="width: 50px;">Rata Evaluasi</th>
                  <th style="width: 40px;">Hadir</th>
                  <th style="width: 55px;">Nilai SP</th>
                  <th style="width: 45px;">Nilai PK</th>
                  <th style="width: 70px;">Total Nilai Evaluasi</th>
               </tr>
            </thead>
            <tbody>
               <?php
               // Function untuk grade
               if (!function_exists('getGradePrint')) {
                  function getGradePrint($nilai)
                  {
                     if ($nilai >= 90) return ['grade' => 'A', 'class' => 'grade-a'];
                     if ($nilai >= 80) return ['grade' => 'B', 'class' => 'grade-b'];
                     if ($nilai >= 70) return ['grade' => 'C', 'class' => 'grade-c'];
                     if ($nilai >= 60) return ['grade' => 'D', 'class' => 'grade-d'];
                     return ['grade' => 'E', 'class' => 'grade-e'];
                  }
               }

               $no = 1;
               foreach ($data_pegawai as $p):
                  $gl = getGradePrint($p['avg_laporan']);
                  $ge = getGradePrint($p['avg_evaluasi']);
                  $gp = getGradePrint($p['nilai_pk']);
                  $gf = getGradePrint($p['total_nilai_final']);
               ?>
                  <tr>
                     <td class="text-center"><?= $no++ ?></td>
                     <td><?= htmlspecialchars($p['nama']) ?></td>
                     <td class="text-center"><?= htmlspecialchars($p['no_pegawai']) ?></td>
                     <td><?= htmlspecialchars($p['jabatan']) ?></td>
                     <td class="text-center">
                        <?= number_format($p['avg_laporan'], 1) ?>
                        <span class="<?= $gl['class'] ?>">(<?= $gl['grade'] ?>)</span>
                     </td>
                     <td class="text-center">
                        <?= number_format($p['avg_evaluasi'], 1) ?>
                        <span class="<?= $ge['class'] ?>">(<?= $ge['grade'] ?>)</span>
                     </td>
                     <td class="text-center">
                        <?= $p['total_hadir'] ?> Hari
                        <?php if (isset($p['nilai_kehadiran'])): ?>
                           <br>
                           <small style="color: #2e7d32; font-weight: bold;">(Nilai: <?= (round($p['nilai_kehadiran']) == $p['nilai_kehadiran']) ? round($p['nilai_kehadiran']) : number_format($p['nilai_kehadiran'], 1) ?>)</small>
                        <?php endif; ?>
                     </td>
                     <td class="text-center" style="<?= $p['nilai_sp'] < 100 ? 'color: red; font-weight: bold;' : '' ?>">
                        <?= $p['label_sp'] ?> (<?= $p['nilai_sp'] ?>)
                     </td>
                     <td class="text-center">
                        <?= number_format($p['nilai_pk'], 1) ?>
                        <span class="<?= $gp['class'] ?>">(<?= $gp['grade'] ?>)</span>
                     </td>
                     <td class="text-center" style="font-weight: bold;">
                        <?= number_format($p['total_nilai_final'], 2) ?>
                        <span class="<?= $gf['class'] ?>">(<?= $gf['grade'] ?>)</span>
                     </td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>

         <!-- Keterangan -->
         <div class="keterangan-section">
            <strong>Keterangan Grade:</strong>
            <table>
               <tr>
                  <td><span class="grade-a">A</span> = Sangat Baik (≥90)</td>
                  <td><span class="grade-b">B</span> = Baik (≥80)</td>
                  <td><span class="grade-c">C</span> = Cukup (≥70)</td>
                  <td><span class="grade-d">D</span> = Kurang (≥60)</td>
                  <td><span class="grade-e">E</span> = Sangat Kurang (<60)< /td>
               </tr>
            </table>
         </div>

         <!-- TTD Section -->
         <table class="ttd-section">
            <tr>
               <td>
                  <p>Mengetahui,</p>
                  <p>&nbsp;</p>
                  <br><br><br><br>
                  <p>Dian Melati Amelia</p>
                  <p>HR and Legal </p>
               </td>
               <td>
                  <p>Pekanbaru, <?= $tanggal_cetak ?></p>
                  <p>Dibuat oleh,</p>
                  <br><br><br><br>
                  <p>Novemby Ardiansyah Putra</p>
                  <p>General Affairs</p>
               </td>
            </tr>
         </table>
      </div>

      <!-- Footer Kop Surat -->
      <div class="footer-kop">
         <img src="<?= base_url('assets/img/kop_surat_bawahpanjang.jpg') ?>" alt="Footer" />
      </div>
   </div>

   <script>
      window.onload = function() {
         window.print();
      }
   </script>
</body>

</html>