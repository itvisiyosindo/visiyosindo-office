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
    .nav-tabs-tp > li > a {
        font-weight: 600;
        padding: 12px 18px;
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
            return '<span class="tp-status-badge tp-st-2"><i class="fas fa-Hourglass-half"></i> Menunggu Review & Rating ⭐</span>';
        case 3:
            return '<span class="tp-status-badge tp-st-3"><i class="fas fa-redo"></i> Perlu Revisi</span>';
        case 4:
            return '<span class="tp-status-badge tp-st-4"><i class="fas fa-check-circle"></i> Selesai & Dinilai</span>';
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

<section role="main" class="content-body">
    <header class="page-header">
        <h2><?= htmlspecialchars($page_title) ?></h2>
        <div class="right-wrapper text-right">
            <ol class="breadcrumbs">
                <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                <li><span>Task & Proyek Kerja</span></li>
            </ol>
            <a class="sidebar-right-toggle"><i class="fas fa-chevron-left"></i></a>
        </div>
    </header>

    <?php
    $isAdminUser      = (!empty($isAdmin) || isAdmin() || (int)$myId === 1);
    $jmlSupervisiSaya = count($list_bawahan);
    $canGiveTask      = ($isAdminUser || $jmlSupervisiSaya > 0);
    $penerimaList     = $isAdminUser ? $list_semua_karyawan : $list_bawahan;
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
                    <i class="far fa-calendar-alt text-primary mr-1"></i> Periode:
                </label>
                <select name="bulan" class="form-control form-control-sm" style="min-width: 140px; border-radius: 6px;">
                    <option value="">-- Semua Bulan --</option>
                    <?php foreach ($namaBulanList as $num => $nm): ?>
                        <option value="<?= $num ?>" <?= ((int)$filterBulan === $num) ? 'selected' : '' ?>><?= $nm ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="tahun" class="form-control form-control-sm" style="min-width: 105px; border-radius: 6px;">
                    <option value="all" <?= ($filterTahun === 'all') ? 'selected' : '' ?>>Semua Tahun</option>
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

            <!-- Tombol Beri Task (Jika punya bawahan atau Administrator) -->
            <?php if ($canGiveTask): ?>
                <div>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold shadow-sm" id="btnOpenModalBuatTask" style="border-radius: 6px; padding: 6px 14px;">
                        <i class="fas fa-plus-circle mr-1"></i> Beri Task / Proyek <?= $isAdminUser ? 'ke Pegawai' : 'ke Karyawan' ?>
                    </button>
                </div>
            <?php endif; ?>
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
                    <?php else: ?>
                        <span class="badge badge-warning px-2 py-1" style="font-size: 11px; border-radius: 6px;">Belum Diatur</span>
                    <?php endif; ?>
                    <button type="button" class="btn btn-xs btn-outline-primary ml-2 font-weight-bold" data-toggle="modal" data-target="#modalAturAtasanSaya" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;">
                        <i class="fas fa-user-edit mr-1"></i> <?= !empty($me->nama_atasan) ? 'Ubah' : 'Pilih Atasan' ?>
                    </button>
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
                    <?php if ($isAdminUser): ?>
                        <span class="badge badge-info px-2 py-1 ml-2" title="Sebagai Administrator Anda dapat menugaskan task ke pegawai manapun" style="font-size: 11px; border-radius: 6px;">
                            <i class="fas fa-shield-alt mr-1"></i> Akses Administrator (Bisa Beri Task ke Pegawai Manapun)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-muted small d-none d-lg-block" style="font-size: 12px;">
                <i class="fas fa-star text-warning mr-1"></i> Evaluasi Kinerja dengan <strong>Rating Bintang 1–5 ⭐</strong>
            </div>
        </div>
    </div>

    <!-- 4 KARTU KPI RINGKASAN -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="tp-kpi-card">
                <div>
                    <div class="text-muted small font-weight-bold text-uppercase">Tugas Aktif Saya</div>
                    <div style="font-size: 26px; font-weight: 800; color: #1e293b;"><?= (int)$kpi['my_active'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                    <div class="small text-primary">Sebagai Pelaksana / Karyawan</div>
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
                    <div style="font-size: 26px; font-weight: 800; color: #d97706;"><?= (int)$kpi['need_review'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
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
                        </div>
                        <div class="text-muted" style="font-size: 10.5px;">Bobot: <?= htmlspecialchars($kpi['my_combined']->komposisi) ?></div>
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
            $openTabAtasan = ($this->input->get('tab', TRUE) === 'atasan') || (empty($tugas_saya) && !empty($tugas_atasan));
            ?>
            <ul class="nav nav-tabs nav-tabs-tp" role="tablist">
                <li class="nav-item <?= !$openTabAtasan ? 'active' : '' ?>">
                    <a class="nav-link <?= !$openTabAtasan ? 'active' : '' ?>" href="#tab_tugas_saya" data-toggle="tab" role="tab">
                        <i class="fas fa-inbox text-primary mr-1"></i> Tugas Saya (Karyawan)
                        <?php if ($kpi['my_active'] > 0): ?>
                            <span class="badge badge-primary ml-1"><?= (int)$kpi['my_active'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item <?= $openTabAtasan ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabAtasan ? 'active' : '' ?>" href="#tab_tugas_atasan" data-toggle="tab" role="tab">
                        <i class="fas fa-paper-plane text-success mr-1"></i> Penugasan ke Karyawan
                        <?php if ($kpi['need_review'] > 0): ?>
                            <span class="badge badge-warning ml-1"><?= (int)$kpi['need_review'] ?> Perlu Rating ⭐</span>
                        <?php else: ?>
                            <span class="badge badge-secondary ml-1"><?= count($tugas_atasan) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_leaderboard" data-toggle="tab" role="tab">
                        <i class="fas fa-trophy text-warning mr-1"></i> Leaderboard KPI & Evaluasi Karyawan
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
                                    <tr>
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
                                            <?php if ($isOverdue): ?>
                                                <span class="badge badge-danger mt-1">Terlambat / Overdue</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td><?= renderStatusTaskBadge($row->status_task) ?></td>
                                        <td>
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                                <?php if (!empty($row->ulasan_atasan)): ?>
                                                    <div class="small text-muted mt-1" style="max-width:200px; white-space:normal;">"<?= htmlspecialchars($row->ulasan_atasan) ?>"</div>
                                                <?php endif; ?>
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
                                                <button type="button" class="btn btn-xs btn-success btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="submit">
                                                    <i class="fas fa-check"></i> Submit Selesai
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
                                    <tr>
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
                                        </td>
                                        <td>
                                            <span class="tp-priority-badge tp-prio-<?= htmlspecialchars($row->prioritas) ?>"><?= htmlspecialchars($row->prioritas) ?></span>
                                            <div class="small mt-1">
                                                <?= !empty($row->tgl_deadline) ? date('d-m-Y', strtotime($row->tgl_deadline)) : '-' ?>
                                            </div>
                                            <?php if ($isOverdue): ?>
                                                <span class="badge badge-danger mt-1">Overdue</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-info' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td><?= renderStatusTaskBadge($row->status_task) ?></td>
                                        <td>
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                                <?php if (!empty($row->ulasan_atasan)): ?>
                                                    <div class="small text-muted mt-1" style="max-width:180px; white-space:normal;">"<?= htmlspecialchars($row->ulasan_atasan) ?>"</div>
                                                <?php endif; ?>
                                            <?php elseif ((int)$row->status_task === 2): ?>
                                                <span class="badge badge-warning"><i class="fas fa-star"></i> Siap Dinilai</span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ((int)$row->status_task === 2): ?>
                                                <button type="button" class="btn btn-xs btn-warning font-weight-bold mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="review">
                                                    <i class="fas fa-star"></i> Beri Rating ⭐
                                                </button><br>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-xs btn-info mb-1 btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="review">
                                                    <i class="fas fa-eye"></i> Detail & Nilai
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

                <!-- ================= TAB 3: LEADERBOARD KPI & EVALUASI KARYAWAN ================= -->
                <div id="tab_leaderboard" class="tab-pane" role="tabpanel">
                    <div class="alert alert-info py-2 mb-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px; border-left: 4px solid #0284c7; background: #f0f9ff; color: #0369a1;">
                        <div>
                            <i class="fas fa-medal text-warning mr-1"></i>
                            <strong>Sistem Evaluasi Kinerja (KPI Terpadu):</strong> Integrasi bobot <strong>70% Penugasan Task Proyek</strong> (konversi rating bintang 1–5 ⭐) + <strong>30% Nilai Laporan Mingguan</strong>.
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
                                    <th width="50" class="text-center align-middle">Rank</th>
                                    <th class="align-middle">Nama Karyawan</th>
                                    <th class="align-middle">Divisi & Jabatan</th>
                                    <th class="text-center align-middle" style="min-width: 130px;">
                                        <div>Task Proyek</div>
                                        <small class="badge badge-primary" style="font-size: 10px;">Bobot 70%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 130px;">
                                        <div>Laporan Mingguan</div>
                                        <small class="badge badge-info" style="font-size: 10px;">Bobot 30%</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 110px; background: #f0fdf4;">
                                        <div class="text-success font-weight-bold">Skor Akhir</div>
                                        <small class="text-muted" style="font-size: 10px;">Skala 100</small>
                                    </th>
                                    <th class="text-center align-middle" style="min-width: 140px;">Rating Agregat</th>
                                    <th class="text-center align-middle" width="125">Grade Kinerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rank = 1; foreach ($leaderboard as $lb):
                                    $selesai = (int)$lb->task_selesai;
                                    $isMe    = ((int)$lb->pengguna_id === (int)$myId);
                                ?>
                                    <tr <?= $isMe ? 'style="background-color: #f0f9ff;"' : '' ?>>
                                        <td class="text-center font-weight-bold align-middle">
                                            <?php if ($rank === 1 && $lb->skor_akhir !== null): ?>
                                                <span class="badge badge-warning" style="font-size:13px;">🥇 #1</span>
                                            <?php elseif ($rank === 2 && $lb->skor_akhir !== null): ?>
                                                <span class="badge badge-secondary" style="font-size:13px;">🥈 #2</span>
                                            <?php elseif ($rank === 3 && $lb->skor_akhir !== null): ?>
                                                <span class="badge badge-info" style="font-size:13px;">🥉 #3</span>
                                            <?php else: ?>
                                                #<?= $rank ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <strong><?= htmlspecialchars($lb->nama) ?></strong>
                                            <?php if ($isMe): ?>
                                                <span class="badge badge-primary ml-1" style="font-size: 10px;">Saya</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge badge-light border"><?= htmlspecialchars($lb->nama_divisi ?: '-') ?></span>
                                            <div class="small text-muted"><?= htmlspecialchars($lb->jabatan ?: '') ?></div>
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
                                        <td class="text-center align-middle" style="background: #f0fdf4;">
                                            <?php if ($lb->skor_akhir !== null): ?>
                                                <div class="font-weight-bold" style="font-size: 17px; color: <?= $lb->grade_color ?>;"><?= number_format($lb->skor_akhir, 1) ?></div>
                                                <div class="small text-muted" style="font-size: 10px;"><?= htmlspecialchars($lb->komposisi) ?></div>
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
</section>

<!-- =========================================================================
     MODAL 1: BUAT TASK / PROYEK BARU (ATASAN -> KARYAWAN)
     ========================================================================= -->
<div class="modal fade" id="modalBuatTask" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formBuatTask">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-plus-circle mr-1"></i> Buat Penugasan Task / Proyek ke Karyawan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                            <label class="font-weight-bold mb-0">Pilih Karyawan Penerima Tugas <span class="text-danger">*</span></label>
                            <?php if (!empty($penerimaList)): ?>
                                <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" id="btnSelectSupervisedEmployees">
                                    <i class="fas fa-user-check"></i> <?= $isAdminUser ? 'Pilih Semua Pegawai' : 'Pilih Semua Bawahan' ?> (<?= count($penerimaList) ?>)
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if ($isAdminUser): ?>
                            <small class="text-primary d-block mb-1"><i class="fas fa-shield-alt mr-1"></i> Sebagai <strong>Administrator</strong>, Anda dapat memberikan task / proyek kepada seluruh pegawai perusahaan.</small>
                        <?php else: ?>
                            <small class="text-muted d-block mb-1">Hanya menampilkan karyawan yang berada di bawah supervisi / atasan langsung Anda.</small>
                        <?php endif; ?>
                        <select name="id_penerima[]" id="select_penerima_task" class="form-control" multiple="multiple" required style="width: 100%;">
                            <?php foreach ($penerimaList as $bw):
                                $divLabel = !empty($bw->nama_divisi) ? $bw->nama_divisi : 'Umum';
                            ?>
                                <option value="<?= (int)$bw->pengguna_id ?>">
                                    <?= htmlspecialchars($bw->nama) ?> <?= !empty($bw->jabatan) ? '(' . htmlspecialchars($bw->jabatan) . ')' : '' ?> — [<?= htmlspecialchars($divLabel) ?>]
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($penerimaList)): ?>
                            <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Belum ada karyawan yang dibawahi:</strong> Anda hanya dapat memberikan tugas kepada karyawan yang dibawahi langsung oleh Anda. Belum ada karyawan yang memilih Anda sebagai <em>Atasan Langsung</em>. Karyawan dapat memilih Anda di menu <strong>Profil Karyawan</strong> mereka, atau Admin/HRD dapat mengaturnya di menu <strong>Data Karyawan</strong>.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Proyek / Kategori Kerja</label>
                                <input type="text" name="nama_proyek" class="form-control" placeholder="Contoh: Proyek Instalasi CCTV / Operasional IT">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Penugasan</label>
                                <select name="jenis" class="form-control">
                                    <option value="Task Harian">Task Harian</option>
                                    <option value="Proyek Mingguan">Proyek Mingguan</option>
                                    <option value="Proyek Bulanan / Khusus">Proyek Bulanan / Khusus</option>
                                    <option value="Urgent / Insidental">Urgent / Insidental</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Prioritas</label>
                                <select name="prioritas" class="form-control">
                                    <option value="Low">Low (Rendah)</option>
                                    <option value="Medium" selected>Medium (Normal)</option>
                                    <option value="High">High (Tinggi)</option>
                                    <option value="Urgent">Urgent (Mendesak)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Task / Instruksi Utama <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" required placeholder="Contoh: Penarikan Kabel Jaringan & Konfigurasi Router Lantai 2">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Target Selesai (Deadline) <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_deadline" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Deskripsi / Rincian Instruksi Kerja</label>
                        <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tuliskan rincian target, spesifikasi hasil yang diharapkan, atau langkah kerja..."></textarea>
                    </div>

                    <!-- DYNAMIC CHECKLIST BUILDER -->
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="font-weight-bold mb-0"><i class="fas fa-list-ul text-primary"></i> Checklist Sub-Task / Langkah Kerja (Opsional)</label>
                            <button type="button" class="btn btn-xs btn-outline-primary" id="btnAddChecklistRow">
                                <i class="fas fa-plus"></i> Tambah Item Checklist
                            </button>
                        </div>
                        <div id="checklistBuilderContainer">
                            <div class="input-group input-group-sm mb-1 checklist-input-row">
                                <input type="text" name="checklist_items[]" class="form-control" placeholder="Item checklist ke-1 (contoh: Siapkan peralatan & bahan)...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger btn-remove-checklist"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Link Lampiran / Referensi dari Atasan (Opsional)</label>
                        <input type="text" name="lampiran_atasan" class="form-control" placeholder="https://drive.google.com/... (Link dokumen, desain, atau referensi)">
                    </div>

                    <div class="checkbox-custom checkbox-success mt-2">
                        <input type="checkbox" id="chk_kirim_wa" name="kirim_wa" value="1" checked>
                        <label for="chk_kirim_wa" class="font-weight-bold text-success">
                            <i class="fab fa-whatsapp"></i> Kirim Notifikasi WhatsApp Otomatis ke Karyawan
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold" id="btnSubmitBuatTask" <?= empty($penerimaList) ? 'disabled title="Tidak ada karyawan yang dapat dipilih"' : '' ?>>
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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-clipboard-list mr-1"></i> Detail Task: <span id="dt_kode_task">-</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- INFO RINGKAS TASK -->
                <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                        <div>
                            <span class="badge badge-info" id="dt_nama_proyek">Proyek</span>
                            <span class="badge badge-secondary" id="dt_jenis">Jenis</span>
                            <span id="dt_prioritas_badge"></span>
                            <h4 class="font-weight-bold mt-2 mb-1" id="dt_judul" style="color:#0f172a;">-</h4>
                        </div>
                        <div class="text-right">
                            <div id="dt_status_badge"></div>
                            <div class="small text-muted mt-1">Deadline: <strong id="dt_deadline">-</strong></div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row small">
                        <div class="col-md-6">
                            <strong>Pemberi Tugas (Atasan):</strong> <span id="dt_pemberi">-</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Pelaksana (Karyawan):</strong> <span id="dt_penerima">-</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <strong class="small text-uppercase text-muted">Instruksi / Deskripsi Pekerjaan:</strong>
                        <div id="dt_deskripsi" class="p-2 mt-1 rounded bg-white border" style="white-space: pre-line; font-size: 13px;">-</div>
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
                </div>

                <!-- HASIL PENILAIAN BINTANG 5 (TAMPIL JIKA SUDAH DINILAI) -->
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

                <!-- PANEL 1: CHECKLIST & UPDATE PROGRES (UNTUK PELAKSANA / KARYAWAN) -->
                <div id="panelBawahanProgres" class="card mb-3 border-primary">
                    <div class="card-header bg-light py-2">
                        <strong class="text-primary"><i class="fas fa-tasks mr-1"></i> Checklist Sub-Task & Update Progres Pengerjaan</strong>
                    </div>
                    <div class="card-body py-3">
                        <form id="formUpdateProgres">
                            <input type="hidden" name="id_task" id="up_id_task">
                            <input type="hidden" name="auto_calc_checklist" id="up_auto_calc" value="0">

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
                                <button type="submit" class="btn btn-sm btn-success font-weight-bold">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Hasil Pekerjaan & Minta Rating ⭐
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PANEL 3: FORM PENILAIAN RATING BINTANG 5 & REVIEW ATASAN -->
                <div id="panelAtasanReview" class="card mb-3" style="border: 2px solid #f59e0b; display:none;">
                    <div class="card-header py-2" style="background: #fef3c7;">
                        <strong style="color:#b45309;"><i class="fas fa-star mr-1"></i> Form Evaluasi & Penilaian Bintang 5 (Khusus Atasan / Reviewer)</strong>
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
        <?php if (!$isAdminUser && empty($list_bawahan)): ?>
            if (window.swal) {
                swal('Perhatian', 'Anda belum memiliki karyawan yang dibawahi langsung, sehingga belum dapat memberikan tugas. Karyawan harus memilih Anda sebagai Atasan Langsung terlebih dahulu.', 'warning');
            } else {
                alert('Anda belum memiliki karyawan yang dibawahi langsung, sehingga belum dapat memberikan tugas. Karyawan harus memilih Anda sebagai Atasan Langsung terlebih dahulu.');
            }
            return;
        <?php endif; ?>
        $('#formBuatTask')[0].reset();
        if ($.fn.select2) {
            $('#select_penerima_task').val(null).trigger('change');
        }
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
                var canReview = (parseInt(t.id_pemberi, 10) === myId) || !!res.is_admin_reviewer;

                $('#dt_kode_task').text(t.kode_task);
                $('#dt_nama_proyek').text(t.nama_proyek || 'Umum');
                $('#dt_jenis').text(t.jenis || 'Task Harian');
                $('#dt_prioritas_badge').html('<span class="tp-priority-badge tp-prio-' + t.prioritas + '">' + t.prioritas + '</span>');
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

                // Render Checklist Interaktif
                var chkHtml = '';
                if (t.checklist && t.checklist.length > 0) {
                    chkHtml += '<label class="font-weight-bold small">Checklist Sub-Task (Centang yang sudah selesai):</label>';
                    for (var i = 0; i < t.checklist.length; i++) {
                        var item = t.checklist[i];
                        var isChecked = parseInt(item.done, 10) === 1;
                        chkHtml += '<div class="tp-checklist-row ' + (isChecked ? 'done' : '') + '">' +
                            '<input type="checkbox" class="chk-subtask-item" name="checklist_done[]" value="' + i + '" ' + (isChecked ? 'checked' : '') + '>' +
                            '<span>' + $('<div>').text(item.item).html() + '</span>' +
                            '</div>';
                    }
                }
                $('#dt_checklist_container').html(chkHtml);

                // Tampilkan / sembunyikan panel sesuai status & role
                if (parseInt(t.status_task, 10) === 4) {
                    $('#panelBawahanProgres').hide();
                    $('#panelBawahanSubmit').hide();
                } else {
                    $('#panelBawahanProgres').show();
                    $('#panelBawahanSubmit').show();
                }

                if (canReview) {
                    $('#panelAtasanReview').show();
                    var initStar = parseInt(t.rating_bintang, 10) || 5;
                    setStarPickerValue(initStar);
                    $('#rev_ulasan_atasan').val(t.ulasan_atasan || '');
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

    // Slider Progres & Checklist Auto-Calculation
    $('#up_progres_slider').on('input change', function() {
        $('#up_progres_label').text($(this).val());
        $('#up_auto_calc').val('0');
    });

    $(document).on('change', '.chk-subtask-item', function() {
        var $row = $(this).closest('.tp-checklist-row');
        if ($(this).is(':checked')) {
            $row.addClass('done');
        } else {
            $row.removeClass('done');
        }
        var total = $('.chk-subtask-item').length;
        var done = $('.chk-subtask-item:checked').length;
        if (total > 0) {
            var pct = Math.round((done / total) * 100);
            $('#up_progres_slider').val(pct);
            $('#up_progres_label').text(pct);
            $('#up_auto_calc').val('1');
        }
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
        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/submit_selesai',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    $('#modalDetailTask').modal('hide');
                    if (window.swal) {
                        swal('Terkirim!', res.msg, 'success').then(function() { window.location.reload(); });
                    } else {
                        alert(res.msg);
                        window.location.reload();
                    }
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal mengirim laporan selesai.');
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
        $('#rev_rating_bintang').val(val);
        $('#starPickerWidget .star-btn').each(function() {
            var sVal = parseInt($(this).data('val'), 10);
            if (sVal <= val) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
        $('#starPickerLabel').text(starDescriptions[val] || '');
    }

    $(document).on('click', '#starPickerWidget .star-btn', function() {
        var val = $(this).data('val');
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
