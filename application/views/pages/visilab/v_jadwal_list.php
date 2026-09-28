<?php
// Page: Manajemen Jadwal Ukes & Upar
?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

<style>
  .jadwal-calendar-card {
    background: #fff;
    border: 1px solid #e3e6eb;
    border-radius: 14px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
  }

  .jadwal-calendar-card h4 {
    margin-bottom: 10px;
    font-weight: 700;
    color: #1f2a37;
  }

  .jadwal-calendar-card .calendar-subtitle {
    margin-bottom: 14px;
    color: #6b7280;
    font-size: 0.92rem;
  }

  #visilab-jadwal-calendar {
    min-height: 460px;
  }

  #visilab-jadwal-calendar .fc-event {
    cursor: pointer;
  }

  .jadwal-event-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .jadwal-event-title {
    font-size: 0.78rem;
    line-height: 1.15;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .wilayah-badge {
    display: inline-flex;
    align-items: center;
    padding: 1px 7px;
    border-radius: 999px;
    font-size: 0.67rem;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    flex-shrink: 0;
  }

  .jadwal-form-layout .form-row {
    margin-left: -8px;
    margin-right: -8px;
  }

  .jadwal-form-layout .form-group {
    position: relative;
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
    padding-top: 10px;
    padding-left: 8px;
    padding-right: 8px;
    border-top: 1px solid #edf1f6;
  }

  .jadwal-form-layout .form-group.form-col-left {
    border-top: 2px solid #d8e0ea;
  }

  .jadwal-form-layout .form-group > label {
    min-height: 22px;
    margin-bottom: 6px;
    font-weight: 600;
    color: #4a4a4a;
  }

  .jadwal-form-layout .form-control {
    min-height: 42px;
    border-radius: 8px;
  }

  .jadwal-form-layout .form-control::placeholder {
    color: #b0b7c3;
  }

  .jadwal-form-layout .select2-container--default .select2-selection--single {
    height: 42px;
    border: 1px solid #ced4da;
    border-radius: 8px;
  }

  .jadwal-form-layout .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    padding-left: 12px;
  }

  .jadwal-form-layout .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
    right: 8px;
  }

  #modalJadwal .select2-container {
    width: 100% !important;
  }

  .select2-container--open {
    z-index: 2055;
  }

  .jadwal-form-layout .form-row:last-child .form-group {
    margin-bottom: 0;
  }
</style>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="fas fa-calendar-week"></i> Manajemen Jadwal Ukes & Upar</h4>
            <div>
              <button class="btn btn-success mr-2" id="btn-export-jadwal"><i class="fas fa-file-excel"></i> Export Excel</button>
              <button class="btn btn-primary" id="btn-add-jadwal">Tambah Jadwal</button>
            </div>
        </div>

        <div id="visilab-jadwal-calendar"></div>
    </div>
</div>

<!-- Modal: detail -->
<div class="modal fade" id="modalDetailJadwal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Detail Jadwal</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div><strong>Jenis Jadwal:</strong> <span id="detail_jenis_jadwal">-</span></div>
        <div><strong>Teknisi:</strong> <span id="detail_teknisi">-</span></div>
        <div><strong>Tanggal:</strong> <span id="detail_tanggal_range">-</span></div>
        <div><strong>Jam:</strong> <span id="detail_jam">-</span></div>
        <div><strong>Status:</strong> <span id="detail_status">-</span></div>
        <div><strong>Pelanggan:</strong> <span id="detail_pelanggan">-</span></div>
        <div><strong>Wilayah:</strong> <span id="detail_wilayah">-</span></div>
        <div><strong>Provinsi:</strong> <span id="detail_provinsi">-</span></div>
        <div><strong>Kab/Kota:</strong> <span id="detail_kab_kota">-</span></div>
        <div><strong>Alamat:</strong> <span id="detail_lokasi_alamat">-</span></div>
        <div id="detail_pending_wrap" style="display:none;"><strong>Alasan Pending:</strong> <span id="detail_pending_reason">-</span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="btn-edit-jadwal">Edit Data</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: form -->
