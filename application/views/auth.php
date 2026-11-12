<!doctype html>
<html class="fixed">

<head>
	<meta charset="UTF-8">
	<title><?= ($mode == 'form-register') ? 'Register Page' : 'Login Page' ?> | <?= $this->config->item('apps_name') ?></title>
	<meta name="keywords" content="Sistem Informasi" />
	<meta name="description" content="<?= $this->config->item('apps_name') ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

	<style>
		:root {
			--vym-navy: #0b1d39;
			--vym-blue: #1d4ed8;
			--vym-blue-bright: #2563eb;
			--vym-sky: #0ea5e9;
			--vym-cyan: #38bdf8;
			--vym-ink: #0f172a;
			--vym-muted: #64748b;
			--vym-border: #e2e8f0;
			--vym-surface: #ffffff;
			--vym-bg: #f3f7fc;
		}

		*,
		*::before,
		*::after {
			box-sizing: border-box;
		}

		html,
		body {
			margin: 0;
			padding: 0;
			min-height: 100%;
			font-family: 'Plus Jakarta Sans', 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
			background: var(--vym-navy);
			color: var(--vym-ink);
			-webkit-font-smoothing: antialiased;
			overflow-x: hidden;
		}

		/* Full-Screen Static Background Photo */
		.auth-bg-stage {
			position: fixed;
			inset: 0;
			z-index: 0;
			background: linear-gradient(135deg, #091428 0%, #102a52 100%);
			overflow: hidden;
		}

		.auth-bg-photo {
			position: absolute;
			inset: 0;
			background-size: cover;
			background-position: center 34%;
			background-repeat: no-repeat;
		}

		.auth-bg-stage::after {
			content: '';
			position: fixed;
			inset: 0;
			background:
				radial-gradient(circle at center, rgba(8, 17, 34, 0.14) 0%, rgba(8, 17, 34, 0.44) 100%),
				linear-gradient(180deg, rgba(8, 17, 34, 0.34) 0%, rgba(8, 17, 34, 0.16) 46%, rgba(8, 17, 34, 0.66) 100%);
			z-index: 2;
			pointer-events: none;
		}

		/* Main Foreground Shell */
		.auth-shell {
			position: relative;
			z-index: 5;
			min-height: 100vh;
			width: 100%;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: space-between;
			padding: 18px 24px 16px;
			gap: 12px;
		}

		@media (max-width: 575.98px) {
			.auth-shell {
				padding: 16px 14px 18px;
				gap: 14px;
			}
		}

		/* Top Navigation / Brand Bar */
		.auth-topbar {
			width: 100%;
			max-width: 1180px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 12px;
		}

		.brand-pill {
			display: inline-flex;
			align-items: center;
			gap: 11px;
			background: rgba(255, 255, 255, 0.92);
			backdrop-filter: blur(14px);
			-webkit-backdrop-filter: blur(14px);
			border: 1px solid rgba(255, 255, 255, 0.85);
			border-radius: 999px;
			padding: 6px 16px 6px 9px;
			box-shadow: 0 12px 28px rgba(8, 17, 34, 0.22);
		}

		.brand-logo-wrap {
			width: 34px;
			height: 34px;
			border-radius: 50%;
			background: #eff6ff;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 4px;
		}

		.brand-logo-mini {
			height: 24px;
			width: auto;
			object-fit: contain;
		}

		.brand-text-group {
			display: flex;
			flex-direction: column;
			line-height: 1.15;
		}

		.brand-name {
			color: var(--vym-ink);
			font-size: 13px;
			font-weight: 800;
			letter-spacing: -0.1px;
		}

		.brand-sub {
			color: var(--vym-blue-bright);
			font-size: 10.5px;
			font-weight: 600;
		}

		.hero-status-chip {
			display: inline-flex;
			align-items: center;
			gap: 7px;
			background: rgba(15, 23, 42, 0.56);
			backdrop-filter: blur(12px);
			-webkit-backdrop-filter: blur(12px);
			border: 1px solid rgba(255, 255, 255, 0.24);
			color: #ffffff;
			font-size: 11.5px;
			font-weight: 600;
			padding: 7px 14px;
			border-radius: 999px;
			box-shadow: 0 8px 20px rgba(8, 17, 34, 0.2);
		}

		.hero-status-dot {
			width: 8px;
			height: 8px;
			border-radius: 50%;
			background: #34d399;
			box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.28);
		}

		@media (max-width: 639.98px) {
			.auth-topbar {
				justify-content: center;
			}

			.hero-status-chip {
				display: none;
			}
		}

		/* Centered Login Stage */
		.auth-center-stage {
			width: 100%;
			flex: 1;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		/* Bottom Bar (Centered Copyright) */
		.auth-bottombar {
			width: 100%;
			max-width: 1180px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.auth-slide-caption {
			display: inline-flex;
			align-items: center;
			gap: 10px;
			background: rgba(9, 20, 40, 0.56);
			backdrop-filter: blur(12px);
			-webkit-backdrop-filter: blur(12px);
			border: 1px solid rgba(255, 255, 255, 0.18);
			border-radius: 999px;
			padding: 6px 16px;
			color: rgba(248, 250, 252, 0.92);
			font-size: 12px;
			font-weight: 500;
			text-align: center;
		}

		.auth-slide-caption strong {
			color: #38bdf8;
			font-weight: 700;
		}

		/* Form Card */
		.auth-form-card {
			width: 100%;
			max-width: 424px;
			background: rgba(255, 255, 255, 0.91);
			backdrop-filter: blur(22px) saturate(165%);
			-webkit-backdrop-filter: blur(22px) saturate(165%);
			border-radius: 24px;
			box-shadow:
				0 32px 64px -16px rgba(8, 17, 34, 0.45),
				0 10px 24px -6px rgba(15, 23, 42, 0.18),
				0 0 0 1px rgba(255, 255, 255, 0.75);
			padding: 30px 32px 26px;
			position: relative;
			z-index: 2;
			overflow: hidden;
		}

		.auth-form-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 4px;
			background: linear-gradient(90deg, #1d4ed8 0%, #0ea5e9 55%, #38bdf8 100%);
		}

		@media (max-width: 575.98px) {
			.auth-form-card {
				padding: 26px 22px 24px;
				border-radius: 20px;
			}
		}

		.auth-logo-box {
			text-align: center;
			margin-bottom: 14px;
		}

		.auth-logo-frame {
			width: 60px;
			height: 60px;
			margin: 0 auto 10px;
			border-radius: 18px;
			background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
			border: 1px solid #dbeafe;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.18);
		}

		.auth-logo {
			max-height: 42px;
			width: auto;
			object-fit: contain;
		}

		.auth-portal-pill {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			padding: 4px 12px;
			border-radius: 999px;
			background: #eff6ff;
			border: 1px solid #dbeafe;
			color: var(--vym-blue);
			font-size: 11.5px;
			font-weight: 700;
			letter-spacing: 0.2px;
		}

		.auth-header {
			text-align: center;
			margin-bottom: 20px;
		}

		.auth-header h2 {
			font-size: 25px;
			font-weight: 800;
			color: var(--vym-ink);
			margin: 0 0 6px 0;
			letter-spacing: -0.5px;
			line-height: 1.2;
		}

		.auth-header p {
			color: var(--vym-muted);
			font-size: 13.5px;
			margin: 0;
			line-height: 1.5;
		}

		.auth-alert {
			border-radius: 12px;
			font-size: 13px;
			padding: 11px 14px;
			border: 1px solid transparent;
			margin-bottom: 18px;
		}

		.auth-alert.alert-danger {
			background: #fef2f2;
			border-color: #fecaca;
			color: #b91c1c;
		}

		.auth-alert.alert-success {
			background: #f0fdf4;
			border-color: #bbf7d0;
			color: #15803d;
		}

		/* Form Group & Inputs */
		.auth-form-group {
			margin-bottom: 18px;
		}

		.auth-label {
			font-weight: 600;
			font-size: 13px;
			color: #334155;
			margin-bottom: 7px;
			display: block;
		}

		.auth-input-group {
			border: 1.5px solid var(--vym-border);
			border-radius: 14px;
			background: #f8fafc;
			overflow: hidden;
			display: flex;
			align-items: center;
			transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
		}

		.auth-input-group:hover {
			border-color: #cbd5e1;
		}

		.auth-input-group:focus-within {
			border-color: var(--vym-blue-bright);
			background: #ffffff;
			box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
		}

		.auth-input-leading {
			padding: 0 4px 0 15px;
			color: #94a3b8;
			font-size: 18px;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: color 0.2s ease;
		}

		.auth-input-group:focus-within .auth-input-leading {
			color: var(--vym-blue-bright);
		}

		.auth-input-group .form-control {
			flex: 1 1 auto;
			min-width: 0;
			border: none;
			border-radius: 0;
			background: transparent;
			height: 48px;
			font-size: 14px;
			font-weight: 500;
			color: #0f172a;
			padding: 0 12px;
			box-shadow: none;
			width: 100%;
		}

		.auth-actions {
			margin-top: 24px;
		}

		.btn-auth-secondary {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			height: 44px;
			margin-top: 10px;
			border-radius: 14px;
			background: #f1f5f9;
			color: #475569;
			font-size: 14px;
			font-weight: 600;
			text-decoration: none;
			transition: background-color 0.2s ease, color 0.2s ease;
		}

		.btn-auth-secondary:hover {
			background: #e2e8f0;
			color: #1e293b;
			text-decoration: none;
		}

		.auth-input-group .form-control::placeholder {
			color: #94a3b8;
			font-weight: 400;
		}

		.auth-input-group .form-control:focus {
			box-shadow: none;
			outline: none;
		}

		.auth-input-group-addon {
			padding: 0 14px;
			color: #94a3b8;
			font-size: 18px;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: color 0.2s ease;
		}

		.auth-input-group-addon.toggle-password:hover {
			color: var(--vym-blue-bright);
		}

		.auth-section-divider {
			font-size: 11.5px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.7px;
			color: var(--vym-blue-bright);
			margin: 18px 0 12px;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.auth-section-divider::after {
			content: '';
			flex: 1;
			height: 1px;
			background: #e2e8f0;
		}

		/* Action Button */
		.btn-auth-submit {
			width: 100%;
			height: 50px;
			background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #0284c7 100%);
			color: #ffffff;
			border: none;
			border-radius: 14px;
			font-size: 15px;
			font-weight: 700;
			letter-spacing: 0.2px;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 9px;
			box-shadow: 0 10px 22px -6px rgba(37, 99, 235, 0.45);
			transition: all 0.22s ease;
			cursor: pointer;
		}

		.btn-auth-submit:hover {
			background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 55%, #0369a1 100%);
			transform: translateY(-1.5px);
			box-shadow: 0 14px 28px -6px rgba(37, 99, 235, 0.55);
			color: #ffffff;
		}

		.btn-auth-submit:hover .btn-arrow-icon {
			transform: translateX(3px);
		}

		.btn-arrow-icon {
			transition: transform 0.2s ease;
		}

		.btn-auth-submit:active {
			transform: translateY(0);
		}

		.btn-auth-submit:disabled {
			opacity: 0.78;
			cursor: not-allowed;
			transform: none;
		}

		.auth-bottom-text {
			text-align: center;
			margin-top: 20px;
			padding-top: 18px;
			border-top: 1px solid #f1f5f9;
			font-size: 13.5px;
			color: var(--vym-muted);
		}

		.auth-bottom-text a {
			color: var(--vym-blue-bright);
			font-weight: 700;
			text-decoration: none;
		}

		.auth-bottom-text a:hover {
			text-decoration: underline;
		}

		.auth-footer {
			margin-top: 22px;
			text-align: center;
			font-size: 12px;
			font-weight: 500;
			color: #64748b;
			position: relative;
			z-index: 2;
		}
	</style>
</head>

<body>
	<!-- Full-Screen Static Background Photo -->
	<div class="auth-bg-stage" aria-hidden="true">
		<div class="auth-bg-photo" style="background-image: url('<?= base_url('assets/img/gedung-visilab.jpg') ?>');"></div>
	</div>

	<div class="auth-shell">
		<!-- Top Brand Bar -->
		<header class="auth-topbar">
			<div class="brand-pill">
				<span class="brand-logo-wrap">
					<img src="<?= base_url('assets/img/logovym2023.png') ?>" alt="logo vym" class="brand-logo-mini" />
				</span>
				<span class="brand-text-group">
					<span class="brand-name"><?= $this->config->item('apps_name') ?></span>
					<span class="brand-sub">PT Visi Yosindo Medikal</span>
				</span>
			</div>
			<div class="hero-status-chip">
				<span class="hero-status-dot"></span>
				<span>Visilab &bull; Visi Academy</span>
			</div>
		</header>

		<!-- Centered Auth Form Card -->
		<main class="auth-center-stage">
			<div class="auth-form-card">
				<!-- Logo -->
				<div class="auth-logo-box">
					<div class="auth-logo-frame">
						<img src="<?= base_url('assets/img/logovym2023.png') ?>" alt="logo vym" class="auth-logo" />
					</div>
					<span class="auth-portal-pill">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
						VYM Office Portal
					</span>
				</div>

				<!-- Login Mode -->
				<?php if ($mode == "form-login") { ?>
					<div class="auth-header">
						<h2>Sign In</h2>
						<p>Silakan masukkan Email &amp; Password akun Anda</p>
					</div>

					<?= $this->session->flashdata('error') ? '<div class="alert alert-danger auth-alert text-center" role="alert">' . $this->session->flashdata('error') . '</div>' : '' ?>
					<?= $this->session->flashdata('success') ? '<div class="alert alert-success auth-alert text-center" role="alert">' . $this->session->flashdata('success') . '</div>' : '' ?>

					<?= form_open("auth/oauth", array('id' => 'kt_login_signin_form', 'class' => 'form', 'autocomplete' => 'off')); ?>
					<div>
						<div class="auth-form-group">
							<label class="auth-label" for="email">Email Address</label>
							<div class="auth-input-group">
								<span class="auth-input-leading">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="M22 7l-10 6L2 7"/></svg>
								</span>
								<input id="email" name="email" type="email" class="form-control" placeholder="nama@email.com" required />
							</div>
						</div>

						<div class="auth-form-group">
							<label class="auth-label" for="login_password">Password</label>
							<div class="auth-input-group">
								<span class="auth-input-leading">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
								</span>
								<input name="password" type="password" class="form-control" id="login_password" placeholder="••••••••" required />
								<span class="auth-input-group-addon toggle-password" data-target="#login_password" style="cursor: pointer;" title="Tampilkan / Sembunyikan Password">
									<svg class="eye-show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M2 12C4.5 7 8 5 12 5C16 5 19.5 7 22 12C19.5 17 16 19 12 19C8 19 4.5 17 2 12Z"></path>
										<circle cx="12" cy="12" r="3"></circle>
									</svg>
									<svg class="eye-hide" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
										<line x1="2" y1="2" x2="22" y2="22"></line>
									</svg>
								</span>
							</div>
						</div>

						<div class="auth-actions">
							<button type="submit" class="btn-auth-submit" id="btn-submit">
								<span>Sign In</span>
								<svg class="btn-arrow-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
							</button>
						</div>

						<div class="auth-bottom-text">
							Belum punya akun? <a href="<?= base_url('auth/register/form-register') ?>">Daftar Sekarang</a>
						</div>
					</div>
					<?= form_close() ?>

				<!-- Register Mode -->
				<?php } else if ($mode == "form-register") { ?>
					<div class="auth-header">
						<h2>Sign Up</h2>
						<p>Silakan isi biodata Anda dengan benar</p>
					</div>

					<?= $this->session->flashdata('error') ? '<div class="alert alert-danger auth-alert" role="alert">' . $this->session->flashdata('error') . '</div>' : '' ?>
					<?= $this->session->flashdata('success') ? '<div class="alert alert-success auth-alert" role="alert">' . $this->session->flashdata('success') . '</div>' : '' ?>

					<?= form_open("auth/register", array('id' => 'kt_login_signin_form', 'class' => 'form', 'autocomplete' => 'off')); ?>
					<div>
						<div class="auth-section-divider">Data Diri</div>

						<div class="auth-form-group">
							<label class="auth-label" for="nama">Nama Lengkap</label>
							<div class="auth-input-group">
								<span class="auth-input-leading">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
								</span>
								<input class="form-control" type="text" placeholder="Masukkan Nama Lengkap" name="nama" id="nama" required>
							</div>
						</div>

						<div class="auth-form-group">
							<label class="auth-label" for="no_hp">No Handphone</label>
							<div class="auth-input-group">
								<span class="auth-input-leading">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
								</span>
								<input class="form-control" type="text" placeholder="08xxxxxxxxxx" name="no_hp" id="no_hp" autocomplete="off" required>
							</div>
						</div>

						<div class="auth-section-divider">Akun Email &amp; Password</div>

						<div class="auth-form-group">
							<label class="auth-label" for="email">Email</label>
							<div class="auth-input-group">
								<span class="auth-input-leading">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="M22 7l-10 6L2 7"/></svg>
								</span>
								<input class="form-control" type="email" placeholder="nama@email.com" name="email" id="email" autocomplete="off" required>
							</div>
						</div>

						<div class="auth-form-group">
							<label class="auth-label" for="password">Password</label>
							<div class="auth-input-group">
								<span class="auth-input-leading">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
								</span>
								<input class="form-control" type="password" placeholder="••••••••" name="password" id="password" required>
								<span class="auth-input-group-addon toggle-password" data-target="#password" style="cursor: pointer;" title="Tampilkan / Sembunyikan Password">
									<svg class="eye-show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M2 12C4.5 7 8 5 12 5C16 5 19.5 7 22 12C19.5 17 16 19 12 19C8 19 4.5 17 2 12Z"></path>
										<circle cx="12" cy="12" r="3"></circle>
									</svg>
									<svg class="eye-hide" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
										<line x1="2" y1="2" x2="22" y2="22"></line>
									</svg>
								</span>
							</div>
						</div>

						<div class="auth-form-group">
							<label class="auth-label" for="cpassword">Konfirmasi Password</label>
							<div class="auth-input-group">
								<span class="auth-input-leading">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
								</span>
								<input class="form-control" type="password" placeholder="••••••••" name="cpassword" id="cpassword" required>
								<span class="auth-input-group-addon toggle-password" data-target="#cpassword" style="cursor: pointer;" title="Tampilkan / Sembunyikan Password">
									<svg class="eye-show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M2 12C4.5 7 8 5 12 5C16 5 19.5 7 22 12C19.5 17 16 19 12 19C8 19 4.5 17 2 12Z"></path>
										<circle cx="12" cy="12" r="3"></circle>
									</svg>
									<svg class="eye-hide" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
										<line x1="2" y1="2" x2="22" y2="22"></line>
									</svg>
								</span>
							</div>
						</div>

						<div class="auth-actions">
							<button type="button" class="btn-auth-submit btn-submit-daftar">
								<span>Daftar Sekarang</span>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
							</button>
							<a class="btn-auth-secondary" href="<?= base_url('auth') ?>">Kembali ke Sign In</a>
						</div>
					</div>
					<?= form_close() ?>
				<?php } ?>
			</div>
		</main>

		<!-- Bottom Bar: Centered Copyright -->
		<footer class="auth-bottombar">
			<div class="auth-slide-caption">
				<span>&copy; 2022 - <?= date('Y') ?> <strong>PT VISI YOSINDO MEDIKAL</strong>. All Rights Reserved.</span>
			</div>
		</footer>
	</div>

	<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
	<script src="<?= base_url() ?>assets/vendor/jquery/jquery.js"></script>
	<script>
		if (typeof window.jQuery === 'undefined') {
			document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"><\/script>');
		}
	</script>
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
	<script src="<?= base_url('assets/') ?>js/sweetalert2/sweetalert2.min.js"></script>
	<script src="<?= base_url('assets/') ?>js/global.js"></script>

	<script>
		let token = (document.querySelector('input[name=token]') || {}).value || '';

		document.addEventListener('DOMContentLoaded', function() {
			// Vanilla JS: Password Show/Hide toggle logic
			document.querySelectorAll('.toggle-password').forEach(function(toggleBtn) {
				toggleBtn.addEventListener('click', function() {
					var targetSelector = toggleBtn.getAttribute('data-target');
					var input = targetSelector ? document.querySelector(targetSelector) : null;
					if (!input) return;

					var eyeShow = toggleBtn.querySelector('.eye-show');
					var eyeHide = toggleBtn.querySelector('.eye-hide');

					if (input.getAttribute('type') === 'password') {
						input.setAttribute('type', 'text');
						if (eyeShow) eyeShow.style.display = 'none';
						if (eyeHide) eyeHide.style.display = 'inline';
					} else {
						input.setAttribute('type', 'password');
						if (eyeShow) eyeShow.style.display = 'inline';
						if (eyeHide) eyeHide.style.display = 'none';
					}
				});
			});

			// AJAX form submit for login with button loading protection & sweetalert
			var loginForm = document.getElementById('kt_login_signin_form');
			var loginPassword = document.getElementById('login_password');
			if (loginForm && loginPassword) {
				loginForm.addEventListener('submit', function(e) {
					e.preventDefault();
					var submitBtn = document.getElementById('btn-submit');
					var originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

					if (submitBtn) {
						submitBtn.disabled = true;
						submitBtn.innerHTML = '<span>Memproses...</span>';
					}

					if (typeof Swal !== 'undefined') {
						Swal.fire({
							allowOutsideClick: false,
							showConfirmButton: false,
							background: 'transparent',
							backdrop: 'rgba(15, 23, 42, 0.45)',
							didOpen: function() {
								Swal.showLoading();
							}
						});
					}

					var startTime = new Date().getTime();
					fetch(loginForm.getAttribute('action'), {
						method: 'POST',
						headers: { 'X-Requested-With': 'XMLHttpRequest' },
						body: new FormData(loginForm)
					})
						.then(function(res) { return res.json(); })
						.then(function(resp) {
							var delay = Math.max(0, 450 - (new Date().getTime() - startTime));
							setTimeout(function() {
								if (resp.status === 'success') {
									if (typeof Swal !== 'undefined') {
										Swal.fire({
											title: 'Login Berhasil!',
											text: resp.msg,
											icon: 'success',
											timer: 1300,
											showConfirmButton: false
										}).then(function() {
											window.location.href = resp.redirect;
										});
									} else {
										window.location.href = resp.redirect;
									}
								} else {
									if (submitBtn) {
										submitBtn.disabled = false;
										submitBtn.innerHTML = originalBtnHtml;
									}
									if (typeof Swal !== 'undefined') {
										Swal.fire({
											title: 'Login Gagal',
											text: resp.msg,
											icon: 'error'
										});
									}
								}
							}, delay);
						})
						.catch(function() {
							var delay = Math.max(0, 450 - (new Date().getTime() - startTime));
							setTimeout(function() {
								if (submitBtn) {
									submitBtn.disabled = false;
									submitBtn.innerHTML = originalBtnHtml;
								}
								if (typeof Swal !== 'undefined') {
									Swal.fire({
										title: 'System Error',
										text: 'Terjadi kesalahan sistem, silakan coba beberapa saat lagi.',
										icon: 'error'
									});
								}
							}, delay);
						});
				});
			}
		});
	</script>

	<?php if ($mode == 'form-register') { ?>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
		<script>
			var map;
			var popup;

			document.addEventListener("DOMContentLoaded", function() {
				if (document.getElementById('mapid')) {
					initMap();
				}

				$('.btn-submit-daftar').on('click', function() {
					const form = $(this).closest('form');
					const url = form.attr('action');
					const formId = form.attr('id');
					Swal.fire({
						title: 'Apakah anda yakin?',
						text: 'Pastikan data yang anda masukkan sudah benar!',
						icon: 'question',
						showCancelButton: true,
						confirmButtonText: 'Ya',
						cancelButtonText: 'Batal'
					}).then(function(result) {
						if (result.value || result.isConfirmed) {
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
										});
									} else {
										handleResponse(resp);
									}
								}
							});
						}
					});
				});
			});

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
				var longt = parseFloat(e.latlng['lng']).toFixed(8);
				var lat = parseFloat(e.latlng['lat']).toFixed(8);
				$('#longitude').val(longt);
				$('#latitude').val(lat);
				$('#longitude_text').html(parseFloat(longt));
				$('#latitude_text').html(parseFloat(lat));
				popup
					.setLatLng(e.latlng)
					.setContent("Alamat Saya Disini")
					.openOn(existMap);
			}
		</script>
	<?php } ?>
</body>

</html>
