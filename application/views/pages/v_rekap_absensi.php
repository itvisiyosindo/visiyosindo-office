<header class="page-header">
    <h2><i class="icons icon-user-follow"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>
<div class="row mb-3" id="absensi-summary-cards">
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">Total Baris Absensi</div>
            <div class="h3 mb-0" id="stats_total_absensi">0</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">Hari Masuk</div>
            <div class="h3 mb-0" id="stats_total_masuk">0</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">Terlambat</div>
            <div class="h3 mb-0" id="stats_total_terlambat">0</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">Izin</div>
            <div class="h3 mb-0" id="stats_total_izin">0</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">Cuti</div>
            <div class="h3 mb-0" id="stats_total_cuti">0</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">WFA</div>
            <div class="h3 mb-0" id="stats_total_wfa">0</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">Hari Dinas</div>
            <div class="h3 mb-0" id="stats_total_dinas">0</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
        <div class="card card-body bg-light h-100 text-center">
            <div class="text-uppercase font-weight-bold small">Weekend Masuk</div>
            <div class="h3 mb-0" id="stats_total_weekend_masuk">0</div>
            <div class="small text-left mt-2" id="stats_weekend_masuk_list">Tidak ada absen masuk Sabtu/Minggu.</div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col">
        <div class="alert alert-info small mb-0">
            Total Baris Absensi menghitung semua baris absen masuk/istirahat/keluar. Hari Masuk adalah jumlah hari hadir dengan absen tipe "masuk".
        </div>
    </div>
