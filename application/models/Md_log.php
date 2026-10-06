<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_log extends CI_Model {

    function getTop30Activity($login_type){
        if($login_type!='Administrator' and $login_type!='Keuangan' and $login_type!='Eksekutif'){
            $pengguna_id = $this->session->userdata('pengguna_id');
            $this->db->where('lg.pengguna_id',$pengguna_id);
        }
        $this->db->limit(30);
        $this->db->join('pengguna pg','pg.pengguna_id = lg.pengguna_id');
        $this->db->order_by('lg.log_id','desc');
        $this->db->where('lg.status',1);
        $this->db->from('log lg');
        return $this->db->get()->result();
    }

    function getByWhere($where) {
        return $this->db->get_where('log', $where)->result();
    }

    function getLogLoginBytgl($tgl){
        $this->db->order_by('tgl','desc');
        $this->db->where('tgl',$tgl);
        $this->db->where('jenis_aksi','Login');
        $this->db->where('status',1);
        $this->db->from('log');
        $hasil = $this->db->get()->result();
        return $hasil; 
    }
    
    function addLog($data){
         $this->db->insert('log', $data);
         if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function addLog_transact($data){
        $this->db->insert('log', $data);
    }
    
    function getAllLog11()
{
    $columns = ['lg.log_id', 'pg.nama', 'lg.jenis_aksi', 'lg.keterangan', 'lg.tgl'];
    $order_column = $columns[$_POST['order'][0]['column']];
    $order_dir = $_POST['order'][0]['dir'];

    $this->db->select('lg.log_id, pg.nama as nama_pengguna, lg.jenis_aksi, lg.keterangan, lg.tgl');
    $this->db->from('log lg');
    $this->db->join('pengguna pg', 'pg.pengguna_id = lg.pengguna_id', 'left');

    // Filter
    if ($this->input->post('filter_month')) {
        $this->db->where("DATE_FORMAT(lg.tgl,'%Y-%m')", $this->input->post('filter_month'));
    }
    if ($this->uri->segment(1) == 'dashboard') {
        $this->db->where("lg.pengguna_id", sessPenggunaId());
    }
    if (isAdmin() == FALSE) {
        $this->db->where("lg.pengguna_id !=", 15);
    }

    // Total tanpa filter
    $total = $this->db->count_all_results('', FALSE);

    // Ordering
    $this->db->order_by($order_column, $order_dir);

    // Pagination
    $this->db->limit($_POST['length'], $_POST['start']);
    $data = $this->db->get()->result();

    // Filtered total
    $this->db->reset_query(); // penting: reset filter
    $this->db->select('COUNT(*) as jumlah');
    $this->db->from('log lg');
    $this->db->join('pengguna pg', 'pg.pengguna_id = lg.pengguna_id', 'left');
    if ($this->input->post('filter_month')) {
        $this->db->where("DATE_FORMAT(lg.tgl,'%Y-%m')", $this->input->post('filter_month'));
    }
    if ($this->uri->segment(1) == 'dashboard') {
        $this->db->where("lg.pengguna_id", sessPenggunaId());
    }
    if (isAdmin() == FALSE) {
        $this->db->where("lg.pengguna_id !=", 15);
    }
    $filtered = $this->db->get()->row()->jumlah;

    return [
        "draw" => intval($_POST['draw']),
        "recordsTotal" => $total,
        "recordsFiltered" => $filtered,
        "data" => $data
    ];
}


    function getDistinctAksi(){
        $this->db->distinct();
        $this->db->select('jenis_aksi');
        $this->db->from('log');
        $this->db->where('jenis_aksi IS NOT NULL');
        $this->db->where('jenis_aksi !=', '');
        $this->db->order_by('jenis_aksi', 'ASC');
        return $this->db->get()->result();
    }

    function getSummaryStats($month = null){
        if (!$month) {
            $month = date('Y-m');
        }
        $today = date('Y-m-d');
        
        // Total Hari Ini
        $this->db->where("tgl LIKE", $today . '%');
        if (isAdmin() == FALSE) {
            $this->db->where("pengguna_id !=", 15);
        }
        $total_today = $this->db->count_all_results('log');

        // Total Bulan Ini
        $this->db->where("tgl LIKE", $month . '%');
        if (isAdmin() == FALSE) {
            $this->db->where("pengguna_id !=", 15);
        }
        $total_month = $this->db->count_all_results('log');

        // Total Pengguna Aktif Bulan Ini
        $this->db->select('COUNT(DISTINCT pengguna_id) as total_user');
        $this->db->where("tgl LIKE", $month . '%');
        $this->db->where("pengguna_id IS NOT NULL");
        if (isAdmin() == FALSE) {
            $this->db->where("pengguna_id !=", 15);
        }
        $res_user = $this->db->get('log')->row();
        $total_user = $res_user ? $res_user->total_user : 0;

        return [
            'today' => $total_today,
            'month' => $total_month,
            'user' => $total_user
        ];
    }

    function getAllLog(){
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_selesai = $this->input->post('tgl_selesai');
        $filter_month = $this->input->post('filter_month');
        $pengguna_id = $this->input->post('pengguna_id');
        $jenis_aksi = $this->input->post('jenis_aksi');

        if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
            $this->datatables->where("lg.tgl >=", $tgl_mulai . ' 00:00:00');
            $this->datatables->where("lg.tgl <=", $tgl_selesai . ' 23:59:59');
        } else if (!empty($filter_month)){
            $this->datatables->where("lg.tgl LIKE", $filter_month . '%');
        }

        if (!empty($pengguna_id)) {
            $this->datatables->where("lg.pengguna_id", $pengguna_id);
        }

        if (!empty($jenis_aksi)) {
            $this->datatables->where("lg.jenis_aksi", $jenis_aksi);
        }

        if ($this->uri->segment(1) == 'dashboard'){
            $this->datatables->where("lg.pengguna_id", sessPenggunaId());
        }

        if (isAdmin() == FALSE)
        {
            $this->datatables->where("lg.pengguna_id !=", 15);
        }
        
        return $this->datatables
        ->select('  
            lg.log_id,
            lg.pengguna_id,
            pg.nama as nama_pengguna,
            lg.jenis_aksi,
            lg.keterangan,
            lg.tgl,
            lg.ip_addr
        ')
        ->from('log lg')
        ->join('pengguna pg','pg.pengguna_id = lg.pengguna_id','left')
        ->generate();        
    }


    function getAllLogVisilab(){
        // Cek filter bulan jika ada
        if ($this->input->post('filter_month')) {
            $this->datatables->where("DATE_FORMAT(lg.tgl, '%Y-%m')", $this->input->post('filter_month'));
        }

        return $this->datatables
            ->select('  
                lg.log_id,
                pg.nama as nama_pengguna,
                lg.jenis_aksi,
                lg.keterangan,
                lg.tgl
            ')
            ->from('log lg')
            ->join('pengguna pg', 'pg.pengguna_id = lg.pengguna_id', 'left') // Left join untuk menampilkan semua pengguna
            ->where('lg.jenis_aksi LIKE', '%Visilab%')  // Filter hanya untuk jenis aksi yang mengandung kata 'Visilab'
            ->generate();
    }



}