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
<div class="col-xl-12 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Form Serah Terima Barang </font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Pada hari ini, kami yang bertanda tangan dibawah ini:</font>
					</td>
				</tr>
				<tr>											
		</font>
					<td rowspan="8" width="7%"></td>
					<tr>
						<td>Nama </td>
						<td>
							<select id="id_pihak1" style="text-align:left; width:17%; border:0px; margin:0px;" required>
								<option value="">Pilih Nama Pihak Pertama</option>
								<option value="325">PT VISI YOSINDO MEDIKAL</option>
								<?php
								foreach ($list_cust as $row) {
									echo "<option value='".$row->id_customer."PengHubunG".$row->nama_customer."'>".$row->nama_customer."</option>";
								}
								?>
							</select>
							
						</td>
					</tr>
					
					<tr>
						<td>Disebut sebagai </td>
						<td><b>"PIHAK PERTAMA"<b></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td>Nama </td>
						<td>
							<select id="id_customer" style="text-align:left; width:17%; border:0px; margin:0px;" required>
								<option value="">Pilih Nama Pihak Kedua</option>
								<?php
								foreach ($list_cust as $row) {
									echo "<option value='".$row->id_customer."PengHubunG".$row->nama_customer."'>".$row->nama_customer."</option>";
								}
								?>
							</select>
							
						</td>
					</tr>
					<tr>
						<td>Disebut sebagai </td>
						<td><b>"PIHAK KEDUA"<b></td>
					</tr>
					
					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" width="5%"> No </th>
                                <th style="text-align:center"> Nama Barang </th>
                                <th style="text-align:center" width="15%"> Merk </th>
                                <th style="text-align:center" width="15%"> No Batch</th>
                                <th style="text-align:center" width="7%"> QTY</th>
                                <th style="text-align:center" width="15%"> SATUAN </th>
                                <th style="text-align:center" width="15%"> KET </th>
                            </tr>
							
                        </thead>
                        <tbody>
									<?php
										$itung = 1;
										for($x=1;$x<=35;$x++){
										$itung = $x;
									?>
												<tr>
                              <td><input type="number" id="<?= 'nomor_'.$x ?>" Style="width:100%; text-align:center" required></td>
															<td><input type="text" id="<?= 'nama_'.$x ?>" Style="width:100%" required></td>
                              <td><input type="text" id="<?= 'merk_'.$x ?>" Style="width:100%; text-align:center" required></td>
                              <td><input type="text" id="<?= 'nobatch_'.$x ?>" Style="width:100%; text-align:center" required></td>
                              <td><input type="number" id="<?= 'qty_'.$x ?>" Style="width:100%; text-align:center" required></td>
                              <td><input type="text" id="<?= 'satuan_'.$x ?>" Style="width:100%; text-align:center" required></td>
                              <td><input type="text" id="<?= 'ket_'.$x ?>" Style="width:100%; text-align:center" required></td>
                        </tr>
										<?php } ?>
										<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        
			</table>
			<br>
			
			<button type="button" class="btn btn-success" onclick="myFunction1()">Tambah Baris</button>
			<button type="button" class="btn btn-danger" onclick="myDeleteFunction1()">	Hapus Baris</button>



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
	/*var x = <?= $itung ?>;
		x = parseInt(x)+2;
		function myFunction1() {
		  var urut1 	= x - 1;
		  var table = document.getElementById("kt_table_1");
		  var row 	= table.insertRow(x);
		  x = x + 1;
		  var cell1 = row.insertCell(0);
		  var cell2 = row.insertCell(1);
		  var cell3 = row.insertCell(2);
		  var cell4 = row.insertCell(3);
		  var cell5 = row.insertCell(4);
		  var cell6 = row.insertCell(5);
		  var cell7 = row.insertCell(6);
		  
		  //x = x+1;
		  cell1.innerHTML = "<input type='number' id='nomor_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell2.innerHTML = "<input type='text' id='nama_"+urut1+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='text' id='merk_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell4.innerHTML = "<input type='text' id='nobatch_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell5.innerHTML = "<input type='number' id='qty_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell6.innerHTML = "<input type='text' id='satuan_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell7.innerHTML = "<input type='text' id='ket_"+urut1+"' Style='width:100%; text-align:center'>";
		}
		
		function myDeleteFunction1() {
			x = x-1;		
			document.getElementById("kt_table_1").deleteRow(x);			
		}*/
		
		let x; // jumlah baris, dihitung saat DOM siap

		// Hitung jumlah baris saat DOM sudah siap
		window.onload = function () {
			var table = document.getElementById("kt_table_1");
			x = table.rows.length; // jumlah baris aktual (termasuk header)
		};

		function myFunction1() {
			var table = document.getElementById("kt_table_1");
			var urut1 = x - 1; // untuk ID input, dikurangi 1 jika ada header

			var row = table.insertRow(x); // tambahkan di akhir
			x = x + 1;

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);
			var cell6 = row.insertCell(5);
			var cell7 = row.insertCell(6);

			cell1.innerHTML = "<input type='number' id='nomor_" + urut1 + "' style='width:100%; text-align:center'>";
			cell2.innerHTML = "<input type='text' id='nama_" + urut1 + "' style='width:100%'>";
			cell3.innerHTML = "<input type='text' id='merk_" + urut1 + "' style='width:100%; text-align:center'>";
			cell4.innerHTML = "<input type='text' id='nobatch_" + urut1 + "' style='width:100%; text-align:center'>";
			cell5.innerHTML = "<input type='number' id='qty_" + urut1 + "' style='width:100%; text-align:center'>";
			cell6.innerHTML = "<input type='text' id='satuan_" + urut1 + "' style='width:100%; text-align:center'>";
			cell7.innerHTML = "<input type='text' id='ket_" + urut1 + "' style='width:100%; text-align:center'>";
		}

		function myDeleteFunction1() {
			if (x > 1) { // pastikan tidak menghapus header
				x = x - 1;
				document.getElementById("kt_table_1").deleteRow(x);
			}
		}
	
	

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {		
			var kota_aju	= $('#drop_kota').val();
			const 	isi		= kota_aju.split("PengHubunG");
				kota_aju	= isi[1];
			var id_pihak1 	= $('#id_pihak1').val();
			var id_customer 	= $('#id_customer').val();
			var pengajuan	= $('#pengajuan').val();
			
			let itung_isi	= $('#itung').val();
			let nom			= [];
			let nomor		= [];
			let nam			= [];
			let nama		= [];
			let mer			= [];
			let merk		= [];
			let nob			= [];
			let nobatch		= [];
			let qt			= [];
			let qty		= [];
			let sat			= [];
			let satuan		= [];
			let ke			= [];
			let ket		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#nama_'+i).val() != ""){
					nom[i] = $('#nomor_'+i).val();
					nam[i] = $('#nama_'+i).val();
					mer[i] = $('#merk_'+i).val();		  
					nob[i] = $('#nobatch_'+i).val();		  
					qt[i] = $('#qty_'+i).val();		  
					sat[i] = $('#satuan_'+i).val();	  
					ke[i] = $('#ket_'+i).val();	  
				}				
			}			
			let itung		= nam.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Serah Terima barang?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'serah_terima_barang/add',
                        dataType: 'JSON',
                        data: {
							kota_aju 	: kota_aju,
							pengajuan	: pengajuan,
							id_pihak1	: id_pihak1,
							id_customer	: id_customer,
							itung	: itung,
							nomor	: nom,
							nama	: nam,
							merk	: mer,
							nobatch	: nob,
							qty	: qt,
							satuan	: sat,
							ket	: ke,
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