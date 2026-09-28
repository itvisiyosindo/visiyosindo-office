<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_acc_bukti_lapor_pajak $md_acc_bukti_lapor_pajak
 */
class Acc_bukti_lapor_pajak extends CI_Controller
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
        
        $this->load->model('md_acc_bukti_lapor_pajak');
    }

    public function id_navbar()
    {
        return 'accounting';
    }

    public function index()
    {
        $page_data['switch']     = $this->id_navbar();
        $page_data['page_name']  = 'acc_pemasok/v_bukti_lapor_pajak';
        $page_data['page_title'] = 'Bukti Lapor Pajak';
        $page_data['page_desc']  = 'Manajemen Dokumen Bukti Pelaporan Pajak Perusahaan';
        $this->load->view('index', $page_data);
    }

    public function save()
    {
        $id_encrypt = $this->input->post('id');
        
        $data['kategori']         = $this->input->post('kategori', TRUE);
        $data['nama_dokumen']     = $this->input->post('nama_dokumen', TRUE);
        $data['tahun_pelaporan']  = $this->input->post('tahun_pelaporan') ? intval($this->input->post('tahun_pelaporan')) : NULL;
        $data['tanggal_lapor']    = $this->input->post('tanggal_lapor') ? date_db_format($this->input->post('tanggal_lapor', TRUE)) : NULL;
        $data['batas_akhir']      = $this->input->post('batas_akhir') ? date_db_format($this->input->post('batas_akhir', TRUE)) : NULL;
        $data['link_dokumen']     = $this->input->post('link_dokumen', TRUE);
        $data['updated_at']       = date('Y-m-d H:i:s');

        checkEmptyForm([$data['kategori'], $data['nama_dokumen']]);

        if (!empty($id_encrypt)) {
            $id = decrypt($id_encrypt);
            $this->md_acc_bukti_lapor_pajak->update(['id' => $id], $data);
            
            addlog('Edit Bukti Lapor Pajak', 'Mengedit bukti lapor pajak (' . $data['kategori'] . '): ' . $data['nama_dokumen']);
            ajaxReturnDie('success', 'Dokumen Pajak berhasil diperbarui', 'reload_table');
        } else {
            $data['status']     = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->md_acc_bukti_lapor_pajak->add($data);

            addlog('Tambah Bukti Lapor Pajak', 'Menambahkan bukti lapor pajak (' . $data['kategori'] . ') baru: ' . $data['nama_dokumen']);
            ajaxReturnDie('success', 'Dokumen Pajak berhasil ditambahkan', 'reload_table');
        }
    }

    public function get_detail($encrypted_id)
    {
        $id = decrypt($encrypted_id);
        $dt = $this->md_acc_bukti_lapor_pajak->getById($id);
        if (!empty($dt)) {
            $row = $dt[0];
            $row->id = encrypt($row->id);
            $row->tanggal_lapor = $row->tanggal_lapor ? date('d-m-Y', strtotime($row->tanggal_lapor)) : '';
            $row->batas_akhir = $row->batas_akhir ? date('d-m-Y', strtotime($row->batas_akhir)) : '';
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
        
        $temp = $this->md_acc_bukti_lapor_pajak->getById($id);
        if (!empty($temp)) {
            $this->md_acc_bukti_lapor_pajak->update(['id' => $id], ['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            addlog('Hapus Bukti Lapor Pajak', 'Menghapus bukti lapor pajak (' . $temp[0]->kategori . '): ' . $temp[0]->nama_dokumen);
            ajaxReturnDie('success', 'Dokumen Pajak berhasil dihapus', 'reload_table');
        } else {
            ajaxReturnDie('error', 'Dokumen Pajak tidak ditemukan');
        }
    }

    public function pagination()
    {
        $kategori = $this->input->post('kategori', TRUE);
        $filters = [
            'tahun_pelaporan' => $this->input->post('filter_tahun_pelaporan', TRUE)
        ];
        $dt    = $this->md_acc_bukti_lapor_pajak->getAll($kategori, $filters);
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
            $th[] = $row->tahun_pelaporan ? '<span class="badge badge-info">' . $row->tahun_pelaporan . '</span>' : '-';
            $th[] = $row->tanggal_lapor ? date('d-M-Y', strtotime($row->tanggal_lapor)) : '-';
            $th[] = $row->batas_akhir ? date('d-M-Y', strtotime($row->batas_akhir)) : '-';
            $th[] = $link_download;
            $th[] = $btn_actions;
            
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        exit;
    }
}
