<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;
        <?= $page_title ?>
    </h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span>
                    <?= $page_desc ?>
                </span></li>
        </ol>
    </div>
</header>

<div class="row">
    <div class="col">
        <div class="card-body">
            <div class="table-responsive">
                <div class="row mb-3 ml-1">
                    <div>
                        <button type="button" print="gapok" class="btn btn-success btn-print-salary"><i
                                class="icons fas fa-print"></i> Print Gapok</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" print="tunjangan" class="btn btn-warning btn-print"><i
                                class="icons fas fa-print"></i> Print T. Jabatan</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" id="btn-tutupbuku" class="btn btn-danger btn-save"><i
                                class="icons fas fa-print"></i> Tutup Buku</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" id="btn-history" class="btn btn-outline-secondary btn-history"><i
                                class="icons fas fa-print"></i> History Salary</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" id="btn-resign" class="btn btn-outline-secondary btn-resign"><i
                                class="icons fas fa-print"></i> Print Salary Resign</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" class="btn btn-info btn-import-komisi"><i
                                class="icons fas fa-file-excel"></i> Import Komisi (Excel)</button>
                    </div>
                    &nbsp;
                    <div>
                        <button type="button" class="btn btn-primary btn-import-pph21"><i
                                class="icons fas fa-file-excel"></i> Import PPh 21 (Excel)</button>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Filter Bulan & Tahun:</label>
                            <input type="text" id="filter_month" class="form-control" 
                                   data-plugin-datepicker 
                                   data-plugin-options='{"format": "yyyy-mm", "minViewMode": "months", "orientation": "bottom"}' 
                                   placeholder="Pilih Bulan" value="<?= date('Y-m') ?>">
                        </div>
                    </div>
                </div>
                <br>
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nama Karyawan</th>
                            <th> Gaji Pokok </th>
                            <th> Komisi </th>
                            <th> BPJS Kesehatan </th>
                            <th> BPJS TK </th>
                            <th> Pendapatan Lain </th>
                            <th> PPh 21 </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Gaji Pokok :</label>
                        <input type="text" class="form-control input_salary" id="gaji_pokok" name="gaji_pokok" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Tunjungan Jabatan :</label>
                        <input type="text" class="form-control input_salary" id="tunjangan_jabatan"
                            name="tunjangan_jabatan" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Dasar Potongan BPJS Kesehatan :</label>
                        <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs"
                            name="dasar_potongan_bpjs" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Dasar Potongan BPJS Ketenagakerjaan :</label>
                        <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs_tk"
                            name="dasar_potongan_bpjs_tk" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-komisi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Komisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-komisi', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Komisi :</label>
                        <input type="text" class="form-control input_salary" id="komisi" name="komisi" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Bulan :</label>
                        <input type="text" data-plugin-datepicker
                            data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}'
                            class="form-control" id="bulan" name="bulan" placeholder="Pilih Bulan" required
                            data-plugin-datepicker>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Import Komisi Excel -->
