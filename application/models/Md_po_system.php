<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_po_system extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    /**
     * Pastikan kolom tambahan DP (persen / nominal) tersedia di tabel po_masuk
     */
    private function ensureSchema()
    {
        static $checked = false;
        if ($checked) {
            return;
        }
        $checked = true;

        if ($this->db->table_exists('po_masuk')) {
            if (!$this->db->field_exists('cicilan_dp_tipe', 'po_masuk')) {
                @$this->db->query("ALTER TABLE `po_masuk` ADD COLUMN `cicilan_dp_tipe` VARCHAR(20) DEFAULT 'persen' AFTER `sistem_pembayaran`");
            }
            if (!$this->db->field_exists('cicilan_dp_nominal', 'po_masuk')) {
                @$this->db->query("ALTER TABLE `po_masuk` ADD COLUMN `cicilan_dp_nominal` DECIMAL(18,2) DEFAULT 0.00 AFTER `cicilan_dp_persen`");
            }
        }
    }

    /**
     * Ambil semua PO masuk dengan filter status, status proses, sales/marketing, dan tanggal
     */
    public function getAllPo($filter_status = null, $filter_status_proses = null, $filter_sales = null, $tgl_awal = null, $tgl_akhir = null)
    {
        $this->db->select('
            p.*,
            c.nama_customer as master_nama_customer,
            c.contact as master_contact,
            c.alamat_customer as master_alamat,
            pl.identitas_pelanggan as master_pelanggan_nama,
            pl.alamat as master_pelanggan_alamat,
            pl.npwp as master_pelanggan_npwp,
            u.nama as nama_pembuat,
            u.jabatan as jabatan_pembuat,
            cro.nama as nama_kroscek,
            acc.nama as nama_acc,
            pjk.nama as nama_pajak,
            gm.nama as nama_gm,
            (SELECT COUNT(*) FROM po_masuk_item WHERE id_po = p.id_po) as total_item
        ');
        $this->db->from('po_masuk p');
        $this->db->join('customer c', 'p.id_customer = c.id_customer', 'left');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('pengguna u', 'p.created_by = u.pengguna_id', 'left');
        $this->db->join('pengguna cro', 'p.cro_user_id = cro.pengguna_id', 'left');
        $this->db->join('pengguna acc', 'p.acc_user_id = acc.pengguna_id', 'left');
        $this->db->join('pengguna pjk', 'p.pajak_user_id = pjk.pengguna_id', 'left');
        $this->db->join('pengguna gm', 'p.gm_user_id = gm.pengguna_id', 'left');
        $this->db->where('p.deleted', 0);

        if (!empty($filter_status)) {
            $this->db->where('p.status', $filter_status);
        }

        if (!empty($filter_status_proses)) {
            $this->db->where('p.status_proses', $filter_status_proses);
        }

        if (!empty($filter_sales)) {
            $this->db->group_start();
            $this->db->where('p.id_sales', $filter_sales);
            $this->db->or_where('p.created_by', $filter_sales);
            $this->db->group_end();
        }

        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $this->db->where('p.tanggal_po >=', $tgl_awal);
            $this->db->where('p.tanggal_po <=', $tgl_akhir);
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
            pl.identitas_pelanggan as master_pelanggan_nama,
            pl.alamat as master_pelanggan_alamat,
            pl.npwp as master_pelanggan_npwp,
            u.nama as nama_pembuat,
            u.jabatan as jabatan_pembuat,
            cro.nama as nama_kroscek,
            cro.jabatan as jabatan_kroscek,
            acc.nama as nama_acc,
            acc.jabatan as jabatan_acc,
            pjk.nama as nama_pajak,
            pjk.jabatan as jabatan_pajak,
            gm.nama as nama_gm,
            gm.jabatan as jabatan_gm
        ');
        $this->db->from('po_masuk p');
        $this->db->join('customer c', 'p.id_customer = c.id_customer', 'left');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('pengguna u', 'p.created_by = u.pengguna_id', 'left');
        $this->db->join('pengguna cro', 'p.cro_user_id = cro.pengguna_id', 'left');
        $this->db->join('pengguna acc', 'p.acc_user_id = acc.pengguna_id', 'left');
        $this->db->join('pengguna pjk', 'p.pajak_user_id = pjk.pengguna_id', 'left');
        $this->db->join('pengguna gm', 'p.gm_user_id = gm.pengguna_id', 'left');
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
     * Update data 1 item PO
     */
    public function updateItem($id_item, $data)
    {
        $this->db->where('id_item', $id_item);
        return $this->db->update('po_masuk_item', $data);
    }

    /**
     * Hitung sisa barang inden dalam 1 PO
     */
    public function countIndentItems($id_po)
    {
        return $this->db->where('id_po', $id_po)->where('status_item', 'indent')->count_all_results('po_masuk_item');
    }

    /**
     * Ambil item yang masih inden dalam 1 PO
     */
    public function getIndentItemsByPoId($id_po)
    {
        return $this->db->get_where('po_masuk_item', ['id_po' => $id_po, 'status_item' => 'indent'])->result();
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
            SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as total_ditolak,
            SUM(CASE WHEN status_proses = 'BELUM DI PROSES' THEN 1 ELSE 0 END) as total_belum,
            SUM(CASE WHEN status_proses = 'PROSES SEBAGIAN' THEN 1 ELSE 0 END) as total_sebagian,
            SUM(CASE WHEN status_proses = 'SUDAH DI PROSES' THEN 1 ELSE 0 END) as total_sudah,
            SUM(CASE WHEN status_proses = 'HOLD / INDENT' THEN 1 ELSE 0 END) as total_hold
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
            $parts = explode('-', $last->kode_masuk);
            $last_num = (int) end($parts);
            $new_num = sprintf('%03d', $last_num + 1);
        } else {
            $new_num = '001';
        }
        return $prefix . $new_num;
    }
}
