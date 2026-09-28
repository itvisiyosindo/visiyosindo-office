<?php

use FontLib\Table\Type\post;

use function Complex\rho;

defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_stok extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengiriman_stok');
        $this->load->model('md_penerimaan_stok');
        $this->load->model('md_detail_barang_penerimaan_stok');
        $this->load->model('md_detail_barang_pengiriman_stok');
        $this->load->model('md_penerimaan_barang');
        $this->load->model('md_detail_barang');
        $this->load->model('md_detail_barang_keluar');
        $this->load->model('md_riwayat_status_pengiriman_stok');
        $this->load->model('md_gudang');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']		= $this->id_navbar();
		$page_data['gudang']  		= $this->md_gudang->getByWhere(['g.status' => 1]);
        $page_data['page_name']  	= 'v_penerimaan_stok';
        $page_data['page_title'] 	= 'Penerimaan Pemindahan Stok';
        $page_data['page_desc']  	= 'Management Data Penerimaan Pemindahan Stok';
        $this->load->view('index', $page_data);
    }

    public function show()
    {
        grantAccessFor('all');

        $page_data['switch']			= $this->id_navbar();
		$page_data['pengiriman_stok'] 	= $this->md_pengiriman_stok->getByWhere(['ps.status' => 1, 'rsps.status_pengiriman !=' => 'Diterima Seluruhnya']);
        $page_data['page_name'] 		= 'v_penerimaan_stok_form';
        $page_data['page_title'] 		= 'Form Penerimaan Pemindahan Stok';
        $page_data['page_desc']  		= 'Isi form Penerimaan Barang dengan benar';
        $this->load->view('index', $page_data);
    }

    public function get($param = "", $param2 = "")
    {
        grantAccessFor('all');

        if ($param == "download_file") {
            $id = decrypt($param2);
            $data = $this->md_penerimaan_stok->getById($id);
            if ($data[0]->file_pendukung) {
                $this->load->helper('download');
                force_download('uploads/penerimaan_stok/' . $data[0]->file_pendukung, NULL);
            } else {
                show_404();
            }
        } else if ($param == "lihat_file") {
            $id = decrypt($this->input->post('id'));
            $data = $this->md_penerimaan_stok->getById($id)[0]->file_pendukung;
            echo json_encode($data);
            die;
        } else if ($param == "print_dokumen") {
            $data['penerimaan_stok'] = $this->md_penerimaan_stok->getByWhere(['ps.id_penerimaan_stok' => decrypt($param2)]);
            $data['detail_barang_penerimaan_stok'] = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_penerimaan_stok' => decrypt($param2)]);
            // echo '<pre>'; print_r( $data['detail_barang_penerimaan_stok'] );die; echo '</pre>';
            $this->load->library('pdfgenerator');
            $file_pdf       = 'Penerimaan-Stok_' . $data['penerimaan_stok'][0]->no_penerimaan_stok . '_' . date_view_format($data['penerimaan_stok'][0]->tgl_penerimaan_stok);
            $paper          = 'A4';
            $orientation    = "portrait";
            $html           = $this->load->view('pages/v_print/print_penerimaan_stok', $data, true);
            $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
        }
    }

    public function add()
    {
        grantAccessFor('all');

        //insert detail barang penerimaan stok
        $checkbox = $this->input->post('checkbox');
        if (!$checkbox) {
            ajaxReturnDie('error', 'Pilih barang yang di terima!');
        }

        // //insert penerimaan stok
        $dt['id_pengiriman_stok'] = decrypt($this->input->post('id_pengiriman_stok'));
        $dt['tgl_penerimaan_stok'] = date_db_format($this->input->post('tgl_penerimaan_stok'));
        $dt['keterangan'] = $this->input->post('keterangan_penerimaan');
        $dt['no_penerimaan_stok'] = $this->input->post('no_penerimaan_stok');
        $dt['pengguna_id'] = sessPenggunaId();
        checkEmptyForm($dt);
        $this->db->trans_begin();
        $this->md_penerimaan_stok->add($dt);
        $id_penerimaan_stok = $this->db->insert_id();


        //add data penerimaan barang ke gudang tujuan (pada saat penerimaan pemindah gudang, akan di tambah satu data penerimaan_barang agar bisa di anggap sebagai stok)
        $penerimaan_stok = $this->md_pengiriman_stok->getById($dt['id_pengiriman_stok']);
        $dt2['tgl_masuk'] = $dt['tgl_penerimaan_stok'];
        $dt2['id_gudang'] = $penerimaan_stok[0]->id_gudang_tujuan;
        $dt2['keterangan'] = $dt['keterangan'];
        $dt2['no_terima'] = $dt['no_penerimaan_stok'];
        $dt2['id_gudang_asal'] = $penerimaan_stok[0]->id_gudang_asal;

        $this->md_penerimaan_barang->add($dt2);
        $data3['id_penerimaan_barang'] = $this->db->insert_id();
        $this->md_penerimaan_stok->update($id_penerimaan_stok, $data3); //masukan id_penerimaan_barang ke penerimaan_stok barusan

        $id_detail_barang_pengiriman_stok = $this->input->post('id_detail_barang_pengiriman_stok');
        foreach ($checkbox as $row) {
            foreach ($id_detail_barang_pengiriman_stok as $key => $row2) {
                if (decrypt($row2) == decrypt($row)) {

                    //cek qty yg di masukan tidak boleh kosong
                    if ($this->input->post('qty')[$key] == NULL || $this->input->post('qty')[$key] == 0) {
                        ajaxReturnDie('error', 'Kolom kuantitas harus di isi');
                    }

                    //insert data ke detail_barang
                    $detail_barang = $this->md_detail_barang->getById(decrypt($this->input->post('id_detail_barang_lama')[$key]));
                    $data3['id_barang'] = $detail_barang[0]->id_barang;
                    $data3['qty'] = $this->input->post('qty')[$key];
                    $data3['current_stock'] = $this->input->post('qty')[$key];
                    $data3['no_batch'] = $detail_barang[0]->no_batch;
                    $data3['exp_date'] = $detail_barang[0]->exp_date;
                    $this->md_detail_barang->add($data3);
                    $id_detail_barang_baru = $this->db->insert_id();

                    $data['id_penerimaan_stok'] = $id_penerimaan_stok;
                    $data['id_detail_barang_lama'] = decrypt($this->input->post('id_detail_barang_lama')[$key]);
                    $data['id_detail_barang_baru'] = $id_detail_barang_baru;
                    $data['id_detail_barang_pengiriman_stok'] = decrypt($this->input->post('id_detail_barang_pengiriman_stok')[$key]);
                    $data['id_barang'] = decrypt($this->input->post('id_barang')[$key]);
                    $data['no_batch'] = $this->input->post('no_batch')[$key];
                    $data['qty'] = $this->input->post('qty')[$key];
                    $this->md_detail_barang_penerimaan_stok->add($data);

                    //update current qty di detail barang pengiriman stok
                    $tmp = $this->md_detail_barang_pengiriman_stok->getByWhere($data['id_detail_barang_pengiriman_stok'])[0]->current_qty;
                    if ($data['qty'] > $tmp) {
                        ajaxReturnDie('error', 'Barang dengan no batch ' . $data['no_batch'] . ' tidak cukup stok!');
                    }
                    $data2['current_qty'] = $tmp - $data['qty'];
                    $this->md_detail_barang_pengiriman_stok->updateDetailBarangPengirimanStok($data['id_detail_barang_pengiriman_stok'], $data2);
                }
            }
        }

        //cek qty detail_barang_pengiriman_stok untuk update status pengiriman
        $ganti_status = [];
        foreach ($id_detail_barang_pengiriman_stok as $row) {
            $data_current_qty = $this->md_detail_barang_pengiriman_stok->getByWhere(decrypt($row))[0]->current_qty;
            if ($data_current_qty == 0) {
                $ganti_status[] = 1;
            } else {
                $ganti_status[] = 0;
            }
        }
        if ((count(array_unique($ganti_status)) === 1) && in_array(1, $ganti_status)) {
            $status['status_pengiriman'] = 'Diterima Seluruhnya';
        } else {
            $status['status_pengiriman'] = 'Diterima Sebagian';
        }
        $status['id_pengiriman_stok'] = $dt['id_pengiriman_stok'];

        //add riwayat status pengiriman stok
        $this->md_riwayat_status_pengiriman_stok->add($status);
        $id_riwayat['id_latest_riwayatstatus_pengiriman'] = $this->db->insert_id();
        $this->md_pengiriman_stok->update(['id_pengiriman_stok' => $dt['id_pengiriman_stok']], $id_riwayat);


        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            //add log
            $gudang_asal = $this->md_gudang->getById($dt2['id_gudang_asal']);
            $gudang_tujuan = $this->md_gudang->getById($dt2['id_gudang']);
            $aksi = 'Tambah Penerimaan Stok';
            // $ket = 'Pengiriman stok dari gudang : ' . $gudang_asal[0]->nama_gudang . ', ke gudang : ' . $gudang_tujuan[0]->nama_gudang . ' - No Pemindahan : ' . $data['no_pemindahan'];
            $ket = 'Gudang : ' . $gudang_tujuan[0]->nama_gudang . ' menerima stok dari gudang : ' . $gudang_asal[0]->nama_gudang . ' - No Terima : ' . $dt2['no_terima'];
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

        if ($param == 'file_pendukung') {
            $id = decrypt($this->input->post('id_penerimaan_stok'));

            //cek file sebelumnya, jika ada hapus file itu
            $tmp = $this->md_penerimaan_stok->getById($id);
            if ($tmp[0]->file_pendukung) {
                file_exists('uploads/penerimaan_stok/' . $tmp[0]->file_pendukung) ? unlink('uploads/penerimaan_stok/' . $tmp[0]->file_pendukung) : '';
            }

            $file = $_FILES['file_pendukung'];
            if ($file['name']) {
                $config['file_name']        = 'PenerimaanStok_' . $tmp[0]->no_penerimaan_stok . '_' . date_view_format($tmp[0]->tgl_penerimaan_stok) . '_' . time();
                $config['upload_path']      = 'uploads/penerimaan_stok';
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
            $this->md_penerimaan_stok->update($id, $data);
            ajaxReturnDie('success', 'File Berhasil di Upload', TRUE);
        }
        // echo '<pre>'; print_r($this->input->post()  );die; echo '</pre>';
        //update penerimaan_stok
        $id_penerimaan_stok = decrypt($this->input->post('id_penerimaan_stok'));
        $data['tgl_penerimaan_stok'] = date_db_format($this->input->post('tgl_penerimaan_stok'));
        $data['keterangan'] = $this->input->post('keterangan_penerimaan');
        checkEmptyForm($data);
        $this->db->trans_begin();
        $this->md_penerimaan_stok->update($id_penerimaan_stok, $data);

        //cek form empty
        $data_qty = $this->input->post('qty');
        checkEmptyForm($data_qty);

        //update penerimaan barang
        $id_penerimaan_barang  = $this->md_penerimaan_stok->getById($id_penerimaan_stok)[0]->id_penerimaan_barang; //sini fuadi
        $data2['tgl_masuk'] = $this->input->post('tgl_penerimaan_stok') == "00-00-0000" ? '00-00-0000' : date('Y-m-d', strtotime($this->input->post('tgl_penerimaan_stok')));
        $data2['keterangan'] = $this->input->post('keterangan_penerimaan');
        $this->md_penerimaan_barang->update(['id_penerimaan_barang' => $id_penerimaan_barang], $data2);

        //update detail barang penerimaan stok
        $id_detail_barang_penerimaan_stok = $this->input->post('id_detail_barang_penerimaan_stok');
        foreach ($id_detail_barang_penerimaan_stok as $key => $row) {
            $dta['qty'] = $this->input->post('qty')[$key];

            //kurang atau tambah current_qty di detail_barang_pengiriman_stok
            $current_qty = $this->md_detail_barang_pengiriman_stok->getByWhere(decrypt($this->input->post('id_detail_barang_pengiriman_stok')[$key]))[0]->current_qty;
            $qty = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_detail_barang_penerimaan_stok' => decrypt($row)])[0]->qty;
            if ($dta['qty'] - $qty > $current_qty)
                ajaxReturnDie('error', 'Stock tidak cukup!');

            if ($dta['qty'] > $qty) {
                $dt2['current_qty'] = $current_qty - ($dta['qty'] - $qty);
            } else {
                $dt2['current_qty'] = $current_qty + ($qty - $dta['qty']);
            }

            $this->md_detail_barang_penerimaan_stok->update(decrypt($row), $dta);
            $this->md_detail_barang_pengiriman_stok->updateDetailBarangPengirimanStok(decrypt($this->input->post('id_detail_barang_pengiriman_stok')[$key]), $dt2);

            //update detail_barang di table detail_barang(karna seluruh stok letaknya di detail_barang)
            $id_detail_barang = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_detail_barang_penerimaan_stok' => decrypt($row)])[0]->id_detail_barang_baru;

            //akumulasi total current_stock
            $temp = $this->md_detail_barang->getStockDetailBarang($id_detail_barang)[0];
            $current_stock = $temp->current_stock;
            $current_qty = $temp->qty;

            if ($dta['qty'] < ($current_qty - $current_stock)) {
                ajaxReturnDie('error', 'Stok tidak cukup!');
            }
            
            if ($dta['qty'] > $current_qty) {
                $dt['current_stock'] = $current_stock + ($dta['qty'] - $current_qty);
                $dt['qty'] = $dta['qty'];
            } else {
                $dt['current_stock'] = $current_stock - ($current_qty - $dta['qty']);
                $dt['qty'] = $dta['qty'];
            }
            
            $this->md_detail_barang->updateDetailBarang($id_detail_barang, $dt);
        }

        //cek qty detail_barang_pengiriman_stok untuk update status pengiriman
        $id_detail_barang_pengiriman_stok = $this->input->post('id_detail_barang_pengiriman_stok');
        $id_pengiriman_stok = $this->md_penerimaan_stok->getById($id_penerimaan_stok)[0]->id_pengiriman_stok;
        $ganti_status = [];
        foreach ($id_detail_barang_pengiriman_stok as $row) {
            $data_current_qty = $this->md_detail_barang_pengiriman_stok->getByWhere(decrypt($row))[0]->current_qty;
            if ($data_current_qty == 0) {
                $ganti_status[] = 1;
            } else {
                $ganti_status[] = 0;
            }
        }
        if ((count(array_unique($ganti_status)) === 1) && in_array(1, $ganti_status)) {
            $status['status_pengiriman'] = 'Diterima Seluruhnya';
        } else {
            $status['status_pengiriman'] = 'Diterima Sebagian';
        }
        $status['id_pengiriman_stok'] = $id_pengiriman_stok;

        //add riwayat status pengiriman stok
        $this->md_riwayat_status_pengiriman_stok->add($status);
        $id_riwayat['id_latest_riwayatstatus_pengiriman'] = $this->db->insert_id();
        $this->md_pengiriman_stok->update(['id_pengiriman_stok' => $id_pengiriman_stok], $id_riwayat);

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            //add log
            $temp1 = $this->md_penerimaan_stok->getById($id_penerimaan_stok);
            $aksi = 'Edit Penerimaan Stok';
            $ket = 'Mengedit data Penerimaan stok - No Penerimaan Stok : ' . $temp1[0]->no_penerimaan_stok;
            addlog($aksi, $ket);
        } else {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
        }
        ajaxReturnDie('success', 'Data Berhasil di Update!', TRUE);
    }

    public function edit($param = "")
    {
        grantAccessFor('all');
        
        $id                             = decrypt($param);
		$page_data['penerimaan_stok'] 	= $this->md_penerimaan_stok->getById($id);
        $page_data['pengiriman_stok'] 	= $this->md_pengiriman_stok->getByWhere(['ps.id_pengiriman_stok' => $page_data['penerimaan_stok'][0]->id_pengiriman_stok]);
        $page_data['detail_barang_penerimaan_stok'] = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_penerimaan_stok' => $page_data['penerimaan_stok'][0]->id_penerimaan_stok]);
        foreach ($page_data['detail_barang_penerimaan_stok'] as $row) {
            $row->exp_date = $this->md_detail_barang->getById($row->id_detail_barang_lama)[0]->exp_date;
        }
        $page_data['page_name']		= 'v_penerimaan_stok_form';
        $page_data['page_title'] 	= 'Form Penerimaan Pemindahan Stok';
        $page_data['page_desc']  	= 'Isi form Penerimaan Barang dengan benar';
        $page_data['switch']		= $this->id_navbar();
        $this->load->view('index', $page_data);
    }

    public function delete($param)
    {
        grantAccessFor('all');

        $id = decrypt($param);

        //delete data penerimaan_barang
        $id_penerimaan_barang  = $this->md_penerimaan_stok->getById($id)[0]->id_penerimaan_barang;

        //cek apakah detail barang sudah pernah di gunakan pada pengeluaran barang
        $tmp = $this->md_detail_barang->getByIdPenerimaanBarang($id_penerimaan_barang);
        $cek = []; //cek lagi
        foreach ($tmp as $row) {
            $cek[] = $this->md_detail_barang_keluar->cekByIdDetailBarang($row->id_detail_barang);
        }
        foreach ($cek as $row) {
            // echo '<pre>'; print_r( $cek );die; echo '</pre>';
            if (!empty($row)) {
                ajaxReturnDie('error', 'Data Sudah di Gunakan di Pengeluaran Barang!');
            }
        }
        //cek apakah detail_barang pada penerimaan barang ini pernah di pakai di pengiriman stok
        $cek2 = []; //cek lagi
        foreach ($tmp as $row) {
            $cek2[] = $this->md_detail_barang_pengiriman_stok->getByWhere($row->id_detail_barang);
        }
        foreach ($cek2 as $row) {
            if (!empty($row)) {
                ajaxReturnDie('error', 'Data Sudah di Gunakan di Pemindahan Stok!');
            }
        }
        $data['status'] = 0;
        $this->db->trans_begin();
        $this->md_penerimaan_barang->update(['id_penerimaan_barang' => $id_penerimaan_barang], $data);
        $this->md_detail_barang->deleteByIdPenerimaanBarang($data, $id_penerimaan_barang); //delete detail barang by id_penerimaan_barang
        $this->md_penerimaan_stok->update($id, $data); // delete penerimaan stok

        $detail_barang_penerimaan_stok = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_penerimaan_stok' => $id]);
        foreach ($detail_barang_penerimaan_stok as $row) {
            $current_stock = $this->md_detail_barang_pengiriman_stok->getDetailBarangPengirimanStokById($row->id_detail_barang_pengiriman_stok)[0]->current_qty;
            $dt['current_qty'] = $current_stock + $row->qty;
            $this->md_detail_barang_pengiriman_stok->updateDetailBarangPengirimanStok($row->id_detail_barang_pengiriman_stok, $dt);
        }
        $this->md_detail_barang_penerimaan_stok->updateByWhere(['id_penerimaan_stok' => $id], $data);

        //cek qty detail_barang_pengiriman_stok untuk update status pengiriman
        $id_pengiriman_stok = $this->md_penerimaan_stok->getById($id)[0]->id_pengiriman_stok;
        $id_detail_barang_pengiriman_stok = $this->md_detail_barang_pengiriman_stok->getByIdPengirimanStok($id_pengiriman_stok);
        $ganti_status = [];
        foreach ($id_detail_barang_pengiriman_stok as $row) {
            $data_current_qty = $this->md_detail_barang_pengiriman_stok->getByWhere($row->id_detail_barang_pengiriman_stok);
            if ($data_current_qty[0]->qty == $data_current_qty[0]->current_qty) {
                $ganti_status[] = 1;
            } else {
                $ganti_status[] = 0;
            }
        }
        if ((count(array_unique($ganti_status)) === 1) && in_array(1, $ganti_status)) {
            $status['status_pengiriman'] = 'Belum Diterima';
        } else {
            $status['status_pengiriman'] = 'Diterima Sebagian';
        }
        $status['id_pengiriman_stok'] = $id_pengiriman_stok;

        //add riwayat status pengiriman stok
        $this->md_riwayat_status_pengiriman_stok->add($status);
        $id_riwayat['id_latest_riwayatstatus_pengiriman'] = $this->db->insert_id();
        $this->md_pengiriman_stok->update(['id_pengiriman_stok' => $id_pengiriman_stok], $id_riwayat);

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            //add log
            $temp = $this->md_penerimaan_stok->getById($id);
            $aksi = 'Hapus Penerimaan Stok';
            $ket = 'Menghapus Penerimaan Stok - No Penerimaan Stok: ' . $temp[0]->no_penerimaan_stok;
            addlog($aksi, $ket);
        } else {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
        }
        ajaxReturnDie('success', 'Data berhasil dihapus', 'reload_table');
    }

    public function print($param){
        $data['penerimaan_stok'] = $this->md_penerimaan_stok->getByWhere(['ps.id_penerimaan_stok' => decrypt($param)]);
        $data['detail_barang_penerimaan_stok'] = $this->md_detail_barang_penerimaan_stok->getByWhere(['dbps.id_penerimaan_stok' => decrypt($param)]);
        // echo '<pre>'; print_r( $data );die; echo '</pre>';
        $this->load->view('pages/v_print/print_penerimaan_stok', $data);
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_penerimaan_stok->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_penerimaan_stok);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-success btn-file" no-penerimaan-stok="' . $row->no_penerimaan_stok . '" data-id="' . $id . '"><i class="far fa-file-pdf"></i></button>
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="penerimaan_stok/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->no_penerimaan_stok;
            $th[] = date('d-m-Y', strtotime($row->tgl_penerimaan_stok));
            $th[] = $row->gudang_asal;
            $th[] = $row->gudang_tujuan;
            $th[] = $row->keterangan;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
