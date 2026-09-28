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
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama Pengguna</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Lokasi Absen</h4>
				<button type="button" class="close" style="color:white;margin: -1px" data-dismiss="modal" aria-label="Close"><i class="far fa-times-circle"></i></button>
			</div>
			<div class="modal-body">
				<div id="dvMap" style="height: 700px"></div><br>
				<div class="text-right">
					<!-- <input type="hidden" id='latitude' value="">
					<input type="hidden" id='longitude' value=""> -->
				</div>
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
				url: 'slip_gaji/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0],
				className: 'text-center'
			}]
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>