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
		<br>
		<?php if (isAdmin() || isHrd()): ?>
			<div class="mb-3 text-right">
				<button type="button" class="btn btn-warning btn-sm font-weight-bold" onclick="kirimReminderWaLaporan()">
					<i class="fab fa-whatsapp"></i> Kirim Reminder WA Belum Isi Laporan Mingguan
				</button>
			</div>
		<?php endif; ?>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
					<thead>
						<tr>
							<th> # </th>
							<th> Nama</th>
							<th> NPP</th>
							<th> Jabatan</th>
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
    	//pageLength: 25,
			order: [
				[0, 'desc']
			],
			ajax: {
				url: 'laporan/pagination/all',
				type: 'POST',
				data: function(e) {
					e.tahun = $('#tahun').val()
					e.csrf_token = token
				}
			},
			columnDefs: [{
				targets: [0, 2],
				className: 'text-center'
			}]
		})

		

		
	})

	function updateDatatable() {
		table.ajax.reload(null, false)
	}

	function kirimReminderWaLaporan() {
		if (typeof Swal === 'undefined') {
			if (!confirm("Kirim pengingat WhatsApp ke seluruh karyawan yang BELUM mengisi Laporan Mingguan minggu ini?")) {
				return;
			}
			$.ajax({
				url: '<?= base_url("reminder/remind_laporan_mingguan/vysi_medikal_2026") ?>',
				type: 'GET',
				dataType: 'json',
				success: function(res) {
					alert(res.msg);
				},
				error: function() {
					alert("Gagal mengirim pengingat WhatsApp.");
				}
			});
			return;
		}

		Swal.fire({
			title: 'Kirim Pengingat WA?',
			text: "Sistem akan mengirimkan pesan WhatsApp ke seluruh karyawan yang BELUM mengisi Laporan Mingguan minggu ini.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#e0a800',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Ya, Kirim Sekarang!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: 'Mengirim WhatsApp...',
					text: 'Mohon tunggu sebentar',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: '<?= base_url("reminder/remind_laporan_mingguan/vysi_medikal_2026") ?>',
					type: 'GET',
					dataType: 'json',
					success: function(res) {
						Swal.fire({
							icon: res.status ? 'success' : 'error',
							title: 'Reminder WA',
							text: res.msg
						});
					},
					error: function() {
						Swal.fire('Error', 'Gagal mengirim pengingat WhatsApp.', 'error');
					}
				});
			}
		});
	}




	
</script>