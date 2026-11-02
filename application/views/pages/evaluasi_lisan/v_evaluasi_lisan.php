<style>
    .evl-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #0ea5e9 100%);
        border-radius: 14px;
        padding: 22px 26px;
        color: #fff;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.18);
        margin-bottom: 22px;
    }
    .evl-stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .evl-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .evl-section-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .evl-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .evl-feedback-pill {
        background: #f1f5f9;
        border-left: 3px solid #3b82f6;
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 12px;
        color: #334155;
        margin-bottom: 6px;
    }
    .evl-feedback-pill strong {
        color: #0f172a;
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .evl-detail-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #2563eb;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 12px;
    }
    .evl-detail-label {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 4px;
    }
    .evl-detail-text {
        font-size: 13.5px;
        color: #0f172a;
        white-space: pre-line;
    }
</style>

<header class="page-header">
    <h2><?= htmlspecialchars($page_title) ?></h2>
    <div class="right-wrapper text-right">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
            <li><span>Kepegawaian</span></li>
            <li><span>Evaluasi Lisan & Konseling HRGA</span></li>
        </ol>
        <a class="sidebar-right-toggle"><i class="fas fa-chevron-left"></i></a>
    </div>
</header>

<div class="row">
    <div class="col-lg-12">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> <?= $this->session->flashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-1"></i> <?= $this->session->flashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <!-- Hero Banner -->
        <div class="evl-hero d-flex flex-wrap align-items-center justify-content-between">
            <div class="mb-2 mb-md-0">
                <h4 class="font-weight-bold mb-1 text-white">
                    <i class="fas fa-comments mr-2"></i> Evaluasi Lisan & Penjadwalan Konseling HRGA
                </h4>
                <p class="mb-0" style="opacity: 0.9; font-size: 13.5px;">
                    Sampaikan masukan konstruktif untuk <strong>Manajemen, Karyawan (Seluruh Karyawan), Lingkungan Kerja, dan Pekerjaan</strong>, lampirkan dokumen pendukung, serta jadwalkan sesi konseling bersama HRGA.
                </p>
            </div>
            <div>
                <button type="button" class="btn btn-light font-weight-bold text-primary shadow-sm" onclick="openFormEvaluasiBaru()">
                    <i class="fas fa-plus-circle mr-1"></i> Isi Evaluasi Lisan & Konseling
                </button>
            </div>
        </div>

        <?php if ($canManage): ?>
            <!-- Statistik Ringkas untuk Manajemen (Bob Ariyos, Meilina, Yola) & HRGA -->
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="evl-stat-card">
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Total Evaluasi Masuk</div>
                            <h3 class="font-weight-bold mb-0 mt-1 text-dark"><?= (int)$stats['total'] ?></h3>
                        </div>
                        <div class="evl-stat-icon" style="background:#eff6ff; color:#2563eb;">
                            <i class="fas fa-inbox"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="evl-stat-card">
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Mengajukan Konseling</div>
                            <h3 class="font-weight-bold mb-0 mt-1 text-primary"><?= (int)$stats['butuh_konseling'] ?></h3>
                        </div>
                        <div class="evl-stat-icon" style="background:#f0fdf4; color:#16a34a;">
                            <i class="fas fa-user-friends"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="evl-stat-card">
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Menunggu Jadwal HRGA</div>
                            <h3 class="font-weight-bold mb-0 mt-1 text-warning"><?= (int)$stats['menunggu_jadwal'] ?></h3>
                        </div>
                        <div class="evl-stat-icon" style="background:#fefce8; color:#ca8a04;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="evl-stat-card">
                        <div>
                            <div class="text-muted small font-weight-bold text-uppercase">Terjadwal / Selesai</div>
                            <h3 class="font-weight-bold mb-0 mt-1 text-success"><?= (int)($stats['terjadwal'] + $stats['konseling_selesai']) ?></h3>
                        </div>
                        <div class="evl-stat-icon" style="background:#ecfeff; color:#0891b2;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Card Tabel Utama -->
        <section class="card card-modern">
            <div class="card-body">

                <?php if ($canManage): ?>
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" data-toggle="tab" href="#tabRekapSemua" role="tab">
                                <i class="fas fa-users-cog text-primary mr-1"></i> Rekap Seluruh Karyawan (Manajemen & HRGA)
                                <span class="badge badge-primary ml-1"><?= count($allEvaluasi) ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#tabEvaluasiSaya" role="tab">
                                <i class="fas fa-user-edit text-info mr-1"></i> Evaluasi Lisan Saya
                                <span class="badge badge-info ml-1"><?= count($myEvaluasi) ?></span>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <div class="tab-content">

                    <?php if ($canManage): ?>
                        <!-- TAB 1: REKAP SELURUH KARYAWAN (KHUSUS MANAJEMEN & HRGA) -->
                        <div class="tab-pane fade show active" id="tabRekapSemua" role="tabpanel">
                            <form method="get" action="<?= base_url('evaluasi_lisan') ?>" class="mb-3 p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <input type="hidden" name="nav" value="<?= htmlspecialchars($switch) ?>">
                                <div class="form-row align-items-end">
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">Tahun</label>
                                        <select name="tahun" class="form-control form-control-sm">
                                            <option value="all" <?= $filterTahun === 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                                            <?php for ($y = (int)date('Y') + 1; $y >= 2024; $y--): ?>
                                                <option value="<?= $y ?>" <?= (string)$filterTahun === (string)$y ? 'selected' : '' ?>><?= $y ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">Bulan</label>
                                        <select name="bulan" class="form-control form-control-sm">
                                            <option value="all">Semua Bulan</option>
                                            <?php
                                            $namaBulan = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ];
                                            foreach ($namaBulan as $num => $label):
                                            ?>
                                                <option value="<?= $num ?>" <?= (string)$filterBulan === (string)$num ? 'selected' : '' ?>><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">Filter Konseling HRGA</label>
                                        <select name="konseling" class="form-control form-control-sm">
                                            <option value="all">Semua Status Konseling</option>
                                            <option value="ya" <?= $filterKonseling === 'ya' ? 'selected' : '' ?>>Semua yang Butuh Konseling</option>
                                            <option value="Menunggu Jadwal" <?= $filterKonseling === 'Menunggu Jadwal' ? 'selected' : '' ?>>Menunggu Jadwal HRGA</option>
                                            <option value="Terjadwal" <?= $filterKonseling === 'Terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
                                            <option value="Selesai" <?= $filterKonseling === 'Selesai' ? 'selected' : '' ?>>Konseling Selesai</option>
                                            <option value="tidak" <?= $filterKonseling === 'tidak' ? 'selected' : '' ?>>Tidak Butuh Konseling</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-6 mb-2">
                                        <label class="small font-weight-bold text-muted mb-1">Status Evaluasi</label>
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="all">Semua Status</option>
                                            <option value="Terkirim" <?= $filterStatus === 'Terkirim' ? 'selected' : '' ?>>Terkirim</option>
                                            <option value="Ditinjau" <?= $filterStatus === 'Ditinjau' ? 'selected' : '' ?>>Ditinjau</option>
                                            <option value="Selesai" <?= $filterStatus === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-12 mb-2 d-flex" style="gap:6px;">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                                            <i class="fas fa-filter mr-1"></i> Filter
                                        </button>
                                        <a href="<?= base_url('evaluasi_lisan?nav=' . urlencode($switch)) ?>" class="btn btn-light btn-sm border" title="Reset">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover mb-0" id="tableRekapEvaluasi">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="45" class="text-center">No</th>
                                            <th width="130">Kode & Tanggal</th>
                                            <th width="190">Nama Karyawan</th>
                                            <th>Ringkasan Evaluasi Lisan (4 Kategori Masukan)</th>
                                            <th width="120" class="text-center">Lampiran</th>
                                            <th width="210">Konseling HRGA</th>
                                            <th width="150" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($allEvaluasi)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-50"></i>
                                                    Belum ada data Evaluasi Lisan & Pengajuan Konseling.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $no = 1; foreach ($allEvaluasi as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td>
                                                        <div class="font-weight-bold text-primary"><?= htmlspecialchars($row->kode_evaluasi) ?></div>
                                                        <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i><?= date('d/m/Y', strtotime($row->tanggal_evaluasi)) ?></small>
                                                        <div class="mt-1">
                                                            <?php
                                                            $badgeEval = 'badge-secondary';
                                                            if ($row->status_evaluasi === 'Ditinjau') $badgeEval = 'badge-info';
                                                            elseif ($row->status_evaluasi === 'Selesai') $badgeEval = 'badge-success';
                                                            ?>
                                                            <span class="badge <?= $badgeEval ?>"><?= htmlspecialchars($row->status_evaluasi) ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-weight-bold text-dark"><?= htmlspecialchars($row->nama_karyawan ?: '-') ?></div>
                                                        <small class="text-muted d-block"><?= htmlspecialchars($row->jabatan ?: '-') ?></small>
                                                        <?php if (!empty($row->nama_divisi)): ?>
                                                            <span class="badge badge-light border mt-1"><?= htmlspecialchars($row->nama_divisi) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row->masukan_manajemen)): ?>
                                                            <div class="evl-feedback-pill">
                                                                <strong><i class="fas fa-user-tie mr-1"></i> Masukan kepada Manajemen:</strong>
                                                                <?= htmlspecialchars(mb_strimwidth($row->masukan_manajemen, 0, 140, '...')) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($row->masukan_karyawan)): ?>
                                                            <div class="evl-feedback-pill" style="border-left-color:#8b5cf6;">
                                                                <strong><i class="fas fa-users mr-1"></i> Masukan untuk Karyawan (Seluruh Karyawan):</strong>
                                                                <?= htmlspecialchars(mb_strimwidth($row->masukan_karyawan, 0, 140, '...')) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($row->masukan_lingkungan)): ?>
                                                            <div class="evl-feedback-pill" style="border-left-color:#10b981;">
                                                                <strong><i class="fas fa-building mr-1"></i> Masukan untuk Lingkungan Kerja:</strong>
                                                                <?= htmlspecialchars(mb_strimwidth($row->masukan_lingkungan, 0, 140, '...')) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($row->masukan_pekerjaan)): ?>
                                                            <div class="evl-feedback-pill" style="border-left-color:#f59e0b;">
                                                                <strong><i class="fas fa-briefcase mr-1"></i> Masukan untuk Pekerjaan:</strong>
                                                                <?= htmlspecialchars(mb_strimwidth($row->masukan_pekerjaan, 0, 140, '...')) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if (!empty($row->file_lampiran)): ?>
                                                            <a href="<?= htmlspecialchars($row->file_lampiran) ?>" target="_blank" class="btn btn-xs btn-outline-primary mb-1 d-block">
                                                                <i class="fas fa-paperclip mr-1"></i> Berkas
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (!empty($row->link_lampiran)): ?>
                                                            <a href="<?= htmlspecialchars($row->link_lampiran) ?>" target="_blank" class="btn btn-xs btn-outline-info d-block">
                                                                <i class="fas fa-external-link-alt mr-1"></i> Link
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (empty($row->file_lampiran) && empty($row->link_lampiran)): ?>
                                                            <span class="text-muted small">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ((int)$row->butuh_konseling === 1): ?>
                                                            <?php
                                                            $badgeKons = 'badge-warning';
                                                            if ($row->status_konseling === 'Terjadwal') $badgeKons = 'badge-primary';
                                                            elseif ($row->status_konseling === 'Selesai') $badgeKons = 'badge-success';
                                                            elseif ($row->status_konseling === 'Dibatalkan') $badgeKons = 'badge-danger';
                                                            ?>
                                                            <span class="badge <?= $badgeKons ?> mb-1">
                                                                <i class="fas fa-calendar-check mr-1"></i> <?= htmlspecialchars($row->status_konseling) ?>
                                                            </span>
                                                            <?php if (!empty($row->tgl_jadwal_konseling)): ?>
                                                                <div class="small text-dark font-weight-bold">
                                                                    Jadwal: <?= date('d/m/Y', strtotime($row->tgl_jadwal_konseling)) ?> <?= htmlspecialchars($row->jam_jadwal_konseling ?: '') ?>
                                                                </div>
                                                                <?php if (!empty($row->lokasi_konseling)): ?>
                                                                    <div class="small text-muted"><i class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($row->lokasi_konseling) ?></div>
                                                                <?php endif; ?>
                                                            <?php elseif (!empty($row->tgl_usulan_konseling)): ?>
                                                                <div class="small text-muted">
                                                                    Usulan: <?= date('d/m/Y', strtotime($row->tgl_usulan_konseling)) ?> <?= htmlspecialchars($row->jam_usulan_konseling ?: '') ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="badge badge-light border text-muted">Tidak Butuh Konseling</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-primary mb-1"
                                                            onclick='openDetailDanTindakLanjut(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                            <i class="fas fa-calendar-alt mr-1"></i> Detail & Konseling
                                                        </button>
                                                        <a href="<?= base_url('evaluasi_lisan/hapus/' . (int)$row->id_evaluasi_lisan) ?>"
                                                           class="btn btn-sm btn-outline-danger mb-1"
                                                           onclick="return confirm('Yakin ingin menghapus data evaluasi lisan ini?')"
                                                           title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- TAB 2 (ATAU UTAMA BAGI KARYAWAN): RIWAYAT EVALUASI LISAN SAYA -->
                    <div class="tab-pane fade <?= !$canManage ? 'show active' : '' ?>" id="tabEvaluasiSaya" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="font-weight-bold mb-0 text-dark">
                                <i class="fas fa-history text-primary mr-1"></i> Riwayat Evaluasi Lisan & Pengajuan Konseling Saya
                            </h5>
                            <button type="button" class="btn btn-primary btn-sm font-weight-bold" onclick="openFormEvaluasiBaru()">
                                <i class="fas fa-plus mr-1"></i> Tambah Evaluasi Lisan
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="45" class="text-center">No</th>
                                        <th width="140">Kode & Tanggal</th>
                                        <th>Ringkasan Masukan (Manajemen, Karyawan, Lingkungan, Pekerjaan)</th>
                                        <th width="120" class="text-center">Lampiran</th>
                                        <th width="230">Jadwal Konseling HRGA</th>
                                        <th width="140" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($myEvaluasi)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-comment-dots fa-2x mb-2 d-block opacity-50"></i>
                                                Anda belum pernah mengirim Evaluasi Lisan. Klik tombol <strong>"Isi Evaluasi Lisan & Konseling"</strong> di kanan atas untuk membuat baru.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $noMy = 1; foreach ($myEvaluasi as $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $noMy++ ?></td>
                                                <td>
                                                    <div class="font-weight-bold text-primary"><?= htmlspecialchars($row->kode_evaluasi) ?></div>
                                                    <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i><?= date('d/m/Y', strtotime($row->tanggal_evaluasi)) ?></small>
                                                    <div class="mt-1">
                                                        <?php
                                                        $badgeEval = 'badge-secondary';
                                                        if ($row->status_evaluasi === 'Ditinjau') $badgeEval = 'badge-info';
                                                        elseif ($row->status_evaluasi === 'Selesai') $badgeEval = 'badge-success';
                                                        ?>
                                                        <span class="badge <?= $badgeEval ?>"><?= htmlspecialchars($row->status_evaluasi) ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (!empty($row->masukan_manajemen)): ?>
                                                        <div class="evl-feedback-pill">
                                                            <strong>Masukan kepada Manajemen:</strong>
                                                            <?= htmlspecialchars(mb_strimwidth($row->masukan_manajemen, 0, 140, '...')) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($row->masukan_karyawan)): ?>
                                                        <div class="evl-feedback-pill" style="border-left-color:#8b5cf6;">
                                                            <strong>Masukan untuk Karyawan:</strong>
                                                            <?= htmlspecialchars(mb_strimwidth($row->masukan_karyawan, 0, 140, '...')) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($row->masukan_lingkungan)): ?>
                                                        <div class="evl-feedback-pill" style="border-left-color:#10b981;">
                                                            <strong>Masukan untuk Lingkungan Kerja:</strong>
                                                            <?= htmlspecialchars(mb_strimwidth($row->masukan_lingkungan, 0, 140, '...')) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($row->masukan_pekerjaan)): ?>
                                                        <div class="evl-feedback-pill" style="border-left-color:#f59e0b;">
                                                            <strong>Masukan untuk Pekerjaan:</strong>
                                                            <?= htmlspecialchars(mb_strimwidth($row->masukan_pekerjaan, 0, 140, '...')) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($row->tanggapan_manajemen)): ?>
                                                        <div class="alert alert-info py-1 px-2 mb-0 mt-2 small">
                                                            <strong><i class="fas fa-reply mr-1"></i> Tanggapan HRGA/Manajemen:</strong>
                                                            <?= htmlspecialchars($row->tanggapan_manajemen) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!empty($row->file_lampiran)): ?>
                                                        <a href="<?= htmlspecialchars($row->file_lampiran) ?>" target="_blank" class="btn btn-xs btn-outline-primary mb-1 d-block">
                                                            <i class="fas fa-paperclip mr-1"></i> Berkas
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($row->link_lampiran)): ?>
                                                        <a href="<?= htmlspecialchars($row->link_lampiran) ?>" target="_blank" class="btn btn-xs btn-outline-info d-block">
                                                            <i class="fas fa-external-link-alt mr-1"></i> Link
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (empty($row->file_lampiran) && empty($row->link_lampiran)): ?>
                                                        <span class="text-muted small">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ((int)$row->butuh_konseling === 1): ?>
                                                        <?php
                                                        $badgeKons = 'badge-warning';
                                                        if ($row->status_konseling === 'Terjadwal') $badgeKons = 'badge-primary';
                                                        elseif ($row->status_konseling === 'Selesai') $badgeKons = 'badge-success';
                                                        elseif ($row->status_konseling === 'Dibatalkan') $badgeKons = 'badge-danger';
                                                        ?>
                                                        <span class="badge <?= $badgeKons ?> mb-1">
                                                            <?= htmlspecialchars($row->status_konseling) ?>
                                                        </span>
                                                        <?php if (!empty($row->tgl_jadwal_konseling)): ?>
                                                            <div class="small font-weight-bold text-primary">
                                                                <i class="far fa-calendar-check mr-1"></i>
                                                                <?= date('d/m/Y', strtotime($row->tgl_jadwal_konseling)) ?> <?= htmlspecialchars($row->jam_jadwal_konseling ?: '') ?>
                                                            </div>
                                                            <div class="small text-muted">
                                                                <?= htmlspecialchars($row->metode_konseling ?: 'Tatap Muka') ?>
                                                                <?= !empty($row->lokasi_konseling) ? ' • ' . htmlspecialchars($row->lokasi_konseling) : '' ?>
                                                            </div>
                                                        <?php elseif (!empty($row->tgl_usulan_konseling)): ?>
                                                            <div class="small text-muted">
                                                                Usulan Anda: <?= date('d/m/Y', strtotime($row->tgl_usulan_konseling)) ?> <?= htmlspecialchars($row->jam_usulan_konseling ?: '') ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-light border text-muted">Tidak Diajukan</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info mb-1"
                                                        onclick='openDetailDanTindakLanjut(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                        <i class="fas fa-eye"></i> Detail
                                                    </button>
                                                    <?php if ($row->status_evaluasi === 'Terkirim'): ?>
                                                        <button type="button" class="btn btn-sm btn-warning mb-1"
                                                            onclick='openEditEvaluasi(<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </section>

    </div>
