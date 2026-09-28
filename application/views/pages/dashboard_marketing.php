<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

<style>
	.marketing-panel-card {
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
	}

	.marketing-panel-card .card-body {
		padding: 18px;
	}

	.marketing-chart-title {
		font-weight: 700;
		font-size: 1.05rem;
		margin-bottom: 14px;
		color: #1f2a37;
	}

	.jadwal-calendar-card {
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
	}

	.jadwal-calendar-card .card-body {
		padding: 18px;
	}

	.jadwal-calendar-card .calendar-subtitle {
		font-size: 0.92rem;
		color: #6b7280;
	}

	#dashboard-marketing-visilab-calendar {
		min-height: 460px;
	}

	#dashboard-marketing-visilab-calendar .fc-event {
		cursor: pointer;
	}
</style>

<div class="row mb-3">
	<div class="col-lg-4 col-md-6 col-sm-12">
		<div class="card marketing-panel-card">
			<div class="card-body">
				<small><i class="fas fa-calendar"></i> Pilih Tahun:</small>
				<div class="input-group mt-2">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar"></i></span>
					</div>
					<input type="text"
						data-plugin-datepicker
						data-plugin-options='{"orientation": "bottom", "format": "yyyy", "minViewMode": "years"}'
						class="form-control"
						id="filter_year"
						placeholder="Pilih Tahun"
						required>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12 mb-4">
		<div class="card marketing-panel-card">
			<div class="card-body">
				<h3 id="judul" class="marketing-chart-title text-center">Diagram Calon Pelanggan</h3>
				<canvas id="bar" height="100"></canvas>
			</div>
		</div>
	</div>

	<div class="col-12 mb-4">
		<div class="card marketing-panel-card">
			<div class="card-body">
				<h3 id="id_funnel" class="marketing-chart-title text-center">Data Funnel</h3>
				<canvas id="bar2" height="100"></canvas>
			</div>
		</div>
	</div>

	<div class="col-12 mb-4">
		<div class="card marketing-panel-card">
			<div class="card-body">
				<h3 id="id_fpp" class="marketing-chart-title text-center">Data Permintaan Penawaran</h3>
				<canvas id="bar3" height="100"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col-12">
		<div class="card jadwal-calendar-card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<div>
						<h4 class="mb-1">Kalender Jadwal Visilab</h4>
						<p class="calendar-subtitle mb-0">Pantau jadwal Ukes & Upar yang terkait dengan aktivitas marketing.</p>
					</div>
				</div>
				<div id="dashboard-marketing-visilab-calendar"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="dashboardMarketingVisilabDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title"><i class="fas fa-calendar-check"></i> Detail Jadwal Visilab</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div><strong>Jenis Jadwal:</strong> <span id="marketingVisiJenis">-</span></div>
				<div><strong>Teknisi:</strong> <span id="marketingVisiTeknisi">-</span></div>
				<div><strong>Tanggal:</strong> <span id="marketingVisiTanggal">-</span></div>
				<div><strong>Jam:</strong> <span id="marketingVisiJam">-</span></div>
				<div><strong>Status:</strong> <span id="marketingVisiStatus">-</span></div>
				<div><strong>Pelanggan:</strong> <span id="marketingVisiPelanggan">-</span></div>
				<div><strong>Wilayah:</strong> <span id="marketingVisiWilayah">-</span></div>
				<div><strong>Provinsi:</strong> <span id="marketingVisiProvinsi">-</span></div>
				<div><strong>Kab/Kota:</strong> <span id="marketingVisiKabKota">-</span></div>
				<div><strong>Alamat:</strong> <span id="marketingVisiAlamat">-</span></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-7 mb-4">
		<div class="card marketing-panel-card h-100">
			<div class="card-body">
				<div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
					<h4 class="mb-0">Log Aktivitas Anda</h4>
					<div class="form-group mb-0" style="min-width: 220px;">
						<small>Filter By Month:</small>
						<div class="input-group mt-1">
							<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
						</div>
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
	</div>
	<div class="col-md-5 mb-4">
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
								<strong class="text-6 text-color-dark"><?= $countcalonpelanggan[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-success line-height-2 my-0">Data <strong>Calon Pelanggan &uarr;</strong></h3>

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
								<strong class="text-6 text-color-dark"><?= $countpelanggan[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Data <strong>Pelanggan &darr;</strong></h3>

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
								<strong class="text-6 text-color-dark"><?= $countfunnel[0]->total ?></strong>
							</div>
							<div class="col-6 col-md-4 border border-top-0 border-end-0 border-bottom-0 border-color-light-grey py-3">
								<h3 class="text-4-1 text-color-danger line-height-2 my-0">Data <strong>Funnel &darr;</strong></h3>

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
</div>


<div id="main-modal-hadir" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Calon Pelanggan</h5>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Pelanggan</h5>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
<script>

	document.addEventListener('DOMContentLoaded', function() {

		const judul = document.getElementById('judul');
    const idFunnel = document.getElementById('id_funnel');
    const idFpp = document.getElementById('id_fpp');


    // Set tahun saat ini sebagai nilai awal
    const tahunSekarang = new Date().getFullYear();
    judul.innerText = `Diagram Calon Pelanggan ${tahunSekarang}`;
    idFunnel.innerText = `Diagram Funnel ${tahunSekarang}`;
    idFpp.innerText = `Diagram Permintaan Penawaran ${tahunSekarang} yang Disetujui`;

		// Inisialisasi tahun default
    let tahunDipilih = new Date().getFullYear();

    // Event listener untuk perubahan tahun
    $('#filter_year').change(function () {
        tahunDipilih = $(this).val(); // Ambil tahun yang dipilih
        updateCharts(tahunDipilih); // Update chart berdasarkan tahun

				    judul.innerText = `Diagram Calon Pelanggan ${tahunDipilih}`;
            idFunnel.innerText = `Diagram Funnel ${tahunDipilih}`;
            idFpp.innerText = `Diagram Permintaan Penawaran ${tahunDipilih} yang Disetujui`;
       
    });

    // Fungsi untuk memperbarui chart
    function updateCharts(tahun) {
        myChart('bar', tahun);
        myChart2(tahun);
        myChart3(tahun);
    }

    // Panggilan awal untuk data default (tahun sekarang)
    updateCharts(tahunDipilih);




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
	})


		// Mengambil tahun saat ini
    //const tahunSekarang = new Date().getFullYear();
    // Mengupdate teks judul
    //document.getElementById('judul').innerText = `Diagram Calon Pelanggan ${tahunSekarang}`;
    //document.getElementById('id_funnel').innerText = `Diagram Funnel ${tahunSekarang}`;
    //document.getElementById('id_fpp').innerText = `Diagram Permintaan Penawaran ${tahunSekarang} yang Disetujui`;

		



		const baseUrl = "<?php echo base_url();?>";

		const myChart = (chartType, tahun) => {
    $.ajax({
        url: baseUrl + 'dashboard_marketing/chart_data',
        method: 'get',
        data: { tahun: tahun }, // Kirim parameter tahun
        dataType: 'json',
        success: data => {
            let chartX = [];
            let chartY = [];
            let chartY2 = [];
            let chartY3 = [];

            const bulanNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            data.forEach(item => {
                chartX.push(bulanNames[item.month - 1]);
                chartY.push(item['105'] || 0);
                chartY2.push(item['742'] || 0);
                chartY3.push(item['745'] || 0);
            });

            const chartData = {
                labels: chartX,
                datasets: [
                    { label: 'M Tamrin', data: chartY, backgroundColor: 'Blue', borderColor: 'Blue', borderWidth: 2 },
                    { label: 'Rizqilillah', data: chartY2, backgroundColor: 'skyblue', borderColor: 'skyblue', borderWidth: 2 },
                    { label: 'Nurdiansyah', data: chartY3, backgroundColor: 'Green', borderColor: 'Green', borderWidth: 2 }
                ]
            };

            const ctx = document.getElementById(chartType).getContext('2d');
            new Chart(ctx, { type: 'bar', data: chartData, options: { scales: { y: { beginAtZero: true } } } });
        },
        error: (xhr, status, error) => {
            console.error("Error fetching data: ", error);
        }
    });
};




// Modifikasi fungsi myChart2
const myChart2 = (tahun) => {
    $.ajax({
        url: baseUrl + 'dashboard_marketing/chart_data_funnel',
        data: { tahun: tahun }, // Kirim tahun sebagai parameter
        dataType: 'json',
        method: 'get',
        success: data => {
            let chartX = [];
            let chartY = [];
            let chartY2 = [];
            let chartY3 = [];

            const bulanNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            data.forEach(item => {
                chartX.push(bulanNames[item.month - 1]);
                chartY.push(item['105'] || 0);
                chartY2.push(item['742'] || 0);
                chartY3.push(item['745'] || 0);
            });

            const chartData = {
                labels: chartX,
                datasets: [
                    { label: 'M Tamrin', data: chartY, backgroundColor: 'Blue', borderColor: 'Blue', borderWidth: 2 },
                    { label: 'Rizqilillah', data: chartY2, backgroundColor: 'skyblue', borderColor: 'skyblue', borderWidth: 2 },
                    { label: 'Nurdiansyah', data: chartY3, backgroundColor: 'Green', borderColor: 'Green', borderWidth: 2 }
                ]
            };

            const ctx = document.getElementById('bar2').getContext('2d');
            const config = {
                type: 'bar',
                data: chartData,
                options: { scales: { y: { beginAtZero: true } } }
            };

            new Chart(ctx, config);
        },
        error: (xhr, status, error) => {
            console.error("Error fetching data: ", error);
        }
    });
};

// Modifikasi fungsi myChart3
const myChart3 = (tahun) => {
    $.ajax({
        url: baseUrl + 'dashboard_marketing/chart_data_fpp',
        data: { tahun: tahun }, // Kirim tahun sebagai parameter
        dataType: 'json',
        method: 'get',
        success: data => {
            let chartX = [];
            let chartY = [];
            let chartY2 = [];
            let chartY3 = [];

            const bulanNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            data.forEach(item => {
                chartX.push(bulanNames[item.month - 1]);
                chartY.push(item['105'] || 0);
                chartY2.push(item['742'] || 0);
                chartY3.push(item['745'] || 0);
            });

            const chartData = {
                labels: chartX,
                datasets: [
                    { label: 'M Tamrin', data: chartY, backgroundColor: 'Blue', borderColor: 'Blue', borderWidth: 2 },
                    { label: 'Rizqilillah', data: chartY2, backgroundColor: 'skyblue', borderColor: 'skyblue', borderWidth: 2 },
                    { label: 'Nurdiansyah', data: chartY3, backgroundColor: 'Green', borderColor: 'Green', borderWidth: 2 }
                ]
            };

            const ctx = document.getElementById('bar3').getContext('2d');
            const config = {
                type: 'bar',
                data: chartData,
                options: { scales: { y: { beginAtZero: true } } }
            };

            new Chart(ctx, config);
        },
        error: (xhr, status, error) => {
            console.error("Error fetching data: ", error);
        }
    });
};

	var visilabMarketingCalendarEl = document.getElementById('dashboard-marketing-visilab-calendar');
	if (visilabMarketingCalendarEl && typeof FullCalendar !== 'undefined') {
		var visilabMarketingCalendar = new FullCalendar.Calendar(visilabMarketingCalendarEl, {
			initialView: 'dayGridMonth',
			locale: 'id',
			height: 520,
			headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,dayGridWeek' },
			events: function(info, successCallback, failureCallback) {
				var viewYear = new Date((info.start.getTime() + info.end.getTime()) / 2).getFullYear();
				$.getJSON('<?= base_url('visilab_jadwal/get_events') ?>', { year: viewYear })
					.done(function(resp) { successCallback(resp); })
					.fail(function() { failureCallback(); });
			},
			eventContent: function(arg) {
				var props = arg.event.extendedProps || {};
				var wilayah = props.wilayah || 'Lainnya';
				var badgeColor = props.wilayah_badge_color || '#6c757d';
				return {
					html: '<div><span style="display:inline-block;padding:1px 6px;border-radius:999px;background:' + badgeColor + ';color:#fff;font-size:10px;font-weight:700;margin-right:4px;">' + wilayah + '</span><span>' + (arg.event.title || '') + '</span></div>'
				};
			},
			eventClick: function(info) {
				var props = info.event.extendedProps || {};
				var opt = { year: 'numeric', month: 'long', day: 'numeric' };
				var tanggalMulai = props.tanggal_mulai || (info.event.startStr || '');
				var tanggalSelesai = props.tanggal_selesai || tanggalMulai;
				var tanggal = '-';
				if (tanggalMulai) {
					var tStart = new Date(tanggalMulai + 'T00:00:00').toLocaleDateString('id-ID', opt);
					var tEnd = new Date(tanggalSelesai + 'T00:00:00').toLocaleDateString('id-ID', opt);
					tanggal = tStart === tEnd ? tStart : (tStart + ' s/d ' + tEnd);
				}
				$('#marketingVisiJenis').text(props.jenis_jadwal || '-');
				$('#marketingVisiTeknisi').text(props.teknisi_nama || (props.teknisi_id ? ('ID ' + props.teknisi_id) : '-'));
				$('#marketingVisiTanggal').text(tanggal);
				$('#marketingVisiJam').text(props.jam || '-');
				$('#marketingVisiStatus').text(props.status || '-');
				$('#marketingVisiPelanggan').text(props.lokasi_pelanggan_nama || '-');
				$('#marketingVisiWilayah').text(props.wilayah || '-');
				$('#marketingVisiProvinsi').text(props.provinsi || '-');
				$('#marketingVisiKabKota').text(props.kab_kota || '-');
				$('#marketingVisiAlamat').text(props.lokasi_alamat || '-');
				$('#dashboardMarketingVisilabDetailModal').modal('show');
			}
		});
		visilabMarketingCalendar.render();
	}



</script>