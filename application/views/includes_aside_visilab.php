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
								<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '75' || sessPenggunaId() == '755') { ?>
									<option value="<?= base_url('dashboard_marketing') ?>" selected>Marketing</option>
								<?php } ?>
								<option value="<?= base_url('dashboard_marketing') ?>">Marketing</option>
								<?php if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '736' || sessPenggunaId() == '754' || sessPenggunaId() == '751' || sessPenggunaId() == '757' || sessPenggunaId() == '765' || sessPenggunaId() == '770') { ?>
									<option value="<?= base_url('dashboard_visilab') ?>" selected>Visilab</option>
								<?php } ?>
							
								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('acc_pemasok') ?>" <?= $switch == 'accounting' ? 'selected' : '' ?>>Accounting & Tax</option>
								<?php } ?>
							</select>
						</span>
					</li>

					<li class="nav-parent <?= in_array($page_name, ['visilab/v_alat', 'visilab/v_suhu']) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-database" aria-hidden="true"></i>
							<span>Master Data</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= in_array($page_name, ['visilab/v_alat']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('visilab/show/list/alat') ?>">
									Data Alat
								</a>
							</li>
							<li class="<?= in_array($page_name, ['visilab/v_suhu']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('visilab/show/list/suhu') ?>">
									Suhu
								</a>
							</li>
						</ul>
					</li>

					<li class="<?= in_array($page_name, ['visilab/v_jadwal_list']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('visilab_jadwal') ?>">
							<i class="fas fa-calendar-week" aria-hidden="true"></i>
							<span>Jadwal Ukes & Upar</span>
						</a>
					</li>

					<li class="nav-parent <?= in_array($page_name, ['pengujian/v_ukes', 'pengujian/v_detail_ukes', 'pengujian/v_upar', 'pengujian/v_detail_upar']) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-chart-bar" aria-hidden="true"></i>
							<span>Pengujian</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= in_array($page_name, ['pengujian/v_ukes', 'pengujian/v_detail_ukes']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('pengujian/show/permintaan/ukes') ?>">
									Uji Kesesuaian
								</a>
							</li>
							<li class="<?= in_array($page_name, ['pengujian/v_upar', 'pengujian/v_detail_upar']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('pengujian/show/permintaan/upar') ?>">
									Uji Paparan
								</a>
							</li>
						</ul>
					</li>
					<li class="<?= in_array($page_name, ['visilab/v_detail_laporan_stok', 'visilab/v_aju_laporan_stok', 'visilab/v_list_laporan_stok']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('visilab/show/list/laporan_stok') ?>">
							<i class="fas fa-clipboard-list" aria-hidden="true"></i>
							<span>Laporan Stok Alat</span>
						</a>
					</li>

					<li class="<?= in_array($page_name, ['visilab/v_detail_approval_visilab', 'visilab/v_aju_approval_visilab', 'visilab/v_list_approval_visilab']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('visilab/show/list/approval_harga') ?>">
							<i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
							<span>Approval Harga</span>
						</a>
					</li>

					<li class="<?= in_array($page_name, ['visilab/v_detail_app_po', 'visilab/v_aju_app_po', 'visilab/v_list_app_po']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('visilab/show/list/approval_po') ?>">
							<i class="fas fa-clipboard-check" aria-hidden="true"></i>
							<span>Approval PO</span>
						</a>
					</li>


					<li class="<?= in_array($page_name, ['visilab/v_purchase_order', 'visilab/v_detail_po', 'visilab/v_aju_po']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('po_visilab/show/permintaan/purchase_order') ?>">
							<i class="fas fa-shopping-cart" aria-hidden="true"></i>
							<span>Pengajuan PO Aset Visilab</span>
						</a>
					</li>


					<li class="<?= in_array($page_name, ['visilab/v_detail_berita_acara', 'visilab/v_aju_berita_acara', 'visilab/v_list_berita_acara']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('visilab/show/list/berita_acara') ?>">
							<i class="fas fa-file-signature" aria-hidden="true"></i>
							<span>Berita Acara</span>
						</a>
					</li>



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