<header class="page-header">
    <h2><i class="fas fa-graduation-cap mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .pk-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        transition: all 0.2s ease;
    }
    .pk-card:hover {
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
    }
    .pk-stat-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .pk-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .nav-tabs-pk {
        border-bottom: 2px solid #e2e8f0 !important;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .nav-tabs-pk > li > a {
        font-weight: 600 !important;
        font-size: 13.5px !important;
        color: #475569 !important;
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px 8px 0 0 !important;
        padding: 11px 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        text-decoration: none !important;
    }
    .nav-tabs-pk > li.active > a,
    .nav-tabs-pk > li > a:hover {
        background: #ffffff !important;
        color: #0f172a !important;
        border-top: 3px solid #059669 !important;
        border-bottom: 2px solid #ffffff !important;
        margin-bottom: -2px !important;
    }
    .badge-grade {
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
    }
    .badge-grade-a { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-grade-b { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .badge-grade-c { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-grade-d { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    @media (max-width: 768px) {
        .pk-stat-card {
            padding: 12px 14px;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        .pk-stat-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        .nav-tabs-pk {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 4px;
            gap: 4px;
        }
        .nav-tabs-pk > li {
            flex: 0 0 auto;
        }
        .nav-tabs-pk > li > a {
            padding: 9px 14px !important;
            font-size: 12px !important;
            white-space: nowrap;
        }
        .pk-card {
            padding: 14px !important;
            border-radius: 12px;
        }
        .top-filter-bar {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
        }
        .top-filter-bar > div:last-child {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .card-actions-row {
            flex-direction: column;
            gap: 8px !important;
        }
        .card-actions-row .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<!-- KPI STATS SUMMARY -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-4 mb-2">
        <div class="pk-stat-card">
            <div>
                <div class="text-muted small font-weight-bold text-uppercase">Total Paket Kuis</div>
                <div style="font-size: 24px; font-weight: 800; color: #059669;">
                    <?= count($list_kuis) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Paket</small>
                </div>
            </div>
            <div class="pk-stat-icon" style="background:#ecfdf5; color:#059669;">
                <i class="fas fa-book-open"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 mb-2">
        <div class="pk-stat-card">
            <div>
                <div class="text-muted small font-weight-bold text-uppercase">Parameter Leaderboard KPI</div>
                <div style="font-size: 17px; font-weight: 800; color: #0284c7; margin-top: 4px;">
                    Bobot 15% <small style="font-size: 11.5px; font-weight: 600; color:#64748b;">(Pilar Mutu Kinerja)</small>
                </div>
            </div>
            <div class="pk-stat-icon" style="background:#f0f9ff; color:#0284c7;">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-4 mb-2">
        <div class="pk-stat-card">
            <div>
                <div class="text-muted small font-weight-bold text-uppercase">Periode Aktif</div>
                <div style="font-size: 19px; font-weight: 800; color: #475569; margin-top: 4px;">
                    Tahun <?= htmlspecialchars($tahun) ?>
                </div>
            </div>
            <div class="pk-stat-icon" style="background:#f8fafc; color:#64748b; border:1px solid #e2e8f0;">
                <i class="far fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- WORKSPACE TABS -->
<div class="tabs">
    <ul class="nav nav-tabs nav-tabs-pk" role="tablist">
        <li class="<?= ($active_tab === 'rekap') ? '' : 'active' ?>">
            <a href="#tab_kuis_online" data-toggle="tab">
                <i class="fas fa-laptop-code mr-2 text-success"></i> Kuis Online (CBT)
                <span class="badge badge-success ml-2"><?= count($list_kuis) ?></span>
            </a>
        </li>
        <?php if ($is_admin_hrd): ?>
        <li class="<?= ($active_tab === 'rekap') ? 'active' : '' ?>">
            <a href="#tab_rekap_nilai" data-toggle="tab">
                <i class="fas fa-table mr-2 text-primary"></i> Rekap Nilai Per Semester
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="tab-content" style="background: transparent; border: none; padding: 0;">
        <!-- ================= TAB 1: KUIS ONLINE (CBT) ================= -->
        <div id="tab_kuis_online" class="tab-pane <?= ($active_tab === 'rekap') ? '' : 'active' ?>" role="tabpanel">
            <?php if (!empty($is_exempt_pk)): ?>
                <div class="alert alert-light border mb-3 d-flex align-items-center" style="background: #f8fafc; border-left: 4px solid #64748b !important; border-radius: 8px;">
                    <i class="fas fa-info-circle text-secondary mr-3" style="font-size: 24px;"></i>
                    <div style="font-size: 13px; color: #334155;">
                        <strong>Informasi Pengecualian Kuis:</strong> Akun Anda (<strong><?= htmlspecialchars(!empty($me->nama) ? $me->nama : 'Karyawan') ?></strong> - <?= htmlspecialchars(!empty($me->nama_divisi) ? $me->nama_divisi : (!empty($me->jabatan) ? $me->jabatan : 'Umum')) ?>) termasuk dalam posisi/divisi yang <strong>dikecualikan dari kewajiban kuis Product Knowledge</strong>. Pada Leaderboard KPI, bobot penilaian kinerja Anda otomatis dialihkan ke Task Proyek (40%), Laporan (30%), dan Pencapaian (30%) tanpa mengurangi hak podium juara.
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success_msg')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-check-circle mr-2"></i><?= $this->session->flashdata('success_msg') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error_msg')): ?>
                <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i><?= $this->session->flashdata('error_msg') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 p-3 bg-white rounded border top-filter-bar" style="border-radius: 10px;">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-tasks text-success mr-2"></i> Daftar Kuis & Ujian Product Knowledge</h5>
                    <small class="text-muted">Karyawan dapat mengerjakan soal ujian pemahaman produk secara langsung pada jadwal yang ditentukan. Skor otomatis terhitung dan tersimpan ke KPI.</small>
                </div>
                <div class="d-flex align-items-center flex-wrap mt-2 mt-md-0" style="gap: 8px;">
                    <form method="get" class="form-inline">
                        <select name="tahun" class="form-control form-control-sm mr-1" onchange="this.form.submit()">
                            <?php for ($y = date('Y') + 1; $y >= 2024; $y--): ?>
                                <option value="<?= $y ?>" <?= (int)$tahun === $y ? 'selected' : '' ?>>Tahun <?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                    <?php if ($is_admin_hrd): ?>
                        <button type="button" class="btn btn-sm btn-success font-weight-bold shadow-sm" id="btnTambahKuisBaru">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Paket Kuis Baru
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (empty($list_kuis)): ?>
                <div class="p-5 text-center bg-white rounded border" style="border-radius: 12px;">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Belum Ada Kuis untuk Periode Ini</h5>
                    <p class="text-muted small">
                        <?php if ($is_admin_hrd): ?>
                            Silakan klik tombol <strong>"Buat Paket Kuis Baru"</strong> di atas untuk membuat soal kuis pilihan ganda bagi karyawan.
                        <?php else: ?>
                            Belum ada jadwal kuis online yang dibuka oleh HRD untuk periode tahun <?= htmlspecialchars($tahun) ?>.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($list_kuis as $kuis): 
                        $namaBulanList = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        $namaBulan = $namaBulanList[(int)$kuis->bulan] ?? ('Bulan ' . $kuis->bulan);
                        $isCompleted = ($kuis->status_peserta === 'selesai');

                        $now = time();
                        $bukaTs = !empty($kuis->waktu_buka) ? strtotime($kuis->waktu_buka) : null;
                        $tutupTs = !empty($kuis->waktu_tutup) ? strtotime($kuis->waktu_tutup) : null;
                        $isBelumBuka = ($bukaTs && $now < $bukaTs);
                        $isSudahTutup = ($tutupTs && $now > $tutupTs);
                        $isSedangBuka = (!$isBelumBuka && !$isSudahTutup);
                    ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="pk-card p-3 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 11px;">
                                            <i class="far fa-calendar-alt mr-1"></i> <?= $namaBulan . ' ' . $kuis->tahun ?>
                                        </span>
                                        <?php if ($kuis->status === 'draft'): ?>
                                            <span class="badge badge-secondary px-2 py-1" style="font-size: 11px;">Draft</span>
                                        <?php elseif ($isSudahTutup || $kuis->status === 'selesai'): ?>
                                            <span class="badge badge-light border text-danger px-2 py-1" style="font-size: 11px;"><i class="fas fa-times-circle mr-1"></i> Ditutup</span>
                                        <?php elseif ($isBelumBuka): ?>
                                            <span class="badge badge-warning px-2 py-1" style="font-size: 11px; background:#fef3c7; color:#92400e;"><i class="far fa-clock mr-1"></i> Terjadwal</span>
                                        <?php else: ?>
                                            <span class="badge badge-success px-2 py-1" style="font-size: 11px;"><i class="fas fa-check-circle mr-1"></i> Sedang Dibuka</span>
                                        <?php endif; ?>
                                    </div>

                                    <h5 class="font-weight-bold mb-1" style="color: #0f172a; line-height: 1.4;">
                                        <?= htmlspecialchars($kuis->judul_kuis) ?>
                                    </h5>
                                    <?php if (!empty($kuis->deskripsi)): ?>
                                        <p class="text-muted small mb-2" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= htmlspecialchars($kuis->deskripsi) ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="p-2 mb-3 rounded small" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-muted"><i class="fas fa-question-circle text-primary mr-1"></i> Jumlah Soal:</span>
                                            <strong class="text-dark"><?= (int)$kuis->total_soal ?> Soal PG</strong>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-muted"><i class="far fa-clock text-warning mr-1"></i> Durasi Ujian:</span>
                                            <strong class="text-dark"><?= (int)$kuis->durasi_menit ?> Menit</strong>
                                        </div>
                                        <?php if ($bukaTs || $tutupTs): ?>
                                            <div class="d-flex justify-content-between py-1 border-bottom">
                                                <span class="text-muted"><i class="far fa-calendar-check text-success mr-1"></i> Jadwal:</span>
                                                <strong class="text-dark" style="font-size: 11px;">
                                                    <?= $bukaTs ? date('d M H:i', $bukaTs) : 'Sekarang' ?> s/d <?= $tutupTs ? date('d M H:i', $tutupTs) : 'Seterusnya' ?> WIB
                                                </strong>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between py-1">
                                            <span class="text-muted"><i class="fas fa-users text-info mr-1"></i> Peserta Selesai:</span>
                                            <strong class="text-dark"><?= (int)$kuis->total_selesai ?> Karyawan</strong>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <!-- Status Karyawan Login -->
                                    <div class="mb-2">
                                        <?php if ($isCompleted): 
                                            $skorVal = (float)$kuis->skor_saya;
                                            $gradeCls = ($skorVal >= 85) ? 'badge-grade-a' : (($skorVal >= 75) ? 'badge-grade-b' : (($skorVal >= 65) ? 'badge-grade-c' : 'badge-grade-d'));
                                        ?>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                                                <span class="small font-weight-bold text-success"><i class="fas fa-check-double mr-1"></i> Sudah Mengerjakan</span>
                                                <span class="badge-grade <?= $gradeCls ?>">Skor: <?= $skorVal ?> / 100</span>
                                            </div>
                                        <?php elseif ($isSudahTutup || $kuis->status === 'selesai'): ?>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: #f1f5f9; border: 1px solid #cbd5e1;">
                                                <span class="small font-weight-bold text-muted"><i class="fas fa-calendar-times mr-1"></i> Waktu Berakhir</span>
                                                <span class="small text-danger font-weight-bold">Ditutup</span>
                                            </div>
                                        <?php elseif ($isBelumBuka): ?>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: #fffbeb; border: 1px solid #fde68a;">
                                                <span class="small font-weight-bold text-warning"><i class="far fa-clock mr-1"></i> Belum Dibuka</span>
                                                <span class="small text-muted">Mulai <?= date('d M H:i', $bukaTs) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                                                <span class="small font-weight-bold text-success"><i class="fas fa-unlock mr-1"></i> Kuis Tersedia</span>
                                                <span class="small text-muted"><?= (int)$kuis->durasi_menit ?> Menit</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="d-flex flex-wrap card-actions-row" style="gap: 6px;">
                                        <?php if ($isCompleted): ?>
                                            <a href="<?= base_url('knowledge/show/hasil/' . $kuis->id_kuis) ?>" class="btn btn-sm btn-outline-success font-weight-bold flex-grow-1">
                                                <i class="fas fa-eye mr-1"></i> Lihat Hasil Ujian
                                            </a>
                                        <?php elseif ($isSudahTutup || $kuis->status === 'selesai'): ?>
                                            <button type="button" class="btn btn-sm btn-light border text-danger flex-grow-1 font-weight-bold" disabled>
                                                <i class="fas fa-ban mr-1"></i> Kuis Telah Berakhir
                                            </button>
                                        <?php elseif ($isBelumBuka): ?>
                                            <button type="button" class="btn btn-sm btn-light border text-warning flex-grow-1 font-weight-bold" disabled style="background:#fffbeb;">
                                                <i class="far fa-clock mr-1"></i> Dibuka <?= date('d M H:i', $bukaTs) ?> WIB
                                            </button>
                                        <?php elseif ((int)$kuis->total_soal <= 0): ?>
                                            <button type="button" class="btn btn-sm btn-light border text-muted flex-grow-1" disabled>
                                                <i class="fas fa-lock mr-1"></i> Belum Ada Soal
                                            </button>
                                        <?php elseif ($kuis->status === 'aktif'): ?>
                                            <a href="<?= base_url('knowledge/show/kerjakan/' . $kuis->id_kuis) ?>" class="btn btn-sm btn-success font-weight-bold flex-grow-1 shadow-sm">
                                                <i class="fas fa-play mr-1"></i> Kerjakan Kuis Sekarang
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-light border text-muted flex-grow-1" disabled>
                                                <i class="fas fa-lock mr-1"></i> Draft
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($is_admin_hrd): ?>
                                            <a href="<?= base_url('knowledge/show/kelola_kuis/' . $kuis->id_kuis) ?>" class="btn btn-sm btn-primary font-weight-bold" title="Kelola Soal & Rekap Peserta">
                                                <i class="fas fa-cog"></i> Soal (<?= (int)$kuis->total_soal ?>)
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-kuis" data-id="<?= (int)$kuis->id_kuis ?>" data-judul="<?= htmlspecialchars($kuis->judul_kuis) ?>" title="Hapus Kuis">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($is_admin_hrd): ?>
        <!-- ================= TAB 2: REKAP NILAI PER SEMESTER ================= -->
        <div id="tab_rekap_nilai" class="tab-pane <?= ($active_tab === 'rekap') ? 'active' : '' ?>" role="tabpanel">
            <div class="pk-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; gap: 12px;">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-chart-line text-primary mr-2"></i> Rekap Nilai Product Knowledge Per Semester</h5>
                        <small class="text-muted">1 Tahun terbagi menjadi 2 Semester: <strong>Semester 1</strong> (Januari – Juni) dan <strong>Semester 2</strong> (Juli – Desember).</small>
                    </div>
                    <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                        <!-- Filter Tahun -->
                        <form method="get" class="form-inline m-0">
                            <input type="hidden" name="tab" value="rekap">
                            <select name="tahun" class="form-control form-control-sm font-weight-bold" onchange="this.form.submit()">
                                <?php for ($y = date('Y') + 1; $y >= 2024; $y--): ?>
                                    <option value="<?= $y ?>" <?= (int)$tahun === $y ? 'selected' : '' ?>>Tahun <?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </form>

                        <!-- Filter Semester Dropdown (Interaktif) -->
                        <select id="filterSemester" class="form-control form-control-sm font-weight-bold" style="min-width: 160px;">
                            <option value="all">Semua Semester (S1 & S2)</option>
                            <option value="1">Semester 1 (Jan - Jun)</option>
                            <option value="2">Semester 2 (Jul - Des)</option>
                        </select>

                        <!-- Search Karyawan -->
                        <input type="text" id="searchKaryawanPk" class="form-control form-control-sm" placeholder="🔍 Cari nama karyawan..." style="max-width: 170px;">

                        <?php if ($is_admin_hrd): ?>
                            <!-- Tombol Import Excel -->
                            <button type="button" class="btn btn-sm btn-success font-weight-bold shadow-sm" id="btn-show-import-modal">
                                <i class="fas fa-file-excel mr-1"></i> Import Excel
                            </button>
                            <!-- Tombol Download Template -->
                            <a href="<?= base_url('knowledge/download_template?tahun=' . $tahun) ?>" class="btn btn-sm btn-outline-primary font-weight-bold" title="Download Template Excel Resmi">
                                <i class="fas fa-download mr-1"></i> Template
                            </a>
                            <!-- Tombol Input Manual -->
                            <button type="button" id="btn-show-add-form" class="btn btn-sm btn-outline-secondary font-weight-bold">
                                <i class="fas fa-plus mr-1"></i> Manual
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info Badges Semester -->
                <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 12px; font-size: 11.5px;">
                    <span class="d-inline-flex align-items-center">
                        <span style="display:inline-block; width:12px; height:12px; background:#e0f2fe; border:1px solid #38bdf8; border-radius:3px; margin-right:5px;"></span>
                        <strong>Semester 1:</strong> Bulan 1 s/d 6 (Jan – Jun)
                    </span>
                    <span class="d-inline-flex align-items-center">
                        <span style="display:inline-block; width:12px; height:12px; background:#fef3c7; border:1px solid #f59e0b; border-radius:3px; margin-right:5px;"></span>
                        <strong>Semester 2:</strong> Bulan 7 s/d 12 (Jul – Des)
                    </span>
                    <span class="d-inline-flex align-items-center">
                        <span style="display:inline-block; width:12px; height:12px; background:#dcfce7; border:1px solid #22c55e; border-radius:3px; margin-right:5px;"></span>
                        <strong>Rata-rata Tahunan:</strong> Gabungan S1 & S2
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover" id="tableRekapPk" style="font-size: 12.5px;">
                        <thead class="text-center" style="background:#f8fafc;">
                            <tr style="background:#f1f5f9;">
                                <th rowspan="2" style="vertical-align:middle; width:40px;">No</th>
                                <th rowspan="2" style="vertical-align:middle; text-align:left; min-width:170px;">Nama Karyawan</th>
                                <th colspan="7" class="col-smt1" style="background:#e0f2fe; color:#0369a1; border-bottom:2px solid #38bdf8; font-weight:800; font-size:12px;">
                                    <i class="fas fa-calendar-alt mr-1"></i> SEMESTER 1 (Januari – Juni)
                                </th>
                                <th colspan="7" class="col-smt2" style="background:#fef3c7; color:#92400e; border-bottom:2px solid #f59e0b; font-weight:800; font-size:12px;">
                                    <i class="fas fa-calendar-check mr-1"></i> SEMESTER 2 (Juli – Desember)
                                </th>
                                <th rowspan="2" class="col-avg-thn" style="vertical-align:middle; background:#dcfce7; color:#15803d; border-bottom:2px solid #22c55e; min-width:85px; font-weight:800;">
                                    Rata-rata Tahunan
                                </th>
                            </tr>
                            <tr style="font-size:11.5px; background:#f8fafc;">
                                <!-- Semester 1 Columns -->
                                <th class="col-smt1" style="width:42px;">Jan</th>
                                <th class="col-smt1" style="width:42px;">Feb</th>
                                <th class="col-smt1" style="width:42px;">Mar</th>
                                <th class="col-smt1" style="width:42px;">Apr</th>
                                <th class="col-smt1" style="width:42px;">Mei</th>
                                <th class="col-smt1" style="width:42px;">Jun</th>
                                <th class="col-smt1" style="background:#bae6fd; color:#0369a1; font-weight:800; min-width:65px;">Rata S1</th>
                                <!-- Semester 2 Columns -->
                                <th class="col-smt2" style="width:42px;">Jul</th>
                                <th class="col-smt2" style="width:42px;">Agu</th>
                                <th class="col-smt2" style="width:42px;">Sep</th>
                                <th class="col-smt2" style="width:42px;">Okt</th>
                                <th class="col-smt2" style="width:42px;">Nov</th>
                                <th class="col-smt2" style="width:42px;">Des</th>
                                <th class="col-smt2" style="background:#fde68a; color:#92400e; font-weight:800; min-width:65px;">Rata S2</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php if (empty($list_knowledge)): ?>
                                <tr>
                                    <td colspan="17" class="text-center text-muted py-4">Data belum tersedia untuk tahun <?= htmlspecialchars($tahun) ?>.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($list_knowledge as $data): 
                                    $id = encrypt($data['id']);
                                    $namaPegawai = '<a href="' . base_url('knowledge/show/detail/' . $id) . '" class="font-weight-bold text-dark">' . htmlspecialchars($data['nama']) . '</a>';
                                    $subInfo = [];
                                    if (!empty($data['npp'])) $subInfo[] = 'NPP ' . htmlspecialchars($data['npp']);
                                    if (!empty($data['divisi'])) $subInfo[] = htmlspecialchars($data['divisi']);
                                ?>
                                    <tr class="row-karyawan">
                                        <td class="text-muted"><?= $no++ ?></td>
                                        <td style="text-align:left;">
                                            <div><?= $namaPegawai ?></div>
                                            <?php if (!empty($subInfo)): ?>
                                                <small class="text-muted" style="font-size:11px;"><?= implode(' &bull; ', $subInfo) ?></small>
                                            <?php endif; ?>
                                            <?php if (!empty($data['is_exempt'])): ?>
                                                <span class="badge badge-light border text-muted ml-1" style="font-size:9.5px; padding:1px 5px;"><i class="fas fa-shield-alt mr-1"></i> Bebas PK</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Nilai Bulan 1 s/d 6 (Semester 1) -->
                                        <?php for ($i = 1; $i <= 6; $i++): 
                                            $val = isset($data['nilai'][$i]) ? $data['nilai'][$i] : null;
                                        ?>
                                            <td class="col-smt1">
                                                <?php if ($val !== null): 
                                                    $valFloat = (float)$val;
                                                    $pillColor = ($valFloat >= 85) ? '#166534; background:#dcfce7;' : (($valFloat >= 75) ? '#1e40af; background:#eff6ff;' : '#92400e; background:#fef3c7;');
                                                ?>
                                                    <span class="badge px-1 py-1 font-weight-bold" style="color: <?= $pillColor ?>; font-size:11.5px; min-width:30px;"><?= $val ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted" style="opacity:0.4;">-</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endfor; ?>

                                        <!-- Rata-rata Semester 1 -->
                                        <td class="col-smt1 font-weight-bold" style="background:#f0f9ff;">
                                            <?php if ($data['avg_smt1'] !== null): 
                                                $avgS1 = (float)$data['avg_smt1'];
                                                $badgeS1 = ($avgS1 >= 85) ? 'badge-success' : (($avgS1 >= 75) ? 'badge-primary' : 'badge-warning');
                                            ?>
                                                <span class="badge <?= $badgeS1 ?> px-2 py-1" style="font-size:11.5px;"><?= $avgS1 ?></span>
                                            <?php else: ?>
                                                <span class="text-muted" style="opacity:0.4;">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Nilai Bulan 7 s/d 12 (Semester 2) -->
                                        <?php for ($i = 7; $i <= 12; $i++): 
                                            $val = isset($data['nilai'][$i]) ? $data['nilai'][$i] : null;
                                        ?>
                                            <td class="col-smt2">
                                                <?php if ($val !== null): 
                                                    $valFloat = (float)$val;
                                                    $pillColor = ($valFloat >= 85) ? '#166534; background:#dcfce7;' : (($valFloat >= 75) ? '#1e40af; background:#eff6ff;' : '#92400e; background:#fef3c7;');
                                                ?>
                                                    <span class="badge px-1 py-1 font-weight-bold" style="color: <?= $pillColor ?>; font-size:11.5px; min-width:30px;"><?= $val ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted" style="opacity:0.4;">-</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endfor; ?>

                                        <!-- Rata-rata Semester 2 -->
                                        <td class="col-smt2 font-weight-bold" style="background:#fffbeb;">
                                            <?php if ($data['avg_smt2'] !== null): 
                                                $avgS2 = (float)$data['avg_smt2'];
                                                $badgeS2 = ($avgS2 >= 85) ? 'badge-success' : (($avgS2 >= 75) ? 'badge-primary' : 'badge-warning');
                                            ?>
                                                <span class="badge <?= $badgeS2 ?> px-2 py-1" style="font-size:11.5px;"><?= $avgS2 ?></span>
                                            <?php else: ?>
                                                <span class="text-muted" style="opacity:0.4;">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Rata-rata Tahunan -->
                                        <td class="col-avg-thn font-weight-bold" style="background:#f0fdf4;">
                                            <?php if ($data['avg_tahun'] !== null): 
                                                $avgThn = (float)$data['avg_tahun'];
                                                $badgeThn = ($avgThn >= 85) ? '#166534; background:#bbf7d0;' : (($avgThn >= 75) ? '#1e40af; background:#dbeafe;' : '#92400e; background:#fef3c7;');
                                            ?>
                                                <span class="badge px-2 py-1 font-weight-bold" style="color: <?= $badgeThn ?>; font-size:12px;"><?= $avgThn ?></span>
                                            <?php else: ?>
                                                <span class="text-muted" style="opacity:0.4;">-</span>
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
        <?php endif; ?>
    </div>
</div>

<?php if ($is_admin_hrd): ?>
<!-- =========================================================================
     MODAL: BUAT / EDIT PAKET KUIS BARU (KHUSUS HRD / ADMIN)
     ========================================================================= -->
<div class="modal fade" id="modalKuisBaru" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <form id="formKuisBaru">
                <input type="hidden" name="id_kuis" id="modal_id_kuis" value="">
                <div class="modal-header d-flex align-items-center justify-content-between" style="background:#ffffff; border-bottom: 1px solid #e2e8f0; padding: 18px 24px;">
                    <div class="d-flex align-items-center">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #ecfdf5; border: 1.5px solid #a7f3d0; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #059669; margin-right: 12px;">
                            <i class="fas fa-pencil-ruler"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" id="labelModalKuis" style="color: #0f172a;">Buat Paket Kuis Baru</h5>
                            <small class="text-muted">Ujian pemahaman produk online bagi karyawan</small>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" style="font-size: 24px; opacity: 0.6;">&times;</button>
                </div>

                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small text-uppercase">Judul Kuis / Topik Ujian <span class="text-danger">*</span></label>
                        <input type="text" name="judul_kuis" id="modal_judul_kuis" class="form-control" required placeholder="Contoh: Kuis Pemahaman Produk Alerio Smart 4000 & Radiologi">
                    </div>

                    <?php
                    $namaBulanModal = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Bulan Pelaksanaan <span class="text-danger">*</span></label>
                                <select name="bulan" id="modal_bulan_kuis" class="form-control">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= $m ?>" <?= (int)date('n') === $m ? 'selected' : '' ?>><?= $namaBulanModal[$m] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Tahun <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" id="modal_tahun_kuis" class="form-control" value="<?= !empty($tahun) ? (int)$tahun : (int)date('Y') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Durasi Pengerjaan (Menit) <span class="text-danger">*</span></label>
                                <input type="number" name="durasi_menit" id="modal_durasi_kuis" class="form-control" value="30" min="5" max="180" required>
                                <small class="text-muted">Timer akan menghitung mundur otomatis.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Status Kuis <span class="text-danger">*</span></label>
                                <select name="status" id="modal_status_kuis" class="form-control">
                                    <option value="aktif">Aktif (Dapat Dikerjakan)</option>
                                    <option value="draft">Draft (Belum Dibuka)</option>
                                    <option value="selesai">Selesai / Ditutup</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Jadwal Buka (Mulai) <span class="text-muted">(Opsional)</span></label>
                                <input type="datetime-local" name="waktu_buka" id="modal_waktu_buka" class="form-control">
                                <small class="text-muted">Kuis baru bisa diklik sejak jam ini.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Jadwal Tutup (Batas Akhir) <span class="text-muted">(Opsional)</span></label>
                                <input type="datetime-local" name="waktu_tutup" id="modal_waktu_tutup" class="form-control">
                                <small class="text-muted">Kuis tertutup otomatis setelah jam ini.</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small text-uppercase">Deskripsi / Petunjuk Pengerjaan</label>
                        <textarea name="deskripsi" id="modal_deskripsi_kuis" rows="3" class="form-control" placeholder="Tuliskan petunjuk pengerjaan, ruang lingkup materi, atau ketentuan kelulusan..."></textarea>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 d-flex justify-content-between align-items-center" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-light px-4 py-2 font-weight-bold" data-dismiss="modal" style="border: 1px solid #cbd5e1;">Batal</button>
                    <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btnSubmitKuis">
                        <i class="fas fa-check mr-1"></i> Simpan Paket Kuis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL: IMPORT NILAI PRODUCT KNOWLEDGE DARI EXCEL
     ========================================================================= -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 14px; overflow: hidden;">
            <?= form_open_multipart('knowledge/import_excel', ['id' => 'formImportExcel']); ?>
            <div class="modal-header d-flex align-items-center justify-content-between" style="background:#ffffff; border-bottom: 1px solid #e2e8f0; padding: 18px 24px;">
                <div class="d-flex align-items-center">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #ecfdf5; border: 1.5px solid #a7f3d0; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #059669; margin-right: 12px;">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" style="color: #0f172a;">Import Nilai dari Excel</h5>
                        <small class="text-muted">Sinkronisasi data nilai sebelumnya ke Leaderboard KPI</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" style="font-size: 24px; opacity: 0.6;">&times;</button>
            </div>

            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark small text-uppercase">Tahun Periode <span class="text-danger">*</span></label>
                    <select name="tahun" class="form-control font-weight-bold" required>
                        <?php for ($y = date('Y') + 1; $y >= 2024; $y--): ?>
                            <option value="<?= $y ?>" <?= (int)$tahun === $y ? 'selected' : '' ?>>Tahun <?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark small text-uppercase">File Excel / CSV <span class="text-danger">*</span></label>
                    <input type="file" name="berkas_excel" class="form-control" accept=".xlsx, .xls, .csv" required style="padding: 4px;">
                    <small class="text-muted">Format file yang didukung: <strong>.xlsx</strong>, <strong>.xls</strong>, atau <strong>.csv</strong>.</small>
                </div>

                <div class="p-3 rounded mb-0" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 12.5px; color: #334155;">
                    <div class="font-weight-bold text-dark mb-1"><i class="fas fa-lightbulb text-warning mr-1"></i> Format Template:</div>
                    <p class="mb-2">Gunakan format matriks bulanan dari template resmi agar seluruh karyawan & kolom bulan otomatis sinkron.</p>
                    <a href="<?= base_url('knowledge/download_template?tahun=' . $tahun) ?>" class="btn btn-sm btn-light border text-primary font-weight-bold">
                        <i class="fas fa-download mr-1"></i> Download Template Excel (Tahun <?= $tahun ?>)
                    </a>
                </div>
            </div>

            <div class="modal-footer px-4 py-3 d-flex justify-content-between align-items-center" style="background:#f8fafc; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-light px-4 py-2 font-weight-bold" data-dismiss="modal" style="border: 1px solid #cbd5e1;">Batal</button>
                <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btnSubmitImport">
                    <i class="fas fa-upload mr-1"></i> Mulai Import Nilai
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL: INPUT NILAI MANUAL (DILENGKAPI BULAN & TAHUN)
     ========================================================================= -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Input Nilai Manual</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label for="id_pengguna" class="form-control-label font-weight-bold">Nama Karyawan <span class="text-danger">*</span> :</label>
                    <select data-plugin-selectTwo class="form-control populate" id="id_pengguna" name="id_pengguna" required style="width:100%;">
                        <option value="">- Pilih Karyawan -</option>
                        <?php foreach ($list_nama as $row): ?>
                            <option value="<?= $row->pengguna_id ?>"><?= htmlspecialchars($row->nama) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group mb-3">
                            <label for="input_bulan_manual" class="form-control-label font-weight-bold">Bulan Pelaksanaan <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="input_bulan_manual" name="bulan" required>
                                <optgroup label="Semester 1">
                                    <option value="1">Januari (S1)</option>
                                    <option value="2">Februari (S1)</option>
                                    <option value="3">Maret (S1)</option>
                                    <option value="4">April (S1)</option>
                                    <option value="5">Mei (S1)</option>
                                    <option value="6">Juni (S1)</option>
                                </optgroup>
                                <optgroup label="Semester 2">
                                    <option value="7">Juli (S2)</option>
                                    <option value="8">Agustus (S2)</option>
                                    <option value="9">September (S2)</option>
                                    <option value="10">Oktober (S2)</option>
                                    <option value="11">November (S2)</option>
                                    <option value="12">Desember (S2)</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group mb-3">
                            <label for="input_tahun_manual" class="form-control-label font-weight-bold">Tahun <span class="text-danger">*</span> :</label>
                            <input type="number" class="form-control font-weight-bold" id="input_tahun_manual" name="tahun" value="<?= (int)$tahun ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-0">
                    <label for="nilai" class="form-control-label font-weight-bold">Nilai Skor (0 - 100) <span class="text-danger">*</span> :</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control" id="nilai" name="nilai" required placeholder="Contoh: 85.5">
                </div>
            </div>
            <div class="modal-footer px-4 py-3">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save font-weight-bold">Simpan Nilai</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var baseUrl = '<?= base_url() ?>';

    // Buka Modal Kuis Baru
    $('#btnTambahKuisBaru').on('click', function() {
        $('#formKuisBaru')[0].reset();
        $('#modal_id_kuis').val('');
        $('#labelModalKuis').text('Buat Paket Kuis Baru');
        $('#modalKuisBaru').modal('show');
    });

    // Simpan Kuis Baru / Edit
    $('#formKuisBaru').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSubmitKuis');
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

        $.ajax({
            url: baseUrl + 'knowledge/ajax_simpan_kuis',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    alert(res.msg);
                    if (res.data && res.data.id_kuis) {
                        window.location.href = baseUrl + 'knowledge/show/kelola_kuis/' + res.data.id_kuis;
                    } else {
                        location.reload();
                    }
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menyimpan paket kuis.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Hapus Kuis
    $(document).on('click', '.btn-hapus-kuis', function() {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        if (!confirm('Hapus paket kuis "' + judul + '"? Seluruh soal dan data nilai pada kuis ini akan dinonaktifkan.')) {
            return;
        }

        $.ajax({
            url: baseUrl + 'knowledge/ajax_hapus_kuis',
            type: 'POST',
            data: { id_kuis: id },
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menghapus kuis.');
                }
            }
        });
    });

    // Modal Input Manual
    $('#btn-show-add-form').click(function() {
        $('#main-modal #modal-form')[0].reset();
        $('#main-modal #modal-form').attr('action', baseUrl + 'knowledge/add');
        $('#main-modal').modal('show');
    });

    // Modal Import Excel
    $('#btn-show-import-modal').click(function() {
        $('#modalImportExcel').modal('show');
    });

    $('#formImportExcel').on('submit', function() {
        var $btn = $('#btnSubmitImport');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengimpor Data...');
    });

    // Filter Semester Interaktif (S1, S2, atau Semua)
    function applySemesterView(smt) {
        if (smt === '1') {
            $('.col-smt1').show();
            $('.col-smt2').hide();
            $('.col-avg-thn').hide();
        } else if (smt === '2') {
            $('.col-smt1').hide();
            $('.col-smt2').show();
            $('.col-avg-thn').hide();
        } else {
            $('.col-smt1').show();
            $('.col-smt2').show();
            $('.col-avg-thn').show();
        }
    }

    $('#filterSemester').on('change', function() {
        applySemesterView($(this).val());
    });

    // Search Karyawan Cepat
    $('#searchKaryawanPk').on('input', function() {
        var q = $(this).val().toLowerCase();
        $('#tableRekapPk tbody tr.row-karyawan').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(q) > -1);
        });
    });

    // Switch Tab otomatis jika ada hash atau parameter
    if (window.location.hash === '#tab_rekap_nilai' || window.location.search.indexOf('tab=rekap') !== -1) {
        $('a[href="#tab_rekap_nilai"]').tab('show');
    }

    $('.btn-save').click(function() {
        var $form = $('#modal-form');
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    alert('Nilai berhasil disimpan');
                    location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menyimpan nilai.');
                }
            }
        });
    });
});
</script>
