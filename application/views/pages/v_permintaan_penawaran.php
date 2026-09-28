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
            <h2><font color='#000000' face='Times New Roman'><u>Form Order Confirmation</u></font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'></font>
					</td>
				</tr>
				<tr>					
						
					</font>
					<td rowspan="10" width="1%"></td>
					<tr>
						<td width="18%"></td>
						<td></td>
                        <td>Tanggal</td>
                        <td><input type="date" name="tanggal_order" class="form-control" id="tanggal_order"></td>
					</tr>
					<tr>
						<td>Contact Person</td>
						<td>:&nbsp;<input type="text" name="contact_person" id="contact_person" placeholder=" &nbsp;Ketik Contact Person" required></td>
                        <td>Term of Payment</td>
                        <td><select name="top_order" class="form-control">
							<option value="">Pilih Salah Satu</option>
							<option value="cash">Cash</option>
							<option value="cash on delivery">Cash on Delivery</option>
							<option value="net 30">Net 30</option>
							<option value="credit">Credit</option>
						</select></td>
                    </tr>
					<tr>
						<td>Mr / Mrs. </td>
						<td>:<input type="text" name="nama_cust" id="nama_cust" placeholder=" &nbsp;Ketik Nama Customer" required></td>
					</tr>

					<tr>
						<td><font color="white"> </font></td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="4%"> No</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="15%"> Description </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="25%"> Qty </th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="25%"> Unit Price (IDR)</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="11%"> Disc</th>
                                <th style="text-align:center" bgcolor="#C6DEFF" width="20%"> Komisi User </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$itung = 1;
								for($x=1;$x<=15;$x++){
									$itung = $x;
                            $no=1;
							?>
								<tr>
                                    <td style="text-align:center;">
                                    	<input type="text" value="<?php echo $x?>" Style="width:100%; text-align: center;" required>
                                    </td>
                                    <td style="text-align:center">
										<input type="text" id="<?= 'desc_'.$x ?>" Style="width:100%" required>										
									</td>
                                    <td>
                                    	<input type="text" id="<?= 'qty_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'idr_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
									<input type="text" id="<?= 'disc_'.$x ?>" Style="width:100%" required>
                                    </td>
                                    <td>
                                    	<input type="text" id="<?= 'komisi_user_'.$x ?>" Style="width:100%" required>
                                    </td>
                                </tr>
							<?php } ?>
							<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        
			</table>
            <br>
			<table id="tbl_top" border="1" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
            <tbody>
                <tr>
                    <td>Term of Payment<input type="text" name="top" id="top" placeholder="Ketik Term of Payment"></td>
                </tr>
            </tbody>
            </table>
            <br>
            <table id="tbl_top" border="1" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
            <tbody>
                <tr>
                    <td> Notes<input type="text" name="top" id="top" placeholder="Ketik Catatan"></td>
                </tr>
            </tbody>
            </table>
            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr>
						<td height="20px"></td>
					</tr>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; vertical-align:top;" colspan="7">
							 <select id="kota_aju" name="kota_aju" style="text-align:left; width:20%; border:0px; margin:0px;" required>
								<option value="">Pilih Kota</option>
								<?php
								foreach ($list_kota as $row) {
									echo "<option value='".$row->id."PengHubunG".$row->nama."'>".$row->nama."</option>";
								}
								?>
							</select>
							<span> ,&nbsp; </span>
							<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"top", "format":"dd-mm-yyyy"}' id="tgl_pengajuan" name="tgl_pengajuan" Style="width:15%; text-align:left" placeholder="Tanggal Pengajuan" required> 
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2"></td>
						<td style="text-align:center; width:10%;" rowspan=""> </td>
						<td style="text-align:top; vertical-align: top; width:50%;font-size:12px; font-color:#0000;" rowspan="4"><b>
                            Notes : <br>
                               <li>Harap diisi dengan jelas dan lengkap</li>
                                <li>Apabila Paket Mesin (CR / DR / Xray / dll) dengan sistem pembayaran cicilan, mohon dilampirkan capture hitungan kalkulasi dari website inventory PT SGM</li>
                            </p>
                        </b></td>
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
						<td style="text-align:center; width:23%;"></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"> </td>

						
					</tr>
					<tr>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
		
				
					</tr>
					<tr>
                    <td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
	
				
						
					</tr>
				</tbody>
				
			</table>
			</div>
			<?= form_close(); ?>
			<br><br>
		<div role="document">
			<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Ajukan </button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var contact_person 			= $('#contact_person').val();			
			var top_order				= $('#top_order').val();
			var nama_cust				= $('#nama_cust').val();
			var tanggal_order			= $('#tanggal_order').val();

			let itung_isi		= $('#itung').val();
			let desc			= [];
			let qty				= [];
			let idr				= [];
			let disc			= [];			
			let komisi_user 	= [];
			
			for (let i=1; i<=itung_isi; i++) {
				if($( '#nama_customer_'+i).val() != ""){
					tanggal_order[i]	= $('#tanggal_order'+i).val();
					desc[i]				= $('#desc_'+i).val();
					qty[i]				= $('#qty_'+i).val();
					idr[i]				= $('#idr'+i).val();
					disc[i]				= $('#disc_'+i).val();
					komisi_user[i]		= $('#komisi_user_'+i).val();
					
					// nom[i] = $('#nominal_'+i).val();
				}				
			}			
			let itung		= nama_customer.length;
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Penawaran?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'permintaan_penawaran/add',
                        dataType: 'JSON',
                        data: {
                        	contact_person 	: contact_person,
                        	top_order 		: top_order,
							nama_cust 		: nama_cust,
							tanggal_order 	: tanggal_order,

							desc			: desc,
							qty 			: qty,
							idr 			: idr,
							disc 			: disc,
							komisi_user 	: komisi_user,
							csrf_token	: token
							
							// pembayar_pajak : pembayar_pajak
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }				
            })
        })
		
		$(document).on('click', '.btn-denial', function() {
            const id_pb = $(this).attr("id-pb")
            Swal.fire({
				title: 'Tolak Pengajuan Biaya Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/pbok/2/ttd_1',
                        dataType: 'JSON',
                        data: {
                            id_pbok : id_pbok,
							csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
                }
            })
        })
		
		Array.prototype.forEach.call(document.getElementsByName('kode_tiket'),
		function (elem) {
			elem.addEventListener('change', function() {
				let 	text	= this.value;
				const 	isi		= text.split("PengHubunG");
				let		idTiket		= isi[0],
						tampil_cust = isi[1],
						tampil_kota = isi[2];						
				
				// 				
			});
		});
		
    })

    function goBack() {
        window.history.back();
    }
</script>