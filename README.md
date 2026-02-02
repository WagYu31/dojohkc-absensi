# Sistem Absensi DOJO HKC

Aplikasi sistem absensi berbasis web untuk DOJO HKC (Hapkido Karate Club).

## Fitur

- ✅ Absensi karyawan dengan foto
- ✅ Manajemen lokasi dengan radius GPS
- ✅ Manajemen shift kerja
- ✅ Laporan kehadiran harian/bulanan
- ✅ Manajemen cuti dan izin
- ✅ Reset password via email
- ✅ Multi-level user (Admin, Sensei, Bendahara)

## Teknologi

- PHP Native
- MySQL
- Bootstrap
- jQuery
- DataTables

## Instalasi

1. Clone repository ini
2. Import database dari folder `Database/`
3. Konfigurasi database di `sw-library/sw-config.php`
4. Akses via browser

## Konfigurasi

Edit file `sw-library/sw-config.php`:

```php
$DB_HOST = 'localhost';
$DB_USER = 'username';
$DB_PASSWD = 'password';
$DB_NAME = 'nama_database';
```

## Lisensi

Private - DOJO HKC © 2024
