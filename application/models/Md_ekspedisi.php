<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_ekspedisi extends CI_Model {

    function add($data)
    {
        $this->db->insert('ekspedisi', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('ekspedisi', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('ekspedisi e', array('e.id_ekspedisi' => $id))->result();
    }

    function getByWhere($param="") 
    {
        $this->db->where('e.status', 1);
        return $this->db->get('ekspedisi e')->result();
    }

    function getAll(){
            
        return $this->datatables
        ->select('  
            e.id_ekspedisi,
            e.nama_ekspedisi,
            e.alamat_ekspedisi,
            e.contact,
            e.nama_pic,
            e.jabatan,
            e.mou,
            e.legalitas,
            e.identitas,
            e.link_tracking,
            e.keterangan,
            e.coverage,
            e.status,
            e.payment,
            e.pph23,
            e.min_berat,
            e.berat,
            e.kerjasama

        ')
        ->from('ekspedisi e')
        ->where('e.status = 1')
        ->generate();        
    }

    function getEkspedisiByTgl($tglawal,$tglakhir)
    { 
		$this->db->order_by('e.id_ekspedisi', 'DESC');
        return $this->db
                ->select('
                e.id_ekspedisi,
                e.nama_ekspedisi,
                e.alamat_ekspedisi,
                e.contact,
                e.nama_pic,
                e.jabatan,
                e.mou,
                e.legalitas,
                e.identitas,
                e.link_tracking,
                e.keterangan,
                e.status,
                e.data_created
            ')
            ->from('ekspedisi e')
            ->where('e.status = 1')
            ->where('e.data_created >=', date('Y-m-d', strtotime($tglawal)))
            ->where('e.data_created <=', date('Y-m-d', strtotime($tglakhir)))
			->get()
			->result();
    }


    
//==================================================================
//==================================================================
//================= Perbandingan Ekspedisi =========================
//==================================================================
//==================================================================


//==== Destinasi (Kabupaten/Kota) ======
function getAllDestinasi11(){
    return $this->datatables
        ->select('  
            d.id,
            d.provinsi,
            d.kab_kota
        ')
        ->from('ekspedisi_destinasi d')
        ->generate();        
}

function getAllDestinasi()
{
    $searchArray = $this->input->post('search', TRUE);
    $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

    $this->db->select('d.id, d.provinsi, d.kab_kota');
    $this->db->from('ekspedisi_destinasi d');

    // Search: cari di kolom provinsi atau kab_kota
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    // Order dari DataTables
    $orderColumn = 'd.provinsi, d.kab_kota';
    $orderDir = 'ASC';
    $columns = ['d.id', 'd.provinsi', 'd.kab_kota']; // urut sesuai kolom DataTables

    if (isset($_POST['order']) && !empty($_POST['order'])) {
        $colIndex = $_POST['order'][0]['column'];
        $dir = $_POST['order'][0]['dir'];
        if (isset($columns[$colIndex])) {
            $orderColumn = $columns[$colIndex];
            $orderDir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
        }
    }

    $this->db->order_by($orderColumn, $orderDir);

    // Pagination
    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    $data = $this->db->get()->result();

    // Hitung total semua data
    $recordsTotal = $this->db->count_all('ekspedisi_destinasi');

    // Hitung data setelah filter
    $this->db->select('COUNT(*) as count');
    $this->db->from('ekspedisi_destinasi d');

    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    $recordsFiltered = $this->db->get()->row()->count;

    return [
        'draw' => intval($this->input->post('draw')),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data
    ];
}


function addDestinasi($data)
{
    $this->db->insert('ekspedisi_destinasi', $data);
}

function cekDestinasiExist($id) {
    return $this->db->where('id', $id)->get('ekspedisi_destinasi')->num_rows() > 0;
}


function getByIdDestinasi($id)
  {
        return $this->db->get_where('ekspedisi_destinasi p', array('p.id' => $id))->result();
  }

function updateDestinasi($id, $data) {
    return $this->db->where('id', $id)->update('ekspedisi_destinasi', $data);
}


function getAllDestinasiExport()
{
    return $this->db
        ->select('
            id,
            provinsi,
            kab_kota
        ')
        ->from('ekspedisi_destinasi')
        ->order_by('provinsi', 'ASC')
        ->order_by('kab_kota', 'ASC')
        ->get()
        ->result();
}


public function getUniqueProvinsi()
{
    $this->db->select('provinsi');
    $this->db->group_by('provinsi');
    $this->db->order_by('provinsi', 'ASC');
    return $this->db->get('ekspedisi_destinasi')->result();
}

function getByIdDeleteDestinasi($id)
{
    return $this->db->get_where('ekspedisi_destinasi p', array('p.id' => $id))->row();
}

function deleteDestinasi($id)
{
    $this->db->where('id', $id); 
    return $this->db->delete('ekspedisi_destinasi');
}
//==== END Destinasi (Kabupaten/Kota) ======


//======= Berat & Acuan Ongkir ========
function getAllAcuanOngkir11(){
    return $this->datatables
        ->select('  
            a.id,
            a.brand,
            a.nama,
            a.berat,
            a.dimensi,
            a.berat_dimensi,
            a.acuan_berat,
            a.acuan_ongkir_maksimal,
            a.created_at
        ')
        ->from('acuan_ongkir a')
        ->generate();        
}

function getAllAcuanOngkir()
{
    $searchArray = $this->input->post('search', TRUE);
    $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

    $this->db->select('a.id, a.brand, a.nama, a.berat, a.dimensi, a.berat_dimensi, a.acuan_berat, a.acuan_ongkir_maksimal, a.created_at');
    $this->db->from('acuan_ongkir a');

    // Search: cari di kolom brand, nama, atau acuan_berat
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('a.brand', $keyword);
        $this->db->or_like('a.nama', $keyword);
        $this->db->or_like('a.acuan_berat', $keyword);
        $this->db->group_end();
    }

    // Order default
    $orderColumn = 'a.brand, a.nama';
    $orderDir = 'ASC';
    $columns = ['a.id', 'a.brand', 'a.nama', 'a.berat', 'a.dimensi', 'a.berat_dimensi', 'a.acuan_berat', 'a.acuan_ongkir_maksimal', 'a.created_at'];

    if (isset($_POST['order']) && !empty($_POST['order'])) {
        $colIndex = $_POST['order'][0]['column'];
        $dir = $_POST['order'][0]['dir'];
        if (isset($columns[$colIndex])) {
            $orderColumn = $columns[$colIndex];
            $orderDir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
        }
    }

    $this->db->order_by($orderColumn, $orderDir);

    // Pagination
    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    $data = $this->db->get()->result();

    // Total records
    $recordsTotal = $this->db->count_all('acuan_ongkir');

    // Filtered records
    $this->db->select('COUNT(*) as count');
    $this->db->from('acuan_ongkir a');

    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('a.brand', $keyword);
        $this->db->or_like('a.nama', $keyword);
        $this->db->or_like('a.acuan_berat', $keyword);
        $this->db->group_end();
    }

    $recordsFiltered = $this->db->get()->row()->count;

    return [
        'draw' => intval($this->input->post('draw')),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data
    ];
}


function addAcuanOngkir($data)
{
    $this->db->insert('acuan_ongkir', $data);
}

function getByIdAcuanOngkir($id)
{
     return $this->db->get_where('acuan_ongkir p', array('p.id' => $id))->result();
}

public function getByIdBarang($id)
{
    return $this->db->get_where('acuan_ongkir p', array('p.id' => $id))->row(); // pakai row() karena ambil 1 data
}


function updateAcuanOngkir($id, $data) {
    return $this->db->where('id', $id)->update('acuan_ongkir', $data);
}

function getByIdDeleteAcuanOngkir($id)
{
    return $this->db->get_where('acuan_ongkir p', array('p.id' => $id))->row();
}

function deleteAcuanOngkir($id)
{
    $this->db->where('id', $id); 
    return $this->db->delete('acuan_ongkir');
}

//======= END Berat & Acuan Ongkir ========

//======= Pricelist Ekspedisi ========
function getAllPricelist($id)
{
    $searchArray = $this->input->post('search', TRUE);
    $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

    $this->db->select('
        d.id,
        d.provinsi,
        d.kab_kota,
        p.pku,
        p.jogja,
        p.jkt,
        p.est_pku,
        p.est_jogja,
        p.est_jkt,
        p.fix_pku,
        p.fix_jogja,
        p.fix_jkt,
        p.id as idPrice
    ');
    $this->db->from('ekspedisi_destinasi d');
    $this->db->join('ekspedisi_pricelist p', 'p.id_destinasi = d.id AND p.id_ekspedisi = ' . $this->db->escape($id), 'left');

    // Search di kolom provinsi dan kab_kota
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    // Order dari DataTables
    $orderColumn = 'd.provinsi, d.kab_kota';
    $orderDir = 'ASC';
    $columns = ['d.id', 'd.provinsi', 'd.kab_kota', 'p.pku', 'p.est_pku', 'p.jogja', 'p.est_jogja', 'p.jkt', 'p.est_jkt'];

    if (isset($_POST['order']) && !empty($_POST['order'])) {
        $colIndex = $_POST['order'][0]['column'];
        $dir = $_POST['order'][0]['dir'];
        if (isset($columns[$colIndex])) {
            $orderColumn = $columns[$colIndex];
            $orderDir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
        }
    }

    $this->db->order_by($orderColumn, $orderDir);

    // Pagination
    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    $data = $this->db->get()->result();

    // Hitung total semua (tanpa filter dan join)
    $recordsTotal = $this->db->count_all('ekspedisi_destinasi');

    // Hitung total setelah filter
    $this->db->select('COUNT(*) as count');
    $this->db->from('ekspedisi_destinasi d');
    $this->db->join('ekspedisi_pricelist p', 'p.id_destinasi = d.id AND p.id_ekspedisi = ' . $this->db->escape($id), 'left');

    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    $recordsFiltered = $this->db->get()->row()->count;

    return [
        'draw' => intval($this->input->post('draw')),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data
    ];
}


function getPricelistById($id)
{
	$this->db->select('
		d.id AS id_destinasi,
		d.provinsi,
		d.kab_kota,
		p.id AS id_pricelist,
		p.id_ekspedisi,
		p.pku,
		p.jogja,
		p.jkt
	');
	$this->db->from('ekspedisi_destinasi d');
	$this->db->join('ekspedisi_pricelist p', 'p.id_destinasi = d.id');
	$this->db->where('p.id_ekspedisi', $id);

	return $this->db->get()->result(); // result() karena bisa banyak baris
}


function getAllPricelistExport($idEkspedisi)
{
    return $this->db
        ->select('
            d.id AS idEd,
            d.provinsi,
            d.kab_kota,
            p.id_ekspedisi AS idEkspedisi,
            p.pku,
            p.jkt,
            p.jogja,
            p.est_pku,
            p.est_jogja,
            p.est_jkt,
            p.fix_pku,
            p.fix_jogja,
            p.fix_jkt
        ')
        ->from('ekspedisi_destinasi d')
        ->join('ekspedisi_pricelist p', 'p.id_destinasi = d.id AND p.id_ekspedisi = ' . $this->db->escape($idEkspedisi), 'left')
        ->order_by('d.provinsi', 'ASC')
        ->order_by('d.kab_kota', 'ASC')
        ->get()
        ->result();
}


function cekPricelistExist($id_destinasi, $id_ekspedisi) {
    return $this->db
        ->where('id_destinasi', $id_destinasi)
        ->where('id_ekspedisi', $id_ekspedisi)
        ->get('ekspedisi_pricelist')
        ->num_rows() > 0;
}

function addPricelist($data)
{
    $this->db->insert('ekspedisi_pricelist', $data);
}

function updatePricelist($id_destinasi, $id_ekspedisi, $data) {
    return $this->db
        ->where('id_destinasi', $id_destinasi)
        ->where('id_ekspedisi', $id_ekspedisi)
        ->update('ekspedisi_pricelist', $data);
}



function getByIdPricelist($id)
{
    $this->db->select('p.id AS idPrice, p.id_destinasi, p.id_ekspedisi, p.pku, p.jkt, p.jogja,  p.fix_pku, p.fix_jkt, p.fix_jogja,  p.est_pku, p.est_jkt, p.est_jogja, d.provinsi, d.kab_kota');
    $this->db->from('ekspedisi_pricelist p');
    $this->db->join('ekspedisi_destinasi d', 'd.id = p.id_destinasi', 'left');
    $this->db->where('p.id', $id);
    return $this->db->get()->result(); // ← pakai result() agar foreach tidak error

}

function updatePricelist2($id, $data) {
    return $this->db->where('id', $id)->update('ekspedisi_pricelist', $data);
}


public function getByIdEkspedisi($id)
{
    return $this->db->get_where('ekspedisi p', array('p.id_ekspedisi' => $id))->row(); // pakai row() karena ambil 1 data
}






//======= END Pricelist Ekspedisi ========


//======= Berdasarkan Nama Ekspedisi ========
function getAllNamaEkspedisi($id)
{
    $searchArray = $this->input->post('search', TRUE);
    $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

    $this->db->select('
        d.id,
        d.provinsi,
        d.kab_kota,
        p.pku,
        p.jogja,
        p.jkt,
        p.id as idPrice
    ');
    $this->db->from('ekspedisi_destinasi d');
    $this->db->join('ekspedisi_pricelist p', 'p.id_destinasi = d.id AND p.id_ekspedisi = ' . $this->db->escape($id), 'left');

    // Search di kolom provinsi dan kab_kota
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    // Order dari DataTables
    $orderColumn = 'd.provinsi, d.kab_kota';
    $orderDir = 'ASC';
    $columns = ['d.id', 'd.provinsi', 'd.kab_kota', 'p.pku', 'p.jogja', 'p.jkt'];

    if (isset($_POST['order']) && !empty($_POST['order'])) {
        $colIndex = $_POST['order'][0]['column'];
        $dir = $_POST['order'][0]['dir'];
        if (isset($columns[$colIndex])) {
            $orderColumn = $columns[$colIndex];
            $orderDir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
        }
    }

    $this->db->order_by($orderColumn, $orderDir);

    // Pagination
    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    $data = $this->db->get()->result();

    // Hitung total semua (tanpa filter dan join)
    $recordsTotal = $this->db->count_all('ekspedisi_destinasi');

    // Hitung total setelah filter
    $this->db->select('COUNT(*) as count');
    $this->db->from('ekspedisi_destinasi d');
    $this->db->join('ekspedisi_pricelist p', 'p.id_destinasi = d.id AND p.id_ekspedisi = ' . $this->db->escape($id), 'left');

    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    $recordsFiltered = $this->db->get()->row()->count;

    return [
        'draw' => intval($this->input->post('draw')),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data
    ];
}


public function getAllPricelistByAsal($asal)
{
    $asal = in_array($asal, ['pku', 'jkt', 'jogja']) ? $asal : 'pku';

    $searchArray = $this->input->post('search', TRUE);
    $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

    // Ambil semua ekspedisi yang punya ongkir untuk asal ini
    $expedisi = $this->db->query("
        SELECT e.id_ekspedisi, e.nama_ekspedisi
        FROM ekspedisi e
        INNER JOIN ekspedisi_pricelist p ON p.id_ekspedisi = e.id_ekspedisi
        WHERE p.$asal IS NOT NULL AND p.$asal != ''
        GROUP BY e.id_ekspedisi
        ORDER BY e.nama_ekspedisi ASC
    ")->result();

    // Buat index untuk memetakan ekspedisi ke posisi kolom
    $expedisi_map = [];
    foreach ($expedisi as $i => $e) {
        $expedisi_map[$e->id_ekspedisi] = [
            'index' => $i,
            'nama' => $e->nama_ekspedisi
        ];
    }

    // Ambil data destinasi
    $this->db->select('d.id, d.provinsi, d.kab_kota');
    $this->db->from('ekspedisi_destinasi d');

    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    $this->db->order_by('d.provinsi, d.kab_kota');

    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    $destinasi = $this->db->get()->result();

    $result = [];
    foreach ($destinasi as $d) {
        $row = [
            'provinsi' => $d->provinsi,
            'kab_kota' => $d->kab_kota,
            'harga' => [],
            'estimasi' => []
        ];

        foreach ($expedisi_map as $id_ekspedisi => $info) {
            $est_col = "est_" . $asal;
            $fix_col = "fix_" . $asal;

            $q = $this->db
                ->select("$asal, $est_col, $fix_col")
                ->from('ekspedisi_pricelist')
                ->where('id_destinasi', $d->id)
                ->where('id_ekspedisi', $id_ekspedisi)
                ->get()
                ->row();


            /*// Harga
            $row['harga'][$info['index']] = ($q && $q->$asal !== null && $q->$asal !== '')
                ? 'Rp ' . number_format($q->$asal, 0, ',', '.')
                : '-';

            // Estimasi
            $est_col = 'est_' . $asal;
            $row['estimasi'][$info['index']] = ($q && $q->$est_col !== null && $q->$est_col !== '')
                ? $q->$est_col . ' hari'
                : '-';*/

                $harga_value = ($q && $q->$asal !== null && $q->$asal !== '')
                    ? 'Rp ' . number_format($q->$asal, 0, ',', '.')
                    : '-';

                $estimasi_value = ($q && $q->$est_col !== null && $q->$est_col !== '')
                    ? $q->$est_col . ' hari'
                    : '-';

                $fix = ($q && isset($q->$fix_col) && $q->$fix_col == 1);

                $row['harga'][$info['index']]    = $fix ? '<strong>' . $harga_value . '</strong>' : $harga_value;
                $row['estimasi'][$info['index']] = $fix ? '<strong>' . $estimasi_value . '</strong>' : $estimasi_value;




        }

        $result[] = $row;
    }

    // Total data
    $recordsTotal = $this->db->count_all('ekspedisi_destinasi');

    // Total data setelah filter
    $this->db->select('COUNT(*) as count');
    $this->db->from('ekspedisi_destinasi d');
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }
    $recordsFiltered = $this->db->get()->row()->count;

    return [
        'draw' => intval($this->input->post('draw')),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $result,
        'ekspedisi' => array_values(array_column($expedisi_map, 'nama'))
    ];
}



public function getAllPricelistBarangByAsal($asal, $id_acuan)
{
    $asal = in_array($asal, ['pku', 'jkt', 'jogja']) ? $asal : 'pku';

    $searchArray = $this->input->post('search', TRUE);
    $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';

    // Ambil data acuan
    $acuan = $this->db->get_where('acuan_ongkir', ['id' => $id_acuan])->row();
    if (!$acuan) {
        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'ekspedisi' => []
        ];
    }
    $berat_dipakai = ($acuan->acuan_berat == 2) ? $acuan->berat_dimensi : $acuan->berat;

    // Ambil semua ekspedisi yang punya ongkir untuk asal ini
    $expedisi = $this->db->query("
        SELECT e.id_ekspedisi, e.nama_ekspedisi, e.berat as berat_min
        FROM ekspedisi e
        INNER JOIN ekspedisi_pricelist p ON p.id_ekspedisi = e.id_ekspedisi
        WHERE p.$asal IS NOT NULL AND p.$asal != ''
        GROUP BY e.id_ekspedisi
        ORDER BY e.nama_ekspedisi ASC
    ")->result();

    // Buat map ekspedisi
    $expedisi_map = [];
    foreach ($expedisi as $i => $e) {
        $expedisi_map[$e->id_ekspedisi] = [
            'index' => $i,
            'nama' => $e->nama_ekspedisi,
            'berat_min' => (float)$e->berat_min
        ];
    }

    // Ambil semua destinasi
    $this->db->select('d.id, d.provinsi, d.kab_kota');
    $this->db->from('ekspedisi_destinasi d');
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }

    $this->db->order_by('d.provinsi, d.kab_kota');
    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    $destinasi = $this->db->get()->result();

    $result = [];
    foreach ($destinasi as $d) {
        $row = [
            'provinsi' => $d->provinsi,
            'kab_kota' => $d->kab_kota,
            'harga' => []
        ];

        foreach ($expedisi_map as $id_ekspedisi => $info) {
            $harga_row = $this->db->select($asal)
                ->from('ekspedisi_pricelist')
                ->where('id_destinasi', $d->id)
                ->where('id_ekspedisi', $id_ekspedisi)
                ->get()->row();

            $harga_satuan = ($harga_row && $harga_row->$asal !== null && $harga_row->$asal !== '') ? (float)$harga_row->$asal : null;

            if ($harga_satuan === null) {
                $row['harga'][$info['index']] = '-';
            } else {
                $berat_min = $info['berat_min'] ?: 1;

                $total_harga = ($berat_dipakai <= $berat_min)
                    ? $harga_satuan * $berat_min
                    : $harga_satuan * $berat_dipakai;

                $row['harga'][$info['index']] = 'Rp ' . number_format($total_harga, 0, ',', '.');
            }
        }

        $result[] = $row;
    }

    // Total semua baris
    $recordsTotal = $this->db->count_all('ekspedisi_destinasi');

    // Total filter
    $this->db->select('COUNT(*) as count');
    $this->db->from('ekspedisi_destinasi d');
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('d.provinsi', $keyword);
        $this->db->or_like('d.kab_kota', $keyword);
        $this->db->group_end();
    }
    $recordsFiltered = $this->db->get()->row()->count;

    return [
        'draw' => intval($this->input->post('draw')),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $result,
        'ekspedisi' => array_values(array_column($expedisi_map, 'nama'))
    ];
}

public function getDataPerbandinganOLD($id_destinasi, $jumlah, $acuan_berat, $layanan, $asal)
{
    $this->db->select('
        e.nama_ekspedisi,
        e.berat as berat_ekspedisi,
        p.id_ekspedisi,
        p.est_' . $asal . ' as estimasi,
        p.' . $asal . ' as harga_awal
    ');
    $this->db->from('ekspedisi_pricelist p');
    $this->db->join('ekspedisi e', 'e.id_ekspedisi = p.id_ekspedisi', 'left');
    $this->db->where('p.id_destinasi', $id_destinasi);

    $query = $this->db->get()->result();

    $result = [];
    foreach ($query as $row) {
        $berat_barang = $acuan_berat * $jumlah;
        $berat_final = ($berat_barang < $row->berat_ekspedisi) ? $row->berat_ekspedisi : $berat_barang;

        // Jika harga_awal NULL atau 0, skip ekspedisi ini
        if ($row->harga_awal === null || $row->harga_awal <= 0) {
            continue;
        }

        // RUMUS ID 13 JnT Cargo
        if ($row->id_ekspedisi == 13) {
            if ($layanan == 2) {
                $harga_final = ($row->harga_awal * $berat_final) + 5000;
            } elseif ($layanan == 1) {
                $harga_final = ($row->harga_awal * $berat_final) + 200000;
            }
        } else {
            $harga_final = $row->harga_awal * $berat_final;
        }

        $result[] = (object)[
            'nama_ekspedisi'    => $row->nama_ekspedisi,
            'estimasi'          => $row->estimasi,
            'harga_final'       => $harga_final,
            'berat_ekspedisi'   => $row->berat_ekspedisi
        ];
    }


    return $result;
}


public function getDataPerbandingan($id_destinasi, $jumlah, $acuan_berat, $layanan, $asal)
{
    $fix_field = 'fix_' . $asal;

    $this->db->select("
        e.nama_ekspedisi,
        e.berat as berat_ekspedisi,
        p.id_ekspedisi,
        p.est_$asal as estimasi,
        p.$asal as harga_awal,
        p.$fix_field as fix
    ");
    $this->db->from('ekspedisi_pricelist p');
    $this->db->join('ekspedisi e', 'e.id_ekspedisi = p.id_ekspedisi', 'left');
    $this->db->where('p.id_destinasi', $id_destinasi);

    $query = $this->db->get()->result();

    $result = [];
    foreach ($query as $row) {
        $berat_barang = $acuan_berat * $jumlah;
        $berat_final = ($berat_barang < $row->berat_ekspedisi) ? $row->berat_ekspedisi : $berat_barang;

        if ($row->harga_awal === null || $row->harga_awal <= 0) {
            continue;
        }

        if ($row->id_ekspedisi == 13) {
            if ($layanan == 2) {
                $harga_final = ($row->harga_awal * $berat_final) + 5000;
            } elseif ($layanan == 1) {
                $harga_final = ($row->harga_awal * $berat_final) + 200000;
            }
        } else {
            $harga_final = $row->harga_awal * $berat_final;
        }

        $result[] = (object)[
            'nama_ekspedisi'    => $row->nama_ekspedisi,
            'estimasi'          => $row->estimasi,
            'harga_final'       => $harga_final,
            'berat_ekspedisi'   => $row->berat_ekspedisi,
            'fix'               => $row->fix // bisa 1 atau null/0
        ];
    }

    return $result;
}











//======= END Berdasarkan Nama Ekspedisi ========





//==================================================================
//==================================================================
//================= End Perbandingan Ekspedisi =====================
//==================================================================
//==================================================================

}