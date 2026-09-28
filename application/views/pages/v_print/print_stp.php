<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> SERAH TERIMA PEKERJAAN | <?= $this->config->item('apps_name') ?></title>
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
							<th style="border: none;" width="23%"></th>
							<th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none; font-size:15px"><font color='#000000'> BERITA ACARA SERAH TERIMA PEKERJAAN <hr></hr></th>
              <th style="border: none;" width="23%"></th>
						</tr>
            <tr>
							<th style="border: none;"></th>
							<th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none; font-size:14px"><font color='#000000'>No : <?= $data_serah[0]->kode ?></th>
							<th style="border: none;"></th>
            </tr>
						<tr>
							<th style="border: none;" ></th>
							<th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none;"><font color='#000000'> </th>
							<th style="border: none;"></th>
                        </tr>
					</table>

         <table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
			<font color='#ffffff'>
								<?= 

									$tanggal		= strtotime($data_serah[0]->tanggal);
									$hari 	= date("D", $tanggal);
									$tgl 	= date("d", $tanggal);
									$bln 	= date("M", $tanggal);
									$thn 	= date("Y", $tanggal);
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


									$bulan = [
											"Jan" => "Januari",
											"Feb" => "Februari",
											"Mar" => "Maret",
											"Apr" => "April",
											"May" => "Mei",
											"Jun" => "Juni",
											"Jul" => "Juli",
											"Aug" => "Agustus",
											"Sep" => "September",
											"Oct" => "Oktober",
											"Nov" => "November",
											"Dec" => "Desember"
									];

									$blnInggris = date("M", $tanggal);
									$bln = $bulan[$blnInggris];

									if ($data_serah[0]->idPengaju==58){
										$pengaju = "Amtisari Destiani Eka Putri";
									}else{
										$pengaju = $data_serah[0]->pengaju;
									}

								?>
							</font>
				<tr>
						<td colspan="3" style="text-align:left; color: #000000;">
								<span style="font-weight: bold;">Berita Acara Serah Terima Pekerjaan</span> ini dibuat pada hari ini:
						</td>
				</tr>


				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>								
						<td width="15%"><font color='#000000'>Hari </td>
						<td><font color='#000000'> :&nbsp;&nbsp; <?= $hari ?></td>
					</tr>
					<tr>								
						<td><font color='#000000'>Tanggal </td>
						<td> :&nbsp;&nbsp; <?= $tgl ?> - <?= $bln ?> - <?= $thn ?></td>
					</tr>
					<tr>								
						<td>Bertempat di </td>
						<td> :&nbsp;&nbsp; <?= $data_serah[0]->kota_aju ?></td>
					</tr>
					
				</tr>

				<tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
			
			<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
			
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Oleh dan diantara :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>
						<td width="15%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_serah[0]->pengaju ?> </td>
					</tr>
					<tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->nppaju ?></td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->jabatan ?></td>
					</tr>
				</tr>

					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Selanjutnya disebut sebagai <span style="font-weight: bold;">“Pihak Pertama” </span></font>
						</td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>

				<tr>											
				</font>
					<tr>
						<td width="15%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_serah[0]->penerima ?> </td>
					</tr>
					<tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->nppterima ?></td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_serah[0]->jabatan2 ?></td>
					</tr>
				</tr>

					<tr>
						<td colspan="3" style="text-align:left">
							<font color='#000000'>Selanjutnya disebut sebagai <span style="font-weight: bold;">“Pihak Kedua” </span></font>
						</td>
					</tr>
				<tr>
						<td><font color="white">i </font></td>
				</tr>


			</table>


			<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
				<tr>	
					<td colspan="3" style="text-align: justify;">
						<font color='#000000'>	
							Pihak Pertama dan Pihak Kedua secara bersama-sama selanjutnya disebut sebagai <span style="font-weight: bold;">“Para Pihak”</span>. Para Pihak dengan ini menerangkan dan menyatakan hal-hal sebagai berikut:
						</font>
					</td>
				</tr>
				
								
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
				<tr>		
					<td rowspan="12"></td>
					<tr>
						<td width="5%" style="vertical-align: top;">(1)</td>
						<td style="text-align: justify; color: #000000;">	
							Bahwa, Pihak pertama menyerahkan tugas dan wewenang pekerjaan (daftar terlampir_Lampiran 1) kepada pihak kedua karena pihak pertama resign dari perusahaan dimulai saat serah terima ini dibuat. Pihak kedua selanjutnya berkewajiban menjalankan tugas dan wewenang pekerjaan tersebut.
						</td>
					</tr>
					<tr>
						<td width="5%" style="vertical-align: top;">(2)</td>
						<td style="text-align: justify; color: #000000;">	
							Pihak pertama menyerahkan pekerjaan sedang diproses dan terkendala (daftar terlampir_Lampiran 2) kepada pihak kedua karena pihak pertama resign dari perusahaan dimulai saat serah terima ini dibuat. Pihak kedua selanjutnya berkewajiban meneruskan pekerjaan yang sedang diproses dan terkendala tersebut dan melaporkan ke atasan terkait.
						</td>
					</tr>
					<tr>
						<td width="5%" style="vertical-align: top;">(3)</td>
						<td style="text-align: justify; color: #000000;">	
							Pihak pertama berkewajiban untuk menyerahkan username dan password (daftar terlampir_Lampiran 3) yang digunakan untuk menjalankan tugas dan wewenang kepada pihak kedua.
						</td>
					</tr>
					<tr>
						<td width="5%" style="vertical-align: top;">(4)</td>
						<td style="text-align: justify; color: #db1514;">	
							Bahwa pihak pertama dengan ini menyerahkan tugas dan wewenang pekerjaan serta dokumen kepada pihak kedua karena pihak pertama tidak lagi bertanggung jawab atas pekerjaan terkait dan pihak kedua dengan ini menerima tugas, wewenang, dan dokumen tersebut dari pihak pertama.
						</td>
					</tr>
					<tr>
						<td width="5%" style="vertical-align: top;">(5)</td>
						<td style="text-align: justify; color: #000000;">	
							Bahwa dengan telah dilakukannya serah terima pekerjaan dan dokumen tersebut sebagaimana dimaksud dalam Butir (1 dan 2) di atas, maka kewajiban pihak pertama untuk menyerahkan pekerjaan dan dokumen kepada pihak kedua dan hak pihak kedua untuk menerima pekerjaan dan dokumen tersebut dari pihak pertama telah dilaksanakan.							
						</td>
					</tr>
				</tr>

				
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
				<tr>	
					<td colspan="3" style="text-align: justify;">
						Demikian Berita Acara ini dibuat di tempat dan pada waktu sebagaimana disebutkan pada bagian awal Berita Acara ini.
					</td>
				</tr>
				
				
			</table>



			<br>
								



      <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:12px;">
				<tbody>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:30%;" ><font color='#000000'><span style="font-weight: bold;">Pihak Pertama </span></td>
						<td style="text-align:center; width:40%;" ><font color='#000000'>Para Pihak,</td>
						<td style="text-align:center; width:30%;" ><font color='#000000'><span style="font-weight: bold;">Pihak Kedua</span></td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_serah[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							
							if($data_serah[0]->ttd == '1'){
								$ttd1		= $img_path."ttd_".$data_serah[0]->id_terima.".png";
							}else if($data_serah[0]->ttd == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}

							if($data_serah[0]->ttd_1 == '1'){
								$ttd2		= $img_path."ttd_".$data_serah[0]->id_diketahui.".png";
							}else if($data_serah[0]->ttd_1 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:30%;"> <?php echo'<img src="'.$ttdaju.'" height="60">';?> </td>
						<td style="text-align:center; width:40%;"></td>
						<td style="text-align:center; width:30%;"><?php echo'<img src="'.$ttd1.'" height="60">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color='#000000'><?= $pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'><?= $data_serah[0]->penerima ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $data_serah[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $data_serah[0]->jabatan2 ?></i></td>
					</tr>
				</tbody>
				
			</table>


			<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:12px;">
				<tbody>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:40%;" ><font color='#000000'></td>
						<td style="text-align:center; width:30%;" ><font color='#000000'><span style="font-weight: bold;">Mengetahui </span></td>
						<td style="text-align:center; width:40%;" ><font color='#000000'></td>
					</tr>
					

					<tr style="height:60px;">
						
						<td style="text-align:center; width:40%;">  </td>
						<td style="text-align:center; width:30%;"><?php echo'<img src="'.$ttd2.'" height="60">';?></td>
						<td style="text-align:center; width:40%;"></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'><?= $data_serah[0]->mengetahui ?><hr></hr></td>
						<td style="text-align:center; "></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center;  vertical-align:top;"><font color='#000000'><i><?= $data_serah[0]->jabatan3 ?></i></td>
						<td style="text-align:center; vertical-align:top;"></td>
					</tr>
				</tbody>
				
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
				<tr>	
					<td colspan="3" style="text-align: justify;"><br><br><br>
							<i> Lampiran :</i>
					</td>
				</tr>
				
				<tr>
						<td><font color="white">i </font></td>
				</tr>
				
				
			</table>

		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:12px" border="1" width="100%">
					<thead>
							<tr>
									<th style="text-align:center; height: 35px;" bgcolor="#d3d3d3" width="5%"><font color='#000000'> No </th>
									<th style="text-align:center; height: 35px;" bgcolor="#d3d3d3"><font color='#000000'> Nama Pekerjaan </th>
									<th style="text-align:center; height: 35px;" bgcolor="#d3d3d3" width="30%"><font color='#000000'> Link </th>
									<th style="text-align:center; height: 35px;" bgcolor="#d3d3d3" width="30%"><font color='#000000'> Keterangan </th>
							</tr>
					</thead>
					<tbody>
							<?php
									$x = 1;
									$xy = 0;
									$totalJumlah = 0; // Variabel untuk menghitung total jumlah
									foreach ($detail_serah as $row) {
											$x = $x + 1;
											$xy = $xy + 1;
											$totalJumlah += $row->jumlah; // Tambahkan jumlah ke total

											
							?>
							<tr>
									<td style="text-align:center; height: 35px;">
											<input type="hidden" name="id_sodetail[]" value="<?= encrypt($row->id) ?>"><font color='#000000'><?= $xy ?>
									</td>
									<td style="text-align:center; height: 35px;" class="des"><font color='#000000'>&nbsp;<?= $row->nama ?>&nbsp;</td>
									<td style="text-align:center; height: 35px;" class="qty"><font color='#000000'>&nbsp;	<?= $row->link ?> &nbsp;</td>
									<td style="text-align:center; height: 35px;" class="qty"><font color='#000000'>&nbsp;<?= $row->ket ?>&nbsp;</td>
							</tr>
							<?php } ?>

							<!-- Tambahkan baris kosong jika diperlukan -->
							<?php for ($kosong = $x; $kosong <= 20; $kosong++) { ?>
							<tr>
									<td style="text-align:center; height: 35px;"><font color="white">i </font></td>
									<td style="text-align:center; height: 35px;"></td>
									<td style="text-align:center; height: 35px;"></td>
									<td style="text-align:center; height: 35px;"></td>
							</tr>
							<?php } ?>

							
					</tbody>
			</table>

			<table id="tbl_17" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="black">
			

				
				<tr>
						<td><font color="white">i </font></td>
				</tr>
				<tr>
					<tr>
						<td> <i> Tambahan / Catatan: </i>&nbsp;&nbsp; <?=  $data_serah[0]->keterangan ?> </td>
					</tr>
					
					<tr>
						<td width="10%" height="35px"> </td>
					</tr>
					
												
				</tr>
			</table>

			<br>
								

			
                    
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