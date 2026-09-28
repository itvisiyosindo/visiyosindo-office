
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
</style>


<div class="row">
	<div class="col">
		<div class="">
			<!--<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a><br>-->
		</div>
		<div class="card-body">
				<div class="row mb-3 align-items-center">
					<div class="col-md-7 mb-2 mb-md-0">
						<a href="<?= base_url('laporan/print_mingguan/' . encrypt(sessPenggunaId()) . '/' . $week_offset) ?>" target="_blank" class="btn btn-sm btn-success">
							<i class="fas fa-print"></i>&nbsp; Cetak Laporan Mingguan Ini
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
								<?php for ($i = 0; $i >= -20; $i--): ?>
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
									<option value="<?= base_url('laporan/show/list/rev2/' . $i) ?>" <?= $sel ?>><?= $label ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</div>
				</div>
				<strong class="fw-bold text-dark">Keterangan Nilai : </strong>
				<!--<br><strong class="fw-bold text-dark"> &lt;60 &nbsp; = &nbsp; Buruk Sekali  &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 60-69 &nbsp; = &nbsp; Buruk  
					&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 70-79 &nbsp; = &nbsp; Biasa Saja  &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 80-89 &nbsp; = &nbsp; Baik  
					&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 90-100 &nbsp; = &nbsp; Sangat Baik </strong>-->
				<br><strong class="fw-bold text-dark">
									96 – 100 = Istimewa &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 90 – 95 = Sangat Baik &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 85 – 89 = Baik 
									&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 80 – 84 = Cukup Baik &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 75 – 79 = Cukup &nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; 70 – 74 = Kurang 
									&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp;  60 – 69 = Kurang Sekali 
				</strong>
								
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Laporan Mingguan dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>
						
					<!--<div style="overflow-x: auto; white-space: nowrap; max-width: 100%;">
                        <table border="1" class="striped" style="width: 100%; min-width: 2500px;">-->
				<div style="max-width: 100%;">									
    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width: 100%; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'>&nbsp; No &nbsp;</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Jobdesk </th>
																		<th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> + </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Jenis</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Tanggal </th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan Proyek</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp; Status Pekerjaan &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>&nbsp; Hasil Kerja &nbsp;</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Pihak Terkait</th>
                                    <th style="text-align:center; width:20%; word-wrap: break-word; white-space: normal;" bgcolor="#C6DEFF"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Nilai A</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'>Nilai B</th>
                                		<th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'>  Aksi </th>
																</tr>
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
    if (!isset($grouped_data[$row->id])) {
        $grouped_data[$row->id] = [];
    }
    $grouped_data[$row->id][] = $row;

    // Tandai point mana saja yang memiliki laporan terisi pada minggu ini
    if (!empty($row->id_lap) && $row->jenis !== 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
        if (!empty($row->point)) {
            $point_has_lap[$row->point] = true;
        }
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
																						$link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-link"></i> Link</a>';
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
                                        <td rowspan="<?= $rowspan ?>" style="text-align:center">&nbsp;<button type="button" class="btn btn-sm btn-primary btn-edit2" data-id="<?= $row->id ?>"><i class="icons icon-plus"></i></button>&nbsp;</td>
                                        <?php }else{ ?>
                                        <td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><b><?= $row->deskripsi ?></b></font></td>
                                        <td rowspan="<?= $rowspan ?>"></td>
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
																				<td style="text-align:center">
                                        <?php 
                                        if ($row->nilai_isi == 1) { 
                                            $nilai_a = isset($nilai_point_map[$row->point]) ? $nilai_point_map[$row->point]->nilai_a : '';
                                        ?>
                                            <font color='#000000'><?= htmlspecialchars($nilai_a) ?></font>
                                        <?php 
                                        } 
                                        ?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php 
                                        if ($row->nilai_isi == 1) { 
                                            $nilai_b = isset($nilai_point_map[$row->point]) ? $nilai_point_map[$row->point]->nilai_b : '';
                                        ?>
                                            <font color='#000000'><?= htmlspecialchars($nilai_b) ?></font>
                                        <?php 
                                        } 
                                        ?>
                                    </td>
																				<td style="text-align:center">&nbsp;
																						<?php if(!empty($row->id_lap)){ ?>
																							<?php if($nilai_a == '' && $nilai_b == ''){ ?>
																								<button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= encrypt($row->id_lap) ?>"><i class="bx bx-pencil"></i></button>
																								<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id_lap ?>" data-object="laporan/delete/<?= $row->id_lap ?>"> <i class="bx bx-trash"></i> </button>
																							<?php }?>		 
																						<?php }?> &nbsp;
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
																$nilaiRata_b = ($jumlah_data > 0) ? ($total_nilai_b / $jumlah_data) : 0;
																$nilaiRataAll = ($jumlah_data > 0) ? ($nilaiRata + $nilaiRata_b)/2 : 0;
																?>

																<tr>
																		<td colspan="10" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata : &nbsp;</strong></td>		
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataLapA, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataLap, 2) ?></strong>&nbsp;</td>
																		<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($rataAll, 2) ?></strong>&nbsp;</td>
																
																</tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<!--<a href="<?= base_url('laporan/show/list/rev2/' . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/list/rev2/' . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>-->
						</div>
				</div>

			</div>
		</div>

		</div>
