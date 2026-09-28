<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Pengalaman Kerja | <?= $this->config->item('apps_name') ?></title>
    <meta name="keywords" content="Sistem Informasi" />
    <meta name="description" content="<?= $this->config->item('apps_name') ?>">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- includes_css.php -->
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,600,700,800,900" rel="stylesheet" type="text/css">

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
<font color='#000000'>
<body style="background-color:white; font-color:black;">
    <section class="body" style="padding-top:0px;">
        <img src="assets/img/kop_new.jpg" alt="Logo" style="width: 100%;" />
        <hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
<!-- TABEL SURAT TUGAS --> 
<section role="main" class="body" style="padding-top:0px;">
 
    <div class="table-responsive">
    <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:12px" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td colspan="6" style="text-align:center; font-size:15px; font-weight:bold;"><br>
                        	        <font color="#000000"><u>SURAT KETERANGAN PENGALAMAN KERJA</u></font> 
                        		</td>
                            </tr>
                            <tr>
                        		<td colspan="6" style="text-align:center; font-size:15px;">
                        	        <font color="#000000"> No : <?= $data_pk[0]->kode ?> </font> <br><br>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4" style="text-align:center; font-size:14px; font-weight:bold;"></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i</font></td>
                        	</tr>

							<tr>
                                <td></td>
                                <td colspan="4"> <font color="#000000"><br><br>
                                Dengan Surat ini kami dari PT. Visi Yosindo Medikal menyatakan bahwa :

                                    </font>
                                </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                                <td></td>
                        		<td colspan="4"><font color="#000000">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    :&nbsp;<?= $data_pk[0]->pegawai ?></font></td>
                        	</tr>
                        	<tr>
                                <td></td>
                        		<td colspan="4"><font color="#000000">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    NIK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    :&nbsp;<?= $data_pk[0]->nik ?></font></td>
                        	</tr>
                        	<tr>
                                <td></td>
                        		<td colspan="4"><font color="#000000">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Jabatan&nbsp;&nbsp;&nbsp;&nbsp;
                                    :&nbsp;<?= $data_pk[0]->jabatan ?></font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                                <td></td>
                        		<td colspan="5"><font color="#000000">Dinyatakan benar telah bekerja di PT. Visi Yosindo Medikal terhitung sejak  : 
                                    &nbsp; <?= date('d-m-Y',strtotime($data_pk[0]->tgl_masuk)) ?> &nbsp;
                        		    sampai dengan &nbsp;&nbsp; <?= date('d-m-Y',strtotime($data_pk[0]->tgl_keluar)) ?> 
                                    &nbsp;&nbsp; dengan Jabatan terakhir sebagai &nbsp;&nbsp; <?= $data_pk[0]->jabatan ?>. </font></td>
                                <td></td>
                            </tr>
                        	<tr>
                                <td></td>
                        		<td colspan="5"><font color="#000000"><br>Demikian surat paklaring ini dibuat untuk digunakan sebagaimana mestinya. </font><br><br><br><br> 
                                <br><br><br><br> <br><br><br><br> 
                        		</td>
                                <td></td>
                        	</tr>

                    <tr style="height: 18px;">
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:15%;" ></td>
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:15%;" ><font color="#000000"><?= $data_pk[0]->kota_pengajuan ?>, <?= date('d-m-Y',strtotime($data_pk[0]->tgl_Pengajuan)) ?></font></td>
					</tr>
                     <tr style="height: 18px;">
                        
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:15%;" ></td>
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:15%;" ><font color="#000000">Disetujui Oleh,</font></td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttd1 		= $img_path."ttd_notyet2.png";
							
							if($data_pk[0]->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_33_cap.png";
							}else if($data_pk[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							
						?>
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:24%;"></td>
						<td style="text-align:center; width:9%;"></td>
						<td style="text-align:center; width:24%;"></td>
						<td style="text-align:center; width:9%;"></td>
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
                        <td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000">Yolanda Pratiwi<hr></hr></font></td>
					</tr>
					<tr>
                        <td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"></font></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"></font></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i>General Manager</i></font></td>
					</tr>
                    <br><br><br><br> <br><br><br><br> <br><br><br><br> <br><br><br><br> <br><br><br><br>


                 </tbody>
			</table>
			<br>

    </div>
</section>
<!-- PENUTUP TABEL SURAT TUGAS -->
            
            <section role="main" class="content-body content-body-modern" style="padding-top: 0px;">

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
<font color='#000000'>
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
         <!-- <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" /> -->
         <!-- <div id="footer">
            <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
        </div> -->
        <footer>
            <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
        </footer>
        <!-- includes_js.php -->
    </section>
</body>

</html>