<header class="page-header">
	<h2><i class="fas fa-thermometer-half "></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>
		</div>
		<br>
		<div class="card-body">
			<div class="row">
        <div class="col-md-2">
           	<small>Filter By Month:</small>
          	<div class="input-group">
          		<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
              <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
          </div>
        </div>
                
        <div class="col-md-2">
					<small> <i class="fas fa-print"></i> Print By Month:</small>
					<div class="input-group">
						<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
						<input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="print_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
					</div>
				</div>
				
      </div>
      <br>
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Tanggal</th>
							<th> Waktu</th>
							<th> Suhu (&deg;C)</th>
							<th> Kelembapan Relatif RH (%)</th>
							<th> Keterangan</th>
							<th> Diisi Oleh</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Suhu Ruangan Visilab</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div>
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
						Tanggal : <span id="modal-display-date"><?php echo $hari . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun; ?></span> - 
						<span id="live-clock"></span>
				</div>

				


					<div class="form-group">
							<label for="suhu" class="form-control-label">Suhu <span class="text-danger">*</span> :</label>
							<div class="input-group">
									<input type="text" class="form-control" id="suhu" name="suhu" placeholder="0" required>
									<div class="input-group-append">
											<span class="input-group-text">&deg;C</span>
									</div>
							</div>
					</div>
					<div class="form-group">
							<label for="kelembapan" class="form-control-label">Kelembapan Relatif RH <span class="text-danger">*</span> :</label>
							<div class="input-group">
									<input type="text" class="form-control" id="kelembapan" name="kelembapan" placeholder="0" required>
									<div class="input-group-append">
											<span class="input-group-text">%</span>
									</div>
							</div>
					</div>

					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="keterangan" name="keterangan" required>
              <option value="Baik">Baik</option>
              <option value="Tidak Baik">Tidak Baik</option>
						</select>
					</div>


				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_brosur" name="id_brosur">
				<input type="hidden" id="tanggal_suhu" name="tanggal">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	var defaultTodayDisplay = '<?php echo $hari . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun; ?>';
	var defaultTodayYmd = '<?php echo date('Y-m-d'); ?>';

	document.addEventListener('DOMContentLoaded', function() {
		$('#print_month').change(function() {
			window.location.href = '<?= base_url() ?>' + 'visilab/print_page/printSuhuByMonth/' + $('#print_month').val();
		})

		$('#filter_month').change(function() {
            table.ajax.reload()
        })
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
            pageLength: 50, // ✅ default jadi 50 baris
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'visilab/pagination_suhu',
				type: 'POST',
				data: function(e) {
          e.filter_month = $('#filter_month').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal #modal-form').attr('action', 'visilab/addSuhu')
			$('#main-modal #id_brosur').val('')
			$('#main-modal #tanggal_suhu').val(defaultTodayYmd)
			$('#main-modal #modal-display-date').text(defaultTodayDisplay)
			$('#main-modal #keterangan').val('Baik').trigger('change')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			var object = 'visilab'
			$('#main-modal #modal-form').attr('action', 'visilab/updateSuhu')

			var id = $(this).attr("data-id")
			var tanggal = $(this).attr("data-tanggal") || defaultTodayYmd
			var tanggalDisplay = $(this).attr("data-tanggal-display") || defaultTodayDisplay

			$('#main-modal #tanggal_suhu').val(tanggal)
			$('#main-modal #modal-display-date').text(tanggalDisplay)
			$('#main-modal #id_brosur').val(id || '')

			if (id) {
				fetch(object + '/editSuhu/' + id)
					.then(function(resp) {
						return resp.json()
					})
					.then(function(data) {
						if (data && data.length > 0) {
							$('#main-modal #id_brosur').val(data[0].id)
							$('#main-modal #suhu').val(data[0].suhu)
							$('#main-modal #kelembapan').val(data[0].kelembapan)
							$('#main-modal #keterangan').val(data[0].keterangan).trigger('change')
						}
					})
			} else {
				$('#main-modal #suhu').val('')
				$('#main-modal #kelembapan').val('')
				$('#main-modal #keterangan').val('Baik').trigger('change')
			}

			$('#main-modal').modal()
		})

	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}


	//Jam Real Time
	function updateClock() {
			const now = new Date();
			const jam = String(now.getHours()).padStart(2, '0');
			const menit = String(now.getMinutes()).padStart(2, '0');
			const detik = String(now.getSeconds()).padStart(2, '0');
			document.getElementById('live-clock').textContent = jam + ':' + menit + ':' + detik;
	}

	setInterval(updateClock, 1000);
	updateClock(); // tampilkan langsung saat pertama load

</script>