</div>

<!-- MODAL INPUT / EDIT EVALUASI LISAN & KONSELING HRGA -->
<div class="modal fade" id="modalFormEvaluasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:12px;">
            <form method="post" action="<?= base_url('evaluasi_lisan/simpan') ?>" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="id_evaluasi_lisan" id="form_id_evaluasi_lisan" value="0">

                <div class="modal-header bg-primary text-white" style="border-radius:12px 12px 0 0;">
                    <h5 class="modal-title font-weight-bold text-white" id="judulModalEvaluasi">
                        <i class="fas fa-comment-medical mr-1"></i> Form Evaluasi Lisan & Pengajuan Konseling HRGA
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body" style="max-height:78vh; overflow-y:auto;">

                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold small text-uppercase text-muted">Nama Karyawan</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($me->nama ?? sessNama()) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold small text-uppercase text-muted">Tanggal Evaluasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_evaluasi" id="form_tanggal_evaluasi" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <!-- BAGIAN 1: EVALUASI LISAN (4 KATEGORI MASUKAN) -->
                    <div class="evl-section-box">
                        <div class="evl-section-title">
                            <i class="fas fa-clipboard-list text-primary"></i>
                            <span>1. Evaluasi Lisan (Masukan Umum / Penilaian Bersama)</span>
                        </div>
                        <p class="text-muted small mb-3">
                            Silakan tuliskan masukan atau evaluasi Anda pada kategori di bawah ini (bersifat umum untuk peningkatan bersama, minimal isi salah satu):
                        </p>

                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-user-tie text-primary mr-1"></i> a. Masukan kepada Manajemen
                            </label>
                            <textarea name="masukan_manajemen" id="form_masukan_manajemen" rows="3" class="form-control"
                                placeholder="Tuliskan masukan, saran kebijakan, atau arahan untuk Manajemen..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-users text-purple mr-1" style="color:#7c3aed;"></i> b. Masukan untuk Karyawan (Seluruh Karyawan)
                            </label>
                            <textarea name="masukan_karyawan" id="form_masukan_karyawan" rows="3" class="form-control"
                                placeholder="Tuliskan masukan yang bersifat umum untuk seluruh rekan karyawan (kedisiplinan, kerja sama tim, komunikasi, dll)..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-building text-success mr-1"></i> c. Masukan untuk Lingkungan Kerja
                            </label>
                            <textarea name="masukan_lingkungan" id="form_masukan_lingkungan" rows="3" class="form-control"
                                placeholder="Tuliskan masukan mengenai fasilitas kantor, kenyamanan, kebersihan, atau suasana lingkungan kerja..."></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark">
                                <i class="fas fa-briefcase text-warning mr-1"></i> d. Masukan untuk Pekerjaan
                            </label>
                            <textarea name="masukan_pekerjaan" id="form_masukan_pekerjaan" rows="3" class="form-control"
                                placeholder="Tuliskan masukan terkait alur kerja (SOP), beban kerja, peralatan, atau koordinasi pekerjaan..."></textarea>
                        </div>
                    </div>

                    <!-- BAGIAN 2: LAMPIRAN PENDUKUNG -->
                    <div class="evl-section-box">
                        <div class="evl-section-title">
                            <i class="fas fa-paperclip text-info"></i>
                            <span>2. Lampiran Pendukung (Opsional)</span>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 form-group mb-2">
                                <label class="small font-weight-bold">Upload Berkas Pendukung (PDF/JPG/PNG/DOC/XLS, Maks 10MB)</label>
                                <input type="file" name="file_lampiran" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                <small id="info_file_existing" class="text-primary d-none mt-1"></small>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="small font-weight-bold">Atau Link Lampiran Eksternal (GDrive / Dokumen)</label>
                                <input type="url" name="link_lampiran" id="form_link_lampiran" class="form-control" placeholder="https://drive.google.com/...">
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 3: PENJADWALAN KONSELING DENGAN HRGA -->
                    <div class="evl-section-box mb-0" style="background:#f0f9ff; border-color:#bae6fd;">
                        <div class="evl-section-title" style="color:#0369a1;">
                            <i class="fas fa-calendar-check"></i>
                            <span>3. Butuh Penjadwalan Konseling dengan HRGA?</span>
                        </div>

                        <div class="form-group mb-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="konseling_tidak" name="butuh_konseling" value="0" class="custom-control-input" checked onchange="toggleBoxKonseling()">
                                <label class="custom-control-label font-weight-bold" for="konseling_tidak">Tidak, Cukup Masukan Tertulis</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="konseling_ya" name="butuh_konseling" value="1" class="custom-control-input" onchange="toggleBoxKonseling()">
                                <label class="custom-control-label font-weight-bold text-primary" for="konseling_ya">Ya, Saya Butuh Penjadwalan Konseling dengan HRGA</label>
                            </div>
                        </div>

                        <div id="boxInputKonseling" class="mt-3 pt-3 border-top" style="display:none; border-color:#bae6fd !important;">
                            <div class="form-row">
                                <div class="col-md-4 form-group">
                                    <label class="small font-weight-bold">Usulan Tanggal Konseling</label>
                                    <input type="date" name="tgl_usulan_konseling" id="form_tgl_usulan_konseling" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="small font-weight-bold">Usulan Jam Konseling</label>
                                    <input type="time" name="jam_usulan_konseling" id="form_jam_usulan_konseling" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="small font-weight-bold">Metode Konseling</label>
                                    <select name="metode_konseling" id="form_metode_konseling" class="form-control">
                                        <option value="Tatap Muka">Tatap Muka (Offline di Kantor)</option>
                                        <option value="Online">Online (Zoom / Google Meet / Call)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold">Topik / Hal yang Ingin Dikonselingkan bersama HRGA</label>
                                <textarea name="topik_konseling" id="form_topik_konseling" rows="2" class="form-control"
                                    placeholder="Tuliskan secara singkat topik atau hal yang ingin dibahas saat sesi konseling dengan HRGA..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-paper-plane mr-1"></i> Simpan & Kirim Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DETAIL & TINDAK LANJUT / PENJADWALAN KONSELING HRGA -->
