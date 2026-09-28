<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_dokumen_rahasia_visilab extends CI_Model
{
  var $table = 'dokumen_rahasia_visilab';

  // Update column_order agar fitur sorting di datatable bekerja pada kolom nama kategori
  var $column_order = array(null, 'a.nama_dokumen', 'b.nama', 'a.file', 'a.created_at', null);
  var $column_search = array('a.nama_dokumen', 'b.nama');
  var $order = array('a.id' => 'desc');

  public function __construct()
  {
    parent::__construct();
  }

  private function _get_datatables_query()
  {
    // Mengambil nama kategori sebagai 'nama_kategori'
    $this->db->select('a.*, b.nama as nama_kategori');
    $this->db->from($this->table . ' a');

    // Join ke tabel dokumen_kategori_visilab
    $this->db->join('dokumen_kategori_visilab b', 'b.id = a.id_kategori', 'left');

    $this->db->where('a.status', 1);

    $i = 0;
    foreach ($this->column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if (count($this->column_search) - 1 == $i)
          $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables()
  {
    $this->_get_datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered()
  {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->table);
    $this->db->where('status', 1);
    return $this->db->count_all_results();
  }

  public function getById($id)
  {
    $this->db->where('id', $id);
    return $this->db->get($this->table)->row();
  }

  public function add($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function update($id, $data)
  {
    $this->db->where('id', $id);
    $this->db->update($this->table, $data);
    return $this->db->affected_rows();
  }

  public function delete($id)
  {
    $data = [
      'status' => 0,
      'last_updated_by' => sessPenggunaId(),
      'last_updated_at' => date('Y-m-d H:i:s')
    ];
    $this->db->where('id', $id);
    $this->db->update($this->table, $data);
  }
}
