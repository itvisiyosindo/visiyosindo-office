<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Laporan Stok Keluar Masuk Alatt | <?= $this->config->item('apps_name') ?></title>
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
<body style="background-color:white; font-color:black;">
    <section class="body" style="padding-top:0px;">
        <img src="assets/img/kop_visilab.png" alt="Logo" style="width: 100%;" />
		<hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
           <section role="main" class="content-body content-body-modern" style="padding-top:13px; padding-bottom:0px;">
                <!-- start: page -->
					
                    <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
                    	<tr>
							<th width="23.4%"></th>
							<th colspan="" style="height:27px; text-align:center; vertical-align:top;"><font color='#000000'> LAPORAN STOK KELUAR - MASUK ALAT <hr></hr><hr></hr></th>
							<th width="23.4%"></th>
						</tr>
                        
						<tr>
							<td><font color="white">i </font></td>
						</tr>
                    </table>

                    <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
						<tr>
                            <?php $bulan =  explode("-",$month) ?>
							<tr>
								<td width="10%"><font color='#000000'> Bulan  </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= all_bulan()[(int)$bulan[1]] ?>  </td>
							</tr>
							<tr>
								<td><font color='#000000'> Tahun </td>
								<td><font color='#000000'> :&nbsp;&nbsp;  <?= $bulan[0] ?></td>
							</tr>
							<tr>
								<td><font color="white">i </font></td>
							</tr>
						</tr>
                    </table>
            </section>

            <section role="main" class="body" style="padding-top:0px;">
                <div class="table-responsive">
                    <table id="kt_table_1" style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" width="5%"><font color='#000000'> NO.</th>
                                <th style="text-align:center"><font color='#000000'> NAMA ALAT</th>
                                <th style="text-align:center" width="15%"><font color='#000000'> NO. SERI </th>
                                <th style="text-align:center" width="15%"><font color='#000000'> NAMA PENGGUNA </th>
                                <th style="text-align:center" width="12%"><font color='#000000'> WAKTU KELUAR </th>
                                <th style="text-align:center" width="12%"><font color='#000000'> WAKTU MASUK </th>
                                <th style="text-align:center" width="17%"><font color='#000000'> KETERANGAN </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
								$x=1;
								$nom = 0;
								$xtampil = 0;
								foreach ($detail_stok as $row) {

                                    if($row->waktu_masuk != ''){
                                        $masuk = date('d-m-Y', strtotime($row->waktu_masuk)); 
                                    }else{
                                        $masuk = "";
                                    }
							?>
								<tr>
                                    <td style="text-align:center"><font color='#000000'><?= $x++ ?></td>
                                    <td class="hari" >&nbsp;<font color='#000000'><?= $row->nama ?>&nbsp;</td>
                                    <td class="pic" >&nbsp;<font color='#000000'><?= $row->serial_number ?>&nbsp;</td>
                                    <td class="pic" >&nbsp;<font color='#000000'><?= $row->pengaju ?>&nbsp;</td>
                                    <td class="tgl" >&nbsp;<font color='#000000'><?= date('d-m-Y', strtotime($row->waktu_keluar)); ?>&nbsp;</td>
                                    <td class="nama_customer" >&nbsp;<font color='#000000'><?= $masuk ?>&nbsp;</td>
                                    <td class="tujuan_dinas" >&nbsp;<font color='#000000'><?= $row->ket ?>&nbsp;</td>
                                </tr>
                            <?php } ?>
							<?php for($kosong=$x;$kosong<=10;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
							<?php } ?>							
                        </tbody>
                    </table>
                </div>
			</section>
			<br><br>

			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                
				
			<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							<font color='#000000'>Pekanbaru, <?= indo_dates(date('Y-m-d')) ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2"></td>
						<td style="text-align:center; width:49%;" colspan="3"> </td>
						<th style="text-align:center; width:25.5%;" colspan="2"><font color='#000000'>Diketahui Oleh,</th>
					</tr>
                    <?php
						$img_path 	= "uploads/file_karyawan/ttd/";
							
						$bulan12 = explode("-", $month); 
                        $tahun = (int)$bulan12[0];
                        $bln   = (int)$bulan12[1];

                        if ($tahun > 2025 || ($tahun == 2025 && $bln >= 9)) {
                            $nama = 'Mohammad Fisal Rabin';
                            $jabatan = 'Staff Teknis';
                            $ttd	= $img_path."ttd_757.png";
                        } else {
                            $nama = 'Kardonal, S.Pt';
                            $jabatan = 'Manager Teknis';
                            $ttd	= $img_path."ttd_15.png";
                        }
                    ?>
					<tr style="height:60px;">
						<td style="text-align:center; width:23%; "></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%; "></td>
						<td style="text-align:center; width:2%;  "></td>
						<td style="text-align:center; width:24%; "></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%; "><?php echo'<img src="'.$ttd.'" height="70">';?></td>
					</tr>

					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'><?= $nama ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $jabatan ?></i></td>
					</tr>
					</tbody>
				</table>

                
                <br><br><br>
                <script>
                    window.onload = function() {
                        window.print();
                    }
                </script>
                <!-- end: page -->
            </section>

        </div>
        <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
        <!-- includes_js.php -->
    </section>
</body>

</html>