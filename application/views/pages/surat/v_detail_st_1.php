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
            <h2><font color='#000000' face='Times New Roman'>No : <?= $data_st[0]->kode ?></font></h2>
        </div>
		<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '70')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '23')){
					$ttd = 'ttd_2';
				}else if((sessPenggunaId() == '33')){
					$ttd = 'ttd_3';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
<!-- TABEL SURAT KUNJUNGAN GUDANG -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                        	<tr>
                        		<td colspan="4"> Perihal&nbsp;&nbsp;: <?= $data_st[0]->perihal ?> </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"> Dasar&nbsp;&nbsp;&nbsp;&nbsp;: <?= $data_st[0]->dasar ?> </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><center><u><b>Memerintahkan / Menugaskan</b></u></center></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Kepada :
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="7%" style="text-align:right;">1. &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td ><?php
                        		   	 $servername = "localhost";
									 $username = "visiyosi_root";
									 $password = "q%MDa{uYlcyv";
								     $dbname = "visiyosi_office";
								  $conn = mysqli_connect($servername, $username, $password, $dbname);
								if (!$conn) {
									die("Koneksi gagal: " . mysqli_connect_error());
								}
								$sql = "SELECT nama FROM pengguna WHERE pengguna_id = " . $data_st[0]->nama_kyw;
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
								<input type="text" hidden value="<?= $data_st[0]->nama_kyw ?>" name="nama" id="nama" ></td></td>
                        		<td width="30%"></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>NIP</td>
                        		<td><?= $data_st[0]->nip ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Untuk melaksanakan tugas </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><?= $data_st[0]->keterangan ?></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat tugas ini dibuat, agar dapat digunakan sebagaimana mestinya.</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; ">Pekanbaru, <?= date('d-m-Y', strtotime($data_st[0]->tgl_pengajuan)); ?></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; ">Disetujui Oleh,</td>
                        	</tr>
                        	<tr style="height:60px;">
                        		<?php
                        		$img_path 	= "uploads/file_karyawan/ttd/";
							// $ttdaju		= $img_path."ttd_".$data_pkk[0]->idPengaju.".png";
							// $ttd1 		= $img_path."ttd_notyet2.png";
							// $ttd2 		= $img_path."ttd_notyet2.png";
                        		$ttd3 		= $img_path."ttd_notyet2.png";

                        		if($data_st[0]->aju_ttd3 == '1'){
                        			$ttd3 = $img_path."ttd_33_cap1.png";
                        		}else if($data_st[0]->aju_ttd3 == '2'){
                        			$ttd3 = $img_path."ttd_not.png";
                        		}
                        		?>
                        		<td style="text-align:center; width:5%;"> </td>
                        		<td style="text-align:center; width:2.5%;"></td>
                        		<td style="text-align:center; width:23%;"></td>
                        		<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>

                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; "><b><u>Yollanda Pratiwi</u></b></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        </tbody>
			</table>
			<br>
<!-- PENUTUP SURAT KUNJUNGAN GUDANG -->
			
<br><br>
<div width="100%">
	<?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_st[0]->idPengaju) { ?>
		<?php if(sessPenggunaId() == '81') { ?>
			<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-st="<?=encrypt($data_st[0]->id)?>"> <i class="fas fa-check"></i> Submit </button>
		<?php } ?>
		<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-st="<?=encrypt($data_st[0]->id)?>"> <i class="fas fa-check"></i> Setujui </button>
		<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-st="<?=encrypt($data_st[0]->id)?>"> <i class="fas fa-times"></i> Tolak </button>				
	<?php } ?>
	<a href="surat/print_page/st/<?=$data_st[0]->id?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
	<?php if($data_st[0]->lampiran != "") { ?>
		<a href="<?=$data_st[0]->lampiran?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Pengaju</a>
	<?php } ?>
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
            const id_st = $(this).attr("id-st");
            	var nama			        = $('#nama').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Pengajuan Surat Tugas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/st/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_st : id_st,
                            nama : nama,
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
            const id_st = $(this).attr("id-st");
            Swal.fire({
				title: 'Tolak Pengajuan Surat Tugas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/st/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_st: id_st,
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
</script>\