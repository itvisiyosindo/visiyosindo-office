<!-- 
	Create by KURNIAWAN  
	12-06-2025
-->

<header class="page-header">
	<h2><i class="fas fa-clipboard-list"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
    table.table-bordered, 
    table.table-bordered th, 
    table.table-bordered td {
        border: 1px solid #666 !important;
    }


		table.striped tbody tr:nth-child(odd) {
			background-color: #f0f0f0;
		}
		table.striped tbody tr:nth-child(even) {
			background-color: #ffffff; 
		}

  @media (min-width: 768px) {
    .modal-dialog.modal-custom {
      max-width: 800px; /* Ubah ini sesuai kebutuhan (default Bootstrap = 500px) */
    }
  }
</style>


<div class="row">
	<div class="col">
		<div class="card-body">
				<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->nama ?></strong>
				<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    : <?= $data_job->no_pegawai ?></strong>
				<br><strong class="fw-bold text-dark">Jabatan &nbsp; : <?= $data_job->jabatan ?></strong>
				<br><br><strong class="fw-bold text-dark">Keterangan Nilai : </strong>
				<!--<br><strong class="fw-bold text-dark"> &lt;60 &nbsp; = &nbsp; Buruk Sekali  &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 60-69 &nbsp; = &nbsp; Buruk  
					&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 70-79 &nbsp; = &nbsp; Biasa Saja  &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 80-89 &nbsp; = &nbsp; Baik  
					&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 90-100 &nbsp; = &nbsp; Sangat Baik </strong>-->
				<br><strong class="fw-bold text-dark">
									96 – 100 = Istimewa &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 90 – 95 = Sangat Baik &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 85 – 89 = Baik 
									&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 80 – 84 = Cukup Baik &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 75 – 79 = Cukup &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 70 – 74 = Kurang 
									&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp;  60 – 69 = Kurang Sekali 
				</strong>
  
				<br><br>
				<div class="row mb-3 align-items-center">
					<div class="col-md-7 mb-2 mb-md-0">
						<a href="<?= base_url('laporan/print_mingguan/' . encrypt($data_job->pengguna_id) . '/' . $week_offset) ?>" target="_blank" class="btn btn-sm btn-success">
							<i class="fas fa-print"></i>&nbsp; Cetak Laporan Mingguan
						</a>
						&nbsp;
						<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-cetak-bulanan">
							<i class="fas fa-file-pdf"></i>&nbsp; Cetak Rangkuman 1 Bulan
						</button>
					</div>
					<div class="col-md-5">
						<div class="form-inline justify-content-md-end">
							<label class="mr-2 font-weight-bold text-dark"><i class="fas fa-history"></i>&nbsp;Pilih Minggu :</label>
							<select class="form-control form-control-sm" style="max-width: 260px;" onchange="if (this.value) window.location.href = this.value;">
								<?php for ($i = 0; $i >= -250; $i--): ?>
									<?php 
										$m_time = strtotime("monday this week $i week");
										$s_time = strtotime("sunday this week $i week");
										$start_w = date('d/m/Y', $m_time);
										$end_w = date('d/m/Y', $s_time);
										$sel = ($week_offset == $i) ? 'selected' : '';
										if ($i == 0) {
											$label = "Minggu Ini ($start_w - $end_w)";
										} else {
											$label = "Minggu " . abs($i) . " Lalu ($start_w - $end_w)";
										}
									?>
									<option value="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/' . $i) ?>" <?= $sel ?>><?= $label ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</div>
				</div>
					<!--<button type="button" class="btn btn-info float-right btn-selesai" style="margin-right: 10px;" id-Sijk="<?=$data_job->pengguna_id?>"> <i class="fas fa-check"></i> Selesaikan Penilaian</button>-->
					<button type="button" class="btn btn-danger float-right btn-petujuk" style="margin-right: 10px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Petunjuk Nilai</button>
				<br><br>

				<input type="hidden" id="id_po" value="<?= $data_job->pengguna_id ?>" />

				<?php $id = $data_job->pengguna_id ?> 
				<input type="hidden" name="id" id="id" value="<?= $data_job->pengguna_id ?>">

				<?php $is_bulk_nilai_hrd_allowed = isHrd(); ?>
				<?php if ($is_bulk_nilai_hrd_allowed) { ?>
				<div class="card border-info mb-2">
					<div class="card-body p-3">
						<form id="bulk-nilai-adm-form" class="row align-items-end">
							<div class="col-md-4 col-sm-12 mb-2 mb-md-0">
								<label for="bulk-nilai-adm-input" class="mb-1"><strong>Bulk Action Nilai HRD</strong></label>
								<input type="number" min="60" max="100" step="1" id="bulk-nilai-adm-input" class="form-control" placeholder="Contoh: 95">
							</div>
							<div class="col-md-4 col-sm-12 mb-2 mb-md-0">
								<button type="submit" class="btn btn-primary" id="btn-bulk-nilai-adm-submit">Terapkan & Simpan Otomatis</button>
							</div>
							<div class="col-md-4 col-sm-12">
								<small class="text-muted">Khusus HRD. Mengisi semua input nilai A lalu menyimpan otomatis ke database.</small>
							</div>
						</form>
					</div>
				</div>
				<?php } ?>
		</div>

		<br>
		
    <?= form_open('laporan/inputNilaiLaporanAdm', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
		<input type="hidden" name="pengguna_id" value="<?= $data_job->pengguna_id ?>">
		<input type="hidden" name="wednesday" value="<?= $wednesday ?>">

		
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4><strong>Laporan Mingguan</strong> dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>
						
					<!--<div style="overflow-x: auto; white-space: nowrap; max-width: 100%;">
                        <table border="1" class="striped" style="width: 100%; min-width: 2500px;">-->
				<div style="max-width: 100%;">									
    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width: 100%; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'>&nbsp; No &nbsp;</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Jobdesk </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Jenis</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Tanggal </th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan Proyek</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp; Status Pekerjaan &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp; Hasil Kerja &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Pihak Terkait</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>Nilai A</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>Nilai B</th>
                                		<th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>Total Nilai</th></tr>
                            </thead>

														<?php
														
																$total_nilai_a = 0;
																$jumlah_nilai_a = 0;
																$total_nilai_b = 0;
																$jumlah_nilai_b = 0;

																foreach ($nilai_point_map as $poin) {
																		if (!empty($poin->nilai_a) && is_numeric($poin->nilai_a)) {
																				$total_nilai_a += $poin->nilai_a;
																				$jumlah_nilai_a++;
																		}

																		if (!empty($poin->nilai_b) && is_numeric($poin->nilai_b)) {
																				$total_nilai_b += $poin->nilai_b;
																				$jumlah_nilai_b++;
																		}
																}

																$nilaiRataLapA = ($jumlah_nilai_a > 0) ? ($total_nilai_a / $jumlah_nilai_a) : 0;
																$nilaiRataLap = ($jumlah_nilai_b > 0) ? ($total_nilai_b / $jumlah_nilai_b) : 0;

																$rataAll = ($nilaiRataLapA + $nilaiRataLap)/2;
															?>


                            <tbody>
                              <?php 
																$no = 1;

																$grouped_data = [];
																$point_has_lap = [];
																foreach ($data_detail as $row) {
																		$id_job = $row->id_lap;
																		if (!isset($grouped_data[$row->id])) {
																				$grouped_data[$row->id] = [];
																		}
																		$grouped_data[$row->id][] = $row;

																		// Cek point yang punya id_lap
																		if (!empty($row->id_lap) && $row->jenis !== 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
																				$point_has_lap[$row->point] = true;
																		}
																}
																?>

																<?php foreach ($grouped_data as $id_desc => $rows): ?>
																		<?php 
																		$firstRow = true;
																		$rowspan = count($rows);
																		foreach ($rows as $row): 

																				$hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
																				$nama_hari = $hari[date('l', strtotime($row->tanggal))];

																				$tgl_format = date('d-m-Y', strtotime($row->tanggal));

																				if (!empty($row->link) && empty($row->ket_hasil)) {
																						$link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-link"></i> Hasil Kerja</a>';
																				} elseif (empty($row->link) && !empty($row->ket_hasil)) {
																						$link_download = htmlspecialchars($row->ket_hasil, ENT_QUOTES, 'UTF-8');
																				} else {
																						$link_download = 'Tidak Ada';
																				}


																				// Gunakan week_offset untuk menghitung Senin dan Jumat berdasarkan minggu
																				$monday = date('Y-m-d', strtotime("monday this week +$week_offset week"));
																				$Sunday = date('Y-m-d', strtotime("Sunday this week +$week_offset week"));

																				// Cek apakah tanggal created_at berada di luar rentang minggu
																				$created_at = date('Y-m-d', strtotime($row->created_at));
																				$is_outside_range = ($created_at < $monday || $created_at > $Sunday);
																				
																				
																		?>
																		<tr 
																			<?php 
																				if ($row->nilai_isi == 1) {
																						echo 'style="background-color: #C7C6C1;"';
																				} elseif ($is_outside_range && !empty($row->id_lap)) {
																						echo 'style="background-color: #ffebee;"';
																				}
																			?>
																		>

																				<?php if ($firstRow): ?>
																						<td rowspan="<?= $rowspan ?>" style="text-align:center"><font color='#000000'>&nbsp;<?= $no++ ?>&nbsp;</td>
																						<?php if($row->nilai_isi != 1){ ?>
																						<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><?= $row->deskripsi ?></td>
																						<?php }else{ ?>
																						<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><b><?= $row->deskripsi ?></b></font></td>
																						<?php } ?>
																						
																				<?php endif; ?>

																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->jenis : '' ?>&nbsp;</td>
																				<td style="text-align:center">
																					<font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $nama_hari . ', ' . $tgl_format : '' ?>&nbsp;</font>
																					<?php if ($is_outside_range && !empty($row->id_lap)): ?>
																						<br><span class="badge badge-danger" style="font-size: 10px;" title="Diisi terlambat / di luar minggu berjalan"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
																					<?php endif; ?>
																				</td>
																				<td style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->progress : '' ?> <input type="hidden" name="id_sodetail[]" value="<?= $row->id_lap ?>"> &nbsp;</td>
																				<?php if($row->jenis == 'TIDAK ADA PEKERJAAN DI MINGGU INI'){ ?>
																				<td style="text-align:center"><font color='#000000'>&nbsp; &nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp; &nbsp;</td>
																				<?php }else{ ?>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->status_pekerjaan : '' ?>&nbsp;</td>
																				<td style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $link_download : '' ?>&nbsp;</td>
																				<?php } ?>
																				<td><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->pihak : '' ?>&nbsp;</td>
																				<td style="width:15%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->keterangan : '' ?>&nbsp;</td>
																				<td>
																					<?php 
																					if (
																							$row->nilai_isi == 1 && 
																							isset($point_has_lap[$row->point])
																					) { 
																							$nilai_a = isset($nilai_point_map[$row->point]) ? $nilai_point_map[$row->point]->nilai_a : '';
																					?>
																							<input class="form-control" type="text" name="nilaia[<?= $row->point ?>]" value="<?= htmlspecialchars($nilai_a) ?>" style="background-color: #ffffff;">
																					<?php 
																					} 
																					?>
																				</td>
																				<td style="text-align:center">
																					<?php 
																					if (
																							$row->nilai_isi == 1 && 
																							isset($point_has_lap[$row->point])
																					) { 
																							$nilai_b = isset($nilai_point_map[$row->point]) ? $nilai_point_map[$row->point]->nilai_b : '';
																					?>
																							<font color='#000000'><?= htmlspecialchars($nilai_b) ?>
																					<?php 
																					} 
																					?>
																				</td>
																				<td style="text-align:center"></td>
																		</tr>
																		<?php 
																		$firstRow = false;
																		endforeach; 
																		?>
																<?php endforeach; ?>

															
																<tr>
																		<td colspan="9" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata : &nbsp;</strong></td>		
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataLapA, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataLap, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($rataAll, 2) ?></strong>&nbsp;</td>
																
																</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<!--<a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>-->
								
								<button type="button" class="btn btn-success btn-save float-right">Simpan</button>
						</div>
				</div>

				

			</div>
		</div>
    <?= form_close(); ?>

		
		<br>

		<?= form_open('laporan/inputNilaiPencapaianAdm', array('id' => 'main-form-pencapaian', 'autocomplete' => 'off')); ?>
		<!-- PENCAPAIAN -->
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Pencapaian Mingguan dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>

						
					<div style="max-width: 100%;">
    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width: 100%; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="1%"><font color='#000000'> No </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Tanggal </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="15%"><font color='#000000'> Deskripsi</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Hasil Kerja</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'> Nilai A</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'> Nilai B</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="7%"><font color='#000000'> Catatan A</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="7%"><font color='#000000'> Catatan B</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php 
                                $no = 1;
                                $total_nilai = 0; // Variabel untuk menampung total nilai
                                $total_nilai_b = 0; // Variabel untuk menampung total nilai
                                $jumlah_data = count($data_detail_pencapaian); // Jumlah data untuk menghitung rata-rata

                                foreach ($data_detail_pencapaian as $row) {
                                    
                                    if (!empty($row->link) && empty($row->keterangan)) {
																						$link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-link"></i> Hasil Kerja</a>';
																				} elseif (empty($row->link) && !empty($row->keterangan)) {
																						$link_download = htmlspecialchars($row->keterangan, ENT_QUOTES, 'UTF-8');
																				} else {
																						$link_download = 'Tidak Ada';
																				}

                                    $hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
                                    $nama_hari = $hari[date('l', strtotime($row->tanggal))];

                                    // Gunakan week_offset untuk menghitung Senin dan Jumat berdasarkan minggu
																				$monday = date('Y-m-d', strtotime("monday this week +$week_offset week"));
																				$Sunday = date('Y-m-d', strtotime("Sunday this week +$week_offset week"));

																				// Cek apakah tanggal created_at berada di luar rentang minggu
																				$created_at = date('Y-m-d', strtotime($row->created_at));
																				$is_outside_range = ($created_at < $monday || $created_at > $Sunday);

                                    $id = $row->id;
																		$id_edit = encrypt($row->id);

                                    // Tambahkan nilai ke total_nilai
                                    $total_nilai += $row->nilai_a;
                                    $total_nilai_b += $row->nilai_b;

                                ?>
                                    <tr <?php if ($is_outside_range) { echo 'style="background-color: red;"'; } ?>>
                                        <td style="text-align:center"><font color='#000000'><?= $no++ ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $nama_hari . ', ' . date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                        <td><font color='#000000'><?= $row->detail ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $link_download ?></td>
																				<td><input class="form-control" type="text" name="nilaipa[<?= $id ?>]" value="<?= $row->nilai_a ?>"></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->nilai_b ?></td>
																				<td><input class="form-control" type="text" name="catatanpa[<?= $id ?>]" value="<?= $row->catatan_a ?>"></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->catatan_b ?></td>
                                    </tr>

                                <?php } 

                                // Menghitung rata-rata, jika jumlah data > 0
                                if ($jumlah_data > 0) {
                                    $nilaiRataA = $total_nilai / $jumlah_data;
                                    $nilaiRataB = $total_nilai_b / $jumlah_data;
																		$nilaiRataPencapaian = ($nilaiRataA+$nilaiRataB)/2;
                                } else {
                                    $nilaiRataA = 0; // Jika tidak ada data, set rata-rata 0
                                    $nilaiRataB = 0; // Jika tidak ada data, set rata-rata 0
																		$nilaiRataPencapaian = 0;
                                }
                                ?>

                                <tr>
                                    <td colspan="4" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata :</strong></td>
                                    <td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($nilaiRataA, 2) ?></strong></td>
                                    <td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($nilaiRataB, 2) ?></strong></td>
                                    <td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($nilaiRataPencapaian, 2) ?></strong></td>
																		<td></td>
                                </tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<!--<a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>-->
								
								<button type="button" class="btn btn-success btn-save float-right">Simpan</button>
						</div>
				</div>

			</div>
		</div>
		<!-- PENCAPAIAN -->
		<?= form_close(); ?>
		


		<br>

<?= form_open('laporan/inputNilaiUmumAdm', array('id' => 'main-form-umum', 'autocomplete' => 'off')); ?>
		<input type="hidden" name="id_pengg" value="<?= $data_job->pengguna_id ?>">
		<input type="hidden" name="hari_rabu" value="<?= $wednesday ?>">


		<!-- PENILAIAN UMUM -->
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Penilaian Umum dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>

					<div style="max-width: 100%;">
    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width: 100%; table-layout: fixed;">
     
                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="1%"><font color='#000000'> No </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Indikator </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="15%"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'> Nilai A</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="2%"><font color='#000000'> Nilai B</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Catatan A</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Catatan B</th>
                                </tr>
                            </thead>
                      <tbody>
                       <tr>
													<td style="text-align:center">1</td>
													<td style="text-align:center">Inisiatif & Kreativitas	</td>
													<td style="text-align:left;">a. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.<br>
															b. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama				
													</td>
													<td><input class="form-control" type="text" name="nilaia1" value="<?= htmlspecialchars($penilaian_umum->nilaia1) ?>" style="background-color: #ffffff;"></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib1) ?></td>
													<td><input class="form-control" type="text" name="catatana1" value="<?= htmlspecialchars($penilaian_umum->catatana1) ?>" style="background-color: #ffffff;"></td>
													<td><?= htmlspecialchars($penilaian_umum->catatanb1) ?></td>
											</tr>
											<tr>
													<td style="text-align:center">2</td>
													<td style="text-align:center">Kepatuhan Peraturan	</td>
													<td style="text-align:left;">a. Memahami setiap SOP pekerjaaannya.<br>
															b. Melaksanakan pekerjaannya sesuai SOP yang diberikan.<br>
															c. Memahami Peraturan dan Tata Tertib Perusahaan.<br>
															d. Melaksanakan pekerjaan sesuai SOP dan Peraturan/Tata Tertib Perusahaan.<br>
															e. Kepatuhan dalam menjalankan semua Peraturan/Tata Tertib Peraturan.				
													</td>
													<td><input class="form-control" type="text" name="nilaia2" value="<?= htmlspecialchars($penilaian_umum->nilaia2) ?>" style="background-color: #ffffff;"></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib2) ?></td>
													<td><input class="form-control" type="text" name="catatana2" value="<?= htmlspecialchars($penilaian_umum->catatana2) ?>" style="background-color: #ffffff;"></td>
													<td><?= htmlspecialchars($penilaian_umum->catatanb2) ?></td>
											</tr>
											<tr>
													<td style="text-align:center">3</td>
													<td style="text-align:center">Analisa atas Masalah	</td>
													<td style="text-align:left;">a. Mampu melakukan analisa atas trouble/problem yang dihadapi.<br>
															b. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.<br>
															c. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.				
													</td>
													<td><input class="form-control" type="text" name="nilaia3" value="<?= htmlspecialchars($penilaian_umum->nilaia3) ?>" style="background-color: #ffffff;"></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib3) ?></td>
													<td><input class="form-control" type="text" name="catatana3" value="<?= htmlspecialchars($penilaian_umum->catatana3) ?>" style="background-color: #ffffff;"></td>
													<td><?= htmlspecialchars($penilaian_umum->catatanb3) ?></td>
											</tr>
											<tr>
													<td style="text-align:center">4</td>
													<td style="text-align:center">Komunikasi & Kerja sama tim	</td>
													<td style="text-align:left;">a. Mampu berkoordinasi lintas fungsi.<br>
															b. Mampu berkomunikasi dengan baik secara internal maupun eksternal divisi maupun perusahaan.<br>
															c. Mampu berkomunikasi dengan efektif.<br>
															d. Kesesuaian lokasi komunikasi (Personal/Group).<br>
															e. Kemampuan membuat pelaporan on time.				
													</td>
													<td><input class="form-control" type="text" name="nilaia4" value="<?= htmlspecialchars($penilaian_umum->nilaia4) ?>" style="background-color: #ffffff;"></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib4) ?></td>
													<td><input class="form-control" type="text" name="catatana4" value="<?= htmlspecialchars($penilaian_umum->catatana4) ?>" style="background-color: #ffffff;"></td>
													<td><?= htmlspecialchars($penilaian_umum->catatanb4) ?></td>
											</tr>
											<tr>
													<td style="text-align:center">5</td>
													<td style="text-align:center">Ketelitian, Administrasi dan Teknologi	</td>
													<td style="text-align:left;">a. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan.<br>
															b. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.<br>
															c. Pelaksanaan pekerjaan secara efektif dan minim human error.						
													</td>
													<td><input class="form-control" type="text" name="nilaia5" value="<?= htmlspecialchars($penilaian_umum->nilaia5) ?>" style="background-color: #ffffff;"></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib5) ?></td>
													<td><input class="form-control" type="text" name="catatana5" value="<?= htmlspecialchars($penilaian_umum->catatana5) ?>" style="background-color: #ffffff;"></td>
													<td><?= htmlspecialchars($penilaian_umum->catatanb5) ?></td>
											</tr>
											
											<?php
												$nilaiRataa = ($penilaian_umum->nilaia1 + $penilaian_umum->nilaia2 + $penilaian_umum->nilaia3 + $penilaian_umum->nilaia4 + $penilaian_umum->nilaia5)/5;
												$nilaiRatab = ($penilaian_umum->nilaib1 + $penilaian_umum->nilaib2 + $penilaian_umum->nilaib3 + $penilaian_umum->nilaib4 + $penilaian_umum->nilaib5)/5;
												$nilaiRataPumum = ($nilaiRataa + $nilaiRatab)/2;
											?>
											<tr class="fw-bold bg-light">
                        <td colspan="3" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata :</strong></td>
												<td style="text-align:center"><strong><?= number_format($nilaiRataa, 2) ?></strong></td>
												<td style="text-align:center"><strong><?= number_format($nilaiRatab, 2) ?></strong></td>
												<td style="text-align:center"><strong><?= number_format($nilaiRataPumum, 2) ?></strong></td>
												<td></td>
											</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
							
							<strong class="fw-bold text-dark">Rangkuman Nilai :
								<table style="margin-top: 4px;">
										<tr>
												<td style="padding-right: 17px;">Laporan Mingguan</td>
												<td>: <?= number_format($rataAll, 2) ?></td>
										</tr>
										<tr>
												<td>Penilaian Umum</td>
												<td>: <?= number_format($nilaiRataPumum, 2) ?></td>
										</tr>
										<tr>
												<td>Rata-rata (Laporan Mingguan + Penilaian Umum)</td>
												<td>: <?= number_format(($rataAll+$nilaiRataPumum)/2, 2) ?></td>
										</tr>
										<tr>
												<td>Pencapaian</td>
												<td>: <?= number_format($nilaiRataPencapaian, 2) ?></td>
										</tr>
								</table></strong><br>





								<a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
								
								<button type="button" class="btn btn-success btn-save float-right">Simpan</button>
                <!--<button type="button" class="btn btn-success float-right btn-selesai" style="margin-right: 10px;" id-Sijk="<?=$data_job->pengguna_id?>"> <i class="fas fa-check"></i> Selesai </button>-->
						</div>
				</div>

			</div>
		</div>
		<!-- PENILAIAN UMUM -->
		<?= form_close(); ?>
	</div>
