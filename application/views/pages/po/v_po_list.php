<header class="page-header">
    <h2><i class="fas fa-file-signature text-primary"></i> <?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
            <li><span><?= $page_desc ?></span></li>
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

        <!-- Stat Cards -->
        <div class="row mb-3">
            <div class="col-xl-3 col-sm-6 mb-2">
                <div class="card card-sm border-left-primary shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total PO Masuk</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->total ?? 0) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-invoice fa-2x text-gray-300 text-primary" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-2">
                <div class="card card-sm border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Kroscek</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->total_menunggu ?? 0) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-warning" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-2">
                <div class="card card-sm border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sesuai / Lolos Kroscek</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats->total_sesuai ?? 0) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-double fa-2x text-success" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-2">
                <div class="card card-sm border-left-danger shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Perlu Revisi / Ditolak</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format(($stats->total_revisi ?? 0) + ($stats->total_ditolak ?? 0)) ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-danger" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <section class="card shadow-sm">
            <header class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
                <div class="card-actions-left mb-2 mb-md-0">
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('po_system') ?>" class="btn btn-sm <?= empty($cur_status) ? 'btn-primary' : 'btn-outline-secondary' ?>">
                            Semua (<?= $stats->total ?? 0 ?>)
                        </a>
                        <a href="<?= base_url('po_system?status=menunggu_kroscek') ?>" class="btn btn-sm <?= $cur_status == 'menunggu_kroscek' ? 'btn-warning text-dark font-weight-bold' : 'btn-outline-warning text-dark' ?>">
                            <i class="fas fa-clock mr-1"></i> Menunggu Kroscek (<?= $stats->total_menunggu ?? 0 ?>)
                        </a>
                        <a href="<?= base_url('po_system?status=sesuai') ?>" class="btn btn-sm <?= $cur_status == 'sesuai' ? 'btn-success' : 'btn-outline-success' ?>">
                            <i class="fas fa-check mr-1"></i> Sesuai (<?= $stats->total_sesuai ?? 0 ?>)
                        </a>
                        <a href="<?= base_url('po_system?status=revisi') ?>" class="btn btn-sm <?= $cur_status == 'revisi' ? 'btn-danger' : 'btn-outline-danger' ?>">
                            <i class="fas fa-undo mr-1"></i> Revisi (<?= $stats->total_revisi ?? 0 ?>)
                        </a>
                        <a href="<?= base_url('po_system?status=ditolak') ?>" class="btn btn-sm <?= $cur_status == 'ditolak' ? 'btn-secondary' : 'btn-outline-secondary' ?>">
                            <i class="fas fa-ban mr-1"></i> Ditolak (<?= $stats->total_ditolak ?? 0 ?>)
                        </a>
                    </div>
                </div>
                <div class="card-actions-right">
                    <a href="<?= base_url('po_system/tambah') ?>" class="btn btn-sm btn-primary shadow-sm font-weight-bold">
                        <i class="fas fa-plus-circle mr-1"></i> Input PO Baru
                    </a>
                </div>
            </header>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="table-po-masuk" style="width: 100%;">
                        <thead class="bg-light">
                            <tr>
                                <th width="4%" class="text-center">No</th>
                                <th width="12%">No PO Customer</th>
                                <th width="18%">Customer / Instansi</th>
                                <th width="10%" class="text-center">Tgl PO</th>
                                <th width="10%" class="text-center">Tgl Diterima</th>
                                <th width="14%" class="text-right">Nominal PO</th>
                                <th width="13%" class="text-center">Status Kroscek</th>
                                <th width="10%">Diinput Oleh</th>
                                <th width="9%" class="text-center">Aksi</th>
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
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <a href="<?= base_url('po_system/detail/' . $id_enc) ?>" class="font-weight-bold text-primary">
                                                <?= htmlspecialchars($row->no_po) ?>
                                            </a>
                                            <?php if (!empty($row->kode_masuk)): ?>
                                                <br><small class="text-muted"><i class="fas fa-barcode"></i> <?= $row->kode_masuk ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($nama_cust) ?></strong>
                                            <?php if (!empty($row->nama_sales)): ?>
                                                <br><small class="text-muted"><i class="fas fa-user-tie"></i> Sales: <?= htmlspecialchars($row->nama_sales) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= !empty($row->tanggal_po) ? date('d-m-Y', strtotime($row->tanggal_po)) : '-' ?>
                                        </td>
                                        <td class="text-center">
                                            <?= !empty($row->tanggal_terima) ? date('d-m-Y', strtotime($row->tanggal_terima)) : '-' ?>
                                        </td>
                                        <td class="text-right font-weight-bold text-dark">
                                            Rp. <?= number_format($row->nominal_po, 0, ',', '.') ?>,-
                                            <?php if ($row->total_item > 0): ?>
                                                <br><small class="text-muted font-weight-normal"><?= $row->total_item ?> Item Barang</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row->status == 'menunggu_kroscek'): ?>
                                                <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Menunggu Kroscek</span>
                                            <?php elseif ($row->status == 'sesuai'): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Sesuai / Disetujui</span>
                                            <?php elseif ($row->status == 'revisi'): ?>
                                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-undo mr-1"></i> Perlu Revisi</span>
                                            <?php elseif ($row->status == 'ditolak'): ?>
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                            <?php endif; ?>

                                            <?php if (!empty($row->nama_kroscek)): ?>
                                                <br><small class="text-muted" style="font-size: 11px;">Oleh: <?= htmlspecialchars($row->nama_kroscek) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($row->nama_pembuat ?? '-') ?>
                                            <br><small class="text-muted"><?= date('d/m/y H:i', strtotime($row->created_at)) ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url('po_system/detail/' . $id_enc) ?>" class="btn btn-sm btn-info" title="Detail & Kroscek PO">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                <?php if (isAdmin()): ?>
                                                    <a href="<?= base_url('po_system/hapus/' . $id_enc) ?>" class="btn btn-sm btn-danger" title="Hapus Data" onclick="return confirm('Apakah Anda yakin ingin menghapus data PO No: <?= htmlspecialchars($row->no_po, ENT_QUOTES) ?>?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-2 d-block text-secondary"></i>
                                        Belum ada data PO masuk<?= !empty($cur_status) ? ' dengan status ini' : '' ?>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.DataTable && $('#table-po-masuk tbody tr').length > 0 && !$('#table-po-masuk tbody td').hasClass('text-muted')) {
            $('#table-po-masuk').DataTable({
                "language": {
                    "search": "Cari PO:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ PO",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Lanjut",
                        "previous": "Sebelum"
                    }
                },
                "order": [[0, "asc"]],
                "pageLength": 25
            });
        }
    });
</script>
