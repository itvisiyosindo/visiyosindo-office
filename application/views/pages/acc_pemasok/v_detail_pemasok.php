<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.05);
        --success-color: #10b981;
        --danger-color: #ef4444;
    }
    .pemasok-detail-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px 15px;
    }
    .profile-header-card {
        background: var(--primary-gradient);
        border-radius: 16px;
        padding: 30px;
        color: white;
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
    }
    .profile-tabs {
        background: white;
        border-radius: 12px;
        padding: 5px;
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .profile-tabs .nav-link {
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s ease;
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
    .form-group-modern {
        margin-bottom: 20px;
    }
    .form-group-modern label {
        font-weight: 700;
        color: #334155;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
    .form-group-modern .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    .form-group-modern .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        background: white;
    }
    .dynamic-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>

<header class="page-header">
    <h2><i class="fas fa-truck-loading"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('acc_pemasok') ?>"><span>Data Pemasok</span></a></li>
            <li><span>Detail</span></li>
        </ol>
    </div>
</header>

<div class="pemasok-detail-container">
    <div class="container-fluid">
        <!-- HEADER PROFILE CARD -->
        <div class="profile-header-card">
            <div class="row align-items-center">
                <div class="col-lg-2 text-center mb-3 mb-lg-0">
                    <div style="background: rgba(255,255,255,0.2); width:100px; height:100px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                        <i class="fas fa-building fa-4x text-white"></i>
                    </div>
                </div>
                <div class="col-lg-6 text-center text-lg-left">
                    <h2 class="font-weight-bold text-white mb-2"><?= $pemasok ? htmlspecialchars($pemasok->nama_pemasok) : 'Pemasok Baru' ?></h2>
                    <p class="mb-2 text-white-50"><i class="fas fa-globe"></i> <?= $pemasok ? htmlspecialchars($pemasok->negara ?: '-') : 'Negara belum diatur' ?></p>
                    <?php if ($pemasok && $pemasok->link_gdrive) { ?>
                        <a href="<?= $pemasok->link_gdrive ?>" target="_blank" class="btn btn-xs btn-light text-dark"><i class="fab fa-google-drive"></i> Google Drive</a>
                    <?php } ?>
                </div>
                <div class="col-lg-4 text-center text-lg-right mt-3 mt-lg-0">
                    <span class="badge badge-lg p-2 font-weight-bold text-uppercase" style="background: rgba(255,255,255,0.25); color: white; border-radius: 8px;">
                        <?= $pemasok ? htmlspecialchars($pemasok->status_nama ?: 'Draft') : 'Draft' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- FORM OPEN -->
        <?= form_open('acc_pemasok/save', array('id' => 'form-detail-pemasok')); ?>
        <input type="hidden" name="id_pemasok" value="<?= $pemasok ? encrypt($pemasok->id) : '' ?>">

        <!-- TAB NAVIGATION -->
        <ul class="nav profile-tabs" id="pemasokTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="utama-tab" data-toggle="tab" href="#utama" role="tab"><i class="fas fa-info-circle"></i> Info Utama</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="kontak-tab" data-toggle="tab" href="#kontak" role="tab"><i class="fas fa-address-book"></i> Kontak & Keuangan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sertifikasi-tab" data-toggle="tab" href="#sertifikasi" role="tab"><i class="fas fa-certificate"></i> Sertifikasi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="agree-tab" data-toggle="tab" href="#agree" role="tab"><i class="fas fa-file-contract"></i> Agreement / LOA</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="brochure-tab" data-toggle="tab" href="#brochure" role="tab"><i class="fas fa-images"></i> Produk / Brochure</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="harga-tab" data-toggle="tab" href="#harga" role="tab"><i class="fas fa-tags"></i> Informasi Harga</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="expo-tab" data-toggle="tab" href="#expo" role="tab"><i class="fas fa-award"></i> Hospital Expo</a>
            </li>
        </ul>

        <!-- TAB CONTENTPANELS -->
        <div class="tab-content" id="pemasokTabsContent">
            <!-- 1. INFO UTAMA -->
            <div class="tab-pane fade show active" id="utama" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="font-weight-bold text-dark mb-4"><i class="fas fa-info-circle text-primary"></i> Informasi Utama Pemasok</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Nama Pemasok <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_pemasok" value="<?= $pemasok ? htmlspecialchars($pemasok->nama_pemasok) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group-modern">
                                    <label>Status Pemasok <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="id_status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <?php foreach ($status_list as $st) { ?>
                                            <?php $selected = ($pemasok && $pemasok->id_status == $st->id) ? 'selected' : ''; ?>
                                            <option value="<?= $st->id ?>" <?= $selected ?>><?= $st->nama ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group-modern">
                                    <label>Tipe Pemasok <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="id_tipe" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <?php foreach ($tipe_list as $tp) { ?>
                                            <?php $selected = ($pemasok && $pemasok->id_tipe == $tp->id) ? 'selected' : ''; ?>
                                            <option value="<?= $tp->id ?>" <?= $selected ?>><?= $tp->nama ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Negara <span class="text-danger">*</span></label>
                                    <select class="form-control" name="negara" data-current-val="<?= $pemasok ? htmlspecialchars($pemasok->negara) : '' ?>" required>
                                        <option value="">-- Pilih Negara --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label>Produk Terkait</label>
                                    <select class="form-control select2" name="products[]" multiple data-placeholder="Pilih produk dari master data...">
                                        <?php foreach ($product_list as $pr) { ?>
                                            <?php 
                                            $selected = '';
                                            if ($pemasok && !empty($pemasok->products)) {
                                                foreach ($pemasok->products as $p_rel) {
                                                    if ($p_rel->id == $pr->id) {
                                                        $selected = 'selected';
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <option value="<?= $pr->id ?>" <?= $selected ?>><?= $pr->nama ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group-modern">
                                    <label>Tanggal Terdata Awal</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tanggal_terdata_awal" value="<?= $pemasok && $pemasok->tanggal_terdata_awal ? date('d-m-Y', strtotime($pemasok->tanggal_terdata_awal)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group-modern">
                                    <label>Tanggal LOA Awal</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tanggal_loa_awal" value="<?= $pemasok && $pemasok->tanggal_loa_awal ? date('d-m-Y', strtotime($pemasok->tanggal_loa_awal)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group-modern">
                                    <label>Tanggal Berakhir LOA</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="tanggal_berakhir" value="<?= $pemasok && $pemasok->tanggal_berakhir ? date('d-m-Y', strtotime($pemasok->tanggal_berakhir)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group-modern">
                                    <label>Link GDrive Dokumen</label>
                                    <input type="url" class="form-control" name="link_gdrive" value="<?= $pemasok ? htmlspecialchars($pemasok->link_gdrive) : '' ?>" placeholder="https://drive.google.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. KONTAK & KEUANGAN -->
            <div class="tab-pane fade" id="kontak" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="font-weight-bold text-dark mb-4"><i class="fas fa-address-book text-primary"></i> Kontak, Keuangan & Info Lainnya</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group-modern">
                                    <label>Alamat Lengkap</label>
                                    <textarea class="form-control" name="alamat" rows="4"><?= $pemasok ? htmlspecialchars($pemasok->alamat) : '' ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label>Kontak Utama (No HP/Telp/PIC)</label>
                                            <input type="text" class="form-control" name="kontak" value="<?= $pemasok ? htmlspecialchars($pemasok->kontak) : '' ?>" placeholder="Contoh: Budi Santoso (0812-xxx)">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label>Website</label>
                                            <input type="url" class="form-control" name="website" value="<?= $pemasok ? htmlspecialchars($pemasok->website) : '' ?>" placeholder="https://...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 border-top pt-4">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group-modern">
                                    <label>NPWP Pemasok</label>
                                    <input type="text" class="form-control" name="npwp" value="<?= $pemasok ? htmlspecialchars($pemasok->npwp) : '' ?>" placeholder="Masukkan nomor NPWP...">
                                </div>
                                <div class="form-group-modern">
                                    <label>Informasi Bank / Pembayaran</label>
                                    <textarea class="form-control" name="info_bank" rows="3" placeholder="Nama Bank, No Rekening, Atas Nama, dll..."><?= $pemasok ? htmlspecialchars($pemasok->info_bank) : '' ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group-modern">
                                    <label>Informasi Partner / Distributor</label>
                                    <textarea class="form-control" name="info_partner" rows="2" placeholder="Informasi jaringan distribusi atau sub-partner..."><?= $pemasok ? htmlspecialchars($pemasok->info_partner) : '' ?></textarea>
                                </div>
                                <div class="form-group-modern">
                                    <label>Informasi Garansi Produk</label>
                                    <textarea class="form-control" name="info_garansi" rows="2" placeholder="Ketentuan garansi, klaim, dll..."><?= $pemasok ? htmlspecialchars($pemasok->info_garansi) : '' ?></textarea>
                                </div>
                                <div class="form-group-modern">
                                    <label>Ketentuan Pembayaran (Term of Payment / TOP)</label>
                                    <input type="text" class="form-control" name="info_pembayaran" value="<?= $pemasok ? htmlspecialchars($pemasok->info_pembayaran) : '' ?>" placeholder="Contoh: COD, NET 30, NET 60...">
                                </div>
                            </div>
                        </div>

                        <!-- DINAMIS INFO LAINNYA -->
                        <div class="row mt-3 border-top pt-4">
                            <div class="col-lg-12">
                                <label class="font-weight-bold text-dark d-block mb-3">Informasi Lainnya (Dinamis)</label>
                                <div id="info-lainnya-wrapper">
                                    <?php 
                                    $info_lainnya_arr = [];
                                    if ($pemasok && $pemasok->info_lainnya) {
                                        $info_lainnya_arr = json_decode($pemasok->info_lainnya, true);
                                    }
                                    if (!empty($info_lainnya_arr)) {
                                        foreach ($info_lainnya_arr as $key => $val) {
                                    ?>
                                            <div class="row info-lainnya-row mb-2">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="info_lainnya_key[]" value="<?= htmlspecialchars($key) ?>" placeholder="Nama Kolom (e.g. Info Warehouse)">
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="info_lainnya_value[]" value="<?= htmlspecialchars($val) ?>" placeholder="Isi Nilai/Keterangan">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-block btn-remove-info-row"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                    <?php 
                                        }
                                    } 
                                    ?>
                                </div>
                                <button type="button" class="btn btn-success btn-sm mt-2" id="btn-add-info-lainnya"><i class="fas fa-plus"></i> Tambah Informasi Lainnya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. SERTIFIKASI -->
            <div class="tab-pane fade" id="sertifikasi" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-certificate text-primary"></i> Dokumen Sertifikasi Pemasok</h4>
                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-sertifikasi"><i class="fas fa-plus"></i> Tambah Baris Sertifikasi</button>
                        </div>
                        <div id="sertifikasi-wrapper">
                            <?php 
                            if ($pemasok && !empty($pemasok->sertifikasi)) {
                                foreach ($pemasok->sertifikasi as $idx => $sert) {
                                    $tgl_dok = $sert->tanggal_dokumen ? date('d-m-Y', strtotime($sert->tanggal_dokumen)) : '';
                                    $masa = $sert->masa_berlaku ? date('d-m-Y', strtotime($sert->masa_berlaku)) : '';
                            ?>
                                    <div class="dynamic-row sertifikasi-row" data-row-idx="<?= $idx ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                                                <input type="text" class="form-control" name="sert_nama_dokumen[]" value="<?= htmlspecialchars($sert->nama_dokumen) ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="sert_tanggal_dokumen[]" value="<?= $tgl_dok ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted font-weight-bold">Masa Berlaku</label>
                                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="sert_masa_berlaku[]" value="<?= $masa ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted font-weight-bold">Link Dokumen</label>
                                                <input type="url" class="form-control" name="sert_link_dokumen[]" value="<?= htmlspecialchars($sert->link_dokumen ?: '') ?>" placeholder="https://drive.google.com/...">
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <label class="small d-block text-white">Action</label>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                        
                                        <!-- Custom fields nested inside row (Kolom Dokumen Lainnya) -->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <label class="small font-weight-bold text-dark"><i class="fas fa-plus-circle"></i> Kolom Dokumen Lainnya (Dinamis)</label>
                                                <div class="sert-custom-cols-container">
                                                    <?php 
                                                    $custom_cols = [];
                                                    if ($sert->kolom_lainnya) {
                                                        $custom_cols = json_decode($sert->kolom_lainnya, true);
                                                    }
                                                    if (!empty($custom_cols)) {
                                                        foreach ($custom_cols as $c_idx => $col) {
                                                    ?>
                                                            <div class="row custom-col-item mb-2 align-items-center">
                                                                <div class="col-md-4">
                                                                    <input type="text" class="form-control input-sm" name="sert_custom_key[<?= $idx ?>][<?= $c_idx ?>]" value="<?= htmlspecialchars($col['label']) ?>" placeholder="Kolom (e.g. Instansi Penerbit)">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <input type="text" class="form-control input-sm" name="sert_custom_val[<?= $idx ?>][<?= $c_idx ?>]" value="<?= htmlspecialchars($col['value']) ?>" placeholder="Keterangan">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-xs btn-danger btn-remove-custom-col"><i class="fas fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                    <?php 
                                                        }
                                                    } 
                                                    ?>
                                                </div>
                                                <button type="button" class="btn btn-xs btn-default btn-add-custom-col mt-1" data-row-idx="<?= $idx ?>"><i class="fas fa-plus"></i> Tambah Kolom Lainnya</button>
                                            </div>
                                        </div>
                                    </div>
                            <?php 
                                }
                            } else {
                            ?>
                                <p class="text-muted no-rows-msg text-center my-4">Belum ada dokumen sertifikasi yang dimasukkan.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. AGREEMENT / LOA -->
            <div class="tab-pane fade" id="agree" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-file-contract text-primary"></i> Daftar Agreement / LOA</h4>
                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-agree"><i class="fas fa-plus"></i> Tambah Baris Agreement</button>
                        </div>
                        <div id="agree-wrapper">
                            <?php 
                            if ($pemasok && !empty($pemasok->agreement)) {
                                foreach ($pemasok->agreement as $agree) {
                                    $tgl_dok = $agree->tanggal_dokumen ? date('d-m-Y', strtotime($agree->tanggal_dokumen)) : '';
                                    $masa = $agree->masa_berlaku ? date('d-m-Y', strtotime($agree->masa_berlaku)) : '';
                            ?>
                                    <div class="dynamic-row">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                                                <input type="text" class="form-control" name="agree_nama_dokumen[]" value="<?= htmlspecialchars($agree->nama_dokumen) ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="agree_tanggal_dokumen[]" value="<?= $tgl_dok ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted font-weight-bold">Masa Berlaku</label>
                                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="agree_masa_berlaku[]" value="<?= $masa ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted font-weight-bold">Link Dokumen</label>
                                                <input type="url" class="form-control" name="agree_link_dokumen[]" value="<?= htmlspecialchars($agree->link_dokumen ?: '') ?>" placeholder="https://drive.google.com/...">
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <label class="small d-block text-white">Action</label>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                            <?php 
                                }
                            } else {
                            ?>
                                <p class="text-muted no-rows-msg text-center my-4">Belum ada agreement / LOA yang dimasukkan.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. BROCHURE -->
            <div class="tab-pane fade" id="brochure" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-images text-primary"></i> Informasi Produk / Brochure</h4>
                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-brochure"><i class="fas fa-plus"></i> Tambah Baris Brochure</button>
                        </div>
                        <div id="brochure-wrapper">
                            <?php 
                            if ($pemasok && !empty($pemasok->brochure)) {
                                foreach ($pemasok->brochure as $broch) {
                                    $tgl_dok = $broch->tanggal_dokumen ? date('d-m-Y', strtotime($broch->tanggal_dokumen)) : '';
                            ?>
                                    <div class="dynamic-row">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                                                <input type="text" class="form-control" name="broch_nama_dokumen[]" value="<?= htmlspecialchars($broch->nama_dokumen) ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="broch_tanggal_dokumen[]" value="<?= $tgl_dok ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted font-weight-bold">Link Dokumen</label>
                                                <input type="url" class="form-control" name="broch_link_dokumen[]" value="<?= htmlspecialchars($broch->link_dokumen ?: '') ?>" placeholder="https://drive.google.com/...">
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <label class="small d-block text-white">Action</label>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                            <?php 
                                }
                            } else {
                            ?>
                                <p class="text-muted no-rows-msg text-center my-4">Belum ada brochure produk yang dimasukkan.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. INFORMASI HARGA -->
            <div class="tab-pane fade" id="harga" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-tags text-primary"></i> Daftar Informasi Harga</h4>
                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-harga"><i class="fas fa-plus"></i> Tambah Baris Info Harga</button>
                        </div>
                        <div id="harga-wrapper">
                            <?php 
                            if ($pemasok && !empty($pemasok->harga)) {
                                foreach ($pemasok->harga as $hrg) {
                                    $tgl_dok = $hrg->tanggal_dokumen ? date('d-m-Y', strtotime($hrg->tanggal_dokumen)) : '';
                                    $masa = $hrg->masa_berlaku ? date('d-m-Y', strtotime($hrg->masa_berlaku)) : '';
                            ?>
                                    <div class="dynamic-row">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                                                <input type="text" class="form-control" name="harga_nama_dokumen[]" value="<?= htmlspecialchars($hrg->nama_dokumen) ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="harga_tanggal_dokumen[]" value="<?= $tgl_dok ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted font-weight-bold">Masa Berlaku</label>
                                                <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' class="form-control" name="harga_masa_berlaku[]" value="<?= $masa ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small text-muted font-weight-bold">Link Dokumen</label>
                                                <input type="url" class="form-control" name="harga_link_dokumen[]" value="<?= htmlspecialchars($hrg->link_dokumen ?: '') ?>" placeholder="https://drive.google.com/...">
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <label class="small d-block text-white">Action</label>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                            <?php 
                                }
                            } else {
                            ?>
                                <p class="text-muted no-rows-msg text-center my-4">Belum ada daftar informasi harga yang dimasukkan.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. HOSPITAL EXPO -->
            <div class="tab-pane fade" id="expo" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="font-weight-bold text-dark mb-4"><i class="fas fa-award text-primary"></i> Keikutsertaan Hospital Expo</h4>
                        <p class="text-muted">Checlist event Hospital Expo yang pernah/sedang diikuti oleh pemasok ini:</p>
                        
                        <div class="row">
                            <?php foreach ($hospital_expo_list as $he) { ?>
                                <?php 
                                $checked = '';
                                if ($pemasok && !empty($pemasok->hospital_expos)) {
                                    foreach ($pemasok->hospital_expos as $h_rel) {
                                        if ($h_rel->id == $he->id) {
                                            $checked = 'checked';
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="checkbox-custom checkbox-primary" style="background:#f8fafc; padding:12px; border-radius:8px; border: 1px solid #e2e8f0;">
                                        <input type="checkbox" name="hospital_expos[]" id="expo-<?= $he->id ?>" value="<?= $he->id ?>" <?= $checked ?>>
                                        <label for="expo-<?= $he->id ?>" class="font-weight-semibold text-dark">
                                            <?= htmlspecialchars($he->nama) ?> 
                                            <span class="badge badge-info ml-1"><?= $he->tahun ?></span>
                                            <?php if ($he->lokasi) { ?>
                                                <small class="d-block text-muted"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($he->lokasi) ?></small>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTION FOOTER BUTTONS -->
        <div class="row mt-4 mb-5">
            <div class="col-lg-12 text-right">
                <a href="<?= base_url('acc_pemasok') ?>" class="btn btn-default mr-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary" id="btn-save-pemasok"><i class="fas fa-save"></i> Simpan Pemasok</button>
            </div>
        </div>

        <?= form_close(); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Fetch countries from local controller (solving CORS)
    $.ajax({
        url: '<?= base_url("acc_pemasok/get_countries") ?>',
        method: 'GET',
        dataType: 'JSON',
        success: function(resp) {
            if (resp.success && resp.data) {
                var select = $('select[name="negara"]');
                var current_val = select.data('current-val');
                
                resp.data.forEach(function(country) {
                    select.append(new Option(country, country, false, country === current_val));
                });
                
                select.select2({
                    theme: 'bootstrap',
                    width: '100%',
                    placeholder: '-- Pilih Negara --',
                    allowClear: true
                });
            }
        },
        error: function() {
            // Fallback list of major countries
            var fallback = ['Indonesia', 'Singapore', 'Malaysia', 'Japan', 'China', 'United States', 'Germany', 'India'];
            var select = $('select[name="negara"]');
            var current_val = select.data('current-val');
            fallback.forEach(function(c) {
                select.append(new Option(c, c, false, c === current_val));
            });
            select.select2({
                theme: 'bootstrap',
                width: '100%',
                placeholder: '-- Pilih Negara --',
                allowClear: true
            });
        }
    });

    // Initialize regular select2 elements
    if ($.isFunction($.fn.select2)) {
        $('.select2').select2({
            theme: 'bootstrap',
            width: '100%'
        });
    }

    // Initialize datepickers
    if ($.isFunction($.fn.datepicker)) {
        // Handled by data-plugin-datepicker markup
    }

    // Helper functions to remove row
    $(document).on('click', '.btn-remove-row', function() {
        var wrapper = $(this).closest('.dynamic-row').parent();
        $(this).closest('.dynamic-row').remove();
        if (wrapper.children().length === 0) {
            wrapper.html('<p class="text-muted no-rows-msg text-center my-4">Belum ada baris data.</p>');
        }
    });

    // 2. DYNAMIC ROW - INFO LAINNYA
    $('#btn-add-info-lainnya').click(function() {
        var html = `
            <div class="row info-lainnya-row mb-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="info_lainnya_key[]" placeholder="Nama Kolom (e.g. Info Warehouse)">
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" name="info_lainnya_value[]" placeholder="Isi Nilai/Keterangan">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-block btn-remove-info-row"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        `;
        $('#info-lainnya-wrapper').append(html);
    });
    $(document).on('click', '.btn-remove-info-row', function() {
        $(this).closest('.info-lainnya-row').remove();
    });

    // 3. DYNAMIC ROW - SERTIFIKASI
    $('#btn-add-sertifikasi').click(function() {
        var wrapper = $('#sertifikasi-wrapper');
        wrapper.find('.no-rows-msg').remove();

        var rowIdx = wrapper.find('.sertifikasi-row').length;

        var html = `
            <div class="dynamic-row sertifikasi-row" data-row-idx="${rowIdx}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                        <input type="text" class="form-control" name="sert_nama_dokumen[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                        <input type="text" class="form-control init-datepicker" name="sert_tanggal_dokumen[]">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted font-weight-bold">Masa Berlaku</label>
                        <input type="text" class="form-control init-datepicker" name="sert_masa_berlaku[]">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted font-weight-bold">Link Dokumen</label>
                        <input type="url" class="form-control" name="sert_link_dokumen[]" placeholder="https://drive.google.com/...">
                    </div>
                    <div class="col-md-1 text-right">
                        <label class="small d-block text-white">Action</label>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <label class="small font-weight-bold text-dark"><i class="fas fa-plus-circle"></i> Kolom Dokumen Lainnya (Dinamis)</label>
                        <div class="sert-custom-cols-container"></div>
                        <button type="button" class="btn btn-xs btn-default btn-add-custom-col mt-1" data-row-idx="${rowIdx}"><i class="fas fa-plus"></i> Tambah Kolom Lainnya</button>
                    </div>
                </div>
            </div>
        `;
        wrapper.append(html);
        
        // Re-initialize datepicker for new elements
        wrapper.find('.init-datepicker').datepicker({
            orientation: "bottom",
            format: "dd-mm-yyyy"
        }).removeClass('init-datepicker');
    });

    // Netted dynamic columns for Sertifikasi
    $(document).on('click', '.btn-add-custom-col', function() {
        var rowIdx = $(this).data('row-idx');
        var container = $(this).siblings('.sert-custom-cols-container');
        var colIdx = container.find('.custom-col-item').length;

        var html = `
            <div class="row custom-col-item mb-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control input-sm" name="sert_custom_key[${rowIdx}][${colIdx}]" placeholder="Kolom (e.g. Instansi Penerbit)">
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control input-sm" name="sert_custom_val[${rowIdx}][${colIdx}]" placeholder="Keterangan">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-xs btn-danger btn-remove-custom-col"><i class="fas fa-times"></i></button>
                </div>
            </div>
        `;
        container.append(html);
    });
    $(document).on('click', '.btn-remove-custom-col', function() {
        $(this).closest('.custom-col-item').remove();
    });

    // 4. DYNAMIC ROW - AGREEMENT
    $('#btn-add-agree').click(function() {
        var wrapper = $('#agree-wrapper');
        wrapper.find('.no-rows-msg').remove();

        var html = `
            <div class="dynamic-row">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                        <input type="text" class="form-control" name="agree_nama_dokumen[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                        <input type="text" class="form-control init-datepicker" name="agree_tanggal_dokumen[]">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted font-weight-bold">Masa Berlaku</label>
                        <input type="text" class="form-control init-datepicker" name="agree_masa_berlaku[]">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted font-weight-bold">Link Dokumen</label>
                        <input type="url" class="form-control" name="agree_link_dokumen[]" placeholder="https://drive.google.com/...">
                    </div>
                    <div class="col-md-1 text-right">
                        <label class="small d-block text-white">Action</label>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        wrapper.append(html);
        wrapper.find('.init-datepicker').datepicker({
            orientation: "bottom",
            format: "dd-mm-yyyy"
        }).removeClass('init-datepicker');
    });

    // 5. DYNAMIC ROW - BROCHURE
    $('#btn-add-brochure').click(function() {
        var wrapper = $('#brochure-wrapper');
        wrapper.find('.no-rows-msg').remove();

        var html = `
            <div class="dynamic-row">
                <div class="row">
                    <div class="col-md-4">
                        <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                        <input type="text" class="form-control" name="broch_nama_dokumen[]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                        <input type="text" class="form-control init-datepicker" name="broch_tanggal_dokumen[]">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted font-weight-bold">Link Dokumen</label>
                        <input type="url" class="form-control" name="broch_link_dokumen[]" placeholder="https://drive.google.com/...">
                    </div>
                    <div class="col-md-1 text-right">
                        <label class="small d-block text-white">Action</label>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        wrapper.append(html);
        wrapper.find('.init-datepicker').datepicker({
            orientation: "bottom",
            format: "dd-mm-yyyy"
        }).removeClass('init-datepicker');
    });

    // 6. DYNAMIC ROW - INFORMASI HARGA
    $('#btn-add-harga').click(function() {
        var wrapper = $('#harga-wrapper');
        wrapper.find('.no-rows-msg').remove();

        var html = `
            <div class="dynamic-row">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted font-weight-bold">Nama Dokumen</label>
                        <input type="text" class="form-control" name="harga_nama_dokumen[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted font-weight-bold">Tanggal Dokumen</label>
                        <input type="text" class="form-control init-datepicker" name="harga_tanggal_dokumen[]">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted font-weight-bold">Masa Berlaku</label>
                        <input type="text" class="form-control init-datepicker" name="harga_masa_berlaku[]">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted font-weight-bold">Link Dokumen</label>
                        <input type="url" class="form-control" name="harga_link_dokumen[]" placeholder="https://drive.google.com/...">
                    </div>
                    <div class="col-md-1 text-right">
                        <label class="small d-block text-white">Action</label>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        wrapper.append(html);
        wrapper.find('.init-datepicker').datepicker({
            orientation: "bottom",
            format: "dd-mm-yyyy"
        }).removeClass('init-datepicker');
    });

    // 7. AJAX FORM SUBMIT
    $('#form-detail-pemasok').submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var btn = $('#btn-save-pemasok');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'JSON',
            success: function(resp) {
                if (resp.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: resp.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = '<?= base_url("acc_pemasok") ?>';
                    });
                } else {
                    Swal.fire('Gagal!', resp.message, 'error');
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Pemasok');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error, 'error');
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Pemasok');
            }
        });
    });
});
</script>
