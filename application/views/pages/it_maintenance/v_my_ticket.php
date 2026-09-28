<header class="page-header">
    <h2><i class="icons fas fa-ticket-alt"></i>&nbsp;<?= $page_title ?></h2>
    <div class="right-wrapper text-left">
        <ol class="breadcrumbs">
            <li><span><?= $page_desc ?></span></li>
        </ol>
    </div>
</header>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="<?= base_url('it_maintenance/open_ticket') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Open Ticket Baru
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Daftar Tiket IT Saya</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelMyTicket" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Kode Tiket</th>
                            <th style="width: 25%;">Subject</th>
                            <th style="width: 15%;">Aset</th>
                            <th style="width: 10%;">Prioritas</th>
                            <th style="width: 15%;">Tanggal Pengajuan</th>
                            <th style="width: 10%;">Teknisi</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var table = $('#tabelMyTicket').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url("it_maintenance/my_ticket_pagination") ?>',
                type: 'POST'
            },
            columns: [{
                    data: 1, // kode_tiket
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                {
                    data: 2
                }, // subject
                {
                    data: 7, // nama_aset (index 7)
                    render: function(data, type, row) {
                        var kode = row[8]; // kode_aset (index 8)
                        if (kode && kode !== 'Tidak Ada') {
                            return data + ' (' + kode + ')';
                        }
                        return data || 'Lainnya / Umum';
                    }
                },
                {
                    data: 9, // prioritas
                    render: function(data) {
                        var text = data || 'Rendah';
                        var badge = 'badge-info';
                        if (text === 'Sedang') badge = 'badge-warning';
                        else if (text === 'Tinggi') badge = 'badge-danger';
                        return '<span class="badge ' + badge + '">' + text + '</span>';
                    }
                },
                {
                    data: 4, // created_at
                    render: function(data) {
                        if (!data) return '-';
                        var d = new Date(data);
                        return d.getDate().toString().padStart(2, '0') + '-' +
                            (d.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            d.getFullYear() + ' ' +
                            d.getHours().toString().padStart(2, '0') + ':' +
                            d.getMinutes().toString().padStart(2, '0');
                    }
                },
                {
                    data: 6, // nama_penerima (assigned IT tech)
                    render: function(data) {
                        return data || '<span class="text-muted">Belum Ditugaskan</span>';
                    }
                },
                {
                    data: 3, // status_tiket
                    render: function(data) {
                        var badges = {
                            1: '<span class="badge badge-warning">Open</span>',
                            2: '<span class="badge badge-info">In Progress</span>',
                            3: '<span class="badge badge-success">Solved</span>',
                            4: '<span class="badge badge-secondary">Closed</span>',
                            5: '<span class="badge badge-danger">Rejected</span>'
                        };
                        return badges[data] || '<span class="badge badge-dark">Unknown</span>';
                    }
                },
                {
                    data: 0, // id_ticket (for encrypt link)
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                    render: function(data, type, row) {
                        var buttons = '<div class="d-flex justify-content-center align-items-center flex-nowrap">';
                        buttons += '<a href="<?= base_url("it_maintenance/detail_ticket/") ?>' + data + '" class="btn btn-sm btn-info" title="Detail"><i class="fa fa-search"></i></a>';
                        buttons += '</div>';
                        return buttons;
                    }
                }
            ],
            order: [
                [1, 'desc']
            ],
            pageLength: 10,
            language: {
                processing: "Memproses...",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data tiket",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(filtered dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>