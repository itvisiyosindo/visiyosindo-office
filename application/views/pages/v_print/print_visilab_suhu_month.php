<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> Laporan Suhu Ruangan | <?= $this->config->item('apps_name') ?></title>
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
							<th colspan="" style="height:27px; text-align:center; vertical-align:top;"><font color='#000000'> LAPORAN HARIAN PENCATATAN SUHU DAN KELEMBAPAN RUANGAN <hr></hr><hr></hr></th>
							<th width="23.4%"></th>
						</tr>
                        
						<tr>
							<td><font color="white">i </font></td>
						</tr>
                    </table>

                    <table style="font-family:Times New Roman; font-size:15px" border="0" width="100%">
						<tr>
                            <?php 
                            
                            $bulan =  explode("-",$month);
                            
                                $img_path 	= "uploads/file_karyawan/ttd/";
    							
                                //$bulan12 = explode("-", $month); 
                                $tahun = (int)$bulan[0];
                                $bln   = (int)$bulan[1];
    
                                if ($tahun > 2025 || ($tahun == 2025 && $bln >= 9)) {
                                    $nama = 'Mohd. Rendy Samudra';
                                    $jabatan = 'Staff Teknis';
                                    $ttd	= $img_path."ttd_760.png";
                                } else {
                                    $nama = 'Kardonal, S.Pt';
                                    $jabatan = 'Manager Teknis';
                                    $ttd	= $img_path."ttd_15.png";
                                }
                                
                            ?>
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
                                <th style="text-align:center"><font color='#000000'> TANGGAL</th>
                                <th style="text-align:center" width="15%"><font color='#000000'> WAKTU </th>
                                <th style="text-align:center" width="15%"><font color='#000000'> SUHU (&deg;C) </th>
                                <th style="text-align:center" width="20%"><font color='#000000'> KELEMBAPAN RELATIF RH (%) </th>
                                <th style="text-align:center" width="17%"><font color='#000000'> KETERANGAN </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
                                $x = 1;
                                $nom = 0;
                                $xtampil = 0;

                                // Ambil filter bulan dari post (atau default bulan ini)
                                $filter_month = $month;

                                // Pecah tahun dan bulan
                                $year_month = explode('-', $filter_month);
                                $year = $year_month[0];
                                $month = $year_month[1];

                                // Bikin array suhu_by_date, tapi yang hanya sesuai bulan filter
                                $suhu_by_date = [];
                                foreach ($detail_suhu as $row) {
                                    // Filter hanya data bulan yang dipilih
                                    if (date('Y-m', strtotime($row->created_at)) == $filter_month) {
                                        $tanggal = date('Y-m-d', strtotime($row->created_at));
                                        $suhu_by_date[$tanggal] = $row;
                                    }
                                }

                                // Hitung jumlah hari di bulan tersebut
                                $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                                // Biar kalau bulan sekarang, data cuma sampai hari ini
                                $current_year = date('Y');
                                $current_month = date('m');
                                $current_day = date('d');

                                if ($year == $current_year && $month == $current_month) {
                                    $days_in_month = $current_day;
                                }

                                // Cek apakah bulan yang dipilih masa depan
                                $filter_timestamp = strtotime($filter_month . '-01');
                                $now_timestamp = strtotime(date('Y-m-01'));

                                if ($filter_timestamp > $now_timestamp) {
                                    // Kalau bulan depan atau bulan setelah sekarang, gak tampilkan apapun
                                    echo "<tr><td colspan='6' style='text-align:center'><font color='#000000'><b>Belum ada data untuk bulan ini</b></font></td></tr>";
                                } else {
                                    // Loop dari tanggal 1 sampai jumlah hari bulan
                                    for ($day = 1; $day <= $days_in_month; $day++) {
                                        $tanggal = sprintf('%04d-%02d-%02d', $year, $month, $day);

                            //Fungsi untuk Nama Hari
                            if (!function_exists('namaHari')) {
                                function namaHari($tanggal) {
                                    $hari = date('N', strtotime($tanggal)); // 1 (Senin) sampai 7 (Minggu)
                                    $nama_hari = [
                                        1 => 'Senin',
                                        2 => 'Selasa',
                                        3 => 'Rabu',
                                        4 => 'Kamis',
                                        5 => 'Jumat',
                                        6 => 'Sabtu',
                                        7 => 'Minggu',
                                    ];
                                    return $nama_hari[$hari];
                                }
                            }

                                ?>
                                        <tr>
                                            <td style="text-align:center"><font color='#000000'><?= $x++ ?></td>
                                            <?php if (isset($suhu_by_date[$tanggal])) {
                                                $row = $suhu_by_date[$tanggal];
                                            ?>
                                                <td class="tanggal" style="text-align:center">&nbsp;<font color='#000000'><?= namaHari($row->created_at) . ', ' . date('d-m-Y', strtotime($row->created_at)) ?>&nbsp;</td>
                                                <td class="waktu" style="text-align:center">&nbsp;<font color='#000000'><?= date('H:i:s', strtotime($row->created_at)) . ' WIB' ?>&nbsp;</td>
                                                <td class="suhu" style="text-align:center">&nbsp;<font color='#000000'><?= $row->suhu . ' &deg;C' ?>&nbsp;</td>
                                                <td class="kelembapan" style="text-align:center">&nbsp;<font color='#000000'><?= $row->kelembapan . ' %' ?>&nbsp;</td>
                                                <td class="keterangan" style="text-align:center">&nbsp;<font color='#000000'><?= $row->keterangan ?>&nbsp;</td>
                                            <?php } else { ?>
                                                <td class="tanggal" style="text-align:center">&nbsp;<font color='#000000'><?= namaHari($tanggal) . ', ' . date('d-m-Y', strtotime($tanggal)) ?>&nbsp;</td>
                                                <td class="waktu" style="text-align:center">&nbsp;<font color='#000000'>-</td>
                                                <td class="suhu" style="text-align:center">&nbsp;<font color='#000000'>-</td>
                                                <td class="kelembapan" style="text-align:center">&nbsp;<font color='#000000'>-</td>
                                                <td class="keterangan" style="text-align:center"><b><font color='#000000'>&nbsp;Libur</font></b></td>
                                            <?php } ?>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
				
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