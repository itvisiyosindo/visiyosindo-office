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
		<div class="card-body">
				<strong class="fw-bold text-dark">Nama &nbsp; &nbsp; &nbsp; &nbsp; : <?= $data_job->nama ?></strong>
				<br><strong class="fw-bold text-dark">NPP &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    : <?= $data_job->no_pegawai ?></strong>
				<br><strong class="fw-bold text-dark">Jabatan &nbsp; : <?= $data_job->jabatan ?></strong>

				<br><br>
				<div class="row mb-3 align-items-center">
					<div class="col-md-7 mb-2 mb-md-0">
						<a href="<?= base_url('laporan/print_mingguan/' . $id_pengaju . '/' . $week_offset) ?>" target="_blank" class="btn btn-sm btn-success">
							<i class="fas fa-print"></i>&nbsp; Cetak Laporan Mingguan
						</a>
						&nbsp;
						<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-cetak-bulanan">
							<i class="fas fa-file-pdf"></i>&nbsp; Cetak Rangkuman 1 Bulan
						</button>
					</div>
					<div class="col-md-5">
						<div class="form-inline justify-content-md-end">
							<label class="mr-2 font-weight-bold text-dark"><i class="fas fa-history"></i>&nbsp;Pilih Minggu :</label>
							<select class="form-control form-control-sm" style="max-width: 260px;" onchange="if (this.value) window.location.href = this.value;">
								<?php for ($i = 0; $i >= -250; $i--): ?>
									<?php 
										$m_time = strtotime("monday this week $i week");
										$s_time = strtotime("sunday this week $i week");
										$start_w = date('d/m/Y', $m_time);
										$end_w = date('d/m/Y', $s_time);
										$sel = ($week_offset == $i) ? 'selected' : '';
										if ($i == 0) {
											$label = "Minggu Ini ($start_w - $end_w)";
										} else {
											$label = "Minggu " . abs($i) . " Lalu ($start_w - $end_w)";
										}
									?>
									<option value="<?= base_url('laporan/show/detail/laporan_mingguan/' . $id_pengaju . '/' . $i) ?>" <?= $sel ?>><?= $label ?></option>
								<?php endfor; ?>
							</select>
						</div>
					</div>
				</div>

				<input type="hidden" id="id_po" value="<?= $data_job->pengguna_id ?>" />

				<?php $id = $data_job->pengguna_id ?> 
				<input type="hidden" name="id" id="id" value="<?= $data_job->pengguna_id ?>">
		</div>

		<br>
		
    <?= form_open('laporan/updateNilai', array('id' => 'main-form', 'autocomplete' => 'off')); ?>

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
                                    <th style="text-align:center" bgcolor="#C6DEFF" width="15%"><font color='#000000'>&nbsp;&nbsp;&nbsp;&nbsp; Nilai &nbsp;&nbsp;&nbsp;&nbsp;</th>
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

                                    // Gunakan week_offset untuk menghitung Senin dan Minggu berdasarkan minggu
                                    $week_str = ($week_offset >= 0 ? "+$week_offset" : "$week_offset") . " week";
                                    $monday = date('Y-m-d', strtotime("monday this week $week_str"));
                                    $Sunday = date('Y-m-d', strtotime("sunday this week $week_str"));

                                    // Cek apakah tanggal created_at berada di luar rentang minggu
                                    $created_at = date('Y-m-d', strtotime($row->created_at));
                                    $is_outside_range = ($created_at < $monday || $created_at > $Sunday);

                                    $id = $row->id;

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
                                        <td><input class="form-control" type="text" name="nilai[]" value="<?= $row->nilai ?>"></td>
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
								<a href="<?= base_url('laporan/show/detail/laporan_mingguan/' . $id_pengaju . '/' . ($week_offset - 1)) ?>" class="btn btn-outline-secondary">< Minggu Sebelumnya</a>
								<a href="<?= base_url('laporan/show/detail/laporan_mingguan/' . $id_pengaju . '/' . ($week_offset + 1)) ?>" class="btn btn-outline-secondary">Minggu Berikutnya ></a>
						</div>
				</div>



					<br><br>
					<div role="document">
								<button type="button" onclick="goBack()" class="btn btn-secondary btn-clear-form float-left" >Kembali</button>
							
							<?php if($data_job->status != 5){ ?>
                <button type="button" class="btn btn-success btn-save float-right">Simpan</button>
              <?php } ?>

						
					<br>
					<br>
        	</div>
				</div>
		</div>
		
		<?= form_close(); ?>
	</div>
</div>


<script>
	document.addEventListener('DOMContentLoaded', function() {
        var level_ttd = $('#level_ttd').val();		
        
		$(document).on('click', '.btn-approval', function() {
        	var id = $('#id').val();
			
            Swal.fire({
				title: 'Kirim Notifikasi Evaluasi Pegawai?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        method: 'POST',
                        url: 'evaluasi/sendNotif',
                        dataType: 'JSON',
                        data: {
                            id : id,
							csrf_token: token
                        },
                        success: function(resp) {
                            handleResponse(resp)
                        }
                    })
					
                }
				
            })
        })
	 })

	function goBack() {
        window.history.back();
    }
</script>

<div id="modal-cetak-bulanan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<form action="<?= base_url('laporan/print_bulanan/' . encrypt($data_job->pengguna_id)) ?>" method="GET" target="_blank" onsubmit="$('#modal-cetak-bulanan').modal('hide');">
				<div class="modal-header bg-dark text-light">
					<h5 class="modal-title"><i class="fas fa-print"></i> Cetak Rangkuman Bulanan</h5>
					<button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="form-control-label">Pilih Bulan & Tahun :</label>
						<input type="month" name="month" class="form-control" value="<?= date('Y-m') ?>" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Cetak PDF</button>
				</div>
			</form>
		</div>
	</div>
</div>

