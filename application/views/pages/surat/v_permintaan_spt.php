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
		<font color='#000000'>
		<div class="form-group">
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
		</div>

<!-- TABEL SURAT PENGAJUAN -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                            <tr>
                        		<td colspan="4">
                        			Nomor Surat Dinas :
                        			<textarea class="myinput" rows="2" id="kode" name="kode" placeholder="Masukkan nomor surat dinas disini"></textarea>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"> Perihal : Dinas   </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			Sehubungan dengan adanya pekerjaan
                        			<textarea class="myinput" rows="2" id="perihal" name="perihal" placeholder="Tulis Keperluan Anda Disini"></textarea>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			di <textarea class="myinput" rows="2" id="lokasi_dinas" name="lokasi_dinas" placeholder="Tulis Lokasi Dinas Anda Disini"></textarea> pada Tanggal 
                        			<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_dinas" Style="width:20%; text-align:center" placeholder="Pilih">
                        			sampai dengan 	<input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_dinas_akhir" Style="width:20%; text-align:center" placeholder="Pilih">, maka dengan ini kami mengutus karyawan kami
                        			sebagai berikut :
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td width="7%" style="text-align:right;">1. &nbsp;</td>
                        		<td width="8%">Nama</td>
                        		<td >:&nbsp;<input type="text" name="nama_karyawan" id="nama_karyawan" placeholder=" &nbsp;Ketik Nama Karyawan" required></td>
                        		<td width="30%"></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Jabatan</td>
                        		<td>:&nbsp;<input type="text" name="jabatan_karyawan" id="jabatan_karyawan" placeholder=" &nbsp;Ketik Jabatan Karyawan" required></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>NIK</td>
                        		<td>:&nbsp;<input type="number" name="nik_karyawan" id="nik_karyawan" placeholder=" &nbsp;Ketik NIK Karyawan" required></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td width="5%"></td>
                        		<td>Lampiran</td>
                        		<td>:&nbsp;<input type="text" name="lampiran" id="lampiran" placeholder=" &nbsp;Ketik Lampiran Karyawan" required></td>
                        		<td></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Untuk melaksanakan tugas pada pekerjaan tersebut. Kami berharap karyawan kami dapat
                        		menyelesaikan tugas tersebut dengan baik.</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Surat pengantar dinas ini berlaku hingga tugas selesai dan yang bersangkutan telah memberikan
                        			laporan kembali ke Perusahaan.
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat pengantar dinas ini dibuat, agar dapat digunakan sebagaimana mestinya. Atas
                        		perhatian dan kerjasamanya diucapkan terimakasih.</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
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
                        		<td style="text-align:center; "><b><u>Yolanda Pratiwi</u></b></td>
                        	</tr>
                        	<tr>
                        		<td colspan="3"></td>
                        		<td style="text-align:center; vertical-align:top;"><i>Pimpinan Umum</i></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        		<td colspan="4"><i>Tembusan :</i></td>
                        	</tr>
                        	<tr>
                        		<td style="text-align:right; "><i>1. </i></td>
                        		<td colspan="3" style="text-align:left; vertical-align:top;"><i>&nbsp;Director</i></td>
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
		    var kode 				    = $('#kode').val();	
			var perihal 			    = $('#perihal').val();			
			var tgl_dinas			    = $('#tgl_dinas').val();
		    var tgl_dinas_akhir			= $('#tgl_dinas_akhir').val();
			var tgl_pengajuan		    = $('#tgl_pengajuan').val();
			var lampiran			    = $('#lampiran').val();
			var lokasi_dinas		    = $('#lokasi_dinas').val();
			var nama_karyawan		    = $('#nama_karyawan').val();
			var jabatan_karyawan	    = $('#jabatan_karyawan').val();
			var nik_karyawan		    = $('#nik_karyawan').val();
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Surat Dinas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/sd',
                        dataType: 'JSON',
                        data: {
                            kode				: kode,
                        	perihal 			: perihal,
							tgl_dinas 	    	: tgl_dinas,
							tgl_dinas_akhir 	: tgl_dinas_akhir,
							tgl_pengajuan		: tgl_pengajuan,
							lampiran			: lampiran,
							lokasi_dinas		: lokasi_dinas,
							nama_karyawan		: nama_karyawan,
							jabatan_karyawan	: jabatan_karyawan,
							nik_karyawan		: nik_karyawan

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