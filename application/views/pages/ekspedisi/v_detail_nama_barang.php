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
    .label-col,
		.value-col {
        min-width: 220px;
        font-weight: bold;
        color: #343a40;
        position: relative;
    }
    .label-col::after {
        content: ":";
        position: absolute;
        right: -10px;
    }

		.text-right {
			text-align: right;
		}
		.text-center {
			text-align: center;
		}
</style>


<div class="row">
	<div class="col">
		<div class="card-body">
			<div class="row mb-1">
					<div class="col-auto label-col">Brand</div>
					<div class="col value-col"><?= $data_barang->brand ?></div>
			</div>
			<div class="row mb-1">
					<div class="col-auto label-col">Nama Produk</div>
					<div class="col value-col"><?= $data_barang->nama ?></div>
			</div>
				<?php
					if ($data_barang->acuan_berat == 1){
						$berat = $data_barang->berat;
					}else if ($data_barang->acuan_berat == 2){
						$berat = $data_barang->berat_dimensi;
					}
				?>
			<input type="hidden" name="acuan_berat" value="<?= $berat ?>">
			<input type="hidden" name="layanan" value="<?= $data_barang->layanan ?>">
			<div class="row mb-1">
					<div class="col-auto label-col">Berat</div>
					<div class="col value-col"><?= $berat ?></div>
			</div>
			<div class="row mb-1">
					<div class="col-auto label-col">Acuan Ongkir Maksimal</div>
					<div class="col value-col">
							<?= (!empty($data_barang->acuan_ongkir_maksimal) && $data_barang->acuan_ongkir_maksimal != 0) 
									? 'Rp ' . number_format($data_barang->acuan_ongkir_maksimal, 0, ',', '.') 
									: '-' ?>
					</div>
			</div>
			<div class="row mb-1 align-items-center">
					<div class="col-auto label-col">Kota Tujuan</div>
					<div class="col d-flex">
							<select name="id_destinasi"
											class="form-control form-control-sm populate me-2"
											data-plugin-selectTwo
											style="width: 300px;">
									<option value="">-- Pilih Kota --</option>
									<?php foreach ($destinasi as $d): ?>
											<option value="<?= $d->id ?>"><?= $d->kab_kota ?></option>
									<?php endforeach; ?>
							</select>
					</div>
			</div>
   

			<div class="row mb-2 align-items-center">
				<div class="col-auto label-col">Jumlah</div>
				<div class="col d-flex">
						<input type="number" id="filterJumlah" class="form-control w-auto me-2" placeholder="Angka" style="max-width: 150px;">
						&nbsp;&nbsp;
						<button id="btnHitung" class="btn btn-outline-secondary">
								<i class="fas fa-calculator me-1"></i> Hitung Ongkir
						</button>
				</div>
		</div>

				
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
									<th>Nama Ekspedisi</th>
									<th>Minimal Berat (Kg)</th>
									<th>Biaya (Rp)</th>
									<th>Estimasi (Hari)</th>
								</tr>
							</thead>
				</table>
				<br>
				Note : Harga atau estimasi yang dicetak <strong class="fw-bold text-dark">tebal(Bold)</strong> menandakan data tersebut sudah dikonfirmasi dan bersifat final.

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
	let table;

function goBack() {
	window.history.back();
}

document.addEventListener('DOMContentLoaded', function () {
	// Inisialisasi awal: tampilkan pesan kosong
	table = $('#kt_table_1').DataTable({
		processing: true,
		serverSide: false,
		paging: false,
		searching: false,
		info: false,
		data: [], // kosongkan
		columns: [
			{ title: "#", data: 0 },
			{ title: "Nama Ekspedisi", data: 1 },
			{ title: "Minimal Berat (Kg)", data: 2 },
			{ title: "Biaya (Rp)", data: 3 },
			{ title: "Estimasi (Hari)", data: 4 }
		],
    columnDefs: [
			{ targets: 0, className: 'text-center' }, // Minimal Berat
			{ targets: 2, visible: false },   // Minimal Berat
			{ targets: 3, className: 'text-right' },                 // Biaya
			{ targets: 4, className: 'text-center' }                 // Estimasi
		],
		language: {
			emptyTable: "Silakan pilih Kota Tujuan dan Jumlah Produk terlebih dahulu."
		}
	});

	$('#btnHitung').on('click', function(e) {
		e.preventDefault();

		let id_destinasi = $('select[name="id_destinasi"]').val();
		let jumlah       = $('#filterJumlah').val();
		let acuan_berat  = $('input[name="acuan_berat"]').val();
		let layanan			 = $('input[name="layanan"]').val();
		let asal         = "<?= $asal ?>";

		if (!id_destinasi || !jumlah) {
			alert('Kota tujuan dan jumlah wajib diisi.');
			return;
		}

		// Ambil data via AJAX (bukan lagi pakai serverSide true)
		$.ajax({
			url: '<?= base_url("ekspedisi/paginationDetailNamaBarang") ?>',
			type: 'POST',
			data: {
				id_destinasi: id_destinasi,
				jumlah: jumlah,
				acuan_berat: acuan_berat,
				layanan: layanan,
				asal: asal
			},
			dataType: 'json',
			success: function(response) {
				if (response.message) {
					alert(response.message);
				}
				table.clear().rows.add(response.data).draw();
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error: ", error);
				alert("Harga ekspedisi untuk kota tujuan ini belum diinput oleh tim warehouse.");
			}
		});

	});
});

</script>



