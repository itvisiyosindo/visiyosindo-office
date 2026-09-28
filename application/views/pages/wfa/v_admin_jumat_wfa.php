<header class="page-header">
    <h2><i class="fas fa-chart-line"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --adm-primary: #0d4d7f;
        --adm-secondary: #1e7f89;
        --adm-card: #ffffff;
        --adm-bg: #f2f7fb;
        --adm-text: #1f2937;
        --adm-muted: #64748b;
    }

    .adm-shell {
        background: linear-gradient(180deg, #e9f2fb 0%, #f7fbff 100%);
        border-radius: 16px;
        padding: 1.2rem;
    }

    .adm-hero {
        background: linear-gradient(135deg, var(--adm-primary) 0%, var(--adm-secondary) 100%);
        color: #fff;
        border-radius: 14px;
        padding: 1.2rem;
        margin-bottom: 1rem;
    }

    .adm-hero h3 {
        margin: 0;
        font-size: 1.22rem;
        font-weight: 700;
    }

    .adm-hero p {
        margin: 0.4rem 0 0;
        opacity: 1;
        color: #f8fbff;
        font-size: 0.92rem;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.28);
    }

    .adm-filter {
        background: var(--adm-card);
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 8px 18px rgba(13, 77, 127, 0.08);
        margin-bottom: 1rem;
    }

    .adm-filter .adm-filter-row {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .adm-filter .adm-filter-help {
        display: inline-block;
        margin-top: 0.35rem;
    }

    .adm-stats {
        display: grid;
        grid-template-columns: repeat(6, minmax(120px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .stat {
        background: var(--adm-card);
        border-radius: 12px;
        padding: 0.9rem;
        box-shadow: 0 8px 18px rgba(13, 77, 127, 0.08);
    }

    .stat .num {
        font-size: 1.58rem;
        line-height: 1;
        font-weight: 800;
        color: var(--adm-primary);
    }

    .stat .txt {
        margin-top: 0.2rem;
        color: var(--adm-muted);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .adm-card {
        background: var(--adm-card);
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 8px 18px rgba(13, 77, 127, 0.08);
    }

    .adm-card h4 {
        margin: 0 0 0.8rem;
        font-size: 1rem;
        font-weight: 700;
        color: var(--adm-text);
    }

    .adm-table th {
        background: #edf4fb;
        color: #3a4d5f;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .adm-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .adm-card .dataTables_wrapper .dataTables_filter input,
    .adm-card .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #d5e1ec;
        min-height: 34px;
    }

    .adm-card .dataTables_wrapper .dataTables_info {
        color: var(--adm-muted);
        font-size: 0.85rem;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 0.24rem 0.62rem;
        line-height: 1.2;
        white-space: nowrap;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .status-pill.pending { background: #fff6df; color: #8a6500; }
    .status-pill.inprogress { background: #e8f2ff; color: #0d4d7f; }
    .status-pill.approved { background: #e8f7ef; color: #137a44; }
    .status-pill.rejected { background: #fdecec; color: #b42318; }

    @media (max-width: 992px) {
        .adm-stats {
            grid-template-columns: repeat(2, minmax(130px, 1fr));
        }
    }
</style>

<?php
$total = (int) ($summary->total ?? 0);
$menunggu_mengetahui = (int) ($summary->menunggu_mengetahui ?? 0);
$menunggu_memverifikasi = (int) ($summary->menunggu_memverifikasi ?? 0);
$menunggu_menyetujui = (int) ($summary->menunggu_menyetujui ?? 0);
$disetujui = (int) ($summary->disetujui ?? 0);
$ditolak = (int) ($summary->ditolak ?? 0);
$selected_date_display = date('d-m-Y', strtotime($selected_date));
?>

<div class="adm-shell">
    <div class="adm-hero">
        <h3>Dashboard Monitoring WFA Jumat</h3>
        <p>Rekap pengajuan WFA berdasarkan tanggal Jumat terpilih, untuk kebutuhan monitoring administrator.</p>
    </div>

    <div class="adm-filter">
        <?= form_open(base_url('wfa_pengajuan/show/admin_jumat'), ['method' => 'GET']); ?>
            <div class="row adm-filter-row">
                <div class="col-md-4 form-group mb-0">
                    <label for="tanggal_wfa" class="font-weight-bold">Tanggal Monitoring</label>
                    <div class="input-group input-daterange" data-plugin-datepicker data-plugin-options='{"format":"dd-mm-yyyy"}'>
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" class="form-control" id="tanggal_wfa" name="tanggal_wfa" value="<?= $selected_date_display ?>">
                    </div>
                </div>
                <div class="col-md-2 form-group mb-0">
                    <button type="submit" class="btn btn-primary btn-block">Tampilkan</button>
                </div>
            </div>
            <small class="text-muted adm-filter-help">Jumat terdekat: <?= date('d-m-Y', strtotime($next_friday)) ?></small>
        <?= form_close(); ?>

        <?php if (!$selected_is_friday): ?>
            <div class="alert alert-warning mt-3 mb-0">
                Tanggal yang dipilih bukan hari Jumat. Data tetap ditampilkan, namun disarankan filter ke hari Jumat.
            </div>
        <?php endif; ?>
    </div>

    <div class="adm-stats">
        <div class="stat">
            <div class="num"><?= $total ?></div>
            <div class="txt">Total Pengajuan</div>
        </div>
        <div class="stat">
            <div class="num"><?= $menunggu_mengetahui ?></div>
            <div class="txt">Menunggu Mengetahui</div>
        </div>
        <div class="stat">
            <div class="num"><?= $menunggu_memverifikasi ?></div>
            <div class="txt">Menunggu Memverifikasi</div>
        </div>
        <div class="stat">
            <div class="num"><?= $menunggu_menyetujui ?></div>
            <div class="txt">Menunggu Menyetujui</div>
        </div>
        <div class="stat">
            <div class="num"><?= $disetujui ?></div>
            <div class="txt">Disetujui</div>
        </div>
        <div class="stat">
            <div class="num"><?= $ditolak ?></div>
            <div class="txt">Ditolak</div>
        </div>
    </div>

    <div class="adm-card">
        <h4><i class="bx bx-list-ul"></i> Daftar Karyawan Pengaju WFA</h4>
        <div class="table-responsive">
            <table id="table-admin-jumat-wfa" class="table table-bordered table-hover table-sm adm-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="18%">Kode</th>
                        <th>Nama Karyawan</th>
                        <th>Mengetahui</th>
                        <th>Memverifikasi</th>
                        <th>Menyetujui</th>
                        <th width="14%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_pengajuan)): ?>
                        <?php $no = 1; foreach ($list_pengajuan as $row): ?>
                            <?php
                            $badge = '<span class="status-pill pending">Menunggu Mengetahui</span>';
                            if ((int) $row->status === 1) {
                                $badge = '<span class="status-pill inprogress">Menunggu Memverifikasi</span>';
                            } elseif ((int) $row->status === 2) {
                                $badge = '<span class="status-pill inprogress">Menunggu Menyetujui</span>';
                            } elseif ((int) $row->status === 5) {
                                $badge = '<span class="status-pill approved">Disetujui</span>';
                            } elseif ((int) $row->status === 99) {
                                $badge = '<span class="status-pill rejected">Ditolak</span>';
                            }
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $row->kode_pengajuan ?></td>
                                <td>
                                    <strong><?= $row->nama_pengaju ?></strong>
                                    <div class="text-muted" style="font-size:0.78rem;"><?= !empty($row->jabatan_pengaju) ? $row->jabatan_pengaju : '-' ?></div>
                                </td>
                                <td><?= $row->nama_mengetahui ?></td>
                                <td><?= $row->nama_memverifikasi ?></td>
                                <td><?= $row->nama_menyetujui ?></td>
                                <td class="text-center"><?= $badge ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('wfa_pengajuan/show/detail/' . $row->id) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
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
        if (!$.fn.DataTable || $.fn.DataTable.isDataTable('#table-admin-jumat-wfa')) {
            return;
        }

        $('#table-admin-jumat-wfa').DataTable({
            pageLength: 10,
            autoWidth: false,
            order: [],
            language: {
                search: 'Cari:',
                searchPlaceholder: 'Kode / Nama / Mengetahui / Memverifikasi / Menyetujui',
                emptyTable: 'Belum ada pengajuan WFA pada tanggal ini.',
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
    });
</script>
