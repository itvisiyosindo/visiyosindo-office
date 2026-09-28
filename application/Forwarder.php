<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Forwarder extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_forwarder');
        $this->load->model('md_pelanggan');
        $this->load->model('md_pengguna');
        $this->load->model('md_prov_kota');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('datetime_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
    }
	
	function id_navbar(){
		$id_navbar = "helpdesk";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']     = 'forwarder/v_forwarder';
        $page_data['page_title']    = 'Forwarder';
        $page_data['page_desc']     = 'Master Data Forwarder';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $data['nama']	= $this->input->post('nama', TRUE);
        $data['alamat']	= $this->input->post('alamat', TRUE);
        $data['contact']	= $this->input->post('contact', TRUE);
        $data['website']	= $this->input->post('website', TRUE);
        $data['keterangan']	= $this->input->post('keterangan', TRUE);

        $this->md_forwarder->add($data);

        /** LOG */
        addLog('Forwarder', 'Menambah Forwarder "' . $data['nama'] . '"');
        ajaxReturnDie('success', 'Data berhasil ditambahkan', 'reload_table');
    }

    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_forwarder->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }

    public function update()
    {        
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['nama']	= $this->input->post('nama', TRUE);
        $data['alamat']	= $this->input->post('alamat', TRUE);
        $data['contact']	= $this->input->post('contact', TRUE);
        $data['website']	= $this->input->post('website', TRUE);
        $data['keterangan']	= $this->input->post('keterangan', TRUE);

        $this->md_forwarder->update($id, $data);

        /** LOG */
        addLog('Forwarder', 'Memperbarui data Forwarder "' . $data['nama'] . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', 'reload_table');
	}
	
    public function delete($param1)
    {
        grantAccessFor('all');
        
        $id = $param1;
        $this->md_forwarder->hapus('id = '.$id, 'forwarder');
        
        addLog('Forwarder', 'Menghapus Forwarder');
        ajaxReturnDie('success', 'Forwarder Berhasil Dihapus', 'reload_table');
			
		
    }

    public function pagination()
    {
        grantAccessFor('all');

        $dt    = $this->md_forwarder->getAll();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       	= encrypt($row->id);
            $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'.$row->id.'" data-object="forwarder/delete/'.$row->id.'"><i class="bx bx-trash"></i></button>
                </div>';
                
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->nama;
            $th[] = $row->alamat;
            $th[] = $row->contact;
            $th[] = $row->website;
            $th[] = $row->keterangan;
            $th[] = $li_btn;
            
            $data[] = $th;

        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }









    

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if ($param == 'list') {
			      $page_data['switch']      		= $this->id_navbar();
            $page_data['page_name']       	= 'forwarder/v_list_forwarder';
            $page_data['page_title']      	= 'Approval Forwarder';
            $page_data['page_desc']       	= 'Daftar Approval Forwarder Luar Negeri';
            $this->load->view('index', $page_data);
        }else if ($param == 'permintaan') {
			      $page_data['switch']      		= $this->id_navbar();
            $page_data['page_name']       	= 'forwarder/v_all_forwarder';
            $page_data['page_title']      	= 'Approval Forwarder';
            $page_data['page_desc']       	= 'Daftar Approval Forwarder Luar Negeri yang Diajukan';
            $this->load->view('index', $page_data);
        }else if ($param == 'detail') {
			      $page_data['switch']      		  = $this->id_navbar();
            $page_data['data_aprv']	        = $this->md_forwarder->getAppByID($param2);
            $page_data['detail_aprv']	      = $this->md_forwarder->getDetailAppById($param2);
            $page_data['forwarderNames'] = array_unique(array_map(function($item) {
                return $item->nama_forwarder;
            }, $page_data['detail_aprv']));
		        $page_data['list_for']          = $this->md_forwarder->getForByWhere(['g.status' => 1]);
            $page_data['pengguna']          = $this->md_pengguna->getById(sessPenggunaId());
            $page_data['list_kota']         = $this->md_prov_kota->getAllKota();
            $page_data['page_name']       	= 'forwarder/v_aju_forwarder';
            $page_data['page_title']      	= 'Approval Forwarder';
            $page_data['page_desc']       	= 'Form Pengajuan Approval Forwarder Luar Negeri';
            $this->load->view('index', $page_data);
			
        }else if ($param == 'detail_data') {
			      $page_data['switch']      		  = $this->id_navbar();
            $page_data['data_aprv']	        = $this->md_forwarder->getAppByID($param2);
            $page_data['detail_aprv']	      = $this->md_forwarder->getDetailAppById($param2);
            $page_data['forwarderNames'] = array_unique(array_map(function($item) {
                return $item->nama_forwarder;
            }, $page_data['detail_aprv']));
		        $page_data['list_for']          = $this->md_forwarder->getForByWhere(['g.status' => 1]);
            $page_data['pengguna']          = $this->md_pengguna->getById(sessPenggunaId());
            $page_data['list_kota']         = $this->md_prov_kota->getAllKota();
            $page_data['page_name']       	= 'forwarder/v_detail_forwarder';
            $page_data['page_title']      	= 'Approval Forwarder';
            $page_data['page_desc']       	= 'Detail Approval Forwarder Luar Negeri';
            $this->load->view('index', $page_data);
			
        }


    }

    public function pagination_permintaan()
    {
        grantAccessFor('all');

        $dt    = $this->md_forwarder->getAllAppBy(sessPenggunaId());
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       	= encrypt($row->id);

            if($row->status==1 || $row->status== 0){
              $li_btn   	= '
                  <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                  
                  </div>';
            }else{
              $li_btn   	= '';
            }
            $kode	= '<a href="forwarder/show/detail/'.$row->id.'">'.$row->kode.'</a>'; 

            if($row->status== "0"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Proses Pengajuan</span>';
              }else if($row->status == "1"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
              }else if($row->status == "2"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning & Business Management</span>';
              }else if($row->status == "3"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director</span>';
              }else{
                 $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
              } 

              
            $tanggal = $row->tanggal;
            $bulanTahun = date('F Y', strtotime($tanggal));

            // ubah nama bulan Inggris ke Indonesia
            $bulanIndonesia = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember',
            ];

            list($bulanInggris, $tahun) = explode(' ', $bulanTahun);
            $tanggalFormatted = $bulanIndonesia[$bulanInggris] . ' ' . $tahun;

            $kurs = 'Rp ' . number_format($row->kurs, 0, ',', '.');

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $kode;
            $th[] = $row->nama;
            $th[] = $row->pol;
            $th[] = $row->pod;
            $th[] = $kurs;
            $th[] = $row->pengaju;
            $th[] = $stat_surat;
            $th[] = $li_btn;
            
            $data[] = $th;

        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function pagination_list()
    {
        grantAccessFor('all');

        $dt    = $this->md_forwarder->getAllApp();
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       	= encrypt($row->id);

            if($row->status==1 || $row->status== 0){
              $li_btn   	= '
                  <div class="btn-group" role="group" aria-label="First group">
                    <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                  
                  </div>';
            }else{
              $li_btn   	= '';
            }
            $kode	= '<a href="forwarder/show/detail_data/'.$row->id.'">'.$row->kode.'</a>'; 

            if($row->status== "0"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Proses Pengajuan</span>';
              }else if($row->status == "1"){
                $stat_surat = '<span class="badge badge-ecommerce badge-success">Baru Diajukan</span>';
              }else if($row->status == "2"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director of Corporate Planning & Business Management</span>';
              }else if($row->status == "3"){
                $stat_surat = '<span class="badge badge-ecommerce badge-info">Disetujui Director</span>';
              }else{
                 $stat_surat = '<span class="badge badge-ecommerce badge-danger">Ditolak</span>';
              } 

              
            

            $kurs = 'Rp ' . number_format($row->kurs, 0, ',', '.');

            $th = array();
            $th[] = ++$start . '.';
            $th[] = $kode;
            $th[] = $row->nama;
            $th[] = $row->pol;
            $th[] = $row->pod;
            $th[] = $kurs;
            $th[] = $row->pengaju;
            $th[] = $stat_surat;
            $th[] = $li_btn;
            
            $data[] = $th;

        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

//=========================================
//=========== DUPLIKAT ====================
//=========================================
public function duplikat($id)
{
    grantAccessFor('all'); // jika pakai ACL, tetap pertahankan

    // Ambil data berdasarkan ID yang mau diduplikat
    $originalApp = $this->md_forwarder->getAppByID($id);
    $originalDetail = $this->md_forwarder->getDetailAppById($id);

    if (!$originalApp) {
        show_404(); // atau redirect dengan pesan error
    }

    // 1. Buat kode baru
    $idFpp      = $this->md_forwarder->getKodeId();
    $ambilId    = $idFpp->id + 1;
    $panjangId  = strlen($ambilId);

    if ($panjangId == 1) {
        $kodeFpp = "00" . $ambilId;
    } else if ($panjangId == 2) {
        $kodeFpp = "0" . $ambilId;
    } else {
        $kodeFpp = $ambilId;
    }

    $bulan = ambil_bulan();
    $tahun = ambil_tahun();
    $kodeFpp = $kodeFpp . "/PF/VYM/" . $bulan . "/" . $tahun;

    // 2. Siapkan data baru approval_forwarder
    $dataBaru = [
        'nama'             => $originalApp[0]->nama,
        'kode'             => $kodeFpp,
        'sistem_pengiriman'=> $originalApp[0]->sistem_pengiriman,
        'pol'              => $originalApp[0]->pol,
        'pod'              => $originalApp[0]->pod,
        'berat_dimensi'    => $originalApp[0]->berat_dimensi,
        'nilai_inv'        => $originalApp[0]->nilai_inv,
        'mata_nilai_inv'   => $originalApp[0]->mata_nilai_inv,
        'kurs'             => $originalApp[0]->kurs,
        'id_pengguna'      => sessPenggunaId(),
        'created_at'       => date('Y-m-d H:i:s'),
    ];

    // 3. Insert ke approval_forwarder dan ambil ID baru
    $idBaru = $this->md_forwarder->insertApprovalForwarder($dataBaru);

    // 4. Duplikat semua detail
    foreach ($originalDetail as $detail) {
        $dataDetail = [
            'id_app'               => $idBaru,
            'id_forwarder'         => $detail->id_forwarder,
            'asal_thc'             => $detail->asal_thc,
            'asal_bl_fee'          => $detail->asal_bl_fee,
            'asal_vgm'             => $detail->asal_vgm,
            'asal_agaency_fee'     => $detail->asal_agaency_fee,
            'asal_handling_fee'    => $detail->asal_handling_fee,
            'asal_transportasi'    => $detail->asal_transportasi,
            'asal_loading'         => $detail->asal_loading,
            'asal_custom'          => $detail->asal_custom,

            'freight_ocean'        => $detail->freight_ocean,
            'freight_air'          => $detail->freight_air,

            'tuj_cfs'              => $detail->tuj_cfs,
            'tuj_doc'              => $detail->tuj_doc,
            'tuj_agency_fee'       => $detail->tuj_agency_fee,
            'tuj_handling'         => $detail->tuj_handling,
            'tuj_do'               => $detail->tuj_do,
            'tuj_admin'            => $detail->tuj_admin,
            'tuj_devanning'        => $detail->tuj_devanning,
            'tuj_fordwarding_fee'  => $detail->tuj_fordwarding_fee,
            'tuj_mechanics'        => $detail->tuj_mechanics,
            'tuj_other'            => $detail->tuj_other,

            'cust_clearance'       => $detail->cust_clearance,
            'cust_red_line'        => $detail->cust_red_line,
            'cust_handling'        => $detail->cust_handling,
            'cust_admin_fee'       => $detail->cust_admin_fee,
            'cust_pib_fee'         => $detail->cust_pib_fee,
            'cust_transfer'        => $detail->cust_transfer,

            'oth_do'               => $detail->oth_do,
            'oth_storage'          => $detail->oth_storage,
            'oth_trucking'         => $detail->oth_trucking,

            'ins_nilai'            => $detail->ins_nilai,
            'ins_jenis'            => $detail->ins_jenis,

            'ppn'                  => $detail->ppn,
            'dipilih'              => $detail->dipilih,
            'alasan'               => $detail->alasan,

            'link_invoice'         => $detail->link_invoice,
            'link_packing'         => $detail->link_packing,
            'link_sph_for'         => $detail->link_sph_for,
            'link_sph_ins'         => $detail->link_sph_ins,
        ];

        $this->md_forwarder->insertDetailApprovalForwarder($dataDetail);
    }

    // Redirect ke halaman detail data baru
    redirect('forwarder/show/detail/' . $idBaru);
}






    public function addForwarder(){
        grantAccessFor('all');

              $this->md_forwarder->reset_increment("approval_forwarder");
              $idFpp      = $this->md_forwarder->getKodeId();
              $ambilId    = $idFpp->id;
              $ambilId    = ($ambilId+1);
              $panjangId  = strlen($ambilId);
              
              if ($panjangId == 1){
                $kodeFpp = "00".$ambilId;
              } else if ($panjangId == 2){
                $kodeFpp = "0".$ambilId;
              } else{
                $kodeFpp = $ambilId;
              }
              
              $bulan = ambil_bulan();
              $tahun = ambil_tahun();
              
              $kodeFpp = $kodeFpp."/PF/VYM/".$bulan."/".$tahun;
              $data['kode']			     = $kodeFpp;
              
              $data['id_pengguna']    = sessPenggunaId();
              $data['nama']           = $this->input->post('nama', TRUE);
              //$tanggal_input = $this->input->post('tanggal', TRUE); // ex: 06-2025
              //$tanggal_db = DateTime::createFromFormat('m-Y', $tanggal_input)->format('Y-m-01');
              //$data['tanggal'] = $tanggal_db; // jadi: 2025-06-01

              $data['sistem_pengiriman'] = $this->input->post('sistem_pengiriman', TRUE);
              $data['pol']               = $this->input->post('pol', TRUE);
              $data['pod']               = $this->input->post('pod', TRUE);
              $data['berat_dimensi']     = $this->input->post('berat_dimensi', TRUE);
              $data['mata_nilai_inv']    = $this->input->post('uang_nilai_inv', TRUE) ?: 'USD';
              $data['nilai_inv']         = $this->input->post('nilai_inv', TRUE);
              //$data['nilai_inv']         = $mata.' '.$nilai;
              $data['kurs']              = $this->input->post('kurs', TRUE);
              $this->md_forwarder->addForwarder($data);



              /** LOG */
              addLog('Approval Forwarder', 'Permintaan Approval Forwarder Kode: '.$kodeFpp);
              ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);  

    }



