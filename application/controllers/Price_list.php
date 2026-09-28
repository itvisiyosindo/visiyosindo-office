<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class price_list extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_price_list');
    }

    function id_navbar()
    {
        $id_navbar = "marketing";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']         = $this->id_navbar();
        $page_data['page_name']      = 'v_price_list';
        $page_data['page_title']     = 'Price List';
        $page_data['page_desc']        = 'Management Data Price List';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_price_list'] = $this->input->post('nama_price_list');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['diskon']         = $this->input->post('diskon');
        $data['jenis']         = 1;
        $data['pengguna_id'] = sessPenggunaId();

        checkEmptyForm($data);
        $this->md_price_list->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Price List - ' . $data['nama_price_list'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function addKatalog()
    {
        grantAccessFor('all');

        $data['nama_price_list'] = $this->input->post('nama_price_list');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['diskon']         = $this->input->post('diskon');
        $data['jenis']         = 2;
        $data['pengguna_id'] = sessPenggunaId();

        checkEmptyForm($data);
        $this->md_price_list->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Price List - ' . $data['nama_price_list'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_price_list->getById($id);
        foreach ($dt as $row) {
            $row->id_price_list = encrypt($row->id_price_list);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_price_list    = decrypt($param);

        $data['status'] = 0;
        $data['pengguna_id'] = sessPenggunaId();
        $data['alasan'] = $this->input->post('alasan');

        $this->md_price_list->update($id_price_list, $data);

        //add log
        $temp = $this->md_price_list->getById($id_price_list);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data price_list - ' . $temp[0]->nama_price_list;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'price_list berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');
        if ($this->input->post('alasanedit') == '') {
            ajaxReturnDie('error', 'Alasan tidak boleh kosong..!!');
        }
        $id = decrypt($this->input->post('id_price_list'));
        $data['nama_price_list'] = $this->input->post('nama_price_list');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['diskon']         = $this->input->post('diskon');
        $data['alasan']         = $this->input->post('alasanedit');
        $data['pengguna_id'] = sessPenggunaId();

        $this->md_price_list->update($id, $data);

        //add log
        $temp = $this->md_price_list->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data price_list - ' . $temp[0]->nama_price_list;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_price_list->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_price_list);
            $li_btn   = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button onclick="dialoghapus(\'' . $id . '\');" id="btn-hapus-pricelist" type="button" class="btn btn-sm btn-danger btn-hapus" title="Hapus Data" data-id="' . $id . '"><i class="bx bx-trash"></i></button>
                </div>';

            // Menambahkan Button Preview
            $link_download = '
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-info btn-preview" data-url="' . $row->link_download . '" title="Preview">
                        <i class="fas fa-eye"></i> Preview
                    </button>
                    <a href="' . $row->link_download . '" target="_blank" class="btn btn-sm btn-secondary" title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                </div>';

            if (strpos($row->diskon, 'Disc') !== false) {
                $namaprice = $row->nama_price_list . '&nbsp;<sup><span class="badge badge-danger" style="vertical-align: top">' . 'Disc' . '</span></sup>';
            } else if (strpos($row->diskon, 'Promo') !== false) {
                $namaprice = $row->nama_price_list . '&nbsp;<sup><span class="badge badge-success" style="vertical-align: top">' . 'Promo' . '</span></sup>';
            } else {
                $namaprice = $row->nama_price_list;
            }

            if ($row->data_deleted > $row->data_created) {
                $tglbuat = date('Y-m-d', strtotime($row->data_deleted)) . '&nbsp;<sup><span class="badge badge-info" style="vertical-align: top">' . 'edited' . '</span></sup>';
            } else {
                $tglbuat = date('Y-m-d', strtotime($row->data_created));
            }

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $namaprice;
            $th[] = $row->kategori;
            $th[] = $row->diskon;
            $th[] = $link_download;
            $th[] = $row->nama;
            $th[] = $tglbuat;
            if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107') {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function paginationKatalog()
    {
        grantAccessFor('all');

        $dt    = $this->md_price_list->getAllKatalog();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_price_list);
            $li_btn   = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button onclick="dialoghapus(\'' . $id . '\');" id="btn-hapus-pricelist" type="button" class="btn btn-sm btn-danger btn-hapus" title="Hapus Data" data-id="' . $id . '"><i class="bx bx-trash"></i></button>
                </div>';

            // Menambahkan Button Preview
            $link_download = '
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-info btn-preview" data-url="' . $row->link_download . '" title="Preview">
                        <i class="fas fa-eye"></i> Preview
                    </button>
                    <a href="' . $row->link_download . '" target="_blank" class="btn btn-sm btn-secondary" title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                </div>';

            if (strpos($row->diskon, 'Disc') !== false) {
                $namaprice = $row->nama_price_list . '&nbsp;<sup><span class="badge badge-danger" style="vertical-align: top">' . 'Disc' . '</span></sup>';
            } else if (strpos($row->diskon, 'Promo') !== false) {
                $namaprice = $row->nama_price_list . '&nbsp;<sup><span class="badge badge-success" style="vertical-align: top">' . 'Promo' . '</span></sup>';
            } else {
                $namaprice = $row->nama_price_list;
            }

            if ($row->data_deleted > $row->data_created) {
                $tglbuat = date('Y-m-d', strtotime($row->data_deleted)) . '&nbsp;<sup><span class="badge badge-info" style="vertical-align: top">' . 'edited' . '</span></sup>';
            } else {
                $tglbuat = date('Y-m-d', strtotime($row->data_created));
            }

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $namaprice;
            $th[] = $row->kategori;
            $th[] = $row->diskon;
            $th[] = $link_download;
            $th[] = $row->nama;
            $th[] = $tglbuat;
            if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107') {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function addEndoscopy()
    {
        grantAccessFor('all');
        $data['nama_price_list'] = $this->input->post('nama_price_list');
        $data['kategori']       = $this->input->post('kategori');
        $data['link_download']  = $this->input->post('link_download');
        $data['diskon']         = $this->input->post('diskon');
        $data['jenis']          = 3;
        $data['pengguna_id']    = sessPenggunaId();

        checkEmptyForm($data);
        $this->md_price_list->add($data);

        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Brosur Endoscopy - ' . $data['nama_price_list'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Endoscopy Berhasil Ditambahkan', 'reload_table');
    }

    public function paginationEndoscopy()
    {
        grantAccessFor('all');
        $dt    = $this->md_price_list->getAllEndoscopy();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id      = encrypt($row->id_price_list);
            $li_btn   = '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                <button onclick="dialoghapus(\'' . $id . '\');" type="button" class="btn btn-sm btn-danger btn-hapus" data-id="' . $id . '"><i class="bx bx-trash"></i></button>
            </div>';

            $link_download = '
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-info btn-preview" data-url="' . $row->link_download . '" title="Preview">
                        <i class="fas fa-eye"></i> Preview
                    </button>
                    <a href="' . $row->link_download . '" target="_blank" class="btn btn-sm btn-secondary" title="Download Langsung">
                        <i class="fas fa-download"></i>
                    </a>
                </div>';
            $namaprice = $row->nama_price_list;

            if ($row->data_deleted > $row->data_created) {
                $tglbuat = date('Y-m-d', strtotime($row->data_deleted)) . '&nbsp;<sup><span class="badge badge-info">edited</span></sup>';
            } else {
                $tglbuat = date('Y-m-d', strtotime($row->data_created));
            }

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $namaprice;
            $th[] = $row->kategori;
            $th[] = $row->diskon;
            $th[] = $link_download;
            $th[] = $row->nama;
            $th[] = $tglbuat;
            if (isAdmin() || isStafAdmin() || sessPenggunaId() == '107') {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
