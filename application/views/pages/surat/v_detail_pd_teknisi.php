<header class="page-header">
	<h2><i class="icons fas fa-user"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>

	<style>
		hr {
			display: block;
			margin-top: 0em;
			margin-bottom: 0em;
			margin-left: auto;
			margin-right: auto;
			border-top: 1px solid black;
		}

		.mydiv {
			display: inline-block;
		}

		.mylabel {
			border: 0px solid blue;
			display: table-cell;
			width: 100%;
		}
	</style>
</header>
<?php
if ($data_pdt[0]->persetujuan_ga == 0) {
	$stat_surat = '<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	<div width="100%">
				 <div class="alert alert-info alert-dismissible">
<h4><i class="icon fa fa-square"></i> Baru di Ajukan! Belum Disetujui <b>General Affair</b></h4>';
} else if ($data_pdt[0]->persetujuan_ga == 1) {
	$stat_surat = '<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
		<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
		<div width="100%">
<div class="alert alert-success alert-dismissible">
	<h4><i class="icon fa fa-check"></i> Telah Disetujui <b>General Affair!</b></h4>';
} else {
	$stat_surat =  '<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	<div width="100%">
				 <div class="alert alert-danger alert-dismissible">
<h4><i class="icon fa fa-times"></i> Surat Ditolak<b>General Affair!</b></h4>';
}
echo $stat_surat;
?>
<?php $stat_surat; ?>
</div>
</div>
<div class="text-center">
	<h2>
		<font color='#000000' face='Times New Roman'>No : <?= $data_pdt[0]->kode ?> </font>
	</h2>
