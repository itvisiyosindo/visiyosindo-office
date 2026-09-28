<header class="page-header">
	<h2><i class="fas fa-clipboard-list"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
				<a href="laporan/show/list/nilai" id="btn-a-gc" class="btn btn-outline-info">
				    <i class="fas fa-percentage"></i>&nbsp;&nbsp;Nilai Rata-Rata
				</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama</th>
							<th> NPP</th>
							<th> Jabatan</th>
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
    	//pageLength: 25,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'laporan/pagination/allNew',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2],
				className: 'text-center'
			}]
		})

		

		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}



	
</script>