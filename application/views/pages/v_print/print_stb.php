<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Surat Serah Terima Barang | <?= $this->config->item('apps_name') ?></title>
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
        <img src="assets/img/kop_baru.jpg" alt="Logo" style="width: 100%;" />
        <hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
<!-- TABEL SURAT TUGAS --> 
<section role="main" class="body" style="padding-top:0px;">
 
    <div class="table-responsive">
    <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:12px" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td colspan="6" style="text-align:center; font-size:15px; font-weight:bold;"><br>
                        	        <font color="#000000"><u>SURAT SERAH TERIMA BARANG</u><br></font> 
                        		</td>
                            </tr>
                            <tr>
                        		<td colspan="6" style="text-align:center; font-size:15px;">
                        	        <font color="#000000"> No : <?= $data_po[0]->kode_stb ?> </font> <br><br>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4" style="text-align:center; font-size:12px; font-weight:bold;"></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i</font></td>
                        	</tr>
                                <font color='#ffffff'>
                                    <?php
                                    $dt_pengajuan	= strtotime($data_po[0]->tgl_Pengajuan);
                                    $tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);

									$hari 	= date("D", $dt_pengajuan);
									$tgl 	= date("d", $dt_pengajuan);
									$bulan 	= date("M", $dt_pengajuan);
									$thn 	= date("Y", $dt_pengajuan);
									switch($hari){
										case 'Sun':
											$hari = "Minggu";
										break;
										case 'Mon':         
											$hari = "Senin";
										break;
										case 'Tue':
											$hari = "Selasa";
										break;
										case 'Wed':
											$hari = "Rabu";
										break;
										case 'Thu':
											$hari = "Kamis";
										break;
										case 'Fri':
											$hari = "Jumat";
										break;
										case 'Sat':
											$hari = "Sabtu";
										break;									
									}

									switch($bulan) {
										case 'Jan':
												$bulan = "Januari";
												break;
										case 'Feb':
												$bulan = "Februari";
												break;
										case 'Mar':
												$bulan = "Maret";
												break;
										case 'Apr':
												$bulan = "April";
												break;
										case 'May':
												$bulan = "Mei";
												break;
										case 'Jun':
												$bulan = "Juni";
												break;
										case 'Jul':
												$bulan = "Juli";
												break;
										case 'Aug':
												$bulan = "Agustus";
												break;
										case 'Sep':
												$bulan = "September";
												break;
										case 'Oct':
												$bulan = "Oktober";
												break;
										case 'Nov':
												$bulan = "November";
												break;
										case 'Dec':
												$bulan = "Desember";
												break;
                                    }
                                ?>
                                </font>	
                                
                        	<tr>
                                <td width="3%"></td>
                        		<td colspan="5"><font color='#000000'><br>Pada hari ini &nbsp; <strong><?= $hari ?></strong>, Tanggal &nbsp; <strong><?= $tgl ?></strong> &nbsp; Bulan &nbsp; <strong><?= $bulan ?></strong> &nbsp; Tahun &nbsp; <strong><?= $thn ?></strong>, kami yang bertanda tangan dibawah ini:</font>
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                                <td width="15%"><font color="#000000">Nama </td>
						        <td><font color="#000000">:&nbsp;&nbsp;<?= $data_po[0]->nama_pihak1 ?></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td><font color="#000000">Alamat </td>
						        <td><font color="#000000">:&nbsp;&nbsp;<?= $data_po[0]->alamat_pihak1 ?></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td><font color="#000000">Disebut sebagai </td>
						        <td style="font-weight:bold;"><font color="#000000">"PIHAK PERTAMA"<b></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="5" style="font-weight:bold;"><font color="white">i 
                                </td>
                                <td></td>
                        	</tr>
                             <tr>
                                <td></td>
                                <td width="15%"><font color="#000000">Nama </td>
						        <td><font color="#000000">:&nbsp;&nbsp;<?= $data_po[0]->nama_customer ?></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td><font color="#000000">Alamat </td>
						        <td><font color="#000000">:&nbsp;&nbsp;<?= $data_po[0]->alamat_customer ?></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td><font color="#000000">Disebut sebagai </td>
						        <td style="font-weight:bold;"><font color="#000000">"PIHAK KEDUA"<b></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="5"><font color="#000000"><br>Dengan ini Pihak Pertama telah menyerahkan kepada Pihak Kedua berupa : 
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="5" style="font-weight:bold;"><font color="white">i 
                                </td>
                                <td></td>
                        	</tr>

                        </tbody>
                        
			    </table>

                <table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:12px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" width="5%"><font color="#000000"> No </th>
                                <th style="text-align:center"><font color="#000000"> Nama Barang </th>
                                <th style="text-align:center" width="15%"><font color="#000000"> Merk </th>
                                <th style="text-align:center" width="15%"><font color="#000000"> No Batch</th>
                                <th style="text-align:center" width="7%"><font color="#000000"> QTY</th>
                                <th style="text-align:center" width="15%"><font color="#000000"> SATUAN </th>
                                <th style="text-align:center" width="15%"><font color="#000000"> KET </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
								$x=1;
								$xy=0;
								foreach ($data_detail as $row) {
									$x = $x+1;
									$xy = $xy+1;

									$id = encrypt($row->id_pod);
							?>
								<tr>
                                   	<td class="nomor" style="text-align:center"><font color="#000000">&nbsp;<?= $row->nomor ?>&nbsp;</td>
                                   	<td class="nama"><font color="#000000">&nbsp;<?= $row->nama_barang ?>&nbsp;</td>
                                   	<td class="merk" style="text-align:center"><font color="#000000">&nbsp;<?= $row->merk ?>&nbsp;</td>
                                   	<td class="nobatch" style="text-align:center"><font color="#000000">&nbsp;<?= $row->no_batch ?>&nbsp;</td>
                                   	<td class="qty" style="text-align:center"><font color="#000000">&nbsp;<?= $row->qty ?>&nbsp;</td>
                                    <td class="satuan" style="text-align:center"><font color="#000000">&nbsp;<?= $row->satuan ?>&nbsp;</td>
                                    <td class="ket" style="text-align:center"><font color="#000000">&nbsp;<?= $row->ket ?>&nbsp;</td>
                                </tr>
                            <?php } ?>							
                        </tbody>
                        
			</table>

            <?php if($data_po[0]->id_stb == 6){ ?>
                <table id="myTable2" style="font-family:Times New Roman; font-color:black; font-size:12px" border="0" width="100%">
				<tbody>
                    
                            <tr>
                                <td></td>
                        		<td colspan="5"><font color="#000000"><br>	
                                </td>
                                <td></td>
                        	</tr>
                        	<tr>
                        		<td></td>
                        		<td colspan="5" style="text-align:right"><br><br><br><br><br><br><br><br><br><br><br></td>
                        	</tr>
                     


                 </tbody>
			</table>
            <?php  }else{  ?>
            <?php  } ?>
            
            <table id="myTable2" style="font-family:Times New Roman; font-color:black; font-size:12px" border="0" width="100%">
				<tbody>
                    
                            <tr>
                                <td></td>
                        		<td colspan="5"><font color="#000000"><br>	Demikian surat serah terima barang ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
                                </td>
                                <td></td>
                        	</tr>
                        	<tr>
                        		<td></td>
                        		<td colspan="5" style="text-align:right"><br><br><br></td>
                        	</tr>
                     


                 </tbody>
			</table>
            
            

            <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:12px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="9"><font color="#000000">
							<?= $data_po[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
						</td>
						<td style="text-align:center; width:10%;"></td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><font color="#000000">Yang Menerima</td>
						<td style="text-align:center; width:35%;"></td>
						<td style="text-align:center; width:20%;"><font color="#000000">Yang Menyerahkan</td>
					</tr>

                        <?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_po[0]->idPengaju.".png";
							
							if ($data_po[0]->id_pihak1 == 325){
						?>

					<tr style="height: 18px;">
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><font color="#000000"><?= $data_po[0]->nama_customer ?></font></td>
						<td style="text-align:center; width:35%;"></td>
						<td style="text-align:center; width:20%;"><font color="#000000"><?= $data_po[0]->nama_pihak1 ?></font></td>
					</tr>
                    <tr style="height:60px;">
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"></td>
						<td style="text-align:center; width:35%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="white">i </font><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_po[0]->pengaju ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $data_po[0]->jabatan ?></i></td>
					</tr>
                    <?php }else if ($data_po[0]->id_customer == 325){ ?>
                        <tr style="height: 18px;">
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><font color="#000000"><?= $data_po[0]->nama_customer ?></font></td>
						<td style="text-align:center; width:35%;"></td>
						<td style="text-align:center; width:20%;"><font color="#000000"><?= $data_po[0]->nama_pihak1 ?></font></td>
					</tr>
                    <tr style="height:60px;">
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
						<td style="text-align:center; width:35%;"></td>
						<td style="text-align:center; width:20%;"></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_po[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="white">i </font><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $data_po[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
					</tr>
                    <?php } ?>
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