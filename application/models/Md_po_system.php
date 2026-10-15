<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_po_system extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ambil semua PO masuk dengan filter status
     */
    public function getAllPo($filter_status = null)
    {
        $this->db->select('
            p.*,
            c.nama_customer as master_nama_customer,
            c.contact as master_contact,
            c.alamat_customer as master_alamat,
            u.nama as nama_pembuat,
            cro.nama as nama_kroscek,
            (SELECT COUNT(*) FROM po_masuk_item WHERE id_po = p.id_po) as total_item
        ');
        $this->db->from('po_masuk p');
        $this->db->join('customer c', 'p.id_customer = c.id_customer', 'left');
        $this->db->join('pengguna u', 'p.created_by = u.pengguna_id', 'left');
        $this->db->join('pengguna cro', 'p.dikroscek_oleh = cro.pengguna_id', 'left');
        $this->db->where('p.deleted', 0);

        if (!empty($filter_status)) {
            $this->db->where('p.status', $filter_status);
        }

        $this->db->order_by('p.id_po', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Ambil 1 PO by ID
     */
    public function getPoById($id_po)
    {
        $this->db->select('
            p.*,
            c.nama_customer as master_nama_customer,
            c.contact as master_contact,
            c.alamat_customer as master_alamat,
            u.nama as nama_pembuat,
            u.jabatan as jabatan_pembuat,
            cro.nama as nama_kroscek,
            cro.jabatan as jabatan_kroscek
        ');
        $this->db->from('po_masuk p');
        $this->db->join('customer c', 'p.id_customer = c.id_customer', 'left');
        $this->db->join('pengguna u', 'p.created_by = u.pengguna_id', 'left');
        $this->db->join('pengguna cro', 'p.dikroscek_oleh = cro.pengguna_id', 'left');
        $this->db->where('p.id_po', $id_po);
        $this->db->where('p.deleted', 0);
        return $this->db->get()->row();
    }

    /**
     * Ambil rincian item barang PO
     */
    public function getItemsByPoId($id_po)
    {
        return $this->db->get_where('po_masuk_item', ['id_po' => $id_po])->result();
    }

    /**
     * Ambil riwayat log kroscek
     */
    public function getLogsByPoId($id_po)
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get_where('po_masuk_log', ['id_po' => $id_po])->result();
    }

    /**
     * Insert PO baru
     */
    public function insertPo($data)
    {
        $this->db->insert('po_masuk', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert batch item PO
     */
    public function insertItems($items)
    {
        if (!empty($items)) {
            $this->db->insert_batch('po_masuk_item', $items);
        }
    }

    /**
     * Hapus item lama PO
     */
    public function deleteItems($id_po)
    {
        $this->db->delete('po_masuk_item', ['id_po' => $id_po]);
    }

    /**
     * Update PO
     */
    public function updatePo($id_po, $data)
    {
        $this->db->where('id_po', $id_po);
        return $this->db->update('po_masuk', $data);
    }

    /**
     * Soft delete PO
     */
    public function deletePo($id_po)
    {
        $this->db->where('id_po', $id_po);
        return $this->db->update('po_masuk', ['deleted' => 1]);
    }

    /**
     * Catat log aktivitas kroscek
     */
    public function addLog($id_po, $status_sebelumnya, $status_baru, $catatan, $user_id, $user_nama)
    {
        $data = [
            'id_po'             => $id_po,
            'status_sebelumnya' => $status_sebelumnya,
            'status_baru'       => $status_baru,
            'catatan'           => $catatan,
            'user_id'           => $user_id,
            'user_nama'         => $user_nama,
            'created_at'        => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('po_masuk_log', $data);
    }

    /**
     * Ringkasan statistik status PO
     */
    public function getStatistik()
    {
        $sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'menunggu_kroscek' THEN 1 ELSE 0 END) as total_menunggu,
            SUM(CASE WHEN status = 'sesuai' THEN 1 ELSE 0 END) as total_sesuai,
            SUM(CASE WHEN status = 'revisi' THEN 1 ELSE 0 END) as total_revisi,
            SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as total_ditolak
        FROM po_masuk 
        WHERE deleted = 0";
        return $this->db->query($sql)->row();
    }

    /**
     * Generate kode masuk PO otomatis (e.g. PO-202610-001)
     */
    public function generateKodeMasuk()
    {
        $prefix = 'PO-' . date('Ym') . '-';
        $this->db->select('kode_masuk');
        $this->db->like('kode_masuk', $prefix, 'after');
        $this->db->order_by('id_po', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('po_masuk')->row();

        if ($last && !empty($last->kode_masuk)) {
            $last_num = (int) substr($last->kode_masuk, -3);
            $new_num = sprintf('%03d', $last_num + 1);
        } else {
            $new_num = '001';
        }
        return $prefix . $new_num;
    }
}
