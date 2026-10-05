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
			width:100%;
			height:auto;
			border:0px solid #000;
			border-radius:0px; 
			-moz-border-radius:8px;
			margin-left:0px;
			text-align:center;
			height: 100px;
            line-height: 100px;
            font-weight:bold;
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
		
		*::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
          color: red;
          opacity: 1; /* Firefox */
        }
    
	</style>

</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
       <!--  <div class="text-right">
            <?php
                if($data_sp[0]->ttd_3 == 0){
                    echo '<span class="btn btn-warning">Baru Diajukan</span>';
                }else if($data_sp[0]->ttd_3 == 1){
                    echo '<span class="btn btn-success">Disetujui GA</span>';
                }else if($data_sp[0]->ttd_3 == 2){
                    echo '<span class="btn btn-danger">Ditolak GA</span>';
                }
            ?>
        </div> -->
        <div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>SURAT PERINGATAN</font></h2>
        </div>
			<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '70')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '23')){
					$ttd = 'ttd_2';
				}else if((sessPenggunaId() == '69' || sessPenggunaId() == '744')){
					$ttd = 'ttd_3';
				}
		?>
		<?php 
		
		$id = $data_sp[0]->id?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<input type="hidden" id="id" value="<?= $id ?>">
		<div class="table-responsive">
		<font color='#000000'>
<!-- TABEL SURAT KUNJUNGAN GUDANG -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                            <tr>
                        		<td colspan="4" style="text-align:center; font-size:20px; font-weight:bold;">
                        	    <?= $data_sp[0]->kode ?>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4" style="text-align:center; font-size:12px; font-weight:bold;"></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i</font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="2">
                        			Perihal : Surat Peringatan
                        		</td>
                        		<td><?= $data_sp[0]->perihal ?></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Surat peringatan ini ditujukan <br> Kepada :
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="7%" style="text-align:right;">1. &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td>:&nbsp;<?= $data_sp[0]->namasp ?>
														<input type="text" hidden value="<?= $data_sp[0]->nama ?>" name="nama" id="nama" ></td>
                        		<td width="30%"></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Jabatan</td>
                        		<td>:&nbsp;<?= $data_sp[0]->jabatansp ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Surat ini dikeluarkan sehubungan dengan evaluasi kinerja pertanggal <?= $data_sp[0]->tgl_evaluasi ?>
                        		Saudara Melakukan Kesalahan Pada <?= $data_sp[0]->tgl_salah ?>, Yaitu :</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4" height="100" align="center" style="font-size: 15px;"><strong><?= strtoupper($data_sp[0]->kesalahan) ?></strong></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Bahwasanya hal tersebut saudara lakukan dengan cara <?= strtoupper($data_sp[0]->cara) ?><br>
                        		Surat Peringatan &nbsp;<?= $data_sp[0]->jenis_sp ?>ini mengakibatkan saudara di kenakan sanksi berupa :

                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4" height="100" align="center" style="font-size: 15px;"><strong><?= strtoupper($data_sp[0]->sanksi) ?></strong></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Dengan diterimanya surat peringatan ini, jika dalam kurun waktu <?= $data_sp[0]->masa ?> Bulan / Masa SP tidak memperbaiki kesalahannya dan/atau melakukan kesalahan yang sama, maka akan diberikan surat peringatan berikutnya.
<br>&nbsp;&nbsp;&nbsp;
</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat peringatan ini kami sampaikan agar dapat diperhatikan dan menjadi evaluasi diri saudara.
Terimakasih.</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; ">Pekanbaru, <?= $data_sp[0]->tgl_pengajuan ?></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td></td>
                        		<td></td>
                        		<td style="text-align:center; ">PT. Visi Yosindo Medikal</td>
                        	</tr>
                        	<tr style="height:60px;">
                        		<?php
                        		$img_path 	= "uploads/file_karyawan/ttd/";
							// $ttdaju		= $img_path."ttd_".$data_pkk[0]->idPengaju.".png";
							// $ttd1 		= $img_path."ttd_notyet2.png";
							// $ttd2 		= $img_path."ttd_notyet2.png";
                        		$ttd3 		= $img_path."ttd_notyet2.png";

														//Logika TTD
														if($data_sp[0]->id > 65){
															$idTTD = $masternotifikasi[0]->disetujui1;
															$namaTTD = $masternotifikasi[0]->namad1;
															$jabatanTTD = $masternotifikasi[0]->jabatand1;
														}else{
															$idTTD = $masternotifikasi[0]->verifikasi1;
															$namaTTD = $masternotifikasi[0]->namav1;
															$jabatanTTD = $masternotifikasi[0]->jabatanv1;

														}



                       	        if($data_sp[0]->ttd_3 == '1'){
                        			$ttd3 = $img_path."ttd_".$idTTD."_cap.png";
                        		}else if($data_sp[0]->ttd_3 == '2'){
                        			$ttd3 = $img_path."ttd_not.png";
                        		}
                        		?>
                        		<td style="text-align:center; width:5%;"> </td>
                        		<td style="text-align:center; width:2.5%;"></td>
                        		<td style="text-align:center; width:23%;"></td>
                        		<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd3.'" height="90">';?></td>

                        	</tr>
                        	<tr>
                        		<td width="5%" style="text-align:center; "></td>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; "></td>
														<td style="text-align:center; "><b><?= $namaTTD ?></b></td>
                        	</tr>
                        	<tr>
                        		<td width="5%" style="text-align:center; "></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        		<td style="text-align:center; "></td>
														<td style="text-align:center; vertical-align:top;"><i><?= $jabatanTTD ?></i></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"><i>Tembusan :</i></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        	</tr>
                        	<tr>
        
                        		<tr>
                        		<td style="text-align:right; "><i>1. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Direksi</i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>2. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;General Manager</i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>3. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;<?= ($data_sp[0]->tembusan) ?></i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>4. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Finance Staff</i></td>
                        	</tr>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        	</tr>
                        </tbody>
			</table>
			<br>
<!-- PENUTUP SURAT KUNJUNGAN GUDANG -->
			
<br><br>
<div width="100%">
	<?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_sp[0]->id || sessPenggunaId() == '69' || sessPenggunaId() == '744')  { ?>
		<?php if(sessPenggunaId() == '81') { ?>
			<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-sp="<?=encrypt($data_sp[0]->id)?>"> <i class="fas fa-check"></i> Submit </button>
		<?php } ?>
		<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-sp="<?=encrypt($data_sp[0]->id)?>"> <i class="fas fa-check"></i> Setujui </button>
			<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-sp="<?=encrypt($data_sp[0]->id)?>"> <i class="fas fa-times"></i> Tolak </button>				
	<?php } ?>
	<a href="surat/print_page/sp/<?=$data_sp[0]->id?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
	<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
	<br>
</div>
        </div>
		<br>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
            const id_sp = $(this).attr("id-sp");
			var nama	= $('#nama').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Surat Peringatan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/sp/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id : id_sp,
							nama : nama,
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
            const id_sp = $(this).attr("id-sp");
            Swal.fire({
				title: 'Tolak Surat Peringatan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/sp/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id: id_sp,
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