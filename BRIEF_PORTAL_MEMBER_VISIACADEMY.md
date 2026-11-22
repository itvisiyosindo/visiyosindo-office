# BRIEF & SPESIFIKASI PROYEK: PORTAL MEMBER VISI ACADEMY (VISIYOSINDO.ID)

## 1. Latar Belakang & Alur Bisnis
PT Visi Yosindo Medikal memiliki program referral pemasaran untuk produk alat kesehatan (Mobile X-Ray, Apron Pb, USG, dll) melalui unit pelatihannya (Visi Academy).
Member (dokter, radiografer, fisikawan medis, staf RS, alumni pelatihan) dapat mendaftar mandiri, mendapatkan kode member unik, dan menginfokan kebutuhan alat kesehatan di rumah sakit/klinik mereka.
Jika informasi kebutuhan tersebut berhasil terjual (Deal) oleh tim sales internal, member mendapatkan komisi uang tunai.

## 2. Struktur Dua Aplikasi (Satu Server & Satu Database)
1. **Aplikasi Admin Internal (Office ERP):**
   - Lokasi: `D:\vsiyosindo office` (Domain: `office.visiyosindo.id`)
   - Fungsi: Admin/Sales memantau seluruh member, mem-follow up prospek, mengubah status deal (Pending -> Deal / Batal), dan mencairkan komisi.
2. **Aplikasi Portal Member (Project Ini):**
   - Lokasi: `F:\visiyosindo member deal` (Domain: `visiyosindo.id`)
   - Fungsi: Khusus untuk member luar (Daftar mandiri, Login, Cek Saldo Komisi, Input Kebutuhan Alkes Baru, Tracking Status Deal, dan Kelola Rekening Bank).

## 3. Konfigurasi Database (Shared DB)
Kedua project menggunakan database yang sama di server:
- **Host:** `office.visiyosindo.id` (atau `localhost` jika berjalan di server lokal)
- **Database:** `visiyosi_office`
- **Username:** `visiyosi_root`
- **Password:** `q%MDa{uYlcyv`

### Skema Tabel yang Digunakan:
1. **`va_member` (Data Akun Member):**
   - `id_member` (INT, PK, Auto Increment)
   - `kode_member` (VARCHAR 50, UNIQUE) -> Format auto: `VA-001`, `VA-002`, dst.
   - `nama_member` (VARCHAR 255)
   - `no_telp` (VARCHAR 20) -> Digunakan untuk login via No. WhatsApp
   - `email` (VARCHAR 100)
   - `password` (VARCHAR 255) -> Hash password / bcrypt
   - `institusi_asal` (VARCHAR 255) -> Asal RS/Klinik/Instansi
   - `nama_bank` (VARCHAR 50) -> BCA, Mandiri, BRI, dll
   - `no_rekening` (VARCHAR 50)
   - `atas_nama_rekening` (VARCHAR 100)
   - `status` (ENUM: 'aktif', 'nonaktif') DEFAULT 'aktif'
   - `created_at` (DATETIME)

2. **`va_member_deal` (Data Kebutuhan Alkes & Komisi):**
   - `id_deal` (INT, PK, Auto Increment)
   - `id_member` (INT, FK -> va_member.id_member)
   - `nama_produk` (VARCHAR 255) -> Nama alkes yang dibutuhkan RS
   - `estimasi_nilai` (DECIMAL 15,2) -> Nilai transaksi
   - `persen_komisi` (DECIMAL 5,2) -> Persentase komisi (estimasi awal dari portal dikunci di `2.00%`; persentase & nominal final diverifikasi/disesuaikan di Office saat status diubah ke `Deal`)
   - `nominal_komisi` (DECIMAL 15,2) -> Nilai komisi terhitung (`estimasi_nilai * persen_komisi / 100`)
   - `status_deal` (ENUM: 'Pending', 'Deal', 'Batal') DEFAULT 'Pending'
   - `catatan` (TEXT) -> Spesifikasi atau info PIC RS
   - `created_at` (DATETIME)

3. **`va_katalog_produk` (Katalog Produk Alkes & Brosur):**
   - `id_produk` (`INT UNSIGNED`, PK, Auto Increment)
   - `nama_produk` (`VARCHAR(200) NOT NULL`) -> Nama produk alkes
   - `kategori` (`VARCHAR(100) NOT NULL DEFAULT 'Radiologi'`) -> Kategori produk
   - `deskripsi_singkat` (`TEXT NULL`) -> Ringkasan spesifikasi / keunggulan produk
   - `estimasi_harga` (`DECIMAL(15,2) NOT NULL DEFAULT 0.00`) -> Estimasi harga (`0.00` tampil sebagai On Request)
   - `url_gambar` (`VARCHAR(255) NULL`) -> Path relatif (`uploads/katalog/...`) atau URL eksternal (`https://...`)
   - `url_brosur` (`VARCHAR(255) NULL`) -> Path relatif (`uploads/brosur/...`) atau URL eksternal (`https://...`)
   - `status` (`ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif'`) -> Produk `aktif` ditampilkan di portal (`katalog.php`)
   - `urutan` (`INT NOT NULL DEFAULT 0`) -> Urutan tampil di portal
   - `created_at` (`DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP`)
   - `updated_at` (`DATETIME NULL ON UPDATE CURRENT_TIMESTAMP`)
   - **Kontrak path file gambar & brosur:** Portal otomatis meresolve `url_gambar` dan `url_brosur` — jika diawali `https://` / `http://` dipakai langsung, jika path relatif (misal `uploads/katalog/usg.jpg` atau `uploads/brosur/usg.pdf`) otomatis digabungkan dengan `https://office.visiyosindo.id/<path>`.

## 4. Fitur-Fitur yang Perlu Dibangun di Project Portal Member:
1. **Halaman Autentikasi:**
   - Halaman **Login** (menggunakan No. WhatsApp / Email dan Password).
   - Halaman **Register** (Daftar Mandiri: Nama, No WA, Email, RS Asal, Password -> Otomatis generate `kode_member`).
2. **Dashboard Member:**
   - Kartu Ringkasan: Total Komisi yang Sudah Deal (Siap Cair), Total Prospek Berjalan (Pending).
   - Profil Member: Menampilkan Kode Member, Nama, dan form Rekening Bank untuk pencairan komisi.
3. **Form "Ajukan Kebutuhan Alkes Baru":**
   - Member menginput produk alkes yang dibutuhkan instansi mereka dan catatan kebutuhan.
   - Status awal otomatis `Pending`.
4. **Tabel Tracking Riwayat Prospek & Komisi Milik Member:**
   - Hanya menampilkan prospek milik member yang sedang login.
   - Menampilkan status transparan: `Pending (Sedang diproses sales)`, `Deal (Berhasil)`, atau `Batal`.
