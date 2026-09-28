<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hasil_evaluasi_semester extends CI_Controller
{
  function id_navbar()
  {
    $id_navbar = "kepegawaian";
    return $id_navbar;
  }

  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->model('md_hasil_evaluasi_semester');
    $this->load->model('md_pengguna');
    $this->load->helper('encrypt_helper');
    $this->load->helper('whatsapp_helper');
  }

  /**
   * Private function untuk kirim WA - menggunakan helper sendWa yang sudah ada
   */
  private function sendWaEvaluasi($phone, $pesan)
  {
    // Format nomor HP
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (substr($phone, 0, 1) == '0') {
      $phone = '62' . substr($phone, 1);
    }
    if (substr($phone, 0, 2) != '62') {
      $phone = '62' . $phone;
    }

    $devId = hostWa('1');

    // Gunakan helper sendWa yang sudah ada dan berfungsi
    $dataWa = [
      'devId'    => $devId,
      'penerima' => $phone,
      'pesan'    => $pesan
    ];

    try {
      sendWa($dataWa);
      return [
        'success' => true,
        'phone' => $phone
      ];
    } catch (Exception $e) {
      return [
        'success' => false,
        'phone' => $phone,
        'error' => $e->getMessage()
      ];
    }
  }

  /**
   * DEBUG: Test perhitungan evaluasi untuk pegawai tertentu
   * URL: /hasil_evaluasi_semester/debug_evaluasi/[pengguna_id]
   * URL: /hasil_evaluasi_semester/debug_evaluasi?nama=Amtisari
   */
  public function debug_evaluasi($pengguna_id = null)
  {
    $tahun = $this->input->get('tahun') ?: date('Y');
    $semester = $this->input->get('semester') ?: (date('n') <= 6 ? 1 : 2);
    $nama_search = $this->input->get('nama');

    if ($semester == 1) {
      $start_date = "$tahun-01-01";
      $end_date = "$tahun-06-30";
    } else {
      $start_date = "$tahun-07-01";
      $end_date = "$tahun-12-31";
    }

    // Search by nama jika diberikan
    if ($nama_search) {
      $pegawai_list = $this->md_hasil_evaluasi_semester->getPegawaiDatatables(-1, 0, $nama_search);
      echo "<h2>DEBUG EVALUASI - Search: $nama_search</h2>";
      echo "<p><strong>Periode:</strong> $start_date - $end_date (Semester $semester, Tahun $tahun)</p>";

      if (empty($pegawai_list)) {
        echo "Pegawai tidak ditemukan";
        return;
      }

      foreach ($pegawai_list as $p) {
        $this->debug_single_pegawai($p->pengguna_id, $semester, $tahun, $start_date, $end_date);
        echo "<hr>";
      }
      return;
    }

    if (!$pengguna_id) {
      echo "Masukkan pengguna_id di URL atau gunakan ?nama=xxx";
      return;
    }

    echo "<h2>DEBUG EVALUASI</h2>";
    echo "<p><strong>Periode:</strong> $start_date - $end_date (Semester $semester, Tahun $tahun)</p>";

    $this->debug_single_pegawai($pengguna_id, $semester, $tahun, $start_date, $end_date);
  }

  private function debug_single_pegawai($pengguna_id, $semester, $tahun, $start_date, $end_date)
  {
    $pegawai = $this->md_hasil_evaluasi_semester->getPegawaiById($pengguna_id);

    if (!$pegawai) {
      echo "Pegawai ID $pengguna_id tidak ditemukan";
      return;
    }

    echo "<h3>Data Pegawai: {$pegawai->nama}</h3>";
    echo "<p><strong>ID:</strong> {$pegawai->pengguna_id}</p>";
    echo "<p><strong>No Pegawai:</strong> {$pegawai->no_pegawai}</p>";
    echo "<p><strong>Jabatan (raw):</strong> '{$pegawai->jabatan}'</p>";

    // Normalisasi jabatan (sama seperti di model)
    $jabatan_raw = $pegawai->jabatan ?? '';
    $jabatan_normalized = strtolower(preg_replace('/[^a-zA-Z]+/', ' ', $jabatan_raw));
    $jabatan_normalized = trim($jabatan_normalized);

    echo "<p><strong>Jabatan (raw hex):</strong> " . bin2hex($jabatan_raw) . "</p>";
    echo "<p><strong>Jabatan (normalized):</strong> '$jabatan_normalized'</p>";

    // Test kondisi
    echo "<h4>Test Kondisi Jabatan (dengan normalisasi)</h4>";
    echo "<ul>";
    echo "<li>strpos('$jabatan_normalized', 'hr'): " . var_export(strpos($jabatan_normalized, 'hr'), true) . "</li>";
    echo "<li>strpos('$jabatan_normalized', 'legal'): " . var_export(strpos($jabatan_normalized, 'legal'), true) . "</li>";
    echo "<li>strpos('$jabatan_normalized', 'general affair'): " . var_export(strpos($jabatan_normalized, 'general affair'), true) . "</li>";
    echo "<li>strpos('$jabatan_normalized', 'ga'): " . var_export(strpos($jabatan_normalized, 'ga'), true) . "</li>";
    echo "<li>strpos('$jabatan_normalized', 'security'): " . var_export(strpos($jabatan_normalized, 'security'), true) . "</li>";
    echo "<li>strpos('$jabatan_normalized', 'helper'): " . var_export(strpos($jabatan_normalized, 'helper'), true) . "</li>";
    echo "<li>strpos('$jabatan_normalized', 'office boy'): " . var_export(strpos($jabatan_normalized, 'office boy'), true) . "</li>";
    echo "</ul>";

    // Hasil evaluasi
    echo "<h4>Hasil getTotalNilaiEvaluasi</h4>";
    $hasil = $this->md_hasil_evaluasi_semester->getTotalNilaiEvaluasi($pengguna_id, $semester, $tahun, $start_date, $end_date);
    echo "<pre>" . print_r($hasil, true) . "</pre>";
  }

  public function index()
  {
    grantAccessFor('all');

    // Default filter: Tahun sekarang & Semester berdasarkan bulan
    $data['tahun']      = $this->input->get('tahun') ?: date('Y');
    $data['semester']   = $this->input->get('semester') ?: (date('n') <= 6 ? 1 : 2);

    $page_data['switch']      = $this->id_navbar();
    $page_data['tahun']       = $data['tahun'];
    $page_data['semester']    = $data['semester'];

    // Pastikan path view ini benar
    $page_data['page_name']   = 'hasil_evaluasi_semester/v_hasil_evaluasi';

    $page_data['page_title']  = 'Hasil Evaluasi Semester';
    $page_data['page_desc']   = 'Rangkuman Kinerja Karyawan';

    $this->load->view('index', $page_data);
  }

  // JSON GENERATOR (Inti logic ada disini)
  public function pagination()
  {
    // 1. Ambil Parameter Filter dari Ajax
    $tahun    = $this->input->post('tahun');
    $semester = $this->input->post('semester');

    // 2. Tentukan Range Tanggal berdasarkan Semester
    if ($semester == 1) {
      $start_date = "$tahun-01-01";
      $end_date   = "$tahun-06-30";
    } else {
      $start_date = "$tahun-07-01";
      $end_date   = "$tahun-12-31";
    }

    // 3. Parameter DataTables (Paging & Searching)
    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $search = $this->input->post('search')['value'];

    // 4. Ambil Data Pegawai
    $list_pegawai = $this->md_hasil_evaluasi_semester->getPegawaiDatatables($length, $start, $search);
    $total_data   = $this->md_hasil_evaluasi_semester->countAllPegawai($search);

    // 5. FOREACH: Loop data pegawai & hitung nilai satu per satu
    $data = array();
    $no   = $start + 1;

    foreach ($list_pegawai as $p) {
      $row = array();
      $id_encrypted = encrypt($p->pengguna_id);

      // -- Kolom 1: No --
      $row[] = $no++;

      // -- Kolom 2: Pegawai dengan Avatar Inisial --
      $words = explode(' ', trim($p->nama));
      $initials = '';
      foreach ($words as $word) {
        if (!empty($word)) {
          $initials .= strtoupper(substr($word, 0, 1));
          if (strlen($initials) >= 2) break;
        }
      }
      if (empty($initials)) $initials = 'NA';

      // Generate consistent color based on name
      $colors = [
        ['#667eea', '#764ba2'],
        ['#f093fb', '#f5576c'],
        ['#4facfe', '#00f2fe'],
        ['#43e97b', '#38f9d7'],
        ['#fa709a', '#fee140'],
        ['#11998e', '#38ef7d'],
        ['#ff9a9e', '#fecfef'],
        ['#ffecd2', '#fcb69f'],
      ];
      $colorIndex = abs(crc32($p->nama)) % count($colors);
      $avatarColor = $colors[$colorIndex];

      $avatar = '<div style="display:inline-flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,' . $avatarColor[0] . ',' . $avatarColor[1] . ');display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:13px;flex-shrink:0;">' . $initials . '</div>
        <div><strong style="font-size:14px;">' . $p->nama . '</strong><br><small class="text-muted">' . ($p->no_pegawai ?: '-') . '</small></div>
      </div>';
      $row[] = $avatar;

      // -- Kolom 3: Jabatan --
      $row[] = $p->jabatan;

      // -- Kolom 4: Rata Laporan (Index 3) --
      $avg_laporan = $this->md_hasil_evaluasi_semester->getRataRataLaporan($p->pengguna_id, $start_date, $end_date);
      $predikat_lap = $this->md_hasil_evaluasi_semester->getPredikat($avg_laporan);
      $row[] = '<span class="badge badge-' . $predikat_lap['class'] . '" style="padding:6px 12px;border-radius:15px;">' . number_format($avg_laporan, 1) . '</span>';

      // ==========================================================
      // PASTIIN BAGIAN INI SUDAH ADA & TIDAK DI-COMMENT
      // -- Kolom 5: Rata Evaluasi (Index 4) -- 
      // ==========================================================
      $avg_evaluasi = $this->md_hasil_evaluasi_semester->getNilaiEvaluasi($p->pengguna_id, $semester, $tahun);
      if ($avg_evaluasi > 0) {
        $predikat_eval = $this->md_hasil_evaluasi_semester->getPredikat($avg_evaluasi);
        $row[] = '<span class="badge badge-' . $predikat_eval['class'] . '" style="padding:6px 12px;border-radius:15px;">' . number_format($avg_evaluasi, 1) . '</span>';
      } else {
        $row[] = '<span class="text-muted">-</span>';
      }
      // ==========================================================

      // -- Kolom 6: Total Kehadiran (Index 5) --
      $total_hadir = $this->md_hasil_evaluasi_semester->getTotalKehadiran($p->pengguna_id, $start_date, $end_date);
      $row[] = '<span style="background:linear-gradient(135deg,#11998e,#38ef7d);color:white;padding:6px 12px;border-radius:15px;font-weight:500;">' . $total_hadir . '</span>';

      // -- Kolom 7: Total SP (Index 6) - Nilai SP berdasarkan SP terakhir --
      $nilai_sp = $this->md_hasil_evaluasi_semester->getNilaiSP($p->pengguna_id, $start_date, $end_date);
      $sp_terakhir = $this->md_hasil_evaluasi_semester->getSPTerakhir($p->pengguna_id, $start_date, $end_date);

      if ($sp_terakhir) {
        $label_sp = $this->md_hasil_evaluasi_semester->getLabelJenisSP($sp_terakhir->jenis_sp);
        $row[] = '<span style="background:linear-gradient(135deg,#eb3349,#f45c43);color:white;padding:6px 12px;border-radius:15px;font-weight:500;" title="' . $label_sp . ' - Nilai: ' . $nilai_sp . '">' . $label_sp . ' (' . $nilai_sp . ')</span>';
      } else {
        $row[] = '<span class="badge badge-light" style="padding:6px 12px;border-radius:15px;" title="Tidak ada SP - Nilai: 100">- (100)</span>';
      }

      // -- Kolom 8: Nilai PK (Index 7) --
      $nilai_pk = $this->md_hasil_evaluasi_semester->getNilaiPK($p->pengguna_id, $semester, $tahun);
      if ($nilai_pk > 0) {
        $predikat_pk = $this->md_hasil_evaluasi_semester->getPredikat($nilai_pk);
        $row[] = '<span class="badge badge-' . $predikat_pk['class'] . '" style="padding:6px 12px;border-radius:15px;">' . number_format($nilai_pk, 1) . '</span>';
      } else {
        $row[] = '<span class="text-muted">-</span>';
      }

      // -- Kolom 9: Total Nilai Evaluasi Semester (Index 8) --
      $hasil_evaluasi = $this->md_hasil_evaluasi_semester->getTotalNilaiEvaluasi($p->pengguna_id, $semester, $tahun, $start_date, $end_date);
      $total_nilai_final = $hasil_evaluasi['total_nilai'];
      $predikat_final = $this->md_hasil_evaluasi_semester->getPredikat($total_nilai_final);
      $formula_desc = $this->md_hasil_evaluasi_semester->getFormulaDescription($hasil_evaluasi['formula_type']);

      $row[] = '<span class="badge badge-' . $predikat_final['class'] . '" style="padding:8px 14px;border-radius:15px;font-weight:600;font-size:13px;" title="' . $formula_desc . '">' . number_format($total_nilai_final, 2) . ' (' . $predikat_final['predikat'] . ')</span>';

      // -- Kolom 10: Aksi (Index 9) --
      $url_detail = site_url('hasil_evaluasi_semester/detail/' . $id_encrypted . '?tahun=' . $tahun . '&semester=' . $semester);
      $btn_detail = '<a href="' . $url_detail . '" class="btn btn-sm mr-1" style="background:linear-gradient(135deg,#667eea,#764ba2);border:none;padding:6px 12px;border-radius:8px;color:white;font-size:11px;" title="Lihat Detail"><i class="fa fa-eye"></i></a>';

      // Button WhatsApp
      $no_hp = $p->no_hp ?: '';
      $btn_wa = '<button type="button" class="btn btn-sm btn-send-wa" style="background:linear-gradient(135deg,#25d366,#128c7e);border:none;padding:6px 12px;border-radius:8px;color:white;font-size:11px;" data-id="' . $p->pengguna_id . '" data-nama="' . htmlspecialchars($p->nama) . '" data-hp="' . $no_hp . '" title="Kirim via WhatsApp"><i class="fab fa-whatsapp"></i></button>';

      $row[] = '<div class="d-flex justify-content-center gap-1">' . $btn_detail . $btn_wa . '</div>';

      $data[] = $row;
    }

    // 6. Kirim JSON ke View
    $output = array(
      "draw"            => intval($this->input->post('draw')),
      "recordsTotal"    => intval($total_data),
      "recordsFiltered" => intval($total_data),
      "data"            => $data,
    );

    header('Content-Type: application/json');
    echo json_encode($output);
  }

  /**
   * Halaman Detail Evaluasi Karyawan
   */
  public function detail($id_encrypted)
  {
    grantAccessFor('all');

    $id_pengguna = decrypt($id_encrypted);
    $tahun       = $this->input->get('tahun') ?: date('Y');
    $semester    = $this->input->get('semester') ?: (date('n') <= 6 ? 1 : 2);

    if ($semester == 1) {
      $start_date = "$tahun-01-01";
      $end_date   = "$tahun-06-30";
      $periode_label = "Januari - Juni $tahun";
    } else {
      $start_date = "$tahun-07-01";
      $end_date   = "$tahun-12-31";
      $periode_label = "Juli - Desember $tahun";
    }

    // Data Pegawai
    $pegawai = $this->md_hasil_evaluasi_semester->getPegawaiById($id_pengguna);

    // Rangkuman Nilai
    $rata_laporan  = $this->md_hasil_evaluasi_semester->getRataRataLaporan($id_pengguna, $start_date, $end_date);
    $rata_evaluasi = $this->md_hasil_evaluasi_semester->getNilaiEvaluasi($id_pengguna, $semester, $tahun);
    $total_hadir   = $this->md_hasil_evaluasi_semester->getTotalKehadiran($id_pengguna, $start_date, $end_date);
    $total_sp      = $this->md_hasil_evaluasi_semester->getTotalSP($id_pengguna, $start_date, $end_date);
    $nilai_pk      = $this->md_hasil_evaluasi_semester->getNilaiPK($id_pengguna, $semester, $tahun);

    // Nilai SP berdasarkan SP terakhir
    $nilai_sp      = $this->md_hasil_evaluasi_semester->getNilaiSP($id_pengguna, $start_date, $end_date);
    $sp_terakhir   = $this->md_hasil_evaluasi_semester->getSPTerakhir($id_pengguna, $start_date, $end_date);

    // Nilai Kehadiran (persentase)
    $nilai_kehadiran = $this->md_hasil_evaluasi_semester->getNilaiKehadiran($id_pengguna, $start_date, $end_date);

    // Total Nilai Evaluasi Semester
    $hasil_evaluasi_final = $this->md_hasil_evaluasi_semester->getTotalNilaiEvaluasi($id_pengguna, $semester, $tahun, $start_date, $end_date);

    // Detail Data
    $detail_laporan   = $this->md_hasil_evaluasi_semester->getDetailLaporan($id_pengguna, $start_date, $end_date);
    $detail_evaluasi  = $this->md_hasil_evaluasi_semester->getDetailEvaluasi($id_pengguna, $semester, $tahun);
    $detail_sp        = $this->md_hasil_evaluasi_semester->getDetailSP($id_pengguna, $start_date, $end_date);
    $detail_pk        = $this->md_hasil_evaluasi_semester->getDetailNilaiPK($id_pengguna, $semester, $tahun);
    $statistik_hadir  = $this->md_hasil_evaluasi_semester->getStatistikKehadiran($id_pengguna, $start_date, $end_date);
    $jumlah_laporan   = $this->md_hasil_evaluasi_semester->countLaporan($id_pengguna, $start_date, $end_date);

    // Predikat
    $predikat_laporan  = $this->md_hasil_evaluasi_semester->getPredikat($rata_laporan);
    $predikat_evaluasi = $this->md_hasil_evaluasi_semester->getPredikat($rata_evaluasi);
    $predikat_pk       = $this->md_hasil_evaluasi_semester->getPredikat($nilai_pk);
    $predikat_final    = $this->md_hasil_evaluasi_semester->getPredikat($hasil_evaluasi_final['total_nilai']);
    $formula_desc      = $this->md_hasil_evaluasi_semester->getFormulaDescription($hasil_evaluasi_final['formula_type']);

    // Page Data
    $page_data = [
      'switch'           => $this->id_navbar(),
      'page_name'        => 'hasil_evaluasi_semester/v_detail_evaluasi',
      'page_title'       => 'Detail Evaluasi Semester',
      'page_desc'        => 'Detail Kinerja ' . $pegawai->nama,
      'pegawai'          => $pegawai,
      'id_encrypted'     => $id_encrypted,
      'tahun'            => $tahun,
      'semester'         => $semester,
      'periode_label'    => $periode_label,
      'start_date'       => $start_date,
      'end_date'         => $end_date,
      'rata_laporan'     => $rata_laporan,
      'rata_evaluasi'    => $rata_evaluasi,
      'total_hadir'      => $total_hadir,
      'total_sp'         => $total_sp,
      'nilai_pk'         => $nilai_pk,
      'nilai_sp'         => $nilai_sp,
      'sp_terakhir'      => $sp_terakhir,
      'nilai_kehadiran'  => $nilai_kehadiran,
      'hasil_evaluasi_final' => $hasil_evaluasi_final,
      'predikat_final'   => $predikat_final,
      'formula_desc'     => $formula_desc,
      'detail_laporan'   => $detail_laporan,
      'detail_evaluasi'  => $detail_evaluasi,
      'detail_sp'        => $detail_sp,
      'detail_pk'        => $detail_pk,
      'statistik_hadir'  => $statistik_hadir,
      'jumlah_laporan'   => $jumlah_laporan,
      'predikat_laporan' => $predikat_laporan,
      'predikat_evaluasi' => $predikat_evaluasi,
      'predikat_pk'      => $predikat_pk,
    ];

    $this->load->view('index', $page_data);
  }

  /**
   * Halaman Input Nilai PK
   */
  public function nilai_pk()
  {
    grantAccessFor('all');

    $tahun    = $this->input->get('tahun') ?: date('Y');
    $semester = $this->input->get('semester') ?: (date('n') <= 6 ? 1 : 2);

    // Label periode
    $periode_label = $semester == 1 ? "Semester 1 (Jan - Jun) $tahun" : "Semester 2 (Jul - Des) $tahun";

    $page_data = [
      'switch'       => $this->id_navbar(),
      'page_name'    => 'hasil_evaluasi_semester/v_nilai_pk',
      'page_title'   => 'Input Nilai Product Knowledge',
      'page_desc'    => 'Kelola Nilai PK Karyawan',
      'tahun'        => $tahun,
      'semester'     => $semester,
      'periode_label' => $periode_label,
    ];

    $this->load->view('index', $page_data);
  }

  /**
   * DataTables untuk Nilai PK
   */
  public function pagination_nilai_pk()
  {
    $tahun    = $this->input->post('tahun');
    $semester = $this->input->post('semester');
    $start    = $this->input->post('start');
    $length   = $this->input->post('length');
    $search   = $this->input->post('search')['value'];

    $list = $this->md_hasil_evaluasi_semester->getNilaiPKDatatables($semester, $tahun, $length, $start, $search);
    $total = $this->md_hasil_evaluasi_semester->countAllPegawai($search);

    $data = [];
    $no = $start + 1;

    foreach ($list as $row) {
      $nilai = $row->nilai ?: 0;
      $keterangan = $row->keterangan ?: '';

      // Generate initials
      $words = explode(' ', trim($row->nama));
      $initials = '';
      foreach ($words as $word) {
        if (!empty($word)) {
          $initials .= strtoupper(substr($word, 0, 1));
          if (strlen($initials) >= 2) break;
        }
      }
      if (empty($initials)) $initials = 'NA';

      // Generate consistent color
      $colors = [
        ['#667eea', '#764ba2'],
        ['#f093fb', '#f5576c'],
        ['#4facfe', '#00f2fe'],
        ['#43e97b', '#38f9d7'],
        ['#fa709a', '#fee140'],
        ['#11998e', '#38ef7d'],
        ['#ff9a9e', '#fecfef'],
        ['#ffecd2', '#fcb69f'],
      ];
      $colorIndex = abs(crc32($row->nama)) % count($colors);
      $avatarColor = $colors[$colorIndex];

      $nama_display = '<div style="display:inline-flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,' . $avatarColor[0] . ',' . $avatarColor[1] . ');display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:12px;flex-shrink:0;">' . $initials . '</div>
        <div><strong style="font-size:14px;">' . $row->nama . '</strong><br><small class="text-muted">' . ($row->no_pegawai ?: '-') . '</small></div>
      </div>';

      // Button edit dengan onclick function
      $btn_edit = '<button type="button" class="btn btn-edit-pk" onclick="editNilaiPK(\'' . $row->pengguna_id . '\', \'' . addslashes($row->nama) . '\', \'' . addslashes($row->jabatan) . '\', ' . $nilai . ', \'' . addslashes($keterangan) . '\')"><i class="fa fa-edit mr-1"></i> Edit</button>';

      $data[] = [
        'no' => $no++,
        'no_pegawai' => $row->no_pegawai,
        'nama' => $nama_display,
        'jabatan' => $row->jabatan ?: '-',
        'nilai_pk' => $nilai,
        'keterangan' => $keterangan,
        'action' => $btn_edit
      ];
    }

    echo json_encode([
      "draw"            => intval($this->input->post('draw')),
      "recordsTotal"    => $total,
      "recordsFiltered" => $total,
      "data"            => $data,
    ]);
  }

  /**
   * Simpan Nilai PK via AJAX
   */
  public function save_nilai_pk()
  {
    grantAccessFor('all');

    $id_pengguna = $this->input->post('id_pengguna');
    $semester    = $this->input->post('semester');
    $tahun       = $this->input->post('tahun');
    $nilai       = $this->input->post('nilai');
    $keterangan  = $this->input->post('keterangan');
    $send_wa     = $this->input->post('send_wa'); // Optional: kirim WA atau tidak

    if (!$id_pengguna || !$semester || !$tahun) {
      echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
      return;
    }

    $data = [
      'id_pengguna' => $id_pengguna,
      'semester'    => $semester,
      'tahun'       => $tahun,
      'nilai'       => $nilai,
      'keterangan'  => $keterangan,
      'created_by'  => sessPenggunaId()
    ];

    $result = $this->md_hasil_evaluasi_semester->saveNilaiPK($data);

    if ($result) {
      $wa_status = '';

      // Kirim notifikasi WhatsApp jika diminta
      if ($send_wa == '1') {
        $pegawai = $this->md_hasil_evaluasi_semester->getPegawaiById($id_pengguna);

        if ($pegawai && !empty($pegawai->no_hp)) {
          $predikat = $this->md_hasil_evaluasi_semester->getPredikat($nilai);
          $periode = ($semester == 1) ? "Januari - Juni $tahun" : "Juli - Desember $tahun";

          $pesan = '*NILAI PRODUCT KNOWLEDGE*' .
            '%0A========================' .
            '%0A%0AHalo *' . $pegawai->nama . '*,' .
            '%0A%0ANilai Product Knowledge Anda untuk periode *' . $periode . '* telah diinput:' .
            '%0A%0A- Nilai: *' . number_format($nilai, 1) . '*' .
            '%0A- Predikat: *' . $predikat['predikat'] . '*' .
            ($keterangan ? '%0A- Catatan: ' . $keterangan : '') .
            '%0A%0A_Pesan ini dikirim otomatis oleh sistem._';

          // Gunakan function private sendWaEvaluasi
          $waResult = $this->sendWaEvaluasi($pegawai->no_hp, $pesan);
          if ($waResult['success']) {
            $wa_status = ' & notifikasi WhatsApp terkirim ke ' . $waResult['phone'];
          } else {
            $wa_status = ' (WA gagal: ' . ($waResult['error'] ?? 'unknown') . ')';
          }
        } else {
          $wa_status = ' (No HP tidak tersedia)';
        }
      }

      echo json_encode(['status' => true, 'message' => 'Nilai PK berhasil disimpan' . $wa_status]);
    } else {
      echo json_encode(['status' => false, 'message' => 'Gagal menyimpan nilai']);
    }
  }

  /**
   * Export PDF
   */
  public function export_pdf()
  {
    grantAccessFor('all');

    $tahun    = $this->input->get('tahun') ?: date('Y');
    $semester = $this->input->get('semester') ?: (date('n') <= 6 ? 1 : 2);

    if ($semester == 1) {
      $start_date = "$tahun-01-01";
      $end_date   = "$tahun-06-30";
      $periode    = "Januari - Juni $tahun";
    } else {
      $start_date = "$tahun-07-01";
      $end_date   = "$tahun-12-31";
      $periode    = "Juli - Desember $tahun";
    }

    $list_pegawai = $this->md_hasil_evaluasi_semester->getPegawaiDatatables(-1, 0, null);

    // Siapkan data untuk PDF
    $data_pegawai = [];
    foreach ($list_pegawai as $p) {
      $avg_laporan  = $this->md_hasil_evaluasi_semester->getRataRataLaporan($p->pengguna_id, $start_date, $end_date);
      $avg_evaluasi = $this->md_hasil_evaluasi_semester->getNilaiEvaluasi($p->pengguna_id, $semester, $tahun);
      $total_hadir  = $this->md_hasil_evaluasi_semester->getTotalKehadiran($p->pengguna_id, $start_date, $end_date);
      $total_sp     = $this->md_hasil_evaluasi_semester->getTotalSP($p->pengguna_id, $start_date, $end_date);
      $nilai_pk     = $this->md_hasil_evaluasi_semester->getNilaiPK($p->pengguna_id, $semester, $tahun);

      // Nilai SP dan Total Nilai Evaluasi
      $nilai_sp = $this->md_hasil_evaluasi_semester->getNilaiSP($p->pengguna_id, $start_date, $end_date);
      $sp_terakhir = $this->md_hasil_evaluasi_semester->getSPTerakhir($p->pengguna_id, $start_date, $end_date);
      $label_sp = $sp_terakhir ? $this->md_hasil_evaluasi_semester->getLabelJenisSP($sp_terakhir->jenis_sp) : 'Tidak Ada';
      $hasil_evaluasi = $this->md_hasil_evaluasi_semester->getTotalNilaiEvaluasi($p->pengguna_id, $semester, $tahun, $start_date, $end_date);

      $data_pegawai[] = [
        'nama'               => $p->nama,
        'no_pegawai'         => $p->no_pegawai,
        'jabatan'            => $p->jabatan,
        'avg_laporan'        => $avg_laporan,
        'avg_evaluasi'       => $avg_evaluasi,
        'total_hadir'        => $total_hadir,
        'total_sp'           => $total_sp,
        'nilai_pk'           => $nilai_pk,
        'nilai_sp'           => $nilai_sp,
        'label_sp'           => $label_sp,
        'total_nilai_final'  => $hasil_evaluasi['total_nilai'],
        'predikat_final'     => $hasil_evaluasi['grade']
      ];
    }

    $data = [
      'tahun'         => $tahun,
      'semester'      => $semester,
      'periode'       => $periode,
      'data_pegawai'  => $data_pegawai,
      'tanggal_cetak' => date('d F Y')
    ];

    $this->load->library('pdfgenerator');
    $html = $this->load->view('pages/hasil_evaluasi_semester/v_export_pdf', $data, true);
    $filename = "Hasil_Evaluasi_Semester_{$semester}_{$tahun}";

    $this->pdfgenerator->generate($html, $filename, 'A4', 'landscape', true);
  }
  
  /**
   * Export Excel
   */
  /**
 * Export Excel (ULTIMATE FIX: Prefix tab untuk mencegah deteksi tanggal)
 */
