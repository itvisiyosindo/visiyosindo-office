<header class="page-header">
    <h2><i class="fas fa-bullseye"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    /* CSS Premium styling extension */
    .premium-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        margin-bottom: 25px;
    }
    .premium-card:hover {
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
    }
    .btn-premium {
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.2s;
    }
    .btn-premium-success {
        background-color: #10B981;
        color: white;
        border: none;
    }
    .btn-premium-success:hover {
        background-color: #059669;
        color: white;
        transform: translateY(-1px);
    }
    .btn-premium-primary {
        background-color: #2563EB;
        color: white;
        border: none;
    }
    .btn-premium-primary:hover {
        background-color: #1D4ED8;
        color: white;
        transform: translateY(-1px);
    }
    .custom-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100% !important;
    }
    .custom-table th {
        background-color: #F3F4F6 !important;
        color: #374151 !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #E5E7EB !important;
        padding: 12px 10px !important;
    }
    .custom-table td {
        padding: 12px 10px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #E5E7EB !important;
    }
    .custom-table tbody tr:hover {
        background-color: #F9FAFB !important;
        transition: background-color 0.15s ease;
    }
    .form-control-premium {
        border-radius: 8px;
        border: 1px solid #D1D5DB;
        padding: 8px 12px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control-premium:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }
    .modal-content-premium {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    }
    .modal-header-premium {
        background: #1F2937;
        color: #ffffff;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        padding: 16px 24px;
    }
    .modal-header-premium .close {
        color: #ffffff;
        opacity: 0.8;
    }
    .modal-header-premium .close:hover {
        opacity: 1;
    }
    .modal-footer-premium {
        border-top: 1px solid #F3F4F6;
        padding: 16px 24px;
    }
    .alert-premium-info {
        background-color: #EFF6FF;
        border-left: 4px solid #3B82F6;
        color: #1E3A8A;
        border-radius: 8px;
        padding: 12px 16px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card premium-card">
            <div class="card-body">
                <!-- Filters & Action Bar -->
                <div class="row align-items-center">
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold text-muted small text-uppercase">Tahun</label>
                        <select class="form-control form-control-premium" id="filter_tahun">
                            <?php
                            $current_year = date('Y');
                            for ($y = $current_year - 5; $y <= $current_year + 5; $y++) {
                                $selected = ($y == $current_year) ? 'selected' : '';
                                echo "<option value='{$y}' {$selected}>Tahun berjalan: {$y}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold text-muted small text-uppercase">Bulan</label>
                        <select class="form-control form-control-premium" id="filter_bulan">
                            <option value="all" selected>Semua Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>

                    <?php if ($is_admin_or_leader): ?>
                        <div class="col-md-3 mb-2">
                            <label class="font-weight-bold text-muted small text-uppercase">Marketing</label>
                            <select class="form-control form-control-premium" id="filter_marketing">
                                <option value="all">Semua Marketing</option>
                                <?php foreach ($marketing_list as $m): ?>
                                    <option value="<?= $m->pengguna_id ?>"><?= htmlspecialchars($m->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="<?= $is_admin_or_leader ? 'col-md-3' : 'col-md-6' ?> mb-2 text-right pt-4">
                        <button type="button" id="btn-add-target" class="btn btn-premium btn-premium-success mr-2">
                            <i class="fas fa-plus"></i> Tambah Target
                        </button>
                        <button type="button" id="btn-export-excel" class="btn btn-premium btn-premium-primary">
                            <i class="fas fa-file-excel"></i> Cetak Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card premium-card">
            <div class="card-body">
                <!-- Table View -->
                <div class="table-responsive">
                    <table class="table custom-table table-hover" id="target_table">
                        <thead>
                            <tr>
                                <th style="width: 3%">No</th>
                                <th style="width: 4%">Thn</th>
                                <th style="width: 10%">Marketing</th>
                                <th style="width: 12%">Instansi</th>
                                <th style="width: 8%">Provinsi</th>
                                <th style="width: 8%">Kota</th>
                                <th style="width: 10%">PIC</th>
                                <th style="width: 10%">Unit/Produk</th>
                                <th style="width: 10%">Harga Jual</th>
                                <th style="width: 10%">Harga Permintaan</th>
                                <th style="width: 5%">Gap</th>
                                <th style="width: 5%">Kecapaian</th>
                                <th style="width: 5%">Funnel</th>
                                <th style="width: 8%">Estimasi</th>
                                <th style="width: 10%">Kendala</th>
                                <th style="width: 12%">Kebutuhan Support</th>
                                <th style="width: 8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loaded via DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="modal-target" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <h5 class="modal-title font-weight-bold" id="modal-title-text"><i class="fas fa-bullseye"></i> Form Prospek Target Jangka Pendek</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('', ['id' => 'form-target', 'autocomplete' => 'off']); ?>
            <input type="hidden" name="id" id="target_id">
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Tahun <span class="text-danger">*</span></label>
                        <select name="tahun" id="tahun" class="form-control form-control-premium" required>
                            <?php
                            for ($y = $current_year - 2; $y <= $current_year + 5; $y++) {
                                $selected = ($y == $current_year) ? 'selected' : '';
                                echo "<option value='{$y}' {$selected}>{$y}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <?php if ($is_admin_or_leader): ?>
                        <div class="col-md-8 mb-3">
                            <label class="font-weight-bold">Nama Marketing <span class="text-danger">*</span></label>
                            <select name="marketing_id" id="marketing_id" class="form-control form-control-premium" required>
                                <option value="">- Pilih Marketing -</option>
                                <?php foreach ($marketing_list as $m): ?>
                                    <option value="<?= $m->pengguna_id ?>"><?= htmlspecialchars($m->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="font-weight-bold text-primary"><i class="fas fa-magic"></i> Autofill: Pilih dari Master Calon/Pelanggan (Opsional)</label>
                        <select id="autofill_customer" class="form-control form-control-premium" style="width: 100%;">
                            <option value="">-- Cari & Pilih Customer --</option>
                        </select>
                        <small class="text-muted">Pilih customer untuk mengisi otomatis nama instansi, wilayah, dan PIC. Anda tetap bisa mengubah nilai secara manual.</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Nama Instansi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_instansi" id="nama_instansi" class="form-control form-control-premium" placeholder="Nama Rumah Sakit / Dinas / Klinik" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">PIC Customer <span class="text-danger">*</span></label>
                        <input type="text" name="pic_customer" id="pic_customer" class="form-control form-control-premium" placeholder="Nama PIC Customer" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Provinsi <span class="text-danger">*</span></label>
                        <select name="provinsi_kode" id="provinsi_kode" class="form-control form-control-premium" required>
                            <option value="">- Pilih Provinsi -</option>
                            <?php foreach ($provinsi as $p): ?>
                                <option value="<?= $p->kode ?>"><?= htmlspecialchars($p->nama) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Kabupaten / Kota <span class="text-danger">*</span></label>
                        <select name="kota_id" id="kota_id" class="form-control form-control-premium" required>
                            <option value="">- Pilih Kabupaten/Kota -</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Nama Unit / Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama_unit" id="nama_unit" class="form-control form-control-premium" placeholder="Nama Unit Alat Kesehatan / Produk" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Estimasi Closing <span class="text-danger">*</span></label>
                        <input type="date" name="estimasi_closing" id="estimasi_closing" class="form-control form-control-premium" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                        <input type="text" name="harga_jual" id="harga_jual" class="form-control form-control-premium format-rupiah" placeholder="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Harga Permintaan Customer (Rp) <span class="text-danger">*</span></label>
                        <input type="text" name="harga_permintaan" id="harga_permintaan" class="form-control form-control-premium format-rupiah" placeholder="0" required>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Gap Harga (%)</label>
                        <input type="text" id="gap_harga_display" class="form-control form-control-premium" readonly style="background-color: #F3F4F6;" value="0%">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Persentase Kecapaian (%) <span class="text-danger">*</span></label>
                        <input type="number" name="persentase_kecapaian" id="persentase_kecapaian" class="form-control form-control-premium" min="0" max="100" placeholder="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold d-block">Status Funnel</label>
                        <div id="funnel_status_badge" class="mt-2">
                            <span class="badge badge-danger font-weight-bold" style="background-color: #dc3545; color: white; padding: 8px 12px; font-size: 0.9rem;">Cold</span>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold">Kendala <span class="text-danger">*</span></label>
                    <textarea name="kendala" id="kendala" class="form-control form-control-premium" rows="2" placeholder="Tuliskan kendala prospek target penjualan..." required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Jenis Support <span class="text-danger">*</span></label>
                        <select name="jenis_support" id="jenis_support" class="form-control form-control-premium" required>
                            <option value="">- Pilih Jenis Support -</option>
                            <option value="None">Tanpa Support</option>
                            <option value="Principal">Support Principal</option>
                            <option value="Demo">Support Demo</option>
                            <option value="E-Katalog">Support Informasi E-Katalog</option>
                            <option value="Negosiasi">Support Negosiasi Harga</option>
                            <option value="Other">Lain-lain</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="font-weight-bold">Kebutuhan Support Detail <span class="text-danger">*</span></label>
                        <textarea name="kebutuhan_support" id="kebutuhan_support" class="form-control form-control-premium" rows="2" placeholder="Jelaskan kebutuhan support yang diharapkan secara mendetail..." required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-premium text-right">
                <button type="button" class="btn btn-secondary btn-premium" data-dismiss="modal">Batal</button>
                <button type="submit" id="btn-save-target" class="btn btn-premium btn-premium-success">Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Achievement / Closing Deal -->
<div class="modal fade" id="modal-achievement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-premium">
            <div class="modal-header modal-header-premium">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-trophy text-warning"></i> Form Realisasi Penjualan (Closing Deal)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('', ['id' => 'form-achievement', 'autocomplete' => 'off']); ?>
            <input type="hidden" name="id" id="achieve_target_id">
            <div class="modal-body p-4">
                <div class="alert alert-premium-info mb-3">
                    <h6 class="font-weight-bold mb-1" id="achieve_nama_instansi">Nama Instansi</h6>
                    <small id="achieve_nama_unit">Unit / Alat</small>
                </div>
                
                <div class="row text-center mb-3">
                    <div class="col-6" style="border-right: 1px solid #E5E7EB;">
                        <label class="text-muted small d-block mb-1">Harga Jual Prospek</label>
                        <strong class="text-dark" id="achieve_harga_jual">Rp 0</strong>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small d-block mb-1">Harga Permintaan Prospek</label>
                        <strong class="text-primary" id="achieve_harga_permintaan">Rp 0</strong>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Nilai Realisasi Penjualan (Rp) <span class="text-danger">*</span></label>
                    <input type="text" name="nilai_achievement" id="nilai_achievement" class="form-control form-control-premium format-rupiah" placeholder="0" required>
                    <small class="text-muted">Masukkan nilai nominal penjualan riil yang berhasil dicapai.</small>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold">Tanggal Closing / Penjualan <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_achievement" id="tgl_achievement" class="form-control form-control-premium" required>
                </div>

                <div class="form-group mb-0 d-none" id="cancel_achievement_container" style="background-color: #FEF2F2; padding: 12px; border-radius: 8px; border: 1px solid #FEE2E2;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="cancel_achievement" name="cancel_achievement" value="1">
                        <label class="custom-control-label font-weight-bold text-danger cursor-pointer" for="cancel_achievement">Batalkan Status Closing Deal (Kembalikan ke Pipeline)</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-premium text-right">
                <button type="button" class="btn btn-secondary btn-premium" data-dismiss="modal">Batal</button>
                <button type="submit" id="btn-save-achievement" class="btn btn-premium btn-premium-success"><i class="fas fa-save"></i> Simpan Realisasi</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let token = $('input[name=token]').val();

        // 1. DataTables Init
        let target_table = $('#target_table').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('marketing_target/get_targets_json') ?>',
                type: 'POST',
                data: function(d) {
                    d.tahun = $('#filter_tahun').val();
                    d.marketing_id = $('#filter_marketing').val() || 'all';
                    d.bulan = $('#filter_bulan').val() || 'all';
                    d.csrf_token = token;
                }
            },
            columnDefs: [
                {
                    targets: [8, 9, 10, 11, 12, 13, 16],
                    className: 'text-center'
                }
            ],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
            }
        });

        // Reload table triggers
        $('#filter_tahun, #filter_marketing, #filter_bulan').on('change', function() {
            target_table.search('').columns().search('');
            target_table.ajax.reload();
        });

        // Autofill Customer Data Queries
        function loadAutofillCustomers() {
            let autofillSelect = $('#autofill_customer');
            autofillSelect.html('<option value="">-- Cari & Pilih Customer --</option>');
            $.ajax({
                url: '<?= base_url('marketing_target/get_customers_json') ?>',
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    data.forEach(function(cust) {
                        let tipe_badge = cust.tipe === 'calon' ? '[Calon]' : '[Pelanggan]';
                        let optText = tipe_badge + ' ' + cust.nama;
                        let option = $('<option></option>')
                            .val(cust.id + '|' + cust.tipe)
                            .text(optText)
                            .attr('data-nama', cust.nama)
                            .attr('data-provinsi', cust.provinsi)
                            .attr('data-kota', cust.kota);
                        autofillSelect.append(option);
                    });
                    
                    if ($.fn.select2) {
                        autofillSelect.select2({
                            dropdownParent: $('#modal-target'),
                            placeholder: "-- Cari & Pilih Customer --",
                            allowClear: true
                        });
                    }
                }
            });
        }

        loadAutofillCustomers();

        $('#autofill_customer').on('change', function() {
            let val = $(this).val();
            if (!val) return;

            let parts = val.split('|');
            let custId = parts[0];
            let custTipe = parts[1];

            let selectedOpt = $(this).find('option:selected');
            let nama = selectedOpt.attr('data-nama');
            let provinsi = selectedOpt.attr('data-provinsi');
            let kota = selectedOpt.attr('data-kota');

            $('#nama_instansi').val(nama);
            if (provinsi) {
                $('#provinsi_kode').val(provinsi).trigger('change');
                
                // Allow province loading to trigger city dropdown population first
                setTimeout(function() {
                    if (kota) {
                        $('#kota_id').val(kota);
                    }
                }, 600);
            }

            // Fetch PIC for selected customer
            $.ajax({
                url: '<?= base_url('marketing_target/get_customer_pics_json/') ?>' + custId + '/' + custTipe,
                type: 'GET',
                dataType: 'JSON',
                success: function(pics) {
                    if (pics && pics.length > 0) {
                        let pic = pics[0];
                        let picText = pic.namapic;
                        if (pic.jabatanpic) picText += ' (' + pic.jabatanpic + ')';
                        if (pic.teleponpic) picText += ' - ' + pic.teleponpic;
                        $('#pic_customer').val(picText);
                    } else {
                        $('#pic_customer').val('');
                    }
                }
            });
        });

        // 2. Province -> Kota Dynamic Loading
        $('#provinsi_kode').on('change', function() {
            let kode = this.value;
            let kotaSelect = $('#kota_id');
            kotaSelect.html('<option value="">Loading...</option>');
            if (kode) {
                kotaSelect.load('<?= site_url('calonpelanggan/add_ajax_kota') ?>/' + kode);
            } else {
                kotaSelect.html('<option value="">- Pilih Kabupaten/Kota -</option>');
            }
        });

        // 3. Currency Input Helper (Rupiah formatting)
        $(document).on('keyup', '.format-rupiah', function() {
            let val = $(this).val();
            let clean = val.replace(/\D/g, "");
            let formatted = clean.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(formatted);
            calculateGapPercentage();
        });

        // 4. Automatic Gap and Funnel Status Calculation
        function calculateGapPercentage() {
            let hjVal = $('#harga_jual').val() || "0";
            let hpVal = $('#harga_permintaan').val() || "0";
            
            let hj = parseFloat(hjVal.replace(/\D/g, "")) || 0;
            let hp = parseFloat(hpVal.replace(/\D/g, "")) || 0;
            
            let gap = 0;
            if (hj > 0) {
                gap = ((hj - hp) / hj) * 100;
            }
            
            $('#gap_harga_display').val(gap.toFixed(1) + '%');
        }

        $('#persentase_kecapaian').on('input', function() {
            let pct = parseInt($(this).val()) || 0;
            let badge = $('#funnel_status_badge');
            
            if (pct >= 75) {
                badge.html('<span class="badge badge-success font-weight-bold" style="background-color: #28a745; color: white; padding: 8px 12px; font-size: 0.9rem;">Hot</span>');
            } else if (pct >= 40) {
                badge.html('<span class="badge badge-warning font-weight-bold" style="background-color: #ffc107; color: black; padding: 8px 12px; font-size: 0.9rem;">Warm</span>');
            } else {
                badge.html('<span class="badge badge-danger font-weight-bold" style="background-color: #dc3545; color: white; padding: 8px 12px; font-size: 0.9rem;">Cold</span>');
            }
        });

        // 5. Add Target Action
        $('#btn-add-target').click(function() {
            $('#form-target')[0].reset();
            $('#target_id').val('');
            $('#modal-title-text').html('<i class="fas fa-plus"></i> Tambah Prospek Target Jangka Pendek');
            $('#kota_id').html('<option value="">- Pilih Kabupaten/Kota -</option>');
            $('#gap_harga_display').val('0%');
            $('#funnel_status_badge').html('<span class="badge badge-danger font-weight-bold" style="background-color: #dc3545; color: white; padding: 8px 12px; font-size: 0.9rem;">Cold</span>');
            $('#modal-target').modal('show');
        });

        // 6. Edit Action
        $('#target_table').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('marketing_target/edit/') ?>' + id,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $('#form-target')[0].reset();
                    $('#target_id').val(id);
                    $('#modal-title-text').html('<i class="fas fa-pencil-alt"></i> Edit Prospek Target Jangka Pendek');

                    $('#tahun').val(data.tahun);
                    if ($('#marketing_id').length) {
                        $('#marketing_id').val(data.marketing_id);
                    }
                    $('#nama_instansi').val(data.nama_instansi);
                    $('#pic_customer').val(data.pic_customer);
                    $('#provinsi_kode').val(data.provinsi_kode).trigger('change');
                    
                    // Delay loading kota to allow province change trigger to complete
                    setTimeout(function() {
                        $('#kota_id').val(data.kota_id);
                    }, 500);

                    $('#nama_unit').val(data.nama_unit);
                    $('#estimasi_closing').val(data.estimasi_closing);

                    // Format values
                    let hj_formatted = String(Math.round(data.harga_jual)).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    let hp_formatted = String(Math.round(data.harga_permintaan)).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    $('#harga_jual').val(hj_formatted);
                    $('#harga_permintaan').val(hp_formatted);
                    
                    $('#gap_harga_display').val(parseFloat(data.gap_harga).toFixed(1) + '%');
                    $('#persentase_kecapaian').val(data.persentase_kecapaian).trigger('input');
                    
                    $('#kendala').val(data.kendala);
                    $('#jenis_support').val(data.jenis_support);
                    $('#kebutuhan_support').val(data.kebutuhan_support);

                    $('#modal-target').modal('show');
                }
            });
        });

        // 7. Delete Action
        $('#target_table').on('click', '.btn-delete-target', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data target penjualan yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '<?= base_url('marketing_target/delete/') ?>' + id,
                        type: 'POST',
                        data: {
                            '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
                        },
                        dataType: 'JSON',
                        success: function(res) {
                            if (res.status === 'success') {
                                Swal.fire('Terhapus!', res.message, 'success');
                                target_table.ajax.reload(null, false);
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan sistem: ' + (xhr.responseJSON?.message || error || 'Gagal terhubung ke server'), 'error');
                        }
                    });
                }
            });
        });

        // 8. Submit Form Action
        $('#form-target').on('submit', function(e) {
            e.preventDefault();
            let actionUrl = '<?= base_url('marketing_target/save_target') ?>';
            
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#modal-target').modal('hide');
                        Swal.fire('Berhasil!', res.message, 'success');
                        target_table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                }
            });
        });

        // 9. Achieve Action (Closing Deal)
        $('#target_table').on('click', '.btn-achieve', function() {
            let id = $(this).attr('data-id');
            let instansi = $(this).attr('data-instansi');
            let unit = $(this).attr('data-unit');
            let hargaJual = $(this).attr('data-harga-jual');
            let hargaPermintaan = $(this).attr('data-harga-permintaan');
            let nilaiAchievement = $(this).attr('data-nilai-achievement');
            let tglAchievement = $(this).attr('data-tgl-achievement');
            let isAchieved = $(this).attr('data-is-achieved') === '1';

            $('#achieve_target_id').val(id);
            $('#achieve_nama_instansi').text(instansi);
            $('#achieve_nama_unit').text(unit);
            $('#achieve_harga_jual').text('Rp ' + hargaJual);
            $('#achieve_harga_permintaan').text('Rp ' + hargaPermintaan);
            $('#nilai_achievement').val(nilaiAchievement);
            $('#tgl_achievement').val(tglAchievement);

            if (isAchieved) {
                $('#cancel_achievement_container').removeClass('d-none');
                $('#cancel_achievement').prop('checked', false);
            } else {
                $('#cancel_achievement_container').addClass('d-none');
                $('#cancel_achievement').prop('checked', false);
            }

            $('#modal-achievement').modal('show');
        });

        // 10. Submit Achievement Action
        $('#form-achievement').on('submit', function(e) {
            e.preventDefault();
            let actionUrl = '<?= base_url('marketing_target/save_achievement') ?>';
            
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#modal-achievement').modal('hide');
                        Swal.fire('Berhasil!', res.message, 'success');
                        target_table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                }
            });
        });

        // 11. Excel Export Action
        $('#btn-export-excel').click(function() {
            let tahun = $('#filter_tahun').val();
            let marketing_id = $('#filter_marketing').val() || 'all';
            let bulan = $('#filter_bulan').val() || 'all';
            window.open('<?= base_url('marketing_target/export_excel') ?>?tahun=' + tahun + '&marketing_id=' + marketing_id + '&bulan=' + bulan, '_blank');
        });
    });
</script>
