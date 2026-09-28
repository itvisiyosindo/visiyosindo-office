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
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Pengajuan Biaya</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan biaya perjalanan dinas :</font>
					</td>
				</tr>
				<tr>					
						
					</font>
					<td rowspan="9" width="7%"></td>
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
						<td>Lampiran </td>
						<td>:&nbsp;&nbsp;<input id='lampiran' type="text" placeholder='Klik Untuk Memasukkan Link Lampiran' required></td></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                        	<tr>
                        	</tr>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung = 1;
								for($x1=1;$x1<=26;$x1++){
									$itung = $x1;
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
                                <th bgcolor="#b7d5ac" colspan="4" style="text-align:center">TOTAL </th>                    
                            </tr>
                        </tfoot>
			</table>
			<br>
			<button type="button" class="btn btn-success" onclick="myFunction()">Tambah Baris</button>
			<button type="button" class="btn btn-danger" onclick="myDeleteFunction()">Hapus Baris</button>
			<br>


			<table id="tbl_2" border="0">
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
			
            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
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
						<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dirangga Madali<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Yolanda Pratiwi<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Senior Accounting and Finance</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Pimpinan Umum</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corp Planning & Management Bussinees</i></td>
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
	var x1 = <?= $itung ?>;
	x1 = parseInt(x1)+2;

		function myFunction() {
			
		  var urut 	= x1 - 1;
		  var table = document.getElementById("kt_table_1");
		  // var ambil	= document.getElementById("myTable").rows[10].cells;
		  var row 	= table.insertRow(x1);
		  x1 = x1 + 1;
		  var cell1 = row.insertCell(0);
		  var cell2 = row.insertCell(1);
		  var cell3 = row.insertCell(2);
		  //var cell4 = row.insertCell(3);

		  
		  //x1 = x1+1;
		  //cell1.innerHTML = "<center>"+urut+"</center>";
		  // cell2.innerHTML = ""+ambil[1].innerHTML;
		  
		  cell1.innerHTML = "<input type='date' id='tgl1_"+urut+"' Style='width:100%'>";
			cell2.innerHTML = "<input type='text' id='keterangan_"+urut+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='number' id='nominal_"+urut+"' Style='width:100%; text-align:right'>";
		  document.getElementById('itung').value = urut;
		}
		
		function myDeleteFunction() {
			x1 = x1-1;		
			document.getElementById("kt_table_1").deleteRow(x1);			
		}


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
			var lampiran	= $('#lampiran').val();
			//var tes_tgl		= $('#tgl1').val();
			
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
				title: 'Ajukan Pembiayaan Biaya Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/pb',
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
							lampiran: lampiran,
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