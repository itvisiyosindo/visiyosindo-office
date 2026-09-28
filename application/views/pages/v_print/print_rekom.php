<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Surat Rekomendasi | <?= $this->config->item('apps_name') ?></title>
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
<body style="background-color:white; font-color:black;">
    <section class="body" style="padding-top:0px;">
        <img src="assets/img/kop_new.jpg" alt="Logo" style="width: 100%;" />
        <hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
<!-- TABEL SURAT TUGAS --> 
<section role="main" class="body" style="padding-top:0px;">
    <br>
    <div class="table-responsive">
        <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
            <tr>
                <th width="35%"></th>
                <th colspan="" style="height:27px; text-align:center; vertical-align:top;"><font color='#000000'> SURAT REKOMENDASI<hr></hr><hr></th>
                <th width="35%"></th>
            </tr>
            <tr>
                <td><font color="white">i </font></td>
            </tr>
        </table>
         <table id="kt_table_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
            <tbody>
                <tr>
                    <td colspan="4"><font color='#000000'> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $data_rekom[0]->kode ?> </td>
                </tr>
                <tr>
                    <td colspan="4"><font color='#000000'> Perihal &nbsp;&nbsp;: <?= $data_rekom[0]->perihal ?>  </td>
                </tr>
                <tr>
                    <td colspan="4"><font color="white">i </font></td>
                </tr>
                <tr>
                    <td colspan="4"><font color='#000000'>
                        Kepada Yth.<br>
                        Direktur PT. Visi Yosindo Medikal<br>
                        Bob Ariyos
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><font color="white">i </font></td>
                </tr>
                <tr>
                    <td colspan="4"><font color='#000000'>
                        Di -
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><font color='#000000'>Tempat</td>
                </tr>
                <tr>
                    <td colspan="4"><font color="white">i </font></td>
                </tr>
                <tr>
                    <td colspan="4"><font color='#000000'>
                        Dengan Ini &nbsp;<?= $data_rekom[0]->keterangan_1 ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><font color='#000000'>
                        bahwasanya karyawan atas nama sebagai berikut   :
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4"><font color="white">i </font></td>
                </tr>
                
                <?php
														$kar1 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000"> Nama </td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama1. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp1. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan11. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar2 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama2. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp2. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan2. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar3 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama3. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp3. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan3. '</td>
															<td></td>
														</tr>							
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar4 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama4. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp4. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan4. '</td>
															<td></td>
														</tr>										
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>'; 

														$kar5 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama5. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp5. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan5. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar6 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama6. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp6. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan6. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar7 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama7. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp7. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan7. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar8 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama8. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp8. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan8. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar9 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama9. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp9. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan9. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

														$kar10 = '
														<tr>
															<td width="7%" style="text-align:right;"> &nbsp;</td>
															<td width="8%"><font color="#000000">Nama</td>
															<td ><font color="#000000"> :&nbsp; ' .$data_rekom[0]->nama10. '</td>
															<td width="30%"></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">NPP</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->npp10. '</td>
															<td></td>
														</tr>
														<tr>
															<td width="5%"></td>
															<td><font color="#000000">Jabatan</td>
															<td><font color="#000000">:&nbsp; ' .$data_rekom[0]->jabatan10. '</td>
															<td></td>
														</tr>									
														<tr>
																<td colspan="4" style="height: 20px;"></td>
														</tr>';

												if($data_rekom[0]->jumlahkar == '1'){
													echo $kar1;
												}
												if($data_rekom[0]->jumlahkar == '2'){
													echo $kar1;
													echo $kar2;
												}
												if($data_rekom[0]->jumlahkar == '3'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
												}
												if($data_rekom[0]->jumlahkar == '4'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
												}
												if($data_rekom[0]->jumlahkar == '5'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
												}
												if($data_rekom[0]->jumlahkar == '6'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
												}
												if($data_rekom[0]->jumlahkar == '7'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
												}
												if($data_rekom[0]->jumlahkar == '8'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
													echo $kar8;
												}
												if($data_rekom[0]->jumlahkar == '9'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
													echo $kar8;
													echo $kar9;
												}
												if($data_rekom[0]->jumlahkar == '10'){
													echo $kar1;
													echo $kar2;
													echo $kar3;
													echo $kar4;
													echo $kar5;
													echo $kar6;
													echo $kar7;
													echo $kar8;
													echo $kar9;
													echo $kar10;
												}
										
										?>
                <tr>
                    <td colspan="4"><font color='#000000'>Diberikan rekomendasi <?= $data_rekom[0]->keterangan_2 ?> sebagai berikut :</td>
                </tr>
                <tr>
                    <td colspan="4"><font color="white">i </font></td>
                </tr>
                <tr>
                    <td width="10%" style="text-align:right;"> &nbsp;</td>
                    <td width="25%"><font color='#000000'>Atas Dasar</td>
                    <td width="60%"><font color='#000000'> : &nbsp;<?= $data_rekom[0]->dasar ?></td>
                </tr>
                <tr>
                    <td width="10%" style="text-align:right;"> &nbsp;</td>
                    <td width="25%"><font color='#000000'>Terhitung Mulai Bulan</td>
                    <td width="60%"><font color='#000000'> : &nbsp;<?= date_mont('M Y',strtotime($data_rekom[0]->tgl)) ?></td>
                </tr>
                <tr>
                    <td width="5%"></td>
                    <td><font color='#000000'>Perubahan</td>
                    <td><font color='#000000'>:&nbsp;<?= $data_rekom[0]->perubahan ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4"><font color="white">i </font></td>
                </tr>
                <tr>
                    <td colspan="4"><font color='#000000'>Demikian surat pemberitahuan ini dibuat, atas perhatian diucapkan terima kasih.
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><font color="white">i </font></td>
                </tr>
            </tbody>
        </table>

    </div>
