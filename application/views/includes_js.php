<!-- Vendor -->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="<?= base_url('assets/') ?>vendor/popper/umd/popper.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url('assets/') ?>vendor/common/common.js"></script>
<script src="<?= base_url('assets/') ?>vendor/nanoscroller/nanoscroller.js"></script>
<script src="<?= base_url('assets/') ?>vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jquery-placeholder/jquery.placeholder.js"></script>

<!-- Specific Page Vendor -->
<script src="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
<script src="<?= base_url('assets/') ?>vendor/jquery-validation/jquery.validate.js"></script>
<script src="<?= base_url('assets/') ?>vendor/select2/js/select2.js"></script>
<script src="<?= base_url('assets/') ?>vendor/dropzone/dropzone.js"></script>
<script src="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.js"></script>
<script src="<?= base_url('assets/') ?>vendor/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/') ?>js/global.js?v=<?= time() ?>"></script>
<script src="<?= base_url('assets/') ?>js/jquery.mask.min.js"></script>
<script src="<?= base_url('assets/') ?>js/table2excel.min.js"></script>
<script src="<?= base_url('assets/') ?>/js/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url('assets/') ?>/js/chart/chart.min.js"></script>
<script src="<?= base_url('assets/') ?>/js/ckeditor/ckeditor.js"></script>
<script src="<?= base_url('assets/') ?>/js/croppie/croppie.js"></script>

<!--(remove-empty-lines-end)-->

<!-- Theme Base, Components and Settings -->
<script src="<?= base_url('assets/') ?>js/theme.js"></script>


<!-- Theme Initialization Files -->
<script src="<?= base_url('assets/') ?>js/theme.init.js"></script>

<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">

