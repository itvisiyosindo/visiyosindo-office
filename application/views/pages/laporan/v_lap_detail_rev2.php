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
  .laporan-card {
    border-radius: 12px;
    border: 1px solid #eef2f6;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
  }
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
    font-family: 'Inter', sans-serif;
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
  /* Clean, Neat, Responsive Table Styling */
  .table-responsive,
  .custom-table-scroll {
      width: 100% !important;
      max-width: 100% !important;
      overflow-x: auto !important;
      overflow-y: hidden !important;
      -webkit-overflow-scrolling: touch !important;
      border-radius: 8px !important;
      border: 1px solid #e2e8f0 !important;
      background: #ffffff !important;
      margin-bottom: 1.25rem !important;
  }
  .table-responsive::-webkit-scrollbar,
  .custom-table-scroll::-webkit-scrollbar {
      height: 6px !important;
  }
  .table-responsive::-webkit-scrollbar-track,
  .custom-table-scroll::-webkit-scrollbar-track {
      background: #f1f5f9 !important;
  }
  .table-responsive::-webkit-scrollbar-thumb,
  .custom-table-scroll::-webkit-scrollbar-thumb {
      background: #cbd5e1 !important;
      border-radius: 3px !important;
  }
  .table-responsive::-webkit-scrollbar-thumb:hover,
  .custom-table-scroll::-webkit-scrollbar-thumb:hover {
      background: #94a3b8 !important;
  }
  .table-modern {
      margin-bottom: 0 !important;
      border: none !important;
      width: 100% !important;
      table-layout: auto !important;
  }
  .table-modern thead th {
      background-color: #f8fafc !important;
      color: #1e293b !important;
      font-weight: 700 !important;
      font-size: 11px !important;
      text-transform: uppercase !important;
      letter-spacing: 0.3px !important;
      padding: 10px 8px !important;
      vertical-align: middle !important;
      border-bottom: 2px solid #cbd5e1 !important;
      border-top: none !important;
      border-left: 1px solid #e2e8f0 !important;
      border-right: 1px solid #e2e8f0 !important;
      white-space: nowrap !important;
      text-align: center;
  }
  .table-modern tbody td {
      padding: 10px 12px !important;
      font-size: 12.5px !important;
      color: #334155 !important;
      vertical-align: middle !important;
      border: 1px solid #e2e8f0 !important;
      line-height: 1.5 !important;
      word-break: normal !important;
      overflow-wrap: break-word !important;
      white-space: normal;
  }
  .table-modern tbody tr:nth-of-type(even) {
      background-color: #fafbfd;
  }
  .table-modern tbody tr:hover {
      background-color: #f0f7ff !important;
  }
  .table-modern .row-summary td {
      background-color: #f8fafc !important;
      font-weight: 700 !important;
      color: #0f172a !important;
      border-top: 2px solid #cbd5e1 !important;
      padding: 11px 12px !important;
  }
</style>

