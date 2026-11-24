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
            ORDER BY ts.created_at DESC, ts.id DESC LIMIT 1) AS id_status
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
        if ($orderCol === 't.id_tracking') {
            $this->db->order_by("COALESCE(NULLIF(t.tgl_pengiriman, '0000-00-00'), DATE(t.created_at))", $orderDir, false);
            $this->db->order_by('t.id_tracking', $orderDir);
        } else {
            $this->db->order_by($orderCol, $orderDir);
        }

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
            (SELECT ts_last.id_status FROM tracking_status ts_last WHERE ts_last.id_tracking = t.id_tracking ORDER BY ts_last.created_at DESC, ts_last.id DESC LIMIT 1) as id_status
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

        $curStatus = (isset($row->id_status) && $row->id_status !== '' && $row->id_status !== null) ? (int)$row->id_status : 0;

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
        if (!$waktuOrderEks && $curStatus >= 1) {
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
            if ($curStatus === 5) {
                $ketepatan['status']     = 'delivered_no_est';
                $ketepatan['label']      = 'Selesai (Diterima)';
                $ketepatan['badge_bg']   = '#dcfce7';
                $ketepatan['badge_text'] = '#166534';
                $ketepatan['badge_bdr']  = '#86efac';
                $ketepatan['icon']       = 'fa-check-circle';
            } else {
                $ketepatan['status']     = 'no_estimate';
                $ketepatan['label']      = 'Estimasi Belum Diisi';
                $ketepatan['badge_bg']   = '#f1f5f9';
                $ketepatan['badge_text'] = '#64748b';
                $ketepatan['badge_bdr']  = '#cbd5e1';
                $ketepatan['icon']       = 'fa-minus-circle';
            }
        }

        // --- Kepatuhan SOP Warehouse ---
        $hasResi = (!empty($row->no_resi) && trim((string)$row->no_resi) !== '-') || (!empty($row->link_resi) && trim((string)$row->link_resi) !== '-');
        $hasEkspedisi = !empty($row->nama_ekspedisi) && trim((string)$row->nama_ekspedisi) !== '-';
        $isDirectDelivery = in_array(trim((string)($row->nama_ekspedisi ?? '')), ['Diantarkan Langsung', 'Dijemput Langsung'], true);
        $hasPod  = ($curStatus === 5) && (!empty($row->nama_penerima_aktual ?? ($row->nama_penerima ?? null)) || !empty($row->bukti_penerima_aktual ?? ($row->bukti_penerima ?? null)));

        $sopChecks = [
            ['label' => 'PO CRO / Dokumen Sumber', 'done' => !empty($waktuPoCro) || !empty($waktuCetakSj)],
            ['label' => 'Cetak SJ / Dokumen Keluar', 'done' => !empty($waktuCetakSj)],
            ['label' => 'Order Ekspedisi', 'done' => !empty($waktuOrderEks) && $hasEkspedisi],
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
        } elseif ($curStatus === 0) {
            $sopLabel = 'Menunggu Proses (' . $sopDoneCount . '/' . $sopTotalCount . ')';
            $sopBg    = '#f1f5f9';
            $sopText  = '#475569';
            $sopBdr   = '#cbd5e1';
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

    /**
     * Sinkronisasi Otomatis Tracking Import dari:
     * - Tahap 1: purchase_order & purchase_order_detail (Pengajuan PO Supplier)
     * - Tahap 2: approval_forwarder & approval_forwarder_detail (Approval Forwarder Luar Negeri)
     * - Tahap 9: penerimaan_barang (Penerimaan Barang di Gudang Pusat / Jakarta / Jogja)
     */
    public function syncAutomaticImportFromExistingData()
    {
        if (!$this->db->table_exists('purchase_order') || !$this->db->table_exists('tracking_import')) {
            return;
        }

        $stage_defs = $this->getImportStageDefinitions();

        // Ambil semua Pengajuan PO Supplier yang sudah memiliki No PO atau sudah disetujui (status IN 4,7,8,9)
        $sqlPo = "SELECT 
                    po.id as id_po,
                    po.kode_po,
                    po.no_po,
                    po.tgl_pengajuan,
                    po.created_at,
                    po.ttd_4_at,
                    po.status,
                    po.ket_adm,
                    po.lampiran_adm,
                    po.id_pengaju,
                    (SELECT pod.supplier FROM purchase_order_detail pod WHERE pod.id_po = po.id AND pod.supplier IS NOT NULL AND pod.supplier != '' LIMIT 1) as supplier,
                    (SELECT GROUP_CONCAT(CONCAT(pod.nama_barang, ' (', COALESCE(NULLIF(pod.rencana_po,''), NULLIF(pod.kebutuhan_po,''), '1'), ')') SEPARATOR ', ') FROM purchase_order_detail pod WHERE pod.id_po = po.id) as items_summary
                  FROM purchase_order po
                  WHERE po.status IN (4, 7, 8, 9)
                    AND po.id >= 200
                  ORDER BY po.id ASC";
        $poList = $this->db->query($sqlPo)->result();
        if (empty($poList)) {
            return;
        }

        // Map existing tracking_import by id_po_supplier
        $existingRows = $this->db->get('tracking_import')->result();
        $byPoId = [];
        foreach ($existingRows as $er) {
            if (!empty($er->id_po_supplier)) {
                $byPoId[(int)$er->id_po_supplier] = $er;
            }
        }

        // Ambil data Approval Forwarder Luar Negeri untuk pencocokan otomatis Tahap 2
        $appForwarders = [];
        if ($this->db->table_exists('approval_forwarder')) {
            $sqlAf = "SELECT af.*,
                        (SELECT afd.id_forwarder FROM approval_forwarder_detail afd WHERE afd.id_app = af.id ORDER BY afd.dipilih DESC, afd.id ASC LIMIT 1) as id_forwarder_chose,
                        (SELECT f.nama FROM approval_forwarder_detail afd JOIN forwarder f ON afd.id_forwarder = f.id WHERE afd.id_app = af.id ORDER BY afd.dipilih DESC, afd.id ASC LIMIT 1) as nama_forwarder_chose
                      FROM approval_forwarder af
                      ORDER BY af.id DESC";
            $appForwarders = $this->db->query($sqlAf)->result();
        }

        // Ambil data Penerimaan Barang yang punya no_po untuk pencocokan otomatis Tahap 9
        $penerimaanList = [];
        if ($this->db->table_exists('penerimaan_barang')) {
            $sqlPb = "SELECT pb.id_penerimaan_barang, pb.no_terima, pb.no_po, pb.tgl_masuk, pb.data_created, g.nama_gudang
                      FROM penerimaan_barang pb
                      LEFT JOIN gudang g ON pb.id_gudang = g.id_gudang
                      WHERE pb.no_po IS NOT NULL AND pb.no_po != '' AND pb.no_po != '-'
                      ORDER BY pb.id_penerimaan_barang DESC
                      LIMIT 300";
            $penerimaanList = $this->db->query($sqlPb)->result();
        }

        // Filter hanya supplier / PO yang relevan (mengabaikan pembelian lokal via uang kas / shopee / koperasi lokal bila bukan forwarder)
        $localSkipKeywords = ['uang kas', 'shopee', 'tokopedia', 'nuklindolab', 'sumpah gajah mada', 'uno medical', 'alisha jaya', 'inti cakra'];

        foreach ($poList as $po) {
            $supplierName = trim((string)($po->supplier ?: ''));
            $noPoRaw      = trim(strip_tags((string)($po->no_po ?: $po->kode_po)));
            if ($supplierName === '' && empty($po->items_summary)) {
                continue;
            }

            // Cek apakah ada kecocokan di Approval Forwarder Luar Negeri
            $matchedAf = null;
            $noPoDigits = '';
            if (preg_match('/\b(0\d{2})\b/', $noPoRaw, $mNum)) {
                $noPoDigits = $mNum[1]; // misal 041, 044, 052, 058, 063
            }

            foreach ($appForwarders as $af) {
                $afTitle = strtolower((string)$af->nama . ' ' . (string)$af->keterangan);
                if ($noPoDigits !== '' && strpos($afTitle, strtolower($noPoDigits)) !== false) {
                    $matchedAf = $af;
                    break;
                }
                if ($supplierName !== '' && strlen($supplierName) >= 4) {
                    $firstWord = strtolower( strtok($supplierName, ' ') );
                    if (strlen($firstWord) >= 3 && !in_array($firstWord, ['pt', 'pt.', 'cv', 'cv.', 'lain-lain']) && strpos($afTitle, $firstWord) !== false) {
                        $matchedAf = $af;
                        break;
                    }
                }
            }

            // Skip supplier lokal murni kecuali punya dokumen Approval Forwarder Luar Negeri
            $supLower = strtolower($supplierName . ' ' . $noPoRaw);
            $isLocal = false;
            foreach ($localSkipKeywords as $kw) {
                if (strpos($supLower, $kw) !== false) {
                    $isLocal = true;
                    break;
                }
            }
            if (($isLocal || preg_match('/^(pt\.?\s|cv\.?\s|lain-lain|fujifilm indonesia)/i', $supplierName)) && !$matchedAf) {
                continue;
            }

            // Tentukan Waktu Tahap 1 (PO Manufacture)
            $waktu1 = !empty($po->ttd_4_at) && strpos($po->ttd_4_at, '0000-00-00') === false
                ? $po->ttd_4_at
                : (!empty($po->created_at) && strpos($po->created_at, '0000-00-00') === false
                    ? $po->created_at
                    : (!empty($po->tgl_pengajuan) ? date('Y-m-d H:i:s', strtotime($po->tgl_pengajuan)) : date('Y-m-d H:i:s')));

            // Cek apakah ada kecocokan di Penerimaan Barang Gudang (Tahap 9) yang tanggal masuknya >= tanggal PO
            $matchedPb = null;
            $poDateOnly = date('Y-m-d', strtotime($waktu1));
            foreach ($penerimaanList as $pb) {
                $pbPo = trim((string)$pb->no_po);
                if ($pbPo === '') continue;
                $pbDate = !empty($pb->tgl_masuk) ? $pb->tgl_masuk : (!empty($pb->data_created) ? date('Y-m-d', strtotime($pb->data_created)) : '');
                if ($pbDate !== '' && strtotime($pbDate) < (strtotime($poDateOnly) - 86400 * 7)) {
                    continue; // Abaikan penerimaan barang tahun/bulan lama yang kebetulan nomor urut PO-nya sama
                }
                if (strcasecmp($pbPo, $noPoRaw) === 0) {
                    $matchedPb = $pb;
                    break;
                }
                if ($noPoDigits !== '' && preg_match('/\b' . preg_quote($noPoDigits, '/') . '\b/', $pbPo)) {
                    $matchedPb = $pb;
                    break;
                }
            }

            // Tentukan Waktu Tahap 2, 3, 4 & Info Forwarder dari Approval Forwarder
            $waktu2 = null;
            $waktu3 = null;
            $waktu4 = null;
            $moda   = 'Sea Freight (Kapal)';
            $idFw   = null;
            $namaFw = null;
            $pol    = null;
            $pod    = null;

            if ($matchedAf) {
                $waktu2 = !empty($matchedAf->created_at) ? $matchedAf->created_at : (!empty($matchedAf->tanggal) ? $matchedAf->tanggal . ' 09:00:00' : $waktu1);
                if (strtotime($waktu2) < strtotime($waktu1)) {
                    $waktu1 = $waktu2;
                }
                $idFw   = $matchedAf->id_forwarder_chose ?: null;
                $namaFw = $matchedAf->nama_forwarder_chose ?: null;
                $pol    = $matchedAf->pol ?: null;
                $pod    = $matchedAf->pod ?: null;
                if (stripos((string)$matchedAf->sistem_pengiriman, 'udara') !== false || stripos((string)$matchedAf->sistem_pengiriman, 'air') !== false) {
                    $moda = 'Air Freight (Pesawat)';
                }
                // Jika Approval Forwarder sudah Disetujui Direktur (status IN 3, 4), otomatis aktifkan Tahap 3 & 4
                if (in_array((int)$matchedAf->status, [3, 4])) {
                    $waktu3 = date('Y-m-d H:i:s', strtotime($waktu2) + 86400);
                    $waktu4 = date('Y-m-d H:i:s', strtotime($waktu2) + 86400 * 2);
                }
            }

            // Tentukan Waktu Tahap 9 & Gudang Tujuan dari Penerimaan Barang / Status PO
            $waktu9 = null;
            $gudangTujuan = 'Gudang Pusat';
            $ketPenerimaan = null;

            if ($matchedPb) {
                $waktu9 = !empty($matchedPb->data_created) && strpos($matchedPb->data_created, '0000-00-00') === false
                    ? $matchedPb->data_created
                    : (!empty($matchedPb->tgl_masuk) ? $matchedPb->tgl_masuk . ' 15:00:00' : null);
                $nmGdg = strtolower((string)$matchedPb->nama_gudang);
                if (strpos($nmGdg, 'jakarta') !== false) {
                    $gudangTujuan = 'Gudang Jakarta';
                } elseif (strpos($nmGdg, 'yogya') !== false || strpos($nmGdg, 'jogja') !== false) {
                    $gudangTujuan = 'Gudang Jogja';
                } else {
                    $gudangTujuan = 'Gudang Pusat';
                }
                $ketPenerimaan = 'Diterima otomatis dari Penerimaan Barang No: ' . $matchedPb->no_terima . ' (' . $matchedPb->nama_gudang . ')';
            } elseif ((int)$po->status == 8) {
                // Status 8 di purchase_order = Barang Diterima Seluruh oleh Admin Warehouse
                $waktu9 = !empty($po->ttd_4_at) ? date('Y-m-d H:i:s', strtotime($po->ttd_4_at) + 86400) : $waktu1;
                $ketPenerimaan = 'Status PO Supplier: Barang Diterima Seluruh di Gudang';
            }

            // Fungsi bantu interpolasi waktu tahapan tengah (2..8) bila Tahap 9 sudah selesai agar timeline tidak bolong
            $buildInterpolatedMiddleTimes = function ($t1, $t9, $existingObj = null) use ($stage_defs) {
                $ts1 = strtotime($t1);
                $ts9 = max($ts1 + 3600, strtotime($t9));
                $span = $ts9 - $ts1;
                $ratios = [
                    2 => 0.10, // Order Forwarder
                    3 => 0.25, // Loading Kapal
                    4 => 0.35, // Perjalanan Kapal
                    5 => 0.65, // Sampai Seaport/Airport
                    6 => 0.75, // Pembayaran Pajak
                    7 => 0.85, // Keluar Gudang Port
                    8 => 0.92, // Hand-over Domestik
                ];
                $res = [];
                foreach ($ratios as $st => $ratio) {
                    $fld = $stage_defs[$st]['field'];
                    $curVal = $existingObj ? ($existingObj->$fld ?? null) : null;
                    if (empty($curVal) || strpos((string)$curVal, '0000-00-00') !== false) {
                        $res[$fld] = date('Y-m-d H:i:s', (int)round($ts1 + ($span * $ratio)));
                    }
                }
                return $res;
            };

            if (!isset($byPoId[(int)$po->id_po])) {
                // INSERT OTOMATIS BARU
                $curStage = 1;
                if ($waktu2) $curStage = 2;
                if ($waktu4) $curStage = 4;
                if ($waktu9) $curStage = 9;

                $kodeImport = $this->generateKodeImport();
                $ins = [
                    'kode_import'              => $kodeImport,
                    'id_po_supplier'           => (int)$po->id_po,
                    'no_po_manufacture'        => $noPoRaw ?: $po->kode_po,
                    'nama_manufacture'         => $supplierName ?: 'Manufacture Import',
                    'negara_asal'              => $pol,
                    'daftar_unit'              => $po->items_summary ?: '-',
                    'moda_transportasi'        => $moda,
                    'id_forwarder'             => $idFw,
                    'nama_forwarder'           => $namaFw,
                    'pelabuhan_asal'           => $pol,
                    'pelabuhan_tujuan'         => $pod,
                    'nama_gudang_tujuan'       => $gudangTujuan,
                    'bukti_terima_gudang'      => !empty($po->lampiran_adm) ? strip_tags($po->lampiran_adm) : null,
                    'waktu_1_po_manufacture'   => $waktu1,
                    'waktu_2_order_forwarder'  => $waktu2,
                    'waktu_3_loading_kapal'    => $waktu3,
                    'waktu_4_perjalanan_kapal' => $waktu4,
                    'waktu_9_sampai_gudang'    => $waktu9,
                    'current_stage'            => $curStage,
                    'keterangan'               => $ketPenerimaan ?: (!empty($po->ket_adm) ? strip_tags($po->ket_adm) : ('Tersinkronisasi otomatis dari PO Supplier ' . $po->kode_po)),
                    'created_by'               => $po->id_pengaju ?: 1,
                    'created_at'               => $waktu1,
                    'updated_at'               => date('Y-m-d H:i:s'),
                    'stat'                     => 1
                ];

                if ($waktu9) {
                    $mid = $buildInterpolatedMiddleTimes($waktu1, $waktu9, (object)$ins);
                    foreach ($mid as $k => $v) {
                        $ins[$k] = $v;
                    }
                }

                $this->db->insert('tracking_import', $ins);
                $newId = $this->db->insert_id();

                if ($newId) {
                    $this->db->insert('tracking_import_log', [
                        'id_import'       => $newId,
                        'stage'           => 1,
                        'stage_label'     => $stage_defs[1]['label'],
                        'waktu_milestone' => $waktu1,
                        'catatan'         => 'Otomatis ditarik dari Pengajuan PO Supplier: ' . $po->kode_po . ' (No PO: ' . ($noPoRaw ?: '-') . ')',
                        'id_pengguna'     => $po->id_pengaju ?: 1,
                        'created_at'      => $waktu1
                    ]);

                    if ($waktu2 && $matchedAf) {
                        $this->db->insert('tracking_import_log', [
                            'id_import'       => $newId,
                            'stage'           => 2,
                            'stage_label'     => $stage_defs[2]['label'],
                            'waktu_milestone' => $waktu2,
                            'catatan'         => 'Otomatis terhubung dengan Approval Forwarder: ' . $matchedAf->kode . ' (' . ($namaFw ?: $matchedAf->nama) . ')',
                            'id_pengguna'     => $matchedAf->id_pengguna ?: 1,
                            'created_at'      => $waktu2
                        ]);
                    }

                    if ($waktu9) {
                        $this->db->insert('tracking_import_log', [
                            'id_import'       => $newId,
                            'stage'           => 9,
                            'stage_label'     => $stage_defs[9]['label'],
                            'waktu_milestone' => $waktu9,
                            'catatan'         => $ketPenerimaan,
                            'id_pengguna'     => $po->id_pengaju ?: 1,
                            'created_at'      => $waktu9
                        ]);
                    }
                }
            } else {
                // UPDATE OTOMATIS JIKA ADA DATA BARU DARI APPROVAL FORWARDER ATAU PENERIMAAN BARANG
                $ex = $byPoId[(int)$po->id_po];
                $upd = [];

                if (empty($ex->waktu_2_order_forwarder) && $waktu2) {
                    $upd['waktu_2_order_forwarder'] = $waktu2;
                    if (empty($ex->nama_forwarder) && $namaFw) $upd['nama_forwarder'] = $namaFw;
                    if (empty($ex->pelabuhan_asal) && $pol) $upd['pelabuhan_asal'] = $pol;
                    if (empty($ex->pelabuhan_tujuan) && $pod) $upd['pelabuhan_tujuan'] = $pod;
                    if ((int)$ex->current_stage < 2) $upd['current_stage'] = 2;
                }

                if (empty($ex->waktu_3_loading_kapal) && $waktu3) {
                    $upd['waktu_3_loading_kapal'] = $waktu3;
                    if ((int)$ex->current_stage < 3) $upd['current_stage'] = 3;
                }
                if (empty($ex->waktu_4_perjalanan_kapal) && $waktu4) {
                    $upd['waktu_4_perjalanan_kapal'] = $waktu4;
                    if ((int)$ex->current_stage < 4) $upd['current_stage'] = 4;
                }

                $finalWaktu9 = !empty($ex->waktu_9_sampai_gudang) ? $ex->waktu_9_sampai_gudang : $waktu9;
                if ($finalWaktu9) {
                    if (empty($ex->waktu_9_sampai_gudang)) {
                        $upd['waktu_9_sampai_gudang'] = $finalWaktu9;
                        $upd['nama_gudang_tujuan']    = $gudangTujuan;
                    }
                    if ((int)$ex->current_stage < 9) {
                        $upd['current_stage'] = 9;
                    }
                    // Lengkapi otomatis tahapan 2..8 yang masih kosong jika barang sudah sampai gudang (Tahap 9)
                    $mid = $buildInterpolatedMiddleTimes($ex->waktu_1_po_manufacture ?: $waktu1, $finalWaktu9, $ex);
                    foreach ($mid as $k => $v) {
                        $upd[$k] = $v;
                    }
                }

                if (!empty($upd)) {
                    $upd['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->where('id_import', $ex->id_import)->update('tracking_import', $upd);
                }
            }
        }
    }

    public function getAllTrackingImport($filter_gudang = null, $filter_stage = null)
    {
        $this->syncAutomaticImportFromExistingData();

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

    // =========================================================================
    // AUTO-SYNC & AUTO-PROGRESS ENGINE (SJBK, TTB, STTB, PO GUDANG, PENERIMAAN)
    // =========================================================================

    public function getStageRank($id_status)
    {
        $map = [
            0 => 0, // Menunggu Order Ekspedisi
            1 => 1, // Proses Kirim / Order Ekspedisi
            7 => 2, // Packaging Barang
            2 => 3, // Manifest Berangkat / Loading Hand-over
            3 => 4, // Proses Sortir
            6 => 4, // Menunggu Konfirmasi
            4 => 5, // Pengantaran Kurir
            5 => 6, // Diterima
        ];
        return $map[(int)$id_status] ?? 0;
    }

    public function getNextStageInfo($current_status)
    {
        $cur = ($current_status === null || $current_status === '') ? 0 : (int)$current_status;
        $flow = [
            0 => ['next_status' => 1, 'label' => 'Proses Kirim / Order Ekspedisi', 'btn_label' => 'Order Ekspedisi', 'icon' => 'fa-paper-plane'],
            1 => ['next_status' => 7, 'label' => 'Packaging Barang', 'btn_label' => 'Packaging', 'icon' => 'fa-box-open'],
            7 => ['next_status' => 2, 'label' => 'Manifest Berangkat / Loading Hand-over', 'btn_label' => 'Manifest / Kirim', 'icon' => 'fa-truck-loading'],
            2 => ['next_status' => 3, 'label' => 'Proses Sortir', 'btn_label' => 'Proses Sortir', 'icon' => 'fa-random'],
            3 => ['next_status' => 4, 'label' => 'Pengantaran Kurir', 'btn_label' => 'Pengantaran Kurir', 'icon' => 'fa-shipping-fast'],
            6 => ['next_status' => 4, 'label' => 'Pengantaran Kurir', 'btn_label' => 'Pengantaran Kurir', 'icon' => 'fa-shipping-fast'],
            4 => ['next_status' => 5, 'label' => 'Diterima', 'btn_label' => 'Tandai Diterima', 'icon' => 'fa-check-circle'],
        ];
        return $flow[$cur] ?? null;
    }

    public function getLatestStatusId($id_tracking)
    {
        $row = $this->db->select('id_status')
            ->from('tracking_status')
            ->where('id_tracking', (int)$id_tracking)
            ->order_by('created_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row();
        return $row ? (int)$row->id_status : 0;
    }

    public function advanceTrackingStage($id_tracking, $target_status, $opts = [])
    {
        $id_tracking   = (int)$id_tracking;
        $target_status = (int)$target_status;
        if ($id_tracking <= 0 || $target_status < 0) {
            return false;
        }

        $tr = $this->db->get_where('tracking_barang', ['id_tracking' => $id_tracking])->row();
        if (!$tr) {
            return false;
        }

        $hasStatus  = $this->db->where('id_tracking', $id_tracking)->count_all_results('tracking_status') > 0;
        $curStatus  = $hasStatus ? $this->getLatestStatusId($id_tracking) : -1;
        $curRank    = $hasStatus ? $this->getStageRank($curStatus) : -1;
        $targetRank = $this->getStageRank($target_status);
        $force      = !empty($opts['force']);

        // Update metadata / milestone timestamps pada tracking_barang
        $now = date('Y-m-d H:i:s');
        $tbUpdate = [];

        if (!empty($opts['waktu_po_cro']) && empty($tr->waktu_po_cro)) {
            $tbUpdate['waktu_po_cro'] = $opts['waktu_po_cro'];
        }
        if (!empty($opts['waktu_cetak_sj']) && empty($tr->waktu_cetak_sj)) {
            $tbUpdate['waktu_cetak_sj'] = $opts['waktu_cetak_sj'];
        }
        if ($targetRank >= 1 && empty($tr->waktu_order_ekspedisi)) {
            $tbUpdate['waktu_order_ekspedisi'] = $opts['waktu_order_ekspedisi'] ?? $now;
        } elseif (!empty($opts['waktu_order_ekspedisi']) && !empty($opts['overwrite_order_time'])) {
            $tbUpdate['waktu_order_ekspedisi'] = $opts['waktu_order_ekspedisi'];
        }
        if ($targetRank >= 2 && empty($tr->waktu_packaging)) {
            $tbUpdate['waktu_packaging'] = $opts['waktu_packaging'] ?? $now;
        }
        if ($targetRank >= 3 && empty($tr->waktu_loading_handover)) {
            $tbUpdate['waktu_loading_handover'] = $opts['waktu_loading_handover'] ?? $now;
        }
        if (!empty($opts['no_resi']) && trim((string)$opts['no_resi']) !== '-') {
            $tbUpdate['no_resi'] = trim((string)$opts['no_resi']);
        }
        if (!empty($opts['link_resi']) && trim((string)$opts['link_resi']) !== '-') {
            $tbUpdate['link_resi'] = trim((string)$opts['link_resi']);
        }
        if (!empty($opts['tgl_sampai']) && strpos((string)$opts['tgl_sampai'], '0000') === false) {
            if (!empty($opts['overwrite_tgl_sampai']) || empty($tr->tgl_sampai) || strpos((string)$tr->tgl_sampai, '0000') !== false) {
                $tbUpdate['tgl_sampai'] = $opts['tgl_sampai'];
            }
        }

        if (!empty($tbUpdate)) {
            $this->db->where('id_tracking', $id_tracking)->update('tracking_barang', $tbUpdate);
        }

        // Jangan turunkan status jika sudah berada di tahap yang sama atau lebih tinggi
        if (!$force && $hasStatus && ($targetRank < $curRank || $target_status === $curStatus)) {
            return false;
        }

        $userId = !empty($opts['id_pengguna'])
            ? (int)$opts['id_pengguna']
            : ((function_exists('sessPenggunaId') && sessPenggunaId()) ? (int)sessPenggunaId() : 1);

        $statusData = [
            'id_tracking'           => $id_tracking,
            'id_status'             => (string)$target_status,
            'id_pengguna'           => $userId,
            'keterangan_konfirmasi' => $opts['keterangan'] ?? 'Otomatis tersinkronisasi oleh sistem',
            'created_at'            => $opts['created_at'] ?? $now,
        ];

        if ($target_status === 5) {
            $statusData['nama_penerima']  = !empty($opts['nama_penerima']) ? $opts['nama_penerima'] : ($tr->pic_penerima ?: 'Penerima');
            $statusData['tgl_penerima']   = !empty($opts['tgl_penerima']) ? $opts['tgl_penerima'] : date('Y-m-d');
            $statusData['bukti_penerima'] = !empty($opts['bukti_penerima']) ? $opts['bukti_penerima'] : ($tr->link_resi ?: '-');
        }

        $this->db->insert('tracking_status', $statusData);
        return true;
    }

    public function syncPengeluaranBarangToTracking($id_pengeluaran_barang, $triggerOpts = [])
    {
        $id_pb = (int)$id_pengeluaran_barang;
        if ($id_pb <= 0 || !$this->db->table_exists('pengeluaran_barang')) {
            return false;
        }

        $pb = $this->db->select('
                pb.*,
                c.nama_customer,
                c.contact as customer_contact,
                c.alamat_customer,
                e.nama_ekspedisi,
                g.nama_gudang
            ')
            ->from('pengeluaran_barang pb')
            ->join('customer c', 'pb.id_customer = c.id_customer', 'left')
            ->join('ekspedisi e', 'pb.id_ekspedisi = e.id_ekspedisi', 'left')
            ->join('gudang g', 'pb.id_gudang = g.id_gudang', 'left')
            ->where('pb.id_pengeluaran_barang', $id_pb)
            ->get()
            ->row();

        if (!$pb || (int)$pb->status !== 1) {
            return false;
        }

        // Ambil informasi PO Masuk jika ada no_po
        $pm = null;
        if (!empty($pb->no_po) && $this->db->table_exists('po_masuk')) {
            $pm = $this->db->select('*')
                ->from('po_masuk')
                ->where('no_po', trim($pb->no_po))
                ->where('deleted', 0)
                ->order_by('id_po', 'DESC')
                ->limit(1)
                ->get()
                ->row();
        }

        // Ringkasan barang
        $items = $this->db->select('b.nama_barang')
            ->from('detail_barang_keluar dbk')
            ->join('barang b', 'b.id_barang = dbk.id_barang', 'left')
            ->where('dbk.id_pengeluaran_barang', $id_pb)
            ->where('dbk.status', 1)
            ->get()
            ->result();
        $barangNames = [];
        foreach ($items as $it) {
            if (!empty($it->nama_barang)) {
                $barangNames[] = $it->nama_barang;
            }
        }
        $namaBarangStr = !empty($barangNames) ? implode(', ', array_unique($barangNames)) : '-';

        $waktuCetakSj = (!empty($pb->data_created) && strpos((string)$pb->data_created, '0000') === false)
            ? $pb->data_created
            : ((!empty($pb->tgl_keluar) && strpos((string)$pb->tgl_keluar, '0000') === false) ? ($pb->tgl_keluar . ' 08:00:00') : date('Y-m-d H:i:s'));

        $waktuPoCro = null;
        if ($pm) {
            $waktuPoCro = $pm->cro_date ?: ($pm->gm_date ?: $pm->created_at);
        }

        $noResi   = (!empty($pb->resi) && trim((string)$pb->resi) !== '') ? trim($pb->resi) : '-';
        $linkResi = ($pm && !empty($pm->doc_gudang_resi)) ? trim($pm->doc_gudang_resi) : '-';
        if (!empty($pb->file_pendukung) && $linkResi === '-') {
            $linkResi = base_url('uploads/pengeluaran_barang/' . $pb->file_pendukung);
        }

        $picPenerima = ($pm && !empty($pm->pic_penerima_nama))
            ? $pm->pic_penerima_nama
            : (!empty($pb->customer_contact) ? $pb->customer_contact : ($pb->nama_customer ?: '-'));
        $alamatPenerima = !empty($pb->alamat)
            ? $pb->alamat
            : (($pm && !empty($pm->alamat_pengiriman)) ? $pm->alamat_pengiriman : ($pb->alamat_customer ?: '-'));

        $tglPengiriman = (!empty($pb->tgl_keluar) && strpos((string)$pb->tgl_keluar, '0000') === false)
            ? $pb->tgl_keluar
            : date('Y-m-d');

        // Tentukan status target otomatis berdasarkan kelengkapan data
        $targetStatus = 0; // Default: Menunggu Order Ekspedisi (belum ada aksi order ekspedisi)
        $ketAuto      = 'Otomatis masuk ke Tracking saat Surat Jalan dibuat (Menunggu Order Ekspedisi)';

        if (!empty($pb->status_email_tiki) && (int)$pb->status_email_tiki === 1) {
            $targetStatus = 1; // Proses Kirim / Order Ekspedisi
            $ketAuto      = 'Otomatis Order Ekspedisi (Email TIKI sudah dikonfirmasi)';
        }
        if ($pm && !empty($pm->doc_gudang_packing_list)) {
            $targetStatus = 7; // Packaging Barang
            $ketAuto      = 'Otomatis update ke Packaging Barang dari dokumen Packing List PO';
        }
        if (($noResi !== '-' && $noResi !== '') || ($pm && !empty($pm->doc_gudang_resi))) {
            $targetStatus = 2; // Manifest Berangkat / Loading Hand-over
            $ketAuto      = 'Otomatis update ke Manifest Berangkat (No. Resi telah terbit)';
        }
        if (stripos((string)($pb->status_pengiriman ?? ''), 'diterima') !== false) {
            $targetStatus = 5; // Diterima
            $ketAuto      = 'Otomatis selesai (Diterima) dari status Pengeluaran Barang';
        }

        if (isset($triggerOpts['target_status'])) {
            $reqStatus = (int)$triggerOpts['target_status'];
            if ($this->getStageRank($reqStatus) >= $this->getStageRank($targetStatus)) {
                $targetStatus = $reqStatus;
                if (!empty($triggerOpts['keterangan'])) {
                    $ketAuto = $triggerOpts['keterangan'];
                }
            }
        }

        $existing = $this->db->get_where('tracking_barang', [
            'id_pengeluaran_barang' => $id_pb,
            'stat'                  => 1,
        ])->row();

        // Hanya sync progres jika tracking sudah dibuat manual oleh gudang
        if (!$existing) {
            return false;
        }

        $idTracking = (int)$existing->id_tracking;
        $upd = [
            'no_sj'           => $pb->no_pengiriman,
            'id_gudang'       => $pb->id_gudang,
            'id_customer'     => $pb->id_customer,
            'id_ekspedisi'    => $pb->id_ekspedisi,
            'nama_ekspedisi'  => $pb->nama_ekspedisi ?: $existing->nama_ekspedisi,
            'alamat_penerima' => $alamatPenerima ?: $existing->alamat_penerima,
            'tgl_pengiriman'  => $tglPengiriman ?: $existing->tgl_pengiriman,
        ];
        if ($noResi !== '-') {
            $upd['no_resi'] = $noResi;
        }
        if ($linkResi !== '-') {
            $upd['link_resi'] = $linkResi;
        }
        if (empty($existing->waktu_po_cro) && $waktuPoCro) {
            $upd['waktu_po_cro'] = $waktuPoCro;
        }
        if (empty($existing->waktu_cetak_sj) && $waktuCetakSj) {
            $upd['waktu_cetak_sj'] = $waktuCetakSj;
        }
        $this->db->where('id_tracking', $idTracking)->update('tracking_barang', $upd);

        $advOpts = [
            'keterangan'     => $ketAuto,
            'no_resi'        => $noResi,
            'link_resi'      => $linkResi,
            'waktu_po_cro'   => $waktuPoCro,
            'waktu_cetak_sj' => $waktuCetakSj,
            'nama_penerima'  => $picPenerima,
            'tgl_penerima'   => date('Y-m-d'),
            'bukti_penerima' => ($pm && !empty($pm->doc_gudang_sstb)) ? $pm->doc_gudang_sstb : $linkResi,
        ];
        if ($pm && !empty($pm->doc_gudang_updated_at) && strpos((string)$pm->doc_gudang_updated_at, '0000') === false) {
            if (!empty($pm->doc_gudang_packing_list)) {
                $advOpts['waktu_packaging'] = $pm->doc_gudang_updated_at;
            }
        }
        if (!empty($triggerOpts['overwrite_order_time'])) {
            $advOpts['waktu_order_ekspedisi'] = date('Y-m-d H:i:s');
            $advOpts['overwrite_order_time']  = true;
        }

        $this->advanceTrackingStage($idTracking, $targetStatus, $advOpts);
        return $idTracking;
    }

    public function syncPengirimanStokToTracking($id_pengiriman_stok, $triggerOpts = [])
    {
        $id_ps = (int)$id_pengiriman_stok;
        if ($id_ps <= 0 || !$this->db->table_exists('pengiriman_stok')) {
            return false;
        }

        $ps = $this->db->select('
                ps.*,
                g1.nama_gudang as nama_gudang_asal,
                g2.nama_gudang as nama_gudang_tujuan,
                g2.alamat_gudang as alamat_gudang_tujuan,
                e.nama_ekspedisi,
                rsps.status_pengiriman
            ')
            ->from('pengiriman_stok ps')
            ->join('gudang g1', 'ps.id_gudang_asal = g1.id_gudang', 'left')
            ->join('gudang g2', 'ps.id_gudang_tujuan = g2.id_gudang', 'left')
            ->join('ekspedisi e', 'ps.id_ekspedisi = e.id_ekspedisi', 'left')
            ->join('riwayat_status_pengiriman_stok rsps', 'rsps.id_latest_riwayatstatus_pengiriman = ps.id_latest_riwayatstatus_pengiriman', 'left')
            ->where('ps.id_pengiriman_stok', $id_ps)
            ->get()
            ->row();

        if (!$ps || (int)$ps->status !== 1) {
            return false;
        }

        // Cek apakah sudah ada penerimaan_stok di gudang tujuan
        $penerimaan = null;
        if ($this->db->table_exists('penerimaan_stok')) {
            $penerimaan = $this->db->select('pst.*, u.nama as nama_penerima_user')
                ->from('penerimaan_stok pst')
                ->join('pengguna u', 'pst.pengguna_id = u.pengguna_id', 'left')
                ->where('pst.id_pengiriman_stok', $id_ps)
                ->order_by('pst.id_penerimaan_stok', 'DESC')
                ->limit(1)
                ->get()
                ->row();
        }

        $waktuCetak = (!empty($ps->data_created) && strpos((string)$ps->data_created, '0000') === false)
            ? $ps->data_created
            : ((!empty($ps->tgl_pengiriman) && strpos((string)$ps->tgl_pengiriman, '0000') === false) ? ($ps->tgl_pengiriman . ' 08:00:00') : date('Y-m-d H:i:s'));

        $tglPengiriman    = (!empty($ps->tgl_pengiriman) && strpos((string)$ps->tgl_pengiriman, '0000') === false) ? $ps->tgl_pengiriman : date('Y-m-d');
        $noResi           = (!empty($ps->no_resi) && trim((string)$ps->no_resi) !== '') ? trim($ps->no_resi) : '-';
        $linkResi         = !empty($ps->file_pendukung) ? base_url('uploads/pengiriman_stok/' . $ps->file_pendukung) : '-';

        $targetStatus = 0;
        $ketAuto      = 'Otomatis masuk ke Tracking saat Pengiriman Stok (TTB) dibuat (Menunggu Order Ekspedisi)';
        if ($noResi !== '-' && $noResi !== '') {
            $targetStatus = 2;
            $ketAuto      = 'Otomatis update ke Manifest Berangkat (No. Resi TTB terisi)';
        }
        if (
            ($ps->status_pengiriman ?? '') === 'Diterima Seluruhnya'
            || !empty($triggerOpts['force_diterima'])
        ) {
            $targetStatus = 5;
            $ketAuto      = 'Otomatis Diterima saat Penerimaan Stok dikonfirmasi oleh Gudang Tujuan';
        }
        if (isset($triggerOpts['target_status'])) {
            $reqStatus = (int)$triggerOpts['target_status'];
            if ($this->getStageRank($reqStatus) >= $this->getStageRank($targetStatus)) {
                $targetStatus = $reqStatus;
                if (!empty($triggerOpts['keterangan'])) {
                    $ketAuto = $triggerOpts['keterangan'];
                }
            }
        }

        $existing = $this->db->get_where('tracking_barang', [
            'id_pengiriman_stok' => $id_ps,
            'stat'               => 1,
        ])->row();

        // Hanya sync progres jika tracking sudah dibuat manual oleh gudang
        if (!$existing) {
            return false;
        }

        $idTracking = (int)$existing->id_tracking;
        $upd = [
            'no_sj'          => $ps->no_pemindahan,
            'id_gudang'      => $ps->id_gudang_asal,
            'id_ekspedisi'   => $ps->id_ekspedisi,
            'nama_ekspedisi' => $ps->nama_ekspedisi ?: $existing->nama_ekspedisi,
            'tgl_pengiriman' => $tglPengiriman ?: $existing->tgl_pengiriman,
        ];
        if ($noResi !== '-') {
            $upd['no_resi'] = $noResi;
        }
        if ($linkResi !== '-') {
            $upd['link_resi'] = $linkResi;
        }
        $this->db->where('id_tracking', $idTracking)->update('tracking_barang', $upd);

        $namaPenerimaAktual = ($penerimaan && !empty($penerimaan->nama_penerima_user))
            ? ($penerimaan->nama_penerima_user . ' (' . ($ps->nama_gudang_tujuan ?: 'Gudang Tujuan') . ')')
            : ($ps->nama_gudang_tujuan ?: 'Gudang Tujuan');
        $tglPenerimaAktual = ($penerimaan && !empty($penerimaan->tgl_penerimaan_stok))
            ? $penerimaan->tgl_penerimaan_stok
            : date('Y-m-d');
        $buktiTerima = ($penerimaan && !empty($penerimaan->no_penerimaan_stok))
            ? ('No. Terima Stok: ' . $penerimaan->no_penerimaan_stok)
            : $linkResi;

        $this->advanceTrackingStage($idTracking, $targetStatus, [
            'keterangan'     => $ketAuto,
            'no_resi'        => $noResi,
            'link_resi'      => $linkResi,
            'waktu_cetak_sj' => $waktuCetak,
            'nama_penerima'  => $namaPenerimaAktual,
            'tgl_penerima'   => $tglPenerimaAktual,
            'bukti_penerima' => $buktiTerima,
        ]);

        return $idTracking;
    }

    public function syncSerahTerimaToTracking($id_stb, $triggerOpts = [])
    {
        $id_stb = (int)$id_stb;
        if ($id_stb <= 0 || !$this->db->table_exists('surat_stb')) {
            return false;
        }

        $stb = $this->db->select('
                stb.*,
                c1.nama_customer as nama_pihak1,
                c2.nama_customer as nama_pihak2,
                c2.contact as contact_pihak2,
                c2.alamat_customer as alamat_pihak2
            ')
            ->from('surat_stb stb')
            ->join('customer c1', 'stb.id_pihak1 = c1.id_customer', 'left')
            ->join('customer c2', 'stb.id_customer = c2.id_customer', 'left')
            ->where('stb.id_stb', $id_stb)
            ->get()
            ->row();

        if (!$stb || (isset($stb->status) && (int)$stb->status === 5)) {
            return false;
        }

        $tglPengajuan = (!empty($stb->tgl_pengajuan) && strpos((string)$stb->tgl_pengajuan, '0000') === false)
            ? $stb->tgl_pengajuan
            : date('Y-m-d');
        $waktuCetak = (!empty($stb->created_at) && strpos((string)$stb->created_at, '0000') === false)
            ? $stb->created_at
            : ($tglPengajuan . ' 08:00:00');

        $existing = $this->db->get_where('tracking_barang', [
            'id_serah_terima_barang' => $id_stb,
            'stat'                   => 1,
        ])->row();

        $targetStatus = isset($triggerOpts['target_status']) ? (int)$triggerOpts['target_status'] : 0;
        $ketAuto      = !empty($triggerOpts['keterangan']) ? $triggerOpts['keterangan'] : 'Otomatis masuk ke Tracking saat STTB dibuat (Menunggu Order Ekspedisi)';

        // Hanya sync progres jika tracking sudah dibuat manual oleh gudang
        if (!$existing) {
            return false;
        }

        $idTracking = (int)$existing->id_tracking;
        $this->db->where('id_tracking', $idTracking)->update('tracking_barang', [
            'no_sj'           => $stb->kode_stb,
            'id_customer'     => $stb->id_customer,
            'alamat_penerima' => !empty($stb->alamat_pihak2) ? $stb->alamat_pihak2 : $existing->alamat_penerima,
        ]);

        $this->advanceTrackingStage($idTracking, $targetStatus, [
            'keterangan'     => $ketAuto,
            'waktu_cetak_sj' => $waktuCetak,
            'nama_penerima'  => $stb->nama_pihak2 ?: 'Pihak Kedua',
        ]);

        return $idTracking;
    }

    public function syncFromPoMasukGudang($id_po)
    {
        $id_po = (int)$id_po;
        if ($id_po <= 0 || !$this->db->table_exists('po_masuk') || !$this->db->table_exists('pengeluaran_barang')) {
            return 0;
        }

        $po = $this->db->get_where('po_masuk', ['id_po' => $id_po, 'deleted' => 0])->row();
        if (!$po) {
            return 0;
        }

        $this->db->select('id_pengeluaran_barang');
        $this->db->from('pengeluaran_barang');
        $this->db->where('status', 1);
        $this->db->group_start();
        $hasCond = false;
        if (!empty($po->no_po)) {
            $this->db->where('no_po', trim($po->no_po));
            $hasCond = true;
        }
        if (!empty($po->doc_gudang_sj)) {
            $this->db->or_where('no_pengiriman', trim($po->doc_gudang_sj));
            $hasCond = true;
        }
        $this->db->group_end();

        if (!$hasCond) {
            return 0;
        }

        $rows = $this->db->get()->result();
        $count = 0;
        foreach ($rows as $r) {
            if ($this->syncPengeluaranBarangToTracking($r->id_pengeluaran_barang)) {
                $count++;
            }
        }
        return $count;
    }

    public function autoBackfillUntrackedDocuments()
    {
        static $alreadyRun = false;
        if ($alreadyRun) {
            return;
        }
        $alreadyRun = true;

        if (!$this->db->table_exists('tracking_barang')) {
            return;
        }

        $autoKetCond = "(
            ts_chk.keterangan_konfirmasi LIKE 'Otomatis masuk ke Tracking%'
            OR ts_chk.keterangan_konfirmasi LIKE 'Otomatis update ke %'
            OR ts_chk.keterangan_konfirmasi LIKE 'Otomatis selesai (Diterima)%'
            OR ts_chk.keterangan_konfirmasi LIKE 'Otomatis Diterima saat Penerimaan Stok%'
        )";

        // 0A. Cleanup aman: hapus baris arsip > 30 hari yang barusan tertarik backfill tanpa aksi manual/tagihan
        $hasTagihanTable = $this->db->table_exists('tagihan_ekspedisi');
        $tagihanNotExistsSql = $hasTagihanTable
            ? "AND NOT EXISTS (SELECT 1 FROM tagihan_ekspedisi te WHERE te.id_tracking = tb.id_tracking)"
            : "";

        $staleRows = $this->db->query("
            SELECT tb.id_tracking
            FROM tracking_barang tb
            WHERE tb.stat = 1
              AND tb.tgl_pengiriman IS NOT NULL
              AND tb.tgl_pengiriman != '0000-00-00'
              AND tb.tgl_pengiriman < DATE_SUB(CURDATE(), INTERVAL 30 DAY)
              {$tagihanNotExistsSql}
              AND (SELECT COUNT(*) FROM tracking_status ts_all WHERE ts_all.id_tracking = tb.id_tracking) = 1
              AND EXISTS (
                  SELECT 1 FROM tracking_status ts_chk
                  WHERE ts_chk.id_tracking = tb.id_tracking
                    AND {$autoKetCond}
              )
        ")->result();

        if (!empty($staleRows)) {
            $staleIds = array_map(function ($r) {
                return (int)$r->id_tracking;
            }, $staleRows);
            foreach (array_chunk($staleIds, 200) as $chunk) {
                $this->db->where_in('id_tracking', $chunk)->delete('tracking_status');
                $this->db->where_in('id_tracking', $chunk)->delete('tracking_barang');
            }
        }

        // 0B. Perbaiki baris auto-sync 30 hari terakhir yang masih membawa waktu palsu = waktu_cetak_sj atau ETA palsu +3 hari
        $recentAutoRows = $this->db->query("
            SELECT
                tb.id_tracking,
                tb.tgl_pengiriman,
                tb.tgl_sampai,
                tb.no_resi,
                tb.waktu_cetak_sj,
                tb.waktu_order_ekspedisi,
                tb.waktu_packaging,
                tb.waktu_loading_handover,
                ts_chk.id as status_row_id,
                ts_chk.id_status as cur_id_status,
                ts_chk.keterangan_konfirmasi
            FROM tracking_barang tb
            INNER JOIN tracking_status ts_chk ON ts_chk.id_tracking = tb.id_tracking
            WHERE tb.stat = 1
              AND (tb.tgl_pengiriman IS NULL OR tb.tgl_pengiriman = '0000-00-00' OR tb.tgl_pengiriman >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))
              AND (SELECT COUNT(*) FROM tracking_status ts_all WHERE ts_all.id_tracking = tb.id_tracking) = 1
              AND {$autoKetCond}
        ")->result();

        foreach ($recentAutoRows as $r) {
            $upd = [];
            if (!empty($r->tgl_pengiriman) && strpos((string)$r->tgl_pengiriman, '0000') === false && !empty($r->tgl_sampai)) {
                $fakeEta = date('Y-m-d', strtotime($r->tgl_pengiriman . ' +3 days'));
                if (substr((string)$r->tgl_sampai, 0, 10) === $fakeEta) {
                    $upd['tgl_sampai'] = null;
                }
            }

            $isInitialAuto = ((int)$r->cur_id_status === 1)
                && strpos((string)$r->keterangan_konfirmasi, 'Otomatis masuk ke Tracking') === 0
                && (empty($r->no_resi) || trim((string)$r->no_resi) === '-');

            if ($isInitialAuto) {
                if (!empty($r->waktu_order_ekspedisi) && !empty($r->waktu_cetak_sj) && $r->waktu_order_ekspedisi === $r->waktu_cetak_sj) {
                    $upd['waktu_order_ekspedisi'] = null;
                }
                $this->db->where('id', (int)$r->status_row_id)->update('tracking_status', [
                    'id_status' => '0',
                ]);
            } elseif ((int)$r->cur_id_status >= 1) {
                if (!empty($r->waktu_order_ekspedisi) && !empty($r->waktu_cetak_sj) && $r->waktu_order_ekspedisi === $r->waktu_cetak_sj) {
                    $upd['waktu_order_ekspedisi'] = null;
                }
                if (!empty($r->waktu_packaging) && !empty($r->waktu_cetak_sj) && $r->waktu_packaging === $r->waktu_cetak_sj) {
                    $upd['waktu_packaging'] = null;
                }
                if (!empty($r->waktu_loading_handover) && !empty($r->waktu_cetak_sj) && $r->waktu_loading_handover === $r->waktu_cetak_sj) {
                    $upd['waktu_loading_handover'] = null;
                }
            }

            if (!empty($upd)) {
                $this->db->where('id_tracking', (int)$r->id_tracking)->update('tracking_barang', $upd);
            }
        }

        // Hanya auto-sync progres untuk tracking yang SUDAH dibuat manual oleh gudang (tidak auto-insert baris baru)
        if ($this->db->table_exists('pengiriman_stok') && $this->db->table_exists('riwayat_status_pengiriman_stok')) {
            $pendingPsTracks = $this->db->query("
                SELECT tb.id_pengiriman_stok
                FROM tracking_barang tb
                INNER JOIN pengiriman_stok ps ON ps.id_pengiriman_stok = tb.id_pengiriman_stok
                LEFT JOIN riwayat_status_pengiriman_stok rsps ON rsps.id_latest_riwayatstatus_pengiriman = ps.id_latest_riwayatstatus_pengiriman
                WHERE tb.stat = 1
                  AND tb.id_pengiriman_stok IS NOT NULL
                  AND (rsps.status_pengiriman = 'Diterima Seluruhnya' OR (ps.no_resi IS NOT NULL AND ps.no_resi != '' AND (tb.no_resi IS NULL OR tb.no_resi = '-')))
                ORDER BY tb.id_tracking DESC
                LIMIT 50
            ")->result();
            foreach ($pendingPsTracks as $row) {
                $this->syncPengirimanStokToTracking($row->id_pengiriman_stok);
            }
        }

        if ($this->db->table_exists('pengeluaran_barang')) {
            $pendingPbTracks = $this->db->query("
                SELECT tb.id_pengeluaran_barang
                FROM tracking_barang tb
                INNER JOIN pengeluaran_barang pb ON pb.id_pengeluaran_barang = tb.id_pengeluaran_barang
                WHERE tb.stat = 1
                  AND tb.id_pengeluaran_barang IS NOT NULL
                  AND (
                      LOWER(COALESCE(pb.status_pengiriman, '')) LIKE '%diterima%'
                      OR (pb.resi IS NOT NULL AND pb.resi != '' AND pb.resi != '-' AND (tb.no_resi IS NULL OR tb.no_resi = '-'))
                  )
                ORDER BY tb.id_tracking DESC
                LIMIT 50
            ")->result();
            foreach ($pendingPbTracks as $row) {
                $this->syncPengeluaranBarangToTracking($row->id_pengeluaran_barang);
            }
        }
    }
}
