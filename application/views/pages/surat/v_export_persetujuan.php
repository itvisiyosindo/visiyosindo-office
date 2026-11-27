<header class="page-header">
	<h2><i class="fas fa-file-excel text-success"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	.export-card {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
	}
	.export-group-box {
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		padding: 12px 14px;
		height: 100%;
	}
	.export-group-title {
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.4px;
		color: #1e3a5f;
		border-bottom: 1px solid #cbd5e1;
		padding-bottom: 6px;
		margin-bottom: 10px;
	}
	.export-check-item {
		display: flex;
		align-items: flex-start;
		margin-bottom: 6px;
		font-size: 13px;
		cursor: pointer;
	}
	.export-check-item input {
		margin-top: 3px;
		margin-right: 8px;
		cursor: pointer;
	}
	.summary-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		background: #eef2ff;
		color: #1e3a8a;
		border: 1px solid #c7d2fe;
		border-radius: 20px;
		padding: 4px 12px;
		font-size: 12px;
		font-weight: 600;
		margin: 3px;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="export-card p-4 mb-3">
			<form id="form-export-persetujuan" method="get" action="<?= base_url('export_persetujuan/download') ?>" target="_blank">
				<!-- Baris Filter Periode & Status -->
				<div class="row mb-3">
					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold text-dark mb-1"><i class="fas fa-calendar-alt text-primary mr-1"></i> Tahun</label>
						<select class="form-control form-control-sm" name="tahun" id="filter_tahun">
							<option value="">-- Semua Tahun --</option>
							<?php foreach ($years as $y): ?>
								<option value="<?= $y ?>" <?= ((int)$y === (int)$default_year) ? 'selected' : '' ?>><?= $y ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-2 col-sm-6 mb-2">
						<label class="font-weight-bold text-dark mb-1"><i class="fas fa-calendar text-primary mr-1"></i> Bulan</label>
						<select class="form-control form-control-sm" name="bulan" id="filter_bulan">
							<option value="">-- Semua Bulan --</option>
							<?php
							$bulanList = [
								1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
								5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
								9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
							];
							foreach ($bulanList as $num => $nm): ?>
								<option value="<?= $num ?>"><?= $nm ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold text-dark mb-1"><i class="fas fa-calendar-day text-info mr-1"></i> Tanggal Awal (Opsional)</label>
						<input type="date" class="form-control form-control-sm" name="tgl_awal" id="filter_tgl_awal">
					</div>
					<div class="col-md-3 col-sm-6 mb-2">
						<label class="font-weight-bold text-dark mb-1"><i class="fas fa-calendar-check text-info mr-1"></i> Tanggal Akhir (Opsional)</label>
						<input type="date" class="form-control form-control-sm" name="tgl_akhir" id="filter_tgl_akhir">
					</div>
					<div class="col-md-2 col-sm-12 mb-2">
						<label class="font-weight-bold text-dark mb-1"><i class="fas fa-filter text-warning mr-1"></i> Status</label>
						<select class="form-control form-control-sm" name="status" id="filter_status">
							<option value="all">Semua Status</option>
							<option value="disetujui">Disetujui</option>
							<option value="proses">Proses / Menunggu</option>
							<option value="ditolak">Ditolak</option>
						</select>
					</div>
				</div>
				<div class="text-muted small mb-3">
					<i class="fas fa-info-circle text-primary"></i>
					Isi <strong>Tanggal Awal / Akhir</strong> untuk filter tanggal spesifik (prioritas utama). Kosongkan keduanya untuk memakai filter <strong>Tahun &amp; Bulan</strong>.
				</div>

				<!-- Pilih Jenis Persetujuan -->
				<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
					<label class="font-weight-bold text-dark mb-1">
						<i class="fas fa-check-square text-success mr-1"></i> Pilih Jenis Persetujuan yang Dicetak:
					</label>
					<div class="btn-group btn-group-sm mb-1" role="group">
						<button type="button" class="btn btn-outline-primary" id="btn-select-all">Pilih Semua</button>
						<button type="button" class="btn btn-outline-success" id="btn-select-finance">Keuangan &amp; Ekspedisi</button>
						<button type="button" class="btn btn-outline-secondary" id="btn-unselect-all">Kosongkan</button>
					</div>
				</div>

				<div class="row mb-3">
					<?php
					$hasPre = !empty($selected_jenis);
					foreach ($grouped_jenis as $groupName => $items): ?>
						<div class="col-lg-3 col-md-6 mb-2">
							<div class="export-group-box">
								<div class="export-group-title"><?= htmlspecialchars($groupName) ?></div>
								<?php foreach ($items as $key => $meta):
									$checked = $hasPre ? in_array($key, $selected_jenis, true) : true;
								?>
									<label class="export-check-item">
										<input type="checkbox" class="chk-jenis" data-group="<?= htmlspecialchars($groupName) ?>" name="jenis[]" value="<?= htmlspecialchars($key) ?>" <?= $checked ? 'checked' : '' ?>>
										<span><?= htmlspecialchars($meta['label']) ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Action Buttons -->
				<div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
					<button type="button" id="btn-preview-export" class="btn btn-primary btn-sm px-3">
						<i class="fas fa-search mr-1"></i> Tampilkan Preview Data
					</button>
					<button type="submit" id="btn-download-excel" class="btn btn-success btn-sm px-4 font-weight-bold">
						<i class="fas fa-file-excel mr-1"></i> Download Excel (.xlsx)
					</button>
					<button type="button" id="btn-reset-export" class="btn btn-secondary btn-sm px-3">
						<i class="fas fa-sync-alt mr-1"></i> Reset Filter
					</button>
				</div>
			</form>
		</div>

		<!-- Hasil Preview -->
		<div id="preview-wrapper" class="export-card p-3" style="display: none;">
			<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
				<div>
					<h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-table text-primary mr-1"></i> Preview Data Export</h5>
					<small class="text-muted" id="preview-meta"></small>
				</div>
				<div id="preview-grand-total" class="font-weight-bold text-success"></div>
			</div>
			<div id="preview-summary-pills" class="mb-3"></div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-sm mb-0" style="font-size: 12.5px;">
					<thead class="thead-dark">
						<tr>
							<th class="text-center" width="40">No</th>
							<th class="text-center" width="95">Tanggal</th>
							<th width="145">Jenis</th>
							<th width="180">No. Surat / Kode</th>
							<th width="140">Pengaju</th>
							<th width="210">Nama Transaksi</th>
							<th>Yang Ditransaksikan</th>
							<th class="text-right" width="125">Nominal</th>
							<th class="text-center" width="130">Status</th>
						</tr>
					</thead>
					<tbody id="preview-tbody"></tbody>
				</table>
			</div>
			<div id="preview-footnote" class="small text-muted mt-2"></div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var form = document.getElementById('form-export-persetujuan');
	var chkAll = function() { return Array.prototype.slice.call(document.querySelectorAll('.chk-jenis')); };

	document.getElementById('btn-select-all').addEventListener('click', function() {
		chkAll().forEach(function(el) { el.checked = true; });
	});

	document.getElementById('btn-select-finance').addEventListener('click', function() {
		chkAll().forEach(function(el) {
			var g = el.getAttribute('data-group');
			el.checked = (g === 'Keuangan & Operasional' || g === 'Ekspedisi & Pengiriman');
		});
	});

	document.getElementById('btn-unselect-all').addEventListener('click', function() {
		chkAll().forEach(function(el) { el.checked = false; });
	});

	document.getElementById('btn-reset-export').addEventListener('click', function() {
		document.getElementById('filter_tahun').value = '<?= (int)$default_year ?>';
		document.getElementById('filter_bulan').value = '';
		document.getElementById('filter_tgl_awal').value = '';
		document.getElementById('filter_tgl_akhir').value = '';
		document.getElementById('filter_status').value = 'all';
		chkAll().forEach(function(el) { el.checked = true; });
		document.getElementById('preview-wrapper').style.display = 'none';
	});

	function validateSelection() {
		var checked = chkAll().filter(function(el) { return el.checked; });
		if (checked.length === 0) {
			if (typeof Swal !== 'undefined') {
				Swal.fire({ icon: 'warning', title: 'Pilih Jenis Persetujuan', text: 'Centang minimal 1 jenis persetujuan terlebih dahulu.' });
			} else {
				alert('Centang minimal 1 jenis persetujuan terlebih dahulu.');
			}
			return false;
		}
		var t1 = document.getElementById('filter_tgl_awal').value;
		var t2 = document.getElementById('filter_tgl_akhir').value;
		if (t1 && t2 && t1 > t2) {
			if (typeof Swal !== 'undefined') {
				Swal.fire({ icon: 'warning', title: 'Rentang Tanggal Salah', text: 'Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir.' });
			} else {
				alert('Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir.');
			}
			return false;
		}
		return true;
	}

	form.addEventListener('submit', function(e) {
		if (!validateSelection()) {
			e.preventDefault();
		}
	});

	function escHtml(s) {
		return String(s == null ? '' : s)
			.replace(/&/g, '&amp;').replace(/</g, '&lt;')
			.replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}

	function fmtNum(n) {
		var num = Number(n || 0);
		return num.toLocaleString('id-ID', { maximumFractionDigits: 0 });
	}

	function fmtDate(d) {
		if (!d || d.length < 10) return '-';
		var p = d.split('-');
		return p[2] + '/' + p[1] + '/' + p[0];
	}

	document.getElementById('btn-preview-export').addEventListener('click', function() {
		if (!validateSelection()) return;
		var btn = this;
		var origHtml = btn.innerHTML;
		btn.disabled = true;
		btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memuat...';

		var qs = new URLSearchParams(new FormData(form)).toString();
		fetch('<?= base_url("export_persetujuan/preview") ?>?' + qs, { credentials: 'same-origin' })
			.then(function(res) { return res.json(); })
			.then(function(data) {
				if (data.status !== 'success') {
					throw new Error(data.message || 'Gagal memuat preview.');
				}
				var wrap = document.getElementById('preview-wrapper');
				wrap.style.display = 'block';
				document.getElementById('preview-meta').textContent = 'Periode: ' + data.period_label + ' | Total: ' + data.total_rows + ' baris transaksi';

				var totalsHtml = [];
				Object.keys(data.totals_by_currency || {}).forEach(function(mu) {
					totalsHtml.push('Total ' + escHtml(mu) + ': ' + fmtNum(data.totals_by_currency[mu]));
				});
				document.getElementById('preview-grand-total').textContent = totalsHtml.join('  |  ');

				var pills = '';
				(data.summary || []).forEach(function(s) {
					var rpTxt = s.nominal_rp > 0 ? ' (Rp ' + fmtNum(s.nominal_rp) + ')' : '';
					pills += '<span class="summary-pill">' + escHtml(s.jenis) + ': ' + s.count + ' baris' + rpTxt + '</span>';
				});
				document.getElementById('preview-summary-pills').innerHTML = pills;

				var tbody = document.getElementById('preview-tbody');
				if (!data.sample || data.sample.length === 0) {
					tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-3">Tidak ada data pada filter yang dipilih.</td></tr>';
				} else {
					var html = '';
					data.sample.forEach(function(r, idx) {
						var badge = r.status_group === 'disetujui' ? 'badge-success' : (r.status_group === 'ditolak' ? 'badge-danger' : 'badge-info');
						var kodeCell = r.link ? '<a href="' + escHtml(r.link) + '" target="_blank">' + escHtml(r.no_surat) + '</a>' : escHtml(r.no_surat);
						html += '<tr>' +
							'<td class="text-center">' + (idx + 1) + '</td>' +
							'<td class="text-center">' + fmtDate(r.tanggal) + '</td>' +
							'<td>' + escHtml(r.jenis_label) + '</td>' +
							'<td>' + kodeCell + '</td>' +
							'<td>' + escHtml(r.pengaju) + '</td>' +
							'<td>' + escHtml(r.nama_transaksi) + '</td>' +
							'<td>' + escHtml(r.ditransaksikan) + '</td>' +
							'<td class="text-right text-nowrap">' + (r.nominal > 0 ? escHtml(r.mata_uang) + ' ' + fmtNum(r.nominal) : '-') + '</td>' +
							'<td class="text-center"><span class="badge ' + badge + '">' + escHtml(r.status) + '</span></td>' +
							'</tr>';
					});
					tbody.innerHTML = html;
				}

				document.getElementById('preview-footnote').textContent = data.total_rows > 100
					? 'Menampilkan 100 baris pertama sebagai preview. Klik "Download Excel (.xlsx)" untuk mengunduh seluruh ' + data.total_rows + ' baris lengkap dengan sheet per jenis persetujuan.'
					: 'Menampilkan seluruh ' + data.total_rows + ' baris. Klik "Download Excel (.xlsx)" untuk mencetak ke Excel.';
			})
			.catch(function(err) {
				if (typeof Swal !== 'undefined') {
					Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
				} else {
					alert(err.message);
				}
			})
			.finally(function() {
				btn.disabled = false;
				btn.innerHTML = origHtml;
			});
	});
});
</script>
