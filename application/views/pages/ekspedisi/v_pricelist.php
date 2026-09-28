<!-- 
	Create by KURNIAWAN  
	23-06-2025
-->

<header class="page-header">
	<h2><i class="fas fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
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
							<th> Nama Ekspedisi</th>
							<th> Pricelist </th>
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

				ajax: {
						url: 'ekspedisi/paginationPricelist',
						type: 'POST',
						data: function(e) {
								e.csrf_token = token
						}
				},
				order: [[1, 'asc']], // Kolom ke-1: Brand, kolom ke-2: Nama Produk
				columnDefs: [
						{
								targets: [0,2], className: 'text-center' // tengah
						},
						{
								targets: [1], className: 'text-left' // kiri
						}
				]
		});



		
	})


	function updateDatatable() {
		table.ajax.reload(null, false)
	}


	</script>

	