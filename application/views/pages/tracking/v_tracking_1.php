<header class="page-header">
	<h2><i class="icons fas fa-database"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<?php if(sessPenggunaId() == 1 || sessPenggunaId() == 15 || sessPenggunaId() == 33 || sessPenggunaId() == 7 || sessPenggunaId()==73) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Print Rekapan</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Gudang Pengiriman</th>
							<th> Nama Customer </th>
							<th> Nama Barang </th>
							<th> No Surat Jalan </th>
							<th> Alamat Penerima </th>
							<th> Nama Ekspedisi </th>
							<th> Estimasi Penerimaan </th>
							<th> Status </th>
							<th> Aksi </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Tracking Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label for="id_gudang" class="form-control-label">Alamat Pengiriman <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_gudang" name="id_gudang" required>
							<option value="">- Pilih Gudang -</option>
							<?php
							foreach ($list_gudang as $row) {
								echo '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="id_customer" class="form-control-label">Nama Customer <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_customer" name="id_customer" required>
							<option value="">- Pilih Customer -</option>
							<?php
							foreach ($list_cust as $row) {
								echo '<option value="' . $row->id_customer . '">' . $row->nama_customer . '</option>';
							}
							?>
						</select>
					</div>		
					<div class="form-group">
						<label for="nama_barang" class="form-control-label">Nama Barang <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="nama_barang" name="nama_barang" required></textarea>
					</div>	
					<div class="form-group">
						<label for="pic_penerima" class="form-control-label">PIC Penerima <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="pic_penerima" name="pic_penerima" required>
					</div>
					<div class="form-group">
						<label for="alamat_penerima" class="form-control-label">Alamat Penerima <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="alamat_penerima" name="alamat_penerima" required></textarea>
					</div>
					
					

				<div class="form-group">
						<label for="id_ekspedisi" class="form-control-label">Pilih Ekspedisi <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_ekspedisi" name="id_ekspedisi" required onchange="toggleForm()">
								<option value="">- Pilih Ekspedisi -</option>
								<option value="1">Diantarkan Langsung</option>
								<option value="2">Ekspedisi</option>
						</select>
				</div>

				<div class="form-group" id="form_diantarkan" style="display:none;">
						<label for="nama_diantarkan" class="form-control-label">Diantarkan Oleh <span class="text-danger">*</span> :</label>
						<input class="form-control" id="nama_diantarkan" name="nama_diantarkan" required>
						<input type="hidden" id="id_kirim" name="id_kirim" value="7777777"> <!-- untuk ID Diantarkan Langsung -->
				</div>

			

				<div class="form-group" id="form_dikirim" style="display:none;">
						<label for="nama_dikirim" class="form-control-label">Nama Ekspedisi <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="nama_dikirim" name="nama_dikirim" required>
								<option value="">- Pilih Ekspedisi -</option>
								<?php
								foreach ($list_eks as $row) {
										echo '<option value="' . $row->id_ekspedisi . '" data-nama="' . $row->nama_ekspedisi . '">' . $row->nama_ekspedisi . '</option>';
								}
								?>
						</select>
				</div>

				<div class="form-group">
						<label for="tgl_pengiriman" class="form-control-label">Tanggal Pengiriman <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_pengiriman" name="tgl_pengiriman" required>
					</div>
				</div>

				<div class="form-group">
						<label for="tgl_sampai" class="form-control-label">Estimasi Sampai <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_sampai" name="tgl_sampai" required>
					</div>
				</div>

				<div class="form-group">
						<label for="biaya" class="form-control-label">Biaya <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="biaya" name="biaya" required>
                <div id="danger-alert">Jika 0, maka isi dengan -</div>
				</div>
				<div class="form-group">
						<label for="no_sj" class="form-control-label">No Surat Jalan <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="no_sj" name="no_sj" required>
				</div>
				<div class="form-group">
						<label for="no_resi" class="form-control-label">No Resi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="no_resi" name="no_resi" required>
				</div>
				<div class="form-group">
						<label for="link_resi" class="form-control-label">link Resi <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Google Drive Resi pengiriman (Jika tidak ada isi -)" id="link_resi" name="link_resi" required>
				</div>
				<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan Lainnya <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="keterangan" name="keterangan" required></textarea>
				</div>

				<div class="form-group">
						<label for="status" class="form-control-label">Status <span class="text-danger">*</span> :</label>
						<select class="form-control" id="status" name="status" required onchange="toggleFormStatus()">
							<option value="">- Pilih Status -</option> 
							<option value="1">Proses Kirim</option>
							<option value="2">Manifest Berangkat</option>
							<option value="3">Proses Sortir</option>
							<option value="6">Menunggu Konfirmasi</option>
							<option value="4">Pengantaran Kurir</option>
							<option value="5">Diterima</option>
						</select>
				</div>

				<div class="form-group" id="form_nama_penerima" style="display:none;">
						<label for="nama_penerima" class="form-control-label">Nama Penerima <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama_penerima" name="nama_penerima" required>
				</div>
				<div class="form-group" id="form_tgl_penerima" style="display:none;">
						<label for="tgl_penerima" class="form-control-label">Tanggal Penerimaan <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tgl_penerima" name="tgl_penerima" required>
					</div>
				</div>
				<div class="form-group" id="form_bukti_penerima" style="display:none;">
						<label for="bukti_penerima" class="form-control-label">Bukti Penerimaan <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="bukti_penerima" name="bukti_penerima" required></textarea>
				</div>

				<div class="form-group" id="form_keterangan_konfirmasi" style="display:none;">
						<label for="keterangan_konfirmasi" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea type="text" class="form-control" id="keterangan_konfirmasi" name="keterangan_konfirmasi" required></textarea>
				</div>

				

					
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_tracking" name="id_tracking">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Tracking Barang  </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
						<label for="bukti_penerima" class="form-control-label">Pilih Tanggal Input Data Tracking Barang</label>
				    <div class="form-group" style="display: flex;">
				    <div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>	
					
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
		    	<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form" >Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<script>
function toggleForm() {
        var ekspedisi = document.getElementById("id_ekspedisi").value;
        var formDiantarkan = document.getElementById("form_diantarkan");
        var formDikirim = document.getElementById("form_dikirim");

        if (ekspedisi === "1") {
            formDiantarkan.style.display = "block";
            formDikirim.style.display = "none";
        } else if (ekspedisi === "2") {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "block";
        } else {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "none";
        }
    }


		function toggleFormStatus() {
        var status = document.getElementById("status").value;
        var formDiantarkan = document.getElementById("form_nama_penerima");
        var formDikirim = document.getElementById("form_tgl_penerima");
        var formBukti = document.getElementById("form_bukti_penerima");
        var formKet = document.getElementById("form_keterangan_konfirmasi");

        if (status === "5") {
            formDiantarkan.style.display = "block";
            formDikirim.style.display = "block";
            formBukti.style.display = "block";
            formKet.style.display = "none";
        } else if (status === "6") {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "none";
            formBukti.style.display = "none";
            formKet.style.display = "block";
        } else {
            formDiantarkan.style.display = "none";
            formDikirim.style.display = "none";
            formBukti.style.display = "none";
            formKet.style.display = "none";
        }
    }


	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'tracking/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 4, 5, 6, 7, 8, 9],
				className: 'text-center'
			}]
		})

		

		$('#btn-show-add-form').click(function() {
    $('.form-control').val(null);
    $('#main-modal #modal-form').attr('action', 'tracking/add');
    
    
    $('#nama_ekspedisi_input').remove();

    
    $('<input>').attr({
        type: 'hidden',
        id: 'nama_ekspedisi_input',
        name: 'nama_ekspedisi', 
        value: ''
    }).appendTo('#main-modal #modal-form');

    $('#main-modal').modal();
		});

		
		$('#nama_dikirim').change(function() {
				var selectedNamaEkspedisi = $('#nama_dikirim option:selected').data('nama');
				$('#nama_ekspedisi_input').val(selectedNamaEkspedisi);
		});




		$('#btn-laporan-form').click(function() {
		     $('#main-modal-marketing').modal()	
		     
    			
		})

		$("#btn-export").click(function(){
		     
                tglawal = $("#tglawal").val();
                tglakhir = $("#tglakhir").val();
                 window.open("<?php echo base_url(); ?>tracking/exportlaporan/search?tglawal="+encodeURIComponent(tglawal)+"&tglakhir="+encodeURIComponent(tglakhir),"_blank");
                $('#main-modal-marketing').modal('hide')
			
        });


		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}

	


</script>