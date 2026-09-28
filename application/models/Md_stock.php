<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_stock extends CI_Model
{


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
