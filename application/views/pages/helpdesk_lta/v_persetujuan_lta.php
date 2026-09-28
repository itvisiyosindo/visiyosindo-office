<?php
$role_nav_selected = isset($role_nav_selected) ? $role_nav_selected : 'approver';
$can_access_approval_menu = isset($can_access_approval_menu) ? $can_access_approval_menu : true;
$approval_inbox = isset($approval_inbox) && is_array($approval_inbox) ? $approval_inbox : [];
$approval_history = isset($approval_history) && is_array($approval_history) ? $approval_history : [];
?>



<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;Lumpsum, Akomodasi & Transportasi</h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>Daftar Persetujuan Lumpsum, Akomodasi & Transportasi</span></li>
        </ol>
    </div>
</header>

<div class="d-flex flex-column">
    <div class="card shadow-sm mb-3">
        <div class="card-body p-2 p-sm-3">
            <div class="row align-items-center">
                <div class="col-12 col-sm">
                    <h3 class="h6 mb-1">Daftar Persetujuan LTA</h3>
                    <p class="mb-0 text-muted small">Review pengajuan Lumpsum, Akomodasi, dan Transportasi yang masuk ke approval Anda.</p>
                </div>
                <div class="col-12 col-sm-auto mt-2 mt-sm-0">
                    <a href="<?= base_url('lta_pengajuan/show/pengajuan') ?>" class="btn btn-outline-primary btn-sm d-block d-sm-inline-block">
                        <i class="bx bx-left-arrow-alt"></i> Kembali ke Pengajuan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mx-0 mb-2">
        <div class="col-12 col-md-6 px-0 px-md-2 mb-2 mb-md-0">
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-muted">Menunggu Persetujuan</div>
                            <div class="h5 mb-0 font-weight-bold text-warning"><?= count($approval_inbox) ?></div>
                        </div>
                        <i class="bx bx-time h4 mb-0 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 px-0 px-md-2">
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-muted">Riwayat Persetujuan</div>
                            <div class="h5 mb-0 font-weight-bold text-primary"><?= count($approval_history) ?></div>
                        </div>
                        <i class="bx bx-archive h4 mb-0 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body p-2 p-sm-3">
            <h6 class="mb-2"><i class="bx bx-time"></i> Inbox Persetujuan</h6>
            <div class="table-responsive table-responsive-sm">
                <table id="table-lta-inbox" class="table table-bordered table-hover table-sm w-100 mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Kode</th>
                        <th>Pengaju</th>
                        <th width="10%">Hari Dinas</th>
                        <th width="15%">Transport Udara</th>
                        <th width="15%">Transport Darat</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approval_inbox)): ?>
                        <?php $no = 1; foreach ($approval_inbox as $row): ?>
                            <tr>
                                <td class="text-center" data-label="No"><?= $no++ ?></td>
                                <td data-label="Kode"><?= htmlspecialchars($row->kode_pengajuan) ?></td>
                                <td data-label="Pengaju">
                                    <strong><?= htmlspecialchars($row->nama_pengaju) ?></strong>
                                    <div class="text-muted" style="font-size:.78rem;"><?= !empty($row->jabatan_pengaju) ? htmlspecialchars($row->jabatan_pengaju) : '-' ?></div>
                                </td>
                                <td class="text-center" data-label="Hari Dinas"><?= (int) $row->hari_dinas ?></td>
                                <td class="text-right" data-label="Transport Udara">Rp <?= number_format((int) $row->nominal_udara, 0, ',', '.') ?></td>
                                <td class="text-right" data-label="Transport Darat">Rp <?= number_format((int) $row->nominal_darat, 0, ',', '.') ?></td>
                                <td class="text-center" data-label="Aksi">
                                    <a href="<?= base_url('lta_pengajuan/show/detail/' . encrypt($row->id)) ?>" class="btn btn-sm btn-outline-primary">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body p-2 p-sm-3">
            <h6 class="mb-2"><i class="bx bx-archive"></i> Riwayat Persetujuan</h6>
            <div class="table-responsive table-responsive-sm">
                <table id="table-lta-history" class="table table-bordered table-hover table-sm w-100 mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Kode</th>
                        <th>Pengaju</th>
                        <th width="10%">Hari Dinas</th>
                        <th width="16%">Status</th>
                        <th width="12%">Update</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approval_history)): ?>
                        <?php $no = 1; foreach ($approval_history as $row): ?>
                            <?php
                            $status_chip = ((int) $row->status === 5)
                                ? '<div class="lta-status-badge approved"><i class="bx bx-check-circle"></i> Disetujui</div>'
                                : '<div class="lta-status-badge rejected"><i class="bx bx-x-circle"></i> Ditolak</div>';
                            ?>
                            <tr>
                                <td class="text-center" data-label="No"><?= $no++ ?></td>
                                <td data-label="Kode"><?= htmlspecialchars($row->kode_pengajuan) ?></td>
                                <td data-label="Pengaju"><?= htmlspecialchars($row->nama_pengaju) ?></td>
                                <td class="text-center" data-label="Hari Dinas"><?= (int) $row->hari_dinas ?></td>
                                <td data-label="Status"><?= $status_chip ?></td>
                                <td class="text-center" data-label="Update"><?= !empty($row->updated_at) ? date('d-m-Y', strtotime($row->updated_at)) : '-' ?></td>
                                <td class="text-center" data-label="Aksi">
                                    <a href="<?= base_url('lta_pengajuan/show/detail/' . encrypt($row->id)) ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function initTable(selector, placeholder, emptyText) {
            if (!$.fn.DataTable || $.fn.DataTable.isDataTable(selector)) {
                return;
            }

            var enableScrollX = !window.matchMedia('(max-width: 576px)').matches;
            $(selector).DataTable({
                pageLength: 10,
                autoWidth: false,
                scrollX: enableScrollX,
                scrollCollapse: true,
                order: [],
                language: {
                    search: 'Cari:',
                    searchPlaceholder: placeholder,
                    emptyTable: emptyText,
                    zeroRecords: 'Data tidak ditemukan.',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 data',
                    paginate: {
                        first: '<<',
                        last: '>>',
                        next: '>',
                        previous: '<'
                    }
                }
            });
        }

        initTable('#table-lta-inbox', 'Kode / Pengaju / Hari Dinas', 'Tidak ada pengajuan menunggu persetujuan.');
        initTable('#table-lta-history', 'Kode / Pengaju / Status', 'Belum ada riwayat persetujuan.');
    });
</script>
