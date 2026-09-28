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
				<br> <!--<br><strong class="fw-bold text-dark">Semester : <?= $data_job->nama ?></strong>
				<br><strong class="fw-bold text-dark">Tahun &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->nama ?></strong>-->

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
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Progress Kerja</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Kendala &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Solusi &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Status Pekerjaan &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>&nbsp; Hasil Kerja &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Pihak Terkait</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp;&nbsp;&nbsp; Nilai &nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php 
																$no = 1;
																$total_nilai = 0; // Variabel untuk menampung total nilai
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
																				if (!empty($row->id_lap)) {
																						$total_nilai += $row->nilai;
																						$jumlah_data++; // Hanya hitung jika data valid
																				}

																				$id_lap = $row->id_lap;
																		?>
																		<tr <?php if ($is_outside_range && !empty($row->id_lap)) { echo 'style="background-color: red;"'; } ?>>

																				<?php if ($firstRow): ?>
																						<td rowspan="<?= $rowspan ?>" style="text-align:center"><font color='#000000'>&nbsp;<?= $no++ ?>&nbsp;</td>
																						<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><?= $row->deskripsi ?></td>
																				<?php endif; ?>

																				<td style="text-align:center"><font color='#000000'>&nbsp;<input type="hidden" name="id_sodetail[]" value="<?= $id_lap ?>"><?= !empty($row->id_lap) ? $row->jenis : '' ?>&nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $nama_hari . ', ' . $tgl_format : '' ?>&nbsp;</td>
																				<td style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->progress : '' ?>  &nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->kendala : '' ?>&nbsp;</td>
																				<td><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->solusi : '' ?>&nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->status_pekerjaan : '' ?>&nbsp;</td>
																				<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $link_download : '' ?>&nbsp;</td>
																				<td><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->pihak : '' ?>&nbsp;</td>
																				<td style="width:15%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->keterangan : '' ?>&nbsp;</td>
                                        <td>
																						<?php if(!empty($row->id_lap)) { ?>
																							<input class="form-control" type="text" name="nilai[<?= $id_lap ?>]" value="<?= $row->nilai ?>" style="background-color: #e0e0e0;">
																						<?php } ?>
																				</td>
																				
																		</tr>
																		<?php 
																		$firstRow = false;
																		endforeach; 
																		?>
																<?php endforeach; ?>

																<?php 
																// Menghitung rata-rata hanya jika ada data valid
																$nilaiRata = ($jumlah_data > 0) ? ($total_nilai / $jumlah_data) : 0;
																?>

																<tr>
																		<td colspan="11" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata : &nbsp;</strong></td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRata, 2) ?></strong>&nbsp;</td>
																</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<a href="<?= base_url('laporan/show/detail/laporan/' . $id_pengaju . '/' . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/laporan/' . $id_pengaju . '/' . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
						</div>
				</div>

				<br><br>
					<div role="document">
								<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
							
							<?php if($data_job->status != 5){ ?>
                <button type="button" class="btn btn-success btn-save float-right">Simpan</button>
              <?php } ?>

						
					<br>
					<br>

			</div>
		</div>
		<?= form_close(); ?>
	</div>
</div>


<script>
	document.addEventListener('DOMContentLoaded', function() {
        var level_ttd = $('#level_ttd').val();		
        
		
	 })

	function goBack() {
        window.history.back();
    }
</script>