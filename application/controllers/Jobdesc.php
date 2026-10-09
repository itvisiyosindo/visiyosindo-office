<?php
//abaikan error
			error_reporting(E_ALL & ~E_NOTICE);
			ini_set('display_errors', 0);
			//
use FontLib\Table\Type\post;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Jobdesc extends CI_Controller
{

  function id_navbar(){
		$id_navbar = "kepegawaian";
		return $id_navbar;
	}

    public function index()
    {
       grantAccessFor('all');

        $page_data['switch']      	= $this->id_navbar();
		    $page_data['page_name']     = 'jobdesc/v_job';
        $page_data['page_title']    = 'Data Jobdesk';
        $page_data['page_desc']     = 'Management Jobdesk';
        $this->load->view('index', $page_data);


    }


    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_kategori_tiket');
        $this->load->model('md_tiket');
        $this->load->model('md_fpp');
        $this->load->model('md_jobdesc');
        $this->load->model('md_surat_part_two');
        $this->load->model('md_surat_list');
        $this->load->model('md_prov_kota');
        $this->load->helper('email_helper');
        $this->load->helper('terbilang_helper');
        $this->load->helper('tanggal_helper');
        $this->load->helper('whatsapp_helper');
        $this->load->helper('encrypt_helper');
        
    }

    public function show($param = "", $param2 = "", $param3 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail') {  
          if ($param2 == 'jobdesc') {
            $id_jobdesc               = decrypt($param3);
            $page_data['switch']      = $this->id_navbar();
            $page_data['data_job']    = $this->md_jobdesc->getJobById($id_jobdesc);
            $Detail                   = $page_data['data_job'];
            $idPengguna               = $Detail ? $Detail->id_pengguna : null;
            // Kirim $id_jobdesc dan $idPengguna
            $page_data['data_detail'] = $this->md_jobdesc->getDetailPOById($id_jobdesc, $idPengguna);
            $page_data['next_urut']   = $this->md_jobdesc->getNextUrutByJobdesc($id_jobdesc, $idPengguna);
            $page_data['page_name']   = 'jobdesc/v_job_detail';
            $page_data['page_title']  = 'Jobdesk';
            $page_data['page_desc']   = 'Detail Jobdesk';
            $this->load->view('index', $page_data);
          }
        }else if($param == 'list'){
          if($param2 == 'jobdesc'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['page_name']     = 'jobdesc/v_job';
            $page_data['page_title']    = 'Jobdesk';
            $page_data['page_desc']     = 'Daftar Jobdesk';
            $this->load->view('index', $page_data);
          }else if($param2 == 'my_data'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['page_name']     = 'jobdesc/v_my_job';
            $page_data['page_title']    = 'Jobdesk';
            $page_data['page_desc']     = 'Daftar Jobdesk';
            $this->load->view('index', $page_data);
          }
			  }else if($param == 'pengajuan'){
          if ($param2 == 'jobdesc'){
            $page_data['switch']      	= $this->id_navbar();
            $page_data['pengguna']      = $this->md_pengguna->getById(sessPenggunaId());
            $page_data['list_kota']     = $this->md_prov_kota->getAllKota();
            $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
            $page_data['page_name']     = 'jobdesc/v_aju_job';
            $page_data['page_title']    = 'Jobdesk';
            $page_data['page_desc']     = 'Form Jobdesk';
            $this->load->view('index', $page_data);
          }
			  }else if($param == 'edit'){
          if ($param2 == 'jobdesc'){
            $id_jobdesc                 = decrypt($param3);
            $page_data['switch']        = $this->id_navbar();
            $page_data['data_job']      = $this->md_jobdesc->getJobById($id_jobdesc);
            $Detail                     = $page_data['data_job'];
            $idPengguna                 = $Detail ? $Detail->id_pengguna : null;
            $page_data['data_detail']   = $this->md_jobdesc->getDetailPOById($id_jobdesc, $idPengguna);
            $page_data['list_nama']     = $this->md_surat_part_two->getBywhereActive();
            $page_data['id_jobdesc_enc'] = $param3;
            $page_data['page_name']     = 'jobdesc/v_edit_job';
            $page_data['page_title']    = 'Jobdesk';
            $page_data['page_desc']     = 'Edit Data Jobdesk';
            $this->load->view('index', $page_data);
          }
			  }
          


    }


    //ADD
    public function add()
    {
        grantAccessFor('all');

        $id_pengguna = $this->input->post('id_pengguna', TRUE);
        $tgl_mulai   = $this->input->post('tgl_mulai', TRUE);
        $tgl_selesai = $this->input->post('tgl_selesai', TRUE);

        if (empty($id_pengguna)) {
            ajaxReturnDie('error', 'Silakan pilih Nama Karyawan terlebih dahulu.', false);
        }

        if (empty($tgl_mulai)) {
            $tgl_mulai = date('Y-m-d');
        }
        if (empty($tgl_selesai)) {
            $tgl_selesai = '2099-12-31';
        }

        // Jika ada jobdesk lama yang tgl_selesai-nya melebihi tgl_mulai baru, update tgl_selesai jobdesk lama
        $prev_date = date('Y-m-d', strtotime($tgl_mulai . ' -1 day'));
        $this->db->where('idPengguna', $id_pengguna)
                 ->where('tgl_selesai >=', $tgl_mulai)
                 ->update('jobdesc_detail', ['tgl_selesai' => $prev_date]);

        $this->db->where('id_pengguna', $id_pengguna)
                 ->where('tgl_selesai >=', $tgl_mulai)
                 ->update('jobdesc', ['tgl_selesai' => $prev_date]);

        // 1. Tambah Header Jobdesk
        $data['id_pengaju']   = sessPenggunaId();
        $data['id_pengguna']  = $id_pengguna;
        $data['tgl_mulai']    = $tgl_mulai;
        $data['tgl_selesai']  = $tgl_selesai;
        $data['status']       = 1;
        $this->md_jobdesc->addJob($data);

        $lastGcId = $this->md_jobdesc->getLastId();
        $lastGcId = $lastGcId ? $lastGcId->id : 0;

        // 2. Tambah Detail Jobdesk dengan Rentang Tanggal
        $deskripsi_arr = $this->input->post('deskripsi');
        $idurut_arr    = $this->input->post('idurut');
        $point_arr     = $this->input->post('point');
        $nilai_arr     = $this->input->post('nilai');

        if (is_array($deskripsi_arr) && count($deskripsi_arr) > 0) {
            $urut_counter = 1;
            foreach ($deskripsi_arr as $x => $deskripsi) {
                $deskripsi_clean = trim($deskripsi);
                if ($deskripsi_clean !== '') {
                    $id_urut = (isset($idurut_arr[$x]) && $idurut_arr[$x] !== '') ? trim($idurut_arr[$x]) : $urut_counter;
                    $point   = (isset($point_arr[$x]) && $point_arr[$x] !== '') ? trim($point_arr[$x]) : '1';
                    $nilai   = (isset($nilai_arr[$x]) && $nilai_arr[$x] !== '') ? trim($nilai_arr[$x]) : '2';

                    $dataDetailGc = [
                        'idPengguna'  => $id_pengguna,
                        'id_jobdesc'  => $lastGcId,
                        'id_urut'     => $id_urut,
                        'point'       => $point,
                        'nilai'       => $nilai,
                        'deskripsi'   => $deskripsi_clean,
                        'tgl_mulai'   => $tgl_mulai,
                        'tgl_selesai' => $tgl_selesai,
                        'status'      => 1
                    ];

                    $this->md_jobdesc->addJobdetail($dataDetailGc);
                    $urut_counter++;
                }
            }
        }

        addLog('Jobdesk', 'Menambah Jobdesk Berdasarkan Rentang Tanggal');
        ajaxReturnDie('success', 'Jobdesk Berhasil Ditambahkan dengan Masa Berlaku ' . date('d/m/Y', strtotime($tgl_mulai)) . ' - ' . date('d/m/Y', strtotime($tgl_selesai)), 'jobdesc/show/list/jobdesc');
    }

    public function addDetail()
    {
        grantAccessFor('all');
        
        $data['id_jobdesc'] = $this->input->post('id_jobdesc', TRUE);
        $data['idPengguna'] = $this->input->post('id_pengguna', TRUE);
        $data['point']      = $this->input->post('point', TRUE);
        $id_urut_input      = $this->input->post('idurut', TRUE);

        // Jika nomor urut diisi gunakan input, jika tidak hitung otomatis per jobdesk
        if (!empty($id_urut_input) || $id_urut_input === '0') {
            $data['id_urut'] = $id_urut_input;
        } else {
            $data['id_urut'] = $this->md_jobdesc->getNextUrutByJobdesc($data['id_jobdesc'], $data['idPengguna']);
        }
    
        $data['nilai']      = $this->input->post('nilai', TRUE);
        $data['deskripsi']  = $this->input->post('deskripsi', TRUE);
        $data['status']     = 1;

        $job_hdr = $this->md_jobdesc->getJobById($data['id_jobdesc']);
        if ($job_hdr) {
            $data['tgl_mulai']   = $job_hdr->tgl_mulai;
            $data['tgl_selesai'] = $job_hdr->tgl_selesai;
        }
    
        $result = $this->md_jobdesc->addJobdetail($data);
    
        if (!$result) {
            ajaxReturnDie('error', 'Data Jobdesk gagal disimpan. Silakan cek database/error log.', FALSE);
        }
    
        addLog('Jobdesk', 'Menambah Jobdesk Karyawan');
    
        ajaxReturnDie('success', 'Jobdesk berhasil ditambahkan', TRUE);
    }


    
    

    

    
    //DELETE
    public function delete($id)
    {
      grantAccessFor('all');

    	//$status = $this->input->post('status', TRUE);

    	$data = [
    		'status' =>"2",
    	];


    	$this->md_jobdesc->updateDetail($id, $data);

        addLog('Menghapus Jobdesk', 'Menghapus Jobdesk  ');
        ajaxReturnDie('success', 'JObdesk berhasil dihapus', TRUE);
    }

    
    public function edit($param1)
    {
        grantAccessFor('all');
        $id = decrypt($param1);
        $dt = $this->md_jobdesc->getById($id);
        foreach ($dt as $row) {
            $row->id = encrypt($row->id);
        }
        echo json_encode($dt);
        die;
    }



    public function updateDetail()
    {        
        grantAccessFor('all');
        $id = decrypt($this->input->post('id_pelanggan'));
        $data['point']       = $this->input->post('point', TRUE);
        $data['id_urut']     = $this->input->post('idurut', TRUE);
        $data['nilai']       = $this->input->post('nilai', TRUE);
        $data['Deskripsi']	= $this->input->post('deskripsi', TRUE);
        //$data['status']    				= 1;

        $this->md_jobdesc->updateDetail($id, $data);

        /** LOG */
        addLog('Update Jobdesk', 'Memperbarui data Jobdesk "' . $data['Deskripsi'] . '"');
        ajaxReturnDie('success', 'Data Jobdesk berhasil diperbarui', TRUE);
	}

    public function updateAll()
    {
        grantAccessFor('all');

        $id_jobdesc_enc = $this->input->post('id_jobdesc', TRUE);
        $id_jobdesc     = decrypt($id_jobdesc_enc);
        $id_pengguna    = $this->input->post('id_pengguna', TRUE);
        $tgl_mulai      = $this->input->post('tgl_mulai', TRUE);
        $tgl_selesai    = $this->input->post('tgl_selesai', TRUE);

        if (empty($id_jobdesc)) {
            ajaxReturnDie('error', 'ID Jobdesk tidak valid.', false);
        }

        if (empty($id_pengguna)) {
            ajaxReturnDie('error', 'Silakan pilih Nama Karyawan terlebih dahulu.', false);
        }

        if (empty($tgl_mulai)) {
            $tgl_mulai = date('Y-m-d');
        }
        if (empty($tgl_selesai)) {
            $tgl_selesai = '2099-12-31';
        }

        // Update Header Jobdesk
        $headerData = [
            'id_pengguna' => $id_pengguna,
            'tgl_mulai'   => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
        ];
        $this->db->where('id', $id_jobdesc)->update('jobdesc', $headerData);

        // Hapus detail lama untuk jobdesk ini
        $this->db->where('id_jobdesc', $id_jobdesc)->delete('jobdesc_detail');

        // Insert ulang detail
        $deskripsi_arr = $this->input->post('deskripsi');
        $idurut_arr    = $this->input->post('idurut');
        $point_arr     = $this->input->post('point');
        $nilai_arr     = $this->input->post('nilai');

        if (is_array($deskripsi_arr) && count($deskripsi_arr) > 0) {
            $urut_counter = 1;
            foreach ($deskripsi_arr as $x => $deskripsi) {
                $deskripsi_clean = trim($deskripsi);
                if ($deskripsi_clean !== '') {
                    $id_urut = (isset($idurut_arr[$x]) && $idurut_arr[$x] !== '') ? trim($idurut_arr[$x]) : $urut_counter;
                    $point   = (isset($point_arr[$x]) && $point_arr[$x] !== '') ? trim($point_arr[$x]) : '1';
                    $nilai   = (isset($nilai_arr[$x]) && $nilai_arr[$x] !== '') ? trim($nilai_arr[$x]) : '2';

                    $dataDetail = [
                        'idPengguna'  => $id_pengguna,
                        'id_jobdesc'  => $id_jobdesc,
                        'id_urut'     => $id_urut,
                        'point'       => $point,
                        'nilai'       => $nilai,
                        'deskripsi'   => $deskripsi_clean,
                        'tgl_mulai'   => $tgl_mulai,
                        'tgl_selesai' => $tgl_selesai,
                        'status'      => 1
                    ];

                    $this->md_jobdesc->addJobdetail($dataDetail);
                    $urut_counter++;
                }
            }
        }

        addLog('Jobdesk', 'Mengubah Seluruh Poin Jobdesk ID ' . $id_jobdesc);
        ajaxReturnDie('success', 'Data Jobdesk berhasil diperbarui.', 'jobdesc/show/list/jobdesc');
    }

    

    

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
    public function print_jobdesc($param1 = "")
    {
        grantAccessFor('all');

        $id_jobdesc = decrypt($param1);
        if (empty($id_jobdesc)) {
            show_404();
            return;
        }

        $job_info = $this->md_jobdesc->getJobById($id_jobdesc);
        if (!$job_info) {
            show_404();
            return;
        }

        $idPengguna = $job_info->id_pengguna;

        // Load Md_laporan model to get penilai details
        $this->load->model('md_laporan');
        $data_job = $this->md_laporan->getPengguna($idPengguna);
        if (!$data_job) {
            $data_job = $job_info;
        } else {
            $data_job->tgl_mulai = $job_info->tgl_mulai;
            $data_job->tgl_selesai = $job_info->tgl_selesai;
            $data_job->id_po = $job_info->id_po;
        }

        $data_detail = $this->md_jobdesc->getDetailPOById($id_jobdesc, $idPengguna);

        $dt = [
            'title_pdf' => 'Jobdesk Karyawan - ' . ($data_job ? $data_job->nama : ''),
            'data_job' => $data_job,
            'data_detail' => $data_detail,
        ];

        $html = $this->load->view('pages/v_print/print_jobdesc', $dt, true);

        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $mpdf->AddPage('P', '', '', '', '', 10, 10, 10, 10);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Jobdesk_' . ($data_job ? str_replace(' ', '_', $data_job->nama) : 'Karyawan') . '.pdf', 'I');
    }

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
// pagination -------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
    public function pagination($param = "", $param2 = "")
    {
        grantAccessFor('all');
                if ($param == 'all') {
            
            $dt     = $this->md_jobdesc->getAllPo();
            $start = $this->input->post('start');
            $data  = array();
            $today = date('Y-m-d');

            foreach ($dt['data'] as $row) {
              
              $id_po     = encrypt($row->id_po);
              $pegawai   = '<a href="jobdesc/show/detail/jobdesc/'.$id_po.'">'.$row->pegawai.'</a>';
              $li_btn = '<a href="jobdesc/print_jobdesc/'.$id_po.'" target="_blank" class="btn btn-xs btn-info" title="Print Jobdesk"><i class="fas fa-print"></i> Print</a>';
              if (sessPenggunaId() == 1) {
                  $li_btn .= ' <a href="jobdesc/show/edit/jobdesc/'.$id_po.'" class="btn btn-xs btn-primary" title="Edit Jobdesk"><i class="fas fa-edit"></i> Edit</a>';
                  $li_btn .= ' <button type="button" class="btn btn-xs btn-danger btn-delete-jobdesc" data-id="' . $id_po . '" title="Hapus Jobdesk"><i class="fas fa-trash"></i> Hapus</button>';
              }

              // Format Tanggal Masa Berlaku
              $tgl_mulai   = (!empty($row->tgl_mulai) && $row->tgl_mulai != '0000-00-00') ? date('d/m/Y', strtotime($row->tgl_mulai)) : '01/01/2024';
              $tgl_selesai = (!empty($row->tgl_selesai) && $row->tgl_selesai != '0000-00-00' && $row->tgl_selesai != '2099-12-31') ? date('d/m/Y', strtotime($row->tgl_selesai)) : 'Seterusnya';
              
              // Status Badge Aktif / Histori
              $t_start = !empty($row->tgl_mulai) ? $row->tgl_mulai : '2024-01-01';
              $t_end   = !empty($row->tgl_selesai) ? $row->tgl_selesai : '2099-12-31';
              $is_active = ($t_start <= $today && $t_end >= $today);

              if ($is_active) {
                  $badge_status = '<span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; font-size: 11px; font-weight: 600; background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; border-radius: 9999px; letter-spacing: 0.2px;"><span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10b981; display: inline-block;"></span> Aktif</span>';
              } else {
                  $badge_status = '<span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; font-size: 11px; font-weight: 600; background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 9999px; letter-spacing: 0.2px;"><span style="width: 6px; height: 6px; border-radius: 50%; background-color: #94a3b8; display: inline-block;"></span> Histori / Non-Aktif</span>';
              }

              $masa_berlaku = '
              <div style="display: inline-flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; padding: 6px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; min-width: 175px;">
                  <div style="display: flex; align-items: center; gap: 5px; font-size: 12.5px; font-weight: 600; color: #1e293b; white-space: nowrap;">
                      <i class="far fa-calendar-alt" style="color: #64748b; font-size: 12px;"></i>
                      <span>' . $tgl_mulai . '</span>
                      <span style="color: #94a3b8; font-weight: 400;">—</span>
                      <span>' . $tgl_selesai . '</span>
                  </div>
                  <div>' . $badge_status . '</div>
              </div>';

              $th = array();
              $th[] = ++$start;
              $th[] = $pegawai;
              $th[] = $row->no_pegawai;
              $th[] = $row->jabatan;
              $th[] = $masa_berlaku; // Kolom Baru Masa Berlaku
              $th[] = $li_btn;
              $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;					
        }else if ($param == 'detail') {

        $dt    = $this->md_jobdesc->getAllDetailbyID($param2);
        $start = $this->input->post('start');
        $data  = array();
        foreach ($dt['data'] as $row) {
            $id       	= encrypt($row->id_po);
            $li_btn   	= '
                <div class="btn-group" role="group" aria-label="First group">
                   <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="' . $id . '"><i class="bx bx-pencil"></i></button>
                   <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus Data" data-id="'.$row->id_po.'" data-object="pelanggan/delete/'.$row->id_pelanggan.'"><i class="bx bx-trash"></i></button>
                </div>';
                
            $th = array();
            $th[] = ++$start;
            $th[] = $row->deskripsi;
            $th[] = $li_btn;
            $data[] = $th;

        }
        
        $dt['data'] = $data;
        echo json_encode($dt);
        die;

      }else if ($param == 'my_data') {
            
            $dt     = $this->md_jobdesc->getAllDetailbyID(sessPenggunaId());
          
            $start = $this->input->post('start');
            $data  = array();
            foreach ($dt['data'] as $row) {
              $deskripsi = ($row->nilai == 1) ? "<strong>{$row->deskripsi}</strong>" : $row->deskripsi;
            
              $th = array();
              $th[] = ++$start;
              $th[] = $deskripsi;
              $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;					
        
      }
  
  
  }



    /**
     * Update Rentang Waktu Masa Berlaku Jobdesk (Header & Detail)
     */
        // UPDATE RENTANG WAKTU MASA BERLAKU JOBDESK
    public function updateTanggalMasaBerlaku()
    {
        grantAccessFor('all');
        $id_po = decrypt($this->input->post('id_po', TRUE));
        $tgl_mulai = $this->input->post('tgl_mulai', TRUE);
        $tgl_selesai = $this->input->post('tgl_selesai', TRUE);

        if (empty($tgl_selesai)) {
            $tgl_selesai = '2099-12-31';
        }

        $data = [
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
        ];

        $this->md_jobdesc->updateJobdescTanggal($id_po, $data);

        addLog('Update Masa Berlaku Jobdesk', 'Memperbarui rentang waktu berlaku jobdesk ID: ' . $id_po);
        ajaxReturnDie('success', 'Rentang waktu masa berlaku berhasil diperbarui', TRUE);
    }
 


    // HAPUS SATU KELOMPOK JOBDESK UTAMA
    public function deleteJobdesc($param1 = "")
    {
        grantAccessFor('all');
        $id_po = decrypt($param1);

        if (!empty($id_po)) {
            // Hapus poin detail jobdesk terkait
            $this->md_jobdesc->deleteJobdescDetailByJobId($id_po);
            // Hapus header jobdesk utama
            $this->md_jobdesc->deleteJobdescById($id_po);

            addLog('Hapus Jobdesk', 'Menghapus data Jobdesk ID: ' . $id_po);
            ajaxReturnDie('success', 'Data Jobdesk berhasil dihapus', TRUE);
        } else {
            ajaxReturnDie('error', 'ID Jobdesk tidak valid', FALSE);
        }
    }

}