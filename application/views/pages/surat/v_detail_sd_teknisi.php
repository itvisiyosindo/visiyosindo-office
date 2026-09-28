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
       <!--  <div class="text-right">
            <?php
                if($data_sdt[0]->persetujuan_ga == 0){
                    echo '<span class="btn btn-warning">Baru Diajukan</span>';
                }else if($data_sdt[0]->persetujuan_ga == 1){
                    echo '<span class="btn btn-success">Disetujui GA</span>';
                }else if($data_sdt[0]->persetujuan_ga == 2){
                    echo '<span class="btn btn-danger">Ditolak GA</span>';
                }
            ?>
        </div> -->
        <div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>No : <?= $data_sdt[0]->kode ?></font></h2>
        </div>
		<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '70')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '23')){
					$ttd = 'ttd_2';
				}else if((sessPenggunaId() == '15')){
					$ttd = 'ttd_3';
				}else if(isGa()){
					$ttd = 'persetujuan_ga';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
<!-- TABEL SURAT KUNJUNGAN GUDANG -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                        	<tr>
                        		<td colspan="4"> Perihal : Dinas </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Sehubungan dengan adanya pekerjaan
                        			<?= $data_sdt[0]->perihal ?> di <?= $data_sdt[0]->lokasi_dinas ?> pada Tanggal <?= date('d-m-Y', strtotime($data_sdt[0]->tgl_dinas)); ?>, maka dengan ini kami mengutus karyawan kami
                        			sebagai berikut :
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="5%" style="text-align:right;">1. &nbsp;</td>
                        		<td>Nama</td>
                        		<td>:&nbsp;<?= $data_sdt[0]->nama_karyawan ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Jabatan</td>
                        		<td>:&nbsp;<?= $data_sdt[0]->jabatan_karyawan ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>NIK</td>
                        		<td>:&nbsp;<?= $data_sdt[0]->nik_karyawan ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Untuk melaksanakan tugas pada pekerjaan tersebut. Kami berharap karyawan kami dapat
                        		menyelesaikan tugas tersebut dengan baik.</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Surat pengantar dinas ini berlaku hingga tugas selesai dan yang bersangkutan telah memberikan
                        			laporan kembali ke Perusahaan.
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat pengantar dinas ini dibuat, agar dapat digunakan sebagaimana mestinya. Atas
                        		perhatian dan kerjasamanya diucapkan terimakasih.</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td></td>
                        		<td></td>
                        		<td style="text-align:center; ">Pekanbaru, <?= date('d-m-Y', strtotime($data_sdt[0]->tgl_pengajuan)); ?></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td></td>
                        		<td></td>
                        		<td style="text-align:center; ">PT. Visi Yosindo Medkal</td>
                        	</tr>
                        	<tr style="height:60px;">
                        		<?php
                        		$img_path 	= "uploads/file_karyawan/ttd/";
							// $ttdaju		= $img_path."ttd_".$data_pkk[0]->idPengaju.".png";
							// $ttd1 		= $img_path."ttd_notyet2.png";
							// $ttd2 		= $img_path."ttd_notyet2.png";
                        		$ttd3 		= $img_path."ttd_notyet2.png";

                        		if($data_sdt[0]->aju_ttd3 == '1'){
                        			$ttd3 = $img_path."ttd_15_cap.png";
                        		}else if($data_sdt[0]->aju_ttd3 == '2'){
                        			$ttd3 = $img_path."ttd_not.png";
                        		}
                        		?>
                        		<td style="text-align:center; width:5%;"> </td>
                        		<td style="text-align:center; width:2.5%;"></td>
                        		<td style="text-align:center; width:23%;"></td>
                        		<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>

                        	</tr>
                        	<tr>
                        		<td width="5%" style="text-align:center; "></td>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; "><b><u>Kardonal</u></b></td>
                        	</tr>
                        	<tr>
                        		<td width="5%" style="text-align:center; "></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; vertical-align:top;"><i>Pimpinan Umum</i></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"><i>Tembusan :</i></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        	</tr>
                        	<tr>
                        		<td width="5%" style="text-align:right; "><i>1. </i></td>
                        		<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
                        		<td style="text-align:center; "></td>
                        		<td style="text-align:center; vertical-align:top;"></td>
                        	</tr>
                        </tbody>
			</table>
			<br>
<!-- PENUTUP SURAT KUNJUNGAN GUDANG -->
			
<br><br>
<div width="100%">
	<?php if (sessPenggunaId() == '1' || sessPenggunaId() != $data_sdt[0]->idPengaju) { ?>
		<?php if(sessPenggunaId() == '81') { ?>
			<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-sd="<?=encrypt($data_sdt[0]->id)?>"> <i class="fas fa-check"></i> Submit </button>
		<?php } ?>
		<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-sd="<?=encrypt($data_sdt[0]->id)?>"> <i class="fas fa-check"></i> Setujui </button>
		<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-sd="<?=encrypt($data_sdt[0]->id)?>"> <i class="fas fa-times"></i> Tolak </button>				
	<?php } ?>
	<a href="surat/print_page/sd_teknisi/<?=$data_sdt[0]->id?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
	<?php if($data_sdt[0]->lampiran != "") { ?>
		<a href="<?=$data_sdt[0]->lampiran?>" class="btn btn-primary float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran Pengaju</a>
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
            const id_sd = $(this).attr("id-sd");
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Surat Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/sd_teknisi/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_sd : id_sd,
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
            const id_sd = $(this).attr("id-sd");
            Swal.fire({
				title: 'Tolak Surat Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/sd/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id_sd: id_sd,
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