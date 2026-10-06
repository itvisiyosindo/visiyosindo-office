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
        <h2>Detail Salary Tidak Tetap  "<?= $pengguna[0]->nama ?>"</h1>
        <div class="card-body">
        <div class="row align-items-end mb-2">
				<div class="col-md-3 col-sm-6 mb-2">
					<small class="font-weight-bold">Filter By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
				<div class="col-md-9 col-sm-6 mb-2 text-right">
					<button type="button" id="btn-print-rekap" class="btn btn-sm btn-danger text-white mr-1" title="Cetak Rekapitulasi Tunjangan Tidak Tetap (PDF)">
						<i class="fas fa-file-pdf mr-1"></i> Cetak Rekap Tunjangan (PDF)
					</button>
					<button type="button" id="btn-print-slip" class="btn btn-sm btn-primary" title="Cetak Slip Gaji">
						<i class="fas fa-file-invoice-dollar mr-1"></i> Cetak Slip Gaji
					</button>
				</div>
			</div><br>
            <input type="hidden" id="pengguna_id" value="<?= encrypt($pengguna[0]->pengguna_id) ?>">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Tanggal </th>
                            <th> Tunjangan Kinerja (Rp) </th>
                            <th> Tunjangan Konsumsi (Rp) </th>
                            <th> Keterangan </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter_month').change(function() {
            updateDatatable()
        })

        $('#btn-print-rekap').click(function() {
            var month = $('#filter_month').val() || '<?= date("Y-m") ?>';
            window.open('<?= base_url("salary_tidak_tetap/show/print/") ?>' + month, '_blank');
        });

        $('#btn-print-slip').click(function() {
            var month = $('#filter_month').val() || '<?= date("Y-m") ?>';
            var id = $('#pengguna_id').val();
            window.open('<?= base_url("salary/print_slip_month/") ?>' + month + '/' + id, '_blank');
        });

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
                url: 'salary_tidak_tetap/pagination/detail_salary_tidak_tetap',
                type: 'POST',
                data: function(e) {
                    e.filter_month = $('#filter_month').val()
                    e.pengguna_id = $('#pengguna_id').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0,1, 2],
                className: 'text-center'
            }]
        })
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>