public function editForwarder($param1)
{
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_forwarder->getByIdForwarder($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
}

public function updateForwarder()
{        
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['nama']              = $this->input->post('nama', TRUE);
        $data['sistem_pengiriman'] = $this->input->post('sistem_pengiriman', TRUE);
        $data['pol']               = $this->input->post('pol', TRUE);
        $data['pod']               = $this->input->post('pod', TRUE);
        $data['berat_dimensi']     = $this->input->post('berat_dimensi', TRUE);
        $data['mata_nilai_inv']    = $this->input->post('uang_nilai_inv', TRUE);
        $data['nilai_inv']         = $this->input->post('nilai_inv', TRUE);
        $data['kurs']              = $this->input->post('kurs', TRUE);

        $this->md_forwarder->updateForwarder($id, $data);

        /** LOG */
        addLog('Approval Forwarder', 'Memperbarui data Forwarder "' . $id . '"');
        ajaxReturnDie('success', 'Data berhasil diperbarui', 'reload_table');
}

public function addDetailForwarder(){
    grantAccessFor('all');

      $data['id_app']        = $this->input->post('id_app', TRUE);
      $data['id_forwarder']  = $this->input->post('id_forwarder', TRUE);
      $data['oth_do']        = $this->input->post('oth_do', TRUE);
      $data['oth_storage']   = $this->input->post('oth_storage', TRUE);
      $data['ins_jenis']     = $this->input->post('ins_jenis1', TRUE) ?: 'NON CLAIM';
      $data['dipilih']       = $this->input->post('dipilih1', TRUE) ?: '2';
      $data['ppn']           = "Harga Belum PPN";
      $data['alasan']        = $this->input->post('alasan', TRUE);
      $data['link_invoice']  = $this->input->post('link_invoice', TRUE);
      $data['link_packing']  = $this->input->post('link_packing', TRUE);
      $data['link_sph_for']  = $this->input->post('link_sph_for', TRUE);
      $data['link_sph_ins']  = $this->input->post('link_sph_ins', TRUE);

      //Input Ongkos Sesuai Mata Uang
      $mata   = $this->md_forwarder->getAppByID($data['id_app']);
      $kurs   = $mata[0]->kurs;


      $uang_asal_thc = $this->input->post('uang_asal_thc1', TRUE) ?: '1';
      $asal_thc = $this->input->post('asal_thc', TRUE);
      $data['asal_thc'] = ($uang_asal_thc == '1') ? $kurs * floatval($asal_thc) : floatval($asal_thc);

      $uang_asal_bl_fee = $this->input->post('uang_asal_bl_fee1', TRUE) ?: '1';
      $asal_bl_fee = $this->input->post('asal_bl_fee', TRUE);
      $data['asal_bl_fee'] = ($uang_asal_bl_fee == '1') ? $kurs * floatval($asal_bl_fee) : floatval($asal_bl_fee);

      $uang_asal_vgm = $this->input->post('uang_asal_vgm1', TRUE) ?: '1';
      $asal_vgm = $this->input->post('asal_vgm', TRUE);
      $data['asal_vgm'] = ($uang_asal_vgm == '1') ? $kurs * floatval($asal_vgm) : floatval($asal_vgm);

      $uang_asal_agaency_fee = $this->input->post('uang_asal_agaency_fee1', TRUE) ?: '1';
      $asal_agaency_fee = $this->input->post('asal_agaency_fee', TRUE);
      $data['asal_agaency_fee'] = ($uang_asal_agaency_fee == '1') ? $kurs * floatval($asal_agaency_fee) : floatval($asal_agaency_fee);

      $uang_asal_handling_fee = $this->input->post('uang_asal_handling_fee1', TRUE) ?: '1';
      $asal_handling_fee = $this->input->post('asal_handling_fee', TRUE);
      $data['asal_handling_fee'] = ($uang_asal_handling_fee == '1') ? $kurs * floatval($asal_handling_fee) : floatval($asal_handling_fee);

      $uang_asal_transportasi = $this->input->post('uang_asal_transportasi1', TRUE) ?: '1';
      $asal_transportasi = $this->input->post('asal_transportasi', TRUE);
      $data['asal_transportasi'] = ($uang_asal_transportasi == '1') ? $kurs * floatval($asal_transportasi) : floatval($asal_transportasi);

      $uang_asal_loading = $this->input->post('uang_asal_loading1', TRUE) ?: '1';
      $asal_loading = $this->input->post('asal_loading', TRUE);
      $data['asal_loading'] = ($uang_asal_loading == '1') ? $kurs * floatval($asal_loading) : floatval($asal_loading);

      $uang_asal_custom = $this->input->post('uang_asal_custom1', TRUE) ?: '1';
      $asal_custom = $this->input->post('asal_custom', TRUE);
      $data['asal_custom'] = ($uang_asal_custom == '1') ? $kurs * floatval($asal_custom) : floatval($asal_custom);

      $uang_freight_ocean = $this->input->post('uang_freight_ocean1', TRUE) ?: '1';
      $freight_ocean = $this->input->post('freight_ocean', TRUE);
      $data['freight_ocean'] = ($uang_freight_ocean == '1') ? $kurs * floatval($freight_ocean) : floatval($freight_ocean);

      $uang_freight_air = $this->input->post('uang_freight_air1', TRUE) ?: '1';
      $freight_air = $this->input->post('freight_air', TRUE);
      $data['freight_air'] = ($uang_freight_air == '1') ? $kurs * floatval($freight_air) : floatval($freight_air);

      $uang_tuj_cfs = $this->input->post('uang_tuj_cfs1', TRUE) ?: '1';
      $tuj_cfs = $this->input->post('tuj_cfs', TRUE);
      $data['tuj_cfs'] = ($uang_tuj_cfs == '1') ? $kurs * floatval($tuj_cfs) : floatval($tuj_cfs);

      $uang_tuj_doc = $this->input->post('uang_tuj_doc1', TRUE) ?: '1';
      $tuj_doc = $this->input->post('tuj_doc', TRUE);
      $data['tuj_doc'] = ($uang_tuj_doc == '1') ? $kurs * floatval($tuj_doc) : floatval($tuj_doc);

      $uang_tuj_agency_fee = $this->input->post('uang_tuj_agency_fee1', TRUE) ?: '1';
      $tuj_agency_fee = $this->input->post('tuj_agency_fee', TRUE);
      $data['tuj_agency_fee'] = ($uang_tuj_agency_fee == '1') ? $kurs * floatval($tuj_agency_fee) : floatval($tuj_agency_fee);

      $uang_tuj_handling = $this->input->post('uang_tuj_handling1', TRUE) ?: '1';
      $tuj_handling = $this->input->post('tuj_handling', TRUE);
      $data['tuj_handling'] = ($uang_tuj_handling == '1') ? $kurs * floatval($tuj_handling) : floatval($tuj_handling);

      $uang_tuj_do = $this->input->post('uang_tuj_do1', TRUE) ?: '1';
      $tuj_do = $this->input->post('tuj_do', TRUE);
      $data['tuj_do'] = ($uang_tuj_do == '1') ? $kurs * floatval($tuj_do) : floatval($tuj_do);

      $uang_tuj_admin = $this->input->post('uang_tuj_admin1', TRUE) ?: '1';
      $tuj_admin = $this->input->post('tuj_admin', TRUE);
      $data['tuj_admin'] = ($uang_tuj_admin == '1') ? $kurs * floatval($tuj_admin) : floatval($tuj_admin);

      $uang_tuj_devanning = $this->input->post('uang_tuj_devanning1', TRUE) ?: '1';
      $tuj_devanning = $this->input->post('tuj_devanning', TRUE);
      $data['tuj_devanning'] = ($uang_tuj_devanning == '1') ? $kurs * floatval($tuj_devanning) : floatval($tuj_devanning);

      $uang_tuj_fordwarding_fee = $this->input->post('uang_tuj_fordwarding_fee1', TRUE) ?: '1';
      $tuj_fordwarding_fee = $this->input->post('tuj_fordwarding_fee', TRUE);
      $data['tuj_fordwarding_fee'] = ($uang_tuj_fordwarding_fee == '1') ? $kurs * floatval($tuj_fordwarding_fee) : floatval($tuj_fordwarding_fee);

      $uang_tuj_mechanics = $this->input->post('uang_tuj_mechanics1', TRUE) ?: '1';
      $tuj_mechanics = $this->input->post('tuj_mechanics', TRUE);
      $data['tuj_mechanics'] = ($uang_tuj_mechanics == '1') ? $kurs * floatval($tuj_mechanics) : floatval($tuj_mechanics);

      $uang_tuj_other = $this->input->post('uang_tuj_other1', TRUE) ?: '1';
      $tuj_other = $this->input->post('tuj_other', TRUE);
      $data['tuj_other'] = ($uang_tuj_other == '1') ? $kurs * floatval($tuj_other) : floatval($tuj_other);

      $uang_cust_clearance = $this->input->post('uang_cust_clearance1', TRUE) ?: '1';
      $cust_clearance = $this->input->post('cust_clearance', TRUE);
      $data['cust_clearance'] = ($uang_cust_clearance == '1') ? $kurs * floatval($cust_clearance) : floatval($cust_clearance);

      $uang_cust_red_line = $this->input->post('uang_cust_red_line1', TRUE) ?: '1';
      $cust_red_line = $this->input->post('cust_red_line', TRUE);
      $data['cust_red_line'] = ($uang_cust_red_line == '1') ? $kurs * floatval($cust_red_line) : floatval($cust_red_line);

      $uang_cust_handling = $this->input->post('uang_cust_handling1', TRUE) ?: '1';
      $cust_handling = $this->input->post('cust_handling', TRUE);
      $data['cust_handling'] = ($uang_cust_handling == '1') ? $kurs * floatval($cust_handling) : floatval($cust_handling);

      $uang_cust_admin_fee = $this->input->post('uang_cust_admin_fee1', TRUE) ?: '1';
      $cust_admin_fee = $this->input->post('cust_admin_fee', TRUE);
      $data['cust_admin_fee'] = ($uang_cust_admin_fee == '1') ? $kurs * floatval($cust_admin_fee) : floatval($cust_admin_fee);

      $uang_cust_pib_fee = $this->input->post('uang_cust_pib_fee1', TRUE) ?: '1';
      $cust_pib_fee = $this->input->post('cust_pib_fee', TRUE);
      $data['cust_pib_fee'] = ($uang_cust_pib_fee == '1') ? $kurs * floatval($cust_pib_fee) : floatval($cust_pib_fee);

      $uang_cust_transfer = $this->input->post('uang_cust_transfer1', TRUE) ?: '1';
      $cust_transfer = $this->input->post('cust_transfer', TRUE);
      $data['cust_transfer'] = ($uang_cust_transfer == '1') ? $kurs * floatval($cust_transfer) : floatval($cust_transfer);

      $uang_oth_trucking = $this->input->post('uang_oth_trucking1', TRUE) ?: '1';
      $oth_trucking = $this->input->post('oth_trucking', TRUE);
      $data['oth_trucking'] = ($uang_oth_trucking == '1') ? $kurs * floatval($oth_trucking) : floatval($oth_trucking);

      $uang_ins_nilai = $this->input->post('uang_ins_nilai1', TRUE) ?: '1';
      $ins_nilai = $this->input->post('ins_nilai', TRUE);
      $data['ins_nilai'] = ($uang_ins_nilai == '1') ? $kurs * floatval($ins_nilai) : floatval($ins_nilai);


    $this->md_forwarder->addDetailForwarder($data);

    /** LOG */
    addLog('Approval Forwarder', 'penambahan Detail Harga Forwarder');

    ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
}


public function editDetailForwarder($param1)
{
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_forwarder->getByIdDetail($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
}


public function UpdateDetailForwarder(){
    grantAccessFor('all');

      $id = decrypt($this->input->post('id_pelanggan'));
      $id_app  = $this->input->post('id_app', TRUE);
      $data['id_forwarder']  = $this->input->post('id_forwarder', TRUE);
      $data['oth_do']        = $this->input->post('oth_do', TRUE);
      $data['oth_storage']   = $this->input->post('oth_storage', TRUE);
      $data['ins_jenis']     = $this->input->post('ins_jenis', TRUE);
      $data['dipilih']       = $this->input->post('dipilih', TRUE);
      $data['alasan']        = $this->input->post('alasan', TRUE);
      $data['link_invoice']  = $this->input->post('link_invoice', TRUE);
      $data['link_packing']  = $this->input->post('link_packing', TRUE);
      $data['link_sph_for']  = $this->input->post('link_sph_for', TRUE);
      $data['link_sph_ins']  = $this->input->post('link_sph_ins', TRUE);

      //Input Ongkos Sesuai Mata Uang
      $mata   = $this->md_forwarder->getAppByID($id_app);
      $kurs   = $mata[0]->kurs;


      $uang_asal_thc = $this->input->post('uang_asal_thc', TRUE) ?: '2';
      $asal_thc = $this->input->post('asal_thc', TRUE);
      $data['asal_thc'] = ($uang_asal_thc == '1') ? $kurs * floatval($asal_thc) : floatval($asal_thc);

      $uang_asal_bl_fee = $this->input->post('uang_asal_bl_fee', TRUE) ?: '2';
      $asal_bl_fee = $this->input->post('asal_bl_fee', TRUE);
      $data['asal_bl_fee'] = ($uang_asal_bl_fee == '1') ? $kurs * floatval($asal_bl_fee) : floatval($asal_bl_fee);

      $uang_asal_vgm = $this->input->post('uang_asal_vgm', TRUE) ?: '2';
      $asal_vgm = $this->input->post('asal_vgm', TRUE);
      $data['asal_vgm'] = ($uang_asal_vgm == '1') ? $kurs * floatval($asal_vgm) : floatval($asal_vgm);

      $uang_asal_agaency_fee = $this->input->post('uang_asal_agaency_fee', TRUE) ?: '2';
      $asal_agaency_fee = $this->input->post('asal_agaency_fee', TRUE);
      $data['asal_agaency_fee'] = ($uang_asal_agaency_fee == '1') ? $kurs * floatval($asal_agaency_fee) : floatval($asal_agaency_fee);

      $uang_asal_handling_fee = $this->input->post('uang_asal_handling_fee', TRUE) ?: '2';
      $asal_handling_fee = $this->input->post('asal_handling_fee', TRUE);
      $data['asal_handling_fee'] = ($uang_asal_handling_fee == '1') ? $kurs * floatval($asal_handling_fee) : floatval($asal_handling_fee);

      $uang_asal_transportasi = $this->input->post('uang_asal_transportasi', TRUE) ?: '2';
      $asal_transportasi = $this->input->post('asal_transportasi', TRUE);
      $data['asal_transportasi'] = ($uang_asal_transportasi == '1') ? $kurs * floatval($asal_transportasi) : floatval($asal_transportasi);

      $uang_asal_loading = $this->input->post('uang_asal_loading', TRUE) ?: '2';
      $asal_loading = $this->input->post('asal_loading', TRUE);
      $data['asal_loading'] = ($uang_asal_loading == '1') ? $kurs * floatval($asal_loading) : floatval($asal_loading);

      $uang_asal_custom = $this->input->post('uang_asal_custom', TRUE) ?: '2';
      $asal_custom = $this->input->post('asal_custom', TRUE);
      $data['asal_custom'] = ($uang_asal_custom == '1') ? $kurs * floatval($asal_custom) : floatval($asal_custom);

      $uang_freight_ocean = $this->input->post('uang_freight_ocean', TRUE) ?: '2';
      $freight_ocean = $this->input->post('freight_ocean', TRUE);
      $data['freight_ocean'] = ($uang_freight_ocean == '1') ? $kurs * floatval($freight_ocean) : floatval($freight_ocean);

      $uang_freight_air = $this->input->post('uang_freight_air', TRUE) ?: '2';
      $freight_air = $this->input->post('freight_air', TRUE);
      $data['freight_air'] = ($uang_freight_air == '1') ? $kurs * floatval($freight_air) : floatval($freight_air);

      $uang_tuj_cfs = $this->input->post('uang_tuj_cfs', TRUE) ?: '2';
      $tuj_cfs = $this->input->post('tuj_cfs', TRUE);
      $data['tuj_cfs'] = ($uang_tuj_cfs == '1') ? $kurs * floatval($tuj_cfs) : floatval($tuj_cfs);

      $uang_tuj_doc = $this->input->post('uang_tuj_doc', TRUE) ?: '2';
      $tuj_doc = $this->input->post('tuj_doc', TRUE);
      $data['tuj_doc'] = ($uang_tuj_doc == '1') ? $kurs * floatval($tuj_doc) : floatval($tuj_doc);

      $uang_tuj_agency_fee = $this->input->post('uang_tuj_agency_fee', TRUE) ?: '2';
      $tuj_agency_fee = $this->input->post('tuj_agency_fee', TRUE);
      $data['tuj_agency_fee'] = ($uang_tuj_agency_fee == '1') ? $kurs * floatval($tuj_agency_fee) : floatval($tuj_agency_fee);

      $uang_tuj_handling = $this->input->post('uang_tuj_handling', TRUE) ?: '2';
      $tuj_handling = $this->input->post('tuj_handling', TRUE);
      $data['tuj_handling'] = ($uang_tuj_handling == '1') ? $kurs * floatval($tuj_handling) : floatval($tuj_handling);

      $uang_tuj_do = $this->input->post('uang_tuj_do', TRUE) ?: '2';
      $tuj_do = $this->input->post('tuj_do', TRUE);
      $data['tuj_do'] = ($uang_tuj_do == '1') ? $kurs * floatval($tuj_do) : floatval($tuj_do);

      $uang_tuj_admin = $this->input->post('uang_tuj_admin', TRUE) ?: '2';
      $tuj_admin = $this->input->post('tuj_admin', TRUE);
      $data['tuj_admin'] = ($uang_tuj_admin == '1') ? $kurs * floatval($tuj_admin) : floatval($tuj_admin);

      $uang_tuj_devanning = $this->input->post('uang_tuj_devanning', TRUE) ?: '2';
      $tuj_devanning = $this->input->post('tuj_devanning', TRUE);
      $data['tuj_devanning'] = ($uang_tuj_devanning == '1') ? $kurs * floatval($tuj_devanning) : floatval($tuj_devanning);

      $uang_tuj_fordwarding_fee = $this->input->post('uang_tuj_fordwarding_fee', TRUE) ?: '2';
      $tuj_fordwarding_fee = $this->input->post('tuj_fordwarding_fee', TRUE);
      $data['tuj_fordwarding_fee'] = ($uang_tuj_fordwarding_fee == '1') ? $kurs * floatval($tuj_fordwarding_fee) : floatval($tuj_fordwarding_fee);

      $uang_tuj_mechanics = $this->input->post('uang_tuj_mechanics', TRUE) ?: '2';
      $tuj_mechanics = $this->input->post('tuj_mechanics', TRUE);
      $data['tuj_mechanics'] = ($uang_tuj_mechanics == '1') ? $kurs * floatval($tuj_mechanics) : floatval($tuj_mechanics);

      $uang_tuj_other = $this->input->post('uang_tuj_other', TRUE) ?: '2';
      $tuj_other = $this->input->post('tuj_other', TRUE);
      $data['tuj_other'] = ($uang_tuj_other == '1') ? $kurs * floatval($tuj_other) : floatval($tuj_other);

      $uang_cust_clearance = $this->input->post('uang_cust_clearance', TRUE) ?: '2';
      $cust_clearance = $this->input->post('cust_clearance', TRUE);
      $data['cust_clearance'] = ($uang_cust_clearance == '1') ? $kurs * floatval($cust_clearance) : floatval($cust_clearance);

      $uang_cust_red_line = $this->input->post('uang_cust_red_line', TRUE) ?: '2';
      $cust_red_line = $this->input->post('cust_red_line', TRUE);
      $data['cust_red_line'] = ($uang_cust_red_line == '1') ? $kurs * floatval($cust_red_line) : floatval($cust_red_line);

      $uang_cust_handling = $this->input->post('uang_cust_handling', TRUE) ?: '2';
      $cust_handling = $this->input->post('cust_handling', TRUE);
      $data['cust_handling'] = ($uang_cust_handling == '1') ? $kurs * floatval($cust_handling) : floatval($cust_handling);

      $uang_cust_admin_fee = $this->input->post('uang_cust_admin_fee', TRUE) ?: '2';
      $cust_admin_fee = $this->input->post('cust_admin_fee', TRUE);
      $data['cust_admin_fee'] = ($uang_cust_admin_fee == '1') ? $kurs * floatval($cust_admin_fee) : floatval($cust_admin_fee);

      $uang_cust_pib_fee = $this->input->post('uang_cust_pib_fee', TRUE) ?: '2';
      $cust_pib_fee = $this->input->post('cust_pib_fee', TRUE);
      $data['cust_pib_fee'] = ($uang_cust_pib_fee == '1') ? $kurs * floatval($cust_pib_fee) : floatval($cust_pib_fee);

      $uang_cust_transfer = $this->input->post('uang_cust_transfer', TRUE) ?: '2';
      $cust_transfer = $this->input->post('cust_transfer', TRUE);
      $data['cust_transfer'] = ($uang_cust_transfer == '1') ? $kurs * floatval($cust_transfer) : floatval($cust_transfer);

      $uang_oth_trucking = $this->input->post('uang_oth_trucking', TRUE) ?: '2';
      $oth_trucking = $this->input->post('oth_trucking', TRUE);
      $data['oth_trucking'] = ($uang_oth_trucking == '1') ? $kurs * floatval($oth_trucking) : floatval($oth_trucking);

      $uang_ins_nilai = $this->input->post('uang_ins_nilai', TRUE) ?: '2';
      $ins_nilai = $this->input->post('ins_nilai', TRUE);
      $data['ins_nilai'] = ($uang_ins_nilai == '1') ? $kurs * floatval($ins_nilai) : floatval($ins_nilai);

    $this->md_forwarder->updateDetailForwarder($id, $data);

    /** LOG */
    addLog('Approval Forwarder', 'Edit Harga Forwarder');

    ajaxReturnDie('success', 'Berhasil Diajukan', TRUE);
}

    public function deleteDetail($id)
    {
        grantAccessFor('all');

        // Langsung hapus data
        $this->md_forwarder->deleteDetail($id);


        addLog('Approval Forwarder', 'Menghapus Detail Forwarder');
        ajaxReturnDie('success', 'berhasil dihapus', TRUE);
    }


public function ajukanApp(){
    	grantAccessFor('all');
        
                
			$id_sp = $this->input->post('id');
      $data['status'] = 1;
      $this->md_forwarder->updateForwarder($id_sp, $data);

      $dataApp 	= $this->md_forwarder->getAppByID($id_sp);
      $kodeApp     = $dataApp[0]->kode;

      //APPROVAL NOTIFIKASI
      $urlNotif = "https://office.visiyosindo.id/forwarder/show/detail_data/$id_sp"; 
                  

                    //send notif wa
                    $dataWa = [
                            'idPenerima1' 	=> 23,
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => 'Approval Forwarder',
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => 'Meilina Safitri',
                            'perihal' 	    => "",
                            'kode' 	        => $kodeApp 
                          ];
                        
                    $this->notifWaAddSuratLink(1, $dataWa);
      

               

    addLog('Approval Forwarder', 'Permintaan Diajukan Kode '.$kodeApp);
    ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);

}


public function notifWaAddSuratLink($ulang, $detail){
        //ambil data pengaju
      $ambilDataPengaju 	= $this->md_pengguna->getById(sessPenggunaId());
      $namaPengaju		= $ambilDataPengaju[0]->nama;

      for($i=1; $i<=$ulang; $i++){
        if($i == 1){
            $idpenerima = $detail['idPenerima1'];
        }else if($i == 2){
            $idpenerima = $detail['idPenerima2'];
        }
        
        $dataPenerima 	= $this->md_pengguna->getById($idpenerima);
        //abaikan error
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 0);
      //
        $nope           = $dataPenerima[0]->no_hp;
        $dataWa = [
            'namaSurat' 	=> urlencode($detail['namaSurat']),
            'urlNotif' 	  => urlencode($detail['urlNotif']),
            'noPenerima' 	=> $nope,
            'kodeSurat' 	=> $detail['kode'],
            'namaPengaju' => $namaPengaju,
            'perihal' 		=> urlencode($detail['perihal']),
            'namaPenerima' 	=> urlencode($detail['penerima'])
            ];
            waSuratOpenLink($dataWa);
      }
}


