<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_marketing_target extends CI_Model
{
    // === Target Penjualan CRUD ===
    function add_target($data)
    {
        $this->db->insert('marketing_target_penjualan', $data);
        return $this->db->insert_id();
    }

    function update_target($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('marketing_target_penjualan', $data);
    }

    function delete_target($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('marketing_target_penjualan', ['deleted' => 1]);
    }

    function get_target_by_id($id)
    {
        return $this->db->get_where('marketing_target_penjualan', ['id' => $id])->row();
    }

    function get_targets($marketing_id = NULL, $tahun = NULL, $bulan = NULL)
    {
        $this->db->select('t.*, p.nama as nama_marketing, prov.nama as nama_provinsi, k.nama as nama_kota');
        $this->db->from('marketing_target_penjualan t');
        $this->db->join('pengguna p', 't.marketing_id = p.pengguna_id', 'left');
        $this->db->join('provinsi prov', 't.provinsi_kode = prov.kode', 'left');
        $this->db->join('kota k', 't.kota_id = k.id', 'left');
        $this->db->where('t.deleted', 0);

        if ($marketing_id !== NULL) {
            if ($marketing_id == 54) {
                $this->db->where_in('t.marketing_id', [54, 72, 77]);
            } else {
                $this->db->where('t.marketing_id', $marketing_id);
            }
        }
        if ($tahun !== NULL) {
            $this->db->where('t.tahun', $tahun);
        }
        if ($bulan !== NULL && $bulan !== 'all') {
            $this->db->where('MONTH(t.estimasi_closing)', (int)$bulan);
        }

        $this->db->order_by('t.id', 'DESC');
        $result = $this->db->get()->result();

        foreach ($result as $row) {
            if (in_array($row->marketing_id, [54, 72, 77])) {
                $row->nama_marketing = 'Kantor Pusat [Bob Ariyos, CRO & Buldani]';
            } elseif ($row->marketing_id == 747) {
                $row->nama_marketing = 'After Sales Service';
            } elseif ($row->marketing_id == 754) {
                $row->nama_marketing = 'VISILAB';
            }
        }

        return $result;
    }

    // === Target Bulanan CRUD ===
    function save_target_bulanan($marketing_id, $tahun, $bulan, $target_nominal)
    {
        $this->db->where([
            'marketing_id' => $marketing_id,
            'tahun' => $tahun,
            'bulan' => $bulan
        ]);
        $exists = $this->db->get('marketing_target_bulanan')->num_rows() > 0;

        $data = [
            'marketing_id' => $marketing_id,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'target_nominal' => $target_nominal
        ];

        if ($exists) {
            $this->db->where([
                'marketing_id' => $marketing_id,
                'tahun' => $tahun,
                'bulan' => $bulan
            ]);
            return $this->db->update('marketing_target_bulanan', $data);
        } else {
            return $this->db->insert('marketing_target_bulanan', $data);
        }
    }

    function get_target_bulanan($marketing_id, $tahun, $bulan)
    {
        $bulan_int = (int)$bulan;
        $tahun_int = (int)$tahun;
        if (in_array($marketing_id, [54, 72, 77])) {
            $this->db->select_sum('target_nominal');
            $this->db->where_in('marketing_id', [54, 72, 77]);
            $this->db->where([
                'tahun' => $tahun_int,
                'bulan' => $bulan_int
            ]);
            $row = $this->db->get('marketing_target_bulanan')->row();
            return ($row && isset($row->target_nominal)) ? (float)$row->target_nominal : 0.0;
        } else {
            $row = $this->db->get_where('marketing_target_bulanan', [
                'marketing_id' => $marketing_id,
                'tahun' => $tahun_int,
                'bulan' => $bulan_int
            ])->row();
            return ($row && isset($row->target_nominal)) ? (float)$row->target_nominal : 0.0;
        }
    }

    function get_all_targets_bulanan($tahun, $bulan)
    {
        return $this->db->get_where('marketing_target_bulanan', [
            'tahun' => $tahun,
            'bulan' => (int)$bulan
        ])->result();
    }

    // === Rekapitulasi Dashboard ===
    function get_rekap_dashboard($tahun, $bulan, $marketing_id = NULL)
    {
        // Ambil semua marketing aktif yang jabatannya mengandung kata "marketing", 
        // atau yang memiliki data target aktif
        $this->db->select('pengguna_id, nama');
        $this->db->from('pengguna');
        $this->db->group_start();
        $this->db->like('jabatan', 'marketing', 'both');
        $this->db->or_where('id_divisi', 3); // Divisi marketing
        $this->db->or_where('pengguna_id IN (SELECT DISTINCT marketing_id FROM marketing_target_penjualan WHERE deleted = 0)', NULL, FALSE);
        $this->db->group_end();
        $this->db->where('is_active', 1);
        
        if ($marketing_id !== NULL) {
            if ($marketing_id == 54 || $marketing_id == 72 || $marketing_id == 77) {
                $this->db->where_in('pengguna_id', [54, 72, 77]);
            } else {
                $this->db->where('pengguna_id', $marketing_id);
            }
        }
        
        $marketings = $this->db->get()->result();

        $rekap = [];
        $has_combined = false;

        foreach ($marketings as $m) {
            $m_id = $m->pengguna_id;

            // Jika m_id adalah 72 atau 77, kita lewati karena digabung ke 54
            if ($m_id == 72 || $m_id == 77) {
                continue;
            }

            $query_ids = [$m_id];
            $display_name = $m->nama;

            if ($m_id == 54) {
                $query_ids = [54, 72, 77];
                $display_name = 'Kantor Pusat [Bob Ariyos, CRO & Buldani]';
                $has_combined = true;
            } elseif ($m_id == 747) {
                $display_name = 'After Sales Service';
            } elseif ($m_id == 754) {
                $display_name = 'VISILAB';
            }

            // 1. Jumlah Funnel Hot yang BELUM closing (status_funnel = 'Hot', tahun berjalan, is_achieved = 0)
            $this->db->where_in('marketing_id', $query_ids);
            $this->db->where([
                'tahun' => $tahun,
                'status_funnel' => 'Hot',
                'is_achieved' => 0,
                'deleted' => 0
            ]);
            $jml_hot = $this->db->count_all_results('marketing_target_penjualan');

            // 2. Nilai Hot Pipeline (SUM harga_jual untuk status_funnel = 'Hot', tahun berjalan, is_achieved = 0)
            $this->db->select_sum('harga_jual');
            $this->db->where_in('marketing_id', $query_ids);
            $this->db->where([
                'tahun' => $tahun,
                'status_funnel' => 'Hot',
                'is_achieved' => 0,
                'deleted' => 0
            ]);
            $query_val = $this->db->get('marketing_target_penjualan')->row();
            $nilai_hot = $query_val->harga_jual ? (float)$query_val->harga_jual : 0.0;

            // 3. Target Bulanan untuk bulan ini
            $target_bulanan = $this->get_target_bulanan($m_id, $tahun, $bulan);

            // 4. Potensi Closing Bulan Ini yang BELUM closing (Forecast Closing = Harga Jual * Persentase)
            $first_day = date('Y-m-01', strtotime("$tahun-$bulan-01"));
            $last_day = date('Y-m-t', strtotime("$tahun-$bulan-01"));

            $this->db->select('harga_jual, persentase_kecapaian');
            $this->db->from('marketing_target_penjualan');
            $this->db->where_in('marketing_id', $query_ids);
            $this->db->where([
                'is_achieved' => 0,
                'deleted' => 0
            ]);
            $this->db->where("estimasi_closing >= '$first_day'");
            $this->db->where("estimasi_closing <= '$last_day'");
            $funnels_month = $this->db->get()->result();

            $potensi_closing = 0;
            foreach ($funnels_month as $f) {
                $potensi_closing += ((float)$f->harga_jual * (int)$f->persentase_kecapaian) / 100;
            }

            // 5. Achievement Forecast (%) = (Potensi Closing Bulan Ini / Target Bulanan) * 100
            $achievement = 0;
            if ($target_bulanan > 0) {
                $achievement = ($potensi_closing / $target_bulanan) * 100;
            }

            // 6. Realisasi Achievement Penjualan (is_achieved = 1 pada bulan ini)
            $this->db->where_in('marketing_id', $query_ids);
            $this->db->where([
                'is_achieved' => 1,
                'deleted' => 0
            ]);
            $this->db->where("tgl_achievement >= '$first_day'");
            $this->db->where("tgl_achievement <= '$last_day'");
            $jumlah_achieved = $this->db->count_all_results('marketing_target_penjualan');

            $this->db->select_sum('nilai_achievement');
            $this->db->where_in('marketing_id', $query_ids);
            $this->db->where([
                'is_achieved' => 1,
                'deleted' => 0
            ]);
            $this->db->where("tgl_achievement >= '$first_day'");
            $this->db->where("tgl_achievement <= '$last_day'");
            $query_ach = $this->db->get('marketing_target_penjualan')->row();
            $nilai_achievement = $query_ach->nilai_achievement ? (float)$query_ach->nilai_achievement : 0.0;

            // 7. Realisasi vs Target (%) = (Nilai Achievement / Target Bulanan) * 100
            $realisasi_pct = 0;
            if ($target_bulanan > 0) {
                $realisasi_pct = ($nilai_achievement / $target_bulanan) * 100;
            }

            $rekap[] = [
                'marketing_id' => $m_id,
                'nama_marketing' => $display_name,
                'jumlah_hot' => $jml_hot,
                'nilai_hot_pipeline' => $nilai_hot,
                'target_bulanan' => $target_bulanan,
                'potensi_closing' => $potensi_closing,
                'achievement' => round($achievement, 2),
                'jumlah_achieved' => $jumlah_achieved,
                'nilai_achievement' => $nilai_achievement,
                'realisasi_pct' => round($realisasi_pct, 2)
            ];
        }

        return $rekap;
    }
}
