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
                if($data_pk[0]->status == 0){
                    echo '<span class="btn btn-warning">Baru Diajukan</span>';
                }else if($data_pk[0]->status == 1){
                    echo '<span class="btn btn-success">Disetujui General Manager</span>';
                }
            ?>
        </div> -->
        <div class="text-center">
            <h3><font color='#000000' face='Times New Roman'>Surat Keterangan Pengalaman Kerja </font></h3>
        </div>
			<?php
		    	$ttd = "ttd_1";
				if((sessPenggunaId() == '69')){
					$ttd = 'ttd_1';
				}
		?>
		<?php 
		
		$id = $data_pk[0]->idGc ?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<input type="hidden" name="id" id="id" value="<?= $data_pk[0]->idGc ?>">
		<div class="table-responsive">
		<font color='#000000'>
<!-- TABEL SURAT KUNJUNGAN GUDANG -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                            <tr>
                        		<td colspan="6" style="text-align:center; font-size:18px; font-weight:bold;">
                        	    No : <?= $data_pk[0]->kode ?> <br><br>
                        		</td>
                        	</tr>
							<tr>
								<td colspan="3" style="text-align:left"><br><br>
									<font color='#000000'>
									Dengan Surat ini kami dari PT. Visi Yosindo Medikal menyatakan bahwa : <br><br></font>
								</td>
							</tr>
							<tr>
								<td rowspan="9" width="5%"></td>
								<tr>
									<td width="12%">Nama </td>
									<td>:&nbsp;&nbsp;  <font color='#000000'><?= $data_pk[0]->pegawai ?></font></td>
								</tr>
								<tr>
									<td>NIK </td>
									<td>:&nbsp;&nbsp;  <font color='#000000'><?= $data_pk[0]->nik ?></font></td>
								</tr>
								<tr>
									<td>Jabatan </td>
									<td>:&nbsp;&nbsp;  <font color='#000000'><?= $data_pk[0]->jabatan ?></font></td>
								</tr>
							</tr>
							</tbody>
							</table>
		
			
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			<tbody>
                        	<tr>
									<td colspan="4">Dinyatakan benar telah bekerja di PT. Visi Yosindo Medikal terhitung sejak 
									<font color='#000000'> <?= date('d-m-Y',strtotime($data_pk[0]->tgl_masuk)) ?> </font>
									sampai dengan 
									<font color='#000000'> <?= date('d-m-Y',strtotime($data_pk[0]->tgl_keluar)) ?> </font>
									dengan jabatan terakhir
									<font color='#000000'> <?= $data_pk[0]->jabatan ?> </font>.

							</tr>	
							<tr>
								<td colspan="4"><br>Demikian surat paklaring ini dibuat untuk digunakan sebagaimana mestinya. </td>
							</tr>
                        	<tr>
                        		<td colspan="4"></td>
                        		<td style="text-align:center; "><?= $data_pk[0]->kota_pengajuan ?>, <?= date('d-m-Y',strtotime($data_pk[0]->tgl_Pengajuan)) ?></td>
                        	</tr>
                     <tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;"></td>
						<td style="text-align:center; width:6%;"></td>
						<td style="text-align:center; width:29%;" ></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;" >Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttd1 		= $img_path."ttd_notyet2.png";
							
							
							if($data_pk[0]->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_744_cap.png";
							}else if($data_pk[0]->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:25%;"> <?php echo'<img src="" height="70">';?> </td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="" height="70">';?></td>
						<td style="text-align:center; width:15%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Dian Melati Amelia<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>HR and Legal</i></td>
					</tr>
                        	
                        </tbody>
			</table>
			<br>
<!-- PENUTUP SURAT KUNJUNGAN GUDANG -->
			
<br><br>
<div width="100%">
	<?php if (sessPenggunaId() == '1' || sessPenggunaId() == '69')  { ?>
		
		<button type="button" class="btn btn-success float-right btn-approval" style="margin-left:12px; margin-top:12px;" id-sp="<?=encrypt($data_pk[0]->idGc)?>"> <i class="fas fa-check"></i> Setujui </button>
		<button type="button" class="btn btn-secondary float-right btn-denial" style="margin-left:12px; margin-top:12px;" id-sp="<?=encrypt($data_pk[0]->idGc)?>"> <i class="fas fa-times"></i> Tolak </button>				
	<?php } ?>
	<a href="surat_part_two/print_page/paklaring/<?=$data_pk[0]->idGc?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
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
        	var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui Surat Keterangan Pengalaman Kerja?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_part_two/ttd_setujui/paklaring/'+level_ttd,
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
                //title: approval + ' absensi?',
				title: 'Tolak permohonan Surat Keterangan Pengalaman Kerja?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_part_two/ttd_tolak/paklaring/'+level_ttd,
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