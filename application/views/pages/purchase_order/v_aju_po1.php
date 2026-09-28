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
<div class="col-xl-12 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Form Purchase Order</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan Purchase Order :</font>
					</td>
				</tr>
				<tr>											
		</font>
					<td rowspan="5" width="7%"></td>
					<tr>
						<td width="10%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td>Lampiran Warehouse</td>
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
                                <th style="text-align:center" width="3%"> No </th>
                                <th style="text-align:center"> Nama Barang/Jasa </th>
                                <th style="text-align:center" width="11%"> Kode Barang/Jasa </th>
                                <th colspan="3" style="text-align:center"  width="21%"> Stock Keluar </th>
                                <th style="text-align:center" width="8%"> Stock Gudang Keluruhan Saat Ini </th>
                                <th style="text-align:center" width="5%"> Stock yang Sudah di PO </th>
                                <th style="text-align:center" width="5%"> Kebutuhan PO Pending </th>
                                <th style="text-align:center" bgcolor="#ffc000" width="5%"> Rencana PO Baru (Dari Gudang) </th>
                                <th style="text-align:center" bgcolor="#ffc000" width="7%"> Supplier </th>
                                <th style="text-align:center" bgcolor="#ffff01" width="10%"> Detail </th>
                            </tr>
							<tr>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> <input id='bulan_1' type="text" Style="text-align:center" placeholder='Bulan' required> </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> <input id='bulan_2' type="text" Style="text-align:center" placeholder='Bulan' required> </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> <input id='bulan_3' type="text" Style="text-align:center" placeholder='Bulan' required> </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>
                                <th style="text-align:center" bgcolor="#b7d5ac">  </th>

							</tr>
                        </thead>
                        <tbody>
									<?php
										$itung = 1;
										for($x=1;$x<=15;$x++){
										$itung = $x;
									?>
								<tr>
                                    <td style="text-align:center">
										<?= $x ?>
									</td>
																		<td><input type="text" id="<?= 'nama_'.$x ?>" Style="width:100%" required></td>
																		<td><input type="text" id="<?= 'kodebarang_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="number" id="<?= 'bulan1_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <td><input type="number" id="<?= 'bulan2_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <td><input type="number" id="<?= 'bulan3_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <td><input type="number" id="<?= 'gudang_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <td><input type="number" id="<?= 'stokpo_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <td><input type="number" id="<?= 'kebutuhan_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <td><input type="number" id="<?= 'rencana_'.$x ?>" Style="width:100%; text-align:center" required></td>
                                    <!--<td><input type="text" id="<?= 'supplier_'.$x ?>" Style="width:100%" required></td>-->
																		<td>
                                    	<select class="select-transaction input-group-sm form-control" id="<?= 'supplier_'.$x ?>" >
																				<option value ="">Pilih</option>
																				<?php
																				foreach ($list_supplier as $row) {
																					echo '<option value="' . $row->nama_pemasok . '">' . $row->nama_pemasok . '</option>';
																				} 
																				?>
																			</select>
                                    </td>
                                    <td><input type="text" id="<?= 'detail_'.$x ?>" Style="width:100%" required></td>
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
	var x = <?= $itung ?>;
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
		  var cell8 = row.insertCell(7);
		  var cell9 = row.insertCell(8);
		  var cell10 = row.insertCell(9);
		  var cell11 = row.insertCell(10);
		  var cell12 = row.insertCell(11);
		  
		  // x = x+1;
		  cell1.innerHTML = "<center>"+urut1+"</center>";
		  cell2.innerHTML = "<input type='text' id='nama_"+urut1+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='text' id='kodebarang_"+urut1+"' Style='width:100%'>";
		  cell4.innerHTML = "<input type='number' id='bulan1_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell5.innerHTML = "<input type='number' id='bulan2_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell6.innerHTML = "<input type='number' id='bulan3_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell7.innerHTML = "<input type='number' id='gudang_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell8.innerHTML = "<input type='number' id='stokpo_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell9.innerHTML = "<input type='number' id='kebutuhan_"+urut1+"' Style='width:100%; text-align:center'>";
		  cell10.innerHTML = "<input type='number' id='rencana_"+urut1+"' Style='width:100%; text-align:center'>";
		  //cell11.innerHTML = "<input type='text' id='supplier_"+urut1+"' Style='width:100%'>";
			cell11.innerHTML = `<select class="select-transaction input-group-sm form-control" id="supplier_${urut1}" style="width:100%">
					<option value="">Pilih</option>
					<?php foreach ($list_supplier as $row) { ?>
							<option value="<?= $row->nama_pemasok ?>"><?= $row->nama_pemasok ?></option>
					<?php } ?>
			</select>`;
		  cell12.innerHTML = "<input type='text' id='detail_"+urut1+"' Style='width:100%'>";
		  document.getElementById('itung').value = urut1;
		}
		
		function myDeleteFunction1() {
			x = x-1;		
			document.getElementById("kt_table_1").deleteRow(x);			
		}
	

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {		
			var kota_aju	= $('#drop_kota').val();
			const 	isi		= kota_aju.split("PengHubunG");
				kota_aju	= isi[1];
			var bulan_1 	= $('#bulan_1').val();
			var bulan_2 	= $('#bulan_2').val();
			var bulan_3		= $('#bulan_3').val();
			var lampiran	= $('#lampiran').val();
			var pengajuan	= $('#pengajuan').val();
			
			let itung_isi	= $('#itung').val();
			let nam			= [];
			let nama		= [];
			let kod			= [];
			let kodebarang		= [];
			let bu1			= [];
			let bulan1		= [];
			let bu2			= [];
			let bulan2		= [];
			let bu3			= [];
			let bulan3		= [];
			let gud			= [];
			let gudang		= [];
			let sto			= [];
			let stokpo		= [];
			let keb			= [];
			let kebutuhan	= [];
			let ren			= [];
			let rencana		= [];
			let sup			= [];
			let supplier	= [];
			let det			= [];
			let detail	= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#nama_'+i).val() != ""){
					nam[i] = $('#nama_'+i).val();
					kod[i] = $('#kodebarang_'+i).val();
					bu1[i] = $('#bulan1_'+i).val();		  
					bu2[i] = $('#bulan2_'+i).val();		  
					bu3[i] = $('#bulan3_'+i).val();		  
					gud[i] = $('#gudang_'+i).val();	  
					sto[i] = $('#stokpo_'+i).val();	  
					keb[i] = $('#kebutuhan_'+i).val();	  
					ren[i] = $('#rencana_'+i).val();  
					sup[i] = $('#supplier_'+i).val();  
					det[i] = $('#detail_'+i).val();
				}				
			}			
			let itung		= nam.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Purchase Order?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'purchase_order/add1',
                        dataType: 'JSON',
                        data: {
							kota_aju : kota_aju,
							pengajuan	: pengajuan,
							bulan_1	: bulan_1,
							bulan_2	: bulan_2,
							bulan_3	: bulan_3,
							lampiran: lampiran,
							itung	: itung,
							nama	: nam,
							kodebarang	: kod,
							bulan1	: bu1,
							bulan2	: bu2,
							bulan3	: bu3,
							gudang	: gud,
							stokpo	: sto,
							kebutuhan	: keb,
							rencana		: ren,
							supplier	: sup,
							detail			: det,
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