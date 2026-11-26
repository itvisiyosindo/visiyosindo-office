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

    public function index($kode, $param = null)
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
            $list_kategori = $this->md_dokumen->getKategoriProductAktif();
            $selected_kategori_id = $param ? (int)decrypt($param) : 0;
            $is_hs_code = false;
            foreach ($list_kategori as $kat) {
                if ((int)$kat->id === $selected_kategori_id && strtoupper(trim((string)$kat->keterangan)) === 'HS CODE') {
                    $is_hs_code = true;
                    break;
                }
            }
            $page_data['switch']               = $this->id_navbar();
            $page_data['page_name']            = 'dokumen/v_dok_product';
            $page_data['page_title']           = $is_hs_code ? 'HS Code' : 'Dokumen Product';
            $page_data['page_desc']            = $is_hs_code ? 'Management Data HS Code' : 'Management Data Dokumen Product';
            $page_data['list_kategori']        = $list_kategori;
            $page_data['selected_kategori_id'] = $selected_kategori_id;
            $page_data['is_hs_code']           = $is_hs_code;
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

            $ambilKategori = $this->md_dokumen->getById('kategori', $data['id_kategori']);
            $kategori      = !empty($ambilKategori) ? $ambilKategori[0]->nama : '-';
            $this->notifWaGroup(1, [
                'idPenerima1' => 'IZIN K3 ISO CDAKB VYM',
                'idPenerima2' => '',
                'namaSurat'   => 'Dokumen Rahasia',
                'status'      => 'mengupload',
                'kategori'    => $kategori,
                'url'         => 'https://office.visiyosindo.id/dokumen/index/rahasia',
                'idTracking'  => '',
                'penerima'    => 'Bapak dan Ibu',
                'csname'      => $data['nama_dokumen']
            ]);

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
            $id_kategori = (int)$this->input->post('kategori');
            $ambilKategori = $this->md_dokumen->getById('katproduct', $id_kategori);
            $kategori = !empty($ambilKategori) ? $ambilKategori[0]->keterangan : '';
            $is_hs_code = (strtoupper(trim((string)$kategori)) === 'HS CODE');

            if ($is_hs_code) {
                $kode_hs = trim((string)$this->input->post('kode_hs_code', TRUE));
                $ket_hs  = trim((string)$this->input->post('keterangan_hs', TRUE));
                $lartas  = trim((string)$this->input->post('dokumen_lartas', TRUE));
                $tarif   = trim((string)$this->input->post('tarif_impor', TRUE));
                $link_hs = trim((string)$this->input->post('file', TRUE));
                if ($kode_hs === '' || $link_hs === '') {
                    ajaxReturnDie('error', 'Kode HS Code dan Link HS Code wajib diisi.', FALSE);
                }
                $data['nama_dokumen']   = $kode_hs;
                $data['kode_hs_code']   = $kode_hs;
                $data['keterangan']     = $ket_hs;
                $data['dokumen_lartas'] = $lartas;
                $data['tarif_impor']    = $tarif;
                $data['file']           = $link_hs;
                $data['tgl_mulai']      = null;
                $data['tgl_akhir']      = null;
                $data['id_kategori']    = $id_kategori;
            } else {
                $data['nama_dokumen']   = $this->input->post('nama');
                $data['file']           = $this->input->post('file');
                $data['tgl_mulai']      = date_db_format($this->input->post('tgl_mulai', TRUE));
                $data['tgl_akhir']      = date_db_format($this->input->post('tgl_akhir', TRUE));
                $data['id_kategori']    = $id_kategori;
                checkEmptyForm($data);
            }
            $data['created_by']     = sessPenggunaId();
            $this->md_all->reset_increment('dokumen_product');
            $this->md_dokumen->add($kode, $data);

            $url = 'https://office.visiyosindo.id/dokumen/index/product/';


            //$lastGcId = $this->md_dokumen->getVisilabLastId();
            //$lastGcId = $lastGcId->id;

            $idTracking = encrypt($data['id_kategori']);

            $dataWa = [
                //'idPenerima1' 	=> 'Test Api Wa Group',
                //'idPenerima2' 	=> '',
                'idPenerima1'     => 'IZIN K3 ISO CDAKB VYM',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Produk',
                'status'             => 'mengupload',
                'kategori'         => $kategori,
                'url'             => $url,
                'idTracking'         => $idTracking,
                'penerima'         => 'Bapak dan Ibu',
                'csname'             => $data['nama_dokumen']
            ];
            if (!$is_hs_code) {
                $this->notifWaGroup(1, $dataWa);
            }

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

            $ambilKategori = $this->md_dokumen->getById('kategori', $data['id_kategori']);
            $kategori      = !empty($ambilKategori) ? $ambilKategori[0]->nama : '-';
            $this->notifWaGroup(1, [
                'idPenerima1' => 'IZIN K3 ISO CDAKB VYM',
                'idPenerima2' => '',
                'namaSurat'   => 'Dokumen Rahasia',
                'status'      => 'mengedit',
                'kategori'    => $kategori,
                'url'         => 'https://office.visiyosindo.id/dokumen/index/rahasia',
                'idTracking'  => '',
                'penerima'    => 'Bapak dan Ibu',
                'csname'      => $data['nama_dokumen']
            ]);

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
            $id_kategori = (int)$this->input->post('kategori');
            $ambilKategori = $this->md_dokumen->getById('katproduct', $id_kategori);
            $kategori = !empty($ambilKategori) ? $ambilKategori[0]->keterangan : '';
            $is_hs_code = (strtoupper(trim((string)$kategori)) === 'HS CODE');

            if ($is_hs_code) {
                $kode_hs = trim((string)$this->input->post('kode_hs_code', TRUE));
                $link_hs = trim((string)$this->input->post('file', TRUE));
                if ($kode_hs === '' || $link_hs === '') {
                    ajaxReturnDie('error', 'Kode HS Code dan Link HS Code wajib diisi.', FALSE);
                }
                $data['nama_dokumen']   = $kode_hs;
                $data['kode_hs_code']   = $kode_hs;
                $data['keterangan']     = trim((string)$this->input->post('keterangan_hs', TRUE));
                $data['dokumen_lartas'] = trim((string)$this->input->post('dokumen_lartas', TRUE));
                $data['tarif_impor']    = trim((string)$this->input->post('tarif_impor', TRUE));
                $data['id_kategori']    = $id_kategori;
                $data['file']           = $link_hs;
                $data['tgl_mulai']      = null;
                $data['tgl_akhir']      = null;
                $data['last_updated_by'] = sessPenggunaId();
                $data['last_updated_at'] = date('y-m-d H:i:s');
            } else {
                $data['nama_dokumen']   = $this->input->post('nama');
                $data['id_kategori']    = $id_kategori;
                $data['file']           = $this->input->post('file');
                $data['tgl_mulai']      = date_db_format($this->input->post('tgl_mulai', TRUE));
                $data['tgl_akhir']      = date_db_format($this->input->post('tgl_akhir', TRUE));
                $data['last_updated_by'] = sessPenggunaId();
                $data['last_updated_at'] = date('y-m-d H:i:s');
                checkEmptyForm($data);
            }
            $this->md_dokumen->update($kode, ['id' => $id], $data);

            $url = 'https://office.visiyosindo.id/dokumen/index/product/';
            $idTracking = encrypt($data['id_kategori']);

            $dataWa = [


                //'idPenerima1' 	=> 'Test Api Wa Group',
                //'idPenerima2' 	=> '',
                'idPenerima1'     => 'IZIN K3 ISO CDAKB VYM',
                'idPenerima2'     => '',
                'namaSurat'          => 'Dokumen Produk',
                'status'             => 'mengedit',
                'kategori'         => $kategori,
                'url'             => $url,
                'idTracking'         => $idTracking,
                'penerima'         => 'Bapak dan Ibu',
                'csname'             => $data['nama_dokumen']
            ];
            if (!$is_hs_code) {
                $this->notifWaGroup(1, $dataWa);
            }

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
        $id_kat         = decrypt($id);
        $dt             = $this->md_dokumen->getAllProduct($id_kat);
        $kat_info       = $this->md_dokumen->getById('katproduct', $id_kat);
        $is_hs_code     = (!empty($kat_info) && strtoupper(trim((string)$kat_info[0]->keterangan)) === 'HS CODE');
        $start          = $this->input->post('start');
        $data           = array();
        foreach ($dt['data'] as $row) {
            $id_enc     = encrypt($row->id_dokumen);
            $li_btn     = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id_enc . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id_enc . '" data-object="dokumen/delete/product/' . $id_enc . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
            $can_action = (isAdmin() || isGa() || sessPenggunaId() == '23' || sessPenggunaId() == '74' || sessPenggunaId() == '83' || sessPenggunaId() == '84' || sessPenggunaId() == '86' || sessPenggunaId() == '750' || sessPenggunaId() == '92' || sessPenggunaId() == '766');

            if ($is_hs_code) {
                $kode_hs = !empty($row->kode_hs_code) ? $row->kode_hs_code : $row->nama_dokumen;
                $ket_hs  = !empty($row->keterangan) ? $row->keterangan : '-';
                $lartas  = !empty($row->dokumen_lartas) ? $row->dokumen_lartas : '-';
                $tarif   = !empty($row->tarif_impor) ? $row->tarif_impor : '-';
                $link_hs = !empty($row->file)
                    ? '<a href="' . htmlspecialchars($row->file, ENT_QUOTES, 'UTF-8') . '" target="_blank"><i class="fas fa-external-link-alt"></i> Buka Link</a>'
                    : '-';
                $th = [
                    ++$start . '.',
                    htmlspecialchars((string)$kode_hs, ENT_QUOTES, 'UTF-8'),
                    nl2br(htmlspecialchars((string)$ket_hs, ENT_QUOTES, 'UTF-8')),
                    nl2br(htmlspecialchars((string)$lartas, ENT_QUOTES, 'UTF-8')),
                    htmlspecialchars((string)$tarif, ENT_QUOTES, 'UTF-8'),
                    $link_hs,
                ];
                $li_btn_hs = '
                    <center>
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-sm btn-success btn-notif-hs" title="Kirim Notif WA" data-id="' . $id_enc . '" data-kode="' . htmlspecialchars((string)$kode_hs, ENT_QUOTES, 'UTF-8') . '"><i class="fab fa-whatsapp"></i></button>
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id_enc . '"><i class="bx bx-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id_enc . '" data-object="dokumen/delete/product/' . $id_enc . '"><i class="bx bx-trash"></i></button>
                    </div>
                    </center>';
                if ($can_action) {
                    $th[] = $li_btn_hs;
                }
                $data[] = $th;
                continue;
            }

            $link_download = '<a href="' . $row->file . '"><i class="fas fa-download"></i> Download</a>';
            $masa_berlaku = '';
            $kategori_produk = strtoupper(trim((string) $row->kategori));
            $tampilkan_masa_berlaku = in_array($kategori_produk, ['LOA', 'AKL', 'AKD', 'ISO'], true);

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
            if ($can_action) {
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
            $filterKategori = $this->input->post('kategori', true);
            $dt             = $this->md_dokumen->getAll($kode, $filterKategori);
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

    public function send_notif_hscode($id = null)
    {
        grantAccessFor('all');
        $allowed = (isAdmin() || isGa() || in_array(sessPenggunaId(), ['23', '74', '75', '83', '84', '86', '92', '750', '766']));
        if (!$allowed) {
            ajaxReturnDie('error', 'Akses ditolak.', FALSE);
        }

        $id_kategori = 1;
        $csname = 'Update Data HS Code';
        if (!empty($id)) {
            $row = $this->md_dokumen->getById('product', decrypt($id));
            if (empty($row)) {
                ajaxReturnDie('error', 'Data HS Code tidak ditemukan.', FALSE);
            }
            $id_kategori = (int)$row[0]->id_kategori;
            $kode_hs = !empty($row[0]->kode_hs_code) ? $row[0]->kode_hs_code : $row[0]->nama_dokumen;
            $csname = $kode_hs . (!empty($row[0]->keterangan) ? ' - ' . $row[0]->keterangan : '');
        }

        $dataWa = [
            'idPenerima1' => 'IZIN K3 ISO CDAKB VYM',
            'idPenerima2' => '',
            'namaSurat'   => 'Dokumen Produk',
            'status'      => 'memperbarui',
            'kategori'    => 'HS CODE',
            'url'         => 'https://office.visiyosindo.id/dokumen/index/product/',
            'idTracking'  => encrypt($id_kategori),
            'penerima'    => 'Bapak dan Ibu',
            'csname'      => $csname
        ];
        $this->notifWaGroup(1, $dataWa);

        addlog('Notif WA HS Code', 'Mengirim notifikasi WA manual HS Code: ' . $csname);
        ajaxReturnDie('success', 'Notifikasi WA HS Code berhasil dikirim ke grup IZIN K3 ISO CDAKB VYM.', FALSE);
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

    public function export_excel_hs($param = null)
    {
        grantAccessFor('all');

        $id_kat = $param ? (int)decrypt($param) : 0;
        if ($id_kat <= 0) {
            $kat = $this->db->get_where('masterdokumenproduct', ['keterangan' => 'HS CODE'])->row();
            $id_kat = $kat ? (int)$kat->id : 0;
        }

        $items = $this->md_dokumen->getProductDocsByKategori($id_kat);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data HS Code');

        // Header Title
        $sheet->setCellValue('A1', 'DAFTAR DATA HS CODE & TARIF IMPOR - PT VISI YOSINDO MEDIKAL');
        $sheet->setCellValue('A2', 'Kategori: HS CODE | Total: ' . count($items) . ' Data | Tanggal Unduh: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);

        // Header Table
        $headers = [
            'A4' => 'No',
            'B4' => 'Kode HS Code',
            'C4' => 'Keterangan / Produk',
            'D4' => 'Dokumen Lartas',
            'E4' => 'Tarif Impor',
            'F4' => 'Link HS Code (INSW)'
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        $rowNum = 5;
        $no = 1;
        foreach ($items as $it) {
            $kode_hs = !empty($it->kode_hs_code) ? $it->kode_hs_code : (!empty($it->nama_dokumen) ? $it->nama_dokumen : '-');
            $keterangan = !empty($it->keterangan) ? $it->keterangan : '-';
            $lartas = !empty($it->dokumen_lartas) ? $it->dokumen_lartas : '-';
            $tarif = !empty($it->tarif_impor) ? $it->tarif_impor : '-';
            $link = !empty($it->file) ? $it->file : '';

            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValueExplicit('B' . $rowNum, (string)$kode_hs, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowNum, $keterangan);
            $sheet->setCellValue('D' . $rowNum, $lartas);
            $sheet->setCellValue('E' . $rowNum, $tarif);
            $sheet->setCellValue('F' . $rowNum, $link);

            if (!empty($link) && filter_var($link, FILTER_VALIDATE_URL)) {
                $sheet->getCell('F' . $rowNum)->getHyperlink()->setUrl($link);
                $sheet->getStyle('F' . $rowNum)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1D4ED8'))->setUnderline(true);
            }

            // Alignment and borders
            $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP)->setWrapText(true);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFE2E8F0'));

            $rowNum++;
        }

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(35);

        $filename = "Data_HS_Code_PT_VYM_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_pdf_hs($param = null)
    {
        grantAccessFor('all');

        $id_kat = $param ? (int)decrypt($param) : 0;
        if ($id_kat <= 0) {
            $kat = $this->db->get_where('masterdokumenproduct', ['keterangan' => 'HS CODE'])->row();
            $id_kat = $kat ? (int)$kat->id : 0;
        }

        $items = $this->md_dokumen->getProductDocsByKategori($id_kat);

        $page_data['title_pdf'] = 'DAFTAR HS CODE & TARIF IMPOR';
        $page_data['data'] = $items;
        $page_data['tgl_cetak'] = date('d F Y, H:i') . ' WIB';

        $this->load->view('pages/v_print/print_hscode', $page_data);
    }
}
