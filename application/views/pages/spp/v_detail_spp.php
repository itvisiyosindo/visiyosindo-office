<?php
// LOGIC: MENENTUKAN HAK AKSES TOMBOL
$status_now = $spp->status_approval;
$my_jabatan = trim($current_user_jabatan ?? '');
$required_role = '';
$step_name = 'Menunggu Giliran';

// Pastikan $is_admin tersedia - cek dari session langsung
if (!isset($is_admin)) {
    $is_admin = false;
}
// Double check admin dari session
$session_nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
if ($session_nama == 'Administrator') {
    $is_admin = true;
}

// Cek apakah user adalah pengaju
$is_pengaju = sessPenggunaId() == $spp->created_by;

// Cek apakah bisa revisi (status 0 atau 99 dan user adalah pengaju/admin)
$can_revise = ($status_now == 0 || $status_now == 99) && ($is_pengaju || $is_admin);

// Jika status 0 (revisi), tampilkan info bahwa perlu diajukan ulang atau admin approve
if ($status_now == 0) {
    $step_name = 'Revisi / Menunggu Pengajuan Ulang';
    $required_role = ''; // Tidak ada role yang match, kecuali admin
}

foreach ($approval_configs as $cfg) {
    if ($cfg->status_code == $status_now) {
        $required_role = $cfg->role_label;
        $step_name = $cfg->role_label;
    }
}

$can_approve = false;
if ($required_role != '' && strtolower($my_jabatan) == strtolower($required_role)) {
    $can_approve = true;
}
// Admin selalu bisa approve di semua tahap (kecuali selesai/ditolak)
if ($is_admin === true) {
    $can_approve = true;
}
// Kecuali sudah selesai/ditolak
if ($status_now == 5 || $status_now == 99) {
    $can_approve = false;
}
?>

<header class="page-header">
    <h2><i class="fas fa-file-invoice-dollar"></i> <?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('spp') ?>"><span>SPP</span></a></li>
            <li><span><?= $spp->no_spp ?></span></li>
        </ol>
    </div>
</header>