</div>
<?php
$ttd = "ttd_1";
if ((sessPenggunaId() == '70')) {
	$ttd = 'ttd_1';
} else if ((sessPenggunaId() == '23')) {
	$ttd = 'ttd_2';
} else if ((sessPenggunaId() == '15')) {
	$ttd = 'ttd_3';
} else if (isGa()) {
	$ttd = 'persetujuan_ga';
}
?>
<input type="hidden" id="level_ttd" value="<?= $ttd ?>">
<input type="hidden" id="idPB" value="<?= $data_pdt[0]->id_pd ?>">
<div class="table-responsive">
	<font color='#000000'>
		<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			<tr>
				<!-- <font color='#ffffff'>
						<?=


						$pergi		= date('d-m-Y', strtotime($data_pdt[0]->mulai));
						$pulang		= date('d-m-Y', strtotime($data_pdt[0]->akhir));

						switch ($hari_pergi) {
							case 'Sun':
								$hari_pergi = "Minggu";
								break;
							case 'Mon':
								$hari_pergi = "Senin";
								break;
							case 'Tue':
								$hari_pergi = "Selasa";
								break;
							case 'Wed':
								$hari_pergi = "Rabu";
								break;
							case 'Thu':
								$hari_pergi = "Kamis";
								break;
							case 'Fri':
								$hari_pergi = "Jumat";
								break;
							case 'Sat':
								$hari_pergi = "Sabtu";
								break;
						}
						?>
					</font> -->
				<td rowspan="9" width="7%"></td>
			<tr>
				<td width="30%">Nama </td>
				<td>:&nbsp;&nbsp; <?= $data_pdt[0]->pengaju ?> </td>
			</tr>
			<tr>
				<td>Wilayah Dinas (Provinsi) </td>
				<td>:&nbsp;&nbsp; <?= $data_pdt[0]->wilayah_dinas ?></td>
			</tr>
			<tr>
				<td>Tanggal </td>
				<td>:&nbsp;&nbsp; <?= $pergi ?> &nbsp; - &nbsp; <?= $pulang ?></td>
			</tr>
			<tr>
				<td>Lama Dinas </td>
				<td>:&nbsp;&nbsp; <?= $data_pdt[0]->lama_dinas ?></td>
			</tr>
			<tr>
				<td>Tujuan Dinas </td>
				<td>:&nbsp;&nbsp; <?= $data_pdt[0]->tujuan ?></td>
			</tr>
			<tr>
				<td>Transportasi </td>
				<td>:&nbsp;&nbsp; <?= $data_pdt[0]->transportasi ?></td>
			</tr>
			<tr>
				<td>Jenis Kendaraan </td>
				<td>:&nbsp;&nbsp; <?= $data_pdt[0]->jenis_kendaraan ?></td>
			</tr>
			<tr>
				<td>No. Polisi (Jika Kantor) </td>
				<td>:&nbsp;&nbsp; <?= $data_pdt[0]->no_polisi ?></td>
			</tr>
			<tr>
				<td>
					<font color="white">i </font>
				</td>
			</tr>
			</tr>
		</table>

		<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
			<thead>
				<tr>
					<th style="text-align:center" bgcolor="#C6DEFF" width="10%"> HARI</th>
					<th style="text-align:center" bgcolor="#C6DEFF" width="15%"> TANGGAL </th>
					<th style="text-align:center" bgcolor="#C6DEFF" width="25%"> NAMA CUSTOMER </th>
					<th style="text-align:center" bgcolor="#C6DEFF" width="25%"> KETERANGAN</th>
					<th style="text-align:center" bgcolor="#C6DEFF" width="5%"> KOTA/KABUPATEN </th>
					<th style="text-align:center" bgcolor="#C6DEFF" width="20%"> PIC </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$x = 1;
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
						<td class="hari">&nbsp;<?= $row->hari ?>&nbsp;</td>
						<td class="tgl">&nbsp;<?= date('d-m-Y', strtotime($row->tgl)); ?>&nbsp;</td>
						<td class="nama_customer">&nbsp;<?= $row->nama_customer ?>&nbsp;</td>
						<td class="tujuan_dinas">&nbsp;<?= $row->tujuan_dinas ?>&nbsp;</td>
						<td class="kota">&nbsp;<?= $row->kota ?>&nbsp;</td>
						<td class="pic">&nbsp;<?= $row->pic ?>&nbsp;</td>
					</tr>
				<?php } ?>
				<?php for ($kosong = $x; $kosong <= 12; $kosong++) { ?>
					<tr>
						<td>
							<font color="white">i </font>
						</td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		<table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
			<tbody>
				<tr style="height: 35px;">
					<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
						<?= $data_pdt[0]->kota_aju ?>, <?= date('d-m-Y', strtotime($data_pdt[0]->tgl_pengajuan)); ?>
					</td>
				</tr>
				<tr style="height: 18px;">
					<td style="text-align:center; width:25.5%;" colspan="2"></td>
					<td style="text-align:center; width:49%;" colspan="3"> </td>
					<td style="text-align:center; width:25.5%;" colspan="2"><b>Dibuat Oleh,</b></td>
				</tr>
				<tr style="height:60px;">
					<?php
					$img_path 	= "uploads/file_karyawan/ttd/";
					$ttdaju		= $img_path . "ttd_" . $data_pdt[0]->idPengaju . ".png";
					$ttd1 		= $img_path . "ttd_notyet2.png";
					$ttd2 		= $img_path . "ttd_notyet2.png";
					$ttd3 		= $img_path . "ttd_notyet2.png";
					?>
					<td style="text-align:center; width:23%;"></td>
					<td style="text-align:center; width:2.5%;"></td>
					<td style="text-align:center; width:23%;"> </td>
					<td style="text-align:center; width:2%;"></td>
					<td style="text-align:center; width:24%;"> </td>
					<td style="text-align:center; width:2.5%;"></td>
					<td style="text-align:center; width:23%;"><?php echo '<img src="' . $ttdaju . '" height="70">'; ?></td>
				</tr>
				<tr>
					<td style="text-align:center; "></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; "><?= $data_pdt[0]->nama_ttd ?>
						<hr>
						</hr>
					</td>
				</tr>
				<tr>
					<td style="text-align:center; vertical-align:top;"></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; vertical-align:top;"></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; vertical-align:top;"></td>
					<td style="text-align:center; "></td>
					<td style="text-align:center; vertical-align:top;"><i><?= $data_pdt[0]->jabatan ?></i></td>
				</tr>
			</tbody>
		</table>


		<br><br>
		<div width="100%">
			<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '58') { ?>
				<?php if ($data_pdt[0]->id_surat_dinas != '') { ?>
					<a href="surat/show/detail_surat/sd/<?= $data_pdt[0]->id_surat_dinas ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i></i> Cek Surat Dinas </a>
				<?php } else { ?>
					<?php if ($data_pdt[0]->persetujuan_ga != '') { ?>
						<button type="button" class="btn btn-primary float-right btn-sd" style="margin-left:12px; margin-top:12px;" id-pd="<?= encrypt($data_pdt[0]->id_pd) ?>"> <i class="fas fa-check"></i> Ajukan Surat Dinas </button>
					<?php } else { ?>
						<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-pd="<?= encrypt($data_pdt[0]->id_pd) ?>"> <i class="fas fa-check"></i> Setujui </button>
						<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-pd="<?= encrypt($data_pdt[0]->id_pd) ?>"> <i class="fas fa-times"></i> Tolak </button>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '58') { ?>
				<!-- <a href="surat/show/pengajuan/permintaan_sd/<?= $data_pdt[0]->id_pd ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i></i> Surat Dinas </a>		 -->
			<?php } ?>
			<?php if ($data_pdt[0]->lampiran != "") { ?>
				<a href="<?= $data_pdt[0]->lampiran ?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Pengaju</a>
			<?php } ?>
			<a href="surat/print_page/pd_teknisi/<?= $data_pdt[0]->id_pd ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
			<?php if (isAdmin()): ?>
				<button type="button" onclick="openAdminEditModal('pd_teknisi', '<?= encrypt($data_pdt[0]->id_pd) ?>')" class="btn btn-warning float-right font-weight-bold text-dark" style="margin-left:12px; margin-top:12px;">
					<i class="fas fa-user-shield mr-1"></i> Edit Data (Admin)
				</button>
			<?php endif; ?>
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
			<br>
		</div>
</div>
<br>
</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var level_ttd = $('#level_ttd').val();
		var idPB = $('#idPB').val();

		$(document).on('click', '.btn-approval', function() {
			const id_pd = $(this).attr("id-pd");

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Setujui Pengajuan Permintaan Dinas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pd_teknisi/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pd: id_pd,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
							location.reload(); // Add this line to refresh the page
						}
					})

				}

			})
		})

		$(document).on('click', '.btn-sd', function() {
			const id_pd = $(this).attr("id-pd");

			Swal.fire({
				//title: approval + ' absensi?',
				title: 'Apakah anda yakin mengajukan Surat Dinas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/add/sd_teknisi_auto/' + idPB,
						dataType: 'JSON',
						data: {
							id_pd: id_pd,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})

				}

			})
		})

		$(document).on('click', '.btn-save', function() {
			const id_pd = $(this).attr("id-pd")
			Swal.fire({
				title: 'Setujui Pengajuan Permintaan Dinas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pd_teknisi/1/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pd: id_pd,
							csrf_token: token
						},
						success: function(resp) {
							handleResponse(resp)
						}
					})
				}
			})
		})

		$(document).on('click', '.btn-denial', function() {
			const id_pd = $(this).attr("id-pd");
			Swal.fire({
				title: 'Tolak Pengajuan Permintaan Dinas?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						method: 'POST',
						url: 'surat/ttd_update/pd_teknisi/2/' + level_ttd,
						dataType: 'JSON',
						data: {
							id_pd: id_pd,
							csrf_token: token
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