<script>
    let token = $('input[name=token]').val()
    if (typeof localStorage !== 'undefined') {
        if (localStorage.getItem('sidebar-left-position') !== null) {
            var initialPosition = localStorage.getItem('sidebar-left-position'),
                sidebarLeft = document.querySelector('#sidebar-left .nano-content');
            if (sidebarLeft) {
                sidebarLeft.scrollTop = initialPosition;
            }
        }
    }

    $(document).on('click', '.btn-resend-wa', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var recipient = $(this).data('recipient');
        Swal.fire({
            title: 'Konfirmasi Kirim Notifikasi',
            html: '<div style="font-size: 14px; color: #475569; margin-top: 8px;">Apakah Anda ingin mengirim notifikasi kembali ke <strong style="color: #0f172a;">' + recipient + '</strong>?</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kirim Notifikasi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({
                    title: 'Mengirim...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = url;
            }
        });
    });

    $(document).on('click', '.btn-quick-stok', function(e) {
        e.preventDefault();
        var $el = $(this);
        var idBarang = $el.data('id-barang');
        var namaBarang = $el.data('nama-barang');
        var satuanBarang = $el.data('satuan') || 'Unit';
        var idGudang = $el.data('id-gudang');
        var namaGudang = $el.data('nama-gudang');
        var stokSaatIni = parseInt($el.data('stok'), 10) || 0;

        Swal.fire({
            title: 'Update / Sesuaikan Stok',
            html:
                '<div style="text-align:left; font-size:13px; margin-top:8px;">' +
                    '<div class="mb-2 p-2" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px;">' +
                        '<div><strong>Barang:</strong> ' + $('<div/>').text(namaBarang).html() + '</div>' +
                        '<div><strong>Satuan:</strong> <span class="badge badge-secondary">' + $('<div/>').text(satuanBarang).html() + '</span></div>' +
                        '<div><strong>Gudang:</strong> ' + $('<div/>').text(namaGudang).html() + '</div>' +
                        '<div><strong>Stok Saat Ini:</strong> <span class="badge badge-info">' + stokSaatIni + ' ' + $('<div/>').text(satuanBarang).html() + '</span></div>' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<label class="font-weight-bold mb-1">Jumlah Stok Aktual Baru (' + $('<div/>').text(satuanBarang).html() + ') <span class="text-danger">*</span></label>' +
                        '<input type="number" id="swal_stok_baru" class="form-control" min="0" value="' + stokSaatIni + '" style="width:100%; border:1px solid #cbd5e1; padding:6px 10px;">' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<label class="font-weight-bold mb-1">No. Batch / SN (Jika Tambah Stok)</label>' +
                        '<input type="text" id="swal_no_batch" class="form-control" placeholder="Contoh: ADJ-<?= date("Ymd") ?>" value="ADJ-<?= date("Ymd") ?>" style="width:100%; border:1px solid #cbd5e1; padding:6px 10px;">' +
                    '</div>' +
                    '<div class="form-group mb-0">' +
                        '<label class="font-weight-bold mb-1">Keterangan Penyesuaian</label>' +
                        '<input type="text" id="swal_ket_stok" class="form-control" placeholder="Penyesuaian Stok Gudang" value="Penyesuaian Stok Gudang" style="width:100%; border:1px solid #cbd5e1; padding:6px 10px;">' +
                    '</div>' +
                '</div>',
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-save mr-1"></i> Simpan Stok',
            cancelButtonText: 'Batal',
            didOpen: function() {
                var input = document.getElementById('swal_stok_baru');
                if (input) {
                    input.focus();
                    input.select();
                }
            }
        }).then(function(result) {
            if (result.isConfirmed || result.value) {
                var stokBaru = $('#swal_stok_baru').val();
                var noBatch = $('#swal_no_batch').val();
                var ketStok = $('#swal_ket_stok').val();

                if (stokBaru === '' || isNaN(stokBaru) || parseInt(stokBaru, 10) < 0) {
                    Swal.fire('Perhatian', 'Jumlah stok harus berupa angka 0 atau lebih!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Menyimpan Stok...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: function() {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?= base_url("stock_gudang/quickUpdateStok") ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_barang: idBarang,
                        id_gudang: idGudang,
                        stok_baru: parseInt(stokBaru, 10),
                        no_batch: noBatch,
                        keterangan: ketStok,
                        csrf_token: (typeof token !== 'undefined') ? token : ''
                    },
                    success: function(resp) {
                        if (resp && (resp.status === 'success' || resp.status === true)) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: resp.message || 'Stok berhasil diperbarui.',
                                icon: 'success',
                                timer: 1800,
                                showConfirmButton: false
                            });
                            if (typeof updateDatatable === 'function') {
                                updateDatatable();
                            } else if (typeof table !== 'undefined' && table.ajax) {
                                table.ajax.reload(null, false);
                            }
                        } else {
                            Swal.fire('Gagal', (resp && resp.message) ? resp.message : 'Gagal memperbarui stok.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghubungi server saat menyimpan stok.', 'error');
                    }
                });
            }
        });
    });

    $(document).ready(function() {
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: '<?= addslashes($this->session->flashdata('success')) ?>',
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            Swal.fire({
                title: 'Gagal!',
                text: '<?= addslashes($this->session->flashdata('error')) ?>',
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Tutup'
            });
        <?php endif; ?>
        <?php if ($this->session->flashdata('warning')): ?>
            Swal.fire({
                title: 'Peringatan!',
                text: '<?= addslashes($this->session->flashdata('warning')) ?>',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        // Global Logout Confirmation Alert
        $(document).on('click', 'a[href*="auth/logout"]', function(e) {
            e.preventDefault();
            var logoutUrl = $(this).attr('href');
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    window.location.href = logoutUrl;
                }
            });
        });

        // Initialize Custom Animated Switch Menu Dropdown
        var $nativeSwitchSelect = $('#switch_menu');
        if ($nativeSwitchSelect.length && !$('.custom-switch-dropdown').length) {
            var $selectedOpt = $nativeSwitchSelect.find('option:selected');
            var selectedText = ($selectedOpt.length && $selectedOpt.text()) ? $selectedOpt.text() : 'Pilih Menu';
            var selectedVal = $nativeSwitchSelect.val();

            var $wrapper = $('<div class="custom-switch-dropdown"></div>');
            var $trigger = $(
                '<button type="button" class="custom-switch-trigger" aria-haspopup="true" aria-expanded="false">' +
                    '<span class="custom-switch-text">' + selectedText + '</span>' +
                    '<i class="fas fa-chevron-down custom-switch-arrow"></i>' +
                '</button>'
            );
            var $menu = $('<div class="custom-switch-menu"></div>');

            $nativeSwitchSelect.find('option').each(function() {
                var val = $(this).val();
                var text = $(this).text();
                var isSelected = $(this).is(':selected') || (val === selectedVal);
                var $item = $(
                    '<a href="' + val + '" class="custom-switch-item ' + (isSelected ? 'active' : '') + '" data-value="' + val + '">' +
                        '<span class="custom-switch-item-text">' + text + '</span>' +
                        '<i class="fas fa-check custom-switch-item-check"></i>' +
                    '</a>'
                );
                $menu.append($item);
            });

            $nativeSwitchSelect.hide().after($wrapper);
            $wrapper.append($trigger).append($menu);

            $trigger.on('click', function(e) {
                e.stopPropagation();
                var isOpen = $wrapper.hasClass('open');
                $('.custom-switch-dropdown').removeClass('open');
                if (!isOpen) {
                    $wrapper.addClass('open');
                    $trigger.attr('aria-expanded', 'true');
                } else {
                    $trigger.attr('aria-expanded', 'false');
                }
            });

            $(document).on('click', function(e) {
                if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0) {
                    $wrapper.removeClass('open');
                    $trigger.attr('aria-expanded', 'false');
                }
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $wrapper.hasClass('open')) {
                    $wrapper.removeClass('open');
                    $trigger.attr('aria-expanded', 'false');
                }
            });
        }
    });
</script>