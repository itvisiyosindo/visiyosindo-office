<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Pengajuan Limit GoCorp | <?= $this->config->item('apps_name') ?></title>
    <meta name="keywords" content="Sistem Informasi" />
    <meta name="description" content="<?= $this->config->item('apps_name') ?>">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- includes_css.php -->
    <!-- Web Fonts  
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,600,700,800,900" rel="stylesheet" type="text/css">
	-->

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/animate/animate.compat.css">

    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/boxicons/css/boxicons.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2/css/select2.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/basic.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/dropzone.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/datatables/media/css/dataTables.bootstrap4.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/simple-line-icons/css/simple-line-icons.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>js/sweetalert2/sweetalert2.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>js/croppie/croppie.css" />

    <!--(remove-empty-lines-end)-->

    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/theme.css" />



    <!-- Theme Layout -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/layouts/modern.css" />
    <!--(remove-empty-lines-end)-->



    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/') ?>css/custom.css">
    <link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.png" />
    <!-- Head Libs -->

    <style>
        html.modern html,
        html.modern body {
            background: white !important;
			//color: red;
			//color: rgb(0, 0, 0);
			color : #d41c0f;
        }

        .page-header {
            background: #1D2127 !important;
        }

        .header .logo-container {
            background-image: none;
            background-color: #1D2127;
            border-bottom-color: #161a1e;
            border-top-color: #1D2127;
            /* background-color: #1D2127; */

        }

        .modal-header {
            padding: 0px 5px 5px 5px;
        }

        @media only screen and (min-width: 768px) {
            html.modern .header:not(.header-nav-menu) .logo {
                line-height: 0px;
                padding: 5px 20px 0 15px;
                font-size: 18px
            }
        }

        @media (max-width: 767px) {
            html.modern .header .logo-container .logo {
                margin-top: 0px;
                line-height: 0px;
                font-size: 18px
            }
        }
		
		hr{
		   display: block;
		   margin-top: 0em;
		   margin-bottom: 0em;
		   margin-left: auto;
		   margin-right: auto;
		   border-top: 2px solid black;
		}
		
		.mod-u {
		  text-decoration-line: underline;
		  text-underline-position: under;
		  text-decoration-style: solid;
		  text-decoration-color: black;
		  text-decoration-thickness: 5px;
		}
		
		.mylabel {
			border:1px solid blue;
			display: table-cell;
			width: 100%;
		}
		
		.mylabel2 {
			border:1px solid blue;
			display: table-cell;
			width: 20%;
		}
    </style>
    <!-- end includes_css.php -->


    <!-- Head Libs -->
    <script src="<?= base_url('assets/') ?>vendor/modernizr/modernizr.js"></script>

