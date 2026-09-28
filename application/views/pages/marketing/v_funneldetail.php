<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div id="main-modal-kompetitor" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Kompetitor</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-kompetitor', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kompetitor-form">
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idfunnelkomp" name="idfunnelkomp" required>
					</div>				
					<div class="form-group">
						<label for="namakompetitor" class="form-control-label">Nama Kompetitor <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namakompetitor" name="namakompetitor" required>
					</div>					
					<div class="form-group">
						<label for="produkkompetitor" class="form-control-label">Nama Product Kompetitor<span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="produkkompetitor" name="produkkompetitor" required>
					</div>
						<div class="form-group">
						<label for="hargakompetitor" class="form-control-label">Harga Kompetitor<span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="hargakompetitor" name="hargakompetitor" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" id="btn-tutupkomp" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-kompetitor" class="btn btn-success btn-save">Simpan</button>
			</div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						 <div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_2'>
								<thead>
									<tr>
										<th> # </th>
									<!-- 	<th> ID </th> -->
										<th> Nama Kompetitor</th>
										<th> Nama Produk </th>
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
<div class="col-xl-8 mb-8 mb-xl-0;" style="margin: auto;">
    <div class="card-body" style="background-color:#FFF;padding:5%">
        <div class="text-center">
            <h2>Detail Data Funnel  </h2>
        </div>
            <?= form_open('#', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
            <!-- value preview -->
            <div id="value_preview">
							<div class="form-group">
									<div hidden>
										<label for="idpelanggan" class="form-control-label">ID Funel<span class="text-danger">*</span> :</label>
										<input type="text" class="form-control col-sm-5" id="idfunnel" name="idfunnel" value="<?php echo encrypt($datafunnel->id);?>" readonly required/>
									</div>
							</div>	
                <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Nama Customer <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="namacaloncustomer" name="namacaloncustomer" value="<?= $datafunnel->namacaloncustomer ?>">
                </div>
                            <div class="form-group mb-2 pt-1">
                    <label class="col-form-label">Status Funnel <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="namastatus" name="namastatus" value="<?= $datafunnel->namastatus ?>">
                </div>
                <div class="form-group mb-2 pt-1" style="display: flex;">
                    <div style="flex: 50%; padding: 10px;">
                        <label class="col-form-label">Merk <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="nama_kategori" name="nama_kategori" value="<?= $datafunnel->nama_kategori ?>">
                    </div>
                    <div style="flex: 50%; padding: 10px;">
                        <label class="col-form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="nama_barang" name="nama_barang" value="<?= $datafunnel->nama_barang ?>">
                    </div>
                </div>
                <div class="form-group mb-2 pt-1" hidden>
                    <label class="col-form-label">Progress Kerja <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="namaprogress" name="namaprogress" value="<?= $datafunnel->namaprogress ?>">
                </div>
                <div class="form-group mb-2 pt-1 col-sm-12">
                    <label for="keterangan" class="col-form-label">Keterangan <span class="text-danger">*</span> :</label>
                    <textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"><?= $datafunnel->keterangan ?></textarea>
                </div>
                <div class="form-group mb-2 pt-1 col-sm-12">
                    <label for="kendala" class="col-form-label">Kendala <span class="text-danger">*</span> :</label>
                    <textarea name="kendala" class="form-control" id="kendala" cols="15" rows="3"><?= $datafunnel->kendala ?></textarea>
                </div>
                <div class="form-group mb-2 pt-1" style="display: flex;">
									<div style="flex: 80%; margin-right: 5px">
														<label for="kompetitor" class="form-control-label">Kompetitor <span class="text-danger">*</span> :</label> 
														 <?php
                                if(is_array($kompetitor) && count($kompetitor)>0){
                                    foreach ($kompetitor as $row) {
                                        //echo '<option id="' . $row->id . '" value="' . $row->id . '" selected>' . $row->namakompetitor . '</option>';
																				echo '<div class="form-control toast-xl">';
																				echo '<div class="toast-header">';
																				//echo  $row->id;
																				echo '<button type="button" class="btn-close" data-bs-dismiss="toast"></button>';
																				echo '</div>';
																				echo '<div class="toast-body">';
																				echo $row->namakompetitor;
																				echo '</div>';
																				echo '</div>';
                                    }

                                }
                                ?>
                                
									</div>
									<div style="flex: 20%; margin-top: 30px;">					
														<span class="input-group-append">
																 <button type="button" name="btnaddkompetitor" id="btnaddkompetitor" class="btn btn-success btn-xs">Tambah Kompetitor</button> 
														</span>
									</div>
                </div>
            </div>
        <div class="modal-footer">
            <button type="button"  onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" data-dismiss="modal" style="margin-left: 10px; margin-right: 10px;">Kembali</button>
            <?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO()) { ?>
				<button type="button" id="btn-laporan"  class="btn btn-success btn-clear-form float-right" data-dismiss="modal">Update Laporan</button>
			<?php } ?>
        </div>
        <?= form_close(); ?>    
    </div>
