<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_history_barang extends CI_Model
{


    //function getAllHistoryByTGL()
    // Untuk export data ke Excel History Barang
    function getAllHistoryByTGL($id_barang = NULL, $tglawal, $tglakhir)
    {
        // Order data utama berdasarkan tanggal pada `data_created` tabel utama
        $this->db->order_by('db.data_created', 'ASC');
        
        // Build query
        $query = $this->db
            ->select('
                db.id_barang,
                db.no_batch,
                db.qty,
                db.exp_date,
                db.current_stock,
                b.nama_barang,
                g.nama_gudang,
                pb.id_penerimaan_barang,
                pb.no_terima,
                pb.tgl_masuk,
                pb.id_pemasok,
                pb.data_created as penerimaan_created,
                ps.tgl_penerimaan_stok,
                ps.no_penerimaan_stok,
                ps.id_penerimaan_stok,
                ps.data_created as stok_created,
                ps.id_penerimaan_barang as penerimaan_barang_id,
                ps.id_pengiriman_stok as pengiriman_stok_id,
                pss.id_pengiriman_stok,
                pss.tgl_pengiriman,
                pss.data_created as pengiriman_created,
                g2.nama_gudang as gudang_asal,
                dbk.id_detail_barang as detail_barang_exit,
                dbk.no_batch as no_batch_exit,
                dbk.qty as qty_exit,
                dbk.exp_date as exp_date_exit,
                dbk.data_created,
                pbb.id_pengeluaran_barang,
                pbb.no_pengiriman,
                pbb.tgl_keluar,
                pbb.data_created as pengeluaran_created,
                g3.nama_gudang as nama_gudang_exit,
                b2.nama_barang as nama_barang_exit,
                cs.nama_customer,
                pm.nama_pemasok
            ')
            ->from('detail_barang db')
            ->join('barang b', 'db.id_barang=b.id_barang', 'left')
            ->join('penerimaan_barang pb', 'db.id_penerimaan_barang=pb.id_penerimaan_barang', 'left')
            ->join('penerimaan_stok ps', 'pb.id_penerimaan_barang=ps.id_penerimaan_barang', 'left')
            ->join('pengiriman_stok pss', 'ps.id_pengiriman_stok=pss.id_pengiriman_stok', 'left')
            ->join('gudang g', 'pb.id_gudang=g.id_gudang', 'left')
            ->join('gudang g2', 'pss.id_gudang_asal=g2.id_gudang', 'left')
            ->join('detail_barang_keluar dbk', 'db.id_detail_barang=dbk.id_detail_barang', 'left')
            ->join('pengeluaran_barang pbb', 'dbk.id_pengeluaran_barang=pbb.id_pengeluaran_barang', 'left')
            ->join('gudang g3', 'pbb.id_gudang=g3.id_gudang', 'left')
            ->join('barang b2', 'dbk.id_barang=b2.id_barang', 'left')
            ->join('customer cs', 'pbb.id_customer=cs.id_customer', 'left')
            ->join('pemasok_utama pm', 'pb.id_pemasok=pm.id_pemasok', 'left')
            ->where('db.status = 1')
            ->where('db.data_created >=', date('Y-m-d', strtotime($tglawal)))
            ->where('db.data_created <=', date('Y-m-d', strtotime($tglakhir)))
            ->order_by('db.data_created', 'ASC'); // Pastikan hanya satu order_by yang diprioritaskan

        // Kondisi jika ada barang yang dipilih
        if ($id_barang != NULL) {
            $query->where('db.id_barang', $id_barang);
        }

        // Return hasil query
        return $query->get()->result();
    }



    function getStokByBarangId($id_barang)
    {
        $q = 'SELECT sum(db.current_stock) as total_stock FROM barang b
                LEFT JOIN detail_barang db ON db.id_barang = b.id_barang
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                WHERE (db.status = 1 OR db.id_detail_barang IS NULL) 
                AND b.id_barang = ?';

        // Mengambil satu baris hasil dan mendapatkan kolom total_stock
        $result = $this->db->query($q, [$id_barang])->row(); // menggunakan row() untuk satu hasil

        // Jika hasilnya tidak kosong, ambil nilai total_stock, jika tidak, set ke 0
        return $result ? $result->total_stock : 0;
    }


    function getStokByBarangIdDate($id_barang, $tanggal_awal, $tanggal_akhir)
    {
        $q = 'SELECT sum(db.current_stock) as total_stock FROM barang b
                LEFT JOIN detail_barang db ON db.id_barang = b.id_barang
                LEFT JOIN penerimaan_barang pb ON pb.id_penerimaan_barang = db.id_penerimaan_barang
                WHERE (db.status = 1 OR db.id_detail_barang IS NULL) 
                AND b.id_barang = ? 
                AND db.data_created BETWEEN ? AND ?';

        // Mengambil satu baris hasil dan mendapatkan kolom total_stock
        $result = $this->db->query($q, [$id_barang, $tanggal_awal, $tanggal_akhir])->row(); // menggunakan row() untuk satu hasil

        // Jika hasilnya tidak kosong, ambil nilai total_stock, jika tidak, set ke 0
        return $result ? $result->total_stock : 0;
    }








    function getAll12()
    {
        $this->db->order_by('db.data_created', 'DESC');
        return $this->datatables
            ->select('
                db.id_barang,
                db.no_batch,
                db.qty,
                db.exp_date,
                db.current_stock,
                b.nama_barang,
                g.nama_gudang,
                pb.no_terima,
                pb.tgl_masuk,
                ps.tgl_penerimaan_stok,
                ps.no_penerimaan_stok
            ')
            ->from('detail_barang db')
            ->join('barang b', 'db.id_barang=b.id_barang')
            ->join('penerimaan_barang pb', 'db.id_penerimaan_barang=pb.id_penerimaan_barang')
            ->join('penerimaan_stok ps', 'pb.id_penerimaan_barang=ps.id_penerimaan_barang', 'left')
            ->join('gudang g', 'pb.id_gudang=g.id_gudang')
            ->generate();
    }

    public function getAll()
    {
        $this->db->order_by('db.data_created', 'DESC');
        $this->db->order_by('dbk.data_created', 'DESC');
        return $this->datatables
            ->select('
                db.id_barang,
                db.no_batch,
                db.qty,
                db.exp_date,
                db.current_stock,
                b.nama_barang,
                g.nama_gudang,
                pb.no_terima,
                pb.tgl_masuk,
                pb.id_pemasok,
                ps.tgl_penerimaan_stok,
                ps.no_penerimaan_stok,
                ps.id_penerimaan_barang as penerimaan_barang_id,
                ps.id_pengiriman_stok as pengiriman_stok_id,
                g2.nama_gudang as gudang_asal,
                dbk.id_detail_barang  as detail_barang_exit,
                dbk.no_batch  as no_batch_exit,
                dbk.qty as qty_exit,
                dbk.exp_date as exp_date_exit,
                pbb.no_pengiriman,
                pbb.tgl_keluar,
                g3.nama_gudang as nama_gudang_exit,
                b2.nama_barang as nama_barang_exit


            ')
            ->from('detail_barang db')
            ->join('barang b', 'db.id_barang=b.id_barang', 'left')
            ->join('penerimaan_barang pb', 'db.id_penerimaan_barang=pb.id_penerimaan_barang', 'left') // LEFT JOIN
            ->join('penerimaan_stok ps', 'pb.id_penerimaan_barang=ps.id_penerimaan_barang', 'left') // LEFT JOIN
            ->join('pengiriman_stok pss', 'ps.id_pengiriman_stok=pss.id_pengiriman_stok', 'left') // LEFT JOIN
            ->join('gudang g', 'pb.id_gudang=g.id_gudang', 'left') // LEFT JOIN
            ->join('gudang g2', 'pss.id_gudang_asal=g2.id_gudang', 'left') // LEFT JOIN
            ->join('detail_barang_keluar dbk', 'db.id_detail_barang=dbk.id_detail_barang', 'left')
            ->join('pengeluaran_barang pbb', 'dbk.id_pengeluaran_barang=pbb.id_pengeluaran_barang', 'left')
            ->join('gudang g3', 'pbb.id_gudang=g3.id_gudang', 'left') // LEFT JOIN
            ->join('barang b2', 'dbk.id_barang=b2.id_barang', 'left')
            ->where('db.status = 1')
            ->generate();
    }

    public function getAllbyTGL($id_barang,$tglawal,$tglakhir)
    {
        $this->db->order_by('db.data_created', 'DESC');
        $this->db->order_by('dbk.data_created', 'DESC');
        return $this->datatables
            ->select('
                db.id_barang,
                db.no_batch,
                db.qty,
                db.exp_date,
                db.current_stock,
                b.nama_barang,
                g.nama_gudang,
                pb.no_terima,
                pb.tgl_masuk,
                pb.id_pemasok,
                ps.tgl_penerimaan_stok,
                ps.no_penerimaan_stok,
                ps.id_penerimaan_barang as penerimaan_barang_id,
                ps.id_pengiriman_stok as pengiriman_stok_id,
                g2.nama_gudang as gudang_asal,
                dbk.id_detail_barang  as detail_barang_exit,
                dbk.no_batch  as no_batch_exit,
                dbk.qty as qty_exit,
                dbk.exp_date as exp_date_exit,
                pbb.no_pengiriman,
                pbb.tgl_keluar,
                g3.nama_gudang as nama_gudang_exit,
                b2.nama_barang as nama_barang_exit


            ')
            ->from('detail_barang db')
            ->join('barang b', 'db.id_barang=b.id_barang', 'left')
            ->join('penerimaan_barang pb', 'db.id_penerimaan_barang=pb.id_penerimaan_barang', 'left') // LEFT JOIN
            ->join('penerimaan_stok ps', 'pb.id_penerimaan_barang=ps.id_penerimaan_barang', 'left') // LEFT JOIN
            ->join('pengiriman_stok pss', 'ps.id_pengiriman_stok=pss.id_pengiriman_stok', 'left') // LEFT JOIN
            ->join('gudang g', 'pb.id_gudang=g.id_gudang', 'left') // LEFT JOIN
            ->join('gudang g2', 'pss.id_gudang_asal=g2.id_gudang', 'left') // LEFT JOIN
            ->join('detail_barang_keluar dbk', 'db.id_detail_barang=dbk.id_detail_barang', 'left')
            ->join('pengeluaran_barang pbb', 'dbk.id_pengeluaran_barang=pbb.id_pengeluaran_barang', 'left')
            ->join('gudang g3', 'pbb.id_gudang=g3.id_gudang', 'left') // LEFT JOIN
            ->join('barang b2', 'dbk.id_barang=b2.id_barang', 'left')
            ->where('db.status = 1')
            ->where('db.data_created >=', date('Y-m-d', strtotime($tglawal)))
            ->where('db.data_created <=', date('Y-m-d', strtotime($tglakhir)))
            ->where('db.id_barang <=', $id_barang)
            
            ->generate();
    }

    

    


    

}
