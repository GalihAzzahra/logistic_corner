# 📦 SISTEM INFORMASI LOGISTIC CORNER
**Politeknik Negeri Lampung**

---

## 📋 DAFTAR ISI
1. [Tentang Aplikasi](#tentang-aplikasi)
2. [Fitur Utama](#fitur-utama)
3. [Persyaratan Sistem](#persyaratan-sistem)
4. [Panduan Instalasi](#panduan-instalasi)
5. [Struktur Database](#struktur-database)
6. [Cara Menggunakan](#cara-menggunakan)
7. [API Endpoint (untuk Laravel)](#api-endpoint)
8. [Troubleshooting](#troubleshooting)

---

## 🎯 TENTANG APLIKASI

Sistem Informasi Logistic Corner adalah aplikasi berbasis web untuk mengelola paket kiriman di Politeknik Negeri Lampung. Sistem ini menggantikan proses pencatatan manual dengan sistem digital yang terintegrasi dengan notifikasi otomatis.

**Dikembangkan dengan:**
- Frontend: HTML5, CSS3, JavaScript (ES6+)
- Backend: PHP/Laravel (rekomendasi)
- Database: MySQL
- Framework CSS: Tailwind CSS

---

## ✨ FITUR UTAMA

### 1. **Manajemen Paket**
   - Input paket baru dengan validasi lengkap
   - Pencarian dan filter paket
   - Status tracking real-time
   - Riwayat pengambilan paket

### 2. **Sistem Notifikasi Otomatis**
   - Notifikasi kedatangan paket (WhatsApp/Email)
   - Pengingat otomatis untuk paket yang belum diambil
   - Notifikasi konfirmasi pengambilan

### 3. **Perhitungan Denda Otomatis**
   - Rp 1.000/hari setelah hari pertama
   - Mengecualikan hari Minggu dan tanggal merah
   - Laporan denda terakumulasi

### 4. **Dashboard & Statistik**
   - Total paket masuk
   - Status paket (belum/sudah diambil)
   - Total denda
   - Grafik dan visualisasi data

### 5. **Multi-User & Role Management**
   - Admin: Akses penuh
   - Petugas: Input & serah terima paket
   - Mahasiswa/Dosen: Tracking paket sendiri

### 6. **Activity Logs**
   - Pencatatan semua aktivitas sistem
   - Audit trail untuk keamanan
   - Riwayat perubahan data

---

## 💻 PERSYARATAN SISTEM

### Software yang Dibutuhkan:
- **XAMPP** (v8.0 atau lebih baru)
  - PHP 8.0+
  - MySQL 5.7+ / MariaDB 10.3+
  - Apache 2.4+
- **Web Browser** (Chrome, Firefox, Edge)
- **Text Editor** (VS Code, Sublime Text, Notepad++)

### Hardware Minimum:
- Processor: Intel Core i3 / AMD Ryzen 3
- RAM: 4GB
- Storage: 500MB ruang kosong
- Koneksi Internet (untuk CDN Tailwind CSS)

---

## 🚀 PANDUAN INSTALASI

### LANGKAH 1: INSTALL XAMPP

1. **Download XAMPP:**
   - Kunjungi: https://www.apachefriends.org/
   - Download versi untuk Windows/Mac/Linux
   - Install dengan setting default

2. **Jalankan XAMPP Control Panel:**
   ```
   C:\xampp\xampp-control.exe
   ```

3. **Start Services:**
   - Klik tombol "Start" pada **Apache**
   - Klik tombol "Start" pada **MySQL**

### LANGKAH 2: SETUP DATABASE

1. **Buka phpMyAdmin:**
   - Browser: `http://localhost/phpmyadmin`
   - User: `root`
   - Password: (kosongkan)

2. **Import Database:**
   - Klik tab "Import"
   - Klik "Choose File"
   - Pilih file `database.sql`
   - Klik "Go" / "Kirim"
   - Tunggu hingga proses selesai

3. **Verifikasi Database:**
   - Cek database `logistic_corner` sudah terbuat
   - Pastikan ada 7 tabel:
     - users
     - packages
     - notifications
     - holidays
     - settings
     - activity_logs
     - v_dashboard_stats (view)
     - v_packages_with_fine (view)

### LANGKAH 3: SETUP PROJECT

#### **OPSI A: Standalone HTML/JavaScript (Simple)**

1. **Copy semua file project ke folder:**
   ```
   C:\xampp\htdocs\logistic-corner\
   ```

2. **Struktur Folder:**
   ```
   logistic-corner/
   ├── index.html
   ├── admin-dashboard.html
   ├── user-tracking.html
   ├── login.html
   ├── database.sql
   ├── README.md
   └── config.php (untuk koneksi database)
   ```

3. **Buat file `config.php`:**
   ```php
   <?php
   $host = 'localhost';
   $dbname = 'logistic_corner';
   $username = 'root';
   $password = '';

   try {
       $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch(PDOException $e) {
       die("Connection failed: " . $e->getMessage());
   }
   ?>
   ```

4. **Akses aplikasi:**
   ```
   http://localhost/logistic-corner/
   ```

#### **OPSI B: Laravel (Recommended untuk Production)**

1. **Install Composer:**
   - Download: https://getcomposer.org/
   - Install dengan setting default

2. **Install Laravel:**
   ```bash
   cd C:\xampp\htdocs
   composer create-project laravel/laravel logistic-corner
   cd logistic-corner
   ```

3. **Setup Environment:**
   - Rename `.env.example` menjadi `.env`
   - Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=logistic_corner
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Import Database:**
   - Import `database.sql` seperti langkah 2 di atas

6. **Jalankan Server:**
   ```bash
   php artisan serve
   ```
   - Akses: `http://localhost:8000`

### LANGKAH 4: KONFIGURASI TAMBAHAN

1. **Setting Timezone (PHP):**
   - Edit: `C:\xampp\php\php.ini`
   - Cari: `;date.timezone =`
   - Ubah menjadi: `date.timezone = Asia/Jakarta`
   - Restart Apache

2. **Enable Extension (jika perlu):**
   - Buka `php.ini`
   - Hapus `;` pada:
     ```ini
     extension=mysqli
     extension=pdo_mysql
     extension=curl
     extension=mbstring
     ```

3. **Setting MySQL:**
   - Edit: `C:\xampp\mysql\bin\my.ini`
   - Pastikan:
     ```ini
     max_allowed_packet=64M
     innodb_buffer_pool_size=256M
     ```

---

## 🗄️ STRUKTUR DATABASE

### Tabel Utama:

#### **1. users** - Data Pengguna
- `id`: Primary Key
- `username`: Username login (unique)
- `password`: Password (hashed)
- `nama_lengkap`: Nama lengkap user
- `nim_nip`: NIM/NIP (unique)
- `role`: Role user (admin/petugas/mahasiswa)

#### **2. packages** - Data Paket
- `id`: Primary Key
- `nomor_resi`: Nomor resi (unique)
- `nama_penerima`: Nama penerima
- `nim_nip`: NIM/NIP penerima
- `kurir`: Nama kurir
- `status`: Status paket (belum_diambil/sudah_diambil)
- `tanggal_masuk`: Tanggal paket masuk
- `tanggal_diambil`: Tanggal paket diambil

#### **3. notifications** - Notifikasi
- `id`: Primary Key
- `package_id`: Foreign key ke packages
- `type`: Tipe notifikasi (arrival/reminder/taken)
- `message`: Isi pesan
- `is_read`: Status baca

#### **4. holidays** - Hari Libur
- `id`: Primary Key
- `tanggal`: Tanggal libur (unique)
- `keterangan`: Keterangan hari libur

#### **5. settings** - Pengaturan Sistem
- `setting_key`: Key pengaturan (unique)
- `setting_value`: Nilai pengaturan
- `description`: Deskripsi

---

## 📖 CARA MENGGUNAKAN

### LOGIN PERTAMA KALI

**Akun Admin Default:**
- Username: `admin`
- Password: `admin123`

**Akun Petugas:**
- Username: `petugas1`
- Password: `admin123`

⚠️ **PENTING:** Segera ganti password setelah login pertama!

### WORKFLOW SISTEM

#### **A. Untuk Admin/Petugas:**

1. **Input Paket Baru**
   - Login ke dashboard
   - Klik tab "Input Paket Baru"
   - Isi form:
     - Nama Penerima
     - NIM/NIP
     - Nomor Resi
     - Kurir
     - Berat Paket
     - No. WhatsApp (opsional)
     - Tanggal Masuk
     - Catatan
   - Klik "Simpan Paket & Kirim Notifikasi"
   - Sistem otomatis kirim notifikasi ke penerima

2. **Mengelola Paket**
   - Tab "Daftar Paket"
   - Lihat semua paket yang masuk
   - Fitur:
     - 🔍 Cari paket (nama/resi/NIM)
     - 🎯 Filter status
     - 👁️ Lihat detail
     - ✅ Tandai sudah diambil
     - 🔔 Kirim pengingat
     - 🗑️ Hapus paket

3. **Serah Terima Paket**
   - Saat mahasiswa datang ambil paket
   - Cari paket di "Daftar Paket"
   - Verifikasi identitas (KTM/KTP)
   - Klik tombol "✅" (Tandai Diambil)
   - Jika ada denda, konfirmasi pembayaran
   - Sistem otomatis catat waktu pengambilan

#### **B. Untuk Mahasiswa/Dosen:**

1. **Tracking Paket**
   - Buka: `http://localhost/logistic-corner/user-tracking.html`
   - Masukkan Nomor Resi ATAU NIM
   - Klik "Cari"
   - Lihat status paket:
     - ✅ Sudah Diambil
     - ⏰ Belum Diambil
     - 💰 Info Denda (jika ada)

2. **Cek Notifikasi**
   - Terima notifikasi via WhatsApp/Email saat:
     - Paket tiba
     - Pengingat (jika belum diambil >3 hari)
     - Konfirmasi pengambilan

---

## 🔌 API ENDPOINT (untuk Laravel)

### Authentication
```
POST   /api/login
POST   /api/logout
POST   /api/register
```

### Packages
```
GET    /api/packages              # List semua paket
POST   /api/packages              # Input paket baru
GET    /api/packages/{id}         # Detail paket
PUT    /api/packages/{id}         # Update paket
DELETE /api/packages/{id}         # Hapus paket
POST   /api/packages/{id}/take    # Tandai diambil
POST   /api/packages/{id}/remind  # Kirim pengingat
```

### Tracking
```
GET    /api/track/{resi}          # Track by resi
GET    /api/track/nim/{nim}       # Track by NIM
```

### Notifications
```
GET    /api/notifications         # List notifikasi
PUT    /api/notifications/{id}/read  # Tandai dibaca
```

### Dashboard
```
GET    /api/dashboard/stats       # Statistik dashboard
```

### Contoh Response (JSON):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nomor_resi": "JNE1234567890",
    "nama_penerima": "Budi Santoso",
    "status": "belum_diambil",
    "denda": 4000
  },
  "message": "Data berhasil diambil"
}
```

---

## 🔧 TROUBLESHOOTING

### Problem 1: "Error establishing database connection"
**Solusi:**
1. Pastikan MySQL di XAMPP sudah running
2. Cek konfigurasi database di `config.php` atau `.env`
3. Verifikasi username/password database

### Problem 2: "Access denied for user 'root'@'localhost'"
**Solusi:**
1. Buka phpMyAdmin
2. Klik "User accounts"
3. Edit user 'root'
4. Set password atau kosongkan
5. Update di `config.php`

### Problem 3: "Port 80 sudah digunakan"
**Solusi:**
1. Stop Skype/IIS
2. Atau ubah port Apache:
   - Edit: `C:\xampp\apache\conf\httpd.conf`
   - Cari: `Listen 80`
   - Ubah: `Listen 8080`

### Problem 4: "Notifikasi tidak terkirim"
**Solusi:**
1. Cek koneksi internet
2. Verifikasi API WhatsApp Gateway (jika ada)
3. Cek setting di tabel `settings`

### Problem 5: "Tampilan berantakan"
**Solusi:**
1. Pastikan koneksi internet aktif (untuk load Tailwind CSS dari CDN)
2. Clear browser cache (Ctrl+F5)
3. Coba browser lain

### Problem 6: "Denda tidak terhitung"
**Solusi:**
1. Cek tabel `holidays` sudah terisi
2. Verifikasi setting `denda_per_hari` di tabel `settings`
3. Pastikan timezone PHP sudah benar (Asia/Jakarta)

---

## 📞 SUPPORT & KONTAK

**Developed by:**
- Politeknik Negeri Lampung
- Jurusan Teknik Informatika

**Email:** support@polinela.ac.id  
**Website:** https://polinela.ac.id

---

## 📝 CHANGELOG

### Version 1.0.0 (2024)
- ✅ Initial release
- ✅ Fitur input paket
- ✅ Sistem notifikasi otomatis
- ✅ Perhitungan denda otomatis
- ✅ Tracking paket
- ✅ Dashboard statistik

---

## 📄 LICENSE

Copyright © 2024 Politeknik Negeri Lampung  
All Rights Reserved.

---

## 🎓 CREDITS

Sistem ini dikembangkan sebagai bagian dari tugas akhir/penelitian untuk meningkatkan layanan Logistic Corner di Politeknik Negeri Lampung.

**Referensi:**
- Teyfulati et al. (2023) - Sistem Arsip Paket Shopee Express
- S. Pini, Tarmuji, R. Dayat (2022) - Integrasi Teknologi Logistik
- Paramytha & Darma (2025) - Smart Box IoT
- Zahra et al. (2024) - Kualitas Layanan Logistik

---

**🚀 Selamat Menggunakan Sistem Logistic Corner! 🚀**
