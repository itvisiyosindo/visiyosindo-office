<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<style>
	/* Calendar Container Styles */
	.calendar-container {
		background: #fff;
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		padding: 20px;
		margin-bottom: 20px;
	}

	.calendar-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 15px;
		padding-bottom: 15px;
		border-bottom: 2px solid #f0f0f0;
	}

	.calendar-title {
		font-size: 1.25rem;
		font-weight: 600;
		color: #333;
		margin: 0;
	}

	.calendar-legend {
		display: flex;
		flex-wrap: wrap;
		gap: 15px;
	}

	.legend-item {
		display: flex;
		align-items: center;
		font-size: 12px;
	}

	.legend-color {
		width: 14px;
		height: 14px;
		border-radius: 3px;
		margin-right: 6px;
	}

	/* FullCalendar Custom Styles */
	#calendar {
		min-height: 600px;
	}

	.fc-event {
		cursor: pointer;
		padding: 2px 5px;
		font-size: 11px;
		border-radius: 3px;
	}

	.fc-daygrid-event {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.fc-toolbar-title {
		font-size: 1.2rem !important;
		font-weight: 600;
	}

	.fc-button-primary {
		background-color: #4e73df !important;
		border-color: #4e73df !important;
	}

	.fc-button-primary:hover {
		background-color: #2e59d9 !important;
		border-color: #2653d4 !important;
	}

	.fc-button-primary:disabled {
		background-color: #4e73df !important;
		border-color: #4e73df !important;
	}

	.fc-day-today {
		background-color: #fff3cd !important;
	}

	/* Modal Styles */
	.cuti-detail-modal .modal-header {
		background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
		color: white;
	}

	.cuti-detail-modal .detail-row {
		display: flex;
		padding: 8px 0;
		border-bottom: 1px solid #f0f0f0;
	}

	.cuti-detail-modal .detail-label {
		font-weight: 600;
		width: 130px;
		color: #555;
	}

	.cuti-detail-modal .detail-value {
		flex: 1;
		color: #333;
	}

	.cuti-detail-modal .status-badge {
		padding: 4px 10px;
		border-radius: 15px;
		font-size: 12px;
		font-weight: 500;
	}

	/* Responsive */
	@media (max-width: 768px) {
		.calendar-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 10px;
		}

		.calendar-legend {
			justify-content: flex-start;
		}

		#calendar {
			min-height: 400px;
		}
	}
</style>

<div class="row">
	<div class="col-12">
		<!-- Calendar Section -->
		<div class="calendar-container">
			<div class="calendar-header">
				<h5 class="calendar-title"><i class="fas fa-calendar-alt text-primary"></i> Kalender Cuti Tahunan Karyawan (SGM)</h5>
				<div class="calendar-legend">
					<div class="legend-item">
						<span class="legend-color" style="background-color: #ffc107;"></span>
						<span>Baru Diajukan</span>
					</div>
					<div class="legend-item">
						<span class="legend-color" style="background-color: #17a2b8;"></span>
						<span>Disetujui GA</span>
					</div>
					<div class="legend-item">
						<span class="legend-color" style="background-color: #28a745;"></span>
						<span>Disetujui</span>
					</div>
					<div class="legend-item">
						<span class="legend-color" style="background-color: #dc3545;"></span>
						<span>Ditolak</span>
					</div>
				</div>
			</div>
			<div id="calendar"></div>
		</div>

		<!-- Data Table Section -->
		<div class="card-body">
			<h5 class="mb-3"><i class="fas fa-list text-primary"></i> Daftar Persetujuan Cuti Tahunan SGM</h5>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> No</th>
							<th> Nama</th>
							<th> Jabatan</th>
							<th> Keperluan</th>
							<th> Tanggal</th>
							<th> Total Hari</th>
							<th> Status</th>
							<th> Aksi</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal Detail Cuti -->
