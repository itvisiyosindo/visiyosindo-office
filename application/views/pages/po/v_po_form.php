<header class="page-header">
    <h2><i class="fas fa-file-invoice text-primary mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url(($switch ?? '') == 'marketing' ? 'dashboard_marketing' : 'dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
            <li><a href="<?= base_url('po_system') ?>">Daftar PO Masuk</a></li>
            <li><span><?= $page_title ?></span></li>
        </ol>
    </div>
</header>

<style>
    :root {
        --primary-blue: #1e40af;
        --primary-light: #eff6ff;
        --card-radius: 12px;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .card-modern {
        border: 1px solid var(--border-color);
        border-radius: var(--card-radius);
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        background: #ffffff;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-header-modern {
        background: #ffffff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.1rem 1.4rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-modern h5 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.02rem;
        letter-spacing: -0.2px;
        display: flex;
        align-items: center;
    }

    .card-header-modern h5 i {
        margin-right: 10px;
        color: #2563eb;
    }

    .form-label-custom {
        font-size: 0.84rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }

    .form-label-custom .text-danger {
        font-size: 0.9rem;
    }

    .form-control-custom {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.88rem;
        color: #0f172a;
        transition: all 0.2s ease;
        background-color: #fff;
    }

    .form-control-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    .form-control-custom[readonly] {
        background-color: #f8fafc;
        color: #64748b;
    }

    /* Proteksi alignment & styling dropdown select & select2 */
    select.form-control-custom,
    select.form-control-custom option {
        text-align: left !important;
        direction: ltr !important;
        white-space: normal !important;
    }

    .select2-container--bootstrap .select2-selection--single {
        height: 42px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 0.88rem !important;
        color: #0f172a !important;
        background-color: #fff !important;
    }

    .select2-container--bootstrap .select2-selection--single .select2-selection__rendered {
        padding-left: 0 !important;
        padding-right: 24px !important;
        color: #0f172a !important;
        line-height: 24px !important;
        text-align: left !important;
    }

    .select2-container--bootstrap .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }

    .select2-container--bootstrap.select2-container--focus .select2-selection,
    .select2-container--bootstrap.select2-container--open .select2-selection {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
    }

    .select2-dropdown {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        z-index: 1060 !important;
    }

    .select2-results__option {
        padding: 8px 12px !important;
        font-size: 0.88rem !important;
        line-height: 1.4 !important;
        text-align: left !important;
        white-space: normal !important;
        word-break: break-word !important;
    }

    .select2-container--bootstrap .select2-results__option--highlighted[aria-selected] {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }

    .custom-radio-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        background: #fff;
        height: 100%;
    }

    .custom-radio-card:hover {
        border-color: #93c5fd;
        background: #f8fafc;
    }

    .custom-radio-card input[type="radio"] {
        margin-right: 10px;
        transform: scale(1.15);
    }

    .custom-radio-card.active {
        border-color: #2563eb;
        background-color: #eff6ff;
    }

    .table-items thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        padding: 11px 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-items tbody td {
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .summary-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 0.9rem;
    }

    .summary-row.total-row {
        border-bottom: none;
        border-top: 2px solid #cbd5e1;
        margin-top: 8px;
        padding-top: 12px;
        font-size: 1.12rem;
        font-weight: 800;
        color: #0f172a;
    }

    .help-hint {
        font-size: 0.77rem;
        color: #64748b;
        margin-top: 4px;
        display: block;
    }

    .badge-pill-soft {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

<div class="row">
    <div class="col-12">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i><?= $this->session->flashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php
            $is_edit = !empty($po_edit);
            $doc_checked = $is_edit && !empty($po_edit->dokumen_dilampirkan)
                ? array_map('trim', explode(',', $po_edit->dokumen_dilampirkan))
                : ['PO', 'Kwitansi', 'Faktur Pajak', 'Invoice'];
            $npwp_map = [];
            if (!empty($pelanggan_list)) {
                foreach ($pelanggan_list as $pl) {
                    if (!empty($pl->identitas_pelanggan) && !empty($pl->npwp)) {
                        $npwp_map[strtolower(trim($pl->identitas_pelanggan))] = trim($pl->npwp);
                    }
                }
            }
        ?>

        <form action="<?= base_url('po_system/simpan') ?>" method="post" enctype="multipart/form-data" id="formPoMasuk">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id_po_edit" value="<?= htmlspecialchars($id_encrypt ?? '') ?>">
            <?php endif; ?>
            
            <!-- CARD 1: INFORMASI PO & DATA CUSTOMER -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-building"></i> 1. Informasi PO & Data Pelanggan / Customer</h5>
                    <span class="badge badge-info text-white badge-pill-soft">Kode: <?= htmlspecialchars($is_edit ? ($po_edit->kode_masuk ?? $kode_masuk_auto) : $kode_masuk_auto) ?></span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Nomor PO -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Nomor PO Customer <span class="text-danger">*</span></label>
                            <input type="text" name="no_po" class="form-control form-control-custom" placeholder="Contoh: 045/PO-RSUD/IX/2026" value="<?= htmlspecialchars($po_edit->no_po ?? '') ?>" required autofocus>
                            <small class="help-hint">Nomor resmi yang tertera pada lembar PO Customer</small>
                        </div>

                        <!-- Tanggal PO -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Tanggal Surat PO <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_po" class="form-control form-control-custom" value="<?= htmlspecialchars($po_edit->tanggal_po ?? date('Y-m-d')) ?>" required>
                        </div>

                        <!-- Tanggal Diterima -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Tanggal Diterima Marketing</label>
                            <input type="date" name="tanggal_terima" class="form-control form-control-custom" value="<?= htmlspecialchars($po_edit->tanggal_terima ?? date('Y-m-d')) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Pilih Customer dari Inventory -> Master Data -> Customer -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label-custom">
                                Nama Customer <span class="text-danger">*</span>
                                <span class="text-muted font-weight-normal">(Inventory &rarr; Master Data &rarr; Customer)</span>
                            </label>
                            <select name="id_customer" id="selectCustomer" data-plugin-selectTwo class="form-control form-control-custom select2" style="width: 100%;" data-placeholder="-- Ketik / Pilih Customer --" required>
                                <option value="">-- Ketik / Pilih Customer --</option>
                                <?php if (!empty($customer_list)): ?>
                                    <?php foreach ($customer_list as $cust): ?>
                                        <?php
                                            $nama_c = trim($cust->nama_customer ?? '');
                                            $npwp_c = $npwp_map[strtolower($nama_c)] ?? '';
                                            $contact_preview = '';
                                            if (!empty($cust->contact) && trim($cust->contact) !== '-') {
                                                $clean_c = preg_replace('/\s+/', ' ', trim($cust->contact));
                                                if (mb_strlen($clean_c) > 35) {
                                                    $clean_c = mb_substr($clean_c, 0, 32) . '...';
                                                }
                                                $contact_preview = ' — [' . $clean_c . ']';
                                            }
                                            $display_label = $nama_c . $contact_preview;
                                            $sel_cust = ($is_edit && ($po_edit->id_customer == $cust->id_customer || strcasecmp(trim($po_edit->nama_customer ?? ''), $nama_c) === 0)) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $cust->id_customer ?>" data-nama="<?= htmlspecialchars($nama_c) ?>" data-alamat="<?= htmlspecialchars(trim($cust->alamat_customer ?? '')) ?>" data-contact="<?= htmlspecialchars(trim($cust->contact ?? '')) ?>" data-npwp="<?= htmlspecialchars($npwp_c) ?>" <?= $sel_cust ?>><?= htmlspecialchars($display_label) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="nama_customer" id="namaCustomerInput" value="<?= htmlspecialchars($po_edit->nama_customer ?? '') ?>">
                            <div class="mt-1">
                                <small class="text-warning font-italic">
                                    <i class="fas fa-info-circle mr-1"></i>Jika customer belum terdaftar, silakan tambahkan data melalui menu <strong>Inventory &rarr; Master Data &rarr; Customer</strong>.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Alamat Customer (Otomatis) -->
                        <div class="col-md-8 mb-3">
                            <label class="form-label-custom">Alamat Customer <span class="badge badge-secondary badge-pill-soft ml-1">Otomatis dari Master</span></label>
                            <textarea name="alamat_customer" id="alamatCustomer" class="form-control form-control-custom" rows="2" placeholder="Otomatis terisi dari data master customer..."><?= htmlspecialchars($po_edit->alamat_customer ?? '') ?></textarea>
                        </div>

                        <!-- NPWP Customer (Otomatis) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">NPWP Customer <span class="badge badge-secondary badge-pill-soft ml-1">Otomatis</span></label>
                            <input type="text" name="npwp_customer" id="npwpCustomer" class="form-control form-control-custom" placeholder="Otomatis dari master..." value="<?= htmlspecialchars($po_edit->npwp_customer ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Alamat Pengiriman (Input Sendiri) -->
                        <div class="col-md-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label-custom mb-0">Alamat Pengiriman Barang <span class="text-danger">*</span> <span class="text-muted font-weight-normal">(Input Sendiri)</span></label>
                                <button type="button" class="btn btn-xs btn-outline-primary" id="btnSalinAlamat">
                                    <i class="fas fa-copy mr-1"></i> Salin dari Alamat Customer
                                </button>
                            </div>
                            <textarea name="alamat_pengiriman" id="alamatPengiriman" class="form-control form-control-custom" rows="2" placeholder="Masukkan alamat lengkap tujuan pengiriman fisik barang (Rumah Sakit / Lab / Klinik / Ruangan / Gedung)..." required><?= htmlspecialchars($po_edit->alamat_pengiriman ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <!-- PIC Penerima Barang -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light">
                                <label class="form-label-custom text-primary"><i class="fas fa-user-check mr-1"></i> PIC Penerima Barang</label>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" name="pic_penerima_nama" class="form-control form-control-custom" placeholder="Nama PIC Penerima" value="<?= htmlspecialchars($po_edit->pic_penerima_nama ?? '') ?>" required>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" name="pic_penerima_nohp" class="form-control form-control-custom" placeholder="No. HP / WhatsApp PIC" value="<?= htmlspecialchars($po_edit->pic_penerima_nohp ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PIC Penagihan -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light">
                                <label class="form-label-custom text-primary"><i class="fas fa-file-invoice-dollar mr-1"></i> PIC Penagihan / Keuangan</label>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" name="pic_penagihan_nama" class="form-control form-control-custom" placeholder="Nama PIC Penagihan" value="<?= htmlspecialchars($po_edit->pic_penagihan_nama ?? '') ?>" required>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" name="pic_penagihan_nohp" class="form-control form-control-custom" placeholder="No. HP / WhatsApp Penagihan" value="<?= htmlspecialchars($po_edit->pic_penagihan_nohp ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 2: SISTEM TRANSAKSI, PERPAJAKAN & PENGIRIMAN -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-money-check-alt"></i> 2. Sistem Pembayaran, Perpajakan & Dokumen</h5>
                </div>
                <div class="card-body p-4">
                    <?php $cur_pay = $po_edit->sistem_pembayaran ?? 'Cash'; ?>
                    <!-- Sistem Pembayaran -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label-custom">Sistem Pembayaran <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="custom-radio-card" id="cardPayCash">
                                        <input type="radio" name="sistem_pembayaran" value="Cash" <?= ($cur_pay === 'Cash') ? 'checked' : '' ?>>
                                        <div>
                                            <span class="font-weight-bold d-block">Cash / Lunas</span>
                                            <small class="text-muted">Pembayaran langsung / tunai saat barang diterima</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="custom-radio-card" id="cardPayTempo">
                                        <input type="radio" name="sistem_pembayaran" value="Tempo" <?= ($cur_pay === 'Tempo') ? 'checked' : '' ?>>
                                        <div>
                                            <span class="font-weight-bold d-block">Tempo</span>
                                            <small class="text-muted">Pembayaran bertenggang (30 / 60 hari)</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="custom-radio-card" id="cardPayCicilan">
                                        <input type="radio" name="sistem_pembayaran" value="Cicilan" <?= ($cur_pay === 'Cicilan') ? 'checked' : '' ?>>
                                        <div>
                                            <span class="font-weight-bold d-block">Cicilan / Bertahap</span>
                                            <small class="text-muted">Pembayaran dengan DP & beberapa kali termin</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input Tambahan Jika Cicilan Dipilih -->
                    <div class="row mb-3 p-3 border rounded bg-light" id="boxCicilan" style="<?= ($cur_pay === 'Cicilan') ? 'display: flex;' : 'display: none;' ?>">
                        <div class="col-md-6 mb-2">
                            <label class="form-label-custom">Uang Muka / DP (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="cicilan_dp_persen" class="form-control form-control-custom" placeholder="Contoh: 30" value="<?= htmlspecialchars($po_edit->cicilan_dp_persen ?? '0') ?>">
                                <div class="input-group-append"><span class="input-group-text">%</span></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label-custom">Jumlah Cicilan</label>
                            <div class="input-group">
                                <input type="number" name="cicilan_kali" class="form-control form-control-custom" placeholder="Contoh: 3" value="<?= htmlspecialchars($po_edit->cicilan_kali ?? '0') ?>">
                                <div class="input-group-append"><span class="input-group-text">Kali (X)</span></div>
                            </div>
                        </div>
                    </div>

                    <?php $cur_pajak = $po_edit->sistem_perpajakan ?? 'PPN'; ?>
                    <!-- Sistem Perpajakan -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Sistem Perpajakan <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <label class="custom-radio-card mr-2 flex-fill" id="cardPajakPpn">
                                    <input type="radio" name="sistem_perpajakan" value="PPN" <?= ($cur_pajak === 'PPN') ? 'checked' : '' ?>>
                                    <span class="font-weight-bold">PPN (11%)</span>
                                </label>
                                <label class="custom-radio-card mr-2 flex-fill" id="cardPajakNonPpn">
                                    <input type="radio" name="sistem_perpajakan" value="NON PPN" <?= ($cur_pajak === 'NON PPN') ? 'checked' : '' ?>>
                                    <span class="font-weight-bold">NON PPN</span>
                                </label>
                                <label class="custom-radio-card flex-fill" id="cardPajakEndorsement">
                                    <input type="radio" name="sistem_perpajakan" value="ENDORSEMENT" <?= ($cur_pajak === 'ENDORSEMENT') ? 'checked' : '' ?>>
                                    <span class="font-weight-bold">ENDORSEMENT</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">No. NPWP Pajak / KTP</label>
                            <input type="text" name="no_npwp_ktp" id="noNpwpKtp" class="form-control form-control-custom" placeholder="Masukkan nomor NPWP atau NIK KTP untuk faktur" value="<?= htmlspecialchars($po_edit->no_npwp_ktp ?? '') ?>">
                        </div>
                    </div>

                    <?php
                        $cur_faktur = $po_edit->info_pengiriman_faktur ?? 'DIMUKA';
                        $cur_ongkir = $po_edit->ongkir_ditanggung ?? 'PERUSAHAAN';
                        $cur_dok_tipe = $po_edit->dokumen_pembayaran_tipe ?? 'Hardfile';
                    ?>
                    <div class="row mb-3">
                        <!-- Informasi Pengiriman Faktur Pajak -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Informasi Pengiriman Faktur Pajak</label>
                            <div class="d-flex">
                                <label class="custom-radio-card mr-2 flex-fill">
                                    <input type="radio" name="info_pengiriman_faktur" value="DIMUKA" <?= ($cur_faktur === 'DIMUKA') ? 'checked' : '' ?>>
                                    <span class="font-weight-bold">Di Muka</span>
                                </label>
                                <label class="custom-radio-card flex-fill">
                                    <input type="radio" name="info_pengiriman_faktur" value="SAAT PELUNASAN" <?= ($cur_faktur === 'SAAT PELUNASAN') ? 'checked' : '' ?>>
                                    <span class="font-weight-bold">Saat Pelunasan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Ongkos Kirim Ditanggung Oleh -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Ongkos Kirim Ditanggung Oleh</label>
                            <div class="d-flex">
                                <label class="custom-radio-card mr-2 flex-fill" id="cardOngkirPerusahaan">
                                    <input type="radio" name="ongkir_ditanggung" value="PERUSAHAAN" <?= ($cur_ongkir === 'PERUSAHAAN') ? 'checked' : '' ?>>
                                    <span class="font-weight-bold">Perusahaan (Free Ongkir)</span>
                                </label>
                                <label class="custom-radio-card flex-fill" id="cardOngkirCustomer">
                                    <input type="radio" name="ongkir_ditanggung" value="CUSTOMER" <?= ($cur_ongkir === 'CUSTOMER') ? 'checked' : '' ?>>
                                    <span class="font-weight-bold">Customer</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sistem Dokumen Pembayaran -->
                    <div class="p-3 border rounded bg-light mb-3">
                        <label class="form-label-custom text-primary mb-2"><i class="fas fa-folder-open mr-1"></i> Sistem Dokumen Pembayaran</label>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label-custom">Bentuk Dokumen</label>
                                <div class="d-flex flex-column">
                                    <label class="mb-2">
                                        <input type="radio" name="dokumen_pembayaran_tipe" value="Hardfile" <?= ($cur_dok_tipe === 'Hardfile') ? 'checked' : '' ?>> <b>Hardfile</b> (Fisik)
                                    </label>
                                    <label>
                                        <input type="radio" name="dokumen_pembayaran_tipe" value="Softfile" <?= ($cur_dok_tipe === 'Softfile') ? 'checked' : '' ?>> <b>Softfile</b> (Digital)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label-custom">Detail Dokumen yang Dilampirkan</label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="PO" <?= in_array('PO', $doc_checked) ? 'checked' : '' ?>> PO Customer</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Kwitansi" <?= in_array('Kwitansi', $doc_checked) ? 'checked' : '' ?>> Kwitansi</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Faktur Pajak" <?= in_array('Faktur Pajak', $doc_checked) ? 'checked' : '' ?>> Faktur Pajak</label>
                                    </div>
                                    <div class="col-6">
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Invoice" <?= in_array('Invoice', $doc_checked) ? 'checked' : '' ?>> Invoice</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Surat Jalan" <?= in_array('Surat Jalan', $doc_checked) ? 'checked' : '' ?>> Surat Jalan (SJ)</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="BAST/SSTB" <?= in_array('BAST/SSTB', $doc_checked) ? 'checked' : '' ?>> BAST / SSTB</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Alamat Pengiriman Dokumen</label>
                                <textarea name="alamat_pengiriman_dokumen" id="alamatPengirimanDokumen" class="form-control form-control-custom" rows="3" placeholder="Masukkan alamat pengiriman dokumen/invoice..."><?= htmlspecialchars($po_edit->alamat_pengiriman_dokumen ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 3: RINCIAN BARANG PESANAN (INTEGRASI INVENTORY) -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-boxes"></i> 3. Rincian Barang / Modalitas (Integrasi Stok Inventory)</h5>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-primary mr-2 shadow-sm font-weight-bold" id="btnBukaCalcInclude" data-toggle="modal" data-target="#modalIncludePpn">
                            <i class="fas fa-calculator mr-1"></i> Hitung Include PPN (Nett)
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" id="btnTambahBarang">
                            <i class="fas fa-plus mr-1"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-items mb-0" id="tableItems">
                            <thead>
                                <tr>
                                    <th style="width: 4%; text-align: center;">No</th>
                                    <th style="width: 40%;">Nama Barang (Type, Ukuran, Warna)</th>
                                    <th style="width: 14%; text-align: right;">Harga Satuan (Rp)</th>
                                    <th style="width: 10%; text-align: center;">Qty</th>
                                    <th style="width: 10%;">Satuan</th>
                                    <th style="width: 17%; text-align: right;">Harga Total (Rp)</th>
                                    <th style="width: 5%; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <?php if ($is_edit && !empty($items_edit)): ?>
                                    <?php foreach ($items_edit as $idx => $it): ?>
                                        <?php
                                            $h_num = (float)($it->harga_satuan ?? 0);
                                            $h_str = (abs($h_num - round($h_num)) > 0.0001)
                                                ? rtrim(rtrim(number_format($h_num, 2, ',', '.'), '0'), ',')
                                                : number_format($h_num, 0, ',', '.');
                                        ?>
                                        <tr class="item-row" data-row="<?= $idx ?>">
                                            <td class="text-center font-weight-bold row-num"><?= $idx + 1 ?></td>
                                            <td>
                                                <input type="text" name="items[<?= $idx ?>][nama_barang]" list="listBarang" class="form-control form-control-custom item-nama" placeholder="Pilih / ketik nama barang..." value="<?= htmlspecialchars($it->nama_barang ?? '') ?>" required>
                                                <input type="hidden" name="items[<?= $idx ?>][id_barang]" class="item-id-barang" value="<?= htmlspecialchars($it->id_barang ?? '') ?>">
                                                <input type="hidden" name="items[<?= $idx ?>][tipe_barang]" class="item-tipe-barang" value="<?= htmlspecialchars($it->tipe_barang ?? '') ?>">
                                                <input type="text" name="items[<?= $idx ?>][catatan]" class="form-control form-control-custom mt-1" style="font-size: 0.78rem;" placeholder="Catatan spesifikasi / nomor seri (opsional)" value="<?= htmlspecialchars($it->catatan ?? '') ?>">
                                            </td>
                                            <td>
                                                <input type="text" name="items[<?= $idx ?>][harga]" class="form-control form-control-custom text-right item-harga mask-rupiah" placeholder="0" value="<?= $h_str ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" step="any" min="1" name="items[<?= $idx ?>][qty]" class="form-control form-control-custom text-center item-qty" value="<?= (float)($it->qty ?? 1) ?>" required>
                                            </td>
                                            <td>
                                                <input type="text" name="items[<?= $idx ?>][satuan]" class="form-control form-control-custom" value="<?= htmlspecialchars($it->satuan ?? 'Unit') ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-custom text-right item-total font-weight-bold" readonly value="Rp 0">
                                                <input type="hidden" name="items[<?= $idx ?>][total]" class="item-total-raw" value="0">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Baris Item 1 -->
                                    <tr class="item-row" data-row="0">
                                        <td class="text-center font-weight-bold row-num">1</td>
                                        <td>
                                            <input type="text" name="items[0][nama_barang]" list="listBarang" class="form-control form-control-custom item-nama" placeholder="Pilih / ketik nama barang..." required>
                                            <input type="hidden" name="items[0][id_barang]" class="item-id-barang">
                                            <input type="hidden" name="items[0][tipe_barang]" class="item-tipe-barang">
                                            <input type="text" name="items[0][catatan]" class="form-control form-control-custom mt-1" style="font-size: 0.78rem;" placeholder="Catatan spesifikasi / nomor seri (opsional)">
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][harga]" class="form-control form-control-custom text-right item-harga mask-rupiah" placeholder="0" required>
                                        </td>
                                        <td>
                                            <input type="number" step="any" min="1" name="items[0][qty]" class="form-control form-control-custom text-center item-qty" value="1" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][satuan]" class="form-control form-control-custom" value="Unit">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-custom text-right item-total font-weight-bold" readonly value="Rp 0">
                                            <input type="hidden" name="items[0][total]" class="item-total-raw" value="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Datalist Master Barang -->
                    <datalist id="listBarang">
                        <?php if (!empty($barang_list)): ?>
                            <?php foreach ($barang_list as $b): ?>
                                <option value="<?= htmlspecialchars($b->nama_barang . (!empty($b->tipe) ? ' - ' . $b->tipe : '')) ?>" data-id="<?= $b->id_barang ?>" data-tipe="<?= htmlspecialchars($b->tipe ?? '') ?>">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist>

                    <div class="px-4 py-2 bg-light border-top d-flex align-items-center justify-content-between flex-wrap">
                        <small class="text-muted py-1">
                            <i class="fas fa-info-circle text-primary mr-1"></i> <b>Tips Harga Include PPN:</b> Kolom <i>Harga Satuan</i> mendukung angka desimal/koma (contoh: <code>4.504.504,5</code>) atau klik tombol <b>"Hitung Include PPN (Nett)"</b> di atas agar total akhir pas genap (misal Rp 5.000.000).
                        </small>
                    </div>

                    <!-- CARD KALKULASI & TOTAL -->
                    <div class="p-4 border-top">
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-lg-5">
                                <div class="summary-card">
                                    <div class="summary-row">
                                        <span class="text-muted font-weight-bold">Subtotal Barang:</span>
                                        <span class="font-weight-bold text-dark" id="displaySubtotal">Rp 0</span>
                                        <input type="hidden" name="nominal_subtotal" id="inputSubtotal" value="0">
                                    </div>
                                    <div class="summary-row" id="rowPpn">
                                        <span class="text-muted font-weight-bold" id="labelPpn">PPN (11%):</span>
                                        <span class="font-weight-bold text-dark" id="displayPpn">Rp 0</span>
                                        <input type="hidden" name="nominal_ppn" id="inputPpn" value="0">
                                    </div>
                                    <div class="summary-row" id="rowOngkir">
                                        <span class="text-muted font-weight-bold">Ongkos Kirim:</span>
                                        <div style="width: 150px;">
                                            <input type="text" name="nominal_ongkir" id="inputOngkir" class="form-control form-control-custom text-right mask-rupiah" placeholder="0" value="<?= $is_edit && !empty($po_edit->nominal_ongkir) ? number_format((float)$po_edit->nominal_ongkir, 0, ',', '.') : '0' ?>">
                                        </div>
                                    </div>
                                    <div class="summary-row total-row">
                                        <span class="text-primary font-weight-bold">TOTAL AKHIR:</span>
                                        <span class="text-primary font-weight-bold" id="displayTotalAkhir">Rp 0</span>
                                        <input type="hidden" name="nominal_total_akhir" id="inputTotalAkhir" value="0">
                                        <input type="hidden" name="nominal_po" id="inputNominalPo" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 4: LAMPIRAN & DATA PENGAJU -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-paperclip"></i> 4. Lampiran Dokumen & Penanggung Jawab</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Link PO Customer (Wajib jika tidak upload file) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Link PO Customer (Google Drive / Cloud) <?= ($is_edit && !empty($po_edit->file_po)) ? '' : '<span class="text-danger">*</span>' ?></label>
                            <input type="url" name="link_po_customer" id="linkPoCustomer" class="form-control form-control-custom" placeholder="https://drive.google.com/file/d/..." value="<?= htmlspecialchars($po_edit->link_po_customer ?? '') ?>" <?= ($is_edit && !empty($po_edit->file_po)) ? '' : 'required' ?>>
                            <small class="help-hint">Scan dokumen PO bertanda tangan pihak Rumah Sakit / Pembeli</small>
                        </div>

                        <!-- Link Penawaran -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Link Penawaran / SPH <span class="text-muted font-weight-normal">(Jika ada)</span></label>
                            <input type="url" name="link_penawaran" class="form-control form-control-custom" placeholder="https://drive.google.com/file/d/..." value="<?= htmlspecialchars($po_edit->link_penawaran ?? '') ?>">
                            <small class="help-hint">Surat Penawaran Harga acuan</small>
                        </div>

                        <!-- Link Approval Harga -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Link Approval Harga <span class="text-muted font-weight-normal">(Jika ada)</span></label>
                            <input type="url" name="link_approval_harga" class="form-control form-control-custom" placeholder="https://drive.google.com/file/d/..." value="<?= htmlspecialchars($po_edit->link_approval_harga ?? '') ?>">
                            <small class="help-hint">Bukti persetujuan harga khusus jika ada diskon</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Upload File PO Fisik Alternatif -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Atau Upload Berkas PO (PDF / Foto)</label>
                            <input type="file" name="file_po" id="filePoInput" class="form-control form-control-custom" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="help-hint">Format PDF, JPG, PNG (Maks 10MB)</small>
                        </div>

                        <!-- Diajukan Oleh (Marketing/Sales) -->
                        <?php $selected_sales_id = $is_edit ? ($po_edit->id_sales ?? sessPenggunaId()) : sessPenggunaId(); ?>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Diajukan Oleh (Marketing / Sales) <span class="text-danger">*</span></label>
                            <select name="id_sales" id="id_sales" data-plugin-selectTwo class="form-control form-control-custom select2" style="width: 100%;" data-placeholder="-- Pilih Sales / Marketing --" required>
                                <?php if (!empty($sales_list)): ?>
                                    <?php foreach ($sales_list as $s): ?>
                                        <option value="<?= $s->pengguna_id ?>" data-nama="<?= htmlspecialchars($s->nama) ?>" <?= ($s->pengguna_id == $selected_sales_id) ? 'selected' : '' ?>><?= htmlspecialchars($s->nama . ' (' . $s->jabatan . ')') ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="nama_sales" id="nama_sales_input" value="<?= htmlspecialchars($is_edit ? ($po_edit->nama_sales ?? '') : ($current_user->nama ?? '')) ?>">
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Catatan Tambahan untuk CRO / Pengadaan</label>
                            <textarea name="keterangan" class="form-control form-control-custom" rows="2" placeholder="Catatan khusus terkait pengiriman, batas waktu, dll..."><?= htmlspecialchars($po_edit->keterangan ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light p-3 d-flex justify-content-between align-items-center">
                    <a href="<?= $is_edit ? base_url('po_system/detail/' . ($id_encrypt ?? '')) : base_url('po_system') ?>" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm" id="btnSubmit">
                        <i class="fas fa-paper-plane mr-2"></i> <?= $is_edit ? 'Simpan Perubahan & Ajukan Ulang PO' : 'Ajukan PO Masuk ke CRO' ?>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- MODAL HITUNG INCLUDE PPN (NETT) -->
<div class="modal fade" id="modalIncludePpn" tabindex="-1" role="dialog" aria-labelledby="modalIncludePpnLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.18);">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title font-weight-bold" id="modalIncludePpnLabel">
                    <i class="fas fa-calculator mr-2"></i>Kalkulator Harga Include PPN (11%)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info py-2 px-3 small mb-3" style="border-radius: 8px;">
                    <i class="fas fa-lightbulb mr-1"></i> Gunakan alat ini jika customer / RS memesan dengan total nilai bersih sudah termasuk PPN (misal pas <b>Rp 5.000.000</b>). Sistem akan menghitung harga dasar (DPP) dan PPN secara presisi tanpa selisih Rp 1.
                </div>
                <div class="form-group mb-3">
                    <label class="form-label-custom font-weight-bold text-dark">Target Total Nilai Include PPN (Rp) <span class="text-danger">*</span></label>
                    <input type="text" id="calcTargetTotal" class="form-control form-control-custom font-weight-bold text-primary" style="font-size: 1.15rem;" placeholder="Contoh: 5.000.000">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label-custom font-weight-bold text-dark">Jumlah Qty Barang</label>
                    <input type="number" id="calcTargetQty" class="form-control form-control-custom text-center" value="1" min="1" step="any">
                </div>
                
                <div class="p-3 rounded bg-light border mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small font-weight-bold">Harga Satuan DPP (Dasar):</span>
                        <span class="font-weight-bold text-dark" id="calcPreviewHargaSatuan">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Subtotal DPP:</span>
                        <span class="text-muted" id="calcPreviewSubtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small font-weight-bold">PPN (11%):</span>
                        <span class="font-weight-bold text-dark" id="calcPreviewPpn">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                        <span class="font-weight-bold text-primary" style="font-size: 1.05rem;">Total Akhir Pas:</span>
                        <span class="font-weight-bold text-primary" style="font-size: 1.05rem;" id="calcPreviewTotal">Rp 0</span>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label-custom font-weight-bold text-dark">Terapkan ke Baris Barang:</label>
                    <select id="calcSelectRow" class="form-control form-control-custom">
                        <option value="0">Baris 1</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light p-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-3" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success font-weight-bold px-4 shadow-sm" id="btnApplyIncludePpn">
                    <i class="fas fa-check mr-1"></i> Terapkan ke Form PO
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Auto-fill Data Customer saat dipilih (Support Vanilla & Select2)
    function onCustomerChange() {
        var $select = $('#selectCustomer');
        var opt = $select.find('option:selected');
        if (opt.length && opt.val()) {
            var nama = opt.attr('data-nama') || opt.text().trim();
            var alamat = opt.attr('data-alamat') || '';
            var contact = opt.attr('data-contact') || '';
            var npwp = opt.attr('data-npwp') || '';

            $('#namaCustomerInput').val(nama);
            $('#alamatCustomer').val(alamat);
            if (npwp) {
                $('#npwpCustomer').val(npwp);
                var $npwpKtp = $('#noNpwpKtp');
                if ($npwpKtp.length && !$npwpKtp.val()) {
                    $npwpKtp.val(npwp);
                }
            }

            // Auto pre-fill PIC Penerima Nama & No HP jika tersedia di master customer
            var namaPic = '';
            var hpPic = '';
            if (contact) {
                var phoneMatch = contact.match(/(?:(?:\+62|62|0)[0-9\s\-]{8,16})/);
                if (phoneMatch) {
                    hpPic = phoneMatch[0].trim();
                    namaPic = contact.replace(phoneMatch[0], '').replace(/(?:Up\.?|Hp\.?|Telp\.?|PIC\s*:?|[\(\)\:\-\n\r])/gi, ' ').trim();
                } else {
                    namaPic = contact.replace(/(?:Up\.?|PIC\s*:?|[\:\-\n\r])/gi, ' ').trim();
                }
                namaPic = namaPic.replace(/\s+/g, ' ');
            }
            var $picNama = $('input[name="pic_penerima_nama"]');
            if ($picNama.length && (!$picNama.val() || $picNama.data('autofilled'))) {
                if (namaPic) {
                    $picNama.val(namaPic).data('autofilled', true);
                } else {
                    $picNama.val('').data('autofilled', false);
                }
            }
            var $picHp = $('input[name="pic_penerima_nohp"]');
            if ($picHp.length && (!$picHp.val() || $picHp.data('autofilled'))) {
                if (hpPic) {
                    $picHp.val(hpPic).data('autofilled', true);
                } else if (contact && !namaPic) {
                    $picHp.val(contact).data('autofilled', true);
                } else {
                    $picHp.val('').data('autofilled', false);
                }
            }
        } else {
            $('#namaCustomerInput').val('');
            $('#alamatCustomer').val('');
        }
    }

    // Inisialisasi Select2 dengan pencarian cepat dan styling rapi
    if (window.jQuery && $.fn.select2) {
        $('.select2').each(function () {
            var $this = $(this);
            if (!$this.hasClass('select2-hidden-accessible')) {
                var ph = $this.data('placeholder') || '-- Pilih Data --';
                $this.select2({
                    theme: 'bootstrap',
                    width: '100%',
                    placeholder: ph,
                    allowClear: true
                });
            }
        });

        $('#selectCustomer').on('change select2:select select2:clear', onCustomerChange);
    } else if (window.jQuery) {
        $('#selectCustomer').on('change', onCustomerChange);
    } else {
        var selectCust = document.getElementById('selectCustomer');
        if (selectCust) {
            selectCust.addEventListener('change', onCustomerChange);
        }
    }

    // 2. Tombol Salin Alamat Customer ke Alamat Pengiriman
    var btnSalin = document.getElementById('btnSalinAlamat');
    if (btnSalin) {
        btnSalin.addEventListener('click', function () {
            var alCust = document.getElementById('alamatCustomer').value;
            if (alCust) {
                document.getElementById('alamatPengiriman').value = alCust;
            }
        });
    }

    // 3. Conditional Cicilan
    var radioPayments = document.querySelectorAll('input[name="sistem_pembayaran"]');
    var boxCicilan = document.getElementById('boxCicilan');
    radioPayments.forEach(function (r) {
        r.addEventListener('change', function () {
            if (this.value === 'Cicilan') {
                boxCicilan.style.display = 'flex';
            } else {
                boxCicilan.style.display = 'none';
            }
        });
    });

    // 4. Update nama sales saat dropdown sales berubah
    function syncNamaSales() {
        var selectSales = document.getElementById('id_sales');
        if (selectSales && selectSales.selectedIndex >= 0) {
            var opt = selectSales.options[selectSales.selectedIndex];
            if (opt) {
                document.getElementById('nama_sales_input').value = opt.getAttribute('data-nama') || '';
            }
        }
    }
    var selectSales = document.getElementById('id_sales');
    if (selectSales) {
        selectSales.addEventListener('change', syncNamaSales);
        if (window.jQuery) {
            $('#id_sales').on('change select2:select', syncNamaSales);
        }
        syncNamaSales();
    }

    // 5. Format & Kalkulasi Barang & Total Realtime
    function formatCurrencyInput(valStr) {
        if (!valStr && valStr !== 0) return '';
        var s = valStr.toString().trim();
        
        // Toleransi jika user ketik / paste titik sebagai desimal (termasuk numpad dot ".")
        // Jika tidak ada koma, titik terakhir dengan 0-2 digit di belakangnya otomatis dijadikan koma desimal
        if (s.indexOf(',') === -1 && s.indexOf('.') !== -1) {
            var lastDot = s.lastIndexOf('.');
            var afterDot = s.substring(lastDot + 1);
            if (afterDot.length <= 2) {
                s = s.substring(0, lastDot) + ',' + afterDot;
            }
        }

        var hasComma = s.indexOf(',') !== -1;
        var parts = s.split(',');
        
        var intClean = parts[0].replace(/[^0-9]/g, '');
        if (!intClean && !hasComma) return '';
        
        var intFormatted = intClean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        if (!intFormatted && hasComma) intFormatted = '0';
        
        if (hasComma) {
            var decClean = parts.length > 1 ? parts[1].replace(/[^0-9]/g, '').slice(0, 2) : '';
            return intFormatted + ',' + decClean;
        }
        
        return intFormatted;
    }

    function parseRupiah(str) {
        if (!str && str !== 0) return 0;
        var s = str.toString().trim();
        if (s.indexOf(',') === -1 && s.indexOf('.') !== -1) {
            var lastDot = s.lastIndexOf('.');
            var afterDot = s.substring(lastDot + 1);
            if (afterDot.length <= 2) {
                s = s.substring(0, lastDot) + ',' + afterDot;
            }
        }
        if (s.indexOf(',') !== -1) {
            var parts = s.split(',');
            var intPart = parts[0].replace(/[^0-9]/g, '') || '0';
            var decPart = parts[1].replace(/[^0-9]/g, '') || '0';
            return parseFloat(intPart + '.' + decPart) || 0;
        }
        var clean = s.replace(/[^0-9]/g, '');
        return parseFloat(clean) || 0;
    }

    function formatRupiah(num) {
        if (num === null || num === undefined || isNaN(num)) return 'Rp 0';
        var isNeg = num < 0;
        var n = Math.abs(num);
        var isDecimal = (Math.abs(n - Math.round(n)) > 0.0001);
        if (isDecimal) {
            var parts = n.toFixed(2).split('.');
            var intFormatted = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            var decFormatted = parts[1].replace(/0+$/, '');
            if (!decFormatted) decFormatted = '0';
            return (isNeg ? '-Rp ' : 'Rp ') + intFormatted + ',' + decFormatted;
        } else {
            return (isNeg ? '-Rp ' : 'Rp ') + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }

    function recalculate() {
        var subtotal = 0;
        var rows = document.querySelectorAll('#itemsBody tr.item-row');
        rows.forEach(function (row) {
            var qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            var harga = parseRupiah(row.querySelector('.item-harga').value);
            var total = Math.round(qty * harga * 100) / 100;
            row.querySelector('.item-total').value = formatRupiah(total);
            row.querySelector('.item-total-raw').value = total;
            subtotal += total;
        });

        subtotal = Math.round(subtotal * 100) / 100;

        // Cek PPN / Endorsement (PPh 11%)
        var sistemPajak = 'PPN';
        var radioPajak = document.querySelector('input[name="sistem_perpajakan"]:checked');
        if (radioPajak) {
            sistemPajak = radioPajak.value;
        }

        var ppn = 0;
        var rowPpn = document.getElementById('rowPpn');
        var labelPpn = document.getElementById('labelPpn');

        if (sistemPajak === 'PPN') {
            ppn = Math.round(subtotal * 0.11 * 100) / 100;
            if (labelPpn) labelPpn.innerText = 'PPN (11%):';
            rowPpn.style.display = 'flex';
        } else if (sistemPajak === 'ENDORSEMENT') {
            ppn = Math.round(subtotal * 0.11 * 100) / 100;
            if (labelPpn) labelPpn.innerText = 'Pajak (11%):';
            rowPpn.style.display = 'flex';
        } else {
            rowPpn.style.display = 'none';
        }

        var ongkir = parseRupiah(document.getElementById('inputOngkir').value);
        var totalAkhir = Math.round((subtotal + ppn + ongkir) * 100) / 100;

        document.getElementById('displaySubtotal').innerText = formatRupiah(subtotal);
        document.getElementById('inputSubtotal').value = subtotal;

        document.getElementById('displayPpn').innerText = formatRupiah(ppn);
        document.getElementById('inputPpn').value = ppn;

        document.getElementById('displayTotalAkhir').innerText = formatRupiah(totalAkhir);
        document.getElementById('inputTotalAkhir').value = totalAkhir;
        document.getElementById('inputNominalPo').value = totalAkhir;
    }

    // Event listener untuk perpajakan
    var radioPajaks = document.querySelectorAll('input[name="sistem_perpajakan"]');
    radioPajaks.forEach(function (r) {
        r.addEventListener('change', recalculate);
    });

    // Event listener ongkir
    var inputOngkir = document.getElementById('inputOngkir');
    if (inputOngkir) {
        inputOngkir.addEventListener('input', function () {
            var val = formatCurrencyInput(this.value);
            this.value = val;
            recalculate();
        });
    }

    // Map master barang dari datalist untuk auto-fill id_barang & tipe_barang
    var barangMap = {};
    document.querySelectorAll('#listBarang option').forEach(function (opt) {
        var val = (opt.getAttribute('value') || '').trim().toLowerCase();
        if (val) {
            barangMap[val] = {
                id: opt.getAttribute('data-id') || '',
                tipe: opt.getAttribute('data-tipe') || ''
            };
        }
    });

    var filePoInput = document.getElementById('filePoInput');
    var linkPoCustomer = document.getElementById('linkPoCustomer');
    if (filePoInput && linkPoCustomer) {
        filePoInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                linkPoCustomer.removeAttribute('required');
            } else if (!linkPoCustomer.value) {
                linkPoCustomer.setAttribute('required', 'required');
            }
        });
    }

    // Format input harga satuan & sinkronisasi master barang
    function bindRowEvents(row) {
        var inputNama = row.querySelector('.item-nama');
        var inputIdBarang = row.querySelector('.item-id-barang');
        var inputTipeBarang = row.querySelector('.item-tipe-barang');
        var inputHarga = row.querySelector('.item-harga');
        var inputQty = row.querySelector('.item-qty');
        var btnHapus = row.querySelector('.btn-hapus-row');

        if (inputNama) {
            function syncBarangMeta() {
                var key = inputNama.value.trim().toLowerCase();
                if (barangMap[key]) {
                    if (inputIdBarang) inputIdBarang.value = barangMap[key].id;
                    if (inputTipeBarang) inputTipeBarang.value = barangMap[key].tipe;
                } else {
                    if (inputIdBarang) inputIdBarang.value = '';
                }
            }
            inputNama.addEventListener('input', syncBarangMeta);
            inputNama.addEventListener('change', syncBarangMeta);
        }

        inputHarga.addEventListener('input', function () {
            var val = formatCurrencyInput(this.value);
            this.value = val;
            recalculate();
        });

        inputQty.addEventListener('input', recalculate);

        btnHapus.addEventListener('click', function () {
            var allRows = document.querySelectorAll('#itemsBody tr.item-row');
            if (allRows.length > 1) {
                row.remove();
                renumberRows();
                recalculate();
            } else {
                alert('Minimal harus ada 1 baris barang!');
            }
        });
    }

    // 6. Kalkulator Include PPN Modal Logic
    var btnBukaModal = document.getElementById('btnBukaCalcInclude');
    if (btnBukaModal) {
        btnBukaModal.addEventListener('click', function () {
            var selectRow = document.getElementById('calcSelectRow');
            if (selectRow) {
                selectRow.innerHTML = '';
                var rows = document.querySelectorAll('#itemsBody tr.item-row');
                rows.forEach(function (r, idx) {
                    var nama = r.querySelector('.item-nama').value.trim() || ('Baris ' + (idx + 1));
                    var opt = document.createElement('option');
                    opt.value = idx;
                    opt.innerText = 'Baris ' + (idx + 1) + ': ' + nama;
                    selectRow.appendChild(opt);
                });
            }
            updateCalcPreview();
        });
    }

    function updateCalcPreview() {
        var targetTotal = parseRupiah(document.getElementById('calcTargetTotal').value);
        var qty = parseFloat(document.getElementById('calcTargetQty').value) || 1;
        if (targetTotal <= 0) {
            document.getElementById('calcPreviewHargaSatuan').innerText = 'Rp 0';
            document.getElementById('calcPreviewSubtotal').innerText = 'Rp 0';
            document.getElementById('calcPreviewPpn').innerText = 'Rp 0';
            document.getElementById('calcPreviewTotal').innerText = 'Rp 0';
            return;
        }

        // DPP = Total / 1.11
        var totalDpp = Math.round((targetTotal / 1.11) * 100) / 100;
        var hargaSatuan = Math.round((totalDpp / qty) * 100) / 100;
        var subtotalAktual = Math.round(hargaSatuan * qty * 100) / 100;
        var ppnAktual = Math.round(subtotalAktual * 0.11 * 100) / 100;
        var totalGenap = Math.round((subtotalAktual + ppnAktual) * 100) / 100;

        document.getElementById('calcPreviewHargaSatuan').innerText = formatRupiah(hargaSatuan);
        document.getElementById('calcPreviewSubtotal').innerText = formatRupiah(subtotalAktual);
        document.getElementById('calcPreviewPpn').innerText = formatRupiah(ppnAktual);
        document.getElementById('calcPreviewTotal').innerText = formatRupiah(totalGenap);
    }

    var inputCalcTargetTotal = document.getElementById('calcTargetTotal');
    if (inputCalcTargetTotal) {
        inputCalcTargetTotal.addEventListener('input', function () {
            this.value = formatCurrencyInput(this.value);
            updateCalcPreview();
        });
    }

    var inputCalcTargetQty = document.getElementById('calcTargetQty');
    if (inputCalcTargetQty) {
        inputCalcTargetQty.addEventListener('input', updateCalcPreview);
    }

    var btnApplyInclude = document.getElementById('btnApplyIncludePpn');
    if (btnApplyInclude) {
        btnApplyInclude.addEventListener('click', function () {
            var targetTotal = parseRupiah(document.getElementById('calcTargetTotal').value);
            var qty = parseFloat(document.getElementById('calcTargetQty').value) || 1;
            if (targetTotal <= 0) {
                alert('Silakan masukkan target total nilai PO!');
                return;
            }

            var totalDpp = Math.round((targetTotal / 1.11) * 100) / 100;
            var hargaSatuan = Math.round((totalDpp / qty) * 100) / 100;
            var rowIdx = parseInt(document.getElementById('calcSelectRow').value) || 0;

            var targetRow = document.querySelector('#itemsBody tr.item-row[data-row="' + rowIdx + '"]');
            if (targetRow) {
                targetRow.querySelector('.item-qty').value = qty;
                targetRow.querySelector('.item-harga').value = formatCurrencyInput(hargaSatuan.toFixed(2).replace('.', ','));
                recalculate();
                if (window.jQuery && $('#modalIncludePpn').modal) {
                    $('#modalIncludePpn').modal('hide');
                } else {
                    document.getElementById('modalIncludePpn').classList.remove('show');
                }
            }
        });
    }

    function renumberRows() {
        var rows = document.querySelectorAll('#itemsBody tr.item-row');
        rows.forEach(function (row, idx) {
            row.setAttribute('data-row', idx);
            row.querySelector('.row-num').innerText = (idx + 1);
            row.querySelector('.item-nama').name = 'items[' + idx + '][nama_barang]';
            row.querySelector('.item-id-barang').name = 'items[' + idx + '][id_barang]';
            row.querySelector('.item-tipe-barang').name = 'items[' + idx + '][tipe_barang]';
            row.querySelector('input[name*="[catatan]"]').name = 'items[' + idx + '][catatan]';
            row.querySelector('.item-harga').name = 'items[' + idx + '][harga]';
            row.querySelector('.item-qty').name = 'items[' + idx + '][qty]';
            row.querySelector('input[name*="[satuan]"]').name = 'items[' + idx + '][satuan]';
            row.querySelector('.item-total-raw').name = 'items[' + idx + '][total]';
        });
    }

    // Bind initial row
    document.querySelectorAll('#itemsBody tr.item-row').forEach(bindRowEvents);

    // Tambah Baris Barang
    var btnTambah = document.getElementById('btnTambahBarang');
    if (btnTambah) {
        btnTambah.addEventListener('click', function () {
            var tbody = document.getElementById('itemsBody');
            var rowCount = tbody.querySelectorAll('tr.item-row').length;
            var newTr = document.createElement('tr');
            newTr.className = 'item-row';
            newTr.setAttribute('data-row', rowCount);
            newTr.innerHTML = `
                <td class="text-center font-weight-bold row-num">${rowCount + 1}</td>
                <td>
                    <input type="text" name="items[${rowCount}][nama_barang]" list="listBarang" class="form-control form-control-custom item-nama" placeholder="Pilih / ketik nama barang..." required>
                    <input type="hidden" name="items[${rowCount}][id_barang]" class="item-id-barang">
                    <input type="hidden" name="items[${rowCount}][tipe_barang]" class="item-tipe-barang">
                    <input type="text" name="items[${rowCount}][catatan]" class="form-control form-control-custom mt-1" style="font-size: 0.78rem;" placeholder="Catatan spesifikasi / nomor seri (opsional)">
                </td>
                <td>
                    <input type="text" name="items[${rowCount}][harga]" class="form-control form-control-custom text-right item-harga mask-rupiah" placeholder="0" required>
                </td>
                <td>
                    <input type="number" step="any" min="1" name="items[${rowCount}][qty]" class="form-control form-control-custom text-center item-qty" value="1" required>
                </td>
                <td>
                    <input type="text" name="items[${rowCount}][satuan]" class="form-control form-control-custom" value="Unit">
                </td>
                <td>
                    <input type="text" class="form-control form-control-custom text-right item-total font-weight-bold" readonly value="Rp 0">
                    <input type="hidden" name="items[${rowCount}][total]" class="item-total-raw" value="0">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-row" title="Hapus"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(newTr);
            bindRowEvents(newTr);
        });
    }

    // Initial calculation
    recalculate();
});
</script>
