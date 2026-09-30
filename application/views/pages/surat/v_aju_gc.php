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
			//font-family:Garamond;
			//background:#363;
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
			//background:#b7d5ac;
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
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Pengajuan Limit Grab</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan limit Grab :</font>
					</td>
				</tr>
				<tr>											
		</font>
					<td rowspan="8" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
					</tr>
					<tr>
						<td>Kota Tujuan </td>
						<td>:&nbsp;
							
							<select id="drop_kota_tujuan" class="myselect" style="text-align:left" required>
								<option value="">&nbsp;Pilih Kota</option>
								<?php
								foreach ($list_kota as $row){
									echo "<option value='".$row->tipe." ".$row->nama."'>&nbsp;".$row->tipe." ".$row->nama."</option>";
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Keperluan </td>
						<td>:&nbsp;&nbsp;<input id='perihal' type="text" placeholder='Klik Untuk Memasukkan Keperluan' required></td></td>
					</tr>
					<tr>
						<td>Hari </td>
						<td>:&nbsp;&nbsp; -</td>
					</tr>
					<tr>								
						<td>Tanggal </td>
						<td>
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'>
								<span> :&nbsp;&nbsp; </span>
								<input type="text" id="mulai" Style="width:20%" placeholder='Mulai' required>
								&nbsp; - &nbsp;
								<input type="text" id="selesai" Style="width:20%" placeholder='Selesai' required>
							</div>
						</td>
					</tr>
					<tr>
						<td>Lama Perjalanan </td>
						<td>:&nbsp;&nbsp; -</td>
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
								$itung = 1;
								for($x=1;$x<=15;$x++){
									$itung = $x;
							?>
								<tr>
                                    <td style="text-align:center">
										<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="<?= 'tgl_'.$x ?>" Style="width:100%; text-align:center"  required>										
									</td>
                                    <td><input type="text" id="<?= 'keterangan_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="number" id="<?= 'nominal_'.$x ?>" Style="width:100%; text-align:right" required></td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL </th>
								<th bgcolor="#b7d5ac" style="text-align:right"> -&nbsp;</th>                                
                            </tr>
                        </tfoot>
			</table>
			<br>
            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">
							<select id="drop_kota" style="text-align:right; width:50%; border:0px; margin:0px;" required>
								<option value="">Pilih Kota</option>
								<?php
								foreach ($list_kota as $row) {
									echo "<option value='".$row->id."PengHubunG".$row->nama."'>".$row->nama."</option>";
								}
								?>
							</select>
							<span> ,&nbsp; </span>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="pengajuan" Style="width:15%; text-align:left" placeholder="Pengajuan" required>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:49%;" ></td>
						<td style="text-align:center; width:25.5%;" >Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Mulia<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Staff Accounting</i></td>
					</tr>
				</tbody>
				
			</table>
			</div>
			<?= form_close(); ?>
			<br><br>
		<div role="document">
			<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Ajukan </button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var kota 		= $('#drop_kota_tujuan').val();			
			var kota_aju	= $('#drop_kota').val();
			const 	isi		= kota_aju.split("PengHubunG");
				kota_aju	= isi[1];
			var perihal 	= $('#perihal').val();
			var hari 		= $('#hari').val();
			var pergi 		= $('#mulai').val();
			var pulang 		= $('#selesai').val();
			var pengajuan	= $('#pengajuan').val();
			
			let itung_isi	= $('#itung').val();
			let ket			= [];
			let keterangan	= [];
			let tgl			= [];
			let tanggal		= [];
			let nom			= [];
			let nominal		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#keterangan_'+i).val() != ""){
					tgl[i] = $('#tgl_'+i).val();
					ket[i] = $('#keterangan_'+i).val();		  
					nom[i] = $('#nominal_'+i).val();
				}				
			}			
			let itung		= ket.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Limit Grab?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/gc',
                        dataType: 'JSON',
                        data: {
							kota	: kota,
							kota_aju : kota_aju,
							perihal	: perihal,
							hari	: hari,
							pergi	: pergi,
							pulang	: pulang,
							itung	: itung,
							pengajuan	: pengajuan,
							keterangan	: ket,
							tanggal	: tgl,
							nominal	: nom,
							csrf_token	: token
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