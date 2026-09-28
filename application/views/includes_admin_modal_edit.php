<!-- Admin Generic Edit Modal -->
<div class="modal fade" id="adminEditModal" tabindex="-1" role="dialog" aria-labelledby="adminEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold" id="adminEditModalLabel">
                    <i class="fas fa-user-shield mr-2"></i>Edit Data (Administrator Only)
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="adminEditModalBody">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-warning mb-3"></i>
                    <p class="text-muted">Memuat data form...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined') {
            (function($) {
                window.openAdminEditModal = function(module, idEnc) {
                    // Reset body to loading spinner
                    $('#adminEditModalBody').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-3x text-warning mb-3"></i>
                            <p class="text-muted">Memuat data form...</p>
                        </div>
                    `);
                    $('#adminEditModal').modal('show');

                    // Fetch form
                    $.ajax({
                        url: '<?= base_url("surat/get_admin_edit_form") ?>',
                        type: 'POST',
                        data: {
                            module: module,
                            id: idEnc,
                            token: $('input[name=token]').val()
                        },
                        success: function(html) {
                            $('#adminEditModalBody').html(html);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            $('#adminEditModalBody').html(`
                                <div class="alert alert-danger mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat form edit. Silakan coba lagi.
                                </div>
                            `);
                        }
                    });
                };

                // Prevent double submit binding
                $(document).off('submit', '#adminEditForm').on('submit', '#adminEditForm', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let submitBtn = form.find('button[type="submit"]');
                    let originalHtml = submitBtn.html();
                    
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                    $.ajax({
                        url: '<?= base_url("surat/save_admin_edit") ?>',
                        type: 'POST',
                        data: form.serialize() + '&token=' + $('input[name=token]').val(),
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                $('#adminEditModal').modal('hide');
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reload Datatable or reload page
                                    try {
                                        let dtTables = $.fn.dataTable.tables({ api: true });
                                        if (dtTables.length > 0) {
                                            dtTables.ajax.reload(null, false);
                                        } else {
                                            location.reload();
                                        }
                                    } catch (err) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                                submitBtn.prop('disabled', false).html(originalHtml);
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                            submitBtn.prop('disabled', false).html(originalHtml);
                        }
                    });
                });
            })(jQuery);
        }
    });
</script>
