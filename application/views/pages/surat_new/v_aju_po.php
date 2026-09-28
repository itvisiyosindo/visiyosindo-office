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
            <h2><font color='#000000' face='Times New Roman'><u>Form Permintaan Approval PO</u></font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left"><br><br>
						<font color='#000000'>Kepada Yth:<br>
						<b>Direktur PT. Visi Yosindo Medikal</b><br><br>
						
						Saya yang bertanda tangan di bawah ini : <br><br></font>
					</td>
				</tr>
				<tr>
					<tr>
						<td width="25%">Nama </td>
						<td>:</td>
						<td> <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:</td>
						<td>  <?= $pengguna[0]->jabatan ?></td>
					</tr>
				</tr>
				
			</table>
		
			
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				
				<tr>
            <td colspan="4">Untuk Purchase Order (PO) berikut: </td>
				</tr>	

			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>					
						
					<tr><br>
						<td>Nama Instansi/Perusahaan</td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="id_pelanggan" id="id_pelanggan" >
                                        
                                            <option value="">- Pilih Instansi/Perusahaan-</option>
																						<?php
																						foreach ($list_cust as $row) {
																							echo '<option value="' . $row->id_pelanggan . '">' . $row->identitas_pelanggan . '</option>';
																						}
																						?>
                                        </select>
						</td>
					</tr>
					<tr>
						<td width="25%">Nomor PO</td>
						<td>:</td>
						<td><input id='no_po' type="text" Style="width:40%; text-align:left" placeholder='Nomor PO' required></td>
					</tr>
					<tr>
						<td width="25%">Item PO</td>
						<td>:</td>
						<td><input id='item1' type="text" placeholder='Klik Untuk Memasukkan Item 1' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item2' type="text" placeholder='Klik Untuk Memasukkan Item 2' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item3' type="text" placeholder='Klik Untuk Memasukkan Item 3' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item4' type="text" placeholder='Klik Untuk Memasukkan Item 4' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item5' type="text" placeholder='Klik Untuk Memasukkan Item 5' required></td>
					</tr>


					<tr>
						<td width="25%">Tanggal PO</td>
						<td>:</td>
						<td><input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tanggal" Style="width:20%; text-align:center" placeholder="Pilih Tanggal"></td>
					</tr>

					<tr>
						<td width="25%">Alasan PO tidak diproses</td>
						<td>:</td>
						<td><input id='alasan' type="text" placeholder='Klik Untuk Memasukkan Alasan PO tidak diproses' required></td>
					</tr>
					
					<tr>
						<td width="25%">Lampiran</td>
						<td>:</td>
						<td><input id='lampiran' type="text" placeholder='Klik Untuk Memasukkan Lampiran' required></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>

					<tr>
						<td>Jenis</td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="jenis" id="jenis" >
                   <option value="1">Bukan Piutang Macet</option>
                   <option value="2">Piutang Macet</option>
								</select>
						</td>
					</tr>
					<tr>
							<td colspan="3" style="padding-top:5px; font-size:14px;">
									<i>Note:</i> Jika memilih <b>Piutang Macet</b>, maka data akan direview oleh <b>Legal</b>.
							</td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>

					

				</tr>
				
				

			</table>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					
				<tr>
            <td colspan="4"><br>Dengan ini Mengajukan Purchase Order (PO) berikut untuk dapat diproses dan dikirimkan barangnya ke customer.</td>
				</tr>

			</table>


            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" ></td>
						<td style="text-align:center; width:44.5%;" ></td>
						<td style="text-align:center; width:25.5%;" >Diajukan Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
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
			var no_po			= $('#no_po').val();
			var id_pelanggan		= $('#id_pelanggan').val();
			var item1			= $('#item1').val();
			var item2			= $('#item2').val();
			var item3			= $('#item3').val();
			var item4			= $('#item4').val();
			var item5			= $('#item5').val();
			var tanggal 	= $('#tanggal').val();
			var lampiran 	= $('#lampiran').val();
			var alasan 		= $('#alasan').val();
			var jenis 		= $('#jenis').val();
			
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Approval PO?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/addSrt/po',
                        dataType: 'JSON',
                        data: {
							id_pelanggan	: id_pelanggan,
							no_po			: no_po,
							tanggal		: tanggal,
							lampiran	: lampiran,
							alasan		: alasan,
							jenis			: jenis,
							item1			: item1,
							item2			: item2,
							item3			: item3,
							item4			: item4,
							item5			: item5,
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