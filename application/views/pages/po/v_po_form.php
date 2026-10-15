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
        --primary-blue: #1565c0;
        --card-radius: 12px;
        --text-dark: #2d3748;
        --text-muted: #718096;
        --border-color: #e2e8f0;
        --bg-light: #f8fafc;
    }

    .card-modern {
        border: 0;
        border-radius: var(--card-radius);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        background: #fff;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .card-header-modern {
        background: #fff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.2rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-modern h5, .card-header-modern h6 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1rem;
        letter-spacing: 0.2px;
    }

    .form-section-title {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }
    .form-section-title i {
        margin-right: 8px;
        color: var(--primary-blue);
    }

    .form-label-modern {
        font-size: 0.85rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control-modern {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.9rem;
        color: #1f2937;
        transition: all 0.2s ease;
    }
    .form-control-modern:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .table-items-modern thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        font-weight: 700;
        padding: 12px 14px;
        border: 0;
    }

    .table-items-modern tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }

    .upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        transition: border-color 0.2s ease;
    }
    .upload-box:hover {
        border-color: #3b82f6;
    }

    .grand-total-banner {
        background: linear-gradient(135deg, #f0f7ff 0%, #e6f0fa 100%);
        border: 1px solid #bee3f8;
        border-radius: 10px;
        padding: 16px 20px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle fa-lg mr-3 text-danger"></i>
                    <div>
                        <strong class="d-block">Gagal Menyimpan:</strong>
                        <span class="small"><?= $this->session->flashdata('error'); ?></span>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('po_system/simpan') ?>" method="POST" enctype="multipart/form-data" id="form-po-baru" autocomplete="off">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <!-- Card 1: Identitas & Customer -->
            <div class="card card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-file-contract text-primary mr-2"></i>Informasi Purchase Order Customer</h5>
                    <span class="badge badge-light border px-2 py-1 small text-muted"><i class="fas fa-info-circle mr-1"></i>Isian wajib tanda (*)</span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label-modern">Nomor PO Customer <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-hashtag text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-modern" name="no_po" id="no_po" placeholder="Contoh: PO/2026/09/001" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label-modern">Tanggal PO (di dokumen) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="far fa-calendar-alt text-muted"></i></span>
                                    </div>
                                    <input type="date" class="form-control form-control-modern" name="tanggal_po" id="tanggal_po" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label-modern">Tanggal Diterima Kantor</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar-check text-muted"></i></span>
                                    </div>
                                    <input type="date" class="form-control form-control-modern" name="tanggal_terima" id="tanggal_terima" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3" style="border-top: 1px dashed #e2e8f0;">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label-modern">Instansi / Customer <span class="text-danger">*</span></label>
                                <select class="form-control form-control-modern select2" name="id_customer" id="select_customer" onchange="pilihCustomer(this)" required>
                                    <option value="">-- Pilih Customer Terdaftar --</option>
                                    <?php foreach ($customers as $c): ?>
                                        <option value="<?= $c->id_customer ?>" data-nama="<?= htmlspecialchars($c->nama_customer, ENT_QUOTES) ?>" data-alamat="<?= htmlspecialchars($c->alamat_customer ?? '', ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($c->nama_customer) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="nama_customer" id="nama_customer">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label-modern">Sales / Marketing Terkait</label>
                                <select class="form-control form-control-modern select2" name="id_sales" id="select_sales" onchange="pilihSales(this)">
                                    <option value="">-- Pilih Sales / Marketing (Opsional) --</option>
                                    <?php foreach ($sales_list as $s): ?>
                                        <option value="<?= $s->pengguna_id ?>" data-nama="<?= htmlspecialchars($s->nama, ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($s->nama) ?> (<?= htmlspecialchars($s->jabatan) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="nama_sales" id="nama_sales">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label-modern">Alamat Pengiriman / Instansi</label>
                        <textarea class="form-control form-control-modern" name="alamat_customer" id="alamat_customer" rows="2" placeholder="Alamat lengkap tujuan pengiriman barang atau instalasi"></textarea>
                    </div>

                    <hr class="my-3" style="border-top: 1px dashed #e2e8f0;">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label-modern"><i class="fas fa-file-pdf text-danger mr-1"></i> Upload Lampiran Berkas PO</label>
                            <div class="upload-box">
                                <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                <div class="font-weight-bold text-dark small mb-1">Pilih File PO dari Komputer</div>
                                <input type="file" class="form-control-file text-center mx-auto" name="file_po" id="file_po" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted d-block mt-2">Format: PDF, JPG, PNG (Maksimal 10 MB)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-modern"><i class="fas fa-link text-info mr-1"></i> Tautan / Link Dokumen PO Cloud</label>
                            <div class="p-3 bg-light rounded border h-100 d-flex flex-column justify-content-center">
                                <span class="small text-muted mb-2">Gunakan opsi ini jika berkas tersimpan di Google Drive, Dropbox, atau Cloud:</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fas fa-globe text-muted"></i></span>
                                    </div>
                                    <input type="url" class="form-control form-control-modern" name="link_file_po" id="link_file_po" placeholder="https://drive.google.com/file/d/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Rincian Item PO -->
            <div class="card card-modern">
                <div class="card-header-modern">
                    <div>
                        <h5><i class="fas fa-boxes text-primary mr-2"></i>Rincian Item / Modalitas / Alat</h5>
                        <small class="text-muted">Masukkan item yang tertera di surat pesanan customer</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-success px-3 shadow-sm" onclick="tambahBarisItem()" style="border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-plus mr-1"></i> Tambah Baris Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-items-modern mb-0" id="table-items">
                            <thead>
                                <tr>
                                    <th width="38%">Deskripsi Nama Barang / Modalitas</th>
                                    <th width="12%" class="text-center">Kuantitas</th>
                                    <th width="12%" class="text-center">Satuan</th>
                                    <th width="18%" class="text-right">Harga Satuan (Rp)</th>
                                    <th width="15%" class="text-right">Subtotal (Rp)</th>
                                    <th width="5%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="item-tbody">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-modern" name="items[0][nama_barang]" placeholder="Contoh: USG Mindray DC-30 / Reagen..." required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm form-control-modern text-center item-qty" name="items[0][qty]" value="1" min="1" step="any" oninput="hitungSubtotal(this)" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm form-control-modern text-center" name="items[0][satuan]" value="Unit">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm form-control-modern text-right item-harga" name="items[0][harga]" value="0" min="0" step="any" oninput="hitungSubtotal(this)" required>
                                    </td>
                                    <td class="text-right align-middle font-weight-bold text-dark subtotal-display" style="font-size: 0.92rem;">
                                        Rp. 0,-
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisItem(this)" title="Hapus Baris" style="border-radius: 6px; width: 30px; height: 30px; padding: 0;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Grand Total Banner -->
                    <div class="p-3 bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center grand-total-banner">
                            <div>
                                <span class="text-uppercase text-muted small font-weight-bold d-block" style="letter-spacing: 0.8px;">Total Nilai Purchase Order</span>
                                <small class="text-muted">Total akumulasi dari seluruh item barang</small>
                            </div>
                            <div class="text-right">
                                <h3 class="font-weight-bold text-primary mb-0" id="grand-total-display">Rp. 0,-</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Catatan & Submit -->
            <div class="card card-modern">
                <div class="card-body p-4">
                    <div class="form-group mb-0">
                        <label class="form-label-modern"><i class="fas fa-comment-alt text-muted mr-1"></i> Catatan / Syarat Khusus (Opsional)</label>
                        <textarea class="form-control form-control-modern" name="keterangan" rows="3" placeholder="Tuliskan syarat termin pembayaran, jadwal pengiriman khusus, garansi, atau catatan penawaran terkait..."></textarea>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('po_system') ?>" class="btn btn-outline-secondary px-4" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px; font-weight: 700;">
                        <i class="fas fa-check-circle mr-2"></i> Simpan Data PO Masuk
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = 1;

    function pilihCustomer(sel) {
        let opt = sel.options[sel.selectedIndex];
        let nama = opt.getAttribute('data-nama') || '';
        let alamat = opt.getAttribute('data-alamat') || '';
        document.getElementById('nama_customer').value = nama;
        if (alamat && !document.getElementById('alamat_customer').value) {
            document.getElementById('alamat_customer').value = alamat;
        }
    }

    function pilihSales(sel) {
        let opt = sel.options[sel.selectedIndex];
        let nama = opt.getAttribute('data-nama') || '';
        document.getElementById('nama_sales').value = nama;
    }

    function tambahBarisItem() {
        let tbody = document.getElementById('item-tbody');
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <input type="text" class="form-control form-control-sm form-control-modern" name="items[${itemIndex}][nama_barang]" placeholder="Contoh: USG Mindray DC-30 / Reagen..." required>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm form-control-modern text-center item-qty" name="items[${itemIndex}][qty]" value="1" min="1" step="any" oninput="hitungSubtotal(this)" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm form-control-modern text-center" name="items[${itemIndex}][satuan]" value="Unit">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm form-control-modern text-right item-harga" name="items[${itemIndex}][harga]" value="0" min="0" step="any" oninput="hitungSubtotal(this)" required>
            </td>
            <td class="text-right align-middle font-weight-bold text-dark subtotal-display" style="font-size: 0.92rem;">
                Rp. 0,-
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisItem(this)" title="Hapus Baris" style="border-radius: 6px; width: 30px; height: 30px; padding: 0;">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        itemIndex++;
    }

    function hapusBarisItem(btn) {
        let tr = btn.closest('tr');
        let tbody = document.getElementById('item-tbody');
        if (tbody.querySelectorAll('tr').length > 1) {
            tr.remove();
            hitungGrandTotal();
        } else {
            alert('Minimal harus terdapat 1 baris item barang pada PO.');
        }
    }

    function hitungSubtotal(elem) {
        let tr = elem.closest('tr');
        let qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
        let harga = parseFloat(tr.querySelector('.item-harga').value) || 0;
        let subtotal = qty * harga;
        tr.querySelector('.subtotal-display').innerText = 'Rp. ' + subtotal.toLocaleString('id-ID') + ',-';
        hitungGrandTotal();
    }

    function hitungGrandTotal() {
        let total = 0;
        document.querySelectorAll('#item-tbody tr').forEach(function(tr) {
            let qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
            let harga = parseFloat(tr.querySelector('.item-harga').value) || 0;
            total += (qty * harga);
        });
        document.getElementById('grand-total-display').innerText = 'Rp. ' + total.toLocaleString('id-ID') + ',-';
    }
</script>
