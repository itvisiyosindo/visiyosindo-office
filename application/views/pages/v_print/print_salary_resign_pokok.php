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
      font-size: 10px;
      background-color: #f2f2f2;
    }

    #table td {
      font-size: 9px;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .tandatangan-container {
      width: 100%;
      margin-top: 30px;
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
          <th width="3%"> No </th>
          <th width="12%"> Nama Karyawan</th>
          <th width="8%"> Lama Kerja</th>
          <th width="9%"> Gaji Pokok </th>
          <th width="8%"> Pendapatan Lainnya</th>
          <th width="8%"> Potongan BPJS Kesehatan</th>
          <th width="8%"> Potongan BPJS TK</th>
          <th width="8%"> Potongan Lainnya</th>
          <th width="9%"> Penghasilan Sebelum Pajak</th>
          <th width="7%"> Pajak PPH21</th>
          <th width="9%"> Penghasilan Setelah Pajak</th>
          <th width="9%"> Nomor Rekening</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;

        // Inisialisasi variabel akumulasi total footer
        $sum_gapok = 0;
        $sum_pendapatan_lain = 0;
        $sum_bpjs_kes = 0;
        $sum_bpjs_tk = 0;
        $sum_potongan_lain = 0;
        $sum_sebelum_pajak = 0;
        $sum_pph21 = 0;
        $sum_setelah_pajak = 0;

        if (!empty($dt)) :
          foreach ($dt as $row):
            // 1. Ambil data dari database
            $gaji_pokok         = (float) $row->gaji_pokok;
            $pendapatan_lainnya  = (float) $row->pendapatan_lain;
            $bpjs_kes           = (float) $row->bpjs_kes;
            $bpjs_tk            = (float) $row->bpjs_tk;
            $potongan_lainnya   = (float) $row->potongan_lain;
            $pph21              = (float) $row->pph21;

            // 2. RUMUS: Hitung Penghasilan Sebelum Pajak
            // Rumus: (Gaji Pokok + Pendapatan Lainnya) - (BPJS Kes + BPJS TK + Potongan Lainnya)
            $sebelum_pajak = ($gaji_pokok + $pendapatan_lainnya) - ($bpjs_kes + $bpjs_tk + $potongan_lainnya);

            // 3. RUMUS: Hitung Penghasilan Setelah Pajak
            // Rumus: Hasil Sebelum Pajak - Pajak PPH21
            $setelah_pajak = $sebelum_pajak - $pph21;

            // 4. Update Akumulasi Total Footer
            $sum_gapok           += $gaji_pokok;
            $sum_pendapatan_lain += $pendapatan_lainnya;
            $sum_bpjs_kes        += $bpjs_kes;
            $sum_bpjs_tk         += $bpjs_tk;
            $sum_potongan_lain   += $potongan_lainnya;
            $sum_sebelum_pajak   += $sebelum_pajak;
            $sum_pph21           += $pph21;
            $sum_setelah_pajak   += $setelah_pajak;
        ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td><?= ucwords(strtolower($row->nama_karyawan)) ?></td>
              <td class="text-center"><?= $row->masa_kerja ?: '0' ?></td>
              <td class="text-right">Rp. <?= $gaji_pokok > 0 ? rupiah($gaji_pokok) : '0' ?></td>
              <td class="text-right">Rp. <?= $pendapatan_lainnya > 0 ? rupiah($pendapatan_lainnya) : '0' ?></td>
              <td class="text-right">Rp. <?= $bpjs_kes > 0 ? rupiah($bpjs_kes) : '0' ?></td>
              <td class="text-right">Rp. <?= $bpjs_tk > 0 ? rupiah($bpjs_tk) : '0' ?></td>
              <td class="text-right">Rp. <?= $potongan_lainnya > 0 ? rupiah($potongan_lainnya) : '0' ?></td>
              <td class="text-right">Rp. <?= $sebelum_pajak > 0 ? rupiah($sebelum_pajak) : '0' ?></td>
              <td class="text-right">Rp. <?= $pph21 > 0 ? rupiah($pph21) : '0' ?></td>
              <td class="text-right"><strong>Rp. <?= $setelah_pajak > 0 ? rupiah($setelah_pajak) : '0' ?></strong></td>
              <td class="text-center"><?= $row->no_rekening ?: '0' ?></td>
            </tr>
          <?php
          endforeach;
        else:
          ?>
          <tr>
            <td colspan="12" class="text-center">Tidak ada data untuk periode ini</td>
          </tr>
        <?php endif; ?>

        <tr style="background-color: #f9f9f9; font-weight: bold;">
          <td colspan="10" class="text-left"><strong>Total Keseluruhan :</strong></td>
          <td class="text-right"><b>Rp. <?= rupiah($sum_setelah_pajak) ?></b></td>
          <td></td>
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
          <td style="width: 29.8296%; height: 18px; text-align: center;">Pekanbaru, <?= indo_dates(date('Y-m-d')) ?></td>
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
          <td style="width: 25%; text-align: center; height: 18px;"><u>Novemby Ardiansyah</u></td>
          <td style="width: 21.8748%; height: 18px; text-align: center;"><u>Azhari Pratama</u></td>
          <td style="width: 25%; text-align: center; height: 18px;"><u>Dirangga Madali</u></td>
          <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Yolanda Pratiwi</u></td>
          <td style="width: 23.2955%; height: 18px; text-align: center;"><u>Meilina Safitri</u></td>
          <td style="width: 29.8296%; text-align: center; height: 18px;"><u>Bob Ariyos</u></td>
        </tr>
        <tr style="height: 18px;">
          <td style="width: 29.8296%; text-align: center; height: 18px;"><i>General Affair</i></td>
          <td style="width: 21.8748%; text-align: center; height: 18px;"><i>Staff Tax</i></td>
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