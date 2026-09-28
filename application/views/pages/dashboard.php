<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>

<style>
	.big-icon {
		font-size: 45px;
		/* Sesuaikan ukuran sesuai kebutuhan */
	}

	.dashboard-calendar-panel {
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
	}

	.dashboard-calendar-panel .card-body {
		padding: 18px;
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

	#dashboard-visilab-calendar {
		min-height: 460px;
	}

	#dashboard-visilab-calendar .fc-event {
		cursor: pointer;
	}
</style>

<div class="row">

	<?php if (sessPenggunaId() == 1 || sessPenggunaId() == 69 || sessPenggunaId() == 58 || sessPenggunaId() == 54) { ?>

		<?php if ($ulangtahun[0]->total != 0) { ?>
			<div class="col-xl-4 col-sm-6 col-12 mb-2">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h5 class="text-3-1 text-color-success line-height-2 my-0"> <strong>Happy Birthday</strong></h5>
							</div>
							<?php foreach ($daftar_ulangtahun as $row) { ?>
								<h4 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $row->nama ?></strong></h4>
							<?php } ?>

							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-cake icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="col-xl-2 col-sm-6 col-12 mb-2">
			<a href="javascript:;" id="btn-show-hadir">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $present[0]->total ?></strong></h3>
								<h6 class="text-3-1 text-color-success line-height-2 my-0">Total <strong>Hadir</strong></h6>
							</div>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-xl-2 col-sm-6 col-12 mb-2">
			<a href="javascript:;" id="btn-show-terlambat">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $terlambat[0]->total ?></strong></h3>
								<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Terlambat</strong></h6>
							</div>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-x icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
									<line x1="12" y1="1" x2="12" y2="23"></line>
									<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</a>
		</div>

		<?php if ($istirahat[0]->total != 0) { ?>
			<div class="col-xl-2 col-sm-6 col-12 mb-2">
				<a href="javascript:;" id="btn-show-istirahat">
					<div class="card board1 fill">
						<div class="card-body">
							<div class="dash-widget-header">
								<div>
									<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $istirahat[0]->total ?></strong></h3>
									<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Absen Istirahat &darr;</strong></h6>
								</div>
								<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
										</path>
										<polyline points="14 2 14 8 20 8"></polyline>
										<line x1="12" y1="18" x2="12" y2="12"></line>
										<line x1="9" y1="15" x2="15" y2="15"></line>
										</svg>
									</span> </div>
							</div>
						</div>
					</div>
				</a>
			</div>


			<div class="col-xl-2 col-sm-6 col-12 mb-2">
				<a href="javascript:;" id="btn-show-noistirahat">
					<div class="card board1 fill">
						<div class="card-body">
							<div class="dash-widget-header">
								<div>
									<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $noistirahat ?></strong></h3>
									<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Tidak Absen Istirahat &darr;</strong></h6>
								</div>
								<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
										</path>
										<polyline points="14 2 14 8 20 8"></polyline>
										<line x1="12" y1="18" x2="12" y2="12"></line>
										<line x1="9" y1="15" x2="15" y2="15"></line>
										</svg>
									</span> </div>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php } ?>


		<div class="col-xl-2 col-sm-6 col-12 mb-2">
			<a href="javascript:;" id="btn-show-izin">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $izin[0]->total ?></strong></h3>
								<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Izin</strong></h6>
							</div>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-pin icon icon-inline icon-md bg-primary rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-xl-2 col-sm-6 col-12 mb-2">
			<a href="javascript:;" id="btn-show-cuti">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $cuti[0]->total ?></strong></h3>
								<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Cuti &darr;</strong></h6>
							</div>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-circle icon icon-inline icon-md bg-info rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-xl-2 col-sm-6 col-12 mb-2">
			<a href="javascript:;" id="btn-show-sakit">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $sakit[0]->total ?></strong></h3>
								<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Sakit &darr;</strong></h6>
							</div>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-minus icon icon-inline icon-md bg-warning rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-xl-2 col-sm-6 col-12 mb-2">
			<a href="javascript:;" id="btn-show-pulang">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $pulang[0]->total ?></strong></h3>
								<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Pulang &darr;</strong></h6>
							</div>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-minus icon icon-inline icon-md bg-danger rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-xl-2 col-sm-6 col-12 mb-2">
			<div class="card board1 fill">
				<div class="card-body">
					<div class="dash-widget-header">
						<div>
							<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $pengumuman[0]->total ?></strong></h3>
							<h6 class="text-3-1 text-color-danger line-height-2 my-0"><strong>PENGUMUMAN &darr;</strong></h6>
						</div>
						<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="fas fa-bullhorn icon icon-inline icon-md bg-danger rounded-circle text-color-light"></i>
								</path>
								<polyline points="14 2 14 8 20 8"></polyline>
								<line x1="12" y1="18" x2="12" y2="12"></line>
								<line x1="9" y1="15" x2="15" y2="15"></line>
								</svg>
							</span> </div>
					</div>
				</div>
			</div>
		</div>

	<?php } else { ?>


		<!-- Ulang Tahun -->
		<?php if ($ulangtahun[0]->total != 0) { ?>
			<div class="col-xl-4 col-sm-6 col-12 mb-2">
				<div class="card board1 fill">
					<div class="card-body">
						<div class="dash-widget-header">
							<div>
								<h5 class="text-3-1 text-color-success line-height-2 my-0"> <strong>Happy Birthday</strong></h5>
							</div>
							<?php foreach ($daftar_ulangtahun as $row) { ?>
								<h4 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $row->nama ?></strong></h4>
							<?php } ?>
							<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-cake icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg>
								</span> </div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>


		<style>
			.stat-card {
				border: none;
				border-radius: 18px;
				transition: all 0.3s ease-in-out;
			}

			.stat-card:hover {
				transform: translateY(-6px);
				box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
			}

			.stat-icon {
				width: 55px;
				height: 55px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 26px;
			}
		</style>

		<div class="container-fluid mt-4">
			<div class="row">
				<!-- Sisa Cuti Tahunan -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
					<a href="javascript:;" id="btn-show-cutiTahunan" class="text-decoration-none">
						<div class="card stat-card shadow-sm">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<h6 class="text-primary mb-1"><strong>Sisa Cuti Tahunan</strong></h6>
									<div class="d-flex align-items-baseline">
										<h3 class="font-weight-bold mb-0 me-2"><?= $cuti_tahunan ?></h3>
										<small class="text-primary">&nbsp;&nbsp;&nbsp; Detail</small>
									</div>
								</div>
								<div class="stat-icon bg-primary text-white">
									<i class="bx bx-calendar-check"></i>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Izin pada Jam Kerja -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
					<a href="javascript:;" id="btn-show-open" class="text-decoration-none">
						<div class="card stat-card shadow-sm">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<h6 class="text-danger mb-1"><strong>Izin pada Jam Kerja</strong></h6>
									<div class="d-flex align-items-baseline">
										<h3 class="font-weight-bold mb-0 me-2"><?= $izin_jam ?></h3>
										<small class="text-primary">&nbsp;&nbsp;&nbsp; Detail</small>
									</div>
								</div>
								<div class="stat-icon bg-danger text-white">
									<i class="bx bx-user-x"></i>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Izin Meninggalkan Pekerjaan -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
					<a href="javascript:;" id="btn-show-submit" class="text-decoration-none">
						<div class="card stat-card shadow-sm">
							<div class="card-body d-flex justify-content-between align-items-center">
								<div>
									<h6 class="text-danger mb-1"><strong>Izin dari Pekerjaan</strong></h6>
									<div class="d-flex align-items-baseline">
										<h3 class="font-weight-bold mb-0 me-2"><?= $izin_meinggalkan ?></h3>
										<small class="text-primary">&nbsp;&nbsp;&nbsp; Detail</small>
									</div>
								</div>
								<div class="stat-icon bg-danger text-white">
									<i class="bx bx-user-pin"></i>
								</div>
							</div>
						</div>
					</a>
				</div>

				<!-- Pengumuman -->
				<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
					<div class="card stat-card shadow-sm">
						<div class="card-body d-flex justify-content-between align-items-center">
							<div>
								<h6 class="text-info mb-1"><strong>Pengumuman</strong></h6>
								<div class="d-flex align-items-baseline">
									<h3 class="font-weight-bold mb-0 me-2"><?= $pengumuman[0]->total ?></h3>
									<small class="text-primary">&nbsp;&nbsp;&nbsp; </small>
								</div>
							</div>
							<div class="stat-icon bg-info text-white">
								<i class="bx bx-bell"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>



	<?php } ?>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

<div class="row mb-4">
	<div class="col-12">
		<div class="card dashboard-calendar-panel">
			<div class="card-body">
				<?php
				$calendar_widget_id = 'dashboard-cuti-calendar';
				$calendar_modal_id = 'dashboard-cuti-calendar-modal';
				$calendar_widget_title = 'Kalender Cuti Karyawan';
				$calendar_widget_subtitle = 'Pantau siapa saja yang sedang cuti langsung dari dashboard.';
				$this->load->view('pages/surat/partials/v_calendar_cuti_widget');
				?>
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
						<p class="calendar-subtitle mb-0">Pantau jadwal Ukes & Upar dari dashboard utama.</p>
					</div>
				</div>
				<div id="dashboard-visilab-calendar"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="dashboardVisilabDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title"><i class="fas fa-calendar-check"></i> Detail Jadwal Visilab</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div><strong>Jenis Jadwal:</strong> <span id="dashVisiJenis">-</span></div>
				<div><strong>Teknisi:</strong> <span id="dashVisiTeknisi">-</span></div>
				<div><strong>Tanggal:</strong> <span id="dashVisiTanggal">-</span></div>
				<div><strong>Jam:</strong> <span id="dashVisiJam">-</span></div>
				<div><strong>Status:</strong> <span id="dashVisiStatus">-</span></div>
				<div><strong>Pelanggan:</strong> <span id="dashVisiPelanggan">-</span></div>
				<div><strong>Wilayah:</strong> <span id="dashVisiWilayah">-</span></div>
				<div><strong>Provinsi:</strong> <span id="dashVisiProvinsi">-</span></div>
				<div><strong>Kab/Kota:</strong> <span id="dashVisiKabKota">-</span></div>
				<div><strong>Alamat:</strong> <span id="dashVisiAlamat">-</span></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="text-center">
			<h2>PENGUMUMAN</h2>
		</div>
		<div class="card-body">
			<div class="span6">
				<div class="widget-box">
					<div class="widget-title bg_ly" data-toggle="collapse" href="#collapseG2" aria-expanded="true"><span class="icon"><i class="fas fa-chevron-down"></i></span>
					</div>
					<div class="widget-content nopadding in collapse show" id="collapseG2">
						<ul class="recent-posts">
							<li>
								<?php
								foreach ($announce as $value) {
									echo "<li>";
									echo "<div class='user-thumb'><div id='userbox' class='userbox'> <span class='profile-picture profile-picture-as-text'>" . substr($value->nama, 0, 1) . "</span></div></div>";
									echo "<div class='article-post'>";
									echo "<span class='user-info'> Dibuat Oleh : " . $value->nama . " <br/> Tanggal : " . date('Y-m-d', strtotime($value->data_created)) . " </span>";
									echo "</br></br>";
									echo "<p><a href='#'>" . $value->message . "</a> </p>";
									if ($value->lampiran != "") {
										echo '<div class="float-right d-flex align-items-center" style="gap: 5px;">';
										echo '<a href="' . $value->lampiran . '" target="_blank" class="btn btn-info btn-xs"> <i class="fas fa-external-link-alt"></i>&nbsp;&nbsp;Lampiran </a>';
										echo '<button type="button" class="btn btn-warning btn-xs btn-preview-gdrive" data-url="' . $value->lampiran . '"> <i class="fas fa-eye"></i>&nbsp;&nbsp;Preview </button>';
										echo '</div>';
									}
									echo "<div></br></div>";
								}
								//echo"</div>";
								echo "</li>";
								?>
								<div class="text-center mt-2">
									<a href="<?= base_url('Announcement/daftar') ?>" class="btn btn-primary btn-sm btn-block" style="border-radius: 4px;"><i class="fas fa-eye"></i> Lihat Semua Pengumuman</a>
								</div>
							</li>
						</ul>
					</div>
				</div><!-- Visit codeastro.com for more projects -->
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="text-center">
			<h2>Log Aktivitas Anda</h2>
		</div>
		<div class="card-body">
			<div class="row form-group col-md-4">
				<small>Filter By Month:</small>
				<div class="input-group">
					<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
					<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
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


<div id="main-modal-hadir" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Hadir</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
							<th>Jenis Absen</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_hadir as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
								<td><?= $row->jenis_absen ?></td>
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
<div id="main-modal-terlambat" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Terlambat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable6" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_terlambat as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Sakit</h5>
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

<div id="main-modal-cuti" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Cuti</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable2" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_cuti as $row) { ?>
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

<div id="main-modal-pulang" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Pulang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable4" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_pulang as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
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


<div id="main-modal-istirahat" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Absen Setelah Istirahat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable5" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
							<th>Waktu</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_istirahat as $row) { ?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $row->nama ?></td>
								<td><?= $row->waktu_absen ?></td>
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

<div id="main-modal-noistirahat" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Karyawan Tidak Absen Istirahat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTable7" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th>Nama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_noistirahat as $row) { ?>
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





<div id="main-modal-open" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Izin Pada Jam Kerja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTableNew1" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:35%">Kode</th>
							<th style="width:50%">Keperluan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_izin_jam as $row) {

							$koTik  = '<a href="surat_part_two/show/detail/izin_jam_kerja/' . $row->id . '")>' . $row->kode_ijk . '</a>';

						?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->alasan ?></td>
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


<div id="main-modal-submit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Izin Meninggalkan Pekerjaan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<table id="myTableNew2" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:35%">Kode</th>
							<th style="width:50%">Keperluan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_izin_meninggalkan as $row) {

							$koTik  = '<a href="surat_part_two/show/detail/izin_meninggalkan/' . $row->id . '")>' . $row->kode_ijk . '</a>';

						?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->alasan ?></td>
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


<div id="main-modal-cutiTahunan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Data Cuti Tahunan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<h6 class="font-weight-bold mb-0 me-2"><?= $detail_cuti ?></h6><br>
				<table id="myTableNew3" class="table table-striped table-sm table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:5%">#</th>
							<th style="width:35%">Kode</th>
							<th style="width:50%">Keperluan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$x = 1;
						foreach ($daftar_cuti_tahunan as $row) {

							$koTik  = '<a href="surat_part_two/show/detail/cuti/' . $row->id . '")>' . $row->kode_cuti . '</a>';

						?>
							<tr>
								<td><?= $x++ ?></td>
								<td><?= $koTik ?></td>
								<td><?= $row->alasan ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

				<?php if (isAdmin() || isHrd()) { ?>
					<hr>
					<h6 class="font-weight-bold mb-3">Rekap Sisa Cuti Seluruh Karyawan Tahun <?= $tahun_berjalan ?></h6>
					<div class="table-responsive">
						<table id="myTableSisaCutiKaryawan" class="table table-striped table-sm table-bordered table-hover">
							<thead>
								<tr>
									<th style="width:5%">#</th>
									<th style="width:20%">Nama</th>
									<th style="width:12%">NPP</th>
									<th style="width:18%">Jabatan</th>
									<th style="width:10%">Jatah Cuti</th>
									<th style="width:10%">Cuti Diambil</th>
									<th style="width:10%">Sisa Cuti</th>
									<th style="width:15%">Kategori Masa Kerja</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no_rekap = 1;
								$masa_kerja_label = array(
									'A' => 'Di atas 5 tahun',
									'B' => 'Di atas 1 tahun',
									'C' => 'Di bawah 1 tahun',
									'D' => 'Belum kontrak'
								);
								foreach ($rekap_sisa_cuti_karyawan as $rekap) {
									$label_masa_kerja = isset($masa_kerja_label[$rekap->masa_kerja]) ? $masa_kerja_label[$rekap->masa_kerja] : '-';
								?>
									<tr>
										<td><?= $no_rekap++ ?></td>
										<td><?= htmlspecialchars($rekap->nama) ?></td>
										<td><?= htmlspecialchars($rekap->no_pegawai) ?></td>
										<td><?= htmlspecialchars($rekap->jabatan) ?></td>
										<td><?= $rekap->jatah_cuti ?></td>
										<td><?= $rekap->cuti_diambil ?></td>
										<td><?= $rekap->sisa_cuti ?></td>
										<td><?= $label_masa_kerja ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>

		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		$('#myTableNew1').DataTable();
		$('#myTableNew2').DataTable();
		$('#myTableNew3').DataTable();
		if ($('#myTableSisaCutiKaryawan').length) {
			$('#myTableSisaCutiKaryawan').DataTable({
				pageLength: 25,
				order: [
					[1, 'asc']
				]
			});
		}

		$('#btn-show-open').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-open').modal()
		})

		$('#btn-show-submit').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-submit').modal()
		})

		$('#btn-show-cutiTahunan').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-cutiTahunan').modal()
		})




		$('#filter_month').change(function() {
			table.ajax.reload()
		})
		$('#myTable').DataTable();
		$('#myTable1').DataTable();
		$('#myTable2').DataTable();
		$('#myTable3').DataTable();
		$('#myTable4').DataTable();
		$('#myTable5').DataTable();
		$('#myTable6').DataTable();
		$('#myTable7').DataTable();

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

		$('#btn-show-terlambat').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-terlambat').modal()
		})

		$('#btn-show-istirahat').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-istirahat').modal()
		})

		$('#btn-show-noistirahat').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-noistirahat').modal()
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

		$('#btn-show-pulang').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-pulang').modal()
		})


		$('#btn-show-cuti').click(function() {
			$('.btn-isactive').remove()
			$('#main-modal-cuti').modal()
		})

		/** * Logika Confetti:
		 * Cek PHP: Apakah ada yang ultah? (total > 0)
		 * Cek Session: Apakah ini login pertama? (show_confetti == TRUE)
		 */
		<?php if ($ulangtahun[0]->total > 0 && $this->session->userdata('show_confetti')) : ?>

			// Jalankan Confetti
			var duration = 3 * 1000; // 3 Detik
			var animationEnd = Date.now() + duration;
			var defaults = {
				startVelocity: 30,
				spread: 360,
				ticks: 60,
				zIndex: 0
			};

			function randomInRange(min, max) {
				return Math.random() * (max - min) + min;
			}

			var interval = setInterval(function() {
				var timeLeft = animationEnd - Date.now();

				if (timeLeft <= 0) {
					return clearInterval(interval);
				}

				var particleCount = 50 * (timeLeft / duration);
				// Menembak dari kiri dan kanan
				confetti(Object.assign({}, defaults, {
					particleCount,
					origin: {
						x: randomInRange(0.1, 0.3),
						y: Math.random() - 0.2
					}
				}));
				confetti(Object.assign({}, defaults, {
					particleCount,
					origin: {
						x: randomInRange(0.7, 0.9),
						y: Math.random() - 0.2
					}
				}));
			}, 250);

			// 2. Hapus flag session agar tidak muncul lagi saat refresh (AJAX atau Simple PHP)
			<?php $this->session->unset_userdata('show_confetti'); ?>

		<?php endif; ?>

		var visilabCalendarEl = document.getElementById('dashboard-visilab-calendar');
		if (visilabCalendarEl && typeof FullCalendar !== 'undefined') {
			var visilabCalendar = new FullCalendar.Calendar(visilabCalendarEl, {
				initialView: 'dayGridMonth',
				locale: 'id',
				height: 520,
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,dayGridWeek'
				},
				events: function(info, successCallback, failureCallback) {
					var viewYear = new Date((info.start.getTime() + info.end.getTime()) / 2).getFullYear();
					$.getJSON('<?= base_url('visilab_jadwal/get_events') ?>', {
							year: viewYear
						})
						.done(function(resp) {
							successCallback(resp);
						})
						.fail(function() {
							failureCallback();
						});
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
					var opt = {
						year: 'numeric',
						month: 'long',
						day: 'numeric'
					};
					var tanggalMulai = props.tanggal_mulai || (info.event.startStr || '');
					var tanggalSelesai = props.tanggal_selesai || tanggalMulai;
					var tanggal = '-';
					if (tanggalMulai) {
						var tStart = new Date(tanggalMulai + 'T00:00:00').toLocaleDateString('id-ID', opt);
						var tEnd = new Date(tanggalSelesai + 'T00:00:00').toLocaleDateString('id-ID', opt);
						tanggal = tStart === tEnd ? tStart : (tStart + ' s/d ' + tEnd);
					}
					$('#dashVisiJenis').text(props.jenis_jadwal || '-');
					$('#dashVisiTeknisi').text(props.teknisi_nama || (props.teknisi_id ? ('ID ' + props.teknisi_id) : '-'));
					$('#dashVisiTanggal').text(tanggal);
					$('#dashVisiJam').text(props.jam || '-');
					$('#dashVisiStatus').text(props.status || '-');
					$('#dashVisiPelanggan').text(props.lokasi_pelanggan_nama || '-');
					$('#dashVisiWilayah').text(props.wilayah || '-');
					$('#dashVisiProvinsi').text(props.provinsi || '-');
					$('#dashVisiKabKota').text(props.kab_kota || '-');
					$('#dashVisiAlamat').text(props.lokasi_alamat || '-');
					$('#dashboardVisilabDetailModal').modal('show');
				}
			});
			visilabCalendar.render();
		}
	})
