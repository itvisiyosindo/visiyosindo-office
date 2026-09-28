<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<h1>Riwayat Perubahan Salary "<?= $pengguna[0]->nama ?>"</h1>
<div class="row">
    <div class="col">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
					<small> <i class="fas fa-print"></i> Print SLIP GAJI By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="print_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Gaji Pokok </th>
                            <th> Tujangan Jabatan </th>
                            <th> Tanggal Perubahan </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="pengguna_id" value="<?= encrypt($pengguna[0]->pengguna_id) ?>">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // $('#filter_jenis_penerimaan, #filter_gudang, #filter_month').change(function() {
        //     updateDatatable()
        // })

        // $('.input_salary').mask('000.000.000.000', {
        //     reverse: true
        // });
        var pengguna_id = $('#pengguna_id').val()
        table = $('#kt_table_1').DataTable({
            responsive: false,
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: 'salary/getAllRiwayat/' + pengguna_id,
                type: 'POST',
                data: function(e) {
                    // e.filter_gudang = $('#filter_gudang').val()
                    // e.filter_gudang = $('#filter_gudang').val()
                    // e.filter_jenis_penerimaan = $('#filter_jenis_penerimaan').val()
                    // e.filter_month = $('#filter_month').val()
                    e.csrf_token = token
                }
            },
            columnDefs: [{
                targets: [0, 1, 2, 3],
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
                    $('#main-modal #gaji_pokok').val(data[0].gaji_pokok)
                    $('#main-modal #tunjangan_jabatan').val(data[0].tunjangan_jabatan)
                })
        })
        //end file pendukung


        $('#print_month').change(function() {
            var month =$('#print_month').val();
            var id =$('#pengguna_id').val();
            var link ='salary/print_slip_month/' + month +'/'+ id;
			window.open('<?= base_url() ?>' + link)
		})
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>