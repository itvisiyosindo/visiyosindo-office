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
            <h2><font color='#000000' face='Times New Roman'>Form Permintaan Approval Expedisi</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan permintaan penawaran :</font>
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
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
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
						<td>Nama Customer </td>
						<td>:&nbsp;&nbsp;<input id='nama_customer' type="text" placeholder='Klik Untuk Memasukkan Nama Customer' required></td>
					</tr>
					<tr>
						<td>Asal Pengiriman </td>
						<td>
							<select class="form-control " name="gudang_asal" id="gudang_asal">
								<option value="">- Pilih Gudang -</option>
								<?php foreach ($gudang as $row) { ?>
									<option value="<?= $row->nama_gudang ?>"><?= $row->nama_gudang ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Tujuan Pengiriman </td>
						<td>:&nbsp;&nbsp;<input id='tujuan' type="text" placeholder='Klik Untuk Memasukkan Tujuan Pengiriman' required></td>
					</tr>
					<tr>
						<td>Detail Barang</td>
						<td>:&nbsp;&nbsp;<input id='nama_barang' type="text" placeholder='Klik Untuk Memasukkan Detail Nama Barang' required></td>
					</tr>
					<tr>
						<td>Berat Barang </td>
						<td>:&nbsp;&nbsp;<input id='no_sj' type="text" placeholder='Klik Untuk Memasukkan Berat Barang' required></td>
					</tr>

					<tr>
						<td><font color="white">i </font></td>
					</tr>

					<tr>
						<td>Rencana Expedisi Yang Akan Digunakan</td>
						<td>:&nbsp;&nbsp;<input id='rencana_expedisi' type="text" placeholder='Klik Untuk Memasukkan Rencana Expedisi Yang Akan Digunakan' required></td>
					</tr>
					<tr>
						<td>Harga Expedisi Yang Diajukan</td>
						<td>:&nbsp;&nbsp;<input id='harga_expedisi' type="text" placeholder='Klik Untuk Memasukkan Harga Expedisi Yang Diajukan' required></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td>PO Customer</td>
						<td>:&nbsp;&nbsp;<input id='po_customer' type="text" placeholder='Klik Untuk Memasukkan Link PO Customer' required></td>
					</tr>
					<tr>
						<td>SPH Customer</td>
						<td>:&nbsp;&nbsp;<input id='sph_customer' type="text" placeholder='Klik Untuk Memasukkan Link SPH Customer' required></td>
					</tr>

					<tr>
						<td>Link Lampiran Lainnya</td>
						<td>:&nbsp;&nbsp;<input id='lampiran' type="text" placeholder='Klik Untuk Memasukkan Link Lampiran Lainya (Jika Ada)' required></td>
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
                                <th style="text-align:center" bgcolor="#d3d3d3"> Nama Expedisi </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="5%"> Berat Barang </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="10%"> Harga Acuan Terendah</th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="10%"> Harga Yang Ditawarkan </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="15%"> Kelebihan </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="15%"> Kekurangan </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="20%" colspan="4"> Approval </th>
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
																		<td><input type="text" id="<?= 'namaekspedisi_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="text" id="<?= 'berat_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="number" id="<?= 'acuanharga_'.$x ?>" Style="width:100%; text-align:right" required></td>
                                    <td><input type="number" id="<?= 'harga_'.$x ?>" Style="width:100%; text-align:right" required></td>
                                    <td><input type="text" id="<?= 'fasilitas_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="text" id="<?= 'kekurangan_'.$x ?>" Style="width:100%; text-align:right" required></td>
																		<td>
                                    	<input  type="checkbox" id="<?= 'approval_'.$x ?>" name="approval" value="1" disabled/>
                                    </td>
                                    <td><label>Disetujui</label></td>
                                    <td>
                                    	<input class="active"
                                    	type="checkbox" id="<?= 'approval_'.$x ?>" name="approval" value="2" disabled/>
                                    </td>
                                    <td><label>Ditolak</label></td>
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
			var tanggal		= $('#tanggal').val();
			var nama_customer 		= $('#nama_customer').val();
			var tujuan 		= $('#tujuan').val();
			var nama_barang	= $('#nama_barang').val();
			var no_sj			= $('#no_sj').val();
			var lampiran	= $('#lampiran').val();

			
			var gudang_asal	= $('#gudang_asal').val();
			var rencana_expedisi	= $('#rencana_expedisi').val();
			var harga_expedisi		= $('#harga_expedisi').val();
			var po_customer		= $('#po_customer').val();
			var sph_customer	= $('#sph_customer').val();
			
			let itung_isi	= $('#itung').val();
			let nam			= [];
			let namaekspedisi	= [];
			let ber			= [];
			let berat		= [];
			let fas			= [];
			let fasilitas		= [];
			let har			= [];
			let harga		= [];
			let acuan			= [];
			let acuanharga		= [];
			let keku			= [];
			let kekurangan		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#namaekspedisi_'+i).val() != ""){
					nam[i] = $('#namaekspedisi_'+i).val();
					ber[i] = $('#berat_'+i).val();			  
					acuan[i] = $('#acuanharga_'+i).val();			  
					har[i] = $('#harga_'+i).val();				  
					fas[i] = $('#fasilitas_'+i).val();		  
					keku[i] = $('#kekurangan_'+i).val();		  
				}				
			}			
			let itung		= nam.length;
			
			Swal.fire({
				title: 'Ajukan Permintaan Approval Ekspedisi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/addSrt/appeks',
                        dataType: 'JSON',
                        data: {
							kota_aju		: kota_aju,
							tanggal			: tanggal,
							nama_customer	: nama_customer,
							tujuan			: tujuan,
							nama_barang	: nama_barang,
							no_sj				: no_sj,
							lampiran		: lampiran,
							gudang_asal	: gudang_asal,
							rencana_expedisi : rencana_expedisi,
							harga_expedisi	: harga_expedisi,
							po_customer		: po_customer,
							sph_customer	: sph_customer,
							itung				: itung,
							namaekspedisi	: nam,
							berat				: ber,
							fasilitas		: fas,
							harga				: har,
							acuanharga	: acuan,
							kekurangan	: keku,
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