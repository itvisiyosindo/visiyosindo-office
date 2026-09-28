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
            <h2><font color='#000000' face='Times New Roman'><u>No : <?= $data_po[0]->kode_stb ?></u></font></h2>
        </div>
			
		
			<?php
				$dt_pengajuan	= strtotime($data_po[0]->tgl_Pengajuan);
				$tgl_pengajuan 	= date("d", $dt_pengajuan)." - ".date("m", $dt_pengajuan)." - ".date("Y", $dt_pengajuan);

									$hari 	= date("D", $dt_pengajuan);
									$tgl 	= date("d", $dt_pengajuan);
									$bulan 	= date("M", $dt_pengajuan);
									$thn 	= date("Y", $dt_pengajuan);
									switch($hari){
										case 'Sun':
											$hari = "Minggu";
										break;
										case 'Mon':         
											$hari = "Senin";
										break;
										case 'Tue':
											$hari = "Selasa";
										break;
										case 'Wed':
											$hari = "Rabu";
										break;
										case 'Thu':
											$hari = "Kamis";
										break;
										case 'Fri':
											$hari = "Jumat";
										break;
										case 'Sat':
											$hari = "Sabtu";
										break;									
									}

									switch($bulan) {
										case 'Jan':
												$bulan = "Januari";
												break;
										case 'Feb':
												$bulan = "Februari";
												break;
										case 'Mar':
												$bulan = "Maret";
												break;
										case 'Apr':
												$bulan = "April";
												break;
										case 'May':
												$bulan = "Mei";
												break;
										case 'Jun':
												$bulan = "Juni";
												break;
										case 'Jul':
												$bulan = "Juli";
												break;
										case 'Aug':
												$bulan = "Agustus";
												break;
										case 'Sep':
												$bulan = "September";
												break;
										case 'Oct':
												$bulan = "Oktober";
												break;
										case 'Nov':
												$bulan = "November";
												break;
										case 'Dec':
												$bulan = "Desember";
												break;
								}
			?>
		<font color='#000000'>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>
					<td colspan="3" style="text-align:left">
						<font color='#000000'><br>Pada hari ini &nbsp;&nbsp; <strong><?= $hari ?></strong>, Tanggal &nbsp;&nbsp; <strong><?= $tgl ?></strong> &nbsp;&nbsp; Bulan &nbsp;&nbsp; <strong><?= $bulan ?></strong> &nbsp;&nbsp; Tahun &nbsp;&nbsp; <strong><?= $thn ?></strong>, kami yang bertanda tangan dibawah ini:</font>
					</td>
				</tr>
				<tr>											
		</font>
					<tr>
						<td>Nama </td>
						<td>:&nbsp;&nbsp;<?= $data_po[0]->nama_pihak1 ?></td>
					</tr>
					<tr>
						<td>Alamat </td>
						<td>:&nbsp;&nbsp;<?= $data_po[0]->alamat_pihak1 ?></td>
					</tr>
					
					<tr>
						<td>Disebut sebagai </td>
						<td><b>"PIHAK PERTAMA"<b></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td>Nama </td>
						<td>:&nbsp;&nbsp;<?= $data_po[0]->nama_customer ?></td>
					</tr>
					<tr>
						<td>Alamat </td>
						<td>:&nbsp;&nbsp;<?= $data_po[0]->alamat_customer ?></td>
					</tr>
					<tr>
						<td>Disebut sebagai </td>
						<td><b>"PIHAK KEDUA"<b></td>
					</tr>
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					<tr>
						<td colspan="3" style="text-align:left">
						<font color='#000000'>Dengan ini Pihak Pertama telah menyerahkan kepada Pihak Kedua berupa :
					</td>
				</tr>
				<tr>	
					<tr>
						<td><font color="white">i </font></td>
					</tr>
				</tr>
			</table>
								
			<button type="button" class="btn btn-sm btn-primary btn-addDetail mb-2" data-id="<?= $data_po[0]->id_stb ?>"> <i class="bx bx-plus"></i> Tambah </button>
			<br>
			<table id="kt_table_1" style="font-family:Times New Roman; font-color:black; font-size:15px" border="1" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center" width="3%"> No </th>
                                <th style="text-align:center"> Nama Barang </th>
                                <th style="text-align:center" width="15%"> Merk </th>
                                <th style="text-align:center" width="15%"> No Batch</th>
                                <th style="text-align:center" width="7%"> QTY</th>
                                <th style="text-align:center" width="15%"> SATUAN </th>
                                <th style="text-align:center" width="23%"> KET </th>
                                <th style="text-align:center" width="7%"> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
							<?php 
								$x=1;
								$xy=0;
								foreach ($data_detail as $row) {
									$x = $x+1;
									$xy = $xy+1;


									$id = encrypt($row->id_dstb);
									
							?>
																<tr>
                                   	<td class="nomor"  style="text-align:center">&nbsp;<?= $row->nomor ?>&nbsp;</td>
                                   	<td class="nama" >&nbsp;<?= $row->nama_barang ?>&nbsp;</td>
                                   	<td class="merk" style="text-align:center">&nbsp;<?= $row->merk ?>&nbsp;</td>
                                   	<td class="nobatch" style="text-align:center">&nbsp;<?= $row->no_batch ?>&nbsp;</td>
                                   	<td class="qty" style="text-align:center">&nbsp;<?= $row->qty ?>&nbsp;</td>
                                    <td class="satuan" style="text-align:center">&nbsp;<?= $row->satuan ?>&nbsp;</td>
                                    <td class="ket" style="text-align:center">&nbsp;<?= $row->ket ?>&nbsp;</td>
                                    <!--<td class="keterangan2" style="text-align:center">&nbsp;<?= $row->keterangan2 ?>&nbsp;</td>-->
																		<td class="aksi" style="text-align:center">
																			<button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $id ?>">
																				<i class="bx bx-pencil"></i>
																			</button>
																			
																			<button type="button" class="btn btn-sm btn-danger btn_deleteData" data-id="<?= $row->id_dstb ?>">
																					<i class="bx bx-trash"></i>
																			</button>
																		</td>
                                </tr>
                            <?php } ?>							
                        </tbody>
                        
			</table>
			<br>
			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>	
						<td colspan="3" style="text-align:left">
									<font color='#000000'><br>Demikian surat serah terima barang ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</font>
						</td>
				</tr>
				<tr>
						<td><font color="white">i </font></td>
				</tr>
				<tr>
						<td><font color="white">i </font></td>
				</tr>
						
			</table>



            <table border="0" style="width:100%; font-family:Times New Roman; font-color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="9">
							<?= $data_po[0]->kota_pengajuan ?>, <?= $tgl_pengajuan ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;">Yang Menerima</td>
						<td style="text-align:center; width:60%;"></td>
						<td style="text-align:center; width:20%;">Yang Menyerahkan</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:20%;"><?= $data_po[0]->nama_customer ?></td>
						<td style="text-align:center; width:60%;"></td>
						<td style="text-align:center; width:20%;"><?= $data_po[0]->nama_pihak1 ?></td>
					</tr>
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$data_po[0]->idPengaju.".png";
							
						if ($data_po[0]->id_pihak1 == 325){
						
						?>
						
					<tr style="height:60px;">
						<td style="text-align:center; width:20%;"></td>
						<td style="text-align:center; width:60%;"></td>
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
					</tr>
					<tr>
						<td style="text-align:center; "><font color="white">i </font><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><?= $data_po[0]->pengaju ?><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_po[0]->jabatan ?></i></td>
					</tr>

					<?php }else if ($data_po[0]->id_customer == 325){ ?>

					<tr style="height:60px;">
						<td style="text-align:center; width:20%;"><?php echo'<img src="'.$ttdaju.'" height="70">';?></td>
						<td style="text-align:center; width:60%;"></td>
						<td style="text-align:center; width:20%;"></td>
					</tr>
					<tr>
						<td style="text-align:center; "><?= $data_po[0]->pengaju ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; "><font color="white">i </font><hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $data_po[0]->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"></td>
					</tr>

						<?php } ?>
				</tbody>
			</table>
			<br><br>
			</div>
			<br><br>
		<div role="document">
			<a href="serah_terima_barang/print_page/stb/<?=$data_po[0]->id_stb?>" class="btn btn-warning float-right" style="margin-left:12px; margin-top:12px;"> <i class="fas fa-print"></i> Cetak </a>
      
			<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
            <br>
        </div>
		<br><br>
		</div>
		
    </div>	
