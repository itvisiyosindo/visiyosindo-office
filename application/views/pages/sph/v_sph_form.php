<!-- View: v_sph_form.php - Form Input/Edit/Duplikasi Surat Penawaran Harga -->

<?php
$isEdit = isset($mode) && $mode == 'edit';
$isDuplicate = isset($mode) && $mode == 'duplicate';
$hasData = $isEdit || $isDuplicate; // Kedua mode memiliki data SPH
$sph = isset($sph) ? $sph : null;
?>

<style>
    /* Card Styling */
    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px 10px 0 0 !important;
    }

    .card-header h6 {
        color: #fff !important;
    }

    .card-header .text-primary {
        color: #fff !important;
    }

    /* Table Styling */
    #tabelProduk {
        font-size: 13px;
    }

    #tabelProduk thead th {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: #fff;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        padding: 12px 8px;
        border: none;
    }

    #tabelProduk tbody td {
        vertical-align: middle;
        padding: 8px 6px;
    }

    .item-row {
        transition: all 0.2s ease;
    }

    .item-row:hover {
        background-color: #f0f7ff !important;
    }

    .item-row:nth-child(odd) {
        background-color: #fafbfc;
    }

    .item-row:nth-child(even) {
        background-color: #fff;
    }

    /* Form Controls */
    .form-control-sm {
        font-size: 12px;
        padding: 0.35rem 0.5rem;
    }

    .price-input {
        text-align: right;
        font-family: 'Consolas', monospace;
        font-weight: 600;
    }

    .select2-container--default .select2-selection--single {
        height: 32px;
        border-radius: 4px;
    }

    /* Responsive Table */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 1200px) {
        #tabelProduk {
            min-width: 1000px;
        }
    }

    /* Buttons */
    .btn-add-row,
    .btn-add-col {
        border-style: dashed;
        border-width: 2px;
        transition: all 0.3s ease;
    }

    .btn-add-row:hover,
    .btn-add-col:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-remove-row {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
    }

    /* Hidden item indicator */
    .item-row.item-hidden {
        opacity: 0.6;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%) !important;
        border-color: #ccc !important;
    }

    .item-row.item-hidden .badge-primary {
        background-color: #999 !important;
    }

    /* Sections */
    .cicilan-section,
    .tempo-section {
        display: none;
    }

    .keterangan-item {
        padding: 10px 15px;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .keterangan-item:hover {
        background-color: #f0f7ff;
        border-color: #4facfe;
    }

    /* Produk Mode Toggle */
    .produk-mode-toggle .btn-group .btn {
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 20px !important;
        margin-right: 4px;
    }

    .produk-mode-toggle .btn-group .btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-color: #667eea;
    }

    .produk-manual-input {
        border: 2px dashed #667eea;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    .produk-manual-input:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* Summary Card */
    .summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 10px;
        padding: 15px;
    }

    .summary-card .label {
        opacity: 0.8;
        font-size: 12px;
    }

    .summary-card .value {
        font-size: 18px;
        font-weight: bold;
    }

    /* Footer Total */
    #tabelProduk tfoot td {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-weight: 700;
        font-size: 14px;
    }

    /* Select2 adjustments */
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
        font-size: 12px;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .item-row {
        animation: fadeIn 0.3s ease;
    }

    /* Duplicate mode badge */
    .badge-duplicate {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 12px;
        padding: 5px 10px;
    }
</style>

<!-- Page Header -->
<header class="page-header">
    <h2>
        <i class="fas fa-file-invoice-dollar text-primary"></i>&nbsp;
        <?php if ($isDuplicate): ?>
            Duplikasi Surat Penawaran Harga
            <span class="badge badge-duplicate ml-2"><i class="fas fa-copy"></i> Mode Duplikasi</span>
        <?php elseif ($isEdit): ?>
            Edit Surat Penawaran Harga
        <?php else: ?>
            Buat Surat Penawaran Harga
        <?php endif; ?>
    </h2>
    <div class="right-wrapper text-right">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('sph') ?>"><span>SPH</span></a></li>
            <li><span><?= $isDuplicate ? 'Duplikasi' : ($isEdit ? 'Edit' : 'Buat Baru') ?></span></li>
        </ol>
    </div>
</header>

