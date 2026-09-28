<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_anggota extends CI_Model
{

    function getByWhere($dt, $mode = false)
    {
        if ($mode == 'v2') {
            $this->db->select('
                a.anggota_id,
                a.nama,
                k.nama_kelas,
                k.kelas_id,
                a.is_active
            ');
        } else {
            $this->db->select('
                a.*,
                w.kecamatan,
                w.kelurahan,
                g.kode_grup,
                g.guru as guru_grup_id,
                k.nama_kelas,
                a2.nama as req_by_guru_nama, 
                a3.nama as guru_grup,
                a4.nama as pemberi_catatan_khusus_nama,
                k.kelas_id,
                rk.tmt,
                rk.riwayatkelas_created
            ');
        }
        $this->db->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left');
        $this->db->join('grup g', 'g.grup_id = ga.grup_id', 'left');
        $this->db->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left');
        $this->db->join('kelas k', 'k.kelas_id = rk.kelas_id');
        $this->db->join('anggota a2', 'a2.anggota_id = ga.req_by_guru', 'left');
        $this->db->join('anggota a3', 'a3.anggota_id = g.guru', 'left');
        $this->db->join('anggota a4', 'a4.anggota_id = a.pemberi_catatan_khusus', 'left');
        $this->db->join('wilayah w', 'w.wilayah_id = a.wilayah_id', 'left');
        return $this->db->get_where('anggota a', $dt)->result();
    }

    function getCurrentGrup($anggota_id)
    {

        $this->db->select('a.nama,g.grup_id,g.kode_grup');
        $this->db->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left');
        $this->db->join('grup g', 'g.grup_id = ga.grup_id', 'left');
        return $this->db->get_where('anggota a', ['a.anggota_id' => $anggota_id])->result();
    }

    function getAnggotaNotPesertaKegiatan($kegiatan_id)
    {

        $this->db->where("a.status = 1 and a.verifikasi = 1 and a.anggota_id NOT IN (SELECT anggota_id from kegiatan_peserta where kegiatan_id = '" . $kegiatan_id . "' and status = '1')");

        if ($this->input->post('jenis_kelamin'))
            $this->db->where('a.jenis_kelamin', $this->input->post('jenis_kelamin', TRUE));

        if ($this->input->post('kelas'))
            $this->db->where('k.kelas_id', decrypt($this->input->post('kelas', TRUE)));

        $this->db->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left');
        $this->db->join('grup g', 'g.grup_id = ga.grup_id', 'left');
        $this->db->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left');
        $this->db->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left');

        $this->db->select('a.anggota_id, a.nama');
        return $this->db->get('anggota a')->result();
    }

    function cekDuplikasiAnggota($data)
    {
        $query = 'SELECT * FROM `anggota` WHERE `email` = ? AND (`verifikasi` IS NULL OR `verifikasi` = 1) AND `status` = 1';
        $hasil = $this->db->query($query, array($data))->result();
        return $hasil;
    }

    function getAnggotaBySearch()
    {

        $q = $this->input->post('q', TRUE);
        $query = '';

        //search ketua by kelas
        if (isset($_POST['grup_kelas'])){
            $this->db->where('k.kelas_id >='.$_POST['grup_kelas']);
        }
        else {
            if (isStafAdminPelopor()) {
                $this->datatables->where_in("k.kelas_id", [5, 6, 7]);
            } else if (isStafAdminPendukung()) {
                $this->datatables->where_in("k.kelas_id", [1, 2]);
            } else if (isStafAdminPenggerak()) {
                $this->datatables->where_in("k.kelas_id", [3, 4]);
            }
        }

        if (isKetua())
            $this->db->where('g.guru', sessAnggotaId());


            
        $this->db->select('a.*,k.kelas_id,k.nama_kelas');
        $this->db->like('a.nama', $q, 'both');
        $this->db->where('a.status = 1 AND a.verifikasi = 1 ' . $query);
        $this->db->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left');
        $this->db->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left');
        $this->db->join('grup_anggota ga', 'a.latest_grupanggota_id = ga.grupanggota_id', 'left');
        $this->db->join('grup g', 'ga.grup_id = g.grup_id', 'left');
        $this->db->order_by('a.nama', 'asc');
        return $this->db->get('anggota a')->result();
    }

    function countAnggotaBySearch()
    {
        $q = $this->input->post('q', TRUE);
        $query = '';
        if (isset($_POST['kelas']))
            $query .= ' and k.kelas >= ' . $this->input->post('kelas', TRUE);

        $this->db->select('COUNT(*) as total');
        $this->db->from('anggota a');
        $this->db->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left');
        $this->db->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left');
        $this->db->where('a.status = 1 AND a.verifikasi = 1 ' . $query);
        $this->db->like('nama', $q);
        return $this->db->get()->result();
    }

    function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('anggota', $data);
    }

    function update($id, $data)
    {
        $this->db->where('anggota_id', $id);
        $this->db->update('anggota', $data);
    }

    function add($data)
    {
        $this->db->insert('anggota', $data);
        $this->exceptions->checkForError();
    }

    function getAllAnggota()
    {
        if (isStafAdminPelopor()) {
            $this->datatables->where_in("k.kelas_id", [5, 6, 7]);
        } else if (isStafAdminPendukung()) {
            $this->datatables->where_in("k.kelas_id", [1, 2]);
        } else if (isStafAdminPenggerak()) {
            $this->datatables->where_in("k.kelas_id", [3, 4]);
        }

        if ($this->input->post('filter_jenis_kelamin'))
            $this->datatables->where('a.jenis_kelamin', $this->input->post('filter_jenis_kelamin', TRUE));

        if ($this->input->post('filter_kelas')) {
            $this->datatables->where('k.kelas_id', decrypt($this->input->post('filter_kelas', TRUE)));
        } else {
            if (isStafAdmin()) {
                $this->datatables->where('k.kelas <= 5');
            }
        }

        if ($this->input->post('filter_verifikasi')) {
            $stat = $this->input->post('filter_verifikasi', TRUE);
            if ($stat == 'Aktif') {
                $this->datatables->where('a.verifikasi', 1);
                $this->datatables->where('a.is_active', 1);
            } else if ($stat == 'Non Aktif') {
                $this->datatables->where('a.verifikasi', 1);
                $this->datatables->where('a.is_active <>', 1);
            } else if ($stat == 'Pendaftaran Ditolak') {
                $this->datatables->where('a.verifikasi', 2);
            } else if ($stat == 'Menunggu Verifikasi') {
                $this->datatables->where('a.verifikasi', null);
            }
        }

        if ($this->input->post('filter_kode_grup'))
            $this->datatables->where('g.kode_grup', $this->input->post('filter_kode_grup', TRUE));

        return $this->datatables
            ->select("  
            a.anggota_id,
            a.nama,
            k.nama_kelas,
            a.jenis_kelamin,
            a.is_active,
            g.kode_grup,
            a.verifikasi,
            a.latitude,
            a.longitude,
            a.lokasi_pin,
            a.no_hp,
            a.status,
            g.grup_id,
            a.nama as guru_pemberi_catatan_khusus
        ")
            ->from('anggota a')
            ->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left')
            ->join('grup g', 'g.grup_id = ga.grup_id', 'left')
            ->join('anggota a1', 'a1.anggota_id = a.pemberi_catatan_khusus', 'left')
            ->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left')
            ->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left')
            ->where('a.status = 1')
            ->generate();
    }

    function getKetuaByKelas($data)
    {
        $q = $this->input->post('q', TRUE);
        $query = '';

        if (isset($_POST['kelas']))
            $query .= ' and k.kelas >= ' . $this->input->post('kelas', TRUE);
        else {
            if (isStafAdminPelopor()) {
                $this->datatables->where_in("k.kelas_id", [5, 6, 7]);
            } else if (isStafAdminPendukung()) {
                $this->datatables->where_in("k.kelas_id", [1, 2]);
            } else if (isStafAdminPenggerak()) {
                $this->datatables->where_in("k.kelas_id", [3, 4]);
            }
        }
        $this->db->like('a.nama', $q, 'both');

        $this->db->where('k.kelas_id >='.$data);
        $this->db->where('a.status = 1 AND a.verifikasi = 1 ' . $query);
        $this->db->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left');
        $this->db->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left');
        $this->db->join('grup_anggota ga', 'a.latest_grupanggota_id = ga.grupanggota_id', 'left');
        $this->db->join('grup g', 'ga.grup_id = g.grup_id', 'left');
        $this->db->order_by('a.nama', 'asc');
        return $this->db->get('anggota a')->result();
    }

    function getAnggotaWithGroup()
    {
        if ($this->input->post('jenis_kelamin'))
            $this->datatables->where('a.jenis_kelamin', $this->input->post('jenis_kelamin', TRUE));

        if ($this->input->post('kelas_id')) {
            $kelas_id = decrypt($this->input->post('kelas_id', TRUE));
            $this->datatables->where('k.kelas_id', $kelas_id);
        }

        return $this->datatables
            ->select("  
            a.anggota_id,
            a.nama,
            a.jenis_kelamin,
            k.kelas,
            k.nama_kelas,
            a.status,
            g.grup_id,
            g.kode_grup,
            a.latest_grupanggota_id
            
        ")
            ->from('anggota a')
            ->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left')
            ->join('grup g', 'g.grup_id = ga.grup_id', 'left')
            ->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left')
            ->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left')
            ->where('a.status = 1 and a.verifikasi=1')
            ->generate();
    }

    function getAnggotaForLaporan()
    {
        if ($this->input->post('filter_jenis_kelamin'))
            $this->datatables->where('a.jenis_kelamin', $this->input->post('filter_jenis_kelamin', TRUE));

        if (isset($_POST['filter_kelas']))
            $this->datatables->like('k.kelas', $this->input->post('filter_kelas', TRUE));

        if ($this->input->post('filter_kode_grup'))
            $this->datatables->where('g.kode_grup', $this->input->post('filter_kode_grup', TRUE));


        if ($this->input->post('filter_guru_grup'))
            $this->datatables->where('g.guru', decrypt($this->input->post('filter_guru_grup', TRUE)));
        else if (isKetua()) {
            $this->datatables->where('g.guru', sessAnggotaId());
        }


        $this->db->select('
            a.*,
            g.jenis_kelamin grup_jk, 
            g.kode_grup as kode_grup_murid, 
            g2.kode_grup as kode_grup_guru, g2.guru guru_grup,
            k.nama_kelas,
            a.nama as guru_pemberi_catatan_khusus,
            w.kelurahan, w.kecamatan
        ');
        $this->db->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left');
        $this->db->join('grup g', 'g.grup_id = ga.grup_id', 'left');
        $this->db->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left');
        $this->db->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left');
        $this->db->join('grup g2', 'g2.guru = a.anggota_id', 'left');
        $this->db->join('wilayah w', 'w.wilayah_id = a.wilayah_id', 'left');
        $this->db->order_by('a.verifikasi', 'asc');
        $this->db->order_by('a.nama', 'asc');
        return $this->db->get_where('anggota a', array('a.status' => 1))->result();
    }

    function getAllAnggotaVerifForLaporanKegiatan()
    {
        $tgl_awal  = date_db_format($this->input->post('tgl_awal'));
        $tgl_akhir = date_db_format($this->input->post('tgl_akhir'));

        if ($this->input->post('kelas_id'))
            $this->datatables->where('k.kelas_id', decrypt($this->input->post('kelas_id')));
        else {
            if (isStafAdminPelopor()) {
                $this->datatables->where_in("k.kelas_id", [5, 6, 7]);
            } else if (isStafAdminPendukung()) {
                $this->datatables->where_in("k.kelas_id", [1, 2]);
            } else if (isStafAdminPenggerak()) {
                $this->datatables->where_in("k.kelas_id", [3, 4]);
            }
        }

        if (isKetua())
            $this->datatables->where('g.guru', sessAnggotaId());

        else if (isAnggota())
            $this->datatables->where('a.anggota_id', sessAnggotaId());

        return $this->datatables
            ->select("  
            a.anggota_id,
            a.nama,
            a.jenis_kelamin,
            g.kode_grup,
            k.nama_kelas,
            k.kelas,
            (
                SELECT COUNT(*) 
                FROM kegiatan_peserta ea
                JOIN kegiatan e on e.kegiatan_id = ea.kegiatan_id
                where ea.anggota_id = a.anggota_id and e.status = 1 and ea.status = 1 and e.tgl_mulai BETWEEN '" . $tgl_awal . "' and '" . $tgl_akhir . "'
            ) as peserta_kegiatan,
            (
                SELECT COUNT(*) 
                FROM kegiatan_peserta ea
                JOIN kegiatan e on e.kegiatan_id = ea.kegiatan_id
                where ea.anggota_id = a.anggota_id and e.status = 1 and ea.status = 1 and e.tgl_mulai BETWEEN '" . $tgl_awal . "' and '" . $tgl_akhir . "' and ea.status_hadir = 'Hadir'
            ) as kegiatan_hadir,
            (
                SELECT COUNT(*) 
                FROM kegiatan_peserta ea
                JOIN kegiatan e on e.kegiatan_id = ea.kegiatan_id
                where ea.anggota_id = a.anggota_id and e.status = 1 and ea.status = 1 and e.tgl_mulai BETWEEN '" . $tgl_awal . "' and '" . $tgl_akhir . "' and ea.status_hadir is null
            ) as kegiatan_tidak_hadir,
        ")
            ->from('anggota a')
            ->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left')
            ->join('grup g', 'g.grup_id = ga.grup_id', 'left')
            ->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left')
            ->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left')
            ->where('a.status', 1)
            ->where('a.verifikasi', 1)
            ->generate();
    }

    function getAllAnggotaVerifForLogBook()
    {

        if ($this->input->post('kelas_id'))
            $this->datatables->where('k.kelas_id', decrypt($this->input->post('kelas_id')));
        else {
            if (isStafAdminPelopor()) {
                $this->datatables->where_in("k.kelas_id", [5, 6, 7]);
            } else if (isStafAdminPendukung()) {
                $this->datatables->where_in("k.kelas_id", [1, 2]);
            } else if (isStafAdminPenggerak()) {
                $this->datatables->where_in("k.kelas_id", [3, 4]);
            }
        }

        if ($this->input->post('jenis_kelamin')) {
            $this->datatables->where('a.jenis_kelamin', $this->input->post('jenis_kelamin'));
        }

        if (isKetua())
            $this->datatables->where('g.guru', sessAnggotaId());

        // else if(isAnggota())
        //     $this->datatables->where('a.anggota_id',sessAnggotaId());

        return $this->datatables
            ->select("  
            a.anggota_id,
            a.nama,
            a.jenis_kelamin,
            k.kelas,
            g.kode_grup,
            (SELECT count(*) FROM anggota_logbook where status = 1 and anggota_id = a.anggota_id ) as total_pekan_logbook,
            k.nama_kelas,
            a.status
        ")
            ->from('anggota a')
            ->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left')
            ->join('grup g', 'g.grup_id = ga.grup_id', 'left')
            ->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left')
            ->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left')
            ->where('a.status', 1)
            ->where('a.verifikasi', 1)
            ->generate();
    }

    function getAllAnggotaVerifForIuran()
    {
        $bulan = $this->input->post('filter_month', TRUE);
        $year = $this->input->post('filter_year', TRUE);

        if ($this->input->post('filter_kelas_id'))
            $this->datatables->where('k.kelas_id', decrypt($this->input->post('filter_kelas_id', TRUE)));
        else {
            if (isStafAdminPelopor()) {
                $this->datatables->where_in("k.kelas_id", [5, 6, 7]);
            } else if (isStafAdminPendukung()) {
                $this->datatables->where_in("k.kelas_id", [1, 2]);
            } else if (isStafAdminPenggerak()) {
                $this->datatables->where_in("k.kelas_id", [3, 4]);
            }
        }

        if ($this->input->post('jenis_kelamin')) {
            $this->datatables->where('a.jenis_kelamin', $this->input->post('jenis_kelamin', TRUE));
        }

        if ($this->input->post('filter_kode_grup'))
            $this->datatables->where('g.kode_grup', $this->input->post('filter_kode_grup', TRUE));


        return $this->datatables

            ->select("  
                a.anggota_id,
                a.nama,
                a.jenis_kelamin,
                k.nama_kelas,
                ai.jumlah,
                ai.tgl_bayar,
                ai.anggotaiuran_created,
                k.kelas,
                ai.year,
                ai.month,
                ai.upload_by,
            ")
            ->from('anggota a')
            ->join('grup_anggota ga', 'ga.grupanggota_id = a.latest_grupanggota_id', 'left')
            ->join('grup g', 'g.grup_id = ga.grup_id', 'left')
            ->join('riwayat_kelas rk', 'rk.riwayatkelas_id = a.latest_riwayatkelas_id', 'left')
            ->join('kelas k', 'k.kelas_id = rk.kelas_id', 'left')
            ->join('anggota_iuran ai', "a.anggota_id = ai.anggota_id and ai.year = '" . $year . "' and ai.month = '" . $bulan . "'", 'left')
            ->where('a.status', 1)
            ->where('a.verifikasi', 1)
            ->generate();
    }
}
