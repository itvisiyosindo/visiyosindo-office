<!-- 
	Create by KURNIAWAN  
	23-06-2025
-->

<header class="page-header">
	<h2><i class="icons fas fa-truck-loading"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<style>
	#kt_table_1 thead th {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>


<div class="row">
	<div class="col">
		<div class="">
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Brand</th>
							<th> Nama Product </th>
							<th> Acuan Berat Barang di Expedisi </th>
							<th> Acuan Berat Barang di Expedisi (Rp) </th>
							<th> Acuan Ongkir Maksimal (Rp) </th>
							<th> Perbandingan Ongkir dari PKU </th>
							<th> Perbandingan Ongkir dari JKT </th>
							<th> Perbandingan Ongkir dari Jogja </th>
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
			order: [[0, 'desc']],
			ajax: {
				url: 'ekspedisi/paginationNamaBarang2',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token;
				}
			},
			order: [[1, 'asc'], [2, 'asc']],
			columnDefs: [
					{ targets: [0,3,4,5,6,7,8], className: 'text-center', searchable: false },
					{ targets: [1, 2], className: 'text-left', searchable: false },
					{ targets: [1, 2], searchable: true },
			]

		});



		
		

		
	})


	function updateDatatable() {
		table.ajax.reload(null, false)
	}


	</script>

	