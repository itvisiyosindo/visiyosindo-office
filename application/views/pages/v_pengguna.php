<style>
    /* Modern Employee List Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        --success-gradient: linear-gradient(135deg, #059669 0%, #047857 100%);
        --warning-gradient: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        --danger-gradient: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
        --info-gradient: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        --card-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.06);
    }

    .employee-list-container {
        padding: 10px 0 30px 0;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Page Header */
    .employee-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .employee-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .employee-header-icon {
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

    .employee-header-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .employee-header-desc {
        margin: 2px 0 0 0;
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px -4px rgba(15, 23, 42, 0.08);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-icon.total { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; }
    .stat-icon.active { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); color: #059669; }
    .stat-icon.inactive { background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); color: #e11d48; }
    .stat-icon.training { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); color: #d97706; }

    .stat-info h3 {
        font-size: 26px;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-info p {
        margin: 2px 0 0 0;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
    }

    /* Filter Section */
    .filter-section {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        margin-bottom: 24px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        cursor: pointer;
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-header h5 i {
        color: #2563eb;
    }

    .filter-toggle {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 10px;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-toggle:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .filter-body {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 14px;
        padding-top: 10px;
    }

    .filter-group {
        position: relative;
    }

    .filter-group label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 6px;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        color: #0f172a;
        font-weight: 500;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    .btn-filter {
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-filter-apply {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-filter-apply:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        color: #ffffff;
    }

    .btn-filter-reset {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    .btn-filter-reset:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    /* Action Buttons Section */
    .action-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-action-add {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
    }

    .btn-action-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3);
        color: #ffffff;
    }

    .btn-action-print {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155;
    }

    .btn-action-print:hover {
        background: #f8fafc;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .btn-action-export {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #059669;
    }

    .btn-action-export:hover {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #047857;
        transform: translateY(-1px);
    }

    /* Table Card */
    .table-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 20px;
        box-shadow: 0 4px 24px -2px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table-card .card-header {
        background: #ffffff;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* Modern Table */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead th {
        background: #f8fafc;
        padding: 12px 16px;
        text-align: left;
        font-weight: 700;
        color: #475569;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        font-size: 13px;
        vertical-align: middle;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Employee Cell */
    .employee-cell {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 240px;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    .employee-avatar-placeholder {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }

    .employee-info h6 {
        margin: 0 0 2px 0;
        font-weight: 700;
        color: #0f172a;
        font-size: 13.5px;
        line-height: 1.3;
    }

    .employee-info p {
        margin: 0;
        font-size: 12px;
        color: #64748b;
        line-height: 1.3;
    }

    /* Badges */
    .badge-modern {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .badge-active {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .badge-inactive {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
    }

    .badge-level {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    /* Action Buttons in Table */
    .table-actions {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .btn-table-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 12px;
    }

    .btn-view {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .btn-view:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .btn-table-edit {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .btn-table-edit:hover {
        background: #d97706;
        color: #ffffff;
    }

    .btn-table-delete {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
    }

    .btn-table-delete:hover {
        background: #e11d48;
        color: #ffffff;
    }

    /* Modal Styles */
    .modal-modern .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.18);
    }

    .modal-modern .modal-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border-radius: 0;
        padding: 18px 24px;
        border-bottom: none;
    }

    .modal-modern .modal-header .close {
        color: #ffffff;
        opacity: 0.8;
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

    .form-group-modern {
        margin-bottom: 16px;
    }

    .form-group-modern label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }

    .form-group-modern .form-control {
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .form-group-modern .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h5 {
        color: #475569;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 13px;
    }

    /* DataTables Override Styles */
    #kt_table_1_wrapper {
        padding: 20px;
    }

    #kt_table_1_wrapper .dataTables_length,
    #kt_table_1_wrapper .dataTables_filter {
        margin-bottom: 16px;
    }

    #kt_table_1_wrapper .dataTables_length select {
        padding: 6px 10px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        margin: 0 6px;
        font-size: 13px;
    }

    #kt_table_1_wrapper .dataTables_filter input {
        padding: 8px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        margin-left: 8px;
        min-width: 240px;
        font-size: 13px;
    }

    #kt_table_1_wrapper .dataTables_filter input:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    #kt_table_1_wrapper .dataTables_info {
        padding: 15px 0;
        color: #64748b;
        font-size: 12.5px;
    }

    #kt_table_1_wrapper .dataTables_paginate {
        padding: 15px 0;
    }

    #kt_table_1_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 3px;
        border-radius: 8px;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #475569 !important;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s ease;
    }

    #kt_table_1_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }

    #kt_table_1_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-body {
            grid-template-columns: 1fr;
        }

        .action-section {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 576px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="employee-list-container">
    <!-- Modern Page Header -->
    <div class="employee-page-header">
        <div class="employee-header-left">
            <div class="employee-header-icon">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3 class="employee-header-title"><?= $page_title ?></h3>
                <p class="employee-header-desc"><?= $page_desc ?></p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-row" id="stats-row">
        <div class="stat-card total">
            <div class="stat-icon total">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3 id="stat-total">-</h3>
                <p>Total Karyawan</p>
            </div>
        </div>
        <div class="stat-card active">
            <div class="stat-icon active">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h3 id="stat-active">-</h3>
                <p>Karyawan Aktif</p>
            </div>
        </div>
        <div class="stat-card inactive">
            <div class="stat-icon inactive">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-info">
                <h3 id="stat-inactive">-</h3>
                <p>Tidak Aktif</p>
            </div>
        </div>
        <div class="stat-card training">
            <div class="stat-icon training">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info">
                <h3 id="stat-training">-</h3>
                <p>Training</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header" data-toggle="collapse" data-target="#filterCollapse">
            <h5><i class="fas fa-filter"></i> Filter & Pencarian</h5>
            <button class="filter-toggle">
                <i class="fas fa-chevron-down"></i> Tampilkan Filter
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="filter-body">
                <div class="filter-group">
                    <label>Nama Karyawan</label>
                    <input type="text" id="filter-nama" placeholder="Cari nama karyawan...">
                </div>
                <div class="filter-group">
                    <label>Divisi</label>
                    <select id="filter-divisi">
                        <option value="">Semua Divisi</option>
                        <?php if(isset($divisi_list)): foreach($divisi_list as $div): ?>
                        <option value="<?= $div->id_divisi ?>"><?= $div->nama ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status Karyawan</label>
                    <select id="filter-status-karyawan">
                        <option value="">Semua Status</option>
                        <option value="tetap">Tetap</option>
                        <option value="kontrak">Kontrak</option>
                        <option value="training">Training</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Level Akses</label>
                    <select id="filter-level">
                        <option value="">Semua Level</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Hrd">HRD</option>
                        <option value="Ga">General Affair</option>
                        <option value="Karyawan">Karyawan</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status Aktif</label>
                    <select id="filter-is-active">
                        <option value="">Semua</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button class="btn-filter btn-filter-apply" id="btn-apply-filter">
                        <i class="fas fa-search"></i> Terapkan
                    </button>
                    <button class="btn-filter btn-filter-reset" id="btn-reset-filter">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Section -->
    <div class="action-section">
        <div class="action-buttons">
            <?php if(isAdmin()){ ?>
            <a href="javascript:;" id="btn-show-add-form" class="btn-action btn-action-add">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
            <?php } ?>
            <a href="javascript:;" id="btn-laporan-cetak-form" class="btn-action btn-action-print">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
            <a href="javascript:;" id="btn-export-excel" class="btn-action btn-action-export">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Daftar Karyawan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="modern-table" id="kt_table_1" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Karyawan</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Status Aktif</th>
                            <?php if (isAdmin() || isHrd() || isGa()) { ?>
                            <th style="width: 150px;">Aksi</th>
                            <?php } ?>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="main-modal" class="modal fade modal-modern" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-label"><i class="fas fa-user-plus"></i> Form Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="dt-pengguna-form">
                    <div class="form-group-modern">
                        <label>Nama Pengguna <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group-modern">
                        <label>Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Masukkan username">
                    </div>
                    <div class="form-group-modern">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email">
                    </div>
                    <div class="form-group-modern">
                        <label>Hak Akses <span class="text-danger">*</span></label>
                        <select class="form-control" id="level" name="level" required>
                            <option value="">- Pilih Hak Akses -</option>
                            <option value="Administrator">Administrator</option>
                            <option value="Karyawan">Karyawan</option>
                            <option value="Hrd">HRD</option>
                            <option value="Ga">General Affair</option>
                        </select>
                    </div>
                    <div class="form-group-modern">
                        <label>Hirarki Tiket <span class="text-danger">*</span></label>
                        <select class="form-control" id="hirarki" name="hirarki" required>
                            <option value="">- Pilih Hirarki -</option>
                            <option value="1">Eksekutif</option>
                            <option value="2">Kepala Divisi</option>
                            <option value="3">Admin Divisi</option>
                            <option value="4">PIC</option>
                        </select>
                    </div>
                    <div class="form-group-modern password-form">
                        <label>Password <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <input class="form-control" type="password" placeholder="Password" name="password" id="password" required>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="password" placeholder="Konfirmasi Password" name="rpassword" id="rpassword" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="is_aktif"></div>
                <input type="hidden" id="pengguna_id" name="pengguna_id">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" class="btn btn-success btn-save">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    var table = $('#kt_table_1').DataTable({
        responsive: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: {
            url: 'pengguna/pagination',
            type: 'POST',
            data: function(e) {
                e.csrf_token = token;
                e.filter_nama = $('#filter-nama').val();
                e.filter_divisi = $('#filter-divisi').val();
                e.filter_status_karyawan = $('#filter-status-karyawan').val();
                e.filter_level = $('#filter-level').val();
                e.filter_is_active = $('#filter-is-active').val();
            }
        },
        columnDefs: [
            { targets: [0, 4], className: 'text-center' }
        ],
        language: {
            processing: '<div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>',
            emptyTable: '<div class="empty-state"><i class="fas fa-inbox"></i><h5>Tidak ada data</h5><p>Belum ada data karyawan yang tersedia</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><h5>Tidak ditemukan</h5><p>Tidak ada data yang sesuai dengan filter</p></div>'
        },
        drawCallback: function() {
            // Animate rows on draw
            $('.modern-table tbody tr').each(function(i) {
                $(this).css('opacity', 0).delay(i * 30).animate({opacity: 1}, 200);
            });
        }
    });

    // Load Statistics
    loadStatistics();

    function loadStatistics() {
        $.ajax({
            url: 'pengguna/get_statistics',
            type: 'POST',
            dataType: 'json',
            data: { csrf_token: token },
            success: function(resp) {
                if(resp.success) {
                    animateNumber('#stat-total', resp.data.total);
                    animateNumber('#stat-active', resp.data.aktif);
                    animateNumber('#stat-inactive', resp.data.tidak_aktif);
                    animateNumber('#stat-training', resp.data.training);
                }
            }
        });
    }

    function animateNumber(selector, value) {
        $({num: 0}).animate({num: value}, {
            duration: 800,
            easing: 'swing',
            step: function() {
                $(selector).text(Math.floor(this.num));
            },
            complete: function() {
                $(selector).text(value);
            }
        });
    }

    // Filter Collapse Toggle
    $('.filter-header').on('click', function() {
        var $icon = $(this).find('.filter-toggle i');
        var $text = $(this).find('.filter-toggle');
        if($('#filterCollapse').hasClass('show')) {
            $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            $text.html('<i class="fas fa-chevron-down"></i> Tampilkan Filter');
        } else {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            $text.html('<i class="fas fa-chevron-up"></i> Sembunyikan Filter');
        }
    });

    // Apply Filter
    $('#btn-apply-filter').on('click', function() {
        table.ajax.reload();
        loadStatistics();
    });

    // Reset Filter
    $('#btn-reset-filter').on('click', function() {
        $('#filter-nama').val('');
        $('#filter-divisi').val('');
        $('#filter-status-karyawan').val('');
        $('#filter-level').val('');
        $('#filter-is-active').val('');
        $('#filter-approval').val('');
        table.ajax.reload();
        loadStatistics();
    });

    // Enter key for search
    $('#filter-nama').on('keypress', function(e) {
        if(e.which == 13) {
            table.ajax.reload();
        }
    });

    // Add User Button
    $('#btn-show-add-form').click(function() {
        $('.form-control').val(null);
        $('.btn-isactive').remove();
        $('#main-modal #modal-form').attr('action', 'pengguna/add');
        $('#main-modal #modal-label').html('<i class="fas fa-user-plus"></i> Tambah Karyawan Baru');
        $('#main-modal').modal();
        togglePasswordForm();
    });

    // Edit Button
    $(document).on('click', '.btn-edit', function() {
        $('.btn-isactive').remove();
        $('#main-modal #modal-form').attr('action', 'pengguna/update');
        $('#main-modal #modal-label').html('<i class="fas fa-user-edit"></i> Edit Data Karyawan');
        $('#main-modal').modal();
        togglePasswordForm('hide');

        var id = $(this).attr("data-id");
        fetch('pengguna/edit/' + id)
            .then(function(resp) {
                return resp.json();
            })
            .then(function(data) {
                $('#main-modal #pengguna_id').val(data[0].pengguna_id);
                $('#main-modal #nama').val(data[0].nama);
                $('#main-modal #email').val(data[0].email);
                $('#main-modal #username').val(data[0].email);
                $('#main-modal #level option[value="' + data[0].level + '"]').prop("selected", true).trigger('change');
                $('#main-modal #hirarki option[value="' + data[0].hirarki + '"]').prop("selected", true).trigger('change');

                let text_isaktif = data[0].is_active == 1 ? "Nonaktifkan" : "Aktifkan";
                let color = data[0].is_active == 1 ? "danger" : "success";
                let value = data[0].is_active == 1 ? 0 : 1;
                let x = `<button type="button" class="btn btn-${color} btn-isactive" id="${data[0].pengguna_id}" value_isactive="${value}"><i class="fas fa-power-off"></i> ${text_isaktif}</button>`;
                $('.is_aktif').append(x);
            });
    });

    // Toggle Active Status
    $(document).on('click', '.btn-isactive', function() {
        $.ajax({
            method: 'POST',
            url: 'pengguna/update/is_active',
            dataType: 'json',
            data: {
                pengguna_id: $(this).attr("id"),
                value: $(this).attr("value_isactive"),
                csrf_token: token
            },
            success: function(resp) {
                handleResponse(resp);
                loadStatistics();
            }
        });
    });

    // Reset Password Button
    $(document).on('click', '.btn-reset-password', function() {
        var id = $(this).data("id");
        $('#main-modal #pengguna_id').val(id);
        $('#main-modal #modal-label').html('<i class="fas fa-key"></i> Reset Password');
        $('#main-modal #modal-form').attr('action', 'pengguna/reset');
        $('#main-modal').modal();
        togglePasswordForm('show');
    });

    // Print Button
    $('#btn-laporan-cetak-form').on('click', function() {
        window.open("<?php echo base_url('pengguna/cetakPengguna'); ?>", "_blank");
    });

    // Export Excel
    $('#btn-export-excel').on('click', function() {
        var params = new URLSearchParams({
            filter_nama: $('#filter-nama').val(),
            filter_divisi: $('#filter-divisi').val(),
            filter_status_karyawan: $('#filter-status-karyawan').val(),
            filter_level: $('#filter-level').val(),
            filter_is_active: $('#filter-is-active').val(),
            filter_approval: $('#filter-approval').val()
        });
        window.open("<?php echo base_url('pengguna/exportExcel'); ?>?" + params.toString(), "_blank");
    });

    function togglePasswordForm(status) {
        switch (status) {
            case 'show':
                $('.password-form *').removeClass('d-none').prop('disabled', false);
                $('.dt-pengguna-form *').addClass('d-none').prop('disabled', true);
                break;
            case 'hide':
                $('.password-form *').addClass('d-none').prop('disabled', true);
                $('.dt-pengguna-form *').removeClass('hide').prop('disabled', false);
                break;
            default:
                $('.password-form *').removeClass('d-none').prop('disabled', false);
                $('.dt-pengguna-form *').removeClass('d-none').prop('disabled', false);
                break;
        }
    }

    window.updateDatatable = function() {
        table.ajax.reload(null, false);
        loadStatistics();
    };
});
</script>
