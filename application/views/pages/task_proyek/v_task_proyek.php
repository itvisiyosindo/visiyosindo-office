<style>
    .tp-control-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 16px 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        margin-bottom: 16px;
    }
    .tp-kpi-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 18px 20px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        transition: transform 0.15s ease;
    }
    .tp-kpi-card:hover {
        transform: translateY(-2px);
    }
    .tp-kpi-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .tp-star-display {
        color: #f59e0b;
        font-size: 14px;
        letter-spacing: 1px;
    }
    .tp-star-empty {
        color: #cbd5e1;
    }
    .tp-priority-badge {
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }
    .tp-prio-Low { background: #f1f5f9; color: #475569; }
    .tp-prio-Medium { background: #e0f2fe; color: #0369a1; }
    .tp-prio-High { background: #ffedd5; color: #c2410c; }
    .tp-prio-Urgent { background: #fee2e2; color: #b91c1c; }

    .tp-status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        display: inline-block;
    }
    .tp-st-0 { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .tp-st-1 { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .tp-st-2 { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .tp-st-3 { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .tp-st-4 { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }

    /* Interactive 5-Star Rating Picker */
    .star-rating-picker {
        display: inline-flex;
        flex-direction: row;
        gap: 8px;
        cursor: pointer;
        user-select: none;
    }
    .star-rating-picker .star-btn {
        font-size: 32px;
        color: #cbd5e1;
        transition: transform 0.15s ease, color 0.15s ease;
    }
    .star-rating-picker .star-btn.active,
    .star-rating-picker .star-btn.hover {
        color: #f59e0b;
        transform: scale(1.12);
    }
    .tp-checklist-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 6px;
        background: #f8fafc;
    }
    .tp-checklist-row.done {
        background: #f0fdf4;
        border-color: #bbf7d0;
        text-decoration: line-through;
        color: #64748b;
    }
    .tp-log-item {
        border-left: 3px solid #3b82f6;
        padding: 8px 12px;
        margin-bottom: 10px;
        background: #f8fafc;
        border-radius: 0 8px 8px 0;
    }
    /* Modern high-contrast styling for Workspace Tabs */
    .nav-tabs-tp {
        border-bottom: 2px solid #e2e8f0 !important;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        background: transparent;
    }
    .nav-tabs-tp > li {
        margin-bottom: 0;
    }
    .nav-tabs-tp > li > a,
    .nav-tabs-tp .nav-link {
        font-weight: 600 !important;
        font-size: 13.5px !important;
        color: #475569 !important; /* High contrast dark slate */
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        border-bottom: 2px solid #e2e8f0 !important;
        border-radius: 8px 8px 0 0 !important;
        padding: 11px 18px !important;
        display: inline-flex !important;
        align-items: center !important;
        text-decoration: none !important;
        transition: all 0.2s ease-in-out;
    }
    .nav-tabs-tp > li > a:hover,
    .nav-tabs-tp .nav-link:hover {
        background: #e2e8f0 !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }
    .nav-tabs-tp > li.active > a,
    .nav-tabs-tp > li.active > a:hover,
    .nav-tabs-tp > li.active > a:focus,
    .nav-tabs-tp .nav-link.active,
    .nav-tabs-tp .nav-link.active:hover,
    .nav-tabs-tp .nav-link.active:focus {
        background: #ffffff !important;
        color: #0f172a !important; /* Bold high contrast dark slate */
        font-weight: 700 !important;
        border-top: 3px solid #0088cc !important;
        border-left: 1px solid #cbd5e1 !important;
        border-right: 1px solid #cbd5e1 !important;
        border-bottom: 2px solid #ffffff !important;
        margin-bottom: -2px !important;
        box-shadow: 0 -3px 8px rgba(0, 0, 0, 0.05) !important;
    }
    .nav-tabs-tp .badge {
        font-size: 11px !important;
        font-weight: 700 !important;
        padding: 4px 10px !important;
        border-radius: 999px !important;
        line-height: 1.2 !important;
        letter-spacing: 0.2px;
        vertical-align: middle;
        display: inline-flex;
        align-items: center;
        margin-left: 8px !important;
    }
    .nav-tabs-tp .badge-primary {
        background-color: #0088cc !important;
        color: #ffffff !important;
    }
    .nav-tabs-tp .badge-warning {
        background-color: #f59e0b !important;
        color: #ffffff !important;
        box-shadow: 0 1px 4px rgba(245, 158, 11, 0.35);
    }
    .nav-tabs-tp .badge-secondary {
        background-color: #64748b !important;
        color: #ffffff !important;
    }

    /* =========================================================================
       CUSTOM JOBDESK PICKER (MULTI-LINE WRAP, CLEAN SEARCHABLE, ANTI-TERPOTONG)
       ========================================================================= */
    #dropdown_jobdesk_container {
        position: relative;
        width: 100%;
    }
    #btn_toggle_jobdesk {
        white-space: normal;
        min-height: 44px;
        padding: 9px 14px;
        border: 1.5px solid #86efac;
        border-radius: 6px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
        text-align: left;
        transition: all 0.2s ease;
    }
    #btn_toggle_jobdesk:hover,
    #btn_toggle_jobdesk:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        outline: none;
        background: #fff;
    }
    #menu_jobdesk_list {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        min-width: 100%;
        max-height: 380px;
        border: 1.5px solid #10b981;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
        overflow: hidden;
        z-index: 100050;
        margin-top: 4px;
        background: #ffffff;
        display: none;
    }
    .jobdesk-choice-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        line-height: 1.5;
        font-size: 13px;
        color: #1e293b;
        text-align: left;
        word-break: break-word;
        white-space: normal;
        transition: background-color 0.15s ease, color 0.15s ease;
    }
    .jobdesk-choice-item:hover {
        background-color: #ecfdf5 !important;
        color: #065f46 !important;
    }
    .jobdesk-group-header {
        background: #f0fdf4;
        color: #166534;
        font-weight: 700;
        font-size: 11.5px;
        padding: 7px 14px;
        border-top: 1px solid #bbf7d0;
        border-bottom: 1px solid #bbf7d0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
    }

    /* =========================================================================
       MODERN ENTERPRISE MODAL FORM STYLING (#modalBuatTask & #modalDetailTask)
       ========================================================================= */
    #modalBuatTask .modal-content,
    #modalDetailTask .modal-content,
    #modalAturAtasanSaya .modal-content,
    #modalJadwalHrdSp3 .modal-content {
        border-radius: 16px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
        overflow: hidden;
        background: #ffffff;
    }
    #modalBuatTask .modal-header,
    #modalDetailTask .modal-header,
    #modalAturAtasanSaya .modal-header,
    #modalJadwalHrdSp3 .modal-header {
        background: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 18px 24px !important;
    }
    #modalBuatTask .modal-body,
    #modalDetailTask .modal-body,
    #modalAturAtasanSaya .modal-body,
    #modalJadwalHrdSp3 .modal-body {
        padding: 22px 24px !important;
        background: #ffffff;
    }
    #modalBuatTask .modal-footer,
    #modalDetailTask .modal-footer,
    #modalAturAtasanSaya .modal-footer,
    #modalJadwalHrdSp3 .modal-footer {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 14px 24px !important;
    }
    #modalBuatTask .form-label-custom {
        font-size: 11.5px !important;
        font-weight: 700 !important;
        color: #475569 !important;
        letter-spacing: 0.35px !important;
        text-transform: uppercase !important;
        margin-bottom: 6px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    #modalBuatTask .input-group-text {
        background: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        border-right: none !important;
        color: #64748b !important;
        border-top-left-radius: 8px !important;
        border-bottom-left-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 13.5px !important;
        transition: all 0.2s ease;
    }
    #modalBuatTask .form-control {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        color: #1e293b !important;
        padding: 9px 13px !important;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    #modalBuatTask .input-group .form-control {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-left: none !important;
    }
    #modalBuatTask .form-control:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
        background-color: #ffffff !important;
    }
    #modalBuatTask .input-group:focus-within .input-group-text {
        border-color: #10b981 !important;
        color: #10b981 !important;
        background-color: #ecfdf5 !important;
    }
    #modalBuatTask .select2-container--default .select2-selection--multiple {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        min-height: 42px !important;
        padding: 4px 6px !important;
        transition: all 0.2s ease;
    }
    #modalBuatTask .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }
    #modalBuatTask .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ecfdf5 !important;
        border: 1px solid #a7f3d0 !important;
        color: #065f46 !important;
        border-radius: 6px !important;
        padding: 3px 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }
    #modalBuatTask .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #059669 !important;
        margin-right: 5px !important;
    }
    #modalBuatTask select.form-control {
        height: 42px !important;
        padding: 8px 12px !important;
        cursor: pointer;
        background-color: #ffffff !important;
    }
</style>

<?php
function renderStarsHtml($rating)
{
    $r = (int)round((float)$rating);
    $html = '<span class="tp-star-display" title="Rating ' . $rating . ' / 5">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $r) {
            $html .= '<i class="fas fa-star"></i>';
        } else {
            $html .= '<i class="far fa-star tp-star-empty"></i>';
        }
    }
    $html .= '</span>';
    return $html;
}

function renderStatusTaskBadge($st)
{
    $st = (int)$st;
    switch ($st) {
        case 0:
            return '<span class="tp-status-badge tp-st-0"><i class="far fa-clock"></i> Baru / Belum Mulai</span>';
        case 1:
            return '<span class="tp-status-badge tp-st-1"><i class="fas fa-spinner fa-spin"></i> Sedang Dikerjakan</span>';
        case 2:
            return '<span class="tp-status-badge tp-st-2"><i class="fas fa-hourglass-half"></i> Menunggu Penilaian</span>';
        case 3:
            return '<span class="tp-status-badge tp-st-3"><i class="fas fa-redo"></i> Perlu Revisi</span>';
        case 4:
            return '<span class="tp-status-badge tp-st-4"><i class="fas fa-check-circle"></i> Selesai & Dinilai</span>';
        case 5:
            return '<span class="tp-status-badge tp-st-5" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;"><i class="fas fa-hourglass-start"></i> Menunggu ACC HRD</span>';
        case 6:
            return '<span class="tp-status-badge tp-st-6" style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca;"><i class="fas fa-times-circle"></i> Ditolak HRD</span>';
        default:
            return '<span class="tp-status-badge tp-st-0">Draft</span>';
    }
}

$namaBulanList = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<header class="page-header">
    <h2><i class="fas fa-tasks text-primary mr-2"></i><?= htmlspecialchars($page_title) ?></h2>
    <div class="right-wrapper text-right">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
            <li><span>Task & Proyek Kerja</span></li>
        </ol>
        <a class="sidebar-right-toggle"><i class="fas fa-chevron-left"></i></a>
    </div>
</header>

