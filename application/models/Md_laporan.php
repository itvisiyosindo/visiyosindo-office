<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan extends CI_Model
{


    function getAllPenggunaAktif()
    {
        $this->db->order_by('pg.nama', 'ASC');

        return $this->datatables
            ->select('  
							pg.pengguna_id,
							pg.nama,
							pg.jabatan,
							pg.no_pegawai,
              lp.id as id_lp,
              lp.id_pengguna,
              lp.penilai_a,
              lp.penilai_b,
              p2.nama as penilai,
					')
            ->from('pengguna pg')
            ->join('laporan_penilai lp', 'lp.id_pengguna=pg.pengguna_id', 'left')
            ->join('pengguna p2', 'lp.penilai_b=p2.pengguna_id', 'left')
            ->where('pg.status', 1)
            ->where('pg.is_active', 1)
            ->where("pg.pengguna_id NOT IN (1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 37, 73, 68, 724, 77)", NULL, FALSE) // Ubah ke string SQL langsung
            ->generate();
    }

    function getAllPenilai($id)
    {
        $this->db->order_by('p.nama', 'ASC');
        return $this->datatables
            ->select('
							lp.id as id_po,
							lp.id_pengguna,
							lp.penilai_a,
							lp.penilai_b,
							p.nama,
							p.no_pegawai,
							p.jabatan,
              p.pengguna_id
							')
            ->from('laporan_penilai lp')
            ->join('pengguna p', 'lp.id_pengguna=p.pengguna_id')
            ->where('lp.penilai_b', $id)
            //->where('f.status = 1')
            ->generate();
    }

    public function getPenilaiLaporan()
    {
        $this->db->select('penilai_b, id_pengguna');
        $this->db->from('laporan_penilai');
        $query = $this->db->get();
        return $query->result();
    }


    public function getBywhereActive()
    {
        $sql = "SELECT * FROM pengguna p 
							WHERE p.is_active = 1 
							AND p.pengguna_id NOT IN (1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 749) 
							ORDER BY p.nama ASC";

        $query = $this->db->query($sql);
        return $query->result(); // Mengembalikan hasil query dalam bentuk array objek
    }


    public function getByJobs($id)
    {
        $sql = "SELECT 
									f.id AS id_pod,
									f.id_jobdesc,
									f.nilai,
									f.point,
									f.idPengguna,
									f.deskripsi,
									f.status
							FROM jobdesc_detail f
							WHERE f.idPengguna = ? 
							AND f.status = 1
							ORDER BY f.id ASC";

        $query = $this->db->query($sql, array($id));
        return $query->result(); // Mengembalikan hasil query dalam bentuk array objek
    }


    // Add
    function add($data)
    {
        $this->db->insert('laporan', $data);
    }

    function addPencapaian($data)
    {
        $this->db->insert('pencapaian', $data);
    }


    function addNilai($data)
    {
        $this->db->insert('nilai_point', $data);
    }

    function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('laporan', $data);
    }

    function updatePencapaian($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('pencapaian', $data);
    }


    function updatePenilai($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('laporan_penilai', $data);
    }

    function deleteLaporan($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('laporan');
    }

    function deletePencapaian($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('pencapaian');
    }


    function getAllLaporan($id)
    {
        $this->db->order_by('tanggal', 'DESC');
        return $this->datatables
            ->select('
								id,
								tanggal,
								id_pengaju,
                jobdesc,
								jenis,
								progress,
								kendala,
								solusi,
								status_pekerjaan,
								link,
								ket_hasil,
								pihak,
								keterangan,
								nilai,
								status,
								created_at
            ')
            ->from('laporan')
            ->where('id_pengaju', $id)
            ->where('status != 3')
            ->generate();
    }


    function getById($id)
    {
        return $this->db->get_where('laporan p', array('p.id' => $id))->result();
    }

    function getByIdPencapaian($id)
    {
        return $this->db->get_where('pencapaian p', array('p.id' => $id))->result();
    }

    function getByIdDelete($id)
    {
        return $this->db->get_where('laporan p', array('p.id' => $id))->row();
    }

    function getByIdDeletePencapaian($id)
    {
        return $this->db->get_where('pencapaian p', array('p.id' => $id))->row();
    }



    function getByIdPenilai($id)
    {
        return $this->db->get_where('laporan_penilai p', array('p.id' => $id))->result();
    }

    function getByIdJob($id)
    {
        return $this->db->get_where('jobdesc_detail p', array('p.id' => $id))->result();
    }


    function getPengguna($id)
    {
        $this->db->select('
							pg.pengguna_id,
							pg.nama,
							pg.jabatan,
							pg.no_pegawai,
							lp.penilai_a,
							lp.penilai_b,
							pa.nama as nama_penilai_a,
							pa.jabatan as jabatan_penilai_a,
							pb.nama as nama_penilai_b,
							pb.nama as penilai,
							pb.jabatan as jabatan_penilai_b
					')
            ->from('pengguna pg')
            ->join('laporan_penilai lp', 'lp.id_pengguna = pg.pengguna_id', 'left')
            ->join('pengguna pa', 'lp.penilai_a = pa.pengguna_id', 'left')
            ->join('pengguna pb', 'lp.penilai_b = pb.pengguna_id', 'left')
            ->where('pg.pengguna_id', $id);
        return $this->db->get()->row();
    }


    function getLaporanBywhereID($where)
    {
        $this->db->select('
								id,
								tanggal,
								id_pengaju,
                jobdesc,
								jenis,
								progress,
								kendala,
								solusi,
								status_pekerjaan,
								link,
								ket_hasil,
								pihak,
								keterangan,
								nilai,
								status,
								created_at
									')
            ->from('laporan')
            ->where($where)
            ->order_by('tanggal', 'DESC');
        return $this->db->get()->result();
    }



    function getLaporanPerMinggu($start_date, $end_date, $id_pengaju)
    {
        $this->db->select('
        id, 
				tanggal, 
				id_pengaju, 
				jobdesc, 
				jenis, 
				progress, 
				kendala,
        solusi, 
				status_pekerjaan, 
				link,
				ket_hasil, 
				pihak, 
				keterangan, 
				nilai, 
				status, 
				created_at
    ');
        $this->db->from('laporan');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->where('id_pengaju', $id_pengaju);
        $this->db->where('status !=', 3); // Tambahkan kondisi untuk mengecualikan status 3
        $this->db->order_by('tanggal', 'ASC');

        return $this->db->get()->result();
    }

    function getLaporanMingguanOLD($start_date, $end_date, $id)
    {
        $this->db->select('
        jd.id, 
        jd.deskripsi, 
        jd.idPengguna,
        l.id as id_lap, 
        l.tanggal, 
        l.id_pengaju, 
				l.id_job,
        l.jobdesc, 
        l.jenis, 
        l.progress, 
        l.kendala,
        l.solusi, 
        l.status_pekerjaan, 
        l.link, 
				l.ket_hasil,
        l.pihak, 
        l.keterangan, 
        l.nilai, 
        l.status, 
        l.created_at
    ');
        $this->db->from('jobdesc_detail jd');

        // LEFT JOIN dengan kondisi khusus agar data tetap muncul meskipun laporan kosong
        $this->db->join('laporan l', "jd.id = l.id_job AND (l.tanggal IS NULL OR (l.tanggal >= '$start_date' AND l.tanggal <= '$end_date'))", 'left');

        $this->db->where('jd.idPengguna', $id);
        $this->db->where('jd.status', 1);

        // Menyaring status agar status = 3 tidak muncul
        $this->db->group_start();
        $this->db->where('l.status !=', 3);
        $this->db->or_where('l.status IS NULL');
        $this->db->group_end();

        $this->db->order_by('jd.id', 'ASC');

        return $this->db->get()->result();
    }


    function getLaporanMingguan($start_date, $end_date, $id)
    {
        $this->db->select('
        jd.id, 
        jd.deskripsi, 
				jd.nilai as nilai_isi,
				jd.point,
        jd.idPengguna,
        l.id as id_lap, 
        l.tanggal, 
        l.id_pengaju, 
        l.id_job,
        l.jobdesc, 
        l.jenis, 
        l.progress, 
        l.kendala,
        l.solusi, 
        l.status_pekerjaan, 
        l.link,
				l.ket_hasil, 
        l.pihak, 
        l.keterangan, 
        l.nilai, 
        l.status, 
        l.created_at
    ');
        $this->db->from('jobdesc_detail jd');

        // LEFT JOIN dengan laporan agar jobdesc tetap muncul meskipun tidak ada laporan
        $this->db->join('laporan l', "jd.id = l.id_job AND (l.tanggal IS NULL OR (l.tanggal >= '$start_date' AND l.tanggal <= '$end_date'))", 'left');

        $this->db->where('jd.idPengguna', $id);
        $this->db->where('jd.status', 1);

        // Menyaring status agar status = 3 tidak muncul
        $this->db->group_start();
        $this->db->where('l.status !=', 3);
        $this->db->or_where('l.status IS NULL');
        $this->db->group_end();

        // Urutan pertama berdasarkan jd.id (supaya jobdesc urut)
        $this->db->order_by('jd.id', 'ASC');

        // Urutan kedua berdasarkan tanggal laporan ASC dalam setiap jobdesc
        $this->db->order_by('l.tanggal', 'ASC');

        return $this->db->get()->result();
    }

    function getLaporanMingguanRev1($start_date, $end_date, $id)
    {
        $this->db->select('
        jd.id, 
        jd.deskripsi, 
				jd.nilai as nilai_isi,
				jd.point,
        jd.idPengguna,
        l.id as id_lap, 
        l.tanggal, 
        l.id_pengaju, 
        l.id_job,
        l.jobdesc, 
        l.jenis, 
        l.progress, 
        l.kendala,
        l.solusi, 
        l.status_pekerjaan, 
        l.link,
				l.ket_hasil, 
        l.pihak, 
        l.keterangan, 
        l.nilai,  
        l.nilai_b,  
        l.nilai_pencapaian_a,  
        l.nilai_pencapaian_b, 
        l.status, 
        l.created_at
    ');
        $this->db->from('jobdesc_detail jd');

        // LEFT JOIN dengan laporan agar jobdesc tetap muncul meskipun tidak ada laporan
        $this->db->join('laporan l', "jd.id = l.id_job AND (l.tanggal IS NULL OR (l.tanggal >= '$start_date' AND l.tanggal <= '$end_date'))", 'left');

        $this->db->where('jd.idPengguna', $id);
        $this->db->where('jd.status', 1);

        // Menyaring status agar status = 3 tidak muncul
        $this->db->group_start();
        $this->db->where('l.status !=', 3);
        $this->db->or_where('l.status IS NULL');
        $this->db->group_end();

        // Urutan pertama berdasarkan jd.id (supaya jobdesc urut)
        $this->db->order_by('jd.id', 'ASC');

        // Urutan kedua berdasarkan tanggal laporan ASC dalam setiap jobdesc
        $this->db->order_by('l.tanggal', 'ASC');

        return $this->db->get()->result();
    }


    function getPencapaianMingguanRev1($start_date, $end_date, $id)
    {
        $this->db->select('
        jd.id, 
        jd.deskripsi, 
        jd.nilai as nilai_isi,
        jd.point,
        jd.idPengguna,
        l.id as id_lap, 
        l.tanggal, 
        l.id_pengaju, 
        l.id_job,
        l.jobdesc, 
        l.jenis, 
        l.progress, 
        l.kendala,
        l.solusi, 
        l.status_pekerjaan, 
        l.link,
        l.ket_hasil, 
        l.pihak, 
        l.keterangan, 
        l.nilai,  
        l.nilai_b,  
        l.nilai_pencapaian_a,  
        l.nilai_pencapaian_b, 
        l.status, 
        l.pencapaian,
        l.created_at
    ');
        $this->db->from('jobdesc_detail jd');

        // LEFT JOIN laporan dan filter waktu serta pencapaian di bagian ON
        $this->db->join('laporan l', "jd.id = l.id_job AND (l.tanggal IS NULL OR (l.tanggal >= '$start_date' AND l.tanggal <= '$end_date')) AND (l.pencapaian = 2 OR l.pencapaian IS NULL)", 'left');

        $this->db->where('jd.idPengguna', $id);
        $this->db->where('jd.status', 1);

        // Menyaring status agar status = 3 tidak muncul
        $this->db->group_start();
        $this->db->where('l.status !=', 3);
        $this->db->or_where('l.status IS NULL');
        $this->db->group_end();

        //$this->db->order_by('jd.id', 'ASC');
        $this->db->order_by('jd.id_urut', 'ASC');
        $this->db->order_by('l.tanggal', 'ASC');

        return $this->db->get()->result();
    }


    function getAllNilai($start_date, $end_date)
    {
        $this->db->select('
        pengguna.pengguna_id, 
        pengguna.nama, 
        pengguna.no_pegawai, 
        pengguna.jabatan, 
        COALESCE(AVG(laporan.nilai), 0) AS rataNilai
    ');
        $this->db->from('pengguna');
        $this->db->join(
            'laporan',
            'pengguna.pengguna_id = laporan.id_pengaju 
        AND laporan.tanggal >= "' . $start_date . '" 
        AND laporan.tanggal <= "' . $end_date . '" 
        AND laporan.status = 0',
            'inner' // Ubah dari LEFT JOIN ke INNER JOIN
        );
        $this->db->group_by('pengguna.pengguna_id, pengguna.nama, pengguna.no_pegawai, pengguna.jabatan');
        $this->db->order_by('pengguna.nama', 'ASC');

        return $this->db->get()->result();
    }




    //Update 12 06 2025
    // function getLaporanMingguanRev2($start_date, $end_date, $id)
    // {
    //     $this->db->select('
    //     jd.id, 
    //     jd.deskripsi, 
				// jd.nilai as nilai_isi,
				// jd.point,
    //     jd.idPengguna,
    //     l.id as id_lap, 
    //     l.tanggal, 
    //     l.id_pengaju, 
    //     l.id_job,
    //     l.jobdesc, 
    //     l.jenis, 
    //     l.progress, 
    //     l.kendala,
    //     l.solusi, 
    //     l.status_pekerjaan, 
    //     l.link,
				// l.ket_hasil, 
    //     l.pihak, 
    //     l.keterangan, 
    //     l.nilai,  
    //     l.nilai_b,  
    //     l.nilai_pencapaian_a,  
    //     l.nilai_pencapaian_b, 
    //     l.status, 
    //     l.created_at
    // ');
    //     $this->db->from('jobdesc_detail jd');

    //     // LEFT JOIN dengan laporan agar jobdesc tetap muncul meskipun tidak ada laporan
    //     // $this->db->join('laporan l', "jd.id = l.id_job AND (l.tanggal IS NULL OR (l.tanggal >= '$start_date' AND l.tanggal <= '$end_date'))", 'left');
    //     $this->db->join('laporan l', "jd.id = l.id_job AND l.id_pengaju = '$id' AND (l.tanggal >= '$start_date' AND l.tanggal <= '$end_date')", 'left');

    //     $this->db->where('jd.idPengguna', $id);
    //     $this->db->where('jd.status', 1);


    //     // Urutan pertama berdasarkan jd.id (supaya jobdesc urut)
    //     //$this->db->order_by('jd.id', 'ASC');
    //     $this->db->order_by('jd.id_urut', 'ASC');

    //     // Urutan kedua berdasarkan tanggal laporan ASC dalam setiap jobdesc
    //     $this->db->order_by('l.tanggal', 'ASC');

    //     return $this->db->get()->result();
    // }
    
            // Update Laporan Mingguan Rev2 dengan penanganan laporan terpisah / unlinked
        // UPDATE PERMANEN: Menampilkan Jobdesk Baru untuk minggu berjalan & Jobdesk Lama untuk histori minggu lalu
        // UPDATE PERMANEN: Menarik Jobdesk Berdasarkan Rentang Tanggal Berlaku (Date Range Versioning)
        function getLaporanMingguanRev2($start_date, $end_date, $id)
    {
        $this->db->select('
            jd.id, 
            jd.deskripsi, 
            jd.nilai as nilai_isi,
            jd.point,
            jd.id_urut,
            jd.idPengguna,
            l.id as id_lap, 
            l.tanggal, 
            l.id_pengaju, 
            l.id_job,
            l.jobdesc, 
            l.jenis, 
            l.progress, 
            l.kendala,
            l.solusi, 
            l.status_pekerjaan, 
            l.link,
            l.ket_hasil, 
            l.pihak, 
            l.keterangan, 
            l.nilai,  
            l.nilai_b,  
            l.nilai_pencapaian_a,  
            l.nilai_pencapaian_b, 
            l.status, 
            l.created_at
        ');
        $this->db->from('jobdesc_detail jd');
        $this->db->join('jobdesc j', 'jd.id_jobdesc = j.id', 'left');
        $this->db->join('laporan l', "jd.id = l.id_job AND (l.tanggal IS NULL OR (l.tanggal >= '$start_date' AND l.tanggal <= '$end_date'))", 'left');
        $this->db->where('jd.idPengguna', $id);
        $this->db->where('jd.status', 1);
        
        // Match murni berdasarkan rentang tanggal berlaku jobdesk pada minggu yang dipilih
        $this->db->group_start();
            $this->db->where("j.tgl_mulai IS NULL OR (j.tgl_mulai <= '$end_date' AND (j.tgl_selesai IS NULL OR j.tgl_selesai >= '$start_date'))");
            $this->db->or_where('jd.id_jobdesc IS NULL');
        $this->db->group_end();

        // Menyaring status laporan agar status = 3 tidak muncul
        $this->db->group_start();
            $this->db->where('l.status !=', 3);
            $this->db->or_where('l.status IS NULL');
        $this->db->group_end();

        $this->db->order_by('jd.point', 'ASC');
        $this->db->order_by('jd.id_urut', 'ASC');
        $this->db->order_by('l.tanggal', 'ASC');

        return $this->db->get()->result();
    }



    function getPencapaianPerMingguRev2($start_date, $end_date, $id_pengguna)
    {
        $this->db->select('
        id, 
        tanggal, 
        id_pengguna, 
        detail, 
        link,
        keterangan,
        nilai_a,
        nilai_b,
        catatan_a,
        catatan_b,
        created_at
    ');
        $this->db->from('pencapaian');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->where('id_pengguna', $id_pengguna);
        $this->db->order_by('tanggal', 'ASC');

        return $this->db->get()->result();
    }


    public function saveOrUpdateNilaiAdm($data)
    {
        // Cek apakah data sudah ada berdasarkan kombinasi unik
        $this->db->where('point', $data['point']);
        $this->db->where('id_pengguna', $data['id_pengguna']);
        $this->db->where('date', $data['date']);

        $query = $this->db->get('nilai_point');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, update nilai_a saja
            $this->db->where('point', $data['point']);
            $this->db->where('id_pengguna', $data['id_pengguna']);
            $this->db->where('date', $data['date']);
            $this->db->update('nilai_point', [
                'nilai_a' => $data['nilai_a']
            ]);
        } else {
            // Jika belum ada, insert baru
            $this->db->insert('nilai_point', $data);
        }
    }

    public function saveOrUpdateNilai($data)
    {
        // Cek apakah data sudah ada berdasarkan kombinasi unik
        $this->db->where('point', $data['point']);
        $this->db->where('id_pengguna', $data['id_pengguna']);
        $this->db->where('date', $data['date']);

        $query = $this->db->get('nilai_point');

        if ($query->num_rows() > 0) {
            // Jika data sudah ada, update nilai_b saja
            $this->db->where('point', $data['point']);
            $this->db->where('id_pengguna', $data['id_pengguna']);
            $this->db->where('date', $data['date']);
            $this->db->update('nilai_point', [
                'nilai_b' => $data['nilai_b']
            ]);
        } else {
            // Jika belum ada, insert baru
            $this->db->insert('nilai_point', $data);
        }
    }

    public function saveOrUpdateNilaiUmumAdm($data)
    {
        $this->db->where('id_pengguna', $data['id_pengguna']);
        $this->db->where('date', $data['date']);
        $query = $this->db->get('penilaian_umum');

        if ($query->num_rows() > 0) {
            // Data sudah ada, update semua nilaib1-5
            $this->db->where('id_pengguna', $data['id_pengguna']);
            $this->db->where('date', $data['date']);
            $this->db->update('penilaian_umum', [
                'nilaia1' => $data['nilaia1'],
                'nilaia2' => $data['nilaia2'],
                'nilaia3' => $data['nilaia3'],
                'nilaia4' => $data['nilaia4'],
                'nilaia5' => $data['nilaia5'],
                'catatana1' => $data['catatana1'],
                'catatana2' => $data['catatana2'],
                'catatana3' => $data['catatana3'],
                'catatana4' => $data['catatana4'],
                'catatana5' => $data['catatana5']
            ]);
        } else {
            // Belum ada, insert baru
            $this->db->insert('penilaian_umum', $data);
        }
    }

    public function saveOrUpdateNilaiUmum($data)
    {
        $this->db->where('id_pengguna', $data['id_pengguna']);
        $this->db->where('date', $data['date']);
        $query = $this->db->get('penilaian_umum');

        if ($query->num_rows() > 0) {
            // Data sudah ada, update semua nilaib1-5
            $this->db->where('id_pengguna', $data['id_pengguna']);
            $this->db->where('date', $data['date']);
            $this->db->update('penilaian_umum', [
                'nilaib1' => $data['nilaib1'],
                'nilaib2' => $data['nilaib2'],
                'nilaib3' => $data['nilaib3'],
                'nilaib4' => $data['nilaib4'],
                'nilaib5' => $data['nilaib5'],
                'catatanb1' => $data['catatanb1'],
                'catatanb2' => $data['catatanb2'],
                'catatanb3' => $data['catatanb3'],
                'catatanb4' => $data['catatanb4'],
                'catatanb5' => $data['catatanb5']
            ]);
        } else {
            // Belum ada, insert baru
            $this->db->insert('penilaian_umum', $data);
        }
    }

    public function getPenilaianUmum($id_pengguna, $tanggal)
    {
        return $this->db->get_where('penilaian_umum', [
            'id_pengguna' => $id_pengguna,
            'date' => $tanggal
        ])->row();
    }



    public function getAllPencapaianByTgl11($pengguna_id = NULL, $tglawal, $tglakhir)
    {
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db
            ->select('
            id,
            id_pengguna,
            tanggal,
            detail,
            link,
            keterangan,
            nilai_a,
            nilai_b,
            catatan_a,
            catatan_b
        ')
            ->from('pencapaian')
            ->where('tanggal >=', date('Y-m-d', strtotime($tglawal)))
            ->where('tanggal <=', date('Y-m-d', strtotime($tglakhir)));

        if ($pengguna_id != NULL) {
            $query->where('id_pengguna', $pengguna_id);
        }

        return $query->get()->result();
    }

    public function getAllPencapaianByTgl($pengguna_id = NULL, $tglawal, $tglakhir)
    {
        $this->db->order_by('pencapaian.tanggal', 'ASC');

        $query = $this->db
            ->select('
            pencapaian.id,
            pencapaian.id_pengguna,
            pencapaian.tanggal,
            pencapaian.detail,
            pencapaian.link,
            pencapaian.keterangan,
            pencapaian.nilai_a,
            pencapaian.nilai_b,
            pencapaian.catatan_a,
            pencapaian.catatan_b,
            pengguna.nama AS nama
        ')
            ->from('pencapaian')
            ->join('pengguna', 'pengguna.pengguna_id = pencapaian.id_pengguna', 'left')
            ->where('pencapaian.tanggal >=', date('Y-m-d', strtotime($tglawal)))
            ->where('pencapaian.tanggal <=', date('Y-m-d', strtotime($tglakhir)));

        if ($pengguna_id != NULL) {
            $query->where('pencapaian.id_pengguna', $pengguna_id);
        }

        return $query->get()->result();
    }

    function addPenilai($data)
    {
        $this->db->insert('laporan_penilai', $data);
        return $this->db->insert_id();
    }

    public function getKaryawanBelumIsiLaporanMingguan($monday = null, $friday = null)
    {
        if (empty($monday)) {
            $monday = date('Y-m-d', strtotime('monday this week'));
        }
        if (empty($friday)) {
            $friday = date('Y-m-d', strtotime('friday this week'));
        }

        $sql = "SELECT p.pengguna_id, p.nama, p.no_hp, p.jabatan
                FROM pengguna p
                WHERE p.is_active = 1 
                AND p.pengguna_id NOT IN (1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 749)
                AND p.pengguna_id NOT IN (
                    SELECT DISTINCT id_pengaju 
                    FROM laporan 
                    WHERE tanggal BETWEEN ? AND ? AND status != 3
                )
                ORDER BY p.nama ASC";

        $query = $this->db->query($sql, array($monday, $friday));
        return $query->result();
    }

    public function getLaporanPerBulan($start_date, $end_date, $id_pengguna)
    {
        $this->db->select('
            l.id, 
            l.tanggal, 
            l.id_pengaju, 
            l.id_job,
            l.jobdesc, 
            l.jenis, 
            l.progress, 
            l.kendala,
            l.solusi, 
            l.status_pekerjaan, 
            l.link,
            l.ket_hasil, 
            l.pihak, 
            l.keterangan, 
            l.nilai,  
            l.nilai_b,  
            l.status, 
            l.created_at,
            jd.deskripsi as jobdesc_master,
            jd.point
        ');
        $this->db->from('laporan l');
        $this->db->join('jobdesc_detail jd', 'l.id_job = jd.id', 'left');
        $this->db->where('l.tanggal >=', $start_date);
        $this->db->where('l.tanggal <=', $end_date);
        $this->db->where('l.id_pengaju', $id_pengguna);
        $this->db->where('l.status !=', 3);
        $rows = $this->db->get()->result();

        $cur = strtotime($start_date);
        $end = strtotime($end_date);
        if (!$cur || !$end || $cur > $end) {
            return $rows;
        }

        $existing = [];
        foreach ($rows as $r) {
            if (!empty($r->tanggal)) {
                $t = date('Y-m-d', strtotime($r->tanggal));
                $existing[$t][] = $r;
            }
        }

        $result = [];
        while ($cur <= $end) {
            $ymd = date('Y-m-d', $cur);
            if (!empty($existing[$ymd])) {
                foreach ($existing[$ymd] as $r) {
                    $result[] = $r;
                }
            } else {
                $obj = new stdClass();
                $obj->id = null;
                $obj->tanggal = $ymd;
                $obj->id_pengaju = $id_pengguna;
                $obj->id_job = null;
                $obj->jobdesc = '-';
                $obj->jobdesc_master = '-';
                $obj->point = null;
                $obj->jenis = '';
                $obj->progress = '';
                $obj->kendala = '';
                $obj->solusi = '';
                $obj->status_pekerjaan = '';
                $obj->link = '';
                $obj->ket_hasil = '';
                $obj->pihak = '';
                $obj->keterangan = '';
                $obj->nilai = null;
                $obj->nilai_b = null;
                $obj->status = 1;
                $obj->created_at = null;
                $result[] = $obj;
            }
            $cur = strtotime('+1 day', $cur);
        }

        return $result;
    }


    public function getPencapaianPerBulan($start_date, $end_date, $id_pengguna)
    {
        $this->db->select('
            id, 
            tanggal, 
            id_pengguna, 
            detail, 
            link,
            keterangan,
            nilai_a,
            nilai_b,
            catatan_a,
            catatan_b,
            created_at
        ');
        $this->db->from('pencapaian');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->where('id_pengguna', $id_pengguna);
        $this->db->order_by('COALESCE(tanggal, created_at) ASC', '', FALSE);

        return $this->db->get()->result();
    }

    public function getRekapStatusBulan($start_date, $end_date, $id_pengguna)
    {
        $sql = "SELECT 
                    COUNT(*) as total_pekerjaan,
                    SUM(CASE WHEN UPPER(status_pekerjaan) LIKE '%SELESAI%' THEN 1 ELSE 0 END) as total_selesai,
                    SUM(CASE WHEN UPPER(status_pekerjaan) LIKE '%PROSES%' THEN 1 ELSE 0 END) as total_proses,
                    SUM(CASE WHEN UPPER(status_pekerjaan) LIKE '%KENDALA%' OR UPPER(status_pekerjaan) LIKE '%PENDING%' OR UPPER(status_pekerjaan) LIKE '%TIDAK%' THEN 1 ELSE 0 END) as total_kendala
                FROM laporan 
                WHERE (tanggal BETWEEN ? AND ?)
                AND id_pengaju = ? 
                AND status != 3";

        $query = $this->db->query($sql, array($start_date, $end_date, $id_pengguna));
        return $query->row();
    }
}

