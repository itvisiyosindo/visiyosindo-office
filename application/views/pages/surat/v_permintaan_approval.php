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
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Form Approval Harga</font><hr width="80%"></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>					
						
					</font>
					<!-- <td rowspan="10" width="7%"></td> -->
					<tr>
						<td width="25%">Tanggal </td>
						<td>:</td>
						<td><input type="text" name="tgl" id="tgl" value="<?php echo date('d-m-Y'); ?>" placeholder=" &nbsp;(terlampir)" required disabled></td>
						<!-- <td style="text-align:center; "><?= date('d-m-Y', strtotime($data_approval[0]->tgl)); ?></td> -->
					</tr>
					<tr>
						<td>Nama Marketing </td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="nama" id="nama" >
                                        
                                            <option value="">- Pilih Marketing-</option>
																						<option value = "Office / Kantor Pusat">Office / Kantor Pusat</option>
																						<?php
																						foreach ($nama_marketing as $row) {
																							echo '<option value="' . $row->nama . '">' . $row->nama . '</option>';
																						}
																						?>
                                        </select>
						</td>
					</tr>
					<tr>
						<td>Nama Customer </td>
						<td>:</td>
						<td><input type="text" name="nama_customer" id="nama_customer" placeholder=" &nbsp;Nama Customer" required></td>
					</tr>
					<tr>
						<td>Detail Order Confirmation </td>
						<td>:</td>
						<td><input type="text" name="detail_order" id="detail_order" placeholder=" &nbsp;(terlampir)" required></td>
					</tr>
					
					<tr>
						<td>Sistem Pembayaran </td>						
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="payment" id="payment" >
                  		<option value = "CASH">CASH</option>
											<option value = "COD">COD</option>
											<option value = "NET 30">NET 30</option>
											<option value = "CREDIT">CREDIT</option>
											<option value = "1">Lainnya</option>
                </select>     
							&nbsp;&nbsp;<input id="lainnyaInput" type="text" placeholder='Klik Untuk Memasukkan Sistem Pembayaran' style="display: none;" required></td>                  
						</td>
					</tr>	
					
					
					<tr>
						<td>Tipe Cicilan </td>
									
						<td>:</td>
						<td><input id='cicilan' type="text" placeholder='Klik Untuk Memasukkan DP dan Lama Cicilan' required></td>
					</tr>

					<tr>
						<td>Ongkos Kirim </td>		
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="ongkir" id="ongkir" >
                  <option value = "Termasuk">Termasuk</option>
									<option value = "Tidak Termasuk">Tidak Termasuk</option>
									<option value = "1">Tambahkan Referensi Ongkir</option>
                </select>     
							&nbsp;&nbsp;<input id="lainnyaOngkir" type="text" placeholder='Klik Untuk Memasukkan Tambahkan Referensi Ongkir' style="display: none;" required></td>                  
						</td>
					</tr>
					
					<tr>
						<td>Pajak </td>						
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="pajak" id="pajak" >
                  <option value = "Belum Termasuk PPN">Belum Termasuk PPN</option>
									<option value = "Termasuk PPN">Termasuk PPN</option>
									<option value = "Non PPN">Non PPN</option>
                </select>                   
						</td>
					</tr>	
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td>Notifikasi </td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="notifikasi" id="notifikasi" >
                  <option value="">- Default -</option>
									<option value = "1">Admin VISILAB</option>
								</select>
						</td>
					</tr>
					<tr>
						<td colspan="3"><font color='#000000'><b>Note</b> : Jika ada produk <b>UKES, UPAR atau TLD</b> pilih notifikasi <b>Admin VISILAB</b></font></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td colspan="3"><font color='#000000'>Pada Order Confirmation diatas terdapat harga product yang akan ditawarkan dibawah harga Pricelist 
						setelah diberikan diskon maksimal / Acuan Harga Terendah dengan detail :</font></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center">Nama Barang/Package </th>
                                <th style="text-align:center" width="25%"> Acuan Harga Terendah </th>
                                <th style="text-align:center" width="25%"> Harga yang akan ditawarkan </th>
                                <th style="text-align:center" width="20%" colspan="4"> Approval </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung = 1;
								for($x=1;$x<=25;$x++){
									$itung = $x;
							?>
								<tr>
                                    <td>
                                    	<input type="text" id="<?= 'nama_barang_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="number" id="<?= 'acuan_hrg_'.$x ?>" Style="width:100%; text-align:right" required>
                                    </td>
                                    <td>
                                    	<input type="number" id="<?= 'hrg_ditawarkan_'.$x ?>" Style="width:100%; text-align:right" required>
                                    </td>
                                    <td >
                                    	<input  type="checkbox" id="<?= 'approvall_'.$x ?>" name="approvall" value="1" disabled/>
                                    </td>
                                    <td><label>Disetujui</label></td>
                                    <td>
                                    	<input class="active"
                                    	type="checkbox" id="<?= 'approvall_'.$x ?>" name="approvall" value="2" disabled/>
                                    </td>
                                    <td><label>Ditolak</label></td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung" name="itung" value="<?= $itung ?>">
                        </tbody>
			</table>
			<br>
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <thead>
                            <tr>
                                <th width="25%">*Tarif Komisi Marketing : </th>
                                <th width="25%"> *Catatan : </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung1 = 1;
								for($x1=1;$x1<=1;$x1++){
									$itung1 = $x1;
							?>
								<tr>
                                    <td >
                                    	<textarea type="text" id="<?= 'trf_komisi_'.$x1 ?>" placeholder="Diisi oleh Accounting" Style="width:100%" disabled> </textarea>
                                    </td>
                                    <td >
                                    	<textarea type="text" id="<?= 'catatan_'.$x1 ?>" placeholder="Diisi oleh Accounting" Style="width:100%" disabled> </textarea>
                                    </td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung1" name="itung1" value="<?= $itung1 ?>">
                        </tbody>
			</table>

			<table>
				<tr>
					<td><font color="white">i </font></td>
				</tr>
				<tr>
					<td><font color="#000000" style="font-family:Times New Roman"><i>(*Tarif Komisi Marketing dan Catatan diisi oleh Director of Corporate Planning & Business Management)</i></font></td>
				</tr>
				<tr>
					<td><font color="white">i </font></td>
				</tr>
			</table>
			
            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 18px;">
						<td style="text-align:center; ">Dibuat Oleh,</td>
						<td style="text-align:right; ">Diverifikasi Oleh,</td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
							$ttd2 		= $img_path."ttd_blank.png";
							$ttd3 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><strong><?= $pengguna[0]->short_name ?></strong><hr width="40%"></td>
						<td style="text-align:center; "><strong>Yolanda Pratiwi</strong><hr width="40%"></td>
						<td style="text-align:center; "><strong>Dirangga Madali</strong><hr width="40%"></td>
						<td style="text-align:center; "><strong>Meilina Safitri</strong><hr width="40%"></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i>(<?= $pengguna[0]->jabatan ?>)</i></td>
						<td style="text-align:center; vertical-align:top;"><i>(General Manager)</i></td>
						<td style="text-align:center; vertical-align:top;"><i>(Senior Accounting & Finance)</i></td>
						<td style="text-align:center; vertical-align:top;"><i>(Director of Corporate Planning & Business Management)</i></td>
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
		document.getElementById("payment").addEventListener("change", function() {
					var pilihan = this.value;
					var lainnyaInput = document.getElementById("lainnyaInput");

					if (pilihan === "1") {
							lainnyaInput.style.display = "block";
					} else {
							lainnyaInput.style.display = "none";
					}
			});


		document.getElementById("ongkir").addEventListener("change", function() {
        var pilih = this.value;
        var lainnyaOngkir = document.getElementById("lainnyaOngkir");

        if (pilih === "1") {
            lainnyaOngkir.style.display = "block";
        } else {
            lainnyaOngkir.style.display = "none";
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var tgl 				= $('#tgl').val();			
			var nama				= $('#nama option:selected').html();
			var nama_customer		= $('#nama_customer').val();
			var notifikasi			= $('#notifikasi').val();
			var detail_order		= $('#detail_order').val();
			var payment	= $('#payment').val();
			var lainnyaInput	= $('#lainnyaInput').val();
			var pajak				= $('#pajak').val();
			var cicilan	= $('#cicilan').val();
			var ongkir	= $('#ongkir').val();
			var lainnyaOngkir	= $('#lainnyaOngkir').val();

			let nama_barang			= [];
			let acuan_hrg			= [];
			let hrg_ditawarkan		= [];
			let approvall			= [];
			let trf_komisi			= [];
			let catatan				= [];
			let itung_isi			= $('#itung').val();
			let itung_isi1			= $('#itung1').val();

			for (let i=1; i<=itung_isi; i++) {
				if($( '#nama_barang_'+i).val() != ""){
					nama_barang[i]		= $('#nama_barang_'+i).val();
					acuan_hrg[i]		= $('#acuan_hrg_'+i).val();
					hrg_ditawarkan[i]	= $('#hrg_ditawarkan_'+i).val();
					approvall[i]		= $('#approvall_'+i).val();
				}				
			}			
			let itung		= nama_barang.length;

			for (let i=1; i<=itung_isi1; i++) {
				if($( '#catatan_'+i).val() != ""){
					trf_komisi[i]		= $('#trf_komisi_'+i).val();
					catatan[i]		= $('#catatan_'+i).val();
				}				
			}			
			let itung1		= catatan.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Approval Harga?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/approval',
                        dataType: 'JSON',
                        data: {
                        	tgl 			: tgl,
							nama 			: nama,
							nama_customer	: nama_customer,
							notifikasi		: notifikasi,
							detail_order	: detail_order,
							payment	: payment,
							lainnyaInput	: lainnyaInput,
							cicilan	: cicilan,
							ongkir	: ongkir,
							lainnyaOngkir	: lainnyaOngkir,
							pajak			: pajak,
							nama_barang		: nama_barang,
							acuan_hrg 		: acuan_hrg,
							hrg_ditawarkan	: hrg_ditawarkan,
							approvall		: approvall,
							trf_komisi		: trf_komisi,
							catatan			: catatan,
							itung 			: itung,
							itung1 			: itung1
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }				
            })
        })
		
		$(document).on('click', '.btn-denial', function() {
            const id_approval = $(this).attr("id-approval")
            Swal.fire({
				title: 'Tolak Pengajuan Approval Harga?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/approval/2/ttd_1',
                        dataType: 'JSON',
                        data: {
                            id_approval : id_approval,
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