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
					<!--<button type="button" class="btn btn-info float-right btn-selesai" style="margin-right: 10px;" id-Sijk="<?=$data_job->pengguna_id?>"> <i class="fas fa-check"></i> Selesaikan Penilaian</button>-->
					<button type="button" class="btn btn-danger float-right btn-petujuk" style="margin-right: 10px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Petunjuk Nilai</button>
				<br><br>

				<input type="hidden" id="id_po" value="<?= $data_job->pengguna_id ?>" />

				<?php $id = $data_job->pengguna_id ?> 
				<input type="hidden" name="id" id="id" value="<?= $data_job->pengguna_id ?>">

				<?php
				$bulk_nilai_allowed_users = array(744, 29, 759);
				$is_bulk_nilai_allowed = in_array((int) sessPenggunaId(), $bulk_nilai_allowed_users, true);
				?>

				<?php if ($is_bulk_nilai_allowed) { ?>
				<div class="card border-info mb-2">
					<div class="card-body p-3">
						<form id="bulk-nilai-form" class="row align-items-end">
							<div class="col-md-4 col-sm-12 mb-2 mb-md-0">
								<label for="bulk-nilai-input" class="mb-1"><strong>Bulk Action Nilai Penilai</strong></label>
								<input type="number" min="60" max="100" step="1" id="bulk-nilai-input" class="form-control" placeholder="Contoh: 95">
							</div>
							<div class="col-md-4 col-sm-12 mb-2 mb-md-0">
								<button type="submit" class="btn btn-primary" id="btn-bulk-nilai-submit">Terapkan & Simpan Otomatis</button>
							</div>
							<div class="col-md-4 col-sm-12">
								<small class="text-muted">Mengisi seluruh field nilai B lalu langsung menyimpan ke database (catatan tidak diubah).</small>
							</div>
						</form>
					</div>
				</div>
				<?php } ?>
		</div>

		<br>
		
    <?= form_open('laporan/inputNilaiLaporan', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
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
                                    <th style="text-align:center; width:15%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Nilai B</th>
                            </thead>
														
															<?php
																$total_nilai_b = 0;
																$jumlah_nilai_b = 0;

																foreach ($nilai_point_map as $poin) {
																		if (!empty($poin->nilai_b) && is_numeric($poin->nilai_b)) {
																				$total_nilai_b += $poin->nilai_b;
																				$jumlah_nilai_b++;
																		}
																}

																$nilaiRataLap = ($jumlah_nilai_b > 0) ? ($total_nilai_b / $jumlah_nilai_b) : 0;
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
																						echo 'style="background-color: red;"';
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
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $nama_hari . ', ' . $tgl_format : '' ?>&nbsp;</td>
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
																							$nilai_b = isset($nilai_point_map[$row->point]) ? $nilai_point_map[$row->point]->nilai_b : '';
																					?>
																							<input class="form-control" type="text" name="nilaib[<?= $row->point ?>]" value="<?= htmlspecialchars($nilai_b) ?>" style="background-color: #ffffff;">
																					<?php 
																					} 
																					?>
																				</td>

																		</tr>
																		<?php 
																		$firstRow = false;
																		endforeach; 
																		?>
																<?php endforeach; ?>

															
																<tr>
																		<td colspan="9" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata : &nbsp;</strong></td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataLap, 2) ?></strong>&nbsp;</td>
																</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<!--<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>-->
								
              	<button type="button" class="btn btn-success btn-save float-right">Simpan</button>
						</div>
				</div>

				

			</div>
		</div>
    <?= form_close(); ?>

		
		<br>

		<!-- PROJECT & TASK YANG DISELESAIKAN (SINKRON TASK & PROYEK) -->
		<div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap: 10px;">
					<div>
						<h5 class="font-weight-bold text-dark mb-1" style="font-size: 15px;">
							<i class="fas fa-tasks text-primary mr-1"></i> Project & Task yang Diselesaikan: <span class="text-primary font-weight-bold"><?= $startDate ?></span> s/d <span class="text-primary font-weight-bold"><?= $endDate ?></span>
						</h5>
						<div class="text-muted small">
							<i class="fas fa-sync-alt mr-1 text-success"></i> Terisi otomatis & sinkron dengan menu <strong>Task & Proyek</strong>
						</div>
					</div>
					<div class="d-flex align-items-center" style="gap: 8px;">
						<span class="badge badge-success font-weight-bold px-3 py-2" style="border-radius: 8px; font-size: 12px;">
							<i class="fas fa-check-circle mr-1"></i> <?= !empty($data_project_selesai) ? count($data_project_selesai) : 0 ?> Selesai
						</span>
						<a href="<?= base_url('task_proyek') ?>" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600; padding: 6px 14px;">
							<i class="fas fa-external-link-alt mr-1"></i> Menu Task & Proyek
						</a>
					</div>
				</div>
				
				<div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" style="min-width: 980px; width: 100%;">
                        <thead>
                            <tr style="background-color: #f1f5f9;">
                                <th style="width: 45px; text-align: center;">No</th>
                                <th style="width: 120px; text-align: center;">Kode Task</th>
                                <th style="min-width: 220px; text-align: left;">Judul & Deskripsi</th>
                                <th style="width: 150px; text-align: left;">Proyek / Jenis</th>
                                <th style="width: 140px; text-align: left;">Pemberi Tugas</th>
                                <th style="width: 120px; text-align: center;">Tgl Selesai</th>
                                <th style="width: 150px; text-align: left;">Hasil / Bukti</th>
                                <th style="width: 140px; text-align: center;">Rating & Ulasan</th>
                                <th style="width: 110px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no_ps = 1;
                            $count_ps = !empty($data_project_selesai) ? count($data_project_selesai) : 0;
                            if ($count_ps > 0):
                                foreach ($data_project_selesai as $t): 
                                    $tgl_selesai = !empty($t->tgl_dinilai) ? $t->tgl_dinilai : (!empty($t->tgl_submit) ? $t->tgl_submit : $t->updated_at);
                                    $prio_badge = 'badge-secondary';
                                    if ($t->prioritas === 'Urgent') $prio_badge = 'badge-danger';
                                    elseif ($t->prioritas === 'High') $prio_badge = 'badge-warning text-dark';
                                    elseif ($t->prioritas === 'Medium') $prio_badge = 'badge-info';
                            ?>
                                <tr>
                                    <td style="text-align:center; font-weight: 600; color: #475569;"><?= $no_ps++ ?></td>
                                    <td style="text-align:center;">
                                        <div class="font-weight-bold text-dark" style="font-size: 12px;"><?= htmlspecialchars($t->kode_task) ?></div>
                                        <span class="badge <?= $prio_badge ?> mt-1" style="font-size: 10px; padding: 2px 6px;"><?= htmlspecialchars($t->prioritas ?: 'Normal') ?></span>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark mb-1" style="font-size: 13px;"><?= htmlspecialchars($t->judul) ?></div>
                                        <?php if (!empty($t->deskripsi)): ?>
                                            <div class="text-muted small" style="line-height: 1.4;"><?= nl2br(htmlspecialchars(character_limiter($t->deskripsi, 120))) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($t->nama_proyek)): ?>
                                            <div class="font-weight-600 text-dark small mb-1"><i class="fas fa-folder-open text-primary mr-1"></i><?= htmlspecialchars($t->nama_proyek) ?></div>
                                        <?php endif; ?>
                                        <span class="badge badge-light-primary text-primary" style="font-size: 11px;"><?= htmlspecialchars($t->jenis ?: 'Task') ?></span>
                                    </td>
                                    <td>
                                        <div class="font-weight-600 text-dark small"><?= htmlspecialchars($t->nama_pemberi ?: '-') ?></div>
                                        <?php if (!empty($t->jabatan_pemberi)): ?>
                                            <div class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($t->jabatan_pemberi) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center; white-space: nowrap; font-size: 12px;">
                                        <i class="far fa-calendar-check text-success mr-1"></i><?= date('d-m-Y', strtotime($tgl_selesai)) ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (!empty($t->bukti_selesai)): 
                                            $is_url = filter_var($t->bukti_selesai, FILTER_VALIDATE_URL) || preg_match('/^(http|https):\/\//i', $t->bukti_selesai);
                                            if ($is_url):
                                        ?>
                                            <a href="<?= htmlspecialchars($t->bukti_selesai, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-primary" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;">
                                                <i class="fas fa-external-link-alt mr-1"></i> Bukti Kerja
                                            </a>
                                        <?php else: ?>
                                            <span class="small text-dark font-weight-500"><?= htmlspecialchars($t->bukti_selesai) ?></span>
                                        <?php endif; endif; ?>

                                        <?php if (!empty($t->catatan_bawahan)): ?>
                                            <div class="text-muted small mt-1" style="font-style: italic; line-height: 1.3;">"<?= htmlspecialchars(character_limiter($t->catatan_bawahan, 80)) ?>"</div>
                                        <?php endif; ?>

                                        <?php if (empty($t->bukti_selesai) && empty($t->catatan_bawahan)): ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center;">
                                        <?php if (!empty($t->rating_bintang)): ?>
                                            <div class="text-warning mb-1" style="font-size: 11px; letter-spacing: 1px;">
                                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                                    <?= $s <= (int)$t->rating_bintang ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>' ?>
                                                <?php endfor; ?>
                                                <span class="font-weight-bold text-dark ml-1" style="font-size: 12px;"><?= $t->rating_bintang ?>/5</span>
                                            </div>
                                            <?php if (!empty($t->ulasan_atasan)): ?>
                                                <div class="text-muted small" style="font-style: italic; line-height: 1.3;">"<?= htmlspecialchars(character_limiter($t->ulasan_atasan, 60)) ?>"</div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-warning text-dark small px-2 py-1" style="border-radius: 6px;">
                                                <i class="far fa-clock mr-1"></i> Menunggu Rating
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center;">
                                        <?php if ((int)$t->status_task === 4): ?>
                                            <span class="badge badge-success px-2 py-1" style="border-radius: 6px; font-size: 11px;">
                                                <i class="fas fa-check-double mr-1"></i> Selesai
                                            </span>
                                        <?php elseif ((int)$t->status_task === 2): ?>
                                            <span class="badge badge-warning text-dark px-2 py-1" style="border-radius: 6px; font-size: 11px;">
                                                <i class="fas fa-hourglass-half mr-1"></i> Review
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-info px-2 py-1" style="border-radius: 6px; font-size: 11px;">
                                                <?= (int)$t->progres ?>%
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4" style="background: #fafbfc;">
                                        <div class="my-2">
                                            <i class="fas fa-clipboard-check text-muted fa-2x d-block mb-2" style="opacity: 0.5;"></i>
                                            <span class="font-weight-bold text-dark">Belum ada project atau task yang diselesaikan pada periode minggu ini.</span><br>
                                            <small class="text-muted">Task atau proyek yang diselesaikan di menu <a href="<?= base_url('task_proyek') ?>" target="_blank" class="font-weight-bold">Task & Proyek</a> akan otomatis sinkron dan muncul di sini.</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
		<!-- PROJECT & TASK YANG DISELESAIKAN -->

        <?php 
        $jumlah_data_pencapaian = !empty($data_detail_pencapaian) ? count($data_detail_pencapaian) : 0;
        $total_nilai = 0;
        $nilaiRataPenc = 0;

        if ($jumlah_data_pencapaian > 0): 
        ?>
		<?= form_open('laporan/inputNilaiPencapaian', array('id' => 'main-form-pencapaian', 'autocomplete' => 'off')); ?>
		<!-- ARSIP PENCAPAIAN SEBELUMNYA -->
		<div class="card mb-4 border-warning" style="background-color: #fffdfa; border-radius: 10px;">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex align-items-center justify-content-between mb-3">
					<div>
						<h5 class="font-weight-bold text-dark mb-0" style="font-size: 14.5px;">
							<i class="fas fa-archive text-warning mr-1"></i> Riwayat Pencapaian Sebelumnya: <span class="text-primary font-weight-bold"><?= $startDate ?></span> s/d <span class="text-primary font-weight-bold"><?= $endDate ?></span>
							<span class="badge badge-warning ml-2" style="font-size: 11px;">Data Arsip</span>
						</h5>
						<div class="text-muted small mt-1">Data pencapaian manual periode ini tetap tersimpan utuh dan dapat ditinjau.</div>
					</div>
				</div>
				<div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover mb-0" id="kt_table_1" style="width: 100%;">
                        <thead>
                            <tr style="background-color: #e2e8f0;">
                                <th style="text-align:center; width: 45px;">No</th>
                                <th style="text-align:center; width: 140px;">Tanggal</th>
                                <th style="text-align:left;">Deskripsi</th>
                                <th style="text-align:center; width: 150px;">Hasil Kerja</th>
                                <th style="text-align:center; width: 90px;">Nilai B</th>
                                <th style="text-align:left; width: 200px;">Catatan B</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($data_detail_pencapaian as $row):
                                if (!empty($row->link) && empty($row->keterangan)) {
                                    $link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-link"></i> Hasil Kerja</a>';
                                } elseif (empty($row->link) && !empty($row->keterangan)) {
                                    $link_download = htmlspecialchars($row->keterangan, ENT_QUOTES, 'UTF-8');
                                } else {
                                    $link_download = '<span class="text-muted small">-</span>';
                                }

                                $hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
                                $nama_hari = isset($hari[date('l', strtotime($row->tanggal))]) ? $hari[date('l', strtotime($row->tanggal))] : '-';

                                $id = $row->id;
                                $total_nilai += (float)$row->nilai_b;
                            ?>
                                <tr>
                                    <td style="text-align:center;"><?= $no++ ?></td>
                                    <td style="text-align:center; white-space: nowrap;"><?= $nama_hari . ', ' . date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                    <td><?= htmlspecialchars($row->detail) ?></td>
                                    <td style="text-align:center"><?= $link_download ?></td>
                                    <td><input class="form-control form-control-sm text-center" type="text" name="nilaipb[<?= $id ?>]" value="<?= $row->nilai_b ?>"></td>
                                    <td><input class="form-control form-control-sm" type="text" name="catatanpb[<?= $id ?>]" value="<?= htmlspecialchars($row->catatan_b) ?>"></td>
                                </tr>
                            <?php endforeach; 
                            $nilaiRataPenc = $jumlah_data_pencapaian > 0 ? $total_nilai / $jumlah_data_pencapaian : 0;
                            ?>
                            <tr class="row-summary font-weight-bold">
                                <td colspan="4" style="text-align:right;">Rata-rata :</td>
                                <td style="text-align:center; color: #2563eb;"><?= number_format($nilaiRataPenc, 2) ?></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
				</div>
				<div class="mt-3 clearfix">
					<button type="button" class="btn btn-success btn-save float-right">Simpan Penilaian Arsip</button>
				</div>
			</div>
		</div>
		<!-- ARSIP PENCAPAIAN SEBELUMNYA -->
		<?= form_close(); ?>
        <?php endif; ?>
		


		<br>

