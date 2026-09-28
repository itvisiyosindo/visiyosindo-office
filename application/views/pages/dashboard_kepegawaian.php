<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<?php
// Helper function untuk mencegah division by zero
if (!function_exists('safe_divide')) {
    function safe_divide($numerator, $denominator, $default = 0) {
        return ($denominator != 0) ? ($numerator / $denominator) : $default;
    }
}
?>

<style>
    table.table-border-hitam,
    table.table-border-hitam th,
    table.table-border-hitam td {
        border: 1px solid black !important;
    }

    table.table-border-hitam thead {
        background-color: #d3d3d3;
        text-align: center;
        vertical-align: middle;
    }

		/* Baris ganjil: abu terang */
		.table-border-hitam tbody tr:nth-child(odd) td {
				background-color: #f9f9f9;
		}

		/* Baris genap: putih (atau biarkan default) */
		.table-border-hitam tbody tr:nth-child(even) td {
				background-color: #ffffff;
		}
</style>

<div class="row">
  <?php if (sessPenggunaId() == '58' || sessPenggunaId() == '1' || isGa() || isAdmin()) { ?>
		<div class="col-md-12 mb-4">
			<div class="card card-modern border border-primary">
				<div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-2 px-3" style="background: #1e293b; color: #fff; padding: 10px 15px; border-radius: 6px 6px 0 0;">
					<h4 class="card-title text-white my-0" style="font-size: 15px; font-weight: 700; color: #ffffff; margin: 0;">
						<i class="fa fa-clipboard-check text-info"></i> Modul Audit Internal PBOK & Pembelian Aset (PPA)
					</h4>
					<a href="<?= base_url('audit/index.html') ?>" target="_blank" class="btn btn-sm btn-info text-white" style="font-size: 12px; font-weight: 600;">
						<i class="fa fa-external-link"></i> Buka Layar Penuh
					</a>
				</div>
				<div class="card-body p-0" style="padding: 0;">
					<iframe 
						src="<?= base_url('audit/index.html?user_id=58&role=GeneralAffair') ?>" 
						style="width: 100%; height: 800px; border: none; border-radius: 0 0 6px 6px;"
						title="Modul Audit PBOK PPA Visi Yosindo Medikal">
					</iframe>
				</div>
			</div>
		</div>
  <?php } ?>

  <?php if (sessPenggunaId()=='1' || sessPenggunaId()=='54' || sessPenggunaId()=='69' || sessPenggunaId()=='58') { ?>

		<div class="col-md-12">
			<div class="card-body">
				<form method="get">
						<div class="form-row d-flex align-items-end">
								<div class="col-md-2">
										<small>Pilih Bulan:</small>
										<div class="input-group">
												<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-calendar"></i></span>
												</div>
												<input type="text" name="bulan"
															data-plugin-datepicker
															data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}'
															class="form-control"
															id="filter_bulan"
															placeholder="Pilih Bulan"
															value="<?= isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m') ?>"
															required>
										</div>
								</div>

								<div class="col-auto">
										<button type="submit" class="btn btn-outline-secondary"><i class="fa fa-filter"></i> Filter</button>
								</div>
						</div>
				</form>

			</br>

			<div class="card-body">
				<div class="text-center">
					<h4>Rekap Nilai pada Bulan <strong> <?= $bulanIni ?> </strong> </h4>
				</div>

				<!-- Tambahkan wrapper responsif di sini -->
				<div class="table-responsive">
					<!--<table class="table table-bordered">-->
					<table class="table table-bordered table-border-hitam">

						<thead style="background-color: #d3d3d3; text-align: center; vertical-align: middle;">
							<tr>
									<th rowspan="3" style="vertical-align: middle;">No</th>
									<th rowspan="3" style="vertical-align: middle;">Nama</th>
									<th colspan="<?= count($data_mingguan) * 4 ?>">Periode</th>
							</tr>
							<tr>
									<?php foreach ($data_mingguan as $minggu): ?>
											<th colspan="4"><?= $minggu['periode'] ?></th>
									<?php endforeach; ?>
							</tr>
							<tr>
									<?php foreach ($data_mingguan as $minggu): ?>
											<th style="vertical-align: middle;">Laporan</th>
											<th style="vertical-align: middle;">Penilaian Umum</th>
											<th style="vertical-align: middle;">Laporan + Penilaian Umum</th>
											<th style="vertical-align: middle;">Pencapaian</th>
									<?php endforeach; ?>
							</tr>
					</thead>

						<tbody>
							<?php $no = 1; foreach ($list_pengguna as $pengguna): ?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $pengguna->nama ?></td>
									<?php foreach ($data_mingguan as $minggu): ?>
										<td style="text-align:center"><?= $minggu['nilai_laporan_adm'][$pengguna->pengguna_id] ?? '-' ?></td>
										<td style="text-align:center"><?= $minggu['nilai_penilaianumum_adm'][$pengguna->pengguna_id] ?? '-' ?></td>
										<td style="text-align:center"><?= $minggu['rata_lap_adms'][$pengguna->pengguna_id] ?? '-' ?></td>
										<td style="text-align:center"><?= $minggu['nilai_pencapaian_adm'][$pengguna->pengguna_id] ?? '-' ?></td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>


			
				
			</div>
		</div>



	<div class="col-md-6">
		<div class="text-center">
			<h2>Log Aktivitas Anda</h2>
		</div>
		<div class="card-body">
			<div class="row form-group col-md-4">
				<small>Filter By Month:</small>
				<div class="input-group">
					<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
					<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Pengguna</th>
							<th> Aksi </th>
							<th> Keterangan </th>
							<th> Tanggal </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="text-center">
			<h2>&nbsp;</h2>
		</div>
			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-hadir">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $present[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-success line-height-2 my-0">Karyawan <strong>Hadir &uarr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>


			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-izin">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $izin[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Izin &darr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>


			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-cuti">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $cuti[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Cuti &darr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="card card-modern">
				<a href="javascript:;" id="btn-show-sakit">
					<div class="card-body py-4">
						<div class="row align-items-center">
							<div class="col-6 col-md-4">
								<h3 class="text-4-1 my-0"></h3>
								<strong class="text-6 text-color-dark"><?= $sakit[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Sakit &darr;</strong></h3>

							</div>
							<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
								<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
							</div>
						</div>
					</div>
				</a>
			</div>
	</div>

	<?php } else { ?>

		<!----- Selain Admin ----->

		<div class="col-md-12">
			<div class="text-center">
				 <!--<h4>Nilai Anda Priode Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>-->
			</div>
			<div class="card-body">
				<form method="get">
						<div class="form-row d-flex align-items-end">
								<div class="col-md-2">
										<small>Pilih Bulan:</small>
										<div class="input-group">
												<div class="input-group-prepend">
														<span class="input-group-text"><i class="fa fa-calendar"></i></span>
												</div>
												<input type="text" name="bulan"
															data-plugin-datepicker
															data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}'
															class="form-control"
															id="filter_bulan"
															placeholder="Pilih Bulan"
															value="<?= isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m') ?>"
															required>
										</div>
								</div>

								<div class="col-auto">
										<button type="submit" class="btn btn-outline-secondary"><i class="fa fa-filter"></i> Filter</button>
								</div>
						</div>
				</form>

	</br>

			<div class="card-body">
						<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;  : <?= $getUser[0]->nama ?></strong>
						<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    : <?= $getUser[0]->no_pegawai ?></strong>
						<br><strong class="fw-bold text-dark">Jabatan &nbsp; &nbsp; &nbsp;      : <?= $getUser[0]->jabatan ?></strong>
						<br><br>
						<table class="table table-bordered">
								<thead class="table-dark text-center">
										<tr>
												<th>No</th>
												<th>Periode</th>
												<th>Nilai Laporan</th>
												<th>Nilai Pencapaian</th>
												<th>Total</th>
										</tr>
								</thead>
								<tbody class="text-center">
										<?php $no = 1; foreach ($data_mingguan as $minggu): ?>
												<tr>
														<td><?= $no++ ?></td>
														<td><?= $minggu['periode'] ?></td>
														<td><?= number_format($minggu['nilai_laporan'], 2) ?></td>
														<td><?= number_format($minggu['nilai_pencapaian'], 2) ?></td>
														<td><?= number_format(($minggu['nilai_laporan'] + $minggu['nilai_pencapaian']) / 2, 2) ?></td>
												</tr>
										<?php endforeach; ?>
								</tbody>
						</table>


						<!--<br><strong class="fw-bold text-dark">Rata-rata Nilai Laporan Minggu Ini : <?= $nilai_week ? number_format($nilai_week, 2) : 0 ?> </strong>
						<br><strong class="fw-bold text-dark">Rata-rata Nilai Pencapaian Minggu Ini : <?= $nilai_pencapaian_week ? number_format($nilai_pencapaian_week, 2) : 0 ?> </strong>-->
						
			</div>

			</br>
				<div class="card-body">
					<h4>Rata-Rata Nilai Anda pada Bulan <strong> <?= $bulanIni ?> </strong> </h4>
					<table class="table table-bordered">
							<thead class="table-dark text-center">
									<tr>
											<th>No</th>
											<th>Nama</th>
											<th>Bobot</th>
											<th>Nilai</th>
											<th>Nilai x Bobot</th>
									</tr>
							</thead>
							<tbody class="text-center">
								<?php
									$nilai_laporan  = $rata_nilai ? number_format($rata_nilai, 2) : 0;
									$kehadiran			= $total_kehadiran ? $total_kehadiran : 0;
									$hariKerja 			= $getHariKerja ? $getHariKerja->total_hari_kerja : 0;
									$testProduct		= $getTest ? $getTest->nilai : 0;
									$pencapaianAkhir = $rata_nilai_pencapaian ? number_format($rata_nilai_pencapaian, 2) : 0;


									//Total
									$Total_nilai_laporan  = $nilai_laporan*25/100;
									$Total_SP  						= $nilaiSp*20/100;
									
									// Menggunakan safe_divide untuk mencegah division by zero
									$persentase_kehadiran = safe_divide($kehadiran, $hariKerja, 0);
									$Total_kehadiran = ($persentase_kehadiran * 100/100) * 10/100;
									
									$Total_testProduct		= $testProduct*20/100;
									$Total_pencapaianAkhir = $pencapaianAkhir*25/100;

									$Total_all = $Total_nilai_laporan + $Total_kehadiran + $Total_testProduct + $Total_pencapaianAkhir + $Total_SP ;

								?>
											<tr>
													<td>1</td>
													<td style="text-align:left;">Nilai Evaluasi adalah skor akhir/Rata-rata dari laporan penilaian mingguan</td>
													<td>25%</td>
													<td><?= $nilai_laporan ?></td>
													<td><?= number_format($Total_nilai_laporan, 2) ?></td>
											</tr>
											<tr>
													<td>2</td>
													<td style="text-align:left;">Bobot Riwayat SP : </br>
															- Tidak ada SP= 100 </br>
															- SP 1= 80 </br>
															- SP2= 60 </br>
															- SP3= 20
													</td>
													<td>20%</td>
													<td><?= $s_peringatan ?></td>
													<td><?= number_format($Total_SP, 2) ?></td>
											</tr>
											<tr>
													<td>3</td>
													<td style="text-align:left;">Rumus Penilaian Absensi </br>
															(Total Kehadiran / Total Hari Kerja) * 100% </br>
													</td>
													<td>10%</td>
													<td><?= $kehadiran ?> Hari / <?= $hariKerja ?> Hari</td>
													<td><?= number_format($Total_kehadiran, 2) ?></td>
											</tr>
											<tr>
													<td>4</td>
													<td style="text-align:left;">Rata-rata Test Product Knowledge</td>
													<td>20%</td>
													<td><?= $testProduct ?></td>
													<td><?= number_format($Total_testProduct, 2) ?></td>
											</tr>
											<tr>
													<td>5</td>
													<td style="text-align:left;">Pencapaian Akhir</td>
													<td>25%</td>
													<td><?= $pencapaianAkhir ?></td>
													<td><?= number_format($Total_pencapaianAkhir, 2) ?></td>
											</tr>
											<tr class="fw-bold bg-light">
												<td colspan="4" class="text-end"><strong>Total</strong></td>
												<td><strong><?= number_format($Total_all, 2) ?></strong></td>
											</tr>
							</tbody>
					</table>

					<!--	<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; : <?= $getSP->nama ?></strong>
						<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    : <?= $getSP->kode ?></strong>
						<br><strong class="fw-bold text-dark">Sisa Masa SP : <?=  $sisa_masa ? $sisa_masa : 0 ?>  bulan</strong>
						<br><strong class="fw-bold text-dark">Tes Bulan Ini : <?= $getTest ? $getTest->nilai : 0 ?> </strong>
						<br><strong class="fw-bold text-dark">Kehadiran Bulan Ini : <?= $total_kehadiran ? $total_kehadiran : 0 ?> </strong>
						<br><strong class="fw-bold text-dark">Hari Kerja Bulan Ini : <?= $getHariKerja ? $getHariKerja->total_hari_kerja : 0 ?> </strong>
						<br><strong class="fw-bold text-dark">Rata-rata Nilai Laporan Bulan Ini : <?= $rata_nilai ? number_format($rata_nilai, 2) : 0 ?> </strong>
						<br><strong class="fw-bold text-dark">Rata-rata Nilai Pencapaian Bulan Ini : <?= $rata_nilai_pencapaian ? number_format($rata_nilai_pencapaian, 2) : 0 ?> </strong>-->
						
				</div>
				
			</div>
		</div>
		<div class="col-md-6">
			<div class="text-center">
				<h2>Log Aktivitas Anda</h2>
			</div>
			<div class="card-body">
				<div class="row form-group col-md-4">
					<small>Filter By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
						<thead>
							<tr>
								<th> # </th>
								<th> Pengguna</th>
								<th> Aksi </th>
								<th> Keterangan </th>
								<th> Tanggal </th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="text-center">
				<h2>&nbsp;</h2>
			</div>
			<?php if (isHrd() || isAdmin()) { ?>
				<div class="card card-modern">
					<a href="javascript:;" id="btn-show-hadir">
						<div class="card-body py-4">
							<div class="row align-items-center">
								<div class="col-6 col-md-4">
									<h3 class="text-4-1 my-0"></h3>
									<strong class="text-6 text-color-dark"><?= $present[0]->total ?></strong>
								</div>
								<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
									<h3 class="text-4-1 text-color-success line-height-2 my-0">Karyawan <strong>Hadir &uarr;</strong></h3>

								</div>
								<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
									<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
								</div>
							</div>
						</div>
					</a>
				</div>


				<div class="card card-modern">
					<a href="javascript:;" id="btn-show-izin">
						<div class="card-body py-4">
							<div class="row align-items-center">
								<div class="col-6 col-md-4">
									<h3 class="text-4-1 my-0"></h3>
									<strong class="text-6 text-color-dark"><?= $izin[0]->total ?></strong>
								</div>
								<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
									<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Izin &darr;</strong></h3>

								</div>
								<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
									<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
								</div>
							</div>
						</div>
					</a>
				</div>


				<div class="card card-modern">
					<a href="javascript:;" id="btn-show-cuti">
						<div class="card-body py-4">
							<div class="row align-items-center">
								<div class="col-6 col-md-4">
									<h3 class="text-4-1 my-0"></h3>
									<strong class="text-6 text-color-dark"><?= $cuti[0]->total ?></strong>
								</div>
								<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
									<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Cuti &darr;</strong></h3>

								</div>
								<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
									<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
								</div>
							</div>
						</div>
					</a>
				</div>
				<div class="card card-modern">
					<a href="javascript:;" id="btn-show-sakit">
						<div class="card-body py-4">
							<div class="row align-items-center">
								<div class="col-6 col-md-4">
									<h3 class="text-4-1 my-0"></h3>
									<strong class="text-6 text-color-dark"><?= $sakit[0]->total ?></strong>
								</div>
								<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
									<h3 class="text-4-1 text-color-danger line-height-2 my-0">Karyawan <strong>Sakit &darr;</strong></h3>

								</div>
								<div class="col-md-4 text-left text-md-right pe-md-4 mt-4 mt-md-0">
									<i class="bx bx-user icon icon-inline icon-xl bg-primary rounded-circle text-color-light"></i>
								</div>
							</div>
						</div>
					</a>
				</div>
			<?php } else { ?>
				<img style="width: 100%;height: 75vh" src="<?= base_url('/assets/img/gedung.jpg') ?>" alt="">

			<?php } ?>


			<!-- <br><br><br> -->
		</div>
		<?php } ?>

</div>


<div id="main-modal-hadir" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Hadir</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_hadir as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-sakit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Sakit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable1" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_sakit as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-cuti" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Cuti</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable2" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_cuti as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<div id="main-modal-izin" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Izin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable3" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_izin as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#filter_month').change(function() {
			table.ajax.reload()
		})
		$('#myTable').DataTable();
		$('#myTable1').DataTable();
		$('#myTable2').DataTable();
		$('#myTable3').DataTable();

		table = $('#kt_table_1').DataTable({
			responsive: false,
			searchDelay: 500,
			processing: true,
			serverSide: true,
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'dashboard/pagination_log',
				type: 'POST',
				data: function(e) {
					e.filter_month = $('#filter_month').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0],
				className: 'text-center'
			}]
		})

		$('#btn-show-izin').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-izin').modal()
		})

		$('#btn-show-hadir').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-hadir').modal()
		})

		
		$('#btn-show-sakit').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-sakit').modal()
		})

		
		$('#btn-show-cuti').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-cuti').modal()
		})
	})
</script>