<div class="row">
	<div class="col-12">
		<div class="card laporan-card mb-4">
			<div class="card-body p-3 p-md-4">
				<!-- User Info Header -->
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
						<button type="button" class="btn btn-sm btn-outline-danger shadow-sm btn-petujuk" style="border-radius: 8px; font-weight: 600; padding: 6px 12px; display: inline-flex; align-items: center; gap: 5px;">
							<i class="fas fa-info-circle"></i> Petunjuk Nilai
						</button>
					</div>
				</div>

				<!-- Toolbar & Week Selector Row -->
				<div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-3" style="border-bottom: 1px solid #f1f5f9; gap: 12px;">
					<!-- Left: Action Buttons -->
					<div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
						<a href="<?= base_url('laporan/print_mingguan/' . encrypt($data_job->pengguna_id) . '/' . $week_offset) ?>" target="_blank" class="btn btn-sm btn-success shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
							<i class="fas fa-print"></i> Cetak Laporan Mingguan
						</a>
						<button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modal-cetak-bulanan" style="border-radius: 8px; font-weight: 600; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
							<i class="fas fa-file-pdf"></i> Cetak Rangkuman 1 Bulan
						</button>
					</div>

					<!-- Right: Week Selector Custom Dropdown -->
					<div class="d-flex align-items-center" style="gap: 8px;">
						<?php 
							$curr_m_time = strtotime("monday this week {$week_offset} week");
							$curr_s_time = strtotime("sunday this week {$week_offset} week");
							$curr_start = date('d/m/Y', $curr_m_time);
							$curr_end = date('d/m/Y', $curr_s_time);
							$curr_title = ($week_offset == 0) ? 'Minggu Ini' : 'Minggu ' . abs($week_offset) . ' Lalu';
						?>
						<div class="dropdown custom-week-dropdown">
							<button class="btn btn-sm btn-white border dropdown-toggle d-flex align-items-center justify-content-between shadow-sm" type="button" id="dropdownWeekMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 290px; border-radius: 8px; font-weight: 500; font-size: 12.5px; padding: 7px 14px; background: #ffffff; color: #334155; border-color: #cbd5e1;">
								<span class="d-flex align-items-center text-truncate mr-2">
									<i class="fas fa-calendar-week text-primary mr-2" style="font-size: 13px;"></i>
									<span class="font-weight-bold text-dark mr-1"><?= $curr_title ?></span>
									<span class="text-muted" style="font-size: 11.5px;">(<?= $curr_start ?> - <?= $curr_end ?>)</span>
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
									<?php for ($i = 0; $i >= -50; $i--): ?>
										<?php 
											$m_time = strtotime("monday this week $i week");
											$s_time = strtotime("sunday this week $i week");
											$start_w = date('d/m/Y', $m_time);
											$end_w = date('d/m/Y', $s_time);
											$is_active = ($week_offset == $i);
											$title = ($i == 0) ? 'Minggu Ini' : 'Minggu ' . abs($i) . ' Lalu';
										?>
										<a class="dropdown-item custom-week-item <?= $is_active ? 'active' : '' ?>" href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/' . $i) ?>" data-text="<?= strtolower($title . ' ' . $start_w . ' ' . $end_w) ?>">
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
					</div>
				</div>

				<!-- Modern Keterangan Nilai Legend Bar -->
				<div class="p-2 p-md-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
					<div class="d-flex align-items-center mb-2" style="font-size: 11.5px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
						<i class="fas fa-award mr-2 text-primary" style="font-size: 13px;"></i> Keterangan Skala Nilai:
					</div>
					<div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
						<span class="score-badge badge-istimewa" title="Nilai 96 s/d 100: Istimewa">
							<span class="score-range">96 – 100</span>
							<span class="score-label">Istimewa</span>
						</span>
						<span class="score-badge badge-sangat-baik" title="Nilai 90 s/d 95: Sangat Baik">
							<span class="score-range">90 – 95</span>
							<span class="score-label">Sangat Baik</span>
						</span>
						<span class="score-badge badge-baik" title="Nilai 85 s/d 89: Baik">
							<span class="score-range">85 – 89</span>
							<span class="score-label">Baik</span>
						</span>
						<span class="score-badge badge-cukup-baik" title="Nilai 80 s/d 84: Cukup Baik">
							<span class="score-range">80 – 84</span>
							<span class="score-label">Cukup Baik</span>
						</span>
						<span class="score-badge badge-cukup" title="Nilai 75 s/d 79: Cukup">
							<span class="score-range">75 – 79</span>
							<span class="score-label">Cukup</span>
						</span>
						<span class="score-badge badge-kurang" title="Nilai 70 s/d 74: Kurang">
							<span class="score-range">70 – 74</span>
							<span class="score-label">Kurang</span>
						</span>
						<span class="score-badge badge-kurang-sekali" title="Nilai 60 s/d 69: Kurang Sekali">
							<span class="score-range">60 – 69</span>
							<span class="score-label">Kurang Sekali</span>
						</span>
					</div>
				</div>
			</div>
		</div>

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

		
		<div class="card-body p-3 p-md-4">
			<div class="d-flex align-items-center justify-content-between mb-3">
				<h5 class="font-weight-bold text-dark mb-0" style="font-size: 14.5px;">
					<i class="fas fa-calendar-day text-primary mr-1"></i> Laporan Mingguan dari Tanggal <span class="text-primary font-weight-bold"><?= $startDate ?></span> sampai <span class="text-primary font-weight-bold"><?= $endDate ?></span>
				</h5>
			</div>
						
			<div class="table-responsive custom-table-scroll mb-3">									
                <table class="table table-bordered table-hover table-modern mb-0" id="kt_table_1">
                        <thead>
                            <tr>
                                <th style="width: 50px; min-width: 45px;">No</th>
                                <th style="min-width: 240px; max-width: 320px; text-align: left;">Jobdesk</th>
                                <th style="width: 130px; min-width: 120px;">Jenis</th>
                                <th style="width: 140px; min-width: 130px;">Tanggal</th>
                                <th style="min-width: 200px; max-width: 300px; text-align: left;">Keterangan Proyek</th>
                                <th style="width: 130px; min-width: 120px;">Status Pekerjaan</th>
                                <th style="min-width: 180px; max-width: 260px; text-align: left;">Hasil Kerja</th>
                                <th style="width: 140px; min-width: 130px; text-align: left;">Pihak Terkait</th>
                                <th style="min-width: 180px; max-width: 260px; text-align: left;">Keterangan</th>
                                <th style="width: 90px; min-width: 85px;">Nilai A</th>
                                <th style="width: 80px; min-width: 75px;">Nilai B</th>
                                <th style="width: 95px; min-width: 90px;">Total Nilai</th>
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
                                // Sembunyikan rincian jobdesk yang tidak diisi pegawai pada minggu ini
                                if (empty($row->id_lap) && $row->nilai_isi != 1) {
                                    continue;
                                }

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
                                        $link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-primary shadow-sm" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;"><i class="fas fa-link mr-1"></i> Hasil Kerja</a>';
                                    } elseif (empty($row->link) && !empty($row->ket_hasil)) {
                                        $link_download = htmlspecialchars($row->ket_hasil, ENT_QUOTES, 'UTF-8');
                                    } else {
                                        $link_download = '<span class="text-muted small">-</span>';
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
                                            echo 'style="background-color: #f1f5f9; font-weight: 600;"';
                                        } elseif ($is_outside_range && !empty($row->id_lap)) {
                                            echo 'style="background-color: #fff1f2;"';
                                        }
                                    ?>
                                >

                                    <?php if ($firstRow): ?>
                                        <td rowspan="<?= $rowspan ?>" style="text-align:center; font-weight: 600; color: #475569;"><?= $no++ ?></td>
                                        <?php if($row->nilai_isi != 1){ ?>
                                            <td rowspan="<?= $rowspan ?>" style="min-width: 240px; max-width: 320px; font-weight: 500;"><?= $row->deskripsi ?></td>
                                        <?php }else{ ?>
                                            <td rowspan="<?= $rowspan ?>" style="min-width: 240px; max-width: 320px; font-weight: 700; color: #1e293b;"><?= $row->deskripsi ?></td>
                                        <?php } ?>
                                    <?php endif; ?>

                                    <td style="text-align:center">
                                        <?php if (!empty($row->id_lap)): ?>
                                            <span class="badge badge-light border text-dark font-weight-bold" style="font-size: 11px; padding: 4px 7px; border-radius: 6px;"><?= $row->jenis ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center; white-space: nowrap;">
                                        <?php if (!empty($row->id_lap)): ?>
                                            <div style="font-weight: 600; font-size: 12px; color: #334155;"><?= $nama_hari ?>, <?= $tgl_format ?></div>
                                            <?php if ($is_outside_range): ?>
                                                <span class="badge badge-danger mt-1" style="font-size: 10px; border-radius: 4px;" title="Diisi terlambat / di luar minggu berjalan"><i class="fas fa-exclamation-triangle mr-1"></i> Terlambat</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="min-width: 200px; max-width: 300px;">
                                        <?= !empty($row->id_lap) ? $row->progress : '' ?> 
                                        <input type="hidden" name="id_sodetail[]" value="<?= $row->id_lap ?>">
                                    </td>
                                    <?php if($row->jenis == 'TIDAK ADA PEKERJAAN DI MINGGU INI'){ ?>
                                        <td style="text-align:center">-</td>
                                        <td style="text-align:center">-</td>
                                    <?php }else{ ?>
                                        <td style="text-align:center">
                                            <?php 
                                                if (!empty($row->id_lap)) {
                                                    $st = strtoupper(trim($row->status_pekerjaan));
                                                    if ($st == 'SELESAI') {
                                                        echo '<span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 11px; border-radius: 6px;"><i class="fas fa-check-circle mr-1"></i> SELESAI</span>';
                                                    } elseif ($st == 'PROSES') {
                                                        echo '<span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size: 11px; border-radius: 6px;"><i class="fas fa-clock mr-1"></i> PROSES</span>';
                                                    } elseif (!empty($st)) {
                                                        echo '<span class="badge badge-secondary px-2 py-1 font-weight-bold" style="font-size: 11px; border-radius: 6px;">' . htmlspecialchars($row->status_pekerjaan) . '</span>';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td style="min-width: 180px; max-width: 260px;"><?= !empty($row->id_lap) ? $link_download : '' ?></td>
                                    <?php } ?>
                                    <td><?= !empty($row->id_lap) ? $row->pihak : '' ?></td>
                                    <td style="min-width: 180px; max-width: 260px;"><?= !empty($row->id_lap) ? $row->keterangan : '' ?></td>
                                    <td style="text-align:center;">
                                        <?php 
                                        if (
                                                $row->nilai_isi == 1 && 
                                                isset($point_has_lap[$row->point])
                                        ) { 
                                                $nilai_a = isset($nilai_point_map[$row->point]) ? $nilai_point_map[$row->point]->nilai_a : '';
                                        ?>
                                                <input class="form-control form-control-sm text-center font-weight-bold" type="text" name="nilaia[<?= $row->point ?>]" value="<?= htmlspecialchars($nilai_a) ?>" style="border-radius: 6px; border-color: #cbd5e1; max-width: 75px; margin: 0 auto; background-color: #ffffff;">
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
                                                <span class="font-weight-bold" style="font-size: 13px; color: #1e293b;"><?= htmlspecialchars($nilai_b) ?></span>
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

                            <tr class="row-summary">
                                <td colspan="9" style="text-align:right; font-weight: 700; color: #1e293b;">Rata-rata :</td>		
                                <td style="text-align:center; font-weight: 700; color: #2563eb; font-size: 13px;"><?= number_format($nilaiRataLapA, 2) ?></td>
                                <td style="text-align:center; font-weight: 700; color: #2563eb; font-size: 13px;"><?= number_format($nilaiRataLap, 2) ?></td>
                                <td style="text-align:center; font-weight: 700; color: #10b981; font-size: 13px;"><?= number_format($rataAll, 2) ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="mt-3 clearfix">
                    <button type="button" class="btn btn-success btn-save float-right shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 20px;"><i class="fas fa-save mr-1"></i> Simpan Penilaian</button>
                </div>
			</div>
		</div>
    <?= form_close(); ?>

		<br>

		<?= form_open('laporan/inputNilaiPencapaianAdm', array('id' => 'main-form-pencapaian', 'autocomplete' => 'off')); ?>
		<!-- PENCAPAIAN -->
		<div class="card-body p-0">
			<div class="table-responsive">
                <table class="table table-bordered table-hover table-modern mb-0" id="kt_table_pencapaian" style="min-width: 1100px;">
                    <thead>
                        <tr>
                            <th style="width: 50px; min-width: 45px;">No</th>
                            <th style="width: 140px; min-width: 130px;">Tanggal</th>
                            <th style="min-width: 250px; text-align: left;">Deskripsi</th>
                            <th style="min-width: 150px;">Hasil Kerja</th>
                            <th style="width: 90px; min-width: 85px;">Nilai A</th>
                            <th style="width: 80px; min-width: 75px;">Nilai B</th>
                            <th style="min-width: 150px;">Catatan A</th>
                            <th style="min-width: 150px;">Catatan B</th>
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
                                $link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-primary shadow-sm" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;"><i class="fas fa-link mr-1"></i> Hasil Kerja</a>';
                            } elseif (empty($row->link) && !empty($row->keterangan)) {
                                $link_download = htmlspecialchars($row->keterangan, ENT_QUOTES, 'UTF-8');
                            } else {
                                $link_download = '<span class="text-muted small">-</span>';
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
                            <tr <?php if ($is_outside_range) { echo 'style="background-color: #fff1f2;"'; } ?>>
                                <td style="text-align:center; font-weight: 600; color: #475569;"><?= $no++ ?></td>
                                <td style="text-align:center; white-space: nowrap; font-weight: 500;"><?= $nama_hari . ', ' . date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                <td style="word-break: normal; overflow-wrap: break-word;"><?= $row->detail ?></td>
                                <td style="text-align:center"><?= $link_download ?></td>
                                <td style="text-align:center;">
                                    <input class="form-control form-control-sm text-center font-weight-bold" type="text" name="nilaipa[<?= $id ?>]" value="<?= $row->nilai_a ?>" style="border-radius: 6px; border-color: #cbd5e1; max-width: 75px; margin: 0 auto; background-color: #ffffff;">
                                </td>
                                <td style="text-align:center; font-weight: 700; color: #1e293b;"><?= $row->nilai_b ?></td>
                                <td>
                                    <input class="form-control form-control-sm" type="text" name="catatanpa[<?= $id ?>]" value="<?= $row->catatan_a ?>" style="border-radius: 6px; border-color: #cbd5e1; background-color: #ffffff;">
                                </td>
                                <td><?= $row->catatan_b ?></td>
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

                        <?php if ($jumlah_data === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3"><em>Belum ada data pencapaian untuk minggu ini.</em></td>
                            </tr>
                        <?php endif; ?>

                        <tr class="row-summary">
                            <td colspan="4" style="text-align:right; font-weight: 700; color: #1e293b;">Rata-rata :</td>
                            <td style="text-align:center; font-weight: 700; color: #2563eb; font-size: 13px;"><?= number_format($nilaiRataA, 2) ?></td>
                            <td style="text-align:center; font-weight: 700; color: #2563eb; font-size: 13px;"><?= number_format($nilaiRataB, 2) ?></td>
                            <td colspan="2" style="text-align:center; font-weight: 700; color: #10b981; font-size: 13px;">Total Rata-rata: <?= number_format($nilaiRataPencapaian, 2) ?></td>
                        </tr>

                    </tbody>
                </table>
			</div>


						<br>
		                <div class="mt-3 clearfix">
                    <button type="button" class="btn btn-success btn-save float-right shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 20px;"><i class="fas fa-save mr-1"></i> Simpan Penilaian</button>
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
		<div class="card laporan-card mb-4">
			<div class="card-body p-3 p-md-4">
				<div class="d-flex align-items-center justify-content-between mb-3">
					<h5 class="font-weight-bold text-dark mb-0" style="font-size: 14.5px;">
						<i class="fas fa-star text-warning mr-1"></i> Penilaian Umum: <span class="text-primary font-weight-bold"><?= $startDate ?></span> s/d <span class="text-primary font-weight-bold"><?= $endDate ?></span>
					</h5>
				</div>

				<div class="table-responsive">
                    <table class="table table-bordered table-hover table-modern mb-0" id="kt_table_umum" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th style="width: 50px; min-width: 45px;">No</th>
                                <th style="width: 200px; min-width: 180px; text-align: left;">Indikator</th>
                                <th style="min-width: 320px; text-align: left;">Keterangan</th>
                                <th style="width: 90px; min-width: 85px;">Nilai A</th>
                                <th style="width: 80px; min-width: 75px;">Nilai B</th>
                                <th style="min-width: 160px; text-align: left;">Catatan A</th>
                                <th style="min-width: 160px; text-align: left;">Catatan B</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
								<td style="text-align:center; font-weight: 600; color: #475569;">1</td>
								<td style="font-weight: 600; color: #1e293b;">Inisiatif & Kreativitas</td>
								<td style="line-height: 1.6;">
                                    a. Mampu mengerjakan pekerjaan rutin secara efektif dan/atau secara keseluruhan meskipun tanpa arahan atasan tanpa kendala.<br>
									b. Mampu melaksanakan tindakan awal sesuai kapasitas/kewenangannya untuk mencegah/menyelesaikan permasalahan yang dihadapi pribadi/bersama.				
								</td>
								<td style="text-align:center;">
                                    <input class="form-control form-control-sm text-center font-weight-bold" type="text" name="nilaia1" value="<?= htmlspecialchars($penilaian_umum->nilaia1) ?>" style="border-radius: 6px; border-color: #cbd5e1; max-width: 75px; margin: 0 auto; background-color: #ffffff;">
                                </td>
								<td style="text-align:center; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($penilaian_umum->nilaib1) ?></td>
								<td>
                                    <input class="form-control form-control-sm" type="text" name="catatana1" value="<?= htmlspecialchars($penilaian_umum->catatana1) ?>" style="border-radius: 6px; border-color: #cbd5e1; background-color: #ffffff;">
                                </td>
								<td><?= htmlspecialchars($penilaian_umum->catatanb1) ?></td>
							</tr>
							<tr>
								<td style="text-align:center; font-weight: 600; color: #475569;">2</td>
								<td style="font-weight: 600; color: #1e293b;">Kepatuhan Peraturan</td>
								<td style="line-height: 1.6;">
                                    a. Memahami setiap SOP pekerjaaannya.<br>
									b. Melaksanakan pekerjaannya sesuai SOP yang diberikan.<br>
									c. Memahami Peraturan dan Tata Tertib Perusahaan.<br>
									d. Melaksanakan pekerjaan sesuai SOP dan Peraturan/Tata Tertib Perusahaan.<br>
									e. Kepatuhan dalam menjalankan semua Peraturan/Tata Tertib Peraturan.				
								</td>
								<td style="text-align:center;">
                                    <input class="form-control form-control-sm text-center font-weight-bold" type="text" name="nilaia2" value="<?= htmlspecialchars($penilaian_umum->nilaia2) ?>" style="border-radius: 6px; border-color: #cbd5e1; max-width: 75px; margin: 0 auto; background-color: #ffffff;">
                                </td>
								<td style="text-align:center; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($penilaian_umum->nilaib2) ?></td>
								<td>
                                    <input class="form-control form-control-sm" type="text" name="catatana2" value="<?= htmlspecialchars($penilaian_umum->catatana2) ?>" style="border-radius: 6px; border-color: #cbd5e1; background-color: #ffffff;">
                                </td>
								<td><?= htmlspecialchars($penilaian_umum->catatanb2) ?></td>
							</tr>
							<tr>
								<td style="text-align:center; font-weight: 600; color: #475569;">3</td>
								<td style="font-weight: 600; color: #1e293b;">Analisa atas Masalah</td>
								<td style="line-height: 1.6;">
                                    a. Mampu melakukan analisa atas trouble/problem yang dihadapi.<br>
									b. Mampu menganalisa risiko/hasil akhir atas trouble/problem yang dihadapi.<br>
									c. Mampu melakukan penanganan awal atas trouble/problem yang dihadapi sesuai kewenangan/kapasitasnya.				
								</td>
								<td style="text-align:center;">
                                    <input class="form-control form-control-sm text-center font-weight-bold" type="text" name="nilaia3" value="<?= htmlspecialchars($penilaian_umum->nilaia3) ?>" style="border-radius: 6px; border-color: #cbd5e1; max-width: 75px; margin: 0 auto; background-color: #ffffff;">
                                </td>
								<td style="text-align:center; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($penilaian_umum->nilaib3) ?></td>
								<td>
                                    <input class="form-control form-control-sm" type="text" name="catatana3" value="<?= htmlspecialchars($penilaian_umum->catatana3) ?>" style="border-radius: 6px; border-color: #cbd5e1; background-color: #ffffff;">
                                </td>
								<td><?= htmlspecialchars($penilaian_umum->catatanb3) ?></td>
							</tr>
							<tr>
								<td style="text-align:center; font-weight: 600; color: #475569;">4</td>
								<td style="font-weight: 600; color: #1e293b;">Komunikasi & Kerja sama tim</td>
								<td style="line-height: 1.6;">
                                    a. Mampu berkoordinasi lintas fungsi.<br>
									b. Mampu berkomunikasi dengan baik secara internal maupun eksternal divisi maupun perusahaan.<br>
									c. Mampu berkomunikasi dengan efektif.<br>
									d. Kesesuaian lokasi komunikasi (Personal/Group).<br>
									e. Kemampuan membuat pelaporan on time.				
								</td>
								<td style="text-align:center;">
                                    <input class="form-control form-control-sm text-center font-weight-bold" type="text" name="nilaia4" value="<?= htmlspecialchars($penilaian_umum->nilaia4) ?>" style="border-radius: 6px; border-color: #cbd5e1; max-width: 75px; margin: 0 auto; background-color: #ffffff;">
                                </td>
								<td style="text-align:center; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($penilaian_umum->nilaib4) ?></td>
								<td>
                                    <input class="form-control form-control-sm" type="text" name="catatana4" value="<?= htmlspecialchars($penilaian_umum->catatana4) ?>" style="border-radius: 6px; border-color: #cbd5e1; background-color: #ffffff;">
                                </td>
								<td><?= htmlspecialchars($penilaian_umum->catatanb4) ?></td>
							</tr>
							<tr>
								<td style="text-align:center; font-weight: 600; color: #475569;">5</td>
								<td style="font-weight: 600; color: #1e293b;">Ketelitian, Administrasi dan Teknologi</td>
								<td style="line-height: 1.6;">
                                    a. Kemampuan penguasaan penggunaan platform komunikasi/laporan secara maksimal dalam melaksanakan pekerjaan.<br>
									b. Kemampuan melakukan administrasi pekerjaan yang dilakukan sesuai jobdesc/kewenangannya secara lengkap.<br>
									c. Pelaksanaan pekerjaan secara efektif dan minim human error.						
								</td>
								<td style="text-align:center;">
                                    <input class="form-control form-control-sm text-center font-weight-bold" type="text" name="nilaia5" value="<?= htmlspecialchars($penilaian_umum->nilaia5) ?>" style="border-radius: 6px; border-color: #cbd5e1; max-width: 75px; margin: 0 auto; background-color: #ffffff;">
                                </td>
								<td style="text-align:center; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($penilaian_umum->nilaib5) ?></td>
								<td>
                                    <input class="form-control form-control-sm" type="text" name="catatana5" value="<?= htmlspecialchars($penilaian_umum->catatana5) ?>" style="border-radius: 6px; border-color: #cbd5e1; background-color: #ffffff;">
                                </td>
								<td><?= htmlspecialchars($penilaian_umum->catatanb5) ?></td>
							</tr>
							<?php
								$nilaiRataa = ($penilaian_umum->nilaia1 + $penilaian_umum->nilaia2 + $penilaian_umum->nilaia3 + $penilaian_umum->nilaia4 + $penilaian_umum->nilaia5)/5;
								$nilaiRatab = ($penilaian_umum->nilaib1 + $penilaian_umum->nilaib2 + $penilaian_umum->nilaib3 + $penilaian_umum->nilaib4 + $penilaian_umum->nilaib5)/5;
								$nilaiRataPumum = ($nilaiRataa + $nilaiRatab)/2;
							?>
							<tr class="row-summary">
                                <td colspan="3" style="text-align:right; font-weight: 700; color: #1e293b;">Rata-rata :</td>
								<td style="text-align:center; font-weight: 700; color: #2563eb; font-size: 13px;"><?= number_format($nilaiRataa, 2) ?></td>
								<td style="text-align:center; font-weight: 700; color: #2563eb; font-size: 13px;"><?= number_format($nilaiRatab, 2) ?></td>
								<td colspan="2" style="text-align:center; font-weight: 700; color: #10b981; font-size: 13px;">Total Rata-rata: <?= number_format($nilaiRataPumum, 2) ?></td>
							</tr>

                        </tbody>
                    </table>
                </div>

                <!-- Modern Summary Card -->
                <div class="p-3 mt-4" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <div class="d-flex align-items-center mb-3" style="font-size: 13px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-calculator text-primary mr-2"></i> Rangkuman Nilai Akhir
                    </div>
                    <div class="row" style="row-gap: 12px;">
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 bg-white rounded border shadow-sm d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small font-weight-500">Laporan Mingguan</div>
                                    <div class="font-weight-bold text-dark mt-1" style="font-size: 18px;"><?= number_format($rataAll, 2) ?></div>
                                </div>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 bg-white rounded border shadow-sm d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small font-weight-500">Penilaian Umum</div>
                                    <div class="font-weight-bold text-dark mt-1" style="font-size: 18px;"><?= number_format($nilaiRataPumum, 2) ?></div>
                                </div>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 bg-white rounded border shadow-sm d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small font-weight-500">Rata-rata Gabungan</div>
                                    <div class="font-weight-bold text-primary mt-1" style="font-size: 18px;"><?= number_format(($rataAll+$nilaiRataPumum)/2, 2) ?></div>
                                </div>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 bg-white rounded border shadow-sm d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small font-weight-500">Pencapaian</div>
                                    <div class="font-weight-bold text-success mt-1" style="font-size: 18px;"><?= number_format($nilaiRataPencapaian, 2) ?></div>
                                </div>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action & Navigation Buttons -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-2" style="gap: 10px;">
                    <div>
                        <a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset - 1)) ?>" class="btn btn-sm btn-outline-secondary shadow-sm mr-2" style="border-radius: 8px; font-weight: 600; padding: 7px 16px;">
                            <i class="fas fa-chevron-left mr-1"></i> Minggu Sebelumnya
                        </a>
                        <a href="<?= base_url('laporan/show/detail/laporan_detail_rev2/' . $id_pengaju . '/'  . ($week_offset + 1)) ?>" class="btn btn-sm btn-outline-secondary shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 16px;">
                            Minggu Berikutnya <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success btn-save shadow-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 20px;">
                            <i class="fas fa-save mr-1"></i> Simpan Penilaian Umum
                        </button>
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