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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Pengajuan PKK</font></h2>
        </div>
		<font color='#000000'>
		<div class="form-group">
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			
			<!-- ===== SECTION 1: IDENTITAS PENGAJU ===== -->
			<div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:15px; border-bottom: 2px solid #b7d5ac; padding-bottom:10px;">
					📋 IDENTITAS PENGAJU
				</h5>
				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
					<tr>
						<td width="25%"><strong>Nama</strong></td>
						<td width="2">:</td>
						<td><?= $pengguna[0]->nama ?></td>
					</tr>
					<tr>
						<td><strong>Jabatan</strong></td>
						<td>:</td>
						<td><?= $pengguna[0]->jabatan ?></td>
					</tr>
					<tr>
						<td><strong>Tipe Kas</strong></td>
						<td>:</td>
						<td>
							<input name="type" type="radio" id="kantor" value="kantor" style="vertical-align:middle; cursor: pointer; margin-right: 10px;" checked>
							<label for="kantor" style="cursor: pointer; margin-right: 20px;">📍 Kantor</label>
							
							<input name="type" type="radio" id="gudang" value="gudang" style="vertical-align:middle; cursor: pointer; margin-right: 10px;">
							<label for="gudang" style="cursor: pointer; margin-right: 20px;">📍 Gudang</label>
							
							<input name="type" type="radio" id="marketing" value="marketing" style="vertical-align:middle; cursor: pointer; margin-right: 10px;">
							<label for="marketing" style="cursor: pointer;">📍 Marketing</label>
						</td>
					</tr>
					<tr>
						<td><strong>Keterangan</strong></td>
						<td>:</td>
						<td><input type="text" name="keterangan_pengaju" id="keterangan_pengaju" placeholder="Ketik keterangan pengajuan" required style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px;"></td>
					</tr>
				</table>
			</div>

			<!-- ===== SECTION 2: REKENING PEMBAYARAN ===== -->
			<div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:15px; border-bottom: 2px solid #b7d5ac; padding-bottom:10px;">
					🏦 REKENING PEMBAYARAN
				</h5>
				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
					<tr>
						<td width="25%"><strong>Nama Bank</strong></td>
						<td width="2">:</td>
						<td><input type="text" name="nama_bank" id="nama_bank" placeholder="Ketik nama bank" required style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px;"></td>
					</tr>
					<tr>
						<td><strong>Nomor Rekening</strong></td>
						<td>:</td>
						<td><input type="text" name="no_rek" id="no_rek" placeholder="Ketik nomor rekening" required style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px;"></td>
					</tr>
					<tr>
						<td><strong>Atas Nama</strong></td>
						<td>:</td>
						<td><input type="text" name="ats_nama" id="ats_nama" placeholder="Ketik nama pemilik rekening" required style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px;"></td>
					</tr>
					<tr>
						<td><strong>Lampiran Pengaju</strong></td>
						<td>:</td>
						<td><input type="text" name="lampiran" id="lampiran" placeholder="Pastekan link lampiran (opsional)" style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px;"></td>
					</tr>
				</table>
			</div>

			<!-- ===== SECTION 3: MATA UANG ===== -->
			<div style="background-color: #e8f5e9; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #4CAF50;">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:15px;">
					💱 PILIH MATA UANG TRANSAKSI
				</h5>
				<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
					<tr>
						<td width="25%"><strong>Mata Uang</strong></td>
						<td width="2">:</td>
						<td>
							<select class="myselect" name="mata_uang" id="mata_uang" required style="padding:10px; border: 2px solid #4CAF50; border-radius: 4px; background-color: #ffffff; font-weight: bold;">
								<option value="">-- Pilih Mata Uang --</option>
								<option value="1">🟧 Rupiah (Rp)</option>
								<option value="2">🟩 Dollar ($)</option>
								<option value="3">🟨 Ringgit (RM)</option>
								<option value="4">🟦 SGD ($)</option>
								<option value="5">🟧 Rupee (₹)</option>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<!-- ===== SECTION 4: DETAIL BIAYA ===== -->
			<div style="margin-top: 30px;">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:15px; border-bottom: 2px solid #b7d5ac; padding-bottom:10px;">
					📊 DETAIL BIAYA OPERASIONAL
				</h5>
				<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
						<tr>
							<th style="text-align:center" bgcolor="#b7d5ac" width="5%">No</th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="15%">Tanggal</th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="55%">Keterangan</th>
							<th style="text-align:center" bgcolor="#b7d5ac" width="25%">Nominal</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$itung = 1;
							for($x=1;$x<=12;$x++){
								$itung = $x;
						?>
							<tr>
								<td style="text-align:center"><?= $itung ?></td>
								<td><input type="date" id="tanggal_<?= $x ?>" style="width:100%;" required></td>
								<td><input type="text" id="ket_detail_<?= $x ?>" style="width:100%;" required></td>
								<td><input type="number" id="nominal_<?= $x ?>" style="width:100%; text-align:right;" required></td>
							</tr>
						<?php } ?>
						<input type="hidden" id="itung" value="<?= $itung ?>">
					</tbody>
					<tfoot>
						<tr>
							<th colspan="4" bgcolor="#b7d5ac" style="text-align:center">TOTAL BIAYA OPERASIONAL</th>
						</tr>
					</tfoot>
				</table>
				<div style="margin: 15px 0;">
					<button type="button" class="btn btn-sm btn-success" onclick="myFunction1()"><i class="fas fa-plus"></i> Tambah Baris</button>
					<button type="button" class="btn btn-sm btn-danger" onclick="myDeleteFunction1()"><i class="fas fa-trash"></i> Hapus Baris</button>
				</div>
			</div>

			<!-- ===== SECTION 5: BIAYA DINAS ===== -->
			<div style="margin-top: 30px;" id="company_select">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:15px; border-bottom: 2px solid #ffc107; padding-bottom:10px;">
					📊 DETAIL BIAYA DINAS
				</h5>
				<table id="myTable" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
						<tr>
							<th style="text-align:center" bgcolor="#ffc107" width="5%">No</th>
							<th style="text-align:center" bgcolor="#ffc107" width="15%">Tanggal</th>
							<th style="text-align:center" bgcolor="#ffc107" width="50%">Keterangan</th>
							<th style="text-align:center" bgcolor="#ffc107" width="30%">Nominal</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$itung1 = 1;
							for($x1=1;$x1<=12;$x1++){
								$itung1 = $x1;
						?>
							<tr>
								<td style="text-align:center"><?= $itung1 ?></td>
								<td><input type="date" id="tanggal1_<?= $x1 ?>" style="width:100%;" required></td>
								<td><input type="text" id="ket_detail1_<?= $x1 ?>" style="width:100%;" required></td>
								<td><input type="number" id="nominal1_<?= $x1 ?>" style="width:100%; text-align:right;" required></td>
							</tr>
						<?php } ?>
						<input type="hidden" id="itung1" value="<?= $itung1 ?>">
					</tbody>
					<tfoot>
						<tr>
							<th colspan="4" bgcolor="#ffc107" style="text-align:center">TOTAL BIAYA DINAS</th>
						</tr>
					</tfoot>
				</table>
				<div style="margin: 15px 0;">
					<button type="button" class="btn btn-sm btn-success" onclick="myFunction()"><i class="fas fa-plus"></i> Tambah Baris</button>
					<button type="button" class="btn btn-sm btn-danger" onclick="myDeleteFunction()"><i class="fas fa-trash"></i> Hapus Baris</button>
				</div>
			</div>

			<!-- ===== SECTION 6: CATATAN FINANCE ===== -->
			<div style="margin-top: 30px;">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:15px; border-bottom: 2px solid #9c27b0; padding-bottom:10px;">
					💼 CATATAN FINANCE (Diisi oleh Bagian Keuangan)
				</h5>
				<table id="company" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
					<thead>
						<tr>
							<th style="text-align:center" bgcolor="#9c27b0" color="white" width="15%">Tanggal</th>
							<th style="text-align:center" bgcolor="#9c27b0" color="white" width="50%">Keterangan</th>
							<th style="text-align:center" bgcolor="#9c27b0" color="white" width="35%">Nominal</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$itung2 = 1;
							for($x2=1;$x2<=12;$x2++){
								$itung2 = $x2;
						?>
							<tr>
								<td><input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tanggal2_<?= $x2 ?>" style="width:100%; text-align:center;" disabled></td>
								<td><input type="text" id="ket_detail2_<?= $x2 ?>" style="width:100%;" placeholder="Diisi oleh finance" disabled></td>
								<td><input type="number" id="nominal2_<?= $x2 ?>" style="width:100%; text-align:right;" placeholder="Diisi oleh finance" disabled></td>
							</tr>
						<?php } ?>
						<input type="hidden" id="itung2" value="<?= $itung2 ?>">
					</tbody>
					<tfoot>
						<tr>
							<th colspan="3" bgcolor="#9c27b0" style="text-align:center">TOTAL CATATAN FINANCE</th>
						</tr>
					</tfoot>
				</table>
				<div style="margin-top: 15px;">
					<strong style="font-family:Times New Roman;">Lampiran Link Finance:</strong>
					<input type="text" name="lampiran_finance" id="lampiran_finance" placeholder="Pastekan link lampiran (diisi oleh finance)" disabled style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px; background-color: #f5f5f5;">
				</div>
			</div>

			<!-- ===== SECTION 7: RINGKASAN TOTAL ===== -->
			<div style="margin-top: 30px; background-color: #fff3cd; padding: 20px; border-radius: 5px; border-left: 5px solid #ff9800;">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:15px;">
					📈 RINGKASAN TOTAL
				</h5>
				<table style="font-family:Times New Roman; font-size:15px; width:100%; border-collapse: collapse;">
					<tr style="border-bottom: 2px solid #ff9800;">
						<td style="padding: 10px; font-weight: bold;">Total Biaya Operasional</td>
						<td style="text-align: right; padding: 10px; font-weight: bold;">-</td>
					</tr>
					<tr style="border-bottom: 2px solid #ffc107;">
						<td style="padding: 10px; font-weight: bold;">Total Biaya Dinas</td>
						<td style="text-align: right; padding: 10px; font-weight: bold;">-</td>
					</tr>
					<tr style="border-bottom: 2px solid #9c27b0;">
						<td style="padding: 10px; font-weight: bold;">Total Catatan Finance</td>
						<td style="text-align: right; padding: 10px; font-weight: bold;">-</td>
					</tr>
					<tr style="background-color: #d4af37; border-top: 3px solid #d4af37;">
						<td style="padding: 15px; font-weight: bold; font-size: 16px;">GRAND TOTAL</td>
						<td style="text-align: right; padding: 15px; font-weight: bold; font-size: 16px;">-</td>
					</tr>
				</table>
			</div>

			<!-- ===== SECTION 8: PERNYATAAN ===== -->
			<div style="margin-top: 30px; background-color: #f0f0f0; padding: 20px; border-radius: 5px;">
				<p style="font-family:Times New Roman; font-size:14px; text-align:justify; line-height: 1.6;">
					✓ Saya membuat laporan penggunaan kas dengan melampirkan Struk/Bon biaya terkait dan akan diberikan kepada bagian keuangan.
				</p>
			</div>

			<!-- ===== SECTION 9: INFORMASI PENGAJUAN ===== -->
			<div style="margin-top: 30px;">
				<table style="font-family:Times New Roman; font-size:15px; width:100%;">
					<tr>
						<td width="40%">
							<strong>Pilih Kota Pengajuan:</strong>
							<select id="kota_aju" name="kota_aju" style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;" required>
								<option value="">-- Pilih Kota --</option>
								<?php
									foreach ($list_kota as $row) {
										echo "<option value='".$row->id."".$row->nama."'>".$row->nama."</option>";
									}
								?>
							</select>
						</td>
						<td width="5%"></td>
						<td width="55%">
							<strong>Tanggal Pengajuan:</strong>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="tgl_pengajuan" name="tgl_pengajuan" style="width:100%; padding:8px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;" placeholder="Pilih tanggal pengajuan" required>
						</td>
					</tr>
				</table>
			</div>

			<!-- ===== SECTION 10: TANDA TANGAN ===== -->
			<div style="margin-top: 40px;">
				<h5 style="font-family:Times New Roman; font-weight:bold; margin-bottom:30px; border-bottom: 2px solid #b7d5ac; padding-bottom:10px;">
					✍️ TANDA TANGAN & PERSETUJUAN
				</h5>
				<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:14px;">
					<tbody>
						<tr style="height: 18px;">
							<td style="text-align:center; width:33%"><strong>Diajukan Oleh</strong></td>
							<td style="width:4%"></td>
							<td style="text-align:center; width:33%"><strong>Diverifikasi Oleh</strong></td>
							<td style="width:4%"></td>
							<td style="text-align:center; width:26%"><strong>Disetujui Oleh</strong></td>
						</tr>
						<tr style="height:80px;">
							<?php
								$img_path 	= "uploads/file_karyawan/ttd/";
								$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
								$ttd1 		= $img_path."ttd_blank.png";
								$ttd2 		= $img_path."ttd_blank.png";
								$ttd3 		= $img_path."ttd_blank.png";
							?>
							<td style="text-align:center; vertical-align:bottom; border-top: 1px solid #000;">
								<?php echo'<img src="'.$ttdaju.'" height="60" style="max-width:100%;">';?>
							</td>
							<td></td>
							<td style="text-align:center; vertical-align:bottom; border-top: 1px solid #000;">
								<?php echo'<img src="'.$ttd1.'" height="60" style="max-width:100%;">';?>
							</td>
							<td></td>
							<td style="text-align:center; vertical-align:bottom; border-top: 1px solid #000;">
								<?php echo'<img src="'.$ttd2.'" height="60" style="max-width:100%;">';?>
							</td>
						</tr>
						<tr style="height: 5px;"></tr>
						<tr>
							<td style="text-align:center; font-size:12px;"><strong><?= $pengguna[0]->short_name ?></strong></td>
							<td></td>
							<td style="text-align:center; font-size:12px;"><strong>Dirangga Madali</strong></td>
							<td></td>
							<td style="text-align:center; font-size:12px;"><strong>Yolanda Pratiwi</strong></td>
						</tr>
						<tr>
							<td style="text-align:center; font-size:11px; font-style:italic;"><?= $pengguna[0]->jabatan ?></td>
							<td></td>
							<td style="text-align:center; font-size:11px; font-style:italic;">Senior Accounting and Finance</td>
							<td></td>
							<td style="text-align:center; font-size:11px; font-style:italic;">Pimpinan Umum</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- ===== TOMBOL ACTION ===== -->
			<div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd;">
				<button type="button" class="btn btn-lg btn-success btn-ajukan float-right" style="margin-left:15px; padding: 12px 30px;">
					<i class="fas fa-paper-plane"></i> AJUKAN PERMINTAAN PKK
				</button>
				<button type="button" onclick="goBack()" class="btn btn-lg btn-secondary float-left" style="padding: 12px 30px;">
					<i class="fas fa-arrow-left"></i> KEMBALI
				</button>
			</div>
			<br><br>
			<?= form_close(); ?>
		</div>
		
    </div>	
