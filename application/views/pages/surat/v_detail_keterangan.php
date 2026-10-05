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
		
		.mydiv {
			display:inline-block;
		}
		
		.mylabel {
			border:0px solid blue;
			display: table-cell;
			width: 100%;
		}
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
        <div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>SURAT KETERANGAN AKTIF BEKERJA</font></h2>
        </div>
		<?php
		   // $ttd = "ttd_1";
				if((sessPenggunaId() == '69' || sessPenggunaId() == '744')){
					$ttd = 'ttd_1';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
<!-- TABEL SURAT REKOM-->
		<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                        	<tr>
                        		<td colspan="4"> No : <?= $data_keterangan[0]->kode ?> </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"> Perihal &nbsp;&nbsp;: <?= $data_keterangan[0]->perihal ?>  </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        		<tr>
                        		<td colspan="4">
                        			Yang bertanda tangan dibawah ini :
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="7%" style="text-align:right;"></td>
                        		<td width="8%">Nama &nbsp;&nbsp;&nbsp;&nbsp;:</td>
                        		<td width="10%"><?= $masternotifikasi[0]->namad1 ?></td>
                        		<td width="30%"></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Jabatan &nbsp;:</td>
                        		<td>HR & Legal</td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Menerangkan dengan sesungguhnya bahwa yang bersangkutan dibawah ini :</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="8%"></td>
                        		<td >Nama &nbsp;&nbsp;&nbsp;&nbsp;:</td>
                                <td width="7%">
                                   &nbsp;<?php
								   	$servername = "localhost";
									   $username = "visiyosi_root";
									   $password = "q%MDa{uYlcyv";
								    $dbname = "visiyosi_office";
								    $conn = mysqli_connect($servername, $username, $password, $dbname);
								    if (!$conn) {
									die("Koneksi gagal: " . mysqli_connect_error());
								    }
								    $sql = "SELECT nama FROM pengguna WHERE pengguna_id = " . $data_keterangan[0]->nama_kyw;
								    $result = mysqli_query($conn, $sql);
								    if (mysqli_num_rows($result) > 0) {
									while ($row = mysqli_fetch_assoc($result)) {
										echo "" . $row["nama"] ."<br>";
									}
								    } else {
									echo "0 hasil";
								}
								mysqli_close($conn);
								?>
								<input type="text" hidden value="<?= $data_rekom[0]->nama_kyw ?>" name="nama_kyw" id="nama_kyw" >
                                </td>
                        		<td width="30%"></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Jabatan &nbsp;:</td>
                        		<td><?= $data_keterangan[0]->jabatan_pegawai ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><b>Adalah benar karyawan di Perusahaan PT. Visi Yosindo Medikal terhitung sejak &nbsp;<?= $data_keterangan[0]->tgl_masuk ?>
                        		dan hingga saat ini masih aktif.</b></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Demikian surat keterangan ini dibuat sebagai dokumen untuk keperluan pribadi.
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	
                        </tbody>
					</table>
				<table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr>
						<td height="20px"></td>
					</tr>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">
							 Pekanbaru
							<span> ,&nbsp; </span>
							<?= $data_keterangan[0]->tgl_pengajuan ?>
						</td>
					</tr>
				<tr style="height:60px;">
						<?php
                        	//$img_path 	= "uploads/file_karyawan/ttd/ttd_".$masternotifikasi[0]->disetujui1."_cap.png";
							// $ttdaju		= $img_path."ttd_".$data_pkk[0]->idPengaju.".png";
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttd1 		= $img_path."ttd_notyet2.png";
				// 			$ttd2 		= $img_path."ttd_notyet2.png";
                        		// $ttd3 		= $img_path."ttd_notyet2.png";

                        		if($data_keterangan[0]->aju_ttd1 == '1'){
                        			$ttd1 = $img_path."ttd_744_cap.png";
                        		}else if($data_keterangan[0]->aju_ttd1 == '2'){
                        			$ttd1 = $img_path."ttd_not.png";
                        		}
                        		?>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%; text-align:right;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; text-align:right;"><?= $masternotifikasi[0]->namad1 ?></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; text-align:right;"><i><?= $masternotifikasi[0]->jabatand1 ?></i></td>
					</tr>
				</tbody>
			</table>
					<br>
           
<!-- PENUTUP SURAT KETERANGAN -->
			
<br><br>
<div width="100%">
	<?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_keterangan[0]->idPengaju) { ?>
		<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-st="<?=encrypt($data_keterangan[0]->id)?>"> <i class="fas fa-check"></i> Setujui </button>
		<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-st="<?=encrypt($data_keterangan[0]->id)?>"> <i class="fas fa-times"></i> Tolak </button>				
	<?php } ?>
	<a href="surat/print_page/keterangan/<?=$data_keterangan[0]->id?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
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
        
		$(document).on('click', '.btn-approval', function() {
            const id_keterangan = $(this).attr("id-st");						
						var nama_kyw	= $('#nama_kyw').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Pengajuan Surat Keterangan Aktif Bekerja?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/keterangan/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_keterangan : id_keterangan,
							nama_kyw : nama_kyw,
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
            const id_keterangan = $(this).attr("id-keterangan");
            Swal.fire({
				title: 'Tolak Pengajuan Surat Keterangan Aktif Bekerja?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/keterangan/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_keterangan: id_keterangan,
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