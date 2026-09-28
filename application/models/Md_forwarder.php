<?php
if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}

class Md_forwarder extends CI_Model
{
    function add($data)
    {
        $this->db->insert("forwarder", $data);
    }

    function getById($id)
    {
        return $this->db->get_where("forwarder p", ["p.id" => $id])->result();
    }

    function update($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->update("forwarder", $data);
    }

    function hapus($where, $tabel)
    {
        $this->db->where($where);
        $this->db->delete($tabel);
    }

    function getAll()
    {
        $this->db->order_by("nama", "ASC");
        return $this->datatables
            ->select(
                '
                id,
                nama,
                status,
                alamat,
                contact,
                website,
                keterangan,
                created_at
            ',
            )
            ->from("forwarder")
            ->where("status", 1)
            ->generate();
    }

    function getAllApp()
    {
        $this->db->order_by("id", "DESC");
        return $this->datatables
            ->select(
                '
                f.id,
                f.nama,
                f.status,
                f.kode,
                f.sistem_pengiriman,
                f.pol,
                f.pod,
                f.berat_dimensi,
                f.mata_nilai_inv,
                f.nilai_inv,
                f.tanggal,
                f.kurs,
                f.created_at,
                f.id_pengguna,
                p.nama as pengaju,
                p.jabatan as jabatan
            ',
            )
            ->from("approval_forwarder f")
            ->join("pengguna p", "f.id_pengguna=p.pengguna_id")
            //->where('f.status', 1)
            ->generate();
    }

    function getAllAppBy($id)
    {
        $this->db->order_by("id", "DESC");
        return $this->datatables
            ->select(
                '
                f.id,
                f.nama,
                f.status,
                f.kode,
                f.sistem_pengiriman,
                f.pol,
                f.pod,
                f.berat_dimensi,
                f.nilai_inv,
                f.mata_nilai_inv,
                f.kurs,
                f.tanggal,
                f.created_at,
                f.id_pengguna,
                p.nama as pengaju,
                p.jabatan as jabatan
            ',
            )
            ->from("approval_forwarder f")
            ->join("pengguna p", "f.id_pengguna=p.pengguna_id")
            //->where('f.status', 1)
            ->where("f.id_pengguna", $id)
            ->generate();
    }

    // fungsi reset urutan id pada tabel
    function reset_increment($tabel)
    {
        $this->db->query("ALTER TABLE " . $tabel . " AUTO_INCREMENT = 1");
    }

    //GET
    function getKodeId()
    {
        return $this->db
            ->select("COUNT(*) as id")
            ->limit(1)
            ->order_by("id", "DESC")
            ->get_where("approval_forwarder", [
                "YEAR(`created_at`)" => date("Y"),
            ])
            ->row();
    }

    function addForwarder($data)
    {
        $this->db->insert("approval_forwarder", $data);
    }

    function getByIdForwarder($id)
    {
        return $this->db
            ->get_where("approval_forwarder p", ["p.id" => $id])
            ->result();
    }

    function updateForwarder($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->update("approval_forwarder", $data);
    }

    function addDetailForwarder($data)
    {
        $this->db->insert("approval_forwarder_detail", $data);
    }

    function getAppByID($id)
    {
        $this->db
            ->select(
                '
                f.id as idGc,
                f.nama,
                f.status,
                f.kode,
                f.sistem_pengiriman,
                f.pol,
                f.pod,
                f.berat_dimensi,
                f.nilai_inv,
                f.mata_nilai_inv,
                f.tanggal,
                f.kurs,
                f.ttd_1,
                f.ttd_2,
                f.link_final,
                f.created_at,
                f.id_pengguna,
                f.kota_aju,
                f.tgl_aju,
                p.nama as pengaju,
                p.jabatan as jabatan
                ',
            )
            ->from("approval_forwarder f")
            ->join("pengguna p", "f.id_pengguna=p.pengguna_id")
            ->where("f.id", $id);
        return $this->db->get()->result();
    }

