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
            <h2><font color='#000000' face='Times New Roman'>SURAT SKORSING</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Surat Perintah Istirahat dibuat pada hari ini :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					
				</tr>

					

				<tr>
					<tr>
						<td width="10%">Perihal </td>
						<td>:&nbsp;&nbsp;Pemberian Skorsing</td>
					</tr>
					

					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>




			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
			
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Yang bertanda tangan dibawah ini :</font>
					</td>
				</tr>

				<tr>											
				</font>
					<td rowspan="12" width="5%"></td>
					<tr>
						<td width="10%">Nama </td>
						<td>:&nbsp;&nbsp; Dian Melati Amelia  </td>
					</tr>
					<tr>
						<td width="10%">NPP </td>
						<td>:&nbsp;&nbsp; 091 </td>
					</tr>
					<tr>
						<td>Jabatan </td>
						<td>:&nbsp;&nbsp;  HR & Legal</td>
					</tr>
				</tr>
				</tr>
				<tr>
						<td><font color="white">i </font></td>
					</tr>
			</table>



			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				

				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan sangat terpaksa memberikan skorsing kepada :</font>
					</td>
				</tr>

				<tr>
					
					<td rowspan="12" width="5%"></td>
				<tr>
					<tr>
						<td width="10%">Nama </td>
						<td style="text-align:left; ">
						
                        		<select name="idpengguna" id="idpengguna" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama </option>
                                    <?php
                                    //$nama = $this->db->get('pengguna')->where('pengguna.status=1')->result();
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                        	
						</td>
					</tr>
					
				</tr>
				

				<tr>
						<td><font color="white">i </font></td>
				</tr>

			</tr>
			</table>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="black">
				<tr>
          <td colspan="4">untuk tidak masuk kerja selama <input type="text" name="masa" id="masa" placeholder=" &nbsp;Input Waktu" Style="width:30%;" required > terhitung sejak tanggal<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tanggal" Style="width:10%; text-align:center" placeholder="Pilih">dan dilakukan pemotongan gaji sesuai dengan perhitungan hari kerja yang tidak diikutinya.</td>
        </tr>
				<tr>
          <td colspan="4">Demikian Surat Skorsing ini kami buat dan kami berikan kepada yang bersangkutan agar dipatuhi dan dipergunakan sebagaimana mestinya.</td>
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
			var tanggal		= $('#tanggal').val();
			var idpengguna 		= $('#idpengguna').val();
			var masa 		= $('#masa').val();
			
			
			
			Swal.fire({
				title: 'Ajukan Permintaan Surat Skorsing?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_new/addSrt/spi',
                        dataType: 'JSON',
                        data: {
							tanggal			: tanggal,
							idpengguna		: idpengguna,
							masa	: masa,
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