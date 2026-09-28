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
	</div>
		<br>
		<div class="card-body">
				<button type="button" class="btn btn-success float-left btn-approval" style="margin-right: 10px;" id-Sijk="<?=encrypt($stock_opname[0]->id_so)?>"> <i class="fas fa-check"></i> Kirim Notif </button>
				</br></br></br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama</th>
							<th> NPP</th>
							<th> Jabatan</th>
							<th> Nama Penilai</th>
							<th> Aksi</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Penilai</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">

					<div class="form-group">
						<label for="id_pengguna" class="form-control-label">Nama Pegawai <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_pengguna" name="id_pengguna" disabled>
							
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="penilai_b" class="form-control-label">Nama Penilai <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="penilai_b" name="penilai_b" required>
							<option value="">- Pilih Pegawai-</option>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>
					

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
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
				url: 'laporan/pagination/allRev1',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2, 5],
				className: 'text-center'
			}]
		})


		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal #modal-form').attr('action', 'laporan/updatePenilai')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editPenilai/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_pengguna').val(data[0].id_pengguna).trigger('change');
					$('#main-modal #penilai_b').val(data[0].penilai_b).trigger('change');
					$('#main-modal #id_pelanggan').val(id);
				})
		})

		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Kirim Notifikasi Laporan Mingguan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'laporan/sendNotif',
                        dataType: 'JSON',
                        data: {
                            id : id,
							csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
					
                }
				
            })
        })

		

		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}



	
</script>