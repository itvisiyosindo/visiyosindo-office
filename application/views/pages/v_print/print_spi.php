<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> SURAT SKORSING | <?= $this->config->item('apps_name') ?></title>
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
					
             

         <table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
			
						<tr>
								<td style="text-align:right">Pekanbaru, &nbsp;<?=  date('d-m-Y',strtotime($data_istirahat[0]->created_at)) ?></td>
						</tr>

						


				

				</table>
				<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
			
						<tr>
								<td width="10%">No </td>
								<td>:&nbsp;&nbsp;<?= $data_istirahat[0]->kode?></td>
						</tr>
					<tr>
						<td width="10%">Perihal </td>
						<td>:&nbsp;&nbsp;Pemberian Skorsing</td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>


				

				</table>

				<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
			
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Yang bertanda tangan dibawah ini :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>
						<td width="10%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->pengaju?>  </td>
					</tr>
					<tr>
						<td width="10%">NPP </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->npp?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_istirahat[0]->jabatan?></td>
					</tr>
				</tr>
				</tr>
				<tr>
						<td><font color="white">i </font></td>
					</tr>
			</table>



			<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
				

				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan sangat terpaksa memberikan skorsing kepada :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>
						<td width="10%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->penerima?>  </td>
					</tr>
					<tr>
						<td width="10%">NPP </td>
						<td>:&nbsp;&nbsp; <?= $data_istirahat[0]->npp2?> </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_istirahat[0]->jabatan2?></td>
					</tr>
				</tr>
				</tr>
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
			</table>

		<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
				<tr>
          <td colspan="4">Untuk tidak masuk kerja selama&nbsp;&nbsp;<?= $data_istirahat[0]->masa?>&nbsp;&nbsp;terhitung sejak tanggal&nbsp;&nbsp;<?=  date('d-m-Y',strtotime($data_istirahat[0]->tanggal)) ?>&nbsp;&nbsp;dan dilakukan pemotongan gaji sesuai dengan perhitungan hari kerja yang tidak diikutinya.</td>
        </tr>
				
				<tr>
						<td><font color="white">i </font></td>
					</tr>
				<tr>
          <td colspan="4">Demikian Surat Skorsing ini kami buat dan kami berikan kepada yang bersangkutan agar dipatuhi dan dipergunakan sebagaimana mestinya.</td>
        </tr>
				
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
					
				<tr>
						<td><font color="white">i </font></td>
					</tr>
			</table>
								


			<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
				<tbody>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" ></td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" ></td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" >Mengetahui,</td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttd1 		= $img_path."ttd_notyet2.png";
							
							if($data_istirahat[0]->ttd == '1'){
								$ttd1 = $img_path."ttd_".$data_istirahat[0]->id_hr."_cap.png";
							}else if($data_istirahat[0]->ttd == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:25%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_istirahat[0]->pengaju ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_istirahat[0]->jabatan ?></i></td>
					</tr>
				</tbody>
				
			</table>


			<br><br><br><br><br><br><br>
								

			
                    
                </div>
                
			</section>
			
			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                    


                
            <br><br><br><br>
                

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