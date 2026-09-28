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
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Pengajuan Permintaan Dinas Karyawan</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan permintaan dinas sebagai berikut :</font>
					</td>
				</tr>
				<tr>					
						
					</font>
					<td rowspan="10" width="7%"></td>
					<tr>
						<td width="30%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Wilayah Dinas (Provinsi) </td>
						<td>:<input type="text" name="wilayah_dinas" id="wilayah_dinas" placeholder=" &nbsp;Ketik Wilayah Dinas Anda" required></td>
					</tr>
					<tr>								
						<td>Tanggal </td>
						<td>
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'>
								<span> :&nbsp;&nbsp; </span>
								<input type="text" id="mulai" Style="width:20%" placeholder='Mulai' required>
								&nbsp; - &nbsp;
								<input type="text" id="akhir" Style="width:20%" placeholder='Selesai' required>
							</div>
						</td>
					</tr>
					<tr>
						<td>Lama Dinas </td>
						<td>:&nbsp;&nbsp; -</td>
					</tr>
					<tr>
						<td>Tujuan Dinas </td>
						<td>:<input type="text" name="tujuan" id="tujuan" placeholder=" &nbsp;Ketik Tujuan Dinas Anda, misalkan menawarkan produk ke RS" required></td>
					</tr>
					<tr>
						<td>Transportasi </td>
						<td>:<input type="text" name="transportasi" id="transportasi" placeholder=" &nbsp;Ketik Transportasi Anda" required></td>
					</tr>
					<tr>
						<td>Jenis Kendaraan </td>
						<td>:<input type="text" name="jenis_kendaraan" id="jenis_kendaraan" placeholder=" &nbsp;Ketik Jenis Kendaraan Anda" required></td>
					</tr>
					<tr>
						<td>No. Polisi(Jika Kantor) </td>
						<td>:<input type="text" name="no_polisi" id="no_polisi" placeholder=" &nbsp;Ketik Nomor Polisi Anda" required></td>
					</tr>
					<tr>
						<td>Lampiran </td>
						<td>:<input type="text" name="lampiran" id="lampiran" placeholder=" &nbsp;Pastekan Link Lampiran Pendukung Anda" required></td>
					</tr>

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="10%"> HARI</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="15%"> TANGGAL </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="25%"> NAMA CUSTOMER </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="25%"> KETERANGAN</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="5%"> KOTA/KABUPATEN </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="20%"> PIC </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung = 1;
								for($x=1;$x<=15;$x++){
									$itung = $x;
							?>
								<tr>
                                    <td>
                                    	<input type="text" id="<?= 'hari_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td style="text-align:center">
										<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="<?= 'tgl_'.$x ?>" Style="width:100%; text-align:center"  required>										
									</td>
                                    <td>
                                    	<input type="text" id="<?= 'nama_customer_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'tujuan_dinas_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<select id="<?= 'kota_'.$x ?>" style="text-align:center; width:100%; border:0px; margin:0px;" required>
                                    		<option value=""></option>
                                    		<?php
                                    		foreach ($list_kota as $row) {
                                    			echo "<option value='"."".$row->nama."'>".$row->nama."</option>";
                                    		}
                                    		?>
                                    	</select>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'pic_'.$x ?>" Style="width:100%" required>
                                    </td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
			</table>
			<br>
			<button type="button" class="btn btn-success" onclick="myFunction1()">Tambah Baris</button>
			<!-- <button type="button" class="btn btn-primary" onclick="addRow('tbody2')">Tambah Baris</button> -->
			<button type="button" class="btn btn-danger" onclick="myDeleteFunction1()">	Hapus Baris</button>
			
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
						<td style="text-align:center; width:25.5%;" colspan="2"></td>
						<td style="text-align:center; width:49%;" colspan="3"> </td>
						<td style="text-align:center; width:25.5%;" colspan="2"><b>Dibuat Oleh,</b></td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
							$ttd2 		= $img_path."ttd_blank.png";
							$ttd3 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center; width:23%;"></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"> </td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"> </td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
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

// FUNGSI TAMBAH BARIS BARU
	var x = <?= $itung ?>;
	x = parseInt(x) + 2;
	function myFunction1() {
		var urut1 = x - 1;
		var table = document.getElementById("myTable1");
		var rowCount = table.rows.length;
		var row = table.insertRow(rowCount);
		x = x + 1;
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
		var cell4 = row.insertCell(3);
		var cell5 = row.insertCell(4);
		var cell6 = row.insertCell(5);

		cell1.innerHTML = "<input type='text' id='hari_" + urut1 + "' Style='width:100%'>";
		cell2.innerHTML = "<input type='date' id='tgl_" + urut1 + "' Style='width:100%'>";
		cell3.innerHTML = "<input type='text' id='nama_customer_" + urut1 + "' Style='width:100%'>";
		cell4.innerHTML = "<input type='text' id='tujuan_dinas_" + urut1 + "' Style='width:100%'>";
		cell5.innerHTML = "<input type='text' id='kota_" + urut1 + "' Style='width:100%'>";
		cell6.innerHTML = "<input type='text' id='pic_" + urut1 + "' Style='width:100%'>";
		document.getElementById('itung').value = urut1;
	}
	
		function myDeleteFunction1() {
			x = x-1;		
			document.getElementById("myTable1").deleteRow(x);			
		}

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var wilayah_dinas 			= $('#wilayah_dinas').val();			
			var lama_dinas				= $('#lama_dinas').val();
			var transportasi			= $('#transportasi').val();
			var jenis_kendaraan			= $('#jenis_kendaraan').val();
			var no_polisi				= $('#no_polisi').val();
			var lampiran				= $('#lampiran').val();
			var mulai					= $('#mulai').val();
			var akhir					= $('#akhir').val();
			var tujuan					= $('#tujuan').val();
			// var trf_pajak		= $('#trf_pajak').val();
			// var nml_pjk			= $('#nml_pjk').val();
			// var pembayar_pajak	= $('#pembayar_pajak').val();
			var kota_aju		= $('#kota_aju').val();
			const 	isi			= kota_aju.split("PengHubunG");
				kota_aju		= isi[1];
			var tgl_pengajuan	= $('#tgl_pengajuan').val();
			
			let itung_isi	= $('#itung').val();
			let nama_customer	= [];
			let hari			= [];
			let tgl				= [];
			let tujuan_dinas	= [];			
			let kota 			= [];
			let pic 			= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#hari_'+i).val() != ""){
					nama_customer[i]	= $('#nama_customer_'+i).val();
					hari[i]				= $('#hari_'+i).val();
					tgl[i]				= $('#tgl_'+i).val();
					tujuan_dinas[i]		= $('#tujuan_dinas_'+i).val();
					kota[i]				= $('#kota_'+i).val();
					pic[i]				= $('#pic_'+i).val();
					
					// nom[i] = $('#nominal_'+i).val();
				}				
			}	
			// var_dump($this->input->post('itung', TRUE));
			// die;		
			let itung		= nama_customer.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/pd_karyawan',
                        dataType: 'JSON',
                        data: {
                        	wilayah_dinas 	: wilayah_dinas,
                        	lama_dinas 		: lama_dinas,
                        	transportasi 	: transportasi,
                        	jenis_kendaraan : jenis_kendaraan,
                        	no_polisi 		: no_polisi,
							kota_aju 		: kota_aju,
							tgl_pengajuan 	: tgl_pengajuan,
							lampiran        : lampiran,
							mulai	        : mulai,
							akhir	        : akhir,
							tujuan	        : tujuan,

							nama_customer	: nama_customer,
							hari 			: hari,
							tgl 			: tgl,
							tujuan_dinas 	: tujuan_dinas,
							kota 			: kota,
							pic 			: pic,
							itung           : itung,
							csrf_token	: token
							
							// pembayar_pajak : pembayar_pajak
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
				title: 'Tolak Pengajuan Permintaan Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/pbok/2/ttd_1',
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