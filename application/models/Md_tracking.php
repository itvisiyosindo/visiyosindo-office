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

        if ($this->db->table_exists('tracking_import')) {
            $newImportCols = [
                'hs_code'                   => "VARCHAR(100) NULL DEFAULT NULL AFTER `daftar_unit`",
                'ada_lartas'                => "VARCHAR(20) NULL DEFAULT NULL AFTER `hs_code`",
                'keterangan_lartas'         => "TEXT NULL DEFAULT NULL AFTER `ada_lartas`",
                'id_approval_forwarder'     => "INT(11) NULL DEFAULT NULL AFTER `id_forwarder`",
                'tgl_siap_jemput'           => "DATE NULL DEFAULT NULL AFTER `tgl_estimasi_sampai_gudang`",
                'tgl_jatuh_tempo_forwarder' => "DATE NULL DEFAULT NULL AFTER `tgl_siap_jemput`"
            ];
            foreach ($newImportCols as $col => $def) {
                if (!$this->db->field_exists($col, 'tracking_import')) {
                    @$this->db->query("ALTER TABLE `tracking_import` ADD COLUMN `{$col}` {$def}");
                }
            }
        }

        if (!$this->db->table_exists('tracking_import_steps')) {
            @$this->db->query("CREATE TABLE IF NOT EXISTS `tracking_import_steps` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_import` INT(11) NOT NULL,
                `stage` TINYINT(2) NOT NULL,
                `status` VARCHAR(20) NOT NULL DEFAULT 'belum',
                `data_json` LONGTEXT NULL DEFAULT NULL,
                `docs_json` LONGTEXT NULL DEFAULT NULL,
                `catatan` TEXT NULL DEFAULT NULL,
                `completed_at` DATETIME NULL DEFAULT NULL,
                `updated_by` INT(11) NULL DEFAULT NULL,
                `updated_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uniq_import_stage` (`id_import`, `stage`),
                KEY `idx_import_id` (`id_import`)
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
    // SISTEM TRACKING IMPORT (14 TAHAP END-TO-END STAFF IMPORT)
    // =========================================================================

    public function getImportPhaseDefinitions()
    {
        return [
            1 => ['name' => 'Fase 1: Pre-Order & Payment PI',      'short' => 'Pre-Order & PI',      'stages' => [1, 2, 3, 4],     'color' => '#2563eb', 'icon' => 'fa-file-contract'],
            2 => ['name' => 'Fase 2: Produksi & Shipping Docs',    'short' => 'Produksi & Shipping', 'stages' => [5, 6, 7],        'color' => '#7c3aed', 'icon' => 'fa-ship'],
            3 => ['name' => 'Fase 3: Customs & Pajak Impor',       'short' => 'Customs & Pajak',     'stages' => [8, 9, 10],       'color' => '#d97706', 'icon' => 'fa-file-invoice-dollar'],
            4 => ['name' => 'Fase 4: Release & Warehouse',         'short' => 'Release & Gudang',    'stages' => [11, 12],         'color' => '#0284c7', 'icon' => 'fa-warehouse'],
            5 => ['name' => 'Fase 5: Settlement Forwarder',        'short' => 'Settlement Forwarder','stages' => [13, 14],         'color' => '#16a34a', 'icon' => 'fa-money-check-alt'],
        ];
    }

    public function getImportStageDefinitions()
    {
        return [
            1 => [
                'stage' => 1,
                'phase' => 1,
                'label' => '1. Penerimaan Purchase Order (PO)',
                'short' => 'Penerimaan PO',
                'role'  => 'Purchasing / Office PO Supplier',
                'desc'  => 'Penerimaan dokumen PO Supplier dari sistem Office untuk diproses import.',
                'fields' => [],
                'docs' => [
                    'doc_po_supplier' => [
                        'label' => 'PO Supplier (dari Office - PO Supplier / Link Drive)',
                        'type'  => 'office_po'
                    ]
                ]
            ],
            2 => [
                'stage' => 2,
                'phase' => 1,
                'label' => '2. Pengecekan HS Code dan Lartas',
                'short' => 'Cek HS Code & Lartas',
                'role'  => 'Staff Import & Regulatory',
                'desc'  => 'Staff Import update pengecekan HS Code dan Lartas. Apabila ada lartas, berikan informasi rencana import ke Regulatory dan pastikan lartas terpenuhi saat barang tiba di Indonesia.',
                'wa_groups' => ['IZIN K3 ISO CDAKB VYM'],
                'wa_only_if_lartas' => true,
                'fields' => [
                    'hs_code' => [
                        'label' => 'Kode HS Code',
                        'type'  => 'text',
                        'placeholder' => 'Contoh: 9018.12.00'
                    ],
                    'ada_lartas' => [
                        'label' => 'Status Lartas',
                        'type'  => 'select',
                        'options' => [
                            ''      => '-- Pilih Status Lartas --',
                            'tidak' => 'Tidak Ada Lartas (Tahap 9 otomatis N/A)',
                            'ada'   => 'Ada Lartas (Perlu Koordinasi Regulatory)'
                        ]
                    ],
                    'keterangan_lartas' => [
                        'label' => 'Keterangan Lartas',
                        'type'  => 'textarea',
                        'placeholder' => 'Jelaskan ketentuan lartas / koordinasi dengan Regulatory...'
                    ]
                ],
                'docs' => [
                    'doc_hs_intr' => [
                        'label' => 'Print Check HS Code / Link Lartas dari INTR (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            3 => [
                'stage' => 3,
                'phase' => 1,
                'label' => '3. Pengiriman PO ke Supplier & Permintaan Proforma Invoice',
                'short' => 'Kirim PO & Minta PI',
                'role'  => 'Staff Import',
                'desc'  => 'Mengirimkan PO ke supplier luar negeri dan meminta dokumen Proforma Invoice (PI).',
                'wa_groups' => ['MEDIKAL IMPORT / PO'],
                'fields' => [
                    'status_permintaan_pi' => [
                        'label' => 'Status Permintaan Invoice (PI)',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Belum Diminta' => 'Belum Diminta',
                            'PO Terkirim / Menunggu PI' => 'PO Terkirim / Menunggu PI',
                            'Revisi PI' => 'Revisi PI',
                            'Proforma Invoice Diterima' => 'Proforma Invoice Diterima'
                        ]
                    ]
                ],
                'docs' => [
                    'doc_bukti_kirim_po' => [
                        'label' => 'Bukti Pengiriman PO ke Supplier (Link Drive)',
                        'type'  => 'link'
                    ],
                    'doc_proforma_invoice' => [
                        'label' => 'Proforma Invoice Supplier (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            4 => [
                'stage' => 4,
                'phase' => 1,
                'label' => '4. Proses Pembayaran Proforma Invoice (PI)',
                'short' => 'Pembayaran PI',
                'role'  => 'Staff Import & Finance',
                'desc'  => 'Staff Import memastikan informasi Price, Quantity, dan spesifikasi produk sesuai antara Dokumen PI dan PO sebelum melakukan proses pembayaran.',
                'fields' => [
                    'cek_kesesuaian_pi_po' => [
                        'label' => 'Verifikasi Kesesuaian Price, Qty & Spesifikasi (PI vs PO)',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Hasil Cek --',
                            'Belum Dicek' => 'Belum Dicek',
                            'Sesuai (PI = PO)' => 'Sesuai (PI = PO)',
                            'Perlu Revisi' => 'Perlu Revisi'
                        ]
                    ],
                    'status_pembayaran_pi' => [
                        'label' => 'Status Pembayaran Invoice',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Belum Diajukan' => 'Belum Diajukan',
                            'Diajukan PBOK' => 'Diajukan PBOK',
                            'Proses Transfer / DP' => 'Proses Transfer / DP',
                            'Lunas / Sudah Dibayar' => 'Lunas / Sudah Dibayar'
                        ]
                    ]
                ],
                'docs' => [
                    'doc_pbok_pi' => [
                        'label' => 'Link Lampiran PBOK (PI, PO Supplier, Dokumen Pendukung Lainnya)',
                        'type'  => 'office_pbok'
                    ],
                    'doc_bukti_bayar_pi' => [
                        'label' => 'Bukti Pembayaran PI (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            5 => [
                'stage' => 5,
                'phase' => 2,
                'label' => '5. Follow Up Status Produksi & Tanggal Pengiriman Produk',
                'short' => 'Produksi & Forwarder',
                'role'  => 'Staff Import & Forwarder',
                'desc'  => 'Monitoring proses produksi di supplier, pemilihan/perbandingan forwarder, dan jadwal pengiriman.',
                'fields' => [
                    'status_produksi' => [
                        'label' => 'Update Status Produksi',
                        'type'  => 'text',
                        'placeholder' => 'Contoh: Antri Produksi / Proses Produksi / Ready to Ship'
                    ],
                    'tgl_rencana_kirim' => [
                        'label' => 'Tanggal Rencana / Pengiriman Produk',
                        'type'  => 'date'
                    ],
                    'status_pengiriman' => [
                        'label' => 'Update Status Pengiriman',
                        'type'  => 'text',
                        'placeholder' => 'Contoh: Booking Space Forwarder / Pickup / Sudah Berangkat'
                    ]
                ],
                'docs' => [
                    'doc_perbandingan_forwarder' => [
                        'label' => 'Link Perbandingan Forwarder (dari Office / Link Drive)',
                        'type'  => 'office_forwarder'
                    ]
                ]
            ],
            6 => [
                'stage' => 6,
                'phase' => 2,
                'label' => '6. Koordinasi Dokumen CIPL',
                'short' => 'Dokumen CIPL',
                'role'  => 'Staff Import & Supplier',
                'desc'  => 'Koordinasi kelengkapan dan validasi dokumen Commercial Invoice & Packing List.',
                'wa_groups' => ['MEDIKAL IMPORT / PO'],
                'fields' => [
                    'status_cipl' => [
                        'label' => 'Update Status Dokumen CIPL',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Belum Diminta' => 'Belum Diminta',
                            'Draft / Review' => 'Draft / Review',
                            'Revisi' => 'Revisi',
                            'Final Diterima' => 'Final Diterima'
                        ]
                    ]
                ],
                'docs' => [
                    'doc_commercial_invoice' => [
                        'label' => 'Commercial Invoice (Link Drive)',
                        'type'  => 'link'
                    ],
                    'doc_packing_list' => [
                        'label' => 'Packing List (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            7 => [
                'stage' => 7,
                'phase' => 2,
                'label' => '7. Koordinasi Dokumen AWB / BL',
                'short' => 'Dokumen AWB / BL',
                'role'  => 'Staff Import & Forwarder',
                'desc'  => 'Koordinasi dokumen Air Way Bill (AWB) / Bill of Lading (BL) serta foto produk sebelum dikirim.',
                'wa_groups' => ['Gudang PT. VYM', 'MEDIKAL IMPORT / PO'],
                'fields' => [
                    'status_awb_bl' => [
                        'label' => 'Update Status Dokumen AWB / BL',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Menunggu Draft' => 'Menunggu Draft',
                            'Draft / Review' => 'Draft / Review',
                            'Final / Telex Release' => 'Final / Telex Release'
                        ]
                    ],
                    'no_bl_awb' => [
                        'label' => 'Nomor AWB / B/L',
                        'type'  => 'text',
                        'placeholder' => 'Masukkan nomor AWB atau Bill of Lading'
                    ]
                ],
                'docs' => [
                    'doc_foto_produk' => [
                        'label' => 'Foto Produk Sebelum Dikirim (Link Drive)',
                        'type'  => 'link'
                    ],
                    'doc_awb_bl' => [
                        'label' => 'Air Way Bill (AWB) / Bill of Lading (BL) (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            8 => [
                'stage' => 8,
                'phase' => 3,
                'label' => '8. Koordinasi Dokumen PIB',
                'short' => 'Dokumen PIB',
                'role'  => 'Staff Import & PPJK / Forwarder',
                'desc'  => 'Koordinasi penyusunan dan validasi Pemberitahuan Import Barang (PIB).',
                'fields' => [
                    'status_pib' => [
                        'label' => 'Update Status Dokumen PIB',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Menunggu Draft PIB' => 'Menunggu Draft PIB',
                            'Draft PIB Direview' => 'Draft PIB Direview',
                            'PIB Submitted / Aju' => 'PIB Submitted / Aju',
                            'PIB Final' => 'PIB Final'
                        ]
                    ],
                    'no_pib' => [
                        'label' => 'Nomor PIB / Aju',
                        'type'  => 'text',
                        'placeholder' => 'Nomor Aju / PIB'
                    ]
                ],
                'docs' => [
                    'doc_pib' => [
                        'label' => 'Pemberitahuan Import Barang (PIB) (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            9 => [
                'stage' => 9,
                'phase' => 3,
                'label' => '9. Koordinasi Dokumen Lartas',
                'short' => 'Dokumen Lartas',
                'role'  => 'Staff Import & Regulatory',
                'conditional_lartas' => true,
                'desc'  => 'Pastikan kebutuhan dokumen pengurusan lartas sudah lengkap dan sesuai informasi di PIB, AWB/BL, dan CIPL. Koordinasi dengan Regulatory (jika ada lartas).',
                'wa_groups' => ['IZIN K3 ISO CDAKB VYM'],
                'fields' => [
                    'status_dokumen_lartas' => [
                        'label' => 'Update Status Dokumen Lartas',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Tidak Ada Lartas (N/A)' => 'Tidak Ada Lartas (N/A)',
                            'Koordinasi Regulatory' => 'Koordinasi Regulatory',
                            'Proses Pengurusan' => 'Proses Pengurusan',
                            'Dokumen Lartas Terbit / Lengkap' => 'Dokumen Lartas Terbit / Lengkap'
                        ]
                    ]
                ],
                'docs' => [
                    'doc_lartas' => [
                        'label' => 'Dokumen Lartas (jika ada) (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            10 => [
                'stage' => 10,
                'phase' => 3,
                'label' => '10. Koordinasi Dokumen Pajak Impor',
                'short' => 'Pajak Impor',
                'role'  => 'Staff Import & Finance',
                'desc'  => 'Pengurusan Billing Pajak Impor (Bea Masuk, PPN, PPh), pengajuan PBOK, hingga bukti bayar pajak impor.',
                'wa_groups' => ['MEDIKAL IMPORT / PO'],
                'fields' => [
                    'status_pajak_impor' => [
                        'label' => 'Update Status Dokumen Pajak Impor',
                        'type'  => 'select',
                        'col'   => 'col-md-12',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Menunggu Billing' => 'Menunggu Billing',
                            'Billing Diterima' => 'Billing Diterima',
                            'Diajukan PBOK' => 'Diajukan PBOK',
                            'Pajak Sudah Dibayar' => 'Pajak Sudah Dibayar'
                        ]
                    ],
                    'bea_masuk' => [
                        'label' => 'Bea Masuk (Rp)',
                        'type'  => 'number',
                        'col'   => 'col-md-4',
                        'placeholder' => 'Nominal Bea Masuk'
                    ],
                    'ppn_impor' => [
                        'label' => 'PPN Impor (Rp)',
                        'type'  => 'number',
                        'col'   => 'col-md-4',
                        'placeholder' => 'Nominal PPN Impor'
                    ],
                    'pph_impor' => [
                        'label' => 'PPh Impor (Rp)',
                        'type'  => 'number',
                        'col'   => 'col-md-4',
                        'placeholder' => 'Nominal PPh Impor'
                    ],
                    'nominal_pajak_import' => [
                        'label' => 'Total Pajak Impor (Rp) — Otomatis Bea Masuk + PPN + PPh',
                        'type'  => 'number',
                        'col'   => 'col-md-12',
                        'readonly' => true,
                        'placeholder' => 'Otomatis dijumlahkan dari Bea Masuk + PPN + PPh'
                    ]
                ],
                'docs' => [
                    'doc_billing_pajak' => [
                        'label' => 'Billing Pajak Impor (Link Drive)',
                        'type'  => 'link'
                    ],
                    'doc_pbok_pajak' => [
                        'label' => 'Link Lampiran PBOK (Billing Pajak, PI, CI, PL, Resi, PIB, Lartas, dll.)',
                        'type'  => 'office_pbok'
                    ],
                    'doc_bukti_bayar_pajak' => [
                        'label' => 'Bukti Bayar Pajak Import (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            11 => [
                'stage' => 11,
                'phase' => 4,
                'label' => '11. Follow Up Status Pengeluaran Barang',
                'short' => 'Pengeluaran / SPPB',
                'role'  => 'Staff Import & Forwarder',
                'desc'  => 'Follow up SPPB, proses bongkar barang di pelabuhan/bandara, pemindahan ke gudang forwarder/perusahaan, dan tanggal barang siap dijemput/dikirim.',
                'wa_groups' => ['Gudang PT. VYM'],
                'fields' => [
                    'status_pengeluaran' => [
                        'label' => 'Update Status Pengeluaran Barang (Bongkar Port / Pindah Gudang Forwarder)',
                        'type'  => 'text',
                        'placeholder' => 'Contoh: SPPB Terbit / Bongkar di Pelabuhan / Siap Dijemput'
                    ],
                    'tgl_siap_jemput' => [
                        'label' => 'Tanggal Barang Bisa Dijemput / Dikirim ke Warehouse',
                        'type'  => 'date'
                    ]
                ],
                'docs' => [
                    'doc_sppb' => [
                        'label' => 'SPPB / Info Barang Siap Dijemput atau Dikirim ke Warehouse (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            12 => [
                'stage' => 12,
                'phase' => 4,
                'label' => '12. Follow Up Penjemputan / Pengiriman ke Warehouse',
                'short' => 'Kirim ke Warehouse',
                'role'  => 'Staff Import & Warehouse',
                'desc'  => 'Penjemputan oleh Warehouse atau pengiriman via ekspedisi lokal beserta bukti foto penerimaan barang.',
                'fields' => [
                    'status_penjemputan' => [
                        'label' => 'Status Penjemputan / Pengiriman ke Warehouse',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Belum Dijemput' => 'Belum Dijemput',
                            'Dijadwalkan Penjemputan' => 'Dijadwalkan Penjemputan',
                            'Dalam Perjalanan ke Warehouse' => 'Dalam Perjalanan ke Warehouse',
                            'Diterima di Warehouse' => 'Diterima di Warehouse'
                        ]
                    ],
                    'ekspedisi_domestik' => [
                        'label' => 'Ekspedisi yang Digunakan untuk Penjemputan / Pengiriman',
                        'type'  => 'text',
                        'placeholder' => 'Contoh: Armada Internal VYM / JNE Cargo / Forwarder'
                    ],
                    'no_resi_domestik' => [
                        'label' => 'No. Resi / Surat Jalan Lokal (Opsional)',
                        'type'  => 'text',
                        'placeholder' => 'No. Resi jika pakai ekspedisi'
                    ],
                    'nama_gudang_tujuan' => [
                        'label' => 'Gudang Tujuan VYM',
                        'type'  => 'select',
                        'options' => [
                            'Gudang Pusat'   => 'Gudang Pusat',
                            'Gudang Jakarta' => 'Gudang Jakarta',
                            'Gudang Jogja'   => 'Gudang Jogja'
                        ]
                    ]
                ],
                'docs' => [
                    'doc_bukti_terima_wh' => [
                        'label' => 'Bukti Foto Produk / Penerimaan Barang oleh Warehouse (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            13 => [
                'stage' => 13,
                'phase' => 5,
                'label' => '13. Follow Up Dokumen Invoice Forwarder (Ongkir & Handling)',
                'short' => 'Invoice Forwarder',
                'role'  => 'Staff Import & Forwarder',
                'desc'  => 'Pengumpulan Invoice Forwarder, Faktur Pajak Forwarder, dan pencatatan tanggal jatuh tempo pembayaran.',
                'wa_groups' => ['MEDIKAL IMPORT / PO'],
                'fields' => [
                    'status_inv_forwarder' => [
                        'label' => 'Update Status Dokumen Pembayaran',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Menunggu Invoice Forwarder' => 'Menunggu Invoice Forwarder',
                            'Invoice & Faktur Pajak Diterima' => 'Invoice & Faktur Pajak Diterima',
                            'Review / Verifikasi Nominal' => 'Review / Verifikasi Nominal',
                            'Siap Diajukan Pembayaran' => 'Siap Diajukan Pembayaran'
                        ]
                    ],
                    'tgl_jatuh_tempo_forwarder' => [
                        'label' => 'Info Jatuh Tempo Pembayaran',
                        'type'  => 'date'
                    ]
                ],
                'docs' => [
                    'doc_invoice_forwarder' => [
                        'label' => 'Invoice Forwarder (Link Drive)',
                        'type'  => 'link'
                    ],
                    'doc_faktur_pajak_forwarder' => [
                        'label' => 'Faktur Pajak Invoice Forwarder (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
            ],
            14 => [
                'stage' => 14,
                'phase' => 5,
                'label' => '14. Follow Up Pembayaran Invoice Forwarder',
                'short' => 'Bayar Inv. Forwarder',
                'role'  => 'Staff Import & Finance',
                'desc'  => 'Pengajuan PBOK pembayaran Invoice Forwarder, bukti potong pajak, hingga pelunasan.',
                'fields' => [
                    'status_bayar_forwarder' => [
                        'label' => 'Update Status Pembayaran Invoice Forwarder',
                        'type'  => 'select',
                        'options' => [
                            '' => '-- Pilih Status --',
                            'Belum Diajukan' => 'Belum Diajukan',
                            'Diajukan PBOK' => 'Diajukan PBOK',
                            'Proses Transfer' => 'Proses Transfer',
                            'Lunas / Selesai' => 'Lunas / Selesai'
                        ]
                    ]
                ],
                'docs' => [
                    'doc_bukti_potong_pajak' => [
                        'label' => 'Bukti Potong Pajak (Link Drive)',
                        'type'  => 'link'
                    ],
                    'doc_pbok_forwarder' => [
                        'label' => 'Link Lampiran PBOK (Inv. Forwarder, Faktur Pajak, PI/CI, PL, Resi, Bukti Terima, dll.)',
                        'type'  => 'office_pbok'
                    ],
                    'doc_bukti_bayar_forwarder' => [
                        'label' => 'Bukti Bayar Invoice Forwarder (Link Drive)',
                        'type'  => 'link'
                    ]
                ]
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
                    (SELECT GROUP_CONCAT(CONCAT(pod.nama_barang, ' (', COALESCE(NULLIF(pod.rencana_po,''), NULLIF(pod.kebutuhan_po,''), '1'), ')') SEPARATOR ', ') FROM purchase_order_detail pod WHERE pod.id_po = po.id) as items_summary
                FROM purchase_order po
                WHERE po.status != 5
                ORDER BY po.id DESC
                LIMIT 150";
        $rows = $this->db->query($sql)->result();
        if (empty($rows)) {
            return [];
        }

        // Batch-load detail produk per PO agar nama produk sama persis dengan PO Supplier
        $poIds = array_map(function ($r) { return (int)$r->id_po; }, $rows);
        $itemsByPo = [];
        if ($this->db->table_exists('purchase_order_detail') && !empty($poIds)) {
            $detailRows = $this->db->select('id_pod, id_po, nama_barang, kode_barang, rencana_po, kebutuhan_po, supplier')
                ->from('purchase_order_detail')
                ->where_in('id_po', $poIds)
                ->order_by('id_pod', 'ASC')
                ->get()
                ->result();
            foreach ($detailRows as $dr) {
                $qty = (trim((string)$dr->rencana_po) !== '' && (float)$dr->rencana_po > 0)
                    ? trim((string)$dr->rencana_po)
                    : ((trim((string)$dr->kebutuhan_po) !== '' && (float)$dr->kebutuhan_po > 0) ? trim((string)$dr->kebutuhan_po) : '1');
                $itemsByPo[(int)$dr->id_po][] = [
                    'id_pod'      => (int)$dr->id_pod,
                    'nama_barang' => trim((string)$dr->nama_barang),
                    'kode_barang' => trim((string)$dr->kode_barang),
                    'qty'         => $qty,
                    'supplier'    => trim((string)$dr->supplier)
                ];
            }
        }
        foreach ($rows as $r) {
            $r->items = $itemsByPo[(int)$r->id_po] ?? [];
        }
        return $rows;
    }

    public function getHsCodeKategoriId()
    {
        if (!$this->db->table_exists('masterdokumenproduct')) {
            return 0;
        }
        $kat = $this->db->query("SELECT id FROM masterdokumenproduct WHERE UPPER(TRIM(keterangan)) = 'HS CODE' LIMIT 1")->row();
        return $kat ? (int)$kat->id : 0;
    }

    public function getAllHsCodeMaster()
    {
        if (!$this->db->table_exists('dokumen_product') || !$this->db->table_exists('masterdokumenproduct')) {
            return [];
        }
        $katId = $this->getHsCodeKategoriId();
        if ($katId <= 0) {
            return [];
        }
        return $this->db->select('id, nama_dokumen, kode_hs_code, keterangan, dokumen_lartas, tarif_impor, file')
            ->from('dokumen_product')
            ->where('id_kategori', $katId)
            ->where('status', 1)
            ->where("kode_hs_code IS NOT NULL AND TRIM(kode_hs_code) != ''", null, false)
            ->order_by('kode_hs_code', 'ASC')
            ->get()
            ->result();
    }

    public function getAllPbokForImport()
    {
        if (!$this->db->table_exists('surat_pbok')) {
            return [];
        }
        $sql = "SELECT 
                    sb.id_pbok,
                    sb.kode_pbok,
                    sb.tgl_pengajuan,
                    p.nama as nama_pengaju,
                    (SELECT GROUP_CONCAT(d.keterangan SEPARATOR ', ') FROM surat_pbok_detail d WHERE d.id_pbok = sb.id_pbok) as ringkasan
                FROM surat_pbok sb
                LEFT JOIN pengguna p ON p.pengguna_id = sb.id_pengguna
                ORDER BY sb.id_pbok DESC
                LIMIT 150";
        return $this->db->query($sql)->result();
    }

    public function getAllApprovalForwarderForImport()
    {
        if (!$this->db->table_exists('approval_forwarder')) {
            return [];
        }
        $sql = "SELECT 
                    af.id,
                    af.kode,
                    af.nama,
                    af.tanggal,
                    af.pol,
                    af.pod,
                    (SELECT f.nama FROM approval_forwarder_detail afd JOIN forwarder f ON afd.id_forwarder = f.id WHERE afd.id_app = af.id ORDER BY afd.dipilih DESC, afd.id ASC LIMIT 1) as nama_forwarder
                FROM approval_forwarder af
                ORDER BY af.id DESC
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

    public function ensureImportStepRow($id_import, $stage, $defaults = [])
    {
        if (!$this->db->table_exists('tracking_import_steps')) {
            return;
        }
        $exists = $this->db->get_where('tracking_import_steps', [
            'id_import' => (int)$id_import,
            'stage'     => (int)$stage
        ])->row();

        if (!$exists) {
            $ins = [
                'id_import'    => (int)$id_import,
                'stage'        => (int)$stage,
                'status'       => $defaults['status'] ?? 'belum',
                'data_json'    => isset($defaults['data']) ? json_encode($defaults['data'], JSON_UNESCAPED_UNICODE) : null,
                'docs_json'    => isset($defaults['docs']) ? json_encode($defaults['docs'], JSON_UNESCAPED_UNICODE) : null,
                'catatan'      => $defaults['catatan'] ?? null,
                'completed_at' => $defaults['completed_at'] ?? null,
                'updated_by'   => $defaults['updated_by'] ?? null,
                'updated_at'   => date('Y-m-d H:i:s')
            ];
            $this->db->insert('tracking_import_steps', $ins);
        } else {
            // Merge non-destructively jika diminta dari auto-sync
            $curData = !empty($exists->data_json) ? (json_decode($exists->data_json, true) ?: []) : [];
            $curDocs = !empty($exists->docs_json) ? (json_decode($exists->docs_json, true) ?: []) : [];
            $upd = [];

            if (!empty($defaults['docs'])) {
                $changed = false;
                foreach ($defaults['docs'] as $dk => $dv) {
                    if (empty($curDocs[$dk]) && !empty($dv)) {
                        $curDocs[$dk] = $dv;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $upd['docs_json'] = json_encode($curDocs, JSON_UNESCAPED_UNICODE);
                }
            }

            if (!empty($defaults['data'])) {
                $changed = false;
                foreach ($defaults['data'] as $dk => $dv) {
                    if (empty($curData[$dk]) && !empty($dv)) {
                        $curData[$dk] = $dv;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $upd['data_json'] = json_encode($curData, JSON_UNESCAPED_UNICODE);
                }
            }

            if (!empty($defaults['status']) && $exists->status === 'belum' && $defaults['status'] !== 'belum') {
                $upd['status'] = $defaults['status'];
                if ($defaults['status'] === 'selesai' && empty($exists->completed_at) && !empty($defaults['completed_at'])) {
                    $upd['completed_at'] = $defaults['completed_at'];
                }
            }

            if (!empty($upd)) {
                $upd['updated_at'] = date('Y-m-d H:i:s');
                $this->db->where('id', $exists->id)->update('tracking_import_steps', $upd);
            }
        }
    }

    /**
     * Sinkronisasi Otomatis Tracking Import (tanpa interpolasi fiktif):
     * - Tahap 1: purchase_order (Pengajuan PO Supplier) -> otomatis link ke detail PO Office
     * - Tahap 5: approval_forwarder (Perbandingan Forwarder) -> otomatis link ke detail Approval Forwarder Office
     * - Tahap 12: penerimaan_barang (Penerimaan Barang Gudang) -> otomatis tandai diterima di Warehouse
     */
    public function syncAutomaticImportFromExistingData()
    {
        if (!$this->db->table_exists('purchase_order') || !$this->db->table_exists('tracking_import')) {
            return;
        }

        $stage_defs = $this->getImportStageDefinitions();

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

        $existingRows = $this->db->get('tracking_import')->result();
        $byPoId = [];
        foreach ($existingRows as $er) {
            if (!empty($er->id_po_supplier)) {
                $byPoId[(int)$er->id_po_supplier] = $er;
            }
        }

        $appForwarders = [];
        if ($this->db->table_exists('approval_forwarder')) {
            $sqlAf = "SELECT af.*,
                        (SELECT afd.id_forwarder FROM approval_forwarder_detail afd WHERE afd.id_app = af.id ORDER BY afd.dipilih DESC, afd.id ASC LIMIT 1) as id_forwarder_chose,
                        (SELECT f.nama FROM approval_forwarder_detail afd JOIN forwarder f ON afd.id_forwarder = f.id WHERE afd.id_app = af.id ORDER BY afd.dipilih DESC, afd.id ASC LIMIT 1) as nama_forwarder_chose
                      FROM approval_forwarder af
                      ORDER BY af.id DESC";
            $appForwarders = $this->db->query($sqlAf)->result();
        }

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

        $localSkipKeywords = ['uang kas', 'shopee', 'tokopedia', 'nuklindolab', 'sumpah gajah mada', 'uno medical', 'alisha jaya', 'inti cakra'];

        foreach ($poList as $po) {
            $supplierName = trim((string)($po->supplier ?: ''));
            $noPoRaw      = trim(strip_tags((string)($po->no_po ?: $po->kode_po)));
            if ($supplierName === '' && empty($po->items_summary)) {
                continue;
            }

            $matchedAf = null;
            $noPoDigits = '';
            if (preg_match('/\b(0\d{2})\b/', $noPoRaw, $mNum)) {
                $noPoDigits = $mNum[1];
            }

            foreach ($appForwarders as $af) {
                $afTitle = strtolower((string)$af->nama . ' ' . (string)$af->keterangan);
                if ($noPoDigits !== '' && strpos($afTitle, strtolower($noPoDigits)) !== false) {
                    $matchedAf = $af;
                    break;
                }
                if ($supplierName !== '' && strlen($supplierName) >= 4) {
                    $firstWord = strtolower(strtok($supplierName, ' '));
                    if (strlen($firstWord) >= 3 && !in_array($firstWord, ['pt', 'pt.', 'cv', 'cv.', 'lain-lain']) && strpos($afTitle, $firstWord) !== false) {
                        $matchedAf = $af;
                        break;
                    }
                }
            }

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

            $waktu1 = !empty($po->ttd_4_at) && strpos($po->ttd_4_at, '0000-00-00') === false
                ? $po->ttd_4_at
                : (!empty($po->created_at) && strpos($po->created_at, '0000-00-00') === false
                    ? $po->created_at
                    : (!empty($po->tgl_pengajuan) ? date('Y-m-d H:i:s', strtotime($po->tgl_pengajuan)) : date('Y-m-d H:i:s')));

            $matchedPb = null;
            $poDateOnly = date('Y-m-d', strtotime($waktu1));
            foreach ($penerimaanList as $pb) {
                $pbPo = trim((string)$pb->no_po);
                if ($pbPo === '') continue;
                $pbDate = !empty($pb->tgl_masuk) ? $pb->tgl_masuk : (!empty($pb->data_created) ? date('Y-m-d', strtotime($pb->data_created)) : '');
                if ($pbDate !== '' && strtotime($pbDate) < (strtotime($poDateOnly) - 86400 * 7)) {
                    continue;
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

            $moda   = 'Sea Freight (Kapal)';
            $idFw   = null;
            $idAf   = null;
            $namaFw = null;
            $pol    = null;
            $pod    = null;

            if ($matchedAf) {
                $idAf   = (int)$matchedAf->id;
                $idFw   = $matchedAf->id_forwarder_chose ?: null;
                $namaFw = $matchedAf->nama_forwarder_chose ?: null;
                $pol    = $matchedAf->pol ?: null;
                $pod    = $matchedAf->pod ?: null;
                if (stripos((string)$matchedAf->sistem_pengiriman, 'udara') !== false || stripos((string)$matchedAf->sistem_pengiriman, 'air') !== false) {
                    $moda = 'Air Freight (Pesawat)';
                }
            }

            $waktuTerimaWh = null;
            $gudangTujuan  = 'Gudang Pusat';
            $ketPenerimaan = null;

            if ($matchedPb) {
                $waktuTerimaWh = !empty($matchedPb->data_created) && strpos($matchedPb->data_created, '0000-00-00') === false
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
            }

            $poOfficeUrl = base_url('purchase_order/show/detail/purchase_order/' . $po->id_po);
            $afOfficeUrl = $matchedAf ? base_url('forwarder/show/detail/' . $matchedAf->id) : null;

            if (!isset($byPoId[(int)$po->id_po])) {
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
                    'id_approval_forwarder'    => $idAf,
                    'nama_forwarder'           => $namaFw,
                    'pelabuhan_asal'           => $pol,
                    'pelabuhan_tujuan'         => $pod,
                    'nama_gudang_tujuan'       => $gudangTujuan,
                    'bukti_terima_gudang'      => !empty($po->lampiran_adm) ? strip_tags($po->lampiran_adm) : null,
                    'waktu_1_po_manufacture'   => $waktu1,
                    'waktu_9_sampai_gudang'    => $waktuTerimaWh,
                    'current_stage'            => $waktuTerimaWh ? 12 : ($matchedAf ? 5 : 1),
                    'keterangan'               => $ketPenerimaan ?: (!empty($po->ket_adm) ? strip_tags($po->ket_adm) : ('Tersinkronisasi dari PO Supplier ' . $po->kode_po)),
                    'created_by'               => $po->id_pengaju ?: 1,
                    'created_at'               => $waktu1,
                    'updated_at'               => date('Y-m-d H:i:s'),
                    'stat'                     => 1
                ];

                $this->db->insert('tracking_import', $ins);
                $newId = $this->db->insert_id();

                if ($newId) {
                    // Tahap 1: Penerimaan PO (Otomatis Selesai)
                    $this->ensureImportStepRow($newId, 1, [
                        'status'       => 'selesai',
                        'docs'         => ['doc_po_supplier' => $poOfficeUrl],
                        'catatan'      => 'Otomatis ditarik dari Office PO Supplier: ' . $po->kode_po,
                        'completed_at' => $waktu1,
                        'updated_by'   => $po->id_pengaju ?: 1
                    ]);

                    $this->db->insert('tracking_import_log', [
                        'id_import'       => $newId,
                        'stage'           => 1,
                        'stage_label'     => $stage_defs[1]['label'],
                        'waktu_milestone' => $waktu1,
                        'catatan'         => 'Otomatis ditarik dari Pengajuan PO Supplier: ' . $po->kode_po . ' (No PO: ' . ($noPoRaw ?: '-') . ')',
                        'bukti_link'      => $poOfficeUrl,
                        'id_pengguna'     => $po->id_pengaju ?: 1,
                        'created_at'      => $waktu1
                    ]);

                    // Tahap 5: Link Perbandingan Forwarder jika ada
                    if ($matchedAf && $afOfficeUrl) {
                        $this->ensureImportStepRow($newId, 5, [
                            'status'     => 'proses',
                            'docs'       => ['doc_perbandingan_forwarder' => $afOfficeUrl],
                            'catatan'    => 'Terhubung otomatis dengan Approval Forwarder: ' . $matchedAf->kode,
                            'updated_by' => $matchedAf->id_pengguna ?: 1
                        ]);
                    }

                    // Tahap 12: Penerimaan Warehouse jika sudah masuk penerimaan_barang
                    if ($waktuTerimaWh) {
                        $this->ensureImportStepRow($newId, 12, [
                            'status'       => 'selesai',
                            'data'         => [
                                'status_penjemputan' => 'Diterima di Warehouse',
                                'nama_gudang_tujuan' => $gudangTujuan
                            ],
                            'docs'         => !empty($po->lampiran_adm) ? ['doc_bukti_terima_wh' => strip_tags($po->lampiran_adm)] : [],
                            'catatan'      => $ketPenerimaan,
                            'completed_at' => $waktuTerimaWh,
                            'updated_by'   => $po->id_pengaju ?: 1
                        ]);
                    }
                }
            } else {
                // Pastikan row lama juga memiliki step Tahap 1, 5, dan 12 jika cocok
                $ex = $byPoId[(int)$po->id_po];
                $this->ensureImportStepRow($ex->id_import, 1, [
                    'status'       => 'selesai',
                    'docs'         => ['doc_po_supplier' => $poOfficeUrl],
                    'completed_at' => $ex->waktu_1_po_manufacture ?: $waktu1,
                    'updated_by'   => $ex->created_by ?: 1
                ]);

                $upd = [];
                if ($matchedAf && $afOfficeUrl) {
                    if (empty($ex->id_approval_forwarder)) $upd['id_approval_forwarder'] = $idAf;
                    if (empty($ex->nama_forwarder) && $namaFw) $upd['nama_forwarder'] = $namaFw;
                    if (empty($ex->pelabuhan_asal) && $pol) $upd['pelabuhan_asal'] = $pol;
                    if (empty($ex->pelabuhan_tujuan) && $pod) $upd['pelabuhan_tujuan'] = $pod;
                    $this->ensureImportStepRow($ex->id_import, 5, [
                        'status'     => 'proses',
                        'docs'       => ['doc_perbandingan_forwarder' => $afOfficeUrl],
                        'updated_by' => $matchedAf->id_pengguna ?: 1
                    ]);
                }

                if ($waktuTerimaWh) {
                    if (empty($ex->waktu_9_sampai_gudang)) {
                        $upd['waktu_9_sampai_gudang'] = $waktuTerimaWh;
                        $upd['nama_gudang_tujuan']    = $gudangTujuan;
                    }
                    $this->ensureImportStepRow($ex->id_import, 12, [
                        'status'       => 'selesai',
                        'data'         => [
                            'status_penjemputan' => 'Diterima di Warehouse',
                            'nama_gudang_tujuan' => $gudangTujuan
                        ],
                        'catatan'      => $ketPenerimaan,
                        'completed_at' => $waktuTerimaWh,
                        'updated_by'   => $ex->created_by ?: 1
                    ]);
                }

                if (!empty($upd)) {
                    $upd['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->where('id_import', $ex->id_import)->update('tracking_import', $upd);
                }
            }
        }
    }

    public function getImportStepsMap($id_import)
    {
        $defs = $this->getImportStageDefinitions();
        $rows = [];
        if ($this->db->table_exists('tracking_import_steps')) {
            $rows = $this->db->select('s.*, p.nama as nama_pengguna')
                ->from('tracking_import_steps s')
                ->join('pengguna p', 's.updated_by = p.pengguna_id', 'left')
                ->where('s.id_import', (int)$id_import)
                ->get()->result();
        }

        $byStage = [];
        foreach ($rows as $r) {
            $r->data = !empty($r->data_json) ? (json_decode($r->data_json, true) ?: []) : [];
            $r->docs = !empty($r->docs_json) ? (json_decode($r->docs_json, true) ?: []) : [];
            $byStage[(int)$r->stage] = $r;
        }

        $result = [];
        foreach ($defs as $st => $def) {
            if (isset($byStage[$st])) {
                $result[$st] = $byStage[$st];
            } else {
                $result[$st] = (object)[
                    'id'            => null,
                    'id_import'     => (int)$id_import,
                    'stage'         => $st,
                    'status'        => 'belum',
                    'data'          => [],
                    'docs'          => [],
                    'catatan'       => null,
                    'completed_at'  => null,
                    'updated_by'    => null,
                    'updated_at'    => null,
                    'nama_pengguna' => null
                ];
            }
        }
        return $result;
    }

    public function getAllImportStepsGrouped($importIds = [])
    {
        $grouped = [];
        if (empty($importIds) || !$this->db->table_exists('tracking_import_steps')) {
            return $grouped;
        }
        $rows = $this->db->where_in('id_import', $importIds)->get('tracking_import_steps')->result();
        foreach ($rows as $r) {
            $r->data = !empty($r->data_json) ? (json_decode($r->data_json, true) ?: []) : [];
            $r->docs = !empty($r->docs_json) ? (json_decode($r->docs_json, true) ?: []) : [];
            $grouped[(int)$r->id_import][(int)$r->stage] = $r;
        }
        return $grouped;
    }

    public function saveImportStep($id_import, $stage, $payload, $user_id)
    {
        $defs = $this->getImportStageDefinitions();
        if (!isset($defs[$stage])) {
            return ['ok' => false, 'msg' => 'Tahapan import tidak valid!'];
        }

        $row = $this->getTrackingImportById($id_import);
        if (!$row) {
            return ['ok' => false, 'msg' => 'Data Tracking Import tidak ditemukan!'];
        }

        $def = $defs[$stage];
        $now = date('Y-m-d H:i:s');

        $status = strtolower(trim((string)($payload['status'] ?? 'proses')));
        if (!in_array($status, ['belum', 'proses', 'selesai', 'na'], true)) {
            $status = 'proses';
        }
        if ($stage !== 9 && $status === 'na') {
            $status = 'belum';
        }

        // Ambil data & docs lama agar tidak hilang jika sebagian tidak dikirim
        $existing = $this->db->get_where('tracking_import_steps', [
            'id_import' => (int)$id_import,
            'stage'     => (int)$stage
        ])->row();

        $stepData = $existing && !empty($existing->data_json) ? (json_decode($existing->data_json, true) ?: []) : [];
        $stepDocs = $existing && !empty($existing->docs_json) ? (json_decode($existing->docs_json, true) ?: []) : [];

        if (!empty($payload['data']) && is_array($payload['data'])) {
            foreach ($def['fields'] as $fk => $fmeta) {
                if (array_key_exists($fk, $payload['data'])) {
                    $stepData[$fk] = trim((string)$payload['data'][$fk]);
                }
            }
        }

        // Tahap 10: otomatis hitung Total Pajak Impor = Bea Masuk + PPN + PPh
        if ($stage === 10) {
            $parseRp = function ($v) {
                $clean = preg_replace('/[^0-9.]/', '', str_replace(',', '', trim((string)$v)));
                return $clean !== '' ? (float)$clean : 0.0;
            };
            $hasBreakdown = (
                (isset($stepData['bea_masuk']) && trim((string)$stepData['bea_masuk']) !== '') ||
                (isset($stepData['ppn_impor']) && trim((string)$stepData['ppn_impor']) !== '') ||
                (isset($stepData['pph_impor']) && trim((string)$stepData['pph_impor']) !== '')
            );
            if ($hasBreakdown) {
                $bm  = $parseRp($stepData['bea_masuk'] ?? '');
                $ppn = $parseRp($stepData['ppn_impor'] ?? '');
                $pph = $parseRp($stepData['pph_impor'] ?? '');
                $stepData['bea_masuk'] = $bm > 0 ? (string)$bm : '0';
                $stepData['ppn_impor'] = $ppn > 0 ? (string)$ppn : '0';
                $stepData['pph_impor'] = $pph > 0 ? (string)$pph : '0';
                $stepData['nominal_pajak_import'] = (string)($bm + $ppn + $pph);
            }
        }

        $firstDocLink = null;
        if (!empty($payload['docs']) && is_array($payload['docs'])) {
            foreach ($def['docs'] as $dk => $dmeta) {
                if (array_key_exists($dk, $payload['docs'])) {
                    $val = trim((string)$payload['docs'][$dk]);
                    $stepDocs[$dk] = $val;
                    if ($val !== '' && $firstDocLink === null) {
                        $firstDocLink = $val;
                    }
                }
            }
        }

        $catatan = isset($payload['catatan']) ? trim((string)$payload['catatan']) : ($existing ? $existing->catatan : null);

        // Waktu selesai aktual (bisa input manual tanggal/jam, default $now saat status selesai)
        $completedAt = $existing ? $existing->completed_at : null;
        if (!empty($payload['completed_at'])) {
            $ts = strtotime(str_replace('T', ' ', trim((string)$payload['completed_at'])));
            if ($ts && $ts > 0) {
                $completedAt = date('Y-m-d H:i:s', $ts);
            }
        }
        if ($status === 'selesai' && empty($completedAt)) {
            $completedAt = $now;
        } elseif ($status === 'belum') {
            $completedAt = null;
        }

        $stepRecord = [
            'status'       => $status,
            'data_json'    => json_encode($stepData, JSON_UNESCAPED_UNICODE),
            'docs_json'    => json_encode($stepDocs, JSON_UNESCAPED_UNICODE),
            'catatan'      => $catatan !== '' ? $catatan : null,
            'completed_at' => $completedAt,
            'updated_by'   => $user_id,
            'updated_at'   => $now
        ];

        $this->db->trans_begin();

        if ($existing) {
            $this->db->where('id', $existing->id)->update('tracking_import_steps', $stepRecord);
        } else {
            $stepRecord['id_import'] = (int)$id_import;
            $stepRecord['stage']     = (int)$stage;
            $this->db->insert('tracking_import_steps', $stepRecord);
        }

        // Sinkronisasi kolom ringkasan ke header tracking_import
        $headerUpd = ['updated_at' => $now];

        if ($stage === 2) {
            if (isset($stepData['hs_code'])) $headerUpd['hs_code'] = $stepData['hs_code'] ?: null;
            if (isset($stepData['ada_lartas'])) $headerUpd['ada_lartas'] = $stepData['ada_lartas'] ?: null;
            if (isset($stepData['keterangan_lartas'])) $headerUpd['keterangan_lartas'] = $stepData['keterangan_lartas'] ?: null;

            // Jika Tidak Ada Lartas -> otomatis set Tahap 9 menjadi N/A
            if (($stepData['ada_lartas'] ?? '') === 'tidak') {
                $st9 = $this->db->get_where('tracking_import_steps', ['id_import' => (int)$id_import, 'stage' => 9])->row();
                $st9Data = $st9 && !empty($st9->data_json) ? (json_decode($st9->data_json, true) ?: []) : [];
                $st9Data['status_dokumen_lartas'] = 'Tidak Ada Lartas (N/A)';
                $st9Rec = [
                    'status'       => 'na',
                    'data_json'    => json_encode($st9Data, JSON_UNESCAPED_UNICODE),
                    'catatan'      => 'Otomatis N/A karena Tahap 2 ditandai Tidak Ada Lartas.',
                    'completed_at' => $now,
                    'updated_by'   => $user_id,
                    'updated_at'   => $now
                ];
                if ($st9) {
                    $this->db->where('id', $st9->id)->update('tracking_import_steps', $st9Rec);
                } else {
                    $st9Rec['id_import'] = (int)$id_import;
                    $st9Rec['stage']     = 9;
                    $this->db->insert('tracking_import_steps', $st9Rec);
                }
            } elseif (($stepData['ada_lartas'] ?? '') === 'ada') {
                // Jika sebelumnya N/A, kembalikan ke belum agar bisa diproses
                $st9 = $this->db->get_where('tracking_import_steps', ['id_import' => (int)$id_import, 'stage' => 9])->row();
                if ($st9 && $st9->status === 'na') {
                    $this->db->where('id', $st9->id)->update('tracking_import_steps', [
                        'status'       => 'belum',
                        'completed_at' => null,
                        'updated_by'   => $user_id,
                        'updated_at'   => $now
                    ]);
                }
            }
        } elseif ($stage === 7) {
            if (!empty($stepData['no_bl_awb'])) $headerUpd['no_bl_awb'] = $stepData['no_bl_awb'];
        } elseif ($stage === 8) {
            if (!empty($stepData['no_pib'])) $headerUpd['no_pib'] = $stepData['no_pib'];
        } elseif ($stage === 10) {
            if (isset($stepData['nominal_pajak_import']) && $stepData['nominal_pajak_import'] !== '') {
                $clean = preg_replace('/[^0-9.]/', '', str_replace(',', '', $stepData['nominal_pajak_import']));
                $headerUpd['nominal_pajak_import'] = (float)$clean;
            }
            if (!empty($stepDocs['doc_bukti_bayar_pajak'])) {
                $headerUpd['bukti_bayar_pajak'] = $stepDocs['doc_bukti_bayar_pajak'];
            }
        } elseif ($stage === 11) {
            if (!empty($stepData['tgl_siap_jemput'])) {
                $headerUpd['tgl_siap_jemput'] = $stepData['tgl_siap_jemput'];
            }
        } elseif ($stage === 12) {
            if (!empty($stepData['ekspedisi_domestik'])) $headerUpd['nama_ekspedisi_domestik'] = $stepData['ekspedisi_domestik'];
            if (!empty($stepData['no_resi_domestik'])) $headerUpd['no_resi_domestik'] = $stepData['no_resi_domestik'];
            if (!empty($stepData['nama_gudang_tujuan'])) $headerUpd['nama_gudang_tujuan'] = $stepData['nama_gudang_tujuan'];
            if (!empty($stepDocs['doc_bukti_terima_wh'])) $headerUpd['bukti_terima_gudang'] = $stepDocs['doc_bukti_terima_wh'];
            if ($status === 'selesai') {
                $headerUpd['waktu_9_sampai_gudang'] = $completedAt ?: $now;
            }
        } elseif ($stage === 13) {
            if (!empty($stepData['tgl_jatuh_tempo_forwarder'])) {
                $headerUpd['tgl_jatuh_tempo_forwarder'] = $stepData['tgl_jatuh_tempo_forwarder'];
            }
        }

        // Hitung ulang current_stage (tahap tertinggi yang berstatus proses/selesai)
        $allSteps = $this->db->where('id_import', (int)$id_import)->get('tracking_import_steps')->result();
        $highest = 1;
        foreach ($allSteps as $sRow) {
            if (in_array($sRow->status, ['proses', 'selesai'], true) && (int)$sRow->stage > $highest) {
                $highest = (int)$sRow->stage;
            }
        }
        $headerUpd['current_stage'] = $highest;

        $this->db->where('id_import', (int)$id_import)->update('tracking_import', $headerUpd);

        // Catat log aktivitas
        $statusText = strtoupper($status);
        $logCatatan = $catatan !== '' ? ("[$statusText] " . $catatan) : ("Update status tahap menjadi: " . $statusText);
        $this->db->insert('tracking_import_log', [
            'id_import'       => (int)$id_import,
            'stage'           => (int)$stage,
            'stage_label'     => $def['label'],
            'waktu_milestone' => $completedAt ?: $now,
            'catatan'         => $logCatatan,
            'bukti_link'      => $firstDocLink,
            'id_pengguna'     => $user_id,
            'created_at'      => $now
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['ok' => false, 'msg' => 'Gagal menyimpan perubahan tahapan import!'];
        }

        $this->db->trans_commit();
        return ['ok' => true, 'msg' => 'Tahap "' . $def['label'] . '" berhasil disimpan!'];
    }

    public function getAllTrackingImport($filter_gudang = null, $filter_phase = null)
    {
        $this->syncAutomaticImportFromExistingData();

        $this->db->select('ti.*, u.nama as nama_pembuat');
        $this->db->from('tracking_import ti');
        $this->db->join('pengguna u', 'ti.created_by = u.pengguna_id', 'left');
        $this->db->where('ti.stat', 1);

        if (!empty($filter_gudang)) {
            $this->db->where('ti.nama_gudang_tujuan', $filter_gudang);
        }

        $this->db->order_by('ti.id_import', 'DESC');
        $rows = $this->db->get()->result();

        if (!empty($filter_phase) && !empty($rows)) {
            $ids = array_map(function ($r) { return (int)$r->id_import; }, $rows);
            $stepsGrouped = $this->getAllImportStepsGrouped($ids);
            $filtered = [];
            foreach ($rows as $r) {
                $m = $this->resolveImportMetrics($r, $stepsGrouped[(int)$r->id_import] ?? []);
                if ((string)$m['active_phase'] === (string)$filter_phase) {
                    $filtered[] = $r;
                }
            }
            return $filtered;
        }

        return $rows;
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
        $this->db->order_by('l.created_at', 'DESC');
        $this->db->order_by('l.id', 'DESC');
        return $this->db->get()->result();
    }

    public function resolveImportMetrics($row, $stepsMap = null)
    {
        $defs   = $this->getImportStageDefinitions();
        $phases = $this->getImportPhaseDefinitions();

        if ($stepsMap === null) {
            $stepsMap = $this->getImportStepsMap($row->id_import);
        }

        $completedCount = 0;
        $inProgressCount = 0;
        $stepStatuses = [];
        $times = [];
        $activeStage = 1;
        $firstIncompleteStage = null;

        for ($s = 1; $s <= 14; $s++) {
            $stObj = $stepsMap[$s] ?? null;
            $status = $stObj ? ($stObj->status ?: 'belum') : 'belum';
            $compAt = ($stObj && !empty($stObj->completed_at) && strpos((string)$stObj->completed_at, '0000-00-00') === false)
                ? $stObj->completed_at : null;

            // Fallback untuk data lama sebelum migrasi steps
            if ($s === 1 && $status === 'belum' && !empty($row->waktu_1_po_manufacture)) {
                $status = 'selesai';
                $compAt = $row->waktu_1_po_manufacture;
            }
            if ($s === 9 && $status === 'belum' && ($row->ada_lartas ?? '') === 'tidak') {
                $status = 'na';
            }

            $stepStatuses[$s] = $status;
            $times[$s] = $compAt;

            if ($status === 'selesai' || $status === 'na') {
                $completedCount++;
            } else {
                if ($status === 'proses') {
                    $inProgressCount++;
                }
                if ($firstIncompleteStage === null) {
                    $firstIncompleteStage = $s;
                }
            }
        }

        if ($firstIncompleteStage !== null) {
            $activeStage = $firstIncompleteStage;
        } else {
            $activeStage = 14; // Semua selesai
        }

        $activePhase = $defs[$activeStage]['phase'] ?? 1;
        $isAllDone   = ($completedCount >= 14);

        // Ringkasan per fase (1..5)
        $phaseStats = [];
        foreach ($phases as $pNum => $pDef) {
            $pTotal = count($pDef['stages']);
            $pDone  = 0;
            $pProc  = 0;
            foreach ($pDef['stages'] as $stNum) {
                if (in_array($stepStatuses[$stNum], ['selesai', 'na'], true)) {
                    $pDone++;
                } elseif ($stepStatuses[$stNum] === 'proses') {
                    $pProc++;
                }
            }
            $phaseStats[$pNum] = [
                'done'  => $pDone,
                'total' => $pTotal,
                'pct'   => (int)round(($pDone / $pTotal) * 100),
                'state' => ($pDone === $pTotal) ? 'selesai' : (($pDone > 0 || $pProc > 0) ? 'proses' : 'belum')
            ];
        }

        // Hitung Lead Time aktual (tanpa interpolasi fiktif)
        $calcDur = function ($start, $end) {
            if (empty($start) || empty($end)) {
                return ['valid' => false, 'text' => '-'];
            }
            if (function_exists('formatApprovalDurationInfo')) {
                return formatApprovalDurationInfo($start, $end, false);
            }
            $diff = strtotime($end) - strtotime($start);
            if ($diff < 0) return ['valid' => false, 'text' => '-'];
            $days = floor($diff / 86400);
            $hrs  = floor(($diff % 86400) / 3600);
            return ['valid' => true, 'text' => ($days > 0 ? "{$days} hr " : '') . "{$hrs} jam"];
        };

        $t1  = $times[1] ?: ($row->waktu_1_po_manufacture ?: $row->created_at);
        $t12 = $times[12] ?: (!empty($row->waktu_9_sampai_gudang) ? $row->waktu_9_sampai_gudang : null);
        $t14 = $times[14];

        $durToWarehouse = $calcDur($t1, $t12);
        $durEndToEnd    = $calcDur($t1, $t14);

        // Status Jatuh Tempo Invoice Forwarder (Tahap 13 vs Tahap 14)
        $jtBadge = [
            'has_date'   => false,
            'label'      => 'Belum Diisi',
            'badge_bg'   => '#f1f5f9',
            'badge_text' => '#64748b',
            'badge_bdr'  => '#cbd5e1',
            'icon'       => 'fa-calendar'
        ];
        if (!empty($row->tgl_jatuh_tempo_forwarder) && strpos((string)$row->tgl_jatuh_tempo_forwarder, '0000-00-00') === false) {
            $jtBadge['has_date'] = true;
            if ($stepStatuses[14] === 'selesai') {
                $jtBadge['label']      = 'Lunas (' . date('d/m/Y', strtotime($row->tgl_jatuh_tempo_forwarder)) . ')';
                $jtBadge['badge_bg']   = '#dcfce7';
                $jtBadge['badge_text'] = '#166534';
                $jtBadge['badge_bdr']  = '#86efac';
                $jtBadge['icon']       = 'fa-check-circle';
            } else {
                $diffJt = (int)floor((strtotime($row->tgl_jatuh_tempo_forwarder) - strtotime(date('Y-m-d'))) / 86400);
                if ($diffJt < 0) {
                    $jtBadge['label']      = 'Overdue ' . abs($diffJt) . ' Hr (' . date('d/m/Y', strtotime($row->tgl_jatuh_tempo_forwarder)) . ')';
                    $jtBadge['badge_bg']   = '#fee2e2';
                    $jtBadge['badge_text'] = '#991b1b';
                    $jtBadge['badge_bdr']  = '#fca5a5';
                    $jtBadge['icon']       = 'fa-exclamation-triangle';
                } elseif ($diffJt <= 3) {
                    $jtBadge['label']      = 'Jatuh Tempo H-' . $diffJt . ' (' . date('d/m/Y', strtotime($row->tgl_jatuh_tempo_forwarder)) . ')';
                    $jtBadge['badge_bg']   = '#fef3c7';
                    $jtBadge['badge_text'] = '#92400e';
                    $jtBadge['badge_bdr']  = '#fcd34d';
                    $jtBadge['icon']       = 'fa-clock';
                } else {
                    $jtBadge['label']      = 'JT: ' . date('d/m/Y', strtotime($row->tgl_jatuh_tempo_forwarder)) . ' (H-' . $diffJt . ')';
                    $jtBadge['badge_bg']   = '#e0f2fe';
                    $jtBadge['badge_text'] = '#0369a1';
                    $jtBadge['badge_bdr']  = '#bae6fd';
                    $jtBadge['icon']       = 'fa-calendar-alt';
                }
            }
        }

        return [
            'times'            => $times,
            'step_statuses'    => $stepStatuses,
            'phase_stats'      => $phaseStats,
            'active_stage'     => $activeStage,
            'active_phase'     => $activePhase,
            'is_all_done'      => $isAllDone,
            'completed_cnt'    => $completedCount,
            'in_progress_cnt'  => $inProgressCount,
            'progress_pct'     => (int)round(($completedCount / 14) * 100),
            'dur_to_warehouse' => $durToWarehouse,
            'dur_end_to_end'   => $durEndToEnd,
            'jatuh_tempo'      => $jtBadge
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
