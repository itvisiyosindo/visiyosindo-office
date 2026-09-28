<!doctype html>
<html class="fixed">

<head>
	<meta charset="UTF-8">
	<title>Login Page | <?= $this->config->item('apps_name') ?></title>
	<meta name="keywords" content="Sistem Informasi" />
	<meta name="description" content="<?= $this->config->item('apps_name') ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/animate/animate.compat.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/font-awesome/css/all.min.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/magnific-popup/magnific-popup.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/simple-line-icons/css/simple-line-icons.css" />
	<link rel="stylesheet" href="<?= base_url('assets/') ?>js/sweetalert2/sweetalert2.min.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/theme.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">
	<link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.png" />
	<script src="<?= base_url() ?>assets/vendor/modernizr/modernizr.js"></script>
	<script src="<?= base_url() ?>assets/master/style-switcher/style.switcher.localstorage.js"></script>

	<style>
		/* Custom Redesign CSS for Premium Split Screen Login Page */
		html,
		body {
			margin: 0;
			padding: 0;
			height: 100%;
			font-family: 'Poppins', sans-serif;
			background: #f4f7f6;
		}

		.auth-container {
			display: flex;
			min-height: 100vh;
			width: 100%;
		}

		/* Left Section: Slider */
		.auth-slider-section {
			flex: 1.2;
			position: relative;
			background-color: #0b1d33;
			overflow: hidden;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		@media (max-width: 991px) {
			.auth-slider-section {
				display: none;
				/* Hide slider on smaller screens */
			}
		}

		/* Right Section: Form Box */
		.auth-form-section {
			flex: 0.8;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 40px;
			background: #ffffff;
			position: relative;
			box-shadow: -5px 0 25px rgba(0, 0, 0, 0.05);
		}

		@media (max-width: 991px) {
			.auth-form-section {
				flex: 1;
				padding: 40px 20px;
				background: #f4f7f6;
			}
		}

		/* Slider Content Styling */
		.auth-carousel {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}

		.carousel-slide {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			opacity: 0;
			transition: opacity 1s ease-in-out;
			background-size: cover;
			background-position: center;
			display: flex;
			flex-direction: column;
			justify-content: flex-end;
			padding: 80px 60px;
			z-index: 1;
		}

		.carousel-slide.active {
			opacity: 1;
			z-index: 2;
		}

		.carousel-slide::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: linear-gradient(to top, rgba(11, 29, 51, 0.95) 0%, rgba(11, 29, 51, 0.4) 60%, rgba(11, 29, 51, 0.2) 100%);
			z-index: 1;
		}

		.slide-content {
			position: relative;
			z-index: 2;
			color: #ffffff;
			max-width: 600px;
			transform: translateY(20px);
			transition: transform 0.8s ease;
		}

		.carousel-slide.active .slide-content {
			transform: translateY(0);
		}

		.slide-title {
			font-size: 2.2rem;
			font-weight: 700;
			margin-bottom: 15px;
			line-height: 1.3;
			color: #ffffff;
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
		}

		.slide-desc {
			font-size: 1.1rem;
			color: rgba(255, 255, 255, 0.85);
			line-height: 1.6;
			margin-bottom: 30px;
			text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
		}

		.carousel-indicators-custom {
			position: absolute;
			bottom: 40px;
			left: 60px;
			display: flex;
			gap: 10px;
			z-index: 10;
		}

		.indicator-dot {
			width: 30px;
			height: 4px;
			background: rgba(255, 255, 255, 0.3);
			border-radius: 2px;
			cursor: pointer;
			transition: background 0.3s ease, width 0.3s ease;
		}

		.indicator-dot.active {
			background: #007bff;
			width: 45px;
		}

		/* Form Card/Box Styling */
		.auth-form-card {
			width: 100%;
			max-width: 450px;
			background: #ffffff;
			border-radius: 16px;
			box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
			border: 1px solid rgba(0, 0, 0, 0.05);
			padding: 40px 35px;
			transition: transform 0.3s ease;
		}

		@media (max-width: 575px) {
			.auth-form-card {
				padding: 30px 20px;
				box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
			}
		}

		.auth-form-card:hover {
			transform: translateY(-2px);
		}

		.auth-logo {
			display: block;
			margin: 0 auto 30px auto;
			max-height: 65px;
			width: auto;
			object-fit: contain;
		}

		.auth-header {
			text-align: center;
			margin-bottom: 30px;
		}

		.auth-header h2 {
			font-size: 1.8rem;
			font-weight: 700;
			color: #1a2530;
			margin: 0 0 8px 0;
		}

		.auth-header p {
			color: #6c757d;
			font-size: 0.95rem;
			margin: 0;
		}

		/* Input Form Tweaks */
		.lgn-form .form-group,
		.rgstr-form .form-group {
			margin-bottom: 22px;
		}

		.lgn-form label,
		.rgstr-form label {
			font-weight: 500;
			font-size: 0.85rem;
			color: #495057;
			margin-bottom: 6px;
			display: inline-block;
		}

		.lgn-form .input-group,
		.rgstr-form .input-group {
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
			border-radius: 8px;
			overflow: hidden;
			border: 1px solid #ced4da;
			transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
		}

		.lgn-form .input-group:focus-within,
		.rgstr-form .input-group:focus-within {
			border-color: #007bff;
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .15);
		}

		.lgn-form .form-control,
		.rgstr-form .form-control {
			border: none;
			height: 48px;
			font-size: 0.95rem;
			padding-left: 16px;
		}

		.lgn-form .form-control:focus,
		.rgstr-form .form-control:focus {
			box-shadow: none;
			outline: none;
		}

		.lgn-form .input-group-text,
		.rgstr-form .input-group-text {
			background-color: #f8f9fa;
			border: none;
			color: #6c757d;
			font-size: 1.1rem;
			padding: 0 15px;
			display: flex;
			align-items: center;
		}

		.lgn-form .input-group-append svg,
		.rgstr-form .input-group-append svg {
			color: #6c757d;
			transition: color 0.2s ease;
		}

		.lgn-form .input-group-append:hover svg,
		.rgstr-form .input-group-append:hover svg {
			color: #333333;
		}

		.btn-primary {
			background-color: #007bff;
			border-color: #007bff;
			font-weight: 600;
			font-size: 0.95rem;
			padding: 12px 24px;
			border-radius: 8px;
			transition: all 0.2s ease;
			box-shadow: 0 4px 10px rgba(0, 123, 255, 0.15);
		}

		.btn-primary:hover,
		.btn-primary:focus {
			background-color: #0069d9;
			border-color: #0062cc;
			transform: translateY(-1px);
			box-shadow: 0 6px 15px rgba(0, 123, 255, 0.25);
		}

		.btn-primary:active {
			transform: translateY(0);
		}

		.btn-block {
			display: block;
			width: 100%;
		}

		/* Absolut footer copyright */
		.auth-footer {
			position: absolute;
			bottom: 20px;
			left: 0;
			width: 100%;
			text-align: center;
			font-size: 0.8rem;
			color: #adb5bd;
			z-index: 10;
		}

		/* Slide background images overlay decoration */
		.brand-overlay {
			position: absolute;
			top: 40px;
			left: 60px;
			z-index: 10;
			display: flex;
			align-items: center;
			gap: 12px;
		}

		.brand-logo-mini {
			height: 40px;
			width: auto;
		}

		.brand-name {
			color: #ffffff;
			font-size: 1.3rem;
			font-weight: 700;
			letter-spacing: 0.5px;
			text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
		}
	</style>
