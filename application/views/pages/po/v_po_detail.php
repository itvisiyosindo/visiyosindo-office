<header class="page-header">
    <h2><i class="fas fa-file-invoice text-primary mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url(($switch ?? '') == 'marketing' ? 'dashboard_marketing' : 'dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
            <li><a href="<?= base_url('po_system') ?>">Daftar PO Masuk</a></li>
            <li><span>Detail PO: <?= htmlspecialchars($po->no_po) ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --primary-blue: #1e40af;
        --card-radius: 12px;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .card-modern {
        border: 1px solid var(--border-color);
        border-radius: var(--card-radius);
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        background: #ffffff;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-header-modern {
        background: #ffffff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.1rem 1.4rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-modern h6 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.98rem;
        display: flex;
        align-items: center;
    }

    .card-header-modern h6 i {
        margin-right: 10px;
        color: #2563eb;
    }

    .text-label {
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: var(--text-muted);
        font-weight: 700;
        display: block;
        margin-bottom: 3px;
    }

    .text-value {
        font-size: 0.92rem;
        color: var(--text-dark);
        font-weight: 600;
        line-height: 1.45;
    }

    .badge-pill-status {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .badge-pill-status i {
        margin-right: 6px;
    }

    /* Step Timeline */
    .approval-step-box {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 16px;
        background: #fff;
        height: 100%;
        position: relative;
        transition: all 0.2s ease;
    }

    .approval-step-box:hover {
        border-color: #93c5fd;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
    }

    .approval-step-box.approved {
        border-left: 4px solid #10b981;
        background: #f0fdf4;
    }

    .approval-step-box.pending {
        border-left: 4px solid #f59e0b;
        background: #fffbeb;
    }

    .approval-step-box.rejected {
        border-left: 4px solid #ef4444;
        background: #fef2f2;
    }

    .doc-fulfillment-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        background: #f8fafc;
        height: 100%;
    }

    .doc-fulfillment-card.filled {
        background: #f0fdf4;
        border-color: #86efac;
    }

    .table-items thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        padding: 10px 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-items tbody td {
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }
</style>

