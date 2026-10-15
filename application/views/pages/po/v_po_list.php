<header class="page-header">
    <h2><i class="fas fa-file-signature text-primary mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --card-radius: 12px;
        --text-dark: #2d3748;
        --text-muted: #718096;
        --border-color: #edf2f7;
    }

    .card-modern {
        border: 0;
        border-radius: var(--card-radius);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        background: #fff;
        margin-bottom: 24px;
        transition: all 0.25s ease;
    }

    .card-header-modern {
        background: #fff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.2rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: var(--card-radius);
        border-top-right-radius: var(--card-radius);
    }

    /* KPI Stat Cards */
    .kpi-card {
        border: 0;
        border-radius: var(--card-radius);
        background: #fff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        padding: 1.25rem 1.4rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }
    .kpi-card.kpi-blue::before { background: linear-gradient(180deg, #3182ce, #2b6cb0); }
    .kpi-card.kpi-amber::before { background: linear-gradient(180deg, #dd6b20, #d69e2e); }
    .kpi-card.kpi-green::before { background: linear-gradient(180deg, #38a169, #2f855a); }
    .kpi-card.kpi-red::before { background: linear-gradient(180deg, #e53e3e, #c53030); }

    .kpi-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .kpi-blue .kpi-icon-wrapper { background: #ebf8ff; color: #2b6cb0; }
    .kpi-amber .kpi-icon-wrapper { background: #fffaf0; color: #dd6b20; }
    .kpi-green .kpi-icon-wrapper { background: #f0fff4; color: #2f855a; }
    .kpi-red .kpi-icon-wrapper { background: #fff5f5; color: #c53030; }

    .kpi-title {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 2px;
    }
    .kpi-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.2;
    }

    /* Status Badges */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        border-radius: 50rem;
        line-height: 1.3;
        letter-spacing: 0.2px;
    }
    .badge-modern i { font-size: 0.75rem; margin-right: 5px; }

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

    /* Filter Pills */
    .filter-pill {
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #4a5568;
        margin-right: 6px;
        margin-bottom: 6px;
        display: inline-block;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }
    .filter-pill:hover {
        background: #f7fafc;
        color: #2d3748;
    }
    .filter-pill.active {
        background: #3182ce;
        color: #fff;
        border-color: #3182ce;
        box-shadow: 0 2px 8px rgba(49, 130, 206, 0.35);
    }
    .filter-pill.active-amber {
        background: #dd6b20;
        color: #fff;
        border-color: #dd6b20;
        box-shadow: 0 2px 8px rgba(221, 107, 32, 0.35);
    }
    .filter-pill.active-green {
        background: #38a169;
        color: #fff;
        border-color: #38a169;
        box-shadow: 0 2px 8px rgba(56, 161, 105, 0.35);
    }
    .filter-pill.active-red {
        background: #e53e3e;
        color: #fff;
        border-color: #e53e3e;
        box-shadow: 0 2px 8px rgba(229, 62, 62, 0.35);
    }

    /* Table styling */
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
        padding: 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
        color: #2d3748;
    }
    .table-modern tbody tr:hover td {
        background-color: #f8fafc;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
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
                    <i class="fas fa-exclamation-triangle fa-lg mr-3 text-danger"></i>
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

        <!-- Stat Cards Row -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                <div class="kpi-card kpi-blue">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-title">Total PO Masuk</div>
                            <div class="kpi-value"><?= number_format($stats->total ?? 0) ?></div>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                <div class="kpi-card kpi-amber">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-title">Menunggu Kroscek</div>
                            <div class="kpi-value text-warning" style="color: #dd6b20 !important;"><?= number_format($stats->total_menunggu ?? 0) ?></div>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                <div class="kpi-card kpi-green">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-title">Sesuai / Lolos Kroscek</div>
                            <div class="kpi-value text-success" style="color: #38a169 !important;"><?= number_format($stats->total_sesuai ?? 0) ?></div>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                <div class="kpi-card kpi-red">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-title">Revisi / Ditolak</div>
                            <div class="kpi-value text-danger" style="color: #e53e3e !important;"><?= number_format(($stats->total_revisi ?? 0) + ($stats->total_ditolak ?? 0)) ?></div>
                        </div>
                        <div class="kpi-icon-wrapper">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card-modern">
            <div class="card-header-modern flex-wrap">
                <div class="d-flex align-items-center flex-wrap">
                    <a href="<?= base_url('po_system') ?>" class="filter-pill <?= empty($cur_status) ? 'active' : '' ?>">
                        Semua Status (<?= $stats->total ?? 0 ?>)
                    </a>
                    <a href="<?= base_url('po_system?status=menunggu_kroscek') ?>" class="filter-pill <?= $cur_status == 'menunggu_kroscek' ? 'active-amber' : '' ?>">
                        <i class="fas fa-clock mr-1"></i> Menunggu Kroscek (<?= $stats->total_menunggu ?? 0 ?>)
                    </a>
                    <a href="<?= base_url('po_system?status=sesuai') ?>" class="filter-pill <?= $cur_status == 'sesuai' ? 'active-green' : '' ?>">
                        <i class="fas fa-check mr-1"></i> Sesuai / Lolos (<?= $stats->total_sesuai ?? 0 ?>)
                    </a>
                    <a href="<?= base_url('po_system?status=revisi') ?>" class="filter-pill <?= $cur_status == 'revisi' ? 'active-red' : '' ?>">
                        <i class="fas fa-undo mr-1"></i> Perlu Revisi (<?= $stats->total_revisi ?? 0 ?>)
                    </a>
                    <a href="<?= base_url('po_system?status=ditolak') ?>" class="filter-pill <?= $cur_status == 'ditolak' ? 'active' : '' ?>">
                        <i class="fas fa-ban mr-1"></i> Ditolak (<?= $stats->total_ditolak ?? 0 ?>)
                    </a>
                </div>

                <div class="mt-2 mt-md-0">
                    <a href="<?= base_url('po_system/tambah') ?>" class="btn btn-primary shadow-sm font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-plus-circle mr-1"></i> Input PO Baru
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table-modern" id="table-po-masuk">
                        <thead>
                            <tr>
                                <th width="4%" class="text-center">#</th>
                                <th width="15%">No PO Customer</th>
                                <th width="20%">Customer / Instansi</th>
                                <th width="11%" class="text-center">Tgl PO</th>
                                <th width="11%" class="text-center">Tgl Diterima</th>
                                <th width="15%" class="text-right">Nominal PO</th>
                                <th width="12%" class="text-center">Status Kroscek</th>
                                <th width="7%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($po_list)): ?>
                                <?php $no = 1; foreach ($po_list as $row): ?>
                                    <?php 
                                        $id_enc = encrypt($row->id_po);
                                        $nama_cust = !empty($row->nama_customer) ? $row->nama_customer : ($row->master_nama_customer ?? '-');
                                    ?>
                                    <tr>
                                        <td class="text-center text-muted small"><?= $no++ ?></td>
                                        <td>
                                            <a href="<?= base_url('po_system/detail/' . $id_enc) ?>" class="font-weight-bold text-primary" style="font-size: 0.95rem;">
                                                <?= htmlspecialchars($row->no_po) ?>
                                            </a>
                                            <?php if (!empty($row->kode_masuk)): ?>
                                                <div class="small text-muted mt-1"><i class="fas fa-barcode mr-1"></i><?= $row->kode_masuk ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark"><?= htmlspecialchars($nama_cust) ?></div>
                                            <?php if (!empty($row->nama_sales)): ?>
                                                <small class="text-muted"><i class="fas fa-user-tie mr-1"></i>Sales: <?= htmlspecialchars($row->nama_sales) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center text-dark">
                                            <?= !empty($row->tanggal_po) ? date('d-m-Y', strtotime($row->tanggal_po)) : '-' ?>
                                        </td>
                                        <td class="text-center text-muted">
                                            <?= !empty($row->tanggal_terima) ? date('d-m-Y', strtotime($row->tanggal_terima)) : '-' ?>
                                        </td>
                                        <td class="text-right">
                                            <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                Rp. <?= number_format($row->nominal_po, 0, ',', '.') ?>,-
                                            </div>
                                            <?php if ($row->total_item > 0): ?>
                                                <small class="text-muted"><?= $row->total_item ?> Item Barang</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row->status == 'menunggu_kroscek'): ?>
                                                <span class="badge-modern badge-menunggu"><i class="fas fa-clock"></i> Menunggu Kroscek</span>
                                            <?php elseif ($row->status == 'sesuai'): ?>
                                                <span class="badge-modern badge-sesuai"><i class="fas fa-check-circle"></i> Sesuai / Lolos</span>
                                            <?php elseif ($row->status == 'revisi'): ?>
                                                <span class="badge-modern badge-revisi"><i class="fas fa-undo"></i> Perlu Revisi</span>
                                            <?php elseif ($row->status == 'ditolak'): ?>
                                                <span class="badge-modern badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                                            <?php endif; ?>

                                            <?php if (!empty($row->nama_kroscek)): ?>
                                                <div class="text-muted mt-1" style="font-size: 11px;">Oleh: <?= htmlspecialchars($row->nama_kroscek) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url('po_system/detail/' . $id_enc) ?>" class="btn btn-sm btn-outline-primary btn-action" title="Detail & Kroscek">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                <?php if (isAdmin()): ?>
                                                    <a href="<?= base_url('po_system/hapus/' . $id_enc) ?>" class="btn btn-sm btn-outline-danger btn-action ml-1" title="Hapus PO" onclick="return confirm('Apakah Anda yakin ingin menghapus data PO No: <?= htmlspecialchars($row->no_po, ENT_QUOTES) ?>?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <div class="py-4">
                                            <i class="fas fa-folder-open fa-3x text-secondary mb-3" style="opacity: 0.4;"></i>
                                            <h5 class="text-muted font-weight-bold">Belum Ada Data PO Masuk</h5>
                                            <p class="small text-muted mb-3">Dokumen PO yang diinput akan muncul di sini untuk dikroscek oleh tim CRO.</p>
                                            <a href="<?= base_url('po_system/tambah') ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus-circle mr-1"></i> Mulai Input PO Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.DataTable && $('#table-po-masuk tbody tr').length > 0 && !$('#table-po-masuk tbody td').hasClass('py-5')) {
            $('#table-po-masuk').DataTable({
                "language": {
                    "search": "Cari Cepat:",
                    "lengthMenu": "Tampilkan _MENU_ baris",
                    "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data yang cocok",
                    "zeroRecords": "Tidak ditemukan data yang sesuai pencarian",
                    "paginate": {
                        "next": "<i class='fas fa-chevron-right'></i>",
                        "previous": "<i class='fas fa-chevron-left'></i>"
                    }
                },
                "order": [[0, "asc"]],
                "pageLength": 25
            });
        }
    });
</script>