    /*function getDetailAppById($id)
{
    $this->db->select('
        df.id,
        df.id_app,
        df.id_forwarder,
        f.nama AS nama_forwarder,
        df.detail,
        df.pol,
        df.pod,
        df.berat,
        df.ocean,
        df.cfs,
        df.air,
        df.doc,
        df.trf,
        df.agency,
        df.handling,
        df.mechanics,
        df.other,
        df.do,
        df.warehouse,
        df.devanning,
        df.forwarding,
        df.admin,
        df.exwork,
        df.customs,
        df.ppn,
        df.dipilih,
        df.storage,
        df.asuransi,
        df.nom_as,
        df.sph_as,
        df.alasan,
        df.link_sph,
        df.status,
        df.created_at
    ')
    ->from('approval_forwarder_detail df')
    ->join('forwarder f', 'f.id = df.id_forwarder', 'left')
    ->where('df.id_app', $id);

    return $this->db->get()->result();
}*/

    function getDetailAppById($id)
    {
        $this->db
            ->select(
                '
                df.id,
                df.id_app,
                df.id_forwarder,
                f.nama AS nama_forwarder,
                df.status,
                df.created_at,

                /* --- BIAYA ASAL (ORIGIN) --- */
                IFNULL(df.asal_thc, 0) as asal_thc,
                IFNULL(df.asal_bl_fee, 0) as asal_bl_fee,
                IFNULL(df.asal_vgm, 0) as asal_vgm,
                IFNULL(df.asal_agaency_fee, 0) as asal_agaency_fee,
                IFNULL(df.asal_handling_fee, 0) as asal_handling_fee,
                IFNULL(df.asal_transportasi, 0) as asal_transportasi,
                IFNULL(df.asal_loading, 0) as asal_loading,
                IFNULL(df.asal_custom, 0) as asal_custom,
                IFNULL(df.asal_pickup, 0) as asal_pickup,
                IFNULL(df.asal_seal, 0) as asal_seal,
                IFNULL(df.asal_shipping, 0) as asal_shipping,
                IFNULL(df.asal_cfs, 0) as asal_cfs,
                IFNULL(df.asal_dg, 0) as asal_dg,
                IFNULL(df.asal_psa, 0) as asal_psa,
                IFNULL(df.asal_other, 0) as asal_other,

                /* --- FREIGHT --- */
                IFNULL(df.freight_ocean, 0) as freight_ocean,
                IFNULL(df.freight_air, 0) as freight_air,

                /* --- BIAYA TUJUAN (DESTINATION) --- */
                IFNULL(df.tuj_cfs, 0) as tuj_cfs,
                IFNULL(df.tuj_doc, 0) as tuj_doc,
                IFNULL(df.tuj_agency_fee, 0) as tuj_agency_fee,
                IFNULL(df.tuj_handling, 0) as tuj_handling,
                IFNULL(df.tuj_do, 0) as tuj_do,
                IFNULL(df.tuj_admin, 0) as tuj_admin,
                IFNULL(df.tuj_devanning, 0) as tuj_devanning,
                IFNULL(df.tuj_fordwarding_fee, 0) as tuj_fordwarding_fee,
                IFNULL(df.tuj_mechanics, 0) as tuj_mechanics,
                IFNULL(df.tuj_other, 0) as tuj_other,

                /* --- CUSTOMS CLEARANCE --- */
                IFNULL(df.cust_clearance, 0) as cust_clearance,
                IFNULL(df.cust_red_line, 0) as cust_red_line,
                IFNULL(df.cust_handling, 0) as cust_handling,
                IFNULL(df.cust_admin_fee, 0) as cust_admin_fee,
                IFNULL(df.cust_pib_fee, 0) as cust_pib_fee,
                IFNULL(df.cust_transfer, 0) as cust_transfer,
                IFNULL(df.cust_storage, 0) as cust_storage,
                IFNULL(df.cust_lift, 0) as cust_lift,
                IFNULL(df.cust_asu, 0) as cust_asu,
                IFNULL(df.cust_other, 0) as cust_other,

                /* --- OTHER CHARGES --- */
                IFNULL(df.oth_do, 0) as oth_do,
                IFNULL(df.oth_storage, 0) as oth_storage,
                IFNULL(df.oth_trucking, 0) as oth_trucking,
                IFNULL(df.oth_handling, 0) as oth_handling,
                IFNULL(df.oth_buruh, 0) as oth_buruh,
                IFNULL(df.oth_adm, 0) as oth_adm,
                IFNULL(df.oth_other, 0) as oth_other,

                /* --- INSURANCE --- */
                IFNULL(df.ins_nilai, 0) as ins_nilai,
                
                /* --- KOLOM NON ANGKA (TEXT/STRING) --- */
                df.ins_jenis,
                df.ppn,
                df.dipilih,
                df.alasan,
                df.keterangan AS keterangan_detail,
                df.link_invoice,
                df.link_packing,
                df.link_sph_for,
                df.link_sph_ins
            ',
            )
            ->from("approval_forwarder_detail df")
            ->join("forwarder f", "f.id = df.id_forwarder", "left")
            ->where("df.id_app", $id);

        return $this->db->get()->result();
    }

