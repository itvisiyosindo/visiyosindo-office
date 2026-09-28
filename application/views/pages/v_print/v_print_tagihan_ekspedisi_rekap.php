<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Rekap Tagihan Ekspedisi</title>
   <style>
      #table {
         font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
         border-collapse: collapse;
         width: 100%;
      }

      #table td,
      #table th {
         border: 1px solid #ddd;
         padding: 6px;
      }

      #table th {
         padding-top: 8px;
         padding-bottom: 8px;
         text-align: center;
         font-size: 9px;
         background-color: #C6DEFF;
      }

      #table td {
         font-size: 9px;
      }

      .text-center {
         text-align: center;
      }

      .text-right {
         text-align: right;
      }

      .font-bold {
         font-weight: bold;
      }
   </style>
</head>

<body>
   <img src="assets/img/kop_baru.jpg" width="100%" height="15%" />
   <div style="text-align:center">
      <h2 style="text-decoration: underline; margin-bottom: 5px;">REKAP TAGIHAN EKSPEDISI</h2>
      <?php if (!empty($filter_ekspedisi)): ?>
         <p style="margin: 3px 0;">Ekspedisi: <?= $filter_ekspedisi ?></p>
      <?php endif; ?>
      <?php if (!empty($filter_periode)): ?>
         <p style="margin: 3px 0;">Periode: <?= $filter_periode ?></p>
      <?php endif; ?>
   </div>

   <div style="margin-bottom: 15px; font-size: 11px;">
      <table style="border: none;">
         <tr>
            <td style="width: 120px; border: none;">Tanggal Cetak</td>
            <td style="width: 10px; border: none;">:</td>
            <td style="border: none;"><?= date('d F Y H:i') ?></td>
         </tr>
         <tr>
            <td style="border: none;">Total Data</td>
            <td style="border: none;">:</td>
            <td style="border: none;"><?= count($list_tagihan) ?> Tagihan</td>
         </tr>
      </table>
   </div>

   <div>
      <table id="table" style="width:100%">
         <thead>
            <tr>
               <th style="width: 20px;">No</th>
               <th style="width: 70px;">No. Invoice</th>
               <th style="width: 60px;">No. SJ/Kode</th>
               <th>Ekspedisi</th>
               <th>Customer</th>
               <th style="width: 55px;">Tgl Invoice</th>
               <th style="width: 75px;">Nilai Tagihan</th>
               <th style="width: 70px;">Biaya Asuransi</th>
               <th style="width: 80px;">Total Tagihan</th>
               <th style="width: 50px;">Status</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $no = 1;
            $total_nilai = 0;
            $total_asuransi = 0;
            $total_tagihan_all = 0;

            foreach ($list_tagihan as $t):
               $nilai = $t->nilai_tagihan ?? 0;
               $asuransi = $t->biaya_asuransi ?? 0;
               $total_row = $t->total_tagihan ?? ($nilai + $asuransi);

               $total_nilai += $nilai;
               $total_asuransi += $asuransi;
               $total_tagihan_all += $total_row;

               // Determine status text (formal, tanpa warna)
               $status_text = 'Diproses';
               if ($t->status_approval == 5) {
                  $status_text = 'Disetujui';
               } elseif ($t->status_approval == 99) {
                  $status_text = 'Ditolak';
               } elseif ($t->status_approval == 0) {
                  $status_text = 'Revisi';
               }

               // Tentukan No. SJ/Kode berdasarkan tipe
               $is_kirim_dokumen = (!empty($t->id_kirim) && empty($t->id_tracking)) || ($t->tagihan_type ?? '') == 'kirim_dokumen';
               $kode_display = $is_kirim_dokumen ? ($t->kode_kirim ?? '-') : ($t->no_sj ?? '-');
            ?>
               <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><?= $t->no_invoice ?? '-' ?></td>
                  <td><?= $kode_display ?></td>
                  <td><?= $is_kirim_dokumen ? ($t->nama_ekspedisi_kirim ?? $t->ekspedisi_kirim ?? '-') : ($t->nama_ekspedisi ?? '-') ?></td>
                  <td><?= $t->nama_customer ?? '-' ?></td>
                  <td class="text-center"><?= !empty($t->tanggal_invoice) ? date('d/m/Y', strtotime($t->tanggal_invoice)) : '-' ?></td>
                  <td class="text-right">Rp <?= number_format($nilai, 0, ',', '.') ?></td>
                  <td class="text-right"><?= $asuransi > 0 ? 'Rp ' . number_format($asuransi, 0, ',', '.') : '-' ?></td>
                  <td class="text-right font-bold">Rp <?= number_format($total_row, 0, ',', '.') ?></td>
                  <td class="text-center"><?= $status_text ?></td>
               </tr>
            <?php endforeach; ?>

            <tr style="background-color: #f8f9fa;">
               <td colspan="6" class="text-right font-bold">TOTAL KESELURUHAN :</td>
               <td class="text-right font-bold">Rp <?= number_format($total_nilai, 0, ',', '.') ?></td>
               <td class="text-right font-bold"><?= $total_asuransi > 0 ? 'Rp ' . number_format($total_asuransi, 0, ',', '.') : '-' ?></td>
               <td class="text-right font-bold" style="background-color: #d4edda;">Rp <?= number_format($total_tagihan_all, 0, ',', '.') ?></td>
               <td></td>
            </tr>
         </tbody>
      </table>
   </div>

   <br>

   <!-- TTD Section (seperti print_salary_full) -->
   <?php
   $img_path = "uploads/file_karyawan/ttd/";
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
            <td style="text-align: center;" colspan="3">Diverifikasi Oleh,</td>
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
               <img src="<?= $img_path ?>ttd_<?= sessPenggunaId() ?>.png" height="50" alt="TTD" onerror="this.src='<?= $img_path ?>ttd_blank.png'">
            </td>
            <td style="text-align: center;">
               <img src="<?= $img_path ?>ttd_769.png" height="50" alt="TTD Warehouse">
            </td>
            <td style="text-align: center;" colspan="2">
               <img src="<?= $img_path ?>ttd_6.png" height="50" alt="TTD Tax">
            </td>
            <td style="text-align: center;">
               <img src="<?= $img_path ?>ttd_33.png" height="50" alt="TTD Finance">
            </td>
         </tr>
         <tr>
            <td style="text-align: center;"><u><?= $this->session->userdata('nama') ?></u></td>
            <td style="text-align: center;"><u>Fitri Andriani</u></td>
            <td style="text-align: center;" colspan="2"><u>Azhari Pratama</u></td>
            <td style="text-align: center;"><u>Dirangga Madali</u></td>
         </tr>
         <tr>
            <td style="text-align: center;"><i><?= $this->session->userdata('jabatan') ?? 'Staff' ?></i></td>
            <td style="text-align: center;"><i>PJT & Warehouse</i></td>
            <td style="text-align: center;" colspan="2"><i>Senior Tax</i></td>
            <td style="text-align: center;"><i>Head of Accounting and Tax</i></td>
         </tr>
      </tbody>
   </table>

   <!-- Footer Kop Surat -->
   <div style="margin-top: 30px;">
      <img src="assets/img/kop_surat_bawah.jpg" width="100%" />
   </div>
</body>

</html>