public function ttd_setujui($param1="", $param2="", $param3=""){
    	grantAccessFor('all');

      if($param1 == "ttd_1"){
                      
          $id_sp = $this->input->post('id');
          $data['status'] = 2;
          $data['ttd_1'] = 1;
          $this->md_forwarder->updateForwarder($id_sp, $data);

          //$dataApp 	= $this->md_forwarder->getAppByID($id_sp);
          //$kodeApp     = $dataApp[0]->kode;

                   $urlNotif = "https://office.visiyosindo.id/forwarder/show/detail_data/$id_sp";  
                    
                    
                 

                    $dataWa = [
                            'id' 	          => $id_sp,
                            'idPenerima1' 	=> '54',
                            'idPenerima2' 	=> '',
                            'namaSurat' 	  => 'Approval Forwarder',
                            'urlNotif' 	    => $urlNotif,
                            'penerima' 	    => 'Director',
                            'ttd_sebelum1' 	=> 'Meilina Safitri',
                            'ttd_sebelum2' 	=> '',
                            'ttd_sebelum3' 	=> '',
                            'ttd_sebelum4' 	=> ''
                        ];
                    
                    $this->notifWaAprovBa(1, 1, $dataWa);
      
               addLog('Approval Forwarder', 'Permintaan Approval Forwarder Disetujui');
               ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      
             }else if($param1 == "ttd_2"){

                $id_sp = $this->input->post('id');
                $data['status'] = 3;
                $data['ttd_2'] = 1;
                $this->md_forwarder->updateForwarder($id_sp, $data);

                        $urlNotif = "https://office.visiyosindo.id/forwarder/show/detail/$id_sp";  
                          
                          
                      

                          $dataWa = [
                                  'id' 	          => $id_sp,
                                  'idPenerima1' 	=> '',
                                  'idPenerima2' 	=> '',
                                  'namaSurat' 	  => 'Approval Forwarder',
                                  'urlNotif' 	    => $urlNotif,
                                  'penerima' 	    => '',
                                  'ttd_sebelum1' 	=> 'Meilina Safitri',
                                  'ttd_sebelum2' 	=> 'Bob Ariyos',
                                  'ttd_sebelum3' 	=> '',
                                  'ttd_sebelum4' 	=> ''
                              ];
                          
                          $this->notifWaAprovBa(1, 2, $dataWa);
      
               addLog('Approval Forwarder', 'Permintaan Approval Forwarder Disetujui');
               ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
      
        }

}



