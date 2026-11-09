<header class="page-header">
	<h2><i class="fas fa-users-cog text-primary"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Kepegawaian</span></li>
			<li><span>Audit</span></li>
			<li><span>Audit Kinerja</span></li>
		</ol>
	</div>
</header>

<style>
	.table-audit th {
		background-color: #1e293b;
		color: #ffffff;
		font-size: 12px;
		font-weight: 600;
		vertical-align: middle !important;
		text-align: center;
		border: 1px solid #334155 !important;
	}
	.table-audit td {
		font-size: 12px;
		vertical-align: top !important;
		border: 1px solid #e2e8f0 !important;
		line-height: 1.5;
	}
	.table-audit tr:hover {
		background-color: #f8fafc;
	}
	.multiline-text {
		white-space: pre-line;
	}
	.stat-card-audit {
		border-radius: 10px;
		padding: 16px;
		background: #ffffff;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0,0,0,0.05);
	}
	.filter-card {
		background: #ffffff;
		border-radius: 10px;
		border: 1px solid #e2e8f0;
		padding: 18px;
		margin-bottom: 20px;
	}
	.doc-link-btn {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		background-color: #eff6ff;
		color: #1d4ed8;
		border: 1px solid #bfdbfe;
		padding: 4px 10px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 600;
		text-decoration: none;
		max-width: 100%;
		word-break: break-all;
	}
	.doc-link-btn:hover {
		background-color: #dbeafe;
		color: #1e40af;
		text-decoration: none;
	}
</style>

