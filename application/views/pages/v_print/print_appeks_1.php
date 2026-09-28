<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> FORM APPROVAL PENGIRIMAN BARANG / DOKUMEN | <?= $this->config->item('apps_name') ?></title>
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
							<th style="border: none;" width="18%"></th>
							<th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none; font-size:15px"><font color='#000000'> FORM APPROVAL PENGIRIMAN BARANG / DOKUMEN <hr></hr></th>
                            <th style="border: none;" width="18%"></th>
						</tr>
                        <tr>
							<th style="border: none;" width="18%"></th>
							<th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none;"><font color='#000000'>No : <?= $data_aprv[0]->kode ?></th>
							<th style="border: none;" width="18%"></th>
                        </tr>
						<tr>
							<th style="border: none;" width="18%"></th>
							<th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none;"><font color='#000000'> </th>
							<th style="border: none;" width="18%"></th>
                        </tr>
					</table>

                    
                    


					
					<table border="1" style="font-family:Times New Roman; font-size:12px" width="100%">
						
                        <tr>
                            <th style="border: none; font-weight: normal;" width="17%"><font color='#000000'>Tanggal</th>
                            <th style="border: none; font-weight: normal;" width="2%"><font color='#000000'>:</th>
							<td style="text-align:left"> <font color='#000000'>&nbsp; <?= date('d/m/Y',strtotime($data_aprv[0]->tanggal))?> </td>
						</tr>
						<tr>
                            <th style="border: none; font-weight: normal;" width="17%"><font color='#000000'>Nama Customer</th>
                            <th style="border: none; font-weight: normal;" width="2%"><font color='#000000'>:</th>
							<td style="text-align:left"> <font color='#000000'>&nbsp; <?= $data_aprv[0]->nama_customer ?> </td>
						</tr>
						<tr>
                            <th style="border: none; font-weight: normal;" width="17%"><font color='#000000'>Tujuan Pengiriman</th>
                            <th style="border: none; font-weight: normal;" width="2%"><font color='#000000'>:</th>
							<td style="text-align:left"> <font color='#000000'>&nbsp; <?= $data_aprv[0]->tujuan ?> </td>
						</tr>
					</table>



					<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="red">
                        
                        <tr>
                            <td><font color="white">i </font></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:left">
                                <font color='#000000'>Berikut pengajuan pengiriman barang &nbsp;&nbsp; <?=  $data_aprv[0]->nama_barang ?> &nbsp;&nbsp; dengan Surat Jalan No &nbsp;&nbsp; <?=  $data_aprv[0]->no_sj ?>, <br>dengan detail perbandingan harga ongkos kirim sebagai berikut :</font>
                            </td>
                        </tr>
                        <tr>
                            <td><font color="white">i </font></td>
                        </tr>
                    </table>
            </section>
			
			<section role="main" class="content-body content-body-modern" style="padding-top:5px; padding-bottom:0px;">
                <div class="table-responsive">
                    <table border="1" id="kt_table_1" style="font-family:Times New Roman; font-size:12px" width="100%">
                    
                        
                            <tr>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="5%"><font color='#000000'> No </th>
                                <th style="text-align:center" bgcolor="#d3d3d3"><font color='#000000'> Nama Ekspedisi </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="15%"><font color='#000000'> Berat Barang </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="20%"><font color='#000000'> Fasilitas Pengiriman </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="20%"><font color='#000000'> Harga yang akan ditawarkan </th>
                                <th style="text-align:center" bgcolor="#d3d3d3" width="12%"><font color='#000000'> Approval </th>
                            </tr>
                        
                        
							
							<?php 
								$x=1;
								$nom = 0;
								foreach ($detail_aprv as $row) {
								$x = $x+1;
                                $xy = $xy+1;

                                    if ($row->approval==1){
                                        $apprvl = "Disetujui";
                                    }else if ($row->approval==2){
                                        $apprvl = "Ditolak";
                                    }
							?>
								<tr>
                                    <td style="text-align:center"><font color='#000000'> <?= $xy ?></td>
									<td class="des" ><font color='#000000'>&nbsp;<?= $row->namaekspedisi ?>&nbsp;</td>
									<td style="text-align:center" class="berat" ><font color='#000000'>&nbsp;<?= $row->berat ?>&nbsp;</td>
									<td style="text-align:center" class="fas" ><font color='#000000'>&nbsp;<?= $row->fasilitas ?>&nbsp;</td>
                                    <?php if($row->harga != '') { ?>
                                    <td class="nom" style="text-align:right"> <font color='#000000'>
										<label class="mylabel2" style="text-align:left">&nbsp;Rp. </label>
										<label class="mylabel" style="text-align:right"><?= number_format($row->harga,0,",",".") ?>,-&nbsp;</label>
									</td>
                                    <?php } else { ?>
                                    <td class="nom" style="text-align:center"> <font color='#000000'></td>
                                    <?php }  ?>
									<td style="text-align:center" class="app"><font color='#000000'>&nbsp;<?= $apprvl ?>&nbsp;</td>
                                </tr>
                            <?php } ?>
							<?php for($kosong=$x;$kosong<=7;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
							<?php } ?>							
                        
                    </table>
                    
                </div>
                
			</section>
			
			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                    


                <table id="tbl_17" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="red">
			

				<tr>
					<tr>
						<td><br><font color='#000000'> <i>(Terlampir Dokumen pendukung misal : Surat Jalan, bukti penawaran harga, PO barang, Lampiran menyesuakan dengan kondisi pengajuan) </i> </td>
					</tr>
					<tr>
						<td width="10%" height="35px"> </td>
					</tr>
					
												
				</tr>
			</table>


      <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:12px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
							<font color='#000000'><?=  $data_aprv[0]->kota_aju ?>
							<span> ,&nbsp; </span>
							<?= date('d-m-Y',strtotime($data_aprv[0]->created_at)) ?>
							
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" ><font color='#000000'>Diajukan Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" ><font color='#000000'>Diketahui Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" ><font color='#000000'>Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_aprv[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							
							if($data_aprv[0]->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_749.png";
							}else if($data_aprv[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							if($data_aprv[0]->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_33.png";
							}else if($data_aprv[0]->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:25%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color='#000000'><?= $data_aprv[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'>Sehan Ohisabaref<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'>Yolanda Pratiwi<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $data_aprv[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i>Head of Warehouse</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i>General Manager</i></td>
					</tr>
				</tbody>
				
			</table>
            <br><br><br><br><br><br><br><br><br>
                

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