</div>

<div id="main-modal-calonedit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Catatan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-calonedit', 'autocomplete' => 'off')); ?> 
				<div class="modal-body">
					<div class="dt-calonpelangganedit-form">
						<div class="form-group">
							<div hidden>
								<input type="text" class="form-control col-sm-5" id="idcalonedit" name="idcalonedit" readonly required/>
							</div>
						</div>
						<div class="form-group">
							<div>
								<label for="nomor" class="form-control-label">Nomor :</label>
								<input type="text" class="form-control" id="nomor" name="nomor" required/>
							</div>
						</div>
						<div class="form-group">
							<div>
								<label for="namabarang" class="form-control-label">Nama Barang :</label>
								<input type="text" class="form-control" id="namabarang" name="namabarang" required/>
							</div>
						</div>
						<div class="form-group">
							<label for="merk" class="form-control-label">Merk<span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="merk" name="merk" required>
						</div>
						<div class="form-group">
							<label for="nobatch" class="form-control-label">No Batch<span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="nobatch" name="nobatch" required>
						</div>
						<div class="form-group">
							<label for="qty" class="form-control-label">QTY<span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="qty" name="qty" required>
						</div>
						<div class="form-group">
							<label for="satuan" class="form-control-label">Satuan<span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="satuan" name="satuan" required>
						</div>
						<div class="form-group">
							<label for="ket" class="form-control-label">Keterangan<span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="ket" name="ket" required>
						</div>
						
							
					<div>	
						<div class="modal-footer">
								<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
								<button type="button" id="btn-add-form-pelanggan" class="btn btn-success" style="display:none;">Simpan</button>
								<button type="button" id="btn-edit-form-pelanggan" class="btn btn-success btn-edit" data-dismiss="modal">Update</button>
						</div>
											
				</div>
			
			<?= form_close(); ?>
		</div>
	</div>
