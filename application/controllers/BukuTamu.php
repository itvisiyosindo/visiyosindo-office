<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class BukuTamu extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    date_default_timezone_set('Asia/Jakarta');
    $this->load->model('md_prov_kota');
    $this->load->model('md_bukutamu');
    $this->load->model('md_kategori_tiket');
  }

  function id_navbar()
  {
    $id_navbar = "home";
    return $id_navbar;
  }

  private function normalizeTanggalFilter($tanggal)
  {
    $tanggal = trim((string) $tanggal);
    if ($tanggal === '') {
      return null;
    }

    $dateObj = DateTime::createFromFormat('Y-m-d', $tanggal);
    if ($dateObj && $dateObj->format('Y-m-d') === $tanggal) {
      return $tanggal;
    }

    return null;
  }



  public function index($event_id = '')
  {
    $kegiatanId = 0;
    if (!empty($event_id)) {
      $kegiatanId = is_numeric($event_id) ? (int) $event_id : (int) decrypt($event_id);
    }
    if ($kegiatanId <= 0) {
      $kegiatanId = (int) $this->input->get('kegiatan_id') ?: ((int) $this->input->get('event') ?: 0);
    }

    if ($kegiatanId > 0) {
      $kegiatan = $this->md_bukutamu->getKegiatanById($kegiatanId);
    } else {
      $kegiatan = $this->md_bukutamu->getLastId();
    }

    if (empty($kegiatan)) {
      $kegiatan = $this->md_bukutamu->getLastId();
    }

    $kegiatanId = (is_object($kegiatan) && !empty($kegiatan->id)) ? (int) $kegiatan->id : 0;

    $page_data['page_name']     = 'bukutamu';
    $page_data['page_title']    = 'Buku Tamu';
    $page_data['page_desc']     = 'Management Buku Tamu';
    $page_data['kegiatan']      = $kegiatan;
    $page_data['totalIsiEvent'] = $kegiatanId > 0
      ? $this->md_bukutamu->getTotalIsiByKegiatan($kegiatanId)
      : 0;
    $page_data['provinsi']      = $this->md_prov_kota->getAllProvinsi();
    $page_data['kategori']      = $this->md_kategori_tiket->getByWhere(['t.is_active' => 1, 't.status' => 1]);
    $this->load->view('bukutamu', $page_data);
  }



  public function dataBukutamu($param2 = "")
  {
    grantAccessFor('all');

    $selectedKegiatanId = (int) $this->input->get('kegiatan_id');
    $selectedKegiatanId = $selectedKegiatanId > 0 ? $selectedKegiatanId : null;
    $selectedTanggalMulai = $this->normalizeTanggalFilter($this->input->get('tanggal_mulai', true));
    $selectedTanggalSelesai = $this->normalizeTanggalFilter($this->input->get('tanggal_selesai', true));

    if (!empty($selectedTanggalMulai) && !empty($selectedTanggalSelesai) && $selectedTanggalMulai > $selectedTanggalSelesai) {
      $tmp = $selectedTanggalMulai;
      $selectedTanggalMulai = $selectedTanggalSelesai;
      $selectedTanggalSelesai = $tmp;
    }

    $selectedEventLabel = 'Semua Kegiatan';
    if (!empty($selectedKegiatanId)) {
      $eventRow = $this->md_bukutamu->getKegiatanById($selectedKegiatanId);
      if (!empty($eventRow->nama)) {
        $selectedEventLabel = $eventRow->nama;
      }
    }

    $statistik = $this->md_bukutamu->getManagementStats($selectedKegiatanId, $selectedTanggalMulai, $selectedTanggalSelesai);

    $page_data['switch']        = $this->id_navbar();
    $page_data['pengguna']  = $this->md_pengguna->getById(decrypt($param2));
    $page_data['page_name']    = 'v_bukutamu';
    $page_data['page_title']    = 'Buku Tamu';
    $page_data['page_desc']     = 'List Isi Buku Tamu';
    $page_data['switch']        = $this->id_navbar();
    $page_data['pengguna']  = $this->md_pengguna->getById(decrypt($param2));
    $page_data['nama_kegiatan']     = $this->md_bukutamu->getKegiatan();
    $page_data['totalIsi'] = $this->md_bukutamu->getTotalIsi();
    $page_data['provinsi']      = $this->md_prov_kota->getAllProvinsi();
    $page_data['selected_kegiatan_id'] = $selectedKegiatanId;
    $page_data['selected_tanggal_mulai'] = $selectedTanggalMulai;
    $page_data['selected_tanggal_selesai'] = $selectedTanggalSelesai;
    $page_data['selected_event_label'] = $selectedEventLabel;
    $page_data['statistik'] = $statistik;
    $this->load->view('index', $page_data);
  }



  function add_ajax_kota($id_prov)
  {
    $query = $this->db->get_where('kota', array('id_prov' => $id_prov));
    $data = "<option value=''>- Pilih Kabupaten/Kota -</option>";
    foreach ($query->result() as $value) {
      $data .= "<option value='" . $value->id . "'>" . $value->tipe . ' ' . $value->nama . "</option>";
    }
    echo $data;
  }


  public function pagination()
  {
    // grantAccessFor(['Administrator', 'Hrd', 'Ga']);

    $kegiatanId = (int) $this->input->post('kegiatan_id');
    $tanggalMulai = $this->normalizeTanggalFilter($this->input->post('tanggal_mulai', true));
    $tanggalSelesai = $this->normalizeTanggalFilter($this->input->post('tanggal_selesai', true));

    if (!empty($tanggalMulai) && !empty($tanggalSelesai) && $tanggalMulai > $tanggalSelesai) {
      $tmp = $tanggalMulai;
      $tanggalMulai = $tanggalSelesai;
      $tanggalSelesai = $tmp;
    }

    $dt = $this->md_bukutamu->getAllBukuTamu($kegiatanId, $tanggalMulai, $tanggalSelesai);
    $data  = array();
    $index = ((int) $this->input->post('start')) + 1;
    foreach ($dt['data'] as $row) {
      $id_enc = encrypt($row->id_bukutamu);

      $th = array();
      $th[] = $index;
      $th[] = htmlspecialchars($row->nama);
      $th[] = htmlspecialchars($row->nomorwa);
      $th[] = htmlspecialchars($row->email);
      $th[] = htmlspecialchars($row->jabatan);
      $th[] = htmlspecialchars($row->instansi);
      $th[] = htmlspecialchars($row->tipe_rs ?: '-');
      $th[] = htmlspecialchars($row->provinsi ?: '-');
      $th[] = htmlspecialchars($row->kota ?: '-');
      $th[] = '<i class="fa fa-clock-o"></i> ' . date('d-M-Y | H:i', strtotime($row->created_at));
      $th[] = htmlspecialchars($row->nama_kegiatan);
      $th[] = htmlspecialchars($row->kebutuhan);

      $action = '<div style="white-space: nowrap;">';
      $action .= '<button type="button" class="btn btn-xs btn-primary btn-edit-tamu" data-id="' . $id_enc . '" title="Edit"><i class="fas fa-pencil-alt"></i> Edit</button> ';
      $action .= '<button type="button" class="btn btn-xs btn-danger btn-delete-tamu" data-id="' . $id_enc . '" title="Hapus"><i class="fas fa-trash"></i> Hapus</button>';
      $action .= '</div>';
      $th[] = $action;

      $data[] = $th;
      $index++;
    }
    $dt['data'] = $data;
    echo json_encode($dt);
    die;
  }

  public function statistik()
  {
    grantAccessFor('all');
    $this->output->set_content_type('application/json');

    if (strtoupper($this->input->method()) !== 'POST') {
      echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.',
      ]);
      return;
    }

    $kegiatanId = (int) $this->input->post('kegiatan_id');
    $kegiatanId = $kegiatanId > 0 ? $kegiatanId : null;
    $tanggalMulai = $this->normalizeTanggalFilter($this->input->post('tanggal_mulai', true));
    $tanggalSelesai = $this->normalizeTanggalFilter($this->input->post('tanggal_selesai', true));

    if (!empty($tanggalMulai) && !empty($tanggalSelesai) && $tanggalMulai > $tanggalSelesai) {
      $tmp = $tanggalMulai;
      $tanggalMulai = $tanggalSelesai;
      $tanggalSelesai = $tmp;
    }

    $stats = $this->md_bukutamu->getManagementStats($kegiatanId, $tanggalMulai, $tanggalSelesai);

    $eventLabel = 'Semua Kegiatan';
    if (!empty($kegiatanId)) {
      $eventRow = $this->md_bukutamu->getKegiatanById($kegiatanId);
      if (!empty($eventRow->nama)) {
        $eventLabel = $eventRow->nama;
      }
    }

    echo json_encode([
      'status' => 'ok',
      'data' => [
        'total_data' => (int) ($stats['total_data'] ?? 0),
        'total_hari_ini' => (int) ($stats['total_hari_ini'] ?? 0),
        'total_instansi_unik' => (int) ($stats['total_instansi_unik'] ?? 0),
        'total_nomorwa_unik' => (int) ($stats['total_nomorwa_unik'] ?? 0),
        'last_input_label' => !empty($stats['last_input_at']) ? date('d-M-Y | H:i', strtotime($stats['last_input_at'])) : '-',
        'event_label' => $eventLabel,
      ],
    ]);
  }








  public function register()
  {
    // Tangani proses submit form
    $this->load->library('form_validation');
    // Atur aturan validasi untuk setiap field
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
    $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|trim');
    $this->form_validation->set_rules('nomorwa', 'Nomor WA', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
    $this->form_validation->set_rules('instansi', 'Instansi', 'required|trim');
    $this->form_validation->set_rules('tipe_rs', 'Tipe Rumah Sakit', 'trim');
    $this->form_validation->set_rules('provinsi', 'Provinsi', 'required|trim');
    $this->form_validation->set_rules('kota', 'Kota', 'required|trim');
    $this->form_validation->set_rules('kebutuhan', 'Kebutuhan', 'trim');

    $oldInput = [
      'nama' => trim((string) $this->input->post('nama', true)),
      'jabatan' => trim((string) $this->input->post('jabatan', true)),
      'nomorwa' => trim((string) $this->input->post('nomorwa', true)),
      'email' => trim((string) $this->input->post('email', true)),
      'instansi' => trim((string) $this->input->post('instansi', true)),
      'tipe_rs' => trim((string) $this->input->post('tipe_rs', true)),
      'provinsi' => trim((string) $this->input->post('provinsi', true)),
      'kota' => trim((string) $this->input->post('kota', true)),
      'kebutuhan' => trim((string) $this->input->post('kebutuhan', true)),
    ];

    if ($this->form_validation->run() === false) {
      $this->session->set_flashdata('old_input', $oldInput);
      $this->session->set_flashdata('error', trim(strip_tags(validation_errors())));
      redirect('BukuTamu');
      return;
    }

    $kegiatanId = (int) $this->input->post('kegiatan_id');
    if ($kegiatanId <= 0) {
      $lastId = $this->md_bukutamu->getLastId();
      $kegiatanId = !empty($lastId->id) ? (int) $lastId->id : 0;
    }

    if ($kegiatanId <= 0 || !$this->md_bukutamu->isKegiatanExists($kegiatanId)) {
      $this->session->set_flashdata('old_input', $oldInput);
      $this->session->set_flashdata('error', 'Event belum tersedia. Silakan buat kegiatan terlebih dahulu.');
      redirect('BukuTamu');
      return;
    }

    $normalizedNoWa = $this->md_bukutamu->normalizeNoWa($oldInput['nomorwa']);
    if ($normalizedNoWa === '' || strlen($normalizedNoWa) < 10) {
      $this->session->set_flashdata('old_input', $oldInput);
      $this->session->set_flashdata('error', 'Nomor WhatsApp tidak valid. Gunakan angka aktif yang benar.');
      redirect('BukuTamu');
      return;
    }

    $ipAddress = (string) $this->input->ip_address();
    if ($ipAddress === '' || $ipAddress === '0.0.0.0') {
      $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    }

    $userAgent = substr((string) $this->input->user_agent(), 0, 255);

    $provinsi_id = $oldInput['provinsi'];
    $kota_id = $oldInput['kota'];
    $provinsi_nama = '';
    $kota_nama = '';

    if ($provinsi_id) {
      if (is_numeric($provinsi_id)) {
        $prov_row = $this->db->get_where('provinsi', ['id' => $provinsi_id])->row();
        $provinsi_nama = $prov_row ? $prov_row->nama : $provinsi_id;
      } else {
        $provinsi_nama = $provinsi_id;
      }
    }

    if ($kota_id) {
      if (is_numeric($kota_id)) {
        $kota_row = $this->db->get_where('kota', ['id' => $kota_id])->row();
        $kota_nama = $kota_row ? ($kota_row->tipe . ' ' . $kota_row->nama) : $kota_id;
      } else {
        $kota_nama = $kota_id;
      }
    }

    // Validasi berhasil, simpan data ke database.
    $data = [
      'nama' => $oldInput['nama'],
      'jabatan' => $oldInput['jabatan'],
      'nomorwa' => $normalizedNoWa,
      'email' => $oldInput['email'],
      'instansi' => $oldInput['instansi'],
      'tipe_rs' => $oldInput['tipe_rs'],
      'provinsi' => $provinsi_nama,
      'kota' => $kota_nama,
      'kebutuhan' => $oldInput['kebutuhan'],
      'kegiatan' => $kegiatanId,
    ];

    $saveResult = $this->md_bukutamu->saveWithDuplicateGuard($data, $ipAddress, $userAgent);

    if (($saveResult['status'] ?? '') === 'duplicate') {
      $this->session->set_flashdata('old_input', $oldInput);
      $this->session->set_flashdata('warning', 'Data duplikat terdeteksi: nomor WhatsApp ini sudah terdaftar pada event yang sama.');
      redirect('BukuTamu');
      return;
    }

    if (($saveResult['status'] ?? '') !== 'inserted') {
      $this->session->set_flashdata('old_input', $oldInput);
      $this->session->set_flashdata('error', 'Data gagal disimpan. Silakan coba lagi.');
      redirect('BukuTamu');
      return;
    }

    $this->session->set_flashdata('success', '<b>Isi Buku Tamu Berhasil!</b> Terimakasih dan semoga harimu menyenangkan... <a href="https://linktr.ee/expovym" target="_blank">Kunjungi kami disini</a>.');
    redirect('BukuTamu');
  }

  public function checkDuplicate()
  {
    $this->output->set_content_type('application/json');

    if (strtoupper($this->input->method()) !== 'POST') {
      echo json_encode([
        'status' => 'error',
        'duplicate' => false,
        'message' => 'Metode request tidak valid.'
      ]);
      return;
    }

    $kegiatanId = (int) $this->input->post('kegiatan_id');
    $nomorwa = trim((string) $this->input->post('nomorwa', true));

    if ($kegiatanId <= 0) {
      $lastId = $this->md_bukutamu->getLastId();
      $kegiatanId = !empty($lastId->id) ? (int) $lastId->id : 0;
    }

    if ($kegiatanId <= 0 || $nomorwa === '') {
      echo json_encode([
        'status' => 'ok',
        'duplicate' => false,
        'message' => ''
      ]);
      return;
    }

    $isDuplicate = $this->md_bukutamu->findDuplicateByNoWaInEvent($kegiatanId, $nomorwa) !== null;

    echo json_encode([
      'status' => 'ok',
      'duplicate' => $isDuplicate,
      'message' => $isDuplicate
        ? 'Nomor WhatsApp ini sudah terdaftar pada event yang sama.'
        : 'Data belum terdaftar.'
    ]);
  }

  public function success()
  {
    // Tampilkan halaman sukses
    $this->load->view('success');
  }



  public function addKegiatan()
  {
    grantAccessFor('all');

    $nama = trim((string) $this->input->post('nama', true));
    if (empty($nama)) {
      ajaxReturnDie('error', 'Nama kegiatan tidak boleh kosong.');
      return;
    }

    $data['nama']        = $nama;
    $data['pengguna_id'] = sessPenggunaId();

    $newId = $this->md_bukutamu->add($data);

    //add log
    $aksi = 'Manajemen Buku Tamu';
    $ket = 'Menambahkan Kegiatan Buku Tamu - ' . $data['nama'];
    addlog($aksi, $ket);

    $targetUrl = base_url('bukutamu?kegiatan_id=' . $newId);
    $response = [
      'status' => 'success',
      'message' => 'Kegiatan Berhasil Ditambahkan',
      'kegiatan_id' => $newId,
      'kegiatan_nama' => $nama,
      'target_url' => $targetUrl,
      'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' . urlencode($targetUrl),
      'print_url' => base_url('BukuTamu/print_qr/' . encrypt($newId))
    ];

    echo json_encode($response);
    die;
  }

  public function print_qr($id_encrypt = '')
  {
    grantAccessFor('all');

    $id = 0;
    if (!empty($id_encrypt)) {
      $id = is_numeric($id_encrypt) ? (int) $id_encrypt : (int) decrypt($id_encrypt);
    }
    if ($id <= 0) {
      $id = (int) $this->input->get('kegiatan_id') ?: 0;
    }

    if ($id > 0) {
      $kegiatan = $this->md_bukutamu->getKegiatanById($id);
    } else {
      $kegiatan = $this->md_bukutamu->getLastId();
    }

    if (empty($kegiatan)) {
      show_404();
      return;
    }

    $targetUrl = base_url('bukutamu?kegiatan_id=' . $kegiatan->id);
    $data = [
      'kegiatan' => $kegiatan,
      'target_url' => $targetUrl,
      'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=450x450&data=' . urlencode($targetUrl)
    ];

    $this->load->view('pages/v_print/print_bukutamu_qr', $data);
  }

  public function getQrModalJson()
  {
    grantAccessFor('all');
    $this->output->set_content_type('application/json');

    $id = (int) $this->input->get_post('kegiatan_id');
    if ($id <= 0) {
      $id_enc = $this->input->get_post('id_encrypt', true);
      if (!empty($id_enc)) {
        $id = (int) decrypt($id_enc);
      }
    }

    if ($id > 0) {
      $kegiatan = $this->md_bukutamu->getKegiatanById($id);
    } else {
      $kegiatan = $this->md_bukutamu->getLastId();
    }

    if (empty($kegiatan)) {
      echo json_encode(['status' => 'error', 'message' => 'Kegiatan tidak ditemukan.']);
      return;
    }

    $targetUrl = base_url('bukutamu?kegiatan_id=' . $kegiatan->id);
    echo json_encode([
      'status' => 'success',
      'kegiatan_id' => (int) $kegiatan->id,
      'kegiatan_nama' => $kegiatan->nama,
      'target_url' => $targetUrl,
      'qr_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=' . urlencode($targetUrl),
      'print_url' => base_url('BukuTamu/print_qr/' . encrypt($kegiatan->id))
    ]);
  }


  public function exportlaporan()
  {

    $data = $this->md_bukutamu->getAllKegiatan($this->input->get('idmarketing'));



    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();

    // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
    $style_col = [
      'font' => ['bold' => true], // Set font nya jadi bold
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
      'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
      ]
    ];


    // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
    $style_row = [
      'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
      'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
      ]
    ];

    $sheet->setCellValue('A1', "DATA BUKU TAMU " . strtoupper($this->input->get('namamarketing'))); // Set kolom A1 dengan tulisan "DATA SISWA"
    $sheet->mergeCells('A1:L1'); // Set Merge Cell pada kolom A1 sampai E1
    $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

    // Buat header tabel nya pada baris ke 3
    $sheet->setCellValue('A4', 'No');
    $sheet->setCellValue('B4', 'Nama');
    $sheet->setCellValue('C4', 'No HP');
    $sheet->setCellValue('D4', 'Email');
    $sheet->setCellValue('E4', 'Jabatan');
    $sheet->setCellValue('F4', 'Instansi');
    $sheet->setCellValue('G4', 'Tipe Rumah Sakit');
    $sheet->setCellValue('H4', 'Provinsi');
    $sheet->setCellValue('I4', 'Kota');
    $sheet->setCellValue('J4', 'Tanggal');
    $sheet->setCellValue('K4', 'Kegiatan');

    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A4')->applyFromArray($style_col);
    $sheet->getStyle('B4')->applyFromArray($style_col);
    $sheet->getStyle('C4')->applyFromArray($style_col);
    $sheet->getStyle('D4')->applyFromArray($style_col);
    $sheet->getStyle('E4')->applyFromArray($style_col);
    $sheet->getStyle('F4')->applyFromArray($style_col);
    $sheet->getStyle('G4')->applyFromArray($style_col);
    $sheet->getStyle('H4')->applyFromArray($style_col);
    $sheet->getStyle('I4')->applyFromArray($style_col);
    $sheet->getStyle('J4')->applyFromArray($style_col);
    $sheet->getStyle('K4')->applyFromArray($style_col);


    $kolom = 5;
    $nomor = 1;

    foreach ($data as $marketing) {

      $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $kolom, $nomor)
        ->setCellValue('B' . $kolom, $marketing->nama)
        ->setCellValue('C' . $kolom, $marketing->nomorwa)
        ->setCellValue('D' . $kolom, $marketing->email)
        ->setCellValue('E' . $kolom, $marketing->jabatan)
        ->setCellValue('F' . $kolom, $marketing->instansi)
        ->setCellValue('G' . $kolom, $marketing->tipe_rs ?: '-')
        ->setCellValue('H' . $kolom, $marketing->provinsi ?: '-')
        ->setCellValue('I' . $kolom, $marketing->kota ?: '-')
        ->setCellValue('J' . $kolom, date('d-M-Y | H:i', strtotime($marketing->created_at)))
        ->setCellValue('K' . $kolom, $marketing->nama_kegiatan);

      $kolom++;
      $nomor++;
    }

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
    $sheet->getColumnDimension('B')->setWidth(35); // Set width kolom B
    $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
    $sheet->getColumnDimension('D')->setWidth(27); // Set width kolom D
    $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
    $sheet->getColumnDimension('F')->setWidth(35); // Set width kolom F
    $sheet->getColumnDimension('G')->setWidth(20); // Set width kolom G
    $sheet->getColumnDimension('H')->setWidth(25); // Set width kolom H
    $sheet->getColumnDimension('I')->setWidth(25); // Set width kolom I
    $sheet->getColumnDimension('J')->setWidth(25); // Set width kolom J
    $sheet->getColumnDimension('K')->setWidth(45); // Set width kolom K

    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
    $sheet->getDefaultRowDimension()->setRowHeight(-1);
    // Set orientasi kertas jadi LANDSCAPE
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    // Set judul file excel nya
    $sheet->setTitle("Data Buku Tamu");
    ob_end_clean();
    // Proses file excel
    $filename = "Data Buku Tamu - " . strtoupper($this->input->get('namamarketing')) . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }


  public function addTamuManual()
  {
    grantAccessFor('all');

    $this->form_validation->set_rules('kegiatan_id', 'Kegiatan / Event', 'required|integer');
    $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim');
    $this->form_validation->set_rules('nomorwa', 'Nomor WhatsApp', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
    $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|trim');
    $this->form_validation->set_rules('instansi', 'Instansi', 'required|trim');
    $this->form_validation->set_rules('tipe_rs', 'Tipe Rumah Sakit', 'trim');
    $this->form_validation->set_rules('provinsi', 'Provinsi', 'required|trim');
    $this->form_validation->set_rules('kota', 'Kota', 'required|trim');
    $this->form_validation->set_rules('kebutuhan', 'Kebutuhan', 'trim');

    if ($this->form_validation->run() === false) {
      ajaxReturnDie('error', trim(strip_tags(validation_errors())));
      return;
    }

    $kegiatanId = (int) $this->input->post('kegiatan_id');
    if (!$this->md_bukutamu->isKegiatanExists($kegiatanId)) {
      ajaxReturnDie('error', 'Event tidak valid atau belum dibuat.');
      return;
    }

    $nama = trim((string) $this->input->post('nama', true));
    $nomorwa = trim((string) $this->input->post('nomorwa', true));
    $normalizedNoWa = $this->md_bukutamu->normalizeNoWa($nomorwa);

    if ($normalizedNoWa === '' || strlen($normalizedNoWa) < 10) {
      ajaxReturnDie('error', 'Nomor WhatsApp tidak valid.');
      return;
    }

    $ipAddress = (string) $this->input->ip_address();
    if ($ipAddress === '' || $ipAddress === '0.0.0.0') {
      $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    }
    $userAgent = substr((string) $this->input->user_agent(), 0, 255);

    $provinsi_id = $this->input->post('provinsi', true);
    $kota_id = $this->input->post('kota', true);
    $provinsi_nama = '';
    $kota_nama = '';

    if ($provinsi_id) {
      if (is_numeric($provinsi_id)) {
        $prov_row = $this->db->get_where('provinsi', ['id' => $provinsi_id])->row();
        $provinsi_nama = $prov_row ? $prov_row->nama : $provinsi_id;
      } else {
        $provinsi_nama = $provinsi_id;
      }
    }

    if ($kota_id) {
      if (is_numeric($kota_id)) {
        $kota_row = $this->db->get_where('kota', ['id' => $kota_id])->row();
        $kota_nama = $kota_row ? ($kota_row->tipe . ' ' . $kota_row->nama) : $kota_id;
      } else {
        $kota_nama = $kota_id;
      }
    }

    $data = [
      'nama' => $nama,
      'jabatan' => trim((string) $this->input->post('jabatan', true)),
      'nomorwa' => $normalizedNoWa,
      'email' => trim((string) $this->input->post('email', true)),
      'instansi' => trim((string) $this->input->post('instansi', true)),
      'tipe_rs' => trim((string) $this->input->post('tipe_rs', true)),
      'provinsi' => $provinsi_nama,
      'kota' => $kota_nama,
      'kebutuhan' => trim((string) $this->input->post('kebutuhan', true)),
      'kegiatan' => $kegiatanId,
    ];

    $saveResult = $this->md_bukutamu->saveWithDuplicateGuard($data, $ipAddress, $userAgent);

    if (($saveResult['status'] ?? '') === 'duplicate') {
      ajaxReturnDie('error', 'Data duplikat terdeteksi: nomor WhatsApp ini sudah terdaftar pada event yang sama.');
      return;
    }

    if (($saveResult['status'] ?? '') !== 'inserted') {
      ajaxReturnDie('error', 'Data gagal disimpan. Silakan coba lagi.');
      return;
    }

    // add log
    $aksi = 'Input Tamu Manual';
    $ket = 'Menambahkan tamu secara manual: ' . $nama . ' (' . $normalizedNoWa . ')';
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Data tamu manual berhasil ditambahkan', 'reload_table');
  }

  public function getTamuJson($id_encrypt)
  {
    grantAccessFor('all');
    $id = decrypt($id_encrypt);
    $row = $this->md_bukutamu->getBukuTamuById($id);
    if (!$row) {
      echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
      return;
    }

    echo json_encode([
      'status' => 'success',
      'data' => $row
    ]);
  }

  public function saveEditTamu()
  {
    grantAccessFor('all');

    $this->load->library('form_validation');
    $this->form_validation->set_rules('id_bukutamu', 'ID', 'required');
    $this->form_validation->set_rules('kegiatan_id', 'Kegiatan / Event', 'required|integer');
    $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim');
    $this->form_validation->set_rules('nomorwa', 'Nomor WhatsApp', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
    $this->form_validation->set_rules('jabatan', 'Jabatan', 'required|trim');
    $this->form_validation->set_rules('instansi', 'Instansi', 'required|trim');
    $this->form_validation->set_rules('tipe_rs', 'Tipe Rumah Sakit', 'trim');
    $this->form_validation->set_rules('provinsi', 'Provinsi', 'required|trim');
    $this->form_validation->set_rules('kota', 'Kota', 'required|trim');
    $this->form_validation->set_rules('kebutuhan', 'Kebutuhan', 'trim');

    if ($this->form_validation->run() === false) {
      ajaxReturnDie('error', trim(strip_tags(validation_errors())));
      return;
    }

    $id = decrypt($this->input->post('id_bukutamu'));
    $kegiatanId = (int) $this->input->post('kegiatan_id');
    if (!$this->md_bukutamu->isKegiatanExists($kegiatanId)) {
      ajaxReturnDie('error', 'Event tidak valid.');
      return;
    }

    $nama = trim((string) $this->input->post('nama', true));
    $nomorwa = trim((string) $this->input->post('nomorwa', true));
    $normalizedNoWa = $this->md_bukutamu->normalizeNoWa($nomorwa);

    if ($normalizedNoWa === '' || strlen($normalizedNoWa) < 10) {
      ajaxReturnDie('error', 'Nomor WhatsApp tidak valid.');
      return;
    }

    // Check duplicate except for this ID
    $existing = $this->md_bukutamu->findDuplicateByNoWaInEvent($kegiatanId, $normalizedNoWa);
    if ($existing && $existing->id_bukutamu != $id) {
      ajaxReturnDie('error', 'Nomor WhatsApp ini sudah terdaftar pada event yang sama.');
      return;
    }

    $provinsi_id = $this->input->post('provinsi', true);
    $kota_id = $this->input->post('kota', true);
    $provinsi_nama = '';
    $kota_nama = '';

    if ($provinsi_id) {
      if (is_numeric($provinsi_id)) {
        $prov_row = $this->db->get_where('provinsi', ['id' => $provinsi_id])->row();
        $provinsi_nama = $prov_row ? $prov_row->nama : $provinsi_id;
      } else {
        $provinsi_nama = $provinsi_id;
      }
    }

    if ($kota_id) {
      if (is_numeric($kota_id)) {
        $kota_row = $this->db->get_where('kota', ['id' => $kota_id])->row();
        $kota_nama = $kota_row ? ($kota_row->tipe . ' ' . $kota_row->nama) : $kota_id;
      } else {
        $kota_nama = $kota_id;
      }
    }

    $data = [
      'nama' => $nama,
      'jabatan' => trim((string) $this->input->post('jabatan', true)),
      'nomorwa' => $normalizedNoWa,
      'email' => trim((string) $this->input->post('email', true)),
      'instansi' => trim((string) $this->input->post('instansi', true)),
      'tipe_rs' => trim((string) $this->input->post('tipe_rs', true)),
      'provinsi' => $provinsi_nama,
      'kota' => $kota_nama,
      'kebutuhan' => trim((string) $this->input->post('kebutuhan', true)),
      'kegiatan' => $kegiatanId,
    ];

    $this->md_bukutamu->updateBukuTamu($id, $data);

    // add log
    $aksi = 'Edit Tamu';
    $ket = 'Mengubah data tamu: ' . $nama . ' (' . $normalizedNoWa . ')';
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Data tamu berhasil diperbarui', 'reload_table');
  }

  public function deleteTamu($id_encrypt)
  {
    grantAccessFor('all');
    $id = decrypt($id_encrypt);
    $row = $this->md_bukutamu->getBukuTamuById($id);
    if (!$row) {
      ajaxReturnDie('error', 'Data tidak ditemukan.');
      return;
    }

    $this->md_bukutamu->deleteBukuTamu($id);

    // add log
    $aksi = 'Hapus Tamu';
    $ket = 'Menghapus data tamu: ' . $row->nama . ' (' . $row->nomorwa . ')';
    addlog($aksi, $ket);

    ajaxReturnDie('success', 'Data tamu berhasil dihapus', 'reload_table');
  }
}
