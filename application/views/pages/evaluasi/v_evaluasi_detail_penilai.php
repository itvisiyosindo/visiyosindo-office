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
			
			<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center bg-light p-3 rounded border my-2">
				<div class="w-100">
					<strong class="d-block text-dark mb-2"><i class="fas fa-list-ol text-warning mr-1"></i> Indikator Keterangan Nilai :</strong>
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



		<?= form_open('evaluasi/updateEvDetailPenilai', array('id' => 'main-form', 'autocomplete' => 'off')); ?>



		<div class="card-body">

			<div class="table-responsive">

				<strong style="font-size: 16px;">I. Penilaian Jobdesc</strong><br><br>

				<table id="kt_table_2" class="table table-bordered table-striped table-sm" style="font-family:Times New Roman; color:black; font-size:15px;" width="100%">

					<thead>

						<tr>

							<th style="text-align:center" bgcolor="#C6DEFF" width="3%">
								<font color='#000000'> No</font>
							</th>

							<th style="text-align:center" bgcolor="#C6DEFF">
								<font color='#000000'> Deskripsi</font>
							</th>

							<th style="text-align:center" bgcolor="#C6DEFF" width="15%">
								<font color='#000000'> Nilai (1-100)</font>
							</th>

							<th style="text-align:center" bgcolor="#C6DEFF" width="30%">
								<font color='#000000'> Masukan</font>
							</th>

						</tr>

					</thead>

					<tbody>

						<?php

						$x = 1;

						$xy = 0;

						// Variabel untuk menyimpan Kode Penilai User yang sedang login (a/b/c/d/e/f)

						$my_penilai_code = '';



						foreach ($data_detail as $row) {

							$x = $x + 1;

							$xy = $xy + 1;

							$id = $row->id;



							// Deteksi User Login sebagai Penilai apa

							if (sessPenggunaId() == $row->penilaia) $my_penilai_code = 'a';

							elseif (sessPenggunaId() == $row->penilaib) $my_penilai_code = 'b';

							elseif (sessPenggunaId() == $row->penilaic) $my_penilai_code = 'c';

							elseif (sessPenggunaId() == $row->penilaid) $my_penilai_code = 'd';

							elseif (sessPenggunaId() == $row->penilaie) $my_penilai_code = 'e';

							elseif (sessPenggunaId() == $row->penilaif) $my_penilai_code = 'f';

						?>

							<tr>

								<?php if ($row->nilai == 1): ?>

									<td style="text-align:center"><input type="hidden" name="id_sodetail[]" value="<?= $id ?>">

										<font color='#000000'> <?= $xy ?></font>

									</td>

									<td class="nama">

										<font color='#000000'><strong><?= $row->deskripsi ?></strong></font>

									</td>



									<?php

									// Helper function simple untuk generate input

									// REVISI: Menggunakan Input Number 1-100, bukan Select

									$code = '';

									$val_nilai = '';

									$val_masukan = '';



									if (sessPenggunaId() == $row->penilaia) {
										$code = 'a';
										$val_nilai = $row->nilaia;
										$val_masukan = $row->masukana;
									} elseif (sessPenggunaId() == $row->penilaib) {
										$code = 'b';
										$val_nilai = $row->nilaib;
										$val_masukan = $row->masukanb;
									} elseif (sessPenggunaId() == $row->penilaic) {
										$code = 'c';
										$val_nilai = $row->nilaic;
										$val_masukan = $row->masukanc;
									} elseif (sessPenggunaId() == $row->penilaid) {
										$code = 'd';
										$val_nilai = $row->nilaid;
										$val_masukan = $row->masukand;
									} elseif (sessPenggunaId() == $row->penilaie) {
										$code = 'e';
										$val_nilai = $row->nilaie;
										$val_masukan = $row->masukane;
									} elseif (sessPenggunaId() == $row->penilaif) {
										$code = 'f';
										$val_nilai = $row->nilaif;
										$val_masukan = $row->masukanf;
									}



									if ($code != ''):

									?>

										<td>

											<input type="number" class="form-control" name="nilai<?= $code ?>[<?= $id ?>]" value="<?= $val_nilai ?>" min="1" max="100" required placeholder="0-100">

										</td>

										<td>

											<input class="form-control" type="text" name="masukan<?= $code ?>[<?= $id ?>]" value="<?= $val_masukan ?>">

										</td>

									<?php else: ?>

										<td></td>
										<td></td>

									<?php endif; ?>



								<?php else: ?>

									<td style="text-align:center">
										<font color='#000000'> <?= $xy ?></font>
									</td>

									<td class="nama">
										<font color='#000000'> <?= $row->deskripsi ?></font>
									</td>

									<td></td>

									<td></td>

								<?php endif; ?>

							</tr>

						<?php } ?>

					</tbody>

				</table>

			</div>



			<?php if ($my_penilai_code != ''): ?>

				<br>

				<div class="table-responsive">

					<strong style="font-size: 16px;">II. Penilaian Umum</strong><br>

					<span class="text-muted">*Harap diisi sesuai pengamatan Anda (Skala 1 - 100)</span><br><br>



					<input type="hidden" name="id_evaluasi_umum" value="<?= $data_job->id_po; ?>">

					<input type="hidden" name="my_penilai_code" value="<?= $my_penilai_code; ?>">



					<table class="table table-bordered table-striped table-sm" width="100%">

						<thead>

							<tr>

								<th style="text-align:center" bgcolor="#C6DEFF" width="5%">No</th>

								<th style="text-align:center" bgcolor="#C6DEFF" width="25%">Indikator</th>

								<th style="text-align:center" bgcolor="#C6DEFF">Keterangan</th>

								<th style="text-align:center" bgcolor="#C6DEFF" width="15%">Nilai (1-100)</th>

								<th style="text-align:center" bgcolor="#C6DEFF" width="25%">Catatan</th>

							</tr>

						</thead>

						<tbody>

							<?php

							// Ambil data penilaian umum yang sudah ada (jika edit)

							// Kita perlu load model penilaian umum di Controller untuk dikirim kesini, 

							// Asumsi: di controller sudah diload ke $page_data['penilaian_umum'] (lihat Controller update di bawah)

							// Jika belum ada, $penilaian_umum adalah object kosong



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

								// Nama field dinamis, misal: nilaia1, nilaia2, dst.

								$field_nilai = 'nilai' . $my_penilai_code . $no;

								$field_catatan = 'catatan' . $my_penilai_code . $no;



								// Ambil value existing jika ada

								$val_nilai_umum = isset($penilaian_umum->$field_nilai) ? $penilaian_umum->$field_nilai : '';

								$val_catatan_umum = isset($penilaian_umum->$field_catatan) ? $penilaian_umum->$field_catatan : '';

							?>

								<tr>

									<td class="text-center"><?= $no ?></td>

									<td><b><?= $item['nama'] ?></b></td>

									<td style="text-align:left; font-size: 13px; white-space: normal; min-width: 350px;">
										<?= $item['ket'] ?>
									</td>

									<td>

										<input type="number" class="form-control" name="umum_nilai_<?= $no ?>" value="<?= $val_nilai_umum ?>" min="1" max="100" required placeholder="0-100">

									</td>

									<td>

										<input type="text" class="form-control" name="umum_catatan_<?= $no ?>" value="<?= $val_catatan_umum ?>" placeholder="Catatan...">

									</td>

								</tr>

							<?php endforeach; ?>

						</tbody>

					</table>

				</div>

			<?php endif; ?>



			<br><br>

			<div role="document">

				<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left">Kembali</button>

				<?php if ($data_job->status != 5) { ?>

					<button type="button" class="btn btn-success btn-save float-right">Simpan</button>

				<?php } ?>

				<br><br>

			</div>

		</div>



		<?= form_close(); ?>

	</div>
	
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



<script>
	document.addEventListener('DOMContentLoaded', function() {

		// Validasi Client Side agar max 100

		$('input[type="number"]').on('input', function() {

			var value = parseInt($(this).val());

			if (value > 100) {

				$(this).val(100);

				Swal.fire('Maksimal Nilai 100', '', 'warning');

			}

		});
		
		$(document).on('click', '.btn-petujuk', function() {
			$('#main-modal-indikatorPenilaian').modal('show');
		});



		$(document).on('click', '.btn-save', function(e) {

			e.preventDefault();

			var form = $('#main-form');



			Swal.fire({

				title: 'Simpan Penilaian?',

				text: "Pastikan nilai Jobdesc dan Evaluasi Umum sudah terisi.",

				icon: 'question',

				showCancelButton: true,

				confirmButtonText: 'Ya, Simpan',

				cancelButtonText: 'Batal'

			}).then(function(result) {

				if (result.value) {

					$.ajax({

						url: form.attr('action'),

						type: 'POST',

						data: form.serialize(),

						dataType: 'JSON',

						success: function(resp) {

							handleResponse(resp);

						}

					});

				}

			});

		});

	});



	function goBack() {

		window.history.back();

	}
</script>