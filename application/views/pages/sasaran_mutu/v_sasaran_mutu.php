<header class="page-header">
	<h2><i class="fas fa-bullseye text-warning"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Kepegawaian</span></li>
			<li><span>Sasaran Mutu</span></li>
			<li><span><?= $selected_year ?></span></li>
		</ol>
	</div>
</header>

<style>
	.badge-role-karyawan {
		background-color: #e0f2fe;
		color: #0369a1;
		font-size: 10px;
		font-weight: 600;
		padding: 2px 6px;
		border-radius: 4px;
		display: inline-block;
		margin-bottom: 4px;
	}
	.badge-role-head {
		background-color: #fef3c7;
		color: #b45309;
		font-size: 10px;
		font-weight: 600;
		padding: 2px 6px;
		border-radius: 4px;
		display: inline-block;
		margin-bottom: 4px;
	}
	.table-sasaran-mutu th {
		background-color: #1e293b;
		color: #ffffff;
		font-size: 11px;
		font-weight: 600;
		vertical-align: middle !important;
		text-align: center;
		border: 1px solid #334155 !important;
		padding: 10px 8px;
	}
	.table-sasaran-mutu td {
		font-size: 12px;
		vertical-align: top !important;
		border: 1px solid #e2e8f0 !important;
		line-height: 1.5;
		padding: 8px 10px;
	}
	.table-sasaran-mutu tr:hover {
		background-color: #f8fafc;
	}
	.multiline-text {
		white-space: pre-line;
	}
	.stat-card-sm {
		border-radius: 10px;
		padding: 16px;
		background: #ffffff;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0,0,0,0.05);
		transition: all 0.2s ease;
	}
	.stat-card-sm:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(0,0,0,0.08);
	}
	.filter-card {
		background: #ffffff;
		border-radius: 10px;
		border: 1px solid #e2e8f0;
		padding: 18px;
		margin-bottom: 20px;
	}
	.btn-sheet-link {
		background: linear-gradient(135deg, #10b981 0%, #059669 100%);
		color: #ffffff !important;
		font-weight: 600;
		border: none;
		box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
	}
	.btn-sheet-link:hover {
		background: linear-gradient(135deg, #059669 0%, #047857 100%);
		color: #ffffff !important;
	}
	.div-progress-item {
		border-radius: 8px;
		background: #ffffff;
		border: 1px solid #e2e8f0;
		padding: 10px 14px;
		cursor: pointer;
		transition: all 0.2s ease;
	}
	.div-progress-item:hover {
		border-color: #3b82f6;
		background: #f8fafc;
		transform: translateY(-1px);
	}
	.div-progress-item.active {
		border-color: #2563eb;
		background: #eff6ff;
	}
	.doc-link-pill {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 2px 8px;
		border-radius: 4px;
		font-size: 11px;
		background-color: #ecfdf5;
		color: #047857;
		border: 1px solid #a7f3d0;
		text-decoration: none;
	}
	.doc-link-pill:hover {
		background-color: #d1fae5;
		color: #065f46;
		text-decoration: none;
	}
</style>

<div class="row">
	<div class="col-12">
		<!-- Summary Cards -->
		<div class="row mb-3">
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-sm" style="border-left: 4px solid #3b82f6;">
					<div class="text-muted small font-weight-bold text-uppercase">Total Item Jobdesk</div>
					<div class="h3 font-weight-bold text-primary mb-0 mt-1"><?= count($items) ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-tasks"></i> 8 Divisi Perusahaan</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-sm" style="border-left: 4px solid #10b981;">
					<div class="text-muted small font-weight-bold text-uppercase">Terealisasi / Terisi</div>
					<div class="h3 font-weight-bold text-success mb-0 mt-1"><?= $sm_summary['filled_count'] ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-check-circle text-success"></i> Realisasi Terdata</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-sm" style="border-left: 4px solid #f59e0b;">
					<div class="text-muted small font-weight-bold text-uppercase">Persentase Realisasi</div>
					<div class="h3 font-weight-bold text-warning mb-0 mt-1"><?= $sm_summary['persentase'] ?>%</div>
					<div class="small text-muted mt-1">
						<div class="progress mt-1" style="height: 6px;">
							<div class="progress-bar bg-warning" role="progressbar" style="width: <?= $sm_summary['persentase'] ?>%"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-sm" style="border-left: 4px solid #8b5cf6;">
					<div class="text-muted small font-weight-bold text-uppercase">Tahun Periode</div>
					<div class="h3 font-weight-bold text-purple mb-0 mt-1" style="color: #8b5cf6;"><?= $selected_year ?></div>
					<div class="small text-muted mt-1">
						<?php if ($selected_year == 2026): ?>
							<span class="badge badge-success"><i class="fas fa-file-excel"></i> Terformat dari Excel Master</span>
						<?php else: ?>
							<span class="badge badge-primary"><i class="fas fa-sync-alt"></i> Tersinkron Langsung di Office</span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Notification Banner Tahun & Petunjuk Sinkronisasi -->
		<?php if ($selected_year == 2026): ?>
			<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #059669; background-color: #ecfdf5; color: #065f46;">
				<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
					<div>
						<i class="fas fa-check-circle mr-1 text-success fa-lg"></i>
						<strong>Arsip Periode 2026:</strong> Data telah <strong>otomatis terformat di sistem Office</strong> sesuai yang diisi manual oleh karyawan pada Google Spreadsheet untuk audit HR. Anda tetap dapat melakukan penyesuaian jika ada revisi terbaru.
					</div>
					<div>
						<a href="https://docs.google.com/spreadsheets/d/116hr1DHsrlX_spxVRvE8B7jrYmgsHTvQpED-xuW00ls/edit?gid=2070287268#gid=2070287268" target="_blank" class="btn btn-sm btn-outline-success font-weight-bold">
							<i class="fas fa-external-link-alt mr-1"></i> Buka Spreadsheet Excel Asli 2026
						</a>
					</div>
				</div>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php else: ?>
			<div class="alert alert-primary alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #2563eb; background-color: #eff6ff; color: #1e3a8a;">
				<div class="d-flex align-items-start justify-content-between flex-wrap" style="gap: 12px;">
					<div style="flex: 1; min-width: 280px;">
						<div class="d-flex align-items-center mb-1">
							<span class="badge badge-primary px-2 py-1 mr-2 font-weight-bold" style="background-color: #2563eb;">
								<i class="fas fa-sync-alt fa-spin mr-1" style="animation-duration: 4s;"></i> Single Source of Truth
							</span>
							<strong class="h6 mb-0 font-weight-bold" style="color: #1e40af;">Periode <?= $selected_year ?>: Pengisian Tersinkronisasi Penuh di Sistem Office</strong>
						</div>
						<p class="mb-1 small" style="line-height: 1.5; color: #1e3a8a;">
							Mulai tahun 2027 dan seterusnya, karyawan dan Head <strong>TIDAK LAGI mengisi manual di Excel</strong>. Seluruh proses input dan pembaruan sasaran mutu dilakukan secara langsung di sistem Office ini dan otomatis tersimpan untuk audit HR secara real-time.
						</p>
						<div class="d-flex flex-wrap mt-2" style="gap: 15px;">
							<div class="small">
								<span class="badge-role-karyawan"><i class="fas fa-user-edit"></i> Karyawan</span>:
								Mengisi <em>Detail Proyek</em>, <em>Implementasi Kegiatan</em> & <em>Link Dokumen Bukti</em>.
							</div>
							<div class="small">
								<span class="badge-role-head"><i class="fas fa-user-tie"></i> Head / Karyawan</span>:
								Mengisi <em>Sasaran Mutu</em>, <em>Metode Pengukuran</em>, <em>Indikator Keberhasilan</em> & <em>Keterangan Nilai</em>.
							</div>
						</div>
					</div>
					<div>
						<button type="button" class="btn btn-sm btn-success font-weight-bold px-3 py-2 shadow-sm" data-toggle="modal" data-target="#modalAddJobdesk">
							<i class="fas fa-plus-circle mr-1"></i> Tambah Jobdesk / Proyek Baru
						</button>
					</div>
				</div>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #1e3a8a;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php endif; ?>

		<!-- Widget Rekap Progres Pengisian per Divisi (Monitoring Audit HR) -->
		<div class="card border-0 shadow-sm mb-3" style="border-radius: 10px;">
			<div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center" style="cursor: pointer;" data-toggle="collapse" data-target="#collapseDivProgress" aria-expanded="true">
				<span class="font-weight-bold text-dark small">
					<i class="fas fa-chart-pie text-primary mr-1"></i> Rekap Progres Pengisian per Divisi (Monitoring Audit HR Periode <?= $selected_year ?>)
				</span>
				<div class="small text-muted">
					<span class="mr-2">Klik untuk memfilter per divisi</span>
					<i class="fas fa-chevron-down"></i>
				</div>
			</div>
			<div class="collapse show" id="collapseDivProgress">
				<div class="card-body p-3 bg-light">
					<div class="row">
						<?php if (!empty($div_progress)): ?>
							<?php foreach ($div_progress as $dName => $dp): ?>
								<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
									<div class="div-progress-item <?= $selected_div === $dName ? 'active' : '' ?>" onclick="filterByDivisi('<?= htmlspecialchars($dName) ?>');">
										<div class="d-flex justify-content-between align-items-center mb-1">
											<span class="font-weight-bold text-dark small text-truncate" title="<?= htmlspecialchars($dName) ?>">
												<?= htmlspecialchars($dName) ?>
											</span>
											<span class="badge <?= $dp['pct'] >= 100 ? 'badge-success' : ($dp['pct'] > 0 ? 'badge-warning' : 'badge-secondary') ?> font-weight-bold" style="font-size: 10px;">
												<?= $dp['pct'] ?>%
											</span>
										</div>
										<div class="progress" style="height: 5px;">
											<div class="progress-bar <?= $dp['pct'] >= 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= $dp['pct'] ?>%;"></div>
										</div>
										<div class="d-flex justify-content-between align-items-center mt-1">
											<span class="text-muted" style="font-size: 10px;"><?= $dp['filled'] ?> dari <?= $dp['total'] ?> terisi</span>
											<span class="text-primary font-weight-bold" style="font-size: 10px;">Lihat <i class="fas fa-arrow-right"></i></span>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Filter Bar -->
		<div class="filter-card shadow-sm">
			<form method="GET" action="<?= base_url('sasaran_mutu') ?>" id="formFilterSM">
				<div class="row align-items-end">
					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="far fa-calendar-alt"></i> Tahun Periode</label>
						<select name="tahun" id="filterTahun" class="form-control form-control-sm font-weight-bold" onchange="document.getElementById('formFilterSM').submit();">
							<?php for ($y = 2026; $y <= 2030; $y++): ?>
								<option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>>
									<?= $y ?><?= $y == 2026 ? ' (Excel Master)' : ' (Sync Online)' ?>
								</option>
							<?php endfor; ?>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="fas fa-sitemap"></i> Divisi</label>
						<select name="divisi" id="filterDivisi" class="form-control form-control-sm" onchange="updatePosisiDropdown(); document.getElementById('formFilterSM').submit();">
							<option value="all">-- Semua Divisi --</option>
							<?php foreach ($div_tree as $divName => $posList): ?>
								<option value="<?= $divName ?>" <?= $selected_div == $divName ? 'selected' : '' ?>><?= $divName ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="fas fa-user-tag"></i> Posisi / Karyawan</label>
						<select name="posisi" id="filterPosisi" class="form-control form-control-sm" onchange="document.getElementById('formFilterSM').submit();">
							<option value="all">-- Semua Posisi --</option>
							<?php 
							foreach ($div_tree as $divName => $posList):
								if ($selected_div !== 'all' && $selected_div !== $divName) continue;
								foreach ($posList as $p):
							?>
								<option value="<?= htmlspecialchars($p) ?>" <?= $selected_pos == $p ? 'selected' : '' ?>><?= htmlspecialchars($p) ?><?= $selected_div === 'all' ? ' (' . $divName . ')' : '' ?></option>
							<?php 
								endforeach;
							endforeach; 
							?>
						</select>
					</div>

					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="fas fa-search"></i> Cari Kata Kunci</label>
						<input type="text" name="q" id="filterQuery" value="<?= htmlspecialchars($search_query) ?>" class="form-control form-control-sm" placeholder="Cari jobdesk / kata...">
					</div>

					<div class="col-md-2 col-sm-6 mb-2 text-right">
						<button type="submit" class="btn btn-sm btn-primary btn-block mb-1">
							<i class="fas fa-filter"></i> Filter
						</button>
						<a href="<?= base_url('sasaran_mutu?tahun=' . $selected_year) ?>" class="btn btn-sm btn-light border btn-block">
							<i class="fas fa-undo"></i> Reset Filter
						</a>
					</div>
				</div>

				<div class="row mt-2 pt-2 border-top">
					<div class="col-md-8 d-flex align-items-center flex-wrap" style="gap: 8px;">
						<!-- Tombol Pekerjaan Saya -->
						<?php if (!empty($user_jabatan)): ?>
							<button type="button" class="btn btn-sm btn-info font-weight-bold" onclick="filterMyWork('<?= htmlspecialchars($user_jabatan) ?>');" title="Filter langsung ke jabatan Anda: <?= htmlspecialchars($user_jabatan) ?>">
								<i class="fas fa-user-check mr-1"></i> Pekerjaan Saya (<?= htmlspecialchars($user_jabatan) ?>)
							</button>
						<?php endif; ?>

						<!-- Tombol Tambah Jobdesk Baru -->
						<button type="button" class="btn btn-sm btn-success font-weight-bold" data-toggle="modal" data-target="#modalAddJobdesk">
							<i class="fas fa-plus mr-1"></i> Tambah Jobdesk Baru
						</button>

						<a href="<?= base_url('sasaran_mutu/export_csv?tahun=' . $selected_year . '&divisi=' . urlencode($selected_div) . '&posisi=' . urlencode($selected_pos)) ?>" class="btn btn-sm btn-outline-success">
							<i class="fas fa-file-excel mr-1"></i> Export CSV
						</a>

						<a href="<?= base_url('audit/kinerja?tahun=' . $selected_year) ?>" class="btn btn-sm btn-outline-primary">
							<i class="fas fa-clipboard-check mr-1"></i> Ke Audit Kinerja
						</a>

						<button type="button" class="btn btn-sm btn-secondary" onclick="window.print();">
							<i class="fas fa-print mr-1"></i> Cetak
						</button>
					</div>
					<div class="col-md-4 text-right">
						<span class="small text-muted font-italic">
							User: <strong><?= htmlspecialchars($current_user_name) ?></strong> (ID: <?= $current_user_id ?>)
						</span>
					</div>
				</div>
			</form>
		</div>

		<!-- Table Card -->
		<div class="card border-0 shadow-sm" style="border-radius: 10px;">
			<div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
				<div>
					<h5 class="mb-0 font-weight-bold text-dark">
						<i class="fas fa-list-alt text-primary mr-1"></i> Rincian Sasaran & Manajemen Mutu <?= $selected_year ?>
						<?php if ($selected_div !== 'all'): ?>
							— <span class="badge badge-info"><?= htmlspecialchars($selected_div) ?></span>
						<?php endif; ?>
						<?php if ($selected_pos !== 'all'): ?>
							<span class="badge badge-secondary"><?= htmlspecialchars($selected_pos) ?></span>
						<?php endif; ?>
					</h5>
					<small class="text-muted">
						<?= $selected_year == 2026 ? 'Terformat dari Excel master' : 'Tersinkronisasi langsung di sistem Office (tanpa isi Excel lagi)' ?>
					</small>
				</div>
				<div class="d-flex align-items-center" style="gap: 8px;">
					<span class="badge badge-light border text-muted px-2 py-1 font-weight-normal">
						Total: <strong><?= count($items) ?></strong> baris
					</span>
				</div>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
					<table class="table table-bordered table-sasaran-mutu mb-0" id="tableSasaranMutu">
						<thead>
							<tr>
								<th style="width: 35px;">No</th>
								<th style="width: 140px;">Divisi & Posisi</th>
								<th style="width: 200px;">Jobdesk</th>
								<th style="width: 220px;">
									<div>List Detail Proyek</div>
									<span class="badge-role-karyawan"><i class="fas fa-user-edit"></i> Karyawan</span>
								</th>
								<th style="width: 230px;">
									<div>Implementasi Nyata Kegiatan</div>
									<span class="badge-role-karyawan"><i class="fas fa-user-edit"></i> Karyawan</span>
								</th>
								<th style="width: 200px;">
									<div>Sasaran Mutu</div>
									<span class="badge-role-head"><i class="fas fa-user-tie"></i> Head / Karyawan</span>
								</th>
								<th style="width: 180px;">
									<div>Metode Pengukuran</div>
									<span class="badge-role-head"><i class="fas fa-calculator"></i> Angka & Rumus</span>
								</th>
								<th style="width: 180px;">
									<div>Indikator Keberhasilan</div>
									<span class="badge-role-head"><i class="fas fa-flag-checkered"></i> Kriteria / Target</span>
								</th>
								<th style="width: 110px;">
									<div>Keterangan</div>
									<span class="badge badge-light border" style="font-size: 9px;">Nilai / Realisasi</span>
								</th>
								<th style="width: 110px;">
									<div>Bukti Dokumen</div>
									<span class="badge badge-light border" style="font-size: 9px;">Link Kerja</span>
								</th>
								<th style="width: 70px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($items)): ?>
								<tr>
									<td colspan="11" class="text-center py-5 text-muted">
										<i class="fas fa-folder-open fa-3x mb-3 text-secondary d-block"></i>
										Tidak ada data sasaran mutu yang sesuai filter.
									</td>
								</tr>
							<?php else: ?>
								<?php $no = 1; foreach ($items as $row): ?>
									<tr id="row-<?= htmlspecialchars($row['id']) ?>">
										<td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
										<td>
											<span class="badge badge-primary px-2 py-1 mb-1 d-inline-block"><?= htmlspecialchars($row['divisi']) ?></span>
											<div class="font-weight-bold text-dark small"><?= htmlspecialchars($row['posisi']) ?></div>
											<?php if (!empty($row['is_custom'])): ?>
												<span class="badge badge-info px-1" style="font-size: 9px;"><i class="fas fa-plus"></i> Input Baru</span>
											<?php endif; ?>
										</td>
										<td class="font-weight-600 text-dark">
											<?= htmlspecialchars($row['jobdesk']) ?>
										</td>
										<td class="multiline-text text-secondary font-italic small" id="detail_proyek-<?= htmlspecialchars($row['id']) ?>">
											<?= !empty($row['detail_proyek']) ? htmlspecialchars($row['detail_proyek']) : '<span class="text-muted small">Belum ada detail</span>' ?>
										</td>
										<td class="multiline-text" id="implementasi-<?= htmlspecialchars($row['id']) ?>">
											<?php if (!empty($row['implementasi'])): ?>
												<span class="text-dark small"><?= htmlspecialchars($row['implementasi']) ?></span>
											<?php else: ?>
												<span class="badge badge-light text-muted border font-weight-normal"><i class="fas fa-pen"></i> Belum diisi</span>
											<?php endif; ?>
										</td>
										<td class="multiline-text text-secondary small" id="sasaran_mutu-<?= htmlspecialchars($row['id']) ?>">
											<?= !empty($row['sasaran_mutu']) ? htmlspecialchars($row['sasaran_mutu']) : '<span class="text-muted small">-</span>' ?>
										</td>
										<td class="multiline-text text-secondary small" id="metode_pengukuran-<?= htmlspecialchars($row['id']) ?>">
											<?= !empty($row['metode_pengukuran']) ? htmlspecialchars($row['metode_pengukuran']) : '<span class="text-muted small">-</span>' ?>
										</td>
										<td class="multiline-text text-secondary small" id="indikator_keberhasilan-<?= htmlspecialchars($row['id']) ?>">
											<?= !empty($row['indikator_keberhasilan']) ? htmlspecialchars($row['indikator_keberhasilan']) : '<span class="text-muted small">-</span>' ?>
										</td>
										<td class="text-center" id="keterangan-<?= htmlspecialchars($row['id']) ?>">
											<?php if (!empty($row['keterangan'])): ?>
												<span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 11px;">
													<?= htmlspecialchars($row['keterangan']) ?>
												</span>
											<?php else: ?>
												<span class="badge badge-light text-muted border font-weight-normal">-</span>
											<?php endif; ?>
										</td>
										<td class="text-center" id="link_dokumen-<?= htmlspecialchars($row['id']) ?>">
											<?php if (!empty($row['link_dokumen'])): ?>
												<a href="<?= htmlspecialchars($row['link_dokumen']) ?>" target="_blank" class="doc-link-pill" title="<?= htmlspecialchars($row['link_dokumen']) ?>">
													<i class="fas fa-external-link-alt"></i> Buka Bukti
												</a>
											<?php else: ?>
												<span class="badge badge-light text-muted border font-weight-normal" style="font-size: 10px;">Belum ada link</span>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<button type="button" class="btn btn-xs btn-outline-primary" title="Edit / Input Realisasi & Sasaran" onclick='openEditModal(<?= json_encode($row) ?>)'>
												<i class="fas fa-edit"></i> Edit
											</button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Edit / Input Sasaran Mutu -->
<div class="modal fade" id="modalEditSM" tabindex="-1" role="dialog" aria-labelledby="modalEditSMLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title font-weight-bold" id="modalEditSMLabel">
					<i class="fas fa-edit text-warning mr-1"></i> Edit Realisasi & Sasaran Mutu (Tahun <span id="modalYearTitle"><?= $selected_year ?></span>)
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formSaveSM" onsubmit="saveSMData(event);">
				<input type="hidden" name="item_id" id="modalItemId">
				<input type="hidden" name="year" id="modalYear" value="<?= $selected_year ?>">
				<input type="hidden" name="divisi" id="modalDivisi">
				<input type="hidden" name="posisi" id="modalPosisi">
				<input type="hidden" name="jobdesk" id="modalJobdesk">

				<div class="modal-body p-4" style="background-color: #f8fafc; max-height: 75vh; overflow-y: auto;">
					<!-- Info Divisi & Jobdesk -->
					<div class="card p-3 mb-3 border bg-white shadow-sm">
						<div class="d-flex justify-content-between align-items-center mb-1">
							<span class="badge badge-primary font-weight-bold px-2 py-1" id="badgeModalDivisi">HRGA</span>
							<span class="text-muted small">Posisi: <strong id="textModalPosisi">HR & Legal</strong></span>
						</div>
						<div class="font-weight-bold text-dark mt-1" id="textModalJobdesk">
							Jobdesk text goes here...
						</div>
					</div>

					<div class="row">
						<!-- Kolom Karyawan: List Detail Proyek, Implementasi Nyata Kegiatan & Link Dokumen -->
						<div class="col-md-6 mb-3">
							<div class="card p-3 border-0 shadow-sm h-100" style="background-color: #ffffff; border-top: 3px solid #0284c7 !important;">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<h6 class="font-weight-bold text-primary mb-0">Bagian Karyawan</h6>
									<span class="badge-role-karyawan"><i class="fas fa-user-edit"></i> Diisi Karyawan</span>
								</div>

								<div class="form-group mb-3">
									<label class="small font-weight-bold text-dark">
										List Detail Proyek
										<span class="text-muted font-weight-normal">(Pendetailan cara kerja / poin-poin dari jobdesc)</span>
									</label>
									<textarea name="detail_proyek" id="inputDetailProyek" class="form-control" rows="4" placeholder="Detail cara kerja / tahapan proyek..."></textarea>
								</div>

								<div class="form-group mb-3">
									<label class="small font-weight-bold text-dark">
										Implementasi Nyata Kegiatan
										<span class="text-muted font-weight-normal">(Realisasi pekerjaan atas jobdesc ini)</span>
									</label>
									<textarea name="implementasi" id="inputImplementasi" class="form-control" rows="4" placeholder="Tuliskan realisasi nyata pekerjaan yang telah/sedang dilaksanakan..."></textarea>
								</div>

								<div class="form-group mb-0">
									<label class="small font-weight-bold text-dark">
										<i class="fas fa-link text-primary mr-1"></i> Link Dokumen Bukti Pekerjaan
										<span class="text-muted font-weight-normal">(Google Drive / Link file bukti)</span>
									</label>
									<input type="text" name="link_dokumen" id="inputLinkDokumen" class="form-control" placeholder="https://drive.google.com/..." style="font-size: 12px;">
									<small class="text-muted">Tersinkron otomatis dengan Audit Kinerja.</small>
								</div>
							</div>
						</div>

						<!-- Kolom Head / Karyawan: Sasaran Mutu, Metode, Indikator, Keterangan -->
						<div class="col-md-6 mb-3">
							<div class="card p-3 border-0 shadow-sm h-100" style="background-color: #ffffff; border-top: 3px solid #d97706 !important;">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<h6 class="font-weight-bold text-warning mb-0" style="color: #b45309 !important;">Bagian Sasaran & Indikator Mutu</h6>
									<span class="badge-role-head"><i class="fas fa-user-tie"></i> Diisi Head / Karyawan</span>
								</div>

								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">
										Sasaran Mutu
										<span class="text-muted font-weight-normal">(Tujuan dari jobdesc ini)</span>
									</label>
									<textarea name="sasaran_mutu" id="inputSasaranMutu" class="form-control" rows="2" placeholder="Tujuan / sasaran mutu yang diharapkan..."></textarea>
								</div>

								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">
										Metode Pengukuran
										<span class="text-muted font-weight-normal">(Angka dan rumus masing-masing)</span>
									</label>
									<textarea name="metode_pengukuran" id="inputMetodePengukuran" class="form-control" rows="2" placeholder="Contoh: Jumlah kasus selesai ÷ total kasus x 100%"></textarea>
								</div>

								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">
										Indikator Keberhasilan
										<span class="text-muted font-weight-normal">(Target / standar capaian)</span>
									</label>
									<textarea name="indikator_keberhasilan" id="inputIndikatorKeberhasilan" class="form-control" rows="2" placeholder="Contoh: Minimal 90% terlaksana tepat waktu..."></textarea>
								</div>

								<div class="form-group mb-0">
									<label class="small font-weight-bold text-dark">
										Keterangan
										<span class="text-muted font-weight-normal">(Hasil nilai metode pengukuran / realisasi)</span>
									</label>
									<input type="text" name="keterangan" id="inputKeterangan" class="form-control font-weight-bold text-success" placeholder="Contoh: 100 %, 95 %, Terlaksana">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer bg-white border-top">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
						<i class="fas fa-times"></i> Batal
					</button>
					<button type="submit" id="btnSubmitSM" class="btn btn-primary btn-sm font-weight-bold px-4">
						<i class="fas fa-save mr-1"></i> Simpan Perubahan
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Tambah Jobdesk Baru (Khusus 2027+) -->
<div class="modal fade" id="modalAddJobdesk" tabindex="-1" role="dialog" aria-labelledby="modalAddJobdeskLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
			<div class="modal-header bg-success text-white">
				<h5 class="modal-title font-weight-bold" id="modalAddJobdeskLabel">
					<i class="fas fa-plus-circle mr-1"></i> Tambah Jobdesk & Sasaran Mutu Baru (Tahun <?= $selected_year ?>)
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formAddCustomSM" onsubmit="addCustomSMData(event);">
				<input type="hidden" name="year" value="<?= $selected_year ?>">

				<div class="modal-body p-4" style="background-color: #f8fafc; max-height: 75vh; overflow-y: auto;">
					<div class="alert alert-info py-2 px-3 small mb-3">
						<i class="fas fa-info-circle mr-1"></i>
						Jobdesk baru ini akan langsung tersimpan di sistem Office dan tersinkronisasi otomatis untuk seluruh tim dan audit HR.
					</div>

					<div class="row mb-3">
						<div class="col-md-6">
							<label class="small font-weight-bold text-dark">Divisi <span class="text-danger">*</span></label>
							<select name="divisi" id="addDivisiSelect" class="form-control form-control-sm" required onchange="updateAddPosisiOptions();">
								<option value="">-- Pilih Divisi --</option>
								<?php foreach ($div_tree as $divName => $posList): ?>
									<option value="<?= htmlspecialchars($divName) ?>"><?= htmlspecialchars($divName) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-6">
							<label class="small font-weight-bold text-dark">Posisi / Jabatan <span class="text-danger">*</span></label>
							<input type="text" name="posisi" id="addPosisiInput" class="form-control form-control-sm" placeholder="Contoh: Staff HR, Warehouse Admin..." required list="posisiListSuggestions">
							<datalist id="posisiListSuggestions"></datalist>
						</div>
					</div>

					<div class="form-group mb-3">
						<label class="small font-weight-bold text-dark">Rincian Jobdesk <span class="text-danger">*</span></label>
						<textarea name="jobdesk" class="form-control" rows="2" placeholder="Nama tugas atau deskripsi jobdesk..." required></textarea>
					</div>

					<div class="row">
						<div class="col-md-6 mb-3">
							<div class="card p-3 border-0 shadow-sm h-100 bg-white" style="border-top: 3px solid #0284c7 !important;">
								<h6 class="font-weight-bold text-primary mb-2">Bagian Karyawan</h6>
								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">List Detail Proyek</label>
									<textarea name="detail_proyek" class="form-control" rows="3" placeholder="Pendetailan tahapan atau cara kerja..."></textarea>
								</div>
								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">Implementasi Nyata Kegiatan</label>
									<textarea name="implementasi" class="form-control" rows="3" placeholder="Realisasi nyata pekerjaan..."></textarea>
								</div>
								<div class="form-group mb-0">
									<label class="small font-weight-bold text-dark">Link Dokumen Bukti Pekerjaan</label>
									<input type="text" name="link_dokumen" class="form-control form-control-sm" placeholder="https://drive.google.com/...">
								</div>
							</div>
						</div>

						<div class="col-md-6 mb-3">
							<div class="card p-3 border-0 shadow-sm h-100 bg-white" style="border-top: 3px solid #d97706 !important;">
								<h6 class="font-weight-bold text-warning mb-2" style="color: #b45309 !important;">Bagian Head / Sasaran Mutu</h6>
								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">Sasaran Mutu</label>
									<textarea name="sasaran_mutu" class="form-control" rows="2" placeholder="Tujuan sasaran mutu..."></textarea>
								</div>
								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">Metode Pengukuran</label>
									<textarea name="metode_pengukuran" class="form-control" rows="2" placeholder="Angka & rumus..."></textarea>
								</div>
								<div class="form-group mb-2">
									<label class="small font-weight-bold text-dark">Indikator Keberhasilan</label>
									<textarea name="indikator_keberhasilan" class="form-control" rows="2" placeholder="Target / standar capaian..."></textarea>
								</div>
								<div class="form-group mb-0">
									<label class="small font-weight-bold text-dark">Keterangan (Hasil Nilai Realisasi)</label>
									<input type="text" name="keterangan" class="form-control form-control-sm font-weight-bold text-success" placeholder="Contoh: 100%, Terlaksana">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer bg-white border-top">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
						<i class="fas fa-times"></i> Batal
					</button>
					<button type="submit" id="btnAddCustomSM" class="btn btn-success btn-sm font-weight-bold px-4">
						<i class="fas fa-plus mr-1"></i> Simpan Jobdesk Baru
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	const divTreeData = <?= json_encode($div_tree) ?>;

	function updatePosisiDropdown() {
		const divSelect = document.getElementById('filterDivisi');
		const posSelect = document.getElementById('filterPosisi');
		const chosenDiv = divSelect.value;

		posSelect.innerHTML = '<option value="all">-- Semua Posisi --</option>';

		if (chosenDiv === 'all') {
			for (const div in divTreeData) {
				divTreeData[div].forEach(pos => {
					const opt = document.createElement('option');
					opt.value = pos;
					opt.textContent = pos + ' (' + div + ')';
					posSelect.appendChild(opt);
				});
			}
		} else if (divTreeData[chosenDiv]) {
			divTreeData[chosenDiv].forEach(pos => {
				const opt = document.createElement('option');
				opt.value = pos;
				opt.textContent = pos;
				posSelect.appendChild(opt);
			});
		}
	}

	function updateAddPosisiOptions() {
		const chosenDiv = document.getElementById('addDivisiSelect').value;
		const datalist = document.getElementById('posisiListSuggestions');
		datalist.innerHTML = '';
		if (divTreeData[chosenDiv]) {
			divTreeData[chosenDiv].forEach(pos => {
				const opt = document.createElement('option');
				opt.value = pos;
				datalist.appendChild(opt);
			});
		}
	}

	function filterByDivisi(divName) {
		const divSelect = document.getElementById('filterDivisi');
		divSelect.value = divName;
		updatePosisiDropdown();
		document.getElementById('formFilterSM').submit();
	}

	function filterMyWork(jabatan) {
		if (!jabatan) return;
		const queryInput = document.getElementById('filterQuery');
		queryInput.value = jabatan;
		document.getElementById('formFilterSM').submit();
	}

	function openEditModal(row) {
		document.getElementById('modalItemId').value = row.id;
		document.getElementById('modalDivisi').value = row.divisi;
		document.getElementById('modalPosisi').value = row.posisi;
		document.getElementById('modalJobdesk').value = row.jobdesk;
		document.getElementById('modalYear').value = row.year || '<?= $selected_year ?>';
		document.getElementById('modalYearTitle').textContent = row.year || '<?= $selected_year ?>';

		document.getElementById('badgeModalDivisi').textContent = row.divisi;
		document.getElementById('textModalPosisi').textContent = row.posisi;
		document.getElementById('textModalJobdesk').textContent = row.jobdesk;

		document.getElementById('inputDetailProyek').value = row.detail_proyek || '';
		document.getElementById('inputImplementasi').value = row.implementasi || '';
		document.getElementById('inputLinkDokumen').value = row.link_dokumen || '';
		document.getElementById('inputSasaranMutu').value = row.sasaran_mutu || '';
		document.getElementById('inputMetodePengukuran').value = row.metode_pengukuran || '';
		document.getElementById('inputIndikatorKeberhasilan').value = row.indikator_keberhasilan || '';
		document.getElementById('inputKeterangan').value = row.keterangan || '';

		$('#modalEditSM').modal('show');
	}

	function saveSMData(e) {
		e.preventDefault();
		const btn = document.getElementById('btnSubmitSM');
		btn.disabled = true;
		btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

		const form = document.getElementById('formSaveSM');
		const formData = new FormData(form);

		fetch('<?= base_url('sasaran_mutu/save_item') ?>', {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			btn.disabled = false;
			btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Perubahan';

			if (data.status === 'success') {
				const itemId = formData.get('item_id');
				
				// Update tampilan DOM langsung
				const detailEl = document.getElementById('detail_proyek-' + itemId);
				if (detailEl) detailEl.textContent = formData.get('detail_proyek') || '-';

				const impEl = document.getElementById('implementasi-' + itemId);
				if (impEl) {
					const val = formData.get('implementasi');
					impEl.innerHTML = val ? '<span class="text-dark small">' + escapeHtml(val) + '</span>' : '<span class="badge badge-light text-muted border font-weight-normal"><i class="fas fa-pen"></i> Belum diisi</span>';
				}

				const smEl = document.getElementById('sasaran_mutu-' + itemId);
				if (smEl) smEl.textContent = formData.get('sasaran_mutu') || '-';

				const mpEl = document.getElementById('metode_pengukuran-' + itemId);
				if (mpEl) mpEl.textContent = formData.get('metode_pengukuran') || '-';

				const indEl = document.getElementById('indikator_keberhasilan-' + itemId);
				if (indEl) indEl.textContent = formData.get('indikator_keberhasilan') || '-';

				const ketEl = document.getElementById('keterangan-' + itemId);
				if (ketEl) {
					const val = formData.get('keterangan');
					ketEl.innerHTML = val ? '<span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 11px;">' + escapeHtml(val) + '</span>' : '<span class="badge badge-light text-muted border font-weight-normal">-</span>';
				}

				const linkEl = document.getElementById('link_dokumen-' + itemId);
				if (linkEl) {
					const val = formData.get('link_dokumen');
					linkEl.innerHTML = val ? '<a href="' + escapeHtml(val) + '" target="_blank" class="doc-link-pill"><i class="fas fa-external-link-alt"></i> Buka Bukti</a>' : '<span class="badge badge-light text-muted border font-weight-normal" style="font-size: 10px;">Belum ada link</span>';
				}

				$('#modalEditSM').modal('hide');

				if (typeof Swal !== 'undefined') {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil!',
						text: data.message,
						timer: 1800,
						showConfirmButton: false
					});
				} else {
					alert(data.message);
				}
			} else {
				alert(data.message || 'Gagal menyimpan data.');
			}
		})
		.catch(err => {
			btn.disabled = false;
			btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Perubahan';
			console.error(err);
			alert('Terjadi kesalahan koneksi.');
		});
	}

	function addCustomSMData(e) {
		e.preventDefault();
		const btn = document.getElementById('btnAddCustomSM');
		btn.disabled = true;
		btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

		const form = document.getElementById('formAddCustomSM');
		const formData = new FormData(form);

		fetch('<?= base_url('sasaran_mutu/add_custom_item') ?>', {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			btn.disabled = false;
			btn.innerHTML = '<i class="fas fa-plus mr-1"></i> Simpan Jobdesk Baru';

			if (data.status === 'success') {
				$('#modalAddJobdesk').modal('hide');
				if (typeof Swal !== 'undefined') {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil Ditambahkan!',
						text: data.message,
						timer: 2000,
						showConfirmButton: false
					}).then(() => {
						window.location.reload();
					});
				} else {
					alert(data.message);
					window.location.reload();
				}
			} else {
				alert(data.message || 'Gagal menambahkan jobdesk baru.');
			}
		})
		.catch(err => {
			btn.disabled = false;
			btn.innerHTML = '<i class="fas fa-plus mr-1"></i> Simpan Jobdesk Baru';
			console.error(err);
			alert('Terjadi kesalahan koneksi.');
		});
	}

	function escapeHtml(text) {
		if (!text) return '';
		return text
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;")
			.replace(/'/g, "&#039;");
	}
</script>