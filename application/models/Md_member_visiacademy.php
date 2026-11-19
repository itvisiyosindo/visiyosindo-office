<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_member_visiacademy extends CI_Model
{
    public function getAllMember()
    {
        return $this->db->order_by('id_member', 'DESC')->get('va_member')->result_array();
    }

    public function getMemberById($id)
    {
        return $this->db->where('id_member', (int)$id)->get('va_member')->row_array();
    }

    public function getMemberByKode($kode)
    {
        return $this->db->where('kode_member', trim($kode))->get('va_member')->row_array();
    }

    public function generateNextKode()
    {
        $last = $this->db->select('kode_member')
                         ->like('kode_member', 'VA-', 'after')
                         ->order_by('id_member', 'DESC')
                         ->limit(1)
                         ->get('va_member')
                         ->row_array();

        if ($last && preg_match('/VA-(\d+)/', $last['kode_member'], $m)) {
            $nextNum = ((int)$m[1]) + 1;
        } else {
            // Count total rows as fallback
            $count = $this->db->count_all('va_member');
            $nextNum = $count + 1;
        }

        return 'VA-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function addMember($data)
    {
        return $this->db->insert('va_member', $data);
    }

    public function updateMember($id, $data)
    {
        return $this->db->where('id_member', (int)$id)->update('va_member', $data);
    }

    public function deleteMember($id)
    {
        return $this->db->where('id_member', (int)$id)->delete('va_member');
    }

    public function getAllDeals()
    {
        return $this->db->select('d.*, m.nama_member, m.kode_member, m.institusi_asal')
                        ->from('va_member_deal d')
                        ->join('va_member m', 'm.id_member = d.id_member', 'left')
                        ->order_by('d.id_deal', 'DESC')
                        ->get()->result_array();
    }

    public function getDealById($id)
    {
        return $this->db->where('id_deal', (int)$id)->get('va_member_deal')->row_array();
    }

    public function getDealByMember($id_member)
    {
        return $this->db->where('id_member', (int)$id_member)->get('va_member_deal')->result_array();
    }

    public function addDeal($data)
    {
        return $this->db->insert('va_member_deal', $data);
    }

    public function updateDealStatus($id, $status)
    {
        return $this->db->where('id_deal', (int)$id)->update('va_member_deal', ['status_deal' => $status]);
    }

    public function deleteDeal($id)
    {
        return $this->db->where('id_deal', (int)$id)->delete('va_member_deal');
    }

    public function getSummary()
    {
        $totalMember = $this->db->count_all('va_member');
        
        $dealStats = $this->db->select("
            COUNT(id_deal) as total_deal,
            SUM(CASE WHEN status_deal = 'Deal' THEN estimasi_nilai ELSE 0 END) as total_nilai_deal,
            SUM(CASE WHEN status_deal = 'Deal' THEN nominal_komisi ELSE 0 END) as total_komisi_deal,
            SUM(CASE WHEN status_deal = 'Pending' THEN nominal_komisi ELSE 0 END) as total_komisi_pending
        ")->get('va_member_deal')->row_array();

        return [
            'total_member'         => (int)$totalMember,
            'total_deal'           => (int)($dealStats['total_deal'] ?? 0),
            'total_nilai_deal'     => (float)($dealStats['total_nilai_deal'] ?? 0),
            'total_komisi_deal'    => (float)($dealStats['total_komisi_deal'] ?? 0),
            'total_komisi_pending' => (float)($dealStats['total_komisi_pending'] ?? 0),
        ];
    }
}
