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
		<div class="card-body">
            <a href="javascript:;" id="btn-cetaklaporan-form" class="btn btn-sm btn-success"><i class="fas fa-box"></i>&nbsp;&nbsp;&nbsp; Cek Stock Barang</a>
			
		</div>
		<br>
		<?php if(!empty($data_barang)) { ?>
		<div class="card-body">
            <table style="border-collapse: collapse;">
                <tr>
                    <td><b>Nama Barang</b></td>
                    <td><b>&nbsp;:&nbsp;&nbsp;</b></td>
                    <td><b><?= $data_barang[0]->nama_barang ?></b></td>
                </tr>
                <tr>
                    <td><b>Stock yang bisa Dijual</b></td>
                    <td><b>&nbsp;:&nbsp;&nbsp;</b></td>
                    <td><b><?= $totalSTOCKjual ?></b> &nbsp;&nbsp;(Berdasarkan data dari <b>Gudang Stock Jual</b>)</td>
                </tr>
                <tr>
                    <td><b>Total Stock Barang</b></td>
                    <td><b>&nbsp;:&nbsp;&nbsp;</b></td>
                    <td><b><?= $totalSTOCK ?></b></td>
                </tr>
            </table>

            
    <?php if(!empty($list_data)) { ?>




        <div class="table-responsive">
            <table id="stokTable" class="table table-striped table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Serial Number</th>
                        <th style="text-align:center;">Gudang</th>
                        <th style="text-align:center;">No Penerimaan Barang</th>
                        <th style="text-align:center;">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_data)) { ?>
                        <?php $no = 1; foreach ($list_data as $row) { ?>
                            <tr>
                                <td style="text-align:center;"><?= $no++; ?></td>
                                <td style="text-align:center;"><?= $row->no_batch; ?></td>
                                <td style="text-align:center;"><?= $row->nama_gudang; ?></td>
                                <td style="text-align:center;">
                                    <a href="<?= site_url('penerimaan_barang/edit/' . encrypt($row->id_penerimaan_barang)); ?>">
                                        <?= $row->no_terima; ?>
                                    </a>
                                </td>
                                <td style="text-align:center;"><?= $row->current_stock; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">Tidak ada data stok tersedia</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <?php if (!empty($list_data)) { ?>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;"><b>Total:</b></td>
                        <td style="text-align:center;"><b><?= $totalSTOCK ?></b></td>
                    </tr>
                </tfoot>
                <?php } ?>
            </table>
        </div>
            <?php } else { ?>
                <br> <br> <p>Belum ada data untuk ditampilkan.</p>
            <?php } ?>

            <?php } else { ?>
                 <p>Belum ada data untuk ditampilkan.</p>
            <?php } ?>
        </div>

	</div>
</div>



<div id="main-modal-marketing" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Cek Stock Barang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form-marketing', 'autocomplete' => 'off')); ?> 
			<div class="modal-body">
				<div class="dt-marketing-form">
						
					<div class="form-group mb-2 pt-1">
            <div class="col-form-label">
            <label>Pilih Barang</label>
                <select data-plugin-selectTwo="search_barang" class="form-control search_barang_diform filter-grup" name="id_barang[]" id="id_barang"></select>
            </div>
          </div>	
					<label>Harap isi dengan Huruf Kecil</label>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div class="is_aktif"></div>
				<input type="hidden" id="ID" name="ID"> -->
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" id="btn-tampilkan" class="btn btn-success btn-clear-form" >Tampilkan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<script>
 

	document.addEventListener('DOMContentLoaded', function() {
		$(".search_barang_diform").themePluginSelect2({
            placeholder: "--- Ketik Nama Barang ---",
            allowClear: true,
            minimumInputLength: 1,
            width: '100%',
            ajax: {
                method: 'POST',
                url: "barang/get/by_search",
                dataType: 'json',
                delay: 250,
                data:

                    function(params) {
                        return {
                            q: params.term, // search term
                            csrf_token: token
                        };
                    },
                processResults: function(data, params) {
                    return {

                        results: $.map(data.items, function(obj) {
                            return {
                                id: obj.id_barang,
                                text: `${obj.nama_barang}`
                            };
                        })
                    }
                },
                cache: true
            },
        });


        $(document).ready(function() {
            $('#stokTable').DataTable({
                paging: false,        // semua data ditampilkan
                info: false,          // hilangkan teks info
                lengthChange: false,  // hilangkan dropdown "Tampilkan per halaman"
                ordering: false,      // nonaktifkan sorting
                language: {
                    search: "Cari Serial Number :",
                    zeroRecords: "Tidak ditemukan data"
                }
            });
        });


/** 

		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			
			// order: [
			// 	[0, 'ASC']
			// ],
			ajax: {
				url: 'history_barang/pagination',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				// targets: [0, 1, 2],
				// className: 'text-center'
			}]
		})
		function updateDatatable() {
		table.ajax.reload(null, false)
	}
		
		*/


		$('#btn-cetaklaporan-form').click(function() {
		     $('#main-modal-marketing').modal()	
		     
    			
		})



		$("#btn-tampilkan").click(function(){
				id_barang = $("#id_barang").val();
				
				// Ganti window.open dengan window.location.href untuk reload halaman yang sama
				window.location.href = "<?php echo base_url(); ?>stock/index/search?id_barang=" + encodeURIComponent(id_barang);
				
				// Menyembunyikan modal setelah klik
				$('#main-modal-marketing').modal('hide');
		});


	

		
	})

	
</script>