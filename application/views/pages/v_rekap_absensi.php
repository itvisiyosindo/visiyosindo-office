<?php
$can_edit = isAdmin() || isGa() || ($this->session->userdata('login_type') == 'General Affair');
?>

<style>
    /* Modern Rekap Absensi Styles */
    .rekap-container {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
        padding-bottom: 30px;
    }

    /* Page Header */
    .rekap-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .rekap-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .rekap-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .rekap-header-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .rekap-header-desc {
        margin: 2px 0 0 0;
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Stats Cards Grid */
    .rekap-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    @media (max-width: 1200px) {
        .rekap-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .rekap-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .rekap-stat-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 4px 18px -2px rgba(15, 23, 42, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .rekap-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -3px rgba(15, 23, 42, 0.08);
    }

    .rekap-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .rekap-stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin: 0;
    }

    .rekap-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .icon-total { background: #eff6ff; color: #2563eb; }
    .icon-masuk { background: #ecfdf5; color: #059669; }
    .icon-terlambat { background: #fff1f2; color: #e11d48; }
    .icon-izin { background: #f5f3ff; color: #7c3aed; }
    .icon-cuti { background: #fffbeb; color: #d97706; }
    .icon-wfa { background: #f0f9ff; color: #0284c7; }
    .icon-dinas { background: #eef2ff; color: #4f46e5; }
    .icon-weekend { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

    .rekap-stat-val {
        font-size: 26px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
        margin: 0;
    }

    .rekap-stat-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 6px;
        padding-top: 6px;
        border-top: 1px dashed #f1f5f9;
    }

    /* Alert Banner */
    .rekap-alert-banner {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 14px;
        padding: 12px 18px;
        color: #0369a1;
        font-size: 12.5px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .rekap-alert-banner i {
        font-size: 18px;
        flex-shrink: 0;
    }

    /* Filter Card */
    .rekap-filter-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.06);
        margin-bottom: 24px;
    }

    .rekap-filter-header {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .rekap-filter-header i {
        color: #2563eb;
    }

    .rekap-filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
        gap: 14px;
        align-items: flex-end;
    }

    .rekap-form-group {
        margin: 0;
    }

    .rekap-form-group label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 6px;
    }

    .rekap-form-group .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 500;
        color: #0f172a;
        height: auto;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .rekap-form-group .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        outline: none;
    }

    .rekap-form-group .input-group-text {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #64748b;
        font-size: 12px;
    }

    .rekap-form-group .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .btn-rekap-pdf {
        background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 11.5px;
        padding: 6px 12px;
        box-shadow: 0 2px 8px rgba(225, 29, 72, 0.25);
        transition: all 0.2s ease;
    }

    .btn-rekap-pdf:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.35);
    }

    .btn-rekap-foto {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 11.5px;
        padding: 6px 12px;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        transition: all 0.2s ease;
    }

    .btn-rekap-foto:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
    }

    /* Table Card */
    .rekap-table-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 20px;
        box-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.06);
        padding: 24px;
        overflow: hidden;
    }

    .rekap-table-card .card-header-clean {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .rekap-table-card .card-header-clean i {
        color: #2563eb;
    }

    #kt_table_1 {
        margin: 0 !important;
        font-size: 12.5px;
    }

    #kt_table_1 thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 12px 14px !important;
        white-space: nowrap;
    }

    #kt_table_1 tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-color: #f1f5f9 !important;
        color: #334155 !important;
    }

    #kt_table_1 tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Modal Modern */
    .modal-modern .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.2);
    }

    .modal-modern .modal-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        padding: 18px 24px;
    }

    .modal-modern .modal-header .close {
        color: #ffffff;
        opacity: 0.9;
    }

    .modal-modern .modal-body {
        padding: 24px;
        background: #ffffff;
    }

    .modal-modern .modal-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }
</style>

