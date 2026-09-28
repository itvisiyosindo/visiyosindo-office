<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div id="main-modal-pic" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form PIC</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
		<?= form_open('#', array('id' => 'modal-form-pic', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-pic-form">
                     <div class="form-group" hidden>
                        <input type="text" class="form-control" id="idpic" name="idpic" value="<?= encrypt($datacaloncustomer->id) ?>"  autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-9" for="namapic">Nama PIC:</label>
                        <input type="text" class="form-control" id="namapic" placeholder="Masukkkan Nama PIC" name="namapic" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-9" for="jabatanpic">Jabatan PIC:</label>
                        <input type="email" class="form-control" id="jabatanpic" placeholder="Masukkan Jabatan PIC" name="jabatanpic" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="teleponpic">Telepon PIC:</label>
                        <input type="number" class="form-control" id="teleponpic" placeholder="Masukkan Telepon PIC" name="teleponpic" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				<button type="button" id="btnclose" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                <button type="button" id="edit-pic" class="btn btn-success btn-edit hidden">Edit</button>
				<button type="button" id="add-pic" class="btn btn-success btn-save">Simpan</button>
			</div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						 <div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_1'>
								<thead>
									<tr>
										<th> # </th>
										<th> Nama</th>
										<th> Jabatan </th>
										<th> Telepon </th>
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
<div class="col-xl-8 mb-8 mb-xl-0;" style="margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:5%">
        <div class="text-center">
            <h2>Detail Data Calon  </h2>
        </div>

        <?= form_open('tiket/update/edit_on_detail', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
        <!-- value preview -->
        <div id="value_preview">
            <div class="form-group mb-2 pt-1 col-sm-4">
                <label class="col-form-label">Kode Calon Customer <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="kodecaloncustomer" name="kodecaloncustomer" value="<?= $datacaloncustomer->kodecaloncustomer ?>" readonly>
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label class="col-form-label">Nama Calon Customer <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="namacaloncustomer" name="namacaloncustomer" value="<?= $datacaloncustomer->namacaloncustomer ?>">
            </div>
            <div class="form-group mb-2 pt-1" style="display: flex;">
                <div style="flex: 50%; padding: 10px;">
                    <label class="col-form-label">Type Calon Customer <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="tipecustomer" name="tipecustomer" value="<?= $datacaloncustomer->tipecustomer ?>">
                </div>
                <div style="flex: 50%; padding: 10px;" id='dpihakketiga'>
                    <label class="col-form-label">Nama Pihak Ketiga <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="namapihakketiga" name="namapihakketiga" value="<?= $datacaloncustomer->namapihakketiga ?>">
                </div>
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label class="col-form-label">Kelas Calon Customer <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="kelascustomer" name="kelascustomer" value="<?= $datacaloncustomer->kelascustomer ?>">
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label class="col-form-label">Provinsi <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="provinsi" name="provinsi" value="<?= $datacaloncustomer->provinsi ?>">
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label class=" col-form-label">Kota <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="kota" name="kota" value="<?= $datacaloncustomer->tipe.' '.$datacaloncustomer->kota ?>">
            </div>
             <div class="form-group mb-2 pt-1 col-sm-12">
                <label for="alamat" class="col-form-label">Alamat <span class="text-danger">*</span> :</label>
				<textarea name="alamatcaloncustomer" class="form-control" id="alamatcaloncustomer" cols="15" rows="3"><?= $datacaloncustomer->alamatcaloncustomer ?></textarea>
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label for="pic" class="form-control-label">PIC <span class="text-danger">*</span> :</label>
				<select data-plugin-selectTwo class="form-control populate" name="pic[]" id="pic[]" data-placeholder="Data PIC" multiple disabled >
					<?php
						foreach ($pic as $row) {
						    echo '<option value="' . $row->id . '" selected="selected">' . $row->namapic . '</option>';
						}
					?>
				</select>
				<span class="input-group-append float-right">
			        <button type="button" name="btnaddpic" id="btnaddpic" class="btn btn-success btn-xs">Tambah PIC</button> 
				</span>
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="email" name="email" value="<?= $datacaloncustomer->email ?>">
            </div>
            <div class="form-group mb-2 pt-1 col-sm-12">
                <label class="col-form-label">Website <span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="website" name="website" value="<?= $datacaloncustomer->website ?>">
            </div>
        </div>
        <div class="row-action-buttons">
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal">Kembali</button>
        </div>
        <?= form_close(); ?>    
    </div>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('click', '.btn-edit', function() {
			        $('#main-modal-pic #idpic').val('');
					$('#main-modal-pic #namapic').val('');
                    $('#main-modal-pic #jabatanpic').val('');
                    $('#main-modal-pic #teleponpic').val('');
                   
					var par = $(this).data("id"); 
					var url ="calonpelanggan/editpic/"+par
					
					$.ajax({
							type: "GET",
							url: url,
							success: function(response) {     
							 if (response) {
								 	  result = JSON.parse(response);
                                      $("#main-modal-pic #add-pic").addClass("hidden");
                                      $("#main-modal-pic #edit-pic").removeClass("hidden");
								 	  $('#main-modal-pic #idpic').val(result['id']);
								 	  $('#main-modal-pic #namapic').val(result['namapic']);
                                      $('#main-modal-pic #jabatanpic').val(result['jabatanpic']);
                                      $('#main-modal-pic #teleponpic').val(result['teleponpic']);
								 }
    					},
							error: function (request, status, error) {
									alert(request.responseText);
							}					
				});
				
				
			})

            $('#edit-pic').click(function() {
			$.ajax({
							type: "POST",
							url: "calonpelanggan/updatepic",
							cache: false,
							data: {
									id: $('#main-modal-pic #idpic').val(),
									namapic: $('#main-modal-pic #namapic').val(),
									jabatanpic: $('#main-modal-pic #jabatanpic').val(), 
									teleponpic: $('#main-modal-pic #teleponpic').val()									
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
        if  ($("#idpic").val()!=''){
             table1 = $('#kt_table_1').DataTable({
				responsive: false,
				processing: true,
				serverSide: true,
			
				ajax: {
					url: 'calonpelanggan/paginationpic/'+$("#idpic").val(),
					type: 'POST',
					data: function(e) {
						e.csrf_token = token
					},
					
				},
			})
        }
       
			
        $("#dpihakketiga").hide();
        $('#tipecustomer').focusout(function() {
           // alert(this.value)
			//var tipecustomer = this.value;
  			if (this.value == 'Pihak Ketiga'){
					$("#dpihakketiga").show();
                    $("#namapihakketiga").focus();
				}else{
					$("#dpihakketiga").hide();
				}	
		});
		$('#btnaddpic').click(function() {
           	$('#main-modal-pic #modal-form-pic').attr('action', 'calonpelanggan/addpic')
	 		$('#main-modal-pic').modal()
           
        });

        $('#btnclose').click(function() {
            window.location.reload()
           	
        });
    })		
    
    function goBack() {
      window.history.back();
    }
</script>