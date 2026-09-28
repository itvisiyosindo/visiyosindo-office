<style>
    /* Modern Employee List Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --info-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --card-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .employee-list-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        min-height: 100vh;
        padding: 20px 15px;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px 25px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .stat-card.total::before { background: var(--info-gradient); }
    .stat-card.active::before { background: var(--success-gradient); }
    .stat-card.inactive::before { background: var(--danger-gradient); }
    .stat-card.training::before { background: var(--warning-gradient); }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-icon.total { background: var(--info-gradient); }
    .stat-icon.active { background: var(--success-gradient); }
    .stat-icon.inactive { background: var(--danger-gradient); }
    .stat-icon.training { background: var(--warning-gradient); }

    .stat-info h3 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
    }

    .stat-info p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-header h5 i {
        color: #667eea;
    }

    .filter-toggle {
        background: #f1f5f9;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        color: #64748b;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-toggle:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .filter-body {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .filter-group {
        position: relative;
    }

    .filter-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        color: #1e293b;
        transition: all 0.3s ease;
        background: white;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .btn-filter {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-filter-apply {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-filter-apply:hover {
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        transform: translateY(-2px);
    }

    .btn-filter-reset {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-filter-reset:hover {
        background: #e2e8f0;
        color: #475569;
    }

    /* Action Buttons Section */
    .action-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-action-add {
        background: var(--success-gradient);
        color: white;
    }

    .btn-action-add:hover {
        box-shadow: 0 5px 20px rgba(16, 185, 129, 0.4);
        transform: translateY(-2px);
        color: white;
    }

    .btn-action-print {
        background: var(--info-gradient);
        color: white;
    }

    .btn-action-print:hover {
        box-shadow: 0 5px 20px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
        color: white;
    }

    .btn-action-export {
        background: var(--warning-gradient);
        color: white;
    }

    .btn-action-export:hover {
        box-shadow: 0 5px 20px rgba(245, 158, 11, 0.4);
        transform: translateY(-2px);
        color: white;
    }

    /* Search Box */
    .search-box {
        position: relative;
        min-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .table-card .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 20px 25px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-card .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1e293b;
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
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
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

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Employee Name Cell */
    .employee-cell {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 250px;
    }

    .employee-avatar {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    .employee-avatar-placeholder {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
        flex-shrink: 0;
    }

    .employee-info {
        flex: 1;
        min-width: 0;
    }

    .employee-info h6 {
        margin: 0 0 4px 0;
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.3;
    }

    .employee-info p {
        margin: 0;
        font-size: 12px;
        color: #64748b;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Badges */
    .badge-modern {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .badge-approved {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .badge-level {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
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
        transition: all 0.3s ease;
    }

    .btn-view {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .btn-view:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-table-edit {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .btn-table-edit:hover {
        background: #f59e0b;
        color: white;
    }

    .btn-table-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .btn-table-delete:hover {
        background: #ef4444;
        color: white;
    }

    /* Modal Styles */
    .modal-modern .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.2);
    }

    .modal-modern .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 20px 25px;
    }

    .modal-modern .modal-header .close {
        color: white;
        opacity: 0.8;
    }

    .modal-modern .modal-header .close:hover {
        opacity: 1;
    }

    .modal-modern .modal-body {
        padding: 25px;
    }

    .modal-modern .modal-footer {
        padding: 15px 25px 25px;
        border-top: none;
    }

    .form-group-modern {
        margin-bottom: 20px;
    }

    .form-group-modern label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
    }

    .form-group-modern .form-control {
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group-modern .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        color: #64748b;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Loading Overlay */
    .table-loading {
        position: relative;
    }

    .table-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
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

        .search-box {
            min-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
    }

    /* DataTables Override Styles */
    #kt_table_1_wrapper {
        padding: 20px;
    }

    #kt_table_1_wrapper .dataTables_length,
    #kt_table_1_wrapper .dataTables_filter {
        margin-bottom: 20px;
    }

    #kt_table_1_wrapper .dataTables_length select {
        padding: 8px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        margin: 0 8px;
    }

    #kt_table_1_wrapper .dataTables_filter input {
        padding: 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        margin-left: 10px;
        min-width: 250px;
    }

    #kt_table_1_wrapper .dataTables_filter input:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    #kt_table_1_wrapper .dataTables_info {
        padding: 15px 0;
        color: #64748b;
        font-size: 13px;
    }

    #kt_table_1_wrapper .dataTables_paginate {
        padding: 15px 0;
    }

    #kt_table_1_wrapper .dataTables_paginate .paginate_button {
        padding: 8px 14px;
        margin: 0 3px;
        border-radius: 8px;
        border: none !important;
        background: #f1f5f9 !important;
        color: #64748b !important;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    #kt_table_1_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e2e8f0 !important;
        color: #1e293b !important;
    }

    #kt_table_1_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }

    #kt_table_1_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Table specific overrides */
    #kt_table_1 {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0;
    }

    #kt_table_1 thead th {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
        padding: 14px 16px !important;
        font-weight: 600 !important;
        color: #475569 !important;
        font-size: 12px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0 !important;
        white-space: nowrap;
    }

    #kt_table_1 tbody td {
        padding: 14px 16px !important;
        border-bottom: 1px solid #f1f5f9 !important;
        vertical-align: middle !important;
    }

    #kt_table_1 tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>

<div class="employee-list-container">
    <!-- Page Header -->
    <header class="page-header mb-4">
        <h2 style="color: #1e293b; font-weight: 700;"><i class="fas fa-users" style="color: #667eea;"></i>&nbsp;<?= $page_title ?></h2>
        <div class="right-wrapper text-left">
            <ol class="breadcrumbs">
                <li><span><?= $page_desc ?></span></li>
            </ol>
        </div>
    </header>

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