<!-- PENUTUP TABEL SURAT TUGAS -->
  <section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
                <tr>
                        <td height="20px"></td>
                    </tr>
                    <tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><font color="#000000"><br><br>
						   Pekanbaru
							<span> ,&nbsp; </span>
							<?= $data_rekom[0]->tgl_pengajuan ?>
						</td>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;" ><font color="#000000">Diajukan Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:25%;" ><font color="#000000">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:17%;" ></td>
						<td style="text-align:center; width:20%;" ><font color="#000000">Disetujui Oleh,</td>
					</tr>
                     <tr style="height:60px;">
                            <?php
                                $img_path   = "uploads/file_karyawan/ttd/";
                                $ttdaju     = $img_path."ttd_".$data_rekom[0]->idPengaju.".png";
                                $ttd1       = $img_path."ttd_notyet2.png";
                                $ttd2       = $img_path."ttd_notyet2.png";
                                $ttd3       = $img_path."ttd_notyet2.png";
                                
                                if($data_rekom[0]->aju_ttd1 == '1'){
                                    $ttd1 = $img_path."ttd_20.png";
                                }else if($data_rekom[0]->aju_ttd1 == '2'){
                                    $ttd1 = $img_path."ttd_not.png";
                                }
                                if($data_rekom[0]->aju_ttd2 == '1'){
                                    $ttd2 = $img_path."ttd_54.png";
                                }else if($data_rekom[0]->aju_ttd2 == '2'){
                                    $ttd2 = $img_path."ttd_not.png";
                                }
                            ?>
                            <td style="text-align:center; width:35%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
                            <td style="text-align:center; width:10%;"></td>
                            <td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
                            <td style="text-align:center; width:15%;"></td>
                            <td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; "><font color="#000000">Amtisari Destiani Eka Putri<hr></hr></td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; "><font color="#000000">Yolanda Pratiwi<hr></hr></td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; "><font color="#000000">Bob Ariyos<hr></hr></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; vertical-align:top;"><i><font color="#000000">General Affair</i></td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; vertical-align:top;"><i><font color="#000000">General Manager</i></td>
                            <td style="text-align:center; "></td>
                            <td style="text-align:center; vertical-align:top;"><i><font color="#000000">Director</i></td>
                        </tr>
                    </table>
                    <br><br>
                    <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tr>
							<td colspan="4"><font color="#000000"><i>Tembusan :</i></td>
						</tr>
						<tr>
							<td style="text-align:right; " width="5%"><font color="#000000"><i>1. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><font color="#000000"><i>&nbsp;Direksi</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><i><font color="#000000">2. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><font color="#000000"><i>&nbsp;General Affair</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><font color="#000000"><i>3. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><font color="#000000"><i>&nbsp;Finance</i></td>
						</tr>
						<tr>
							<td style="text-align:right; "><font color="#000000"><i>4. </i></td>
							<td colspan="3" style="text-align:left; vertical-align:top;"><font color="#000000"><i>&nbsp;Arsip</i></td>
						</tr>
                        <tr>
                            <td colspan="4"><font color="white">i </font></td>
                        </tr>
                        <!-- <tr>
                            <td >Lampiran</td>
                            <td colspan="3"> :<input type="text" name="lampiran" id="lampiran" placeholder=" &nbsp;Pastekan link lampiran anda" required></td>
                        </tr> -->
                    </table>
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