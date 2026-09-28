<header class="page-header">
    <h2><i class="fas fa-user-check"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --app-primary: #0f4c81;
        --app-cyan: #197d8f;
        --app-bg: #f3f7fb;
        --app-text: #1f2937;
        --app-muted: #64748b;
        --app-card: #ffffff;
    }

    .approval-shell {
        background: linear-gradient(180deg, #e8f1fb 0%, #f6f9fc 100%);
        border-radius: 16px;
        padding: 1.2rem;
    }

    .approval-head {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        background: linear-gradient(135deg, var(--app-primary) 0%, var(--app-cyan) 100%);
        color: #fff;
        border-radius: 14px;
        padding: 1.15rem 1.2rem;
        margin-bottom: 1rem;
    }

    .approval-head h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .approval-head p {
        margin: 0.35rem 0 0;
        opacity: 1;
        color: #f8fbff;
        font-size: 0.92rem;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.28);
    }

    .approval-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .stat-box {
        background: var(--app-card);
        border-radius: 12px;
        padding: 0.95rem;
        box-shadow: 0 6px 18px rgba(15, 76, 129, 0.08);
    }

    .stat-box .number {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--app-primary);
        line-height: 1;
    }

    .stat-box .label {
        color: var(--app-muted);
        font-size: 0.83rem;
        margin-top: 0.25rem;
    }

    .approval-card {
        background: var(--app-card);
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 6px 18px rgba(15, 76, 129, 0.08);
        margin-bottom: 1rem;
    }

    .approval-card h4 {
        margin: 0 0 0.8rem;
        font-size: 1rem;
        font-weight: 700;
        color: var(--app-text);
    }

    .approval-table th {
        background: #edf4fb;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #3c4e60;
    }

    .approval-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .approval-card .dataTables_wrapper .dataTables_filter input,
    .approval-card .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #d5e1ec;
        min-height: 34px;
    }

    .approval-card .dataTables_wrapper .dataTables_info {
        color: var(--app-muted);
        font-size: 0.85rem;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.22rem 0.58rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .chip.mengetahui {
        background: #fff0db;
        color: #976300;
    }

    .chip.menyetujui {
        background: #e8f2ff;
        color: #0f4c81;
    }

    .chip.memverifikasi {
        background: #e9f8f2;
        color: #0f7356;
    }

    .chip.approved {
        background: #e8f7ef;
        color: #137a44;
    }

    .chip.rejected {
        background: #fdecec;
        color: #b42318;
    }

    @media (max-width: 768px) {
        .approval-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="approval-shell">
    <div class="approval-head">
        <div>
            <h3>Inbox Persetujuan WFA</h3>
            <p>Review pengajuan yang perlu aksi Anda sebagai Mengetahui atau Menyetujui.</p>
        </div>
        <div>
            <a href="<?= base_url('wfa_pengajuan/show/pengajuan') ?>" class="btn btn-light btn-sm">
                <i class="bx bx-plus"></i> Buat Pengajuan Baru
            </a>
        </div>
    </div>

    <div class="approval-stats">
        <div class="stat-box">
            <div class="number"><?= count($approval_inbox) ?></div>
            <div class="label">Menunggu Aksi Saya</div>
        </div>
        <div class="stat-box">
            <div class="number"><?= count($approval_history) ?></div>
            <div class="label">Riwayat Persetujuan</div>
        </div>
    </div>

    <div class="approval-card">
        <h4><i class="bx bx-time-five"></i> Menunggu Tindak Lanjut</h4>
        <div class="table-responsive">
            <table id="table-approval-inbox" class="table table-bordered table-hover table-sm approval-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Kode</th>
                        <th>Pengaju</th>
                        <th width="13%">Tanggal WFA</th>
                        <th width="15%">Tahap</th>
                        <th width="12%">Dibuat</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approval_inbox)): ?>
                        <?php $no = 1; foreach ($approval_inbox as $row): ?>
                            <?php
                            $tahap_chip = '<span class="chip mengetahui">Tahap Mengetahui</span>';
                            if ((int) $row->status === 1) {
                                $tahap_chip = '<span class="chip memverifikasi">Tahap Memverifikasi</span>';
                            } elseif ((int) $row->status === 2) {
                                $tahap_chip = '<span class="chip menyetujui">Tahap Menyetujui</span>';
                            }
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $row->kode_pengajuan ?></td>
                                <td>
                                    <strong><?= $row->nama_pengaju ?></strong>
                                    <div class="text-muted" style="font-size:0.78rem;"><?= !empty($row->jabatan_pengaju) ? $row->jabatan_pengaju : '-' ?></div>
                                </td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal_wfa)) ?></td>
                                <td class="text-center"><?= $tahap_chip ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row->created_at)) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('wfa_pengajuan/show/detail/' . $row->id) ?>" class="btn btn-sm btn-outline-primary">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="approval-card">
        <h4><i class="bx bx-archive"></i> Riwayat Persetujuan</h4>
        <div class="table-responsive">
            <table id="table-approval-history" class="table table-bordered table-hover table-sm approval-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Kode</th>
                        <th>Pengaju</th>
                        <th width="13%">Tanggal WFA</th>
                        <th width="14%">Status Akhir</th>
                        <th width="12%">Update</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($approval_history)): ?>
                        <?php $no = 1; foreach ($approval_history as $row): ?>
                            <?php
                            $status_chip = ((int) $row->status === 5)
                                ? '<span class="chip approved">Disetujui</span>'
                                : '<span class="chip rejected">Ditolak</span>';
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $row->kode_pengajuan ?></td>
                                <td><?= $row->nama_pengaju ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal_wfa)) ?></td>
                                <td class="text-center"><?= $status_chip ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row->updated_at)) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('wfa_pengajuan/show/detail/' . $row->id) ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
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
        function initApprovalDataTable(selector, placeholder, emptyText) {
            if (!$.fn.DataTable || $.fn.DataTable.isDataTable(selector)) {
                return;
            }

            $(selector).DataTable({
                pageLength: 10,
                autoWidth: false,
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

        initApprovalDataTable('#table-approval-inbox', 'Kode / Pengaju / Mengetahui / Memverifikasi / Menyetujui', 'Tidak ada pengajuan yang menunggu tindakan Anda.');
        initApprovalDataTable('#table-approval-history', 'Kode / Pengaju / Status', 'Belum ada riwayat persetujuan.');
    });
</script>
