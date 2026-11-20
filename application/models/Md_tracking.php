<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_tracking extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureWarehouseSchema();
    }

    public function ensureWarehouseSchema()
    {
        static $checked = false;
        if ($checked) {
            return;
        }
        $checked = true;

        if ($this->db->table_exists('tracking_barang')) {
            if (!$this->db->field_exists('waktu_po_cro', 'tracking_barang')) {
                @$this->db->query("ALTER TABLE `tracking_barang` ADD COLUMN `waktu_po_cro` DATETIME NULL DEFAULT NULL AFTER `tgl_sampai`");
            }
            if (!$this->db->field_exists('waktu_cetak_sj', 'tracking_barang')) {
                @$this->db->query("ALTER TABLE `tracking_barang` ADD COLUMN `waktu_cetak_sj` DATETIME NULL DEFAULT NULL AFTER `waktu_po_cro`");
            }
            if (!$this->db->field_exists('waktu_order_ekspedisi', 'tracking_barang')) {
                @$this->db->query("ALTER TABLE `tracking_barang` ADD COLUMN `waktu_order_ekspedisi` DATETIME NULL DEFAULT NULL AFTER `waktu_cetak_sj`");
            }
            if (!$this->db->field_exists('waktu_packaging', 'tracking_barang')) {
                @$this->db->query("ALTER TABLE `tracking_barang` ADD COLUMN `waktu_packaging` DATETIME NULL DEFAULT NULL AFTER `waktu_order_ekspedisi`");
            }
            if (!$this->db->field_exists('waktu_loading_handover', 'tracking_barang')) {
                @$this->db->query("ALTER TABLE `tracking_barang` ADD COLUMN `waktu_loading_handover` DATETIME NULL DEFAULT NULL AFTER `waktu_packaging`");
            }
        }

        if (!$this->db->table_exists('tracking_import')) {
            @$this->db->query("CREATE TABLE IF NOT EXISTS `tracking_import` (
                `id_import` INT(11) NOT NULL AUTO_INCREMENT,
                `kode_import` VARCHAR(50) NOT NULL,
                `id_po_supplier` INT(11) NULL DEFAULT NULL,
                `no_po_manufacture` VARCHAR(100) NOT NULL,
                `nama_manufacture` VARCHAR(255) NOT NULL,
                `negara_asal` VARCHAR(100) NULL DEFAULT NULL,
                `daftar_unit` TEXT NOT NULL,
                `moda_transportasi` VARCHAR(50) DEFAULT 'Sea Freight (Kapal)',
                `id_forwarder` INT(11) NULL DEFAULT NULL,
                `nama_forwarder` VARCHAR(200) NULL DEFAULT NULL,
                `no_bl_awb` VARCHAR(100) NULL DEFAULT NULL,
                `nama_kapal_flight` VARCHAR(150) NULL DEFAULT NULL,
                `pelabuhan_asal` VARCHAR(150) NULL DEFAULT NULL,
                `pelabuhan_tujuan` VARCHAR(150) NULL DEFAULT NULL,
                `no_pib` VARCHAR(100) NULL DEFAULT NULL,
                `nominal_pajak_import` DECIMAL(18,2) DEFAULT 0.00,
                `bukti_bayar_pajak` TEXT NULL DEFAULT NULL,
                `id_ekspedisi_domestik` INT(11) NULL DEFAULT NULL,
                `nama_ekspedisi_domestik` VARCHAR(200) NULL DEFAULT NULL,
                `no_resi_domestik` VARCHAR(100) NULL DEFAULT NULL,
                `id_gudang_tujuan` INT(11) NULL DEFAULT NULL,
                `nama_gudang_tujuan` VARCHAR(150) DEFAULT 'Gudang Pusat',
                `pic_penerima_gudang` VARCHAR(150) NULL DEFAULT NULL,
                `bukti_terima_gudang` TEXT NULL DEFAULT NULL,
                `tgl_estimasi_sampai_port` DATE NULL DEFAULT NULL,
                `tgl_estimasi_sampai_gudang` DATE NULL DEFAULT NULL,
                `waktu_1_po_manufacture` DATETIME NULL DEFAULT NULL,
                `waktu_2_order_forwarder` DATETIME NULL DEFAULT NULL,
                `waktu_3_loading_kapal` DATETIME NULL DEFAULT NULL,
                `waktu_4_perjalanan_kapal` DATETIME NULL DEFAULT NULL,
                `waktu_5_sampai_port` DATETIME NULL DEFAULT NULL,
                `waktu_6_bayar_pajak` DATETIME NULL DEFAULT NULL,
                `waktu_7_keluar_port` DATETIME NULL DEFAULT NULL,
                `waktu_8_handover_domestik` DATETIME NULL DEFAULT NULL,
                `waktu_9_sampai_gudang` DATETIME NULL DEFAULT NULL,
                `current_stage` INT(11) DEFAULT 1,
                `keterangan` TEXT NULL DEFAULT NULL,
                `created_by` INT(11) NULL DEFAULT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL DEFAULT NULL,
                `stat` TINYINT(1) DEFAULT 1,
                PRIMARY KEY (`id_import`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        if (!$this->db->table_exists('tracking_import_log')) {
            @$this->db->query("CREATE TABLE IF NOT EXISTS `tracking_import_log` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_import` INT(11) NOT NULL,
                `stage` INT(11) NOT NULL,
                `stage_label` VARCHAR(150) NOT NULL,
                `waktu_milestone` DATETIME NOT NULL,
                `catatan` TEXT NULL DEFAULT NULL,
                `bukti_link` TEXT NULL DEFAULT NULL,
                `id_pengguna` INT(11) NULL DEFAULT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_id_import` (`id_import`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
    }

    function add($data)
    {
        $this->db->insert('tracking_barang', $data);
    }

    function addStatus($data)
    {
        $this->db->insert('tracking_status', $data);
    }

    function update($where, $data)
    {
        $this->db->where($where);
        $this->db->update('tracking_barang', $data);
    }

    function updateStatus($where, $data)
    {
        $this->db->where($where);
        $this->db->update('tracking_status', $data);
    }

    function deleteStatus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tracking_status');
    }

    function getStatusHistoryByTracking($id_tracking)
    {
        $this->db->select('ts.*, p.nama as nama_pengguna');
        $this->db->from('tracking_status ts');
        $this->db->join('pengguna p', 'ts.id_pengguna = p.pengguna_id', 'LEFT');
        $this->db->where('ts.id_tracking', $id_tracking);
        $this->db->order_by('ts.created_at', 'ASC');
        return $this->db->get()->result();
    }

    function getById($id)
    {
        return $this->db->get_where('tracking_barang e', array('e.id_tracking' => $id))->result();
    }

    function getGudangById($id)
    {
        return $this->db->get_where('gudang g', array('g.id_gudang' => $id))->result();
    }


    function getByWhere($param = "")
    {
        $this->db->where('e.status', 1);
        return $this->db->get('tracking_barang e')->result();
    }

    function getTrackLastId()
    {
        return $this->db->select("*")->limit(1)->order_by('id_tracking', "DESC")->get('tracking_barang')->row();
    }

    function reset_increment($tabel)
    {
        $this->db->query("ALTER TABLE " . $tabel . " AUTO_INCREMENT = 1");
    }

    function getAllEkspedisi()
    {
        $this->db->where('status', 1);
        $this->db->order_by('k.nama_ekspedisi', 'ASC');
        return $this->db->get('ekspedisi k')->result();
    }

    function getAllCustomer()
    {
        $sql = "
        SELECT 
            id_pelanggan AS id, 
            identitas_pelanggan AS nama, 
            alamat AS alamat, 
            kontak AS telp,
            'pelanggan' AS tipe_data
        FROM pelanggan 
        WHERE status = 1

        UNION ALL

        SELECT 
            id_customer AS id, 
            nama_customer AS nama, 
            alamat_customer AS alamat, 
            contact AS telp,
            'customer' AS tipe_data
        FROM customer 
        WHERE status = 1

        ORDER BY nama ASC
    ";

        return $this->db->query($sql)->result();
    }

    function getCustomerById($id)
    {
        return $this->db->get_where('customer e', array('e.id_customer' => $id))->result();
    }

    function getAllGudang()
    {
        $this->db->where('status', 1);
        $this->db->order_by('k.nama_gudang', 'ASC');
        return $this->db->get('gudang k')->result();
    }


    function getAllTracking()
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tracking,
                t.id_gudang,
                t.id_customer,
                t.nama_barang,
                t.alamat_penerima,
                t.nama_ekspedisi,
                t.id_pengeluaran_barang,
                t.tgl_sampai,
                t.no_sj,
                t.stat,
                g.nama_gudang,
                c.nama_customer,
                (SELECT ts.id_status FROM tracking_status ts WHERE ts.id_tracking = t.id_tracking ORDER BY ts.created_at DESC LIMIT 1) AS id_status
            ')
            ->from('tracking_barang t')
            ->join('gudang g', 't.id_gudang=g.id_gudang')
            ->join('customer c', 't.id_customer=c.id_customer')
            ->where('t.stat = 1')
            ->generate();
    }

    function getAllTrackingNew()
    {
        $searchArray = $this->input->post('search', TRUE);
        $keyword = isset($searchArray['value']) ? trim($searchArray['value']) : '';
        $filter_type = $this->input->post('filter_type', TRUE);

        $this->db->select('
            t.id_tracking,
            t.id_gudang,
            t.id_customer,
            t.nama_barang,
            t.alamat_penerima,
            t.nama_ekspedisi,
            t.id_pengeluaran_barang,
            t.id_pengiriman_stok,
            t.id_serah_terima_barang,
            t.id_kirim_dokumen,
            t.tracking_type,
            t.tgl_pengiriman,
            t.tgl_sampai,
            t.waktu_po_cro,
            t.waktu_cetak_sj,
            t.waktu_order_ekspedisi,
            t.waktu_packaging,
            t.waktu_loading_handover,
            t.created_at,
            t.no_sj,
            t.no_resi,
            t.link_resi,
            t.stat,
            g.nama_gudang,
            c.nama_customer,
            g2.nama_gudang as gudang_tujuan,
            stb_c1.nama_customer as nama_pihak1_stb,
            stb_c2.nama_customer as nama_pihak2_stb,
            kd.kode as kode_kirim_dokumen,
            kd.nama_customer as nama_customer_kirim,
            te.id_tagihan,
            te.status_approval,
            pb.no_po as pb_no_po,
            pb.data_created as pb_created_at,
            ps.data_created as ps_created_at,
            stb.created_at as stb_created_at,
            (SELECT pm.cro_date FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_cro_date,
            (SELECT pm.gm_date FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_gm_date,
            (SELECT pm.created_at FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_created_at,
            (SELECT pm.doc_gudang_packing_list FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_doc_packing_list,
            (SELECT MIN(ts1.created_at) FROM tracking_status ts1 WHERE ts1.id_tracking = t.id_tracking AND ts1.id_status >= 1) as status1_time,
            (SELECT MIN(ts7.created_at) FROM tracking_status ts7 WHERE ts7.id_tracking = t.id_tracking AND ts7.id_status = 7) as status_packaging_time,
            (SELECT MIN(ts2.created_at) FROM tracking_status ts2 WHERE ts2.id_tracking = t.id_tracking AND ts2.id_status IN (2,3,4,5)) as status2_time,
            (SELECT ts5.tgl_penerima FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as tgl_penerima_aktual,
            (SELECT ts5.nama_penerima FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as nama_penerima_aktual,
            (SELECT ts5.bukti_penerima FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as bukti_penerima_aktual,
            (SELECT ts5.created_at FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as status5_time,
            CASE 
                WHEN t.tracking_type = "pengiriman_stok" THEN 
                    (SELECT GROUP_CONCAT(DISTINCT b.nama_barang SEPARATOR ", ")
                    FROM detail_barang_pengiriman_stok dbps
                    LEFT JOIN barang b ON b.id_barang = dbps.id_barang
                    WHERE dbps.id_pengiriman_stok = t.id_pengiriman_stok
                    AND dbps.status = 1)
                WHEN t.tracking_type = "serah_terima_barang" THEN 
                    (SELECT GROUP_CONCAT(DISTINCT d.nama_barang SEPARATOR ", ")
                    FROM surat_stb_detail d
                    WHERE d.id_stb = t.id_serah_terima_barang
                    AND d.status = 1)
                WHEN t.tracking_type = "kirim_dokumen" THEN 
                    "Dokumen"
                ELSE 
                    (SELECT GROUP_CONCAT(DISTINCT b.nama_barang SEPARATOR ", ")
                    FROM detail_barang_keluar dbk
                    LEFT JOIN barang b ON b.id_barang = dbk.id_barang
                    WHERE dbk.id_pengeluaran_barang = t.id_pengeluaran_barang
                    AND dbk.status = 1)
            END as barang_list,
            (SELECT ts.id_status FROM tracking_status ts 
            WHERE ts.id_tracking = t.id_tracking 
            ORDER BY ts.created_at DESC LIMIT 1) AS id_status
        ');
        $this->db->from('tracking_barang t');
        $this->db->join('gudang g', 't.id_gudang=g.id_gudang', 'LEFT');
        $this->db->join('customer c', 't.id_customer=c.id_customer', 'LEFT');
        $this->db->join('pengeluaran_barang pb', 't.id_pengeluaran_barang=pb.id_pengeluaran_barang', 'LEFT');
        $this->db->join('pengiriman_stok ps', 't.id_pengiriman_stok=ps.id_pengiriman_stok', 'LEFT');
        $this->db->join('gudang g2', 'ps.id_gudang_tujuan=g2.id_gudang', 'LEFT');
        $this->db->join('surat_stb stb', 't.id_serah_terima_barang=stb.id_stb', 'LEFT');
        $this->db->join('customer stb_c1', 'stb.id_pihak1=stb_c1.id_customer', 'LEFT');
        $this->db->join('customer stb_c2', 'stb.id_customer=stb_c2.id_customer', 'LEFT');
        $this->db->join('kirim_dokumen kd', 't.id_kirim_dokumen=kd.id', 'LEFT');
        $this->db->join('tagihan_ekspedisi te', 't.id_tracking = te.id_tracking', 'LEFT');

        $this->db->where('t.stat', 1);

        if (!empty($filter_type)) {
            $this->db->where('t.tracking_type', $filter_type);
        }

        $exclude_kirim_dokumen = $this->input->post('exclude_kirim_dokumen', TRUE);
        if ($exclude_kirim_dokumen === true || $exclude_kirim_dokumen === 'true') {
            $this->db->where('t.tracking_type !=', 'kirim_dokumen');
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('t.no_sj', $keyword);
            $this->db->or_like('c.nama_customer', $keyword);
            $this->db->or_like('g.nama_gudang', $keyword);
            $this->db->or_like('t.alamat_penerima', $keyword);
            $this->db->or_like('g2.nama_gudang', $keyword);
            $this->db->or_like('stb_c1.nama_customer', $keyword);
            $this->db->or_like('stb_c2.nama_customer', $keyword);
            $this->db->or_like('kd.kode', $keyword);
            $this->db->or_like('kd.nama_customer', $keyword);

            $this->db->or_where("t.id_pengeluaran_barang IN (
                SELECT dbk.id_pengeluaran_barang 
                FROM detail_barang_keluar dbk 
                LEFT JOIN barang b ON b.id_barang = dbk.id_barang 
                WHERE b.nama_barang LIKE '%" . $this->db->escape_like_str($keyword) . "%'
            )");
            $this->db->or_where("t.id_pengiriman_stok IN (
                SELECT dbps.id_pengiriman_stok 
                FROM detail_barang_pengiriman_stok dbps 
                LEFT JOIN barang b ON b.id_barang = dbps.id_barang 
                WHERE b.nama_barang LIKE '%" . $this->db->escape_like_str($keyword) . "%'
            )");
            $this->db->or_where("t.id_serah_terima_barang IN (
                SELECT d.id_stb 
                FROM surat_stb_detail d 
                WHERE d.nama_barang LIKE '%" . $this->db->escape_like_str($keyword) . "%'
            )");
            $this->db->group_end();
        }

        $columns = ['t.id_tracking', 't.no_sj', 'c.nama_customer', 'g.nama_gudang', 'barang_list', 't.tgl_sampai'];
        $orderCol = $columns[0];
        $orderDir = 'DESC';
        if (isset($_POST['order'][0]['column'])) {
            $colIdx = $_POST['order'][0]['column'];
            $orderCol = isset($columns[$colIdx]) ? $columns[$colIdx] : $columns[0];
            $orderDir = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
        }
        $this->db->order_by($orderCol, $orderDir);

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $data = $this->db->get()->result();

        $this->db->reset_query();
        $this->db->select('COUNT(*) as total');
        $this->db->from('tracking_barang t');
        $this->db->where('t.stat', 1);
        $recordsTotal = $this->db->get()->row()->total;

        $this->db->reset_query();
        $this->db->select('COUNT(DISTINCT t.id_tracking) as total');
        $this->db->from('tracking_barang t');
        $this->db->join('gudang g', 't.id_gudang=g.id_gudang', 'LEFT');
        $this->db->join('customer c', 't.id_customer=c.id_customer', 'LEFT');
        $this->db->join('tagihan_ekspedisi te', 't.id_tracking = te.id_tracking', 'LEFT');
        $this->db->where('t.stat', 1);

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('t.no_sj', $keyword);
            $this->db->or_like('c.nama_customer', $keyword);
            $this->db->or_like('g.nama_gudang', $keyword);
            $this->db->or_like('t.alamat_penerima', $keyword);
            $this->db->or_where("t.id_pengeluaran_barang IN (
                SELECT dbk.id_pengeluaran_barang 
                FROM detail_barang_keluar dbk 
                LEFT JOIN barang b ON b.id_barang = dbk.id_barang 
                WHERE b.nama_barang LIKE '%" . $this->db->escape_like_str($keyword) . "%'
            )");
            $this->db->group_end();
        }

        $recordsFiltered = $this->db->get()->row()->total;

        return [
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];
    }

    public function checkExistingInTracking($type, $id)
    {
        if ($type == 'pengeluaran_barang') {
            $this->db->where('id_pengeluaran_barang', $id);
        } elseif ($type == 'pengiriman_stok') {
            $this->db->where('id_pengiriman_stok', $id);
        } elseif ($type == 'serah_terima_barang') {
            $this->db->where('id_serah_terima_barang', $id);
        } elseif ($type == 'kirim_dokumen') {
            $this->db->where('id_kirim_dokumen', $id);
        } else {
            return null;
        }

        return $this->db->get('tracking_barang')->row();
    }

    public function getAllSJ()
    {
        $sql = 'SELECT 
                    pb.id_pengeluaran_barang,
                    pb.id_invoice,
                    pb.id_customer,
                    c.nama_customer,
                    c.contact,
                    pb.tgl_keluar,
                    pb.id_gudang,
                    g.nama_gudang,
                    pb.no_pengiriman,
                    pb.no_po,
                    pb.id_ekspedisi,
                    e.nama_ekspedisi,
                    pb.keterangan,
                    pb.resi,
                    pb.status_pengiriman,
                    pb.file_pendukung,
                    pb.alamat,
                    pb.status,
                    pb.data_created,
                    pb.from_invoice,
                    pb.perusahaan,
                    (SELECT COALESCE(pm.cro_date, pm.gm_date, pm.created_at) FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_cro_date
                FROM pengeluaran_barang pb
                LEFT JOIN customer c ON pb.id_customer = c.id_customer
                LEFT JOIN ekspedisi e ON pb.id_ekspedisi = e.id_ekspedisi
                LEFT JOIN gudang g ON pb.id_gudang = g.id_gudang
                WHERE pb.status = 1
                AND pb.id_pengeluaran_barang NOT IN (
                    SELECT id_pengeluaran_barang FROM tracking_barang 
                    WHERE id_pengeluaran_barang IS NOT NULL
                )
                ORDER BY pb.id_pengeluaran_barang DESC 
                LIMIT 350';

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getAllPengirimanStok()
    {
        $sql = 'SELECT 
                    ps.id_pengiriman_stok,
                    ps.no_pemindahan,
                    ps.tgl_pengiriman,
                    ps.id_gudang_asal,
                    g1.nama_gudang as nama_gudang_asal,
                    g1.alamat_gudang as alamat_gudang_asal,
                    ps.id_gudang_tujuan,
                    g2.nama_gudang as nama_gudang_tujuan,
                    g2.alamat_gudang as alamat_gudang_tujuan,
                    ps.id_ekspedisi,
                    e.nama_ekspedisi,
                    ps.no_resi,
                    ps.keterangan,
                    rsps.status_pengiriman
                FROM pengiriman_stok ps
                LEFT JOIN gudang g1 ON ps.id_gudang_asal = g1.id_gudang
                LEFT JOIN gudang g2 ON ps.id_gudang_tujuan = g2.id_gudang
                LEFT JOIN ekspedisi e ON ps.id_ekspedisi = e.id_ekspedisi
                LEFT JOIN riwayat_status_pengiriman_stok rsps ON rsps.id_latest_riwayatstatus_pengiriman = ps.id_latest_riwayatstatus_pengiriman
                WHERE ps.status = 1
                AND ps.id_pengiriman_stok NOT IN (
                    SELECT id_pengiriman_stok FROM tracking_barang 
                    WHERE id_pengiriman_stok IS NOT NULL
                )
                ORDER BY ps.id_pengiriman_stok DESC 
                LIMIT 350';

        $query = $this->db->query($sql);
        return $query->result();
    }

    function getByWhereIDExtended($where)
    {
        $this->db->select('
            t.id_tracking,
            t.id_gudang,
            t.id_customer,
            t.id_ekspedisi,
            t.nama_barang,
            t.alamat_penerima,
            t.nama_ekspedisi,
            t.tgl_pengiriman,
            t.tgl_sampai,
            t.waktu_po_cro,
            t.waktu_cetak_sj,
            t.waktu_order_ekspedisi,
            t.waktu_packaging,
            t.waktu_loading_handover,
            t.created_at,
            t.biaya,
            t.pic_penerima,
            t.no_sj,
            t.no_resi,
            t.link_resi,
            t.keterangan,
            t.stat,
            t.id_pengeluaran_barang,
            t.id_pengiriman_stok,
            t.id_serah_terima_barang,
            t.id_kirim_dokumen,
            t.tracking_type,
            g.nama_gudang,
            c.nama_customer,
            pb.no_po as pb_no_po,
            pb.data_created as pb_created_at,
            ps.id_gudang_tujuan,
            ps.data_created as ps_created_at,
            g2.nama_gudang as gudang_tujuan,
            g2.alamat_gudang as alamat_gudang_tujuan,
            stb.kode_stb,
            stb.kota_pengajuan,
            stb.tgl_pengajuan as tgl_pengajuan_stb,
            stb.created_at as stb_created_at,
            c_stb_pihak1.nama_customer as nama_pihak1_stb,
            c_stb_pihak1.alamat_customer as alamat_pihak1_stb,
            c_stb_pihak2.nama_customer as nama_pihak2_stb,
            c_stb_pihak2.alamat_customer as alamat_pihak2_stb,
            kd.kode as kode_kirim_dokumen,
            kd.nama_customer as nama_customer_kirim,
            kd.alamat as alamat_kirim,
            kd.pic as pic_kirim,
            kd.asal as asal_kirim,
            kd.marketing as marketing_kirim,
            kd.link_doc as link_doc_kirim,
            kd.tgl_kirim,
            kd.ekspedisi as ekspedisi_kirim,
            (SELECT pm.id_po FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as id_po_masuk,
            (SELECT pm.kode_masuk FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_kode_masuk,
            (SELECT pm.cro_date FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_cro_date,
            (SELECT pm.gm_date FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_gm_date,
            (SELECT pm.created_at FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_created_at,
            (SELECT pm.doc_gudang_packing_list FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_doc_packing_list,
            (SELECT MIN(ts1.created_at) FROM tracking_status ts1 WHERE ts1.id_tracking = t.id_tracking AND ts1.id_status >= 1) as status1_time,
            (SELECT MIN(ts7.created_at) FROM tracking_status ts7 WHERE ts7.id_tracking = t.id_tracking AND ts7.id_status = 7) as status_packaging_time,
            (SELECT MIN(ts2.created_at) FROM tracking_status ts2 WHERE ts2.id_tracking = t.id_tracking AND ts2.id_status IN (2,3,4,5)) as status2_time,
            (SELECT ts5.tgl_penerima FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as tgl_penerima_aktual,
            (SELECT ts5.nama_penerima FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as nama_penerima_aktual,
            (SELECT ts5.bukti_penerima FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as bukti_penerima_aktual,
            (SELECT ts5.created_at FROM tracking_status ts5 WHERE ts5.id_tracking = t.id_tracking AND ts5.id_status = 5 ORDER BY ts5.created_at DESC LIMIT 1) as status5_time,
            (SELECT ts_last.id_status FROM tracking_status ts_last WHERE ts_last.id_tracking = t.id_tracking ORDER BY ts_last.created_at DESC LIMIT 1) as id_status
        ')
            ->from('tracking_barang t')
            ->join('gudang g', 't.id_gudang=g.id_gudang', 'LEFT')
            ->join('customer c', 't.id_customer=c.id_customer', 'LEFT')
            ->join('pengeluaran_barang pb', 't.id_pengeluaran_barang=pb.id_pengeluaran_barang', 'LEFT')
            ->join('pengiriman_stok ps', 't.id_pengiriman_stok=ps.id_pengiriman_stok', 'LEFT')
            ->join('gudang g2', 'ps.id_gudang_tujuan=g2.id_gudang', 'LEFT')
            ->join('surat_stb stb', 't.id_serah_terima_barang=stb.id_stb', 'LEFT')
            ->join('customer c_stb_pihak1', 'stb.id_pihak1=c_stb_pihak1.id_customer', 'LEFT')
            ->join('customer c_stb_pihak2', 'stb.id_customer=c_stb_pihak2.id_customer', 'LEFT')
            ->join('kirim_dokumen kd', 't.id_kirim_dokumen=kd.id', 'LEFT')
            ->where($where)
            ->order_by('t.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getAllSerahTerimaBarang()
    {
        $sql = 'SELECT 
                    stb.id_stb,
                    stb.kode_stb,
                    stb.id_pihak1,
                    stb.id_customer,
                    stb.kota_pengajuan,
                    stb.tgl_pengajuan,
                    c1.nama_customer as nama_pihak1,
                    c1.alamat_customer as alamat_pihak1,
                    c2.nama_customer as nama_pihak2,
                    c2.alamat_customer as alamat_pihak2,
                    p.nama as nama_pengaju
                FROM surat_stb stb
                LEFT JOIN customer c1 ON stb.id_pihak1 = c1.id_customer
                LEFT JOIN customer c2 ON stb.id_customer = c2.id_customer
                LEFT JOIN pengguna p ON stb.id_pengaju = p.pengguna_id
                WHERE stb.id_stb NOT IN (
                    SELECT id_serah_terima_barang FROM tracking_barang 
                    WHERE id_serah_terima_barang IS NOT NULL
                )
                ORDER BY stb.id_stb DESC 
                LIMIT 350';

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getSerahTerimaBarangById($id_stb)
    {
        $this->db->select('
            stb.id_stb,
            stb.kode_stb,
            stb.id_pihak1,
            stb.id_customer,
            stb.kota_pengajuan,
            stb.tgl_pengajuan,
            c1.nama_customer as nama_pihak1,
            c1.alamat_customer as alamat_pihak1,
            c2.nama_customer as nama_pihak2,
            c2.alamat_customer as alamat_pihak2,
            p.nama as nama_pengaju
        ');
        $this->db->from('surat_stb stb');
        $this->db->join('customer c1', 'stb.id_pihak1 = c1.id_customer', 'LEFT');
        $this->db->join('customer c2', 'stb.id_customer = c2.id_customer', 'LEFT');
        $this->db->join('pengguna p', 'stb.id_pengaju = p.pengguna_id', 'LEFT');
        $this->db->where('stb.id_stb', $id_stb);
        return $this->db->get()->result();
    }

    public function getDetailSerahTerimaBarangById($id_stb)
    {
        $this->db->select('
            d.id_dstb,
            d.nomor,
            d.id_stb,
            d.nama_barang,
            d.merk,
            d.no_batch,
            d.qty,
            d.satuan,
            d.ket,
            d.status
        ');
        $this->db->from('surat_stb_detail d');
        $this->db->where('d.id_stb', $id_stb);
        $this->db->where('d.status', 1);
        return $this->db->get()->result();
    }

    public function getAllKirimDokumen()
    {
        $sql = 'SELECT 
                    kd.id as id_kirim_dokumen,
                    kd.kode,
                    kd.nama_customer,
                    kd.alamat,
                    kd.pic,
                    kd.asal,
                    kd.marketing,
                    kd.ekspedisi,
                    kd.id_ekspedisi,
                    kd.no_resi,
                    kd.link_resi,
                    kd.keterangan,
                    kd.tgl_kirim,
                    kd.tgl_sampai,
                    kd.created_at
                FROM kirim_dokumen kd
                WHERE kd.id NOT IN (
                    SELECT id_kirim_dokumen FROM tracking_barang 
                    WHERE id_kirim_dokumen IS NOT NULL
                )
                ORDER BY kd.id DESC 
                LIMIT 350';

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getKirimDokumenById($id_kirim_dokumen)
    {
        $this->db->select('
            kd.id,
            kd.kode,
            kd.nama_customer,
            kd.alamat,
            kd.pic,
            kd.asal,
            kd.marketing,
            kd.ekspedisi,
            kd.no_resi,
            kd.link_resi,
            kd.link_doc,
            kd.keterangan,
            kd.tgl_kirim,
            kd.tgl_sampai,
            kd.created_at,
            kd.id_pengguna,
            p.nama as nama_pengaju
        ');
        $this->db->from('kirim_dokumen kd');
        $this->db->join('pengguna p', 'kd.id_pengguna = p.pengguna_id', 'LEFT');
        $this->db->where('kd.id', $id_kirim_dokumen);
        return $this->db->get()->result();
    }

    public function getAllTrackingDokumen()
    {
        $sql = "SELECT 
                    ks.id,
                    ks.id_kirim,
                    ks.id_status,
                    ks.created_at as tgl_update,
                    kd.kode,
                    kd.nama_customer,
                    kd.ekspedisi,
                    kd.no_resi,
                    kd.tgl_kirim,
                    kd.tgl_sampai,
                    kd.alamat,
                    kd.pic,
                    CASE ks.id_status
                        WHEN 0 THEN 'Pending'
                        WHEN 1 THEN 'Proses Kirim'
                        WHEN 2 THEN 'Pickup'
                        WHEN 3 THEN 'On Transit'
                        WHEN 4 THEN 'Out for Delivery'
                        WHEN 5 THEN 'Diterima'
                        ELSE 'Unknown'
                    END as status_label
                FROM kirim_status ks
                INNER JOIN kirim_dokumen kd ON ks.id_kirim = kd.id
                WHERE ks.id IN (
                    SELECT MAX(id) 
                    FROM kirim_status 
                    GROUP BY id_kirim
                )
                ORDER BY ks.created_at DESC";

        return $this->db->query($sql)->result();
    }

    function getTrackingByTgl($tglawal, $tglakhir)
    {
        $subquery = $this->db->select('id_tracking, MAX(created_at) as latest_created_at')
            ->from('tracking_status')
            ->group_by('id_tracking')
            ->get_compiled_select();

        return $this->db
            ->select('
                t.id_tracking,
                t.id_gudang,
                t.id_customer,
                t.nama_barang,
                t.alamat_penerima,
                t.nama_ekspedisi,
                t.tgl_pengiriman,
                t.tgl_sampai,
                t.waktu_po_cro,
                t.waktu_cetak_sj,
                t.waktu_order_ekspedisi,
                t.waktu_packaging,
                t.waktu_loading_handover,
                t.biaya,
                t.pic_penerima,
                t.no_sj,
                t.no_resi,
                t.link_resi,
                t.keterangan,
                t.stat,
                t.created_at AS createdAt,
                g.nama_gudang,
                c.nama_customer,
                pb.no_po as pb_no_po,
                pb.data_created as pb_created_at,
                ps.data_created as ps_created_at,
                stb.created_at as stb_created_at,
                (SELECT pm.cro_date FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_cro_date,
                (SELECT pm.gm_date FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_gm_date,
                (SELECT pm.created_at FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_created_at,
                (SELECT pm.doc_gudang_packing_list FROM po_masuk pm WHERE pm.no_po = pb.no_po AND pb.no_po IS NOT NULL AND pb.no_po != "" AND pm.deleted = 0 ORDER BY pm.id_po DESC LIMIT 1) as po_doc_packing_list,
                (SELECT MIN(ts1.created_at) FROM tracking_status ts1 WHERE ts1.id_tracking = t.id_tracking AND ts1.id_status >= 1) as status1_time,
                (SELECT MIN(ts7.created_at) FROM tracking_status ts7 WHERE ts7.id_tracking = t.id_tracking AND ts7.id_status = 7) as status_packaging_time,
                (SELECT MIN(ts2.created_at) FROM tracking_status ts2 WHERE ts2.id_tracking = t.id_tracking AND ts2.id_status IN (2,3,4,5)) as status2_time,
                ts.id_tracking,
                ts.id_status,
                ts.nama_penerima,
                ts.tgl_penerima,
                ts.bukti_penerima,
                ts.created_at AS statusCreatedAt
            ')
            ->from('tracking_barang t')
            ->join('gudang g', 't.id_gudang = g.id_gudang', 'LEFT')
            ->join('customer c', 't.id_customer = c.id_customer', 'LEFT')
            ->join('pengeluaran_barang pb', 't.id_pengeluaran_barang = pb.id_pengeluaran_barang', 'LEFT')
            ->join('pengiriman_stok ps', 't.id_pengiriman_stok = ps.id_pengiriman_stok', 'LEFT')
            ->join('surat_stb stb', 't.id_serah_terima_barang = stb.id_stb', 'LEFT')
            ->join('tracking_status ts', 't.id_tracking = ts.id_tracking', 'LEFT')
            ->join("($subquery) latest_ts", 'ts.id_tracking = latest_ts.id_tracking AND ts.created_at = latest_ts.latest_created_at', 'LEFT')
            ->where('t.stat', 1)
            ->where('t.created_at >=', date('Y-m-d 00:00:00', strtotime($tglawal)))
            ->where('t.created_at <=', date('Y-m-d 23:59:59', strtotime($tglakhir)))
            ->order_by('t.id_tracking', 'DESC')
            ->get()
            ->result();
    }

    public function resolveWarehouseMilestoneTimes($row)
    {
        $cleanDt = function ($dt) {
            if (empty($dt)) return null;
            $s = trim((string)$dt);
            if ($s === '' || strpos($s, '0000-00-00') !== false) return null;
            return $s;
        };

        // 1. Waktu PO diterima dari CRO
        $waktuPoCro = $cleanDt($row->waktu_po_cro ?? null);
        if (!$waktuPoCro) {
            $waktuPoCro = $cleanDt($row->po_cro_date ?? null)
                ?: ($cleanDt($row->po_gm_date ?? null) ?: $cleanDt($row->po_created_at ?? null));
        }

        // 2. Waktu Cetak SJ / Dokumen Keluar
        $waktuCetakSj = $cleanDt($row->waktu_cetak_sj ?? null);
        if (!$waktuCetakSj) {
            $waktuCetakSj = $cleanDt($row->pb_created_at ?? null)
                ?: ($cleanDt($row->ps_created_at ?? null)
                ?: ($cleanDt($row->stb_created_at ?? null)
                ?: ($cleanDt($row->tgl_pengiriman ?? null) ? $row->tgl_pengiriman . ' 08:00:00' : $cleanDt($row->created_at ?? $row->createdAt ?? null))));
        }

        // 3. Waktu Order Ekspedisi
        $waktuOrderEks = $cleanDt($row->waktu_order_ekspedisi ?? null);
        if (!$waktuOrderEks) {
            $waktuOrderEks = $cleanDt($row->status1_time ?? null) ?: $cleanDt($row->created_at ?? $row->createdAt ?? null);
        }

        // 4. Waktu Loading / Hand-over ke Ekspedisi
        $waktuLoading = $cleanDt($row->waktu_loading_handover ?? null);
        if (!$waktuLoading) {
            $waktuLoading = $cleanDt($row->status2_time ?? null);
            if (!$waktuLoading && !empty($row->no_resi) && trim((string)$row->no_resi) !== '-') {
                $waktuLoading = $waktuOrderEks;
            }
        }

        // 3b. Waktu Packaging (antara Order Ekspedisi dan Loading / Hand-over)
        $waktuPackaging = $cleanDt($row->waktu_packaging ?? null);
        if (!$waktuPackaging) {
            $waktuPackaging = $cleanDt($row->status_packaging_time ?? null);
            if (!$waktuPackaging && $waktuLoading) {
                $waktuPackaging = $waktuOrderEks ?: $waktuLoading;
            }
        }

        // Hitung kecepatan menggunakan standar indikator formatApprovalDurationInfo
        $calcDur = function ($start, $end) {
            if (function_exists('formatApprovalDurationInfo')) {
                return formatApprovalDurationInfo($start, $end, false);
            }
            return ['valid' => false, 'text' => '-', 'tier' => 'none', 'label_tier' => ''];
        };

        $durPoToSj        = ($waktuPoCro && $waktuCetakSj) ? $calcDur($waktuPoCro, $waktuCetakSj) : $calcDur(null, null);
        $durSjToOrder     = ($waktuCetakSj && $waktuOrderEks) ? $calcDur($waktuCetakSj, $waktuOrderEks) : $calcDur(null, null);
        $durOrderToPack   = ($waktuOrderEks && $waktuPackaging) ? $calcDur($waktuOrderEks, $waktuPackaging) : $calcDur(null, null);
        $durPackToLoad    = ($waktuPackaging && $waktuLoading) ? $calcDur($waktuPackaging, $waktuLoading) : $calcDur(null, null);
        $durOrderToLoad   = ($waktuOrderEks && $waktuLoading) ? $calcDur($waktuOrderEks, $waktuLoading) : $calcDur(null, null);
        $durSjToLoadTotal = ($waktuCetakSj && $waktuLoading) ? $calcDur($waktuCetakSj, $waktuLoading) : $calcDur(null, null);

        // --- Ketepatan Pengiriman (On-Time Delivery: Estimasi Sampai vs Tanggal Diterima) ---
        $tglEstimasi = $cleanDt($row->tgl_sampai ?? null);
        $tglDiterima = $cleanDt($row->tgl_penerima_aktual ?? ($row->tgl_penerima ?? null));
        $curStatus   = (int)($row->id_status ?? 0);
        if ($curStatus === 5 && !$tglDiterima) {
            $st5Time = $cleanDt($row->status5_time ?? ($row->statusCreatedAt ?? null));
            if ($st5Time) {
                $tglDiterima = date('Y-m-d', strtotime($st5Time));
            }
        }

        $ketepatan = [
            'status'       => 'in_progress',
            'label'        => 'Dalam Pengiriman',
            'selisih_hari' => 0,
            'badge_bg'     => '#eff6ff',
            'badge_text'   => '#1d4ed8',
            'badge_bdr'    => '#bfdbfe',
            'icon'         => 'fa-shipping-fast',
            'tgl_estimasi' => $tglEstimasi ? date('Y-m-d', strtotime($tglEstimasi)) : null,
            'tgl_diterima' => $tglDiterima ? date('Y-m-d', strtotime($tglDiterima)) : null
        ];

        if (!empty($ketepatan['tgl_estimasi'])) {
            $tsEst = strtotime($ketepatan['tgl_estimasi'] . ' 23:59:59');
            if ($curStatus === 5 && !empty($ketepatan['tgl_diterima'])) {
                $tsAct = strtotime($ketepatan['tgl_diterima'] . ' 00:00:00');
                $diffDays = (int)floor(($tsAct - strtotime($ketepatan['tgl_estimasi'] . ' 00:00:00')) / 86400);
                $ketepatan['selisih_hari'] = $diffDays;
                if ($diffDays <= 0) {
                    $ketepatan['status']     = 'on_time';
                    $ketepatan['label']      = ($diffDays < 0) ? ('Tepat Waktu (' . abs($diffDays) . ' Hr Lebih Cepat)') : 'Tepat Waktu (On-Time)';
                    $ketepatan['badge_bg']   = '#dcfce7';
                    $ketepatan['badge_text'] = '#166534';
                    $ketepatan['badge_bdr']  = '#86efac';
                    $ketepatan['icon']       = 'fa-check-circle';
                } else {
                    $ketepatan['status']     = 'late';
                    $ketepatan['label']      = 'Terlambat ' . $diffDays . ' Hari';
                    $ketepatan['badge_bg']   = '#fee2e2';
                    $ketepatan['badge_text'] = '#991b1b';
                    $ketepatan['badge_bdr']  = '#fca5a5';
                    $ketepatan['icon']       = 'fa-exclamation-triangle';
                }
            } else {
                $todayTs = strtotime(date('Y-m-d') . ' 00:00:00');
                $estDayTs = strtotime($ketepatan['tgl_estimasi'] . ' 00:00:00');
                $diffDays = (int)floor(($todayTs - $estDayTs) / 86400);
                $ketepatan['selisih_hari'] = $diffDays;
                if ($diffDays > 0) {
                    $ketepatan['status']     = 'overdue';
                    $ketepatan['label']      = 'Melewati Estimasi ' . $diffDays . ' Hari';
                    $ketepatan['badge_bg']   = '#fef3c7';
                    $ketepatan['badge_text'] = '#92400e';
                    $ketepatan['badge_bdr']  = '#fcd34d';
                    $ketepatan['icon']       = 'fa-clock';
                } else {
                    $sisa = abs($diffDays);
                    $ketepatan['status']     = 'on_schedule';
                    $ketepatan['label']      = ($sisa === 0) ? 'Jadwal Tiba Hari Ini' : ('Dalam Jadwal (H-' . $sisa . ')');
                    $ketepatan['badge_bg']   = '#e0f2fe';
                    $ketepatan['badge_text'] = '#0369a1';
                    $ketepatan['badge_bdr']  = '#bae6fd';
                    $ketepatan['icon']       = 'fa-truck-moving';
                }
            }
        } else {
            $ketepatan['label']      = 'Estimasi Belum Diisi';
            $ketepatan['badge_bg']   = '#f1f5f9';
            $ketepatan['badge_text'] = '#64748b';
            $ketepatan['badge_bdr']  = '#cbd5e1';
            $ketepatan['icon']       = 'fa-minus-circle';
        }

        // --- Kepatuhan SOP Warehouse ---
        $hasResi = (!empty($row->no_resi) && trim((string)$row->no_resi) !== '-') || (!empty($row->link_resi) && trim((string)$row->link_resi) !== '-');
        $isDirectDelivery = in_array(trim((string)($row->nama_ekspedisi ?? '')), ['Diantarkan Langsung', 'Dijemput Langsung'], true);
        $hasPod  = ($curStatus === 5) && (!empty($row->nama_penerima_aktual ?? ($row->nama_penerima ?? null)) || !empty($row->bukti_penerima_aktual ?? ($row->bukti_penerima ?? null)));

        $sopChecks = [
            ['label' => 'PO CRO / Dokumen Sumber', 'done' => !empty($waktuPoCro) || !empty($waktuCetakSj)],
            ['label' => 'Cetak SJ / Dokumen Keluar', 'done' => !empty($waktuCetakSj)],
            ['label' => 'Order Ekspedisi', 'done' => !empty($waktuOrderEks) && !empty($row->nama_ekspedisi)],
            ['label' => 'Packaging & QC Barang', 'done' => !empty($waktuPackaging)],
            ['label' => 'Loading / Hand-over & Resi', 'done' => !empty($waktuLoading) && ($hasResi || $isDirectDelivery)],
            ['label' => 'Bukti Serah Terima (POD)', 'done' => $hasPod]
        ];

        $sopDoneCount  = 0;
        foreach ($sopChecks as $sc) {
            if ($sc['done']) $sopDoneCount++;
        }
        $sopTotalCount = count($sopChecks);
        $sopPercent    = (int)round(($sopDoneCount / $sopTotalCount) * 100);

        if ($sopDoneCount === $sopTotalCount) {
            $sopLabel = 'SOP Lengkap (100%)';
            $sopBg    = '#dcfce7';
            $sopText  = '#166534';
            $sopBdr   = '#86efac';
        } elseif ($sopDoneCount >= 4) {
            $sopLabel = 'SOP Berjalan (' . $sopDoneCount . '/' . $sopTotalCount . ')';
            $sopBg    = '#e0f2fe';
            $sopText  = '#0369a1';
            $sopBdr   = '#bae6fd';
        } else {
            $sopLabel = 'SOP Belum Lengkap (' . $sopDoneCount . '/' . $sopTotalCount . ')';
            $sopBg    = '#fef3c7';
            $sopText  = '#92400e';
            $sopBdr   = '#fcd34d';
        }

        return [
            'no_po'              => !empty($row->pb_no_po) ? $row->pb_no_po : null,
            'waktu_po_cro'       => $waktuPoCro,
            'waktu_cetak_sj'     => $waktuCetakSj,
            'waktu_order_eks'    => $waktuOrderEks,
            'waktu_packaging'    => $waktuPackaging,
            'waktu_loading'      => $waktuLoading,
            'dur_po_to_sj'       => $durPoToSj,
            'dur_sj_to_order'    => $durSjToOrder,
            'dur_order_to_pack'  => $durOrderToPack,
            'dur_pack_to_load'   => $durPackToLoad,
            'dur_order_to_load'  => $durOrderToLoad,
            'dur_sj_to_load'     => $durSjToLoadTotal,
            'ketepatan'          => $ketepatan,
            'sop'                => [
                'checks'     => $sopChecks,
                'done_count' => $sopDoneCount,
                'total'      => $sopTotalCount,
                'percent'    => $sopPercent,
                'label'      => $sopLabel,
                'badge_bg'   => $sopBg,
                'badge_text' => $sopText,
                'badge_bdr'  => $sopBdr
            ]
        ];
    }

    function getUpdateById($where)
    {
        // Deteksi tipe query berdasarkan key where
        if (isset($where['t.id_kirim'])) {
            // Query untuk kirim_dokumen status
            $this->db->select('
            t.id,
            t.id_kirim,
            t.id_status,
            t.keterangan_konfirmasi,
            t.created_at,
            t.nama_penerima,
            t.tgl_penerima,
            t.bukti_penerima,
            p1.nama as nama_pembuat
        ')
                ->from('kirim_status t')
                ->join('pengguna p1', 't.id_pengguna=p1.pengguna_id', 'left')
                ->where($where)
                ->order_by('t.created_at', 'DESC');
        } else {
            // Query untuk tracking_barang status (default)
            $this->db->select('
            t.id,
            t.id_tracking,
            t.bukti_penerima,
            t.nama_penerima,
            t.tgl_penerima,
            t.id_status,
            t.keterangan_konfirmasi,
            t.created_at,
            p1.nama as nama_pembuat
        ')
                ->from('tracking_status t')
                ->join('pengguna p1', 't.id_pengguna=p1.pengguna_id', 'left')
                ->where($where)
                ->order_by('t.created_at', 'DESC');
        }

        return $this->db->get()->result();
    }
    function getHistoryStatus($id_tracking)
    {
        $this->db->select('ts.*, p.nama as nama_admin');
        $this->db->from('tracking_status ts');
        $this->db->join('pengguna p', 'ts.id_pengguna = p.pengguna_id', 'left');
        $this->db->where('ts.id_tracking', $id_tracking);
        $this->db->order_by('ts.created_at', 'ASC');
        return $this->db->get()->result();
    }

    public function update_kirim($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('kirim_dokumen', $data);
    }

    public function update_history($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tracking_status', $data);
    }

    /**
     * Update History Status - SAFE VERSION WITH BETTER VALIDATION
     * 
     * @param int $id - ID dari record di tabel tracking_status atau kirim_status
     * @param array $data - Data yang akan diupdate
     * @param string $type - 'barang' atau 'dokumen'
     * @return int - Jumlah baris yang berubah (-1 jika error)
     */
    public function update_history_safe($id, $data, $type = 'barang')
    {
        if (empty($id) || !is_numeric($id)) {
            log_message('error', 'update_history_safe: ID tidak valid');
            return -1;
        }

        if (empty($data) || !is_array($data)) {
            log_message('error', 'update_history_safe: Data tidak valid');
            return -1;
        }

        // Pilih tabel berdasarkan tipe
        $table = ($type === 'dokumen') ? 'kirim_status' : 'tracking_status';

        // Validasi: Cek apakah record exists
        $existing = $this->db->where('id', $id)->get($table);
        if ($existing->num_rows() == 0) {
            log_message('error', "update_history_safe: Record ID $id tidak ditemukan di tabel $table");
            return -1;
        }

        // Mulai transaksi
        $this->db->trans_start();

        // Update data
        $this->db->where('id', $id);
        $this->db->update($table, $data);

        // Complete transaksi
        $this->db->trans_complete();

        // Cek status transaksi
        if ($this->db->trans_status() === FALSE) {
            log_message('error', "update_history_safe: Transaksi gagal untuk ID $id di tabel $table");
            return -1;
        }

        $affected = $this->db->affected_rows();
        log_message('info', "update_history_safe: Berhasil update ID $id di $table (affected rows: $affected)");

        return $affected;
    }
    /**
     * Update Kirim Dokumen - SAFE VERSION
     * 
     * @param int $id - ID kirim_dokumen
     * @param array $data - Data yang akan diupdate
     * @return int - Jumlah baris yang berubah (-1 jika error)
     */
    public function update_kirim_safe($id, $data)
    {
        if (empty($id) || !is_numeric($id)) {
            log_message('error', 'update_kirim_safe: ID tidak valid');
            return -1;
        }

        if (empty($data) || !is_array($data)) {
            log_message('error', 'update_kirim_safe: Data tidak valid');
            return -1;
        }

        // Validasi: Cek apakah record exists di kirim_dokumen
        $existing = $this->db->where('id', $id)->get('kirim_dokumen');
        if ($existing->num_rows() == 0) {
            log_message('error', "update_kirim_safe: Dokumen ID $id tidak ditemukan");
            return -1;
        }

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('kirim_dokumen', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', "update_kirim_safe: Transaksi gagal untuk dokumen ID $id");
            return -1;
        }

        $affected = $this->db->affected_rows();
        log_message('info', "update_kirim_safe: Berhasil update dokumen ID $id (affected rows: $affected)");

        return $affected;
    }

    /**
     * Get Status History by ID untuk validasi
     */
    public function getStatusHistoryById($id, $type = 'barang')
    {
        $table = ($type === 'dokumen') ? 'kirim_status' : 'tracking_status';

        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function getKirimDokumenByIdExtended($id)
    {
        $this->db->select('
        kd.*,
        e.nama_ekspedisi
    ');
        $this->db->from('kirim_dokumen kd');
        $this->db->join('ekspedisi e', 'kd.id_ekspedisi = e.id_ekspedisi', 'LEFT');
        $this->db->where('kd.id', $id);

        return $this->db->get()->result();
    }

    /**
     * Get Status History untuk Kirim Dokumen
     */
    public function getKirimDokumenStatusHistory($id_kirim)
    {
        $this->db->select('
        ks.*,
        p.nama as nama_pengguna
    ');
        $this->db->from('kirim_status ks');
        $this->db->join('pengguna p', 'ks.id_pengguna = p.pengguna_id', 'LEFT');
        $this->db->where('ks.id_kirim', $id_kirim);
        $this->db->order_by('ks.created_at', 'ASC');

        return $this->db->get()->result();
    }

    public function deleteStatusHistory($id, $type = 'barang')
    {
        $table = ($type === 'dokumen') ? 'kirim_status' : 'tracking_status';

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->delete($table);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }

        return $this->db->affected_rows() > 0;
    }

    // =========================================================================
    // SISTEM TRACKING IMPORT (9 TAHAP: PO MANUFACTURE -> GUDANG PUSAT/JKT/JOGJA)
    // =========================================================================

    public function getImportStageDefinitions()
    {
        return [
            1 => [
                'field' => 'waktu_1_po_manufacture',
                'label' => '1. PO ke Manufacture',
                'role'  => 'Purchasing / Pengadaan Import',
                'short' => 'PO Manufacture'
            ],
            2 => [
                'field' => 'waktu_2_order_forwarder',
                'label' => '2. Order Ekspedisi / Forwarder',
                'role'  => 'Booking Space & AWB / B/L',
                'short' => 'Order Forwarder'
            ],
            3 => [
                'field' => 'waktu_3_loading_kapal',
                'label' => '3. Unit Loading Kapal / Pesawat',
                'role'  => 'Port of Loading (Origin)',
                'short' => 'Loading Kapal'
            ],
            4 => [
                'field' => 'waktu_4_perjalanan_kapal',
                'label' => '4. Perjalanan Kapal / Pesawat',
                'role'  => 'In Transit Internasional',
                'short' => 'Perjalanan Kapal'
            ],
            5 => [
                'field' => 'waktu_5_sampai_port',
                'label' => '5. Sampai Seaport / Airport',
                'role'  => 'Port of Discharge (Indonesia)',
                'short' => 'Sampai Seaport/Airport'
            ],
            6 => [
                'field' => 'waktu_6_bayar_pajak',
                'label' => '6. Pembayaran Pajak & Bea Cukai',
                'role'  => 'PIB, Bea Masuk, PPN & PPh Impor',
                'short' => 'Pembayaran Pajak'
            ],
            7 => [
                'field' => 'waktu_7_keluar_port',
                'label' => '7. Keluar Gudang Seaport / Airport',
                'role'  => 'SPPB / Custom Clearance Release',
                'short' => 'Keluar Seaport/Airport'
            ],
            8 => [
                'field' => 'waktu_8_handover_domestik',
                'label' => '8. Hand-over Ekspedisi Domestik / Lokal',
                'role'  => 'Pengiriman Lokal ke Gudang VYM',
                'short' => 'Hand-over Domestik'
            ],
            9 => [
                'field' => 'waktu_9_sampai_gudang',
                'label' => '9. Sampai Gudang Pusat / Jakarta / Jogja',
                'role'  => 'Penerimaan & QC Warehouse VYM',
                'short' => 'Sampai Gudang VYM'
            ]
        ];
    }

    public function generateKodeImport()
    {
        $prefix = 'IMP-' . date('Ym') . '-';
        $this->db->select('kode_import');
        $this->db->like('kode_import', $prefix, 'after');
        $this->db->order_by('id_import', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('tracking_import')->row();

        if ($last && !empty($last->kode_import)) {
            $parts = explode('-', $last->kode_import);
            $num = (int)end($parts) + 1;
        } else {
            $num = 1;
        }
        return $prefix . sprintf('%03d', $num);
    }

    public function getAllPoSupplierForImport()
    {
        if (!$this->db->table_exists('purchase_order')) {
            return [];
        }
        $sql = "SELECT 
                    po.id as id_po,
                    po.kode_po,
                    po.no_po,
                    po.tgl_pengajuan,
                    (SELECT pod.supplier FROM purchase_order_detail pod WHERE pod.id_po = po.id AND pod.supplier IS NOT NULL AND pod.supplier != '' LIMIT 1) as supplier,
                    (SELECT GROUP_CONCAT(CONCAT(pod.nama_barang, ' (', COALESCE(pod.rencana_po, pod.kebutuhan_po, '1'), ')') SEPARATOR ', ') FROM purchase_order_detail pod WHERE pod.id_po = po.id) as items_summary
                FROM purchase_order po
                WHERE po.status != 5
                ORDER BY po.id DESC
                LIMIT 150";
        return $this->db->query($sql)->result();
    }

    public function getAllForwarderMaster()
    {
        if (!$this->db->table_exists('forwarder')) {
            return [];
        }
        return $this->db->where('status', 1)->order_by('nama', 'ASC')->get('forwarder')->result();
    }

    public function getAllTrackingImport($filter_gudang = null, $filter_stage = null)
    {
        $this->db->select('ti.*, u.nama as nama_pembuat');
        $this->db->from('tracking_import ti');
        $this->db->join('pengguna u', 'ti.created_by = u.pengguna_id', 'left');
        $this->db->where('ti.stat', 1);

        if (!empty($filter_gudang)) {
            $this->db->where('ti.nama_gudang_tujuan', $filter_gudang);
        }
        if (!empty($filter_stage)) {
            $this->db->where('ti.current_stage', (int)$filter_stage);
        }

        $this->db->order_by('ti.id_import', 'DESC');
        return $this->db->get()->result();
    }

    public function getTrackingImportById($id_import)
    {
        $this->db->select('ti.*, u.nama as nama_pembuat');
        $this->db->from('tracking_import ti');
        $this->db->join('pengguna u', 'ti.created_by = u.pengguna_id', 'left');
        $this->db->where('ti.id_import', $id_import);
        $this->db->where('ti.stat', 1);
        return $this->db->get()->row();
    }

    public function getTrackingImportLogs($id_import)
    {
        $this->db->select('l.*, p.nama as nama_pengguna');
        $this->db->from('tracking_import_log l');
        $this->db->join('pengguna p', 'l.id_pengguna = p.pengguna_id', 'left');
        $this->db->where('l.id_import', $id_import);
        $this->db->order_by('l.waktu_milestone', 'DESC');
        $this->db->order_by('l.id', 'DESC');
        return $this->db->get()->result();
    }

    public function resolveImportMetrics($row)
    {
        $defs = $this->getImportStageDefinitions();
        $cleanDt = function ($dt) {
            if (empty($dt)) return null;
            $s = trim((string)$dt);
            if ($s === '' || strpos($s, '0000-00-00') !== false) return null;
            return $s;
        };

        $calcDur = function ($start, $end) {
            if (function_exists('formatApprovalDurationInfo')) {
                return formatApprovalDurationInfo($start, $end, false);
            }
            return ['valid' => false, 'text' => '-', 'tier' => 'none', 'label_tier' => ''];
        };

        $times = [];
        foreach ($defs as $num => $d) {
            $field = $d['field'];
            $times[$num] = $cleanDt($row->$field ?? null);
        }

        $stepDurations = [];
        $prevTime = $times[1];
        for ($i = 2; $i <= 9; $i++) {
            $curTime = $times[$i];
            $stepDurations[$i] = ($prevTime && $curTime) ? $calcDur($prevTime, $curTime) : $calcDur(null, null);
            if ($curTime) {
                $prevTime = $curTime;
            }
        }

        // Ringkasan 4 Segmen Utama Import
        // 1. Produksi & Origin (PO Manufacture -> Loading Kapal)
        $durOrigin   = ($times[1] && $times[3]) ? $calcDur($times[1], $times[3]) : $calcDur(null, null);
        // 2. Freight Transit (Loading Kapal -> Sampai Seaport/Airport)
        $durFreight  = ($times[3] && $times[5]) ? $calcDur($times[3], $times[5]) : $calcDur(null, null);
        // 3. Pajak & Customs Clearance (Sampai Seaport/Airport -> Keluar Gudang Seaport/Airport)
        $durCustoms  = ($times[5] && $times[7]) ? $calcDur($times[5], $times[7]) : $calcDur(null, null);
        // 4. Domestik ke Gudang VYM (Keluar Seaport/Airport -> Sampai Gudang Pusat/JKT/Jogja)
        $durDomestik = ($times[7] && $times[9]) ? $calcDur($times[7], $times[9]) : $calcDur(null, null);
        // 5. Total End-to-End Import (PO Manufacture -> Sampai Gudang VYM)
        $durTotal    = ($times[1] && $times[9]) ? $calcDur($times[1], $times[9]) : $calcDur(null, null);

        // Ketepatan Pengiriman Import (Estimasi Sampai Gudang vs Aktual Sampai Gudang)
        $estGudang = $cleanDt($row->tgl_estimasi_sampai_gudang ?? null);
        $actGudang = $times[9] ? date('Y-m-d', strtotime($times[9])) : null;

        $ketepatan = [
            'label'      => 'Estimasi Belum Diisi',
            'badge_bg'   => '#f1f5f9',
            'badge_text' => '#64748b',
            'badge_bdr'  => '#cbd5e1',
            'icon'       => 'fa-clock'
        ];

        if ($estGudang) {
            if ($actGudang) {
                $diffDays = (int)floor((strtotime($actGudang) - strtotime($estGudang)) / 86400);
                if ($diffDays <= 0) {
                    $ketepatan['label']      = ($diffDays < 0) ? ('Tepat Waktu (' . abs($diffDays) . ' Hr Lebih Cepat)') : 'Tepat Waktu (On-Time)';
                    $ketepatan['badge_bg']   = '#dcfce7';
                    $ketepatan['badge_text'] = '#166534';
                    $ketepatan['badge_bdr']  = '#86efac';
                    $ketepatan['icon']       = 'fa-check-circle';
                } else {
                    $ketepatan['label']      = 'Terlambat ' . $diffDays . ' Hari';
                    $ketepatan['badge_bg']   = '#fee2e2';
                    $ketepatan['badge_text'] = '#991b1b';
                    $ketepatan['badge_bdr']  = '#fca5a5';
                    $ketepatan['icon']       = 'fa-exclamation-triangle';
                }
            } else {
                $diffDays = (int)floor((strtotime(date('Y-m-d')) - strtotime($estGudang)) / 86400);
                if ($diffDays > 0) {
                    $ketepatan['label']      = 'Melewati Estimasi ' . $diffDays . ' Hari';
                    $ketepatan['badge_bg']   = '#fef3c7';
                    $ketepatan['badge_text'] = '#92400e';
                    $ketepatan['badge_bdr']  = '#fcd34d';
                    $ketepatan['icon']       = 'fa-exclamation-circle';
                } else {
                    $ketepatan['label']      = 'Dalam Jadwal (H-' . abs($diffDays) . ')';
                    $ketepatan['badge_bg']   = '#e0f2fe';
                    $ketepatan['badge_text'] = '#0369a1';
                    $ketepatan['badge_bdr']  = '#bae6fd';
                    $ketepatan['icon']       = 'fa-ship';
                }
            }
        }

        $completedCount = 0;
        foreach ($times as $t) {
            if (!empty($t)) $completedCount++;
        }

        return [
            'times'          => $times,
            'step_durations' => $stepDurations,
            'dur_origin'     => $durOrigin,
            'dur_freight'    => $durFreight,
            'dur_customs'    => $durCustoms,
            'dur_domestik'   => $durDomestik,
            'dur_total'      => $durTotal,
            'ketepatan'      => $ketepatan,
            'completed_cnt'  => $completedCount,
            'progress_pct'   => (int)round(($completedCount / 9) * 100)
        ];
    }
}