<?php if ($isDuplicate): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle"></i>
        <strong>Mode Duplikasi:</strong> Data dari SPH sebelumnya akan disalin. Anda dapat mengubah data sesuai kebutuhan. Setelah submit, akan menjadi <strong>SPH baru</strong> dengan nomor surat baru.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<form id="formSph" method="post">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= encrypt($sph->id) ?>">
    <?php endif; ?>
    <!-- Hidden inputs untuk kolom dinamis yang sudah ada (edit atau duplicate mode) -->
    <?php if ($hasData && !empty($sph->dynamic_columns)): ?>
        <?php foreach ($sph->dynamic_columns as $col): ?>
            <input type="hidden" name="dynamic_columns[existing_<?= $col->id ?>]" value="<?= htmlspecialchars($col->nama_kolom) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="row">
        <!-- Kolom Kiri: Info Surat -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-alt mr-2"></i>Informasi Surat
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Divisi Visilab Toggle -->
                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_visilab" name="is_visilab" value="1"
                                <?= ($hasData && !empty($sph->is_visilab)) ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-bold" for="is_visilab">
                                <i class="fas fa-flask text-info mr-1"></i> SPH Divisi Visilab
                            </label>
                            <small class="form-text text-muted">Jika diaktifkan, nomor surat dapat diketik manual (penomoran berbeda untuk Visilab)</small>
                        </div>
                    </div>

                    <!-- Nomor & Tanggal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-hashtag text-muted mr-1"></i>Nomor Surat</label>
                                <input type="text" class="form-control font-weight-bold text-primary" id="nomor_surat" name="nomor_surat_manual"
                                    value="<?= $isEdit ? $sph->nomor_surat : ($isDuplicate ? '(Akan digenerate otomatis)' : ((!empty($sph->is_visilab) || false) ? ($nomor_surat_visilab ?? '') : ($nomor_surat ?? ''))) ?>"
                                    readonly
                                    style="background-color: #e9ecef; letter-spacing: 0.5px; <?= ($hasData && !empty($sph->is_visilab)) ? 'border-color: #17a2b8;' : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-calendar-alt text-muted mr-1"></i>Tanggal Surat <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_surat" id="tanggal_surat"
                                    value="<?= $hasData ? $sph->tanggal_surat : date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Kota & Hal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-map-marker-alt text-muted mr-1"></i>Kota <span class="text-danger">*</span></label>
                                <select class="form-control" name="kota" required>
                                    <option value="Pekanbaru" <?= ($hasData && $sph->kota == 'Pekanbaru') ? 'selected' : '' ?>>Pekanbaru</option>
                                    <option value="Yogyakarta" <?= ($hasData && $sph->kota == 'Yogyakarta') ? 'selected' : '' ?>>Yogyakarta</option>
                                    <option value="Jakarta" <?= ($hasData && $sph->kota == 'Jakarta') ? 'selected' : '' ?>>Jakarta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-tag text-muted mr-1"></i>Hal</label>
                                <input type="text" class="form-control" name="hal"
                                    value="<?= $hasData ? $sph->hal : 'Penawaran Harga' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TTD -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-signature mr-2"></i>Tanda Tangan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama</label>
                                <input type="text" class="form-control" name="nama_ttd"
                                    value="<?= $hasData ? $sph->nama_ttd : 'Yolanda Pratiwi, S.Pd' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan_ttd"
                                    value="<?= $hasData ? $sph->jabatan_ttd : 'General Manager' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Penerima -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building mr-2"></i>Kepada Yth.
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Sapaan -->
                    <div class="form-group">
                        <label class="font-weight-bold">Sapaan <span class="text-danger">*</span></label>
                        <select class="form-control" name="sapaan" required>
                            <option value="Direktur" <?= ($hasData && $sph->sapaan == 'Direktur') ? 'selected' : '' ?>>Direktur</option>
                            <option value="Pimpinan" <?= ($hasData && $sph->sapaan == 'Pimpinan') ? 'selected' : '' ?>>Pimpinan</option>
                            <option value="Bapak/Ibu" <?= ($hasData && $sph->sapaan == 'Bapak/Ibu') ? 'selected' : '' ?>>Bapak/Ibu</option>
                            <option value="Bapak" <?= ($hasData && $sph->sapaan == 'Bapak') ? 'selected' : '' ?>>Bapak</option>
                            <option value="Ibu" <?= ($hasData && $sph->sapaan == 'Ibu') ? 'selected' : '' ?>>Ibu</option>
                        </select>
                    </div>

                    <!-- Tipe Penerima -->
                    <div class="form-group">
                        <label class="font-weight-bold">Tipe Customer <span class="text-danger">*</span></label>
                        <select class="form-control" name="tipe_penerima" id="tipe_penerima" required>
                            <option value="pelanggan" <?= ($hasData && $sph->tipe_penerima == 'pelanggan') ? 'selected' : '' ?>>Customer (Pelanggan)</option>
                            <option value="calon_pelanggan" <?= ($hasData && $sph->tipe_penerima == 'calon_pelanggan') ? 'selected' : '' ?>>Calon Customer</option>
                        </select>
                    </div>

                    <!-- Pilih Customer -->
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Instansi <span class="text-danger">*</span></label>
                        <select class="form-control select2-penerima" name="penerima_id" id="penerima_id" required>
                            <?php if ($hasData && $sph->penerima_id): ?>
                                <option value="<?= $sph->penerima_id ?>" selected><?= $sph->nama_penerima ?></option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label class="font-weight-bold">Alamat</label>
                        <textarea class="form-control" name="alamat_penerima" id="alamat_penerima" rows="3" readonly
                            style="background-color: #e9ecef;"><?= $hasData ? $sph->alamat_penerima : '' ?></textarea>
                    </div>

                    <!-- Kalkulator Cek Estimasi Pengiriman (Self-Service Marketing) -->
                    <div class="card mb-2" style="border: 1px dashed #4e73df; background: #f8faff; border-radius: 8px;">
                        <div class="card-header py-2 px-3 bg-light d-flex justify-content-between align-items-center" style="cursor: pointer;" data-toggle="collapse" data-target="#boxCekOngkir" aria-expanded="true">
                            <span class="font-weight-bold text-primary" style="font-size: 13px;">
                                <i class="fas fa-shipping-fast mr-1"></i> Cek Estimasi Pengiriman (ONS & Reguler)
                            </span>
                            <span class="badge badge-primary"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div id="boxCekOngkir" class="collapse show">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="small font-weight-bold mb-1"><i class="fas fa-building text-primary mr-1"></i> Asal Kantor Kirim:</label>
                                        <select class="form-control form-control-sm" id="calc_asal_kantor">
                                            <option value="pekanbaru" selected>Kantor Pekanbaru</option>
                                            <option value="jakarta">Kantor Jakarta</option>
                                            <option value="jogja">Kantor Jogja (Yogyakarta)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 col-12 mb-2">
                                        <label class="small font-weight-bold mb-1"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Wilayah / Kota Tujuan Pelanggan:</label>
                                        <select class="form-control form-control-sm" id="calc_wilayah_tujuan">
                                            <option value="">-- Pilih Wilayah / Kota Tujuan --</option>
                                            <option value="lokal_riau">Riau / Pekanbaru & Sekitarnya</option>
                                            <option value="jabodetabek">Jabodetabek</option>
                                            <option value="jogja_solo_jateng">DIY & Jawa Tengah (Jogja, Solo, Semarang)</option>
                                            <option value="jawa_timur">Jawa Timur (Surabaya, Malang, Sidoarjo)</option>
                                            <option value="jawa_barat">Jawa Barat & Banten (Bandung, dsb)</option>
                                            <option value="sumatera_kota">Sumatera (Kota Besar: Medan, Padang, Batam, Palembang)</option>
                                            <option value="sumatera_kab">Sumatera (Kabupaten / Daerah Lainnya)</option>
                                            <option value="kalimantan">Kalimantan</option>
                                            <option value="sulawesi">Sulawesi</option>
                                            <option value="bali_nusa">Bali, NTB, NTT</option>
                                            <option value="indonesia_timur">Maluku & Papua</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12 mb-2">
                                        <label class="small font-weight-bold mb-1"><i class="fas fa-weight-hanging text-muted mr-1"></i> Est. Berat (Kg):</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="calc_berat" value="1" min="1" step="0.5">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="btnHitungOngkir">
                                                    <i class="fas fa-search mr-1"></i> Cek
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hasil Estimasi -->
                                <div id="hasilEstimasiBox" class="mt-2" style="display: none;">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered bg-white mb-2" style="font-size: 12px;">
                                            <thead class="thead-light">
                                                <tr class="text-center">
                                                    <th>Layanan</th>
                                                    <th>Estimasi Sampai</th>
                                                    <th>Perkiraan Tarif / Kg</th>
                                                    <th>Total Est. Biaya</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="row_ons" class="table-info">
                                                    <td><strong><i class="fas fa-bolt text-warning mr-1"></i> ONS (Over Night)</strong></td>
                                                    <td class="text-center font-weight-bold text-success" id="est_hari_ons">1 Hari Kerja</td>
                                                    <td class="text-right" id="tarif_ons">-</td>
                                                    <td class="text-right font-weight-bold" id="total_ons">-</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-outline-info btn-salin-ket" data-layanan="ONS" title="Tambahkan ke Catatan SPH">
                                                            <i class="fas fa-plus"></i> Ke Keterangan
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr id="row_reguler">
                                                    <td><strong><i class="fas fa-truck text-primary mr-1"></i> REGULER</strong></td>
                                                    <td class="text-center font-weight-bold text-primary" id="est_hari_reguler">2 - 3 Hari Kerja</td>
                                                    <td class="text-right" id="tarif_reguler">-</td>
                                                    <td class="text-right font-weight-bold" id="total_reguler">-</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-outline-primary btn-salin-ket" data-layanan="Reguler" title="Tambahkan ke Catatan SPH">
                                                            <i class="fas fa-plus"></i> Ke Keterangan
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small class="text-muted font-italic">
                                        * Estimasi acuan standar ekspedisi rekanan VYM (JNE/TIKI/SiCepat). Klik <strong>"Ke Keterangan"</strong> untuk otomatis mencantumkan syarat pengiriman di surat SPH.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Produk -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-shopping-cart mr-2"></i>Daftar Produk Penawaran
                </h6>
                <div class="btn-group mt-2 mt-md-0">
                    <button type="button" class="btn btn-success btn-sm btn-add-row" id="btnAddRow">
                        <i class="fas fa-plus mr-1"></i> Tambah Baris
                    </button>
                    <button type="button" class="btn btn-warning btn-sm btn-add-col" id="btnAddCol">
                        <i class="fas fa-columns mr-1"></i> Tambah Kolom
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <!-- Konfigurasi Kolom yang Ditampilkan saat Cetak -->
            <div class="card mb-3" style="border: 1px dashed #6c757d; background: #f8f9fa;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center flex-wrap">
                        <span class="font-weight-bold text-secondary mr-3" style="font-size: 13px;">
                            <i class="fas fa-eye-slash mr-1"></i> Sembunyikan Kolom saat Cetak:
                        </span>
                        <div class="d-flex flex-wrap align-items-center" id="globalHideContainer" style="gap: 15px;">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input global-hide-col" id="globalHide_harga_pricelist" data-field="harga_pricelist">
                                <label class="custom-control-label small" for="globalHide_harga_pricelist">Harga Pricelist</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input global-hide-col" id="globalHide_qty" data-field="qty">
                                <label class="custom-control-label small" for="globalHide_qty">Qty</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input global-hide-col" id="globalHide_diskon" data-field="diskon">
                                <label class="custom-control-label small" for="globalHide_diskon">Diskon</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input global-hide-col" id="globalHide_harga_penawaran" data-field="harga_penawaran">
                                <label class="custom-control-label small" for="globalHide_harga_penawaran">Harga Penawaran</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input global-hide-col" id="globalHide_subtotal" data-field="subtotal">
                                <label class="custom-control-label small" for="globalHide_subtotal">Subtotal</label>
                            </div>
                            <!-- Container untuk kolom dinamis -->
                            <div id="dynamicColHideContainer" class="d-flex flex-wrap align-items-center" style="gap: 15px;">
                                <?php if ($hasData && !empty($sph->dynamic_columns)): ?>
                                    <?php foreach ($sph->dynamic_columns as $col): ?>
                                        <div class="custom-control custom-checkbox dynamic-col-hide-item" data-col="existing_<?= $col->id ?>">
                                            <input type="checkbox" class="custom-control-input global-hide-col" id="globalHide_dyn_existing_<?= $col->id ?>" data-field="dyn_existing_<?= $col->id ?>">
                                            <label class="custom-control-label small text-info" for="globalHide_dyn_existing_<?= $col->id ?>"><i class="fas fa-link mr-1"></i><?= htmlspecialchars($col->nama_kolom) ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hidden input untuk menyimpan konfigurasi global -->
            <input type="hidden" name="global_hidden_columns" id="globalHiddenColumns" value="[]">

            <!-- Info Tips -->
            <div class="alert alert-info py-2 px-3 mb-3" style="font-size: 12px; border-radius: 10px;">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Tips:</strong> Ketik nama produk untuk mencari. Centang kolom di atas untuk menyembunyikan pada semua item saat cetak.
            </div>

            <!-- Container untuk item cards -->
            <div id="itemsBody">
                <?php if ($hasData && !empty($sph->items)): ?>
                    <?php foreach ($sph->items as $idx => $item): ?>
                        <div class="item-row card mb-3" data-index="<?= $idx ?>" style="border: 1px solid #e0e0e0; border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <div class="card-body p-3">
                                <!-- Header Row dengan No dan Delete -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge badge-primary row-number" style="font-size: 14px; padding: 8px 15px; border-radius: 20px;">
                                        <i class="fas fa-box mr-1"></i> Item #<?= $idx + 1 ?>
                                    </span>
                                    <div class="d-flex align-items-center">
                                        <div class="custom-control custom-checkbox mr-3" title="Sembunyikan item ini saat cetak">
                                            <input type="checkbox" class="custom-control-input item-hide-print"
                                                id="hideItem_<?= $idx ?>" name="items[<?= $idx ?>][hide_on_print]"
                                                value="1" <?= !empty($item->hide_on_print) ? 'checked' : '' ?>>
                                            <label class="custom-control-label text-muted small" for="hideItem_<?= $idx ?>">
                                                <i class="fas fa-eye-slash mr-1"></i> Sembunyikan saat cetak
                                            </label>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-remove-row" style="border-radius: 50%; width: 40px; height: 40px; padding: 0;" title="Hapus item">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Grid Layout 3 kolom -->
                                <div class="row">
                                    <!-- Produk - Full Width -->
                                    <div class="col-12 mb-3">
                                        <label class="small font-weight-bold text-primary mb-1"><i class="fas fa-cube mr-1"></i> Produk <span class="text-danger">*</span></label>
                                        <div class="produk-mode-toggle mb-1" style="<?= $item->jenis_harga == 'free' ? '' : 'display:none;' ?>">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary btn-mode-search <?= ($item->jenis_harga == 'free' && empty($item->pricelist_id)) ? '' : 'active' ?>" title="Cari dari database">
                                                    <i class="fas fa-search mr-1"></i> Cari Produk
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-mode-manual <?= ($item->jenis_harga == 'free' && empty($item->pricelist_id)) ? 'active' : '' ?>" title="Ketik manual">
                                                    <i class="fas fa-keyboard mr-1"></i> Input Manual
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="items[<?= $idx ?>][pricelist_id]" value="<?= $item->pricelist_id ?>">
                                        <div class="produk-search-wrapper" style="<?= ($item->jenis_harga == 'free' && empty($item->pricelist_id)) ? 'display:none;' : '' ?>">
                                            <select class="form-control select2-produk" name="items[<?= $idx ?>][deskripsi_select]">
                                                <option value="<?= $item->pricelist_id ?>" selected><?= $item->deskripsi ?></option>
                                            </select>
                                        </div>
                                        <div class="produk-manual-wrapper" style="<?= ($item->jenis_harga == 'free' && empty($item->pricelist_id)) ? '' : 'display:none;' ?>">
                                            <input type="text" class="form-control produk-manual-input" placeholder="Ketik nama produk manual..." value="<?= ($item->jenis_harga == 'free' && empty($item->pricelist_id)) ? htmlspecialchars($item->deskripsi) : '' ?>">
                                        </div>
                                        <input type="hidden" name="items[<?= $idx ?>][deskripsi]" value="<?= htmlspecialchars($item->deskripsi) ?>">
                                    </div>

                                    <!-- Row 1: Jenis, Harga, Qty -->
                                    <div class="col-md-4 col-6 mb-3">
                                        <label class="small font-weight-bold text-secondary mb-1"><i class="fas fa-tag mr-1"></i> Jenis</label>
                                        <select class="form-control jenis-harga" name="items[<?= $idx ?>][jenis_harga]">
                                            <option value="regular" <?= $item->jenis_harga == 'regular' ? 'selected' : '' ?>>Regular</option>
                                            <option value="e_catalog" <?= $item->jenis_harga == 'e_catalog' ? 'selected' : '' ?>>E-Catalog</option>
                                            <option value="free" <?= $item->jenis_harga == 'free' ? 'selected' : '' ?>>Free</option>
                                        </select>
                                    </div>
                                    <?php
                                    $hiddenFields = [];
                                    if (!empty($item->hidden_fields)) {
                                        $hiddenFields = json_decode($item->hidden_fields, true) ?: [];
                                    }
                                    ?>
                                    <div class="col-md-4 col-6 mb-3">
                                        <label class="small font-weight-bold text-secondary mb-1">
                                            <i class="fas fa-money-bill mr-1"></i> Harga
                                        </label>
                                        <input type="text" class="form-control price-input harga-pricelist"
                                            name="items[<?= $idx ?>][harga_pricelist]"
                                            value="<?= number_format($item->harga_pricelist, 0, ',', '.') ?>" placeholder="0">
                                    </div>
                                    <div class="col-md-4 col-6 mb-3">
                                        <label class="small font-weight-bold text-secondary mb-1">
                                            <i class="fas fa-sort-numeric-up mr-1"></i> Qty
                                        </label>
                                        <input type="number" class="form-control qty text-center"
                                            name="items[<?= $idx ?>][qty]" value="<?= $item->qty ?>" min="1">
                                    </div>

                                    <!-- Row 2: Diskon, Harga Penawaran, Subtotal -->
                                    <div class="col-md-4 col-6 mb-2">
                                        <label class="small font-weight-bold text-secondary mb-1">
                                            <i class="fas fa-percent mr-1"></i> Diskon
                                        </label>
                                        <?php
                                        // Fix: Format nilai diskon dengan benar
                                        $diskonPersenInt = intval(floatval($item->diskon_persen)); // 5.00 -> 5
                                        $diskonNominalInt = intval(floatval($item->diskon_nominal)); // 50000.00 -> 50000
                                        $diskonDisplayValue = $item->tipe_diskon == 'persen' ? $diskonPersenInt : number_format($diskonNominalInt, 0, ',', '.');
                                        ?>
                                        <div class="input-group">
                                            <input type="text" class="form-control diskon-value"
                                                name="items[<?= $idx ?>][diskon_value]"
                                                value="<?= $diskonDisplayValue ?>">
                                            <div class="input-group-append">
                                                <select class="form-control tipe-diskon" name="items[<?= $idx ?>][tipe_diskon]" style="border-radius: 0 4px 4px 0;">
                                                    <option value="none" <?= $item->tipe_diskon == 'none' ? 'selected' : '' ?>>-</option>
                                                    <option value="persen" <?= $item->tipe_diskon == 'persen' ? 'selected' : '' ?>>%</option>
                                                    <option value="nominal" <?= $item->tipe_diskon == 'nominal' ? 'selected' : '' ?>>Rp</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="items[<?= $idx ?>][diskon_persen]" value="<?= $diskonPersenInt ?>">
                                        <input type="hidden" name="items[<?= $idx ?>][diskon_nominal]" value="<?= $diskonNominalInt ?>">
                                        <input type="hidden" name="items[<?= $idx ?>][tampilkan_diskon]" value="1">
                                    </div>
                                    <div class="col-md-4 col-6 mb-2">
                                        <label class="small font-weight-bold text-success mb-1">
                                            <i class="fas fa-hand-holding-usd mr-1"></i> Hrg Penawaran
                                        </label>
                                        <input type="text" class="form-control price-input harga-penawaran"
                                            name="items[<?= $idx ?>][harga_penawaran]"
                                            value="<?= number_format($item->harga_penawaran, 0, ',', '.') ?>" readonly
                                            style="background: #e8f5e9; font-weight: bold;">
                                    </div>
                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="small font-weight-bold text-warning mb-1">
                                            <i class="fas fa-calculator mr-1"></i> Subtotal
                                        </label>
                                        <input type="text" class="form-control price-input subtotal font-weight-bold"
                                            name="items[<?= $idx ?>][subtotal]"
                                            value="<?= number_format($item->subtotal, 0, ',', '.') ?>" readonly
                                            style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); font-size: 16px; color: #e65100;">
                                    </div>
                                    <!-- Hidden input untuk menyimpan field yang disembunyikan -->
                                    <input type="hidden" class="hidden-fields-input" name="items[<?= $idx ?>][hidden_fields]" value='<?= htmlspecialchars($item->hidden_fields ?? '[]') ?>'>
                                </div>

                                <!-- Dynamic columns container - Load existing dynamic columns -->
                                <div class="dynamic-cols-container row">
                                    <?php if (!empty($sph->dynamic_columns)): ?>
                                        <?php foreach ($sph->dynamic_columns as $col): ?>
                                            <?php
                                            // Cari nilai untuk kolom ini (jika ada)
                                            $colValue = '';
                                            if (!empty($item->dynamic_values)) {
                                                foreach ($item->dynamic_values as $dv) {
                                                    if ($dv->sph_column_id == $col->id) {
                                                        $colValue = $dv->nilai;
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="col-md-4 col-6 mb-2 dynamic-col-cell" data-col="existing_<?= $col->id ?>">
                                                <label class="small font-weight-bold text-info mb-1">
                                                    <i class="fas fa-link mr-1"></i><?= htmlspecialchars($col->nama_kolom) ?>
                                                </label>
                                                <input type="text" class="form-control"
                                                    name="items[<?= $idx ?>][dynamic_values][existing_<?= $col->id ?>]"
                                                    value="<?= htmlspecialchars($colValue) ?>" placeholder="Masukkan <?= htmlspecialchars($col->nama_kolom) ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default empty row - Card Layout -->
                    <div class="item-row card mb-3" data-index="0" style="border: 1px solid #e0e0e0; border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                        <div class="card-body p-3">
                            <!-- Header Row dengan No dan Delete -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-primary row-number" style="font-size: 14px; padding: 8px 15px; border-radius: 20px;">
                                    <i class="fas fa-box mr-1"></i> Item #1
                                </span>
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-checkbox mr-3" title="Sembunyikan item ini saat cetak">
                                        <input type="checkbox" class="custom-control-input item-hide-print"
                                            id="hideItem_0" name="items[0][hide_on_print]" value="1">
                                        <label class="custom-control-label text-muted small" for="hideItem_0">
                                            <i class="fas fa-eye-slash mr-1"></i> Sembunyikan saat cetak
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-remove-row" style="border-radius: 50%; width: 40px; height: 40px; padding: 0;" title="Hapus item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Grid Layout 3 kolom -->
                            <div class="row">
                                <!-- Produk - Full Width -->
                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-primary mb-1"><i class="fas fa-cube mr-1"></i> Produk <span class="text-danger">*</span></label>
                                    <div class="produk-mode-toggle mb-1" style="display:none;">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-mode-search active" title="Cari dari database">
                                                <i class="fas fa-search mr-1"></i> Cari Produk
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-mode-manual" title="Ketik manual">
                                                <i class="fas fa-keyboard mr-1"></i> Input Manual
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="items[0][pricelist_id]" value="">
                                    <div class="produk-search-wrapper">
                                        <select class="form-control select2-produk" name="items[0][deskripsi_select]">
                                            <option value="">-- Cari Produk --</option>
                                        </select>
                                    </div>
                                    <div class="produk-manual-wrapper" style="display:none;">
                                        <input type="text" class="form-control produk-manual-input" placeholder="Ketik nama produk manual..." value="">
                                    </div>
                                    <input type="hidden" name="items[0][deskripsi]" value="">
                                </div>

                                <!-- Row 1: Jenis, Harga, Qty -->
                                <div class="col-md-4 col-6 mb-3">
                                    <label class="small font-weight-bold text-secondary mb-1"><i class="fas fa-tag mr-1"></i> Jenis</label>
                                    <select class="form-control jenis-harga" name="items[0][jenis_harga]">
                                        <option value="regular">Regular</option>
                                        <option value="e_catalog">E-Catalog</option>
                                        <option value="free">Free</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-6 mb-3">
                                    <label class="small font-weight-bold text-secondary mb-1">
                                        <i class="fas fa-money-bill mr-1"></i> Harga
                                    </label>
                                    <input type="text" class="form-control price-input harga-pricelist"
                                        name="items[0][harga_pricelist]" value="0" placeholder="0">
                                </div>
                                <div class="col-md-4 col-6 mb-3">
                                    <label class="small font-weight-bold text-secondary mb-1">
                                        <i class="fas fa-sort-numeric-up mr-1"></i> Qty
                                    </label>
                                    <input type="number" class="form-control qty text-center"
                                        name="items[0][qty]" value="1" min="1">
                                </div>

                                <!-- Row 2: Diskon, Harga Penawaran, Subtotal -->
                                <div class="col-md-4 col-6 mb-2">
                                    <label class="small font-weight-bold text-secondary mb-1">
                                        <i class="fas fa-percent mr-1"></i> Diskon
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control diskon-value" name="items[0][diskon_value]" value="0">
                                        <div class="input-group-append">
                                            <select class="form-control tipe-diskon" name="items[0][tipe_diskon]" style="border-radius: 0 4px 4px 0;">
                                                <option value="none">-</option>
                                                <option value="persen">%</option>
                                                <option value="nominal">Rp</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="items[0][diskon_persen]" value="0">
                                    <input type="hidden" name="items[0][diskon_nominal]" value="0">
                                    <input type="hidden" name="items[0][tampilkan_diskon]" value="1">
                                </div>
                                <div class="col-md-4 col-6 mb-2">
                                    <label class="small font-weight-bold text-success mb-1">
                                        <i class="fas fa-hand-holding-usd mr-1"></i> Hrg Penawaran
                                    </label>
                                    <input type="text" class="form-control price-input harga-penawaran"
                                        name="items[0][harga_penawaran]" value="0" readonly
                                        style="background: #e8f5e9; font-weight: bold;">
                                </div>
                                <div class="col-md-4 col-12 mb-2">
                                    <label class="small font-weight-bold text-warning mb-1">
                                        <i class="fas fa-calculator mr-1"></i> Subtotal
                                    </label>
                                    <input type="text" class="form-control price-input subtotal font-weight-bold"
                                        name="items[0][subtotal]" value="0" readonly
                                        style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); font-size: 16px; color: #e65100;">
                                </div>
                                <!-- Hidden input untuk menyimpan field yang disembunyikan -->
                                <input type="hidden" class="hidden-fields-input" name="items[0][hidden_fields]" value="[]">
                            </div>

                            <!-- Dynamic columns container -->
                            <div class="dynamic-cols-container row"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Grand Total Card -->
            <div class="card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="text-white mb-0">
                                <i class="fas fa-calculator mr-2"></i>GRAND TOTAL
                            </h5>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control form-control-lg price-input text-right"
                                id="grandTotal" value="0" readonly
                                style="font-weight: bold; font-size: 20px; background: #fff; color: #667eea; border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-2 px-2">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="tampilkan_total"
                        name="tampilkan_total" value="1" <?= (!$hasData || $sph->tampilkan_total) ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="tampilkan_total">Tampilkan Total Harga saat Cetak</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sistem Pembayaran -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-credit-card mr-2"></i>Sistem Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Payment Type Selection -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="font-weight-bold mb-2"><i class="fas fa-hand-pointer text-muted mr-1"></i>Pilih Sistem</label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-outline-success flex-fill <?= (!$hasData || $sph->sistem_pembayaran == 'cash') ? 'active' : '' ?>">
                                    <input type="radio" name="sistem_pembayaran" id="sistem_cash" value="cash"
                                        <?= (!$hasData || $sph->sistem_pembayaran == 'cash') ? 'checked' : '' ?>>
                                    <i class="fas fa-money-bill-wave mr-1"></i> Cash
                                </label>
                                <label class="btn btn-outline-warning flex-fill <?= ($hasData && $sph->sistem_pembayaran == 'tempo') ? 'active' : '' ?>">
                                    <input type="radio" name="sistem_pembayaran" id="sistem_tempo" value="tempo"
                                        <?= ($hasData && $sph->sistem_pembayaran == 'tempo') ? 'checked' : '' ?>>
                                    <i class="fas fa-clock mr-1"></i> Tempo
                                </label>
                                <label class="btn btn-outline-info flex-fill <?= ($hasData && $sph->sistem_pembayaran == 'cicilan') ? 'active' : '' ?>">
                                    <input type="radio" name="sistem_pembayaran" id="sistem_cicilan" value="cicilan"
                                        <?= ($hasData && $sph->sistem_pembayaran == 'cicilan') ? 'checked' : '' ?>>
                                    <i class="fas fa-calendar-alt mr-1"></i> Cicilan
                                </label>
                                <label class="btn btn-outline-primary flex-fill <?= ($hasData && $sph->sistem_pembayaran == 'custom') ? 'active' : '' ?>">
                                    <input type="radio" name="sistem_pembayaran" id="sistem_custom" value="custom"
                                        <?= ($hasData && $sph->sistem_pembayaran == 'custom') ? 'checked' : '' ?>>
                                    <i class="fas fa-sliders-h mr-1"></i> DP / Custom
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Payment Section -->
                    <div class="custom-payment-section p-3 rounded" id="customPaymentSection" style="background: #f3e5f5; border: 1px dashed #9c27b0; display: none;">
                        <h6 class="text-primary font-weight-bold mb-2"><i class="fas fa-sliders-h mr-1"></i> Teks Sistem Pembayaran</h6>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold small">Rincian Pembayaran (Tampil setelah kata "Pembayaran ")</label>
                            <input type="text" class="form-control" name="pembayaran_custom" id="pembayaran_custom"
                                value="<?= htmlspecialchars(($hasData && !empty($sph->pembayaran_custom)) ? $sph->pembayaran_custom : 'DP 50% + Pelunasan Setelah Instalasi 50%') ?>"
                                placeholder="Contoh: DP 50% + Pelunasan Setelah Instalasi 50%">
                        </div>
                    </div>

                    <!-- Tempo Section -->
                    <div class="tempo-section p-3 rounded" id="tempoSection" style="background: #fff8e1; border: 1px dashed #ffc107;">
                        <h6 class="text-warning font-weight-bold mb-3"><i class="fas fa-clock mr-1"></i> Pengaturan Tempo</h6>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Jangka Waktu</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="tempo_hari" id="tempo_hari"
                                    value="<?= $hasData ? $sph->tempo_hari : '30' ?>" min="1">
                                <div class="input-group-append">
                                    <span class="input-group-text">Hari</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cicilan Section -->
                    <div class="cicilan-section p-3 rounded" id="cicilanSection" style="background: #e3f2fd; border: 1px dashed #2196f3;">
                        <h6 class="text-info font-weight-bold mb-3"><i class="fas fa-calendar-alt mr-1"></i> Pengaturan Cicilan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">DP (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="dp_persen" id="dp_persen"
                                            value="<?= $hasData ? intval(floatval($sph->dp_persen)) : '40' ?>" min="0" max="100" step="1">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nominal DP</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control price-input" name="dp_nominal" id="dp_nominal"
                                            value="<?= $hasData ? number_format(intval(floatval($sph->dp_nominal)), 0, ',', '.') : '0' ?>" readonly
                                            style="background: #e8f5e9;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jumlah Bulan</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="cicilan_bulan" id="cicilan_bulan"
                                            value="<?= $hasData ? $sph->cicilan_bulan : '12' ?>" min="1">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bulan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Cicilan Per Bulan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control price-input font-weight-bold" name="cicilan_per_bulan" id="cicilan_per_bulan"
                                            value="<?= $hasData ? number_format(intval(floatval($sph->cicilan_per_bulan)), 0, ',', '.') : '0' ?>" readonly
                                            style="background: #fff3e0; color: #e65100;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clipboard-list mr-2"></i>Keterangan / Catatan
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle mr-1"></i>Centang keterangan yang akan ditampilkan pada surat:
                    </p>

                    <div class="row" style="max-height: 300px; overflow-y: auto;">
                        <?php
                        $selectedIds = [];
                        if ($hasData && !empty($sph->keterangan['selected'])) {
                            foreach ($sph->keterangan['selected'] as $sel) {
                                $selectedIds[] = $sel->keterangan_id;
                            }
                        }
                        ?>
                        <?php foreach ($keterangan_master as $ket): ?>
                            <div class="col-12">
                                <div class="keterangan-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="ket_<?= $ket->id ?>"
                                            name="keterangan_ids[]"
                                            value="<?= $ket->id ?>"
                                            <?= in_array($ket->id, $selectedIds) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="ket_<?= $ket->id ?>" style="font-size: 13px;">
                                            <?= $ket->keterangan ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Keterangan Custom -->
                    <hr>
                    <h6 class="font-weight-bold"><i class="fas fa-plus-circle text-success mr-1"></i>Keterangan Tambahan</h6>
                    <div id="keteranganCustomContainer">
                        <?php if ($hasData && !empty($sph->keterangan['custom'])): ?>
                            <?php foreach ($sph->keterangan['custom'] as $idx => $cust): ?>
                                <div class="input-group mb-2 keterangan-custom-row">
                                    <input type="text" class="form-control" name="keterangan_custom[]" value="<?= htmlspecialchars($cust->keterangan) ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger btn-remove-keterangan"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm mt-2" id="btnAddKeterangan">
                        <i class="fas fa-plus mr-1"></i> Tambah Keterangan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Rekening -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-university mr-2"></i>Informasi Rekening Perusahaan
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Jenis PPN -->
                    <div class="col-lg-12 mb-3">
                        <label class="font-weight-bold mb-2"><i class="fas fa-check-circle text-info mr-1"></i>Jenis Transaksi</label>
                        <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                            <label class="btn btn-outline-primary flex-fill <?= (!$hasData || $sph->jenis_ppn == 'ppn') ? 'active' : '' ?>">
                                <input type="radio" name="jenis_ppn" id="ppn_yes" value="ppn"
                                    <?= (!$hasData || $sph->jenis_ppn == 'ppn') ? 'checked' : '' ?>>
                                <i class="fas fa-receipt mr-1"></i> PPN Terlampir
                            </label>
                            <label class="btn btn-outline-warning flex-fill <?= ($hasData && $sph->jenis_ppn == 'non_ppn') ? 'active' : '' ?>">
                                <input type="radio" name="jenis_ppn" id="ppn_no" value="non_ppn"
                                    <?= ($hasData && $sph->jenis_ppn == 'non_ppn') ? 'checked' : '' ?>>
                                <i class="fas fa-times-circle mr-1"></i> Non-PPN
                            </label>
                        </div>
                        <small class="form-text text-muted d-block mt-2">Pilih jenis transaksi untuk menentukan rekening yang ditampilkan</small>
                    </div>

                    <!-- Norek Perusahaan (PPN) -->
                    <div class="col-lg-6 mb-3">
                        <div class="ppn-section p-3 rounded" style="background: #e3f2fd; border-left: 4px solid #2196f3;">
                            <label class="font-weight-bold mb-2" for="norek_perusahaan">
                                <i class="fas fa-bank text-primary mr-1"></i>No. Rekening Perusahaan (PPN)
                            </label>
                            <input type="text" class="form-control" id="norek_perusahaan" name="norek_perusahaan"
                                placeholder="Contoh: 123456789-001 - PT Bank Mandiri"
                                value="<?= $hasData ? htmlspecialchars($sph->norek_perusahaan) : '' ?>">
                            <small class="form-text text-muted d-block mt-2">Rekening yang digunakan untuk transaksi dengan PPN</small>
                        </div>
                    </div>

                    <!-- Norek A.N Pak Bob (Non-PPN) -->
                    <div class="col-lg-6 mb-3">
                        <div class="non-ppn-section p-3 rounded" style="background: #fff3e0; border-left: 4px solid #ff9800;">
                            <label class="font-weight-bold mb-2" for="norek_a_n_bob">
                                <i class="fas fa-user-tie text-warning mr-1"></i>No. Rekening A.N Pak Bob (Non-PPN)
                            </label>
                            <input type="text" class="form-control" id="norek_a_n_bob" name="norek_a_n_bob"
                                placeholder="Contoh: 098765432-002 - A.N Bob"
                                value="<?= $hasData ? htmlspecialchars($sph->norek_a_n_bob) : '' ?>">
                            <small class="form-text text-muted d-block mt-2">Rekening pribadi untuk transaksi tanpa PPN</small>
                        </div>
                    </div>

                    <!-- Preview Rekening Terpilih -->
                    <div class="col-lg-12">
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle mr-1"></i>Rekening yang akan ditampilkan:
                            </h6>
                            <p class="mb-0" id="norekTerpilihPreview">
                                <strong id="norekTerpilihValue">Silakan pilih jenis transaksi</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="card shadow mb-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <a href="<?= base_url('sph') ?>" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                    </a>
                </div>
                <div class="mt-2 mt-md-0">
                    <button type="button" class="btn btn-outline-info btn-lg mr-2" id="btnPreview">
                        <i class="fas fa-eye mr-1"></i> Preview
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" id="btnSimpan" style="min-width: 150px;">
                        <i class="fas fa-save mr-1"></i> Simpan SPH
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Tambah Kolom -->
<div class="modal fade" id="modalAddCol" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white"><i class="fas fa-columns mr-2"></i>Tambah Kolom Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold">Nama Kolom</label>
                    <input type="text" class="form-control" id="namaKolomBaru" placeholder="Contoh: Link E-Catalog, Min. Qty, Keterangan">
                    <small class="text-muted">Kolom ini akan ditambahkan di sebelah kanan tabel</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnKonfirmasiAddCol">
                    <i class="fas fa-plus mr-1"></i> Tambah Kolom
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Wait for jQuery to be available
    (function checkJQuery() {
        if (typeof jQuery === 'undefined') {
            setTimeout(checkJQuery, 50);
            return;
        }

        $(document).ready(function() {
            // Toastr fallback using PNotify (jika toastr tidak ada)
            if (typeof toastr === 'undefined') {
                window.toastr = {
                    success: function(msg, title) {
                        new PNotify({
                            title: title || 'Sukses',
                            text: msg,
                            type: 'success',
                            delay: 3000,
                            buttons: {
                                closer: true,
                                sticker: false
                            }
                        });
                    },
                    error: function(msg, title) {
                        new PNotify({
                            title: title || 'Error',
                            text: msg,
                            type: 'error',
                            delay: 4000,
                            buttons: {
                                closer: true,
                                sticker: false
                            }
                        });
                    },
                    warning: function(msg, title) {
                        new PNotify({
                            title: title || 'Peringatan',
                            text: msg,
                            type: 'warning',
                            delay: 3500,
                            buttons: {
                                closer: true,
                                sticker: false
                            }
                        });
                    },
                    info: function(msg, title) {
                        new PNotify({
                            title: title || 'Info',
                            text: msg,
                            type: 'info',
                            delay: 3000,
                            buttons: {
                                closer: true,
                                sticker: false
                            }
                        });
                    }
                };
            }

            // Toggle visual indicator untuk item yang disembunyikan saat cetak
            $(document).on('change', '.item-hide-print', function() {
                var $row = $(this).closest('.item-row');
                if ($(this).is(':checked')) {
                    $row.addClass('item-hidden');
                } else {
                    $row.removeClass('item-hidden');
                }
            });

            // Set initial state untuk checkbox yang sudah tercentang
            $('.item-hide-print:checked').each(function() {
                $(this).closest('.item-row').addClass('item-hidden');
            });

            // ============================================
            // GLOBAL HIDE COLUMNS CONFIGURATION
            // ============================================

            // Handler untuk checkbox global hide kolom
            $(document).on('change', '.global-hide-col', function() {
                var fieldName = $(this).data('field');
                var isHidden = $(this).is(':checked');

                // Update semua rows
                $('#itemsBody .item-row').each(function() {
                    var $row = $(this);
                    var $hiddenInput = $row.find('.hidden-fields-input');

                    // Parse existing hidden fields
                    var hiddenFields = [];
                    try {
                        hiddenFields = JSON.parse($hiddenInput.val()) || [];
                    } catch (e) {
                        hiddenFields = [];
                    }

                    var fieldIndex = hiddenFields.indexOf(fieldName);

                    if (isHidden) {
                        // Add to array jika belum ada
                        if (fieldIndex === -1) {
                            hiddenFields.push(fieldName);
                        }
                    } else {
                        // Remove from array jika ada
                        if (fieldIndex > -1) {
                            hiddenFields.splice(fieldIndex, 1);
                        }
                    }

                    // Update hidden input
                    $hiddenInput.val(JSON.stringify(hiddenFields));
                });

                // Update global hidden columns input
                updateGlobalHiddenColumnsInput();
            });

            // Function untuk update hidden input global
            function updateGlobalHiddenColumnsInput() {
                var globalHidden = [];
                $('.global-hide-col:checked').each(function() {
                    globalHidden.push($(this).data('field'));
                });
                $('#globalHiddenColumns').val(JSON.stringify(globalHidden));
            }

            // Function untuk apply global config ke row baru
            function applyGlobalHiddenToRow($row) {
                var $hiddenInput = $row.find('.hidden-fields-input');
                var hiddenFields = [];

                try {
                    hiddenFields = JSON.parse($hiddenInput.val()) || [];
                } catch (e) {
                    hiddenFields = [];
                }

                // Apply dari global checkboxes
                $('.global-hide-col:checked').each(function() {
                    var fieldName = $(this).data('field');
                    if (hiddenFields.indexOf(fieldName) === -1) {
                        hiddenFields.push(fieldName);
                    }
                });

                $hiddenInput.val(JSON.stringify(hiddenFields));
            }

            // Initialize: set checkbox state berdasarkan data existing
            function initGlobalHideCheckboxes() {
                // Hitung berapa kali setiap field muncul di hidden
                var fieldCounts = {};
                var totalRows = $('#itemsBody .item-row').length;

                $('#itemsBody .item-row').each(function() {
                    var $hiddenInput = $(this).find('.hidden-fields-input');
                    var hiddenFields = [];
                    try {
                        hiddenFields = JSON.parse($hiddenInput.val()) || [];
                    } catch (e) {
                        hiddenFields = [];
                    }

                    hiddenFields.forEach(function(field) {
                        fieldCounts[field] = (fieldCounts[field] || 0) + 1;
                    });
                });

                // Jika semua rows punya field yang di-hide, centang global checkbox
                $('.global-hide-col').each(function() {
                    var fieldName = $(this).data('field');
                    if (fieldCounts[fieldName] && fieldCounts[fieldName] === totalRows) {
                        $(this).prop('checked', true);
                    }
                });

                updateGlobalHiddenColumnsInput();
            }

            // Initialize saat load
            initGlobalHideCheckboxes();

            var rowIndex = <?= $hasData && !empty($sph->items) ? count($sph->items) : 1 ?>;
            var colIndex = <?= $hasData && !empty($sph->dynamic_columns) ? count($sph->dynamic_columns) : 0 ?>;

            // Inisialisasi kolom dinamis dari database (jika edit/duplicate mode)
            var dynamicColumns = [
                <?php if ($hasData && !empty($sph->dynamic_columns)): ?>
                    <?php foreach ($sph->dynamic_columns as $col): ?> {
                            id: 'existing_<?= $col->id ?>',
                            nama: '<?= addslashes($col->nama_kolom) ?>'
                        },
                    <?php endforeach; ?>
                <?php endif; ?>
            ];

            // Initialize Select2 untuk penerima
            initSelect2Penerima();

            // Initialize Select2 untuk produk yang sudah ada
            $('.select2-produk').each(function() {
                initSelect2Produk($(this));
            });

            // Toggle sistem pembayaran - untuk radio buttons
            toggleSistemPembayaran();
            $('input[name="sistem_pembayaran"]').on('change', toggleSistemPembayaran);

            function toggleSistemPembayaran() {
                var sistem = $('input[name="sistem_pembayaran"]:checked').val();
                $('.tempo-section, .cicilan-section, .custom-payment-section').hide();

                if (sistem == 'tempo') {
                    $('.tempo-section').slideDown(300);
                } else if (sistem == 'cicilan') {
                    $('.cicilan-section').slideDown(300);
                    hitungCicilan();
                } else if (sistem == 'custom') {
                    $('.custom-payment-section').slideDown(300);
                }
            }

            // Toggle jenis PPN dan update preview norek
            function updateNorekPreview() {
                var jenisPpn = $('input[name="jenis_ppn"]:checked').val();
                var norekPpn = $('#norek_perusahaan').val();
                var norekNonPpn = $('#norek_a_n_bob').val();
                var norekTerpilih = '';

                if (jenisPpn == 'ppn') {
                    norekTerpilih = norekPpn || 'Belum diisi';
                } else {
                    norekTerpilih = norekNonPpn || 'Belum diisi';
                }

                $('#norekTerpilihValue').text(norekTerpilih);
            }

            // Initialize norek preview
            updateNorekPreview();
            $('input[name="jenis_ppn"]').on('change', updateNorekPreview);
            $('#norek_perusahaan, #norek_a_n_bob').on('change keyup', updateNorekPreview);

            // Hitung cicilan
            function hitungCicilan() {
                var total = parseNumber($('#grandTotal').val());
                var dpPersen = parseFloat($('#dp_persen').val()) || 0;
                var bulan = parseInt($('#cicilan_bulan').val()) || 1;

                var dpNominal = total * dpPersen / 100;
                var sisaHarga = total - dpNominal;
                var cicilanPerBulan = bulan > 0 ? sisaHarga / bulan : 0;

                $('#dp_nominal').val(formatNumber(dpNominal));
                $('#cicilan_per_bulan').val(formatNumber(cicilanPerBulan));
            }

            $('#dp_persen, #cicilan_bulan').on('change keyup', hitungCicilan);

            // Initialize Select2 untuk penerima
            function initSelect2Penerima() {
                $('#penerima_id').select2({
                    ajax: {
                        url: '<?= base_url('sph/search_pelanggan') ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                tipe: $('#tipe_penerima').val()
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results
                            };
                        }
                    },
                    minimumInputLength: 2,
                    placeholder: 'Ketik untuk mencari...',
                    allowClear: true
                });

                // On select, fill alamat
                $('#penerima_id').on('select2:select', function(e) {
                    var data = e.params.data;
                    var alamat = data.alamat || '';
                    if (data.kota) alamat += (alamat ? ', ' : '') + data.kota;
                    if (data.provinsi) alamat += (alamat ? ', ' : '') + data.provinsi;
                    $('#alamat_penerima').val(alamat);

                    // Otomatis deteksi wilayah tujuan untuk kalkulator pengiriman
                    var teksAlamat = (alamat + ' ' + (data.kota || '') + ' ' + (data.provinsi || '')).toLowerCase();
                    if (teksAlamat.indexOf('pekanbaru') !== -1 || teksAlamat.indexOf('riau') !== -1 || teksAlamat.indexOf('kampar') !== -1 || teksAlamat.indexOf('siak') !== -1) {
                        $('#calc_wilayah_tujuan').val('lokal_riau');
                    } else if (teksAlamat.indexOf('jakarta') !== -1 || teksAlamat.indexOf('bogor') !== -1 || teksAlamat.indexOf('depok') !== -1 || teksAlamat.indexOf('tangerang') !== -1 || teksAlamat.indexOf('bekasi') !== -1) {
                        $('#calc_wilayah_tujuan').val('jabodetabek');
                    } else if (teksAlamat.indexOf('jogja') !== -1 || teksAlamat.indexOf('yogyakarta') !== -1 || teksAlamat.indexOf('solo') !== -1 || teksAlamat.indexOf('semarang') !== -1 || teksAlamat.indexOf('jawa tengah') !== -1 || teksAlamat.indexOf('jateng') !== -1) {
                        $('#calc_wilayah_tujuan').val('jogja_solo_jateng');
                    } else if (teksAlamat.indexOf('surabaya') !== -1 || teksAlamat.indexOf('malang') !== -1 || teksAlamat.indexOf('sidoarjo') !== -1 || teksAlamat.indexOf('jawa timur') !== -1 || teksAlamat.indexOf('jatim') !== -1) {
                        $('#calc_wilayah_tujuan').val('jawa_timur');
                    } else if (teksAlamat.indexOf('bandung') !== -1 || teksAlamat.indexOf('jawa barat') !== -1 || teksAlamat.indexOf('jabar') !== -1 || teksAlamat.indexOf('banten') !== -1 || teksAlamat.indexOf('serang') !== -1) {
                        $('#calc_wilayah_tujuan').val('jawa_barat');
                    } else if (teksAlamat.indexOf('medan') !== -1 || teksAlamat.indexOf('padang') !== -1 || teksAlamat.indexOf('palembang') !== -1 || teksAlamat.indexOf('batam') !== -1) {
                        $('#calc_wilayah_tujuan').val('sumatera_kota');
                    } else if (teksAlamat.indexOf('sumatera') !== -1 || teksAlamat.indexOf('lampung') !== -1 || teksAlamat.indexOf('jambi') !== -1 || teksAlamat.indexOf('bengkulu') !== -1 || teksAlamat.indexOf('aceh') !== -1) {
                        $('#calc_wilayah_tujuan').val('sumatera_kab');
                    } else if (teksAlamat.indexOf('kalimantan') !== -1 || teksAlamat.indexOf('balikpapan') !== -1 || teksAlamat.indexOf('pontianak') !== -1 || teksAlamat.indexOf('banjarmasin') !== -1 || teksAlamat.indexOf('samarinda') !== -1) {
                        $('#calc_wilayah_tujuan').val('kalimantan');
                    } else if (teksAlamat.indexOf('sulawesi') !== -1 || teksAlamat.indexOf('makassar') !== -1 || teksAlamat.indexOf('manado') !== -1) {
                        $('#calc_wilayah_tujuan').val('sulawesi');
                    } else if (teksAlamat.indexOf('bali') !== -1 || teksAlamat.indexOf('denpasar') !== -1 || teksAlamat.indexOf('lombok') !== -1 || teksAlamat.indexOf('ntb') !== -1 || teksAlamat.indexOf('ntt') !== -1) {
                        $('#calc_wilayah_tujuan').val('bali_nusa');
                    } else if (teksAlamat.indexOf('papua') !== -1 || teksAlamat.indexOf('maluku') !== -1 || teksAlamat.indexOf('ambon') !== -1 || teksAlamat.indexOf('jayapura') !== -1) {
                        $('#calc_wilayah_tujuan').val('indonesia_timur');
                    }
                    if ($('#calc_wilayah_tujuan').val()) {
                        $('#btnHitungOngkir').trigger('click');
                    }
                });
            }

            // Reinit saat tipe penerima berubah
            $('#tipe_penerima').on('change', function() {
                $('#penerima_id').val(null).trigger('change');
                $('#alamat_penerima').val('');
            });

            // Mapping jenis_harga to pricelist jenis
            // regular = Swasta (jenis=1), e_catalog = Government (jenis=2), free = manual input (null)
            function mapJenisHargaToPricelist(jenisHarga) {
                switch (jenisHarga) {
                    case 'regular':
                        return 1; // Pricelist Swasta
                    case 'e_catalog':
                        return 2; // Pricelist Government
                    case 'free':
                        return null; // Manual input, no filter
                    default:
                        return null;
                }
            }

            // Initialize Select2 untuk produk
            function initSelect2Produk($el) {
                $el.select2({
                    ajax: {
                        url: '<?= base_url('sph/search_pricelist') ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            // Get jenis_harga from the same row
                            var $row = $el.closest('.item-row');
                            var jenisHarga = $row.find('.jenis-harga').val();
                            var jenisParam = mapJenisHargaToPricelist(jenisHarga);

                            var data = {
                                q: params.term
                            };
                            if (jenisParam !== null) {
                                data.jenis = jenisParam;
                            }
                            return data;
                        },
                        processResults: function(data) {
                            return {
                                results: data.results
                            };
                        }
                    },
                    minimumInputLength: 2,
                    placeholder: 'Cari produk...',
                    allowClear: true,
                    tags: true,
                    createTag: function(params) {
                        return {
                            id: 'custom_' + params.term,
                            text: params.term,
                            newTag: true
                        };
                    }
                });

                // On select, fill harga
                $el.on('select2:select', function(e) {
                    var data = e.params.data;
                    var $row = $(this).closest('.item-row');

                    if (data.harga) {
                        $row.find('.harga-pricelist').val(formatNumber(data.harga));
                        $row.find('input[name$="[pricelist_id]"]').val(data.id);
                        $row.find('input[name$="[deskripsi]"]').val(data.nama || data.text);
                    } else {
                        $row.find('input[name$="[deskripsi]"]').val(data.text);
                        $row.find('input[name$="[pricelist_id]"]').val('');
                    }

                    calculateRow($row);
                });
            }

            // Event handler: Reset produk dropdown when jenis_harga changes
            $(document).on('change', '.jenis-harga', function() {
                var $row = $(this).closest('.item-row');
                var $produkSelect = $row.find('.select2-produk');
                var jenisHarga = $(this).val();

                // Clear current selection
                $produkSelect.val(null).trigger('change');

                // Clear related fields
                $row.find('.harga-pricelist').val('0');
                $row.find('input[name$="[pricelist_id]"]').val('');
                $row.find('input[name$="[deskripsi]"]').val('');
                $row.find('.produk-manual-input').val('');

                // Toggle mode search/manual untuk free
                if (jenisHarga == 'free') {
                    $row.find('.produk-mode-toggle').slideDown(200);
                    // Default ke mode search
                    $row.find('.btn-mode-search').addClass('active');
                    $row.find('.btn-mode-manual').removeClass('active');
                    $row.find('.produk-search-wrapper').show();
                    $row.find('.produk-manual-wrapper').hide();
                } else {
                    $row.find('.produk-mode-toggle').slideUp(200);
                    // Pastikan search mode aktif
                    $row.find('.btn-mode-search').addClass('active');
                    $row.find('.btn-mode-manual').removeClass('active');
                    $row.find('.produk-search-wrapper').show();
                    $row.find('.produk-manual-wrapper').hide();
                }

                // Recalculate
                calculateRow($row);
            });

            // Toggle mode search/manual untuk produk (Free mode)
            $(document).on('click', '.btn-mode-search', function() {
                var $row = $(this).closest('.item-row');
                $(this).addClass('active');
                $row.find('.btn-mode-manual').removeClass('active');
                $row.find('.produk-search-wrapper').show();
                $row.find('.produk-manual-wrapper').hide();

                // Set deskripsi dari select2 jika ada
                var select2Text = $row.find('.select2-produk option:selected').text();
                if (select2Text && select2Text !== '-- Cari Produk --') {
                    $row.find('input[name$="[deskripsi]"]').val(select2Text);
                }
            });

            $(document).on('click', '.btn-mode-manual', function() {
                var $row = $(this).closest('.item-row');
                $(this).addClass('active');
                $row.find('.btn-mode-search').removeClass('active');
                $row.find('.produk-search-wrapper').hide();
                $row.find('.produk-manual-wrapper').show();

                // Clear pricelist_id karena input manual
                $row.find('input[name$="[pricelist_id]"]').val('');

                // Set deskripsi dari manual input
                var manualVal = $row.find('.produk-manual-input').val();
                $row.find('input[name$="[deskripsi]"]').val(manualVal);
            });

            // Update deskripsi saat user mengetik di input manual
            $(document).on('input', '.produk-manual-input', function() {
                var $row = $(this).closest('.item-row');
                $row.find('input[name$="[deskripsi]"]').val($(this).val());
            });

            // Tambah baris dengan animasi - Card Layout
            $('#btnAddRow').on('click', function() {
                var newRow = `
            <div class="item-row card mb-3" data-index="${rowIndex}" style="display: none; border: 1px solid #e0e0e0; border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="card-body p-3">
                    <!-- Header Row dengan No dan Delete -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-primary row-number" style="font-size: 14px; padding: 8px 15px; border-radius: 20px;">
                            <i class="fas fa-box mr-1"></i> Item #${rowIndex + 1}
                        </span>
                        <div class="d-flex align-items-center">
                            <div class="custom-control custom-checkbox mr-3" title="Sembunyikan item ini saat cetak">
                                <input type="checkbox" class="custom-control-input item-hide-print" 
                                       id="hideItem_${rowIndex}" name="items[${rowIndex}][hide_on_print]" value="1">
                                <label class="custom-control-label text-muted small" for="hideItem_${rowIndex}">
                                    <i class="fas fa-eye-slash mr-1"></i> Sembunyikan saat cetak
                                </label>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-remove-row" style="border-radius: 50%; width: 40px; height: 40px; padding: 0;" title="Hapus item">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Grid Layout 3 kolom -->
                    <div class="row">
                        <!-- Produk - Full Width -->
                        <div class="col-12 mb-3">
                            <label class="small font-weight-bold text-primary mb-1"><i class="fas fa-cube mr-1"></i> Produk <span class="text-danger">*</span></label>
                            <div class="produk-mode-toggle mb-1" style="display:none;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-mode-search active" title="Cari dari database">
                                        <i class="fas fa-search mr-1"></i> Cari Produk
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-mode-manual" title="Ketik manual">
                                        <i class="fas fa-keyboard mr-1"></i> Input Manual
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="items[${rowIndex}][pricelist_id]" value="">
                            <div class="produk-search-wrapper">
                                <select class="form-control select2-produk" name="items[${rowIndex}][deskripsi_select]">
                                    <option value="">-- Cari Produk --</option>
                                </select>
                            </div>
                            <div class="produk-manual-wrapper" style="display:none;">
                                <input type="text" class="form-control produk-manual-input" placeholder="Ketik nama produk manual..." value="">
                            </div>
                            <input type="hidden" name="items[${rowIndex}][deskripsi]" value="">
                        </div>
                        
                        <!-- Row 1: Jenis, Harga, Qty -->
                        <div class="col-md-4 col-6 mb-3">
                            <label class="small font-weight-bold text-secondary mb-1"><i class="fas fa-tag mr-1"></i> Jenis</label>
                            <select class="form-control jenis-harga" name="items[${rowIndex}][jenis_harga]">
                                <option value="regular">Regular</option>
                                <option value="e_catalog">E-Catalog</option>
                                <option value="free">Free</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <label class="small font-weight-bold text-secondary mb-1">
                                <i class="fas fa-money-bill mr-1"></i> Harga
                            </label>
                            <input type="text" class="form-control price-input harga-pricelist" 
                                   name="items[${rowIndex}][harga_pricelist]" value="0" placeholder="0">
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <label class="small font-weight-bold text-secondary mb-1">
                                <i class="fas fa-sort-numeric-up mr-1"></i> Qty
                            </label>
                            <input type="number" class="form-control qty text-center" 
                                   name="items[${rowIndex}][qty]" value="1" min="1">
                        </div>
                        
                        <!-- Row 2: Diskon, Harga Penawaran, Subtotal -->
                        <div class="col-md-4 col-6 mb-2">
                            <label class="small font-weight-bold text-secondary mb-1">
                                <i class="fas fa-percent mr-1"></i> Diskon
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control diskon-value" name="items[${rowIndex}][diskon_value]" value="0">
                                <div class="input-group-append">
                                    <select class="form-control tipe-diskon" name="items[${rowIndex}][tipe_diskon]" style="border-radius: 0 4px 4px 0;">
                                        <option value="none">-</option>
                                        <option value="persen">%</option>
                                        <option value="nominal">Rp</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="items[${rowIndex}][diskon_persen]" value="0">
                            <input type="hidden" name="items[${rowIndex}][diskon_nominal]" value="0">
                            <input type="hidden" name="items[${rowIndex}][tampilkan_diskon]" value="1">
                        </div>
                        <div class="col-md-4 col-6 mb-2">
                            <label class="small font-weight-bold text-success mb-1">
                                <i class="fas fa-hand-holding-usd mr-1"></i> Hrg Penawaran
                            </label>
                            <input type="text" class="form-control price-input harga-penawaran" 
                                   name="items[${rowIndex}][harga_penawaran]" value="0" readonly 
                                   style="background: #e8f5e9; font-weight: bold;">
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <label class="small font-weight-bold text-warning mb-1">
                                <i class="fas fa-calculator mr-1"></i> Subtotal
                            </label>
                            <input type="text" class="form-control price-input subtotal font-weight-bold" 
                                   name="items[${rowIndex}][subtotal]" value="0" readonly 
                                   style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); font-size: 16px; color: #e65100;">
                        </div>
                        <!-- Hidden input untuk menyimpan field yang disembunyikan -->
                        <input type="hidden" class="hidden-fields-input" name="items[${rowIndex}][hidden_fields]" value="[]">
                    </div>
                    
                    <!-- Dynamic columns container -->
                    <div class="dynamic-cols-container row">
                        ${getDynamicColumnCells(rowIndex)}
                    </div>
                </div>
            </div>
        `;

                $('#itemsBody').append(newRow);
                var $newRow = $('#itemsBody .item-row:last');
                $newRow.fadeIn(300);
                initSelect2Produk($newRow.find('.select2-produk'));

                // Apply global hidden columns ke row baru
                applyGlobalHiddenToRow($newRow);

                rowIndex++;
                updateRowNumbers();
            });

            // Hapus baris dengan animasi - Card Layout
            $(document).on('click', '.btn-remove-row', function() {
                var $row = $(this).closest('.item-row');
                if ($('#itemsBody .item-row').length > 1) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        updateRowNumbers();
                        calculateGrandTotal();
                    });
                } else {
                    toastr.warning('Minimal harus ada 1 baris produk');
                }
            });

            // Update nomor baris - Card Layout
            function updateRowNumbers() {
                $('#itemsBody .item-row:visible').each(function(idx) {
                    $(this).find('.row-number').html('<i class="fas fa-box mr-1"></i> Item #' + (idx + 1));
                });
            }

            // Get dynamic column cells for new row - Card Layout
            function getDynamicColumnCells(idx) {
                var cells = '';
                dynamicColumns.forEach(function(col, colIdx) {
                    cells += `<div class="col-md-4 col-6 mb-2 dynamic-col-cell" data-col="${col.id}">
                <label class="small font-weight-bold text-info mb-1">
                    <i class="fas fa-link mr-1"></i>${col.nama}
                </label>
                <input type="text" class="form-control" 
                       name="items[${idx}][dynamic_values][${col.id}]" value="" placeholder="Masukkan ${col.nama}">
            </div>`;
                });
                return cells;
            }

            // Tambah kolom - Card Layout
            $('#btnAddCol').on('click', function() {
                $('#namaKolomBaru').val('');
                $('#modalAddCol').modal('show');
            });

            $('#btnKonfirmasiAddCol').on('click', function() {
                var namaKolom = $('#namaKolomBaru').val().trim();
                if (!namaKolom) {
                    toastr.warning('Nama kolom harus diisi');
                    return;
                }

                colIndex++;
                var colId = 'new_' + colIndex;
                dynamicColumns.push({
                    id: colId,
                    nama: namaKolom
                });

                // Tambah input hidden untuk nama kolom dengan colId sebagai key
                $('#formSph').append('<input type="hidden" name="dynamic_columns[' + colId + ']" value="' + namaKolom + '">');

                // Tambah checkbox di konfigurasi global hide
                $('#dynamicColHideContainer').append(
                    '<div class="custom-control custom-checkbox dynamic-col-hide-item" data-col="' + colId + '">' +
                    '<input type="checkbox" class="custom-control-input global-hide-col" id="globalHide_dyn_' + colId + '" data-field="dyn_' + colId + '">' +
                    '<label class="custom-control-label small text-info" for="globalHide_dyn_' + colId + '"><i class="fas fa-link mr-1"></i>' + namaKolom + '</label>' +
                    '</div>'
                );

                // Tambah cell ke setiap item card
                $('#itemsBody .item-row').each(function() {
                    var idx = $(this).data('index');
                    $(this).find('.dynamic-cols-container').append(
                        '<div class="col-md-4 col-6 mb-2 dynamic-col-cell" data-col="' + colId + '">' +
                        '<label class="small font-weight-bold text-info mb-1">' +
                        '<i class="fas fa-link mr-1"></i>' + namaKolom +
                        ' <button type="button" class="btn btn-danger btn-xs btn-remove-col" data-col="' + colId + '" style="padding: 0 4px; font-size: 10px;">&times;</button>' +
                        '</label>' +
                        '<input type="text" class="form-control" name="items[' + idx + '][dynamic_values][' + colId + ']" value="" placeholder="Masukkan ' + namaKolom + '">' +
                        '</div>'
                    );
                });

                $('#modalAddCol').modal('hide');
                toastr.success('Kolom berhasil ditambahkan');
            });

            // Hapus kolom - Card Layout
            $(document).on('click', '.btn-remove-col', function() {
                var colId = $(this).data('col');

                // Hapus dari array
                dynamicColumns = dynamicColumns.filter(c => c.id != colId);

                // Hapus cell dari semua cards
                $('.dynamic-col-cell[data-col="' + colId + '"]').remove();

                // Hapus checkbox dari konfigurasi global hide
                $('.dynamic-col-hide-item[data-col="' + colId + '"]').remove();

                // Hapus hidden input kolom
                $('input[name="dynamic_columns[' + colId + ']"]').remove();

                toastr.success('Kolom berhasil dihapus');
            });

            // Calculate row
            function calculateRow($row) {
                var hargaPricelist = parseNumber($row.find('.harga-pricelist').val());
                var tipeDiskon = $row.find('.tipe-diskon').val();
                var diskonValue = parseNumber($row.find('.diskon-value').val());
                var qty = parseInt($row.find('.qty').val()) || 1;

                var hargaPenawaran = hargaPricelist;

                if (tipeDiskon == 'persen' && diskonValue > 0) {
                    hargaPenawaran = hargaPricelist * (100 - diskonValue) / 100;
                    $row.find('input[name$="[diskon_persen]"]').val(diskonValue);
                    $row.find('input[name$="[diskon_nominal]"]').val(0);
                } else if (tipeDiskon == 'nominal' && diskonValue > 0) {
                    hargaPenawaran = hargaPricelist - diskonValue;
                    $row.find('input[name$="[diskon_persen]"]').val(0);
                    $row.find('input[name$="[diskon_nominal]"]').val(diskonValue);
                }

                var subtotal = hargaPenawaran * qty;

                $row.find('.harga-penawaran').val(formatNumber(hargaPenawaran));
                $row.find('.subtotal').val(formatNumber(subtotal));

                calculateGrandTotal();
            }

            // Calculate grand total - Card Layout
            function calculateGrandTotal() {
                var total = 0;
                $('#itemsBody .item-row').each(function() {
                    total += parseNumber($(this).find('.subtotal').val());
                });
                $('#grandTotal').val(formatNumber(total));

                // Update cicilan jika aktif
                if ($('input[name="sistem_pembayaran"]:checked').val() == 'cicilan') {
                    hitungCicilan();
                }
            }

            // Event listeners untuk kalkulasi
            $(document).on('change keyup', '.harga-pricelist, .diskon-value, .qty', function() {
                calculateRow($(this).closest('.item-row'));
            });

            $(document).on('change', '.tipe-diskon, .jenis-harga', function() {
                calculateRow($(this).closest('.item-row'));
            });

            // Tambah keterangan custom
            $('#btnAddKeterangan').on('click', function() {
                var html = `
            <div class="input-group mb-2 keterangan-custom-row">
                <input type="text" class="form-control" name="keterangan_custom[]" placeholder="Masukkan keterangan...">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger btn-remove-keterangan"><i class="fas fa-times"></i></button>
                </div>
            </div>
        `;
                $('#keteranganCustomContainer').append(html);
            });

            $(document).on('click', '.btn-remove-keterangan', function() {
                $(this).closest('.keterangan-custom-row').remove();
            });

            // Helper functions
            function parseNumber(str) {
                if (!str) return 0;
                // Remove dots (thousand separator) and replace comma with dot for decimal
                return parseFloat(str.toString().replace(/\./g, '').replace(',', '.')) || 0;
            }

            function formatNumber(num) {
                // Format number to Indonesian format (dot as thousand separator)
                return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Format rupiah with prefix
            function formatRupiah(num) {
                return 'Rp ' + formatNumber(num);
            }

            // Submit form
            $('#formSph').on('submit', function(e) {
                e.preventDefault();

                // Validasi minimal 1 produk (card layout)
                var itemCount = $('#itemsBody .item-row').length;
                if (itemCount === 0) {
                    toastr.error('Minimal harus ada 1 produk dalam tabel!');
                    return false;
                }

                // Validasi apakah ada produk yang dipilih
                var hasValidProduct = false;
                $('#itemsBody .item-row').each(function() {
                    var deskripsi = $(this).find('input[name$="[deskripsi]"]').val();
                    var pricelistId = $(this).find('input[name$="[pricelist_id]"]').val();
                    if (deskripsi || pricelistId) {
                        hasValidProduct = true;
                        return false; // break loop
                    }
                });

                if (!hasValidProduct) {
                    toastr.error('Silakan pilih minimal 1 produk!');
                    return false;
                }

                var url = '<?= $isEdit && !$isDuplicate ? base_url('sph/update') : base_url('sph/simpan') ?>';
                var $btn = $('#btnSimpan');

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(response.message);

                            // Cek apakah auto preview
                            if (response.data.auto_preview) {
                                // Buka preview di tab baru, lalu redirect ke edit
                                window.open(response.data.print_url, '_blank');
                                setTimeout(function() {
                                    window.location.href = '<?= base_url('sph/edit/') ?>' + response.data.id;
                                }, 500);
                            } else {
                                setTimeout(function() {
                                    window.location.href = '<?= base_url('sph/detail/') ?>' + response.data.id;
                                }, 1000);
                            }
                        } else {
                            toastr.error(response.message);
                            $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan SPH');
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Terjadi kesalahan sistem';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        toastr.error(msg);
                        $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan SPH');
                    }
                });
            });

            // Preview
            $('#btnPreview').on('click', function() {
                <?php if ($isEdit && !$isDuplicate): ?>
                    // Mode edit - langsung bisa preview
                    window.open('<?= base_url('sph/print/' . encrypt($sph->id ?? 0)) ?>', '_blank');
                <?php else: ?>
                    // Mode create/duplicate - perlu simpan dulu
                    Swal.fire({
                        title: 'Simpan sebagai Draft?',
                        text: 'Data harus disimpan terlebih dahulu sebelum preview.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-save mr-1"></i> Ya, Simpan Draft',
                        cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Set status ke draft dan submit form
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'auto_preview',
                                value: '1'
                            }).appendTo('#formSph');
                            $('#formSph').submit();
                        }
                    });
                <?php endif; ?>
            });

            // Initial calculation
            calculateGrandTotal();

            // Hitung cicilan jika mode cicilan aktif (untuk edit/duplicate mode)
            if ($('input[name="sistem_pembayaran"]:checked').val() == 'cicilan') {
                hitungCicilan();
            }

            // ============================================
            // DYNAMIC NOMOR SURAT AJAX GENERATION
            // ============================================
            function getNextNomorSurat() {
                var isEdit = <?= $isEdit ? 'true' : 'false' ?>;
                var isDuplicate = <?= $isDuplicate ? 'true' : 'false' ?>;
                var initialIsVisilab = <?= ($hasData && !empty($sph->is_visilab)) ? '1' : '0' ?>;
                var currentIsVisilab = $('#is_visilab').is(':checked') ? 1 : 0;

                // Jika edit mode (bukan duplicate) dan toggle sama dengan awalnya, restore nomor original
                if (isEdit && !isDuplicate && currentIsVisilab === initialIsVisilab) {
                    $('#nomor_surat').val('<?= $sph ? addslashes($sph->nomor_surat) : '' ?>');
                    return;
                }

                var tanggal = $('#tanggal_surat').val();
                if (!tanggal) return;

                $.ajax({
                    url: '<?= base_url("sph/get_next_number") ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        tanggal: tanggal,
                        is_visilab: currentIsVisilab
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#nomor_surat').val(response.nomor_surat);
                        }
                    }
                });
            }

            // ============================================
            // VISILAB TOGGLE - Nomor Surat Manual
            // ============================================
            $('#is_visilab').on('change', function() {
                var $nomorSurat = $('#nomor_surat');
                if ($(this).is(':checked')) {
                    // Visilab mode
                    $nomorSurat.prop('readonly', true);
                    $nomorSurat.css({
                        'background-color': '#e9ecef',
                        'border-color': '#17a2b8'
                    });
                    // Auto-fill TTD Visilab
                    $('input[name="nama_ttd"]').val('Mega Ratu, S.KM, M.KM');
                    $('input[name="jabatan_ttd"]').val('Head of Visilab');
                    toastr.info('Mode Visilab aktif - Nomor surat akan digenerate otomatis format Visilab', 'Visilab');
                } else {
                    // Normal mode
                    $nomorSurat.prop('readonly', true);
                    $nomorSurat.css({
                        'background-color': '#e9ecef',
                        'border-color': '#ced4da'
                    });
                    // Reset TTD ke default
                    $('input[name="nama_ttd"]').val('Yolanda Pratiwi, S.Pd');
                    $('input[name="jabatan_ttd"]').val('General Manager');
                }
                getNextNomorSurat();
            });

            // Trigger fetch number when date changes
            $('#tanggal_surat').on('change', function() {
                getNextNomorSurat();
            });

            // Trigger fetch number on page load if not edit mode
            <?php if (!$isEdit || $isDuplicate): ?>
                getNextNomorSurat();
            <?php endif; ?>

            // ==========================================
            // LOGIKA KALKULATOR PENGIRIMAN MULTI-ASAL KANTOR (ONS & REGULER)
            // ==========================================
            var labelKantor = {
                'pekanbaru': 'Kantor Pekanbaru',
                'jakarta': 'Kantor Jakarta',
                'jogja': 'Kantor Jogja'
            };

            var tarifMatriks = {
                // ASAL: PEKANBARU
                'pekanbaru': {
                    'lokal_riau': { label: 'Riau / Pekanbaru & Sekitarnya', ons: { tarif: 15000, est: '1 Hari' }, reg: { tarif: 8000, est: '1 - 2 Hari' } },
                    'jabodetabek': { label: 'Jabodetabek', ons: { tarif: 26000, est: '1 Hari Kerja' }, reg: { tarif: 15000, est: '2 - 3 Hari Kerja' } },
                    'jogja_solo_jateng': { label: 'DIY & Jawa Tengah', ons: { tarif: 32000, est: '1 Hari Kerja' }, reg: { tarif: 20000, est: '2 - 3 Hari Kerja' } },
                    'jawa_timur': { label: 'Jawa Timur', ons: { tarif: 34000, est: '1 Hari Kerja' }, reg: { tarif: 22000, est: '2 - 4 Hari Kerja' } },
                    'jawa_barat': { label: 'Jawa Barat & Banten', ons: { tarif: 30000, est: '1 Hari Kerja' }, reg: { tarif: 18000, est: '2 - 3 Hari Kerja' } },
                    'sumatera_kota': { label: 'Sumatera (Kota Besar)', ons: { tarif: 26000, est: '1 Hari Kerja' }, reg: { tarif: 16000, est: '2 - 3 Hari Kerja' } },
                    'sumatera_kab': { label: 'Sumatera (Kabupaten)', ons: { tarif: 36000, est: '1 - 2 Hari' }, reg: { tarif: 22000, est: '3 - 5 Hari Kerja' } },
                    'kalimantan': { label: 'Kalimantan', ons: { tarif: 48000, est: '1 - 2 Hari Kerja' }, reg: { tarif: 35000, est: '3 - 5 Hari Kerja' } },
                    'sulawesi': { label: 'Sulawesi', ons: { tarif: 55000, est: '1 - 2 Hari Kerja' }, reg: { tarif: 38000, est: '3 - 5 Hari Kerja' } },
                    'bali_nusa': { label: 'Bali & Nusa Tenggara', ons: { tarif: 52000, est: '1 - 2 Hari' }, reg: { tarif: 36000, est: '3 - 5 Hari Kerja' } },
                    'indonesia_timur': { label: 'Maluku & Papua', ons: { tarif: 85000, est: '2 - 3 Hari' }, reg: { tarif: 55000, est: '5 - 8 Hari Kerja' } }
                },
                // ASAL: JAKARTA
                'jakarta': {
                    'lokal_riau': { label: 'Riau / Pekanbaru & Sekitarnya', ons: { tarif: 26000, est: '1 Hari Kerja' }, reg: { tarif: 15000, est: '2 - 3 Hari' } },
                    'jabodetabek': { label: 'Jabodetabek (Lokal)', ons: { tarif: 14000, est: '1 Hari' }, reg: { tarif: 9000, est: '1 - 2 Hari' } },
                    'jogja_solo_jateng': { label: 'DIY & Jawa Tengah', ons: { tarif: 22000, est: '1 Hari Kerja' }, reg: { tarif: 13000, est: '1 - 2 Hari' } },
                    'jawa_timur': { label: 'Jawa Timur', ons: { tarif: 24000, est: '1 Hari Kerja' }, reg: { tarif: 14000, est: '2 - 3 Hari' } },
                    'jawa_barat': { label: 'Jawa Barat & Banten', ons: { tarif: 18000, est: '1 Hari Kerja' }, reg: { tarif: 11000, est: '1 - 2 Hari' } },
                    'sumatera_kota': { label: 'Sumatera (Kota Besar)', ons: { tarif: 26000, est: '1 Hari Kerja' }, reg: { tarif: 16000, est: '2 - 3 Hari' } },
                    'sumatera_kab': { label: 'Sumatera (Kabupaten)', ons: { tarif: 35000, est: '1 - 2 Hari' }, reg: { tarif: 22000, est: '3 - 5 Hari' } },
                    'kalimantan': { label: 'Kalimantan', ons: { tarif: 42000, est: '1 - 2 Hari Kerja' }, reg: { tarif: 30000, est: '2 - 4 Hari' } },
                    'sulawesi': { label: 'Sulawesi', ons: { tarif: 48000, est: '1 - 2 Hari Kerja' }, reg: { tarif: 34000, est: '3 - 5 Hari' } },
                    'bali_nusa': { label: 'Bali & Nusa Tenggara', ons: { tarif: 38000, est: '1 Hari Kerja' }, reg: { tarif: 24000, est: '2 - 3 Hari' } },
                    'indonesia_timur': { label: 'Maluku & Papua', ons: { tarif: 75000, est: '2 - 3 Hari' }, reg: { tarif: 48000, est: '4 - 7 Hari' } }
                },
                // ASAL: JOGJA (YOGYAKARTA)
                'jogja': {
                    'lokal_riau': { label: 'Riau / Pekanbaru & Sekitarnya', ons: { tarif: 32000, est: '1 Hari Kerja' }, reg: { tarif: 20000, est: '2 - 4 Hari' } },
                    'jabodetabek': { label: 'Jabodetabek', ons: { tarif: 20000, est: '1 Hari Kerja' }, reg: { tarif: 12000, est: '1 - 2 Hari' } },
                    'jogja_solo_jateng': { label: 'DIY & Jawa Tengah (Lokal)', ons: { tarif: 12000, est: '1 Hari' }, reg: { tarif: 7000, est: '1 Hari' } },
                    'jawa_timur': { label: 'Jawa Timur', ons: { tarif: 18000, est: '1 Hari Kerja' }, reg: { tarif: 11000, est: '1 - 2 Hari' } },
                    'jawa_barat': { label: 'Jawa Barat & Banten', ons: { tarif: 20000, est: '1 Hari Kerja' }, reg: { tarif: 13000, est: '1 - 2 Hari' } },
                    'sumatera_kota': { label: 'Sumatera (Kota Besar)', ons: { tarif: 30000, est: '1 Hari Kerja' }, reg: { tarif: 20000, est: '2 - 4 Hari' } },
                    'sumatera_kab': { label: 'Sumatera (Kabupaten)', ons: { tarif: 40000, est: '2 Hari Kerja' }, reg: { tarif: 26000, est: '3 - 5 Hari' } },
                    'kalimantan': { label: 'Kalimantan', ons: { tarif: 44000, est: '1 - 2 Hari Kerja' }, reg: { tarif: 32000, est: '3 - 5 Hari' } },
                    'sulawesi': { label: 'Sulawesi', ons: { tarif: 50000, est: '1 - 2 Hari Kerja' }, reg: { tarif: 35000, est: '3 - 5 Hari' } },
                    'bali_nusa': { label: 'Bali & Nusa Tenggara', ons: { tarif: 32000, est: '1 Hari Kerja' }, reg: { tarif: 20000, est: '2 - 3 Hari' } },
                    'indonesia_timur': { label: 'Maluku & Papua', ons: { tarif: 80000, est: '2 - 3 Hari' }, reg: { tarif: 52000, est: '5 - 7 Hari' } }
                }
            };

            function formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            }

            var currentEstimasi = null;

            $('#btnHitungOngkir').on('click', function() {
                var asal = $('#calc_asal_kantor').val() || 'pekanbaru';
                var zona = $('#calc_wilayah_tujuan').val();
                var berat = parseFloat($('#calc_berat').val()) || 1;
                if (berat <= 0) berat = 1;

                if (!zona) {
                    toastr.warning('Silakan pilih Wilayah / Kota Tujuan terlebih dahulu.', 'Pilih Wilayah');
                    return;
                }

                var tarifAsal = tarifMatriks[asal] || tarifMatriks['pekanbaru'];
                var data = tarifAsal[zona];
                if (!data) return;

                var totalOns = data.ons.tarif * Math.ceil(berat);
                var totalReg = data.reg.tarif * Math.ceil(berat);

                $('#est_hari_ons').text(data.ons.est);
                $('#tarif_ons').text(formatRupiah(data.ons.tarif) + '/Kg');
                $('#total_ons').text(formatRupiah(totalOns));

                $('#est_hari_reguler').text(data.reg.est);
                $('#tarif_reguler').text(formatRupiah(data.reg.tarif) + '/Kg');
                $('#total_reguler').text(formatRupiah(totalReg));

                $('#hasilEstimasiBox').slideDown();

                currentEstimasi = {
                    asal: labelKantor[asal],
                    wilayah: data.label,
                    berat: Math.ceil(berat),
                    ons: { est: data.ons.est, tarif: totalOns },
                    reg: { est: data.reg.est, tarif: totalReg }
                };
            });

            $('#calc_asal_kantor, #calc_wilayah_tujuan').on('change', function() {
                if ($('#calc_wilayah_tujuan').val()) {
                    $('#btnHitungOngkir').trigger('click');
                }
            });

            // Tombol Salin ke Catatan/Keterangan SPH
            $(document).on('click', '.btn-salin-ket', function() {
                if (!currentEstimasi) return;
                var jenis = $(this).data('layanan'); // ONS atau Reguler
                var teksKet = '';

                if (jenis === 'ONS') {
                    teksKet = 'Estimasi pengiriman via ONS (Over Night Service) dari ' + currentEstimasi.asal + ' ke ' + currentEstimasi.wilayah + ': ' + currentEstimasi.ons.est + ' (Est. Biaya ' + formatRupiah(currentEstimasi.ons.tarif) + ' untuk ' + currentEstimasi.berat + ' Kg).';
                } else {
                    teksKet = 'Estimasi pengiriman via Reguler dari ' + currentEstimasi.asal + ' ke ' + currentEstimasi.wilayah + ': ' + currentEstimasi.reg.est + ' (Est. Biaya ' + formatRupiah(currentEstimasi.reg.tarif) + ' untuk ' + currentEstimasi.berat + ' Kg).';
                }

                // Tambahkan langsung ke Keterangan Tambahan SPH
                var htmlCustom = '<div class="input-group mb-2 keterangan-custom-row">' +
                    '<input type="text" class="form-control" name="keterangan_custom[]" value="' + teksKet.replace(/"/g, '&quot;') + '">' +
                    '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-danger btn-remove-keterangan"><i class="fas fa-times"></i></button>' +
                    '</div>' +
                    '</div>';

                $('#keteranganCustomContainer').append(htmlCustom);
                toastr.success('Keterangan estimasi pengiriman ' + jenis + ' dari ' + currentEstimasi.asal + ' berhasil ditambahkan ke Catatan SPH!', 'Berhasil Ditambahkan');
                
                // Scroll halus ke bagian Keterangan
                $('html, body').animate({
                    scrollTop: $("#keteranganCustomContainer").offset().top - 200
                }, 400);
            });

            // Tampilkan toast saat page load
            <?php if (!$isEdit || $isDuplicate): ?>
                toastr.info('<?= $isDuplicate ? "Data SPH telah disalin. Silakan sesuaikan dan simpan sebagai SPH baru." : "Lengkapi form di bawah untuk membuat Surat Penawaran Harga baru" ?>', '<?= $isDuplicate ? "Duplikasi SPH" : "Buat SPH Baru" ?>');
            <?php endif; ?>

        }); // End jQuery ready
    })(); // End checkJQuery IIFE
</script>