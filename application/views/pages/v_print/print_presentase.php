<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Form Permintaan Presentase | <?= $this->config->item('apps_name') ?></title>
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
            padding: 0px 0px 0px 0px;
        }

        @media only screen and (min-width: 768px) {
            html.modern .header:not(.header-nav-menu) .logo {
                line-height: 0px;
                padding: 0 0 0 0;
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
		   margin-top: 0px;
		   margin-bottom: 0px;
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
    <section class="body" style="padding-top:0px;padding-left:0px;">
        <img src="assets/img/kop_baru.jpg" alt="Logo" style="width: 100%;" />
				<hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
            <section role="main" class="content-body content-body-modern" style="padding-top:11px; padding-bottom:0px;">
                <!-- start: page -->
					
                    <table style="font-family:Times New Roman; font-size:12px" border="1" width="100%">
                            
						<tr>
							<th style="border: none;" width="25%"></th>
							<th colspan="4" style="height:20px; text-align:center; vertical-align:top; border: none; font-size:15px"><font color='#000000'> FORM PRESENTATION / TRAINING USER / DEMO REQUEST <hr></hr></th>
                            <td style="text-align:left; border: none;" width="25%"> </td>
						</tr>
                        <tr>
							<th style="border: none;" width="25%"></th>
							<th colspan="4" style="height:20px; text-align:center; vertical-align:top; border: none;"><font color='#000000'>No : <?= $data_fpp[0]->kode ?></th>
                            <td style="text-align:left; border: none;" width="25%"> </td>
						</tr>
                    </table>
                    <br><br>

                    <table style="font-family:Times New Roman; font-size:12px" border="1" width="100%">

						<tr>
							<th colspan="" style="text-align:left; border: none; font-size:12px"><font color='#000000'> Customer Name : &nbsp;<?= $data_fpp[0]->csName ?></th>
                            <th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none;"><font color='#000000'> </th>
                            <td style="text-align:left; border: none;" width="7%"> </td>
                            <th colspan="" style="height:10px; border: none;"><font color='#000000'>&nbsp; INFORMATION </th>
							<th colspan="" style="height:10px; border: none;"></th>
						</tr>
						
						<tr>
							<td style="text-align:left">
								<font color='#000000'>&nbsp;Alamat: <?= $data_fpp[0]->alamat ?> 
							</td>
                            <td style="text-align:left; border: none;" width="7%"></td>
                            <th style="border: none;" width="10%"></th>	
                            <td style="text-align:left; border: none;"><font color='#000000'>&nbsp; Invoice / Quotation :  <?= $data_fpp[0]->invoice ?> </td>
					
						</tr>
                        <tr>
							<td style="text-align:left">
								<font color='#000000'>&nbsp;Contact Person: <?= $data_fpp[0]->noCp ?> 
							</td>
                            <td style="text-align:left; border: none;" width="7%"></td>
                            <th style="border: none;" width="10%"></th>	
                            <td style="text-align:left; border: none;"><font color='#000000'>&nbsp; Technician / Aplicant Name :  <?= $data_fpp[0]->teknisi ?> </td>
					
						</tr>
						<tr>
							<td style="text-align:left">
								<font color='#000000'>&nbsp;Mr / Mrs: <?= $data_fpp[0]->cpName ?> 
							</td>
                            
						</tr>
					</table>



					<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
						<tr>
						</tr>
						<tr>
							<?= 
								$dt_pengajuan	= strtotime($data_fpp[0]->tgl_Pengajuan);
								$tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);
							?>
							
							<tr>
								<td><font color="white">i </font></td>
							</tr>
						</tr>
                    </table>
            </section>
			
			<section role="main" class="content-body content-body-modern" style="padding-top:5px; padding-bottom:0px;">
                <div class="table-responsive">
                    <table border="1" id="kt_table_1" style="font-family:Times New Roman; font-size:12px" width="100%">
                    
                        
                            <tr>
                                <th style="text-align:center" bgcolor="#b0bccc" height="35px" width="6%"> <font color='#000000'> No </th>
                                <th style="text-align:center" bgcolor="#b0bccc"> <font color='#000000'> Description Equipment </th>
                                <th style="text-align:center" bgcolor="#b0bccc" width="40%"> <font color='#000000'> Request Detail </th>
                            </tr>
                        
                        
							
							<?php 
								$x=1;
								$nom = 0;
								foreach ($detail_fpp as $row) {
								$x = $x+1;
                                $xy = $xy+1;
								//$nom = $row->nominal + $nom;
							?>
								<tr>
                                    <td style="text-align:center"><font color='#000000'><?= $xy ?> </td>
                                    <td class="des" > <font color='#000000'>&nbsp;<?= $row->des ?></td>
                                    <td class="kom" style="text-align:center"> <font color='#000000'>&nbsp;<?= $row->req ?></td>
                                </tr>
                            <?php } ?>
							<?php for($kosong=$x;$kosong<=15;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
							<?php } ?>							
                        
                    </table>
                    
                </div>
                

                <table border="1" style="font-family:Times New Roman; font-size:12px" width="100%">
                        <tr>
							<td style="text-align:left; border: none;" height="15px">
								<font color='#000000'> </b>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:left" height="45px">
								<font color='#000000'>&nbsp;Notes: <b> <?= $data_fpp[0]->noTes ?> </b>
							</td>
						</tr>
                        <tr>
							<td style="text-align:left; border: none;" height="15px">
								<font color='#000000'> </b>
							</td>
						</tr>
					</table>
			</section>
			
			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                    


                <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:12px;">
					<tbody>
						<tr style="height: 35px;">
							<td style="width:100%; height:35px; text-align:left; vertical-align:top;" colspan="3">
								<font color='#000000'> <?= $data_fpp[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
							</td>
						</tr>
						<tr style="height: 18px;">
							<td style="text-align:center; width:25%;" ><font color='#000000'>Diajukan Oleh,</td>
							<td></td>
							<td style="text-align:center; width:44%;" ><font color='#000000'></td>
						</tr>
						<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_fpp[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:left;"><font color='#000000'>Notes<br>- Harap diisi dengan jelas dan lengkap<br>- Apabila Paket Mesin (CR / DR / Xray / dll) dengan
                        Detail informasi alat/mesin , No SN / Invoice pembelian<br>- Jika Instalasi membutuhkan jasa Aplicant maka cantumkan
                        detail permintaan nya pada detail request</td>
					</tr>
						<tr>
							<td style="text-align:center; "><font color='#000000'><?= $data_fpp[0]->nama_ttd ?><hr></hr></td>
							<td style="text-align:center; "></td>
							<td style="text-align:left; "><font color='#000000'></td>
						</tr>
						<tr>
							<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $data_fpp[0]->jabatan ?></i></td>
							<td style="text-align:center; "></td>
							<td style="text-align:left; vertical-align:top;"><font color='#000000'><i></i></td>
						</tr>
					</tbody>
				</table>
                <br><br>
                

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