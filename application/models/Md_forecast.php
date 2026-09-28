<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_forecast extends CI_Model
{

    function getAll()
    {
        $this->db->order_by('id', 'DESC');
        return $this->datatables
            ->select('
				f.id,
                f.kode,
				f.status,
                f.id_kategori,
                p.nama,
                k.nama_kategori
            ')
            ->from('forecast f')
			->join('pengguna p', 'f.id_pengaju=p.pengguna_id')
			->join('kategori_barang k', 'f.id_kategori=k.id_kategori')
            ->generate();
    }

    public function getAllTampil($id_kategori)
    {
        $tahun_sekarang = date('Y');
        $tahun_lalu     = $tahun_sekarang - 1;

        $this->db->select("
            b.id_barang,
            b.nama_barang,
            sb.nama_satuan,

            -- Total terjual tahun lalu
            IFNULL(SUM(CASE WHEN YEAR(pbk.tgl_keluar) = {$tahun_lalu} THEN dbk.qty ELSE 0 END),0) AS terjual_tahun_lalu,

            -- Total terjual tahun ini
            IFNULL(SUM(CASE WHEN YEAR(pbk.tgl_keluar) = {$tahun_sekarang} THEN dbk.qty ELSE 0 END),0) AS terjual_tahun_ini
        ", FALSE);

        $this->db->from('barang b');
        $this->db->join('satuan_barang sb', 'sb.id_satuan = b.id_satuan_barang', 'left');

        $this->db->join('detail_barang_keluar dbk', 'dbk.id_barang = b.id_barang AND dbk.status = 1', 'left');
        $this->db->join('pengeluaran_barang pbk', 'pbk.id_pengeluaran_barang = dbk.id_pengeluaran_barang AND pbk.status = 1', 'left');

        $this->db->where('b.status', 1);
        $this->db->where('b.id_kategori', $id_kategori);

        $this->db->group_by('b.id_barang, b.nama_barang, sb.nama_satuan');

        

        // ✅ Urutkan berdasarkan nama barang (A-Z)
        $this->db->order_by('b.nama_barang', 'ASC');

        $result = $this->db->get()->result();

        // Tambahkan stok manual (pakai getBarangStock-style)
        foreach ($result as &$row) {
            $stok = $this->getStokBarang($row->id_barang);

            $row->total_stok          = $stok['total'];
            $row->jual_stok           = $stok['jual'];
            $row->demo_stok           = $stok['demo'];
            $row->barang_customer_stok= $stok['customer'];
        }

        return $result;
    }

private function getStokBarang($id_barang)
{
    $rows = $this->db->select("
            db.current_stock,
            pb.id_gudang
        ")
        ->from('detail_barang db')
        ->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'left')
        ->where('db.id_barang', $id_barang)
        ->where('db.status', 1)
        ->where('db.current_stock IS NOT NULL')
        ->get()
        ->result();

    $total = 0;
    $jual = 0;
    $demo = 0;
    $customer = 0;

    foreach ($rows as $r) {
        $stok = is_numeric($r->current_stock) ? (float)$r->current_stock : 0;

        $total += $stok;

        if (in_array($r->id_gudang, [1,2,3])) {
            $jual += $stok;
        }
        if (in_array($r->id_gudang, [6,10,11,23,26,30])) {
            $demo += $stok;
        }
        if (in_array($r->id_gudang, [24,28,32])) {
            $customer += $stok;
        }
    }

    return [
        'total'    => $total,
        'jual'     => $jual,
        'demo'     => $demo,
        'customer' => $customer,
    ];
}

public function getKategoriById($id_kategori)
{
    return $this->db->where('id_kategori', $id_kategori)->get('kategori_barang')->row();
}



function getKodeId(){
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('forecast',array('YEAR(`created_at`)' => date('Y')))->row();
}

function reset_increment($tabel){
		$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
}

public function getForecastByKategori($id_kategori, $id_pengaju, $tahun)
{
    $this->db->where('id_kategori', $id_kategori);
    $this->db->where('id_pengaju', $id_pengaju);
    $this->db->where('tahun_b', $tahun); // forecast tahun berjalan
    return $this->db->get('forecast')->row();
}

public function getDetailForecast($id_forecast, $forecast_status)
{
    $this->db->select("
        id_barang,
        nama_barang,
        satuan AS nama_satuan,
        terjual_a AS terjual_tahun_lalu,
        terjual_b AS terjual_tahun_ini,
        stok AS total_stok,
        demo AS demo_stok,
        customer AS barang_customer_stok,
        permintaan,
        keterangan
    ");
    $this->db->from('forecast_detail');
    $this->db->where('id_forecast', $id_forecast);
    $this->db->where('status', 1); // ini tetap filter data aktif

    // kalau kondisi A ($forecast->status != 0)
    if ($forecast_status != 0) {
        $this->db->where('permintaan !=', 0); // filter permintaan tidak boleh 0
    }

    // Urutkan nama_barang A - Z
    $this->db->order_by('nama_barang', 'ASC');

    return $this->db->get()->result();
}


public function getDetailForecastOLD($id_forecast)
{
    return $this->db->select("
            id_barang,
            nama_barang,
            satuan AS nama_satuan,
            terjual_a AS terjual_tahun_lalu,
            terjual_b AS terjual_tahun_ini,
            stok AS total_stok,
            demo AS demo_stok,
            customer AS barang_customer_stok,
            permintaan,
            keterangan
        ")
        ->from('forecast_detail')
        ->where('id_forecast', $id_forecast)
        ->where('status', 1)
        ->get()
        ->result();
}

public function getForecastById($id_forecast)
{
    return $this->db->where('id', $id_forecast)
                    ->get('forecast')
                    ->row();
}

public function getById($id)
{
    return $this->db->select('
            f.id,
            f.kode,
            f.id_kategori,
            f.id_pengaju,
            f.tahun_a,
            f.tahun_b,
            f.status,
            f.created_at,
            f.ttd_1,
            f.ttd_2,
            f.ttd_3,
            p.pengguna_id,
            p.nama,
            p.jabatan,
            p.no_pegawai,
            k.nama_kategori
        ')
        ->from('forecast f')
        ->join('pengguna p', 'p.pengguna_id = f.id_pengaju', 'left')
		->join('kategori_barang k', 'f.id_kategori=k.id_kategori', 'left')
        ->where('f.id', $id)
        ->get()
        ->row();
}


function updateForecast($id, $data)
{
    $this->db->where('id', $id);
    $this->db->update('forecast', $data);
}



























 public function getBarangStock($id_barang)
{
    if (empty($id_barang)) {
        return [];
    }

    return $this->db
        ->select('
            db.no_batch,
            db.current_stock,
            b.nama_barang,
            g.nama_gudang,
            g.id_gudang,
            pb.id_penerimaan_barang,
            pb.no_terima
        ')
        ->from('detail_barang db')
        ->join('barang b', 'db.id_barang = b.id_barang', 'left')
        ->join('penerimaan_barang pb', 'db.id_penerimaan_barang = pb.id_penerimaan_barang', 'left')
        ->join('gudang g', 'pb.id_gudang = g.id_gudang', 'left')
        ->where('db.id_barang', $id_barang)
        ->where('db.status', 1) // hanya ambil yang aktif
        ->where('db.current_stock IS NOT NULL')
        ->where("TRIM(db.current_stock) <> ''")
        ->where("CAST(db.current_stock AS DECIMAL(10,2)) >", 0)
        ->order_by('pb.no_terima', 'ASC')
        ->get()
        ->result();
}








    

}