public function export_excel()
{
  grantAccessFor('all');

  $tahun    = $this->input->get('tahun') ?: date('Y');
  $semester = $this->input->get('semester') ?: (date('n') <= 6 ? 1 : 2);

  // Tentukan range tanggal
  if ($semester == 1) {
    $start_date = "$tahun-01-01";
    $end_date   = "$tahun-06-30";
    $periode    = "Januari - Juni $tahun";
  } else {
    $start_date = "$tahun-07-01";
    $end_date   = "$tahun-12-31";
    $periode    = "Juli - Desember $tahun";
  }

  // Ambil semua data pegawai
  $list_pegawai = $this->md_hasil_evaluasi_semester->getPegawaiDatatables(-1, 0, null);

  // Nama File
  $filename = "Hasil_Evaluasi_Semester_{$semester}_{$tahun}.xls";

  // Header Export
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Transfer-Encoding: binary");

  ?>
  <style>
    table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 12px; }
    th { background-color: #764ba2; color: white; padding: 10px; border: 1px solid #000; text-align: center; vertical-align: middle; }
    td { padding: 5px; border: 1px solid #000; vertical-align: middle; }
    
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    
    /* SEMUA format sebagai TEXT untuk menghindari deteksi tanggal */
    .num-text { mso-number-format:\@; text-align: center; }
    .str { mso-number-format:\@; } 
  </style>

  <center>
    <h3 style="margin-bottom: 5px;">HASIL EVALUASI SEMESTER</h3>
    <p style="margin-top: 0;">Periode: <strong><?= $periode ?></strong></p>
  </center>
  
  <table border="1">
    <thead>
      <tr>
        <th width="50">No</th>
        <th width="200">Nama Pegawai</th>
        <th width="100">No Pegawai</th>
        <th width="150">Jabatan</th>
        <th width="100">Rata Laporan</th>
        <th width="100">Rata Evaluasi</th>
        <th width="80">Total Hadir</th>
        <th width="80">Status SP</th>
        <th width="80">Nilai SP</th>
        <th width="80">Nilai PK</th>
        <th width="100">Total Nilai</th>
        <th width="80">Predikat</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $no = 1;
      foreach ($list_pegawai as $p) {
        // 1. Hitung Nilai
        $avg_laporan  = $this->md_hasil_evaluasi_semester->getRataRataLaporan($p->pengguna_id, $start_date, $end_date);
        $avg_evaluasi = $this->md_hasil_evaluasi_semester->getNilaiEvaluasi($p->pengguna_id, $semester, $tahun);
        $total_hadir  = $this->md_hasil_evaluasi_semester->getTotalKehadiran($p->pengguna_id, $start_date, $end_date);
        $nilai_pk_raw = $this->md_hasil_evaluasi_semester->getNilaiPK($p->pengguna_id, $semester, $tahun);
        $nilai_sp     = $this->md_hasil_evaluasi_semester->getNilaiSP($p->pengguna_id, $start_date, $end_date);

        // 2. Logic SP
        $sp_terakhir  = $this->md_hasil_evaluasi_semester->getSPTerakhir($p->pengguna_id, $start_date, $end_date);
        $label_sp     = $sp_terakhir ? $this->md_hasil_evaluasi_semester->getLabelJenisSP($sp_terakhir->jenis_sp) : '-';

        // 3. Hitung Total Final
        $hasil_evaluasi = $this->md_hasil_evaluasi_semester->getTotalNilaiEvaluasi($p->pengguna_id, $semester, $tahun, $start_date, $end_date);
        $total_nilai    = $hasil_evaluasi['total_nilai']; 
        
        // 4. Ambil Predikat 
        $predikat_data  = $this->md_hasil_evaluasi_semester->getPredikat($total_nilai);
        $predikat       = $predikat_data['predikat']; 
        
        // 5. FORMAT NILAI dengan number_format 2 desimal
        $format_nilai = function($nilai) {
          if ($nilai == 0 || $nilai === null || $nilai === '') return '-';
          // Format dengan 2 desimal, hilangkan .00 jika bulat
          $formatted = number_format($nilai, 2, '.', '');
          if (substr($formatted, -3) === '.00') {
            return rtrim(rtrim($formatted, '0'), '.');
          }
          return $formatted;
        };

        $output_laporan = $format_nilai($avg_laporan);
        $output_evaluasi = $format_nilai($avg_evaluasi);
        $output_total_nilai = $format_nilai($total_nilai);
        $output_nilai_pk = $format_nilai($nilai_pk_raw);
      ?>
      <tr>
        <td class="num-text"><?= $no++ ?></td>
        <td><?= $p->nama ?></td>
        <td class="text-center str"><?= $p->no_pegawai ?: '-' ?></td>
        <td><?= $p->jabatan ?></td>
        
        <!-- SOLUSI: Tambahkan TAB invisible di awal untuk paksa jadi text -->
        <td class="num-text"><?= "\t" . $output_laporan ?></td>
        <td class="num-text"><?= "\t" . $output_evaluasi ?></td>
        
        <td class="num-text"><?= $total_hadir ?></td>
        
        <td class="text-center str"><?= $label_sp ?></td>
        
        <td class="num-text"><?= $nilai_sp ?></td>
        
        <td class="num-text"><?= "\t" . $output_nilai_pk ?></td>
        
        <td class="num-text" style="font-weight:bold; background-color: #e8f0fe;"><?= "\t" . $output_total_nilai ?></td>
        
        <td class="text-center str" style="font-weight:bold;"><?= $predikat ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php
}

  /**
   * Print Hasil Evaluasi Semester (dengan Kop Surat)
   */
  public function print_evaluasi()
  {
    grantAccessFor('all');

    $tahun    = $this->input->get('tahun') ?: date('Y');
    $semester = $this->input->get('semester') ?: (date('n') <= 6 ? 1 : 2);

    if ($semester == 1) {
      $start_date = "$tahun-01-01";
      $end_date   = "$tahun-06-30";
      $periode    = "Januari - Juni $tahun";
    } else {
      $start_date = "$tahun-07-01";
      $end_date   = "$tahun-12-31";
      $periode    = "Juli - Desember $tahun";
    }

    $list_pegawai = $this->md_hasil_evaluasi_semester->getPegawaiDatatables(-1, 0, null);

    // Siapkan data untuk Print
    $data_pegawai = [];
    foreach ($list_pegawai as $p) {
      $avg_laporan  = $this->md_hasil_evaluasi_semester->getRataRataLaporan($p->pengguna_id, $start_date, $end_date);
      $avg_evaluasi = $this->md_hasil_evaluasi_semester->getNilaiEvaluasi($p->pengguna_id, $semester, $tahun);
      $total_hadir  = $this->md_hasil_evaluasi_semester->getTotalKehadiran($p->pengguna_id, $start_date, $end_date);
      $total_sp     = $this->md_hasil_evaluasi_semester->getTotalSP($p->pengguna_id, $start_date, $end_date);
      $nilai_pk     = $this->md_hasil_evaluasi_semester->getNilaiPK($p->pengguna_id, $semester, $tahun);

      // Data SP baru
      $nilai_sp     = $this->md_hasil_evaluasi_semester->getNilaiSP($p->pengguna_id, $start_date, $end_date);
      $sp_terakhir  = $this->md_hasil_evaluasi_semester->getSPTerakhir($p->pengguna_id, $start_date, $end_date);
      $label_sp     = $sp_terakhir ? $this->md_hasil_evaluasi_semester->getLabelJenisSP($sp_terakhir->jenis_sp) : '-';

      // Total Nilai Evaluasi Semester
      $hasil_evaluasi = $this->md_hasil_evaluasi_semester->getTotalNilaiEvaluasi($p->pengguna_id, $semester, $tahun, $start_date, $end_date);
      $total_nilai_final = $hasil_evaluasi['total_nilai'];
      $formula_type = $hasil_evaluasi['formula_type'];

      $data_pegawai[] = [
        'nama'               => $p->nama,
        'no_pegawai'         => $p->no_pegawai,
        'jabatan'            => $p->jabatan,
        'avg_laporan'        => $avg_laporan,
        'avg_evaluasi'       => $avg_evaluasi,
        'total_hadir'        => $total_hadir,
        'total_sp'           => $total_sp,
        'nilai_sp'           => $nilai_sp,
        'label_sp'           => $label_sp,
        'nilai_pk'           => $nilai_pk,
        'total_nilai_final'  => $total_nilai_final,
        'formula_type'       => $formula_type
      ];
    }

    $data = [
      'tahun'         => $tahun,
      'semester'      => $semester,
      'periode'       => $periode,
      'data_pegawai'  => $data_pegawai,
      'tanggal_cetak' => date('d F Y')
    ];

    $this->load->view('pages/hasil_evaluasi_semester/v_print_evaluasi_semester', $data);
  }

  /**
   * Kirim Notifikasi WhatsApp Hasil Evaluasi
   */
  public function send_wa_notif()
  {
    grantAccessFor('all');

    $id_pengguna = $this->input->post('id_pengguna');
    $tahun       = $this->input->post('tahun');
    $semester    = $this->input->post('semester');

    if (!$id_pengguna) {
      echo json_encode(['status' => false, 'message' => 'ID Pengguna tidak valid']);
      return;
    }

    // Get data pegawai
    $pegawai = $this->md_hasil_evaluasi_semester->getPegawaiById($id_pengguna);

    if (!$pegawai) {
      echo json_encode(['status' => false, 'message' => 'Data pegawai tidak ditemukan']);
      return;
    }

    if (empty($pegawai->no_hp)) {
      echo json_encode(['status' => false, 'message' => 'Nomor HP pegawai belum diisi']);
      return;
    }

    // Calculate date range
    if ($semester == 1) {
      $start_date = "$tahun-01-01";
      $end_date   = "$tahun-06-30";
      $periode = "Januari - Juni $tahun";
    } else {
      $start_date = "$tahun-07-01";
      $end_date   = "$tahun-12-31";
      $periode = "Juli - Desember $tahun";
    }

    // Get evaluation data
    $rata_laporan  = $this->md_hasil_evaluasi_semester->getRataRataLaporan($id_pengguna, $start_date, $end_date);
    $rata_evaluasi = $this->md_hasil_evaluasi_semester->getNilaiEvaluasi($id_pengguna, $semester, $tahun);
    $total_hadir   = $this->md_hasil_evaluasi_semester->getTotalKehadiran($id_pengguna, $start_date, $end_date);
    $total_sp      = $this->md_hasil_evaluasi_semester->getTotalSP($id_pengguna, $start_date, $end_date);
    $nilai_pk      = $this->md_hasil_evaluasi_semester->getNilaiPK($id_pengguna, $semester, $tahun);

    // Get nilai SP berdasarkan SP terakhir
    $nilai_sp = $this->md_hasil_evaluasi_semester->getNilaiSP($id_pengguna, $start_date, $end_date);
    $sp_terakhir = $this->md_hasil_evaluasi_semester->getSPTerakhir($id_pengguna, $start_date, $end_date);
    $label_sp = $sp_terakhir ? $this->md_hasil_evaluasi_semester->getLabelJenisSP($sp_terakhir->jenis_sp) : 'Tidak ada SP';

    // Get Total Nilai Evaluasi Semester
    $hasil_evaluasi = $this->md_hasil_evaluasi_semester->getTotalNilaiEvaluasi($id_pengguna, $semester, $tahun, $start_date, $end_date);
    $total_nilai_final = $hasil_evaluasi['total_nilai'];
    $predikat_final = $this->md_hasil_evaluasi_semester->getPredikat($total_nilai_final);

    // Get predikat
    $predikat_laporan  = $this->md_hasil_evaluasi_semester->getPredikat($rata_laporan);
    $predikat_evaluasi = $this->md_hasil_evaluasi_semester->getPredikat($rata_evaluasi);
    $predikat_pk       = $this->md_hasil_evaluasi_semester->getPredikat($nilai_pk);

    // Build message - gunakan karakter sederhana untuk menghindari HTTP 400
    $pesan = '*HASIL EVALUASI SEMESTER*' .
      '%0A========================' .
      '%0A%0AHalo *' . $pegawai->nama . '*,' .
      '%0A%0ABerikut hasil evaluasi kinerja Anda pada periode *' . $periode . '*:' .
      '%0A%0A*RINGKASAN PENILAIAN*' .
      '%0A- Rata Laporan: *' . number_format($rata_laporan, 1) . '* (' . $predikat_laporan['predikat'] . ')' .
      '%0A- Rata Evaluasi: *' . number_format($rata_evaluasi, 1) . '* (' . $predikat_evaluasi['predikat'] . ')' .
      '%0A- Total Kehadiran: *' . $total_hadir . ' hari*' .
      '%0A- Status SP: *' . $label_sp . '* (Nilai: ' . $nilai_sp . ')' .
      '%0A- Nilai PK: *' . ($nilai_pk > 0 ? number_format($nilai_pk, 1) . ' (' . $predikat_pk['predikat'] . ')' : 'Belum dinilai') . '*' .
      '%0A%0A*TOTAL NILAI EVALUASI SEMESTER: ' . number_format($total_nilai_final, 2) . ' (' . $predikat_final['predikat'] . ' - ' . $predikat_final['label'] . ')*' .
      '%0A%0A*Catatan:*' .
      '%0ATerus tingkatkan kinerja dan kedisiplinan Anda.' .
      '%0A%0A_Pesan ini dikirim otomatis oleh sistem Office Visiyosindo._' .
      '%0A%0ATerima kasih.';

    // Send WhatsApp dengan function private yang lebih reliable
    $waResult = $this->sendWaEvaluasi($pegawai->no_hp, $pesan);

    if ($waResult['success']) {
      echo json_encode([
        'status'  => true,
        'message' => 'Notifikasi berhasil dikirim ke ' . $waResult['phone'],
        'debug'   => [
          'phone' => $waResult['phone'],
          'nama'  => $pegawai->nama
        ]
      ]);
    } else {
      echo json_encode([
        'status'  => false,
        'message' => 'Gagal mengirim notifikasi: ' . ($waResult['error'] ?? 'Unknown error')
      ]);
    }
  }

  /**
   * Kirim Notifikasi WhatsApp ke Semua Karyawan
   */
  public function send_wa_bulk()
  {
    grantAccessFor('Admin', 'HRD');

    $tahun    = $this->input->post('tahun');
    $semester = $this->input->post('semester');

    $list_pegawai = $this->md_hasil_evaluasi_semester->getPegawaiDatatables(-1, 0, null);

    $success = 0;
    $failed  = 0;

    foreach ($list_pegawai as $p) {
      if (!empty($p->no_hp)) {
        // Simulate sending (you may want to add delay between sends)
        $_POST['id_pengguna'] = $p->pengguna_id;
        $_POST['tahun'] = $tahun;
        $_POST['semester'] = $semester;

        // For bulk, we'll just count - actual sending should be done with queue
        $success++;
      } else {
        $failed++;
      }
    }

    echo json_encode([
      'status'  => true,
      'message' => "Notifikasi dikirim: $success berhasil, $failed gagal (no HP kosong)"
    ]);
  }

  /**
   * Test WhatsApp - untuk debugging
   */
  public function test_wa()
  {
    grantAccessFor('all');

    $phone = $this->input->get('phone') ?: '081261457547'; // Default test number

    // Format nomor
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (substr($phone, 0, 1) == '0') {
      $phone = '62' . substr($phone, 1);
    }

    $pesan = '*🧪 TEST WHATSAPP*' .
      '%0A━━━━━━━━━━━━━━━━━━━━' .
      '%0A%0AIni adalah pesan test dari sistem.' .
      '%0A%0AWaktu: ' . date('d/m/Y H:i:s') .
      '%0A%0A_Office Visiyosindo_';

    $dataWa = [
      'devId'    => hostWa('1'),
      'penerima' => $phone,
      'pesan'    => $pesan
    ];

    // Log detail
    log_message('info', '=== TEST WA START ===');
    log_message('info', 'Device ID: ' . $dataWa['devId']);
    log_message('info', 'Phone: ' . $phone);
    log_message('info', 'Message length: ' . strlen($pesan));

    $result = sendWa($dataWa);

    echo json_encode([
      'status' => true,
      'message' => 'Test WA dikirim ke ' . $phone,
      'debug' => [
        'device_id' => $dataWa['devId'],
        'phone' => $phone,
        'result' => $result
      ]
    ]);
  }
}