</head>

<body>
	<div class="auth-container">
		<!-- Left Section: Slider -->
		<div class="auth-slider-section">
			<!-- Brand Mini Logo top left -->
			<div class="brand-overlay">
				<img src="<?= base_url('assets/img/logovym2023.png') ?>" alt="logo vym" class="brand-logo-mini" />
				<span class="brand-name"><?= $this->config->item('apps_name') ?></span>
			</div>

			<div class="auth-carousel">
				<!-- Slide 1 -->
				<div class="carousel-slide active" style="background-image: url('<?= base_url('assets/img/gedung.jpg') ?>');">
					<div class="slide-content">
						<h3 class="slide-title">Integrated Office System</h3>
						<p class="slide-desc">Sistem pendukung operasional yang handal untuk menunjang produktivitas dan koordinasi tim secara efisien.</p>
					</div>
				</div>
				<!-- Slide 2 -->
				<div class="carousel-slide" style="background-image: url('https://visiyosindo.com/storage/galleries/Aqkh8BmsUM7Vqpfj6rP8gNwe0j7NQCA4w7g7eiYO.jpg">
					<div class="slide-content">
						<h3 class="slide-title">Visi Yosindo Medikal</h3>
						<p class="slide-desc">Mitra terpercaya penyedia alat kesehatan berkualitas tinggi dan layanan purna jual terbaik di Indonesia.</p>
					</div>
				</div>
				<!-- Slide 3 -->
				<div class="carousel-slide" style="background-image: url('https://visiyosindo.com/storage/galleries/oM0IEUBtdewkoxkWYGhlZj6VqlW91gq05nM4bOjl.jpg">
					<div class="slide-content">
						<h3 class="slide-title">Helpdesk & Service Center</h3>
						<p class="slide-desc">Kemudahan pelaporan masalah teknis, pemantauan status tiket, dan respons cepat untuk menjamin kepuasan pelanggan.</p>
					</div>
				</div>
			</div>

			<!-- Carousel Indicators -->
			<div class="carousel-indicators-custom">
				<div class="indicator-dot active" data-slide-to="0"></div>
				<div class="indicator-dot" data-slide-to="1"></div>
				<div class="indicator-dot" data-slide-to="2"></div>
			</div>
		</div>

		<!-- Right Section: Form Box -->
		<div class="auth-form-section">
			<div class="auth-form-card">
				<!-- Logo -->
				<img src="<?= base_url('assets/img/logovym2023.png') ?>" alt="logo vym" class="auth-logo" />

				<!-- Login Mode -->
				<?php if ($mode == "form-login") { ?>
					<div class="auth-header">
						<h2>Sign In</h2>
						<p>Silahkan masukkan Email & Password anda</p>
					</div>

					<?= $this->session->flashdata('error') ? '<div class="alert alert-danger text-center" role="alert">' . $this->session->flashdata('error') . '</div>' : '' ?>
					<?= $this->session->flashdata('success') ? '<div class="alert alert-success text-center" role="alert">' . $this->session->flashdata('success') . '</div>' : '' ?>

					<?= form_open("auth/oauth", array('id' => 'kt_login_signin_form', 'class' => 'form', 'autocomplete' => 'off')); ?>
					<div class="lgn-form">
						<div class="form-group">
							<label for="email">Email Address</label>
							<div class="input-group">
								<input name="email" type="email" class="form-control" placeholder="example@email.com" required />
								<span class="input-group-append">
									<span class="input-group-text">
										<i class="bx bx-envelope"></i>
									</span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label for="login_password">Password</label>
							<div class="input-group">
								<input name="password" type="password" class="form-control" id="login_password" placeholder="••••••••" required />
								<span class="input-group-append toggle-password" data-target="#login_password" style="cursor: pointer;">
									<span class="input-group-text">
										<svg class="eye-show" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
											<circle cx="12" cy="12" r="3"></circle>
										</svg>
										<svg class="eye-hide" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
											<line x1="1" y1="1" x2="23" y2="23"></line>
										</svg>
									</span>
								</span>
							</div>
						</div>

						<div class="row align-items-center mt-4">
							<div class="col-7">
								<p class="mb-0" style="font-size: 0.85rem; color: #6c757d;">
									Belum punya akun? <a href="auth/register/form-register" style="font-weight: 600; color: #007bff;">Daftar</a>
								</p>
							</div>
							<div class="col-5">
								<button type="submit" class="btn btn-primary btn-block" id="btn-submit">Sign In</button>
							</div>
						</div>
					</div>
					<?= form_close() ?>

					<!-- Register Mode -->
				<?php } else if ($mode == "form-register") { ?>
					<div class="auth-header">
						<h2>Sign Up</h2>
						<p>Silahkan isi Biodata anda dengan benar</p>
					</div>

					<?= $this->session->flashdata('error') ? '<div class="alert alert-danger" role="alert">' . $this->session->flashdata('error') . '</div>' : '' ?>
					<?= $this->session->flashdata('success') ? '<div class="alert alert-success" role="alert">' . $this->session->flashdata('success') . '</div>' : '' ?>

					<?= form_open("auth/register", array('id' => 'kt_login_signin_form', 'class' => 'form', 'autocomplete' => 'off')); ?>
					<div class="rgstr-form">
						<h5 class="mb-3" style="font-weight: 600; color: #495057; border-bottom: 1px solid #eee; padding-bottom: 8px;">Data Diri</h5>

						<div class="form-group">
							<label for="nama">Nama Lengkap</label>
							<div class="input-group">
								<input class="form-control" type="text" placeholder="Masukkan Nama Lengkap" name="nama" id="nama" required>
								<span class="input-group-append">
									<span class="input-group-text"><i class="bx bx-user"></i></span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label for="no_hp">No Handphone</label>
							<div class="input-group">
								<input class="form-control" type="text" placeholder="Masukkan Nomor HP" name="no_hp" id="no_hp" autocomplete="off" required>
								<span class="input-group-append">
									<span class="input-group-text"><i class="bx bx-phone"></i></span>
								</span>
							</div>
						</div>

						<h5 class="mb-3 mt-4" style="font-weight: 600; color: #495057; border-bottom: 1px solid #eee; padding-bottom: 8px;">Akun Email & Password</h5>

						<div class="form-group">
							<label for="email">Email</label>
							<div class="input-group">
								<input class="form-control" type="email" placeholder="example@email.com" name="email" id="email" autocomplete="off" required>
								<span class="input-group-append">
									<span class="input-group-text"><i class="bx bx-envelope"></i></span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label for="password">Password</label>
							<div class="input-group">
								<input class="form-control" type="password" placeholder="••••••••" name="password" id="password" required>
								<span class="input-group-append toggle-password" data-target="#password" style="cursor: pointer;">
									<span class="input-group-text">
										<svg class="eye-show" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
											<circle cx="12" cy="12" r="3"></circle>
										</svg>
										<svg class="eye-hide" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
											<line x1="1" y1="1" x2="23" y2="23"></line>
										</svg>
									</span>
								</span>
							</div>
						</div>

						<div class="form-group">
							<label for="cpassword">Confirm Password</label>
							<div class="input-group">
								<input class="form-control" type="password" placeholder="••••••••" name="cpassword" id="cpassword" required>
								<span class="input-group-append toggle-password" data-target="#cpassword" style="cursor: pointer;">
									<span class="input-group-text">
										<svg class="eye-show" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
											<circle cx="12" cy="12" r="3"></circle>
										</svg>
										<svg class="eye-hide" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
											<line x1="1" y1="1" x2="23" y2="23"></line>
										</svg>
									</span>
								</span>
							</div>
						</div>

						<div class="text-center mt-4">
							<button type="button" class="btn btn-primary btn-submit-daftar px-5 mr-2">Submit</button>
							<a class="btn btn-danger btn-cancel px-5" href="javascript:history.back()" style="border-radius: 8px; font-weight: 600; padding: 12px 24px; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.15);">Cancel</a>
						</div>
					</div>
					<?= form_close() ?>
				<?php } ?>
			</div>

			<!-- Footer Copyright inside Form Area -->
			<div class="auth-footer">
				&copy; All Rights Reserved 2022 - <?= date('Y') ?>. PT VISI YOSINDO MEDIKAL
			</div>
		</div>
	</div>

	<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
	<script src="<?= base_url() ?>assets/vendor/jquery/jquery.js"></script>
	<script src="<?= base_url() ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
	<script src="<?= base_url() ?>assets/vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="<?= base_url() ?>assets/vendor/popper/umd/popper.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="<?= base_url() ?>assets/vendor/common/common.js"></script>
	<script src="<?= base_url() ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
	<script src="<?= base_url() ?>assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
	<script src="<?= base_url() ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
	<script src="<?= base_url() ?>assets/js/theme.js"></script>
	<script src="<?= base_url() ?>assets/js/theme.init.js"></script>
	<script src="<?= base_url('assets/') ?>/js/sweetalert2/sweetalert2.min.js"></script>
	<script src="<?= base_url('assets/') ?>js/global.js"></script>

	<script>
		let token = $('input[name=token]').val()

		$(document).ready(function() {
			// AJAX form submit for login with loading placeholder & sweetalert
			$('#kt_login_signin_form').on('submit', function(e) {
				if ($('#login_password').length) {
					e.preventDefault();
					const form = $(this);
					const url = form.attr('action');
					
					Swal.fire({
						allowOutsideClick: false,
						showConfirmButton: false,
						background: 'transparent',
						backdrop: `
							rgba(0, 0, 0, 0.4)
							backdrop-filter: blur(8px)
							-webkit-backdrop-filter: blur(8px)
						`,
						didOpen: () => {
							Swal.showLoading()
						}
					});

					const startTime = new Date().getTime();
					$.ajax({
						url: url,
						type: 'POST',
						data: form.serialize(),
						dataType: 'JSON',
						success: function(resp) {
							const endTime = new Date().getTime();
							const timeDiff = endTime - startTime;
							const delay = Math.max(0, 3000 - timeDiff);
							
							setTimeout(function() {
								if (resp.status === 'success') {
									Swal.fire({
										title: 'Login Berhasil!',
										text: resp.msg,
										icon: 'success',
										timer: 1500,
										showConfirmButton: false
									}).then(function() {
										window.location.href = resp.redirect;
									});
								} else {
									Swal.fire({
										title: 'Login Gagal',
										text: resp.msg,
										icon: 'error'
									});
								}
							}, delay);
						},
						error: function(xhr, status, error) {
							const endTime = new Date().getTime();
							const timeDiff = endTime - startTime;
							const delay = Math.max(0, 3000 - timeDiff);
							
							setTimeout(function() {
								Swal.fire({
									title: 'System Error',
									text: 'Terjadi kesalahan sistem, silakan coba beberapa saat lagi.',
									icon: 'error'
								});
							}, delay);
						}
					});
				}
			});

			// Password Show/Hide toggle logic
			$(document).on('click', '.toggle-password', function() {
				var targetSelector = $(this).data('target');
				var $input = $(targetSelector);
				var $eyeShow = $(this).find('.eye-show');
				var $eyeHide = $(this).find('.eye-hide');

				if ($input.attr('type') === 'password') {
					$input.attr('type', 'text');
					$eyeShow.hide();
					$eyeHide.show();
				} else {
					$input.attr('type', 'password');
					$eyeShow.show();
					$eyeHide.hide();
				}
			});

			// Carousel Slider Auto Change logic
			var slides = $('.carousel-slide');
			var dots = $('.indicator-dot');
			var currentIndex = 0;
			var slideInterval = 5000; // 5 seconds

			function showSlide(index) {
				slides.removeClass('active');
				dots.removeClass('active');

				slides.eq(index).addClass('active');
				dots.eq(index).addClass('active');
				currentIndex = index;
			}

			function nextSlide() {
				var nextIndex = (currentIndex + 1) % slides.length;
				showSlide(nextIndex);
			}

			var timer = setInterval(nextSlide, slideInterval);

			dots.click(function() {
				clearInterval(timer);
				var index = $(this).data('slide-to');
				showSlide(index);
				timer = setInterval(nextSlide, slideInterval);
			});
		});
	</script>

	<?php if ($mode == 'form-register') { ?>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
		<script>
			var map;
			var popup;

			document.addEventListener("DOMContentLoaded", function() {
				initMap()

				$('.btn-submit-daftar').on('click', function() {
					const form = $(this).closest('form')
					const url = form.attr('action')
					const formId = form.attr('id')
					Swal.fire({
						title: 'Apakah anda yakin?',
						text: 'Pastikan data yang anda masukkan sudah benar!',
						icon: 'question',
						showCancelButton: true,
						confirmButtonText: 'Ya',
						cancelButtonText: 'Batal'
					}).then(function(result) {
						if (result.value) {
							$.ajax({
								url: url,
								type: "POST",
								data: new FormData($('#' + formId)[0]),
								contentType: false,
								processData: false,
								dataType: "JSON",
								success: function(resp) {
									if (resp['status'] == 'error') {
										return Swal.fire({
											html: `<h4>${resp['msg']}</h4>`,
											icon: resp['status']
										})
									} else {
										handleResponse(resp)
									}
								}
							});
						}
					})
				})
			})

			function initMap() {
				setTimeout(() => {
					existMap = new L.map('mapid', {
						center: [-1.239751, 116.8503613, 14],
						zoom: 12
					});
					popup = L.popup();
					L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
						attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, © <a href="https://www.mapbox.com/">Mapbox</a>',
						maxZoom: 18,
						id: 'mapbox/streets-v11',
						accessToken: <?= json_encode($_ENV['MAPBOX_PUBLIC_TOKEN'] ?? '') ?>
					}).addTo(existMap);
					existMap.on('click', onMapClick);
					existMap.invalidateSize(true);
				}, 500);
			}

			function onMapClick(e) {
				var longt = parseFloat(e.latlng['lng']).toFixed(8)
				var lat = parseFloat(e.latlng['lat']).toFixed(8)
				$('#longitude').val(longt)
				$('#latitude').val(lat)
				$('#longitude_text').html(parseFloat(longt))
				$('#latitude_text').html(parseFloat(lat))
				popup
					.setLatLng(e.latlng)
					.setContent("Alamat Saya Disini")
					.openOn(existMap);
			}
		</script>
	<?php } ?>
</body>

</html>