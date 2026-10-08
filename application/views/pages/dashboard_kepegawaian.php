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
    /* Kepegawaian Executive Dashboard Custom Styles */
    .kpi-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px 0 rgba(0, 0, 0, 0.02);
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        border-color: #cbd5e1;
    }

    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .dashboard-panel-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .dashboard-panel-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .dashboard-panel-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Modern Clean Table Styling */
    .table-rekap-modern {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        margin-bottom: 0 !important;
    }

    .table-rekap-modern thead th {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.3px !important;
        padding: 10px 8px !important;
        border: 1px solid #e2e8f0 !important;
        text-align: center !important;
        vertical-align: middle !important;
    }

    .table-rekap-modern tbody td {
        font-size: 12.5px !important;
        color: #1e293b !important;
        padding: 8px 10px !important;
        border: 1px solid #e2e8f0 !important;
        vertical-align: middle !important;
        transition: background-color 0.15s ease;
    }

    .table-rekap-modern tbody tr:nth-child(even) td {
        background-color: #f8fafc !important;
    }

    .table-rekap-modern tbody tr:hover td {
        background-color: #eff6ff !important;
    }

    .score-badge {
        font-weight: 600;
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
    }
</style>

<div class="row">
<?php
// Hitung data agregat untuk chart analitik kepegawaian
$chart_labels = [];
$chart_scores_lap = [];
$chart_scores_penc = [];

