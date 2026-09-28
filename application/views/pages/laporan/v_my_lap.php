<header class="page-header">
	<h2><i class="fas fa-clipboard-list"></i>&nbsp;<?= $page_title ?></h2>
	<div class="right-wrapper text-left">
		<ol class="breadcrumbs">
			<li><span><?= $page_desc ?></span></li>
		</ol>
	</div>
</header>

<div class="row">
	<div class="col">
		<div class="">
			<a href="javascript:;" id="btn-show-add-form" class="btn btn-sm btn-success"><i class="icons icon-plus"></i>&nbsp;Tambah</a><br>
		</div>
		<br>
		<div class="card-body">
			<div class="table-responsive">

				<div>
						<h4>Laporan Minggu ke-<?= $week_number ?>, <?= $month_year ?></h4>
						
					
					<div style="overflow-x: auto; white-space: nowrap; max-width: 100%;">
                        <table border="1" style="width: 100%; min-width: 2500px;">
                            <thead>
                                <tr>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> No </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Tanggal </th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="15%"><font color='#000000'> Jobdesk</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Jenis</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="15%"><font color='#000000'> Progress Kerja</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Kendala</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Solusi</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Status Pekerjaan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Link Hasil Kerja</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Pihak Terkait</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Keterangan</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="10%"><font color='#000000'> Nilai</th>
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="5%"><font color='#000000'> Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php 
                                $no = 1;
                                $total_nilai = 0; // Variabel untuk menampung total nilai
                                $jumlah_data = count($data_detail); // Jumlah data untuk menghitung rata-rata

                                foreach ($data_detail as $row) {
                                    
                                    if ($row->link != '') {    
                                        $link_download = '<a href="' . htmlspecialchars($row->link, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-download"></i> Download
                                        </a>';
                                    } else {
                                        $link_download = 'Tidak Ada';
                                    }

                                    $hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
                                    $nama_hari = $hari[date('l', strtotime($row->tanggal))];

                                    // Gunakan week_offset untuk menghitung Senin dan Jumat berdasarkan minggu
                                    $monday = date('Y-m-d', strtotime("monday this week +$week_offset week"));
                                    $Sunday = date('Y-m-d', strtotime("Sunday this week +$week_offset week"));

                                    // Cek apakah tanggal created_at berada di luar rentang minggu
                                    $created_at = date('Y-m-d', strtotime($row->created_at));
                                    $is_outside_range = ($created_at < $monday || $created_at > $Sunday);

                                    $id = $row->id;
																		$id_edit = encrypt($row->id);

                                    // Tambahkan nilai ke total_nilai
                                    $total_nilai += $row->nilai;

                                ?>
                                    <tr <?php if ($is_outside_range) { echo 'style="background-color: red;"'; } ?>>
                                        <td style="text-align:center"><font color='#000000'><input type="hidden" name="id_sodetail[]" value="<?= $id ?>"><?= $no++ ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $nama_hari . ', ' . date('d-m-Y', strtotime($row->tanggal)); ?></td>
                                        <td><font color='#000000'><?= $row->jobdesc ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->jenis ?></td>
                                        <td><font color='#000000'><?= $row->progress ?></td>
                                        <td><font color='#000000'><?= $row->kendala ?></td>
                                        <td><font color='#000000'><?= $row->solusi ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->status_pekerjaan ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $link_download ?></td>
                                        <td><font color='#000000'><?= $row->pihak ?></td>
                                        <td><font color='#000000'><?= $row->keterangan ?></td>
                                        <td style="text-align:center"><font color='#000000'><?= $row->nilai ?></td>
                                        <td style="text-align:center">
																						<button type="button" class="btn btn-sm btn-primary btn-edit" data-id="<?= $id_edit ?>"><i class="bx bx-pencil"></i></button>
                    												<button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="<?= $row->id ?>" data-object="laporan/delete/<?= $row->id ?>"> <i class="bx bx-trash"></i> </button>
																				</td>
                                    </tr>

                                <?php } 

                                // Menghitung rata-rata, jika jumlah data > 0
                                if ($jumlah_data > 0) {
                                    $nilaiRata = $total_nilai / $jumlah_data;
                                } else {
                                    $nilaiRata = 0; // Jika tidak ada data, set rata-rata 0
                                }
                                ?>

                                <tr>
                                    <td colspan="11" style="text-align:right; border: 1px solid black;"><font color='#000000'><strong>Rata-rata :</strong></td>
                                    <td style="text-align:center;border: 1px solid black;"><font color='#000000'><strong><?= number_format($nilaiRata, 2) ?></strong></td>
                                </tr>

                            </tbody>
                        </table>

                    </div>


						<br>
						<div>
								<a href="<?= base_url('laporan/show/list/my_data/' . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/list/my_data/' . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
						</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content ">
			<div class="modal-header bg-dark text-light">
				<h5 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i> Form Laporan Mingguan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<?= form_open('#', array('id' => 'modal-form', 'autocomplete' => 'off')); ?>
			<div class="modal-body">
				<div class="dt-kategori-form">
					
					
					<div class="form-group">
						<label for="waktu" class="form-control-label">Tanggal <span class="text-danger">*</span> :</label>
						<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{ "format": "dd-mm-yyyy"}'>
							<span class="input-group-text">
								<i class="fas fa-calendar-alt"></i>
							</span>
							<input type="text" class="form-control" id="tanggal" name="tanggal" required>
						</div>
					</div>
					
					<div class="form-group">
						<label for="jobdesc" class="form-control-label">Pilih Jobdesk <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jobdesc" name="jobdesc" required onchange="toggleFormStatus()">
							<option value="">- Pilih Jobdesk -</option>
							<!--<option value="1">- Lainnya -</option>-->
							<?php
							foreach ($list_job as $row) {
								echo '<option value="' . $row->id_pod . '">' . $row->deskripsi . '</option>';
							}
							?>
						</select>
					</div>
					<input type="hidden" id="jobdesc_text" name="jobdesc_text">


					<div class="form-group" id="form_keterangan_konfirmasi" style="display:none;">
							<label for="keterangan_konfirmasi" class="form-control-label">Jobdesc Lainnya <span class="text-danger">*</span> :</label>
							<textarea type="text" class="form-control" id="keterangan_konfirmasi" name="keterangan_konfirmasi" required></textarea>
					</div>
					
					<div class="form-group">
						<label for="jenis" class="form-control-label">Jenis <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="jenis" name="jenis" required>
              <option value = "JOBDESK RUTIN">JOBDESK RUTIN</option>
              <option value = "PROJECT TAMBAHAN">PROJECT TAMBAHAN</option>
						</select>
					</div>

					<div class="form-group">
						<label for="progress" class="form-control-label">Progress <span class="text-danger">*</span> :</label>
						<textarea class="form-control" placeholder="Masukkan Progress Pekerjaan Anda" name="progress" id="progress" cols="10" rows="5"></textarea>
					</div>

					<div class="form-group">
						<label for="kendala" class="form-control-label">Kendala :</label>
						<input type="text" class="form-control" placeholder="Masukkan Kendala Jika ada" id="kendala" name="kendala" required>
					</div>
					<div class="form-group">
						<label for="solusi" class="form-control-label">Solusi :</label>
						<input type="text" class="form-control" placeholder="Masukkan Solusinya" id="solusi" name="solusi" required>
					</div>
					<div class="form-group">
						<label for="status_pekerjaan" class="form-control-label">Status Pekerjaan <span class="text-danger">*</span> :</label>
						<select data-plugin-selectTwo class="form-control populate" id="status_pekerjaan" name="status_pekerjaan" required>
              <option value = "SELESAI">SELESAI</option>
              <option value = "DALAM PROSES">DALAM PROSES</option>
              <option value = "MENUNGGU KONFIRMASI">MENUNGGU KONFIRMASI</option>
              <option value = "PENDING">PENDING</option>
              <option value = "TIDAK SELESAI">TIDAK SELESAI</option>
						</select>
					</div>
					<div class="form-group">
						<label for="link" class="form-control-label">Link Bukti Pekerjaan <span class="text-danger">*</span>:</label>
						<input type="text" class="form-control" placeholder="Masukkan Link Hasil / Bukti Pekerjaan Anda" id="link" name="link" required>
					</div>


					<div class="form-group">
							<label for="pihak" class="form-control-label">Nama Pihak Terkait :</label>
							<select class="form-control" id="pihak" name="pihak" required>
									<option value="" selected>- TIDAK ADA -</option>
									<?php if (!empty($list_nama)): ?>
											<?php foreach ($list_nama as $row): ?>
													<option value="<?= htmlspecialchars(trim($row->nama)) ?>">
															<?= htmlspecialchars($row->nama) ?>
													</option>
											<?php endforeach; ?>
									<?php endif; ?>
							</select>
					</div>


					<div class="form-group" id="keterangan-group" style="display: none;">
							<label for="keterangan" class="form-control-label">Keterangan yang Dikerjakan oleh Pihak Terkait :</label>
							<textarea class="form-control" placeholder="Masukkan Keterangan yang Dikerjakan oleh Pihak Terkait" 
												name="keterangan" id="keterangan" cols="10" rows="2"></textarea>
					</div>
					


				</div>
			</div>
			<div class="modal-footer">
				<div class="is_aktif"></div>
				<input type="hidden" id="id_pelanggan" name="id_pelanggan">
				<button type="button" class="btn btn-secondary btn-clear-form" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success btn-save">Simpan</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	/*function toggleFormStatus() {
        var status = document.getElementById("jobdesc").value;
        var formKet = document.getElementById("form_keterangan_konfirmasi");

        if (status === "1") {
            formKet.style.display = "block";
        } else {
            formKet.style.display = "none";
        }
    }*/
	
	function toggleFormStatus() {
			var select = document.getElementById("jobdesc");
			var selectedValue = select.value;
			var selectedText = select.options[select.selectedIndex].text;
			var formKet = document.getElementById("form_keterangan_konfirmasi");
			var jobdescText = document.getElementById("jobdesc_text");
			
			if (selectedValue === "0") {
					formKet.style.display = "block";
					jobdescText.value = ""; // Kosongkan dulu karena akan diisi oleh user
			} else {
					formKet.style.display = "none";
					jobdescText.value = selectedText; // Simpan deskripsi dari dropdown
			}
	}

	// Tambahkan event listener untuk menangkap input manual jika "Lainnya" dipilih
	document.getElementById("keterangan_konfirmasi").addEventListener("input", function() {
			var jobdescText = document.getElementById("jobdesc_text");
			jobdescText.value = this.value; // Simpan input manual ke jobdesc_text
	});

	document.getElementById("pihak").addEventListener("change", function() {
				var selectedValue = this.value.trim(); 
				var keteranganGroup = document.getElementById("keterangan-group");


				if (selectedValue === "") {
						keteranganGroup.style.display = "none"; 
				} else {
						keteranganGroup.style.display = "block"; 
				}
		});


	document.addEventListener('DOMContentLoaded', function() {
		table = $('#kt_table_1').DataTable({
			responsive: false,
			processing: true,
			serverSide: true,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'laporan/pagination/my_data',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 1, 4, 5, 6],
				className: 'text-center'
			}]
		})

		$('#btn-show-add-form').click(function() {
			$('.form-control').val(null)
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal #modal-form').attr('action', 'laporan/add')
			$('#main-modal').modal()
		})

		$(document).on('click', '.btn-edit', function() {
			$('.btn-isactive').remove()
			var object = 'laporan'
			$('#main-modal #modal-form').attr('action', 'laporan/update')
			$('#main-modal').modal()

			var id = $(this).attr("data-id")
			fetch(object + '/edit/' + id)
				.then(function(resp) {
					return resp.json()
				})
				.then(function(data) {
					$('#main-modal #tanggal').val(data[0].tanggal)
					$('#main-modal #jobdesc').val(data[0].jobdesc)
					$('#main-modal #jenis').val(data[0].jenis)
					$('#main-modal #progress').val(data[0].progress)
					$('#main-modal #kendala').val(data[0].kendala)
					$('#main-modal #solusi').val(data[0].solusi)

					$('#main-modal #status_pekerjaan').val(data[0].status_pekerjaan)
					$('#main-modal #link').val(data[0].link)
					$('#main-modal #pihak').val(data[0].pihak)
					$('#main-modal #keterangan').val(data[0].keterangan)
					$('#main-modal #id_pelanggan').val(id)
				})
		})


		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}



		
</script>