<?php
if (!function_exists('lap_limit_text')) {
    function lap_limit_text($str, $n = 100)
    {
        $str = (string)$str;
        if ($str === '') return '';
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            return (mb_strlen($str) <= $n) ? $str : mb_substr($str, 0, $n) . '...';
        }
        return (strlen($str) <= $n) ? $str : substr($str, 0, $n) . '...';
    }
}
?><!-- 
	Create by KURNIAWAN  
	12-06-2025
	Updated for Modern UI & Responsive Navigation
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
      max-width: 800px;
    }
  }

  /* Score Legend Badges */
  .score-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 6px;
    font-size: 11.5px;
    overflow: hidden;
    border: 1px solid;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }
  .score-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.06);
  }
  .score-badge .score-range {
    padding: 4px 8px;
    font-weight: 700;
  }
  .score-badge .score-label {
    padding: 4px 8px;
    font-weight: 600;
  }
  .badge-istimewa { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
  .badge-istimewa .score-range { background: #10b981; color: #ffffff; }
  .badge-sangat-baik { background: #f0fdfa; border-color: #99f6e4; color: #115e59; }
  .badge-sangat-baik .score-range { background: #0d9488; color: #ffffff; }
  .badge-baik { background: #f0f9ff; border-color: #bae6fd; color: #075985; }
  .badge-baik .score-range { background: #0284c7; color: #ffffff; }
  .badge-cukup-baik { background: #eef2ff; border-color: #c7d2fe; color: #3730a3; }
  .badge-cukup-baik .score-range { background: #6366f1; color: #ffffff; }
  .badge-cukup { background: #fffbeb; border-color: #fde68a; color: #92400e; }
  .badge-cukup .score-range { background: #f59e0b; color: #ffffff; }
  .badge-kurang { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
  .badge-kurang .score-range { background: #ea580c; color: #ffffff; }
  .badge-kurang-sekali { background: #fff1f2; border-color: #fecdd3; color: #9f1239; }
  .badge-kurang-sekali .score-range { background: #e11d48; color: #ffffff; }

  /* Custom Week Dropdown Styling */
  .custom-week-dropdown .dropdown-toggle {
      background: #ffffff;
      border: 1px solid #cbd5e1;
      transition: all 0.2s ease;
  }
  .custom-week-dropdown .dropdown-toggle:hover,
  .custom-week-dropdown.show .dropdown-toggle {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
      background: #f8fafc;
  }
  .custom-week-dropdown .dropdown-toggle::after {
      display: none !important;
  }
  .custom-week-dropdown.show .dropdown-arrow {
      transform: rotate(180deg);
  }
  .dropdown-arrow {
      transition: transform 0.2s ease;
  }
  .custom-week-menu {
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.12), 0 4px 6px -2px rgba(15, 23, 42, 0.04);
      padding: 0;
      min-width: 320px;
      max-width: 360px;
      margin-top: 6px;
      animation: weekDropdownIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      transform-origin: top right;
      overflow: hidden;
  }
  @keyframes weekDropdownIn {
      from {
          opacity: 0;
          transform: scale(0.97) translateY(-6px);
      }
      to {
          opacity: 1;
          transform: scale(1) translateY(0);
      }
  }
  .custom-week-scroll {
      max-height: 290px;
      overflow-y: auto;
      padding: 6px;
  }
  .custom-week-scroll::-webkit-scrollbar {
      width: 6px;
  }
  .custom-week-scroll::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 4px;
  }
  .custom-week-scroll::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
  }
  .custom-week-scroll::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
  }
  .custom-week-item {
      padding: 8px 12px;
      border-radius: 8px;
      margin-bottom: 3px;
      transition: all 0.15s ease;
      color: #334155;
      border-left: 3px solid transparent;
      display: block;
      text-decoration: none !important;
  }
  .custom-week-item:hover {
      background: #f1f5f9;
      color: #0f172a;
      border-left-color: #3b82f6;
      transform: translateX(2px);
  }
  .custom-week-item.active {
      background: #eff6ff !important;
      color: #1d4ed8 !important;
      border-left-color: #2563eb;
  }
  .custom-week-item .week-title {
      font-size: 12.5px;
      font-weight: 700;
      line-height: 1.3;
  }
  .custom-week-item .week-dates {
      font-size: 11px;
      color: #64748b;
      margin-top: 2px;
  }
</style>

<div class="row">
	<div class="col-12">

		<!-- 1. HEADER & USER INFO CARD -->
		<div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
			<div class="card-body p-3 p-md-4">
				<!-- User Info Row -->
				<div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-3" style="border-bottom: 1px solid #f1f5f9; gap: 15px;">
					<div class="d-flex align-items-center" style="gap: 14px;">
						<div style="width: 46px; height: 46px; border-radius: 10px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
							<i class="fas fa-user-circle"></i>
						</div>
						<div>
							<h5 class="mb-0 font-weight-bold text-dark" style="font-size: 15px;"><?= $data_job->nama ?></h5>
							<div class="text-muted small" style="font-size: 12px;">
								<span class="mr-3"><i class="fas fa-id-badge text-primary mr-1"></i> NPP: <strong><?= $data_job->no_pegawai ?></strong></span>
								<span><i class="fas fa-briefcase text-info mr-1"></i> Jabatan: <strong><?= $data_job->jabatan ?></strong></span>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-center" style="gap: 8px;">
						<button type="button" class="btn btn-sm btn-outline-danger shadow-sm btn-petujuk" style="border-radius: 8px; font-weight: 600; padding: 6px 14px; display: inline-flex; align-items: center; gap: 5px;">
							<i class="fas fa-info-circle"></i> Petunjuk Nilai
						</button>
					</div>
				</div>

				<!-- Toolbar & Week Selector Row -->
				<div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-3" style="border-bottom: 1px solid #f1f5f9; gap: 12px;">
					<!-- Left: Active Periode Badge -->
					<div class="d-flex align-items-center">
						<span class="badge badge-light-primary font-weight-bold px-3 py-2" style="border-radius: 8px; font-size: 13px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
							<i class="fas fa-calendar-alt mr-1"></i> Periode: <strong><?= $startDate ?></strong> s/d <strong><?= $endDate ?></strong>
						</span>
					</div>

					<!-- Right: Week Selector & Navigation -->
					<div class="d-flex align-items-center" style="gap: 8px;">
						<?php 
							$curr_title = ($week_offset == 0) ? 'Minggu Ini' : 'Minggu ' . abs($week_offset) . ' Lalu';
						?>
						<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-sm btn-outline-secondary shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; display: inline-flex; align-items: center; gap: 5px;" title="Minggu Sebelumnya">
							<i class="fas fa-chevron-left"></i> <span class="d-none d-sm-inline">Minggu Sebelumnya</span>
						</a>
						<div class="dropdown custom-week-dropdown">
							<button class="btn btn-sm btn-white border dropdown-toggle d-flex align-items-center justify-content-between shadow-sm" type="button" id="dropdownWeekMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 280px; border-radius: 8px; font-weight: 500; font-size: 12.5px; padding: 7px 14px; background: #ffffff; color: #334155; border-color: #cbd5e1;">
								<span class="d-flex align-items-center text-truncate mr-2">
									<i class="fas fa-calendar-week text-primary mr-2" style="font-size: 13px;"></i>
									<span class="font-weight-bold text-dark mr-1"><?= $curr_title ?></span>
									<span class="text-muted" style="font-size: 11.5px;">(<?= $startDate ?> - <?= $endDate ?>)</span>
								</span>
								<i class="fas fa-chevron-down text-muted dropdown-arrow" style="font-size: 10px;"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-right shadow-lg custom-week-menu" aria-labelledby="dropdownWeekMenu">
								<div class="dropdown-header d-flex align-items-center justify-content-between py-2 px-3 bg-light border-bottom">
									<span class="font-weight-bold text-dark" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
										<i class="fas fa-history mr-1 text-primary"></i> Pilih Periode Minggu
									</span>
									<span class="badge badge-primary badge-pill" style="font-size: 10px; font-weight: 600;">Riwayat Minggu</span>
								</div>
								<div class="p-2 border-bottom bg-white">
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<span class="input-group-text bg-light border-right-0" style="border-radius: 6px 0 0 6px;"><i class="fas fa-search text-muted" style="font-size: 11px;"></i></span>
										</div>
										<input type="text" class="form-control form-control-sm border-left-0 week-search-input" placeholder="Cari minggu / tanggal..." style="border-radius: 0 6px 6px 0; font-size: 12px;" onclick="event.stopPropagation();">
									</div>
								</div>
								<div class="custom-week-scroll">
									<?php 
										$base_m_ts = strtotime("monday this week");
										$base_s_ts = strtotime("sunday this week");
										for ($i = 0; $i >= -50; $i--): 
											$m_time = $base_m_ts + ($i * 604800);
											$s_time = $base_s_ts + ($i * 604800);
											$start_w = date('d-m-Y', $m_time);
											$end_w = date('d-m-Y', $s_time);
											$is_active = ($week_offset == $i);
											$title = ($i == 0) ? 'Minggu Ini' : 'Minggu ' . abs($i) . ' Lalu';
										?>
										<a class="dropdown-item custom-week-item <?= $is_active ? 'active' : '' ?>" href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/' . $i) ?>" data-text="<?= strtolower($title . ' ' . $start_w . ' ' . $end_w) ?>">
											<div class="d-flex align-items-center justify-content-between w-100">
												<div>
													<div class="week-title"><?= $title ?></div>
													<div class="week-dates"><i class="far fa-calendar-alt mr-1"></i><?= $start_w ?> - <?= $end_w ?></div>
												</div>
												<?php if ($is_active): ?>
													<span class="badge badge-primary badge-pill px-2 py-1" style="font-size: 10px; font-weight: 600;">
														<i class="fas fa-check mr-1"></i> Aktif
													</span>
												<?php endif; ?>
											</div>
										</a>
									<?php endfor; ?>
								</div>
							</div>
						</div>
						<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-sm btn-outline-secondary shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; display: inline-flex; align-items: center; gap: 5px;" title="Minggu Berikutnya">
							<span class="d-none d-sm-inline">Minggu Berikutnya</span> <i class="fas fa-chevron-right"></i>
						</a>
					</div>
				</div>

				<!-- Keterangan Skala Nilai Badges -->
				<div class="p-2 p-md-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
					<div class="d-flex align-items-center mb-2" style="font-size: 11.5px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
						<i class="fas fa-award mr-2 text-primary" style="font-size: 13px;"></i> Keterangan Skala Nilai:
					</div>
					<div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
						<span class="score-badge badge-istimewa" title="Nilai 96 s/d 100: Istimewa"><span class="score-range">96 â€“ 100</span><span class="score-label">Istimewa</span></span>
						<span class="score-badge badge-sangat-baik" title="Nilai 90 s/d 95: Sangat Baik"><span class="score-range">90 â€“ 95</span><span class="score-label">Sangat Baik</span></span>
						<span class="score-badge badge-baik" title="Nilai 85 s/d 89: Baik"><span class="score-range">85 â€“ 89</span><span class="score-label">Baik</span></span>
						<span class="score-badge badge-cukup-baik" title="Nilai 80 s/d 84: Cukup Baik"><span class="score-range">80 â€“ 84</span><span class="score-label">Cukup Baik</span></span>
						<span class="score-badge badge-cukup" title="Nilai 75 s/d 79: Cukup"><span class="score-range">75 â€“ 79</span><span class="score-label">Cukup</span></span>
						<span class="score-badge badge-kurang" title="Nilai 70 s/d 74: Kurang"><span class="score-range">70 â€“ 74</span><span class="score-label">Kurang</span></span>
						<span class="score-badge badge-kurang-sekali" title="Nilai 60 s/d 69: Kurang Sekali"><span class="score-range">60 â€“ 69</span><span class="score-label">Kurang Sekali</span></span>
					</div>
				</div>
			</div>
		</div>

		<input type="hidden" id="id_po" value="<?= $data_job->pengguna_id ?>" />
		<?php $id = $data_job->pengguna_id; ?> 
		<input type="hidden" name="id" id="id" value="<?= $data_job->pengguna_id ?>">

		<!-- 2. BULK ACTION CARD (IF ALLOWED) -->
		<?php
		$bulk_nilai_allowed_users = array(744, 29, 759);
		$is_bulk_nilai_allowed = in_array((int) sessPenggunaId(), $bulk_nilai_allowed_users, true);
		?>

		<?php if ($is_bulk_nilai_allowed) { ?>
		<div class="card mb-4" style="border-left: 4px solid #0ea5e9; border: 1px solid #e2e8f0; border-left-width: 4px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
			<div class="card-body p-3">
				<form id="bulk-nilai-form" class="row align-items-end">
					<div class="col-md-4 col-sm-12 mb-2 mb-md-0">
						<label for="bulk-nilai-input" class="mb-1" style="font-size: 12.5px;"><strong><i class="fas fa-bolt text-info mr-1"></i> Bulk Action Nilai Penilai</strong></label>
						<input type="number" min="60" max="100" step="1" id="bulk-nilai-input" class="form-control form-control-sm" placeholder="Contoh: 95" style="border-radius: 6px;">
					</div>
					<div class="col-md-3 col-sm-12 mb-2 mb-md-0">
						<button type="submit" class="btn btn-sm btn-primary shadow-sm w-100" id="btn-bulk-nilai-submit" style="border-radius: 6px; font-weight: 600; padding: 6px 12px;">Terapkan &amp; Simpan Otomatis</button>
					</div>
					<div class="col-md-5 col-sm-12">
						<small class="text-muted">Mengisi seluruh field nilai B lalu langsung menyimpan ke database (catatan tidak diubah).</small>
					</div>
				</form>
			</div>
		</div>
		<?php } ?>

		<!-- 3. TABEL 1: LAPORAN MINGGUAN -->
		<?= form_open('laporan/inputNilaiLaporan', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
		<input type="hidden" name="pengguna_id" value="<?= $data_job->pengguna_id ?>">
		<input type="hidden" name="wednesday" value="<?= $wednesday ?>">

		<div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex align-items-center justify-content-between mb-3">
					<h5 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">
						<i class="fas fa-calendar-day text-primary mr-1"></i> Laporan Mingguan: <span class="text-primary font-weight-bold"><?= $startDate ?></span> s/d <span class="text-primary font-weight-bold"><?= $endDate ?></span>
					</h5>
				</div>

				<div class="table-responsive">
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
							</tr>
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

								if (!empty($row->id_lap) && $row->jenis !== 'TIDAK ADA PEKERJAAN DI MINGGU INI') {
									$point_has_lap[$row->point] = true;
								}
							}

							foreach ($grouped_data as $id => $rows): 
								$firstRow = true;
								$rowspan = count($rows);

								foreach ($rows as $row): 
									$hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
									$nama_hari = !empty($row->created_at) && isset($hari[date('l', strtotime($row->created_at))]) ? $hari[date('l', strtotime($row->created_at))] : '-';
									$tgl_format = !empty($row->created_at) ? date('d-m-Y', strtotime($row->created_at)) : '-';

									if (!empty($row->link) && empty($row->ket_hasil)) {
										$link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-link"></i> Hasil Kerja</a>';
									} elseif (empty($row->link) && !empty($row->ket_hasil)) {
										$link_download = htmlspecialchars($row->ket_hasil, ENT_QUOTES, 'UTF-8');
									} else {
										$link_download = 'Tidak Ada';
									}

									$created_at = !empty($row->created_at) ? date('Y-m-d', strtotime($row->created_at)) : '';
									$is_outside_range = (!empty($created_at) && !empty($monday) && !empty($Sunday) && ($created_at < $monday || $created_at > $Sunday));
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
									<?php if ($row->nilai_isi != 1) { ?>
										<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><?= $row->deskripsi ?></td>
									<?php } else { ?>
										<td rowspan="<?= $rowspan ?>" style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'><b><?= $row->deskripsi ?></b></font></td>
									<?php } ?>
								<?php endif; ?>

								<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->jenis : '' ?>&nbsp;</td>
								<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $nama_hari . ', ' . $tgl_format : '' ?>&nbsp;</td>
								<td style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->progress : '' ?> <input type="hidden" name="id_sodetail[]" value="<?= $row->id_lap ?>"> &nbsp;</td>
								<?php if ($row->jenis == 'TIDAK ADA PEKERJAAN DI MINGGU INI') { ?>
									<td style="text-align:center"><font color='#000000'>&nbsp; &nbsp;</td>
									<td style="text-align:center"><font color='#000000'>&nbsp; &nbsp;</td>
								<?php } else { ?>
									<td style="text-align:center"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->status_pekerjaan : '' ?>&nbsp;</td>
									<td style="width:20%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $link_download : '' ?>&nbsp;</td>
								<?php } ?>
								<td><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->pihak : '' ?>&nbsp;</td>
								<td style="width:15%; word-wrap: break-word; white-space: normal;"><font color='#000000'>&nbsp;<?= !empty($row->id_lap) ? $row->keterangan : '' ?>&nbsp;</td>
								<td>
									<?php 
									if ($row->nilai_isi == 1 && isset($point_has_lap[$row->point])) { 
										$nilai_b = isset($nilai_point_map[$row->point]) ? $nilai_point_map[$row->point]->nilai_b : '';
									?>
										<input class="form-control" type="text" name="nilaib[<?= $row->point ?>]" value="<?= htmlspecialchars($nilai_b) ?>" style="background-color: #ffffff;">
									<?php } ?>
								</td>
							</tr>
							<?php 
								$firstRow = false;
								endforeach; 
							endforeach; 
							?>
							<tr>
								<td colspan="9" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata : &nbsp;</strong></td>
								<td style="text-align:center;border: 1px solid black;"><font color='#000000'>&nbsp;<strong><?= number_format($nilaiRataLap, 2) ?></strong>&nbsp;</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- Single Clean Save Button for Table 1 -->
				<div class="mt-3 clearfix">
					<button type="button" class="btn btn-success btn-save float-right shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 22px;">
						<i class="fas fa-save mr-1"></i> Simpan Penilaian
					</button>
				</div>
			</div>
		</div>
		<?= form_close(); ?>

		<!-- 4. PROJECT & TASK YANG DISELESAIKAN (SINKRON TASK & PROYEK) -->
		<div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap: 10px;">
					<div>
						<h5 class="font-weight-bold text-dark mb-1" style="font-size: 15px;">
							<i class="fas fa-tasks text-primary mr-1"></i> Project &amp; Task yang Diselesaikan: <span class="text-primary font-weight-bold"><?= $startDate ?></span> s/d <span class="text-primary font-weight-bold"><?= $endDate ?></span>
						</h5>
						<div class="text-muted small">
							<i class="fas fa-sync-alt mr-1 text-success"></i> Terisi otomatis &amp; sinkron dengan menu <strong>Task &amp; Proyek</strong>
						</div>
					</div>
					<div class="d-flex align-items-center" style="gap: 8px;">
						<span class="badge badge-success font-weight-bold px-3 py-2" style="border-radius: 8px; font-size: 12px;">
							<i class="fas fa-check-circle mr-1"></i> <?= !empty($data_project_selesai) ? count($data_project_selesai) : 0 ?> Selesai
						</span>
						<a href="<?= base_url('task_proyek') ?>" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 12px; font-weight: 600; padding: 6px 12px;">
							<i class="fas fa-external-link-alt mr-1"></i> Menu Task &amp; Proyek
						</a>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover mb-0" style="width: 100%; font-size: 13px;">
						<thead>
							<tr style="background: #f1f5f9; color: #334155; font-weight: 600;">
								<th style="width: 45px; text-align: center;">NO</th>
								<th style="width: 120px; text-align: center;">KODE TASK</th>
								<th style="min-width: 200px;">JUDUL &amp; DESKRIPSI</th>
								<th style="min-width: 160px;">PROYEK / JENIS</th>
								<th style="width: 150px;">PEMBERI TUGAS</th>
								<th style="width: 120px; text-align: center;">TGL SELESAI</th>
								<th style="min-width: 140px;">HASIL / BUKTI</th>
								<th style="width: 120px; text-align: center;">RATING &amp; ULASAN</th>
								<th style="width: 90px; text-align: center;">STATUS</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if (!empty($data_project_selesai)):
								$no_task = 1;
								foreach ($data_project_selesai as $t):
									$tgl_selesai = !empty($t->tanggal_selesai) ? $t->tanggal_selesai : $t->created_at;
							?>
								<tr>
									<td style="text-align:center;"><?= $no_task++ ?></td>
									<td style="text-align:center;">
										<span class="badge badge-light-primary px-2 py-1" style="font-family: monospace; font-size: 11px;">
											<?= htmlspecialchars($t->kode_task ?: 'TSK-' . $t->id_task) ?>
										</span>
									</td>
									<td>
										<div class="font-weight-bold text-dark"><?= htmlspecialchars($t->nama_task) ?></div>
										<?php if (!empty($t->keterangan)): ?>
											<div class="text-muted small mt-1" style="line-height: 1.3;"><?= htmlspecialchars(lap_limit_text($t->keterangan, 120)) ?></div>
										<?php endif; ?>
									</td>
									<td>
										<span class="font-weight-600 text-dark small"><?= htmlspecialchars($t->nama_proyek ?: '-') ?></span>
										<?php if (!empty($t->nama_jenis_task)): ?>
											<br><span class="badge badge-secondary" style="font-size: 10px;"><?= htmlspecialchars($t->nama_jenis_task) ?></span>
										<?php endif; ?>
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
											<div class="text-muted small mt-1" style="font-style: italic; line-height: 1.3;">"<?= htmlspecialchars(lap_limit_text($t->catatan_bawahan, 80)) ?>"</div>
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
												<div class="text-muted small" style="font-style: italic; line-height: 1.3;">"<?= htmlspecialchars(lap_limit_text($t->ulasan_atasan, 60)) ?>"</div>
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
											<small class="text-muted">Task atau proyek yang diselesaikan di menu <a href="<?= base_url('task_proyek') ?>" target="_blank" class="font-weight-bold">Task &amp; Proyek</a> akan otomatis sinkron dan muncul di sini.</small>
										</div>
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- 5. ARSIP PENCAPAIAN SEBELUMNYA (IF ANY) -->
		<?php 
		$jumlah_data_pencapaian = !empty($data_detail_pencapaian) ? count($data_detail_pencapaian) : 0;
		$total_nilai = 0;
		$nilaiRataPenc = 0;

		if ($jumlah_data_pencapaian > 0): 
		?>
		<?= form_open('laporan/inputNilaiPencapaian', array('id' => 'main-form-pencapaian', 'autocomplete' => 'off')); ?>
		<div class="card mb-4 border-warning" style="background-color: #fffdfa; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
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
					<table class="table table-striped table-sm table-bordered table-hover mb-0" style="width: 100%;">
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
							$no_pa = 1;
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

								$id_pa = $row->id;
								$total_nilai += (float)$row->nilai_b;
							?>
								<tr>
									<td style="text-align:center;"><?= $no_pa++ ?></td>
									<td style="text-align:center; white-space: nowrap;"><?= $nama_hari . ', ' . date('d-m-Y', strtotime($row->tanggal)); ?></td>
									<td><?= htmlspecialchars($row->detail) ?></td>
									<td style="text-align:center"><?= $link_download ?></td>
									<td><input class="form-control form-control-sm text-center" type="text" name="nilaipb[<?= $id_pa ?>]" value="<?= $row->nilai_b ?>"></td>
									<td><input class="form-control form-control-sm" type="text" name="catatanpb[<?= $id_pa ?>]" value="<?= htmlspecialchars($row->catatan_b) ?>"></td>
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
					<button type="button" class="btn btn-success btn-save float-right shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 20px;">
						<i class="fas fa-save mr-1"></i> Simpan Penilaian Arsip
					</button>
				</div>
			</div>
		</div>
		<?= form_close(); ?>
		<?php endif; ?>

		<!-- 6. TABEL PENILAIAN UMUM -->
		<?= form_open('laporan/inputNilaiUmum', array('id' => 'main-form-umum', 'autocomplete' => 'off')); ?>
		<input type="hidden" name="id_pengg" value="<?= $data_job->pengguna_id ?>">
		<input type="hidden" name="hari_rabu" value="<?= $wednesday ?>">

		<div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex align-items-center justify-content-between mb-3">
					<h5 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">
						<i class="fas fa-star text-warning mr-1"></i> Penilaian Umum: <span class="text-primary font-weight-bold"><?= $startDate ?></span> s/d <span class="text-primary font-weight-bold"><?= $endDate ?></span>
					</h5>
				</div>

				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width: 100%; table-layout: fixed;">
						<thead>
							<tr>
								<th style="text-align:center" bgcolor="#C6DEFF" width="4%"><font color='#000000'> No </th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="22%"><font color='#000000'> Indikator </th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="44%"><font color='#000000'> Keterangan</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Nilai B</th>
								<th style="text-align:center" bgcolor="#C6DEFF" width="20%"><font color='#000000'> Catatan B</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:center">1</td>
								<td style="text-align:center">Inisiatif &amp; Kreativitas</td>
								<td style="text-align:left;">
									a. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.<br>
									b. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama.
								</td>
								<td><input class="form-control text-center" type="text" name="nilaib1" value="<?= htmlspecialchars($penilaian_umum->nilaib1) ?>" style="background-color: #ffffff;"></td>
								<td><input class="form-control" type="text" name="catatanb1" value="<?= htmlspecialchars($penilaian_umum->catatanb1) ?>" style="background-color: #ffffff;"></td>
							</tr>
							<tr>
								<td style="text-align:center">2</td>
								<td style="text-align:center">Kepatuhan Peraturan</td>
								<td style="text-align:left;">
									a. Memahami setiap SOP pekerjaannya.<br>
									b. Melaksanakan pekerjaannya sesuai SOP yang diberikan.<br>
									c. Memahami Peraturan dan Tata Tertib Perusahaan.<br>
									d. Menjalankan Peraturan dan Tata Tertib Perusahaan.
								</td>
								<td><input class="form-control text-center" type="text" name="nilaib2" value="<?= htmlspecialchars($penilaian_umum->nilaib2) ?>" style="background-color: #ffffff;"></td>
								<td><input class="form-control" type="text" name="catatanb2" value="<?= htmlspecialchars($penilaian_umum->catatanb2) ?>" style="background-color: #ffffff;"></td>
							</tr>
							<tr>
								<td style="text-align:center">3</td>
								<td style="text-align:center">Tanggung Jawab</td>
								<td style="text-align:left;">
									a. Menyelesaikan seluruh tugas tepat waktu.<br>
									b. Tidak mengabaikan pekerjaan sekecil apa pun yang diberikan.<br>
									c. Memiliki rasa memiliki (sense of belonging) terhadap pekerjaan.<br>
									d. Mengakui kesalahan dan bertanggung jawab untuk memperbaiki.
								</td>
								<td><input class="form-control text-center" type="text" name="nilaib3" value="<?= htmlspecialchars($penilaian_umum->nilaib3) ?>" style="background-color: #ffffff;"></td>
								<td><input class="form-control" type="text" name="catatanb3" value="<?= htmlspecialchars($penilaian_umum->catatanb3) ?>" style="background-color: #ffffff;"></td>
							</tr>
							<tr>
								<td style="text-align:center">4</td>
								<td style="text-align:center">Komunikasi &amp; Kerjasama</td>
								<td style="text-align:left;">
									a. Memiliki komunikasi yang baik secara internal dan eksternal divisi.<br>
									b. Mampu menyampaikan ide dan pendapat secara profesional.<br>
									c. Menunjukkan sikap saling menghormati.<br>
									d. Bekerja sama secara solid dalam tim maupun lintas divisi.
								</td>
								<td><input class="form-control text-center" type="text" name="nilaib4" value="<?= htmlspecialchars($penilaian_umum->nilaib4) ?>" style="background-color: #ffffff;"></td>
								<td><input class="form-control" type="text" name="catatanb4" value="<?= htmlspecialchars($penilaian_umum->catatanb4) ?>" style="background-color: #ffffff;"></td>
							</tr>
							<tr>
								<td style="text-align:center">5</td>
								<td style="text-align:center">Kerapian &amp; Kebersihan</td>
								<td style="text-align:left;">
									a. Menjaga kerapian penampilan diri sesuai standar.<br>
									b. Menjaga kebersihan dan kerapian area kerja.<br>
									c. Merawat dan menggunakan inventaris kantor dengan baik.<br>
									d. Menyimpan dokumen dan data secara teratur dan aman.
								</td>
								<td><input class="form-control text-center" type="text" name="nilaib5" value="<?= htmlspecialchars($penilaian_umum->nilaib5) ?>" style="background-color: #ffffff;"></td>
								<td><input class="form-control" type="text" name="catatanb5" value="<?= htmlspecialchars($penilaian_umum->catatanb5) ?>" style="background-color: #ffffff;"></td>
							</tr>
							<?php 
								$totalNilaiPumum = 0;
								$jumlahNilaiPumum = 0;
								for ($i = 1; $i <= 5; $i++) {
									$fieldB = "nilaib$i";
									if (isset($penilaian_umum->$fieldB) && is_numeric($penilaian_umum->$fieldB)) {
										$totalNilaiPumum += $penilaian_umum->$fieldB;
										$jumlahNilaiPumum++;
									}
								}
								$nilaiRataPumum = ($jumlahNilaiPumum > 0) ? ($totalNilaiPumum / $jumlahNilaiPumum) : 0;
							?>
							<tr class="fw-bold bg-light">
								<td colspan="3" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata :</strong></td>
								<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($nilaiRataPumum, 2) ?></strong></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- Bottom Action & Navigation Buttons -->
				<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-2" style="gap: 10px;">
					<div>
						<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-sm btn-outline-secondary shadow-sm mr-2" style="border-radius: 8px; font-weight: 600; padding: 7px 16px;">
							<i class="fas fa-chevron-left mr-1"></i> Minggu Sebelumnya
						</a>
						<a href="<?= base_url('laporan/show/detail/my_detail_nilai_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-sm btn-outline-secondary shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 16px;">
							Minggu Berikutnya <i class="fas fa-chevron-right ml-1"></i>
						</a>
					</div>
					<div>
						<button type="button" class="btn btn-sm btn-info btn-selesai shadow-sm mr-2" style="border-radius: 8px; font-weight: 600; padding: 7px 20px;" id-Sijk="<?=$data_job->pengguna_id?>">
							<i class="fas fa-check mr-1"></i> Selesaikan Penilaian
						</button>
						<button type="button" class="btn btn-sm btn-success btn-save shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 20px;">
							<i class="fas fa-save mr-1"></i> Simpan Penilaian Umum
						</button>
					</div>
				</div>
			</div>
		</div>
		<?= form_close(); ?>

	</div>
</div>

<!-- Modal Indikator Skala Penilaian -->
<div id="main-modal-indikatorPenilaian" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-custom" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label" class="mb-0 d-flex justify-content-between w-100 align-items-center">
					<span><i class="flaticon2-avatar icon-2x text-grey-light"></i> Indikator Skala Penilaian 60â€“100</span>
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
								<th style="width: 25%; text-align: center; background-color: #C7C6C1;">Nilai</th>
								<th style="text-align: center; background-color: #C7C6C1;">Indikator Penilaian</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align: center;">96 â€“ 100 <br><strong>Istimewa</strong> (Outstanding)</td>
								<td>Kinerja luar biasa, melebihi semua target yang ditetapkan. Sangat proaktif, menunjukkan inisiatif tinggi, kepemimpinan kuat, dan menjadi panutan bagi rekan kerja.</td>
							</tr>
							<tr>
								<td style="text-align: center;">90 â€“ 95 <br><strong>Sangat Baik</strong> (Excellent)</td>
								<td>Kinerja sangat memuaskan. Mencapai semua target dan sering kali melebihi ekspektasi. Disiplin, mandiri, dan berkinerja konsisten.</td>
							</tr>
							<tr>
								<td style="text-align: center;">85 â€“ 89 <br><strong>Baik</strong> (Very Good)</td>
								<td>Kinerja baik dan memenuhi seluruh target kerja. Terkadang menunjukkan inisiatif, bisa diandalkan dan bekerja efektif dengan pengawasan minimal.</td>
							</tr>
							<tr>
								<td style="text-align: center;">80 â€“ 84 <br><strong>Cukup Baik</strong> (Good)</td>
								<td>Umumnya memenuhi target kerja, meskipun masih ada beberapa aspek yang perlu ditingkatkan. Perlu dorongan untuk lebih proaktif dan konsisten.</td>
							</tr>
							<tr>
								<td style="text-align: center;">75 â€“ 79 <br><strong>Cukup</strong> (Fair)</td>
								<td>Kinerja memenuhi sebagian besar harapan, namun ada beberapa kekurangan yang perlu diperbaiki. Memerlukan pemantauan dan bimbingan lebih lanjut.</td>
							</tr>
							<tr>
								<td style="text-align: center;">70 â€“ 74 <br><strong>Kurang</strong> (Below Average)</td>
								<td>Beberapa target tidak tercapai. Kurang inisiatif, kurang akurat dalam bekerja, dan membutuhkan peningkatan dalam beberapa aspek penting.</td>
							</tr>
							<tr>
								<td style="text-align: center;">60 â€“ 69 <br><strong>Kurang Sekali</strong> (Poor)</td>
								<td>Kinerja jauh di bawah standar yang ditetapkan. Gagal mencapai sebagian besar target, kurang tanggung jawab, dan perlu evaluasi menyeluruh untuk perbaikan.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="modal-footer">
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

		$(document).ready(function () {
			$('.btn-petujuk').click(function () {
				$('#main-modal-indikatorPenilaian').modal('show');
			});
		});

		// Search filter for custom week dropdown
		$(document).on('keyup', '.week-search-input', function(e) {
			e.stopPropagation();
			var val = $(this).val().toLowerCase().trim();
			var $menu = $(this).closest('.custom-week-menu');
			$menu.find('.custom-week-item').each(function() {
				var text = $(this).attr('data-text') || $(this).text().toLowerCase();
				if (text.indexOf(val) > -1) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		});

	})

	function goBack() {
		window.history.back();
	}
</script>