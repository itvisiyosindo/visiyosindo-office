<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
	
	<style>
		hr{
		   display: block;
		   margin-top: 0em;
		   margin-bottom: 0em;
		   margin-left: auto;
		   margin-right: auto;
		   border-top: 1px solid black;
		}
		
		.mydiv {
			display:inline-block;
		}
		
		.mylabel {
			border:0px solid blue;
			display: table-cell;
			width: 100%;
		}
	</style>
</header>
<div id="main-modal-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Update Modal Approval Harga </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
		<?= form_open('#', array('id' => 'modal-form-modal', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-modal-form">
                     <div class="form-group" hidden>
                        <input type="text" class="form-control" id="idapprov" name="idapprov" value="<?= encrypt($data_approval[0]->id_approval) ?>"  autocomplete="off" readonly>
						<input type="text" class="form-control" id="iddetail" name="iddetail" autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-9" for="namabarang">Nama Barang:</label>
                        <input type="email" class="form-control" id="namabarang" name="namabarang" autocomplete="off" disabled>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="modalbarang">Jenis Approval:</label>
                    	<select class="form-control" id="modalbarang" name="modalbarang" required>
												<option value="0">- Jenis Approval Harga -</option>
												<option value="1">Margin > 45% Harga Modal </option>
												<option value="2">Margin < 45% Harga Modal </option>
												<option value="3">Margin < 20% Harga Modal </option>
											</select>
                    </div>
                </div>
            </div>
	            <div class="modal-footer">
					<button type="button" id="btnclose" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
                    <button type="button" id="btn-edit-modal" class="btn btn-success btn-edit">Edit</button>
			    </div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						 <div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_3'>
								<thead>
									<tr>
										<th> # </th>
										<th> Nama</th>
										<th> Nama Barang </th>
										<th> Acuan Harga Terendah </th>
										<th> Harga Yang Ditawarkan </th>
										<th> Harga Modal </th>
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
<div id="main-modal-approval" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Log Detail Approval</h5>
			</div>
			<?= form_open('#', array('id' => 'modal-form-approval', 'autocomplete' => 'off')); ?>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						 <div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_1'>
								<thead>
									<tr>
										<th> # </th>
										<th> ID</th>
										<th> Nama </th>
										<th> Acuan </th>
										<th> Harga </th>
										<th> Alasan </th>
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
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
        <?php
			if ((sessPenggunaId() == '107' || sessPenggunaId() == '1')){
				echo "<div class='text-right'><button type='button' class='btn btn-success btn-edit btn-xl btn-modalapproval'>Modal</button></div>";
			}
		?>
        <div class="text-center">
             <h2><font color='#000000' face='Times New Roman'><?= $data_approval[0]->kode ?></font></h2>
        </div>
		<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '75')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '107')){
					$ttd = 'ttd_2';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
			<br>
			<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Tanggal </td>
						<td width="18%"></td>
						<td>:&nbsp;&nbsp;<?= date('d-m-Y', strtotime($data_approval[0]->tgl)); ?>  </td>
					</tr>
					<tr>
						<td>Nama Marketing </td>
						<td width="18%"></td>
						<td>:&nbsp;&nbsp;  <?= $data_approval[0]->nama ?></td>
					</tr>
					<tr>
						<td>Nama Customer </td>
						<td width="18%"></td>
						<td>:&nbsp;&nbsp;  <?= $data_approval[0]->nama_customer ?></td>
					</tr>
					<tr>
						<td colspan="2">Detail Order Confirmation </td>
						<td>:&nbsp;&nbsp; <?=  $data_approval[0]->detail_order ?></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td colspan="3"><font color='#000000'>Pada Order Confirmation diatas terdapat harga product yang akan ditawarkan dibawah harga Pricelist 
						setelah diberikan diskon maksimal / Acuan Harga Terendah dengan detail :</font></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                            	<th style="text-align:center" width="25%">Nama Barang/Package </th>
                                <th style="text-align:center" width="11%"> Acuan Harga Terendah </th>
                                <th style="text-align:center" width="11%"> Harga yang akan ditawarkan </th>
                                <?php
									if (isAdmin() || (sessPenggunaId() == '107') || (sessPenggunaId() == '23') || (sessPenggunaId() == '54' )){
										echo "<th style='text-align:center' width='3%'> Modal Harga </th>";
									}
								?>
                                <th style="text-align:center" width="15%" colspan="3"> Approval </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
								$x=1;
								$nom = 0;
								$xtampil = 0;
								foreach ($detail_approval as $row) {
								$x = $x+1;
							?>
								<tr>
                                <td class="nama_barang" >
									&nbsp;<?= $row->nama_barang ?>;
									<?php 
										foreach ($detail_approvalrevisicount as $rowdetail) {
											if($rowdetail->iddetail==$row->id){
												echo '<button type="button" class="btn btn-link btn-revisi btn-sm" data-id="'. encrypt($rowdetail->iddetail) .'"><sup><span class="badge badge-danger" style="vertical-align: top">'.'Revisi &nbsp;('. $rowdetail->total .')'.'</span></sup></button>';
										    }
										}
									?>
								</td>
                                    <!-- <td class="acuan_hrg" style="text-align:right">&nbsp;<?= $row->acuan_hrg ?>&nbsp;</td> -->
                                    <td>
                                    	<label class="acuan_hrg" style="text-align:left">&nbsp;Rp. </label>
                                    	<label class="acuan_hrg" style="text-align:right"><?= number_format((float)$row->acuan_hrg, 0, ",", ".") ?>,-&nbsp;</label>
                                    </td>
                                    <td>
                                    	<label class="hrg_ditawarkan" style="text-align:left">&nbsp;Rp. </label>
                                    	<label class="hrg_ditawarkan" style="text-align:right"><?= number_format((float)$row->hrg_ditawarkan, 0, ",", ".") ?>,-&nbsp;</label>
                                    </td>
                                    <!-- <td class="hrg_ditawarkan"  style="text-align:right">&nbsp;<?= $row->hrg_ditawarkan ?>&nbsp;</td> -->
                                    <!-- <td class="approvall" style="text-align:center">&nbsp;&nbsp;
                                    </td> -->
                                    <?php
										$xtampil+=1;
										if (isAdmin() || (sessPenggunaId() == '107') || (sessPenggunaId() == '23') || (sessPenggunaId() == '54')){
											echo "<td class='hrg_modal' style='text-align:center;width:16%;'><label class='hrg_modal' style='text-align:center'></label><label class='hrg_modal' style='text-align:right'>".$row->modal."</label></td>";
										}
									?>

																		<?php 
																				if ($row->approvall==1){
																					$dataApproval = "Disetujui";
																				}else if($row->approvall==2){
																					$dataApproval = "Ditolak";
																				}

																		?>
                                    <td class="tgl" style="text-align:center;">
																				<?php if (isAdmin() || (sessPenggunaId() == '107') || (sessPenggunaId() == '75') ){ ?>
																					<label class="dataApprovall"><?= $dataApproval ?></label>
																					<button type="button" class="btn btn-sm btn-primary btn-editItem" data-id="<?= encrypt($row->id) ?>"><i class="bx bx-pencil"></i></button>	
																				<?php } else { ?>
																					<label class="dataApprovall"><?= $dataApproval ?></label>
																				<?php } ?>
           												 	</td>
                                </tr>
                            <?php } ?>
							<?php for($kosong=$x;$kosong<=8;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                   
                                </tr>
							<?php } ?>							
                        </tbody>
                    </table>
                    <br>
                    <table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                    	<thead>
                    		<tr>
                    			<th width="25%">*Tarif Komisi Marketing : </th>
                    			<th width="25%"> *Catatan : </th>
                    		</tr>
                    	</thead>
                    	<tbody>
                    		<?php 
								$x2=1;
								foreach ($detail_approval as $row) {
								$x2 = $x2+1;
							?>

							<?php if ($row->trf_komisi == 0) { ?>
							
							<?php } else { ?>
									<tr>
                                    <td class="trf_komisi" style="text-align:center;">
    
                                    	 &nbsp;<?= $row->trf_komisi ?>&nbsp;
                                    </td>
                                    <td class="catatan" >
                                    	&nbsp;<?= $row->catatan ?>&nbsp;
                                    </td>
								</tr>

							<?php } ?>
								
								<?php } ?>	

                    		<?php $itung2 = 1;
								for($x2=1;$x2<=1;$x2++){
									$itung2 = $x2; ?>
								<tr>
									<input class="no-outline" type="hidden" id="id_approval" name="id_approval" value="<?= $data_approval[0]->id_approval ?>">
                                    <td> 
                                    <?php
                                    if (sessPenggunaId() == '1' || sessPenggunaId() == '107') {?>
                                    	<textarea type="text" id="trf_komisi" name="trf_komisi" Style="width:100%" value="" placeholder="Diisi oleh Accounting"><?= $data_approval[0]->trf_komisi ?></textarea>
                                    	<?php } else {?>
                                    	<textarea readonly><?= $row->trf_komisi ?></textarea>
                                    	<?php } ?>
                                    </td>
                                    
                                    <td>
                                        <?php
                                    if (sessPenggunaId() == '1' || sessPenggunaId() == '107') {?>
                                    	<textarea type="text" id="catatan" name="catatan" Style="width:100%" value="" placeholder="Diisi oleh Accounting"><?= $data_approval[0]->catatan ?></textarea>
                                    	<?php } else {?>
                                    	<textarea readonly><?= $row->catatan ?></textarea>
                                    	<?php } ?>
                                    </td>
                                </tr>
            
							<?php } ?>
							<input type="hidden" id="itung2" value="<?= $itung2 ?>"/>								
                    	</tbody>
                    </table>

                    <table>
                    	<tr>
                    		<td><font color="white">i </font></td>
                    	</tr>
                    	<tr>
                    		<td><font color="#000000" style="font-family:Times New Roman"><i>(*Tarif Komisi Marketing dan Catatan diisi oleh Senior Accounting & Finance)</i></font></td>
                    	</tr>
                    	<tr>
                    		<td><font color="white">i </font></td>
                    	</tr>
                    </table>

                    <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
											<tbody>
												<tr style="height: 18px;">
													<td style="text-align:center; ">Dibuat Oleh,</td>
													<td style="text-align:center; ">Diverifikasi Oleh,</td>
													<td style="text-align:center; ">Disetujui Oleh,</td>
												</tr>
												<tr style="height:60px;">
													<?php
														
														$img_path 	= "uploads/file_karyawan/ttd/";
														$ttdaju		= $img_path."ttd_".$data_approval[0]->idPengaju.".png";
														$ttd1 		= $img_path."ttd_notyet2.png";
														$ttd2 		= $img_path."ttd_notyet2.png";
														
														if($data_approval[0]->ttd_1 == '1'){
															$ttd1 = $img_path."ttd_75.png";
														}else if($data_approval[0]->ttd_1 == '2'){
															$ttd1 = $img_path."ttd_not.png";
														}
														if($data_approval[0]->ttd_2 == '1'){
															$ttd2 = $img_path."ttd_107.png";
														}else if($data_approval[0]->ttd_2 == '2'){
															$ttd2 = $img_path."ttd_not.png";
														}
													?>
													<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
													<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
													<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
												</tr>
												<tr>
													<td style="text-align:center; "><strong><?= $data_approval[0]->nama_ttd ?></strong><hr width="40%"></td>
													<td style="text-align:center; "><strong>Mega Ratu</strong><hr width="40%"></td>
													<td style="text-align:center; "><strong>Dirangga Madali</strong><hr width="40%"></td>
												</tr>
												<tr>
													<td style="text-align:center; vertical-align:top;"><i><?= $data_approval[0]->jabatan_visilab ?></i></td>
													<td style="text-align:center; vertical-align:top;"><i>Head of Visilab</i></td>
													<td style="text-align:center; vertical-align:top;"><i>Head of Accounting and Tax</i></td>
												</tr>
											</tbody>
											
										</table>
			
			
			<br><br>
		<div width="100%">
            	<?php if(sessPenggunaId() == '107' || sessPenggunaId() == '1') { ?>
            	<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-approval="<?=encrypt($data_approval[0]->id_approval)?>"> <i class="fas fa-check"></i> Submit </button>
            	<?php } ?>
            	<?php if(sessPenggunaId() == '107' || sessPenggunaId() == '75') { ?>
									<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-approval="<?=encrypt($data_approval[0]->id_approval)?>"> <i class="fas fa-check"></i> Setujui </button>
							
									<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-approval="<?=encrypt($data_approval[0]->id_approval)?>"> <i class="fas fa-times"></i> Tolak </button>
							<?php } ?>
							<?php if($data_approval[0]->detail_order != "") { ?>
								<a href="<?=$data_approval[0]->detail_order?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
							<?php } ?>

						<a href="visilab/print_page/approval_harga/<?=$data_approval[0]->id_approval?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
            <br>
        </div>
        </div>
		<br>
    </div>
</div>



<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Detail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					

					<div class="form-group">
						<label for="nama_barang" class="form-control-label">Nama Barang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
					</div>
					<div class="form-group">
						<label for="acuan_hrg" class="form-control-label">Acuan Harga Terendah <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="acuan_hrg" name="acuan_hrg" required>
					</div>
					<div class="form-group">
						<label for="hrg_ditawarkan" class="form-control-label">Harga Yang Ditawarkan <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="hrg_ditawarkan" name="hrg_ditawarkan" required>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id" name="id">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
        <button type="button" id="save-form" class="btn btn-success save-form"><i class="fas fa-check"></i> Setujui</button>
        <button type="button" id="save-form-tolak" class="btn btn btn-secondary save-form-tolak"><i class="fas fa-times"></i> Tolak</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<script>
	
	//function onlyOne(checkbox) {
  //  var checkboxes = document.getElementsByName('approvall')
  //  checkboxes.forEach((item) => {
  //      if (item !== checkbox) item.checked = false
  //  })
	//}

    document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();		
        
        
		 $(document).on('click', '.btn-edit', function() {
					$('#main-modal-modal #iddetail').val('');
					$('#main-modal-modal #namabarang').val('');
					$('#main-modal-modal #modalbarang').val('');
                   
					var par = $(this).data("id"); 
					var url ="visilab/editmodalapproval/"+par
					
					$.ajax({
							type: "GET",
							url: url,
							success: function(response) {     
							 if (response) {
								 	  result = JSON.parse(response);
								 	  $('#main-modal-modal #iddetail').val(result['iddetail']);
								 	  $('#main-modal-modal #namabarang').val(result['namabarang']);
										$('#main-modal-modal #modalbarang').val(result['modalbarang']);
							}
    					},
							error: function (request, status, error) {
									alert(request.responseText);
							}					
				});
				
				
		})

		$('#btn-edit-modal').click(function() {
		    console.log($('#main-modal-modal #modalbarang').val())
			if(($('#main-modal-modal #modalbarang').val()>0) && ($('#main-modal-modal #modalbarang').val()!='')){
			    $.ajax({
				type: "POST",
				url: "visilab/updatemodalapproval",
				cache: false,
				data: {
						iddetail: $('#main-modal-modal  #iddetail').val(),
						namabarang: $('#main-modal-modal #namabarang').val(),
						modalbarang: $('#main-modal-modal #modalbarang').val(),      
						
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
			//	window.location.reload();
			}else{
		    	Swal.fire({
                    type: 'error',
                    icon: 'error',
                    title: 'Nama Barang atau Jenis Approval tidak boleh kosong..!!',
                    showConfirmButton: false,
                    timer: 2000
                });
			}
    	  })
        
        table = $('#kt_table_1').DataTable({
				responsive: false,
				processing: true,
				serverSide: true,
			
				ajax: {
					url: 'surat/paginationapprovallog/12k72k',
					type: 'POST',
					data: function(e) {
						e.csrf_token = token
					},
					columns: [
						{ "width": "1%" },
						{ "width": "1%" },
						{ "width": "20%" },
						{ "width": "10%" },
						{ "width": "10%" },
						{ "width": "40%" },
					],
					columnDefs: [
    			    {
        				'visible': false, 
        				'targets': [1],
    			    }
    			],
					
				},
			})

         table3 = $('#kt_table_3').DataTable({
				responsive: false,
				processing: true,
				serverSide: true,
			
				ajax: {
					url: 'surat/paginationapprovalmodal/12k72k',
					type: 'POST',
					data: function(e) {
						e.csrf_token = token
					},
					
				},
			})
			
			$(document).on('click', '.btn-revisi', function() {			
					var par = $(this).data("id"); 
					//console.log(par);
					$('#main-modal-approval').modal('toggle');
					table.ajax.url('surat/paginationapprovallog/' + par).load();
					table.column(1).visible(false);
					
			})
        
		
        $(document).on('click','.btn-modalapproval', function() {
                $('#main-modal-modal').modal();
						idapprov = $('#idapprov').val();
						console.log(idapprov);
						table3.ajax.url('visilab/paginationapprovalmodal/' + idapprov).load();
						//table.column(1).visible(false);
				})

        $(document).on('click', '.btn-submit', function() {
        	var trf_komisi = $('#trf_komisi').val();
        	var catatan = $('#catatan').val();
        	//var approvall = jQuery("input[name=approvall]:checked").val();
        	var id_approval = $('#id_approval').val();

        	// var level_ttd = $('#level_ttd').val();
        	// console.log(approvall);
        	Swal.fire({
        		title: 'Data telah tepat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
        			// console.log('' + trf_komisi);
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/updateFromAccountingApproval/'+id_approval,
                        dataType: 'JSON',
                        data: {
							trf_komisi : trf_komisi,
							catatan : catatan,
							//approvall : approvall,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })


		
		$(document).on('click', '.btn-approval', function() {
            const id_approval = $(this).attr("id-approval");
			console.log(level_ttd);
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Pengajuan Approval Harga?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_approval/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_approval : id_approval,
							csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
					
                }
				
            })
        })


		
		$(document).on('click', '.btn-denial', function() {
            const id_approval = $(this).attr("id-approval")
            Swal.fire({
				title: 'Tolak Pengajuan Approval Harga?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_approval/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_approval : id_approval,
							csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
                }
            })
        })




				

			$(document).on('click', '.btn-editItem', function() {
					$('.btn-isactive').remove()
					var object = 'visilab'
					$('#main-modal #modal-form').attr('action', 'visilab/updateItemApproval')
					$('#main-modal').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/editItem/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal #nama_barang').val(data[0].nama_barang)
							$('#main-modal #acuan_hrg').val(data[0].acuan_hrg)
							$('#main-modal #hrg_ditawarkan').val(data[0].hrg_ditawarkan)
							$('#main-modal #approvall').val(data[0].approvall)
							$('#main-modal #id').val(id)
						})
				})


				$('.save-form').click(function() {

            $.ajax({
                method: 'POST',
                url: 'visilab/updateItemApproval',
                dataType: 'JSON',
                data: {
                    id: $('#id').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })

				$('.save-form-tolak').click(function() {

            $.ajax({
                method: 'POST',
                url: 'visilab/updateItemApprovalTolak',
                dataType: 'JSON',
                data: {
                    id: $('#id').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    handleResponse(resp)
                }
            })
        })


		
    })

    function goBack() {
        window.history.back();
    }
</script>