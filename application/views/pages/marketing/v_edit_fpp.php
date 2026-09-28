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
			width:97%;
			height:auto;
			border:0px solid #000;
			border-radius:0px; 
			-moz-border-radius:8px;
			margin-left:0px;
			background:#b7d5ac;
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
	</style>
</header>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'>Edit Data Form Permintaan Penawaran</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini saya mengajukan permintaan penawaran :</font>
					</td>
				</tr>
							
				<tr>											
		</font>
					<font color='#ffffff'>
							<?= 
							
								$dt_aju	= strtotime($data_fpp[0]->tanggal);
								$tgl 	= date("d", $dt_aju)." - ".date("m", $dt_aju)." - ".date("Y", $dt_aju);
								$dt_pengajuan	= strtotime($data_fpp[0]->tgl_Pengajuan);
								$tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);
								
							?>
					
					</font>
					<td rowspan="8" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $data_fpp[0]->pengaju ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $data_fpp[0]->jabatan ?></td>
					</tr>
					
					<tr>
						<td>Customer Name </td>
						<td>:&nbsp;&nbsp;<input id='csname' type="text" value="<?=  $data_fpp[0]->csName ?>" placeholder='Klik Untuk Memasukkan Customer Name' required></td>
					</tr>
					<tr>								
						<td>Tanggal </td>
						<td>
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'>
								<span> :&nbsp;&nbsp; </span>
								<input type="text" id="tanggal" Style="width:20%" value="<?=  $tgl ?>" placeholder='Tanggal' required>
							</div>
						</td>
					</tr>
					<tr>
						<td>Contact Person </td>
						<td>:&nbsp;&nbsp;<input id='cpname' type="text" value="<?=  $data_fpp[0]->cpName ?>" placeholder='Klik Untuk Memasukkan Contact Person Name' required></td>
					</tr>
					<tr>
						<td>No CP </td>
						<td>:&nbsp;&nbsp;<input id='cpno' type="text" value="<?=  $data_fpp[0]->noCp ?>" placeholder='Klik Untuk Memasukkan No CP' required></td>
					</tr>

					<tr>
						<td>Term Of Payment </td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="payment" id="payment" >
                  					<option value = "<?=  $data_fpp[0]->paYment ?>"><?= $data_fpp[0]->paYment ?></option>
									<option value = "CASH">CASH</option>
									<option value = "COD">COD</option>
									<option value = "NET 30">NET 30</option>
									<option value = "CREDIT">CREDIT</option>
									<option value = "1">Lainnya</option>
                			</select>     
							&nbsp;&nbsp;<input id="lainnyaInput" type="text" placeholder=': Klik Untuk Memasukkan Term of Payment' style="display: none;" required></td>                  
						</td>
					</tr>			

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="5%"> No </th>
                                <th style="text-align:center" bgcolor="#b7d5ac"> Description </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="7%"> Qty </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="17%"> Unit Price (IDR) </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Disc </th>
                                <th style="text-align:center" bgcolor="#b7d5ac" width="15%"> Komisi User </th>
                            </tr>
                        </thead>
                        <tbody>
									<?php
										$x=1;
										$xy=0;
										foreach ($detail_fpp as $row) {
										$x = $x+1;
										$xy = $xy+1;
									?>
								<tr>
									<input class="no-outline" type="hidden" id="<?= 'id_Dfpp_'.$xy ?>"  value="<?= $row->if_Dfpp ?>">
                                    <td style="text-align:center">
										<?= $xy ?>
									</td>
									<td><input type="text" id="<?= 'deskripsi_'.$xy ?>" value="&nbsp;<?= $row->des ?>&nbsp;" Style="width:100%" required></td>
                                    <td><input type="text" id="<?= 'quali_'.$xy ?>" value="&nbsp;<?= $row->Qty ?>&nbsp;"Style="width:100%; text-align:right" required></td>
                                    <td><input type="text" id="<?= 'price_'.$xy ?>" value="&nbsp;<?= $row->pri ?>&nbsp;"Style="width:100%; text-align:right" required></td>
                                    <td><input type="text" id="<?= 'diskon_'.$xy ?>" value="&nbsp;<?= $row->dis ?>&nbsp;"Style="width:100%" placeholder='Isi Angka Saja, Tanpa %' required></td>
                                    <td><input type="text" id="<?= 'komisi_'.$xy ?>" value="&nbsp;<?= $row->kom ?>&nbsp;"Style="width:100%" placeholder='Isi Angka Saja, Tanpa %' required></td>
                                </tr>
										<?php } ?>
										<input type="hidden" id="x" value="<?= $xy ?>">
										<?php for($kosong=$x;$kosong<=20;$kosong++){ ?>
								<tr>
								<td> <font color="white">i </font> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
							<?php } ?>
                        </tbody>
                        
			</table>
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<input class="no-outline" type="hidden" id="id_fpp" name="id_fpp" value="<?= $data_fpp[0]->idFpp ?>">		


				<tr>
					<tr>
						<td>Notes </td>
						<td>:&nbsp;&nbsp;<input id='notes' type="text" value="<?= $data_fpp[0]->noTes ?>" placeholder='Klik Untuk Memasukkan Notes' required></td>
					</tr>
				</tr>
			</table>


            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

				<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="3">
								
								<?= $data_fpp[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" ></td>
						<td style="text-align:center; width:44.5%;" ></td>
						<td style="text-align:center; width:25.5%;" >Diajukan Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_fpp[0]->idPengaju.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_fpp[0]->nama_ttd ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_fpp[0]->jabatan ?></i></td>
					</tr>
				</tbody>
				
			</table>
			</div>
			<?= form_close(); ?>
			<br><br>
		<div role="document">
			<button type="button" class="btn btn-info btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Revisi </button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>

<script>
	document.getElementById("payment").addEventListener("change", function() {
        var pilihan = this.value;
        var lainnyaInput = document.getElementById("lainnyaInput");

        if (pilihan === "1") {
            lainnyaInput.style.display = "block";
        } else {
            lainnyaInput.style.display = "none";
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {		
			
			var csname 	= $('#csname').val();
        	var id_fpp = $('#id_fpp').val();
        	var id_Dfpp = $('#id_Dfpp').val();
			var tanggal	= $('#tanggal').val();
			var cpname 		= $('#cpname').val();
			var cpno 	= $('#cpno').val();
			var payment	= $('#payment').val();
			var lainnyaInput	= $('#lainnyaInput').val();
			var notes	= $('#notes').val();
			
			let itung_isi	= $('#x').val();
			let id_D		= [];
			let id_Dfpp		= [];
			let des			= [];
			let deskripsi	= [];
			let qty			= [];
			let quali		= [];
			let pri			= [];
			let prince		= [];
			let dis			= [];
			let diskon		= [];
			let kom			= [];
			let komisi		= [];
			for (let i=1; i<=itung_isi; i++) {
				if($( '#deskripsi_'+i).val() != ""){
					id_D[i] = $('#id_Dfpp_'+i).val();
					des[i] = $('#deskripsi_'+i).val();
					qty[i] = $('#quali_'+i).val();		  
					pri[i] = $('#price_'+i).val();		  
					dis[i] = $('#diskon_'+i).val();		  
					kom[i] = $('#komisi_'+i).val();
				}				
			}			
			let itung		= des.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Edit Permintaan Penawaran?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'fpp/updateAll/'+id_fpp,
                        dataType: 'JSON',
                        data: {
							csname	: csname,
							tanggal	: tanggal,
							cpname	: cpname,
							cpno	: cpno,
							itung	: itung,
							payment	: payment,
							lainnyaInput	: lainnyaInput,
							notes	: notes,
							id_Dfpp	: id_D,
							deskripsi	: des,
							quali	: qty,
							price	: pri,
							diskon	: dis,
							komisi	: kom,
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