</head>
<body style="background-color:white; font-color:black;">
    <section class="body" style="padding-top:0px;">
        <img src="assets/img/kop_baru.jpg" alt="Logo" style="width: 100%;" />
		<hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
            <section role="main" class="content-body content-body-modern" style="padding-top:13px; padding-bottom:0px;">
                <!-- start: page -->
					
                    <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
						<tr>
							<th width="24%"></th>
							<th colspan="" style="height:27px; text-align:center; vertical-align:top;"><font color='#000000'> PENGAJUAN LIMIT GOJEK CORPORATE <hr></hr><hr></hr></th>
							<th width="24%"></th>
						</tr>
						<tr>
							<th colspan="3" style="text-align:center"><font color='#000000'> No : <?= $data_gc[0]->kode ?> </th>
						</tr>
						<tr>
							<td><font color="white">i </font></td>
						</tr>
					</table>
					<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
						<tr>
							<td colspan="3" style="text-align:left">
								<font color='#000000'> Dengan ini saya mengajukan limit gojek corporate :
							</td>
						</tr>
						<tr>
							<?= 
								$dt_pengajuan	= strtotime($data_gc[0]->tglPengajuan);
								$dt_pergi		= strtotime($data_gc[0]->tglPergi);
								$dt_pulang		= strtotime($data_gc[0]->tglKembali);
								
								$tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);
								$hari_pergi 	= date("D", $dt_pergi);
								$tgl_pergi 		= date("d", $dt_pergi)." - ".date("m", $dt_pergi)." - ".date("Y", $dt_pergi);
								$pergi 	        = date_create(date("d", $dt_pergi)."-".date("m", $dt_pergi)."-".date("Y", $dt_pergi));
							    $pulang 	    = date_create(date("d", $dt_pulang)."-".date("m", $dt_pulang)."-".date("Y", $dt_pulang));
							    $lama_hari 		= date_diff($pergi, $pulang);
							    $lama_hari		= $lama_hari->format("%d") + 1;
								
								switch($hari_pergi){
									case 'Sun':
										$hari_pergi = "Minggu";
									break;
									case 'Mon':         
										$hari_pergi = "Senin";
									break;
									case 'Tue':
										$hari_pergi = "Selasa";
									break;
									case 'Wed':
										$hari_pergi = "Rabu";
									break;
									case 'Thu':
										$hari_pergi = "Kamis";
									break;
									case 'Fri':
										$hari_pergi = "Jumat";
									break;
									case 'Sat':
										$hari_pergi = "Sabtu";
									break;									
								}
							?>
							<td rowspan="9" width="7%"></td>
							<tr>
								<td width="22%"><font color='#000000'> Nama </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $data_gc[0]->pengaju ?>  </td>
							</tr>
							<tr>
								<td><font color='#000000'> Jabatan </td>
								<td><font color='#000000'> :&nbsp;&nbsp;  <?= $data_gc[0]->jabatan ?></td>
							</tr>
							<tr>
								<td><font color='#000000'> Kota Tujuan </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $data_gc[0]->kota ?></td>
							</tr>
							<tr>
								<td><font color='#000000'> Keperluan </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $data_gc[0]->perihal ?></td>
							</tr>
							<tr>
								<td><font color='#000000'> Hari </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $hari_pergi ?></td>
							</tr>
							<tr>								
								<td><font color='#000000'> Tanggal </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $tgl_pergi ?></td>
							</tr>
							<tr>
								<td><font color='#000000'> Lama Perjalanan </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $lama_hari ?> hari</td>
							</tr>
							<tr>
								<td><font color="white">i </font></td>
							</tr>
						</tr>
                    </table>
            </section>
			
			<section role="main" class="body" style="padding-top:0px;">
                <div class="table-responsive">
                    <table border="1" id="kt_table_1" style="font-family:Times New Roman; font-size:15px" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> <font color='#000000'> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> <font color='#000000'> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%"> <font color='#000000'> Nominal </th>
                            </tr>
                        </thead>
                        <tbody>
							
							<?php 
								$x=1;
								$nom = 0;
								foreach ($detail as $row) {
								$x = $x+1;
								$nom = $row->nominal + $nom;
							?>
								<tr>
                                    <td class="tgl" style="text-align:center"> <font color='#000000'> <?= $row->tgl ?></td>
                                    <td class="ket" > <font color='#000000'>&nbsp;<?= $row->ket ?></td>
                                    <td class="nom" style="text-align:right"> <font color='#000000'>
										<label class="mylabel2" style="text-align:left">&nbsp;Rp. </label>
										<label class="mylabel" style="text-align:right"><?= number_format($row->nominal,0,",",".") ?>,-&nbsp;</label>
									</td>
                                </tr>
                            <?php } ?>
							<?php for($kosong=$x;$kosong<=15;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
							<?php } ?>							
                        </tbody>
                        <tfoot>
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="2" style="text-align:center"><font color='#000000'> TOTAL</th>
                                <td bgcolor="#b7d5ac" style="text-align:right"> <font color='#000000'>
									<label class="mylabel2" style="text-align:left">&nbsp;Rp. </label>
									<label class="mylabel" style="text-align:right"><?= number_format($nom,0,",",".") ?>,-&nbsp;</label>
								</td>
                            </tr>
                            <tr>
                            </tr>
                        </tfoot>
                    </table>
                </div>
			</section>
			<br><br>
			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>
						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">
								<font color='#000000'> <?= $data_gc[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
							</td>
						</tr>
						<tr style="height: 18px;">
							<td style="text-align:center; width:25%;" ><font color='#000000'>Diajukan Oleh,</td>
							<td></td>
							<td style="text-align:center; width:25%;" ><font color='#000000'>Diverifikasi Oleh,</td>
						</tr>
						<tr style="height:60px;">
							<?php
								$img_path 	= "uploads/file_karyawan/ttd/";
								$ttdaju		= $img_path."ttd_".$data_gc[0]->idPengaju.".png";
								$ttd1 		= $img_path."ttd_notyet2.png";
								
								if($data_gc[0]->aju_ttd1 == '1'){
									$ttd1 = $img_path."ttd_107.png";
								}else if($data_gc[0]->aju_ttd1 == '2'){
									$ttd1 = $img_path."ttd_not.png";
								}
							?>
							<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
							<td style="text-align:center;"></td>
							<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						</tr>
						<tr>
							<td style="text-align:center; "><font color='#000000'><?= $data_gc[0]->nama_ttd ?><hr></hr></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; "><font color='#000000'>Dirangga Madali<hr></hr></td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $data_gc[0]->jabatan ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:center; vertical-align:top;"><font color='#000000'><i>Senior Accounting and Finance</i></td>
						</tr>
					</tbody>
				</table>

                <script>
                    window.onload = function() {
                        window.print();
                    }
                </script>
                <!-- end: page -->
            </section>
            <!-- end includes_js.php -->
            <!-- Vendor -->
            <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-cookie/jquery.cookie.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/popper/umd/popper.min.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/common/common.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/nanoscroller/nanoscroller.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/magnific-popup/jquery.magnific-popup.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-placeholder/jquery.placeholder.js"></script>

            <!-- Specific Page Vendor -->
            <script src="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/jquery-validation/jquery.validate.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/select2/js/select2.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/dropzone/dropzone.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/datatables/media/js/jquery.dataTables.min.js"></script>
            <script src="<?= base_url('assets/') ?>vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
            <script src="<?= base_url('assets/') ?>js/global.js"></script>
            <script src="<?= base_url('assets/') ?>js/jquery.mask.min.js"></script>
            <script src="<?= base_url('assets/') ?>js/table2excel.min.js"></script>
            <script src="<?= base_url('assets/') ?>/js/sweetalert2/sweetalert2.min.js"></script>
            <script src="<?= base_url('assets/') ?>/js/chart/chart.min.js"></script>
            <script src="<?= base_url('assets/') ?>/js/ckeditor/ckeditor.js"></script>
            <script src="<?= base_url('assets/') ?>/js/croppie/croppie.js"></script>

            <!--(remove-empty-lines-end)-->

            <!-- Theme Base, Components and Settings -->
            <script src="<?= base_url('assets/') ?>js/theme.js"></script>


            <!-- Theme Initialization Files -->
            <script src="<?= base_url('assets/') ?>js/theme.init.js"></script>

            <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

            <script>
                let token = $('input[name=token]').val()
                // Maintain Scroll Position
                if (typeof localStorage !== 'undefined') {
                    if (localStorage.getItem('sidebar-left-position') !== null) {
                        var initialPosition = localStorage.getItem('sidebar-left-position'),
                            sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                        sidebarLeft.scrollTop = initialPosition;
                    }
                }
            </script>
        </div>
        <!-- includes_js.php -->
        <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
    </section>
</body>

</html>