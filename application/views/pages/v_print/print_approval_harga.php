<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Pengajuan Approval Harga | <?= $this->config->item('apps_name') ?></title>
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
        div {
          
          padding: 25px;

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
							
							<th colspan="3" style="text-align:center"><font color='#000000'> APPROVAL HARGA <hr></hr></th>
							
						</tr>
						<tr>
							<th colspan="3" style="text-align:center"><font color='#000000'> No : <?= $data_approval[0]->kode ?> </th>
						</tr>
						<tr>
							<td><font color="white">i </font></td>
						</tr>
                    </table>
                    <br>
                    <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
                    	<tr>
                    		<td width="35%"><font color="#000000" style="font-family:Times New Roman">Tanggal</font></td>
                    		<td width="5%"><font color="#000000" style="font-family:Times New Roman">:</font></td>
                    		<td><font color="#000000" style="font-family:Times New Roman"><?= date('d-m-Y', strtotime($data_approval[0]->tgl)); ?></font></td>
                    	</tr>
                    	<tr>
                    		<td width="35%"><font color="#000000" style="font-family:Times New Roman">Nama Marketing</font></td>
                    		<td width="5%"><font color="#000000" style="font-family:Times New Roman">:</font></td>
                    		<td><font color="#000000" style="font-family:Times New Roman"><?= $data_approval[0]->nama ?></font></td>
                    	</tr>
                    	<tr>
                    		<td width="35%"><font color="#000000" style="font-family:Times New Roman">Nama Customer</font></td>
                    		<td width="5%"><font color="#000000" style="font-family:Times New Roman">:</font></td>
                    		<td><font color="#000000" style="font-family:Times New Roman"><?= $data_approval[0]->nama_customer ?></font></td>
                    	</tr>
                    	<tr>
                    		<td width="35%"><font color="#000000" style="font-family:Times New Roman">Detail Order Confirmation</font></td>
                    		<td width="5%"><font color="#000000" style="font-family:Times New Roman">:</font></td>
                    		<td><font color="#000000" style="font-family:Times New Roman"><?= $data_approval[0]->detail_order ?></font></td>
                    	</tr>
                    	<tr>
                    		<td><font color="white">i </font></td>
                    	</tr>
                    	<tr>
                    		<td colspan="3"><font color='#000000'>Pada Order Confirmation diatas terdapat harga product yang akan ditawarkan dibawah harga Pricelist 
                    		setelah diberikan diskon maksimal / Acuan Harga Terendah dengan detail :</font></td>
                    	</tr>
                    	<tr>
							<td><font color="white">i </font></td>
						</tr>
                    </table>
                    <br>
                    <table style="font-family:Times New Roman; font-size:15px" border="1" width="100%">
                    	<thead>
                            <tr>
                            	<th style="text-align:center"><font color="#000000" style="font-family:Times New Roman">Nama Barang/Package </font></th>
                            	<th style="text-align:center" width="25%"><font color="#000000" style="font-family:Times New Roman"> Acuan Harga Terendah </font></th>
                            	<th style="text-align:center" width="25%"><font color="#000000" style="font-family:Times New Roman"> Harga yang akan ditawarkan </font></th>
                            	<th style="text-align:center" width="25%" colspan="4"><font color="#000000" style="font-family:Times New Roman"> Approval </font></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $x=1;
                        $nom = 0;
                        $xtampil = 0;
                        foreach ($detail_approval as $row) {
                        	$x = $x+1;

                            if($row->approvall ==1){
                                $approvall = "Disetujui";
                            }else{
                                $approvall = "Ditolak";
                            }
                        ?> 
                        	<tr>
                        		<td class="nama_barang" >&nbsp;<font color="#000000" style="font-family:Times New Roman"><?= $row->nama_barang ?></font>&nbsp;</td>
                        		<td>
                                        <label class="acuan_hrg" style="text-align:left"><font color="#000000" style="font-family:Times New Roman" width="25%">&nbsp;Rp. </font></label>
                                        <label class="acuan_hrg" style="text-align:right"><font color="#000000" style="font-family:Times New Roman" width="25%"><?= number_format($row->acuan_hrg,0,",",".") ?>,-&nbsp;</font></label>
                                    </td>
                                    <td>
                                        <label class="hrg_ditawarkan" style="text-align:left"><font color="#000000" style="font-family:Times New Roman" width="25%">&nbsp;Rp. </font></label>
                                        <label class="hrg_ditawarkan" style="text-align:right"><font color="#000000" style="font-family:Times New Roman" width="25%"><?= number_format($row->hrg_ditawarkan,0,",",".") ?>,-&nbsp;</font></label>
                                    </td>
                        		<td class="approvall" style="text-align:center">&nbsp;<font color="#000000" style="font-family:Times New Roman" width="25%"><?= $approvall ?>&nbsp;</font></td>
                        		<!-- <td class="ket" >&nbsp;<?= $row->qty ?>&nbsp;</td> -->

                        	</tr>
                        	<?php } ?>
							<?php for($kosong=$x;$kosong<=8;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td width="20%"> </td>
                                   
                                </tr>
							<?php } ?>							
                        </tbody>
                        </table>
                        <br>
                      <table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                    	<thead>
                    		<tr>
                    			<th width="25%"><font color="#000000" style="font-family:Times New Roman">*Tarif Komisi Marketing : </font></th>
                    			<th width="25%"><font color="#000000" style="font-family:Times New Roman"> *Catatan : </font></th>
                    		</tr>
                    	</thead>
                    	<tbody>

								<tr>
                                    <!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
                                     <td class="trf_komisi" >
                                     	<font color="#000000" style="font-family:Times New Roman;white-space: pre-line"><div>
                                    	<?= $row->trf_komisi ?></div>
                                    	</font>
                                	</td>
                                    <td class="catatan" >
                                    	<font color="#000000" style="font-family:Times New Roman;white-space: pre-line"><div>
                                    	<?= $row->catatan ?></div>
                                    	</font>
                                	</td>
								</tr>

                    		 <!-- <?php for($kosong=$x;$kosong<=1;$kosong++){ ?>
                                <tr>
                                    <td> <font color="white">i </font> </td>
                                    <td> </td>
                                </tr>
                            <?php } ?>	 -->						
                    	</tbody>
                    </table>

                    <table>
                    	<tr>
                    		<td><font color="white">i </font></td>
                    	</tr>
                    	<tr>
                    		<td><font color="#000000" style="font-family:Times New Roman"><i>(*Tarif Komisi Marketing dan Catatan diisi oleh Head of Finance and Corporate Planning)</i></font></td>
                    	</tr>
                    	<tr>
                    		<td><font color="white">i </font></td>
                    	</tr>
                    </table>
                     <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
											<tbody>
												<tr style="height: 18px;">
													<td style="text-align:center; "><font color="#000000" style="font-family:Times New Roman">Dibuat Oleh,</td>
													<td style="text-align:center; "><font color="#000000" style="font-family:Times New Roman">Diverifikasi Oleh,</td>
													<td style="text-align:center; "><font color="#000000" style="font-family:Times New Roman">Disetujui Oleh,</td>
												</tr>
												<tr style="height:60px;">
													<?php
														
														$img_path 	= "uploads/file_karyawan/ttd/";
														$ttdaju		= $img_path."ttd_".$data_approval[0]->idPengaju.".png";
														$ttd1 		= $img_path."ttd_notyet2.png";
														$ttd2 		= $img_path."ttd_notyet2.png";
														
														if($data_approval[0]->ttd_1 == '1'){
															$ttd1 = $img_path."ttd_75.png";
														}else if($data_approval[0]->ttd_1 == '2'){
															$ttd1 = $img_path."ttd_not.png";
														}
														if($data_approval[0]->ttd_2 == '1'){
															$ttd2 = $img_path."ttd_107.png";
														}else if($data_approval[0]->ttd_2 == '2'){
															$ttd2 = $img_path."ttd_not.png";
														}
													?>
													<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
													<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
													<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
												</tr>
												<tr>
													<td style="text-align:center; "><font color="#000000" style="font-family:Times New Roman"><strong><?= $data_approval[0]->nama_ttd ?></strong><hr width="40%"></td>
													<td style="text-align:center; "><font color="#000000" style="font-family:Times New Roman"><strong>Mega Ratu</strong><hr width="40%"></td>
													<td style="text-align:center; "><font color="#000000" style="font-family:Times New Roman"><strong>Dirangga Madali</strong><hr width="40%"></td>
												</tr>
												<tr>
													<td style="text-align:center; vertical-align:top;"><font color="#000000" style="font-family:Times New Roman"><i><?= $data_approval[0]->jabatan_visilab ?></i></td>
													<td style="text-align:center; vertical-align:top;"><font color="#000000" style="font-family:Times New Roman"><i>Head of Visilab</i></td>
													<td style="text-align:center; vertical-align:top;"><font color="#000000" style="font-family:Times New Roman"><i>Head of Accounting and Tax</i></td>
												</tr>
											</tbody>
											
										</table>
			
            </section>

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
         <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
        <!-- includes_js.php -->
    </section>
</body>

</html>