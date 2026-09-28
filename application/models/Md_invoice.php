<?php

use function Complex\sec;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_invoice extends CI_Model
{
    function add($data)
    {
        $this->db->insert('invoice', $data);
    }

    function getById($id)
    {
        $this->db->select('i.*, c.nama_customer');
        $this->db->join('customer c', 'i.id_customer = c.id_customer');
        return $this->db->get_where('invoice i', ['i.id_invoice' => $id])->result();
    }

    function getByWhere($where)
    {
        $this->db->select('i.*, c.nama_customer, c.alamat_customer, c.contact, sp.nama_syarat_pembayaran, tp.nama_pajak, tp.persentase, p.nama as nama_markerting');
        $this->db->where('i.status', 1);
        $this->db->join('customer c', 'c.id_customer = i.id_customer');
        $this->db->join('syarat_pembayaran sp', 'sp.id_syarat_pembayaran = i.id_syarat_pembayaran');
        $this->db->join('tarif_pajak tp', 'tp.id_tarif_pajak = i.id_tarif_pajak');
        $this->db->join('pengguna p', 'p.pengguna_id = i.id_marketing');
        return $this->db->get_where('invoice i', $where)->result();
    }

    // function getForStockGudang()
    // {
    //     $this->db->select('id_pengeluaran_barang, id_gudang');
    //     return $this->db->get_where('pengeluaran_barang', ['status'=> 1])->result();
    // }


    function update($where = "", $data = "")
    {
        $this->db->where($where);
        $this->db->update('invoice', $data);
    }

    function getDetailBarangInvoiceById($id)
    {
        $this->db->select('dbi.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbi.id_barang', 'LEFT');
        // $this->db->join('detail_barang db', 'db.id_detail_barang = dbi.id_detail_barang', 'LEFT');
        // $this->db->join('penerimaan_barang pb', 'pb.id_penerimaan_barang = db.id_penerimaan_barang', 'LEFT');
        return $this->db->get_where('detail_barang_invoice dbi', ['dbi.id_detail_barang_invoice' => $id])->result();
    }

    function getAll()
    {
        if ($this->input->post('filter_month'))
            $this->datatables->where("DATE_FORMAT(i.tgl_invoice,'%Y-%m')", $this->input->post('filter_month'));

        if ($this->input->post('filter_status_pembuatan') == 'all') {
        } else if ($this->input->post('filter_status_pembuatan') == 1 || $this->input->post('filter_status_pembuatan') == 0) {
            $this->datatables->where("i.from_pengeluaran_barang", (int)$this->input->post('filter_status_pembuatan'));
        }

        if ($this->input->post('filter_pengeluaran_barang')){
            if($this->input->post('filter_pengeluaran_barang') == 1){
                $this->datatables->where('i.status_barang_keluar', 'belum_keluar');
            } else if($this->input->post('filter_pengeluaran_barang') == 2) {
                $this->datatables->where('i.status_barang_keluar', 'keluar_sebagian');
            } else {
                $this->datatables->where('i.status_barang_keluar', 'sudah_keluar');
            }
        }

        return $this->datatables
            ->select(' 
            i.id_invoice, 
            i.no_invoice,
            i.tgl_invoice,
            i.no_po,
            p.nama as nama_marketing,
            c.nama_customer,
            c.alamat_customer,
            i.from_pengeluaran_barang,
            i.status_barang_keluar,
            i.status,
        ')
            ->from('invoice i')
            ->join('customer c', 'c.id_customer = i.id_customer', 'LEFT')
            ->join('pengguna p', 'p.pengguna_id = i.id_marketing', 'LEFT')
            ->where('i.status = 1')
            ->generate();
    }

    //////////////////////////////////////////////////////////////////////////////////////
    //mulai dari sini adalah semua tentang function temp data di form penerimaan barang//
    ////////////////////////////////////////////////////////////////////////////////////
    function getTempData()
    {
        $this->db->select('it.*,c.nama_customer');
        $this->db->join('customer c', 'c.id_customer = it.id_customer', 'LEFT');
        return $this->db->get_where('invoice_temp it', ['it.pengguna_id' => sessPenggunaId()])->result();
    }

    function addTempData($data)
    {
        $this->db->insert('invoice_temp', $data);
    }

    function updateTempData($where, $data)
    {
        $this->db->where($where);
        $this->db->update('invoice_temp', $data);
    }

    function getDetailBarangTempBySess()
    {
        $this->db->select('dbit.*,b.nama_barang, b.kode_barang');
        $this->db->join('barang b', 'b.id_barang = dbit.id_barang', 'LEFT');
        $this->db->where('dbit.id_invoice', NULL);
        return $this->db->get_where('detail_barang_invoice_temp dbit', ['dbit.pengguna_id' => sessPenggunaId()])->result();
    }

    function getDetailBarangTempById($id)
    {
        $this->db->select('dbit.*,b.nama_barang');
        $this->db->join('barang b', 'b.id_barang = dbit.id_barang', 'LEFT');
        return $this->db->get_where('detail_barang_invoice_temp dbit', ['dbit.id_detail_barang_invoice_temp' => $id])->result();
    }

    function getByIdInvoice($dt)
    {
        $this->db->select('dbit.*, b.nama_barang, b.kode_barang');
        $this->db->join('barang b', 'b.id_barang = dbit.id_barang');
        return $this->db->get_where('detail_barang_invoice_temp dbit', ['dbit.id_invoice' => $dt])->result();
    }

    function deleteDetailBarangTemp($data)
    {
        $this->db->delete('detail_barang_invoice_temp', ['id_detail_barang_invoice_temp' => $data]);
    }

    function addDetailBarangTemp($data)
    {
        $this->db->insert('detail_barang_invoice_temp', $data);
    }

    function updateDetailBarangInvoiceTemp($where, $data)
    {
        $this->db->where($where);
        $this->db->update('detail_barang_invoice_temp', $data);
    }

    function destroyTempData()
    {
        $this->db->where('id_invoice', NULL);
        $this->db->delete('detail_barang_invoice_temp', ['pengguna_id' => sessPenggunaId()]);
        $this->db->delete('invoice_temp', ['pengguna_id' => sessPenggunaId()]);
    }

    function destroyNewTempDetailBarang($id)
    {
        $this->db->where('id_invoice', $id);
        $this->db->delete('detail_barang_invoice_temp');
    }
    //////////////////
    //End Temp Data//
    ////////////////
}
