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
		<font color='#000000'>
		<div class="form-group">
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
		</div>

<!-- TABEL SURAT PENGAJUAN -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                        	<tr>
                        		<td colspan="4"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: …/S.RKM/PT.VYM/…/20..(di isi otomatis oleh sistem) </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"> Perihal :<input type="text" id="perihal" style="width:75%; border:0px; margin:0px;" required placeholder="Input Perihal">  </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Kepada Yth.
                        		</td>
                        	</tr>
                        	<!-- <tr>
                        		<td colspan="4"> <input type="text" id="perihal" style="width:75%; border:0px; margin:0px;" required placeholder="Input yang bersangkutan"> </td>
                        	</tr> -->
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Di -
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Tempat</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Dengan Ini <input type="text" id="keterangan_1" style="width:75%; border:0px; margin:0px;" required placeholder="Input Keperluan">
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			bahwasanya karyawan atas nama sebagai berikut	:
                        		</td>
                        	</tr>

													<tr>
														<td colspan="1">
																<select class="select-transaction input-group-sm form-control" name="kar" id="kar" >
																					<option value = "">Pilih Jumlah karyawan</option>
																					<option value = "1">1</option>
																					<option value = "2">2</option>
																					<option value = "3">3</option>
																					<option value = "4">4</option>
																					<option value = "5">5</option>
																					<option value = "6">6</option>
																					<option value = "7">7</option>
																					<option value = "8">8</option>
																					<option value = "9">9</option>
																					<option value = "10">10</option>
																	</select>     
														</td>  
													</tr>
													<tr name="kar1" id="kar1" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar1" id="idkar1" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>

													<tr name="kar2" id="kar2" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar2" id="idkar2" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar3" id="kar3" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar3" id="idkar3" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar4" id="kar4" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar4" id="idkar4" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar5" id="kar5" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar5" id="idkar5" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar6" id="kar6" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar6" id="idkar6" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar7" id="kar7" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar7" id="idkar7" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar8" id="kar8" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar8" id="idkar8" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar9" id="kar9" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar9" id="idkar9" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
													<tr name="kar10" id="kar10" style="display: none;">
                        		<td width="7%" style="text-align:right;"> &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="idkar10" id="idkar10" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                                </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Diberikan rekomendasi <input type="text" id="keterangan_2" style="width:75%; border:0px; margin:0px;" required placeholder="Input yang bersangkutan"> sebagai berikut :</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="25%">&nbsp;Atas Dasar</td>
                        		<td width="60%"> : &nbsp;<input type="text" id="dasar" style="width:75%; border:0px; margin:0px;" required placeholder="Input Dasar"></td>
                        	</tr>
                        	<tr>
                        		<td width="25%">&nbsp;Terhitung Mulai Bulan</td>
                        		<td width="60%"> : &nbsp;<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="tgl" name="tgl" Style="width:15%; text-align:left" placeholder="Input Tanggal" required> </td>
                        		<td width="5%"></td>
                        	</tr>
                        	<tr>
                        		<td>&nbsp;Perubahan</td>
                        		<td>:&nbsp;<input type="text" id="perubahan" style="width:75%; border:0px; margin:0px;" required placeholder="Input Perubahan"></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat pemberitahuan ini dibuat, atas perhatian diucapkan terima kasih.
.
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Lampiran <input type="text" id="lampiran" style="width:75%; border:0px; margin:0px;" required placeholder="Input Link Lampiran"></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	
                        </tbody>
					</table>
				<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr>
						<td height="20px"></td>
					</tr>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
						 	Pekanbaru
							<span> ,&nbsp; </span>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="tgl_pengajuan" name="tgl_pengajuan" Style="width:15%; text-align:left" placeholder="Tanggal Pengajuan" required> 
						</td>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" >Diverifikasi Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
							$ttd2 		= $img_path."ttd_blank.png";
							$ttd3 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center; width:25%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; ">Amtisari Destiani Eka Putri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Yolanda Pratiwi<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Bob Ariyos<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i>General Affair</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>
				</tbody>
			</table>
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
						<tr>
							<td colspan="4"><i>Tembusan :</i></td>
						</tr>
						<tr>
							<td style="text-align:right; " width="5%"><i>1. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Direksi</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><i>2. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;General Affair</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><i>3. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Finance</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><i>4. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Arsip</i></td>
						</tr>
						<tr>
							<td colspan="4"><font color="white">i </font></td>
						</tr>
						<!--<tr>-->
						<!--	<td >Lampiran</td>-->
						<!--	<td colspan="3"> :<input type="text" name="lampiran" id="lampiran" placeholder=" &nbsp;Pastekan link lampiran anda" required></td>-->
						<!--</tr>-->
					</table>
					<br>
