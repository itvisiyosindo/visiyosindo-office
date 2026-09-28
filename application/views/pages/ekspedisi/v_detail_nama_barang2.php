<!-- 
	Create by KURNIAWAN  
	25-06-2025
-->

<header class="page-header">
	<h2><i class="fas fa-file-invoice-dollar"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>
<style>
	#kt_table_1 thead th {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>


<div class="row">
	<div class="col">
		<div class="card-body">
				<strong class="fw-bold text-dark">Brand &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;   : <?= $data_barang->brand ?></strong>
				<br><strong class="fw-bold text-dark">Nama Product &nbsp; &nbsp;  : <?= $data_barang->nama ?></strong>
				
		</div>
			<?php
				if($asal=='pku'){
					$gudang = "Pekanbaru";
				}else if($asal=='jkt'){
					$gudang = "Jakarta";
				}else if($asal=='jogja'){
					$gudang = "Yogyakarta";
				}

			?>
				
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<h4>Perbandingan Ongkir dari Gudang<strong> <?= $gudang ?> </strong></h4>
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
								<thead>
								<tr>
									<th>#</th>
									<th>Provinsi</th>
									<th>Kab/Kota</th>
									<?php foreach ($nama_ekspedisi as $i => $e): ?>
										<th><?= htmlspecialchars($e->nama_ekspedisi, ENT_QUOTES, 'UTF-8') ?></th>
									<?php endforeach ?>
								</tr>
							</thead>

					<tbody></tbody> <!-- DataTable akan isi bagian ini via Ajax -->
				</table>

			</div>
			<br><br>
					<div role="document">
								<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
						
					<br>
					<br>
        	</div>
		</div>
	</div>
</div>




	<script>
		document.addEventListener('DOMContentLoaded', function() {
		
		/*let id_ekspedisi = "<?= $id_ekspedisi ?>";

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [[1, 'asc'], [2, 'asc']],
			ajax: {
				url: 'ekspedisi/paginationDetailNamaEkspedisi',
				type: 'POST',
				data: function(e) {
					e.csrf_token = token;
					e.id_ekspedisi = id_ekspedisi; // kirim ke server
				}
			},
			columnDefs: [
				{ targets: [0, 3, 4, 5, 6], className: 'text-center', searchable: false },
				{ targets: [1, 2], className: 'text-left' },
				{ targets: [1, 2], searchable: true }
			]
		});*/

		const asal = "<?= $asal ?>";
		const id_acuan = "<?= $id_acuan ?>";
		const ekspedisiList = <?= json_encode(array_values(array_column($nama_ekspedisi, 'nama_ekspedisi'))) ?>;

		$(document).ready(function () {
				let columns = [
						{ data: 'no', title: '#', className: 'text-center' },
						{ data: 'provinsi', title: 'Provinsi' },
						{ data: 'kab_kota', title: 'Kab/Kota' }
				];

				ekspedisiList.forEach(function (nama, index) {
						columns.push({
								data: 'ekspedisi_' + index,
								title: nama,
								className: 'text-center'
						});
				});

				$('#kt_table_1').DataTable({
						processing: true,
						serverSide: true,
						responsive: true,
						ajax: {
								url: '<?= base_url("ekspedisi/paginationOngkirBarangByAsal") ?>',
								type: 'POST',
								data: function (d) {
										d.asal = asal;
										d.id_acuan = id_acuan;
										d.csrf_token = '<?= $this->security->get_csrf_hash() ?>';
								}
						},
						columns: columns
				});
		});


		
		

		
	})




function updateDatatable() {
		table.ajax.reload(null, false)
}

function goBack() {
    window.history.back();
}

</script>

	