if (!empty($data_mingguan)) {
    $w_num = 1;
    foreach ($data_mingguan as $minggu) {
        $p_parts = explode(' s/d ', $minggu['periode']);
        $short_p = isset($p_parts[0]) ? date('d/m', strtotime($p_parts[0])) : 'M' . $w_num;
        $chart_labels[] = 'Minggu ' . $w_num . ' (' . $short_p . ')';

        $total_lap = 0;
        $total_pen = 0;
        $total_penc = 0;
        $cnt_emp = 0;

        if (!empty($list_pengguna)) {
            foreach ($list_pengguna as $p) {
                $pid = $p->pengguna_id;
                if (isset($minggu['nilai_laporan_adm'][$pid])) {
                    $total_lap += (float)$minggu['nilai_laporan_adm'][$pid];
                    $total_pen += (float)$minggu['nilai_penilaianumum_adm'][$pid];
                    $total_penc += (float)$minggu['nilai_pencapaian_adm'][$pid];
                    $cnt_emp++;
                }
            }
        }
        $avg_lap = $cnt_emp > 0 ? round($total_lap / $cnt_emp, 2) : 0;
        $avg_pen = $cnt_emp > 0 ? round($total_pen / $cnt_emp, 2) : 0;
        $avg_penc = $cnt_emp > 0 ? round($total_penc / $cnt_emp, 2) : 0;
        $avg_combined = round(($avg_lap + $avg_pen) / 2, 2);

        $chart_scores_lap[] = $avg_combined;
        $chart_scores_penc[] = $avg_penc;
        $w_num++;
    }
}
$total_karyawan_aktif = !empty($list_pengguna) ? count($list_pengguna) : 0;
$total_hadir = isset($present[0]->total) ? (int)$present[0]->total : 0;
$total_izin = isset($izin[0]->total) ? (int)$izin[0]->total : 0;
$total_sakit = isset($sakit[0]->total) ? (int)$sakit[0]->total : 0;
$total_cuti = isset($cuti[0]->total) ? (int)$cuti[0]->total : 0;
$total_absen_lainnya = max(0, $total_karyawan_aktif - ($total_hadir + $total_izin + $total_sakit + $total_cuti));
$rate_kehadiran = $total_karyawan_aktif > 0 ? round(($total_hadir / $total_karyawan_aktif) * 100, 1) : 0;
?>

	<!-- 1. Executive Top Banner Overview -->
	<div class="col-12 mb-3">
		<div class="card p-3 p-md-4 border-0" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 12px; color: #ffffff; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.12);">
			<div class="d-flex flex-wrap justify-content-between align-items-center">
				<div class="d-flex align-items-center">
					<div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.35); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #60a5fa; margin-right: 14px; flex-shrink: 0;">
						<i class="fas fa-users-cog"></i>
					</div>
					<div>
						<h3 style="font-size: 18px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">Executive Overview & Analytics Kepegawaian</h3>
						<p style="font-size: 12.5px; color: #94a3b8; margin: 0;">Monitoring kehadiran harian real-time, performa SDM, dan rekapitulasi nilai kerja PT Visi Yosindo Medikal.</p>
					</div>
				</div>
				<div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
					<span class="badge" style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; font-weight: 600; padding: 6px 12px; border-radius: 9999px; font-size: 12px;">
						<i class="fas fa-calendar-day mr-1"></i> <?= date('d M Y') ?>
					</span>
					<?php if (isAdmin() || isHrd() || isGa()) { ?>
						<a href="<?= base_url('dashboard_kepegawaian/sisa_cuti_karyawan') ?>" class="btn btn-sm btn-info" style="font-size: 12px; margin-left: 8px;">
							<i class="fas fa-calendar-check mr-1"></i> Sisa Cuti
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Four Key Performance Indicator (KPI) Cards -->
	<div class="col-xl-3 col-sm-6 mb-3">
		<div class="kpi-card h-100">
			<div class="card-body p-3">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Karyawan Aktif</span>
						<h3 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 4px 0 2px 0;"><?= $total_karyawan_aktif ?></h3>
						<span style="font-size: 11.5px; color: #10b981; font-weight: 600;"><i class="fas fa-check-circle mr-1"></i> Terdaftar Aktif</span>
					</div>
					<div class="kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
						<i class="fas fa-users"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="javascript:;" id="btn-show-hadir" style="text-decoration: none; color: inherit;">
			<div class="kpi-card h-100" style="cursor: pointer;">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Hadir Hari Ini</span>
							<h3 style="font-size: 26px; font-weight: 800; color: #047857; margin: 4px 0 2px 0;"><?= $total_hadir ?></h3>
							<span style="font-size: 11.5px; color: #2563eb; font-weight: 600;"><i class="fas fa-chart-line mr-1"></i> <?= $rate_kehadiran ?>% Kehadiran</span>
						</div>
						<div class="kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
							<i class="fas fa-user-check"></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="javascript:;" id="btn-show-izin" style="text-decoration: none; color: inherit;">
			<div class="kpi-card h-100" style="cursor: pointer;">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Izin & Sakit</span>
							<h3 style="font-size: 26px; font-weight: 800; color: #b45309; margin: 4px 0 2px 0;"><?= $total_izin + $total_sakit ?></h3>
							<span style="font-size: 11.5px; color: #64748b; font-weight: 500;">
								<span class="text-warning font-weight-bold mr-1"><?= $total_izin ?> Izin</span> &bull; 
								<span class="text-info font-weight-bold ml-1"><?= $total_sakit ?> Sakit</span>
							</span>
						</div>
						<div class="kpi-icon-box" style="background: #fffbeb; color: #f59e0b;">
							<i class="fas fa-user-clock"></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-xl-3 col-sm-6 mb-3">
		<a href="javascript:;" id="btn-show-cuti" style="text-decoration: none; color: inherit;">
			<div class="kpi-card h-100" style="cursor: pointer;">
				<div class="card-body p-3">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Sedang Cuti</span>
							<h3 style="font-size: 26px; font-weight: 800; color: #b91c1c; margin: 4px 0 2px 0;"><?= $total_cuti ?></h3>
							<span style="font-size: 11.5px; color: #ef4444; font-weight: 600;"><i class="fas fa-calendar-times mr-1"></i> Klik rincian cuti</span>
						</div>
						<div class="kpi-icon-box" style="background: #fef2f2; color: #ef4444;">
							<i class="fas fa-calendar-day"></i>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- 3. Visual Charts (Tren Kinerja Mingguan & Donut Absensi) -->
	<div class="col-lg-8 mb-4">
		<div class="dashboard-panel-card h-100">
			<div class="dashboard-panel-header">
				<h5 class="dashboard-panel-title">
					<i class="fas fa-chart-line text-primary"></i> Rata-rata Skor Kinerja SDM Mingguan (<?= $bulanIni ?>)
				</h5>
				<span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 600; padding: 4px 8px;">
					Laporan vs Pencapaian
				</span>
			</div>
			<div class="card-body p-3">
				<div style="height: 240px; position: relative;">
					<canvas id="kepegawaianTrendChart"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4 mb-4">
		<div class="dashboard-panel-card h-100">
			<div class="dashboard-panel-header">
				<h5 class="dashboard-panel-title">
					<i class="fas fa-chart-pie text-success"></i> Status Absensi Hari Ini
				</h5>
				<span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 11px; font-weight: 600; padding: 4px 8px;">
					Live Real-time
				</span>
			</div>
			<div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
				<div style="height: 175px; width: 175px; position: relative;">
					<canvas id="kepegawaianAttendanceChart"></canvas>
				</div>
				<div class="d-flex justify-content-center flex-wrap gap-2 mt-3 text-center" style="font-size: 11.5px; font-weight: 600;">
					<span class="mr-2" style="color: #047857;"><i class="fas fa-circle" style="color: #10b981;"></i> Hadir (<?= $total_hadir ?>)</span>
					<span class="mr-2" style="color: #b45309;"><i class="fas fa-circle" style="color: #f59e0b;"></i> Izin (<?= $total_izin ?>)</span>
					<span class="mr-2" style="color: #0369a1;"><i class="fas fa-circle" style="color: #0ea5e9;"></i> Sakit (<?= $total_sakit ?>)</span>
					<span style="color: #b91c1c;"><i class="fas fa-circle" style="color: #ef4444;"></i> Cuti (<?= $total_cuti ?>)</span>
				</div>
			</div>
		</div>
	</div>

  <?php if (sessPenggunaId()=='1' || sessPenggunaId()=='54' || sessPenggunaId()=='69' || sessPenggunaId()=='744' || sessPenggunaId()=='58') { ?>

	<!-- 4. Section Rekap Nilai Karyawan Mingguan (Admin/HR) -->
	<div class="col-12 mb-4">
		<div class="dashboard-panel-card">
			<div class="dashboard-panel-header">
				<h5 class="dashboard-panel-title">
					<i class="fas fa-clipboard-list text-info"></i> Rekapitulasi Nilai & Evaluasi Karyawan (<?= $bulanIni ?>)
				</h5>
				<div>
					<form method="get" class="d-inline-flex align-items-center gap-2 m-0">
						<div class="input-group input-group-sm" style="width: 170px;">
							<div class="input-group-prepend">
								<span class="input-group-text bg-white" style="border-right: none;"><i class="far fa-calendar-alt text-muted"></i></span>
							</div>
							<input type="text" name="bulan"
								data-plugin-datepicker
								data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}'
								class="form-control form-control-sm"
								id="filter_bulan"
								style="border-left: none; font-weight: 600;"
								placeholder="Pilih Bulan"
								value="<?= isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m') ?>"
								required>
						</div>
						<button type="submit" class="btn btn-sm btn-primary ml-2">
							<i class="fa fa-filter mr-1"></i> Filter
						</button>
					</form>
				</div>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-rekap-modern">
						<thead>
							<tr>
								<th rowspan="3" style="width: 50px;">No</th>
								<th rowspan="3" style="min-width: 180px; text-align: left !important; padding-left: 14px !important;">Nama Karyawan</th>
								<th colspan="<?= count($data_mingguan) * 4 ?>" style="background: #e2e8f0; color: #1e293b;">Periode Penilaian Mingguan</th>
							</tr>
							<tr>
								<?php foreach ($data_mingguan as $minggu): ?>
									<th colspan="4" style="background: #f1f5f9; color: #0284c7; font-size: 11px;"><?= $minggu['periode'] ?></th>
								<?php endforeach; ?>
							</tr>
							<tr>
								<?php foreach ($data_mingguan as $minggu): ?>
									<th style="font-size: 10.5px;">Laporan</th>
									<th style="font-size: 10.5px;">Penilaian Umum</th>
									<th style="font-size: 10.5px; background: #e0f2fe; color: #0369a1;">Rata-rata</th>
									<th style="font-size: 10.5px; background: #ecfdf5; color: #047857;">Pencapaian</th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; foreach ($list_pengguna as $pengguna): ?>
								<tr>
									<td style="text-align: center; font-weight: 600; color: #64748b;"><?= $no++ ?></td>
									<td style="font-weight: 600; color: #0f172a; padding-left: 14px !important;"><?= $pengguna->nama ?></td>
									<?php foreach ($data_mingguan as $minggu): 
										$v_lap = $minggu['nilai_laporan_adm'][$pengguna->pengguna_id] ?? '-';
										$v_pen = $minggu['nilai_penilaianumum_adm'][$pengguna->pengguna_id] ?? '-';
										$v_avg = $minggu['rata_lap_adms'][$pengguna->pengguna_id] ?? '-';
										$v_penc = $minggu['nilai_pencapaian_adm'][$pengguna->pengguna_id] ?? '-';
									?>
										<td style="text-align:center;"><?= $v_lap ?></td>
										<td style="text-align:center;"><?= $v_pen ?></td>
										<td style="text-align:center; font-weight: 700; color: #0284c7; background-color: rgba(224, 242, 254, 0.4);"><?= $v_avg ?></td>
										<td style="text-align:center; font-weight: 700; color: #059669; background-color: rgba(236, 253, 245, 0.4);"><?= $v_penc ?></td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- 5. Section Log Aktivitas (Admin/HR) -->
	<div class="col-12 mb-4">
		<div class="dashboard-panel-card">
			<div class="dashboard-panel-header">
				<h5 class="dashboard-panel-title">
					<i class="fas fa-history text-secondary"></i> Log Aktivitas Pengguna & Audit Kepegawaian
				</h5>
				<div class="d-inline-flex align-items-center gap-2">
					<small class="text-muted mr-1 font-weight-bold">Filter Bulan:</small>
					<div class="input-group input-group-sm" style="width: 150px;">
						<div class="input-group-prepend">
							<span class="input-group-text bg-white" style="border-right: none;"><i class="far fa-calendar-alt text-muted"></i></span>
						</div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control form-control-sm" id="filter_month" placeholder="Pilih Bulan" style="border-left: none;" required>
					</div>
				</div>
			</div>
			<div class="card-body p-3">
				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1" style="width: 100%;">
						<thead>
							<tr>
								<th style="width: 50px;"> # </th>
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

		// 1. Chart Tren Rata-rata Skor Kinerja Mingguan
		var trendCanvas = document.getElementById('kepegawaianTrendChart');
		if (trendCanvas) {
			var ctxTrend = trendCanvas.getContext('2d');
			new Chart(ctxTrend, {
				type: 'line',
				data: {
					labels: <?= json_encode(!empty($chart_labels) ? $chart_labels : ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4']) ?>,
					datasets: [
						{
							label: 'Rata-rata Laporan + Penilaian Umum',
							data: <?= json_encode(!empty($chart_scores_lap) ? $chart_scores_lap : [0, 0, 0, 0]) ?>,
							borderColor: '#2563eb',
							backgroundColor: 'rgba(37, 99, 235, 0.08)',
							borderWidth: 2.5,
							pointRadius: 4,
							pointBackgroundColor: '#2563eb',
							fill: true,
							tension: 0.35
						},
						{
							label: 'Rata-rata Pencapaian',
							data: <?= json_encode(!empty($chart_scores_penc) ? $chart_scores_penc : [0, 0, 0, 0]) ?>,
							borderColor: '#10b981',
							backgroundColor: 'rgba(16, 185, 129, 0.05)',
							borderWidth: 2.5,
							pointRadius: 4,
							pointBackgroundColor: '#10b981',
							fill: true,
							tension: 0.35
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						y: {
							beginAtZero: true,
							max: 100,
							grid: { color: '#f1f5f9' },
							ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
						},
						x: {
							grid: { display: false },
							ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
						}
					},
					plugins: {
						legend: {
							position: 'top',
							labels: { boxWidth: 12, font: { family: 'Plus Jakarta Sans', size: 11.5, weight: '600' } }
						}
					}
				}
			});
		}

		// 2. Chart Donut Status Kehadiran Hari Ini
		var attCanvas = document.getElementById('kepegawaianAttendanceChart');
		if (attCanvas) {
			var ctxAtt = attCanvas.getContext('2d');
			new Chart(ctxAtt, {
				type: 'doughnut',
				data: {
					labels: ['Hadir', 'Izin', 'Sakit', 'Cuti', 'Belum/Lainnya'],
					datasets: [{
						data: [
							<?= $total_hadir ?>,
							<?= $total_izin ?>,
							<?= $total_sakit ?>,
							<?= $total_cuti ?>,
							<?= $total_absen_lainnya ?>
						],
						backgroundColor: ['#10b981', '#f59e0b', '#0ea5e9', '#ef4444', '#e2e8f0'],
						borderWidth: 2,
						borderColor: '#ffffff'
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					cutout: '70%',
					plugins: {
						legend: { display: false }
					}
				}
			});
		}
	})
</script>