    function getForByWhere($where = "")
    {
        $this->db->where($where);
        $this->db->order_by("nama", "ASC");
        return $this->db->get("forwarder g")->result();
    }

    function getByIdDetail($id)
    {
        // Memperbaiki: Mengambil semua kolom dari tabel detail secara eksplisit.
        // Ini memastikan kolom 'keterangan' ada, menghilangkan error Notice di View saat proses Edit.
        $this->db->select("p.*");
        $this->db->from("approval_forwarder_detail p");
        $this->db->where("p.id", $id);
        return $this->db->get()->result();
    }
    function updateDetailForwarder($id, $data)
    {
        // DEBUG: Log ID yang dicari
        error_log("DEBUG updateDetailForwarder: Attempting to update ID = " . $id . " with data keys: " . implode(", ", array_keys($data)));

        $existing = $this->db
            ->select("id")
            ->from("approval_forwarder_detail")
            ->where("id", $id)
            ->limit(1)
            ->get()
            ->row();

        if (!$existing) {
            error_log("DEBUG updateDetailForwarder: ID not found = " . $id);
            return [
                'affected_rows' => 0,
                'error'         => '',
                'success'       => false,
                'found'         => false,
                'id_searched'   => $id,
            ];
        }

        $this->db->where("id", $id);
        $this->db->update("approval_forwarder_detail", $data);
        
        $affected_rows = $this->db->affected_rows();
        
        // DEBUG: Log hasil query
        error_log("DEBUG updateDetailForwarder: Query result - affected_rows = " . $affected_rows);
        error_log("DEBUG updateDetailForwarder: Last query = " . $this->db->last_query());
        
        // CodeIgniter error() returns array, kita extract message
        $db_error = $this->db->error();
        $error_msg = '';
        
        if (is_array($db_error) && !empty($db_error)) {
            $error_msg = $db_error['message'] ?? '';
            error_log("DEBUG updateDetailForwarder: DB Error = " . $error_msg);
        }
        
        // Return status update: jumlah rows yang diupdate atau error info
        return [
            'affected_rows' => $affected_rows,
            'error'         => $error_msg,
            'success'       => (empty($error_msg) && (bool) $existing),
            'found'         => true,
            'changed'       => ($affected_rows > 0),
            'id_searched'   => $id  // Add untuk debugging
        ];
    }

    function deleteDetail($id)
    {
        $this->db->where("id", $id);
        return $this->db->delete("approval_forwarder_detail");
    }

    function insertApprovalForwarder($data)
    {
        $this->db->insert("approval_forwarder", $data);
        return $this->db->insert_id(); // ambil ID terakhir
    }

    function insertDetailApprovalForwarder($data)
    {
        return $this->db->insert("approval_forwarder_detail", $data);
    }
}