</script>

<!-- Modal Preview Google Drive -->
<div class="modal fade" id="modalPreviewGDrive" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="max-width: 85%;">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center" style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100%;">
				<h5 class="modal-title" style="margin: 0;"><i class="fas fa-eye"></i> Preview Lampiran</h5>
				<div class="d-flex align-items-center" style="gap: 15px; display: flex !important; align-items: center !important;">
					<a href="" id="btnOpenGDriveTab" target="_blank" class="btn btn-warning btn-xs text-dark" style="font-weight: 600;"><i class="fas fa-external-link-alt"></i> Buka di Tab Baru</a>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="margin: 0; padding: 0; opacity: 0.8;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
			<div class="modal-body p-0" style="height: 75vh; position: relative;">
				<div class="alert alert-info py-2 px-3 m-0 rounded-0" style="font-size: 12px; border: none; border-bottom: 1px solid #bce8f1; background-color: #d9edf7; color: #31708f; margin: 0 !important; border-radius: 0 !important;">
					<i class="fas fa-info-circle"></i> <strong>Tips:</strong> Jika file tidak muncul (masalah hak akses / akun), silakan klik tombol <strong>Buka di Tab Baru</strong> untuk melihat file secara langsung.
				</div>
				<iframe id="iframeGDrivePreview" src="" style="width: 100%; height: calc(100% - 38px); border: none;"></iframe>
			</div>
		</div>
	</div>
