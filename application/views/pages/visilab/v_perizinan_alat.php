<header class="page-header">
	<h2><i class="fas fa-file-contract"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span>Visilab</span></li>
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<!-- Widget KPI Ringkasan Kepatuhan -->
<!-- Widget KPI Ringkasan Kepatuhan -->
<style>
	.kpi-card {
		border-radius: 8px !important;
		border: none !important;
		transition: transform 0.2s, box-shadow 0.2s;
	}
	.kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
	}
	.kpi-bg-primary {
		background: linear-gradient(135deg, #0088cc 0%, #005580 100%) !important;
		color: #ffffff !important;
	}
	.kpi-bg-warning {
		background: linear-gradient(135deg, #f39c12 0%, #d68910 100%) !important;
		color: #ffffff !important;
	}
	.kpi-bg-info {
		background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%) !important;
		color: #ffffff !important;
	}
	.kpi-bg-secondary {
		background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
		color: #ffffff !important;
	}
	.kpi-bg-dark {
		background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
		color: #ffffff !important;
	}
	.kpi-bg-danger {
		background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
		color: #ffffff !important;
	}
</style>

<div class="row mb-3">
	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card kpi-bg-primary shadow-sm h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold" style="color: rgba(255,255,255,0.85);"><i class="fas fa-hospital"></i> Total Instansi</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-total"><?= $stats['total'] ?></div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card kpi-bg-warning shadow-sm h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold" style="color: rgba(255,255,255,0.9);"><i class="fas fa-file-signature"></i> Izin &le; 45 Hari</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-h45-izin"><?= $stats['h45_izin'] ?></div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card kpi-bg-info shadow-sm h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold" style="color: rgba(255,255,255,0.85);"><i class="fas fa-clipboard-check"></i> LUK &le; 1 Bulan</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-h30-luk"><?= $stats['h30_luk'] ?></div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card kpi-bg-secondary shadow-sm h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold" style="color: rgba(255,255,255,0.85);"><i class="fas fa-cogs"></i> Kalibrasi &le; 1 Bln</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-h30-kal"><?= $stats['h30_kalibrasi'] ?></div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card kpi-bg-dark shadow-sm h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold" style="color: rgba(255,255,255,0.85);"><i class="fas fa-id-badge"></i> TLD (3 Bln)</div>
				<div class="h4 font-weight-bold mb-0 text-warning font-weight-bold" id="stat-h30-tld"><?= $stats['h30_tld'] ?></div>
			</div>
		</div>
	</div>

	<div class="col-xl-2 col-md-4 col-sm-6 mb-2">
		<div class="card card-sm kpi-card kpi-bg-danger shadow-sm h-100">
			<div class="card-body p-2 text-center text-white">
				<div class="small text-uppercase font-weight-bold" style="color: rgba(255,255,255,0.85);"><i class="fas fa-exclamation-triangle"></i> Expired</div>
				<div class="h4 font-weight-bold mb-0 text-white" id="stat-expired"><?= $stats['expired'] ?></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<section class="card">
			<header class="card-header bg-light py-2">
				<div class="d-flex justify-content-between align-items-center flex-wrap">
					<div class="my-1">
						<button type="button" class="btn btn-sm btn-success" id="btn-tambah">
							<i class="fas fa-plus"></i>&nbsp; Tambah Data
						</button>
						<button type="button" class="btn btn-sm btn-info text-white ml-1" id="btn-import">
							<i class="fas fa-file-import"></i>&nbsp; Import Excel
						</button>
						<button type="button" class="btn btn-sm btn-secondary ml-1" id="btn-export">
							<i class="fas fa-file-excel"></i>&nbsp; Export Excel
						</button>
						<button type="button" class="btn btn-sm btn-warning text-dark ml-1" id="btn-cron-reminder" title="Kirim reminder ke seluruh customer & marketing yang izinnya &le; 45 hari atau LUK/Kalibrasi/TLD &le; 1 bulan">
							<i class="fab fa-whatsapp"></i>&nbsp; Cek & Kirim Semua Pengingat
						</button>
					</div>
					<div class="my-1">
						<button type="button" class="btn btn-sm btn-outline-dark" id="btn-refresh" title="Muat Ulang Data">
							<i class="fas fa-sync-alt"></i>&nbsp; Refresh
						</button>
					</div>
				</div>
			</header>

			<div class="card-body">
				<!-- Filter Bar -->
				<div class="row mb-3 bg-light p-2 rounded mx-0">
					<div class="col-md-3 col-sm-6 mb-2">
						<label class="small font-weight-bold text-muted mb-1">Status Jasa:</label>
						<select id="filter_status_jasa" class="form-control form-control-sm">
							<option value="">-- Semua Status Jasa --</option>
							<option value="Pengurusan Perizinan">Pengurusan Perizinan</option>
							<option value="Pendampingan Perizinan">Pendampingan Perizinan</option>
							<option value="Tidak Termasuk Perizinan">Tidak Termasuk Perizinan</option>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="small font-weight-bold text-muted mb-1">Status Perizinan:</label>
						<select id="filter_status_perizinan" class="form-control form-control-sm">
							<option value="">-- Semua Status Izin --</option>
							<option value="Menunggu Info Customer">Menunggu Info Customer</option>
							<option value="On Progress">On Progress</option>
							<option value="Selesai">Selesai</option>
							<option value="-">-</option>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="small font-weight-bold text-muted mb-1">Marketing:</label>
						<select id="filter_marketing" class="form-control form-control-sm">
							<option value="">-- Semua Marketing --</option>
							<?php foreach ($marketing_list as $m) : ?>
								<option value="<?= htmlspecialchars($m->nama) ?>"><?= htmlspecialchars($m->nama) ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-md-3 col-sm-6 mb-2">
						<label class="small font-weight-bold text-muted mb-1">Filter Masa Berlaku / Jatuh Tempo:</label>
						<select id="filter_masa_berlaku" class="form-control form-control-sm">
							<option value="">-- Semua Kewajiban --</option>
							<option value="h45_izin">⚠️ Izin Alat &le; 45 Hari</option>
							<option value="h30_luk">📋 LUK (Ukes) &le; 1 Bulan (30 Hari)</option>
							<option value="h30_kalibrasi">⚙️ Kalibrasi &le; 1 Bulan (30 Hari)</option>
							<option value="h30_tld">🩻 TLD (3 Bln) &le; 1 Bulan (30 Hari)</option>
							<option value="expired">🚨 Ada yang Expired / Lewat Waktu</option>
						</select>
					</div>
				</div>

				<!-- Tabel DataTables -->
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover table-sm text-center" id="table-perizinan" style="width:100%; font-size:12px;">
						<thead class="thead-dark">
							<tr>
								<th style="width: 3%;">No</th>
								<th style="width: 15%; text-align: left;">Nama Instansi</th>
								<th style="width: 11%; text-align: left;">Pihak Ketiga</th>
								<th style="width: 10%;">Jenis Alat</th>
								<th style="width: 8%;">Status Jasa</th>
								<th style="width: 8%;">Status Izin</th>
								<th style="width: 14%; text-align: left;">Progres Izin</th>
								<th style="width: 18%; text-align: left;">Jadwal Kepatuhan & Masa Berlaku</th>
								<th style="width: 8%;">Marketing</th>
								<th style="width: 13%;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<!-- Diisi via AJAX DataTables -->
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 1: FORM TAMBAH / EDIT PERIZINAN LENGKAP -->
<!-- ======================================================== -->
<div id="modal-form-perizinan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title" id="modal-form-title"><i class="fas fa-file-contract"></i>&nbsp; Form Data Perizinan & Kepatuhan Alat</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'form-perizinan', 'autocomplete' => 'off')); ?>
			<input type="hidden" name="id" id="id_perizinan" value="">
			<div class="modal-body py-3">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Nama Instansi <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control form-control-sm" id="nama_instansi" name="nama_instansi" required placeholder="Contoh: RSUD Bangil / Klinik Brawijaya">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Pihak Ketiga :</label>
							<input type="text" class="form-control form-control-sm" id="pihak_ketiga" name="pihak_ketiga" placeholder="Contoh: PT UNIOMED MEDICAL / -">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Jenis Alat <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control form-control-sm" id="jenis_alat" name="jenis_alat" list="list_jenis_alat" required placeholder="Contoh: Alerio Smart 4000">
							<datalist id="list_jenis_alat">
								<option value="Alerio Smart 4000">
								<option value="Alerio Smart 1600">
								<option value="Alerio Smart 2000">
								<option value="Alerio Smart 3000">
								<option value="X-Ray Mobile">
								<option value="C-Arm">
							</datalist>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Status Jasa <span class="text-danger">*</span> :</label>
							<select class="form-control form-control-sm" id="status_jasa" name="status_jasa" required>
								<option value="Pengurusan Perizinan">Pengurusan Perizinan</option>
								<option value="Pendampingan Perizinan">Pendampingan Perizinan</option>
								<option value="Tidak Termasuk Perizinan">Tidak Termasuk Perizinan</option>
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Status Perizinan :</label>
							<select class="form-control form-control-sm" id="status_perizinan" name="status_perizinan">
								<option value="-">-</option>
								<option value="Menunggu Info Customer">Menunggu Info Customer</option>
								<option value="On Progress">On Progress</option>
								<option value="Selesai">Selesai</option>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Catatan Tambahan :</label>
							<input type="text" class="form-control form-control-sm" id="catatan" name="catatan" placeholder="Catatan internal bila ada">
						</div>
					</div>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Progres Perizinan :</label>
					<textarea class="form-control form-control-sm" id="progres_perizinan" name="progres_perizinan" rows="2" placeholder="Catatan perkembangan izin (contoh: Dokumen sudah sesuai, menunggu SIB PPR, dll.)"></textarea>
				</div>

				<!-- Bagian Jadwal Kepatuhan & Masa Berlaku -->
				<hr class="my-2">
				<div class="h6 font-weight-bold text-danger mb-2"><i class="fas fa-calendar-check"></i> Tanggal Masa Berlaku & Siklus Kepatuhan (Untuk Notifikasi Pengingat)</div>

				<div class="row bg-light py-2 rounded border mx-0 mb-3">
					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							1. Exp Izin Alat / Batas Evaluasi (H-45 Hari):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo">
						<small class="text-muted">Diingatkan 45 hari sebelumnya untuk perpanjangan izin.</small>
					</div>

					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							2. Exp LUK / Uji Kesesuaian (H-1 Bulan):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_exp_luk" name="tgl_exp_luk">
						<small class="text-muted">Diingatkan 1 bulan sebelumnya untuk uji kesesuaian (UKES) ulang.</small>
					</div>

					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							3. Exp Kalibrasi Alat (H-1 Bulan):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_exp_kalibrasi" name="tgl_exp_kalibrasi">
						<small class="text-muted">Diingatkan 1 bulan sebelumnya untuk kalibrasi ulang alat.</small>
					</div>

					<div class="col-md-6 mb-2">
						<label class="font-weight-bold small mb-1">
							4. Evaluasi TLD Badge - Triwulanan (H-1 Bulan):
						</label>
						<input type="date" class="form-control form-control-sm" id="tgl_evaluasi_tld" name="tgl_evaluasi_tld">
						<small class="text-muted">Siklus per 3 bulan. Diingatkan 1 bulan sebelumnya.</small>
					</div>
				</div>

				<!-- Bagian Kontak & Marketing -->
				<div class="h6 font-weight-bold text-primary mb-2"><i class="fas fa-users-cog"></i> Kontak & Penanggung Jawab</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Marketing Penanggung Jawab :</label>
							<select class="form-control form-control-sm" id="marketing_nama" name="marketing_nama">
								<option value="">-- Pilih Marketing --</option>
								<option value="Muhammad Tamrin" data-nohp="081261457547">Muhammad Tamrin</option>
								<option value="Nurdiansyah" data-nohp="">Nurdiansyah</option>
								<option value="Yolanda Pratiwi" data-nohp="">Yolanda Pratiwi</option>
								<option value="Buldani" data-nohp="">Buldani</option>
								<option value="Office" data-nohp="">Office</option>
								<?php foreach ($marketing_list as $m) : ?>
									<option value="<?= htmlspecialchars($m->nama) ?>" data-nohp="<?= htmlspecialchars($m->no_hp) ?>"><?= htmlspecialchars($m->nama) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">No. WhatsApp Marketing :</label>
							<input type="text" class="form-control form-control-sm" id="marketing_nohp" name="marketing_nohp" placeholder="08xxxxxxxxxx">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">Nama PIC Instansi / Customer :</label>
							<input type="text" class="form-control form-control-sm" id="pic_instansi" name="pic_instansi" placeholder="Nama PIC Rumah Sakit / Klinik">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group mb-2">
							<label class="form-control-label font-weight-bold">No. WhatsApp PIC Customer (Untuk Reminder) :</label>
							<input type="text" class="form-control form-control-sm" id="kontak_pic" name="kontak_pic" placeholder="08xxxxxxxxxx">
						</div>
					</div>
				</div>

				<div class="form-group mb-0 bg-light p-2 rounded border">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="kirim_notif" name="kirim_notif" value="1" checked>
						<label class="custom-control-label font-weight-bold text-dark" for="kirim_notif">
							<i class="fab fa-whatsapp text-success"></i> Kirim Notifikasi WhatsApp ke Marketing & Grup VISILAB jika progres/status diperbarui
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-success btn-sm" id="btn-save-perizinan">
					<i class="fas fa-save"></i>&nbsp; Simpan Data
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 2: KIRIM NOTIFIKASI MANUAL WHATSAPP (LENGKAP) -->
<!-- ======================================================== -->
<div id="modal-notif-manual" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success text-white">
				<h5 class="modal-title"><i class="fab fa-whatsapp"></i>&nbsp; Kirim Notifikasi WhatsApp Manual</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'form-notif-manual')); ?>
			<input type="hidden" name="id" id="manual_id" value="">
			<div class="modal-body py-3">
				<!-- Header Info Instansi -->
				<div class="alert alert-light border py-2 mb-3">
					<div class="h6 font-weight-bold text-primary mb-0" id="manual_info_instansi">-</div>
					<div class="small text-muted" id="manual_info_sub">-</div>
				</div>

				<!-- Pilih Jenis Notifikasi -->
				<div class="form-group mb-3">
					<label class="form-control-label font-weight-bold">Pilih Jenis Notifikasi yang Mau Dikirim <span class="text-danger">*</span> :</label>
					<div class="row">
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_izin" name="jenis_notif" value="izin_h45" class="custom-control-input" checked>
								<label class="custom-control-label font-weight-bold" for="notif_type_izin">
									⚠️ Reminder Perizinan Alat (H-45 Hari)
								</label>
							</div>
						</div>
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_luk" name="jenis_notif" value="luk_1bln" class="custom-control-input">
								<label class="custom-control-label font-weight-bold" for="notif_type_luk">
									📋 Reminder LUK / Ukes (H-1 Bulan)
								</label>
							</div>
						</div>
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_kalibrasi" name="jenis_notif" value="kalibrasi_1bln" class="custom-control-input">
								<label class="custom-control-label font-weight-bold" for="notif_type_kalibrasi">
									⚙️ Reminder Kalibrasi Alat (H-1 Bulan)
								</label>
							</div>
						</div>
						<div class="col-md-6 mb-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_tld" name="jenis_notif" value="tld_1bln" class="custom-control-input">
								<label class="custom-control-label font-weight-bold" for="notif_type_tld">
									🩻 Reminder Evaluasi TLD (3 Bulan, H-1 Bulan)
								</label>
							</div>
						</div>
						<div class="col-md-12 mb-1 mt-1">
							<div class="custom-control custom-radio">
								<input type="radio" id="notif_type_progres" name="jenis_notif" value="progres" class="custom-control-input">
								<label class="custom-control-label font-weight-bold text-info" for="notif_type_progres">
									📝 Update Progres Perizinan (Kirim ke Marketing & Grup VISILAB)
								</label>
							</div>
						</div>
					</div>
				</div>

				<!-- Pilihan Penerima & Nomor HP -->
				<div class="card card-sm bg-light mb-3">
					<div class="card-body p-3">
						<div class="font-weight-bold small text-uppercase mb-2 text-dark">Target Penerima WhatsApp:</div>
						<div class="row">
							<div class="col-md-6 mb-2">
								<div class="custom-control custom-checkbox mb-1">
									<input type="checkbox" class="custom-control-input" id="send_customer" name="send_customer" value="1" checked>
									<label class="custom-control-label font-weight-bold" for="send_customer">
										Kirim ke PIC Instansi / Customer
									</label>
								</div>
								<input type="text" class="form-control form-control-sm" id="manual_kontak_pic" name="kontak_pic" placeholder="No. WA PIC Customer (08xxx)">
							</div>

							<div class="col-md-6 mb-2">
								<div class="custom-control custom-checkbox mb-1">
									<input type="checkbox" class="custom-control-input" id="send_marketing" name="send_marketing" value="1" checked>
									<label class="custom-control-label font-weight-bold" for="send_marketing">
										Kirim ke Marketing Penanggung Jawab
									</label>
								</div>
								<input type="text" class="form-control form-control-sm" id="manual_marketing_nohp" name="marketing_nohp" placeholder="No. WA Marketing (08xxx)">
							</div>

							<div class="col-md-12" id="box_send_visilab" style="display: none;">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="send_visilab" name="send_visilab" value="1" checked>
									<label class="custom-control-label font-weight-bold text-primary" for="send_visilab">
										<i class="fas fa-users"></i> Teruskan ke WhatsApp Grup VISILAB
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Preview & Edit Teks Pesan WhatsApp -->
				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">
						<i class="fas fa-eye"></i> Preview Pesan WhatsApp (Bisa diedit bila perlu sebelum dikirim):
					</label>
					<div class="nav nav-tabs mb-2" id="preview-tab" role="tablist">
						<a class="nav-item nav-link active py-1 px-3 small font-weight-bold" id="tab-cust" data-toggle="tab" href="#content-cust" role="tab">Pesan ke Customer</a>
						<a class="nav-item nav-link py-1 px-3 small font-weight-bold" id="tab-mkt" data-toggle="tab" href="#content-mkt" role="tab">Pesan ke Marketing & Grup</a>
					</div>
					<div class="tab-content" id="preview-tabContent">
						<div class="tab-pane fade show active" id="content-cust" role="tabpanel">
							<textarea class="form-control form-control-sm" id="manual_pesan_customer" name="pesan_customer" rows="8" style="font-family: monospace; font-size: 11px;"></textarea>
						</div>
						<div class="tab-pane fade" id="content-mkt" role="tabpanel">
							<textarea class="form-control form-control-sm" id="manual_pesan_marketing" name="pesan_marketing" rows="8" style="font-family: monospace; font-size: 11px;"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-success btn-sm" id="btn-submit-manual-notif">
					<i class="fab fa-whatsapp"></i>&nbsp; Kirim Sekarang (WhatsApp)
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 3: QUICK UPDATE PROGRES PERIZINAN -->
<!-- ======================================================== -->
<div id="modal-quick-progres" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title"><i class="fas fa-pencil-alt"></i>&nbsp; Update Cepat Progres Perizinan</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('#', array('id' => 'form-quick-progres', 'autocomplete' => 'off')); ?>
			<input type="hidden" name="id" id="quick_id" value="">
			<div class="modal-body py-3">
				<div class="alert alert-info py-2 mb-3">
					<div class="font-weight-bold" id="quick_info_instansi">-</div>
					<div class="small" id="quick_info_alat">-</div>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Status Perizinan <span class="text-danger">*</span> :</label>
					<select class="form-control form-control-sm" id="quick_status" name="status_perizinan" required>
						<option value="-">-</option>
						<option value="Menunggu Info Customer">Menunggu Info Customer</option>
						<option value="On Progress">On Progress</option>
						<option value="Selesai">Selesai</option>
					</select>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Progres Perizinan Terbaru <span class="text-danger">*</span> :</label>
					<textarea class="form-control form-control-sm" id="quick_progres" name="progres_perizinan" rows="4" required placeholder="Tuliskan perkembangan perizinan terbaru..."></textarea>
				</div>

				<div class="form-group mb-2">
					<label class="form-control-label font-weight-bold">Batas Evaluasi / Jatuh Tempo Izin :</label>
					<input type="date" class="form-control form-control-sm" id="quick_tgl_jatuh_tempo" name="tgl_jatuh_tempo">
				</div>

				<div class="form-group mb-0 bg-light p-2 rounded border">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="quick_kirim_notif" name="kirim_notif" value="1" checked>
						<label class="custom-control-label font-weight-bold text-dark" for="quick_kirim_notif">
							<i class="fab fa-whatsapp text-success"></i> Kirim Notif WhatsApp ke Marketing & Grup VISILAB
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn btn-primary btn-sm" id="btn-save-quick">
					<i class="fas fa-paper-plane"></i>&nbsp; Simpan & Kirim Notif
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 4: RIWAYAT PROGRES & LOG NOTIFIKASI -->
<!-- ======================================================== -->
<div id="modal-riwayat" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title"><i class="fas fa-history"></i>&nbsp; Riwayat Perkembangan & Notifikasi</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body py-3">
				<div class="h6 font-weight-bold text-primary mb-3" id="riwayat_instansi_title">Instansi</div>
				<div id="riwayat_timeline_content">
					<p class="text-center text-muted">Memuat riwayat...</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- ======================================================== -->