<div class="modal fade" id="modalDetailEvaluasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:12px;">
            <div class="modal-header bg-dark text-white" style="border-radius:12px 12px 0 0;">
                <h5 class="modal-title font-weight-bold text-white">
                    <i class="fas fa-file-alt mr-1"></i> Detail Evaluasi Lisan & Konseling HRGA (<span id="det_kode"></span>)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body" style="max-height:80vh; overflow-y:auto;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Karyawan:</small>
                        <strong class="text-dark" id="det_nama_karyawan" style="font-size:15px;">-</strong>
                        <div class="small text-muted" id="det_jabatan_divisi">-</div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <small class="text-muted d-block">Tanggal Evaluasi:</small>
                        <strong id="det_tanggal">-</strong>
                        <div class="mt-1" id="det_status_badges"></div>
                    </div>
                </div>

                <hr class="my-2">

                <h6 class="font-weight-bold text-primary mt-3 mb-2">
                    <i class="fas fa-comments mr-1"></i> Isi Evaluasi Lisan (4 Kategori Masukan)
                </h6>

                <div class="evl-detail-box">
                    <div class="evl-detail-label"><i class="fas fa-user-tie mr-1"></i> 1. Masukan kepada Manajemen</div>
                    <div class="evl-detail-text" id="det_masukan_manajemen">-</div>
                </div>

                <div class="evl-detail-box" style="border-left-color:#8b5cf6;">
                    <div class="evl-detail-label"><i class="fas fa-users mr-1"></i> 2. Masukan untuk Karyawan (Seluruh Karyawan)</div>
                    <div class="evl-detail-text" id="det_masukan_karyawan">-</div>
                </div>

                <div class="evl-detail-box" style="border-left-color:#10b981;">
                    <div class="evl-detail-label"><i class="fas fa-building mr-1"></i> 3. Masukan untuk Lingkungan Kerja</div>
                    <div class="evl-detail-text" id="det_masukan_lingkungan">-</div>
                </div>

                <div class="evl-detail-box" style="border-left-color:#f59e0b;">
                    <div class="evl-detail-label"><i class="fas fa-briefcase mr-1"></i> 4. Masukan untuk Pekerjaan</div>
                    <div class="evl-detail-text" id="det_masukan_pekerjaan">-</div>
                </div>

                <div class="evl-detail-box" style="border-left-color:#0ea5e9;">
                    <div class="evl-detail-label"><i class="fas fa-paperclip mr-1"></i> Lampiran Pendukung</div>
                    <div id="det_lampiran">-</div>
                </div>

                <div class="evl-detail-box" style="border-left-color:#14b8a6; background:#f0fdfa;">
                    <div class="evl-detail-label"><i class="fas fa-user-friends mr-1"></i> Pengajuan Konseling dari Karyawan</div>
                    <div id="det_info_konseling_karyawan" class="small text-dark">-</div>
                </div>

                <?php if (!$canManage): ?>
                    <!-- Tampilan Read-Only Hasil Jadwal & Tanggapan untuk Karyawan -->
                    <div class="evl-section-box mt-3" style="background:#eff6ff; border-color:#bfdbfe;">
                        <div class="evl-section-title text-primary">
                            <i class="fas fa-calendar-check"></i>
                            <span>Jadwal Konseling & Tanggapan HRGA / Manajemen</span>
                        </div>
                        <div class="row small">
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-block">Jadwal Konseling yang Ditetapkan:</span>
                                <strong id="ro_jadwal_konseling" class="text-dark">-</strong>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-block">Metode & Lokasi / Link:</span>
                                <strong id="ro_lokasi_konseling" class="text-dark">-</strong>
                            </div>
                            <div class="col-md-12 mb-2">
                                <span class="text-muted d-block">Tanggapan Manajemen / HRGA:</span>
                                <div id="ro_tanggapan_manajemen" class="p-2 bg-white rounded border mt-1">-</div>
                            </div>
                            <div class="col-md-12">
                                <span class="text-muted d-block">Catatan Hasil Konseling:</span>
                                <div id="ro_catatan_hasil_konseling" class="p-2 bg-white rounded border mt-1">-</div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Form Tindak Lanjut & Penjadwalan Konseling (Khusus Manajemen & HRGA) -->
                    <form method="post" action="<?= base_url('evaluasi_lisan/tindak_lanjut') ?>" class="mt-3 p-3 rounded" style="background:#eff6ff; border:1px solid #93c5fd;">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="id_evaluasi_lisan" id="tl_id_evaluasi_lisan" value="0">

                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-user-check mr-1"></i> Form Penjadwalan Konseling HRGA & Tindak Lanjut Manajemen
                        </h6>

                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Status Evaluasi Lisan</label>
                                <select name="status_evaluasi" id="tl_status_evaluasi" class="form-control form-control-sm">
                                    <option value="Terkirim">Terkirim</option>
                                    <option value="Ditinjau">Ditinjau</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Butuh Konseling HRGA?</label>
                                <select name="butuh_konseling" id="tl_butuh_konseling" class="form-control form-control-sm">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya, Jadwalkan Konseling</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Status Konseling HRGA</label>
                                <select name="status_konseling" id="tl_status_konseling" class="form-control form-control-sm">
                                    <option value="Tidak Ada">Tidak Ada</option>
                                    <option value="Menunggu Jadwal">Menunggu Jadwal</option>
                                    <option value="Terjadwal">Terjadwal</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Tanggal Konseling Ditetapkan</label>
                                <input type="date" name="tgl_jadwal_konseling" id="tl_tgl_jadwal_konseling" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Jam Konseling Ditetapkan</label>
                                <input type="time" name="jam_jadwal_konseling" id="tl_jam_jadwal_konseling" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Metode Konseling</label>
                                <select name="metode_konseling" id="tl_metode_konseling" class="form-control form-control-sm">
                                    <option value="Tatap Muka">Tatap Muka</option>
                                    <option value="Online">Online</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Ruangan Konseling / Link Meeting</label>
                            <input type="text" name="lokasi_konseling" id="tl_lokasi_konseling" class="form-control form-control-sm"
                                placeholder="Contoh: Ruang HRGA Lt. 2 / Link Google Meet...">
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Tanggapan Manajemen / HRGA atas Masukan Karyawan</label>
                            <textarea name="tanggapan_manajemen" id="tl_tanggapan_manajemen" rows="2" class="form-control form-control-sm"
                                placeholder="Tuliskan tanggapan atau tindak lanjut atas masukan yang disampaikan..."></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold">Catatan Hasil Konseling (Diisi setelah konseling terlaksana)</label>
                            <textarea name="catatan_hasil_konseling" id="tl_catatan_hasil_konseling" rows="2" class="form-control form-control-sm"
                                placeholder="Tuliskan rangkuman atau hasil sesi konseling dengan karyawan..."></textarea>
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Jadwal Konseling & Tindak Lanjut (Kirim Notif WA)
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleBoxKonseling() {
        var butuh = document.getElementById('konseling_ya').checked;
        document.getElementById('boxInputKonseling').style.display = butuh ? 'block' : 'none';
    }

    function openFormEvaluasiBaru() {
        document.getElementById('judulModalEvaluasi').innerHTML = '<i class="fas fa-comment-medical mr-1"></i> Form Evaluasi Lisan & Pengajuan Konseling HRGA';
        document.getElementById('form_id_evaluasi_lisan').value = '0';
        document.getElementById('form_tanggal_evaluasi').value = '<?= date('Y-m-d') ?>';
        document.getElementById('form_masukan_manajemen').value = '';
        document.getElementById('form_masukan_karyawan').value = '';
        document.getElementById('form_masukan_lingkungan').value = '';
        document.getElementById('form_masukan_pekerjaan').value = '';
        document.getElementById('form_link_lampiran').value = '';
        document.getElementById('info_file_existing').classList.add('d-none');
        document.getElementById('konseling_tidak').checked = true;
        document.getElementById('form_tgl_usulan_konseling').value = '';
        document.getElementById('form_jam_usulan_konseling').value = '';
        document.getElementById('form_metode_konseling').value = 'Tatap Muka';
        document.getElementById('form_topik_konseling').value = '';
        toggleBoxKonseling();
        $('#modalFormEvaluasi').modal('show');
    }

    function openEditEvaluasi(row) {
        document.getElementById('judulModalEvaluasi').innerHTML = '<i class="fas fa-edit mr-1"></i> Edit Evaluasi Lisan (' + (row.kode_evaluasi || '') + ')';
        document.getElementById('form_id_evaluasi_lisan').value = row.id_evaluasi_lisan || '0';
        document.getElementById('form_tanggal_evaluasi').value = row.tanggal_evaluasi || '<?= date('Y-m-d') ?>';
        document.getElementById('form_masukan_manajemen').value = row.masukan_manajemen || '';
        document.getElementById('form_masukan_karyawan').value = row.masukan_karyawan || '';
        document.getElementById('form_masukan_lingkungan').value = row.masukan_lingkungan || '';
        document.getElementById('form_masukan_pekerjaan').value = row.masukan_pekerjaan || '';
        document.getElementById('form_link_lampiran').value = row.link_lampiran || '';

        var infoFile = document.getElementById('info_file_existing');
        if (row.file_lampiran) {
            infoFile.classList.remove('d-none');
            infoFile.innerHTML = 'Berkas saat ini sudah terlampir. Biarkan kosong jika tidak ingin mengganti.';
        } else {
            infoFile.classList.add('d-none');
        }

        if (parseInt(row.butuh_konseling || 0, 10) === 1) {
            document.getElementById('konseling_ya').checked = true;
        } else {
            document.getElementById('konseling_tidak').checked = true;
        }
        document.getElementById('form_tgl_usulan_konseling').value = row.tgl_usulan_konseling || '';
        document.getElementById('form_jam_usulan_konseling').value = row.jam_usulan_konseling || '';
        document.getElementById('form_metode_konseling').value = row.metode_konseling || 'Tatap Muka';
        document.getElementById('form_topik_konseling').value = row.topik_konseling || '';
        toggleBoxKonseling();
        $('#modalFormEvaluasi').modal('show');
    }

    function openDetailDanTindakLanjut(row) {
        document.getElementById('det_kode').textContent = row.kode_evaluasi || '-';
        document.getElementById('det_nama_karyawan').textContent = row.nama_karyawan || '-';
        document.getElementById('det_jabatan_divisi').textContent = (row.jabatan || '-') + (row.nama_divisi ? ' • ' + row.nama_divisi : '');
        document.getElementById('det_tanggal').textContent = row.tanggal_evaluasi || '-';

        document.getElementById('det_masukan_manajemen').textContent = row.masukan_manajemen || '(Tidak ada masukan)';
        document.getElementById('det_masukan_karyawan').textContent = row.masukan_karyawan || '(Tidak ada masukan)';
        document.getElementById('det_masukan_lingkungan').textContent = row.masukan_lingkungan || '(Tidak ada masukan)';
        document.getElementById('det_masukan_pekerjaan').textContent = row.masukan_pekerjaan || '(Tidak ada masukan)';

        var lampHtml = '';
        if (row.file_lampiran) {
            lampHtml += '<a href="' + row.file_lampiran + '" target="_blank" class="btn btn-xs btn-primary mr-2"><i class="fas fa-download mr-1"></i> Lihat Berkas Lampiran</a>';
        }
        if (row.link_lampiran) {
            lampHtml += '<a href="' + row.link_lampiran + '" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-external-link-alt mr-1"></i> Buka Link Lampiran</a>';
        }
        document.getElementById('det_lampiran').innerHTML = lampHtml || '<span class="text-muted">Tidak ada lampiran pendukung</span>';

        if (parseInt(row.butuh_konseling || 0, 10) === 1) {
            var infoKons = '<strong>Ya, Membutuhkan Konseling HRGA</strong><br>' +
                'Usulan Tanggal & Jam: <strong>' + (row.tgl_usulan_konseling || '-') + ' ' + (row.jam_usulan_konseling || '') + '</strong> (' + (row.metode_konseling || 'Tatap Muka') + ')<br>' +
                'Topik Konseling: ' + (row.topik_konseling || '-');
            document.getElementById('det_info_konseling_karyawan').innerHTML = infoKons;
        } else {
            document.getElementById('det_info_konseling_karyawan').innerHTML = '<span class="text-muted">Karyawan tidak mengajukan sesi konseling lanjutan.</span>';
        }

        // Jika form tindak lanjut ada (untuk Manajemen & HRGA)
        var tlId = document.getElementById('tl_id_evaluasi_lisan');
        if (tlId) {
            tlId.value = row.id_evaluasi_lisan || '0';
            document.getElementById('tl_status_evaluasi').value = row.status_evaluasi || 'Terkirim';
            document.getElementById('tl_butuh_konseling').value = parseInt(row.butuh_konseling || 0, 10) === 1 ? '1' : '0';
            document.getElementById('tl_status_konseling').value = row.status_konseling || 'Tidak Ada';
            document.getElementById('tl_tgl_jadwal_konseling').value = row.tgl_jadwal_konseling || row.tgl_usulan_konseling || '';
            document.getElementById('tl_jam_jadwal_konseling').value = row.jam_jadwal_konseling || row.jam_usulan_konseling || '';
            document.getElementById('tl_metode_konseling').value = row.metode_konseling || 'Tatap Muka';
            document.getElementById('tl_lokasi_konseling').value = row.lokasi_konseling || '';
            document.getElementById('tl_tanggapan_manajemen').value = row.tanggapan_manajemen || '';
            document.getElementById('tl_catatan_hasil_konseling').value = row.catatan_hasil_konseling || '';
        }

        // Jika tampilan Read-Only untuk karyawan
        var roJadwal = document.getElementById('ro_jadwal_konseling');
        if (roJadwal) {
            roJadwal.textContent = row.tgl_jadwal_konseling
                ? (row.tgl_jadwal_konseling + ' ' + (row.jam_jadwal_konseling || '') + ' (' + (row.status_konseling || '') + ')')
                : (row.status_konseling || 'Belum dijadwalkan');
            document.getElementById('ro_lokasi_konseling').textContent = (row.metode_konseling || '-') + (row.lokasi_konseling ? ' - ' + row.lokasi_konseling : '');
            document.getElementById('ro_tanggapan_manajemen').textContent = row.tanggapan_manajemen || 'Belum ada tanggapan.';
            document.getElementById('ro_catatan_hasil_konseling').textContent = row.catatan_hasil_konseling || '-';
        }

        $('#modalDetailEvaluasi').modal('show');
    }
</script>
