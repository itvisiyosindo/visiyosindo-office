<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Pengiriman_stok extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_detail_barang_pengiriman_stok');
        $this->load->model('md_detail_barang');
        $this->load->model('md_barang');
        $this->load->model('md_gudang');
        $this->load->model('md_pengiriman_stok');
        $this->load->model('md_penerimaan_stok');
        $this->load->model('md_ekspedisi');
        $this->load->model('md_riwayat_status_pengiriman_stok');
        $this->load->model('md_detail_barang_penerimaan_stok');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');
        $page_data['switch']      	= $this->id_navbar();
		$page_data['gudang']  		= $this->md_gudang->getByWhere(['g.status' => 1]);
        $page_data['page_name']  	= 'v_pengiriman_stok';
        $page_data['page_title'] 	= 'Pengiriman Stok';
        $page_data['page_desc']  	= 'Form Pengiriman Stok ';
        $this->load->view('index', $page_data);
    }

    public function show()
    {
        grantAccessFor('all');
        $page_data['gudang']  = $this->md_gudang->getByWhere(['g.status' => 1]);
        $page_data['ekspedisi']  = $this->md_ekspedisi->getByWhere(['e.status' => 1]);
        $page_data['temp_data'] = $this->md_pengiriman_stok->getTempData();
        if (isset($page_data['temp_data'])) {
            foreach ($page_data['temp_data'] as $row) {
                $row->id_pengiriman_stok_temp = encrypt($row->id_pengiriman_stok_temp);
                $row->pengguna_id = encrypt($row->pengguna_id);
                $row->id_gudang_asal = encrypt($row->id_gudang_asal);
                $row->id_gudang_tujuan = encrypt($row->id_gudang_tujuan);
                $row->id_ekspedisi = encrypt($row->id_ekspedisi);
                $row->tgl_pengiriman = date('d-m-Y', strtotime($row->tgl_pengiriman));
            }
        }
        $page_data['detail_barang_temp'] = $this->md_pengiriman_stok->getDetailBarangTempBySess();
        if (isset($page_data['detail_barang_temp'])) {
            foreach ($page_data['detail_barang_temp'] as $row) {
                $row->pengguna_id = encrypt($row->pengguna_id);
                $row->id_detail_barang_pengiriman_stok_temp = encrypt($row->id_detail_barang_pengiriman_stok_temp);
                $row->id_barang = encrypt($row->id_barang);
                $row->exp_date = $this->md_detail_barang->getById($row->id_detail_barang)[0]->exp_date;
                $row->id_detail_barang = encrypt($row->id_detail_barang);
            }
        }

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']  	= 'v_pengiriman_stok_form';
        $page_data['page_title'] 	= 'Pengiriman Stok';
        $page_data['page_desc']  	= 'Form Pengiriman Stok ';
        $this->load->view('index', $page_data);
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
		$page_data['switch']			= $this->id_navbar();
        $page_data['gudang']  			= $this->md_gudang->getByWhere(['g.status' => 1]);
        $page_data['ekspedisi']  		= $this->md_ekspedisi->getByWhere(['e.status' => 1]);
        $page_data['pengiriman_stok'] 	= $this->md_pengiriman_stok->getById($id);
        foreach ($page_data['pengiriman_stok'] as $row) {
            $row->id_pengiriman_stok 	= encrypt($row->id_pengiriman_stok);
            $row->id_gudang_asal 		= encrypt($row->id_gudang_asal);
            $row->id_gudang_tujuan 		= encrypt($row->id_gudang_tujuan);
            $row->id_ekspedisi 			= encrypt($row->id_ekspedisi);
            $row->tgl_pengiriman 		= date('d-m-Y', strtotime($row->tgl_pengiriman));
        }
        $page_data['detail_barang_pengiriman_stok'] = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($id);
        foreach ($page_data['detail_barang_pengiriman_stok'] as $row) {
            $row->id_detail_barang_pengiriman_stok = encrypt($row->id_detail_barang_pengiriman_stok);
            $row->id_barang = encrypt($row->id_detail_barang_pengiriman_stok);
            $row->exp_date = $this->md_detail_barang->getById($row->id_detail_barang)[0]->exp_date;
            $row->id_detail_barang = encrypt($row->id_detail_barang);
            $row->id_pengiriman_stok = encrypt($row->id_pengiriman_stok);
        }

        $page_data['new_detail_barang_pengiriman_stok_temp'] = $this->md_pengiriman_stok->getByIdPengirimanStok($id);
        if ($page_data['new_detail_barang_pengiriman_stok_temp']) {
            foreach ($page_data['new_detail_barang_pengiriman_stok_temp'] as $row) {
                $row->id_detail_barang_pengiriman_stok_temp = encrypt($row->id_detail_barang_pengiriman_stok_temp);
                $row->exp_date = $this->md_detail_barang->getById($row->id_detail_barang)[0]->exp_date;
                $row->id_detail_barang = encrypt($row->id_detail_barang);
                $row->id_barang = encrypt($row->id_barang);
                $row->pengguna_id = encrypt($row->pengguna_id);
                $row->id_pengiriman_stok = encrypt($row->id_pengiriman_stok);
            }
        }

        //ada beberapa form yg harus di kunci jika sudah ada penerimaan barang pada pengiriman stok
        $cek_sudah_terima = $this->md_penerimaan_stok->getByWhere(['ps.id_pengiriman_stok' => $id]);
        if ($cek_sudah_terima) {
            $page_data['cek_sudah_terima']  = TRUE;
        } else {
            $page_data['cek_sudah_terima']  = FALSE;
        }
        // echo '<pre>'; print_r( $page_data['detail_barang_pengiriman_stok'] );die; echo '</pre>';
        $page_data['page_name']  = 'v_pengiriman_stok_form';
        $page_data['page_title'] = 'Pengiriman Stok';
        $page_data['page_desc']  = 'Form Pengiriman Stok Gudang';
        $this->load->view('index', $page_data);
    }

    public function get($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail_barang_pengiriman_stok_temp') {
            $id = decrypt($this->input->post('id_detail_barang_pengiriman_stok_temp'));
            $data = $this->md_pengiriman_stok->getDetailBarangPengirimanStokTempById($id);
            foreach ($data as $row) {
                $row->id_detail_barang_pengiriman_stok_temp = encrypt($row->id_detail_barang_pengiriman_stok_temp);
                $row->pengguna_id = encrypt($row->pengguna_id);
                $row->id_barang = encrypt($row->id_barang);
                $row->id_detail_barang = encrypt($row->id_detail_barang);
                $row->id_pengiriman_stok = encrypt($row->id_pengiriman_stok);
            }
            echo json_encode($data);
            die;
        } else if ($param == 'resume_detail_barang') {
            $id = decrypt($this->input->post('id_pengiriman_stok'));
            $data['pengiriman_stok'] = $this->md_pengiriman_stok->getById($id);
            foreach ($data['pengiriman_stok'] as $row) {
                $row->id_pengiriman_stok = encrypt($row->id_pengiriman_stok);
                $row->id_gudang_asal = encrypt($row->id_gudang_asal);
                $row->id_gudang_tujuan = encrypt($row->id_gudang_tujuan);
                $row->id_ekspedisi = encrypt($row->id_ekspedisi);
                $row->id_latest_riwayatstatus_pengiriman = encrypt($row->id_latest_riwayatstatus_pengiriman);
                $row->tgl_pengiriman = date_view_format($row->tgl_pengiriman);
            }
            $data['detail_barang'] = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($id);
            foreach ($data['detail_barang'] as $row) {
                $row->id_detail_barang_pengiriman_stok = encrypt($row->id_detail_barang_pengiriman_stok);
                $row->exp_date = date_view_format($this->md_detail_barang->getById($row->id_detail_barang)[0]->exp_date);
                $row->id_barang = encrypt($row->id_barang);
                $row->id_detail_barang = encrypt($row->id_detail_barang);
                $row->id_pengiriman_stok = encrypt($row->id_pengiriman_stok);
            }
            echo json_encode($data);
            die;
        } else if ($param == "download_file") {
            $id = decrypt($param2);
            $data = $this->md_pengiriman_stok->getById($id);
            if ($data[0]->file_pendukung) {
                $this->load->helper('download');
                force_download('uploads/pengiriman_stok/' . $data[0]->file_pendukung, NULL);
            } else {
                show_404();
            }
        } else if ($param == "lihat_file") {
            $id = decrypt($this->input->post('id'));
            $data = $this->md_pengiriman_stok->getById($id)[0]->file_pendukung;
            echo json_encode($data);
            die;
        } else if ($param == "print_dokumen") {
            $data['pengiriman_stok'] = $this->md_pengiriman_stok->getByWhere(['ps.id_pengiriman_stok' => decrypt($param2)]);
            $data['detail_barang_pengiriman_stok'] = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok(decrypt($param2));
            // echo '<pre>'; print_r( $data );die; echo '</pre>';
            $this->load->library('pdfgenerator');
            $file_pdf       = 'Pengiriman_Stok_' . $data['pengiriman_stok'][0]->no_pemindahan . '_' . date_view_format($data['pengiriman_stok'][0]->tgl_pengiriman);
            $paper          = 'A4';
            $orientation    = "portrait";
            $html           = $this->load->view('pages/v_print/print_pengiriman_stok', $data, true);
            $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
        } else {
            $id = decrypt($this->input->post('id_detail_barang_pengiriman_stok'));
            $data = $this->md_detail_barang_pengiriman_stok->getDetailBarangPengirimanStokById($id);
            foreach ($data as $row) {
                $row->id_detail_barang_pengiriman_stok = encrypt($row->id_detail_barang_pengiriman_stok);
                $row->id_barang = encrypt($row->id_barang);
                $row->id_detail_barang = encrypt($row->id_detail_barang);
                $row->id_pengiriman_stok = encrypt($row->id_pengiriman_stok);
            }
            echo json_encode($data);
            die;
        }
    }

    public function add($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'temp') {
            $temp = $this->md_pengiriman_stok->getTempData();
            if ($temp && $param2 == NULL) {
                //update jika sudah ada temp data sebelumnya
                $data['tgl_pengiriman'] = $this->input->post('tgl_pengiriman') == 00 - 00 - 0000 ? '0000-00-00' : date_db_format($this->input->post('tgl_pengiriman'));
                $data['no_pemindahan'] = $this->input->post('no_pemindahan');
                $data['id_gudang_asal'] = decrypt($this->input->post('id_gudang_asal'));
                $data['id_gudang_tujuan'] = decrypt($this->input->post('id_gudang_tujuan'));
                $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
                $data['no_resi'] = $this->input->post('no_resi');
                $data['keterangan'] = $this->input->post('keterangan');
                $this->md_pengiriman_stok->updateTempData(['pengguna_id' => sessPenggunaId()], $data);
                ajaxReturnDie('success', 'Temp Data Input', TRUE);
            } else if ($param2 == 'detail_barang_pengiriman_stok') {
                //add detail barang pengiriman stok temp
                $dt['id_barang'] = decrypt($this->input->post('id_barang'));
                $dt['id_detail_barang'] = decrypt($this->input->post('id_detail_barang'));
                $dt['no_batch'] = $this->input->post('no_batch');
                $dt['qty'] = $this->input->post('qty');
                checkEmptyForm($dt);
                $dt['id_pengiriman_stok'] = $this->input->post('id_pengiriman_stok') ? decrypt($this->input->post('id_pengiriman_stok')) : NULL;
                if ($this->input->post('id_detail_barang_pengiriman_stok_temp')) {
                    //update detail barang temp
                    $dt['pengguna_id'] = sessPenggunaId();
                    $id = decrypt($this->input->post('id_detail_barang_pengiriman_stok_temp'));
                    $where = ['id_detail_barang_pengiriman_stok_temp' => $id];
                    $this->md_pengiriman_stok->updateDetailBarangPengirimanStokTemp($where, $dt);
                    ajaxReturnDie('success', 'Data Berhasil Diubah', TRUE);
                } else {
                    $dt['pengguna_id'] = sessPenggunaId();
                    //add detail barang temp
                    $this->md_pengiriman_stok->addDetailBarangPengirimanStokTemp($dt);
                    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
                }
            } else {
                //add pengiriman stok temp
                $data['pengguna_id'] = sessPenggunaId();
                $data['tgl_pengiriman'] = $this->input->post('tgl_pengiriman') == 00 - 00 - 0000 ? '0000-00-00' : date_db_format($this->input->post('tgl_pengiriman'));
                $data['no_pemindahan'] = $this->input->post('no_pemindahan');
                $data['id_gudang_asal'] = decrypt($this->input->post('id_gudang_asal'));
                $data['id_gudang_tujuan'] = decrypt($this->input->post('id_gudang_tujuan'));
                $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
                $data['no_resi'] = $this->input->post('no_resi');
                $data['keterangan'] = $this->input->post('keterangan');
                $this->md_pengiriman_stok->addTempData($data);
            }
            ajaxReturnDie('success', 'Temp Data Input', TRUE);
        }
        //pengecekan no_batch (no_batch tidak boleh sama dalam satu pengeluaran barang)
        $cek_batch = array_count_values($this->input->post('no_batch'));
        foreach ($cek_batch as $key => $row) {
            if ($key != '-') {
                if ($row > 1) {
                    ajaxReturnDie('error', 'No Batch tidak boleh sama');
                }
            }
        }
        //add data pengeluaran barang
        $this->db->trans_begin();
        $data['tgl_pengiriman'] = date_db_format($this->input->post('tgl_pengiriman'));
        $data['no_pemindahan'] = $this->input->post('no_pemindahan');
        $data['id_gudang_asal'] = decrypt($this->input->post('id_gudang_asal'));
        $data['id_gudang_tujuan'] = decrypt($this->input->post('id_gudang_tujuan'));
        $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
        $data['no_resi'] = $this->input->post('no_resi');
        $data['keterangan'] = $this->input->post('keterangan');
        //cek apakah no_pemindahan unique
        $cek = $this->md_pengiriman_stok->getByWHere(['ps.no_pemindahan' => $data['no_pemindahan']]);
        if ($cek) {
            ajaxReturnDie('error', 'No Pengiriman Sudah Ada');
        }
        $this->md_pengiriman_stok->add($data);

        //add riwayat status pengiriman stok
        $dta['id_pengiriman_stok'] = $this->db->insert_id();
        $dta['status_pengiriman'] = "Belum Diterima";
        $this->md_riwayat_status_pengiriman_stok->add($dta);

        //update riwayat status pengiriman stok
        $dta2['id_latest_riwayatstatus_pengiriman'] = $this->db->insert_id();
        $this->md_pengiriman_stok->update(['id_pengiriman_stok' => $dta['id_pengiriman_stok']], $dta2);

        //add data detail barang
        $dt['id_pengiriman_stok'] = $dta['id_pengiriman_stok'];
        $id_barang = $this->input->post('id_barang');
        foreach ($id_barang as $key => $row) {
            $dt['id_barang'] = decrypt($this->input->post('id_barang')[$key]);
            $dt['qty'] = $this->input->post('qty')[$key];
            $dt['current_qty'] = $this->input->post('qty')[$key];
            $dt['no_batch'] = $this->input->post('no_batch')[$key];
            $dt['id_detail_barang'] = decrypt($this->input->post('id_detail_barang')[$key]);
            $this->md_detail_barang_pengiriman_stok->add($dt);

            //kurangi jumlah stock di detail_barang
            $current_stock = $this->md_detail_barang->getStockDetailBarang($dt['id_detail_barang'])[0]->current_stock;
            if ($dt['qty'] > $current_stock) {
                ajaxReturnDie('error', 'Stock untuk No Batch ' . $dt['no_batch'] . ' tidak cukup!');
            }
            $stock['current_stock'] = $current_stock - $dt['qty'];
            $this->md_detail_barang->updateDetailBarang($dt['id_detail_barang'], $stock);
        }
        //destroy temp data
        $this->md_pengiriman_stok->destroyTempData();
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            //add log
            $gudang_asal = $this->md_gudang->getById($data['id_gudang_asal']);
            $gudang_tujuan = $this->md_gudang->getById($data['id_gudang_tujuan']);
            $aksi = 'Tambah Pengiriman Stok';
            $ket = 'Pengiriman stok dari gudang : ' . $gudang_asal[0]->nama_gudang . ', ke gudang : ' . $gudang_tujuan[0]->nama_gudang . ' - No Pemindahan : ' . $data['no_pemindahan'];
            addlog($aksi, $ket);
        } else {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
        }
        ajaxReturnDie('success', 'Data Berhasil di masukan', TRUE);
    }

    public function update($param = "")
    {
        grantAccessFor('all');
        if ($param == 'detail_barang_pengiriman_stok') {
            $id_detail_barang_pengiriman_stok = decrypt($this->input->post('id_detail_barang_pengiriman_stok'));

            //cek apakah detail pengiriman ini sudah di terima sebelumnya(jka sudah ada penerimaan, detail pengiriman tidak bisa di hapus)
            $cek = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_detail_barang_pengiriman_stok' => $id_detail_barang_pengiriman_stok]);
            if ($cek) {
                ajaxReturnDie('error', 'Sudah ada penerimaan stok pada pengiriman ini!');
            }

            $id_detail_barang = decrypt($this->input->post('id_detail_barang'));
            $data['qty'] = $this->input->post('qty');
            $data['current_qty'] = $this->input->post('qty');
            checkEmptyForm($data);

            $this->db->trans_begin();
            $current_stock = $this->md_detail_barang->getStockDetailBarang($id_detail_barang)[0]->current_stock;
            $current_qty = $this->md_detail_barang_pengiriman_stok->getByWhere($id_detail_barang_pengiriman_stok)[0]->qty;
            if ($data['qty'] - $current_qty > $current_stock)
                ajaxReturnDie('error', 'Stock tidak cukup!');

            if ($data['qty'] > $current_qty) {
                $dt2['current_stock'] = $current_stock - ($data['qty'] - $current_qty);
            } else {
                $dt2['current_stock'] = $current_stock + ($current_qty - $data['qty']);
            }
            //update current_stock
            $this->md_detail_barang->updateDetailBarang($id_detail_barang, $dt2);

            //update detail barang pengiriman stok asli
            $this->md_detail_barang_pengiriman_stok->updateDetailBarangPengirimanStok($id_detail_barang_pengiriman_stok, $data);

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                //add log
                $temp1 = $this->md_detail_barang_pengiriman_stok->getByWhere($id_detail_barang_pengiriman_stok);
                $temp2 = $this->md_barang->getById($temp1[0]->id_barang);
                $temp3 = $this->md_pengiriman_stok->getById($temp1[0]->id_pengiriman_stok);
                $aksi = 'Edit Detail Barang Pengiriman Stok';
                $ket = 'Mengedit barang ' . $temp2[0]->nama_barang . ' pada No Pemindahan: ' . $temp3[0]->no_pemindahan;
                addlog($aksi, $ket);
            } else {
                $this->db->trans_rollback();
                ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
            }
            ajaxReturnDie('success', 'Data Berhasil di Update!', TRUE);
        } else if ($param == 'file_pendukung') {
            $id = decrypt($this->input->post('id_pengiriman_stok'));

            //cek file sebelumnya, jika ada hapus file itu
            $tmp = $this->md_pengiriman_stok->getById($id);
            if ($tmp[0]->file_pendukung) {
                file_exists('uploads/pengiriman_stok/' . $tmp[0]->file_pendukung) ? unlink('uploads/pengiriman_stok/' . $tmp[0]->file_pendukung) : '';
            }
            $file = $_FILES['file_pendukung'];
            if ($file['name']) {
                $config['file_name']        = 'PengirimanStok_' . $tmp[0]->no_pemindahan . '_' . date_view_format($tmp[0]->tgl_pengiriman) . '_' . time();
                $config['upload_path']      = 'uploads/pengiriman_stok';
                $config['allowed_types']    = 'pdf|xls|xlsx|doc|docx|jpg|png|jpeg';
                $config['max_size']         = 4000;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('file_pendukung')) {
                    ajaxReturnDie('error', $this->upload->display_errors());
                } else {
                    $dt = $this->upload->data();
                    $data['file_pendukung'] = $dt['file_name'];
                }
            } else {
                ajaxReturnDie('error', 'Silahkan upload File Pendukung');
            }
            $this->md_pengiriman_stok->update(['id_pengiriman_stok' => $id], $data);
            ajaxReturnDie('success', 'File Berhasil di Upload', TRUE);
        }
        // if ($this->input->post('no_batch')) {
        //     //pengecekan no_batch (no_batch tidak boleh sama dalam satu pengeluaran barang)
        //     $cek_batch = array_count_values($this->input->post('no_batch'));
        //     foreach ($cek_batch as $row) {
        //         if ($row > 1) {
        //             ajaxReturnDie('error', 'No Batch tidak boleh sama');
        //         }
        //     }
        // }
        //add data pengiriman stok
        $id_pengiriman_stok = decrypt($this->input->post('id_pengiriman_stok'));
        $data['tgl_pengiriman'] = date('Y-m-d', strtotime($this->input->post('tgl_pengiriman')));
        // $data['id_gudang_asal'] = decrypt($this->input->post('id_gudang_asal'));
        // $data['id_gudang_tujuan'] = decrypt($this->input->post('id_gudang_tujuan'));
        // $data['no_pemindahan'] = $this->input->post('no_pemindahan');
        $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
        $data['keterangan'] = $this->input->post('keterangan');
        $data['no_resi'] = $this->input->post('no_resi');

        //cek apakah no_pemindahan unique
        // $cek = $this->md_pengiriman_stok->getByWHere(['ps.no_pemindahan' => $data['no_pemindahan']]);
        // if ($cek && $cek[0]->id_pengiriman_stok != $id_pengiriman_stok) {
        //     ajaxReturnDie('error', 'No Pemindahan Sudah Ada');
        // }

        $this->db->trans_begin();
        $this->md_pengiriman_stok->update(['id_pengiriman_stok' => $id_pengiriman_stok], $data);
        if ($this->input->post('id_barang')) {
            //add detail barang pengiriman stok
            $dt['id_pengiriman_stok'] = $id_pengiriman_stok;
            $id_barang = $this->input->post('id_barang');
            foreach ($id_barang as $key => $row) {
                //kurangi jumlah stok di detail barang
                $id_detail_barang = decrypt($this->input->post('id_detail_barang')[$key]);
                $current_stock = $this->md_detail_barang->getStockDetailBarang($id_detail_barang)[0]->current_stock;
                if ($this->input->post('qty')[$key] > $current_stock) {
                    ajaxReturnDie('error', 'Stock untuk No Batch ' . $dt['no_batch'] . ' tidak cukup!');
                }
                $stock['current_stock'] = $current_stock - $this->input->post('qty')[$key];
                $this->md_detail_barang->updateDetailBarang($id_detail_barang, $stock);

                $dt['id_barang'] = decrypt($this->input->post('id_barang')[$key]);
                $dt['id_detail_barang'] = decrypt($this->input->post('id_detail_barang')[$key]);
                $dt['qty'] = $this->input->post('qty')[$key];
                $dt['current_qty'] = $this->input->post('qty')[$key];
                $dt['no_batch'] = $this->input->post('no_batch')[$key];
                $this->md_detail_barang_pengiriman_stok->add($dt);
            }
            //destroy new detail barang di tampilan edit
            $this->md_pengiriman_stok->destroyNewTempDetailBarangPengirimanStok($id_pengiriman_stok);
        }
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            //add log
            $no_batch = $this->md_pengiriman_stok->getByid($id_pengiriman_stok)[0]->no_pemindahan;
            $aksi = 'Edit Pengiriman Stok';
            $ket = 'Mengedit data Pengiriman Stok - No Pemindahan : ' . $no_batch;
            addlog($aksi, $ket);
        } else {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
        }
        ajaxReturnDie('success', 'Data Berhasil di Update!', TRUE);
    }

    public function delete($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail_barang_pengiriman_stok_temp') {
            $id = decrypt($this->input->post('id_detail_barang_pengiriman_stok_temp'));
            $this->md_pengiriman_stok->deleteDetailBarangTemp($id);
            ajaxReturnDie('success', 'Data Berhasil Dihapus', TRUE);
        } else if ($param == 'dest_temp') {
            $this->md_pengiriman_stok->destroyTempData();
            ajaxReturnDie('success', 'Data Berhasil Dihapus', TRUE);
        } else if ($param == "detail_barang_pengiriman_stok") {
            $detail_barang_pengiriman_stok = decrypt($param2);
            //cek apakah detail pengiriman ini sudah di terima sebelumnya(jka sudah ada penerimaan, detail pengiriman tidak bisa di hapus)
            $cek = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_detail_barang_pengiriman_stok' => $detail_barang_pengiriman_stok]);
            if ($cek) {
                ajaxReturnDie('error', 'Sudah ada penerimaan stok pada pengiriman ini!');
            }

            //saat delete detail barang pengiriman stok, qty nya di kembalikan lagi ke stock di detail barang
            $this->db->trans_begin();
            $temp = $this->md_detail_barang_pengiriman_stok->getByWhere($detail_barang_pengiriman_stok);
            $current_stock = $this->md_detail_barang->getStockDetailBarang($temp[0]->id_detail_barang)[0]->current_stock;
            $dt['current_stock'] = $current_stock + $temp[0]->qty;
            $this->md_detail_barang->updateDetailBarang($temp[0]->id_detail_barang, $dt);

            //add log
            $temp1 = $this->md_detail_barang_pengiriman_stok->getByWhere($detail_barang_pengiriman_stok);
            $temp2 = $this->md_barang->getById($temp1[0]->id_barang);
            $temp3 = $this->md_pengiriman_stok->getById($temp1[0]->id_pengiriman_stok);
            $aksi = 'Hapus Detail Barang Pengiriman Stok';
            $ket = 'Menghapus barang ' . $temp2[0]->nama_barang . ' pada No Pemindahan: ' . $temp3[0]->no_pemindahan;
            addlog($aksi, $ket);

            $data['status'] = 0;
            $this->md_detail_barang_pengiriman_stok->delete($detail_barang_pengiriman_stok, $data);

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
            } else {
                $this->db->trans_rollback();
                ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi  !');
            }
            ajaxReturnDie('success', 'Data Berhasil Dihapus', TRUE);
        }
        //delete pengiriman stok sekaligus detail barang pengiriman stok
        $id_pengiriman_stok = decrypt($param);
        $temp = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($id_pengiriman_stok);

        $this->db->trans_begin();
        foreach ($temp as $row) {
            //cek apakah detail pengiriman ini sudah di terima sebelumnya(jka sudah ada penerimaan, detail pengiriman tidak bisa di hapus)
            $cek = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_detail_barang_pengiriman_stok' => $row->id_detail_barang_pengiriman_stok]);
            if ($cek) {
                ajaxReturnDie('error', 'Sudah ada penerimaan stok pada pengiriman ini!');
            }
            $current_stock = $this->md_detail_barang->getStockDetailBarang($row->id_detail_barang)[0]->current_stock;
            $dt['current_stock'] = $current_stock + $row->qty;
            $this->md_detail_barang->updateDetailBarang($row->id_detail_barang, $dt);
        }
        $data['status'] = 0;
        $this->md_pengiriman_stok->update(['id_pengiriman_stok' => $id_pengiriman_stok], $data);
        $this->md_detail_barang_pengiriman_stok->deleteByIdPengirimanStok($id_pengiriman_stok, $data);
        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            //add log
            $temp = $this->md_pengiriman_stok->getById($id_pengiriman_stok);
            $aksi = 'Hapus Pengiriman Stok';
            $ket = 'Menghapus Pengiriman Stok - No Pemindahan: ' . $temp[0]->no_pemindahan;
            addlog($aksi, $ket);
        } else {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
        }
        ajaxReturnDie('success', 'Data berhasil dihapus', TRUE);
    }

    public function print($param)
    {
        $data['pengiriman_stok'] = $this->md_pengiriman_stok->getByWhere(['ps.id_pengiriman_stok' => decrypt($param)]);
        $data['detail_barang_pengiriman_stok'] = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok(decrypt($param));
        // echo '<pre>'; print_r( $data['detail_barang_pengiriman_stok'] );die; echo '</pre>';
        $this->load->view('pages/v_print/print_pengiriman_stok', $data);
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_pengiriman_stok->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_pengiriman_stok);
            if ($row->status_pengiriman == "Belum Diterima") {
                $status_pengiriman = '<span class="badge-warning badge-pill">Belum Diterima</span>';
            } else if ($row->status_pengiriman == "Diterima Sebagian") {
                $status_pengiriman = '<span class="badge-primary badge-pill">Diterima Sebagian</span>';
            } else {
                $status_pengiriman = '<span class="badge-success badge-pill">Diterima Seluruhnya</span>';
            }
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-success btn-file" no-pemindahan="' . $row->no_pemindahan . '" data-id="' . $id . '"><i class="far fa-file-pdf"></i></button>
                    <button type="button" class="btn btn-sm btn-warning btn-detail" data-id="' . $id . '"><i class="fas fa-info-circle"></i></button>
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="pengiriman_stok/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->no_pemindahan;
            $th[] = date('d-m-Y', strtotime($row->tgl_pengiriman));
            $th[] = $status_pengiriman;
            $th[] = $row->nama_gudang_asal;
            $th[] = $row->nama_gudang_tujuan;
            $th[] = $row->keterangan;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
