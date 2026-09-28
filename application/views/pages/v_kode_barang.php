<header class="page-header">
	<h2><i class="icons fas fa-database "></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="row">
			<div class="col-md-6">
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class="col-md-6 text-right">
				<a href="javascript:;" id="btn-show-cek-barcode" class="btn btn-sm btn-primary"><i class="fas fa-barcode"></i>&nbsp;Check Barcode Exist</a>
			</div>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Kode Barang</th>
							<th> Nama Barang</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form cabang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
                <div class="form-group mb-2 pt-1">
                    <div class="col-form-label">
                        <label>Pilih Barang</label>
                        <select data-plugin-selectTwo="search_barang" class="form-control search_barang_diform filter-grup" name="id_barang" id="id_barang"></select>
                    </div>
                </div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Kode Barang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="kode_barang" name="kode_barang" placeholder="--- Scan Barcode ---" required>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Konfirmasi Kode Barang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="c_kode_barang" name="c_kode_barang" placeholder="--- Scan Ulang Barcode ---" required>
					</div>
				</div>
			</div>	
			<div class="modal-footer">
				<input type="hidden" id="id_kode_barang" name="id_kode_barang">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div id="barcode-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Check Barcode Exist</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'barcode-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label class="form-control-label">Cek Barcode <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="barcode" name="barcode" placeholder="--- Scan Barcode ---" required>
						<input style="visibility: hidden" type="" class="" id="" name="" placeholder="" required>
					</div>
				</div>
			</div>	
			<div class="modal-footers text-center mb-2 mt-1">
				<input type="hidden" id="id_kode_barang" name="id_kode_barang">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		$(document).on('change', '#barcode', function() {
			const barcode = $('#barcode').val()
            $.ajax({
                method: 'POST',
                url: 'kode_barang/get/cek_barcode',
                data: {
                    barcode: barcode,
                    csrf_token: token
                },
                dataType: 'json',
                success: function(resp) {
					// $('#barcode-modal').modal()
					$('#barcode-modal').modal('toggle');
					alert(resp)
                }
            })
        })

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'kode_barang/pagination',
				type: 'POST',
				data: function(e) {
					// e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 3],
				className: 'text-center'
			}]
		})

        $(".search_barang_diform").themePluginSelect2({
            placeholder: "--- Ketik Nama Barang ---",
            allowClear: true,
            minimumInputLength: 1,
            width: '100%',
            ajax: {
                method: 'POST',
                url: "barang/get/by_search",
                dataType: 'json',
                delay: 250,
                data:

                    function(params) {
                        return {
                            q: params.term, // search term
                            csrf_token: token
                        };
                    },
                processResults: function(data, params) {
                    return {

                        results: $.map(data.items, function(obj) {
                            return {
                                id: obj.id_barang,
                                text: `${obj.nama_barang}`
                            };
                        })
                    }
                },
                cache: true
            },
        });

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'kode_barang/add')
			$('#main-modal').modal()
		})

		$('#btn-show-cek-barcode').click(function() {
			$('.form-control').val(null)
			$('#barcode-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'cabang'
			$('#main-modal #modal-form').attr('action', 'cabang/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #id_cabang').val(data[0].id_cabang)
					$('#main-modal #nama_cabang').val(data[0].nama_cabang)
					$('#main-modal #penanggung_jawab').val(data[0].penanggung_jawab)
					$('#main-modal #alamat_cabang').val(data[0].alamat_cabang)
				})
		})
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}
</script>