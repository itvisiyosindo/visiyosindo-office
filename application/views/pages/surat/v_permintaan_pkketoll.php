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
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Pengajuan PKK E-Toll</font></h2>
        </div>
		<font color='#000000'>
		<div class="form-group">
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
		<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan klaim kas sebagai berikut :</font>
					</td>
				</tr>
				<tr>					
						
					</font>
					<td rowspan="10" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td width="2">&nbsp;:</td>
						<td colspan="6">&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>&nbsp;:</td>
						<td colspan="6">&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
						<!-- <td>:<input type="text" name="" id="" placeholder="Ketik Nama Divisi" required></td> -->
					</tr>
					<tr>
						<td>Kas</td>
						<td>&nbsp;:</td>
						<td><input  name="type" type="radio" id="kantor" value="kantor" style="vertical-align:middle; cursor: pointer;" checked></td>
						<td><label for="kantor">kantor</label><br></td>
						<td><input  name="type" type="radio" id="gudang" value="gudang"  style="vertical-align:middle; cursor: pointer;"></td>
						<td><label for="gudang">gudang</label></td>
						<td><input  name="type" type="radio" id="marketing" value="marketing" style="vertical-align:middle; cursor: pointer;"></td>
						<td><label for="marketing">marketing</label></td>
					</tr>
					<tr>
						<td>Keterangan </td>
						<td>&nbsp;:</td>
						<td colspan="6"><input type="text" name="keterangan_pengaju" id="keterangan_pengaju" placeholder=" &nbsp;Ketik Keterangan" required></td>
					</tr>
					<tr>
						<td  width="30%">Nomor Rekening Pembayaran </td>
					<tr>
						<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Nama Bank </td>
						<td>&nbsp;:</td>
						<td colspan="6"><input type="text" name="nama_bank" id="nama_bank" placeholder=" &nbsp;Ketik Nama Bank" required></td>
					</tr>
					<tr>
						<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Nomor Rekening </td>
						<td>&nbsp;:</td>
						<td colspan="6"><input type="text" name="no_rek" id="no_rek" placeholder=" &nbsp;Ketik Nomor Rekening" required></td>
					</tr>
					<tr>
						<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Atas Nama </td>
						<td>&nbsp;:</td>
						<td colspan="6"><input type="text" name="ats_nama" id="ats_nama" placeholder=" &nbsp;Ketik Nama" required></td>
					</tr>
					<tr>
						<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Lampiran Pengaju </td>
						<td>&nbsp;:</td>
						<td colspan="6"><input type="text" name="lampiran" id="lampiran" placeholder=" &nbsp;Pastekan link lampiran anda" required></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
			<br>
			
		</div>