</div>
<script>
	// FUNGSI TAMBAH BARIS BARU
	var x1 = <?= $itung1 ?>;
	x1 = parseInt(x1)+2;

		function myFunction() {
			
		  var urut 	= x1 - 1;
		  var table = document.getElementById("myTable");
		  // var ambil	= document.getElementById("myTable").rows[10].cells;
		  var row 	= table.insertRow(x1);
		  x1 = x1 + 1;
		  var cell1 = row.insertCell(0);
		  var cell2 = row.insertCell(1);
		  var cell3 = row.insertCell(2);
		  var cell4 = row.insertCell(3);

		  
		  //x1 = x1+1;
		  cell1.innerHTML = "<center>"+urut+"</center>";
		  // cell2.innerHTML = ""+ambil[1].innerHTML;
		  cell2.innerHTML = "<input type='date' id='tanggal1_"+urut+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='text' id='ket_detail1_"+urut+"' Style='width:100%'>";
		  cell4.innerHTML = "<input type='number' id='nominal1_"+urut+"' Style='width:100%; text-align:right'>";
		  document.getElementById('itung1').value = urut;
		}
		
		function myDeleteFunction() {
			x1 = x1-1;		
			document.getElementById("myTable").deleteRow(x1);			
		}

		var x = <?= $itung ?>;
		x = parseInt(x)+2;
		function myFunction1() {
		  var urut1 	= x - 1;
		  var table = document.getElementById("myTable1");
		  // var ambil	= document.getElementById("myTable").rows[10].cells;
		  var row 	= table.insertRow(x);
		  x = x + 1;
		  var cell1 = row.insertCell(0);
		  var cell2 = row.insertCell(1);
		  var cell3 = row.insertCell(2);
		  var cell4 = row.insertCell(3);
		  
		  // x = x+1;
		   cell1.innerHTML = "<center>"+urut1+"</center>";
		  // cell2.innerHTML = ""+ambil[1].innerHTML;
		  cell2.innerHTML = "<input type='date' id='tanggal_"+urut1+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='text' id='ket_detail_"+urut1+"' Style='width:100%'>";
		  cell4.innerHTML = "<input type='number' id='nominal_"+urut1+"' Style='width:100%; text-align:right'>";
		  document.getElementById('itung').value = urut1;
		}
		
		function myDeleteFunction1() {
			x = x-1;		
			document.getElementById("myTable1").deleteRow(x);			
		}
