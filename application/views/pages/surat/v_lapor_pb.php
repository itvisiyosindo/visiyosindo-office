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
		.table-input {
			border-collapse: collapse;
			width: 100%;
			font-family: 'Times New Roman', serif;
			font-size: 15px;
			color: #000;
		}
		.table-input th {
			background-color: #f4f6f8;
			color: #000;
			text-align: center;
			vertical-align: middle;
			padding: 8px;
			font-weight: bold;
		}
		.table-input tfoot th,
		.table-input tfoot td {
			background-color: #f4f6f8;
			color: #000;
		}
		.table-input td {
			padding: 4px;
			vertical-align: middle;
		}
		.form-control-table {
			width: 100%;
			height: 34px;
			padding: 4px 8px;
			font-size: 14px;
			border: 1px solid #ced4da;
			border-radius: 4px;
			background-color: #fff;
			transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
			box-sizing: border-box;
		}
		.form-control-table:focus {
			border-color: #5b9bd5;
			outline: 0;
			box-shadow: 0 0 0 0.2rem rgba(91, 155, 213, 0.25);
		}
		.input-lampiran {
			width: 85%;
			max-width: 600px;
			height: 32px;
			padding: 4px 10px;
			font-size: 14px;
			border: 1px solid #ced4da;
			border-radius: 4px;
			background-color: #fff;
			transition: all .2s;
		}
		.input-lampiran:focus {
			border-color: #5b9bd5;
			outline: 0;
			box-shadow: 0 0 0 0.2rem rgba(91, 155, 213, 0.25);
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
    <div class="table-responsive">
        <div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>No. Pengajuan : <?= $data_pb[0]->kodePB ?></font></h2>
        </div>
		<font color='#000000'>
			<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr >
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya memberikan laporan pertanggungjawaban biaya perjalanan dinas :</font>
					</td>
				</tr>
				<tr>
					<font color='#ffffff'>
						<?=
							$dt_pengajuan	= strtotime($data_pb[0]->tglPengajuan);
							$dt_pergi		= strtotime($data_pb[0]->tglPergi);
							$dt_pulang		= strtotime($data_pb[0]->tglKembali);
							
							$tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);
							$hari_pergi 	= date("D", $dt_pergi);
							$tgl_pergi 		= date("d", $dt_pergi)." - ".date("m", $dt_pergi)." - ".date("Y", $dt_pergi);
							$pergi 	        = date_create(date("d", $dt_pergi)."-".date("m", $dt_pergi)."-".date("Y", $dt_pergi));
							$pulang 	    = date_create(date("d", $dt_pulang)."-".date("m", $dt_pulang)."-".date("Y", $dt_pulang));
							$lama_hari 		= date_diff($pergi, $pulang);
							$lama_hari		= $lama_hari->format("%d") + 1;
							
							switch($hari_pergi){
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
					</font>
					<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->pengaju ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_pb[0]->jabatan ?></td>
					</tr>
					<tr>
						<td>Kota Tujuan </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->kota ?></td>
					</tr>
					<tr>
						<td>Keperluan </td>
						<td>:&nbsp;&nbsp; <?= $data_pb[0]->perihal ?></td>
					</tr>
					<tr>
						<td>Hari </td>
						<td>:&nbsp;&nbsp; <?= $hari_pergi ?></td>
					</tr>
					<tr>								
						<td>Tanggal </td>
						<td>:&nbsp;&nbsp; <?= $tgl_pergi ?></td>
					</tr>
					<tr>
						<td>Lama Perjalanan </td>
						<td>:&nbsp;&nbsp; <?= $lama_hari ?> hari</td>
					</tr>
					<tr>
						<td>Lampiran </td>
						<td>:&nbsp;&nbsp;<input id='lampiran' class="input-lampiran" type="text" placeholder="Klik Untuk Memasukkan Link Lampiran" required></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" class="table-input" border="1" width="100%">
                        <thead>
                            <tr>
                                <th width="20%"> Tanggal </th>
                                <th> Keterangan </th>
                                <th width="25%"> Nominal </th>
                            </tr>
                        </thead>
                        <tbody id="bodyt">
							<?php
								$itung = 1;
								for($x=1;$x<=12;$x++){
									$itung = $x;
							?>
								<tr>
                                    <td style="text-align:center">
										<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="<?= 'tgl_'.$x ?>" class="form-control-table text-center input-tgl" placeholder="dd-mm-yyyy" autocomplete="off">										
									</td>
                                    <td><input type="text" id="<?= 'keterangan_'.$x ?>" class="form-control-table input-ket" placeholder="Keterangan pengeluaran"></td>
                                    <td><input type="number" id="<?= 'nominal_'.$x ?>" class="form-control-table text-right input-nominal" placeholder="0" min="0" oninput="hitungTotal()"></td>
                                </tr>
							<?php } ?>
									
                        </tbody>
                        <tfoot>
                            <input type="hidden" id="itung" value="<?= $itung ?>">
                            <tr>
                                <th colspan="2" style="text-align:center; background-color:#f4f6f8; padding:8px; font-weight:bold;"> TOTAL </th>
                                <td id="total_display" style="text-align:right; background-color:#f4f6f8; padding:8px; font-weight:bold; font-size:15px;">Rp. 0,-</td>
                            </tr>                            
                        </tfoot>
			</table>
			<div class="mt-2 mb-3">
				<button type="button" class="btn btn-sm btn-success shadow-sm" onclick="myFunction1()"><i class="fas fa-plus-circle mr-1"></i> Tambah Baris</button>
				<button type="button" class="btn btn-sm btn-danger shadow-sm ml-1" onclick="myDeleteFunction1()"><i class="fas fa-minus-circle mr-1"></i> Hapus Baris</button>
			</div>
			<table border="0">
				<tr>
					<td width="7%">
					<td style="text-align:justify; text-justify:inter-word;">
						<font style="font-family:Times New Roman; font-size:15px;">
							Saya membuat laporan pertanggungjawaban penggunaan biaya dengan melampirkan Struk 
							/ Bon biaya terkait dan laporan perjalanan dinas dan akan diberikan kepada bagian keuangan.
						</font>
					</td>
					<td width="7%">
                </tr>
			</table>
            <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">							
							<span> <?= $data_pb[0]->kota_pengajuan ?>,&nbsp; </span>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="pengajuan" class="form-control-table d-inline-block text-center" style="width:160px;" placeholder="dd-mm-yyyy" required autocomplete="off">
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
						<td style="text-align:center; width:49%;" colspan="3">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:25.5%;" colspan="2">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
							$ttd2 		= $img_path."ttd_blank.png";
							$ttd3 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_pb[0]->nama_ttd ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dirangga Madali<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Yolanda Pratiwi<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_pb[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Head of Accounting and Tax</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Head of Finance & Corporate Planning</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Pimpinan Umum</i></td>
					</tr>
				</tbody>
			</table>
			<br><br>
		<div role="document">
            <button type="button" class="btn btn-success btn-save float-right btn-laporan" id-pb="<?=encrypt($data_pb[0]->idPB)?>" style="margin-left:12px;"> <i class="fas fa-check"></i> Submit Laporan </button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br><br>
    </div>
    </div>
</div>

<script>

	// FUNGSI HITUNG TOTAL OTOMATIS
	function hitungTotal() {
		let total = 0;
		$('.input-nominal').each(function() {
			let val = parseFloat($(this).val());
			if (!isNaN(val)) {
				total += val;
			}
		});
		$('#total_display').text('Rp. ' + total.toLocaleString('id-ID') + ',-');
	}

	// FUNGSI TAMBAH BARIS BARU
	var x1 = <?= $itung ?>;

	function myFunction1() {
		x1++;
		var newRow = `<tr>
			<td style="text-align:center">
				<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_${x1}" class="form-control-table text-center input-tgl" placeholder="dd-mm-yyyy" autocomplete="off">
			</td>
			<td><input type="text" id="keterangan_${x1}" class="form-control-table input-ket" placeholder="Keterangan pengeluaran"></td>
			<td><input type="number" id="nominal_${x1}" class="form-control-table text-right input-nominal" placeholder="0" min="0" oninput="hitungTotal()"></td>
		</tr>`;
		$('#bodyt').append(newRow);
		$('#itung').val(x1);

		if ($.fn.datepicker) {
			$('#tgl_' + x1).datepicker({
				orientation: "bottom",
				format: "dd-mm-yyyy",
				autoclose: true
			});
		}
	}

	// FUNGSI HAPUS BARIS TERAKHIR
	function myDeleteFunction1() {
		var rows = $('#bodyt tr');
		if (rows.length > 1) {
			rows.last().remove();
			x1--;
			$('#itung').val($('#bodyt tr').length);
			hitungTotal();
		} else {
			if (typeof Swal !== 'undefined') {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Minimal harus ada 1 baris pengeluaran.'
				});
			} else {
				alert('Minimal harus ada 1 baris pengeluaran.');
			}
		}
	}

    document.addEventListener('DOMContentLoaded', function() {             
		hitungTotal();             
        
		$(document).on('click', '.btn-laporan', function() {
			var pengajuan	= $('#pengajuan').val();
			var lampiran	= $('#lampiran').val();
			const id_pb = $(this).attr("id-pb");
			
			let itung_isi	= $('#itung').val();
			let ket			= [];
			let keterangan	= [];
			let tgl			= [];
			let tanggal		= [];
			let nom			= [];
			let nominal		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#keterangan_'+i).val() != ""){
					tgl[i] = $('#tgl_'+i).val();
					ket[i] = $('#keterangan_'+i).val();		  
					nom[i] = $('#nominal_'+i).val();
				}				
			}			
			let itung		= ket.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Submit Laporan Biaya perjalanan Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/laporan_update/pb',
                        dataType: 'JSON',
                        data: {
							id_pb	: id_pb,
							itung	: itung,
							pengajuan	: pengajuan,
							keterangan	: ket,
							tanggal	: tgl,
							nominal	: nom,
							lampiran: lampiran,
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