<div class="row">
	<div class="col-12">
		<!-- Summary Cards -->
		<div class="row mb-3">
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-audit" style="border-left: 4px solid #3b82f6;">
					<div class="text-muted small font-weight-bold text-uppercase">Total Item Audit Kinerja</div>
					<div class="h3 font-weight-bold text-primary mb-0 mt-1"><?= $summary['total_items'] ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-sitemap"></i> Seluruh Karyawan / Divisi</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-audit" style="border-left: 4px solid #10b981;">
					<div class="text-muted small font-weight-bold text-uppercase">Terverifikasi / Sesuai</div>
					<div class="h3 font-weight-bold text-success mb-0 mt-1"><?= $summary['verified_count'] ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-check-double text-success"></i> Dokumen Bukti Valid</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-audit" style="border-left: 4px solid #f59e0b;">
					<div class="text-muted small font-weight-bold text-uppercase">Perlu Bukti Tambahan</div>
					<div class="h3 font-weight-bold text-warning mb-0 mt-1"><?= $summary['need_evidence_count'] ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-exclamation-triangle text-warning"></i> Menunggu Kelengkapan</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-audit" style="border-left: 4px solid #64748b;">
					<div class="text-muted small font-weight-bold text-uppercase">Belum Diaudit / Pending</div>
					<div class="h3 font-weight-bold text-secondary mb-0 mt-1"><?= $summary['pending_count'] ?></div>
					<div class="small text-muted mt-1"><i class="far fa-clock"></i> Antrean Verifikasi</div>
				</div>
			</div>
		</div>

		<!-- Filter Bar -->
		<div class="filter-card">
			<form method="GET" action="<?= base_url('audit/kinerja') ?>" id="formFilterKinerja">
				<div class="row align-items-end">
					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="far fa-calendar-alt"></i> Tahun Periode</label>
						<select name="tahun" id="filterTahun" class="form-control form-control-sm font-weight-bold" onchange="document.getElementById('formFilterKinerja').submit();">
							<?php for ($y = 2026; $y <= 2030; $y++): ?>
								<option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
							<?php endfor; ?>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="fas fa-sitemap"></i> Divisi Karyawan</label>
						<select name="divisi" id="filterDivisi" class="form-control form-control-sm" onchange="updatePosisiDropdown(); document.getElementById('formFilterKinerja').submit();">
							<option value="all">-- Semua Divisi --</option>
							<?php foreach ($div_tree as $divName => $posList): ?>
								<option value="<?= $divName ?>" <?= $selected_div == $divName ? 'selected' : '' ?>><?= $divName ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="fas fa-user-tag"></i> Posisi / Karyawan</label>
						<select name="posisi" id="filterPosisi" class="form-control form-control-sm" onchange="document.getElementById('formFilterKinerja').submit();">
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
						<label class="font-weight-bold small text-muted"><i class="fas fa-search"></i> Cari Jobdesk / Bukti</label>
						<input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" class="form-control form-control-sm" placeholder="Cari rincian...">
					</div>

					<div class="col-md-2 col-sm-6 mb-2 text-right">
						<button type="submit" class="btn btn-sm btn-primary btn-block mb-1">
							<i class="fas fa-filter"></i> Terapkan
						</button>
						<a href="<?= base_url('audit/kinerja') ?>" class="btn btn-sm btn-light border btn-block">
							<i class="fas fa-undo"></i> Reset
						</a>
					</div>
				</div>
			</form>
		</div>

		<!-- Table Card -->
		<div class="card border-0 shadow-sm" style="border-radius: 10px;">
			<div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
				<h5 class="mb-0 font-weight-bold text-dark">
					<i class="fas fa-clipboard-check text-primary mr-1"></i> Data Audit Kinerja Karyawan (Tahun <?= $selected_year ?>)
				</h5>
				<div class="d-flex align-items-center" style="gap: 8px;">
					<a href="<?= base_url('sasaran_mutu?tahun=' . $selected_year) ?>" class="btn btn-sm btn-outline-warning">
						<i class="fas fa-bullseye mr-1"></i> Ke Halaman Sasaran Mutu
					</a>
					<button type="button" class="btn btn-sm btn-secondary" onclick="window.print();">
						<i class="fas fa-print mr-1"></i> Cetak
					</button>
				</div>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
					<table class="table table-bordered table-audit mb-0" id="tableAuditKinerja">
						<thead>
							<tr>
								<th style="width: 40px;">No</th>
								<th style="width: 150px;">Divisi & Posisi</th>
								<th style="width: 220px;">Jobdesk</th>
								<th style="width: 230px;">List Detail Proyek</th>
								<th style="width: 260px;">Implementasi Nyata Kinerja</th>
								<th style="width: 210px;">Link Dokumen Pekerjaan</th>
								<th style="width: 140px;">Status Audit</th>
								<th style="width: 160px;">Catatan Auditor</th>
								<th style="width: 80px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($items)): ?>
								<tr>
									<td colspan="9" class="text-center py-5 text-muted">
										<i class="fas fa-folder-open fa-3x mb-3 text-secondary d-block"></i>
										Tidak ada data audit kinerja yang sesuai filter.
									</td>
								</tr>
							<?php else: ?>
								<?php $no = 1; foreach ($items as $row): ?>
									<tr id="row-kinerja-<?= htmlspecialchars($row['id']) ?>">
										<td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
										<td>
											<span class="badge badge-primary px-2 py-1 mb-1 d-inline-block"><?= htmlspecialchars($row['divisi']) ?></span>
											<div class="font-weight-bold text-dark small"><?= htmlspecialchars($row['posisi']) ?></div>
										</td>
										<td class="font-weight-600 text-dark">
											<?= htmlspecialchars($row['jobdesk']) ?>
										</td>
										<td class="multiline-text text-secondary font-italic small">
											<?= !empty($row['detail_proyek']) ? htmlspecialchars($row['detail_proyek']) : '<span class="text-muted small">-</span>' ?>
										</td>
										<td class="multiline-text" id="imp-kinerja-<?= htmlspecialchars($row['id']) ?>">
											<?php if (!empty($row['implementasi'])): ?>
												<span class="text-dark small"><?= htmlspecialchars($row['implementasi']) ?></span>
											<?php else: ?>
												<span class="badge badge-light text-muted border font-weight-normal"><i class="fas fa-pen"></i> Belum diisi</span>
											<?php endif; ?>
										</td>
										<td id="cell-link-<?= htmlspecialchars($row['id']) ?>">
											<?php if (!empty($row['link_dokumen'])): ?>
												<a href="<?= htmlspecialchars($row['link_dokumen']) ?>" target="_blank" class="doc-link-btn" title="Buka Dokumen Bukti Pekerjaan">
													<i class="fas fa-external-link-alt text-primary"></i>
													<span>Buka Dokumen Pekerjaan</span>
												</a>
												<div class="text-truncate small text-muted mt-1" style="max-width: 190px;" title="<?= htmlspecialchars($row['link_dokumen']) ?>">
													<?= htmlspecialchars($row['link_dokumen']) ?>
												</div>
											<?php else: ?>
												<span class="badge badge-light text-muted border font-weight-normal">
													<i class="fas fa-link-slash"></i> Belum ada link dokumen
												</span>
											<?php endif; ?>
										</td>
										<td class="text-center" id="cell-status-<?= htmlspecialchars($row['id']) ?>">
											<?php 
											$statusClass = 'badge-secondary';
											if ($row['status_audit'] === 'Terverifikasi / Sesuai') {
												$statusClass = 'badge-success';
											} else if ($row['status_audit'] === 'Perlu Bukti Tambahan') {
												$statusClass = 'badge-warning';
											} else if ($row['status_audit'] === 'Temuan / Tidak Sesuai') {
												$statusClass = 'badge-danger';
											}
											?>
											<span class="badge <?= $statusClass ?> px-2 py-1 font-weight-bold" style="font-size: 11px;">
												<?= htmlspecialchars($row['status_audit']) ?>
											</span>
											<?php if (!empty($row['audited_by_name'])): ?>
												<div class="small text-muted mt-1" style="font-size: 10px;">
													Oleh: <?= htmlspecialchars($row['audited_by_name']) ?>
												</div>
											<?php endif; ?>
										</td>
										<td class="small text-secondary" id="cell-catatan-<?= htmlspecialchars($row['id']) ?>">
											<?= !empty($row['catatan_audit']) ? htmlspecialchars($row['catatan_audit']) : '<span class="text-muted">-</span>' ?>
										</td>
										<td class="text-center">
											<button type="button" class="btn btn-xs btn-outline-primary" title="Input Dokumen / Verifikasi Audit" onclick='openModalKinerja(<?= json_encode($row) ?>)'>
												<i class="fas fa-clipboard-check"></i> Audit
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

