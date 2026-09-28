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
        <div class="row">
				<div class="col-md-2">
					<small>Filter By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
			</div><br>
            <input type="hidden" id="pengguna_id" value="<?= encrypt($pengguna[0]->pengguna_id) ?>">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
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
                url: 'salary_tidak_tetap/detail_salary_tt',
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