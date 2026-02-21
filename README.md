# 📋 Sistem Absensi DOJO HKC

Aplikasi sistem absensi berbasis web untuk **DOJO HKC** (Hapkido Karate Club) dengan fitur verifikasi wajah otomatis, GPS radius, manajemen shift, dan SPP.

🌐 **Live:** [dojohkc.com](https://dojohkc.com)

---

## ✨ Fitur Utama

### 🎥 Pendaftaran Wajah Otomatis (Auto Face Scan)
- Kamera langsung aktif saat halaman `/wajah` dibuka
- **Oval progress ring** mengisi (kuning → hijau) saat wajah terdeteksi
- **Auto-capture** otomatis setelah wajah tahan 3 detik di dalam oval
- **Auto-save** langsung ke server tanpa tombol manual
- Deteksi wajah via pixel brightness analysis (tanpa library ML eksternal)
- Flip kamera (depan ↔ belakang)
- Fallback ke file picker jika `getUserMedia` tidak tersedia

### 📍 Absensi dengan Verifikasi Lokasi
- Absensi masuk & pulang berbasis GPS
- Validasi radius jarak dari lokasi kantor
- Dukungan multi-lokasi (berbeda per shift)
- Riwayat absensi harian & bulanan

### 👤 Manajemen Karyawan
- Multi-level user: **Admin**, **Sensei**, **Bendahara**
- Profil karyawan dengan foto wajah terverifikasi
- Reset password via email
- Manajemen izin & cuti

### ⏰ Manajemen Shift Kerja
- Konfigurasi shift dengan jam masuk/pulang
- Kalkulasi keterlambatan otomatis
- Laporan rekap per shift

### 💰 SPP (Sumbangan Pembinaan Pendidikan)
- Pencatatan pembayaran SPP per siswa
- Riwayat pembayaran & laporan tagihan
- Export laporan

### 🖼️ Galeri
- Upload dan manajemen galeri foto kegiatan
- Lightbox preview

---

## 🛠️ Teknologi

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP Native |
| Database | MySQL |
| Frontend | Bootstrap 4, jQuery 3.4 |
| Camera | `getUserMedia` API (WebRTC) |
| Tabel | DataTables |
| Alert | SweetAlert |
| Icons | Ionicons, FontAwesome |
| Container | Docker + Nginx |

---

## 🚀 Instalasi

### Cara 1: Docker (Direkomendasikan)

```bash
# Clone repository
git clone https://github.com/WagYu31/dojohkc-absensi.git
cd dojohkc-absensi

# Jalankan dengan Docker
docker-compose up -d

# Import database
docker exec -i absensi-db mysql -u root -proot absensi < Database/absensi.sql
```

Akses di: `http://localhost:8080`

### Cara 2: Manual (XAMPP/LAMP)

```bash
# Clone repository
git clone https://github.com/WagYu31/dojohkc-absensi.git

# Salin ke htdocs/www
cp -r dojohkc-absensi /path/to/htdocs/

# Import database via phpMyAdmin atau CLI
mysql -u root -p nama_database < Database/absensi.sql
```

---

## ⚙️ Konfigurasi

Edit file `sw-library/sw-config.php`:

```php
$DB_HOST   = 'localhost';      // Host database
$DB_USER   = 'username';       // User database
$DB_PASSWD = 'password';       // Password database
$DB_NAME   = 'nama_database';  // Nama database
```

Untuk Docker, host sudah dikonfigurasi otomatis via `docker-compose.yml`.

---

## 📱 Alur Penggunaan

### Pendaftaran Wajah
1. Login ke aplikasi
2. Buka menu **Profil → Daftar Wajah**
3. Izinkan akses kamera saat diminta browser
4. **Arahkan wajah ke dalam oval** — ring hijau akan mengisi otomatis
5. Setelah 3 detik wajah di oval → foto otomatis diambil & disimpan

### Absensi Harian
1. Buka halaman **Absensi**
2. Pastikan GPS aktif
3. Tap **Absen Masuk** atau **Absen Pulang**
4. Sistem validasi lokasi & mencatat waktu

---

## 📁 Struktur Folder

```
├── action/              # AJAX handlers (absensi, save wajah, dll)
├── Database/            # SQL schema & migration
├── sw-admin/            # Panel admin
├── sw-library/          # Config & functions library
├── sw-mod/              # Halaman user (wajah, profil, dll)
│   └── sw-assets/       # CSS, JS, fonts, images
├── docker-compose.yml   # Docker configuration
└── Dockerfile
```

---

## 🔧 Deployment ke Production

```bash
# Di server production
cd /path/to/dojohkc.com
git pull origin main

# Verifikasi
php -l sw-mod/wajah.php
```

---

## 📋 Changelog

### v3.x (Terbaru)
- ✅ **Auto Face Scan** — wajah terdeteksi otomatis, tidak perlu tombol
- ✅ **SVG Progress Ring** — animasi oval hijau saat scanning wajah
- ✅ **Face Save Fix** — perbaikan penyimpanan foto wajah ke database
- ✅ **Docker Support** — containerization dengan MySQL & Nginx
- ✅ **SPP Module** — modul pembayaran SPP
- ✅ **Shift Management** — manajemen shift kerja multi-lokasi

### v2.x
- Absensi GPS dengan radius
- Multi-level user
- Laporan kehadiran
- Galeri foto

---

## ⚠️ Catatan Penting

- **Pendaftaran wajah** memerlukan **HTTPS** (browser policy untuk `getUserMedia`)
- Pastikan akses kamera telah diizinkan di browser
- Data foto wajah disimpan sebagai Base64 di kolom `MEDIUMTEXT` pada tabel `employees`
- File `sw-library/sw-config.php` **tidak boleh** di-commit ke repository publik

---

## 📄 Lisensi

**Private** — DOJO HKC © 2024–2025. Seluruh hak cipta dilindungi.
