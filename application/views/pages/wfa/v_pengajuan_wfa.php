<header class="page-header">
    <h2><i class="fas fa-laptop-house"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --wfa-primary: #0f4c81;
        --wfa-secondary: #1f7a8c;
        --wfa-accent: #f4b942;
        --wfa-surface: #ffffff;
        --wfa-bg: #f4f8fb;
        --wfa-text: #1f2933;
        --wfa-muted: #64748b;
        --wfa-success-bg: #e8f7ef;
        --wfa-success-text: #137a44;
        --wfa-danger-bg: #fdecec;
        --wfa-danger-text: #b42318;
        --wfa-warning-bg: #fff6df;
        --wfa-warning-text: #8a6500;
        --wfa-radius: 14px;
    }

    .wfa-shell {
        background: radial-gradient(circle at 10% 0%, #e1ecf8 0, transparent 35%),
                    radial-gradient(circle at 90% 100%, #d8efe9 0, transparent 30%),
                    var(--wfa-bg);
        border-radius: 18px;
        padding: 1.5rem;
    }

    .wfa-hero {
        background: linear-gradient(130deg, var(--wfa-primary) 0%, var(--wfa-secondary) 60%, #2d8ba2 100%);
        color: #fff;
        border-radius: var(--wfa-radius);
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        position: relative;
        overflow: hidden;
    }

    .wfa-hero::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        right: -50px;
        top: -60px;
    }

    .wfa-hero h3 {
        margin: 0;
        font-size: 1.45rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .wfa-hero p {
        margin: 0.45rem 0 0;
        opacity: 1;
        color: #f8fbff;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.28);
        position: relative;
        z-index: 1;
    }

    .wfa-grid {
        display: grid;
        grid-template-columns: 1.05fr 1.4fr;
        gap: 1rem;
    }

    .wfa-card {
        background: var(--wfa-surface);
        border-radius: var(--wfa-radius);
        padding: 1.1rem;
        box-shadow: 0 8px 20px rgba(15, 76, 129, 0.08);
    }

    .wfa-card h4 {
        margin: 0 0 0.75rem;
        color: var(--wfa-text);
        font-weight: 700;
        font-size: 1.03rem;
    }

    .wfa-list-step {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .wfa-list-step li {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        color: var(--wfa-text);
        align-items: flex-start;
        font-size: 0.92rem;
    }

    .wfa-list-step li:last-child {
        margin-bottom: 0;
    }

    .wfa-list-step li i {
        color: var(--wfa-accent);
        margin-top: 0.12rem;
    }

    .wfa-date-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #eaf3ff;
        color: var(--wfa-primary);
        border-radius: 999px;
        padding: 0.45rem 0.85rem;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .wfa-form .form-group {
        margin-bottom: 0.8rem;
    }

    .wfa-form label {
        color: var(--wfa-text);
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .wfa-form .form-control {
        border-radius: 10px;
        border: 1px solid #d9e4ef;
    }

    .wfa-form .form-control:focus {
        border-color: var(--wfa-secondary);
        box-shadow: 0 0 0 0.12rem rgba(31, 122, 140, 0.18);
    }

    .wfa-approval-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.85rem;
    }

    .wfa-approval-field {
        margin-bottom: 0;
        min-width: 0;
    }

    .wfa-approval-field label {
        display: flex;
        align-items: center;
        min-height: 22px;
        margin: 0 0 0.3rem;
    }

    .wfa-approval-field .select2-container {
        width: 100% !important;
        margin-top: 0 !important;
    }

    .wfa-approval-field .select2-container .select2-selection--single {
        height: 38px !important;
        border-radius: 10px;
        border: 1px solid #d9e4ef;
    }

    .wfa-approval-field .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 26px;
    }

    .wfa-approval-field .select2-container .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }

    .btn-wfa-primary {
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--wfa-primary) 0%, var(--wfa-secondary) 100%);
        color: #fff;
        padding: 0.6rem 1.15rem;
        font-weight: 600;
    }

    .btn-wfa-primary:hover {
        color: #fff;
        filter: brightness(1.05);
    }

    .wfa-table-wrap {
        margin-top: 1rem;
    }

    .wfa-table-wrap table {
        margin-bottom: 0;
    }

    .wfa-table-wrap th {
        background: #f0f6fb;
        color: #304457;
        border-bottom: 0;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .wfa-table-wrap td {
        vertical-align: middle;
        font-size: 0.92rem;
    }

    .wfa-table-wrap .dataTables_wrapper .dataTables_filter input,
    .wfa-table-wrap .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #d9e4ef;
        min-height: 34px;
    }

    .wfa-table-wrap .dataTables_wrapper .dataTables_info {
        color: var(--wfa-muted);
        font-size: 0.85rem;
    }

    .badge-status {
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        display: inline-flex;
    }

    .badge-status.pending {
        background: var(--wfa-warning-bg);
        color: var(--wfa-warning-text);
    }

    .badge-status.progress {
        background: #e6f4ff;
        color: #145ea8;
    }

    .badge-status.approved {
        background: var(--wfa-success-bg);
        color: var(--wfa-success-text);
    }

    .badge-status.rejected {
        background: var(--wfa-danger-bg);
        color: var(--wfa-danger-text);
    }

    @media (max-width: 991px) {
        .wfa-grid {
            grid-template-columns: 1fr;
        }

        .wfa-approval-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
$next_friday_display = !empty($next_friday) ? date('d-m-Y', strtotime($next_friday)) : date('d-m-Y');
$memverifikasi_name = !empty($memverifikasi_user) && !empty($memverifikasi_user->nama) ? $memverifikasi_user->nama : 'Amtisari';
$memverifikasi_jabatan = !empty($memverifikasi_user) && !empty($memverifikasi_user->jabatan) ? $memverifikasi_user->jabatan : 'General Affair';
$memverifikasi_office_name = !empty($memverifikasi_office_user) && !empty($memverifikasi_office_user->nama) ? $memverifikasi_office_user->nama : 'General Affairs';
$memverifikasi_office_jabatan = !empty($memverifikasi_office_user) && !empty($memverifikasi_office_user->jabatan) ? $memverifikasi_office_user->jabatan : 'General Affairs';
$menyetujui_name = !empty($menyetujui_user) && !empty($menyetujui_user->nama) ? $menyetujui_user->nama : 'Dian Melati';
$menyetujui_jabatan = !empty($menyetujui_user) && !empty($menyetujui_user->jabatan) ? $menyetujui_user->jabatan : 'HR & Legal';
$menyetujui_office_name = !empty($menyetujui_office_user) && !empty($menyetujui_office_user->nama) ? $menyetujui_office_user->nama : 'HR & Legal';
$menyetujui_office_jabatan = !empty($menyetujui_office_user) && !empty($menyetujui_office_user->jabatan) ? $menyetujui_office_user->jabatan : 'HR & Legal';
$memverifikasi_label = $memverifikasi_name . ' | ' . $memverifikasi_jabatan . ' / ' . $memverifikasi_office_name . ' | ' . $memverifikasi_office_jabatan;
$menyetujui_label = $menyetujui_name . ' | ' . $menyetujui_jabatan . ' / ' . $menyetujui_office_name . ' | ' . $menyetujui_office_jabatan;
?>

<div class="wfa-shell">
    <div class="wfa-hero">
        <h3>Pengajuan WFA Karyawan</h3>
        <p>Ajukan WFA dengan alur mengetahui, memverifikasi, dan menyetujui yang jelas, cepat, dan terdokumentasi.</p>
    </div>

    <div class="wfa-grid">
        <div class="wfa-card">
            <h4><i class="bx bx-bulb"></i> Ringkasan Alur</h4>
            <ul class="wfa-list-step">
                <li><i class="bx bx-check-circle"></i><span>Pilih tanggal WFA (wajib hari Jumat).</span></li>
                <li><i class="bx bx-check-circle"></i><span>Pilih Kepala Divisi pada tahap Mengetahui. Jika tidak memiliki Kepala Divisi, centang opsi khusus agar lanjut langsung ke Memverifikasi.</span></li>
                <li><i class="bx bx-check-circle"></i><span>Lokasi kerja otomatis ditetapkan sebagai Rumah.</span></li>
                <li><i class="bx bx-check-circle"></i><span>Sistem mengirim notifikasi WhatsApp otomatis.</span></li>
                <li><i class="bx bx-check-circle"></i><span>Progress approval bisa dipantau pada riwayat pengajuan.</span></li>
            </ul>
            <div class="mt-3">
                <span class="wfa-date-badge"><i class="bx bx-calendar"></i> Jumat Terdekat: <?= $next_friday_display ?></span>
            </div>
        </div>

        <div class="wfa-card">
            <h4><i class="bx bx-edit"></i> Form Pengajuan WFA</h4>
            <?= form_open('#', ['id' => 'form-pengajuan-wfa', 'autocomplete' => 'off', 'class' => 'wfa-form']); ?>
                <div class="form-group">
                    <label for="tanggal_wfa">Tanggal WFA <span class="text-danger">*</span></label>
                    <div class="input-group input-daterange" data-plugin-datepicker data-plugin-options='{"format":"dd-mm-yyyy"}'>
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" class="form-control" id="tanggal_wfa" name="tanggal_wfa" value="<?= $next_friday_display ?>" required>
                    </div>
                    <small class="text-muted">Sistem hanya menerima tanggal hari Jumat.</small>
                </div>

                <div class="form-group">
                    <label for="rencana_pekerjaan">Rencana Pekerjaan</label>
                    <textarea class="form-control" id="rencana_pekerjaan" name="rencana_pekerjaan" rows="2" placeholder="Aktivitas utama yang dikerjakan saat WFA"></textarea>
                </div>

                <div class="form-group">
                    <label>Lokasi Kerja</label>
                    <input type="text" class="form-control" value="Rumah" readonly>
                    <small class="text-muted">Lokasi kerja otomatis diset sebagai Rumah.</small>
                </div>

                <div class="wfa-approval-grid">
                    <div class="form-group wfa-approval-field">
                        <label for="id_mengetahui">Mengetahui <span class="text-danger">*</span></label>
                        <select data-plugin-selectTwo class="form-control populate" id="id_mengetahui" name="id_mengetahui" required>
                            <option value="">Pilih Mengetahui</option>
                            <?php foreach ($list_approver as $row): ?>
                                <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?><?= !empty($row->jabatan) ? ' | ' . $row->jabatan : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-1">Mengetahui diisi oleh Kepala Divisi pengaju.</small>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="tanpa_kepala_divisi" name="tanpa_kepala_divisi" value="1">
                            <label class="custom-control-label" for="tanpa_kepala_divisi">Saya tidak memiliki Kepala Divisi (Mengetahui auto-approved)</label>
                        </div>
                    </div>
                    <div class="form-group wfa-approval-field">
                        <label>Memverifikasi</label>
                        <input type="text" class="form-control" value="<?= $memverifikasi_label ?>" readonly>
                        <small class="text-muted">Otomatis Dipilih Oleh Sistem.</small>
                    </div>
                    <div class="form-group wfa-approval-field">
                        <label>Menyetujui</label>
                        <input type="text" class="form-control" value="<?= $menyetujui_label ?>" readonly>
                        <small class="text-muted">Otomatis Dipilih Oleh Sistem.</small>
                    </div>
                </div>

                <div class="text-right mt-2">
                    <button type="button" class="btn btn-wfa-primary" id="btn-ajukan-wfa">
                        <i class="bx bx-send"></i> Ajukan WFA
                    </button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>

    <div class="wfa-card wfa-table-wrap">
        <h4><i class="bx bx-history"></i> Riwayat Pengajuan Saya</h4>
        <div class="table-responsive">
            <table id="table-riwayat-wfa" class="table table-hover table-bordered table-sm">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Kode</th>
                        <th width="12%">Tanggal WFA</th>
                        <th>Mengetahui</th>
                        <th>Memverifikasi</th>
                        <th>Menyetujui</th>
                        <th width="14%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($my_submissions)): ?>
                        <?php $no = 1; foreach ($my_submissions as $row): ?>
                            <?php
                            $status_badge = '<span class="badge-status pending">Menunggu Mengetahui</span>';
                            if ((int) $row->status === 1) {
                                $status_badge = '<span class="badge-status progress">Menunggu Memverifikasi</span>';
                            } elseif ((int) $row->status === 2) {
                                $status_badge = '<span class="badge-status progress">Menunggu Menyetujui</span>';
                            } elseif ((int) $row->status === 5) {
                                $status_badge = '<span class="badge-status approved">Disetujui</span>';
                            } elseif ((int) $row->status === 99) {
                                $status_badge = '<span class="badge-status rejected">Ditolak</span>';
                            }
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $row->kode_pengajuan ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row->tanggal_wfa)) ?></td>
                                <td><?= !empty($row->nama_mengetahui) ? $row->nama_mengetahui : '-' ?></td>
                                <td><?= !empty($row->nama_memverifikasi) ? $row->nama_memverifikasi : '-' ?></td>
                                <td><?= !empty($row->nama_menyetujui) ? $row->nama_menyetujui : '-' ?></td>
                                <td class="text-center"><?= $status_badge ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('wfa_pengajuan/show/detail/' . $row->id) ?>" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
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
    function isFridayDateString(value) {
        const parts = value.split('-');
        if (parts.length !== 3) {
            return false;
        }

        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1;
        const year = parseInt(parts[2], 10);
        const dt = new Date(year, month, day);

        if (Number.isNaN(dt.getTime())) {
            return false;
        }

        return dt.getDay() === 5;
    }

    document.addEventListener('DOMContentLoaded', function() {
        function syncMengetahuiField() {
            const tanpaKepalaDivisi = $('#tanpa_kepala_divisi').is(':checked');
            $('#id_mengetahui').prop('disabled', tanpaKepalaDivisi);

            if (tanpaKepalaDivisi) {
                $('#id_mengetahui').val('').trigger('change');
            }
        }

        syncMengetahuiField();

        $(document).on('change', '#tanpa_kepala_divisi', function() {
            syncMengetahuiField();
        });

        if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#table-riwayat-wfa')) {
            $('#table-riwayat-wfa').DataTable({
                pageLength: 10,
                autoWidth: false,
                order: [],
                language: {
                    search: 'Cari:',
                    searchPlaceholder: 'Kode / Mengetahui / Memverifikasi / Menyetujui',
                    emptyTable: 'Belum ada riwayat pengajuan.',
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

        $(document).on('click', '#btn-ajukan-wfa', function() {
            const tanggal_wfa = $('#tanggal_wfa').val();
            const rencana_pekerjaan = $('#rencana_pekerjaan').val();
            const tanpa_kepala_divisi = $('#tanpa_kepala_divisi').is(':checked') ? 1 : 0;
            const id_mengetahui = tanpa_kepala_divisi ? '' : $('#id_mengetahui').val();

            if (!tanggal_wfa || (!id_mengetahui && tanpa_kepala_divisi !== 1)) {
                Swal.fire('Validasi', 'Tanggal dan mengetahui wajib diisi, kecuali jika opsi tidak memiliki Kepala Divisi dicentang.', 'warning');
                return;
            }

            if (!isFridayDateString(tanggal_wfa)) {
                Swal.fire('Validasi', 'Tanggal WFA wajib hari Jumat.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Ajukan WFA?',
                text: 'Pastikan data pengajuan sudah sesuai.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ajukan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.value) {
                    return;
                }

                $.ajax({
                    method: 'POST',
                    url: 'wfa_pengajuan/add',
                    dataType: 'JSON',
                    data: {
                        tanggal_wfa: tanggal_wfa,
                        rencana_pekerjaan: rencana_pekerjaan,
                        id_mengetahui: id_mengetahui,
                        tanpa_kepala_divisi: tanpa_kepala_divisi,
                        csrf_token: token
                    },
                    success: function(resp) {
                        handleResponse(resp);
                    }
                });
            });
        });
    });
</script>
