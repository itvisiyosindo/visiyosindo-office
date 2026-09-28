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
			
				<!--<a href="javascript:;" id="btn-show-ajuTraining-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Ajukan Training</a>-->
				
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode </th>
							<th> Nama Pengaju</th>
							<th> Jabatan</th>
							<th> Nama Training</th>
							<th> Penyelenggara</th>
							<th> Tanggal</th>
							<th> Biaya</th>
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
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'training/pagination/training',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
				className: 'text-center'
			}]
		})
		
		

		
		
		

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>