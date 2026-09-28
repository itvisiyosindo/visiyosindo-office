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
		<div class="card-body">
			<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;  : <?= $pengguna[0]->nama ?></strong>
						<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    : <?= $pengguna[0]->no_pegawai ?></strong>
						<br><strong class="fw-bold text-dark">Jabatan &nbsp; &nbsp; &nbsp;      : <?= $pengguna[0]->jabatan ?></strong>
						<br><br>

			<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
							<thead class="text-center">					
								<tr>
									<th width="10%"> No </th>
									<th> Bulan</th>
									<th> Nilai </th>
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

		
    var pengguna_id = $('#pengguna_id').val()
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			
			// order: [
			// 	[0, 'ASC']
			// ],
			ajax: {
				url: 'knowledge/pagination/' + pengguna_id,
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
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'knowledge'
			$('#main-modal #modal-form').attr('action', 'knowledge/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'knowledge'
			$('#main-modal #modal-form').attr('action', 'knowledge/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_pengguna').val(data[0].id_pengguna).trigger('change');
					$('#main-modal #nilai').val(data[0].nilai)
					$('#main-modal #id_pelanggan').val(id)
				})
		})

		



		
	})

	
</script>
