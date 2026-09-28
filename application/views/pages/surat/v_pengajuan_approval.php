<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<div id="main-modal-approval" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail Approval</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-approval', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-approval-form">
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idapproval" name="idapproval" required>
						<input type="text" class="form-control" id="iddetail" name="iddetail" required>
					</div>	
					<div class="form-group">
						<label for="merk" class="form-control-label">Nama Barang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namabarangsebelum" name="namabarangsebelum" required hidden>
						<input type="text" class="form-control" id="namabarang" name="namabarang" required>
					</div>					
					<div class="form-group">
						<label for="nama" class="form-control-label">Acuan Harga Terendah <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="acuanharga" name="acuanharga" required>
						<input type="number" class="form-control" id="acuanhargasebelum" name="acuanhargasebelum" required hidden>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Harga yang akan ditawarkan <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="harga" name="harga" required>
						<input type="number" class="form-control" id="hargasebelum" name="hargasebelum" required hidden>
					</div>
					<div class="form-group">
							<label for="alasan" class="form-control-label">Alasan Perubahan <span class="text-danger">*</span> :</label>
							<textarea name="alasan" class="form-control" id="alasan" cols="15" rows="3"></textarea>
						</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button id="btn-updateapproval" type="button" class="btn btn-success btn-save" >Update</button>
			</div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						 <div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_2'>
								<thead>
									<tr>
										<th> # </th>
										<th> ID</th>
										<th> Nama </th>
										<th> Acuan </th>
										<th> Harga </th>
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
<div class="row">
	<div class="col">
		<div class="">
				<a href="surat/show/pengajuan/permintaan_approval" id="btn-a-per_biaya" class="btn btn-sm btn-success">
				    <i class="fas fa-plus"></i>&nbsp;&nbsp;Ajukan Approval Harga
				</a>
				
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Print Rekapan</a>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode Surat</th>
							<th> Tanggal </th>
							<th> Kategori</th>
							<?php if(isStafAdmin()){ ?>
								<th> Nama Marketing </th>
							<?php } ?>
							<th> Status</th>
							<th> Aksi</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Approval  </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
				    <div class="form-group" style="display: flex;">
				        <div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>	
					
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
		    	<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form" >Export Excel</button>
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
			order: [ 
				[0, 'desc']
			],
			ajax: {
				url: 'surat/pagination/my_surat_approval',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5],
				className: 'text-center'
			}]
		})
		
    	table2 = $('#kt_table_2').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [ 
				[0, 'desc']
			],
			
			ajax: {
				url: 'surat/paginationapproval/' + 'UXNhOENlQVBqYWNjQWtnYjltNGROQT09',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				},
				columns: [
				{ "width": "1%" },
				{ "width": "1%" },
				{ "width": "20%" },
				{ "width": "10%" },
				{ "width": "10%" },
				{ "width": "7%" },
			],
				columnDefs: [
    			    {
        				'visible': false, 
        				'targets': [1],
    			    }
    			],
			},
							
		})	
			
		

		$(document).on('click', '.btn-edit', function() {
					var par = $(this).data("id"); 
					var url ="surat/editapproval/"+par
					$('#main-modal-approval #idapproval').val(par)
					$.ajax({
							type: "GET",
							url: url,
							success: function(response) {     
								if (response) {
										//console.log(par);
									  result = JSON.parse(response);
										table2.ajax.url('surat/paginationapproval/' + par).load();
										table2.column(1).visible(false);
										$('#main-modal-approval').modal();
								}
    					},
							error: function (request, status, error) {
									alert(request.responseText);
							}					
				});				
			})

			$(document).on('click', '.btn-editapproval', function() {
					 var currentRow = table2.row($(this).parents("tr")).data();  
		 		 	 var iddetail = currentRow[1];
					 var namabarang = currentRow[2];
					 var acuanharga = currentRow[3];
					 var harga = currentRow[4];
					 $('#iddetail').val(iddetail);
					 $('#namabarangsebelum').val(namabarang);
					 $('#acuanhargasebelum').val(acuanharga);
					 $('#hargasebelum').val(harga);
					 $('#namabarang').val(namabarang);
					 $('#acuanharga').val(acuanharga);
					 $('#harga').val(harga);
			})

		$('#btn-updateapproval').click(function() {
			$.ajax({
							type: "POST",
							url: "surat/updateapproval",
							cache: false,
							data: {
									id: $('#main-modal-approval  #idapproval').val(),
									iddetail: $('#main-modal-approval #iddetail').val(),
									namabarang: $('#main-modal-approval #namabarang').val(), 
									acuanharga: $('#main-modal-approval #acuanharga').val(),
									harga: $('#main-modal-approval #harga').val(),
									namabarangsebelum: $('#main-modal-approval #namabarangsebelum').val(), 
									acuanhargasebelum: $('#main-modal-approval #acuanhargasebelum').val(),
									hargasebelum: $('#main-modal-approval #hargasebelum').val(),
									alasan: $('#main-modal-approval #alasan').val(),									
							},
							success: function(result) { 
									 var pesan = $.parseJSON(result)
									 if(pesan.status!='error'){
										  $('#main-modal-approval').modal('hide');
									 }
									 Swal.fire({
                                        type: pesan.status,
                                        icon: pesan.status,
                                        title: pesan.msg,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
    					},				
				});
	  })
	  
	  $('#btn-laporan-form').click(function() {
		     $('#main-modal-marketing').modal()	
		     
    			
		})

		$("#btn-export").click(function(){
		     
                tglawal = $("#tglawal").val();
                tglakhir = $("#tglakhir").val();
                 window.open("<?php echo base_url(); ?>surat/exportlaporan/search?tglawal="+encodeURIComponent(tglawal)+"&tglakhir="+encodeURIComponent(tglakhir),"_blank");
                $('#main-modal-marketing').modal('hide')
			
        });
	  
	  
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>