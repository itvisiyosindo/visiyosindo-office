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
		<div class="">
			<!--<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>-->
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<strong style="color: black;">Data Penilai</strong> <br><br>
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th width="5%"> # </th>
							<th width="25%"> Nama</th>
							<th width="10%"> NPP</th>
							<th width="30%"> Jabatan</th>
							<th> Jenis Evaluasi</th>
							<th width="10%"> Semester</th>
							<th width="10%"> Tahun</th>
							<th width="10%"> Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col">
		<div class="">
			<!--<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a>-->
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">
				<strong style="color: black;">Data Saya</strong> <br><br>
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_2">
					<thead>
						<tr>
							<th width="5%"> # </th>
							<th width="25%"> Nama</th>
							<th width="10%"> NPP</th>
							<th width="30%"> Jabatan</th>
							<th> Jenis Evaluasi</th>
							<th width="10%"> Semester</th>
							<th width="10%"> Tahun</th>
							<th width="10%"> Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>

	</div>
</div>



<script>
	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'evaluasi/pagination/detail_penilai',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
				className: 'text-center'
			}]
		})

		table = $('#kt_table_2').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'evaluasi/pagination/my_detail',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5, 6, 7],
				className: 'text-center'
			}]
		})




	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}



	// // Ambil tahun sekarang
	// const tahunSekarang = new Date().getFullYear();
	// const dropdownTahun = document.getElementById("tahun");

	// // Loop untuk menambahkan opsi tahun (-1 tahun hingga +1 tahun dari tahun sekarang)
	// for (let i = tahunSekarang - 0; i <= tahunSekarang + 2; i++) {
	// 	let option = document.createElement("option");
	// 	option.value = i;
	// 	option.textContent = i;
	// 	dropdownTahun.appendChild(option);
	// }
</script>