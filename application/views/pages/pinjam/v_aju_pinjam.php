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
            <h2><font color='#000000' face='Times New Roman'>Form Permintaan Peminjaman Unit</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan permintaan Peminjaman Unit :</font>
					</td>
				</tr>
				<tr>											
		</font>
					<td rowspan="17" width="2%"></td>
					<tr>
						<td width="25%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->no_pegawai ?></td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					
					
					

					<tr>
						<td>Keperluan</td>
						<td>:&nbsp;&nbsp;<input id='keperluan' type="text" placeholder='Klik Untuk Memasukkan Keperluan Peminjaman (Misal Demo, Sewa atau Pinjam Pakai)' required></td>
					</tr>
					

					<tr>
						<td>Link Lampiran</td>
						<td>:&nbsp;&nbsp;<input id='lampiran' type="text" placeholder='Klik Untuk Memasukkan Link Lampiran (Jika Ada)' required></td>
					</tr>

					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                	<thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="3%"> No </th>
                                <th style="text-align:center" bgcolor="#d3d3d3"> Nama Barang </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="7%"> Jumlah </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="30%"> Keterangan</th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="17%"> Serial Number (SN) </th>
                            </tr>
                  </thead>
                  <tbody>
									<?php
										$itung = 1;
										for($x=1;$x<=10;$x++){
										$itung = $x;
									?>
																<tr>
                                    <td style="text-align:center"><?= $x ?> </td>
																		<td><input type="text" id="<?= 'namabarang_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="number" id="<?= 'jumlah_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="text" id="<?= 'keterangan_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <td><input type="text" id="<?= 'serialnumber_'.$x ?>" placeholder='Diisi oleh Team Warehouse' Style="width:100%; text-align:center" disabled></td>
                                </tr>
									<?php } ?>
									<input type="hidden" id="itung" value="<?= $itung ?>">
              </tbody>                        
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
							<span> ,&nbsp; Diisi sistem</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" ></td>
						<td style="text-align:center; width:44.5%;" ></td>
						<td style="text-align:center; width:25.5%;" >Diajukan Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
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
			var kota_aju	= $('#drop_kota').val();
			const 	isi		= kota_aju.split("PengHubunG");
				kota_aju		= isi[1];
			var keperluan = $('#keperluan').val();
			var lampiran	= $('#lampiran').val();

			
			let itung_isi	= $('#itung').val();
			let nam			= [];
			let namabarang	= [];
			let jum			= [];
			let jumlah	= [];
			let ket			= [];
			let keterangan	= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#namabarang_'+i).val() != ""){
					nam[i] = $('#namabarang_'+i).val();
					jum[i] = $('#jumlah_'+i).val();			  
					ket[i] = $('#keterangan_'+i).val();		  
				}				
			}			
			let itung		= nam.length;
			
			Swal.fire({
				title: 'Ajukan Permintaan Peminjaman Unit?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'pinjam/add',
                        dataType: 'JSON',
                        data: {
													kota_aju		: kota_aju,
													keperluan		: keperluan,
													lampiran		: lampiran,
													itung				: itung,
													namabarang	: nam,
													jumlah			: jum,
													keterangan	: ket,
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