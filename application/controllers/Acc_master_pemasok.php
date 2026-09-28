<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property Md_acc_pemasok $md_acc_pemasok
 */
class Acc_master_pemasok extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_acc_pemasok');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('encrypt_helper');

        // Access restriction
        if (!isAccountingUser()) {
            redirect(base_url('dashboard'));
        }
    }

    private function id_navbar()
    {
        return 'accounting';
    }

    public function index()
    {
        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'acc_pemasok/v_master_data';
        $page_data['page_title'] = 'Master Data Pemasok';
        $page_data['page_desc'] = 'Manajemen Parameter Master Pemasok';

        $page_data['status_list'] = $this->md_acc_pemasok->getStatusList();
        $page_data['tipe_list'] = $this->md_acc_pemasok->getTipeList();
        $page_data['product_list'] = $this->md_acc_pemasok->getProductList();
        $page_data['hospital_expo_list'] = $this->md_acc_pemasok->getHospitalExpoList();

        $this->load->view('index', $page_data);
    }

    // ==========================================
    // STATUS ACTIONS
    // ==========================================
    public function save_status()
    {
        $nama = $this->input->post('nama', TRUE);
        $urutan = $this->input->post('urutan', TRUE) ?: 0;

        if (empty($nama)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama status wajib diisi']);
            return;
        }

        $this->md_acc_pemasok->addStatus([
            'nama' => trim($nama),
            'urutan' => intval($urutan)
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Status Pemasok berhasil ditambahkan']);
    }

    public function delete_status($id)
    {
        if ($id) {
            $this->md_acc_pemasok->deleteStatus($id);
            echo json_encode(['status' => 'success', 'message' => 'Status Pemasok berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        }
    }

    // ==========================================
    // TIPE ACTIONS
    // ==========================================
    public function save_tipe()
    {
        $nama = $this->input->post('nama', TRUE);

        if (empty($nama)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama tipe wajib diisi']);
            return;
        }

        $this->md_acc_pemasok->addTipe([
            'nama' => trim($nama)
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Tipe Pemasok berhasil ditambahkan']);
    }

    public function delete_tipe($id)
    {
        if ($id) {
            $this->md_acc_pemasok->deleteTipe($id);
            echo json_encode(['status' => 'success', 'message' => 'Tipe Pemasok berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        }
    }

    // ==========================================
    // PRODUCT ACTIONS
    // ==========================================
    public function save_product()
    {
        $nama = $this->input->post('nama', TRUE);

        if (empty($nama)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama produk wajib diisi']);
            return;
        }

        $this->md_acc_pemasok->addProduct([
            'nama' => trim($nama)
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil ditambahkan']);
    }

    public function delete_product($id)
    {
        if ($id) {
            $this->md_acc_pemasok->deleteProduct($id);
            echo json_encode(['status' => 'success', 'message' => 'Produk berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        }
    }

    // ==========================================
    // HOSPITAL EXPO ACTIONS
    // ==========================================
    public function save_hospital_expo()
    {
        $nama = $this->input->post('nama', TRUE);
        $tahun = $this->input->post('tahun', TRUE);
        $lokasi = $this->input->post('lokasi', TRUE);

        if (empty($nama)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama event wajib diisi']);
            return;
        }

        $this->md_acc_pemasok->addHospitalExpo([
            'nama' => trim($nama),
            'tahun' => !empty($tahun) ? intval($tahun) : NULL,
            'lokasi' => trim($lokasi)
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Hospital Expo berhasil ditambahkan']);
    }

    public function delete_hospital_expo($id)
    {
        if ($id) {
            $this->md_acc_pemasok->deleteHospitalExpo($id);
            echo json_encode(['status' => 'success', 'message' => 'Hospital Expo berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        }
    }
}