<div class="rekap-container">
    <!-- Modern Header -->
    <div class="rekap-page-header">
        <div class="rekap-header-left">
            <div class="rekap-header-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <h3 class="rekap-header-title">Rekap Absensi "<?= $pengguna[0]->nama ?>"</h3>
                <p class="rekap-header-desc"><?= $page_desc ?> &bull; Pantau riwayat dan statistik kehadiran karyawan.</p>
            </div>
        </div>
    </div>

    <!-- Summary Stats Cards -->
    <div class="rekap-stats-grid" id="absensi-summary-cards">
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">Total Baris Absensi</span>
                <div class="rekap-stat-icon icon-total"><i class="fas fa-list-ol"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_absensi">0</div>
        </div>
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">Hari Masuk</span>
                <div class="rekap-stat-icon icon-masuk"><i class="fas fa-user-check"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_masuk">0</div>
        </div>
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">Terlambat</span>
                <div class="rekap-stat-icon icon-terlambat"><i class="fas fa-user-clock"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_terlambat">0</div>
        </div>
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">Izin</span>
                <div class="rekap-stat-icon icon-izin"><i class="fas fa-envelope-open-text"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_izin">0</div>
        </div>
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">Cuti</span>
                <div class="rekap-stat-icon icon-cuti"><i class="fas fa-calendar-minus"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_cuti">0</div>
        </div>
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">WFA</span>
                <div class="rekap-stat-icon icon-wfa"><i class="fas fa-laptop-house"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_wfa">0</div>
        </div>
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">Hari Dinas</span>
                <div class="rekap-stat-icon icon-dinas"><i class="fas fa-plane-departure"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_dinas">0</div>
        </div>
        <div class="rekap-stat-card">
            <div class="rekap-stat-top">
                <span class="rekap-stat-label">Weekend Masuk</span>
                <div class="rekap-stat-icon icon-weekend"><i class="fas fa-calendar-week"></i></div>
            </div>
            <div class="rekap-stat-val" id="stats_total_weekend_masuk">0</div>
            <div class="rekap-stat-sub" id="stats_weekend_masuk_list">Tidak ada absen masuk Sabtu/Minggu.</div>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="rekap-alert-banner">
        <i class="fas fa-info-circle"></i>
        <span><strong>Catatan:</strong> Total Baris Absensi menghitung semua baris absen masuk/istirahat/keluar. Hari Masuk adalah jumlah hari hadir dengan absen tipe "masuk".</span>
    </div>

    <!-- Filter & Action Card -->
    <div class="rekap-filter-card">
        <div class="rekap-filter-header">
            <i class="fas fa-filter"></i> Filter & Cetak Rekap
        </div>
        <div class="rekap-filter-grid">
            <div class="rekap-form-group">
                <label>Filter By Month:</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                </div>
            </div>
            <div class="rekap-form-group">
                <label>Filter By Date:</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm-dd", "minViewMode": "days"}' class="form-control" id="filter_date" placeholder="Pilih Tanggal" required data-plugin-datepicker>
                </div>
            </div>
            <div class="rekap-form-group">
                <label>Filter By Type Absen:</label>
                <select class="form-control" name="filter_type" id="filter_type">
                    <option value="">Semua Tipe</option>
                    <option value="masuk">Absen Masuk</option>
                    <option value="istirahat">Absen Istirahat</option>
                    <option value="keluar">Absen Keluar</option>
                </select>
            </div>
            <div class="rekap-form-group">
                <label>Filter By Status Absen:</label>
                <select class="form-control" name="filter_status" id="filter_status">
                    <option value="">Semua Status</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="tepat_waktu">Tepat Waktu</option>
                    <option value="dinas">Dinas</option>
                </select>
            </div>
            <div class="rekap-form-group">
                <label>Filter By Jenis Absen:</label>
                <select class="form-control" name="jenis_absen" id="jenis_absen">
                    <option value="">Semua Jenis</option>
                    <option value="Kantor">Kantor</option>
                    <option value="WFA">WFA</option>
                    <option value="Dinas">Dinas</option>
                </select>
            </div>
            <div class="rekap-form-group">
                <label><i class="fas fa-print mr-1"></i> Cetak Rekap (1 Bulan):</label>
                <div class="input-group mb-1">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                    <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="print_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                </div>
                <div class="d-flex" style="gap: 6px;">
                    <button type="button" id="btn_print_rekap_pdf" class="btn btn-rekap-pdf flex-fill" title="Cetak Rekap Absensi Karyawan (PDF)">
                        <i class="fas fa-file-pdf mr-1"></i> Rekap PDF
                    </button>
                    <button type="button" id="btn_print_foto_gps" class="btn btn-rekap-foto flex-fill" title="Print Absensi 1 Bulan Lengkap Foto Selfie & GPS">
                        <i class="fas fa-camera mr-1"></i> Foto & GPS
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="rekap-table-card">
        <div class="card-header-clean">
            <i class="fas fa-table"></i> Rincian Data Log Kehadiran
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm" id="kt_table_1" style="width: 100%;">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Tanggal Absensi </th>
                        <th> Type Absen </th>
                        <th> Waktu Absen </th>
                        <th> Status Absen </th>
                        <th> Lokasi Absen & Foto</th>
                        <th> Tunjangan </th>
                        <th> File Pendukung </th>
                        <th> Keterangan </th>
                        <th> IP Address </th>
                        <th> Approval </th>
                        <th> Lokasi Kerja </th>
                        <th> Jenis Absen </th>
                        <?php if ($can_edit): ?>
                            <th> Aksi </th>
                        <?php endif; ?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<input type="hidden" id="pengguna_id" value="<?= $pengguna_id ?>">
    <!-- Modal Lokasi Absen & Foto -->
    <div id="main-modal" class="modal fade modal-modern" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="fas fa-map-marked-alt mr-2"></i> Lokasi & Foto Absensi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <style>
                        #dvImage {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 280px;
                            background-color: #f1f5f9;
                            overflow: hidden;
                        }
                        #dvImage img {
                            max-height: 100%;
                            width: auto;
                            object-fit: contain;
                        }
                    </style>
                    <div id="dvImage"></div>
                    <div id="dvMap" style="height: 320px; width: 100%;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php if ($can_edit): ?>
    <!-- Modal Edit Absensi -->
    <div id="modal-edit-absen" class="modal fade modal-modern" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditAbsenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalEditAbsenLabel"><i class="fas fa-edit mr-2"></i> Edit Data Absensi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-absen" autocomplete="off">
                    <input type="hidden" name="id_absensi" id="edit_id_absensi">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_tanggal_absen" class="font-weight-bold">Tanggal Absensi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_tanggal_absen" name="tanggal_absen" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_waktu_absen" class="font-weight-bold">Waktu / Jam Absen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_waktu_absen" name="waktu_absen" placeholder="HH:MM:SS (cth: 07:45:00)" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_type_absen" class="font-weight-bold">Tipe Absen <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_type_absen" name="type_absen" required>
                                    <option value="masuk">Absen Masuk</option>
                                    <option value="istirahat">Absen Istirahat</option>
                                    <option value="keluar">Absen Keluar</option>
                                    <option value="izin">Izin</option>
                                    <option value="cuti">Cuti</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_status_absen" class="font-weight-bold">Status Absen</label>
                                <select class="form-control" id="edit_status_absen" name="status_absen">
                                    <option value="">- Tidak Ada / Kosong -</option>
                                    <option value="tepat_waktu">Tepat Waktu</option>
                                    <option value="terlambat">Terlambat</option>
                                    <option value="dinas">Dinas</option>
                                    <option value="izin">Izin</option>
                                    <option value="cuti">Cuti</option>
                                    <option value="sakit">Sakit</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_jenis_absen" class="font-weight-bold">Jenis Absen <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_jenis_absen" name="jenis_absen" required>
                                    <option value="Kantor">Kantor</option>
                                    <option value="WFA">WFA</option>
                                    <option value="Dinas">Dinas</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_jenis_lokasi" class="font-weight-bold">Lokasi Kerja</label>
                                <input type="text" class="form-control" id="edit_jenis_lokasi" name="jenis_lokasi" placeholder="Kantor / WFA / Dinas / Nama Lokasi">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_tanpa_tunjangan" class="font-weight-bold">Tunjangan Kehadiran <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_tanpa_tunjangan" name="tanpa_tunjangan" required>
                                    <option value="0">Ya (Dapat Tunjangan)</option>
                                    <option value="1">Tidak (Tanpa Tunjangan)</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_approval" class="font-weight-bold">Status Approval</label>
                                <select class="form-control" id="edit_approval" name="approval">
                                    <option value="">- Belum Ditentukan / Menunggu -</option>
                                    <option value="terima">Diterima</option>
                                    <option value="tolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_ip_addr" class="font-weight-bold">IP Address</label>
                                <input type="text" class="form-control" id="edit_ip_addr" name="ip_addr" placeholder="cth: 103.129.25.12">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_keterangan" class="font-weight-bold">Keterangan</label>
                                <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal" style="border-radius: 10px; font-weight: 600;"><i class="fas fa-times mr-1"></i> Batal</button>
                        <button type="button" class="btn btn-primary btn-sm px-4 btn-save-edit-absen" style="border-radius: 10px; font-weight: 600; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none;"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script>
        function updateStatsCards() {
            $.ajax({
                method: 'POST',
                url: 'absensi/getStatsCards',
                dataType: 'JSON',
                data: {
                    filter_month: $('#filter_month').val(),
                    filter_date: $('#filter_date').val(),
                    filter_type: $('#filter_type').val(),
                    filter_status: $('#filter_status').val(),
                    jenis_absen: $('#jenis_absen').val(),
                    pengguna_id: $('#pengguna_id').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    if (resp.status == 'success' && resp.totals) {
                        $('#stats_total_absensi').text(resp.totals.total_absensi);
                        $('#stats_total_masuk').text(resp.totals.total_masuk);
                        $('#stats_total_terlambat').text(resp.totals.total_terlambat);
                        $('#stats_total_izin').text(resp.totals.total_izin);
                        $('#stats_total_cuti').text(resp.totals.total_cuti);
                        $('#stats_total_wfa').text(resp.totals.total_wfa);
                        $('#stats_total_dinas').text(resp.totals.total_dinas);
                        $('#stats_total_weekend_masuk').text(resp.totals.weekend_absen_count || 0);
                        var weekendList = resp.totals.weekend_absen_dates || [];
                        if (weekendList.length) {
                            $('#stats_weekend_masuk_list').html('<ul class="mb-0 pl-3">' + weekendList.map(function(item){ return '<li>' + item + '</li>'; }).join('') + '</ul>');
                        } else {
                            $('#stats_weekend_masuk_list').text('Tidak ada absen masuk Sabtu/Minggu.');
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Load stats cards on page load
            updateStatsCards();
            
            // Update stats cards when any filter changes
            $('#filter_month, #filter_date, #filter_type, #filter_status, #jenis_absen').change(function() {
                updateStatsCards();
                table.ajax.reload();
            })
            
            table = $('#kt_table_1').DataTable({
                responsive: false,
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: 'absensi/rekap_absensi',
                    type: 'POST',
                    data: function(e) {
                        e.filter_month = $('#filter_month').val()
                        e.filter_status = $('#filter_status').val()
                        e.filter_date = $('#filter_date').val()
                        e.filter_type = $('#filter_type').val()
                        e.pengguna_id = $('#pengguna_id').val()
                        e.jenis_absen = $('#jenis_absen').val()
                        e.csrf_token = token
                    },
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                columnDefs: [{
                    targets: '_all',
                    className: 'text-center'
                }]
            })

            $(document).on('click', '.btn-lihat-posisi', function() {
                $('#main-modal').modal()
                $.ajax({
                    method: 'POST',
                    url: 'absensi/show/latlong/by_id',
                    dataType: 'JSON',
                    data: {
                        id: $(this).attr("data-id"),
                        csrf_token: token
                    },
                    success: function(resp) {
                        if ((resp.latitude != null) && (resp.longitude != null)) {
                            $('#main-modal').modal();

                            // Menampilkan peta di dalam dvMap
                            document.getElementById("dvMap").innerHTML = `
                                    <iframe style="overflow:hidden;height:100%;width:100%" 
                                        loading="lazy" 
                                        allowfullscreen 
                                        referrerpolicy="no-referrer-when-downgrade" 
                                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo
                                        &q=${resp.latitude},${resp.longitude}
                                        &center=${resp.latitude},${resp.longitude}
                                        &zoom=21
                                        &maptype=roadmap">
                                    </iframe>
                                `;

                            // Menampilkan foto di dalam dvImage
                            if (resp.file_foto) {
                                document.getElementById("dvImage").innerHTML = `
                                        <img src="assets/img/absen/${resp.file_foto}" 
                                            alt="Foto Absen" 
                                            style="max-width: 100%; height: auto;">
                                    `;
                            } else {
                                document.getElementById("dvImage").innerHTML = `
                                        <p>Foto tidak tersedia.</p>
                                    `;
                            }


                        } else {
                            document.getElementById("dvMap").innerHTML = "";
                        }
                    }
                })
            })

            $('#btn_print_rekap_pdf').click(function() {
                var month = $('#print_month').val() || $('#filter_month').val() || '<?= date("Y-m") ?>';
                var id = $('#pengguna_id').val();
                var link = 'absensi/print/detailKaryawanMonth/' + month + '/' + id;
                window.open('<?= base_url() ?>' + link, '_blank');
            });

            $('#btn_print_foto_gps').click(function() {
                var month = $('#print_month').val() || $('#filter_month').val() || '<?= date("Y-m") ?>';
                var id = $('#pengguna_id').val();
                var link = 'absensi/print_foto_gps/' + month + '/' + id;
                window.open('<?= base_url() ?>' + link, '_blank');
            });

            $(document).on('click', '.btn-approval', function() {
                const id_absensi = $(this).attr("data-id")
                const pengguna_id = $(this).attr("pengguna-id")
                const approval = $(this).attr("approval")
                Swal.fire({
                    title: approval + ' absensi?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            method: 'POST',
                            url: 'salary_tidak_tetap/add',
                            dataType: 'JSON',
                            data: {
                                id_absensi: id_absensi,
                                pengguna_id: pengguna_id,
                                approval: approval,
                                csrf_token: token
                            },
                            success: function(resp) {
                                handleResponse(resp)
                            }
                        })
                    }
                })
            })

            <?php if ($can_edit): ?>
            $(document).on('click', '.btn-edit-absen', function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: 'absensi/get_absen_detail/' + id,
                    type: 'GET',
                    dataType: 'JSON',
                    beforeSend: function() {
                        Swal.fire({
                            html: `<h4>Memuat Data...</h4>`,
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 500
                        });
                    },
                    success: function(resp) {
                        Swal.close();
                        if (resp.status == 'error') {
                            Swal.fire('Error', resp.msg, 'error');
                            return;
                        }
                        var d = resp.data;
                        $('#edit_id_absensi').val(resp.id_encrypted);
                        $('#edit_tanggal_absen').val(d.tanggal);
                        $('#edit_waktu_absen').val(d.waktu_absen);
                        $('#edit_type_absen').val(d.type_absen);
                        $('#edit_status_absen').val(d.status_absen || '');
                        $('#edit_jenis_absen').val(d.jenis_absen || 'Kantor');
                        $('#edit_jenis_lokasi').val(d.jenis_lokasi || 'Kantor');
                        $('#edit_tanpa_tunjangan').val(d.tanpa_tunjangan);
                        $('#edit_approval').val(d.approval || '');
                        $('#edit_ip_addr').val(d.ip_addr || '');
                        $('#edit_keterangan').val(d.keterangan || '');

                        $('#modal-edit-absen').modal('show');
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal memuat detail absensi.', 'error');
                    }
                });
            });

            $(document).on('click', '.btn-save-edit-absen', function() {
                var form = $('#form-edit-absen');
                if (!$('#edit_tanggal_absen').val() || !$('#edit_waktu_absen').val()) {
                    Swal.fire('Perhatian', 'Tanggal dan Waktu Absen wajib diisi!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Simpan Perubahan Absensi?',
                    text: 'Pastikan data yang diinput sudah sesuai.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.value) {
                        var formData = new FormData(form[0]);
                        formData.append('csrf_token', token);
                        $.ajax({
                            url: 'absensi/update_absen',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'JSON',
                            beforeSend: function() {
                                Swal.fire({
                                    html: `<h4>Menyimpan Perubahan...</h4>`,
                                    icon: 'info',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                });
                            },
                            success: function(resp) {
                                if (resp.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: resp.msg || 'Data absensi berhasil diperbarui.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(function() {
                                        $('#modal-edit-absen').modal('hide');
                                        updateDatatable();
                                        updateStatsCards();
                                    });
                                } else {
                                    Swal.fire('Error', resp.msg || 'Gagal menyimpan perubahan.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan pada server saat memperbarui data.', 'error');
                            }
                        });
                    }
                });
            });
            <?php endif; ?>
        })

        function updateDatatable() {
            table.ajax.reload(null, false)
        }
    </script>