<!-- MODAL 5: IMPORT EXCEL -->
<!-- ======================================================== -->
<div id="modal-import" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title"><i class="fas fa-file-import"></i>&nbsp; Import Data Perizinan dari Excel</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open_multipart('#', array('id' => 'form-import')); ?>
			<div class="modal-body py-3">
				<p class="small text-muted mb-2">
					Pilih file Excel (.xlsx) atau CSV yang berisi format kolom: 
					<code>No, Nama Instansi, Pihak ketiga, Jenis Alat, Status Jasa, Status Perizinan, Progres Perizinan, Marketing, Batas Evaluasi</code>.
				</p>
				<div class="form-group">
					<label class="form-control-label font-weight-bold">File Excel / CSV <span class="text-danger">*</span> :</label>
					<input type="file" class="form-control-file border p-2" id="file_excel" name="file_excel" accept=".xlsx, .xls, .csv" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-info btn-sm text-white" id="btn-submit-import">
					<i class="fas fa-upload"></i>&nbsp; Mulai Import
				</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<style>
	.badge-pulse-warning {
		background-color: #ffc107;
		color: #212529;
		animation: pulseWarn 2s infinite;
	}
	@keyframes pulseWarn {
		0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
		70% { box-shadow: 0 0 0 6px rgba(255, 193, 7, 0); }
		100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
	}
	.timeline-item {
		border-left: 3px solid #007bff;
		padding-left: 15px;
		margin-bottom: 20px;
		position: relative;
	}
	.timeline-item::before {
		content: '';
		position: absolute;
		left: -7px;
		top: 0;
		width: 11px;
		height: 11px;
		border-radius: 50%;
		background-color: #007bff;
	}
	.kepatuhan-row {
		display: flex;
		justify-content: space-between;
		padding: 2px 0;
		border-bottom: 1px dashed #e9ecef;
	}
	.kepatuhan-row:last-child {
		border-bottom: none;
	}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var table;

	function formatTglBadge(tglStr, sisaHari, labelPrefix, maxDays) {
		if (!tglStr) return '<span class="text-muted small">' + labelPrefix + ': -</span>';
		var sisa = parseInt(sisaHari, 10);
		var d = new Date(tglStr);
		var tglFormatted = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + ('' + d.getFullYear()).slice(-2);

		if (isNaN(sisa)) return '<span class="small">' + labelPrefix + ': ' + tglFormatted + '</span>';

		var badgeCls = 'badge-success';
		var text = sisa + ' hr';
		if (sisa < 0) {
			badgeCls = 'badge-danger';
			text = 'Lewat ' + Math.abs(sisa) + ' hr';
		} else if (sisa <= maxDays) {
			badgeCls = 'badge-pulse-warning';
			text = sisa + ' hr ⚠️';
		}

		return '<div class="kepatuhan-row"><span class="small font-weight-bold">' + labelPrefix + ': ' + tglFormatted + '</span><span class="badge ' + badgeCls + ' ml-1">' + text + '</span></div>';
	}

	table = $('#table-perizinan').DataTable({
		responsive: false,
		processing: true,
		serverSide: false,
		order: [[0, 'asc']],
		ajax: {
			url: '<?= base_url("perizinan_visilab/data_json") ?>',
			type: 'GET',
			data: function(d) {
				d.status_jasa = $('#filter_status_jasa').val();
				d.status_perizinan = $('#filter_status_perizinan').val();
				d.marketing = $('#filter_marketing').val();
				d.masa_berlaku = $('#filter_masa_berlaku').val();
			},
			dataSrc: function(json) {
				if (json && json.stats) {
					$('#stat-total').text(json.stats.total);
					$('#stat-h45-izin').text(json.stats.h45_izin);
					$('#stat-h30-luk').text(json.stats.h30_luk);
					$('#stat-h30-kal').text(json.stats.h30_kalibrasi);
					$('#stat-h30-tld').text(json.stats.h30_tld);
					$('#stat-expired').text(json.stats.expired);
				}
				if (json && json.data) {
					return json.data;
				}
				if (Array.isArray(json)) {
					return json;
				}
				return [];
			},
			error: function(xhr, status, error) {
				console.error('DataTables Load Error:', xhr.responseText || error);
			}
		},
		columns: [
			{ 
				data: null,
				render: function(data, type, row, meta) {
					return meta.row + 1;
				}
			},
			{ 
				data: 'nama_instansi',
				className: 'text-left font-weight-bold',
				render: function(data, type, row) {
					var html = '<div>' + (data || '-') + '</div>';
					if (row.pic_instansi || row.kontak_pic) {
						html += '<small class="text-muted"><i class="fas fa-user-circle"></i> ' + (row.pic_instansi || 'PIC') + ' (' + (row.kontak_pic || '-') + ')</small>';
					}
					return html;
				}
			},
			{ 
				data: 'pihak_ketiga',
				className: 'text-left',
				render: function(data) {
					return (!data || data === '-') ? '<span class="text-muted">-</span>' : data;
				}
			},
			{ 
				data: 'jenis_alat',
				render: function(data) {
					return '<span class="badge badge-info px-2 py-1">' + (data || '-') + '</span>';
				}
			},
			{ 
				data: 'status_jasa',
				render: function(data) {
					if (!data) return '-';
					var cls = 'badge-secondary';
					if (data.indexOf('Pengurusan') !== -1) cls = 'badge-primary';
					else if (data.indexOf('Pendampingan') !== -1) cls = 'badge-success';
					else if (data.indexOf('Tidak') !== -1) cls = 'badge-danger';
					return '<span class="badge ' + cls + ' px-2 py-1">' + data + '</span>';
				}
			},
			{ 
				data: 'status_perizinan',
				render: function(data) {
					if (!data || data === '-') return '<span class="badge badge-light border">-</span>';
					var cls = 'badge-secondary';
					if (data === 'Selesai') cls = 'badge-success';
					else if (data === 'On Progress') cls = 'badge-warning text-dark';
					else if (data === 'Menunggu Info Customer') cls = 'badge-info';
					return '<span class="badge ' + cls + ' px-2 py-1">' + data + '</span>';
				}
			},
			{ 
				data: 'progres_perizinan',
				className: 'text-left',
				render: function(data, type, row) {
					var text = data ? data.replace(/\n/g, '<br>') : '<span class="text-muted">-</span>';
					var btnQuick = '<div class="mt-1"><button type="button" class="btn btn-xs btn-outline-primary btn-quick-progres" data-id="' + row.id + '"><i class="fas fa-pencil-alt"></i> Update Progres</button></div>';
					return '<div>' + text + '</div>' + btnQuick;
				}
			},
			{ 
				data: null,
				className: 'text-left p-1',
				render: function(data, type, row) {
					var rowIzin = formatTglBadge(row.tgl_jatuh_tempo, row.sisa_hari_izin, 'Izin (H-45)', 45);
					var rowLuk  = formatTglBadge(row.tgl_exp_luk, row.sisa_hari_luk, 'LUK (1 Bln)', 30);
					var rowKal  = formatTglBadge(row.tgl_exp_kalibrasi, row.sisa_hari_kalibrasi, 'Kalibrasi', 30);
					var rowTld  = formatTglBadge(row.tgl_evaluasi_tld, row.sisa_hari_tld, 'TLD (3 Bln)', 30);

					return '<div class="bg-white p-1 rounded border">' + rowIzin + rowLuk + rowKal + rowTld + '</div>';
				}
			},
			{ 
				data: 'marketing_nama',
				render: function(data, type, row) {
					if (!data || data === '-') return '<span class="text-muted">-</span>';
					var html = '<span class="badge badge-light border font-weight-bold">' + data + '</span>';
					if (row.marketing_nohp) {
						html += '<br><small class="text-muted">' + row.marketing_nohp + '</small>';
					}
					return html;
				}
			},
			{ 
				data: null,
				orderable: false,
				render: function(data, type, row) {
					var btnManualWa = '<button type="button" class="btn btn-sm btn-success btn-open-manual-notif mr-1 mb-1 font-weight-bold" data-id="' + row.id + '" title="Kirim Notifikasi WhatsApp Manual"><i class="fab fa-whatsapp"></i> Kirim Notif</button>';
					var btnEdit = '<button type="button" class="btn btn-sm btn-info btn-edit mr-1 mb-1 text-white" data-id="' + row.id + '" title="Edit Data Lengkap"><i class="fas fa-edit"></i></button>';
					var btnHist = '<button type="button" class="btn btn-sm btn-secondary btn-history mr-1 mb-1" data-id="' + row.id + '" title="Lihat Riwayat"><i class="fas fa-history"></i></button>';
					var btnDel = '<button type="button" class="btn btn-sm btn-danger btn-delete mb-1" data-id="' + row.id + '" title="Hapus"><i class="fas fa-trash"></i></button>';

					return '<div class="d-flex flex-wrap justify-content-center">' + btnManualWa + btnEdit + btnHist + btnDel + '</div>';
				}
			}
		]
	});

	// Filter trigger
	$('#filter_status_jasa, #filter_status_perizinan, #filter_marketing, #filter_masa_berlaku').change(function() {
		table.ajax.reload();
	});

	$('#btn-refresh').click(function() {
		table.ajax.reload();
	});

	$('#marketing_nama').change(function() {
		var selected = $(this).find('option:selected');
		var nohp = selected.data('nohp');
		if (nohp) {
			$('#marketing_nohp').val(nohp);
		}
	});

	// Buka modal tambah
	$('#btn-tambah').click(function() {
		$('#form-perizinan')[0].reset();
		$('#id_perizinan').val('');
		$('#modal-form-title').html('<i class="fas fa-file-contract"></i>&nbsp; Tambah Data Perizinan & Kepatuhan Baru');
		$('#modal-form-perizinan').modal('show');
	});

	// Submit Form Tambah / Edit
	$('#form-perizinan').submit(function(e) {
		e.preventDefault();
		var btn = $('#btn-save-perizinan');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/save") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fas fa-save"></i>&nbsp; Simpan Data');
				if (res.status) {
					$('#modal-form-perizinan').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: res.message,
						timer: 1800,
						showConfirmButton: false
					});
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'error');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fas fa-save"></i>&nbsp; Simpan Data');
				Swal.fire('Error', 'Terjadi kesalahan sistem saat menyimpan data.', 'error');
			}
		});
	});

	// Edit button
	$('#table-perizinan').on('click', '.btn-edit', function() {
		var id = $(this).data('id');
		$.ajax({
			url: '<?= base_url("perizinan_visilab/detail/") ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data) {
					var d = res.data;
					$('#id_perizinan').val(d.id);
					$('#nama_instansi').val(d.nama_instansi);
					$('#pihak_ketiga').val(d.pihak_ketiga);
					$('#jenis_alat').val(d.jenis_alat);
					$('#status_jasa').val(d.status_jasa);
					$('#status_perizinan').val(d.status_perizinan);
					$('#progres_perizinan').val(d.progres_perizinan);
					$('#marketing_nama').val(d.marketing_nama);
					$('#marketing_nohp').val(d.marketing_nohp);
					$('#pic_instansi').val(d.pic_instansi);
					$('#kontak_pic').val(d.kontak_pic);
					$('#email_pic').val(d.email_pic);
					$('#tgl_jatuh_tempo').val(d.tgl_jatuh_tempo);
					$('#tgl_exp_luk').val(d.tgl_exp_luk);
					$('#tgl_exp_kalibrasi').val(d.tgl_exp_kalibrasi);
					$('#tgl_evaluasi_tld').val(d.tgl_evaluasi_tld);
					$('#catatan').val(d.catatan);

					$('#modal-form-title').html('<i class="fas fa-edit"></i>&nbsp; Edit Data Perizinan: ' + d.nama_instansi);
					$('#modal-form-perizinan').modal('show');
				} else {
					Swal.fire('Error', res.message || 'Gagal mengambil data', 'error');
				}
			}
		});
	});

	// ==========================================
	// MODAL NOTIFIKASI MANUAL WHATSAPP
	// ==========================================
	$('#table-perizinan').on('click', '.btn-open-manual-notif', function() {
		var id = $(this).data('id');
		$('#manual_id').val(id);
		$('input[name="jenis_notif"][value="izin_h45"]').prop('checked', true);
		loadPreviewNotif(id, 'izin_h45');
		$('#modal-notif-manual').modal('show');
	});

	$('input[name="jenis_notif"]').change(function() {
		var id = $('#manual_id').val();
		var jenis = $(this).val();

		if (jenis === 'progres') {
			$('#box_send_visilab').slideDown();
		} else {
			$('#box_send_visilab').slideUp();
		}

		loadPreviewNotif(id, jenis);
	});

	function loadPreviewNotif(id, jenis) {
		$('#manual_pesan_customer').val('Memuat format pesan...');
		$('#manual_pesan_marketing').val('Memuat format pesan...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/preview_notif") ?>',
			type: 'GET',
			data: { id: id, jenis: jenis },
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data) {
					var d = res.data;
					$('#manual_info_instansi').html('<i class="fas fa-hospital"></i> ' + d.nama_instansi + ' (' + (d.pihak_ketiga || '-') + ')');
					$('#manual_info_sub').text('Alat: ' + d.jenis_alat + ' | PIC: ' + (d.pic_instansi || '-') + ' | Marketing: ' + (d.marketing_nama || '-'));
					$('#manual_kontak_pic').val(d.kontak_pic || '');
					$('#manual_marketing_nohp').val(d.marketing_nohp || '');
					$('#manual_pesan_customer').val(res.pesan_customer);
					$('#manual_pesan_marketing').val(res.pesan_marketing);
				}
			}
		});
	}

	// Submit Kirim Notifikasi Manual
	$('#form-notif-manual').submit(function(e) {
		e.preventDefault();
		var btn = $('#btn-submit-manual-notif');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim WhatsApp...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/kirim_notif_manual") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fab fa-whatsapp"></i>&nbsp; Kirim Sekarang (WhatsApp)');
				if (res.status) {
					$('#modal-notif-manual').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Notifikasi Terkirim!',
						text: res.message
					});
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'warning');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fab fa-whatsapp"></i>&nbsp; Kirim Sekarang (WhatsApp)');
				Swal.fire('Error', 'Terjadi kesalahan sistem saat mengirim WA.', 'error');
			}
		});
	});

	// Quick update progres modal
	$('#table-perizinan').on('click', '.btn-quick-progres', function() {
		var id = $(this).data('id');
		$.ajax({
			url: '<?= base_url("perizinan_visilab/detail/") ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data) {
					var d = res.data;
					$('#quick_id').val(d.id);
					$('#quick_info_instansi').text(d.nama_instansi + ' (' + (d.pihak_ketiga || '-') + ')');
					$('#quick_info_alat').text('Alat: ' + d.jenis_alat + ' | Marketing: ' + (d.marketing_nama || '-'));
					$('#quick_status').val(d.status_perizinan || '-');
					$('#quick_progres').val(d.progres_perizinan || '');
					$('#quick_tgl_jatuh_tempo').val(d.tgl_jatuh_tempo || '');
					$('#modal-quick-progres').modal('show');
				}
			}
		});
	});

	$('#form-quick-progres').submit(function(e) {
		e.preventDefault();
		var btn = $('#btn-save-quick');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/update_progres") ?>',
			type: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>&nbsp; Simpan & Kirim Notif');
				if (res.status) {
					$('#modal-quick-progres').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Tersimpan & Notifikasi Terkirim',
						text: res.message,
						timer: 2000,
						showConfirmButton: false
					});
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'error');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>&nbsp; Simpan & Kirim Notif');
				Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
			}
		});
	});

	// Riwayat button
	$('#table-perizinan').on('click', '.btn-history', function() {
		var id = $(this).data('id');
		$('#riwayat_timeline_content').html('<p class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Memuat riwayat...</p>');
		$('#modal-riwayat').modal('show');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/riwayat/") ?>' + id,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				if (res.status && res.data && res.data.length > 0) {
					var html = '';
					res.data.forEach(function(item) {
						var notifBadge = '';
						if (item.jenis_notif) {
							notifBadge = '<span class="badge badge-primary mr-1">' + item.jenis_notif + '</span>';
						}
						html += '<div class="timeline-item">';
						html += '<div class="d-flex justify-content-between font-weight-bold text-dark">';
						html += '<span>' + notifBadge + 'Status: <span class="badge badge-info">' + (item.status_baru || '-') + '</span></span>';
						html += '<small class="text-muted">' + item.created_at + '</small>';
						html += '</div>';
						html += '<div class="mt-1 font-italic text-secondary">"' + (item.progres_baru ? item.progres_baru.replace(/\n/g, '<br>') : '-') + '"</div>';
						if (item.keterangan) {
							html += '<div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> ' + item.keterangan + '</div>';
						}
						html += '<small class="text-muted"><i class="fas fa-user-edit"></i> Petugas: ' + (item.created_by_nama || 'System') + '</small>';
						html += '</div>';
					});
					$('#riwayat_timeline_content').html(html);
				} else {
					$('#riwayat_timeline_content').html('<p class="text-center text-muted">Belum ada riwayat untuk perizinan ini.</p>');
				}
			}
		});
	});

	// Delete button
	$('#table-perizinan').on('click', '.btn-delete', function() {
		var id = $(this).data('id');
		Swal.fire({
			title: 'Hapus Data Perizinan?',
			text: 'Data perizinan dan seluruh riwayatnya akan dihapus permanen!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value || result.isConfirmed) {
				$.ajax({
					url: '<?= base_url("perizinan_visilab/delete/") ?>' + id,
					type: 'POST',
					dataType: 'json',
					success: function(res) {
						if (res.status) {
							Swal.fire({
								icon: 'success',
								title: 'Dihapus!',
								text: res.message,
								timer: 1500,
								showConfirmButton: false
							});
							table.ajax.reload(null, false);
						} else {
							Swal.fire('Gagal', res.message, 'error');
						}
					}
				});
			}
		});
	});

	// Cron reminder massal
	$('#btn-cron-reminder').click(function() {
		Swal.fire({
			title: 'Jalankan Pengingat Otomatis?',
			text: 'Sistem akan memeriksa seluruh kewajiban kepatuhan (Izin H-45, LUK 1 Bln, Kalibrasi 1 Bln, TLD 1 Bln) dan mengirimkan WhatsApp ke PIC Customer serta Marketing.',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, Jalankan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value || result.isConfirmed) {
				Swal.fire({
					title: 'Memproses Pengingat...',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: '<?= base_url("perizinan_visilab/cron_reminder_all/vysi_medikal_2026") ?>',
					type: 'GET',
					dataType: 'json',
					success: function(res) {
						Swal.fire('Selesai', res.message, 'info');
						table.ajax.reload(null, false);
					},
					error: function() {
						Swal.fire('Error', 'Gagal menjalankan cron reminder.', 'error');
					}
				});
			}
		});
	});

	// Export Excel
	$('#btn-export').click(function() {
		var q = $.param({
			status_jasa: $('#filter_status_jasa').val(),
			status_perizinan: $('#filter_status_perizinan').val(),
			marketing: $('#filter_marketing').val(),
			masa_berlaku: $('#filter_masa_berlaku').val()
		});
		window.open('<?= base_url("perizinan_visilab/export_excel?") ?>' + q, '_blank');
	});

	// Import Excel
	$('#btn-import').click(function() {
		$('#form-import')[0].reset();
		$('#modal-import').modal('show');
	});

	$('#form-import').submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		var btn = $('#btn-submit-import');
		btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengimpor...');

		$.ajax({
			url: '<?= base_url("perizinan_visilab/import_excel") ?>',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(res) {
				btn.prop('disabled', false).html('<i class="fas fa-upload"></i>&nbsp; Mulai Import');
				if (res.status) {
					$('#modal-import').modal('hide');
					Swal.fire('Berhasil!', res.message, 'success');
					table.ajax.reload(null, false);
				} else {
					Swal.fire('Gagal', res.message, 'error');
				}
			},
			error: function() {
				btn.prop('disabled', false).html('<i class="fas fa-upload"></i>&nbsp; Mulai Import');
				Swal.fire('Error', 'Terjadi kesalahan sistem saat mengimpor Excel.', 'error');
			}
		});
	});
});
</script>
