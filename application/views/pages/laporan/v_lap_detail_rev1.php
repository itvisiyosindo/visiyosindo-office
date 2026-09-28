<header class="page-header">
	<h2><i class="fas fa-clipboard-list"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="card-body">
				<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->nama ?></strong>
				<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    : <?= $data_job->no_pegawai ?></strong>
				<br><strong class="fw-bold text-dark">Jabatan &nbsp; : <?= $data_job->jabatan ?></strong>
				<br><br><strong class="fw-bold text-dark">Keterangan Nilai : </strong>
				<br><strong class="fw-bold text-dark"> &lt;60 &nbsp; = &nbsp; Buruk Sekali  &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 60-69 &nbsp; = &nbsp; Buruk  
					&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 70-79 &nbsp; = &nbsp; Biasa Saja  &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 80-89 &nbsp; = &nbsp; Baik  
					&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 90-100 &nbsp; = &nbsp; Sangat Baik </strong>

				<input type="hidden" id="id_po" value="<?= $data_job->pengguna_id ?>" />

				<?php $id = $data_job->pengguna_id ?> 
				<input type="hidden" name="id" id="id" value="<?= $data_job->pengguna_id ?>">
		</div>

		<br>
		
    <?= form_open('laporan/updateNilaiNew', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

		<br>
		
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Laporan Mingguan dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>
						
					<div style="overflow-x: auto; white-space: nowrap; max-width: 100%;">
                       <table border="1" style="width: 100%; min-width: 2500px;">
                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'>&nbsp; No &nbsp;</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Jobdesk </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="7%"><font color='#000000'> Jenis</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Tanggal </th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan Proyek</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Status Pekerjaan &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Hasil Kerja &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Pihak Terkait</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp;&nbsp;&nbsp; Nilai A&nbsp;&nbsp;&nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp;&nbsp;&nbsp; Nilai B&nbsp;&nbsp;&nbsp;</th>
                                		<th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp;&nbsp;&nbsp; Total Nilai &nbsp;&nbsp;&nbsp;</th>
																</tr>
                            </thead>
                            <tbody>
                              <?php 
																$no = 1;
																$total_nilai = 0; // Variabel untuk menampung total nilai
																$total_nilai_b = 0; // Variabel untuk menampung total nilai
																$jumlah_data = 0; // Variabel untuk menghitung jumlah data valid

																$grouped_data = [];
																foreach ($data_detail as $row) {
																		$id_job = $row->id_lap;
																		if (!isset($grouped_data[$row->id])) {
																				$grouped_data[$row->id] = [];
																		}
																		$grouped_data[$row->id][] = $row;
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
																				
																				// Menambahkan nilai ke total hanya jika id_lap tidak kosong
																				if (!empty($row->id_lap) && $row->jenis !== 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
																						$total_nilai += $row->nilai;
																						$total_nilai_b += $row->nilai_b;
																						$jumlah_data++; // Hanya hitung jika data valid
																				}

																		?>
																		<tr <?php if ($is_outside_range && !empty($row->id_lap)) { echo 'style="background-color: red;"'; } ?>>

																				<?php if ($firstRow): ?>
																						<td rowspan="<?= $rowspan ?>" style="text-align:center"><font color='#000000'>&nbsp;<?= $no++ ?>&nbsp;</td>
																						<?php if($row->nilai_isi != 1){ ?>
																						<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><?= $row->deskripsi ?></td>
																						<?php }else{ ?>
																						<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><b><?= $row->deskripsi ?></b></font></td>
																						<?php } ?>
																						
																				<?php endif; ?>

																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->jenis : '' ?>&nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $nama_hari . ', ' . $tgl_format : '' ?>&nbsp;</td>
																				<td style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->progress : '' ?> <input type="hidden" name="id_sodetail[]" value="<?= $row->id_lap ?>"> &nbsp;</td>
																				<?php if($row->jenis == 'TIDAK ADA PEKERJAAN DI MINGGU INI'){ ?>
																				<td style="text-align:center"><font color='#000000'>&nbsp; &nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp; &nbsp;</td>
																				<?php }else{ ?>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->status_pekerjaan : '' ?>&nbsp;</td>
																				<td style="width:10%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $link_download : '' ?>&nbsp;</td>
																				<?php } ?>
																				<td><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->pihak : '' ?>&nbsp;</td>
																				<td style="width:10%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->keterangan : '' ?>&nbsp;</td>
																				<?php if($row->jenis == 'TIDAK ADA PEKERJAAN DI MINGGU INI'){ ?>
																					<td style="text-align:center; width:15%; word-wrap: break-word; white-space: normal;"><font color='#000000'>
																						<?php if (empty($row->id_lap)): ?>
																								&nbsp;
																						<?php else: ?>
																							&nbsp;<?= $row->kendala ?>
																								&nbsp;
																								<button type="button" class="btn btn-sm btn-primary btn-catatan1" data-id="<?= encrypt($row->id_lap) ?>"><i class="icons icon-plus"> Catatan</i></button>
																								&nbsp;
																						<?php endif; ?>
																				</td>
																				<td style="text-align:center; width:15%; word-wrap: break-word; white-space: normal;"><font color='#000000'>
																						<?php if (empty($row->id_lap)): ?>
																								&nbsp;
																						<?php else: ?>
																							&nbsp;<?= $row->solusi ?>
																								&nbsp;
																								<?php if(sessPenggunaId() == 1){ ?>
																								<button type="button" class="btn btn-sm btn-primary btn-catatan2" data-id="<?= encrypt($row->id_lap) ?>"><i class="icons icon-plus"> Catatan</i></button>
																								<?php } ?>&nbsp;
																						<?php endif; ?>
																				</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;&nbsp;</td>
																				<?php }else{ ?>
																				<td style="text-align:center"><font color='#000000'>
																						<?php if (empty($row->id_lap)): ?>
																								&nbsp;
																						<?php else: ?>
																							&nbsp;<?= $row->nilai ?>
																								&nbsp;<button type="button" class="btn btn-sm btn-primary btn-nilai1" data-id="<?= encrypt($row->id_lap) ?>"><i class="icons icon-plus"> Nilai</i></button>
																								&nbsp;
																						<?php endif; ?>
																				</td>
																				<td style="text-align:center"><font color='#000000'>
																						<?php if (empty($row->id_lap)): ?>
																								&nbsp;
																						<?php else: ?>
																							&nbsp;<?= $row->nilai_b ?>
																								&nbsp;
																								<?php if(sessPenggunaId() == 1){ ?>
																								<button type="button" class="btn btn-sm btn-primary btn-nilai2" data-id="<?= encrypt($row->id_lap) ?>"><i class="icons icon-plus"> Nilai</i></button>
																								<?php } ?>&nbsp;
																						<?php endif; ?>
																				</td>																				
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? ($row->nilai+$row->nilai_b)/2 : '' ?>&nbsp;</td>
																				<?php } ?>
																		</tr>
																		<?php 
																		$firstRow = false;
																		endforeach; 
																		?>
																<?php endforeach; ?>

																<?php 
																// Menghitung rata-rata hanya jika ada data valid
																$nilaiRata = ($jumlah_data > 0) ? ($total_nilai / $jumlah_data) : 0;
																$nilaiRata_b = ($jumlah_data > 0) ? ($total_nilai_b / $jumlah_data) : 0;
																$nilaiRataAll = ($jumlah_data > 0) ? ($nilaiRata + $nilaiRata_b)/2 : 0;
																?>

																<tr>
																		<td colspan="9" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata : &nbsp;</strong></td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRata, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRata_b, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataAll, 2) ?></strong>&nbsp;</td>
																</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<a href="<?= base_url('laporan/show/detail/laporan_detail/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/laporan_detail/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
						</div>
				</div>

			</div>
		</div>

		<!-- PENCAPAIAN -->
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Pencapaian Mingguan dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>
						
					<div style="overflow-x: auto; white-space: nowrap; max-width: 100%;">
                        <table border="1" style="width: 100%; min-width: 2500px;">
                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'>&nbsp; No &nbsp;</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Jobdesk </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="7%"><font color='#000000'> Jenis</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Tanggal </th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan Proyek</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Status Pekerjaan &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Hasil Kerja &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Pihak Terkait</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp;&nbsp;&nbsp; Nilai A&nbsp;&nbsp;&nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp;&nbsp;&nbsp; Nilai B&nbsp;&nbsp;&nbsp;</th>
                                		<th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp;&nbsp;&nbsp; Total Nilai &nbsp;&nbsp;&nbsp;</th>
																</tr>
                            </thead>
                            <tbody>
                              <?php 
																$no = 1;
																$total_nilai = 0; // Variabel untuk menampung total nilai
																$total_nilai_b = 0; // Variabel untuk menampung total nilai
																$jumlah_data = 0; // Variabel untuk menghitung jumlah data valid

																$grouped_data = [];
																foreach ($data_pencapaian as $row) {
																		$id_job = $row->id_lap;
																		if (!isset($grouped_data[$row->id])) {
																				$grouped_data[$row->id] = [];
																		}
																		$grouped_data[$row->id][] = $row;
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
																				
																				// Menambahkan nilai ke total hanya jika id_lap tidak kosong
																				if (!empty($row->id_lap)) {
																						$total_nilai += $row->nilai_pencapaian_a;
																						$total_nilai_b += $row->nilai_pencapaian_b;
																						$jumlah_data++; // Hanya hitung jika data valid
																				}
																		?>
																		<tr <?php if ($is_outside_range && !empty($row->id_lap)) { echo 'style="background-color: red;"'; } ?>>

																				<?php if ($firstRow): ?>
																						<td rowspan="<?= $rowspan ?>" style="text-align:center"><font color='#000000'>&nbsp;<?= $no++ ?>&nbsp;</td>
																						<?php if($row->nilai_isi != 1){ ?>
																						<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><?= $row->deskripsi ?></td>
																						<?php }else{ ?>
																						<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><b><?= $row->deskripsi ?></b></font></td>
																						<?php } ?>
																						
																				<?php endif; ?>

																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->jenis : '' ?>&nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $nama_hari . ', ' . $tgl_format : '' ?>&nbsp;</td>
																				<td style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->progress : '' ?> <input type="hidden" name="id_sodetail[]" value="<?= $row->id_lap ?>"> &nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->status_pekerjaan : '' ?>&nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $link_download : '' ?>&nbsp;</td>
																				<td><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->pihak : '' ?>&nbsp;</td>
																				<td style="width:15%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->keterangan : '' ?>&nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>
																						<?php if (empty($row->id_lap)): ?>
																								&nbsp;
																						<?php else: ?>
																							&nbsp;<?= $row->nilai_pencapaian_a ?>
																								&nbsp;<button type="button" class="btn btn-sm btn-primary btn-nilai3" data-id="<?= encrypt($row->id_lap) ?>"><i class="icons icon-plus"> Nilai</i></button>
																								&nbsp;
																						<?php endif; ?>
																				</td>
																				<td style="text-align:center"><font color='#000000'>
																						<?php if (empty($row->id_lap)): ?>
																								&nbsp;
																						<?php else: ?>
																							&nbsp;<?= $row->nilai_pencapaian_b ?>
																								&nbsp;
																								<?php if(sessPenggunaId() == 1){ ?>
																									<button type="button" class="btn btn-sm btn-primary btn-nilai4" data-id="<?= encrypt($row->id_lap) ?>"><i class="icons icon-plus"> Nilai</i></button>
																								<?php  } ?> &nbsp;
																						<?php endif; ?>
																				</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? ($row->nilai_pencapaian_a+$row->nilai_pencapaian_b)/2 : '' ?>&nbsp;</td>
																		</tr>
																		<?php 
																		$firstRow = false;
																		endforeach; 
																		?>
																<?php endforeach; ?>

																<?php 
																// Menghitung rata-rata hanya jika ada data valid
																$nilaiRata = ($jumlah_data > 0) ? ($total_nilai / $jumlah_data) : 0;
																$nilaiRata_b = ($jumlah_data > 0) ? ($total_nilai_b / $jumlah_data) : 0;
																$nilaiRataAll = ($jumlah_data > 0) ? ($nilaiRata + $nilaiRata_b)/2 : 0;
																?>

																<tr>
																		<td colspan="9" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata : &nbsp;</strong></td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRata, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRata_b, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataAll, 2) ?></strong>&nbsp;</td>
																</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<a href="<?= base_url('laporan/show/detail/laporan_detail/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/laporan_detail/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
						</div>
				</div>

			</div>
		</div>
		<!-- PENCAPAIAN -->
		<?= form_close(); ?>
	</div>
</div>

<!-- Nilai Laporan Mingguan A -->
<div id="main-modal-nilaia" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Input Nilai Laporan Mingguan A</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
    <table class="table table-bordered table-sm">
        <tbody>
            <tr>
                <td style="width: 5%; text-align: center;">1.</td>
                <td style="width: 20%;"><label for="solusi">Inisiatif & Kreativitas</label></td>
                <td style="width: 60%;">
										1. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.</br>
										2. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_1" name="nilai_1" placeholder="Nilai" required>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">2.</td>
                <td><label for="solusi2">Kepatuhan Peraturan</label></td>
                <td>
									1. Memahami setiap SOP pekerjaaannya.</br>
									2. Melaksanakan pekerjaannya sesuai SOP yang diberikan.</br>
									3. Memahami Peraturan dan Tata Tertib Perusahaan.</br>
									4. Melaksanakan pekerjaan sesuai SOP dan Peraturan/Tata Tertib Perusahaan.</br>
									5. Kepatuhan dalam menjalankan semua Peraturan/Tata Tertib Peraturan.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_2" name="nilai_2" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">3.</td>
                <td><label for="solusi2">Analisa atas Masalah</label></td>
                <td>
									1. Mampu melakukan analisa atas trouble/problem yang dihadapi.</br>
									2. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.</br>
									3. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_3" name="nilai_3" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">4.</td>
                <td><label for="solusi2">Komunikasi & Kerja sama tim </label></td>
                <td>
									1. Mampu berkoordinasi lintas fungsi.</br>
									2. Mampu berkomunikasi dengan baik secara internal maupun eksternal
									divisi maupun perusahaan.</br>
									3. Mampu berkomunikasi dengan efektif.</br>
									4. Kesesuaian lokasi komunikasi (Personal/Group).</br>
									5. Kemampuan membuat pelaporan on time.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_4" name="nilai_4" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">5.</td>
                <td><label for="solusi2">Ketelitian, Administrasi dan Teknologi</label></td>
                <td>
									1. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan</br>
									2. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.</br>
									3. Pelaksanaan pekerjaan secara efektif dan minim human error.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_5" name="nilai_5" placeholder="Nilai">
                </td>
            </tr>
            <!-- Tambah baris lagi sesuai kebutuhan -->
        </tbody>
    </table>
</div>

			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<!-- Nilai Laporan Mingguan A -->

<!-- Nilai Laporan Mingguan B -->
<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Input Nilai Laporan Mingguan B</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form2', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
    <table class="table table-bordered table-sm">
        <tbody>
            <tr>
                <td style="width: 5%; text-align: center;">1.</td>
                <td style="width: 20%;"><label for="solusi">Inisiatif & Kreativitas</label></td>
                <td style="width: 60%;">
										1. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.</br>
										2. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_b1" name="nilai_b1" placeholder="Nilai" required>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">2.</td>
                <td><label for="solusi2">Kepatuhan Peraturan</label></td>
                <td>
									1. Memahami setiap SOP pekerjaaannya.</br>
									2. Melaksanakan pekerjaannya sesuai SOP yang diberikan.</br>
									3. Memahami Peraturan dan Tata Tertib Perusahaan.</br>
									4. Melaksanakan pekerjaan sesuai SOP dan Peraturan/Tata Tertib Perusahaan.</br>
									5. Kepatuhan dalam menjalankan semua Peraturan/Tata Tertib Peraturan.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_b2" name="nilai_b2" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">3.</td>
                <td><label for="solusi2">Analisa atas Masalah</label></td>
                <td>
									1. Mampu melakukan analisa atas trouble/problem yang dihadapi.</br>
									2. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.</br>
									3. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_b3" name="nilai_b3" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">4.</td>
                <td><label for="solusi2">Komunikasi & Kerja sama tim </label></td>
                <td>
									1. Mampu berkoordinasi lintas fungsi.</br>
									2. Mampu berkomunikasi dengan baik secara internal maupun eksternal
									divisi maupun perusahaan.</br>
									3. Mampu berkomunikasi dengan efektif.</br>
									4. Kesesuaian lokasi komunikasi (Personal/Group).</br>
									5. Kemampuan membuat pelaporan on time.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_b4" name="nilai_b4" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">5.</td>
                <td><label for="solusi2">Ketelitian, Administrasi dan Teknologi</label></td>
                <td>
									1. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan</br>
									2. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.</br>
									3. Pelaksanaan pekerjaan secara efektif dan minim human error.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_b5" name="nilai_b5" placeholder="Nilai">
                </td>
            </tr>
            <!-- Tambah baris lagi sesuai kebutuhan -->
        </tbody>
    </table>
</div>

			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<!-- Nilai Laporan Mingguan B -->

<!-- Nilai Pencapaian Mingguan A -->
<div id="main-modal-pencapaian1" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Input Nilai Pencapaian Mingguan A</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form3', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
    <table class="table table-bordered table-sm">
        <tbody>
            <tr>
                <td style="width: 5%; text-align: center;">1.</td>
                <td style="width: 20%;"><label for="solusi">Inisiatif & Kreativitas</label></td>
                <td style="width: 60%;">
										1. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.</br>
										2. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_1" name="nilai_1" placeholder="Nilai" required>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">2.</td>
                <td><label for="solusi2">Kepatuhan Peraturan</label></td>
                <td>
									1. Memahami setiap SOP pekerjaaannya.</br>
									2. Melaksanakan pekerjaannya sesuai SOP yang diberikan.</br>
									3. Memahami Peraturan dan Tata Tertib Perusahaan.</br>
									4. Melaksanakan pekerjaan sesuai SOP dan Peraturan/Tata Tertib Perusahaan.</br>
									5. Kepatuhan dalam menjalankan semua Peraturan/Tata Tertib Peraturan.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_2" name="nilai_2" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">3.</td>
                <td><label for="solusi2">Analisa atas Masalah</label></td>
                <td>
									1. Mampu melakukan analisa atas trouble/problem yang dihadapi.</br>
									2. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.</br>
									3. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_3" name="nilai_3" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">4.</td>
                <td><label for="solusi2">Komunikasi & Kerja sama tim </label></td>
                <td>
									1. Mampu berkoordinasi lintas fungsi.</br>
									2. Mampu berkomunikasi dengan baik secara internal maupun eksternal
									divisi maupun perusahaan.</br>
									3. Mampu berkomunikasi dengan efektif.</br>
									4. Kesesuaian lokasi komunikasi (Personal/Group).</br>
									5. Kemampuan membuat pelaporan on time.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_4" name="nilai_4" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">5.</td>
                <td><label for="solusi2">Ketelitian, Administrasi dan Teknologi</label></td>
                <td>
									1. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan</br>
									2. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.</br>
									3. Pelaksanaan pekerjaan secara efektif dan minim human error.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_5" name="nilai_5" placeholder="Nilai">
                </td>
            </tr>
            <!-- Tambah baris lagi sesuai kebutuhan -->
        </tbody>
    </table>
</div>

			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<!-- Nilai Pencapaian Mingguan A -->

<!-- Nilai Pencapaian Mingguan B -->
<div id="main-modal-pencapaian2" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Input Nilai Pencapaian Mingguan B</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form4', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
    <table class="table table-bordered table-sm">
        <tbody>
            <tr>
                <td style="width: 5%; text-align: center;">1.</td>
                <td style="width: 20%;"><label for="solusi">Inisiatif & Kreativitas</label></td>
                <td style="width: 60%;">
										1. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.</br>
										2. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_1" name="nilai_1" placeholder="Nilai" required>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">2.</td>
                <td><label for="solusi2">Kepatuhan Peraturan</label></td>
                <td>
									1. Memahami setiap SOP pekerjaaannya.</br>
									2. Melaksanakan pekerjaannya sesuai SOP yang diberikan.</br>
									3. Memahami Peraturan dan Tata Tertib Perusahaan.</br>
									4. Melaksanakan pekerjaan sesuai SOP dan Peraturan/Tata Tertib Perusahaan.</br>
									5. Kepatuhan dalam menjalankan semua Peraturan/Tata Tertib Peraturan.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_2" name="nilai_2" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">3.</td>
                <td><label for="solusi2">Analisa atas Masalah</label></td>
                <td>
									1. Mampu melakukan analisa atas trouble/problem yang dihadapi.</br>
									2. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.</br>
									3. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_3" name="nilai_3" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">4.</td>
                <td><label for="solusi2">Komunikasi & Kerja sama tim </label></td>
                <td>
									1. Mampu berkoordinasi lintas fungsi.</br>
									2. Mampu berkomunikasi dengan baik secara internal maupun eksternal
									divisi maupun perusahaan.</br>
									3. Mampu berkomunikasi dengan efektif.</br>
									4. Kesesuaian lokasi komunikasi (Personal/Group).</br>
									5. Kemampuan membuat pelaporan on time.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_4" name="nilai_4" placeholder="Nilai">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">5.</td>
                <td><label for="solusi2">Ketelitian, Administrasi dan Teknologi</label></td>
                <td>
									1. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan</br>
									2. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.</br>
									3. Pelaksanaan pekerjaan secara efektif dan minim human error.
								</td>
                <td>
                    <input type="text" class="form-control" id="nilai_5" name="nilai_5" placeholder="Nilai">
                </td>
            </tr>
            <!-- Tambah baris lagi sesuai kebutuhan -->
        </tbody>
    </table>
</div>

			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<!-- Nilai Pencapaian Mingguan B -->


<div id="main-modal-catatan1" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Catatan Laporan Mingguan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form5', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
				
					<div class="form-group">
						<label for="solusi" class="form-control-label">Catatan :</label>
						<textarea type="text" class="form-control" placeholder="Masukkan Catatan" id="catatan" name="catatan" required></textarea>
					</div>
					

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<div id="main-modal-catatan2" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Catatan Laporan Mingguan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form6', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
				
					<div class="form-group">
						<label for="solusi" class="form-control-label">Catatan :</label>
						<textarea type="text" class="form-control" placeholder="Masukkan Catatan" id="catatan" name="catatan" required></textarea>
					</div>
					

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

				$(document).on('click', '.btn-nilai1', function() {
					$('.btn-isactive').remove()
					var object = 'laporan'
					$('#main-modal-nilaia #modal-form').attr('action', 'laporan/update_nilai1')
					$('#main-modal-nilaia').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/edit/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal-nilaia #nilai_1').val(data[0].nilai_a1)
							$('#main-modal-nilaia #nilai_2').val(data[0].nilai_a2)
							$('#main-modal-nilaia #nilai_3').val(data[0].nilai_a3)
							$('#main-modal-nilaia #nilai_4').val(data[0].nilai_a4)
							$('#main-modal-nilaia #nilai_5').val(data[0].nilai_a5)
							$('#main-modal-nilaia #id_pelanggan').val(id)
						})
				})


        $(document).on('click', '.btn-nilai2', function() {
					$('.btn-isactive').remove()
					var object = 'laporan'
					$('#main-modal #modal-form2').attr('action', 'laporan/update_nilai2')
					$('#main-modal').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/edit/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal #nilai_b1').val(data[0].nilai_b1)
							$('#main-modal #nilai_b2').val(data[0].nilai_b2)
							$('#main-modal #nilai_b3').val(data[0].nilai_b3)
							$('#main-modal #nilai_b4').val(data[0].nilai_b4)
							$('#main-modal #nilai_b5').val(data[0].nilai_b5)
							$('#main-modal #id_pelanggan').val(id)
						})
				})
				
				$(document).on('click', '.btn-nilai3', function() {
					$('.btn-isactive').remove()
					var object = 'laporan'
					$('#main-modal-pencapaian1 #modal-form3').attr('action', 'laporan/update_nilai3')
					$('#main-modal-pencapaian1').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/edit/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal-pencapaian1 #nilai_1').val(data[0].nilai_pa1)
							$('#main-modal-pencapaian1 #nilai_2').val(data[0].nilai_pa2)
							$('#main-modal-pencapaian1 #nilai_3').val(data[0].nilai_pa3)
							$('#main-modal-pencapaian1 #nilai_4').val(data[0].nilai_pa4)
							$('#main-modal-pencapaian1 #nilai_5').val(data[0].nilai_pa5)
							$('#main-modal-pencapaian1 #id_pelanggan').val(id)
						})
				})

				$(document).on('click', '.btn-nilai4', function() {
					$('.btn-isactive').remove()
					var object = 'laporan'
					$('#main-modal-pencapaian2 #modal-form4').attr('action', 'laporan/update_nilai4')
					$('#main-modal-pencapaian2').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/edit/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal-pencapaian2 #nilai_1').val(data[0].nilai_pb1)
							$('#main-modal-pencapaian2 #nilai_2').val(data[0].nilai_pb2)
							$('#main-modal-pencapaian2 #nilai_3').val(data[0].nilai_pb3)
							$('#main-modal-pencapaian2 #nilai_4').val(data[0].nilai_pb4)
							$('#main-modal-pencapaian2 #nilai_5').val(data[0].nilai_pb5)
							$('#main-modal-pencapaian2 #id_pelanggan').val(id)
						})
				})

				$(document).on('click', '.btn-catatan1', function() {
					$('.btn-isactive').remove()
					var object = 'laporan'
					$('#main-modal-catatan1 #modal-form5').attr('action', 'laporan/update_catatan1')
					$('#main-modal-catatan1').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/edit/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal-catatan1 #catatan').val(data[0].kendala)
							$('#main-modal-catatan1 #id_pelanggan').val(id)
						})
				})

				$(document).on('click', '.btn-catatan2', function() {
					$('.btn-isactive').remove()
					var object = 'laporan'
					$('#main-modal-catatan2 #modal-form6').attr('action', 'laporan/update_catatan2')
					$('#main-modal-catatan2').modal()

					var id = $(this).attr("data-id")
					fetch(object + '/edit/' + id)
						.then(function(resp) {
							return resp.json()
						})
						.then(function(data) {
							$('#main-modal-catatan2 #catatan').val(data[0].solusi)
							$('#main-modal-catatan2 #id_pelanggan').val(id)
						})
				})
        
		
	 })

	function goBack() {
        window.history.back();
    }
</script>