//FUNGSI BARU COBA
// 	function addRow(tableID) {
//     var table = document.getElementById(tableID);
//     var rowCount = table.rows.length;
//     var row = table.insertRow(rowCount);
//     var colCount = table.rows[1].cells.length;
//     for(var i=0; i<colCount; i++) {
//         var newcell1 = row.insertCell(i);
//         var newcell2 = row.insertCell(i);
//         var newcell3 = row.insertCell(i);
//         newcell1.innerHTML = table.rows[0].cells[i].innerHTML;
//         newcell2.innerHTML = table.rows[1].cells[i].innerHTML;
//         newcell3.innerHTML = table.rows[2].cells[i].innerHTML;
//         var child = newcell.children;
//         for(var i2=1; i2<child.length; i2++) {
//             var test = newcell.children[i2].tagName;
//             switch(test) {
//                 case "INPUT":
//                     if(newcell.children[i2].type=='text'){
//                         newcell1.children[i2].value = "<td hight='500%'> <input type='text' data-plugin-datepicker data-plugin-options='{'orientation':'bottom', 'format':'dd-mm-yyyy'}' id='<?= 'tanggal1_'.$x1 ?>' Style='width:100%; text-align:center'> </input> </td>";
//                         newcell2.children[i2].value = "<td hight='500%'> <input type='text' id='<?= 'ket_detail_'.$x ?>' Style='width:100%'> </td>";
//                         newcell3.children[i2].value = "<td hight='500%'> <input type='number' id='<?= 'nominal_'.$x ?>' Style='width:100%; text-align:right'> </td>";
//                         // newcell.children[i2].checked = false;
//                     }else{
//                         newcell1.children[i2].value = "<td hight='500%'> <input type='text' data-plugin-datepicker data-plugin-options='{'orientation':'bottom', 'format':'dd-mm-yyyy'}' id='<?= 'tanggal1_'.$x1 ?>' Style='width:100%; text-align:center'> </input> </td>";
//                         newcell2.children[i2].value = "<td hight='500%'> <input type='text' id='<?= 'ket_detail_'.$x ?>' Style='width:100%'> </td>";
//                         newcell3.children[i2].value = "<td hight='500%'> <input type='number' id='<?= 'nominal_'.$x ?>' Style='width:100%; text-align:right'> </td>";
//                     }
//                 break;
//                 case "SELECT":
//                     	newcell1.children[i2].value = "<td hight='500%'> <input type='text' data-plugin-datepicker data-plugin-options='{'orientation':'bottom', 'format':'dd-mm-yyyy'}' id='<?= 'tanggal1_'.$x1 ?>' Style='width:100%; text-align:center'> </input> </td>";
//                         newcell2.children[i2].value = "<td hight='500%'> <input type='text' id='<?= 'ket_detail_'.$x ?>' Style='width:100%'> </td>";
//                         newcell3.children[i2].value = "<td hight='500%'> <input type='number' id='<?= 'nominal_'.$x ?>' Style='width:100%; text-align:right'> </td>";
//                 break;
//                 default:
//                 break;
//             }
//         }
//     }
// }
//PENUTUP FUNGSI BARU COBA

	// PENUTP FUNGSI TAMBAH BUTTON
    document.addEventListener('DOMContentLoaded', function() {

    	$(document).ready(function () {
    		$("input[name=type]").change(function(){

    			if($("#kantor").is(':checked')){
    				$("#company_select").show();
    			}else if($("#marketing").is(':checked')){
    				$("#company_select").show();
    			}else{
    				$("#company_select").hide();
    			}
    		});
    	});
        
		$(document).on('click', '.btn-ajukan', function() {
			var nama_bank 			= $('#nama_bank').val();			
			var no_rek				= $('#no_rek').val();
			var ats_nama			= $('#ats_nama').val();
			var lampiran			= $('#lampiran').val();
			var lampiran_finance	= $('#lampiran_finance').val();
			var keterangan_pengaju	= $('#keterangan_pengaju').val();
			var type				= "";
    		if($("#kantor").is(':checked')){
    				type = "kantor";
    			}else if($("#marketing").is(':checked')){
    				type = "marketing";
    			}else{
    				type = "gudang";    			
    			}
			

			// var trf_pajak		= $('#trf_pajak').val();
			// var nml_pjk			= $('#nml_pjk').val();
			// var pembayar_pajak	= $('#pembayar_pajak').val();
			var kota_aju		= $('#kota_aju').val();
			// const 	isi			= kota_aju.split("PengHubunG");
			// 	kota_aju		= isi[1];
			var tgl_pengajuan	= $('#tgl_pengajuan').val();
			
			let itung_isi		= $('#itung').val();
			let itung_isi1		= $('#itung1').val();
			let itung_isi2		= $('#itung2').val();
			let ket_detail		= [];
			let nominal			= [];
			let tanggal			= [];
			let ket_detail1		= [];
			let nominal1		= [];
			let tanggal1		= [];
			let ket_detail2		= [];
			let nominal2		= [];
			let tanggal2		= [];					
			// let total 		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#ket_detail_'+i).val() != ""){
					tanggal[i]			= $('#tanggal_'+i).val();
					ket_detail[i]	= $('#ket_detail_'+i).val();
					nominal[i]		= $('#nominal_'+i).val();
					// total[i]		= nominal[i]*qty[i];
					// nom[i] = $('#nominal_'+i).val();
				}				
			}			
			let itung		= ket_detail.length;

			for (let i=1; i<=itung_isi1; i++) {
				if($( '#ket_detail1_'+i).val() != ""){
					tanggal1[i]			= $('#tanggal1_'+i).val();
					ket_detail1[i]	= $('#ket_detail1_'+i).val();
					nominal1[i]		= $('#nominal1_'+i).val();
					// total[i]		= nominal[i]*qty[i];
					// nom[i] = $('#nominal_'+i).val();
				}				
			}			
			let itung1		= ket_detail1.length;

			for (let i=1; i<=itung_isi2; i++) {
				if($( '#ket_detail2_'+i).val() != ""){
					tanggal2[i]			= $('#tanggal2_'+i).val();
					ket_detail2[i]	= $('#ket_detail2_'+i).val();
					nominal2[i]		= $('#nominal2_'+i).val();
					// total[i]		= nominal[i]*qty[i];
					// nom[i] = $('#nominal_'+i).val();
				}				
			}			
			let itung2		= ket_detail2.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan PKK?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/pkk',
                        dataType: 'JSON',
                        data: {
                        	nama_bank 			: nama_bank,
							no_rek 				: no_rek,
							ats_nama			: ats_nama,
							lampiran			: lampiran,
							lampiran_finance	: lampiran_finance,
							ats_nama			: ats_nama,
							tgl_pengajuan 		: tgl_pengajuan,
							kota_aju			: kota_aju,
							keterangan_pengaju	: keterangan_pengaju,
							type 				: type,
							itung 				: itung,
							itung1 				: itung1,
							itung2 				: itung2,
							// trf_pajak		: trf_pajak,
							// nml_pjk			: nml_pjk,
							// pembayar_pajak	: pembayar_pajak,
							ket_detail			: ket_detail,
							nominal 			: nominal,
							tanggal 			: tanggal,
							ket_detail1			: ket_detail1,
							nominal1 			: nominal1,
							tanggal1 			: tanggal1,
							ket_detail2			: ket_detail2,
							nominal2 			: nominal2,
							tanggal2 			: tanggal2
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
            const id_pkk = $(this).attr("id-pkk")
            Swal.fire({
				title: 'Tolak Pengajuan PKK?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/pkk/2/ttd_1',
                        dataType: 'JSON',
                        data: {
                            id_pkk : id_pkk,
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