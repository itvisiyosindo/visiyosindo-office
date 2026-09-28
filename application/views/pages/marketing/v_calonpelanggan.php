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
			<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO()) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah Calon</a>
			<?php } ?>

			<?php if (isAdmin() || isHrd() || isCRO()) { ?>
				<a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-print"></i>&nbsp;&nbsp;&nbsp;Cetak Laporan</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> ID </th>
							<th> Kode </th>
							<th> Kode </th>
							<th> Nama Marketing</th>
							<th> Nama Calon Customer </th>
							<th> Alamat </th>
							<th> Provinsi </th>
							<th> Kota </th>
							<th> Tanggal </th>
							<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || sessPenggunaId() == 755) { ?>
								<th> Ubah Status </th>
								<th> Modality </th>
							<?php } ?>
							<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || sessPenggunaId() == 755) { ?>
								<th> Aksi</th>
							<?php } ?>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="main-modal-modality" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Modality</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-modality', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idcalonpelanggan" name="idcalonpelanggan" required>
					</div>
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="idpelanggan" name="idpelanggan" required>
					</div>
					<div class="form-group" hidden>
						<input type="text" class="form-control" id="kodepelanggan" name="kodepelanggan" required>
					</div>
					<div class="form-group">
						<label class="control-label">Kategori</label>
						<select class="select-transaction input-group-sm form-control" name="kategori" id="kategori">
							<?php if ($kategorimodality != NULL): ?>
								<option value=''>— Pilih Kategori —</option>
								<?php foreach ($kategorimodality as $value): ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('kategori'); ?>
					</div>
					<div class="form-group">
						<label for="merk" class="form-control-label">Merk <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="merk" name="merk" required>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nama" name="nama" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<div class="row">
				<div class="col">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id='kt_table_2'>
								<thead>
									<tr>
										<th> # </th>
										<th> Kategori</th>
										<th> Nama </th>
										<th> Merk </th>
										<th> Tanggal </th>
										<th> Aksi </th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>

			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>
