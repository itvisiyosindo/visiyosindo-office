<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
				<a href="visilab/show/permintaan/laporan_stok" id="btn-a-per_biaya" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan
				</a>
		</div>
		
				
		<br>
		<div class="card-body">
			<div class="row">
				
				
				<div class="col-md-3">
					<small> <i class="fas fa-print"></i> Print By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" style="text-align: center;" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="print_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
			</div><br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode</th>
							<th> Nama Pengguna</th>
							<th> Tanggal</th>
							<th> Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#print_month').change(function() {
			window.location.href = '<?= base_url() ?>' + 'visilab/print_page/printByMonth/' + $('#print_month').val();
		})



		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [ 
				[0, 'desc']
			],
			
			ajax: {
				url: 'visilab/pagination/laporan_stok',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4],
				className: 'text-center'
			}]
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>