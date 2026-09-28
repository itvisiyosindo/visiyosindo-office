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
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Pengajuan PPA</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan pengajuan biaya sebagai berikut :</font>
					</td>
				</tr>
				<tr>					
						
					</font>
					<td rowspan="10" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Divisi </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
						<!-- <td>:<input type="text" name="" id="" placeholder="Ketik Nama Divisi" required></td> -->
					</tr>
					<tr>
						<td>Rencana Tempat Pembelian </td>
						<td>:<input type="text" name="alamat" id="alamat" placeholder=" &nbsp;Ketik Alamat" required></td>
					</tr>

					<tr>
						<td  width="30%">Nomor Rekening Pembayaran </td>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Nama Bank </td>
						<td>:<input type="text" name="bank" id="bank" placeholder=" &nbsp;Ketik Nama Bank" required></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Nomor Rekening </td>
						<td>:<input type="text" name="rekening" id="rekening" placeholder=" &nbsp;Ketik Nomor Rekening" required></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Atas Nama </td>
						<td>:<input type="text" name="ats_nama" id="ats_nama" placeholder=" &nbsp;Ketik Nama" required></td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Lampiran </td>
						<td>:<input type="text" name="lampiran" id="lampiran" placeholder=" &nbsp;Pastekan link lampiran anda" required></td>
						</tr>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%"> No. </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Estimasi Harga Satuan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="10%"> Qty </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Estimasi Harga Total </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung = 1;
								for($x=1;$x<=12;$x++){
									$itung = $x;
							?>
								<tr>
                                    <td style="text-align:center">
										<!-- <?= $x ?> -->
										<input type="number" id="no" name="no" Style="width:100%" required>
									</td>
                                    <td>
                                    	<input type="text" id="<?= 'keterangan_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="number" id="<?= 'estimasi_'.$x ?>" Style="width:100%; text-align:right" required>
                                    </td>
                                    <td>
                                    	<input type="number" id="<?= 'qty_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>                                    	
                                    </td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="3" style="text-align:center"> GRAND TOTAL </th>
								<th bgcolor="#b7d5ac" style="text-align:right"> </th>
								<th bgcolor="#b7d5ac" style="text-align:right"> </th>                                
                            </tr>
                        </tfoot>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="3" style="text-align:center">TOTAL </th>
								<th bgcolor="#b7d5ac" style="text-align:right"> </th>
								<th bgcolor="#b7d5ac" style="text-align:right"> </th>                                
                            </tr>
                        </tfoot>
                        <tfoot>
                        	
                            <tr>
                                <th  colspan="3" style="text-align:left" align="left" >
                                	<table border="0" style="width:100%">
                                		<tr>
                                			<td>Tipe dan Tarif Pajak</td>
                                			<td>&nbsp;:</td>
                                			<td colspan="4"><input type="text" id="trf_pajak" name="trf_pajak" style="width:100%" placeholder="Diisi oleh finance" disabled></td>
                                		</tr>
                                		<tr>
                                			<td>Nominal Pajak</td>
                                			<td>&nbsp;:</td>
                                			<td colspan="4"><input type="text" id="nml_pajak" name="nml_pajak" style="width:100%" placeholder="Diisi oleh finance" disabled></td>
                                		</tr>
                                		<tr>
                                			<td>Pajak dibayarkan oleh</td>
                                			<td>&nbsp;:</td>
                                			<td><input type="radio" id="pembayar_pajak" name="pembayar_pajak" width="100%" disabled /></td>
                                			<td><label>Vendor</label></td>
                                			<td><input type="radio" id="pembayar_pajak" name="pembayar_pajak" disabled /></td>
                                			<td><label>PT. Visi Yosindo Medikal</label></td>
                                		</tr>
                                	</table>
                                 </th>
								<th style="text-align:left"> </th>                                
                            </tr>
                        </tfoot>

			</table>
			
            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr>
						<td height="20px"></td>
					</tr>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							 <select id="kota_aju" name="kota_aju" style="text-align:right; width:50%; border:0px; margin:0px;" required>
								<option value="">Pilih Kota</option>
								<?php
								foreach ($list_kota as $row) {
									echo "<option value='".$row->id."PengHubunG".$row->nama."'>".$row->nama."</option>";
								}
								?>
							</select>
							<span> ,&nbsp; </span>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="tgl_pengajuan" name="tgl_pengajuan" Style="width:15%; text-align:left" placeholder="Tanggal Pengajuan" required> 
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="3">Diajukan Oleh,</td>
						<td style="text-align:center; width:49%;" colspan="3">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:25.5%;" colspan="3">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
							$ttd2 		= $img_path."ttd_blank.png";
							$ttd3 		= $img_path."ttd_blank.png";
							$ttd4 		= $img_path."ttd_blank.png";
							$ttd5 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd4.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dirangga Madali<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Yolanda Pratiwi<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Bob Ariyos<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Head of Accounting and Tax</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Pimpinan Umum</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corp Planning & Management Bussinees</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
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
			var alamat 			= $('#alamat').val();			
			var bank			= $('#bank').val();
			var rekening		= $('#rekening').val();
			var ats_nama		= $('#ats_nama').val();
			var lampiran		= $('#lampiran').val();
			var kota_aju		= $('#kota_aju').val();
			const 	isi			= kota_aju.split("PengHubunG");
				kota_aju		= isi[1];
			var tgl_pengajuan	= $('#tgl_pengajuan').val();
			
			let itung_isi	= $('#itung').val();
			let keterangan	= [];
			let estimasi	= [];
			let qty			= [];			
			let total 		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#keterangan_'+i).val() != ""){
					keterangan[i]	= $('#keterangan_'+i).val();
					estimasi[i]		= $('#estimasi_'+i).val();
					qty[i]			= $('#qty_'+i).val();
					total[i]		= estimasi[i]*qty[i];
				}				
			}			
			let itung		= keterangan.length;
			
			Swal.fire({
				title: 'Ajukan Permintaan PPA?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/ppa',
                        dataType: 'JSON',
                        data: {
                        	alamat 			: alamat,
							kota_aju 		: kota_aju,
							bank			: bank,
							rekening		: rekening,
							ats_nama		: ats_nama,
							lampiran 		: lampiran,
							keterangan		: keterangan,
							estimasi 		: estimasi,
							qty 			: qty,
							total 			: total,
							itung 			: itung,
							tgl_pengajuan 	: tgl_pengajuan
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }				
            })
        })
		
		$(document).on('click', '.btn-denial', function() {
            const id_ppa = $(this).attr("id-ppa")
            Swal.fire({
				title: 'Tolak Pengajuan PPA?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/ppa/2/ttd_1',
                        dataType: 'JSON',
                        data: {
                            id_pbok : id_pbok,
							csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
                }
            })
        })
		
		Array.prototype.forEach.call(document.getElementsByName('kode_tiket'),
		function (elem) {
			elem.addEventListener('change', function() {
				let 	text	= this.value;
				const 	isi		= text.split("PengHubunG");
				let		idTiket		= isi[0],
						tampil_cust = isi[1],
						tampil_kota = isi[2];						
				
				if (text == ""){
					tampil_cust	= "Identitas Pelanggan";
					tampil_kota	= "Informasi Kota Asal Pelanggan";
				}
				
				document.getElementById('tampil_pelanggan').innerHTML = tampil_cust;
				document.getElementById('tampil_kota_visit').innerHTML = tampil_kota;
				document.getElementById('id_tiket').value = idTiket;				
			});
		});
		
    })

    function goBack() {
        window.history.back();
    }
</script>