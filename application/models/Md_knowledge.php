<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_knowledge extends CI_Model
{

    function add($data)
    {
        $this->db->insert('product_knowledge', $data);
    }

    public function saveOrUpdateNilai($id_pengguna, $tahun, $bulan, $nilai)
    {
        $bulanDate = sprintf('%04d-%02d-01', (int)$tahun, (int)$bulan);
        $checkPk = $this->db->select('id')
            ->from('product_knowledge')
            ->where('id_pengguna', (int)$id_pengguna)
            ->where('bulan', $bulanDate)
            ->get()
            ->row();

        $now = date('Y-m-d H:i:s');
        $nilaiFloat = (float)$nilai;

        if ($checkPk) {
            $this->db->where('id', $checkPk->id)->update('product_knowledge', [
                'nilai' => $nilaiFloat
            ]);
            return 'updated';
        } else {
            $this->db->insert('product_knowledge', [
                'id_pengguna' => (int)$id_pengguna,
                'nilai'       => $nilaiFloat,
                'bulan'       => $bulanDate,
                'created_at'  => $now
            ]);
            return 'inserted';
        }
    }

    public function getAllKnowledge($tahun = null)
    {
        if ($tahun === null) {
            $tahun = date('Y'); // default: tahun sekarang
        }

        // Ambil seluruh karyawan aktif
        $this->db->select('p.pengguna_id, p.nama, p.npp, p.jabatan, d.nama AS nama_divisi');
        $this->db->from('pengguna p');
        $this->db->join('divisi d', 'd.id_divisi = p.id_divisi', 'left');
        $this->db->where('p.is_active', 1);
        $this->db->where_not_in('p.pengguna_id', [1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 749]);
        $this->db->order_by('p.nama', 'ASC');
        $pegawaiList = $this->db->get()->result();

        $data = [];
        foreach ($pegawaiList as $p) {
            $isExempt = $this->isExemptFromPk($p->nama, $p->jabatan, $p->nama_divisi);
            $data[$p->pengguna_id] = [
                'id'        => $p->pengguna_id,
                'nama'      => $p->nama,
                'npp'       => $p->npp,
                'jabatan'   => $p->jabatan,
                'divisi'    => $p->nama_divisi,
                'is_exempt' => $isExempt,
                'nilai'     => array_fill(1, 12, null),
                'avg_smt1'  => null,
                'avg_smt2'  => null,
                'avg_tahun' => null
            ];
        }

        // Ambil nilai dari tabel product_knowledge untuk tahun terkait
        $this->db->select('
            p.pengguna_id,
            p.nama,
            p.npp,
            p.jabatan,
            MONTH(k.bulan) AS bulan,
            k.nilai,
            k.id
        ');
        $this->db->from('product_knowledge k');
        $this->db->join('pengguna p', 'p.pengguna_id = k.id_pengguna');
        $this->db->where('YEAR(k.bulan)', $tahun);
        $query = $this->db->get();
        $result = $query->result();

        // Olah data menjadi format per pengguna dengan nilai per bulan
        foreach ($result as $row) {
            $uid = (int)$row->pengguna_id;
            if (!isset($data[$uid])) {
                $data[$uid] = [
                    'id'        => $uid,
                    'nama'      => $row->nama,
                    'npp'       => $row->npp,
                    'jabatan'   => $row->jabatan,
                    'divisi'    => '',
                    'is_exempt' => $this->isExemptFromPk($row->nama, $row->jabatan),
                    'nilai'     => array_fill(1, 12, null),
                    'avg_smt1'  => null,
                    'avg_smt2'  => null,
                    'avg_tahun' => null
                ];
            }
            $data[$uid]['nilai'][(int)$row->bulan] = (float)$row->nilai;
        }

        // Hitung rata-rata per semester & tahun
        foreach ($data as $uid => &$item) {
            // Semester 1 (Bulan 1 - 6)
            $s1 = [];
            for ($m = 1; $m <= 6; $m++) {
                if ($item['nilai'][$m] !== null) {
                    $s1[] = (float)$item['nilai'][$m];
                }
            }
            $item['avg_smt1'] = !empty($s1) ? round(array_sum($s1) / count($s1), 1) : null;

            // Semester 2 (Bulan 7 - 12)
            $s2 = [];
            for ($m = 7; $m <= 12; $m++) {
                if ($item['nilai'][$m] !== null) {
                    $s2[] = (float)$item['nilai'][$m];
                }
            }
            $item['avg_smt2'] = !empty($s2) ? round(array_sum($s2) / count($s2), 1) : null;

            // Tahunan (Bulan 1 - 12)
            $all = array_merge($s1, $s2);
            $item['avg_tahun'] = !empty($all) ? round(array_sum($all) / count($all), 1) : null;
        }
        unset($item);

        return $data;
    }

    public function importExcelData($sheetData, $defaultTahun = null)
    {
        if (empty($defaultTahun)) {
            $defaultTahun = (int)date('Y');
        }

        // 1. Ambil data seluruh pengguna untuk pencocokan (ID, NPP, Nama)
        $allUsers = $this->db->select('pengguna_id, npp, nama')->from('pengguna')->get()->result();
        $userById = [];
        $userByNpp = [];
        $userByNama = [];

        foreach ($allUsers as $u) {
            $uid = (int)$u->pengguna_id;
            $userById[$uid] = $uid;
            if (!empty($u->npp)) {
                $userByNpp[strtolower(trim((string)$u->npp))] = $uid;
            }
            if (!empty($u->nama)) {
                $cleaned = preg_replace('/[^a-z0-9]/', '', strtolower(trim((string)$u->nama)));
                $userByNama[$cleaned] = $uid;
            }
        }

        // 2. Deteksi baris header dan mapping kolom
        $headerRowIdx = null;
        $colMap = [
            'id'     => null,
            'npp'    => null,
            'nama'   => null,
            'tahun'  => null,
            'bulan'  => null,
            'nilai'  => null,
            'months' => [] // 1 => colIdx, ..., 12 => colIdx
        ];

        $monthAliases = [
            1  => ['jan', 'januari', 'january', '1'],
            2  => ['feb', 'februari', 'february', '2'],
            3  => ['mar', 'maret', 'march', '3'],
            4  => ['apr', 'april', '4'],
            5  => ['mei', 'may', '5'],
            6  => ['jun', 'juni', 'june', '6'],
            7  => ['jul', 'juli', 'july', '7'],
            8  => ['agu', 'agustus', 'august', 'aug', '8'],
            9  => ['sep', 'september', '9'],
            10 => ['okt', 'oktober', 'october', 'oct', '10'],
            11 => ['nov', 'november', '11'],
            12 => ['des', 'desember', 'december', 'dec', '12'],
        ];

        foreach ($sheetData as $rIdx => $row) {
            if (!is_array($row)) continue;
            $foundCols = 0;
            foreach ($row as $cIdx => $cellVal) {
                $cellStr = strtolower(trim((string)$cellVal));
                if (empty($cellStr)) continue;

                if (strpos($cellStr, 'id pegawai') !== false || strpos($cellStr, 'id_pengguna') !== false || $cellStr === 'id') {
                    $foundCols++;
                } else if ($cellStr === 'npp') {
                    $foundCols++;
                } else if (strpos($cellStr, 'nama') !== false || strpos($cellStr, 'karyawan') !== false || strpos($cellStr, 'pegawai') !== false) {
                    $foundCols++;
                }
                foreach ($monthAliases as $mNum => $aliases) {
                    foreach ($aliases as $al) {
                        if (strpos($cellStr, $al) === 0 || $cellStr === $al) {
                            $foundCols++;
                            break;
                        }
                    }
                }
            }

            if ($foundCols >= 3) {
                $headerRowIdx = $rIdx;
                break;
            }
        }

        if ($headerRowIdx === null) {
            $headerRowIdx = 0;
        }

        // Mapping index kolom dari baris header
        $headerRow = $sheetData[$headerRowIdx];
        foreach ($headerRow as $cIdx => $cellVal) {
            $cellStr = strtolower(trim((string)$cellVal));
            if (empty($cellStr)) continue;

            if (strpos($cellStr, 'id pegawai') !== false || strpos($cellStr, 'id_pengguna') !== false || $cellStr === 'id') {
                $colMap['id'] = $cIdx;
            } else if ($cellStr === 'npp') {
                $colMap['npp'] = $cIdx;
            } else if (strpos($cellStr, 'nama') !== false || strpos($cellStr, 'karyawan') !== false || strpos($cellStr, 'pegawai') !== false) {
                $colMap['nama'] = $cIdx;
            } else if (strpos($cellStr, 'tahun') !== false || strpos($cellStr, 'year') !== false) {
                $colMap['tahun'] = $cIdx;
            } else if ($cellStr === 'bulan' || $cellStr === 'month') {
                $colMap['bulan'] = $cIdx;
            } else if (strpos($cellStr, 'nilai') !== false || strpos($cellStr, 'skor') !== false || strpos($cellStr, 'score') !== false) {
                $colMap['nilai'] = $cIdx;
            }

            // Month columns detection
            foreach ($monthAliases as $mNum => $aliases) {
                if (isset($colMap['months'][$mNum])) continue;
                foreach ($aliases as $al) {
                    if (preg_match('/^' . preg_quote($al, '/') . '(\b|\s|\()/i', $cellStr)) {
                        $colMap['months'][$mNum] = $cIdx;
                        break;
                    }
                }
            }
        }

        $totalSaved = 0;
        $warnings = [];

        // 3. Loop baris data setelah header
        for ($i = $headerRowIdx + 1; $i < count($sheetData); $i++) {
            $row = $sheetData[$i];
            if (!is_array($row) || empty(array_filter($row, function($v) { return $v !== null && trim((string)$v) !== ''; }))) {
                continue;
            }

            // Tentukan ID Pengguna
            $userId = null;
            if ($colMap['id'] !== null && isset($row[$colMap['id']]) && is_numeric($row[$colMap['id']])) {
                $candId = (int)$row[$colMap['id']];
                if (isset($userById[$candId])) {
                    $userId = $candId;
                }
            }

            if (!$userId && $colMap['npp'] !== null && !empty($row[$colMap['npp']])) {
                $candNpp = strtolower(trim((string)$row[$colMap['npp']]));
                if (isset($userByNpp[$candNpp])) {
                    $userId = $userByNpp[$candNpp];
                }
            }

            if (!$userId && $colMap['nama'] !== null && !empty($row[$colMap['nama']])) {
                $candNama = preg_replace('/[^a-z0-9]/', '', strtolower(trim((string)$row[$colMap['nama']])));
                if (isset($userByNama[$candNama])) {
                    $userId = $userByNama[$candNama];
                } else {
                    foreach ($userByNama as $clNama => $uid) {
                        if (strpos($clNama, $candNama) !== false || strpos($candNama, $clNama) !== false) {
                            $userId = $uid;
                            break;
                        }
                    }
                }
            }

            if (!$userId) {
                $namaPreview = isset($row[$colMap['nama']]) ? $row[$colMap['nama']] : (isset($row[3]) ? $row[3] : 'Baris ' . ($i + 1));
                $warnings[] = "Karyawan tidak teridentifikasi: {$namaPreview} (Baris " . ($i + 1) . ")";
                continue;
            }

            // Tentukan Tahun
            $rowTahun = $defaultTahun;
            if ($colMap['tahun'] !== null && isset($row[$colMap['tahun']]) && is_numeric($row[$colMap['tahun']])) {
                $rowTahun = (int)$row[$colMap['tahun']];
            }

            // A. Format Matriks (Bulan 1 - 12 per kolom)
            if (!empty($colMap['months'])) {
                foreach ($colMap['months'] as $mNum => $cIdx) {
                    if (!isset($row[$cIdx])) continue;
                    $valStr = trim((string)$row[$cIdx]);
                    if ($valStr === '' || $valStr === '-') continue;

                    $valClean = str_replace(',', '.', $valStr);
                    if (is_numeric($valClean)) {
                        $score = max(0, min(100, (float)$valClean));
                        $this->saveOrUpdateNilai($userId, $rowTahun, $mNum, $score);
                        $totalSaved++;
                    }
                }
            }
            // B. Format Baris Tunggal (kolom bulan & nilai)
            else if ($colMap['bulan'] !== null && $colMap['nilai'] !== null) {
                $bulanVal = trim((string)$row[$colMap['bulan']]);
                $nilaiVal = trim((string)$row[$colMap['nilai']]);

                $mNum = null;
                if (is_numeric($bulanVal) && (int)$bulanVal >= 1 && (int)$bulanVal <= 12) {
                    $mNum = (int)$bulanVal;
                } else {
                    $bulanStr = strtolower($bulanVal);
                    foreach ($monthAliases as $mn => $aliases) {
                        foreach ($aliases as $al) {
                            if (strpos($bulanStr, $al) === 0) {
                                $mNum = $mn;
                                break 2;
                            }
                        }
                    }
                }

                if ($mNum && is_numeric(str_replace(',', '.', $nilaiVal))) {
                    $score = max(0, min(100, (float)str_replace(',', '.', $nilaiVal)));
                    $this->saveOrUpdateNilai($userId, $rowTahun, $mNum, $score);
                    $totalSaved++;
                }
            }
        }

        return [
            'status'      => 'success',
            'total_saved' => $totalSaved,
            'warnings'    => $warnings
        ];
    }



    function getAllDetail($id)
    {
        $this->db->order_by('bulan', 'DESC');
        return $this->datatables
            ->select('
				id,
                id_pengguna,
				nilai,
				bulan,
				created_at
            ')
            ->from('product_knowledge')
            ->where('id_pengguna', $id)
            ->generate();
    }

    function getById($id)
    {
        return $this->db->get_where('product_knowledge p', array('p.id' => $id))->result();
    }

    /* =========================================================================
       MODUL KUIS PRODUCT KNOWLEDGE (AUTO-MIGRATION, KUIS, SOAL & PESERTA)
       ========================================================================= */

    public function auto_migrate()
    {
        // 1. Tabel Master Kuis
        $this->db->query("CREATE TABLE IF NOT EXISTS `pk_kuis` (
            `id_kuis` INT(11) NOT NULL AUTO_INCREMENT,
            `judul_kuis` VARCHAR(255) NOT NULL,
            `deskripsi` TEXT NULL,
            `bulan` TINYINT(2) NOT NULL DEFAULT 1,
            `tahun` INT(4) NOT NULL DEFAULT 2026,
            `durasi_menit` INT(11) NOT NULL DEFAULT 30,
            `waktu_buka` DATETIME NULL,
            `waktu_tutup` DATETIME NULL,
            `status` ENUM('draft', 'aktif', 'selesai') NOT NULL DEFAULT 'aktif',
            `created_by` INT(11) NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id_kuis`),
            KEY `idx_periode` (`tahun`, `bulan`),
            KEY `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Migration kolom waktu_buka & waktu_tutup jika tabel sudah ada sebelumnya
        if ($this->db->table_exists('pk_kuis')) {
            $fields = $this->db->list_fields('pk_kuis');
            if (!in_array('waktu_buka', $fields)) {
                $this->db->query("ALTER TABLE `pk_kuis` ADD COLUMN `waktu_buka` DATETIME NULL AFTER `durasi_menit`");
            }
            if (!in_array('waktu_tutup', $fields)) {
                $this->db->query("ALTER TABLE `pk_kuis` ADD COLUMN `waktu_tutup` DATETIME NULL AFTER `waktu_buka`");
            }
        }

        // 2. Tabel Soal Pilihan Ganda (A, B, C, D)
        $this->db->query("CREATE TABLE IF NOT EXISTS `pk_soal` (
            `id_soal` INT(11) NOT NULL AUTO_INCREMENT,
            `id_kuis` INT(11) NOT NULL,
            `nomor_urut` INT(11) NOT NULL DEFAULT 1,
            `pertanyaan` TEXT NOT NULL,
            `pilihan_a` TEXT NOT NULL,
            `pilihan_b` TEXT NOT NULL,
            `pilihan_c` TEXT NOT NULL,
            `pilihan_d` TEXT NOT NULL,
            `kunci_jawaban` VARCHAR(5) NOT NULL DEFAULT 'A',
            `pembahasan` TEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_soal`),
            KEY `idx_kuis` (`id_kuis`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // 3. Tabel Peserta & Riwayat Jawaban Kuis
        $this->db->query("CREATE TABLE IF NOT EXISTS `pk_peserta` (
            `id_peserta` INT(11) NOT NULL AUTO_INCREMENT,
            `id_kuis` INT(11) NOT NULL,
            `id_pengguna` INT(11) NOT NULL,
            `waktu_mulai` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `waktu_selesai` DATETIME NULL,
            `durasi_detik` INT(11) NOT NULL DEFAULT 0,
            `total_soal` INT(11) NOT NULL DEFAULT 0,
            `total_benar` INT(11) NOT NULL DEFAULT 0,
            `total_salah` INT(11) NOT NULL DEFAULT 0,
            `skor_akhir` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            `status_pengerjaan` ENUM('sedang_mengerjakan', 'selesai') NOT NULL DEFAULT 'sedang_mengerjakan',
            `jawaban_json` LONGTEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_peserta`),
            UNIQUE KEY `uniq_kuis_pengguna` (`id_kuis`, `id_pengguna`),
            KEY `idx_kuis_status` (`id_kuis`, `status_pengerjaan`),
            KEY `idx_pengguna` (`id_pengguna`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public function getKuisList($tahun = null, $bulan = null, $onlyActive = false)
    {
        $this->auto_migrate();
        $this->db->select('k.*, p.nama AS pembuat, 
            (SELECT COUNT(*) FROM pk_soal s WHERE s.id_kuis = k.id_kuis) AS total_soal,
            (SELECT COUNT(*) FROM pk_peserta ps WHERE ps.id_kuis = k.id_kuis AND ps.status_pengerjaan = "selesai") AS total_selesai')
            ->from('pk_kuis k')
            ->join('pengguna p', 'p.pengguna_id = k.created_by', 'left')
            ->where('k.is_deleted', 0);

        if (!empty($tahun)) {
            $this->db->where('k.tahun', (int)$tahun);
        }
        if (!empty($bulan)) {
            $this->db->where('k.bulan', (int)$bulan);
        }
        if ($onlyActive) {
            $this->db->where('k.status', 'aktif');
        }

        return $this->db->order_by('k.id_kuis', 'DESC')->get()->result();
    }

    public function getKuisById($id_kuis)
    {
        $this->auto_migrate();
        return $this->db->select('k.*, p.nama AS pembuat,
            (SELECT COUNT(*) FROM pk_soal s WHERE s.id_kuis = k.id_kuis) AS total_soal,
            (SELECT COUNT(*) FROM pk_peserta ps WHERE ps.id_kuis = k.id_kuis AND ps.status_pengerjaan = "selesai") AS total_selesai')
            ->from('pk_kuis k')
            ->join('pengguna p', 'p.pengguna_id = k.created_by', 'left')
            ->where('k.id_kuis', (int)$id_kuis)
            ->where('k.is_deleted', 0)
            ->get()
            ->row();
    }

    public function getSoalByKuis($id_kuis)
    {
        $this->auto_migrate();
        return $this->db->from('pk_soal')
            ->where('id_kuis', (int)$id_kuis)
            ->order_by('nomor_urut', 'ASC')
            ->order_by('id_soal', 'ASC')
            ->get()
            ->result();
    }

    public function getPesertaKuis($id_kuis, $id_pengguna = null)
    {
        $this->auto_migrate();
        $this->db->select('ps.*, p.nama, p.jabatan, d.nama AS nama_divisi')
            ->from('pk_peserta ps')
            ->join('pengguna p', 'p.pengguna_id = ps.id_pengguna', 'left')
            ->join('divisi d', 'd.id_divisi = p.id_divisi', 'left')
            ->where('ps.id_kuis', (int)$id_kuis);

        if ($id_pengguna !== null) {
            $this->db->where('ps.id_pengguna', (int)$id_pengguna);
            return $this->db->get()->row();
        }

        return $this->db->order_by('ps.skor_akhir', 'DESC')->get()->result();
    }

    public function submitJawabanKuis($id_kuis, $id_pengguna, $jawaban_user)
    {
        $this->auto_migrate();
        $soalList = $this->getSoalByKuis($id_kuis);
        $totalSoal = count($soalList);
        if ($totalSoal === 0) {
            return ['status' => 'error', 'msg' => 'Kuis ini belum memiliki soal.'];
        }

        $kuis = $this->getKuisById($id_kuis);
        if (!$kuis) {
            return ['status' => 'error', 'msg' => 'Data kuis tidak ditemukan.'];
        }

        // Cek apakah peserta sudah pernah submit selesai
        $existing = $this->getPesertaKuis($id_kuis, $id_pengguna);
        if ($existing && $existing->status_pengerjaan === 'selesai') {
            return [
                'status' => 'error',
                'msg'    => 'Anda sudah menyelesaikan kuis ini sebelumnya dengan skor ' . $existing->skor_akhir . '. Tidak dapat mengulang kuis.',
                'peserta' => $existing
            ];
        }

        $totalBenar = 0;
        $detailAudit = [];

        foreach ($soalList as $s) {
            $userAns = isset($jawaban_user[$s->id_soal]) ? strtoupper(trim((string)$jawaban_user[$s->id_soal])) : '';
            $kunci = strtoupper(trim((string)$s->kunci_jawaban));
            $isBenar = ($userAns === $kunci && $userAns !== '');
            if ($isBenar) {
                $totalBenar++;
            }
            $detailAudit[$s->id_soal] = [
                'jawaban_user' => $userAns,
                'kunci'        => $kunci,
                'is_benar'     => $isBenar
            ];
        }

        $totalSalah = $totalSoal - $totalBenar;
        $skorAkhir  = round(($totalBenar / $totalSoal) * 100, 2);

        $now = date('Y-m-d H:i:s');
        $waktuMulai = ($existing && !empty($existing->waktu_mulai)) ? $existing->waktu_mulai : $now;
        $durasiDetik = max(0, strtotime($now) - strtotime($waktuMulai));

        $dataPeserta = [
            'id_kuis'           => (int)$id_kuis,
            'id_pengguna'       => (int)$id_pengguna,
            'waktu_selesai'     => $now,
            'durasi_detik'      => $durasiDetik,
            'total_soal'        => $totalSoal,
            'total_benar'       => $totalBenar,
            'total_salah'       => $totalSalah,
            'skor_akhir'        => $skorAkhir,
            'status_pengerjaan' => 'selesai',
            'jawaban_json'      => json_encode($detailAudit, JSON_UNESCAPED_UNICODE)
        ];

        if ($existing) {
            $this->db->where('id_peserta', $existing->id_peserta)->update('pk_peserta', $dataPeserta);
            $idPeserta = $existing->id_peserta;
        } else {
            $dataPeserta['waktu_mulai'] = $now;
            $this->db->insert('pk_peserta', $dataPeserta);
            $idPeserta = $this->db->insert_id();
        }

        // Sinkronisasi otomatis ke tabel `product_knowledge`
        $this->saveOrUpdateNilai($id_pengguna, $kuis->tahun, $kuis->bulan, $skorAkhir);

        return [
            'status'       => 'success',
            'id_peserta'   => $idPeserta,
            'skor_akhir'   => $skorAkhir,
            'total_soal'   => $totalSoal,
            'total_benar'  => $totalBenar,
            'total_salah'  => $totalSalah,
            'durasi_menit' => round($durasiDetik / 60, 1),
            'kuis'         => $kuis
        ];
    }

    /**
     * Cek apakah karyawan dikecualikan (bebas) dari kewajiban Product Knowledge:
     * - HR (Human Resources / Personalia)
     * - GA (General Affairs / Umum)
     * - Security (Satpam / Keamanan)
     * - Pak Afrianto (Direktur / Manajemen)
     */
    public function isExemptFromPk($nama, $jabatan = '', $nama_divisi = '')
    {
        $str = strtolower(trim((string)$nama . ' ' . (string)$jabatan . ' ' . (string)$nama_divisi));
        // Pak Afrianto
        if (strpos($str, 'afrianto') !== false) {
            return true;
        }
        // Security / Keamanan
        if (strpos($str, 'security') !== false || strpos($str, 'satpam') !== false || strpos($str, 'keamanan') !== false) {
            return true;
        }
        // General Affairs (GA) / Bagian Umum
        if (strpos($str, 'general affair') !== false || preg_match('/\bga\b/', $str) || strpos($str, 'ga /') !== false || strpos($str, 'ga &') !== false || strpos($str, 'umum') !== false) {
            return true;
        }
        // Human Resources (HR / HRD / Personalia)
        if (strpos($str, 'human resource') !== false || preg_match('/\bhr\b/', $str) || preg_match('/\bhrd\b/', $str) || strpos($str, 'personalia') !== false) {
            return true;
        }
        return false;
    }
}
