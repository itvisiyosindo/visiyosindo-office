<?php
// =========================================================
// LOGIC: MENENTUKAN HAK AKSES TOMBOL DI VIEW
// =========================================================
$status_now    = $tagihan->status_approval;
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
      <li><span><?= $page_desc ?></span></li>
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

  /* Link Kode Dokumen Hover Effect */
  .text-value a[href*="kirim/detail"]:hover {
    color: #3498db !important;
  }

  .text-value a[href*="kirim/detail"]:hover svg {
    stroke: #3498db;
    transform: translateX(-2px);
    transition: all 0.3s;
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

  /* Checkmark & Crossmark Animation */
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
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #7ac142;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
  }

  .checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #7ac142;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  .checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

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
  }

  .wrapper.rejected {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .crossmark {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px var(--red-color);
    animation: fill_cross .4s ease-in-out .4s forwards, scale_cross .3s ease-in-out .9s both;
  }

  .crossmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: var(--red-color);
    fill: none;
    animation: stroke_cross 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  .crossmark__line {
    fill: none;
    stroke-dasharray: 40;
    stroke-dashoffset: 40;
  }

  .crossmark__line--first {
    transform-origin: 50% 50%;
    animation: stroke_cross 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

  .crossmark__line--second {
    transform-origin: 50% 50%;
    animation: stroke_cross 0.3s cubic-bezier(0.65, 0, 0.45, 1) 1.0s forwards;
  }

  @keyframes stroke_cross {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes fill_cross {
    100% {
      box-shadow: inset 0px 0px 0px 30px var(--red-color);
    }
  }

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
<div class="mb-4">
  <a href="<?= base_url('tagihan') ?>" class="btn btn-secondary shadow-sm">
    <i class="fas fa-arrow-left mr-1"></i> Kembali ke List
  </a>
</div>

<div class="row">
  <div class="col-lg-8 col-md-12">

    <div class="card card-modern">
      <div class="card-body pt-4 pb-2">
        <h6 class="text-center text-label mb-2">STATUS APPROVAL</h6>
        <div class="approval-track px-3">
          <?php foreach ($approval_configs as $cfg):
            if ($cfg->is_active == 0) continue;
            $activeClass = ($status_now >= $cfg->status_code && $status_now != 99) ? 'completed' : ($status_now == $cfg->status_code ? 'active' : '');
            if ($status_now == 5 && $cfg->status_code == 4) $activeClass = 'completed';
          ?>
            <div class="approval-step <?= $activeClass ?>">
              <div class="approval-icon">
                <?php if ($activeClass == 'completed'): ?><i class="fas fa-check"></i>
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
          <a href="<?= base_url('tagihan/print_tagihan_kirim/' . encrypt($tagihan->id_tagihan)) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-print mr-1"></i> Cetak Tagihan
          </a>
        </div>
      </div>
    </div>

    <!-- Card: Data Penagihan -->
    <div class="card card-modern border-left-primary" style="border-left: 4px solid #1565c0;">
      <div class="card-header-modern">
        <h6><i class="fas fa-file-invoice-dollar text-success mr-2"></i>Data Penagihan</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-3">
              <span class="text-label">No. Invoice</span>
              <div class="text-value" style="font-size: 1.1rem;"><i class="fas fa-file-invoice text-info mr-2"></i><?= $tagihan->no_invoice ?? '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Nilai Tagihan (Ongkir)</span>
              <div class="text-value-lg text-dark">Rp <?= number_format($tagihan->nilai_tagihan ?? 0, 0, ',', '.') ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Biaya Asuransi</span>
              <?php if (!empty($tagihan->biaya_asuransi) && $tagihan->biaya_asuransi > 0): ?>
                <div class="text-value-lg text-info">Rp <?= number_format($tagihan->biaya_asuransi, 0, ',', '.') ?></div>
              <?php else: ?>
                <div class="text-muted" style="font-size: 1rem;">Rp 0</div>
                <small class="text-muted font-italic"><i class="fas fa-info-circle mr-1"></i>Tagihan ini tidak memiliki biaya asuransi</small>
              <?php endif; ?>
            </div>
            <div class="form-group mb-3 p-3 rounded" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
              <span class="text-label text-success"><i class="fas fa-calculator mr-1"></i>Total Tagihan</span>
              <div class="text-value-lg text-success" style="font-size: 1.5rem;">Rp <?= number_format($tagihan->total_tagihan ?? 0, 0, ',', '.') ?></div>
              <small class="text-success"><i class="fas fa-info-circle mr-1"></i>Nominal ini yang akan dibayarkan.</small>
            </div>
            <div class="form-group mb-0">
              <span class="text-label">Tanggal Invoice</span>
              <div class="text-value-lg text-danger"><i class="fas fa-calendar-check mr-2 "></i> <?= !empty($tagihan->tanggal_invoice) ? date('d F Y', strtotime($tagihan->tanggal_invoice)) : '-' ?></div>
            </div>
          </div>
          <div class="col-md-6 bg-light p-3 rounded">
            <span class="text-label mb-3">Lampiran Penagihan</span>
            <ul class="list-unstyled mb-0">
              <li class="mb-2 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-invoice text-primary mr-2"></i> Invoice Ekspedisi</span>
                <a href="<?= $tagihan->link_invoice ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
              </li>
              <li class="mb-2 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-alt text-dark mr-2"></i> Faktur Pajak</span>
                <a href="<?= $tagihan->link_faktur_pajak ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3 text-white"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
              </li>
              <?php if (!empty($tagihan->link_dokumen_lain)): ?>
                <li class="mb-2 d-flex justify-content-between align-items-center">
                  <span><i class="fas fa-folder-open text-primary mr-2"></i> Dok. Pendukung</span>
                  <a href="<?= $tagihan->link_dokumen_lain ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
                </li>
              <?php endif; ?>

              <?php if (!empty($tagihan->link_bukti_potong)): ?>
                <li class="d-flex justify-content-between align-items-center">
                  <span><i class="fas fa-receipt text-dark mr-2"></i> Bukti Potong (By: <span class="text-primary">Senior Tax</span>)</span>
                  <a href="<?= $tagihan->link_bukti_potong ?>" target="_blank" class="btn btn-xs btn-secondary rounded-pill px-3"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Card: Data Kirim Dokumen -->
    <div class="card card-modern">
      <div class="card-header-modern">
        <h6><i class="fas fa-file-alt text-warning mr-2"></i>Data Kirim Dokumen</h6>
        <span class="badge badge-dark px-3 py-1">Kirim Dokumen</span>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-3">
              <span class="text-label">Kode Kirim Dokumen</span>
              <div class="text-value" style="font-size: 1.1rem;">
                <a href="<?= base_url('kirim/show/detail/' . encrypt($tagihan->id_kirim)) ?>" target="_blank" class="text-decoration-none" style="color: #2c3e50; transition: all 0.3s;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2" style="vertical-align: text-bottom;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                    <line x1="9" y1="11" x2="15" y2="11"></line>
                    <line x1="9" y1="19" x2="13" y2="19"></line>
                  </svg>
                  <strong><?= $tagihan->kode ?? '-' ?></strong>
                  <i class="fas fa-external-link-alt ml-2 text-muted" style="font-size: 0.75rem;"></i>
                </a>
              </div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Marketing</span>
              <div class="text-value"><?= $tagihan->marketing ?? '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Nama Customer</span>
              <div class="text-value"><?= $tagihan->nama_customer ?? '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">PIC Penerima</span>
              <div class="text-value"><?= $tagihan->pic ?? '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Alamat Penerima</span>
              <div class="text-value"><?= nl2br(htmlspecialchars($tagihan->alamat ?? '-')) ?></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group mb-3">
              <span class="text-label">Ekspedisi</span>
              <div class="text-value"><i class="fas fa-shipping-fast mr-2 text-primary"></i><?= $tagihan->nama_ekspedisi ?? '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">No Resi</span>
              <div class="text-value"><?= $tagihan->no_resi ?? '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Link Resi</span>
              <div class="text-value">
                <?php if (!empty($tagihan->link_resi)): ?>
                  <a href="<?= $tagihan->link_resi ?>" target="_blank" class="btn btn-sm btn-info">
                    <i class="fas fa-external-link-alt mr-1"></i> Cek Resi
                  </a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Tanggal Pengiriman</span>
              <div class="text-value"><?= !empty($tagihan->tgl_kirim) ? date('d-M-Y', strtotime($tagihan->tgl_kirim)) : '-' ?></div>
            </div>
            <div class="form-group mb-3">
              <span class="text-label">Tanggal Barang Sampai</span>
              <div class="text-value"><?= !empty($tagihan->tgl_sampai) ? date('d-M-Y', strtotime($tagihan->tgl_sampai)) : '-' ?></div>
            </div>
            <?php if (!empty($tagihan->link_doc)): ?>
              <div class="form-group mb-0">
                <span class="text-label">Link Dokumen</span>
                <div class="text-value">
                  <a href="<?= $tagihan->link_doc ?>" target="_blank" class="btn btn-sm btn-success">
                    <i class="fas fa-file mr-1"></i> Lihat Dokumen
                  </a>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <?php if (!empty($tagihan->keterangan)): ?>
          <div class="row mt-3">
            <div class="col-12">
              <div class="form-group mb-0">
                <span class="text-label">Keterangan</span>
                <div class="text-value"><?= nl2br(htmlspecialchars($tagihan->keterangan)) ?></div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Card: Data Ekspedisi -->
    <div class="card card-modern">
      <div class="card-header-modern">
        <h6><i class="fas fa-truck text-primary mr-2"></i>Data Ekspedisi</h6>
        <?php if (!empty($tagihan->kerjasama) && $tagihan->kerjasama == 'Kontrak'): ?>
          <span class="badge badge-success px-3 py-1">CONTRACT</span>
        <?php else: ?>
          <span class="badge badge-secondary px-3 py-1">NON-CONTRACT</span>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 mb-3">
            <h5 class="mb-1 text-dark font-weight-bold"><?= $tagihan->nama_ekspedisi ?? '-' ?></h5>
            <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> <?= $tagihan->alamat_ekspedisi ?? '-' ?></small>
          </div>
        </div>
        <hr class="my-2">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group mb-3">
              <span class="text-label">PIC Vendor</span>
              <div class="text-value"><?= $tagihan->pic_ekspedisi ?? '-' ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mb-3">
              <span class="text-label">Kontak</span>
              <div class="text-value"><?= $tagihan->kontak_ekspedisi ?? '-' ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mb-3">
              <span class="text-label">Payment Term</span>
              <div class="text-value text-danger font-weight-bold"><?= $tagihan->payment_term ?? '-' ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mb-3">
              <span class="text-label">PPh 23</span>
              <div class="text-value"><?= $tagihan->pph23 ?? '-' ?></div>
            </div>
          </div>
        </div>
        <hr class="my-2">
        <div class="row">
          <div class="col-md-12">
            <span class="text-label mb-2 d-block">Legalitas</span>
            <div class="d-flex gap-2">
              <?php if (!empty($tagihan->link_mou)): ?>
                <a href="<?= $tagihan->link_mou ?>" target="_blank" class="btn btn-sm btn-outline-primary mr-2 flex-fill">
                  <i class="fas fa-file-contract mr-1"></i> Link MOU
                </a>
              <?php endif; ?>
              <?php if (!empty($tagihan->link_legalitas)): ?>
                <a href="<?= $tagihan->link_legalitas ?>" target="_blank" class="btn btn-sm btn-outline-info flex-fill">
                  <i class="fas fa-balance-scale mr-1"></i> Link Perjanjian Kerja Sama
                </a>
              <?php endif; ?>
              <?php if (empty($tagihan->link_mou) && empty($tagihan->link_legalitas)): ?>
                <span class="text-muted small font-italic">Tidak ada dokumen.</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sidebar -->
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

            <button class="btn btn-success btn-block btn-lg shadow-sm mb-3 btn-action" data-id="<?= $tagihan->id_tagihan ?>" data-status="<?= $tagihan->status_approval ?>" data-aksi="setujui">
              <i class="fas fa-check-circle mr-2"></i> SETUJUI PENGAJUAN
            </button>

            <div class="row">
              <div class="col-6 pr-1"><button class="btn btn-warning btn-block shadow-sm btn-action" data-id="<?= $tagihan->id_tagihan ?>" data-status="<?= $tagihan->status_approval ?>" data-aksi="revisi"><i class="fas fa-undo"></i> Revisi</button></div>
              <div class="col-6 pl-1"><button class="btn btn-danger btn-block shadow-sm btn-action" data-id="<?= $tagihan->id_tagihan ?>" data-status="<?= $tagihan->status_approval ?>" data-aksi="tolak"><i class="fas fa-times"></i> Tolak</button></div>
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
              <p class="text-muted">Tagihan Kirim Dokumen Sudah Disetujui.</p>
            <?php elseif ($status_now == 99): ?>
              <div class="wrapper rejected">
                <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                  <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none" />
                  <path class="crossmark__line crossmark__line--first" fill="none" d="M16 16l20 20" />
                  <path class="crossmark__line crossmark__line--second" fill="none" d="M36 16L16 36" />
                </svg>
              </div>
              <h4 class="text-danger font-weight-bold">DITOLAK</h4>
              <p class="text-muted">Pengajuan Tagihan Kirim Dokumen <span class="font-weight-bold">&rdquo;DITOLAK&ldquo;</span>.</p>
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
  <?php foreach ($tracking_history as $history): ?>
    <?php
    $status_name = 'Pengajuan';
    switch ($history->id_status) {
      case 0: $status_name = 'Pengajuan'; break;
      case 1: $status_name = 'Proses Kirim'; break;
      case 2: $status_name = 'Manifest Berangkat'; break;
      case 3: $status_name = 'Proses Sortir'; break;
      case 4: $status_name = 'Pengantaran Kurir'; break;
      case 5: $status_name = 'Diterima'; break;
      case 6: $status_name = 'Menunggu Konfirmasi'; break;
    }
    $is_finish = ($history->id_status == 5);
    ?>
    <li>
      <span class="timeline-dot" style="<?= $is_finish ? 'background:#2ecc71; border-color:#2ecc71' : '' ?>"></span>
      
      <div class="d-flex justify-content-between align-items-start mb-1">
        <div class="d-flex flex-column">
          <span class="font-weight-bold <?= $is_finish ? 'text-success' : 'text-dark' ?>" style="font-size:0.9rem">
            <?= $status_name ?>
          </span>

          <?php if ($history->id_status == 1 && !empty($tagihan->tgl_kirim)): ?>
            <small class="text-primary font-weight-bold" style="font-size:0.7rem;">
              <i class="fas fa-truck mr-1"></i> Pengiriman: <?= date('d M Y', strtotime($tagihan->tgl_kirim)) ?>
            </small>
          <?php endif; ?>

          <?php if ($history->id_status == 5 && !empty($history->tgl_penerima)): ?>
            <small class="text-success font-weight-bold" style="font-size:0.7rem;">
              <i class="fas fa-calendar-check mr-1"></i> Sampai: <?= date('d M Y', strtotime($history->tgl_penerima)) ?>
            </small>
          <?php endif; ?>
        </div>
        
        <small class="text-muted text-right font-weight-bold d-flex flex-column" style="font-size:0.75rem; line-height: 1.2;">
          <span class="small text-muted mb-0">Update pada</span>
          <span class="text-dark"><?= date('d M Y', strtotime($history->created_at)) ?></span>
          <span class="text-muted"><?= date('H:i', strtotime($history->created_at)) ?> WIB</span>
        </small>
      </div>

      <?php if (!empty($history->keterangan_konfirmasi)): ?>
        <small class="text-muted d-block mt-1 bg-light p-1 rounded" style="font-size:0.8rem; border-left: 2px solid #ddd;">
          <?= htmlspecialchars($history->keterangan_konfirmasi) ?>
        </small>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ul>
          <?php if (!empty($tagihan->catatan_revisi)): ?>
            <div class="alert alert-danger mt-3 small p-2"><strong>Catatan Terakhir:</strong><br>"<?= $tagihan->catatan_revisi ?>"</div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Card: History Tracking Pengiriman -->
      <?php if (!empty($tracking_history)): ?>
        <div class="card card-modern">
          <div class="card-header-modern">
            <h6><i class="fas fa-history text-secondary mr-2"></i>Riwayat Perjalanan</h6>
            <span class="badge badge-light text-primary border"><?= count($tracking_history) ?> Update</span>
          </div>
          <div class="card-body" style="max-height: 400px; overflow-y: auto; scrollbar-width: thin;">
            <ul class="vertical-timeline mt-2">
              <?php foreach ($tracking_history as $history): ?>
                <?php
                $status_name = 'Pengajuan';
                switch ($history->id_status) {
                  case 0:
                    $status_name = 'Pengajuan';
                    break;
                  case 1:
                    $status_name = 'Proses Kirim';
                    break;
                  case 2:
                    $status_name = 'Manifest Berangkat';
                    break;
                  case 3:
                    $status_name = 'Proses Sortir';
                    break;
                  case 4:
                    $status_name = 'Pengantaran Kurir';
                    break;
                  case 5:
                    $status_name = 'Diterima';
                    break;
                  case 6:
                    $status_name = 'Menunggu Konfirmasi';
                    break;
                }
                $is_finish = ($history->id_status == 5);
                ?>
                <li>
                  <span class="timeline-dot" style="<?= $is_finish ? 'background:#2ecc71; border-color:#2ecc71' : '' ?>"></span>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="font-weight-bold <?= $is_finish ? 'text-success' : 'text-dark' ?>" style="font-size:0.9rem"><?= $status_name ?></span>
                    
                    <small class="text-muted text-right font-weight-bold d-flex flex-column" style="font-size:0.75rem;">
                      <span class="small text-muted mb-0">Data Diupdate pada</span>
                      <span class="text-dark"><?= date('d M Y', strtotime($history->created_at)) ?></span>
                      <span class="text-muted"><?= date('H:i', strtotime($history->created_at)) ?> WIB</span>
                    </small>
                  </div>
                  <?php if (!empty($history->keterangan)): ?>
                    <small class="text-muted d-block mt-1" style="font-size:0.8rem;"><?= htmlspecialchars($history->keterangan) ?></small>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Action -->
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
      $('#form-action')[0].reset();
      $('textarea[name="catatan"]').prop('required', false);

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

      if ((aksi == 'revisi' || aksi == 'tolak') && catatan.trim() == '') {
        Swal.fire('Peringatan', 'Mohon isi alasan revisi/penolakan!', 'warning');
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