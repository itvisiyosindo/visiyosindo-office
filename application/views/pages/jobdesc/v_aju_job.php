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
<div class="col-xl-12 mb-8 mb-xl-0;" style=" margin: auto;">
	<div class="card-body" style="background-color:#FFFFFF; padding:5%;">
	    <div class="table-responsive">
        <div class="text-center mt-0">
            <h2><font color='#000000' face='Times New Roman'>Lengkapi Data Jobdesk</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'></font>
					</td>
				</tr>
				<tr>											
		</font>
					<td rowspan="5" ></td>
					
					<tr>
						<td width="10%">Nama Karyawan</td>
						<td style="text-align:left; ">
						:&nbsp;&nbsp;
                        		<select name="id_pengguna" id="id_pengguna" style="background-color: transparent; border: none;">
                                    <option value="">Pilih Nama Karyawan</option>
                                    <?php
                                    //$nama = $this->db->get('pengguna')->where('pengguna.status=1')->result();
                                    foreach ($list_nama as $row) {
                                    ?>
                                    <option value="<?= $row->pengguna_id ?>"><?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                        	
						</td>
					</tr>
					

										<tr>
						<td width="15%">Berlaku Dari Tanggal</td>
						<td style="text-align:left;">
							:&nbsp;&nbsp;
							<input type="date" name="tgl_mulai" id="tgl_mulai" value="<?= date('Y-m-d') ?>" style="width: 200px; padding: 4px; border: 1px solid #ccc; border-radius: 4px;" required>
						</td>
					</tr>
					<tr>
						<td width="15%">Berlaku Sampai Tanggal</td>
						<td style="text-align:left;">
							:&nbsp;&nbsp;
							<input type="date" name="tgl_selesai" id="tgl_selesai" value="2099-12-31" style="width: 200px; padding: 4px; border: 1px solid #ccc; border-radius: 4px;" required>
							<small class="text-muted" style="margin-left: 10px;">*(Biarkan 2099-12-31 jika berlaku seterusnya)</small>
						</td>
					</tr>
				</tr>
			</table>
		
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" width="10%" bgcolor="#d3d3d3"> Nomor Urut </th>
                                <th style="text-align:center" width="15%" bgcolor="#d3d3d3"> Point (Pengelompokan dengan Sub Jobdesc)</th>
                                <th style="text-align:center" bgcolor="#d3d3d3"> Deskripsi Pekerjaan </th>
                                <th style="text-align:center" width="15%"  bgcolor="#d3d3d3"> Penilaian </th>
                            </tr>
                        </thead>
                        <tbody>
									<?php
										$itung = 1;
										for($x=1;$x<=25;$x++){
										$itung = $x;
									?>
								<tr>
                                    <!--<td style="text-align:center">
																			<?= $x ?>
																		</td>-->
																		<td><input type="text" id="<?= 'idurut_'.$x ?>" Style="width:100%" required></td>
																		<td><input type="text" id="<?= 'point_'.$x ?>" Style="width:100%" required></td>
																		<td><input type="text" id="<?= 'deskripsi_'.$x ?>" Style="width:100%" required></td>
																		<td>
                                    	<select class="select-transaction input-group-sm form-control" id="<?= 'nilai_'.$x ?>" >
																				<option value ="2">Tidak Dinilai</option>
																				<option value ="1">Dinilai</option>
																			</select>
                                    </td>
                                </tr>
										<?php } ?>
										<input type="hidden" id="itung" value="<?= $itung ?>">
                        </tbody>
                        
			</table>
			<br>
			
			<button type="button" class="btn btn-success" onclick="myFunction1()">Tambah Baris</button>
			<button type="button" class="btn btn-danger" onclick="myDeleteFunction1()">	Hapus Baris</button>



        
			</div>
			<?= form_close(); ?>
			<br><br>
		<div role="document">
			<button type="button" class="btn btn-success btn-save float-right btn-ajukan" style="margin-left:12px;"> <i class="fas fa-check"></i> Simpan </button>
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>

<script>
	var x = <?= $itung ?>;
		x = parseInt(x)+2;
		function myFunction1() {
		  var urut1 	= x - 1;
		  var table = document.getElementById("kt_table_1");
		  var row 	= table.insertRow(x);
		  x = x + 1;
		  var cell1 = row.insertCell(0);
		  var cell2 = row.insertCell(1);
		  var cell3 = row.insertCell(2);
		  var cell4 = row.insertCell(3);
		  
		  // x = x+1;
		  //cell1.innerHTML = "<center>"+urut1+"</center>";
		  cell1.innerHTML = "<input type='text' id='idurut_"+urut1+"' Style='width:100%'>";
		  cell2.innerHTML = "<input type='text' id='point_"+urut1+"' Style='width:100%'>";
		  cell3.innerHTML = "<input type='text' id='deskripsi_"+urut1+"' Style='width:100%'>";
			cell4.innerHTML = `<select class="select-transaction input-group-sm form-control" id="nilai_${urut1}" style="width:100%">
					<option value="2">Tidak Dinilai</option>
					<option value="1">Dinilai</option>
			</select>`;
		  document.getElementById('itung').value = urut1;
		}
		
		function myDeleteFunction1() {
			x = x-1;		
			document.getElementById("kt_table_1").deleteRow(x);			
		}
	

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {
			var id_pengguna	= $('#id_pengguna').val();
			if (!id_pengguna) {
				Swal.fire('Perhatian', 'Silakan pilih Nama Karyawan terlebih dahulu!', 'warning');
				return;
			}
			
			let itung_isi = parseInt($('#itung').val()) || 25;
			let des = [];
			let idu = [];
			let poi = [];
			let nil = [];

			for (let i = 1; i <= itung_isi; i++) {
				let el = $('#deskripsi_' + i);
				if (el.length > 0) {
					let deskripsiVal = el.val();
					if (deskripsiVal && deskripsiVal.trim() !== "") {
						des.push(deskripsiVal.trim());
						idu.push($('#idurut_' + i).val() || i);
						poi.push($('#point_' + i).val() || '1');
						nil.push($('#nilai_' + i).val() || '2');
					}
				}
			}			
			
			if (des.length === 0) {
				Swal.fire('Perhatian', 'Silakan isi minimal 1 baris deskripsi pekerjaan!', 'warning');
				return;
			}
			
			Swal.fire({
				title: 'Simpan Jobdesk?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then(function(result) {
				if (result.value) {
					var $btn = $('.btn-ajukan');
					$btn.prop('disabled', true);

					$.ajax({
						method: 'POST',
						url: 'jobdesc/add',
						dataType: 'JSON',
						data: {
							id_pengguna : id_pengguna,
							tgl_mulai   : $('#tgl_mulai').val(),
							tgl_selesai : $('#tgl_selesai').val(),
							deskripsi   : des,
							idurut      : idu,
							point       : poi,
							nilai       : nil,
							csrf_token  : token
						},
						success: function(resp) {
							$btn.prop('disabled', false);
							handleResponse(resp);
						},
						error: function() {
							$btn.prop('disabled', false);
							Swal.fire('Error', 'Terjadi kesalahan saat memproses data pada server', 'error');
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