</div>
<h2>Rekap Absensi "<?= $pengguna[0]->nama ?>"</h1>
    <div class="row">
        <div class="col">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <small>Filter By Month:</small>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="filter_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <small>Filter By date:</small>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm-dd", "minViewMode": "days"}' class="form-control" id="filter_date" placeholder="Pilih Tanggal" required data-plugin-datepicker>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <small>Filter By Type Absen:</small>
                        <select class="form-control " name="filter_type" id="filter_type">
                            <option value="">Semua</option>
                            <option value="masuk">Absen Masuk</option>
                            <option value="istirahat">Absen Istirahat</option>
                            <option value="keluar">Absen Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <small>Filter By Status Absen:</small>
                        <select class="form-control " name="filter_status" id="filter_status">
                            <option value="">Semua</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="tepat_waktu">Tepat Waktu</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <small> <i class="fas fa-print"></i> Print By Month:</small>
                        <div class="input-group mb-1">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                            <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "yyyy-mm", "minViewMode": "months"}' class="form-control" id="print_month" placeholder="Pilih Bulan" required data-plugin-datepicker>
                        </div>
                        <div class="btn-group w-100 mt-1" role="group">
                            <button type="button" id="btn_print_detail_absen" class="btn btn-sm btn-primary text-white" style="font-weight: 600;" title="Cetak Rekap Absensi Karyawan">
                                <i class="fas fa-print"></i> Absen
                            </button>
                            <button type="button" id="btn_print_rekap_tunjangan" class="btn btn-sm btn-danger text-white" style="font-weight: 600;" title="Cetak Rekapitulasi Tunjangan Tidak Tetap (PDF)">
                                <i class="fas fa-file-pdf"></i> Rekap PDF
                            </button>
                            <button type="button" id="btn_print_foto_gps" class="btn btn-sm btn-info text-white" style="font-weight: 600;" title="Print Absensi 1 Bulan Lengkap Foto Selfie & GPS">
                                <i class="fas fa-camera"></i> Foto
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <small>Filter By Jenis Absen:</small>
                        <select class="form-control " name="jenis_absen" id="jenis_absen">
                            <option value="">Semua</option>
                            <option value="Kantor">Kantor</option>
                            <option value="WFA">WFA</option>
                            <option value="Dinas">Dinas</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover" id="kt_table_1">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Tanggal Absensi </th>
                                <th> Type Absen </th>
                                <th> Waktu Absen </th>
                                <th> Status Absen </th>
                                <th> Lokasi Absen & Foto</th>
                                <th> Tunjangan </th>
                                <th> File Pendukung </th>
                                <th> Keterangan </th>
                                <th> IP Address </th>
                                <th> Approval </th>
                                <th> Lokasi Kerja </th>
                                <th> Jenis Absen </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="pengguna_id" value="<?= $pengguna_id ?>">
    <div id="main-modal" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-dark text-light">
                    <h4 id="modal-label"><i class="flaticon2-avatar icon-2x text-grey-light"></i>Lokasi Absen</h4>
                    <button type="button" class="close" style="color:white;margin: -1px" data-dismiss="modal" aria-label="Close"><i class="far fa-times-circle"></i></button>
                </div>
                <div class="modal-body">
                    <style>
                        #dvImage {
                            display: flex;
                            justify-content: center;
                            /* Center horizontally */
                            align-items: center;
                            /* Center vertically */
                            height: 300px;
                            background-color: lightgray;
                        }
                    </style>
                    <div id="dvImage" style="height: 300px"></div>
                    <div id="dvMap" style="height: 400px"></div><br>
                    <div class="text-right">
                        <!-- <input type="hidden" id='latitude' value="">
					<input type="hidden" id='longitude' value=""> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script>
        function updateStatsCards() {
            $.ajax({
                method: 'POST',
                url: 'absensi/getStatsCards',
                dataType: 'JSON',
                data: {
                    filter_month: $('#filter_month').val(),
                    filter_date: $('#filter_date').val(),
                    filter_type: $('#filter_type').val(),
                    filter_status: $('#filter_status').val(),
                    jenis_absen: $('#jenis_absen').val(),
                    pengguna_id: $('#pengguna_id').val(),
                    csrf_token: token
                },
                success: function(resp) {
                    if (resp.status == 'success' && resp.totals) {
                        $('#stats_total_absensi').text(resp.totals.total_absensi);
                        $('#stats_total_masuk').text(resp.totals.total_masuk);
                        $('#stats_total_terlambat').text(resp.totals.total_terlambat);
                        $('#stats_total_izin').text(resp.totals.total_izin);
                        $('#stats_total_cuti').text(resp.totals.total_cuti);
                        $('#stats_total_wfa').text(resp.totals.total_wfa);
                        $('#stats_total_dinas').text(resp.totals.total_dinas);
                        $('#stats_total_weekend_masuk').text(resp.totals.weekend_absen_count || 0);
                        var weekendList = resp.totals.weekend_absen_dates || [];
                        if (weekendList.length) {
                            $('#stats_weekend_masuk_list').html('<ul class="mb-0 pl-3">' + weekendList.map(function(item){ return '<li>' + item + '</li>'; }).join('') + '</ul>');
                        } else {
                            $('#stats_weekend_masuk_list').text('Tidak ada absen masuk Sabtu/Minggu.');
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Load stats cards on page load
            updateStatsCards();
            
            // Update stats cards when any filter changes
            $('#filter_month, #filter_date, #filter_type, #filter_status, #jenis_absen').change(function() {
                updateStatsCards();
                table.ajax.reload();
            })
            
            table = $('#kt_table_1').DataTable({
                responsive: false,
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: 'absensi/rekap_absensi',
                    type: 'POST',
                    data: function(e) {
                        e.filter_month = $('#filter_month').val()
                        e.filter_status = $('#filter_status').val()
                        e.filter_date = $('#filter_date').val()
                        e.filter_type = $('#filter_type').val()
                        e.pengguna_id = $('#pengguna_id').val()
                        e.jenis_absen = $('#jenis_absen').val()
                        e.csrf_token = token
                    },
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    className: 'text-center'
                }]
            })

            $(document).on('click', '.btn-lihat-posisi', function() {
                $('#main-modal').modal()
                $.ajax({
                    method: 'POST',
                    url: 'absensi/show/latlong/by_id',
                    dataType: 'JSON',
                    data: {
                        id: $(this).attr("data-id"),
                        csrf_token: token
                    },
                    success: function(resp) {

                        /*if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(p) {
                                var LatLng = new google.maps.LatLng(resp.latitude, resp.longitude);
                                var mapOptions = {
                                    center: LatLng,
                                    zoom: 19,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
                                var marker = new google.maps.Marker({
                                    position: LatLng,
                                    map: map,
                                    title: "Latitude: " + p.coords.latitude + "Longitude: " + p.coords.longitude
                                });
                                google.maps.event.addListener(marker, "click", function(e) {
                                    var infoWindow = new google.maps.InfoWindow();
                                    infoWindow.setContent(marker.title);
                                    infoWindow.open(map, marker);
                                });
                            });
                        } else {
                            alert('Geo Location feature is not supported in this browser.');
                        }*/
                        if ((resp.latitude != null) && (resp.longitude != null)) {
                            // $('#main-modal').modal()
                            //document.getElementById("dvMap").innerHTML = "<iframe style='overflow:hidden;height:100%;width:100%' loading='lazy' allowfullscreen referrerpolicy='no-referrer-when-downgrade' src='https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo &q="+ resp.latitude + "," + resp.longitude + " &center="+ resp.latitude + "," + resp.longitude + " &zoom=21 &maptype=roadmap'></iframe>"
                            $('#main-modal').modal();

                            // Menampilkan peta di dalam dvMap
                            document.getElementById("dvMap").innerHTML = `
                                    <iframe style="overflow:hidden;height:100%;width:100%" 
                                        loading="lazy" 
                                        allowfullscreen 
                                        referrerpolicy="no-referrer-when-downgrade" 
                                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAFycbDEoOn8GPKQ1_fij6S1e1UpRZgKJo
                                        &q=${resp.latitude},${resp.longitude}
                                        &center=${resp.latitude},${resp.longitude}
                                        &zoom=21
                                        &maptype=roadmap">
                                    </iframe>
                                `;

                            // Menampilkan foto di dalam dvImage
                            if (resp.file_foto) {
                                document.getElementById("dvImage").innerHTML = `
                                        <img src="assets/img/absen/${resp.file_foto}" 
                                            alt="Foto Absen" 
                                            style="max-width: 100%; height: auto;">
                                    `;
                            } else {
                                document.getElementById("dvImage").innerHTML = `
                                        <p>Foto tidak tersedia.</p>
                                    `;
                            }


                        } else {
                            document.getElementById("dvMap").innerHTML = "";
                        }
                    }
                })
            })

            $('#btn_print_detail_absen').click(function() {
                var month = $('#print_month').val() || $('#filter_month').val() || '<?= date("Y-m") ?>';
                var id = $('#pengguna_id').val();
                var link = 'absensi/print/detailKaryawanMonth/' + month + '/' + id;
                window.open('<?= base_url() ?>' + link, '_blank');
            });

            $('#btn_print_rekap_tunjangan').click(function() {
                var month = $('#print_month').val() || $('#filter_month').val() || '<?= date("Y-m") ?>';
                window.open('<?= base_url("absensi/print/allKaryawanByMonth/") ?>' + month, '_blank');
            });

            $('#btn_print_foto_gps').click(function() {
                var month = $('#print_month').val() || $('#filter_month').val() || '<?= date("Y-m") ?>';
                var id = $('#pengguna_id').val();
                var link = 'absensi/print_foto_gps/' + month + '/' + id;
                window.open('<?= base_url() ?>' + link, '_blank');
            });

            $(document).on('click', '.btn-approval', function() {
                const id_absensi = $(this).attr("data-id")
                const pengguna_id = $(this).attr("pengguna-id")
                const approval = $(this).attr("approval")
                Swal.fire({
                    title: approval + ' absensi?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            method: 'POST',
                            url: 'salary_tidak_tetap/add',
                            dataType: 'JSON',
                            data: {
                                id_absensi: id_absensi,
                                pengguna_id: pengguna_id,
                                approval: approval,
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

        function updateDatatable() {
            table.ajax.reload(null, false)
        }
    </script>