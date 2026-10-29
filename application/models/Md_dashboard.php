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

    /**
     * Rekapitulasi Izin, Cuti, dan Sakit karyawan per bulan terpilih.
     * Menggabungkan data dari tabel absensi, surat_cuti_tahunan, dan surat_izin_jam_kerja secara DISTINCT per tanggal.
     *
     * @param string $month Format 'YYYY-MM'
     * @return array [pengguna_id => ['izin' => ['total' => int, 'dates' => string], ...]]
     */
    public function getRekapIzinCutiSakitMonth($month)
    {
        $tgl_awal = date('Y-m-01', strtotime($month));
        $tgl_akhir = date('Y-m-t', strtotime($month));

        // 1. Dari tabel absensi
        $sql_absen = "SELECT pengguna_id, 
            DATE(data_created) as tgl,
            CASE 
                WHEN status_absen = 'sakit' OR type_absen = 'sakit' THEN 'sakit'
                WHEN status_absen = 'cuti' OR type_absen = 'cuti' THEN 'cuti'
                WHEN status_absen = 'izin' OR (type_absen = 'izin' AND (status_absen IS NULL OR status_absen = '' OR status_absen = 'izin')) THEN 'izin'
                ELSE NULL 
            END as kategori
            FROM absensi
            WHERE data_created >= ? AND data_created <= ?
              AND (type_absen IN ('izin', 'cuti', 'sakit') OR status_absen IN ('izin', 'cuti', 'sakit'))";

        $query_absen = $this->db->query($sql_absen, array($tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59'));
        $user_dates = array();
        foreach ($query_absen->result() as $r) {
            if (!empty($r->kategori) && !empty($r->tgl)) {
                $user_dates[$r->pengguna_id][$r->kategori][$r->tgl] = true;
            }
        }

        // 2. Dari tabel surat_cuti_tahunan (yang disetujui / status aktif)
        $sql_cuti = "SELECT id_pengaju, tgl_awal, tgl_akhir 
                     FROM surat_cuti_tahunan 
                     WHERE status IN (1, 2, 3) 
                       AND tgl_awal <= ? AND tgl_akhir >= ?";
        $query_cuti = $this->db->query($sql_cuti, array($tgl_akhir, $tgl_awal));
        foreach ($query_cuti->result() as $r) {
            $cur = max($tgl_awal, $r->tgl_awal);
            $end = min($tgl_akhir, $r->tgl_akhir);
            while (strtotime($cur) <= strtotime($end)) {
                $user_dates[$r->id_pengaju]['cuti'][$cur] = true;
                $cur = date('Y-m-d', strtotime('+1 day', strtotime($cur)));
            }
        }

        // 3. Dari tabel surat_izin_jam_kerja (yang tidak ditolak)
        $sql_sijk = "SELECT id_pengaju, jenis_izin, tanggal, tgl_awal, tgl_akhir 
                     FROM surat_izin_jam_kerja 
                     WHERE (status NOT IN (4, 5) OR status IS NULL)
                       AND ((tanggal >= ? AND tanggal <= ?) 
                            OR (tgl_awal <= ? AND tgl_akhir >= ?))";
        $query_sijk = $this->db->query($sql_sijk, array($tgl_awal, $tgl_akhir, $tgl_akhir, $tgl_awal));
        foreach ($query_sijk->result() as $r) {
            $kat = (strtolower($r->jenis_izin ?? '') == 'sakit') ? 'sakit' : 'izin';
            if (!empty($r->tanggal)) {
                $user_dates[$r->id_pengaju][$kat][$r->tanggal] = true;
            }
            if (!empty($r->tgl_awal) && !empty($r->tgl_akhir)) {
                $cur = max($tgl_awal, $r->tgl_awal);
                $end = min($tgl_akhir, $r->tgl_akhir);
                while (strtotime($cur) <= strtotime($end)) {
                    $user_dates[$r->id_pengaju][$kat][$cur] = true;
                    $cur = date('Y-m-d', strtotime('+1 day', strtotime($cur)));
                }
            }
        }

        // Summary counts and formatted date strings
        $summary = array();
        foreach ($user_dates as $pid => $kats) {
            foreach (array('izin', 'cuti', 'sakit') as $k) {
                $dates = isset($kats[$k]) ? array_keys($kats[$k]) : array();
                sort($dates);
                $formatted_dates = array_map(function ($d) {
                    return date('d/m', strtotime($d));
                }, $dates);
                $summary[$pid][$k] = array(
                    'total' => count($dates),
                    'dates' => implode(', ', $formatted_dates)
                );
            }
        }

        return $summary;
    }

    /**
     * Ambil statistik presensi pribadi karyawan (Sakit, Izin, Cuti, Terlambat)
     * untuk Bulan Berjalan dan Tahun Berjalan beserta detail tanggalnya.
     * 
     * @param int $pengguna_id
     * @return array
     */
    public function getPersonalAttendanceStats($pengguna_id)
    {
        $bulan_ini = date('Y-m');
        $tahun_ini = date('Y');

        $tgl_awal_bulan = date('Y-m-01');
        $tgl_akhir_bulan = date('Y-m-t');

        $tgl_awal_tahun = date('Y-01-01');
        $tgl_akhir_tahun = date('Y-12-31');

        // --- 1. TERLAMBAT ---
        // Bulan Ini
        $this->db->select('id_absensi, waktu_absen, DATE(data_created) as tanggal, data_created');
        $this->db->from('absensi');
        $this->db->where('pengguna_id', $pengguna_id);
        $this->db->where('type_absen', 'masuk');
        $this->db->where('status_absen', 'terlambat');
        $this->db->where('data_created >=', $tgl_awal_bulan . ' 00:00:00');
        $this->db->where('data_created <=', $tgl_akhir_bulan . ' 23:59:59');
        $this->db->order_by('data_created', 'DESC');
        $terlambat_list_bulan = $this->db->get()->result();
        $terlambat_count_bulan = count($terlambat_list_bulan);

        // Tahun Ini
        $this->db->select('COUNT(*) as total');
        $this->db->from('absensi');
        $this->db->where('pengguna_id', $pengguna_id);
        $this->db->where('type_absen', 'masuk');
        $this->db->where('status_absen', 'terlambat');
        $this->db->where('data_created >=', $tgl_awal_tahun . ' 00:00:00');
        $this->db->where('data_created <=', $tgl_akhir_tahun . ' 23:59:59');
        $row_terlambat_tahun = $this->db->get()->row();
        $terlambat_count_tahun = $row_terlambat_tahun ? (int)$row_terlambat_tahun->total : 0;

        // --- 2. SAKIT, IZIN, CUTI (BULAN INI) ---
        $stats_bulan = $this->_calcIzinCutiSakitRange($pengguna_id, $tgl_awal_bulan, $tgl_akhir_bulan);

        // --- 3. SAKIT, IZIN, CUTI (TAHUN INI) ---
        $stats_tahun = $this->_calcIzinCutiSakitRange($pengguna_id, $tgl_awal_tahun, $tgl_akhir_tahun);

        return array(
            'bulan' => array(
                'label'      => date('F Y'),
                'label_id'   => $this->_namaBulanIndo(date('m')) . ' ' . date('Y'),
                'terlambat'  => array('total' => $terlambat_count_bulan, 'list' => $terlambat_list_bulan),
                'sakit'      => $stats_bulan['sakit'],
                'izin'       => $stats_bulan['izin'],
                'cuti'       => $stats_bulan['cuti'],
                'dinas'      => $stats_bulan['dinas'],
            ),
            'tahun' => array(
                'label'      => $tahun_ini,
                'terlambat'  => $terlambat_count_tahun,
                'sakit'      => $stats_tahun['sakit']['total'],
                'izin'       => $stats_tahun['izin']['total'],
                'cuti'       => $stats_tahun['cuti']['total'],
                'dinas'      => $stats_tahun['dinas']['total'],
            )
        );
    }

    private function _calcIzinCutiSakitRange($pengguna_id, $tgl_awal, $tgl_akhir)
    {
        $dates_map = array(
            'sakit' => array(),
            'izin'  => array(),
            'cuti'  => array(),
            'dinas' => array()
        );

        // 1. Dari tabel absensi
        $sql_absen = "SELECT DATE(data_created) as tgl, waktu_absen, keterangan, jenis_absen,
            CASE 
                WHEN UPPER(jenis_absen) = 'DINAS' OR status_absen = 'dinas' OR type_absen = 'dinas' THEN 'dinas'
                WHEN status_absen = 'sakit' OR type_absen = 'sakit' THEN 'sakit'
                WHEN status_absen = 'cuti' OR type_absen = 'cuti' THEN 'cuti'
                WHEN status_absen = 'izin' OR (type_absen = 'izin' AND (status_absen IS NULL OR status_absen = '' OR status_absen = 'izin')) THEN 'izin'
                ELSE NULL 
            END as kategori
            FROM absensi
            WHERE pengguna_id = ?
              AND data_created >= ? AND data_created <= ?
              AND (UPPER(jenis_absen) = 'DINAS' OR status_absen IN ('izin', 'cuti', 'sakit', 'dinas') OR type_absen IN ('izin', 'cuti', 'sakit', 'dinas'))
            ORDER BY data_created DESC";
        $q_absen = $this->db->query($sql_absen, array($pengguna_id, $tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59'));
        foreach ($q_absen->result() as $r) {
            if (!empty($r->kategori) && !empty($r->tgl)) {
                $sumber = ($r->kategori == 'dinas') 
                    ? 'Absen Dinas (' . ($r->waktu_absen ? date('H:i', strtotime($r->waktu_absen)) : '') . ')' 
                    : 'Absensi';
                $ket = !empty($r->keterangan) ? $r->keterangan : ($r->kategori == 'dinas' ? 'Absensi Dinas' : '-');
                $dates_map[$r->kategori][$r->tgl] = array(
                    'tgl' => $r->tgl,
                    'tgl_formatted' => date('d M Y', strtotime($r->tgl)),
                    'sumber' => $sumber,
                    'keterangan' => $ket
                );
            }
        }

        // 2. Dari tabel surat_cuti_tahunan (yang disetujui / status aktif 1, 2, 3)
        $sql_cuti = "SELECT kode_cuti, alasan, tgl_awal, tgl_akhir 
                     FROM surat_cuti_tahunan 
                     WHERE id_pengaju = ? 
                       AND status IN (1, 2, 3) 
                       AND tgl_awal <= ? AND tgl_akhir >= ?";
        $q_cuti = $this->db->query($sql_cuti, array($pengguna_id, $tgl_akhir, $tgl_awal));
        foreach ($q_cuti->result() as $r) {
            $cur = max($tgl_awal, $r->tgl_awal);
            $end = min($tgl_akhir, $r->tgl_akhir);
            while (strtotime($cur) <= strtotime($end)) {
                if (!isset($dates_map['cuti'][$cur])) {
                    $dates_map['cuti'][$cur] = array(
                        'tgl' => $cur,
                        'tgl_formatted' => date('d M Y', strtotime($cur)),
                        'sumber' => 'Surat Cuti (' . ($r->kode_cuti ?: 'Cuti') . ')',
                        'keterangan' => !empty($r->alasan) ? $r->alasan : 'Cuti Tahunan'
                    );
                }
                $cur = date('Y-m-d', strtotime('+1 day', strtotime($cur)));
            }
        }

        // 3. Dari tabel surat_izin_jam_kerja (yang tidak ditolak status != 4, 5)
        $sql_sijk = "SELECT kode_ijk, alasan, jenis_izin, tanggal, tgl_awal, tgl_akhir 
                     FROM surat_izin_jam_kerja 
                     WHERE id_pengaju = ? 
                       AND (status NOT IN (4, 5) OR status IS NULL)
                       AND ((tanggal >= ? AND tanggal <= ?) 
                            OR (tgl_awal <= ? AND tgl_akhir >= ?))";
        $q_sijk = $this->db->query($sql_sijk, array($pengguna_id, $tgl_awal, $tgl_akhir, $tgl_akhir, $tgl_awal));
        foreach ($q_sijk->result() as $r) {
            $kat = (strtolower($r->jenis_izin ?? '') == 'sakit') ? 'sakit' : 'izin';
            $ket = !empty($r->alasan) ? $r->alasan : (!empty($r->jenis_izin) ? $r->jenis_izin : 'Izin Kerja');
            $sumber = 'Surat Izin (' . ($r->kode_ijk ?: 'Izin') . ')';

            if (!empty($r->tanggal) && $r->tanggal >= $tgl_awal && $r->tanggal <= $tgl_akhir) {
                if (!isset($dates_map[$kat][$r->tanggal])) {
                    $dates_map[$kat][$r->tanggal] = array(
                        'tgl' => $r->tanggal,
                        'tgl_formatted' => date('d M Y', strtotime($r->tanggal)),
                        'sumber' => $sumber,
                        'keterangan' => $ket
                    );
                }
            }
            if (!empty($r->tgl_awal) && !empty($r->tgl_akhir)) {
                $cur = max($tgl_awal, $r->tgl_awal);
                $end = min($tgl_akhir, $r->tgl_akhir);
                while (strtotime($cur) <= strtotime($end)) {
                    if (!isset($dates_map[$kat][$cur])) {
                        $dates_map[$kat][$cur] = array(
                            'tgl' => $cur,
                            'tgl_formatted' => date('d M Y', strtotime($cur)),
                            'sumber' => $sumber,
                            'keterangan' => $ket
                        );
                    }
                    $cur = date('Y-m-d', strtotime('+1 day', strtotime($cur)));
                }
            }
        }

        // 4. Dari tabel surat_pd_dinas (Surat Perjalanan Dinas yang disetujui ttd_3 = 1)
        $sql_pd = "SELECT sp.id, sp.kode, sp.lokasi_dinas, sp.perihal, sp.tgl_dinas, sp.tgl_dinas_akhir 
                   FROM surat_pd_dinas sp
                   LEFT JOIN pengguna p ON (p.nama = sp.nama_karyawan)
                   WHERE (p.pengguna_id = ? OR sp.id_pengguna = ?)
                     AND sp.ttd_3 = 1
                     AND sp.tgl_dinas <= ? AND sp.tgl_dinas_akhir >= ?";
        $q_pd = $this->db->query($sql_pd, array($pengguna_id, $pengguna_id, $tgl_akhir, $tgl_awal));
        foreach ($q_pd->result() as $r) {
            $cur = max($tgl_awal, $r->tgl_dinas);
            $end = min($tgl_akhir, $r->tgl_dinas_akhir);
            while (strtotime($cur) <= strtotime($end)) {
                if (!isset($dates_map['dinas'][$cur])) {
                    $dates_map['dinas'][$cur] = array(
                        'tgl' => $cur,
                        'tgl_formatted' => date('d M Y', strtotime($cur)),
                        'sumber' => 'Surat Dinas (' . (!empty($r->kode) ? $r->kode : 'SPD') . ')',
                        'keterangan' => !empty($r->perihal) ? $r->perihal : (!empty($r->lokasi_dinas) ? 'Dinas: ' . $r->lokasi_dinas : 'Perjalanan Dinas')
                    );
                }
                $cur = date('Y-m-d', strtotime('+1 day', strtotime($cur)));
            }
        }

        $res = array();
        foreach (array('sakit', 'izin', 'cuti', 'dinas') as $k) {
            krsort($dates_map[$k]);
            $list = array_values($dates_map[$k]);
            $res[$k] = array(
                'total' => count($list),
                'list'  => $list
            );
        }

        return $res;
    }

    /**
     * Mengambil data Surat Peringatan (SP) yang sedang aktif untuk seorang karyawan
     * Kriteria aktif:
     * 1. sp.nama = pengguna_id atau nama karyawan
     * 2. sp.ttd_3 != 2 (bukan surat yang ditolak)
     * 3. Hari ini berada di antara tgl_pengajuan s/d (tgl_pengajuan + masa bulan)
     * 
     * @param int $pengguna_id
     * @return object|null
     */
    public function getActiveSP($pengguna_id)
    {
        // 1. Cek apakah ada data SP resmi di database
        if ($this->db->table_exists('surat_peringatan')) {
            $sql = "SELECT sp.* 
                    FROM surat_peringatan sp
                    LEFT JOIN pengguna p ON (p.pengguna_id = ? AND (sp.nama = ? OR sp.nama = p.nama))
                    WHERE (sp.nama = ? OR (p.pengguna_id IS NOT NULL AND sp.nama = p.nama))
                      AND (sp.ttd_3 IS NULL OR sp.ttd_3 != 2)
                    ORDER BY sp.tgl_pengajuan DESC, sp.id DESC
                    LIMIT 1";
            $q = $this->db->query($sql, array($pengguna_id, (string)$pengguna_id, (string)$pengguna_id));
            $sp = $q->row();

            if ($sp) {
                $tgl_mulai_str = !empty($sp->tgl_pengajuan) ? $sp->tgl_pengajuan : (!empty($sp->tgl_evaluasi) ? $sp->tgl_evaluasi : null);
                if ($tgl_mulai_str && $tgl_mulai_str != '0000-00-00') {
                    $masa_bulan = 0;
                    if (!empty($sp->masa)) {
                        if (preg_match('/\d+/', (string)$sp->masa, $matches)) {
                            $masa_bulan = intval($matches[0]);
                        }
                    }
                    if ($masa_bulan <= 0) {
                        $masa_bulan = 6;
                    }

                    try {
                        $tgl_mulai = new DateTime($tgl_mulai_str);
                        $tgl_akhir = clone $tgl_mulai;
                        $tgl_akhir->modify("+" . $masa_bulan . " months");

                        $today = new DateTime(date('Y-m-d'));

                        if ($today >= $tgl_mulai && $today <= $tgl_akhir) {
                            $diff = $today->diff($tgl_akhir);
                            $sisa_hari = (int)$diff->format('%a');

                            $jenis_sp = !empty($sp->jenis_sp) ? trim($sp->jenis_sp) : '';
                            if (empty($jenis_sp)) {
                                $perihal = strtolower(trim($sp->perihal));
                                if ($perihal == 'pertama') $jenis_sp = 'SP 1';
                                elseif ($perihal == 'kedua') $jenis_sp = 'SP 2';
                                elseif (in_array($perihal, ['ketiga', 'ketiga lanjutan'])) $jenis_sp = 'SP 3';
                                else $jenis_sp = 'Surat Peringatan';
                            }

                            $tgl_mulai_formatted = date('d', strtotime($tgl_mulai->format('Y-m-d'))) . ' ' . $this->_namaBulanIndo(date('m', strtotime($tgl_mulai->format('Y-m-d')))) . ' ' . date('Y', strtotime($tgl_mulai->format('Y-m-d')));
                            $tgl_akhir_formatted = date('d', strtotime($tgl_akhir->format('Y-m-d'))) . ' ' . $this->_namaBulanIndo(date('m', strtotime($tgl_akhir->format('Y-m-d')))) . ' ' . date('Y', strtotime($tgl_akhir->format('Y-m-d')));

                            return (object) array(
                                'id'                  => $sp->id,
                                'kode'                => $sp->kode,
                                'jenis_sp'            => $jenis_sp,
                                'perihal'             => $sp->perihal,
                                'kesalahan'           => $sp->kesalahan,
                                'sanksi'              => $sp->sanksi,
                                'masa_bulan'          => $masa_bulan,
                                'tgl_mulai'           => $tgl_mulai->format('Y-m-d'),
                                'tgl_mulai_formatted' => $tgl_mulai_formatted,
                                'tgl_akhir'           => $tgl_akhir->format('Y-m-d'),
                                'tgl_akhir_formatted' => $tgl_akhir_formatted,
                                'sisa_hari'           => $sisa_hari,
                                'ttd_3'               => $sp->ttd_3
                            );
                        }
                    } catch (Exception $e) {
                    }
                }
            }
        }

        // 2. Simulasi/Uji Coba Tampilan untuk Dimas Chandra Winata (pengguna_id: 770 atau 778 atau nama mengandung Dimas)
        $nama_session = function_exists('sessNama') ? sessNama() : '';
        $is_dimas = ($pengguna_id == 770 || $pengguna_id == 778 || stripos($nama_session, 'dimas') !== false);

        if ($is_dimas) {
            $tgl_mulai = new DateTime(date('Y-m-d', strtotime('-1 month')));
            $tgl_akhir = clone $tgl_mulai;
            $tgl_akhir->modify('+6 months');
            $diff = (new DateTime(date('Y-m-d')))->diff($tgl_akhir);
            $sisa_hari = (int)$diff->format('%a');

            $tgl_mulai_formatted = date('d', strtotime($tgl_mulai->format('Y-m-d'))) . ' ' . $this->_namaBulanIndo(date('m', strtotime($tgl_mulai->format('Y-m-d')))) . ' ' . date('Y', strtotime($tgl_mulai->format('Y-m-d')));
            $tgl_akhir_formatted = date('d', strtotime($tgl_akhir->format('Y-m-d'))) . ' ' . $this->_namaBulanIndo(date('m', strtotime($tgl_akhir->format('Y-m-d')))) . ' ' . date('Y', strtotime($tgl_akhir->format('Y-m-d')));

            return (object) array(
                'id'                  => 1,
                'kode'                => '001/SP/PT.VYM/PKU/' . ambil_bulan() . '/' . date('Y'),
                'jenis_sp'            => 'SP 1',
                'perihal'             => 'Pertama',
                'kesalahan'           => 'Simulasi Uji Coba: Keterlambatan dan ketidakhadiran kerja tanpa konfirmasi',
                'sanksi'              => 'Peringatan tertulis dan evaluasi kedisiplinan kerja selama 6 bulan',
                'masa_bulan'          => 6,
                'tgl_mulai'           => $tgl_mulai->format('Y-m-d'),
                'tgl_mulai_formatted' => $tgl_mulai_formatted,
                'tgl_akhir'           => $tgl_akhir->format('Y-m-d'),
                'tgl_akhir_formatted' => $tgl_akhir_formatted,
                'sisa_hari'           => $sisa_hari,
                'ttd_3'               => 1
            );
        }

        return null;
    }

    private function _namaBulanIndo($m)
    {
        $bulan = array(
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        return isset($bulan[$m]) ? $bulan[$m] : date('F');
    }
}
