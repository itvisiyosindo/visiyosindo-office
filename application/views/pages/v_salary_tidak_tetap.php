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

            <div class="row">
                <div class="col-md-2">
                    <small>Filter By Month:</small>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                    </div>
                </div>
                <div class="col-md-2 mt-4">
                    <button type="button" id="btn-print" class="btn btn-primary"><i class="icons fas fa-print"></i> Print</button>
                </div>
                <div class="col-md-2 mt-4">
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
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- <div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
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
                        <label class="form-control-label">Tunjangan Kinerja :</label>
                        <input type="text" class="form-control input_salary" id="tunjangan_kinerja" name="tunjangan_kinerja" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Tunjungan Konsumsi :</label>
                        <input type="text" class="form-control input_salary" id="tunjangan_konsumsi" name="tunjangan_konsumsi" required>
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
</div> -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter_month').on('change dp.change', function() {
            updateDatatable()
            updateTotals()
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
                url: 'salary_tidak_tetap/pagination',
                type: 'POST',
                data: function(e) {
                    // e.filter_gudang = $('#filter_gudang').val()
                    // e.filter_jenis_penerimaan = $('#filter_jenis_penerimaan').val()
                    e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                className: 'text-center'
            }]
        })

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