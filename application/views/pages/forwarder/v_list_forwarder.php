<!-- 
	Create by KURNIAWAN  
	30-06-2025
-->

<header class="page-header">
	<h2><i class="fas fa-plane"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="d-flex justify-content-end mb-2">
			<a href="<?= base_url('export_persetujuan?jenis=forwarder') ?>" class="btn btn-sm btn-success shadow-sm">
				<i class="fas fa-file-excel mr-1"></i> Export Excel
			</a>
		</div>
		<div class="card-body">
			<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
							<thead>			
								<tr>
									<th> No </th>
									<th> Kode</th>
									<th> Nama Shipment</th>
									<th> Port of Loading (POL)</th>
									<th> Port of Discharge (POD)</th>
									<th> Kurs ke IDR</th>
									<th> Diajukan Oleh</th>
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
			
			// order: [
			// 	[0, 'ASC']
			// ],
			ajax: {
				url: 'forwarder/pagination_list',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 3, 4, 5, 6, 7, 8],
				className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		
		

		
	})

	
</script>
