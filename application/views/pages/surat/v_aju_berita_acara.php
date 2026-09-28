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
            <h2><font color='#000000' face='Times New Roman'><u>Form Berita Acara <br><br></u></font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>								
						<td>Tanggal </td>
						<td>
							<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}'>
								<span> :&nbsp;&nbsp; </span>
								<input type="text" id="tanggal" Style="width:10%" placeholder='Input Tanggal' required>
							</div>
						</td>
				</tr>
				
				<tr>
					<td rowspan="9" width="7%"></td>
					
				</tr>
				
			</table>
		
			
			<br>

			<table id="tbl_7" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
                    <td colspan="4"><strong>Telah dilakukan penelitian dan analisis terhadap : </strong>
						<textarea id='analisis' type="text" style="width: 100%; box-sizing: border-box;" placeholder='Klik Untuk Memasukkan Penelitian dan Analisis' required></textarea></td>
				</tr>	
				<tr>
                    <td colspan="4"><strong>Hasil Sementara : </strong>
						<textarea id='hasil' type="text" style="width: 100%; box-sizing: border-box;" placeholder='Klik Untuk Memasukkan Hasil Sementara' required></textarea></td>
				</tr>
				<tr>
                    <td colspan="4"><strong>Saran, masukan, arahan dan penanganan : </strong>
						<textarea id='penanganan' type="text" style="width: 100%; box-sizing: border-box;" placeholder='Klik Untuk Memasukkan Saran, masukan, arahan dan penanganan' required></textarea></td>
				</tr>	
				<tr>
					<td colspan="4">Lampiran : &nbsp;
					<input id='lampiran' type="text" Style="width:60%" placeholder='Klik Untuk Memasukkan Lampiran' required></td>
				</tr>
				<tr>
                    <td colspan="4"><br>Demikian berita acara ini dibuat, agar dapat digunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya diucapkan terimakasih. <br><br><br><br></td>
				</tr>

				
			</table>


            <table id="tbl_3" border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>

					
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" >Diajukan Oleh,</td>
						<td style="text-align:center; width:35.5%;" >Diketahui Oleh,</td>
						<td style="text-align:center; width:25.5%;" >Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
							$ttd1 		= $img_path."ttd_blank.png";
						?>
						<td style="text-align:center;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $pengguna[0]->short_name ?><hr></hr></td>
						<td style="text-align:center; ">
						
                        		<select name="id_diketahui" id="id_diketahui" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama yang Mengetahui</option>
                                    <?php
                                    //$nama = $this->db->get('pengguna')->where('pengguna.status=1')->result();
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                        	
						</td>
						<td style="text-align:center; ">
							<select name="id_disetujui" id="id_disetujui" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama yang Menyetujui</option>
                                    <?php
                                    //$nama = $this->db->get('pengguna')->where('pengguna.status=1')->result();
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
						</td>
						<td>
               <input  type="checkbox" id="direktor" name="direktor" value="1">
            </td>
            <td style="text-align:center; ">
							<select name="id_dir" id="id_dir" style="background-color: transparent; border: none;">
                  <option value="">Pilih Nama yang Menyetujui</option>
									<option value="54">Bob Ariyos</option>
									<option value="23">Meilina Safitri</option>
									<option value="33">Yolanda Pratiwi</option>
              </select>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "></td>
						<td style="text-align:left; ">Harap Centang & Pilih Nama Jika Persetujuan Sampai Director</td>
					</tr>
				</tbody>
				
			</table>
			
			</div>
			<?= form_close(); ?>
			<br><br>
			
			<p class="text-danger font-weight-bold font-italic">* Note: Silakan pilih "Administrator" jika Berita Acara tanpa approval mengetahui / Diketahui Oleh.</p>
			
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
        
        $(document).on('click', '.btn-ajukan', function(e) {
            e.preventDefault();
            var tanggal       = $('#tanggal').val();
            var analisis      = $('#analisis').val();
            var hasil         = $('#hasil').val();
            var penanganan    = $('#penanganan').val();
            var id_diketahui  = $('#id_diketahui').val();
            var id_disetujui  = $('#id_disetujui').val();
            var id_dir        = $('#id_dir').val();
            var lampiran      = $('#lampiran').val();
            var direktor      = jQuery("input[name=direktor]:checked").val();
            var token_val     = (typeof token !== 'undefined') ? token : '';

            if (!tanggal || !analisis || !hasil || !penanganan) {
                Swal.fire('Perhatian', 'Harap lengkapi tanggal, analisis, hasil, dan penanganan terlebih dahulu!', 'warning');
                return false;
            }

            Swal.fire({
                title: 'Ajukan Berita Acara?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_part_two/addSrt/addBeritaAcara',
                        dataType: 'JSON',
                        data: {
                            tanggal      : tanggal,
                            analisis     : analisis,
                            hasil        : hasil,
                            penanganan   : penanganan,
                            id_diketahui : id_diketahui,
                            id_disetujui : id_disetujui,
                            id_dir       : id_dir,
                            lampiran     : lampiran,
                            direktor     : direktor,
                            csrf_token   : token_val
                        },
                        success: function(resp) {
                            if (resp.status == 'success' || resp.status == true) {
                                Swal.fire('Berhasil!', resp.message || 'Berita Acara Berhasil Diajukan', 'success').then(function() {
                                    window.location.href = 'surat_part_two/show/list/berita_acara';
                                });
                            } else {
                                Swal.fire('Perhatian / Debug', resp.message || 'Gagal mengajukan Berita Acara', 'error');
                            }
                        },
                        error: function(err) {
                            Swal.fire('Error Server', 'Terjadi kesalahan pada respon server.', 'error');
                        }
                    });                    
                }                
            });
        });       
    });

    function goBack() {
        window.history.back();
    }
</script>