</div>
<div class="row">
	<div class="col">


		<!-- PENCAPAIAN -->
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Pencapaian Mingguan dari Tanggal <strong> <?= $startDate ?> </strong> sampai <strong> <?= $endDate ?> </strong></h4>

						
					<div class="">
						<a href="javascript:;" id="btn-show-add-form-pencapaian" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a><br>
					</div> <br>
						
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
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Catatan A</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Catatan B</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'> Aksi</th>
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
                                        <td style="text-align:center"><font color='#000000'><input type="hidden" name="id_sodetail[]" value="<?= $id ?>"><?= $no++ ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $nama_hari . ', ' . date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                        <td><font color='#000000'><?= $row->detail ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $link_download ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->nilai_a ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->nilai_b ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->catatan_a ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->catatan_b ?></td>
                                        <td style="text-align:center">
																					<?php if($row->nilai_a == '' && $row->nilai_b == ''){ ?>
																						<button type="button" class="btn btn-sm btn-primary btn-edit-pencapaian" data-id="<?= $id_edit ?>"><i class="bx bx-pencil"></i></button>
                    												<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id ?>" data-object="laporan/deletePencapaian/<?= $row->id ?>"> <i class="bx bx-trash"></i> </button>
																				  <?php } ?>
																				</td>
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
                                </tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<!--<a href="<?= base_url('laporan/show/list/rev2/' . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/list/rev2/' . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>-->
						</div>
				</div>

			</div>
		</div>
		<!-- PENCAPAIAN -->

	</div>
</div>
<div class="row">
	<div class="col">

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
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="3%"><font color='#000000'> Nilai B</th>
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
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaia1) ?></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib1) ?></td>
													<td><?= htmlspecialchars($penilaian_umum->catatana1) ?></td>
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
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaia2) ?></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib2) ?></td>
													<td><?= htmlspecialchars($penilaian_umum->catatana2) ?></td>
													<td><?= htmlspecialchars($penilaian_umum->catatanb2) ?></td>
											</tr>
											<tr>
													<td style="text-align:center">3</td>
													<td style="text-align:center">Analisa atas Masalah	</td>
													<td style="text-align:left;">a. Mampu melakukan analisa atas trouble/problem yang dihadapi.<br>
															b. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.<br>
															c. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.				
													</td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaia3) ?></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib3) ?></td>
													<td><?= htmlspecialchars($penilaian_umum->catatana3) ?></td>
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
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaia4) ?></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib4) ?></td>
													<td><?= htmlspecialchars($penilaian_umum->catatana4) ?></td>
													<td><?= htmlspecialchars($penilaian_umum->catatanb4) ?></td>
											</tr>
											<tr>
													<td style="text-align:center">5</td>
													<td style="text-align:center">Ketelitian, Administrasi dan Teknologi	</td>
													<td style="text-align:left;">a. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan.<br>
															b. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.<br>
															c. Pelaksanaan pekerjaan secara efektif dan minim human error.						
													</td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaia5) ?></td>
													<td style="text-align:center"><?= htmlspecialchars($penilaian_umum->nilaib5) ?></td>
													<td><?= htmlspecialchars($penilaian_umum->catatana5) ?></td>
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

								
								<a href="<?= base_url('laporan/show/list/rev2/' . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/list/rev2/' . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
						</div>
				</div>

			</div>
		</div>
		<!-- PENILAIAN UMUM -->


	</div>
