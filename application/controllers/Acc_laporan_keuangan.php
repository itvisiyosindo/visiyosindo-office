<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_acc_laporan_keuangan $md_acc_laporan_keuangan
 */
class Acc_laporan_keuangan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        // Restrict access to Accounting and Admin users
        $this->load->helper('session_helper');
        if (!isAccountingUser()) {
            redirect('dashboard');
        }
        
        $this->load->model('md_acc_laporan_keuangan');
    }

    public function id_navbar()
    {
        return 'accounting';
    }

    public function index()
    {
        $page_data['switch']     = $this->id_navbar();
        $page_data['page_name']  = 'acc_pemasok/v_laporan_keuangan';
        $page_data['page_title'] = 'Data Laporan Keuangan';
        $page_data['page_desc']  = 'Manajemen Dokumen Laporan Keuangan Perusahaan';
        $this->load->view('index', $page_data);
    }

    public function save()
    {
        $id_encrypt = $this->input->post('id');
        
        $data['nama_dokumen']       = $this->input->post('nama_dokumen', TRUE);
        $data['jenis_dokumen']      = $this->input->post('jenis_dokumen', TRUE);
        $data['tahun_pelaporan']    = intval($this->input->post('tahun_pelaporan', TRUE));
        $data['keperluan_dokumen']  = $this->input->post('keperluan_dokumen', TRUE);
        $data['link_dokumen']       = $this->input->post('link_dokumen', TRUE);
        $data['updated_at']         = date('Y-m-d H:i:s');

        checkEmptyForm([$data['nama_dokumen'], $data['jenis_dokumen'], $data['tahun_pelaporan'], $data['keperluan_dokumen'], $data['link_dokumen']]);

        if (!empty($id_encrypt)) {
            $id = decrypt($id_encrypt);
            $this->md_acc_laporan_keuangan->update(['id' => $id], $data);
            
            addlog('Edit Laporan Keuangan', 'Mengedit dokumen Laporan Keuangan: ' . $data['nama_dokumen']);
            ajaxReturnDie('success', 'Dokumen berhasil diperbarui', 'reload_table');
        } else {
            $data['status']     = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->md_acc_laporan_keuangan->add($data);

            addlog('Tambah Laporan Keuangan', 'Menambahkan dokumen Laporan Keuangan baru: ' . $data['nama_dokumen']);
            ajaxReturnDie('success', 'Dokumen berhasil ditambahkan', 'reload_table');
        }
    }

    public function get_detail($encrypted_id)
    {
        $id = decrypt($encrypted_id);
        $dt = $this->md_acc_laporan_keuangan->getById($id);
        if (!empty($dt)) {
            $row = $dt[0];
            $row->id = encrypt($row->id);
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
        exit;
    }

    public function delete()
    {
        $id_encrypt = $this->input->post('id');
        $id = decrypt($id_encrypt);
        
        $temp = $this->md_acc_laporan_keuangan->getById($id);
        if (!empty($temp)) {
            $this->md_acc_laporan_keuangan->update(['id' => $id], ['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            addlog('Hapus Laporan Keuangan', 'Menghapus dokumen Laporan Keuangan: ' . $temp[0]->nama_dokumen);
            ajaxReturnDie('success', 'Dokumen berhasil dihapus', 'reload_table');
        } else {
            ajaxReturnDie('error', 'Dokumen tidak ditemukan');
        }
    }

    public function pagination()
    {
        $filters = [
            'jenis_dokumen' => $this->input->post('filter_jenis_dokumen', TRUE),
            'keperluan_dokumen' => $this->input->post('filter_keperluan_dokumen', TRUE),
            'tahun_pelaporan' => $this->input->post('filter_tahun_pelaporan', TRUE)
        ];
        $dt    = $this->md_acc_laporan_keuangan->getAll($filters);
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id = encrypt($row->id);
            
            $btn_actions = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-xs btn-primary btn-edit" data-id="' . $id . '" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                    <button type="button" class="btn btn-xs btn-danger btn-delete" data-id="' . $id . '" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                </div>';
            
            $link_download = $row->link_dokumen ? '<a href="' . $row->link_dokumen . '" target="_blank" class="btn btn-xs btn-success"><i class="fas fa-external-link-alt"></i> Buka Link</a>' : '-';
            
            $th = array();
            $th[] = ++$start . '.';
            $th[] = '<strong>' . htmlspecialchars($row->nama_dokumen) . '</strong>';
            $th[] = '<span class="badge badge-info">' . htmlspecialchars($row->jenis_dokumen) . '</span>';
            $th[] = '<strong>' . $row->tahun_pelaporan . '</strong>';
            $th[] = '<span class="badge badge-dark">' . htmlspecialchars($row->keperluan_dokumen) . '</span>';
            $th[] = $link_download;
            $th[] = $btn_actions;
            
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        exit;
    }
}
