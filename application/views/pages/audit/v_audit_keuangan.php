<header class="page-header">
	<h2><i class="fas fa-file-invoice-dollar text-success"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Kepegawaian</span></li>
			<li><span>Audit</span></li>
			<li><span>Audit Keuangan</span></li>
		</ol>
	</div>
</header>

<style>
	.table-keuangan th {
		background-color: #0f172a;
		color: #ffffff;
		font-size: 12px;
		font-weight: 600;
		vertical-align: middle !important;
		text-align: center;
		border: 1px solid #1e293b !important;
	}
	.table-keuangan td {
		font-size: 12px;
		vertical-align: top !important;
		border: 1px solid #e2e8f0 !important;
		line-height: 1.5;
	}
	.table-keuangan tr:hover {
		background-color: #f8fafc;
	}
	.stat-card-keuangan {
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
	.badge-invoice-ada {
		background-color: #ecfdf5;
		color: #059669;
		border: 1px solid #a7f3d0;
		padding: 5px 10px;
		border-radius: 6px;
		font-weight: 600;
		display: inline-flex;
		align-items: center;
		gap: 5px;
		text-decoration: none !important;
		transition: all 0.2s ease;
	}
	.badge-invoice-ada:hover {
		background-color: #d1fae5;
		color: #047857;
	}
	.badge-invoice-kosong {
		background-color: #fff1f2;
		color: #e11d48;
		border: 1px solid #fecdd3;
		padding: 5px 10px;
		border-radius: 6px;
		font-weight: 600;
		display: inline-flex;
		align-items: center;
		gap: 5px;
	}
</style>

<div class="row">
	<div class="col-12">
		<!-- Summary Cards -->
		<div class="row mb-3">
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-keuangan" style="border-left: 4px solid #10b981;">
					<div class="text-muted small font-weight-bold text-uppercase">Total Pengajuan PBOK</div>
					<div class="h3 font-weight-bold text-success mb-0 mt-1"><?= $summary['total_pbok'] ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-file-invoice"></i> PBOK Terdata</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-keuangan" style="border-left: 4px solid #3b82f6;">
					<div class="text-muted small font-weight-bold text-uppercase">Total Nominal Belanja PBOK</div>
					<div class="h4 font-weight-bold text-primary mb-0 mt-1">
						Rp <?= number_format($summary['total_nominal'], 0, ',', '.') ?>
					</div>
					<div class="small text-muted mt-1"><i class="fas fa-coins"></i> Akumulasi Biaya Diajukan</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-keuangan" style="border-left: 4px solid #059669;">
					<div class="text-muted small font-weight-bold text-uppercase">Lampiran / Invoice Ada</div>
					<div class="h3 font-weight-bold text-success mb-0 mt-1"><?= $summary['with_invoice'] ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-paperclip text-success"></i> Berkas Pendukung Lengkap</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 mb-2">
				<div class="stat-card-keuangan" style="border-left: 4px solid #ef4444;">
					<div class="text-muted small font-weight-bold text-uppercase">Belum Ada Invoice / Lampiran</div>
					<div class="h3 font-weight-bold text-danger mb-0 mt-1"><?= $summary['without_invoice'] ?></div>
					<div class="small text-muted mt-1"><i class="fas fa-exclamation-circle text-danger"></i> Perlu Dilengkapi</div>
				</div>
			</div>
		</div>

		<!-- Filter Bar -->
		<div class="filter-card">
			<form method="GET" action="<?= base_url('audit/keuangan') ?>" id="formFilterKeuangan">
				<div class="row align-items-end">
					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="far fa-calendar-alt"></i> Tahun</label>
						<select name="tahun" class="form-control form-control-sm font-weight-bold" onchange="document.getElementById('formFilterKeuangan').submit();">
							<option value="">-- Semua Tahun --</option>
							<?php for ($y = 2026; $y >= 2023; $y--): ?>
								<option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
							<?php endfor; ?>
						</select>
					</div>

					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="far fa-calendar"></i> Bulan</label>
						<select name="bulan" class="form-control form-control-sm" onchange="document.getElementById('formFilterKeuangan').submit();">
							<option value="">-- Semua Bulan --</option>
							<?php 
							$bulanNames = [
								1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
								5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
								9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
							];
							foreach ($bulanNames as $mNum => $mName):
							?>
								<option value="<?= $mNum ?>" <?= $selected_bulan == $mNum ? 'selected' : '' ?>><?= $mName ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="fas fa-tasks"></i> Status Verifikasi Audit</label>
						<select name="status" class="form-control form-control-sm" onchange="document.getElementById('formFilterKeuangan').submit();">
							<option value="all">-- Semua Status Audit --</option>
							<option value="Sesuai & Valid" <?= $selected_status == 'Sesuai & Valid' ? 'selected' : '' ?>>Sesuai & Valid</option>
							<option value="Menunggu Invoice Asli" <?= $selected_status == 'Menunggu Invoice Asli' ? 'selected' : '' ?>>Menunggu Invoice Asli</option>
							<option value="Lampiran Tersedia" <?= $selected_status == 'Lampiran Tersedia' ? 'selected' : '' ?>>Lampiran Tersedia</option>
							<option value="Belum Ada Lampiran" <?= $selected_status == 'Belum Ada Lampiran' ? 'selected' : '' ?>>Belum Ada Lampiran</option>
							<option value="Selisih Nominal / Temuan" <?= $selected_status == 'Selisih Nominal / Temuan' ? 'selected' : '' ?>>Selisih Nominal / Temuan</option>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold small text-muted"><i class="fas fa-search"></i> Cari No PBOK / Pengaju</label>
						<input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" class="form-control form-control-sm" placeholder="No PBOK, pengaju, rekening...">
					</div>

					<div class="col-md-2 col-sm-6 mb-2 text-right">
						<button type="submit" class="btn btn-sm btn-primary btn-block mb-1">
							<i class="fas fa-filter"></i> Filter
						</button>
						<a href="<?= base_url('audit/keuangan') ?>" class="btn btn-sm btn-light border btn-block">
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
					<i class="fas fa-receipt text-success mr-1"></i> Data Audit Belanja PBOK & Lampiran Invoice
				</h5>
				<div class="d-flex align-items-center" style="gap: 8px;">
					<a href="<?= base_url('surat/show/list_pbok') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
						<i class="fas fa-external-link-alt mr-1"></i> Buka Master Surat PBOK
					</a>
					<button type="button" class="btn btn-sm btn-secondary" onclick="window.print();">
						<i class="fas fa-print mr-1"></i> Cetak
					</button>
				</div>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
					<table class="table table-bordered table-keuangan mb-0" id="tableAuditKeuangan">
						<thead>
							<tr>
								<th style="width: 40px;">No</th>
								<th style="width: 170px;">No. Kode PBOK</th>
								<th style="width: 110px;">Tanggal Aju</th>
								<th style="width: 150px;">Pengaju / Divisi</th>
								<th style="width: 220px;">Rincian Barang / Kebutuhan</th>
								<th style="width: 130px;">Total Nominal</th>
								<th style="width: 160px;">Rekening Pembayaran</th>
								<th style="width: 170px;">
									<i class="fas fa-file-invoice text-success"></i> Invoice & Lampiran
								</th>
								<th style="width: 140px;">Status Audit Keuangan</th>
								<th style="width: 80px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($pbok_list)): ?>
								<tr>
									<td colspan="10" class="text-center py-5 text-muted">
										<i class="fas fa-folder-open fa-3x mb-3 text-secondary d-block"></i>
										Tidak ada data PBOK yang sesuai filter saat ini.
									</td>
								</tr>
							<?php else: ?>
								<?php $no = 1; foreach ($pbok_list as $row): ?>
									<tr id="row-pbok-<?= $row['id_pbok'] ?>">
										<td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
										<td>
											<span class="font-weight-bold text-dark d-block"><?= htmlspecialchars($row['kode_pbok']) ?></span>
											<a href="<?= base_url('surat/show/detail_surat/pbok/' . $row['id_pbok'] . '/1') ?>" target="_blank" class="small text-primary">
												<i class="fas fa-external-link-alt"></i> Detail Surat PBOK
											</a>
										</td>
										<td class="small text-muted text-center">
											<?= !empty($row['tgl_pengajuan']) ? date('d/m/Y', strtotime($row['tgl_pengajuan'])) : '-' ?>
										</td>
										<td>
											<div class="font-weight-bold text-dark small"><?= htmlspecialchars($row['pengguna']) ?></div>
											<span class="badge badge-light border text-muted" style="font-size: 10px;"><?= htmlspecialchars($row['jabatan']) ?></span>
											<?php if (!empty($row['divisi'])): ?>
												<div class="small text-secondary mt-1"><?= htmlspecialchars($row['divisi']) ?></div>
											<?php endif; ?>
										</td>
										<td>
											<?php if (!empty($row['items'])): ?>
												<ul class="pl-3 mb-1 small text-dark" style="font-size: 11px;">
													<?php foreach (array_slice($row['items'], 0, 3) as $it): ?>
														<li>
															<strong><?= htmlspecialchars($it['keterangan']) ?></strong> 
															(<?= $it['qty'] ?>x @ Rp <?= number_format($it['estimasi'], 0, ',', '.') ?>)
														</li>
													<?php endforeach; ?>
													<?php if (count($row['items']) > 3): ?>
														<li class="text-muted font-italic">+<?= count($row['items']) - 3 ?> item lainnya...</li>
													<?php endif; ?>
												</ul>
												<button type="button" class="btn btn-xs btn-link p-0 text-primary small" onclick='showPbokItems(<?= json_encode($row) ?>)'>
													<i class="fas fa-list"></i> Lihat Seluruh Item (<?= count($row['items']) ?>)
												</button>
											<?php else: ?>
												<span class="text-muted small font-italic">Tidak ada item detail</span>
											<?php endif; ?>
										</td>
										<td class="text-right font-weight-bold text-dark">
											Rp <?= number_format($row['total_nominal'], 0, ',', '.') ?>
										</td>
										<td class="small text-secondary">
											<div>Bank: <strong><?= htmlspecialchars($row['bank'] ?: '-') ?></strong></div>
											<div>No. Rek: <strong><?= htmlspecialchars($row['rekening'] ?: '-') ?></strong></div>
											<div>A.N: <strong><?= htmlspecialchars($row['ats_nama'] ?: '-') ?></strong></div>
										</td>
										<!-- Invoice / Lampiran Kolom -->
										<td class="text-center" id="cell-invoice-<?= $row['id_pbok'] ?>">
											<?php if ($row['has_lampiran']): ?>
												<a href="<?= htmlspecialchars($row['lampiran']) ?>" target="_blank" class="badge-invoice-ada mb-1" title="Buka berkas invoice atau bukti lampiran">
													<i class="fas fa-paperclip"></i>
													<span>Lihat Invoice / Lampiran</span>
												</a>
												<div class="small text-muted" style="font-size: 10px;">
													<i class="fas fa-check-circle text-success"></i> Berkas Terlampir
												</div>
											<?php else: ?>
												<div class="badge-invoice-kosong mb-1">
													<i class="fas fa-times-circle"></i>
													<span>Belum Ada Lampiran</span>
												</div>
												<div class="small text-muted" style="font-size: 10px;">
													Wajib verifikasi fisik / nota
												</div>
											<?php endif; ?>
										</td>
										<!-- Status Audit Keuangan -->
										<td class="text-center" id="cell-status-pbok-<?= $row['id_pbok'] ?>">
											<?php
											$statusClass = 'badge-secondary';
											if ($row['status_audit'] === 'Sesuai & Valid') {
												$statusClass = 'badge-success';
											} else if ($row['status_audit'] === 'Lampiran Tersedia') {
												$statusClass = 'badge-info';
											} else if ($row['status_audit'] === 'Menunggu Invoice Asli') {
												$statusClass = 'badge-warning';
											} else if ($row['status_audit'] === 'Selisih Nominal / Temuan') {
												$statusClass = 'badge-danger';
											}
											?>
											<span class="badge <?= $statusClass ?> px-2 py-1 font-weight-bold" style="font-size: 11px;">
												<?= htmlspecialchars($row['status_audit']) ?>
											</span>
											<?php if (!empty($row['catatan_audit'])): ?>
												<div class="small text-muted mt-1" style="font-size: 11px;">
													"<?= htmlspecialchars($row['catatan_audit']) ?>"
												</div>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<button type="button" class="btn btn-xs btn-outline-success" title="Verifikasi Audit Keuangan & Invoice" onclick='openModalKeuangan(<?= json_encode($row) ?>)'>
												<i class="fas fa-check-square"></i> Verifikasi
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

<!-- Modal Detail Item PBOK -->
<div class="modal fade" id="modalPbokItems" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title font-weight-bold">
					<i class="fas fa-list-check text-primary mr-1"></i> Rincian Item Barang PBOK: <span id="detailPbokKode"></span>
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-4">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="tablePbokDetailList">
						<thead class="bg-light">
							<tr>
								<th>No</th>
								<th>Keterangan / Nama Barang</th>
								<th class="text-center">Qty</th>
								<th class="text-right">Estimasi Satuan</th>
								<th class="text-right">Total Nominal</th>
								<th>Catatan</th>
							</tr>
						</thead>
						<tbody id="pbokDetailTbody">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer bg-light">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Verifikasi Audit Keuangan -->
<div class="modal fade" id="modalAuditKeuangan" tabindex="-1" role="dialog" aria-labelledby="modalAuditKeuanganLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content" style="border-radius: 12px; overflow: hidden;">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title font-weight-bold" id="modalAuditKeuanganLabel">
					<i class="fas fa-shield-alt text-success mr-1"></i> Verifikasi Audit PBOK & Invoice
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formSaveKeuangan" onsubmit="saveKeuanganData(event);">
				<input type="hidden" name="id_pbok" id="keuanganIdPbok">

				<div class="modal-body p-4" style="background-color: #f8fafc;">
					<!-- Ringkasan PBOK -->
					<div class="card p-3 mb-3 border bg-white">
						<div class="d-flex justify-content-between align-items-center mb-1">
							<span class="font-weight-bold text-dark" id="modalKeuanganKode">PBOK Code</span>
							<span class="badge badge-success px-2 py-1 font-weight-bold" id="modalKeuanganNominal">Rp 0</span>
						</div>
						<div class="small text-muted">
							Pengaju: <strong id="modalKeuanganPengaju">Nama</strong> (<span id="modalKeuanganJabatan">Jabatan</span>)
						</div>
						<div class="small text-muted mt-1" id="modalKeuanganLampiranPreview">
						</div>
					</div>

					<!-- Form Status Audit Keuangan -->
					<div class="form-group mb-3">
						<label class="font-weight-bold small text-dark">Status Hasil Audit Keuangan</label>
						<select name="status_audit" id="keuanganSelectStatus" class="form-control font-weight-bold">
							<option value="Sesuai & Valid" class="text-success">Sesuai & Valid (Invoice & Nominal Sah)</option>
							<option value="Menunggu Invoice Asli" class="text-warning">Menunggu Invoice Asli / Bukti Pembelian</option>
							<option value="Lampiran Tersedia" class="text-info">Lampiran Tersedia</option>
							<option value="Selisih Nominal / Temuan" class="text-danger">Selisih Nominal / Temuan Audit</option>
							<option value="Belum Diverifikasi" class="text-secondary">Belum Diverifikasi</option>
						</select>
					</div>

					<!-- Kelengkapan Invoice -->
					<div class="form-group mb-3">
						<label class="font-weight-bold small text-dark">Kondisi Kelengkapan Invoice / Kuitansi</label>
						<select name="kelengkapan_invoice" id="keuanganSelectKelengkapan" class="form-control">
							<option value="Lengkap">Lengkap (Nota/Kuitansi/Faktur Jelas)</option>
							<option value="Kurang Jelas / Buram">Kurang Jelas / Foto Buram</option>
							<option value="Tidak Ada File">Tidak Ada File Invoice</option>
							<option value="Perlu Nota Asli">Perlu Nota Asli Fisik</option>
						</select>
					</div>

					<!-- Catatan Auditor -->
					<div class="form-group mb-0">
						<label class="font-weight-bold small text-dark">Catatan Auditor / Tim Finance</label>
						<textarea name="catatan_audit" id="keuanganInputCatatan" class="form-control" rows="3" placeholder="Catatan hasil pemeriksaan invoice dan pengeluaran PBOK..."></textarea>
					</div>
				</div>

				<div class="modal-footer bg-white border-top">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
						<i class="fas fa-times"></i> Batal
					</button>
					<button type="submit" id="btnSubmitKeuangan" class="btn btn-success btn-sm font-weight-bold px-4">
						<i class="fas fa-save mr-1"></i> Simpan Verifikasi Keuangan
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	function showPbokItems(row) {
		document.getElementById('detailPbokKode').textContent = row.kode_pbok;
		const tbody = document.getElementById('pbokDetailTbody');
		tbody.innerHTML = '';

		if (row.items && row.items.length > 0) {
			row.items.forEach((it, idx) => {
				const tr = document.createElement('tr');
				tr.innerHTML = `
					<td class="text-center">${idx + 1}</td>
					<td><strong>${escapeHtml(it.keterangan || '-')}</strong></td>
					<td class="text-center">${it.qty}</td>
					<td class="text-right">Rp ${Number(it.estimasi || 0).toLocaleString('id-ID')}</td>
					<td class="text-right font-weight-bold">Rp ${Number(it.total || 0).toLocaleString('id-ID')}</td>
					<td class="small text-muted">${escapeHtml(it.catatan || '-')}</td>
				`;
				tbody.appendChild(tr);
			});
		} else {
			tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Tidak ada rincian item.</td></tr>';
		}

		$('#modalPbokItems').modal('show');
	}

	function openModalKeuangan(row) {
		document.getElementById('keuanganIdPbok').value = row.id_pbok;
		document.getElementById('modalKeuanganKode').textContent = row.kode_pbok;
		document.getElementById('modalKeuanganNominal').textContent = 'Rp ' + Number(row.total_nominal || 0).toLocaleString('id-ID');
		document.getElementById('modalKeuanganPengaju').textContent = row.pengguna;
		document.getElementById('modalKeuanganJabatan').textContent = row.jabatan || 'Staff';

		const lampiranPreview = document.getElementById('modalKeuanganLampiranPreview');
		if (row.lampiran) {
			lampiranPreview.innerHTML = `
				<a href="${escapeHtml(row.lampiran)}" target="_blank" class="text-primary font-weight-bold">
					<i class="fas fa-external-link-alt"></i> Buka Lampiran Invoice Saat Ini
				</a>
			`;
		} else {
			lampiranPreview.innerHTML = '<span class="text-danger small"><i class="fas fa-times-circle"></i> Tidak ada lampiran terunggah pada PBOK ini.</span>';
		}

		document.getElementById('keuanganSelectStatus').value = row.status_audit || 'Sesuai & Valid';
		document.getElementById('keuanganSelectKelengkapan').value = row.kelengkapan_invoice || 'Lengkap';
		document.getElementById('keuanganInputCatatan').value = row.catatan_audit || '';

		$('#modalAuditKeuangan').modal('show');
	}

	function saveKeuanganData(e) {
		e.preventDefault();
		const btn = document.getElementById('btnSubmitKeuangan');
		btn.disabled = true;
		btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

		const form = document.getElementById('formSaveKeuangan');
		const formData = new FormData(form);

		fetch('<?= base_url('audit/save_keuangan') ?>', {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			btn.disabled = false;
			btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Verifikasi Keuangan';

			if (data.status === 'success') {
				const idPbok = formData.get('id_pbok');
				const statusVal = formData.get('status_audit');
				const catatanVal = formData.get('catatan_audit');

				// Update Status DOM
				const statusCell = document.getElementById('cell-status-pbok-' + idPbok);
				if (statusCell) {
					let sClass = 'badge-secondary';
					if (statusVal === 'Sesuai & Valid') sClass = 'badge-success';
					else if (statusVal === 'Lampiran Tersedia') sClass = 'badge-info';
					else if (statusVal === 'Menunggu Invoice Asli') sClass = 'badge-warning';
					else if (statusVal === 'Selisih Nominal / Temuan') sClass = 'badge-danger';

					let html = `<span class="badge ${sClass} px-2 py-1 font-weight-bold" style="font-size: 11px;">${escapeHtml(statusVal)}</span>`;
					if (catatanVal) {
						html += `<div class="small text-muted mt-1" style="font-size: 11px;">"${escapeHtml(catatanVal)}"</div>`;
					}
					statusCell.innerHTML = html;
				}

				$('#modalAuditKeuangan').modal('hide');

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
			btn.innerHTML = '<i class="fas fa-save mr-1"></i> Simpan Verifikasi Keuangan';
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
