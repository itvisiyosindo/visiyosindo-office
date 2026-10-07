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
    });
</script>