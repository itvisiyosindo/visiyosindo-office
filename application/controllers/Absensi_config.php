<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Absensi_config extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_absensi_config');
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Karyawan', 'Ga']);

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'v_absensi_config';
        $page_data['page_title']    = 'Konfigurasi Absensi';
        $page_data['page_desc']     = '';
        $page_data['config'] = $this->md_absensi_config->get();
        // echo '<pre>'; print_r( $page_data['config'] );die; echo '</pre>';
        $this->load->view('index', $page_data);
    }

    public function update()
    {
        $data['boleh_absen'] = date('H:i:s', strtotime($this->input->post('boleh_absen')));
        $data['jam_masuk'] = date('H:i:s', strtotime($this->input->post('jam_masuk')));
        $data['jam_keluar'] = date('H:i:s', strtotime($this->input->post('jam_keluar')));
        $data['jam_masuk_pak_anto'] = date('H:i:s', strtotime($this->input->post('jam_masuk_pak_anto')));
        //Untuk Data Absen Istirahat
        $data['mulai_rehat_a'] = date('H:i:s', strtotime($this->input->post('mulai_rehat_a')));
        $data['akhir_rehat_a'] = date('H:i:s', strtotime($this->input->post('akhir_rehat_a')));
        $data['mulai_rehat_b'] = date('H:i:s', strtotime($this->input->post('mulai_rehat_b')));
        $data['akhir_rehat_b'] = date('H:i:s', strtotime($this->input->post('akhir_rehat_b')));
        checkEmptyForm($data);
        $data['is_libur'] = $this->input->post('is_libur') == 'on' ? 1 : 0;
        $this->md_absensi_config->update($data);
        ajaxReturnDie('success', 'Data Berhasil Update', true);
    }


    // START Hari Libur

    public function addLibur()
    {
        grantAccessFor('all');

        $data['tgl']    = date_db_format($this->input->post('tgl', TRUE));
        $data['ket']    = $this->input->post('ket', TRUE);

        $this->md_absensi_config->addLibur($data);

        /** LOG */
        addLog('Penambahan Hari Libur', 'Menambah Hari Libur "' . $data['tgl'] . '"');
        ajaxReturnDie('success', 'Hari Libur berhasil ditambahkan', 'reload_table');
    }

    public function editLibur($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_absensi_config->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function updateLibur()
    {
        grantAccessFor('all');
        $id = decrypt($this->input->post('id'));
        $data['tgl']    = date_db_format($this->input->post('tgl', TRUE));
        $data['ket']    = $this->input->post('ket', TRUE);

        $this->md_absensi_config->updateLibur($id, $data);

        /** LOG */
        addLog('Update Hari Libur', 'Memperbarui data Hari Libur "' . $data['tgl'] . '"');
        ajaxReturnDie('success', 'Hari Libur berhasil diperbarui', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_absensi_config->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id           = encrypt($row->id);
            $li_btn       = '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   
                </div>';


            $th = array();
            $th[] = ++$start;
            $th[] = date('d-m-Y', strtotime($row->tgl));
            $th[] = $row->ket;
            $th[] = $li_btn;
            $data[] = $th;
        }

        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    // ===== WFA Configuration Functions =====

    /**
     * Update konfigurasi WFA global
     */
    public function updateWFAConfig()
    {
        grantAccessFor(['Administrator', 'Hrd']);

        $is_wfa_active = $this->input->post('is_wfa_active') == 'on' ? 1 : 0;

        $data['is_wfa_active'] = $is_wfa_active;

        $this->md_absensi_config->update($data);

        addLog('Konfigurasi WFA', 'Update status WFA: ' . ($is_wfa_active ? 'Aktif' : 'Nonaktif'));

        ajaxReturnDie('success', 'Konfigurasi WFA berhasil diupdate', 'reload_page');
    }

    /**
     * Endpoint alias lowercase untuk kompatibilitas URL production
     */
    public function update_wfa_config()
    {
        return $this->updateWFAConfig();
    }

    /**
     * Tampilkan halaman manajemen WFA pengguna
     */
    public function managePenggunaWFA()
    {
        grantAccessFor(['Administrator', 'Hrd']);

        $this->load->model('md_pengguna');
        $this->load->model('md_absensi');

        // Fallback untuk production yang model Md_pengguna-nya belum memiliki method WFA.
        $excluded_names = [
            'Novi Dewi Elmita',
            'Nuh Visi Syailendra',
            'Admin Accounting',
            'Admin Regulatory',
            'Administrator',
            'Bob Ariyos',
            'Bob Ariyos (directoor)',
            'Customer Relation Officer',
            'Finance Staff',
            'General Affair',
            'Head of Accounting and Tax',
            'Head Of Administration',
            'HR and Legal Officer',
            'HRD',
            'Penanggung Jawab Teknis',
            'Romayani Sihombing',
            'Rosmaniar',
            'Staff Legal',
            'Buldani',
        ];

        if (method_exists($this->md_pengguna, 'getActiveUsersWithWFA')) {
            $list_pengguna = $this->md_pengguna->getActiveUsersWithWFA();
        } else {
            $this->db->select('pengguna_id, nama, jabatan, is_wfa')
                ->where('is_active', 1)
                ->where('status', 1)
                ->where('perusahaan', grantAccessForPerusahaan())
                ->order_by('nama', 'ASC');

            if (!empty($excluded_names)) {
                $this->db->where_not_in('nama', $excluded_names);
            }

            $list_pengguna = $this->db->get('pengguna')->result();
        }

        $page_data['switch'] = 'kepegawaian';
        $page_data['page_name'] = 'v_wfa_pengguna';
        $page_data['page_title'] = 'Manajemen WFA Karyawan';
        $page_data['page_desc'] = 'Kelola status WFA untuk setiap karyawan';
        $page_data['list_pengguna'] = $list_pengguna;
        $page_data['wfa_system_active'] = $this->md_absensi->isWFASystemActive();

        $this->load->view('index', $page_data);
    }

    /**
     * Endpoint alias lowercase untuk kompatibilitas URL production
     */
    public function manage_pengguna_wfa()
    {
        return $this->managePenggunaWFA();
    }

    /**
     * Update status WFA untuk satu pengguna via AJAX
     */
    public function updatePenggunaWFA()
    {
        grantAccessFor(['Administrator', 'Hrd']);

        $pengguna_id = decrypt($this->input->post('pengguna_id'));
        $is_wfa = $this->input->post('is_wfa') == 'true' ? 1 : 0;

        $this->load->model('md_pengguna');

        if (method_exists($this->md_pengguna, 'getById')) {
            $pengguna = $this->md_pengguna->getById($pengguna_id);
        } else {
            $pengguna = $this->db->where('pengguna_id', $pengguna_id)
                ->where('perusahaan', grantAccessForPerusahaan())
                ->get('pengguna')
                ->result();
        }

        if (!$pengguna) {
            ajaxReturnDie('error', 'Pengguna tidak ditemukan');
        }

        if (method_exists($this->md_pengguna, 'updateWFAStatus')) {
            $this->md_pengguna->updateWFAStatus($pengguna_id, $is_wfa);
        } else {
            $this->db->where('pengguna_id', $pengguna_id)
                ->where('perusahaan', grantAccessForPerusahaan())
                ->update('pengguna', ['is_wfa' => $is_wfa]);
        }

        $nama_pengguna = $pengguna[0]->nama;
        $status_text = $is_wfa ? 'diaktifkan' : 'dinonaktifkan';

        addLog('Manajemen WFA', 'Status WFA ' . $nama_pengguna . ' ' . $status_text);

        ajaxReturnDie('success', 'Status WFA berhasil diupdate', 'reload_table');
    }

    /**
     * Endpoint alias lowercase untuk kompatibilitas URL production
     */
    public function update_pengguna_wfa()
    {
        return $this->updatePenggunaWFA();
    }
}