<!-- TABEL BIAYA OPERASIONAL -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                        	<tr>
                        		<th colspan="5" bgcolor="#b7d5ac" colspan="4" style="text-align:center">Biaya Operasional</th>
                        	</tr>
                            <tr>
                            	<th style="text-align:center" bgcolor="#b7d5ac">No</th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Nomor Kartu </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="40%"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Nominal </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung = 1;
								for($x=1;$x<=12;$x++){
									$itung = $x;
							?>
								<tr>
									<td style="text-align:center"> <?= $itung ?> </td>
                                    <td>									
                                    	<input type="date" id="<?= 'tanggal_'.$x ?>" Style="width:100%; text-align:center"  required>
									</td>
									<td>
                                    	<input type="text" id="<?= 'nokartu_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'ket_detail_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="number" id="<?= 'nominal_'.$x ?>" Style="width:100%; text-align:right" required>
                                    </td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="5" style="text-align:center">TOTAL </th>                    
                            </tr>
                        </tfoot>
			</table>
			<br>
			<!-- <button type="button" class="btn btn-success" onclick="myFunction1()">Tambah Baris</button>
			 <button type="button" class="btn btn-primary" onclick="addRow('tbody2')">Tambah Baris</button> 
			<button type="button" class="btn btn-danger" onclick="myDeleteFunction1()">	Hapus Baris</button> -->

<!-- PENUTUP TABEL BIAYA OPERASIONAL -->
			<br><br>
<!-- TABEL BIAYA DINAS -->
<div class="form-group" id="company_select">
			<table id="myTable" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                        	<tr>
                        		<th colspan="5" bgcolor="#b7d5ac" style="text-align:center">Biaya Dinas</th>
                        	</tr>
                            <tr>
                            	<th style="text-align:center" bgcolor="#b7d5ac">No</th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="10%"> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Nomor Kartu </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="40%"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Nominal </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung1 = 1;
								for($x1=1;$x1<=12;$x1++){
									$itung1 = $x1;
							?>
								<tr>
									<td style="text-align:center"> <?= $itung1 ?> </td>
                                    <td>
                                    	<input type="date" id="<?= 'tanggal1_'.$x1 ?>" Style="width:100%; text-align:center"  required>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'nokartu1_'.$x1 ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'ket_detail1_'.$x1 ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="number" id="<?= 'nominal1_'.$x1 ?>" Style="width:100%; text-align:right" required>
                                    </td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung1" value="<?= $itung1 ?>">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="5" style="text-align:center">TOTAL </th>                    
                            </tr>
                        </tfoot>
			</table>
			<br>
			<!-- <button type="button" class="btn btn-success" onclick="myFunction()">Tambah Baris</button>
			<button type="button" class="btn btn-danger" onclick="myDeleteFunction()">Hapus Baris</button> -->
</div>
			
<script type="text/javascript">

</script>
<!-- PENUTUP TABEL BIAYA DINAS -->
<br><br>
<!-- TABEL BAGIAN FINANCE -->
			<table id="company" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                        	<tr>
                        		<th colspan="4" bgcolor="#b7d5ac" colspan="3" style="text-align:center">Catatan Finance</th>
                        	</tr>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="10%"> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Nomor Kartu </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="40%"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Nominal </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung2 = 1;
								for($x2=1;$x2<=12;$x2++){
									$itung2 = $x2;
							?>
								<tr>
                                    <td>
                                    	<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="<?= 'tanggal2_'.$x2 ?>" Style="width:100%; text-align:center"  disabled>	
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'nokartu2_'.$x2 ?>" Style="width:100%" placeholder="Diisi oleh finance" disabled>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'ket_detail2_'.$x2 ?>" Style="width:100%" placeholder="Diisi oleh finance" disabled>
                                    </td>
                                    <td>
                                    	<input type="number" id="<?= 'nominal2_'.$x2 ?>" Style="width:100%; text-align:right" placeholder="Diisi oleh finance" disabled>
                                    </td>
                                </tr>

							<?php } ?>
							<input type="hidden" id="itung2" value="<?= $itung2 ?>">
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="4" style="text-align:center">TOTAL </th>                    
                            </tr>
                        </tfoot>
                        <tfoot>
                            <tr>
                                <th colspan="4">
                                	<input type="text" name="lampiran_finance" id="lampiran_finance" placeholder=" &nbsp;Pastekan Link" disabled>
                                </th>                   
                            </tr>
                        </tfoot>
			</table>

<!-- PENUTUP TABEL BAGIAN FINANCE -->
<br><br>
<!-- TABEL PERHITUNGAN TOTAL KESELURUHAN -->
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <th colspan="2" bgcolor="#b7d5ac" style="text-align:center">TOTAL BIAYA OPERASIONAL</th>
                            <th bgcolor="#b7d5ac" style="text-align:center" width="25%"></th>
                        </thead>
                        <thead>
                            <th colspan="2" bgcolor="#b7d5ac" style="text-align:center">TOTAL BIAYA DINAS</th>
                            <th bgcolor="#b7d5ac" style="text-align:center" width="25%"></th>
                        </thead>
                        <thead>
                            <th colspan="2" bgcolor="#b7d5ac" style="text-align:center">TOTAL CATATAN FINANCE</th>
                            <th bgcolor="#b7d5ac" style="text-align:center" width="25%"></th>
                        </thead>
                        <thead>
                            <th colspan="2" bgcolor="#b7d5ac" style="text-align:center">GRAND TOTAL BIAYA PENGAJUAN KLAIM KAS</th>
                            <th bgcolor="#b7d5ac" style="text-align:center" width="25%"></th>
                        </thead>
			</table>
<!-- PENUTUP TABEL PERHITUNGAN TOTAL KESELURUHAN -->
<br>
<!-- TABEL KETERANGAN -->
<table id="tbl_2" border="0">
				<tr>
					<td width="7%">
					<td style="text-align:justify; text-justify:inter-word;">
						<font style="font-family:Times New Roman; font-size:15px;">
							Saya membuat laporan penggunaan kas dengan melampirkan Struk/Bon biaya terkait dan akan diberikan kepada bagian keuangan.
						</font>
					</td>
					<td width="7%">
                </tr>
</table>
<!-- PENUTUP TABEL KETERANGAN -->

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
									echo "<option ='".$row->id."".$row->nama."'>".$row->nama."</option>";
								}
								?>
							</select>
							<span> ,&nbsp; </span>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="tgl_pengajuan" name="tgl_pengajuan" Style="width:15%; text-align:left" placeholder="Tanggal Pengajuan" required> 
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
						<td style="text-align:center; vertical-align:top;"><i>Director of Corp Planning and Management Bussinees</i></td>
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
		  var cell5 = row.insertCell(4);

		  
		  //x1 = x1+1;
		  cell1.innerHTML = "<center>"+urut+"</center>";
		  // cell2.innerHTML = ""+ambil[1].innerHTML;
		  cell2.innerHTML = "<input type='date' id='tanggal1_"+urut+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='text' id='nokartu1_"+urut+"' Style='width:100%'>";
		  cell4.innerHTML = "<input type='text' id='ket_detail1_"+urut+"' Style='width:100%'>";
		  cell5.innerHTML = "<input type='number' id='nominal1_"+urut+"' Style='width:100%; text-align:right'>";
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
		  var cell5 = row.insertCell(4);
		  
		  // x = x+1;
		   cell1.innerHTML = "<center>"+urut1+"</center>";
		  // cell2.innerHTML = ""+ambil[1].innerHTML;
		  cell2.innerHTML = "<input type='date' id='tanggal_"+urut1+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='text' id='nokartu_"+urut1+"' Style='width:100%'>";
		  cell4.innerHTML = "<input type='text' id='ket_detail_"+urut1+"' Style='width:100%'>";
		  cell5.innerHTML = "<input type='number' id='nominal_"+urut1+"' Style='width:100%; text-align:right'>";
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
			let nokartu 		= [];
			let ket_detail		= [];
			let nominal			= [];
			let tanggal			= [];
			let nokartu1		= [];
			let ket_detail1		= [];
			let nominal1		= [];
			let tanggal1		= [];
			let nokartu2 		= [];
			let ket_detail2		= [];
			let nominal2		= [];
			let tanggal2		= [];					
			// let total 		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#ket_detail_'+i).val() != ""){
					tanggal[i]			= $('#tanggal_'+i).val();
					nokartu[i]			= $('#nokartu_'+i).val();
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
					nokartu1[i]			= $('#nokartu1_'+i).val();
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
					nokartu2[i]			= $('#nokartu2_'+i).val();
					ket_detail2[i]	= $('#ket_detail2_'+i).val();
					nominal2[i]		= $('#nominal2_'+i).val();
					// total[i]		= nominal[i]*qty[i];
					// nom[i] = $('#nominal_'+i).val();
				}				
			}			
			let itung2		= ket_detail2.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan PKK E-Toll?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/pkketoll',
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
							nokartu             : nokartu,
							ket_detail			: ket_detail,
							nominal 			: nominal,
							tanggal 			: tanggal,
						    nokartu1            : nokartu1,
							ket_detail1			: ket_detail1,
							nominal1 			: nominal1,
							tanggal1 			: tanggal1,
						    nokartu2            : nokartu2,	
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
				title: 'Tolak Pengajuan PKK E-Toll?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/pkketoll/2/ttd_1',
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