</div>


<div id="main-modal-indikatorPenilaian" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-custom" role="document">
		<div class="modal-content ">
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

			<!--	<p class="mt-2"><i>TOTAL PENILAIAN </i></p>
<p class="mt-2">
96 – 100 = Istimewa (Outstanding) | 90 – 95 = Sangat Baik (Excellent) | 85 – 89 = Baik (Very Good) | 80 – 84 = Cukup Baik (Good) | 75 – 79 = Cukup (Fair) | 70 – 74 = Kurang (Below Average) | 60 – 69 = Kurang Sekali (Poor)
</p>-->


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


<script>
	document.addEventListener('DOMContentLoaded', function() {

				var isBulkNilaiHrdAllowed = <?= $is_bulk_nilai_hrd_allowed ? 'true' : 'false' ?>;

				if (isBulkNilaiHrdAllowed) {
					var bulkSaveTargetsAdm = [
						{
							formSelector: '#main-form',
							requiredSelector: 'input[name^="nilaia["]',
							label: 'Nilai Laporan Mingguan'
						},
						{
							formSelector: '#main-form-pencapaian',
							requiredSelector: 'input[name^="nilaipa["]',
							label: 'Nilai Pencapaian'
						},
						{
							formSelector: '#main-form-umum',
							requiredSelector: 'input[name="nilaia1"], input[name="nilaia2"], input[name="nilaia3"], input[name="nilaia4"], input[name="nilaia5"]',
							label: 'Nilai Umum'
						}
					];

					function submitBulkFormAdm(target) {
						var $form = $(target.formSelector);
						if (!$form.length || $form.find(target.requiredSelector).length === 0) {
							return $.Deferred().resolve({ status: 'success', skipped: true }).promise();
						}

						var formData = new FormData($form[0]);
						if (typeof token !== 'undefined' && token && !formData.has('csrf_token')) {
							formData.append('csrf_token', token);
						}

						return $.ajax({
							url: $form.attr('action'),
							type: 'POST',
							data: formData,
							contentType: false,
							processData: false,
							dataType: 'JSON'
						});
					}

					function submitBulkSequentialAdm(index, onDone, onError) {
						if (index >= bulkSaveTargetsAdm.length) {
							onDone();
							return;
						}

						var target = bulkSaveTargetsAdm[index];
						submitBulkFormAdm(target)
							.done(function(resp) {
								if (resp && resp.status === 'error') {
									onError(target.label, resp.msg || 'Terjadi kesalahan saat menyimpan.');
									return;
								}

								submitBulkSequentialAdm(index + 1, onDone, onError);
							})
							.fail(function(xhr) {
								var msg = 'Gagal menyimpan data.';
								if (xhr && xhr.responseJSON && xhr.responseJSON.msg) {
									msg = xhr.responseJSON.msg;
								}
								onError(target.label, msg);
							});
					}

					$(document).on('submit', '#bulk-nilai-adm-form', function(e) {
						e.preventDefault();

						var nilaiInput = $('#bulk-nilai-adm-input').val();
						var nilaiBulk = parseFloat(nilaiInput);
						var $submitBtn = $('#btn-bulk-nilai-adm-submit');

						if (nilaiInput === '' || isNaN(nilaiBulk)) {
							Swal.fire('Nilai tidak valid', 'Masukkan nilai antara 60 sampai 100.', 'warning');
							return;
						}

						if (nilaiBulk < 60 || nilaiBulk > 100) {
							Swal.fire('Nilai di luar rentang', 'Nilai yang diperbolehkan adalah 60 sampai 100.', 'warning');
							return;
						}

						var nilaiFinal = (Math.floor(nilaiBulk) === nilaiBulk)
							? nilaiBulk.toString()
							: nilaiBulk.toFixed(2).replace(/0+$/, '').replace(/\.$/, '');

						$('input[name^="nilaia["], input[name^="nilaipa["], input[name="nilaia1"], input[name="nilaia2"], input[name="nilaia3"], input[name="nilaia4"], input[name="nilaia5"]').each(function() {
							$(this).val(nilaiFinal);
						});

						$submitBtn.prop('disabled', true);

						Swal.fire({
							html: '<h4>Menyimpan bulk nilai HRD ke database...</h4>',
							icon: 'info',
							allowOutsideClick: false,
							showConfirmButton: false
						});

						submitBulkSequentialAdm(
							0,
							function() {
								$submitBtn.prop('disabled', false);
								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: 'Bulk action nilai HRD berhasil disimpan otomatis ke database.',
									timer: 1500,
									showConfirmButton: false
								});
							},
							function(label, msg) {
								$submitBtn.prop('disabled', false);
								Swal.fire('Gagal simpan ' + label, msg, 'error');
							}
						);
					});
				}


				$(document).on('click', '.btn-selesai', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Selesaikan Penilaian Laporan Mingguan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'laporan/endPenilaianRev2',
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


				//$(document).ready(function () {
					// Panggil modal saat halaman pertama kali dimuat
				//	$('#main-modal-indikatorPenilaian').modal('show');
				//});

				$(document).ready(function () {
					$('.btn-petujuk').click(function () {
							$('.form-control').val(null);      // Kosongkan input
							$('.btn-isactive').remove();       // Hapus elemen kalau ada
							$('#main-modal-indikatorPenilaian').modal('show'); // Tampilkan modal
					});
			});

        
		
	 })

	function goBack() {
        window.history.back();
    }

</script>

<div id="modal-cetak-bulanan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<form action="<?= base_url('laporan/print_bulanan/' . encrypt($data_job->pengguna_id)) ?>" method="GET" target="_blank" onsubmit="$('#modal-cetak-bulanan').modal('hide');">
				<div class="modal-header bg-dark text-light">
					<h5 class="modal-title"><i class="fas fa-print"></i> Cetak Rangkuman Bulanan</h5>
					<button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="form-control-label">Pilih Bulan & Tahun :</label>
						<input type="month" name="month" class="form-control" value="<?= date('Y-m') ?>" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Cetak PDF</button>
				</div>
			</form>
		</div>
	</div>
</div>