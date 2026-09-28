<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Pelangganan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pelangganan');
        $this->load->helper('encrypt_helper');
        
    }
	
	function id_navbar(){
		$id_navbar = "marketing";
		return $id_navbar;
	}

    public function index()
    {
        grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
		$page_data['page_name']     = 'marketing/v_pelangganan';
        $page_data['kategorimodality']  = $this->md_pelangganan->getMasterKategoriModality();
        $page_data['jenisfakturpajak']  = $this->md_pelangganan->getMasterJenisFakturPajak();
        $page_data['syaratpembayaran']  = $this->md_pelangganan->getMasterSyaratPembayaran();
        //$page_data['modality']  = $this->md_pelangganan->getAllModalityPelangganan();
        $page_data['page_title']    = 'Pelanggan';
        $page_data['page_desc']     = 'Management Data Pelanggan';
        $this->load->view('index', $page_data);
    }

    public function addmodality()
    {
        
        //ajaxReturnDie('error', encrypt(0));
        grantAccessFor('all');

        $data['id_pelanggan']= decrypt($this->input->post('idcalonpelanggan'));
        $data['kodepelanggan']= $this->input->post('kodepelanggan');
        $data['kategori']= $this->input->post('kategori');
        $data['merk']= $this->input->post('merk');
        $data['nama']= $this->input->post('nama');

        $this->md_pelangganan->addModality($data);

        // /** LOG */
        addLog('Penambahan Pelanggan', 'Menambah Modality Pelanggan "' . $data['nama'] . '"');
        ajaxReturnDie('success', 'Modality Pelanggan berhasil ditambahkan', 'reload_table');
    }
     public function addpic()
    {
        
        //ajaxReturnDie('error', encrypt(0));
        grantAccessFor('all');

        $data['idpic']= decrypt($this->input->post('idpic'));
        $data['namapic']= $this->input->post('namapic');
        $data['jabatanpic']= $this->input->post('jabatanpic');
        $data['teleponpic']= $this->input->post('teleponpic');

        $this->md_pelangganan->addpic($data);

        // /** LOG */
        addLog('Penambahan PIC Pelanggan', 'Menambah PIC Pelanggan "' . $data['namapic'] . '"');
        ajaxReturnDie('success', 'PIC Pelanggan berhasil ditambahkan', 'reload_table');
    }

	 public function edit($param1)
    {      
            grantAccessFor('all');
            $id = decrypt($param1);
            $dt = $this->md_pelangganan->getAllPelangganById($id);

            if(isset($dt[0]))
                echo json_encode($dt[0]);
            else
                ajaxReturnDie('error', 'error');
             die;
  
    }

    public function update()
    {        
       
         grantAccessFor('all');
        
             $id =  $this->input->post('id');
             $data['kodecustomer'] = $this->input->post('kodecustomer');
             $data['namapelanggan']  = $this->input->post('namapelanggan');
             $data['nik'] = $this->input->post('nik');
             $data['nonpwp']	= $this->input->post('nonpwp');
             $data['namanpwp']	= $this->input->post('namanpwp');
             $data['jenisfakturpajak']	= $this->input->post('jenisfakturpajak');
             $data['syaratpembayaran'] = $this->input->post('syaratpembayaran');
             $data['pengirimandokumen']	= $this->input->post('pengirimandokumen');
             $data['statuspiutang'] = $this->input->post('statuspiutang');
             $data['limitpiutang'] = $this->input->post('limitpiutang');
             $data['alamatpengiriman'] = $this->input->post('alamatpengiriman');
             $data['alamatpenagihan'] = $this->input->post('alamatpenagihan');
             $data['keterangan'] = $this->input->post('keterangan');   
             $data['pengguna_id'] = sessPenggunaId();   

        $this->md_pelangganan->update($id, $data);

        /** LOG */
        addLog('Update Pelanggan', 'Memperbarui data Pelanggan "' . $data['namapelanggan'] . '"');
        ajaxReturnDie('success', 'Data pelanggan '. $data['namapelanggan'].' berhasil diperbarui', 'reload_table');
	}
	
     public function delete($id)
    {
        grantAccessFor('all');
        $data = explode(",", decryptvym($id));
        
       $this->md_pelangganan->hapus($data[0],sessPenggunaId());
        
        addLog('Menghapus Pelanggan', 'Menghapus Pelanggan '. $data[1] );
        ajaxReturnDie('success', 'Pelanggan '. $data[1] . ' Berhasil Dihapus'  , 'reload_table');
			
		
    }

     public function deletemodality($par)
    {
        grantAccessFor('all');
        $data = explode(",", decryptvym($par));
        
        $this->md_pelangganan->hapusmodality($data[0]);
        
        addLog('Menghapus Modality', 'Menghapus Modality '. $data[2] );
        ajaxReturnDie('success', 'Modality '. $data[2] . ' Berhasil Dihapus'  , 'reload_table');
			
		
    }
    public function showdetail($id){  
            
            $page_data['switch'] = $this->id_navbar();
            $page_data['id_pengguna'] = sessPenggunaId();
            $dt    =  $this->md_pelangganan->getAllPelangganById(decrypt($id));
            $page_data['datacaloncustomer'] =$dt[0];
            $page_data['pic']              = $this->md_pelangganan->getAllPICPelangganById(decrypt($id));
            $page_data['modality']              = $this->md_pelangganan->getAllModalityPelangganById(decrypt($id));
            $page_data['berkasdokumenpembayaran'] = $this->md_pelangganan->getAllBerkasDokumenPembayaranById(decrypt($id));
            $page_data['berkaslain'] = $this->md_pelangganan->getAllBerkasDokumenLainById(decrypt($id));
            $page_data['page_name']       = 'marketing/v_pelangganandetail';
            $page_data['page_title']      = 'Detail Pelanggan';
            $page_data['page_desc']       = 'Data Pelanggan';
            $this->load->view('index', $page_data);
    }

    public function pagination()
    {
        grantAccessFor('all');
         $pengguna_id = sessPenggunaId();
        if (isAdmin() || isHrd() || isCRO()) {
            $dt    = $this->md_pelangganan->getAllPelangganan($pengguna_id);
        }else{
             $dt    = $this->md_pelangganan->getAllPelanggananByPenggunaID($pengguna_id);
        }
       
        $start = $this->input->post('start');
        $data  = array();
        $index = 1;
        foreach ($dt['data'] as $row) {
           // $status 	= $row->status == 1 ? '<span class="badge badge-ecommerce badge-success">Aktif</span>' : '<span class="badge badge-ecommerce badge-danger">Tidak Aktif</span>';
            $id       	= encrypt($row->id);
            $myObj = $row->id.",".$row->namapelanggan;
            $parJSON = encryptvym($myObj);
            $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="'. $id .'"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'.$parJSON.'" data-object="pelangganan/delete/'.$parJSON.'"><i class="bx bx-trash"></i></button>

                </div>';
            $ModalityBtn 	 = '<div class="btn-group" role="group" aria-label="First group"> 
                                    <a href="javascript:;" id="btnmodality'. $id .'" class="btn btn-sm btn-dark" "><i class="icons icon-plus">Modality</i></a>
                                </div>';
            $th = array();
            // $th[] = $row->id_pelanggan;
            // $th[] = $row->nama;
            $kodecustomer	= '<a href="pelangganan/showdetail/'.encrypt($row->id).'">'.$row->kodecustomer.'</a>';    
            $th[] = $index;
            $th[] = encrypt($row->id);
            $th[] = encrypt($row->idcalonpelanggan);
            $th[] = $row->kodecustomer;
            $th[] = $kodecustomer;
			$th[] = $row->namapelanggan;
			$th[] = $row->nik;
			$th[] = $row->nonpwp;
			$th[] = $row->namanpwp;
            $th[] = $row->keterangan;
            $th[] = '<i class="fa fa-clock-o"></i> '.date('Y-m-d',strtotime($row->tanggalregistrasi));
            if (isAdmin() || isHrd() || isTeamMarketing() || isCRO()){
                 $th[] =$ModalityBtn;
                  $th[] = $li_btn;
            }
           
            $data[] = $th;
            $index++;
        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

   public function paginationmodality($id_pelanggan)
    {
        grantAccessFor('all');
       //$id = decrypt($id_pelanggan);
        if($id_pelanggan!=null){
            $dt    = $this->md_pelangganan->getAllModalityPelangganan($id_pelanggan);
            $data  = array();
            $index = 1;
            foreach ($dt['data'] as $row) {
                $th = array();
                $id       	= encrypt($row->id);
                $myObj = $row->id.",".$row->idkategori.",".$row->nama;
                $parJSON = encryptvym($myObj);
                $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-danger btn-deletemodality" title="Hapus Data" data-id="'.$parJSON.'" data-object="pelangganan/delete/'.$parJSON.'"><i class="bx bx-trash"></i></button>
                </div>';
                $th[] = $index;
                $th[] = $row->kategori;
                $th[] = $row->nama;
                $th[] = $row->merk;
                $th[] = '<i class="fa fa-clock-o"></i> '.date('Y-m-d',strtotime($row->data_created));
                $th[] = $li_btn;
                $data[] = $th;
                $index++;
            }
            
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        }else{
            $dt['data'] = '';
            echo json_encode($dt);
            die;
        }
    }
}
