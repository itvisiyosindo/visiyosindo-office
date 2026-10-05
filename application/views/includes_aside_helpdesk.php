<aside id="sidebar-left" class="sidebar-left">
	<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
	<!-- DEBUG: ID=<?= sessPenggunaId() ?>, Role=<?= $this->session->userdata('login_type') ?>, isGa=<?= isGa() ? 1 : 0 ?> -->
	<!-- Mobile Sidebar Header with Close Button -->
	<div class="sidebar-header-mobile d-md-none">
		<div class="sidebar-mobile-title">Office Menu</div>
		<button class="sidebar-mobile-close" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
			<i class="fas fa-times"></i>
		</button>
	</div>
	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">
					<li>
						<span>
							<select class="form-control" style="width:90%; margin:5%" id="switch_menu">
								<option value="<?= base_url('dashboard') ?>">Home</option>
								<option value="<?= base_url('dashboard_dokumen') ?>">Dokumen Perusahaan</option>
								<option value="<?= base_url('dashboard_kepegawaian') ?>">Kepegawaian</option>
								<option value="<?= base_url('dashboard_helpdesk') ?>" selected>Helpdesk</option>
								<option value="<?= base_url('dashboard_inventory') ?>">Inventory</option>
								<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '75' || sessPenggunaId() == '755' || sessPenggunaId() == '64' || sessPenggunaId() == '777') { ?>
									<option value="<?= base_url('dashboard_marketing') ?>">Marketing</option>
								<?php } ?>
								<?php if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '84' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '736' || sessPenggunaId() == '754' || sessPenggunaId() == '751' || sessPenggunaId() == '25' || sessPenggunaId() == '765' || sessPenggunaId() == '755' || sessPenggunaId() == '770' || sessPenggunaId() == '777') { ?>
									<option value="<?= base_url('dashboard_visilab') ?>">Visilab</option>
								<?php } ?>

								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('acc_pemasok') ?>" <?= $switch == 'accounting' ? 'selected' : '' ?>>Accounting & Tax</option>
								<?php } ?>
							</select>
						</span>
					</li>



					<?php
					$allowed_master = ['72', '69', '744', '81', '714', '75', '756', '15', '23', '84', '755', '33', '754', '766', '777'];
					?>

					<?php if (isAdmin() || in_array(sessPenggunaId(), $allowed_master)) { ?>
						<li class="nav-parent <?= in_array($page_name, ['pelanggan/v_pelanggan', 'pelanggan/v_detail_pelanggan', 'pelanggan/v_commisioning', 'pelanggan/v_detail_commisioning', 'kategori_Tiket/v_kategori', 'kategori_pajak/v_kategori_pajak', 'forwarder/v_forwarder']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-database" aria-hidden="true"></i>
								<span>Master Data</span>
							</a>
							<ul class="nav nav-children">

								<?php if (!in_array(sessPenggunaId(), ['81', '766'])) { ?>
									<li class="<?= in_array($page_name, ['pelanggan/v_pelanggan', 'pelanggan/v_detail_pelanggan']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('pelanggan') ?>">
											Data Pelanggan
										</a>
									</li>

									<li class="<?= $this->uri->segment(1) == 'kategori_Tiket' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('kategori_Tiket') ?>">
											Kategori Tiket
										</a>
									</li>
								<?php } ?>

								<?php if (isAdmin() || in_array(sessPenggunaId(), ['58', '33'])) { ?>
									<li class="<?= $this->uri->segment(1) == 'kategori_pajak' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('kategori_pajak') ?>">
											Kategori Pajak
										</a>
									</li>
								<?php } ?>

								<?php if (isAdmin() || in_array(sessPenggunaId(), ['75', '15', '23', '84', '756', '33', '766'])) { ?>
									<li class="<?= in_array($page_name, ['forwarder/v_forwarder']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('forwarder') ?>">
											Forwarder
										</a>
									</li>
								<?php } ?>

							</ul>
						</li>
					<?php } ?>

					<li class="nav-parent <?= in_array(
												$page_name,
												[
													'surat/v_surat_list',
													'surat/v_surat_list_spp',
													'surat/v_surat_list_gc',
													'surat/v_pengajuan',
													'surat/v_pengajuan_spp',
													'surat/v_pengajuan_gc',
													'surat/v_detail_pb',
													'surat/v_detail_lapor_pb',
													'surat/v_detail_gc',
													'surat/v_detail_permintaan_pembayaran',
													'surat/v_pengajuan_pbok',
													'surat/v_surat_list_pbok',
													'surat/v_surat_list_kg',
													'surat/v_pengajuan_kg',
													'surat/v_surat_list_pkk',
													'surat/v_pengajuan_pkk',
													'surat/v_surat_list_pkketoll',
													'surat/v_pengajuan_pkketoll',
													'surat/v_detail_kg',
													'surat/v_detail_pbok',
													'surat/v_detail_pkk',
													'surat/v_detail_pkketoll',
													'surat/v_surat_list_ppa',
													'surat/v_pengajuan_ppa',
													'surat/v_pengajuan_approval',
													'surat/v_pengajuan_pd',
													'surat/v_pengajuan_sd',
													'surat/v_surat_list_pd',
													'surat/v_surat_list_sd',
													'surat/v_surat_list_approval',
													'surat/v_surat_list_pd_teknisi',
													'surat/v_surat_list_sdt',
													'surat/v_pengajuan_pd_karyawan',
													'surat/v_surat_list_pd_karyawan',
													'surat/v_pengajuan_izin_jam_kerja',
													'surat/v_aju_izin_jam_kerja',
													'surat/v_surat_list_izin_jam_kerja',
													'surat/v_pengajuan_izin_meninggalkan',
													'surat/v_aju_izin_meninggalkan',
													'surat/v_surat_list_izin_meninggalkan',
													'surat/v_surat_list_izin_cuti',
													'surat/v_pengajuan_izin_cuti',
													'surat/v_aju_izin_cuti',
													'surat/v_lainnya',
													'surat/v_aju_berita_acara',
													'surat/v_surat_list_berita_acara',
													'surat/v_pengajuan_berita_acara',
													'surat/v_detail_berita_acara',
													'surat/v_paklaring',
													'surat/v_detail_paklaring',
													'surat/v_aju_paklaring',
													'surat/v_surat_list_meetingroom',
													'surat/v_pengajuan_meetingroom',
													'surat/v_permintaan_meetingroom',
													'surat_new/v_list_kendaraan',
													'surat_new/v_kendaraan',
													'surat_new/v_aju_kendaraan',
													'surat_new/v_list_po',
													'surat_new/v_po',
													'surat_new/v_aju_po',
													'surat_new/v_list_appeks',
													'surat_new/v_appeks',
													'surat_new/v_aju_appeks',
													'surat/v_pengajuan_izin_jam_kerja_sgm',
													'surat/v_aju_izin_jam_kerja_sgm',
													'surat/v_pengajuan_izin_meninggalkan_sgm',
													'surat/v_aju_izin_meninggalkan_sgm',
													'surat/v_pengajuan_izin_cuti_sgm',
													'surat/v_aju_izin_cuti_sgm',
													'surat/v_surat_list_izin_jam_kerja_sgm',
													'surat/v_surat_list_izin_meninggalkan_sgm',
													'surat/v_surat_list_izin_cuti_sgm',
													'surat_new/v_list_appdir',
													'surat_new/v_appdir',
													'forwarder/v_list_forwarder',
													'forwarder/v_aju_forwarder',
													'forwarder/v_all_forwarder',
													'forwarder/v_detail_forwarder',
													'forwarder/v_list_forwarder',
													'surat_new/v_list_app_pajak',
													'surat_new/v_app_pajak',
													'surat_new/v_detail_app_pajak',
													'kirim/v_kirim',
													'kirim/v_detail_kirim',
													'kirim/v_list_kirim',
													'kirim/v_detail_data_kirim',
													'tagihan/v_detail_tagihan',
													'tagihan/v_list_tagihan',
													'tagihan/v_form_pengajuan',
													'tagihan/v_my_tagihan',
													'spp/v_list_spp',
													'spp/v_create_spp',
													'spp/v_detail_spp',
													'spp/v_edit_spp',
													'spp/v_config_spp',
												]
											) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-digital-tachograph" aria-hidden="true"></i>
							<span>Surat - Menyurat</span>
						</a>
						<ul class="nav nav-children">
							<?php if (isGa() || sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || sessPenggunaId() == '54' || sessPenggunaId() == '72' || sessPenggunaId() == '54' || sessPenggunaId() == '10' || sessPenggunaId() == '62' || sessPenggunaId() == '6' || sessPenggunaId() == '65' || sessPenggunaId() == '39' || sessPenggunaId() == '88'  || sessPenggunaId() == '107' || sessPenggunaId() == '81' || sessPenggunaId() == '70' || sessPenggunaId() == '714' || sessPenggunaId() == '69' || sessPenggunaId() == '744' || sessPenggunaId() == '64' || sessPenggunaId() == '777' || sessPenggunaId() == '15' || sessPenggunaId() == '106' || sessPenggunaId() == '107' || sessPenggunaId() == '749' || sessPenggunaId() == '763' || sessPenggunaId() == '764' || sessPenggunaId() == '769' || sessPenggunaId() == '771') { ?>
								<li class="nav-parent <?= in_array($page_name, [
															'surat/v_surat_list',
															'surat/v_surat_list_spp',
															'surat/v_surat_list_gc',
															'surat/v_detail_pb',
															'surat/v_detail_lapor_pb',
															'surat/v_detail_gc',
															'surat/v_detail_permintaan_pembayaran',
															'surat/v_surat_list_pbok',
															'surat/v_surat_list_kg',
															'surat/v_surat_list_pkk',
															'surat/v_surat_list_pkketoll',
															'surat/v_detail_kg',
															'surat/v_detail_pbok',
															'surat/v_detail_pkk',
															'surat/v_surat_list_ppa',
															'surat/v_surat_list_pd',
															'surat/v_surat_list_sd',
															'surat/v_surat_list_approval',
															'surat/v_surat_list_pd_teknisi',
															'surat/v_surat_list_sdt',
															'surat/v_pengajuan_pd_teknisi',
															'surat/v_pengajuan_sd_teknisi',
															'surat/v_surat_list_pd_karyawan',
															'surat/v_surat_list_izin_jam_kerja',
															'surat/v_surat_list_izin_meninggalkan',
															'surat/v_surat_list_izin_cuti',
															'surat/v_surat_list_meetingroom',
															'surat_new/v_list_kendaraan',
															'surat_new/v_list_po',
															'surat_new/v_list_appeks',
															'surat/v_surat_list_izin_jam_kerja_sgm',
															'surat/v_surat_list_izin_meninggalkan_sgm',
															'surat/v_surat_list_izin_cuti_sgm',
															'surat_new/v_list_appdir',
															'forwarder/v_list_forwarder',
															'forwarder/v_detail_forwarder',
															'surat_new/v_list_app_pajak',
															'surat_new/v_detail_app_pajak',
															'tagihan/v_detail_tagihan',
															'tagihan/v_list_tagihan',
															'spp/v_list_spp',
															'spp/v_detail_spp',
															'spp/v_edit_spp',
															'spp/v_config_spp',
														])  ? 'nav-expanded' : ''; ?>">
									<a class="nav-link" href="#">
										Daftar Persetujuan
									</a>
									<ul class="nav nav-children">
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '58' || sessPenggunaId() == '107' || sessPenggunaId() == '81' || sessPenggunaId() == '81' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || isGa() || sessPenggunaId() == '64' || sessPenggunaId() == '777' || sessPenggunaId() == '106' || sessPenggunaId() == '107' || sessPenggunaId() == '749' || sessPenggunaId() == '763' || sessPenggunaId() == '764' || sessPenggunaId() == '769') { ?>
											<li class="<?= in_array($page_name, ['tagihan/v_detail_tagihan', 'tagihan/v_list_tagihan']) ? 'nav-active' : '' ?>">
												<a class="nav-link" href="<?= base_url('tagihan') ?>">
													Tagihan Ekspedisi
												</a>
											</li>
											<li class="<?= in_array($page_name, ['spp/v_list_spp', 'spp/v_detail_spp', 'spp/v_edit_spp', 'spp/v_config_spp']) ? 'nav-active' : '' ?>">
												<a class="nav-link" href="<?= base_url('spp') ?>">
													SPP Ekspedisi
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '58' || sessPenggunaId() == '107' || sessPenggunaId() == '81' || sessPenggunaId() == '81' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || isGa()) { ?>
											<li class="<?= $page_name == 'surat/v_surat_list' || $page_name == 'surat/v_detail_pb' || $page_name == 'surat/v_detail_lapor_pb' ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat') ?> ">
													Pembiayaan Dinas
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '714' || sessPenggunaId() == '1' || sessPenggunaId() == '58' || sessPenggunaId() == '764' || sessPenggunaId() == '777') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_gc' || $page_name == 'surat/v_detail_gc' ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/gc') ?> ">
													Limit Grab
												</a>
											</li>

										<?php } ?>
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_spp' || $page_name == 'surat/v_detail_permintaan_pembayaran' ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/spp') ?> ">
													Permintaan Pembayaran
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '107' || sessPenggunaId() == '81' || sessPenggunaId() == '81' || sessPenggunaId() == '23' || sessPenggunaId() == '33') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_pbok' || $page_name == 'surat/v_detail_pbok' ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/pbok') ?> ">
													PBOK
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || isGa() || sessPenggunaId() == '33') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_kg' || $page_name == 'surat/v_detail_kg' ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/kg') ?> ">
													Kunjungan Gudang
												</a>
											</li>
										<?php } ?>
										<?php if (isAdmin() || sessPenggunaId() == '82' || sessPenggunaId() == '107' || sessPenggunaId() == '81' || sessPenggunaId() == '23' || sessPenggunaId() == '33') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_pkk' || $page_name == 'surat/v_detail_pkk' ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/pkk') ?> ">
													Klaim Kas
												</a>
											</li>
										<?php } ?>
										<?php if (isAdmin() || sessPenggunaId() == '82' || sessPenggunaId() == '107' || sessPenggunaId() == '81' || sessPenggunaId() == '23' || sessPenggunaId() == '33') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_pkketoll' || $page_name == 'surat/v_detail_pkketoll' ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/pkketoll') ?> ">
													Klaim Kas E-Toll
												</a>
											</li>
										<?php } ?>
										<?php if (isAdmin() || sessPenggunaId() == '82' || sessPenggunaId() == '107' || sessPenggunaId() == '81' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_ppa'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/ppa') ?> ">
													PPA
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '72' || sessPenggunaId() == '54' || sessPenggunaId() == '10' || sessPenggunaId() == '62' || sessPenggunaId() == '6' || sessPenggunaId() == '65' || sessPenggunaId() == '39' || sessPenggunaId() == '88' || sessPenggunaId() == '46' || sessPenggunaId() == '107' || sessPenggunaId() == '33' || sessPenggunaId() == '64' || sessPenggunaId() == '764' || sessPenggunaId() == '777') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_approval'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/approval') ?> ">
													Approval Harga
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || isGa() || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_pd'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/pd') ?> ">
													Permintaan Dinas Marketing
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || isGa() || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_pd_teknisi'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/pd_teknisi') ?> ">
													Permintaan Dinas Teknisi
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '771') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_pd_karyawan'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/pd_karyawan') ?> ">
													Permintaan Dinas Karyawan
												</a>
											</li>
										<?php } ?>
										<?php if (isGa() || sessPenggunaId() == '33' || sessPenggunaId() == '1' || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat/v_surat_list_sd'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat/show/list/sd') ?> ">
													Surat Dinas
												</a>
											</li>
										<?php } ?>
										<?php if (isGa() || sessPenggunaId() == '33' || sessPenggunaId() == '1' || sessPenggunaId() == '54' || sessPenggunaId() == '69' || sessPenggunaId() == '744') { ?>
											<li class="<?= in_array($page_name, ['surat/v_surat_list_izin_jam_kerja'])  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_part_two/show/list/sijk') ?> ">
													Izin Pada Jam Kerja
												</a>
											</li>
											<li class="<?= $page_name == 'surat/v_surat_list_izin_meninggalkan'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_part_two/show/list/simp') ?> ">
													Izin Meninggalkan Pekerjaan
												</a>
											</li>
											<li class="<?= $page_name == 'surat/v_surat_list_izin_cuti'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_part_two/show/list/cuti') ?> ">
													Cuti Tahunan
												</a>
											</li>
											<li class="<?= $page_name == 'surat/v_surat_list_meetingroom'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_part_two/show/list/meetingroom') ?> ">
													Ruang Meeting
												</a>
											</li>
										<?php } ?>


										<?php if (isGa() || sessPenggunaId() == '1' || sessPenggunaId() == '54' || sessPenggunaId() == '69' || sessPenggunaId() == '744' || sessPenggunaId() == 767) { ?>
											<li class="<?= in_array($page_name, ['surat/v_surat_list_izin_jam_kerja_sgm'])  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_part_two/show/list/sijk_sgm') ?> ">
													Izin Pada Jam Kerja SGM
												</a>
											</li>
											<li class="<?= $page_name == 'surat/v_surat_list_izin_meninggalkan_sgm'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_part_two/show/list/simp_sgm') ?> ">
													Izin Meninggalkan Pekerjaan SGM
												</a>
											</li>
											<li class="<?= $page_name == 'surat/v_surat_list_izin_cuti_sgm'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_part_two/show/list/cuti_sgm') ?> ">
													Cuti Tahunan SGM
												</a>
											</li>
										<?php } ?>

										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '15' || sessPenggunaId() == '33' || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat_new/v_list_kendaraan'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_new/show/list/kendaraan') ?> ">
													Penggunaan Kendaraan
												</a>
											</li>
										<?php } ?>

										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '69' || sessPenggunaId() == '744' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat_new/v_list_po'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_new/show/list/po') ?> ">
													Approval PO
												</a>
											</li>
										<?php } ?>

										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '107' || sessPenggunaId() == '749' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || sessPenggunaId() == '54' || sessPenggunaId() == '763') { ?>
											<li class="<?= $page_name == 'surat_new/v_list_appeks'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_new/show/list/appeks') ?> ">
													Approval Expedisi
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '69' || sessPenggunaId() == '744' || sessPenggunaId() == '54') { ?>
											<li class="<?= $page_name == 'surat_new/v_list_appdir'  ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_new/show/list/appdir') ?> ">
													Approval Director
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '54') { ?>
											<li class="<?= in_array($page_name, ['forwarder/v_detail_forwarder', 'forwarder/v_list_forwarder']) ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('forwarder/show/list') ?> ">
													Approval Forwarder
												</a>
											</li>
										<?php } ?>
										<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '33' || sessPenggunaId() == '64' || sessPenggunaId() == '777' || sessPenggunaId() == '23' || sessPenggunaId() == '54') { ?>
											<li class="<?= in_array($page_name, ['surat_new/v_list_app_pajak', 'surat_new/v_detail_app_pajak']) ? 'nav-active' : '' ?>">
												<a class="nav-link" href=" <?= base_url('surat_new/show/list/app_pajak') ?> ">
													Approval Faktur Pajak
												</a>
											</li>
										<?php } ?>


									</ul>
								</li>
							<?php } ?>
							<li class="nav-parent <?= in_array(
														$page_name,
														[
															'surat/v_pengajuan',
															'surat/v_pengajuan_spp',
															'surat/v_pengajuan_gc',
															'surat/v_pengajuan_pbok',
															'surat/v_pengajuan_kg',
															'surat/v_pengajuan_pkk',
															'surat/v_pengajuan_pkketoll',
															'surat/v_pengajuan_ppa',
															'surat/v_pengajuan_approval',
															'surat/v_pengajuan_pd',
															'surat/v_pengajuan_sd',
															'surat/v_pengajuan_pd_teknisi',
															'surat/v_pengajuan_sd_teknisi',
															'surat/v_pengajuan_pd_karyawan',
															'surat/v_pengajuan_izin_jam_kerja',
															'surat/v_aju_izin_jam_kerja',
															'surat/v_pengajuan_izin_meninggalkan',
															'surat/v_aju_izin_meninggalkan',
															'surat/v_pengajuan_izin_cuti',
															'surat/v_aju_izin_cuti',
															'surat/v_pengajuan_meetingroom',
															'surat/v_permintaan_meetingroom',
															'surat_new/v_kendaraan',
															'surat/v_aju_kendaraan',
															'surat_new/v_po',
															'surat_new/v_aju_po',
															'surat_new/v_appeks',
															'surat_new/v_aju_appeks',
															'surat/v_pengajuan_izin_jam_kerja_sgm',
															'surat/v_aju_izin_jam_kerja_sgm',
															'surat/v_pengajuan_izin_meninggalkan_sgm',
															'surat/v_aju_izin_meninggalkan_sgm',
															'surat/v_pengajuan_izin_cuti_sgm',
															'surat/v_aju_izin_cuti_sgm',
															'surat_new/v_appdir',
															'forwarder/v_aju_forwarder',
															'forwarder/v_all_forwarder',
															'surat_new/v_app_pajak',
															'kirim/v_kirim',
															'kirim/v_detail_kirim',
															'tagihan/v_form_pengajuan',
															'tagihan/v_my_tagihan',
															'spp/v_create_spp',
														]
													)  ? 'nav-expanded' : ''; ?>">
								<a class="nav-link" href="#">
									Daftar Pengajuan
								</a>
								<ul class="nav nav-children">
									<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '7' || sessPenggunaId() == '749' || sessPenggunaId() == '73' || isAdmin() || isHrd() || isAdminInventory() || sessPenggunaId() == '764' || sessPenggunaId() == '769') { ?>
										<li class="<?= in_array($page_name, ['tagihan/v_my_tagihan', 'tagihan/v_form_pengajuan']) ? 'nav-active' : '' ?>">
											<a class="nav-link" href="<?= base_url('tagihan/my_submission') ?>">
												Tagihan Ekspedisi
											</a>
										</li>
										<li class="<?= in_array($page_name, ['spp/v_create_spp']) ? 'nav-active' : '' ?>">
											<a class="nav-link" href="<?= base_url('spp/create') ?>">
												SPP Ekspedisi
											</a>
										</li>
									<?php } ?>

									<li class="<?= $page_name == 'surat/v_pengajuan' ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat/show/my_surat') ?> ">
											Pembiayaan Dinas
										</a>
									</li>
									<li class="<?= $page_name == 'surat/v_pengajuan_gc'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat/show/my_surat/gc') ?> ">
											Limit Grab
										</a>
									</li>
									<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '106' || sessPenggunaId() == '107' || sessPenggunaId() == '81') { ?>
										<li class="<?= $page_name == 'surat/v_pengajuan_spp'  ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat/show/my_surat/spp') ?> ">
												Permintaan Pembayaran
											</a>
										</li>
									<?php } ?>
									<li class="<?= $page_name == 'surat/v_pengajuan_pbok'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat/show/my_surat/pbok') ?> ">
											PBOK
										</a>
									</li>
									<li class="<?= $page_name == 'surat/v_pengajuan_kg'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat/show/my_surat/kg') ?> ">
											Kunjungan Gudang
										</a>
									</li>
									<li class="<?= $page_name == 'surat/v_pengajuan_pkk'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat/show/my_surat/pkk') ?> ">
											Klaim Kas
										</a>
									</li>
									<li class="<?= $page_name == 'surat/v_pengajuan_pkketoll'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat/show/my_surat/pkketoll') ?> ">
											Klaim Kas E-Toll
										</a>
									</li>
									<li class="<?= $page_name == 'surat/v_pengajuan_ppa'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat/show/my_surat/ppa') ?> ">
											PPA
										</a>
									</li>
									<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '23' || sessPenggunaId() == '33' || sessPenggunaId() == '71' || sessPenggunaId() == '72' || sessPenggunaId() == '77' || sessPenggunaId() == '15' || sessPenggunaId() == '54' || sessPenggunaId() == '10' || sessPenggunaId() == '62' || sessPenggunaId() == '6' || sessPenggunaId() == '65' || sessPenggunaId() == '39' || sessPenggunaId() == '88' ||  sessPenggunaId() == '85' || sessPenggunaId() == '46' || sessPenggunaId() == '64' || sessPenggunaId() == '777') { ?>
										<li class="<?= $page_name == 'surat/v_pengajuan_approval'  ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat/show/my_surat/approval') ?> ">
												Approval Harga
											</a>
										</li>
									<?php } ?>
									<?php if (sessPenggunaId() == '1' ||  sessPenggunaId() == '15' || sessPenggunaId() == '97' || sessPenggunaId() == '54' || sessPenggunaId() == '10' || sessPenggunaId() == '62' || sessPenggunaId() == '6' || sessPenggunaId() == '65' || sessPenggunaId() == '39' || sessPenggunaId() == '88' || sessPenggunaId() == '77' || isGa() || sessPenggunaId() == '15' || sessPenggunaId() == '105' || sessPenggunaId() == '108' || sessPenggunaId() == '717' || sessPenggunaId() == '742' || sessPenggunaId() == '729' || sessPenggunaId() == '745') { ?>
										<li class="<?= $page_name == 'surat/v_pengajuan_pd'  ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat/show/my_surat/pd') ?> ">
												Permintaan Dinas Marketing
											</a>
										</li>
									<?php } ?>

									<?php if (isGa() || sessPenggunaId() == '15' || sessPenggunaId() == '1' || sessPenggunaId() == '100' || sessPenggunaId() == '25' || sessPenggunaId() == '93' || sessPenggunaId() == '53' || sessPenggunaId() == '103' || sessPenggunaId() == '99' || sessPenggunaId() == '716'  || sessPenggunaId() == '14' || sessPenggunaId() == '736' || sessPenggunaId() == '755') { ?>
										<li class="<?= $page_name == 'surat/v_pengajuan_pd_teknisi' ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat/show/my_surat/pd_teknisi') ?> ">
												Permintaan Dinas Teknisi
											</a>
										</li>
									<?php } ?>
									<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '7' || sessPenggunaId() == '8' || sessPenggunaId() == '11' || sessPenggunaId() == '14' || sessPenggunaId() == '15' || sessPenggunaId() == '19' || sessPenggunaId() == '69' || sessPenggunaId() == '744' || sessPenggunaId() == '23' || sessPenggunaId() == '29' || sessPenggunaId() == '33' || sessPenggunaId() == '37' || sessPenggunaId() == '46' || sessPenggunaId() == '47' || sessPenggunaId() == '53' || sessPenggunaId() == '54' || sessPenggunaId() == '61' || sessPenggunaId() == '64' || sessPenggunaId() == '66' || sessPenggunaId() == '71' || sessPenggunaId() == '73' || sessPenggunaId() == '75' || sessPenggunaId() == '82' || sessPenggunaId() == '85' || sessPenggunaId() == '87' || sessPenggunaId() == '91' || sessPenggunaId() == '92' || sessPenggunaId() == '94' || sessPenggunaId() == '96' || sessPenggunaId() == '98' || sessPenggunaId() == '101' || sessPenggunaId() == '104' || sessPenggunaId() == '106' || sessPenggunaId() == '754' || sessPenggunaId() == '102' || sessPenggunaId() == '721' || sessPenggunaId() == '736' || sessPenggunaId() == '737' || sessPenggunaId() == '749' || sessPenggunaId() == '751' || sessPenggunaId() == '763' || sessPenggunaId() == '769' || sessPenggunaId() == '766' || sessPenggunaId() == '770' || sessPenggunaId() == '771' || sessPenggunaId() == '777') { ?>
										<li class="<?= $page_name == 'surat/v_pengajuan_pd_karyawan'  ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat/show/my_surat/pd_karyawan') ?> ">
												Permintaan Dinas Karyawan
											</a>
										</li>
									<?php } ?>
									<?php if (sessPenggunaId() == '721' || sessPenggunaId() == '743' || sessPenggunaId() == '767') { ?>

										<li class="<?= in_array($page_name, ['surat/v_pengajuan_izin_jam_kerja_sgm', 'surat/v_aju_izin_jam_kerja_sgm'])  ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat_part_two/show/permintaan/izin_jam_kerja_sgm') ?> ">
												Izin Pada Jam Kerja SGM
											</a>
										</li>
										<li class="<?= in_array($page_name, ['surat/v_pengajuan_izin_meninggalkan_sgm', 'surat/v_aju_izin_meninggalkan_sgm'])  ? 'nav-active' : '' ?>">
											<a class="nav-link" style="font-size: 12px;" href=" <?= base_url('surat_part_two/show/permintaan/izin_meninggalkan_sgm') ?> ">
												Izin Meninggalkan Pekerjaan SGM
											</a>
										</li>
										<li class="<?= in_array($page_name, ['surat/v_pengajuan_izin_cuti_sgm', 'surat/v_aju_izin_cuti_sgm']) ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat_part_two/show/permintaan/cuti_sgm') ?> ">
												Cuti Tahunan SGM
											</a>
										</li>
									<?php } else { ?>

										<li class="<?= in_array($page_name, ['surat/v_pengajuan_izin_jam_kerja', 'surat/v_aju_izin_jam_kerja'])  ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat_part_two/show/permintaan/izin_jam_kerja') ?> ">
												Izin Pada Jam Kerja
											</a>
										</li>
										<li class="<?= in_array($page_name, ['surat/v_pengajuan_izin_meninggalkan', 'surat/v_aju_izin_meninggalkan'])  ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat_part_two/show/permintaan/izin_meninggalkan') ?> ">
												Izin Meninggalkan Pekerjaan
											</a>
										</li>
										<li class="<?= in_array($page_name, ['surat/v_pengajuan_izin_cuti', 'surat/v_aju_izin_cuti']) ? 'nav-active' : '' ?>">
											<a class="nav-link" href=" <?= base_url('surat_part_two/show/permintaan/cuti') ?> ">
												Cuti Tahunan
											</a>
										</li>
									<?php } ?>
									<li class="<?= in_array($page_name, ['surat/v_pengajuan_meetingroom', 'surat/v_permintaan_meetingroom']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat_part_two/show/permintaan/meetingroom') ?> ">
											Ruang Meeting
										</a>
									</li>
									<li class="<?= in_array($page_name, ['surat_new/v_kendaraan', 'surat_new/v_aju_kendaraan']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat_new/show/permintaan/kendaraan') ?> ">
											Penggunaan Kendaraan
										</a>
									</li>
									<li class="<?= in_array($page_name, ['surat_new/v_po', 'surat_new/v_aju_po']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat_new/show/permintaan/po') ?> ">
											Approval PO
										</a>
									</li>
									<li class="<?= in_array($page_name, ['surat_new/v_appeks', 'surat_new/v_aju_appeks']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat_new/show/permintaan/appeks') ?> ">
											Approval Expedisi
										</a>
									</li>
									<li class="<?= in_array($page_name, ['surat_new/v_appdir']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat_new/show/permintaan/appdir') ?> ">
											Approval Director
										</a>
									</li>
									<li class="<?= in_array($page_name, ['forwarder/v_aju_forwarder', 'forwarder/v_all_forwarder']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('forwarder/show/permintaan') ?> ">
											Approval Forwarder
										</a>
									</li>
									<li class="<?= in_array($page_name, ['surat_new/v_app_pajak']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('surat_new/show/permintaan/app_pajak') ?> ">
											Approval Faktur Pajak
										</a>
									</li>
									<li class="<?= in_array($page_name, ['kirim/v_kirim', 'kirim/v_detail_kirim']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href=" <?= base_url('kirim/show/list') ?> ">
											Kirim Dokumen
										</a>
									</li>
								</ul>

							</li>
							<?php
							// if (isAdminInventory() || isGA() || sessPenggunaId() == '33' || sessPenggunaId() == '69' || sessPenggunaId() == '23' || sessPenggunaId() == '54') { 
							?>
							<li class="nav-parent <?= in_array($page_name, ['surat/v_lainnya', 'surat/v_aju_berita_acara', 'surat/v_surat_list_berita_acara', 'surat/v_pengajuan_berita_acara', 'surat/v_detail_berita_acara', 'surat/v_paklaring', 'surat/v_detail_paklaring', 'surat/v_aju_paklaring']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('surat/lainnya') ?>">
									<span>Surat Lainnya</span>
								</a>

								<?php
								//} 
								?>
							</li>
						</ul>
					</li>

					<!-- Pengajuan LTA inserted below Surat - Menyurat -->
					<li class="nav-parent <?= $this->uri->segment(1) == 'lta_pengajuan' ? 'nav-active nav-expanded' : '' ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-plane-departure" aria-hidden="true"></i>
							<span>Pengajuan LTA</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a class="nav-link" href="<?= base_url('lta_pengajuan/show/pengajuan/list') ?>">
									Buat Pengajuan
								</a>
							</li>
							<?php if (isAdmin() || isHrd() || (function_exists('isEksekutif') && isEksekutif()) || sessPenggunaId() == '106' || sessPenggunaId() == '107' || sessPenggunaId() == '777') { ?>
								<li>
									<a class="nav-link" href="<?= base_url('lta_pengajuan/show/persetujuan') ?>">
										Persetujuan Pengajuan
									</a>
								</li>
							<?php } ?>
							<li>
								<a class="nav-link" href="<?= base_url('lta_pengajuan/show/pengajuan') ?>">
									Pengajuan Saya
								</a>
							</li>
						</ul>
					</li>

					<li class="nav-parent <?= $this->uri->segment(1) == 'tiket' ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-ticket-alt" aria-hidden="true"></i>
							<span>Job Tiket</span>
						</a>
						<ul class="nav nav-children">


							<li class="<?= $page_name == 'tiket/v_dashboard_tiket'  ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('tiket/show/dashboard') ?>">
									Dashboard
								</a>
							</li>


							<?php if (isAdmin() || isKaryawan() || sessPenggunaId() == '1' || sessPenggunaId() == '72' || sessPenggunaId() == '15' || sessPenggunaId() == '74' || sessPenggunaId() == '54' || sessPenggunaId() == '85' || sessPenggunaId() == '110' || sessPenggunaId() == '107' || sessPenggunaId() == '754' || sessPenggunaId() == '755' || sessPenggunaId() == '751' || sessPenggunaId() == '777') { ?>
								<li class="<?= $page_name == 'tiket/v_tiket' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('tiket') ?>">
										Ticketing
									</a>
								</li>
							<?php } ?>

							<li class="<?= $page_name == 'tiket/v_my_tiket'  ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('tiket/show/my_tiket') ?>">
									My Ticket
								</a>
							</li>

							<?php if (sessPenggunaId() == '69' || sessPenggunaId() == '744' || sessPenggunaId() == '1' || isGa()) { ?>
								<li class="<?= $page_name == 'tiket/v_konfirmasi_ga'  ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('tiket/show/konfirmasi_ga') ?>">
										Konfirmasi GA
									</a>
								</li>
							<?php } ?>

							<?php if (sessPenggunaId() == '72' || sessPenggunaId() == '74' || sessPenggunaId() == '1') { ?>
								<li class="<?= $page_name == 'tiket/v_konfirmasi_cro'  ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('tiket/show/konfirmasi_cro') ?>">
										Konfirmasi CRO
									</a>
								</li>
							<?php } ?>

							<?php if (sessPenggunaId() == '755' || sessPenggunaId() == '1') { ?>
								<li class="<?= $page_name == 'tiket/v_konfirmasi_cro'  ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('tiket/show/konfirmasi_cro') ?>">
										Konfirmasi Head of Technician
									</a>
								</li>
							<?php } ?>

							<?php if (sessPenggunaId() == '29' || sessPenggunaId() == '70' || sessPenggunaId() == '1' || sessPenggunaId() == '81' || sessPenggunaId() == '777') { ?>
								<li class="<?= $page_name == 'tiket/v_konfiramsi_finance'  ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('tiket/show/konfirmasi_finance') ?>">
										Konfirmasi Finance
									</a>
								</li>
							<?php } ?>
						</ul>
					</li>

					<!-- Menu Training Teknisi -->
					<li class="nav-parent <?= $this->uri->segment(1) == 'training_teknisi' ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-book-reader" aria-hidden="true"></i>
							<span>Training Teknisi</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= $page_name == 'training_teknisi/v_my_training' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('training_teknisi/my_training') ?>">
									My Training
								</a>
							</li>
							<?php
							$canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
							if ($canManageTraining) {
							?>
								<li class="<?= $page_name == 'training_teknisi/v_manajemen_training' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('training_teknisi') ?>">
										Manajemen Data Training
									</a>
								</li>
								<li class="<?= $page_name == 'training_teknisi/v_tambah_training' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('training_teknisi/tambah') ?>">
										Tambah Data Training
									</a>
								</li>
							<?php } ?>
						</ul>
					</li>

					<!-- Menu Preventif Maintenance -->
					<?php if (isAdmin() || isKaryawan() || sessPenggunaId() == '72' || sessPenggunaId() == '74' || sessPenggunaId() == '1' || sessPenggunaId() == '75' || sessPenggunaId() == '84' || sessPenggunaId() == '15' || sessPenggunaId() == '754' || sessPenggunaId() == '755' || sessPenggunaId() == '751') {
						$canManagePM = isAdmin() || isCRO() || isGa() || sessPenggunaId() == '755';
					?>
						<li class="nav-parent <?= $this->uri->segment(1) == 'preventif-maintenance' ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-wrench" aria-hidden="true"></i>
								<span>Preventif Maintenance</span>
							</a>
							<ul class="nav nav-children">
								<?php if ($canManagePM) { ?>
									<li class="<?= $page_name == 'preventif_maintenance/v_pm_list' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('preventif-maintenance') ?>">
											Daftar PM
										</a>
									</li>
								<?php } ?>
								<li class="<?= $page_name == 'preventif_maintenance/v_my_pm' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('preventif-maintenance/show/my_pm') ?>">
										My PM
									</a>
								</li>
								<?php if ($canManagePM) { ?>
									<li class="<?= $page_name == 'preventif_maintenance/v_pm_form' && $this->uri->segment(2) == 'add' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('preventif-maintenance/add') ?>">
											Tambah PM
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>

					<!-- Menu Maintenance Aset IT -->
					<?php
					$isITStaff = isAdmin() || isCRO() || isGa() || sessPenggunaId() == '755' || sessPenggunaId() == '769' || sessPenggunaId() == '107';
					?>
					<li class="nav-parent <?= $this->uri->segment(1) == 'it_maintenance' ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-laptop" aria-hidden="true"></i>
							<span>Maintenance Aset IT</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= $page_name == 'it_maintenance/v_open_ticket' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('it_maintenance/open_ticket') ?>">
									Open Ticket
								</a>
							</li>
							<li class="<?= $page_name == 'it_maintenance/v_my_ticket' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('it_maintenance/my_ticket') ?>">
									My Ticket
								</a>
							</li>
							<?php if ($isITStaff) { ?>
								<li class="<?= $page_name == 'it_maintenance/v_data_ticket' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('it_maintenance/data_ticket') ?>">
										Data Ticket
									</a>
								</li>
								<li class="<?= $page_name == 'it_maintenance/v_data_maintenance' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('it_maintenance/data_maintenance') ?>">
										Data maintenance Aset
									</a>
								</li>
								<li class="<?= $page_name == 'it_maintenance/v_config_notif' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('it_maintenance/config_notif') ?>">
										Config Notifikasi IT
									</a>
								</li>
							<?php } ?>
						</ul>
					</li>

					<!-- Menu Surat Penawaran Harga (SPH) -->
					<?php if (isAdmin() || isCRO() || sessPenggunaId() == '72' || sessPenggunaId() == '74' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '84' || sessPenggunaId() == '33' || sessPenggunaId() == '754' || sessPenggunaId() == '107' || sessPenggunaId() == '764') { ?>
						<li class="nav-parent <?= $this->uri->segment(1) == 'sph' ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
								<span>Surat Penawaran Harga</span>
							</a>
							<ul class="nav nav-children">
								<li class="<?= in_array($page_name, ['sph/v_sph_list']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('sph') ?>">
										Daftar SPH
									</a>
								</li>
								<li class="<?= in_array($page_name, ['sph/v_sph_form']) && $this->uri->segment(2) == 'tambah' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('sph/tambah') ?>">
										Buat SPH Baru
									</a>
								</li>
								<li class="<?= in_array($page_name, ['sph/v_sph_keterangan']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('sph/keterangan') ?>">
										Master Keterangan
									</a>
								</li>
								<li class="<?= in_array($page_name, ['sph/v_sph_laporan']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('sph/laporan') ?>">
										Laporan SPH
									</a>
								</li>
							</ul>
						</li>
					<?php } ?>

					<!-- Menu Sistem PO (Penerimaan & Kroscek PO) -->
					<li class="nav-parent <?= ($this->uri->segment(1) == 'po_system' && $this->uri->segment(2) != 'notif_config') ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-file-signature" aria-hidden="true"></i>
							<span>Penerimaan & Kroscek PO</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= (in_array($page_name, ['po/v_po_list']) || ($this->uri->segment(1) == 'po_system' && $this->uri->segment(2) == '')) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('po_system') ?>">
									Daftar PO Masuk
								</a>
							</li>
							<li class="<?= (in_array($page_name, ['po/v_po_form']) && $this->uri->segment(2) == 'tambah') ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('po_system/tambah') ?>">
									Input PO Baru
								</a>
							</li>
						</ul>
					</li>

					<!-- Menu Config Notifikasi PO (Admin Only) -->
					<?php if (isAdmin()) { ?>
						<li class="<?= ($this->uri->segment(1) == 'po_system' && $this->uri->segment(2) == 'notif_config') ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('po_system/notif_config') ?>">
								<i class="fas fa-cogs" aria-hidden="true"></i>
								<span>Config Notifikasi PO</span>
							</a>
						</li>
					<?php } ?>

					<?php if (isAdmin() || sessPenggunaId() == '759' || sessPenggunaId() == '1') { ?>
						<li class="<?= $this->uri->segment(1) == 'Tagihan_Config' ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('Tagihan_config') ?>">
								<i class="fas fa-cogs" aria-hidden="true"></i>
								<span>Config Approval Tagihan</span>
							</a>
						</li>
						<li class="<?= in_array($page_name, ['spp/v_config_spp']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('spp/config') ?>">
								<i class="fas fa-cogs" aria-hidden="true"></i>
								<span>Config Approval SPP</span>
							</a>
						</li>
					<?php } ?>

					<li>
						<a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
							<i class="fas fa-sign-out-alt text-danger" aria-hidden="true"></i>
							<span>Logout</span>
						</a>
					</li>
				</ul>
			</nav>

		</div>
	</div>
</aside>

<script>
	document.getElementById("switch_menu").onchange = function() {
		if (this.value !== "") {
			window.location.href = this.value;
		}
	};
</script>
