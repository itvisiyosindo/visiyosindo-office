<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_absensi extends CI_Model
{
    /**
     * Cek apakah pengguna memiliki akses WFA
     * @param int $pengguna_id - ID Pengguna
     * @return bool - TRUE jika WFA aktif untuk pengguna ini
     */
    public function getWFAStatus($pengguna_id)
    {
        $pengguna = $this->db->select('is_wfa')
            ->where('pengguna_id', $pengguna_id)
            ->where('perusahaan', grantAccessForPerusahaan())
            ->get('pengguna')
            ->row();

        return $pengguna && $pengguna->is_wfa == 1;
    }

    /**
     * Cek apakah fitur WFA diaktifkan di system
     * @return bool - TRUE jika WFA aktif di system
     */
    public function isWFASystemActive()
    {
        $config = $this->db->select('is_wfa_active')
            ->where('perusahaan', grantAccessForPerusahaan())
            ->get('absensi_config')
            ->row();

        return $config && $config->is_wfa_active == 1;
    }

    function add($data)
    {
        // Jika jenis_lokasi = WFA, otomatis set tanpa_tunjangan = 1.
        $jenis_lokasi = isset($data['jenis_lokasi']) ? strtoupper(trim($data['jenis_lokasi'])) : '';
        if ($jenis_lokasi === 'WFA') {
            $data['jenis_lokasi'] = 'WFA';
            $data['tanpa_tunjangan'] = 1;
        }

        $this->db->insert('absensi', $data);
    }
    function addbackup($data)
    {
        // Jika jenis_lokasi = WFA, otomatis set tanpa_tunjangan = 1.
        $jenis_lokasi = isset($data['jenis_lokasi']) ? strtoupper(trim($data['jenis_lokasi'])) : '';
        if ($jenis_lokasi === 'WFA') {
            $data['tanpa_tunjangan'] = 1;
        }

        $this->db->insert('absensibackup', $data);
    }

    /**
     * Ringkasan jumlah absensi hari ini per tipe untuk 1 pengguna.
     * @param int $pengguna_id
     * @return array{masuk:int,istirahat:int,keluar:int}
     */
    public function getTodayTypeSummary($pengguna_id)
    {
        $row = $this->db
            ->select(
                "SUM(CASE WHEN type_absen='masuk' THEN 1 ELSE 0 END) AS total_masuk,
                 SUM(CASE WHEN type_absen='istirahat' THEN 1 ELSE 0 END) AS total_istirahat,
                 SUM(CASE WHEN type_absen='keluar' THEN 1 ELSE 0 END) AS total_keluar",
                false
            )
            ->where('pengguna_id', $pengguna_id)
            ->where('perusahaan', grantAccessForPerusahaan())
            ->where('data_created >=', date('Y-m-d 00:00:00'))
            ->where('data_created <=', date('Y-m-d 23:59:59'))
            ->get('absensi')
            ->row();

        return [
            'masuk' => (int) ($row->total_masuk ?? 0),
            'istirahat' => (int) ($row->total_istirahat ?? 0),
            'keluar' => (int) ($row->total_keluar ?? 0),
        ];
    }

    /**
     * Cek apakah tipe absen tertentu sudah pernah dilakukan hari ini.
     * @param int $pengguna_id
     * @param string $type_absen
     * @return bool
     */
    public function hasTodayType($pengguna_id, $type_absen)
    {
        return $this->db
            ->where('pengguna_id', $pengguna_id)
            ->where('type_absen', $type_absen)
            ->where('perusahaan', grantAccessForPerusahaan())
            ->where('data_created >=', date('Y-m-d 00:00:00'))
            ->where('data_created <=', date('Y-m-d 23:59:59'))
            ->count_all_results('absensi') > 0;
    }

    function updateByWhere($data2, $where)
    {
        $this->db->where($where);
        $this->db->update('absensi', $data2);
    }
    public function updateByWhereToday($data2, $where)
    {
        // Mendapatkan tanggal hari ini
        $tanggal_hari_ini = date('Y-m-d');

        $where['DATE(absensi.data_created)'] = $tanggal_hari_ini;

        $this->db->where($where);
        $this->db->update('absensi', $data2);
    }


    function countTerlambat($pengguna_id, $month)
    {
        $this->db->select('count(*) as total_terlambat');
        $this->db->like('data_created', $month);
        $this->db->where('type_absen', 'masuk');
        $this->db->where('status_absen', 'terlambat');
        $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }
    function countDinas($pengguna_id, $month)
    {
        $this->db->select('count(*) as total');
        $this->db->like('data_created', $month);
        $this->db->where('type_absen', 'masuk');
        $this->db->group_start();
        $this->db->where("UPPER(jenis_absen)", 'DINAS');
        $this->db->or_where("status_absen", 'dinas');
        $this->db->group_end();
        $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }

    /**
     * Hitung jumlah hari WFA (Work From Anywhere) untuk pengguna pada bulan tertentu
     * @param int $pengguna_id
     * @param string $month format 'YYYY-MM'
     * @return array result dengan index 0->total
     */
    function countWfa($pengguna_id, $month)
    {
        $this->db->select('count(*) as total');
        $this->db->like('data_created', $month);
        $this->db->where('type_absen', 'masuk');
        $this->db->where("UPPER(jenis_lokasi)", 'WFA');
        $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }
    function countCuti($pengguna_id, $month)
    {
        $this->db->select('count(*) as total');
        $this->db->like('data_created', $month);
        $this->db->group_start();
        $this->db->where(['type_absen' => 'izin', 'status_absen' => 'cuti']);
        $this->db->or_where(['type_absen' => 'cuti', 'status_absen' => 'cuti']);
        $this->db->group_end();
        $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }

    function countIzin($pengguna_id, $month)
    {
        $where = "absensi.perusahaan=" . grantAccessForPerusahaan() . " AND type_absen ='izin' AND (status_absen='izin' or status_absen='sakit')";
        $this->db->select('count(*) as total')
            ->like('data_created', $month)
            ->where($where);

        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }

    /**
     * Hitung jumlah hari istirahat (break time) untuk pengguna pada bulan tertentu
     * @param int $pengguna_id
     * @param string $month format 'YYYY-MM'
     * @return array result dengan index 0->total
     */
    function countIstirahat($pengguna_id, $month)
    {
        $this->db->select('count(*) as total');
        $this->db->like('data_created', $month);
        $this->db->where('type_absen', 'istirahat');
        $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id])->result();
    }

    function daftar_hadirToday()
    {
        $data = $this->db->select('pengguna.nama, absensi.waktu_absen, absensi.jenis_absen')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where('type_absen', 'masuk')
            ->where('absensi.perusahaan', grantAccessForPerusahaan())
            ->get()->result();
        return $data;
    }

    function count_ulangTahun()
    {

        $data = "SELECT count(*) AS total FROM pengguna WHERE date_format(tgl_lahir,'%m-%d')=date_format(now(),'%m-%d')";
        $query = $this->db->query($data);
        return $query->result();
    }

    function daftar_ulangTahun()
    {

        $data = "SELECT * FROM pengguna WHERE date_format(tgl_lahir,'%m-%d')=date_format(now(),'%m-%d')";
        $query = $this->db->query($data);
        return $query->result();
    }

    function daftar_pulangToday()
    {
        $data = $this->db->select('pengguna.nama, absensi.waktu_absen')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where('type_absen', 'keluar')
            ->where('absensi.perusahaan', grantAccessForPerusahaan())
            ->get()->result();
        return $data;
    }

    function daftar_istirahatToday()
    {
        $data = $this->db->select('pengguna.nama, absensi.waktu_absen')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where('type_absen', 'istirahat')
            ->where('absensi.perusahaan', grantAccessForPerusahaan())
            ->get()->result();
        return $data;
    }

    function daftar_terlambatToday()
    {
        $data = $this->db->select('pengguna.nama, absensi.waktu_absen')
            ->from('absensi')
            ->join('pengguna', 'absensi.pengguna_id=pengguna.pengguna_id')
            ->like('data_created', date('Y-m-d', time()))
            ->where('type_absen', 'masuk')
            ->where('status_absen', 'terlambat')
            ->where('absensi.perusahaan', grantAccessForPerusahaan())
            ->get()->result();
        return $data;
    }

    function daftar_izinToday($status)
    {
        $where = "absensi.perusahaan=" . grantAccessForPerusahaan() . " AND type_absen ='izin' AND (status_absen='" . $status . "')";
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
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        } else {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'masuk');
            $this->db->where('pengguna_id', $id_pengguna);
            $this->db->where('status_absen', 'terlambat');
            $this->db->like('month(data_created)', date('m', time()));
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        }
    }
    function countPresentToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'masuk');
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        } else {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'masuk');
            $this->db->where('pengguna_id', $id_pengguna);
            $this->db->like('month(data_created)', date('m', time()));
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        }
    }

    function countPulangToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'keluar');
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        } else {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'keluar');
            $this->db->where('pengguna_id', $id_pengguna);
            $this->db->like('month(data_created)', date('m', time()));
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        }
    }

    function countIstirahatToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'istirahat');
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        } else {
            $this->db->select('count(*) as total');
            $this->db->like('data_created', date('Y-m-d', time()));
            $this->db->where('type_absen', 'istirahat');
            $this->db->where('pengguna_id', $id_pengguna);
            $this->db->like('month(data_created)', date('m', time()));
            $this->db->where('absensi.perusahaan', grantAccessForPerusahaan());
            return $this->db->get('absensi')->result();
        }
    }

    function daftar_tidakIstirahatToday()
    {
        // Ambil daftar hadir
        $daftar_hadir = $this->daftar_hadirToday();

        // Ambil daftar istirahat
        $daftar_istirahat = $this->daftar_istirahatToday();

        // Konversi daftar istirahat ke array nama untuk memudahkan perbandingan
        $nama_istirahat = array_map(function ($item) {
            return $item->nama;
        }, $daftar_istirahat);

        // Filter daftar hadir untuk yang tidak ada di daftar istirahat
        $daftar_tidak_istirahat = array_filter($daftar_hadir, function ($item) use ($nama_istirahat) {
            return !in_array($item->nama, $nama_istirahat);
        });

        // Return hasil
        return $daftar_tidak_istirahat;
    }


    function countTidakIstirahatToday()
    {
        // Hitung jumlah hadir hari ini
        $count_hadir = $this->countPresentToday();
        $total_hadir = $count_hadir[0]->total;

        // Hitung jumlah istirahat hari ini
        $count_istirahat = $this->countIstirahatToday();
        $total_istirahat = $count_istirahat[0]->total;

        // Hitung jumlah tidak istirahat, jika negatif maka menjadi 0
        $total_tidak_istirahat = max(0, $total_hadir - $total_istirahat);

        // Return hasil
        return $total_tidak_istirahat;
    }



    function countIzinToday($id_pengguna = "")
    {
        if ($id_pengguna == "") {
            $where = "absensi.perusahaan=" . grantAccessForPerusahaan() . " AND type_absen ='izin' AND (status_absen='izin')";
            $this->db->select('count(*) as total')
                ->like('data_created', date('Y-m-d', time()))
                ->where($where);
            return $this->db->get('absensi')->result();
        } else {
            $where = "absensi.perusahaan=" . grantAccessForPerusahaan() . " AND type_absen ='izin' AND (status_absen='izin') AND pengguna_id=" . $id_pengguna;
            $this->db->select('count(*) as total')
                ->like('data_created', date('Y-m-d', time()))
                ->where($where);
            return $this->db->get('absensi')->result();
        }
    }

    function countSakitToday()
    {
        $where = "absensi.perusahaan=" . grantAccessForPerusahaan() . " AND type_absen ='izin' AND (status_absen='sakit')";
        $this->db->select('count(*) as total')
            ->like('data_created', date('Y-m-d', time()))
            ->where($where);
        return $this->db->get('absensi')->result();
    }
    function countCutiToday()
    {
        $where = "absensi.perusahaan=" . grantAccessForPerusahaan() . " AND type_absen ='izin' AND (status_absen='cuti')";
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

            $masukA = !empty($config[0]->boleh_absen_malam) ? date($config[0]->boleh_absen_malam) : date("13:00:00");
            $masukB = date("23:00:00");

            $pulangC = date("H:i:s");

            $isMasukWindow = $pulangC >= $masukA && $pulangC < $masukB;
            $isKeluarWindow = $pulangC >= $pulangA && $pulangC <= $pulangB;

            if ($isMasukWindow) {
                $this->db->where('type_absen ', 'masuk');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            } else if ($isKeluarWindow) {
                $this->db->where('type_absen ', 'keluar');
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
        return $this->db->get_where('absensi', ['pengguna_id' => sessPenggunaId(), 'perusahaan' => grantAccessForPerusahaan()])->result();
    }


    function getAbsensiTodayById2()
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

            $masukA = !empty($config[0]->boleh_absen_malam) ? date($config[0]->boleh_absen_malam) : date("13:00:00");
            $masukB = date("23:00:00");

            $pulangC = date("H:i:s");

            $isMasukWindow = $pulangC >= $masukA && $pulangC < $masukB;
            $isKeluarWindow = $pulangC >= $pulangA && $pulangC <= $pulangB;

            if ($isMasukWindow) {
                $this->db->where('type_absen ', 'masuk');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            } else if ($isKeluarWindow) {
                $this->db->where('type_absen ', 'keluar');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            } else {
                $this->db->where('type_absen ', 'keluar');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            }
        } else {
            $hari = date('l');
            if ($hari == 'Friday') {
                //$rehatA = date("13:20:00");
                //$rehatB = date("13:30:00"); 
                $rehatA = date($config[0]->mulai_rehat_a);
                $rehatB = date($config[0]->akhir_rehat_a);
            } else {
                //$rehatA = date("12:50:00");
                //$rehatB = date("13:00:00"); // Senin - Kamis pukul 13:00
                $rehatA = date($config[0]->mulai_rehat_b);
                $rehatB = date($config[0]->akhir_rehat_b);
            }
            $pulang = date('l') == 'Saturday' ? date($config[0]->jam_keluar_sabtu) : date($config[0]->jam_keluar);
            //$rehatA = date("12:30:00");
            //$rehatB = date("14:00:00");

            //$pulang1 = date("17:00:00");
            $pulang1 = date($config[0]->jam_keluar);
            $pulang2 = date("23:59:00");

            $rehatC = date("H:i:s");
            if (date("H:i:s") < $rehatA) {
                $this->db->where('type_absen ', 'masuk');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            } else if ($rehatC > $rehatA && $rehatC < $rehatB) {
                $this->db->where('type_absen ', 'istirahat');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            } else if ($rehatC > $pulang1 && $rehatC < $pulang2) {
                $this->db->where('type_absen ', 'keluar');
                $this->db->where('perusahaan', grantAccessForPerusahaan());
            }
        }

        $this->db->like('data_created', date('Y-m-d', time()));
        return $this->db->get_where('absensi', ['pengguna_id' => sessPenggunaId(), 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    // Untuk ambil tanggal libur yang sudah diinput ke database
    //function get_libur(){
    //    return $this->db->get('hari_libur')->result();
    //}

    function getAbsenTodayById($id)
    {
        // 12:00:00 dan 17:00:00 master data

        //$this->db->where('type_absen ', 'izin');

        $this->db->like('data_created', date('Y-m-d', time()));
        return $this->db->get_where('absensi', ['pengguna_id' => $id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    function getAbsenIzinById($id, $tgl)
    {

        $formattedDate = date('Y-m-d', strtotime($tgl));

        $this->db->where('data_created >=', $formattedDate . ' 00:00:00');
        $this->db->where('data_created <=', $formattedDate . ' 23:59:59');
        $this->db->where('pengguna_id', $id);
        $this->db->where('perusahaan', grantAccessForPerusahaan());

        return $this->db->get('absensi')->result();
    }


    function getAbsensiIzinTodayById()
    {
        // 12:00:00 dan 17:00:00 master data

        $this->db->where('type_absen ', 'izin');

        $this->db->like('data_created', date('Y-m-d', time()));
        return $this->db->get_where('absensi', ['pengguna_id' => sessPenggunaId(), 'perusahaan' => grantAccessForPerusahaan()])->result();
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
            $f_stat = $this->input->post('filter_status');
            if ($f_stat == 'dinas') {
                $this->db->group_start();
                $this->db->where("status_absen", 'dinas');
                $this->db->or_where("UPPER(jenis_absen)", 'DINAS');
                $this->db->group_end();
            } else {
                $this->db->where("status_absen", $f_stat);
            }
        }
        $this->db->where('type_absen ', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
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
            $f_stat = $this->input->post('filter_status');
            if ($f_stat == 'dinas') {
                $this->db->group_start();
                $this->db->where("status_absen", 'dinas');
                $this->db->or_where("UPPER(jenis_absen)", 'DINAS');
                $this->db->group_end();
            } else {
                $this->db->where("status_absen", $f_stat);
            }
        }
        $this->db->where('type_absen ', 'izin');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getAbsenMasukById($id)
    {
        return $this->db->get_where('absensi', ['id_absensi' => $id, 'perusahaan' => grantAccessForPerusahaan()])->result();
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
        $this->db->where('tanpa_tunjangan', 0);
        $this->db->where("UPPER(jenis_lokasi) !=", 'WFA');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    /**
     * Hitung ringkasan kehadiran & lembur untuk Security (Pak Boddy dkk)
     * Menggunakan bobot dinamis: Hari Biasa (1x) + Hari Libur/Weekend (3x)
     */
    public function getSecurityKehadiranSummary($pengguna_id, $month)
    {
        // Ambil semua daftar libur di bulan tersebut dari config
        $config_libur = $this->db->like('tgl', $month)->get('absensi_config_libur')->result();
        $libur_dates = array_map(function ($item) {
            return date('Y-m-d', strtotime($item->tgl));
        }, $config_libur);

        // Ambil semua absensi masuk pada bulan tersebut
        $records = $this->db->select("id_absensi, data_created, keterangan, approval, status_absen, type_absen, DAYOFWEEK(data_created) as day_of_week")
            ->where("DATE_FORMAT(data_created,'%Y-%m')", $month)
            ->where('type_absen', 'masuk')
            ->where('perusahaan', grantAccessForPerusahaan())
            ->get('absensi')
            ->result();

        $hari_biasa = 0;
        $hari_libur = 0;
        $total_fisik = count($records);

        foreach ($records as $r) {
            $tgl = date('Y-m-d', strtotime($r->data_created));
            $is_weekend = in_array((int)$r->day_of_week, [1, 7]); // 1=Sunday, 7=Saturday
            $is_libur_nasional = in_array($tgl, $libur_dates);
            $is_ket_libur = ($r->keterangan == 'hari_libur');

            if ($is_ket_libur || $is_weekend || $is_libur_nasional) {
                $hari_libur++;
            } else {
                $hari_biasa++;
            }
        }

        // Bobot: hari biasa (x1) + hari libur (x3)
        $total_kehadiran = $hari_biasa + ($hari_libur * 3);
        $hari_lembur = $hari_libur * 2; // tambahan hari hasil lembur di luar hari fisik

        return [
            'total_kehadiran' => $total_kehadiran,
            'hari_fisik'      => $total_fisik,
            'hari_biasa'      => $hari_biasa,
            'hari_libur'      => $hari_libur,
            'hari_lembur'     => $hari_lembur
        ];
    }

    // Fungsi untuk mengetahui pak boddy hari kerja Biasa
    public function getTunjanganBoddyBiasa($pengguna_id, $month)
    {
        $this->db->where("DATE_FORMAT(data_created,'%Y-%m')", $month);

        // $this->db->where("DATE_FORMAT(data_created,'%W') !=", 'Saturday'); //hari sabtu tidak ada tunjangan
        $this->db->where('keterangan', 'hari_biasa');
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        $this->db->where('tanpa_tunjangan', 0);
        $this->db->where("UPPER(jenis_lokasi) !=", 'WFA');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    // Fungsi untuk mengetahui pak boddy hari Libur & Weekend
    public function getTunjanganBoddyLibur($pengguna_id, $month)
    {
        $this->db->where("DATE_FORMAT(data_created,'%Y-%m')", $month);

        // $this->db->where("DATE_FORMAT(data_created,'%W') !=", 'Saturday'); //hari sabtu tidak ada tunjangan
        $this->db->where('keterangan', 'hari_libur');
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        $this->db->where('tanpa_tunjangan', 0);
        $this->db->where("UPPER(jenis_lokasi) !=", 'WFA');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getTunjanganByMonthDinas($pengguna_id, $month)
    {

        $this->datatables->where("DATE_FORMAT(data_created,'%Y-%m')", $month);

        // $this->db->where("DATE_FORMAT(data_created,'%W') !=", 'Saturday'); //hari sabtu tidak ada tunjangan
        $this->db->where("UPPER(jenis_absen)", 'KANTOR');
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        $this->db->where('tanpa_tunjangan', 0);
        $this->db->where("UPPER(jenis_lokasi) !=", 'WFA');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getAbsenKantorByMonth($pengguna_id, $month)
    {
        $this->db->where("DATE_FORMAT(data_created,'%Y-%m')", $month);
        $this->db->where("UPPER(jenis_absen)", 'KANTOR');
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }



    public function getKehadiran($pengguna_id, $month)
    {
        $this->db->where("DATE_FORMAT(data_created,'%Y-%m')", $month);
        $this->db->where('type_absen', 'masuk');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
    }

    public function getWeekendMasukByMonth($pengguna_id, $month)
    {
        $this->db->select("DATE(data_created) AS tanggal, DAYNAME(data_created) AS hari");
        $this->db->where("DATE_FORMAT(data_created,'%Y-%m')", $month);
        $this->db->where('type_absen', 'masuk');
        $this->db->where_in('DAYOFWEEK(data_created)', [1, 7]);
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result_array();
    }

    public function getTunjanganByMonth($pengguna_id, $month)
    {

        $this->datatables->where("DATE_FORMAT(data_created,'%Y-%m')", $month);

        // $this->db->where("DATE_FORMAT(data_created,'%W') !=", 'Saturday'); //hari sabtu tidak ada tunjangan
        $this->db->where('approval', 'terima');
        $this->db->where('type_absen', 'masuk');
        $this->db->where('tanpa_tunjangan', 0);
        $this->db->where("UPPER(jenis_lokasi) !=", 'WFA');
        return $this->db->get_where('absensi', ['pengguna_id' => $pengguna_id, 'perusahaan' => grantAccessForPerusahaan()])->result();
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

        // Urutan rekap: per hari (terbaru), lalu urutan tipe absen masuk -> istirahat -> keluar.
        $this->db->order_by("DATE(a.data_created)", 'DESC', false);
        $this->db->order_by("CASE a.type_absen
            WHEN 'masuk' THEN 1
            WHEN 'istirahat' THEN 2
            WHEN 'keluar' THEN 3
            ELSE 4
        END", 'ASC', false);
        $this->db->order_by("a.waktu_absen", 'ASC');
        $this->db->order_by("a.id_absensi", 'ASC');
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
        a.jenis_lokasi,
        a.ip_addr,
        a.file_pendukung,
        a.file_foto
        ')
            ->from('absensi a')
            ->where('a.pengguna_id', $pengguna_id)
            ->where('a.perusahaan', grantAccessForPerusahaan())
            ->generate();
    }

    public function getRekapTotals($pengguna_id)
    {
        if ($this->input->post('filter_month')) {
            $this->db->where("DATE_FORMAT(a.data_created,'%Y-%m')", $this->input->post('filter_month'));
        } else {
            $this->db->where("DATE_FORMAT(a.data_created,'%Y-%m')", date('Y-m'));
        }

        if ($this->input->post('filter_date')) {
            $this->db->where("DATE_FORMAT(a.data_created,'%Y-%m-%d')", $this->input->post('filter_date'));
        }

        if ($this->input->post('filter_type')) {
            $this->db->where("a.type_absen", $this->input->post('filter_type'));
        }

        if ($this->input->post('filter_status')) {
            $this->db->where("a.status_absen", $this->input->post('filter_status'));
        }

        if ($this->input->post('jenis_absen')) {
            $this->db->where("a.jenis_absen", $this->input->post('jenis_absen'));
        }

        $this->db->select(" 
            COUNT(*) AS total_absensi,
            SUM(CASE WHEN a.type_absen = 'masuk' THEN 1 ELSE 0 END) AS total_masuk,
            SUM(CASE WHEN a.type_absen = 'istirahat' THEN 1 ELSE 0 END) AS total_istirahat,
            SUM(CASE WHEN a.type_absen = 'keluar' THEN 1 ELSE 0 END) AS total_keluar,
            SUM(CASE WHEN a.status_absen = 'terlambat' THEN 1 ELSE 0 END) AS total_terlambat,
            SUM(CASE WHEN a.type_absen = 'izin' THEN 1 ELSE 0 END) AS total_izin,
            SUM(CASE WHEN a.type_absen = 'cuti' THEN 1 ELSE 0 END) AS total_cuti,
            SUM(CASE WHEN a.type_absen = 'masuk' AND UPPER(a.jenis_lokasi) = 'WFA' THEN 1 ELSE 0 END) AS total_wfa,
            SUM(CASE WHEN a.type_absen = 'masuk' AND (UPPER(a.jenis_absen) = 'DINAS' OR a.status_absen = 'dinas') THEN 1 ELSE 0 END) AS total_dinas
        ", false);
        $this->db->from('absensi a');
        $this->db->where('a.pengguna_id', $pengguna_id);
        $this->db->where('a.perusahaan', grantAccessForPerusahaan());
        return $this->db->get()->row_array();
    }



    //Untuk Tombol Absen Dinas atau Kantor
    public function is_dinas_today($pengguna_id)
    {
        $this->db->select('sp.*');
        $this->db->from('surat_pd_dinas sp');
        $this->db->join('pengguna p', 'p.nama = sp.nama_karyawan');
        $this->db->where('p.pengguna_id', $pengguna_id);
        $this->db->where('sp.ttd_3', 1);
        $this->db->where('CURDATE() >= sp.tgl_dinas');
        $this->db->where('CURDATE() <= sp.tgl_dinas_akhir');

        $query = $this->db->get();
        return $query->num_rows() > 0;
    }

    /**
     * Ambil seluruh log absensi lengkap dengan Foto Selfie & Lokasi GPS untuk 1 bulan
     * @param int $pengguna_id
     * @param string $month format 'YYYY-MM'
     * @return array
     */
    public function getAbsensiFotoGpsByMonth($pengguna_id, $month)
    {
        $this->db->select('a.*, p.nama, p.no_pegawai, p.jabatan');
        $this->db->from('absensi a');
        $this->db->join('pengguna p', 'a.pengguna_id = p.pengguna_id', 'left');
        $this->db->where('a.pengguna_id', $pengguna_id);
        $this->db->like("DATE_FORMAT(a.data_created, '%Y-%m')", $month);
        $this->db->where('a.perusahaan', grantAccessForPerusahaan());
        $this->db->order_by('a.data_created', 'ASC');
        $this->db->order_by('a.id_absensi', 'ASC');
        return $this->db->get()->result();
    }
}
