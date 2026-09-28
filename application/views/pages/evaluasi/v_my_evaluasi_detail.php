<header class="page-header">
	<h2><i class="fas fa-chart-line"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="card-body">
			<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->pegawai ?></strong>
			<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->no_pegawai ?></strong>
			<br><strong class="fw-bold text-dark">Jabatan &nbsp; : <?= $data_job->jabatan ?></strong>
			<br><br>
			<?php if ($data_job->jenis_evaluasi == 1) { ?>
				<strong class="fw-bold text-dark">Jenis Evaluasi : Semester </strong>
				<br><strong class="fw-bold text-dark">Semester : <?= $data_job->smt ?></strong>
				<br><strong class="fw-bold text-dark">Tahun &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->tahun ?></strong>
			<?php } else { ?>
				<strong class="fw-bold text-dark">Jenis Evaluasi : Kontrak </strong>
			<?php } ?>

			<input type="hidden" id="id_po" value="<?= $data_job->id_pengguna ?>" />

			<?php $id = $data_job->id_po ?>
			<input type="hidden" name="id" id="id" value="<?= $data_job->id_po ?>">
		</div>
		<br>

		<?= form_open('evaluasi/updateEvDetail', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

		<div class="card-body">
			<div class="table-responsive">



				<div style="overflow-x: auto; white-space: nowrap; max-width: 100%;">

					<?php
					$x = 1;
					$xy = 0;
					$rataA = 0;
					$rataB = 0;
					$rataC = 0;
					$rataD = 0;
					$rataE = 0;
					$rataF = 0;
					$totalRows = 0; // Untuk menghitung jumlah data yang memenuhi syarat (nilai == 1)

					foreach ($data_detail2 as $row) {
						// Hanya menghitung data jika nilai == 1
						if ($row->nilai == 1) {
							$x = $x + 1;
							$xy = $xy + 1;

							$id = encrypt($row->id);

							// Menghitung total nilai untuk setiap penilai
							$rataA += $row->nilaia;
							$rataB += $row->nilaib;
							$rataC += $row->nilaic;
							$rataD += $row->nilaid;
							$rataE += $row->nilaie;
							$rataF += $row->nilaif;

							$totalRows++; // Menambah jumlah data yang nilai == 1
						}
					}

					// Menghitung rata-rata untuk setiap penilai jika ada data
					if ($totalRows > 0) {
						$rataA = $rataA / $totalRows;
						$rataB = $rataB / $totalRows;
						$rataC = $rataC / $totalRows;
						$rataD = $rataD / $totalRows;
						$rataE = $rataE / $totalRows;
						$rataF = $rataF / $totalRows;

						// Menghitung rata-rata keseluruhan (rataAll)
						$rataAll = ($rataA + $rataB + $rataC + $rataD + $rataE + $rataF) / 6;
					} else {
						// Menangani jika tidak ada data yang memenuhi nilai == 1
						$rataA = $rataB = $rataC = $rataD = $rataE = $rataF = $rataAll = 0;
					}
					?>

					<table id="kt_table_2"
						style="font-family: Times New Roman; font-size: 15px; border-collapse: collapse; border: 1px solid black;">

						<thead>
							<tr>
								<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> No </font>
								</th>
								<th style="text-align:center; min-width: 100px; width: 20%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Deskripsi </font>
								</th>
								<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Nilai A </font>
								</th>
								<th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Masukan A </font>
								</th>
								<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Nilai B </font>
								</th>
								<th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Masukan B </font>
								</th>
								<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Nilai C </font>
								</th>
								<th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Masukan C </font>
								</th>
								<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Nilai D </font>
								</th>
								<th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Masukan D </font>
								</th>
								<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Nilai E </font>
								</th>
								<th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Masukan E </font>
								</th>
								<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Nilai F </font>
								</th>
								<th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF">
									<font color='#000000'> Masukan F </font>
								</th>
							</tr>
						</thead>

						<tbody>
							<?php
							$x = 1;
							$xy = 0;
							foreach ($data_detail2 as $row) {
								$x = $x + 1;
								$xy = $xy + 1;

								$id = encrypt($row->id);

								$nilai = $row->nilai;

							?>
								<tr>



									<?php if ($row->nilai == 1): ?>

								<tr>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><?= $xy ?></font>
									</td>
									<td class="nama" style="border: 1px solid black;">
										<font color='#000000'><strong><?= $row->deskripsi ?></strong></font>
									</td>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><strong><?= $row->nilaia ?></strong></font>
									</td>
									<td class="nama" style="border: 1px solid black;">
										<font color='#000000'><strong><?= $row->masukana ?></strong></font>
									</td>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><strong><?= $row->nilaib ?></strong></font>
									</td>
									<td class="nama" style="border: 1px solid black;">
										<font color='#000000'><strong><?= $row->masukanb ?></strong></font>
									</td>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><strong><?= $row->nilaic ?></strong></font>
									</td>
									<td class="nama" style="border: 1px solid black;">
										<font color='#000000'><strong><?= $row->masukanc ?></strong></font>
									</td>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><strong><?= $row->nilaid ?></strong></font>
									</td>
									<td class="nama" style="border: 1px solid black;">
										<font color='#000000'><strong><?= $row->masukand ?></strong></font>
									</td>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><strong><?= $row->nilaie ?></strong></font>
									</td>
									<td class="nama" style="border: 1px solid black;">
										<font color='#000000'><strong><?= $row->masukane ?></strong></font>
									</td>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><strong><?= $row->nilaif ?></strong></font>
									</td>
									<td class="nama" style="border: 1px solid black;">
										<font color='#000000'><strong><?= $row->masukanf ?></strong></font>
									</td>
								</tr>



							<?php else: ?>

								<td style="text-align:center; border: 1px solid black;">
									<font color='#000000'> <?= $xy ?>
								</td>
								<td style="border: 1px solid black;">
									<font color='#000000'> <?= $row->deskripsi ?>
								</td>

								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>
								<td style="border: 1px solid black;"></td>


							<?php endif; ?>


							</tr>
						<?php } ?>


						<tr>
							<td colspan="2" style="text-align:right; border: 1px solid black;">
								<font color='#000000'><strong>Rata-rata :</strong>
							</td>
							<td style="text-align:center;border: 1px solid black;">
								<font color='#000000'><strong><?= number_format($rataA, 2) ?></strong>
							</td>
							<td colspan="1" style="text-align:center"></td>
							<td style="text-align:center;border: 1px solid black;">
								<font color='#000000'><strong><?= number_format($rataB, 2) ?></strong>
							</td>
							<td colspan="1" style="text-align:center"></td>
							<td style="text-align:center;border: 1px solid black;">
								<font color='#000000'><strong><?= number_format($rataC, 2) ?></strong>
							</td>
							<td colspan="1" style="text-align:center"></td>
							<td style="text-align:center;border: 1px solid black;">
								<font color='#000000'><strong><?= number_format($rataD, 2) ?></strong>
							</td>
							<td colspan="1" style="text-align:center"></td>
							<td style="text-align:center;border: 1px solid black;">
								<font color='#000000'><strong><?= number_format($rataE, 2) ?></strong>
							</td>
							<td colspan="1" style="text-align:center"></td>
							<td style="text-align:center;border: 1px solid black;">
								<font color='#000000'><strong><?= number_format($rataF, 2) ?></strong>
							</td>
							<td style="text-align:center;border: 1px solid black;">
								<font color='#000000'><strong><?= number_format($rataAll, 2) ?></strong>
							</td>
						</tr>
						</tbody>

					</table>
				</div>


				<br><br>
				<div role="document">




					<br>
					<br>
				</div>
			</div>
		</div>

		<div class="card-body my-4">
			<div class="table-responsive">
				<h4>Penilaian Umum (Semester/Kontrak)</h4>

				<div style="max-width: 100%; overflow-x: auto;">
					<table class="table table-striped table-sm table-bordered table-hover" style="width: 100%; min-width: 1200px;">
						<thead>
							<tr>
								<th style="text-align:center" bgcolor="#C6DEFF" width="1%">No</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="15%">Indikator</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="25%">Keterangan</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%">Rata-rata Nilai</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$indikator = [

								1 => [

									'nama' => 'Inisiatif & Kreativitas',

									'ket' => 'a. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.<br>b. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama'

								],

								2 => [

									'nama' => 'Kepatuhan Peraturan',

									'ket' => 'a. Memahami setiap SOP pekerjaaannya.<br>b. Melaksanakan pekerjaannya sesuai SOP yang diberikan.<br>c. Memahami Peraturan dan Tata Tertib Perusahaan.<br>d. Melaksanakan pekerjaan sesuai SOP dan Peraturan/Tata Tertib Perusahaan.<br>e. Kepatuhan dalam menjalankan semua Peraturan/Tata Tertib Peraturan.'

								],

								3 => [

									'nama' => 'Analisa atas Masalah',

									'ket' => 'a. Mampu melakukan analisa atas trouble/problem yang dihadapi.<br>b. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.<br>c. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.'

								],

								4 => [

									'nama' => 'Komunikasi & Kerja sama tim',

									'ket' => 'a. Mampu berkoordinasi lintas fungsi.<br>b. Mampu berkomunikasi dengan baik secara internal maupun eksternal divisi maupun perusahaan.<br>c. Mampu berkomunikasi dengan efektif.<br>d. Kesesuaian lokasi komunikasi (Personal/Group).<br>e. Kemampuan membuat pelaporan on time.'

								],

								5 => [

									'nama' => 'Ketelitian, Administrasi dan Teknologi',

									'ket' => 'a. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan.<br>b. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.<br>c. Pelaksanaan pekerjaan secara efektif dan minim human error.'

								]

							];

							$totalNilaiPumum = 0;
							$jumlahPenilaiPumum = 0;

							foreach ($indikator as $no => $item) :
								// Hitung Rata-rata per Baris (Indikator)
								$totalBaris = 0;
								$countBaris = 0;

								foreach (range('a', 'f') as $penilai) {
									$nilai_field = "nilai{$penilai}{$no}";
									if (isset($penilaian_umum->$nilai_field) && is_numeric($penilaian_umum->$nilai_field)) {
										$totalBaris += (float)$penilaian_umum->$nilai_field;
										$countBaris++;
									}
								}
								$rataBaris = ($countBaris > 0) ? $totalBaris / $countBaris : 0;

								// Akumulasi untuk Total Akhir
								if ($rataBaris > 0) {
									$totalNilaiPumum += $rataBaris;
									$jumlahPenilaiPumum++;
								}
							?>
								<tr>
									<td style="text-align:center"><?= $no ?></td>
									<td><?= $item['nama'] ?></td>
									<td><?= $item['ket'] ?></td>
									<td style="text-align:center; font-weight:bold;">
										<?= ($rataBaris > 0) ? number_format($rataBaris, 2) : '-' ?>
									</td>
								</tr>
							<?php endforeach; ?>

							<?php
							$nilaiRataPumum = ($jumlahPenilaiPumum > 0) ? $totalNilaiPumum / $jumlahPenilaiPumum : 0;
							?>
							<tr class="fw-bold bg-light" style="background-color: #e0e0e0;">
								<td colspan="3" style="text-align:right; border: 1px solid black;">
									<font color='#000000'><strong>Total Rata-Rata Penilaian Umum :</strong>
								</td>
								<td style="text-align:center; border: 1px solid black;">
									<strong><?= number_format($nilaiRataPumum, 2) ?></strong>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>


<script>
	document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'evaluasi/pagination/detail_penilai',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6],
				className: 'text-center'
			}]
		})



		$(document).on('click', '.btn-approval', function() {
			var id = $('#id').val();

			Swal.fire({
				title: 'Kirim Notifikasi Evaluasi Pegawai?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'evaluasi/sendNotif',
						dataType: 'JSON',
						data: {
							id: id,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})

				}

			})
		})

		$(document).on('click', '.btn-selesai', function() {
			var id = $('#id').val();

			Swal.fire({
				title: 'Selesaikan Penilaian Evaluasi Pegawai?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'evaluasi/endPenilaian',
						dataType: 'JSON',
						data: {
							id: id,
							csrf_token: token
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