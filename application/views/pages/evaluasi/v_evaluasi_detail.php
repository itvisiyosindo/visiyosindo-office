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
		<!-- Employee Information Card -->
		<div class="card-body my-3">
			<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->pegawai ?></strong>
			<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->no_pegawai ?></strong>
			<br><strong class="fw-bold text-dark">Jabatan &nbsp; : <?= $data_job->jabatan ?></strong>
			<br><br>

			<?php if ($data_job->jenis_evaluasi == 1) { ?>
				<strong class="fw-bold text-dark">Jenis Evaluasi : Semester</strong>
				<br><strong class="fw-bold text-dark">Semester : <?= $data_job->smt ?></strong>
				<br><strong class="fw-bold text-dark">Tahun &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->tahun ?></strong>
			<?php } else { ?>
				<strong class="fw-bold text-dark">Jenis Evaluasi : Kontrak</strong>
			<?php } ?>

			<input type="hidden" id="id_po" value="<?= $data_job->id_pengguna ?>" />
			<?php $id = $data_job->id_po ?>
			<input type="hidden" name="id" id="id" value="<?= $data_job->id_po ?>">

			<hr>

			<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center bg-light p-3 rounded border">
				<div class="w-100">
					<strong class="d-block text-dark mb-2"><i class="fas fa-list-ol text-warning mr-1"></i> Indikator & Keterangan Nilai :</strong>
					<div class="d-flex flex-wrap gap-2" style="gap: 10px;">
						<span class="badge badge-dark p-2 mb-1">96–100 : Istimewa</span>
						<span class="badge badge-success p-2 mb-1">90–95 : Sangat Baik</span>
						<span class="badge badge-primary p-2 mb-1">85–89 : Baik</span>
						<span class="badge badge-info p-2 mb-1">80–84 : Cukup Baik</span>
						<span class="badge badge-warning text-white p-2 mb-1">75–79 : Cukup</span>
						<span class="badge badge-danger p-2 mb-1">70–74 : Kurang</span>
						<span class="badge badge-secondary p-2 mb-1">60–69 : Kurang Sekali</span>
					</div>
				</div>

				<div class="mt-3 mt-md-0 ml-md-3">
					<button type="button" class="btn btn-sm btn-outline-danger btn-petujuk text-nowrap" data-toggle="modal" data-target="#main-modal-indikatorPenilaian">
						<i class="fas fa-info-circle"></i> Detail Indikator
					</button>
				</div>
			</div>
		</div>
		<br>

		<?= form_open('evaluasi/updateEvDetail', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

		<div class="card-body">
			<div class="table-responsive">

				<!-- Status 1, 2, 3: Form Edit Penilai -->
				<?php if (in_array($data_job->status, [1, 2, 3])) { ?>
					<table id="kt_table_2" style="font-family: Times New Roman; color:black; font-size:15px;" border="1" width="100%">
						<thead>
							<tr>
								<th style="text-align:center" bgcolor="#C6DEFF" width="3%">
									<font color='#000000'>No</font>
								</th>
								<th style="text-align:center" bgcolor="#C6DEFF">
									<font color='#000000'>Deskripsi</font>
								</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%">
									<font color='#000000'>Penilai A</font>
								</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%">
									<font color='#000000'>Penilai B</font>
								</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%">
									<font color='#000000'>Penilai C</font>
								</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%">
									<font color='#000000'>Penilai D</font>
								</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%">
									<font color='#000000'>Penilai E</font>
								</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%">
									<font color='#000000'>Penilai F</font>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$xy = 0;
							foreach ($data_detail as $row) {
								$xy++;
								$id = encrypt($row->id);
							?>
								<tr>
									<?php if ($row->nilai == 1): ?>
										<td style="text-align:center">
											<input type="hidden" name="id_sodetail[]" value="<?= $id ?>">
											<font color='#000000'><?= $xy ?></font>
										</td>
										<td class="nama">
											<font color='#000000'><strong><?= $row->deskripsi ?></strong></font>
										</td>

										<?php
										$penilai_fields = ['penilaia', 'penilaib', 'penilaic', 'penilaid', 'penilaie', 'penilaif'];
										foreach ($penilai_fields as $field):
										?>
											<td>
												<select class="select-transaction input-group-sm form-control" name="<?= $field ?>[]">
													<option value="">Pilih</option>
													<?php foreach ($list_nama as $item): ?>
														<?php $selected = ($item->pengguna_id == $row->$field) ? 'selected' : ''; ?>
														<option value="<?= $item->pengguna_id ?>" <?= $selected ?>>
															<?= $item->nama ?>
														</option>
													<?php endforeach; ?>
												</select>
											</td>
										<?php endforeach; ?>

									<?php else: ?>
										<td style="text-align:center">
											<font color='#000000'><?= $xy ?></font>
										</td>
										<td class="nama">
											<font color='#000000'><?= $row->deskripsi ?></font>
										</td>
										<td colspan="6"></td>
									<?php endif; ?>
								</tr>
							<?php } ?>
						</tbody>
					</table>

					<!-- Status 4, 5: Tampilan Hasil Penilaian -->
				<?php } elseif (in_array($data_job->status, [4, 5])) { ?>
					<div style="overflow-x: auto; white-space: nowrap; max-width: 100%; max-width: 100%; overflow-x: auto; scrollbar-width:thin;">
						<?php
						// Perhitungan rata-rata per penilai
						$rataA = $rataB = $rataC = $rataD = $rataE = $rataF = 0;
						$countA = $countB = $countC = $countD = $countE = $countF = 0;

						foreach ($data_detail2 as $row) {
							if ($row->nilai == 1) {
								if (!is_null($row->nilaia)) {
									$rataA += $row->nilaia;
									$countA++;
								}
								if (!is_null($row->nilaib)) {
									$rataB += $row->nilaib;
									$countB++;
								}
								if (!is_null($row->nilaic)) {
									$rataC += $row->nilaic;
									$countC++;
								}
								if (!is_null($row->nilaid)) {
									$rataD += $row->nilaid;
									$countD++;
								}
								if (!is_null($row->nilaie)) {
									$rataE += $row->nilaie;
									$countE++;
								}
								if (!is_null($row->nilaif)) {
									$rataF += $row->nilaif;
									$countF++;
								}
							}
						}

						// Hitung rata-rata (Total Nilai / Jumlah Item)
						$rataA = $countA > 0 ? $rataA / $countA : 0;
						$rataB = $countB > 0 ? $rataB / $countB : 0;
						$rataC = $countC > 0 ? $rataC / $countC : 0;
						$rataD = $countD > 0 ? $rataD / $countD : 0;
						$rataE = $countE > 0 ? $rataE / $countE : 0;
						$rataF = $countF > 0 ? $rataF / $countF : 0;

						// Hitung rata-rata keseluruhan
						$sumRata = 0;
						$countAll = 0;
						$rates = [$rataA, $rataB, $rataC, $rataD, $rataE, $rataF];

						foreach ($rates as $rate) {
							if ($rate > 0) {
								$sumRata += $rate;
								$countAll++;
							}
						}

						$rataAll = ($countAll > 0) ? $sumRata / $countAll : 0;
						?>

						<table id="kt_table_2" style="font-family: Times New Roman; font-size: 15px; border-collapse: collapse; border: 1px solid black;">
							<thead>
								<tr>
									<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
										<font color='#000000'>No</font>
									</th>
									<th style="text-align:center; min-width: 100px; width: 20%; border: 1px solid black;" bgcolor="#C6DEFF">
										<font color='#000000'>Deskripsi</font>
									</th>
									<?php
									$penilai_labels = ['A', 'B', 'C', 'D', 'E', 'F'];
									foreach ($penilai_labels as $label):
									?>
										<th style="text-align:center; min-width: 120px; width: 10%; border: 1px solid black;" bgcolor="#C6DEFF">
											<font color='#000000'>Penilai <?= $label ?></font>
										</th>
										<th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF">
											<font color='#000000'>Nilai <?= $label ?></font>
										</th>
										<th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF">
											<font color='#000000'>Masukan <?= $label ?></font>
										</th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php
								$xy = 0;
								foreach ($data_detail2 as $row) {
									$xy++;
									$id = encrypt($row->id);
								?>
									<tr>
										<?php if ($row->nilai == 1): ?>
											<td style="text-align:center; border: 1px solid black;">
												<font color='#000000'><?= $xy ?></font>
											</td>
											<td class="nama" style="border: 1px solid black;">
												<font color='#000000'><strong><?= $row->deskripsi ?></strong></font>
											</td>
											<?php
											$penilai_data = [
												['nama' => $row->namaa, 'nilai' => $row->nilaia, 'masukan' => $row->masukana],
												['nama' => $row->namab, 'nilai' => $row->nilaib, 'masukan' => $row->masukanb],
												['nama' => $row->namac, 'nilai' => $row->nilaic, 'masukan' => $row->masukanc],
												['nama' => $row->namad, 'nilai' => $row->nilaid, 'masukan' => $row->masukand],
												['nama' => $row->namae, 'nilai' => $row->nilaie, 'masukan' => $row->masukane],
												['nama' => $row->namaf, 'nilai' => $row->nilaif, 'masukan' => $row->masukanf]
											];

											foreach ($penilai_data as $penilai):
											?>
												<td class="nama" style="border: 1px solid black;">
													<font color='#000000'><strong><?= $penilai['nama'] ?></strong></font>
												</td>
												<td style="text-align:center; border: 1px solid black;">
													<font color='#000000'><strong><?= $penilai['nilai'] ?></strong></font>
												</td>
												<td class="nama" style="border: 1px solid black;">
													<font color='#000000'><strong><?= $penilai['masukan'] ?></strong></font>
												</td>
											<?php endforeach; ?>
										<?php else: ?>
											<td style="text-align:center; border: 1px solid black;">
												<font color='#000000'><?= $xy ?></font>
											</td>
											<td style="border: 1px solid black;">
												<font color='#000000'><?= $row->deskripsi ?></font>
											</td>
											<?php for ($i = 0; $i < 18; $i++): ?>
												<td style="border: 1px solid black;"></td>
											<?php endfor; ?>
										<?php endif; ?>
									</tr>
								<?php } ?>

								<!-- Baris Rata-rata -->
								<tr>
									<td colspan="3" style="text-align:right; border: 1px solid black;">
										<font color='#000000'><strong>Rata-rata :</strong></font>
									</td>
									<?php
									$rates_display = [$rataA, $rataB, $rataC, $rataD, $rataE, $rataF];
									foreach ($rates_display as $rate):
									?>
										<td style="text-align:center; border: 1px solid black;">
											<font color='#000000'><strong><?= number_format($rate, 2) ?></strong></font>
										</td>
										<td colspan="2" style="text-align:center"></td>
									<?php endforeach; ?>
									<td style="text-align:center; border: 1px solid black;">
										<font color='#000000'><strong><?= number_format($rataAll, 2) ?></strong></font>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php } ?>

				<!-- Action Buttons -->
				<br><br>
				<div role="document">
					<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>

					<?php if (in_array($data_job->status, [4, 5])): ?>
						<a href="evaluasi/print_page/evaluasi/<?= encrypt($data_job->id_po) ?>"
							class="btn btn-warning float-left"
							style="margin-left: 10px;"
							target="_blank"
							rel="noopener noreferrer">
							<i class="fas fa-print"></i> Cetak
						</a>
					<?php endif; ?>

					<?php if (in_array($data_job->status, [1, 2, 3])): ?>
						<button type="button" class="btn btn-success btn-save float-right">Simpan</button>
					<?php endif; ?>

					<?php if (in_array($data_job->status, [2, 3])): ?>
						<button type="button"
							class="btn btn-success float-right btn-approval"
							style="margin-right: 10px;"
							id-Sijk="<?= encrypt($stock_opname[0]->id_so) ?>">
							<i class="fas fa-check"></i> Kirim Notif
						</button>
					<?php endif; ?>

					<?php if ($data_job->status == 4): ?>
						<button type="button"
							class="btn btn-success float-right btn-selesai"
							style="margin-right: 10px;"
							id-Sijk="<?= encrypt($stock_opname[0]->id_so) ?>">
							<i class="fas fa-check"></i> Selesai
						</button>
					<?php endif; ?>

					<br><br>
				</div>
			</div>
		</div>

		<?= form_close(); ?>

		<!-- Periode Evaluasi & Riwayat -->
		<div class="row my-3">
			<div class="col-md-12 my-2">
				<section class="card card-featured-left card-featured-primary">
					<div class="card-body">
						<div class="widget-summary">
							<div class="widget-summary-col widget-summary-col-icon">
								<div class="summary-icon bg-primary">
									<i class="fas fa-calendar-alt"></i>
								</div>
							</div>
							<div class="widget-summary-col">
								<div class="summary">
									<h4 class="title">Periode Evaluasi</h4>
									<div class="info">
										<strong class="amount">
											SEMESTER <?= $data_job->smt ?> - TAHUN <?= $data_job->tahun ?>
										</strong>
										<span class="text-primary">
											(<?= ($data_job->smt == 1) ? 'Januari - Juni' : 'Juli - Desember'; ?>)
										</span>
									</div>
								</div>
								<div class="summary-footer">
									<div class="form-group row">
										<label class="col-lg-3 control-label text-lg-right pt-2">Lihat Riwayat:</label>
										<div class="col-lg-6">
											<select class="form-control form-control-sm" onchange="if (this.value) window.location.href=this.value">
												<option value="">-- Pilih Semester Lain --</option>
												<?php foreach ($riwayat_evaluasi as $riwayat): ?>
													<?php
													$isSelected = ($riwayat->id == $data_job->id_po) ? 'selected' : '';
													$link = base_url('evaluasi/show/detail/evaluasi/' . encrypt($riwayat->id));
													$labelStatus = ($riwayat->status == 5) ? '✅ Selesai' : '⏳ Proses';
													$labelSmt = ($riwayat->jenis_evaluasi == 1) ? 'Semester' : 'Kontrak';
													?>
													<option value="<?= $link ?>" <?= $isSelected ?>>
														<?= $labelSmt ?> <?= $riwayat->smt ?> - <?= $riwayat->tahun ?> (<?= $labelStatus ?>)
													</option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>

			<!-- FORM PENILAIAN EVALUASI UMUM -->
			<div class="col-md-12">

				<section class="card card-featured-left card-featured-muted">

					<div class="card-body my-4">

						<div class="card-content">

							<div class="d-flex justify-content-between align-items-center py-1">

								<h3 class="text-dark">Penilaian Umum (Semester/Kontrak)</h3>

							</div>



							<?= form_open('evaluasi/savePenilaianUmum', 'id="formPenilaianUmum"'); ?>

							<input type="hidden" name="id_evaluasi" value="<?= $data_job->id_po; ?>">



							<?php

							// ============================================================

							// LOGIC 1: MENENTUKAN NAMA PENILAI DI HEADER

							// ============================================================

							// Default nama penilai (jika belum ada atau status masih 1)

							$nama_penilai = [

								'a' => 'Penilai A',
								'b' => 'Penilai B',
								'c' => 'Penilai C',

								'd' => 'Penilai D',
								'e' => 'Penilai E',
								'f' => 'Penilai F'

							];



							// Jika status sudah 4 (Nilai Diinput) atau 5 (Selesai), ambil nama dari database

							if (in_array($data_job->status, [4, 5]) && !empty($data_detail2)) {

								// Kita ambil sample dari baris pertama data_detail2 karena nama penilai sama untuk satu evaluasi

								$rowSample = $data_detail2[0];

								if (!empty($rowSample->namaa)) $nama_penilai['a'] = $rowSample->namaa;

								if (!empty($rowSample->namab)) $nama_penilai['b'] = $rowSample->namab;

								if (!empty($rowSample->namac)) $nama_penilai['c'] = $rowSample->namac;

								if (!empty($rowSample->namad)) $nama_penilai['d'] = $rowSample->namad;

								if (!empty($rowSample->namae)) $nama_penilai['e'] = $rowSample->namae;

								if (!empty($rowSample->namaf)) $nama_penilai['f'] = $rowSample->namaf;
							}



							// ============================================================

							// LOGIC 2: DISABLE INPUT JIKA STATUS MASIH 'BARU DIAJUKAN' (1)

							// ============================================================

							// Jika status 1, input dimatikan (disabled) dan diberi warna abu-abu

							$isDisabled = ($data_job->status == 1) ? 'disabled style="background-color: #e9ecef;"' : 'style="background-color: #ffffff;"';



							// Pesan peringatan jika disabled

							if ($data_job->status == 1) {

								echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Form Penilaian Umum terkunci karena Evaluasi masih berstatus <b>Baru Diajukan</b>. Silahkan pilih Penilai di form atas terlebih dahulu.</div>';
							}

							?>



							<div style="max-width: 100%; overflow-x: auto; scrollbar-width:thin;">

								<table class="table table-striped table-sm table-bordered table-hover"

									style="width: 100%; table-layout: auto; min-width: 2000px;">

									<thead>
										<tr>
											<th style="text-align:center" bgcolor="#C6DEFF" width="1%">No</th>
											<th style="text-align:center" bgcolor="#C6DEFF" width="5%">Indikator</th>
											<th style="text-align:center" bgcolor="#C6DEFF" width="15%">Keterangan</th>
											<?php
											$penilai_labels = ['a', 'b', 'c', 'd', 'e', 'f'];
											foreach ($penilai_labels as $label):
												// Label Huruf Kapital (A, B, C...)
												$labelUpper = strtoupper($label);

												// Nama Penilai (dari logic sebelumnya)
												$displayName = $nama_penilai[$label];

												// Styling font kecil jika nama kepanjangan
												$thStyle = (strlen($displayName) > 15) ? 'font-size: 12px;' : '';
											?>
												<th style="text-align:center" bgcolor="#C6DEFF" width="3%">
													Nilai <br>
													<strong>Penilai <?= $labelUpper ?></strong> <br>
													<span class="badge badge-light text-dark" style="<?= $thStyle ?>"><?= $displayName ?></span>
												</th>
												<th style="text-align:center" bgcolor="#C6DEFF" width="5%">
													Catatan <br>
													<strong>Penilai <?= $labelUpper ?></strong> <br>
													<span class="badge badge-light text-dark" style="<?= $thStyle ?>"><?= $displayName ?></span>
												</th>
											<?php endforeach; ?>
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



										foreach ($indikator as $no => $item):

										?>

											<tr>

												<td style="text-align:center"><?= $no ?></td>

												<td style="text-align:center"><?= $item['nama'] ?></td>

												<td style="text-align:left; font-size: 13px; white-space: normal; min-width: 350px;">
													<?= $item['ket'] ?>
												</td>
												<?php foreach (range('a', 'f') as $penilai): ?>

													<?php

													$nilai_field = "nilai{$penilai}{$no}";

													$catatan_field = "catatan{$penilai}{$no}";

													$nilai_val = $penilaian_umum->$nilai_field ?? '';

													$catatan_val = $penilaian_umum->$catatan_field ?? '';

													?>

													<td>

														<input class="form-control"

															type="number"

															min="1"

															max="100"

															step="1"

															name="<?= $nilai_field ?>"

															value="<?= htmlspecialchars($nilai_val) ?>"

															<?= $isDisabled ?>

															onkeypress="return event.charCode >= 48 && event.charCode <= 57"

															oninput="if(this.value > 100) this.value = 100;">

													</td>

													<td>

														<input class="form-control"

															type="text"

															name="<?= $catatan_field ?>"

															value="<?= htmlspecialchars($catatan_val) ?>"

															<?= $isDisabled ?>>

													</td>

												<?php endforeach; ?>

											</tr>

										<?php endforeach; ?>



										<tr class="fw-bold bg-light">

											<td colspan="3" style="text-align:right; border: 1px solid black;">

												<font color='#000000'><strong>Rata-rata :</strong></font>

											</td>

											<?php

											$totalNilaiPumum = 0;

											$jumlahPenilaiPumum = 0;



											foreach (range('a', 'f') as $penilai):

												$total = 0;

												$count = 0;



												for ($i = 1; $i <= 5; $i++) {

													$field = "nilai{$penilai}{$i}";

													if (isset($penilaian_umum->$field) && is_numeric($penilaian_umum->$field)) {

														$total += (float)$penilaian_umum->$field;

														$count++;
													}
												}



												$rata = ($count > 0) ? $total / $count : 0;



												if ($rata > 0) {

													$totalNilaiPumum += $rata;

													$jumlahPenilaiPumum++;
												}

											?>

												<td style="text-align:center"><strong><?= number_format($rata, 2) ?></strong></td>

												<td></td>

											<?php endforeach; ?>

										</tr>



										<?php

										$nilaiRataPumum = ($jumlahPenilaiPumum > 0) ? $totalNilaiPumum / $jumlahPenilaiPumum : 0;

										?>

										<tr class="fw-bold bg-light" style="background-color: #e0e0e0;">

											<td colspan="14" style="text-align:right; border: 1px solid black;">

												<font color='#000000'><strong>Rata-Rata Penilaian Umum :</strong></font>

											</td>

											<td colspan="1" style="text-align:center">

												<strong><?= number_format($nilaiRataPumum, 2) ?></strong>

											</td>

										</tr>

									</tbody>

								</table>

							</div>



							<?php

							// Perhitungan Rangkuman Nilai (Sama seperti code sebelumnya)

							$rata_rata_jobdesc = isset($rataAll) ? $rataAll : 0;

							$rata_rata_umum = isset($nilaiRataPumum) ? $nilaiRataPumum : 0;

							$nilai_laporan_jobdesc = $rata_rata_jobdesc;

							$nilai_penilaian_umum = $rata_rata_umum;

							$nilai_rata_rata_total = 0;

							$count_rata_rata = 0;



							if ($nilai_laporan_jobdesc > 0) {
								$nilai_rata_rata_total += $nilai_laporan_jobdesc;
								$count_rata_rata++;
							}

							if ($nilai_penilaian_umum > 0) {
								$nilai_rata_rata_total += $nilai_penilaian_umum;
								$count_rata_rata++;
							}

							$rata_rata_keseluruhan = ($count_rata_rata > 0) ? $nilai_rata_rata_total / $count_rata_rata : 0;

							$pencapaian = $rata_rata_keseluruhan;

							?>



							<div class="row my-4 text-nowrap">

								<div class="col-md-12">

									<h4 class="fw-bold text-dark">Rangkuman Penilaian :</h4>

									<hr>

									<div class="summary-details p-3"

										style="font-size: 14px; background-color: #f7f7f7; border: 1px solid #ddd; width: 50%;">

										<table style="width: 50%;" class="text-dark">

											<tr>

												<td style="width: 40%; padding-right: 10px;">Nilai Laporan</td>

												<td style="width: 5%;" class="px-1">:</td>

												<td style="width: 55%;"><strong><?= number_format($nilai_laporan_jobdesc, 2) ?></strong></td>

											</tr>

											<tr>

												<td style="width: 40%; padding-right: 10px;">Penilaian Umum</td>

												<td style="width: 5%;" class="px-1">:</td>

												<td style="width: 55%;"><strong><?= number_format($nilai_penilaian_umum, 2) ?></strong></td>

											</tr>

											<tr style="border-top: 1px solid #ccc;">

												<td style="width: 40%; padding-right: 10px;">Rata-rata (Laporan + Penilaian Umum)</td>

												<td style="width: 5%;" class="px-1">:</td>

												<td style="width: 55%;"><strong><?= number_format($rata_rata_keseluruhan, 2) ?></strong></td>

											</tr>

											<tr>

												<td style="width: 40%; padding-right: 10px;">Pencapaian</td>

												<td style="width: 5%;" class="px-1">:</td>

												<td style="width: 55%;"><strong><?= number_format($pencapaian, 2) ?></strong></td>

											</tr>

										</table>

									</div>

									<hr>

								</div>

							</div>
							<div class="d-flex justify-content-center">

								<?php if ($data_job->status != 1): ?>

									<button type="submit" class="btn btn-primary" id="btnSimpanPenilaianUmum">

										<i class="fa fa-save"></i> Simpan Penilaian Umum

									</button>

								<?php endif; ?>

							</div>

							<?= form_close(); ?>

						</div>

					</div>

				</section>

				<div id="main-modal-indikatorPenilaian" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
					<div class="modal-dialog modal-custom modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header bg-dark text-light">
								<h5 id="modal-label" class="mb-0 d-flex justify-content-between w-100 align-items-center">
									<span><i class="flaticon2-avatar icon-2x text-grey-light"></i> Indikator Skala Penilaian 60–100</span>
									<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;">
										<span aria-hidden="true">&times;</span>
									</button>
								</h5>
							</div>

							<div class="modal-body">
								<div class="table-responsive">
									<table class="table table-bordered table-striped">
										<thead class="bg-light">
											<tr>
												<th style="width: 20%; text-align: center; background-color: #C7C6C1;">Nilai</th>
												<th style="text-align: center; background-color: #C7C6C1;">Indikator Penilaian</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="text-align: center;">96 – 100 <br> Istimewa (Outstanding) </td>
												<td>
													Kinerja luar biasa, melebihi semua target yang ditetapkan. Sangat proaktif, menunjukkan inisiatif tinggi, kepemimpinan kuat, dan menjadi panutan bagi rekan kerja.
												</td>
											</tr>
											<tr>
												<td style="text-align: center;">90 – 95 <br> Sangat Baik (Excellent)</td>
												<td>
													Kinerja sangat memuaskan. Mencapai semua target dan sering kali melebihi ekspektasi. Disiplin, mandiri, dan berkinerja konsisten.
												</td>
											</tr>
											<tr>
												<td style="text-align: center;">85 – 89 <br> Baik (Very Good)</td>
												<td>
													Kinerja baik dan memenuhi seluruh target kerja. Terkadang menunjukkan inisiatif, bisa diandalkan dan bekerja efektif dengan pengawasan minimal.
												</td>
											</tr>
											<tr>
												<td style="text-align: center;">80 – 84 <br> Cukup Baik (Good)</td>
												<td>
													Umumnya memenuhi target kerja, meskipun masih ada beberapa aspek yang perlu ditingkatkan. Perlu dorongan untuk lebih proaktif dan konsisten.
												</td>
											</tr>
											<tr>
												<td style="text-align: center;">75 – 79 <br> Cukup (Fair)</td>
												<td>
													Kinerja memenuhi sebagian besar harapan, namun ada beberapa kekurangan yang perlu diperbaiki. Memerlukan pemantauan dan bimbingan lebih lanjut.
												</td>
											</tr>
											<tr>
												<td style="text-align: center;">70 – 74 <br> Kurang (Below Average)</td>
												<td>
													Beberapa target tidak tercapai. Kurang inisiatif, kurang akurat dalam bekerja, dan membutuhkan peningkatan dalam beberapa aspek penting.
												</td>
											</tr>
											<tr>
												<td style="text-align: center;">60 – 69 <br> Kurang Sekali (Poor)</td>
												<td>
													Kinerja jauh di bawah standar yang ditetapkan. Gagal mencapai sebagian besar target, kurang tanggung jawab, dan perlu evaluasi menyeluruh untuk perbaikan
												</td>
											</tr>

										</tbody>
									</table>
								</div>
							</div>

							<div class="modal-footer">
								<div class="is_aktif"></div>
								<input type="hidden" id="id_pelanggan" name="id_pelanggan">
								<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>


<script>
	document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();

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

		$(document).on('click', '.btn-petujuk', function() {
			$('#main-modal-indikatorPenilaian').modal('show');
		});

		// Script untuk menyimpan Form Penilaian Umum dengan Konfirmasi SweetAlert
		$('#formPenilaianUmum').submit(function(e) {
			e.preventDefault(); // Mencegah form submit langsung

			var form = $(this);
			var url = form.attr('action');
			var data = form.serialize();

			// Tampilkan Konfirmasi Dulu
			Swal.fire({
				title: 'Simpan Penilaian Umum?',
				text: "Pastikan data yang diinput sudah benar.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Simpan',
				cancelButtonText: 'Batal'
			}).then(function(result) {

				// Jika user klik "Ya" (result.value bernilai true)
				if (result.value) {

					// Baru jalankan proses AJAX
					$.ajax({
						type: 'POST',
						url: url,
						data: data,
						dataType: 'json',
						beforeSend: function() {
							// Hapus blockUI(), ganti dengan disable tombol
							$('#btnSimpanPenilaianUmum').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
						},
						success: function(response) {
							if (response.status == 'success') {
								Swal.fire({
									title: 'Berhasil!',
									text: response.message,
									icon: 'success',
									timer: 1500, // Otomatis tutup setelah 1.5 detik
									showConfirmButton: false
								}).then(function() {
									location.reload(); // Muat ulang halaman
								});
							} else {
								Swal.fire('Gagal!', response.message || 'Terjadi kesalahan', 'error');

								// Kembalikan tombol jika gagal
								$('#btnSimpanPenilaianUmum').attr('disabled', false).html('<i class="fa fa-save"></i> Simpan Penilaian Umum');
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							Swal.fire('Error!', 'Gagal menghubungi server: ' + thrownError, 'error');

							// Kembalikan tombol jika error server
							$('#btnSimpanPenilaianUmum').attr('disabled', false).html('<i class="fa fa-save"></i> Simpan Penilaian Umum');
						}
					});

				}
			});
		});
	})

	function goBack() {
		window.history.back();
	}
</script>