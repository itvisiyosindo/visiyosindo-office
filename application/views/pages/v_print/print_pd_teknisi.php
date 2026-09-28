<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Pengajuan Permintaan Dinas Teknisi| <?= $this->config->item('apps_name') ?></title>
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
        <img src="assets/img/kop_baru.jpg" alt="Logo" style="width: 100%;" />
		<hr></hr><hr></hr>
        <div class="inner-wrapper" style="padding-top: 0px">
           <section role="main" class="content-body content-body-modern" style="padding-top:13px; padding-bottom:0px;">
                <!-- start: page -->
					
                    <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
                    	<tr>
							<th width="23.4%"></th>
							<th colspan="" style="height:27px; text-align:center; vertical-align:top;"><font color='#000000'> PERMINTAAN DINAS TEKNISI <hr></hr><hr></hr></th>
							<th width="23.4%"></th>
						</tr>
						<tr>
							<th colspan="3" style="text-align:center"><font color='#000000'> No : <?= $data_pdt[0]->kode ?> </th>
						</tr>
						<tr>
							<td><font color="white">i </font></td>
						</tr>
                    </table>
						
					<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
						<tr>
							<?=
								$pergi		= date('d-m-Y',strtotime($data_pdt[0]->mulai));
								$pulang		= date('d-m-Y',strtotime($data_pdt[0]->akhir));

								
							?>
							<!-- <td rowspan="8" width="7%"></td> -->
							<tr>
								<td width="22%"><font color='#000000'> Nama </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $data_pdt[0]->pengaju ?>  </td>
							</tr>
							<tr>
								<td><font color='#000000'> Wilayah Dinas (Provinsi) </td>
								<td><font color='#000000'> :&nbsp;&nbsp;  <?= $data_pdt[0]->wilayah_dinas ?></td>
							</tr>
							<tr>								
								<td><font color='#000000'> Tanggal </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $pergi ?> &nbsp; - &nbsp; <?= $pulang ?></td>
							</tr>
							<tr>
								<td><font color='#000000'> Lama Dinas </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?= $data_pdt[0]->lama_dinas ?></td>
							</tr>
							<tr>
								<td><font color='#000000'> Tujuan Dinas </td>
								<td><font color='#000000'> :&nbsp;&nbsp; <?=  $data_pdt[0]->tujuan ?></td>
							</tr>
                            <tr>
								<td><font color='#000000'> Transportasi </td>
								<td ><font color='#000000'> :&nbsp;&nbsp; <?= $data_pdt[0]->transportasi ?></td>
							</tr>
                            <tr>
								<td><font color='#000000'> Jenis Kendaraan </td>
								<td ><font color='#000000'> :&nbsp;&nbsp; <?= $data_pdt[0]->jenis_kendaraan ?></td>
							</tr>
                            <tr>
								<td><font color='#000000'> No Polisi</td>
								<td ><font color='#000000'> :&nbsp;&nbsp; <?= $data_pdt[0]->no_polisi ?></td>
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
                            	<th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> HARI</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="15%"><font color='#000000'> TANGGAL </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="25%"><font color='#000000'> NAMA CUSTOMER </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="25%"><font color='#000000'> KETERANGAN</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> KOTA/KABUPATEN </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="20%"><font color='#000000'> PIC </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
								$x=1;
								$nom = 0;
								$xtampil = 0;
								foreach ($detail_pdt as $row) {
								// $x = $x+1;
								// $x2 = $x-1;
								// $nom = (int) $row->ttl + $nom;
								// $nom_pjk = $data_pdt[0]->trf_pajak * $nom;
								// if ($data_pdt[0]->pembayar_pajak == "vendor") {
								// 	$grnd_ttl = $nom - $nom_pjk;
								// } else {
								// 	$grnd_ttl = $nom + $nom_pjk;
								// }
							?>
								<tr>
                                    <!-- <td class="tgl" style="text-align:center;"><?= $row->tgl ?></td> -->
                                   <td class="tgl" style="text-align:center" >&nbsp;<font color='#000000'><?= $row->hari ?>&nbsp;</td>
                                    <td class="tgl" style="text-align:center">&nbsp;<font color='#000000'><?= date('d-m-Y', strtotime($row->tgl)); ?>&nbsp;</td>
                                    <td class="nama_customer" style="text-align:center">&nbsp;<font color='#000000'><?= $row->nama_customer ?>&nbsp;</td>
                                    <td class="tujuan_dinas" style="text-align:center">&nbsp;<font color='#000000'><?= $row->tujuan_dinas ?>&nbsp;</td>
                                    <td class="kota" style="text-align:center">&nbsp;<font color='#000000'><?= $row->kota ?>&nbsp;</td>
                                    <td class="pic" style="text-align:center">&nbsp;<font color='#000000'><?= $row->pic ?>&nbsp;</td>
                                </tr>
                            <?php } ?>
							<?php for($kosong=$x;$kosong<=12;$kosong++){ ?>
								<tr>
                                    <td> <font color="white">i </font> </td>
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
			<br>
			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
		
                <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
					<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							<font color='#000000'><?= $data_pdt[0]->kota_aju ?>, <?= date('d-m-Y', strtotime($data_pdt[0]->tgl_pengajuan));?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2"></td>
						<td style="text-align:center; width:49%;" colspan="3"> </td>
						<th style="text-align:center; width:25.5%;" colspan="2"><font color='#000000'><strong>Dibuat Oleh,</strong></th>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_pdt[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
						?>
						<td style="text-align:center; width:23%;"></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"> </td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"> </td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><font color='#000000'><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'><?= $data_pdt[0]->nama_ttd ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $data_pdt[0]->jabatan ?></i></td>
					</tr>
					</tbody>
				</table>

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