</div>



<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Laporan Mingguan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					
					
					<div class="form-group">
						<label for="waktu" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal" name="tanggal" required>
						</div>
					</div>
					
					<div class="form-group">
						<label for="jobdesc" class="form-control-label">Pilih Jobdesk <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jobdesc" name="jobdesc" required onchange="toggleFormStatus()">
							<option value="">- Pilih Jobdesk -</option>
							<!--<option value="0">- Lainnya -</option>-->
							<?php
							foreach ($list_job as $row) {
								echo '<option value="' . $row->id_pod . '">' . $row->deskripsi . '</option>';
							}
							?>
						</select>
					</div>
					<input type="hidden" id="jobdesc_text" name="jobdesc_text">

					<div class="form-group" id="form_keterangan_konfirmasi" style="display:none;">
							<label for="keterangan_konfirmasi" class="form-control-label">Jobdesc Lainnya <span class="text-danger">*</span> :</label>
							<textarea type="text" class="form-control" id="keterangan_konfirmasi" name="keterangan_konfirmasi" required></textarea>
					</div>
					
					<div class="form-group">
						<label for="jenis" class="form-control-label">Jenis <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jenis" name="jenis" required>
              <option value = "JOBDESK RUTIN">JOBDESK RUTIN</option>
              <option value = "PROJECT TAMBAHAN">PROJECT TAMBAHAN</option>
              <option value = "TIDAK ADA PEKERJAAN DI MINGGU INI">TIDAK ADA PEKERJAAN DI MINGGU INI</option>
						</select>
					</div>

					<div class="form-group">
						<label for="progress" class="form-control-label">Progress <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Progress Pekerjaan Anda" name="progress" id="progress" cols="10" rows="5"></textarea>
					</div>

					
					<div class="form-group">
						<label for="status_pekerjaan" class="form-control-label">Status Pekerjaan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="status_pekerjaan" name="status_pekerjaan" required>
              <option value = "SELESAI">SELESAI</option>
              <option value = "DALAM PROSES">DALAM PROSES</option>
              <option value = "MENUNGGU KONFIRMASI">MENUNGGU KONFIRMASI</option>
              <option value = "PENDING">PENDING</option>
              <option value = "TIDAK SELESAI">TIDAK SELESAI</option>
						</select>
					</div>
					<div class="form-group">
						<label for="hasil" class="form-control-label">Hasil Pekerjaan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="hasil" name="hasil" required onchange="toggleFormHasil()">
							<option value="">- Pilih Jenis Hasil -</option>
							<option value="1">Link Bukti Pekerjaan</option>
							<option value="2">Keterangan</option>
						</select>
					</div>


					<div class="form-group" id="form_ket_hasil" style="display:none;">
							<label for="ket_hasil" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
							<textarea type="text" class="form-control" id="ket_hasil" name="ket_hasil" required></textarea>
					</div>

					<div class="form-group" id="form_link" style="display:none;">
						<label for="link" class="form-control-label">Link Bukti Pekerjaan <span class="text-danger">*</span>:</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Hasil / Bukti Pekerjaan Anda" id="link" name="link" required>
					</div>


					<div class="form-group">
							<label for="pihak" class="form-control-label">Nama Pihak Terkait :</label>
							<select class="form-control" id="pihak" name="pihak" required>
									<option value="" selected>- TIDAK ADA -</option>
									<?php if (!empty($list_nama)): ?>
											<?php foreach ($list_nama as $row): ?>
													<option value="<?= htmlspecialchars(trim($row->nama)) ?>">
															<?= htmlspecialchars($row->nama) ?>
													</option>
											<?php endforeach; ?>
									<?php endif; ?>
							</select>
					</div>


					<div class="form-group" id="keterangan-group" style="display: none;">
							<label for="keterangan" class="form-control-label">Keterangan yang Dikerjakan oleh Pihak Terkait :</label>
							<textarea class="form-control" placeholder="Masukkan Keterangan yang Dikerjakan oleh Pihak Terkait" 
												name="keterangan" id="keterangan" cols="10" rows="2"></textarea>
					</div>

					<!--<div class="form-group">
						<label for="pencapaian" class="form-control-label">Apakah ini termasuk Pencapaian ?<span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="pencapaian" name="pencapaian" required>
              <option value = "1">Tidak</option>
              <option value = "2">Ya</option>
						</select>
					</div>-->
					


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



