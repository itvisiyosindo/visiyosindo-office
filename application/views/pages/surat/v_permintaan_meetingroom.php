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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="col-xl-10 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
				<div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'><u>Surat Permohonan Penggunaan Ruang Meeting </u></font></h2>
        </div>
		<font color='#000000'>
		<div class="form-group">
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
		</div>

<!-- TABEL SURAT PENGAJUAN -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
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
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="7%" style="text-align:right;"></td>
                        		<td width="8%">Nama</td>
                        		<td >:&nbsp;<?= $pengguna[0]->nama ?></td>
                        		<td width="30%"></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Jabatan</td>
                        		<td>:&nbsp;<?= $pengguna[0]->jabatan ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>NIK</td>
                        		<td>:&nbsp;<?= $pengguna[0]->nik ?></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Mengajukan permohonan izin menggunakan Ruang Meeting untuk keperluan, yaitu :
                        			<textarea class="myinput" rows="2" id="perihal" name="perihal" placeholder="Tulis Keperluan Anda Disini"></textarea>
                        		</td>
                        	</tr>
													<tr>
                        			<td colspan="4">Permohonan izin menggunakan Ruang Meeting pada tangal
															<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_pengajuan" Style="width:10%; text-align:center" placeholder="Pilih Tanggal">
															dari pukul
															<input id='jam_mulai' type="text" Style="width:10%; text-align:center" placeholder='Pukul' required>
															hingga
															<input id='jam_akhir' type="text" Style="width:10%; text-align:center" placeholder='Pukul' required>.
															<br>Lampiran &nbsp;:
															<input id='lampiran' type="text" Style="width:60%" placeholder='Klik Untuk Memasukkan Lampiran' required></td>
													</tr>	
                        	<tr>
														<td colspan="4"><br>Demikian surat permohonan izin ini saya ajukan, atas perhatian dan izin yang diberikan saya
														ucapkan terima kasih.</td>
													</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; ">Pekanbaru, diisi oleh sistem</td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; ">PT. Visi Yosindo Medkal</td>
                        	</tr>
                        	<tr style="height:60px;">
                                <?php
                                $img_path   = "uploads/file_karyawan/ttd/";
																$ttdaju		= $img_path."ttd_".$pengguna[0]->pengguna_id.".png";
                                $ttd3       = $img_path."ttd_blank.png";
                                ?>
                                <td colspan="3"></td>
                                <td style="text-align:center; "><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
                                
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; "><b><?= $pengguna[0]->short_name ?><hr></hr></b></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	
                        </tbody>
			</table>
			<br>
<!-- PENUTUP SURAT PENGANTAR DINAS -->
			<br><br>
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
	
	// PENUTP FUNGSI TAMBAH BUTTON
    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var perihal 			= $('#perihal').val();			
			var tgl_dinas			= $('#tgl_dinas').val();
			var tgl_pengajuan	= $('#tgl_pengajuan').val();
			var jam_mulai			= $('#jam_mulai').val();
			var jam_akhir			= $('#jam_akhir').val();
			var lampiran			= $('#lampiran').val();
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Surat Penggunaan Ruang Meeting?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat_part_two/addSrt/meetingroom',
                        dataType: 'JSON',
                        data: {
                        	perihal		 			: perihal,
													tgl_pengajuan		: tgl_pengajuan,
													jam_mulai 			: jam_mulai,
													tgl_dinas 			: tgl_dinas,
													jam_akhir				: jam_akhir,
													lampiran				: lampiran
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }				
            })
        })
		
		$(document).on('click', '.btn-denial', function() {
            const id = $(this).attr("id-pd")
            Swal.fire({
				title: 'Tolak Penggunaan Ruang Meeting?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/ttd_update/kg/2/ttd_1',
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