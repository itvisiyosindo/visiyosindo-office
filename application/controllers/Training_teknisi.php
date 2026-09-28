<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Training_teknisi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('md_pengguna');
        $this->load->model('md_training_teknisi');
        $this->load->model('md_kategori_tiket');
        $this->load->helper('whatsapp_helper');
    }

    function id_navbar()
    {
        return "helpdesk";
    }

    public function index()
    {
        grantAccessFor('all');

        $canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManageTraining) {
            redirect(base_url('training_teknisi/my_training'));
        }

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'training_teknisi/v_manajemen_training';
        $page_data['page_title']    = 'Manajemen Data Training';
        $page_data['page_desc']     = 'Mengelola data training teknisi';
        $page_data['kategori']      = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
        $page_data['pengguna']      = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);

        $this->load->view('index', $page_data);
    }

    public function tambah()
    {
        grantAccessFor('all');

        $canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManageTraining) {
            redirect(base_url('training_teknisi/my_training'));
        }

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'training_teknisi/v_tambah_training';
        $page_data['page_title']    = 'Tambah Data Training';
        $page_data['page_desc']     = 'Membuat penugasan training baru untuk teknisi';
        $page_data['kategori']      = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
        $page_data['pengguna']      = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);

        $this->load->view('index', $page_data);
    }

    public function my_training()
    {
        grantAccessFor('all');

        $page_data['switch']        = $this->id_navbar();
        $page_data['page_name']     = 'training_teknisi/v_my_training';
        $page_data['page_title']    = 'My Training';
        $page_data['page_desc']     = 'Daftar penugasan training untuk Anda';
        $page_data['kategori']      = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);

        $this->load->view('index', $page_data);
    }

    public function add()
    {
        grantAccessFor('all');

        $canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManageTraining) {
            ajaxReturnDie('error', 'Akses ditolak', TRUE);
        }

        $idTr = $this->md_training_teknisi->getTrainingKodeId();
        $ambilId = is_null($idTr) ? 0 : $idTr->id_training;
        $ambilId = $ambilId + 1;
        $panjangId = strlen($ambilId);

        if ($panjangId == 1) {
            $kodeTraining = "00" . $ambilId;
        } else if ($panjangId == 2) {
            $kodeTraining = "0" . $ambilId;
        } else {
            $kodeTraining = $ambilId;
        }

        $bulanTraining = "";
        $m = date("m");
        if ($m == "01") $bulanTraining = "I";
        else if ($m == "02") $bulanTraining = "II";
        else if ($m == "03") $bulanTraining = "III";
        else if ($m == "04") $bulanTraining = "IV";
        else if ($m == "05") $bulanTraining = "V";
        else if ($m == "06") $bulanTraining = "VI";
        else if ($m == "07") $bulanTraining = "VII";
        else if ($m == "08") $bulanTraining = "VIII";
        else if ($m == "09") $bulanTraining = "IX";
        else if ($m == "10") $bulanTraining = "X";
        else if ($m == "11") $bulanTraining = "XI";
        else if ($m == "12") $bulanTraining = "XII";

        $tahunTraining = date("Y");
        $is_manual = $this->input->post('is_manual_kategori');
        if ($is_manual == 1) {
            $kategori_manual = trim((string) $this->input->post('kategori_manual', TRUE));
            if ($kategori_manual === '') {
                ajaxReturnDie('error', 'Nama kategori manual tidak boleh kosong', TRUE);
                return;
            }

            // Check if exists
            $existing = $this->db->get_where('topik_tiket', ['LOWER(nama)' => strtolower($kategori_manual)])->row();
            if ($existing) {
                $id_topik = $existing->id_topik;
            } else {
                $new_kategori = [
                    'nama' => $kategori_manual,
                    'deskripsi' => 'Dibuat otomatis dari penugasan training manual',
                    'status' => 1,
                    'is_active' => 1
                ];
                $this->db->insert('topik_tiket', $new_kategori);
                $id_topik = $this->db->insert_id();
            }
        } else {
            $id_topik = $this->input->post('kategori', TRUE);
        }

        $data['kode_training']  = $kodeTraining;
        $data['rekanan']        = $this->input->post('rekanan', TRUE);
        $data['contact_person'] = $this->input->post('contact_person', TRUE);
        $data['subject']        = $this->input->post('subject', TRUE);
        $data['id_topik']       = $id_topik;
        $data['prioritas']      = $this->input->post('prioritas', TRUE);
        $data['id_teknisi']     = $this->input->post('teknisi', TRUE);
        $data['deskripsi']      = $this->input->post('deskripsi', TRUE);
        $data['file_pendukung']  = $this->input->post('attachment', TRUE);
        $data['waktu_mulai']    = date_db_format($this->input->post('start', TRUE));
        $data['waktu_selesai']  = date_db_format($this->input->post('end', TRUE));
        $data['status_training'] = 1;
        $data['status_data']     = 1;
        $data['created_by']     = sessPenggunaId();

        $id_new = $this->md_training_teknisi->addTraining($data);

        // Tambah log pertama
        $log_data = [
            'id_training'   => $id_new,
            'kode_training' => $kodeTraining,
            'id_pembuat'    => sessPenggunaId(),
            'update'        => 'Training Baru Dibuat: ' . $data['subject'],
            'status'        => '1'
        ];
        $this->md_training_teknisi->addUpdateTraining($log_data);

        // Kirim WA
        $this->sendWaTraining(1, $id_new);

        addLog('Menambahkan Training', 'Menambah Training ' . $kodeTraining);
        ajaxReturnDie('success', 'Training berhasil ditambahkan', TRUE);
    }

    public function show($param = "", $param2 = "")
    {
        grantAccessFor('all');
        if ($param == 'detail') {
            $id_training = decrypt($param2);
            $page_data['switch']            = $this->id_navbar();
            $page_data['data_training']     = $this->md_training_teknisi->getById($id_training);
            $page_data['data_update']       = $this->md_training_teknisi->getUpdateById(['t.id_training' => $id_training]);
            $page_data['page_name']         = 'training_teknisi/v_detail_training';
            $page_data['page_title']        = 'Detail Training';
            $page_data['page_desc']         = 'Melihat rincian dan progres training';
            $page_data['kategori']          = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
            $page_data['pengguna']          = $this->md_pengguna->getByWhere(['p.is_active' => 1, 'p.status' => 1, 'p.level !=' => 'all']);

            $this->load->view('index', $page_data);
        }
    }

    public function update($param = "")
    {
        grantAccessFor('all');
        if ($param == 'logTraining') {
            $id_training = $this->input->post('id_training');
            $dataTraining = $this->md_training_teknisi->getById($id_training);

            $update_desc = $this->input->post('deskripsi', TRUE);
            $attachment = !empty($this->input->post('attachment', TRUE)) ? $this->input->post('attachment', TRUE) : '-';

            // Update status_training menjadi On Progress (2) jika masih Baru (1)
            if ($dataTraining[0]->status_training == 1) {
                $this->md_training_teknisi->updateTraining($id_training, ['status_training' => 2]);
            }

            $up_log['id_training']   = $id_training;
            $up_log['kode_training'] = $dataTraining[0]->kode_training;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = $update_desc;
            $up_log['file_update']   = $attachment;
            $up_log['status']        = "2";
            $this->md_training_teknisi->addUpdateTraining($up_log);

            // Kirim notifikasi WA
            $this->sendWaTraining(2, $id_training, $update_desc);

            addLog('Memperbaharui Training', 'Melakukan Update Training ' . $dataTraining[0]->kode_training);
            ajaxReturnDie('success', 'Update progres training berhasil disimpan', TRUE);
        } else if ($param == 'status_training') {
            $canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
            if (!$canManageTraining) {
                ajaxReturnDie('error', 'Akses ditolak', TRUE);
            }

            $id_training = decrypt($this->input->post('id_training', TRUE));
            $new_status = $this->input->post('value');

            $this->md_training_teknisi->updateTraining($id_training, ['status_training' => $new_status]);

            $dataTraining = $this->md_training_teknisi->getById($id_training);
            $status_text = "";
            if ($new_status == 1) $status_text = "Baru";
            else if ($new_status == 2) $status_text = "On Progress";
            else if ($new_status == 3) $status_text = "Revision";
            else if ($new_status == 4) $status_text = "Selesai";

            $up_log['id_training']   = $id_training;
            $up_log['kode_training'] = $dataTraining[0]->kode_training;
            $up_log['id_pembuat']    = sessPenggunaId();
            $up_log['update']        = 'Mengubah status training menjadi "' . $status_text . '"';
            $up_log['status']        = "3";
            $this->md_training_teknisi->addUpdateTraining($up_log);

            addLog('Mengubah Status Training', 'Mengubah status training "' . $dataTraining[0]->subject . '" menjadi "' . $status_text . '"');
            ajaxReturnDie('success', 'Status Training Berhasil Diubah', TRUE);
        } else if ($param == 'prioritas') {
            $canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
            if (!$canManageTraining) {
                ajaxReturnDie('error', 'Akses ditolak', TRUE);
            }

            $id_training = decrypt($this->input->post('id_training', TRUE));
            $new_priority = $this->input->post('value');

            $this->md_training_teknisi->updateTraining($id_training, ['prioritas' => $new_priority]);

            $dataTraining = $this->md_training_teknisi->getById($id_training);
            $prioritas_text = "";
            if ($new_priority == 1) $prioritas_text = "Low";
            else if ($new_priority == 2) $prioritas_text = "Medium";
            else if ($new_priority == 3) $prioritas_text = "Priority";

            addLog('Mengubah Prioritas Training', 'Mengubah prioritas training "' . $dataTraining[0]->subject . '" menjadi "' . $prioritas_text . '"');
            ajaxReturnDie('success', 'Prioritas Training Berhasil Diubah', TRUE);
        }
    }

    public function delete($param = "", $param2 = "")
    {
        grantAccessFor('all');

        $canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
        if (!$canManageTraining) {
            ajaxReturnDie('error', 'Akses ditolak', TRUE);
        }

        if ($param == 'training') {
            $id_training = decrypt($param2);
            $dataTraining = $this->md_training_teknisi->getById($id_training);
            $this->md_training_teknisi->updateTraining($id_training, ['status_data' => 2]);

            addLog('Menghapus Training', 'Menghapus Training ' . $dataTraining[0]->subject);
            ajaxReturnDie('success', 'Training berhasil dihapus', 'reload_table');
        }
    }

    public function pagination($param = "")
    {
        grantAccessFor('all');
        if ($param == 'training') {
            $canManageTraining = isAdmin() || isCRO() || isGa() || sessPenggunaId() == 755;
            if (!$canManageTraining) {
                ajaxReturnDie('error', 'Akses ditolak', TRUE);
            }
            $dt = $this->md_training_teknisi->getAllTraining();
            $start = $this->input->post('start');
            $data = array();

            foreach ($dt['data'] as $row) {
                $prioritas = '';
                if ($row->prioritas == 1) {
                    $prioritas = '<span class="badge badge-ecommerce badge-success">Low</span>';
                } elseif ($row->prioritas == 2) {
                    $prioritas = '<span class="badge badge-ecommerce badge-primary">Medium</span>';
                } else {
                    $prioritas = '<span class="badge badge-ecommerce badge-danger">Priority</span>';
                }

                $status = '';
                if ($row->status_training == 1) {
                    $status = '<span class="badge badge-ecommerce badge-info">Baru</span>';
                } elseif ($row->status_training == 2) {
                    $status = '<span class="badge badge-ecommerce badge-warning">Dalam Proses</span>';
                } elseif ($row->status_training == 3) {
                    $status = '<span class="badge badge-ecommerce badge-danger">Revisi</span>';
                } else {
                    $status = '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                }

                $id_enc = encrypt($row->id_training);
                $kode_link = '<a href="training_teknisi/show/detail/' . $id_enc . '">' . $row->kode_training . '</a>';

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $kode_link;
                $th[] = $row->rekanan;
                $th[] = $row->contact_person;
                $th[] = $row->subject;
                $th[] = $row->nama_topik;
                $th[] = $prioritas;
                $th[] = $row->nama_teknisi;
                $th[] = date('d-m-Y', strtotime($row->waktu_mulai)) . ' s/d ' . date('d-m-Y', strtotime($row->waktu_selesai));
                $th[] = $status;

                $action = '<a href="training_teknisi/show/detail/' . $id_enc . '" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i> Detail</a> ';
                $action .= '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-delete" data-id="' . $id_enc . '" data-object="training_teknisi/delete/training"><i class="fas fa-trash"></i> Hapus</a>';
                $th[] = $action;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        } else if ($param == 'my_training') {
            $id_pengguna = sessPenggunaId();
            $dt = $this->md_training_teknisi->getTrainingPenerima($id_pengguna);
            $start = $this->input->post('start');
            $data = array();

            foreach ($dt['data'] as $row) {
                $prioritas = '';
                if ($row->prioritas == 1) {
                    $prioritas = '<span class="badge badge-ecommerce badge-success">Low</span>';
                } elseif ($row->prioritas == 2) {
                    $prioritas = '<span class="badge badge-ecommerce badge-primary">Medium</span>';
                } else {
                    $prioritas = '<span class="badge badge-ecommerce badge-danger">Priority</span>';
                }

                $status = '';
                if ($row->status_training == 1) {
                    $status = '<span class="badge badge-ecommerce badge-info">Baru</span>';
                } elseif ($row->status_training == 2) {
                    $status = '<span class="badge badge-ecommerce badge-warning">Dalam Proses</span>';
                } elseif ($row->status_training == 3) {
                    $status = '<span class="badge badge-ecommerce badge-danger">Revisi</span>';
                } else {
                    $status = '<span class="badge badge-ecommerce badge-success">Selesai</span>';
                }

                $id_enc = encrypt($row->id_training);
                $kode_link = '<a href="training_teknisi/show/detail/' . $id_enc . '">' . $row->kode_training . '</a>';

                $th = array();
                $th[] = ++$start . '.';
                $th[] = $kode_link;
                $th[] = $row->rekanan;
                $th[] = $row->contact_person;
                $th[] = $row->subject;
                $th[] = $row->nama_topik;
                $th[] = $prioritas;
                $th[] = date('d-m-Y', strtotime($row->waktu_mulai)) . ' s/d ' . date('d-m-Y', strtotime($row->waktu_selesai));
                $th[] = $status;

                $action = '<a href="training_teknisi/show/detail/' . $id_enc . '" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i> Detail</a>';
                $th[] = $action;

                $data[] = $th;
            }
            $dt['data'] = $data;
            echo json_encode($dt);
            die;
        }
    }

    private function sendWaTraining($kodeKirim, $id_training, $update_desc = "")
    {
        $dataTraining = $this->md_training_teknisi->getById($id_training);
        if (empty($dataTraining)) return FALSE;

        $row = $dataTraining[0];

        $prioritas_text = "";
        if ($row->prioritas == 1) $prioritas_text = "Low";
        else if ($row->prioritas == 2) $prioritas_text = "Medium";
        else if ($row->prioritas == 3) $prioritas_text = "Priority";

        $waktu_text = date('d-m-Y', strtotime($row->waktu_mulai)) . ' s/d ' . date('d-m-Y', strtotime($row->waktu_selesai));

        if ($kodeKirim == 1) {
            $nope = $row->no_hp_teknisi;
            $nama = $row->nama_teknisi;

            // 1. Kirim secara Personal ke Teknisi
            if (!empty($nope)) {
                $dataWa = [
                    'noPenerima'    => $nope,
                    'namaPenerima'  => $nama,
                    'kodeTraining'  => $row->kode_training,
                    'rekanan'       => urlencode($row->rekanan),
                    'contactPerson' => urlencode($row->contact_person),
                    'subject'       => urlencode($row->subject),
                    'kategori'      => urlencode($row->nama_topik),
                    'prioritas'     => $prioritas_text,
                    'waktu'         => $waktu_text
                ];
                waTrainingTeknisiOpen($dataWa);
            }

            // 2. Kirim ke Group WhatsApp
            $dataWaGroup = [
                'groupPenerima' => urlencode('TEKNISI MEDIKAL PT. VYM'),
                'namaTeknisi'   => urlencode($row->nama_teknisi),
                'kodeTraining'  => $row->kode_training,
                'rekanan'       => urlencode($row->rekanan),
                'contactPerson' => urlencode($row->contact_person),
                'subject'       => urlencode($row->subject),
                'kategori'      => urlencode($row->nama_topik),
                'prioritas'     => $prioritas_text,
                'waktu'         => $waktu_text
            ];
            waTrainingTeknisiOpenGroup($dataWaGroup);
        } else if ($kodeKirim == 2) {
            // Tentukan no HP penerima secara dinamis:
            // Jika teknisi yang mengupdate, kirim notifikasi ke pembuat training.
            // Jika pembuat/admin yang mengupdate, kirim notifikasi ke teknisi.
            $penerimaNoHp = $row->no_hp_teknisi;
            if (sessPenggunaId() == $row->id_teknisi) {
                $penerimaNoHp = $row->no_hp_pembuat;
            }

            // 1. Kirim secara Personal ke target (teknisi/pembuat)
            if (!empty($penerimaNoHp)) {
                $dataWa = [
                    'noPenerima'    => $penerimaNoHp,
                    'kodeTraining'  => $row->kode_training,
                    'subject'       => urlencode($row->subject),
                    'update'        => urlencode($update_desc)
                ];
                waTrainingTeknisiUpdate($dataWa);
            }

            // 2. Kirim ke Group WhatsApp
            $dataWaGroup = [
                'groupPenerima' => urlencode('TEKNISI MEDIKAL PT. VYM'),
                'kodeTraining'  => $row->kode_training,
                'subject'       => urlencode($row->subject),
                'namaPengaju'   => urlencode(sessNama()),
                'update'        => urlencode($update_desc)
            ];
            waTrainingTeknisiUpdateGroup($dataWaGroup);
        }

        return TRUE;
    }
}
