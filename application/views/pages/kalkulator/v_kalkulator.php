<header class="page-header">
	<h2><i class="icons fas fa-box"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<style>
	#kt_table_1 thead th {
		text-align: center !important;
		vertical-align: middle !important;
	}

	/* Proteksi kontras teks dropdown asli & Select2 agar selalu terbaca jelas */
	select.form-control,
	select.form-control option {
		color: #0f172a !important;
		background-color: #ffffff !important;
		text-align: left !important;
		direction: ltr !important;
	}

	/* Styling Select2 */
	.select2-container--bootstrap .select2-selection--single {
		height: 38px !important;
		border: 1px solid #cbd5e1 !important;
		border-radius: 6px !important;
		padding: 6px 12px !important;
		font-size: 13px !important;
		color: #0f172a !important;
		background-color: #ffffff !important;
	}

	.select2-container--bootstrap .select2-selection--single .select2-selection__rendered {
		color: #0f172a !important;
		line-height: 24px !important;
		padding-left: 0 !important;
		padding-right: 20px !important;
		text-align: left !important;
	}

	.select2-container--bootstrap .select2-selection--single .select2-selection__arrow {
		height: 36px !important;
		right: 8px !important;
	}

	.select2-dropdown {
		border: 1px solid #cbd5e1 !important;
		border-radius: 6px !important;
		box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
		z-index: 1060 !important;
		background-color: #ffffff !important;
	}

	.select2-search--dropdown .select2-search__field {
		border: 1px solid #cbd5e1 !important;
		border-radius: 4px !important;
		padding: 6px 10px !important;
		font-size: 13px !important;
		color: #0f172a !important;
		background-color: #ffffff !important;
	}

	.select2-results__option {
		padding: 8px 12px !important;
		font-size: 13px !important;
		color: #0f172a !important;
		background-color: #ffffff !important;
		line-height: 1.4 !important;
		text-align: left !important;
		white-space: normal !important;
		word-break: break-word !important;
	}

	.select2-container--bootstrap .select2-results__option--highlighted[aria-selected] {
		background-color: #2563eb !important;
		color: #ffffff !important;
	}

	.select2-container--bootstrap .select2-results__option[aria-selected="true"] {
		background-color: #eff6ff !important;
		color: #1d4ed8 !important;
		font-weight: 600 !important;
	}
</style>


