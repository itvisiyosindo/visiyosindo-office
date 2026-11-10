<header class="page-header">
	<h2><i class="fas fa-map-marker-alt"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Absensi</span></li>
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<style>
	.lr-kpi-card {
		border-radius: 10px !important;
		border: none !important;
		box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08) !important;
		transition: transform 0.15s;
	}
	.lr-kpi-card .card-body {
		background: transparent !important;
	}
	.lr-kpi-card:hover {
		transform: translateY(-2px);
	}
	.lr-bg-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; color: #fff !important; }
	.lr-bg-info    { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%) !important; color: #fff !important; }
	.lr-bg-primary { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important; color: #fff !important; }
	.lr-bg-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; color: #fff !important; }
	.lr-bg-danger  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important; color: #fff !important; }

	.foto-thumb {
		width: 54px;
		height: 54px;
		object-fit: cover;
		border-radius: 8px;
		cursor: pointer;
		border: 2px solid #e2e8f0;
		transition: transform 0.15s;
	}
	.foto-thumb:hover {
		transform: scale(1.08);
		border-color: #2563eb;
	}
	.alasan-box {
		background: #fffbeb;
		border: 1px solid #fde68a;
		border-radius: 6px;
		padding: 6px 9px;
		font-size: 12.5px;
		color: #78350f;
		max-width: 260px;
		white-space: normal;
		word-break: break-word;
	}
</style>

<!-- KPI Cards -->
<div class="row mb-3">
	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm lr-kpi-card lr-bg-warning h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold"><i class="fas fa-hourglass-half"></i> Menunggu ACC</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-pending"><?= (int)$stats['pending_all'] ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm lr-kpi-card lr-bg-info h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold"><i class="fas fa-user-cog"></i> Menunggu GA</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-pending-ga"><?= (int)$stats['pending_ga'] ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm lr-kpi-card lr-bg-primary h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold"><i class="fas fa-user-tie"></i> Menunggu Bu Yola</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-pending-yola"><?= (int)$stats['pending_yola'] ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6 col-sm-6 mb-2">
		<div class="card card-sm lr-kpi-card lr-bg-success h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold"><i class="fas fa-check-double"></i> Disetujui (ACC Lengkap)</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-approved"><?= (int)$stats['approved'] ?></div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-md-6 col-sm-6 mb-2">
		<div class="card card-sm lr-kpi-card lr-bg-danger h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold"><i class="fas fa-times-circle"></i> Ditolak</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-rejected"><?= (int)$stats['rejected'] ?></div>
			</div>
		</div>
	</div>
</div>

<!-- Info Akses Approver -->
<div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center flex-wrap">
	<div class="small">
		<i class="fas fa-shield-alt mr-1"></i>
		<b>Aturan Persetujuan:</b> Absen luar radius (kunjungan Teknisi / Marketing, dll.) membutuhkan ACC dari <b>General Affair</b> dan <b>Bu Yola</b>.
		<span class="ml-2">
			Hak ACC Anda:
			<?php if (!empty($can_acc_ga)): ?><span class="badge badge-info">General Affair</span><?php endif; ?>
			<?php if (!empty($can_acc_yola)): ?><span class="badge badge-primary">Bu Yola</span><?php endif; ?>
			<?php if (empty($can_acc_ga) && empty($can_acc_yola)): ?><span class="badge badge-secondary">Lihat Saja (Read-Only)</span><?php endif; ?>
		</span>
	</div>
	<a href="<?= base_url('absensi') ?>" class="btn btn-xs btn-outline-primary font-weight-bold mt-1 mt-md-0">
		<i class="fas fa-arrow-left"></i> Kembali ke Data Absensi
	</a>
</div>

<div class="row">
	<div class="col-12">
		<div class="card shadow-sm">
			<div class="card-body p-3">
				<div class="row align-items-end mb-3 p-2 rounded border bg-light mx-0">
					<div class="col-md-4 col-sm-6 mb-2 mb-md-0">
						<label class="small font-weight-bold text-dark mb-1">Filter Status ACC:</label>
						<select class="form-control form-control-sm" id="filter_status_lr">
							<option value="pending" selected>Menunggu ACC (GA / Bu Yola)</option>
							<option value="pending_ga">Belum ACC General Affair</option>
							<option value="pending_yola">Belum ACC Bu Yola</option>
							<option value="approved">Sudah ACC Lengkap (Diterima)</option>
							<option value="rejected">Ditolak</option>
							<option value="all">Semua Pengajuan</option>
						</select>
					</div>
					<div class="col-md-3 col-sm-6 mb-2 mb-md-0">
						<label class="small font-weight-bold text-dark mb-1">Filter Bulan:</label>
						<input type="month" class="form-control form-control-sm" id="filter_month_lr" value="">
					</div>
					<div class="col-md-5 text-md-right mt-2 mt-md-0">
						<button type="button" class="btn btn-sm btn-outline-secondary" id="btn-refresh-lr">
							<i class="fas fa-sync-alt"></i> Muat Ulang Data
						</button>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover table-sm w-100" id="table-luar-radius">
						<thead class="bg-light text-center">
							<tr>
								<th style="width: 40px;">No</th>
								<th>Waktu & Tipe</th>
								<th>Karyawan</th>
								<th>Foto</th>
								<th>Lokasi & Jarak</th>
								<th>Alasan Kunjungan</th>
								<th>ACC General Affair</th>
								<th>ACC Bu Yola</th>
								<th>Status Final</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="modal-foto-lr" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white py-2">
				<h5 class="modal-title mb-0"><i class="fas fa-camera mr-1"></i> Foto Selfie Absensi</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
			</div>
			<div class="modal-body text-center p-3">
				<img id="preview-foto-img" src="" class="img-fluid rounded shadow-sm" style="max-height: 460px;">
				<div class="mt-2 small font-weight-bold text-muted" id="preview-foto-caption"></div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var CAN_ACC_GA   = <?= !empty($can_acc_ga) ? 'true' : 'false' ?>;
	var CAN_ACC_YOLA = <?= !empty($can_acc_yola) ? 'true' : 'false' ?>;

	function escHtml(str) {
		if (!str) return '';
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function renderApproverCell(statusVal, approverName, approvedAt, role, idAbsensi, canApprove) {
		var html = '';
		if (statusVal === 1) {
			html += '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> Disetujui</span>';
			if (approverName) {
				html += '<div class="small text-muted mt-1">' + escHtml(approverName) + '<br>' + escHtml(approvedAt) + '</div>';
			}
		} else if (statusVal === 2) {
			html += '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> Ditolak</span>';
			if (approverName) {
				html += '<div class="small text-muted mt-1">' + escHtml(approverName) + '<br>' + escHtml(approvedAt) + '</div>';
			}
		} else {
			html += '<span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-clock"></i> Menunggu</span>';
		}

		if (canApprove) {
			var roleLabel = role === 'ga' ? 'GA' : 'Bu Yola';
			html += '<div class="mt-2 d-flex justify-content-center flex-wrap" style="gap:4px;">';
			if (statusVal !== 1) {
				html += '<button type="button" class="btn btn-xs btn-success btn-acc-lr" data-id="' + idAbsensi + '" data-role="' + role + '" data-decision="1"><i class="fas fa-check"></i> ACC ' + roleLabel + '</button>';
			}
			if (statusVal !== 2) {
				html += '<button type="button" class="btn btn-xs btn-outline-danger btn-acc-lr" data-id="' + idAbsensi + '" data-role="' + role + '" data-decision="2"><i class="fas fa-times"></i> Tolak</button>';
			}
			html += '</div>';
		}
		return html;
	}

	var table = $('#table-luar-radius').DataTable({
		processing: true,
		serverSide: false,
		order: [],
		ajax: {
			url: '<?= base_url("absensi/luar_radius_data") ?>',
			type: 'GET',
			data: function(d) {
				d.status = $('#filter_status_lr').val();
				d.month  = $('#filter_month_lr').val();
			},
			dataSrc: function(json) {
				if (json && json.stats) {
					$('#stat-pending').text(json.stats.pending_all);
					$('#stat-pending-ga').text(json.stats.pending_ga);
					$('#stat-pending-yola').text(json.stats.pending_yola);
					$('#stat-approved').text(json.stats.approved);
					$('#stat-rejected').text(json.stats.rejected);
				}
				return (json && json.data) ? json.data : [];
			}
		},
		columns: [
			{
				data: null,
				className: 'text-center align-middle',
				render: function(data, type, row, meta) {
					return meta.row + 1;
				}
			},
			{
				data: null,
				className: 'text-center align-middle',
				render: function(data, type, row) {
					var tipeBadge = '<span class="badge badge-info text-uppercase">' + escHtml(row.type_absen) + '</span>';
					var stBadge = '';
					if (row.status_absen === 'terlambat') {
						stBadge = '<div><span class="badge badge-danger mt-1">Terlambat</span></div>';
					} else if (row.status_absen === 'tepat_waktu') {
						stBadge = '<div><span class="badge badge-primary mt-1">Tepat Waktu</span></div>';
					}
					return '<div class="font-weight-bold">' + escHtml(row.tanggal) + '</div>' +
						'<div class="small text-muted mb-1">' + escHtml(row.waktu_absen) + ' WIB</div>' +
						tipeBadge + stBadge;
				}
			},
			{
				data: null,
				className: 'align-middle',
				render: function(data, type, row) {
					var rekapUrl = '<?= base_url("absensi/show/rekap_absensi/") ?>' + row.pengguna_id_enc;
					return '<a href="' + rekapUrl + '" target="_blank" class="font-weight-bold text-dark">' + escHtml(row.nama) + '</a>' +
						'<div class="small text-muted">' + escHtml(row.jabatan) + '</div>' +
						'<div class="small text-primary">' + escHtml(row.divisi) + '</div>';
				}
			},
			{
				data: 'file_foto',
				className: 'text-center align-middle',
				render: function(data, type, row) {
					if (!data) return '<span class="text-muted small">-</span>';
					var url = '<?= base_url("assets/img/absen/") ?>' + encodeURIComponent(data);
					return '<img src="' + url + '" class="foto-thumb btn-preview-foto" data-url="' + url + '" data-caption="' + escHtml(row.nama + ' (' + row.tanggal + ' ' + row.waktu_absen + ')') + '" alt="Foto">';
				}
			},
			{
				data: null,
				className: 'align-middle',
				render: function(data, type, row) {
					var jarakStr = row.jarak_radius !== null ? (row.jarak_radius + ' meter') : '-';
					var mapLink = (row.latitude && row.longitude)
						? '<a href="https://www.google.com/maps?q=' + encodeURIComponent(row.latitude + ',' + row.longitude) + '" target="_blank" class="btn btn-xs btn-outline-primary mt-1"><i class="fas fa-map-marked-alt"></i> Buka Peta GPS</a>'
						: '';
					return '<div class="small"><b>Cabang:</b> ' + escHtml(row.cabang_terdekat) + '</div>' +
						'<div class="small text-danger font-weight-bold">Jarak: ' + escHtml(jarakStr) + '</div>' +
						mapLink;
				}
			},
			{
				data: 'alasan_luar_radius',
				className: 'align-middle',
				render: function(data) {
					return '<div class="alasan-box">' + escHtml(data) + '</div>';
				}
			},
			{
				data: null,
				className: 'text-center align-middle',
				render: function(data, type, row) {
					return renderApproverCell(row.acc_ga, row.nama_acc_ga, row.acc_ga_at, 'ga', row.id_absensi, CAN_ACC_GA);
				}
			},
			{
				data: null,
				className: 'text-center align-middle',
				render: function(data, type, row) {
					return renderApproverCell(row.acc_yola, row.nama_acc_yola, row.acc_yola_at, 'yola', row.id_absensi, CAN_ACC_YOLA);
				}
			},
			{
				data: 'approval',
				className: 'text-center align-middle',
				render: function(data) {
					if (data === 'terima') return '<span class="badge badge-success badge-pill px-2 py-1">Diterima</span>';
					if (data === 'tolak') return '<span class="badge badge-danger badge-pill px-2 py-1">Ditolak</span>';
					return '<span class="badge badge-warning badge-pill text-dark px-2 py-1">Menunggu ACC</span>';
				}
			}
		]
	});

	$('#filter_status_lr, #filter_month_lr').change(function() {
		table.ajax.reload();
	});

	$('#btn-refresh-lr').click(function() {
		table.ajax.reload();
	});

	$(document).on('click', '.btn-preview-foto', function() {
		$('#preview-foto-img').attr('src', $(this).data('url'));
		$('#preview-foto-caption').text($(this).data('caption'));
		$('#modal-foto-lr').modal('show');
	});

	$(document).on('click', '.btn-acc-lr', function() {
		var idAbsensi = $(this).data('id');
		var role      = $(this).data('role');
		var decision  = $(this).data('decision');
		var roleLbl   = role === 'ga' ? 'General Affair' : 'Bu Yola';
		var decLbl    = decision == 1 ? 'MENYETUJUI (ACC)' : 'MENOLAK';

		Swal.fire({
			title: 'Konfirmasi ' + roleLbl,
			text: 'Anda yakin ingin ' + decLbl + ' pengajuan absen luar radius ini?',
			icon: decision == 1 ? 'question' : 'warning',
			showCancelButton: true,
			confirmButtonText: decision == 1 ? 'Ya, Setujui (ACC)' : 'Ya, Tolak',
			cancelButtonText: 'Batal',
			confirmButtonColor: decision == 1 ? '#10b981' : '#ef4444'
		}).then(function(res) {
			if (!res.value) return;

			$.ajax({
				url: '<?= base_url("absensi/acc_luar_radius") ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					id_absensi: idAbsensi,
					role: role,
					decision: decision,
					csrf_token: typeof token !== 'undefined' ? token : ''
				},
				success: function(resp) {
					if (resp && resp.status === 'success') {
						Swal.fire({
							title: 'Berhasil',
							text: resp.msg,
							icon: 'success',
							timer: 1800,
							showConfirmButton: false
						});
						table.ajax.reload(null, false);
					} else {
						Swal.fire('Gagal', (resp && resp.msg) ? resp.msg : 'Gagal memproses persetujuan.', 'error');
					}
				},
				error: function() {
					Swal.fire('Gagal', 'Terjadi kesalahan saat memproses persetujuan.', 'error');
				}
			});
		});
	});
});
</script>
