<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_stock_opname extends CI_Model
{


    public function getBywhereActive()
	{
		$sql = 'SELECT * FROM pengguna p WHERE p.is_active = 1 ORDER BY p.nama ASC';
		$query = $this->db->query($sql);
		return $query->result(); // Mengembalikan hasil query dalam bentuk array objek
	}
			
    // fungsi reset urutan id pada tabel
	function reset_increment($tabel)
    {
		$this->db->query("ALTER TABLE ".$tabel." AUTO_INCREMENT = 1");
	}

    //GET
	function getStockKodeId()
    {
		return $this->db->select("COUNT(*) as id")->limit(1)->order_by('id',"DESC")->get_where('stock_opname',array('YEAR(`created_at`)' => date('Y')))->row();
	}

    function addStockOpname($data)
    {
        $this->db->insert('stock_opname', $data);
    }

    function addStockOpnameDetail($data)
    {
        $this->db->insert('stock_opname_detail', $data);
    }

    function updateSo($where, $data)
    {
        $this->db->where($where);
        $this->db->update('stock_opname', $data);
    }

    function getAllStockOpname()
    {
        $this->db->order_by('so.created_at', 'DESC');
        return $this->datatables
            ->select('
                so.id_so,
				so.kode,
                so.keterangan,
				so.tanggal_mulai,
				so.status,
                so.id_pengaju,
                so.created_at,
				g.nama_gudang,
                p.nama as nama_pelaksana,
                p2.nama as nama_pengaju
            ')
            ->from('stock_opname so')
            ->join('gudang g', 'so.id_gudang = g.id_gudang')
            ->join('pengguna p', 'so.id_pelaksana = p.pengguna_id')
            ->join('pengguna p2', 'so.id_pengaju = p2.pengguna_id')
            ->where('so.status != 9')
            ->generate();
    }


    function getAllSOpname()
    {
        $this->db->select('
                so.id_so,
				so.kode,
                so.keterangan,
				so.tanggal_mulai,
				so.status,
                so.created_at,
                so.ttd_1,
                so.ttd_2,
                so.catatan,
                so.link,
                so.id_pengaju,
                so.id_pelaksana,
				g.nama_gudang,
                p.nama as nama_pelaksana,
                p.jabatan as jabatan_pelaksana,
                p2.nama as nama_pengaju,
                p2.jabatan as jabatan_pengaju
            ')
            ->from('stock_opname so')
            ->join('gudang g', 'so.id_gudang = g.id_gudang')
            ->join('pengguna p', 'so.id_pelaksana = p.pengguna_id')
            ->join('pengguna p2', 'so.id_pengaju = p2.pengguna_id');
            return $this->db->get()->result();
    }


    function getAllSO($id)
    {
        $this->db->select('
                so.id_so,
				so.kode,
                so.keterangan,
				so.tanggal_mulai,
				so.status,
                so.created_at,
                so.ttd_1,
                so.ttd_2,
                so.catatan,
                so.link,
                so.id_pengaju,
                so.id_pelaksana,
				g.nama_gudang,
                p.nama as nama_pelaksana,
                p.jabatan as jabatan_pelaksana,
                p2.nama as nama_pengaju,
                p2.jabatan as jabatan_pengaju
            ')
            ->from('stock_opname so')
            ->join('gudang g', 'so.id_gudang = g.id_gudang')
            ->join('pengguna p', 'so.id_pelaksana = p.pengguna_id')
            ->join('pengguna p2', 'so.id_pengaju = p2.pengguna_id')
            ->where('so.id_so', $id);
            return $this->db->get()->result();
    }
    

    function getAllSODetail($id)
    {
        $this->db->select('
                so.id,
                so.id_so,
                so.id_barang,
                so.stok_office,
                so.stok_accurate,
                so.stok_gudang,
                b.nama_barang
            ')
            ->from('stock_opname_detail so')
            ->join('barang b', 'so.id_barang = b.id_barang')
            ->where('so.id_so', $id)
            ->order_by('b.nama_barang', 'ASC');

        return $this->db->get()->result();
    }


    function updateSoDetail($where, $data)
    {
        $this->db->where($where);
        $this->db->update('stock_opname_detail', $data);
    }


    function getSoLastId(){
			return $this->db->select("*")->limit(1)->order_by('id_so',"DESC")->get('stock_opname')->row();
	}

    function getAllBarang()
	{
				$this->db->select('
						id_barang,
                        nama_barang
				')
				->from('barang')
                ->where('status = 1');
				return $this->db->get()->result();
	}











    function getBywhere($where)
    {
        $this->db->where($where);
        $this->db->order_by('p.identitas_pelanggan', 'ASC');
        return $this->db->get('pelanggan p')->result();
    }

    function updateByWhere($where, $data)
    {
        $this->db->where($where);
        $this->db->update('pelanggan', $data);
    }

    function getById($id)
    {
        return $this->db->get_where('pelanggan p', array('p.id_pelanggan' => $id))->result();
    }

    function getByIdTopik($id)
    {
        return $this->db->get_where('pelanggan p', array('t.id_topik' => $id))->result();
    }

    function updateTiket($id, $data)
    {
        $this->db->where('id_tiket', $id);
        $this->db->update('tiket', $data);
    }

    function addTiket($data)
    {
        $this->db->insert('tiket', $data);
    }
   
    function getAllTiket()
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.id_tiket,
                tt.nama as nama_topik,
                t.subject,
                t.prioritas,
                p.nama,
                p1.nama as nama_penerima, 
                t.status_tiket,
                p.level
            ')
            ->from('tiket t')
            ->join('topik_tiket tt', 't.id_topik=tt.id_topik')
            ->join('pengguna p', 't.id_pembuat=p.pengguna_id')
            ->join('pengguna p1', 't.id_penerima=p1.pengguna_id')
            ->where('t.status_data = 1')
            ->generate();
    }
    
    function addPelanggan($data)
    {
        $this->db->insert('pelanggan', $data);
    }

    function addPelangganCommisioning($data)
    {
        $this->db->insert('pelanggan_commisioning', $data);
    }
	
	function update($id, $data)
    {
        $this->db->where('id_pelanggan', $id);
        $this->db->update('pelanggan', $data);
    }

    function getAllPelanggan()
    {
        $this->db->order_by('id_pelanggan', 'DESC');
        return $this->datatables
            ->select('
				id_pelanggan,
                identitas_pelanggan as nama,
				kontak,
				provinsi as prov,
				kota,
				alamat,
				status,
				date_created
            ')
            ->from('pelanggan')
            ->generate();
    }

    function getAllCommisioning()
    {
        $this->db->order_by('id', 'DESC');
        return $this->datatables
            ->select('
				id,
                nama_hospital,
				kode_tiket,
				provinsi,
				kota,
				created_at
            ')
            ->from('pelanggan_commisioning')
            ->generate();
    }

    function getCommisioningById($where)
    {
        $this->db->order_by('id', 'DESC');
        return $this->datatables
            ->select('
				id,
                id_pelanggan,
                marketing,
                nama_hospital,
				kode_tiket,
				provinsi,
				kota,
				created_at
            ')
            ->from('pelanggan_commisioning')
            ->where($where)
            ->generate();
    }

    function getComByID($where)
    {
        $this->db->select('
				id,
                id_pelanggan,
                marketing,
                warranty_start,
                warranty_end,
                nama_hospital,
                model,
				kode_tiket,
				provinsi,
				kota,
				created_at
                        ')
            ->from('pelanggan_commisioning')
            ->where($where)
            ->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }

    function getDetailPelangganById($where)
    {
        $this->db->select('
							id_pelanggan,
                            identitas_pelanggan as nama,
                            kontak,
                            provinsi as prov,
                            kota,
                            alamat,
                            status,
                            tanggal,
                            cpname,
                            tipe_bisnis,
                            marketing,
                            kelas,
                            npwp,
                            nama_npwp,
                            ktp,
                            jenis_transaksi,
                            date_created
                        ')
            ->from('pelanggan')
            ->where($where)
            ->order_by('id_pelanggan', 'DESC');
        return $this->db->get()->result();
    }

    function getDetailPelanggan($kodeTiket)
    {
        $this->db->order_by('t.created_at', 'DESC');
        return $this->datatables
            ->select('
                t.kode_tiket,
                p.provinsi,
				p.kota
            ')
            ->from('tiket t')
            ->join('pelanggan p', 't.id_pelanggan=p.id_pelanggan')
            ->where('t.kode_tiket', $kodeTiket)
            ->generate();
    }

    // Untuk export data ke Excel PELANGGAN
    function getAllPelangganByTGL($pengguna_id = NULL,$tglawal,$tglakhir)
    { 
			$this->db->order_by('date_created', 'DESC');
            //return $this->db
            $query = $this->db
			->select('
                            id_pelanggan,
                            identitas_pelanggan as nama,
                            kontak,
                            provinsi as prov,
                            kota,
                            alamat,
                            status,
                            tanggal,
                            cpname,
                            tipe_bisnis,
                            marketing,
                            kelas,
                            npwp,
                            nama_npwp,
                            ktp,
                            jenis_transaksi,
                            date_created


            ')
            ->from('pelanggan')
            ->where('date_created >=', date('Y-m-d', strtotime($tglawal)))
            ->where('date_created <=', date('Y-m-d', strtotime($tglakhir)));
			//->get()
			//->result();

        // Kondisi jika ada marketing yang dipilih
        if ($pengguna_id != NULL) {
            $query->where('marketing', $pengguna_id);
        }

        return $query->get()->result();
    }


    // Untuk export data ke Excel COMMISIONING
    function getAllCommisioningByTGL($pengguna_id = NULL,$tglawal,$tglakhir)
    { 
			$this->db->order_by('pc.created_at', 'DESC');
            //return $this->db
            $query = $this->db
			->select('
                            p.id_pelanggan,
                            p.identitas_pelanggan as nama,
                            p.kontak,
                            pc.address,
                            pc.created_at,
                            pc.id_pelanggan,
                            pc.provinsi as prov,
                            pc.kota,
                            pc.kode_tiket,
                            pc.nama_hospital,
                            pc.warranty_start,
                            pc.warranty_end,
                            pc.serial_number,
                            pc.manufacturer,
                            pc.model,
                            pc.equipment,
                            pc.merk,
                            pc.qty,
                            pc.engineer,
                            pc.link_invoice,
                            pc.link_service_report,
                            pc.link_commisioning,
                            pc.link_kepuasan_pelanggan,
                            pc.link_dokumentasi,
                            pc.link_garansi_vym


            ')
            ->from('pelanggan_commisioning pc')
            ->join('pelanggan p', 'pc.id_pelanggan=p.id_pelanggan')
            ->where('created_at >=', date('Y-m-d', strtotime($tglawal)))
            ->where('created_at <=', date('Y-m-d', strtotime($tglakhir)));
			//->get()
			//->result();

        // Kondisi jika ada marketing yang dipilih
        if ($pengguna_id != NULL) {
            $query->where('pc.marketing', $pengguna_id);
        }

        return $query->get()->result();
    }


}
