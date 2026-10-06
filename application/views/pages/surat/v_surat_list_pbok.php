<?php
if (!isset($list_pengaju) || empty($list_pengaju)) {
	$CI = &get_instance();
	$CI->load->model('md_surat_list');
	$list_pengaju = $CI->md_surat_list->getPengajuPbokList();
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
	<div class="col-12">
		<div class="table-card-container">
			<div class="table-top-bar">
				<div class="d-flex align-items-center">
					<span class="badge badge-primary mr-2" style="font-size: 13px; padding: 6px 12px;"><i class="fas fa-list mr-1"></i> Data PBOK</span>
				</div>
			</div>
			<div class="card-body p-3">
				<!-- Filter & Action Menu -->
				<div class="row mb-3 align-items-end" style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
					<div class="col-md-5 col-sm-12 mb-2 mb-md-0">
						<label for="id_pengaju" class="font-weight-bold text-dark mb-1">
							<i class="fas fa-user-check text-primary mr-1"></i> Pilih Nama Pengaju:
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
						<button type="button" id="btn-print-menjabat" class="btn btn-primary btn-sm shadow-sm" title="Print Pengajuan Selama Menjabat">
							<i class="fas fa-print mr-1"></i> Print Pengajuan (Masa Menjabat)
						</button>
						<button type="button" id="btn-reset-filter" class="btn btn-secondary btn-sm shadow-sm ml-1" title="Reset Filter">
							<i class="fas fa-sync-alt mr-1"></i> Reset Filter
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
				url: 'surat/pagination/list_pbok',
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
			var printUrl = '<?= base_url("surat/print_pbok_menjabat/") ?>' + selectedPengaju;
			window.open(printUrl, '_blank');
		});
	});

	function updateDatatable() {
		table.ajax.reload(null, false);
	}
</script>