<div class="modal fade cuti-detail-modal" id="modalDetailCuti" tabindex="-1" role="dialog" aria-labelledby="modalDetailCutiLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailCutiLabel"><i class="fas fa-info-circle"></i> Detail Cuti</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="detail-row">
					<span class="detail-label">Kode Cuti</span>
					<span class="detail-value" id="modal-kode-cuti">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Nama</span>
					<span class="detail-value" id="modal-nama">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Perusahaan</span>
					<span class="detail-value" id="modal-perusahaan">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Jabatan</span>
					<span class="detail-value" id="modal-jabatan">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Divisi</span>
					<span class="detail-value" id="modal-divisi">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Tanggal Cuti</span>
					<span class="detail-value" id="modal-tanggal">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Total Hari</span>
					<span class="detail-value" id="modal-total">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Alasan</span>
					<span class="detail-value" id="modal-alasan">-</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Status</span>
					<span class="detail-value" id="modal-status">-</span>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Initialize DataTable
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'surat_part_two/pagination/list_cuti_sgm',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
				className: 'text-center'
			}]
		});

		// Initialize FullCalendar
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			locale: 'id',
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,dayGridWeek'
			},
			buttonText: {
				today: 'Hari Ini',
				month: 'Bulan',
				week: 'Minggu'
			},
			height: 'auto',
			eventContent: function(arg) {
				var is_sgm = arg.event.extendedProps.is_sgm;
				var badgeHtml = is_sgm ?
					'<span class="badge badge-success" style="margin-right: 4px; font-size: 9px; padding: 1px 3px; vertical-align: middle; color: #fff; background-color: #28a745;">SGM</span>' :
					'<span class="badge badge-primary" style="margin-right: 4px; font-size: 9px; padding: 1px 3px; vertical-align: middle; color: #fff; background-color: #007bff;">VYM</span>';
				return {
					html: '<div style="display: flex; align-items: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' +
						badgeHtml +
						'<span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500;">' + arg.event.title + '</span>' +
						'</div>'
				};
			},
			events: function(info, successCallback, failureCallback) {
				var viewYear = new Date((info.start.getTime() + info.end.getTime()) / 2).getFullYear();

				$.ajax({
					url: '<?= base_url() ?>surat_part_two/getCalendarCuti',
					type: 'GET',
					data: {
						year: viewYear
					},
					dataType: 'json',
					success: function(response) {
						if (Array.isArray(response)) {
							// Filter events to only show SGM leaves on the SGM page
							var sgmEvents = response.filter(function(event) {
								return event.extendedProps && event.extendedProps.is_sgm === true;
							});
							successCallback(sgmEvents);
						} else {
							console.error('Invalid response format:', response);
							successCallback([]);
						}
					},
					error: function(xhr, status, error) {
						console.error('Calendar API Error:', status, error);
						failureCallback();
					}
				});
			},
			eventClick: function(info) {
				var props = info.event.extendedProps;

				$('#modal-kode-cuti').text(props.kode_cuti || '-');
				$('#modal-nama').text(props.nama_karyawan || '-');

				// Set Perusahaan badge (VYM = Blue, SGM = Green)
				if (props.is_sgm) {
					$('#modal-perusahaan').html('<span class="badge badge-success">SGM</span>');
				} else {
					$('#modal-perusahaan').html('<span class="badge badge-primary">VYM</span>');
				}

				$('#modal-jabatan').text(props.jabatan || '-');
				$('#modal-divisi').text(props.divisi || '-');
				$('#modal-tanggal').text(props.tgl_awal + ' s/d ' + props.tgl_akhir);
				$('#modal-total').text(props.total_hari + ' Hari');
				$('#modal-alasan').text(props.alasan || '-');

				// Set status with color
				var statusClass = 'badge-secondary';
				if (props.status_code == 0) statusClass = 'badge-warning';
				else if (props.status_code == 1) statusClass = 'badge-info';
				else if (props.status_code == 2) statusClass = 'badge-success'; // SGM fully approved is status 2
				else if (props.status_code == 6 || props.status_code == 7) statusClass = 'badge-danger';

				$('#modal-status').html('<span class="badge ' + statusClass + '">' + props.status + '</span>');

				$('#modalDetailCuti').modal('show');
			},
			eventDidMount: function(info) {
				// Add tooltip
				$(info.el).attr('title', info.event.extendedProps.nama_karyawan + ' (' + info.event.extendedProps.total_hari + ' hari)');
			},
			loading: function(isLoading) {
				if (isLoading) {
					$('#calendar').css('opacity', '0.6');
				} else {
					$('#calendar').css('opacity', '1');
				}
			}
		});

		calendar.render();
	});

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>