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
		
		.myinput{
			width:100%;
			height:auto;
			border:0px solid #000;
			border-radius:0px; 
			-moz-border-radius:8px;
			margin-left:0px;
			text-align:center;
			height: 100px;
            line-height: 100px;
            font-weight:bold;
		}
		
		.myselect{
			width:97%;
			height:auto;
			border:0px solid #000; 
			border-radius:4px; 
			-moz-border-radius:8px;
			margin:0px;
			//background:#b7d5ac;
		}
		
		.mydiv br {
			display: none;
		}
		
		.mydiv p {
			padding: 0;
			margin: 0;
		}
		
		*::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
          color: red;
          opacity: 1; /* Firefox */
        }
    
	</style>

</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
       <!--  <div class="text-right">
            <?php
                if($data_skd[0]->ttd_persetujuan == 0){
                    echo '<span class="btn btn-warning">Baru Diajukan</span>';
                }else if($data_skd[0]->ttd_persetujuan == 1){
                    echo '<span class="btn btn-success">Disetujui GA</span>';
                }else if($data_skd[0]->ttd_persetujuan == 2){
                    echo '<span class="btn btn-danger">Ditolak GA</span>';
                }
            ?>
        </div> -->
        <div class="text-center">
            <h2></h2>
        </div>
			<?php
		    $ttd = "ttd_1";
				if((sessPenggunaId() == '70')){
					$ttd = 'ttd_1';
				}else if((sessPenggunaId() == '21')){
					$ttd = 'ttd_2';
				}else if((sessPenggunaId() == '54')){
					$ttd = 'ttd_persetujuan';
				}
		?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
<!-- TABEL SURAT KUNJUNGAN GUDANG -->
			 <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0"
                    width="100%">
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align:center; font-size:26px; font-weight:bold;">
                                SURAT KEPUTUSAN DIREKSI
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center; font-size:18px; font-weight:bold;">
                            <?= $data_skd[0]->kode ?>
                </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="white">i</font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                Perihal <?= $data_skd[0]->perihal ?><BR><font color="WHITE">1</font>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                             Berdasarkan <?= $data_skd[0]->isi1 ?></td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <font color="black">maka dengan ini diputuskan bahwa :</font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            &nbsp;</td>
                        </tr>
                         <tr>
                            <td colspan="4"><?= $data_skd[0]->isi2 ?></td>
                        </tr>
                        <tr>
                            <td colspan="4">

                            </td>
                        </tr>
                       
                        <tr>
                            <td colspan="4">Demikian surat keputusan ini dibuat, apabila kemudian hari terdapat kekeliruan dan/atau kesalahan dengan penerbitan surat keputusan ini, maka perusahaan akan melakukan penyesuaian ulang sebagaimana mestinya.</td>
                        </tr>
                        <tr>  
                            <td colspan="4">
                                <font color="white">i </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" width="70%"></td>
                            <td style="text-align:left; ">Ditetapkan di : Pekanbaru<br>Pada Tanggal :<?= $data_skd[0]->tgl_pengajuan ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align:left; ">PT. Visi Yosindo Medkal</td>
                        </tr>
                       <tr style="height:60px;">
                        		<?php
                                $img_path = "uploads/file_karyawan/ttd/";
                                // $ttdaju		= $img_path."ttd_".$data_pkk[0]->idPengaju.".png";
                                // $ttd1 		= $img_path."ttd_notyet2.png";
                                // $ttd2 		= $img_path."ttd_notyet2.png";
                                $ttd3 = $img_path . "ttd_notyet2.png";

                                if ($data_skd[0]->ttd_persetujuan == '1') {
                                    $ttd3 = $img_path . "ttd_54.png";
                                } else if ($data_skd[0]->ttd_persetujuan == '2') {
                                    $ttd3 = $img_path . "ttd_not.png";
                                }
                                ?>
                                    <td style="text-align:center; width:5%;"> </td>
                                    <td style="text-align:center; width:2.5%;"></td>
                                    <td style="text-align:center; width:23%;"></td>
                                    <td style="text-align:left; width:24%;"><?php echo '<img src="' . $ttd3 . '" height="70">'; ?></td>
            
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="text-align:LEFT; "><b><u>Bob Ariyos</u></b></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="text-align:LEFT; vertical-align:top;"><i>Director</i></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <font color="white">i </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><i>Tembusan :</i></td>
                    </tr>
                    <tr>
                        <td style="text-align:right; "><i></i></td>
                        <!-- <td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Direksi</i></td> -->
                    </tr>
                    <tr>
                        <td style="text-align:right; "><i></i></td>
                        <!-- <td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Pimpinan Umum</i></td> -->
                    </tr>
                    <tr>
                        <td style="text-align:right; "><i></i></td>
                        <!-- <td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Finance Staff</i></td> -->
                    </tr>
                    <tr>
                        <td colspan="4">
                            <font color="white">i </font>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <!-- PENUTUP SURAT PENGANTAR DINAS -->
            <br><br>
            </table>
            <br>
<!-- PENUTUP SURAT KUNJUNGAN GUDANG -->
            
<br><br>
<div width="100%">
    <?php if (sessPenggunaId() == '1' || sessPenggunaId() == '54' || sessPenggunaId() != $data_skd[0]->id) { ?>
		<?php if(sessPenggunaId() == '81') { ?>
			<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-skd="<?=encrypt($data_skd[0]->id)?>"> <i class="fas fa-check"></i> Submit </button>
		<?php } ?>
		<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-skd="<?=encrypt($data_skd[0]->id)?>"> <i class="fas fa-check"></i> Setujui </button>
			<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-skd="<?=encrypt($data_skd[0]->id)?>"> <i class="fas fa-times"></i> Tolak </button>				
	<?php } ?>
	<a href="surat/print_page/skd/<?=$data_skd[0]->id?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
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
            const id_skd = $(this).attr("id-skd");
			var nama	= $('#nama').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Surat Keputusan Direksi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/skd/1/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id : id_skd,
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
            const id_skd = $(this).attr("id-skd");
            Swal.fire({
				title: 'Tolak Surat Peringatan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/skd/2/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id: id_skd,
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