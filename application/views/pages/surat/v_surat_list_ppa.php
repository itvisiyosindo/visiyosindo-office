<?php
if (!isset($list_pengaju) || empty($list_pengaju)) {
	$CI = &get_instance();
	$CI->load->model('md_surat_list');
	$list_pengaju = $CI->md_surat_list->getPengajuPpaList();
}
?>
<header class="page-header">
	<h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="card card-modern">
			<div class="card-body">
				<!-- Filter & Action Menu -->
				<div class="row mb-3 align-items-end" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
					<div class="col-md-5 col-sm-12 mb-2 mb-md-0">
						<label for="id_pengaju" class="font-weight-bold text-dark">
							<i class="fas fa-user-check text-primary"></i> Pilih Nama Pengaju:
						</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_pengaju" name="id_pengaju">
							<option value="">-- Semua Pengaju --</option>
							<?php if (!empty($list_pengaju)) : ?>
								<?php foreach ($list_pengaju as $pengaju) : ?>
									<option value="<?= $pengaju->pengguna_id ?>">
										<?= htmlspecialchars($pengaju->nama) ?> (<?= htmlspecialchars($pengaju->jabatan ?: 'Staff') ?>)
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-7 col-sm-12 text-md-left">
						<button type="button" id="btn-print-menjabat" class="btn btn-primary btn-sm" title="Print Pengajuan Selama Menjabat">
							<i class="fas fa-print"></i> &nbsp;Print Pengajuan (Masa Menjabat)
						</button>
						<button type="button" id="btn-reset-filter" class="btn btn-secondary btn-sm ml-1" title="Reset Filter">
							<i class="fas fa-sync-alt"></i> &nbsp;Reset Filter
						</button>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
						<thead>
							<tr>
								<th> # </th>
								<th> Kode Surat</th>
								<th> Keterangan</th>
								<th> Tanggal</th>
								<th> Diajukan Oleh</th>
								<th> Status</th>
								<th> Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		if ($.isFunction($.fn['themePluginSelect2'])) {
			$('#id_pengaju').themePluginSelect2({
				width: '100%',
				allowClear: true
			});
		} else if ($.isFunction($.fn['select2'])) {
			$('#id_pengaju').select2({
				width: '100%',
				allowClear: true
			});
		}

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'surat/pagination/list_ppa',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val();
					e.id_pengaju = $('#id_pengaju').val();
					e.csrf_token = token;
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6],
				className: 'text-center'
			}]
		});

		$('#id_pengaju').on('change', function() {
			updateDatatable();
		});

		$('#btn-reset-filter').on('click', function() {
			$('#id_pengaju').val('').trigger('change');
		});

		$('#btn-print-menjabat').on('click', function() {
			var selectedPengaju = $('#id_pengaju').val();
			if (!selectedPengaju) {
				if (typeof Swal !== 'undefined') {
					Swal.fire({
						icon: 'warning',
						title: 'Pilih Pengaju',
						text: 'Silakan pilih nama pengaju terlebih dahulu untuk mencetak pengajuan selama masa menjabat.'
					});
				} else {
					alert('Silakan pilih nama pengaju terlebih dahulu untuk mencetak pengajuan selama masa menjabat.');
				}
				return;
			}
			var printUrl = '<?= base_url("surat/print_page/ppa_menjabat/") ?>' + selectedPengaju;
			window.open(printUrl, '_blank');
		});
	});

	function updateDatatable() {
		table.ajax.reload(null, false);
	}
</script>