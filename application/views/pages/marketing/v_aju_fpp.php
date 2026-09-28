<header class="page-header">

	<h2><i class="fas fa-file-signature"></i>&nbsp;<?= $page_title ?></h2>

	<div class="right-wrapper text-left">

		<ol class="breadcrumbs">

			<li><span><?= $page_desc ?></span></li>

		</ol>

	</div>



	<style>
		hr {

			display: block;

			margin-top: 0em;

			margin-bottom: 0em;

			margin-left: auto;

			margin-right: auto;

			border-top: 1px solid black;

		}



		input {

			width: 97%;

			height: auto;

			border: 0px dotted #f30;

			border-radius: 4px;

			-moz-border-radius: 8px;

			margin-right: 0px;

		}



		.myinput {

			width: 97%;

			height: auto;

			border: 0px solid #000;

			border-radius: 0px;

			-moz-border-radius: 8px;

			margin-left: 0px;

			background: #b7d5ac;

		}



		.myselect {

			width: 97%;

			height: auto;

			border: 0px solid #000;

			border-radius: 4px;

			-moz-border-radius: 8px;

			margin: 0px;

		}



		.mydiv br {

			display: none;

		}



		.mydiv p {

			padding: 0;

			margin: 0;

		}

		/* CSS tambahan untuk merapikan radio button TLD */

		.tld-options-wrapper {
			width: 100%;
			display: flex;
			justify-content: space-around;
			/* Menyebar opsi secara merata */
			text-align: center;
			/* Memastikan teks di dalam wrapper rata tengah */
		}

		.tld-options label {
			display: flex;
			flex-direction: column;
			/* Radio di atas, Label di bawah */
			align-items: center;
			/* Pusatkan item */
			padding: 5px 0;
			margin: 0 5px;
			/* Memberi sedikit jarak antar kolom label */
			font-size: 14px;
			/* Sedikit perkecil font label radio jika diperlukan */
		}

		.tld-options input[type="radio"] {
			margin-bottom: 5px;
			width: auto;
			/* Radio button tidak memakan 100% lebar */
		}

		/* Style untuk menyelaraskan header tabel */
		.header-table-tld {
			background-color: #b7d5ac !important;
			/* Warna hijau muda dari header tabel atas (kt_table_1) */
			text-align: center;
			font-weight: bold;
		}

		/* Memastikan warna latar pada cell radio/select di tabel TLD tetap putih */
		#table_tld td {
			background-color: #FFFFFF;
		}

		/* Notifikasi Card Selection Style */
		.notif-card-container {
			display: grid !important;
			grid-template-columns: repeat(3, 1fr) !important;
			gap: 10px !important;
			width: 100% !important;
			margin: 5px 0 !important;
		}

		.notif-card-item {
			background: #ffffff !important;
			border: 1.5px solid #e2e8f0 !important;
			border-radius: 6px !important;
			padding: 8px 12px !important;
			cursor: pointer !important;
			position: relative !important;
			transition: all 0.2s ease-in-out !important;
			display: flex !important;
			align-items: center !important;
			gap: 10px !important;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
			user-select: none !important;
		}

		.notif-card-item:hover {
			border-color: #cbd5e1 !important;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04) !important;
		}

		.notif-card-item.active {
			border-color: #0088CC !important;
			background-color: #f0f9ff !important;
			box-shadow: 0 2px 6px rgba(0, 136, 204, 0.05) !important;
		}

		.notif-card-icon {
			width: 28px !important;
			height: 28px !important;
			background: #f1f5f9 !important;
			border-radius: 4px !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			font-size: 12px !important;
			color: #64748b !important;
			transition: all 0.2s ease !important;
			flex-shrink: 0 !important;
		}

		.notif-card-item.active .notif-card-icon {
			background: #0088CC !important;
			color: #ffffff !important;
		}

		.notif-card-content {
			display: flex !important;
			flex-direction: column !important;
			text-align: left !important;
		}

		.notif-card-title {
			font-family: 'Poppins', sans-serif !important;
			font-size: 13px !important;
			font-weight: 600 !important;
			color: #334155 !important;
			margin: 0 !important;
		}

		.notif-card-item.active .notif-card-title {
			color: #0088CC !important;
		}

		.notif-card-checkbox {
			position: absolute !important;
			top: 50% !important;
			right: 12px !important;
			transform: translateY(-50%) !important;
			width: 14px !important;
			height: 14px !important;
			margin: 0 !important;
			cursor: pointer !important;
			accent-color: #0088CC !important;
		}
	</style>

