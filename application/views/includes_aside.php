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
								<option value="<?= base_url('dashboard') ?>" selected>Home</option>
								<option value="<?= base_url('dashboard_dokumen') ?>">Dokumen Perusahaan</option>
								<option value="<?= base_url('dashboard_kepegawaian') ?>">Kepegawaian</option>
								<option value="<?= base_url('dashboard_helpdesk') ?>">Helpdesk</option>
								<option value="<?= base_url('dashboard_inventory') ?>">Inventory</option>
								<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '75' || sessPenggunaId() == '755' || sessPenggunaId() == '64') { ?>
									<option value="<?= base_url('dashboard_marketing') ?>">Marketing</option>
								<?php } ?>
								<?php if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '84' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '736' || sessPenggunaId() == '754' || sessPenggunaId() == '751' || sessPenggunaId() == '25' || sessPenggunaId() == '757' || sessPenggunaId() == '765' || sessPenggunaId() == '770') { ?>
									<option value="<?= base_url('dashboard_visilab') ?>">Visilab</option>
								<?php } ?>

								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('dashboard_accounting') ?>" <?= $switch == 'accounting' ? 'selected' : '' ?>>Accounting & Tax</option>
								<?php } ?>
							</select>
						</span>
					</li>

					<li class="<?= in_array($page_name, ['dashboard']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('Dashboard') ?>">
							<i class="fas fa-tachometer-alt" aria-hidden="true"></i>
							<span>Dashboard</span>
						</a>
					</li>

					<li class="<?= $page_name == 'v_pengguna_detail' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('pengguna/show/detail_pengguna/' . encrypt(sessPenggunaId())) ?>">
							<i class="fas fa-user " aria-hidden="true"></i>
							<span>Data Karyawan</span>
						</a>
					</li>
					<!--<li class="<?= $page_name == 'v_do_absen' ? 'nav-active' : '' ?>">-->
					<!--	<a class="nav-link" href="<?= base_url('absensi/show/do_absen/' . encrypt(sessPenggunaId())) ?>">-->
					<!--		<i class="far fa-calendar-check" aria-hidden="true"></i>-->
					<!--		<span>Absen</span>-->
					<!--	</a>-->
					<!--</li>-->

					<li class="nav-parent <?= in_array($page_name, ['v_do_absen', 'v_do_absen2', 'v_do_absen_dinas', 'v_do_absen_dinas2', 'v_wfa_pengguna']) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="far fa-calendar-check" aria-hidden="true"></i>
							<span>Absen</span>
						</a>
						<ul class="nav nav-children">

							<?php if (isset($is_dinas) && $is_dinas): ?>
								<li class="<?= $page_name == 'v_do_absen_dinas2' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('absensi/show/do_absen_dinas2/' . encrypt(sessPenggunaId())) ?>">
										<span>Absen Dinas</span>
									</a>
								</li>
							<?php else: ?>
								<li class="<?= $page_name == 'v_do_absen2' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('absensi/show/do_absen2/' . encrypt(sessPenggunaId())) ?>">
										<span>Absen Kantor</span>
									</a>
								</li>
							<?php endif; ?>

							<?php if (isAdmin() || isHrd()) { ?>
								<li class="<?= $page_name == 'v_wfa_pengguna' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('absensi_config/manage_pengguna_wfa') ?>">
										<span>Manajemen WFA/WFH</span>
									</a>
								</li>
							<?php } ?>


							<!--<li class="<?= $page_name == 'v_do_absen2' ? 'nav-active' : '' ?>">
						            <a class="nav-link" href="<?= base_url('absensi/show/do_absen2/' . encrypt(sessPenggunaId())) ?>">
							            <span>Absen Kantor</span>
						            </a>
				            	</li>

				            	<li class="<?= $page_name == 'v_do_absen_dinas2' ? 'nav-active' : '' ?>">
						            <a class="nav-link" href="<?= base_url('absensi/show/do_absen_dinas2/' . encrypt(sessPenggunaId())) ?>">
							            <span>Absen Dinas</span>
						            </a>
				            	</li>-->

							<?php ?>
						</ul>
					</li>
					<!--				    <li class="nav-parent <?= in_array($page_name, ['v_suratpimp', 'v_suratpijk']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="far fa-calendar-times" style="color:red" aria-hidden="true"></i>
								<span>Izin</span>
							</a>
							<ul class="nav nav-children">
								<li class="<?= $page_name == 'v_suratpimp' ? 'nav-active' : '' ?>">
						            <a class="nav-link" href="<?= base_url('absensi/show/izinfull/' . encrypt(sessPenggunaId())) ?>">
							            <span>Izin Meninggalkan Pekerjaan</span>
						            </a>
				            	</li>
				            	<li class="<?= $page_name == 'v_suratpijk' ? 'nav-active' : '' ?>">
						            <a class="nav-link" href="<?= base_url('absensi/show/izinjamkerja/' . encrypt(sessPenggunaId())) ?>">
							            <span>Izin Jam Kerja</span>
						            </a>
				            	</li>
				            	
								<?php ?>
							</ul>
				    </li>
