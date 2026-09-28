 <?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class pop_penjualan_produk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pop_penjualan_produk');
    }
	
	function id_navbar(){
		$id_navbar = "marketing";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']  	= 'v_pop_penjualan_produk';
        $page_data['page_title'] 	= 'Populasi Penjualan Produk';
        $page_data['page_desc']  	= 'Management Data Populasi Penjualan Produk';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama_pop_penjualan_produk'] = $this->input->post('nama_pop_penjualan_produk');
        $data['kategori'] = $this->input->post('kategori');
        // $data['wilayah'] = $this->input->post('wilayah');
        $data['link_download'] = $this->input->post('link_download');

        checkEmptyForm($data);
        $this->md_pop_penjualan_produk->add($data);

        //add log
        $aksi = 'Tambah Master Data';
        $ket = 'Menambahkan data Proce List - ' . $data['nama_pop_penjualan_produk'];
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');

        $id = decrypt($param1);
        $dt = $this->md_pop_penjualan_produk->getById($id);
        foreach ($dt as $row) {
            $row->id_pop_penjualan_produk = encrypt($row->id_pop_penjualan_produk);
        }
        echo json_encode($dt);
        die;
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id_pop_penjualan_produk    = decrypt($param);

        $data['status'] = 0;
        $this->md_pop_penjualan_produk->update(['id_pop_penjualan_produk' => $id_pop_penjualan_produk], $data);

        //add log
        $temp = $this->md_pop_penjualan_produk->getById($id_pop_penjualan_produk);
        $aksi = 'Hapus Master Data';
        $ket = 'Menghapus data pop_penjualan_produk - ' . $temp[0]->nama_pop_penjualan_produk;
        addlog($aksi, $ket);

        ajaxReturnDie('success', 'pop_penjualan_produk berhasil dihapus', 'reload_table');
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_pop_penjualan_produk'));
        $data['nama_pop_penjualan_produk'] = $this->input->post('nama_pop_penjualan_produk');
        $data['kategori'] = $this->input->post('kategori');
        // $data['wilayah'] = $this->input->post('wilayah');
        $data['link_download'] = $this->input->post('link_download');
        checkEmptyForm($data);

        $this->md_pop_penjualan_produk->update(['id_pop_penjualan_produk' => $id], $data);

        //add log
        $temp = $this->md_pop_penjualan_produk->getById($id);
        $aksi = 'Edit Master Data';
        $ket = 'Mengedit data pop_penjualan_produk - ' . $temp[0]->nama_pop_penjualan_produk;
        addlog($aksi, $ket);
        ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_pop_penjualan_produk->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_pop_penjualan_produk);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="pop_penjualan_produk/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $link_download = '<a href="' . $row->link_download . '"><i class="fas fa-download"></i> Download</a>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_pop_penjualan_produk;
            $th[] = $row->kategori;
            // $th[] = $row->wilayah;
            $th[] = $link_download;
            if (isAdmin() || isStafAdmin()) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