<style>
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
    }

    .text-value-lg {
        font-size: 1.3rem;
        color: #1565c0;
        font-weight: 800;
    }

    .card-modern {
        border: 0;
        border-radius: var(--card-radius);
        box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15);
        background: #fff;
        margin-bottom: 24px;
    }

    .card-header-modern {
        background: #fff;
        border-bottom: 1px solid #f6f9fc;
        padding: 1.25rem 1.5rem;
    }

    .sticky-sidebar {
        position: sticky;
        top: 20px;
    }

    .info-box {
        background-color: #f6f9fc;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #e9ecef;
    }

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

    .table-tagihan th {
        background-color: #f8f9fa;
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .table-tagihan td {
        vertical-align: middle;
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

    /* Styling Ikon Crossmark */
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

<div class="row">
    <div class="col-lg-8 col-md-12">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h6 class="mb-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle mr-2"></i>Informasi SPP
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <span class="text-label">No. SPP</span>
                        <span class="text-value-lg d-block"><?= $spp->no_spp ?></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="text-label">No. Invoice</span>
                        <span class="text-value d-block" style="font-size: 1.1rem;"><?= $spp->no_invoice ?></span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-label">Jumlah Tagihan</span>
                        <span class="text-value d-block"><?= $spp->jumlah_tagihan ?> item</span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-label">Total Nilai Tagihan</span>
                        <span class="text-value d-block text-success font-weight-bold" style="font-size: 1.2rem;">
                            Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?>
                        </span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-label">Tanggal Pengajuan</span>
                        <span class="text-value d-block"><?= date('d M Y H:i', strtotime($spp->created_at)) ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="p-3 rounded" style="background-color: #e3f2fd; border: 1px solid #90caf9;">
                            <h6 class="font-weight-bold text-primary mb-2" style="font-size: 0.85rem;">
                                <i class="fas fa-money-check mr-1"></i> Tujuan Transfer (Vendor/Ekspedisi)
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="text-label">Nama Bank</span>
                                    <span class="text-value d-block font-weight-bold">
                                        <?= !empty($spp->nama_bank) ? strtoupper($spp->nama_bank) : '-' ?>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-label">Nomor Rekening</span>
                                    <span class="text-value d-block font-weight-bold"
                                        style="font-size: 1.1rem; letter-spacing: 1px;">
                                        <?= !empty($spp->no_rekening) ? $spp->no_rekening : '-' ?>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-label">Atas Nama</span>
                                    <span class="text-value d-block font-weight-bold">
                                        <?= !empty($spp->atas_nama) ? strtoupper($spp->atas_nama) : '-' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="info-box bg-light p-3 rounded">
                            <div class="row">

                                <div class="col-md-2 mb-2">
                                    <span class="text-label">Total Tagihan</span>
                                    <span class="text-value d-block font-weight-bold">
                                        Rp <?= number_format($spp->total_nilai, 0, ',', '.') ?>
                                    </span>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <span class="text-label">PPN</span>
                                    <span class="text-value d-block text-dark font-weight-bold">
                                        + Rp <?= number_format($spp->ppn ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>

                                <?php if (!empty($spp->biaya_lainnya) && $spp->biaya_lainnya > 0): ?>
                                    <div class="col-md-2 mb-2">
                                        <span class="text-label">Biaya Lainnya</span>
                                        <span class="text-value d-block text-info font-weight-bold">
                                            + Rp <?= number_format($spp->biaya_lainnya, 0, ',', '.') ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-2 mb-2">
                                    <span class="text-label">Diskon</span>
                                    <span class="text-value d-block text-warning font-weight-bold">
                                        - Rp <?= number_format($spp->diskon ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <span class="text-label">Grand Total</span>
                                    <span class="text-value d-block text-success font-weight-bold"
                                        style="font-size:1.1rem">
                                        Rp
                                        <?= number_format($spp->grand_total ?? $spp->total_nilai + ($spp->ppn ?? 0) - ($spp->diskon ?? 0), 0, ',', '.') ?>
                                    </span>
                                    <small class="text-muted">(Total + PPN - Diskon)</small>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <span class="text-label">Bukti Potong (PPH)</span>
                                    <span class="text-value d-block text-danger font-weight-bold">
                                        - Rp <?= number_format($spp->nilai_bukti_potong ?? 0, 0, ',', '.') ?>
                                    </span>
                                </div>

                                <div class="col-12 mt-2 pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-label" style="font-size:1rem">TOTAL YANG HARUS DIBAYAR</span>
                                        <span class="text-value-lg text-primary font-weight-bold"
                                            style="font-size:1.5rem">
                                            Rp <?= number_format($spp->nilai_pembayaran ?? 0, 0, ',', '.') ?>
                                        </span>
                                    </div>
                                </div>

                            </div>

                            <?php if (!empty($spp->link_bukti_potong)): ?>
                                <div class="mt-3 pt-2 border-top border-white">
                                    <span class="text-label">Link Bukti Potong</span>
                                    <a href="<?= $spp->link_bukti_potong ?>" target="_blank"
                                        class="d-inline-block text-primary font-weight-bold">
                                        <i class="fas fa-external-link-alt mr-1"></i> Lihat File Bukti Potong
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-start align-items-center" style="gap: 3rem;">
                    <?php if (!empty($spp->link_dokumen_spp)): ?>
                        <div class="my-2">
                            <a href="<?= $spp->link_dokumen_spp ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-pdf mr-1"></i> Lihat Dokumen SPP
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="my-2">
                        <a href="<?= base_url('spp/print_spp/' . encrypt($spp->id_spp)) ?>" target="_blank"
                            class="btn btn-outline-success btn-sm">
                            <i class="fas fa-print mr-1"></i> Cetak SPP
                        </a>
                    </div>

                    <div class="my-2">
                        <?php if ($is_admin): ?>
                            <a href="<?= base_url('spp/edit_admin/' . encrypt($spp->id_spp)) ?>"
                                class="btn btn-warning btn-sm ml-2">
                                <i class="fas fa-edit mr-1"></i> Edit Full (Admin)
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-modern">
            <div class="card-header-modern">
                <h6 class="mb-0 font-weight-bold text-primary">
                    <i class="fas fa-tasks mr-2"></i>Status Approval
                </h6>
            </div>
            <div class="card-body">
                <div class="approval-track">
                    <?php
                    $current_order = 0;
                    foreach ($approval_configs as $cfg) {
                        if ($cfg->status_code == $spp->status_approval) {
                            $current_order = $cfg->level_order;
                            break;
                        }
                    }
                    if ($spp->status_approval == 5) $current_order = 999;

                    foreach ($approval_configs as $cfg):
                        $stepClass = '';
                        if ($cfg->level_order < $current_order) {
                            $stepClass = 'completed';
                        } elseif ($cfg->status_code == $spp->status_approval) {
                            $stepClass = 'active';
                        }
                    ?>
                        <div class="approval-step <?= $stepClass ?>">
                            <div class="approval-icon">
                                <?php if ($stepClass == 'completed'): ?>
                                    <i class="fas fa-check"></i>
                                <?php else: ?>
                                    <?= $cfg->level_order ?>
                                <?php endif; ?>
                            </div>
                            <span class="approval-label"><?= $cfg->role_label ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="approval-step <?= $spp->status_approval == 5 ? 'completed' : '' ?>">
                        <div class="approval-icon">
                            <?php if ($spp->status_approval == 5): ?>
                                <i class="fas fa-check"></i>
                            <?php else: ?>
                                <i class="fas fa-flag-checkered"></i>
                            <?php endif; ?>
                        </div>
                        <span class="approval-label">Selesai</span>
                    </div>
                </div>

                <?php if ($spp->status_approval == 0 && !empty($spp->catatan_revisi)): ?>
                    <div class="alert alert-danger mt-4">
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Catatan Revisi:</strong><br>
                        <?= nl2br($spp->catatan_revisi) ?>
                    </div>
                <?php endif; ?>

                <?php if ($spp->status_approval == 99): ?>
                    <div class="alert alert-dark mt-4">
                        <strong><i class="fas fa-times-circle mr-1"></i> SPP Ditolak</strong><br>
                        <?= nl2br($spp->catatan_revisi) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ======================== SECTION BUKTI PEMBAYARAN ======================== -->
        <?php if ($spp->status_approval == 5): ?>
            <div class="card card-modern border-success">
                <div class="card-header-modern bg-light-success">
                    <h6 class="mb-0 font-weight-bold text-success">
                        <i class="fas fa-receipt mr-2"></i>Status Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($spp->status_bayar == 'sudah_dibayar'): ?>
                        <!-- SPP SUDAH DIBAYAR -->
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-3x mr-3"></i>
                                <div>
                                    <h5 class="mb-1"><strong>✅ SUDAH DIBAYAR</strong></h5>
                                    <p class="mb-0">Pembayaran untuk SPP ini telah selesai dilakukan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <span class="text-label">Tanggal Pembayaran</span>
                                <span
                                    class="text-value d-block"><?= !empty($spp->tgl_bayar) ? date('d M Y', strtotime($spp->tgl_bayar)) : '-' ?></span>
                            </div>
                            <div class="col-md-9 mb-3">
                                <span class="text-label">Bukti Pembayaran</span>
                                <?php if (!empty($spp->link_bukti_bayar)): ?>
                                    <a href="<?= $spp->link_bukti_bayar ?>" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt mr-1"></i> Lihat Bukti Pembayaran
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($spp->keterangan_bayar)): ?>
                                <div class="col-12 mb-3">
                                    <span class="text-label">Keterangan</span>
                                    <div class="text-value border p-2 rounded bg-light">
                                        <?= nl2br(htmlspecialchars($spp->keterangan_bayar)) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!-- SPP BELUM DIBAYAR -->
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-3x mr-3"></i>
                                <div>
                                    <h5 class="mb-1"><strong>⏳ BELUM DIBAYAR</strong></h5>
                                    <p class="mb-0">SPP telah disetujui, menunggu proses pembayaran.</p>
                                </div>
                            </div>
                        </div>

                        <?php if (in_array(sessPenggunaId(), [1, 106, 107])): ?>
                            <!-- FORM UPLOAD BUKTI PEMBAYARAN (Hanya untuk user ID 1 atau 106) -->
                            <div class="mt-3 border-top pt-3">
                                <h6 class="font-weight-bold text-primary mb-3">
                                    <i class="fas fa-upload mr-2"></i>Upload Bukti Pembayaran
                                </h6>
                                <form action="<?= base_url('spp/upload_bukti_bayar') ?>" method="POST"
                                    id="formUploadBuktiBayar">
                                    <input type="hidden" name="id_spp" value="<?= encrypt($spp->id_spp) ?>">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold">Link Bukti Pembayaran <span
                                                    class="text-danger">*</span></label>
                                            <input type="url" name="link_bukti_bayar" class="form-control"
                                                placeholder="https://drive.google.com/..." required>
                                            <small class="form-text text-muted">Link Google Drive atau URL lainnya</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold">Tanggal Pembayaran</label>
                                            <input type="date" name="tgl_bayar" class="form-control"
                                                value="<?= date('Y-m-d') ?>">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label font-weight-bold">Keterangan</label>
                                            <textarea name="keterangan_bayar" class="form-control" rows="3"
                                                placeholder="Keterangan pembayaran (opsional)"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check mr-2"></i>Submit Bukti Pembayaran
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0"><small>
                                    <i class="fas fa-info-circle"></i>
                                    Upload bukti pembayaran hanya dapat dilakukan oleh user tertentu.
                                </small></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <!-- ======================== END SECTION BUKTI PEMBAYARAN ======================== -->

        <div class="card card-modern">
            <div class="card-header-modern">
                <h6 class="mb-0 font-weight-bold text-primary">
                    <i class="fas fa-list mr-2"></i>Daftar Tagihan dalam SPP (<?= count($tagihan_list) ?> item)
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $no = 1;
                    $total = 0;
                    foreach ($tagihan_list as $t):
                        $tagihan_amount = !empty($t->total_tagihan) ? $t->total_tagihan : ($t->nilai_tagihan + ($t->biaya_asuransi ?? 0));
                        $total += $tagihan_amount;

                        // LOGIC URL: CEK APAKAH KIRIM DOKUMEN ATAU TRACKING BARANG
                        $url_detail = base_url('tagihan/detail/' . encrypt($t->id_tagihan)); // Default Tracking
                        if (!empty($t->id_kirim)) {
                            $url_detail = base_url('tagihan/detail_kirim/' . encrypt($t->id_tagihan));
                        }
                    ?>
                        <div class="col-12 mb-3">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-primary">
                                        <span class="badge badge-secondary mr-2"><?= $no++ ?></span>

                                        <a href="<?= $url_detail ?>" class="text-primary"
                                            title="Lihat Detail Tagihan Ekspedisi">
                                            <?php if (empty($t->id_kirim)): ?>
                                                <span>Nomor Surat : <?= $t->no_sj ?? $t->kode_stb ?><i
                                                        class="fas fa-external-link-alt ml-2"></i></span>
                                            <?php else: ?>
                                                Nomor Dokumen : <?= $t->kode_kirim ?>
                                            <?php endif; ?>
                                        </a>
                                    </span>

                                    <div>
                                        <span class="font-weight-bold text-success mr-3" style="font-size: 1.1rem;">
                                            Rp <?= get_display_total_tagihan($t) ?>
                                        </span>
                                        <a href="<?= $url_detail ?>" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i> Detail
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body py-2">
                                    <!-- ========== ALERT JATUH TEMPO & STATUS PEMBAYARAN ========== -->
                                    <?php
                                    // Ambil informasi jatuh tempo dari query
                                    $status_jatuh_tempo = $t->status_jatuh_tempo ?? 'Tidak Ada Info Payment';
                                    $tanggal_jatuh_tempo = $t->tanggal_jatuh_tempo ?? null;
                                    $status_bayar_tagihan = $t->status_bayar ?? 'belum_dibayar';
                                    $hari_tersisa = $t->hari_tersisa ?? null;

                                    // Get alert styling
                                    $alert_class = get_alert_jatuh_tempo_class($status_jatuh_tempo);
                                    $alert_icon = get_alert_jatuh_tempo_icon($status_jatuh_tempo);
                                    $alert_message = get_alert_jatuh_tempo_message($t);
                                    ?>

                                    <?php if ($spp->status_approval == 5): ?>
                                        <!-- Jika SPP sudah approved, tampilkan status jatuh tempo -->
                                        <div class="mb-2">
                                            <?php if ($status_bayar_tagihan == 'sudah_dibayar'): ?>
                                                <!-- Tagihan Sudah Dibayar -->
                                                <div class="alert alert-success mb-2 py-2 px-3 d-flex align-items-center">
                                                    <i class="fas fa-check-circle fa-2x mr-2"></i>
                                                    <div class="flex-grow-1">
                                                        <strong>✅ SUDAH DIBAYAR</strong>
                                                        <?php if (!empty($t->tgl_bayar)): ?>
                                                            <br><small>Dibayar pada:
                                                                <?= date('d M Y', strtotime($t->tgl_bayar)) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if (!empty($t->link_bukti_bayar)): ?>
                                                        <a href="<?= $t->link_bukti_bayar ?>" target="_blank"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-receipt"></i> Bukti
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <!-- Alert Status Jatuh Tempo dengan Helper Function -->
                                                <div class="alert <?= $alert_class ?> mb-2 py-2 px-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center flex-grow-1">
                                                            <i class="<?= $alert_icon ?> fa-2x mr-2"></i>
                                                            <div>
                                                                <strong><?= $status_jatuh_tempo ?></strong>
                                                                <br><small><?= $alert_message ?></small>
                                                            </div>
                                                        </div>

                                                        <!-- Tombol Upload Bukti (Hanya untuk user tertentu & jika sudah/akan jatuh tempo) -->
                                                        <?php if (in_array(sessPenggunaId(), [1, 106, 107]) && in_array($status_jatuh_tempo, ['Sudah Jatuh Tempo', 'Jatuh Tempo Hari Ini', 'Akan Jatuh Tempo'])): ?>
                                                            <button type="button" class="btn btn-sm btn-success ml-2"
                                                                onclick="showUploadModal(<?= $t->id_tagihan ?>, '<?= encrypt($t->id_tagihan) ?>')">
                                                                <i class="fas fa-upload"></i> Upload Bukti
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Sub-status: Belum Dibayar -->
                                                <div class="alert alert-warning mb-2 py-1 px-3">
                                                    <small><strong>📌 Status:</strong> Belum Dibayar</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <!-- ========== END ALERT JATUH TEMPO ========== -->

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless mb-0"
                                                style="font-size: 0.85rem;">
                                                <tr>
                                                    <td width="40%" class="text-muted py-1">Customer</td>
                                                    <td class="font-weight-bold py-1">
                                                        <?= !empty($t->nama_customer) ? $t->nama_customer : '<span class="text-muted font-italic">-</span>' ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Ekspedisi</td>
                                                    <td class="py-1 font-weight-bold text-primary">
                                                        <?= !empty($t->nama_ekspedisi) ? $t->nama_ekspedisi : '<span class="text-muted font-italic">-</span>' ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">No. Invoice</td>
                                                    <td class="font-weight-bold py-1"><?= $t->no_invoice ?? '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Tgl Invoice</td>
                                                    <td class="py-1"><?= date('d M Y', strtotime($t->tanggal_invoice)) ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">No. Resi</td>
                                                    <td class="py-1"><?= $t->no_resi ?? '-' ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bg-light rounded p-2 h-100"
                                                style="border-left: 3px solid #ffc107;">
                                                <h6 class="text-warning mb-2"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Info Penting Ekspedisi
                                                </h6>
                                                <table class="table table-sm table-borderless mb-0"
                                                    style="font-size: 0.85rem;">
                                                    <tr>
                                                        <td width="40%" class="text-muted py-1"><i
                                                                class="fas fa-clock text-warning mr-1"></i> Term Payment
                                                        </td>
                                                        <td class="font-weight-bold py-1">
                                                            <?= !empty($t->term_payment) ? $t->term_payment : '<span class="text-muted">Belum diatur</span>' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted py-1"><i
                                                                class="fas fa-percentage text-info mr-1"></i> PPH 23</td>
                                                        <td class="font-weight-bold py-1">
                                                            <?= !empty($t->info_pph23) ? $t->info_pph23 : '<span class="text-muted">Belum diatur</span>' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted py-1"><i
                                                                class="fas fa-file-contract text-primary mr-1"></i> MOU
                                                        </td>
                                                        <td class="py-1">
                                                            <?php if (!empty($t->link_mou)): ?>
                                                                <a href="<?= $t->link_mou ?>" target="_blank"
                                                                    class="btn btn-xs btn-info py-0 px-2">
                                                                    <i class="fas fa-external-link-alt mr-1"></i> Lihat Dokumen
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-muted">Tidak ada</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted py-1"><i
                                                                class="fas fa-user-tie text-success mr-1"></i> PIC
                                                            Ekspedisi</td>
                                                        <td class="py-1">
                                                            <?= !empty($t->pic_ekspedisi) ? $t->pic_ekspedisi : '<span class="text-muted">-</span>' ?>
                                                        </td>
                                                    </tr>
                                                    <?php if (!empty($t->contact_ekspedisi) && $t->contact_ekspedisi != '-'): ?>
                                                        <tr>
                                                            <td class="text-muted py-1"><i
                                                                    class="fas fa-phone text-success mr-1"></i> Contact</td>
                                                            <td class="py-1"><?= $t->contact_ekspedisi ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="card bg-gradient-primary text-white mt-3">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-white-50">TOTAL KESELURUHAN</h6>
                                <small><?= count($tagihan_list) ?> Tagihan</small>
                            </div>
                            <h3 class="mb-0 font-weight-bold">Rp <?= number_format($total, 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="sticky-sidebar">
            <div class="card card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-user mr-2"></i>Diajukan Oleh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <?= strtoupper(substr($spp->nama_pengaju, 0, 1)) ?>
                        </div>
                        <div>
                            <span class="font-weight-bold d-block"><?= $spp->nama_pengaju ?></span>
                            <span class="text-muted text-small"><?= $spp->jabatan_pengaju ?? '-' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($can_approve): ?>
                <div class="card card-modern border-left-warning" style="border-left: 5px solid #ffc107;">
                    <div class="card-body text-center pt-4">
                        <h5 class="font-weight-bold mb-1">Panel Approval</h5>
                        <p class="text-muted small mb-3">Login sebagai: <b><?= $current_user_jabatan ?></b></p>

                        <?php if ($is_admin): ?>
                            <div class="alert alert-warning py-2 small mb-3">
                                <i class="fas fa-shield-alt mr-1"></i> <strong>Override by Admin</strong>
                                <br><small>Anda dapat menyetujui semua level approval</small>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-small mb-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                Menunggu approval dari: <strong><?= $required_role ?></strong>
                            </p>
                        <?php endif; ?>

                        <?php if ($status_now == 0): ?>
                            <div class="alert alert-info small mb-3">
                                <i class="fas fa-info-circle mr-1"></i> Status: <strong>REVISI</strong>
                                <br>Admin dapat langsung approve atau edit data terlebih dahulu.
                            </div>
                            <a href="<?= base_url('spp/edit/' . encrypt($spp->id_spp)) ?>"
                                class="btn btn-info btn-block mb-3">
                                <i class="fas fa-edit mr-2"></i> Edit Data SPP
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn btn-success btn-block btn-lg shadow-sm mb-3 btn-action"
                            data-aksi="setujui">
                            <i class="fas fa-check-circle mr-2"></i> SETUJUI PENGAJUAN
                        </button>

                        <div class="row">
                            <div class="col-6 pr-1">
                                <button type="button" class="btn btn-warning btn-block shadow-sm btn-action"
                                    data-aksi="revisi">
                                    <i class="fas fa-undo"></i> Revisi
                                </button>
                            </div>
                            <div class="col-6 pl-1">
                                <button type="button" class="btn btn-danger btn-block shadow-sm btn-action"
                                    data-aksi="tolak">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card card-modern bg-light">
                    <div class="card-body text-center py-5">
                        <?php if ($status_now == 5): ?>
                            <div class="wrapper mb-3">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                </svg>
                            </div>
                            <h4 class="text-success font-weight-bold">SELESAI</h4>
                            <p class="text-muted">SPP Sudah Disetujui.</p>
                        <?php elseif ($status_now == 99): ?>
                            <div class="wrapper rejected mb-3">
                                <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="crossmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <line class="crossmark__line crossmark__line--first" x1="16" y1="16"
                                        x2="36" y2="36" stroke-width="2" />
                                    <line class="crossmark__line crossmark__line--second" x1="36" y1="16"
                                        x2="16" y2="36" stroke-width="2" />
                                </svg>
                            </div>
                            <h4 class="text-danger font-weight-bold">DITOLAK</h4>
                            <p class="text-muted">Pengajuan SPP <span class="font-weight-bold">"DITOLAK"</span>.</p>
                            <?php if ($can_revise): ?>
                                <a href="<?= base_url('spp/edit/' . encrypt($spp->id_spp)) ?>"
                                    class="btn btn-warning btn-block mt-3">
                                    <i class="fas fa-redo mr-2"></i>Ajukan Ulang
                                </a>
                            <?php endif; ?>
                        <?php elseif ($status_now == 0): ?>
                            <div class="mb-3">
                                <i class="fas fa-edit text-info fa-3x"></i>
                            </div>
                            <h5 class="text-info font-weight-bold mb-2">REVISI</h5>
                            <p class="text-muted small">SPP membutuhkan revisi sebelum dilanjutkan.</p>
                            <?php if (!empty($spp->catatan_revisi)): ?>
                                <div class="alert alert-warning text-left mt-3 small">
                                    <strong>Catatan:</strong><br>
                                    <?= nl2br($spp->catatan_revisi) ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($can_revise): ?>
                                <a href="<?= base_url('spp/edit/' . encrypt($spp->id_spp)) ?>"
                                    class="btn btn-primary btn-block mt-3">
                                    <i class="fas fa-edit mr-2"></i>Lakukan Revisi
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="mb-3">
                                <i class="fas fa-user-clock text-warning fa-3x"></i>
                            </div>
                            <h5 class="text-muted font-weight-bold mb-2 d-flex align-items-center justify-content-center small"
                                style="gap: 4px;">
                                <div class="spinner-border spinner-border-sm text-dark" role="status">
                                    <span class="sr-only">Loading...</span>
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

            <?php if (isAdmin()): ?>
                <button type="button" onclick="openAdminEditModal('spp', '<?= encrypt($spp->id_spp) ?>')" class="btn btn-warning btn-block mb-2 font-weight-bold text-dark">
                    <i class="fas fa-user-shield mr-2"></i> Edit Data (Admin Modal)
                </button>
            <?php endif; ?>
            <a href="<?= base_url('spp') ?>" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAction" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modal-title">Konfirmasi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-action">
                    <input type="hidden" name="id_spp" value="<?= $spp->id_spp ?>">
                    <input type="hidden" name="current_status" value="<?= $spp->status_approval ?>">
                    <input type="hidden" name="aksi" id="act_aksi">

                    <p id="modal-text" class="mb-3"></p>

                    <div class="form-group" id="div-catatan" style="display: none;">
                        <label class="font-weight-bold">Catatan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Berikan alasan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save-action">
                    <i class="fas fa-check mr-1"></i> Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        // Handle click action button
        $('.btn-action').click(function() {
            let aksi = $(this).data('aksi');
            $('#act_aksi').val(aksi);

            // Reset
            $('#div-catatan').hide();
            $('textarea[name="catatan"]').val('').prop('required', false);

            if (aksi == 'setujui') {
                $('#modal-title').text('Setujui SPP');
                $('#modal-text').text(
                    'Yakin ingin menyetujui SPP ini dan melanjutkan ke tahap berikutnya?');
                $('#btn-save-action').removeClass('btn-warning btn-danger').addClass('btn-success');
            } else if (aksi == 'revisi') {
                $('#modal-title').text('Minta Revisi');
                $('#modal-text').text('Kembalikan ke pengaju untuk diperbaiki?');
                $('#div-catatan').show();
                $('textarea[name="catatan"]').prop('required', true);
                $('#btn-save-action').removeClass('btn-success btn-danger').addClass('btn-warning');
            } else if (aksi == 'tolak') {
                $('#modal-title').text('Tolak SPP');
                $('#modal-text').text('Yakin ingin menolak SPP ini secara permanen?');
                $('#div-catatan').show();
                $('textarea[name="catatan"]').prop('required', true);
                $('#btn-save-action').removeClass('btn-success btn-warning').addClass('btn-danger');
            }

            $('#modalAction').modal('show');
        });

        // Submit action
        $('#btn-save-action').click(function() {
            let btn = $(this);
            let originalText = btn.html();
            let aksi = $('#act_aksi').val();
            let catatan = $('textarea[name="catatan"]').val();

            if ((aksi == 'revisi' || aksi == 'tolak') && catatan.trim() == '') {
                Swal.fire('Peringatan', 'Wajib mengisi alasan!', 'warning');
                return;
            }

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            $.ajax({
                url: '<?= base_url('spp/process_approval') ?>',
                type: 'POST',
                data: $('#form-action').serialize(),
                dataType: 'json',
                success: function(res) {
                    $('#modalAction').modal('hide');
                    if (res.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                        btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

    });

    // ========== FUNCTION UNTUK UPLOAD BUKTI PEMBAYARAN PER TAGIHAN ==========
    function showUploadModal(idTagihan, encryptedId) {
        Swal.fire({
            title: 'Upload Bukti Pembayaran',
            html: `
            <form id="formBuktiBayarTagihan" class="text-left">
               <div class="form-group">
                  <label class="font-weight-bold">Link Bukti Pembayaran <span class="text-danger">*</span></label>
                  <input type="url" id="link_bukti" class="form-control" placeholder="https://drive.google.com/..." required>
                  <small class="form-text text-muted">Link Google Drive atau URL lainnya</small>
               </div>
               <div class="form-group">
                  <label class="font-weight-bold">Tanggal Pembayaran</label>
                  <input type="date" id="tgl_bayar" class="form-control" value="<?= date('Y-m-d') ?>">
               </div>
               <div class="form-group">
                  <label class="font-weight-bold">Keterangan</label>
                  <textarea id="ket_bayar" class="form-control" rows="2" placeholder="Keterangan (opsional)"></textarea>
               </div>
            </form>
         `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check"></i> Submit',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            preConfirm: () => {
                const link = document.getElementById('link_bukti').value;
                const tgl = document.getElementById('tgl_bayar').value;
                const ket = document.getElementById('ket_bayar').value;

                if (!link) {
                    Swal.showValidationMessage('Link bukti pembayaran wajib diisi!');
                    return false;
                }

                return {
                    link,
                    tgl,
                    ket
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const data = result.value;

                // Submit via AJAX
                $.ajax({
                    url: '<?= base_url('tagihan/upload_bukti_bayar_tagihan') ?>',
                    type: 'POST',
                    data: {
                        id_tagihan: encryptedId,
                        link_bukti_bayar: data.link,
                        tgl_bayar: data.tgl,
                        keterangan_bayar: data.ket
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan saat upload', 'error');
                    }
                });
            }
        });
    }
</script>