<div class="row">
    <div class="col-12">

        <!-- Flash Message -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i><?= $this->session->flashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i><?= $this->session->flashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- BANNER STATUS UTAMA -->
        <div class="card-modern bg-white">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <span class="badge badge-primary px-3 py-1 font-weight-bold mb-2">KODE: <?= htmlspecialchars($po->kode_masuk ?? '-') ?></span>
                        <h3 class="font-weight-bold mb-1 text-dark">
                            PO No: <?= htmlspecialchars($po->no_po) ?>
                        </h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-hospital mr-1 text-primary"></i> <?= htmlspecialchars($po->nama_customer) ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar-alt mr-1"></i> Tanggal PO: <?= date('d M Y', strtotime($po->tanggal_po)) ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-user mr-1 text-info"></i> Sales/Marketing: <?= htmlspecialchars($po->nama_sales ?? '-') ?>
                        </p>
                    </div>
                    <div class="text-right mt-3 mt-md-0">
                        <span class="text-label">STATUS PROSES PO</span>
                        <?php
                            $sp = $po->status_proses ?? 'BELUM DI PROSES';
                            $spBadge = 'badge-warning';
                            $spIcon = 'fa-clock';
                            if ($sp === 'SUDAH DI PROSES') { $spBadge = 'badge-success'; $spIcon = 'fa-check-double'; }
                            elseif ($sp === 'PROSES SEBAGIAN') { $spBadge = 'badge-info'; $spIcon = 'fa-truck-loading'; }
                            elseif ($sp === 'HOLD / INDENT') { $spBadge = 'badge-danger'; $spIcon = 'fa-pause-circle'; }
                            elseif ($sp === 'DITOLAK') { $spBadge = 'badge-dark'; $spIcon = 'fa-times-circle'; }
                        ?>
                        <span class="badge-pill-status badge <?= $spBadge ?> text-white font-weight-bold" style="font-size: 0.95rem;">
                            <i class="fas <?= $spIcon ?>"></i> <?= htmlspecialchars($sp) ?>
                        </span>
                        <div class="mt-2">
                            <span class="badge badge-light border text-muted">Status Kroscek: <b><?= strtoupper($po->status) ?></b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 1: INFORMASI PO, CUSTOMER & TRANSAKSI -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h6><i class="fas fa-id-card"></i> 1. Informasi Lengkap PO & Customer</h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="text-label">Nama Customer / Pelanggan</span>
                        <div class="text-value"><?= htmlspecialchars($po->nama_customer) ?></div>
                    </div>
                    <div class="col-md-5 mb-3">
                        <span class="text-label">Alamat Customer</span>
                        <div class="text-value"><?= nl2br(htmlspecialchars($po->alamat_customer ?: '-')) ?></div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="text-label">NPWP Customer</span>
                        <div class="text-value"><?= htmlspecialchars($po->npwp_customer ?: '-') ?></div>
                    </div>
                </div>

                <div class="row pt-2 border-top">
                    <div class="col-md-6 mb-3">
                        <span class="text-label">Alamat Pengiriman Barang</span>
                        <div class="text-value"><?= nl2br(htmlspecialchars($po->alamat_pengiriman ?: '-')) ?></div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="text-label">PIC Penerima Barang</span>
                        <div class="text-value">
                            <b><?= htmlspecialchars($po->pic_penerima_nama ?: '-') ?></b><br>
                            <small class="text-muted"><i class="fas fa-phone mr-1"></i><?= htmlspecialchars($po->pic_penerima_nohp ?: '-') ?></small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="text-label">PIC Penagihan / Keuangan</span>
                        <div class="text-value">
                            <b><?= htmlspecialchars($po->pic_penagihan_nama ?: '-') ?></b><br>
                            <small class="text-muted"><i class="fas fa-phone mr-1"></i><?= htmlspecialchars($po->pic_penagihan_nohp ?: '-') ?></small>
                        </div>
                    </div>
                </div>

                <div class="row pt-2 border-top">
                    <div class="col-md-3 mb-3">
                        <span class="text-label">Sistem Pembayaran</span>
                        <div class="text-value">
                            <span class="badge badge-info"><?= htmlspecialchars($po->sistem_pembayaran ?: 'Cash') ?></span>
                            <?php if ($po->sistem_pembayaran === 'Cicilan'): ?>
                                <small class="d-block text-muted">DP: <?= $po->cicilan_dp_persen ?>% | <?= $po->cicilan_kali ?>x Cicilan</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="text-label">Sistem Perpajakan</span>
                        <div class="text-value">
                            <span class="badge badge-primary"><?= htmlspecialchars($po->sistem_perpajakan ?: 'PPN') ?></span>
                            <small class="d-block text-muted">NPWP/KTP: <?= htmlspecialchars($po->no_npwp_ktp ?: '-') ?></small>
                            <small class="d-block text-muted">Faktur: <?= htmlspecialchars($po->info_pengiriman_faktur ?: '-') ?></small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="text-label">Dokumen Pembayaran</span>
                        <div class="text-value">
                            <b><?= htmlspecialchars($po->dokumen_pembayaran_tipe ?: 'Hardfile') ?></b>
                            <small class="d-block text-muted">Lampiran: <?= htmlspecialchars($po->dokumen_dilampirkan ?: '-') ?></small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="text-label">Ongkos Kirim Ditanggung</span>
                        <div class="text-value">
                            <span class="badge badge-secondary"><?= htmlspecialchars($po->ongkir_ditanggung ?: 'PERUSAHAAN') ?></span>
                            <small class="d-block text-muted">Nominal Ongkir: Rp <?= number_format($po->nominal_ongkir ?? 0, 0, ',', '.') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Link Dokumen Pengajuan -->
                <div class="row pt-2 border-top">
                    <div class="col-md-4 mb-2">
                        <span class="text-label">Link Dokumen PO Customer</span>
                        <?php if (!empty($po->link_po_customer) || !empty($po->file_po)): ?>
                            <a href="<?= htmlspecialchars($po->link_po_customer ?: $po->file_po) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka Lembar PO Customer
                            </a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-2">
                        <span class="text-label">Link Penawaran / SPH</span>
                        <?php if (!empty($po->link_penawaran)): ?>
                            <a href="<?= htmlspecialchars($po->link_penawaran) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka Surat Penawaran
                            </a>
                        <?php else: ?>
                            <span class="text-muted font-italic">Tidak dilampirkan</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-2">
                        <span class="text-label">Link Approval Harga Khusus</span>
                        <?php if (!empty($po->link_approval_harga)): ?>
                            <a href="<?= htmlspecialchars($po->link_approval_harga) ?>" target="_blank" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka Approval Harga
                            </a>
                        <?php else: ?>
                            <span class="text-muted font-italic">Tidak ada diskon khusus</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: RINCIAN BARANG & KALKULASI TOTAL -->
        <div class="card-modern">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-boxes"></i> 2. Rincian Barang / Modalitas (Integrasi Stok Inventory)</h6>
                <?php if (!empty($count_indent) && $count_indent > 0 && !empty($can_update_inden)): ?>
                    <button type="button" class="btn btn-sm btn-warning text-dark font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalUpdateBarangInden">
                        <i class="fas fa-truck-loading mr-1"></i> Update Status Barang Inden (<?= $count_indent ?> Item)
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-items mb-0">
                        <thead>
                            <tr>
                                <th style="width: 4%; text-align: center;">No</th>
                                <th>Nama Barang (Type, Ukuran, Warna)</th>
                                <th style="width: 14%; text-align: right;">Harga Satuan</th>
                                <th style="width: 8%; text-align: center;">Qty</th>
                                <th style="width: 8%;">Satuan</th>
                                <th style="width: 15%; text-align: right;">Total Harga</th>
                                <th style="width: 18%; text-align: center;">Status Pemenuhan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php $no = 1; foreach ($items as $it): ?>
                                    <tr>
                                        <td class="text-center font-weight-bold"><?= $no++ ?></td>
                                        <td>
                                            <span class="font-weight-bold text-dark"><?= htmlspecialchars($it->nama_barang) ?></span>
                                            <?php if (!empty($it->catatan)): ?>
                                                <small class="d-block text-muted">Spesifikasi: <?= htmlspecialchars($it->catatan) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">Rp <?= number_format($it->harga_satuan, 0, ',', '.') ?></td>
                                        <td class="text-center font-weight-bold"><?= (float)$it->qty ?></td>
                                        <td><?= htmlspecialchars($it->satuan) ?></td>
                                        <td class="text-right font-weight-bold text-primary">Rp <?= number_format($it->total_harga, 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <?php if (($it->status_item ?? 'ready') === 'indent'): ?>
                                                <span class="badge badge-warning text-dark px-2 py-1">
                                                    <i class="fas fa-hourglass-half mr-1"></i> Inden / Menunggu
                                                </span>
                                                <?php if (!empty($it->tanggal_ready)): ?>
                                                    <small class="d-block text-muted mt-1"><i class="fas fa-calendar-alt mr-1"></i> Est: <?= date('d/m/Y', strtotime($it->tanggal_ready)) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge badge-success px-2 py-1">
                                                    <i class="fas fa-check-circle mr-1"></i> Ready (Diproses)
                                                </span>
                                                <?php if (!empty($it->tanggal_ready)): ?>
                                                    <small class="d-block text-muted mt-1"><i class="fas fa-calendar-check mr-1"></i> Tgl: <?= date('d/m/Y', strtotime($it->tanggal_ready)) ?></small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (!empty($it->catatan_item)): ?>
                                                <small class="d-block text-muted font-italic mt-1"><i class="fas fa-info-circle mr-1"></i> <?= htmlspecialchars($it->catatan_item) ?></small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">Tidak ada rincian item (Nominal global tercatat).</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary Breakdown -->
                <div class="p-3 bg-light border-top">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Subtotal Barang:</span>
                                <span class="font-weight-bold">Rp <?= number_format($po->nominal_subtotal ?? $po->nominal_po, 0, ',', '.') ?></span>
                            </div>
                            <?php if (in_array(($po->sistem_perpajakan ?? 'PPN'), ['PPN', 'ENDORSEMENT'])): ?>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted"><?= ($po->sistem_perpajakan === 'ENDORSEMENT') ? 'Pajak PPh (11%):' : 'PPN (11%):' ?></span>
                                    <span class="font-weight-bold">Rp <?= number_format($po->nominal_ppn ?? 0, 0, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($po->nominal_ongkir) && $po->nominal_ongkir > 0): ?>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted">Ongkos Kirim (<?= htmlspecialchars($po->ongkir_ditanggung) ?>):</span>
                                    <span class="font-weight-bold">Rp <?= number_format($po->nominal_ongkir, 0, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between py-2 border-top mt-1" style="font-size: 1.15rem;">
                                <span class="font-weight-bold text-dark">TOTAL AKHIR:</span>
                                <span class="font-weight-bold text-primary">Rp <?= number_format($po->nominal_total_akhir ?: $po->nominal_po, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 3: ALUR 4-STAGE MULTI-TIER APPROVAL -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h6><i class="fas fa-check-double"></i> 3. Alur Verifikasi & Persetujuan Bertingkat (CRO ➔ ACC ➔ PAJAK ➔ GM)</h6>
            </div>
            <div class="card-body p-4">
                <div class="row">

                    <!-- TAHAP 1: APPROVAL CRO -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="approval-step-box <?= ($po->cro_status === 'sesuai') ? 'approved' : (($po->cro_status === 'hold_indent') ? 'rejected' : 'pending') ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-primary">1. Tahap CRO</span>
                                <?php if ($po->cro_status === 'sesuai'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Sesuai</span>
                                <?php elseif ($po->cro_status === 'hold_indent'): ?>
                                    <span class="badge badge-warning"><i class="fas fa-pause"></i> Indent</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Menunggu</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.82rem;" class="mb-3">
                                <div><b>Kelengkapan:</b> <?= htmlspecialchars($po->cro_cek_data ?: '-') ?></div>
                                <div><b>Cek Piutang:</b> <?= htmlspecialchars($po->cro_cek_piutang ?: '-') ?></div>
                                <div><b>Stok Gudang:</b> <span class="text-primary font-weight-bold"><?= htmlspecialchars($po->cro_cek_stok ?: '-') ?></span></div>
                                <?php if (!empty($po->cro_link_pjb)): ?>
                                    <div><a href="<?= htmlspecialchars($po->cro_link_pjb) ?>" target="_blank" class="text-info"><i class="fas fa-link mr-1"></i>Link PJB</a></div>
                                <?php endif; ?>
                                <?php if (!empty($po->cro_catatan)): ?>
                                    <div class="text-muted font-italic mt-1">"<?= htmlspecialchars($po->cro_catatan) ?>"</div>
                                <?php endif; ?>
                                <?php if (!empty($po->cro_date)): ?>
                                    <small class="text-muted d-block mt-2"><i class="fas fa-user-check mr-1"></i><?= htmlspecialchars($po->nama_kroscek ?? 'CRO') ?> (<?= date('d/m/y H:i', strtotime($po->cro_date)) ?>)</small>
                                <?php endif; ?>
                            </div>

                            <?php if ($can_cro): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal" data-target="#modalCro">
                                    <i class="fas fa-edit mr-1"></i> Verifikasi CRO
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAHAP 2: APPROVAL ACCOUNTING -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="approval-step-box <?= ($po->acc_status === 'sesuai') ? 'approved' : (($po->acc_status === 'ditolak') ? 'rejected' : 'pending') ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-primary">2. Accounting</span>
                                <?php if ($po->acc_status === 'sesuai'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Sesuai</span>
                                <?php elseif ($po->acc_status === 'ditolak'): ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Menunggu</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.82rem;" class="mb-3">
                                <div><b>Cek Harga:</b> <?= htmlspecialchars($po->acc_cek_harga ?: '-') ?></div>
                                <div><b>Sistem Harga:</b> <?= htmlspecialchars($po->acc_sistem_harga ?: '-') ?></div>
                                <div><b>Sistem Bayar:</b> <?= htmlspecialchars($po->acc_sistem_pembayaran ?: '-') ?></div>
                                <?php if (!empty($po->acc_catatan)): ?>
                                    <div class="text-muted font-italic mt-1">"<?= htmlspecialchars($po->acc_catatan) ?>"</div>
                                <?php endif; ?>
                                <?php if (!empty($po->acc_date)): ?>
                                    <small class="text-muted d-block mt-2"><i class="fas fa-user-check mr-1"></i><?= htmlspecialchars($po->nama_acc ?? 'Accounting') ?> (<?= date('d/m/y H:i', strtotime($po->acc_date)) ?>)</small>
                                <?php endif; ?>
                            </div>

                            <?php if ($can_acc): ?>
                                <?php if ($po->cro_status === 'sesuai'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal" data-target="#modalAcc">
                                        <i class="fas fa-edit mr-1"></i> Verifikasi ACC
                                    </button>
                                <?php elseif ($po->cro_status === 'hold_indent'): ?>
                                    <button type="button" class="btn btn-sm btn-secondary btn-block text-white" disabled title="Menunggu stok gudang ready (Status: HOLD / INDENT)">
                                        <i class="fas fa-pause-circle mr-1"></i> Menunggu Stok Ready
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-light border btn-block text-muted" disabled title="Menunggu persetujuan tahap CRO">
                                        <i class="fas fa-lock mr-1"></i> Menunggu Tahap CRO
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAHAP 3: APPROVAL PAJAK -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="approval-step-box <?= ($po->pajak_status === 'sesuai') ? 'approved' : (($po->pajak_status === 'ditolak') ? 'rejected' : 'pending') ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-primary">3. Pajak (Tax)</span>
                                <?php if ($po->pajak_status === 'sesuai'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Sesuai</span>
                                <?php elseif ($po->pajak_status === 'ditolak'): ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Menunggu</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.82rem;" class="mb-3">
                                <div><b>Cek Pajak:</b> <?= htmlspecialchars($po->pajak_cek_pajak ?: '-') ?></div>
                                <div><b>Sistem Pajak:</b> <?= htmlspecialchars($po->pajak_sistem_perpajakan ?: '-') ?></div>
                                <?php if (!empty($po->pajak_link_approval_faktur)): ?>
                                    <div><a href="<?= htmlspecialchars($po->pajak_link_approval_faktur) ?>" target="_blank" class="text-info"><i class="fas fa-link mr-1"></i>Link Faktur Pajak</a></div>
                                <?php endif; ?>
                                <?php if (!empty($po->pajak_catatan)): ?>
                                    <div class="text-muted font-italic mt-1">"<?= htmlspecialchars($po->pajak_catatan) ?>"</div>
                                <?php endif; ?>
                                <?php if (!empty($po->pajak_date)): ?>
                                    <small class="text-muted d-block mt-2"><i class="fas fa-user-check mr-1"></i><?= htmlspecialchars($po->nama_pajak ?? 'Pajak') ?> (<?= date('d/m/y H:i', strtotime($po->pajak_date)) ?>)</small>
                                <?php endif; ?>
                            </div>

                            <?php if ($can_pajak): ?>
                                <?php if ($po->acc_status === 'sesuai'): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal" data-target="#modalPajak">
                                        <i class="fas fa-edit mr-1"></i> Verifikasi Pajak
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-light border btn-block text-muted" disabled title="Menunggu persetujuan tahap Accounting">
                                        <i class="fas fa-lock mr-1"></i> Menunggu Tahap ACC
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TAHAP 4: APPROVAL GENERAL MANAGER -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="approval-step-box <?= ($po->gm_status === 'disetujui') ? 'approved' : (($po->gm_status === 'ditolak') ? 'rejected' : 'pending') ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-primary">4. General Manager</span>
                                <?php if ($po->gm_status === 'disetujui'): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Disetujui</span>
                                <?php elseif ($po->gm_status === 'ditolak'): ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Menunggu</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.82rem;" class="mb-3">
                                <div><b>Persetujuan PO:</b> <?= ($po->gm_status === 'disetujui') ? '<span class="text-success font-weight-bold">PO Siap Diproses</span>' : '-' ?></div>
                                <?php if (!empty($po->gm_catatan)): ?>
                                    <div class="text-muted font-italic mt-1">"<?= htmlspecialchars($po->gm_catatan) ?>"</div>
                                <?php endif; ?>
                                <?php if (!empty($po->gm_date)): ?>
                                    <small class="text-muted d-block mt-2"><i class="fas fa-user-check mr-1"></i><?= htmlspecialchars($po->nama_gm ?? 'GM') ?> (<?= date('d/m/y H:i', strtotime($po->gm_date)) ?>)</small>
                                <?php endif; ?>
                            </div>

                            <?php if ($can_gm): ?>
                                <?php if ($po->pajak_status === 'sesuai'): ?>
                                    <button type="button" class="btn btn-sm btn-success btn-block" data-toggle="modal" data-target="#modalGm">
                                        <i class="fas fa-signature mr-1"></i> Approval GM
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-light border btn-block text-muted" disabled title="Menunggu persetujuan tahap Pajak">
                                        <i class="fas fa-lock mr-1"></i> Menunggu Tahap Pajak
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- CARD 4: DOKUMEN PEMENUHAN (POST-APPROVAL GM) -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h6><i class="fas fa-folder-plus"></i> 4. Dokumen Kelengkapan PO Diproses (Fulfillment Antar Divisi)</h6>
                <span class="text-muted" style="font-size: 0.8rem;">Diisi oleh Accounting, Gudang, Pajak, dan CRO</span>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <!-- ACCOUNTING (ACC) -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="doc-fulfillment-card <?= (!empty($po->doc_acc_invoice) || !empty($po->doc_acc_kwitansi)) ? 'filled' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-dark"><i class="fas fa-calculator mr-1 text-primary"></i> Accounting (ACC)</span>
                                <?php if (!empty($po->doc_acc_invoice)): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Terisi</span>
                                <?php else: ?>
                                    <span class="badge badge-light border">Kosong</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.8rem;" class="mb-3">
                                <div><b>Invoice:</b> <?= !empty($po->doc_acc_invoice) ? '<a href="'.htmlspecialchars($po->doc_acc_invoice).'" target="_blank">Buka Link</a>' : '-' ?></div>
                                <div><b>Kwitansi:</b> <?= !empty($po->doc_acc_kwitansi) ? '<a href="'.htmlspecialchars($po->doc_acc_kwitansi).'" target="_blank">Buka Link</a>' : '-' ?></div>
                                <div><b>Berkas Lain:</b> <?= !empty($po->doc_acc_lainnya) ? '<a href="'.htmlspecialchars($po->doc_acc_lainnya).'" target="_blank">Buka Link</a>' : '-' ?></div>
                            </div>
                            <?php if ($can_upload_doc): ?>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-block" data-toggle="modal" data-target="#modalDocAcc">
                                    <i class="fas fa-edit mr-1"></i> Update Dokumen ACC
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- GUDANG / LOGISTIK -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="doc-fulfillment-card <?= (!empty($po->doc_gudang_sj) || !empty($po->doc_gudang_resi)) ? 'filled' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-dark"><i class="fas fa-warehouse mr-1 text-primary"></i> Gudang / Logistik</span>
                                <?php if (!empty($po->doc_gudang_sj)): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Terisi</span>
                                <?php else: ?>
                                    <span class="badge badge-light border">Kosong</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.8rem;" class="mb-3">
                                <div><b>Surat Jalan:</b> <?= !empty($po->doc_gudang_sj) ? '<a href="'.htmlspecialchars($po->doc_gudang_sj).'" target="_blank">Buka Link</a>' : '-' ?></div>
                                <div><b>No. Resi:</b> <?= !empty($po->doc_gudang_resi) ? '<a href="'.htmlspecialchars($po->doc_gudang_resi).'" target="_blank">Buka Link</a>' : '-' ?></div>
                                <div><b>Packing List:</b> <?= !empty($po->doc_gudang_packing_list) ? '<a href="'.htmlspecialchars($po->doc_gudang_packing_list).'" target="_blank">Buka Link</a>' : '-' ?></div>
                                <div><b>SSTB / BAST:</b> <?= !empty($po->doc_gudang_sstb) ? '<a href="'.htmlspecialchars($po->doc_gudang_sstb).'" target="_blank">Buka Link</a>' : '-' ?></div>
                            </div>
                            <?php if ($can_upload_doc): ?>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-block" data-toggle="modal" data-target="#modalDocGudang">
                                    <i class="fas fa-edit mr-1"></i> Update Dokumen Gudang
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- PAJAK (TAX) -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="doc-fulfillment-card <?= (!empty($po->doc_pajak_faktur) || !empty($po->doc_pajak_bukti_potong)) ? 'filled' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-dark"><i class="fas fa-receipt mr-1 text-primary"></i> Pajak (Tax)</span>
                                <?php if (!empty($po->doc_pajak_faktur)): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Terisi</span>
                                <?php else: ?>
                                    <span class="badge badge-light border">Kosong</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.8rem;" class="mb-3">
                                <div><b>Faktur Pajak:</b> <?= !empty($po->doc_pajak_faktur) ? '<a href="'.htmlspecialchars($po->doc_pajak_faktur).'" target="_blank">Buka Link</a>' : '-' ?></div>
                                <div><b>Bukti Potong:</b> <?= !empty($po->doc_pajak_bukti_potong) ? '<a href="'.htmlspecialchars($po->doc_pajak_bukti_potong).'" target="_blank">Buka Link</a>' : '-' ?></div>
                                <div><b>PPBJ / Endorsement:</b> <?= !empty($po->doc_pajak_ppbj_endorsement) ? '<a href="'.htmlspecialchars($po->doc_pajak_ppbj_endorsement).'" target="_blank">Buka Link</a>' : '-' ?></div>
                            </div>
                            <?php if ($can_upload_doc): ?>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-block" data-toggle="modal" data-target="#modalDocPajak">
                                    <i class="fas fa-edit mr-1"></i> Update Dokumen Pajak
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- CRO DIGITAL DELIVERY -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="doc-fulfillment-card <?= (!empty($po->doc_cro_digital_delivery)) ? 'filled' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-dark"><i class="fas fa-laptop-code mr-1 text-primary"></i> CRO Digital</span>
                                <?php if (!empty($po->doc_cro_digital_delivery)): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Terisi</span>
                                <?php else: ?>
                                    <span class="badge badge-light border">Kosong</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.8rem;" class="mb-3">
                                <div><b>Bukti Pengiriman Digital:</b></div>
                                <div class="mt-1">
                                    <?= !empty($po->doc_cro_digital_delivery) ? '<a href="'.htmlspecialchars($po->doc_cro_digital_delivery).'" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-link mr-1"></i>Lihat Bukti Kirim</a>' : '<span class="text-muted">-</span>' ?>
                                </div>
                            </div>
                            <?php if ($can_upload_doc): ?>
                                <button type="button" class="btn btn-xs btn-outline-primary btn-block" data-toggle="modal" data-target="#modalDocCro">
                                    <i class="fas fa-edit mr-1"></i> Update Dokumen CRO
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 5: LOG AKTIVITAS -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h6><i class="fas fa-history"></i> 5. Riwayat Aktivitas & Log Kroscek</h6>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($logs)): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($logs as $lg): ?>
                            <li class="mb-2 pb-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-dark"><?= htmlspecialchars($lg->user_nama ?? 'Sistem') ?></span>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($lg->created_at)) ?></small>
                                </div>
                                <div class="text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars($lg->catatan) ?></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada riwayat aktivitas tercatat.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- ==================== MODAL APPROVAL CRO ==================== -->
<div class="modal fade" id="modalCro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/verifikasi_cro') ?>" method="post" id="formModalCro">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle text-primary mr-2"></i>Verifikasi Tahap 1: Approval CRO</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">1. Cek Data Marketing</label>
                        <select name="cro_cek_data" class="form-control" required>
                            <option value="SUDAH LENGKAP" <?= ($po->cro_cek_data === 'SUDAH LENGKAP') ? 'selected' : '' ?>>SUDAH LENGKAP</option>
                            <option value="TIDAK LENGKAP" <?= ($po->cro_cek_data === 'TIDAK LENGKAP') ? 'selected' : '' ?>>TIDAK LENGKAP</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">2. Cek Piutang (Manual)</label>
                        <div class="d-flex">
                            <label class="mr-3"><input type="radio" name="cro_cek_piutang" value="TIDAK" <?= ($po->cro_cek_piutang !== 'YA') ? 'checked' : '' ?> onclick="document.getElementById('boxLinkPiutang').style.display='none'"> <b>Tidak Ada Piutang (Lanjut)</b></label>
                            <label><input type="radio" name="cro_cek_piutang" value="YA" <?= ($po->cro_cek_piutang === 'YA') ? 'checked' : '' ?> onclick="document.getElementById('boxLinkPiutang').style.display='block'"> <b>Ada Piutang</b></label>
                        </div>
                        <div id="boxLinkPiutang" style="<?= ($po->cro_cek_piutang === 'YA') ? '' : 'display:none;' ?>" class="mt-2">
                            <input type="url" name="cro_link_approval_po" class="form-control" placeholder="Link Approval PO (Wajib jika ada piutang)" value="<?= htmlspecialchars($po->cro_link_approval_po ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold">3. Cek Stok Gudang</label>
                        <select name="cro_cek_stok" id="cro_cek_stok" class="form-control" required>
                            <option value="STOK READY WAREHOUSE" <?= ($po->cro_cek_stok === 'STOK READY WAREHOUSE') ? 'selected' : '' ?>>STOK READY WAREHOUSE (Proses Seluruhnya ➔ Lanjut Accounting)</option>
                            <option value="PROSES SEBAGIAN" <?= ($po->cro_cek_stok === 'PROSES SEBAGIAN') ? 'selected' : '' ?>>PROSES SEBAGIAN (Stok Ready Sebagian ➔ Lanjut Accounting)</option>
                            <option value="STOK TIDAK READY" <?= ($po->cro_cek_stok === 'STOK TIDAK READY') ? 'selected' : '' ?>>STOK TIDAK READY (HOLD / INDENT ➔ Notif Grup Gudang)</option>
                        </select>
                    </div>

                    <!-- Detail Pemilihan Barang Ready vs Inden Saat PROSES SEBAGIAN -->
                    <div id="boxPartialItems" class="p-3 mb-3 border rounded bg-light" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-boxes mr-1 text-primary"></i> Pembagian Barang (Ready vs Inden)
                            </label>
                            <small class="text-muted">Tentukan barang yang siap dikirim & yang inden</small>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered bg-white mb-1" style="font-size: 0.88rem;">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 35%;">Nama Barang & Qty</th>
                                        <th style="width: 25%;">Status</th>
                                        <th style="width: 20%;">Tanggal (Ready / Est)</th>
                                        <th style="width: 20%;">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)): ?>
                                        <?php foreach ($items as $it): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <b><?= htmlspecialchars($it->nama_barang) ?></b><br>
                                                    <span class="badge badge-light border">Qty: <?= (float)$it->qty ?> <?= htmlspecialchars($it->satuan) ?></span>
                                                </td>
                                                <td class="align-middle">
                                                    <select name="item_status[<?= $it->id_item ?>]" class="form-control form-control-sm select-item-status">
                                                        <option value="ready" <?= (($it->status_item ?? 'ready') === 'ready') ? 'selected' : '' ?>>Ready (Diproses)</option>
                                                        <option value="indent" <?= (($it->status_item ?? '') === 'indent') ? 'selected' : '' ?>>Inden (Menunggu)</option>
                                                    </select>
                                                </td>
                                                <td class="align-middle">
                                                    <input type="date" name="item_tanggal[<?= $it->id_item ?>]" class="form-control form-control-sm" value="<?= !empty($it->tanggal_ready) ? $it->tanggal_ready : date('Y-m-d') ?>">
                                                </td>
                                                <td class="align-middle">
                                                    <input type="text" name="item_catatan[<?= $it->id_item ?>]" class="form-control form-control-sm" placeholder="Catatan..." value="<?= htmlspecialchars($it->catatan_item ?? '') ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Tidak ada rincian item dalam PO ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle mr-1 text-primary"></i> <b>Ready:</b> Barang akan langsung diproses/dikirim terlebih dahulu. <b>Inden:</b> Barang dapat diperbarui statusnya kemudian saat sudah tiba di gudang.
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Link PJB (Jika ada)</label>
                        <input type="url" name="cro_link_pjb" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->cro_link_pjb ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Link Approval Dokumen PO</label>
                        <input type="url" name="cro_link_approval_po_doc" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->cro_link_approval_po_doc ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Catatan CRO</label>
                        <textarea name="cro_catatan" class="form-control" rows="2" placeholder="Catatan evaluasi CRO..."><?= htmlspecialchars($po->cro_catatan ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keputusan Approval CRO</label>
                        <select name="cro_status" id="cro_status" class="form-control" required>
                            <option value="sesuai" id="opt_cro_sesuai">Disetujui / Sesuai (Lanjut ke Accounting)</option>
                            <option value="revisi">Perlu Revisi (Kembalikan ke Marketing)</option>
                            <option value="hold_indent" id="opt_cro_hold">HOLD / INDENT (Stok Belum Tersedia)</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                        <div id="alertCroStok" class="alert alert-warning py-2 px-3 small mt-2" style="display: none; border-left: 4px solid #f59e0b; background: #fffbeb; color: #92400e;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <span id="alertCroStokText"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Verifikasi CRO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL APPROVAL ACCOUNTING ==================== -->
<div class="modal fade" id="modalAcc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/verifikasi_acc') ?>" method="post">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-calculator text-primary mr-2"></i>Verifikasi Tahap 2: Approval Accounting</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">1. Cek Kesesuaian Harga</label>
                        <select name="acc_cek_harga" class="form-control" required>
                            <option value="SUDAH SESUAI" <?= ($po->acc_cek_harga === 'SUDAH SESUAI') ? 'selected' : '' ?>>SUDAH SESUAI</option>
                            <option value="TIDAK" <?= ($po->acc_cek_harga === 'TIDAK') ? 'selected' : '' ?>>TIDAK SESUAI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">2. Sistem Harga</label>
                        <select name="acc_sistem_harga" class="form-control" required>
                            <option value="SUDAH SESUAI" <?= ($po->acc_sistem_harga === 'SUDAH SESUAI') ? 'selected' : '' ?>>SUDAH SESUAI</option>
                            <option value="TIDAK" <?= ($po->acc_sistem_harga === 'TIDAK') ? 'selected' : '' ?>>TIDAK SESUAI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">3. Sistem Pembayaran (TOP/Cash/Cicilan)</label>
                        <select name="acc_sistem_pembayaran" class="form-control" required>
                            <option value="SUDAH SESUAI" <?= ($po->acc_sistem_pembayaran === 'SUDAH SESUAI') ? 'selected' : '' ?>>SUDAH SESUAI</option>
                            <option value="TIDAK" <?= ($po->acc_sistem_pembayaran === 'TIDAK') ? 'selected' : '' ?>>TIDAK SESUAI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Catatan Accounting</label>
                        <textarea name="acc_catatan" class="form-control" rows="2" placeholder="Catatan harga/termin..."><?= htmlspecialchars($po->acc_catatan ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Keputusan Accounting</label>
                        <select name="acc_status" class="form-control" required>
                            <option value="sesuai">Disetujui / Sesuai (Lanjut ke Pajak)</option>
                            <option value="revisi">Perlu Revisi</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Verifikasi Accounting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL APPROVAL PAJAK ==================== -->
<div class="modal fade" id="modalPajak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/verifikasi_pajak') ?>" method="post">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-receipt text-primary mr-2"></i>Verifikasi Tahap 3: Approval Pajak (Tax)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">1. Cek Pajak</label>
                        <select name="pajak_cek_pajak" class="form-control" required>
                            <option value="SUDAH SESUAI" <?= ($po->pajak_cek_pajak === 'SUDAH SESUAI') ? 'selected' : '' ?>>SUDAH SESUAI</option>
                            <option value="TIDAK" <?= ($po->pajak_cek_pajak === 'TIDAK') ? 'selected' : '' ?>>TIDAK SESUAI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">2. Sistem Perpajakan (PPN/Non PPN/Endorsement)</label>
                        <select name="pajak_sistem_perpajakan" class="form-control" required>
                            <option value="SUDAH SESUAI" <?= ($po->pajak_sistem_perpajakan === 'SUDAH SESUAI') ? 'selected' : '' ?>>SUDAH SESUAI</option>
                            <option value="TIDAK" <?= ($po->pajak_sistem_perpajakan === 'TIDAK') ? 'selected' : '' ?>>TIDAK SESUAI</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link Approval Faktur Pajak</label>
                        <input type="url" name="pajak_link_approval_faktur" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->pajak_link_approval_faktur ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Catatan Pajak</label>
                        <textarea name="pajak_catatan" class="form-control" rows="2" placeholder="Catatan pajak..."><?= htmlspecialchars($po->pajak_catatan ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Keputusan Pajak</label>
                        <select name="pajak_status" class="form-control" required>
                            <option value="sesuai">Disetujui / Sesuai (Lanjut ke General Manager)</option>
                            <option value="revisi">Perlu Revisi</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Verifikasi Pajak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL APPROVAL GM ==================== -->
<div class="modal fade" id="modalGm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/verifikasi_gm') ?>" method="post">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-signature text-success mr-2"></i>Tahap 4: Persetujuan Final General Manager (GM)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> Saat disetujui, sistem akan otomatis mengubah status PO menjadi <b>SIAP DIPROSES</b> dan mengirim notifikasi WhatsApp ke Grup Marketing, Visilab, & Gudang untuk instruksi pemenuhan dokumen.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Persetujuan PO Diproses</label>
                        <select name="gm_status" class="form-control" required>
                            <option value="disetujui">DISETUJUI (PO Resmi Siap Diproses)</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Catatan General Manager</label>
                        <textarea name="gm_catatan" class="form-control" rows="2" placeholder="Instruksi / catatan pelaksanaan..."><?= htmlspecialchars($po->gm_catatan ?? '') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success font-weight-bold">Simpan Persetujuan GM</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL UPDATE DOKUMEN ACC ==================== -->
<div class="modal fade" id="modalDocAcc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/simpan_dokumen') ?>" method="post">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <input type="hidden" name="divisi" value="acc">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Input Link Dokumen Accounting (ACC)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Link Invoice</label>
                        <input type="url" name="doc_acc_invoice" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_acc_invoice ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link Kwitansi</label>
                        <input type="url" name="doc_acc_kwitansi" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_acc_kwitansi ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link Berkas Pembayaran Lainnya</label>
                        <input type="url" name="doc_acc_lainnya" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_acc_lainnya ?? '') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Dokumen ACC</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL UPDATE DOKUMEN GUDANG ==================== -->
<div class="modal fade" id="modalDocGudang" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/simpan_dokumen') ?>" method="post">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <input type="hidden" name="divisi" value="gudang">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Input Link Dokumen Gudang / Logistik</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Link Surat Jalan (SJ)</label>
                        <input type="url" name="doc_gudang_sj" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_gudang_sj ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link Bukti Resi Pengiriman</label>
                        <input type="url" name="doc_gudang_resi" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_gudang_resi ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link Packing List</label>
                        <input type="url" name="doc_gudang_packing_list" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_gudang_packing_list ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link SSTB / BAST</label>
                        <input type="url" name="doc_gudang_sstb" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_gudang_sstb ?? '') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Dokumen Gudang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL UPDATE DOKUMEN PAJAK ==================== -->
<div class="modal fade" id="modalDocPajak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/simpan_dokumen') ?>" method="post">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <input type="hidden" name="divisi" value="pajak">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Input Link Dokumen Pajak (Tax)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Link Faktur Pajak</label>
                        <input type="url" name="doc_pajak_faktur" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_pajak_faktur ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link Bukti Potong</label>
                        <input type="url" name="doc_pajak_bukti_potong" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_pajak_bukti_potong ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Link PPBJ / Endorsement (Jika ada)</label>
                        <input type="url" name="doc_pajak_ppbj_endorsement" class="form-control" placeholder="https://drive.google.com/..." value="<?= htmlspecialchars($po->doc_pajak_ppbj_endorsement ?? '') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Dokumen Pajak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL UPDATE DOKUMEN CRO ==================== -->
<div class="modal fade" id="modalDocCro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/simpan_dokumen') ?>" method="post">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <input type="hidden" name="divisi" value="cro">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Input Bukti Pengiriman Digital (CRO)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Bukti Link Pengiriman Dokumen Digital ke Customer</label>
                        <input type="url" name="doc_cro_digital_delivery" class="form-control" placeholder="Link bukti chat / email pengiriman dokumen..." value="<?= htmlspecialchars($po->doc_cro_digital_delivery ?? '') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Dokumen CRO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL UPDATE PEMENUHAN BARANG INDEN ==================== -->
<div class="modal fade" id="modalUpdateBarangInden" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('po_system/update_barang_inden') ?>" method="post" id="formUpdateInden">
                <input type="hidden" name="id_po" value="<?= $id_encrypt ?>">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-boxes mr-2"></i>Update Pemenuhan Status Barang Inden</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 px-3 small mb-3">
                        <i class="fas fa-info-circle mr-1"></i> Centang barang inden yang <b>telah siap/tiba di gudang</b> dan tentukan tanggal siap kirimnya.
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Daftar Barang Inden yang Menunggu Pemenuhan:</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm bg-white mb-0" style="font-size: 0.9rem;">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">
                                            <input type="checkbox" id="checkAllInden" title="Pilih Semua">
                                        </th>
                                        <th>Nama Barang</th>
                                        <th style="width: 15%; text-align: center;">Qty</th>
                                        <th style="width: 25%;">Est. Tgl Sebelumnya</th>
                                        <th style="width: 25%;">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $hasInden = false;
                                        if (!empty($items)): 
                                            foreach ($items as $it): 
                                                if (($it->status_item ?? '') === 'indent'):
                                                    $hasInden = true;
                                    ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <input type="checkbox" name="items_ready[]" value="<?= $it->id_item ?>" class="check-inden-item">
                                            </td>
                                            <td class="align-middle">
                                                <span class="font-weight-bold text-dark"><?= htmlspecialchars($it->nama_barang) ?></span>
                                                <?php if (!empty($it->catatan)): ?>
                                                    <small class="d-block text-muted"><?= htmlspecialchars($it->catatan) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle font-weight-bold">
                                                <?= (float)$it->qty ?> <?= htmlspecialchars($it->satuan) ?>
                                            </td>
                                            <td class="align-middle text-muted small">
                                                <?= !empty($it->tanggal_ready) ? date('d/m/Y', strtotime($it->tanggal_ready)) : '-' ?>
                                            </td>
                                            <td class="align-middle small">
                                                <?= htmlspecialchars($it->catatan_item ?: '-') ?>
                                            </td>
                                        </tr>
                                    <?php 
                                                endif;
                                            endforeach; 
                                        endif; 
                                        if (!$hasInden):
                                    ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-2">Tidak ada barang yang berstatus Inden dalam PO ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tanggal Pemenuhan / Tiba (Ready) <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_ready" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            <small class="text-muted">Tanggal fisik barang siap diproses/dikirim.</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Catatan Pemenuhan</label>
                            <input type="text" name="catatan_inden" class="form-control" placeholder="Contoh: Barang batch 2 tiba, nomor resi supplier...">
                        </div>
                    </div>

                    <div class="alert alert-light border small text-muted mb-0">
                        <i class="fas fa-check-double mr-1 text-success"></i> Jika semua barang inden telah terpenuhi (menjadi Ready), status proses PO akan otomatis bertransisi menjadi <b>SUDAH DI PROSES</b>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Status Barang Ready</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var selCekStok = document.getElementById('cro_cek_stok');
    var selStatus  = document.getElementById('cro_status');
    var optSesuai  = document.getElementById('opt_cro_sesuai');
    var optHold    = document.getElementById('opt_cro_hold');
    var alertBox   = document.getElementById('alertCroStok');
    var alertText  = document.getElementById('alertCroStokText');
    var formCro    = document.getElementById('formModalCro');
    var boxPartial = document.getElementById('boxPartialItems');
    var checkAllInden = document.getElementById('checkAllInden');

    function syncCroStok() {
        if (!selCekStok || !selStatus) return;

        var valStok = selCekStok.value;

        if (boxPartial) {
            boxPartial.style.display = (valStok === 'PROSES SEBAGIAN') ? 'block' : 'none';
        }

        if (valStok === 'STOK TIDAK READY') {
            // Stok Kosong -> Wajib HOLD / INDENT, kunci opsi Disetujui
            if (optSesuai) optSesuai.disabled = true;
            if (optHold) optHold.disabled = false;
            selStatus.value = 'hold_indent';

            if (alertBox && alertText) {
                alertText.innerHTML = '<strong>Perhatian:</strong> Karena stok barang di gudang berstatus <u>STOK TIDAK READY</u>, keputusan otomatis dialihkan ke <strong>HOLD / INDENT</strong> dan tidak dapat disetujui ke tahap Accounting.';
                alertBox.style.display = 'block';
            }
        } else if (valStok === 'STOK READY WAREHOUSE') {
            // Stok Ready -> Tidak boleh hold_indent, aktifkan Disetujui
            if (optSesuai) optSesuai.disabled = false;
            if (optHold) optHold.disabled = true;

            if (selStatus.value === 'hold_indent') {
                selStatus.value = 'sesuai';
            }

            if (alertBox) {
                alertBox.style.display = 'none';
            }
        } else if (valStok === 'PROSES SEBAGIAN') {
            // Stok Sebagian -> Boleh Disetujui (sebagian) atau HOLD
            if (optSesuai) optSesuai.disabled = false;
            if (optHold) optHold.disabled = false;

            if (alertBox && alertText) {
                alertText.innerHTML = '<strong>Catatan:</strong> Stok barang ready sebagian. Barang bertanda <strong>Ready</strong> akan langsung diproses hari ini, dan barang bertanda <strong>Inden</strong> dapat diperbarui saat barang tiba.';
                alertBox.style.display = 'block';
            }
        }
    }

    if (checkAllInden) {
        checkAllInden.addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.check-inden-item');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = checkAllInden.checked;
            }
        });
    }

    if (selCekStok) {
        selCekStok.addEventListener('change', syncCroStok);
        syncCroStok();
        $('#modalCro').on('shown.bs.modal', function() {
            syncCroStok();
        });
    }

    if (selStatus) {
        selStatus.addEventListener('change', function() {
            if (selCekStok && selCekStok.value === 'STOK TIDAK READY' && this.value === 'sesuai') {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Kontradiksi Approval!',
                        text: 'Stok gudang berstatus STOK TIDAK READY. PO ini tidak dapat disetujui ke tahap Accounting. Status dialihkan ke HOLD / INDENT.'
                    });
                } else {
                    alert('Stok gudang berstatus STOK TIDAK READY. PO ini tidak dapat disetujui ke tahap Accounting!');
                }
                this.value = 'hold_indent';
                syncCroStok();
            }
        });
    }

    if (formCro) {
        formCro.addEventListener('submit', function(e) {
            if (selCekStok && selStatus && selCekStok.value === 'STOK TIDAK READY' && selStatus.value === 'sesuai') {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Dapat Disimpan!',
                        text: 'Terdapat kontradiksi: Stok gudang TIDAK READY, sehingga keputusan approval tidak boleh Disetujui/Lanjut ke Accounting. Silakan pilih HOLD / INDENT.'
                    });
                } else {
                    alert('Kontradiksi: Stok tidak ready tidak dapat disetujui ke Accounting.');
                }
                return false;
            }
        });
    }
});
</script>
