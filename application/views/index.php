<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
	<base href="<?= base_url() ?>">
	<!-- Basic -->
	<meta charset="UTF-8">

	<title><?= $page_title ?> | <?= $this->config->item('apps_name') ?></title>
	<meta name="keywords" content="Sistem Informasi" />
	<meta name="description" content="<?= $this->config->item('apps_name') ?>">

	<!-- Mobile Metas -->
	<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php include 'includes_css.php'; ?>

	<!-- Head Libs -->
	<script src="<?= base_url('assets/') ?>vendor/modernizr/modernizr.js"></script>

</head>

<body>
	<section class="body">

		<!-- start: header -->
		<?php include 'includes_header.php'; ?>
		<!-- end: header -->

		<div class="inner-wrapper">
			<!-- start: sidebar -->
			<?php
			if ($switch == "home") {
				include 'includes_aside.php';
			} else if ($switch == "kepegawaian") {
				include 'includes_aside_kepegawaian.php';
			} else if ($switch == "helpdesk") {
				include 'includes_aside_helpdesk.php';
			} else if ($switch == "inventory") {
				include 'includes_aside_inventory.php';
			} else if ($switch == "dokumen") {
				include 'includes_aside_dokumen.php';
			} else if ($switch == "marketing") {
				include 'includes_aside_marketing.php';
			} else if ($switch == "visilab") {
				include 'includes_aside_visilab.php';
			} else if ($switch == "accounting") {
				include 'includes_aside_accounting.php';
			}

			?>
			<!-- end: sidebar -->

			<section role="main" class="content-body content-body-modern mt-0">
				<!-- start: page -->
				<?php include 'pages/' . $page_name . '.php'; ?>
				<!-- end: page -->
			</section>

			<?php if (isAdmin()) {
				$this->load->view('includes_admin_modal_edit');
			} ?>
			<?php include 'includes_js.php'; ?>
	</section>
</body>

</html>