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
            <h2><font color='#000000' face='Times New Roman'>Form PO Pending</font></h2>
        </div>
		<font color='#000000'>
			<?= form_open('#', array('id' => 'a-gc-form', 'autocomplete' => 'off')); ?>
			
		
			
			<br>

			<table id="tbl_1" style="font-family:Times New Roman; font-size:15px" border="0" width="100%" color="red">
				<tr>					
						
					<!--<tr>
						<td>Nama Instansi/Perusahaan</td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="id_pelanggan" id="id_pelanggan"  onchange="setNamaPelanggan()">
                    <option value="">- Pilih Instansi/Perusahaan -</option>
										<?php foreach ($list_custA as $row) { ?>
												<option value="<?= $row->id_pelanggan ?>" data-nama="<?= $row->identitas_pelanggan ?>"><?= $row->identitas_pelanggan ?></option>
										<?php } ?>
                 </select>
								<input type="hidden" name="nama_pelanggan" id="nama_pelanggan" />
						</td>
					</tr>-->

					<td>Nama Instansi/Perusahaan</td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="id_pelanggan" id="id_pelanggan"  onchange="setNamaPelanggan()">
                    <option value="">- Pilih Instansi/Perusahaan -</option>
										<?php foreach ($list_cust as $row) { ?>
												<option value="<?= $row->id_customer ?>" data-nama="<?= $row->nama_customer ?>"><?= $row->nama_customer ?></option>
										<?php } ?>
                 </select>
								<input type="hidden" name="nama_pelanggan" id="nama_pelanggan" />
						</td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>

					<tr>
						<td>Nama Marketing </td>
						<td>:</td>
						<td>
								<select class="select-transaction input-group-sm form-control" name="id_marketing" id="id_marketing" onchange="setNamaMarketing()">
										<option value="">- Pilih Marketing -</option>
										<option value="77777">Office / Kantor Pusat</option>
										<?php foreach ($nama_marketing as $row) { ?>
												<option value="<?= $row->pengguna_id ?>" data-nama="<?= $row->nama ?>"><?= $row->nama ?></option>
										<?php } ?>
								</select>
								<input type="hidden" name="nama_marketing" id="nama_marketing" />
						</td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>

					<tr>
						<td width="25%">Item PO</td>
						<td>:</td>
						<td><input id='item1' type="text" placeholder='Klik Untuk Memasukkan Item 1' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item2' type="text" placeholder='Klik Untuk Memasukkan Item 2' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item3' type="text" placeholder='Klik Untuk Memasukkan Item 3' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item4' type="text" placeholder='Klik Untuk Memasukkan Item 4' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item5' type="text" placeholder='Klik Untuk Memasukkan Item 5' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item6' type="text" placeholder='Klik Untuk Memasukkan Item 6' required></td>
					</tr>
					<tr>
						<td width="25%"></td>
						<td></td>
						<td><input id='item7' type="text" placeholder='Klik Untuk Memasukkan Item 7' required></td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					
					<tr>
						<td width="25%">Tanggal PO</td>
						<td>:</td>
						<td><input type="text" data-plugin-datepicker data-plugin-options='{"orientation":"bottom", "format":"dd-mm-yyyy"}' id="tanggal_po" Style="width:20%; text-align:center" placeholder="Pilih Tanggal"></td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					
					<tr>
						<td>Sistem Pembayaran </td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="sistem_pembayaran" id="sistem_pembayaran" >
                  <option value="">- Pilih -</option>
									<option value = "CASH">CASH</option>
									<option value = "COD">COD</option>
									<option value = "NET 30">NET 30</option>
									<option value = "CREDIT">CREDIT</option>
                </select>
						</td>
					</tr>
					
					<tr>
						<td><font color="white">i </font></td>
					</tr>
					

					<tr>
						<td>Status</td>
						<td>:</td>
						<td>
						    <select class="select-transaction input-group-sm form-control" name="id_status" id="id_status" >
                  <option value="">- Pilih Status -</option>
									<option value = "1">Pending</option>
									<option value = "2">Batal</option>
									<option value = "3">Barang Dalam Pemesanan</option>
									<option value = "4">Barang Ready</option>
									<option value = "5">Menunggu Konfirmasi Customer</option>
									<option value = "6">Dikirim</option>
                </select>
						</td>
					</tr>

					<tr>
						<td><font color="white">i </font></td>
					</tr>
					
					<tr>
						<td width="25%">Remarks</td>
						<td>:</td>
						<td><input id='remarks' type="text" placeholder='Klik Untuk Memasukkan Keterangan' required></td>
					</tr>
					
					

				</tr>
				
				

			</table>



           
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

	function setNamaMarketing() {
        var selectedOption = document.getElementById('id_marketing').selectedOptions[0];
        var namaMarketing = selectedOption ? selectedOption.getAttribute('data-nama') : '';
        document.getElementById('nama_marketing').value = namaMarketing;
    }

	function setNamaPelanggan() {
        var selectedOption = document.getElementById('id_pelanggan').selectedOptions[0];
        var namaPelanggan = selectedOption ? selectedOption.getAttribute('data-nama') : '';
        document.getElementById('nama_pelanggan').value = namaPelanggan;
    }

    document.addEventListener('DOMContentLoaded', function() {
        
		$(document).on('click', '.btn-ajukan', function() {		
			var id_pelanggan		= $('#id_pelanggan').val();
			var nama_pelanggan	= $('#nama_pelanggan').val();
			var id_marketing		= $('#id_marketing').val();
			var nama_marketing	= $('#nama_marketing').val();
			var item1			= $('#item1').val();
			var item2			= $('#item2').val();
			var item3			= $('#item3').val();
			var item4			= $('#item4').val();
			var item5			= $('#item5').val();
			var item6			= $('#item6').val();
			var item7			= $('#item7').val();
			var tanggal_po 	= $('#tanggal_po').val();
			var sistem_pembayaran 	= $('#sistem_pembayaran').val();
			
			var id_status 	= $('#id_status').val();
			var remarks 		= $('#remarks').val();
			
			
			Swal.fire({
                //title: approval + ' absensi?',
				title: 'Data sudah tepat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'inventory_new/addSrt/po_pending',
                        dataType: 'JSON',
                        data: {
							id_pelanggan	 : id_pelanggan,
							nama_pelanggan : nama_pelanggan,
							id_marketing	 : id_marketing,
							nama_marketing : nama_marketing,
							tanggal_po	   : tanggal_po,
							id_status			 : id_status,
							remarks		: remarks,
							item1			: item1,
							item2			: item2,
							item3			: item3,
							item4			: item4,
							item5			: item5,
							item6			: item6,
							item7			: item7,
							sistem_pembayaran	: sistem_pembayaran,
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