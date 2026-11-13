<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<?php if (!empty($stats) && (isAdmin() || isHrd() || isCRO() || isTeamMarketing())): ?>
<?php
    $pm_labels   = json_encode(array_map(function($r){ return isset($r->nama) ? $r->nama : 'Unknown'; }, $stats['per_marketing']));
    $pm_data     = json_encode(array_map(function($r){ return (int)$r->total; }, $stats['per_marketing']));
    $prov_labels = json_encode(array_map(function($r){ return isset($r->provinsi) ? $r->provinsi : 'Unknown'; }, $stats['per_provinsi']));
    $prov_data   = json_encode(array_map(function($r){ return (int)$r->total; }, $stats['per_provinsi']));
    $bl_labels   = json_encode(array_map(function($r){ return $r->bulan; }, $stats['per_bulan']));
    $bl_data     = json_encode(array_map(function($r){ return (int)$r->total; }, $stats['per_bulan']));
?>
<style>
	.cp-kpi-card {
		background: #ffffff;
		border-radius: 12px;
		border: 1px solid #e2e8f0;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		transition: all 0.2s ease;
		overflow: hidden;
		display: flex;
		flex-direction: column;
		height: 100%;
	}
	.cp-kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.08);
	}
	.cp-kpi-body {
		padding: 16px 18px;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.cp-kpi-icon {
		width: 46px;
		height: 46px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}
	.cp-panel-card {
		background: #ffffff;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
		overflow: hidden;
		height: 100%;
		display: flex;
		flex-direction: column;
	}
	.cp-panel-header {
		background: #f8fafc;
		border-bottom: 1px solid #e2e8f0;
		padding: 12px 18px;
		font-weight: 700;
		font-size: 13.5px;
		color: #1e293b;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.cp-panel-body {
		padding: 16px;
		flex: 1;
		position: relative;
	}
	.cp-duplikat-item {
		cursor: pointer;
		transition: background-color 0.15s;
	}
	.cp-duplikat-item:hover {
		background-color: #fef2f2 !important;
	}
</style>

<div class="row mb-4" id="dashboard-calonpelanggan">
    <!-- 4 KPI Modern Summary Cards -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="cp-kpi-card" style="border-left: 4px solid #2563eb;">
            <div class="cp-kpi-body">
                <div>
                    <div class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Calon Pelanggan</div>
                    <div class="h3 font-weight-bold text-dark mb-0 mt-1"><?= number_format($stats['total']) ?></div>
                    <div class="small text-muted mt-1"><i class="fas fa-database mr-1"></i> Data Terdaftar</div>
                </div>
                <div class="cp-kpi-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="cp-kpi-card" style="border-left: 4px solid #10b981;">
            <div class="cp-kpi-body">
                <div>
                    <div class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Tambah Bulan Ini</div>
                    <div class="h3 font-weight-bold text-success mb-0 mt-1"><?= number_format($stats['bulan_ini']) ?></div>
                    <div class="small text-muted mt-1"><i class="fas fa-calendar-check mr-1 text-success"></i> <?= date('F Y') ?></div>
                </div>
                <div class="cp-kpi-icon" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="cp-kpi-card" style="border-left: 4px solid #ef4444;">
            <div class="cp-kpi-body">
                <div>
                    <div class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Grup Nama Duplikat</div>
                    <div class="h3 font-weight-bold text-danger mb-0 mt-1"><?= number_format($stats['duplikat']) ?></div>
                    <div class="small text-danger mt-1"><i class="fas fa-exclamation-triangle mr-1"></i> Perlu Validasi</div>
                </div>
                <div class="cp-kpi-icon" style="background: #fef2f2; color: #ef4444;">
                    <i class="fas fa-clone"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="cp-kpi-card" style="border-left: 4px solid #f59e0b;">
            <div class="cp-kpi-body">
                <div>
                    <div class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Total Baris Duplikat</div>
                    <div class="h3 font-weight-bold text-warning mb-0 mt-1"><?= number_format($stats['jml_duplikat']) ?></div>
                    <div class="small text-muted mt-1"><i class="fas fa-copy mr-1 text-warning"></i> Entri Serupa</div>
                </div>
                <div class="cp-kpi-icon" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-copy"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart: Trend Bulanan (12 Bulan) -->
    <div class="col-lg-7 mb-3">
        <div class="cp-panel-card">
            <div class="cp-panel-header">
                <span><i class="fas fa-chart-line text-primary mr-1"></i> Trend Penambahan Calon Pelanggan (12 Bulan)</span>
            </div>
            <div class="cp-panel-body">
                <div style="position: relative; height: 210px; width: 100%;">
                    <canvas id="chartBulan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart: Per Marketing -->
    <div class="col-lg-5 mb-3">
        <div class="cp-panel-card">
            <div class="cp-panel-header">
                <span><i class="fas fa-user-tie text-success mr-1"></i> Distribusi Calon per Marketing</span>
            </div>
            <div class="cp-panel-body">
                <div style="position: relative; height: 210px; width: 100%;">
                    <canvas id="chartMarketing"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart: Per Provinsi -->
    <div class="col-lg-5 mb-3">
        <div class="cp-panel-card">
            <div class="cp-panel-header">
                <span><i class="fas fa-map-marked-alt text-warning mr-1"></i> Top 7 Sebaran Provinsi</span>
            </div>
            <div class="cp-panel-body">
                <div style="position: relative; height: 230px; width: 100%;">
                    <canvas id="chartProvinsi"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Nama Duplikat Interaktif -->
    <div class="col-lg-7 mb-3">
        <div class="cp-panel-card" style="border-top: 3px solid #ef4444;">
            <div class="cp-panel-header">
                <span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i> Daftar Calon Pelanggan Duplikat</span>
                <small class="text-muted">Klik nama untuk filter tabel di bawah</small>
            </div>
            <div class="cp-panel-body p-0" style="max-height: 245px; overflow-y: auto;">
                <div id="duplikat-loading" class="text-muted text-center py-4"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat data duplikat...</div>
                <table class="table table-sm table-hover mb-0 d-none" id="tbl-duplikat" style="font-size: 13px;">
                    <thead style="background: #f8fafc; position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th style="width: 45px;" class="text-center">#</th>
                            <th>Nama Customer Duplikat</th>
                            <th style="width: 90px;" class="text-center">Jumlah</th>
                            <th style="width: 80px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="duplikat-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function initCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initCharts, 100);
            return;
        }

        // 1. Chart Trend Bulanan (Line Chart)
        var ctxBulan = document.getElementById('chartBulan');
        if (ctxBulan) {
            new Chart(ctxBulan.getContext('2d'), {
                type: 'line',
                data: {
                    labels: <?= $bl_labels ?>,
                    datasets: [{
                        label: 'Calon Baru',
                        data: <?= $bl_data ?>,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: {
                        yAxes: [{
                            ticks: { beginAtZero: true, precision: 0 },
                            gridLines: { color: '#f1f5f9' }
                        }],
                        xAxes: [{
                            gridLines: { display: false }
                        }]
                    }
                }
            });
        }

        // 2. Chart Marketing (Horizontal Bar)
        var ctxMarketing = document.getElementById('chartMarketing');
        if (ctxMarketing) {
            new Chart(ctxMarketing.getContext('2d'), {
                type: 'horizontalBar',
                data: {
                    labels: <?= $pm_labels ?>,
                    datasets: [{
                        label: 'Calon Terdaftar',
                        data: <?= $pm_data ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: {
                        xAxes: [{
                            ticks: { beginAtZero: true, precision: 0 },
                            gridLines: { color: '#f1f5f9' }
                        }],
                        yAxes: [{
                            gridLines: { display: false }
                        }]
                    }
                }
            });
        }

        // 3. Chart Provinsi (Horizontal Bar)
        var ctxProv = document.getElementById('chartProvinsi');
        if (ctxProv) {
            new Chart(ctxProv.getContext('2d'), {
                type: 'horizontalBar',
                data: {
                    labels: <?= $prov_labels ?>,
                    datasets: [{
                        label: 'Jumlah Calon',
                        data: <?= $prov_data ?>,
                        backgroundColor: '#f59e0b',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: {
                        xAxes: [{
                            ticks: { beginAtZero: true, precision: 0 },
                            gridLines: { color: '#f1f5f9' }
                        }],
                        yAxes: [{
                            gridLines: { display: false }
                        }]
                    }
                }
            });
        }
    }

    initCharts();

    // Load Duplikat Data via AJAX + Click to Filter Table
    $.get('<?= site_url('calonpelanggan/getduplikat') ?>', function(res) {
        var rows = '';
        if (res && res.length > 0) {
            $.each(res, function(i, r) {
                var safeName = $('<div>').text(r.nama).html();
                var rawName  = $('<div>').text(r.nama).text();
                rows += '<tr class="cp-duplikat-item" data-nama="' + rawName + '">' +
                    '<td class="text-center text-muted">' + (i + 1) + '</td>' +
                    '<td class="font-weight-bold text-dark">' + safeName + '</td>' +
                    '<td class="text-center"><span class="badge badge-danger px-2 py-1">' + r.jumlah + ' Data</span></td>' +
                    '<td class="text-center"><button type="button" class="btn btn-xs btn-outline-danger btn-filter-duplikat" data-nama="' + rawName + '"><i class="fas fa-search"></i> Cari</button></td>' +
                    '</tr>';
            });
        } else {
            rows = '<tr><td colspan="4" class="text-center text-success py-3"><i class="fas fa-check-circle mr-1"></i> Tidak ditemukan data calon pelanggan duplikat. Bersih!</td></tr>';
        }
        $('#duplikat-body').html(rows);
        $('#tbl-duplikat').removeClass('d-none');
        $('#duplikat-loading').addClass('d-none');
    }, 'json');

    // Klik tombol cari / baris duplikat langsung memfilter DataTables di bawah
    $(document).on('click', '.cp-duplikat-item, .btn-filter-duplikat', function(e) {
        var nama = $(this).data('nama');
        if (nama && typeof table !== 'undefined') {
            table.search(nama).draw();
            $('html, body').animate({
                scrollTop: $('#kt_table_1').offset().top - 120
            }, 400);
        }
    });
});
</script>
<?php endif; ?>

<div class="row">
	<div class="col">
		<div class="card-body">
			<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO()) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Calon</a>
			<?php } ?>

			<?php if (isAdmin() || isHrd() || isCRO()) { ?>
				<a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Cetak Laporan</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> ID </th>
							<th> Kode </th>
							<th> Kode </th>
							<th> Nama Marketing</th>
							<th> Nama Calon Customer </th>
							<th> Kontak PIC </th>
							<th> Alamat </th>
							<th> Provinsi </th>
							<th> Kota </th>
							<th> Tanggal </th>
							<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || sessPenggunaId() == 755) { ?>
								<th> Ubah Status </th>
								<th> Modality </th>
							<?php } ?>
							<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || sessPenggunaId() == 755) { ?>
								<th> Aksi</th>
							<?php } ?>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="main-modal-modality" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Modality</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-modality', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idcalonpelanggan" name="idcalonpelanggan" required>
					</div>
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idpelanggan" name="idpelanggan" required>
					</div>
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="kodepelanggan" name="kodepelanggan" required>
					</div>
					<div class="form-group">
						<label class="control-label">Kategori</label>
						<select class="select-transaction input-group-sm form-control" name="kategori" id="kategori">
							<?php if ($kategorimodality != NULL): ?>
								<option value=''>— Pilih Kategori —</option>
								<?php foreach ($kategorimodality as $value): ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('kategori'); ?>
					</div>
					<div class="form-group">
						<label for="merk" class="form-control-label">Merk <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="merk" name="merk" required>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_2'>
								<thead>
									<tr>
										<th> # </th>
										<th> Kategori</th>
										<th> Nama </th>
										<th> Merk </th>
										<th> Tanggal </th>
										<th> Aksi </th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>

			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>
