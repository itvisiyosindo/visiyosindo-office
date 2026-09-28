<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dokumen_rahasia_visilab extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    // Load model baru
    $this->load->model('md_dokumen_rahasia_visilab', 'model_doc');
    $this->load->model('md_dokumen');
    $this->load->model('md_all');
    $this->load->model('md_pengguna');
    $this->load->helper('whatsapp_helper');
  }

  public function index()
  {
    grantAccessFor('all');

    $page_data['switch']        = "dokumen";
    $page_data['page_name']     = 'visilab/v_dokumen_rahasia';
    $page_data['page_title']    = 'Dokumen Rahasia Visilab';
    $page_data['page_desc']     = 'Management Data Dokumen Rahasia Visilab';

    // Pastikan mengambil list dari tabel kategori yang benar
    $page_data['list_kategori'] = $this->db->get('dokumen_kategori_visilab')->result();

    $this->load->view('index', $page_data);
  }

  public function pagination()
  {
    $list = $this->model_doc->get_datatables();
    $data = array();
    $no = $_POST['start'];

    foreach ($list as $field) {
      $no++;
      $row = array();

      $row[] = $no . ".";
      $row[] = $field->nama_dokumen;
      $row[] = $field->nama_kategori;

      $link_download = '<a href="' . $field->file . '" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-download"></i> Download</a>';
      $row[] = $link_download;

      $btn_edit = '<button type="button" class="btn btn-sm btn-primary btn-edit" onclick="edit_data(' . $field->id . ')" title="Edit"><i class="fa fa-pencil-alt"></i></button>';

      // --- PERBAIKAN UTAMA ADA DI SINI ---
      // HAPUS class 'btn-delete' agar tidak konflik dengan global.js
      // GANTI menjadi 'btn-danger' saja atau tambah class unik lain
      $btn_delete = '<button type="button" class="btn btn-sm btn-danger" onclick="delete_data(' . $field->id . ')" title="Hapus"><i class="fa fa-trash"></i></button>';
      // ------------------------------------

      $row[] = '<div class="btn-group">' . $btn_edit . $btn_delete . '</div>';

      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->model_doc->count_all(),
      "recordsFiltered" => $this->model_doc->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function add()
  {
    grantAccessFor('all');

    // 1. Validasi Input Server Side
    $this->load->library('form_validation');
    $this->form_validation->set_rules('nama', 'Nama Dokumen', 'required|trim');
    $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim|numeric');
    $this->form_validation->set_rules('file', 'Link File', 'required|trim');

    if ($this->form_validation->run() == FALSE) {
      $errors = validation_errors();
      $clean_error = str_replace(['<p>', '</p>'], '', $errors);
      echo json_encode(array("status" => FALSE, "msg" => $clean_error));
      return;
    }

    // 2. Siapkan Data Array
    $data = array(
      'nama_dokumen' => $this->input->post('nama', TRUE),
      'id_kategori'  => $this->input->post('kategori', TRUE),
      'file'         => $this->input->post('file', TRUE),
      'status'       => 1, // Default Aktif
      'perusahaan'   => 1, // Default Visilab
      'created_by'   => sessPenggunaId(),
      'created_at'   => date('Y-m-d H:i:s')
    );

    checkEmptyForm($data);

    $nama_tabel = 'dokumen_rahasia_visilab';
    $this->md_all->reset_increment($nama_tabel);

    $insert_id = $this->model_doc->add($data);

    // PERBAIKAN: Mengambil nama kategori dari tabel 'dokumen_kategori_visilab'
    $kategori_db = $this->db->get_where('dokumen_kategori_visilab', ['id' => $data['id_kategori']])->row();
    $nama_kategori = $kategori_db ? $kategori_db->nama : 'Umum';

    $url_wa     = base_url('Dokumen_rahasia_visilab');
    $idTracking = encrypt($data['id_kategori']);

    $dataWa = [
      //  'idPenerima1' => 'Visi Yosindo Medical',
      'idPenerima1' => 'Test Api Wa Group',
      'idPenerima2' => '',
      'namaSurat'   => 'Dokumen Rahasia Visilab',
      'status'      => 'mengupload',
      'kategori'    => $nama_kategori,
      'url'         => $url_wa,
      'idTracking'  => $idTracking,
      'penerima'    => 'Team Visilab',
      'csname'      => $data['nama_dokumen']
    ];

    // $this->notifWaGroup(1, $dataWa);

    $aksi = 'Tambah Dokumen Rahasia Visilab';
    $ket  = 'Menambahkan dokumen baru: ' . $data['nama_dokumen'];
    addlog($aksi, $ket);

    echo json_encode(array("status" => TRUE, "msg" => "Data Berhasil Ditambahkan"));
  }

  public function update()
  {
    $id = $this->input->post('id_dokumen');
    $data = array(
      'nama_dokumen'    => $this->input->post('nama'),
      'id_kategori'     => $this->input->post('kategori'),
      'file'            => $this->input->post('file'),
      'last_updated_by' => sessPenggunaId(),
      'last_updated_at' => date('Y-m-d H:i:s')
    );

    $this->model_doc->update($id, $data);
    echo json_encode(array("status" => TRUE, "msg" => "Data berhasil diupdate"));
  }

  public function edit($id)
  {
    $data = $this->model_doc->getById($id);
    echo json_encode($data);
  }

  public function delete($id)
  {
    $this->model_doc->delete($id);
    echo json_encode(array("status" => TRUE, "msg" => "Data berhasil dihapus"));
  }
}