</div>



<script>
	

    document.addEventListener('DOMContentLoaded', function() {
        
		var level_ttd = $('#level_ttd').val();	
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui permohonan Purchase Order?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'purchase_order/ttd_setujui/po/'+level_ttd,
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

		$(document).on('click', '.btn-submit', function() {
        	
        	var id = $('#id').val();
        	var no_po = $('#no_po').val();
        	var lampiran_finance = $('#lampiran_finance').val();

        	// var level_ttd = $('#level_ttd').val();
        	// console.log(approvall);
        	Swal.fire({
        		title: 'Submit No PO dan Link Lampiran?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
        	}).then(function(result) {
        		if (result.value) {	
        			// console.log('' + trf_komisi);
                    $.ajax({
                        method: 'POST',
                        url: 'purchase_order/updateFinance/'+id,
                        dataType: 'JSON',
                        data: {
							no_po : no_po,
							lampiran_finance : lampiran_finance,
							csrf_token	: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })					
                }
        	})
        })
        
		
		$(document).on('click', '.btn-denial', function() {
			var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Tolak permohonan Purchase Order?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'purchase_order/ttd_tolak/po/'+level_ttd,
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

				$(document).on('click', '.btn-edit', function() {
			
					var par = $(this).data("id"); 
					var url ="serah_terima_barang/editDetail/"+par
					
					$.ajax({
							type: "GET",
							url: url,
							success: function(response) {     
								//console.log(response)  	
								if (response) {
									  result = JSON.parse(response);
										$('#main-modal-calonedit #idcalonedit').val(result['id_dstb']);
										$('#main-modal-calonedit #nomor').val(result['nomor']);
										$('#main-modal-calonedit #namabarang').val(result['nama_barang']);
										$('#main-modal-calonedit #merk').val(result['merk']);
										$('#main-modal-calonedit #nobatch').val(result['no_batch']);
										$('#main-modal-calonedit #qty').val(result['qty']);
										$('#main-modal-calonedit #satuan').val(result['satuan']);
										$('#main-modal-calonedit #ket').val(result['ket']);
										$('#main-modal-calonedit').modal();
								}
    					},
							error: function (request, status, error) {
									alert(request.responseText);
							}					
					});
					
					
				})

				$('#btn-edit-form-pelanggan').click(function() {
					$.ajax({
									type: "POST",
									url: "serah_terima_barang/updateDetail",
									cache: false,
									data: {
											id: $('#main-modal-calonedit  #idcalonedit').val(),
											nomor: $('#main-modal-calonedit #nomor').val(),
											namabarang: $('#main-modal-calonedit #namabarang').val(),
											merk: $('#main-modal-calonedit #merk').val(),
											nobatch: $('#main-modal-calonedit #nobatch').val(), 
											qty: $('#main-modal-calonedit #qty').val(), 
											satuan: $('#main-modal-calonedit #satuan').val(), 
											ket: $('#main-modal-calonedit #ket').val(),      
											
									},
									success: function(result) { 
											var pesan = $.parseJSON(result)
											Swal.fire({
												type: 'success',
												icon: 'success',
												title: pesan.msg,
												showConfirmButton: false,
												timer: 2000
										});
									},						
						});
					//	window.location.reload();
				})


			$(document).on('click', '.btn_deleteData', function() {
				var id = $(this).data("id");

				Swal.fire({
						title: 'Yakin hapus data ini?',
						text: "Data tidak benar-benar dihapus, hanya diset status = 0.",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#d33',
						cancelButtonColor: '#3085d6',
						confirmButtonText: 'Ya, Hapus!',
						cancelButtonText: 'Batal'
				}).then((result) => {
						if (result.isConfirmed) {
								$.ajax({
										type: "POST",
										url: "serah_terima_barang/deleteDetail",
										data: {
												id: id,
												csrf_token: token
										},
										success: function(response) {
												var pesan = $.parseJSON(response);
												Swal.fire({
														icon: 'success',
														title: pesan.msg,
														showConfirmButton: false,
														timer: 2000
												});
												// reload tabel
												setTimeout(() => {
														window.location.reload();
												}, 1500);
										},
										error: function(xhr) {
												Swal.fire({
														icon: 'error',
														title: 'Gagal',
														text: 'Terjadi kesalahan: ' + xhr.responseText
												});
										}
								});
						}
				});
		});



		// Tambah detail
		$(document).on('click', '.btn-addDetail', function() {
				var id_stb = $(this).data("id");

				// reset form
				$('#main-modal-calonedit input').val('');
				$('#main-modal-calonedit #idcalonedit').val(''); // pastikan kosong
				$('#main-modal-calonedit').modal();

				// ganti tombol modal biar tahu ini mode tambah
				$('#btn-edit-form-pelanggan')
						.hide(); // sembunyikan tombol update
				$('#btn-add-form-pelanggan')
						.show()
						.data('id-stb', id_stb);
		});

		// Simpan detail baru
		$(document).on('click', '#btn-add-form-pelanggan', function() {
				var id_stb = $(this).data("id-stb");

				$.ajax({
						type: "POST",
						url: "serah_terima_barang/addDetail",
						data: {
								id_stb: id_stb,
								nomor: $('#main-modal-calonedit #nomor').val(),
								namabarang: $('#main-modal-calonedit #namabarang').val(),
								merk: $('#main-modal-calonedit #merk').val(),
								nobatch: $('#main-modal-calonedit #nobatch').val(),
								qty: $('#main-modal-calonedit #qty').val(),
								satuan: $('#main-modal-calonedit #satuan').val(),
								ket: $('#main-modal-calonedit #ket').val(),
								csrf_token: token
						},
						success: function(result) {
								var pesan = $.parseJSON(result);
								Swal.fire({
										icon: 'success',
										title: pesan.msg,
										showConfirmButton: false,
										timer: 2000
								});
								setTimeout(() => {
										window.location.reload();
								}, 1500);
						},
						error: function(xhr) {
								Swal.fire({
										icon: 'error',
										title: 'Gagal',
										text: xhr.responseText
								});
						}
				});
		});




		
		
})

	

    function goBack() {
        window.history.back();
    }
</script>