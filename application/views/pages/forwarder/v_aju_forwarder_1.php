<header class="page-header">
    <h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
	
	<style>
		hr{
		   display: block;
		   margin-top: 0em;
		   margin-bottom: 0em;
		   margin-left: auto;
		   margin-right: auto;
		   border-top: 1px solid black;
		}
		
		input{
			width:97%;
			height:auto;
			border:0px dotted #f30; 
			border-radius:4px; 
			-moz-border-radius:8px;			
			margin-right:0px;
		}
		
		.myinput{
			width:97%;
			height:auto;
			border:0px solid #000;
			border-radius:0px; 
			-moz-border-radius:8px;
			margin-left:0px;
			background:#b7d5ac;
		}
		
		.myselect{
			width:97%;
			height:auto;
			border:0px solid #000; 
			border-radius:4px; 
			-moz-border-radius:8px;
			margin:0px;
		}
		
		.mydiv br {
			display: none;
		}
		
		.mydiv p {
			padding: 0;
			margin: 0;

			
			
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
			<div class="text-right">
        <a href="#" class="btn btn-sm btn-warning btn-duplikat" data-href="<?= site_url('forwarder/duplikat/' . $data_aprv[0]->idGc) ?>">
						<i class="fas fa-clone"></i> Duplikat
				</a>
      </div>
	    <div class="table-responsive">
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'>Form Permintaan Approval Forwarder Luar Negeri</font></h2>
        		<h2><font color='#000000' face='Times New Roman'>No : <?= $data_aprv[0]->kode?></font></h2>
        </div><br>
		<font color='#000000'>
			<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '23')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '54')){
					$ttd = 'ttd_2';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_aprv[0]->idGc ?> 
		<input type="hidden" name="id" id="id" value="<?= $data_aprv[0]->idGc ?>">


		
    <?= form_open('surat_new/updateApprovalEks', array('id' => 'main-form', 'autocomplete' => 'off')); ?>
			
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan Permintaan Approval Forwarder Luar Negeri :</font>
					</td>
				</tr>
				<tr>	
														
					</font>
		
					<td rowspan="17" width="2%"></td>
					
            <input type="hidden" id="idpengaju" value="<?= $data_aprv[0]->id_pengguna ?>"> 
					<tr>
						<td width="15%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_aprv[0]->pengaju ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?=  $data_aprv[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<?php
						$tanggal = $data_aprv[0]->tanggal;
            $bulanTahun = date('F Y', strtotime($tanggal));

            // ubah nama bulan Inggris ke Indonesia
            $bulanIndonesia = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember',
            ];

            list($bulanInggris, $tahun) = explode(' ', $bulanTahun);
            $tanggalFormatted = $bulanIndonesia[$bulanInggris] . ' ' . $tahun;

						$kurs = 'Rp ' . number_format($data_aprv[0]->kurs, 0, ',', '.');


					?>
					<!--<tr>
						<td>Bulan </td>
						<td>:&nbsp;&nbsp; <?=  $tanggalFormatted ?></td>
					</tr>-->
					<tr>								
						<td>Nama Shipment</td>
						<td>:&nbsp;&nbsp; <?=  $data_aprv[0]->nama ?></td>
					</tr>
					<tr>								
						<td>Sistem Pengiriman</td>
						<td>:&nbsp;&nbsp; <?=  $data_aprv[0]->sistem_pengiriman ?></td>
					</tr>
					<tr>								
						<td>Port of Loading (POL)</td>
						<td>:&nbsp;&nbsp; <?=  $data_aprv[0]->pol ?></td>
					</tr>
					<tr>								
						<td>Port of Discharge (POD) </td>
						<td>:&nbsp;&nbsp; <?=  $data_aprv[0]->pod ?></td>
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
							<td style="vertical-align: top;">Berat dan Dimensi Barang</td>
							<td><?= $formatted ?></td>
					</tr>
					<tr>								
						<td>Nilai Invoice Shipment</td>
						<td>:&nbsp;&nbsp; <?=  $data_aprv[0]->mata_nilai_inv.' '.$data_aprv[0]->nilai_inv ?></td>
					</tr>
					<tr>								
						<td>Kurs 1 USD </td>
						<td>:&nbsp;&nbsp; <?=  $kurs ?></td>
					</tr>
					

					<tr>
						<td><font color="white">i </font></td>
					</tr>

					
				</tr>
			</table>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Detail perbandingan Forwarder sebagai berikut :</font>
					</td>
				</tr>
				
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					
					<div class="card-body">
						<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Forwarder</a>
					</div>
			</table>
		
			<?php
			$data = $detail_aprv;
			$forwarderCount = count($data);
			?>

			<table id="kt_table_1" style="font-family:Times New Roman; color:black; font-size:15px" border="1" width="100%">
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
						'Aksi' => null,
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
								if ($label === 'Aksi') {
									$align = 'center';
								} elseif ($kolomDb === 'ppn' || $kolomDb === 'ins_jenis' || $kolomDb === 'storage' || $kolomDb === 'dipilih' || $kolomDb === 'alasan'
									 || $kolomDb === 'link_invoice' || $kolomDb === 'link_packing' || $kolomDb === 'link_sph_for' || $kolomDb === 'link_sph_ins') {
									$align = 'center';
								}
								?>
								<td align="<?= $align ?>"<?= $style ?>>
									<?php
										if ($label === 'Aksi') {
											
            						$id       	= encrypt($row->id);
												if($data_aprv[0]->status==0 || $data_aprv[0]->status==1){
													echo '<div style="display: flex; gap: 8px; justify-content: center;">
																	<button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '">
																			<i class="fas fa-edit"></i> Edit
																	</button>
																	<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'.$row->id.'" data-object="forwarder/deleteDetail/"'.$row->id.'"> 
																		<i class="bx bx-trash"></i> Hapus
																	</button>		
																</div>';
												}else{
													echo '';
												}
										} elseif ($kolomDb === null) {
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

											if ($kolomDb === 'link_invoice') {
												if (!empty($value)) {
													echo '<a href="' . htmlspecialchars($value) . '" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> Invoice
																</a>';
												} else {
													echo '-';
												}
											} elseif ($kolomDb === 'link_packing') {
												if (!empty($value)) {
													echo '<a href="' . htmlspecialchars($value) . '" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> Packing List
																</a>';
												} else {
													echo '-';
												}
											} elseif ($kolomDb === 'link_sph_for') {
												if (!empty($value)) {
													echo '<a href="' . htmlspecialchars($value) . '" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> SPH Forwarder
																</a>';
												} else {
													echo '-';
												}
											} elseif ($kolomDb === 'link_sph_ins') {
												if (!empty($value)) {
													echo '<a href="' . htmlspecialchars($value) . '" class="btn btn-sm btn-primary" target="_blank" style="min-width:100px;">
																	<i class="fas fa-link"></i> SPH Asuransi
																</a>';
												} else {
													echo '-';
												}
											} elseif ($kolomDb === 'detail' || $kolomDb === 'alasan' || $kolomDb === 'pol' || $kolomDb === 'pod' || $kolomDb === 'berat') {
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

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>	
					<td rowspan="17" width="2%"></td>
					
						<?php if($data_aprv[0]->status==3){ ?>

							
							<tr>
								<td><font color="white">i </font></td>
							</tr>
							
							<tr>
								<td width="20%">Link Invoice Final</td>
								<td>:&nbsp;&nbsp;<input id="catatan_finance" type="text" placeholder="Klik Untuk Memasukkan Link Invoice Final" value="<?= isset($data_aprv[0]->link_final) ? htmlspecialchars($data_aprv[0]->link_final) : '' ?>" required>
								</td>
							</tr>

						<?php }?>
							
					
				</tr>	
			</table>


      <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="6"><br><br>
							<?=  $data_aprv[0]->kota_aju ?> Pekanbaru
							<span> ,&nbsp; </span>
							<?= date('d-m-Y',strtotime($data_aprv[0]->created_at)) ?>
							
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25%;" >Dibuat Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:25%;" >Diverifikasi Oleh,</td>
						<td style="text-align:center; width:10%;" ></td>
						<td style="text-align:center; width:25%;" >Disetujui Oleh,</td>
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
						<td style="text-align:center; "><?= $data_aprv[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Bob Ariyos<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_aprv[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>
				</tbody>
				
			</table>
			</div>
		
			<br><br>
		<div role="document">
			<?php if($data_aprv[0]->status==0){ ?>
			<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Ajukan </button>
      <?php } ?>
			
			 <?php if ($data_aprv[0]->status==3) { ?>
			  <button type="button" class="btn btn-success float-right btn-submit2" style="margin-left:12px;" id-Sijk="<?=encrypt($data_aprv[0]->idGc)?>"> <i class="fas fa-check"></i> Submit (Invoice Final) </button>				
			 <?php }?>
			 
			<?php if($data_aprv[0]->link_final != ''){ ?>
			<a href="<?=$data_aprv[0]->link_final?>" class="btn btn-primary float-right" style="margin-left:12px;"> <i class="fas fa-link"></i> Invoice Final </a>
			<?php } ?>
			<a href="forwarder/print_page/detail/<?=$data_aprv[0]->idGc?>" class="btn btn-warning float-right" style="margin-left:12px;"> <i class="fas fa-print"></i> Cetak </a>
      <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br><br>
		</div>

		
    <?= form_close(); ?>
		
    </div>	
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Perbandingan Expedisi Luar Negeri</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="id_forwarder" class="form-control-label"><strong> Nama Forwarder </strong> <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_forwarder" name="id_forwarder" required>
							<option value="">- Pilih Forwarder -</option>
							<?php
							foreach ($list_for as $row) {
								echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
							}
							?>
						</select>
					</div>
					
					<br>
					<strong> EXW CHARGES (Biaya di Negara Asal) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_thc1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_thc1" name="uang_asal_thc1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_thc" class="form-control-label">THC :</label>
							<input type="number" class="form-control" id="asal_thc" name="asal_thc">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_bl_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_bl_fee1" name="uang_asal_bl_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_bl_fee" class="form-control-label">B/L Fee :</label>
							<input type="number" class="form-control" id="asal_bl_fee" name="asal_bl_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_vgm1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_vgm1" name="uang_asal_vgm1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_vgm" class="form-control-label">VGM :</label>
							<input type="number" class="form-control" id="asal_vgm" name="asal_vgm">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_agaency_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_agaency_fee1" name="uang_asal_agaency_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_agaency_fee" class="form-control-label">Agency Fee / Admin Fee :</label>
							<input type="number" class="form-control" id="asal_agaency_fee" name="asal_agaency_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_handling_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_handling_fee1" name="uang_asal_handling_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_handling_fee" class="form-control-label">Handling Fee :</label>
							<input type="number" class="form-control" id="asal_handling_fee" name="asal_handling_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_transportasi1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_transportasi1" name="uang_asal_transportasi1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_transportasi" class="form-control-label">Transportation / Trucking :</label>
							<input type="number" class="form-control" id="asal_transportasi" name="asal_transportasi">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_loading1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_loading1" name="uang_asal_loading1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_loading" class="form-control-label">Loading / Unloading :</label>
							<input type="number" class="form-control" id="asal_loading" name="asal_loading">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_custom1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_custom1" name="uang_asal_custom1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_custom" class="form-control-label">Custom Clearance :</label>
							<input type="number" class="form-control" id="asal_custom" name="asal_custom">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_pickup1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_pickup1" name="uang_asal_pickup1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_pickup" class="form-control-label">Pick Up Fee :</label>
							<input type="number" class="form-control" id="asal_pickup" name="asal_pickup">
						</div>
					</div>
					<br>
					<strong> Freight (Biaya Ongkos Kirim) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_freight_ocean1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_freight_ocean1" name="uang_freight_ocean1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="freight_ocean" class="form-control-label">Ocean Freight :</label>
							<input type="number" class="form-control" id="freight_ocean" name="freight_ocean">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_freight_air1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_freight_air1" name="uang_freight_air1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="freight_air" class="form-control-label">Air Freight :</label>
							<input type="number" class="form-control" id="freight_air" name="freight_air">
						</div>
					</div>
					<br>
					<strong> Destination Charges (Biaya di Negara Tujuan) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_cfs1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_cfs1" name="uang_tuj_cfs1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_cfs" class="form-control-label">CFS :</label>
							<input type="number" class="form-control" id="tuj_cfs" name="tuj_cfs">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_doc1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_doc1" name="uang_tuj_doc1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_doc" class="form-control-label">DOC :</label>
							<input type="number" class="form-control" id="tuj_doc" name="tuj_doc">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_agency_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_agency_fee1" name="uang_tuj_agency_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_agency_fee" class="form-control-label">AGENCY FEE :</label>
							<input type="number" class="form-control" id="tuj_agency_fee" name="tuj_agency_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_handling1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_handling1" name="uang_tuj_handling1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_handling" class="form-control-label">HANDLING :</label>
							<input type="number" class="form-control" id="tuj_handling" name="tuj_handling">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_do1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_do1" name="uang_tuj_do1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_do" class="form-control-label">PU & DO :</label>
							<input type="number" class="form-control" id="tuj_do" name="tuj_do">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_admin1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_admin1" name="uang_tuj_admin1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_admin" class="form-control-label">ADMIN :</label>
							<input type="number" class="form-control" id="tuj_admin" name="tuj_admin">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_devanning1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_devanning1" name="uang_tuj_devanning1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_devanning" class="form-control-label">DEVANNING :</label>
							<input type="number" class="form-control" id="tuj_devanning" name="tuj_devanning">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_fordwarding_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_fordwarding_fee1" name="uang_tuj_fordwarding_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_fordwarding_fee" class="form-control-label">FORWARDING FEE :</label>
							<input type="number" class="form-control" id="tuj_fordwarding_fee" name="tuj_fordwarding_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_mechanics1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_mechanics1" name="uang_tuj_mechanics1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_mechanics" class="form-control-label">MECHANICS :</label>
							<input type="number" class="form-control" id="tuj_mechanics" name="tuj_mechanics">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_other1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_other1" name="uang_tuj_other1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_other" class="form-control-label">OTHER :</label>
							<input type="number" class="form-control" id="tuj_other" name="tuj_other">
						</div>
					</div>
					<br>
					<strong> Custom Clearance Charges </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_clearance1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_clearance1" name="uang_cust_clearance1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_clearance" class="form-control-label">Custom Clearance :</label>
							<input type="number" class="form-control" id="cust_clearance" name="cust_clearance">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_red_line1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_red_line1" name="uang_cust_red_line1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_red_line" class="form-control-label">Red Line / Labor (if any) :</label>
							<input type="number" class="form-control" id="cust_red_line" name="cust_red_line">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_handling1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_handling1" name="uang_cust_handling1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_handling" class="form-control-label">Handling Charges :</label>
							<input type="number" class="form-control" id="cust_handling" name="cust_handling">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_admin_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_admin_fee1" name="uang_cust_admin_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_admin_fee" class="form-control-label">Admin Fee :</label>
							<input type="number" class="form-control" id="cust_admin_fee" name="cust_admin_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_pib_fee1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_pib_fee1" name="uang_cust_pib_fee1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_pib_fee" class="form-control-label">PIB Fee :</label>
							<input type="number" class="form-control" id="cust_pib_fee" name="cust_pib_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_transfer1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_transfer1" name="uang_cust_transfer1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_transfer" class="form-control-label">Transfer EDI :</label>
							<input type="number" class="form-control" id="cust_transfer" name="cust_transfer">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_storage1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_storage1" name="uang_cust_storage1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_storage" class="form-control-label">STORAGE + LIFT OF LIFT ON :</label>
							<input type="number" class="form-control" id="cust_storage" name="cust_storage">
						</div>
					</div>
					<br>
					<strong>Other Charges </strong>
					<!--<div class="form-group">
							<label for="oth_do" class="form-control-label">DO :</label>
							<input type="text" class="form-control" id="oth_do" name="oth_do">
					</div>
					<div class="form-group">
							<label for="oth_storage" class="form-control-label">STORAGE :</label>
							<input type="text" class="form-control" id="oth_storage" name="oth_storage">
					</div>-->
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_do1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_do1" name="uang_oth_do1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_do" class="form-control-label">DO :</label>
							<input type="number" class="form-control" id="oth_do" name="oth_do">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_storage1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_storage1" name="uang_oth_storage1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_storage" class="form-control-label">STORAGE :</label>
							<input type="number" class="form-control" id="oth_storage" name="oth_storage">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_trucking1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_trucking1" name="uang_oth_trucking1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_trucking" class="form-control-label">TRUCKING (Delivery Fee) (if any) :</label>
							<input type="number" class="form-control" id="oth_trucking" name="oth_trucking">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_handling1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_handling1" name="uang_oth_handling1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_handling" class="form-control-label">Handling Gudang :</label>
							<input type="number" class="form-control" id="oth_handling" name="oth_handling">
						</div>
					</div>
					<br>
					<strong>Insurance </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_ins_nilai1" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_ins_nilai1" name="uang_ins_nilai1" required>
								<option value="1">USD</option>
								<option value="2">IDR</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="ins_nilai" class="form-control-label">Nilai Asuransi :</label>
							<input type="number" class="form-control" id="ins_nilai" name="ins_nilai">
						</div>
					</div>
					<div class="form-group">
						<label for="ins_jenis1" class="form-control-label">Jenis Asuransi :</label>
						<select data-plugin-selectTwo class="form-control populate" id="ins_jenis1" name="ins_jenis1" required>
              <option value = "NON CLAIM">NON CLAIM</option>
              <option value = "CLAIM">CLAIM</option>
						</select>
					</div>
					<br>
					<strong>FORWARDER YANG DIPILIH </strong>
					<div class="form-group">
						<label for="dipilih1" class="form-control-label">Apakah Forwarder yang dipilih?</label>
						<select data-plugin-selectTwo class="form-control populate" id="dipilih1" name="dipilih1" required>
              <option value = "2">Tidak</option>
              <option value = "1">Ya</option>
						</select>
					</div>
					<div class="form-group">
							<label for="alasan" class="form-control-label">ALASAN :</label>
							<textarea type="text" class="form-control" id="alasan" name="alasan" cols="10" rows="3"></textarea>
					</div>
					<br>
					<strong>Link </strong>					
					<div class="form-group">
							<label for="link_invoice" class="form-control-label">Link Invoice :</label>
							<textarea type="text" class="form-control" id="link_invoice" name="link_invoice" cols="10" rows="3"></textarea>
					</div>					
					<div class="form-group">
							<label for="link_packing" class="form-control-label">Link Packing List :</label>
							<textarea type="text" class="form-control" id="link_packing" name="link_packing" cols="10" rows="3"></textarea>
					</div>					
					<div class="form-group">
							<label for="link_sph_for" class="form-control-label">Link SPH Forwarder :</label>
							<textarea type="text" class="form-control" id="link_sph_for" name="link_sph_for" cols="10" rows="3"></textarea>
					</div>					
					<div class="form-group">
							<label for="link_sph_ins" class="form-control-label">LINK SPH Asuransi :</label>
							<textarea type="text" class="form-control" id="link_sph_ins" name="link_sph_ins" cols="10" rows="3"></textarea>
					</div>			
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" name="id_app" id="id_app" value="<?= isset($data_aprv[0]->idGc) ? htmlspecialchars($data_aprv[0]->idGc, ENT_QUOTES, 'UTF-8') : '' ?>">
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>




<div id="main-modal-edit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Edit Perbandingan Expedisi Luar Negeri</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-edit', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group">
						<label for="id_forwarder" class="form-control-label"><strong> Nama Forwarder </strong> <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_forwarder" name="id_forwarder" required>
							<option value="">- Pilih Forwarder -</option>
							<?php
							foreach ($list_for as $row) {
								echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
							}
							?>
						</select>
					</div>
					
					<br>
					<strong> EXW CHARGES (Biaya di Negara Asal) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_thc" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_thc" name="uang_asal_thc" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_thc" class="form-control-label">THC :</label>
							<input type="number" class="form-control" id="asal_thc" name="asal_thc">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_bl_fee" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_bl_fee" name="uang_asal_bl_fee" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_bl_fee" class="form-control-label">B/L Fee :</label>
							<input type="number" class="form-control" id="asal_bl_fee" name="asal_bl_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_vgm" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_vgm" name="uang_asal_vgm" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_vgm" class="form-control-label">VGM :</label>
							<input type="number" class="form-control" id="asal_vgm" name="asal_vgm">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_agaency_fee" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_agaency_fee" name="uang_asal_agaency_fee" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_agaency_fee" class="form-control-label">Agency Fee / Admin Fee :</label>
							<input type="number" class="form-control" id="asal_agaency_fee" name="asal_agaency_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_handling_fee" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_handling_fee" name="uang_asal_handling_fee" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_handling_fee" class="form-control-label">Handling Fee :</label>
							<input type="number" class="form-control" id="asal_handling_fee" name="asal_handling_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_transportasi" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_transportasi" name="uang_asal_transportasi" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_transportasi" class="form-control-label">Transportation / Trucking :</label>
							<input type="number" class="form-control" id="asal_transportasi" name="asal_transportasi">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_loading" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_loading" name="uang_asal_loading" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_loading" class="form-control-label">Loading / Unloading :</label>
							<input type="number" class="form-control" id="asal_loading" name="asal_loading">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_custom" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_custom" name="uang_asal_custom" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_custom" class="form-control-label">Custom Clearance :</label>
							<input type="number" class="form-control" id="asal_custom" name="asal_custom">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_asal_pickup" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_asal_pickup" name="uang_asal_pickup" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="asal_pickup" class="form-control-label">Pick Up Fee :</label>
							<input type="number" class="form-control" id="asal_pickup" name="asal_pickup">
						</div>
					</div>
					<br>
					<strong> Freight (Biaya Ongkos Kirim) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_freight_ocean" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_freight_ocean" name="uang_freight_ocean" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="freight_ocean" class="form-control-label">Ocean Freight :</label>
							<input type="number" class="form-control" id="freight_ocean" name="freight_ocean">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_freight_air" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_freight_air" name="uang_freight_air" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="freight_air" class="form-control-label">Air Freight :</label>
							<input type="number" class="form-control" id="freight_air" name="freight_air">
						</div>
					</div>
					<br>
					<strong> Destination Charges (Biaya di Negara Tujuan) </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_cfs" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_cfs" name="uang_tuj_cfs" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_cfs" class="form-control-label">CFS :</label>
							<input type="number" class="form-control" id="tuj_cfs" name="tuj_cfs">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_doc" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_doc" name="uang_tuj_doc" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_doc" class="form-control-label">DOC :</label>
							<input type="number" class="form-control" id="tuj_doc" name="tuj_doc">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_agency_fee" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_agency_fee" name="uang_tuj_agency_fee" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_agency_fee" class="form-control-label">AGENCY FEE :</label>
							<input type="number" class="form-control" id="tuj_agency_fee" name="tuj_agency_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_handling" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_handling" name="uang_tuj_handling" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_handling" class="form-control-label">HANDLING :</label>
							<input type="number" class="form-control" id="tuj_handling" name="tuj_handling">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_do" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_do" name="uang_tuj_do" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_do" class="form-control-label">PU & DO :</label>
							<input type="number" class="form-control" id="tuj_do" name="tuj_do">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_admin" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_admin" name="uang_tuj_admin" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_admin" class="form-control-label">ADMIN :</label>
							<input type="number" class="form-control" id="tuj_admin" name="tuj_admin">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_devanning" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_devanning" name="uang_tuj_devanning" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_devanning" class="form-control-label">DEVANNING :</label>
							<input type="number" class="form-control" id="tuj_devanning" name="tuj_devanning">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_fordwarding_fee" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_fordwarding_fee" name="uang_tuj_fordwarding_fee" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_fordwarding_fee" class="form-control-label">FORWARDING FEE :</label>
							<input type="number" class="form-control" id="tuj_fordwarding_fee" name="tuj_fordwarding_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_mechanics" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_mechanics" name="uang_tuj_mechanics" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_mechanics" class="form-control-label">MECHANICS :</label>
							<input type="number" class="form-control" id="tuj_mechanics" name="tuj_mechanics">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_tuj_other" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_tuj_other" name="uang_tuj_other" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="tuj_other" class="form-control-label">OTHER :</label>
							<input type="number" class="form-control" id="tuj_other" name="tuj_other">
						</div>
					</div>
					<br>
					<strong> Custom Clearance Charges </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_clearance" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_clearance" name="uang_cust_clearance" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_clearance" class="form-control-label">Custom Clearance :</label>
							<input type="number" class="form-control" id="cust_clearance" name="cust_clearance">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_red_line" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_red_line" name="uang_cust_red_line" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_red_line" class="form-control-label">Red Line / Labor (if any) :</label>
							<input type="number" class="form-control" id="cust_red_line" name="cust_red_line">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_handling" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_handling" name="uang_cust_handling" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_handling" class="form-control-label">Handling Charges :</label>
							<input type="number" class="form-control" id="cust_handling" name="cust_handling">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_admin_fee" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_admin_fee" name="uang_cust_admin_fee" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_admin_fee" class="form-control-label">Admin Fee :</label>
							<input type="number" class="form-control" id="cust_admin_fee" name="cust_admin_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_pib_fee" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_pib_fee" name="uang_cust_pib_fee" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_pib_fee" class="form-control-label">PIB Fee :</label>
							<input type="number" class="form-control" id="cust_pib_fee" name="cust_pib_fee">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_transfer" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_transfer" name="uang_cust_transfer" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_transfer" class="form-control-label">Transfer EDI :</label>
							<input type="number" class="form-control" id="cust_transfer" name="cust_transfer">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_cust_storage" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_cust_storage" name="uang_cust_storage" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="cust_storage" class="form-control-label">STORAGE + LIFT OF LIFT ON :</label>
							<input type="number" class="form-control" id="cust_storage" name="cust_storage">
						</div>
					</div>
					<br>
					<strong>Other Charges </strong>
					<!--<div class="form-group">
							<label for="oth_do" class="form-control-label">DO :</label>
							<input type="text" class="form-control" id="oth_do" name="oth_do">
					</div>
					<div class="form-group">
							<label for="oth_storage" class="form-control-label">STORAGE :</label>
							<input type="text" class="form-control" id="oth_storage" name="oth_storage">
					</div>-->
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_do" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_do" name="uang_oth_do" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_do" class="form-control-label">DO :</label>
							<input type="number" class="form-control" id="oth_do" name="oth_do">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_storage" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_storage" name="uang_oth_storage" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_storage" class="form-control-label">STORAGE :</label>
							<input type="number" class="form-control" id="oth_storage" name="oth_storage">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_trucking" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_trucking" name="uang_oth_trucking" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_trucking" class="form-control-label">TRUCKING (Delivery Fee) (if any) :</label>
							<input type="number" class="form-control" id="oth_trucking" name="oth_trucking">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_oth_handling" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_oth_handling" name="uang_oth_handling" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="oth_handling" class="form-control-label">Handling Gudang :</label>
							<input type="number" class="form-control" id="oth_handling" name="oth_handling">
						</div>
					</div>
					<br>
					<strong>Insurance </strong>
					<div class="form-group row">
						<div class="col-md-3">
							<label for="uang_ins_nilai" class="form-control-label">MATA UANG :</label>
							<select data-plugin-selectTwo class="form-control populate" id="uang_ins_nilai" name="uang_ins_nilai" required>
								<option value="2">IDR</option>
								<option value="1">USD</option>
							</select>
						</div>
						<div class="col-md-9">
							<label for="ins_nilai" class="form-control-label">Nilai Asuransi :</label>
							<input type="number" class="form-control" id="ins_nilai" name="ins_nilai">
						</div>
					</div>
					<div class="form-group">
						<label for="ins_jenis" class="form-control-label">Jenis Asuransi :</label>
						<select data-plugin-selectTwo class="form-control populate" id="ins_jenis" name="ins_jenis" required>
              <option value = "NON CLAIM">NON CLAIM</option>
              <option value = "CLAIM">CLAIM</option>
						</select>
					</div>
					<br>
					<strong>FORWARDER YANG DIPILIH </strong>
					<div class="form-group">
						<label for="dipilih" class="form-control-label">Apakah Forwarder yang dipilih?</label>
						<select data-plugin-selectTwo class="form-control populate" id="dipilih" name="dipilih" required>
              <option value = "2">Tidak</option>
              <option value = "1">Ya</option>
						</select>
					</div>
					<div class="form-group">
							<label for="alasan" class="form-control-label">ALASAN :</label>
							<textarea type="text" class="form-control" id="alasan" name="alasan" cols="10" rows="3"></textarea>
					</div>
					<br>
					<strong>Link </strong>					
					<div class="form-group">
							<label for="link_invoice" class="form-control-label">Link Invoice :</label>
							<textarea type="text" class="form-control" id="link_invoice" name="link_invoice" cols="10" rows="3"></textarea>
					</div>					
					<div class="form-group">
							<label for="link_packing" class="form-control-label">Link Packing List :</label>
							<textarea type="text" class="form-control" id="link_packing" name="link_packing" cols="10" rows="3"></textarea>
					</div>					
					<div class="form-group">
							<label for="link_sph_for" class="form-control-label">Link SPH Forwarder :</label>
							<textarea type="text" class="form-control" id="link_sph_for" name="link_sph_for" cols="10" rows="3"></textarea>
					</div>					
					<div class="form-group">
							<label for="link_sph_ins" class="form-control-label">LINK SPH Asuransi :</label>
							<textarea type="text" class="form-control" id="link_sph_ins" name="link_sph_ins" cols="10" rows="3"></textarea>
					</div>			
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_app" name="id_app">
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>




<script>

	 document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {		
			var id 		= $('#id').val();
			
			Swal.fire({
				title: 'Ajukan Permintaan Approval Forwarder?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'forwarder/ajukanApp',
                        dataType: 'JSON',
                        data: {
									id		: id,
									csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }				
            })
        })
				
				


			$('#btn-show-add-form').click(function() {
				$('.form-control').val(null)
				$('.btn-isactive').remove()
				var object = 'forwarder'
				$('#main-modal #modal-form').attr('action', 'forwarder/addDetailForwarder')
				$('#main-modal').modal()
			})

			$(document).on('click', '.btn-edit', function() {
				$('.btn-isactive').remove()
				var object = 'forwarder'
				$('#main-modal-edit #modal-form-edit').attr('action', 'forwarder/updateDetailForwarder')
				$('#main-modal-edit').modal()

				var id = $(this).attr("data-id")
				fetch(object + '/editDetailForwarder/' + id)
					.then(function(resp) {
						return resp.json()
					})
					.then(function(data) {
						$('#main-modal-edit #id_forwarder').val(data[0].id_forwarder)
						$('#main-modal-edit #asal_thc').val(data[0].asal_thc);
						$('#main-modal-edit #asal_bl_fee').val(data[0].asal_bl_fee);
						$('#main-modal-edit #asal_vgm').val(data[0].asal_vgm);
						$('#main-modal-edit #asal_agaency_fee').val(data[0].asal_agaency_fee);
						$('#main-modal-edit #asal_handling_fee').val(data[0].asal_handling_fee);
						$('#main-modal-edit #asal_transportasi').val(data[0].asal_transportasi);
						$('#main-modal-edit #asal_loading').val(data[0].asal_loading);
						$('#main-modal-edit #asal_custom').val(data[0].asal_custom);
						$('#main-modal-edit #asal_pickup').val(data[0].asal_pickup);

						$('#main-modal-edit #freight_ocean').val(data[0].freight_ocean);
						$('#main-modal-edit #freight_air').val(data[0].freight_air);

						$('#main-modal-edit #tuj_cfs').val(data[0].tuj_cfs);
						$('#main-modal-edit #tuj_doc').val(data[0].tuj_doc);
						$('#main-modal-edit #tuj_agency_fee').val(data[0].tuj_agency_fee);
						$('#main-modal-edit #tuj_handling').val(data[0].tuj_handling);
						$('#main-modal-edit #tuj_do').val(data[0].tuj_do);
						$('#main-modal-edit #tuj_admin').val(data[0].tuj_admin);
						$('#main-modal-edit #tuj_devanning').val(data[0].tuj_devanning);
						$('#main-modal-edit #tuj_fordwarding_fee').val(data[0].tuj_fordwarding_fee);
						$('#main-modal-edit #tuj_mechanics').val(data[0].tuj_mechanics);
						$('#main-modal-edit #tuj_other').val(data[0].tuj_other);

						$('#main-modal-edit #cust_clearance').val(data[0].cust_clearance);
						$('#main-modal-edit #cust_red_line').val(data[0].cust_red_line);
						$('#main-modal-edit #cust_handling').val(data[0].cust_handling);
						$('#main-modal-edit #cust_admin_fee').val(data[0].cust_admin_fee);
						$('#main-modal-edit #cust_pib_fee').val(data[0].cust_pib_fee);
						$('#main-modal-edit #cust_transfer').val(data[0].cust_transfer);
						$('#main-modal-edit #cust_storage').val(data[0].cust_storage);

						$('#main-modal-edit #oth_do').val(data[0].oth_do);
						$('#main-modal-edit #oth_storage').val(data[0].oth_storage);
						$('#main-modal-edit #oth_trucking').val(data[0].oth_trucking);
						$('#main-modal-edit #oth_handling').val(data[0].oth_handling);

						$('#main-modal-edit #ins_nilai').val(data[0].ins_nilai);
						$('#main-modal-edit #ins_jenis').val(data[0].ins_jenis);

						$('#main-modal-edit #ppn').val(data[0].ppn);
						$('#main-modal-edit #dipilih').val(data[0].dipilih);
						$('#main-modal-edit #alasan').val(data[0].alasan);

						$('#main-modal-edit #link_invoice').val(data[0].link_invoice);
						$('#main-modal-edit #link_packing').val(data[0].link_packing);
						$('#main-modal-edit #link_sph_for').val(data[0].link_sph_for);
						$('#main-modal-edit #link_sph_ins').val(data[0].link_sph_ins);
						$('#main-modal-edit #id_app').val(data[0].id_app);

						$('#main-modal-edit #id_pelanggan').val(id)
					})
			})





			$(document).on('click', '.btn-duplikat', function (e) {
					e.preventDefault(); // cegah langsung redirect

					var link = $(this).data('href'); // ambil link tujuan

					Swal.fire({
							title: 'Duplikat Data Ini?',
							text: 'Data akan disalin dan dibuka di halaman baru.',
							icon: 'question',
							showCancelButton: true,
							confirmButtonText: 'Ya, Duplikat',
							cancelButtonText: 'Batal'
					}).then(function (result) {
							if (result.isConfirmed) {
									window.location.href = link;
							}
					});
			});



			$(document).on('click', '.btn-submit2', function() {
        	
        	var id = $('#id').val();
        	var catatan_finance = $('#catatan_finance').val();

        	Swal.fire({
        		title: 'Submit Link Invoice Final?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
        			// console.log('' + trf_komisi);
                    $.ajax({
                        method: 'POST',
                        url: 'forwarder/UpdateAppeksFinance/'+id,
                        dataType: 'JSON',
                        data: {
							catatan_finance : catatan_finance,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })


			





    })

	

    function goBack() {
        window.history.back();
    }
</script>