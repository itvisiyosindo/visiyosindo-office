<!-- 
	Create by KURNIAWAN  
	30-06-2025
-->

<header class="page-header">
	<h2><i class="fas fa-chart-line"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="card-body">
				<a href="javascript:;" id="btn-laporan-form" class="btn btn-sm btn-success"><i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah</a>
        
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
					<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
							<thead>					
								<tr>
									<th> No</th>
									<th> Kode</th>
									<th> Kategori Barang</th>
									<th> Diajukan Oleh</th>
									<th> Status</th>
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
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Pengajuan Forecast </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
				    
				<!--<div class="form-group" style="display: flex;">
				      div style="flex: 50%;padding: 10px;">
							<input class="form-control"  data-provide="datepicker" name="tglawal" id="tglawal" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Awal" required>
							</div>
							<div style="flex: 50%;padding: 10px;">
							<input class="form-control"  data-provide="datepicker" name="tglakhir" id="tglakhir" data-date-format="yyyy-mm-dd" placeholder="Pilih Tanggal Akhir" required>
							</div>
						</div>	-->
						<div class="form-group mb-2 pt-1">
							<label class="col-form-label">Kategori Barang :</label>
							<select data-plugin-selectTwo class="form-control" name="id_kategori" id="id_kategori" required>
								<option value="">- Pilih Kategori -</option>
								<?php foreach ($kategori_barang as $kb) { ?>
									<option value="<?= $kb->id_kategori ?>"><?= $kb->nama_kategori ?></option>
								<?php } ?>
							</select>
						</div>
					
    				
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
		    	<button type="button" id="btn-tampil" class="btn btn-primary btn-clear-form" >Tampilkan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>


<script>
	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			
			// order: [
			// 	[0, 'ASC']
			// ],
			ajax: {
				url: 'forecast/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 4],
				className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		

		$('#btn-laporan-form').click(function() {
					$('#main-modal-marketing').modal()	
					
						
		})

		$("#btn-tampil").click(function(){
				let id_kategori = $("#id_kategori").val();                
				window.location.href = "<?php echo base_url(); ?>forecast/show/detail?id_kategori=" + encodeURIComponent(id_kategori);
				$('#main-modal-marketing').modal('hide');
		});


		

		
	})

	
</script>
