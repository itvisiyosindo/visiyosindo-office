<header class="page-header">
    <h2>
        <i class="icons fas fa-money-bill"></i>&nbsp;
        <?= $page_title ?>
    </h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li>
                <span>
                    <?= $page_desc ?>
                </span>
            </li>
        </ol>
    </div>
</header>

<div class="row mb-1">
    <div class="col-md-4 col-12 mb-1">
        <div class="card h-100 text-white shadow-sm">
            <div class="card-body bg-info text-center d-flex flex-column justify-content-center align-items-center">
                <i class="fas fa-cogs fa-3x mb-3"></i>
                <h4 class="font-weight-bold">Setting Kategori</h4>
                <p class="text-white">Atur penerima THR Natal / Idul Fitri</p>
                <a href="<?= base_url('setting_thr') ?>" class="btn btn-light text-info font-weight-bold w-75">
                    <i class="fas fa-external-link-alt"></i> Buka Konfigurasi
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-12 mb-1">
        <div class="card h-100 text-white shadow-sm">
            <div class="card-body bg-danger d-flex align-items-center">
                <div class="mr-4">
                    <i class="fas fa-tree fa-4x opacity-50"></i>
                </div>
                <div>
                    <h6 class="text-uppercase mb-1">Penerima THR Natal</h6>
                    <h2 class="mb-0 font-weight-bold display-4">
                        <?= isset($total_natal) ? $total_natal : 0 ?>
                    </h2>
                    <small>Orang Karyawan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-12 mb-1">
        <div class="card h-100 text-white shadow-sm">
            <div class="card-body bg-success d-flex align-items-center">
                <div class="mr-4">
                    <i class="fas fa-mosque fa-4x opacity-50"></i>
                </div>
                <div>
                    <h6 class="text-uppercase mb-1">Penerima THR Idul Fitri</h6>
                    <h2 class="mb-0 font-weight-bold display-4">
                        <?= isset($total_fitri) ? $total_fitri : 0 ?>
                    </h2>
                    <small>Orang Karyawan</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card-body">
            <div class="table-responsive" style="scrollbar-width: thin; scroll-behavior: smooth;">
                <div class="row mb-3">
                    <div class="ml-3">
                        <button type="button" print_thr="gapok" class="btn btn-success btn-print">
                            <i class="icons fas fa-mosque"></i> Print THR Idul Fitri
                        </button>
                    </div>

                    <div class="ml-3">
                        <button type="button" class="btn btn-success btn-export-excel">
                            <i class="icons fas fa-file-excel"></i> Download Excel Idul Fitri
                        </button>
                    </div>

                    <div class="ml-3">
                        <button type="button" print_thr="gapok" class="btn btn-outline-danger btn-print-natal">
                            <i class="icons fas fa-tree"></i> Print Khusus Natal
                        </button>
                    </div>

                    <div class="ml-3">
                        <button type="button" class="btn btn-outline-danger btn-export-excel-natal">
                            <i class="icons fas fa-file-excel"></i> Download Excel Natal
                        </button>
                    </div>

                    <div class="ml-3">
                        <button type="button" print_thr="gapok" class="btn btn-success btn-print2">
                            <i class="icons fas fa-print"></i> Print THR Pihak Ketiga
                        </button>
                    </div>
                </div>

                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan Tetap</th>
                            <th>Total THR</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label">
                    <i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Customer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Gaji Pokok :</label>
                        <input type="text" class="form-control input_salary" id="gaji_pokok" name="gaji_pokok" required />
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Tunjungan Jabatan :</label>
                        <input type="text" class="form-control input_salary" id="tunjangan_jabatan" name="tunjangan_jabatan" required />
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Dasar Potongan BPJS Kesehatan :</label>
                        <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs" name="dasar_potongan_bpjs" required />
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Dasar Potongan BPJS Ketenagakerjaan :</label>
                        <input type="text" class="form-control input_salary" id="dasar_potongan_bpjs_tk" name="dasar_potongan_bpjs_tk" required />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control" />
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-komisi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label">
                    <i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Komisi
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-komisi', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Komisi :</label>
                        <input type="text" class="form-control input_salary" id="komisi" name="komisi" required />
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Bulan :</label>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="bulan" name="bulan" placeholder="Pilih Bulan" required />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control" />
                <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-success btn-save">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div id="main-modal-pendapatan_lain" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 id="modal-label">
                    <i class="flaticon2-avatar icon-2x text-grey-light"></i> Form
                    Pendapatan Lain
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('#', array('id' => 'modal-form-pendapatan_lain', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <label class="form-control-label">Pendapatan Lainnya :</label>
                        <input type="text" class="form-control input_salary" id="pendapatan" name="pendapatan" required />
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Pengurangan Lainnya :</label>
                        <input type="text" class="form-control input_salary" id="pengurangan" name="pengurangan" required />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="pengguna_id" name="pengguna_id" class="form-control" />
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter_jenis_penerimaan, #filter_gudang, #filter_month').change(function() {
            updateDatatable()
        })

        $('.input_salary').mask('000.000.000.000', {
            reverse: true
        });
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'salary/pagination_thr',
                type: 'POST',
                data: function(e) {
                    e.csrf_token = '<?= $this->security->get_csrf_hash(); ?>' // Pastikan token CSRF benar
                }
            },
            columnDefs: [{
                targets: [0],
                className: 'text-center'
            }]
        })

        // 1. PRINT ALL (DEFAULT)
        $(document).on('click', '.btn-print', function() {
            var link = 'salary_thr/print/'
            window.open('<?= base_url() ?>' + link)
        })

        // 2. PRINT KHUSUS NATAL (TAMBAHAN BUTTON)
        $(document).on('click', '.btn-print-natal', function() {
            // Mengarah ke fungsi print dengan parameter 'natal'
            var link = 'salary_thr/print/natal'
            window.open('<?= base_url() ?>' + link)
        })

        // 3. PRINT PIHAK KETIGA
        $(document).on('click', '.btn-print2', function() {
            var link = 'salary_thr/print_pihak/'
            window.open('<?= base_url() ?>' + link)
        })

        // 4. EXPORT EXCEL IDUL FITRI
        $(document).on('click', '.btn-export-excel', function() {
            var link = 'salary_thr/export_excel'
            window.open('<?= base_url() ?>' + link)
        })

        // 5. EXPORT EXCEL NATAL
        $(document).on('click', '.btn-export-excel-natal', function() {
            var link = 'salary_thr/export_excel_natal'
            window.open('<?= base_url() ?>' + link)
        })

    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>