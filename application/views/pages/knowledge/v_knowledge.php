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
</style>

<!-- KPI STATS SUMMARY -->
<div class="row">
    <div class="col-md-4">
        <div class="pk-stat-card">
            <div>
                <div class="text-muted small font-weight-bold text-uppercase">Total Paket Kuis</div>
                <div style="font-size: 26px; font-weight: 800; color: #059669;">
                    <?= count($list_kuis) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Paket</small>
                </div>
            </div>
            <div class="pk-stat-icon" style="background:#ecfdf5; color:#059669;">
                <i class="fas fa-book-open"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="pk-stat-card">
            <div>
                <div class="text-muted small font-weight-bold text-uppercase">Parameter Leaderboard KPI</div>
                <div style="font-size: 18px; font-weight: 800; color: #0284c7; margin-top: 5px;">
                    Bobot 15% <small style="font-size: 12px; font-weight: 600; color:#64748b;">(Pilar Mutu Kinerja)</small>
                </div>
            </div>
            <div class="pk-stat-icon" style="background:#f0f9ff; color:#0284c7;">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="pk-stat-card">
            <div>
                <div class="text-muted small font-weight-bold text-uppercase">Periode Aktif</div>
                <div style="font-size: 20px; font-weight: 800; color: #475569; margin-top: 5px;">
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
        <li class="active">
            <a href="#tab_kuis_online" data-toggle="tab">
                <i class="fas fa-laptop-code mr-2 text-success"></i> Kuis Online (CBT)
                <span class="badge badge-success ml-2"><?= count($list_kuis) ?></span>
            </a>
        </li>
        <li>
            <a href="#tab_rekap_nilai" data-toggle="tab">
                <i class="fas fa-table mr-2 text-primary"></i> Rekap Nilai Bulanan Karyawan
            </a>
        </li>
    </ul>

    <div class="tab-content" style="background: transparent; border: none; padding: 0;">
        <!-- ================= TAB 1: KUIS ONLINE (CBT) ================= -->
        <div id="tab_kuis_online" class="tab-pane active" role="tabpanel">
            <?php if (!empty($is_exempt_pk)): ?>
                <div class="alert alert-light border mb-3 d-flex align-items-center" style="background: #f8fafc; border-left: 4px solid #64748b !important; border-radius: 8px;">
                    <i class="fas fa-info-circle text-secondary mr-3" style="font-size: 24px;"></i>
                    <div style="font-size: 13px; color: #334155;">
                        <strong>Informasi Pengecualian Kuis:</strong> Akun Anda (<strong><?= htmlspecialchars(!empty($me->nama) ? $me->nama : 'Karyawan') ?></strong> - <?= htmlspecialchars(!empty($me->nama_divisi) ? $me->nama_divisi : (!empty($me->jabatan) ? $me->jabatan : 'Umum')) ?>) termasuk dalam posisi/divisi yang <strong>dikecualikan dari kewajiban kuis Product Knowledge</strong>. Pada Leaderboard KPI, bobot penilaian kinerja Anda otomatis dialihkan ke Task Proyek (40%), Laporan (30%), dan Pencapaian (30%) tanpa mengurangi hak podium juara.
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error_msg')): ?>
                <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i><?= $this->session->flashdata('error_msg') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 p-3 bg-white rounded border" style="border-radius: 10px;">
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
                                    <div class="d-flex flex-wrap" style="gap: 6px;">
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

        <!-- ================= TAB 2: REKAP NILAI BULANAN ================= -->
        <div id="tab_rekap_nilai" class="tab-pane" role="tabpanel">
            <div class="pk-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-chart-line text-primary mr-2"></i> Rekapan Nilai Product Knowledge Karyawan (Januari - Desember)</h5>
                        <small class="text-muted">Nilai otomatis terisi saat karyawan menyelesaikan Kuis Online CBT di Tab 1, atau dapat diinput manual jika diperlukan.</small>
                    </div>
                    <div>
                        <a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-outline-success font-weight-bold">
                            <i class="fas fa-plus mr-1"></i> Input Nilai Manual
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                        <thead class="text-center" style="background:#f8fafc;">
                            <tr>
                                <th>No</th>
                                <th style="text-align:left;">Nama Karyawan</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Agu</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php if (empty($list_knowledge)): ?>
                                <tr>
                                    <td colspan="14" class="text-center text-muted py-4">Data belum tersedia untuk tahun <?= htmlspecialchars($tahun) ?>.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($list_knowledge as $data): 
                                    $id = encrypt($data['id']);
                                    $namaPegawai = '<a href="' . base_url('knowledge/show/detail/' . $id) . '" class="font-weight-bold text-dark">' . htmlspecialchars($data['nama']) . '</a>';
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td style="text-align:left;"><?= $namaPegawai ?></td>
                                        <?php for ($i = 1; $i <= 12; $i++): 
                                            $val = isset($data['nilai'][$i]) ? $data['nilai'][$i] : null;
                                        ?>
                                            <td>
                                                <?php if ($val !== null): 
                                                    $valFloat = (float)$val;
                                                    $pillColor = ($valFloat >= 85) ? '#166534; background:#dcfce7;' : (($valFloat >= 75) ? '#1e40af; background:#eff6ff;' : '#92400e; background:#fef3c7;');
                                                ?>
                                                    <span class="badge px-2 py-1 font-weight-bold" style="color: <?= $pillColor ?>"><?= $val ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Bulan Pelaksanaan <span class="text-danger">*</span></label>
                                <select name="bulan" id="modal_bulan_kuis" class="form-control">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= $m ?>" <?= (int)date('n') === $m ? 'selected' : '' ?>><?= $namaBulanList[$m] ?? $m ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small text-uppercase">Tahun <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" id="modal_tahun_kuis" class="form-control" value="<?= date('Y') ?>" required>
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
     MODAL LAMA: INPUT NILAI MANUAL
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

    // Modal Input Manual Lama
    $('#btn-show-add-form').click(function() {
        $('#main-modal #modal-form')[0].reset();
        $('#main-modal #modal-form').attr('action', baseUrl + 'knowledge/add');
        $('#main-modal').modal('show');
    });

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
