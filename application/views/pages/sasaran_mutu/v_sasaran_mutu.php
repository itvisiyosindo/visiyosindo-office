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
		font-size: 12px;
		font-weight: 600;
		vertical-align: middle !important;
		text-align: center;
		border: 1px solid #334155 !important;
	}
	.table-sasaran-mutu td {
		font-size: 12px;
		vertical-align: top !important;
		border: 1px solid #e2e8f0 !important;
		line-height: 1.5;
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
</style>

<div class="row">
	<div class="col-12">
		<!-- Summary Cards -->
		<div class="row mb-3">
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-sm border-left-primary" style="border-left: 4px solid #3b82f6;">
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
					<div class="text-muted small font-weight-bold text-uppercase">Tahun Sasaran Mutu</div>
					<div class="h3 font-weight-bold text-purple mb-0 mt-1" style="color: #8b5cf6;"><?= $selected_year ?></div>
					<div class="small text-muted mt-1">
						<?php if ($selected_year == 2026): ?>
							<span class="badge badge-success"><i class="fas fa-check"></i> Data Terformat Excel</span>
						<?php else: ?>
							<span class="badge badge-info"><i class="fas fa-edit"></i> Siap Diinput</span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Notification Banner Tahun -->
		<?php if ($selected_year == 2026): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert" style="border-left: 4px solid #059669; background-color: #ecfdf5; color: #065f46;">
				<i class="fas fa-info-circle mr-1"></i>
				<strong>Tahun 2026:</strong> Data telah <strong>terformat langsung di sistem office</strong> sesuai yang diisi pada Google Spreadsheet master. Anda dapat melakukan penyesuaian kapan saja melalui tombol <strong>Edit</strong>.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php else: ?>
			<div class="alert alert-info alert-dismissible fade show" role="alert" style="border-left: 4px solid #0284c7; background-color: #f0f9ff; color: #075985;">
				<i class="fas fa-info-circle mr-1"></i>
				<strong>Tahun <?= $selected_year ?>:</strong> Template Jobdesk & Detail Proyek telah disiapkan secara otomatis. Bagian <strong>Implementasi Nyata Kegiatan</strong>, <strong>Sasaran Mutu</strong>, <strong>Metode Pengukuran</strong>, <strong>Indikator Keberhasilan</strong>, dan <strong>Keterangan</strong> dikosongkan untuk diisi oleh Karyawan dan Head masing-masing.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php endif; ?>

		<!-- Filter Bar -->
		<div class="filter-card">
			<form method="GET" action="<?= base_url('sasaran_mutu') ?>" id="formFilterSM">
				<div class="row align-items-end">
					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="far fa-calendar-alt"></i> Tahun</label>
						<select name="tahun" id="filterTahun" class="form-control form-control-sm font-weight-bold" onchange="document.getElementById('formFilterSM').submit();">
							<?php for ($y = 2026; $y <= 2030; $y++): ?>
								<option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?><?= $y == 2026 ? ' (Excel 2026)' : '' ?></option>
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
						<input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" class="form-control form-control-sm" placeholder="Cari jobdesk...">
					</div>

					<div class="col-md-2 col-sm-6 mb-2 text-right">
						<button type="submit" class="btn btn-sm btn-primary btn-block mb-1">
							<i class="fas fa-filter"></i> Filter
						</button>
						<a href="<?= base_url('sasaran_mutu') ?>" class="btn btn-sm btn-light border btn-block">
							<i class="fas fa-undo"></i> Reset
						</a>
					</div>
				</div>

				<div class="row mt-2 pt-2 border-top">
					<div class="col-md-8 d-flex align-items-center flex-wrap" style="gap: 8px;">
						<span class="small font-weight-bold text-muted mr-1">Tautan Cepat:</span>
						<a href="https://docs.google.com/spreadsheets/d/116hr1DHsrlX_spxVRvE8B7jrYmgsHTvQpED-xuW00ls/edit?gid=2070287268#gid=2070287268" target="_blank" class="btn btn-sm btn-sheet-link">
							<i class="fas fa-external-link-alt mr-1"></i> Buka Spreadsheet Sasaran & Manajemen Mutu 2026
						</a>
						<a href="<?= base_url('sasaran_mutu/export_csv?tahun=' . $selected_year . '&divisi=' . urlencode($selected_div) . '&posisi=' . urlencode($selected_pos)) ?>" class="btn btn-sm btn-success">
							<i class="fas fa-file-excel mr-1"></i> Download CSV / Excel
						</a>
						<button type="button" class="btn btn-sm btn-secondary" onclick="window.print();">
							<i class="fas fa-print mr-1"></i> Cetak Halaman
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
			<div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
				<h5 class="mb-0 font-weight-bold text-dark">
					<i class="fas fa-list-alt text-primary mr-1"></i> Daftar Rincian Sasaran Mutu <?= $selected_year ?>
					<?php if ($selected_div !== 'all'): ?>
						— <span class="badge badge-info"><?= htmlspecialchars($selected_div) ?></span>
					<?php endif; ?>
				</h5>
				<span class="badge badge-light border text-muted px-2 py-1 font-weight-normal">
					Menampilkan <?= count($items) ?> data baris
				</span>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
					<table class="table table-bordered table-sasaran-mutu mb-0" id="tableSasaranMutu">
						<thead>
							<tr>
								<th style="width: 40px;">No</th>
								<th style="width: 140px;">Divisi & Posisi</th>
								<th style="width: 200px;">Jobdesk</th>
								<th style="width: 220px;">
									<div>List Detail Proyek</div>
									<span class="badge-role-karyawan"><i class="fas fa-user-edit"></i> Diisi Karyawan</span>
								</th>
								<th style="width: 240px;">
									<div>Implementasi Nyata Kegiatan</div>
									<span class="badge-role-karyawan"><i class="fas fa-user-edit"></i> Diisi Karyawan</span>
								</th>
								<th style="width: 220px;">
									<div>Sasaran Mutu</div>
									<span class="badge-role-head"><i class="fas fa-user-tie"></i> Diisi Head / Karyawan</span>
								</th>
								<th style="width: 200px;">
									<div>Metode Pengukuran</div>
									<span class="badge-role-head"><i class="fas fa-calculator"></i> Angka & Rumus</span>
								</th>
								<th style="width: 200px;">
									<div>Indikator Keberhasilan</div>
									<span class="badge-role-head"><i class="fas fa-flag-checkered"></i> Kriteria / Target</span>
								</th>
								<th style="width: 130px;">
									<div>Keterangan</div>
									<span class="badge badge-light border" style="font-size: 9px;">Nilai / Realisasi</span>
								</th>
								<th style="width: 70px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($items)): ?>
								<tr>
									<td colspan="10" class="text-center py-5 text-muted">
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
					<i class="fas fa-edit text-warning mr-1"></i> Edit Realisasi & Sasaran Mutu (Tahun <span id="modalYearTitle">2026</span>)
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
					<div class="card p-3 mb-3 border bg-white">
						<div class="d-flex justify-content-between align-items-center mb-1">
							<span class="badge badge-primary font-weight-bold px-2 py-1" id="badgeModalDivisi">HRGA</span>
							<span class="text-muted small">Posisi: <strong id="textModalPosisi">HR & Legal</strong></span>
						</div>
						<div class="font-weight-bold text-dark mt-1" id="textModalJobdesk">
							Jobdesk text goes here...
						</div>
					</div>

					<div class="row">
						<!-- Kolom Karyawan: List Detail Proyek & Implementasi Nyata Kegiatan -->
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

								<div class="form-group mb-0">
									<label class="small font-weight-bold text-dark">
										Implementasi Nyata Kegiatan
										<span class="text-muted font-weight-normal">(Realisasi pekerjaan atas jobdesc ini)</span>
									</label>
									<textarea name="implementasi" id="inputImplementasi" class="form-control" rows="5" placeholder="Tuliskan realisasi nyata pekerjaan yang telah/sedang dilaksanakan..."></textarea>
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
										<span class="text-muted font-weight-normal">(Hasil nilai dari metode pengukuran / persentase realisasi)</span>
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
