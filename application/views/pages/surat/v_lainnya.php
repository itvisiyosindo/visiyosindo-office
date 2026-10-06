<header class="page-header">
    <h2><i class="icons icon-envelope-open"></i>&nbsp;<?= $page_title ?? 'Surat Lainnya' ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Helpdesk</span></li>
            <li><span>Surat Menyurat</span></li>
            <li><span><?= $page_title ?? 'Surat Lainnya' ?></span></li>
        </ol>
    </div>
</header>

<style>
    .surat-header-box {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: #ffffff;
        margin-bottom: 24px;
        box-shadow: 0 8px 20px rgba(30, 60, 114, 0.15);
    }
    .surat-header-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 6px 0;
        letter-spacing: -0.3px;
    }
    .surat-header-desc {
        font-size: 13.5px;
        opacity: 0.9;
        margin: 0;
    }
    .surat-search-input {
        border-radius: 30px;
        padding: 10px 20px 10px 42px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }
    .surat-search-input:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.15);
    }
    .surat-filter-btn {
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
        padding: 6px 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        margin-right: 6px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .surat-filter-btn:hover, .surat-filter-btn.active {
        background: #2a5298;
        color: #ffffff;
        border-color: #2a5298;
        box-shadow: 0 4px 10px rgba(42, 82, 152, 0.2);
    }
    .surat-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: calc(100% - 20px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .surat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }
    .surat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 14px;
        flex-shrink: 0;
    }
    .surat-tag {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .surat-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        line-height: 1.35;
    }
    .surat-desc {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.45;
        margin-bottom: 16px;
        min-height: 36px;
    }
    .surat-actions {
        border-top: 1px dashed #e2e8f0;
        padding-top: 14px;
        margin-top: auto;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
    }
    .surat-btn-action {
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .surat-btn-pengajuan {
        background: #f0f7ff;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }
    .surat-btn-pengajuan:hover {
        background: #0284c7;
        color: #ffffff;
    }
    .surat-btn-persetujuan {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .surat-btn-persetujuan:hover {
        background: #16a34a;
        color: #ffffff;
    }
    .surat-btn-all {
        background: #faf5ff;
        color: #9333ea;
        border: 1px solid #e9d5ff;
    }
    .surat-btn-all:hover {
        background: #9333ea;
        color: #ffffff;
    }
</style>

<div class="container-fluid px-0">
    <!-- Header Box -->
    <div class="surat-header-box">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="surat-header-title"><i class="fas fa-folder-open mr-2"></i>Katalog Surat Lainnya</h1>
                <p class="surat-header-desc">Kelola permohonan, persetujuan, dan pengajuan berbagai kategori surat resmi perusahaan.</p>
            </div>
            <div class="col-md-5 mt-3 mt-md-0">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute text-muted" style="top: 13px; left: 16px; font-size: 14px;"></i>
                    <input type="text" id="search_surat" class="form-control surat-search-input" placeholder="Cari jenis surat...">
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Category Pills -->
    <div class="mb-3 d-flex flex-wrap align-items-center">
        <button class="surat-filter-btn active" data-filter="all"><i class="fas fa-th-large mr-1"></i> Semua Surat</button>
        <button class="surat-filter-btn" data-filter="operasional"><i class="fas fa-tasks mr-1"></i> Operasional & Tugas</button>
        <button class="surat-filter-btn" data-filter="aset"><i class="fas fa-boxes mr-1"></i> Aset & Inventaris</button>
        <button class="surat-filter-btn" data-filter="kepegawaian"><i class="fas fa-user-tie mr-1"></i> Kepegawaian & HR</button>
        <button class="surat-filter-btn" data-filter="direksi"><i class="fas fa-stamp mr-1"></i> Legal & Direksi</button>
    </div>

    <!-- Cards Grid -->
    <div class="row" id="surat_card_grid">

        <?php 
        // 1. SURAT TUGAS
        if (isAdmin() || isGa() || in_array(sessPenggunaId(), ['33', '69', '744'])) { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="operasional" data-name="surat tugas penugasan">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <span class="surat-tag" style="background: #e0f2fe; color: #0369a1;">Operasional</span>
                    </div>
                    <h3 class="surat-title">Surat Tugas</h3>
                    <p class="surat-desc">Penerbitan dan persetujuan surat tugas operasional & perjalanan dinas karyawan.</p>
                </div>
                <div class="surat-actions">
                    <?php if (isAdmin() || in_array(sessPenggunaId(), ['33', '69', '744'])) { ?>
                        <a href="<?= base_url('surat/show/list/st') ?>" class="surat-btn-action surat-btn-persetujuan" title="Daftar Persetujuan Surat Tugas">
                            <i class="fas fa-check-circle"></i> Persetujuan
                        </a>
                    <?php } ?>
                    <?php if (isGa() || isAdmin()) { ?>
                        <a href="<?= base_url('surat/show/my_surat/st') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Surat Tugas">
                            <i class="fas fa-paper-plane"></i> Pengajuan
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php 
        // 2. SURAT SKORSING
        if (isAdmin() || isGa() || in_array(sessPenggunaId(), ['33', '69', '744'])) { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="kepegawaian" data-name="surat skorsing sanksi">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #fee2e2; color: #dc2626;">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <span class="surat-tag" style="background: #fee2e2; color: #b91c1c;">Kepegawaian</span>
                    </div>
                    <h3 class="surat-title">Surat Skorsing</h3>
                    <p class="surat-desc">Penerbitan surat keputusan tindakan sanksi dan skorsing kerja karyawan.</p>
                </div>
                <div class="surat-actions">
                    <a href="<?= base_url('surat_new/show/list/spi') ?>" class="surat-btn-action surat-btn-all" title="Kelola Pengajuan & Persetujuan Surat Skorsing">
                        <i class="fas fa-folder-open"></i> Pengajuan & Persetujuan
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- 3. SERAH TERIMA ASET -->
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="aset" data-name="serah terima aset sta inventaris barang">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #ccfbf1; color: #0d9488;">
                            <i class="fas fa-laptop-house"></i>
                        </div>
                        <span class="surat-tag" style="background: #ccfbf1; color: #0f766e;">Aset & Inventaris</span>
                    </div>
                    <h3 class="surat-title">Serah Terima Aset</h3>
                    <p class="surat-desc">Berita acara penyerahan aset, perangkat kerja, dan inventaris kantor.</p>
                </div>
                <div class="surat-actions">
                    <a href="<?= base_url('surat_new/show/list/sta') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Serah Terima Aset">
                        <i class="fas fa-check-circle"></i> Persetujuan
                    </a>
                    <a href="<?= base_url('surat_new/show/permintaan/sta') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Serah Terima Aset">
                        <i class="fas fa-paper-plane"></i> Pengajuan
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. SERAH TERIMA FISIK PERLENGKAPAN -->
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="aset" data-name="serah terima fisik perlengkapan stfp alat kerja">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #f3e8ff; color: #9333ea;">
                            <i class="fas fa-tools"></i>
                        </div>
                        <span class="surat-tag" style="background: #f3e8ff; color: #7e22ce;">Aset & Inventaris</span>
                    </div>
                    <h3 class="surat-title">Serah Terima Fisik Perlengkapan</h3>
                    <p class="surat-desc">Formulir serah terima fisik sarana dan perlengkapan perlengkapan kerja.</p>
                </div>
                <div class="surat-actions">
                    <a href="<?= base_url('surat_new/show/list/stfp') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Serah Terima Fisik">
                        <i class="fas fa-check-circle"></i> Persetujuan
                    </a>
                    <a href="<?= base_url('surat_new/show/permintaan/stfp') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Serah Terima Fisik">
                        <i class="fas fa-paper-plane"></i> Pengajuan
                    </a>
                </div>
            </div>
        </div>

        <?php 
        // 5. SURAT KETERANGAN AKTIF BEKERJA
        if (isAdmin() || isGa() || in_array(sessPenggunaId(), ['33', '69', '744'])) { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="kepegawaian" data-name="surat keterangan aktif bekerja karyawan">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <span class="surat-tag" style="background: #dcfce7; color: #15803d;">Kepegawaian</span>
                    </div>
                    <h3 class="surat-title">Surat Keterangan Aktif Bekerja</h3>
                    <p class="surat-desc">Penerbitan surat resmi status aktif karyawan untuk keperluan perbankan/kedinasan.</p>
                </div>
                <div class="surat-actions">
                    <?php if (isAdmin() || in_array(sessPenggunaId(), ['69', '744'])) { ?>
                        <a href="<?= base_url('surat/show/list/keterangan') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Surat Keterangan">
                            <i class="fas fa-check-circle"></i> Persetujuan
                        </a>
                    <?php } ?>
                    <?php if (isGa() || isAdmin()) { ?>
                        <a href="<?= base_url('surat/show/my_surat/keterangan') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Surat Keterangan">
                            <i class="fas fa-paper-plane"></i> Pengajuan
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php 
        // 6. SURAT PEMBERITAHUAN
        if (isAdmin() || isGa() || sessPenggunaId() == '33') { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="operasional" data-name="surat pemberitahuan pengumuman internal">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #e0e7ff; color: #4f46e5;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <span class="surat-tag" style="background: #e0e7ff; color: #4338ca;">Operasional</span>
                    </div>
                    <h3 class="surat-title">Surat Pemberitahuan</h3>
                    <p class="surat-desc">Pemberitahuan edaran resmi dan pengumuman kedinasan perusahaan.</p>
                </div>
                <div class="surat-actions">
                    <a href="javascript:;" class="surat-btn-action surat-btn-pengajuan" title="Surat Pemberitahuan">
                        <i class="fas fa-envelope-open-text"></i> Lihat Pemberitahuan
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- 7. SERAH TERIMA PEKERJAAN -->
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="kepegawaian" data-name="serah terima pekerjaan stp handover tugas">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #cffafe; color: #0891b2;">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <span class="surat-tag" style="background: #cffafe; color: #0e7490;">Kepegawaian</span>
                    </div>
                    <h3 class="surat-title">Serah Terima Pekerjaan</h3>
                    <p class="surat-desc">Handover tugas, tanggung jawab, dan berkas pekerjaan (mutasi/resign).</p>
                </div>
                <div class="surat-actions">
                    <a href="<?= base_url('surat_new/show/list/stp') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Serah Terima Pekerjaan">
                        <i class="fas fa-check-circle"></i> Persetujuan
                    </a>
                    <a href="<?= base_url('surat_new/show/permintaan/stp') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Serah Terima Pekerjaan">
                        <i class="fas fa-paper-plane"></i> Pengajuan
                    </a>
                </div>
            </div>
        </div>

        <?php 
        // 8. SURAT KEPUTUSAN DIREKSI (SKD)
        if (isAdmin() || isGa() || sessPenggunaId() == '33') { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="direksi" data-name="surat keputusan direksi skd kebijakan">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #f1f5f9; color: #334155;">
                            <i class="fas fa-stamp"></i>
                        </div>
                        <span class="surat-tag" style="background: #f1f5f9; color: #1e293b;">Legal & Direksi</span>
                    </div>
                    <h3 class="surat-title">Surat Keputusan Direksi</h3>
                    <p class="surat-desc">Penerbitan surat ketetapan kebijakan dan keputusan jajaran direksi perusahaan.</p>
                </div>
                <div class="surat-actions">
                    <?php if (isAdmin() || isHrd()) { ?>
                        <a href="<?= base_url('surat/show/list/skd') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan SKD">
                            <i class="fas fa-check-circle"></i> Persetujuan
                        </a>
                    <?php } ?>
                    <a href="<?= base_url('surat/show/my_surat/skd') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan SKD">
                        <i class="fas fa-paper-plane"></i> Pengajuan
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php 
        // 9. SURAT KUASA
        if (isAdmin() || isGa() || sessPenggunaId() == '33') { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="direksi" data-name="surat kuasa wewenang legal">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <span class="surat-tag" style="background: #fef3c7; color: #b45309;">Legal & Direksi</span>
                    </div>
                    <h3 class="surat-title">Surat Kuasa</h3>
                    <p class="surat-desc">Pemberian wewenang dan kuasa perwakilan untuk tindakan hukum/operasional.</p>
                </div>
                <div class="surat-actions">
                    <a href="javascript:;" class="surat-btn-action surat-btn-all" title="Surat Kuasa">
                        <i class="fas fa-file-signature"></i> Lihat Berkas
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- 10. SURAT REKOMENDASI -->
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="kepegawaian" data-name="surat rekomendasi prestasi kinerja">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #ecfdf5; color: #059669;">
                            <i class="fas fa-award"></i>
                        </div>
                        <span class="surat-tag" style="background: #ecfdf5; color: #047857;">Kepegawaian</span>
                    </div>
                    <h3 class="surat-title">Surat Rekomendasi</h3>
                    <p class="surat-desc">Surat rekomendasi kinerja, kelayakan, dan kualifikasi karyawan.</p>
                </div>
                <div class="surat-actions">
                    <?php if (isAdmin() || in_array(sessPenggunaId(), ['33', '54'])) { ?>
                        <a href="<?= base_url('surat/show/list/rekom') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Surat Rekomendasi">
                            <i class="fas fa-check-circle"></i> Persetujuan
                        </a>
                    <?php } ?>
                    <?php if (isGa() || isLegalOfficer() || isAdmin()) { ?>
                        <a href="<?= base_url('surat/show/my_surat/rekom') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Surat Rekomendasi">
                            <i class="fas fa-paper-plane"></i> Pengajuan
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php 
        // 11. SURAT PERINGATAN (SP)
        if (isAdmin() || in_array(sessPenggunaId(), ['33', '69', '744']) || isGa()) { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="kepegawaian" data-name="surat peringatan sp disiplin teguran">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #ffe4e6; color: #e11d48;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span class="surat-tag" style="background: #ffe4e6; color: #be123c;">Kepegawaian</span>
                    </div>
                    <h3 class="surat-title">Surat Peringatan</h3>
                    <p class="surat-desc">Penerbitan surat peringatan (SP 1, SP 2, SP 3) dan pembinaan disiplin karyawan.</p>
                </div>
                <div class="surat-actions">
                    <a href="<?= base_url('surat/show/list/surat_peringatan') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Surat Peringatan">
                        <i class="fas fa-check-circle"></i> Persetujuan
                    </a>
                    <a href="<?= base_url('surat/show/my_surat/surat_peringatan') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Surat Peringatan">
                        <i class="fas fa-paper-plane"></i> Pengajuan
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- 12. BERITA ACARA -->
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="operasional" data-name="berita acara ba serah terima kejadian">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">
                            <i class="fas fa-scroll"></i>
                        </div>
                        <span class="surat-tag" style="background: #f1f5f9; color: #334155;">Operasional</span>
                    </div>
                    <h3 class="surat-title">Berita Acara</h3>
                    <p class="surat-desc">Penyusunan berkas berita acara verifikasi, kejadian, atau kesepakatan resmi.</p>
                </div>
                <div class="surat-actions">
                    <a href="<?= base_url('surat_part_two/show/list/berita_acara') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Berita Acara">
                        <i class="fas fa-check-circle"></i> Persetujuan
                    </a>
                    <a href="<?= base_url('surat_part_two/show/permintaan/ba') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Berita Acara">
                        <i class="fas fa-paper-plane"></i> Pengajuan
                    </a>
                </div>
            </div>
        </div>

        <?php 
        // 13. SURAT KETERANGAN PENGALAMAN KERJA (PAKLARING)
        if (isAdmin() || isGa() || in_array(sessPenggunaId(), ['33', '69', '744'])) { ?>
        <div class="col-xl-4 col-md-6 mb-4 surat-item" data-category="kepegawaian" data-name="surat keterangan pengalaman kerja paklaring resign">
            <div class="surat-card">
                <div>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="surat-icon-wrapper" style="background: #eef2ff; color: #6366f1;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span class="surat-tag" style="background: #eef2ff; color: #4f46e5;">Kepegawaian</span>
                    </div>
                    <h3 class="surat-title">Surat Keterangan Pengalaman Kerja</h3>
                    <p class="surat-desc">Penerbitan paklaring dan riwayat masa kerja resmi bagi mantan karyawan.</p>
                </div>
                <div class="surat-actions">
                    <a href="<?= base_url('surat_part_two/show/list/paklaring') ?>" class="surat-btn-action surat-btn-persetujuan" title="Persetujuan Paklaring">
                        <i class="fas fa-check-circle"></i> Persetujuan
                    </a>
                    <a href="<?= base_url('surat_part_two/show/pengajuan/paklaring') ?>" class="surat-btn-action surat-btn-pengajuan" title="Pengajuan Paklaring">
                        <i class="fas fa-paper-plane"></i> Pengajuan
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>

    <!-- Empty Search State -->
    <div id="empty_search_state" class="text-center py-5 d-none">
        <div class="mb-3 text-muted" style="font-size: 48px;">
            <i class="fas fa-search"></i>
        </div>
        <h4 class="font-weight-bold text-dark mb-1">Surat Tidak Ditemukan</h4>
        <p class="text-muted">Coba kata kunci pencarian lain atau pilih kategori yang tersedia.</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('search_surat');
    var filterBtns = document.querySelectorAll('.surat-filter-btn');
    var cardItems = document.querySelectorAll('.surat-item');
    var emptyState = document.getElementById('empty_search_state');
    var activeCategory = 'all';

    function filterCards() {
        var query = (searchInput.value || '').toLowerCase().trim();
        var visibleCount = 0;

        cardItems.forEach(function(card) {
            var category = card.getAttribute('data-category');
            var searchData = (card.getAttribute('data-name') || '') + ' ' + (card.innerText || '');
            searchData = searchData.toLowerCase();

            var matchesCategory = (activeCategory === 'all') || (category === activeCategory);
            var matchesQuery = !query || (searchData.indexOf(query) !== -1);

            if (matchesCategory && matchesQuery) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            emptyState.classList.remove('d-none');
        } else {
            emptyState.classList.add('d-none');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            activeCategory = this.getAttribute('data-filter');
            filterCards();
        });
    });
});
</script>