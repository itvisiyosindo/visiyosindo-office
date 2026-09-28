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
	<div class="card-body col-12 offset" style="background-color:#FFFFFF; padding:5%;">
	     <div>
        	<table style="font-family:Times New Roman; color:black; font-size:13px;"  border="0" width="100%" >
                <tr>
                  <th>
                    <img src="assets/img/kop_surat_vym_underline.png" width="100%" height="50%" />
                  </th>
                </tr>
                <tr>
                  <th>
                    <div style="text-align:center">
                      <h3>
                        <font color="black"><u><?= $page_desc ?></font></u>
                      </h3>
                    </div>
                  </th>
            </table>
        </div>
	    <div>
    		<div class="form-group">
    			<?= form_open('#', array('id' => 'a-pb-form', 'autocomplete' => 'off')); ?>
                <!-- TABEL SURAT PENGAJUAN -->
                	<table style="font-family:Times New Roman; color:black; font-size:13px;"  border="0" width="100%" >
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
                        		    <textarea name="alasan" class="form-control" id="alasan" cols="10" rows="1"></textarea>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td colspan="4">
                        		    Dengan ini saya memohon izin selama <input type="number" Style="width:13%; text-align:center;font-weight: bold;" name="hari" id="hari" value="11"> hari mulai tanggal <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"yyyy-mm-dd"}' id="tgl_mulai" Style="width:25%; text-align:center;font-weight: bold;" placeholder="Pilih"> hingga tanggal <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"yyyy-mm-dd"}' id="tgl_akhir" Style="width:25%; text-align:center;font-weight: bold;" placeholder="Pilih"> 
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
                        </tbody>
			        </table>
			        <table style="font-family:Times New Roman; color:black; font-size:13px;"  border="0" width="auto" align="right">
			             <tr>
                    		&nbsp;&nbsp;<td style="text-align:center;">
                        		    <select id="kota" style="text-align:right; width:auto; border:0px; margin:0px; position:static;" required>
        								<option value="">Pilih Kota</option>
            								<?php
            								foreach ($list_kota as $row) {
            									echo "<option value='".$row->id."PengHubunG".$row->nama."'>".$row->nama."</option>";
            								}
            								?>
        							</select  <span>,</span>  <input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"yyyy-mm-dd"}' id="tgl_pengajuan" Style="width:30%; text-align:center;font-weight: bold; position:static;" placeholder="Pengajuan" required>
        							</br>
        							<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PT. Visi Yosindo Medikal</span>
                            </td>
                            
                    	</tr>
                    	<tr>

                    	</tr>
			        </table>
			        <table  style="font-family:Times New Roman; color:black; font-size:13px;"  border="0" width="auto" align="right">
                    	<tr style="height:100px;">
                            <?php
                            $img_path   = "uploads/file_karyawan/ttd/";
                            $ttd3       = $img_path."ttd_blank.png";
                            ?>
                            <td colspan="3"></td>
                            <td style="text-align:center; "><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
                            
                    	</tr>
        			</table>
        			<table width="100%" style="font-family:Times New Roman; color:black; font-size:13px;"  border="0" width="100%">
                    	<tr>
                    		<td style="text-align:center; "><b><u><?= $pengguna[0]->nama ?></u></b></td>
                    		<td style="text-align:center; "><b><u>M. Syarif Hidayatullah</u></b></td>
                    		<td style="text-align:center; "><b><u>Yolanda Pratiwi</u></b></td>
                    	</tr>
                    	<tr>
                    	    <td style="text-align:center; vertical-align:top;"><i><?= $pengguna[0]->jabatan ?></i></td>
                    		<td style="text-align:center; vertical-align:top;"><i>Legal & HR</i></td>
                    		<td style="text-align:center; vertical-align:top;"><i>Pimpinan Umum</i></td>
                    	</tr>
                    	<tr>
                    		<td ><font color="white">i </font></td>
                    	</tr>
        			</table>
        			<table>
        			    <tr>
                    		<td><i>Tembusan :</i></td>
                    	</tr>
                    	<tr>
                    		<td style="text-align:left; vertical-align:top;"><i>&emsp;&emsp;1. Director</i></td>
                    	</tr>
                    	<tr>
                    		<td ><font color="white">i </font></td>
                    	</tr>
                    	<tr>
                    		<td >
                    		    Lampiran  :
                    		    <input class=myinput"" type="text" name="lampiran" id="lampiran" placeholder="Pastekan link lampiran anda" required>
                    		</td>
                    	</tr>
        			</table>
        			<br>
                    <!-- PENUTUP SURAT PENGANTAR DINAS -->
        			<br><br>
        			</div>
        			<br><br>
            		<div role="document">
            			<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Ajukan </button>
                        <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
                    </div>
            		<br><br>
                <?= form_close(); ?>
            </div
        </div>    
    </div>	
</div>
<script>
	let todayMulai = new Date();
	let todayAkhir = new Date();
	let hari =0;
	// PENUTP FUNGSI TAMBAH BUTTON
    document.addEventListener('DOMContentLoaded', function() {
        
        $('#tgl_mulai').val(todayMulai.toISOString().split('T')[0]);
        $('#tgl_akhir').val(todayAkhir.toISOString().split('T')[0]);
        let Result = Math.round((todayAkhir.getTime() - todayAkhir.getTime()))+1;
        let hari = (Result.toFixed(0));
        $('#hari').val(hari)
        
       
        $("#tgl_mulai").datepicker({ 
            dateFormat: "yy-mm-dd", 
            onSelect: function(){
                var selected = $(this).val();
                todayMulai = new Date(selected);
                Result = (Math.abs((todayAkhir.getTime() - todayMulai.getTime()))/(1000 * 60 * 60 * 24))+1;
                hari = (Result.toFixed(0));
                $('#hari').val(hari);
            }
        });
        $("#tgl_akhir").datepicker({ 
            dateFormat: "yy-mm-dd", 
            onSelect: function(){
                var selected = $(this).val();
                todayAkhir = new Date(selected);
                Result = (Math.abs((todayAkhir.getTime() - todayMulai.getTime()))/(1000 * 60 * 60 * 24))+1;
                hari = (Result.toFixed(0));
                $('#hari').val(hari);
            }
        });
        
		$(document).on('click', '.btn-ajukan', function() {
			var perihal 		= 'Surat Izin';			
			var tgl_mulai		= $('#tgl_mulai').val();
			var tgl_akhir   	= $('#tgl_akhir').val();
			var alasan  		= $('#alasan').val();
			var lampiran		= $('#lampiran').val();
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Surat Izin Meninggalkan Pekerjaan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'absensi/addsimp',
                        dataType: 'JSON',
                        data: {
                        	perihal 			: perihal,
							tgl_mulai 			: tgl_mulai,
							tgl_akhir   		: tgl_akhir,
							tgl_pengajuan       : tgl_pengajuan,
							kota                : kota,
							hari                : hari,
							alasan              : alasan,
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