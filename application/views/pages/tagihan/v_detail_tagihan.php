<?php
// =========================================================
// LOGIC: MENENTUKAN HAK AKSES TOMBOL DI VIEW
// =========================================================
$status_now    = $detail->status_approval;
$my_jabatan    = trim($current_user_jabatan);
$required_role = '';
$step_name     = 'Menunggu Giliran';
$code_senior_tax = "";

foreach ($approval_configs as $cfg) {
  if ($cfg->status_code == $status_now) {
    $required_role = $cfg->role_label;
    $step_name     = $cfg->role_label;
  }
  if (stripos($cfg->role_label, 'Tax') !== false) {
    $code_senior_tax = $cfg->status_code;
  }
}

$can_approve = false;
if ($required_role != '' && strtolower($my_jabatan) == strtolower($required_role)) $can_approve = true;
if ($is_admin) $can_approve = true;
if ($status_now == 5 || $status_now == 99) $can_approve = false;
?>

<header class="page-header">
  <h2><i class="fas fa-file-invoice-dollar"></i> <?= $page_title ?></h2>
  <div class="right-wrapper text-left">
    <ol class="breadcrumbs">
      <li><span>Detail Data Tagihan Ekspedisi : <?= $detail->no_pengiriman ?></span></li>
    </ol>
  </div>
</header>

