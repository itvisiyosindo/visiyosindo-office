<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Rencana Forecast | <?= $this->config->item('apps_name') ?></title>
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
        <img src="assets/img/kop_baru.jpg" alt="Logo" style="width: 100%;" />
        <hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
<!-- TABEL SURAT TUGAS --> 
<section role="main" class="body" style="padding-top:0px;">
 
    <div class="table-responsive">
    <table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
                    <tr>
                    <td colspan="6" style="text-align:center; font-size:15px; font-weight:bold;"><br>
                    <u>RENCANA FORECAST</u></td>
                    </tr>
                    <tr>
                    <td colspan="6" style="text-align:center; font-size:15px;">
                     No : <?= $forecast->kode ?> <br><br>
                    </td>
                </tr>
						<tr>
								<td><font color="white">i </font></td>
						</tr>
						<tr>
								<td><font color="white">i </font></td>
						</tr>
				<tr>
                    <td width="5%"></td>
					<td colspan="3" style="text-align:left">
						Dengan ini saya mengajukan permintaan Forecast :<br><br>
					</td>
				</tr>
				<tr>
					<tr>
                        <td></td>
                        <td style="width:70px;">Nama</td>
                        <td style="width:10px;">:</td>
                        <td><?= $forecast->nama ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>NPP</td>
                        <td>:</td>
                        <td><?= $forecast->no_pegawai ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td><?= $forecast->jabatan ?></td>
                    </tr>
                    
				</tr>
				
			</table>
			<br>

            <span style="font-family:'Times New Roman', serif; font-size:12px; color:black;">
                Detail Barang Kategori <?= $forecast->nama_kategori ?> :
            </span>
            <table border="1" id="kt_table_1" style="font-family:Times New Roman; font-size:12px" width="100%">
                    <tr>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>No</th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Nama Barang</th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Satuan</th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Terjual <?= $forecast->tahun_a  ?></th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Terjual <?= $forecast->tahun_b  ?></th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Total Stok</th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Demo</th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Barang Customer</th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Permintaan PO</th>
                        <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'>Keterangan</th>
                    </tr>
                    <?php 
                    $no = 1;
                    $total_permintaan = 0;
                        foreach ($data_forecast as $row) { 
                            if ($forecast->status != 0) {
                                $total_permintaan += (int) $row->permintaan;
                            } else {
                                $total_permintaan += (int) $row->permintaan;
                            }
                            
                            ?>
                            
                            
                            <tr>
                                <td style="text-align:center"><font color='#000000'><?= $no++ ?></td>
                                <td class="text-left"><font color='#000000'><?= $row->nama_barang ?></td>
                                <td class="satuan" style="text-align:center"><font color='#000000'><?= $row->nama_satuan ?></td>
                                <td class="terjual_lalu" style="text-align:center"><font color='#000000'><?= $row->terjual_tahun_lalu ?></td>
                                <td class="terjual_ini" style="text-align:center"><font color='#000000'><?= $row->terjual_tahun_ini ?></td>
                                <td class="stok" style="text-align:center"><font color='#000000'><?= $row->total_stok ?></td>
                                <td class="demo" style="text-align:center"><font color='#000000'><?= $row->demo_stok ?></td>
                                <td class="customer" style="text-align:center"><font color='#000000'><?= $row->barang_customer_stok ?></td>                                
                                <td style="text-align:center"><font color='#000000'><?= $row->permintaan ?></td>
                                <td class="text-left"><font color='#000000'><?= $row->keterangan ?></td>
                            </tr>
                    <?php } ?>
                    <!-- baris total -->
                    <tr style="font-weight:bold; background:#f0f0f0;">
                        <td colspan="8" style="font-weight:bold; text-align:right;"><font color='#000000'><strong>Total : </strong></font></td>
                        <td style="text-align:center; font-weight:bold;"><font color='#000000'><strong> <?= $total_permintaan ?> </strong></font></td>
                        <td></td>
                    </tr>
                    <?php /*for($kosong=$no;$kosong<=17;$kosong++){ ?>
							<tr>
                                <td> <font color="white">i </font> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                            </tr>
					<?php } */ ?>	
                    
            </table>
			<br><br>
		
			

			<table border="0" style="width:100%; font-family:'Times New Roman', serif; color:black; font-size:12px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							Pekanbaru, <?= $forecast->created_at ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
						<td style="text-align:center; width:49%;" colspan="3">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:25.5%;" colspan="2">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$forecast->id_pengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
							
							if($forecast->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_756.png";
							}else if($forecast->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							if($forecast->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_23.png";
							}else if($forecast->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
							if($forecast->ttd_3 == '1'){
								$ttd3 = $img_path."ttd_54.png";
							}else if($forecast->ttd_3 == '2'){
								$ttd3 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
					</tr>
                    <tr>
                        <td style="text-align:center; padding:0;">
                            <?= $forecast->nama ?>
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                        <td></td>
                        <td style="text-align:center; padding:0;">
                            Siti Safrida
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                        <td></td>
                        <td style="text-align:center; padding:0;">
                            Meilina Safitri
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                        <td></td>
                        <td style="text-align:center; padding:0;">
                            Bob Ariyos
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i><?= $forecast->jabatan ?></i>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i>Regulatory & Import</i>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i>Director of Corporate Planning & Business Management</i>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i>Director</i>
                        </td>
                    </tr>
				</tbody>
			</table>
			<br><br>


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