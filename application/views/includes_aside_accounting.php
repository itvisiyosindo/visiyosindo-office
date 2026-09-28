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
									<option value="<?= base_url('dashboard_marketing') ?>">Marketing</option>
								<?php } ?>
								<?php if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '84' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '736' || sessPenggunaId() == '754' || sessPenggunaId() == '751' || sessPenggunaId() == '25' || sessPenggunaId() == '765' || sessPenggunaId() == '755' || sessPenggunaId() == '770') { ?>
									<option value="<?= base_url('dashboard_visilab') ?>">Visilab</option>
								<?php } ?>
								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('acc_pemasok') ?>" selected>Accounting & Tax</option>
								<?php } ?>
							</select>
						</span>
					</li>

					<li class="<?= in_array($page_name, ['acc_pemasok/v_data_pemasok', 'acc_pemasok/v_detail_pemasok']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('acc_pemasok') ?>">
							<i class="fas fa-truck-loading" aria-hidden="true"></i>
							<span>Data Pemasok</span>
						</a>
					</li>

					<li class="<?= in_array($page_name, ['acc_pemasok/v_master_data']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('acc_master_pemasok') ?>">
							<i class="fas fa-database" aria-hidden="true"></i>
							<span>Master Data Pemasok</span>
						</a>
					</li>

					<li class="<?= $page_name == 'acc_pemasok/v_sk_ketentuan' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('acc_sk_ketentuan') ?>">
							<i class="fas fa-file-contract" aria-hidden="true"></i>
							<span>Data Dokumen SK & Ketentuan</span>
						</a>
					</li>

					<li class="<?= $page_name == 'acc_pemasok/v_bukti_lapor_pajak' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('acc_bukti_lapor_pajak') ?>">
							<i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
							<span>Data Bukti Lapor Pajak</span>
						</a>
					</li>

					<li class="<?= $page_name == 'acc_pemasok/v_laporan_keuangan' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('acc_laporan_keuangan') ?>">
							<i class="fas fa-balance-scale" aria-hidden="true"></i>
							<span>Data Laporan Keuangan</span>
						</a>
					</li>

					<li>
						<a class="nav-link text-danger border-top" href="<?= base_url('auth/logout') ?>">
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