<div id="modal-import-komisi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-light">
                <h5 class="modal-title"><i class="fas fa-file-excel"></i> Import Data Komisi via Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <?= form_open_multipart('salary/import_komisi', array('id' => 'form-import-komisi')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template Excel:</label><br>
                        <a href="<?= base_url('salary/download_template_komisi'); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-download"></i> Download Template Komisi.xlsx
                        </a>
                </div>
                <hr>
                <div class="form-group">
                    <label>Pilih File Excel (.xlsx / .xls):</label>
                    <input type="file" name="file_excel" class="form-control-file" accept=".xlsx, .xls, .csv" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload & Simpan ke Database</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Import PPh 21 Excel -->
<div id="modal-import-pph21" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title"><i class="fas fa-file-excel"></i> Import Data PPh 21 via Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <?= form_open_multipart('salary/import_pph21', array('id' => 'form-import-pph21')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template Excel:</label><br>
                        <a href="<?= base_url('salary/download_template_pph21'); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-download"></i> Download Template PPh21.xlsx
                        </a>
                </div>
                <hr>
                <div class="form-group">
                    <label>Pilih File Excel (.xlsx / .xls):</label>
                    <input type="file" name="file_excel" class="form-control-file" accept=".xlsx, .xls, .csv" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload & Simpan ke Database</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-pendapatan_lain" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pendapatan Lain</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-pendapatan_lain', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Pendapatan Lainnya (Gaji) :</label>
                        <input type="text" class="form-control input_salary" id="pendapatan" name="pendapatan" required>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Pengurangan Lainnya :</label>
                        <input type="text" class="form-control input_salary" id="pengurangan" name="pengurangan"
                            required>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-pendapatanlain" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-pph21" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form PPh 21</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-pph21', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">PPh 21 :</label>
                        <input type="text" class="form-control input_salary" id="pph21" name="pph21" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control">
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#filter_jenis_penerimaan, #filter_gudang, #filter_month').change(function () {
            updateDatatable()
        })

        $('.input_salary').mask('000.000.000.000', {
            reverse: true
        });
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']], // Urutkan berdasarkan nama
            ajax: {
                url: 'salary/pagination',
                type: 'POST',
                data: function (e) {
                    e.filter_month = $('#filter_month').val(); // Ambil nilai filter bulan
                    e.csrf_token = token; // Pastikan token CSRF tetap terkirim
                }
            },
            columnDefs: [{
                targets: [0],
                className: 'text-center'
            }]
        });
        $('#filter_month').change(function () {
            table.ajax.reload();
        });

        $(document).on('click', '.btn-edit', function () {
            $('.form-control').val(null)
            var object = 'salary'
            $('#main-modal #modal-form').attr('action', 'salary/add')
            $('#main-modal').modal()

            var id = $(this).attr("data-id")
            $('#pengguna_id').val(id)

            fetch(object + '/edit/' + id)
                .then(function (resp) {
                    return resp.json()
                })
                .then(function (data) {
                    $('#main-modal #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal #gaji_pokok').val(data[0].gaji_pokok)
                    $('#main-modal #tunjangan_jabatan').val(data[0].tunjangan_jabatan)
                    $('#main-modal #dasar_potongan_bpjs').val(data[0].dasar_potong_bpjs)
                    $('#main-modal #dasar_potongan_bpjs_tk').val(data[0].dasar_potong_bpjs_tk)
                })
        })

        $(document).on('click', '.btn-komisi', function () {
            $('.form-control').val(null)

            var object = 'salary'
            $('#main-modal-komisi #modal-form-komisi').attr('action', 'salary/add_komisi')
            $('#main-modal-komisi').modal()

            var id = $(this).attr("data-id")
            $('#main-modal-komisi #pengguna_id').val(id)

            fetch(object + '/edit_komisi/' + id)
                .then(function (resp) {
                    return resp.json()
                })
                .then(function (data) {
                    $('#main-modal #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal #komisi').val(data[0].jumlah)
                    $('#main-modal #bulan').val(data[0].bulan)
                })
        })

        $(document).on('click', '.btn-import-komisi', function() {
            $('#modal-import-komisi').modal('show');
        });

        $('#form-import-komisi').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('csrf_token', token);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    var data = typeof resp === 'string' ? JSON.parse(resp) : resp;
                    if (data.status === 'success' || data.type === 'success') {
                        swal.fire('Berhasil', data.message || 'Data Komisi Berhasil Diimpor', 'success');
                        $('#modal-import-komisi').modal('hide');
                        updateDatatable();
                    } else {
                        swal.fire('Gagal', data.message || 'Gagal mengimpor file', 'error');
                    }
                },
                error: function() {
                    swal.fire('Gagal', 'Terjadi kesalahan sistem saat upload file', 'error');
                }
            });
        });

        $(document).on('click', '.btn-import-pph21', function() {
            $('#modal-import-pph21').modal('show');
        });

        $('#form-import-pph21').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('csrf_token', token);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    var data = typeof resp === 'string' ? JSON.parse(resp) : resp;
                    if (data.status === 'success' || data.type === 'success') {
                        swal.fire('Berhasil', data.message || 'Data PPh 21 Berhasil Diimpor', 'success');
                        $('#modal-import-pph21').modal('hide');
                        updateDatatable();
                    } else {
                        swal.fire('Gagal', data.message || 'Gagal mengimpor file', 'error');
                    }
                },
                error: function() {
                    swal.fire('Gagal', 'Terjadi kesalahan sistem saat upload file', 'error');
                }
            });
        });

        $(document).on('click', '.btn-pen_lain', function () {
            $('.form-control').val(null)

            var object = 'salary'
            $('#main-modal-pendapatan_lain #modal-form-pendapatan_lain').attr('action', 'salary/add_pendapatan_lain')
            $('#main-modal-pendapatan_lain').modal()

            var id = $(this).attr("data-id")
            $('#main-modal-pendapatan_lain #pengguna_id').val(id)

            fetch(object + '/edit_pendapatan_lain/' + id)
                .then(function (resp) {
                    return resp.json()
                })
                .then(function (data) {
                    $('#main-modal-pendapatan_lain #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal-pendapatan_lain #pendapatan').val(data[0].jumlah)
                    $('#main-modal-pendapatan_lain #pengurangan').val(data[0].pengurangan)
                })
        })

        $(document).on('click', '.btn-pph21', function() {
            $('.form-control').val(null)
           
            var object = 'salary'
            $('#main-modal-pph21 #modal-form-pph21').attr('action', 'salary/add_pph21')
            $('#main-modal-pph21').modal()

            var id = $(this).attr("data-id")
            $('#main-modal-pph21 #pengguna_id').val(id)

            fetch(object + '/edit_pph21/' + id)
                .then(function(resp) {
                    return resp.json()
                })
                .then(function(data) {
                    $('#main-modal-pph21 #pengguna_id').val(data[0].pengguna_id)
                    $('#main-modal-pph21 #pph21').val(data[0].jumlah)
                })
        })

        $(document).on('click', '.btn-print', function () {
            var print = $(this).attr('print')
            var link = 'salary/show/print/' + print
            window.open('<?= base_url() ?>' + link)
        })

        $(document).on('click', '.btn-print-salary', function () {
            var print = $(this).attr('print');
            var month = $('#filter_month').val() || '<?= date("Y-m") ?>';
            var link = 'salary/print/' + month;
            window.open('<?= base_url() ?>' + link);
        });
        $('#btn-tutupbuku').click(function() {
            var print = $(this).attr('print')
            console.log(print);
            var link = 'salary/tutupbuku'
            window.open('<?= base_url() ?>' + link,"_self")
        })
        $('#btn-history').click(function() {
            var print = $(this).attr('print')
            console.log(print);
            var link = 'salary/history_salary'
            window.open('<?= base_url() ?>' + link,"_self")
        })
        $(document).on('click', '.btn-resign', function () {
            var print = $(this).attr('print')
            var link = 'salary/print_resign/'
            window.open('<?= base_url() ?>' + link)
        })
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>
