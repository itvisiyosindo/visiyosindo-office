<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Md_hasil_evaluasi_semester extends CI_Model
{
  // ID yang dikecualikan dari evaluasi (misal: admin, direktur, dll)
  private $excluded_ids = [1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 77, 758];

  /**
   * Mendapatkan semua pegawai aktif
   */
  function getBywhereActive()
  {
    $this->db->select('*');
    $this->db->from('pengguna p');
    $this->db->where('p.is_active', 1);

    if (!empty($this->excluded_ids)) {
      $this->db->where_not_in('p.pengguna_id', $this->excluded_ids);
    }

    $this->db->order_by('p.nama', 'ASC');
    return $this->db->get()->result();
  }

  /**
   * Mendapatkan data pegawai untuk DataTables
   */
  function getPegawaiDatatables($limit, $start, $search = null)
  {
    $this->db->select('p.pengguna_id, p.nama, p.no_pegawai, p.jabatan, p.file_foto, p.no_hp');
    $this->db->from('pengguna p');
    $this->db->where('p.is_active', 1);

    if (!empty($this->excluded_ids)) {
      $this->db->where_not_in('p.pengguna_id', $this->excluded_ids);
    }

    if ($search) {
      $this->db->group_start();
      $this->db->like('p.nama', $search);
      $this->db->or_like('p.no_pegawai', $search);
      $this->db->or_like('p.jabatan', $search);
      $this->db->group_end();
    }

    if ($limit != -1) {
      $this->db->limit($limit, $start);
    }

    $this->db->order_by('p.nama', 'ASC');
    return $this->db->get()->result();
  }

  /**
   * Menghitung total pegawai
   */
  function countAllPegawai($search = null)
  {
    $this->db->from('pengguna p');
    $this->db->where('p.is_active', 1);

    if (!empty($this->excluded_ids)) {
      $this->db->where_not_in('p.pengguna_id', $this->excluded_ids);
    }

    if ($search) {
      $this->db->group_start();
      $this->db->like('p.nama', $search);
      $this->db->or_like('p.no_pegawai', $search);
      $this->db->or_like('p.jabatan', $search);
      $this->db->group_end();
    }
    return $this->db->count_all_results();
  }

  /**
   * Mendapatkan detail pegawai berdasarkan ID
   */
  function getPegawaiById($id_pengguna)
  {
    $this->db->select('*');
    $this->db->from('pengguna');
    $this->db->where('pengguna_id', $id_pengguna);
    return $this->db->get()->row();
  }

  /**
   * Menghitung Rata-rata Nilai Laporan Mingguan per Semester
   * Mengambil data dari:
   * 1. nilai_point (nilai laporan per item mingguan)
   * 2. penilaian_umum (penilaian umum mingguan)
   * Kemudian dihitung rata-rata dari keduanya
   */
  public function getRataRataLaporan($id_pengguna, $start_date, $end_date)
  {
    // Array untuk menyimpan nilai per minggu
    $nilai_per_minggu = [];

    // 1. Ambil semua nilai dari nilai_point dalam rentang tanggal
    $this->db->select('date, nilai_a, nilai_b');
    $this->db->from('nilai_point');
    $this->db->where('id_pengguna', $id_pengguna);
    $this->db->where('date >=', $start_date);
    $this->db->where('date <=', $end_date);
    $nilai_points = $this->db->get()->result();

    // Group nilai_point berdasarkan date (minggu)
    $grouped_nilai_point = [];
    foreach ($nilai_points as $np) {
      if (!isset($grouped_nilai_point[$np->date])) {
        $grouped_nilai_point[$np->date] = [
          'total_a' => 0,
          'count_a' => 0,
          'total_b' => 0,
          'count_b' => 0
        ];
      }

      if (!empty($np->nilai_a) && is_numeric($np->nilai_a)) {
        $grouped_nilai_point[$np->date]['total_a'] += floatval($np->nilai_a);
        $grouped_nilai_point[$np->date]['count_a']++;
      }

      if (!empty($np->nilai_b) && is_numeric($np->nilai_b)) {
        $grouped_nilai_point[$np->date]['total_b'] += floatval($np->nilai_b);
        $grouped_nilai_point[$np->date]['count_b']++;
      }
    }

    // 2. Ambil semua penilaian_umum dalam rentang tanggal
    $this->db->select('date, nilaia1, nilaia2, nilaia3, nilaia4, nilaia5, nilaib1, nilaib2, nilaib3, nilaib4, nilaib5');
    $this->db->from('penilaian_umum');
    $this->db->where('id_pengguna', $id_pengguna);
    $this->db->where('date >=', $start_date);
    $this->db->where('date <=', $end_date);
    $penilaian_umums = $this->db->get()->result();

    // 3. Hitung rata-rata per minggu (gabungan nilai_point dan penilaian_umum)
    foreach ($grouped_nilai_point as $date => $data) {
      // Rata-rata nilai_point untuk minggu ini
      $rata_lap_a = ($data['count_a'] > 0) ? ($data['total_a'] / $data['count_a']) : 0;
      $rata_lap_b = ($data['count_b'] > 0) ? ($data['total_b'] / $data['count_b']) : 0;

      // Cari penilaian_umum untuk tanggal yang sama
      $rata_pumum = 0;
      foreach ($penilaian_umums as $pu) {
        if ($pu->date == $date) {
          // Hitung rata-rata nilaia
          $nilai_a_arr = array_filter([
            floatval($pu->nilaia1),
            floatval($pu->nilaia2),
            floatval($pu->nilaia3),
            floatval($pu->nilaia4),
            floatval($pu->nilaia5)
          ], function ($v) {
            return $v > 0;
          });

          // Hitung rata-rata nilaib
          $nilai_b_arr = array_filter([
            floatval($pu->nilaib1),
            floatval($pu->nilaib2),
            floatval($pu->nilaib3),
            floatval($pu->nilaib4),
            floatval($pu->nilaib5)
          ], function ($v) {
            return $v > 0;
          });

          $rata_a = count($nilai_a_arr) > 0 ? array_sum($nilai_a_arr) / count($nilai_a_arr) : 0;
          $rata_b = count($nilai_b_arr) > 0 ? array_sum($nilai_b_arr) / count($nilai_b_arr) : 0;

          if ($rata_a > 0 && $rata_b > 0) {
            $rata_pumum = ($rata_a + $rata_b) / 2;
          } elseif ($rata_b > 0) {
            $rata_pumum = $rata_b;
          } else {
            $rata_pumum = $rata_a;
          }
          break;
        }
      }

      // Hitung nilai laporan mingguan = (rata nilai_point A + rata nilai_point B) / 2
      $nilai_lap_mingguan = 0;
      if ($rata_lap_a > 0 && $rata_lap_b > 0) {
        $nilai_lap_mingguan = ($rata_lap_a + $rata_lap_b) / 2;
      } elseif ($rata_lap_b > 0) {
        $nilai_lap_mingguan = $rata_lap_b;
      } else {
        $nilai_lap_mingguan = $rata_lap_a;
      }

      // Hitung rata-rata akhir untuk minggu ini = (Laporan Mingguan + Penilaian Umum) / 2
      if ($nilai_lap_mingguan > 0 && $rata_pumum > 0) {
        $nilai_per_minggu[] = ($nilai_lap_mingguan + $rata_pumum) / 2;
      } elseif ($nilai_lap_mingguan > 0) {
        $nilai_per_minggu[] = $nilai_lap_mingguan;
      } elseif ($rata_pumum > 0) {
        $nilai_per_minggu[] = $rata_pumum;
      }
    }

    // Jika ada penilaian_umum tapi tidak ada nilai_point, tetap hitung
    foreach ($penilaian_umums as $pu) {
      if (!isset($grouped_nilai_point[$pu->date])) {
        // Hitung rata-rata nilaia
        $nilai_a_arr = array_filter([
          floatval($pu->nilaia1),
          floatval($pu->nilaia2),
          floatval($pu->nilaia3),
          floatval($pu->nilaia4),
          floatval($pu->nilaia5)
        ], function ($v) {
          return $v > 0;
        });

        // Hitung rata-rata nilaib
        $nilai_b_arr = array_filter([
          floatval($pu->nilaib1),
          floatval($pu->nilaib2),
          floatval($pu->nilaib3),
          floatval($pu->nilaib4),
          floatval($pu->nilaib5)
        ], function ($v) {
          return $v > 0;
        });

        $rata_a = count($nilai_a_arr) > 0 ? array_sum($nilai_a_arr) / count($nilai_a_arr) : 0;
        $rata_b = count($nilai_b_arr) > 0 ? array_sum($nilai_b_arr) / count($nilai_b_arr) : 0;

        if ($rata_a > 0 || $rata_b > 0) {
          if ($rata_a > 0 && $rata_b > 0) {
            $nilai_per_minggu[] = ($rata_a + $rata_b) / 2;
          } elseif ($rata_b > 0) {
            $nilai_per_minggu[] = $rata_b;
          } else {
            $nilai_per_minggu[] = $rata_a;
          }
        }
      }
    }

    // 4. Hitung rata-rata dari semua minggu
    if (count($nilai_per_minggu) > 0) {
      return array_sum($nilai_per_minggu) / count($nilai_per_minggu);
    }

    return 0;
  }

  /**
   * Mendapatkan detail laporan mingguan
   */
  public function getDetailLaporan($id_pengguna, $start_date, $end_date)
  {
    $this->db->select('l.*, jd.deskripsi as nama_job');
    $this->db->from('laporan l');
    $this->db->join('jobdesc_detail jd', 'jd.id = l.id_job', 'left');
    $this->db->where('l.id_pengaju', $id_pengguna);
    $this->db->where('l.tanggal >=', $start_date);
    $this->db->where('l.tanggal <=', $end_date);
    $this->db->where('l.status !=', 3);
    $this->db->order_by('l.tanggal', 'DESC');
    return $this->db->get()->result();
  }

  /**
   * Menghitung jumlah laporan
   */
  public function countLaporan($id_pengguna, $start_date, $end_date)
  {
    $this->db->from('laporan');
    $this->db->where('id_pengaju', $id_pengguna);
    $this->db->where('tanggal >=', $start_date);
    $this->db->where('tanggal <=', $end_date);
    $this->db->where('status !=', 3);
    return $this->db->count_all_results();
  }

  /**
   * Menghitung Rata-rata Nilai Evaluasi Semester
   */
  public function getNilaiEvaluasi($id_pengguna, $smt, $tahun)
  {
    $this->db->select('id');
    $this->db->from('evaluasi');
    $this->db->where('id_pengguna', $id_pengguna);
    $this->db->where('smt', $smt);
    $this->db->where('tahun', $tahun);
    $this->db->where('jenis_evaluasi', 1);

    $evaluasi = $this->db->get()->row();

    if (!$evaluasi) {
      return 0;
    }

    $this->db->select('nilaia, nilaib, nilaic, nilaid, nilaie, nilaif');
    $this->db->from('evaluasi_detail');
    $this->db->where('id_evaluasi', $evaluasi->id);
    $details = $this->db->get()->result();

    if (empty($details)) {
      return 0;
    }

    $total_score_all_jobdesc = 0;
    $count_jobdesc = 0;

    foreach ($details as $row) {
      $sum_row = 0;
      $count_penilai = 0;
      $columns = ['nilaia', 'nilaib', 'nilaic', 'nilaid', 'nilaie', 'nilaif'];

      foreach ($columns as $col) {
        $val = floatval($row->$col);
        if ($val > 0) {
          $sum_row += $val;
          $count_penilai++;
        }
      }

      if ($count_penilai > 0) {
        $avg_row = $sum_row / $count_penilai;
        $total_score_all_jobdesc += $avg_row;
        $count_jobdesc++;
      }
    }

    if ($count_jobdesc > 0) {
      return $total_score_all_jobdesc / $count_jobdesc;
    }

    return 0;
  }

  /**
   * Mendapatkan detail evaluasi semester
   */
  public function getDetailEvaluasi($id_pengguna, $smt, $tahun)
  {
    $this->db->select('e.*, ed.*, jd.deskripsi as nama_job');
    $this->db->from('evaluasi e');
    $this->db->join('evaluasi_detail ed', 'ed.id_evaluasi = e.id', 'left');
    $this->db->join('jobdesc_detail jd', 'jd.id = ed.id_jobdesc', 'left');
    $this->db->where('e.id_pengguna', $id_pengguna);
    $this->db->where('e.smt', $smt);
    $this->db->where('e.tahun', $tahun);
    $this->db->where('e.jenis_evaluasi', 1);
    return $this->db->get()->result();
  }

  /**
   * Menghitung Total Kehadiran
   * Termasuk type_absen 'masuk' (tepat_waktu, tepat waktu, terlambat) dan type_absen 'cuti'
   */
  public function getTotalKehadiran($id_pengguna, $start_date, $end_date)
  {
    // Hitung absensi masuk (tepat waktu + terlambat)
    $this->db->where('pengguna_id', $id_pengguna);
    $this->db->where('data_created >=', $start_date . ' 00:00:00');
    $this->db->where('data_created <=', $end_date . ' 23:59:59');
    $this->db->group_start();
    $this->db->where('status_absen', 'tepat_waktu');
    $this->db->or_where('status_absen', 'tepat waktu');
    $this->db->or_where('status_absen', 'terlambat');
    $this->db->group_end();
    $this->db->where('type_absen', 'masuk');
    $count_masuk = $this->db->count_all_results('absensi');

    // Hitung absensi cuti
    $this->db->where('pengguna_id', $id_pengguna);
    $this->db->where('data_created >=', $start_date . ' 00:00:00');
    $this->db->where('data_created <=', $end_date . ' 23:59:59');
    $this->db->where('type_absen', 'cuti');
    $count_cuti = $this->db->count_all_results('absensi');

    return $count_masuk + $count_cuti;
  }

  /**
   * Menghitung persentase kehadiran berdasarkan hari kerja per semester
   * 1 semester = 6 bulan, 1 bulan = ~20 hari kerja (Senin-Jumat, kecuali libur)
   * Total hari kerja per semester = 6 bulan x 20 hari = 120 hari
   * 
   * Contoh perhitungan:
   * - Hadir 120 hari = (120/120) x 100 = 100
   * - Hadir 113 hari = (113/120) x 100 = 94.17
   * - Hadir 111 hari = (111/120) x 100 = 92.5
   */
  public function getNilaiKehadiran($id_pengguna, $start_date, $end_date)
  {
    $total_hadir = $this->getTotalKehadiran($id_pengguna, $start_date, $end_date);

    // Get target workdays from config, default to 120
    $total_hari_kerja = intval($this->getConfig('batas_kehadiran') ?: 120);
    if ($total_hari_kerja <= 0) {
      $total_hari_kerja = 120;
    }

    // Hitung nilai kehadiran: (total_hadir / batas) * 100
    // Maksimal 100 (jika hadir melebihi atau sama dengan batas)
    if ($total_hadir >= $total_hari_kerja) {
      $nilai_kehadiran = 100;
    } else {
      $nilai_kehadiran = ($total_hadir / $total_hari_kerja) * 100;
    }

    return min(100, round($nilai_kehadiran, 2)); // Maksimal 100, dibulatkan 2 desimal
  }

  /**
   * Mendapatkan detail kehadiran
   */
  public function getDetailKehadiran($id_pengguna, $start_date, $end_date)
  {
    $this->db->select('*');
    $this->db->from('absensi');
    $this->db->where('pengguna_id', $id_pengguna);
    $this->db->where('data_created >=', $start_date . ' 00:00:00');
    $this->db->where('data_created <=', $end_date . ' 23:59:59');
    $this->db->order_by('data_created', 'DESC');
    return $this->db->get()->result();
  }

  /**
   * Menghitung statistik kehadiran
   */
  public function getStatistikKehadiran($id_pengguna, $start_date, $end_date)
  {
    $stats = [
      'hadir' => 0,
      'terlambat' => 0,
      'izin' => 0,
      'sakit' => 0
    ];

    // Hadir tepat waktu
    $this->db->where('pengguna_id', $id_pengguna);
    $this->db->where('data_created >=', $start_date . ' 00:00:00');
    $this->db->where('data_created <=', $end_date . ' 23:59:59');
    $this->db->group_start();
    $this->db->where('status_absen', 'tepat_waktu');
    $this->db->or_where('status_absen', 'tepat waktu');
    $this->db->group_end();
    $this->db->where('type_absen', 'masuk');
    $stats['hadir'] = $this->db->count_all_results('absensi');

    // Terlambat
    $this->db->where('pengguna_id', $id_pengguna);
    $this->db->where('data_created >=', $start_date . ' 00:00:00');
    $this->db->where('data_created <=', $end_date . ' 23:59:59');
    $this->db->where('status_absen', 'terlambat');
    $this->db->where('type_absen', 'masuk');
    $stats['terlambat'] = $this->db->count_all_results('absensi');

    // Izin
    $this->db->where('pengguna_id', $id_pengguna);
    $this->db->where('data_created >=', $start_date . ' 00:00:00');
    $this->db->where('data_created <=', $end_date . ' 23:59:59');
    $this->db->where('status_absen', 'izin');
    $stats['izin'] = $this->db->count_all_results('absensi');

    // Sakit
    $this->db->where('pengguna_id', $id_pengguna);
    $this->db->where('data_created >=', $start_date . ' 00:00:00');
    $this->db->where('data_created <=', $end_date . ' 23:59:59');
    $this->db->where('status_absen', 'sakit');
    $stats['sakit'] = $this->db->count_all_results('absensi');

    return $stats;
  }

  /**
   * Menghitung Total SP (Surat Peringatan)
   * PENTING: Kolom 'nama' berisi id_pengguna yang dikenakan SP, bukan nama string
   */
  public function getTotalSP($id_pengguna, $start_date, $end_date)
  {
    if (!$this->db->table_exists('surat_peringatan')) return 0;

    // Kolom 'nama' menyimpan id_pengguna yang dikenakan SP
    $this->db->where('nama', (string)$id_pengguna);
    $this->db->where('tgl_pengajuan >=', $start_date);
    $this->db->where('tgl_pengajuan <=', $end_date);

    return $this->db->count_all_results('surat_peringatan');
  }

  /**
   * Mendapatkan SP Terakhir yang dikenakan ke pengguna dalam periode
   * PENTING: Kolom 'nama' berisi id_pengguna yang dikenakan SP
   * Return: object dengan jenis_sp terakhir
   */
  public function getSPTerakhir($id_pengguna, $start_date, $end_date)
  {
    if (!$this->db->table_exists('surat_peringatan')) return null;

    $this->db->select('*');
    $this->db->from('surat_peringatan');
    // Kolom 'nama' menyimpan id_pengguna yang dikenakan SP
    $this->db->where('nama', (string)$id_pengguna);
    $this->db->where('tgl_pengajuan >=', $start_date);
    $this->db->where('tgl_pengajuan <=', $end_date);
    $this->db->order_by('tgl_pengajuan', 'DESC');
    $this->db->limit(1);

    return $this->db->get()->row();
  }

  /**
   * Mendapatkan nilai SP berdasarkan jenis_sp terakhir
   * Rumus: Tidak ada SP = 100, SP1 = 80, SP2 = 60, SP3 = 40
   */
  public function getNilaiSP($id_pengguna, $start_date, $end_date)
  {
    $sp_terakhir = $this->getSPTerakhir($id_pengguna, $start_date, $end_date);

    if (!$sp_terakhir) {
      return 100; // Tidak ada SP = 100
    }

    $jenis_sp = strtolower(trim($sp_terakhir->jenis_sp));

    // Mapping jenis_sp ke level SP
    // SP 1 / Pertama = 80
    // SP 2 / Kedua = 60
    // SP 3 / Ketiga = 40
    if (strpos($jenis_sp, 'pertama') !== false || $jenis_sp === '1' || strpos($jenis_sp, 'sp 1') !== false || strpos($jenis_sp, 'sp1') !== false) {
      return 80;
    } elseif (strpos($jenis_sp, 'kedua') !== false || $jenis_sp === '2' || strpos($jenis_sp, 'sp 2') !== false || strpos($jenis_sp, 'sp2') !== false || $jenis_sp === 'ii') {
      return 60;
    } elseif (strpos($jenis_sp, 'ketiga') !== false || $jenis_sp === '3' || strpos($jenis_sp, 'sp 3') !== false || strpos($jenis_sp, 'sp3') !== false) {
      return 40;
    }

    // Default jika tidak dikenali, cek angka di dalam string
    if (preg_match('/(\d+)/', $jenis_sp, $matches)) {
      $level = (int)$matches[1];
      if ($level == 1) return 80;
      if ($level == 2) return 60;
      if ($level >= 3) return 40;
    }

    return 80; // Default SP Pertama jika tidak dikenali
  }

  /**
   * Mendapatkan label jenis SP untuk ditampilkan
   */
  public function getLabelJenisSP($jenis_sp)
  {
    if (!$jenis_sp) return '-';

    $jenis = strtolower(trim($jenis_sp));

    if (strpos($jenis, 'pertama') !== false || $jenis === '1') {
      return 'SP 1';
    } elseif (strpos($jenis, 'kedua') !== false || $jenis === '2' || $jenis === 'ii') {
      return 'SP 2';
    } elseif (strpos($jenis, 'ketiga') !== false || $jenis === '3') {
      return 'SP 3';
    }

    return strtoupper($jenis_sp);
  }

  /**
   * Mendapatkan detail SP
   * PENTING: Kolom 'nama' berisi id_pengguna yang dikenakan SP
   */
  public function getDetailSP($id_pengguna, $start_date, $end_date)
  {
    if (!$this->db->table_exists('surat_peringatan')) return [];

    $this->db->select('*');
    $this->db->from('surat_peringatan');
    // Kolom 'nama' menyimpan id_pengguna yang dikenakan SP
    $this->db->where('nama', (string)$id_pengguna);
    $this->db->where('tgl_pengajuan >=', $start_date);
    $this->db->where('tgl_pengajuan <=', $end_date);
    $this->db->order_by('tgl_pengajuan', 'DESC');
    return $this->db->get()->result();
  }

  /**
   * Mendapatkan Nilai Product Knowledge
   */
  public function getNilaiPK($id_pengguna, $smt, $tahun)
  {
    if (!$this->db->table_exists('nilai_pk')) return 0;

    $this->db->select('nilai');
    $this->db->from('nilai_pk');
    $this->db->where('id_pengguna', $id_pengguna);
    $this->db->where('semester', $smt);
    $this->db->where('tahun', $tahun);

    $res = $this->db->get()->row();
    return $res ? floatval($res->nilai) : 0;
  }

  /**
   * Mendapatkan detail Nilai PK
   */
  public function getDetailNilaiPK($id_pengguna, $smt, $tahun)
  {
    if (!$this->db->table_exists('nilai_pk')) return null;

    $this->db->select('np.*, p.nama as created_by_nama');
    $this->db->from('nilai_pk np');
    $this->db->join('pengguna p', 'p.pengguna_id = np.created_by', 'left');
    $this->db->where('np.id_pengguna', $id_pengguna);
    $this->db->where('np.semester', $smt);
    $this->db->where('np.tahun', $tahun);

    return $this->db->get()->row();
  }

  /**
   * Menyimpan atau update Nilai PK
   */
  public function saveNilaiPK($data)
  {
    // Pastikan tabel ada
    if (!$this->db->table_exists('nilai_pk')) {
      // Buat tabel jika belum ada
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `nilai_pk` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `id_pengguna` int(11) NOT NULL,
          `semester` int(1) NOT NULL,
          `tahun` int(4) NOT NULL,
          `nilai` decimal(5,2) DEFAULT 0,
          `keterangan` text,
          `created_by` int(11) DEFAULT NULL,
          `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_pengguna_semester_tahun` (`id_pengguna`, `semester`, `tahun`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ");
    }

    $this->db->where('id_pengguna', $data['id_pengguna']);
    $this->db->where('semester', $data['semester']);
    $this->db->where('tahun', $data['tahun']);
    $existing = $this->db->get('nilai_pk')->row();

    if ($existing) {
      $this->db->where('id', $existing->id);
      return $this->db->update('nilai_pk', [
        'nilai' => $data['nilai'],
        'keterangan' => $data['keterangan'],
        'created_by' => $data['created_by']
      ]);
    } else {
      return $this->db->insert('nilai_pk', $data);
    }
  }

  /**
   * Mendapatkan predikat berdasarkan nilai
   */
  public function getPredikat($nilai)
  {
    if ($nilai >= 90) return ['predikat' => 'A', 'label' => 'Sangat Baik', 'class' => 'success'];
    if ($nilai >= 80) return ['predikat' => 'B', 'label' => 'Baik', 'class' => 'primary'];
    if ($nilai >= 70) return ['predikat' => 'C', 'label' => 'Cukup', 'class' => 'warning'];
    if ($nilai >= 60) return ['predikat' => 'D', 'label' => 'Kurang', 'class' => 'danger'];
    return ['predikat' => 'E', 'label' => 'Sangat Kurang', 'class' => 'dark'];
  }

  /**
   * Menghitung Total Nilai Evaluasi Semester berdasarkan jabatan
   * 
   * Rumus berdasarkan jabatan:
   * 1. Karyawan Biasa: 35% Evaluasi + 20% SP + 15% PK + 15% Kehadiran + 15% Laporan
   * 2. HR & Legal / General Affairs: 30% Evaluasi + 20% SP + 20% Kehadiran + 30% Laporan
   * 3. Security: 60% Evaluasi + 20% Kehadiran + 20% SP
   * 4. Helper: 25% Evaluasi + 25% SP + 25% Kehadiran + 25% PK
   * 
   * Contoh perhitungan manual (HR & Legal / GA):
   * - Rata Laporan: 95.0 → 30% = 0.30 x 95.0 = 28.5
   * - Rata Evaluasi: 94.9 → 30% = 0.30 x 94.9 = 28.47
   * - Nilai SP: 100 → 20% = 0.20 x 100 = 20
   * - Nilai Kehadiran: (111/120)x100 = 92.5 → 20% = 0.20 x 92.5 = 18.5
   * - Total = 28.5 + 28.47 + 20 + 18.5 = 95.47
   */
  public function getTotalNilaiEvaluasi($id_pengguna, $semester, $tahun, $start_date, $end_date)
  {
    // Ambil data pegawai untuk mengetahui jabatan
    $pegawai = $this->getPegawaiById($id_pengguna);

    // Normalisasi jabatan: lowercase dan ganti semua karakter non-huruf dengan spasi
    $jabatan_raw = $pegawai->jabatan ?? '';
    // Ganti semua karakter yang bukan huruf a-z dengan spasi, lalu lowercase
    $jabatan = strtolower(preg_replace('/[^a-zA-Z]+/', ' ', $jabatan_raw));
    $jabatan = trim($jabatan);

    // Ambil semua komponen nilai
    $nilai_evaluasi = $this->getNilaiEvaluasi($id_pengguna, $semester, $tahun);
    $nilai_sp = $this->getNilaiSP($id_pengguna, $start_date, $end_date);
    $nilai_pk = $this->getNilaiPK($id_pengguna, $semester, $tahun);
    $nilai_kehadiran = $this->getNilaiKehadiran($id_pengguna, $start_date, $end_date);

    // Ambil nilai laporan berdasarkan mode konfigurasi (manual atau sistem)
    $nilai_laporan = $this->getNilaiLaporanByMode($id_pengguna, $semester, $tahun, $start_date, $end_date);

    $total_nilai = 0;
    $formula_type = 'karyawan_biasa'; // Default
    $detail_hitung = []; // Untuk debugging/detail

    // Cek jabatan dan hitung berdasarkan rumus yang sesuai
    if (strpos($jabatan, 'hr') !== false || strpos($jabatan, 'legal') !== false) {
      // HR & Legal: 30% Evaluasi + 30% SP + 20% Kehadiran + 20% Laporan
      $komponen_evaluasi = round($nilai_evaluasi * 0.30, 2);
      $komponen_sp = round($nilai_sp * 0.30, 2);
      $komponen_kehadiran = round($nilai_kehadiran * 0.20, 2);
      $komponen_laporan = round($nilai_laporan * 0.20, 2);

      $total_nilai = $komponen_evaluasi + $komponen_sp + $komponen_kehadiran + $komponen_laporan;
      $formula_type = 'hr_legal';

      $detail_hitung = [
        'evaluasi_30' => $komponen_evaluasi,
        'sp_30' => $komponen_sp,
        'kehadiran_20' => $komponen_kehadiran,
        'laporan_20' => $komponen_laporan
      ];
    } elseif (strpos($jabatan, 'general affair') !== false || strpos($jabatan, 'general affairs') !== false || strpos($jabatan, 'ga') !== false) {
      // General Affairs: 30% Evaluasi + 30% SP + 20% Kehadiran + 20% Laporan (sama dengan HR & Legal)
      $komponen_evaluasi = round($nilai_evaluasi * 0.30, 2);
      $komponen_sp = round($nilai_sp * 0.30, 2);
      $komponen_kehadiran = round($nilai_kehadiran * 0.20, 2);
      $komponen_laporan = round($nilai_laporan * 0.20, 2);

      $total_nilai = $komponen_evaluasi + $komponen_sp + $komponen_kehadiran + $komponen_laporan;
      $formula_type = 'general_affairs';

      $detail_hitung = [
        'evaluasi_30' => $komponen_evaluasi,
        'sp_30' => $komponen_sp,
        'kehadiran_20' => $komponen_kehadiran,
        'laporan_20' => $komponen_laporan
      ];
    } elseif (strpos($jabatan, 'security') !== false || strpos($jabatan, 'satpam') !== false || strpos($jabatan, 'office boy') !== false || strpos($jabatan, 'ob') !== false) {
      // Security: 85% Evaluasi + 8% Kehadiran + 7% SP
      $komponen_evaluasi = round($nilai_evaluasi * 0.85, 2);
      $komponen_kehadiran = round($nilai_kehadiran * 0.08, 2);
      $komponen_sp = round($nilai_sp * 0.07, 2);

      $total_nilai = $komponen_evaluasi + $komponen_kehadiran + $komponen_sp;
      $formula_type = 'security';

      $detail_hitung = [
        'evaluasi_85' => $komponen_evaluasi,
        'kehadiran_8' => $komponen_kehadiran,
        'sp_7' => $komponen_sp
      ];
    } elseif (strpos($jabatan, 'helper') !== false) {
      // Helper: 85% Evaluasi + 5% SP + 5% PK + 5% Kehadiran
      $komponen_evaluasi = round($nilai_evaluasi * 0.85, 2);
      $komponen_sp = round($nilai_sp * 0.05, 2);
      $komponen_pk = round($nilai_pk * 0.05, 2);
      $komponen_kehadiran = round($nilai_kehadiran * 0.05, 2);

      $total_nilai = $komponen_evaluasi + $komponen_sp + $komponen_pk + $komponen_kehadiran;
      $formula_type = 'helper';

      $detail_hitung = [
        'evaluasi_85' => $komponen_evaluasi,
        'sp_5' => $komponen_sp,
        'pk_5' => $komponen_pk,
        'kehadiran_5' => $komponen_kehadiran
      ];
    } else {
      // Karyawan Biasa: 50% Evaluasi + 3% SP + 10% PK + 2% Kehadiran + 35% Laporan
      $komponen_evaluasi = round($nilai_evaluasi * 0.50, 2);
      $komponen_sp = round($nilai_sp * 0.03, 2);
      $komponen_pk = round($nilai_pk * 0.10, 2);
      $komponen_kehadiran = round($nilai_kehadiran * 0.02, 2);
      $komponen_laporan = round($nilai_laporan * 0.35, 2);

      $total_nilai = $komponen_evaluasi + $komponen_sp + $komponen_pk + $komponen_kehadiran + $komponen_laporan;
      $formula_type = 'karyawan_biasa';

      $detail_hitung = [
        'evaluasi_50' => $komponen_evaluasi,
        'sp_3' => $komponen_sp,
        'pk_10' => $komponen_pk,
        'kehadiran_2' => $komponen_kehadiran,
        'laporan_35' => $komponen_laporan
      ];
    }

    return [
      'total_nilai' => round($total_nilai, 2),
      'formula_type' => $formula_type,
      'detail_hitung' => $detail_hitung,
      'grade' => $this->getPredikat($total_nilai)['predikat'],
      'komponen' => [
        'evaluasi' => round($nilai_evaluasi, 2),
        'sp' => $nilai_sp,
        'pk' => round($nilai_pk, 2),
        'kehadiran' => round($nilai_kehadiran, 2),
        'kehadiran_hari' => $this->getTotalKehadiran($id_pengguna, $start_date, $end_date),
        'laporan' => round($nilai_laporan, 2)
      ]
    ];
  }

  /**
   * Mendapatkan deskripsi formula berdasarkan jabatan
   */
  public function getFormulaDescription($formula_type)
  {
    switch ($formula_type) {
      case 'hr_legal':
        return '30% Evaluasi + 30% SP + 20% Kehadiran + 20% Laporan';
      case 'general_affairs':
        return '30% Evaluasi + 30% SP + 20% Kehadiran + 20% Laporan';
      case 'security':
        return '85% Evaluasi + 8% Kehadiran + 7% SP';
      case 'helper':
        return '85% Evaluasi + 5% SP + 5% PK + 5% Kehadiran';
      default:
        return '50% Evaluasi + 3% SP + 10% PK + 2% Kehadiran + 35% Laporan';
    }
  }

  /**
   * Mendapatkan semua data Nilai PK untuk DataTables
   */
  public function getNilaiPKDatatables($semester, $tahun, $limit, $start, $search = null)
  {
    $this->db->select('p.pengguna_id, p.nama, p.no_pegawai, p.jabatan, np.nilai, np.keterangan, np.updated_at');
    $this->db->from('pengguna p');
    $this->db->join('nilai_pk np', 'np.id_pengguna = p.pengguna_id AND np.semester = ' . intval($semester) . ' AND np.tahun = ' . intval($tahun), 'left');
    $this->db->where('p.is_active', 1);

    if (!empty($this->excluded_ids)) {
      $this->db->where_not_in('p.pengguna_id', $this->excluded_ids);
    }

    if ($search) {
      $this->db->group_start();
      $this->db->like('p.nama', $search);
      $this->db->or_like('p.no_pegawai', $search);
      $this->db->group_end();
    }

    if ($limit != -1) {
      $this->db->limit($limit, $start);
    }

    $this->db->order_by('p.nama', 'ASC');
    return $this->db->get()->result();
  }

  // ============================================================
  // FITUR KONFIGURASI & INPUT MANUAL RATA-RATA LAPORAN
  // ============================================================

  /**
   * Mendapatkan nilai konfigurasi evaluasi semester
   */
  public function getConfig($key)
  {
    if (!$this->db->table_exists('evaluasi_semester_config')) {
      // Buat tabel jika belum ada
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `evaluasi_semester_config` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `config_key` varchar(100) NOT NULL,
          `config_value` varchar(255) NOT NULL,
          `description` text,
          `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `idx_config_key` (`config_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ");
      // Insert default config
      $this->db->insert('evaluasi_semester_config', [
        'config_key' => 'mode_rata_laporan',
        'config_value' => 'sistem',
        'description' => 'Mode perhitungan rata-rata laporan: sistem (otomatis) atau manual (input)'
      ]);
    }

    $this->db->select('config_value');
    $this->db->from('evaluasi_semester_config');
    $this->db->where('config_key', $key);
    $result = $this->db->get()->row();

    return $result ? $result->config_value : null;
  }

  /**
   * Menyimpan nilai konfigurasi evaluasi semester
   */
  public function setConfig($key, $value, $description = null)
  {
    if (!$this->db->table_exists('evaluasi_semester_config')) {
      $this->getConfig($key); // Ini akan membuat tabel
    }

    $this->db->where('config_key', $key);
    $existing = $this->db->get('evaluasi_semester_config')->row();

    if ($existing) {
      $this->db->where('config_key', $key);
      return $this->db->update('evaluasi_semester_config', [
        'config_value' => $value
      ]);
    } else {
      return $this->db->insert('evaluasi_semester_config', [
        'config_key' => $key,
        'config_value' => $value,
        'description' => $description
      ]);
    }
  }

  /**
   * Cek apakah mode input laporan adalah manual
   */
  public function isModeLaporanManual()
  {
    $mode = $this->getConfig('mode_rata_laporan');
    return ($mode === 'manual');
  }

  /**
   * Mendapatkan Nilai Laporan Manual (mirip dengan getNilaiPK)
   */
  public function getNilaiLaporanManual($id_pengguna, $smt, $tahun)
  {
    if (!$this->db->table_exists('nilai_laporan_manual')) return 0;

    $this->db->select('nilai');
    $this->db->from('nilai_laporan_manual');
    $this->db->where('id_pengguna', $id_pengguna);
    $this->db->where('semester', $smt);
    $this->db->where('tahun', $tahun);

    $res = $this->db->get()->row();
    return $res ? floatval($res->nilai) : 0;
  }

  /**
   * Mendapatkan detail Nilai Laporan Manual
   */
  public function getDetailNilaiLaporanManual($id_pengguna, $smt, $tahun)
  {
    if (!$this->db->table_exists('nilai_laporan_manual')) return null;

    $this->db->select('nlm.*, p.nama as created_by_nama');
    $this->db->from('nilai_laporan_manual nlm');
    $this->db->join('pengguna p', 'p.pengguna_id = nlm.created_by', 'left');
    $this->db->where('nlm.id_pengguna', $id_pengguna);
    $this->db->where('nlm.semester', $smt);
    $this->db->where('nlm.tahun', $tahun);

    return $this->db->get()->row();
  }

  /**
   * Menyimpan atau update Nilai Laporan Manual
   */
  public function saveNilaiLaporanManual($data)
  {
    // Pastikan tabel ada
    if (!$this->db->table_exists('nilai_laporan_manual')) {
      $this->db->query("
        CREATE TABLE IF NOT EXISTS `nilai_laporan_manual` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `id_pengguna` int(11) NOT NULL,
          `semester` int(1) NOT NULL,
          `tahun` int(4) NOT NULL,
          `nilai` decimal(5,2) DEFAULT 0,
          `keterangan` text,
          `created_by` int(11) DEFAULT NULL,
          `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `idx_pengguna_semester_tahun` (`id_pengguna`, `semester`, `tahun`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ");
    }

    $this->db->where('id_pengguna', $data['id_pengguna']);
    $this->db->where('semester', $data['semester']);
    $this->db->where('tahun', $data['tahun']);
    $existing = $this->db->get('nilai_laporan_manual')->row();

    if ($existing) {
      $this->db->where('id', $existing->id);
      return $this->db->update('nilai_laporan_manual', [
        'nilai' => $data['nilai'],
        'keterangan' => $data['keterangan'],
        'created_by' => $data['created_by']
      ]);
    } else {
      return $this->db->insert('nilai_laporan_manual', $data);
    }
  }

  /**
   * Mendapatkan semua data Nilai Laporan Manual untuk DataTables
   */
  public function getNilaiLaporanManualDatatables($semester, $tahun, $limit, $start, $search = null)
  {
    $this->db->select('p.pengguna_id, p.nama, p.no_pegawai, p.jabatan, nlm.nilai, nlm.keterangan, nlm.updated_at');
    $this->db->from('pengguna p');
    $this->db->join('nilai_laporan_manual nlm', 'nlm.id_pengguna = p.pengguna_id AND nlm.semester = ' . intval($semester) . ' AND nlm.tahun = ' . intval($tahun), 'left');
    $this->db->where('p.is_active', 1);

    if (!empty($this->excluded_ids)) {
      $this->db->where_not_in('p.pengguna_id', $this->excluded_ids);
    }

    if ($search) {
      $this->db->group_start();
      $this->db->like('p.nama', $search);
      $this->db->or_like('p.no_pegawai', $search);
      $this->db->group_end();
    }

    if ($limit != -1) {
      $this->db->limit($limit, $start);
    }

    $this->db->order_by('p.nama', 'ASC');
    return $this->db->get()->result();
  }

  /**
   * Mendapatkan Rata-rata Laporan berdasarkan mode konfigurasi
   * Jika mode = 'manual', ambil dari tabel nilai_laporan_manual
   * Jika mode = 'sistem', hitung dari sistem (getRataRataLaporan)
   */
  public function getNilaiLaporanByMode($id_pengguna, $semester, $tahun, $start_date, $end_date)
  {
    if ($this->isModeLaporanManual()) {
      return $this->getNilaiLaporanManual($id_pengguna, $semester, $tahun);
    } else {
      return $this->getRataRataLaporan($id_pengguna, $start_date, $end_date);
    }
  }
}