</div>
<div id="main-modal-laporan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Laporan Update Funnel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-laporan', 'autocomplete' => 'on')); ?>
			<div class="modal-body">
				<div class="dt-funnel-form">
				<div class="form-group">
						<div hidden>
							<label for="idpelanggan" class="form-control-label">ID Funel<span class="text-danger">*</span> :</label>
							<input type="text" class="form-control col-sm-5" id="idfunnel" name="idfunnel"  value="<?php echo encrypt($datafunnel->id);?>"  readonly required/>
							<input type="text" class="form-control col-sm-5" id="namapelanggan" name="namapelanggan"  value="<?= $datafunnel->namacaloncustomer ?>" readonly required/>
						</div>
				</div>	
				<div class="form-group">
					    <input class="form-control"  data-provide="datepicker" name="tanggal" id="tanggal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Realisasi" required>
					
						</div>
				<div class="form-group">
						<label class="control-label">Realisasi <span class="box text-danger"><sup>(*) </sup></span></label>
						<div class="col-sm-12">
							<div class="input-group mb-12">
							<select class="select-transaction input-group-sm form-control" name="realisasi" id="realisasi">
																					<?php if ($realisasifunnel != NULL): ?>
																							<option>— Pilih Realisasi —</option>
																							<?php foreach ($realisasifunnel as $value): ?>
																							<option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
																							<?php endforeach;?>
																							<?php else:?>
																							<option>— Tidak ada data —</option>
																					<?php endif;?>
																					</select>
																					<?php echo form_error('realisasi');?>
																					
																																		
							</div>
						</div>
					</div>	
        <div class="form-group">
						<label class="control-label">Funnel Status <span class="box text-danger"><sup>(*) </sup></span></label>
						<div class="col-sm-12">
							<div class="input-group mb-12">
							<select class="select-transaction input-group-sm form-control" name="statusfunnel" id="statusfunnel">
																					<?php if ($statusfunnel != NULL): ?>
																							<option>— Pilih Status Funnel —</option>
																							<?php foreach ($statusfunnel as $value): ?>
																							<option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
																							<?php endforeach;?>
																							<?php else:?>
																							<option>— Tidak ada data —</option>
																					<?php endif;?>
																					</select>
																					<?php echo form_error('statusfunnel');?>
																					<div hidden>
																						<span class="input-group-append">&nbsp; &nbsp; </span>
																						<span class="box">
																							<input type="checkbox" id="rencana" class="check" name="rencana" checked>&nbsp; <label class="control-label"><strong>Rencana</strong></label></input>
																						</span>			
																					</div>
																																		
							</div>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label">Progress Kerja</label>
						 <select class="select-transaction input-group-sm form-control" name="progressfunnel" id="progressfunnel">
                                        <?php if ($progressfunnel != NULL): ?>
                                            <option>— Pilih Progress Kerja —</option>
                                            <?php foreach ($progressfunnel as $value): ?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
                                            <?php endforeach;?>
                                            <?php else:?>
                                            <option>— Tidak ada data —</option>
                                        <?php endif;?>
                                        </select>
                                        <?php echo form_error('progressfunnel');?>
					</div>
          <div class="form-group">
						<label for="peluang" class="form-control-label">Peluang Keberhasilan <span class="text-danger">*</span> :</label>
						<select class="form-control" id="peluang" name="peluang"  required>
							<option value="">- Pilih Peluang Keberhasilan -</option>
							<option value="< 50%">< 50%</option>
							<option value="> 50%">> 50%</option>
							<option value="50%:50%">50%:50%</option>
							<option value="100%">100%</option>
						</select>
					</div>
          <div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="kendala" class="form-control-label">Kendala <span class="text-danger">*</span> :</label>
						<textarea name="kendala" class="form-control" id="kendala" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="attachment_visit" class="form-control-label">Link Dokumentasi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Goggle Drive Dokumentasi" id="attachment_funnel" name="attachment_funnel" required>
					</div>

					</div>
				</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-simpan-form-pelanggan" class="btn btn-success btn-save" data-dismiss="modal">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#peluangkeberhasilan').mask('##0%', {reverse: true});

				table2 = $('#kt_table_2').DataTable({
								responsive: false,
								processing: true,
								serverSide: true,
							
								ajax: {
									url: 'funnel/paginationkompetitor/12k72k',
									type: 'POST',
									data: function(e) {
										e.csrf_token = token
									},
									
								},
							})
	var i=1;  
    $('#add').click(function(){ 		
				if($('#namakomp').val()!=''){
	        i++;             
  	      $('#dynamic_field').append('<div id="row'+i+'">'+
																				'<div class="form-group">'+
																						'<div id="dynamic_field">'+
																								'<label class="control-label col-sm-9" for="namakompetitor">Nama Kompetitor:</label>'+
																										'<div class="col-sm-9">'+
																												'<input hidden type="text" class="form-control" id="kompkomp" name="kompkomp">'+
																												'<input type="text" class="form-control" id="namakompetitor" placeholder="Masukkkan Nama Kompetitor" name="namakompetitor[]" autocomplete="off">'+
																										'</div>'+
																										'<label class="control-label col-sm-9" for="produkkompetitor">Nama Product Kompetitor:</label>'+
																										'<div class="col-sm-9">'+
																												'<input type="text" class="form-control" id="produkkompetitor" placeholder="Masukkkan Nama Product Kompetitor" name="produkkompetitor[]" autocomplete="off">'+
																										'</div>'+
																										'<label class="control-label col-sm-6" for="hargakomp">Harga Kompetitor:</label>'+
																										'<div class="col-sm-10">'+
																												'<div class="input-group mb-12">'+
																														'<input type="number" class="form-control" id="hargakompetitor" placeholder="Masukkan Harga Kompetitor" name="hargakompetitor[]" autocomplete="off">'+
																														'<span class="input-group-append">'+
																															'<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>'+
																														'</span>'+
																												'</div>'+
																										'</div>'+
																							'</div>'+
																				'</div>'+
																			'</div>');
					$('#count').val(i);
				};
    });
    
    $(document).on('click', '.btn_remove', function(){  
		i--;   
		$('#count').val(i);
           var button_id = $(this).attr("id"); 
           var res = confirm('Apakah PIC ini akan dihapus?');
           if(res==true){
           $('#row'+button_id+'').remove();  
           $('#'+button_id+'').remove();  
           }
    });  

        $('#btn-laporan').click(function() {			
                $('#main-modal-laporan #modal-form-laporan').attr('action', 'funnel/updatefunnel')
                $('#main-modal-laporan').modal()
        })

				$('#btnaddkompetitor').click(function() {
						var idfunnel = 	$('#idfunnel').val()	
						table2.ajax.url('funnel/paginationkompetitor/'+idfunnel).load();
						$('#kt_table_2').DataTable().ajax.reload();
					  $('#idfunnelkomp').val(idfunnel)	
						$('#main-modal-kompetitor #modal-form-kompetitor').attr('action', 'funnel/addfunnelkompetitor')
            $('#main-modal-kompetitor').modal()
    		})
				
				$('#btn-tutupkomp').click(function(){
						location.reload(true);
				})
    })

				

    function goBack() {
      window.history.back();
    }
</script>