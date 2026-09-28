<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_dashboard extends CI_Model
{

    function get_dashboard_count()
    {
        $sql = "
        -- Anggota Terverifikasi 
            SELECT
                count(*) as total, 
                'Anggota Terverifikasi' as 'jenis_data',
                'Terverifikasi' as nama_data_1,
                null as nama_data_2,
                null as nama_data_3
            FROM anggota 
            WHERE verifikasi = 1 and status = 1 
    
        UNION
    
        -- Anggota Belum Verifikasi
            SELECT 
                count(*) as total,
                'Anggota Belum Verifikasi' as 'jenis_data',
                'Belum Verifikasi' as nama_data_1, 
                null as nama_data_2, 
                null as nama_data_3
            FROM anggota
            WHERE verifikasi is null and status = 1 
        
        UNION
        
        -- Anggota Pendukung 
            SELECT 
                count(*) as total, 
                'Anggota Pendukung' as 'jenis_data',
                'Pendukung' as nama_data_1, 
                null as nama_data_2, 
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE k.kelas_id IN (1,2) and a.status = 1 and verifikasi = 1
        
        UNION
        
        -- Anggota Penggerak
            SELECT 
                count(*) as total, 
                'Anggota Penggerak' as 'jenis_data',
                'Penggerak' as nama_data_1, 
                null as nama_data_2, 
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE k.kelas_id IN (3,4) and a.status = 1 and verifikasi = 1
        
        UNION 
        
        -- Anggota Pelopor
            SELECT 
                count(*) as total, 
                'Anggota Pelopor' as 'jenis_data',
                'Pelopor' as nama_data_1, 
                null as nama_data_2, 
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE k.kelas_id IN (5,6,7) and a.status = 1 and verifikasi = 1

        UNION 

        -- Total Anggota per Jenjang per Gender
            SELECT 
                count(*) as total,
                'Anggota Per Kelas Per Gender' as 'jenis_data',
                k.nama_kelas as nama_data_1,
                jenis_kelamin as nama_data_2,
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE a.status = 1 and a.verifikasi = 1
            GROUP BY k.nama_kelas, a.jenis_kelamin

        UNION 

        -- Total Grup Per Jenjang & Jenis Kelamin
            SELECT 
                count(*) as total, 
                'Total Grup' as 'jenis_data',
                k.nama_kelas as nama_data_1,
                jenis_kelamin as nama_data_2,
                null as nama_data_3
            FROM grup
            JOIN kelas k ON k.kelas_id = grup.kelas_id
            WHERE grup.status = 1
            group by k.kelas,grup.jenis_kelamin	

        UNION

        -- Total Anggota per Jenjang per Gender
            SELECT 
                count(*) as total,
                'Pembelajar Per Kelas Per Gender' as 'jenis_data',
                k.nama_kelas as nama_data_1,
                jenis_kelamin as nama_data_2,
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE a.status = 1 and a.verifikasi = 1
            GROUP BY k.nama_kelas, a.jenis_kelamin

        UNION 

        -- Anggota Pendukung 
            SELECT 
                count(*) as total, 
                'Anggota Pendukung Per Jenis Kelamin' as 'jenis_data',
                jenis_kelamin as nama_data_2,
                null as nama_data_2, 
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE k.kelas_id IN (1,2) and a.status = 1 and verifikasi = 1
            GROUP BY a.jenis_kelamin
            
        UNION

        -- Anggota Penggerak
            SELECT 
                count(*) as total, 
                'Anggota Penggerak Per Jenis Kelamin' as 'jenis_data',
                jenis_kelamin as nama_data_2,
                null as nama_data_2, 
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE k.kelas_id IN (3,4) and a.status = 1 and verifikasi = 1
            GROUP BY a.jenis_kelamin
            
        UNION 

        -- Anggota Pelopor
            SELECT 
                count(*) as total, 
                'Anggota Pelopor Per Jenis Kelamin' as 'jenis_data',
                jenis_kelamin as nama_data_2,
                null as nama_data_2, 
                null as nama_data_3
            FROM anggota a
            JOIN riwayat_kelas rk ON rk.riwayatkelas_id = a.latest_riwayatkelas_id
            JOIN kelas k ON k.kelas_id = rk.kelas_id
            WHERE k.kelas_id IN (5,6,7) and a.status = 1 and verifikasi = 1
            GROUP BY a.jenis_kelamin
            ORDER BY jenis_data desc
        
        ";
        return $this->db->query($sql)->result();
    }



    public function getSPLatest($id)
    {
        $this->db->from('surat_peringatan');
        $this->db->where('nama', $id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getTestById($id, $yearMonth)
    {
        $this->db->from('product_knowledge');
        $this->db->where('id_pengguna', $id);
        $this->db->like('created_at', $yearMonth, 'after'); // cocokkan awal string
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    public function getHariKerja($yearMonth)
    {
        $this->db->from('laporan_config');
        $this->db->like('created_at', $yearMonth, 'after'); // cocokkan awal string
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }

    //Rata Rata nilai Perbulan
    public function getNilaiData($id, $month)
    {
        $this->db->select('
                            id as id_lap,
                            nilai, 
                            nilai_b,
                            tanggal, 
                            nilai_pencapaian_a,
                            nilai_pencapaian_b,
                            jenis,
                            status
                        ');
        $this->db->from('laporan');
        $this->db->where('id_pengaju', $id);
        $this->db->like('tanggal', $month, 'after');
        $this->db->where('status !=', 3);

        return $this->db->get()->result();
    }

    public function getNilaiPencapaian($id, $month)
    {
        $this->db->select('
                            id as id_lap,
                            nilai, 
                            nilai_b,
                            tanggal, 
                            nilai_pencapaian_a,
                            nilai_pencapaian_b,
                            jenis,
                            status,
                            pencapaian
                        ');
        $this->db->from('laporan');
        $this->db->where('id_pengaju', $id);
        $this->db->like('tanggal', $month, 'after');
        $this->db->where('pencapaian', 2);

        return $this->db->get()->result();
    }

    //Nilai Per Minggu
    public function getNilaiDataPerWeek($id, $start_date, $end_date)
    {
        $this->db->select('
            id as id_lap,
            nilai, 
            nilai_b,
            tanggal, 
            nilai_pencapaian_a,
            nilai_pencapaian_b,
            jenis,
            status
        ');
        $this->db->from('laporan');
        $this->db->where('id_pengaju', $id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->where('status !=', 3);

        return $this->db->get()->result();
    }


    public function getNilaiPencapaianPerWeek($id, $start_date, $end_date)
    {
        $this->db->select('
            id as id_lap,
            nilai, 
            nilai_b,
            tanggal, 
            nilai_pencapaian_a,
            nilai_pencapaian_b,
            jenis,
            status
        ');
        $this->db->from('laporan');
        $this->db->where('id_pengaju', $id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->where('pencapaian', 2);

        return $this->db->get()->result();
    }




    public function getNilaiNewPerWeek($id, $start_date, $end_date)
    {
        $this->db->select('
            id as id_lap,
            nilai_a, 
            nilai_b,
            date
        ');
        $this->db->from('nilai_point');
        $this->db->where('id_pengguna', $id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);

        return $this->db->get()->result();
    }

    public function getNilaiLaporan($pengguna_id, $start_date, $end_date)
    {
        $this->db->select('AVG((nilai_a + nilai_b) / 2) AS rata_rata');
        $this->db->from('nilai_point');
        $this->db->where('id_pengguna', $pengguna_id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);

        $query = $this->db->get()->row();
        return $query ? round($query->rata_rata, 2) : null;
    }



    public function getPenilauanUmumPerWeek($id, $start_date, $end_date)
    {
        $this->db->select('
            id as id_lap,
            nilaia1, 
            nilaia2, 
            nilaia3,
            nilaia4,
            nilaia5,
            nilaib1,
            nilaib2,
            nilaib3,
            nilaib4,
            nilaib5,
            date
        ');
        $this->db->from('penilaian_umum');
        $this->db->where('id_pengguna', $id);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);

        return $this->db->get()->result();
    }

    public function getNilaiPenilaianUmum($id_pengguna, $start_date, $end_date)
    {
        $this->db->select('
            AVG(nilaia1) AS a1,
            AVG(nilaia2) AS a2,
            AVG(nilaia3) AS a3,
            AVG(nilaia4) AS a4,
            AVG(nilaia5) AS a5,
            AVG(nilaib1) AS b1,
            AVG(nilaib2) AS b2,
            AVG(nilaib3) AS b3,
            AVG(nilaib4) AS b4,
            AVG(nilaib5) AS b5
        ');
        $this->db->from('penilaian_umum');
        $this->db->where('id_pengguna', $id_pengguna);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);

        $result = $this->db->get()->row();

        // Hitung rata-rata dari semua nilai yang tersedia
        if ($result) {
            $total = (
                $result->a1 + $result->a2 + $result->a3 + $result->a4 + $result->a5 +
                $result->b1 + $result->b2 + $result->b3 + $result->b4 + $result->b5
            );
            $rata_rata = round($total / 10, 2);
            return $rata_rata;
        }

        return 0; // default jika tidak ada data
    }



    public function getPencapaianPerWeek($id, $start_date, $end_date)
    {
        $this->db->select('
            id as id_lap,
            nilai_a, 
            nilai_b,
            tanggal
        ');
        $this->db->from('pencapaian');
        $this->db->where('id_pengguna', $id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);

        return $this->db->get()->result();
    }

    public function getNilaiPencapaianAdm($pengguna_id, $start_date, $end_date)
    {
        $this->db->select('AVG((nilai_a + nilai_b) / 2) AS rata_rata');
        $this->db->from('pencapaian');
        $this->db->where('id_pengguna', $pengguna_id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);

        $query = $this->db->get()->row();
        return $query ? round($query->rata_rata, 2) : null;
    }





    //Rata Rata nilai Perbulan
    public function getNilaiLapMonth($id, $month)
    {
        $this->db->select('
                    id as id_lap,
                    nilai_a, 
                    nilai_b,
                    date
                        ');
        $this->db->from('nilai_point');
        $this->db->where('id_pengguna', $id);
        $this->db->like('date', $month, 'after');

        return $this->db->get()->result();
    }


    public function getNilaiUmumMonth($id, $month)
    {
        $this->db->select('
            id as id_lap,
            nilaia1, 
            nilaia2, 
            nilaia3,
            nilaia4,
            nilaia5,
            nilaib1,
            nilaib2,
            nilaib3,
            nilaib4,
            nilaib5,
            date
                        ');
        $this->db->from('penilaian_umum');
        $this->db->where('id_pengguna', $id);
        $this->db->like('date', $month, 'after');

        return $this->db->get()->result();
    }

    public function getNilaiPencMonth($id, $month)
    {
        $this->db->select('
                    id as id_lap,
                    nilai_a, 
                    nilai_b,
                    tanggal
                        ');
        $this->db->from('pencapaian');
        $this->db->where('id_pengguna', $id);
        $this->db->like('tanggal', $month, 'after');

        return $this->db->get()->result();
    }


    public function getBywhereActive()
    {
        $sql = "SELECT * FROM pengguna p 
							WHERE p.is_active = 1 
							AND p.pengguna_id NOT IN (1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 37, 73, 68, 724, 94, 77) 
							ORDER BY p.nama ASC";

        $query = $this->db->query($sql);
        return $query->result(); // Mengembalikan hasil query dalam bentuk array objek
    }





    //Dashboard Home New
    function countIzinJamKerja($id_pengaju)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('surat_izin_jam_kerja');
        $this->db->where('id_pengaju', $id_pengaju);
        $this->db->where('jenis', 1);
        $this->db->where('ttd_3', 1);
        $this->db->where('YEAR(tanggal)', date('Y')); // hanya tahun ini
        return $this->db->get()->row()->total;
    }


    function countIzinMeninggalkan($id_pengaju)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('surat_izin_jam_kerja');
        $this->db->where('id_pengaju', $id_pengaju);
        $this->db->where('jenis', 2);
        $this->db->where('ttd_3', 1);
        $this->db->where('YEAR(tgl_awal)', date('Y')); // hanya tahun ini
        return $this->db->get()->row()->total;
    }

    function countCutiTahunan($id_pengaju)
    {
        $this->db->select('IFNULL(SUM(total), 0) as totalAll');
        $this->db->from('surat_cuti_tahunan');
        $this->db->where('id_pengaju', $id_pengaju);
        $this->db->where('ttd_3', 1);
        $this->db->where('YEAR(tgl_awal)', date('Y')); // hanya tahun ini
        return $this->db->get()->row()->totalAll;
    }


    function daftarIzinJamKerja($id_pengaju)
    {
        $this->db->select('
            id,
            kode_ijk, 
            alasan
        ');
        $this->db->from('surat_izin_jam_kerja');
        $this->db->where('id_pengaju', $id_pengaju);
        $this->db->where('jenis', 1);
        $this->db->where('ttd_3', 1);
        $this->db->where('YEAR(tanggal)', date('Y')); // hanya tahun ini
        $this->db->order_by('id', 'DESC');

        $data = $this->db->get()->result();

        return $data;
    }


    function daftarIzinMeninggalkan($id_pengaju)
    {
        $this->db->select('
            id,
            kode_ijk, 
            alasan
        ');
        $this->db->from('surat_izin_jam_kerja');
        $this->db->where('id_pengaju', $id_pengaju);
        $this->db->where('jenis', 2);
        $this->db->where('ttd_3', 1);
        $this->db->where('YEAR(tgl_awal)', date('Y')); // hanya tahun ini
        $this->db->order_by('id', 'DESC');

        $data = $this->db->get()->result();

        return $data;
    }


    function daftarCutiTahunan($id_pengaju)
    {
        $this->db->select('
            id,
            kode_cuti, 
            alasan
        ');
        $this->db->from('surat_cuti_tahunan');
        $this->db->where('id_pengaju', $id_pengaju);
        $this->db->where('ttd_3', 1);
        $this->db->where('YEAR(tgl_awal)', date('Y')); // hanya tahun ini
        $this->db->order_by('id', 'DESC');

        $data = $this->db->get()->result();

        return $data;
    }


    function getMasaKerjaKategori($id_pengguna)
    {
        $this->db->select('tgl_kontrak');
        $this->db->from('pengguna');
        $this->db->where('pengguna_id', $id_pengguna);
        $row = $this->db->get()->row();

        return $this->getMasaKerjaKategoriFromTanggalKontrak($row ? $row->tgl_kontrak : null);
    }

    function getRekapSisaCutiKaryawanTahunBerjalan($pemerintah = 8, $tahun = null)
    {
        $tahun = $tahun ? (int) $tahun : (int) date('Y');
        $jatah_dasar = max(0, 12 - (int) $pemerintah);

        $sql = "SELECT p.pengguna_id, p.nama, p.no_pegawai, p.jabatan, p.tgl_kontrak,
                       IFNULL(SUM(sct.total), 0) AS cuti_diambil
                FROM pengguna p
                LEFT JOIN surat_cuti_tahunan sct
                    ON sct.id_pengaju = p.pengguna_id
                    AND sct.ttd_3 = 1
                    AND YEAR(sct.tgl_awal) = ?
                WHERE p.is_active = 1
                                    AND p.no_pegawai IS NOT NULL
                                    AND TRIM(p.no_pegawai) <> ''
                  AND p.pengguna_id NOT IN (1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 37, 73, 68, 724, 94, 77)
                GROUP BY p.pengguna_id, p.nama, p.no_pegawai, p.jabatan, p.tgl_kontrak
                ORDER BY p.nama ASC";

        $rows = $this->db->query($sql, array($tahun))->result();

        foreach ($rows as $row) {
            $masa_kerja = $this->getMasaKerjaKategoriFromTanggalKontrak($row->tgl_kontrak);
            $jatah_cuti = $this->getJatahCutiTahunanByKategori($masa_kerja, $jatah_dasar);
            $cuti_diambil = (float) $row->cuti_diambil;

            $row->masa_kerja = $masa_kerja;
            $row->jatah_cuti = $jatah_cuti;
            $row->sisa_cuti = $jatah_cuti - $cuti_diambil;
        }

        return $rows;
    }

    function getDetailPengajuanCutiTahunanKaryawan($pengguna_id, $tahun = null)
    {
        $tahun = $tahun ? (int) $tahun : (int) date('Y');

        // Detail cuti di modal mengikuti perhitungan rekap: hanya cuti yang sudah disetujui final.
        $rows = $this->db->select('id, kode_cuti, alasan, total, tgl_awal, tgl_akhir')
            ->from('surat_cuti_tahunan')
            ->where('id_pengaju', (int) $pengguna_id)
            ->where('YEAR(tgl_awal)', $tahun)
            ->where('ttd_3', 1)
            ->order_by('tgl_awal', 'DESC')
            ->order_by('id', 'DESC')
            ->get()
            ->result();

        $result = array();

        foreach ($rows as $row) {
            $tanggal_awal = !empty($row->tgl_awal) ? date('d-m-Y', strtotime($row->tgl_awal)) : '-';
            $tanggal_akhir = !empty($row->tgl_akhir) ? date('d-m-Y', strtotime($row->tgl_akhir)) : '-';

            $tanggal_label = $tanggal_awal;
            if ($tanggal_awal !== '-' && $tanggal_akhir !== '-' && $row->tgl_awal !== $row->tgl_akhir) {
                $tanggal_label .= ' s/d ' . $tanggal_akhir;
            }

            $result[] = array(
                'id' => (int) $row->id,
                'kode_cuti' => (string) $row->kode_cuti,
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'tanggal_label' => $tanggal_label,
                'total_hari' => (float) $row->total,
                'alasan' => (string) $row->alasan,
                'status_key' => 'disetujui',
                'status_label' => 'Disetujui',
                'masuk_perhitungan' => 1,
            );
        }

        return $result;
    }

    function getSummaryPengajuanCutiTahunanKaryawan($pengguna_id, $tahun = null)
    {
        $tahun = $tahun ? (int) $tahun : (int) date('Y');

        $rows = $this->db->select('total, ttd_1, ttd_2, ttd_3, status')
            ->from('surat_cuti_tahunan')
            ->where('id_pengaju', (int) $pengguna_id)
            ->where('YEAR(tgl_awal)', $tahun)
            ->get()
            ->result();

        $summary = array(
            'total_pengajuan' => 0,
            'total_disetujui' => 0,
            'total_pending' => 0,
            'total_ditolak' => 0,
            'total_hari_disetujui' => 0,
        );

        foreach ($rows as $row) {
            $status_info = $this->getStatusCutiTahunanFromRow($row);
            $summary['total_pengajuan']++;

            if (!empty($status_info['counted'])) {
                $summary['total_disetujui']++;
                $summary['total_hari_disetujui'] += (float) $row->total;
            } elseif (isset($status_info['key']) && $status_info['key'] === 'ditolak') {
                $summary['total_ditolak']++;
            } else {
                $summary['total_pending']++;
            }
        }

        return $summary;
    }

    private function getStatusCutiTahunanFromRow($row)
    {
        $ttd_1 = isset($row->ttd_1) ? (int) $row->ttd_1 : 0;
        $ttd_2 = isset($row->ttd_2) ? (int) $row->ttd_2 : 0;
        $ttd_3 = isset($row->ttd_3) ? (int) $row->ttd_3 : 0;
        $status = isset($row->status) ? (int) $row->status : -1;

        if ($ttd_1 === 2 || $ttd_2 === 2 || $ttd_3 === 2 || in_array($status, array(4, 5), true)) {
            return array('key' => 'ditolak', 'label' => 'Ditolak', 'counted' => false);
        }

        if ($ttd_3 === 1 || $status === 3) {
            return array('key' => 'disetujui', 'label' => 'Disetujui', 'counted' => true);
        }

        if ($ttd_2 === 1 || $status === 2) {
            return array('key' => 'disetujui_hr', 'label' => 'Disetujui HR', 'counted' => false);
        }

        if ($ttd_1 === 1 || $status === 1) {
            return array('key' => 'disetujui_ga', 'label' => 'Disetujui GA', 'counted' => false);
        }

        return array('key' => 'diajukan', 'label' => 'Baru Diajukan', 'counted' => false);
    }

    private function getMasaKerjaKategoriFromTanggalKontrak($tgl_kontrak)
    {
        if (empty($tgl_kontrak)) {
            return 'D';
        }

        $tgl_kontrak_obj = new DateTime($tgl_kontrak);
        $today = new DateTime();
        $years = $today->diff($tgl_kontrak_obj)->y;

        if ($years >= 5) {
            return 'A';
        }

        if ($years >= 1) {
            return 'B';
        }

        return 'C';
    }

    private function getJatahCutiTahunanByKategori($masa_kerja, $jatah_dasar)
    {
        if ($masa_kerja === 'A') {
            return $jatah_dasar + 2;
        }

        if ($masa_kerja === 'B') {
            return $jatah_dasar;
        }

        return 0;
    }
}
