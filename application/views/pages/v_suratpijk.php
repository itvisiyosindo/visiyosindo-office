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
        	<table>
                <tr>
                  <th>
                    <img src="assets/img/kop_surat_vym_underline.png" width="100%" height="30%" />
                  </th>
                </tr>
                <tr>
                  <th>
                    <div style="text-align:center">
                      <h4>
                        <font color="black"><u><?= $page_desc ?></font></u>
                      </h4>
                    </div>
                  </th>
            </table>
        </div>
	    <div class="table-responsive">
		<font color='#000000'>
		<div class="form-group">
			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
		</div>
            
<!-- TABEL SURAT PENGAJUAN -->
			<table id="myTable1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="0" width="100%">
                        <tbody>
                        	<tr>
                        		<td colspan="4"> Kepada Yth. </td>
                        	</tr>
	                        <tr>
                        		<td colspan="4"><b>PIMPINAN PT. VISI YOSINDO MEDIKAL</b></td>
                        	</tr
                             <tr>
                        		<td colspan="4">Jl. Inpres No.268 D - Pekanbaru</td>
                        	</tr
                        	<tr>
                        		<td colspan="4"><font color="white">i </font></td>
                        	</tr>
                        	<tr>
                        	    <td colspan="4">
                        	        Dengan Hormat,
                        	    </td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			&emsp;&emsp; Saya yang bertanda tangan di bawah ini :
                        			<table>
                        			    <tr><td>&emsp;&emsp;&emsp;&emsp;</td></td><td>Nama</td><td>&emsp;:&nbsp;</td><td><?= $pengguna[0]->nama ?></td></tr>
                        			    <tr><td>&emsp;&emsp;&emsp;&emsp;</td><td>Jabatan</td><td>&emsp;:&nbsp;</td><td><?= $pengguna[0]->jabatan ?></td></tr>
                        			    <tr><td>&emsp;&emsp;&emsp;&emsp;</td><td>Divisi</td><td>&emsp;:&nbsp;</td><td><?= isset($pengguna[0]->divisi) ? $pengguna[0]->divisi : '-'  ?></td></tr>
                        			</table>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        			mengajukan Permohonan izin karena :
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        		    Dengan ini saya memohon izin selama hari mulai tanggal <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_mulai" Style="width:25%; text-align:center" placeholder="Pilih"> hingga tanggal <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tgl_akhir" Style="width:20%; text-align:center" placeholder="Pilih"> 
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">Demikian surat permohonan cuti ini saya ajukan, atas perhatian dan izin yang diberikan saya ucapkan terima kasih.</td>
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
                        	<tr>
                        		<td colspan="4">
                        		    Lampiran  :
                        		    <input class=myinput"" type="text" name="lampiran" id="lampiran" placeholder="Pastekan link lampiran anda" required>
                        		</td>
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
			var perihal 		= $('#perihal').val();			
			var tgl_dinas		= $('#tgl_dinas').val();
			var tgl_pengajuan	= $('#tgl_pengajuan').val();
			var lampiran		= $('#lampiran').val();
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Surat Kunjungan Gudang?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'surat/add/kg',
                        dataType: 'JSON',
                        data: {
                        	perihal 			: perihal,
							tgl_dinas 			: tgl_dinas,
							tgl_pengajuan		: tgl_pengajuan,
							lampiran			: lampiran
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