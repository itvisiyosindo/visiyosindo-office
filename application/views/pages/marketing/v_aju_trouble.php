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
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Form Trouble / Installation Request</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan  Trouble / Installation Request :</font>
					</td>
				</tr>
				<tr>											
		</font>
					<td rowspan="12" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td>Customer Name </td>
						<td>:&nbsp;&nbsp;<input id='csname' type="text" placeholder='Klik Untuk Memasukkan Customer Name' required></td>
					</tr>
					<tr>
						<td>Address </td>
						<td>:&nbsp;&nbsp;<input id='alamat' type="text" placeholder='Klik Untuk Memasukkan Address' required></td>
					</tr>
					<tr>
						<td>Contact Person Name</td>
						<td>:&nbsp;&nbsp;<input id='cpname' type="text" placeholder='Klik Untuk Memasukkan Contact Person Name' required></td>
					</tr>
					<tr>
						<td>No CP </td>
						<td>:&nbsp;&nbsp;<input id='nocp' type="text" placeholder='Klik Untuk Memasukkan No CP' required></td>
					</tr>
					<tr>
						<td>Invoice </td>
						<td>:&nbsp;&nbsp;<input id='invoice' type="text" placeholder='Klik Untuk Memasukkan Invoice' required></td>
					</tr>

					
					
					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%"> No </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Description Equipment </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="40%"> Request Detail </th>
                            </tr>
                        </thead>
                        <tbody>
									<?php
										$itung = 1;
										for($x=1;$x<=10;$x++){
										$itung = $x;
									?>
								<tr>
                  <td style="text-align:center"><?= $x ?></td>
									<td><input type="text" id="<?= 'deskripsi_'.$x ?>" Style="width:100%" required></td>
									<td><input type="text" id="<?= 'req_detail_'.$x ?>" Style="width:100%" required></td>
                </tr>
										<?php } ?>
										<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        
			</table>
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
						


				<tr>
					<tr>
						<td>Notes </td>
						<td>:&nbsp;&nbsp;<input id='notes' type="text" placeholder='Klik Untuk Memasukkan Notes' required></td>
					</tr>
				</tr>
			</table>
			<br>

			<table id="tbl_171" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left; font-weight:bold;">
						<font color='#000000'> Information Pra Intalation (Isi jika melakukan permintaan instalasi)</font>
					</td>
				</tr>
			</table>

			
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
						<thead>
								<tr>
										<th style="text-align:center" bgcolor="#b7d5ac">Unit</th>
										<th style="text-align:center" bgcolor="#b7d5ac" width="20%">Kondisi</th>
								</tr>
						</thead>
						<tbody>
							<tr>
									<td>Kodisi ruangan sudah ready</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi1" id="kondisi1" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi1" id="kondisi1" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah Listrik (220 Volt)</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi2" id="kondisi2" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi2" id="kondisi2" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah daya listrik lebih dari (4000 watt)</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi3" id="kondisi3" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi3" id="kondisi3" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah ruangan sudah terdapat AC dan suhu sudah berkisar 15-25</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi4" id="kondisi4" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi4" id="kondisi4" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah di ruangan terdapat UPS / Stabilizer</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi5" id="kondisi5" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi5" id="kondisi5" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah sudah terdapat petugas radiographer/user dan PPR yang akan mengoperasikan alat</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi6" id="kondisi6" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi6" id="kondisi6" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah dokumen perizinan pesawat tersedia</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi7" id="kondisi7" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi7" id="kondisi7" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah manual/spesifikasi teknis pesawat tersedia</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi8" id="kondisi8" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi8" id="kondisi8" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah tersedia data dukung terkait peralatan pengolah citra (CR, DR, dsb)</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi9" id="kondisi9" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi9" id="kondisi9" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td>Apakah tersedia perlengkapan proteksi radiasi (apron, shielding, dsb)</td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi10" id="kondisi10" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi10" id="kondisi10" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
							<tr>
									<td><input id='namaitem11' type="text" placeholder='Klik Untuk Memasukkan Lainnya' required></td>
									<td style="text-align:center">
											<label>
													<input type="checkbox" name="kondisi11" id="kondisi11" value="Ya" onclick="toggleCheckbox(this)"> Ya
											</label>
											<label>
													<input type="checkbox" name="kondisi11" id="kondisi11" value="Tidak" onclick="toggleCheckbox(this)"> Tidak
											</label>
									</td>
							</tr>
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

		function toggleCheckbox(checkbox) {
								const checkboxes = document.getElementsByName(checkbox.name);
								checkboxes.forEach(cb => {
										if (cb !== checkbox) {
												cb.checked = false;
										}
								});
        }




    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {		
			var kota_aju	= $('#drop_kota').val();
			const 	isi		= kota_aju.split("PengHubunG");
				kota_aju	= isi[1];
			var csname 	= $('#csname').val();
			var alamat 	= $('#alamat').val();
			var tanggal	= $('#tanggal').val();
			var cpname 	= $('#cpname').val();
			var nocp 		= $('#nocp').val();
			var invoice = $('#invoice').val();
			var notes		= $('#notes').val();
			var pengajuan	= $('#pengajuan').val();

			var kondisi1 = $('input[name="kondisi1"]:checked').val();  // Dapatkan nilai dari checkbox yang dicentang
			var kondisi2 = $('input[name="kondisi2"]:checked').val();
			var kondisi3 = $('input[name="kondisi3"]:checked').val();
			var kondisi4 = $('input[name="kondisi4"]:checked').val();
			var kondisi5 = $('input[name="kondisi5"]:checked').val();
			var kondisi6 = $('input[name="kondisi6"]:checked').val();
			var kondisi7 = $('input[name="kondisi7"]:checked').val();
			var kondisi8 = $('input[name="kondisi8"]:checked').val();
			var kondisi9 = $('input[name="kondisi9"]:checked').val();
			var kondisi10 = $('input[name="kondisi10"]:checked').val();
			var kondisi11 = $('input[name="kondisi11"]:checked').val();

			var namaitem11	= $('#namaitem11').val();
			
			let itung_isi	= $('#itung').val();
			let des			= [];
			let deskripsi	= [];
			let reqdet			= [];
			let req_detail		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#deskripsi_'+i).val() != ""){
					des[i] = $('#deskripsi_'+i).val();
					reqdet[i] = $('#req_detail_'+i).val();		
				}				
			}			
			let itung		= des.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Trouble atau Installation?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'fpp/addPermintaan/trouble',
                        dataType: 'JSON',
                        data: {
							kota_aju : kota_aju,
							csname	: csname,
							alamat	: alamat,
							tanggal	: tanggal,
							cpname	: cpname,
							nocp		: nocp,
							itung		: itung,
							invoice	: invoice,
							notes		: notes,
							pengajuan	: pengajuan,
							kondisi1	: kondisi1,
							kondisi2	: kondisi2,
							kondisi3	: kondisi3,
							kondisi4	: kondisi4,
							kondisi5	: kondisi5,
							kondisi6	: kondisi6,
							kondisi7	: kondisi7,
							kondisi8	: kondisi8,
							kondisi9	: kondisi9,
							kondisi10	: kondisi10,
							kondisi11	: kondisi11,
							namaitem11: namaitem11,
							deskripsi	: des,
							req_detail	: reqdet,
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