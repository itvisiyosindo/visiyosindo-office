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
			<h2><font color='#000000' face='Times New Roman'>No : <?= $data_serah[0]->kode?></font></h2>
        </div>
		<font color='#000000'>
			<?php
			$terima = $data_serah[0]->id_terima;
			$diketahui = $data_serah[0]->id_diketahui;

		    $ttd = "terima";
				if((sessPenggunaId() == $terima)){
					$ttd = 'terima';
				}else if((sessPenggunaId() == $diketahui)){
					$ttd = 'ketahui';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_serah[0]->idGc ?> 
		<input type="hidden" name="id" id="id" value="<?= $data_serah[0]->idGc ?>">


		
    <?= form_open('surat_new/updateApprovalEks', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

		<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			<font color='#ffffff'>
								<?= 

									$tanggal		= strtotime($data_serah[0]->tanggal);
									$hari 	= date("D", $tanggal);
									$tgl 	= date("d", $tanggal);
									$bln 	= date("M", $tanggal);
									$thn 	= date("Y", $tanggal);
									switch($hari){
										case 'Sun':
											$hari = "Minggu";
										break;
										case 'Mon':         
											$hari = "Senin";
										break;
										case 'Tue':
											$hari = "Selasa";
										break;
										case 'Wed':
											$hari = "Rabu";
										break;
										case 'Thu':
											$hari = "Kamis";
										break;
										case 'Fri':
											$hari = "Jumat";
										break;
										case 'Sat':
											$hari = "Sabtu";
										break;									
									}


									$bulan = [
											"Jan" => "Januari",
											"Feb" => "Februari",
											"Mar" => "Maret",
											"Apr" => "April",
											"May" => "Mei",
											"Jun" => "Juni",
											"Jul" => "Juli",
											"Aug" => "Agustus",
											"Sep" => "September",
											"Oct" => "Oktober",
											"Nov" => "November",
											"Dec" => "Desember"
									];

									$blnInggris = date("M", $tanggal);
									$bln = $bulan[$blnInggris];

									if ($data_serah[0]->idPengaju==58){
										$pengaju = "Amtisari Destiani Eka Putri";
									}else{
										$pengaju = $data_serah[0]->pengaju;
									}

								?>
							</font>
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Berita Acara Serah Terima Pekerjaan ini dibuat pada hari ini:</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>								
						<td width="15%">Hari </td>
						<td> :&nbsp;&nbsp; <?= $hari ?></td>
					</tr>
					<tr>								
						<td>Tanggal </td>
						<td> :&nbsp;&nbsp; <?= $tgl ?> - <?= $bln ?> - <?= $thn ?></td>
					</tr>
					<tr>								
						<td>Bertempat di </td>
						<td> :&nbsp;&nbsp; <?= $data_serah[0]->kota_aju ?></td>
					</tr>
					
				</tr>

				<tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
			
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Oleh dan diantara :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>
						<td width="15%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_serah[0]->pengaju ?> </td>
					</tr>
					<tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->nppaju ?></td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->jabatan ?></td>
					</tr>
				</tr>

					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Selanjutnya disebut sebagai <b>“Pihak Pertama” </b></font>
						</td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>

				<tr>											
				</font>
					<tr>
						<td width="15%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_serah[0]->penerima ?> </td>
					</tr>
					<tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->nppterima ?></td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->jabatan2 ?></td>
					</tr>
				</tr>

					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Selanjutnya disebut sebagai <b>“Pihak Kedua” </b></font>
						</td>
					</tr>
				<tr>
						<td><font color="white">i </font></td>
				</tr>


			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				
				<tr>	
														
					</font>
		
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					

					
				</tr>
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>	
					<td colspan="3" style="text-align:left">
						<font color='#000000'>	
							Pihak Pertama dan Pihak Kedua secara bersama-sama selanjutnya disebut sebagai <b>“Para Pihak”</b>. Para Pihak dengan ini menerangkan dan menyatakan hal-hal sebagai berikut:
						</font>
					</td>
				</tr>
				
														
				<tr>
						<td><font color="white">i </font></td>
				</tr>
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>		
					<td rowspan="12"></td>
					<tr>
						<td width="5%">(1)</td>
						<td>
							<font color='#000000'>	
								Bahwa, Pihak pertama menyerahkan tugas dan wewenang pekerjaan (daftar terlampir_Lampiran 1) ke pada pihak kedua karena pihak pertama resign dari perusahaan dimulai saat serah terima ini dibuat. Pihak kedua selanjutnya berkewajiban menjalan tugas dan wewenang pekerjaan tersebut.
							</font>
						</td>
					</tr>
					<tr>
						<td width="5%">(2)</td>
						<td>
							<font color='#000000'>	
							Pihak pertama menyerahkan pekerjaan sedang di proses dan terkendala (daftar terlampir_lampiran2) ke pada pihak kedua karena pihak pertama resign dari perusahaan dimulai saat serah terima ini dibuat. Pihak kedua selanjutnya berkewajiban meneruskan pekerjaan yang sedang di proses dan terkendala tersebut dan melaporkan ke atasan terkait.
						</font>
						</td>
					</tr>
					<tr>
						<td width="5%">(3)</td>
						<td>
							<font color='#000000'>	
								Pihak Pertama berkewajiban untuk menyerahkan username dan password (daftar terlampir_lampiran 3) yang digunakan untuk menjalankan tugas dan wewenang kepada Pihak Kedua. 
							</font>
						</td>
					</tr>
					<tr>
						<td width="5%">(4)</td>
						<td>
							<font color='#db1514'>	
								Bahwa Pihak Pertama dengan ini menyerahkan Tugas dan Wewenang Pekerjaan serta Dokumen kepada Pihak Kedua karena pihak pertama tidak lagi bertanggung jawab atas pekerjaan terkait dan Pihak Kedua dengan ini menerima Tugas dan Wewenang dan Dokumen tersebut dari Pihak Pertama.
							</font>
						</td>
					</tr>
					<tr>
						<td width="5%">(5)</td>
						<td>
							<font color='#000000'>	
								Bahwa dengan telah dilakukannya serah terima Pekrjaan dan Dokumen tersebut sebagaimana dimaksud dalam Butir (1 dan 2) diatas, maka kewajiban Pihak Pertama untuk menyerahkan Pekerjaan dan Dokumen kepada Pihak Kedua dan hak Pihak Kedua untuk menerima Pekerjaan dan Dokumen tersebut dari Pihak Pertama telah dilaksanakan.							
							</font>
						</td>
					</tr>
				</tr>
				
														
				<tr>
						<td><font color="white">i </font></td>
				</tr>
			</table>

		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
							<tr>
									<th style="text-align:center" bgcolor="#d3d3d3" width="5%"> No </th>
									<th style="text-align:center" bgcolor="#d3d3d3"> Nama Pekerjaan </th>
									<th style="text-align:center" bgcolor="#d3d3d3" width="15%"> Link </th>
									<th style="text-align:center" bgcolor="#d3d3d3" width="30%"> Keterangan </th>
							</tr>
					</thead>
					<tbody>
							<?php
									$x = 1;
									$xy = 0;
									$totalJumlah = 0; // Variabel untuk menghitung total jumlah
									foreach ($detail_serah as $row) {
											$x = $x + 1;
											$xy = $xy + 1;
											$totalJumlah += $row->jumlah; // Tambahkan jumlah ke total

											if($row->link != ''){
												$link = '<a href="'.$row->link.'" target="blank"><i class="fas fa-link"></i> Link</a>';
											}else{
												$link = "Tidak Ada Link";
											}
							?>
							<tr>
									<td style="text-align:center">
											<input type="hidden" name="id_sodetail[]" value="<?= encrypt($row->id) ?>"><?= $xy ?>
									</td>
									<td class="des">&nbsp;<?= $row->nama ?>&nbsp;</td>
									<td style="text-align:center" class="qty">&nbsp;	<?= $link ?> &nbsp;</td>
									<td style="text-align:center" class="qty">&nbsp;<?= $row->ket ?>&nbsp;</td>
							</tr>
							<?php } ?>

							<!-- Tambahkan baris kosong jika diperlukan -->
							<?php for ($kosong = $x; $kosong <= 5; $kosong++) { ?>
							<tr>
									<td><font color="white">i </font></td>
									<td></td>
									<td></td>
									<td></td>
							</tr>
							<?php } ?>

							
					</tbody>
			</table>

			<br>
								

			<table id="tbl_17" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			

				<tr>
					<tr>
						<td> Keterangan : &nbsp;&nbsp; <?=  $data_serah[0]->keterangan ?> </td>
					</tr>
					
					<tr>
						
						<td> <br><br>Demikian Berita Acara ini dibuat di tempat dan pada waktu sebagaimana disebutkan pada bagian awal Berita Acara ini. </td>
					</tr>
					<tr>
						<td width="10%" height="35px"> </td>
					</tr>
					
												
				</tr>
			</table>

			<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" >Pihak Pertama,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" >Pihak Kedua,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" >Mengetahui,</td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_serah[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							
							if($data_serah[0]->ttd == '1'){
								$ttd1 = $img_path."ttd_".$data_serah[0]->id_terima.".png";
							}else if($data_serah[0]->ttd == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							if($data_serah[0]->ttd_1 == '1'){
								$ttd2 = $img_path."ttd_".$data_serah[0]->id_diketahui.".png";
							}else if($data_serah[0]->ttd_1 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:25%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_serah[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_serah[0]->penerima ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_serah[0]->mengetahui ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_serah[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_serah[0]->jabatan2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_serah[0]->jabatan3 ?></i></td>
					</tr>
				</tbody>
				
			</table>


      
			</div>
		
			<br><br>
		<div width="100%">
			
        <?php 
				
				if (sessPenggunaId() == '1'|| sessPenggunaId() == $terima || sessPenggunaId() == $diketahui) { ?>
				<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_serah[0]->idGc)?>"> <i class="fas fa-check"></i> Terima </button>
				<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_serah[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>				
       <?php } ?>
				<a href="surat_new/print_page/stp/<?=$data_serah[0]->idGc?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
        <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
			
			<?php if($data_serah[0]->lampiran != ""){ ?>
				<a href="<?=$data_serah[0]->lampiran?>" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran</a>
			<?php } ?>
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
				title: 'Setujui Permintaan Serah Terima Pekerjaan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/ttd_setujui/stp/'+level_ttd,
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
				title: 'Tolak Permintaan Serah Terima Pekerjaan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/ttd_tolak/stp/'+level_ttd,
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