<?= form_open('laporan/inputNilaiUmum', array('id' => 'main-form-umum', 'autocomplete' => 'off')); ?>
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
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'> Nilai B</th>
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
													<td><input class="form-control" type="text" name="nilaib1" value="<?= htmlspecialchars($penilaian_umum->nilaib1) ?>" style="background-color: #ffffff;"></td>
													<td><input class="form-control" type="text" name="catatanb1" value="<?= htmlspecialchars($penilaian_umum->catatanb1) ?>" style="background-color: #ffffff;"></td>
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
													<td><input class="form-control" type="text" name="nilaib2" value="<?= htmlspecialchars($penilaian_umum->nilaib2) ?>" style="background-color: #ffffff;"></td>
													<td><input class="form-control" type="text" name="catatanb2" value="<?= htmlspecialchars($penilaian_umum->catatanb2) ?>" style="background-color: #ffffff;"></td>
											</tr>
											<tr>
													<td style="text-align:center">3</td>
													<td style="text-align:center">Analisa atas Masalah	</td>
													<td style="text-align:left;">a. Mampu melakukan analisa atas trouble/problem yang dihadapi.<br>
															b. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.<br>
															c. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.				
													</td>
													<td><input class="form-control" type="text" name="nilaib3" value="<?= htmlspecialchars($penilaian_umum->nilaib3) ?>" style="background-color: #ffffff;"></td>
													<td><input class="form-control" type="text" name="catatanb3" value="<?= htmlspecialchars($penilaian_umum->catatanb3) ?>" style="background-color: #ffffff;"></td>
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
													<td><input class="form-control" type="text" name="nilaib4" value="<?= htmlspecialchars($penilaian_umum->nilaib4) ?>" style="background-color: #ffffff;"></td>
													<td><input class="form-control" type="text" name="catatanb4" value="<?= htmlspecialchars($penilaian_umum->catatanb4) ?>" style="background-color: #ffffff;"></td>
											</tr>
											<tr>
													<td style="text-align:center">5</td>
													<td style="text-align:center">Ketelitian, Administrasi dan Teknologi	</td>
													<td style="text-align:left;">a. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan.<br>
															b. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.<br>
															c. Pelaksanaan pekerjaan secara efektif dan minim human error.						
													</td>
													<td><input class="form-control" type="text" name="nilaib5" value="<?= htmlspecialchars($penilaian_umum->nilaib5) ?>" style="background-color: #ffffff;"></td>
													<td><input class="form-control" type="text" name="catatanb5" value="<?= htmlspecialchars($penilaian_umum->catatanb5) ?>" style="background-color: #ffffff;"></td>
											</tr>
											<?php
												$nilaiRataPumum = ($penilaian_umum->nilaib1 + $penilaian_umum->nilaib2 + $penilaian_umum->nilaib3 + $penilaian_umum->nilaib4 + $penilaian_umum->nilaib5)/5;
											?>
											<tr class="fw-bold bg-light">
                        <td colspan="3" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata :</strong></td>
                        <td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($nilaiRataPumum, 2) ?></strong></td>
												<td></td>
											</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
								
								<button type="button" class="btn btn-success btn-save float-right">Simpan</button>
                <button type="button" class="btn btn-info float-right btn-selesai" style="margin-right: 10px;" id-Sijk="<?=$data_job->pengguna_id?>"> <i class="fas fa-check"></i> Selesaikan Penilaian </button>
						</div>
				</div>

			</div>
		</div>
		<!-- PENILAIAN UMUM -->
		<?= form_close(); ?>
	</div>
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

				var isBulkNilaiAllowed = <?= $is_bulk_nilai_allowed ? 'true' : 'false' ?>;

				if (isBulkNilaiAllowed) {
					var bulkSaveTargets = [
						{
							formSelector: '#main-form',
							requiredSelector: 'input[name^="nilaib["]',
							label: 'Nilai Laporan Mingguan'
						},
						{
							formSelector: '#main-form-pencapaian',
							requiredSelector: 'input[name^="nilaipb["]',
							label: 'Nilai Pencapaian'
						},
						{
							formSelector: '#main-form-umum',
							requiredSelector: 'input[name="nilaib1"], input[name="nilaib2"], input[name="nilaib3"], input[name="nilaib4"], input[name="nilaib5"]',
							label: 'Nilai Umum'
						}
					];

					function submitBulkForm(target) {
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

					function submitBulkSequential(index, onDone, onError) {
						if (index >= bulkSaveTargets.length) {
							onDone();
							return;
						}

						var target = bulkSaveTargets[index];
						submitBulkForm(target)
							.done(function(resp) {
								if (resp && resp.status === 'error') {
									onError(target.label, resp.msg || 'Terjadi kesalahan saat menyimpan.');
									return;
								}

								submitBulkSequential(index + 1, onDone, onError);
							})
							.fail(function(xhr) {
								var msg = 'Gagal menyimpan data.';
								if (xhr && xhr.responseJSON && xhr.responseJSON.msg) {
									msg = xhr.responseJSON.msg;
								}
								onError(target.label, msg);
							});
					}

					$(document).on('submit', '#bulk-nilai-form', function(e) {
						e.preventDefault();

						var nilaiInput = $('#bulk-nilai-input').val();
						var nilaiBulk = parseFloat(nilaiInput);
						var $submitBtn = $('#btn-bulk-nilai-submit');

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

						$('input[name^="nilaib["], input[name^="nilaipb["], input[name="nilaib1"], input[name="nilaib2"], input[name="nilaib3"], input[name="nilaib4"], input[name="nilaib5"]').each(function() {
							$(this).val(nilaiFinal);
						});

						$submitBtn.prop('disabled', true);

						Swal.fire({
							html: '<h4>Menyimpan bulk nilai ke database...</h4>',
							icon: 'info',
							allowOutsideClick: false,
							showConfirmButton: false
						});

						submitBulkSequential(
							0,
							function() {
								$submitBtn.prop('disabled', false);
								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: 'Bulk action berhasil disimpan otomatis ke database.',
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
				title: 'Selesaikan Penilaian Laporan Mingguan? (Akan Kirim Notifikasi Ke HR)',
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