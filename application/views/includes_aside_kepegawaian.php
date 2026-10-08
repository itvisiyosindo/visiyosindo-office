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
								<option value="<?= base_url('dashboard_kepegawaian') ?>" selected>Kepegawaian</option>
								<option value="<?= base_url('dashboard_helpdesk') ?>">Helpdesk</option>
								<option value="<?= base_url('dashboard_inventory') ?>">Inventory</option>
								<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '75' || sessPenggunaId() == '755') { ?>
									<option value="<?= base_url('dashboard_marketing') ?>">Marketing</option>
								<?php } ?>
								<?php if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '84' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '736' || sessPenggunaId() == '754' || sessPenggunaId() == '751' || sessPenggunaId() == '25' || sessPenggunaId() == '757' || sessPenggunaId() == '760' || sessPenggunaId() == '770') { ?>
									<option value="<?= base_url('dashboard_visilab') ?>">Visilab</option>
								<?php } ?>
							
								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('dashboard_accounting') ?>" <?= $switch == 'accounting' ? 'selected' : '' ?>>Accounting & Tax</option>
								<?php } ?>
							</select>
						</span>
					</li>

					<li class="<?= in_array($page_name, ['dashboard_kepegawaian']) ? 'nav-active' : '' ?>">
						<a class="nav-link" href="<?= base_url('Dashboard_kepegawaian') ?>">
							<i class="fas fa-tachometer-alt" aria-hidden="true"></i>
							<span>Dashboard</span>
						</a>
					</li>

					<?php if (isAdmin() || isHrd() || isGa() || sessPenggunaId() == 92) { ?>
						<li class="<?= in_array($page_name, ['kepegawaian/v_sisa_cuti_karyawan']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('dashboard_kepegawaian/sisa_cuti_karyawan') ?>">
								<i class="fas fa-calendar-check" aria-hidden="true"></i>
								<span>Daftar Sisa Cuti</span>
							</a>
						</li>
					<?php } ?>

					<?php if (isAdmin() || isHrd() || isGa() || sessPenggunaId() == 92 || sessPenggunaId() == 764) { ?>
						<li class="nav-parent <?= in_array($page_name, ['v_pengguna', 'v_absensi_config', 'kategori_Tiket/v_kategori', 'divisi_Pengguna/v_divisi', 'v_pengguna_detail', 'v_sk']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-database" aria-hidden="true"></i>
								<span>Master Data</span>
							</a>
							<ul class="nav nav-children">

								<li class="<?= in_array($page_name, ['v_pengguna', 'v_pengguna_detail']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('pengguna') ?>">
										Data Karyawan
									</a>
								</li>

								<?php if (isAdmin() || isHrd() || isGa()) { ?>
									<li class="<?= $page_name == 'v_sk' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('sk') ?>">
											SK Penghasilan
										</a>
									</li>
									<li class="<?= $page_name == 'v_absensi_config' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('absensi_config') ?>">
											Konfigurasi Absen
										</a>
									</li>
									<li class="<?= $this->uri->segment(1) == 'divisi_Pengguna'  ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('divisi_Pengguna') ?>">
											Divisi Pengguna
										</a>
									</li>
								<?php } ?>

							</ul>
						</li>
						<li class="<?= in_array($page_name, ['v_reminder']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('reminder') ?>">
								<i class="fas fa-bell" aria-hidden="true"></i>
								<span>Reminder Karyawan</span>
							</a>
						</li>

						<?php if (isAdmin() || isHrd() || isGa() || sessPenggunaId() == 764) { ?>
							<li class="nav-parent <?= in_array($page_name, ['salary/v_salary', 'salary/v_salary_tidak_tetap', 'salary/v_salary_thr', 'salary/v_detail_salary', 'salary/v_salary_freelance', 'salary/v_detail_salary_tt', 'salary/v_bonus', 'salary/v_salary_resign', 'salary/v_salary_resign_pokok']) ? 'nav-active nav-expanded' : ''; ?>">
								<a class="nav-link" href="#">
									<i class="fas fa-money-bill" aria-hidden="true"></i>
									<span>Salary</span>
								</a>
								<ul class="nav nav-children">
									<li class="<?= $this->uri->segment(1) == 'salary' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('salary') ?>">
											Salary Tetap
										</a>
									</li>
									<li class="<?= $this->uri->segment(1) == 'salary_tidak_tetap' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('salary_tidak_tetap') ?>">
											Salary Tidak Tetap
										</a>
									</li>
									<li class="<?= $this->uri->segment(1) == 'salary_resign' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('salary_resign') ?>">
											Salary T. Tetap Karyawan Resign
										</a>
									</li>
									<li class="<?= $this->uri->segment(1) == 'salary_resign_pokok' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('salary_resign_pokok') ?>">
											Salary Tetap Karyawan Resign
										</a>
									</li>
									<li class="<?= $this->uri->segment(1) == 'salary_freelance' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('salary_freelance') ?>">
											Salary Freelance
										</a>
									</li>
									<li class="<?= $this->uri->segment(1) == 'salary_thr' ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('salary_thr') ?>">
											THR
										</a>
									</li>
									<li class="<?= in_array($page_name, ['salary/v_bonus']) ? 'nav-active' : '' ?>">
										<a class="nav-link" href="<?= base_url('bonus') ?>">
											Bonus Tahunan
										</a>
									</li>
								</ul>
							</li>
						<?php } ?>

					<?php } ?>

					<li class="nav-parent <?= in_array($page_name, ['training/v_training', 'training/v_list_training', 'training/v_detail', 'training/v_upload_sertifikat']) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-graduation-cap" aria-hidden="true"></i>
							<span>Training & Development</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= in_array($page_name, ['training/v_list_training']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('training/show/training_list') ?>">
									Persetujuan
								</a>
							</li>
							<li class="<?= in_array($page_name, ['training/v_training']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('training/show/training') ?>">
									Pengajuan
								</a>
							</li>
							<li class="<?= in_array($page_name, ['training/v_upload_sertifikat']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('training/show/upload_sertifikat') ?>">
									Upload Sertifikat
								</a>
							</li>
						</ul>
					</li>

					<li class="nav-parent <?= in_array($page_name, ['wfa/v_pengajuan_wfa', 'wfa/v_persetujuan_wfa', 'wfa/v_detail_wfa', 'wfa/v_admin_jumat_wfa']) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-laptop" aria-hidden="true"></i>
							<span>Pengajuan WFA</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= in_array($page_name, ['wfa/v_pengajuan_wfa']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('wfa_pengajuan/show/pengajuan') ?>">
									Ajukan WFA
								</a>
							</li>
							<li class="<?= in_array($page_name, ['wfa/v_persetujuan_wfa', 'wfa/v_detail_wfa']) ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('wfa_pengajuan/show/persetujuan') ?>">
									Persetujuan WFA
								</a>
							</li>
							<?php if (isAdmin() || isHrd() || sessPenggunaId() == 58 || sessPenggunaId() == 69 || sessPenggunaId() == 744) { ?>
								<li class="<?= in_array($page_name, ['wfa/v_admin_jumat_wfa']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('wfa_pengajuan/show/admin_jumat') ?>">
										Monitoring Jumat
									</a>
								</li>
							<?php } ?>
						</ul>
					</li>




					<li class="nav-parent <?= in_array($page_name, [
												'jobdesc/v_job',
												'jobdesc/v_aju_job',
												'jobdesc/v_job_detail',
												'jobdesc/v_my_job',
												'evaluasi/v_evaluasi',
												'evaluasi/v_evaluasi_detail',
												'evaluasi/v_my_evaluasi',
												'evaluasi/v_evaluasi_detail_penilai',
												'evaluasi/v_my_evaluasi_detail',
												'laporan/v_lap',
												'laporan/v_lap_detail',
												'laporan/v_my_lap',
												'laporan/v_my_lap_new',
												'laporan/v_lap_detail_new',
												'laporan/v_lap_new',
												'laporan/v_lap_nilai',
												'laporan/v_my_lap_rev1',
												'laporan/v_lap_rev1',
												'laporan/v_lap_detail_rev1',
												'laporan/v_my_lap_nilai',
												'laporan/v_my_detail_nilai',
												'knowledge/v_knowledge',
												'knowledge/v_knowledge_detail',
												'knowledge/v_my_knowledge_detail',
												'laporan/v_my_lap_rev2',
												'laporan/v_my_lap_nilai_rev2',
												'laporan/v_my_detail_nilai_rev2',
												'laporan/v_lap_rev2',
												'laporan/v_lap_detail_rev2',
												'evaluasi/v_evaluasi_smt'
											]) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fa fa-chart-bar" aria-hidden="true"></i>
							<span>Kinerja Pegawai</span>
						</a>
						<ul class="nav nav-children">

							<?php if (in_array((string)sessPenggunaId(), ['1', '58', '69', '744', '54', '33', '23', '107'])) { ?>
								<li class="<?= in_array($page_name, ['jobdesc/v_job', 'jobdesc/v_aju_job', 'jobdesc/v_job_detail']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('jobdesc') ?>">
										Jobdesk
									</a>
								</li>
								<li class="<?= in_array($page_name, ['laporan/v_my_lap_rev2']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/rev2') ?>">
										Laporan Saya &nbsp;<strong> Ver. 2 </strong>
									</a>
								</li>
								<li class="<?= in_array($page_name, ['laporan/v_my_lap_nilai_rev2', 'laporan/v_my_detail_nilai_rev2']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/my_nilai_rev2') ?>">
										Nilai Laporan Saya &nbsp;<strong> Ver. 2 </strong>
									</a>
								</li>
								<li class="<?= in_array($page_name, ['laporan/v_lap_rev2', 'laporan/v_lap_detail_rev2']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/all_rev2') ?>">
										Laporan Mingguan Pegawai &nbsp; <strong> Ver. 2 </strong>
									</a>
								</li>
								<li class="<?= in_array($page_name, ['evaluasi/v_evaluasi', 'evaluasi/v_evaluasi_detail']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('evaluasi') ?>">
										Evaluasi
									</a>
								</li>
								<li class="<?= in_array($page_name, ['evaluasi/v_evaluasi_smt']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('bonus/show/evaluasi') ?>">
										Nilai Evaluasi Semester
									</a>
								</li>
								<li class="<?= in_array($page_name, ['knowledge/v_knowledge', 'knowledge/v_knowledge_detail']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('knowledge/show/knowledge') ?>">
										Product Knowledge
									</a>
								</li>
								<!-- MENU AUDIT KARYAWAN & ASET PBOK/PPA KHUSUS USER ID 107, 58, 69, 54, 33, 23, 1 -->
								<li class="<?= $page_name == 'v_audit_karyawan' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('audit/index.html') ?>">
										<i class="fas fa-clipboard-check text-info" aria-hidden="true"></i>
										<span>Audit Karyawan & Aset</span>
									</a>
								</li>
							<?php } else { ?>
								<!--<li class="<?= in_array($page_name, ['jobdesc/v_my_job']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('jobdesc/show/list/my_data') ?>">
										Jobdesk 
									</a>
								</li>-->
								<li class="<?= in_array($page_name, ['jobdesc/v_job', 'jobdesc/v_aju_job', 'jobdesc/v_job_detail']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('jobdesc') ?>">
										Jobdesk
									</a>
								</li>
								<!--<li class="<?= in_array($page_name, ['laporan/v_my_lap']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/my_data') ?>">
										Laporan Mingguan 
									</a>
								</li>-->
								<!--<li class="<?= in_array($page_name, ['laporan/v_my_lap_new']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/my') ?>">
										Laporan Mingguan &nbsp;<strong> Ver. 2 </strong>
									</a>
								</li>						
								<li class="<?= in_array($page_name, ['laporan/v_my_lap_rev1']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/mydata') ?>">
										Laporan Mingguan &nbsp;<strong> New </strong>
									</a>
								</li>						
								<li class="<?= in_array($page_name, ['laporan/v_my_lap_nilai', 'laporan/v_my_detail_nilai']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/mynilai') ?>">
										Nilai Laporan Mingguan &nbsp;<strong> New </strong>
									</a>
								</li>-->

								<li class="<?= in_array($page_name, ['laporan/v_my_lap_rev2']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/rev2') ?>">
										Laporan Mingguan &nbsp;<strong> Ver. 2 </strong>
									</a>
								</li>
								<li class="<?= in_array($page_name, ['laporan/v_my_lap_nilai_rev2', 'laporan/v_my_detail_nilai_rev2']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('laporan/show/list/my_nilai_rev2') ?>">
										Nilai Laporan Mingguan &nbsp;<strong> Ver. 2 </strong>
									</a>
								</li>

								<li class="<?= in_array($page_name, ['evaluasi/v_my_evaluasi', 'evaluasi/v_evaluasi_detail_penilai', 'evaluasi/v_my_evaluasi_detail']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('evaluasi/show/list/my_data') ?>">
										Evaluasi
									</a>
								</li>

								<li class="<?= in_array($page_name, ['knowledge/v_my_knowledge_detail']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('knowledge/show/my_detail') ?>">
										Product Knowledge
									</a>
								</li>
							<?php } ?>

						</ul>
					</li>

					<?php
					$specials_user_ids = ['1', '744', '29', '69', '58']; // hak akses untuk admin, hrd dan user tertentu
					?>
					<?php
					$current_user_id = sessPenggunaId();
					if (in_array($current_user_id, $specials_user_ids)) {
					?>
						<li class="<?= in_array($page_name, ['hasil_evaluasi_semester/v_hasil_evaluasi']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('Hasil_evaluasi_semester') ?>">
								<i class="fas fa-chart-line" aria-hidden="true"></i>
								<span>Hasil Evaluasi Semester</span>
							</a>
						</li>
					<?php } ?>

					<?php if (isHrd() || isAdmin() || isGa() || sessPenggunaId() == '743') { ?>
						<li class="<?= in_array($page_name, ['v_absensi', 'v_rekap_absensi']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('absensi') ?>">
								<i class="fas fa-digital-tachograph" aria-hidden="true"></i>
								<span>Data Absensi</span>
							</a>
						</li>
					<?php } ?>
					<?php if (isHrd() || isAdmin() || isGa()) { ?>
						<li class="<?= in_array($page_name, ['v_announcement']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('announcement') ?>">
								<i class="fas fa-bullhorn" aria-hidden="true"></i>
								<span>Announcements</span>
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
