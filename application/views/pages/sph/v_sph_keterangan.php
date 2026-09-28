<!-- View: v_sph_keterangan.php - Master Data Keterangan SPH -->

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list-ul mr-2"></i>Master Data Keterangan SPH
        </h6>
        <div>
            <a href="<?= base_url('sph') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-primary btn-sm" id="btnTambah">
                <i class="fas fa-plus mr-1"></i> Tambah Keterangan
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tableKeterangan">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="50%">Keterangan</th>
                        <th width="15%">Tipe</th>
                        <th width="10%">Urutan</th>
                        <th width="10%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($keterangan as $ket): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $ket->keterangan ?></td>
                        <td>
                            <?php
                            $tipeLabel = [
                                'sudah_termasuk' => '<span class="badge badge-success">Sudah Termasuk</span>',
                                'belum_termasuk' => '<span class="badge badge-warning">Belum Termasuk</span>',
                                'berlaku' => '<span class="badge badge-info">Masa Berlaku</span>',
                                'lainnya' => '<span class="badge badge-secondary">Lainnya</span>'
                            ];
                            echo $tipeLabel[$ket->tipe] ?? '<span class="badge badge-secondary">-</span>';
                            ?>
                        </td>
                        <td class="text-center"><?= $ket->urutan ?></td>
                        <td class="text-center">
                            <?= $ket->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>' ?>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm btn-edit" 
                                    data-id="<?= $ket->id ?>"
                                    data-keterangan="<?= htmlspecialchars($ket->keterangan) ?>"
                                    data-tipe="<?= $ket->tipe ?>"
                                    data-urutan="<?= $ket->urutan ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus" data-id="<?= $ket->id ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKeterangan">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-list-ul mr-2"></i><span id="modalTitle">Tambah Keterangan</span></h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="inputId">
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keterangan" id="inputKeterangan" rows="3" required 
                                  placeholder="Contoh: Harga sudah termasuk PPN"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Tipe</label>
                        <select class="form-control" name="tipe" id="inputTipe">
                            <option value="sudah_termasuk">Sudah Termasuk</option>
                            <option value="belum_termasuk">Belum Termasuk</option>
                            <option value="berlaku">Masa Berlaku</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Urutan</label>
                        <input type="number" class="form-control" name="urutan" id="inputUrutan" value="0" min="0">
                        <small class="text-muted">Semakin kecil angka, semakin atas posisinya</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toastr fallback using PNotify
    if (typeof toastr === 'undefined') {
        window.toastr = {
            success: function(msg, title) { new PNotify({ title: title || 'Sukses', text: msg, type: 'success', delay: 3000 }); },
            error: function(msg, title) { new PNotify({ title: title || 'Error', text: msg, type: 'error', delay: 4000 }); },
            warning: function(msg, title) { new PNotify({ title: title || 'Peringatan', text: msg, type: 'warning', delay: 3500 }); },
            info: function(msg, title) { new PNotify({ title: title || 'Info', text: msg, type: 'info', delay: 3000 }); }
        };
    }
    
    // DataTable
    $('#tableKeterangan').DataTable({
        order: [[3, 'asc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            zeroRecords: 'Tidak ada data yang ditemukan',
            paginate: {
                first: 'Awal',
                last: 'Akhir',
                next: 'Selanjutnya',
                previous: 'Sebelumnya'
            }
        }
    });
    
    // Tambah
    $('#btnTambah').on('click', function() {
        $('#formKeterangan')[0].reset();
        $('#inputId').val('');
        $('#modalTitle').text('Tambah Keterangan');
        $('#modalForm').modal('show');
    });
    
    // Edit
    $(document).on('click', '.btn-edit', function() {
        $('#inputId').val($(this).data('id'));
        $('#inputKeterangan').val($(this).data('keterangan'));
        $('#inputTipe').val($(this).data('tipe'));
        $('#inputUrutan').val($(this).data('urutan'));
        $('#modalTitle').text('Edit Keterangan');
        $('#modalForm').modal('show');
    });
    
    // Submit
    $('#formKeterangan').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('sph/keterangan_simpan') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalForm').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem'
                });
            }
        });
    });
    
    // Hapus
    $(document).on('click', '.btn-hapus', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Hapus Keterangan?',
            text: 'Data keterangan akan dihapus dari daftar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('sph/keterangan_hapus') ?>',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem'
                        });
                    }
                });
            }
        });
    });
});
</script>
