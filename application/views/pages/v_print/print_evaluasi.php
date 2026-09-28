<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Evaluasi Pegawai | <?= $this->config->item('apps_name') ?></title>
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
        <img src="assets/img/kop_newpanjang.jpg" alt="Logo" style="width: 100%;" />
        
        <div class="inner-wrapper" style="padding-top: 0px">
<!-- TABEL SURAT TUGAS --> 
<section role="main" class="body" style="padding-top:0px;">
            
    <div class="table-responsive">
    <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:12px" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td colspan="6" style="text-align:center; font-size:15px; font-weight:bold;"><br>
                        	        <font color="#000000"><u>EVALUASI PEGAWAI </u></font> 
                        		</td>
                            </tr>
                            <tr>
                                <?php
                                if($data_job->jenis_evaluasi==1){
                                 ?>
                                 <td colspan="6" style="text-align:center; font-size:15px;">
                        	        <font color="#000000"> Jenis Evaluasi : Semester </font> <br><br>
                        		</td>
                                <td colspan="6" style="text-align:center; font-size:15px;">
                        	        <font color="#000000"> Semester : <?= $data_job->smt ?> </font> <br><br>
                        		</td>
                                <td colspan="6" style="text-align:center; font-size:15px;">
                        	        <font color="#000000"> Tahun : <?= $data_job->tahun ?> </font> <br><br>
                        		</td>

                                 <?php   
                                
                                }else{
                                ?>
                                
                                <td colspan="6" style="text-align:center; font-size:15px;">
                        	        <font color="#000000"> Jenis Evaluasi : Kontrak </font> <br><br>
                        		</td>

                                <?php
                                }
                                ?>
                        	</tr>
                        	<tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i</font></td>
                        	</tr>
                        	
                 </tbody>
			</table>
            <table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="red">
                        
                       

                         <tr>
                            <td><font color="white">i </font></td>
                        </tr>
                         <tr>
                            <th style="border: none; font-weight: normal;" width="17%"><font color='#000000'>Nama</th>
                            <th style="border: none; font-weight: normal;" width="2%"><font color='#000000'>:</th>
							<td style="text-align:left"> <font color='#000000'>&nbsp; <?= $data_job->pegawai ?> </td>
						</tr>
						<tr>
                            <th style="border: none; font-weight: normal;" width="17%"><font color='#000000'>NPP</th>
                            <th style="border: none; font-weight: normal;" width="2%"><font color='#000000'>:</th>
							<td style="text-align:left"> <font color='#000000'>&nbsp; <?= $data_job->no_pegawai ?> </td>
						</tr>
						<tr>
                            <th style="border: none; font-weight: normal;" width="17%"><font color='#000000'>Jabatan</th>
                            <th style="border: none; font-weight: normal;" width="2%"><font color='#000000'>:</th>
							<td style="text-align:left"> <font color='#000000'>&nbsp; <?= $data_job->jabatan ?> </td>
						</tr>
                        <tr>
                            <td><font color="white">i </font></td>
                        </tr>
                    </table>


            <?php 
			

					
						// Inisialisasi nilai
						$rataA = $rataB = $rataC = $rataD = $rataE = $rataF = 0;
						$countA = $countB = $countC = $countD = $countE = $countF = 0;

						foreach ($data_detail2 as $row) {
							if ($row->nilai == 1) {
								$id = encrypt($row->id);

								// Cek dan jumlahkan hanya jika tidak null
								if (!is_null($row->nilaia)) {
									$rataA += $row->nilaia;
									$countA++;
								}
								if (!is_null($row->nilaib)) {
									$rataB += $row->nilaib;
									$countB++;
								}
								if (!is_null($row->nilaic)) {
									$rataC += $row->nilaic;
									$countC++;
								}
								if (!is_null($row->nilaid)) {
									$rataD += $row->nilaid;
									$countD++;
								}
								if (!is_null($row->nilaie)) {
									$rataE += $row->nilaie;
									$countE++;
								}
								if (!is_null($row->nilaif)) {
									$rataF += $row->nilaif;
									$countF++;
								}
							}
						}

						// Hitung rata-rata hanya jika ada datanya
						$rataA = $countA > 0 ? $rataA / (6*$countA)*100 : 0;
						$rataB = $countB > 0 ? $rataB / (6*$countB)*100 : 0;
						$rataC = $countC > 0 ? $rataC / (6*$countC)*100 : 0;
						$rataD = $countD > 0 ? $rataD / (6*$countD)*100 : 0;
						$rataE = $countE > 0 ? $rataE / (6*$countE)*100 : 0;
						$rataF = $countF > 0 ? $rataF / (6*$countF)*100 : 0;

						// Hitung rata-rata keseluruhan hanya dari nilai-nilai yang punya data
						//$sumRata = 0;
						//$countAll = 0;

					
						//$rataAll = ($rataA+$rataB+$rataC+$rataD+$rataE+$rataF)/6;
						//$countAll > 0 ? $sumRata / $countAll : 0;

						$sumRata = 0;
						$countAll = 0;

						if ($rataA > 0) { $sumRata += $rataA; $countAll++; }
						if ($rataB > 0) { $sumRata += $rataB; $countAll++; }
						if ($rataC > 0) { $sumRata += $rataC; $countAll++; }
						if ($rataD > 0) { $sumRata += $rataD; $countAll++; }
						if ($rataE > 0) { $sumRata += $rataE; $countAll++; }
						if ($rataF > 0) { $sumRata += $rataF; $countAll++; }

						$rataAll = ($countAll > 0) ? $sumRata / $countAll : 0;


			?> 

    <table id="kt_table_2" 
        style="font-family: Times New Roman; font-size: 15px; border-collapse: collapse; border: 1px solid black;">
        
        <thead>
            <tr>
                <th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> No </font></th>
                <th style="text-align:center; min-width: 100px; width: 20%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Deskripsi </font></th>
                <th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Nilai A </font></th>
                <th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Masukan A </font></th>
                <th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Nilai B </font></th>
                <th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Masukan B </font></th>
                <th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Nilai C </font></th>
                <th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Masukan C </font></th>
                <th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Nilai D </font></th>
                <th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Masukan D </font></th>
                <th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Nilai E </font></th>
                <th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Masukan E </font></th>
                <th style="text-align:center; min-width: 50px; width: 5%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Nilai F </font></th>
                <th style="text-align:center; min-width: 150px; width: 15%; border: 1px solid black;" bgcolor="#C6DEFF"><font color='#000000'> Masukan F </font></th>
            </tr>
        </thead>

        <tbody>
							<?php 
								$x=1;
								$xy=0;
								foreach ($data_detail2 as $row) {
									$x = $x+1;
									$xy = $xy+1;

									$id = encrypt($row->id);

									$nilai = $row->nilai;

							?>
																<tr>

																
																	
																	<?php if ($row->nilai == 1): ?>

																		<tr>
																				<td style="text-align:center; border: 1px solid black;">
																						<font color='#000000'><?= $xy ?></font>
																				</td>
																				<td class="nama" style="border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->deskripsi ?></strong></font>
																				</td>
																				<td style="text-align:center; border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->nilaia ?></strong></font>
																				</td>
																				<td class="nama" style="border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->masukana ?></strong></font>
																				</td>
																				<td style="text-align:center; border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->nilaib ?></strong></font>
																				</td>
																				<td class="nama" style="border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->masukanb ?></strong></font>
																				</td>
																				<td style="text-align:center; border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->nilaic ?></strong></font>
																				</td>
																				<td class="nama" style="border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->masukanc ?></strong></font>
																				</td>
																				<td style="text-align:center; border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->nilaid ?></strong></font>
																				</td>
																				<td class="nama" style="border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->masukand ?></strong></font>
																				</td>
																				<td style="text-align:center; border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->nilaie ?></strong></font>
																				</td>
																				<td class="nama" style="border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->masukane ?></strong></font>
																				</td>
																				<td style="text-align:center; border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->nilaif ?></strong></font>
																				</td>
																				<td class="nama" style="border: 1px solid black;">
																						<font color='#000000'><strong><?= $row->masukanf ?></strong></font>
																				</td>
																		</tr>



																	<?php else: ?>

																		<td style="text-align:center; border: 1px solid black;"><font color='#000000'> <?= $xy ?> </td>
																		<td style="border: 1px solid black;">
																				<font color='#000000'> <?= $row->deskripsi ?>
																		</td>
																		
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		<td style="border: 1px solid black;"></td>
																		
																		
																	<?php endif; ?>

                                   	
                                </tr>
                            <?php } ?>
													

														<tr>
																<td colspan="2" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata :</strong></td>
																<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($rataA, 2) ?></strong></td>
																<td colspan="1" style="text-align:center"></td>
																<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($rataB, 2) ?></strong></td>
																<td colspan="1" style="text-align:center"></td>
																<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($rataC, 2) ?></strong></td>
																<td colspan="1" style="text-align:center"></td>
																<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($rataD, 2) ?></strong></td>
																<td colspan="1" style="text-align:center"></td>
																<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($rataE, 2) ?></strong></td>
																<td colspan="1" style="text-align:center"></td>
																<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($rataF, 2) ?></strong></td>
																<td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($rataAll, 2) ?></strong></td>
														</tr>
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
            <img src="assets/img/kop_surat_bawahpanjang.jpg" alt="Logo" style="width: 100%;" />
        </footer>
        <!-- includes_js.php -->
    </section>
</body>

</html>