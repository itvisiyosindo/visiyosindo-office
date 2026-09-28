<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title_pdf; ?></title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; color: #333; line-height: 1.4; }
    .gaji-slip { border: 2px solid #000; padding: 0; margin: 0; }

    /* Header & Info Section */
    .header-table { width: 100%; border-bottom: 2px solid #000; }
    .title-section { text-align: center; margin: 10px 0; }
    .title-section h2 { margin: 0; padding: 0; font-size: 18px; text-transform: uppercase; }
    .periode-text { text-align: right; font-size: 12px; margin: 5px 15px; }

    /* Bio Data Section */
    .bio-table { width: 100%; margin: 10px 15px; font-size: 12px; border-collapse: collapse; }
    .bio-table th { text-align: left; width: 100px; }
    .bio-table td { padding: 2px 0; }

    /* Main Salary Table */
    .main-table { width: 100%; border-collapse: collapse; table-layout: fixed; border-top: 1px solid #000; }
    .main-table thead th { border-bottom: 1px solid #000; padding: 8px 5px; font-size: 12px; background-color: #f9f9f9; }
    .main-table tbody td, .main-table tbody th { font-size: 11px; padding: 4px 8px; vertical-align: top; }

    /* Border tengah untuk pemisah A dan B */
    .border-divider { border-right: 1px solid #000; }

    /* Column Alignment Helpers */
    .label-col { width: 35%; text-align: left; font-weight: bold; }
    .sym-col { width: 3%; text-align: center; }
    .curr-col { width: 4%; text-align: left; }
    .val-col { width: 8%; text-align: right; }

    /* Footer / Total Section */
    .total-row { background-color: #f2f2f2; font-weight: bold; }
    .total-row td, .total-row th { border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 8px; }
    .thp-row { background-color: #e2e2e2; font-size: 13px; }
    .terbilang-section { padding: 10px 15px; font-size: 11px; font-style: italic; border-top: 1px solid #000; }
  </style>
</head>

<body>
  <?php
    $total_tunjangan = ($row->tunjangan_jabatan ?? 0)
                     + ($row->tunjangan_kinerja ?? 0)
                     + ($row->tunjangan_konsumsi ?? 0)
                     + ($row->tunjangan_komunikasi ?? 0)
                     + ($row->tunjangan_transportasi ?? 0)
                     + ($row->tunjangan_bbm ?? 0)
                     + ($row->tunjangan_lainnya ?? 0);
    $total_potongan = ($row->potongan ?? 0);
    $total_bersih = $total_tunjangan - $total_potongan;
  ?>
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
        <td>: <?= ucwords(strtolower($row->nama_karyawan)) ?></td>
        <th style="width: 100px;">Status</th>
        <td>: <?= ucwords(strtolower($row->status_karyawan)) ?></td>
      </tr>
      <tr>
        <th>No Pegawai</th>
        <td>: <?= $row->no_pegawai ?: '-' ?></td>
        <th>Masa Kerja</th>
        <td>: <?= $row->masa_kerja ?: '-' ?></td>
      </tr>
      <tr>
        <th>Kehadiran</th>
        <td>: <?= $row->hari_kehadiran ?> Hari</td>
        <th>No Rekening</th>
        <td>: <?= $row->no_rekening ?: '-' ?></td>
      </tr>
    </table>

    <table class="main-table">
      <thead>
        <tr>
          <th colspan="4" class="border-divider" style="text-align: left;">A. TUNJANGAN</th>
          <th colspan="4" style="text-align: left;">B. POTONGAN</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="label-col">1. Tunjangan Jabatan</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col border-divider"><?= ($row->tunjangan_jabatan > 0 ? rupiah($row->tunjangan_jabatan) : '0') ?></td>

          <td class="label-col">1. Potongan</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col"><?= ($row->potongan > 0 ? rupiah($row->potongan) : '0') ?></td>
        </tr>

        <tr>
          <td class="label-col">2. Tunjangan Kinerja</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col border-divider"><?= ($row->tunjangan_kinerja > 0 ? rupiah($row->tunjangan_kinerja) : '0') ?></td>
          <td colspan="4"></td>
        </tr>

        <tr>
          <td class="label-col">3. Tunjangan Konsumsi</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col border-divider"><?= ($row->tunjangan_konsumsi > 0 ? rupiah($row->tunjangan_konsumsi) : '0') ?></td>
          <td colspan="4"></td>
        </tr>

        <tr>
          <td class="label-col">4. Tunjangan Komunikasi</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col border-divider"><?= ($row->tunjangan_komunikasi > 0 ? rupiah($row->tunjangan_komunikasi) : '0') ?></td>
          <td colspan="4"></td>
        </tr>

        <tr>
          <td class="label-col">5. Tunjangan Transportasi</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col border-divider"><?= ($row->tunjangan_transportasi > 0 ? rupiah($row->tunjangan_transportasi) : '0') ?></td>
          <td colspan="4"></td>
        </tr>

        <tr>
          <td class="label-col">6. Tunjangan BBM</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col border-divider"><?= ($row->tunjangan_bbm > 0 ? rupiah($row->tunjangan_bbm) : '0') ?></td>
          <td colspan="4"></td>
        </tr>

        <tr>
          <td class="label-col">7. Tunjangan Lainnya</td>
          <td class="sym-col">=</td>
          <td class="curr-col">Rp.</td>
          <td class="val-col border-divider"><?= ($row->tunjangan_lainnya > 0 ? rupiah($row->tunjangan_lainnya) : '0') ?></td>
          <td colspan="4"></td>
        </tr>

        <!-- Total Row -->
        <tr class="total-row">
          <td colspan="2">TOTAL TUNJANGAN (A)</td>
          <td>Rp.</td>
          <td class="val-col border-divider"><?= rupiah($total_tunjangan) ?></td>
          <td colspan="2">TOTAL POTONGAN (B)</td>
          <td>Rp.</td>
          <td class="val-col"><?= ($total_potongan > 0 ? rupiah($total_potongan) : '0') ?></td>
        </tr>

        <!-- Total Bersih -->
        <tr class="total-row thp-row">
          <td colspan="6" style="text-align: right; padding-right: 20px;">TOTAL BERSIH (A - B)</td>
          <td>Rp.</td>
          <td class="val-col"><?= rupiah($total_bersih) ?></td>
        </tr>

        <!-- Terbilang -->
        <tr>
          <td colspan="8" class="terbilang-section">
            <strong>TERBILANG:</strong><br>
            # <?= ucwords(penyebut($total_bersih)) ?> Rupiah #
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

</html>
