<header class="page-header">
	<h2><i class="icons fa fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nomor</th>
							<th> Nama Customer</th>
							<th> Nomor PO/INV</th>
							<th> Marketing </th>
							<th> Pembayaran </th>
							<th> Diajukan Oleh </th>
							<th> Tanggal </th>
							<th> Status </th>
							<th> Aksi </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>



<script>
	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,

			// order: [
			// 	[0, 'ASC']
			// ],
			ajax: {
				url: 'surat_new/pagination/list_app_pajak',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 3, 4, 5, 6, 7, 8, 9],
				className: 'text-center'
			}]
		})

		function updateDatatable() {
			table.ajax.reload(null, false)
		}






	})
</script>