<!-- PENUTUP SURAT PENGANTAR DINAS -->
			<br><br>
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

	document.getElementById("kar").addEventListener("change", function() {
        var pilih = this.value;
        var kar1 = document.getElementById("kar1");
        var kar2 = document.getElementById("kar2");
        var kar3 = document.getElementById("kar3");
        var kar4 = document.getElementById("kar4");
        var kar5 = document.getElementById("kar5");
        var kar6 = document.getElementById("kar6");
        var kar7 = document.getElementById("kar7");
        var kar8 = document.getElementById("kar8");
        var kar9 = document.getElementById("kar9");
        var kar10 = document.getElementById("kar10");

        if (pilih === "1") {
            kar1.style.display = "block";
            kar2.style.display = "none";
            kar3.style.display = "none";
            kar4.style.display = "none";
            kar5.style.display = "none";
            kar6.style.display = "none";
            kar7.style.display = "none";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "2"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "none";
            kar4.style.display = "none";
            kar5.style.display = "none";
            kar6.style.display = "none";
            kar7.style.display = "none";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "3"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "none";
            kar5.style.display = "none";
            kar6.style.display = "none";
            kar7.style.display = "none";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "4"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "block";
            kar5.style.display = "none";
            kar6.style.display = "none";
            kar7.style.display = "none";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "5"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "block";
            kar5.style.display = "block";
            kar6.style.display = "none";
            kar7.style.display = "none";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "6"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "block";
            kar5.style.display = "block";
            kar6.style.display = "block";
            kar7.style.display = "none";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "7"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "block";
            kar5.style.display = "block";
            kar6.style.display = "block";
            kar7.style.display = "block";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "8"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "block";
            kar5.style.display = "block";
            kar6.style.display = "block";
            kar7.style.display = "block";
            kar8.style.display = "block";
            kar9.style.display = "none";
            kar10.style.display = "none";
        } else if(pilih === "9"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "block";
            kar5.style.display = "block";
            kar6.style.display = "block";
            kar7.style.display = "block";
            kar8.style.display = "block";
            kar9.style.display = "block";
            kar10.style.display = "none";
        } else if(pilih === "10"){
            kar1.style.display = "block";
            kar2.style.display = "block";
            kar3.style.display = "block";
            kar4.style.display = "block";
            kar5.style.display = "block";
            kar6.style.display = "block";
            kar7.style.display = "block";
            kar8.style.display = "block";
            kar9.style.display = "block";
            kar10.style.display = "block";
        } else {
            kar1.style.display = "none";
            kar2.style.display = "none";
            kar3.style.display = "none";
            kar4.style.display = "none";
            kar5.style.display = "none";
            kar6.style.display = "none";
            kar7.style.display = "none";
            kar8.style.display = "none";
            kar9.style.display = "none";
            kar10.style.display = "none";
        }
    });
	
	// PENUTP FUNGSI TAMBAH BUTTON
    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var perihal 					= $('#perihal').val();
			var keterangan_1 			= $('#keterangan_1').val();
			var keterangan_2 			= $('#keterangan_2').val()
			var kar		 						= $('#kar').val();	;		
			var idkar1 						= $('#idkar1').val();	
			var idkar2 						= $('#idkar2').val();	
			var idkar3 						= $('#idkar3').val();	
			var idkar4 						= $('#idkar4').val();	
			var idkar5 						= $('#idkar5').val();	
			var idkar6 						= $('#idkar6').val();	
			var idkar7 						= $('#idkar7').val();	
			var idkar8 						= $('#idkar8').val();	
			var idkar9 						= $('#idkar9').val();	
			var idkar10 					= $('#idkar10').val();
			var lampiran 					= $('#lampiran').val();
			var dasar 						= $('#dasar').val();
			var tgl 							= $('#tgl').val();
			var perubahan 				= $('#perubahan').val();
			var tgl_pengajuan			= $('#tgl_pengajuan').val();
			// var lampiran				= $('#lampiran').val();
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Surat Rekomendasi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/rekom',
                        dataType: 'JSON',
                        data: {
							perihal 			: perihal,
							keterangan_1 	: keterangan_1,
							keterangan_2 	: keterangan_2,	
							kar		 				: kar,		
							idkar1 				: idkar1,		
							idkar2 				: idkar2,		
							idkar3 				: idkar3,		
							idkar4 				: idkar4,		
							idkar5 				: idkar5,		
							idkar6 				: idkar6,		
							idkar7 				: idkar7,		
							idkar8 				: idkar8,		
							idkar9 				: idkar9,		
							idkar10				: idkar10,		
							lampiran			: lampiran,	
							dasar 				: dasar,	
							tgl 					: tgl,		
							perubahan 		: perubahan,		
							tgl_pengajuan	: tgl_pengajuan,			
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }				
            })
        })
		
		$(document).on('click', '.btn-denial', function() {
            const id = $(this).attr("id-pd")
            Swal.fire({
				title: 'Tolak Pengantar Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/pd/2/ttd_1',
                        dataType: 'JSON',
                        data: {
                            id : id,
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