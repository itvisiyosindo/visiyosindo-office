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
			<?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa()) { ?>
				<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-danger"><i class="icons icon-plus"></i>&nbsp;Tambah Funnel</a>
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Cetak Laporan</a>
			<?php } ?>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
						<thead>					
							<tr>
								<th> # </th>
								<!--<th> ID </th> -->
								<th> Nama Pelanggan </th> 
								<!--<th> Provinsi Kota </th>-->
								<th> Nama Marketing </th>
								<th> Jenis Pekerjaan </th>
								<th> Rencana </th>
								<th> Merk</th>
								<th> Nama Product </th>
								<th> Realisasi </th>
								<th> Progress </th>
								<!-- <th> Keterangan </th>
								<th> Kendala </th>		 -->
								<th> Diajukan Oleh </th>
								<th> Tanggal Rencana </th>
								<th> Tanggal Realisasi </th>
        					    <th> Update Laporan </th>
                                <th> Aksi </th>						        	
							</tr>
						</thead>
					</table>
			</div>
		</div>
	</div>
</div>
<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Laporan Data Marketing  </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
				    <div class="form-group" style="display: flex;">
				        <div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
						</div>
						<div style="flex: 50%;padding: 10px;">
						<input class="form-control"  data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label">Nama Marketing</label>
						 <select class="select-transaction input-group-sm form-control" name="namamarketing" id="namamarketing">
								<?php if ($nama_marketing != NULL): ?>
													<option value=''>— Semua Marketing —</option>
													<?php foreach ($nama_marketing as $value): ?>
													<option value="<?php echo $value->pengguna_id;?>"><?php echo $value->nama;?></option>
													<?php endforeach;?>
													<?php else:?>
													<option value=''>— Tidak ada data —</option>
													<?php endif;?>
													</select>
													<?php echo form_error('nama_marketing');?>
					</div>
					<div class="form-group" style="display: flex;">
					    <div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="provinsi"  name="provinsi" checked>&nbsp;&nbsp;Provinsi Kota 
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="perjalanankerja"  name="perjalanankerja" checked>&nbsp;&nbsp;Perjalanan Kerja
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="rencana"  name="rencana" checked>&nbsp;&nbsp;Rencana Kerja
    					</div>
    				</div>
    				<div class="form-group" style="display: flex;">
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="tujuankegiatan"  name="tujuankegiatan" checked>&nbsp;&nbsp;Tujuan Kegiatan
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="tipecustomer"  name="tipecustomer" checked>&nbsp;&nbsp;Tipe Customer
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="pic"  name="pic" checked>&nbsp;&nbsp;PIC
    					</div>
    				</div>
    				<div class="form-group" style="display: flex;">
    				    <div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="tglrealisasi"  name="tglrealisasi" checked>&nbsp;&nbsp;Tanggal Realisasi
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="realisasi"  name="realisasi" checked>&nbsp;&nbsp;Realisasi
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="funnelstatus"  name="funnelstatus" checked>&nbsp;&nbsp;Funnel Status
    					</div>
					</div>
					<div class="form-group" style="display: flex;">
					    <div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="progress"  name="progress" checked>&nbsp;&nbsp;Progress
    					</div>
					    <div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="product"  name="product" checked>&nbsp;&nbsp;Product
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="merk"  name="merk" checked>&nbsp;&nbsp;Merk
    					</div>
    			    </div>
    			    <div class="form-group" style="display: flex;">
    			        <div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="kompetitor"  name="kompetitor" checked>&nbsp;&nbsp;Kompetitor
    					</div>
    			        <div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="peluang"  name="peluang" checked>&nbsp;&nbsp;Peluang
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="keterangan"  name="keterangan" checked>&nbsp;&nbsp;Keterangan
    					</div>

				    </div>
				    <div class="form-group" style="display: flex;">
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="kendala"  name="kendala" checked>&nbsp;&nbsp;Kendala
    					</div>
				        <div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="analisa"  name="analisa" checked>&nbsp;&nbsp;Hasil Analisa
    					</div>
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="modality"  name="modality" checked>&nbsp;&nbsp;Modality
    					</div>
				    </div>
						<div class="form-group" style="display: flex;">
    					<div style="flex: 30%;padding: 1px;">
        					<input type="checkbox" id="linkDokumentasi"  name="linkDokumentasi" checked>&nbsp;&nbsp;Link Dokumentasi
    					</div>
				    </div>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
		    	<button type="button" id="btn-cetak" class="btn btn-primary btn-clear-form" >Cetak</button>
				<button type="button" id="btn-export" class="btn btn-success btn-clear-form" >Export Excel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<div id="main-modal-funnel" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Funnel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open("<?= site_url('funnel/printlaporan/') ?>" , array('id' => 'modal-form-funnel', 'autocomplete' => 'off')); ?>
				<div class="modal-body">
					<div class="dt-funnel-form">
						<div class="form-group">
							<div hidden>
								<label for="idpelanggan" class="form-control-label">ID Customer <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control col-sm-5" id="idpelanggan" name="idpelanggan"  readonly required/>
								<input type="text" class="form-control col-sm-5" id="namacalonpelanggan" name="namacalonpelanggan"  readonly required/>
							</div>
						</div>
						<div class="form-group">
					    <input class="form-control"  data-provide="datepicker" name="tanggal" id="tanggal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Funnel" required>
						</div>
						<div class="form-group">
							<label class="control-label">Nama Customer</label>
							<select data-plugin-selectTwo class="form-control populate" name="namapelanggan" id="namapelanggan" data-placeholder="Data Nama Calon/Pelanggan" >
															<option selected>— Pilih Nama Calon/Pelanggan —</option>
																<?php
                                if ($pelanggan != NULL) :
                                    foreach ($pelanggan as $row) :
                                        echo '<option value="' . $row->id . '">' . $row->namacaloncustomer . '</option>';
																		endforeach;
																else :		
																	echo '<option>— Tidak ada data —</option>';
                                endif;
                                ?>
                            </select> 
														<?php echo form_error('pelanggan');?>
						</div>
						<div class="form-group">
							<label for="provinsikota" class="form-control-label">Provinsi - Kabupaten/Kota <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="provinsikota" name="provinsikota" required readonly/>
						</div>
						<div class="form-group">
						<label for="jenispekerjaan" class="form-control-label">Jenis Pekerjaan <span class="text-danger">*</span> :</label>
						<select class="form-control" id="jenispekerjaan" name="jenispekerjaan"  required>
							<option value="">- Pilih Jenis Pekerjaan -</option>
							<option value="Stay">Stay</option>
							<option value="Dinas">Dinas</option>
						</select>
					</div>
						<div class="form-group">
							<label class="control-label">Rencana <span class="box text-danger"><sup>(*) </sup></span></label>
							<div class="col-sm-12">
								<div class="input-group mb-12">
								<select class="select-transaction input-group-sm form-control" name="statusfunnel" id="statusfunnel">
																						<?php if ($statusfunnel != NULL): ?>
																								<option>— Pilih Status Funnel —</option>
																								<?php foreach ($statusfunnel as $value): ?>
																								<option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
																								<?php endforeach;?>
																								<?php else:?>
																								<option>— Tidak ada data —</option>
																						<?php endif;?>
																						</select>
																						<?php echo form_error('statusfunnel');?>
																						<div hidden>
																							<span class="input-group-append">&nbsp; &nbsp; </span>
																							<span class="box">
																								<input type="checkbox" id="rencana" class="check" name="rencana" checked>&nbsp; <label class="control-label"><strong>Rencana</strong></label></input>
																							</span>			
																						</div>
																																			
								</div>
							</div>
						</div>	
						<div class="form-group">
							<label for="tujuankegiatan" class="form-control-label">Tujuan Kegiatan <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="tujuankegiatan" name="tujuankegiatan" required/>
						</div>			
						<div class="form-group" id='picpic' style="display: inline-block;">
									<label for="pic" class="form-control-label">PIC <span class="text-danger">*</span> :</label>
									<select data-plugin-selectTwo class="form-control populate" name="pic[]" id="pic[]" style="display: inline-block;"  multiple disabled="disabled" >
									
									</select>
						</div>
						<div class="form-group" id="merek">
							<label class="control-label">Merk Product</label>
							<!-- <input type="text" class="form-control" id="merkproduct" name="merkproduct"  required/>	 -->
							<select data-plugin-selectTwo class="form-control populate" name="merkproduct" id="merkproduct" data-placeholder="Data Merk Product" >
							<option selected>— Pilih Merk Product —</option>
								<?php
                                if ($merkproduk != NULL) :
                                    foreach ($merkproduk as $row) :
                                        echo '<option value="' . $row->id_kategori . '">' . $row->nama_kategori . '</option>';
										endforeach;
								else :		
									echo '<option>— Tidak ada data —</option>';
                                endif;
                                ?>
                            </select> 
								<?php echo form_error('merkproduct');?>
						
						</div>
						<div class="form-group">	
							<label class="control-label">Nama Product</label>	
							<select data-plugin-selectTwo class="form-control populate" name="namaproduct" id="namaproduct" data-placeholder="Data Nama Product" >
									<option value=''>- Pilih Nama Product -</option>
							</select>
									<?php echo form_error('namaproduct');?>
						</div>
						<div class="form-group" hidden>
							<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
							<textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"></textarea>
						</div>
						<div class="form-group" hidden>
							<label for="kendala" class="form-control-label">Kendala <span class="text-danger">*</span> :</label>
							<textarea name="kendala" class="form-control" id="kendala" cols="15" rows="3"></textarea>
						</div>
						<div class="form-group">
							<div class="form-group" id="groupkompetitor">
								<div id="dynamic_field">
									<input hidden type="text" class="form-control" id="kompkomp" name="kompkomp">
									<label class="control-label col-sm-9" for="namakomp">Nama Kompetitor:</label>
									<div class="col-sm-9">
										<input type="number" class="form-control" id="count" name="count" hidden>
										<input type="text" class="form-control" id="namakompetitor" placeholder="Masukkkan Nama Kompetitor" name="namakompetitor[]" autocomplete="off">
									</div>
									<label class="control-label col-sm-9" for="produkkompetitor">Nama Product Kompetitor:</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="produkkompetitor" placeholder="Masukkkan Nama Product Kompetitor" name="produkkompetitor[]" autocomplete="off">
									</div>
									<label class="control-label col-sm-6" for="hargakompetitor">Harga Kompetitor:</label>
									<div class="col-sm-12"> 
										<div class="input-group mb-12">
											<input type="number" class="form-control" id="hargakompetitor" placeholder="Masukkan Harga Kompetitor" name="hargakompetitor[]" autocomplete="off">
											<span class="input-group-append">&nbsp &nbsp &nbsp</span>
											<span class="input-group-append">
												<button type="button" name="add" id="add" class="btn btn-success">Tambah Kompetitor</button>
											</span>
										</div>
									</div>
								</div>
							</div>  
						</div>	
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
<div id="main-modal-updatehistori" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="width: 100%;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
					<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Update Funnel</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>
