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
                    <small>Scan Barcode:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="scan_barcode" placeholder="-- Scan Barcode --">
                        </div>
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
				url: 'stock_gudang/pagination',
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


	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>