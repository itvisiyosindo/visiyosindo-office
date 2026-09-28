<header class="page-header">
    <h2><i class="fas fa-box-open"></i>&nbsp;<?= $page_title ?></h2>
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
		
		input{
			width:97%;
			height:auto;
			border:0px dotted #f30; 
			border-radius:4px; 
			-moz-border-radius:8px;			
			margin-right:0px;
		}
		
		.myinput{
			width:97%;
			height:auto;
			border:0px solid #000;
			border-radius:0px; 
			-moz-border-radius:8px;
			margin-left:0px;
			background:#b7d5ac;
		}
		
		.myselect{
			width:97%;
			height:auto;
			border:0px solid #000; 
			border-radius:4px; 
			-moz-border-radius:8px;
			margin:0px;
		}
		
		.mydiv br {
			display: none;
		}
		
		.mydiv p {
			padding: 0;
			margin: 0;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
        <div class="text-center mt-0">
			<h2><font color='#000000' face='Times New Roman'>No : <?= $data_aprv->kode?></font></h2>
        </div>
		<font color='#000000'>
			<?php

			$ttd_1 = $data_aprv->idttd_1;
			$ttd_2 = $data_aprv->idttd_2;

		    $ttd = "ttd_1";
				if((sessPenggunaId() == $ttd_1)){
					if($data_aprv->jenis == 1){
							$ttd = 'ttd_2';	 //GM
					}else{
							$ttd = 'ttd_1';  //GA
					}					
				}else if((sessPenggunaId() == $ttd_2)){
					if($data_aprv->jenis == 1){
							$ttd = 'ttd_4';	 //GM
					}else{
							$ttd = 'ttd_3';  //GA
					}	
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_aprv->id ?> 
		<input type="hidden" name="id" id="id" value="<?= $data_aprv->id ?>">


		
    <?= form_open('pinjam/updateSnPinjam', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
			
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan Peminjaman Unit :</font>
					</td>
				</tr>
				<tr>	
														
					</font>
		
					<td rowspan="17" width="2%"></td>
					
            <input type="hidden" id="idpengaju" value="<?= $data_aprv->id_peminjam ?>"> 
					<tr>
						<td width="10%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_aprv->nama_peminjam ?>  </td>
					</tr>
					<tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp;  <?=  $data_aprv->npp ?></td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?=  $data_aprv->jabatan_peminjam ?></td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td>Keperluan </td>
						<td>:&nbsp;&nbsp; <?=  $data_aprv->keperluan ?></td>
					</tr>
					<tr>								
						<td>Lampiran</td>
						<td>:&nbsp;&nbsp;
							<?php if (!empty($data_aprv->link)): ?>
								<a href="<?= $data_aprv->link ?>">Klik untuk cek</a>
							<?php else: ?>
								-
							<?php endif; ?>
						</td>
					</tr>					
					<tr>
						<td><font color="white">i </font></td>
					</tr>

					
				</tr>
			</table>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Detail Unit sebagai berikut :</font>
					</td>
				</tr>
				
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="3%"> No </th>
                                <th style="text-align:center" bgcolor="#d3d3d3"> Nama Barang </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="7%"> Jumlah </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="25%"> Keterangan</th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="20%"> Serial Number (SN) </th>
                            </tr>
                        </thead>
                        <tbody>
									<?php
													$x=1;
													$xy=0;
													foreach ($detail_aprv as $row) {
													$x = $x+1;
													$xy = $xy+1;
									?>
																	<tr>
																				<td style="text-align:center">
																						<input type="hidden" name="id_sodetail[]" value="<?= encrypt($row->id) ?>"><?= $xy ?>
																				</td>
																				<td class="des">&nbsp;<?= $row->nama ?>&nbsp;</td>
																				<td style="text-align:center" class="juml">&nbsp;<?= $row->jumlah ?>&nbsp;</td>																				
																				<td style="text-align:center" class="ket">&nbsp;<?= $row->keterangan ?>&nbsp;</td>
																				<?php if(sessPenggunaId() == 1 || sessPenggunaId() == 7 || sessPenggunaId()==73 || sessPenggunaId()==749){ ?>
                                    		<td><input type="text" id="<?= 'serialnumber_'.$x ?>" name="serialnumber[]" value="<?= $row->serial_number ?>" placeholder='Diisi oleh Team Warehouse'  Style="width:100%; text-align:center" required></td>
																				<?php }else{ ?>																				
																				<td style="text-align:center" class="sn">&nbsp;<?= $row->serial_number ?>&nbsp;</td>
																				<?php } ?>
																		</tr>

										<?php } ?>
										<?php for($kosong=$x;$kosong<=10;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
							<?php } ?>

                        </tbody>
                        
			</table>
			<br>
								<div style="text-align: right;">
                    <?php if(sessPenggunaId() == 1 || sessPenggunaId() == 7 || sessPenggunaId()==73 || sessPenggunaId()==749){ ?>
                        <button type="button" class="btn btn-success btn-save">Simpan</button>
                    <?php } ?>
                </div>
			<br>

			

			


      <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
							<?=  $data_aprv->kota_aju ?>
							<span> ,&nbsp; </span>
							<?= date('d-m-Y',strtotime($data_aprv->created_at)) ?>
							
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:30%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:5%;" ></td>
						<td style="text-align:center; width:30%;" >Diketahui Oleh,</td>
						<td style="text-align:center; width:5%;" ></td>
						<td style="text-align:center; width:30%;" >Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_aprv->id_peminjam.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							
							if($data_aprv->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_".$data_aprv->idttd_1.".png";
							}else if($data_aprv->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							
							if($data_aprv->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_".$data_aprv->idttd_2.".png";
							}else if($data_aprv->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:30%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:30%;"><?php echo'<img src="'.$ttd1.'" height="70">';?> </td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:30%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_aprv->nama_peminjam ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_aprv->nama1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_aprv->nama2?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_aprv->jabatan_peminjam ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_aprv->jabatan1?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_aprv->jabatan2 ?></i></td>
					</tr>
				</tbody>
				
			</table>
			</div>
		
			<br><br>
		<div width="100%">
			 <?php if (sessPenggunaId() == '1' || sessPenggunaId() == $ttd_1  || sessPenggunaId() == $ttd_2) { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_aprv->id)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_aprv->id)?>"> <i class="fas fa-times"></i> Tolak </button>				
       <?php } ?>
				<a href="pinjam/print_page/pinjam/<?=$data_aprv->id?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
        <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
			
			
			<br>
        </div>
		<br><br>

		
    <?= form_close(); ?>





		<br><br>  
						<?php if(sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId()==73 || sessPenggunaId()==749) { ?>
                <button type="button" class="btn btn-primary btn-edit" data-id="<?= encrypt($data_aprv->id) ?>"> <i class="bx bx-pencil"></i> Update History Peminjaman</button>
            <?php } ?>    
       
    
            <!-- Section: Timeline  
            <div class="container my-5">-->  
            <div class="row">
                <div class="col-md-12 offset-md-0">
                <h4 style="margin-left: 1.2rem;"><b>History Peminjaman</b></h4>
                <ul class="timeline-3">
                 <?php
                foreach($data_status as $each){

                $dari = date_create($each->created_at); 
                $sampai = date_create();
                $diff  = date_diff($dari, $sampai); //untuk menghitung hari
                // echo $diff->d . ' Hari, ';                         <span><i class="fa fa-clock-o mr-1"></i>21 March, 2019</span>

                if($each->status==1){
                    $status = "Disetujui Oleh General Affair";
                }else if($each->status==2){
                    $status = "Disetujui Oleh General Manager";
                }else if($each->status==3){
                    $status = "Disetujui Oleh Head Of Warehouse";
                }else if($each->status==4){
                    $status = "Barang Diterima Peminjam";
                }else if($each->status==5){
                    $status = "Barang Dikembalikan Kegudang";
                }else if($each->status==6){
                    $status = "Barang Dikirim";
                }else if($each->status==7){
                    $status = "Ditolak";
                }else if($each->status==0){
                    $status = "Pengajuan Peminjaman Unit";
                }
                  
                        
            ?>
                
                    <li>
                    <a><b><?php echo  $status?></b></a>
                    <a class="float-right"><?php echo date('d-M-Y | H:i:s',strtotime($each->created_at))?></a>
                    <p class="mt-2"><?php echo "Update oleh : " . $each->nama_pembuat; ?></p>

                    <?php if($each->keterangan_konfirmasi != "") { ?>
                       <a><b><?php echo  "Keterangan : " . $each->keterangan_konfirmasi; ?></b></a>
                    <?php } ?> 
                    
                    <?php if($each->nama_penerima != "") { ?>
                       <a><b><?php echo  "Nama Penerima : " . $each->nama_penerima; ?></b></a>
                    <?php } ?> 
                    
                <br>
                    <?php if($each->tgl_penerima != "") { ?>
                        <a class="float-left"><?php echo "Tanggal Penerimaan : " . date('d-M-Y',strtotime($each->tgl_penerima)); ?></a>
                        
                    <?php } ?>
                    <br><br>    
                    <?php if($each->bukti_penerima != "") { ?>
			            <a href="<?=$each->bukti_penerima?>" target="blank" class="btn btn-primary float-left" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Bukti Penerimaan </a>
			        <br><br>
                    <?php } ?> 
                    <!--<font color='#22c0e8'><?php echo  $status?></font>-->
                    <!--<br><br>-->
                    
                    </li>
              
                <?php } ?>
                  </ul>
                </div>
            </div>
            <!-- </div>
            Section: Timeline 
            <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">  -->
            <link rel="stylesheet" href="assets/css/timeline.css">





		</div>
		
    </div>	
</div>



<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Update History Peminjaman</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					

                    <div class="form-group">
                            <label for="status" class="form-control-label">Status <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="status" name="status" required onchange="toggleFormStatus()">
                                <option value="">- Pilih Status -</option> 
                                <option value="4">Barang Diterima Peminjam</option>
                                <option value="5">Barang Dikembalikan Kegudang</option>
                                <option value="6">Barang Dikirim</option>
                            </select>
                    </div>

                    <div class="form-group" id="form_nama_penerima" style="display:none;">
                            <label for="nama_penerima" class="form-control-label">Nama Penerima <span class="text-danger">*</span> :</label>
                            <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" required>
                    </div>
                    <div class="form-group" id="form_tgl_penerima" style="display:none;">
                            <label for="tgl_penerima" class="form-control-label">Tanggal Penerimaan <span class="text-danger">*</span> :</label>
                            <div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="text" class="form-control" id="tgl_penerima" name="tgl_penerima" required>
                        </div>
                    </div>
                    <div class="form-group" id="form_bukti_penerima" style="display:none;">
                            <label for="bukti_penerima" class="form-control-label">Bukti Penerimaan <span class="text-danger">*</span> :</label>
                            <textarea type="text" class="form-control" id="bukti_penerima" name="bukti_penerima" required></textarea>
                    </div>

                    <div class="form-group" id="form_keterangan_konfirmasi" style="display:none;">
                            <label for="keterangan_konfirmasi" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
                            <textarea type="text" class="form-control" id="keterangan_konfirmasi" name="keterangan_konfirmasi" required></textarea>
                    </div>

				

					
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_kirim" name="id_kirim">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	function toggleFormStatus() {
        var status = document.getElementById("status").value;
        var formDiantarkan = document.getElementById("form_nama_penerima");
        var formDikirim = document.getElementById("form_tgl_penerima");
        var formBukti = document.getElementById("form_bukti_penerima");
        var formKet = document.getElementById("form_keterangan_konfirmasi");

        if (status === "4" || status === "5") {
            formDiantarkan.style.display = "block";
            formDikirim.style.display = "block";
            formBukti.style.display = "block";
            formKet.style.display = "none";
        } else {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "none";
            formBukti.style.display = "none";
            formKet.style.display = "block";
        } 
				
				
    }


	document.addEventListener('DOMContentLoaded', function() {

		var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Setujui Permintaan Peminjaman Unit?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'pinjam/ttd_setujui/pinjam/'+level_ttd,
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
        
		
		$(document).on('click', '.btn-denial', function() {
			var id = $('#id').val();
			
            Swal.fire({
				title: 'Tolak Permintaan Peminjaman Unit?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'pinjam/ttd_tolak/pinjam/'+level_ttd,
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



				$(document).on('click', '.btn-edit', function() {
					$('.btn-isactive').remove()
					var object = 'pinjam'
					$('#main-modal #modal-form').attr('action', 'pinjam/updateStatusPinjam')
					$('#main-modal').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/edit/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal #id_kirim').val(id)
						})
				})

				






		})

    function goBack() {
        window.history.back();
    }

	
</script>
