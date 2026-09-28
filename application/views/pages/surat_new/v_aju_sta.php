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
            <h2><font color='#000000' face='Times New Roman'>Form Serah Terima Aset</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan Serah Terima Aset :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>

					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Kepada :</font>
						</td>
					</tr>

				<tr>
					<tr>
						<td width="18%">Nama </td>
						<td style="text-align:left; ">
						
                        		<select name="id_terima" id="id_terima" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama yang Penerima</option>
                                    <?php
                                    //$nama = $this->db->get('pengguna')->where('pengguna.status=1')->result();
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                        	
						</td>
					</tr>
					
					
					
					
					<tr>								
						<td>Tanggal </td>
						<td>
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'>
								<span> :&nbsp;&nbsp; </span>
								<input type="text" id="tanggal" Style="width:20%" placeholder='Tanggal' required>
							</div>
						</td>
					</tr>
					

					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="5%"> No </th>
                                <th style="text-align:center" bgcolor="#d3d3d3"> Nama atau Keterangan Alat </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="20%"> Serial Number (SN) </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="20%"> Jumlah </th>
                            </tr>
                        </thead>
                        <tbody>
									<?php
										$itung = 1;
										for($x=1;$x<=7;$x++){
										$itung = $x;
									?>
																<tr>
                                    <td style="text-align:center"><?= $x ?> </td>
																		<td><input type="text" id="<?= 'nama_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="text" id="<?= 'sn_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="text" id="<?= 'jumlah_'.$x ?>" Style="width:100%" required></td>
																		
                                </tr>
										<?php } ?>
										<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        
			</table>
			<br>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
					</td>
				</tr>
				<tr>											
		</font>
					<td rowspan="12" width="7%"></td>
					<tr>
						<td width="18%">Keterangan </td>
						<td>:&nbsp;&nbsp;<input id='keterangan' type="text" placeholder='Klik Untuk Memasukkan Keterangan' required></td>
					</tr>
					<tr>
						<td>Link Lampiran </td>
						<td>:&nbsp;&nbsp;<input id='lampiran' type="text" placeholder='Klik Untuk Memasukkan Lampiran' required></td>
					</tr>
					
					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>


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
			var tanggal		= $('#tanggal').val();
			var id_terima 		= $('#id_terima').val();
			var keterangan 		= $('#keterangan').val();
			var lampiran	= $('#lampiran').val();
			
			let itung_isi	= $('#itung').val();
			let nam				= [];
			let nama			= [];
			let ser				= [];
			let sn				= [];
			let jum				= [];
			let jumlah		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#nama_'+i).val() != ""){
					nam[i] = $('#nama_'+i).val();
					ser[i] = $('#sn_'+i).val();		  
					jum[i] = $('#jumlah_'+i).val();		  
				}				
			}			
			let itung		= nam.length;
			
			Swal.fire({
				title: 'Ajukan Permintaan Serah Terima Aset?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/addSrt/sta',
                        dataType: 'JSON',
                        data: {
							kota_aju		: kota_aju,
							tanggal			: tanggal,
							id_terima		: id_terima,
							keterangan	: keterangan,
							lampiran		: lampiran,
							itung				: itung,
							nama				: nam,
							sn					: ser,
							jumlah			: jum,
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