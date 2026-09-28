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
            <h2><font color='#000000' face='Times New Roman'>Pengajuan Pengeluaran Alat</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan permintaan berikut :</font>
					</td>
				</tr>
				<tr>					
						
					</font>
					<td rowspan="10" width="7%"></td>
					<tr>
						<td width="20%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td width="20%">Jabatan </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->jabatan ?>  </td>
					</tr>
					<tr>
						<td width="20%">Tujuan Penggunaan </td>
						<td>:&nbsp;&nbsp;<input id='penggunaan' type="text" placeholder='Klik Untuk Memasukkan Tujuan Penggunaan Alat' required></td>
					</tr>
					
					<tr>
						<td width="20%">Link Lampiran </td>
						<td>:&nbsp;&nbsp;<input id='link_lampiran' type="text" placeholder='Klik Untuk Memasukkan Link Lampiran' required></td>
					</tr>
					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#C6DEFF"> Nama Alat</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="17%"> Waktu Keluar </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="30%"> Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung = 1;
								for($x=1;$x<=10;$x++){
									$itung = $x;
							?>
								<tr>
                                    <td>
                                    	<select class="select-transaction input-group-sm form-control" id="<?= 'namaalat_'.$x ?>" >
																				<option value ="">Pilih Alat</option>
																				<?php
																				foreach ($list_alat as $row) {
																					echo '<option value="' . $row->id . '">' . $row->nama . '</option>';
																				} 
																				?>
																			</select>
                                    </td>
                                    <td style="text-align:center">
																			<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="<?= 'waktukeluar_'.$x ?>" Style="width:100%; text-align:center"  required>										
																		</td>
                                    <td>
                                    	<input type="text" id="<?= 'ket_'.$x ?>" Style="width:100%" required>
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
							<span> ,&nbsp; Diisi sistem</span>
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

		//cell1.innerHTML = "<input type='text' id='namaalat_" + urut1 + "' Style='width:100%'>";
		cell1.innerHTML = `<select class="select-transaction input-group-sm form-control" id="namaalat_${urut1}" style="width:100%">
					<option value="">Pilih Alat</option>
					<?php foreach ($list_alat as $row) { ?>
							<option value="<?= $row->id ?>"><?= $row->nama ?></option>
					<?php } ?>
			</select>`;
		cell2.innerHTML = "<input type='date' id='waktukeluar_" + urut1 + "' Style='width:100%'>";
		cell3.innerHTML = "<input type='text' id='ket_" + urut1 + "' Style='width:100%'>";
		document.getElementById('itung').value = urut1;
	}
	
		function myDeleteFunction1() {
			x = x-1;		
			document.getElementById("myTable1").deleteRow(x);			
		}

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var kota_aju		= $('#kota_aju').val();
			const 	isi			= kota_aju.split("PengHubunG");
				kota_aju		= isi[1];
			var penggunaan 	= $('#penggunaan').val();
			var link_lampiran 	= $('#link_lampiran').val();
			
			let itung_isi	= $('#itung').val();
			let namaalat		= [];
			let waktukeluar	= [];
			let ket	= [];		
			for (let i=1; i<=itung_isi; i++) {
				if($( '#waktukeluar_'+i).val() != ""){
					namaalat[i]			= $('#namaalat_'+i).val();
					waktukeluar[i]	= $('#waktukeluar_'+i).val();
					ket[i]	= $('#ket_'+i).val();
					
				}				
			}		
			let itung		= namaalat.length;
			
			Swal.fire({
				title: 'Ajukan Pengeluaran Stok Alat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/add/laporan_stok',
                        dataType: 'JSON',
                        data: {
							kota_aju 		: kota_aju,
							penggunaan		: penggunaan,
							link_lampiran : link_lampiran,
							namaalat		: namaalat,
							waktukeluar : waktukeluar,
							ket 	: ket,
							itung       : itung,
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