<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pengguna extends CI_Model
{
    function resetVoting($id)
    {
        $data = array(
            'deleted' => 1
        );
        $this->db->where('pengguna_id', $id);
        $this->db->where('deleted', 0);
        $this->db->update('voting', $data);
    }
    function addVoting($data)
    {
        $this->db->insert('voting', $data);
    }

    function updateVoting($id, $data)
    {
        $this->db->where('pengguna_id', $id);
        $this->db->where('deleted', 0);
        $this->db->update('voting', $data);
    }

    function getTotalVoting()
    {
        $result = $this->db->get_where('voting', array('deleted' => 0));
        return $result->num_rows();

        //$result = $this->db->get_where('voting',array('deleted' => 0))->join('pengguna','pengguna.pengguna_id=voting.pengguna_id');
        //return $result->num_rows();
    }
    function getAllHasilVoting()
    {
        $this->db->order_by('nama', 'ASC');
        return $this->datatables
            ->select('pengguna.pengguna_id,pengguna.nama,pengguna.jabatan')
            ->from('pengguna')
            ->where('pengguna.is_active = 1')
            ->where('pengguna.pengguna_id not in (1,54,55,56,57,58,69,70,72,74,77,79,81,83,84,86,95,107,109,110,714)')
            ->generate();
    }

    function getAllHasilVotingDicipline()
    {
        $this->db->order_by('total', 'DESC');
        return $this->datatables
            ->select('pengguna.nama as nama,count(dicipline) as total')
            ->from('voting')
            ->join('pengguna', 'voting.dicipline=pengguna.pengguna_id')
            ->where('dicipline<>0')
            ->where('deleted=0')
            ->where('pengguna.no_pegawai is not null')
            ->group_by("dicipline")
            ->generate();
    }
    function getAllHasilVotingProfesional()
    {
        $this->db->order_by('total', 'DESC');
        return $this->datatables
            ->select('pengguna.nama as nama,count(profesional) as total')
            ->from('voting')
            ->join('pengguna', 'voting.profesional=pengguna.pengguna_id')
            ->where('profesional<>0')
            ->where('deleted=0')
            ->where('pengguna.no_pegawai is not null')
            ->group_by("profesional")
            ->generate();
    }
    function getAllHasilVotingCreative()
    {
        $this->db->order_by('total', 'DESC');
        return $this->datatables
            ->select('pengguna.nama as nama,count(creative) as total')
            ->from('voting')
            ->join('pengguna', 'voting.creative=pengguna.pengguna_id')
            ->where('creative<>0')
            ->where('deleted=0')
            ->where('pengguna.no_pegawai is not null')
            ->group_by("creative")
            ->generate();
    }
    function getAllHasilVotingSales()
    {
        $this->db->order_by('total', 'DESC');
        return $this->datatables
            ->select('pengguna.nama as nama,count(sales) as total')
            ->from('voting')
            ->join('pengguna', 'voting.sales=pengguna.pengguna_id')
            ->where('sales<>0')
            ->where('deleted=0')
            ->where('pengguna.no_pegawai is not null')
            ->group_by("sales")
            ->generate();
    }
    function getAllHasilVotingOperational()
    {
        $this->db->order_by('total', 'DESC');
        return $this->datatables
            ->select('pengguna.nama as nama,count(operational) as total')
            ->from('voting')
            ->join('pengguna', 'voting.operational=pengguna.pengguna_id')
            ->where('operational<>0')
            ->where('deleted=0')
            ->where('pengguna.no_pegawai is not null')
            ->group_by("operational")
            ->generate();
    }
    function getAllHasilVotingBestofyear()
    {
        $this->db->order_by('total', 'DESC');
        return $this->datatables
            ->select('pengguna.nama as nama,count(bestofyear) as total')
            ->from('voting')
            ->join('pengguna', 'voting.bestofyear=pengguna.pengguna_id')
            ->where('bestofyear<>0')
            ->where('deleted=0')
            ->where('pengguna.no_pegawai is not null')
            ->group_by("bestofyear")
            ->generate();
    }
    function getAllHasilVotingSudahVoting()
    {
        $this->db->order_by('pengguna.nama', 'ASC');
        return $this->datatables
            ->select('pengguna.nama as nama,dicipline,profesional,creative,sales,operational,bestofyear')
            ->from('pengguna')
            ->join('voting', 'voting.pengguna_id=pengguna.pengguna_id AND voting.deleted=0', 'left')
            ->where('pengguna.is_active = 1')
            ->where('pengguna.pengguna_id not in (1,54,55,56,57,58,69,70,72,74,77,79,81,83,84,86,95,107,109,110,714)')
            ->generate();
    }

    function getAllPenggunaVoting()
    {
        $this->db->order_by('nama', 'ASC');
        return $this->datatables
            ->select('pengguna.pengguna_id,pengguna.nama,pengguna.jabatan')
            ->from('pengguna')
            ->where('pengguna.is_active = 1')
            ->where('pengguna.pengguna_id not in (1,54,55,56,57,58,69,70,72,74,77,79,81,83,84,86,95,107,109,110,714)')
            ->generate();
    }
    function getAllPenggunaVotingKosong($id)
    {
        $this->db->order_by('nama', 'ASC');
        return $this->datatables
            ->select('pengguna.pengguna_id,
                      pengguna.nama,pengguna.jabatan,
                      (SELECT a.nama FROM pengguna a WHERE a.pengguna_id=voting.dicipline) AS dicipline,
                      (SELECT b.nama FROM pengguna b WHERE b.pengguna_id=voting.profesional) AS profesional,
                      (SELECT c.nama FROM pengguna c WHERE c.pengguna_id=voting.creative) AS creative,
                      (SELECT d.nama FROM pengguna d WHERE d.pengguna_id=voting.sales) AS sales,
                      (SELECT e.nama FROM pengguna e WHERE e.pengguna_id=voting.operational) AS operational,
                      (SELECT f.nama FROM pengguna f WHERE f.pengguna_id=voting.bestofyear) AS bestofyear')
            ->from('voting')
            ->join('pengguna', 'pengguna.pengguna_id=voting.pengguna_id')
            ->where('voting.pengguna_id', $id)
            ->where('voting.deleted', 0)
            ->generate();
    }


    function getBywhere($where)
    {
        $this->db->select('*');
        $this->db->where($where);
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get('pengguna p')->result();
    }

    function getBywhereStatus($where)
    {
        $this->db->select('
                            t.id_tiket,
							t.kode_tiket,
                            t.id_penerima,
                            t.subject,
                            t.prioritas,
                            t.deskripsi,
                            t.file_pendukung,
                            t.file_invoice as invoice,
                            t.pelanggan,
                            t.waktu_mulai,
                            t.waktu_selesai,
							t.catatan_visit,
							t.status_visit as stat_visit,
							t.file_pengajuan_biaya,
							t.file_pengajuan_gokop as file_gocorp,
							t.file_laporan_teknisi,
							t.file_laporan_biaya,
							t.file_feedback_cust as feedcust,
							t.deskripsi_feedback_cust as desk_feedcust,
                            t.status_tiket,
							t.status_surat_dinas as stat_sudin,
							t.file_surat_dinas as link_sudin,
							t.waktu_selesai as deadline,
                            p2.nama,
                            p2.pengguna_id,
                            p2.level,
                            p2.status,
                            p2.is_active
                        ')
            ->from('tiket t')
            ->join('pengguna p2', 't.id_penerima=p2.pengguna_id', 'left')
            ->where($where)
            // ->where('t.id_tiket IN (SELECT MAX(t.id_tiket) FROM tiket t GROUP BY t.id_penerima)')
            ->order_by('p2.nama', 'ASC');
        return $this->db->get()->result();
    }

    function getBywhereStatus1()
    {
        $sql = 'SELECT  p2.nama,
                            p2.pengguna_id,
                            p2.level,
                            p2.status,
                            p2.is_active,
                            t.id_penerima,
                            t.subject,
                            t.pelanggan,
                            t.status_tiket,
                            t.id_tiket,
                            t.kode_tiket,
                            t.status_tiket
        FROM pengguna p2
        LEFT JOIN (
                SELECT id_penerima, MAX(id_tiket) AS max_id_tiket
                FROM tiket
                GROUP BY id_penerima
            ) max_tiket ON p2.pengguna_id = max_tiket.id_penerima
            LEFT JOIN tiket t ON max_tiket.max_id_tiket = t.id_tiket
            WHERE p2.is_active = 1 AND p2.status = 1 AND p2.hirarki=4
            ORDER BY p2.nama ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }

    /*LEFT JOIN tiket t ON p2.pengguna_id = t.id_penerima
        WHERE p2.is_active=1  AND p2.status=1
        GROUP BY p2.nama
        ORDER BY p2.nama ASC';*/

    function countTiket()
    {
        $sql = 'SELECT * FROM tiket 
        LEFT JOIN pengguna ON tiket.id_penerima=pengguna.pengguna_id
        WHERE tiket.id_penerima IS NULL';
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function getBywherenotIn($where, $con)
    {
        $this->db->where($where);
        $this->db->where_not_in('p.pengguna_id', $con);
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get('pengguna p')->result();
    }

    function getBywhereFreelance()
    {
        $this->db->select('*');
        $this->db->where('p.pengguna_id = 77');
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get('pengguna p')->result();
    }

    function getBywhereSkor()
    {
        $this->db->select('*');
        $this->db->group_start();
        $this->db->where('p.pengguna_id', 15);
        $this->db->or_where('p.pengguna_id', 29);
        $this->db->group_end();
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get('pengguna p')->result();
    }

    function getKaryawan($where)
    {
        $this->db->where($where);
        $this->db->where('p.level !=', 'Ga');
        $this->db->where('p.pengguna_id !=', '47');
        $this->db->order_by('p.tgl_masuk', 'ASC');
        return $this->db->get('pengguna p')->result();
    }

    function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('pengguna', $data);
    }

    function getByJenis($jenis)
    {
        if ($jenis == '1_2')
            $this->db->where('p.level in (\'Administrator\',\'Staf Admin Pendukung\')');
        else if ($jenis == '3_4')
            $this->db->where('p.level in (\'Administrator\',\'Staf Admin Penggerak\')');
        else if ($jenis == '5_6_7')
            $this->db->where('p.level in (\'Administrator\',\'Staf Admin Pelopor\')');

        $this->db->where('p.status', 1);
        return $this->db->get('pengguna p')->result();
    }

    function cekDuplikasiAnggota($email)
    {
        $this->db->where('status', 1);
        return $this->db->get_where('pengguna', ['email' => $email])->result();
    }

    function getByNoHp($no_hp)
    {
        return $this->db->get_where('pengguna pg', array('pg.no_hp' => $no_hp, 'pg.status' => 1))->result();
    }

    function getByUsernameByPass($username, $pass)
    {
        return $this->db->get_where('pengguna', array('username' => $username, 'password' => $pass, 'status' => 1))->result();
    }


    function getById($id)
    {
        return $this->db->get_where('pengguna pg', array('pg.pengguna_id' => $id))->result();
    }

    function getIzinById($id)
    {
        $this->db->join('divisi', 'divisi.id_divisi=pg.id_divisi', 'left');
        return $this->db->select('pg.*,divisi.nama as divisi')->get_where('pengguna pg', array('pg.pengguna_id' => $id))->result();
    }

    function updatePengguna($id, $data)
    {
        $this->db->where('pengguna_id', $id);
        $this->db->update('pengguna', $data);
    }

    function addPengguna($data)
    {
        $this->db->insert('pengguna', $data);
    }

    function getPenggunaMarketing()
    {
        $raw = $this->db->get_where('pengguna pg', 'pg.id_divisi=3 and pg.is_active=1 or pg.pengguna_id in (72)')->result();
        $filtered = [];
        foreach ($raw as $m) {
            if ($m->pengguna_id == 72 || $m->pengguna_id == 77) {
                continue;
            }
            if ($m->pengguna_id == 54) {
                $m->nama = 'Kantor Pusat [Bob Ariyos, CRO & Buldani]';
            }
            if ($m->pengguna_id == 747) {
                $m->nama = 'After Sales Service';
            }
            if ($m->pengguna_id == 754) {
                $m->nama = 'VISILAB';
            }
            $filtered[] = $m;
        }
        return $filtered;
    }

    function getPenggunaTeknisi()
    {
        return $this->db->get_where('pengguna pg', 'pg.id_divisi=7 AND pg.is_active=1 AND pg.pengguna_id NOT IN (92, 94, 724, 737)')->result();
    }


    function getPenggunaMarketingFunnel()
    {
        $raw = $this->db->get_where('pengguna pg', 'pg.id_divisi=3  or pg.pengguna_id in (72)')->result();
        $filtered = [];
        foreach ($raw as $m) {
            if ($m->pengguna_id == 72 || $m->pengguna_id == 77) {
                continue;
            }
            if ($m->pengguna_id == 54) {
                $m->nama = 'Kantor Pusat [Bob Ariyos, CRO & Buldani]';
            }
            if ($m->pengguna_id == 747) {
                $m->nama = 'After Sales Service';
            }
            if ($m->pengguna_id == 754) {
                $m->nama = 'VISILAB';
            }
            $filtered[] = $m;
        }
        return $filtered;
    }

    function getAllPengguna()
    {
        $this->db->order_by('pg.nama', 'ASC');
        return $this->datatables
            ->select('  
            pg.pengguna_id,
            pg.nama,
            pg.status_karyawan,
            pg.jabatan,
            pg.email,
            pg.username,
             pg.terima_tunjangan_tt,
            pg.terima_tunjangan_kinerja,
            pg.terima_tunjangan_konsumsi,
            pg.terima_tunjangan_komunikasi,
            pg.terima_tunjangan_transportasi,
            pg.terima_tunjangan_jabatan,
             pg.terima_tunjangan_bbm,
            pg.level,
            pg.status,
            pg.status_approval,
            pg.is_active,
            (SELECT COUNT(*) FROM log where pengguna_id = pg.pengguna_id) as dependency
        ')
            ->from('pengguna pg')
            ->where('pg.status = 1')
            ->where('pg.pengguna_id != 1')
            ->generate();
    }

    function getAllPenggunaFiltered($filters = [])
    {
        $this->db->order_by('pg.nama', 'ASC');
        $dt = $this->datatables
            ->select('  
                pg.pengguna_id,
                pg.nama,
                pg.status_karyawan,
                pg.jabatan,
                pg.email,
                pg.username,
                pg.terima_tunjangan_tt,
                pg.terima_tunjangan_kinerja,
                pg.terima_tunjangan_konsumsi,
                pg.terima_tunjangan_komunikasi,
                pg.terima_tunjangan_transportasi,
                pg.terima_tunjangan_jabatan,
                pg.terima_tunjangan_bbm,
                pg.level,
                pg.status,
                pg.status_approval,
                pg.is_active,
                pg.id_divisi,
                (SELECT COUNT(*) FROM log where pengguna_id = pg.pengguna_id) as dependency
            ')
            ->from('pengguna pg')
            ->where('pg.status = 1')
            ->where('pg.pengguna_id != 1');

        // Apply filters
        if (!empty($filters['nama'])) {
            $dt->like('pg.nama', $filters['nama']);
        }

        if (!empty($filters['divisi'])) {
            $dt->where('pg.id_divisi', $filters['divisi']);
        }

        if (!empty($filters['status_karyawan'])) {
            $dt->where('LOWER(pg.status_karyawan)', strtolower($filters['status_karyawan']));
        }

        if (!empty($filters['level'])) {
            $dt->where('pg.level', $filters['level']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '' && $filters['is_active'] !== null) {
            $dt->where('pg.is_active', $filters['is_active']);
        }

        return $dt->generate();
    }

    //  function getAllPenggunaAktif()
    // {
    //     $this->db->order_by('pg.nama', 'ASC');
    //     return $this->datatables
    //         ->select('  
    //         pg.pengguna_id,
    //         pg.nama,
    //          pg.jabatan,
    //         pg.status_karyawan,
    //         pg.email,
    //         pg.username,
    //         (CASE WHEN pg.terima_tunjangan_tt IS NULL THEN 0 ELSE pg.terima_tunjangan_tt END) AS terima_tunjangan_tt,
    //         (CASE WHEN pg.terima_tunjangan_kinerja IS NULL THEN 0 ELSE pg.terima_tunjangan_kinerja END) AS terima_tunjangan_kinerja,
    //         (CASE WHEN pg.terima_tunjangan_konsumsi IS NULL THEN 0 ELSE pg.terima_tunjangan_konsumsi END) AS terima_tunjangan_konsumsi,
    //         (CASE WHEN pg.terima_tunjangan_komunikasi IS NULL THEN 0 ELSE pg.terima_tunjangan_komunikasi END) AS terima_tunjangan_komunikasi,
    //         (CASE WHEN pg.terima_tunjangan_transportasi IS NULL THEN 0 ELSE pg.terima_tunjangan_transportasi END) AS terima_tunjangan_transportasi,
    //         (CASE WHEN pg.terima_tunjangan_jabatan IS NULL THEN 0 ELSE pg.terima_tunjangan_jabatan END) AS terima_tunjangan_jabatan,
    //         (CASE WHEN pg.terima_tunjangan_bbm IS NULL THEN 0 ELSE pg.terima_tunjangan_bbm END) AS terima_tunjangan_bbm,
    //         pg.level,
    //         pg.status,
    //         pg.status_approval,
    //         pg.is_active,
    //         (SELECT COUNT(*) FROM log where pengguna_id = pg.pengguna_id) as dependency
    //     ')
    //         ->from('pengguna pg')
    //         ->where('pg.status = 1')
    //         ->where('pg.is_active = 1')
    //         ->where('pg.pengguna_id != 1')
    //         ->generate();
    // }

    function getAllPenggunaAktif()
    {
        $this->db->order_by('pg.nama', 'ASC');
        return $this->datatables
            ->select('  
            pg.pengguna_id,
            pg.nama,
             pg.jabatan,
            pg.status_karyawan,
            pg.email,
            pg.username,
            pg.terima_tunjangan_tt,
            pg.terima_tunjangan_kinerja,
            pg.terima_tunjangan_konsumsi,
            pg.terima_tunjangan_komunikasi,
            pg.terima_tunjangan_transportasi,
            pg.terima_tunjangan_jabatan,
            pg.terima_tunjangan_bbm,
            pg.id_pendapatan_lain,
            pg.level,
            pg.status,
            pg.status_approval,
            pg.is_active,
            (SELECT COUNT(*) FROM log where pengguna_id = pg.pengguna_id) as dependency
        ')
            ->from('pengguna pg')
            ->where('pg.status = 1')
            ->where('pg.is_active = 1')
            ->where('pg.pengguna_id != 1')
            ->generate();
    }

    function getAllPenggunaAktifList()
    {
        $this->db->select('  
            pg.pengguna_id,
            pg.nama,
             pg.jabatan,
            pg.status_karyawan,
            pg.email,
            pg.username,
            pg.terima_tunjangan_tt,
            pg.terima_tunjangan_kinerja,
            pg.terima_tunjangan_konsumsi,
            pg.terima_tunjangan_komunikasi,
            pg.terima_tunjangan_transportasi,
            pg.terima_tunjangan_jabatan,
            pg.terima_tunjangan_bbm,
            pg.id_pendapatan_lain,
            pg.level,
            pg.status,
            pg.status_approval,
            pg.is_active,
            (SELECT COUNT(*) FROM log where pengguna_id = pg.pengguna_id) as dependency
        ');
        $this->db->from('pengguna pg');
        $this->db->where('pg.status = 1');
        $this->db->where('pg.is_active = 1');
        $this->db->where('pg.pengguna_id != 1');
        $this->db->order_by('pg.nama', 'ASC');
        return $this->db->get()->result();
    }

    function getAllPenggunaFreelance()
    {
        $this->db->order_by('pg.nama', 'ASC');
        return $this->datatables
            ->select('  
            pg.pengguna_id,
            pg.nama,
             pg.jabatan,
            pg.status_karyawan,
            pg.email,
            pg.username,
            pg.terima_tunjangan_tt,
            pg.terima_tunjangan_kinerja,
            pg.terima_tunjangan_konsumsi,
            pg.terima_tunjangan_komunikasi,
            pg.terima_tunjangan_transportasi,
            pg.terima_tunjangan_jabatan,
            pg.terima_tunjangan_bbm,
            pg.id_pendapatan_lain,
            pg.level,
            pg.status,
            pg.status_approval,
            pg.is_active,
            (SELECT COUNT(*) FROM log where pengguna_id = pg.pengguna_id) as dependency
        ')
            ->from('pengguna pg')
            ->where('pg.status = 1')
            ->where('pg.is_active = 1')
            ->where('pg.pengguna_id = 77')
            ->generate();
    }

    function getAllPenggunaReal()
    {
        $this->db->order_by('pg.nama', 'ASC');
        return $this->datatables
            ->select('  
            pg.pengguna_id,
            pg.nama,
             pg.jabatan,
            pg.status_karyawan,
            pg.email,
            pg.username,
            pg.terima_tunjangan_tt,
            pg.terima_tunjangan_kinerja,
            pg.terima_tunjangan_konsumsi,
            pg.terima_tunjangan_komunikasi,
            pg.terima_tunjangan_transportasi,
            pg.terima_tunjangan_jabatan,
            pg.terima_tunjangan_bbm,
            pg.id_pendapatan_lain,
            pg.level,
            pg.status,
            pg.status_approval,
            pg.is_active,
            (SELECT COUNT(*) FROM log where pengguna_id = pg.pengguna_id) as dependency
        ')
            ->from('pengguna pg')
            ->where('pg.status = 1')
            ->where('pg.is_active = 1')
            ->where('pg.pengguna_id not in (58,47,84,714,77,79,110,87,72,70,81,69,83,107,86,74,57,727,1)')
            ->generate();
    }


    public function getAllPegawai()
    {
        $this->db->select('*');
        $this->db->from('pengguna pg');
        $this->db->where("pg.pengguna_id NOT IN (1, 727, 714, 84, 109, 110, 79, 54, 72, 81, 70, 58, 69, 57, 74, 56, 83, 55, 107, 86, 77, 743)", NULL, FALSE);
        $this->db->where('pg.status', 1);
        $this->db->where('pg.is_active', 1);
        $this->db->order_by('pg.nama', 'ASC');

        return $this->db->get()->result();
    }
}
