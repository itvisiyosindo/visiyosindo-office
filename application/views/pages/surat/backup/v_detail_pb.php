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
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
        <div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>No : <?= $data_pb[0]->kodePB ?></font></h2>
            <input type="hidden" id="kodesurat" value="<?= $data_pb[0]->kodePB ?>"> 
        </div>
		<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '107')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '33')){
					$ttd = 'ttd_2';
				}else if((sessPenggunaId() == '23')){
					$ttd = 'ttd_3';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
			<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr >
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan biaya perjalanan dinas :</font>
					</td>
				</tr>
				<tr>
					<font color='#ffffff'>
						<?= 
							$dt_pengajuan	= strtotime($data_pb[0]->tglPengajuan);
							$dt_pergi		= strtotime($data_pb[0]->tglPergi);
							$dt_pulang		= strtotime($data_pb[0]->tglKembali);
							
							$tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);
							$hari_pergi 	= date("D", $dt_pergi);
							$tgl_pergi 		= date("d", $dt_pergi)." - ".date("m", $dt_pergi)." - ".date("Y", $dt_pergi);
							$pergi 	        = date_create(date("d", $dt_pergi)."-".date("m", $dt_pergi)."-".date("Y", $dt_pergi));
							$pulang 	    = date_create(date("d", $dt_pulang)."-".date("m", $dt_pulang)."-".date("Y", $dt_pulang));
							$lama_hari 		= date_diff($pergi, $pulang);
							$lama_hari		= $lama_hari->format("%d") + 1;
								
							switch($hari_pergi){
								case 'Sun':
									$hari_pergi = "Minggu";
								break;
								case 'Mon':         
									$hari_pergi = "Senin";
								break;
								case 'Tue':
									$hari_pergi = "Selasa";
								break;
								case 'Wed':
									$hari_pergi = "Rabu";
								break;
								case 'Thu':
									$hari_pergi = "Kamis";
								break;
								case 'Fri':
									$hari_pergi = "Jumat";
								break;
								case 'Sat':
									$hari_pergi = "Sabtu";
								break;									
							}
						?>
					</font>
					<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->pengaju ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_pb[0]->jabatan ?></td>
					</tr>
					<tr>
						<td>Kota Tujuan </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->kota ?></td>
					</tr>
					<tr>
						<td>Keperluan </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->perihal ?></td>
					</tr>
					<tr>
						<td>Hari </td>
						<td>:&nbsp;&nbsp; <?= $hari_pergi ?></td>
					</tr>
					<tr>								
						<td>Tanggal </td>
						<td>:&nbsp;&nbsp; <?= $tgl_pergi ?></td>
					</tr>
					<tr>
						<td>Lama Perjalanan </td>
						<td>:&nbsp;&nbsp; <?= $lama_hari ?> hari</td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
								$x=1;
								$nom = 0;
								foreach ($detail_pb as $row) {
								$x = $x+1;
								$nom = (int) $row->nominal + $nom;
							?>
								<tr>
                                    <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td>
                                    <td class="ket" >&nbsp;<?= $row->ket ?>&nbsp;</td>
                                    <td class="nom">
										<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
										<label class="mylabel" style="text-align:right"><?= number_format($row->nominal,0,",",".") ?>,-&nbsp;</label>										
									</td>
                                </tr>
                            <?php } ?>
							<?php for($kosong=$x;$kosong<=20;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
							<?php } ?>							
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL </th>
                                <td bgcolor="#b7d5ac">
									<label class="mylabel" style="text-align:left">&nbsp;Rp. </label>
									<label class="mylabel" style="text-align:right"><?= number_format($nom,0,",",".") ?>,-&nbsp;</label>
									<input type="hidden" id="jml_nom" value="<?= $nom ?>">
								</td>
                            </tr>                            
                        </tfoot>
			</table>
			
			<table border="0" width="100%">
				<tr>
					<td width="7%">
					<td style="text-align:justify; text-justify:inter-word;">
						<font style="font-family:Times New Roman; font-size:15px;">
							Setelah selesai menjalankan dinas ke luar kota saya akan membuat laporan dinas dan laporan pertanggungjawaban biaya perjalanan dinas dengan
							melampirkan Struk / Bon biaya terkait kepada bagian keuangan.
						</font>
					</td>
					<td width="7%">
                </tr>
			</table>
			
            <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							<?= $data_pb[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
						<td style="text-align:center; width:49%;" colspan="3">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:25.5%;" colspan="2">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_pb[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
							
							if($data_pb[0]->aju_ttd1 == '1'){
								$ttd1 = $img_path."ttd_".$masternotifikasi[0]->verifikasi1.".png";
							}else if($data_pb[0]->aju_ttd1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							if($data_pb[0]->aju_ttd2 == '1'){
								$ttd2 = $img_path."ttd_".$masternotifikasi[0]->verifikasi2.".png";
							}else if($data_pb[0]->aju_ttd2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
							if($data_pb[0]->aju_ttd3 == '1'){
								$ttd3 = $img_path."ttd_".$masternotifikasi[0]->disetujui1.".png";
							}else if($data_pb[0]->aju_ttd3 == '2'){
								$ttd3 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_pb[0]->nama_ttd ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $masternotifikasi[0]->namav1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $masternotifikasi[0]->namav2 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $masternotifikasi[0]->namad1 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_pb[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $masternotifikasi[0]->jabatanv1 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $masternotifikasi[0]->jabatanv2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $masternotifikasi[0]->jabatand1 ?></i></td>
					</tr>
				</tbody>
			</table>
			<br><br>
		<div width="100%">
            <?php if ((sessPenggunaId()=='33' || isAdmin() || isKepalaDivisi() || sessPenggunaId() != $data_pb[0]->idPengaju) && !isGa() ) { ?>
				<button type="button" class="btn btn-success btn-save float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pb="<?=encrypt($data_pb[0]->idPB)?>"> <i class="fas fa-check"></i> Setujui </button>
				<button type="button" class="btn btn-secondary btn-save float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pb="<?=encrypt($data_pb[0]->idPB)?>"> <i class="fas fa-times"></i> Tolak </button>
            <?php } ?>
			<a href="surat/print_page/PB/<?=$data_pb[0]->idPB?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<?php if($data_pb[0]->lampiran_1 != "") { ?>
			    <a href="<?=$data_pb[0]->lampiran_1?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>
			<?php } ?>
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
            const id_pb = $(this).attr("id-pb");
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Pengajuan Biaya Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/pb/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_pb : id_pb,
                            kodesurat : $('#kodesurat').val(),
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
            const id_pb = $(this).attr("id-pb")
            Swal.fire({
				title: 'Tolak Pengajuan Biaya Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/pb/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_pb : id_pb,
                            kodesurat : $('#kodesurat').val(),
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