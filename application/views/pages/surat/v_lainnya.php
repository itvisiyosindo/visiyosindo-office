<br>
<div class="col-lg-12">
                            <div class="row mb-3">

                                <?php if(isAdmin() || isGa() || sessPenggunaId() == '33'){ ?>
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Tugas</h4>
                                                        <!--<div class="info">-->
                                                        <!--    <strong class="amount">1281</strong>-->
                                                        <!--    <span class="text-primary">(14 unread)</span>-->
                                                        <!--</div>-->
                                                    </div>
                                                    <div class="summary-footer">
                                                        <?php if (isAdmin() || sessPenggunaId() == '33') { ?>
													
															
															<a class="text-uppercase" href="<?= base_url('surat/show/list/st') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
														
													<?php } else if (isGa()) { ?>
													<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/st') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
										            <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                
                                
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Skorsing</h4>
                                                    </div>
                                                    <div class="summary-footer">
													        <a class="text-uppercase" href="<?= base_url('surat_new/show/list/spi') ?>">
																<span>Pengajuan & Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                               <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Aset</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/sta') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/sta') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                
                               <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Fisik Perlengkapan</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/stfp') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/stfp') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat<br> Keterangan<br>Aktif Bekerja</h4>
                                                        <!--<div class="info">-->
                                                        <!--    <strong class="amount">1281</strong>-->
                                                        <!--    <span class="text-primary">(14 unread)</span>-->
                                                        <!--</div>-->
                                                    </div>
                                                    <div class="summary-footer">
                                                        <?php if (isAdmin() || sessPenggunaId() == '69' || sessPenggunaId() == '744') { ?>
													
															
															<a class="text-uppercase" href="<?= base_url('surat/show/list/keterangan') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
														
													<?php } else if (isGa()) { ?>
													<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/keterangan') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
										            <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Pemberitahuan</h4>
                                                        <div class="info">
                                                            <strong class="amount">1</strong>
                                                        </div>
                                                    </div>
                                                    <div class="summary-footer">
                                                        <a class="text-muted text-uppercase" href="#">(withdraw)</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Pekerjaan</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/stp') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/stp') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Keputusan Direksi</h4>
                                                        <div class="info">
                                                            <strong class="amount">1281</strong>
                                                            <span class="text-primary">(14 unread)</span>
                                                        </div>
                                                    </div>
                                                    <div class="summary-footer">
                                                        <?php if (isAdmin() || isHrd()) { ?>
												      <a class="text-uppercase" href="<?= base_url('surat/show/my_surat/skd') ?>">
												          <span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
															<a class="text-uppercase" href="<?= base_url('surat/show/list/skd') ?>">
															    <span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<?php } else { ?>
													<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/skd') ?>">
													    <span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
													</a>
											<?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-xl-4">
                                   <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Kuasa</h4>
                                                        <div class="info">
                                                            <strong class="amount">1281</strong>
                                                            <span class="text-primary">(14 unread)</span>
                                                        </div>
                                                    </div>
                                                    <div class="summary-footer">
                                                        <a class="text-muted text-uppercase" href="#">(view all)</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                 <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Rekomendasi</h4>
                                                    </div>
                                                    <div class="summary-footer">
                                                        <?php if (isAdmin() || sessPenggunaId() == '33' || sessPenggunaId() == '54') { ?>
													
															
															<a class="text-uppercase" href="<?= base_url('surat/show/list/rekom') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
														
													<?php } else if (isGa() || isLegalOfficer()) { ?>
													<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/rekom') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
										            <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Peringatan</h4>
                                                        <!--<div class="info">-->
                                                        <!--    <strong class="amount">3765</strong>-->
                                                        <!--</div>-->
                                                    </div>
                                                     <?php if (isAdmin() || sessPenggunaId() == '33' || isGa()) { ?>
                                                    <div class="summary-footer">
															<a class="text-uppercase" href="<?= base_url('surat/show/list/surat_peringatan') ?>">
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
												        	<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/surat_peringatan') ?>">
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>	
                                                    </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Berita Acara</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_part_two/show/list/berita_acara') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_part_two/show/permintaan/ba') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Keterangan<br>Pengalaman Kerja</h4>
                                                    </div>
                                                    <div class="summary-footer">
															<a class="text-uppercase" href="<?= base_url('surat_part_two/show/list/paklaring') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													        <a class="text-uppercase" href="<?= base_url('surat_part_two/show/list/paklaring') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                            <?php }elseif(sessPenggunaId() == '69' || sessPenggunaId() == '744'){ ?>
                                
                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Peringatan</h4>
                                                        <!--<div class="info">-->
                                                        <!--    <strong class="amount">3765</strong>-->
                                                        <!--</div>-->
                                                    </div>
                                                    <div class="summary-footer">
															<a class="text-uppercase" href="<?= base_url('surat/show/list/surat_peringatan') ?>">
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
												        	<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/surat_peringatan') ?>">
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>	
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Berita Acara</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_part_two/show/list/berita_acara') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_part_two/show/permintaan/ba') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Tugas</h4>
                                                        <!--<div class="info">-->
                                                        <!--    <strong class="amount">1281</strong>-->
                                                        <!--    <span class="text-primary">(14 unread)</span>-->
                                                        <!--</div>-->
                                                    </div>
                                                    <div class="summary-footer">
                                                        <?php if (isAdmin() || sessPenggunaId() == '69' || sessPenggunaId() == '744') { ?>
													
															
															<a class="text-uppercase" href="<?= base_url('surat/show/list/st') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
														
													<?php } else if (isGa()) { ?>
													<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/st') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
										            <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Keterangan<br>Pengalaman Kerja</h4>
                                                    </div>
                                                    <div class="summary-footer">
															<a class="text-uppercase" href="<?= base_url('surat_part_two/show/list/paklaring') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													        <a class="text-uppercase" href="<?= base_url('surat_part_two/show/list/paklaring') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Aset</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/sta') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/sta') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Fisik Perlengkapan</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/stfp') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/stfp') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>


                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Pekerjaan</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/stp') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/stp') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                 <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Skorsing</h4>
                                                    </div>
                                                    <div class="summary-footer">
													        <a class="text-uppercase" href="<?= base_url('surat_new/show/list/spi') ?>">
																<span>Pengajuan & Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                 <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat<br> Keterangan<br>Aktif Bekerja</h4>
                                                        <!--<div class="info">-->
                                                        <!--    <strong class="amount">1281</strong>-->
                                                        <!--    <span class="text-primary">(14 unread)</span>-->
                                                        <!--</div>-->
                                                    </div>
                                                    <div class="summary-footer">
                                                        <?php if (isAdmin() || sessPenggunaId() == '69' || sessPenggunaId() == '744') { ?>
													
															
															<a class="text-uppercase" href="<?= base_url('surat/show/list/keterangan') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
														
													<?php } else if (isGa()) { ?>
													<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/keterangan') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
										            <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                
                                                        
                                <?php }else{ ?>
                                    <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Surat Rekomendasi</h4>
                                                    </div>
                                                    <div class="summary-footer">
                                                        <?php if (isAdmin() || sessPenggunaId() == '33' || sessPenggunaId() == '54') { ?>
													
															
															<a class="text-uppercase" href="<?= base_url('surat/show/list/rekom') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
														
													<?php } else if (isGa() || isLegalOfficer()) { ?>
													<a class="text-uppercase" href="<?= base_url('surat/show/my_surat/rekom') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
										            <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Berita Acara</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_part_two/show/list/berita_acara') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_part_two/show/permintaan/ba') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Aset</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/sta') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/sta') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Fisik Perlengkapan</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/stfp') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/stfp') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>


                                <div class="col-xl-4">
                                    <section class="card card-featured-left card-featured-tertiary mb-3">
                                        <div class="card-body">
                                            <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                    <div class="summary-icon bg-tertiary">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                    <div class="summary">
                                                        <h4 class="title">Serah Terima Pekerjaan</h4>
                                                    </div>
                                                    <div class="summary-footer">
															
															<a class="text-uppercase" href="<?= base_url('surat_new/show/list/stp') ?>">
																
																<span>Persetujuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
													<a class="text-uppercase" href="<?= base_url('surat_new/show/permintaan/stp') ?>">
																
																<span>Pengajuan</span>&nbsp;<i class="fas fa-paper-plane"></i>
															</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                
                                <?php }?>


                            </div>
                            
                        </div>
                    </div>
                     </div>