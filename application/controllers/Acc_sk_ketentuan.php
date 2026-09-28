<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_acc_sk_ketentuan $md_acc_sk_ketentuan
 */
class Acc_sk_ketentuan extends CI_Controller
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
        
        $this->load->model('md_acc_sk_ketentuan');
    }

    public function id_navbar()
    {
        return 'accounting';
    }

    public function index()
    {
        $page_data['switch']     = $this->id_navbar();
        $page_data['page_name']  = 'acc_pemasok/v_sk_ketentuan';
        $page_data['page_title'] = 'Data SK & Ketentuan';
        $page_data['page_desc']  = 'Manajemen Dokumen SK & Ketentuan Perusahaan';
        $this->load->view('index', $page_data);
    }

    public function save()
    {
        $id_encrypt = $this->input->post('id');
        
        $data['nama_dokumen']     = $this->input->post('nama_dokumen', TRUE);
        $data['tanggal_dokumen']  = $this->input->post('tanggal_dokumen') ? date_db_format($this->input->post('tanggal_dokumen', TRUE)) : NULL;
        $data['masa_berlaku']     = $this->input->post('masa_berlaku') ? date_db_format($this->input->post('masa_berlaku', TRUE)) : NULL;
        $data['link_dokumen']     = $this->input->post('link_dokumen', TRUE);
        $data['updated_at']       = date('Y-m-d H:i:s');

        checkEmptyForm([$data['nama_dokumen']]);

        if (!empty($id_encrypt)) {
            $id = decrypt($id_encrypt);
            $this->md_acc_sk_ketentuan->update(['id' => $id], $data);
            
            addlog('Edit SK Ketentuan', 'Mengedit dokumen SK/Ketentuan: ' . $data['nama_dokumen']);
            ajaxReturnDie('success', 'Dokumen berhasil diperbarui', 'reload_table');
        } else {
            $data['status']     = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->md_acc_sk_ketentuan->add($data);

            addlog('Tambah SK Ketentuan', 'Menambahkan dokumen SK/Ketentuan baru: ' . $data['nama_dokumen']);
            ajaxReturnDie('success', 'Dokumen berhasil ditambahkan', 'reload_table');
        }
    }

    public function get_detail($encrypted_id)
    {
        $id = decrypt($encrypted_id);
        $dt = $this->md_acc_sk_ketentuan->getById($id);
        if (!empty($dt)) {
            $row = $dt[0];
            $row->id = encrypt($row->id);
            $row->tanggal_dokumen = $row->tanggal_dokumen ? date('d-m-Y', strtotime($row->tanggal_dokumen)) : '';
            $row->masa_berlaku = $row->masa_berlaku ? date('d-m-Y', strtotime($row->masa_berlaku)) : '';
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
        
        $temp = $this->md_acc_sk_ketentuan->getById($id);
        if (!empty($temp)) {
            $this->md_acc_sk_ketentuan->update(['id' => $id], ['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            addlog('Hapus SK Ketentuan', 'Menghapus dokumen SK/Ketentuan: ' . $temp[0]->nama_dokumen);
            ajaxReturnDie('success', 'Dokumen berhasil dihapus', 'reload_table');
        } else {
            ajaxReturnDie('error', 'Dokumen tidak ditemukan');
        }
    }

    public function pagination()
    {
        $filters = [
            'tahun' => $this->input->post('filter_tahun', TRUE),
            'status_berlaku' => $this->input->post('filter_status_berlaku', TRUE)
        ];
        $dt    = $this->md_acc_sk_ketentuan->getAll($filters);
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
            $th[] = $row->tanggal_dokumen ? date('d-M-Y', strtotime($row->tanggal_dokumen)) : '-';
            $th[] = $row->masa_berlaku ? date('d-M-Y', strtotime($row->masa_berlaku)) : 'Seumur Hidup';
            $th[] = $link_download;
            $th[] = $btn_actions;
            
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        exit;
    }
}
