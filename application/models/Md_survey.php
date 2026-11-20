<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_survey extends CI_Model
{
    protected $table = 'survey_kepuasan';

    public function create_survey($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_token($token)
    {
        return $this->db->get_where($this->table, ['token' => $token])->row();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function submit_survey($token, $data)
    {
        $this->db->where('token', $token);
        return $this->db->update($this->table, $data);
    }

    public function update_tindak_lanjut($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function get_all_surveys($filter = [])
    {
        $this->db->select('s.*, p.kota, p.provinsi, p.tipe_bisnis');
        $this->db->from($this->table . ' s');
        $this->db->join('pelanggan p', 'p.id_pelanggan = s.id_pelanggan', 'left');

        if (!empty($filter['status'])) {
            $this->db->where('s.status', $filter['status']);
        }
        if (!empty($filter['status_tindak_lanjut'])) {
            $this->db->where('s.status_tindak_lanjut', $filter['status_tindak_lanjut']);
        }
        if (!empty($filter['tgl_awal'])) {
            $this->db->where('DATE(s.created_at) >=', $filter['tgl_awal']);
        }
        if (!empty($filter['tgl_akhir'])) {
            $this->db->where('DATE(s.created_at) <=', $filter['tgl_akhir']);
        }

        $this->db->order_by('s.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_statistics()
    {
        $total_terkirim = $this->db->count_all($this->table);
        $total_selesai  = $this->db->where('status', 'Selesai')->count_all_results($this->table);
        $total_pending  = $this->db->where('status', 'Pending')->count_all_results($this->table);
        $butuh_tindak_lanjut = $this->db->where('status', 'Selesai')
            ->where('status_tindak_lanjut', 'Belum Ditindaklanjuti')
            ->count_all_results($this->table);

        $row_avg = $this->db->select_avg('rata_rata', 'avg_all')
            ->where('status', 'Selesai')
            ->get($this->table)
            ->row();

        $avg_all = $row_avg && $row_avg->avg_all ? round((float)$row_avg->avg_all, 2) : 0;

        return [
            'total_terkirim'      => $total_terkirim,
            'total_selesai'       => $total_selesai,
            'total_pending'       => $total_pending,
            'avg_all'             => $avg_all,
            'butuh_tindak_lanjut' => $butuh_tindak_lanjut
        ];
    }

    public function build_wa_message($nama_pelanggan, $nama_pic, $survey_url)
    {
        $nama_pelanggan = trim((string)$nama_pelanggan);
        $nama_pic       = trim((string)$nama_pic);

        $hour = (int)date('G');
        if ($hour >= 4 && $hour < 11) {
            $waktu = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $waktu = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $waktu = 'Selamat Sore';
        } else {
            $waktu = 'Selamat Malam';
        }

        if ($nama_pic !== '' && $nama_pic !== '-' && strcasecmp($nama_pic, $nama_pelanggan) !== 0) {
            if (preg_match('/^(bapak|ibu|bpk|bp|bu|dr|drg|prof|ir|h|hj|tn|ny|sdr|sdri)\.?\s/i', $nama_pic)) {
                $baris_pic = "*{$nama_pic}*";
            } else {
                $baris_pic = "*Bapak/Ibu {$nama_pic}*";
            }
        } else {
            $baris_pic = "*Bapak/Ibu PIC / Pimpinan*";
        }

        $baris_instansi = $nama_pelanggan !== '' ? "\n*{$nama_pelanggan}*" : '';

        return "{$waktu},\n\n"
             . "Kepada Yth.\n"
             . "{$baris_pic}{$baris_instansi}\n\n"
             . "Terima kasih atas kepercayaan Bapak/Ibu yang telah menggunakan produk dan layanan *PT Visi Yosindo Medika*.\n\n"
             . "Sebagai komitmen kami untuk terus meningkatkan mutu pelayanan dan purna jual, kami mohon kesediaan Bapak/Ibu meluangkan waktu sejenak (±1 menit) untuk mengisi *Survey Kepuasan Pelanggan* melalui tautan berikut:\n\n"
             . "{$survey_url}\n\n"
             . "Penilaian serta saran dan masukan dari Bapak/Ibu sangat berharga bagi evaluasi dan peningkatan kualitas layanan kami ke depannya.\n\n"
             . "Atas waktu, perhatian, dan kerja sama yang baik, kami ucapkan terima kasih.\n\n"
             . "Salam hangat,\n"
             . "*Customer Relation Officer (CRO)*\n"
             . "*PT Visi Yosindo Medika*";
    }

    public function build_wa_url($no_wa, $pesan_wa)
    {
        $clean_phone = preg_replace('/[^0-9]/', '', (string)$no_wa);
        if (substr($clean_phone, 0, 1) === '0') {
            $clean_phone = '62' . substr($clean_phone, 1);
        } elseif (substr($clean_phone, 0, 2) !== '62' && strlen($clean_phone) > 0) {
            $clean_phone = '62' . $clean_phone;
        }

        return 'https://wa.me/' . $clean_phone . '?text=' . rawurlencode($pesan_wa);
    }
}