</div>

<script>
	function initGDrivePreview() {
		$(document).on('click', '.btn-preview-gdrive', function() {
			var rawUrl = $(this).data('url');
			var previewUrl = rawUrl;

			// Set the href for fallback button
			$('#btnOpenGDriveTab').attr('href', rawUrl);

			if (rawUrl.includes('drive.google.com')) {
				// If it's a folder link
				if (rawUrl.includes('/drive/folders/')) {
					var folderMatch = rawUrl.match(/\/folders\/([a-zA-Z0-9_-]+)/);
					if (folderMatch && folderMatch[1]) {
						previewUrl = "https://drive.google.com/embeddedfolderview?id=" + folderMatch[1] + "#grid";
					}
				} else {
					// File link
					var match = rawUrl.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
					if (match && match[1]) {
						previewUrl = "https://drive.google.com/file/d/" + match[1] + "/preview";
					} else {
						var matchId = rawUrl.match(/[?&]id=([a-zA-Z0-9_-]+)/);
						if (matchId && matchId[1]) {
							previewUrl = "https://drive.google.com/file/d/" + matchId[1] + "/preview";
						}
					}
				}
			}

			$('#iframeGDrivePreview').attr('src', previewUrl);
			$('#modalPreviewGDrive').modal('show');
		});

		$('#modalPreviewGDrive').on('hidden.bs.modal', function() {
			$('#iframeGDrivePreview').attr('src', '');
			$('#btnOpenGDriveTab').attr('href', '');
		});
	}

	if (typeof jQuery !== 'undefined') {
		initGDrivePreview();
	} else {
		window.addEventListener('load', initGDrivePreview);
	}
</script>