<div id="main-modal2" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Input Laporan Mingguan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form2', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					
					
					<div class="form-group">
						<label for="waktu" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal" name="tanggal" required>
						</div>
					</div>
					<input type="hidden" name="week_offset7" value="0">

									
					
					<div class="form-group">
						<label for="jenis" class="form-control-label">Jenis <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jenis" name="jenis" required>
              <option value = "JOBDESK RUTIN">JOBDESK RUTIN</option>
              <option value = "PROJECT TAMBAHAN">PROJECT TAMBAHAN</option>
              <option value = "TIDAK ADA PEKERJAAN DI MINGGU INI">TIDAK ADA PEKERJAAN DI MINGGU INI</option>
						</select>
					</div>

					<div class="form-group">
						<label for="progress" class="form-control-label">Progress (Keterangan Proyek) <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Progress Pekerjaan Anda" name="progress" id="progress" cols="10" rows="5"></textarea>
					</div>

					
					<div class="form-group">
						<label for="status_pekerjaan" class="form-control-label">Status Pekerjaan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="status_pekerjaan" name="status_pekerjaan" required>
              <option value = "SELESAI">SELESAI</option>
              <option value = "DALAM PROSES">DALAM PROSES</option>
              <option value = "MENUNGGU KONFIRMASI">MENUNGGU KONFIRMASI</option>
              <option value = "PENDING">PENDING</option>
              <option value = "TIDAK SELESAI">TIDAK SELESAI</option>
						</select>
					</div>

					<div class="form-group">
						<label for="hasil2" class="form-control-label">Hasil Pekerjaan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="hasil2" name="hasil2" required onchange="toggleFormHasil2()">
							<option value="">- Pilih Jenis Hasil -</option>
							<option value="1">Link Bukti Pekerjaan</option>
							<option value="2">Keterangan</option>
						</select>
					</div>


					<div class="form-group" id="form_ket_hasil2" style="display:none;">
							<label for="ket_hasil" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
							<textarea type="text" class="form-control" id="ket_hasil" name="ket_hasil" required></textarea>
					</div>

					<div class="form-group" id="form_link2" style="display:none;">
						<label for="link" class="form-control-label">Link Bukti Pekerjaan <span class="text-danger">*</span>:</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Hasil / Bukti Pekerjaan Anda" id="link" name="link" required>
					</div>


					<div class="form-group">
							<label for="pihak2" class="form-control-label">Nama Pihak Terkait :</label>
							<select class="form-control" id="pihak2" name="pihak2" required>
									<option value="" selected>- TIDAK ADA -</option>
									<?php if (!empty($list_nama)): ?>
											<?php foreach ($list_nama as $row): ?>
													<option value="<?= htmlspecialchars(trim($row->nama)) ?>">
															<?= htmlspecialchars($row->nama) ?>
													</option>
											<?php endforeach; ?>
									<?php endif; ?>
							</select>
					</div>


					<div class="form-group" id="keterangan-group2" style="display: none;">
							<label for="keterangan" class="form-control-label">Keterangan yang Dikerjakan oleh Pihak Terkait :</label>
							<textarea class="form-control" placeholder="Masukkan Keterangan yang Dikerjakan oleh Pihak Terkait" 
												name="keterangan" id="keterangan" cols="10" rows="2"></textarea>
					</div>

					<!--<div class="form-group">
						<label for="pencapaian" class="form-control-label">Apakah ini termasuk Pencapaian ?<span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="pencapaian" name="pencapaian" required>
              <option value = "1">Tidak</option>
              <option value = "2">Ya</option>
						</select>
					</div>-->
					


				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="jobdesc" name="jobdesc">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save7">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>




