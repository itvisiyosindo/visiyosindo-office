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
			<h2><font color='#000000' face='Times New Roman'>SURAT SKORSING</font></h2>
        </div>
		<font color='#000000'>
			<?php

		    $ttd = "ttd_1";
				if((sessPenggunaId() == 69 || sessPenggunaId() == 744)){
					$ttd = 'ttd_1';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_istirahat[0]->idGc ?> 
		<input type="hidden" name="id" id="id" value="<?= $data_istirahat[0]->idGc ?>">


		
    <?= form_open('surat_new/updateApprovalEks', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

		<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			
				<tr>
					<td colspan="3" style="text-align:left">
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					
				</tr>

					

				<tr>
					
					<tr>
						<td width="10%">No </td>
						<td>:&nbsp;&nbsp;<?= $data_istirahat[0]->kode?></td>
					</tr>
					<tr>
						<td width="10%">Perihal </td>
						<td>:&nbsp;&nbsp;Pemberian Skorsing</td>
					</tr>
					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Yang bertanda tangan dibawah ini :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>
						<td width="10%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->pengaju?>  </td>
					</tr>
					<tr>
						<td width="10%">NPP </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->npp?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_istirahat[0]->jabatan?></td>
					</tr>
				</tr>
				</tr>
				<tr>
						<td><font color="white">i </font></td>
					</tr>
			</table>



			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				

				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan sangat terpaksa memberikan skorsing kepada :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>
						<td width="10%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->penerima?>  </td>
					</tr>
					<tr>
						<td width="10%">NPP </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->npp2?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_istirahat[0]->jabatan2?></td>
					</tr>
				</tr>
				</tr>
				<tr>
						<td><font color="white">i </font></td>
					</tr>
			</table>

		<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="black">
				<tr>
          <td colspan="4">Untuk tidak masuk kerja selama&nbsp;&nbsp;<?= $data_istirahat[0]->masa?>&nbsp;&nbsp;terhitung sejak tanggal&nbsp;&nbsp;<?=  date('d-m-Y',strtotime($data_istirahat[0]->tanggal)) ?>&nbsp;&nbsp;dan dilakukan pemotongan gaji sesuai dengan perhitungan hari kerja yang tidak diikutinya.</td>
        </tr>
				
				<tr>
						<td><font color="white">i </font></td>
					</tr>
				<tr>
          <td colspan="4">Demikian Surat Skorsing ini kami buat dan kami berikan kepada yang bersangkutan agar dipatuhi dan dipergunakan sebagaimana mestinya.</td>
        </tr>
				
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
			</table>
								


			<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" ></td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" ></td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" >Mengetahui,</td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttd1 		= $img_path."ttd_notyet2.png";
							
							if($data_istirahat[0]->ttd == '1'){
								$ttd1 = $img_path."ttd_".$data_istirahat[0]->id_hr."_cap.png";
							}else if($data_istirahat[0]->ttd == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:25%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_istirahat[0]->pengaju ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_istirahat[0]->jabatan ?></i></td>
					</tr>
				</tbody>
				
			</table>


      
			</div>
		
			<br><br>
		<div width="100%">
			
        <?php 
				
				if (sessPenggunaId() == '1'|| sessPenggunaId() == 69 || sessPenggunaId() == 744 ) { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_istirahat[0]->idGc)?>"> <i class="fas fa-check"></i> Terima </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_istirahat[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>				
       <?php } ?>
				<a href="surat_new/print_page/spi/<?=$data_istirahat[0]->idGc?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
        <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
			
			<br>
        </div>
		<br><br>
		</div>

		
    <?= form_close(); ?>
		
    </div>	
</div>



<script>

	


    document.addEventListener('DOMContentLoaded', function() {

		var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Setujui Permintaan Surat Skorsing?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/ttd_setujui/spi/'+level_ttd,
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
				title: 'Tolak Permintaan Surat Skorsing?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/ttd_tolak/spi/'+level_ttd,
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

    function goBack() {
        window.history.back();
    }

	
</script>
