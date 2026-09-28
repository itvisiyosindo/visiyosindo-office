# Panduan Lengkap: Integrasi Modul Audit Khusus User ID 58 (General Affair)

Panduan teknis langkah demi langkah dari awal sampai modul **Audit Belanja PBOK & Pembelian Aset (PPA)** aktif dan dapat diakses oleh **User ID 58 (General Affair / GA)** pada `https://office.visiyosindo.id/dashboard_kepegawaian`.

---

## 📌 RANGKUMAN TAHAPAN (LOKASI SAMPAI AKTIF)

```
[Komputer Lokal]                                  [Server office.visiyosindo.id]
C:\Users\Programmer\...\audit-pekerjaan-karyawan ──> Upload via cPanel / FTP ──> /public_html/audit/
                                                                                         │
                                                                                         ▼
                                                  Otorisasi User ID 58 (General Affair)  │
                                                  https://office.visiyosindo.id/dashboard_kepegawaian
```

---

## 🛠️ LANGKAH 1: Siapkan File Modul Audit dari Komputer Lokal
Seluruh berkas siap unggah tersimpan di folder:  
`C:\Users\Programmer\.gemini\antigravity-ide\scratch\audit-pekerjaan-karyawan\`

File yang wajib diunggah:
- `index.html` *(Dashboard Audit PBOK & PPA Aset)*
- `styles.css` *(Desain UI Visi Yosindo)*
- `app.js` *(Logika data & User ID 58 profile)*
- `excel-generator.js` *(Engine Ekspor 5-Sheet Excel)*

---

## ☁️ LANGKAH 2: Unggah (Upload) File ke Server Hosting Website Office
1. Buka cPanel / File Manager `office.visiyosindo.id`.
2. Buka direktori utama `public_html`.
3. Buat folder baru bernama `audit`.
4. Unggah 4 berkas dari Langkah 1 ke dalam folder `public_html/audit/`.
5. Uji akses URL di browser:  
   👉 `https://office.visiyosindo.id/audit/index.html`

---

## 🔒 LANGKAH 3: Pengecekan Hak Akses Khusus User ID 58 (General Affair)

Pada file backend kodingan `dashboard_kepegawaian` (PHP / Laravel / CodeIgniter / Native), tambahkan kondisi otorisasi khusus untuk **User ID 58** atau **Role General Affair (GA)**:

### Contoh Kodingan PHP Backend di `dashboard_kepegawaian`:
```php
<?php
// Ambil Session User ID yang sedang login
$current_user_id = $_SESSION['user_id']; // Misal: 58
$user_role       = $_SESSION['role_name']; // Misal: 'General Affair'

// Cek Otorisasi Khusus User ID 58 atau Role General Affair
if ($current_user_id == 58 || $user_role == 'General Affair' || $user_role == 'Admin') {
    $has_audit_access = true;
} else {
    $has_audit_access = false;
}
?>
```

---

## 🖥️ LANGKAH 4: Menyematkan Tampilan Audit pada Halaman dashboard_kepegawaian

Di dalam tampilan HTML/PHP halaman `dashboard_kepegawaian`:

```html
<?php if ($has_audit_access): ?>
    <!-- HANYA MUNCUL DAN BISA DIAKSES OLEH USER ID 58 (GENERAL AFFAIR) -->
    <div class="card card-primary card-outline mt-3">
        <div class="card-header bg-navy">
            <h3 class="card-title text-white">
                <i class="fas fa-clipboard-check text-cyan"></i> Modul Audit Internal PBOK & PPA Aset — General Affair (ID: 58)
            </h3>
        </div>
        <div class="card-body p-0">
            <iframe 
                src="https://office.visiyosindo.id/audit/index.html?user_id=58&role=GeneralAffair" 
                style="width: 100%; height: 85vh; border: none;"
                title="Modul Audit PBOK PPA Visi Yosindo Medikal">
            </iframe>
        </div>
    </div>
<?php endif; ?>
```

---

## 🧪 LANGKAH 5: Pengujian & Verifikasi (User ID 58)
1. Login ke `https://office.visiyosindo.id/` menggunakan akun **User ID 58 (General Affair)**.
2. Buka menu **Dashboard Kepegawaian** (`/dashboard_kepegawaian`).
3. Modul **Audit Belanja PBOK & Pembelian Aset (PPA)** akan tampil lengkap dengan identitas User ID 58.
4. Klik tombol hijau **"Ekspor Laporan Excel (.xlsx)"** untuk mengunduh laporan 5-sheet resmi siap diserahkan ke atasan.