<div class="row">
	<div class="col">
		<div class="d-flex align-items-center mb-2">
			<?php if (sessPenggunaId() == 107 || sessPenggunaId() == 1) { ?>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-info mr-2"><i class="fas fa-database"></i>&nbsp;&nbsp;&nbsp;Master Data</a>
			<?php } ?>
			<a href="javascript:;" id="btn-export-excel" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;&nbsp;Print / Export Excel</a>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
					<small class="font-weight-bold text-dark">Filter By Merk:</small>
					<select class="form-control select2" name="filter_merk" id="filter_merk" data-plugin-selectTwo data-placeholder="Semua Merk">
						<option value="">Semua</option>
						<?php foreach ($filter_merk as $row) { 
							$merk = trim($row->merk ?? "");
							if ($merk === "") continue;
						?>
							<option value="<?= htmlspecialchars($merk, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($merk, ENT_QUOTES, 'UTF-8') ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-3">
					<small class="font-weight-bold text-dark">Filter By Nama:</small>
					<select class="form-control select2" name="filter_nama" id="filter_nama" data-plugin-selectTwo data-placeholder="Semua Nama Produk">
						<option value="">Semua</option>
						<?php foreach ($filter_nama as $row) { 
							$nama = trim($row->nama ?? "");
							if ($nama === "") continue;
						?>
							<option value="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-1">
					<small class="font-weight-bold text-dark">Discount:</small>
					<div class="form-group mb-0">
						<div class="input-group">
							<input type="text" class="form-control" id="diskon" placeholder="0">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<small class="font-weight-bold text-dark">Komisi NPWP Badan:</small>
					<div class="form-group mb-0">
						<div class="input-group">
							<input type="text" class="form-control" id="komisi_badan" placeholder="0">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<small class="font-weight-bold text-dark">Komisi NPWP Pribadi:</small>
					<div class="form-group mb-0">
						<div class="input-group">
							<input type="text" class="form-control" id="komisi_pribadi" placeholder="0">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<small class="font-weight-bold text-dark">Komisi Tanpa NPWP:</small>
					<div class="form-group mb-0">
						<div class="input-group">
							<input type="text" class="form-control" id="komisi_npwp" placeholder="0">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Merk</th>
							<th> Nama Product </th>
							<th> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pricelist &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							<th> Harga Acuan Terendah </th>
							<th> Discount yang ingin ditawarkan </th>
							<th> Komisi User dengan NPWP Badan </th>
							<th> Komisi User dengan NPWP Pribadi </th>
							<th> Komisi User Tanpa NPWP </th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>



<div id="file-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-dark text-light">
                <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Master Data Price List SWASTA</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form with enctype for file upload -->
            <?= form_open('#', array('id' => 'file-form', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off')); ?>
                <div class="modal-body">
										<div>
											<form method="post" enctype="multipart/form-data" action="kalkulator/import">
												<div class="box-body">
													<div class="form-group">
															<label for="exampleInputFile">Upload File :</label>
															<input type="file" name="berkas_excel" class="form-control" id="exampleInputFile">
															<small class="text-danger">upload file .xlsx ONLY</small>
													</div>
													<!--<button type="submit" class="btn btn-primary">Import</button>-->
													<br>
													<!--<button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;&nbsp; Import</button>-->
												</div>
											</form>
										</div>
                    <div>
                        <div class="text-left mb-2">
                            <label for="InputExperience" class="col-form-label">Download Format :</label>&nbsp;&nbsp;&nbsp;
                            <a href="javascript:" id="btn-download"><i class="fas fa-download"></i> Download File</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
												<button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i>&nbsp;&nbsp; Import</button>
                        <!--<button type="button" id="save-form" class="btn btn-success btn-save">Simpan</button>-->
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>



	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (window.jQuery && $.fn.select2) {
				$('#filter_merk').select2({
					theme: 'bootstrap',
					width: '100%',
					placeholder: 'Semua Merk',
					allowClear: true
				});

				$('#filter_nama').select2({
					theme: 'bootstrap',
					width: '100%',
					placeholder: 'Semua Nama Produk',
					allowClear: true
				});
			}

			$('#filter_merk, #filter_nama').on('change select2:select select2:clear', function() {
				updateDatatable();
			});

			$('#diskon, #komisi_badan, #komisi_pribadi, #komisi_npwp').keyup(function() {
				updateDatatable();
			});
			
			table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [[0, 'desc']],
			ajax: {
				url: 'kalkulator/pagination',
				type: 'POST',
				data: function(e) {
					e.filter_merk = $('#filter_merk').val() || '';
					e.filter_nama = $('#filter_nama').val() || '';
					e.diskon = $('#diskon').val();
					e.komisi_badan = $('#komisi_badan').val();
					e.komisi_pribadi = $('#komisi_pribadi').val();
					e.komisi_npwp = $('#komisi_npwp').val();
					e.csrf_token = token;
				}
			},
			order: [[0, 'asc']],
			columnDefs: [
					{ targets: [0], className: 'text-center', searchable: false },
					{ targets: [3, 4, 5, 6, 7, 8], className: 'text-right', searchable: false },
					{ targets: [1, 2], searchable: true },
			]
			/*columnDefs: [
				{
					targets: [0],
					className: 'text-center'
				},
				{
					targets: [3, 4, 5, 6, 7, 8],
					className: 'text-right'
				},
				{
					targets: [0, 3, 4, 5, 6, 7, 8],
					searchable: false
				},
				{
					targets: [1, 2],
					searchable: true
				}
			]*/

		});/*
		$('#kt_table_1').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: 'kalkulator/pagination',
        type: 'POST',
        data: function(e) {
            e.filter_merk = $('#filter_merk').val();
            e.filter_nama = $('#filter_nama').val();
            e.diskon = $('#diskon').val();
            e.komisi_badan = $('#komisi_badan').val();
            e.komisi_pribadi = $('#komisi_pribadi').val();
            e.komisi_npwp = $('#komisi_npwp').val();
            e.csrf_token = token;
        }
    },
    order: [[0, 'asc']],
    columnDefs: [
        { targets: [0], className: 'text-center', searchable: false },
        { targets: [3, 4, 5, 6, 7, 8], className: 'text-right', searchable: false },
        { targets: [1, 2], searchable: true },
    ]
});*/



			

			$('#btn-laporan-form').click(function() {
					$('#file-modal .form-control').val(null);
					$('#file-modal').modal();
					$('#file-modal #file-form').attr('action', 'kalkulator/import');

					$('#file-modal #object').val(object); 
			});

			$("#btn-download").click(function(){
				window.open("<?php echo base_url(); ?>kalkulator/export_swasta","_blank");
				$('#file-modal').modal('hide');
			});

			$("#btn-export-excel").click(function(){
				var searchVal = '';
				if (typeof table !== 'undefined' && table.search) {
					searchVal = table.search();
				}
				var params = {
					filter_merk: $('#filter_merk').val() || '',
					filter_nama: $('#filter_nama').val() || '',
					diskon: $('#diskon').val() || '',
					komisi_badan: $('#komisi_badan').val() || '',
					komisi_pribadi: $('#komisi_pribadi').val() || '',
					komisi_npwp: $('#komisi_npwp').val() || '',
					search: searchVal
				};
				window.open("<?php echo base_url(); ?>kalkulator/print_excel_swasta?" + $.param(params), "_blank");
			});
		});

		function updateDatatable() {
			if (typeof table !== 'undefined') {
				table.ajax.reload(null, false);
			}
		}
	</script>