<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Berita Acara Visilab | <?= $this->config->item('apps_name') ?></title>
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
    <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:12px" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td colspan="9" style="text-align:center; font-size:15px; font-weight:bold;"><br>
                        	        <font color="#000000"><u>BERITA ACARA</u><br></font> 
                        	        <font color="#000000"><u>LAPORAN, VERIFIKASI DAN PERSETUJUAN</u></font> 
                        		</td>
                            </tr>
                            <tr>
                        		<td colspan="9" style="text-align:center; font-size:15px;">
                        	        <font color="#000000"> No : <?= $data_ba[0]->kode_ba ?> </font> <br><br>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="9" style="text-align:center; font-size:12px; font-weight:bold;"></td>
                        	</tr>
                        	<tr>
                        		<td colspan="9"><font color="white">i</font></td>
                        	</tr>
                        	<tr>
                                <font color='#ffffff'>
                                    <?= 
                                        $tanggal		= strtotime($data_ba[0]->tanggal);
                                        $hari 	= date("D", $tanggal);
                                        $tgl 	= date("d", $tanggal);
                                        $bln 	= date("m", $tanggal);
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
                                    ?>
                                </font>	
                                <td></td>
                        		<td colspan="9"><font color="#000000"><br>	Pada hari ini &nbsp;&nbsp; <u><?= $hari ?></u> &nbsp;&nbsp; Tanggal &nbsp;&nbsp; <u><?= $tgl ?></u> &nbsp;&nbsp; Bulan &nbsp;&nbsp; <u><?= $bln ?></u> &nbsp;&nbsp; Tahun &nbsp;&nbsp; <u><?= $thn ?></u> .
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="9" style="font-weight:bold;"><font color="#000000"><br><br> Telah dilakukan penelitian dan analisis terhadap : 
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="9"><font color="#000000"><br>	<?= nl2br(htmlspecialchars($data_ba[0]->analisis)) ?> . 
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="9" style="font-weight:bold;"><font color="#000000"><br><br> Hasil Sementara : 
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="9"><font color="#000000"><br>	<?= nl2br(htmlspecialchars($data_ba[0]->hasil)) ?> . 
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="9" style="font-weight:bold;"><font color="#000000"><br><br> Saran, masukan, arahan dan penanganan : 
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="9"><font color="#000000"><br>	<?= nl2br(htmlspecialchars($data_ba[0]->penanganan)) ?> . 
                                </td>
                                <td></td>
                        	</tr>
                            <tr>
                                <td></td>
                        		<td colspan="9"><font color="#000000"><br>	Demikian berita acara ini dibuat, agar dapat digunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya diucapkan terimakasih. 
                                </td>
                                <td></td>
                        	</tr>
                        	<tr>
                        		<td></td>
                        		<td colspan="9" style="text-align:right"><br><br></td>
                        	</tr>

                       


                    

                   


                 </tbody>
			</table>
			<br>
			<br>

            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:12px;">
				<tbody>

				<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_ba[0]->idPengaju.".png";

							// Default tanda tangan
							$ttd1 = $img_path."ttd_notyet2.png";
							$ttd2 = $img_path."ttd_notyet2.png";
							$ttd3 = $img_path."ttd_notyet2.png";
							$ttd4 = $img_path."ttd_notyet2.png";
							$ttd5 = $img_path."ttd_notyet2.png";

							// TTD 1
							if ($data_ba[0]->ttd_1 == '1') {
								$ttd1 = $img_path."ttd_".$data_ba[0]->id_ttd1.".png";
							} else if ($data_ba[0]->ttd_1 == '2') {
								$ttd1 = $img_path."ttd_not.png";
							}

							// TTD 2
							if ($data_ba[0]->ttd_2 == '1') {
								$ttd2 = $img_path."ttd_".$data_ba[0]->id_ttd2.".png";
							} else if ($data_ba[0]->ttd_2 == '2') {
								$ttd2 = $img_path."ttd_not.png";
							}

							// TTD 3
							if ($data_ba[0]->ttd_3 == '1') {
								$ttd3 = $img_path."ttd_".$data_ba[0]->id_ttd3.".png";
							} else if ($data_ba[0]->ttd_3 == '2') {
								$ttd3 = $img_path."ttd_not.png";
							}

							// TTD 4
							if ($data_ba[0]->ttd_4 == '1') {
								$ttd4 = $img_path."ttd_".$data_ba[0]->id_ttd4.".png";
							} else if ($data_ba[0]->ttd_4 == '2') {
								$ttd4 = $img_path."ttd_not.png";
							}

							// TTD 5
							if ($data_ba[0]->ttd_5 == '1') {
								$ttd5 = $img_path."ttd_".$data_ba[0]->id_ttd5.".png";
							} else if ($data_ba[0]->ttd_5 == '2') {
								$ttd5 = $img_path."ttd_not.png";
							}


							//Jabatan 
							if($data_ba[0]->jabatan_visilab != ''){
									$jabatanaju = $data_ba[0]->jabatan_visilab;
							}else{
									$jabatanaju = $data_ba[0]->jabatan;
							}

							if($data_ba[0]->jabatan_visilab1 != ''){
								// 	$jabatan1 = $data_ba[0]->jabatan_visilab1;
									$jabatan1 = $data_ba[0]->jabatan1;
							}else{
									$jabatan1 = $data_ba[0]->jabatan1;
							}

							if($data_ba[0]->jabatan_visilab2 != ''){
									$jabatan2 = $data_ba[0]->jabatan_visilab2;
							}else{
									$jabatan2 = $data_ba[0]->jabatan2;
							}
							if($data_ba[0]->jabatan_visilab3 != ''){
									$jabatan3 = $data_ba[0]->jabatan_visilab3;
							}else{
									$jabatan3 = $data_ba[0]->jabatan3;
							}

							if($data_ba[0]->jabatan_visilab4 != ''){
									$jabatan4 = $data_ba[0]->jabatan_visilab4;
							}else{
									$jabatan4 = $data_ba[0]->jabatan4;
							}

							if($data_ba[0]->jabatan_visilab5 != ''){
									$jabatan5 = $data_ba[0]->jabatan_visilab5;
							}else{
									$jabatan5 = $data_ba[0]->jabatan5;
							}

				if($data_ba[0]->jumlah_ttd ==1) {

						?>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:17%;" ><font color="#000000">Diajukan Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:20%;" ><font color="#000000">Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama1 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan1 ?></i></td>
					</tr>
				<?php } else if ($data_ba[0]->jumlah_ttd == 2) { ?>
					<tr style="height: 18px;">
							<td style="text-align:center; width:33%;"><font color="#000000">Diajukan Oleh,</td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center; width:33%;"><font color="#000000">Diketahui Oleh,</td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center; width:33%;"><font color="#000000">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
							<td style="text-align:center;"><img src="<?= $ttdaju ?>" height="70"></td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center;"><img src="<?= $ttd1 ?>" height="70"></td>
						<td style="text-align:center; width:5%;"></td>
							<td style="text-align:center;"><img src="<?= $ttd2 ?>" height="70"></td>
					</tr>
					<tr>
							<td style="text-align:center;"><font color="#000000"><?= $data_ba[0]->pengaju ?><hr></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><font color="#000000"><?= $data_ba[0]->nama1 ?><hr></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><font color="#000000"><?= $data_ba[0]->nama2 ?><hr></td>
					</tr>
					<tr>
							<td style="text-align:center;"><font color="#000000"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><font color="#000000"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
							<td style="text-align:center;"><i><font color="#000000"><?= $jabatan2 ?></i></td>
					</tr>
				<?php }else if ($data_ba[0]->jumlah_ttd ==3){ ?>
					<tr style="height: 18px;">
						<td style="text-align:center; width:17%;" ><font color="#000000">Diajukan Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:15%;" ><font color="#000000">Diketahui Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:20%;" ><font color="#000000">Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:17%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:5%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama2 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama3 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan3 ?></i></td>
					</tr>
				<?php }else if ($data_ba[0]->jumlah_ttd ==4){ ?>
					<tr style="height: 18px;">
						<td style="text-align:center; width:15%;" ><font color="#000000">Diajukan Oleh,</td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:12%;" ><font color="#000000">Diketahui Oleh,</td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:2%;" ></td>
						<td style="text-align:center; width:20%;" ><font color="#000000">Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:17%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:17%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttd4.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama2 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama3 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama4 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan3 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan4 ?></i></td>
					</tr>
				<?php }else if ($data_ba[0]->jumlah_ttd ==5){ ?>
					<tr style="height: 18px;">
						<td style="text-align:center; width:17%;" ><font color="#000000">Diajukan Oleh,</td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:7%;" ><font color="#000000">Diketahui Oleh,</td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:1%;" ></td>
						<td style="text-align:center; width:20%;" ><font color="#000000">Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<td style="text-align:center; width:15%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd4.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:15%;"><?php echo'<img src="'.$ttd5.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama1 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama2 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama3 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama4 ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="#000000"><?= $data_ba[0]->nama5 ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatanaju ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan1 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan2 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan3 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan4 ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color="#000000"><i><?= $jabatan5 ?></i></td>
					</tr>
				<?php } ?>

				</tbody>
				
			</table>

            <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:12px" border="0" width="100%">
                <tbody>

                    <tr>
                        <td></td>
                        <td colspan="4" style="text-align:left;" ><br><br><font color="#000000"><br><br>
                        <i>Tembusan</i> :<br>
                        <i>1. Presiden Director</i><br>
                        <i>2. Director</i><br>
                        <i>3. Arsip</i><br>
                            </font>
                         </td>
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
            <img src="assets/img/kop_surat_bawah.jpg" alt="Logo" style="width: 100%;" />
        </footer>
        <!-- includes_js.php -->
    </section>
</body>

</html>