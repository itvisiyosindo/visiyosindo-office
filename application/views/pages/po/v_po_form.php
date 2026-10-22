<header class="page-header">
    <h2><i class="fas fa-file-invoice text-primary mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
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

        <form action="<?= base_url('po_system/simpan') ?>" method="post" enctype="multipart/form-data" id="formPoMasuk">
            
            <!-- CARD 1: INFORMASI PO & DATA CUSTOMER -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-building"></i> 1. Informasi PO & Data Pelanggan / Customer</h5>
                    <span class="badge badge-info text-white badge-pill-soft">Kode: <?= $kode_masuk_auto ?></span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Nomor PO -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Nomor PO Customer <span class="text-danger">*</span></label>
                            <input type="text" name="no_po" class="form-control form-control-custom" placeholder="Contoh: 045/PO-RSUD/IX/2026" required autofocus>
                            <small class="help-hint">Nomor resmi yang tertera pada lembar PO Customer</small>
                        </div>

                        <!-- Tanggal PO -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Tanggal Surat PO <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_po" class="form-control form-control-custom" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Tanggal Diterima -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Tanggal Diterima Marketing</label>
                            <input type="date" name="tanggal_terima" class="form-control form-control-custom" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Pilih Customer dari Master Pelanggan -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label-custom">
                                Nama Customer / Pelanggan <span class="text-danger">*</span>
                                <span class="text-muted font-weight-normal">(Ambil dari Data Master Customer)</span>
                            </label>
                            <select name="id_pelanggan" id="selectCustomer" class="form-control form-control-custom select2" style="width: 100%;">
                                <option value="">-- Pilih Customer / Pelanggan --</option>
                                <?php if (!empty($pelanggan_list)): ?>
                                    <?php foreach ($pelanggan_list as $pel): ?>
                                        <option value="<?= $pel->id_pelanggan ?>" 
                                                data-nama="<?= htmlspecialchars($pel->identitas_pelanggan) ?>"
                                                data-alamat="<?= htmlspecialchars($pel->alamat ?? '') ?>"
                                                data-npwp="<?= htmlspecialchars($pel->npwp ?? '') ?>"
                                                data-doc="<?= htmlspecialchars($pel->pengiriman_dokumen ?? 'Hardfile') ?>"
                                                data-docaddr="<?= htmlspecialchars($pel->alamat_pengiriman_dokumen ?? '') ?>">
                                            <?= $pel->identitas_pelanggan ?> <?= !empty($pel->npwp) ? ' (NPWP: ' . $pel->npwp . ')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="nama_customer" id="namaCustomerInput">
                            <div class="mt-1">
                                <small class="text-warning font-italic">
                                    <i class="fas fa-info-circle mr-1"></i>Jika customer belum terdaftar, Marketing input data pelanggan dan konfirmasi ke CRO untuk registrasi master.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Alamat Customer (Otomatis) -->
                        <div class="col-md-8 mb-3">
                            <label class="form-label-custom">Alamat Customer <span class="badge badge-secondary badge-pill-soft ml-1">Otomatis dari Master</span></label>
                            <textarea name="alamat_customer" id="alamatCustomer" class="form-control form-control-custom" rows="2" placeholder="Otomatis terisi dari data master customer..."></textarea>
                        </div>

                        <!-- NPWP Customer (Otomatis) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">NPWP Customer <span class="badge badge-secondary badge-pill-soft ml-1">Otomatis</span></label>
                            <input type="text" name="npwp_customer" id="npwpCustomer" class="form-control form-control-custom" placeholder="Otomatis dari master...">
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
                            <textarea name="alamat_pengiriman" id="alamatPengiriman" class="form-control form-control-custom" rows="2" placeholder="Masukkan alamat lengkap tujuan pengiriman fisik barang (Rumah Sakit / Lab / Klinik / Ruangan / Gedung)..." required></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <!-- PIC Penerima Barang -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light">
                                <label class="form-label-custom text-primary"><i class="fas fa-user-check mr-1"></i> PIC Penerima Barang</label>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" name="pic_penerima_nama" class="form-control form-control-custom" placeholder="Nama PIC Penerima" required>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" name="pic_penerima_nohp" class="form-control form-control-custom" placeholder="No. HP / WhatsApp PIC" required>
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
                                        <input type="text" name="pic_penagihan_nama" class="form-control form-control-custom" placeholder="Nama PIC Penagihan" required>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <input type="text" name="pic_penagihan_nohp" class="form-control form-control-custom" placeholder="No. HP / WhatsApp Penagihan" required>
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
                    <!-- Sistem Pembayaran -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label-custom">Sistem Pembayaran <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="custom-radio-card" id="cardPayCash">
                                        <input type="radio" name="sistem_pembayaran" value="Cash" checked>
                                        <div>
                                            <span class="font-weight-bold d-block">Cash / Lunas</span>
                                            <small class="text-muted">Pembayaran langsung / tunai saat barang diterima</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="custom-radio-card" id="cardPayTempo">
                                        <input type="radio" name="sistem_pembayaran" value="Tempo">
                                        <div>
                                            <span class="font-weight-bold d-block">Tempo</span>
                                            <small class="text-muted">Pembayaran bertenggang (30 / 60 hari)</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="custom-radio-card" id="cardPayCicilan">
                                        <input type="radio" name="sistem_pembayaran" value="Cicilan">
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
                    <div class="row mb-3 p-3 border rounded bg-light" id="boxCicilan" style="display: none;">
                        <div class="col-md-6 mb-2">
                            <label class="form-label-custom">Uang Muka / DP (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="cicilan_dp_persen" class="form-control form-control-custom" placeholder="Contoh: 30" value="0">
                                <div class="input-group-append"><span class="input-group-text">%</span></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label-custom">Jumlah Cicilan</label>
                            <div class="input-group">
                                <input type="number" name="cicilan_kali" class="form-control form-control-custom" placeholder="Contoh: 3" value="0">
                                <div class="input-group-append"><span class="input-group-text">Kali (X)</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Sistem Perpajakan -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Sistem Perpajakan <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <label class="custom-radio-card mr-2 flex-fill" id="cardPajakPpn">
                                    <input type="radio" name="sistem_perpajakan" value="PPN" checked>
                                    <span class="font-weight-bold">PPN (11%)</span>
                                </label>
                                <label class="custom-radio-card mr-2 flex-fill" id="cardPajakNonPpn">
                                    <input type="radio" name="sistem_perpajakan" value="NON PPN">
                                    <span class="font-weight-bold">NON PPN</span>
                                </label>
                                <label class="custom-radio-card flex-fill" id="cardPajakEndorsement">
                                    <input type="radio" name="sistem_perpajakan" value="ENDORSEMENT">
                                    <span class="font-weight-bold">ENDORSEMENT</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">No. NPWP Pajak / KTP</label>
                            <input type="text" name="no_npwp_ktp" class="form-control form-control-custom" placeholder="Masukkan nomor NPWP atau NIK KTP untuk faktur">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Informasi Pengiriman Faktur Pajak -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Informasi Pengiriman Faktur Pajak</label>
                            <div class="d-flex">
                                <label class="custom-radio-card mr-2 flex-fill">
                                    <input type="radio" name="info_pengiriman_faktur" value="DIMUKA" checked>
                                    <span class="font-weight-bold">Di Muka</span>
                                </label>
                                <label class="custom-radio-card flex-fill">
                                    <input type="radio" name="info_pengiriman_faktur" value="SAAT PELUNASAN">
                                    <span class="font-weight-bold">Saat Pelunasan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Ongkos Kirim Ditanggung Oleh -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Ongkos Kirim Ditanggung Oleh</label>
                            <div class="d-flex">
                                <label class="custom-radio-card mr-2 flex-fill" id="cardOngkirPerusahaan">
                                    <input type="radio" name="ongkir_ditanggung" value="PERUSAHAAN" checked>
                                    <span class="font-weight-bold">Perusahaan (Free Ongkir)</span>
                                </label>
                                <label class="custom-radio-card flex-fill" id="cardOngkirCustomer">
                                    <input type="radio" name="ongkir_ditanggung" value="CUSTOMER">
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
                                        <input type="radio" name="dokumen_pembayaran_tipe" value="Hardfile" checked> <b>Hardfile</b> (Fisik)
                                    </label>
                                    <label>
                                        <input type="radio" name="dokumen_pembayaran_tipe" value="Softfile"> <b>Softfile</b> (Digital)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label-custom">Detail Dokumen yang Dilampirkan</label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="PO" checked> PO Customer</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Kwitansi" checked> Kwitansi</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Faktur Pajak" checked> Faktur Pajak</label>
                                    </div>
                                    <div class="col-6">
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Invoice" checked> Invoice</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="Surat Jalan"> Surat Jalan (SJ)</label>
                                        <label class="d-block mb-1"><input type="checkbox" name="dokumen_dilampirkan[]" value="BAST/SSTB"> BAST / SSTB</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Alamat Pengiriman Dokumen</label>
                                <textarea name="alamat_pengiriman_dokumen" id="alamatPengirimanDokumen" class="form-control form-control-custom" rows="3" placeholder="Masukkan alamat pengiriman dokumen/invoice..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 3: RINCIAN BARANG PESANAN (INTEGRASI INVENTORY) -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-boxes"></i> 3. Rincian Barang / Modalitas (Integrasi Stok Inventory)</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="btnTambahBarang">
                        <i class="fas fa-plus mr-1"></i> Tambah Baris
                    </button>
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
                                        <span class="text-muted font-weight-bold">PPN (11%):</span>
                                        <span class="font-weight-bold text-dark" id="displayPpn">Rp 0</span>
                                        <input type="hidden" name="nominal_ppn" id="inputPpn" value="0">
                                    </div>
                                    <div class="summary-row" id="rowOngkir">
                                        <span class="text-muted font-weight-bold">Ongkos Kirim:</span>
                                        <div style="width: 150px;">
                                            <input type="text" name="nominal_ongkir" id="inputOngkir" class="form-control form-control-custom text-right mask-rupiah" placeholder="0" value="0">
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
                        <!-- Link PO Customer (Wajib) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Link PO Customer (Google Drive / Cloud) <span class="text-danger">*</span></label>
                            <input type="url" name="link_po_customer" class="form-control form-control-custom" placeholder="https://drive.google.com/file/d/..." required>
                            <small class="help-hint">Scan dokumen PO bertanda tangan pihak Rumah Sakit / Pembeli</small>
                        </div>

                        <!-- Link Penawaran -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Link Penawaran / SPH <span class="text-muted font-weight-normal">(Jika ada)</span></label>
                            <input type="url" name="link_penawaran" class="form-control form-control-custom" placeholder="https://drive.google.com/file/d/...">
                            <small class="help-hint">Surat Penawaran Harga acuan</small>
                        </div>

                        <!-- Link Approval Harga -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Link Approval Harga <span class="text-muted font-weight-normal">(Jika ada)</span></label>
                            <input type="url" name="link_approval_harga" class="form-control form-control-custom" placeholder="https://drive.google.com/file/d/...">
                            <small class="help-hint">Bukti persetujuan harga khusus jika ada diskon</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Upload File PO Fisik Alternatif -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Atau Upload Berkas PO (PDF / Foto)</label>
                            <input type="file" name="file_po" class="form-control form-control-custom" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="help-hint">Format PDF, JPG, PNG (Maks 10MB)</small>
                        </div>

                        <!-- Diajukan Oleh (Marketing/Sales) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Diajukan Oleh (Marketing / Sales) <span class="text-danger">*</span></label>
                            <select name="id_sales" id="id_sales" class="form-control form-control-custom select2" required>
                                <?php if (!empty($sales_list)): ?>
                                    <?php foreach ($sales_list as $s): ?>
                                        <option value="<?= $s->pengguna_id ?>" data-nama="<?= htmlspecialchars($s->nama) ?>" <?= ($s->pengguna_id == sessPenggunaId()) ? 'selected' : '' ?>>
                                            <?= $s->nama ?> (<?= $s->jabatan ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="nama_sales" id="nama_sales_input" value="<?= htmlspecialchars($current_user->nama ?? '') ?>">
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Catatan Tambahan untuk CRO / Pengadaan</label>
                            <textarea name="keterangan" class="form-control form-control-custom" rows="2" placeholder="Catatan khusus terkait pengiriman, batas waktu, dll..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light p-3 d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('po_system') ?>" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm" id="btnSubmit">
                        <i class="fas fa-paper-plane mr-2"></i> Ajukan PO Masuk ke CRO
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Auto-fill Data Customer saat dipilih
    var selectCust = document.getElementById('selectCustomer');
    if (selectCust) {
        selectCust.addEventListener('change', function () {
            var opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                var nama = opt.getAttribute('data-nama') || opt.text;
                var alamat = opt.getAttribute('data-alamat') || '';
                var npwp = opt.getAttribute('data-npwp') || '';
                var docType = opt.getAttribute('data-doc') || 'Hardfile';
                var docAddr = opt.getAttribute('data-docaddr') || '';

                document.getElementById('namaCustomerInput').value = nama;
                document.getElementById('alamatCustomer').value = alamat;
                document.getElementById('npwpCustomer').value = npwp;
                document.getElementById('alamatPengirimanDokumen').value = docAddr || alamat;

                // Sync radio doc
                var radioDocs = document.querySelectorAll('input[name="dokumen_pembayaran_tipe"]');
                radioDocs.forEach(function (r) {
                    if (r.value.toLowerCase() === docType.toLowerCase()) {
                        r.checked = true;
                    }
                });
            } else {
                document.getElementById('namaCustomerInput').value = '';
                document.getElementById('alamatCustomer').value = '';
                document.getElementById('npwpCustomer').value = '';
            }
        });
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
    var selectSales = document.getElementById('id_sales');
    if (selectSales) {
        selectSales.addEventListener('change', function () {
            var opt = this.options[this.selectedIndex];
            if (opt) {
                document.getElementById('nama_sales_input').value = opt.getAttribute('data-nama') || '';
            }
        });
    }

    // 5. Kalkulasi Barang & Total Realtime
    function parseRupiah(str) {
        if (!str) return 0;
        var clean = str.toString().replace(/[^0-9]/g, '');
        return parseFloat(clean) || 0;
    }

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function recalculate() {
        var subtotal = 0;
        var rows = document.querySelectorAll('#itemsBody tr.item-row');
        rows.forEach(function (row) {
            var qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            var harga = parseRupiah(row.querySelector('.item-harga').value);
            var total = qty * harga;
            row.querySelector('.item-total').value = formatRupiah(total);
            row.querySelector('.item-total-raw').value = total;
            subtotal += total;
        });

        // Cek PPN
        var sistemPajak = 'PPN';
        var radioPajak = document.querySelector('input[name="sistem_perpajakan"]:checked');
        if (radioPajak) {
            sistemPajak = radioPajak.value;
        }

        var ppn = 0;
        if (sistemPajak === 'PPN') {
            ppn = Math.round(subtotal * 0.11);
            document.getElementById('rowPpn').style.display = 'flex';
        } else {
            document.getElementById('rowPpn').style.display = 'none';
        }

        var ongkir = parseRupiah(document.getElementById('inputOngkir').value);
        var totalAkhir = subtotal + ppn + ongkir;

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
            var val = parseRupiah(this.value);
            this.value = val ? val.toLocaleString('id-ID') : '0';
            recalculate();
        });
    }

    // Format input harga satuan
    function bindRowEvents(row) {
        var inputHarga = row.querySelector('.item-harga');
        var inputQty = row.querySelector('.item-qty');
        var btnHapus = row.querySelector('.btn-hapus-row');

        inputHarga.addEventListener('input', function () {
            var val = parseRupiah(this.value);
            this.value = val ? val.toLocaleString('id-ID') : '';
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