<!-- Modal Audit Kinerja & Input Link Dokumen -->
<div class="modal fade" id="modalAuditKinerja" tabindex="-1" role="dialog" aria-labelledby="modalAuditKinerjaLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title font-weight-bold" id="modalAuditKinerjaLabel">
					<i class="fas fa-clipboard-check text-primary mr-1"></i> Audit Kinerja & Dokumen Pekerjaan
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formSaveKinerja" onsubmit="saveKinerjaData(event);">
				<input type="hidden" name="item_id" id="kinerjaItemId">
				<input type="hidden" name="year" id="kinerjaYear" value="<?= $selected_year ?>">

				<div class="modal-body p-4" style="background-color: #f8fafc;">
					<!-- Rincian Jobdesk -->
					<div class="card p-3 mb-3 border bg-white">
						<div class="d-flex justify-content-between align-items-center mb-1">
							<span class="badge badge-primary font-weight-bold px-2 py-1" id="kinerjaBadgeDivisi">Divisi</span>
							<span class="text-muted small">Posisi: <strong id="kinerjaTextPosisi">Posisi</strong></span>
						</div>
						<div class="font-weight-bold text-dark mt-1" id="kinerjaTextJobdesk">
							Jobdesk text...
						</div>
					</div>

					<!-- Form Input Link Dokumen Pekerjaan -->
					<div class="card p-3 mb-3 border bg-white" style="border-left: 4px solid #3b82f6 !important;">
						<label class="font-weight-bold text-dark mb-1">
							<i class="fas fa-link text-primary mr-1"></i> Link Dokumen Pekerjaan
						</label>
						<p class="small text-muted mb-2">
							Masukkan URL Google Drive, Google Docs, link PDF, SharePoint, atau repository file bukti hasil pekerjaan.
						</p>
						<input type="url" name="link_dokumen" id="kinerjaInputLink" class="form-control" placeholder="https://drive.google.com/... atau https://docs.google.com/...">
					</div>

					<!-- Update Implementasi Realisasi Pekerjaan -->
					<div class="card p-3 mb-3 border bg-white">
						<label class="font-weight-bold text-dark mb-1">
							<i class="fas fa-tasks text-success mr-1"></i> Implementasi Nyata Kinerja / Realisasi Pekerjaan
						</label>
						<textarea name="implementasi" id="kinerjaInputImplementasi" class="form-control" rows="4" placeholder="Uraian realisasi nyata pelaksanaan jobdesk..."></textarea>
					</div>

					<!-- Form Verifikasi Auditor -->
					<div class="card p-3 border bg-white" style="border-left: 4px solid #f59e0b !important;">
						<h6 class="font-weight-bold text-warning mb-2" style="color: #b45309 !important;">
							<i class="fas fa-user-shield mr-1"></i> Evaluasi & Verifikasi Auditor
						</h6>
						<div class="row">
							<div class="col-md-6 mb-2">
								<label class="small font-weight-bold text-dark">Status Hasil Audit</label>
								<select name="status_audit" id="kinerjaSelectStatus" class="form-control font-weight-bold">
									<option value="Belum Diaudit">Belum Diaudit</option>
									<option value="Terverifikasi / Sesuai" class="text-success font-weight-bold">Terverifikasi / Sesuai</option>
									<option value="Perlu Bukti Tambahan" class="text-warning font-weight-bold">Perlu Bukti Tambahan</option>
									<option value="Temuan / Tidak Sesuai" class="text-danger font-weight-bold">Temuan / Tidak Sesuai</option>
								</select>
							</div>
							<div class="col-md-6 mb-2">
								<label class="small font-weight-bold text-dark">Catatan Auditor / Temuan</label>
								<textarea name="catatan_audit" id="kinerjaInputCatatan" class="form-control" rows="2" placeholder="Catatan auditor atas bukti dokumen & kinerja..."></textarea>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer bg-white border-top">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
						<i class="fas fa-times"></i> Batal
					</button>
					<button type="submit" id="btnSubmitKinerja" class="btn btn-primary btn-sm font-weight-bold px-4">
						<i class="fas fa-save mr-1"></i> Simpan Audit & Dokumen
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

	function openModalKinerja(row) {
		document.getElementById('kinerjaItemId').value = row.id;
		document.getElementById('kinerjaYear').value = row.year || '<?= $selected_year ?>';

		document.getElementById('kinerjaBadgeDivisi').textContent = row.divisi;
		document.getElementById('kinerjaTextPosisi').textContent = row.posisi;
		document.getElementById('kinerjaTextJobdesk').textContent = row.jobdesk;

		document.getElementById('kinerjaInputLink').value = row.link_dokumen || '';
		document.getElementById('kinerjaInputImplementasi').value = row.implementasi || '';
		document.getElementById('kinerjaSelectStatus').value = row.status_audit || 'Belum Diaudit';
		document.getElementById('kinerjaInputCatatan').value = row.catatan_audit || '';

		$('#modalAuditKinerja').modal('show');
	}

	function saveKinerjaData(e) {
		e.preventDefault();
		const btn = document.getElementById('btnSubmitKinerja');
		btn.disabled = true;
		btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

		const form = document.getElementById('formSaveKinerja');
		const formData = new FormData(form);

		fetch('<?= base_url('audit/save_kinerja') ?>', {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			btn.disabled = false;
			btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Audit & Dokumen';

			if (data.status === 'success') {
				const itemId = formData.get('item_id');
				const linkVal = formData.get('link_dokumen');
				const statusVal = formData.get('status_audit');
				const catatanVal = formData.get('catatan_audit');
				const impVal = formData.get('implementasi');

				// Update Link DOM
				const linkCell = document.getElementById('cell-link-' + itemId);
				if (linkCell) {
					if (linkVal) {
						linkCell.innerHTML = `
							<a href="${escapeHtml(linkVal)}" target="_blank" class="doc-link-btn" title="Buka Dokumen Bukti Pekerjaan">
								<i class="fas fa-external-link-alt text-primary"></i>
								<span>Buka Dokumen Pekerjaan</span>
							</a>
							<div class="text-truncate small text-muted mt-1" style="max-width: 190px;" title="${escapeHtml(linkVal)}">
								${escapeHtml(linkVal)}
							</div>
						`;
					} else {
						linkCell.innerHTML = '<span class="badge badge-light text-muted border font-weight-normal"><i class="fas fa-link-slash"></i> Belum ada link dokumen</span>';
					}
				}

				// Update Implementasi DOM
				const impCell = document.getElementById('imp-kinerja-' + itemId);
				if (impCell && impVal) {
					impCell.innerHTML = '<span class="text-dark small">' + escapeHtml(impVal) + '</span>';
				}

				// Update Status DOM
				const statusCell = document.getElementById('cell-status-' + itemId);
				if (statusCell) {
					let sClass = 'badge-secondary';
					if (statusVal === 'Terverifikasi / Sesuai') sClass = 'badge-success';
					else if (statusVal === 'Perlu Bukti Tambahan') sClass = 'badge-warning';
					else if (statusVal === 'Temuan / Tidak Sesuai') sClass = 'badge-danger';

					statusCell.innerHTML = `<span class="badge ${sClass} px-2 py-1 font-weight-bold" style="font-size: 11px;">${escapeHtml(statusVal)}</span>`;
				}

				// Update Catatan DOM
				const catCell = document.getElementById('cell-catatan-' + itemId);
				if (catCell) {
					catCell.textContent = catatanVal || '-';
				}

				$('#modalAuditKinerja').modal('hide');

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
				alert(data.message || 'Gagal menyimpan.');
			}
		})
		.catch(err => {
			btn.disabled = false;
			btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Audit & Dokumen';
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
