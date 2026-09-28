
var globalJS = function () {
    $(document).on('click', '.btn-save', function () {
        /** SPECIAL CASE FOR CKEDIOTR INPUT */
        if (typeof (CKEDITOR) == "function") {
            for (var instanceName in CKEDITOR.instances)
                CKEDITOR.instances[instanceName].updateElement();
        }

        const form = $(this).closest('form')
        if ($(this).closest('#main-modal-edit').length) {
            return;
        }
        const url = form.attr('action')
        const formId = form.attr('id')
        Swal.fire({
            title: 'Simpan Data?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData($('#' + formId)[0]),
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function () {
                        Swal.fire({
                            html: `<h4>Mohon Tunggu...</h4>`,
                            icon: 'info',
                            allowOutsideClick: false,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        })
                    },
                    success: function (resp) {
                        handleResponse(resp)
                    }
                });
            }
        })
    })

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id')
        const objek = $(this).data('object')
        Swal.fire({
            title: 'Hapus Data?',
            icon: 'error',
            text: 'Data yang sudah dihapus tidak dapat dikembalikan lagi!',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: objek + '/' + id,
                    method: 'POST',
                    dataType: "JSON",
                    data: {
                        csrf_token: token
                    },
                    success: function (resp) {
                        handleResponse(resp)
                    }
                });
            }
        })
    })


    return {
        notificationCheck: function () {
            // $.ajax({
            //     method: 'POST',
            //     url: 'notifikasi/get',
            //     dataType: 'json',
            //     data : {
            //         csrf_token : token
            //     },
            //     success : function(resp){
            //         $('.notif-count').html(resp['count_belum_baca'])
            //         $('.notif-badge-available').hide()
            //         if(resp['count_belum_baca'] > 0){
            //             console.log(resp['count_belum_baca'])
            //             $('.notif-badge-available').show()
            //             var list = resp['list_belum_baca'];
            //             list.forEach(function(resp){
            //                 let x = `
            //                         <ul>
            //                             <li>
            //                                 <a href="notifikasi/update/${resp['notifikasi_id']}" class="clearfix">
            //                                     <div class="image">
            //                                         <i class="bx bx-bell bg-warning text-light"></i>
            //                                     </div>
            //                                     <span class="title text-1">${resp['judul']}</span>
            //                                     <span class="message">${resp['time']}</span>
            //                                 </a>
            //                             </li>
            //                         </ul>
            //                         <br>`
            //                 $('#notif-list').append(x);
            //             })

            //             if(resp['count_belum_baca'] > list.length){
            //                 let x = `
            //                 <div class="d-flex flex-column">
            //                     <a href="notifikasi" class="text-2">Lihat <b>${(resp['count_belum_baca']  - list.length)}</b> Notifikasi Lainnya</a>
            //                 </div>`
            //                 $('#notif-list').append(x);
            //             }
            //         } else {
            //             $('#notification-dropdown').hide();
            //         }
            //     }
            // });


        },
        widget: function () {
            /**
             * Handle search pembelajar form
             */
            // $(".search_barang_diform").themePluginSelect2({
            //     placeholder: "--- Ketik Nama Barang ---",
            //     allowClear: true,
            //     minimumInputLength: 1,
            //     width:'100%',
            //     ajax: {
            //         method:'POST',
            //         url: "barang/get/by_search",
            //         dataType: 'json',
            //         delay: 250,
            //         data: 

            //         function (params) {
            //             return {
            //                 q: params.term, // search term
            //                 csrf_token : token
            //             };
            //         },
            //         processResults: function (data, params) {
            //             return {
            //                 results: $.map(data.items,function(obj){
            //                     return {
            //                         id : obj.id_barang,
            //                         text : `${obj.nama_barang} -  ${obj.kode_barang}`
            //                     };
            //                 })
            //             }
            //         },
            //         cache: true
            //     },
            // });

            $('.select2-local').themePluginSelect2({
                width: '100%'
            })

        },
        setAccordionIdToHref: function (e) {
            var hash = $(e).attr('href');
            var urlWithoutHash = document.location.href.replace(location.hash, "");
            window.location.replace(urlWithoutHash + hash + '&');
        },

        autoOpenAccordion: function () {
            var tmp = location.hash.split('&');
            var last = ''
            tmp.forEach(function (q) {
                var anchor = $('a[href="' + q + '"]');
                if (anchor.length > 0) {
                    anchor.click();
                    last = q;
                }
            });
        }
    }
}()

function handleResponse(resp) {
    if (resp['reload']) {
        if (resp['reload'] === true || resp['reload'] == 'reload') {
            location.reload()
        }
        else if (resp['reload'] == 'reload_table') {
            $("table").each(function () {
                var table_id = $(this).attr('id')
                $('#' + table_id).DataTable().ajax.reload(null);
            });
        }
        else
            window.location = resp['reload']
    }
    if (resp['status'] == 'error') {
        return Swal.fire({
            html: `<h4>${resp['msg']}</h4>`,
            icon: resp['status']
        })
    } else {
        return Swal.fire({
            html: `<h4>${resp['msg']}</h4>`,
            icon: resp['status'],
            timer: 800,
            timerProgressBar: true,
            showConfirmButton: false,
        })
    }
}

jQuery(document).ready(function () {
    // Set global default options for datepickers (bootstrap-datepicker / bootstrapDP)
    if (jQuery.fn.bootstrapDP && jQuery.fn.bootstrapDP.defaults) {
        jQuery.fn.bootstrapDP.defaults.todayHighlight = true;
        jQuery.fn.bootstrapDP.defaults.todayBtn = "linked";
    }
    if (jQuery.fn.datepicker && jQuery.fn.datepicker.defaults) {
        jQuery.fn.datepicker.defaults.todayHighlight = true;
        jQuery.fn.datepicker.defaults.todayBtn = "linked";
    }

    // Toggle show/hide masked asset values (eye icon)
    $(document).on('click', '.toggle-value', function (e) {
        e.preventDefault();
        var container = $(this).closest('.asset-value-container');
        var valText = container.find('.val-text');
        var icon = $(this).find('i');
        var state = container.attr('data-state');
        var realVal = container.attr('data-real');
        var maskedVal = container.attr('data-masked');

        if (state === 'masked') {
            valText.text(realVal);
            container.attr('data-state', 'real');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            valText.text(maskedVal);
            container.attr('data-state', 'masked');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    globalJS.notificationCheck()
    globalJS.widget()
    globalJS.autoOpenAccordion()
})