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
								<option value="<?= base_url("dashboard") ?>">Home</option>
								<option value="<?= base_url("dashboard_dokumen") ?>" selected>Dokumen Perusahaan</option>
								<option value="<?= base_url("dashboard_kepegawaian") ?>">Kepegawaian</option>
								<option value="<?= base_url("dashboard_helpdesk") ?>">Helpdesk</option>
								<option value="<?= base_url("dashboard_inventory") ?>">Inventory</option>
								<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '75') { ?>
									<option value="<?= base_url("dashboard_marketing") ?>">Marketing</option>
								<?php } ?>
								<?php if (
									isAdmin() ||
									isHrd() ||
									isCRO() ||
									isGa() ||
									sessPenggunaId() == "54" ||
									sessPenggunaId() == "84" ||
									sessPenggunaId() == "75" ||
									sessPenggunaId() == "15" ||
									sessPenggunaId() == "736" ||
									sessPenggunaId() == "754" ||
									sessPenggunaId() == "751" ||
									sessPenggunaId() == "757" ||
									sessPenggunaId() == "765" ||
									sessPenggunaId() == '770'
								) { ?>
									<option value="<?= base_url("dashboard_visilab") ?>">Visilab</option>
								<?php } ?>
							
								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('acc_pemasok') ?>" <?= $switch == 'accounting' ? 'selected' : '' ?>>Accounting & Tax</option>
								<?php } ?>
							</select>
						</span>
					</li>

					<li class="<?= in_array($page_name, ["dokumen/v_kategori_dok"])
									? "nav-active"
									: "" ?>">
						<a class="nav-link" href="<?= base_url("dokumen/index/kategori") ?>">
							<i class="fas fa-file" aria-hidden="true"></i>
							<span>Kategori Dokumen</span>
						</a>
					</li>

					<?php if (
						isAdmin() ||
						isGa() ||
						sessPenggunaId() == "54" ||
						sessPenggunaId() == "15" ||
						sessPenggunaId() == "74" ||
						sessPenggunaId() == "75" ||
						sessPenggunaId() == "83" ||
						sessPenggunaId() == "86" ||
						sessPenggunaId() == "23" ||
						sessPenggunaId() == "84" ||
						sessPenggunaId() == "69" ||
						sessPenggunaId() == "744" ||
						sessPenggunaId() == "766"
					) { ?>
						<li class="<?= in_array($page_name, ["dokumen/v_dok_rahasia"])
										? "nav-active"
										: "" ?>">
							<a class="nav-link" href="<?= base_url("dokumen/index/rahasia") ?>">
								<i class="fas fa-file" aria-hidden="true"></i>
								<span>Dokumen Rahasia</span>
							</a>
						</li>
					<?php } ?>

					<li class="<?= in_array($page_name, ["dokumen/v_dok_umum"])
									? "nav-active"
									: "" ?>">
						<a class="nav-link" href="<?= base_url("dokumen/index/umum") ?>">
							<i class="fas fa-file" aria-hidden="true"></i>
							<span>Dokumen Umum</span>
						</a>
					</li>
					<li class="nav-parent <?= in_array($page_name, ["v_dok_product", "v_bhn_presentasi", "v_brosur", "v_manual_book", "v_e_suket"])
												? "nav-active nav-expanded"
												: "" ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-folder" aria-hidden="true"></i>
							<span>Dokumen Product</span>
						</a>
						<ul class="nav nav-children">
							<?php
							$this->load->helper("session_helper");
							foreach (dokumenproduct() as $row) {
								echo "<li>";
								echo '<a class="nav-link" href="' .
									base_url("dokumen/index/product/" . encrypt($row->id)) .
									'">';
								echo "<span>" . $row->keterangan . "</span></a></li>";
							}
							?>
							<li class="<?= $page_name == "v_bhn_presentasi" ? "nav-active" : "" ?>">
								<a class="nav-link" href="<?= base_url("dokumen/index/bahan_presentasi") ?>">
									Bahan Presentasi
								</a>
							</li>

							<li class="<?= $page_name == "v_brosur" ? "nav-active" : "" ?>">
								<a class="nav-link" href="<?= base_url("dokumen/index/brosur") ?>">
									Brochure
								</a>
							</li>
							<li class="<?= $page_name == "v_manual_book" ? "nav-active" : "" ?>">
								<a class="nav-link" href="<?= base_url("dokumen/index/manual_book") ?>">
									Manual Book
								</a>
							</li>

							<li class="<?= $page_name == "v_e_suket" ? "nav-active" : "" ?>">
								<a class="nav-link" href="<?= base_url("dokumen/e_suket") ?>">
									e-Suket
								</a>
							</li>
						</ul>
					</li>



					<?php if (
						isAdmin() ||
						isGa() ||
						sessPenggunaId() == "74" ||
						sessPenggunaId() == "83" ||
						sessPenggunaId() == "84" ||
						sessPenggunaId() == "86" ||
						sessPenggunaId() == "754" ||
						sessPenggunaId() == "736" ||
						sessPenggunaId() == "15" ||
						sessPenggunaId() == "23" ||
						sessPenggunaId() == "54" ||
						sessPenggunaId() == "33" ||
						sessPenggunaId() == "69" ||
						sessPenggunaId() == "744" ||
						sessPenggunaId() == "751" ||
						sessPenggunaId() == "75" ||
						sessPenggunaId() == "757" ||
						sessPenggunaId() == "760"
					) { ?>
						<li class="nav-parent <?= in_array($page_name, ["v_dok_visilab", "visilab/v_dokumen_rahasia"])
													? "nav-active nav-expanded"
													: "" ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-folder" aria-hidden="true"></i>
								<span>Dokumen Visilab</span>
							</a>
							<ul class="nav nav-children">

								<li class="<?= in_array($page_name, ["visilab/v_dokumen_rahasia"])
												? "nav-active"
												: "" ?>">
									<a class="nav-link" href="<?= base_url("Dokumen_rahasia_visilab") ?>">
										<span>Dokumen Rahasia Visilab</span>
									</a>
								</li>

								<?php
								$this->load->helper("session_helper");
								foreach (dokumenvisilab() as $row) {
									echo '<li class="text-wrap">';
									echo '<a class="nav-link" href="' .
										base_url(
											"dokumen/index/visilab/" .
												encrypt($row->id),
										) .
										'">';
									echo "<span>" .
										$row->keterangan .
										"</span></a></li>";
								}
								?>
								<style>
									.text-wrap a {
										display: block;
										white-space: normal !important;
										overflow-wrap: break-word;
										word-break: break-word;

									}
								</style>
							</ul>
						</li>

						<li class="<?= in_array($page_name, ["dokumen/v_kategori_dok_visilab"])
										? "nav-active"
										: "" ?>">
							<a class="nav-link" href="<?= base_url("dokumen/index/kategori_visilab") ?>">
								<i class="fas fa-file" aria-hidden="true"></i>
								<span>Kategori Dokumen Visilab</span>
							</a>
						</li>


					<?php } ?>

					<li class="<?= in_array($page_name, ["dokumen/v_dok_umum_visilab"])
									? "nav-active"
									: "" ?>">
						<a class="nav-link" href="<?= base_url("dokumen/index/umum_visilab") ?>">
							<i class="fas fa-file" aria-hidden="true"></i>
							<span>Dokumen Umum Visilab</span>
						</a>
					</li>

					<li>
						<a class="nav-link text-danger border-top" href="<?= base_url('auth/logout') ?>">
							<i class="fas fa-sign-out-alt text-danger" aria-hidden="true"></i>
							<span>Logout</span>
						</a>
					</li>

					<!-- 
					<li class="<?= in_array($page_name, ["dokumen/v_dok_product"])
									? "nav-active"
									: "" ?>">
						<a class="nav-link" href="<?= base_url("dokumen/product") ?>">
							<i class="fas fa-file" aria-hidden="true"></i>
							<span>Dokumen Product</span>
						</a>
					</li>
					-->
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