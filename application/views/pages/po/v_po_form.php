<header class="page-header">
    <h2><i class="fas fa-plus-circle text-primary"></i> <?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard_helpdesk') ?>"><i class="fas fa-home"></i></a></li>
            <li><a href="<?= base_url('po_system') ?>">Daftar PO Masuk</a></li>
            <li><span><?= $page_title ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col-lg-12">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= $this->session->flashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('po_system/simpan') ?>" method="POST" enctype="multipart/form-data" id="form-po-baru" autocomplete="off">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="fas fa-file-invoice mr-2"></i>Informasi Dokumen PO</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">Nomor PO Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="no_po" id="no_po" placeholder="Contoh: PO/2026/09/001" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">Tanggal PO <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_po" id="tanggal_po" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">Tanggal Diterima</label>
                                <input type="date" class="form-control" name="tanggal_terima" id="tanggal_terima" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">Pilih Customer / Instansi <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_customer" id="select_customer" onchange="pilihCustomer(this)" required>
                                    <option value="">-- Pilih Customer --</option>
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
                                <label class="font-weight-bold text-dark">Sales / Marketing Terkait</label>
                                <select class="form-control" name="id_sales" id="select_sales" onchange="pilihSales(this)">
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
                        <label class="font-weight-bold text-dark">Alamat Customer / Pengiriman</label>
                        <textarea class="form-control" name="alamat_customer" id="alamat_customer" rows="2" placeholder="Alamat lengkap instansi / rumah sakit"></textarea>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark"><i class="fas fa-upload mr-1"></i> Upload Berkas PO (PDF / Gambar)</label>
                                <input type="file" class="form-control-file" name="file_po" id="file_po" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted d-block mt-1">Format didukung: PDF, JPG, PNG (Maks 10MB)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark"><i class="fas fa-link mr-1"></i> Atau Link Berkas Eksternal (Google Drive / Cloud)</label>
                                <input type="url" class="form-control" name="link_file_po" id="link_file_po" placeholder="https://drive.google.com/file/d/...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rincian Item PO -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary font-weight-bold"><i class="fas fa-boxes mr-2"></i>Rincian Item / Barang PO</h5>
                    <button type="button" class="btn btn-sm btn-success shadow-sm" onclick="tambahBarisItem()">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="table-items">
                            <thead class="bg-light">
                                <tr>
                                    <th width="35%">Nama Barang / Modalitas / Alat</th>
                                    <th width="12%" class="text-center">Qty</th>
                                    <th width="12%" class="text-center">Satuan</th>
                                    <th width="18%" class="text-right">Harga Satuan (Rp)</th>
                                    <th width="18%" class="text-right">Subtotal (Rp)</th>
                                    <th width="5%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="item-tbody">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="items[0][nama_barang]" placeholder="Nama item / modalitas / reagen" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center item-qty" name="items[0][qty]" value="1" min="1" step="any" oninput="hitungSubtotal(this)" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm text-center" name="items[0][satuan]" value="Unit">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-right item-harga" name="items[0][harga]" value="0" min="0" step="any" oninput="hitungSubtotal(this)" required>
                                    </td>
                                    <td class="text-right align-middle font-weight-bold subtotal-display">
                                        Rp. 0,-
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisItem(this)" title="Hapus Item">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="4" class="text-right py-3" style="font-size: 15px;">TOTAL NOMINAL PO:</td>
                                    <td class="text-right py-3 text-primary" id="grand-total-display" style="font-size: 16px;">Rp. 0,-</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Keterangan / Catatan Tambahan</label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Catatan syarat pembayaran, batas pengiriman, referensi penawaran, dll..."></textarea>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-between py-3">
                    <a href="<?= base_url('po_system') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Simpan Data PO Masuk
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
        if (alamat) {
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
                <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][nama_barang]" placeholder="Nama item / modalitas / reagen" required>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm text-center item-qty" name="items[${itemIndex}][qty]" value="1" min="1" step="any" oninput="hitungSubtotal(this)" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm text-center" name="items[${itemIndex}][satuan]" value="Unit">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm text-right item-harga" name="items[${itemIndex}][harga]" value="0" min="0" step="any" oninput="hitungSubtotal(this)" required>
            </td>
            <td class="text-right align-middle font-weight-bold subtotal-display">
                Rp. 0,-
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisItem(this)" title="Hapus Item">
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
            alert('Minimal harus ada 1 item barang pada PO.');
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