public function notifWaAprovBa($ulang, $param, $detail){
      //ambil data pengaju
      $ambilDataPengaju 	= $this->md_forwarder->getAppByID($detail['id']);
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $perihal            = $ambilDataPengaju[0]->nama;
      $idpengaju          = $ambilDataPengaju[0]->id_pengguna;

      
      //send notif wa
      for($i=1; $i<=$ulang; $i++){
          if($i == 1){
              //id pengaju surat
              if($param == 2){
                  $idpenerima = $idpengaju;
              }else{
                  $idpenerima = $detail['idPenerima1'];
              }
          }else if($i == 2){
              //id ???
              $idpenerima = $detail['idPenerima2'];
          }
            
          $dataPenerima 	= $this->md_pengguna->getById($idpenerima);
          $nope           = $dataPenerima[0]->no_hp;
          $dataWa = [
              'namaSurat' 	  => urlencode($detail['namaSurat']),
              'urlNotif' 	    => urlencode($detail['urlNotif']),
              'noPenerima'  	=> $nope,
              'kodeSurat' 	  => $kode,
              'namaPengaju' 	=> urlencode($namaPengaju),
              'namaPenerima' 	=> urlencode($detail['penerima']),
              'perihal' 		  => urlencode($perihal),
              'ttd_sebelum1' 	=> urlencode($detail['ttd_sebelum1']),
              'ttd_sebelum2' 	=> urlencode($detail['ttd_sebelum2']),
              'ttd_sebelum3' 	=> urlencode($detail['ttd_sebelum3']),
              'ttd_sebelum4' 	=> urlencode($detail['ttd_sebelum4'])
          ];
          
          if($param == '1'){
            waSuratAprovOnProgVisilab($dataWa);
          }else if($param == '2'){
              waSuratAprovAllVisilab($dataWa);
          }
      }
    }


