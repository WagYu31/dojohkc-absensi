#!/bin/bash
# Script Update Production - Dojo HKC
# Jalankan: bash update.sh

cd ~/dojohkc.com

REPO="https://raw.githubusercontent.com/WagYu31/dojohkc-absensi/main"

echo "=== MULAI UPDATE PRODUCTION ==="

wget -O sw-admin/sw-mod/absensi/absensi.php "$REPO/sw-admin/sw-mod/absensi/absensi.php"
wget -O sw-admin/sw-mod/absensi/scripts.js "$REPO/sw-admin/sw-mod/absensi/scripts.js"
wget -O sw-admin/sw-mod/absensi/sw-datatable.php "$REPO/sw-admin/sw-mod/absensi/sw-datatable.php"
wget -O sw-admin/sw-mod/karyawan/proses.php "$REPO/sw-admin/sw-mod/karyawan/proses.php"
wget -O sw-admin/sw-mod/laporan-harian/print.php "$REPO/sw-admin/sw-mod/laporan-harian/print.php"
wget -O sw-admin/sw-mod/laporan-harian/proses.php "$REPO/sw-admin/sw-mod/laporan-harian/proses.php"
wget -O sw-admin/sw-mod/laporan-spp/print.php "$REPO/sw-admin/sw-mod/laporan-spp/print.php"
wget -O sw-admin/sw-mod/laporan-spp/sw-proses.php "$REPO/sw-admin/sw-mod/laporan-spp/sw-proses.php"
wget -O sw-mod/pembayaran-spp.php "$REPO/sw-mod/pembayaran-spp.php"
wget -O sw-mod/pembayaran-success.php "$REPO/sw-mod/pembayaran-success.php"

echo "=== SEMUA FILE BERHASIL DI-UPDATE! ==="