-->
					<!--<li class="nav-parent <?= in_array($page_name, ['v_stock_gudang', 'v_penerimaan_barang', 'v_pengeluaran_barang', 'v_penerimaan_barang_form', 'v_pengeluaran_barang_form', 'v_pengeluaran_barang_form_invoice']) ? 'nav-active nav-expanded' : ''; ?>">-->
					<!--			<a class="nav-link" href="#">-->
					<!--				<i class="far fa-calendar-check" aria-hidden="true"></i>-->
					<!--				<span>Uji Coba Absen</span>-->
					<!--			</a>-->
					<!--			<ul class="nav nav-children">-->
					<!--				<li class="<?= $page_name == 'v_do_absen' ? 'nav-active' : '' ?>">-->
					<!--		            <a class="nav-link" href="<?= base_url('absensi/show/do_absen/' . encrypt(sessPenggunaId())) ?>">-->
					<!--			            <span>Absen Kantor</span>-->
					<!--		            </a>-->
					<!--            	</li>-->
					<!--				<?php if (isAdminInventory() || isStafAdmin() || isMarketing()) { ?>-->
					<!--				<li class="<?= in_array($page_name, ['v_penerimaan_barang', 'v_penerimaan_barang_form']) ? 'nav-active' : '' ?>">-->
					<!--					<a class="nav-link" href="<?= base_url('penerimaan_barang') ?>">-->
					<!--						Absen Dinas-->
					<!--					</a>-->
					<!--				</li>-->
					<!--				<?php } ?>-->
					<!--			</ul>-->
					<!--</li>-->

					<li class="<?= $page_name == 'v_rekap_absensi' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('absensi/show/rekap_absensi/' . encrypt(sessPenggunaId())) ?>">
							<i class="fas fa-digital-tachograph " aria-hidden="true"></i>
							<span>Rekap Absensi </span>
						</a>
					</li>

					<li class="<?= $page_name == 'voting/v_voting' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('voting') ?>">
							<i class="icons fas fa-poll" aria-hidden="true"></i>
							<span>Voting</span>
						</a>
					</li>


					<li class="<?= $page_name == 'v_bukutamu' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('BukuTamu/dataBukutamu') ?>">
							<i class="fas fa-digital-tachograph" aria-hidden="true"></i>
							<span>Buku Tamu</span>
						</a>
					</li>

					<li class="<?= $page_name == 'v_announcement_list' ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('Announcement/daftar') ?>">
							<i class="fas fa-bullhorn" aria-hidden="true"></i>
							<span>Pengumuman</span>
						</a>
					</li>

					<?php if (in_array((string)sessPenggunaId(), ['33', '23', '69', '744', '58', '54', '107'])) { ?>
						<li>
							<a class="nav-link" href="<?= base_url('audit/index.html') ?>">
								<i class="fas fa-file-invoice-dollar text-primary" aria-hidden="true"></i>
								<span>Audit PBOK & Kinerja</span>
							</a>
						</li>
					<?php } ?>


					<!--<li class="<?= $page_name == 'v_slip_gaji' ? 'nav-active' : '' ?>">-->
					<!--	<a class="nav-link" href="<?= base_url('slip_gaji') ?>">-->
					<!--		<i class="fas fa-file-invoice-dollar"  aria-hidden="true"></i>-->
					<!--		<span>Slip Gaji</span>-->
					<!--	</a>-->
					<!--</li> -->



					<li class="nav-group-label">Lain Lain</li>
					<?php if (isAdmin()) { ?>
						<li class="<?= $page_name == 'v_whatsapp_config' ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('whatsapp_config') ?>">
								<i class="fab fa-whatsapp text-success" aria-hidden="true"></i>
								<span>WhatsApp API Config</span>
							</a>
						</li>
						<li class="<?= $page_name == 'v_db_backup' ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('db_backup') ?>">
								<i class="fas fa-database text-warning" aria-hidden="true"></i>
								<span>Database Backup</span>
							</a>
						</li>
						<li class="<?= $page_name == 'v_export_customer' ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('customer/export_db') ?>">
								<i class="fas fa-file-excel text-info" aria-hidden="true"></i>
								<span>Export Database Customer</span>
							</a>
						</li>
						<li class="<?= $page_name == 'log' || $this->uri->segment(1) == 'log' ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('log') ?>">
								<i class="fas fa-list-alt " aria-hidden="true"></i>
								<span>Log Aktivitas</span>
							</a>
						</li>
					<?php } ?>
					<li>
						<a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
							<i class="fas fa-sign-out-alt text-danger" aria-hidden="true"></i>
							<span>Logout</span>
						</a>
					</li>
					<li class="sidebar-spacer d-md-none" style="height: 150px; list-style: none;"></li>
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