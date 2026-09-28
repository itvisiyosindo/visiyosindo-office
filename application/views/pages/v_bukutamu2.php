<!doctype html>
<html class="fixed">

<head>
	<title>Buku Tamu </title>
</head>

<body>
	<section class="body-sign body-locked">
		<div class="center-sign">
			<div class="panel card-sign">
				<div class="card-body">
    					<div class="current-user text-center">
    						<img src="<?= base_url() ?>assets/img/logovym2023.png" alt="logo pks" class="rounded-circle user-image" />
    						<h2 class="user-name text-dark m-0">Buku Tamu</h2>
    					</div
    					<?= form_open("BukuTamu/register", array('id' => 'kt_bukutamu_form', 'class' => 'form', 'autocomplete' => 'off')); ?>
						<div class="lgn-form">
							<div class="form-group mb-3">
							    <div class="input-group">
									<input name="kode" type="hidden" class="form-control"/>
								</div>
								<div class="input-group">
									<input name="nama" type="text" class="form-control" placeholder="Nama" />
								</div>
								<br>
								<div class="input-group">
									<input name="jabatan" type="text"class="form-control" placeholder="Jabatan" />
								</div>
								<br>
								<div class="input-group">
									<input name="nomorwa" type="number" class="form-control" placeholder="Nomor WA" />
								</div>
								<br>
								<div class="input-group">
									<input name="email" type="email" class="form-control" placeholder="Email" />
								</div>
								<br>
								<div class="input-group">
									<input name="instansi" type="text" class="form-control" placeholder="Instansi" />
								</div>
								<br>
								<div class="input-group">
									 <select class="select-transaction input-group-sm form-control" name="provinsi" id="provinsi">
                                            <?php if ($provinsi != NULL): ?>
                                                <option value=''>— Pilih Provinsi —</option>
                                                <?php foreach ($provinsi as $value): ?>
                                                <option value="<?php echo $value->kode;?>"><?php echo $value->nama;?></option>
                                                <?php endforeach;?>
                                                <?php else:?>
                                                <option value=''>— Tidak ada data —</option>
                                            <?php endif;?>
                                            </select>
                                            <?php echo form_error('provinsi');?>
								</div>
								<br>
								<div class="input-group">
									<select name="kota" class="form-control" id="kota">
                   							<option value=''>- Pilih Kabupaten/Kota -</option>
                  					</select>
                                    <?php echo form_error('kota');?>
								</div>
							</div>
							<div class="row">
								<div class="col-8">
									<button type="submit" class="btn btn-primary pull-right" id="btn-submit">Register</button>
								</div>
							</div>
						</div>
						<?= form_close() ?>
				</div>
			</div>
			<div style="width: 100%;" class="text-center">
				<div class="text-light" style="position: absolute;bottom: -5;width: 100%;">&copy;All Rights Reserved 2022. PT VISI YOSINDO MEDIKAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
			</div>
		</div>
	</section>
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
	</script>

		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
		<script>
			var map;
			var popup;

			document.addEventListener("DOMContentLoaded", function() {
				
				$('#provinsi').on('change', function (){
           		    var kode = this.value;
    				var url = "<?php echo site_url('calonpelanggan/add_ajax_kota');?>/"+kode;
    				$('#kota').load(url);
					return false;
             	});
             	

				$('#btn-submit').on('click', function() {
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
					    console.log(result)
					})
				})
			})

			
		</script>
	
</body>

</html>