<style>
  /* --- MODERN DASHBOARD STYLES --- */
  :root {
    --text-label: #8898aa;
    --text-dark: #32325d;
    --card-radius: 10px;
    --red-color: #ff5757;
  }

  .text-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--text-label);
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
  }

  .text-value {
    font-size: 0.9rem;
    color: var(--text-dark);
    font-weight: 600;
    line-height: 1.4;
  }

  .text-value-lg {
    font-size: 1.3rem;
    color: #1565c0;
    font-weight: 800;
  }

  /* Card Styling */
  .card-modern {
    border: 0;
    border-radius: var(--card-radius);
    box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
    background: #fff;
    margin-bottom: 24px;
    overflow: hidden;
  }

  .card-header-modern {
    background: #fff;
    border-bottom: 1px solid #f6f9fc;
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .card-header-modern h6 {
    margin: 0;
    font-weight: 700;
    color: #32325d;
    font-size: 0.95rem;
    text-transform: uppercase;
  }

  /* Sticky Sidebar */
  .sticky-sidebar {
    position: sticky;
    top: 20px;
    z-index: 99;
  }

  .info-box {
    background-color: #f6f9fc;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
  }

  /* Timeline Approval */
  .approval-track {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 25px 0 10px;
  }

  .approval-track::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 0;
  }

  .approval-step {
    position: relative;
    z-index: 1;
    text-align: center;
    background: #fff;
    padding: 0 10px;
  }

  .approval-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #e9ecef;
    color: #adb5bd;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
  }

  .approval-step.active .approval-icon {
    border-color: #3498db;
    background: #3498db;
    color: #fff;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
  }

  .approval-step.completed .approval-icon {
    border-color: #2ecc71;
    background: #2ecc71;
    color: #fff;
  }

  .approval-label {
    font-size: 0.65rem;
    font-weight: bold;
    color: #8898aa;
    text-transform: uppercase;
  }

  /* Vertical Timeline */
  .vertical-timeline {
    list-style: none;
    padding: 0;
    position: relative;
    margin: 0;
  }

  .vertical-timeline::before {
    content: '';
    position: absolute;
    top: 5px;
    bottom: 0;
    left: 10px;
    width: 2px;
    background: #e9ecef;
  }

  .vertical-timeline li {
    position: relative;
    padding-left: 35px;
    margin-bottom: 25px;
  }

  .timeline-dot {
    position: absolute;
    left: 4px;
    top: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #dee2e6;
    z-index: 1;
  }

  .vertical-timeline li:first-child .timeline-dot {
    border-color: #2ecc71;
    background: #2ecc71;
  }

  /* Container agar posisi tengah */
  .wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .checkmark {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    /* Warna garis centang */
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #7ac142;
    /* Warna hijau awal */
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
  }

  /* Lingkaran luar */
  .checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #7ac142;
    /* Warna garis lingkaran hijau */
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  /* Garis Centang */
  .checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

  /* Keyframes Animasi */
  @keyframes stroke {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes scale {

    0%,
    100% {
      transform: none;
    }

    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }

  @keyframes fill {
    100% {
      box-shadow: inset 0px 0px 0px 30px #7ac142;
    }

    /* Efek isi warna hijau */
  }

  /* Penempatan Kontainer */
  .wrapper.rejected {
    display: flex;
    justify-content: center;
    align-items: center;
    /* Atur padding/margin sesuai kebutuhan layout Anda */
  }

  /* Styling Ikon Crossmark */
  .crossmark {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    /* Warna garis X adalah putih */
    stroke-miterlimit: 10;
    /* Shadow ini akan dianimasikan untuk mengisi lingkaran */
    box-shadow: inset 0px 0px 0px var(--red-color);
    animation: fill_cross .4s ease-in-out .4s forwards, scale_cross .3s ease-in-out .9s both;
  }

  /* Lingkaran Luar (Stroke) */
  .crossmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: var(--red-color);
    /* Warna garis lingkaran merah */
    fill: none;
    animation: stroke_cross 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  /* Garis X (Cross) */
  .crossmark__line {
    fill: none;
    /* Hitung panjang garis (approx 28) */
    stroke-dasharray: 40;
    stroke-dashoffset: 40;
  }

  /* Garis X Pertama */
  .crossmark__line--first {
    transform-origin: 50% 50%;
    /* Animasikan setelah lingkaran terisi, bersamaan dengan garis kedua */
    animation: stroke_cross 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

  /* Garis X Kedua (Dimulai sedikit setelah garis pertama untuk efek yang lebih baik) */
  .crossmark__line--second {
    transform-origin: 50% 50%;
    animation: stroke_cross 0.3s cubic-bezier(0.65, 0, 0.45, 1) 1.0s forwards;
  }


  /* Keyframes Animasi */

  /* Menggambar Garis Lingkaran dan Garis X */
  @keyframes stroke_cross {
    100% {
      stroke-dashoffset: 0;
    }
  }

  /* Efek Fill (Mengisi Lingkaran dengan Warna Merah) */
  @keyframes fill_cross {
    100% {
      box-shadow: inset 0px 0px 0px 30px var(--red-color);
    }
  }

  /* Efek Scale (Pop-up Sedikit) */
  @keyframes scale_cross {

    0%,
    100% {
      transform: none;
    }

    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }
</style>

<!-- Tombol Kembali -->
<div class="mb-4 d-flex justify-content-between align-items-center">
  <a href="javascript:void(0);" onclick="history.back();" class="btn btn-secondary shadow-sm">
    <i class="fas fa-arrow-left mr-1"></i> Kembali
  </a>
  <?php if (isAdmin()): ?>
    <button type="button" onclick="openAdminEditModal('tagihan', '<?= encrypt($detail->id_tagihan) ?>')" class="btn btn-warning font-weight-bold text-dark shadow-sm">
      <i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
    </button>
  <?php endif; ?>
</div>

<div class="row">

  <div class="col-lg-8 col-md-12">

    <div class="card card-modern">
      <div class="card-body pt-4 pb-2">
        <h6 class="text-center text-label mb-2">STATUS APPROVAL</h6>
        <div class="approval-track px-3">
          <?php foreach ($approval_configs as $cfg):
            // Pastikan pengecekan is_active lebih kuat (mengantisipasi string/int)
            if ($cfg->is_active != 1) continue;

            // Perbaikan logika Class:
            // 1. 'completed' jika status sudah MELEWATI kode ini
            // 2. 'active' jika status TEPAT di kode ini
            $is_completed = ($status_now > $cfg->status_code || $status_now == 5) && $status_now != 99;
            $is_active = ($status_now == $cfg->status_code);

            $activeClass = $is_completed ? 'completed' : ($is_active ? 'active' : '');
          ?>
            <div class="approval-step <?= $activeClass ?>">
              <div class="approval-icon">
                <?php if ($is_completed): ?><i class="fas fa-check"></i>
                <?php else: ?><i class="fas fa-circle" style="font-size:0.5rem"></i><?php endif; ?>
              </div>
              <div class="approval-label"><?= $cfg->role_label ?></div>
            </div>
          <?php endforeach; ?>

          <div class="approval-step <?= ($status_now == 5) ? 'completed' : '' ?>">
            <div class="approval-icon"><i class="fas fa-flag-checkered"></i></div>
            <div class="approval-label">Selesai</div>
          </div>
        </div>

        <?php if ($status_now == 99): ?>
          <div class="alert alert-danger mx-4 mt-3 text-center py-2 border-0 shadow-sm"><i class="fas fa-times-circle mr-1"></i> Pengajuan Ditolak</div>
        <?php elseif ($status_now == 0): ?>
          <div class="alert alert-warning mx-4 mt-3 text-center py-2 border-0 shadow-sm d-flex justify-content-center align-items-center" style="gap: 4px;">
            <div class="spinner-border spinner-border-sm text-dark" role="status">
              <span class="hidden">Loading...</span>
            </div>
            <span class="text-dark font-weight-bold">Sedang dalam Revisi</span>
          </div>
        <?php endif; ?>

        <!-- Print Button -->
        <div class="text-center mt-3 pb-2">
          <a href="<?= base_url('tagihan/print_tagihan/' . encrypt($detail->id_tagihan)) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-print mr-1"></i> Cetak Tagihan
          </a>
        </div>
      </div>
    </div>

    <div class="card card-modern border-left-primary" style="border-left: 4px solid #1565c0;">
      <div class="card-header-modern">
        <h6><i class="fas fa-file-invoice-dollar text-success mr-2"></i>Data Penagihan</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-3">
              <span class="text-label">No. Invoice</span>
              <div class="text-value" style="font-size: 1.1rem;"><i class="fas fa-file-invoice text-info mr-2"></i><?= $detail->no_invoice ?? '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Nilai Tagihan (Ongkir)</span>
              <div class="text-value-lg text-dark">Rp <?= number_format($detail->nilai_tagihan, 0, ',', '.') ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Biaya Asuransi</span>
              <?php if (!empty($detail->biaya_asuransi) && $detail->biaya_asuransi > 0): ?>
                <div class="text-value-lg text-info">Rp <?= number_format($detail->biaya_asuransi, 0, ',', '.') ?></div>
              <?php else: ?>
                <div class="text-muted" style="font-size: 1rem;">Rp 0</div>
                <small class="text-muted font-italic"><i class="fas fa-info-circle mr-1"></i>Tagihan ini tidak memiliki biaya asuransi</small>
              <?php endif; ?>
            </div>
            <div class="form-group mb-3 p-3 rounded" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
              <span class="text-label text-success"><i class="fas fa-calculator mr-1"></i>Total Tagihan</span>
              <div class="text-value-lg text-success" style="font-size: 1.5rem;">Rp <?= get_display_total_tagihan($detail) ?></div>
              <small class="text-success"><i class="fas fa-info-circle mr-1"></i>Nominal ini yang akan dibayarkan.</small>
            </div>
            <div class="form-group mb-0">
              <span class="text-label">Tanggal Invoice</span>
              <div class="text-value-lg text-danger"><i class="fas fa-calendar-check mr-2 "></i> <?= date('d F Y', strtotime($detail->tanggal_invoice)) ?></div>
            </div>
          </div>
          <div class="col-md-6 bg-light p-3 rounded">
            <span class="text-label mb-3">Lampiran Penagihan</span>
            <ul class="list-unstyled mb-0">
              <li class="mb-2 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-invoice text-primary mr-2"></i> Invoice Ekspedisi</span>
                <a href="<?= $detail->link_invoice ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
              </li>
              <li class="mb-2 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-alt text-dark mr-2"></i> Faktur Pajak</span>
                <a href="<?= $detail->link_faktur_pajak ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3 text-white"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
              </li>
              <?php if (!empty($detail->link_dokumen_lain)): ?>
                <li class="mb-2 d-flex justify-content-between align-items-center">
                  <span><i class="fas fa-folder-open text-primary mr-2"></i> Dok. Pendukung</span>
                  <a href="<?= $detail->link_dokumen_lain ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
                </li>
              <?php endif; ?>

              <?php if (!empty($detail->link_bukti_potong)): ?>
                <li class="d-flex justify-content-between align-items-center">
                  <span><i class="fas fa-receipt text-dark mr-2"></i> Bukti Potong (By: <span class="text-primary">Senior Tex</span>)</span>
                  <a href="<?= $detail->link_bukti_potong ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-modern">
      <div class="card-header-modern">
        <h6><i class="fas fa-box-open text-warning mr-2"></i>Fisik Barang</h6>
        <?php
        // Tampilkan badge tipe tracking
        $tracking_type = isset($tracking_type) ? $tracking_type : 'pengeluaran_barang';
        if ($tracking_type == 'pengiriman_stok'): ?>
          <span class="badge badge-warning px-3 py-1">Transfer Stok Antar Gudang</span>
        <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
          <span class="badge badge-info px-3 py-1">Serah Terima Barang (STTB)</span>
        <?php else: ?>
          <span class="badge badge-primary px-3 py-1">Pengeluaran ke Customer</span>
        <?php endif; ?>
      </div>
      <div class="card-body p-0">
        <div class="row no-gutters">
          <div class="col-md-7 p-4 border-right">
            <?php if ($tracking_type == 'pengiriman_stok'): ?>
              <!-- PENGIRIMAN STOK -->
              <div class="mb-1">
                <span class="text-label text-warning">Gudang Asal</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $detail->nama_gudang_asal ?? $detail->nama_gudang ?></h5>
                <p class="text-muted text-dark small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $detail->alamat_gudang_asal ?? '-' ?></p>
              </div>
              <div class="mb-2">
                <span class="text-label text-success">Gudang Tujuan</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $detail->nama_gudang_tujuan ?? '-' ?></h5>
                <p class="text-muted text-dark small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $detail->alamat_gudang_tujuan ?? $detail->alamat_penerima ?></p>
              </div>
            <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
              <!-- SERAH TERIMA BARANG -->
              <div class="mb-1">
                <span class="text-label text-info">Pihak Pertama (Pengirim)</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $detail->nama_pihak1 ?? '-' ?></h5>
                <p class="text-muted text-dark small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $detail->alamat_pihak1 ?? '-' ?></p>
              </div>
              <div class="mb-2">
                <span class="text-label text-success">Pihak Kedua (Penerima)</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $detail->nama_pihak2 ?? $detail->nama_customer ?></h5>
                <p class="text-muted text-dark small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $detail->alamat_pihak2 ?? $detail->alamat_penerima ?></p>
              </div>
            <?php else: ?>
              <!-- PENGELUARAN BARANG (Default) -->
              <div class="mb-1">
                <span class="text-label text-primary">Customer Tujuan</span>
                <h5 class="font-weight-bold text-dark mt-1"><?= $detail->nama_customer ?></h5>
                <p class="text-muted text-dark small mb-0"><i class="fas fa-map-pin mr-1"></i> <?= $detail->alamat_penerima ?></p>
              </div>
              <div class="mb-2">
                <span class="text-label text-primary">PIC Penerima</span><span class="text-value">
                  <?= $detail->pic_penerima ?>
                </span>
              </div>
            <?php endif; ?>
            <div class="row">
              <div class="col-12 mb-3">
                <div class="mt-3">
                  <span class="text-label text-primary mb-2 d-block">Rincian Item Barang</span>

                  <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0" style="font-size: 0.8rem;">
                      <thead style="background: #f6f9fc;">
                        <tr>
                          <th width="5%" class="text-center">#</th>
                          <th>Nama Barang</th>
                          <?php if ($tracking_type == 'serah_terima_barang'): ?>
                            <th>Merk</th>
                            <th class="text-center">Qty</th>
                            <th>Satuan</th>
                            <th>Batch</th>
                          <?php else: ?>
                            <th>NIE</th>
                            <th class="text-center">Qty</th>
                            <th>Batch</th>
                            <th>Exp Date</th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // KONDISI 1: Ada Data Detail Array
                        if (!empty($detail_barang_keluar)):
                          $no = 1;
                          foreach ($detail_barang_keluar as $row):
                            if ($tracking_type == 'serah_terima_barang'):
                        ?>
                              <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle font-weight-bold"><?= $row->nama_barang ?></td>
                                <td class="align-middle"><?= $row->merk ?? '-' ?></td>
                                <td class="text-center align-middle text-dark font-weight-bold"><?= $row->qty ?></td>
                                <td class="align-middle"><?= $row->satuan ?? '-' ?></td>
                                <td class="align-middle"><?= $row->no_batch ?? '-' ?></td>
                              </tr>
                            <?php else:
                              $exp = (!empty($row->exp_date) && date('Y', strtotime($row->exp_date)) > 2000)
                                ? date('d M Y', strtotime($row->exp_date)) : '-';
                            ?>
                              <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle font-weight-bold"><?= $row->nama_barang ?></td>
                                <td class="align-middle"><?= $row->nie ?? '-' ?></td>
                                <td class="text-center align-middle text-dark font-weight-bold"><?= $row->qty ?></td>
                                <td class="align-middle"><?= $row->no_batch ?? '-' ?></td>
                                <td class="align-middle text-danger"><?= $exp ?></td>
                              </tr>
                            <?php endif;
                          endforeach;

                        // KONDISI 2: Fallback (Cuma ada String Nama Barang)
                        elseif (!empty($tracking->nama_barang) && $tracking->nama_barang != '-'):
                          $list_barang = explode(',', $tracking->nama_barang);
                          $no = 1;
                          foreach ($list_barang as $brg):
                            ?>
                            <tr>
                              <td class="text-center"><?= $no++ ?></td>
                              <td colspan="5"><strong><?= trim($brg) ?></strong> <span class="text-muted font-italic ml-2">(Detail qty/batch tidak tersedia)</span></td>
                            </tr>
                          <?php endforeach; ?>

                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center">- Tidak ada data barang -</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="col-md-5 bg-light p-4">
            <div class="mb-4">
              <span class="text-label">Nomor Resi / AWB</span>
              <div class="text-value-lg text-dark mt-1" style="font-size:1.5rem"><?= $detail->no_resi ?></div>
              <a href="<?= base_url('tracking/detail/' . encrypt($detail->id_tracking)) ?>" target="_blank">
                <span class="text-primary font-monospace"><i class="fas fa-link mr-2"></i><?= $detail->no_sj ?></span>
              </a>
            </div>
            <div class="mb-4">
              <span class="text-label">Tanggal Diterima</span>
              <div class="text-value-lg text-dark mt-1" style="font-size:1.5rem">
                <?php
                // LOGIC SINKRON:
                // 1. Cek tgl_penerima (input manual)
                // 2. Jika kosong, cek tgl_log_status_5 (waktu sistem saat status 5 dibuat)
                // 3. Jika masih kosong (misal belum status 5), baru pakai tgl tagihan dibuat

                $fix_tgl_diterima = $detail->created_at; // Default
                if (!empty($detail->tgl_penerima)) {
                  $fix_tgl_diterima = $detail->tgl_penerima;
                } elseif (!empty($detail->tgl_log_status_5)) {
                  $fix_tgl_diterima = $detail->tgl_log_status_5;
                }

                echo date('d F Y', strtotime($fix_tgl_diterima));
                ?>
              </div>
            </div>

            <?php if (!empty($detail->link_resi) && $detail->link_resi != '-'): ?>
              <a href="<?= $detail->link_resi ?>" target="_blank" class="btn btn-primary btn-block rounded-pill shadow-sm"><i class="fas fa-external-link-alt mr-2"></i> Cek Resi Online</a>
            <?php endif; ?>

            <?php if ($detail->biaya_real) { ?>
              <div class="mb-4">
                <span class="text-label">Biaya Pengiriman</span>
                <div class="text-value text-danger mt-1">Rp <?= number_format($detail->biaya_real, 0, ',', '.') ?></div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <div class="row d-flex align-items-stretch">
      <div class="col-md-6 mb-4 d-flex">
        <div class="card card-modern w-100 h-100">
          <div class="card-header-modern">
            <h6><i class="fas fa-truck text-primary mr-2"></i>Ekspedisi</h6>
            <?php if ($detail->kerjasama == 'Kontrak'): ?>
              <span class="badge badge-success px-2">CONTRACT</span>
            <?php else: ?>
              <span class="badge badge-secondary px-2">NON-CONTRACT</span>
            <?php endif; ?>
          </div>
          <div class="card-body d-flex flex-column">
            <h5 class="mb-1 text-dark font-weight-bold"><?= $detail->nama_ekspedisi ?></h5>
            <small class="text-muted mb-3"><i class="fas fa-map-marker-alt mr-1"></i> <?= $detail->alamat_ekspedisi ?></small>

            <hr class="my-2 w-100 border-light">
            <div class="row mt-2">
              <div class="col-6 mb-3"><span class="text-label">PIC Vendor</span><span class="text-value"><?= $detail->pic_ekspedisi ?? '-' ?></span></div>
              <div class="col-6 mb-3"><span class="text-label">Kontak</span><span class="text-value"><?= $detail->kontak_ekspedisi ?? '-' ?></span></div>
              <div class="col-6 mb-3"><span class="text-label">Payment Term</span><span class="text-value text-danger font-weight-bold"><?= $detail->payment_term ?? '-' ?></span></div>
              <div class="col-6 mb-3"><span class="text-label">PPh 23</span><span class="text-value"><?= $detail->pph23 ?? '-' ?></span></div>
            </div>
            <div class="mt-auto pt-3 border-top">
              <span class="text-label mb-2">Legalitas</span>
              <div class="d-flex">
                <?php if (!empty($detail->link_mou)): ?>
                  <a href="<?= $detail->link_mou ?>" target="_blank" class="btn btn-sm btn-outline-primary mr-2 flex-fill"><i class="fas fa-file-contract"></i> Link MOU</a>
                <?php endif; ?>
                <?php if (!empty($detail->link_legalitas)): ?>
                  <a href="<?= $detail->link_legalitas ?>" target="_blank" class="btn btn-sm btn-outline-info flex-fill"><i class="fas fa-balance-scale"></i> Link Perjanjian Kerja Sama</a>
                <?php endif; ?>
                <?php if (empty($detail->link_mou) && empty($detail->link_legalitas)): ?>
                  <span class="text-muted small font-italic">Tidak ada dokumen.</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4 d-flex">
        <div class="card card-modern w-100 h-100">
          <div class="card-header-modern">
            <?php if ($tracking_type == 'pengiriman_stok'): ?>
              <h6><i class="fas fa-exchange-alt text-warning mr-2"></i>Detail Pemindahan</h6>
            <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
              <h6><i class="fas fa-handshake text-info mr-2"></i>Detail Serah Terima</h6>
            <?php else: ?>
              <h6><i class="fas fa-warehouse text-info mr-2"></i>Asal Barang</h6>
            <?php endif; ?>
          </div>
          <div class="card-body d-flex flex-column">
            <div class="info-box mb-3 flex-fill">
              <div class="row">
                <?php if ($tracking_type == 'pengiriman_stok'): ?>
                  <!-- PENGIRIMAN STOK -->
                  <div class="col-5 border-right">
                    <span class="text-label">No. Pemindahan</span>
                    <div class="text-value font-weight-bold mt-1 text-warning"><?= $detail->no_pemindahan ?? '-' ?></div>
                  </div>
                  <div class="col-7 pl-3">
                    <span class="text-label">No. Pengiriman/Surat Jalan</span>
                    <div class="text-value mt-1"><?= $detail->no_pengiriman ?? '-' ?></div>
                  </div>
                <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
                  <!-- SERAH TERIMA BARANG -->
                  <div class="col-5 border-right">
                    <span class="text-label">Kode STTB</span>
                    <div class="text-value font-weight-bold mt-1 text-info"><?= $detail->kode_stb ?? '-' ?></div>
                  </div>
                  <div class="col-7 pl-3">
                    <span class="text-label">No. Pengiriman/Surat Jalan</span>
                    <div class="text-value mt-1"><?= $detail->no_pengiriman ?? '-' ?></div>
                  </div>
                <?php else: ?>
                  <!-- PENGELUARAN BARANG (Default) -->
                  <div class="col-4 border-right"><span class="text-label">No. PO</span>
                    <div class="text-value font-weight-bold mt-1 text-primary"><?= $detail->no_po ?? '-' ?></div>
                  </div>
                  <div class="col-8 pl-3"><span class="text-label">No. Pengiriman/Surat Jalan</span>
                    <div class="text-value mt-1"><?= $detail->no_pengiriman ?? '-' ?></div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="px-2">
              <?php if ($tracking_type == 'pengiriman_stok'): ?>
                <div class="mb-3"><span class="text-label">Gudang Asal</span><span class="text-value"><i class="fas fa-building mr-2 text-muted"></i> <?= $detail->nama_gudang_asal ?? $detail->nama_gudang ?></span></div>
                <div class="mb-3"><span class="text-label">Gudang Tujuan</span><span class="text-value"><i class="fas fa-building mr-2 text-success"></i> <?= $detail->nama_gudang_tujuan ?? '-' ?></span></div>
              <?php elseif ($tracking_type == 'serah_terima_barang'): ?>
                <div class="mb-3"><span class="text-label">Kota Pengiriman</span><span class="text-value"><i class="fas fa-map-marker-alt mr-2 text-muted"></i> <?= $detail->kota_stb ?? '-' ?></span></div>
                <div class="mb-3"><span class="text-label">Pengirim</span><span class="text-value"><i class="fas fa-user mr-2 text-muted"></i> <?= $detail->nama_pengirim ?? $detail->nama_pengirim_stb ?? '-' ?></span></div>
              <?php else: ?>
                <div class="mb-3"><span class="text-label">Gudang Pengirim</span><span class="text-value"><i class="fas fa-building mr-2 text-muted"></i> <?= $detail->nama_gudang ?></span></div>
              <?php endif; ?>

              <div class="d-flex align-items-center justify-content-between">
                <div class="mb-3">
                  <span class="text-label"><?= $tracking_type == 'pengiriman_stok' ? 'Tanggal Pengiriman' : 'Tanggal Keluar' ?></span>
                  <span class="text-value"><i class="far fa-clock mr-2 text-muted"></i>
                    <?php
                    if ($tracking_type == 'pengiriman_stok' && isset($detail->tgl_pengiriman_stok)) {
                      echo date('d F Y', strtotime($detail->tgl_pengiriman_stok));
                    } elseif ($tracking_type == 'serah_terima_barang' && isset($detail->tgl_stb)) {
                      echo date('d F Y', strtotime($detail->tgl_stb));
                    } elseif (isset($detail->tgl_keluar_gudang)) {
                      echo date('d F Y', strtotime($detail->tgl_keluar_gudang));
                    } else {
                      echo '-';
                    }
                    ?>
                  </span>
                </div>
                <div class="mb-3">
                  <span class="text-label">Estimasi Sampai</span>
                  <span class="text-value"><i class="far fa-clock mr-2 text-muted"></i> <?= isset($detail->tgl_sampai) ? date('d F Y', strtotime($detail->tgl_sampai)) : '-' ?></span>
                </div>
              </div>
            </div>
            <?php if (!empty($detail->link_file_po)): ?>
              <div class="mt-auto pt-3 border-top"><a href="<?= $detail->link_file_po ?>" target="_blank" class="btn btn-info btn-block shadow-sm"><i class="fas fa-cloud-download-alt mr-2"></i> Unduh PO / DO</a></div>
            <?php elseif ($tracking_type == 'pengiriman_stok' || $tracking_type == 'serah_terima_barang'): ?>
              <div class="mt-auto pt-3 border-top text-center"><span class="text-muted small">Tidak ada lampiran dokumen</span></div>
            <?php else: ?>
              <div class="mt-auto pt-3 border-top text-center"><span class="text-muted small">Tidak ada lampiran PO/DO</span></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-12">
    <div class="sticky-sidebar">

      <?php if ($can_approve): ?>
        <div class="card card-modern border-left-warning" style="border-left: 5px solid #ffc107;">
          <div class="card-body text-center pt-4">
            <h5 class="font-weight-bold mb-1">Panel Approval</h5>
            <p class="text-muted small mb-4">Login sebagai: <b><?= $current_user_jabatan ?></b></p>

            <?php if ($is_admin): ?>
              <div class="alert alert-soft-warning py-1 small mb-3"><i class="fas fa-shield-alt"></i> Override by Admin</div>
            <?php endif; ?>

            <button class="btn btn-success btn-block btn-lg shadow-sm mb-3 btn-action" data-id="<?= $detail->id_tagihan ?>" data-status="<?= $detail->status_approval ?>" data-aksi="setujui">
              <i class="fas fa-check-circle mr-2"></i> SETUJUI PENGAJUAN
            </button>

            <div class="row">
              <div class="col-6 pr-1"><button class="btn btn-warning btn-block shadow-sm btn-action" data-id="<?= $detail->id_tagihan ?>" data-status="<?= $detail->status_approval ?>" data-aksi="revisi"><i class="fas fa-undo"></i> Revisi</button></div>
              <div class="col-6 pl-1"><button class="btn btn-danger btn-block shadow-sm btn-action" data-id="<?= $detail->id_tagihan ?>" data-status="<?= $detail->status_approval ?>" data-aksi="tolak"><i class="fas fa-times"></i> Tolak</button></div>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="card card-modern bg-light">
          <div class="card-body text-center py-5">
            <?php if ($status_now == 5): ?>
              <div class="wrapper">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                  <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                  <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                </svg>
              </div>
              <h4 class="text-success font-weight-bold">SELESAI</h4>
              <p class="text-muted">Tagihan Ekspedisi Sudah Disetujui.</p>
            <?php elseif ($status_now == 99): ?> <!-- jika status di db = 99, maka status ditolak -->
              <div class="wrapper rejected">
                <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                  <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none" />
                  <path class="crossmark__line crossmark__line--first" fill="none" d="M16 16l20 20" />
                  <path class="crossmark__line crossmark__line--second" fill="none" d="M36 16L16 36" />
                </svg>
              </div>
              <h4 class="text-danger font-weight-bold">DITOLAK</h4>
              <p class="text-muted">Pengajuan Tagihan Ekspedisi <span class="font-weight-bold">&rdquo;DITOLAK&ldquo;</span>.</p>
            <?php else: ?>
              <div class="mb-3">
                <i class="fas fa-user-clock text-warning fa-3x"></i>
              </div>
              <h5 class="text-muted font-weight-bold mb-2 d-flex align-items-center justify-content-center small" style="gap: 4px;">
                <div class="spinner-border spinner-border-sm text-dark" role="status">
                  <span class="hidden">Loading...</span>
                </div>
                <span class="text-dark">Menunggu Persetujuan</span>
              </h5>
              <span class="badge badge-warning px-3 py-2 shadow-sm" style="font-size: 0.9rem;">
                <?= $step_name ?>
              </span>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="card card-modern">
        <div class="card-header-modern">
          <h6><i class="fas fa-history text-muted mr-2"></i>Riwayat Status</h6>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
          <ul class="vertical-timeline mt-2">
            <li>
              <span class="timeline-dot" style="background:#2ecc71; border-color:#2ecc71"></span>
              <div class="mb-1"><span class="font-weight-bold text-dark">Diajukan</span></div>
              <small class="text-muted"><?= $detail->nama_pengaju ?></small>
            </li>
            <?php foreach ($approval_configs as $cfg):
              if ($cfg->is_active == 0) continue;
              $is_passed  = ($status_now > $cfg->status_code) || ($status_now == 5);
              $is_current = ($status_now == $cfg->status_code);
              $color      = $is_passed ? '#2ecc71' : ($is_current ? '#3498db' : '#dee2e6');
            ?>
              <li>
                <span class="timeline-dot" style="background:<?= $color ?>; border-color:<?= $color ?>"></span>
                <div class="mb-1">
                  <span class="font-weight-bold <?= $is_current ? 'text-primary' : 'text-dark' ?>"><?= $cfg->role_label ?></span>
                </div>
                <?php if ($is_current): ?><small class="text-primary font-italic">Approval Diproses...</small><?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
          <?php if (!empty($detail->catatan_revisi)): ?>
            <div class="alert alert-danger mt-3 small p-2"><strong>Catatan Terakhir:</strong><br>"<?= $detail->catatan_revisi ?>"</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="card card-modern">
      <div class="card-header-modern">
        <h6><i class="fas fa-history text-secondary mr-2"></i>Riwayat Perjalanan</h6>
        <span class="badge badge-light text-primary border"><?= count($tracking_history_log) ?> Update</span>
      </div>
      <div class="card-body" style="max-height: 400px; overflow-y: auto; scrollbar-width: thin;">
        <?php if (!empty($tracking_history_log)): ?>
          <ul class="vertical-timeline mt-2">
            <?php foreach ($tracking_history_log as $log): ?>
              <?php
              $status_name = 'Unknown';
              if ($log->id_status == 1) $status_name = 'Proses Kirim';
              elseif ($log->id_status == 2) $status_name = 'Manifest Berangkat';
              elseif ($log->id_status == 3) $status_name = 'Proses Sortir / Transit';
              elseif ($log->id_status == 4) $status_name = 'Pengantaran Kurir';
              elseif ($log->id_status == 5) $status_name = 'Barang Diterima';
              elseif ($log->id_status == 6) $status_name = 'Menunggu Konfirmasi';

              $is_finish = ($log->id_status == 5);
              ?>
              <li>
                <span class="timeline-dot"></span>
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="font-weight-bold <?= $is_finish ? 'text-success' : 'text-dark' ?>" style="font-size:0.9rem"><?= $status_name ?></span>
                  <small class="text-muted text-right font-weight-bold d-flex flex-column" style="font-size:0.75rem;">
                    <span class="small text-muted mb-0">Data Diupdate pada</span>
                    <span><?= date('d M Y', strtotime($log->created_at)) ?></span>
                    <span class="text-primary"><?= date('H:i', strtotime($log->created_at)) ?></span>
                  </small>
                </div>

                <div class="bg-light p-3 rounded border-left border-3 <?= $is_finish ? 'border-success' : 'border-primary' ?>">

                  <div class="pt-2">
                    <ul class="list-unstyled mb-0 small text-muted">
                      <?php if ($log->id_status == 5): ?>
                        <li class="mb-1 text-success">
                          <i class="fas fa-calendar-check mr-2"></i>Tanggal Diterima:
                          <strong><?= !empty($log->tgl_penerima) ? date('d M Y', strtotime($log->tgl_penerima)) :  date('d M Y', strtotime($log->created_at)) ?></strong>
                        </li>
                        <?php if (!empty($log->nama_penerima)): ?>
                          <li class="text-success border-bottom mb-0">
                            <i class="fas fa-user-check mr-2"></i>Diterima Oleh:
                            <strong><?= $log->nama_penerima ?></strong>
                          </li>
                        <?php endif; ?>
                        <li class="mb-2">
                          <i class="fas fa-user-edit mr-2 text-secondary"></i>Update Oleh:
                          <strong><?= isset($log->nama_penerima) ? $log->nama_penerima : 'System/Admin' ?></strong>
                        </li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>

                <?php if ($is_finish && !empty($log->bukti_penerima)): ?>
                  <div class="mt-2">
                    <a href="<?= $log->bukti_penerima ?>" target="_blank" class="btn btn-sm btn-success shadow-sm rounded-pill px-3">
                      <i class="fas fa-image mr-1"></i> Bukti Foto
                    </a>
                  </div>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-center py-5">
            <img src="https://img.icons8.com/clouds/100/000000/shipped.png" alt="No Data" style="opacity:0.5">
            <p class="text-muted mt-2">Belum ada riwayat perjalanan.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAction" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold">Konfirmasi Approval</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body p-4">
        <form id="form-action">
          <input type="hidden" name="id_tagihan" id="act_id_tagihan">
          <input type="hidden" name="current_status" id="act_current_status">
          <input type="hidden" name="aksi" id="act_tipe">

          <div class="text-center mb-4">
            <h4 id="modal-title-confirm" class="font-weight-bold text-dark"></h4>
            <p id="modal-text-confirm" class="text-muted"></p>
          </div>

          <div id="div-catatan" class="form-group" style="display:none;">
            <label class="font-weight-bold small text-uppercase">Alasan Revisi/Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control bg-light border-0" name="catatan" rows="3" placeholder="Tuliskan catatan anda disini..."></textarea>
          </div>

          <div id="div-doc-lain" class="form-group" style="display:none;">
            <label class="font-weight-bold small text-uppercase" id="lbl-doc-lain">Upload Dokumen Pendukung</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text border-0 bg-light"><i class="fas fa-link"></i></span></div>
              <input type="text" class="form-control border-0 bg-light" id="input-doc-lain" name="link_dokumen_lain" placeholder="Paste Link GDrive...">
            </div>
            <small class="text-muted mt-1 d-block">Dokumen pendukung lainnya (opsional).</small>
          </div>
        </form>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" id="btn-save-action">Ya, Proses</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {
    let seniorTaxStatus = "<?= $code_senior_tax ?>";

    $('.btn-action').click(function() {
      let id = $(this).data('id');
      let status = $(this).data('status');
      let aksi = $(this).data('aksi');

      $('#act_id_tagihan').val(id);
      $('#act_current_status').val(status);
      $('#act_tipe').val(aksi);

      $('#div-catatan').hide();
      $('#div-doc-lain').hide();
      $('#form-action')[0].reset();
      $('textarea[name="catatan"]').prop('required', false);
      $('#input-doc-lain').prop('required', false);

      let btnColor = 'btn-primary';

      if (aksi === 'revisi') {
        $('#modal-title-confirm').text('Kembalikan Revisi?');
        $('#modal-text-confirm').text('Data dikembalikan ke Pengaju untuk diperbaiki.');
        $('#div-catatan').slideDown();
        $('textarea[name="catatan"]').prop('required', true);
        btnColor = 'btn-warning';
      } else if (aksi === 'tolak') {
        $('#modal-title-confirm').text('Tolak Permanen?');
        $('#modal-text-confirm').text('Pengajuan ditolak dan tidak dapat diproses lagi.');
        $('#div-catatan').slideDown();
        $('textarea[name="catatan"]').prop('required', true);
        btnColor = 'btn-danger';
      } else {
        $('#modal-title-confirm').text('Setujui Pengajuan?');
        $('#modal-text-confirm').text('Anda menyetujui data ini valid.');
        btnColor = 'btn-success';
      }

      $('#btn-save-action').removeClass('btn-primary btn-warning btn-danger btn-success').addClass(btnColor);
      $('#modalAction').modal('show');
    });

    $('#btn-save-action').click(function() {
      let btn = $(this);
      let originalText = btn.html();
      let aksi = $('#act_tipe').val();
      let catatan = $('textarea[name="catatan"]').val();
      let docLain = $('#input-doc-lain').val();
      let isDocRequired = $('#input-doc-lain').prop('required');

      if ((aksi == 'revisi' || aksi == 'tolak') && catatan.trim() == '') {
        Swal.fire('Peringatan', 'Mohon isi alasan revisi/penolakan!', 'warning');
        return;
      }
      if (aksi == 'setujui' && isDocRequired && docLain.trim() == '') {
        Swal.fire('Peringatan', 'Link Faktur Pajak wajib diisi!', 'warning');
        return;
      }

      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

      $.ajax({
        url: '<?= base_url("tagihan/process_approval") ?>',
        type: 'POST',
        data: $('#form-action').serialize(),
        dataType: 'json',
        success: function(res) {
          if (res.status == 'success') {
            $('#modalAction').modal('hide');
            Swal.fire({
              title: 'Berhasil',
              text: res.message,
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Gagal', res.message, 'error');
            btn.prop('disabled', false).html(originalText);
          }
        },
        error: function(xhr) {
          Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
          btn.prop('disabled', false).html(originalText);
        }
      });
    });
  });
</script>