<div class="modal fade" id="modalJadwal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Form Jadwal</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="formJadwal" class="jadwal-form-layout">
          <input type="hidden" name="id" id="jadwal_id" />

          <div class="form-row">
            <div class="form-group col-md-6 form-col-left">
              <label>Jenis Jadwal</label>
              <select name="jenis_jadwal" id="jenis_jadwal" class="form-control" required>
                <option value="Uji Kesesuaian">Uji Kesesuaian</option>
                <option value="Uji Paparan">Uji Paparan</option>
                <option value="Uji Kesesuaian & Uji Paparan">Uji Kesesuaian &amp; Uji Paparan</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Teknisi</label>
              <select name="teknisi_id" id="teknisi_id" class="form-control">
                <option value="">-- Pilih Teknisi --</option>
                <?php foreach ($teknisi_list as $t): ?>
                  <option value="<?= $t->pengguna_id ?>"><?= htmlspecialchars($t->nama) ?><?= $t->no_pegawai ? ' ('.$t->no_pegawai.')' : '' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 form-col-left">
              <label>Tanggal Mulai</label>
              <input type="text" name="tanggal_mulai" id="tanggal_mulai" class="form-control" data-plugin-datepicker data-plugin-options='{"format":"yyyy-mm-dd"}' required />
            </div>
            <div class="form-group col-md-6">
              <label>Tanggal Selesai</label>
              <input type="text" name="tanggal_selesai" id="tanggal_selesai" class="form-control" data-plugin-datepicker data-plugin-options='{"format":"yyyy-mm-dd"}' required />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 form-col-left">
              <label>Jam</label>
              <input type="text" name="jam" id="jam" class="form-control" placeholder="09:00 - 11:00" />
            </div>
            <div class="form-group col-md-6">
              <label>Status</label>
              <select name="status" id="status" class="form-control">
                <option value="On Proses">On Proses</option>
                <option value="Pending">Pending</option>
                <option value="Selesai">Selesai</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 form-col-left">
              <label>Lokasi (Pelanggan)</label>
              <select name="lokasi_pelanggan_id" id="lokasi_pelanggan_id" class="form-control" data-placeholder="-- Pilih Pelanggan --"></select>
            </div>
            <div class="form-group col-md-6">
              <label>Wilayah</label>
              <select name="wilayah" id="wilayah" class="form-control">
                <option value="">-- Pilih Wilayah --</option>
                <option value="Sumatera">Sumatera</option>
                <option value="Jawa">Jawa</option>
                <option value="Kalimantan">Kalimantan</option>
                <option value="Sulawesi">Sulawesi</option>
                <option value="Bali Nusra">Bali Nusra</option>
                <option value="Maluku Papua">Maluku Papua</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-12" id="pending_reason_group" style="display:none;">
              <label>Alasan Pending</label>
              <input type="text" name="pending_reason" id="pending_reason" class="form-control" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 form-col-left">
              <label>Provinsi</label>
              <input type="text" name="provinsi" id="provinsi" class="form-control" placeholder="Provinsi" />
            </div>
            <div class="form-group col-md-6">
              <label>Kab/Kota</label>
              <input type="text" name="kab_kota" id="kab_kota" class="form-control" placeholder="Kabupaten / Kota" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-12">
              <label>Alamat Lokasi</label>
              <textarea name="lokasi_alamat" id="lokasi_alamat" class="form-control" rows="3" placeholder="Alamat lokasi akan terisi otomatis saat pelanggan dipilih"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" id="save_jadwal" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('visilab-jadwal-calendar');
    if (!calendarEl) return;

    var selectedEventData = null;

    function setPelangganSelect(data) {
      if (data && data.lokasi_pelanggan_id) {
        var pelangganText = data.lokasi_pelanggan_nama || 'Pelanggan';
        var pelangganOption = new Option(pelangganText, data.lokasi_pelanggan_id, true, true);
        $('#lokasi_pelanggan_id').append(pelangganOption).trigger('change');
      } else {
        $('#lokasi_pelanggan_id').val(null).trigger('change');
      }
    }

    function openEditForm(data) {
      data = data || {};
      $('#jadwal_id').val(data.id || '');
      $('#jenis_jadwal').val(data.jenis_jadwal || 'Uji Kesesuaian');
      $('#teknisi_id').val(data.teknisi_id || '').trigger('change');
      setPelangganSelect(data);
      $('#lokasi_alamat').val(data.lokasi_alamat || '');
      $('#wilayah').val(data.wilayah || '').trigger('change');
      $('#provinsi').val(data.provinsi || '');
      $('#kab_kota').val(data.kab_kota || '');
      $('#tanggal_mulai').val(data.tanggal_mulai || data.tanggal || '');
      $('#tanggal_selesai').val(data.tanggal_selesai || data.tanggal || '');
      $('#jam').val(data.jam || '');
      $('#status').val(data.status || 'On Proses');
      $('#pending_reason').val(data.pending_reason || '');
      if ((data.status || '').toLowerCase() === 'pending') {
        $('#pending_reason_group').show();
      } else {
        $('#pending_reason_group').hide();
      }
      $('#modalJadwal').modal('show');
    }

    function inferWilayahFromProvinsi(provinsi) {
      var p = (provinsi || '').toLowerCase();
      if (!p) return '';

      if (p.indexOf('aceh') >= 0 || p.indexOf('sumatera') >= 0 || p.indexOf('riau') >= 0 || p.indexOf('kepri') >= 0 || p.indexOf('jambi') >= 0 || p.indexOf('bengkulu') >= 0 || p.indexOf('lampung') >= 0 || p.indexOf('bangka') >= 0) return 'Sumatera';
      if (p.indexOf('dki') >= 0 || p.indexOf('jakarta') >= 0 || p.indexOf('jawa') >= 0 || p.indexOf('banten') >= 0 || p.indexOf('yogyakarta') >= 0) return 'Jawa';
      if (p.indexOf('kalimantan') >= 0) return 'Kalimantan';
      if (p.indexOf('sulawesi') >= 0 || p.indexOf('gorontalo') >= 0) return 'Sulawesi';
      if (p.indexOf('bali') >= 0 || p.indexOf('nusa tenggara') >= 0) return 'Bali Nusra';
      if (p.indexOf('maluku') >= 0 || p.indexOf('papua') >= 0) return 'Maluku Papua';
      return 'Lainnya';
    }

    function formatTanggalRange(mulai, selesai) {
      if (!mulai) return '-';
      var start = new Date(mulai + 'T00:00:00');
      var end = selesai ? new Date(selesai + 'T00:00:00') : start;
      var opt = { year: 'numeric', month: 'long', day: 'numeric' };
      var tStart = start.toLocaleDateString('id-ID', opt);
      var tEnd = end.toLocaleDateString('id-ID', opt);
      return tStart === tEnd ? tStart : (tStart + ' s/d ' + tEnd);
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 'auto',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,dayGridWeek' },
        events: function(info, successCallback, failureCallback) {
            var viewYear = new Date((info.start.getTime() + info.end.getTime()) / 2).getFullYear();
            $.getJSON('<?= base_url('visilab_jadwal/get_events') ?>', { year: viewYear })
                .done(function(resp){ successCallback(resp); })
                .fail(function(){ failureCallback(); });
        },
        eventContent: function(arg) {
          var props = arg.event.extendedProps || {};
          var wilayah = props.wilayah || 'Lainnya';
          var badgeColor = props.wilayah_badge_color || '#6c757d';
          var title = arg.event.title || '';
          return {
            html: '<div class="jadwal-event-wrap">'
                + '<span class="wilayah-badge" style="background:' + badgeColor + '">' + wilayah + '</span>'
                + '<span class="jadwal-event-title">' + title + '</span>'
                + '</div>'
          };
        },
        eventClick: function(info) {
            var props = info.event.extendedProps || {};
            selectedEventData = {
              id: info.event.id,
              jenis_jadwal: props.jenis_jadwal || 'Uji Kesesuaian',
              teknisi_id: props.teknisi_id || '',
              teknisi_nama: props.teknisi_nama || '',
              lokasi_pelanggan_id: props.lokasi_pelanggan_id || '',
              lokasi_pelanggan_nama: props.lokasi_pelanggan_nama || '',
              lokasi_alamat: props.lokasi_alamat || '',
              wilayah: props.wilayah || '',
              provinsi: props.provinsi || '',
              kab_kota: props.kab_kota || '',
              tanggal_mulai: props.tanggal_mulai || info.event.startStr || '',
              tanggal_selesai: props.tanggal_selesai || props.tanggal_mulai || info.event.startStr || '',
              jam: props.jam || '',
              status: props.status || 'On Proses',
              pending_reason: props.pending_reason || ''
            };

            $('#detail_jenis_jadwal').text(selectedEventData.jenis_jadwal || '-');
            $('#detail_teknisi').text(selectedEventData.teknisi_nama || (selectedEventData.teknisi_id ? ('ID ' + selectedEventData.teknisi_id) : '-'));
            $('#detail_tanggal_range').text(formatTanggalRange(selectedEventData.tanggal_mulai, selectedEventData.tanggal_selesai));
            $('#detail_jam').text(selectedEventData.jam || '-');
            $('#detail_status').text(selectedEventData.status || '-');
            $('#detail_pelanggan').text(selectedEventData.lokasi_pelanggan_nama || '-');
            $('#detail_wilayah').text(selectedEventData.wilayah || '-');
            $('#detail_provinsi').text(selectedEventData.provinsi || '-');
            $('#detail_kab_kota').text(selectedEventData.kab_kota || '-');
            $('#detail_lokasi_alamat').text(selectedEventData.lokasi_alamat || '-');

            if ((selectedEventData.status || '').toLowerCase() === 'pending' && selectedEventData.pending_reason) {
              $('#detail_pending_reason').text(selectedEventData.pending_reason);
              $('#detail_pending_wrap').show();
            } else {
              $('#detail_pending_reason').text('-');
              $('#detail_pending_wrap').hide();
            }

            $('#modalDetailJadwal').modal('show');
        }
    });

    calendar.render();

    $('#btn-add-jadwal').on('click', function(){
        $('#formJadwal')[0].reset();
        $('#jadwal_id').val('');
        $('#pending_reason_group').hide();
        $('#teknisi_id').val(null).trigger('change');
        $('#wilayah').val('').trigger('change');
        $('#provinsi').val('');
        $('#kab_kota').val('');
        $('#tanggal_mulai').val('');
        $('#tanggal_selesai').val('');
      if ($('#lokasi_pelanggan_id').data('select2')) {
        $('#lokasi_pelanggan_id').val(null).trigger('change');
      }
        selectedEventData = null;
        $('#modalJadwal').modal('show');
    });

    $('#btn-edit-jadwal').on('click', function() {
      if (!selectedEventData) return;
      $('#modalDetailJadwal').modal('hide');
      openEditForm(selectedEventData);
    });

    $('#status').on('change', function(){
        if ($(this).val() === 'Pending') $('#pending_reason_group').show(); else $('#pending_reason_group').hide();
    });

    $('#save_jadwal').on('click', function(){
        var tglMulai = $('#tanggal_mulai').val();
        var tglSelesai = $('#tanggal_selesai').val();
        if (tglMulai && tglSelesai && tglSelesai < tglMulai) {
          alert('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
          return;
        }
        var data = $('#formJadwal').serialize();
        $.post('<?= base_url('visilab_jadwal/save') ?>', data, function(resp){
            if (resp && resp.success) {
                $('#modalJadwal').modal('hide');
                calendar.refetchEvents();
            } else {
                alert('Gagal menyimpan data');
            }
        }, 'json').fail(function(){ alert('Gagal menyimpan data'); });
    });

      if ($.fn.select2) {
        var $modalJadwal = $('#modalJadwal');

        if ($('#teknisi_id').data('select2')) {
          $('#teknisi_id').select2('destroy');
        }
        if ($('#lokasi_pelanggan_id').data('select2')) {
          $('#lokasi_pelanggan_id').select2('destroy');
        }

        $('#teknisi_id').select2({
          placeholder: '-- Pilih Teknisi --',
          allowClear: true,
          width: '100%',
          dropdownParent: $modalJadwal
        });

        $('#wilayah').select2({
          placeholder: '-- Pilih Wilayah --',
          allowClear: true,
          width: '100%',
          dropdownParent: $modalJadwal
        });

        $('#lokasi_pelanggan_id').select2({
          placeholder: '-- Pilih Pelanggan --',
          allowClear: true,
          width: '100%',
          dropdownParent: $modalJadwal,
          ajax: {
            url: '<?= base_url('visilab_jadwal/ajax_pelanggan') ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
              return { q: params.term };
            },
            processResults: function(data) {
              return data;
            }
          }
        }).on('select2:select', function(e) {
          var d = e.params.data || {};
          $('#lokasi_alamat').val(d.alamat || '');
          $('#provinsi').val(d.provinsi || '');
          $('#kab_kota').val(d.kab_kota || '');
          if (!$('#wilayah').val()) {
            $('#wilayah').val(inferWilayahFromProvinsi(d.provinsi || '')).trigger('change');
          }
        }).on('select2:clear', function() {
          $('#lokasi_alamat').val('');
          $('#provinsi').val('');
          $('#kab_kota').val('');
        });
      }

      $('#btn-export-jadwal').on('click', function() {
        var year = calendar.getDate().getFullYear();
        window.open('<?= base_url('visilab_jadwal/export_excel') ?>?year=' + year, '_blank');
      });
});
</script>
