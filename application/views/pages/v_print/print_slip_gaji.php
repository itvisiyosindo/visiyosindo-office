<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title_pdf; ?></title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      color: #333;
      line-height: 1.4;
    }

    .gaji-slip {
      border: 2px solid #000;
      padding: 0;
      margin: 0;
    }

    /* Header & Info Section */
    .header-table {
      width: 100%;
      border-bottom: 2px solid #000;
    }

    .title-section {
      text-align: center;
      margin: 10px 0;
    }

    .title-section h2 {
      margin: 0;
      padding: 0;
      font-size: 18px;
      text-transform: uppercase;
    }

    .periode-text {
      text-align: right;
      font-size: 12px;
      margin: 5px 15px;
    }

    /* Bio Data Section */
    .bio-table {
      width: 100%;
      margin: 10px 15px;
      font-size: 12px;
      border-collapse: collapse;
    }

    .bio-table th {
      text-align: left;
      width: 80px;
    }

    .bio-table td {
      padding: 2px 0;
    }

    /* Main Salary Table */
    .main-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
      border-top: 1px solid #000;
    }

    .main-table thead th {
      border-bottom: 1px solid #000;
      padding: 8px 5px;
      font-size: 12px;
      background-color: #f9f9f9;
    }

    .main-table tbody td,
    .main-table tbody th {
      font-size: 11px;
      padding: 4px 8px;
      vertical-align: top;
    }

    /* Border tengah untuk pemisah A dan B */
    .border-divider {
      border-right: 1px solid #000;
    }

    /* Column Alignment Helpers */
    .label-col {
      width: 35%;
      text-align: left;
      font-weight: bold;
    }

    .sym-col {
      width: 3%;
      text-align: center;
    }

    .curr-col {
      width: 4%;
      text-align: left;
    }

    .val-col {
      width: 8%;
      text-align: right;
    }

    /* Footer / Total Section */
    .total-row {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .total-row td,
    .total-row th {
      border-top: 1px solid #000;
      border-bottom: 1px solid #000;
      padding: 8px;
    }

    .thp-row {
      background-color: #e2e2e2;
      font-size: 13px;
    }

    .terbilang-section {
      padding: 10px 15px;
      font-size: 11px;
      font-style: italic;
      border-top: 1px solid #000;
    }
  </style>
</head>

<body>
  <div class="gaji-slip">
    <table class="header-table">
      <tr>
        <td><img src="assets/img/kop_baru.jpg" width="100%" /></td>
      </tr>
    </table>

    <div class="title-section">
      <h2><u><?= $title_pdf ?></u></h2>
    </div>

    <div class="periode-text">
      <strong>Periode:</strong> <?= $periode ?>
    </div>

    <table class="bio-table">
      <tr>
        <th>Nama</th>
        <td>: <?= $dtgaji->nama ?></td>
        <th style="width: 50px;">Jabatan</th>
        <td>: <?= $dtgaji->jabatan ?></td>
      </tr>
      <tr>
        <th>NPP</th>
        <td>: <?= $dtgaji->npp ?></td>
        <th>Status</th>
        <td>: <?= ucwords($dtgaji->status) ?></td>
      </tr>
    </table>

    <table class="main-table">
      <thead>
        <tr>
          <th colspan="4" class="border-divider" style="text-align: left;">A. PENGHASILAN</th>
          <th colspan="4" style="text-align: left;">B. POTONGAN</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (!empty($dtsalary)) :
          $total_a = ($dtsalary->gajipokok ?? 0) + ($dtsalary->tunjangankonsumsi ?? 0) + ($dtsalary->tunjangankinerja ?? 0) + ($dtsalary->tunjangankomunikasi ?? 0) + ($dtsalary->tunjangantransport ?? 0) + ($dtsalary->tunjanganjabatan ?? 0) + ($dtsalary->bonus ?? 0) + ($dtsalary->tunjanganraya ?? 0) + ($dtsalary->pendapatanlain ?? 0) + ($dtsalary->pendapatanlain_tidaktetap ?? 0) + ($dtsalary->tunjanganbbm ?? 0);
          $total_b = ($dtsalary->bpjskesehatan ?? 0) + ($dtsalary->bpjstk ?? 0) + (($dtsalary->pph21 === null) ? 0 : $dtsalary->pph21) + ($dtsalary->potonganlainnya ?? 0);
          $thp = $total_a - $total_b;
        ?>

          <tr>
            <td class="label-col">1. Gaji Pokok</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->gajipokok) ? rupiah($dtsalary->gajipokok) : '0') ?></td>

            <td class="label-col">1. Pph Pasal 21</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col"><?= ($dtsalary->pph21 !== null ? rupiah($dtsalary->pph21) : '0') ?></td>
          </tr>

          <tr>
            <td class="label-col">2. Tunjangan Konsumsi</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->tunjangankonsumsi) ? rupiah($dtsalary->tunjangankonsumsi) : '0') ?></td>

            <td class="label-col">2. BPJS Kesehatan</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col"><?= (!empty($dtsalary->bpjskesehatan) ? rupiah($dtsalary->bpjskesehatan) : '0') ?></td>
          </tr>

          <tr>
            <td class="label-col">3. Tunjangan Kinerja</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->tunjangankinerja) ? rupiah($dtsalary->tunjangankinerja) : '0') ?></td>

            <td class="label-col">3. BPJS Ketenagakerjaan</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col"><?= (!empty($dtsalary->bpjstk) ? rupiah($dtsalary->bpjstk) : '0') ?></td>
          </tr>

          <tr>
            <td class="label-col">4. Tunjangan Komunikasi</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->tunjangankomunikasi) ? rupiah($dtsalary->tunjangankomunikasi) : '0') ?></td>

            <td class="label-col">4. Potongan Lainnya</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col"><?= (!empty($dtsalary->potonganlainnya) ? rupiah($dtsalary->potonganlainnya) : '0') ?></td>
          </tr>

          <tr>
            <td class="label-col">5. Tunjangan Transport</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->tunjangantransport) ? rupiah($dtsalary->tunjangantransport) : '0') ?></td>
            <td colspan="4"></td>
          </tr>
          <tr>
            <td class="label-col">6. Tunjangan BBM</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->tunjanganbbm) ? rupiah($dtsalary->tunjanganbbm) : '0') ?></td>
            <td colspan="4"></td>
          </tr>
          <tr>
            <td class="label-col">7. Tunjangan Jabatan</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->tunjanganjabatan) ? rupiah($dtsalary->tunjanganjabatan) : '0') ?></td>
            <td colspan="4"></td>
          </tr>
          <tr>
            <td class="label-col">8. Bonus</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->bonus) ? rupiah($dtsalary->bonus) : '0') ?></td>
            <td colspan="4"></td>
          </tr>
          <tr>
            <td class="label-col">9. Tunjangan Hari Raya</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (!empty($dtsalary->tunjanganraya) ? rupiah($dtsalary->tunjanganraya) : '0') ?></td>
            <td colspan="4"></td>
          </tr>
          <tr>
            <td class="label-col">10. Pendapatan Lainnya</td>
            <td class="sym-col">=</td>
            <td class="curr-col">Rp.</td>
            <td class="val-col border-divider"><?= (($dtsalary->pendapatanlain + $dtsalary->pendapatanlain_tidaktetap) > 0 ? rupiah($dtsalary->pendapatanlain + $dtsalary->pendapatanlain_tidaktetap) : '0') ?></td>
            <td colspan="4"></td>
          </tr>

          <tr class="total-row">
            <td colspan="2">TOTAL PENGHASILAN (A)</td>
            <td>Rp.</td>
            <td class="val-col border-divider"><?= rupiah($total_a) ?></td>
            <td colspan="2">TOTAL POTONGAN (B)</td>
            <td>Rp.</td>
            <td class="val-col"><?= ($total_b > 0 ? rupiah($total_b) : '0') ?></td>
          </tr>

          <tr class="total-row thp-row">
            <td colspan="6" style="text-align: right; padding-right: 20px;">TAKE HOME PAY (A - B)</td>
            <td>Rp.</td>
            <td class="val-col"><?= rupiah($thp) ?></td>
          </tr>

          <tr>
            <td colspan="8" class="terbilang-section">
              <strong>TERBILANG:</strong><br>
              # <?= ucwords(penyebut($thp)) ?> Rupiah #
            </td>
          </tr>

        <?php else : ?>
          <tr>
            <td colspan="8" style="text-align:center; padding: 50px;">Data tidak ditemukan.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>

</html>