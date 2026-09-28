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
            <h2><font color='#000000' face='Times New Roman'><u>Form Permintaan Penggunaan Kendaraan Kantor</u></font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left"><br><br>
						<font color='#000000'>Kepada Yth.<br>
						<b>PIMPINAN PT. VISI YOSINDO MEDIKAL</b><br>
						Jl. Inpres No. 268 D – Pekanbaru<br><br>
						Dengan Hormat,<br>
						Saya yang bertanda tangan di bawah ini : <br><br></font>
					</td>
				</tr>
				<tr>
					<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Nama </td>
						<td>:&nbsp;&nbsp; <?= $pengguna[0]->nama ?>  </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->jabatan ?></td>
					</tr><tr>
						<td>NPP </td>
						<td>:&nbsp;&nbsp;  <?= $pengguna[0]->no_pegawai ?></td>
					</tr>
				</tr>
				
			</table>
		
			
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				
				<tr>
            <td colspan="4">Mengajukan permohonan penggunaan kendaraan kantor : </td>
				</tr>	

			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				
				<tr>
					<td rowspan="9" width="7%"></td>
					<tr>
						<td width="18%">Keperluan </td>
						<td>:&nbsp;&nbsp; <input id='keperluan' type="text" placeholder='Klik Untuk Memasukkan Keperluan' required>  </td>
					</tr>
					<tr>
						<td>Jenis Mobil </td>
						<td>:&nbsp;&nbsp;  <input id='mobil' type="text" Style="width:20%; text-align:center" placeholder='Jenis Mobil' required></td>
					</tr>
					<tr>
						<td>No. Polisi </td>
						<td>:&nbsp;&nbsp;  <input id='nopol' type="text" Style="width:20%; text-align:center" placeholder='No. Polisi' required></td>
					</tr>
					<tr>
						<td>Lama Pemakaian </td>
						<td>:&nbsp;&nbsp;  <input id='lama' type="text" Style="width:20%; text-align:center" placeholder='Lama Pemakaian' required> Hari</td>
					</tr>
					<tr>
						<td>Tanggal </td>
						<td>:&nbsp;&nbsp;  <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tanggal" Style="width:20%; text-align:center" placeholder="Pilih Tanggal">
						</td>
					</tr>
					<tr>
						<td width="18%">
								<br>Lampiran </td>
						<td><br>:&nbsp;&nbsp; <input id='lampiran' type="text" placeholder='Klik Untuk Memasukkan Lampiran (Jika Ada)' required>  </td>
					</tr>
				</tr>

			</table>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
					
				<tr>
            <td colspan="4"><br>Demikian surat permohonan penggunaan kendaraan kantor ini saya ajukan, atas perhatiannya saya
					ucapkan terimakasih.</td>
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
			var keperluan	= $('#keperluan').val();
			var mobil			= $('#mobil').val();
			var nopol			= $('#nopol').val();
			var lama			= $('#lama').val();
			var tanggal 	= $('#tanggal').val();
			var lampiran 	= $('#lampiran').val();
			
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Penggunaan Kendaraan Kantor?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/addSrt/kendaraan',
                        dataType: 'JSON',
                        data: {
							keperluan	: keperluan,
							lama			: lama,
							mobil			: mobil,
							nopol			: nopol,
							tanggal		: tanggal,
							lampiran	: lampiran,
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