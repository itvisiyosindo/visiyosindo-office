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
</style>


<div class="row">
	<div class="col">
		<div class="">
			<?php if (sessPenggunaId() == 107 || sessPenggunaId() == 1) { ?>
				
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-database"></i>&nbsp;&nbsp;&nbsp;Master Data</a>


			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
					<small>Filter By Merk:</small>
					<select class="form-control " name="filter_merk" id="filter_merk">
						<option value="">Semua</option>
						<?php foreach ($filter_merk as $row) { ?>
							<option value="<?= $row->merk ?>"><?= $row->merk ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-2">
					<small>Filter By Nama:</small>
					<select class="form-control " name="filter_nama" id="filter_nama">
						<option value="">Semua</option>
						<?php foreach ($filter_nama as $row) { ?>
							<option value="<?= $row->nama ?>"><?= $row->nama ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-2">
					<small>Discount:</small>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" id="diskon" placeholder="0">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<small>Komisi User NPWP Badan:</small>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" id="komisi_badan" placeholder="0">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<small>Komisi User NPWP Pribadi:</small>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" id="komisi_pribadi" placeholder="0">
							<div class="input-group-append">
								<span class="input-group-text">%</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<small>Komisi User Tanpa NPWP:</small>
					<div class="form-group">
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
			$('#filter_merk, #filter_nama').change(function() {
				updateDatatable()
			})
			$('#diskon, #komisi_badan, #komisi_pribadi, #komisi_npwp').keyup(function() {
                updateDatatable()
            })
			
			table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [[0, 'desc']],
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
          $('#file-modal').modal('hide')
			
      });


			

		})

		$(document).ready(function() {
    $('#kt_table_1').DataTable({
        "order": [[0, 'asc']] // Set urutan default berdasarkan kolom pertama (b.id)
    });
});

		function updateDatatable() {
			table.ajax.reload(null, false)
		}
	</script>