<div class="row">
    <div class="col">

    <?php
    $canAssignToAll   = true;
    $isAdminUser      = (!empty($isHrdAdmin) || !empty($isAdmin) || isAdmin() || (int)$myId === 1);
    $jmlSupervisiSaya = count($list_bawahan);
    $canGiveTask      = true; // Sekarang semua karyawan bisa membuat / mengajukan task
    $penerimaList     = !empty($list_semua_karyawan_all) ? $list_semua_karyawan_all : $list_semua_karyawan;
    ?>

    <!-- KARTU KONTROL FILTER & STATUS SUPERVISI -->
    <div class="tp-control-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
            <!-- Filter Periode -->
            <form method="GET" action="<?= base_url('task_proyek') ?>" class="form-inline" style="gap: 8px; display: flex; flex-wrap: wrap; align-items: center;">
                <?php if (!empty($switch) && $switch === 'kepegawaian'): ?>
                    <input type="hidden" name="nav" value="kepegawaian">
                <?php endif; ?>
                <?php if (!empty($viewMode)): ?>
                    <input type="hidden" name="mode" value="<?= htmlspecialchars($viewMode) ?>">
                <?php endif; ?>

                <label class="font-weight-bold text-dark mr-1 mb-0" style="font-size: 13px;">
                    <i class="far fa-calendar-alt text-primary mr-1"></i> Periode Semester:
                </label>
                <select name="semester" class="form-control form-control-sm" style="min-width: 195px; border-radius: 6px;">
                    <option value="1" <?= ((string)($filterSemester ?? '') === '1') ? 'selected' : '' ?>>Semester 1 (Januari – Juni)</option>
                    <option value="2" <?= ((string)($filterSemester ?? '') === '2') ? 'selected' : '' ?>>Semester 2 (Juli – Desember)</option>
                </select>

                <select name="tahun" class="form-control form-control-sm" style="min-width: 95px; border-radius: 6px;">
                    <?php for ($y = date('Y') + 1; $y >= 2024; $y--): ?>
                        <option value="<?= $y ?>" <?= ((string)$filterTahun === (string)$y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>

                <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 6px; font-weight: 600; padding: 4px 12px;">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
                <a href="<?= base_url('task_proyek' . ($switch === 'kepegawaian' ? '?nav=kepegawaian' : '')) ?>" class="btn btn-sm btn-default" style="border-radius: 6px; padding: 4px 12px; border: 1px solid #cbd5e1;">
                    Reset
                </a>
            </form>

            <!-- Tombol Beri Task / Ajukan Mandiri (Tersedia untuk SEMUA Karyawan) -->
            <div>
                <button type="button" class="btn btn-sm btn-success font-weight-bold shadow-sm" id="btnOpenModalBuatTask" style="border-radius: 6px; padding: 6px 14px;">
                    <i class="fas fa-plus-circle mr-1"></i> + Buat / Ajukan Task Proyek
                </button>
            </div>
        </div>

        <hr style="margin: 12px 0 10px 0; border: 0; border-top: 1px solid #f1f5f9;">

        <!-- Status Hirarki (Atasan Langsung & Karyawan Dibawahi) -->
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 14px 20px;">
                <!-- Atasan Langsung -->
                <div class="d-flex align-items-center" style="font-size: 13px;">
                    <span class="text-muted mr-1">
                        <i class="fas fa-user-shield text-primary mr-1"></i> <strong>Atasan Langsung:</strong>
                    </span>
                    <?php if (!empty($me->nama_atasan)): ?>
                        <span class="badge badge-light border font-weight-bold px-2 py-1 text-primary" style="font-size: 12px; background: #eff6ff; border-color: #bfdbfe !important; border-radius: 6px;">
                            <?= htmlspecialchars($me->nama_atasan) ?><?= !empty($me->jabatan_atasan) ? ' (' . htmlspecialchars($me->jabatan_atasan) . ')' : '' ?>
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-primary ml-2 font-weight-bold" data-toggle="modal" data-target="#modalAturAtasanSaya" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;">
                            <i class="fas fa-user-edit mr-1"></i> Ubah
                        </button>
                    <?php elseif ($isAdminUser || (int)$myId === 1 || $jmlSupervisiSaya > 0): ?>
                        <span class="badge badge-success px-2 py-1" style="font-size: 11px; border-radius: 6px;">
                            <i class="fas fa-crown text-warning mr-1"></i> Pimpinan / Struktur Tertinggi
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-secondary ml-2 font-weight-bold" data-toggle="modal" data-target="#modalAturAtasanSaya" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;" title="Atur jika memiliki atasan langsung tertentu">
                            <i class="fas fa-cog mr-1"></i> Atur
                        </button>
                    <?php else: ?>
                        <span class="badge badge-warning px-2 py-1" style="font-size: 11px; border-radius: 6px;">Belum Diatur</span>
                        <button type="button" class="btn btn-xs btn-outline-primary ml-2 font-weight-bold" data-toggle="modal" data-target="#modalAturAtasanSaya" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;">
                            <i class="fas fa-user-edit mr-1"></i> Pilih Atasan
                        </button>
                    <?php endif; ?>
                </div>

                <div class="d-none d-md-block" style="width: 1px; height: 16px; background: #cbd5e1;"></div>

                <!-- Karyawan Dibawahi -->
                <div class="d-flex align-items-center" style="font-size: 13px;">
                    <span class="text-muted mr-1">
                        <i class="fas fa-users text-success mr-1"></i> <strong>Karyawan Dibawahi:</strong>
                    </span>
                    <span class="badge <?= $jmlSupervisiSaya > 0 ? 'badge-success' : 'badge-secondary' ?> px-2 py-1" style="font-size: 12px; font-weight: 600; border-radius: 6px;">
                        <?= $jmlSupervisiSaya ?> Karyawan
                    </span>
                    <?php if ($canAssignToAll): ?>
                        <span class="badge badge-info px-2 py-1 ml-2" title="Anda memiliki akses untuk menugaskan task ke seluruh pegawai" style="font-size: 11px; border-radius: 6px;">
                            <i class="fas fa-shield-alt mr-1"></i> Akses Penugasan (Bisa Beri Task ke Semua Pegawai)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-muted small d-none d-lg-block" style="font-size: 12px;">
                <i class="fas fa-star text-warning mr-1"></i> Evaluasi Kinerja dengan <strong>Rating Bintang 1–5 ⭐</strong>
            </div>
        </div>
    </div>

    <?php
    $isTopLeaderOnly = ($isAdminUser || (empty($me->nama_atasan) && $jmlSupervisiSaya > 0)) && (count($tugas_saya) === 0);
    $isStaffOnly     = !$canGiveTask && (count($tugas_atasan) === 0);
    ?>

    <!-- 4 KARTU KPI RINGKASAN -->
    <div class="row">
        <?php if ($isTopLeaderOnly): ?>
            <!-- Mode Pimpinan Tertinggi / Direksi (Fokus Supervisi Tim) -->
            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Penugasan Berjalan Tim</div>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b;"><?= (int)($kpi['given_active'] ?? 0) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-primary">Sedang Dikerjakan Karyawan</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Review Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #d97706;" id="kpi_need_review"><?= (int)$kpi['need_review'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-warning">Siap Diberi Rating Bintang ⭐</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Penugasan Selesai Tim</div>
                        <div style="font-size: 26px; font-weight: 800; color: #15803d;"><?= (int)($kpi['given_done'] ?? 0) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Selesai</small></div>
                        <div class="small text-success">Sudah Tuntas & Dinilai</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Karyawan Dibawahi</div>
                        <div style="font-size: 26px; font-weight: 800; color: #7c3aed;"><?= $jmlSupervisiSaya ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Pegawai</small></div>
                        <div class="small" style="color: #7c3aed;">Tim Supervisi Langsung</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #f3e8ff; color: #7c3aed;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

        <?php elseif ($isStaffOnly): ?>
            <!-- Mode Karyawan / Staf Pelaksana Murni -->
            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Aktif Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b;"><?= (int)$kpi['my_active'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-primary">Sebagai Pelaksana</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Rating Atasan</div>
                        <div style="font-size: 26px; font-weight: 800; color: #d97706;"><?= (int)($kpi['my_waiting_review'] ?? 0) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-warning">Sudah Disubmit Selesai</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Selesai Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #15803d;"><?= (int)$kpi['my_done'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Selesai</small></div>
                        <div class="small text-success">Sudah Disetujui Atasan</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">KPI Gabungan Saya</div>
                        <?php if (!empty($kpi['my_combined']) && $kpi['my_combined']->skor_akhir !== null): ?>
                            <div style="font-size: 24px; font-weight: 800; color: <?= $kpi['my_combined']->grade_color ?>;">
                                <?= number_format($kpi['my_combined']->skor_akhir, 1) ?> <small style="font-size: 13px; color:#64748b;">(Grade <?= $kpi['my_combined']->grade ?>)</small>
                            </div>
                            <div class="small">
                                <?= renderStarsHtml($kpi['my_combined']->rating_agregat) ?>
                                <span class="badge <?= $kpi['my_combined']->grade_badge ?> ml-1"><?= $kpi['my_combined']->grade_label ?></span>
                                <?php if (!empty($kpi['my_combined']->sp_level)): ?>
                                    <span class="badge badge-danger ml-1" title="Plafon maks <?= (int)$kpi['my_combined']->sp_cap ?>"><?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (Maks <?= (int)$kpi['my_combined']->sp_cap ?>)</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted" style="font-size: 10.5px;">Bobot: <?= htmlspecialchars($kpi['my_combined']->komposisi) ?></div>
                            <?php if (!empty($kpi['my_combined']->is_capped_sp)): ?>
                                <div class="text-danger font-weight-bold" style="font-size: 10.5px;">Hitungan <?= number_format($kpi['my_combined']->skor_akhir_raw, 1) ?> dibatasi plafon <?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (<?= (int)$kpi['my_combined']->sp_cap ?>)</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="font-size: 24px; font-weight: 800; color: #f59e0b;">
                                <?= number_format((float)$kpi['my_avg_star'], 2) ?> <small style="font-size: 13px; color:#64748b;">/ 5.0</small>
                            </div>
                            <div><?= renderStarsHtml($kpi['my_avg_star']) ?> <small class="text-muted">(<?= (int)$kpi['my_star_cnt'] ?> dinilai)</small></div>
                        <?php endif; ?>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fffbeb; color: #f59e0b;">
                        <i class="fas fa-medal"></i>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Mode Manajer / Supervisor (Memiliki Atasan & Memiliki Bawahan) -->
            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Aktif Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b;"><?= (int)$kpi['my_active'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-primary">Sebagai Pelaksana</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Review Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #d97706;" id="kpi_need_review"><?= (int)$kpi['need_review'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-warning">Siap Diberi Rating Bintang ⭐</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Selesai Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #15803d;"><?= (int)$kpi['my_done'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Selesai</small></div>
                        <div class="small text-success">Sudah Disetujui Atasan</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="tp-kpi-card">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">KPI Gabungan Saya</div>
                        <?php if (!empty($kpi['my_combined']) && $kpi['my_combined']->skor_akhir !== null): ?>
                            <div style="font-size: 24px; font-weight: 800; color: <?= $kpi['my_combined']->grade_color ?>;">
                                <?= number_format($kpi['my_combined']->skor_akhir, 1) ?> <small style="font-size: 13px; color:#64748b;">(Grade <?= $kpi['my_combined']->grade ?>)</small>
                            </div>
                            <div class="small">
                                <?= renderStarsHtml($kpi['my_combined']->rating_agregat) ?>
                                <span class="badge <?= $kpi['my_combined']->grade_badge ?> ml-1"><?= $kpi['my_combined']->grade_label ?></span>
                                <?php if (!empty($kpi['my_combined']->sp_level)): ?>
                                    <span class="badge badge-danger ml-1" title="Plafon maks <?= (int)$kpi['my_combined']->sp_cap ?>"><?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (Maks <?= (int)$kpi['my_combined']->sp_cap ?>)</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted" style="font-size: 10.5px;">Bobot: <?= htmlspecialchars($kpi['my_combined']->komposisi) ?></div>
                            <?php if (!empty($kpi['my_combined']->is_capped_sp)): ?>
                                <div class="text-danger font-weight-bold" style="font-size: 10.5px;">Hitungan <?= number_format($kpi['my_combined']->skor_akhir_raw, 1) ?> dibatasi plafon <?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (<?= (int)$kpi['my_combined']->sp_cap ?>)</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="font-size: 24px; font-weight: 800; color: #f59e0b;">
                                <?= number_format((float)$kpi['my_avg_star'], 2) ?> <small style="font-size: 13px; color:#64748b;">/ 5.0</small>
                            </div>
                            <div><?= renderStarsHtml($kpi['my_avg_star']) ?> <small class="text-muted">(<?= (int)$kpi['my_star_cnt'] ?> dinilai)</small></div>
                        <?php endif; ?>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fffbeb; color: #f59e0b;">
                        <i class="fas fa-medal"></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- PANEL UTAMA BERBASIS TAB -->
    <section class="card card-featured card-featured-primary">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
            </div>
            <h2 class="card-title"><i class="fas fa-project-diagram mr-1"></i> Workspace Penugasan Proyek & Evaluasi Bintang 5</h2>
        </header>
        <div class="card-body">
            <?php
            $tabParam = $this->input->get('tab', TRUE);
            $openTabSemua  = ($tabParam === 'semua' || $tabParam === 'monitoring');
            $openTabAtasan = ($tabParam === 'atasan') || (empty($tugas_saya) && !empty($tugas_atasan) && !$openTabSemua);
            $openTabSaya   = !$openTabAtasan && !$openTabSemua;
            ?>
            <ul class="nav nav-tabs nav-tabs-tp" role="tablist">
                <li class="nav-item <?= $openTabSaya ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabSaya ? 'active' : '' ?>" href="#tab_tugas_saya" data-toggle="tab" role="tab">
                        <i class="fas fa-inbox text-primary mr-2"></i> Tugas Saya (Pelaksana)
                        <?php if ($kpi['my_active'] > 0): ?>
                            <span class="badge badge-primary ml-2"><?= (int)$kpi['my_active'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item <?= $openTabAtasan ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabAtasan ? 'active' : '' ?>" href="#tab_tugas_atasan" data-toggle="tab" role="tab">
                        <i class="fas fa-paper-plane text-success mr-2"></i> Tugas yang Saya Berikan / Ajukan
                        <?php if ($kpi['need_review'] > 0): ?>
                            <span class="badge badge-warning ml-2" id="badge_need_review"><?= (int)$kpi['need_review'] ?> Perlu Rating â­</span>
                        <?php else: ?>
                            <span class="badge badge-secondary ml-2" id="badge_need_review"><?= count($tugas_atasan) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item <?= $openTabSemua ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabSemua ? 'active' : '' ?>" href="#tab_semua_task" data-toggle="tab" role="tab">
                        <i class="fas fa-users-cog text-info mr-2"></i> Semua Task Perusahaan (Monitoring Tim)
                        <?php if (!empty($kpi['waiting_acc_hrd']) && $kpi['waiting_acc_hrd'] > 0): ?>
                            <span class="badge badge-warning ml-2 font-weight-bold" title="<?= $kpi['waiting_acc_hrd'] ?> Pengajuan Mandiri Butuh ACC HRD"><i class="fas fa-hourglass-start"></i> <?= (int)$kpi['waiting_acc_hrd'] ?> Butuh ACC</span>
                        <?php else: ?>
                            <span class="badge badge-info ml-2"><?= !empty($semua_task) ? count($semua_task) : 0 ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_leaderboard" data-toggle="tab" role="tab">
                        <i class="fas fa-trophy text-warning mr-2"></i> Leaderboard KPI & Evaluasi Karyawan
                    </a>
                </li>
            </ul>

            <div class="tab-content pt-3">
                <!-- ================= TAB 1: TUGAS SAYA (SEBAGAI KARYAWAN) ================= -->
                <div id="tab_tugas_saya" class="tab-pane <?= !$openTabAtasan ? 'active' : '' ?>" role="tabpanel">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Daftar Tugas Anda:</strong> Klik tombol <strong>Update Progres / Detail</strong> untuk mencentang checklist pekerjaan, dan klik <strong>Submit Selesai</strong> jika pekerjaan sudah 100% tuntas agar Atasan dapat memberikan penilaian <strong>Rating Bintang (1–5 ⭐)</strong>.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="datatable_tugas_saya">
                            <thead>
                                <tr>
                                    <th width="45">No</th>
                                    <th>Kode & Proyek</th>
                                    <th>Judul Task / Instruksi</th>
                                    <th>Pemberi Tugas (Atasan)</th>
                                    <th>Prioritas & Deadline</th>
                                    <th width="140">Progres</th>
                                    <th>Status</th>
                                    <th>Rating & Ulasan Atasan</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($tugas_saya as $row):
                                    $isOverdue = (!empty($row->tgl_deadline) && (int)$row->status_task < 4 && date('Y-m-d') > $row->tgl_deadline);
                                ?>
                                    <tr id="row_task_saya_<?= (int)$row->id_task ?>" data-id="<?= (int)$row->id_task ?>">
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <strong class="text-primary"><?= htmlspecialchars($row->kode_task) ?></strong><br>
                                            <span class="badge badge-light border"><?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?></span>
                                            <div class="small text-muted mt-1"><?= htmlspecialchars($row->jenis) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($row->judul) ?></div>
                                            <?php if (!empty($row->deskripsi)): ?>
                                                <div class="small text-muted" style="max-width: 280px; white-space: normal;">
                                                    <?= nl2br(htmlspecialchars(mb_strimwidth($row->deskripsi, 0, 120, '...'))) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ((int)$row->status_task === 3 && !empty($row->catatan_revisi)): ?>
                                                <div class="mt-1 p-1 rounded" style="background:#fee2e2; color:#991b1b; font-size:11.5px;">
                                                    <strong><i class="fas fa-exclamation-triangle"></i> Revisi Atasan:</strong> <?= htmlspecialchars($row->catatan_revisi) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($row->nama_pemberi ?: '-') ?></strong>
                                            <div class="small text-muted"><?= htmlspecialchars($row->jabatan_pemberi ?: '') ?></div>
                                        </td>
                                        <td>
                                            <span class="tp-priority-badge tp-prio-<?= htmlspecialchars($row->prioritas) ?>"><?= htmlspecialchars($row->prioritas) ?></span>
                                            <div class="small mt-1">
                                                <i class="far fa-calendar-check text-muted"></i>
                                                <?= !empty($row->tgl_deadline) ? date('d-m-Y', strtotime($row->tgl_deadline)) : '-' ?>
                                            </div>
                                            <?php if (!empty($row->is_late)): ?>
                                                <span class="badge badge-danger mt-1" title="Melewati batas deadline (Maksimal Rating Bintang 1)">
                                                    <i class="fas fa-exclamation-circle"></i> Terlambat H+<?= (int)$row->hari_telat ?> (Maks ⭐1)
                                                </span>
                                            <?php elseif ($isOverdue): ?>
                                                <span class="badge badge-danger mt-1">Terlambat / Overdue</span>
                                            <?php endif; ?>
                                            <?php if ((int)($row->sp_level ?? 0) >= 1): ?>
                                                <div class="mt-1">
                                                    <span class="badge <?= htmlspecialchars($row->sp_badge_cls ?? 'badge-danger') ?> px-2 py-1" style="font-size: 11px;">
                                                        <i class="fas fa-gavel mr-1"></i> <?= htmlspecialchars($row->sp_label ?? ('SP ' . (int)$row->sp_level)) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->hrd_jadwal_tgl)): ?>
                                                <div class="mt-1 p-1 rounded small" style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; font-size:10.5px;">
                                                    <strong><i class="fas fa-handshake"></i> Jadwal HRD:</strong><br>
                                                    <?= date('d-m-Y', strtotime($row->hrd_jadwal_tgl)) ?> <?= !empty($row->hrd_jadwal_jam) ? htmlspecialchars($row->hrd_jadwal_jam) . ' WIB' : '' ?>
                                                    <?php if (!empty($row->hrd_jadwal_lokasi)): ?>
                                                        <br><span class="text-muted"><?= htmlspecialchars($row->hrd_jadwal_metode ?: 'Tatap Muka') ?> - <?= htmlspecialchars($row->hrd_jadwal_lokasi) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td><?= renderStatusTaskBadge(((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : $row->status_task) ?></td>
                                        <td>
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                                <?php if (!empty($row->is_late)): ?>
                                                    <div class="badge badge-light-danger text-danger mt-1" style="font-size:10px;">Maks ⭐1 (Lewat Deadline)</div>
                                                <?php endif; ?>
                                                <?php if (!empty($row->ulasan_atasan)): ?>
                                                    <div class="small text-muted mt-1" style="max-width:200px; white-space:normal;">"<?= htmlspecialchars($row->ulasan_atasan) ?>"</div>
                                                <?php endif; ?>
                                            <?php elseif (!empty($row->is_late)): ?>
                                                <span class="badge badge-danger" style="font-size:10.5px;"><i class="fas fa-lock mr-1"></i> Maks ⭐ 1/5</span>
                                                <div class="text-muted small mt-1"><em>Lewat deadline H+<?= (int)$row->hari_telat ?></em></div>
                                            <?php else: ?>
                                                <span class="text-muted small"><em>Belum dinilai</em></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-primary mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="progres">
                                                <i class="fas fa-edit"></i> Detail / Progres
                                            </button>
                                            <?php if ((int)$row->status_task < 4 && (int)$row->status_task !== 2): ?>
                                                <br>
                                                <button type="button" class="btn btn-xs btn-success mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="submit">
                                                    <i class="fas fa-check"></i> Submit Selesai
                                                </button>
                                            <?php endif; ?>
                                            <?php if ((int)($row->sp_level ?? 0) >= 3): ?>
                                                <br>
                                                <button type="button" class="btn btn-xs btn-danger font-weight-bold btn-jadwal-hrd-sp3"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>"
                                                    data-karyawan="<?= htmlspecialchars($row->nama_penerima ?: ($me->nama ?? '-')) ?>"
                                                    data-telat="<?= (int)($row->hari_telat ?? 21) ?>"
                                                    data-tgl="<?= htmlspecialchars($row->hrd_jadwal_tgl ?? '') ?>"
                                                    data-jam="<?= htmlspecialchars($row->hrd_jadwal_jam ?? '10:00') ?>"
                                                    data-metode="<?= htmlspecialchars($row->hrd_jadwal_metode ?? 'Tatap Muka (Offline)') ?>"
                                                    data-lokasi="<?= htmlspecialchars($row->hrd_jadwal_lokasi ?? 'Ruang HRD') ?>"
                                                    data-catatan="<?= htmlspecialchars($row->hrd_jadwal_catatan ?? '') ?>">
                                                    <i class="fas fa-calendar-check"></i> <?= !empty($row->hrd_jadwal_tgl) ? 'Ubah Jadwal HRD' : 'Jadwalkan HRD (SP 3)' ?>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB 2: PENUGASAN KE KARYAWAN (SEBAGAI ATASAN) ================= -->
                <?php if ($canGiveTask || count($tugas_atasan) > 0): ?>
                <div id="tab_tugas_atasan" class="tab-pane <?= $openTabAtasan ? 'active' : '' ?>" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div>
                            <span class="text-muted">Memantau progres tugas yang diberikan kepada karyawan dan memberikan penilaian <strong>Rating Bintang 5</strong>.</span>
                        </div>
                        <?php if ($canViewAll): ?>
                            <div class="btn-group btn-group-sm mt-2 mt-md-0">
                                <a href="<?= base_url('task_proyek?mode=all' . ($switch === 'kepegawaian' ? '&nav=kepegawaian' : '')) ?>" class="btn <?= ($viewMode !== 'mine') ? 'btn-primary' : 'btn-default' ?>">
                                    <i class="fas fa-globe"></i> Semua Penugasan Perusahaan
                                </a>
                                <a href="<?= base_url('task_proyek?mode=mine' . ($switch === 'kepegawaian' ? '&nav=kepegawaian' : '')) ?>" class="btn <?= ($viewMode === 'mine') ? 'btn-primary' : 'btn-default' ?>">
                                    <i class="fas fa-user-tie"></i> Hanya Penugasan Dari Saya
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="datatable_tugas_atasan">
                            <thead>
                                <tr>
                                    <th width="45">No</th>
                                    <th>Kode & Proyek</th>
                                    <th>Penerima Tugas (Karyawan)</th>
                                    <th>Judul Task & Hasil Karyawan</th>
                                    <th>Pemberi Tugas</th>
                                    <th>Prioritas & Deadline</th>
                                    <th width="120">Progres</th>
                                    <th>Status</th>
                                    <th>Rating Bintang ⭐</th>
                                    <th width="165">Aksi Atasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($tugas_atasan as $row):
                                    $isOverdue = (!empty($row->tgl_deadline) && (int)$row->status_task < 4 && date('Y-m-d') > $row->tgl_deadline);
                                ?>
                                    <tr id="row_task_atasan_<?= (int)$row->id_task ?>" data-id="<?= (int)$row->id_task ?>">
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <strong class="text-primary"><?= htmlspecialchars($row->kode_task) ?></strong><br>
                                            <span class="badge badge-light border"><?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?></span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($row->nama_penerima ?: '-') ?></strong>
                                            <div class="small text-muted"><?= htmlspecialchars($row->jabatan_penerima ?: '') ?></div>
                                            <?php if (!empty($row->divisi_penerima)): ?>
                                                <span class="badge badge-info" style="font-size:10px;"><?= htmlspecialchars($row->divisi_penerima) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($row->judul) ?></div>
                                            <?php if (!empty($row->catatan_bawahan)): ?>
                                                <div class="mt-1 p-1 rounded" style="background: #f0fdf4; border: 1px solid #bbf7d0; font-size: 11.5px;">
                                                    <strong>Laporan Karyawan:</strong> <?= htmlspecialchars(mb_strimwidth($row->catatan_bawahan, 0, 110, '...')) ?>
                                                    <?php if (!empty($row->bukti_selesai)): ?>
                                                        <br><a href="<?= htmlspecialchars($row->bukti_selesai) ?>" target="_blank" class="text-primary font-weight-bold"><i class="fas fa-external-link-alt"></i> Buka Bukti / Lampiran</a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="small font-weight-bold"><?= htmlspecialchars($row->nama_pemberi ?: '-') ?></span>
                                            <?php if (!empty($row->nama_mengetahui)): ?>
                                                <div class="small text-muted mt-1" style="font-size: 11px;"><i class="fas fa-eye text-primary mr-1"></i> Mengetahui: <strong><?= htmlspecialchars($row->nama_mengetahui) ?></strong></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="tp-priority-badge tp-prio-<?= htmlspecialchars($row->prioritas) ?>"><?= htmlspecialchars($row->prioritas) ?></span>
                                            <div class="small mt-1">
                                                <?= !empty($row->tgl_deadline) ? date('d-m-Y', strtotime($row->tgl_deadline)) : '-' ?>
                                            </div>
                                            <?php if (!empty($row->is_late)): ?>
                                                <span class="badge badge-danger mt-1" title="Melewati batas deadline (Maksimal Rating Bintang 1)">
                                                    <i class="fas fa-exclamation-circle"></i> H+<?= (int)$row->hari_telat ?> (Maks ⭐1)
                                                </span>
                                            <?php elseif ($isOverdue): ?>
                                                <span class="badge badge-danger mt-1">Overdue</span>
                                            <?php endif; ?>
                                            <?php if ((int)($row->sp_level ?? 0) >= 1): ?>
                                                <div class="mt-1">
                                                    <span class="badge <?= htmlspecialchars($row->sp_badge_cls ?? 'badge-danger') ?> px-2 py-1" style="font-size: 11px;">
                                                        <i class="fas fa-gavel mr-1"></i> <?= htmlspecialchars($row->sp_label ?? ('SP ' . (int)$row->sp_level)) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->hrd_jadwal_tgl)): ?>
                                                <div class="mt-1 p-1 rounded small" style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; font-size:10.5px;">
                                                    <strong><i class="fas fa-handshake"></i> Jadwal HRD:</strong><br>
                                                    <?= date('d-m-Y', strtotime($row->hrd_jadwal_tgl)) ?> <?= !empty($row->hrd_jadwal_jam) ? htmlspecialchars($row->hrd_jadwal_jam) . ' WIB' : '' ?>
                                                    <?php if (!empty($row->hrd_jadwal_lokasi)): ?>
                                                        <br><span class="text-muted"><?= htmlspecialchars($row->hrd_jadwal_metode ?: 'Tatap Muka') ?> - <?= htmlspecialchars($row->hrd_jadwal_lokasi) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-info' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td><?= renderStatusTaskBadge(((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : $row->status_task) ?></td>
                                        <td>
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                                <?php if (!empty($row->is_late)): ?>
                                                    <div class="badge badge-light-danger text-danger mt-1" style="font-size:10px;">Maks ⭐1 (Lewat Deadline)</div>
                                                <?php endif; ?>
                                                <?php if (!empty($row->ulasan_atasan)): ?>
                                                    <div class="small text-muted mt-1" style="max-width:180px; white-space:normal;">"<?= htmlspecialchars($row->ulasan_atasan) ?>"</div>
                                                <?php endif; ?>
                                            <?php elseif ((int)$row->status_task === 2): ?>
                                                <span class="badge badge-warning"><i class="fas fa-star"></i> Siap Dinilai</span>
                                                <?php if (!empty($row->is_late)): ?>
                                                    <div class="badge badge-danger mt-1" style="font-size:10px;"><i class="fas fa-lock"></i> Maks ⭐1</div>
                                                <?php endif; ?>
                                            <?php elseif (!empty($row->is_late)): ?>
                                                <span class="badge badge-danger" style="font-size:10px;"><i class="fas fa-lock"></i> Maks ⭐1</span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $effStatusAtasan = ((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : (int)$row->status_task;
                                            $alreadyRated = ($effStatusAtasan === 4 && !empty($row->rating_bintang));
                                            ?>
                                                                                        <?php if ((int)$row->status_task === 5 && !empty($isHrdAdmin)): ?>
                                                <button type="button" class="btn btn-xs btn-success font-weight-bold mb-1 btn-acc-hrd"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                    <i class="fas fa-check-circle"></i> ACC Task
                                                </button><br>
                                                <button type="button" class="btn btn-xs btn-outline-danger font-weight-bold mb-1 btn-tolak-hrd"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button><br>
                                            <?php endif; ?>
                                            <?php 
                                            $isSelfRow = ((int)$row->id_penerima === (int)$myId);
                                            if ($effStatusAtasan === 2 && !$alreadyRated && !$isSelfRow): 
                                            ?>
                                                <button type="button" class="btn btn-xs btn-warning font-weight-bold mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="review">
                                                    <i class="fas fa-star"></i> Beri Rating ⭐
                                                </button><br>
                                            <?php elseif ($effStatusAtasan === 2 && !$alreadyRated && $isSelfRow): ?>
                                                <span class="badge badge-warning mb-1" style="font-size: 10px;"><i class="fas fa-hourglass-half"></i> Menunggu Rating HRD</span><br>
                                                <button type="button" class="btn btn-xs btn-info mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="detail">
                                                    <i class="fas fa-eye"></i> Detail Task
                                                </button><br>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-xs btn-info mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="detail">
                                                    <i class="fas fa-eye"></i> Detail Task
                                                </button><br>
                                            <?php endif; ?>

                                            <?php if ((int)($row->sp_level ?? 0) >= 3): ?>
                                                <button type="button" class="btn btn-xs btn-danger font-weight-bold mb-1 btn-jadwal-hrd-sp3"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>"
                                                    data-karyawan="<?= htmlspecialchars($row->nama_penerima ?: '-') ?>"
                                                    data-telat="<?= (int)($row->hari_telat ?? 21) ?>"
                                                    data-tgl="<?= htmlspecialchars($row->hrd_jadwal_tgl ?? '') ?>"
                                                    data-jam="<?= htmlspecialchars($row->hrd_jadwal_jam ?? '10:00') ?>"
                                                    data-metode="<?= htmlspecialchars($row->hrd_jadwal_metode ?? 'Tatap Muka (Offline)') ?>"
                                                    data-lokasi="<?= htmlspecialchars($row->hrd_jadwal_lokasi ?? 'Ruang HRD') ?>"
                                                    data-catatan="<?= htmlspecialchars($row->hrd_jadwal_catatan ?? '') ?>">
                                                    <i class="fas fa-calendar-check"></i> <?= !empty($row->hrd_jadwal_tgl) ? 'Jadwal HRD' : 'Jadwalkan HRD (SP 3)' ?>
                                                </button><br>
                                            <?php endif; ?>

                                            <?php if ((int)$row->id_pemberi === (int)$myId || $canViewAll): ?>
                                                <button type="button" class="btn btn-xs btn-danger btn-hapus-task" data-id="<?= (int)$row->id_task ?>" data-kode="<?= htmlspecialchars($row->kode_task) ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ================= TAB 3: SEMUA TASK PERUSAHAAN (MONITORING TIM) ================= -->
                <div id="tab_semua_task" class="tab-pane <?= $openTabSemua ? 'active' : '' ?>" role="tabpanel">
                    <div class="alert alert-light border py-2 mb-3" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
                            <div>
                                <i class="fas fa-users text-primary mr-1"></i>
                                <strong>Monitoring Seluruh Task Perusahaan:</strong> Halaman transparansi untuk melihat tugas yang sedang dikerjakan rekan kerja di seluruh divisi PT Visi Yosindo Medikal.
                            </div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                <label class="font-weight-bold text-dark small mb-0"><i class="fas fa-filter text-muted mr-1"></i> Saring:</label>
                                <select id="filter_semua_karyawan" class="form-control form-control-sm" style="min-width: 170px; border-radius: 6px;">
                                    <option value="">-- Semua Karyawan --</option>
                                    <?php foreach ($list_semua_karyawan_all as $kAll): ?>
                                        <option value="<?= htmlspecialchars(strtolower($kAll->nama)) ?>"><?= htmlspecialchars($kAll->nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select id="filter_semua_status" class="form-control form-control-sm" style="min-width: 150px; border-radius: 6px;">
                                    <option value="">-- Semua Status --</option>
                                    <option value="Menunggu ACC HRD">Menunggu ACC HRD</option>
                                    <option value="Baru">Baru / Belum Mulai</option>
                                    <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                                    <option value="Menunggu Penilaian">Menunggu Penilaian</option>
                                    <option value="Perlu Revisi">Perlu Revisi</option>
                                    <option value="Selesai">Selesai & Dinilai</option>
                                    <option value="Ditolak">Ditolak HRD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="datatable_semua_task" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="45">No</th>
                                    <th>Kode & Proyek</th>
                                    <th>Judul Task & Jobdesk</th>
                                    <th>Pelaksana (Karyawan)</th>
                                    <th>Pemberi / Pengaju</th>
                                    <th>Mengetahui</th>
                                    <th>Prioritas & Deadline</th>
                                    <th width="120">Progres</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                    <th width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $noAll = 1; foreach ($semua_task as $row):
                                    $isOverdue = (!empty($row->tgl_deadline) && (int)$row->status_task < 4 && date('Y-m-d') > $row->tgl_deadline);
                                ?>
                                    <tr id="row_semua_task_<?= (int)$row->id_task ?>" data-id="<?= (int)$row->id_task ?>">
                                        <td class="text-center"><?= $noAll++ ?></td>
                                        <td>
                                            <strong class="text-primary"><?= htmlspecialchars($row->kode_task) ?></strong><br>
                                            <span class="badge badge-light border"><?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?></span>
                                            <div class="small text-muted mt-1"><?= htmlspecialchars($row->jenis) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($row->judul) ?></div>
                                            <?php if (!empty($row->nama_jobdesk)): ?>
                                                <div class="mt-1">
                                                    <span class="badge badge-success px-2 py-1" style="font-size: 11px; white-space: normal; text-align: left; line-height: 1.4; display: inline-block; max-width: 100%; word-break: break-word;">
                                                        <i class="fas fa-bullseye mr-1"></i> <?= htmlspecialchars($row->nama_jobdesk) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->deskripsi)): ?>
                                                <div class="small text-muted mt-1" style="max-width: 250px; white-space: normal;">
                                                    <?= nl2br(htmlspecialchars(mb_strimwidth($row->deskripsi, 0, 100, '...'))) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong class="text-dark"><?= htmlspecialchars($row->nama_penerima ?: '-') ?></strong>
                                            <?php if (!empty($row->jabatan_penerima)): ?>
                                                <div class="small text-muted"><?= htmlspecialchars($row->jabatan_penerima) ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->divisi_penerima)): ?>
                                                <span class="badge badge-light border text-info" style="font-size:10.5px;"><?= htmlspecialchars($row->divisi_penerima) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($row->is_mandiri)): ?>
                                                <div class="mt-1"><span class="badge badge-warning text-dark" style="font-size: 10px;"><i class="fas fa-user-edit mr-1"></i> Mandiri</span></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="small font-weight-bold"><?= htmlspecialchars($row->nama_pemberi ?: '-') ?></span>
                                            <?php if (!empty($row->jabatan_pemberi)): ?>
                                                <div class="small text-muted"><?= htmlspecialchars($row->jabatan_pemberi) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($row->nama_mengetahui)): ?>
                                                <span class="small font-weight-bold text-dark"><i class="fas fa-eye text-primary mr-1"></i> <?= htmlspecialchars($row->nama_mengetahui) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="tp-priority-badge tp-prio-<?= htmlspecialchars($row->prioritas) ?>"><?= htmlspecialchars($row->prioritas) ?></span>
                                            <div class="small mt-1">
                                                <i class="far fa-calendar-check text-muted mr-1"></i>
                                                <?= !empty($row->tgl_deadline) ? date('d-m-Y', strtotime($row->tgl_deadline)) : '-' ?>
                                            </div>
                                            <?php if (!empty($row->is_late)): ?>
                                                <span class="badge badge-danger mt-1">Terlambat H+<?= (int)$row->hari_telat ?></span>
                                            <?php elseif ($isOverdue): ?>
                                                <span class="badge badge-danger mt-1">Overdue</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td>
                                            <?= renderStatusTaskBadge(((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : $row->status_task) ?>
                                            <?php if (!empty($row->nama_acc_hrd) && (int)$row->status_task !== 5): ?>
                                                <div class="small text-muted mt-1" style="font-size:10px;"><i class="fas fa-check-double text-success"></i> ACC HRD: <?= htmlspecialchars($row->nama_acc_hrd) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                            <?php else: ?>
                                                <span class="text-muted small"><em>-</em></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-primary mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="detail">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                            <?php if ((int)$row->status_task === 2 && empty($row->rating_bintang) && (int)$row->id_penerima !== (int)$myId && !empty($isHrdAdmin)): ?>
                                                <br>
                                                <button type="button" class="btn btn-xs btn-warning font-weight-bold mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="review">
                                                    <i class="fas fa-star"></i> Nilai (HRD)
                                                </button>
                                            <?php endif; ?>
                                            <?php if ((int)$row->status_task === 5 && !empty($isHrdAdmin)): ?>
                                                <br>
                                                <button type="button" class="btn btn-xs btn-success font-weight-bold mb-1 btn-acc-hrd"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                    <i class="fas fa-check-circle"></i> ACC
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-danger font-weight-bold mb-1 btn-tolak-hrd"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB 3: LEADERBOARD KPI & EVALUASI KARYAWAN ================= -->
                <div id="tab_leaderboard" class="tab-pane" role="tabpanel">
                    <div class="alert alert-info py-2 mb-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px; border-left: 4px solid #0284c7; background: #f0f9ff; color: #0369a1;">
                        <div>
                            <i class="fas fa-medal text-warning mr-1"></i>
                            <strong>Leaderboard <?= htmlspecialchars($semLabel ?? '') ?> (Direset Per Semester &bull; Ratio 80% Mutu + 20% Volume):</strong>
                            <strong>Reguler:</strong> 25% Task Proyek + 20% Laporan + 15% Pencapaian + 10% Product Knowledge + 15% Absensi + 15% Evaluasi Semester
                            <span class="text-muted">(Bebas Product Knowledge: 30% Task + 20% Lap + 20% Penc + 15% Absensi + 15% Evaluasi)</span>.
                            Keterlambatan absen mengurangi poin; Evaluasi yang belum diisi dianggap belum dinilai dulu.
                            <strong>Plafon SP:</strong> SP0 Maks 100% &bull; SP1 Maks 80% &bull; SP2 Maks 60% &bull; SP3 Maks 40%.
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 6px; font-size: 11px;">
                            <span class="badge badge-success px-2 py-1">Grade A &ge; 85 (Sangat Baik)</span>
                            <span class="badge badge-primary px-2 py-1">Grade B: 75–84 (Baik)</span>
                            <span class="badge badge-warning px-2 py-1">Grade C: 65–74 (Cukup)</span>
                            <span class="badge badge-danger px-2 py-1">Grade D &lt; 65 (Kurang / PIP)</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="datatable_leaderboard">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th class="text-center align-middle" style="min-width: 74px;">Rank</th>
                                    <th class="align-middle">Nama Karyawan</th>
                                    <th class="align-middle">Jabatan</th>
                                    <th class="text-center align-middle" style="min-width: 110px;">
                                        <div>Task Proyek</div>
                                        <small class="badge badge-primary" style="font-size: 10px;">Mutu 25%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 115px;">
                                        <div>Laporan</div>
                                        <small class="badge badge-info" style="font-size: 10px;">Mutu 20%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 110px;">
                                        <div>Pencapaian</div>
                                        <small class="badge text-white" style="font-size: 10px; background-color: #8b5cf6;">Mutu 15%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 130px;">
                                        <div>Product Knowledge</div>
                                        <small class="badge text-white" style="font-size: 10px; background-color: #0d9488;">Mutu 10%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 115px;">
                                        <div>Absensi</div>
                                        <small class="badge text-white" style="font-size: 10px; background-color: #0284c7;">Mutu 15%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 115px;">
                                        <div>Evaluasi Smt</div>
                                        <small class="badge text-white" style="font-size: 10px; background-color: #6366f1;">Mutu 15%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 100px;">
                                        <div>Status SP</div>
                                        <small class="badge badge-dark" style="font-size: 10px;">Plafon Maks</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 130px; background: #f0fdf4;">
                                        <div class="text-success font-weight-bold">Skor Akhir</div>
                                        <small class="text-muted" style="font-size: 10px;">80% Mutu + 20% Vol</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 130px;">Rating Agregat</th>
                                    <th class="text-center align-middle" width="120">Grade Kinerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $topPins = [
                                    1 => ['icon' => '🥇', 'bg' => '#fef08a', 'fg' => '#854d0e', 'bd' => '#eab308', 'title' => 'Top 1 Leaderboard'],
                                    2 => ['icon' => '🥈', 'bg' => '#e2e8f0', 'fg' => '#334155', 'bd' => '#94a3b8', 'title' => 'Top 2 Leaderboard'],
                                    3 => ['icon' => '🥉', 'bg' => '#fed7aa', 'fg' => '#9a3412', 'bd' => '#f97316', 'title' => 'Top 3 Leaderboard'],
                                    4 => ['icon' => '🏅', 'bg' => '#dbeafe', 'fg' => '#1e40af', 'bd' => '#60a5fa', 'title' => 'Top 4 Leaderboard'],
                                    5 => ['icon' => '🏅', 'bg' => '#ede9fe', 'fg' => '#5b21b6', 'bd' => '#a78bfa', 'title' => 'Top 5 Leaderboard'],
                                ];
                                $rank = 1;
                                foreach ($leaderboard as $lb):
                                    $selesai = (int)$lb->task_selesai;
                                    $isMe    = (!empty($myReceiverIds) && is_array($myReceiverIds)) ? in_array((int)$lb->pengguna_id, $myReceiverIds, true) : ((int)$lb->pengguna_id === (int)$myId);
                                ?>
                                    <tr <?= $isMe ? 'style="background-color: #f0f9ff;"' : '' ?>>
                                        <td class="text-center font-weight-bold align-middle" data-order="<?= $rank ?>">
                                            <?php if ($lb->skor_akhir !== null && isset($topPins[$rank])): $pin = $topPins[$rank]; ?>
                                                <span class="badge px-2 py-1" style="font-size:12.5px; white-space:nowrap; background:<?= $pin['bg'] ?>; color:<?= $pin['fg'] ?>; border:1px solid <?= $pin['bd'] ?>; border-radius:20px; font-weight:800;" title="<?= $pin['title'] ?>"><?= $pin['icon'] ?> #<?= $rank ?></span>
                                            <?php else: ?>
                                                <span class="text-muted font-weight-semibold">#<?= $rank ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <strong><?= htmlspecialchars($lb->nama) ?></strong>
                                            <?php if ($isMe): ?>
                                                <span class="badge badge-primary ml-1" style="font-size: 10px;">Saya</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <span><?= htmlspecialchars($lb->jabatan ?: '-') ?></span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($lb->skor_task !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 14px; color: #1e293b;"><?= number_format($lb->skor_task, 1) ?></div>
                                                <div class="small text-muted" style="font-size: 11px;">
                                                    <i class="fas fa-star text-warning"></i> <?= number_format($lb->avg_bintang, 2) ?> <span class="text-muted">(<?= $selesai ?> task)</span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum ada task</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($lb->skor_lap !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 14px; color: #0284c7;"><?= number_format($lb->skor_lap, 1) ?></div>
                                                <div class="small text-muted" style="font-size: 11px;">
                                                    Poin: <?= number_format($lb->total_poin_laporan, 0) ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum dinilai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($lb->skor_penc !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 14px; color: #7c3aed;"><?= number_format($lb->skor_penc, 1) ?></div>
                                                <div class="small text-muted" style="font-size: 11px;">
                                                    <i class="fas fa-award mr-1" style="color: #8b5cf6;"></i><?= (int)$lb->total_pencapaian ?> item
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum dinilai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if (!empty($lb->is_exempt_pk)): ?>
                                                <span class="badge badge-light text-muted px-2 py-1" style="border: 1px dashed #cbd5e1; font-size: 11px;" title="Dikecualikan dari Product Knowledge (HR/GA/Security/Manajemen)">
                                                    <i class="fas fa-minus-circle mr-1 text-secondary"></i>Bebas PK
                                                </span>
                                            <?php elseif ($lb->skor_pk !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 14px; color: #0d9488;"><?= number_format($lb->skor_pk, 1) ?></div>
                                                <div class="small text-muted" style="font-size: 11px;">
                                                    <i class="fas fa-book-reader mr-1" style="color: #0d9488;"></i><?= (int)$lb->total_pk ?> kuis
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum kuis</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($lb->skor_abs !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 14px; color: #0369a1;"><?= number_format($lb->skor_abs, 1) ?></div>
                                                <div class="small text-muted" style="font-size: 11px;">
                                                    <?= (int)$lb->abs_hari_hadir ?>/<?= (int)$lb->abs_hari_kerja ?> hr
                                                    <?php if ((int)$lb->abs_hari_terlambat > 0): ?>
                                                        <span class="badge badge-danger px-1 py-0 ml-1" style="font-size: 9.5px;"><?= (int)$lb->abs_hari_terlambat ?> telat</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum ada data</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($lb->skor_eval !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 14px; color: #4f46e5;"><?= number_format($lb->skor_eval, 1) ?></div>
                                                <div class="small text-muted" style="font-size: 11px;">
                                                    <?= htmlspecialchars($lb->eval_label ?: 'Evaluasi') ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum dinilai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php
                                                $spLv = (int)($lb->sp_level ?? 0);
                                                $spCp = (int)($lb->sp_cap ?? 100);
                                                $spTooltip = ($spLv > 0 && !empty($lb->sp_kode))
                                                    ? htmlspecialchars($lb->sp_kode . ' (berlaku s/d ' . $lb->sp_akhir . ')')
                                                    : 'Tidak ada SP aktif';
                                            ?>
                                            <?php if ($spLv === 0): ?>
                                                <span class="badge badge-light text-success px-2 py-1" style="border: 1px solid #bbf7d0; font-size: 11px;" title="<?= $spTooltip ?>">SP 0</span>
                                                <div class="small text-muted" style="font-size: 10px;">Maks 100%</div>
                                            <?php elseif ($spLv === 1): ?>
                                                <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 11px; font-weight: 700;" title="<?= $spTooltip ?>">SP 1</span>
                                                <div class="small font-weight-bold text-warning" style="font-size: 10px;">Maks <?= $spCp ?>%</div>
                                            <?php elseif ($spLv === 2): ?>
                                                <span class="badge text-white px-2 py-1" style="background: #ea580c; font-size: 11px; font-weight: 700;" title="<?= $spTooltip ?>">SP 2</span>
                                                <div class="small font-weight-bold" style="color: #ea580c; font-size: 10px;">Maks <?= $spCp ?>%</div>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1" style="font-size: 11px; font-weight: 700;" title="<?= $spTooltip ?>">SP 3</span>
                                                <div class="small font-weight-bold text-danger" style="font-size: 10px;">Maks <?= $spCp ?>%</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle" style="background: #f0fdf4;">
                                            <?php if ($lb->skor_akhir !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 17px; color: <?= $lb->grade_color ?>;"><?= number_format($lb->skor_akhir, 1) ?></div>
                                                <?php if ($lb->status_nilai === 'lengkap'): ?>
                                                    <div class="small font-weight-bold text-success" style="font-size: 10px;">
                                                        <i class="fas fa-check-circle text-success mr-1"></i>Mutu: <?= number_format($lb->skor_mutu, 1) ?> | Vol: <?= number_format($lb->skor_prod, 0) ?>%
                                                    </div>
                                                <?php elseif ($lb->status_nilai === 'parsial_no_eval'): ?>
                                                    <div class="small text-info font-weight-bold" style="font-size: 10px;">
                                                        <span class="badge badge-info py-0 px-1 mr-1" style="font-size: 9px; font-weight: 600;">Non-Eval</span>Mutu: <?= number_format($lb->skor_mutu, 1) ?> | Vol: <?= number_format($lb->skor_prod, 0) ?>%
                                                    </div>
                                                <?php else: ?>
                                                    <div class="small text-muted" style="font-size: 10px;">
                                                        <span class="badge badge-warning text-dark py-0 px-1 mr-1" style="font-size: 9px; font-weight: 600;">Parsial</span>Mutu: <?= number_format($lb->skor_mutu, 1) ?> | Vol: <?= number_format($lb->skor_prod, 0) ?>%
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($lb->is_capped_sp)): ?>
                                                    <div class="small text-danger font-weight-bold" style="font-size: 9.5px;">
                                                        Asli <?= number_format($lb->skor_akhir_raw, 1) ?> &rarr; Cap <?= htmlspecialchars($lb->sp_label) ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($lb->skor_akhir !== null): ?>
                                                <div><?= renderStarsHtml($lb->rating_agregat) ?></div>
                                                <span class="font-weight-bold ml-1" style="font-size:12px; color:#d97706;"><?= number_format($lb->rating_agregat, 2) ?> / 5.0</span>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum dinilai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php if ($lb->grade !== '-'): ?>
                                                <span class="badge <?= $lb->grade_badge ?> px-2 py-1" style="font-size: 12px; font-weight: 700; border-radius: 6px;">
                                                    Grade <?= $lb->grade ?>
                                                </span>
                                                <div class="small font-weight-bold mt-1" style="font-size: 11px; color: <?= $lb->grade_color ?>;">
                                                    <?= htmlspecialchars($lb->grade_label) ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge badge-secondary px-2 py-1" style="font-size: 11px;">Belum Dinilai</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php $rank++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>

<!-- =========================================================================
     MODAL 1: BUAT TASK / PROYEK BARU (ATASAN -> KARYAWAN)
     ========================================================================= -->
<div class="modal fade" id="modalBuatTask" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formBuatTask">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div style="width: 42px; height: 42px; border-radius: 10px; background: #ecfdf5; border: 1.5px solid #a7f3d0; display: flex; align-items: center; justify-content: center; font-size: 19px; color: #059669; flex-shrink: 0; margin-right: 14px;">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" style="color: #0f172a; font-size: 17px; letter-spacing: -0.2px;">
                                Buat Penugasan Task / Proyek ke Karyawan
                            </h5>
                            <small class="text-muted d-block" style="font-size: 12px; margin-top: 2px;">
                                Delegasikan instruksi kerja baru atau ajukan task mandiri ke HRD
                            </small>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #64748b; font-size: 26px; font-weight: 300; opacity: 0.7; transition: opacity 0.2s; outline: none; margin-top: -6px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- SECTION 1: PILIH KARYAWAN PENERIMA TUGAS -->
                    <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                            <label class="form-label-custom mb-0 font-weight-bold" style="color: #0f172a; font-size: 12px;">
                                <span><i class="fas fa-user-friends text-success mr-1"></i> PILIH KARYAWAN PENERIMA TUGAS <span class="text-danger">*</span></span>
                            </label>
                            <?php if (!empty($penerimaList)): ?>
                                <button type="button" class="btn btn-xs btn-outline-success font-weight-bold shadow-none" id="btnSelectSupervisedEmployees" style="border-radius: 20px; padding: 3px 12px; font-size: 11px;">
                                    <i class="fas fa-check-double mr-1"></i> <?= $canAssignToAll ? 'Pilih Semua Pegawai' : 'Pilih Semua Bawahan' ?> (<?= count($penerimaList) ?>)
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if ($canAssignToAll): ?>
                            <small class="text-primary d-block mb-2 font-weight-semibold" style="font-size: 11.5px;">
                                <i class="fas fa-shield-alt mr-1"></i> Anda memiliki akses untuk memberikan task / proyek kepada seluruh pegawai perusahaan.
                            </small>
                        <?php else: ?>
                            <small class="text-muted d-block mb-2" style="font-size: 11.5px;">
                                <i class="fas fa-info-circle mr-1"></i> Hanya menampilkan karyawan yang berada di bawah supervisi / atasan langsung Anda.
                            </small>
                        <?php endif; ?>
                        <select name="id_penerima[]" id="select_penerima_task" class="form-control" multiple="multiple" required style="width: 100%;">
                            <?php foreach ($penerimaList as $bw):
                                $divLabel = !empty($bw->nama_divisi) ? $bw->nama_divisi : 'Umum';
                                $isSelf = ((int)$bw->pengguna_id === (int)$myId);
                            ?>
                                <option value="<?= (int)$bw->pengguna_id ?>" <?= $isSelf ? 'data-self="1"' : '' ?>>
                                    <?= $isSelf ? "[Diri Sendiri] " : "" ?><?= htmlspecialchars($bw->nama) ?> <?= !empty($bw->jabatan) ? "(" . htmlspecialchars($bw->jabatan) . ")" : "" ?> - [<?= htmlspecialchars($divLabel) ?>]<?= $isSelf ? " (Pengajuan Mandiri)" : "" ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="alert_task_mandiri" class="alert alert-warning py-2 px-3 mt-2 mb-0" style="display: none; font-size: 12px; border-left: 4px solid #f59e0b; background: #fffbeb; color: #92400e; border-radius: 6px;">
                            <i class="fas fa-shield-alt text-warning mr-1"></i>
                            <strong>Pengajuan Task Mandiri:</strong> Tugas ini diajukan untuk diri Anda sendiri. Sesuai ketentuan, tugas akan dikirimkan ke <strong>HRD</strong> untuk di-<strong>ACC</strong> terlebih dahulu sebelum Anda dapat mulai mengerjakan.
                        </div>
                        <?php if (empty($penerimaList)): ?>
                            <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small" style="border-radius: 6px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Belum ada karyawan yang dibawahi:</strong> Anda hanya dapat memberikan tugas kepada karyawan yang dibawahi langsung oleh Anda. Belum ada karyawan yang memilih Anda sebagai <em>Atasan Langsung</em>. Karyawan dapat memilih Anda di menu <strong>Profil Karyawan</strong> mereka, atau Admin/HRD dapat mengaturnya di menu <strong>Data Karyawan</strong>.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Container Dropdown Jobdesk Sasaran Mutu (Hanya muncul jika 1 orang karyawan dipilih) -->
                    <div id="container_jobdesk_karyawan" style="display: none; background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-1" style="gap: 8px;">
                            <label class="font-weight-bold text-success mb-0" style="font-size: 12.5px;">
                                <i class="fas fa-bullseye text-success mr-1"></i> Hubungkan ke Jobdesk (Sama dengan Laporan Mingguan) <span class="text-danger">* (Wajib)</span>
                            </label>
                            <span class="badge badge-success px-2 py-1" id="badge_info_karyawan" style="font-size: 11px; border-radius: 12px;">
                                <i class="fas fa-user mr-1"></i> <span id="nama_karyawan_terpilih">-</span>
                            </span>
                        </div>
                        <small class="text-muted d-block mb-2" style="font-size: 11.5px;">
                            <i class="fas fa-sync-alt text-success mr-1"></i> Penugasan ini akan otomatis terhubung ke jobdesk karyawan terkait dan disinkronkan ke <strong>Sasaran Mutu</strong> serta <strong>Audit Kinerja</strong>.
                        </small>

                        <!-- Input Hidden untuk form submission -->
                        <input type="hidden" name="id_sasaran_mutu" id="select_jobdesk_karyawan" value="">
                        <input type="hidden" name="nama_jobdesk" id="input_nama_jobdesk" value="">

                        <!-- Custom Searchable Dropdown Picker -->
                        <div class="dropdown" id="dropdown_jobdesk_container">
                            <button type="button" class="btn btn-default" id="btn_toggle_jobdesk">
                                <span id="label_selected_jobdesk" style="font-size: 13px; color: #64748b; line-height: 1.45; word-break: break-word; text-align: left;">-- Pilih Jobdesk Sasaran Mutu Terkait (Wajib) --</span>
                                <i class="fas fa-chevron-down text-muted" style="font-size: 12px; flex-shrink: 0;"></i>
                            </button>

                            <div id="menu_jobdesk_list">
                                <!-- Search Box -->
                                <div class="p-2 border-bottom" style="background: #f8fafc;">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                                        </div>
                                        <input type="text" id="filter_jobdesk_search" class="form-control border-left-0" placeholder="Ketik kata kunci jobdesk...">
                                    </div>
                                </div>

                                <!-- Loading Indicator -->
                                <div id="jobdesk_loading" class="p-3 text-center text-muted small" style="display: none;">
                                    <i class="fas fa-spinner fa-spin text-success mr-1"></i> Memuat daftar jobdesk resmi karyawan...
                                </div>

                                <!-- Items List -->
                                <div id="container_jobdesk_items" style="max-height: 290px; overflow-y: auto;">
                                    <!-- Dynamic items -->
                                </div>
                            </div>
                        </div>

                        <div id="jobdesk_detail_preview" class="mt-2 small text-dark p-3 bg-white rounded border border-success" style="display: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 8px;">
                            <div class="font-weight-bold text-success mb-1" style="font-size: 13px;">
                                <i class="fas fa-check-circle mr-1"></i> <span id="text_jobdesk_title">-</span>
                            </div>
                            <div id="text_jobdesk_detail" class="mt-1" style="white-space: pre-line; line-height: 1.45; color: #334155; font-size: 12px;">-</div>
                        </div>
                    </div>

                    <!-- ROW: NAMA PROYEK, JENIS PENUGASAN, PRIORITAS -->
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="fas fa-folder text-muted mr-1"></i> Nama Proyek / Kategori</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                    </div>
                                    <input type="text" name="nama_proyek" class="form-control" placeholder="Contoh: Proyek CCTV / Operasional IT">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="fas fa-tags text-muted mr-1"></i> Jenis Penugasan</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                    </div>
                                    <select name="jenis" class="form-control">
                                        <option value="Task Harian">Task Harian</option>
                                        <option value="Proyek Mingguan">Proyek Mingguan</option>
                                        <option value="Proyek Bulanan / Khusus">Proyek Bulanan / Khusus</option>
                                        <option value="Urgent / Insidental">Urgent / Insidental</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="fas fa-flag text-muted mr-1"></i> Prioritas</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signal"></i></span>
                                    </div>
                                    <select name="prioritas" class="form-control">
                                        <option value="Low">Low (Rendah)</option>
                                        <option value="Medium" selected>Medium (Normal)</option>
                                        <option value="High">High (Tinggi)</option>
                                        <option value="Urgent">Urgent (Mendesak)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ROW: JUDUL TASK -->
                    <div class="form-group mb-3">
                        <label class="form-label-custom">
                            <span><i class="fas fa-pencil-alt text-success mr-1"></i> Judul Task / Instruksi Utama <span class="text-danger">*</span></span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-heading"></i></span>
                            </div>
                            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Penarikan Kabel Jaringan & Konfigurasi Router Lantai 2" style="font-weight: 500;">
                        </div>
                    </div>

                    <!-- ROW: TANGGAL MULAI & TARGET SELESAI -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="far fa-calendar-alt text-muted mr-1"></i> Tanggal Mulai</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" name="tgl_mulai" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span class="text-danger"><i class="far fa-calendar-check mr-1"></i> Target Selesai (Deadline) *</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-check text-danger"></i></span>
                                    </div>
                                    <input type="date" name="tgl_deadline" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ROW: DESKRIPSI -->
                    <div class="form-group mb-3">
                        <label class="form-label-custom">
                            <span><i class="fas fa-align-left text-muted mr-1"></i> Deskripsi / Rincian Instruksi Kerja</span>
                        </label>
                        <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tuliskan rincian target, spesifikasi hasil yang diharapkan, atau langkah kerja..."></textarea>
                    </div>

                    <!-- ROW: MENGETAHUI (PIHAK TERKAIT / ATASAN) -->
                    <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label-custom mb-0 font-weight-bold" style="color: #0f172a; font-size: 12px;">
                                <span><i class="fas fa-eye text-info mr-1"></i> Mengetahui (Pihak Terkait / Atasan)</span>
                            </label>
                            <span class="badge badge-light border text-muted" style="font-size: 11px; border-radius: 6px;">Opsional</span>
                        </div>
                        <select name="id_mengetahui" id="select_mengetahui_task" class="form-control" style="width: 100%;">
                            <option value="">-- Pilih Karyawan / Atasan yang Mengetahui (Opsional) --</option>
                            <?php foreach ($list_semua_karyawan_all as $kw):
                                if ((int)$kw->pengguna_id === (int)$myId) continue;
                            ?>
                                <option value="<?= (int)$kw->pengguna_id ?>">
                                    <?= htmlspecialchars($kw->nama) ?> <?= !empty($kw->jabatan) ? '(' . htmlspecialchars($kw->jabatan) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-2" style="font-size: 11.5px; line-height: 1.4;">
                            <i class="fas fa-bell text-warning mr-1"></i> Pihak yang dipilih di kolom Mengetahui <strong>hanya akan dikirimkan notifikasi WhatsApp setelah task disetujui (ACC) oleh HRD</strong>.
                        </small>
                    </div>

                    <!-- ROW: LINK LAMPIRAN / REFERENSI -->
                    <div class="form-group mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label-custom mb-0">
                                <span><i class="fas fa-paperclip text-muted mr-1"></i> Link Lampiran / Referensi dari Atasan</span>
                            </label>
                            <span class="badge badge-light border text-muted" style="font-size: 11px; border-radius: 6px;">Opsional</span>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="text" name="lampiran_atasan" class="form-control" placeholder="https://drive.google.com/... (Link dokumen, desain, atau referensi)">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">
                            <i class="fas fa-info-circle text-primary mr-1"></i> Catatan: <strong>Checklist langkah kerja / sub-task</strong> akan diisi dan dicentang langsung oleh <strong>Karyawan Penerima Tugas</strong> sebagai dasar perhitungan progres.
                        </small>
                    </div>

                    <!-- ROW: NOTIFIKASI WHATSAPP -->
                    <div class="p-3 rounded mb-1 d-flex align-items-center" style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 10px;">
                        <input type="checkbox" id="chk_kirim_wa" name="kirim_wa" value="1" checked style="width: 18px; height: 18px; accent-color: #10b981; cursor: pointer; margin-right: 12px;">
                        <label for="chk_kirim_wa" class="font-weight-bold text-success mb-0" style="cursor: pointer; font-size: 13px; user-select: none;">
                            <i class="fab fa-whatsapp mr-1" style="font-size: 15px;"></i> Kirim Notifikasi WhatsApp Otomatis ke Karyawan
                        </label>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light px-4 py-2 font-weight-bold" data-dismiss="modal" style="border-radius: 8px; border: 1.5px solid #cbd5e1; color: #475569; background: #ffffff;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btnSubmitBuatTask" <?= empty($penerimaList) ? 'disabled title="Tidak ada karyawan yang dapat dipilih"' : '' ?> style="border-radius: 8px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 13.5px; letter-spacing: 0.2px;">
                        <i class="fas fa-paper-plane mr-1"></i> Simpan & Tugaskan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL 2: DETAIL TASK, UPDATE PROGRES, SUBMIT SELESAI & REVIEW BINTANG 5
     ========================================================================= -->
<div class="modal fade" id="modalDetailTask" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center" style="min-width: 0; flex: 1;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #eff6ff; border: 1.5px solid #bfdbfe; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #2563eb; flex-shrink: 0; margin-right: 14px;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div style="min-width: 0;">
                        <h5 class="modal-title font-weight-bold mb-0" style="color: #0f172a; font-size: 17px; letter-spacing: -0.2px; word-break: break-word;">
                            Detail Task: <span id="dt_kode_task" class="text-primary">-</span>
                        </h5>
                        <small class="text-muted d-block" style="font-size: 12px; margin-top: 2px;">
                            Monitoring progres pekerjaan, verifikasi checklist & submit review
                        </small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #64748b; font-size: 26px; font-weight: 300; opacity: 0.7; transition: opacity 0.2s; outline: none; margin-top: -6px; flex-shrink: 0;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- INFO RINGKAS TASK -->
                <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; overflow-wrap: break-word; word-break: break-word;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 12px;">
                        <div style="flex: 1 1 300px; min-width: 0;">
                            <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                                <span class="badge badge-info" id="dt_nama_proyek" style="white-space: normal; text-align: left;">Proyek</span>
                                <span class="badge badge-secondary" id="dt_jenis">Jenis</span>
                                <span id="dt_prioritas_badge"></span>
                            </div>
                            <div id="dt_jobdesk_badge" style="display:none;"></div>
                            <h4 class="font-weight-bold mt-2 mb-1" id="dt_judul" style="color:#0f172a; white-space: normal; word-break: break-word; overflow-wrap: anywhere; line-height: 1.35; font-size: 18px;">-</h4>
                        </div>
                        <div class="text-md-right" style="flex-shrink: 0;">
                            <div id="dt_status_badge"></div>
                            <div class="small text-muted mt-1">Deadline: <strong id="dt_deadline">-</strong></div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row small">
                        <div class="col-md-6 mb-1 mb-md-0">
                            <strong>Pemberi Tugas / Pengaju:</strong> <span id="dt_pemberi">-</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Pelaksana (Karyawan):</strong> <span id="dt_penerima">-</span>
                        </div>
                    </div>
                    <div class="row small mt-1">
                        <div class="col-md-6 mb-1 mb-md-0">
                            <strong><i class="fas fa-eye text-primary mr-1"></i> Mengetahui:</strong> <span id="dt_mengetahui">-</span>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-check-double text-success mr-1"></i> Status ACC HRD:</strong> <span id="dt_acc_hrd_info">-</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <strong class="small text-uppercase text-muted">Instruksi / Deskripsi Pekerjaan:</strong>
                        <div id="dt_deskripsi" class="p-2 mt-1 rounded bg-white border" style="white-space: pre-line; word-break: break-word; overflow-wrap: anywhere; font-size: 13px;">-</div>
                    </div>
                    <div id="dt_lampiran_atasan_wrap" class="mt-2" style="display:none;">
                        <a href="#" id="dt_lampiran_atasan_link" target="_blank" class="btn btn-xs btn-outline-primary">
                            <i class="fas fa-paperclip"></i> Buka Lampiran Referensi Atasan
                        </a>
                    </div>
                    <div id="dt_revisi_wrap" class="alert alert-danger mt-2 mb-0 py-2" style="display:none;">
                        <strong><i class="fas fa-redo"></i> Catatan Permintaan Revisi dari Atasan:</strong>
                        <div id="dt_catatan_revisi"></div>
                    </div>
                    <div id="dt_sp_alert_wrap" class="mt-2" style="display:none;"></div>
                    <div id="dt_readonly_checklist_wrap" class="mt-2 p-2 rounded bg-white border" style="display:none;"></div>
                </div>

                <!-- HASIL PENILAIAN BINTANG 5 (TAMPIL JIKA SUDAH DINILAI) -->
                                <!-- KOTAK PERSETUJUAN ACC HRD (HANYA MUNCUL JIKA STATUS MENUNGGU ACC HRD) -->
                <div id="dt_hrd_acc_box" class="p-3 mb-3 rounded" style="display: none; background: #fffbeb; border: 2px solid #f59e0b;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 14px;"><i class="fas fa-hourglass-start text-warning mr-1"></i> Pengajuan Task Mandiri (Menunggu ACC HRD)</div>
                            <div class="small text-muted mt-1" id="dt_hrd_acc_desc">Task ini diajukan secara mandiri oleh karyawan dan memerlukan persetujuan (ACC) HRD sebelum pengerjaan dapat dimulai.</div>
                        </div>
                        <div id="dt_hrd_acc_actions" style="display: none; gap: 6px;">
                            <button type="button" class="btn btn-sm btn-success font-weight-bold btn-modal-acc-hrd" id="btn_modal_acc_hrd">
                                <i class="fas fa-check-circle mr-1"></i> ACC / Setujui Task
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold btn-modal-tolak-hrd" id="btn_modal_tolak_hrd">
                                <i class="fas fa-times-circle mr-1"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>

                <div id="dt_hasil_rating_box" class="p-3 mb-3 rounded" style="display:none; background:#fffbeb; border: 2px solid #f59e0b;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="text-uppercase small font-weight-bold" style="color:#b45309;">🌟 Hasil Penilaian Atasan</div>
                            <div id="dt_rating_stars_html" style="font-size: 20px;" class="my-1"></div>
                            <div id="dt_ulasan_atasan_text" class="font-italic text-dark"></div>
                        </div>
                        <div class="text-right small text-muted" id="dt_tgl_dinilai"></div>
                    </div>
                </div>

                <!-- PANEL 1: CHECKLIST & UPDATE PROGRES (DIISI OLEH PELAKSANA / KARYAWAN PENERIMA TASK) -->
                <div id="panelBawahanProgres" class="card mb-3 border-primary">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center flex-wrap">
                        <strong class="text-primary"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Saya & Update Progres</strong>
                        <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" id="btnAddBawahanChecklist">
                            <i class="fas fa-plus mr-1"></i> Tambah Item Checklist
                        </button>
                    </div>
                    <div class="card-body py-3">
                        <form id="formUpdateProgres">
                            <input type="hidden" name="id_task" id="up_id_task">
                            <input type="hidden" name="auto_calc_checklist" id="up_auto_calc" value="1">

                            <div class="small text-muted mb-2">
                                Tambahkan langkah-langkah kerja (sub-task) Anda di bawah ini, lalu centang item yang sudah dikerjakan untuk menghitung persentase progres secara otomatis:
                            </div>
                            <div id="dt_checklist_container" class="mb-3"></div>

                            <div class="form-group mb-2">
                                <label class="font-weight-bold d-flex justify-content-between">
                                    <span>Persentase Progres Pengerjaan:</span>
                                    <span class="badge badge-primary" style="font-size:14px;"><span id="up_progres_label">0</span>%</span>
                                </label>
                                <input type="range" name="progres" id="up_progres_slider" class="form-control-range" min="0" max="100" step="5" value="0">
                            </div>

                            <div class="form-group mb-2">
                                <input type="text" name="catatan" id="up_catatan" class="form-control form-control-sm" placeholder="Catatan update progres (opsional, misal: Sedang proses instalasi tahap 1)...">
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-sm btn-primary font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Simpan Update Progres
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PANEL 2: SUBMIT LAPORAN SELESAI 100% KE ATASAN -->
                <div id="panelBawahanSubmit" class="card mb-3 border-success">
                    <div class="card-header bg-light py-2">
                        <strong class="text-success"><i class="fas fa-check-circle mr-1"></i> Laporkan Pekerjaan Selesai (100%) ke Atasan</strong>
                    </div>
                    <div class="card-body py-3">
                        <form id="formSubmitSelesai">
                            <input type="hidden" name="id_task" id="sub_id_task">
                            <div class="form-group mb-2">
                                <label class="font-weight-bold small">Ringkasan Hasil Pekerjaan / Penyelesaian <span class="text-danger">*</span></label>
                                <textarea name="catatan_bawahan" id="sub_catatan_bawahan" rows="2" class="form-control" required placeholder="Jelaskan hasil pekerjaan yang telah diselesaikan..."></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label class="font-weight-bold small">Link Bukti Selesai / Foto / Google Drive (Opsional)</label>
                                <input type="text" name="bukti_selesai" id="sub_bukti_selesai" class="form-control form-control-sm" placeholder="https://drive.google.com/...">
                            </div>
                            <div class="text-right">
                                <button type="submit" id="btnSubmitSelesai" class="btn btn-sm btn-success font-weight-bold">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Hasil Pekerjaan & Minta Rating ⭐
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- NOTIFIKASI VALIDASI SUDAH TERKIRIM (MENUNGGU PENILAIAN) -->
                <div id="panelSudahDisubmitInfo" class="alert alert-success py-3 px-3 mb-3" style="display:none; border-left: 4px solid #10b981; background: #ecfdf5;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hourglass-half fa-2x text-success mr-3"></i>
                        <div>
                            <h6 class="mb-1 font-weight-bold text-success"><i class="fas fa-check-circle mr-1"></i> Hasil Pekerjaan Sudah Terkirim!</h6>
                            <div class="small text-muted">Laporan penyelesaian tugas ini telah berhasil diserahkan (100%) dan saat ini sedang <strong>Menunggu Penilaian / Rating Bintang ⭐ dari HRD / Manajemen / Atasan</strong>.</div>
                            <div class="small text-muted mt-1" style="font-size: 11.5px;"><i class="fas fa-shield-alt text-warning mr-1"></i> Sesuai standar penilaian objektif perusahaan, tugas yang diajukan ke diri sendiri dinilai langsung oleh HRD dan Manajemen.</div>
                        </div>
                    </div>
                </div>

                <!-- PANEL 3: FORM PENILAIAN RATING BINTANG 5 & REVIEW ATASAN -->
                <div id="panelAtasanReview" class="card mb-3" style="border: 2px solid #f59e0b; display:none;">
                    <div class="card-header py-2" style="background: #fef3c7;">
                        <strong style="color:#b45309;"><i class="fas fa-star mr-1"></i> Form Evaluasi & Penilaian Bintang 5 (Khusus Atasan / HRD / Manajemen)</strong>
                    </div>
                    <div class="card-body py-3">
                        <form id="formReviewAtasan">
                            <input type="hidden" name="id_task" id="rev_id_task">
                            <input type="hidden" name="rating_bintang" id="rev_rating_bintang" value="5">

                            <div class="form-group mb-2">
                                <label class="font-weight-bold">Keputusan Review Atasan:</label>
                                <div class="mt-1">
                                    <label class="mr-3" style="cursor:pointer;">
                                        <input type="radio" name="keputusan" value="terima" checked>
                                        <span class="badge badge-success p-1"><i class="fas fa-check"></i> Setujui Selesai & Beri Rating Bintang ⭐</span>
                                    </label>
                                    <label style="cursor:pointer;">
                                        <input type="radio" name="keputusan" value="revisi">
                                        <span class="badge badge-danger p-1"><i class="fas fa-redo"></i> Kembalikan untuk Revisi</span>
                                    </label>
                                </div>
                            </div>

                            <!-- PILIH BINTANG 1 - 5 INTERAKTIF -->
                            <div id="wrapStarPicker" class="p-3 mb-3 rounded text-center" style="background:#fffbeb; border:1px dashed #f59e0b;">
                                <div id="wrapOverdueMaxStarAlert" class="alert alert-danger py-2 px-3 mb-2 text-left small" style="display:none;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <strong>Melewati Batas Deadline (<span id="lblOverdueDays">0</span> hari):</strong> Sesuai aturan kedisiplinan, task/proyek yang selesai melewati tanggal deadline <strong>maksimal mendapatkan Rating Bintang 1 (⭐ 1/5)</strong>.
                                </div>
                                <div class="small font-weight-bold text-uppercase mb-1" style="color:#b45309;">Klik Bintang untuk Memberikan Nilai Kualitas Kerja Karyawan:</div>
                                <div class="star-rating-picker my-1" id="starPickerWidget">
                                    <i class="fas fa-star star-btn active" data-val="1" title="1 Bintang - Kurang Memuaskan"></i>
                                    <i class="fas fa-star star-btn active" data-val="2" title="2 Bintang - Perlu Banyak Perbaikan"></i>
                                    <i class="fas fa-star star-btn active" data-val="3" title="3 Bintang - Cukup Baik / Sesuai Standar"></i>
                                    <i class="fas fa-star star-btn active" data-val="4" title="4 Bintang - Sangat Baik & Rapi"></i>
                                    <i class="fas fa-star star-btn active" data-val="5" title="5 Bintang - Istimewa / Sempurna & Cepat!"></i>
                                </div>
                                <div id="starPickerLabel" class="font-weight-bold mt-1" style="color:#d97706; font-size:14px;">
                                    ⭐⭐⭐⭐⭐ (5 / 5 — Istimewa / Sangat Memuaskan!)
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="font-weight-bold small">Ulasan Apresiasi / Catatan Evaluasi Atasan:</label>
                                <div class="mb-1">
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-ulasan" data-text="Kerja bagus, selesai tepat waktu dan hasil sangat rapi!">👍 Kerja bagus & tepat waktu</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-ulasan" data-text="Eksekusi sangat cepat, komunikasi dan laporan sangat jelas. Pertahankan!">🌟 Eksekusi cepat & jelas</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-ulasan" data-text="Pekerjaan sudah selesai sesuai instruksi, namun ke depannya mohon lebih teliti.">📝 Sesuai standar, tingkatkan ketelitian</button>
                                </div>
                                <textarea name="ulasan_atasan" id="rev_ulasan_atasan" rows="2" class="form-control" placeholder="Tulis komentar penilaian atau catatan revisi untuk karyawan..."></textarea>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-warning font-weight-bold" id="btnSubmitReviewAtasan">
                                    <i class="fas fa-award mr-1"></i> Simpan Penilaian & Kirim WA ke Karyawan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- RIWAYAT LOG AKTIVITAS TASK -->
                <div>
                    <h6 class="font-weight-bold text-muted text-uppercase mb-2"><i class="fas fa-history mr-1"></i> Riwayat Aktivitas & Log Progres</h6>
                    <div id="dt_logs_container" style="max-height: 220px; overflow-y: auto;"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 3: ATUR ATASAN LANGSUNG SAYA -->
<div class="modal fade" id="modalAturAtasanSaya" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formAturAtasanSaya">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-user-shield mr-1"></i> Pilih Atasan Langsung Anda
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">
                        Tentukan siapa <strong>Atasan Langsung / Supervisor</strong> yang membawahi Anda:
                    </p>
                    <div class="form-group">
                        <label class="font-weight-bold">Pilih Atasan Langsung</label>
                        <select id="select_atasan_saya_tp" name="id_atasan" class="form-control">
                            <option value="">-- Belum Ditentukan / Tidak Ada --</option>
                            <?php foreach ($list_semua_karyawan as $atOpt):
                                if ((int)$atOpt->pengguna_id === (int)$myId) continue;
                            ?>
                                <option value="<?= (int)$atOpt->pengguna_id ?>" <?= ((int)($me->id_atasan ?? 0) === (int)$atOpt->pengguna_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($atOpt->nama) ?><?= !empty($atOpt->jabatan) ? ' (' . htmlspecialchars($atOpt->jabatan) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="btnSimpanAtasanSaya">
                        <i class="fas fa-save mr-1"></i> Simpan Atasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 4: JADWALKAN PERTEMUAN HRD (SAAT TASK KENA SP 3) -->
<div class="modal fade" id="modalJadwalHrdSp3" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formJadwalHrdSp3">
                <input type="hidden" name="id_task" id="jhrd_id_task">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-gavel mr-1"></i> Penjadwalan Pertemuan HRD (Tindak Lanjut SP 3)
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger py-2 px-3 mb-3 small">
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Peringatan Eskalasi SP 3:</strong><br>
                        Task <strong id="jhrd_kode_task">-</strong> (<span id="jhrd_judul_task">-</span>) untuk karyawan <strong id="jhrd_nama_karyawan">-</strong> telah melewati deadline selama <strong id="jhrd_hari_telat">21</strong> hari (&ge; 3 minggu). Silakan atur jadwal pertemuan evaluasi dengan HRD.
                    </div>
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold small">Tanggal Pertemuan HRD <span class="text-danger">*</span></label>
                            <input type="date" name="hrd_jadwal_tgl" id="jhrd_tgl" class="form-control" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                        <div class="col-md-5 form-group">
                            <label class="font-weight-bold small">Jam Pertemuan <span class="text-danger">*</span></label>
                            <input type="time" name="hrd_jadwal_jam" id="jhrd_jam" class="form-control" required value="10:00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small">Metode Pertemuan</label>
                            <select name="hrd_jadwal_metode" id="jhrd_metode" class="form-control">
                                <option value="Tatap Muka (Offline)">Tatap Muka (Offline)</option>
                                <option value="Online (Zoom / Google Meet)">Online (Zoom / Google Meet)</option>
                                <option value="Telepon / WhatsApp Call">Telepon / WhatsApp Call</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small">Lokasi / Link Meeting <span class="text-danger">*</span></label>
                            <input type="text" name="hrd_jadwal_lokasi" id="jhrd_lokasi" class="form-control" required placeholder="Ruang HRD / Link Meet" value="Ruang HRD">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small">Agenda / Catatan Pemanggilan HRD</label>
                        <textarea name="hrd_jadwal_catatan" id="jhrd_catatan" rows="2" class="form-control" placeholder="Evaluasi keterlambatan penyelesaian task proyek & pembinaan disiplin SP 3..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger font-weight-bold" id="btnSubmitJadwalHrdSp3">
                        <i class="fas fa-calendar-check mr-1"></i> Simpan Jadwal & Kirim Notifikasi WA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var $ = window.jQuery;
    if (!$) return;

    var baseUrl = '<?= base_url() ?>';
    var csrfName = '<?= $this->security->get_csrf_token_name() ?>';

    function getCsrfHash() {
        return $('input[name="token"]').val() || '<?= $this->security->get_csrf_hash() ?>';
    }

    // Inisialisasi DataTables
    if ($.fn.DataTable) {
        $('#datatable_tugas_saya').DataTable({ pageLength: 10, order: [] });
        $('#datatable_tugas_atasan').DataTable({ pageLength: 10, order: [] });
        $('#datatable_leaderboard').DataTable({ pageLength: 15, order: [] });
    }

    // Inisialisasi Select2 pada pilihan karyawan
    if ($.fn.select2) {
        $('#select_penerima_task').select2({
            dropdownParent: $('#modalBuatTask'),
            placeholder: 'Pilih karyawan penerima tugas (bisa lebih dari 1)...',
            allowClear: true,
            width: '100%'
        });
    }

    // Tombol Pilih Semua Karyawan yang Dibawahi Langsung
    $('#btnSelectSupervisedEmployees').on('click', function() {
        var allIds = [];
        $('#select_penerima_task option').each(function() {
            allIds.push($(this).val());
        });
        $('#select_penerima_task').val(allIds).trigger('change');
    });

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Toggle Menu Dropdown Jobdesk
    $('#btn_toggle_jobdesk').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#menu_jobdesk_list').toggle();
        if ($('#menu_jobdesk_list').is(':visible')) {
            $('#filter_jobdesk_search').val('').trigger('input').focus();
        }
    });

    // Cegah dropdown tertutup saat mengklik/mengetik di search box
    $('#filter_jobdesk_search').on('click keydown keyup input', function(e) {
        e.stopPropagation();
        var q = $(this).val().toLowerCase().trim();
        $('#container_jobdesk_items .jobdesk-choice-item').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(q) !== -1);
        });
        $('#container_jobdesk_items .jobdesk-group-header').each(function() {
            var $groupItems = $(this).nextUntil('.jobdesk-group-header', '.jobdesk-choice-item:visible');
            $(this).toggle($groupItems.length > 0);
        });
    });

    // Tutup dropdown saat klik di luar
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#dropdown_jobdesk_container').length) {
            $('#menu_jobdesk_list').hide();
        }
    });

    // Saat salah satu opsi jobdesk diklik
    $(document).on('click', '.jobdesk-choice-item', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var detail = $(this).data('detail') || '';
        var sasaran = $(this).data('sasaran') || '';

        $('#select_jobdesk_karyawan').val(id);
        $('#input_nama_jobdesk').val(nama);

        $('#label_selected_jobdesk')
            .html('<strong class="text-success mr-1"><i class="fas fa-check-circle"></i> Terpilih:</strong> ' + escapeHtml(nama))
            .css('color', '#0f172a');

        if (nama) {
            $('#text_jobdesk_title').text(nama);
            var detailText = '';
            if (detail) detailText += '• Detail Proyek: ' + detail;
            if (sasaran) detailText += (detailText ? '\n' : '') + '• Sasaran Mutu: ' + sasaran;
            $('#text_jobdesk_detail').text(detailText || '(Tidak ada sasaran mutu khusus)');
            $('#jobdesk_detail_preview').slideDown(150);
        } else {
            $('#jobdesk_detail_preview').hide();
        }

        $('#menu_jobdesk_list').hide();
    });

    // Handle Dropdown Jobdesk Sasaran Mutu berdasarkan penerima tugas
    $('#select_penerima_task').on('change', function() {
        var selected = $(this).val() || [];
        if (selected.length === 1) {
            var empId = selected[0];
            $('#container_jobdesk_karyawan').slideDown(200);
            loadJobdeskKaryawan(empId);
        } else {
            $('#container_jobdesk_karyawan').slideUp(200);
            $('#select_jobdesk_karyawan').val('');
            $('#input_nama_jobdesk').val('');
            $('#label_selected_jobdesk').text('-- Pilih Jobdesk Sasaran Mutu Terkait (Wajib) --').css('color', '#64748b');
            $('#jobdesk_detail_preview').hide();
            $('#menu_jobdesk_list').hide();
        }
    });

    function loadJobdeskKaryawan(empId) {
        $('#jobdesk_loading').show();
        $('#container_jobdesk_items').empty();
        $('#jobdesk_detail_preview').hide();
        $('#select_jobdesk_karyawan').val('');
        $('#input_nama_jobdesk').val('');
        $('#label_selected_jobdesk').text('-- Memuat daftar jobdesk... --').css('color', '#94a3b8');

        $.ajax({
            url: baseUrl + 'task_proyek/get_karyawan_jobdesks',
            type: 'GET',
            data: { id_pengguna: empId },
            dataType: 'json',
            success: function(res) {
                $('#jobdesk_loading').hide();

                try {
                    if (res && res.status === 'success') {
                        if (res.karyawan) {
                            var kInfo = res.karyawan.nama + ' (' + (res.karyawan.posisi_matched || res.karyawan.jabatan || res.karyawan.divisi) + ')';
                            $('#nama_karyawan_terpilih').text(kInfo);
                        }

                        var itemsHtml = '';
                        if (res.jobdesks && res.jobdesks.length > 0) {
                            var currentGroup = '';
                            $.each(res.jobdesks, function(i, it) {
                                var groupTitle = it.posisi ? it.posisi : (it.divisi ? 'Divisi ' + it.divisi : 'Jobdesk Karyawan');
                                if (groupTitle !== currentGroup) {
                                    currentGroup = groupTitle;
                                    itemsHtml += '<div class="jobdesk-group-header"><i class="fas fa-briefcase mr-1"></i> Jobdesk Resmi: ' + escapeHtml(currentGroup) + '</div>';
                                }
                                itemsHtml += '<div class="jobdesk-choice-item" data-id="' + escapeHtml(it.id) + '" data-nama="' + escapeHtml(it.jobdesk) + '" data-detail="' + escapeHtml(it.detail_proyek || '') + '" data-sasaran="' + escapeHtml(it.sasaran_mutu || '') + '">' +
                                    '<strong class="text-success mr-1">' + (i + 1) + '.</strong> ' + escapeHtml(it.jobdesk) +
                                    '</div>';
                            });
                        } else {
                            itemsHtml += '<div class="jobdesk-choice-item" data-id="SM_CUSTOM_TASK" data-nama="Tugas & Tanggung Jawab Operasional Tambahan">' +
                                '<strong class="text-success mr-1">1.</strong> Tugas & Tanggung Jawab Operasional Tambahan' +
                                '</div>';
                        }
                        $('#container_jobdesk_items').html(itemsHtml);
                        $('#label_selected_jobdesk').text('-- Klik untuk Memilih Jobdesk Terkait (Wajib) --').css('color', '#64748b');
                    } else {
                        $('#container_jobdesk_items').html('<div class="p-3 text-muted small text-center">Tidak ada jobdesk khusus untuk posisi ini.</div>');
                        $('#label_selected_jobdesk').text('-- Tidak ada jobdesk --').css('color', '#ef4444');
                    }
                } catch (renderErr) {
                    console.error('Error rendering jobdesks:', renderErr);
                    $('#container_jobdesk_items').html('<div class="jobdesk-choice-item" data-id="SM_CUSTOM_TASK" data-nama="Tugas Operasional Karyawan"><strong class="text-success mr-1">1.</strong> Tugas Operasional Karyawan</div>');
                    $('#label_selected_jobdesk').text('-- Klik untuk Memilih Jobdesk Terkait --').css('color', '#64748b');
                }
            },
            error: function() {
                $('#jobdesk_loading').hide();
                $('#container_jobdesk_items').html('<div class="jobdesk-choice-item" data-id="SM_CUSTOM_TASK" data-nama="Tugas Operasional Karyawan"><strong class="text-success mr-1">1.</strong> Tugas Operasional Karyawan</div>');
                $('#label_selected_jobdesk').text('-- Klik untuk Memilih Jobdesk Terkait --').css('color', '#64748b');
            }
        });
    }



    // Simpan Atasan Langsung Saya dari halaman Task Proyek
    $('#formAturAtasanSaya').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSimpanAtasanSaya');
        var orig = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: baseUrl + 'pengguna/update/atasan',
            type: 'POST',
            dataType: 'json',
            data: {
                pengguna_id: '<?= (int)$myId ?>',
                id_atasan: $('#select_atasan_saya_tp').val(),
                [csrfName]: getCsrfHash()
            },
            success: function(res) {
                $btn.prop('disabled', false).html(orig);
                if (res && res.status === 'success') {
                    window.location.reload();
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menyimpan atasan.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(orig);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Buka Modal Buat Task
    $('#btnOpenModalBuatTask').on('click', function() {
        <?php if (!$canAssignToAll && empty($list_bawahan)): ?>
            if (window.swal) {
                swal('Perhatian', 'Anda belum memiliki karyawan yang dibawahi langsung, sehingga belum dapat memberikan tugas. Karyawan harus memilih Anda sebagai Atasan Langsung terlebih dahulu.', 'warning');
            } else {
                alert('Anda belum memiliki karyawan yang dibawahi langsung, sehingga belum dapat memberikan tugas. Karyawan harus memilih Anda sebagai Atasan Langsung terlebih dahulu.');
            }
            return;
        <?php endif; ?>
        $('#formBuatTask')[0].reset();
        $('#container_jobdesk_karyawan').hide();
        $('#select_jobdesk_karyawan').val('');
        $('#input_nama_jobdesk').val('');
        $('#label_selected_jobdesk').text('-- Pilih Jobdesk Sasaran Mutu Terkait (Wajib) --').css('color', '#64748b');
        $('#jobdesk_detail_preview').hide();
        $('#menu_jobdesk_list').hide();
        if ($.fn.select2) {
            $('#select_penerima_task').val(null).trigger('change');
        }
        $('#select_mengetahui_task').val('');
        $('#modalBuatTask').modal('show');
    });

    // Dynamic Checklist Builder
    $('#btnAddChecklistRow').on('click', function() {
        var count = $('#checklistBuilderContainer .checklist-input-row').length + 1;
        var html = '<div class="input-group input-group-sm mb-1 checklist-input-row">' +
            '<input type="text" name="checklist_items[]" class="form-control" placeholder="Item checklist ke-' + count + '...">' +
            '<div class="input-group-append">' +
            '<button type="button" class="btn btn-outline-danger btn-remove-checklist"><i class="fas fa-times"></i></button>' +
            '</div></div>';
        $('#checklistBuilderContainer').append(html);
    });

    $(document).on('click', '.btn-remove-checklist', function() {
        if ($('#checklistBuilderContainer .checklist-input-row').length > 1) {
            $(this).closest('.checklist-input-row').remove();
        } else {
            $(this).closest('.checklist-input-row').find('input').val('');
        }
    });

    // Submit Form Buat Task Baru
    $('#formBuatTask').on('submit', function(e) {
        e.preventDefault();

        var selectedPenerima = $('#select_penerima_task').val() || [];
        if (selectedPenerima.length === 1 && !$('#select_jobdesk_karyawan').val()) {
            if (window.swal) {
                swal('Perhatian', 'Jobdesk Sasaran Mutu wajib dipilih untuk penugasan karyawan ini!', 'warning');
            } else {
                alert('Jobdesk Sasaran Mutu wajib dipilih untuk penugasan karyawan ini!');
            }
            $('#menu_jobdesk_list').show();
            $('#filter_jobdesk_search').focus();
            return false;
        }

        var $btn = $('#btnSubmitBuatTask');
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/add',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    $('#modalBuatTask').modal('hide');
                    var nextUrl = baseUrl + 'task_proyek?tab=atasan<?= $switch === "kepegawaian" ? "&nav=kepegawaian" : "" ?>';
                    if (window.swal) {
                        swal('Berhasil!', res.msg, 'success').then(function() { window.location.href = nextUrl; });
                    } else {
                        alert(res.msg);
                        window.location.href = nextUrl;
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal membuat task.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan saat menyimpan task.');
            }
        });
    });

    // Helper render bintang di JS
    function buildStarsJs(rating) {
        var r = Math.round(parseFloat(rating) || 0);
        var out = '<span class="tp-star-display">';
        for (var i = 1; i <= 5; i++) {
            out += (i <= r) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star tp-star-empty"></i>';
        }
        out += '</span> <strong>(' + r + '/5)</strong>';
        return out;
    }

    // Buka Modal Detail Task
    $(document).on('click', '.btn-detail-task', function() {
        var idTask = $(this).data('id');
        var focusTarget = $(this).data('focus') || 'progres';

        $.ajax({
            url: baseUrl + 'task_proyek/get_detail',
            type: 'POST',
            data: { id_task: idTask, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                if (!res || res.status !== 'success') {
                    alert(res && res.msg ? res.msg : 'Gagal memuat detail task.');
                    return;
                }
                var t = res.task;
                var myId = parseInt(res.my_id, 10);
                var isPenerima = (parseInt(t.id_penerima, 10) === myId);

                // ATURAN MUTLAK: Pelaksana (penerima tugas) TIDAK BOLEH menilai tugas diri sendiri!
                // canReview hanya bernilai true jika user BUKAN penerima dan backend memberikan izin review
                var canReview = !isPenerima && (res.can_review !== undefined ? !!res.can_review : ((parseInt(t.id_pemberi, 10) === myId) || !!res.is_admin_reviewer));

                var stInt = parseInt(t.status_task, 10) || 0;
                if (stInt === 1 && (parseInt(t.progres, 10) >= 100 || !!t.tgl_submit)) {
                    stInt = 2;
                }
                var statusBadgeHtml = '';
                if (stInt === 5) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-5" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;"><i class="fas fa-hourglass-start"></i> Menunggu ACC HRD</span>';
                } else if (stInt === 6) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-6" style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca;"><i class="fas fa-times-circle"></i> Ditolak HRD</span>';
                } else if (stInt === 4) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-4"><i class="fas fa-check-circle"></i> Selesai & Dinilai</span>';
                } else if (stInt === 2) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-2"><i class="fas fa-hourglass-half"></i> Menunggu Penilaian</span>';
                } else if (stInt === 3) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-3"><i class="fas fa-redo"></i> Perlu Revisi</span>';
                } else if (stInt === 1) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-1"><i class="fas fa-spinner fa-spin"></i> Sedang Dikerjakan</span>';
                } else {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-0"><i class="far fa-clock"></i> Baru / Belum Mulai</span>';
                }
                $('#dt_status_badge').html(statusBadgeHtml);

                $('#dt_mengetahui').text(t.nama_mengetahui ? t.nama_mengetahui : '-');
                var accHrdText = '-';
                if (parseInt(t.status_task, 10) === 5) {
                    accHrdText = '<span class="badge badge-warning text-dark"><i class="fas fa-hourglass-start mr-1"></i> Menunggu ACC HRD</span>';
                } else if (parseInt(t.status_task, 10) === 6) {
                    accHrdText = '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Ditolak: ' + $('<div>').text(t.acc_hrd_catatan || '').html() + '</span>';
                } else if (t.nama_acc_hrd) {
                    accHrdText = '<span class="text-success"><i class="fas fa-check-circle mr-1"></i> Disetujui (' + $('<div>').text(t.nama_acc_hrd).html() + (t.acc_hrd_at_fmt ? ', ' + t.acc_hrd_at_fmt : '') + ')</span>';
                }
                $('#dt_acc_hrd_info').html(accHrdText);

                if (parseInt(t.status_task, 10) === 5) {
                    $('#dt_hrd_acc_box').show();
                    if (res.is_hrd_admin) {
                        $('#btn_modal_acc_hrd').data('id', t.id_task).data('kode', t.kode_task).data('judul', t.judul);
                        $('#btn_modal_tolak_hrd').data('id', t.id_task).data('kode', t.kode_task).data('judul', t.judul);
                        $('#dt_hrd_acc_actions').css('display', 'flex');
                    } else {
                        $('#dt_hrd_acc_actions').hide();
                    }
                } else {
                    $('#dt_hrd_acc_box').hide();
                }

                $('#dt_kode_task').text(t.kode_task);
                $('#dt_nama_proyek').text(t.nama_proyek || 'Umum');
                $('#dt_jenis').text(t.jenis || 'Task Harian');
                $('#dt_prioritas_badge').html('<span class="tp-priority-badge tp-prio-' + t.prioritas + '">' + t.prioritas + '</span>');
                if (t.nama_jobdesk) {
                    $('#dt_jobdesk_badge').html('<div class="d-flex align-items-start mt-2 px-2 py-1 rounded" style="background:#dcfce7; border:1px solid #86efac; color:#166534; font-size:12px; font-weight:600; line-height:1.45; white-space:normal; word-break:break-word; max-width:100%;"><i class="fas fa-bullseye mr-2 mt-1 flex-shrink-0"></i><span><strong>Jobdesk:</strong> ' + $('<div>').text(t.nama_jobdesk).html() + '</span></div>').show();
                } else {
                    $('#dt_jobdesk_badge').hide().empty();
                }
                $('#dt_judul').text(t.judul);
                $('#dt_deadline').text(t.tgl_deadline_fmt + (t.is_overdue ? ' (OVERDUE)' : ''));
                $('#dt_pemberi').text((t.nama_pemberi || '-') + (t.jabatan_pemberi ? ' (' + t.jabatan_pemberi + ')' : ''));
                $('#dt_penerima').text((t.nama_penerima || '-') + (t.jabatan_penerima ? ' (' + t.jabatan_penerima + ')' : ''));
                $('#dt_deskripsi').text(t.deskripsi || 'Tidak ada deskripsi tambahan.');

                if (t.lampiran_atasan) {
                    $('#dt_lampiran_atasan_link').attr('href', t.lampiran_atasan);
                    $('#dt_lampiran_atasan_wrap').show();
                } else {
                    $('#dt_lampiran_atasan_wrap').hide();
                }

                if (parseInt(t.status_task, 10) === 3 && t.catatan_revisi) {
                    $('#dt_catatan_revisi').text(t.catatan_revisi);
                    $('#dt_revisi_wrap').show();
                } else {
                    $('#dt_revisi_wrap').hide();
                }

                // Tampilkan info keterlambatan, SP 1/2/3 & jadwal pertemuan HRD
                var spLevel = parseInt(t.sp_level, 10) || 0;
                var hariTelat = parseInt(t.hari_telat, 10) || 0;
                var isLate = !!t.is_late || (parseInt(t.max_bintang, 10) === 1);
                if (isLate || spLevel >= 1) {
                    var spHtml = '<div class="alert ' + (spLevel >= 1 ? 'alert-danger' : 'alert-warning') + ' py-2 px-3 mb-0 small">';
                    spHtml += '<div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:8px;">';
                    spHtml += '<div>';
                    if (spLevel >= 1) {
                        spHtml += '<span class="badge badge-danger mr-1" style="font-size:11.5px;"><i class="fas fa-gavel mr-1"></i> STATUS SANKSI: ' + (t.sp_label || ('SP ' + spLevel)) + '</span> ';
                    }
                    spHtml += '<strong>Terlambat H+' + hariTelat + ' hari dari deadline (' + (t.tgl_deadline_fmt || '-') + ').</strong> ';
                    spHtml += 'Maksimal nilai bintang terkunci pada <strong>⭐ 1/5</strong>.';
                    if (t.sp_desc) {
                        spHtml += '<div class="mt-1">' + $('<div>').text(t.sp_desc).html() + '</div>';
                    }
                    if (t.hrd_jadwal_tgl) {
                        spHtml += '<div class="mt-1 p-1 px-2 rounded bg-white text-dark border border-danger">' +
                            '<strong><i class="fas fa-handshake text-danger mr-1"></i> Jadwal Pertemuan HRD:</strong> ' +
                            (t.hrd_jadwal_tgl_fmt || t.hrd_jadwal_tgl) + ' pukul ' + (t.hrd_jadwal_jam || '10:00') + ' WIB — ' +
                            $('<div>').text((t.hrd_jadwal_metode || 'Tatap Muka') + ' (' + (t.hrd_jadwal_lokasi || 'Ruang HRD') + ')').html() +
                            (t.hrd_jadwal_catatan ? '<br><em>Catatan: ' + $('<div>').text(t.hrd_jadwal_catatan).html() + '</em>' : '') +
                            '</div>';
                    }
                    spHtml += '</div>';
                    if (spLevel >= 3) {
                        spHtml += '<div>' +
                            '<button type="button" class="btn btn-xs btn-danger font-weight-bold btn-jadwal-hrd-sp3" ' +
                            'data-id="' + t.id_task + '" ' +
                            'data-kode="' + $('<div>').text(t.kode_task || '').html() + '" ' +
                            'data-judul="' + $('<div>').text(t.judul || '').html() + '" ' +
                            'data-karyawan="' + $('<div>').text(t.nama_penerima || '').html() + '" ' +
                            'data-telat="' + hariTelat + '" ' +
                            'data-tgl="' + (t.hrd_jadwal_tgl || '') + '" ' +
                            'data-jam="' + (t.hrd_jadwal_jam || '10:00') + '" ' +
                            'data-metode="' + $('<div>').text(t.hrd_jadwal_metode || 'Tatap Muka (Offline)').html() + '" ' +
                            'data-lokasi="' + $('<div>').text(t.hrd_jadwal_lokasi || 'Ruang HRD').html() + '" ' +
                            'data-catatan="' + $('<div>').text(t.hrd_jadwal_catatan || '').html() + '">' +
                            '<i class="fas fa-calendar-check mr-1"></i> ' + (t.hrd_jadwal_tgl ? 'Ubah Jadwal HRD' : 'Jadwalkan Pertemuan HRD') +
                            '</button>' +
                            '</div>';
                    }
                    spHtml += '</div></div>';
                    $('#dt_sp_alert_wrap').html(spHtml).show();
                } else {
                    $('#dt_sp_alert_wrap').hide().empty();
                }

                // Tampilkan kotak hasil rating jika sudah dinilai
                if (parseInt(t.status_task, 10) === 4 && t.rating_bintang) {
                    $('#dt_rating_stars_html').html(buildStarsJs(t.rating_bintang));
                    $('#dt_ulasan_atasan_text').text('"' + (t.ulasan_atasan || 'Pekerjaan selesai dengan baik.') + '"');
                    $('#dt_tgl_dinilai').text('Dinilai pada: ' + (t.tgl_dinilai || '-'));
                    $('#dt_hasil_rating_box').show();
                } else {
                    $('#dt_hasil_rating_box').hide();
                }

                // Isi form update progres & checklist
                $('#up_id_task').val(t.id_task);
                $('#sub_id_task').val(t.id_task);
                $('#rev_id_task').val(t.id_task);

                $('#up_progres_slider').val(t.progres || 0);
                $('#up_progres_label').text(t.progres || 0);
                $('#up_catatan').val('');
                $('#up_auto_calc').val('0');

                $('#sub_catatan_bawahan').val(t.catatan_bawahan || '');
                $('#sub_bukti_selesai').val(t.bukti_selesai || '');

                var isPenerima = (parseInt(t.id_penerima, 10) === myId);
                var canEditChecklist = isPenerima && stInt < 4 && stInt !== 2 && stInt !== 5 && stInt !== 6;

                // Render Checklist untuk Penerima Task (Bisa tambah, edit, hapus & centang)
                var chkHtml = '';
                if (canEditChecklist) {
                    if (t.checklist && t.checklist.length > 0) {
                        for (var i = 0; i < t.checklist.length; i++) {
                            var item = t.checklist[i];
                            var isChecked = parseInt(item.done, 10) === 1;
                            chkHtml += buildEditableChecklistRowHtml(item.item || '', isChecked, i + 1);
                        }
                    } else {
                        chkHtml += buildEditableChecklistRowHtml('', false, 1);
                    }
                    $('#dt_checklist_container').html(chkHtml);
                    $('#dt_readonly_checklist_wrap').hide().empty();
                } else {
                    $('#dt_checklist_container').empty();
                    if (t.checklist && t.checklist.length > 0) {
                        var doneCntRo = 0;
                        var roHtml = '<div class="small font-weight-bold text-primary mb-1"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Karyawan:</div>';
                        for (var k = 0; k < t.checklist.length; k++) {
                            var roItem = t.checklist[k];
                            var roDone = parseInt(roItem.done, 10) === 1;
                            if (roDone) doneCntRo++;
                            roHtml += '<div class="tp-checklist-row ' + (roDone ? 'done' : '') + ' mb-1">' +
                                '<i class="fas ' + (roDone ? 'fa-check-square text-success' : 'fa-square text-muted') + ' mr-1"></i>' +
                                '<span>' + $('<div>').text(roItem.item || '').html() + '</span>' +
                                '</div>';
                        }
                        roHtml = '<div class="d-flex justify-content-between align-items-center mb-1">' +
                            '<span class="small font-weight-bold text-primary"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Karyawan</span>' +
                            '<span class="badge badge-info">' + doneCntRo + ' / ' + t.checklist.length + ' Selesai</span>' +
                            '</div>' + roHtml.replace('<div class="small font-weight-bold text-primary mb-1"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Karyawan:</div>', '');
                        $('#dt_readonly_checklist_wrap').html(roHtml).show();
                    } else {
                        $('#dt_readonly_checklist_wrap').hide().empty();
                    }
                }

                // Tampilkan / sembunyikan panel sesuai status & role
                // Form Update Progres & Submit Selesai HANYA untuk Karyawan Penerima Tugas (Pelaksana) jika belum disubmit / belum tuntas
                if (canEditChecklist) {
                    $('#panelBawahanProgres').show();
                    $('#panelBawahanSubmit').show();
                    $('#panelSudahDisubmitInfo').hide();
                } else {
                    $('#panelBawahanProgres').hide();
                    $('#panelBawahanSubmit').hide();
                    if (isPenerima && stInt === 2) {
                        $('#panelSudahDisubmitInfo').show();
                    } else {
                        $('#panelSudahDisubmitInfo').hide();
                    }
                }

                // Panel Review / Beri Rating Bintang Atasan HANYA jika canReview dan statusnya Menunggu Penilaian (2) serta BELUM pernah diberi bintang
                var isAlreadyRated = (stInt === 4 && parseInt(t.rating_bintang, 10) > 0);
                window.currentTaskMaxStar = isLate ? 1 : 5;
                if (isLate) {
                    $('#lblOverdueDays').text(hariTelat);
                    $('#wrapOverdueMaxStarAlert').show();
                    $('#starPickerWidget .star-btn').each(function() {
                        var sVal = parseInt($(this).data('val'), 10);
                        if (sVal > 1) {
                            $(this).css({ opacity: 0.25, cursor: 'not-allowed' }).attr('title', 'Terkunci: Maksimal Bintang 1 karena melewati deadline');
                        }
                    });
                } else {
                    $('#wrapOverdueMaxStarAlert').hide();
                    $('#starPickerWidget .star-btn').css({ opacity: 1, cursor: 'pointer' });
                }

                if (canReview && stInt === 2 && !isAlreadyRated) {
                    $('#panelAtasanReview').show();
                    var initStar = 5;
                    if (isLate) initStar = 1;
                    setStarPickerValue(initStar);
                    $('#rev_ulasan_atasan').val('');
                } else {
                    $('#panelAtasanReview').hide();
                }

                // Render Riwayat Log
                var logsHtml = '';
                if (res.logs && res.logs.length > 0) {
                    for (var j = 0; j < res.logs.length; j++) {
                        var lg = res.logs[j];
                        logsHtml += '<div class="tp-log-item">' +
                            '<div class="d-flex justify-content-between small">' +
                            '<strong>' + $('<div>').text(lg.nama_pengguna || 'User').html() + ' — <span class="text-primary">' + $('<div>').text(lg.aksi).html() + '</span></strong>' +
                            '<span class="text-muted">' + (lg.created_at || '') + '</span>' +
                            '</div>' +
                            '<div class="small mt-1">' + $('<div>').text(lg.catatan || '').html() + '</div>' +
                            (lg.lampiran ? '<div class="small mt-1"><a href="' + $('<div>').text(lg.lampiran).html() + '" target="_blank"><i class="fas fa-link"></i> Lampiran Bukti</a></div>' : '') +
                            '</div>';
                    }
                } else {
                    logsHtml = '<div class="text-muted small">Belum ada riwayat aktivitas.</div>';
                }
                $('#dt_logs_container').html(logsHtml);

                $('#modalDetailTask').modal('show');

                setTimeout(function() {
                    if (focusTarget === 'submit' && $('#panelBawahanSubmit').is(':visible')) {
                        $('#sub_catatan_bawahan').trigger('focus');
                    } else if (focusTarget === 'review' && $('#panelAtasanReview').is(':visible')) {
                        $('#rev_ulasan_atasan').trigger('focus');
                    }
                }, 400);
            }
        });
    });

    // Helper pembuat baris checklist interaktif milik Penerima Task
    function buildEditableChecklistRowHtml(textVal, isChecked, num) {
        var safeVal = $('<div>').text(textVal || '').html().replace(/"/g, '&quot;');
        return '<div class="input-group input-group-sm mb-2 tp-checklist-edit-row">' +
            '<div class="input-group-prepend">' +
            '<div class="input-group-text bg-white">' +
            '<input type="checkbox" class="chk-subtask-item" title="Centang jika langkah ini sudah selesai" ' + (isChecked ? 'checked' : '') + '>' +
            '<input type="hidden" name="checklist_done_flags[]" class="chk-subtask-flag" value="' + (isChecked ? '1' : '0') + '">' +
            '</div>' +
            '</div>' +
            '<input type="text" name="checklist_items[]" class="form-control txt-subtask-item" value="' + safeVal + '" placeholder="Langkah kerja ke-' + (num || 1) + ' (ketik langkah kerja Anda)..." style="' + (isChecked ? 'text-decoration: line-through; color: #16a34a; background:#f0fdf4;' : '') + '">' +
            '<div class="input-group-append">' +
            '<button type="button" class="btn btn-outline-danger btn-remove-subtask-item" title="Hapus item checklist"><i class="fas fa-times"></i></button>' +
            '</div>' +
            '</div>';
    }

    function recalcChecklistProgres() {
        var total = 0;
        var done = 0;
        $('#dt_checklist_container .tp-checklist-edit-row').each(function() {
            var txt = $.trim($(this).find('.txt-subtask-item').val() || '');
            var isChk = $(this).find('.chk-subtask-item').is(':checked');
            $(this).find('.chk-subtask-flag').val(isChk ? '1' : '0');
            $(this).find('.txt-subtask-item').css({
                'text-decoration': isChk ? 'line-through' : 'none',
                'color': isChk ? '#16a34a' : '#1e293b',
                'background': isChk ? '#f0fdf4' : '#ffffff'
            });
            if (txt !== '') {
                total++;
                if (isChk) done++;
            }
        });
        if (total > 0) {
            var pct = Math.round((done / total) * 100);
            $('#up_progres_slider').val(pct);
            $('#up_progres_label').text(pct);
            $('#up_auto_calc').val('1');
        }
    }

    $('#btnAddBawahanChecklist').on('click', function() {
        var nextNum = $('#dt_checklist_container .tp-checklist-edit-row').length + 1;
        $('#dt_checklist_container').append(buildEditableChecklistRowHtml('', false, nextNum));
        $('#dt_checklist_container .tp-checklist-edit-row:last .txt-subtask-item').trigger('focus');
        recalcChecklistProgres();
    });

    $(document).on('click', '.btn-remove-subtask-item', function() {
        if ($('#dt_checklist_container .tp-checklist-edit-row').length > 1) {
            $(this).closest('.tp-checklist-edit-row').remove();
        } else {
            var $row = $(this).closest('.tp-checklist-edit-row');
            $row.find('.txt-subtask-item').val('');
            $row.find('.chk-subtask-item').prop('checked', false);
        }
        recalcChecklistProgres();
    });

    // Slider Progres & Checklist Auto-Calculation
    $('#up_progres_slider').on('input change', function() {
        $('#up_progres_label').text($(this).val());
        $('#up_auto_calc').val('0');
    });

    $(document).on('change', '.chk-subtask-item', function() {
        recalcChecklistProgres();
    });

    $(document).on('input', '.txt-subtask-item', function() {
        recalcChecklistProgres();
    });

    // Submit Update Progres
    $('#formUpdateProgres').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/update_progres',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    window.location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal memperbarui progres.');
                }
            }
        });
    });

    // Submit Selesai 100% (Bawahan -> Atasan)
    $('#formSubmitSelesai').on('submit', function(e) {
        e.preventDefault();

        var idTask = $('#sub_id_task').val();
        var catatan = $.trim($('#sub_catatan_bawahan').val());
        if (!catatan) {
            if (window.swal) {
                swal('Perhatian', 'Harap isi ringkasan hasil pekerjaan / penyelesaian terlebih dahulu!', 'warning');
            } else {
                alert('Harap isi ringkasan hasil pekerjaan / penyelesaian terlebih dahulu!');
            }
            $('#sub_catatan_bawahan').focus();
            return;
        }

        var $btn = $(this).find('button[type="submit"]');
        var originalBtnHtml = $btn.html();

        // Validasi: Cegah double click saat sedang proses kirim
        if ($btn.prop('disabled')) {
            return;
        }
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim Laporan...');

        var formData = $(this).serializeArray();
        $('#dt_checklist_container .txt-subtask-item').each(function() {
            var v = $.trim($(this).val() || '');
            if (v !== '') {
                formData.push({ name: 'checklist_items[]', value: v });
            }
        });
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/submit_selesai',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(originalBtnHtml);

                if (res && res.status === 'success') {
                    // 1. Tutup modal detail task
                    $('#modalDetailTask').modal('hide');

                    // 2. Update langsung row di tabel Tugas Saya tanpa refresh Chrome
                    if ($.fn.dataTable && $.fn.dataTable.isDataTable('#datatable_tugas_saya')) {
                        var dtSaya = $('#datatable_tugas_saya').DataTable();
                        var $trSaya = $('#datatable_tugas_saya').find('tr[data-id="' + idTask + '"], #row_task_saya_' + idTask);
                        if ($trSaya.length) {
                            // Kolom Progres (Index 5)
                            $trSaya.find('td').eq(5).html(
                                '<div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">' +
                                '<div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>' +
                                '</div>' +
                                '<div class="text-center small font-weight-bold">100%</div>'
                            );
                            // Kolom Status (Index 6)
                            $trSaya.find('td').eq(6).html(
                                '<span class="tp-status-badge tp-st-2"><i class="fas fa-hourglass-half"></i> Menunggu Penilaian</span>'
                            );
                            // Kolom Aksi (Index 8) -> Hilangkan tombol submit selesai jika ada
                            $trSaya.find('button[data-focus="submit"]').remove();

                            // Re-index row DataTables tanpa mereset halaman / sorting
                            dtSaya.row($trSaya).invalidate().draw(false);
                        }
                    }

                    // 3. Update juga di tabel Penugasan ke Karyawan jika ada
                    if ($.fn.dataTable && $.fn.dataTable.isDataTable('#datatable_tugas_atasan')) {
                        var dtAtasan = $('#datatable_tugas_atasan').DataTable();
                        var $trAtasan = $('#datatable_tugas_atasan').find('tr[data-id="' + idTask + '"], #row_task_atasan_' + idTask);
                        if ($trAtasan.length) {
                            // Kolom Progres (Index 6)
                            $trAtasan.find('td').eq(6).html(
                                '<div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">' +
                                '<div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>' +
                                '</div>' +
                                '<div class="text-center small font-weight-bold">100%</div>'
                            );
                            // Kolom Status (Index 7)
                            $trAtasan.find('td').eq(7).html(
                                '<span class="tp-status-badge tp-st-2"><i class="fas fa-hourglass-half"></i> Menunggu Penilaian</span>'
                            );
                            // Kolom Rating (Index 8)
                            $trAtasan.find('td').eq(8).html(
                                '<span class="badge badge-warning"><i class="fas fa-star"></i> Siap Dinilai</span>'
                            );
                            // Kolom Aksi Atasan (Index 9)
                            var btnReviewHtml = '<button type="button" class="btn btn-xs btn-warning font-weight-bold mb-1 btn-detail-task" data-id="' + idTask + '" data-focus="review">' +
                                '<i class="fas fa-star"></i> Beri Rating ⭐' +
                                '</button><br>';
                            $trAtasan.find('td').eq(9).html(btnReviewHtml);

                            dtAtasan.row($trAtasan).invalidate().draw(false);
                        }
                    }

                    // 4. Update badge counter tab & KPI card jika ada
                    var $badgeReview = $('#badge_need_review');
                    if ($badgeReview.length) {
                        var curReview = parseInt($badgeReview.text(), 10) || 0;
                        $badgeReview.text((curReview + 1) + ' Perlu Rating ⭐').removeClass('badge-secondary').addClass('badge-warning');
                    }
                    var $kpiReview = $('#kpi_need_review');
                    if ($kpiReview.length) {
                        var curKpi = parseInt($kpiReview.text(), 10) || 0;
                        $kpiReview.html((curKpi + 1) + ' <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small>');
                    }

                    // 5. Tampilkan SweetAlert sukses TANPA me-reload Chrome
                    if (window.swal) {
                        swal('Terkirim!', res.msg || 'Hasil pekerjaan berhasil dikirim ke Atasan untuk dinilai!', 'success');
                    } else {
                        alert(res.msg || 'Hasil pekerjaan berhasil dikirim ke Atasan untuk dinilai!');
                    }
                } else {
                    if (window.swal) {
                        swal('Gagal', (res && res.msg ? res.msg : 'Gagal mengirim laporan selesai.'), 'error');
                    } else {
                        alert(res && res.msg ? res.msg : 'Gagal mengirim laporan selesai.');
                    }
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalBtnHtml);
                var errTxt = 'Terjadi kesalahan jaringan atau server saat mengirim laporan.';
                try {
                    var jErr = JSON.parse(xhr.responseText);
                    if (jErr && (jErr.msg || jErr.message)) {
                        errTxt = jErr.msg || jErr.message;
                    }
                } catch(e) {}
                if (window.swal) {
                    swal('Error', errTxt, 'error');
                } else {
                    alert(errTxt);
                }
            }
        });
    });

    // Interactive 5-Star Rating Picker Logic
    var starDescriptions = {
        1: '⭐ (1 / 5 — Kurang Memuaskan / Banyak Kekurangan)',
        2: '⭐⭐ (2 / 5 — Perlu Perbaikan)',
        3: '⭐⭐⭐ (3 / 5 — Cukup Baik / Sesuai Standar)',
        4: '⭐⭐⭐⭐ (4 / 5 — Sangat Baik, Rapi & Tepat Waktu)',
        5: '⭐⭐⭐⭐⭐ (5 / 5 — Istimewa / Sempurna & Sangat Memuaskan!)'
    };

    function setStarPickerValue(val) {
        val = parseInt(val, 10) || 5;
        var maxAllowed = parseInt(window.currentTaskMaxStar, 10) || 5;
        if (val > maxAllowed) {
            val = maxAllowed;
        }
        $('#rev_rating_bintang').val(val);
        $('#starPickerWidget .star-btn').each(function() {
            var sVal = parseInt($(this).data('val'), 10);
            if (sVal <= val) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
        if (maxAllowed === 1) {
            $('#starPickerLabel').text('⭐ (1 / 5 — Maksimal Bintang 1 karena melewati batas Deadline)');
        } else {
            $('#starPickerLabel').text(starDescriptions[val] || '');
        }
    }

    $(document).on('click', '#starPickerWidget .star-btn', function() {
        var val = parseInt($(this).data('val'), 10) || 1;
        var maxAllowed = parseInt(window.currentTaskMaxStar, 10) || 5;
        if (val > maxAllowed) {
            setStarPickerValue(1);
            return;
        }
        setStarPickerValue(val);
    });

    $('input[name="keputusan"]').on('change', function() {
        if ($(this).val() === 'revisi') {
            $('#wrapStarPicker').slideUp(150);
        } else {
            $('#wrapStarPicker').slideDown(150);
        }
    });

    $(document).on('click', '.btn-quick-ulasan', function() {
        $('#rev_ulasan_atasan').val($(this).data('text'));
    });

    // Submit Review & Rating Bintang 5 Atasan
    $('#formReviewAtasan').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSubmitReviewAtasan');
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan Penilaian...');

        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/review_atasan',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    $('#modalDetailTask').modal('hide');
                    if (window.swal) {
                        swal('Berhasil!', res.msg, 'success').then(function() { window.location.reload(); });
                    } else {
                        alert(res.msg);
                        window.location.reload();
                    }
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menyimpan penilaian.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Buka Modal Jadwalkan Pertemuan HRD (SP 3)
    $(document).on('click', '.btn-jadwal-hrd-sp3', function() {
        var $b = $(this);
        $('#jhrd_id_task').val($b.data('id'));
        $('#jhrd_kode_task').text($b.data('kode') || '-');
        $('#jhrd_judul_task').text($b.data('judul') || '-');
        $('#jhrd_nama_karyawan').text($b.data('karyawan') || '-');
        $('#jhrd_hari_telat').text($b.data('telat') || '21');
        var tgl = $b.data('tgl');
        if (tgl) {
            $('#jhrd_tgl').val(tgl);
        }
        $('#jhrd_jam').val($b.data('jam') || '10:00');
        $('#jhrd_metode').val($b.data('metode') || 'Tatap Muka (Offline)');
        $('#jhrd_lokasi').val($b.data('lokasi') || 'Ruang HRD');
        $('#jhrd_catatan').val($b.data('catatan') || '');
        $('#modalDetailTask').modal('hide');
        setTimeout(function() {
            $('#modalJadwalHrdSp3').modal('show');
        }, 200);
    });

    $('#formJadwalHrdSp3').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSubmitJadwalHrdSp3');
        var orig = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan Jadwal...');
        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });
        $.ajax({
            url: baseUrl + 'task_proyek/jadwalkan_hrd',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(orig);
                if (res && res.status === 'success') {
                    $('#modalJadwalHrdSp3').modal('hide');
                    if (window.swal) {
                        swal('Terjadwal!', res.msg, 'success').then(function() { window.location.reload(); });
                    } else {
                        alert(res.msg);
                        window.location.reload();
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menyimpan jadwal pertemuan HRD.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(orig);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Deteksi Pengajuan Mandiri pada dropdown Penerima
    $('#select_penerima_task').on('change', function() {
        var val = $(this).val() || [];
        var myUserId = <?= (int)$myId ?>;
        var isSelf = (val.length === 1 && parseInt(val[0], 10) === myUserId);
        if (isSelf) {
            $('#alert_task_mandiri').slideDown();
            $('#btnSubmitBuatTask').html('<i class="fas fa-paper-plane mr-1"></i> Ajukan Task Mandiri ke HRD');
            $('label[for="chk_kirim_wa"]').html('<i class="fab fa-whatsapp text-success"></i> Kirim Notifikasi Konfirmasi Pengajuan ke WhatsApp Saya');
        } else {
            $('#alert_task_mandiri').slideUp();
            $('#btnSubmitBuatTask').html('<i class="fas fa-paper-plane mr-1"></i> Simpan & Tugaskan Sekarang');
            $('label[for="chk_kirim_wa"]').html('<i class="fab fa-whatsapp text-success"></i> Kirim Notifikasi WhatsApp Otomatis ke Penerima Tugas');
        }
    });

    // Event Handler ACC Task Mandiri oleh HRD
    $(document).on('click', '.btn-acc-hrd, .btn-modal-acc-hrd', function() {
        var idTask = $(this).data('id');
        var kode = $(this).data('kode') || '';
        var judul = $(this).data('judul') || '';

        var confirmMsg = 'Setujui (ACC) pengajuan task mandiri ' + (kode ? '[' + kode + '] ' : '') + judul + '?\n\nKaryawan akan dapat mulai mengerjakan dan notifikasi WhatsApp otomatis dikirimkan ke Pelaksana serta pihak Mengetahui.';
        if (!confirm(confirmMsg)) return;

        var $btn = $(this);
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: baseUrl + 'task_proyek/acc_hrd',
            type: 'POST',
            data: { id_task: idTask, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    if (window.swal) {
                        swal('Berhasil!', res.msg, 'success').then(function() { location.reload(); });
                    } else {
                        alert(res.msg);
                        location.reload();
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menyetujui task.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Event Handler Tolak Task Mandiri oleh HRD
    $(document).on('click', '.btn-tolak-hrd, .btn-modal-tolak-hrd', function() {
        var idTask = $(this).data('id');
        var kode = $(this).data('kode') || '';
        var judul = $(this).data('judul') || '';

        var alasan = prompt('Masukkan alasan penolakan pengajuan task mandiri ' + (kode ? '[' + kode + '] ' : '') + ':', '');
        if (alasan === null) return;
        alasan = $.trim(alasan);
        if (!alasan) {
            alert('Alasan penolakan wajib diisi!');
            return;
        }

        var $btn = $(this);
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: baseUrl + 'task_proyek/tolak_hrd',
            type: 'POST',
            data: { id_task: idTask, alasan: alasan, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    if (window.swal) {
                        swal('Ditolak', res.msg, 'info').then(function() { location.reload(); });
                    } else {
                        alert(res.msg);
                        location.reload();
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menolak task.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Inisialisasi DataTable Semua Task Perusahaan
    if ($.fn.dataTable && $('#datatable_semua_task').length) {
        var dtSemua = $('#datatable_semua_task').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' },
            order: [[0, 'asc']],
            pageLength: 25
        });

        $('#filter_semua_karyawan').on('change', function() {
            var val = $(this).val();
            dtSemua.column(3).search(val ? val : '', true, false).draw();
        });

        $('#filter_semua_status').on('change', function() {
            var val = $(this).val();
            dtSemua.column(8).search(val ? val : '', true, false).draw();
        });
    }

    // Hapus Task
    $(document).on('click', '.btn-hapus-task', function() {
        var idTask = $(this).data('id');
        var kode = $(this).data('kode');
        if (!confirm('Apakah Anda yakin ingin menghapus Task ' + kode + '?')) return;

        $.ajax({
            url: baseUrl + 'task_proyek/delete',
            type: 'POST',
            data: { id_task: idTask, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    window.location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menghapus task.');
                }
            }
        });
    });
});
</script>
