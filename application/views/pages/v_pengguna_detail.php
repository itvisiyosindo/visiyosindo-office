<style>
    /* Modern Employee Profile Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --card-shadow: 0 10px 40px rgba(0,0,0,0.1);
        --card-hover-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .employee-profile-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        min-height: 100vh;
        padding: 30px 15px;
    }

    /* Profile Header Card */
    .profile-header-card {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 40px;
        color: white;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .profile-header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .profile-photo-wrapper {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto 20px;
    }

    .profile-photo {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid rgba(255,255,255,0.3);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        transition: transform 0.3s ease;
    }

    .profile-photo:hover {
        transform: scale(1.05);
    }

    .profile-photo-placeholder {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 5px solid rgba(255,255,255,0.3);
    }

    .profile-photo-placeholder i {
        font-size: 80px;
        color: rgba(255,255,255,0.5);
    }

    .employee-status-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background: var(--success-color);
        color: white;
    }

    .status-inactive {
        background: var(--danger-color);
        color: white;
    }

    .profile-name {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .profile-position {
        font-size: 18px;
        color: #ffffff;
        font-weight: 500;
        margin-bottom: 15px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .profile-npp {
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        background: rgba(255,255,255,0.25);
        padding: 5px 15px;
        border-radius: 20px;
        display: inline-block;
    }

    .profile-quick-info {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 25px;
    }

    .quick-info-item {
        text-align: center;
        padding: 15px 25px;
        background: rgba(255,255,255,0.2);
        border-radius: 15px;
        backdrop-filter: blur(10px);
        min-width: 120px;
        color: #ffffff;
    }

    .quick-info-item i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
    }

    .quick-info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.95);
        font-weight: 500;
    }

    .quick-info-value {
        font-size: 14px;
        font-weight: 700;
        margin-top: 3px;
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    /* Tab Navigation */
    .profile-tabs {
        background: white;
        border-radius: 15px;
        padding: 5px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .profile-tabs .nav-link {
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-tabs .nav-link:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .profile-tabs .nav-link.active {
        background: var(--primary-gradient);
        color: white;
    }

    .profile-tabs .nav-link i {
        font-size: 16px;
    }

    /* Content Cards */
    .profile-content-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-content-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 20px 25px;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-custom h5 i {
        color: #667eea;
    }

    .card-body-custom {
        padding: 25px;
    }

    /* Form Styles */
    .form-group-modern {
        margin-bottom: 20px;
    }

    .form-group-modern label {
        font-weight: 600;
        color: #475569;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-group-modern .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-group-modern .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .form-group-modern .input-group-text {
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 10px 0 0 10px;
        background: #f1f5f9;
    }

    .form-group-modern .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    /* Checkbox Styles */
    .benefit-checkbox {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .benefit-checkbox:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }

    .benefit-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        accent-color: #667eea;
    }

    .benefit-checkbox label {
        margin: 0;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
    }

    /* Document Cards */
    .document-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .document-card {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 15px;
        padding: 25px 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        position: relative;
        overflow: hidden;
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: #667eea;
    }

    .document-card.has-file {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-color: var(--success-color);
    }

    .document-card .doc-icon {
        font-size: 40px;
        color: #64748b;
        margin-bottom: 15px;
    }

    .document-card.has-file .doc-icon {
        color: var(--success-color);
    }

    .document-card .doc-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .document-card .doc-status {
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 15px;
        display: inline-block;
        margin-bottom: 15px;
    }

    .document-card.has-file .doc-status {
        background: var(--success-color);
        color: white;
    }

    .document-card:not(.has-file) .doc-status {
        background: #fef3c7;
        color: #92400e;
    }

    .document-card .doc-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .document-card .btn-doc {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Salary Section */
    .salary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .salary-item:hover {
        background: #f1f5f9;
    }

    .salary-label {
        font-weight: 500;
        color: #475569;
    }

    .salary-value {
        font-weight: 700;
        color: #1e293b;
    }

    /* Action Buttons */
    .btn-action-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: flex-end;
        padding: 20px 25px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .btn-modern {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .btn-primary-modern {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-secondary-modern {
        background: #64748b;
        color: white;
    }

    .btn-warning-modern {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-success-modern {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    /* PDF Preview Modal */
    .modal-pdf-preview .modal-dialog {
        max-width: 900px;
    }

    .modal-pdf-preview .modal-content {
        border-radius: 20px;
        overflow: hidden;
    }

    .modal-pdf-preview .modal-header {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 20px 25px;
    }

    .modal-pdf-preview .modal-header .close {
        color: white;
        opacity: 1;
    }

    .pdf-viewer-container {
        width: 100%;
        height: 600px;
        background: #1e293b;
    }

    .pdf-viewer-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .pdf-viewer-container embed {
        width: 100%;
        height: 100%;
    }

    .image-preview-container {
        width: 100%;
        max-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #1e293b;
        padding: 20px;
    }

    .image-preview-container img {
        max-width: 100%;
        max-height: 560px;
        object-fit: contain;
        border-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-header-card {
            padding: 25px;
        }

        .profile-photo-wrapper {
            width: 140px;
            height: 140px;
        }

        .profile-photo, .profile-photo-placeholder {
            width: 140px;
            height: 140px;
        }

        .profile-name {
            font-size: 24px;
        }

        .profile-quick-info {
            gap: 10px;
        }

        .quick-info-item {
            padding: 10px 15px;
            min-width: 100px;
        }

        .profile-tabs .nav-link {
            padding: 10px 15px;
            font-size: 13px;
        }

        .document-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Loading Animation */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-overlay.hide {
        display: none;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid #e2e8f0;
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Signature Styles */
    .signature-container {
        padding: 30px;
    }

    .signature-preview-box {
        background: #f8fafc;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 40px;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .signature-preview-box:hover {
        border-color: #667eea;
        background: #f1f5f9;
    }

    .signature-image {
        max-width: 100%;
        max-height: 200px;
        object-fit: contain;
    }

    .no-signature {
        text-align: center;
        color: #94a3b8;
    }

    .no-signature i {
        font-size: 64px;
        margin-bottom: 15px;
        color: #cbd5e1;
    }

    .no-signature p {
        font-size: 16px;
        font-weight: 500;
    }

    .signature-info {
        background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
        border-radius: 12px;
        padding: 20px;
    }

    .signature-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-danger-modern {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-danger-modern:hover {
        box-shadow: 0 5px 20px rgba(239, 68, 68, 0.4);
        transform: translateY(-2px);
        color: white;
    }
</style>

<header class="page-header">
    <h2><i class="icons fas fa-user-tie"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('pengguna') ?>"><span>Data Karyawan</span></a></li>
            <li><span><?= $data_pengguna[0]->nama ?></span></li>
        </ol>
    </div>
</header>

<div class="employee-profile-container">
    <div class="container-fluid">
        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center">
                    <div class="profile-photo-wrapper">
                        <?php if($data_pengguna[0]->file_foto): ?>
                            <img src="<?= base_url('uploads/file_karyawan/foto/' . $data_pengguna[0]->file_foto) ?>" 
                                 alt="<?= $data_pengguna[0]->nama ?>" 
                                 class="profile-photo"
                                 onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'profile-photo-placeholder\'><i class=\'fas fa-user\'></i></div>';">
                        <?php else: ?>
                            <div class="profile-photo-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                        <span class="employee-status-badge <?= $data_pengguna[0]->is_active == 1 ? 'status-active' : 'status-inactive' ?>">
                            <?= $data_pengguna[0]->is_active == 1 ? 'Aktif' : 'Tidak Aktif' ?>
                        </span>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="text-center text-lg-left">
                        <h1 class="profile-name"><?= $data_pengguna[0]->nama ?></h1>
                        <p class="profile-position"><?= $data_pengguna[0]->jabatan ?: 'Belum ada jabatan' ?></p>
                        <span class="profile-npp">
                            <i class="fas fa-id-badge"></i> NPP: <?= $data_pengguna[0]->no_pegawai ?: '-' ?>
                        </span>
                        <span class="profile-npp" style="margin-left: 10px;">
                            <i class="fas fa-hashtag"></i> ID: <?= $data_pengguna[0]->pengguna_id ?>
                        </span>
                    </div>
                    
                    <div class="profile-quick-info">
                        <div class="quick-info-item">
                            <i class="fas fa-briefcase"></i>
                            <div class="quick-info-label">Status</div>
                            <div class="quick-info-value"><?= ucfirst($data_pengguna[0]->status_karyawan ?: '-') ?></div>
                        </div>
                        <div class="quick-info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="quick-info-label">Tanggal Masuk</div>
                            <div class="quick-info-value"><?= $data_pengguna[0]->tgl_masuk ? date('d M Y', strtotime($data_pengguna[0]->tgl_masuk)) : '-' ?></div>
                        </div>
                        <div class="quick-info-item">
                            <i class="fas fa-envelope"></i>
                            <div class="quick-info-label">Email</div>
                            <div class="quick-info-value"><?= $data_pengguna[0]->email ?: '-' ?></div>
                        </div>
                        <div class="quick-info-item">
                            <i class="fas fa-phone"></i>
                            <div class="quick-info-label">No. HP</div>
                            <div class="quick-info-value"><?= $data_pengguna[0]->no_hp ?: '-' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav profile-tabs" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                    <i class="fas fa-user-circle"></i> Data Pribadi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="employment-tab" data-toggle="tab" href="#employment" role="tab">
                    <i class="fas fa-building"></i> Data Kepegawaian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="benefits-tab" data-toggle="tab" href="#benefits" role="tab">
                    <i class="fas fa-gift"></i> Tunjangan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                    <i class="fas fa-folder-open"></i> Dokumen
                </a>
            </li>
            <?php if (isAdmin() || isHrd() || isGa()) { ?>
            <li class="nav-item">
                <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab">
                    <i class="fas fa-money-bill-wave"></i> Data Gaji
                </a>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" id="signature-tab" data-toggle="tab" href="#signature" role="tab">
                    <i class="fas fa-signature"></i> Tanda Tangan
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="profileTabsContent">
            <!-- Personal Data Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <?= form_open('pengguna/update/edit_on_detail', array('id' => 'personal-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-user-edit"></i> Informasi Pribadi</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user"></i> Nama Lengkap</label>
                                    <input class="form-control" type="text" name="nama" value="<?= $data_pengguna[0]->nama ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-id-card"></i> NIK</label>
                                    <input class="form-control" type="text" name="nik" value="<?= $data_pengguna[0]->nik ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-birthday-cake"></i> Tanggal Lahir</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_lahir" 
                                               value="<?= $data_pengguna[0]->tgl_lahir ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_lahir)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-graduation-cap"></i> Pendidikan Terakhir</label>
                                    <input class="form-control" type="text" name="pendidikan" value="<?= $data_pengguna[0]->pendidikan ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-ring"></i> Status Perkawinan</label>
                                    <input type="hidden" name="id_status_perkawinan" id="id_status_perkawinan" value="<?= $data_pengguna[0]->id_status_perkawinan ?>">
                                    <select class="form-control" name="status_perkawinan" id="status_perkawinan">
                                        <option value="">---Pilih Status Perkawinan---</option>
                                        <?php foreach (status_perkawinan() as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= $data_pengguna[0]->id_status_perkawinan == $row['id'] ? 'selected' : '' ?>><?= $row['jenis'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-users"></i> Pasangan Se-Kantor</label>
                                    <input type="hidden" name="id_pasangan_sekantor" id="id_pasangan_sekantor" value="<?= encrypt($data_pengguna[0]->id_pasangan_sekantor) ?>">
                                    <select data-plugin-selectTwo class="form-control" name="pasangan_sekantor" id="pasangan_sekantor">
                                        <option value="">---Pilih---</option>
                                        <?php foreach ($pengguna as $row) { ?>
                                            <option value="<?= encrypt($row->pengguna_id) ?>" <?= $data_pengguna[0]->id_pasangan_sekantor == $row->pengguna_id ? 'selected' : '' ?>><?= $row->nama ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3"><?= $data_pengguna[0]->alamat ?? '' ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-phone-alt"></i> Kontak Keluarga</label>
                                    <input class="form-control" type="text" name="kontak_keluarga" value="<?= $data_pengguna[0]->kontak_keluarga ?>">
                                </div>
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-friends"></i> Hubungan Keluarga</label>
                                    <input class="form-control" type="text" name="hubungan_keluarga" value="<?= $data_pengguna[0]->hubungan_keluarga ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-address-book"></i> Informasi Kontak</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-envelope"></i> Email</label>
                                    <input class="form-control" type="email" name="email" value="<?= $data_pengguna[0]->email ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-mobile-alt"></i> No. HP</label>
                                    <input class="form-control" type="text" name="no_hp" value="<?= $data_pengguna[0]->no_hp ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <!-- Hidden fields for employment data -->
                        <input type="hidden" name="no_pegawai" value="<?= $data_pengguna[0]->no_pegawai ?>">
                        <input type="hidden" name="status_karyawan" value="<?= $data_pengguna[0]->status_karyawan ?>">
                        <input type="hidden" name="jabatan" value="<?= $data_pengguna[0]->jabatan ?>">
                        <input type="hidden" name="jabatan_visilab" value="<?= $data_pengguna[0]->jabatan_visilab ?>">
                        <input type="hidden" name="lama_training" value="<?= $data_pengguna[0]->lama_training ?>">
                        <input type="hidden" name="no_rek" value="<?= $data_pengguna[0]->no_rek ?>">
                        <input type="hidden" name="npwp" value="<?= $data_pengguna[0]->npwp ?>">
                        <input type="hidden" name="tgl_masuk" value="<?= $data_pengguna[0]->tgl_masuk ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_masuk)) : '' ?>">
                        <input type="hidden" name="tgl_kontrak" value="<?= $data_pengguna[0]->tgl_kontrak ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_kontrak)) : '' ?>">
                        <input type="hidden" name="tgl_keluar" value="<?= $data_pengguna[0]->tgl_keluar ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_keluar)) : '' ?>">
                        <input type="hidden" name="alasan_keluar" value="<?= $data_pengguna[0]->alasan_keluar ?>">
                        <input type="hidden" name="keterangan_lain" value="<?= $data_pengguna[0]->keterangan_lain ?>">
                        <!-- Hidden fields for benefits -->
                        <input type="hidden" name="terima_tunjangan_jabatan" value="<?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_tt" value="<?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_konsumsi" value="<?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_kinerja" value="<?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_komunikasi" value="<?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_transportasi" value="<?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_bbm" value="<?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_raya" value="<?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_bonus_tahunan" value="<?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'on' : '' ?>">
                        
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <?php if (isAdmin() || sessPenggunaId() == $data_pengguna[0]->pengguna_id) { ?>
                            <button type="button" class="btn btn-modern btn-warning-modern btn-reset-password">
                                <i class="fas fa-key"></i> Reset Password
                            </button>
                        <?php } ?>
                        <?php if (isAdmin() || isHrd() || isGa()) { ?>
                            <button type="button" class="btn btn-modern btn-success-modern btn-save">
                                <i class="fas fa-save"></i> Simpan Data Pribadi
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

            <!-- Employment Data Tab -->
            <div class="tab-pane fade" id="employment" role="tabpanel">
                <?= form_open('pengguna/update/edit_on_detail', array('id' => 'employment-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-briefcase"></i> Informasi Kepegawaian</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-id-badge"></i> No. Pegawai (NPP)</label>
                                    <input class="form-control" type="text" name="no_pegawai" value="<?= $data_pengguna[0]->no_pegawai ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-tag"></i> Status Karyawan</label>
                                    <select class="form-control" name="status_karyawan">
                                        <option value="">Pilih Status</option>
                                        <option <?= $data_pengguna[0]->status_karyawan == 'training' ? 'selected' : '' ?> value="training">Training</option>
                                        <option <?= $data_pengguna[0]->status_karyawan == 'kontrak' ? 'selected' : '' ?> value="kontrak">Kontrak</option>
                                        <option <?= $data_pengguna[0]->status_karyawan == 'tetap' ? 'selected' : '' ?> value="tetap">Tetap</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-tie"></i> Jabatan</label>
                                    <input class="form-control" type="text" name="jabatan" value="<?= $data_pengguna[0]->jabatan ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-flask"></i> Jabatan VISILAB</label>
                                    <input class="form-control" type="text" name="jabatan_visilab" value="<?= $data_pengguna[0]->jabatan_visilab ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-hourglass-half"></i> Lama Training (Bulan)</label>
                                    <input class="form-control" type="text" name="lama_training" value="<?= $data_pengguna[0]->lama_training ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-university"></i> No. Rekening</label>
                                    <input class="form-control" type="text" name="no_rek" value="<?= $data_pengguna[0]->no_rek ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-file-invoice"></i> NPWP</label>
                                    <input class="form-control" type="text" name="npwp" value="<?= $data_pengguna[0]->npwp ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-calendar-check"></i> Tanggal Masuk</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_masuk" 
                                               value="<?= $data_pengguna[0]->tgl_masuk ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_masuk)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-file-signature"></i> Tanggal Kontrak</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_kontrak" 
                                               value="<?= $data_pengguna[0]->tgl_kontrak ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_kontrak)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-sign-out-alt"></i> Tanggal Keluar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_keluar" 
                                               value="<?= $data_pengguna[0]->tgl_keluar ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_keluar)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-door-open"></i> Alasan Keluar</label>
                                    <textarea class="form-control" name="alasan_keluar" rows="3"><?= $data_pengguna[0]->alasan_keluar ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-sticky-note"></i> Keterangan Lainnya</label>
                                    <textarea class="form-control" name="keterangan_lain" rows="3"><?= $data_pengguna[0]->keterangan_lain ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <!-- Hidden fields for personal data -->
                        <input type="hidden" name="nama" value="<?= $data_pengguna[0]->nama ?>">
                        <input type="hidden" name="nik" value="<?= $data_pengguna[0]->nik ?>">
                        <input type="hidden" name="email" value="<?= $data_pengguna[0]->email ?>">
                        <input type="hidden" name="no_hp" value="<?= $data_pengguna[0]->no_hp ?>">
                        <input type="hidden" name="tgl_lahir" value="<?= $data_pengguna[0]->tgl_lahir ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_lahir)) : '' ?>">
                        <input type="hidden" name="pendidikan" value="<?= $data_pengguna[0]->pendidikan ?>">
                        <input type="hidden" name="status_perkawinan" value="<?= $data_pengguna[0]->id_status_perkawinan ?>">
                        <input type="hidden" name="pasangan_sekantor" value="<?= encrypt($data_pengguna[0]->id_pasangan_sekantor) ?>">
                        <input type="hidden" name="kontak_keluarga" value="<?= $data_pengguna[0]->kontak_keluarga ?>">
                        <input type="hidden" name="hubungan_keluarga" value="<?= $data_pengguna[0]->hubungan_keluarga ?>">
                        <!-- Hidden fields for benefits -->
                        <input type="hidden" name="terima_tunjangan_jabatan" value="<?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_tt" value="<?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_konsumsi" value="<?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_kinerja" value="<?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_komunikasi" value="<?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_transportasi" value="<?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_bbm" value="<?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_raya" value="<?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_bonus_tahunan" value="<?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'on' : '' ?>">
                        
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <?php if (isAdmin() || isHrd() || isGa()) { ?>
                            <button type="button" class="btn btn-modern btn-success-modern btn-save">
                                <i class="fas fa-save"></i> Simpan Data Kepegawaian
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

            <!-- Benefits Tab -->
            <div class="tab-pane fade" id="benefits" role="tabpanel">
                <?= form_open('pengguna/update/edit_on_detail', array('id' => 'benefits-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-hand-holding-usd"></i> Pengaturan Tunjangan</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_jabatan" name="terima_tunjangan_jabatan" <?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_jabatan"><i class="fas fa-user-tie text-primary"></i> Tunjangan Jabatan</label>
                                </div>
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_tt" name="terima_tunjangan_tt" <?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_tt"><i class="fas fa-random text-info"></i> Tunjangan Tidak Tetap</label>
                                </div>
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_konsumsi" name="terima_tunjangan_konsumsi" <?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_konsumsi"><i class="fas fa-utensils text-warning"></i> Tunjangan Konsumsi</label>
                                </div>
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_kinerja" name="terima_tunjangan_kinerja" <?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_kinerja"><i class="fas fa-chart-line text-success"></i> Tunjangan Kinerja</label>
                                </div>
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_komunikasi" name="terima_tunjangan_komunikasi" <?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_komunikasi"><i class="fas fa-phone text-danger"></i> Tunjangan Komunikasi</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_transportasi" name="terima_tunjangan_transportasi" <?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_transportasi"><i class="fas fa-bus text-secondary"></i> Tunjangan Transportasi</label>
                                </div>
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_bbm" name="terima_tunjangan_bbm" <?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_bbm"><i class="fas fa-gas-pump text-dark"></i> Tunjangan BBM</label>
                                </div>
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_tunjangan_raya" name="terima_tunjangan_raya" <?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'checked' : '' ?>>
                                    <label for="terima_tunjangan_raya"><i class="fas fa-star text-warning"></i> Tunjangan Hari Raya</label>
                                </div>
                                <div class="benefit-checkbox">
                                    <input type="checkbox" id="terima_bonus_tahunan" name="terima_bonus_tahunan" <?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'checked' : '' ?>>
                                    <label for="terima_bonus_tahunan"><i class="fas fa-gift text-danger"></i> Bonus Tahunan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <!-- Hidden fields for personal data -->
                        <input type="hidden" name="nama" value="<?= $data_pengguna[0]->nama ?>">
                        <input type="hidden" name="nik" value="<?= $data_pengguna[0]->nik ?>">
                        <input type="hidden" name="email" value="<?= $data_pengguna[0]->email ?>">
                        <input type="hidden" name="no_hp" value="<?= $data_pengguna[0]->no_hp ?>">
                        <input type="hidden" name="tgl_lahir" value="<?= $data_pengguna[0]->tgl_lahir ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_lahir)) : '' ?>">
                        <input type="hidden" name="pendidikan" value="<?= $data_pengguna[0]->pendidikan ?>">
                        <input type="hidden" name="status_perkawinan" value="<?= $data_pengguna[0]->id_status_perkawinan ?>">
                        <input type="hidden" name="pasangan_sekantor" value="<?= encrypt($data_pengguna[0]->id_pasangan_sekantor) ?>">
                        <input type="hidden" name="kontak_keluarga" value="<?= $data_pengguna[0]->kontak_keluarga ?>">
                        <input type="hidden" name="hubungan_keluarga" value="<?= $data_pengguna[0]->hubungan_keluarga ?>">
                        <!-- Hidden fields for employment data -->
                        <input type="hidden" name="no_pegawai" value="<?= $data_pengguna[0]->no_pegawai ?>">
                        <input type="hidden" name="status_karyawan" value="<?= $data_pengguna[0]->status_karyawan ?>">
                        <input type="hidden" name="jabatan" value="<?= $data_pengguna[0]->jabatan ?>">
                        <input type="hidden" name="jabatan_visilab" value="<?= $data_pengguna[0]->jabatan_visilab ?>">
                        <input type="hidden" name="lama_training" value="<?= $data_pengguna[0]->lama_training ?>">
                        <input type="hidden" name="no_rek" value="<?= $data_pengguna[0]->no_rek ?>">
                        <input type="hidden" name="npwp" value="<?= $data_pengguna[0]->npwp ?>">
                        <input type="hidden" name="tgl_masuk" value="<?= $data_pengguna[0]->tgl_masuk ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_masuk)) : '' ?>">
                        <input type="hidden" name="tgl_kontrak" value="<?= $data_pengguna[0]->tgl_kontrak ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_kontrak)) : '' ?>">
                        <input type="hidden" name="tgl_keluar" value="<?= $data_pengguna[0]->tgl_keluar ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_keluar)) : '' ?>">
                        <input type="hidden" name="alasan_keluar" value="<?= $data_pengguna[0]->alasan_keluar ?>">
                        <input type="hidden" name="keterangan_lain" value="<?= $data_pengguna[0]->keterangan_lain ?>">
                        
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <?php if (isAdmin() || isHrd() || isGa()) { ?>
                            <button type="button" class="btn btn-modern btn-success-modern btn-save">
                                <i class="fas fa-save"></i> Simpan Pengaturan Tunjangan
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-folder-open"></i> Dokumen Karyawan</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="document-grid">
                            <!-- Pas Foto -->
                            <div class="document-card <?= $data_pengguna[0]->file_foto ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-camera"></i>
                                <div class="doc-title">Pas Foto</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_foto ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_foto): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="foto" data-file="<?= $data_pengguna[0]->file_foto ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="foto">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- KTP -->
                            <div class="document-card <?= $data_pengguna[0]->file_ktp ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-id-card"></i>
                                <div class="doc-title">KTP</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_ktp ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_ktp): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="ktp" data-file="<?= $data_pengguna[0]->file_ktp ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="ktp">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Kartu Keluarga -->
                            <div class="document-card <?= $data_pengguna[0]->file_kk ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-users"></i>
                                <div class="doc-title">Kartu Keluarga</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_kk ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_kk): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="kk" data-file="<?= $data_pengguna[0]->file_kk ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="kk">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Ijazah -->
                            <div class="document-card <?= $data_pengguna[0]->file_ijazah ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-graduation-cap"></i>
                                <div class="doc-title">Ijazah</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_ijazah ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_ijazah): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="ijazah" data-file="<?= $data_pengguna[0]->file_ijazah ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="ijazah">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Transkip Nilai -->
                            <div class="document-card <?= $data_pengguna[0]->file_transkip ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-file-alt"></i>
                                <div class="doc-title">Transkip Nilai</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_transkip ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_transkip): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="transkip" data-file="<?= $data_pengguna[0]->file_transkip ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="transkip">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- SIM -->
                            <div class="document-card <?= $data_pengguna[0]->file_sim ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-car"></i>
                                <div class="doc-title">SIM</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_sim ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_sim): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="sim" data-file="<?= $data_pengguna[0]->file_sim ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="sim">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- BPJS -->
                            <div class="document-card <?= $data_pengguna[0]->file_bpjs ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-heartbeat"></i>
                                <div class="doc-title">Kartu BPJS</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_bpjs ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_bpjs): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="bpjs" data-file="<?= $data_pengguna[0]->file_bpjs ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="bpjs">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- SKCK -->
                            <div class="document-card <?= $data_pengguna[0]->file_skck ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-shield-alt"></i>
                                <div class="doc-title">SKCK</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_skck ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_skck): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="skck" data-file="<?= $data_pengguna[0]->file_skck ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="skck">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Buku Tabungan -->
                            <div class="document-card <?= $data_pengguna[0]->file_no_rek ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-piggy-bank"></i>
                                <div class="doc-title">Buku Tabungan</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_no_rek ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_no_rek): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="no_rek" data-file="<?= $data_pengguna[0]->file_no_rek ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="no_rek">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Surat Domisili -->
                            <div class="document-card <?= $data_pengguna[0]->file_domisili ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-home"></i>
                                <div class="doc-title">Surat Domisili</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_domisili ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_domisili): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="domisili" data-file="<?= $data_pengguna[0]->file_domisili ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="domisili">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Sertifikat Vaksin -->
                            <div class="document-card <?= $data_pengguna[0]->file_sertifikat_vaksin ? 'has-file' : '' ?>">
                                <i class="doc-icon fas fa-syringe"></i>
                                <div class="doc-title">Sertifikat Vaksin</div>
                                <span class="doc-status"><?= $data_pengguna[0]->file_sertifikat_vaksin ? 'Tersedia' : 'Belum Upload' ?></span>
                                <div class="doc-actions">
                                    <?php if($data_pengguna[0]->file_sertifikat_vaksin): ?>
                                        <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="sertifikat_vaksin" data-file="<?= $data_pengguna[0]->file_sertifikat_vaksin ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (isAdmin() || isHrd() || isGa()) { ?>
                                        <button type="button" class="btn btn-primary btn-doc btn-upload-file" data-type="sertifikat_vaksin">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Tab -->
            <?php if (isAdmin() || isHrd() || isGa()) { ?>
            <div class="tab-pane fade" id="salary" role="tabpanel">
                <?= form_open('pengguna/addgaji/', array('id' => 'salary-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-money-bill-wave"></i> Informasi Gaji</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-wallet"></i> Gaji Pokok</label>
                                    <input class="form-control input_salary" type="text" name="gaji_pokok" 
                                           value="<?= isset($data_salary[0]->gaji_pokok) ? $data_salary[0]->gaji_pokok : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-tie"></i> Tunjangan Jabatan</label>
                                    <input class="form-control input_salary" type="text" name="tunjangan_jabatan" 
                                           value="<?= isset($data_salary[0]->tunjangan_jabatan) ? $data_salary[0]->tunjangan_jabatan : 0 ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-percentage"></i> Komisi</label>
                                    <input class="form-control input_salary" type="text" name="komisi" 
                                           value="<?= isset($data_salary[0]->komisi) ? $data_salary[0]->komisi : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-plus-circle"></i> Pendapatan Lain</label>
                                    <input class="form-control input_salary" type="text" name="pendapatan_lain" 
                                           value="<?= isset($data_salary[0]->pendapatan_lain) ? $data_salary[0]->pendapatan_lain : 0 ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-utensils"></i> Tunjangan Konsumsi</label>
                                    <input class="form-control input_salary" type="text" name="tunjangan_konsumsi" 
                                           value="<?= isset($data_salary[0]->tunjangan_konsumsi) ? $data_salary[0]->tunjangan_konsumsi : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-chart-line"></i> Tunjangan Kinerja</label>
                                    <input class="form-control input_salary" type="text" name="tunjangan_kinerja" 
                                           value="<?= isset($data_salary[0]->tunjangan_kinerja) ? $data_salary[0]->tunjangan_kinerja : 0 ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-phone"></i> Tunjangan Komunikasi</label>
                                    <input class="form-control input_salary" type="text" name="tunjangan_komunikasi" 
                                           value="<?= isset($data_salary[0]->tunjangan_komunikasi) ? $data_salary[0]->tunjangan_komunikasi : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-bus"></i> Tunjangan Transportasi</label>
                                    <input class="form-control input_salary" type="text" name="tunjangan_transportasi" 
                                           value="<?= isset($data_salary[0]->tunjangan_transportasi) ? $data_salary[0]->tunjangan_transportasi : 0 ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-gas-pump"></i> Tunjangan BBM</label>
                                    <input class="form-control input_salary" type="text" name="tunjangan_bbm" 
                                           value="<?= isset($data_salary[0]->tunjangan_bbm) ? $data_salary[0]->tunjangan_bbm : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-minus-circle"></i> Potongan Tunjangan</label>
                                    <input class="form-control input_salary" type="text" name="potongan" 
                                           value="<?= isset($data_salary[0]->potongan) ? $data_salary[0]->potongan : 0 ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-heartbeat"></i> Dasar Potong BPJS Kesehatan</label>
                                    <input class="form-control input_salary" type="text" name="dasar_bpjs_sehat" 
                                           value="<?= isset($data_salary[0]->dasar_bpjs_sehat) ? $data_salary[0]->dasar_bpjs_sehat : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-hard-hat"></i> Dasar Potong BPJS Ketenagakerjaan</label>
                                    <input class="form-control input_salary" type="text" name="dasar_bpjs_kerja" 
                                           value="<?= isset($data_salary[0]->dasar_bpjs_kerja) ? $data_salary[0]->dasar_bpjs_kerja : 0 ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-star"></i> Tunjangan Hari Raya</label>
                                    <input class="form-control input_salary" type="text" name="tunjangan_raya" 
                                           value="<?= isset($data_salary[0]->tunjangan_raya) ? $data_salary[0]->tunjangan_raya : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-gift"></i> Bonus Tahunan</label>
                                    <input class="form-control input_salary" type="text" name="bonus_tahunan" 
                                           value="<?= isset($data_salary[0]->bonus_tahunan) ? $data_salary[0]->bonus_tahunan : 0 ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-modern btn-success-modern btn-save">
                            <i class="fas fa-save"></i> Simpan Data Gaji
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
            <?php } ?>
            
            <!-- Signature Tab -->
            <div class="tab-pane fade" id="signature" role="tabpanel">
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-signature"></i> Tanda Tangan Digital</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="signature-container text-center">
                                    <?php 
                                    $ttd_file = 'uploads/file_karyawan/ttd/ttd_' . $data_pengguna[0]->pengguna_id . '.png';
                                    $ttd_exists = file_exists(FCPATH . $ttd_file);
                                    ?>
                                    
                                    <div class="signature-preview-box" id="signature-preview-box">
                                        <?php if($ttd_exists): ?>
                                        <img src="<?= base_url($ttd_file) ?>?t=<?= time() ?>" alt="Tanda Tangan" class="signature-image" id="current-signature">
                                        <?php else: ?>
                                        <div class="no-signature" id="no-signature">
                                            <i class="fas fa-file-signature"></i>
                                            <p>Belum ada tanda tangan</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="signature-info mt-4">
                                        <p class="text-muted mb-3">
                                            <i class="fas fa-info-circle"></i> 
                                            Tanda tangan akan digunakan pada dokumen resmi perusahaan.
                                        </p>
                                        <p class="text-muted small">
                                            Format yang didukung: PNG (Disarankan dengan background transparan)<br>
                                            Ukuran maksimal: 2MB
                                        </p>
                                    </div>
                                    
                                    <div class="signature-actions mt-4">
                                        <label for="signature-file-input" class="btn btn-modern btn-primary-modern mb-0">
                                            <i class="fas fa-upload"></i> <?= $ttd_exists ? 'Ganti' : 'Upload' ?> Tanda Tangan
                                        </label>
                                        <input type="file" id="signature-file-input" accept=".png" style="display: none;">
                                        
                                        <?php if($ttd_exists): ?>
                                        <button type="button" class="btn btn-modern btn-danger-modern" id="btn-delete-signature">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Upload Progress -->
                                    <div class="upload-progress mt-4" id="signature-upload-progress" style="display: none;">
                                        <div class="progress" style="height: 10px; border-radius: 5px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"></div>
                                        </div>
                                        <p class="text-muted mt-2 small">Mengupload...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Upload Modal -->
<div id="file-upload-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <h5 class="modal-title"><i class="fas fa-cloud-upload-alt"></i> <span id="upload-modal-title">Upload Dokumen</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('#', array('id' => 'file-upload-form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data')); ?>
            <div class="modal-body" style="padding: 30px;">
                <div class="form-group-modern">
                    <label><i class="fas fa-file"></i> Pilih File</label>
                    <input type="file" class="form-control" name="file_input" id="file_input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> Format yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX (Max: 4MB)
                    </small>
                </div>
                <div id="file-preview-area" class="text-center mt-3" style="display: none;">
                    <img id="image-preview" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 10px; display: none;">
                    <div id="pdf-preview-icon" style="display: none;">
                        <i class="fas fa-file-pdf fa-5x text-danger"></i>
                        <p class="mt-2" id="file-name-preview"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border: none; padding: 20px 30px;">
                <input type="hidden" id="upload_pengguna_id" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                <input type="hidden" id="upload_file_type" name="file_type" value="">
                <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" id="btn-submit-upload" class="btn btn-modern btn-success-modern">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- File Preview Modal -->
<div id="file-preview-modal" class="modal fade modal-pdf-preview" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file"></i> <span id="preview-modal-title">Preview Dokumen</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="preview-content">
                    <!-- PDF Preview -->
                    <div id="pdf-preview-container" class="pdf-viewer-container" style="display: none;">
                        <iframe id="pdf-iframe" src=""></iframe>
                    </div>
                    <!-- Image Preview -->
                    <div id="image-preview-container" class="image-preview-container" style="display: none;">
                        <img id="preview-image" src="" alt="Preview">
                    </div>
                    <!-- Other File Type -->
                    <div id="other-preview-container" class="text-center p-5" style="display: none;">
                        <i class="fas fa-file fa-5x text-secondary mb-3"></i>
                        <p>File ini tidak dapat ditampilkan preview.</p>
                        <a id="download-link" href="#" class="btn btn-modern btn-primary-modern" target="_blank">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border: none;">
                <a id="preview-download-link" href="#" class="btn btn-modern btn-primary-modern" target="_blank">
                    <i class="fas fa-download"></i> Download
                </a>
                <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="reset-password-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none;">
                <h5 class="modal-title"><i class="fas fa-key"></i> Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('pengguna/reset', array('id' => 'reset-password-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body" style="padding: 30px;">
                <div class="form-group-modern">
                    <label><i class="fas fa-lock"></i> Password Baru</label>
                    <input class="form-control" type="password" name="password" id="new_password" placeholder="Masukkan password baru" required>
                    <small class="text-muted">Minimal 8 karakter, kombinasi huruf dan angka</small>
                </div>
                <div class="form-group-modern">
                    <label><i class="fas fa-lock"></i> Konfirmasi Password</label>
                    <input class="form-control" type="password" name="rpassword" id="confirm_password" placeholder="Konfirmasi password baru" required>
                </div>
            </div>
            <div class="modal-footer" style="border: none; padding: 20px 30px;">
                <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-modern btn-warning-modern btn-save">
                    <i class="fas fa-save"></i> Reset Password
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay hide" id="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize salary input masks
    $('.input_salary').mask('000.000.000.000', {
        reverse: true
    });

    // File type mappings for document titles
    const fileTypeTitles = {
        'foto': 'Pas Foto',
        'ktp': 'KTP',
        'kk': 'Kartu Keluarga',
        'ijazah': 'Ijazah',
        'transkip': 'Transkip Nilai',
        'sim': 'SIM',
        'bpjs': 'Kartu BPJS',
        'skck': 'SKCK',
        'no_rek': 'Buku Tabungan',
        'domisili': 'Surat Domisili',
        'sertifikat_vaksin': 'Sertifikat Vaksin'
    };

    // Reset Password Modal
    $(document).on('click', '.btn-reset-password', function() {
        $('#reset-password-modal').modal('show');
    });

    // Upload File Button
    $(document).on('click', '.btn-upload-file', function() {
        var fileType = $(this).data('type');
        var title = fileTypeTitles[fileType] || 'Dokumen';
        
        $('#upload-modal-title').text('Upload ' + title);
        $('#upload_file_type').val(fileType);
        $('#file_input').val('');
        $('#file-preview-area').hide();
        $('#file-upload-form').attr('action', 'pengguna/update/file/' + fileType);
        $('#file-upload-modal').modal('show');
    });

    // File input preview
    $('#file_input').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            var fileName = file.name;
            var fileExt = fileName.split('.').pop().toLowerCase();
            
            $('#file-preview-area').show();
            
            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result).show();
                    $('#pdf-preview-icon').hide();
                };
                reader.readAsDataURL(file);
            } else {
                $('#image-preview').hide();
                $('#pdf-preview-icon').show();
                $('#file-name-preview').text(fileName);
            }
        }
    });

    // Submit Upload
    $('#btn-submit-upload').on('click', function() {
        var formData = new FormData($('#file-upload-form')[0]);
        var fileType = $('#upload_file_type').val();
        
        // Rename file input to match expected name
        var fileInput = $('#file_input')[0].files[0];
        if (!fileInput) {
            Swal.fire('Error', 'Silahkan pilih file terlebih dahulu', 'error');
            return;
        }
        
        formData.delete('file_input');
        formData.append(fileType, fileInput);
        formData.append('csrf_token', token);
        
        $('#loading-overlay').removeClass('hide');
        
        $.ajax({
            url: '<?= base_url() ?>pengguna/update/file/' + fileType,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#loading-overlay').addClass('hide');
                try {
                    var res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.msg, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.msg, 'error');
                    }
                } catch(e) {
                    Swal.fire('Berhasil', 'File berhasil diupload', 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                $('#loading-overlay').addClass('hide');
                Swal.fire('Error', 'Terjadi kesalahan saat mengupload file', 'error');
            }
        });
    });

    // Preview File Button
    $(document).on('click', '.btn-preview-file', function() {
        var fileType = $(this).data('type');
        var fileName = $(this).data('file');
        var title = fileTypeTitles[fileType] || 'Dokumen';
        var fileUrl = '<?= base_url() ?>uploads/file_karyawan/' + fileType + '/' + fileName;
        var fileExt = fileName.split('.').pop().toLowerCase();
        
        $('#preview-modal-title').text('Preview ' + title);
        $('#preview-download-link').attr('href', fileUrl);
        $('#download-link').attr('href', fileUrl);
        
        // Hide all preview containers
        $('#pdf-preview-container').hide();
        $('#image-preview-container').hide();
        $('#other-preview-container').hide();
        
        if (fileExt === 'pdf') {
            $('#pdf-iframe').attr('src', fileUrl);
            $('#pdf-preview-container').show();
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            $('#preview-image').attr('src', fileUrl);
            $('#image-preview-container').show();
        } else {
            $('#other-preview-container').show();
        }
        
        $('#file-preview-modal').modal('show');
    });

    // Clear iframe when modal closes
    $('#file-preview-modal').on('hidden.bs.modal', function() {
        $('#pdf-iframe').attr('src', '');
    });

    // Tab persistence
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        localStorage.setItem('activeProfileTab', $(e.target).attr('href'));
    });

    var activeTab = localStorage.getItem('activeProfileTab');
    if (activeTab) {
        $('#profileTabs a[href="' + activeTab + '"]').tab('show');
    }

    // ============ SIGNATURE UPLOAD ============
    $('#signature-file-input').on('change', function() {
        var file = this.files[0];
        if (!file) return;
        
        // Validate file type
        if (file.type !== 'image/png') {
            Swal.fire('Error', 'Hanya file PNG yang diperbolehkan!', 'error');
            $(this).val('');
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Error', 'Ukuran file maksimal 2MB!', 'error');
            $(this).val('');
            return;
        }
        
        // Confirm upload
        Swal.fire({
            title: 'Upload Tanda Tangan?',
            text: 'File tanda tangan akan diupload.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Upload!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                uploadSignature(file);
            } else {
                $('#signature-file-input').val('');
            }
        });
    });

    function uploadSignature(file) {
        var formData = new FormData();
        formData.append('signature_file', file);
        formData.append('pengguna_id', '<?= encrypt($data_pengguna[0]->pengguna_id) ?>');
        formData.append('csrf_token', token);
        
        $('#signature-upload-progress').show();
        
        $.ajax({
            url: '<?= base_url("pengguna/upload_signature") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percent = Math.round((e.loaded / e.total) * 100);
                        $('#signature-upload-progress .progress-bar').css('width', percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                $('#signature-upload-progress').hide();
                try {
                    var res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.msg || 'Tanda tangan berhasil diupload!', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.msg || 'Gagal mengupload tanda tangan', 'error');
                    }
                } catch(e) {
                    Swal.fire('Berhasil', 'Tanda tangan berhasil diupload!', 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                $('#signature-upload-progress').hide();
                Swal.fire('Error', 'Terjadi kesalahan saat mengupload file', 'error');
            }
        });
    }

    // Delete Signature
    $('#btn-delete-signature').on('click', function() {
        Swal.fire({
            title: 'Hapus Tanda Tangan?',
            text: 'Tanda tangan akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("pengguna/delete_signature") ?>',
                    type: 'POST',
                    data: {
                        pengguna_id: '<?= encrypt($data_pengguna[0]->pengguna_id) ?>',
                        csrf_token: token
                    },
                    success: function(response) {
                        try {
                            var res = typeof response === 'string' ? JSON.parse(response) : response;
                            if (res.status === 'success') {
                                Swal.fire('Berhasil', res.msg || 'Tanda tangan berhasil dihapus!', 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.msg || 'Gagal menghapus tanda tangan', 'error');
                            }
                        } catch(e) {
                            Swal.fire('Berhasil', 'Tanda tangan berhasil dihapus!', 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan', 'error');
                    }
                });
            }
        });
    });
});

function goBack() {
    window.history.back();
}
</script>
