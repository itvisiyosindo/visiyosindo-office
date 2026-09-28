<header class="page-header">
    <h2><i class="icons fas fa-money-bill"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="row">
    
    <div class="col">
        <div class="card-body">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Gaji Pokok</a><br>
            <br><strong class="fw-bold text-dark">Gaji Pokok Tahun <?= date('Y'); ?> &nbsp; : &nbsp; <?= 'Rp ' . number_format($GapokTahunIni, 0, ',', '.'); ?> </strong>
			<br><br><br><a href="<?= base_url('bonus/show/evaluasi'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-clipboard-check"></i>&nbsp;Nilai Evaluasi</a>

		</div>
		<br>
        <div class="card-body">
            
            <div class="row">
                <div class="col-md-2">
                    <small><i class="fas fa-calendar"></i> Print by Tahun :</small>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        <input type="text" 
                            data-plugin-datepicker 
                            data-plugin-options='{"orientation": "bottom", "format": "yyyy", "minViewMode": "years"}' 
                            class="form-control" 
                            id="filter_year" 
                            placeholder="Pilih Tahun" 
                            required>
                    </div>
                </div>
            </div><br>
            <div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama</th>
							<th> NPP</th>
							<th> Jabatan</th>
							<th> Status</th>
							<th> Masa Kerja (dari Kontrak)</th>
							<!--<th> Prorate</th>-->
							<th> Nilai Evaluasi</th>
							<th> Presentasi Bonus</th>
							<th> Total Bonus</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Gaji Pokok</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">

					<div class="form-group">
                    <label for="gapok" class="form-control-label">
                        Gaji Pokok Tahun <?= date('Y'); ?> :
                    </label>
                    <input type="text"
                            class="form-control"
                            id="gapok"
                            name="gapok"
                            placeholder="Masukkan Gaji Pokok"
                            required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>

				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter_year').change(function() {
			window.location.href = '<?= base_url() ?>' + 'bonus/print/' + $('#filter_year').val();
		})



        $('#filter_month').change(function() {
            updateDatatable()
        })

        $('.input_salary').mask('000.000.000.000', {
            reverse: true
        });
        table = $('#kt_table_1').DataTable({
                responsive: false,
                processing: true,
                serverSide: true,
                order: [
                    [0, 'desc']
                ],
                pageLength: 50,
                ajax: {
                    url: 'bonus/pagination/all',
                    type: 'POST',
                    data: function(e) {
                        e.tahun = $('#tahun').val()
                        e.csrf_token = token
                    }
                },
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                    className: 'text-center'
                }]
            })

        $('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'bonus'
			$('#main-modal #modal-form').attr('action', 'bonus/addGapok')
			$('#main-modal').modal()
		})

        $(document).on('click', '#btn-print', function() {
            var month = $('#filter_month').val()
            var link = 'salary_tidak_tetap/show/print/' + month
            window.open('<?= base_url() ?>' + link)
        })
    })

    function updateDatatable() {
        table.ajax.reload(null, false)
    }
</script>