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
		<div class="card-body">
			<form method="get">
    <div class="form-row d-flex align-items-end">
        <div class="col-md-2">
            <small>Pilih Tahun:</small>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
                <input type="text" name="tahun"
                    data-plugin-datepicker
                    data-plugin-options='{"orientation": "bottom", "format": "yyyy", "minViewMode": "years"}'
                    class="form-control"
                    id="filter_tahun"
                    placeholder="Pilih Tahun"
                    value="<?= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y') ?>"
                    required>
            </div>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fa fa-filter"></i> Filter
            </button>
        </div>
    </div>
</form>

			<br>
         <!--   <a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Cetak Rekapan</a> -->
			
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
						<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Nilai</a>
						<br><br>
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
						<thead  class="text-center">					
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Januari</th>
									<th>Februari</th>
									<th>Maret</th>
									<th>April</th>
									<th>Mei</th>
									<th>Juni</th>
									<th>Juli</th>
									<th>Agustus</th>
									<th>September</th>
									<th>Oktober</th>
									<th>November</th>
									<th>Desember</th>
							</tr>
						</thead>
						<tbody class="text-center">
								<?php if (empty($list_knowledge)): ?>
										<tr>
												<td colspan="14" class="text-center text-muted">Data tidak tersedia untuk tahun yang dipilih.</td>
										</tr>
								<?php else: ?>
										<?php $no = 1; foreach ($list_knowledge as $data): 
												//Ke Halaman Detail
												$id       	= encrypt($data['id']);
                        $namaPegawai  = '<a href="knowledge/show/detail/' . $id . '")>' . $data['nama'] . '</a>';
										?>
												<tr>
														<td><?= $no++ ?></td>
														<td style="text-align:left;"><?= $namaPegawai ?></td>
														<?php for ($i = 1; $i <= 12; $i++): ?>
																<td><?= isset($data['nilai'][$i]) ? $data['nilai'][$i] : '-' ?></td>
														<?php endfor; ?>
												</tr>
										<?php endforeach; ?>
								<?php endif; ?>
						</tbody>
					</table>	
				</div>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Input Nilai</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">

				<?php
						// Ambil data tanggal sekarang
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
						$hariArray = [
								'Sunday' => 'Minggu',
								'Monday' => 'Senin',
								'Tuesday' => 'Selasa',
								'Wednesday' => 'Rabu',
								'Thursday' => 'Kamis',
								'Friday' => 'Jumat',
								'Saturday' => 'Sabtu'
						];

						$hariInggris = date('l');
						$hari = $hariArray[$hariInggris];
						$bulan = $bulanArray[date('n')];
						$tahun = date('Y');
				?>
				<div class="form-group" style="display: flex; align-items: center; gap: 5px;">
						Input Nilai  Bulan  <strong> <?php echo $bulan . ' ' . $tahun; ?> </strong>
						<span id="live-clock"></span>
				</div>
					
					<div class="form-group">
						<label for="id_pengguna" class="form-control-label">Nama Pegawai <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="id_pengguna" name="id_pengguna" required>
							<option value="">- Pilih Pegawai -</option>
							<?php
							foreach ($list_nama as $row) {
								echo '<option value="' . $row->pengguna_id . '">' . $row->nama . '</option>';
							}
							?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="nilai" class="form-control-label">Nilai <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nilai" name="nilai" required>
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
		


		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'knowledge'
			$('#main-modal #modal-form').attr('action', 'knowledge/add')
			$('#main-modal').modal()
		})

		

		

		
	})

	
</script>
