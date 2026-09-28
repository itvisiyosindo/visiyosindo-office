<?php

use FontLib\Table\Type\post;
use Mpdf\Mpdf;


// defined('BASEPATH') or exit('No direct script access allowed');

class Salary_freelance extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_salary');
        $this->load->model('md_komisi');
        $this->load->model('md_pendapatan_lain');
        $this->load->helper('mandatory_helper');
        $this->load->helper('terbilang_helper');
         $this->load->helper('encrypt_helper');
         $this->load->helper('whatsapp_helper');		
    }

    function id_navbar()
    {
        $id_navbar = "kepegawaian";
        return $id_navbar;
    }

    public function index()
    {
        // grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $page_data['switch'] = $this->id_navbar();
        $page_data['page_name'] = 'salary/v_salary_freelance';
        $page_data['page_title'] = 'Salary Freelance';
        $page_data['page_desc'] = 'Management Salary Freelance';
        $this->load->view('index', $page_data);
    }

    

    public function show($param = "", $param2 = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga',85]);

 
        if ($param == 'detail_salary') {
            $page_data['switch'] = $this->id_navbar();
            $page_data['pengguna'] = $this->md_pengguna->getById(decrypt($param2));
            $page_data['page_name'] = 'salary/v_detail_salary';
            $page_data['page_title'] = 'Detail Salary';
            $page_data['page_desc'] = 'Riwayat Perubahan Salary Karyawan';
            $this->load->view('index', $page_data);
        } else if ($param == 'print') {
            // Data tunjangan
            $where = ['p.status' => 1, 'p.is_active' => 1, 'p.pengguna_id !=' => 1];
            $pengguna = $this->md_pengguna->getBywhere($where);
            $total = [];
            $salary_data = [];
            foreach ($pengguna as $row) {
                $tmp = $this->md_salary->getById($row->id_latestriwayat_salary);
                $salary = '';
                // $pph21 = '';
                $data['nama'] = $row->nama;
                $data['rek'] = $row->no_rek;
                if ($tmp) {
                    foreach ($tmp as $row2) {
                        $salary = $param2 == 'gapok' ? $row2->gaji_pokok : $row2->tunjangan_jabatan;
                        // $pph21 = $param2 == 'gapok' ? $row2->gaji_pokok : 0;
                    }
                }
                $data['salary'] = $salary;
                array_push($salary_data, $data);
                array_push($total, $salary);
            }

            //load mpdf dan membuat page size legal
            $mpdf = new Mpdf(['format' => 'Legal']);
            //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
            $mpdf->AddPage('P');

            $dt = [
                'title_pdf' => 'Rekapitulasi Tunjangan Jabatan',
                'object' => $param2,
                'salary_data' => $salary_data,
                'total' => array_sum($total)
            ];

            // filename dari pdf ketika didownload
            $file_pdf = 'Rekapitulasi Tunjangan Jabatan';

            // page htmk yang akan di jadikan ke pdf
            $html = $this->load->view('pages/v_print/print_salary', $dt, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_pdf . '.pdf', 'I');
        }
    }


    public function edit($param)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $id_salary = $this->md_pengguna->getById($pengguna_id)[0]->id_latestriwayat_salary;
        $dt = $this->md_salary->getById($id_salary);
        $dt[0]->pengguna_id = encrypt($dt[0]->pengguna_id);
        $dt[0]->id_riwayat_salary = encrypt($dt[0]->id_riwayat_salary);
        $dt[0]->gaji_pokok = $dt[0]->gaji_pokok ? rupiah($dt[0]->gaji_pokok) : '';
        $dt[0]->tunjangan_jabatan = $dt[0]->tunjangan_jabatan ? rupiah($dt[0]->tunjangan_jabatan) : '';
        $dt[0]->dasar_potong_bpjs = $dt[0]->dasar_potong_bpjs ? rupiah($dt[0]->dasar_potong_bpjs) : '';
        $dt[0]->dasar_potong_bpjs_tk = $dt[0]->dasar_potong_bpjs_tk ? rupiah($dt[0]->dasar_potong_bpjs_tk) : '';
        echo json_encode($dt);
        die;
    }

    public function edit_komisi($param)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $id_komisi = $this->md_pengguna->getById($pengguna_id)[0]->id_komisi;
        $dt = $this->md_komisi->getById($id_komisi);
        // echo_array($dt);die;
        $dt[0]->pengguna_id = encrypt($dt[0]->pengguna_id);
        $dt[0]->id_komisi = encrypt($dt[0]->id_komisi);
        $dt[0]->jumlah = $dt[0]->jumlah ? rupiah($dt[0]->jumlah) : '';
        $dt[0]->bulan = $dt[0]->bulan;
        echo json_encode($dt);
        die;
    }

    public function edit_pendapatan_lain($param)
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $id_pendapatan_lain = $this->md_pengguna->getById($pengguna_id)[0]->id_pendapatan_lain;
        $dt = $this->md_pendapatan_lain->getById($id_pendapatan_lain);
        // echo_array($dt);die;
        $dt[0]->pengguna_id = encrypt($dt[0]->pengguna_id);
        $dt[0]->id_pendapatan = encrypt($dt[0]->id_pendapatan);
        $dt[0]->jumlah = $dt[0]->jumlah ? rupiah($dt[0]->jumlah) : '';
        $dt[0]->pengurangan = $dt[0]->pengurangan ? rupiah($dt[0]->pengurangan) : '';
        echo json_encode($dt);
        die;
    }

    public function getAllRiwayat($param = "")
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $pengguna_id = decrypt($param);
        $dt = $this->md_salary->getRiwayatByIdPengguna($pengguna_id);
        $start = $this->input->post('start');
        $data = array();
        foreach ($dt['data'] as $row) {
            $th = array();
            $th[] = ++$start . '.';
            $th[] = isset($row->gaji_pokok) ? rupiah($row->gaji_pokok) : '';
            $th[] = isset($row->tunjangan_jabatan) ? rupiah($row->tunjangan_jabatan) : '';
            $th[] = date_view_format($row->data_created);
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function kirimnotifikasi($id){
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);
         $month = date("Y-m");
        
        $this->db->where('tutupbuku =', 0);
        $this->db->where('year(data_created) =', date('Y'));
        $this->db->where('month(data_created) =', date('m'));
        $this->db->where('idpengguna', decrypt($id));
        $query = $this->db->get('riwayat_salary_tutupbuku');
        $jumlah = $query->num_rows();
        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);
        $row = $query->result();
        if($jumlah>0){
             $myObj = encrypt($row[0]->idpengguna).",".$tglcetakstamp;
             $parJSON = encryptvym($myObj);
             $dataWa = [
              'noPenerima' 	=> $row[0]->nowhatsapp, // 33,
              'namaSurat' 	=> 'Slip Gaji '.getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
              'penerima' 	    => $row[0]->nama,
              'perihal' 	    => '',
              'kode' 	        => $row[0]->npp
             ];
             waSlipGaji($dataWa,'https://office.visiyosindo.id/salary/print_slip/'.$parJSON);
        }
    }
    
    public function tutupbuku(){  
           grantAccessFor(['Administrator', 'Hrd', 'Ga']);
            $month = date("Y-m");
            $this->db->where('tutupbuku =', 0);
            $this->db->where('year(data_created) =', date('Y'));
            $this->db->where('month(data_created) =', date('m'));
            $query = $this->db->get('riwayat_salary_tutupbuku');
            $jumlah = $query->num_rows();
        
           if($jumlah<=0){
                $dt    =  $this->md_salary->addRiwayatSalaryTutupBuku((date('m', strtotime($month))),date('Y', strtotime($month)),sessPenggunaId());
           }
           
           
            $month = date("Y-m");
            
            $page_data['switch'] = $this->id_navbar();
            $page_data['id_pengguna'] = sessPenggunaId();
            $page_data['page_name']       = 'salary/v_salarytutupbuku';
            $page_data['page_title']      = 'Tutup Buku Penggajian';
            $page_data['page_desc']       = 'Data Penggajian';
            
            $tglcetak = date('Y-m-d H:i:s');
            $tglcetakstamp = strtotime($tglcetak);
            
            
            

            if($jumlah>0){
                 foreach ($query->result() as $row){
                     //if($row->idpengguna==715){
                         //send notif wa
                        $myObj = encrypt($row->idpengguna).",".$tglcetakstamp;
                        $parJSON = encryptvym($myObj);
            			$dataWa = [
                            'noPenerima' 	=> $row->nowhatsapp, // 33,
                        	'namaSurat' 	=> 'Slip Gaji '.getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
                        	'penerima' 	    => $row->nama,
                        	'perihal' 	    => '',
                        	'kode' 	        => $row->npp
                        ];
                        waSlipGaji($dataWa,'https://office.visiyosindo.id/salary/print_slip/'.$parJSON);
                     //}
                 }
                 $this->load->view('index', $page_data);
            }
                   //$data = $this->md_salary->getRiwayatTutupBuku()->num_rows();
             
    }

   public function print_slip($id)
    {
         grantAccessFor('all');
        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);
        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('P');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        $month = date("Y-m");
        
        $dataid = explode(",", decryptvym($id));
        $idpengguna =decrypt($dataid[0]);
        
        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getById($idpengguna),
            'title_pdf' => 'SLIP GAJI KARYAWAN',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'potonganlain' => potongan_lain($idpengguna),
            'tglcetak' =>$dataid[1],
            'dtgaji' => $this->md_salary->getSalaryTerakhirTutupBukuByPenggunaID($idpengguna),
            //'dtpph' => $this->md_salary->getNewpph21ByPenggunaID($idpengguna),
            //'dtthr' => $this->md_salary->getTHRByPenggunaID($idpengguna),
            'dtsalary' => $this->md_salary->getSalaryHistoryPenggunaID($idpengguna)
        ];
        
        
        
        // filename dari pdf ketika didownload
        $file_pdf = 'SLIP GAJI KARYAWAN ' . $data['periode'];

        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_slip_gaji_freelance', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }
    
    public function print($param2 = "")
    {
      
        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        $month = date("Y-m");
        $idPe=77;
        // Data tunjangan
        $data = [
            //'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47,84,714,77,79,110,87,72,70,81,69,83,107,86,74,57,56]),
            //'dt' => $this->md_pengguna->getBywhereFreelance(),
            //'dtgaji' => $this->md_salary->getSalaryFreelance((date('m', strtotime($month))),date('Y', strtotime($month))),
            'dtgaji' => $this->md_salary->getSalaryFreelanceCoba($idPe),
            'title_pdf' => 'Rekapitulasi Penghasilan Freelance',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'bulan' => date('m', strtotime($month)),
            'tahun' => date('Y', strtotime($month))
        ];
        // filename dari pdf ketika didownload
        $file_pdf = 'Rekapitulasi Penghasilan Periode ' . $data['periode'];
        
        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_salary_full_freelance', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }


    public function print_month($month = '')
    {
      
        //load mpdf dan membuat page size legal
        $mpdf = new Mpdf(['format' => 'Legal']);

        //  orientasi ketas 'L' untuk Landscape 'P' untuk Portait
        $mpdf->AddPage('L');
        // Data bulan, jika tidak ada dipilih mengambil hari pertama di bulan sebelum nya
        //$month = date("Y-m");
        // Data tunjangan
        $data = [
            'dt' => $this->md_pengguna->getByWherenotIn(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'Administrator'], [58, 47,84,714,77,79,110,87,72,70,81,69,83,107,86,74,57,56]),
            //'dtgaji' => $this->md_salary->getSalaryFULLVYM((date('m', strtotime($month))),date('Y', strtotime($month))),
            'dtgaji' => $this->md_salary->getSalaryHistoryMonth((date('m', strtotime($month))),date('Y', strtotime($month))),
            'title_pdf' => 'Rekapitulasi Penghasilan',
            'periode' => getMonthName(date('m', strtotime($month))) . ' ' . date('Y', strtotime($month)),
            'month' => $month,
            'bulan' => date('m', strtotime($month)),
            'tahun' => date('Y', strtotime($month))
        ];
        // filename dari pdf ketika didownload
        $file_pdf = 'Rekapitulasi Penghasilan Periode ' . $data['periode'];
        
        // page htmk yang akan di jadikan ke pdf
        $html = $this->load->view('pages/v_print/print_salary_full_history', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($file_pdf . '.pdf', 'I');
    }

    

   
    public function pagination()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $dt = $this->md_pengguna->getAllPenggunaFreelance();
        $start = $this->input->post('start');
        $data = array();
        $bulan = date("Y-m");
        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);
        
        foreach ($dt['data'] as $row) {
            $id = encrypt($row->pengguna_id);
            $pengguna = $this->md_pengguna->getById($row->pengguna_id);
            $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
            $nama_pengguna = '<a href="salary/show/detail_salary/' . $id . '")>' . $row->nama . '</a>';
            $bpjskes = tunjanganBPJSKesehatan($row->pengguna_id);
            $bpjstk = tunjanganBPJStk($row->pengguna_id);
            $komisi = komisi($row->pengguna_id);
            $pendapatan_lain = pendapatan_lain($row->pengguna_id);
            $myObj = $id.",".$tglcetakstamp;
            $parJSON = encryptvym($myObj);
            $li_btn = '
                <div class="btn-group" role="group" aria-label="First group">
                <button type="button" title="Tambah Pendapatan Lain" class="btn btn-sm btn-info btn-pen_lain" data-id="' . $id . '"><i class="bx bx-message-alt-add"></i></button>
                    <a target="_self" class="btn btn-danger" href="salary_freelance/print_slip/' . $parJSON . '">Slip Gaji</a>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_pengguna;
            $th[] = isset($salary[0]->gaji_pokok) ? rupiah($salary[0]->gaji_pokok) : '';
            $th[] = $bpjskes ? rupiah($bpjskes) : '-';
            $th[] = $bpjstk ? rupiah($bpjstk) : '-';
            $th[] = $komisi ? rupiah($komisi) : '-';
            $th[] = $pendapatan_lain ? rupiah($pendapatan_lain) : '-';
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }

    public function pagination_freelance()
    {
        grantAccessFor(['Administrator', 'Hrd', 'Ga']);

        $dt = $this->md_pengguna->getAllPenggunaFreelance();
        $start = $this->input->post('start');
        $data = array();
        $bulan = date("Y-m");
        $tglcetak = date('Y-m-d H:i:s');
        $tglcetakstamp = strtotime($tglcetak);
        
        foreach ($dt['data'] as $row) {
            $id = encrypt($row->pengguna_id);
            $pengguna = $this->md_pengguna->getById($row->pengguna_id);
            $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
            $nama_pengguna = '<a href="salary/show/detail_salary/' . $id . '")>' . $row->nama . '</a>';
            $bpjskes = tunjanganBPJSKesehatan($row->pengguna_id);
            $bpjstk = tunjanganBPJStk($row->pengguna_id);
            $komisi = komisi($row->pengguna_id);
            $pendapatan_lain = pendapatan_lain($row->pengguna_id);
            $myObj = $id.",".$tglcetakstamp;
            $parJSON = encryptvym($myObj);
            $li_btn = '
                <div class="btn-group" role="group" aria-label="First group">
                <button type="button" title="Tambah Pendapatan Lain" class="btn btn-sm btn-info btn-pen_lain" data-id="' . $id . '"><i class="bx bx-message-alt-add"></i></button>
                    <a target="_self" class="btn btn-danger" href="salary/print_slip/' . $parJSON . '">Slip Gaji</a>
                </div>';
            $th = array();
            $th[] = ++$start . '.';
            $th[] = $nama_pengguna;
            $th[] = isset($salary[0]->gaji_pokok) ? rupiah($salary[0]->gaji_pokok) : '';
            $th[] = $bpjskes ? rupiah($bpjskes) : '-';
            $th[] = $bpjstk ? rupiah($bpjstk) : '-';
            $th[] = $komisi ? rupiah($komisi) : '-';
            $th[] = $pendapatan_lain ? rupiah($pendapatan_lain) : '-';
            $th[] = $li_btn;
            $data[] = $th;
        }
        $dt['data'] = $data;
        echo json_encode($dt);
        die;
    }


    

    
    
     
    public function test()
    {

        $pengguna = $this->md_pengguna->getById(56);
        $salary = $this->md_salary->getById($pengguna[0]->id_latestriwayat_salary);
        $pl = $this->md_pendapatan_lain->getById($pengguna[0]->id_pendapatan_lain);
        echo 'nama Meilina Safitri';
        echo '<br>';
        echo 'Gaji Pokok =' . $salary[0]->gaji_pokok;
        echo '<br>';
        echo 'Tunjangan Jabatan =' . $salary[0]->tunjangan_jabatan;
        echo '<br>';
        echo 'Tunjangan Kinerja =' . tunjangan(56, '2022-05')['total_kinerja'];
        echo '<br>';
        echo 'Tunjangan Konsumsi =' . tunjangan(56, '2022-05')['total_konsumsi'];
        echo '<br>';
        echo 'Tunjangan BPJS Kes =' . tunjanganBPJSKesehatan(56);
        echo '<br>';
        echo 'Tunjangan BPJS Kes =' . potonganBPJSkes(56)['bpjskes'];
        echo '<br>';
        echo 'Tunjangan BPJS TK =' . tunjanganBPJStk(56);
        echo '<br>';

        echo 'Pendapatan Lainnya =' . pendapatan_lain(56);
        echo '<br>';
        echo 'Bruto =' . penghasilan_bruto(56);
        echo '<br>';
        echo 'Netto =' . penghasilan_netto(56);
        echo '<br>';
        echo 'Dasar =' . dasar_perhitungan_pajak_pribadi(56);
        echo '<br>';
        echo 'penghasilan Kena Pajak =' . penghasilan_kena_pajak(56);
        echo '<br>';
        echo 'pph21 =' . dasar_tarif(56);
        echo '<br>';
        echo 'MK =' . masaKerjaBulan($pengguna[0]->tgl_masuk);
        echo '<br>';





        //    print_r(masaKerjaBulan('2021-03-08'));

        // echo 'nama Meilina Safitri';
        // echo '<br>';
        // echo 'Gaji Pokok ='.  $salary[0]->gaji_pokok;
        // echo '<br>';
        // echo 'Tunjangan BPJS Kes ='. tunjanganBPJSKesehatan(49);
        // echo '<br>';

        // echo 'Tunjangan BPJS Tk ='. tunjanganBPJStk(49);

        //    echo_array(tunjanganBPJStk(54));
        //    echo_array(tunjanganBPJSKesehatan(54));
        //    echo_array(dasar_tarif(54));
        //    echo_array(tunjangan(49,'2022-04'));
        //    echo_array(penghasilan_netto(54));
        //    echo_array(dasar_tarif(54));
    }
}