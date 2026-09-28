<?php

use FontLib\Table\Type\post;

use function Complex\rho;

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_customer');
        $this->load->model('md_syarat_pembayaran');
        $this->load->model('md_ekspedisi');
        $this->load->model('md_tarif_pajak');
        $this->load->model('md_invoice');
        $this->load->model('md_barang');
        $this->load->model('md_detail_barang_invoice');
        $this->load->model('md_pengeluaran_barang');
        $this->load->model('md_detail_barang_keluar');
        $this->load->model('md_virtual_account');
    }
	
	function id_navbar(){
		$id_navbar = "inventory";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        // $page_data['gudang']  = $this->md_gudang->getByWhere(['g.status' => 1]);
        $page_data['switch']		= $this->id_navbar();
		$page_data['page_name']  	= 'v_invoice';
        $page_data['page_title'] 	= 'Invoice';
        $page_data['page_desc']  	= 'Management Data Invoice';
        $this->load->view('index', $page_data);
    }

    public function show($param = "", $param2 = "")
    {
        grantAccessFor('all');

        //form invoice berdasarkan pengeluaran barang
        if ($param) {
            $id_customer = decrypt($param);
            $id_pengeluaran_barang = explode("-", $param2);
            //gabung semua detail barang pada pengeluaran barang dalam satu array
            $page_data['detail_barang'] = [];
            $no_po = [];
            foreach ($id_pengeluaran_barang as $row) {
                $detail_barang = $this->md_detail_barang_keluar->getByIdPengeluaranBarang(decrypt($row));
                foreach ($detail_barang as $row2) {
                    array_push($page_data['detail_barang'], $row2);
                }

                //get no po, jika semua no po pengeluaran barang sama tampilkan, jika tidak kosongkan '$page_data['no_po']'
                $tmp = $this->md_pengeluaran_barang->getById(decrypt($row));
                foreach ($tmp as $row2) {
                    array_push($no_po, $row2->no_po);
                }
            }
            $page_data['switch']				= $this->id_navbar();
			$page_data['no_po'] 				= count(array_unique($no_po)) == 1 ? $no_po[0] : NULL;
            $page_data['id_pengeluaran_barang'] = implode('-', $id_pengeluaran_barang);
            $page_data['marketing']  			= $this->md_pengguna->getByWhere(['p.status' => 1, 'p.level' => 'Marketing']);
            $page_data['syarat_pembayaran']  	= $this->md_syarat_pembayaran->getByWhere(['sp.status' => 1]);
            $page_data['nama_customer'] 		= $this->md_customer->getById($id_customer);
            $page_data['pajak']  				= $this->md_tarif_pajak->getByWhere(['status' => 1]);
            $page_data['page_name']  			= 'v_invoice_form_pengeluaran_barang';
            $page_data['page_title'] 			= 'Invoice';
            $page_data['page_desc']  			= 'Tarik  Invoice Berdasarkan Pengeluaran Barang';
            $this->load->view('index', $page_data);
        } else {
            // $page_data['customer']  = $this->md_customer->getByWhere();
            $page_data['switch']			= $this->id_navbar();
			$page_data['ekspedisi']  		= $this->md_ekspedisi->getByWhere(['e.status' => 1]);
            $page_data['marketing']  		= $this->md_pengguna->getByWhere(['p.status' => 1, 'p.level' => 'Marketing']);
            $page_data['syarat_pembayaran'] = $this->md_syarat_pembayaran->getByWhere(['sp.status' => 1]);
            $page_data['pajak']  			= $this->md_tarif_pajak->getByWhere(['status' => 1]);
            $page_data['temp_data'] 		= $this->md_invoice->getTempData();
            if (isset($page_data['temp_data'])) {
                foreach ($page_data['temp_data'] as $row) {
                    $row->tgl_invoice = date('d-m-Y', strtotime($row->tgl_invoice));
                }
            }
            $page_data['detail_barang_temp'] = $this->md_invoice->getDetailBarangTempBySess();
            if (isset($page_data['detail_barang_temp'])) {
                foreach ($page_data['detail_barang_temp'] as $row) {
                    $row->harga = rupiah($row->harga);
                    $row->harga_total = rupiah($row->harga_total);
                    $row->discount = rupiah($row->discount);
                }
            }
            $page_data['page_name']  = 'v_invoice_form';
            $page_data['page_title'] = 'Form Invoice';
            $page_data['page_desc']  = 'Isi form Invoice dengan  benar';
            $this->load->view('index', $page_data);
        }
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
		$page_data['switch']	= $this->id_navbar();
        $page_data['invoice'] 	= $this->md_invoice->getById($id);
        foreach ($page_data['invoice'] as $row) {
            $row->id_invoice = encrypt($row->id_invoice);
            $row->id_customer = encrypt($row->id_customer);
            $row->id_marketing = encrypt($row->id_marketing);
            $row->id_syarat_pembayaran = encrypt($row->id_syarat_pembayaran);
            $row->id_ekspedisi = encrypt($row->id_ekspedisi);
            $row->id_tarif_pajak = encrypt($row->id_tarif_pajak);
            $row->tgl_invoice = date('d-m-Y', strtotime($row->tgl_invoice));
            $row->total = rupiah($row->total);
            $row->ongkir = rupiah($row->ongkir);
            $row->total_keseluruhan = rupiah($row->total_keseluruhan);
            $row->nama_customer = $row->nama_customer;
        }
        $page_data['detail_barang_invoice'] = $this->md_detail_barang_invoice->getByIdInvoice($id);
        foreach ($page_data['detail_barang_invoice'] as $row) {
            if ($row->id_pengeluaran_barang)
                $row->no_pengeluaran_barang = $this->md_pengeluaran_barang->getById($row->id_pengeluaran_barang)[0]->no_pengiriman;

            $row->id_detail_barang_invoice = encrypt($row->id_detail_barang_invoice);
            $row->id_detail_baranid_invoiceg_invoice = encrypt($row->id_invoice);
            $row->id_barang = encrypt($row->id_barang);
            $row->harga = rupiah($row->harga);
            $row->discount = rupiah($row->discount);
            $row->harga_total = rupiah($row->harga_total);
        }
        $page_data['new_detail_barang_temp'] = $this->md_invoice->getByIdInvoice($id);
        if ($page_data['new_detail_barang_temp']) {
            foreach ($page_data['new_detail_barang_temp'] as $row) {
                $row->id_detail_barang_invoice_temp = encrypt($row->id_detail_barang_invoice_temp);
                $row->id_barang = encrypt($row->id_barang);
                $row->pengguna_id = encrypt($row->pengguna_id);
                $row->id_invoice = encrypt($row->id_invoice);
                $row->harga = rupiah($row->harga);
                $row->discount = rupiah($row->discount);
                $row->harga_total = rupiah($row->harga_total);
            }
        }
        $page_data['customer']  = $this->md_customer->getByWhere();
        $page_data['marketing']  = $this->md_pengguna->getByWhere(['p.status' => 1, 'p.level' => 'Marketing']);
        $page_data['syarat_pembayaran']  = $this->md_syarat_pembayaran->getByWhere(['sp.status' => 1]);
        $page_data['pajak']  = $this->md_tarif_pajak->getByWhere(['status' => 1]);
        $page_data['ekspedisi']  = $this->md_ekspedisi->getByWhere(['g.status' => 1]);
        $page_data['page_name']  = 'v_invoice_form';
        $page_data['page_title'] = 'Invoice';
        $page_data['page_desc']  = 'Management Data Invoice';
        $this->load->view('index', $page_data);
    }

    public function get($param)
    {
        if ($param == 'detail_barang_invoice_temp') {
            $id_detail_barang_invoice_temp = decrypt($this->input->post('id_detail_barang_invoice_temp'));
            $data = $this->md_invoice->getDetailBarangTempById($id_detail_barang_invoice_temp);
            foreach ($data as $row) {
                $row->id_detail_barang_invoice_temp = encrypt($row->id_detail_barang_invoice_temp);
                $row->pengguna_id = encrypt($row->pengguna_id);
                $row->id_barang = encrypt($row->id_barang);
                $row->qty = $row->qty;
                $row->harga = rupiah($row->harga);
                $row->harga_total = rupiah($row->harga_total);
                $row->discount = rupiah($row->discount);
            }
            echo json_encode($data);
            die;
        } else if ($param == 'detail_barang_invoice') {
            $id_detail_barang_invoice = decrypt($this->input->post('id_detail_barang_invoice'));
            $data['detail_barang_invoice'] = $this->md_invoice->getDetailBarangInvoiceById($id_detail_barang_invoice);
            foreach ($data['detail_barang_invoice'] as $row) {
                $row->id_detail_barang_invoice = encrypt($row->id_detail_barang_invoice);
                $row->id_invoice = encrypt($row->id_invoice);
                $row->id_barang = encrypt($row->id_barang);
                $row->qty = $row->qty;
                $row->current_qty = $row->current_qty;
                $row->harga = rupiah($row->harga);
                $row->harga_total = rupiah($row->harga_total);
                $row->discount = rupiah($row->discount);
            }
            echo json_encode($data);
            die;
        } else if ($param == 'link_file_pendukung') {
            $id = decrypt($this->input->post('id'));
            $tmp = $this->md_invoice->getById($id);
            $data['file_invoice'] = $tmp[0]->file_invoice;
            $data['file_po'] = $tmp[0]->file_po;
            $data['file_faktur_pajak'] = $tmp[0]->file_faktur_pajak;
            $data['file_lainnya'] = $tmp[0]->file_lainnya;

            echo json_encode($data);
            die;
        } else if ($param == "download_file") {
            $id = decrypt($this->input->post('id'));
            $tmp = $this->md_invoice->getById($id);
            $action = $this->input->post('action');
            if ($action == 'file_faktur_pajak') {
                $link = $tmp[0]->file_faktur_pajak;
            } else if ($action == 'file_invoice') {
                $link = $tmp[0]->file_invoice;
            } else if ($action == 'file_po') {
                $link = $tmp[0]->file_po;
            } else if ($action == 'file_lainnya') {
                $link = $tmp[0]->file_lainnya;
            }
            echo json_encode($link);
            die;
        } else if ($param == "form_tarik_pengeluaran") {
            $id_customer = $this->input->post('id') ? decrypt($this->input->post('id')) : NULL;
            $where = ['pb.id_customer' => $id_customer, 'pb.id_invoice' => NULL, 'pb.from_invoice' => NULL];
            $pengeluaran_barang = $this->md_pengeluaran_barang->getByWhere($where);
            foreach ($pengeluaran_barang as $row) {
                $row->id_pengeluaran_barang = encrypt($row->id_pengeluaran_barang);
                $row->no_pengiriman = $row->no_pengiriman;
                $row->tgl_keluar = date_view_format($row->tgl_keluar);
                unset($row->id_customer, $row->id_gudang, $row->id_ekspedisi, $row->id_invoice);
            }
            echo json_encode($pengeluaran_barang);
            die;
        } else if ($param == "resume_detail_invoice") {
            $id = decrypt($this->input->post('id_invoice'));
            $data['invoice'] = $this->md_invoice->getById($id);
            foreach ($data['invoice'] as $row) {
                $row->id_invoice = encrypt($row->id_invoice);
                $row->id_customer = encrypt($row->id_customer);
                $row->id_marketing = encrypt($row->id_marketing);
                $row->id_syarat_pembayaran = encrypt($row->id_syarat_pembayaran);
                $row->id_ekspedisi = encrypt($row->id_ekspedisi);
                $row->id_tarif_pajak = encrypt($row->id_tarif_pajak);
            }

            $data['detail_barang'] = $this->md_detail_barang_invoice->getByIdInvoice($id);
            foreach ($data['detail_barang'] as $row) {
                $row->id_detail_barang_invoice = encrypt($row->id_detail_barang_invoice);
                $row->id_invoice = encrypt($row->id_invoice);
                $row->id_pengeluaran_barang = $row->id_pengeluaran_barang ? encrypt($row->id_pengeluaran_barang) : NULL;
                $row->id_barang = encrypt($row->id_barang);
                $row->harga = rupiah($row->harga);
                $row->harga_total = rupiah($row->harga_total);
                $row->discount = rupiah($row->discount);
            }
            echo json_encode($data);
            die;
        }
    }

    public function add($param = '', $param2 = '')
    {
        grantAccessFor('all');

        //all about temp data on form
        if ($param == 'temp') {
            //cek apakah temp data sudah ada
            $temp = $this->md_invoice->getTempData();
            if ($temp && $param2 == NULL) {
                //update jika sudah ada temp data sebelumnya
                $data['pengguna_id'] = sessPenggunaId();
                $data['id_customer'] = decrypt($this->input->post('id_customer'));
                $data['tgl_invoice'] = $this->input->post('tgl_invoice') == 00 - 00 - 0000 ? '0000-00-00' : date_db_format($this->input->post('tgl_invoice'));
                $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
                $data['id_marketing'] = decrypt($this->input->post('id_marketing'));
                $data['id_syarat_pembayaran'] = decrypt($this->input->post('id_syarat_pembayaran'));
                $data['no_invoice'] = $this->input->post('no_invoice');
                $data['no_po'] = $this->input->post('no_po');
                // echo '<pre>'; print_r( $this->input->post() );die; echo '</pre>';
                $this->md_invoice->updateTempData(['pengguna_id' => sessPenggunaId()], $data);
            } else if ($param2 == 'detail_barang_invoice') {
                //add detail barang invoice temp 
                $dt['id_barang'] = $this->input->post('id_barang') ? decrypt($this->input->post('id_barang')) : NULL;
                $dt['qty'] = $this->input->post('qty');
                $dt['harga'] = str_replace('.', '', $this->input->post('harga'));
                $dt['harga_total'] = str_replace('.', '', $this->input->post('harga_total'));
                checkEmptyForm($dt);
                $dt['discount'] = str_replace('.', '', $this->input->post('discount'));
                $dt['id_invoice'] = $this->input->post('id_invoice')  ? decrypt($this->input->post('id_invoice')) : NULL;
                if ($this->input->post('id_detail_barang_invoice_temp')) {
                    //update detail barang temp
                    $dt['pengguna_id'] = sessPenggunaId();
                    $id = decrypt($this->input->post('id_detail_barang_invoice_temp'));
                    $where = ['id_detail_barang_invoice_temp' => $id];
                    $this->md_invoice->updateDetailBarangInvoiceTemp($where, $dt);
                    ajaxReturnDie('success', 'Data Berhasil Diubah', TRUE);
                } else {
                    $dt['pengguna_id'] = sessPenggunaId();
                    //add detail barang temp
                    $this->md_invoice->addDetailBarangTemp($dt);
                    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
                }
            } else {
                //add temp data jika belum ada 
                $data['pengguna_id'] = sessPenggunaId();
                $data['id_customer'] = decrypt($this->input->post('id_customer'));
                $data['tgl_invoice'] = $this->input->post('tgl_invoice') == 00 - 00 - 0000 ? '0000-00-00' : date_db_format($this->input->post('tgl_invoice'));
                $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
                $data['id_marketing'] = decrypt($this->input->post('id_marketing'));
                $data['id_syarat_pembayaran'] = decrypt($this->input->post('id_syarat_pembayaran'));
                $data['no_invoice'] = $this->input->post('no_invoice');
                $data['no_po'] = $this->input->post('no_po');
                $this->md_invoice->addTempData($data);
            }
            ajaxReturnDie('success', 'Temp Data Input', TRUE);
        }
        //add data invoice
        $this->db->trans_begin();
        $data['id_customer'] = $this->input->post('id_customer') ? decrypt($this->input->post('id_customer')) : NULL;
        $data['tgl_invoice'] = date('Y-m-d', strtotime($this->input->post('tgl_invoice')));
        // $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
        $data['id_marketing'] = $this->input->post('id_marketing') ? decrypt($this->input->post('id_marketing')) : NULL;
        $data['id_syarat_pembayaran'] = $this->input->post('id_syarat_pembayaran') ? decrypt($this->input->post('id_syarat_pembayaran')) : NULL;
        $data['no_invoice'] = $this->input->post('no_invoice');
        $data['no_po'] = $this->input->post('no_po');
        $data['id_tarif_pajak'] = $this->input->post('id_tarif_pajak') ? decrypt($this->input->post('id_tarif_pajak')) : NULL;
        $data['sub_total'] = $this->input->post('sub_total') ? str_replace(".", "", $this->input->post('sub_total')) : NULL;
        $data['total'] = $this->input->post('total') == 'NaN' ? NULL : str_replace(".", "", $this->input->post('total'));
        $data['total_keseluruhan'] = $this->input->post('total_keseluruhan') == 'NaN' ? NULL : str_replace(".", "", $this->input->post('total_keseluruhan'));
        $data['status_barang_keluar'] = $this->input->post('from_pengeluaran_barang') ? 'sudah_keluar' : 'belum_keluar';
        checkEmptyForm($data);
        $data['from_pengeluaran_barang'] = $this->input->post('from_pengeluaran_barang') ? 1 : 0; // jika data dari pengeluaran barang maka 1 jika tidak 0
        $data['ongkir'] = $this->input->post('ongkir') ? str_replace(".", "", $this->input->post('ongkir')) : 0;

        //cek apakah no_invoice unique
        $cek = $this->md_invoice->getByWHere(['i.no_invoice' => $data['no_invoice']]);
        if ($cek) {
            ajaxReturnDie('error', 'No Invoice Sudah Ada');
        }
        $this->md_invoice->add($data);

        //add data detail barang
        $dt['id_invoice'] = $this->db->insert_id();
        $id_barang = $this->input->post('id_barang');
        foreach ($id_barang as $key => $row) {
            $dt['id_barang'] = decrypt($this->input->post('id_barang')[$key]);
            $dt['qty'] = $this->input->post('qty')[$key];
            $dt['current_qty'] = $this->input->post('from_pengeluaran_barang') ? NULL : $this->input->post('qty')[$key]; // jika data dari pengeluaran barang maka current_qty = NULL
            $dt['harga'] = $this->input->post('harga')[$key] ? str_replace(".", "", $this->input->post('harga')[$key]) : NULL;
            $dt['discount'] = $this->input->post('discount')[$key] ? str_replace(".", "", $this->input->post('discount')[$key]) : NULL;
            $dt['harga_total'] = $this->input->post('harga_total')[$key] ? str_replace(".", "", $this->input->post('harga_total')[$key]) : NULL;
            // checkEmptyForm($dt);
            $dt['id_pengeluaran_barang'] = $this->input->post('from_pengeluaran_barang') ? decrypt($this->input->post('id_pengeluaran_barang')[$key]) : NULL; // jika data dari pengeluaran barang maka isi id_pengeluaran_barang
            $this->md_detail_barang_invoice->add($dt);

            //jika berasal dari detail_barang_invoice tambahkan id_detail_barang_invoice ke detail_barang_keluar
            if ($this->input->post('from_pengeluaran_barang')) {
                $dt2['id_detail_barang_invoice'] = $this->db->insert_id();
                $id_detail_barang_keluar = decrypt($this->input->post('id_detail_barang_keluar')[$key]);
                $this->md_detail_barang_keluar->updateDetailBarangKeluar($id_detail_barang_keluar, $dt2);
            }
        }
        //jika data berasal dari invoice pengeluaran barang, tambah id_invoice di pengeluaran baran tersebut
        if ($this->input->post('from_pengeluaran_barang')) {
            $id_pengeluran_barang = explode('-', $this->input->post('from_pengeluaran_barang'));
            foreach ($id_pengeluran_barang as $row) {
                $this->md_pengeluaran_barang->update(['id_pengeluaran_barang' => decrypt($row)], ['id_invoice' => $dt['id_invoice']]);
            }
        }

        //destroy temp data
        $this->md_invoice->destroyTempData();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            //add log
            $aksi = 'Tambah Data Invoice';
            $ket = 'Menambahkan data Invoice - No Invoice : ' . $data['no_invoice'];
            addlog($aksi, $ket);
        } else {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
        }
        ajaxReturnDie('success', 'Data Berhasil di masukan', base_url('invoice'));
    }

    public function update($param = "")
    {
        grantAccessFor('all');

        if ($param == 'detail_barang_invoice') {
            $id_detail_barang_invoice = decrypt($this->input->post('id_detail_barang_invoice'));

            $data['harga'] = str_replace('.', '', $this->input->post('harga'));
            $data['harga_total'] = str_replace('.', '', $this->input->post('harga_total'));
            $data['discount'] = str_replace('.', '', $this->input->post('discount'));
            checkEmptyForm($data);
            if ($this->input->post('from_pengeluaran_barang') != 1) {
                //jika data dari pengeluaran barang tidak bisa edit qty
                //cek apakah qty yg baru lebih besar dari total qty yang sudah pernah di pakai di pengeluaran_barang
                $where1 = ['dbk.status' => 1, 'dbk.from_detail_barang_invoice' => $id_detail_barang_invoice];
                $temp = $this->md_detail_barang_keluar->getByWhere2($where1);
                $tot_qty_pengeluara_barang = [];
                foreach ($temp as $row) {
                    array_push($tot_qty_pengeluara_barang, $row->qty);
                }
                $tot_qty_pengeluara_barang = array_sum($tot_qty_pengeluara_barang);
                if ($this->input->post('qty') >= $tot_qty_pengeluara_barang) {
                    $data['qty'] = $this->input->post('qty');
                    checkEmptyForm($data);
                    $data['current_qty'] = $data['qty'] - $tot_qty_pengeluara_barang;
                } else {
                    ajaxReturnDie('error', 'Data sudah digunakan di pengeluaran barang!');
                }
            }

            $this->db->trans_begin();
            //update detail barang invoice
            $this->md_detail_barang_invoice->updateDetailBarangInvoice($id_detail_barang_invoice, $data);

            if ($this->db->trans_status() === TRUE) {
                //////cek dan update status_barang_keluar///////
                $id_invoice = $this->md_detail_barang_invoice->getByWhere($id_detail_barang_invoice)[0]->id_invoice;
                $cek_status = $this->md_detail_barang_invoice->getByIdInvoice($id_invoice);
                $belum_keluar = [];
                $sudah_keluar = [];
                $keluar_sebagian = [];
                foreach ($cek_status as $row) {
                    if ($row->qty == $row->current_qty) {
                        $belum_keluar[] = 1;
                    } else if ($row->current_qty == 0) {
                        $sudah_keluar[] = 1;
                    } else {
                        $keluar_sebagian[] = 1;
                    }
                }

                if (count($cek_status) == count($sudah_keluar)) {
                    $status['status_barang_keluar'] = 'sudah_keluar';
                } else if (count($cek_status) == count($belum_keluar)) {
                    $status['status_barang_keluar'] = 'belum_keluar';
                } else {
                    $status['status_barang_keluar'] = 'keluar_sebagian';
                }
                $this->md_invoice->update(['id_invoice' => $id_invoice], $status);
                /////end cek///////
                $this->db->trans_commit();

                //add log
                $temp1 = $this->md_detail_barang_invoice->getByWhere($id_detail_barang_invoice);
                $temp2 = $this->md_barang->getById($temp1[0]->id_barang);
                $temp3 = $this->md_invoice->getById($temp1[0]->id_invoice);
                $aksi = 'Edit Detail Barang Invoice';
                $ket = 'Mengedit barang ' . $temp2[0]->nama_barang . ' pada No Invoice: ' . $temp3[0]->no_invoice;
                addlog($aksi, $ket);
            } else {
                $this->db->trans_rollback();
                ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
            }
            ajaxReturnDie('success', 'Data Berhasil di Update!', TRUE);
        } else if ($param == 'link_file_pendukung') {
            $id = decrypt($this->input->post('id_invoice'));
            $data['file_invoice'] = $this->input->post('file_invoice');
            $data['file_po'] = $this->input->post('file_po');
            $data['file_faktur_pajak'] = $this->input->post('file_faktur_pajak');
            $data['file_lainnya'] = $this->input->post('file_lainnya');
            checkEmptyForm($data);

            $this->md_invoice->update(['id_invoice' => $id], $data);

            //add log
            $tmp = $this->md_invoice->getById($id);
            $aksi = 'Update file Pendukung Invoice';
            $ket = 'Mengedit data File Pendukung Inovice - No Invoice : ' . $tmp[0]->no_invoice;
            addlog($aksi, $ket);
            ajaxReturnDie('success', 'File Berhasil di input', TRUE);
        }
        //update data pengeluaran barang
        $id_invoice = decrypt($this->input->post('id_invoice'));
        $data['tgl_invoice'] = date('Y-m-d', strtotime($this->input->post('tgl_invoice')));
        $data['no_invoice'] = $this->input->post('no_invoice');
        $data['id_ekspedisi'] = decrypt($this->input->post('id_ekspedisi'));
        $data['id_marketing'] = decrypt($this->input->post('id_marketing'));
        $data['id_syarat_pembayaran'] = decrypt($this->input->post('id_syarat_pembayaran'));
        $data['no_po'] = $this->input->post('no_po');
        $data['id_tarif_pajak'] = $this->input->post('id_tarif_pajak') ? decrypt($this->input->post('id_tarif_pajak')) : NULL;
        $data['sub_total'] = $this->input->post('sub_total') ? str_replace(".", "", $this->input->post('sub_total')) : NULL;
        $data['total'] = $this->input->post('total') == 'NaN' ? NULL : str_replace(".", "", $this->input->post('total'));
        $data['total_keseluruhan'] = $this->input->post('total_keseluruhan') == 'NaN' ? NULL : str_replace(".", "", $this->input->post('total_keseluruhan'));
        checkEmptyForm($data);
        $data['ongkir'] = $this->input->post('ongkir') ? str_replace(".", "", $this->input->post('ongkir')) : 0;

        //cek apakah no_invoice unique
        $cek = $this->md_invoice->getByWHere(['i.no_invoice' => $data['no_invoice']]);
        if ($cek && $id_invoice != $cek[0]->id_invoice) {
            ajaxReturnDie('error', 'No Invoice Sudah Ada');
        }
        $this->db->trans_begin();
        $this->md_invoice->update(['id_invoice' => $id_invoice], $data);

        if ($this->input->post('id_barang')) {
            //add detail barang keluar
            $dt['id_invoice'] = $id_invoice;
            $id_barang = $this->input->post('id_barang');
            foreach ($id_barang as $key => $row) {
                $dt['id_barang'] = decrypt($this->input->post('id_barang')[$key]);
                $dt['qty'] = $this->input->post('qty')[$key];
                $dt['current_qty'] = $this->input->post('qty')[$key];
                $dt['harga'] = str_replace('.', '', $this->input->post('harga')[$key]);
                $dt['discount'] = str_replace('.', '', $this->input->post('discount')[$key]);
                $dt['harga_total'] = str_replace('.', '', $this->input->post('harga_total')[$key]);
                $this->md_detail_barang_invoice->add($dt);
            }
        }
        //destroy new detail barang di tampilan edit
        $this->md_invoice->destroyNewTempDetailBarang($id_invoice);

        if ($this->db->trans_status() === TRUE) {
            //////cek dan update status_barang_keluar///////
            $cek_status = $this->md_detail_barang_invoice->getByIdInvoice($id_invoice);
            $belum_keluar = [];
            $sudah_keluar = [];
            $keluar_sebagian = [];
            foreach ($cek_status as $row) {
                if ($row->qty == $row->current_qty) {
                    $belum_keluar[] = 1;
                } else if ($row->current_qty == 0) {
                    $sudah_keluar[] = 1;
                } else {
                    $keluar_sebagian[] = 1;
                }
            }

            if (count($cek_status) == count($sudah_keluar)) {
                $status['status_barang_keluar'] = 'sudah_keluar';
            } else if (count($cek_status) == count($belum_keluar)) {
                $status['status_barang_keluar'] = 'belum_keluar';
            } else {
                $status['status_barang_keluar'] = 'keluar_sebagian';
            }
            $this->md_invoice->update(['id_invoice' => $id_invoice], $status);
            /////end cek///////


            $this->db->trans_commit();
            //add log
            $aksi = 'Edit Invoice';
            $ket = 'Mengedit data Invoice - No Invoice : ' . $data['no_invoice'];
            addlog($aksi, $ket);
        } else {
            $this->db->trans_rollback();
            ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
        }

        ajaxReturnDie('success', 'Data Berhasil di ubah', TRUE);
    }

    public function delete($param = "", $param2 = "")
    {
        grantAccessFor('all');
        
        if ($param == 'detail_barang_invoice_temp') {
            $id_detail_barang_invoice_temp = decrypt($this->input->post('id_detail_barang_invoice_temp'));
            $this->md_invoice->deleteDetailBarangTemp($id_detail_barang_invoice_temp);
            ajaxReturnDie('success', 'Data Berhasil Dihapus', TRUE);
        } else if ($param == 'dest_temp') {
            $this->md_invoice->destroyTempData();
            ajaxReturnDie('success', 'Data Berhasil Dihapus', TRUE);
        } else if ($param == 'detail_barang_invoice') {
            $id_detail_barang_invoice = decrypt($param2);

            //cek apakah data detail_barang invoice sudah di gunakan di data pengeluran barang, jika ada tolak penghapusan
            $where = ['dbk.status' => 1, 'dbk.from_detail_barang_invoice' => $id_detail_barang_invoice];
            $cek = $this->md_detail_barang_keluar->getByWhere2($where);
            if ($cek) {
                ajaxReturnDie('error', 'Data sudah digunakan di pengeluaran barang');
            }
            /////////////////////////////////////////////////////////////////////////

            //jika pembuatan invoice berasal dari pengeluaran_barang, hapus id_detail_barang_invoice di detail_barang_keluar
            $where2 = ['dbk.status' => 1, 'dbk.id_detail_barang_invoice' => $id_detail_barang_invoice];
            $cek2 = $this->md_detail_barang_keluar->getByWhere2($where2);
            if ($cek2) {
                $dta2['id_detail_barang_invoice'] = NULL;
                $this->md_detail_barang_keluar->updateDetailBarangKeluar($cek2[0]->id_detail_barang_keluar, $dta2);
            }
            //////////////////////////////////////////////////////////////////

            $this->db->trans_begin();

            $data['status'] = 0;
            $temp = $this->md_detail_barang_invoice->getByWhere($id_detail_barang_invoice);
            $this->md_detail_barang_invoice->delete($id_detail_barang_invoice, $data);

            if ($this->db->trans_status() === TRUE) {
                //////cek dan update status_barang_keluar///////
                $cek_status = $this->md_detail_barang_invoice->getByIdInvoice($temp[0]->id_invoice);
                $belum_keluar = [];
                $sudah_keluar = [];
                $keluar_sebagian = [];
                foreach ($cek_status as $row) {
                    if ($row->qty == $row->current_qty) {
                        $belum_keluar[] = 1;
                    } else if ($row->current_qty == 0) {
                        $sudah_keluar[] = 1;
                    } else {
                        $keluar_sebagian[] = 1;
                    }
                }
                if (count($cek_status) == count($sudah_keluar)) {
                    $status['status_barang_keluar'] = 'sudah_keluar';
                } else if (count($cek_status) == count($belum_keluar)) {
                    $status['status_barang_keluar'] = 'belum_keluar';
                } else {
                    $status['status_barang_keluar'] = 'keluar_sebagian';
                }
                
                $this->md_invoice->update(['id_invoice' => $temp[0]->id_invoice], $status);
                /////end cek///////
                $this->db->trans_commit();
                //add log
                $temp1 = $this->md_detail_barang_invoice->getByWhere($id_detail_barang_invoice);
                $temp2 = $this->md_barang->getById($temp1[0]->id_barang);
                $temp3 = $this->md_invoice->getById($temp1[0]->id_invoice);
                $aksi = 'Hapus Detail Barang Invoice';
                $ket = 'Menghapus barang ' . $temp2[0]->nama_barang . ' pada No Invoice: ' . $temp3[0]->no_invoice;
                addlog($aksi, $ket);
            } else {
                $this->db->trans_rollback();
                ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
            }
            ajaxReturnDie('success', 'Data Berhasil Dihapus', TRUE);
        } else {
            //delete invoice sekaligus detail barang di dalamnya
            $id_invoice = decrypt($param);
            $invoice = $this->md_invoice->getById($id_invoice);

            $this->db->trans_begin();

            //jika invoice di buat berdasarkan pengeluaran_barang, hapus id_invoice di pengeluaran_barang
            if ($invoice[0]->from_pengeluaran_barang == 1) {
                $where = ['pb.status' => 1, 'pb.id_invoice' => $id_invoice];
                $cek = $this->md_pengeluaran_barang->getByWhere($where);
                foreach ($cek as $row) {
                    $this->md_pengeluaran_barang->update(['id_pengeluaran_barang' => $row->id_pengeluaran_barang], ['id_invoice' => NULL]);

                    //hapus id_detail_barang_invoice di detail_barang_keluar
                    $detail_barang_keluar = $this->md_detail_barang_keluar->getByIdPengeluaranBarang($row->id_pengeluaran_barang);
                    foreach ($detail_barang_keluar as $row2) {
                        $dta['id_detail_barang_invoice'] = NULL;
                        $this->md_detail_barang_keluar->updateDetailBarangKeluar($row2->id_detail_barang_keluar, $dta);
                    }
                }
            }
            ///////////////////////////////////////////////////////////

            //cek apakah data invoice sudah di gunakan di pengeluaran barang, jika ada tidak bisa di hapus
            $where = ['pb.status' => 1, 'pb.from_invoice' => $id_invoice];
            $cek = $this->md_pengeluaran_barang->getByWhere($where);
            if ($cek) {
                ajaxReturnDie('error', 'Data sudah digunakan di pengeluaran barang');
            }


            $data['status'] = 0;
            $this->md_invoice->update(['id_invoice' => $id_invoice], $data);
            $this->md_detail_barang_invoice->deleteByIdInvoice($id_invoice, $data);
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                //add log
                $temp1 = $this->md_invoice->getById($id_invoice);
                $aksi = 'Hapus Invoice';
                $ket = 'Menghapus Invoice - No Invoice: ' . $temp1[0]->no_invoice;
                addlog($aksi, $ket);
            } else {
                $this->db->trans_rollback();
                ajaxReturnDie('error', 'Terdapat kesalahan dalam menginputkan data. Hubungi Developer!');
            }
            ajaxReturnDie('success', 'Data berhasil dihapus', true);
        }
    }

    public function print($batch = "", $pajak = "", $ttd_digital = "", $id_invoice = "")
    {
        $data['invoice'] = $this->md_invoice->getByWhere(['i.id_invoice' => decrypt($id_invoice)]);
        if ($batch == 0) {
            $data['detail_barang_invoice'] = $this->md_detail_barang_invoice->getByIdInvoice(decrypt($id_invoice));
        } else {
            if ($data['invoice'][0]->status_barang_keluar != 'sudah_keluar') {
                echo '<pre>';
                print_r('barang belum keluar sepenuhnya');
                die;
                echo '</pre>';
            }

            $tmp = $this->md_detail_barang_invoice->getByIdInvoice(decrypt($id_invoice));
            $data['detail_barang_invoice'] = [];
            foreach ($tmp as $row) {
                $where = ['dbk.id_detail_barang_invoice' => $row->id_detail_barang_invoice];
                $where2 = ['dbk.from_detail_barang_invoice' => $row->id_detail_barang_invoice];
                $from_detail_barang_keluar = $this->md_detail_barang_keluar->getByWhere2($where);
                $to_detail_barang_keluar = $this->md_detail_barang_keluar->getByWhere2($where2);
                if ($from_detail_barang_keluar) {
                    foreach ($from_detail_barang_keluar as $row2) {
                        if ($row2->id_detail_barang_invoice == $row->id_detail_barang_invoice) {
                            $row2->harga = $row->harga;
                            $row2->discount = $row->discount;
                            $row2->harga_total = $row->discount ? $row->harga * $row2->qty - $row->discount : $row->harga * $row2->qty;
                            $row2->kode_barang = $row->kode_barang;
                            $row2->nama_barang = $row->nama_barang;
                            $row2->nama_satuan = $row->nama_satuan;
                            $row2->exp_date = $row2->exp_date != 0000 - 00 - 00 ? date_view_format($row2->exp_date) : '-';
                            array_push($data['detail_barang_invoice'], $row2);
                        }
                    }
                }
                if ($to_detail_barang_keluar) {
                    foreach ($to_detail_barang_keluar as $row2) {
                        if ($row2->from_detail_barang_invoice == $row->id_detail_barang_invoice) {
                            $row2->harga = $row->harga;
                            $row2->discount = $row->discount;
                            $row2->harga_total = $row->discount ? $row->harga * $row2->qty - $row->discount : $row->harga * $row2->qty;
                            $row2->kode_barang = $row->kode_barang;
                            $row2->nama_barang = $row->nama_barang;
                            $row2->nama_satuan = $row->nama_satuan;
                            $row2->exp_date = $row2->exp_date != 0000 - 00 - 00 ? date_view_format($row2->exp_date) : '-';
                            array_push($data['detail_barang_invoice'], $row2);
                        }
                    }
                }
            }
        }
        // echo '<pre>'; print_r( $data['detail_barang_invoice'] );die; echo '</pre>';
        $data['no_va'] = $this->md_virtual_account->getByWhere(['va.status' => 1, 'va.id_customer' => $data['invoice'][0]->id_customer]);
        $data['batch'] = $batch;
        $data['pajak'] = $pajak;
        $data['ttd_digital'] = $ttd_digital;
        // echo '<pre>'; print_r( $data );die; echo '</pre>';
        $this->load->view('pages/v_print/print_invoice', $data);
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_invoice->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       = encrypt($row->id_invoice);
            $li_btn   = '
                <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-success btn-file" no-invoice="' . $row->no_invoice . '" data-id="' . $id . '"><i class="far fa-file-pdf"></i></button>
                    <button type="button" class="btn btn-sm btn-warning btn-detail" data-id="' . $id . '"><i class="fas fa-info-circle"></i></button>
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="invoice/delete"><i class="bx bx-trash"></i></button>
                </div>';

            if ($row->status_barang_keluar == 'sudah_keluar') {
                $status_barang_keluar = '<span class="badge-success badge-pill">Sudah Keluar</span>';
            } else if ($row->status_barang_keluar == 'belum_keluar') {
                $status_barang_keluar = '<span class="badge-primary badge-pill">Belum Keluar</span>';
            } else {
                $status_barang_keluar = '<span class="badge-warning badge-pill">Keluar Sebagian</span>';
            }

            $from_pengeluaran_barang = $row->from_pengeluaran_barang == 1 ? "<span class='badge-primary badge-pill'>TRUE</span>" : "";
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->no_invoice;
            $th[] = date('d-m-Y', strtotime($row->tgl_invoice));
            $th[] = $row->nama_customer;
            $th[] = $row->nama_marketing;
            $th[] = $from_pengeluaran_barang;
            $th[] = $status_barang_keluar;
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}
