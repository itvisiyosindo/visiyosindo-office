<header class="page-header">
	<h2><i class="fas fa-plus"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col-lg-8 col-md-10">
		<section class="card">
			<header class="card-header bg-dark text-white">
				<h2 class="card-title text-white">Form Penugasan Training Teknisi</h2>
			</header>
			<div class="card-body">
				<?= form_open('training_teknisi/add', array('id' => 'form-tambah-training', 'autocomplete' => 'off')); ?>
				
				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="rekanan">Nama Rekanan <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" class="form-control" name="rekanan" id="rekanan" placeholder="Masukkan nama rekanan / institusi / vendor..." required>
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="contact_person">Contact Person <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Masukkan nama/no HP contact person..." required>
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="subject">Subject <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" class="form-control" name="subject" id="subject" placeholder="Masukkan subjek/nama training..." required>
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="kategori">Kategori Alat/Topik <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<div class="checkbox-custom checkbox-default mb-2">
							<input type="checkbox" id="is_manual_kategori" name="is_manual_kategori" value="1">
							<label for="is_manual_kategori" class="font-weight-bold text-primary">Input manual (Ketik Teks)</label>
						</div>
						
						<div id="select-kategori-wrapper">
							<select data-plugin-selectTwo class="form-control populate" id="kategori" name="kategori" required>
								<option value="">- Pilih Kategori -</option>
								<?php foreach ($kategori as $row) { ?>
									<option value="<?= $row->id_topik ?>"><?= $row->nama ?></option>
								<?php } ?>
							</select>
						</div>
						
						<div id="input-kategori-wrapper" style="display: none;">
							<input type="text" class="form-control" id="kategori_manual" name="kategori_manual" placeholder="Ketik nama kategori / topik training manual...">
						</div>
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="prioritas">Prioritas <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<select class="form-control" id="prioritas" name="prioritas" required>
							<option value="">- Pilih Prioritas -</option>
							<option value="1">Low</option>
							<option value="2">Medium</option>
							<option value="3">Priority</option>
						</select>
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="teknisi">Pilih Teknisi <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<select data-plugin-selectTwo class="form-control populate" id="teknisi" name="teknisi" required>
							<option value="">- Pilih Teknisi -</option>
							<?php foreach ($pengguna as $row) { ?>
								<option value="<?= $row->pengguna_id ?>"><?= $row->nama ?> | <?= $row->jabatan ?></option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="waktu">Waktu Pelaksanaan <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="start" name="start" required>
							<span class="input-group-text border-start-0 border-end-0 rounded-0">s/d</span>
							<input type="text" class="form-control" id="end" name="end" required>
						</div>
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="attachment">Link Google Drive / Dokumen</label>
					<div class="col-lg-9">
						<input type="text" class="form-control" name="attachment" id="attachment" placeholder="Masukkan URL dokumen pendukung jika ada...">
					</div>
				</div>

				<div class="form-group row pb-3">
					<label class="col-lg-3 control-label text-lg-end pt-2" for="deskripsi">Deskripsi Detail</label>
					<div class="col-lg-9">
						<textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Masukkan deskripsi detail mengenai materi training/catatan khusus..." rows="4"></textarea>
					</div>
				</div>

				<footer class="card-footer text-end">
					<button type="submit" class="btn btn-primary" id="btn-save-training"><i class="fas fa-save"></i> Simpan Penugasan</button>
					<a href="<?= base_url('training_teknisi') ?>" class="btn btn-default">Kembali</a>
				</footer>

				<?= form_close(); ?>
			</div>
		</section>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#is_manual_kategori').change(function() {
        if (this.checked) {
            $('#select-kategori-wrapper').hide();
            $('#kategori').val('').trigger('change').prop('required', false);
            $('#input-kategori-wrapper').show();
            $('#kategori_manual').prop('required', true);
        } else {
            $('#input-kategori-wrapper').hide();
            $('#kategori_manual').val('').prop('required', false);
            $('#select-kategori-wrapper').show();
            $('#kategori').prop('required', true);
        }
    });

    $('#form-tambah-training').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var data = form.serialize();

        $('#btn-save-training').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        window.location.href = '<?= base_url("training_teknisi") ?>';
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                    $('#btn-save-training').attr('disabled', false).html('<i class="fas fa-save"></i> Simpan Penugasan');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                $('#btn-save-training').attr('disabled', false).html('<i class="fas fa-save"></i> Simpan Penugasan');
            }
        });
    });
});
</script>