</header>

<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">

	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">

		<div class="alert alert-warning fade show text-left" role="alert" style="margin: 0 auto 25px auto; width: 100%; border-left: 6px solid #e09b12; background-color: #fdf8e2; color: #7d5a0b; font-family: Times New Roman; font-size: 15px; padding: 12px 20px; border-radius: 4px;">
			<i class="fas fa-exclamation-triangle" style="margin-right: 8px; color: #d08200;"></i>
			<strong>PENTING:</strong> Mohon perhatikan pilihan <strong>Penerima Notifikasi WhatsApp</strong> di bawah ini agar notifikasi pengajuan terkirim ke tim yang tepat (Marketing / Visilan / Semua). Jangan diabaikan!
		</div>

		<div class="table-responsive">

			<div class="text-center mt-0">

				<h2>

					<font color='#000000' face='Times New Roman'>Lengkapi Data Form Permintaan Penawaran</font>

				</h2>

			</div>

			<font color='#000000'>

				<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">

					<tr>

						<td colspan="3" style="text-align:left">

							<font color='#000000'>Dengan ini saya mengajukan permintaan penawaran :</font>

						</td>

					</tr>

					<tr>

			</font>

			<td rowspan="15" width="7%"></td>

			<tr>

				<td width="18%">Nama </td>

				<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?> </td>

			</tr>

			<tr>

				<td>Jabatan </td>

				<td>:&nbsp;&nbsp; <?= $pengguna[0]->jabatan ?></td>

			</tr>



			<tr>

				<td>Customer Name </td>

				<td>:&nbsp;&nbsp;<input id='csname' type="text" placeholder='Klik Untuk Memasukkan Customer Name' required></td>

			</tr>

			<tr>

				<td>Alamat </td>

				<td>:&nbsp;&nbsp;<input id='alamat' type="text" placeholder='Klik Untuk Memasukkan Alamat' required></td>

			</tr>

			<tr>

				<td>Tanggal </td>

				<td>

					<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'>

						<span> :&nbsp;&nbsp; </span>

						<input type="text" id="tanggal" Style="width:20%" placeholder='Tanggal' required>

					</div>

				</td>

			</tr>

			<tr>

				<td>Contact Person Name</td>

				<td>:&nbsp;&nbsp;<input id='cpname' type="text" placeholder='Klik Untuk Memasukkan Contact Person Name' required></td>

			</tr>

			<tr>

				<td>No CP </td>

				<td>:&nbsp;&nbsp;<input id='cpno' type="text" placeholder='Klik Untuk Memasukkan No CP' required></td>

			</tr>



			<tr>

				<td>Term Of Payment </td>

				<td>

					<select class="select-transaction input-group-sm form-control" name="payment" id="payment">

						<option value="CASH">CASH</option>

						<option value="COD">COD</option>

						<option value="NET 30">NET 30</option>

						<option value="CREDIT">CREDIT</option>

						<option value="1">Lainnya</option>

					</select>

					&nbsp;&nbsp;<input id="lainnyaInput" type="text" placeholder=': Klik Untuk Memasukkan Term of Payment' style="display: none;" required>

				</td>

				</td>

			</tr>



			<tr>

				<td>Tipe Cicilan </td>

				<td>:&nbsp;&nbsp;<input id='cicilan' type="text" placeholder='Klik Untuk Memasukkan DP dan Lama Cicilan' required></td>

			</tr>



			<tr>

				<td>Ongkos Kirim </td>

				<td>

					<select class="select-transaction input-group-sm form-control" name="ongkir" id="ongkir">

						<option value="Termasuk">Termasuk</option>

						<option value="Tidak Termasuk">Tidak Termasuk</option>

						<option value="1">Tambahkan Referensi Ongkir</option>

					</select>

					&nbsp;&nbsp;<input id="lainnyaOngkir" type="text" placeholder=': Klik Untuk Memasukkan Tambahkan Referensi Ongkir' style="display: none;" required>

				</td>

				</td>

			</tr>

			<tr>

				<td>Pajak </td>

				<td>

					<select class="select-transaction input-group-sm form-control" name="pajak" id="pajak">

						<option value="Belum Termasuk PPN">Belum Termasuk PPN</option>

						<option value="Termasuk PPN">Termasuk PPN</option>

						<option value="Non PPN">Non PPN</option>

					</select>

				</td>

			</tr>





			<tr>

				<td>Notifikasi <span class="text-danger">*</span></td>

				<td>
					<div class="notif-card-container">
						<div class="notif-card-item active" id="card_notif_mkt">
							<div class="notif-card-icon"><i class="fas fa-bullhorn"></i></div>
							<div class="notif-card-content">
								<span class="notif-card-title">Marketing</span>
							</div>
							<input type="checkbox" id="notif_mkt" class="notif-card-checkbox" checked style="pointer-events: none;">
						</div>
						<div class="notif-card-item" id="card_notif_vis">
							<div class="notif-card-icon"><i class="fas fa-flask"></i></div>
							<div class="notif-card-content">
								<span class="notif-card-title">Visilan</span>
							</div>
							<input type="checkbox" id="notif_vis" class="notif-card-checkbox" style="pointer-events: none;">
						</div>
						<div class="notif-card-item" id="card_notif_semua">
							<div class="notif-card-icon"><i class="fas fa-users"></i></div>
							<div class="notif-card-content">
								<span class="notif-card-title">Semua</span>
							</div>
							<input type="checkbox" id="notif_semua" class="notif-card-checkbox" style="pointer-events: none;">
						</div>
					</div>
					<input type="hidden" name="notifikasi" id="notifikasi" value="1">
				</td>

			</tr>



			<tr>

				<td>Notes </td>

				<td>:&nbsp;&nbsp;<input id='notes' type="text" placeholder='Klik Untuk Memasukkan Notes' required></td>

			</tr>



			<tr>

				<td>

					<font color="white">i </font>

				</td>

			</tr>

			</tr>

			</table>



			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">

				<thead>

					<tr>

						<th style="text-align:center" bgcolor="#b7d5ac" width="5%"> No </th>

						<th style="text-align:center" bgcolor="#b7d5ac"> Description </th>

						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Qty </th>

						<th style="text-align:center" bgcolor="#b7d5ac" width="10%"> Unit Price (IDR) </th>

						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Disc (%) </th>

						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Komisi User (%) </th>

						<th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Nama User </th>

						<th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Komisi Pihak Ke-3 (%) </th>

						<th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Nama Pihak Ke-3 </th>

					</tr>

				</thead>

				<tbody>

					<?php

					$itung = 1;

					for ($x = 1; $x <= 20; $x++) {

						$itung = $x;

					?>

						<tr>

							<td style="text-align:center">

								<?= $x ?>

							</td>

							<td><input type="text" id="<?= 'deskripsi_' . $x ?>" Style="width:100%" required></td>

							<td><input type="number" id="<?= 'quali_' . $x ?>" Style="width:100%; text-align:right" required></td>

							<td><input type="number" id="<?= 'price_' . $x ?>" Style="width:100%; text-align:right" required></td>

							<td><input type="text" id="<?= 'diskon_' . $x ?>" Style="width:100%" placeholder='Tambah %' required></td>

							<td><input type="text" id="<?= 'komisi_' . $x ?>" Style="width:100%" placeholder='Tambah %' required></td>

							<td><input type="text" id="<?= 'namauser_' . $x ?>" Style="width:100%; text-align:right" required></td>

							<td><input type="text" id="<?= 'komisiketiga_' . $x ?>" Style="width:100%" placeholder='Tambah %' required></td>

							<td><input type="text" id="<?= 'namaketiga_' . $x ?>" Style="width:100%; text-align:right" required></td>

						</tr>

					<?php } ?>

					<input type="hidden" id="itung" value="<?= $itung ?>">

				</tbody>



			</table>

			<br><br>



			<p style="font-family:Times New Roman; font-size:15px; margin-bottom:10px;">

				<font color='#000000'>Silakan centang <i class="fas fa-check"></i> layanan yang ingin disertakan dalam penawaran.<br>

					<em>Catatan: Berlaku untuk pembelian produk <strong>Alerio atau Mesin</strong>. Jika permintaan untuk produk lain (misalnya <strong>Uniray</strong>), abaikan bagian ini.</em>

			</p>



			<table id="table_jasa" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">

				<thead>

					<tr>

						<th style="text-align:center" bgcolor="#d3d3d3" width="5%">No</th>

						<th style="text-align:center" bgcolor="#d3d3d3">Nama Produk</th>

						<th style="text-align:center" bgcolor="#d3d3d3" width="10%">Ceklis</th>

					</tr>

				</thead>

				<tbody>

					<?php

					$produk = [

						"Biaya Pengurusan Izin Pemanfaatan Alat Radiasi",

						"Biaya Pengurusan Perpanjangan Izin Pemanfaatan Alat Radiasi",

						"Biaya Izin Pemanfaatan Alat Radiasi di Bapeten (billing Sertifikat)",

						"Biaya Perpanjangan Izin Pemanfaatan Alat Radiasi di Bapeten (billing Sertifikat)",

						"Dokumen bangunan utilitas operasi pemanfaatan Sumber Radiasi Pengion",

						"Dokumen rencana teknis fasilitas bangunan gedung penahan Radiasi",

						"Dokumen Program Proteksi Radiologi Diagnostik dan Intervensional",

						"Dokumen Kajian Keselamatan Sumber Radiasi"

					];



					$no = 1;

					foreach ($produk as $nama) {

					?>

						<tr>

							<td style="text-align:center"><?= $no; ?></td>

							<td><?= $nama; ?></td>

							<td style="text-align:center">

								<input type="checkbox" id="<?= 'approval_' . $no ?>" name="<?= 'approval_' . $no ?>" value="1" />

							</td>

						</tr>

					<?php

						$no++;
					} ?>



				</tbody>

			</table>



			<br>



			<p style="font-family:Times New Roman; font-size:15px; margin-bottom:10px; font-size: 18px;" class="font-weight-bold text-dark">
				Kondisi Penawaran TLD
			</p>

			<table id="table_tld" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
				<thead>
					<tr>
						<th class="header-table-tld">Kondisi Penawaran</th>
						<th class="header-table-tld" colspan="3">Isi Form</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Apa Jenis Pembelian TLD</td>
						<td colspan="3">
							<div class="tld-options-wrapper">
								<div class="tld-options">
									<label>
										<input type="radio" name="jenis_pembelian_tld" value="TLD Baru" required>
										TLD Baru
									</label>
								</div>
								<div class="tld-options">
									<label>
										<input type="radio" name="jenis_pembelian_tld" value="TLD Tambahan User">
										TLD Tambahan User
									</label>
								</div>
								<div class="tld-options">
									<label>
										<input type="radio" name="jenis_pembelian_tld" value="TLD Kosongan atau Cadangan">
										TLD Kosongan/Cadangan
									</label>
								</div>
							</div>
						</td>
					</tr>

					<tr>
						<td>Berapa Jumlah Pekerja Radiasi ? (SEBUTKAN DALAM ANGKA)</td>
						<td colspan="3"><input type="number" id="jumlah_pekerja_radiasi" name="jumlah_pekerja_radiasi" Style="width:100%" placeholder='........' required></td>
					</tr>
					<tr>
						<td>Include Zero Check ? (YA/TIDAK)</td>
						<td colspan="3">
							<select id="include_zero_check" name="include_zero_check" class="form-control" style="width:100%" required>
								<option value="YA">YA</option>
								<option value="TIDAK">TIDAK</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Apakah sudah memiliki TLD Kontrol ? (YA/TIDAK)</td>
						<td colspan="3">
							<select id="sudah_memiliki_tld_kontrol" name="sudah_memiliki_tld_kontrol" class="form-control" style="width:100%" required>
								<option value="YA">YA</option>
								<option value="TIDAK">TIDAK</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Apakah membutuhkan TLD Kontrol Baru? (YA/TIDAK)</td>
						<td colspan="3">
							<select id="membutuhkan_tld_kontrol_baru" name="membutuhkan_tld_kontrol_baru" class="form-control" style="width:100%" required>
								<option value="YA">YA</option>
								<option value="TIDAK">TIDAK</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Apakah TLD yang di tawarkan sudah include TLD Kontrol ? (YA/TIDAK)</td>
						<td colspan="3">
							<select id="tld_include_tld_kontrol" name="tld_include_tld_kontrol" class="form-control" style="width:100%" required>
								<option value="YA">YA</option>
								<option value="TIDAK">TIDAK</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Apakah user sudah pernah terdaftar di Lab Dosimetri yang sama? (YA/TIDAK)</td>
						<td colspan="3">
							<select id="user_terdaftar_lab_dosimetri" name="user_terdaftar_lab_dosimetri" class="form-control" style="width:100%" required>
								<option value="YA">YA</option>
								<option value="TIDAK">TIDAK</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Instansi Menyetujui estimasi pengerjaan Zero Check &plusmn;14 hari kerja ? (YA/TIDAK)</td>
						<td colspan="3">
							<select id="setuju_estimasi_zero_check" name="setuju_estimasi_zero_check" class="form-control" style="width:100%" required>
								<option value="YA">YA</option>
								<option value="TIDAK">TIDAK</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>

			<br><br>



			<br><br>









			<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">

				<tbody>



					<tr style="height: 35px;">

						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">

							<select id="drop_kota" style="text-align:right; width:50%; border:0px; margin:0px;" required>

								<option value="">Pilih Kota</option>

								<?php

								foreach ($list_kota as $row) {

									echo "<option value='" . $row->id . "PengHubunG" . $row->nama . "'>" . $row->nama . "</option>";
								}

								?>

							</select>

							<span> ,&nbsp; </span>

							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="pengajuan" Style="width:15%; text-align:left" placeholder="Pengajuan" required>

						</td>

					</tr>

					<tr style="height: 18px;">

						<td style="text-align:center; width:25.5%;"></td>

						<td style="text-align:center; width:44.5%;"></td>

						<td style="text-align:center; width:25.5%;">Diajukan Oleh,</td>

					</tr>

					<tr style="height:60px;">

						<?php

						$img_path   = "uploads/file_karyawan/ttd/";

						$ttdaju   = $img_path . "ttd_" . $pengguna[0]->pengguna_id . ".png";

						$ttd1     = $img_path . "ttd_blank.png";

						?>

						<td style="text-align:center;"> <?php echo '<img src="" height="70">'; ?> </td>

						<td style="text-align:center;"></td>

						<td style="text-align:center;"><?php echo '<img src="' . $ttdaju . '" height="70">'; ?></td>

					</tr>

					<tr>

						<td style="text-align:center; "></td>

						<td style="text-align:center; "></td>

						<td style="text-align:center; "><?= $pengguna[0]->short_name ?>

							<hr>

							</hr>

						</td>

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

			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>

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

			var kota_aju = $('#drop_kota').val();

			const isi = kota_aju.split("PengHubunG");

			kota_aju = isi[1];

			var csname = $('#csname').val();

			var alamat = $('#alamat').val();

			var tanggal = $('#tanggal').val();

			var cpname = $('#cpname').val();

			var cpno = $('#cpno').val();

			var payment = $('#payment').val();

			var lainnyaInput = $('#lainnyaInput').val();

			var cicilan = $('#cicilan').val();

			var ongkir = $('#ongkir').val();

			var lainnyaOngkir = $('#lainnyaOngkir').val();

			var pajak = $('#pajak').val();

			var notifikasi = $('#notifikasi').val();

			if (!notifikasi) {
				Swal.fire('Validasi', 'Mohon pilih minimal satu Penerima Notifikasi (Marketing / Visilan / Semua).', 'warning');
				return false;
			}

			var notes = $('#notes').val();

			var pengajuan = $('#pengajuan').val();



			var approval_1 = $('#approval_1').is(':checked') ? $('#approval_1').val() : null;

			var approval_2 = $('#approval_2').is(':checked') ? $('#approval_2').val() : null;

			var approval_3 = $('#approval_3').is(':checked') ? $('#approval_3').val() : null;

			var approval_4 = $('#approval_4').is(':checked') ? $('#approval_4').val() : null;

			var approval_5 = $('#approval_5').is(':checked') ? $('#approval_5').val() : null;

			var approval_6 = $('#approval_6').is(':checked') ? $('#approval_6').val() : null;

			var approval_7 = $('#approval_7').is(':checked') ? $('#approval_7').val() : null;

			var approval_8 = $('#approval_8').is(':checked') ? $('#approval_8').val() : null;



			// Ambil data TLD (termasuk radio button)

			var jenis_pembelian_tld = $('input[name="jenis_pembelian_tld"]:checked').val() || '';
			var jumlah_pekerja_radiasi = $('#jumlah_pekerja_radiasi').val();
			var include_zero_check = $('#include_zero_check').val();
			var sudah_memiliki_tld_kontrol = $('#sudah_memiliki_tld_kontrol').val();
			var membutuhkan_tld_kontrol_baru = $('#membutuhkan_tld_kontrol_baru').val();
			var tld_include_tld_kontrol = $('#tld_include_tld_kontrol').val();
			var user_terdaftar_lab_dosimetri = $('#user_terdaftar_lab_dosimetri').val();
			var setuju_estimasi_zero_check = $('#setuju_estimasi_zero_check').val();

			let itung_isi = $('#itung').val();

			let des = [];

			let deskripsi = [];

			let qty = [];

			let quali = [];

			let pri = [];

			let prince = [];

			let dis = [];

			let diskon = [];

			let kom = [];

			let komisi = [];

			let usr = [];

			let namauser = [];

			let tiga = [];

			let komisiketiga = [];

			let nmtiga = [];

			let namaketiga = [];

			for (let i = 1; i <= itung_isi; i++) {

				if ($('#deskripsi_' + i).val() != "") {

					des[i] = $('#deskripsi_' + i).val();

					qty[i] = $('#quali_' + i).val();

					pri[i] = $('#price_' + i).val();

					dis[i] = $('#diskon_' + i).val();

					kom[i] = $('#komisi_' + i).val();

					usr[i] = $('#namauser_' + i).val();

					tiga[i] = $('#komisiketiga_' + i).val();

					nmtiga[i] = $('#namaketiga_' + i).val();

				}

			}

			let itung = des.length;



			Swal.fire({

				//title: approval + ' absensi?',

				title: 'Ajukan Permintaan Penawaran?',

				icon: 'question',

				showCancelButton: true,

				confirmButtonText: 'Ya',

				cancelButtonText: 'Tidak'

			}).then(function(result) {

				if (result.value) {

					$.ajax({

						method: 'POST',

						url: 'fpp/add',

						dataType: 'JSON',

						data: {

							kota_aju: kota_aju,

							approval_1: approval_1,

							approval_2: approval_2,

							approval_3: approval_3,

							approval_4: approval_4,

							approval_5: approval_5,

							approval_6: approval_6,

							approval_7: approval_7,

							approval_8: approval_8,

							// Data TLD

							jenis_pembelian_tld: jenis_pembelian_tld,

							jumlah_pekerja_radiasi: jumlah_pekerja_radiasi,

							include_zero_check: include_zero_check,

							sudah_memiliki_tld_kontrol: sudah_memiliki_tld_kontrol,

							membutuhkan_tld_kontrol_baru: membutuhkan_tld_kontrol_baru,

							tld_include_tld_kontrol: tld_include_tld_kontrol,

							user_terdaftar_lab_dosimetri: user_terdaftar_lab_dosimetri,

							setuju_estimasi_zero_check: setuju_estimasi_zero_check,

							// Data umum

							csname: csname,

							alamat: alamat,

							tanggal: tanggal,

							cpname: cpname,

							cpno: cpno,

							itung: itung,

							payment: payment,

							lainnyaInput: lainnyaInput,

							cicilan: cicilan,

							ongkir: ongkir,

							lainnyaOngkir: lainnyaOngkir,

							pajak: pajak,

							notifikasi: notifikasi,

							notes: notes,

							pengajuan: pengajuan,

							// Data Detail

							deskripsi: des,

							quali: qty,

							price: pri,

							diskon: dis,

							komisi: kom,

							namauser: usr,

							komisiketiga: tiga,

							namaketiga: nmtiga,

							csrf_token: token

						},

						success: function(resp) {

							handleResponse(resp)

						}

					})

				}

			})

		})

		// Sync checkboxes for Notifikasi
		var $notifMkt = $('#notif_mkt');
		var $notifVis = $('#notif_vis');
		var $notifSemua = $('#notif_semua');
		var $notifikasiInput = $('#notifikasi');

		function updateNotifikasiCards() {
			var mktChecked = $notifMkt.is(':checked');
			var visChecked = $notifVis.is(':checked');
			var semuaChecked = $notifSemua.is(':checked');

			$('#card_notif_mkt').toggleClass('active', mktChecked);
			$('#card_notif_vis').toggleClass('active', visChecked);
			$('#card_notif_semua').toggleClass('active', semuaChecked);
		}

		function updateNotifikasiValue() {
			var mktChecked = $notifMkt.is(':checked');
			var visChecked = $notifVis.is(':checked');

			if (mktChecked && visChecked) {
				$notifikasiInput.val('3');
				$notifSemua.prop('checked', true);
			} else if (mktChecked && !visChecked) {
				$notifikasiInput.val('1');
				$notifSemua.prop('checked', false);
			} else if (!mktChecked && visChecked) {
				$notifikasiInput.val('2');
				$notifSemua.prop('checked', false);
			} else {
				$notifikasiInput.val('');
				$notifSemua.prop('checked', false);
			}
			updateNotifikasiCards();
		}

		// Handle card element clicks
		$('.notif-card-item').click(function(e) {
			if ($(e.target).is('input[type="checkbox"]')) {
				return;
			}
			var $checkbox = $(this).find('input[type="checkbox"]');
			var currentChecked = $checkbox.prop('checked');
			$checkbox.prop('checked', !currentChecked).change();
		});

		$notifMkt.add($notifVis).change(function() {
			updateNotifikasiValue();
		});

		$notifSemua.change(function() {
			if (this.checked) {
				$notifMkt.prop('checked', true);
				$notifVis.prop('checked', true);
			} else {
				$notifMkt.prop('checked', false);
				$notifVis.prop('checked', false);
			}
			updateNotifikasiValue();
		});
	})

	function goBack() {

		window.history.back();

	}
</script>