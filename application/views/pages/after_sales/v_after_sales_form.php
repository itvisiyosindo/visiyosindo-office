<!-- View: v_after_sales_form.php - Formulir Pendaftaran Tiket After Sales Baru -->

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-medical mr-1"></i> Form Pengajuan / Registrasi Tiket After Sales Service
                </h6>
                <a href="<?= base_url('after_sales') ?>" class="btn btn-secondary btn-sm px-3 shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                </a>
            </div>
            <div class="card-body p-4">
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <form action="<?= base_url('after_sales/simpan') ?>" method="POST" id="formAfterSales">
                    <!-- SECTION 1: INFORMASI SURAT & INSTANSI -->
                    <div class="card mb-4 border">
                        <div class="card-header bg-light py-2 font-weight-bold text-dark">
                            <i class="fas fa-info-circle mr-1 text-primary"></i> Informasi Dokumen & Pelanggan
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-dark">Nomor Tiket Servis <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-weight-bold text-primary" name="nomor_tiket" value="<?= htmlspecialchars($nomor_tiket) ?>" readonly>
                                    <small class="text-muted">Nomor di-generate otomatis sistem.</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-dark">Tanggal Masuk / Lapor <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tgl_masuk" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-dark">Jenis Layanan <span class="text-danger">*</span></label>
                                    <select name="jenis_layanan" class="form-control" required>
                                        <option value="Perbaikan / Troubleshooting">Perbaikan / Troubleshooting</option>
                                        <option value="Klaim Garansi">Klaim Garansi</option>
                                        <option value="Maintenance Berkala">Maintenance Berkala</option>
                                        <option value="Penggantian Sparepart">Penggantian Sparepart</option>
                                        <option value="Instalasi & Kalibrasi">Instalasi & Kalibrasi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold text-dark">Pilih Dari Master Pelanggan (Opsional)</label>
                                    <select id="selectPelanggan" class="form-control select2">
                                        <option value="">-- Ketik Nama RS / Klinik / Pelanggan --</option>
                                        <?php if (!empty($pelanggan_list)) {
                                            foreach ($pelanggan_list as $pel) { ?>
                                                <option value="<?= $pel->id ?>" 
                                                        data-nama="<?= htmlspecialchars($pel->nama_pelanggan) ?>" 
                                                        data-alamat="<?= htmlspecialchars($pel->alamat) ?>" 
                                                        data-pic="<?= htmlspecialchars($pel->kontak) ?>" 
                                                        data-telp="<?= htmlspecialchars($pel->no_telp) ?>">
                                                    <?= htmlspecialchars($pel->nama_pelanggan) ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                    <input type="hidden" name="id_pelanggan" id="input_id_pelanggan" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold text-dark">Nama Pelanggan / Rumah Sakit / Instansi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_pelanggan" id="input_nama_pelanggan" placeholder="Contoh: RSUD Kota / Klinik Kimia Farma..." required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold text-dark">Alamat Lengkap</label>
                                    <textarea class="form-control" name="alamat_pelanggan" id="input_alamat_pelanggan" rows="2" placeholder="Alamat instansi / lokasi unit alat..."></textarea>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="font-weight-bold text-dark">Nama PIC / Kontak Pelapor</label>
                                    <input type="text" class="form-control" name="pic_nama" id="input_pic_nama" placeholder="Contoh: Bpk. Budi Santoso">
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="font-weight-bold text-dark">No. HP / WhatsApp PIC</label>
                                    <input type="text" class="form-control" name="pic_kontak" id="input_pic_kontak" placeholder="08123456789">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: DETAIL ALAT & KELUHAN -->
                    <div class="card mb-4 border">
                        <div class="card-header bg-light py-2 font-weight-bold text-dark">
                            <i class="fas fa-stethoscope mr-1 text-primary"></i> Data Alat Kesehatan & Keluhan Kerusakan
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-dark">Nama Alat Medis <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_alat" placeholder="Contoh: X-Ray Mobile, USG, Autoclave..." required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-dark">Merk & Tipe / Model</label>
                                    <input type="text" class="form-control" name="merk_tipe" placeholder="Contoh: EcoRay / UltraView 3000">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-dark">Nomor Seri (Serial Number / SN)</label>
                                    <input type="text" class="form-control" name="nomor_seri" placeholder="Contoh: SN-88291039">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Rincian Keluhan / Gejala Kerusakan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="keluhan" rows="3" placeholder="Tuliskan secara lengkap indikasi error, kode alarm, atau kendala alat..." required></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold text-dark">Tugaskan Teknisi (Opsional)</label>
                                    <select name="id_teknisi" class="form-control select2">
                                        <option value="">-- Pilih Teknisi Penanggung Jawab --</option>
                                        <?php if (!empty($teknisi_list)) {
                                            foreach ($teknisi_list as $tek) { ?>
                                                <option value="<?= $tek->pengguna_id ?>">
                                                    <?= htmlspecialchars($tek->nama) ?> <?= !empty($tek->telepon) ? '(' . htmlspecialchars($tek->telepon) . ')' : '' ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold text-dark">Skema Pembiayaan</label>
                                    <select name="status_pembayaran" class="form-control">
                                        <option value="berbayar">Berbayar (Invoice Perbaikan / Sparepart)</option>
                                        <option value="garansi">Klaim Garansi (Free of Charge)</option>
                                        <option value="free">Gratis / Kebijakan Direksi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: ESTIMASI SPAREPART & JASA SERVIS -->
                    <div class="card mb-4 border">
                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-dark">
                                <i class="fas fa-boxes mr-1 text-primary"></i> Estimasi Sparepart & Jasa Servis (Bisa Diisi Nanti)
                            </span>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnTambahBaris">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="tableItems">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="12%">Kategori</th>
                                            <th width="35%">Deskripsi Sparepart / Jasa</th>
                                            <th width="10%">Qty</th>
                                            <th width="10%">Satuan</th>
                                            <th width="15%">Harga Satuan (Rp)</th>
                                            <th width="13%">Subtotal (Rp)</th>
                                            <th width="5%" class="text-center">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemRows">
                                        <tr>
                                            <td>
                                                <select name="item_jenis[]" class="form-control form-control-sm">
                                                    <option value="sparepart">Sparepart</option>
                                                    <option value="jasa">Jasa</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="item_deskripsi[]" class="form-control form-control-sm" placeholder="Nama suku cadang / tindakan...">
                                            </td>
                                            <td>
                                                <input type="number" name="item_qty[]" class="form-control form-control-sm input-qty" value="1" min="1">
                                            </td>
                                            <td>
                                                <input type="text" name="item_satuan[]" class="form-control form-control-sm" value="Pcs">
                                            </td>
                                            <td>
                                                <input type="number" name="item_harga[]" class="form-control form-control-sm input-harga text-right" value="0" min="0">
                                            </td>
                                            <td class="text-right align-middle font-weight-bold cell-subtotal">
                                                0
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-danger btn-sm btn-hapus-baris" title="Hapus"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light font-weight-bold">
                                            <td colspan="5" class="text-right">Total Estimasi Biaya:</td>
                                            <td class="text-right" id="totalBiayaDisplay">Rp 0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="d-flex justify-content-end align-items-center">
                        <a href="<?= base_url('after_sales') ?>" class="btn btn-secondary mr-2">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4 shadow">
                            <i class="fas fa-save mr-1"></i> Daftarkan Tiket After Sales
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto fill pelanggan dari select dropdown
        $('#selectPelanggan').on('change', function() {
            var opt = $(this).find('option:selected');
            if (opt.val()) {
                $('#input_id_pelanggan').val(opt.val());
                $('#input_nama_pelanggan').val(opt.data('nama'));
                $('#input_alamat_pelanggan').val(opt.data('alamat'));
                $('#input_pic_nama').val(opt.data('pic'));
                $('#input_pic_kontak').val(opt.data('telp'));
            }
        });

        // Hitung total dan subtotal
        function hitungTotal() {
            var total = 0;
            $('#itemRows tr').each(function() {
                var qty = parseFloat($(this).find('.input-qty').val()) || 0;
                var harga = parseFloat($(this).find('.input-harga').val()) || 0;
                var subtotal = qty * harga;
                $(this).find('.cell-subtotal').text(subtotal.toLocaleString('id-ID'));
                total += subtotal;
            });
            $('#totalBiayaDisplay').text('Rp ' + total.toLocaleString('id-ID'));
        }

        $(document).on('input', '.input-qty, .input-harga', function() {
            hitungTotal();
        });

        // Tambah baris item
        $('#btnTambahBaris').on('click', function() {
            var newRow = `<tr>
                <td>
                    <select name="item_jenis[]" class="form-control form-control-sm">
                        <option value="sparepart">Sparepart</option>
                        <option value="jasa">Jasa</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="item_deskripsi[]" class="form-control form-control-sm" placeholder="Nama suku cadang / tindakan...">
                </td>
                <td>
                    <input type="number" name="item_qty[]" class="form-control form-control-sm input-qty" value="1" min="1">
                </td>
                <td>
                    <input type="text" name="item_satuan[]" class="form-control form-control-sm" value="Pcs">
                </td>
                <td>
                    <input type="number" name="item_harga[]" class="form-control form-control-sm input-harga text-right" value="0" min="0">
                </td>
                <td class="text-right align-middle font-weight-bold cell-subtotal">
                    0
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm btn-hapus-baris" title="Hapus"><i class="fas fa-times"></i></button>
                </td>
            </tr>`;
            $('#itemRows').append(newRow);
        });

        // Hapus baris item
        $(document).on('click', '.btn-hapus-baris', function() {
            if ($('#itemRows tr').length > 1) {
                $(this).closest('tr').remove();
                hitungTotal();
            } else {
                $(this).closest('tr').find('input').val('');
                $(this).closest('tr').find('.input-qty').val('1');
                $(this).closest('tr').find('.input-harga').val('0');
                hitungTotal();
            }
        });
    });
</script>
