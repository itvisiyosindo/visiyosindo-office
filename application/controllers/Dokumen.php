<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Dokumen extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_dokumen');
        $this->load->model('md_all');
        $this->load->model('md_pengguna');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        $id_navbar = "dokumen";
        return $id_navbar;
    }

    public function index($kode)
    {
        grantAccessFor('all');

        if ($kode == "rahasia") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'dokumen/v_dok_rahasia';
            $page_data['page_title']     = 'Dokumen Rahasia';
            $page_data['page_desc']      = 'Management Data Dokumen Rahasia';
            $page_data['list_kategori'] = $this->md_dokumen->getAktif('kategori');
            $this->load->view('index', $page_data);
        } else if ($kode == "umum") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'dokumen/v_dok_umum';
            $page_data['page_title']     = 'Dokumen Umum';
            $page_data['page_desc']      = 'Management Data Dokumen Umum';
            $page_data['list_kategori'] = $this->md_dokumen->getAktif('kategori');
            $this->load->view('index', $page_data);
        } else if ($kode == "product") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'dokumen/v_dok_product';
            $page_data['page_title']     = 'Dokumen Product';
            $page_data['page_desc']      = 'Management Data Dokumen Product';
            $page_data['list_kategori'] = $this->md_dokumen->getKategoriProductAktif();
            $this->load->view('index', $page_data);
        } else if ($kode == "kategori") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'dokumen/v_kategori_dok';
            $page_data['page_title']     = 'Kategori Dokumen';
            $page_data['page_desc']      = 'Management Data Kategori Dokumen';
            $this->load->view('index', $page_data);
        } else if ($kode == "bahan_presentasi") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'v_bhn_presentasi';
            $page_data['page_title']     = 'Dokumen Product';
            $page_data['page_desc']      = 'Management Data Kategori Dokumen Product';
            $this->load->view('index', $page_data);
        } else if ($kode == "brosur") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'v_brosur';
            $page_data['page_title']     = 'Dokumen Product';
            $page_data['page_desc']      = 'Management Data Kategori Dokumen Product';
            $this->load->view('index', $page_data);
        } else if ($kode == "manual_book") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'v_manual_book';
            $page_data['page_title']     = 'Dokumen Product';
            $page_data['page_desc']      = 'Management Data Kategori Dokumen Product';
            $this->load->view('index', $page_data);
        } else if ($kode == "visilab") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'dokumen/v_dok_visilab';
            $page_data['page_title']     = 'Dokumen Visilab';
            $page_data['page_desc']      = 'Management Data Dokumen Visilab';
            $page_data['list_kategori'] = $this->md_dokumen->getKategoriVisilabAktif();
            $this->load->view('index', $page_data);
        } else if ($kode == "umum_visilab") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'dokumen/v_dok_umum_visilab';
            $page_data['page_title']     = 'Dokumen Umum Visilab';
            $page_data['page_desc']      = 'Management Data Dokumen Umum Visilab';
            $page_data['list_kategori'] = $this->md_dokumen->getAktif('kategori_visilab');
            $this->load->view('index', $page_data);
        } else if ($kode == "kategori_visilab") {
            $page_data['switch']        = $this->id_navbar();
            $page_data['page_name']      = 'dokumen/v_kategori_dok_visilab';
            $page_data['page_title']     = 'Kategori Dokumen Visilab';
            $page_data['page_desc']      = 'Management Data Kategori Dokumen Visilab';
            $this->load->view('index', $page_data);
        }
    }

    /**
     * e-Suket - langsung via dokumen/e_suket tanpa index
     */
    public function e_suket()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'v_e_suket';
        $page_data['page_title']    = 'e-Suket';
        $page_data['page_desc']     = 'Management Data e-Suket (Surat Keterangan Elektronik)';
        $this->load->view('index', $page_data);
    }

    public function add($kode)
    {
        grantAccessFor('all');

        if ($kode == "rahasia") {
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['file']           = $this->input->post('file');
            $data['id_kategori']    = $this->input->post('kategori');
            checkEmptyForm($data);
            $data['created_by']     = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_rahasia');
            $this->md_dokumen->add($kode, $data);

            //add log
            $aksi = 'Tambah Dokumen Rahasia';
            $ket = 'Menambahkan data dokumen - ' . $data['nama_dokumen'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "umum") {
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['file']           = $this->input->post('file');
            $data['id_kategori']    = $this->input->post('kategori');
            checkEmptyForm($data);
            $data['created_by']     = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_umum');
            $this->md_dokumen->add($kode, $data);

            $dok = 'kategori';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->nama;


            $url = 'https://office.visiyosindo.id/dokumen/index/umum';

            $dataWa = [
                //'idPenerima1' 	=> 'Test Api Wa Group',
                //'idPenerima2' 	=> '',
                'idPenerima1'     => 'Visi Yosindo Medical',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Umum',
                'status'             => 'mengupload',
                'kategori'         => $kategori,
                'url'             => $url,
                'penerima'         => 'Bapak dan Ibu',
                'csname'             => $data['nama_dokumen']
            ];
            $this->notifWaGroup(1, $dataWa);




            //add log
            $aksi = 'Tambah Dokumen Umum';
            $ket = 'Menambahkan data dokumen - ' . $data['nama_dokumen'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "product") {
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['file']           = $this->input->post('file');
            $data['tgl_mulai']           = date_db_format($this->input->post('tgl_mulai', TRUE));
            $data['tgl_akhir']           = date_db_format($this->input->post('tgl_akhir', TRUE));
            $data['id_kategori']    = $this->input->post('kategori');
            checkEmptyForm($data);
            $data['created_by']     = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_product');
            $this->md_dokumen->add($kode, $data);


            $dok = 'katproduct';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->keterangan;

            $url = 'https://office.visiyosindo.id/dokumen/index/product/';


            //$lastGcId = $this->md_dokumen->getVisilabLastId();
            //$lastGcId = $lastGcId->id;

            $idTracking = encrypt($data['id_kategori']);

            $dataWa = [
                //'idPenerima1' 	=> 'Test Api Wa Group',
                //'idPenerima2' 	=> '',
                'idPenerima1'     => 'Visi Yosindo Medical',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Produk',
                'status'             => 'mengupload',
                'kategori'         => $kategori,
                'url'             => $url,
                'idTracking'         => $idTracking,
                'penerima'         => 'Bapak dan Ibu',
                'csname'             => $data['nama_dokumen']
            ];
            $this->notifWaGroup(1, $dataWa);

            //add log
            $aksi = 'Tambah Dokumen Product';
            $ket = 'Menambahkan data dokumen - ' . $data['nama_dokumen'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "kategori") {
            $data['nama']       = $this->input->post('nama');
            $data['deskripsi']  = $this->input->post('deskripsi');
            checkEmptyForm($data);
            $data['created_by'] = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_kategori');
            $this->md_dokumen->add($kode, $data);

            //add log
            $aksi = 'Tambah Kategori Dokumen';
            $ket = 'Menambahkan data kategori dokumen - ' . $data['nama'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "kategoriproduct") {
            $data['keterangan']       = $this->input->post('keterangan');
            checkEmptyForm($data);
            $data['created_by'] = sessPenggunaId();
            $this->md_all->reset_increment('masterdokumenproduct');
            $this->md_dokumen->add('kategoriproduct', $data);

            //add log
            $aksi = 'Tambah Kategori Dokumen Product';
            $ket = 'Menambahkan data kategori dokumen prodcuct- ' . $data['keterangan'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "visilab") {
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['file']           = $this->input->post('file');
            $data['tgl_mulai']           = date_db_format($this->input->post('tgl_mulai', TRUE));
            $data['tgl_akhir']           = date_db_format($this->input->post('tgl_akhir', TRUE));
            $data['id_kategori']    = $this->input->post('kategori');
            checkEmptyForm($data);
            $data['created_by']     = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_visilab');
            $this->md_dokumen->add($kode, $data);


            $dok = 'katvisilab';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->keterangan;

            $url = 'https://office.visiyosindo.id/dokumen/index/visilab/';


            //$lastGcId = $this->md_dokumen->getVisilabLastId();
            //$lastGcId = $lastGcId->id;

            $idTracking = encrypt($data['id_kategori']);

            $dataWa = [
                'idPenerima1'     => 'Test Api Wa Group',
                //   'idPenerima1' 	=> 'VISILAB',
                //'idPenerima2' 	=> '',
                //'idPenerima1' 	=> 'VISILAB',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Visilab',
                'status'             => 'mengupload',
                'kategori'         => $kategori,
                'url'             => $url,
                'idTracking'             => $idTracking,
                'penerima'         => 'Team Visilab',
                'csname'             => $data['nama_dokumen']
            ];
            $this->notifWaGroup(1, $dataWa);

            //add log
            $aksi = 'Tambah Dokumen Visilab';
            $ket = 'Menambahkan data dokumen - ' . $data['nama_dokumen'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "kategorivisilab") {
            $data['keterangan']       = $this->input->post('keterangan');
            checkEmptyForm($data);
            $data['created_by'] = sessPenggunaId();
            $this->md_all->reset_increment('masterdokumenvisilab');
            $this->md_dokumen->add('kategorivisilab', $data);

            //add log
            $aksi = 'Tambah Kategori Dokumen Visilab';
            $ket = 'Menambahkan data kategori dokumen Visilab- ' . $data['keterangan'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "umum_visilab") {
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['file']           = $this->input->post('file');
            $data['id_kategori']    = $this->input->post('kategori');
            checkEmptyForm($data);
            $data['created_by']     = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_umum');
            $this->md_dokumen->add($kode, $data);

            $dok = 'kategori';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->nama;


            $url = 'https://office.visiyosindo.id/dokumen/index/umum_visilab';

            /*$dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      //'idPenerima2' 	=> '',
                      'idPenerima1' 	=> 'VISILAB',
                      'idPenerima2' 	=> '',
                      'namaSurat'  	    => 'Dokumen Umum Visilab',
                      'status' 	        => 'mengupload',
                      'kategori' 	    => $kategori,
                      'url' 	        => $url,
                      'penerima' 	    => 'Bapak dan Ibu',
                      'csname' 	        => $data['nama_dokumen']
                  ];
                  $this->notifWaGroup(1, $dataWa); */




            //add log
            $aksi = 'Tambah Dokumen Umum';
            $ket = 'Menambahkan data dokumen - ' . $data['nama_dokumen'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        } else if ($kode == "kategori_visilab") {
            $data['nama']       = $this->input->post('nama');
            $data['deskripsi']  = $this->input->post('deskripsi');
            checkEmptyForm($data);
            $data['created_by'] = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_kategori');
            $this->md_dokumen->add($kode, $data);

            //add log
            $aksi = 'Tambah Kategori Dokumen';
            $ket = 'Menambahkan data kategori dokumen - ' . $data['nama'];
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data Berhasil Ditambahkan', 'reload_table');
        }
    }

    public function edit($kode, $param1)
    {
        grantAccessFor('all');

        if ($kode == "rahasia") {
            $id = decrypt($param1);
            $dt = $this->md_dokumen->getById($kode, $id);
            foreach ($dt as $row) {
                $row->id = encrypt($row->id);
            }
            echo json_encode($dt);
            die;
        } else if ($kode == "umum") {
            $id = decrypt($param1);
            $dt = $this->md_dokumen->getById($kode, $id);
            foreach ($dt as $row) {
                $row->id = encrypt($row->id);
            }
            echo json_encode($dt);
            die;
        } else if ($kode == "product") {
            $id = decrypt($param1);
            $dt = $this->md_dokumen->getById($kode, $id);
            foreach ($dt as $row) {
                $row->id = encrypt($row->id);
            }
            echo json_encode($dt);
            die;
        } else if ($kode == "kategori") {
            $id = decrypt($param1);
            $dt = $this->md_dokumen->getById($kode, $id);
            foreach ($dt as $row) {
                $row->id = encrypt($row->id);
            }
            echo json_encode($dt);
            die;
        } else if ($kode == "visilab") {
            $id = decrypt($param1);
            $dt = $this->md_dokumen->getById($kode, $id);
            foreach ($dt as $row) {
                $row->id = encrypt($row->id);
            }
            echo json_encode($dt);
            die;
        } else if ($kode == "umum_visilab") {
            $id = decrypt($param1);
            $dt = $this->md_dokumen->getById($kode, $id);
            foreach ($dt as $row) {
                $row->id = encrypt($row->id);
            }
            echo json_encode($dt);
            die;
        } else if ($kode == "kategori_visilab") {
            $id = decrypt($param1);
            $dt = $this->md_dokumen->getById($kode, $id);
            foreach ($dt as $row) {
                $row->id = encrypt($row->id);
            }
            echo json_encode($dt);
            die;
        }
    }

    public function delete($kode, $idDokumen)
    {
        grantAccessFor('all');

        if ($kode == "rahasia") {
            $id_dokumen    = decrypt($idDokumen);
            $data['status'] = 0;
            $this->md_dokumen->update($kode, ['id' => $id_dokumen], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id_dokumen);
            $aksi = 'Hapus Master Data';
            $ket = 'Menghapus data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'dokumen berhasil dihapus', 'reload_table');
        } else if ($kode == "umum") {
            $id_dokumen    = decrypt($idDokumen);
            $data['status'] = 0;
            $this->md_dokumen->update($kode, ['id' => $id_dokumen], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id_dokumen);
            $aksi = 'Hapus Master Data';
            $ket = 'Menghapus data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'dokumen berhasil dihapus', 'reload_table');
        } else if ($kode == "product") {
            $id_dokumen    = decrypt($idDokumen);
            $data['status'] = 0;
            $this->md_dokumen->update($kode, ['id' => $id_dokumen], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id_dokumen);
            $aksi = 'Hapus Master Data';
            $ket = 'Menghapus data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'dokumen berhasil dihapus', 'reload_table');
        } else if ($kode == "kategori") {
            $id_delete    = decrypt($idDokumen);
            $data['status'] = 0;
            $this->md_dokumen->update($kode, ['id' => $id_delete], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id_delete);
            $aksi = 'Hapus Master Data';
            $ket = 'Menghapus data kategori dokumen - ' . $temp[0]->nama;
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'kategori dokumen berhasil dihapus', 'reload_table');
        } else if ($kode == "visilab") {
            $id_dokumen    = decrypt($idDokumen);
            $data['status'] = 0;
            $this->md_dokumen->update($kode, ['id' => $id_dokumen], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id_dokumen);
            $aksi = 'Hapus Master Data';
            $ket = 'Menghapus data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'dokumen berhasil dihapus', 'reload_table');
        } else if ($kode == "umum_visilab") {
            $id_dokumen    = decrypt($idDokumen);
            $data['status'] = 0;
            $this->md_dokumen->update($kode, ['id' => $id_dokumen], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id_dokumen);
            $aksi = 'Hapus Master Data';
            $ket = 'Menghapus data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'dokumen berhasil dihapus', 'reload_table');
        } else if ($kode == "kategori_visilab") {
            $id_delete    = decrypt($idDokumen);
            $data['status'] = 0;
            $this->md_dokumen->update($kode, ['id' => $id_delete], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id_delete);
            $aksi = 'Hapus Master Data';
            $ket = 'Menghapus data kategori dokumen - ' . $temp[0]->nama;
            addlog($aksi, $ket);

            ajaxReturnDie('success', 'kategori dokumen berhasil dihapus', 'reload_table');
        }
    }

    public function update($kode, $param = "")
    {
        grantAccessFor('all');

        if ($kode == "rahasia") {
            $id = decrypt($this->input->post('id_dokumen'));
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['id_kategori']    = $this->input->post('kategori');
            $data['file']           = $this->input->post('file');
            $data['last_updated_by'] = sessPenggunaId();
            $data['last_updated_at'] = date('y-m-d H:i:s');
            checkEmptyForm($data);
            $this->md_dokumen->update($kode, ['id' => $id], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id);
            $aksi = 'Edit Master Data';
            $ket = 'Mengedit data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
        } else if ($kode == "umum") {
            $id = decrypt($this->input->post('id_dokumen'));
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['id_kategori']    = $this->input->post('kategori');
            $data['file']           = $this->input->post('file');
            $data['last_updated_by'] = sessPenggunaId();
            $data['last_updated_at'] = date('y-m-d H:i:s');
            checkEmptyForm($data);
            $this->md_dokumen->update($kode, ['id' => $id], $data);


            $dok = 'kategori';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->nama;


            $url = 'https://office.visiyosindo.id/dokumen/index/umum';

            $dataWa = [
                //'idPenerima1' 	=> 'Test Api Wa Group',
                //'idPenerima2' 	=> '',
                'idPenerima1'     => 'Visi Yosindo Medical',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Umum',
                'status'             => 'mengedit',
                'kategori'         => $kategori,
                'url'             => $url,
                'penerima'         => 'Bapak dan Ibu',
                'csname'             => $data['nama_dokumen']
            ];
            $this->notifWaGroup(1, $dataWa);




            //add log
            $temp = $this->md_dokumen->getById($kode, $id);
            $aksi = 'Edit Master Data';
            $ket = 'Mengedit data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
        } else if ($kode == "product") {
            $id = decrypt($this->input->post('id_dokumen'));
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['id_kategori']    = $this->input->post('kategori');
            $data['file']           = $this->input->post('file');
            $data['tgl_mulai']           = date_db_format($this->input->post('tgl_mulai', TRUE));
            $data['tgl_akhir']           = date_db_format($this->input->post('tgl_akhir', TRUE));
            $data['last_updated_by'] = sessPenggunaId();
            $data['last_updated_at'] = date('y-m-d H:i:s');
            checkEmptyForm($data);
            $this->md_dokumen->update($kode, ['id' => $id], $data);

            $dok = 'katproduct';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->keterangan;

            $url = 'https://office.visiyosindo.id/dokumen/index/product/';
            $idTracking = encrypt($data['id_kategori']);

            $dataWa = [


                //'idPenerima1' 	=> 'Test Api Wa Group',
                //'idPenerima2' 	=> '',
                'idPenerima1'     => 'Visi Yosindo Medical',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Produk',
                'status'             => 'mengedit',
                'kategori'         => $kategori,
                'url'             => $url,
                'idTracking'         => $idTracking,
                'penerima'         => 'Bapak dan Ibu',
                'csname'             => $data['nama_dokumen']
            ];
            $this->notifWaGroup(1, $dataWa);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id);
            $aksi = 'Edit Master Data';
            $ket = 'Mengedit data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
        } else if ($kode == "kategori") {
            $id = decrypt($this->input->post('id_update'));
            $data['nama']           = $this->input->post('nama');
            $data['deskripsi']           = $this->input->post('deskripsi');
            $data['last_updated_by'] = sessPenggunaId();
            $data['last_updated_at'] = date('y-m-d H:i:s');
            checkEmptyForm($data);
            $this->md_dokumen->update($kode, ['id' => $id], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id);
            $aksi = 'Edit Master Data Kategori Dokumen';
            $ket = 'Mengedit data kategori dokumen - ' . $temp[0]->nama;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
        } else if ($kode == "visilab") {
            $id = decrypt($this->input->post('id_dokumen'));
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['id_kategori']    = $this->input->post('kategori');
            $data['file']           = $this->input->post('file');
            $data['tgl_mulai']           = date_db_format($this->input->post('tgl_mulai', TRUE));
            $data['tgl_akhir']           = date_db_format($this->input->post('tgl_akhir', TRUE));
            $data['last_updated_by'] = sessPenggunaId();
            $data['last_updated_at'] = date('y-m-d H:i:s');
            //checkEmptyForm($data);
            $this->md_dokumen->update($kode, ['id' => $id], $data);


            $dok = 'katvisilab';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->keterangan;

            $url = 'https://office.visiyosindo.id/dokumen/index/visilab/';
            $idTracking = encrypt($data['id_kategori']);

            $dataWa = [


                //   'idPenerima1' 	=> 'VISILAB',
                'idPenerima1'     => 'Test Api Wa Group',
                //'idPenerima2' 	=> '',
                //'idPenerima1' 	=> 'VISILAB',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Visilab',
                'status'             => 'mengupdate',
                'kategori'         => $kategori,
                'url'             => $url,
                'idTracking'         => $idTracking,
                'penerima'         => 'Team Visilab',
                'csname'             => $data['nama_dokumen']
            ];
            $this->notifWaGroup(1, $dataWa);


            //add log
            $temp = $this->md_dokumen->getById($kode, $id);
            $aksi = 'Edit Master Data';
            $ket = 'Mengedit data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
        } else if ($kode == "umum_visilab") {
            $id = decrypt($this->input->post('id_dokumen'));
            $data['nama_dokumen']   = $this->input->post('nama');
            $data['id_kategori']    = $this->input->post('kategori');
            $data['file']           = $this->input->post('file');
            $data['last_updated_by'] = sessPenggunaId();
            $data['last_updated_at'] = date('y-m-d H:i:s');
            checkEmptyForm($data);
            $this->md_dokumen->update($kode, ['id' => $id], $data);


            $dok = 'kategori';
            $ambilKategori     = $this->md_dokumen->getById($dok, $data['id_kategori']);
            $kategori        = $ambilKategori[0]->nama;


            $url = 'https://office.visiyosindo.id/dokumen/index/umum_visilab';

            /* $dataWa = [
                      //'idPenerima1' 	=> 'Test Api Wa Group',
                      //'idPenerima2' 	=> '',
                      'idPenerima1' 	=> 'VISILAB',
                      'idPenerima2' 	=> '',
                      'namaSurat'  	    => 'Dokumen Umum Visilab',
                      'status' 	        => 'mengedit',
                      'kategori' 	    => $kategori,
                      'url' 	        => $url,
                      'penerima' 	    => 'Bapak dan Ibu',
                      'csname' 	        => $data['nama_dokumen']
                  ];
                  $this->notifWaGroup(1, $dataWa); */




            //add log
            $temp = $this->md_dokumen->getById($kode, $id);
            $aksi = 'Edit Master Data';
            $ket = 'Mengedit data dokumen - ' . $temp[0]->nama_dokumen;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
        } else if ($kode == "kategori_visilab") {
            $id = decrypt($this->input->post('id_update'));
            $data['nama']           = $this->input->post('nama');
            $data['deskripsi']           = $this->input->post('deskripsi');
            $data['last_updated_by'] = sessPenggunaId();
            $data['last_updated_at'] = date('y-m-d H:i:s');
            checkEmptyForm($data);
            $this->md_dokumen->update($kode, ['id' => $id], $data);

            //add log
            $temp = $this->md_dokumen->getById($kode, $id);
            $aksi = 'Edit Master Data Kategori Dokumen';
            $ket = 'Mengedit data kategori dokumen - ' . $temp[0]->nama;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'Data berhasil diupdate', 'reload_table');
        }
    }

    public function paginationproduct($id)
    {
        $dt             = $this->md_dokumen->getAllProduct(decrypt($id));
        $start          = $this->input->post('start');
        $data           = array();
        foreach ($dt['data'] as $row) {
            $id         = encrypt($row->id_dokumen);
            $li_btn     = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="dokumen/delete/product/' . $id . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
            $link_download = '<a href="' . $row->file . '"><i class="fas fa-download"></i> Download</a>';
            $masa_berlaku = '';
            $kategori_produk = strtoupper(trim((string) $row->kategori));
            $tampilkan_masa_berlaku = in_array($kategori_produk, ['LOA', 'AKL', 'ISO'], true);

            if ($tampilkan_masa_berlaku) {
                $tgl_mulai = strtotime((string) $row->tgl_mulai);
                $tgl_akhir = strtotime((string) $row->tgl_akhir);
                if ($tgl_mulai && $tgl_akhir) {
                    $mulai = date('d-M-Y', $tgl_mulai);
                    $akhir = date('d-M-Y', $tgl_akhir);
                    $masa_berlaku = $mulai . ' s/d ' . $akhir;
                }
            }
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_dokumen;
            $th[] = $masa_berlaku;
            $th[] = $row->kategori;
            $th[] = $link_download;
            if (isAdmin() || isGa() || sessPenggunaId() == '74' || sessPenggunaId() == '83' || sessPenggunaId() == '84' || sessPenggunaId() == '86' || sessPenggunaId() == '750' || sessPenggunaId() == '92' || sessPenggunaId() == '766') {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
    public function paginationvisilab($id)
    {
        $dt             = $this->md_dokumen->getAllVisilab(decrypt($id));
        $start          = $this->input->post('start');
        $data           = array();
        foreach ($dt['data'] as $row) {
            $id         = encrypt($row->id_dokumen);
            $li_btn     = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="dokumen/delete/visilab/' . $id . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
            $link_download = '<a href="' . $row->file . '"><i class="fas fa-download"></i> Download</a>';
            $mulai = date('d-M-Y', strtotime($row->tgl_mulai));
            $akhir = date('d-M-Y', strtotime($row->tgl_akhir));


            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama_dokumen;
            if ($row->tgl_mulai == '1970-01-01' || empty($row->tgl_mulai)) {
                $th[] = "";
            } else {
                $masa_berlaku = $mulai . ' s/d ' . $akhir;
                $th[] = $masa_berlaku;
            }
            $th[] = $row->kategori;
            $th[] = $link_download;
            if (isAdmin() || isGa() || sessPenggunaId() == '75' || sessPenggunaId() == '74' || sessPenggunaId() == '83' || sessPenggunaId() == '84' || sessPenggunaId() == '86' || sessPenggunaId() == '769' || sessPenggunaId() == '736' || sessPenggunaId() == '15' || sessPenggunaId() == '23' || sessPenggunaId() == '54' || sessPenggunaId() == '754' || sessPenggunaId() == '751') {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
    public function pagination($kode)
    {
        //grantAccessFor(['Administrator', 'Staf Admin', 'Marketing']);
        grantAccessFor('all');

        if ($kode == "rahasia") {
            $dt             = $this->md_dokumen->getAll($kode);
            $start          = $this->input->post('start');
            $data           = array();
            foreach ($dt['data'] as $row) {
                $id       = encrypt($row->id_dokumen);
                $li_btn   = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="dokumen/delete/' . $kode . '/' . $id . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
                $link_download = '<a href="' . $row->file . '"><i class="fas fa-download"></i> Download</a>';
                $th = array();
                $th[] = ++$start . '.';
                $th[] = $row->nama_dokumen;
                $th[] = $row->kategori;
                $th[] = $link_download;
                if (isAdmin() || isGa() || sessPenggunaId() == '74' || sessPenggunaId() == '83' || sessPenggunaId() == '84' || sessPenggunaId() == '86' || sessPenggunaId() == '766') {
                    $th[] = $li_btn;
                }
                $data[] = $th;
            }
            $dt['data']         = $data;
            echo json_encode($dt);
            die;
        } else if ($kode == "umum") {
            $dt             = $this->md_dokumen->getAll($kode);
            $start          = $this->input->post('start');
            $data           = array();
            foreach ($dt['data'] as $row) {
                $id         = encrypt($row->id_dokumen);
                $li_btn     = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="dokumen/delete/' . $kode . '/' . $id . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
                $link_download = '<a href="' . $row->file . '"><i class="fas fa-download"></i> Download</a>';
                $th = array();
                $th[] = ++$start . '.';
                $th[] = $row->nama_dokumen;
                $th[] = $row->kategori;
                $th[] = $link_download;
                //if (isAdmin() || isGa() || sessPenggunaId()=='74' || sessPenggunaId()=='83' || sessPenggunaId()=='84' || sessPenggunaId()=='86') {
                $th[] = $li_btn;
                //}
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($kode == "kategori") {
            $dt    = $this->md_dokumen->getAll($kode);
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
                $id       = encrypt($row->id_kategori);
                $li_btn   = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="dokumen/delete/' . $kode . '/' . $id . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
                $th = array();
                $th[] = ++$start . '.';
                $th[] = $row->nama;
                $th[] = $row->deskripsi;
                // $th[] = $link_download;
                if (isAdmin() || isGa() || sessPenggunaId() == '74' || sessPenggunaId() == '83' || sessPenggunaId() == '84' || sessPenggunaId() == '86') {
                    $th[] = $li_btn;
                }
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($kode == "umum_visilab") {
            $dt             = $this->md_dokumen->getAll($kode);
            $start          = $this->input->post('start');
            $data           = array();
            foreach ($dt['data'] as $row) {
                $id         = encrypt($row->id_dokumen);

                if (isAdmin() || isGa() || sessPenggunaId() == '74' || sessPenggunaId() == '83' || sessPenggunaId() == '84' || sessPenggunaId() == '86' || sessPenggunaId() == '754' || sessPenggunaId() == '736' || sessPenggunaId() == '15' || sessPenggunaId() == '23' || sessPenggunaId() == '54' || sessPenggunaId() == '33' || sessPenggunaId() == '69' || sessPenggunaId() == '751' || sessPenggunaId() == '75') {
                    $li_btn     = '
                        <center>
                        <div class="btn-group" role="group" aria-label="First group">
                            <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="dokumen/delete/' . $kode . '/' . $id . '"><i class="bx bx-trash"></i></button>
                        </div>
                        </center>';
                } else {
                    $li_btn     = '<center> - </center>';
                }
                $link_download = '<a href="' . $row->file . '"><i class="fas fa-download"></i> Download</a>';
                $th = array();
                $th[] = ++$start . '.';
                $th[] = $row->nama_dokumen;
                $th[] = $row->kategori;
                $th[] = $link_download;
                //if (isAdmin() || isGa() || sessPenggunaId()=='74' || sessPenggunaId()=='83' || sessPenggunaId()=='84' || sessPenggunaId()=='86') {
                $th[] = $li_btn;
                //}
                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($kode == "kategori_visilab") {
            $dt    = $this->md_dokumen->getAll($kode);
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
                $id       = encrypt($row->id_kategori);
                $li_btn   = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="dokumen/delete/' . $kode . '/' . $id . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
                $th = array();
                $th[] = ++$start . '.';
                $th[] = $row->nama;
                $th[] = $row->deskripsi;
                $th[] = $li_btn;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        }
    }

    public function sendWa()
    {
        grantAccessFor('all');

        $id = decrypt($this->input->post('id_dokumen'));
        $hp = $this->input->post('wa_tujuan');
        $nama = $this->session->userdata('nama');
        $level = $this->session->userdata('login_type');
        $data = $this->md_dokumen->getById($id);
        $message = '&text=Nama%20Product%20%3A%20' . $data[0]->nama_dokumen . '%0A%0ALink%20dokumen%20%3A%20' . $data[0]->link_download . '%0A%0A%0Abest%20regard%2C%0A' . $nama . ',%20(' . $level . ')%0APT%20VISI%20YOSINDO%20MEDIKAL';
        $hp_tujuan = hp($hp);
        $link = 'https://api.whatsapp.com/send?phone=' . $hp_tujuan . $message;
        redirect($link);
        die;
    }

    public function notifWaGroup($ulang, $detail)
    {
        //ambil data pengaju
        $ambilDataPengaju     = $this->md_pengguna->getById(sessPenggunaId());
        $namaPengaju            = $ambilDataPengaju[0]->nama;



        for ($i = 1; $i <= $ulang; $i++) {
            if ($i == 1) {
                $idpenerima = $detail['idPenerima1'];
                //$penerima   = '_Bapak dan Ibu_';
            } else if ($i == 2) {
                $idpenerima = $detail['idPenerima2'];
                //$penerima   = '_Team Warehouse_';
            }


            //abaikan error
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 0);
            //



            $dataWa = [
                'namaSurat'     => urlencode($detail['namaSurat']),
                'noPenerima'     => $idpenerima,
                'namaPengaju'   => $namaPengaju,
                'csname'         => urlencode($detail['csname']),
                'kategori'         => $detail['kategori'],
                'status'         => $detail['status'],
                'url'             => $detail['url'],
                'idTracking'         => $detail['idTracking'],
                'namaPenerima'    => $detail['penerima']
            ];

            waDokumenGroup($dataWa);
        }
    }
}
