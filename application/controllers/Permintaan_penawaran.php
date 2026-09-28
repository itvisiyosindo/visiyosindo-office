<?php
defined('BASEPATH') or exit('No direct script access allowed');

class permintaan_penawaran extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_surat_list');
        $this->load->model('md_prov_kota');
        $this->load->model('md_permintaan_penawaran');

    }

    function id_navbar()
    {
        $id_navbar = "inventory";
        return $id_navbar;
    }

    public function index()
    {
        grantAccessFor(['Administrator', 'Hrd']);
        $page_data['pengguna'] = $this->md_pengguna->getById(sessPenggunaId());
        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'v_permintaan_penawaran';
        $page_data['page_title'] = 'Data Permintaan Penawaran';
        $page_data['page_desc'] = 'Penawaran Permintaan';
        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');
		
			$this->md_surat_list->reset_increment("surat_pp");
			$idpp = $this->md_surat_list->getPbLastId();
			$ambilId = $idpp->id_pp;
			$ambilId = $ambilId+1;
			$panjangId = strlen($ambilId);
			
			if ($panjangId == 1){
				$kodePp = "00".$ambilId;
			} else if ($panjangId == 2){
				$kodePp = "0".$ambilId;
			} else{
				$kodePp = $ambilId;
			}
			
			$bulan = ambil_bulan();
			$tahun = ambil_tahun();
			
			$kodePp = $kodePp."/PP/FAT/VYM/".$bulan."/".$tahun;
			$data['kode_pb']			= $kodePp;
            $data['id_kat_surat']       = 1;
            $data['id_pengguna']      	= sessPenggunaId();
            $data['kota']          		= $this->input->post('kota', TRUE);
			$data['kota_pengajuan']		= $this->input->post('kota_aju', TRUE);
            $data['keperluan']         	= $this->input->post('perihal', TRUE);
            $data['lampiran_pengajuan'] = $this->input->post('lampiran', TRUE);
            $data['tgl_pengajuan']		= date_db_format($this->input->post('pengajuan', TRUE));
            $data['tgl_pergi']         	= date_db_format($this->input->post('pergi', TRUE));
            $data['tgl_kembali']    	= date_db_format($this->input->post('pulang', TRUE));
			$this->md_surat_list->addSurat('pb', $data);
			
			//menambah pengajuan pembiayaan (pb) baru ke surat-list
			$lastPbId = $this->md_surat_list->getPbLastId();
			$lastPbId = $lastPbId->id_pb;
			$dataList['id_srt']			= $lastPbId;
			$dataList['id_kat_surat']	= 1;
			$this->md_surat_list->reset_increment("surat_list");
			$this->md_surat_list->addSurat('list', $dataList);
			
			//menambah detail pengajuan pembiayaan (Dpb)
			$this->md_surat_list->reset_increment("surat_detail_biaya_dinas");
			$itung = $this->input->post('itung', TRUE);
			$dataDetailPB['id_pb']			= $lastPbId;
			$dataDetailPB['kode_laporan']	= 1;
			if($itung > 0){
				for($x=1;$x<$itung;$x++){
					$dataDetailPB['keterangan']	= $this->input->post('keterangan['.$x.']', TRUE);
					$dataDetailPB['tanggal']	= date_db_format($this->input->post('tanggal['.$x.']', TRUE));
					$dataDetailPB['nominal']	= $this->input->post('nominal['.$x.']', TRUE);
					$this->md_surat_list->addSurat('dpb', $dataDetailPB);
				}
			}
			
			//send notif wa
			// $dataWa = [
            //     'idPenerima1' 	=> 96,
		
            //     'idPenerima2' 	=> 96,
            // 	'namaSurat' 	=> 'Surat Biaya Perjalanan Dinas',
            // 	'penerima' 	    => '_Staff Finance_',
            // 	'perihal' 	    => $data['keperluan'],
            // 	'kode' 	        => $kodePp
            // ];
					
			// $this->notifWaAddSurat(2, $dataWa);
			
			/** LOG */
            addLog('Pengajuan Pembiayaan Dinas', 'Pembiayaan Dinas Diajukan Dengan Kode '.$kodePp);
            ajaxReturnDie('success', 'Pembiayaan Dinas Berhasil Diajukan');
    }
    public function delete($id)
    {
        $this->md_umk_kota->delete($id);
        ajaxReturnDie('success', 'Tunjangan berhasil dihapus', 'reload_table');
    }
    public function pagination()
    {
        grantAccessFor(['Administrator', 'Hrd']);

        $dt = $this->md_umk_kota->getAllUmk();

        $start = $this->input->post('start');
        $data = array();
        foreach ($dt['data'] as $row) {
            $id = $row->id;
            $li_btn = '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="' . $id . '" data-object="tunjangan/delete"><i class="bx bx-trash"></i></button>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $row->kota ? $row->kota : '-';
            $th[] = isset($row->jumlah) ? rupiah($row->jumlah) : '';
            if (isAdmin()) {
                $th[] = $li_btn;
            }
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }
}