public function ttd_tolak($param1="", $param2="", $param3=""){
    	grantAccessFor('all');

          if($param1 == "ttd_1"){
                          
                $id_sp = $this->input->post('id');
                $data['status'] = 4;
                $data['ttd_1'] = 2;
                $this->md_forwarder->updateForwarder($id_sp, $data);
                          
    
                      //send notif wa
                        $dataWa = [
                            'namaSurat'     => 'Approval Forwarder',
                                  'id' 	    => $id_sp,
                            'idPenolak'     => '23',
                            'namaPenolak' 	=> 'Meilina Safitri'
                              ];
                              $this->notifWaRejectBa($dataWa);
                  /** LOG */
                  addLog('Approval Forwarder', 'Permintaan Approval Forwarder Ditolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    
          }else if($param1 == "ttd_2"){
                          
                $id_sp = $this->input->post('id');
                $data['status'] = 5;
                $data['ttd_2'] = 2;
                $this->md_forwarder->updateForwarder($id_sp, $data);
                          

    
                      //send notif wa
                        $dataWa = [
                            'namaSurat'     => 'Approval Forwarder',
                                  'id' 	    => $id_sp,
                            'idPenolak'     => '54',
                            'namaPenolak' 	=> 'Bob Ariyos'
                              ];
                              $this->notifWaRejectBa($dataWa);
                  /** LOG */
                  addLog('Approval Forwarder', 'Permintaan Approval Forwarder Ditolak');
                  ajaxReturnDie('success', 'Data Berhasil Ditambahkan', TRUE);
    
          }
    
}  


public function notifWaRejectBa($detail){
  
      $ambilDataPengaju 	= $this->md_forwarder->getAppByID($detail['id']);
      $namaPengaju		    = $ambilDataPengaju[0]->pengaju;
      $kode               = $ambilDataPengaju[0]->kode;
      $perihal            = $ambilDataPengaju[0]->nama;
      $idpengaju          = $ambilDataPengaju[0]->id_pengguna;
      
      //ambil nomor
      $dataPenerima 	= $this->md_pengguna->getById($idpengaju);
      $nope           = $dataPenerima[0]->no_hp;
      $dataPenolak 	  = $this->md_pengguna->getById($detail['idPenolak']);
      $nopePenolak    = $dataPenolak[0]->no_hp;

      $perihal = '';
                  
      //send notif wa
      $dataWa = [
          'namaSurat' 	=> $detail['namaSurat'],
          'noPenerima' 	=> $nope,
          'kodeSurat' 	=> $kode,
          'perihal' 		  => urlencode($perihal),
          'namaPengaju' => $namaPengaju,
          'namaPenolak' => $detail['namaPenolak'],
          'noPenolak' 	=> $nopePenolak
      ];
      waSuratReject($dataWa);
    }

 public function print_page($param1="", $param2="")
 {
        grantAccessFor('all');

       if ($param1 == 'detail') {

          $dataApp = $this->md_forwarder->getAppByID($param2);
          $detailApp = $this->md_forwarder->getDetailAppById($param2);

          $dt = [
              'title_pdf'       => 'ba',
              'object'          => $param1,
              'data_aprv'      => $this->md_forwarder->getAppByID($param2),
              'detail_aprv'    => $this->md_forwarder->getDetailAppById($param2),
              'forwarderNames'  => array_unique(array_map(function($item) {
                  return $item->nama_forwarder;
              }, $detailApp))
          ];

          //load mpdf dan membuat page size 
            $mpdf = new Mpdf(['format' => 'A4']);

          // $mpdf->SetMargins(0, 0, 0, true);

            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
            $mpdf->AddPage('P', '', '', '', '', '5', '5', '4', '1');
              
            // filename dari pdf ketika didownload
            $file_pdf = 'Approval Forwarder '.$dataApp[0]->pengaju;

            // page htmk yang akan di jadikan ke pdf
            $html = $this->load->view('pages/v_print/print_appfor', $dt, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');
            
    }



                  
}



    

    

    


}
