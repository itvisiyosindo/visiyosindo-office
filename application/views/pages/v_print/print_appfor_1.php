<!doctype html>
<html class="modern fixed has-top-menu has-left-sidebar-half" data-style-switcher-options="{'changeLogo': false}">

<head>
    <base href="<?= base_url() ?>">
    <!-- Basic -->
    <meta charset="UTF-8">

    <title> APPROVAL FORWARDER | <?= $this->config->item('apps_name') ?></title>
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
							<th colspan="" style="height:20px; text-align:center; vertical-align:top; border: none; font-size:15px"><font color='#000000'> APPROVAL FORWARDER  <hr></hr></th>
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

      </section>

			<section role="main" class="content-body content-body-modern" style="padding-top:5px; padding-bottom:0px;">
        <div class="table-responsive">
          <table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="red">
						<tr>
							<td colspan="3" style="text-align:left">
								<font color='#000000'>Dengan ini saya mengajukan Permintaan Approval Forwarder Luar Negeri :</font>
							</td>
						</tr>
						<tr>
								<td><font color="white">i </font></td>
						</tr>
					</table>
						
					<table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="red">
						<tr>															
							<tr>
								<td width="20%"<font color='#000000'>>Nama </td>
								<td><font color='#000000'>:&nbsp;&nbsp; <?= $data_aprv[0]->pengaju ?>  </td>
							</tr>
							<tr>
								<td><font color='#000000'>Jabatan </td>
								<td><font color='#000000'>:&nbsp;&nbsp;  <?=  $data_aprv[0]->jabatan ?></td>
							</tr>
							
							<tr>
								<td><font color="white">i </font></td>
							</tr>
							<?php
								$kurs = 'Rp ' . number_format($data_aprv[0]->kurs, 0, ',', '.');
							?>
							<tr>								
								<td><font color='#000000'>Nama Shipment</td>
								<td><font color='#000000'>:&nbsp;&nbsp; <?=  $data_aprv[0]->nama ?></td>
							</tr>
							<tr>								
								<td><font color='#000000'>Sistem Pengiriman</td>
								<td><font color='#000000'>:&nbsp;&nbsp; <?=  $data_aprv[0]->sistem_pengiriman ?></td>
							</tr>
							<tr>								
								<td><font color='#000000'>Port of Loading (POL)</td>
								<td><font color='#000000'>:&nbsp;&nbsp; <?=  $data_aprv[0]->pol ?></td>
							</tr>
							<tr>								
								<td><font color='#000000'>Port of Discharge (POD) </td>
								<td><font color='#000000'>:&nbsp;&nbsp; <?=  $data_aprv[0]->pod ?></td>
							</tr>
							<?php
							$lines = explode("\n", $data_aprv[0]->berat_dimensi);
							$formatted = '';

							foreach ($lines as $i => $line) {
									if ($i == 0) {
											$formatted .= ':&nbsp;&nbsp;' . htmlspecialchars($line) . '<br>';
									} else {
											$formatted .= '&nbsp;&nbsp;&nbsp;' . htmlspecialchars($line) . '<br>';
									}
							}
							?>
							<tr>
									<td style="vertical-align: top;"><font color='#000000'>Berat dan Dimensi Barang</td>
									<td><font color='#000000'><?= $formatted ?></td>
							</tr>
							<tr>								
								<td><font color='#000000'>Nilai Invoice Shipment</td>
								<td><font color='#000000'>:&nbsp;&nbsp; <?=  $data_aprv[0]->mata_nilai_inv.' '.$data_aprv[0]->nilai_inv ?></td>
							</tr>
							<tr>								
								<td><font color='#000000'>Kurs 1 USD </td>
								<td><font color='#000000'>:&nbsp;&nbsp; <?=  $kurs ?></td>
							</tr>
							
							<tr>
								<td><font color="white">i </font></td>
							</tr>						
						</tr>
					</table>
				</div>                
			</section>
			
			<section role="main" class="content-body content-body-modern" style="padding-top:5px; padding-bottom:0px;">
            <div class="table-responsive">

              <table id="tbl_1" style="font-family:Times New Roman; font-size:12px" border="0" width="100%" color="red">
								<tr>
									<td colspan="3" style="text-align:left">
										<font color='#000000'>Detail perbandingan Forwarder sebagai berikut :</font>
									</td>
								</tr>
								
									<tr>
										<td><font color="white">i </font></td>
									</tr>
									
							</table>
						
							<?php
							$data = $detail_aprv;
							$forwarderCount = count($data);
							?>

							<table id="kt_table_1" style="font-family:Times New Roman; color:black; font-size:12px" border="1" width="100%">
								<thead>
									<tr>
										<!--<th style="text-align:center" bgcolor="#d3d3d3" width="3%"> No </th>-->
										<th style="text-align:center" bgcolor="#d3d3d3"> Nama Forwarder </th>
										<?php foreach ($forwarderNames as $name): ?>
											<th style="text-align:center" bgcolor="#d3d3d3"><?= htmlspecialchars($name) ?></th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<tbody>
									<?php
									$keteranganList = [
										'EXW CHARGES<br>(Biaya di Negara Asal)' => null,
										'THC' => 'asal_thc',
										'B/L Fee' => 'asal_bl_fee',
										'VGM' => 'asal_vgm',
										'Agency Fee / Admin Fee' => 'asal_agaency_fee',
										'Handling Fee' => 'asal_handling_fee',
										'Transportation / Trucking' => 'asal_transportasi',
										'Loading / Unloading' => 'asal_loading',
										'Custom Clearance' => 'asal_custom',
										'Pick Up Fee' => 'asal_pickup',
										'Freight<br>(Biaya Ongkos Kirim)' => null,
										'Ocean Freight' => 'freight_ocean',
										'Air Freight' => 'freight_air',
										'Destination Charges<br>(Biaya di Negara Tujuan)' => null,
										'CFS' => 'tuj_cfs',
										'DOC' => 'tuj_doc',
										'AGENCY FEE' => 'tuj_agency_fee',
										'HANDLING' => 'tuj_handling',
										'PU & DO' => 'tuj_do',
										'ADMIN' => 'tuj_admin',
										'DEVANNING' => 'tuj_devanning',
										'FORWARDING FEE' => 'tuj_fordwarding_fee',
										'MECHANICSS' => 'tuj_mechanics',
										'OTHER' => 'tuj_other',
										'Custom Clearance Charges<br>(Bea Cukai)' => null,
										'Custom Clearance Charges' => 'cust_clearance',
										'Red Line / Labor (if any)' => 'cust_red_line',
										'Handling Charges' => 'cust_handling',
										'Admin Fee' => 'cust_admin_fee',
										'PIB Fee' => 'cust_pib_fee',
										'Transfer EDI' => 'cust_transfer',
										'STORAGE + LIFT OF LIFT ON' => 'cust_storage',
										'Other Charges' => null,
										'DO' => 'oth_do',
										'Storage' => 'oth_storage',
										'TRUCKING (Delivery Fee) (if any)' => 'oth_trucking',
										'Handling Gudang' => 'oth_handling',
										'TOTAL ONGKOS KIRIM' => null,
										'PPN' => 'ppn',
										'Insurance' => null,
										'Nilai Asuransi' => 'ins_nilai',
										'Jenis Asuransi' => 'ins_jenis',
										'FORWARDER YANG DIPILIH' => 'dipilih',
										'Alasan' => 'alasan',
										'Link Invoice' => 'link_invoice',
										'Link Packing List' => 'link_packing',
										'Link SPH Forwarder' => 'link_sph_for',
										'Link SPH Asuransi' => 'link_sph_ins',
									];

									// Tambahkan ini: label yang ingin dibold
									$boldItems = ['EXW CHARGES<br>(Biaya di Negara Asal)', 'Freight<br>(Biaya Ongkos Kirim)', 'Destination Charges<br>(Biaya di Negara Tujuan)', 
									'Custom Clearance Charges<br>(Bea Cukai)', 'Other Charges', 'TOTAL ONGKOS KIRIM', 'Insurance', 'FORWARDER YANG DIPILIH', 'Aksi'];

									$no = 1;
									foreach ($keteranganList as $label => $kolomDb):
										$isBold = in_array($label, $boldItems);
										$style = $isBold ? ' style="font-weight:bold; background-color:#e5e5e5;"' : '';
										$styleLabel = 'style="text-align:center;' . ($isBold ? 'font-weight:bold; background-color:#e5e5e5;' : '') . '"';

									?>
										<tr>
											<!--<td align="center"<?= $style ?>><?= $no++ ?></td>-->
											<td <?= $styleLabel ?>><?= $label ?></td>


											<?php //foreach ($data as $row): ?>
											<?php foreach (array_values($data) as $i => $row): ?>


												<?php
												$align = 'right';
												if ($kolomDb === 'ppn' || $kolomDb === 'ins_jenis' || $kolomDb === 'storage' || $kolomDb === 'dipilih' || $kolomDb === 'alasan'
														|| $kolomDb === 'link_invoice' || $kolomDb === 'link_packing' || $kolomDb === 'link_sph_for' || $kolomDb === 'link_sph_ins') {
													$align = 'center';
												}
												?>
												<td align="<?= $align ?>"<?= $style ?>>
													<?php
														if ($kolomDb === null) {
															if ($label === 'TOTAL ONGKOS KIRIM') {
																$total = $row->asal_thc +	$row->asal_bl_fee + $row->asal_vgm + $row->asal_agaency_fee + $row->asal_handling_fee +
																				$row->asal_transportasi + $row->asal_loading + $row->asal_custom + $row->freight_ocean + $row->freight_air +
																				$row->tuj_cfs + $row->tuj_doc + $row->tuj_agency_fee + $row->tuj_handling + $row->tuj_do + $row->tuj_admin +
																				$row->tuj_devanning + $row->tuj_fordwarding_fee + $row->tuj_mechanics + $row->tuj_other + $row->cust_clearance +
																				$row->cust_red_line + $row->cust_handling + $row->cust_admin_fee + $row->cust_pib_fee + $row->cust_transfer + $row->oth_trucking
																+ $row->asal_pickup + $row->cust_storage + $row->oth_handling + $row->oth_do + $row->oth_storage;

																echo 'Rp ' . number_format($total, 0, ',', '.');
															} else {
																echo '';
															}
														} else {
															$value = $row->$kolomDb;

															if ($kolomDb === 'alasan') {
																echo nl2br(htmlspecialchars($value));
															}	elseif ($kolomDb === 'dipilih') {
																		echo ($value == 1) ? htmlspecialchars($forwarderNames[$i]) : '';
															} else {
																echo is_numeric($value) ? 'Rp ' . number_format($value, 0, ',', '.') : $value;
															}
														}
													?>

												</td>
											<?php endforeach; ?>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
                    
                </div>
                
			</section>
			
			<section role="main" class="content-body content-body-modern" style="padding-top: 0px;">
                      
      <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:12px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
							<font color='#000000'><?=  $data_aprv[0]->kota_aju ?> Pekanbaru
							<span> ,&nbsp; </span>
							<?= date('d-m-Y',strtotime($data_aprv[0]->created_at)) ?>
							
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25%;" ><font color='#000000'>Dibuat Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:25%;" ><font color='#000000'>Diverifikasi Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:25%;" ><font color='#000000'>Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_aprv[0]->id_pengguna.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							
							if($data_aprv[0]->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_23.png";
							}else if($data_aprv[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							
							if($data_aprv[0]->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_54.png";
							}else if($data_aprv[0]->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttd1.'" height="70">';?> </td>
						<td style="text-align:center; width:10%;"></td>
						<td style="text-align:center; width:25%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color='#000000'><?= $data_aprv[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'>Meilina Safitri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color='#000000'>Bob Ariyos<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i><?= $data_aprv[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i>Director of Corporate Planning & Business Management</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><font color='#000000'><i>Director</i></td>
					</tr>
				</tbody>
				
			</table>
            <br><br>
                

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