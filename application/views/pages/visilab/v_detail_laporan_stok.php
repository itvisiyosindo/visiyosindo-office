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
    	<div class="text-right">
        <div class="text-center">
            <h2><font color='#000000' face='Times New Roman'>No : <?= $data_stok[0]->kode ?> </font></h2>
        </div>
			</div>
			<br>

			<?php
			$terima = $data_stok[0]->id_setujui;

		    $ttd = "ttd_1";
				if((sessPenggunaId() == $terima)){
					$ttd = 'ttd_1';
				}
			?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<?php $id = $data_stok[0]->idGc ?> 
		<input type="hidden" name="id" id="id" value="<?= $data_stok[0]->idGc ?>">

			
     <?= form_open('visilab/updateStok', array('id' => 'main-form', 'autocomplete' => 'off')); ?>


		<input type="hidden" id="idPB" value="<?= $data_stok[0]->idGc ?>"> 
		<div class="table-responsive">
		<font color='#000000'>
			<table style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					
					<td rowspan="9" width="7%"></td>
					<tr>
						<td width="12%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_stok[0]->pengaju ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_stok[0]->jabatan_visilab ?></td>
					</tr>
					<tr>
						<td>Tujuan Penggunaan </td>
						<td>:&nbsp;&nbsp;  <?= $data_stok[0]->penggunaan ?></td>
					</tr>
					<tr>
						<?php 
								if ($data_stok[0]->link_lampiran != ""){
									$link = '<a href="'.$data_stok[0]->link_lampiran.'" target="blank"><i class="fas fa-link"></i> Link</a>'; 
								}else{
									$link = '-';
								}?>
						<td>Link Lampiran </td>
						<td>:&nbsp;&nbsp; <?= $link ?></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td> 
					</tr>
				</tr>
			</table>
		
			<div class="card-body">
            <div class="table-responsive">
                
                <table class="table table-striped table-sm table-bordered table-hover" id="table_detail">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th style="text-align: center;"> Nama Alat</th>
                            <th style="text-align: center;" width="20%"> No Seri </th>
                            <th style="text-align: center;" width="17%"> Waktu Keluar </th>
														<?php if ($data_stok[0]->ttd_1 != ""){ ?>
                            <th style="text-align: center;" width="17%"> Waktu Masuk</th>
														<?php } ?>
                            <th style="text-align: center;" width="25%"> Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php $i = 1 ?>
                            <?php foreach ($detail_stok as $row) { ?>
                                <tr class="value_row">
                                    <td style="text-align: center;"><input type="hidden" name="id_sodetail[]" value="<?= encrypt($row->id) ?>"><?= $i++ ?></td>
                                    <td><?= $row->nama ?></td>
                                    <td style="text-align: center;"> <?= $row->serial_number ?></td>
                                    <td style="text-align: center;"> <?=  date('d-m-Y',strtotime($row->waktu_keluar)) ?></td>
																		<?php if ($data_stok[0]->ttd_1 != ""){ ?>
																		<?php if ($row->waktu_masuk != ""){ ?>
                                    <td style="text-align: center;"><?=  date('d-m-Y',strtotime($row->waktu_masuk)) ?></td>
																		<?php }else { ?>
                                    <td style="text-align: center;"><input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' name="waktu_masuk[]" Style="width:100%; text-align:center"  required></td>
																		<?php } ?>
																		<?php } ?>
                                    <td style="text-align: center;"> <?= $row->ket ?></td>
                                    
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>


            </div>

                <div style="text-align: right;">
								<?php if ($data_stok[0]->status == "2"){ ?>
                    <?php if((sessPenggunaId() == $data_stok[0]->idPengaju || sessPenggunaId() == '1')){ ?>
                        <button type="button" class="btn btn-success btn-save">Simpan</button>
                    <?php } ?>
								<?php } ?>
                </div>

        </div>
			<br> <br>
      <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							<?= $data_stok[0]->kota_aju ?>, <?= date('d-m-Y', strtotime($data_stok[0]->created_at));?>
						</td>
					</tr>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:30%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:40%;" ></td>
						<td style="text-align:center; width:30%;" >Disetujui Oleh,</td>
					</tr>
					

					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_stok[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							
							if($data_stok[0]->ttd_1 == '1'){
								$ttd1		= $img_path."ttd_".$data_stok[0]->id_setujui.".png";
							}else if($data_stok[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:30%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:40%;"></td>
						<td style="text-align:center; width:30%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_stok[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_stok[0]->setujui ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_stok[0]->jabatan_visilab ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_stok[0]->jabatan2_visilab ?></i></td>
					</tr>
				</tbody>
			</table>
			
			
			<br><br>
				<div width="100%">
					<?php if (sessPenggunaId() == '1'|| sessPenggunaId() == $data_stok[0]->id_setujui) { ?>
						<?php if($data_stok[0]->status == '3'){ ?>	
													
							<button type="button" class="btn btn-success float-right btn-approval1" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_stok[0]->idGc)?>"> <i class="fas fa-check"></i> Setujui </button>
							<button type="button" class="btn btn-secondary float-right btn-denial1" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_stok[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>		
							
						<?php }else if($data_stok[0]->status == '1'){ ?>

							<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_stok[0]->idGc)?>"> <i class="fas fa-check"></i> Setujui </button>
							<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-Sijk="<?=encrypt($data_stok[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>		
						<?php } ?>
					<?php } ?>
						<a href="visilab/print_page/laporan_stok/<?=$data_stok[0]->idGc?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-right" style="margin-top:12px;" data-dismiss="modal">Kembali</button>
            <br>
        </div>
        </div>
		<br>

		<?= form_close(); ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
			var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Setujui Permintaan Pengeluaran Stok Alat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_setujui/stok/1/'+level_ttd,
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
        
		
		$(document).on('click', '.btn-denial', function() {
			var id = $('#id').val();
			
            Swal.fire({
				title: 'Tolak Permintaan Pengeluaran Stok Alat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_tolak/stok/1/'+level_ttd,
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


				$(document).on('click', '.btn-approval1', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Setujui Permintaan Pengembalian Stok Alat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_setujui/stok/2/'+level_ttd,
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
        
		
		$(document).on('click', '.btn-denial1', function() {
			var id = $('#id').val();
			
            Swal.fire({
				title: 'Tolak Permintaan Pengembalian Stok Alat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'visilab/ttd_tolak/stok/2/'+level_ttd,
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