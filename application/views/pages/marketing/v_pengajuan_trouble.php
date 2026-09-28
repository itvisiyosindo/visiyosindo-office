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
				<a href="fpp/show/pengajuan/trouble" id="btn-a-gc" class="btn btn-sm btn-success"><i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan</a>
				
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> No</th>
							<th> Nama Customer</th>
							<th> Marketing</th>
							<th> Invoice</th>
							<th> Status</th>
							<th> Aksi</th>
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
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'fpp/pagination/trouble',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6],
				className: 'text-center'
			}]
		})

		



	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>