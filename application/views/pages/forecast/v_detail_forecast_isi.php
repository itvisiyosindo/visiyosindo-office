<header class="page-header">
	<h2><i class="icons icon-layers"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="card-body">

        <?php
				$ttd = "ttd_1";
					if((sessPenggunaId() == '756')){
						$ttd = 'ttd_1';
					}else if((sessPenggunaId() == '23')){
						$ttd = 'ttd_2';
					}else if((sessPenggunaId() == '54')){
						$ttd = 'ttd_3';
					}
			?>
		<input type="hidden" id="level_ttd" value="<?= $ttd ?>"> 
		<input type="hidden" name="id" id="id" value="<?= $forecast->id ?>">

        <!-- Judul dan kode di tengah -->
        <div class="text-center mb-4">
            <h3 style="color:black;"><b>PENGAJUAN FORECAST</b></h3>
            <h4 style="color:black;">Kode : <span id="kode_forecast"><?= $forecast->kode ?? '-' ?></span><h4>
        </div>

        <!-- Isi pengajuan di kiri -->
        <div class="text-left">
            <h5 style="margin:0; color:black;">Dengan ini saya mengajukan Forecast :</h5>
            <h5 style="margin:0; color:black;">&nbsp;&nbsp;&nbsp;&nbsp;Nama &nbsp;&nbsp;&nbsp;&nbsp;: <?= $forecast->nama ?></h5>
            <h5 style="margin:0; color:black;">&nbsp;&nbsp;&nbsp;&nbsp;Jabatan : <?= $forecast->jabatan ?></h5>
        </div>


	<br>
	<br>
        
       <div class="table-responsive">
             <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">
                    Detail Barang Forecast <strong>(Kategori <?= $nama_kategori ?>)</strong>
                </h4>
                <input type="hidden" class="id_kategori" value="<?= $id_kategori ?>">

                <!-- Input pencarian di kanan -->
                <div class="input-group" style="width: 250px;">
                <span class="input-group-text">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchNamaBarang" class="form-control" placeholder="Cari Nama Barang...">
                    
                </div>
            </div>

            <table class="table table-bordered table-striped text-center" id="tabel-forecast">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th hidden>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Terjual <?= $forecast->tahun_a  ?></th>
                        <th>Terjual <?= $forecast->tahun_b  ?></th>
                        <th>Total Stok</th>
                        <th>Demo</th>
                        <th>Barang Customer</th>
                        <th>Permintaan PO</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $total_permintaan = 0;
                    if (!empty($data_forecast)) {
                        foreach ($data_forecast as $row) { 
                            if ($forecast->status != 0) {
                                $total_permintaan += (int) $row->permintaan;
                            } else {
                                $total_permintaan += (int) $row->permintaan;
                            }
                            
                            ?>

                        
                            <tr>
                                <td><?= $no++ ?></td>
                                <td hidden>
                                    <input type="hidden" class="id_barang" value="<?= $row->id_barang ?>">
                                </td>
                                <td class="nama_barang"><?= $row->nama_barang ?></td>
                                <td class="satuan"><?= $row->nama_satuan ?></td>
                                <td class="terjual_lalu"><?= $row->terjual_tahun_lalu ?></td>
                                <td class="terjual_ini"><?= $row->terjual_tahun_ini ?></td>
                                <td class="stok"><?= $row->total_stok ?></td>
                                <td class="demo"><?= $row->demo_stok ?></td>
                                <td class="customer"><?= $row->barang_customer_stok ?></td>
                                <?php if($forecast->status == 0){ ?>   
                                <td>
                                    <input type="number" min="0" class="form-control permintaan" placeholder="Jumlah PO" value="<?= $row->permintaan ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control keterangan" placeholder="Keterangan" value="<?= $row->keterangan ?>">
                                </td>                                
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <?php }else{ ?>
                                <td><?= $row->permintaan ?></td>
                                <td class="text-left"><?= $row->keterangan ?></td>
                                <td>-</td>
                                <?php } ?>
                            </tr>
                    <?php } ?>
                        <!-- baris total -->
                        <tr class="table-secondary fw-bold">
                            <td colspan="8" class="text-right"><strong>Total :</strong></td>
                            <td><strong><?= $total_permintaan ?></strong></td>
                            <td colspan="2"></td>
                        </tr>
                <?php } else { ?>
                        <tr>
                            <td colspan="12" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br><br><br>

        <?php if($forecast->status != 0){ ?>  
            <table border="0" style="width:100%; font-family:'Times New Roman', serif; color:black; font-size:15px;">
				<tbody>
					<tr style="height: 35px;">
						<td style="width:100%; height:35px; text-align:right; vertical-align:top;" colspan="7">
							Pekanbaru, <?= $forecast->created_at ?>
						</td>
					</tr>
					<tr style="height: 18px;">
						<td style="text-align:center; width:25.5%;" colspan="2">Diajukan Oleh,</td>
						<td style="text-align:center; width:49%;" colspan="3">Diverifikasi Oleh,</td>
						<td style="text-align:center; width:25.5%;" colspan="2">Disetujui Oleh,</td>
					</tr>
					<tr style="height:60px;">
						<?php
							$img_path 	= "uploads/file_karyawan/ttd/";
							$ttdaju		= $img_path."ttd_".$forecast->id_pengaju.".png";
							$ttd1 		= $img_path."ttd_notyet2.png";
							$ttd2 		= $img_path."ttd_notyet2.png";
							$ttd3 		= $img_path."ttd_notyet2.png";
							
							if($forecast->ttd_1 == '1'){
								$ttd1 = $img_path."ttd_766.png";
							}else if($forecast->ttd_1 == '2'){
								$ttd1 = $img_path."ttd_not.png";
							}
							if($forecast->ttd_2 == '1'){
								$ttd2 = $img_path."ttd_23.png";
							}else if($forecast->ttd_2 == '2'){
								$ttd2 = $img_path."ttd_not.png";
							}
							if($forecast->ttd_3 == '1'){
								$ttd3 = $img_path."ttd_54.png";
							}else if($forecast->ttd_3 == '2'){
								$ttd3 = $img_path."ttd_not.png";
							}
						?>
						<td style="text-align:center; width:23%;"> <?php echo'<img src="'.$ttdaju.'" height="70">';?> </td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd1.'" height="70">';?></td>
						<td style="text-align:center; width:2%;"></td>
						<td style="text-align:center; width:24%;"><?php echo'<img src="'.$ttd2.'" height="70">';?></td>
						<td style="text-align:center; width:2.5%;"></td>
						<td style="text-align:center; width:23%;"><?php echo'<img src="'.$ttd3.'" height="70">';?></td>
					</tr>
                    <tr>
                        <td style="text-align:center; padding:0;">
                            <?= $forecast->nama ?>
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                        <td></td>
                        <td style="text-align:center; padding:0;">
                            Afylmardopila
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                        <td></td>
                        <td style="text-align:center; padding:0;">
                            Meilina Safitri
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                        <td></td>
                        <td style="text-align:center; padding:0;">
                            Bob Ariyos
                            <hr style="margin:1px 0; border:1px solid black;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i><?= $forecast->jabatan ?></i>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i>Regulatory & Import</i>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i>Director of Corporate Planning & Business Management</i>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:top; padding-top:2px;">
                            <i>Director</i>
                        </td>
                    </tr>

					<!--<tr>
						<td style="text-align:center; "><?= $forecast->nama ?><hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Siti Safrida<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Meilina Safitri<hr></hr></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; ">Bob Ariyos<hr></hr></td>
					</tr>
					<tr>
						<td style="text-align:center; vertical-align:top;"><i><?= $forecast->jabatan ?></i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Regulatory & Import</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director of Corporate Planning & Business Management</i></td>
						<td style="text-align:center; "></td>
						<td style="text-align:center; vertical-align:top;"><i>Director</i></td>
					</tr>-->
				</tbody>
			</table>
			<br><br>
        <?php } ?>


        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
    <!-- Tombol kembali di kiri -->
    <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form">
        <i class="fa fa-arrow-left"></i> Kembali
    </button>

    <!-- Tombol di kanan -->
    <div class="d-flex gap-2">
        <a href="<?= base_url('forecast/print_page/'.$forecast->id) ?>" target="_blank" 
            class="btn btn-warning">
            <i class="fa fa-print"></i> Cetak
        </a>

        &nbsp;&nbsp;
        <?php if($forecast->status == 0){ 
            if(sessPenggunaId() == $forecast->id_pengaju){ ?>                    
            <button type="button" id="btn-update" class="btn btn-warning">
                <i class="fa fa-sync"></i> Update Draft
            </button>
            &nbsp;&nbsp;
            <button type="button" class="btn btn-success btn-ajukan" id-po="<?=encrypt($forecast->id)?>"> 
                <i class="fas fa-paper-plane"></i> Ajukan
            </button> 
            &nbsp;&nbsp;
        <?php 
            }
        }else{ ?>
            <?php if (sessPenggunaId() == '766' || sessPenggunaId() == '23' || sessPenggunaId() == '54') { ?>
                <button type="button" class="btn btn-secondary btn-denial" id-po="<?=encrypt($forecast->id)?>"> 
                    <i class="fas fa-times"></i> Tolak
                </button>  
                &nbsp;&nbsp;    
                <button type="button" class="btn btn-success btn-approval" id-po="<?=encrypt($forecast->id)?>"> 
                    <i class="fas fa-check"></i> Setujui
                </button>          
            <?php } ?>
        <?php } ?>
    </div>
</div>




    </div>

	<br>





            </div>
        </div>

    </div>
</div>



<script>
 
document.addEventListener('DOMContentLoaded', function() {

		// Hapus baris
        //$(document).on('click', '.btn-hapus', function() {
        //    $(this).closest('tr').remove();
        //});

        // Hapus baris + update ke server
        $(document).on('click', '.btn-hapus', function() {
            var row = $(this).closest('tr');
            var id_forecast = $('#id').val();
            var id_barang = row.find('.id_barang').val();

            Swal.fire({
                title: 'Yakin hapus item ini?',
                text: "Data akan ditandai status=0",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hapus di tampilan
                    row.remove();

                    // AJAX update status ke 0
                    $.ajax({
                        method: 'POST',
                        url: "<?= base_url('forecast/hapus_detail') ?>",
                        dataType: 'JSON',
                        data: {id_forecast: id_forecast, id_barang: id_barang},
                        success: function(res) {
                            if(res.status === 'success'){
                                Swal.fire('Berhasil', res.message, 'success');
                            } else {
                                Swal.fire('Gagal', res.message || 'Terjadi kesalahan.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Tidak dapat menghubungi server.', 'error');
                        }
                    });
                }
            });
        });


        // Filter pencarian berdasarkan nama barang
        $('#searchNamaBarang').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tabel-forecast tbody tr').filter(function() {
                $(this).toggle($(this).find('.nama_barang').text().toLowerCase().indexOf(value) > -1)
            });
        });




       

        // UPDATE DRAFT
        $('#btn-update').click(function () {
            var id_forecast = $('#id').val();
            var details = [];

            $('#tabel-forecast tbody tr').each(function () {
                var row = $(this);
                if (row.find('.id_barang').length > 0) {
                    details.push({
                        id_barang: row.find('.id_barang').val(),
                        permintaan: row.find('.permintaan').val(),
                        keterangan: row.find('.keterangan').val()
                    });
                }
            });

            if (details.length === 0) {
                Swal.fire('Oops', 'Tidak ada data untuk diperbarui!', 'warning');
                return;
            }

            Swal.fire({
                title: 'Update Draft Pengajuan Forecast?',
                text: "Data forecast akan diperbarui",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'POST',
                        url: "<?= base_url('forecast/update_detail') ?>",
                        dataType: 'JSON',
                        data: {id_forecast: id_forecast, details: details},
                        success: function (res) {
                            if(res.status === 'success'){
                                Swal.fire('Berhasil', res.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', res.message || 'Terjadi kesalahan saat update.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Tidak dapat menghubungi server.', 'error');
                        }
                    });
                }
            });
        });


        //Approval
        var level_ttd = $('#level_ttd').val();	

        $(document).on('click', '.btn-ajukan', function() {
        	var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Ajukan Permintaan Forecast?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'forecast/ajukan',
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
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Setujui permintaan Forecast?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'forecast/ttd_setujui/'+level_ttd,
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


        $(document).on('click', '.btn-denial', function() {
			var id = $('#id').val();
			
            Swal.fire({
                //title: approval + ' absensi?',
				title: 'Tolak permintaan Forecast?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'forecast/ttd_tolak/'+level_ttd,
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


    var BASE_URL = '<?= base_url(); ?>';

    function goBack() {
        window.location.href = BASE_URL + 'forecast/show/list';
    }

	
</script>