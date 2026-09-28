<header class="page-header">
	<h2><i class="icons icon-layers"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">


        <div class="table-responsive">
            <h4>Detail Barang Forecast <strong>(Kategori <?= $nama_kategori ?>) </strong></h4>
            <input type="hidden" class="id_kategori" value="<?= $id_kategori ?>">

            <table class="table table-bordered table-striped text-center" id="tabel-forecast">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th hidden>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Terjual <?= date('Y') - 1 ?></th>
                        <th>Terjual <?= date('Y') ?></th>
                        <th>Total Stok</th>
                        <th>Demo</th>
                        <th>Barang Customer</th>
                        <th>Permintaan PO</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (!empty($data_forecast)) {
                        foreach ($data_forecast as $row) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td hidden>
                                    <input type="hidden" class="id_barang" value="<?= $row->id_barang ?>">
                                </td>
                                <td class="nama_barang"><?= $row->nama_barang ?></td>
                                <td class="satuan"><?= $row->nama_satuan ?></td>
                                <td class="terjual_lalu"><?= $row->terjual_tahun_lalu ?></td>
                                <td class="terjual_ini"><?= $row->terjual_tahun_ini ?></td>
                                <td class="stok"><?= $row->jual_stok ?></td>
                                <td class="demo"><?= $row->demo_stok ?></td>
                                <td class="customer"><?= $row->barang_customer_stok ?></td>
                                <td>
                                    <input type="number" min="0" class="form-control permintaan" placeholder="Jumlah PO">
                                </td>
                                <td>
                                    <input type="text" class="form-control keterangan" placeholder="Keterangan">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                    <?php } 
                    } else { ?>
                        <tr>
                            <td colspan="12" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <!-- Tombol kembali di kiri -->
            <button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form">
                <i class="fa fa-arrow-left"></i> Kembali
            </button>

            <!-- Tombol simpan di kanan -->
            <button type="button" id="btn-simpan" class="btn btn-success">
                <i class="fa fa-save"></i> Simpan Draft
            </button>
        </div>


                </div>
                </div>

            </div>
        </div>



<script>
 
document.addEventListener('DOMContentLoaded', function() {

		// Hapus baris
        $(document).on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
        });

        //SIMPAN
        $('#btn-simpan').click(function () {
            var details = [];

            $('#tabel-forecast tbody tr').each(function () {
                var row = $(this);

                if (row.find('.id_barang').length > 0) {
                    details.push({
                        id_barang: row.find('.id_barang').val(),
                        nama_barang: row.find('.nama_barang').text(),
                        satuan: row.find('.satuan').text(),
                        terjual_tahun_lalu: row.find('.terjual_lalu').text(),
                        terjual_tahun_ini: row.find('.terjual_ini').text(),
                        total_stok: row.find('.stok').text(),
                        demo_stok: row.find('.demo').text(),
                        barang_customer_stok: row.find('.customer').text(),
                        permintaan: row.find('.permintaan').val(),
                        keterangan: row.find('.keterangan').val()
                    });
                }
            });

            if (details.length === 0) {
                Swal.fire('Oops', 'Tidak ada data untuk disimpan!', 'warning');
                return;
            }

            Swal.fire({
                title: 'Simpan Draft Pengajuan Forecast?',
                text: "Data forecast akan disimpan ke database",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'POST',
                        url: "<?= base_url('forecast/save_detail') ?>",
                        dataType: 'JSON',
                        data: {
                                id_kategori: $('.id_kategori').val(),
                                details: details
                            },
                        success: function (res) {
                            if(res.status === 'success'){
                                Swal.fire('Berhasil', res.message, 'success').then(() => {
                                    window.location.href = "<?= base_url('forecast/show/detail_data') ?>/" 
                                                            + res.id_kategori + "/" + res.id_forecast;

                                });
                            } else {
                                Swal.fire('Gagal', res.message || 'Terjadi kesalahan saat menyimpan.', 'error');
                            }
                        },

                        error: function () {
                            Swal.fire('Error', 'Tidak dapat menghubungi server.', 'error');
                        }
                    });
                }
            });
        });

        

	

		
})
function goBack() {
    window.history.back();
}
	
</script>