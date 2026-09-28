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
			//font-family:Garamond;
			//background:#363;
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
						<td>:&nbsp;&nbsp;<input id='lampiran' type="text" placeholder='Klik Untuk Memasukkan Link Lampiran' required></td></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="20%"> Tanggal </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Keterangan </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="25%"> Nominal </th>
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
										<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="<?= 'tgl_'.$x ?>" Style="width:100%; text-align:center"  required>										
									</td>
                                    <td><input type="text" id="<?= 'keterangan_'.$x ?>" Style="width:100%" required></td>
                                    <td><input type="number" id="<?= 'nominal_'.$x ?>" Style="width:100%; text-align:right" required></td>
                                </tr>
							<?php } ?>
									
                        </tbody>
                        <tfoot>
                            <input type="hidden" id="itung" value="<?= $itung ?>">
                            <tr>
                                <th bgcolor="#b7d5ac" colspan="2" style="text-align:center"> TOTAL </th>
                                <td bgcolor="#b7d5ac" style="text-align:right"> ,- </td>
                            </tr>                            
                        </tfoot>
			</table>
			<br>
			<button type="button" class="btn btn-success" onclick="myFunction1()"><i class="fa-plus-circle"></i>Tambah Baris</button>
			<!-- <button type="button" class="btn btn-primary" onclick="addRow('tbody2')">Tambah Baris</button> -->
			<button type="button" class="btn btn-danger" onclick="myDeleteFunction1()">	Hapus Baris</button>
			<br>
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
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="pengajuan" Style="width:15%; text-align:left" placeholder="Pengajuan" required>
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
						<td style="text-align:center; ">Amtisari DE Putri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Yolanda Pratiwi<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_pb[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Finance Staff</i></td>
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

	// FUNGSI TAMBAH BARIS BARU
	var x1 = <?= $itung ?>;
	x1 = parseInt(x1)+1;

		function myFunction1() {
			
		  var urut 	= x1 - 0;
		  var table = document.getElementById("kt_table_1");
		  // var ambil	= document.getElementById("myTable").rows[10].cells;
		  var row 	= table.insertRow(x1);
		  x1 = x1 + 1;
		  var cell1 = row.insertCell(0);
		  var cell2 = row.insertCell(1);
		  var cell3 = row.insertCell(2);

		  
		  //x1 = x1+1;
		  // cell2.innerHTML = ""+ambil[1].innerHTML;
		  //cell1.innerHTML = "<input type='text' data-plugin-datepicker data-plugin-options='{\"orientation\":\"bottom\", \"format\":\"dd-mm-yyyy\"}' id="<?= 'tgl_'.$x ?>" Style='width:100%; text-align:center'  required>'
		  //cell1.innerHTML = '<input type="date"  id="tgl'+urut+'"   Style="width:100%">';
		  //"<li><a href='#tabs-" + i + "'>" + tabnames[i] + "</a></li>";
		  cell1.innerHTML = '<input type="text" data-plugin-datepicker="" data-plugin-options="{&quot;orientation&quot;:&quot;bottom&quot;, &quot;format&quot;:&quot;dd-mm-yyyy&quot;}" id="tgl_'+(urut)+'" style="width:100%; text-align:center" required="">';
		  cell2.innerHTML = "<input type='text' id='keterangan_"+urut+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='number' id='nominal_"+urut+"' Style='width:100%; text-align:right'>";
		  document.getElementById('itung').value = urut;
		}
	
		function myDeleteFunction1() {
			x = x-1;		
			document.getElementById("myTable1").deleteRow(x);			
		}
    document.addEventListener('DOMContentLoaded', function() {             
        
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