<div id="main-modal-pelanggan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-pelanggan', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-pelanggan-form">
					<div class="form-group">
						<div>
							<label for="kodecustomer" class="form-control-label">Kode Customer <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control col-sm-5" id="idcalon" name="idcalon" hidden />
							<input type="text" class="form-control col-sm-5" id="kodecaloncustomer2" name="kodecaloncustomer2" style="display: none" />
							<input type="text" class="form-control col-sm-5" id="kodecustomer" name="kodecustomer" readonly required />
						</div>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namapelanggan" name="namapelanggan" required>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Tanggal Registrasi <span class="text-danger">*</span> :</label>
						<input type="date" class="form-control" id="tanggalregistrasi" name="tanggalregistrasi" required>
					</div>
					<div class="form-group">
						<label for="nik" class="form-control-label">NIK <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nik" name="nik" required>
					</div>
					<div class="form-group">
						<label for="nonpwp" class="form-control-label">No. NPWP <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="nonpwp" name="nonpwp" required>
					</div>
					<div class="form-group">
						<label for="namanpwp" class="form-control-label">Nama di NPWP <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namanpwp" name="namanpwp" required>
					</div>
					<div class="form-group">
						<label for="jenisfakturpajak" class="form-control-label">Jenis Faktur Pajak <span class="text-danger">*</span> :</label>
						<select class="select-transaction input-group-sm form-control" name="jenisfakturpajak" id="jenisfakturpajak">
							<?php if ($jenisfakturpajak != NULL): ?>
								<option value=''>— Pilih Jenis Faktur Pajak —</option>
								<?php foreach ($jenisfakturpajak as $value): ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('jenisfakturpajak'); ?>
					</div>
					<div class="form-group">
						<label for="syaratpembayaran" class="form-control-label">Syarat Pembayaran <span class="text-danger">*</span> :</label>
						<select class="select-transaction input-group-sm form-control" name="syaratpembayaran" id="syaratpembayaran">
							<?php if ($jenisfakturpajak != NULL): ?>
								<option value=''>— Pilih Syarat Pembayaran —</option>
								<?php foreach ($syaratpembayaran as $value): ?>
									<option value="<?php echo $value->id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('syaratpembayaran'); ?>
					</div>
					<div class="form-group">
						<label for="berkasdokumenpembayaran" class="form-control-label">Berkas Dokumen Pembayaran <span class="text-danger">*<sup>(Checklist dan Masukkan Link Google Drive)</sup></span> :</label><br />
						<input type="number" class="form-control" id="jumlahberkas" name="jumlahberkas" style="display: none" value="<?= $jumlahberkaspelanggan; ?>" required>
						<div id="chkberkasdokumen" class="col-sm-10">
							<?php if ($masterberkaspelanggan != NULL): ?>
								<?php foreach ($masterberkaspelanggan as $value): ?>
									<div class="col-sm-15" id='dberkasdokumen'>
										<!-- <input type="text" class="form-control" id="id<?php echo $value->id; ?>" name="idberkas[]" value="<?php echo $value->id; ?>" style="display: none" required> -->
										<!-- <input type="checkbox" name="newsletter" value="accept" checked="checked" /> -->
										<input type="checkbox" id="chk<?php echo $value->id; ?>" name="chkberkas[]" value="0" unchecked> <?php echo $value->namaberkas; ?>
										<input type="text" class="form-control" id="link<?php echo $value->id; ?>" name="linkberkas[]" style="display: none">
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php echo form_error('masterberkaspelanggan'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="pengirimandokumen" class="form-control-label">Pengiriman Dokumen Pembayaran <span class="text-danger">*</span> :</label>
						<select class="form-control" id="pengirimandokumen" name="pengirimandokumen" required>
							<option value="">- Pilih Jenis Pengiriman Dokumen -</option>
							<option value="1">Softcopy</option>
							<option value="2">Hardcopy</option>
						</select>
					</div>
					<div class="form-group">
						<label for="statuspiutang" class="form-control-label">Status Piutang <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="statuspiutang" name="statuspiutang" required>
					</div>
					<div class="form-group">
						<label for="limitpiutang" class="form-control-label">Limit Piutang <span class="text-danger">*</span> :</label>
						<input type="number" class="form-control" id="limitpiutang" name="limitpiutang" required>
					</div>
					<div class="form-group">
						<label for="alamatpengiriman" class="form-control-label">Alamat Pengiriman <span class="text-danger">*</span> :</label>
						<textarea name="alamatpengiriman" class="form-control" id="alamatpengiriman" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="alamatpenagihan" class="form-control-label">Alamat Penagihan <span class="text-danger">*</span> :</label>
						<textarea name="alamatpenagihan" class="form-control" id="alamatpenagihan" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
						<textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label for="berkaslainnya" class="form-control-label">Berkas Lainnya <span class="text-danger">*<sup>(Checklist dan Masukkan Link Google Drive)</sup></span> :</label><br />
						<input type="number" class="form-control" id="jumlahberkaslainnya" name="jumlahberkaslainnya" style="display: none" value="<?= $jumlahberkaslainpelanggan; ?>" required>
						<div id="chkberkasdokumenlain" class="col-sm-10">
							<?php if ($masterberkaslainpelanggan != NULL): ?>
								<?php foreach ($masterberkaslainpelanggan as $value): ?>
									<div class="col-sm-15" id='dberkasdokumenlainnya'>
										<input type="checkbox" id="chklainnya<?php echo $value->id; ?>" name="chkberkaslainnya[]" value="0" unchecked> <?php echo $value->namaberkas; ?>
										<input type="text" class="form-control" id="linklainnya<?php echo $value->id; ?>" name="linkberkaslainnya[]" style="display: none">
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php echo form_error('masterberkaslainpelanggan'); ?>
						</div>
					</div>
					<!-- <div class="form-group">
								<label for="kota" class="form-control-label">Kota <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control" id="kota" name="kota" required>
							</div>
							<div class="form-group">
								<label for="provinsi" class="form-control-label">Provinsi <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control" id="provinsi" name="provinsi" required>
							</div> -->
					<!-- 					<div class="form-group">
								<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
								<textarea name="alamat" class="form-control" id="alamat" cols="15" rows="3"></textarea>
							</div> -->
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
					<input type="hidden" id="id_pelanggan" name="id_pelanggan"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-simpan-form-pelanggan" class="btn btn-success btn-save" data-dismiss="modal">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cetak Calon Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-marketing-form">
					<div class="form-group" style="display: flex;">
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
							<input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Nama Marketing</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($nama_marketing != NULL): ?>
								<option value=''>Semua Marketing</option>
								<?php foreach ($nama_marketing as $value): ?>
									<option value="<?php echo $value->pengguna_id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('nama_marketing'); ?>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<div id="main-modal-calon" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Calon Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-calon', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-calonpelanggan-form">
					<div class="form-group">
						<div>
							<label for="kodecaloncustomer" class="form-control-label">Kode Calon Customer <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control col-sm-5" id="kodecaloncustomer" name="kodecaloncustomer" readonly required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Nama Marketing</label>
						<select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
							<?php if ($nama_marketing != NULL): ?>
								<option>— Pilih Nama Marketing —</option>
								<?php foreach ($nama_marketing as $value):
									if ($pengguna_id == $value->pengguna_id) { ?>
										<option value="<?php echo $value->pengguna_id; ?>" selected=true><?php echo $value->nama; ?></option>
									<?php
									}
									?>
									<option value="<?php $value->pengguna_id; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('namamarketing'); ?>
					</div>
					<div class="form-group">
						<label for="nama" class="form-control-label">Nama Calon Customer <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namacaloncustomer" name="namacaloncustomer" required>
					</div>
					<div class="form-group">
						<label for="tipe" class="form-control-label">Tipe Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="tipecustomer" name="tipecustomer" required>
							<option value="">- Pilih Tipe Customer -</option>
							<option value="Government">Government</option>
							<option value="Private">Private</option>
							<option value="Clinic">Clinic</option>
							<option value="Pihak Ketiga">Pihak Ketiga</option>
						</select>
					</div>
					<div class="form-group">
						<label for="kelas" class="form-control-label">Kelas Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kelascustomer" name="kelascustomer" required>
							<option value="">- Pilih Kelas Customer -</option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="D">D</option>
						</select>
					</div>
					<div class="form-group" id='dpihakketiga'>
						<label for="namapihakketiga" class="form-control-label">Nama Pihak Ketiga <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namapihakketiga" name="namapihakketiga">
					</div>
					<div class="form-group">
						<label class="control-label">Provinsi</label>
						<select class="select-transaction input-group-sm form-control" name="provinsi" id="provinsi">
							<?php if ($provinsi != NULL): ?>
								<option value=''>— Pilih Provinsi —</option>
								<?php foreach ($provinsi as $value): ?>
									<option value="<?php echo $value->kode; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('provinsi'); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Kabupaten/Kota</label>
						<select name="kota" class="form-control" id="kota">
							<option value=''>- Pilih Kabupaten/Kota -</option>
						</select>
						<?php echo form_error('kota'); ?>
					</div>
					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea name="alamatcaloncustomer" class="form-control" id="alamatcaloncustomer" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						<div class="form-group" id="grouppic">
							<div id="dynamic_field">
								<label class="control-label col-sm-9" for="namapic">Nama PIC:</label>
								<div class="col-sm-9">
									<input hidden type="number" class="form-control" id="count" name="count">
									<input hidden type="text" class="form-control" id="picpic" name="picpic">
									<input hidden type="text" class="form-control" id="idpic" name="idpic[]">
									<input type="text" class="form-control" id="namapic" placeholder="Masukkkan Nama PIC" name="namapic[]" autocomplete="off">
								</div>
								<label class="control-label col-sm-9" for="jabatanpic">Jabatan PIC:</label>
								<div class="col-sm-9">
									<input type="email" class="form-control" id="jabatanpic" placeholder="Masukkan Jabatan PIC" name="jabatanpic[]" autocomplete="off">
								</div>
								<label class="control-label col-sm-6" for="teleponpic">Telepon PIC:</label>
								<div class="col-sm-12">
									<div class="input-group mb-12">
										<input type="number" class="form-control" id="teleponpic" placeholder="Masukkan Telepon PIC" name="teleponpic[]" autocomplete="off">
										<span class="input-group-append">&nbsp &nbsp &nbsp</span>
										<span class="input-group-append">
											<button type="button" name="add" id="add" class="btn btn-success">Tambah PIC</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							</br>
							<label for="kota" class="form-control-label">Email <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="email" name="email" required>
						</div>
						<div class="form-group">
							<label for="kota" class="form-control-label">Website <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="website" name="website" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
					<button type="button" id="btn-simpan-form-calon" class="btn btn-success btn-save">Simpan</button>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<div id="main-modal-calonedit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Update Calon Pelanggan </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-calonedit', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-calonpelangganedit-form">
					<div class="form-group">
						<div hidden>
							<input type="text" class="form-control col-sm-5" id="idcalonedit" name="idcalonedit" readonly required />
						</div>
					</div>
					<div class="form-group">
						<div>
							<label for="kodecaloncustomer" class="form-control-label">Kode Calon Customer <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control col-sm-5" id="kodecaloncustomer" name="kodecaloncustomer" readonly required />
						</div>
					</div>
					<div class="form-group">
						<label for="namacaloncustomer" class="form-control-label">Nama Calon Customer <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="namacaloncustomer" name="namacaloncustomer" required>
					</div>
					<div class="form-group">
						<label for="tipecustomer" class="form-control-label">Tipe Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="tipecustomer" name="tipecustomer" required>
							<option value="">- Pilih Tipe Customer -</option>
							<option value="Government">Government</option>
							<option value="Private">Private</option>
							<option value="Clinic">Clinic</option>
							<option value="Pihak Ketiga">Pihak Ketiga</option>
						</select>
					</div>
					<div class="form-group">
						<label for="kelascustomer" class="form-control-label">Kelas Customer <span class="text-danger">*</span> :</label>
						<select class="form-control" id="kelascustomer" name="kelascustomer" required>
							<option value="">- Pilih Kelas Customer -</option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="D">D</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Provinsi</label>
						<select class="select-transaction input-group-sm form-control" name="provinsi" id="provinsi">
							<?php if ($provinsi != NULL): ?>
								<option value=''>— Pilih Provinsi —</option>
								<?php foreach ($provinsi as $value): ?>
									<option value="<?php echo $value->kode; ?>"><?php echo $value->nama; ?></option>
								<?php endforeach; ?>
							<?php else: ?>
								<option value=''>— Tidak ada data —</option>
							<?php endif; ?>
						</select>
						<?php echo form_error('provinsi'); ?>
					</div>
					<div class="form-group">
						<label class="control-label">Kabupaten/Kota</label>
						<select name="kota" class="form-control" id="kota">
							<option value=''>- Pilih Kabupaten/Kota -</option>
						</select>
						<?php echo form_error('kota'); ?>
					</div>
					<div class="form-group">
						<label for="alamat" class="form-control-label">Alamat <span class="text-danger">*</span> :</label>
						<textarea name="alamatcaloncustomer" class="form-control" id="alamatcaloncustomer" cols="15" rows="3"></textarea>
					</div>
					<div class="form-group">
						</br>
						<label for="email" class="form-control-label">Email <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="email" name="email" required>
					</div>
					<div class="form-group">
						<label for="website" class="form-control-label">Website <span class="text-danger">*</span> :</label>
						<input type="text" class="form-control" id="website" name="website" required>
					</div>
					<div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
							<button type="button" id="btn-edit-form-pelanggan" class="btn btn-success btn-edit" data-dismiss="modal">Update</button>
						</div>
					</div>

					<?= form_close(); ?>
				</div>
			</div>
		</div>

		<div id="main-modal-cetaklaporan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content ">
					<div class="modal-header bg-dark text-light">
						<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Rekapan Approval </h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?>
					<div class="modal-body">
						<div class="dt-marketing-form">
							<div class="form-group" style="display: flex;">
								<div style="flex: 50%;padding: 10px;">
									<input class="form-control" data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
								</div>
								<div style="flex: 50%;padding: 10px;">
									<input class="form-control" data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
								</div>
							</div>

						</div>
					</div>
					<div class="modal-footer">
						<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
						<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
						<!-- <button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>-->
						<button type="button" id="btn-export" class="btn btn-success btn-clear-form">Export Excel</button>
					</div>
					<?= form_close(); ?>
				</div>
			</div>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var i = 1;
				$('#add').click(function() {
					if ($('#namapic').val() != '') {
						i++;
						$('#dynamic_field').append('<div id="row' + i + '"><div class="form-group"><div id="dynamic_field"><label class="control-label col-sm-9" for="namapic">Nama PIC:</label><div class="col-sm-9"><input hidden type="text" class="form-control" id="picpic" name="picpic" value=""><input type="text" class="form-control" id="namapic" placeholder="Masukkkan Nama PIC" name="namapic[]" autocomplete="off"></div><label class="control-label col-sm-9" for="jabatanpic">Jabatan PIC:</label><div class="col-sm-9"><input type="email" class="form-control" id="jabatanpic" placeholder="Masukkan Jabatan PIC" name="jabatanpic[]" autocomplete="off"></div><label class="control-label col-sm-6" for="teleponpic">Telepon PIC:</label><div class="col-sm-10"><div class="input-group mb-12"><input type="number" class="form-control" id="teleponpic" placeholder="Masukkan Telepon PIC" name="teleponpic[]" autocomplete="off"><span class="input-group-append">&nbsp &nbsp &nbsp</span><span class="input-group-append"><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></span></div></div></div></div>');
						$('#count').val(i);
					};
				});

				$(document).on('click', '.btn_remove', function() {
					i--;
					$('#count').val(i);
					var button_id = $(this).attr("id");
					var res = confirm('Apakah PIC ini akan dihapus?');
					if (res == true) {
						$('#row' + button_id + '').remove();
						$('#' + button_id + '').remove();
					}
				});


				$("#dpihakketiga").hide();
				table = $('#kt_table_1').DataTable({
					responsive: false,
					processing: true,
					serverSide: true,

					ajax: {
						url: 'calonpelanggan/pagination',
						type: 'POST',
						data: function(e) {
							e.tahun = $('#tahun').val()
							e.csrf_token = token
						}
					},


					columns: [{
							"width": "1%"
						},
						{
							"width": "1%"
						},
						{
							"width": "1%"
						},
						{
							"width": "2%"
						},
						{
							"width": "11%"
						},
						{
							"width": "20%"
						},
						{
							"width": "24%"
						},
						{
							"width": "10%"
						},
						{
							"width": "10%"
						},
						{
							"width": "8%"
						},
						{
							"width": "8%"
						},
						{
							"width": "7%"
						},
						{
							"width": "7%"
						},


					],

					columnDefs: [{
							'visible': false,
							'targets': [1, 2],

						}
						//{
						//    "targets": [1,2],
						//    "render" : function (data, type, row) {
						//        if(data == null) {
						//            table.columns([column_number]).visible(false);
						//        }

						//    }
						//},               
					],

				})

				table2 = $('#kt_table_2').DataTable({
					responsive: false,
					processing: true,
					serverSide: true,

					ajax: {
						url: 'calonpelanggan/paginationmodality/12k72k',
						type: 'POST',
						data: function(e) {
							e.csrf_token = token
						},

					},
				})

				function updateDatatable() {
					table.ajax.reload(null, false)
				}

				$('#btn-cetaklaporan-form').click(function() {
					$('#main-modal-marketing').modal()


				})

				$("#btn-export").click(function() {

					tglawal = $("#tglawal").val();
					tglakhir = $("#tglakhir").val();
					idmarketing = $("#namamarketing").val();
					namamarketing = $("#namamarketing option:selected").text();
					window.open("<?php echo base_url(); ?>calonpelanggan/exportlaporan/search?tglawal=" + encodeURIComponent(tglawal) + "&tglakhir=" + encodeURIComponent(tglakhir) + "&idmarketing=" + encodeURIComponent(idmarketing) + "&namamarketing=" + encodeURIComponent(namamarketing), "_blank");
					$('#main-modal-marketing').modal('hide')

				});





				$('#btn-show-add-form').click(function() {
					$("#grouppic").hide();
					$('.form-control').val(null)
					var object = 'calonpelanggan'
					$('#main-modal-calon #modal-form-calon').attr('action', 'calonpelanggan/add')
					$('#main-modal-calon').modal()

				})

				$('#btn-simpan-form-calon').click(function() {
					//	alert($('#kodecaloncustomer').val())
					if ($('#propinsi').val() != '') {
						if ($('#kota').val() != '') {
							if ($('#kelascustomer').val() != '') {
								var kodecc = $('#provinsi').val() + "/" + $('#kota').val() + "/" + $('#kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
								$('#kodecaloncustomer').val(kodecc)
							}
						}
					}
				})


				$("#chkberkasdokumen").on("change", function(e) {
					var pos = e.target.id.replace('chk', '')
					var chk = $("input[id='chk" + pos + "']");
					var lnk = $("input[id='link" + pos + "']");
					var apply = chk.is(':checked') ? true : false;
					lnk.hide()
					chk.val(apply)
					if (apply == true) {
						lnk.show()
					}

				})

				$("#chkberkasdokumenlain").on("change", function(e) {
					var pos = e.target.id.replace('chklainnya', '')
					var chk = $("input[id='chklainnya" + pos + "']");
					var lnk = $("input[id='linklainnya" + pos + "']");
					var apply = chk.is(':checked') ? true : false;
					lnk.hide()
					chk.val(apply)
					if (apply == true) {
						lnk.show()
					}

				})

				$("#kt_table_1").on('click', '.btn-success', function() {
					//console.log(table.row($(this).parents("tr")).data());
					var currentRow = table.row($(this).parents("tr")).data();
					var idc = currentRow[1];
					var kcc = currentRow[2].substr(0, 9) + "<?= sprintf('%03d', $kodecustomer); ?>"; //003/62/B/
					var namapel = currentRow[5];
					var dateStr = currentRow[8].trim();

					$('#main-modal-pelanggan').modal('show')
					$('#main-modal-pelanggan #modal-form-pelanggan').attr('action', 'calonpelanggan/addpelanggan')
					$('#kodecaloncustomer2').val(currentRow[2].trim())
					$('#idcalon').val(idc)
					$('#kodecustomer').val(kcc)
					$('#namapelanggan').val(namapel)
					$('#tanggalregistrasi').val(dateStr)

				});

				$("#kt_table_1").on('click', '.btn-dark', function() {
					idpelanggan = '';
					var currentRow = table.row($(this).parents("tr")).data();
					var idpel = currentRow[1];
					var idcalonpel = currentRow[1];
					var kodepel = currentRow[2];
					//console.log(idpel);
					//console.log(idcalonpel);
					//console.log(kodepel);
					$('#kodepelanggan').val(kodepel);
					$('#idpelanggan').val(idpel);
					$('#idcalonpelanggan').val(idcalonpel);
					table2.ajax.url('calonpelanggan/paginationmodality/' + idpel).load();


					$('#main-modal-modality #modal-form-modality').attr('action', 'calonpelanggan/addmodality')
					$('#main-modal-modality').modal()
					$('#kt_table_2').DataTable().ajax.reload();

				});
				$(document).on('click', '.btn-delete', function() {
					var currentRow = table.row($(this).parents("tr")).data();
					var idc = currentRow[1];
					var namacalon = currentRow[5];
					var par = $(this).data("id")
					$.ajax({
						type: "POST",
						url: "calonpelanggan/delete/" + par,
						async: false,
					});

				});

				$(document).on('click', '.btn-edit', function() {

					var par = $(this).data("id");
					var url = "calonpelanggan/edit/" + par

					$.ajax({
						type: "GET",
						url: url,
						success: function(response) {
							//console.log(response)  	
							if (response) {
								result = JSON.parse(response);
								$('#main-modal-calonedit #idcalonedit').val(result['id']);
								$('#main-modal-calonedit #kodecaloncustomer').val(result['kodecaloncustomer']);
								$('#main-modal-calonedit #namacaloncustomer').val(result['namacaloncustomer']);
								$('#main-modal-calonedit #tipecustomer').val(result['tipecustomer']);
								$('#main-modal-calonedit #kelascustomer').val(result['kelascustomer']);
								$('#main-modal-calonedit #provinsi').val(result['id_prov']);
								var url = "<?php echo site_url('calonpelanggan/add_ajax_kota2'); ?>/" + result['id_prov'] + "/" + result['id_kota'];
								$('#main-modal-calonedit #kota').load(url);
								$('#main-modal-calonedit #kota').val(result['id_kota']).attr('selected', 'selected');
								$('#main-modal-calonedit #alamatcaloncustomer').val(result['alamatcaloncustomer']);
								$('#main-modal-calonedit #email').val(result['email']);
								$('#main-modal-calonedit #website').val(result['website']);
								$('#main-modal-calonedit').modal();
							}
						},
						error: function(request, status, error) {
							alert(request.responseText);
						}
					});


				})

				$("#kt_table_2").on('click', '.btn-deletemodality', function(e) {
					e.preventDefault();
					swal.fire({
						title: "Apakah Yakin menghapusnya..?",
						text: "Data tidak dapat dikembalikan, jika ingin dikembalikan hubungi Administrator!",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Ya, hapus..!",
						closeOnConfirm: false
					}).then((result) => {
						if (result.isConfirmed) {
							var par = $(this).data("id");
							$.ajax({
								type: "POST",
								url: "calonpelanggan/deletemodality/" + par,
								async: false,
							});
							$('#kt_table_2').DataTable().ajax.reload();
						}
					})
				})

				$('#btn-edit-form-pelanggan').click(function() {
					$.ajax({
						type: "POST",
						url: "calonpelanggan/update",
						cache: false,
						data: {
							id: $('#main-modal-calonedit  #idcalonedit').val(),
							kodecaloncustomer: $('#main-modal-calonedit #kodecaloncustomer').val(),
							namacaloncustomer: $('#main-modal-calonedit #namacaloncustomer').val(),
							kelascustomer: $('#main-modal-calonedit #kelascustomer').val(),
							tipecustomer: $('#main-modal-calonedit #tipecustomer').val(),
							provinsi: $('#main-modal-calonedit #provinsi').val(),
							kota: $('#main-modal-calonedit #kota').val(),
							alamatcaloncustomer: $('#main-modal-calonedit #alamatcaloncustomer').val(),
							email: $('#main-modal-calonedit #email').val(),
							website: $('#main-modal-calonedit #website').val(),

						},
						success: function(result) {
							var pesan = $.parseJSON(result)
							Swal.fire({
								type: 'success',
								icon: 'success',
								title: pesan.msg,
								showConfirmButton: false,
								timer: 2000
							});
						},
					});
					//	window.location.reload();
				})

				$('#main-modal-calon #namacaloncustomer').on('change', function() {
					$('#count').val(i);
					if ($('#main-modal-calon #namacaloncustomer').val() == '') {
						$("#main-modal-calon #grouppic").hide();
					} else {
						$("#main-modal-calon #grouppic").show();
					};
				});

				$('#main-modal-calon #tipecustomer').on('change', function() {
					var tipecustomer = this.value;
					if (tipecustomer == 'Pihak Ketiga') {
						$("#main-modal-calon #dpihakketiga").show();
					} else {
						$("#main-modal-calon #dpihakketiga").hide();
					}
				});

				$('#main-modal-calon #provinsi').on('change', function() {
					$('#main-modal-calon #kodecaloncustomer').val('')
					var kode = this.value;
					var url = "<?php echo site_url('calonpelanggan/add_ajax_kota'); ?>/" + kode;
					$('#main-modal-calon #kota').load(url);
					return false;
				});

				$('#main-modal-calonedit #provinsi').on('change', function() {
					var kodecc = $('#main-modal-calonedit #provinsi').val() + "/" + pad($('#main-modal-calonedit #kota').val(), 2) + "/" + $('#main-modal-calonedit #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
					$('#main-modal-calonedit #kodecaloncustomer').val(kodecc)
					var kode = this.value;
					var url = "<?php echo site_url('calonpelanggan/add_ajax_kota'); ?>/" + kode;
					$('#main-modal-calonedit #kota').load(url);
					return false;
				});



				$('#main-modal-calon #kota').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calon #kelascustomer').val() != '') {
							var kodecc = $('#main-modal-calon #provinsi').val() + "/" + pad($('#main-modal-calon #kota').val(), 2) + "/" + $('#main-modal-calon #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calon #kodecaloncustomer').val(kodecc)
						}
					}
				});
				$('#main-modal-calonedit #kota').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calonedit #kelascustomer').val() != '') {
							var kodecc = $('#main-modal-calonedit #provinsi').val() + "/" + pad($('#main-modal-calonedit #kota').val(), 2) + "/" + $('#main-modal-calonedit #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calonedit #kodecaloncustomer').val(kodecc)
						}
					}
				});

				$('#main-modal-calon #kelascustomer').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calon #kota').val() != '') {
							var kodecc = $('#main-modal-calon #provinsi').val() + "/" + pad($('#main-modal-calon #kota').val(), 2) + "/" + $('#main-modal-calon #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calon #kodecaloncustomer').val(kodecc)
						}
					}
				});
				$('#main-modal-calonedit #kelascustomer').on('change', function() {
					if (this.value != '') {
						if ($('#main-modal-calonedut #kota').val() != '') {
							var kodecc = $('#main-modal-calonedit #provinsi').val() + "/" + pad($('#main-modal-calonedit #kota').val(), 2) + "/" + $('#main-modal-calonedit #kelascustomer').val() + "/" + "<?= sprintf('%03d', $kodecaloncustomer); ?>"
							$('#main-modal-calonedit #kodecaloncustomer').val(kodecc)
						}
					}
				});



			})


			function pad(n, len) {
				let l = Math.floor(len)
				let sn = '' + n
				let snl = sn.length
				if (snl >= l) return sn
				return '0'.repeat(l - snl) + sn
			}
		</script>