<div id="main-modal-histori" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="width: 100%;" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Histori</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-histori', 'autocomplete' => 'off')); ?>
				<div class="modal-body">
					<div class="dt-histori-form">
						<div class="form-group" hidden >
							<input type="text" class="form-control" id="idcalonpelanggan" name="idcalonpelanggan" required>
						</div>	
						<div class="row">
							<div class="col">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover" id='kt_table_2'>
											<thead>
												<tr>
													<th> # </th>
													<th> ID </th>
													<th> Status </th>
													<th> Realisasi </th>
													<th> Progress </th>
													<th> Peluang Keberhasilan (%) </th>
													<th> Keterangan </th>
													<th> Kendala </th>
													<th> Hasil Analisa </th>
													<th> Dokumentasi </th>
													<th> Tanggal </th> 
													<th> Aksi </th> 
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
						<input type="hidden" id="id_pelanggan" name="id_pelanggan">
						<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				</div>		
			<?= form_close(); ?>
		</div>
	</div>
</div>
<div id="main-modal-funneledit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Update Funnel </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-funneledit', 'autocomplete' => 'off')); ?> 
				<div class="modal-body">
					<div class="dt-funneledit-form">
						<div class="form-group">
							<div hidden>
								<input type="text" class="form-control col-sm-5" id="id" name="id" readonly required/>
							</div>
						</div>
						<div class="form-group">
					    <input class="form-control"  data-provide="datepicker" name="tanggal" id="tanggal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Funnel" required>
						</div>
						<div class="form-group">
								<label for="namacaloncustomer" class="form-control-label">Nama Calon Customer <span class="text-danger">*</span> :</label>
								<input type="text" class="form-control" id="namacaloncustomer" name="namacaloncustomer" readonly required>
							</div>
						<div>
						<div class="form-group">
							<label for="jenispekerjaan" class="form-control-label">Jenis Pekerjaan <span class="text-danger">*</span> :</label>
							<select class="form-control" id="jenispekerjaan" name="jenispekerjaan"  required>
								<option value="">- Pilih Jenis Pekerjaan -</option>
								<option value="Stay">Stay</option>
								<option value="Dinas">Dinas</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">Rencana <span class="box text-danger"><sup>(*) </sup></span></label>
							<div class="col-sm-12">
								<div class="input-group mb-12">
								<select class="select-transaction input-group-sm form-control" name="statusfunnel" id="statusfunnel">
									<?php if ($statusfunnel != NULL): ?>
											<option>— Pilih Status Funnel —</option>
											<?php foreach ($statusfunnel as $value): ?>
											<option value="<?php echo $value->id;?>"><?php echo $value->nama;?></option>
											<?php endforeach;?>
											<?php else:?>
											<option>— Tidak ada data —</option>
									<?php endif;?>
									</select>
									<?php echo form_error('statusfunnel');?>
									<div hidden>
										<span class="input-group-append">&nbsp; &nbsp; </span>
										<span class="box">
											<input type="checkbox" id="rencana" class="check" name="rencana" checked>&nbsp; <label class="control-label"><strong>Rencana</strong></label></input>
										</span>			
									</div>
																																			
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="tujuankegiatan" class="form-control-label">Tujuan Kegiatan <span class="text-danger">*</span> :</label>
							<input type="text" class="form-control" id="tujuankegiatan" name="tujuankegiatan" required/>
						</div>	
						<div class="form-group" id="merek">
							<label class="control-label">Merk Product</label>
							<select class="select-transaction input-group-sm form-control" name="merkproduct" id="merkproduct">
										<?php if ($merkproduk != NULL): ?>
												<option>— Pilih Merk Product —</option>
												<?php foreach ($merkproduk as $value): ?>
												<option value="<?php echo $value->id_kategori;?>"><?php echo $value->nama_kategori;?></option>
												<?php endforeach;?>
												<?php else:?>
												<option>— Tidak ada data —</option>
										<?php endif;?>
										</select>
										<?php echo form_error('merkproduct');?>
						</div>
						<div class="form-group">	
							<label class="control-label">Nama Product</label>	
							<select name="namaproduct" class="form-control" id="namaproduct">
									<option value=''>- Pilih Nama Product -</option>
							</select>
									<?php echo form_error('namaproduct');?>
						</div>
						<div class="form-group">
							<label for="keterangan" class="form-control-label">Keterangan <span class="text-danger">*</span> :</label>
							<textarea name="keterangan" class="form-control" id="keterangan" cols="15" rows="3"></textarea>
						</div>
						<div class="form-group">
							<label for="kendala" class="form-control-label">Kendala <span class="text-danger">*</span> :</label>
							<textarea name="kendala" class="form-control" id="kendala" cols="15" rows="3"></textarea>
						</div>			
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
						<button type="button" id="btn-edit-form-funnel" class="btn btn-success btn-edit" data-dismiss="modal">Update</button>
					</div>											
				</div>
			
			<?= form_close(); ?>
		</div>
	</div>
