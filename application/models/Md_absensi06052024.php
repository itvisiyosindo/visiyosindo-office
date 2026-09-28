<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_absensi extends CI_Model
{
    function add($data)
    {
        $this->db->insert('absensi', $data);
    }
    function addbackup($data)
    {
        $this->db->insert('absensibackup', $data);
    }
    function updateByWhere($data2, $where)
    {
        $this->db->where($where);
        $this->db->update('absensi', $data2);
    }

    function countTerlambat($pengguna_id, $month)
    {
        $this->db->select('count(*) as total_terlambat');
        $this->db->like('data_created', $month);
        $this->db->where('type_absen', 'masuk');
        $this->db->where('status_absen', 'terlambat');
        $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }
    function countDinas($pengguna_id, $month)
    {
        $this->db->select('count(*) as total');
        $this->db->like('data_created', $month);
        $this->db->where('type_absen', 'masuk');
        $this->db->where('jenis_absen', 'Dinas');
        $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }
    function countCuti($pengguna_id, $month)
    {
        $this->db->select('count(*) as total');
        $this->db->like('data_created', $month);
        $this->db->where('type_absen', 'izin');
        $this->db->where('status_absen', 'cuti');
        $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }

    function countIzin($pengguna_id, $month)
    {
        $where = "absensi.perusahaan=".grantAccessForPerusahaan()." AND type_absen ='izin' AND (status_absen='izin' or status_absen='sakit')";
        $this->db->select('count(*) as total')
            ->like('data_created', $month)
            ->where($where);

        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }

    function daftar_hadirToday()
    {
        $data = $this->db->select('pengguna.nama')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where('type_absen', 'masuk')
            ->where('absensi.perusahaan',grantAccessForPerusahaan())
            ->get()->result();
        return $data;
    }

    function daftar_pulangToday()
    {
        $data = $this->db->select('pengguna.nama')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where('type_absen', 'keluar')
            ->where('absensi.perusahaan',grantAccessForPerusahaan())
            ->get()->result();
        return $data;
    }

    function daftar_terlambatToday()
    {
        $data = $this->db->select('pengguna.nama')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where('type_absen', 'masuk')
            ->where('status_absen', 'terlambat')
            ->where('absensi.perusahaan',grantAccessForPerusahaan())
            ->get()->result();
        return $data;
    }

    function daftar_izinToday($status)
    {
        $where = "absensi.perusahaan=".grantAccessForPerusahaan()." AND type_absen ='izin' AND (status_absen='" . $status . "')";
        $data = $this->db->select('pengguna.nama')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where($where)
            ->get()->result();
        return $data;
    }


    function countTerlambatToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'masuk');
            $this->db->where('status_absen', 'terlambat');
            $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        } else {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'masuk');
            $this->db->where('pengguna_id', $id_pengguna);
            $this->db->where('status_absen', 'terlambat');
            $this->db->like('month(data_created)', date('m', time()));
            $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        }
    }
    function countPresentToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'masuk');
            $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        } else {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'masuk');
            $this->db->where('pengguna_id', $id_pengguna);
            $this->db->like('month(data_created)', date('m', time()));
            $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        }
    }

    function countPulangToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'keluar');
            $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        } else {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'keluar');
            $this->db->where('pengguna_id', $id_pengguna);
            $this->db->like('month(data_created)', date('m', time()));
            $this->db->where('absensi.perusahaan',grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        }
    }

    function countIzinToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $where = "absensi.perusahaan=".grantAccessForPerusahaan()." AND type_absen ='izin' AND (status_absen='izin')";
            $this->db->select('count(*) as total')
                ->like('data_created', date('Y-m-d', time()))
                ->where($where);
            return $this->db->get('absensi')->result();
        } else {
            $where = "absensi.perusahaan=".grantAccessForPerusahaan()." AND type_absen ='izin' AND (status_absen='izin') AND pengguna_id=" . $id_pengguna;
            $this->db->select('count(*) as total')
                ->like('data_created', date('Y-m-d', time()))
                ->where($where);
            return $this->db->get('absensi')->result();
        }
    }

    function countSakitToday()
    {
        $where = "absensi.perusahaan=".grantAccessForPerusahaan()." AND type_absen ='izin' AND (status_absen='sakit')";
        $this->db->select('count(*) as total')
            ->like('data_created', date('Y-m-d', time()))
            ->where($where);
        return $this->db->get('absensi')->result();
    }
    function countCutiToday()
    {
        $where = "absensi.perusahaan=".grantAccessForPerusahaan()." AND type_absen ='izin' AND (status_absen='cuti')";
        $this->db->select('count(*) as total')
            ->like('data_created', date('Y-m-d', time()))
            ->where($where);
        return $this->db->get('absensi')->result();
    }

    function getAbsensibyDays($pengguna_id, $days, $type)
    {
        $this->db->select('*');
        $this->db->like('data_created', $days);
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'type_absen' => $type, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    function getJumlahDinas($pengguna_id, $days, $type)
    {
        $this->db->select('*');
        $this->db->like('data_created', $days);
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'jenis_absen' => $type, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }
    function getAbsensiTodayById()
    {
        // 12:00:00 dan 17:00:00 master data
        $config = $this->md_absensi_config->get();
        if (sessPenggunaId() == 94) {
            $pulang = date($config[0]->jam_keluar_malam);
            $masuk = date($config[0]->jam_masuk_malam);
            $boleh_absen = date($config[0]->boleh_absen_malam);
            //if (date("H:i:s") > $boleh_absen) {
            $pulangA = date("07:00:00");
            $pulangB = date("11:00:00");

            $masukA = date("15:00:00");
            $masukB = date("23:00:00");

            $pulangC = date("H:i:s");

            if($pulangC > $masukA && $pulangC < $masukB)  {
                $this->db->where('type_absen ', 'masuk');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            } else {
                $this->db->where('type_absen ', 'keluar');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            }
        } else {
            $pulang = date('l') == 'Saturday' ? date($config[0]->jam_keluar_sabtu) : date($config[0]->jam_keluar);
            if (date("H:i:s") < $pulang) {
                $this->db->where('type_absen ', 'masuk');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            } else {
                $this->db->where('type_absen ', 'keluar');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            }
        }

        $this->db->like('data_created', date('Y-m-d', time()));
        return $this->db->get_where('absensi', ['pengguna_id' => sessPenggunaId(),'perusahaan' => grantAccessForPerusahaan()])->result();
    }


    function getAbsensiIzinTodayById()
    {
        // 12:00:00 dan 17:00:00 master data

        $this->db->where('type_absen ', 'izin');

        $this->db->like('data_created', date('Y-m-d', time()));
        return $this->db->get_where('absensi', ['pengguna_id' => sessPenggunaId(),'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getAbsenMasukByPenggunaId($pengguna_id)
    {
        if ($this->input->post('filter_month') || $this->input->post('filter_date')) {
            if ($this->input->post('filter_month')) {
                $this->db->like("data_created", $this->input->post('filter_month'));
            }
            if ($this->input->post('filter_date')) {
                $this->db->like("data_created", $this->input->post('filter_date'));
            }
        } else {
            $this->db->like('data_created', date('Y-m-d', time()));
        }

        if ($this->input->post('filter_status')) {
            $this->datatables->where("status_absen", $this->input->post('filter_status'));
        }
        $this->db->where('type_absen ', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id,'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getAbsenIzinByPenggunaId($pengguna_id)
    {
        if ($this->input->post('filter_month') || $this->input->post('filter_date')) {
            if ($this->input->post('filter_month')) {
                $this->db->like("data_created", $this->input->post('filter_month'));
            }
            if ($this->input->post('filter_date')) {
                $this->db->like("data_created", $this->input->post('filter_date'));
            }
        } else {
            $this->db->like('data_created', date('Y-m-d', time()));
        }

        if ($this->input->post('filter_status')) {
            $this->datatables->where("status_absen", $this->input->post('filter_status'));
        }
        $this->db->where('type_absen ', 'izin');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id,'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getAbsenMasukById($id)
    {
        return $this->db->get_where('absensi', ['id_absensi' => $id,'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getTunjanganOnMonth($pengguna_id)
    {
        if ($this->input->post('filter_month')) {
            $this->datatables->where("DATE_FORMAT(data_created,'%Y-%m')", $this->input->post('filter_month'));
        } else {
            $this->datatables->where("DATE_FORMAT(data_created,'%Y-%m')", date('Y-m'));
        }
        // $this->db->where("DATE_FORMAT(data_created,'%W') !=", 'Saturday'); //hari sabtu tidak ada tunjangan
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id,'perusahaan' => grantAccessForPerusahaan()])->result();
    }
 public function getTunjanganByMonthDinas($pengguna_id, $month)
    {

        $this->datatables->where("DATE_FORMAT(data_created,'%Y-%m')", $month);

        // $this->db->where("DATE_FORMAT(data_created,'%W') !=", 'Saturday'); //hari sabtu tidak ada tunjangan
        $this->db->where('jenis_absen', 'kantor');
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id,'perusahaan' => grantAccessForPerusahaan()])->result();
    }
    public function getKehadiran($pengguna_id, $month)
    {

        $this->datatables->where("DATE_FORMAT(data_created,'%Y-%m')", $month);

        $this->db->where('type_absen', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id,'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getTunjanganByMonth($pengguna_id, $month)
    {

        $this->datatables->where("DATE_FORMAT(data_created,'%Y-%m')", $month);

        // $this->db->where("DATE_FORMAT(data_created,'%W') !=", 'Saturday'); //hari sabtu tidak ada tunjangan
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id,'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getRekapById($pengguna_id)
    {
        if ($this->input->post('filter_month')) {
            $this->datatables->where("DATE_FORMAT(a.data_created,'%Y-%m')", $this->input->post('filter_month'));
        } else {
            $this->db->where("DATE_FORMAT(a.data_created,'%Y-%m')", date('Y-m'));
        }

        if ($this->input->post('filter_date')) {
            $this->datatables->where("DATE_FORMAT(a.data_created,'%Y-%m-%d')", $this->input->post('filter_date'));
        }

        if ($this->input->post('filter_type')) {
            $this->datatables->where("a.type_absen", $this->input->post('filter_type'));
        }

        if ($this->input->post('filter_status')) {
            $this->datatables->where("a.status_absen", $this->input->post('filter_status'));
        }

        if ($this->input->post('jenis_absen')) {
            $this->datatables->where("a.jenis_absen", $this->input->post('jenis_absen'));
        }

        $this->db->order_by("a.data_created", 'DESC');
        return $this->datatables
            ->select('  
        a.id_absensi,
        a.pengguna_id,
        a.waktu_absen,
        a.status_absen,
        a.type_absen,
        a.latitude,
        a.longitude,
        a.approval,
        a.tanpa_tunjangan,
        a.data_created,
        a.keterangan,
        a.jenis_absen,
        a.file_pendukung
        ')
            ->from('absensi a')
            ->where('a.pengguna_id', $pengguna_id)
            ->where('a.perusahaan', grantAccessForPerusahaan())
            ->generate();
    }
}