<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_tiket extends CI_Model
{

    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // ADD ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function addTiket($data)
    {
        $this->db->insert('tiket', $data);
    }

    function addPicSupport($data)
    {
        $this->db->insert('tiket_detail_pic_support', $data);
    }

    function addUpdateTiket($data)
    {
        $this->db->insert('tiket_update', $data);
    }


    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // UPDATE ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('tiket', $data);
    }

    function updateTiket($id, $data)
    {
        $this->db->where('id_tiket', $id);
        $this->db->update('tiket', $data);
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    // GET ----------------------------------------------------------------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function getBywhere($where)
    {
        $this->db->select('
                            t.id_tiket,
							t.kode_tiket,
                            t.log_tiket,
                            t.id_penerima,
                            t.id_pelanggan,
                            tt.id_topik,
                            tt.nama as nama_topik,
                            t.subject,
                            t.prioritas,
                            t.deskripsi,
                            t.file_pendukung,
                            t.file_invoice as invoice,
                            t.pelanggan,
                            t.waktu_mulai,
                            t.waktu_selesai,
							t.catatan_visit,
							t.status_visit as stat_visit,
							t.file_pengajuan_biaya,
							t.file_pengajuan_gokop as file_gocorp,
							t.file_laporan_teknisi,
							t.file_laporan_biaya,
							t.file_feedback_cust as feedcust,
							t.deskripsi_feedback_cust as desk_feedcust,
                            p1.nama as nama_pembuat,
                            p2.nama as nama_penerima,
                            t.status_tiket,
                            t.created_at,
							t.status_surat_dinas as stat_sudin,
							t.file_surat_dinas as link_sudin,
							t.waktu_selesai as deadline,
                            p1.level as level1,    
                            p2.level as level2, 
							p.provinsi as provinsi,
							p.kota as kota,
							COALESCE(p.identitas_pelanggan, t.pelanggan) as pelanggan
                        ')
            ->from('tiket t')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik', 'left')
            ->join('pengguna p1', 't.id_pembuat=p1.pengguna_id', 'left')
            ->join('pengguna p2', 't.id_penerima=p2.pengguna_id', 'left')
            ->join('pelanggan p', 't.id_pelanggan=p.id_pelanggan', 'left')
            ->where($where)
            ->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }


    function getUpdateById($where)
    {
        $this->db->select('
							tu.kode_tiket,
                            tu.id_pembuat,
                            tu.update,
                            tu.status,
                            tu.file_update,
                            tu.created_at as waktu,
                            t.kode_tiket,
                            t.subject,
                            t.id_tiket,
                            p1.nama as nama_pembuat
                        ')
            ->from('tiket t')
            ->join('tiket_update tu', 't.kode_tiket=tu.kode_tiket')
            ->join('pengguna p1', 'tu.id_pembuat=p1.pengguna_id')
            ->where($where)
            ->order_by('tu.created_at', 'DESC');
        return $this->db->get()->result();
    }

    function getById($id)
    {
        return $this->db->get_where('tiket t', array('t.id_tiket' => $id))->result();
    }

    function getByIdTopik($id)
    {
        return $this->db->get_where('tiket t', array('t.id_topik' => $id, 't.status_data' => 1))->result();
    }

    function getLastCode()
    {
        return  $this->db->select("id_tiket")
            ->limit(1)
            ->order_by('id_tiket', "DESC")
            ->get('tiket')
            ->row();
    }

    function getTiketKodeId()
    {
        return $this->db->select("COUNT(*) as id_tiket")->limit(1)->order_by('id_tiket', "DESC")->get_where('tiket', array('YEAR(`created_at`)' => date('Y')))->row();
    }


    function getAllTiket()
    {
        if ($this->input->post('filter_pelanggan'))
            $this->datatables->like('t.pelanggan', $this->input->post('filter_pelanggan'));

        if ($this->input->post('filter_subject'))
            $this->datatables->like('t.subject', $this->input->post('filter_subject'));

        $this->db->order_by('t.id_tiket', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
				t.kode_tiket,
                t.created_at,
				t.subject,
                t.prioritas,
                 t.deskripsi,
				t.status_visit,
				t.status_surat_dinas,
				t.status_pembiayaan,
				t.file_surat_dinas as file_sudin,
				t.file_pengajuan_biaya as file_biaya1,
				t.file_laporan_biaya as file_biaya2,
				t.file_laporan_teknisi as laporan_teknisi,
				t.status_tiket,
                t.waktu_mulai,
                t.waktu_selesai,
				t.created_at as tgl_terbit,
                tt.nama as nama_topik,                
                p.nama,
                p1.nama as nama_penerima,  
                p.level,
				COALESCE(pel.identitas_pelanggan, t.pelanggan) as identitas_pelanggan,
				pel.kota as kota
            ')
            ->from('tiket t')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik')
            ->join('pengguna p', 't.id_pembuat=p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima=p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan=pel.id_pelanggan', 'left')
            ->where('t.status_data = 1')
            ->generate();
    }

    function getTiketPembuat($id)
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
                tt.nama as nama_topik,
                t.subject,
                t.prioritas,
                p.nama,
                t.status_tiket,
                p.level
            ')
            ->from('tiket t')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik')
            ->join('pengguna p', 't.id_penerima=p.pengguna_id')
            ->where('t.status_data = 1')
            ->where('t.id_pembuat', $id)
            ->generate();
    }

    function getTiketPenerima($id)
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
				t.kode_tiket,
                tt.nama as nama_topik,
                t.subject,
                t.prioritas,
                t.created_at,
                p.nama,
				t.status_visit,
				t.status_surat_dinas,
				t.file_surat_dinas as file_sudin,
				t.file_pengajuan_biaya as file_biaya1,
				t.file_laporan_biaya as file_biaya2,
				t.status_pembiayaan,
                t.status_tiket,
                p.level,
                pel.kota as kota,
                COALESCE(pel.identitas_pelanggan, t.pelanggan) as identitas_pelanggan
            ')
            ->from('tiket t')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik')
            ->join('pengguna p', 't.id_pembuat=p.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan=pel.id_pelanggan', 'left')
            ->where('t.status_data = 1')
            ->where('t.id_penerima', $id)
            ->generate();
    }

    function getTiketGA()
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
				t.kode_tiket,
                tt.nama as nama_topik,
                t.subject,
                t.created_at,
                t.prioritas,
                p.nama,
				t.status_visit,
				t.status_surat_dinas,
				t.status_pembiayaan,
				t.file_pengajuan_biaya as file_biaya1,
				t.file_laporan_biaya as file_biaya2,
                t.status_tiket,
                t.file_surat_dinas as file_sudin,
                t.file_laporan_teknisi as laporan_teknisi,
                t.catatan_close_tiket as catatan_ct,
                p.level,
                COALESCE(pel.identitas_pelanggan, t.pelanggan) as identitas_pelanggan,
                pel.kota as kota,
                DATE_FORMAT(t.created_at, "%d-%m-%Y") as created_at_formated
            ')
            ->from('tiket t')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik')
            ->join('pengguna p', 't.id_penerima=p.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan=pel.id_pelanggan', 'left')
            ->where('t.status_data = 1')
            ->generate();
    }

    function getTiketFinance()
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
				t.kode_tiket,
                tt.nama as nama_topik,
                t.subject,
                t.created_at,
                t.prioritas,
                p.nama,
				COALESCE(pl.identitas_pelanggan, t.pelanggan) as identitas_pelanggan,
				pl.kota,
				t.status_visit,
				t.status_surat_dinas,
				t.status_pembiayaan,
				t.file_pengajuan_biaya as file_biaya1,
				t.file_laporan_biaya as file_biaya2,
                t.status_tiket,
                t.file_surat_dinas as file_sudin,
                p.level,
                pl.kota as kota
            ')
            ->from('tiket t')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik')
            ->join('pengguna p', 't.id_penerima=p.pengguna_id')
            ->join('pelanggan pl', 't.id_pelanggan=pl.id_pelanggan', 'left')
            ->where('t.status_visit = 2')
            ->where('t.status_surat_dinas = 2')
            ->where('t.status_data = 1')
            ->generate();
    }

    function getTiketPelanggan($id)
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
                pl.identitas_pelanggan as identitas_pelanggan,
                pl.provinsi,
                pl.kota,
                pl.alamat
            ')
            ->from('tiket t')
            ->join('pelanggan p', 't.id_pelanggan=p.id_pelanggan')
            ->where('t.status_data = 1')
            ->where('t.id_pelanggan', $id)
            ->generate();
    }

    function getAllPicSupport($id_tiket)
    {
        $this->db->select('
                tdps.id_tiket,
                tdps.id_pic_support,
				p.nama as nama_pic
            ')
            ->from('tiket_detail_pic_support tdps')
            ->join('pengguna p', 'tdps.id_pic_support=p.pengguna_id')
            ->where('tdps.id_tiket', $id_tiket)
            ->order_by('tdps.id', 'DESC');
        return $this->db->get()->result();
    }


    // Untuk export data ke Excel Data Tiket
    function getAllKegiatan($topik = NULL, $kat = NULL)
    {
        $this->db->order_by('t.id_tiket', 'DESC');
        $query = $this->db
            ->select('
                t.id_tiket,
				t.kode_tiket,
                t.created_at,
                t.id_topik,
				t.subject,
                t.prioritas,
                t.deskripsi,
				t.status_visit,
				t.status_surat_dinas,
				t.status_pembiayaan,
				t.file_surat_dinas as file_sudin,
				t.file_pengajuan_biaya as file_biaya1,
				t.file_laporan_biaya as file_biaya2,
                t.des_peker,
				t.file_laporan_teknisi as laporan_teknisi,
				t.status_tiket,
                t.deskripsi_feedback_cust,
                t.file_feedback_cust,
                t.waktu_mulai,
                t.waktu_selesai,
				t.created_at as tgl_terbit,
                tt.nama as nama_topik,                
                p.nama,
                p1.nama as nama_penerima,  
                p.level,
				COALESCE(pel.identitas_pelanggan, t.pelanggan) as identitas_pelanggan,
				pel.kota as kota

            ')
            ->from('tiket t')
            ->join('pengguna p', 't.id_pembuat=p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima=p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan=pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik')
            ->where('t.status_data = 1');

        // Kondisi jika ada Topik yang dipilih
        if ($topik != NULL) {
            $query->where('t.id_topik', $topik);
        }

        if ($kat != NULL) {
            //$query->where('t.subject LIKE', "%$kat%");
            if ($kat == "Uji Kesesuaian") {
                $query->where("(t.subject LIKE '%Uji Kesesuaian%' OR t.subject LIKE '%UKES%' 
                                OR t.deskripsi LIKE '%Uji Kesesuaian%' OR t.deskripsi LIKE '%UKES%')", NULL, FALSE);
            } else if ($kat == "Uji Paparan") {
                $query->where("(t.subject LIKE '%Uji Paparan%' OR t.subject LIKE '%UPAR%' 
                                OR t.deskripsi LIKE '%Uji Paparan%' OR t.deskripsi LIKE '%UPAR%')", NULL, FALSE);
            } else {
                $query->where("(t.subject LIKE '%$kat%' OR t.deskripsi LIKE '%$kat%')", NULL, FALSE);
            }
        }


        return $query->get()->result();
    }



    //==========================================
    //======== DASHBOARD Tiket =================
    //==========================================

    function countTotalTicket()
    {
        $this->db->select('COUNT(*) as total')
            ->from('tiket t')
            ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
            ->where('t.status_data', 1);

        $query = $this->db->get();
        return $query->row()->total;
    }

    function countTicketOpen()
    {
        $this->db->select('COUNT(*) as total')
            ->from('tiket t')
            ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
            ->where('t.status_data', 1)
            ->where_not_in('t.status_tiket', [4, 5]);

        $query = $this->db->get();
        return $query->row()->total;
    }

    function daftar_tiketOpen()
    {
        $data = $this->db->select('
                t.kode_tiket, 
                COALESCE(pel.identitas_pelanggan, t.pelanggan) as pelanggan, 
                t.id_penerima, 
                t.status_tiket,
                t.id_tiket,
                p1.nama AS nama_penerima,
                p.nama AS nama_pembuat
            ')
            ->from('tiket t')
            ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
            ->where('t.status_data', 1) // Hanya tiket aktif
            ->where_not_in('t.status_tiket', [4, 5]) // Tidak termasuk status tertentu
            ->group_by('t.kode_tiket')
            ->order_by('t.id_tiket', 'DESC')
            ->get()->result();

        return $data;
    }



    function countTicketClosed()
    {
        $this->db->select('COUNT(*) as total')
            ->from('tiket t')
            ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
            ->where('t.status_data', 1)
            ->where_in('t.status_tiket', [4, 5]);

        $query = $this->db->get();
        return $query->row()->total;
    }

    function countTicketSubmit()
    {
        $this->db->select('COUNT(*) as total')
            ->from('tiket t')
            ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
            ->where('t.status_data', 1)
            ->where('t.status_tiket', 2)
            ->where('t.des_peker IS NOT NULL', null, false);

        $query = $this->db->get();
        return $query->row()->total;
    }


    function daftar_tiketSubmit()
    {
        $data = $this->db->select('
                t.kode_tiket, 
                COALESCE(pel.identitas_pelanggan, t.pelanggan) as pelanggan, 
                t.id_penerima, 
                t.status_tiket,
                t.id_tiket,
                p1.nama AS nama_penerima,
                p.nama AS nama_pembuat
            ')
            ->from('tiket t')
            ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
            ->where('t.status_data', 1) // Hanya tiket aktif
            ->where('t.status_tiket', 2)
            ->where('t.des_peker IS NOT NULL', null, false) //Tanda bahwa dia sudah submit laporan Akhir
            ->group_by('t.kode_tiket')
            ->order_by('t.id_tiket', 'DESC')
            ->get()->result();

        return $data;
    }

    function PercentageTicket()
    {
        // Ambil total tiket
        $total_tiket = $this->countTotalTicket();
        // Ambil total tiket closed
        $total_closed = $this->countTicketClosed();
        // Cegah pembagian dengan nol
        if ($total_tiket == 0) {
            return 0;
        }
        // Hitung persentase
        $percentage = ($total_closed / $total_tiket) * 100;
        return round($percentage); // Dibulatkan ke bilangan bulat terdekat
    }


    function getAverageResponseTime()
    {
        $this->db->select('t.id_tiket, t.created_at AS ticket_created, MIN(tu.created_at) AS first_response');
        $this->db->from('tiket t');
        $this->db->join('tiket_update tu', 't.id_tiket = tu.id_tiket', 'inner');
        $this->db->where('t.log_tiket', 1);
        $this->db->where('t.status_data', 1);
        $this->db->where_in('tu.status', [2, 4, 5, 6]);
        $this->db->group_by('t.id_tiket');

        $query = $this->db->get();
        $results = $query->result();

        if (empty($results)) {
            return '0 menit'; // Jika tidak ada data
        }

        $total_time = 0;
        $count = 0;

        foreach ($results as $row) {
            if (!empty($row->first_response)) {
                $ticket_created = strtotime($row->ticket_created);
                $first_response = strtotime($row->first_response);

                if ($first_response > $ticket_created) {
                    $response_time = $first_response - $ticket_created; // Selisih dalam detik
                    $total_time += $response_time;
                    $count++;
                }
            }
        }

        if ($count === 0) {
            return '0 menit'; // Hindari pembagian dengan nol
        }

        $average_response_time = $total_time / $count; // Rata-rata dalam detik

        // Konversi ke Hari, Jam, dan Menit
        $days = floor($average_response_time / (24 * 3600));
        $hours = floor(($average_response_time % (24 * 3600)) / 3600);
        $minutes = floor(($average_response_time % 3600) / 60);

        // Format hasil
        $result = [];
        if ($days > 0) {
            $result[] = "$days hari";
        }
        if ($hours > 0) {
            $result[] = "$hours jam";
        }
        if ($minutes > 0) {
            $result[] = "$minutes menit";
        }

        return !empty($result) ? implode(' ', $result) : '0 menit';
    }

    public function getAvgResponTime()
    {
        $technicians = [
            'Anggi' => 736,
            'Deni' => 99,
            'Ricky Arindi' => 14,
            'Rizki Sabtu' => 25
        ];

        $result = [];

        foreach ($technicians as $name => $id) {
            $subquery = $this->db->select('t.id_tiket, MIN(tu.created_at) AS first_response')
                ->from('tiket t')
                ->join('tiket_update tu', 't.id_tiket = tu.id_tiket', 'inner')
                ->where('t.id_penerima', $id)
                ->where('t.log_tiket', 1)
                ->where('t.status_data', 1)
                ->where('tu.status', 2) //Hanya Ambil Status Update Tiket
                //->where_in('tu.status', [2, 4, 5, 6])
                ->where('t.created_at >=', '2025-03-17')
                ->group_by('t.id_tiket')
                ->get_compiled_select();

            $query = $this->db->query("SELECT AVG(TIMESTAMPDIFF(SECOND, t.created_at, sub.first_response)) AS avg_response_time FROM tiket t JOIN ($subquery) sub ON t.id_tiket = sub.id_tiket");

            $row = $query->row();
            $result[$name] = $row ? round($row->avg_response_time / 60, 2) : 0; // Konversi ke menit
            //$result[$name] = $row ? round($row->avg_response_time / 3600, 2) : 0; // Konversi ke jam

        }

        return $result;
    }




    public function get_ticket_count_by_technician()
    {
        $technicians = [
            'Anggi' => 736,
            'Deni' => 99,
            'Ricky Arindi' => 14,
            'Rizki Sabtu' => 25
        ];

        $result = [];

        // Hitung jumlah tiket berdasarkan teknisi yang sudah ditentukan
        foreach ($technicians as $name => $id) {
            $this->db->from('tiket t')
                ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
                ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
                ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
                ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
                ->where('t.id_penerima', $id)
                ->where('t.status_data', 1);
            $result[$name] = $this->db->count_all_results();
        }

        // Hitung jumlah tiket untuk kategori "Other" (id_penerima selain dari yang disebutkan)
        $this->db->from('tiket t')
            ->join('pengguna p', 't.id_pembuat = p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima = p1.pengguna_id')
            ->join('pelanggan pel', 't.id_pelanggan = pel.id_pelanggan', 'left')
            ->join('topik_tiket tt', 't.id_topik = tt.id_topik')
            ->where_not_in('t.id_penerima', array_values($technicians)) // Filter selain teknisi yang ada
            ->where('t.status_data', 1);
        $result['Other'] = $this->db->count_all_results();

        return $result;
    }

    public function get_ticket_by_category()
    {
        $categories = [
            'Instalasi' => "t.subject LIKE '%Instalasi%' OR t.deskripsi LIKE '%Instalasi%'",
            'Trouble' => "t.subject LIKE '%Trouble%' OR t.deskripsi LIKE '%Trouble%'",
            'UKES' => "t.subject LIKE '%Uji Kesesuaian%' OR t.subject LIKE '%UKES%' OR t.deskripsi LIKE '%Uji Kesesuaian%' OR t.deskripsi LIKE '%UKES%'",
            'UPAR' => "t.subject LIKE '%Uji Paparan%' OR t.subject LIKE '%UPAR%' OR t.deskripsi LIKE '%Uji Paparan%' OR t.deskripsi LIKE '%UPAR%'"
        ];

        $result = [];
        foreach ($categories as $key => $condition) {
            $this->db->from('tiket t')
                ->where('t.status_data', 1)
                ->where("($condition)", NULL, FALSE);
            $result[$key] = $this->db->count_all_results();
        }

        return $result; // Pastikan mengembalikan array, bukan mencetak JSON
    }



    private $alat_list = [
        20 => "Alerio Smart 4000",
        30 => "Console Prima T2",
        18 => "CR Dental JPI",
        28 => "CR Prima",
        44 => "DR JPI Examvue Duo",
        6  => "DRYPIX Lite",
        7  => "DRYPIX Smart",
        33 => "Drypix Edge",
        //5  => "FCR Capsula XLII",
        3  => "FCR Prima T2",
        4  => "FCR Prima TM",
        //11 => "FDR D EVO GL",
        9  => "FDR D-EVO II",
        12 => "FDR Smart X",
        15 => "FDR X-Air",
        26 => "FDL SE LITE",
        42 => "KPACS"
    ];

    public function get_detail_ticket()
    {
        $result = [];

        foreach ($this->alat_list as $id_topik => $nama_alat) {
            // Query untuk kategori Instalasi
            $this->db->from('tiket');
            $this->db->where('status_data', 1);
            $this->db->where('id_topik', $id_topik);
            $this->db->where("(subject LIKE '%Instalasi%' OR deskripsi LIKE '%Instalasi%')", NULL, FALSE);
            $instalasi_count = $this->db->count_all_results();

            // Query untuk kategori Trouble
            $this->db->from('tiket');
            $this->db->where('status_data', 1);
            $this->db->where('id_topik', $id_topik);
            $this->db->where("(subject LIKE '%Trouble%' OR deskripsi LIKE '%Trouble%')", NULL, FALSE);
            $trouble_count = $this->db->count_all_results();

            // Simpan hasil ke array
            $result[] = [
                'alat' => $nama_alat,
                'instalasi' => $instalasi_count,
                'trouble' => $trouble_count
            ];
        }

        return $result;
    }

    function getFirstUpdate($kode_tiket)
    {
        $this->db->select('created_at');
        $this->db->from('tiket_update');
        $this->db->where('kode_tiket', $kode_tiket);
        $this->db->where('status !=', 1);
        $this->db->order_by('created_at', 'ASC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    // function getUpdateById($where)
    // {
    //     $this->db->select('
    //         tu.kode_tiket,
    //         tu.id_pembuat,
    //         tu.update,
    //         tu.status,
    //         tu.file_update,
    //         tu.created_at as waktu,
    //         t.kode_tiket,
    //         t.subject,
    //         t.id_tiket,
    //         p1.nama as nama_pembuat
    //     ');
    //     $this->db->from('tiket t');
    //     $this->db->join('tiket_update tu', 't.kode_tiket=tu.kode_tiket');
    //     $this->db->join('pengguna p1', 'tu.id_pembuat=p1.pengguna_id');
    //     $this->db->where($where);
    //     $this->db->order_by('tu.created_at', 'DESC');
    //     return $this->db->get()->result();
    // }

    //==========================================
    //======== End DASHBOARD Tiket =============
    //==========================================
}
