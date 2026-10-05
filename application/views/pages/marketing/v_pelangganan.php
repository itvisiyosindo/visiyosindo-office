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
		<!-- <div class="card-body">
			<?php if (sessPenggunaId() == 72 || sessPenggunaId() == 69 || sessPenggunaId() == 744 || sessPenggunaId() == 1 || sessPenggunaId() == 755) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Pelanggan</a>
			<?php } ?>
		</div> -->
		<br>
		<div class="card-body">
		<div class="table-responsive">
<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
	<thead>					
		<tr>
			<!-- <th> No </th> -->
			<th> # </th>
			<th> ID </th>
			<th> ID CALON </th>
			<th> Kode</th>
			<th> Kode</th>
			<th> Nama </th>
			<th> NIK </th>
			<th> No. NPWP </th>
			<th> Nama NPWP </th>
			<th> Keterangan </th>
			<th> Tanggal </th>
			<?php  if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || sessPenggunaId() == 755){ ?>
				<th> Modality </th>
				<th> Aksi </th>
			<?php } ?>
		</tr>
	</thead>
	 <!-- <tbody id="list">
Untuk menampilkan datanya, menggunakan JQuery + AJAX 
      </tbody> -->
</table>
			</div>
		</div>
	</div>
</div>
<div id="main-modal-modality" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Modality</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-modality', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idcalonpelanggan" name="idcalonpelanggan" required>
					</div>	
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idpelanggan" name="idpelanggan" required>
					</div>		
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="kodepelanggan" name="kodepelanggan" required>
					</div>				
					<div class="form-group">
						<label class="control-label">Kategori</label>
						 <select class="select-transaction input-group-sm form-control" name="kategori" id="kategori">
								 <?php if ($kategorimodality != NULL): ?>
                      <option value=''>— Pilih Kategori —</option>
                      <?php foreach ($kategorimodality as $value): ?>
                      <option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
                      <?php endforeach;?>
                      <?php else:?>
                      <option value=''>— Tidak ada data —</option>
                      <?php endif;?>
                      </select>
                      <?php echo form_error('kategori');?>
					</div>				
					<div class="form-group">
						<label for="merk" class="form-control-label">Merk <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="merk" name="merk" required>
					</div>					
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						 <div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_2'>
								<thead>
									<tr>
										<th> # </th>
										<th> Kategori</th>
										<th> Nama </th>
										<th> Merk </th>
										<th> Tanggal </th>
										<th> Aksi </th>
									</tr>
								</thead>
								
							</table>
						 </div>
					</div>
				</div>

			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pelanggan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
					<div class="form-group">
						<label for="kontak" class="form-control-label">Kontak <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kontak" name="kontak" required>
					</div>					
					<div class="form-group">
						<label for="kota" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kota" name="kota" required>
					</div>
					<div class="form-group">
						<label for="provinsi" class="form-control-label">Provinsi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="provinsi" name="provinsi" required>
					</div>
					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea name="alamat" class="form-control" id="alamat" cols="15" rows="3"></textarea>
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
<div id="main-modal-pelangganan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Update Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-pelangganan', 'autocomplete' => 'off')); ?> 
				<div class="modal-body">
					<div class="dt-pelangganan-form">
						 <div class="form-group">
							<div>
								<label for="kodecustomer" class="form-control-label">Pelanggan <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control col-sm-5" id="idcalon" name="idcalon" hidden/>
								<input type="text" class="form-control col-sm-5" id="kodecustomer" name="kodecustomer" readonly required/>
								<input type="text" class="form-control" id="namapelanggan" name="namapelanggan" readonly required/>
							</div>
						</div> 
						<div class="form-group">
							<label for="nik" class="form-control-label">NIK <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="nik" name="nik" required>
						</div>
						<div class="form-group">
							</br>
							<label for="nonpwp" class="form-control-label">No NPWP <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="nonpwp" name="nonpwp" required>
						</div>
						<div class="form-group">
							<label for="namanpwp" class="form-control-label">Nama NPWP <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="namanpwp" name="namanpwp" required>
						</div>	
						<div class="form-group">
								<label for="jenisfakturpajak" class="form-control-label">Jenis Faktur Pajak <span class="text-danger">*</span> :</label>
								<select class="select-transaction input-group-sm form-control" name="jenisfakturpajak" id="jenisfakturpajak">
								<?php if ($jenisfakturpajak != NULL): ?>
													<option value=''>— Pilih Jenis Faktur Pajak —</option>
													<?php foreach ($jenisfakturpajak as $value): ?>
													<option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
													<?php endforeach;?>
													<?php else:?>
													<option value=''>— Tidak ada data —</option>
													<?php endif;?>
													</select>
													<?php echo form_error('jenisfakturpajak');?>
						</div>
						<div class="form-group">
								<label for="syaratpembayaran" class="form-control-label">Syarat Pembayaran <span class="text-danger">*</span> :</label>
								<select class="select-transaction input-group-sm form-control" name="syaratpembayaran" id="syaratpembayaran">
								<?php if ($jenisfakturpajak != NULL): ?>
													<option value=''>— Pilih Syarat Pembayaran —</option>
													<?php foreach ($syaratpembayaran as $value): ?>
													<option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
													<?php endforeach;?>
													<?php else:?>
													<option value=''>— Tidak ada data —</option>
													<?php endif;?>
													</select>
													<?php echo form_error('syaratpembayaran');?>
							</div>
							<div class="form-group">
								<label for="pengirimandokumen" class="form-control-label">Pengiriman Dokumen Pembayaran <span class="text-danger">*</span> :</label>
								<select class="form-control" id="pengirimandokumen" name="pengirimandokumen" required>
									<option value="">- Pilih Jenis Pengiriman Dokumen -</option>
									<option value="1">Softcopy</option>
									<option value="2">Hardcopy</option>
								</select>
							</div>
							<div class="form-group">
								<label for="statuspiutang" class="form-control-label">Status Piutang <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control" id="statuspiutang" name="statuspiutang" required>
							</div>
							<div class="form-group">
								<label for="limitpiutang" class="form-control-label">Limit Piutang <span class="text-danger">*</span> :</label>
								<input type="number" class="form-control" id="limitpiutang" name="limitpiutang" required>
							</div>
							<div class="form-group">
								<label for="alamatpengiriman" class="form-control-label">Alamat Pengiriman <span class="text-danger">*</span> :</label>
								<textarea name="alamatpengiriman" class="form-control" id="alamatpengiriman" cols="15" rows="3"></textarea>
							</div>
							<div class="form-group">
								<label for="alamatpenagihan" class="form-control-label">Alamat Penagihan <span class="text-danger">*</span> :</label>
								<textarea name="alamatpenagihan" class="form-control" id="alamatpenagihan" cols="15" rows="3"></textarea>
							</div>
							<div class="form-group">
								<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
								<textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"></textarea>
							</div>			
					<div>		
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
						<button type="button" id="btn-edit-form-pelanggan" class="btn btn-success btn-edit" data-dismiss="modal">Update</button>
					</div>											
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
			
		
			ajax: {
				url: 'pelangganan/pagination',
				type: 'POST',
				 data: function(e) {
				 	e.tahun = $('#tahun').val()
				 	e.csrf_token = token
				 },
				
			},
			columns: [
				{ "width": "1%" },
				{ "width": "1%" },
				{ "width": "1%" },
				{ "width": "1%" },
				{ "width": "11%" },
				{ "width": "20%" },
				{ "width": "10%" },
				{ "width": "10%" },
				{ "width": "10%" },
				{ "width": "8%" },
				{ "width": "8%" },
				{ "width": "7%" },
				{ "width": "7%" }
			],

			columnDefs: [
       { visible: false,  targets: [1,2,3] }
    	]
		})

		 
	table2 = $('#kt_table_2').DataTable({
				responsive: false,
				processing: true,
				serverSide: true,
			
				ajax: {
					url: 'pelangganan/paginationmodality/12k72k',
					type: 'POST',
					data: function(e) {
						e.csrf_token = token
					},
					
				},
			})

			
		// function updateDatatable() {
		// 		table.ajax.reload(null, false)
		// }

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'pelanggan'
			$('#main-modal #modal-form').attr('action', 'pelanggan/add')
			$('#main-modal').modal()
		})

		$("#kt_table_1").on('click','.btn-dark',function(){	
			idpelanggan='';
			var currentRow = table.row($(this).parents("tr")).data(); 
			var idpel = currentRow[1];
			var idcalonpel = currentRow[2];
			var kodepel = currentRow[3];

			//console.log(kodepel.innertext)
			//console.log(kodepel.innerHTML)

		
			//console.log(idpel);
		//	console.log(idcalonpel);
			//console.log(kodepel);
			
			$('#kodepelanggan').val(kodepel);
			$('#idpelanggan').val(idpel);
			$('#idcalonpelanggan').val(idcalonpel);
			table2.ajax.url('pelangganan/paginationmodality/'+idcalonpel).load();
			 
			//var object = 'pelangganan'

	 		$('#main-modal-modality #modal-form-modality').attr('action', 'pelangganan/addmodality')
	 		$('#main-modal-modality').modal()
			 $('#kt_table_2').DataTable().ajax.reload();
			
    });


		$(document).on('click', '.btn-delete', function(){
				 var currentRow = table.row($(this).parents("tr")).data();  
		 		 var idc = currentRow[1];
				  var namacalon = currentRow[5];
				var par = $(this).data("id")
					$.ajax({
							type: "POST",
							url: "pelangganan/delete/"+par,
							async: false,
					});
				
			});

		$(document).on('click', '.btn-edit', function() {
					var par = $(this).data("id"); 
					var url ="pelangganan/edit/"+par
					
					$.ajax({
							type: "GET",
							url: url,
							success: function(response) {     
								console.log(response)  	
								if (response) {
									  result = JSON.parse(response);
										$('#main-modal-pelangganan  #idcalon').val(result['id']);
										$('#main-modal-pelangganan #kodecustomer').val(result['kodecustomer']);
										$('#main-modal-pelangganan #namapelanggan').val(result['namacaloncustomer']);
										$('#main-modal-pelangganan #jenisfakturpajak').val(result['idjenisfakturpajak']);
										$('#main-modal-pelangganan #nik').val(result['nik']);
										$('#main-modal-pelangganan #nonpwp').val(result['nonpwp']);
										$('#main-modal-pelangganan #namanpwp').val(result['namanpwp']);
										$('#main-modal-pelangganan #syaratpembayaran').val(result['idsyaratpembayaran']);
										$('#main-modal-pelangganan #pengirimandokumen').val(result['pengirimandokumen']);
										$('#main-modal-pelangganan #statuspiutang').val(result['statuspiutang']);
										$('#main-modal-pelangganan #limitpiutang').val(result['limitpiutang']);
										$('#main-modal-pelangganan #alamatpengiriman').val(result['alamatpengiriman']);
										$('#main-modal-pelangganan #alamatpenagihan').val(result['alamatpenagihan']);
										$('#main-modal-pelangganan #keterangan').val(result['keterangan']);
										$('#main-modal-pelangganan').modal();
								}
    					},
							error: function (request, status, error) {
									alert(request.responseText);
							}					
				});
				
				
			})
		$('#btn-edit-form-pelanggan').click(function() {
			$.ajax({
							type: "POST",
							url: "pelangganan/update",
							cache: false,
							data: {
									id: $('#main-modal-pelangganan  #idcalon').val(),
									kodecustomer: $('#main-modal-pelangganan #kodecustomer').val(),
									namapelanggan: $('#main-modal-pelangganan #namapelanggan').val(), 
									jenisfakturpajak: $('#main-modal-pelangganan #jenisfakturpajak').val(),
									nik: $('#main-modal-pelangganan #nik').val(),
									nonpwp: $('#main-modal-pelangganan #nonpwp').val(),
									namanpwp: $('#main-modal-pelangganan #namanpwp').val(),
									syaratpembayaran: $('#main-modal-pelangganan #syaratpembayaran').val(),
									pengirimandokumen: $('#main-modal-pelangganan #pengirimandokumen').val(),
									statuspiutang: $('#main-modal-pelangganan #statuspiutang').val(),
									limitpiutang: $('#main-modal-pelangganan #limitpiutang').val(),  
									alamatpengiriman: $('#main-modal-pelangganan #alamatpengiriman').val(),  
									alamatpenagihan: $('#main-modal-pelangganan #alamatpenagihan').val(),  
									keterangan: $('#main-modal-pelangganan #keterangan').val(),  									
							},
							success: function(result) { 
									 var pesan = $.parseJSON(result)
									 Swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: pesan.msg,
                    showConfirmButton: false,
                    timer: 2000
                });
    					},						
				});
				window.location.reload();
	  })
        $("#kt_table_2").on('click','.btn-deletemodality',function(e){	
			e.preventDefault();
			 swal.fire({
					title: "Apakah Yakin menghapusnya..?",
					text: "Data tidak dapat dikembalikan, jika ingin dikembalikan hubungi Administrator!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Ya, hapus..!",
					closeOnConfirm: false
				}).then((result) => {
                    if (result.isConfirmed) {
                       var par = $(this).data("id"); 
												$.ajax({
															type: "POST",
															url: "calonpelanggan/deletemodality/"+par,
															async: false,
												});		
												$('#kt_table_2').DataTable().ajax.reload();
										}
							})
			})
		
	})

	
</script>