<div id="main-modal-pencapaian" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pencapaian Mingguan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-pencapaian', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					
					
					<div class="form-group">
						<label for="waktu" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal" name="tanggal" required>
						</div>
					</div>
					
					

					<div class="form-group">
						<label for="detail" class="form-control-label">Detail <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Detail Pencapaian Anda" name="detail" id="detail" cols="10" rows="5"></textarea>
					</div>

					
					<div class="form-group">
						<label for="hasil3" class="form-control-label">Hasil Pekerjaan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="hasil3" name="hasil3" required onchange="toggleFormHasil3()">
							<option value="">- Pilih Jenis Hasil -</option>
							<option value="1">Link Bukti Pekerjaan</option>
							<option value="2">Keterangan</option>
						</select>
					</div>


					<div class="form-group" id="form_ket_hasil3" style="display:none;">
							<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
							<textarea type="text" class="form-control" id="keterangan" name="keterangan" required></textarea>
					</div>

					<div class="form-group" id="form_link3" style="display:none;">
						<label for="link" class="form-control-label">Link Bukti Pekerjaan <span class="text-danger">*</span>:</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Hasil / Bukti Pekerjaan Anda" id="link" name="link" required>
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
	/*function toggleFormStatus() {
        var status = document.getElementById("jobdesc").value;
        var formKet = document.getElementById("form_keterangan_konfirmasi");

        if (status === "0") {
            formKet.style.display = "block";
        } else {
            formKet.style.display = "none";
        }
    }*/

		

		function toggleFormHasil() {
        var statusHasil = document.getElementById("hasil").value;
        var formKetHasil = document.getElementById("form_ket_hasil");
        var formLink = document.getElementById("form_link");

        if (statusHasil === "1") {
            formLink.style.display = "block";
            formKetHasil.style.display = "none";
        } else if (statusHasil === "2") {
            formLink.style.display = "none";
            formKetHasil.style.display = "block";
        } else {
            formLink.style.display = "none";
            formKetHasil.style.display = "none";
        }
    }

		function toggleFormHasil2() {
        var statusHasil = document.getElementById("hasil2").value;
        var formKetHasil = document.getElementById("form_ket_hasil2");
        var formLink = document.getElementById("form_link2");

        if (statusHasil === "1") {
            formLink.style.display = "block";
            formKetHasil.style.display = "none";
        } else if (statusHasil === "2") {
            formLink.style.display = "none";
            formKetHasil.style.display = "block";
        } else {
            formLink.style.display = "none";
            formKetHasil.style.display = "none";
        }
    }


			function toggleFormHasil3() {
        var statusHasil = document.getElementById("hasil3").value;
        var formKetHasil = document.getElementById("form_ket_hasil3");
        var formLink = document.getElementById("form_link3");

        if (statusHasil === "1") {
            formLink.style.display = "block";
            formKetHasil.style.display = "none";
        } else if (statusHasil === "2") {
            formLink.style.display = "none";
            formKetHasil.style.display = "block";
        } else {
            formLink.style.display = "none";
            formKetHasil.style.display = "none";
        }
    }

		

	function toggleFormStatus() {
			var select = document.getElementById("jobdesc");
			var selectedValue = select.value;
			var selectedText = select.options[select.selectedIndex].text;
			var formKet = document.getElementById("form_keterangan_konfirmasi");
			var jobdescText = document.getElementById("jobdesc_text");
			
			if (selectedValue === "0") {
					formKet.style.display = "block";
					jobdescText.value = ""; // Kosongkan dulu karena akan diisi oleh user
			} else {
					formKet.style.display = "none";
					jobdescText.value = selectedText; // Simpan deskripsi dari dropdown
			}
	}

	// Tambahkan event listener untuk menangkap input manual jika "Lainnya" dipilih
	document.getElementById("keterangan_konfirmasi").addEventListener("input", function() {
			var jobdescText = document.getElementById("jobdesc_text");
			jobdescText.value = this.value; // Simpan input manual ke jobdesc_text
	});
	

	document.getElementById("pihak").addEventListener("change", function() {
				var selectedValue = this.value.trim(); 
				var keteranganGroup = document.getElementById("keterangan-group");


				if (selectedValue === "") {
						keteranganGroup.style.display = "none"; 
				} else {
						keteranganGroup.style.display = "block"; 
				}
		});


	document.getElementById("pihak2").addEventListener("change", function() {
				var selectedValue = this.value.trim(); 
				var keteranganGroup = document.getElementById("keterangan-group2");


				if (selectedValue === "") {
						keteranganGroup.style.display = "none"; 
				} else {
						keteranganGroup.style.display = "block"; 
				}
		});

	/*$('#btn-show-add-form2').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal2 #modal-form2').attr('action', 'laporan/add')
			$('#main-modal2').modal()
		})*/

	document.addEventListener('DOMContentLoaded', function() {

	

		$(document).on('click', '.btn-edit2', function() {
			$('.btn-isactive').remove()
			var object = 'laporan'
			//$('#main-modal2 #modal-form2').attr('action', 'laporan/addRev1')  Yang lama, Saat ini Rev2 menggunakan validasi agar tidak submit diluar priode
			$('#main-modal2 #modal-form2').attr('action', 'laporan/addRev2')
			$('#main-modal2').modal()

			var id2 = $(this).attr("data-id")
			fetch(object + '/edit2/' + id2)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal2 #jobdesc').val(id2)  // DATA MASIH BELUM MASUK ID NYA
				})
		})

	$(document).on('click', '.btn-save7', function(e) {
    e.preventDefault(); // Hindari submit langsung

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Pastikan data sudah benar sebelum disimpan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#modal-form2').submit(); // Ini akan masuk ke event AJAX submit
        }
    });
});

