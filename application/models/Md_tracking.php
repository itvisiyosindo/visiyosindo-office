<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_tracking extends CI_Model
{

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
            t.tgl_sampai,
            t.no_sj,
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
                    pb.perusahaan
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
            ps.id_gudang_tujuan,
            g2.nama_gudang as gudang_tujuan,
            g2.alamat_gudang as alamat_gudang_tujuan,
            stb.kode_stb,
            stb.kota_pengajuan,
            stb.tgl_pengajuan as tgl_pengajuan_stb,
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
            kd.ekspedisi as ekspedisi_kirim
        ')
            ->from('tracking_barang t')
            ->join('gudang g', 't.id_gudang=g.id_gudang', 'LEFT')
            ->join('customer c', 't.id_customer=c.id_customer', 'LEFT')
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
                t.biaya,
                t.pic_penerima,
                t.no_sj,
                t.no_resi,
                t.keterangan,
                t.stat,
                t.created_at AS createdAt,
                g.nama_gudang,
                c.nama_customer,
                ts.id_tracking,
                ts.id_status,
                ts.nama_penerima,
                ts.tgl_penerima,
                ts.bukti_penerima,
                ts.created_at AS statusCreatedAt
            ')
            ->from('tracking_barang t')
            ->join('gudang g', 't.id_gudang = g.id_gudang')
            ->join('customer c', 't.id_customer = c.id_customer')
            ->join('tracking_status ts', 't.id_tracking = ts.id_tracking')
            ->join("($subquery) latest_ts", 'ts.id_tracking = latest_ts.id_tracking AND ts.created_at = latest_ts.latest_created_at')
            ->where('t.stat', 1)
            ->where('t.created_at >=', date('Y-m-d', strtotime($tglawal)))
            ->where('t.created_at <=', date('Y-m-d', strtotime($tglakhir)))
            ->order_by('t.id_tracking', 'DESC')
            ->get()
            ->result();
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
}
