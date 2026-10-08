<aside id="sidebar-left" class="sidebar-left">
	<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
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
								<option value="<?= base_url('dashboard_helpdesk') ?>">Helpdesk</option>
								<option value="<?= base_url('dashboard_inventory') ?>">Inventory</option>
								<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '75' || sessPenggunaId() == '755' || sessPenggunaId() == '64') { ?>
									<option value="<?= base_url('dashboard_marketing') ?>" selected>Marketing</option>
								<?php } ?>
								<?php if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '84' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '736' || sessPenggunaId() == '754' || sessPenggunaId() == '751' || sessPenggunaId() == '25' || sessPenggunaId() == '757' || sessPenggunaId() == '770') { ?>
									<option value="<?= base_url('dashboard_visilab') ?>">Visilab</option>
								<?php } ?>
							
								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('dashboard_accounting') ?>" <?= $switch == 'accounting' ? 'selected' : '' ?>>Accounting & Tax</option>
								<?php } ?>
							</select>
						</span>
					</li>

					<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '755') { ?>
						<li class="nav-parent <?= in_array($page_name, ['marketing/v_calonpelanggan', 'marketing/v_pelangganan']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-database" aria-hidden="true"></i>
								<span>Master Data Customer</span>
							</a>
							<ul class="nav nav-children">
								<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '755') { ?>
									<li class="<?= $this->uri->segment(1) == 'calonpelanggan'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('calonpelanggan') ?>">
											Data Calon Pelanggan
										</a>
									</li>
									<li class="<?= $this->uri->segment(1) == 'pelangganan'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('pelangganan') ?>">
											Data Pelanggan
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
					<?php /*if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa()) { ?>
						<li class="<?= in_array($page_name, ['v_funnels']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('funnel') ?>">
								<i class="fas fa-filter" aria-hidden="true"></i>
								<span>Funnel</span>
							</a>
						</li>
						<?php } */ ?>

					<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '755') { ?>
						<li class="<?= in_array($page_name, ['marketing/v_funnels_beta']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('funnel/show') ?>">
								<i class="fas fa-filter" aria-hidden="true"></i>
								<span>Funnel</span>
							</a>
						</li>
					<?php } ?>

					<?php if (isAdmin() || isHrd() || isCRO() || isGa() || isTeamMarketing() || sessPenggunaId() == '755') { ?>
						<li class="<?= in_array($page_name, ['marketing/v_pengajuan_fpp', 'marketing/v_aju_fpp', 'marketing/v_detail_fpp']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('fpp/show/permintaan/penawaran') ?>">
								<i class="fas fa-digital-tachograph" aria-hidden="true"></i>
								<span>Form Permintaan Penawaran</span>
							</a>
						</li>
					<?php } ?>

					<?php if (isAdmin() || isHrd() || isCRO() || isGa() || isTeamMarketing() || sessPenggunaId() == '755' || sessPenggunaId() == '64') { ?>
						<li class="<?= in_array($page_name, ['marketing/v_pengajuan_presentase', 'marketing/v_aju_presentase', 'marketing/v_detail_presentase']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('fpp/show/permintaan/presentase') ?>">
								<i class="fas fa-digital-tachograph" aria-hidden="true"></i>
								<span>Form Presentase</span>
							</a>
						</li>
					<?php } ?>

					<?php if (isAdmin() || isHrd() || isCRO() || isGa() || isTeamMarketing() || sessPenggunaId() == '755') { ?>
						<li class="<?= in_array($page_name, ['marketing/v_pengajuan_trouble', 'marketing/v_aju_trouble', 'marketing/v_detail_trouble']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('fpp/show/permintaan/trouble') ?>">
								<i class="fas fa-digital-tachograph" aria-hidden="true"></i>
								<span>Form Trouble</span>
							</a>
						</li>
					<?php } ?>
					<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || sessPenggunaId() == '755') { ?>
						<li class="nav-parent <?= in_array($page_name, ['marketing/v_target_list', 'marketing/v_target_dashboard', 'marketing/v_target_bulanan']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-bullseye" aria-hidden="true"></i>
								<span>Target Penjualan</span>
							</a>
							<ul class="nav nav-children">
								<li class="<?= $page_name == 'marketing/v_target_list' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('marketing_target') ?>">
										Funnel & Target Prospek
									</a>
								</li>
								<li class="<?= $page_name == 'marketing/v_target_dashboard' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('marketing_target/dashboard') ?>">
										Dashboard Rekap Target
									</a>
								</li>
								<?php if (isAdmin() || isHrd() || isEksekutif()) { ?>
									<li class="<?= $page_name == 'marketing/v_target_bulanan' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('marketing_target/target_bulanan') ?>">
											Setting Target Bulanan
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>

					<li
						class="nav-parent <?= in_array($page_name, ['v_brosur', 'v_price_list', 'kalkulator/v_kalkulator', 'kalkulator/v_kalkulator_gov', 'v_pop_penjualan_produk', 'v_kalkulasi_cicilan', 'v_detail_package_mesin', 'inv_lain2/v_video_tutorial']) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-box"></i>
							<span>Produk</span>
						</a>
						<ul class="nav nav-children">
							<!--<li class="<?= $page_name == 'v_brosur' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('brosur') ?>">
									Brosur
								</a>
							</li>-->
							<li class="<?= $page_name == 'v_detail_package_mesin' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('detail_package_mesin') ?>">
									Detail Package Mesin
								</a>
							</li>
							<li class="<?= $page_name == 'v_price_list' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('price_list') ?>">
									Price List
								</a>
							</li>
							<li class="<?= $page_name == 'kalkulator/v_kalkulator' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('kalkulator') ?>">
									Kalkulator Price List Swasta
								</a>
							</li>
							<li class="<?= $page_name == 'kalkulator/v_kalkulator_gov' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('kalkulator/gov') ?>">
									Kalkulator Price List Government
								</a>
							</li>
							<li class="<?= $page_name == 'v_pop_penjualan_produk' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('pop_penjualan_produk') ?>">
									Populasi Penjualan Produk
								</a>
							</li>
						</ul>
					</li>
					<!--
						<li class="<?= in_array($page_name, ['marketing/v_pengajuan_fpp_marketing', 'marketing/v_aju_fpp', 'marketing/v_detail_fpp']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('fpp/show/marketing/penawaran') ?>">
								<i class="fas fa-digital-tachograph" aria-hidden="true"></i>
								<span>Form Permintaan Penawaran (FPP)</span>
							</a>
						</li>
						-->

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