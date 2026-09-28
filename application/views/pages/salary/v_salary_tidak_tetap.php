<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">

    <div class="col">
        <div class="">
            <?php if (isAdmin()) { ?>
                <a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i
                        class="icons icon-plus"></i>&nbsp;Tambah Tunjangan</a>
            <?php } ?>
        </div>
        <br>
        <div class="card-body">

            <div class="row align-items-end mb-3">
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="font-weight-bold mb-1"><i class="fa fa-calendar-alt"></i> Pilih Bulan:</label>
                    <select class="form-control" id="sel_bulan">
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
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="font-weight-bold mb-1"><i class="fa fa-calendar"></i> Pilih Tahun:</label>
                    <select class="form-control" id="sel_tahun">
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
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="font-weight-bold mb-1"><i class="fa fa-calendar-check"></i> Datepicker:</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12 mb-2 text-right">
                    <button type="button" id="btn-show-all" class="btn btn-info mr-1" title="Tampilkan Seluruh Karyawan Tanpa Halaman">
                        <i class="icons fas fa-users"></i> Tampilkan Seluruh Karyawan
                    </button>
                    <button type="button" id="btn-print" class="btn btn-primary mr-1"><i class="icons fas fa-print"></i> Print</button>
                    <button type="button" id="btn-print-skor" class="btn btn-primary"><i class="icons fas fa-print"></i> Print Skor</button>
                </div>
            </div><br>
            <div class="row mb-3" id="salary-total-cards">
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Jabatan</div>
                        <div class="h3 mb-0" id="stats_total_jabatan">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Kinerja</div>
                        <div class="h3 mb-0" id="stats_total_kinerja">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Konsumsi</div>
                        <div class="h3 mb-0" id="stats_total_konsumsi">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Komunikasi</div>
                        <div class="h3 mb-0" id="stats_total_komunikasi">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Transportasi</div>
                        <div class="h3 mb-0" id="stats_total_transportasi">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total BBM</div>
                        <div class="h3 mb-0" id="stats_total_bbm">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Lainnya</div>
                        <div class="h3 mb-0" id="stats_total_lainnya">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-light h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Potongan</div>
                        <div class="h3 mb-0" id="stats_total_potongan">Rp. 0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                    <div class="card card-body bg-secondary text-white h-100 text-center">
                        <div class="text-uppercase font-weight-bold small">Total Tunjangan</div>
                        <div class="h3 mb-0" id="stats_total_tunjangan">Rp. 0</div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
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
                    $('#stats_total_jabatan').text(resp.totals.total_jabatan_formatted)
                    $('#stats_total_kinerja').text(resp.totals.total_kinerja_formatted)
                    $('#stats_total_konsumsi').text(resp.totals.total_konsumsi_formatted)
                    $('#stats_total_komunikasi').text(resp.totals.total_komunikasi_formatted)
                    $('#stats_total_transportasi').text(resp.totals.total_transportasi_formatted)
                    $('#stats_total_bbm').text(resp.totals.total_bbm_formatted)
                    $('#stats_total_lainnya').text(resp.totals.total_lainnya_formatted)
                    $('#stats_total_potongan').text(resp.totals.total_potongan_formatted)
                    $('#stats_total_tunjangan').text(resp.totals.total_tunjangan_formatted)
                } else {
                    console.warn('getTotals returned empty or error', resp)
                }
            },
            error: function(xhr, status, error) {
                console.error('getTotals AJAX error', status, error, xhr.responseText)
            }
        })
    }
</script>