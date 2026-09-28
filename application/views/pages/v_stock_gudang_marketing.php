<header class="page-header">
	<h2><i class="icons fas fa-boxes"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<div class="row">
	<div class="col">
		<br>
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
					<small>Filter By Kategori:</small>
					<select class="form-control " name="filter_kategori" id="filter_kategori">
						<option value="">Semua</option>
						<?php foreach ($kategori_barang as $row) { ?>
							<option value="<?= encrypt($row->id_kategori) ?>"><?= $row->nama_kategori ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-2">
						<small>Filter Stok:</small>
						<select class="form-control " name="filter_stok" id="filter_stok">
								<option value="">Semua</option>
								<option value="1">STOCK READY</option>
						</select>
				</div>
				<div class="col-md-2">
						<small>Filter By Lokasi:</small>
						<select class="form-control" name="filter_gudang" id="filter_gudang">
								<option value="4"></option>
								<option value="">Default</option>
								<option value="1">Pusat</option>
								<option value="2">Transit Jakarta</option>
								<option value="3">Transit Yogyakarta</option>
								<!--<option value="4">All</option>-->
						</select>
				</div>
				<div class="col-md-2">
						<small>Filter By Jenis:</small>
						<select class="form-control" name="filter_jenis" id="filter_jenis">
								<option value="4">Stock Demo Marketing</option>
								<option value="1">Stock Jual</option>
								<option value="2">Stock Demo</option>
								<option value="3">Stock Rusak</option>
								<option value="5">Stock Sparepart</option>
								<option value="6">Stock Barang Customer</option>
						</select>
				</div>
				

                <div class="col-md-2">
                    <small>Scan Barcode:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="scan_barcode" placeholder="-- Scan Barcode --">
                        </div>
                    </div>
                </div>
								
								<div class="col-md-2">
                    <small>Export:</small>
                    <div class="form-group">
											<a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Cetak Rekapan</a>
										</div>
                </div>
			</div>
			<br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<!--<th> # </th>-->
							<th> Nama Barang</th>
							<?php foreach ($gudang as $row) { ?>
								<th> <?= $row->nama_gudang ?> </th>
							<?php } ?>
							<?php if ($transit == 1) { ?>
								<th>Stok Transit</th>
							<?php  }?>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Data Stock Gudang  </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
				    <?php
							$tanggal = date('d');
							$bulanArray = [
									1 => 'Januari', 
									2 => 'Februari', 
									3 => 'Maret', 
									4 => 'April', 
									5 => 'Mei', 
									6 => 'Juni', 
									7 => 'Juli', 
									8 => 'Agustus', 
									9 => 'September', 
									10 => 'Oktober', 
									11 => 'November', 
									12 => 'Desember'
							];
							$bulan = $bulanArray[date('n')];
							$tahun = date('Y');
						?>
							<div class="form-group" style="display: flex;">
									Cetak Data Stock Gudang Tanggal : <?php echo $tanggal . ' ' . $bulan . ' ' . $tahun; ?>
							</div>


							<div class="form-group">
								<label class="control-label">Filter Kategori</label>
								<select class="select-transaction input-group-sm form-control" name="filter_kat" id="filter_kat">
										<option value="">Semua Kategori</option>
										<?php foreach ($kategori_barang as $row) { ?>
											<option value="<?= $row->id_kategori ?>"><?= $row->nama_kategori ?></option>
										<?php } ?>
								</select>
						</div>
							
						<div class="form-group">
								<label class="control-label">Filter Stock</label>
								<select class="select-transaction input-group-sm form-control" name="filter_stock" id="filter_stock">
										<option value='1'>Semua Data</option>
										<option value='2'>Stock Ready</option>
								</select>
							</div>	
							

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form" >Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>

	
	document.addEventListener('DOMContentLoaded', function() {
        $('#filter_kategori,#scan_barcode,#filter_stok').change(function() {
                updateDatatable()
            })
        $('#scan_barcode').keyup(function() {
                updateDatatable()
            })
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'stock_gudang/paginationStok/marketing',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.filter_kategori = $('#filter_kategori').val()
					e.filter_stok = $('#filter_stok').val()
					e.scan_barcode = $('#scan_barcode').val()
					e.csrf_token = token
				}
			},
						columnDefs: [
				{
					targets: 0,
					className: 'text-left'
				},
				{
					targets: '_all',
					className: 'text-center'
				}
			]
		})


		$('#btn-cetaklaporan-form').click(function() {
		     $('#main-modal-marketing').modal()	
		     
    			
		})

		$("#btn-export").click(function(){
          filter_stock = $("#filter_stock").val();
          filter_kat 	 = $("#filter_kat").val();
		      window.open("<?php echo base_url(); ?>stock_gudang/exportlaporan/search?filter_stock="+encodeURIComponent(filter_stock)+"&filter_kat="+encodeURIComponent(filter_kat),"_blank");
          $('#main-modal-marketing').modal('hide')
			
        });


	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}

		//Filter by Lokasi
    document.getElementById('filter_gudang').addEventListener('change', function() {
        var selectedValue = this.value;
        
        // Tentukan URL berdasarkan pilihan yang dipilih
        if (selectedValue === '1') {
            window.location.href = 'stock_gudang/show/pusat';
        } else if (selectedValue === '2') {
            window.location.href = 'stock_gudang/show/jakarta';
        } else if (selectedValue === '3') {
            window.location.href = 'stock_gudang/show/yogyakarta';
        //} else if (selectedValue === '4') {
        //    window.location.href = 'stock_gudang/show/all';
        } else {
            // Jika pilihan "Default" dipilih, arahkan ke halaman utama atau halaman yang sesuai
            window.location.href = 'stock_gudang/show/default';
        }
    });



		//Filter by Jenis
    document.getElementById('filter_jenis').addEventListener('change', function() {
        var selectedValue = this.value;
        
        // Tentukan URL berdasarkan pilihan yang dipilih
        if (selectedValue === '1') {
            window.location.href = 'stock_gudang/show/jual';
        } else if (selectedValue === '2') {
            window.location.href = 'stock_gudang/show/demo';
        } else if (selectedValue === '3') {
            window.location.href = 'stock_gudang/show/rusak';
        } else if (selectedValue === '4') {
            window.location.href = 'stock_gudang/show/marketing';
        } else if (selectedValue === '5') {
            window.location.href = 'stock_gudang/show/sparepart';
        } else if (selectedValue === '6') {
            window.location.href = 'stock_gudang/show/customer';
        }
    });
</script>