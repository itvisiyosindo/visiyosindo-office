<header class="page-header">
	<h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<?php if(sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 ) { ?>
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Gudang Pengiriman</th>
							<th> Nama Customer </th>
							<th> Nama Barang </th>
							<th> No SJ </th>
							<th> Alamat Penerima </th>
							<th> Nama Ekspedisi </th>
							<th> Estimasi Penerimaan </th>
							<th> Status </th>
							<th> Tracking </th>
							<th> Keterangan </th>
							<th> Aksi </th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Tracking Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="id_gudang" class="form-control-label">Alamat Pengiriman <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_gudang" name="id_gudang" required>
							<option value="">- Pilih Gudang -</option>
							<?php
							foreach ($list_gudang as $row) {
								echo '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="id_customer" class="form-control-label">Nama Customer <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_customer" name="id_customer" required>
							<option value="">- Pilih Customer -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->id_customer . '">' . $row->nama_customer . '</option>';
							}
							?>
						</select>
					</div>		
					<div class="form-group">
						<label for="nama_barang" class="form-control-label">Nama Barang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
					</div>	
					<div class="form-group">
						<label for="pic_penerima" class="form-control-label">PIC Penerima <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="pic_penerima" name="pic_penerima" required>
					</div>
					<div class="form-group">
						<label for="alamat_penerima" class="form-control-label">Alamat Penerima <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="alamat_penerima" name="alamat_penerima" required></textarea>
					</div>
					
					<!-- <div class="form-group">
						<label for="id_ekspedisi" class="form-control-label">Pilih Ekspedisi <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_ekspedisi" name="id_ekspedisi" required>
								<option value="">- Pilih Ekspedisi -</option>
								<option value="1">Diantarkan Langsung</option>
								<option value="2">Ekspedisi</option>
								
						</select>
					</div>
					<div class="form-group">
						<label for="nama_ekspedisi" class="form-control-label">Diantarkan Oleh  <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="nama_ekspedisi" name="nama_ekspedisi" required></textarea>
					</div>
					<div class="form-group">
						<label for="id_ekspedisi" class="form-control-label">Nama Ekspedisi <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_ekspedisi" name="id_ekspedisi" required>
								<option value="">- Pilih Ekspedisi -</option>
								<?php
								foreach ($list_eks as $row) {
										echo '<option value="' . $row->id_ekspedisi . '" data-nama="' . $row->nama_ekspedisi . '">' . $row->nama_ekspedisi . '</option>';
								}
								?>
						</select>
				</div> -->

				<div class="form-group">
    <label for="id_ekspedisi" class="form-control-label">Pilih Ekspedisi <span class="text-danger">*</span> :</label>
    <select data-plugin-selectTwo class="form-control populate" id="id_ekspedisi" name="id_ekspedisi" required>
        <option value="">- Pilih Ekspedisi -</option>
        <option value="1">Diantarkan Langsung</option>
        <option value="2">Ekspedisi</option>
    </select>
</div>

<div class="form-group" id="form_diantarkan" style="display: none;">
    <label for="nama_ekspedisi" class="form-control-label">Diantarkan Oleh <span class="text-danger">*</span> :</label>
    <textarea class="form-control" id="nama_ekspedisi" name="nama_ekspedisi" required></textarea>
    <input type="hidden" name="id_ekspedisi_hidden" value="0">
</div>

<div class="form-group" id="form_nama_ekspedisi" style="display: none;">
    <label for="nama_ekspedisi_dropdown" class="form-control-label">Nama Ekspedisi <span class="text-danger">*</span> :</label>
    <select data-plugin-selectTwo class="form-control populate" id="nama_ekspedisi_dropdown" name="nama_ekspedisi_dropdown" required>
        <option value="">- Pilih Ekspedisi -</option>
        <?php
        foreach ($list_eks as $row) {
            echo '<option value="' . $row->id_ekspedisi . '" data-nama="' . $row->nama_ekspedisi . '">' . $row->nama_ekspedisi . '</option>';
        }
        ?>
    </select>
</div>

					
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tracking" name="id_tracking">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<script>

	  document.getElementById('id_ekspedisi').addEventListener('change', function() {
        const diantarkanForm = document.getElementById('form_diantarkan');
        const namaEkspedisiForm = document.getElementById('form_nama_ekspedisi');
        
        if (this.value === '1') {
            diantarkanForm.style.display = 'block'; // Tampilkan form diantarkan
            namaEkspedisiForm.style.display = 'none'; // Sembunyikan form nama ekspedisi
        } else if (this.value === '2') {
            diantarkanForm.style.display = 'none'; // Sembunyikan form diantarkan
            namaEkspedisiForm.style.display = 'block'; // Tampilkan form nama ekspedisi
        } else {
            diantarkanForm.style.display = 'none'; // Sembunyikan keduanya jika tidak ada pilihan
            namaEkspedisiForm.style.display = 'none';
        }
    });


	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'ekspedisi/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 4, 5, 6, 7, 8, 9],
				className: 'text-center'
			}]
		})

		//$('#btn-show-add-form').click(function() {
		//	$('.form-control').val(null)
		//	$('#main-modal #modal-form').attr('action', 'tracking/add')
		//	$('#main-modal').modal()
		//})

		$('#btn-show-add-form').click(function() {
    $('.form-control').val(null);
    $('#main-modal #modal-form').attr('action', 'tracking/add');
    
    
    $('#nama_ekspedisi_input').remove();

    
    $('<input>').attr({
        type: 'hidden',
        id: 'nama_ekspedisi_input',
        name: 'nama_ekspedisi', 
        value: ''
    }).appendTo('#main-modal #modal-form');

    $('#main-modal').modal();
		});

		
		$('#id_ekspedisi').change(function() {
				var selectedNamaEkspedisi = $('#id_ekspedisi option:selected').data('nama');
				$('#nama_ekspedisi_input').val(selectedNamaEkspedisi);
		});




		$(document).on('click', '.btn-edit', function() {
			var object = 'ekspedisi'
			$('#main-modal #modal-form').attr('action', 'ekspedisi/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_ekspedisi').val(data[0].id_ekspedisi)
					$('#main-modal #nama_ekspedisi').val(data[0].nama_ekspedisi)
					$('#main-modal #alamat_ekspedisi').val(data[0].alamat_ekspedisi)
					$('#main-modal #contact').val(data[0].contact)
					$('#main-modal #nama_pic').val(data[0].nama_pic)
					$('#main-modal #jabatan').val(data[0].jabatan)
					$('#main-modal #mou').val(data[0].mou)
					$('#main-modal #legalitas').val(data[0].legalitas)
					$('#main-modal #identitas').val(data[0].identitas)
					$('#main-modal #link_tracking').val(data[0].link_tracking)
					$('#main-modal #keterangan').val(data[0].keterangan)
				})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}

	


</script>