<div id="main-modal-pelanggan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-pelanggan', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-pelanggan-form">
					<div class="form-group">
						<div>
							<label for="kodecustomer" class="form-control-label">Kode Customer <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control col-sm-5" id="idcalon" name="idcalon" hidden />
							<input type="text" class="form-control col-sm-5" id="kodecaloncustomer2" name="kodecaloncustomer2" style="display: none" />
							<input type="text" class="form-control col-sm-5" id="kodecustomer" name="kodecustomer" readonly required />
						</div>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namapelanggan" name="namapelanggan" required>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Tanggal Registrasi <span class="text-danger">*</span> :</label>
						<input type="date" class="form-control" id="tanggalregistrasi" name="tanggalregistrasi" required>
					</div>
					<div class="form-group">
						<label for="nik" class="form-control-label">NIK <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nik" name="nik" required>
					</div>
					<div class="form-group">
						<label for="nonpwp" class="form-control-label">No. NPWP <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nonpwp" name="nonpwp" required>
					</div>
					<div class="form-group">
						<label for="namanpwp" class="form-control-label">Nama di NPWP <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namanpwp" name="namanpwp" required>
					</div>
					<div class="form-group">
						<label for="jenisfakturpajak" class="form-control-label">Jenis Faktur Pajak <span class="text-danger">*</span> :</label>
						<select class="select-transaction input-group-sm form-control" name="jenisfakturpajak" id="jenisfakturpajak">
							<?php if ($jenisfakturpajak != NULL): ?>
								<option value=''>— Pilih Jenis Faktur Pajak —</option>
								<?php foreach ($jenisfakturpajak as $value): ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('jenisfakturpajak'); ?>
					</div>
					<div class="form-group">
						<label for="syaratpembayaran" class="form-control-label">Syarat Pembayaran <span class="text-danger">*</span> :</label>
						<select class="select-transaction input-group-sm form-control" name="syaratpembayaran" id="syaratpembayaran">
							<?php if ($jenisfakturpajak != NULL): ?>
								<option value=''>— Pilih Syarat Pembayaran —</option>
								<?php foreach ($syaratpembayaran as $value): ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('syaratpembayaran'); ?>
					</div>
					<div class="form-group">
						<label for="berkasdokumenpembayaran" class="form-control-label">Berkas Dokumen Pembayaran <span class="text-danger">*<sup>(Checklist dan Masukkan Link Google Drive)</sup></span> :</label><br />
						<input type="number" class="form-control" id="jumlahberkas" name="jumlahberkas" style="display: none" value="<?= $jumlahberkaspelanggan; ?>" required>
						<div id="chkberkasdokumen" class="col-sm-10">
							<?php if ($masterberkaspelanggan != NULL): ?>
								<?php foreach ($masterberkaspelanggan as $value): ?>
									<div class="col-sm-15" id='dberkasdokumen'>
										<!-- <input type="text" class="form-control" id="id<?php echo $value->id; ?>" name="idberkas[]" value="<?php echo $value->id; ?>" style="display: none" required> -->
										<!-- <input type="checkbox" name="newsletter" value="accept" checked="checked" /> -->
										<input type="checkbox" id="chk<?php echo $value->id; ?>" name="chkberkas[]" value="0" unchecked> <?php echo $value->namaberkas; ?>
										<input type="text" class="form-control" id="link<?php echo $value->id; ?>" name="linkberkas[]" style="display: none">
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php echo form_error('masterberkaspelanggan'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="pengirimandokumen" class="form-control-label">Pengiriman Dokumen Pembayaran <span class="text-danger">*</span> :</label>
						<select class="form-control" id="pengirimandokumen" name="pengirimandokumen" required>
							<option value="">- Pilih Jenis Pengiriman Dokumen -</option>
							<option value="1">Softcopy</option>
							<option value="2">Hardcopy</option>
						</select>
					</div>
					<div class="form-group">
						<label for="statuspiutang" class="form-control-label">Status Piutang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="statuspiutang" name="statuspiutang" required>
					</div>
					<div class="form-group">
						<label for="limitpiutang" class="form-control-label">Limit Piutang <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="limitpiutang" name="limitpiutang" required>
					</div>
					<div class="form-group">
						<label for="alamatpengiriman" class="form-control-label">Alamat Pengiriman <span class="text-danger">*</span> :</label>
						<textarea name="alamatpengiriman" class="form-control" id="alamatpengiriman" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="alamatpenagihan" class="form-control-label">Alamat Penagihan <span class="text-danger">*</span> :</label>
						<textarea name="alamatpenagihan" class="form-control" id="alamatpenagihan" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="berkaslainnya" class="form-control-label">Berkas Lainnya <span class="text-danger">*<sup>(Checklist dan Masukkan Link Google Drive)</sup></span> :</label><br />
						<input type="number" class="form-control" id="jumlahberkaslainnya" name="jumlahberkaslainnya" style="display: none" value="<?= $jumlahberkaslainpelanggan; ?>" required>
						<div id="chkberkasdokumenlain" class="col-sm-10">
							<?php if ($masterberkaslainpelanggan != NULL): ?>
								<?php foreach ($masterberkaslainpelanggan as $value): ?>
									<div class="col-sm-15" id='dberkasdokumenlainnya'>
										<input type="checkbox" id="chklainnya<?php echo $value->id; ?>" name="chkberkaslainnya[]" value="0" unchecked> <?php echo $value->namaberkas; ?>
										<input type="text" class="form-control" id="linklainnya<?php echo $value->id; ?>" name="linkberkaslainnya[]" style="display: none">
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php echo form_error('masterberkaslainpelanggan'); ?>
						</div>
					</div>
					<!-- <div class="form-group">
								<label for="kota" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control" id="kota" name="kota" required>
							</div>
							<div class="form-group">
								<label for="provinsi" class="form-control-label">Provinsi <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control" id="provinsi" name="provinsi" required>
							</div> -->
					<!-- 					<div class="form-group">
								<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
								<textarea name="alamat" class="form-control" id="alamat" cols="15" rows="3"></textarea>
							</div> -->
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
					<input type="hidden" id="id_pelanggan" name="id_pelanggan"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-simpan-form-pelanggan" class="btn btn-success btn-save" data-dismiss="modal">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Calon Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">
					<div class="form-group" style="display: flex;">
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Nama Marketing</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($nama_marketing != NULL): ?>
								<option value=''>Semua Marketing</option>
								<?php foreach ($nama_marketing as $value): ?>
									<option value="<?php echo $value->pengguna_id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('nama_marketing'); ?>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<div id="main-modal-calon" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Calon Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-calon', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-calonpelanggan-form">
					<div class="form-group">
						<div>
							<label for="kodecaloncustomer" class="form-control-label">Kode Calon Customer <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control col-sm-5" id="kodecaloncustomer" name="kodecaloncustomer" readonly required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Nama Marketing <span class="text-danger">*</span> :</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($nama_marketing != NULL): ?>
								<option value="">— Pilih Nama Marketing —</option>
								<?php foreach ($nama_marketing as $value): ?>
									<option value="<?php echo $value->pengguna_id; ?>" <?php echo ($pengguna_id == $value->pengguna_id) ? 'selected' : ''; ?>>
										<?php echo $value->nama; ?>
									</option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value="">— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('namamarketing'); ?>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Calon Customer <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namacaloncustomer" name="namacaloncustomer" required>
						<div id="detect-calon-box" class="mt-2" style="display:none; font-size:12px;"></div>
					</div>
					<div class="form-group">
						<label for="tipe" class="form-control-label">Tipe Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="tipecustomer" name="tipecustomer" required>
							<option value="">- Pilih Tipe Customer -</option>
							<option value="Government">Government</option>
							<option value="Private">Private</option>
							<option value="Clinic">Clinic</option>
							<option value="Pihak Ketiga">Pihak Ketiga</option>
						</select>
					</div>
					<div class="form-group">
						<label for="kelas" class="form-control-label">Kelas Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kelascustomer" name="kelascustomer" required>
							<option value="">- Pilih Kelas Customer -</option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="D">D</option>
						</select>
					</div>
					<div class="form-group" id='dpihakketiga'>
						<label for="namapihakketiga" class="form-control-label">Nama Pihak Ketiga :</label>
						<input type="text" class="form-control" id="namapihakketiga" name="namapihakketiga">
					</div>
					<div class="form-group">
						<label class="control-label">Provinsi <span class="text-danger">*</span> :</label>
						<select class="select-transaction input-group-sm form-control" name="provinsi" id="provinsi" required>
							<?php if ($provinsi != NULL): ?>
								<option value=''>— Pilih Provinsi —</option>
								<?php foreach ($provinsi as $value): ?>
									<option value="<?php echo $value->kode; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('provinsi'); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Kabupaten/Kota <span class="text-danger">*</span> :</label>
						<select name="kota" class="form-control" id="kota" required>
							<option value=''>- Pilih Kabupaten/Kota -</option>
						</select>
						<?php echo form_error('kota'); ?>
					</div>
					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea name="alamatcaloncustomer" class="form-control" id="alamatcaloncustomer" cols="15" rows="3" required></textarea>
					</div>
					<div class="form-group">
						<div class="form-group" id="grouppic">
							<div id="dynamic_field">
								<label class="control-label col-sm-9" for="namapic">Nama PIC:</label>
								<div class="col-sm-9">
									<input hidden type="number" class="form-control" id="count" name="count">
									<input hidden type="text" class="form-control" id="picpic" name="picpic">
									<input hidden type="text" class="form-control" id="idpic" name="idpic[]">
									<input type="text" class="form-control" id="namapic" placeholder="Masukkkan Nama PIC" name="namapic[]" autocomplete="off">
								</div>
								<label class="control-label col-sm-9" for="jabatanpic">Jabatan PIC:</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="jabatanpic" placeholder="Masukkan Jabatan PIC" name="jabatanpic[]" autocomplete="off">
								</div>
								<label class="control-label col-sm-6" for="teleponpic">Telepon PIC:</label>
								<div class="col-sm-12">
									<div class="input-group mb-12">
										<input type="text" class="form-control" id="teleponpic" placeholder="Masukkan Telepon PIC" name="teleponpic[]" autocomplete="off">
										<span class="input-group-append">&nbsp &nbsp &nbsp</span>
										<span class="input-group-append">
											<button type="button" name="add" id="add" class="btn btn-success">Tambah PIC</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							</br>
							<label for="email" class="form-control-label">Email :</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="contoh@domain.com">
						</div>
						<div class="form-group">
							<label for="website" class="form-control-label">Website :</label>
							<input type="text" class="form-control" id="website" name="website" placeholder="www.contoh.com">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
					<button type="button" id="btn-simpan-form-calon" class="btn btn-success btn-save">Simpan</button>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<div id="main-modal-calonedit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Update Calon Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-calonedit', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-calonpelangganedit-form">
					<div class="form-group">
						<div hidden>
							<input type="text" class="form-control col-sm-5" id="idcalonedit" name="idcalonedit" readonly required />
						</div>
					</div>
					<div class="form-group">
						<div>
							<label for="kodecaloncustomer" class="form-control-label">Kode Calon Customer <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control col-sm-5" id="kodecaloncustomer" name="kodecaloncustomer" readonly required />
						</div>
					</div>
					<div class="form-group">
						<label for="namacaloncustomer" class="form-control-label">Nama Calon Customer <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namacaloncustomer" name="namacaloncustomer" required>
					</div>
					<div class="form-group">
						<label for="tipecustomer" class="form-control-label">Tipe Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="tipecustomer" name="tipecustomer" required>
							<option value="">- Pilih Tipe Customer -</option>
							<option value="Government">Government</option>
							<option value="Private">Private</option>
							<option value="Clinic">Clinic</option>
							<option value="Pihak Ketiga">Pihak Ketiga</option>
						</select>
					</div>
					<div class="form-group">
						<label for="kelascustomer" class="form-control-label">Kelas Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kelascustomer" name="kelascustomer" required>
							<option value="">- Pilih Kelas Customer -</option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="D">D</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Provinsi</label>
						<select class="select-transaction input-group-sm form-control" name="provinsi" id="provinsi">
							<?php if ($provinsi != NULL): ?>
								<option value=''>— Pilih Provinsi —</option>
								<?php foreach ($provinsi as $value): ?>
									<option value="<?php echo $value->kode; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('provinsi'); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Kabupaten/Kota</label>
						<select name="kota" class="form-control" id="kota">
							<option value=''>- Pilih Kabupaten/Kota -</option>
						</select>
						<?php echo form_error('kota'); ?>
					</div>
					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea name="alamatcaloncustomer" class="form-control" id="alamatcaloncustomer" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						</br>
						<label for="email" class="form-control-label">Email <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="email" name="email" required>
					</div>
					<div class="form-group">
						<label for="website" class="form-control-label">Website <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="website" name="website" required>
					</div>
					<div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
							<button type="button" id="btn-edit-form-pelanggan" class="btn btn-success" data-dismiss="modal">Update</button>
						</div>
					</div>

					<?= form_close(); ?>
				</div>
			</div>
		</div>

		<div id="main-modal-cetaklaporan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content ">
					<div class="modal-header bg-dark text-light">
						<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Approval </h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
					<div class="modal-body">
						<div class="dt-marketing-form">
							<div class="form-group" style="display: flex;">
								<div style="flex: 50%;padding: 10px;">
									<input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
								</div>
								<div style="flex: 50%;padding: 10px;">
									<input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
								</div>
							</div>

						</div>
					</div>
					<div class="modal-footer">
						<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
						<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
						<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
						<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
					</div>
					<?= form_close(); ?>
				</div>
			</div>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var i = 1;
				$('#add').click(function() {
					if ($('#namapic').val() != '') {
						i++;
						$('#dynamic_field').append('<div id="row' + i + '"><div class="form-group"><div id="dynamic_field"><label class="control-label col-sm-9" for="namapic">Nama PIC:</label><div class="col-sm-9"><input hidden type="text" class="form-control" id="picpic" name="picpic" value=""><input type="text" class="form-control" id="namapic" placeholder="Masukkkan Nama PIC" name="namapic[]" autocomplete="off"></div><label class="control-label col-sm-9" for="jabatanpic">Jabatan PIC:</label><div class="col-sm-9"><input type="text" class="form-control" id="jabatanpic" placeholder="Masukkan Jabatan PIC" name="jabatanpic[]" autocomplete="off"></div><label class="control-label col-sm-6" for="teleponpic">Telepon PIC:</label><div class="col-sm-10"><div class="input-group mb-12"><input type="text" class="form-control" id="teleponpic" placeholder="Masukkan Telepon PIC" name="teleponpic[]" autocomplete="off"><span class="input-group-append">&nbsp &nbsp &nbsp</span><span class="input-group-append"><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></span></div></div></div></div>');
						$('#count').val(i);
					};
				});

				$(document).on('click', '.btn_remove', function() {
					i--;
					$('#count').val(i);
					var button_id = $(this).attr("id");
					var res = confirm('Apakah PIC ini akan dihapus?');
					if (res == true) {
						$('#row' + button_id + '').remove();
						$('#' + button_id + '').remove();
					}
				});


				$("#dpihakketiga").hide();
				table = $('#kt_table_1').DataTable({
					responsive: false,
					processing: true,
					serverSide: true,

					ajax: {
						url: 'calonpelanggan/pagination',
						type: 'POST',
						data: function(e) {
							e.tahun = $('#tahun').val()
							e.csrf_token = token
						}
					},


					columns: [{
							"width": "1%"
						},
						{
							"width": "1%"
						},
						{
							"width": "1%"
						},
						{
							"width": "3%"
						},
						{
							"width": "10%"
						},
						{
							"width": "15%"
						},
						{
							"width": "14%"
						},
						{
							"width": "18%"
						},
						{
							"width": "8%"
						},
						{
							"width": "8%"
						},
						{
							"width": "7%"
						},
						{
							"width": "6%"
						},
						{
							"width": "6%"
						},
						{
							"width": "6%"
						},
					],

					columnDefs: [{
							'visible': false,
							'targets': [1, 2],

						}
						//{
						//    "targets": [1,2],
						//    "render" : function (data, type, row) {
						//        if(data == null) {
						//            table.columns([column_number]).visible(false);
						//        }

						//    }
						//},               
					],

				})

				table2 = $('#kt_table_2').DataTable({
					responsive: false,
					processing: true,
					serverSide: true,

					ajax: {
						url: 'calonpelanggan/paginationmodality/12k72k',
						type: 'POST',
						data: function(e) {
							e.csrf_token = token
						},

					},
				})

				function updateDatatable() {
					table.ajax.reload(null, false)
				}

				$('#btn-cetaklaporan-form').click(function() {
					$('#main-modal-marketing').modal()


				})

				$("#btn-export").click(function() {

					tglawal = $("#tglawal").val();
					tglakhir = $("#tglakhir").val();
					idmarketing = $("#namamarketing").val();
					namamarketing = $("#namamarketing option:selected").text();
					window.open("<?php echo base_url(); ?>calonpelanggan/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir) + "&idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing), "_blank");
					$('#main-modal-marketing').modal('hide')

				});





				$('#btn-show-add-form').click(function() {
					$("#grouppic").show();
					$("#detect-calon-box").hide().html('');
					$('#btn-simpan-form-calon').prop('disabled', false);
					$('#main-modal-calon #modal-form-calon')[0].reset();
					$('#main-modal-calon #kota').html('<option value="">- Pilih Kabupaten/Kota -</option>');
					$('#main-modal-calon #kodecaloncustomer').val('');
					$('#main-modal-calon #alamatcaloncustomer').val('');
					$('#main-modal-calon #modal-form-calon').attr('action', 'calonpelanggan/add');
					$('#main-modal-calon').modal('show');
				});

				$('#btn-simpan-form-calon').click(function(e) {
					var nama = $.trim($('#main-modal-calon #namacaloncustomer').val());
					var tipe = $('#main-modal-calon #tipecustomer').val();
					var kelas = $('#main-modal-calon #kelascustomer').val();
					var prov = $('#main-modal-calon #provinsi').val();
					var kota = $('#main-modal-calon #kota').val();
					var alamat = $.trim($('#main-modal-calon #alamatcaloncustomer').val());

					if (!nama) {
						Swal.fire({
							icon: 'warning',
							title: 'Bidang Belum Lengkap',
							text: 'Nama Calon Customer wajib diisi!'
						});
						$('#main-modal-calon #namacaloncustomer').focus();
						e.stopImmediatePropagation();
						return false;
					}
					if (!tipe) {
						Swal.fire({
							icon: 'warning',
							title: 'Bidang Belum Lengkap',
							text: 'Silakan pilih Tipe Customer!'
						});
						$('#main-modal-calon #tipecustomer').focus();
						e.stopImmediatePropagation();
						return false;
					}
					if (!kelas) {
						Swal.fire({
							icon: 'warning',
							title: 'Bidang Belum Lengkap',
							text: 'Silakan pilih Kelas Customer!'
						});
						$('#main-modal-calon #kelascustomer').focus();
						e.stopImmediatePropagation();
						return false;
					}
					if (!prov) {
						Swal.fire({
							icon: 'warning',
							title: 'Bidang Belum Lengkap',
							text: 'Silakan pilih Provinsi!'
						});
						$('#main-modal-calon #provinsi').focus();
						e.stopImmediatePropagation();
						return false;
					}
					if (!kota) {
						Swal.fire({
							icon: 'warning',
							title: 'Bidang Belum Lengkap',
							text: 'Silakan pilih Kabupaten/Kota!'
						});
						$('#main-modal-calon #kota').focus();
						e.stopImmediatePropagation();
						return false;
					}
					if (!alamat) {
						Swal.fire({
							icon: 'warning',
							title: 'Bidang Belum Lengkap',
							text: 'Alamat Calon Customer wajib diisi!'
						});
						$('#main-modal-calon #alamatcaloncustomer').focus();
						e.stopImmediatePropagation();
						return false;
					}

					var kodecc = prov + "/" + pad(kota, 2) + "/" + kelas + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>";
					$('#main-modal-calon #kodecaloncustomer').val(kodecc);
				});


				$("#chkberkasdokumen").on("change", function(e) {
					var pos = e.target.id.replace('chk', '')
					var chk = $("input[id='chk" + pos + "']");
					var lnk = $("input[id='link" + pos + "']");
					var apply = chk.is(':checked') ? true : false;
					lnk.hide()
					chk.val(apply)
					if (apply == true) {
						lnk.show()
					}

				})

				$("#chkberkasdokumenlain").on("change", function(e) {
					var pos = e.target.id.replace('chklainnya', '')
					var chk = $("input[id='chklainnya" + pos + "']");
					var lnk = $("input[id='linklainnya" + pos + "']");
					var apply = chk.is(':checked') ? true : false;
					lnk.hide()
					chk.val(apply)
					if (apply == true) {
						lnk.show()
					}

				})

				$("#kt_table_1").on('click', '.btn-success', function() {
					//console.log(table.row($(this).parents("tr")).data());
					var currentRow = table.row($(this).parents("tr")).data();
					var idc = currentRow[1];
					var kcc = currentRow[2].substr(0, 9) + "<?= sprintf('%03d', $kodecustomer); ?>"; //003/62/B/
					var namapel = currentRow[5];
					var dateStr = currentRow[10] ? currentRow[10].replace(/<[^>]*>?/gm, '').trim() : '';

					$('#main-modal-pelanggan').modal('show')
					$('#main-modal-pelanggan #modal-form-pelanggan').attr('action', 'calonpelanggan/addpelanggan')
					$('#kodecaloncustomer2').val(currentRow[2].trim())
					$('#idcalon').val(idc)
					$('#kodecustomer').val(kcc)
					$('#namapelanggan').val(namapel)
					$('#tanggalregistrasi').val(dateStr)

				});

				$("#kt_table_1").on('click', '.btn-dark', function() {
					idpelanggan = '';
					var currentRow = table.row($(this).parents("tr")).data();
					var idpel = currentRow[1];
					var idcalonpel = currentRow[1];
					var kodepel = currentRow[2];
					//console.log(idpel);
					//console.log(idcalonpel);
					//console.log(kodepel);
					$('#kodepelanggan').val(kodepel);
					$('#idpelanggan').val(idpel);
					$('#idcalonpelanggan').val(idcalonpel);
					table2.ajax.url('calonpelanggan/paginationmodality/' + idpel).load();


					$('#main-modal-modality #modal-form-modality').attr('action', 'calonpelanggan/addmodality')
					$('#main-modal-modality').modal()
					$('#kt_table_2').DataTable().ajax.reload();

				});
				$(document).on('click', '.btn-delete', function() {
					var currentRow = table.row($(this).parents("tr")).data();
					var idc = currentRow[1];
					var namacalon = currentRow[5];
					var par = $(this).data("id")
					$.ajax({
						type: "POST",
						url: "calonpelanggan/delete/" + par,
						async: false,
					});

				});

				$(document).on('click', '.btn-edit', function() {

					var par = $(this).data("id");
					var url = "calonpelanggan/edit/" + par

					$.ajax({
						type: "GET",
						url: url,
						success: function(response) {
							//console.log(response)  	
							if (response) {
								result = JSON.parse(response);
								$('#main-modal-calonedit #idcalonedit').val(result['id']);
								$('#main-modal-calonedit #kodecaloncustomer').val(result['kodecaloncustomer']);
								$('#main-modal-calonedit #namacaloncustomer').val(result['namacaloncustomer']);
								$('#main-modal-calonedit #tipecustomer').val(result['tipecustomer']);
								$('#main-modal-calonedit #kelascustomer').val(result['kelascustomer']);
								$('#main-modal-calonedit #provinsi').val(result['id_prov']);
								var url = "<?php echo site_url('calonpelanggan/add_ajax_kota2'); ?>/" + result['id_prov'] + "/" + result['id_kota'];
								$('#main-modal-calonedit #kota').load(url);
								$('#main-modal-calonedit #kota').val(result['id_kota']).attr('selected', 'selected');
								$('#main-modal-calonedit #alamatcaloncustomer').val(result['alamatcaloncustomer']);
								$('#main-modal-calonedit #email').val(result['email']);
								$('#main-modal-calonedit #website').val(result['website']);
								$('#main-modal-calonedit').modal();
							}
						},
						error: function(request, status, error) {
							alert(request.responseText);
						}
					});


				})

				$("#kt_table_2").on('click', '.btn-deletemodality', function(e) {
					e.preventDefault();
					swal.fire({
						title: "Apakah Yakin menghapusnya..?",
						text: "Data tidak dapat dikembalikan, jika ingin dikembalikan hubungi Administrator!",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Ya, hapus..!",
						closeOnConfirm: false
					}).then((result) => {
						if (result.isConfirmed) {
							var par = $(this).data("id");
							$.ajax({
								type: "POST",
								url: "calonpelanggan/deletemodality/" + par,
								async: false,
							});
							$('#kt_table_2').DataTable().ajax.reload();
						}
					})
				})

				$('#btn-edit-form-pelanggan').click(function() {
					$.ajax({
						type: "POST",
						url: "calonpelanggan/update",
						cache: false,
						data: {
							id: $('#main-modal-calonedit  #idcalonedit').val(),
							kodecaloncustomer: $('#main-modal-calonedit #kodecaloncustomer').val(),
							namacaloncustomer: $('#main-modal-calonedit #namacaloncustomer').val(),
							kelascustomer: $('#main-modal-calonedit #kelascustomer').val(),
							tipecustomer: $('#main-modal-calonedit #tipecustomer').val(),
							provinsi: $('#main-modal-calonedit #provinsi').val(),
							kota: $('#main-modal-calonedit #kota').val(),
							alamatcaloncustomer: $('#main-modal-calonedit #alamatcaloncustomer').val(),
							email: $('#main-modal-calonedit #email').val(),
							website: $('#main-modal-calonedit #website').val(),

						},
						success: function(result) {
							var pesan = typeof result === 'string' ? $.parseJSON(result) : result;
							var isError = (pesan.status === 'error' || pesan.status === false);
							Swal.fire({
								type: isError ? 'error' : 'success',
								icon: isError ? 'error' : 'success',
								title: pesan.msg,
								showConfirmButton: isError,
								timer: isError ? undefined : 2000
							});
							if (!isError && typeof table !== 'undefined') {
								table.ajax.reload(null, false);
							}
						},
					});
					//	window.location.reload();
				})

				var timerCekCalon = null;
				function jalankanDeteksiCalon() {
					$('#count').val(i);
					var namaInput = $.trim($('#main-modal-calon #namacaloncustomer').val());
					var alamatInput = $.trim($('#main-modal-calon #alamatcaloncustomer').val());
					var $box = $('#detect-calon-box');

					if (namaInput == '') {
						$("#main-modal-calon #grouppic").hide();
						$box.hide().html('');
						$('#btn-simpan-form-calon').prop('disabled', false);
						return;
					}

					if (namaInput.length < 3 && alamatInput.length < 5) {
						$("#main-modal-calon #grouppic").show();
						$box.hide().html('');
						$('#btn-simpan-form-calon').prop('disabled', false);
						return;
					}

					$.ajax({
						type: "POST",
						url: "calonpelanggan/cek_nama_calon",
						dataType: "json",
						data: {
							namacaloncustomer: namaInput,
							alamatcaloncustomer: alamatInput
						},
						success: function(res) {
							var html = '';
							if (res && res.exists) {
								html += '<div class="alert alert-danger p-2 mb-1"><strong><i class="fa fa-exclamation-triangle"></i> Duplikat Terdeteksi!</strong><br>' + res.msg + '</div>';
							}
							$("#main-modal-calon #grouppic").show();
							$('#btn-simpan-form-calon').prop('disabled', false);

							if (res && res.similar && res.similar.length > 0) {
								html += '<div class="alert alert-warning p-2 mb-0"><strong>Data serupa yang sudah terdaftar di sistem:</strong><ul class="pl-3 mb-0 mt-1">';
								$.each(res.similar, function(idx, item) {
									var lok = (item.kota ? item.kota : '') + (item.provinsi ? ', ' + item.provinsi : '');
									var mkt = item.namamarketing ? ' — <em>Marketing: ' + item.namamarketing + '</em>' : '';
									var almt = item.alamatcaloncustomer ? '<br><small class="text-muted">Alamat: ' + item.alamatcaloncustomer + '</small>' : '';
									html += '<li><strong>' + item.namacaloncustomer + '</strong>' + (lok ? ' (' + lok + ')' : '') + mkt + almt + '</li>';
								});
								html += '</ul></div>';
							}

							if (html !== '') {
								$box.html(html).show();
							} else {
								$box.hide().html('');
							}
						},
						error: function() {
							$("#main-modal-calon #grouppic").show();
							$('#btn-simpan-form-calon').prop('disabled', false);
						}
					});
				}

				$('#main-modal-calon #namacaloncustomer, #main-modal-calon #alamatcaloncustomer').on('input keyup change', function() {
					clearTimeout(timerCekCalon);
					timerCekCalon = setTimeout(jalankanDeteksiCalon, 250);
				});

				$('#main-modal-calon #tipecustomer').on('change', function() {
					var tipecustomer = this.value;
					if (tipecustomer == 'Pihak Ketiga') {
						$("#main-modal-calon #dpihakketiga").show();
					} else {
						$("#main-modal-calon #dpihakketiga").hide();
					}
				});

				$('#main-modal-calon #provinsi').on('change', function() {
					$('#main-modal-calon #kodecaloncustomer').val('')
					var kode = this.value;
					var url = "<?php echo site_url('calonpelanggan/add_ajax_kota'); ?>/" + kode;
					$('#main-modal-calon #kota').load(url);
					return false;
				});

				$('#main-modal-calonedit #provinsi').on('change', function() {
					var kodecc = $('#main-modal-calonedit #provinsi').val() + "/" + pad($('#main-modal-calonedit #kota').val(), 2) + "/" + $('#main-modal-calonedit #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
					$('#main-modal-calonedit #kodecaloncustomer').val(kodecc)
					var kode = this.value;
					var url = "<?php echo site_url('calonpelanggan/add_ajax_kota'); ?>/" + kode;
					$('#main-modal-calonedit #kota').load(url);
					return false;
				});



				$('#main-modal-calon #kota').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calon #kelascustomer').val() != '') {
							var kodecc = $('#main-modal-calon #provinsi').val() + "/" + pad($('#main-modal-calon #kota').val(), 2) + "/" + $('#main-modal-calon #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calon #kodecaloncustomer').val(kodecc)
						}
					}
				});
				$('#main-modal-calonedit #kota').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calonedit #kelascustomer').val() != '') {
							var kodecc = $('#main-modal-calonedit #provinsi').val() + "/" + pad($('#main-modal-calonedit #kota').val(), 2) + "/" + $('#main-modal-calonedit #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calonedit #kodecaloncustomer').val(kodecc)
						}
					}
				});

				$('#main-modal-calon #kelascustomer').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calon #kota').val() != '') {
							var kodecc = $('#main-modal-calon #provinsi').val() + "/" + pad($('#main-modal-calon #kota').val(), 2) + "/" + $('#main-modal-calon #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calon #kodecaloncustomer').val(kodecc)
						}
					}
				});
				$('#main-modal-calonedit #kelascustomer').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calonedut #kota').val() != '') {
							var kodecc = $('#main-modal-calonedit #provinsi').val() + "/" + pad($('#main-modal-calonedit #kota').val(), 2) + "/" + $('#main-modal-calonedit #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calonedit #kodecaloncustomer').val(kodecc)
						}
					}
				});



			})


			function pad(n, len) {
				let l = Math.floor(len)
				let sn = '' + n
				let snl = sn.length
				if (snl >= l) return sn
				return '0'.repeat(l - snl) + sn
			}
		</script>