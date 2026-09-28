<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_pelanggan extends CI_Model
{
    // Mendapatkan tahun berjalan untuk filter New/Existing Customer
    private function getCurrentYear()
    {
        return date('Y');
    }

    // Mendapatkan statistik pelanggan
    function getStatistik()
    {
        $currentYear = $this->getCurrentYear();
        
        // Total Pelanggan
        $total = $this->db->count_all('pelanggan');
        
        // New Customer (registrasi tahun berjalan)
        $this->db->where('YEAR(tanggal_registrasi)', $currentYear);
        $newCustomer = $this->db->count_all_results('pelanggan');
        
        // Existing Customer (registrasi sebelum tahun berjalan atau NULL)
        $this->db->where("(YEAR(tanggal_registrasi) < {$currentYear} OR tanggal_registrasi IS NULL)");
        $existingCustomer = $this->db->count_all_results('pelanggan');
        
        return [
            'total' => $total,
            'new_customer' => $newCustomer,
            'existing_customer' => $existingCustomer
        ];
    }

    // Mendapatkan status customer berdasarkan tanggal registrasi
    function getStatusCustomer($tanggal_registrasi)
    {
        if (empty($tanggal_registrasi)) {
            return 'Existing Customer';
        }
        $tahunRegistrasi = date('Y', strtotime($tanggal_registrasi));
        $currentYear = $this->getCurrentYear();
        return ($tahunRegistrasi == $currentYear) ? 'New Customer' : 'Existing Customer';
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
   function hapus($where, $tabel){
		$this->db->where($where);
		$this->db->delete($tabel);
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

    function getAllPelanggan($filter = [])
    {
        $currentYear = $this->getCurrentYear();
        
        $this->db->order_by('id_pelanggan', 'DESC');
        $query = $this->datatables
            ->select("
				id_pelanggan,
                identitas_pelanggan as nama,
				kontak,
                email,
                nik,
                status_pajak,
				provinsi as prov,
				kota,
				alamat,
                tipe_bisnis,
                cpname,
                npwp,
                nama_npwp,
                link_npwp,
                pengiriman_dokumen,
                alamat_pengiriman_dokumen,
                alamat_penagihan,
                tanggal_registrasi,
				status,
				date_created,
                updated_at,
                CASE 
                    WHEN YEAR(tanggal_registrasi) = {$currentYear} THEN 'New Customer'
                    ELSE 'Existing Customer'
                END as status_customer
            ")
            ->from('pelanggan');
        
        // Filter by status customer
        if (!empty($filter['status_customer'])) {
            if ($filter['status_customer'] == 'New Customer') {
                $query->where('YEAR(tanggal_registrasi)', $currentYear);
            } else if ($filter['status_customer'] == 'Existing Customer') {
                // Use raw WHERE condition for OR clause (Datatables library doesn't support group_start)
                $query->where("(YEAR(tanggal_registrasi) < {$currentYear} OR tanggal_registrasi IS NULL)");
            }
        }
        
        // Filter by tipe bisnis
        if (!empty($filter['tipe_bisnis'])) {
            $query->where('tipe_bisnis', $filter['tipe_bisnis']);
        }
        
        // Filter by status pajak
        if (!empty($filter['status_pajak'])) {
            $query->where('status_pajak', $filter['status_pajak']);
        }
        
        return $query->generate();
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
        $currentYear = $this->getCurrentYear();
        $this->db->select("
							id_pelanggan,
                            identitas_pelanggan as nama,
                            kontak,
                            email,
                            nik,
                            status_pajak,
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
                            link_npwp,
                            pengiriman_dokumen,
                            alamat_pengiriman_dokumen,
                            alamat_penagihan,
                            link_folder_berkas,
                            tanggal_registrasi,
                            ktp,
                            jenis_transaksi,
                            date_created,
                            updated_at,
                            CASE 
                                WHEN YEAR(tanggal_registrasi) = {$currentYear} THEN 'New Customer'
                                ELSE 'Existing Customer'
                            END as status_customer
                        ")
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
        $currentYear = $this->getCurrentYear();
        $this->db->order_by('date_created', 'DESC');
            //return $this->db
            $query = $this->db
			->select("
                            id_pelanggan,
                            identitas_pelanggan as nama,
                            kontak,
                            email,
                            nik,
                            status_pajak,
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
                            link_npwp,
                            pengiriman_dokumen,
                            alamat_pengiriman_dokumen,
                            alamat_penagihan,
                            tanggal_registrasi,
                            ktp,
                            jenis_transaksi,
                            date_created,
                            CASE 
                                WHEN YEAR(tanggal_registrasi) = {$currentYear} THEN 'New Customer'
                                ELSE 'Existing Customer'
                            END as status_customer
            ")
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

    // Export seluruh data pelanggan dengan filter
    function getAllPelangganForExport($filter = [])
    {
        $currentYear = $this->getCurrentYear();
        $this->db->order_by('date_created', 'DESC');
        $query = $this->db
            ->select("
                id_pelanggan,
                identitas_pelanggan as nama,
                kontak,
                email,
                nik,
                status_pajak,
                provinsi as prov,
                kota,
                alamat,
                cpname,
                tipe_bisnis,
                marketing,
                kelas,
                npwp,
                nama_npwp,
                link_npwp,
                pengiriman_dokumen,
                alamat_pengiriman_dokumen,
                alamat_penagihan,
                link_folder_berkas,
                tanggal_registrasi,
                ktp,
                jenis_transaksi,
                date_created,
                CASE 
                    WHEN YEAR(tanggal_registrasi) = {$currentYear} THEN 'New Customer'
                    ELSE 'Existing Customer'
                END as status_customer
            ")
            ->from('pelanggan');
        
        // Filter by status customer
        if (!empty($filter['status_customer'])) {
            if ($filter['status_customer'] == 'New Customer') {
                $query->where('YEAR(tanggal_registrasi)', $currentYear);
            } else if ($filter['status_customer'] == 'Existing Customer') {
                $query->where("(YEAR(tanggal_registrasi) < {$currentYear} OR tanggal_registrasi IS NULL)");
            }
        }
        
        // Filter by tipe bisnis
        if (!empty($filter['tipe_bisnis'])) {
            $query->where('tipe_bisnis', $filter['tipe_bisnis']);
        }
        
        return $query->get()->result();
    }

    // Check pelanggan by ID or Nama untuk Import
    // Prioritas: 1. ID, 2. Nama (exact), 3. Nama (case-insensitive)
    function checkPelangganByIdOrNama($id = null, $nama = null)
    {
        // Check by ID first (paling akurat)
        if ($id && is_numeric($id) && $id > 0) {
            $result = $this->db->get_where('pelanggan', ['id_pelanggan' => $id])->row();
            if ($result) {
                return $result;
            }
        }
        
        // Check by nama (exact match)
        if ($nama) {
            $nama = trim($nama);
            $result = $this->db->get_where('pelanggan', ['identitas_pelanggan' => $nama])->row();
            if ($result) {
                return $result;
            }
            
            // Check by nama (case-insensitive, untuk menghindari duplikat karena kapitalisasi)
            $result = $this->db
                ->where('LOWER(TRIM(identitas_pelanggan))', strtolower($nama))
                ->get('pelanggan')
                ->row();
            if ($result) {
                return $result;
            }
        }
        
        return null;
    }

    // Get tipe bisnis untuk filter
    function getTipeBisnis()
    {
        $this->db->select('DISTINCT(tipe_bisnis) as tipe_bisnis');
        $this->db->from('pelanggan');
        $this->db->where('tipe_bisnis IS NOT NULL');
        $this->db->where('tipe_bisnis !=', '');
        $this->db->order_by('tipe_bisnis', 'ASC');
        return $this->db->get()->result();
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
