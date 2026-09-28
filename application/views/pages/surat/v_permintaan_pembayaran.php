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
		
		.mytextarea{
			width:100%;
			height:auto;
			border:0px solid #000;
			border-radius:0px; 
			-moz-border-radius:8px;
			margin:0px;
		}
		
		.myselect{
			width:auto;
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
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Permintaan Pembayaran</font></h2>
        </div>
		<br>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>BANKING untuk pembayaran sebagai berikut:</font>
					</td>					
				</tr>
			</table>
			<br>
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
                            </tr>
                        </thead>
                        <tbody>
								<tr>
                                    <td style="text-align:center">
										<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl" Style="width:100%; text-align:center"  required>										
									</td>
                                    <td><input type="text" id="keterangan_1" Style="width:100%" required></td>
                                    <td><input type="number" id="nominal_1" Style="width:100%; text-align:right" required></td>
                                </tr>
							<?php
								$itung = 1;
								for($x=2;$x<=40;$x++){
									$itung = $x;
							?>
								<tr>
                                    <td></td>
                                    <td><input type="text" id="<?= 'keterangan_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="number" id="<?= 'nominal_'.$x ?>" Style="width:100%; text-align:right" required></td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL PENGAJUAN </th>
								<th bgcolor="#b7d5ac" style="text-align:right"> -&nbsp;</th>
                                <!--<td><input type="text" id="total" Style="width:100%; text-align:right" class="myinput" placeholder="-" disabled required></td>-->
                            </tr>
                        </tfoot>
			</table>
			
			<table border="0" style="font-family:Times New Roman; font-color:black; font-size:15px" width="100%">
				<tr>
					<td colspan="2" width="20%" style="text-align:center"><font>Terbilang :</font></td>
					<td style="text-align:justify">&nbsp; -</td>
					<br>
                </tr>
				<tr>
					<th colspan="3" style="text-align:left">Rekening / Virtual Account Pembayaran :</th>
				</tr>
					<tr>
						<td>Nama Bank </td>
						<td width="2px">:&nbsp;</td>
						<td><input type="text" id="bank" name="bank" Style="width:100%" placeholder="Ketik Nama Bank" required></td>
					</tr>
					<tr>
						<td>No Rekening </td>
						<td>:&nbsp;</td>
						<td><input type="text" id="norek" name="norek" Style="width:100%" placeholder="Ketik No Rekening" required></td>
					</tr>
					<tr>
						<td>Atas Nama </td>
						<td>:&nbsp;</td>
						<td><input type="text" id="pengaju" name="pengaju" Style="width:100%" placeholder="Ketik Nama Pemilik Rekening" required></td>
					</tr>					
					<tr>
						<td colspan="3"><font color="white">i </font></td>
					</tr>
					<tr>
						<td>Mata Uang </td>
						<td>:&nbsp;</td>
						<td>
							<select class="myselect" id="mata_uang" name="mata_uang" required>
								<option value="">Pilih Mata Uang</option>
								<option value="1"><i class='fas fa-money-bill'></i> Rupiah (Rp)</option>
								<option value="2"><i class='fas fa-dollar-sign'></i> Dollar ($)</option>
								<option value="3"><i class='fas fa-money-bill'></i> Ringgit (RM)</option>
								<option value="4"><i class='fas fa-dollar-sign'></i> SGD ($)</option>
								<option value="5"><i class='fas fa-rupee-sign'></i> Rupee (₹)</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Link Lampiran </td>
						<td>:&nbsp;</td>
						<td><input type="text" id="lampiran" name="lampiran" Style="width:100%" placeholder="Ketik Link Lampiran" required></td>
					</tr>					
				</tr>
			</table>
			
			<br>
			<br>
			
            <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
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
						<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
						<td style="text-align:center; width:49%;" colspan="3">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:25.5%;" colspan="2">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
							$ttd2 		= $img_path."ttd_blank.png";
							$ttd3 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center; width:23%; height:60px;"> <?php echo'<img src="'.$ttdaju.'" height="75">';?> </td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="75">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="75">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd3.'" height="75">';?></td>
					</tr>
					<tr>
						<th style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></th>						
						<td style="text-align:center; "></td>
						<th style="text-align:center; ">Yolanda Pratiwi<hr></hr></th>
						<td style="text-align:center; "></td>
						<th style="text-align:center; ">Meilina Safitri<hr></hr></th>
						<td style="text-align:center; "></td>
						<th style="text-align:center; ">Bob Ariyos<hr></hr></th>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>						
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Pimpinan Umum</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Management Business</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>
				</tbody>
			</table>
			<?= form_close(); ?>
			<br><br>
		<div role="document">
			<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Ajukan </button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br>
    </div>	
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {		
        
		$(document).on('click', '.btn-ajukan', function() {
			var kota_aju	= $('#drop_kota').val();
			const 	isi		= kota_aju.split("PengHubunG");
				kota_aju	= isi[1];
			var tgl			= $('#tgl').val();
			var tgl_aju		= $('#pengajuan').val();
			var bank		= $('#bank').val();
			var norek		= $('#norek').val();
			var nama		= $('#pengaju').val();
			var mata_uang	= $('#mata_uang').val();
			var lampiran	= $('#lampiran').val();
			
			let itung_isi	= $('#itung').val();
			let ket			= [];
			let keterangan	= [];
			let nom			= [];
			let nominal		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#keterangan_'+i).val() != ""){
					ket[i] = $('#keterangan_'+i).val();		  
					nom[i] = $('#nominal_'+i).val();
				}				
			}
			let itung		= ket.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Pembayaran?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/spp',
                        dataType: 'JSON',
                        data: {
							kota_aju    : kota_aju,
							itung	    : itung,							
							nama	    : nama,
							lampiran    : lampiran,
							tgl_aju     : tgl_aju,
							bank	    : bank,
							norek	    : norek,
							tgl		    : tgl,
							keterangan	: ket,
							nominal	    : nom,
							mata_uang   : mata_uang,
							csrf_token	: token
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
                        url: 'surat/ttd_update/pb/2/ttd_1',
                        dataType: 'JSON',
                        data: {
                            id_pb : id_pb,
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