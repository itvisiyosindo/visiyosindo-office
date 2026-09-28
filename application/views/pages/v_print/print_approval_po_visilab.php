<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Approval PO Visilab | <?= $this->config->item('apps_name') ?></title>
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
        <img src="assets/img/kop_visilab.png" alt="Logo" style="width: 100%;" />
        <hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
<!-- TABEL SURAT TUGAS --> 
<section role="main" class="body" style="padding-top:0px;">
 
    <div class="table-responsive">
    <table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
                    <tr>
                    <td colspan="6" style="text-align:center; font-size:15px; font-weight:bold;"><br>
                    <u>FORM PERSETUJUAN PURCHASE ORDER (PO)</u></td>
                    </tr>
                    <tr>
                    <td colspan="6" style="text-align:center; font-size:15px;">
                     No : <?= $data_po[0]->kode ?> <br><br>
                    </td>
                </tr>
				<tr>
                    <td width="5%"></td>
					<td colspan="3" style="text-align:left"><br><br>
						Kepada Yth:<br>
						<b>Direktur PT. Visi Yosindo Medikal</b><br><br><br>
						
						Saya yang bertanda tangan di bawah ini : <br><br>
					</td>
				</tr>
				<tr>
					<tr>
                        <td></td>
						<td><br>Nama </td>
						<td><br>:</td>
						<td><br>Meilina Safitri, S.E   </td>
					</tr>
					<tr>
                        <td></td>
						<td>Jabatan</td>
						<td>:</td>
						<td>Director of Corporate Planning & Business Management  </td>
					</tr>

                    <tr>
                        <td></td>
						<td><br><br></td>
					</tr>
                    <tr>
                        <td></td>
						<td><br>Nama </td>
						<td><br>:</td>
						<td><br>Mega Ratu   </td>
					</tr>
					<tr>
                        <td></td>
						<td>Jabatan</td>
						<td>:</td>
						<td>Manager Puncak  </td>
					</tr>

                    
				<?php if($data_po[0]->jenis != 1){ ?>
                    
                    <tr>
                        <td></td>
						<td><br><br></td>
					</tr>
                    <tr>
                        <td></td>
						<td><br>Nama </td>
						<td><br>:</td>
						<td><br>Dian Melati Amelia   </td>
					</tr>
					<tr>
                        <td></td>
						<td>Jabatan</td>
						<td>:</td>
						<td>HR & Legal  </td>
					</tr>
                <?php } ?>
                    <tr>
                        <td></td>
						<td><br><br>Untuk Purchase Order (PO) berikut :</td>
					</tr>
                    
					<tr>
                        <td></td>
						<td><br>Nama Instansi/Perusahaan</td>
						<td><br>:</td>
						<td><br><?= $data_po[0]->identitas_pelanggan ?></td>
					</tr>
                    <tr>
                        <td></td>
						<td>Nomor PO</td>
						<td>:</td>
						<td><?= $data_po[0]->no_po ?></td>
					</tr>
                    <tr>
                        <td></td>
						<td>Item PO</td>
						<td>:</td>
						<td><?= $data_po[0]->item1 ?> </td>
					</tr>
                    <tr>
                        <td></td>
						<td><font color="white">i</td>
						<td><font color="white">i</td>
						<td><?= $data_po[0]->item2 ?> </td>
					</tr>
                    <tr>
                        <td></td>
						<td><font color="white">i</td>
						<td><font color="white">i</td>
						<td><?= $data_po[0]->item3 ?> </td>
					</tr>
                    <tr>
                        <td></td>
						<td><font color="white">i</td>
						<td><font color="white">i</td>
						<td><?= $data_po[0]->item4 ?> </td>
					</tr>
                    
                    <tr>
                        <td></td>
						<td><font color="white">i</td>
						<td><font color="white">i</td>
						<td><?= $data_po[0]->item5 ?> </td>
					</tr>
                   
                    <tr>
                        <td></td>
						<td><br>Tanggal PO</td>
						<td><br>:</td>
						<td><br><?= date('d/m/Y',strtotime($data_po[0]->tanggal)) ?></td>
					</tr>
                    <tr>
                        <td></td>
						<td>Alasan PO tidak diproses</td>
						<td>:</td>
						<td><?= $data_po[0]->alasan ?></td>
					</tr>

                    
                    <tr>
                        <td></td>
						<td><br>Catatan Director</td>
						<td><br>:</td>
						<td><br><?= $data_po[0]->catatan ?></td>
					</tr>

                    <tr>
                        <td></td>
						<td><br>Catatan Manager Puncak</td>
						<td><br>:</td>
						<td><br><?= $data_po[0]->catatan_gm ?></td>
					</tr>

                <?php if($data_po[0]->jenis != 1){ ?>

                    <tr>
                        <td></td>
						<td><br>Catatan HR</td>
						<td><br>:</td>
						<td><br><?= $data_po[0]->catatan_hr ?></td>
					</tr>

                <?php } ?>
                    
				</tr>
				
			</table>
		
			
			<br>

			

			<table id="tbl_7" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
					
				<tr>
                    
                    <td width="5%"></td>
                    <td colspan="4"><br>Dengan ini menyetujui Purchase Order (PO) berikut untuk dapat diproses dan dikirimkan barangnya ke customer.</td>
				</tr>

			</table>


            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-size:12px;" color="black">
				<tbody>

					<tr style="height: 25px;">
						<td colspan="5" style="text-align:right"><br><font color="#000000"><br>
							Pekanbaru
							<span> ,&nbsp; </span>
							<?= date('d-m-Y',strtotime($data_po[0]->created_at)) ?>
							
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:27%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:20%;" >Disetujui Oleh,</td>
					</tr>

                    <?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_po[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
							
							if($data_po[0]->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_23.png";
							}else if($data_po[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}

                            if($data_po[0]->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_69.png";
							}else if($data_po[0]->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}

                            if($data_po[0]->ttd_3 == '1'){
								$ttd3 = $img_path."ttd_75.png";
							}else if($data_po[0]->ttd_3 == '2'){
								$ttd3 = $img_path."ttd_not.png";
							}
						?>
					
                <?php if($data_po[0]->jenis != 1){ ?>

					<tr style="height:60px;">						
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:10%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; ">Mega Ratu<hr><hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dian Melati Amelia<hr><hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri, S.E<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i>Manager Puncak</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>HR & Legal</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
					</tr>

                <?php }else{ ?>

					<tr style="height:60px;">						
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; ">Mega Ratu<hr><hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri, S.E<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i>Manager Puncak</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
					</tr>

                <?php } ?>

				</tbody>
				
			</table>

             <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-size:12px;" color="black">
				<tbody>

					
                    <tr>
                        <td width="5%"></td>
                        <td colspan="5" style="text-align:left;" ><br><br><font color="#000000">
                        
                        <i>Tembusan :</i><br>
                        <i>1. Presiden Director</i><br>
                        <i>2. Director</i><br>
                            </font>
                         </td>
                    </tr>

				</tbody>
				
			</table>

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