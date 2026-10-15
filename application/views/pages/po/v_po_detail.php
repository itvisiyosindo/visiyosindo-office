<header class="page-header">
    <h2><i class="fas fa-file-invoice text-primary mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
            <li><a href="<?= base_url('po_system') ?>">Daftar PO Masuk</a></li>
            <li><span>Detail PO: <?= htmlspecialchars($po->no_po) ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --primary-blue: #1565c0;
        --card-radius: 12px;
        --text-label: #8898aa;
        --text-dark: #2d3748;
        --text-muted: #718096;
        --border-color: #e2e8f0;
    }

    .text-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-label);
        font-weight: 700;
        display: block;
        margin-bottom: 3px;
    }

    .text-value {
        font-size: 0.92rem;
        color: var(--text-dark);
        font-weight: 600;
        line-height: 1.4;
    }

    .card-modern {
        border: 0;
        border-radius: var(--card-radius);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        background: #fff;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-header-modern {
        background: #fff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.2rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-modern h6 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.95rem;
        letter-spacing: 0.2px;
    }

    /* Status Badges */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        font-size: 0.82rem;
        font-weight: 700;
        border-radius: 50rem;
        line-height: 1.3;
        letter-spacing: 0.2px;
    }
    .badge-modern i { font-size: 0.78rem; margin-right: 6px; }

    .badge-menunggu {
        background-color: #fffaf0;
        color: #c05621;
        border: 1px solid #feebc8;
    }
    .badge-sesuai {
        background-color: #f0fff4;
        color: #276749;
        border: 1px solid #c6f6d5;
    }
    .badge-revisi {
        background-color: #fff5f5;
        color: #9b2c2c;
        border: 1px solid #fed7d7;
    }
    .badge-ditolak {
        background-color: #edf2f7;
        color: #4a5568;
        border: 1px solid #e2e8f0;
    }

    /* Attachment Banner */
    .attachment-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .attachment-card:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    /* Items Table */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .table-modern thead th {
        background: #f8fafc;
        color: #4a5568;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        font-weight: 700;
        padding: 12px 14px;
        border-top: 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-modern tbody td {
        padding: 13px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    /* Timeline Log */
    .log-item {
        position: relative;
        padding-left: 28px;
        padding-bottom: 18px;
        border-left: 2px solid #e2e8f0;
        margin-left: 10px;
    }
    .log-item:last-child {
        border-left: 2px solid transparent;
        padding-bottom: 0;
    }
    .log-dot {
        position: absolute;
        left: -7px;
        top: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #3b82f6;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #bfdbfe;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <!-- Flash Message Alerts -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-lg mr-3 text-success"></i>
                    <div>
                        <strong class="d-block">Berhasil!</strong>
                        <span class="small"><?= $this->session->flashdata('success'); ?></span>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle fa-lg mr-3 text-danger"></i>
                    <div>
                        <strong class="d-block">Pemberitahuan:</strong>
                        <span class="small"><?= $this->session->flashdata('error'); ?></span>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Header Card Summary -->
        <div class="card card-modern">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center flex-wrap mb-2">
                            <h3 class="font-weight-bold text-dark mb-0 mr-3" style="letter-spacing: -0.5px;"><?= htmlspecialchars($po->no_po) ?></h3>
                            <?php if ($po->status == 'menunggu_kroscek'): ?>
                                <span class="badge-modern badge-menunggu"><i class="fas fa-clock"></i> Menunggu Kroscek CRO</span>
                            <?php elseif ($po->status == 'sesuai'): ?>
                                <span class="badge-modern badge-sesuai"><i class="fas fa-check-circle"></i> Sesuai / Lolos Kroscek</span>
                            <?php elseif ($po->status == 'revisi'): ?>
                                <span class="badge-modern badge-revisi"><i class="fas fa-undo"></i> Perlu Revisi</span>
                            <?php elseif ($po->status == 'ditolak'): ?>
                                <span class="badge-modern badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center text-muted small flex-wrap">
                            <span class="mr-3"><i class="far fa-building mr-1"></i> Customer: <strong class="text-dark"><?= htmlspecialchars($po->nama_customer ?: ($po->master_nama_customer ?? '-')) ?></strong></span>
                            <span><i class="fas fa-barcode mr-1"></i> Kode: <strong class="text-dark"><?= $po->kode_masuk ?></strong></span>
                        </div>
                    </div>
                    <div class="col-md-5 text-md-right mt-3 mt-md-0">
                        <span class="text-label">Total Nilai Purchase Order</span>
                        <h2 class="font-weight-bold text-primary mb-0" style="letter-spacing: -0.5px;">Rp. <?= number_format($po->nominal_po, 0, ',', '.') ?>,-</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Informasi Lengkap & Item Barang -->
            <div class="col-lg-7">
                <!-- Info Purchase Order -->
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6><i class="fas fa-file-contract text-primary mr-2"></i>Informasi Purchase Order</h6>
                        <span class="badge badge-light border text-muted px-2 py-1 small">
                            <i class="far fa-clock mr-1"></i> <?= date('d M Y H:i', strtotime($po->created_at)) ?>
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <span class="text-label">Nomor PO Customer</span>
                                <div class="text-value"><?= htmlspecialchars($po->no_po) ?></div>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-label">Tanggal PO / Surat Pesanan</span>
                                <div class="text-value"><?= date('d F Y', strtotime($po->tanggal_po)) ?></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <span class="text-label">Tanggal Diterima Kantor</span>
                                <div class="text-value"><?= !empty($po->tanggal_terima) ? date('d F Y', strtotime($po->tanggal_terima)) : '-' ?></div>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-label">Sales / Marketing Terkait</span>
                                <div class="text-value"><?= htmlspecialchars($po->nama_sales ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <span class="text-label">Instansi / Customer</span>
                                <div class="text-value font-weight-bold text-dark"><?= htmlspecialchars($po->nama_customer ?: ($po->master_nama_customer ?? '-')) ?></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <span class="text-label">Alamat Pengiriman / Instansi</span>
                                <div class="text-value text-muted" style="white-space: pre-line;"><?= htmlspecialchars($po->alamat_customer ?: ($po->master_alamat ?? '-')) ?></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <span class="text-label">Diinput Oleh</span>
                                <div class="text-value"><?= htmlspecialchars($po->nama_pembuat ?: 'Admin') ?> <span class="text-muted small">(<?= date('d/m/Y H:i', strtotime($po->created_at)) ?>)</span></div>
                            </div>
                        </div>

                        <?php if (!empty($po->keterangan)): ?>
                            <div class="p-3 bg-light rounded border mt-3">
                                <span class="text-label mb-1">Catatan Tambahan / Keterangan</span>
                                <p class="mb-0 text-dark small" style="white-space: pre-line;"><?= htmlspecialchars($po->keterangan) ?></p>
                            </div>
                        <?php endif; ?>

                        <hr class="my-3" style="border-top: 1px dashed #e2e8f0;">

                        <!-- Berkas Lampiran PO -->
                        <div class="attachment-card">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                </div>
                                <div>
                                    <strong class="d-block text-dark small">Berkas Fisik / Dokumen PO</strong>
                                    <small class="text-muted">Dokumen resmi yang diunggah dari customer</small>
                                </div>
                            </div>
                            <div>
                                <?php if (!empty($po->file_po)): ?>
                                    <a href="<?= $po->file_po ?>" target="_blank" class="btn btn-sm btn-outline-primary px-3 shadow-sm font-weight-bold" style="border-radius: 8px;">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka Berkas
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted font-italic small">Tidak ada lampiran.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Item Barang -->
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6><i class="fas fa-boxes text-primary mr-2"></i>Rincian Item Barang PO</h6>
                        <span class="badge badge-primary px-2 py-1 small"><?= count($items) ?> Item</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Nama Barang / Modalitas</th>
                                        <th width="15%" class="text-center">Kuantitas</th>
                                        <th width="22%" class="text-right">Harga Satuan</th>
                                        <th width="25%" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)): ?>
                                        <?php $no = 1; foreach ($items as $it): ?>
                                            <tr>
                                                <td class="text-center text-muted font-weight-bold"><?= $no++ ?></td>
                                                <td>
                                                    <strong class="text-dark"><?= htmlspecialchars($it->nama_barang) ?></strong>
                                                    <?php if (!empty($it->catatan)): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars($it->catatan) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center font-weight-bold"><?= $it->qty + 0 ?> <span class="text-muted small"><?= htmlspecialchars($it->satuan) ?></span></td>
                                                <td class="text-right text-muted">Rp. <?= number_format($it->harga_satuan, 0, ',', '.') ?>,-</td>
                                                <td class="text-right font-weight-bold text-dark">Rp. <?= number_format($it->total_harga, 0, ',', '.') ?>,-</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Tidak ada rincian item individual (Total nominal PO: Rp. <?= number_format($po->nominal_po, 0, ',', '.') ?>,-).
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #f8fafc;">
                                        <td colspan="4" class="text-right font-weight-bold py-3 text-muted" style="letter-spacing: 0.5px;">TOTAL NILAI PO:</td>
                                        <td class="text-right font-weight-bold py-3 text-primary" style="font-size: 1.05rem;">Rp. <?= number_format($po->nominal_po, 0, ',', '.') ?>,-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Hasil Kroscek & Panel Verifikasi CRO -->
            <div class="col-lg-5">
                <!-- Status & Hasil Kroscek Card -->
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6><i class="fas fa-clipboard-check text-primary mr-2"></i>Hasil Kroscek CRO</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="text-label">Status Verifikasi</span>
                            <div class="mt-1">
                                <?php if ($po->status == 'menunggu_kroscek'): ?>
                                    <span class="badge-modern badge-menunggu"><i class="fas fa-clock"></i> Menunggu Kroscek CRO</span>
                                <?php elseif ($po->status == 'sesuai'): ?>
                                    <span class="badge-modern badge-sesuai"><i class="fas fa-check-circle"></i> Sesuai / Lolos Kroscek</span>
                                <?php elseif ($po->status == 'revisi'): ?>
                                    <span class="badge-modern badge-revisi"><i class="fas fa-undo"></i> Perlu Revisi</span>
                                <?php elseif ($po->status == 'ditolak'): ?>
                                    <span class="badge-modern badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($po->nama_kroscek)): ?>
                            <div class="mb-3 pt-2 border-top">
                                <span class="text-label">Diverifikasi Oleh</span>
                                <div class="text-value">
                                    <i class="fas fa-user-check text-success mr-1"></i> <?= htmlspecialchars($po->nama_kroscek) ?> 
                                    <span class="text-muted small">(<?= htmlspecialchars($po->jabatan_kroscek ?: 'CRO') ?>)</span>
                                </div>
                                <small class="text-muted d-block mt-1">Pada <?= date('d F Y - H:i', strtotime($po->tgl_kroscek)) ?> WIB</small>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($po->catatan_kroscek)): ?>
                            <div class="p-3 bg-light rounded border mt-3">
                                <span class="text-label text-dark mb-1"><i class="fas fa-sticky-note mr-1 text-warning"></i> Catatan Hasil Kroscek</span>
                                <p class="mb-0 text-dark small" style="white-space: pre-line;"><?= htmlspecialchars($po->catatan_kroscek) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Verifikasi Kroscek untuk CRO / Administrator -->
                <?php if ($can_kroscek): ?>
                    <div class="card card-modern" style="border: 1px solid #90cdf4; box-shadow: 0 4px 20px rgba(66, 153, 225, 0.15);">
                        <div class="card-header-modern" style="background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);">
                            <h6 class="text-primary font-weight-bold mb-0">
                                <i class="fas fa-user-shield text-primary mr-2"></i>Panel Eksekusi Kroscek (CRO)
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?= base_url('po_system/verifikasi') ?>" method="POST" id="form-verifikasi-kroscek">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">

                                <div class="form-group mb-3">
                                    <label class="form-label-modern font-weight-bold text-dark">Keputusan Kroscek <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status_kroscek" id="status_kroscek" required style="border-radius: 8px;">
                                        <option value="">-- Tentukan Hasil Kroscek --</option>
                                        <option value="sesuai" <?= $po->status == 'sesuai' ? 'selected' : '' ?>>
                                            ✓ 1. Sesuai (PO Valid & Disetujui)
                                        </option>
                                        <option value="revisi" <?= $po->status == 'revisi' ? 'selected' : '' ?>>
                                            ⚠ 2. Perlu Revisi (Ada selisih harga / item)
                                        </option>
                                        <option value="ditolak" <?= $po->status == 'ditolak' ? 'selected' : '' ?>>
                                            ✕ 3. Ditolak (Batal / Tidak Valid)
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label-modern font-weight-bold text-dark">Catatan Verifikasi</label>
                                    <textarea class="form-control" name="catatan_kroscek" id="catatan_kroscek" rows="3" placeholder="Tuliskan catatan hasil kroscek, poin revisi, atau konfirmasi harga..." style="border-radius: 8px;"><?= htmlspecialchars($po->catatan_kroscek ?? '') ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 8px;" onclick="return confirm('Apakah Anda yakin ingin menyimpan hasil kroscek ini?')">
                                    <i class="fas fa-save mr-1"></i> Simpan Keputusan Kroscek
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border shadow-sm p-3 mb-4" style="border-radius: 10px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt text-info fa-lg mr-3"></i>
                            <div>
                                <small class="text-muted d-block">Hak Akses Verifikasi:</small>
                                <span class="small text-dark">Hanya tim CRO dan Administrator yang berwenang mengeksekusi verifikasi kroscek PO.</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Log Riwayat Aktivitas -->
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h6><i class="fas fa-history text-primary mr-2"></i>Riwayat & Audit Log</h6>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($logs)): ?>
                            <div class="timeline-wrapper">
                                <?php foreach ($logs as $lg): ?>
                                    <div class="log-item">
                                        <div class="log-dot"></div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark small"><?= htmlspecialchars($lg->user_nama ?: 'Sistem') ?></strong>
                                            <small class="text-muted"><?= date('d/m/y H:i', strtotime($lg->created_at)) ?></small>
                                        </div>
                                        <div>
                                            <span class="badge badge-light border text-muted small py-1 px-2">
                                                Status: <?= htmlspecialchars($lg->status_baru) ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($lg->catatan)): ?>
                                            <p class="small text-muted mb-0 mt-1"><?= htmlspecialchars($lg->catatan) ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3 text-muted small">Belum ada riwayat aktivitas.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?= base_url('po_system') ?>" class="btn btn-outline-secondary btn-block shadow-sm py-2" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar PO Masuk
                </a>
            </div>
        </div>
    </div>
</div>