</div>
<script>
   
	document.addEventListener('DOMContentLoaded', function() {
		var i=1;  
    $('#add').click(function(){ 		
				if($('#namakomp').val()!=''){
	        i++;             
  	      $('#dynamic_field').append('<div id="row'+i+'">'+
    			'<div class="form-group">'+
    					'<div id="dynamic_field">'+
    							'<label class="control-label col-sm-9" for="namakompetitor">Nama Kompetitor:</label>'+
    									'<div class="col-sm-9">'+
    											'<input hidden type="text" class="form-control" id="kompkomp" name="kompkomp">'+
    											'<input type="text" class="form-control" id="namakompetitor" placeholder="Masukkkan Nama Kompetitor" name="namakompetitor[]" autocomplete="off">'+
    									'</div>'+
    									'<label class="control-label col-sm-9" for="produkkompetitor">Nama Product Kompetitor:</label>'+
    									'<div class="col-sm-9">'+
    											'<input type="text" class="form-control" id="produkkompetitor" placeholder="Masukkkan Nama Product Kompetitor" name="produkkompetitor[]" autocomplete="off">'+
    									'</div>'+
    									'<label class="control-label col-sm-6" for="hargakomp">Harga Kompetitor:</label>'+
    									'<div class="col-sm-10">'+
    											'<div class="input-group mb-12">'+
    													'<input type="number" class="form-control" id="hargakompetitor" placeholder="Masukkan Harga Kompetitor" name="hargakompetitor[]" autocomplete="off">'+
    													'<span class="input-group-append">'+
    														'<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>'+
    													'</span>'+
    											'</div>'+
    									'</div>'+
    						'</div>'+
    			'</div>'+
    		'</div>');
					$('#count').val(i);
				};
    });
    
    $(document).on('click', '.btn_remove', function(){  
		i--;   
		$('#count').val(i);
           var button_id = $(this).attr("id"); 
           var res = confirm('Apakah PIC ini akan dihapus?');
           if(res==true){
           $('#row'+button_id+'').remove();  
           $('#'+button_id+'').remove();  
           }
    });  
		
		

		var table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			
			ajax: {
				url: 'funnel/paginationBeta',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			
			columns: [
				{ "width": "1%" },
				//{ "width": "1%" },
				{ "width": "24%" },
				//{ "width": "6%" },
				{ "width": "7%" },
				{ "width": "6%" },
				{ "width": "6%" },
				{ "width": "11%" },
				{ "width": "19%" },
				{ "width": "6%" },
    			{ "width": "6%" },
    			{ "width": "6%" },
	    		{ "width": "7%" },
	    		{ "width": "7%" },
    			{ "width": "6%" },
	    		{ "width": "6%" }
			
			],
		
		         //columnDefs: [
              //      { visible: false,  targets: [1,3] }
            //	]
		   
		})

		table2 = $('#kt_table_2').DataTable({
				responsive: false,
				processing: true,
				serverSide: true,
			
				ajax: {
					url: 'funnel/paginationhistori/12k72k',
					type: 'POST',
					data: function(e) {
						e.csrf_token = token
					},
					
				},
				
				columnDefs: [
                    { visible: false,  targets: [1] }
            	]
			})

	
		 $(document).on('click', '#btn-show-histori-form', function() {
			
				var id = $(this).attr("data-id")
				table2.ajax.url('funnel/paginationhistori/'+id).load();
				$('#kt_table_2').DataTable().ajax.reload();
				$('#main-modal-histori #modal-form-histori').attr('action', 'funnel/addfunnelhistori')
				$('#main-modal-histori').modal()
				
		})

		$(document).on('click', '.btn-edit', function() {
			
					var par = $(this).data("id"); 
					var url ="funnel/edit/"+par
					
					$.ajax({
							type: "GET",
							url: url,
							success: function(response) {     
								console.log(response)  	
								if (response) {
									  result = JSON.parse(response);
										$('#main-modal-funneledit #id').val(result['id']);
										$('#main-modal-funneledit #tanggal').val(result['tanggal']);
										$('#main-modal-funneledit #namacaloncustomer').val(result['namacaloncustomer']);
										$('#main-modal-funneledit #jenispekerjaan').val(result['jenispekerjaan']);
										$('#main-modal-funneledit #tujuankegiatan').val(result['tujuankegiatan']);
										$('#main-modal-funneledit #statusfunnel').val(result['idstatusfunnel']);
										$('#main-modal-funneledit #merkproduct').val(result['idkategori']);
										var kode = result['idkategori'];
										if(kode>0){
											var url = "<?php echo site_url('funnel/add_namaproduct2/');?>"+kode+'/'+result['idproduct'];
											$('#main-modal-funneledit #namaproduct').load(url);
										}
										$('#main-modal-funneledit #namaproduct').val(result['idproduct']);
										$('#main-modal-funneledit #keterangan').val(result['keterangan']);
										$('#main-modal-funneledit #kendala').val(result['kendala']);
										$('#main-modal-funneledit').modal();
								}
    					},
							error: function (request, status, error) {
									alert(request.responseText);
							}					
				});
				
				
			})
		$('#btn-edit-form-funnel').click(function() {
			$.ajax({
							type: "POST",
							url: "funnel/update",
							cache: false,
							data: {
									id: $('#main-modal-funneledit #id').val(),
									tanggal: $('#main-modal-funneledit #tanggal').val(),
									namacaloncustomer: $('#main-modal-funneledit #namacaloncustomer').val(), 
									jenispekerjaan: $('#main-modal-funneledit #jenispekerjaan').val(),
									tujuankegiatan: $('#main-modal-funneledit #tujuankegiatan').val(),
									statusfunnel: $('#main-modal-funneledit #statusfunnel').val(),
									idkategori: $('#main-modal-funneledit #idkategori').val(),
									namaproduct: $('#main-modal-funneledit #namaproduct').val(),
									keterangan: $('#main-modal-funneledit #keterangan').val(),
									kendala: $('#main-modal-funneledit #kendala').val(),      
									
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
				window.location.reload();
	  })
		$(document).on('click', '#btn-delete-funnel', function(){
				 var currentRow = table.row($(this).parents("tr")).data();  
		 		 var idc = currentRow[1];
				  var namacalon = currentRow[5];
				var par = $(this).data("id")
					$.ajax({
							type: "POST",
							url: "funnel/delete/"+par,
							async: false,
					});
				
			});

		$(document).on('click', '#btn-delete-update', function(){
				 var currentRow = table.row($(this).parents("tr")).data();  
		 		 var idc = currentRow[1];
				  var namacalon = currentRow[5];
				var par = $(this).data("id")
					$.ajax({
							type: "POST",
							url: "funnel/hapusupdate/"+par,
							async: false,
					});
				
			});	

		$('#btn-laporan-form').click(function() {
		     $('#main-modal-marketing').modal()	
		     
    			
		})
		
		 $("#btn-cetak").click(function(){
		   /* if($('#main-modal-marketing #namamarketing').val()==''){
					Swal.fire({
                         icon: 'error',
            			 title: 'Oops...',
            			text: 'Nama Marketing tidak boleh kosong..!!',
                        showConfirmButton: false,
                        timer: 2000
                      });
			}else{ */
                tglawal = $("#tglawal").val();
                tglakhir = $("#tglakhir").val();
                idmarketing = $("#namamarketing").val();
                namamarketing = $("#namamarketing option:selected").text();
                provinsi = $("#provinsi:checkbox").is(":checked") ? 1:0;
                perjalanankerja = $("#perjalanankerja:checkbox").is(":checked") ? 1:0;
                rencana = $("#rencana:checkbox").is(":checked") ? 1:0;
                tujuankegiatan = $("#tujuankegiatan:checkbox").is(":checked") ? 1:0;
                tipecustomer = $("#tipecustomer:checkbox").is(":checked") ? 1:0;
                pic = $("#pic:checkbox").is(":checked") ? 1:0;
                tglrealisasi = $("#tglrealisasi:checkbox").is(":checked") ? 1:0;
                realisasi = $("#realisasi:checkbox").is(":checked") ? 1:0;
                funnelstatus = $("#funnelstatus:checkbox").is(":checked") ? 1:0;
                progress = $("#progress:checkbox").is(":checked") ? 1:0;
                product = $("#product:checkbox").is(":checked") ? 1:0;
                merk = $("#merk:checkbox").is(":checked") ? 1:0;
                kompetitor = $("#kompetitor:checkbox").is(":checked") ? 1:0;
                peluang = $("#peluang:checkbox").is(":checked") ? 1:0;
                modality = $("#modality:checkbox").is(":checked") ? 1:0;
                keterangan = $("#keterangan:checkbox").is(":checked") ? 1:0;
                kendala = $("#kendala:checkbox").is(":checked") ? 1:0;
                analisa = $("#analisa:checkbox").is(":checked") ? 1:0;
                linkDokumentasi = $("#linkDokumentasi:checkbox").is(":checked") ? 1:0;
                namamarketing = $("#namamarketing option:selected").text();
                 window.open("<?php echo base_url(); ?>funnel/printlaporan/search?tglawal="+encodeURIComponent(tglawal)+"&tglakhir="+encodeURIComponent(tglakhir)+"&idmarketing="+encodeURIComponent(idmarketing)+"&namamarketing="+encodeURIComponent(namamarketing)+"&provinsi="+provinsi+"&perjalanankerja="+perjalanankerja+"&rencana="+rencana+"&tujuankegiatan="+tujuankegiatan+"&tipecustomer="+tipecustomer+"&pic="+pic+"&tglrealisasi="+tglrealisasi+"&realisasi="+realisasi+"&funnelstatus="+funnelstatus+"&progress="+progress+"&product="+product+"&merk="+merk+"&kompetitor="+kompetitor+"&peluang="+peluang+"&modality="+modality+"&keterangan="+keterangan+"&kendala="+kendala+"&analisa="+analisa+"&linkDokumentasi="+linkDokumentasi,"_blank");
                $('#main-modal-marketing').modal('hide')
			//}
        });
		
		$("#btn-export").click(function(){
		     /*if($('#main-modal-marketing #namamarketing').val()==''){
					Swal.fire({
                         icon: 'error',
            			 title: 'Oops...',
            			text: 'Nama Marketing tidak boleh kosong..!!',
                        showConfirmButton: false,
                        timer: 2000
                      });
			}else{ */
                tglawal = $("#tglawal").val();
                tglakhir = $("#tglakhir").val();
                idmarketing = $("#namamarketing").val();
                namamarketing = $("#namamarketing option:selected").text();
                provinsi = $("#provinsi:checkbox").is(":checked") ? 1:0;
                perjalanankerja = $("#perjalanankerja:checkbox").is(":checked") ? 1:0;
                rencana = $("#rencana:checkbox").is(":checked") ? 1:0;
                tujuankegiatan = $("#tujuankegiatan:checkbox").is(":checked") ? 1:0;
                tipecustomer = $("#tipecustomer:checkbox").is(":checked") ? 1:0;
                pic = $("#pic:checkbox").is(":checked") ? 1:0;
                tglrealisasi = $("#tglrealisasi:checkbox").is(":checked") ? 1:0;
                realisasi = $("#realisasi:checkbox").is(":checked") ? 1:0;
                funnelstatus = $("#funnelstatus:checkbox").is(":checked") ? 1:0;
                progress = $("#progress:checkbox").is(":checked") ? 1:0;
                product = $("#product:checkbox").is(":checked") ? 1:0;
                merk = $("#merk:checkbox").is(":checked") ? 1:0;
                kompetitor = $("#kompetitor:checkbox").is(":checked") ? 1:0;
                peluang = $("#peluang:checkbox").is(":checked") ? 1:0;
                modality = $("#modality:checkbox").is(":checked") ? 1:0;
                keterangan = $("#keterangan:checkbox").is(":checked") ? 1:0;
                kendala = $("#kendala:checkbox").is(":checked") ? 1:0;
                analisa = $("#analisa:checkbox").is(":checked") ? 1:0;
                linkDokumentasi = $("#linkDokumentasi:checkbox").is(":checked") ? 1:0;
                namamarketing = $("#namamarketing option:selected").text();
                 window.open("<?php echo base_url(); ?>funnel/exportlaporan/search?tglawal="+encodeURIComponent(tglawal)+"&tglakhir="+encodeURIComponent(tglakhir)+"&idmarketing="+encodeURIComponent(idmarketing)+"&namamarketing="+encodeURIComponent(namamarketing)+"&provinsi="+provinsi+"&perjalanankerja="+perjalanankerja+"&rencana="+rencana+"&tujuankegiatan="+tujuankegiatan+"&tipecustomer="+tipecustomer+"&pic="+pic+"&tglrealisasi="+tglrealisasi+"&realisasi="+realisasi+"&funnelstatus="+funnelstatus+"&progress="+progress+"&product="+product+"&merk="+merk+"&kompetitor="+kompetitor+"&peluang="+peluang+"&modality="+modality+"&keterangan="+keterangan+"&kendala="+kendala+"&analisa="+analisa+"&linkDokumentasi="+linkDokumentasi,"_blank");
                $('#main-modal-marketing').modal('hide')
			//}
        });
		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('#main-modal-funnel #modal-form-funnel').attr('action', 'funnel/addfunnel')
			$('#main-modal-funnel').modal()
		})


		$('#main-modal-funnel #rencana').on("change",function(e) { 
   			var rencana = $('#rencana').is(':checked') ? true : false;
				var chk = 0;
				
				if(rencana==true){
						chk=1;
				}else{
						chk=0;
				}
					var url = "<?php echo site_url('funnel/statusfunnel');?>/"+chk;
					$('#statusfunnel').load(url);
							return false;
		})

		$('#main-modal-funnel #namapelanggan').on('change', function(){
			$("#main-modal-funnel #provinsikota").val(null);
			if ($('#main-modal-funnel #namapelanggan').val()==''){
					$('#main-modal-funnel #idpelanggan').val('');
					$('#main-modal-funnel #namacalonpelanggan').val('');
			}else{
				  $('#main-modal-funnel #idpelanggan').val($('#main-modal-funnel #namapelanggan').val());
					$('#main-modal-funnel #namacalonpelanggan').val($('#main-modal-funnel #namapelanggan option:selected').text());
					var kode = this.value;
					if(kode>0){
						$.ajax({ 
							type: "GET",   
              url: "funnel/getprovinsikota/"+kode,   
							async: false,
              success: function(provinsikota) {
									$('#main-modal-funnel #provinsikota').val(provinsikota);
    					}
          	});
						$.ajax({ 
							type: "GET",   
              url: "funnel/getpic/"+kode,  
							dataType: 'json', 
							async: false,
              success: function(data) {
								var $select = $("select[name='pic[]']");					
                $select.find('option').remove();
								for (var i=0; i<data.length; i++) {
                    $select.append("<option value=\""+
														data[i].id +
													"\" selected>"+
														data[i].namapic +
													"</option>"
												);
												$select.find('option').css("overflow","hidden");
                };
    					}
          	});
					}else{
					}
			}; 
		});
		$('#main-modal-funnel #merkproduct').on('change', function() {
			var kode = this.value;
					if(kode>0){
						var url = "<?php echo site_url('funnel/add_namaproduct/');?>"+kode;
						$('#main-modal-funnel #namaproduct').load(url);
					}

					return false;
		})

		$('#main-modal-funneledit #merkproduct').on('change', function() {
			var kode = this.value;
					if(kode>0){
						var url = "<?php echo site_url('funnel/add_namaproduct/');?>"+kode;
						$('#main-modal-funneledit #namaproduct').load(url);
					}

					return false;
		})


		
	})	
	
	

</script>