$('#modal-form2').on('submit', function(e) {
    e.preventDefault(); // Hindari reload page

    var form = $(this);
    var actionUrl = form.attr('action');

    $.ajax({
        type: 'POST',
        url: actionUrl,
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.msg
                }).then(() => {
                    location.reload();
                });
            } 
            else if (response.status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.msg
                });
            } 
            else if (response.status === 'confirm') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: response.msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan Simpan',
                    cancelButtonText: 'Tutup'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'force_save',
                            value: '1'
                        }).appendTo('#modal-form2');

                        $('#modal-form2').submit(); // kirim ulang AJAX
                    }
                });
            }
        }
    });
});


	

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal #modal-form').attr('action', 'laporan/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal #modal-form').attr('action', 'laporan/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #tanggal').val(data[0].tanggal)
					$('#main-modal #jobdesc').val(data[0].id_job)
					$('#main-modal #jenis').val(data[0].jenis)
					$('#main-modal #progress').val(data[0].progress)

					$('#main-modal #status_pekerjaan').val(data[0].status_pekerjaan)
					$('#main-modal #link').val(data[0].link)
					$('#main-modal #ket_hasil').val(data[0].ket_hasil)
					$('#main-modal #pihak').val(data[0].pihak)
					$('#main-modal #keterangan').val(data[0].keterangan)
					//$('#main-modal #pencapaian').val(data[0].pencapaian)
					$('#main-modal #id_pelanggan').val(id)
				})
		})





		//Pencapaian
		$('#btn-show-add-form-pencapaian').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal-pencapaian #modal-form-pencapaian').attr('action', 'laporan/addPencapaian')
			$('#main-modal-pencapaian').modal()
		})

		$(document).on('click', '.btn-edit-pencapaian', function() {
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal-pencapaian #modal-form-pencapaian').attr('action', 'laporan/updatePencapaian')
			$('#main-modal-pencapaian').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/editPencapaian/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal-pencapaian #tanggal').val(data[0].tanggal)
					$('#main-modal-pencapaian #detail').val(data[0].detail)
					$('#main-modal-pencapaian #link').val(data[0].link)
					$('#main-modal-pencapaian #keterangan').val(data[0].keterangan)
					$('#main-modal-pencapaian #id_pelanggan').val(id)
				})
		})


		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}

</script>

<div id="modal-cetak-bulanan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<form action="<?= base_url('laporan/print_bulanan/' . encrypt(sessPenggunaId())) ?>" method="GET" target="_blank" onsubmit="$('#modal-cetak-bulanan').modal('hide');">
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