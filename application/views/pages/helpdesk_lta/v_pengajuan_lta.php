<?php
$role_nav_selected = isset($role_nav_selected) ? $role_nav_selected : 'pengaju';
$my_submissions = isset($my_submissions) && is_array($my_submissions) ? $my_submissions : [];

// Prefer server-side stats when provided by controller
$stats = isset($stats) && is_array($stats) ? $stats : null;
if ($stats !== null) {
    $pending = (int) ($stats['pending'] ?? 0);
    $approved = (int) ($stats['approved'] ?? 0);
    $rejected = (int) ($stats['rejected'] ?? 0);
} else {
    $pending = 0; $approved = 0; $rejected = 0;
    foreach ($my_submissions as $rs) {
        if ((int) $rs->status === 0) $pending++;
        elseif ((int) $rs->status === 5) $approved++;
        elseif ((int) $rs->status === 99) $rejected++;
    }
}
?>

<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .lta-list-card { background: #fff; border: 1px solid #dee2e6; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .lta-list-card h4 { margin:0 0 12px 0; color: #1a2332; font-weight:700; }
    .table thead th { background: #f8f9fa; color: #1a2332; }
    .lta-table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .lta-table-scroll table { min-width: 720px; }
    .lta-table-scroll th,
    .lta-table-scroll td { white-space: nowrap; }
    @media (max-width: 576px) {
        .lta-list-card { padding: 12px; }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: left;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0;
        }
        .dataTables_wrapper .dataTables_paginate { margin-top: 8px; }
    }
</style>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card lta-list-card">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6c757d">Menunggu Persetujuan</div>
                    <div style="font-size:1.6rem;font-weight:800;color:#7a5c00"><?= $pending ?></div>
                </div>
                <div><i class="bx bx-time" style="font-size:28px;color:#7a5c00"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card lta-list-card">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6c757d">Disetujui</div>
                    <div style="font-size:1.6rem;font-weight:800;color:#0f5f34"><?= $approved ?></div>
                </div>
                <div><i class="bx bx-check" style="font-size:28px;color:#0f5f34"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card lta-list-card">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.85rem;color:#6c757d">Ditolak</div>
                    <div style="font-size:1.6rem;font-weight:800;color:#7d2c1e"><?= $rejected ?></div>
                </div>
                <div><i class="bx bx-x" style="font-size:28px;color:#7d2c1e"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="lta-list-card">
    <h4><i class="bx bx-list-ul"></i> Daftar Pengajuan Saya</h4>
    <div class="table-responsive lta-table-scroll">
        <table id="table-my-lta-list" class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Kode</th>
                    <th width="11%">Hari Dinas</th>
                    <th width="14%">Transport Udara</th>
                    <th width="14%">Transport Darat</th>
                    <th width="14%">Approver</th>
                    <th width="12%">Status</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($my_submissions)): ?>
                    <?php $no = 1; foreach ($my_submissions as $row): ?>
                        <?php
                        if ((int) $row->status === 5) {
                            $status_badge = '<div class="lta-status-badge approved"><i class="bx bx-check-circle"></i> Disetujui</div>';
                        } elseif ((int) $row->status === 99) {
                            $status_badge = '<div class="lta-status-badge rejected"><i class="bx bx-x-circle"></i> Ditolak</div>';
                        } else {
                            $status_badge = '<div class="lta-status-badge pending"><i class="bx bx-hourglass-mid"></i> Menunggu</div>';
                        }
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row->kode_pengajuan) ?></td>
                            <td class="text-center"><?= (int) $row->hari_dinas ?></td>
                            <td class="text-right">Rp <?= number_format((int) $row->nominal_udara, 0, ',', '.') ?></td>
                            <td class="text-right">Rp <?= number_format((int) $row->nominal_darat, 0, ',', '.') ?></td>
                            <td><?= !empty($row->nama_approver) ? htmlspecialchars($row->nama_approver) : '-' ?></td>
                            <td><?= $status_badge ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('lta_pengajuan/show/detail/' . encrypt($row->id)) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#table-my-lta-list')) {
            $('#table-my-lta-list').DataTable({
                pageLength: 10,
                autoWidth: false,
                order: [],
                language: {
                    search: 'Cari:',
                    searchPlaceholder: 'Kode / Status / Approver',
                    emptyTable: 'Belum ada pengajuan.',
                    zeroRecords: 'Data tidak ditemukan.',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 data',
                    paginate: { first: '<<', last: '>>', next: '>', previous: '<' }
                }
            });
        }
    });
</script>
