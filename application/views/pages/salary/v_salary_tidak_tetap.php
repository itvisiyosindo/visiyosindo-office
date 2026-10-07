<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<style>
    .salary-tt-card {
        border-radius: 12px;
        border: 1px solid #eef2f6;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 13px 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }
    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    .stat-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    .stat-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    .stat-value {
        font-size: 16.5px;
        font-weight: 700;
        color: #1e293b;
        font-family: 'Inter', sans-serif;
        letter-spacing: -0.2px;
    }
    .icon-blue { background: #eff6ff; color: #3b82f6; }
    .icon-purple { background: #f5f3ff; color: #8b5cf6; }
    .icon-amber { background: #fffbeb; color: #f59e0b; }
    .icon-cyan { background: #ecfeff; color: #06b6d4; }
    .icon-emerald { background: #ecfdf5; color: #10b981; }
    .icon-rose { background: #fff1f2; color: #f43f5e; }
    .icon-teal { background: #f0fdfa; color: #14b8a6; }
    .icon-red { background: #fef2f2; color: #ef4444; }

    #kt_table_1 thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e2e8f0;
        vertical-align: middle;
        padding: 11px 8px;
    }
    #kt_table_1 tbody td {
        vertical-align: middle;
        font-size: 12.5px;
        padding: 9px 8px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card salary-tt-card mb-4">
            <div class="card-body p-3 p-md-4">
                <!-- Top Toolbar & Filter Row -->
                <div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-4" style="border-bottom: 1px solid #f1f5f9; gap: 12px;">
                    <!-- Left: Filters & Add Button -->
                    <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                        <?php if (isAdmin()) { ?>
                            <a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa fa-plus-circle"></i> Tambah Tunjangan
                            </a>
                        <?php } ?>

                        <div class="input-group input-group-sm" style="width: auto;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0 text-muted" style="border-radius: 8px 0 0 8px; font-weight: 600; font-size: 12px;">
                                    <i class="fa fa-calendar-alt mr-1 text-primary"></i> Periode:
                                </span>
                            </div>
                            <select class="form-control form-control-sm custom-select" id="sel_bulan" style="width: 145px; border-radius: 0; font-size: 12px; font-weight: 500;">
                                <option value="01">01 - Januari</option>
                                <option value="02">02 - Februari</option>
                                <option value="03">03 - Maret</option>
                                <option value="04">04 - April</option>
                                <option value="05">05 - Mei</option>
                                <option value="06">06 - Juni</option>
                                <option value="07">07 - Juli</option>
                                <option value="08">08 - Agustus</option>
                                <option value="09">09 - September</option>
                                <option value="10">10 - Oktober</option>
                                <option value="11">11 - November</option>
                                <option value="12">12 - Desember</option>
                            </select>
                            <select class="form-control form-control-sm custom-select" id="sel_tahun" style="width: 90px; border-radius: 0 8px 8px 0; font-size: 12px; font-weight: 500;">
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                            </select>
                        </div>
                        <input type="hidden" id="filter_month" value="<?= date('Y-m') ?>">
                    </div>

                    <!-- Right: Actions Buttons -->
                    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                        <button type="button" id="btn-show-all" class="btn btn-sm btn-outline-info shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 13px; display: inline-flex; align-items: center; gap: 5px; font-size: 12px;">
                            <i class="fas fa-users"></i> Tampilkan Semua
                        </button>
                        <button type="button" id="btn-print" class="btn btn-sm btn-primary shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; display: inline-flex; align-items: center; gap: 5px; font-size: 12px;">
                            <i class="fas fa-print"></i> Print Rekap
                        </button>
                        <button type="button" id="btn-print-skor" class="btn btn-sm btn-outline-primary shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; display: inline-flex; align-items: center; gap: 5px; font-size: 12px;">
                            <i class="fas fa-file-invoice"></i> Print Skor
                        </button>
                    </div>
                </div>

                <!-- Grand Total Banner & Component Cards -->
                <div class="mb-4">
                    <!-- Grand Total Banner -->
                    <div class="card border-0 mb-3" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15);">
                        <div class="card-body p-3 p-md-4 d-flex flex-wrap align-items-center justify-content-between" style="gap: 15px;">
                            <div class="d-flex align-items-center" style="gap: 16px;">
                                <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(56, 189, 248, 0.15); display: flex; align-items: center; justify-content: center; color: #38bdf8; font-size: 22px;">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div>
                                    <div class="text-uppercase text-white-50 font-weight-bold" style="font-size: 11px; letter-spacing: 1px;">Grand Total Tunjangan Tidak Tetap</div>
                                    <div class="text-white font-weight-normal mb-0" style="font-size: 13px; opacity: 0.85;">Akumulasi seluruh tunjangan tidak tetap karyawan bulan ini</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-primary px-3 py-1 mb-1" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 20px; background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4);">Total Keseluruhan</span>
                                <div class="h2 mb-0 font-weight-bold" id="stats_total_tunjangan" style="color: #38bdf8; font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">Rp. 0</div>
                            </div>
                        </div>
                    </div>

                    <!-- 8 Component Stat Cards in 4x2 Grid -->
                    <div class="row" id="salary-total-cards" style="margin: 0 -5px;">
                        <!-- 1. Total Jabatan -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total Jabatan</span>
                                    <div class="stat-icon icon-blue"><i class="fas fa-user-tie"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_jabatan">Rp. 0</div>
                            </div>
                        </div>
                        <!-- 2. Total Kinerja -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total Kinerja</span>
                                    <div class="stat-icon icon-purple"><i class="fas fa-chart-line"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_kinerja">Rp. 0</div>
                            </div>
                        </div>
                        <!-- 3. Total Konsumsi -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total Konsumsi</span>
                                    <div class="stat-icon icon-amber"><i class="fas fa-utensils"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_konsumsi">Rp. 0</div>
                            </div>
                        </div>
                        <!-- 4. Total Komunikasi -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total Komunikasi</span>
                                    <div class="stat-icon icon-cyan"><i class="fas fa-phone-alt"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_komunikasi">Rp. 0</div>
                            </div>
                        </div>
                        <!-- 5. Total Transportasi -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total Transportasi</span>
                                    <div class="stat-icon icon-emerald"><i class="fas fa-car"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_transportasi">Rp. 0</div>
                            </div>
                        </div>
                        <!-- 6. Total BBM -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total BBM</span>
                                    <div class="stat-icon icon-rose"><i class="fas fa-gas-pump"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_bbm">Rp. 0</div>
                            </div>
                        </div>
                        <!-- 7. Total Lainnya -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total Lainnya</span>
                                    <div class="stat-icon icon-teal"><i class="fas fa-hand-holding-usd"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_lainnya">Rp. 0</div>
                            </div>
                        </div>
                        <!-- 8. Total Potongan -->
                        <div class="col-xl-3 col-md-6 col-sm-6 p-1 mb-2">
                            <div class="stat-card">
                                <div class="stat-header">
                                    <span class="stat-title">Total Potongan</span>
                                    <div class="stat-icon icon-red"><i class="fas fa-minus-circle"></i></div>
                                </div>
                                <div class="stat-value" id="stats_total_potongan">Rp. 0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Wrapper -->
                <div class="table-responsive" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                    <table class="table table-striped table-sm table-bordered table-hover mb-0" id="kt_table_1">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Nama Karyawan</th>
                                <th> Jumlah Hari Kerja</th>
                                <th> Jabatan</th>
                                <th> Status</th>
                                <th> Tunjangan Jabatan</th>
                                <th> Tunjangan Kinerja</th>
                                <th> Tunjangan Konsumsi</th>
                                <th> Tunjangan Komunikasi</th>
                                <th> Tunjangan Transportasi</th>
                                <th> Tunjangan BBM</th>
                                <th> Tunjungan Lainnya</th>
                                <th> Potongan</th>
                                <th> Total</th>
                                <th> Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Override Manual -->
<div class="modal fade" id="modal-override" tabindex="-1" role="dialog" aria-labelledby="modalOverrideLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-override" method="POST">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold" id="modalOverrideLabel">
                        <i class="fa fa-edit"></i> Edit Hitungan Tunjangan Manual
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <strong>Karyawan:</strong> <span id="ov_nama_karyawan">-</span> | 
                        <strong>Periode:</strong> <span id="ov_periode_text">-</span>
                        <br><small class="text-muted">* Kosongkan kolom jika ingin tetap menggunakan hitungan otomatis sistem.</small>
                    </div>
                    <input type="hidden" name="pengguna_id" id="ov_pengguna_id">
                    <input type="hidden" name="month" id="ov_month">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Jumlah Hari Kerja / Dinas (Hari)</label>
                            <input type="number" name="hari_kerja" id="ov_hari_kerja" class="form-control" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tunjangan Jabatan (Rp)</label>
                            <input type="text" name="tunjangan_jabatan" id="ov_tunjangan_jabatan" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tunjangan Kinerja (Rp)</label>
                            <input type="text" name="tunjangan_kinerja" id="ov_tunjangan_kinerja" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tunjangan Konsumsi (Rp)</label>
                            <input type="text" name="tunjangan_konsumsi" id="ov_tunjangan_konsumsi" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tunjangan Komunikasi (Rp)</label>
                            <input type="text" name="tunjangan_komunikasi" id="ov_tunjangan_komunikasi" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tunjangan Transportasi (Rp)</label>
                            <input type="text" name="tunjangan_transportasi" id="ov_tunjangan_transportasi" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tunjangan BBM (Rp)</label>
                            <input type="text" name="tunjangan_bbm" id="ov_tunjangan_bbm" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Tunjangan Lainnya (Rp)</label>
                            <input type="text" name="tunjangan_lainnya" id="ov_tunjangan_lainnya" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Potongan (Rp)</label>
                            <input type="text" name="potongan" id="ov_potongan" class="form-control input_salary" placeholder="Otomatis">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Keterangan Alasan Edit</label>
                            <input type="text" name="keterangan" id="ov_keterangan" class="form-control" placeholder="Contoh: Penyesuaian dinas luar kota">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-danger" id="btn-reset-override">
                        <i class="fa fa-undo"></i> Reset ke Otomatis
                    </button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning font-weight-bold">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function syncFromDropdowns() {
            var m = $('#sel_bulan').val() || '01';
            var y = $('#sel_tahun').val() || '2026';
            $('#filter_month').val(y + '-' + m);
        }

        function syncToDropdowns(val) {
            if (val && val.indexOf('-') !== -1) {
                var parts = val.split('-');
                if (parts[0]) $('#sel_tahun').val(parts[0]);
                if (parts[1]) $('#sel_bulan').val(parts[1]);
            }
        }

        // Initialize default month & year
        var now = new Date();
        var currentYear = String(now.getFullYear());
        var currentMonth = ('0' + (now.getMonth() + 1)).slice(-2);

        if ($('#sel_tahun option[value="' + currentYear + '"]').length > 0) {
            $('#sel_tahun').val(currentYear);
        }
        $('#sel_bulan').val(currentMonth);
        syncFromDropdowns();

        $('#sel_bulan, #sel_tahun').on('change', function() {
            syncFromDropdowns();
            updateDatatable();
            updateTotals();
        });

        $('#filter_month').on('change dp.change', function() {
            syncToDropdowns($(this).val());
            updateDatatable();
            updateTotals();
        });

        $('.input_salary').mask('000.000.000.000', {
            reverse: true
        });

        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            pageLength: 50,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua (Seluruh Karyawan)"]
            ],
            order: [
                [0, 'asc']
            ],
            ajax: {
                url: 'salary_tidak_tetap/pagination',
                type: 'POST',
                data: function(e) {
                    e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14],
                className: 'text-center'
            }]
        });

        $(document).on('click', '#btn-show-all', function() {
            table.page.len(-1).draw();
        });

        $(document).on('click', '.btn-edit', function() {
            $('.form-control').val(null)
            var object = 'salary'
            $('#main-modal #modal-form').attr('action', 'salary/add')
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            $('#pengguna_id').val(id)

            fetch(object + '/edit/' + id)
                .then(function(resp) {
                    return resp.json()
                })
                .then(function(data) {
                    $('#main-modal #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal #tunjangan_kinerja').val(data[0].tunjangan_kinerja)
                    $('#main-modal #tunjangan_konsumsi').val(data[0].tunjangan_konsumsi)
                })
        })

        $(document).on('click', '#btn-print', function() {
            var month = $('#filter_month').val()
            var link = 'salary_tidak_tetap/show/print/' + month
            window.open('<?= base_url() ?>' + link)
        })

        $(document).on('click', '#btn-print-skor', function() {
            var month = $('#filter_month').val()
            var link = 'salary_tidak_tetap/show/print2/' + month
            window.open('<?= base_url() ?>' + link)
        })

        
        var currentAutoData = {};
        var currentRatesData = {};

        function formatRp(n) {
            if (n === null || n === undefined || isNaN(n)) return '0';
            return new Intl.NumberFormat('id-ID').format(n);
        }

        function updateLivePlaceholders() {
            var rawDays = $('#ov_hari_kerja').val();
            var days = (rawDays !== '' && !isNaN(rawDays)) ? parseInt(rawDays) : (currentAutoData.hari_kerja || 0);

            var rateKinerja = currentRatesData.rate_kinerja || 0;
            var rateKonsumsi = currentRatesData.rate_konsumsi || 0;

            var autoKinerja = rateKinerja * days;
            var autoKonsumsi = rateKonsumsi * days;

            if (rateKinerja > 0) {
                $('#ov_tunjangan_kinerja').attr('placeholder', 'Otomatis (' + days + ' hari x Rp ' + formatRp(rateKinerja) + ') = Rp ' + formatRp(autoKinerja));
            } else {
                $('#ov_tunjangan_kinerja').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.tunjangan_kinerja || 0));
            }

            if (rateKonsumsi > 0) {
                $('#ov_tunjangan_konsumsi').attr('placeholder', 'Otomatis (' + days + ' hari x Rp ' + formatRp(rateKonsumsi) + ') = Rp ' + formatRp(autoKonsumsi));
            } else {
                $('#ov_tunjangan_konsumsi').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.tunjangan_konsumsi || 0));
            }
        }

        $('#ov_hari_kerja').on('input change', function() {
            updateLivePlaceholders();
        });

        $(document).on('click', '.btn-edit-override', function() {
            var penggunaId = $(this).attr('data-id');
            var nama = $(this).attr('data-nama');
            var month = $('#filter_month').val();

            $('#ov_pengguna_id').val(penggunaId);
            $('#ov_month').val(month);
            $('#ov_nama_karyawan').text(nama);
            $('#ov_periode_text').text(month);

            // Reset form fields
            $('#form-override')[0].reset();
            $('#ov_pengguna_id').val(penggunaId);
            $('#ov_month').val(month);
            currentAutoData = {};
            currentRatesData = {};

            // Open modal immediately so user sees modal right away
            $('#modal-override').modal('show');

            // Fetch existing override or auto values
            $.ajax({
                method: 'POST',
                url: '<?= base_url('salary_tidak_tetap/get_override') ?>',
                dataType: 'JSON',
                data: {
                    pengguna_id: penggunaId,
                    month: month,
                    csrf_token: token
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        currentAutoData = resp.auto || {};
                        currentRatesData = resp.rates || {};
                        var ov = resp.override || {};

                        // Set placeholders from auto
                        $('#ov_hari_kerja').attr('placeholder', 'Otomatis: ' + (currentAutoData.hari_kerja || 0) + ' hari');
                        $('#ov_tunjangan_jabatan').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.tunjangan_jabatan || 0));
                        $('#ov_tunjangan_komunikasi').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.tunjangan_komunikasi || 0));
                        $('#ov_tunjangan_transportasi').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.tunjangan_transportasi || 0));
                        $('#ov_tunjangan_bbm').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.tunjangan_bbm || 0));
                        $('#ov_tunjangan_lainnya').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.tunjangan_lainnya || 0));
                        $('#ov_potongan').attr('placeholder', 'Otomatis: Rp ' + formatRp(currentAutoData.potongan || 0));

                        // Set values if override exists
                        if (ov.hari_kerja !== null && ov.hari_kerja !== undefined) $('#ov_hari_kerja').val(ov.hari_kerja);
                        if (ov.tunjangan_jabatan !== null && ov.tunjangan_jabatan !== undefined) $('#ov_tunjangan_jabatan').val(ov.tunjangan_jabatan);
                        if (ov.tunjangan_kinerja !== null && ov.tunjangan_kinerja !== undefined) $('#ov_tunjangan_kinerja').val(ov.tunjangan_kinerja);
                        if (ov.tunjangan_konsumsi !== null && ov.tunjangan_konsumsi !== undefined) $('#ov_tunjangan_konsumsi').val(ov.tunjangan_konsumsi);
                        if (ov.tunjangan_komunikasi !== null && ov.tunjangan_komunikasi !== undefined) $('#ov_tunjangan_komunikasi').val(ov.tunjangan_komunikasi);
                        if (ov.tunjangan_transportasi !== null && ov.tunjangan_transportasi !== undefined) $('#ov_tunjangan_transportasi').val(ov.tunjangan_transportasi);
                        if (ov.tunjangan_bbm !== null && ov.tunjangan_bbm !== undefined) $('#ov_tunjangan_bbm').val(ov.tunjangan_bbm);
                        if (ov.tunjangan_lainnya !== null && ov.tunjangan_lainnya !== undefined) $('#ov_tunjangan_lainnya').val(ov.tunjangan_lainnya);
                        if (ov.potongan !== null && ov.potongan !== undefined) $('#ov_potongan').val(ov.potongan);
                        if (ov.keterangan) $('#ov_keterangan').val(ov.keterangan);

                        updateLivePlaceholders();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('get_override AJAX error', status, error, xhr.responseText);
                }
            });
        });

        $('#form-override').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize() + '&csrf_token=' + token;
            $.ajax({
                method: 'POST',
                url: '<?= base_url('salary_tidak_tetap/save_override') ?>',
                dataType: 'JSON',
                data: formData,
                success: function(resp) {
                    if (resp.status == 'success') {
                        Swal.fire('Berhasil!', resp.message, 'success');
                        $('#modal-override').modal('hide');
                        updateDatatable();
                        updateTotals();
                    } else {
                        Swal.fire('Gagal!', resp.message || 'Gagal menyimpan.', 'error');
                    }
                }
            });
        });

        $('#btn-reset-override').on('click', function() {
            var penggunaId = $('#ov_pengguna_id').val();
            var month = $('#ov_month').val();

            Swal.fire({
                title: 'Reset ke Otomatis?',
                text: 'Hitungan manual karyawan ini akan dihapus dan kembali menggunakan hitungan otomatis.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value || result.isConfirmed) {
                    $.ajax({
                        method: 'POST',
                        url: '<?= base_url('salary_tidak_tetap/save_override') ?>',
                        dataType: 'JSON',
                        data: {
                            pengguna_id: penggunaId,
                            month: month,
                            is_reset: 1,
                            csrf_token: token
                        },
                        success: function(resp) {
                            if (resp.status == 'success') {
                                Swal.fire('Direset!', resp.message, 'success');
                                $('#modal-override').modal('hide');
                                updateDatatable();
                                updateTotals();
                            }
                        }
                    });
                }
            });
        });

        updateTotals()
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }

    function formatTotal(val) {
        if (!val || val === '' || val === '0' || val === 0) return 'Rp. 0';
        if (typeof val === 'string' && val.indexOf('Rp.') !== -1) return val;
        return 'Rp. ' + val;
    }

    function updateTotals() {
        $.ajax({
            method: 'POST',
            url: '<?= base_url('salary_tidak_tetap/getTotals') ?>',
            dataType: 'JSON',
            data: {
                filter_month: $('#filter_month').val(),
                csrf_token: token
            },
            success: function(resp) {
                if (resp.status == 'success' && resp.totals) {
                    $('#stats_total_jabatan').text(formatTotal(resp.totals.total_jabatan_formatted));
                    $('#stats_total_kinerja').text(formatTotal(resp.totals.total_kinerja_formatted));
                    $('#stats_total_konsumsi').text(formatTotal(resp.totals.total_konsumsi_formatted));
                    $('#stats_total_komunikasi').text(formatTotal(resp.totals.total_komunikasi_formatted));
                    $('#stats_total_transportasi').text(formatTotal(resp.totals.total_transportasi_formatted));
                    $('#stats_total_bbm').text(formatTotal(resp.totals.total_bbm_formatted));
                    $('#stats_total_lainnya').text(formatTotal(resp.totals.total_lainnya_formatted));
                    $('#stats_total_potongan').text(formatTotal(resp.totals.total_potongan_formatted));
                    $('#stats_total_tunjangan').text(formatTotal(resp.totals.total_tunjangan_formatted));
                } else {
                    console.warn('getTotals returned empty or error', resp);
                }
            },
            error: function(xhr, status, error) {
                console.error('getTotals AJAX error', status, error, xhr.responseText);
            }
        });
    }
</script>