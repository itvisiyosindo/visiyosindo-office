<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_funnel extends CI_Model
{
    public function getAllFunnelOptimized($restrict_id = NULL, $filters = []) {
        // Subquery untuk mengambil data update terakhir (Status Realisasi Terbaru)
        $latestUpdateJoin = "(
            SELECT a.idfunnel, a.tanggal as tglrealisasi, msf.nama as namarealisasi 
            FROM funnelupdate a 
            INNER JOIN masterstatusfunnel msf ON a.realisasi = msf.id 
            WHERE a.id IN (SELECT MAX(id) FROM funnelupdate WHERE deleted = 0 GROUP BY idfunnel)
        ) as topfunnel";

        $this->datatables->select('
            funnel.id,
            calonpelanggan.namacaloncustomer,
            CONCAT(provinsi.nama, " - ", kota.nama) as provinsikota,
            c.nama as namamarketing,
            funnel.jenispekerjaan,
            msf_main.nama as namastatus,
            kategori_barang.nama_kategori,
            barang.nama_barang,
            topfunnel.namarealisasi AS Realisasi,
            mpkf.nama as namaprogress,
            d.nama as diajukanoleh,
            funnel.tanggal,
            topfunnel.tglrealisasi,
            (SELECT COUNT(*) FROM funnelupdate WHERE idfunnel = funnel.id AND deleted = 0) as histori_count
        ');

        $this->datatables->from('funnel');
        $this->datatables->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id');
        $this->datatables->join('barang', 'funnel.idproduct = barang.id_barang');
        $this->datatables->join('kategori_barang', 'kategori_barang.id_kategori = barang.id_kategori');
        $this->datatables->join('masterstatusfunnel msf_main', 'funnel.statusfunnel = msf_main.id');
        $this->datatables->join('pengguna d', 'd.pengguna_id = funnel.pengguna_id');
        $this->datatables->join('pengguna c', 'c.pengguna_id = calonpelanggan.pengguna_id');
        $this->datatables->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode', 'left');
        $this->datatables->join('kota', 'calonpelanggan.kota = kota.id', 'left');
        $this->datatables->join('masterprogresskerjafunnel mpkf', 'funnel.progressfunnel = mpkf.id', 'left');
        $this->datatables->join($latestUpdateJoin, 'topfunnel.idfunnel = funnel.id', 'left');

        $this->datatables->where('funnel.deleted', 0);

        // --- FILTER LOGIC ---
        if (!empty($filters['idmarketing'])) {
            $this->datatables->where('funnel.idmarketing', $filters['idmarketing']);
        }
        if (!empty($filters['tgl_awal'])) {
            $this->datatables->where('funnel.tanggal >=', $filters['tgl_awal']);
        }
        if (!empty($filters['tgl_akhir'])) {
            $this->datatables->where('funnel.tanggal <=', $filters['tgl_akhir']);
        }

        // Restriction berdasarkan login (Jika bukan admin)
        if ($restrict_id !== NULL) {
            $this->datatables->where('funnel.pengguna_id', $restrict_id);
        }

        return $this->datatables->generate();
    }
    
    public function get_jumlah_id_per_bulan($tahun)
    {
        $this->db->select('MONTH(data_created) AS bulan, idmarketing, COUNT(id) AS jumlah_id');
        $this->db->from('calonpelanggan');
        $this->db->where('YEAR(data_created)', $tahun); 
        $this->db->where_in('idmarketing', [105, 742, 745]);
        $this->db->group_by(['MONTH(data_created)', 'idmarketing']);
        $this->db->order_by('idmarketing, bulan');

        return $this->db->get()->result();
    }



    public function get_funnel_id_per_bulan($tahun)
    {
        $this->db->select('MONTH(data_created) AS bulan, idmarketing, COUNT(id) AS jumlah_id');
        $this->db->from('funnel');
        $this->db->where('YEAR(data_created)', $tahun);
        $this->db->where_in('idmarketing', [105, 742, 745]);
        $this->db->group_by(['MONTH(data_created)', 'idmarketing']);
        $this->db->order_by('idmarketing, bulan');
        
        return $this->db->get()->result();
    }

    public function get_fpp_id_per_bulan($tahun)
    {
        $this->db->select('MONTH(created_at) AS bulan, id_pengaju, COUNT(id) AS jumlah_id');
        $this->db->from('fpp');
        $this->db->where('YEAR(created_at)', $tahun); 
        $this->db->where('status != 3'); // Data yang ditolak di takeout
        $this->db->where_in('id_pengaju', [105, 742, 745]);
        $this->db->group_by(['MONTH(created_at)', 'id_pengaju']);
        $this->db->order_by('id_pengaju, bulan');
        
        return $this->db->get()->result();
    }





     function countCalonPelanggan()
    {
            $this->db->select('count(*) as total')->where('deleted',0);
            return $this->db->get('calonpelanggan')->result();
    }
    function countPelanggan()
    {
            $this->db->select('count(*) as total')->where('deleted',0);
            return $this->db->get('pelangganan')->result();
    }
    function countFunnel()
    {
            $this->db->select('count(*) as total')->where('deleted',0);
            return $this->db->get('funnel')->result();
    }
    function add($data)
    {
        $this->db->insert('funnel', $data);
    }
   function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('funnel', $data);
    }
    function hapus($id,$pengguna_id)
    {
        $data = array(
            'deleted' => 1,
            'pengguna_id' => $pengguna_id
        );
        $this->db->where('id', $id);
        $this->db->update('funnel', $data);
    }
     function hapusupdate($id,$pengguna_id)
    {
        $data = array(
            'deleted' => 1,
            'pengguna_id' => $pengguna_id
        );
        $this->db->where('id', $id);
        $this->db->update('funnelupdate', $data);
    }
    
    function addkomp($data)
    {
        $this->db->insert('funnelkompetitor', $data);
    }
    function updatefunnel($data)
    {
        $this->db->insert('funnelupdate', $data);
    }
    function updateprogressfunnel($data)
    {
        $this->db->set('progressfunnel', $data['progressfunnel'], FALSE);
        $this->db->where('id', $data['idfunnel']);
        $this->db->update('funnel'); 
        
    }
    function addkompetitor($data1){
        $this->db->insert_batch('funnelkompetitor', $data1);
    }
    function updateanalisa($data)
    {
        $this->db->set('hasilanalisa', $data['hasilanalisa']);
        $this->db->where('funnelupdate.id', $data['id']);
        $this->db->update('funnelupdate'); 
    }

    function getAllFunnelBeta()
    {
        $this->db->order_by('funnel.id', 'desc');
        return $this->datatables
            ->select('
                funnel.id,
                c.nama as namamarketing,
                (SELECT COUNT(*) FROM funnelupdate WHERE funnelupdate.idfunnel=funnel.id and funnelupdate.deleted=0) AS histori,
                calonpelanggan.namacaloncustomer,
                funnel.jenispekerjaan,
                masterstatusfunnel.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                masterprogresskerjafunnel.nama  as namaprogress,
                funnel.keterangan,
                funnel.kendala,
                d.nama as diajukanoleh,
                funnel.tanggal,
                (SELECT fu.tanggal 
                FROM funnelupdate fu 
                WHERE fu.idfunnel = funnel.id AND fu.deleted = 0 
                ORDER BY fu.tanggal DESC 
                LIMIT 1) AS tglrealisasi,
                (SELECT msf.nama 
                FROM funnelupdate fu 
                JOIN masterstatusfunnel msf ON fu.realisasi = msf.id 
                WHERE fu.idfunnel = funnel.id AND fu.deleted = 0 
                ORDER BY fu.tanggal DESC 
                LIMIT 1) AS Realisasi
            ')
            ->from('funnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang', 'funnel.idproduct = barang.id_barang')
            ->join('kategori_barang', 'kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnel.progressfunnel = masterprogresskerjafunnel.id', 'left')
            ->join('masterstatusfunnel', 'funnel.statusfunnel = masterstatusfunnel.id')
            ->join('pengguna d', 'd.pengguna_id = funnel.pengguna_id')
            ->join('pengguna c', 'c.pengguna_id = calonpelanggan.pengguna_id')
            ->where('funnel.deleted', 0)
            ->generate();
    }


    function getAllFunnelBetaByPenggunaID($pengguna_id)
    {
        $this->db->order_by('funnel.id', 'desc');
        return $this->datatables
            ->select('
                funnel.id,
                c.nama as namamarketing,
                (SELECT COUNT(*) FROM funnelupdate WHERE funnelupdate.idfunnel=funnel.id and funnelupdate.deleted=0) AS histori,
                calonpelanggan.namacaloncustomer,
                funnel.jenispekerjaan,
                masterstatusfunnel.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                masterprogresskerjafunnel.nama  as namaprogress,
                funnel.keterangan,
                funnel.kendala,
                d.nama as diajukanoleh,
                funnel.tanggal,
                (SELECT fu.tanggal 
                FROM funnelupdate fu 
                WHERE fu.idfunnel = funnel.id AND fu.deleted = 0 
                ORDER BY fu.tanggal DESC 
                LIMIT 1) AS tglrealisasi,
                (SELECT msf.nama 
                FROM funnelupdate fu 
                JOIN masterstatusfunnel msf ON fu.realisasi = msf.id 
                WHERE fu.idfunnel = funnel.id AND fu.deleted = 0 
                ORDER BY fu.tanggal DESC 
                LIMIT 1) AS Realisasi
            ')
            ->from('funnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang', 'funnel.idproduct = barang.id_barang')
            ->join('kategori_barang', 'kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnel.progressfunnel = masterprogresskerjafunnel.id', 'left')
            ->join('masterstatusfunnel', 'funnel.statusfunnel = masterstatusfunnel.id')
            ->join('pengguna d', 'd.pengguna_id = funnel.pengguna_id')
            ->join('pengguna c', 'c.pengguna_id = calonpelanggan.pengguna_id')
            ->where('funnel.pengguna_id',$pengguna_id)
            ->where('funnel.deleted',0)
            ->generate();
    }

  
    function getAllFunnel()
    {
        $this->db->order_by('funnel.id', 'desc');
        return $this->datatables
             ->select('
				funnel.id,
				c.nama as namamarketing,
                (SELECT COUNT(*) FROM funnelupdate WHERE funnelupdate.idfunnel=funnel.id and funnelupdate.deleted=0) AS histori,
                calonpelanggan.namacaloncustomer,
                (CONCAT(provinsi.nama,\' - \',kota.tipe,\' \',kota.nama)) as provinsikota,
                funnel.jenispekerjaan,
                masterstatusfunnel.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                topfunnel.nama AS Realisasi,
                masterprogresskerjafunnel.nama  as namaprogress,
                funnel.keterangan,
                funnel.kendala,
                d.nama as diajukanoleh,
                funnel.tanggal, 
                topfunnel.tanggal as tglrealisasi
            ')
            ->from('funnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang','funnel.idproduct = barang.id_barang')
            ->join('kategori_barang','kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnel.progressfunnel = masterprogresskerjafunnel.id', 'left')
            ->join('masterstatusfunnel', 'funnel.statusfunnel = masterstatusfunnel.id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->join('pengguna d','d.pengguna_id=funnel.pengguna_id')
            ->join('pengguna c','c.pengguna_id=calonpelanggan.pengguna_id')
            ->join('(SELECT a.id,a.tanggal,a.idfunnel,masterstatusfunnel.nama FROM funnelupdate a INNER JOIN masterstatusfunnel ON a.realisasi= masterstatusfunnel.id WHERE a.id in (SELECT MAX(b.id) FROM funnelupdate b GROUP BY b.idfunnel)) topfunnel','topfunnel.idfunnel=funnel.id','left')
            ->where('funnel.deleted',0)
            ->generate();
    }



    function getAllFunnelByPenggunaID($pengguna_id)
    {
        $this->db->order_by('funnel.id', 'desc');
        return $this->datatables
             ->select('
				funnel.id,
				c.nama as namamarketing,
				funnel.tanggal,
                (SELECT COUNT(*) FROM funnelupdate WHERE funnelupdate.idfunnel=funnel.id and funnelupdate.deleted=0) AS histori,
                calonpelanggan.namacaloncustomer,
                (CONCAT(provinsi.nama,\' - \',kota.tipe,\' \',kota.nama)) as provinsikota,
                funnel.jenispekerjaan,
                masterstatusfunnel.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                topfunnel.tanggal as tglrealisasi,
                topfunnel.nama AS Realisasi,
                masterprogresskerjafunnel.nama  as namaprogress,
                funnel.keterangan,
                funnel.kendala,
                d.nama as diajukanoleh,
                funnel.data_created
            ')
            ->from('funnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang','funnel.idproduct = barang.id_barang')
            ->join('kategori_barang','kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnel.progressfunnel = masterprogresskerjafunnel.id', 'left')
            ->join('masterstatusfunnel', 'funnel.statusfunnel = masterstatusfunnel.id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->join('pengguna d','d.pengguna_id=funnel.pengguna_id')
            ->join('pengguna c','c.pengguna_id=calonpelanggan.pengguna_id')
            ->join('(SELECT a.id,a.idfunnel,a.tanggal,masterstatusfunnel.nama FROM funnelupdate a INNER JOIN masterstatusfunnel ON a.realisasi= masterstatusfunnel.id WHERE a.id in (SELECT MAX(b.id) FROM funnelupdate b GROUP BY b.idfunnel)) topfunnel','topfunnel.idfunnel=funnel.id','left')
            ->where('funnel.pengguna_id',$pengguna_id)
            ->where('funnel.deleted',0)
            ->generate();
    }

     function getAllLaporanFunnelByPenggunaIDOLD($pengguna_id,$tglawal,$tglakhir)
    {
        $this->db->order_by('funnel.tanggal', 'asc');
        return $this->db
             ->select('
				funnel.id,
				c.nama as namamarketing,
                (SELECT COUNT(*) FROM funnelupdate WHERE funnelupdate.idfunnel=funnel.id) AS histori,
                calonpelanggan.namacaloncustomer,
                (CONCAT(provinsi.nama,\' - \',kota.tipe,\' \',kota.nama)) as provinsikota,
                funnel.jenispekerjaan,
                funnel.tujuankegiatan,
                calonpelanggan.tipecustomer,
                (SELECT GROUP_CONCAT(CONCAT(namapic,\' (\',teleponpic,\')\') SEPARATOR \' - \') AS pic FROM calonpelangganpic WHERE idpic=funnel.idpelanggan) AS pic,
                masterstatusfunnel.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                (SELECT GROUP_CONCAT(CONCAT(namakompetitor,\' (\',produkkompetitor,\'-\',hargakompetitor,\')\') SEPARATOR \' - \') AS komp FROM funnelkompetitor WHERE idfunnel=funnel.id) AS kompetitor,
                topfunnel.tanggal as tglrealisasi,
                topfunnel.realisasiupdate AS realisasi,
                topfunnel.statusfunnelupdate AS statusfunnel,
                masterprogresskerjafunnel.nama  as namaprogress,
                (CASE WHEN (topfunnel.peluangkeberhasilan=0) THEN \'\' ELSE topfunnel.peluangkeberhasilan END) AS peluangkeberhasilan,
                (SELECT GROUP_CONCAT(CONCAT(merk,\' (\',nama,\')\') SEPARATOR \' - \') AS modality FROM pelanggananmodality WHERE id_pelanggan=funnel.idpelanggan) AS modality,
                topfunnel.keterangan,
                topfunnel.kendala,
                topfunnel.hasilanalisa,
                topfunnel.file_funnel,
                d.nama as diajukanoleh,
                funnel.tanggal
            ')
            ->from('funnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang','funnel.idproduct = barang.id_barang')
            ->join('kategori_barang','kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnel.progressfunnel = masterprogresskerjafunnel.id', 'left')
            ->join('masterstatusfunnel', 'funnel.statusfunnel = masterstatusfunnel.id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->join('pengguna d','d.pengguna_id=funnel.pengguna_id')
            ->join('pengguna c','c.pengguna_id=calonpelanggan.pengguna_id')
            ->join('(SELECT a.id,a.idfunnel,a.tanggal,B.nama AS realisasiupdate, C.nama AS statusfunnelupdate, a.peluangkeberhasilan, a.keterangan, a.kendala, a.hasilanalisa, a.file_funnel FROM funnelupdate a INNER JOIN masterstatusfunnel B ON a.realisasi= B.id INNER JOIN masterstatusfunnel C ON a.statusfunnel= C.id WHERE a.id in (SELECT MAX(b.id) FROM funnelupdate b GROUP BY b.idfunnel)) topfunnel','topfunnel.idfunnel=funnel.id','left')
            ->where('funnel.idmarketing',$pengguna_id)
            ->where('CONVERT(funnel.tanggal, DATE) BETWEEN "'. date('Y-m-d', strtotime($tglawal)). '" and "'. date('Y-m-d', strtotime($tglakhir)).'"')
            ->where('funnel.deleted',0)
            ->get()
            ->result();
    }

    
    function getAllLaporanFunnelByPenggunaID($pengguna_id = NULL, $tglawal, $tglakhir)
    {
        $this->db->order_by('funnel.tanggal', 'asc');
        
        $query = $this->db
            ->select('
                funnel.id,
                c.nama as namamarketing,
                (SELECT COUNT(*) FROM funnelupdate WHERE funnelupdate.idfunnel=funnel.id) AS histori,
                calonpelanggan.namacaloncustomer,
                (CONCAT(provinsi.nama,\' - \',kota.tipe,\' \',kota.nama)) as provinsikota,
                funnel.jenispekerjaan,
                funnel.tujuankegiatan,
                calonpelanggan.tipecustomer,
                (SELECT GROUP_CONCAT(CONCAT(namapic,\' (\',teleponpic,\')\') SEPARATOR \' - \') 
                    FROM calonpelangganpic 
                    WHERE idpic=funnel.idpelanggan) AS pic,
                masterstatusfunnel.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                (SELECT GROUP_CONCAT(CONCAT(namakompetitor,\' (\',produkkompetitor,\'-\',hargakompetitor,\')\') SEPARATOR \' - \') 
                    FROM funnelkompetitor 
                    WHERE idfunnel=funnel.id) AS kompetitor,
                topfunnel.tanggal as tglrealisasi,
                topfunnel.realisasiupdate AS realisasi,
                topfunnel.statusfunnelupdate AS statusfunnel,
                masterprogresskerjafunnel.nama  as namaprogress,
                (CASE WHEN (topfunnel.peluangkeberhasilan=0) 
                    THEN \'\' ELSE topfunnel.peluangkeberhasilan END) AS peluangkeberhasilan,
                (SELECT GROUP_CONCAT(CONCAT(merk,\' (\',nama,\')\') SEPARATOR \' - \') 
                    FROM pelanggananmodality 
                    WHERE id_pelanggan=funnel.idpelanggan) AS modality,
                topfunnel.keterangan,
                topfunnel.kendala,
                topfunnel.hasilanalisa,
                topfunnel.file_funnel,
                d.nama as diajukanoleh,
                funnel.tanggal
            ')
            ->from('funnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang','funnel.idproduct = barang.id_barang')
            ->join('kategori_barang','kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnel.progressfunnel = masterprogresskerjafunnel.id', 'left')
            ->join('masterstatusfunnel', 'funnel.statusfunnel = masterstatusfunnel.id')
            ->join('provinsi', 'calonpelanggan.provinsi = provinsi.kode')
            ->join('kota', 'calonpelanggan.kota = kota.id')
            ->join('pengguna d','d.pengguna_id=funnel.pengguna_id')
            ->join('pengguna c','c.pengguna_id=calonpelanggan.pengguna_id')
            ->join('(
                SELECT a.id,a.idfunnel,a.tanggal,
                    B.nama AS realisasiupdate, 
                    C.nama AS statusfunnelupdate, 
                    a.peluangkeberhasilan, 
                    a.keterangan, 
                    a.kendala, 
                    a.hasilanalisa, 
                    a.file_funnel 
                FROM funnelupdate a 
                INNER JOIN masterstatusfunnel B ON a.realisasi= B.id 
                INNER JOIN masterstatusfunnel C ON a.statusfunnel= C.id 
                WHERE a.id in (SELECT MAX(b.id) FROM funnelupdate b GROUP BY b.idfunnel)
            ) topfunnel','topfunnel.idfunnel=funnel.id','left')
            ->where('CONVERT(funnel.tanggal, DATE) >=', date('Y-m-d', strtotime($tglawal)))
            ->where('CONVERT(funnel.tanggal, DATE) <=', date('Y-m-d', strtotime($tglakhir)))
            ->where('funnel.deleted', 0);

        // filter pengguna jika ada
        if ($pengguna_id != NULL) {
            $query->where('funnel.idmarketing', $pengguna_id);
        }

        return $query->get()->result();
    }


    function getAllFunnelById($id)
    {
        $where = array('funnel.id' => $id);    
        return $this->db
             ->select('
				funnel.id,
                funnel.tanggal,
                calonpelanggan.namacaloncustomer,
                funnel.statusfunnel as idstatusfunnel,
                masterstatusfunnel.nama as namastatus,
                funnel.jenispekerjaan,
                funnel.tujuankegiatan,
                kategori_barang.id_kategori as idkategori,
                kategori_barang.nama_kategori,
                barang.id_barang as idproduct,
                barang.nama_barang,
                masterprogresskerjafunnel.nama as namaprogress,
                funnel.keterangan,
                funnel.kendala,
                funnel.data_created
            ')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang','funnel.idproduct = barang.id_barang')
            ->join('kategori_barang','kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnel.progressfunnel = masterprogresskerjafunnel.id', 'left')
            ->join('masterstatusfunnel', 'funnel.statusfunnel = masterstatusfunnel.id')
            ->get_where('funnel', $where)
            ->result();
    }
    function getAllFunnelDetailUpdateById($id)
    {
        $where = array('funnelupdate.id' => $id);    
        return $this->db
             ->select('
				funnelupdate.id,
				funnelupdate.idfunnel,
                calonpelanggan.namacaloncustomer,
                realisasifunnel.nama as realisasi,
                statusfunnel.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                masterprogresskerjafunnel.nama as namaprogress,
                funnelupdate.peluangkeberhasilan,
                funnelupdate.keterangan,
                funnelupdate.kendala,
                funnelupdate.hasilanalisa,
                funnelupdate.data_created
            ')
            ->join('funnel', 'funnel.id = funnelupdate.idfunnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('masterstatusfunnel realisasifunnel', 'funnelupdate.realisasi = realisasifunnel.id')
            ->join('masterstatusfunnel statusfunnel', 'funnelupdate.statusfunnel = statusfunnel.id')
            ->join('barang','funnel.idproduct = barang.id_barang')
            ->join('kategori_barang','kategori_barang.id_kategori = barang.id_kategori')
            ->join('masterprogresskerjafunnel', 'funnelupdate.progressfunnel = masterprogresskerjafunnel.id')
            ->get_where('funnelupdate', $where)
            ->result();
    }
    function getAllFunnelUpdateByIdFunnel($idfunnel)
    {
         $this->db->order_by('funnelupdate.id', 'DESC');
        return $this->datatables
             ->select('
				funnelupdate.id,
				funnelupdate.idfunnel,
                calonpelanggan.namacaloncustomer,
                funnelrealisasi.nama as realisasi,
                funnelstatus.nama as namastatus,
                kategori_barang.nama_kategori,
                barang.nama_barang,
                masterprogresskerjafunnel.nama as namaprogress,
                funnelupdate.peluangkeberhasilan,
                funnelupdate.keterangan,
                funnelupdate.kendala,
                funnelupdate.file_funnel,
                funnelupdate.hasilanalisa,
                funnelupdate.data_created
            ')
            ->from('funnel')
            ->join('calonpelanggan', 'funnel.idpelanggan = calonpelanggan.id')
            ->join('barang','funnel.idproduct = barang.id_barang')
            ->join('kategori_barang','kategori_barang.id_kategori = barang.id_kategori')
            ->join('funnelupdate', 'funnel.id = funnelupdate.idfunnel')
            ->join('masterprogresskerjafunnel', 'funnelupdate.progressfunnel = masterprogresskerjafunnel.id')
            ->join('masterstatusfunnel funnelstatus', 'funnelupdate.statusfunnel = funnelstatus.id')
            ->join('masterstatusfunnel funnelrealisasi', 'funnelupdate.realisasi = funnelrealisasi.id')
            ->where('funnelupdate.idfunnel', $idfunnel)
            ->where('funnelupdate.deleted', 0)
            ->generate();
    }
    function getmerkbyid($id)
    {
        $querys = "SELECT merk  FROM pelanggananmodality WHERE id=".$id;
        $merk = $this->db->query($querys)->row(0);
        if ($merk == null){
            return 0;
        }else{
            return $merk->merk;
        }

    }
     

    function getAllKompetitorFunnelById($id)
    {
        $where = array('funnelkompetitor.idfunnel' => $id);  
      
        return $this->db
           ->select('
                 funnelkompetitor.id,    
                (CONCAT(funnelkompetitor.namakompetitor,\' - \' ,funnelkompetitor.produkkompetitor,\' - \',funnelkompetitor.hargakompetitor)) as namakompetitor
            ') 
            ->get_where('funnelkompetitor', $where)
            ->result();
    }

    function getAllDataKompetitorFunnelById($id)
    {           
        return $this->datatables
           ->select('
                 funnelkompetitor.id,    
                 funnelkompetitor.namakompetitor,
                 funnelkompetitor.produkkompetitor,
                 funnelkompetitor.hargakompetitor
            ') 
            ->from('funnelkompetitor')
            ->where('funnelkompetitor.idfunnel',$id)
            ->generate();
    }

    function getMasterStatusFunnelByRencana($rencana)
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->select('*')->where('rencana',$rencana)->get('masterstatusfunnel')->result();
    }
    function getMasterProgressFunnel()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('masterprogresskerjafunnel')->result();
    }

    function getMasterKategoriFunnelRadiologi()
    {
        $this->db->order_by('nama_kategori', 'ASC');
        return $this->db->select('*')->where('status',1)->where('id_kategori not in (4,11,12,13,14,15,16,17)')->get('kategori_barang')->result();
    }
    function getTahunFunnelUpdate()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->select('distinct year(data_created) AS tahun')->get('funnelupdate')->result();
    }
    //  function getMasterNamaProductRadiologi()
    // {
    //     $this->db->order_by('a.id_kategori', 'ASC');
    //     $this->db->order_by('a.nama_barang', 'ASC');
    //     return $this->db
    //     ->select('b.id_kategori,b.nama_kategori,a.*')
    //     ->from('barang a')
    //     ->join('kategori_barang b','a.id_kategori=b.id_kategori')
    //     ->where('a.id_kategori not in (4,11,12,13,14,15,16,17)')
    //     ->result();
    // }
}
