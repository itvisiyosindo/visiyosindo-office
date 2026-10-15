<header class="page-header">
    <h2><i class="fas fa-search-dollar text-primary"></i> <?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
            <li><a href="<?= base_url('po_system') ?>">Daftar PO Masuk</a></li>
            <li><span>Detail PO</span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col-lg-12">
        <!-- Flash Message Alerts -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> <?= $this->session->flashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= $this->session->flashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Header Card Summary -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <h3 class="font-weight-bold text-dark mb-0 mr-3"><?= htmlspecialchars($po->no_po) ?></h3>
                            <?php if ($po->status == 'menunggu_kroscek'): ?>
                                <span class="badge badge-warning text-dark px-3 py-2" style="font-size: 13px;"><i class="fas fa-clock mr-1"></i> Menunggu Kroscek CRO</span>
                            <?php elseif ($po->status == 'sesuai'): ?>
                                <span class="badge badge-success px-3 py-2" style="font-size: 13px;"><i class="fas fa-check-circle mr-1"></i> Sesuai / Lolos Kroscek</span>
                            <?php elseif ($po->status == 'revisi'): ?>
                                <span class="badge badge-danger px-3 py-2" style="font-size: 13px;"><i class="fas fa-undo mr-1"></i> Perlu Revisi</span>
                            <?php elseif ($po->status == 'ditolak'): ?>
                                <span class="badge badge-secondary px-3 py-2" style="font-size: 13px;"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted mb-0">
                            Customer: <strong><?= htmlspecialchars($po->nama_customer ?: ($po->master_nama_customer ?? '-')) ?></strong>
                            &nbsp;|&nbsp; Kode Sistem: <span class="badge badge-light border"><?= $po->kode_masuk ?></span>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <span class="text-muted small d-block">Total Nilai PO</span>
                        <h3 class="font-weight-bold text-primary mb-0">Rp. <?= number_format($po->nominal_po, 0, ',', '.') ?>,-</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Data Detail & Berkas -->
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 text-primary font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Informasi Purchase Order</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="35%" class="text-muted">Nomor PO Customer</td>
                                <td width="5%">:</td>
                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($po->no_po) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal PO</td>
                                <td>:</td>
                                <td><?= date('d F Y', strtotime($po->tanggal_po)) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Diterima</td>
                                <td>:</td>
                                <td><?= !empty($po->tanggal_terima) ? date('d F Y', strtotime($po->tanggal_terima)) : '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Customer / Instansi</td>
                                <td>:</td>
                                <td class="font-weight-bold"><?= htmlspecialchars($po->nama_customer ?: ($po->master_nama_customer ?? '-')) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat Pengiriman</td>
                                <td>:</td>
                                <td><?= nl2br(htmlspecialchars($po->alamat_customer ?: ($po->master_alamat ?? '-'))) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sales / Marketing</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($po->nama_sales ?: '-') ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Diinput Oleh</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($po->nama_pembuat ?: 'Admin') ?> (<?= date('d/m/Y H:i', strtotime($po->created_at)) ?>)</td>
                            </tr>
                            <?php if (!empty($po->keterangan)): ?>
                            <tr>
                                <td class="text-muted">Keterangan / Catatan</td>
                                <td>:</td>
                                <td><?= nl2br(htmlspecialchars($po->keterangan)) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>

                        <hr class="my-3">

                        <!-- Berkas Dokumen PO -->
                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded border">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-pdf fa-2x text-danger mr-3"></i>
                                <div>
                                    <strong class="d-block text-dark">Dokumen Berkas PO</strong>
                                    <small class="text-muted">Lampiran PO resmi dari customer</small>
                                </div>
                            </div>
                            <div>
                                <?php if (!empty($po->file_po)): ?>
                                    <a href="<?= $po->file_po ?>" target="_blank" class="btn btn-sm btn-outline-primary px-3 shadow-sm font-weight-bold">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka / Unduh Berkas
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted font-italic small">Tidak ada lampiran berkas.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Item Barang -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 text-primary font-weight-bold"><i class="fas fa-boxes mr-2"></i>Rincian Item Barang PO</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Nama Barang / Modalitas</th>
                                        <th width="15%" class="text-center">Qty</th>
                                        <th width="22%" class="text-right">Harga Satuan</th>
                                        <th width="25%" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)): ?>
                                        <?php $no = 1; foreach ($items as $it): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($it->nama_barang) ?></strong>
                                                    <?php if (!empty($it->catatan)): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars($it->catatan) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center"><?= $it->qty + 0 ?> <?= htmlspecialchars($it->satuan) ?></td>
                                                <td class="text-right">Rp. <?= number_format($it->harga_satuan, 0, ',', '.') ?>,-</td>
                                                <td class="text-right font-weight-bold">Rp. <?= number_format($it->total_harga, 0, ',', '.') ?>,-</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-3 text-muted">
                                                Tidak ada rincian item individual (Total nominal PO: Rp. <?= number_format($po->nominal_po, 0, ',', '.') ?>,-).
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td colspan="4" class="text-right">TOTAL NILAI PO:</td>
                                        <td class="text-right text-primary">Rp. <?= number_format($po->nominal_po, 0, ',', '.') ?>,-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Panel Kroscek CRO & Riwayat -->
            <div class="col-lg-5">
                <!-- Status Kroscek Card -->
                <div class="card shadow-sm mb-4 border-left-<?= $po->status == 'sesuai' ? 'success' : ($po->status == 'menunggu_kroscek' ? 'warning' : 'danger') ?>">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-clipboard-check mr-2"></i>Hasil Kroscek CRO</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="text-muted small d-block">Status Kroscek Saat Ini:</span>
                            <?php if ($po->status == 'menunggu_kroscek'): ?>
                                <span class="badge badge-warning text-dark px-3 py-2 mt-1"><i class="fas fa-clock mr-1"></i> Menunggu Kroscek CRO</span>
                            <?php elseif ($po->status == 'sesuai'): ?>
                                <span class="badge badge-success px-3 py-2 mt-1"><i class="fas fa-check-circle mr-1"></i> Sesuai / Disetujui</span>
                            <?php elseif ($po->status == 'revisi'): ?>
                                <span class="badge badge-danger px-3 py-2 mt-1"><i class="fas fa-undo mr-1"></i> Perlu Revisi</span>
                            <?php elseif ($po->status == 'ditolak'): ?>
                                <span class="badge badge-secondary px-3 py-2 mt-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($po->nama_kroscek)): ?>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Diverifikasi Oleh:</span>
                                <strong><?= htmlspecialchars($po->nama_kroscek) ?></strong> (<?= htmlspecialchars($po->jabatan_kroscek ?: 'CRO') ?>)
                                <br><small class="text-muted"><?= date('d F Y H:i', strtotime($po->tgl_kroscek)) ?></small>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($po->catatan_kroscek)): ?>
                            <div class="alert alert-light border mt-3 mb-0">
                                <strong class="small text-muted d-block mb-1">Catatan Kroscek:</strong>
                                <p class="mb-0 text-dark"><?= nl2br(htmlspecialchars($po->catatan_kroscek)) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Aksi Verifikasi Kroscek untuk CRO / Admin -->
                <?php if ($can_kroscek): ?>
                    <div class="card shadow-sm mb-4 border-primary">
                        <div class="card-header bg-primary text-white py-3">
                            <h6 class="mb-0 font-weight-bold text-white"><i class="fas fa-user-check mr-2"></i>Panel Verifikasi Kroscek (CRO)</h6>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('po_system/verifikasi') ?>" method="POST" id="form-verifikasi-kroscek">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark">Pilih Hasil Kroscek <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status_kroscek" id="status_kroscek" required>
                                        <option value="">-- Tentukan Hasil Kroscek --</option>
                                        <option value="sesuai" <?= $po->status == 'sesuai' ? 'selected' : '' ?>>
                                            1. Sesuai / Disetujui (PO valid dan cocok)
                                        </option>
                                        <option value="revisi" <?= $po->status == 'revisi' ? 'selected' : '' ?>>
                                            2. Perlu Revisi (Ada selisih harga / item / syarat)
                                        </option>
                                        <option value="ditolak" <?= $po->status == 'ditolak' ? 'selected' : '' ?>>
                                            3. Ditolak (Batal / Tidak Valid)
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark">Catatan Kroscek <small class="text-danger">(Wajib jika revisi/ditolak)</small></label>
                                    <textarea class="form-control" name="catatan_kroscek" id="catatan_kroscek" rows="3" placeholder="Tuliskan catatan hasil kroscek, kesesuaian harga/item, atau poin yang harus diperbaiki..."><?= htmlspecialchars($po->catatan_kroscek ?? '') ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm" onclick="return confirm('Apakah Anda yakin ingin memperbarui status kroscek PO ini?')">
                                    <i class="fas fa-check-circle mr-1"></i> Simpan Hasil Verifikasi Kroscek
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info border small py-2 mb-4">
                        <i class="fas fa-info-circle mr-1"></i> Anda login sebagai <strong><?= htmlspecialchars($my_jabatan) ?></strong>. Hanya tim CRO dan Administrator yang dapat melakukan verifikasi kroscek.
                    </div>
                <?php endif; ?>

                <!-- Riwayat / Log Aktivitas -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 text-dark font-weight-bold"><i class="fas fa-history mr-2"></i>Riwayat Aktivitas</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $lg): ?>
                                    <div class="list-group-item px-3 py-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark small"><?= htmlspecialchars($lg->user_nama ?: 'Sistem') ?></strong>
                                            <small class="text-muted"><?= date('d/m/y H:i', strtotime($lg->created_at)) ?></small>
                                        </div>
                                        <span class="badge badge-light border" style="font-size: 11px;">
                                            Status: <?= htmlspecialchars($lg->status_baru) ?>
                                        </span>
                                        <?php if (!empty($lg->catatan)): ?>
                                            <p class="small text-muted mb-0 mt-1"><?= htmlspecialchars($lg->catatan) ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-3 text-muted small text-center">Belum ada riwayat tercatat.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <a href="<?= base_url('po_system') ?>" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar PO Masuk
                </a>
            </div>
        </div>
    </div>
</div>
