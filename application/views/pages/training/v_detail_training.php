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
    </style>
</header>

<?php 
    // Ambil data mentah
    $raw_biaya = isset($data_training[0]->biaya) ? $data_training[0]->biaya : 0;
    // Bersihkan semua karakter selain angka (misal user input "Rp 500.000" jadi "500000")
    $clean_biaya = preg_replace('/[^0-9]/', '', (string)$raw_biaya);
    if(empty($clean_biaya)) { $clean_biaya = 0; }
    
    // Format menjadi Rupiah
    $biaya_final = 'Rp '. number_format((float)$clean_biaya, 0, ",", "."). ',-'; 
?>

<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
    <div class="card-body" style="background-color:#FFFFFF; padding:5%;">
        
        <div class="text-center">
            <h3><font color='#000000' face='Times New Roman'>SURAT PERMOHONAN TRAINING & DEVELOPMENT </font></h3>
        </div>
        
        <?php
            $id_kepaladivisi = $data_training[0]->id_kepaladivisi;
            $ttd = "ttd_1";
            if((sessPenggunaId() == '69' || sessPenggunaId() == '744')){
                $ttd = 'ttd_1';
            }else if((sessPenggunaId() == '33')){
                $ttd = 'ttd_2';
            }else if((sessPenggunaId() == $id_kepaladivisi)){
                $ttd = 'ttd_divisi';
            }
        ?>
        
        <?php $id = $data_training[0]->id ?>
        <input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
        <input type="hidden" name="id" id="id" value="<?= $data_training[0]->id ?>">
        
        <div class="table-responsive">
            <font color='#000000'>
                <table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                    <tbody>
                            <tr>
                        		<td colspan="6" style="text-align:center; font-size:20px; font-weight:bold;">
                        	    No : <?= $data_training[0]->kode_tr ?> <br><br>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4" style="text-align:center; font-size:12px; font-weight:bold;"></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i</font></td>
                        	</tr>
                        	<tr>
							<td colspan="4">
								Kepada Yth. <br> 
								<b>PIMPINAN PT. VISI YOSINDO MEDIKAL</b><br>
								Jl. Inpres No. 268 D – Pekanbaru <br><br>  
                        		</td>
                        	</tr>

							<tr>
							<td colspan="4">
							Dengan Hormat, <br>
							Saya yang bertanda tangan di bawah ini :

                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="8%">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?= $data_training[0]->namaPengaju ?></td>
                        	</tr>
                        	<tr>
                        		<td>Jabatan&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?= $data_training[0]->jabatan ?></td>
                        	</tr>
                        	<tr>
                        		<td>NPP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?= $data_training[0]->no_pegawai ?></td>
                        	</tr>
                        <tr><td colspan="5"><font color="white">i </font></td></tr>
                        <tr>
                            <td colspan="5" style="text-align: justify;">
                                Mengajukan permohonan Training & Development <b><?= $data_training[0]->nama_training ?></b>
                                di <b><?= $data_training[0]->penyelenggara ?></b> Mulai <b><?= date('d-M-Y',strtotime($data_training[0]->tanggal_mulai)) ?></b> 
                                sampai <b><?= date('d-M-Y',strtotime($data_training[0]->tanggal_selesai)) ?></b> dengan Biaya <b><?= $biaya_final ?></b>.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: justify;">
                                Manfaat / kegunaan dari Training & Development ini adalah <b><?= $data_training[0]->manfaat ?></b>.
                            </td>
                        </tr>
                        <tr><td colspan="5"><font color="white">i </font></td></tr>
                        <tr>
                            <td colspan="5">Demikian surat permohonan training & development ini saya ajukan, atas perhatian yang diberikan saya ucapkan terima kasih. <br><br><br><br></td>
                        </tr>

                        <?php if($data_training[0]->id_kepaladivisi != 0) { ?>
                            <tr>
                                <td colspan="6"></td>
                                <td style="text-align:center;">Pekanbaru, <?= date('d-M-Y',strtotime($data_training[0]->created_at)) ?></td>
                            </tr>
                            <tr style="height: 18px;">
                                <td style="text-align:center; width:22%;">Diajukan Oleh,</td>
                                <td style="text-align:center; width:2%;"></td>
                                <td style="text-align:center; width:2%;"></td>
                                <td style="text-align:center; width:12%;">Diverifikasi Oleh,</td>
                                <td style="text-align:center; width:2%;"></td>
                                <td style="text-align:center; width:2%;"></td>
                                <td style="text-align:center; width:22%;">Disetujui Oleh,</td>
                            </tr>
                            <tr style="height:60px;">
                                <?php
                                    $img_path     = "uploads/file_karyawan/ttd/";
                                    $ttdaju       = $img_path."ttd_".$data_training[0]->id_pengaju.".png";
                                    $ttd1         = $img_path."ttd_notyet2.png";
                                    $ttd2         = $img_path."ttd_notyet2.png";
                                    $ttddivisi    = $img_path."ttd_notyet2.png";
                                    
                                    if($data_training[0]->ttd_1 == '1'){ $ttd1 = $img_path."ttd_69.png"; }
                                    else if($data_training[0]->ttd_1 == '2'){ $ttd1 = $img_path."ttd_not.png"; }
                                    
                                    if($data_training[0]->ttd_2 == '1'){ $ttd2 = $img_path."ttd_33.png"; }
                                    else if($data_training[0]->ttd_2 == '2'){ $ttd2 = $img_path."ttd_not.png"; }
                                    
                                    if($data_training[0]->ttd_divisi == '1'){ $ttddivisi = $img_path."ttd_".$data_training[0]->id_kepaladivisi.".png"; }
                                    else if($data_training[0]->ttd_divisi == '2'){ $ttddivisi = $img_path."ttd_not.png"; }
                                ?>
                                <td style="text-align:center; width:18%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
                                <td style="text-align:center; width:6%;"></td>
                                <td style="text-align:center; width:18%;"><?php echo'<img src="'.$ttddivisi.'" height="70">';?></td>
                                <td style="text-align:center; width:6%;"></td>
                                <td style="text-align:center; width:18%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
                                <td style="text-align:center; width:6%;"></td>
                                <td style="text-align:center; width:18%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;"><?= $data_training[0]->namaPengaju ?><hr></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;"><?= $data_training[0]->namadivisi ?><hr></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;">Dian Melati Amelia<hr></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;">Yolanda Pratiwi<hr></td>
                            </tr>
                            <tr>
                                <td style="text-align:center; vertical-align:top;"><i><?= $data_training[0]->jabatan ?></i></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center; vertical-align:top;"><i><?= $data_training[0]->jabatandivisi ?></i></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center; vertical-align:top;"><i>HR & Legal Officer</i></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4"></td>
                                <td style="text-align:center;">Pekanbaru, <?= date('d-M-Y',strtotime($data_training[0]->created_at)) ?></td>
                            </tr>
                            <tr style="height: 18px;">
                                <td style="text-align:center; width:25.5%;">Diajukan Oleh,</td>
                                <td style="text-align:center; width:6%;"></td>
                                <td style="text-align:center; width:29%;">Diverifikasi Oleh,</td>
                                <td style="text-align:center; width:15%;"></td>
                                <td style="text-align:center; width:24%;">Disetujui Oleh,</td>
                            </tr>
                            <tr style="height:60px;">
                                <?php
                                    $img_path     = "uploads/file_karyawan/ttd/";
                                    $ttdaju       = $img_path."ttd_".$data_training[0]->id_pengaju.".png";
                                    $ttd1         = $img_path."ttd_notyet2.png";
                                    $ttd2         = $img_path."ttd_notyet2.png";
                                    
                                    if($data_training[0]->ttd_1 == '1'){ $ttd1 = $img_path."ttd_69.png"; }
                                    else if($data_training[0]->ttd_1 == '2'){ $ttd1 = $img_path."ttd_not.png"; }
                                    
                                    if($data_training[0]->ttd_2 == '1'){ $ttd2 = $img_path."ttd_33.png"; }
                                    else if($data_training[0]->ttd_2 == '2'){ $ttd2 = $img_path."ttd_not.png"; }
                                ?>
                                <td style="text-align:center; width:25%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
                                <td style="text-align:center; width:15%;"></td>
                                <td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
                                <td style="text-align:center; width:15%;"></td>
                                <td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;"><?= $data_training[0]->namaPengaju ?><hr></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;">Dian Melati Amelia<hr></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;">Yolanda Pratiwi<hr></td>
                            </tr>
                            <tr>
                                <td style="text-align:center; vertical-align:top;"><i><?= $data_training[0]->jabatan ?></i></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center; vertical-align:top;"><i>HR & Legal Officer</i></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center; vertical-align:top;"><i>General Manager</i></td>
                            </tr>
                        <?php } ?>

                        <tr><td colspan="4"><font color="white">i </font></td></tr>
                        <tr>
                            <td width="5%"><i>Tembusan :</i></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td><i>&nbsp;&nbsp;&nbsp;1. Direksi</i></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td><i>&nbsp;&nbsp;&nbsp;2. General Manager</i></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td><i>&nbsp;&nbsp;&nbsp;3. HR & Legal Officer</i></td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </font>
            <br><br>

            <div width="100%">
					<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '33' || sessPenggunaId() == '69' || sessPenggunaId() == '744' || sessPenggunaId() == $data_training[0]->id_kepaladivisi) { ?>
						<?php if (sessPenggunaId() == '81') { ?>
							<button type="button" class="btn btn-primary float-right btn-submit" style="margin-left:12px; margin-top:12px;" id-sp="<?= encrypt($data_training[0]->id) ?>"> <i class="fas fa-check"></i> Submit </button>
						<?php } ?>
						<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-sp="<?= encrypt($data_training[0]->id) ?>"> <i class="fas fa-check"></i> Setujui </button>
						<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-sp="<?= encrypt($data_training[0]->id) ?>"> <i class="fas fa-times"></i> Tolak </button>
					<?php } ?>
					<a href="training/print_page/tnd/<?= $data_training[0]->id ?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
					<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
					<?php if ($data_training[0]->link_pelatihan != "") { ?>
						<a href="<?= $data_training[0]->link_pelatihan ?>" target="blank" class="btn btn-primary float-center" style="margin-left:0px;"> <i class="fas fa-info"></i>&nbsp;&nbsp;Lampiran</a>
					<?php } ?>
					<br>
			</div>
            
        </div>
        <br>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var level_ttd = $('#level_ttd').val();      
        
        // Logic Setujui (Approval)
        $(document).on('click', '.btn-approval', function() {
            var id = $('#id').val();
            Swal.fire({
                title: 'Setujui Surat permohonan Training & Development?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'training/ttd_setujui/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id : id,
                            csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
                }
            })
        })
        
        // Logic Tolak (Denial)
        $(document).on('click', '.btn-denial', function() {
            var id = $('#id').val();
            Swal.fire({
                title: 'Tolak permohonan Training & Development?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'training/ttd_tolak/'+level_ttd,
                        dataType: 'JSON',
                        data: {
                            id : id,
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