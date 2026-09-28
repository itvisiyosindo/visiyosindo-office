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
		<font color='#000000'>
		<div class="form-group">
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
		</div>

<!-- TABEL SURAT PENGAJUAN -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                            <tr>
                        		<td colspan="4" style="text-align:center; font-size:20px; font-weight:bold;">
                        			SURAT PERINGATAN
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4" style="text-align:center; font-size:12px; font-weight:bold;">.../SP/PT.VYM/PKU/…/2024</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i</font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="2">
                        			Perihal : Surat Peringatan:
                        		</td>
                        		<td>&nbsp;<input type="text" name="perihal" id="perihal" placeholder=" &nbsp;Ketik Perihal" required></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Surat peringatan ini ditujukan <br> Kepada :
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="7%" style="text-align:right;">1. &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >:&nbsp;<select name="nama" id="nama" style="background-color: transparent; border: none;">
																		<option value="">Semua</option>
																		<?php
																		$nama = $this->db->get('pengguna')->result();
																		foreach ($nama as $row) {
																		?>
																		<option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
																		<?php } ?>
																</select></td>
                        		<td width="30%"></td>
                        	</tr>
                        	<!--<tr>
                        		<td width="5%"></td>
                        		<td>Jabatan</td>
                        		<td>:&nbsp;<input type="text" name="jabatan" id="jabatan" placeholder=" &nbsp;Ketik Jabatan Karyawan" required></td>
                        		<td></td>
                        	</tr>-->
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Surat ini dikeluarkan sehubungan dengan evaluasi kinerja pertanggal <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_evaluasi" Style="width:10%; text-align:center" placeholder="Pilih">
                        		Saudara Melakukan Kesalahan Pada <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_salah" Style="width:10%; text-align:center" placeholder="Pilih">, Yaitu :</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><textarea class="myinput" rows="2" id="kesalahan" name="kesalahan" placeholder="Ketik Hasil Evaluasi"></textarea></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Bahwasanya hal tersebut saudara lakukan dengan cara <input type="text" name="cara" id="cara" placeholder=" &nbsp;Input Penyebab" Style="width:30%;" required ><br>
                        		Surat Peringatan &nbsp;<input type="text" name="jenis_sp" id="jenis_sp" placeholder=" &nbsp;Surat Peringatan Ke-" Style="width:10%;" required > ini mengakibatkan saudara di kenakan sanksi berupa :

                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white"><textarea class="myinput" rows="2" id="sanksi" name="sanksi" placeholder="Ketik Hasil Evaluasi"></textarea> </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Dengan diterimanya surat peringatan ini, jika dalam kurun waktu <input type="text" name="masa" id="masa" placeholder=" &nbsp;Ketik Masa SP" Style="width:10%;" required > bulan/masa SP tidak memperbaiki kesalahannya dan/atau melakukan kesalahan yang sama, maka akan diberikan surat peringatan berikutnya.
<br>&nbsp;&nbsp;&nbsp;
</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat peringatan ini kami sampaikan agar dapat diperhatikan dan menjadi evaluasi diri saudara.
Terimakasih.</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; ">Pekanbaru, <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_pengajuan" Style="width:40%; text-align:center" placeholder="Isi Tanggal"></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; ">PT. Visi Yosindo Medkal</td>
                        	</tr>
                        	<tr style="height:60px;">
                                <?php
                                $img_path   = "uploads/file_karyawan/ttd/";
                                $ttd3       = $img_path."ttd_blank.png";
                                ?>
                                <td colspan="3"></td>
                                <td style="text-align:center; "><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
                                
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; "><b><u>Dian Melati Amelia</u></b></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; vertical-align:top;"><i>HR & Legal Officer</i></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><i>Tembusan :</i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>1. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Direksi</i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>2. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;General Manager</i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>3. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;<input type="text" name="tembusan" id="tembusan" placeholder=" &nbsp;Input Tembusan" Style="width:30%;" required ></i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>4. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Arsip</i></td>
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
			var perihal 			    = $('#perihal').val();			
			var nama			        = $('#nama').val();
		  //var jabatan			        = $('#jabatan').val();
			var tgl_evaluasi		    = $('#tgl_evaluasi').val();
			var tgl_salah			    = $('#tgl_salah').val();
			var kesalahan		        = $('#kesalahan').val();
			var jenis_sp		        = $('#jenis_sp').val();
			var sanksi	                = $('#sanksi').val();
			var masa		            = $('#masa').val();
			var cara		            = $('#cara').val();
			var tembusan		        = $('#tembusan').val();
			var tgl_pengajuan		    = $('#tgl_pengajuan').val();tgl_pengajuan
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Surat Peringatan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/sp',
                        dataType: 'JSON',
                        data: {
                        	perihal 			: perihal,
							nama 	    	: nama,
							//jabatan 	: jabatan,
							tgl_evaluasi		: tgl_evaluasi,
							tgl_salah			: tgl_salah,
							kesalahan		: kesalahan,
							jenis_sp		: jenis_sp,
							sanksi	: sanksi,
							masa		: masa,
							cara        : cara,
							tembusan    : tembusan,
							tgl_pengajuan		: tgl_pengajuan

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
				title: 'Tolak Pengantar Dinas?',
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