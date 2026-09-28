<header class="page-header">
	<h2><i class="icons icon-list"></i>&nbsp;&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
	</div>
</header>
<div class="row">
    <?php if (isHrd() || isAdmin()) { ?>

			<?php if ($ulangtahun[0]->total != 0 ) { ?>
					<div class="col-xl-4 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h5 class="text-3-1 text-color-success line-height-2 my-0"> <strong>Happy Birthday</strong></h5> </div>
										<?php foreach ($daftar_ulangtahun as $row) { ?>
										<h4 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $row->nama ?></strong></h4>
										<?php } ?>
										
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-cake icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-success line-height-2 my-0">Total <strong>Hadir</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Terlambat</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-x icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
									<line x1="12" y1="1" x2="12" y2="23"></line>
									<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
									</svg></span> </div>
								</div>
							</div>
						</div>
						</a>
					</div>

					<?php if ($istirahat[0]->total != 0 ) { ?>
					<div class="col-xl-2 col-sm-6 col-12 mb-2">
					    <a href="javascript:;" id="btn-show-istirahat">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $istirahat[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Absen Istirahat &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Tidak Absen Istirahat &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Izin</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-pin icon icon-inline icon-md bg-primary rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
									    <h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Cuti &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-circle icon icon-inline icon-md bg-info rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Sakit &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-minus icon icon-inline icon-md bg-warning rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Pulang &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-minus icon icon-inline icon-md bg-danger rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0"><strong>PENGUMUMAN &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="fas fa-bullhorn icon icon-inline icon-md bg-danger rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
		<?php }else if (sessPenggunaId()==737) { ?>

			<?php if ($ulangtahun[0]->total != 0 ) { ?>
					<div class="col-xl-4 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h5 class="text-3-1 text-color-success line-height-2 my-0"> <strong>Happy Birthday</strong></h5> </div>
										<?php foreach ($daftar_ulangtahun as $row) { ?>
										<h4 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $row->nama ?></strong></h4>
										<?php } ?>
										
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-cake icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-success line-height-2 my-0">Total <strong>Hadir</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Terlambat</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-x icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
									<line x1="12" y1="1" x2="12" y2="23"></line>
									<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
									</svg></span> </div>
								</div>
							</div>
						</div>
						</a>
					</div>
					<div class="col-xl-2 col-sm-6 col-12 mb-2">
					    <a href="javascript:;" id="btn-show-izin">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $izin[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Izin</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-pin icon icon-inline icon-md bg-primary rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
									    <h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Cuti &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-circle icon icon-inline icon-md bg-info rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Sakit &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-minus icon icon-inline icon-md bg-warning rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
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
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $pengumuman[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-danger line-height-2 my-0"><strong>PENGUMUMAN &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="fas fa-bullhorn icon icon-inline icon-md bg-danger rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
						</a>
					</div>
		<?php } else { ?>
					<!-- Ulang Tahun -->
					<?php if ($ulangtahun[0]->total != 0 ) { ?>
					<div class="col-xl-4 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h5 class="text-3-1 text-color-success line-height-2 my-0"> <strong>Happy Birthday</strong></h5> </div>
										<?php foreach ($daftar_ulangtahun as $row) { ?>
										<h4 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $row->nama ?></strong></h4>
										<?php } ?>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-cake icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>

			        <div class="col-xl-2 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $present[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-success line-height-2 my-0">Total <strong>Hadir</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-check icon icon-inline icon-md bg-success rounded-circle text-color-light"></i>
									<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="8.5" cy="7" r="4"></circle>
									<line x1="20" y1="8" x2="20" y2="14"></line>
									<line x1="23" y1="11" x2="17" y2="11"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $terlambat[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Terlambat</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-x icon icon-inline icon-md bg-dark rounded-circle text-color-light"></i>
									<line x1="12" y1="1" x2="12" y2="23"></line>
									<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $izin[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Izin</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-pin icon icon-inline icon-md bg-primary rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $cuti[0]->total ?></strong></h3>
									    <h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Cuti &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-circle icon icon-inline icon-md bg-info rounded-circle text-color-light"></i>
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $sakit[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-danger line-height-2 my-0">Total <strong>Sakit &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-minus icon icon-inline icon-md bg-warning rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-sm-6 col-12 mb-2">
						<div class="card board1 fill">
							<div class="card-body">
								<div class="dash-widget-header">
									<div>
										<h3 class="card_widget_header"><strong class="text-6 text-color-dark"><?= $pengumuman[0]->total ?></strong></h3>
										<h6 class="text-3-1 text-color-danger line-height-2 my-0"><strong>PENGUMUMAN &darr;</strong></h6> </div>
									<div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><i class="bx bx-user-minus icon icon-inline icon-md bg-danger rounded-circle text-color-light"></i>
									</path>
									<polyline points="14 2 14 8 20 8"></polyline>
									<line x1="12" y1="18" x2="12" y2="12"></line>
									<line x1="9" y1="15" x2="15" y2="15"></line>
									</svg></span> </div>
								</div>
							</div>
						</div>
					</div>
		<?php } ?>
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
                                foreach ($announce as $value){
                                    echo "<li>";
                                    echo "<div class='user-thumb'><div id='userbox' class='userbox'> <span class='profile-picture profile-picture-as-text'>" . substr($value->nama, 0, 1) . "</span></div></div>";
                                    echo "<div class='article-post'>"; 
                                    echo "<span class='user-info'> Dibuat Oleh : " . $value->nama. " <br/> Tanggal : ". date('Y-m-d',strtotime($value->data_created)) ." </span>";
                                    echo "</br></br>";
                                    echo "<p><a href='#'>".$value->message."</a> </p>";
                                    if($value->lampiran != "") {
                                        echo '<a href="'. $value->lampiran . '" class="btn btn-primary btn-xs float-right"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran </a>';
                                    }
                                    echo "<div></br></div>";
                                }
                                //echo"</div>";
                                echo"</li>";
                           ?> 
                          <a href="#"><button class="btn btn-warning btn-sm">View All</button></a>
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


<script>
	document.addEventListener('DOMContentLoaded', function() {
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
	})
</script>